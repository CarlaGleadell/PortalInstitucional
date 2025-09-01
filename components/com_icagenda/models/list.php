<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.8.0 2021-11-14
 *
 * @package     iCagenda.Site
 * @link        https://www.icagenda.com
 *
 * @author      Cyril Rezé
 * @copyright   (c) 2012-2024 Cyril Rezé / iCagenda. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 *
 * @since       1.0
 *----------------------------------------------------------------------------
*/

defined('_JEXEC') or die;

use Joomla\Registry\Registry;

/**
 * This models supports retrieving lists of events.
 */
class iCagendaModelList extends JModelList
{
	/**
	 * Model context string.
	 *
	 * @var        string
	 */
	protected $_context = 'com_icagenda.list';

	/**
	 * Constructor.
	 *
	 * @param   array        An optional associative array of configuration settings.
	 * @see     JController
	 *
	 * @since   3.6.0
	 */
	public function __construct($config = array())
	{
		if (empty($config['filter_fields']))
		{
			$config['filter_fields'] = array(
				'id', 'e.id',
				'ordering', 'e.ordering',
				'state', 'e.state',
				'access', 'e.access', 'access_level',
				'approval', 'e.approval',
				'created', 'e.created',
				'title', 'e.title',
				'username', 'e.username',
				'email', 'e.email',
				'category', 'e.category',
				'cat_color', 'e.catcolor',
				'image', 'e.image',
				'file', 'e.file',
				'next', 'e.next',
				'place', 'e.place',
				'city', 'e.city',
				'country', 'e.country',
				'desc', 'e.desc',
				'language', 'e.language',
				'location', 'e.location',
				'category_id',
			);
		}

		parent::__construct($config);
	}

	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @since  3.6.0
	 */
	protected function populateState($ordering = null, $direction = null)
	{
		// Initialise variables.
		$app = JFactory::getApplication();

		// Load the filter search.
		$search = $app->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
		$this->setState('filter.search', $search);

		// Filter (dropdown) category
		$category = $this->getUserStateFromRequest($this->context . '.filter.category', 'filter_category');
		$this->setState('filter.category', $category);

		// Filter (date picker) from
		$from = $this->getUserStateFromRequest($this->context . '.filter.from', 'filter_from', '', 'string');
		$this->setState('filter.from', $from);

		// Filter (date picker) to
		$to = $this->getUserStateFromRequest($this->context . '.filter.to', 'filter_to', '', 'string');
		$this->setState('filter.to', $to);

		// Filter (dropdown) month
		$month = $this->getUserStateFromRequest($this->context . '.filter.month', 'filter_month', '', 'string');
		$this->setState('filter.month', $month);

		// Filter (dropdown) year
		$year = $this->getUserStateFromRequest($this->context . '.filter.year', 'filter_year', '', 'string');
		$this->setState('filter.year', $year);

		// Load the filter state.
		$published = $app->getUserStateFromRequest($this->context . '.filter.state', 'filter_published', '', 'string');
		$this->setState('filter.state', $published);

		// Load the filter access.
		$access = $app->getUserStateFromRequest($this->context . '.filter.access', 'filter_access', '', 'string');
		$this->setState('filter.access', $access);

		// Load the filter language.
		$language = $app->getUserStateFromRequest($this->context . '.filter.language', 'filter_language', '', 'string');
		$this->setState('filter.language', $language);

		// Filter categoryId
//		$categoryId = $this->getUserStateFromRequest($this->context . '.filter.category_id', 'filter_category_id');
//		$this->setState('filter.category_id', $categoryId);

		// Filter (dropdown) upcoming
//		$upcoming = $this->getUserStateFromRequest($this->context . '.filter.upcoming', 'filter_upcoming', '', 'string');
//		$this->setState('filter.upcoming', $upcoming);

		// Load the parameters.
		$params = $app->getParams();
		$this->setState('params', $params);

		// List state information.
		parent::populateState('e.id', 'desc');
	}

	/**
	 * Method to get a store id based on model configuration state.
	 *
	 * This is necessary because the model is used by the component and
	 * different modules that might need different sets of data or different
	 * ordering requirements.
	 *
	 * @param   string  $id  A prefix for the store id.
	 * @return  string       A store id.
	 *
	 * @since   3.6.0
	 */
	protected function getStoreId($id = '')
	{
		// Compile the store id.
		$id .= ':' . $this->getState('filter.search');
		$id .= ':' . $this->getState('filter.access');
		$id .= ':' . $this->getState('filter.state');
		$id .= ':' . $this->getState('filter.access');
		$id .= ':' . $this->getState('filter.language');
		$id .= ':' . $this->getState('filter.category');
		$id .= ':' . $this->getState('filter.from');
		$id .= ':' . $this->getState('filter.to');
		$id .= ':' . $this->getState('filter.month');
		$id .= ':' . $this->getState('filter.year');

		return parent::getStoreId($id);
	}

