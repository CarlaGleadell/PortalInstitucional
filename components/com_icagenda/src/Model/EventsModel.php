<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.9.12 2025-07-31
 *
 * @package     iCagenda.Site
 * @subpackage  src.Model
 * @link        https://www.joomlic.com
 *
 * @author      Cyril Reze
 * @copyright   (c) 2012-2025 Cyril Reze / JoomliC. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 *
 * @since       3.4
 *----------------------------------------------------------------------------
*/

namespace WebiC\Component\iCagenda\Site\Model;

\defined('_JEXEC') or die;

use iClib\Color\Color as iCColor;
use iClib\Filter\Output as iCFilterOutput;
use iCutilities\Event\Event as icagendaEvent;
use iCutilities\Events\Events as icagendaEvents;
use iCutilities\Events\EventsData as icagendaEventsData;
use iCutilities\Manager\Manager as icagendaManager;
use iCutilities\Render\Render as icagendaRender;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\Model\ListModel;
use Joomla\Registry\Registry;
use Joomla\Utilities\ArrayHelper;

/**
 * This models supports retrieving lists of events.
 */
class EventsModel extends ListModel
{
	/**
	 * Model context string.
	 *
	 * @var  string
	 */
	protected $_context = 'com_icagenda.events';

	/**
	 * Constructor
	 *
	 * @param   array  An optional associative array of configuration settings.
	 *
	 * @see     JController
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
				'checked_out', 'e.checked_out',
				'checked_out_time', 'e.checked_out_time',
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
	 */
	protected function populateState($ordering = null, $direction = null)
	{
		// Initialise variables.
		$app = Factory::getApplication();

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
	 */
	protected function getListQuery()
	{
		$app    = Factory::getApplication();
		$params = $app->getParams();
		$jinput = $app->input;
		$layout = $jinput->get('layout', '');

		// Get the current user for authorisation checks
		$user = Factory::getUser();

		// Get View Options for filtering
		$mcatid = $params->get('mcatid', '');

		// Create a new query object.
		$db    = $this->getDbo();
		$query = $db->getQuery(true);

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

		// Get Module Params
		$mod_params = $this->getState('filter.mod_params');

		// Filter by features
		if ($mod_params)
		{
//			$query->where(icagendaEventsData::getFeaturesFilter($mod_params));
		}
		else
		{
			$query->where(icagendaEventsData::getFeaturesFilter());
		}

		// Join total of registrations (dev. test)
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

		$groupid = ComponentHelper::getParams('com_icagenda')->get('approvalGroups', array("8"));
		$groupid = is_array($groupid) ? $groupid : array($groupid);

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
			$query->where('e.language in (' . $db->q(Factory::getLanguage()->getTag()) . ',' . $db->q('*') . ')');
		}

		if ($mod_params)
		{
			// Get list of All dates
			$all_dates_with_id	= icagendaEventsData::getAllDates(
									$this->getState('filter.upcoming', '0'),
									$this->getState('filter.all_dates'),
									$this->getState('list.direction'),
									$this->getState('filter.category_id', 'all'),
									$mod_params
								);

			$dpp_array = $dpp_dates = array();

			foreach ($all_dates_with_id AS $dpp)
			{
				$dpp_alldates_array = explode('_', $dpp);

				$dpp_date    = $dpp_alldates_array['0'];
				$dpp_id      = $dpp_alldates_array['1'];
				$dpp_dates[] = $dpp_date;
				$dpp_array[] = $dpp_id;
			}

			$list_id = implode(',', $dpp_array);

			if (count($dpp_array))
			{
				$query->where('e.id IN (' . $list_id . ')');
			}

			// Add the list ordering clause.
			$query->order($this->getState('list.ordering', 'e.next') . ' ' . $this->getState('list.direction', 'ASC'));

			// Unlimited: limit already set by getAllDates()
//			$this->setState('list.limit', 0);

			return $query;
		}
		else
		{
			// Prepare to return the list of dates/events
			$format         = $jinput->get('format', '');
			$type           = $jinput->get('type', '');
			$limit          = $jinput->get('limit', '');
//			$number         = ($format == 'feed' && $type == 'rss') // Do not control the format to allow a custom name for it.
			$number         = ($type == 'rss')
							? Factory::getConfig()->get('feed_limit', $params->get('number', 5))
							: $params->get('number', 5);
//			$orderdate      = $params->get('orderby', 2); // Processed in getAllDates()
			$currentPage    = $jinput->get('page', '1');

			// Get array of all [date _ id]
			$list_date_id = icagendaEventsData::getAllDates();

			if ($limit != '' && $limit >= 0)
			{
				$number = ($limit == 0) ? count($list_date_id) : (int) $limit;
			}

			// Set list of PAGE:IDS
			$pages   = ceil(count($list_date_id) / $number);
			$list_id = array();

			for ($n = 1; $n <= $pages; $n++)
			{
				$idsArray = array();

				$page_nb      = $number * ($n - 1);
				$datesPerPage = array_slice($list_date_id, $page_nb, $number, true);

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
					$ex_listid = explode('::', $a);
					$ic_page   = $ex_listid[1];
					$ic_ids    = $ex_listid[0];

					if ($ic_page == $currentPage)
					{
						$this_ic_ids = $ic_ids ? $ic_ids : '0';
					}
				}

				if ($this_ic_ids)
				{
					$query->where('e.id IN (' . $this_ic_ids . ')');
				}
			}

			// Unlimited: limit already set by getAllDates()
			$this->setState('list.limit', 0);

			return $query;
		}
	}

	/**
	 * Method to get an array of data items.
	 *
	 * @return  mixed  An array of data items on success, false on failure.
	 */
	public function getItems()
	{
		$items = parent::getItems();

		// Do any processing on fields here if needed
		foreach ($items as &$item) {
			$eventParams = new Registry($item->params);

			$item->params = clone $this->getState('params');
			$item->params->merge($eventParams);

			$item->eventParams      = $eventParams;
			$item->titleFormat      = $item->title ? icagendaRender::titleToFormat($item->title) : '';
			$item->metaAsShortDesc  = $item->metadesc ? iCFilterOutput::fullCleanHTML($item->metadesc) : '';
			$item->shortDescription = $item->shortdesc
									? HTMLHelper::_('content.prepare', icagendaEvents::deleteAllBetween('{', '}', $item->shortdesc), $item->params, 'com_icagenda.events')
									: '';
			$item->description      = $item->desc
									? HTMLHelper::_('content.prepare', icagendaEvents::deleteAllBetween('{', '}', $item->desc), $item->params, 'com_icagenda.events')
									: '';
			$item->descShort        = $item->desc ? icagendaEvents::shortDescription($item->desc) : '';

			// TO BE REFACTORIED (change css class name)
			$item->fontColor        = (iCColor::getBrightness($item->cat_color) == 'bright') ? 'fontColor' : '';

			// List only
			$item->titlebar         = icagendaEvent::titleBar($item); // @todo move from item to element list vars
			$item->managerToolBar   = icagendaManager::toolBar($item);

			// Extract the feature details, if needed
			if (is_null($item->features)) {
				$item->features = array();
			} else {
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

		return $items;
	}

	/**
	 * Method to get the list of iCagenda categories.
	 *
	 * @return  array  The list of categories object.
	 *
	 * @since   3.9.0
	 */
	public function getCategoriesList()
	{
		$app    = Factory::getApplication();
		$params = $app->getParams();

		$filters_mode   = ($app->getMenu()->getActive()->getParams()->get('search_filters') == 1)
						? $params->get('filters_mode', 1)
						: ComponentHelper::getParams('com_icagenda')->get('filters_mode', 1);

		$orderby_catlist = $params->get('orderby_catlist', 'alpha');

		// Initialize variables.
		$options = [];

		$db = Factory::getDbo();

		$query = $db->getQuery(true);

		$query->select('id AS catid, title AS cattitle, ordering AS catorder');
		$query->from('#__icagenda_category AS c');
		$query->where('state = 1');

		// Search in menu filtered list
		if ($filters_mode == 1) {
			$mcatid = $params->get('mcatid', '');

			$list_catid = \is_array($mcatid)
						? implode(',', ArrayHelper::toInteger($mcatid))
						: trim($mcatid);

			if ($mcatid
				&& \count($mcatid) > 0
				&& ! \in_array('0', $mcatid)
			) {
				$query->where('c.id IN (' . $list_catid . ')');
			}
		}

		if ($orderby_catlist == 'alpha') {
			$query->order('c.title ASC');
		} elseif ($orderby_catlist == 'ralpha') {
			$query->order('c.title DESC');
		} elseif ($orderby_catlist == 'order') {
			$query->order('c.ordering ASC');
		}

		// Get the options.
		$db->setQuery($query);

		$options = $db->loadObjectList();

		return $options;
	}

	/**
	 * Method to get the list of months.
	 *
	 * @return  array  The list of months object.
	 *
	 * @since   3.9.0
	 */
	public function getMonthsList()
	{
		// Initialize variables.
		$options = [
			'1'  => Text::_('JANUARY'),
			'2'  => Text::_('FEBRUARY'),
			'3'  => Text::_('MARCH'),
			'4'  => Text::_('APRIL'),
			'5'  => Text::_('MAY'),
			'6'  => Text::_('JUNE'),
			'7'  => Text::_('JULY'),
			'8'  => Text::_('AUGUST'),
			'9'  => Text::_('SEPTEMBER'),
			'10' => Text::_('OCTOBER'),
			'11' => Text::_('NOVEMBER'),
			'12' => Text::_('DECEMBER'),
		];

		return $options;
	}

	/**
	 * Method to get the list of years.
	 *
	 * @return  array  The list of years object.
	 *
	 * @since   3.9.0
	 */
	public function getYearsList()
	{
		$session = Factory::getSession();
		$app     = Factory::getApplication();
		$params  = $app->getParams();

		$filters_mode   = ($app->getMenu()->getActive()->getParams()->get('search_filters') == 1)
						? $params->get('filters_mode', 1)
						: ComponentHelper::getParams('com_icagenda')->get('filters_mode', 1);

		$session_options = $session->get('filter_year_options');

		$filters_mode_session = $session->get('filters_mode_session');

		if ($session_options && $filters_mode == $filters_mode_session) {
			rsort($session_options);

			return $session_options;
		}

		// Initialize variables.
		$options = [];

		$filterTime = ($filters_mode == 1)
					? $params->get('time', 0)
					: '0';

		$dates = icagendaEventsData::getAllDates($filterTime);

		if (\count($dates) < 1) {
			$year = $app->input->get('filter_year', '');

			if ($year) {
				$year_option = new \stdClass();
				$year_option->value = $year;
				$year_option->label = $year;

				if ( ! in_array($year_option, $options)) {
					$options[] = $year_option;
				}
			}
		} else {
			foreach ($dates AS $date) {
				$year = substr($date, 0, 4);
				$year = ((int) $year > 0) ? $year : '';

				if ($year) {
					$year_option = new \stdClass();
					$year_option->value = $year;
					$year_option->label = $year;

					if ( ! in_array($year_option, $options)) {
						$options[] = $year_option;
					}
				}
			}
		}

		rsort($options);

		$session->set('filter_year_options', $options);
		$session->set('filters_mode_session', $filters_mode);

		return $options;
	}
}
