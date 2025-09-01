<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.9.9 2025-01-25
 *
 * @package     iCagenda.Site
 * @link        https://www.joomlic.com
 *
 * @author      Cyril Rezé
 * @copyright   (c) 2012-2025 Cyril Rezé / JoomliC. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 *
 * @since       3.6
 *----------------------------------------------------------------------------
*/

defined('_JEXEC') or die;

use Joomla\Registry\Registry;

/**
 * iCagenda Component Event Model
 *
 */
class iCagendaModelEvent extends JModelItem
{
	/**
	 * Model context string.
	 *
	 * @var     string
	 */
	protected $_context = 'com_icagenda.event';

	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @return  void
	 *
	 * @since   3.6.0
	 */
	protected function populateState()
	{
		$app    = JFactory::getApplication('site');
		$jinput = $app->input;

		// Load state from the request.
		$pk = $jinput->getInt('id');
		$this->setState('event.id', $pk);

		$offset = $jinput->getUInt('limitstart');
		$this->setState('list.offset', $offset);

		// Load the parameters.
		$params = $app->getParams();
		$this->setState('params', $params);

		// TODO: Tune these values based on other permissions.
		$user = JFactory::getUser();

		if ( ! $user->authorise('core.edit.state', 'com_icagenda') && ! $user->authorise('core.edit', 'com_icagenda'))
		{
			$this->setState('filter.published', 1);
			$this->setState('filter.archived', 2);
		}

		$this->setState('filter.language', JLanguageMultilang::isEnabled());
	}

