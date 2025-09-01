<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.8.24 2023-12-05
 *
 * @package     iCagenda.Admin
 * @link        https://www.icagenda.com
 *
 * @author      Cyril Rezé
 * @copyright   (c) 2012-2024 Cyril Rezé / iCagenda. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 *
 * @since       2.0
 *----------------------------------------------------------------------------
*/

defined('_JEXEC') or die;

use Joomla\Archive\Archive;

/**
 * Registrations Model.
 */
class iCagendaModelregistrations extends JModelList
{
	/**
	 * Constructor.
	 *
	 * @param   array  $config  An optional associative array of configuration settings.
	 *
	 * @since   2.0
	 * @see     JControllerLegacy
	 */
	public function __construct($config = array())
	{
		if (empty($config['filter_fields']))
		{
			$config['filter_fields'] = array(
				'id', 'a.id',
				'ordering', 'a.ordering',
				'userid', 'userid',
				'name', 'name',
				'username', 'username',
				'email', 'email',
				'phone', 'phone',
				'event', 'event',
				'date', 'a.date',
				'startdate', 'e.startdate',
				'people', 'a.people',
				'notes', 'a.notes',
				'evt_created_by', 'a.evt_created_by'
			);
		}

		parent::__construct($config);
	}

	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 */
	protected function populateState($ordering = null, $direction = null)
	{
		// Initialise variables.
		$app = JFactory::getApplication('administrator');

		// Load the filter search.
		$search = $app->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
		$this->setState('filter.search', $search);

		// Filter (dropdown) state.
		$published = $app->getUserStateFromRequest($this->context . '.filter.state', 'filter_published', '', 'string');
		$this->setState('filter.state', $published);

		// Filter (dropdown) Registration State
		$registration_state = $this->getUserStateFromRequest($this->context . '.filter.registration_state', 'filter_registration_state', '', 'string');
		$this->setState('filter.registration_state', $registration_state);

		// Filter (dropdown) categories
		$categories = $this->getUserStateFromRequest($this->context . '.filter.categories', 'filter_categories', '', 'string');
		$this->setState('filter.categories', $categories);

		// Filter (dropdown) events
		$events = $this->getUserStateFromRequest($this->context . '.filter.events', 'filter_events', '', 'string');
		$this->setState('filter.events', $events);

		// Filter (dropdown) dates
		$dates = $this->getUserStateFromRequest($this->context . '.filter.dates', 'filter_dates', '', 'string');
		$this->setState('filter.dates', $dates);

		// Load the parameters.
		$params = JComponentHelper::getParams('com_icagenda');
		$this->setState('params', $params);

		// List state information.
		parent::populateState('a.id', 'desc');
	}

	/**
	 * Method to get a store id based on model configuration state.
	 *
	 * This is necessary because the model is used by the component and
	 * different modules that might need different sets of data or different
	 * ordering requirements.
	 *
	 * @param   string  $id  A prefix for the store id.
	 *
	 * @return  string  A store id.
	 *
	 * @since   2.0
	 */
	protected function getStoreId($id = '')
	{
		// Compile the store id.
		$id.= ':' . $this->getState('filter.search');
		$id.= ':' . $this->getState('filter.state');
		$id.= ':' . $this->getState('filter.categories');
		$id.= ':' . $this->getState('filter.events');
		$id.= ':' . $this->getState('filter.dates');

		return parent::getStoreId($id);
	}