	/**
	 * Build an SQL query to load the list data.
	 *
	 * @return  JDatabaseQuery
	 *
	 * @since   3.6.0
	 */
	protected function getListQuery()
	{
		$app	= JFactory::getApplication();
		$params	= $app->getParams();
		$jinput	= $app->input;
		$layout	= $jinput->get('layout', '');

		// Get the current user for authorisation checks
		$user	= JFactory::getUser();

		// Get View Options for filtering
		$mcatid	= $params->get('mcatid', '');

		// Create a new query object.
		$db		= $this->getDbo();
		$query	= $db->getQuery(true);

		// Select the required fields from the table.
		$query->select(
			$this->getState(
				'list.select', 'e.*, ' .
				'e.name AS contact_name, e.email AS contact_email'
			)
		);
		$query->from($db->qn('#__icagenda_events') . ' AS e');

		// Join over the category
		$query->select('c.id AS cat_id, c.title AS cat_title, c.color AS cat_color, c.desc AS cat_desc');
		$query->join('LEFT', '#__icagenda_category AS c ON c.id = e.catid');
		$query->where('c.state = 1');

		// Features - extract the number of displayable icons per event
		$query->select('feat.count AS features');
		$sub_query = $db->getQuery(true);
		$sub_query->select('fx.event_id, COUNT(*) AS count');
		$sub_query->from('#__icagenda_feature_xref AS fx');
		$sub_query->innerJoin("#__icagenda_feature AS f ON fx.feature_id=f.id AND f.state=1 AND f.icon<>'-1'");
		$sub_query->group('fx.event_id');
		$query->leftJoin('(' . (string) $sub_query . ') AS feat ON e.id=feat.event_id');

		// Filter by features
		$query->where(icagendaEventsData::getFeaturesFilter());

		// Join total of registrations
//		$current_date = $jinput->get('date', '');

//		if ($current_date)
//		{
//			$ex = explode('-', $current_date);

//			if (count($ex) == 5)
//			{
//				$date_to_check = $ex['0'] . '-' . $ex['1'] . '-' . $ex['2'] . ' ' . $ex['3'] . ':' . $ex['4'] . ':00';
//			}
//			else
//			{
//				$date_to_check = '';
//			}
//		}
//		else
//		{
//			$date_to_check = 'e.next';
//		}

//		$query->select('r.count AS registered');
//		$sub_query = $db->getQuery(true);
//		$sub_query->select('r.state, r.date, r.eventid, sum(r.people) AS count');
//		$sub_query->from('#__icagenda_registration AS r');
//		$sub_query->where('r.state > 0');
//		$sub_query->group('r.date, r.eventid');
//		$query->leftJoin('(' . (string) $sub_query . ') AS r ON ((' . $db->q($date_to_check) . ' = r.date OR r.date = "") AND e.id = r.eventid)');

		// Filter by published state
		$published = $this->getState('filter.state');

		if (is_numeric($published))
		{
			$query->where('e.state = '.(int) $published);
		}
		elseif ($published === '')
		{
			$query->where('(e.state IN (0, 1))');
		}

		$userGroups = $user->getAuthorisedGroups();

		$groupid	= JComponentHelper::getParams('com_icagenda')->get('approvalGroups', array("8"));
		$groupid	= is_array($groupid) ? $groupid : array($groupid);

		// Test if user login have Approval Rights
		if ( ! array_intersect($userGroups, $groupid)
			&& ! in_array('8', $userGroups))
		{
			$query->where('e.approval <> 1');
		}
		else
		{
			$query->where('e.approval < 2');
		}

		// Filter by access level.
		$access = $this->getState('filter.access');

		if ( ! empty($access)
			&& ! in_array('8', $userGroups))
		{
			$access_levels = implode(',', $user->getAuthorisedViewLevels());

			$query->where('e.access IN (' . $access_levels . ')');
//				->where('c.access IN (' . $access_levels . ')'); // To be added later, when access integrated to category
		}

		// Filter by language
		if ($this->getState('filter.language'))
		{
			$query->where('e.language in (' . $db->q(JFactory::getLanguage()->getTag()) . ',' . $db->q('*') . ')');
		}


		// Prepare to return the list of dates/events
		$format	        = $jinput->get('format', '');
		$type	        = $jinput->get('type', '');
		$limit	        = $jinput->get('limit', '');
		$number			= ($format == 'feed' && $type == 'rss')
						? JFactory::getConfig()->get('feed_limit', $params->get('number', 5))
						: $params->get('number', 5);
//		$orderdate		= $params->get('orderby', 2); // Processed in getAllDates()
		$currentPage	= $jinput->get('page', '1');

		// Get array of all [date _ id]
		$list_date_id 	= icagendaEventsData::getAllDates();

		if ($limit != '' && $limit >= 0)
		{
			$number = ($limit == 0) ? count($list_date_id) : (int) $limit;
		}

		// Set list of PAGE:IDS
		$pages			= ceil(count($list_date_id) / $number);
		$list_id		= array();

		for ($n = 1; $n <= $pages; $n++)
		{
			$idsArray = array();

			$page_nb		= $number * ($n - 1);
			$datesPerPage	= array_slice($list_date_id, $page_nb, $number, true);

			foreach ($datesPerPage AS $date_id)
			{
				$ex_date_id = explode('_', $date_id);

				$idsArray[] = $ex_date_id['1'];
			}

			$list_id[] = implode(', ', $idsArray) . '::' . $n;
		}

		$this_ic_ids = '';

		if ($list_id)
		{
			foreach ($list_id as $a)
			{
				$ex_listid	= explode('::', $a);
				$ic_page	= $ex_listid[1];
				$ic_ids		= $ex_listid[0];

				if ($ic_page == $currentPage)
				{
					$this_ic_ids = $ic_ids ? $ic_ids : '0';
				}
			}

			if ($this_ic_ids)
			{
				$query->where('e.id IN (' . $this_ic_ids . ')');
			}
//			else
//			{
				// Unlimited: limit already set by getAllDates()
//				$this->setState('list.limit', 0);

//				return $query; // No Event (if 'All Dates' option selected)
//			}
		}

		// Unlimited: limit already set by getAllDates()
		$this->setState('list.limit', 0);

		return $query;
	}

