<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.9.7 2024-10-10
 *
 * @package     iCagenda.Admin
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

jimport('joomla.application.component.modellist'); // Joomla 2.5

/**
 * Events Model.
 */
class iCagendaModelEvents extends JModelList
{
	/**
	 * Constructor.
	 *
	 * @param   array  $config  An optional associative array of configuration settings.
	 *
	 * @since   1.0
	 * @see     JControllerLegacy
	 */
	public function __construct($config = array())
	{
		if (empty($config['filter_fields']))
		{
			$config['filter_fields'] = array(
				'id', 'e.id',
				'ordering', 'e.ordering',
				'state', 'e.state',
				'approval', 'e.approval',
				'created', 'e.created',
				'title', 'e.title',
				'username', 'e.username',
				'email', 'e.email',
				'category', 'category',
				'image', 'e.image',
				'file', 'e.file',
				'next', 'e.next',
				'place', 'e.place',
				'city', 'e.city',
				'country', 'e.country',
				'desc', 'e.desc',
				'params', 'e.params',
//				'location', 'e.location',
				'category_id',
				'site_itemid', 'e.site_itemid',
				'language', 'e.language',
				'hits', 'e.hits',
				'author_username', 'author_name',
			);
		}

		parent::__construct($config);
	}

	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 * @since   1.0
	 */
	protected function populateState($ordering = null, $direction = null)
	{
		// Initialise variables.
		$app = JFactory::getApplication('administrator');

		// Load the filter search.
		$search = $app->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
		$this->setState('filter.search', $search);

		// Load the filter state.
		$published = $app->getUserStateFromRequest($this->context . '.filter.state', 'filter_published', '', 'string');
		$this->setState('filter.state', $published);

		// Load the filter approval.
		$approval = $app->getUserStateFromRequest($this->context . '.filter.approval', 'filter_approval');
		$this->setState('filter.approval', $approval);

		// Filter (dropdown) category
		$category = $this->getUserStateFromRequest($this->context . '.filter.category', 'filter_category');
		$this->setState('filter.category', $category);

		// Filter categoryId
		$categoryId = $this->getUserStateFromRequest($this->context . '.filter.category_id', 'filter_category_id');
		$this->setState('filter.category_id', $categoryId);

		// Filter (dropdown) upcoming
		$upcoming = $this->getUserStateFromRequest($this->context . '.filter.upcoming', 'filter_upcoming', '', 'string');
		$this->setState('filter.upcoming', $upcoming);

		// Filter (dropdown) Frontend Menu Itemid
		$site_itemid = $this->getUserStateFromRequest($this->context . '.filter.site_itemid', 'filter_site_itemid', '', 'string');
		$this->setState('filter.site_itemid', $site_itemid);

		// Load the parameters.
		$params = JComponentHelper::getParams('com_icagenda');
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
	 *
	 * @return  string  A store id.
	 *
	 * @since   1.0
	 */
	protected function getStoreId($id = '')
	{
		// Compile the store id.
		$id.= ':' . $this->getState('filter.search');
		$id.= ':' . $this->getState('filter.state');
		$id.= ':' . $this->getState('filter.approval');
		$id.= ':' . $this->getState('filter.category_id');
		$id.= ':' . $this->getState('filter.site_itemid');

		return parent::getStoreId($id);
	}

	/**
	 * Build an SQL query to load the list data.
	 *
	 * @return  JDatabaseQuery
	 *
	 * @since   1.0
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
				'e.*'
			)
		);
		$query->from('#__icagenda_events AS e');

		// Join over the users for the checked out user.
		$query->select('uc.name AS editor');
		$query->join('LEFT', '#__users AS uc ON uc.id = e.checked_out');

		// Join over the asset groups.
		$query->select('ag.title AS access_level')
			->join('LEFT', '#__viewlevels AS ag ON ag.id = e.access');

		// Join the category
		$query->select('c.title AS category');
		$query->join('LEFT', '#__icagenda_category AS c ON c.id = e.catid');

		// Join over the users for the author.
		$query->select('CASE WHEN e.created_by = "0" THEN e.username ELSE ua.name END AS author_name, ua.username AS author_username')
			->join('LEFT', '#__users AS ua ON ua.id = e.created_by');

		// Filter by published state
		$published = $this->getState('filter.state');

		if (is_numeric($published))
		{
			$query->where('e.state = ' . (int) $published);
		}
		elseif ($published === '')
		{
			$query->where('(e.state IN (0, 1))');
		}

		// Filter by approval state
		$approval = $this->getState('filter.approval');

		if ($approval == '1')
		{
			$query->where('e.approval = "1"');
		}
		elseif ($approval == '0')
		{
			$query->where('e.approval = "0"');
		}

		// Filter by search in title
		$search = $this->getState('filter.search');

		if ( ! empty($search))
		{
			if (stripos($search, 'id:') === 0)
			{
				$query->where('e.id = ' . (int) substr($search, 3));
			}
			else
			{
				if (iCDate::isDate($search))
				{
					$search = $db->quote('%' . $db->escape($search, true) . '%');

					$query->where('( e.startdate LIKE ' . $search .
							' OR e.enddate LIKE ' . $search .
							' OR e.period LIKE ' . $search .
							' OR e.dates LIKE ' . $search . ')');
				}
				else
				{
					$search = $db->quote('%' . $db->escape($search, true) . '%');

					$query->where('( e.title LIKE ' . $search .
								' OR e.username LIKE ' . $search .
								' OR e.id LIKE ' . $search .
								' OR e.email LIKE ' . $search .
								' OR e.file LIKE ' . $search .
								' OR e.place LIKE ' . $search .
								' OR e.city LIKE ' . $search .
								' OR e.country LIKE ' . $search .
								' OR e.desc LIKE ' . $search .
								' OR e.period LIKE ' . $search .
								' OR e.dates LIKE ' . $search .
								' OR c.title LIKE ' . $search . ')');
				}
			}
		}

		// Filter category (admin)
		$category = $db->escape($this->getState('filter.category'));

		if (!empty($category))
		{
			$query->where('(e.catid = ' . $category . ')');
		}

		// Filter Frontend Menu Itemid (admin)
		$site_itemid = $db->escape($this->getState('filter.site_itemid'));

		if ($site_itemid == '0')
		{
			$query->where('(e.site_itemid = "0")');
		}
		elseif ($site_itemid)
		{
			$query->where('(e.site_itemid = ' . (int) $site_itemid . ')');
		}

		// Filter by categories. (NOT USED (multiple-categories filter))
		$categoryId = $this->getState('filter.category_id');

		if (is_numeric($categoryId) && ! empty($categoryId))
		{
			$query->where('e.catid = ' . (int) $categoryId . '');
		}
		elseif (is_array($categoryId) && ! empty($categoryId))
		{
			JArrayHelper::toInteger($categoryId);
			$categoryId = implode(',', $categoryId);
			$query->where('e.catid IN (' . $categoryId . ')');
		}


		// Filter Upcoming Dates
		$upcoming = $db->escape($this->getState('filter.upcoming'));

		if ( ! empty($upcoming))
		{
			if ($upcoming == '1')
			{
				$query->where(' (e.next >= CURDATE() OR (e.startdate <= CURDATE() AND e.enddate >= NOW() AND e.weekdays = "")) ');
			}
			elseif ($upcoming == '2')
			{
//				$query->where(' ((e.next < CURDATE() AND e.startdate = "' . $db->q('0000-00-00 00:00:00') . '") OR (e.next < CURDATE() AND e.startdate <> "' . $db->q('0000-00-00 00:00:00') . '" AND e.startdate <= CURDATE() AND e.enddate <= NOW() AND e.weekdays = "")) ');
				$query->where(' ((e.next < CURDATE() AND e.startdate IS NULL) OR (e.next < CURDATE() AND e.startdate IS NOT NULL AND e.startdate <= CURDATE() AND e.enddate <= NOW() AND e.weekdays = "")) ');
			}
			elseif ($upcoming == '3')
			{
				$query->where(' e.next >= NOW() ');
			}
			elseif ($upcoming == '4')
			{
				$query->where(' ((e.next >= CURDATE() AND e.next < ( CURDATE() + INTERVAL 1 DAY )) OR (e.startdate <= CURDATE() AND e.enddate >= NOW() AND e.weekdays = "")) ');
			}
		}

		// Add the list ordering clause.
		$orderCol  = $this->state->get('list.ordering');
		$orderDirn = $this->state->get('list.direction');

		if ($orderCol && $orderDirn)
		{
			$query->order($db->escape($orderCol.' '.$orderDirn));
		}

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
		// Since Joomla 3.6.0, need to check if user ID exists to prevent alert message in list
		$usersTable = JUser::getTable();

		if ($items = parent::getItems())
		{
			// Do any procesing on fields here if needed
			foreach ($items AS $item)
			{
				if ($item->created_by && $usersTable->load($item->created_by))
				{
					$item->username = JFactory::getUser($item->created_by)->get('name');
				}

				// Check Next Date
				$item->next = icagendaEvent::getNextDate($item);
			}
		}

		return $items;
	}

	/**
	 * Build an SQL query to load the list of all categories.
	 *
	 * @return  JDatabaseQuery
	 * @since   3.3.0
	 */
	function getCategories()
	{
		// Create a new query object.
		$db    = JFactory::getDBO();
		$query = $db->getQuery(true);

		// Select the required fields from the table.
		$query->select('c.id AS catid, c.title AS category');
		$query->from('#__icagenda_category AS c');

		// Filter by published state
		$query->where('(c.state IN (0,1))');

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

		// Order Ordering ASC
//		$query->order('c.ordering ASC');

		$db->setQuery($query);
		$categories = $db->loadObjectList();

		if (count($categories) > 0)
		{
			foreach ($categories as $cat)
			{
				$list[$cat->catid] = $cat->category;
			}

			return $list;
		}
		else
		{
			return array();
		}
	}

	/**
	 * Build an SQL query to load the list of menu item itemid.
	 *
	 * @return  JDatabaseQuery
	 * @since   3.3.0
	 */
	function getMenuItemID()
	{
		// Create a new query object.
		$db    = JFactory::getDBO();
		$query = $db->getQuery(true);

		// Select the required fields from the table.
		$query->select('m.id AS itemid, m.link AS menu_link, m.title AS menu_title');
		$query->from('#__menu AS m');

		// Filter by published state
		$query->where('(m.link = "index.php?option=com_icagenda&view=submit")');
		$query->where('(m.published IN (0,1))');

		$db->setQuery($query);
		$itemids = $db->loadObjectList();

		$list['0'] = 'Created in admin';

		if (count($itemids) > 0)
		{
			foreach ($itemids as $itemid)
			{
				$list[$itemid->itemid] = $itemid->itemid . ' - ' . $itemid->menu_title;
			}

			return $list;
		}
		else
		{
			return array();
		}
	}

	/**
	 * Gets a list of options for Upcoming (Events) Filter.
	 *
	 * @since   3.3.0
	 */
	function getUpcoming()
	{
		$list['1'] = JText::_('COM_ICAGENDA_OPTION_TODAY_AND_UPCOMING');
		$list['2'] = JText::_('COM_ICAGENDA_OPTION_PAST_EVENTS');
		$list['3'] = JText::_('COM_ICAGENDA_OPTION_UPCOMING_EVENTS');
		$list['4'] = JText::_('COM_ICAGENDA_OPTION_TODAY');

		return $list;
	}

	/**
	 * Gets a list of options for Approval Filter.
	 *
	 * @since   3.6.12
	 */
	function getApprovalOptions()
	{
		$list['0'] = JText::_('COM_ICAGENDA_APPROVED');
		$list['1'] = JText::_('COM_ICAGENDA_UNAPPROVED');

		return $list;
	}
}