	/**
	 * Build an SQL query to load the list data.
	 *
	 * @return  JDatabaseQuery
	 *
	 * @since   2.0
	 */
	protected function getListQuery()
	{
		// Create a new query object.
		$db    = $this->getDbo();
		$query = $db->getQuery(true);

		// Select the required fields from the table.
		$query->select(
			$this->getState(
				'list.select',
				'a.*'
			)
		);
		$query->from('#__icagenda_registration AS a');

		// Join over the events.
		$query->select('e.title AS event, e.created_by AS evt_created_by, e.state AS evt_state,
						e.startdate AS startdate, e.enddate AS enddate, e.displaytime AS displaytime,
						e.params AS eventParams');
		$query->join('LEFT', '#__icagenda_events AS e ON e.id=a.eventid');

		// Join over the categories.
		$query->select('c.id AS cat_id, c.title AS cat_title');
		$query->join('LEFT', '#__icagenda_category AS c ON c.id=e.catid');

		// Join over the users for the checked out user.
		$query->select('u.username AS username, u.name AS fullname');
		$query->join('LEFT', '#__users AS u ON u.id=a.userid');

		// Join over the users for the author.
		$query->select('ua.name AS author_name, ua.username AS author_username')
			->join('LEFT', '#__users AS ua ON ua.id = a.created_by');

		// Join over the users actions (consents). @note: GROUP_CONCAT isn't supported by non-MySQL DBs.
		$query->select('GROUP_CONCAT(uc.user_action) AS user_action, GROUP_CONCAT(uc.action_subject) AS action_subject, GROUP_CONCAT(uc.action_body) AS action_body')
			->join('LEFT', '#__icagenda_user_actions as uc ON uc.parent_id = a.id')
			->group('a.id, uc.parent_id');

		// Filter by published state
		$published = $this->getState('filter.state');

		if (is_numeric($published))
		{
			$query->where('a.state = ' . (int) $published);
		}
		elseif ($published === '')
		{
			$query->where('(a.state IN (0, 1))');
		}

		// Filter by registration state
		$registration_state = $this->getState('filter.registration_state');

		if (is_numeric($registration_state))
		{
			$query->where('a.status = ' . (int) $registration_state);
		}

		// Filter by edit access
		if ( ! JFactory::getUser()->authorise('core.edit', 'com_icagenda')
			&& JFactory::getUser()->authorise('core.edit.own', 'com_icagenda'))
		{
			$userID = JFactory::getUser()->get('id');
			$query->where('a.userid = ' . (int) $userID);
		}

		// Filter by search in content
		$search = $this->getState('filter.search');

		if ( ! empty($search))
		{
			// Search for registration id with Prefix "ID:"
			if (stripos($search, 'id:') === 0)
			{
				$query->where('a.id = ' . (int) substr($search, 3));
			}
			// Search for user id with Prefix "USERID:"
			elseif (stripos($search, 'userid:') === 0)
			{
				$query->where('a.userid = ' . (int) substr($search, 7));
			}
			// Search for user email with Prefix "EMAIL:"
			elseif (stripos($search, 'email:') === 0)
			{
				$query->where('a.email = ' . $db->quote(trim(substr($search, 6))));
			}
			// Search for event id with Prefix "EVENTID:"
			elseif (stripos($search, 'eventid:') === 0)
			{
				$query->where('a.eventid = ' . $db->quote(trim(substr($search, 8))));
			}
			else
			{
				$search = $db->Quote('%' . $db->escape($search, true) . '%');

				$query->where('(u.username LIKE ' . $search .
							' OR  a.name LIKE ' . $search .
							' OR  a.userid LIKE ' . $search .
							' OR  a.email LIKE ' . $search .
							' OR  a.phone LIKE ' . $search .
							' OR  a.date LIKE ' . $search .
							' OR  a.period LIKE ' . $search .
							' OR  a.people LIKE ' . $search .
							' OR  a.notes LIKE ' . $search .
							' OR  e.title LIKE ' . $search . ' )');
			}
		}

		// Filter categories
		$category = $db->escape($this->getState('filter.categories'));

		if ( ! empty($category))
		{
			$query->where('(c.id=' . $db->q($category) . ')');
		}

		// Filter events
		$event = $db->escape($this->getState('filter.events'));

		if ( ! empty($event))
		{
			$query->where($db->qn('a.eventid') . ' = ' . $db->q($event));
		}

		// Filter dates
		$date = $db->escape($this->getState('filter.dates'));

		if (empty($event) && ! empty($date) && iCDate::isDate($date) && ! in_array($date, array('1', 'all')))
		{
			if (empty($event))
			{
				$query->where('(DATE(a.date) = ' . $db->q($date) . ' OR (DATE(e.startdate) = ' . $db->q($date) . ' AND ' . $db->qn('a.date') . ' = ""))');
			}
			elseif ( ! empty($event))
			{
				$query->where('(' . $db->qn('a.date') . ' = ' . $db->q($date) . ' OR (' . $db->qn('e.startdate') . ' = ' . $db->q($date) . ' AND ' . $db->qn('a.date') . ' = ""))');
			}
		}
		elseif ($date == 1)
		{
			$query->where($db->qn('a.date') . ' = ""');
			$query->where($db->qn('a.period') . ' = "0"');
		}
		elseif ($date == 'all')
		{
			$query->where($db->qn('a.date') . ' = ""');
			$query->where($db->qn('a.period') . ' = "1"');
		}
		elseif ( ! empty($date))
		{
			$query->where($db->qn('a.date') . ' = ' . $db->q($date));
		}

		// Add the list ordering clause.
		$orderCol  = $this->state->get('list.ordering');
		$orderDirn = $this->state->get('list.direction');

		if ($orderCol && $orderDirn)
		{
			if ($orderCol == 'a.date')
			{
				$query->order($db->escape($db->qn('a.period') . ' ' . $orderDirn));
			}

			$query->order($db->escape($orderCol . ' ' . $orderDirn));
		}

		return $query;
	}

	/**
	 * Gets a list of options for Registration State Filter.
	 *
	 * @since   3.8.0
	 */
	function getRegistrationStates()
	{
		$list['2']  = JText::_('IC_PENDING');
		$list['1']  = JText::_('IC_COMPLETED');
		$list['0']  = JText::_('IC_CANCELLED');
		$list['-1'] = JText::_('IC_ERROR');

		return $list;
	}

	/**
	 * Gets a list of categories.
	 */
	function getCategories()
	{
		// Create a new query object.
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);

		// Select the required fields from the table.
		$query->select('c.id AS cat_id, c.title AS cat_title');
		$query->from('#__icagenda_category AS c');

		// Join over the events.
		$query->select('e.id AS id');
		$query->join('LEFT', '#__icagenda_events AS e ON e.catid=c.id');

		// Join over the registrations.
		$query->select('r.eventid AS event_id');
		$query->join('LEFT', '#__icagenda_registration AS r ON r.eventid=e.id');
		$query->where('(e.id = r.eventid)');

		$params = JComponentHelper::getParams('com_icagenda');

		$orderby_catlist = $params->get('orderby_catlist', 'alpha');

		if ($orderby_catlist == 'alpha')
		{
			$query->order('c.title ASC');
		}
		elseif ($orderby_catlist == 'ralpha')
		{
			$query->order('c.title DESC');
		}
		elseif ($orderby_catlist == 'order')
		{
			$query->order('c.ordering ASC');
		}
//		$query->order('c.ordering ASC');

		$db->setQuery($query);
		$categories = $db->loadObjectList();

		$list = array();

		foreach ($categories as $c)
		{
			$list[$c->cat_id] = $c->cat_title . ' [' . $c->cat_id . ']';
		}

		return $list;
	}