	/**
	 * Method to get an array of data items.
	 *
	 * @return  mixed  An array of data items on success, false on failure.
	 *
	 * @since   3.6.0
	 */
	public function getItems()
	{
		if ($items = parent::getItems())
		{
			// Do any processing on fields here if needed
			foreach ($items as &$item)
			{
				$item->params           = $this->state->get('params');

				$item->evtParams		= $item->params ? icagendaEvent::evtParams($item->params) : '';
				$item->titleFormat		= $item->title ? icagendaRender::titleToFormat($item->title) : '';
				$item->metaAsShortDesc	= $item->metadesc ? iCFilterOutput::fullCleanHTML($item->metadesc) : '';
				$item->shortDescription	= $item->shortdesc
										? JHtml::_('content.prepare', icagendaEvents::deleteAllBetween('{', '}', $item->shortdesc), $item->params, 'com_icagenda.list')
										: '';
				$item->description		= $item->desc
										? JHtml::_('content.prepare', icagendaEvents::deleteAllBetween('{', '}', $item->desc), $item->params, 'com_icagenda.list')
										: '';
				$item->descShort		= $item->desc ? icagendaEvents::shortDescription($item->desc) : '';

				// TO BE REFACTORIED (change css class name)
				$item->fontColor		= (iCColor::getBrightness($item->cat_color) == 'bright') ? 'fontColor' : '';

				// List only
//				$item->url				= icagendaEvent::url($item->id, $item->alias); // @todo move from item to element list vars ?
				$item->titlebar			= icagendaEvent::titleBar($item); // @todo move from item to element list vars

				// Extract the feature details, if needed
				if (is_null($item->features))
				{
					$item->features = array();
				}
				else
				{
					$db = $this->getDbo();
					$query = $db->getQuery(true);
					$query->select('DISTINCT f.icon, f.icon_alt');
					$query->from('#__icagenda_feature_xref AS fx');
					$query->innerJoin("#__icagenda_feature AS f ON fx.feature_id=f.id AND f.state=1 AND f.icon<>'-1'");
					$query->where('fx.event_id=' . (int)$item->id);
					$query->order('f.ordering DESC'); // Order descending because the icons are floated right
					$db->setQuery($query);
					$item->features = $db->loadObjectList();
				}
			}
		}

		return $items;
	}
}