	/**
	 * Method to get event data.
	 *
	 * @param   integer  $pk  The id of the event.
	 *
	 * @return  mixed  Menu item data object on success, false on failure.
	 *
	 * @since   3.6.0
	 */
	public function getItem($pk = null)
	{
		$app    = JFactory::getApplication();
		$jinput = $app->input;
		$user   = JFactory::getUser();

		$pk = ( ! empty($pk)) ? $pk : (int) $this->getState('event.id');

		if ($this->_item === null)
		{
			$this->_item = array();
		}

		if ( ! isset($this->_item[$pk]))
		{
			try
			{
				$db    = $this->getDbo();
				$query = $db->getQuery(true)
					->select(
						$this->getState(
							'item.select', 'e.*, ' .
							'e.name AS contact_name, e.email AS contact_email'
						)
					);
				$query->from('#__icagenda_events AS e');
				$query->where('e.state = 1');

				// Join over the category
				$query->select('c.id AS cat_id, c.title AS cat_title, c.color AS cat_color, c.desc AS cat_desc')
					->join('LEFT', '#__icagenda_category AS c ON c.id = e.catid')
					->where('c.state = 1');

				// Filter by language
				if ($this->getState('filter.language'))
				{
					$query->where('e.language in (' . $db->quote(JFactory::getLanguage()->getTag()) . ',' . $db->quote('*') . ')');
				}

				$query->where('e.id = ' . (int) $pk);

				// Features - Extract the number of displayable icons per event
				$query->select('feat.count AS features');
				$sub_query = $db->getQuery(true);
				$sub_query->select('fx.event_id, COUNT(*) AS count');
				$sub_query->from('#__icagenda_feature_xref AS fx');
				$sub_query->innerJoin("#__icagenda_feature AS f ON fx.feature_id = f.id AND f.state = 1 AND f.icon <> '-1'");
				$sub_query->group('fx.event_id');
				$query->leftJoin('(' . (string) $sub_query . ') AS feat ON e.id = feat.event_id');

				// Registrations - Get total of registered people
				$evtParams = icagendaEvent::getParams((int) $pk);

				$typeReg = $evtParams->get('typeReg', 1);

				$query->select($db->qn('r.count', 'registered'));
				$sub_query = $db->getQuery(true)
							->select(array(
									$db->qn('r.eventid'),
									'sum(' . $db->qn('r.people') . ') AS count',
								))
							->from($db->qn('#__icagenda_registration', 'r'))
							->where($db->qn('r.status') . ' = 1')
							->where($db->qn('r.state') . ' = 1');

				// Get var event date alias if set or var 'event_date' set to session in event details view
				$session = JFactory::getSession();

				$date_value = $jinput->get('date', '');

				$event_id = $session->get('event_id', '');

				if ($event_id !== $pk)
				{
					$session->set('event_id', (int) $pk);
					$session->set('event_date', icagendaEvent::convertDateAliasToSQLDatetime($date_value));
					$session->set('session_date', icagendaEvent::convertDateAliasToSQLDatetime($date_value));
				}
				elseif ($date_value)
				{
					$session->set('event_date', icagendaEvent::convertDateAliasToSQLDatetime($date_value));
					$session->set('session_date', icagendaEvent::convertDateAliasToSQLDatetime($date_value));
				} else {
					// Flush session for period.
					$session->set('event_date', '');
					$session->set('session_date', '');
				}

				if ( ! $event_id) $session->set('event_id', (int) $pk);

				$event_date = $session->get('event_date', '');
				$get_date   = $jinput->get('date', ($event_date ? date('Y-m-d-H-i', strtotime($event_date)) : ''));

				// Convert to SQL datetime if set, or return empty.
				$dateday = icagendaEvent::convertDateAliasToSQLDatetime($get_date);

				// Redirect and remove date var, if not correctly set
				if ($get_date
					&& ! $dateday)
				{
					$event_url = JUri::getInstance()->toString();
					$cleanurl  = preg_replace('/&date=[^&]*/', '', $event_url);
					$cleanurl  = preg_replace('/\?date=[^\?]*/', '', $cleanurl);

					$app->redirect($cleanurl);

					return false;
				}

				// Registration type: by single date/period (1)
				if ($dateday && $typeReg == 1)
				{
//					$sub_query->where('r.date = ' . $db->q($dateday)); // This is the good logic if correctly set
					$sub_query->where('(r.date = ' . $db->q($dateday) . ' OR (r.date = "" AND r.period = 1))');
				}
				elseif ( ! $dateday && $typeReg == 1)
				{
//					$sub_query->where('r.date = "" AND r.period = 0'); // This is the good logic if correctly set
					$sub_query->where('r.date = ""');
				}

				$sub_query->group('r.eventid');
				$query->leftJoin('(' . (string) $sub_query . ') AS r ON e.id = r.eventid');

				// Filter by published state.
//				$published = $this->getState('filter.published');
//				$archived = $this->getState('filter.archived');

//				if (is_numeric($published))
//				{
//					$query->where('(a.state = ' . (int) $published . ' OR a.state =' . (int) $archived . ')');
//				}

				// Filter by language
				if ($this->getState('filter.language'))
				{
					$query->where('e.language in (' . $db->q(JFactory::getLanguage()->getTag()) . ',' . $db->q('*') . ')');
				}

				$db->setQuery($query);

				$data = $db->loadObject();

				if (empty($data))
				{
					throw new \Exception(JText::_('COM_ICAGENDA_ERROR_EVENT_NOT_FOUND'), 404);
				}

				// Add to Cal
				$data->googleCalendar      = icagendaAddtocal::googleCalendar($data);
				$data->windowsliveCalendar = icagendaAddtocal::windowsliveCalendar($data);
				$data->yahooCalendar       = icagendaAddtocal::yahooCalendar($data);

				// Extract the feature details, if needed
				if (is_null($data->features))
				{
					$data->features = array();
				}
				else
				{
					$db = $this->getDbo();
					$query = $db->getQuery(true);
					$query->select('DISTINCT f.icon, f.icon_alt');
					$query->from('#__icagenda_feature_xref AS fx');
					$query->innerJoin("#__icagenda_feature AS f ON fx.feature_id=f.id AND f.state=1 AND f.icon<>'-1'");
					$query->where('fx.event_id=' . $data->id);
					$query->order('f.ordering DESC'); // Order descending because the icons are floated right
					$db->setQuery($query);
					$data->features = $db->loadObjectList();
				}

				// Convert parameter fields to objects.
				$registry = new Registry;
				$registry->loadString($data->params);

				// Merge Event params to app params
				$data->params = clone $this->getState('params');
				$data->params->merge($registry);

				$this->_item[$pk] = $data;
			}
			catch (\Exception $e)
			{
				if ($e->getCode() == 404)
				{
					// Need to go thru the error handler to allow Redirect to work.
					throw new \Exception($e->getMessage(), 404);
				}
				else
				{
					$this->setError($e);
					$this->_item[$pk] = false;
				}
			}
		}

		return $this->_item[$pk];
	}

	/**
	 * Increment the hit counter for the event.
	 *
	 * @param   integer  $pk  Optional primary key of the event to increment.
	 *
	 * @return  boolean  True if successful; false otherwise and internal error set.
	 *
	 * @since   3.6.0
	 */
	public function hit($pk = 0)
	{
		$jinput = JFactory::getApplication()->input;

		$hitcount = $jinput->getInt('hitcount', 1);

		if ($hitcount)
		{
			// Initialise variables.
			$pk = ( ! empty($pk)) ? $pk : (int) $this->getState('event.id');
			$db = $this->getDbo();

			$db->setQuery(
					'UPDATE #__icagenda_events' .
					' SET hits = hits + 1' .
					' WHERE id = ' . (int) $pk
			);

			if ( ! $db->query())
			{
				$this->setError($db->getErrorMsg());

				return false;
			}
		}

		return true;
	}
}