	/**
	 * Gets a list of all events.
	 */
	function getEvents()
	{
		// Create a new query object.
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);

		// Select the required fields from the table.
		$query->select('e.id AS event, e.title AS title');
		$query->from('#__icagenda_events AS e');

		// Join over the categories.
		$query->select('c.id AS cat_id, c.title AS cat_title');
		$query->join('LEFT', '#__icagenda_category AS c ON c.id=e.catid');

		// Join over the registrations.
		$query->select('r.eventid AS eventid');
		$query->join('LEFT', '#__icagenda_registration AS r ON r.eventid=e.id');
		$query->where('(e.id = r.eventid)');
		$query->order('e.title ASC');

		$db->setQuery($query);
		$events = $db->loadObjectList();

		$list = array();

		$catId = $db->escape($this->getState('filter.categories'));

		foreach ($events as $e)
		{
			if ( ! empty($catId) && $catId == $e->cat_id)
			{
				$list[$e->event] = $e->title . ' [' . $e->event . ']';
			}
			elseif (empty($catId))
			{
				$list[$e->event] = $e->title . ' [' . $e->event . ']';
			}
		}

		return $list;
	}

	/**
	 * Gets a list of dates.
	 */
	function getDates()
	{
		$params        = $this->getState('params');
		$dateFormat    = $params->get('date_format_global', 'Y - m - d');
		$dateSeparator = $params->get('date_separator', ' ');
		$timeFormat    = ($params->get('timeformat', '1') == 1) ? 'H:i' : 'h:i A';

		// Create a new query object.
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);

		// Select the required fields from the table.
		$query->select('r.date AS date, r.period AS period, r.eventid AS eventid');
		$query->from('#__icagenda_registration AS r');

		// Join over the events (period).
		$query->select('e.startdate AS startdate, e.enddate AS enddate, e.displaytime AS displaytime');
		$query->join('LEFT', '#__icagenda_events AS e ON e.id=r.eventid');

		$db->setQuery($query);
		$dates = $db->loadObjectList();

		$list = array();

		$eventId = $db->escape($this->getState('filter.events'));

		$p = $e = 0;

		// Add to select dropdown the filters 'For all dates of the event' and/or 'For all the period',
		// depending of registrations in data, and selected event
		foreach ($dates as $d)
		{
			$period = (empty($d->date) && ($d->period == 1 || $d->period == ''))
					? '[ ' . ucfirst(JText::_('COM_ICAGENDA_ADMIN_REGISTRATION_FOR_ALL_DATES')) . ' ]'
					: '';

			if (empty($d->date)
				&& $d->period == 1
				&& $e == 0
				)
			{
				if ( ! empty($eventId) && $eventId == $d->eventid)
				{
					$e = $e+1;
					$list['all'] = $period;
				}
				elseif (empty($eventId))
				{
					$e = $e+1;
					$list['all'] = $period;
				}
			}
		}

		// Add to select dropdown the list of dates,
		// depending of registrations in data, and selected event
		foreach ($dates as $d)
		{
			$date = '';

			if (empty($d->date) && $d->period == 0)
			{
				if ( ! empty($eventId) && $eventId == $d->eventid)
				{
					if (iCDate::isDate($d->startdate))
					{
						$date = iCGlobalize::dateFormat($d->startdate, $dateFormat, $dateSeparator);

						if ($d->displaytime)
						{
							$date.= ' - ' . date($timeFormat, strtotime($d->startdate));
						}
					}
					if (iCDate::isDate($d->enddate))
					{
						$date.= "\n" . '> ' . iCGlobalize::dateFormat($d->enddate, $dateFormat, $dateSeparator);

						if ($d->displaytime)
						{
							$date.= ' - ' . date($timeFormat, strtotime($d->enddate));
						}
					}
				}
				elseif (empty($eventId))
				{
					if (iCDate::isDate($d->startdate))
					{
						$date = iCGlobalize::dateFormat($d->startdate, $dateFormat, $dateSeparator);
					}
				}
				else
				{
					$date = '[ ' . ucfirst(JText::_('COM_ICAGENDA_ADMIN_REGISTRATION_FOR_ALL_PERIOD')) . ' ]';
				}
			}
			else
			{
				$deprecatedDate = iCDate::isDate($d->date)
								? $d->date
								: JText::_('JUNDEFINED') . "\n" . '&#8627; ' . JText::_('COM_ICAGENDA_LEGEND_EDIT_REGISTRATION');

				if  ( ! empty($eventId))
				{
					$date   = iCDate::isDate($d->date)
							? iCGlobalize::dateFormat($d->date, $dateFormat, $dateSeparator) . ' - ' . date('H:i', strtotime($d->date))
							: '&#9888; ' . $deprecatedDate;
				}
				else
				{
					$date   = iCDate::isDate($d->date)
							? iCGlobalize::dateFormat($d->date, $dateFormat, $dateSeparator)
							: '&#9888; ' . $deprecatedDate;
				}
			}

			$display_date	= ($date != '0000-00-00 00:00:00' && $d->date) ? true : false;
			$display_period	= ($date != '0000-00-00 00:00:00' && $d->startdate) ? true : false;

			if ($display_date
				&& ! empty($eventId)
				&& $eventId == $d->eventid
				)
			{
				$list[$d->date] = $date;
			}
			elseif ($display_date
				&& empty($eventId)
				)
			{
				if (iCDate::isDate($d->date))
				{
					$list[date('Y-m-d', strtotime($d->date))] = $date;
				}
				else
				{
					$list[$d->date] = $date;
				}
			}
			elseif ($display_period
				&& empty($eventId)
				)
			{
				$list[date('Y-m-d', strtotime($d->startdate))] = $date;
			}

			if (empty($d->date)
				&& $d->period == 0
				&& $p == 0
				)
			{
				if ( ! empty($eventId) && $eventId == $d->eventid)
				{
					$p = $p+1;
					$list[1] = $date;
				}
				elseif (empty($eventId))
				{
					$p = $p+1;
					$list[1] = $date;
				}
			}
		}

		// Joomla 3 (update chosen)
		$js = '
			(function($){
				$(window).load(function(){
					$("#filter_dates.chzn-done option").each(
						function(){
							var arr = $(this).html().split(/\n/);
							var html = arr[1] ? arr[0]+"<br />&#160;<small style=\"margin-left: 13px\">"+arr[1]+"</small>" : arr[0];
							$(this).html(html);
						}
					);
					$("#filter_dates").trigger("liszt:updated");
				});
			})(jQuery);
		';

		JFactory::getDocument()->addScriptDeclaration($js);

		krsort($list);

		return $list;
	}
	/**
	 * Get file name
	 *
	 * @return  string  The file name
	 *
	 * @since   2.0
	 */
	public function getBaseName()
	{
		if (!isset($this->basename))
		{
			$app = JFactory::getApplication();
			$basename = $this->getState('basename');
			$basename = str_replace('__SITE__', $app->getCfg('sitename'), $basename);

			$eventId = $this->getState('filter.events');

			if (is_numeric($eventId))
			{
				$basename = str_replace('__EVENTID__', $eventId, $basename);
				$basename = str_replace('__EVENT__', $this->getEventTitle($eventId), $basename);
			}
			else
			{
				$basename = str_replace('__EVENTID__', '', $basename);
				$basename = str_replace('__EVENT__', '', $basename);
			}

			$date = $this->getState('filter.dates');

			if (!empty($date))
			{
				if (iCDate::isDate($date))
				{
					$basename = str_replace('__DATE__', JHtml::date($date, JText::_('DATE_FORMAT_LC3'), null)
											. ' - ' . date('H:i', strtotime($date)),
											$basename);
				}
				else
				{
					$basename = str_replace('__DATE__', $date, $basename);
				}
			}
			else
			{
				$basename = str_replace('__DATE__', '', $basename);
			}

			$this->basename = $basename;
		}

		return $this->basename;
	}

	/**
	 * Get the event title.
	 *
	 * @return  string  The event title
	 *
	 * @since   3.5.0
	 */
	protected function getEventTitle()
	{
		$eventId = $this->getState('filter.events');

		if ($eventId)
		{
			$db = $this->getDbo();
			$query = $db->getQuery(true)
				->select('title')
				->from($db->quoteName('#__icagenda_events'))
				->where($db->quoteName('id') . '=' . $db->quote($eventId));
			$db->setQuery($query);

			try
			{
				$title = $db->loadResult();
			}
			catch (\RuntimeException $e)
			{
				$this->setError($e->getMessage());

				return false;
			}
		}
		else
		{
			$title = JText::_('COM_ICAGENDA_NO_EVENT_TITLE');
		}

		return $title;
	}

	/**
	 * Get the Registration State name.
	 *
	 * @return  string  The Registration State name
	 *
	 * @since   3.8.13
	 */
	protected function getRegistrationStateName($state)
	{
		$states_array = [
			0 => 'IC_CANCELLED',
			1 => 'IC_COMPLETED',
			2 => 'IC_PENDING'
		];

		foreach ($states_array as $key => $name)
		{
			if ($state == $key)
			{
				$registration_state_name = $name;
			}
		}

		return JText::_($registration_state_name);
	}

	/**
	 * Get the status name.
	 *
	 * @return  string  The status name
	 *
	 * @since   3.5.0
	 */
	protected function getStatusName($status)
	{
		$status_array = JHtml::_('jgrid.publishedOptions');

		foreach ($status_array AS $key => $name)
		{
			if ($status == $name->value)
			{
				$status_name = $name->text;
			}
		}

		return JText::_($status_name);
	}

	/**
	 * Get the file type.
	 *
	 * @return  string  The file type
	 *
	 * @since   3.5.0
	 */
	public function getFileType()
	{
		return $this->getState('compressed') ? 'zip' : 'csv';
	}

	/**
	 * Get the mime type.
	 *
	 * @return  string  The mime type.
	 *
	 * @since   3.5.0
	 */
	public function getMimeType()
	{
		return $this->getState('compressed') ? 'application/zip' : 'text/csv';
	}

	/**
	 * Get the separator for values.
	 *
	 * @return  string  The separator.
	 *
	 * @since   3.5.9
	 */
	public function getSeparator()
	{
		return ($this->getState('separator') == 1) ? "," : ";";
	}

	/**
	 * Get the content
	 *
	 * @return  string  The content.
	 *
	 * @since   3.5.0
	 */
	public function getContent()
	{
		if (!isset($this->content))
		{
			$separator = $this->getSeparator();

			foreach ($this->getItems() as $item)
			{
				// Adds filled custom fields
				$customfields = icagendaCustomfields::getList($item->id, 1, 1, true);

 				$header_cfs = array();

				if ($customfields)
				{
					foreach ($customfields AS $customfield)
					{
						$header_cfs[]= $customfield->cf_title;
					}
				}
			}

			// Start csv content
			$this->content  = '';

			$this->content .= '"';

			if ($this->getState('event_title'))
			{
				$this->content .= str_replace('"', '""', JText::_('COM_ICAGENDA_REGISTRATION_EVENTID')) . '"';
			}
			else
			{
				$this->content .= '#' . '"';
			}

			if ($this->getState('registration_state'))
			{
				$this->content .= $separator . '"' . str_replace('"', '""', JText::_('COM_ICAGENDA_REGISTRATION_STATE')) . '"';
			}

			if ($this->getState('date'))
			{
				$this->content .= $separator . '"' . str_replace('"', '""', JText::_('COM_ICAGENDA_REGISTRATION_DATE')) . '"';
			}

			if ($this->getState('tickets'))
			{
				$this->content .= $separator . '"' . str_replace('"', '""', JText::_('COM_ICAGENDA_REGISTRATION_TICKETS')) . '"';
			}

			if ($this->getState('name'))
			{
				$this->content .= $separator . '"' . str_replace('"', '""', JText::_('IC_NAME')) . '"';
			}

			if ($this->getState('email'))
			{
				$this->content .= $separator . '"' . str_replace('"', '""', JText::_('COM_ICAGENDA_REGISTRATION_EMAIL')) . '"';
			}

			if ($this->getState('phone'))
			{
				$this->content .= $separator . '"' . str_replace('"', '""', JText::_('COM_ICAGENDA_REGISTRATION_PHONE')) . '"';
			}

			if ($this->getState('customfields'))
			{
				foreach ($header_cfs AS $header)
				{
					$this->content .= $separator . '"' . str_replace('"', '""', JText::_($header)) . '"';
				}
			}

			if ($this->getState('notes'))
			{
				$this->content .= $separator . '"' . str_replace('"', '""', JText::_('COM_ICAGENDA_REGISTRATION_NOTES_DISPLAY_LABEL')) . '"';
			}

			if ($this->getState('status'))
			{
				$this->content .= $separator . '"' . str_replace('"', '""', JText::_('JSTATUS')) . '"';
			}

			if ($this->getState('created'))
			{
				$this->content .= $separator . '"' . str_replace('"', '""', JText::_('JGLOBAL_FIELD_CREATED_LABEL')) . '"';
			}

			if ($this->getState('reg_id'))
			{
				$this->content .= $separator . '"' . str_replace('"', '""', JText::_('JGLOBAL_FIELD_ID_LABEL')) . '"';
			}

			$this->content .= "\n";

			// Data Rows
			$n = 0;

			foreach ($this->getItems() as $item)
			{
				// Adds filled custom fields
				$customfields = icagendaCustomfields::getList($item->id, 1, 1, true);

 				$values_cfs = array();

				if ($customfields)
				{
					foreach ($customfields AS $customfield)
					{
						$cf_value = isset($customfield->cf_value) ? $customfield->cf_value : JText::_('IC_NOT_SPECIFIED');

						$values_cfs[]= $cf_value;
					}
				}

				$this->content .= '"';

				if ($this->getState('event_title'))
				{
					$this->content .= str_replace('"', '""', $item->event) . '"';
				}
				else
				{
					$n = $n + 1;
					$this->content .= $n . '"';
				}

				if ($this->getState('registration_state'))
				{
					$this->content .= $separator . '"' . str_replace('"', '""', $this->getRegistrationStateName($item->status)) . '"';
				}

				if ($this->getState('date'))
				{
					$isPeriod = ($item->period != 1 && ! $item->date) ? true : false;
					$regDate  = $isPeriod ? $item->startdate . ' > ' . $item->enddate : $item->date;

					$this->content .= $separator . '"' .
						str_replace('"', '""', ($item->period == 1 ? JText::_('COM_ICAGENDA_REGISTRATION_ALL_DATES') : $regDate)) . '"';
				}

				if ($this->getState('tickets'))
				{
					$this->content .= $separator . '"' . str_replace('"', '""', $item->people) . '"';
				}

				if ($this->getState('name'))
				{
					$this->content .= $separator . '"' . str_replace('"', '""', $item->name) . '"';
				}

				if ($this->getState('email'))
				{
					$this->content .= $separator . '"' . str_replace('"', '""', $item->email) . '"';
				}

				if ($this->getState('phone'))
				{
					$this->content .= $separator . '"' . str_replace('"', '""', $item->phone) . '"';
				}

				if ($this->getState('customfields'))
				{
					foreach ($values_cfs AS $value)
					{
						$this->content .= $separator . '"' . str_replace('"', '""', JText::_($value)) . '"';
					}
				}

				if ($this->getState('notes'))
				{
					$this->content .= $separator . '"' . str_replace('"', '""', strip_tags($item->notes)) . '"';
				}

				if ($this->getState('status'))
				{
					$this->content .= $separator . '"' . str_replace('"', '""', $this->getStatusName($item->state)) . '"';
				}

				if ($this->getState('created'))
				{
					$this->content .= $separator . '"' . str_replace('"', '""', $item->created) . '"';
				}

				if ($this->getState('reg_id'))
				{
					$this->content .= $separator . '"' . str_replace('"', '""', $item->id) . '"';
				}

				$this->content .= "\n";
			}

			// Exporting Format: character encoding
			if (function_exists('mb_convert_encoding'))
			{
				$this->content = mb_convert_encoding($this->content, $this->getState('character_encoding', 'UTF-8'));
			}

			if ($this->getState('compressed'))
			{
				$app = JFactory::getApplication('administrator');

				// Remove carriage returns (line breaks)
				$this->content = str_replace(CHR(13).CHR(10), " ", $this->content);

				$files = array(
					'registrations' => array(
						'name' => $this->getBasename() . '.csv',
						'data' => $this->content,
						'time' => time()
					)
				);
				$ziproot = $app->get('tmp_path') . '/' . uniqid('icagenda_registrations_') . '.zip';

				// Run the packager
				jimport('joomla.filesystem.folder');
				jimport('joomla.filesystem.file');
				$delete = JFolder::files($app->get('tmp_path') . '/', uniqid('icagenda_registrations_'), false, true);

				if ( ! empty($delete))
				{
					if (!JFile::delete($delete))
					{
						// JFile::delete throws an error
						$this->setError(JText::_('COM_ICAGENDA_EXPORT_ERR_ZIP_DELETE_FAILURE'));

						return false;
					}
				}

				$archive = new Archive;

				if ( ! $packager = $archive->getAdapter('zip'))
				{
					$this->setError(JText::_('COM_ICAGENDA_EXPORT_ERR_ZIP_ADAPTER_FAILURE'));

					return false;
				}
				elseif ( ! $packager->create($ziproot, $files))
				{
					$this->setError(JText::_('COM_ICAGENDA_EXPORT_ERR_ZIP_CREATE_FAILURE'));

					return false;
				}

				$this->content = file_get_contents($ziproot);
			}
		}

		return $this->content;
	}

	/**
	 * Method to get an array of data items.
	 *
	 * @return  mixed  An array of data items on success, false on failure.
	 *
	 * @since   3.8
	 */
	public function getItems()
	{
		if ($items = parent::getItems())
		{
			JPluginHelper::importPlugin('icagenda');
			$dispatcher = JEventDispatcher::getInstance();

			// Do any procesing on fields here if needed
			foreach ($items AS $item)
			{
				$result = $dispatcher->trigger('onICagendaRegistrationsGetItems', array('com_icagenda.registrations', &$item));
			}
		}

		return $items;
	}
}
