<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.8.0 2021-09-27
 *
 * @package     iCagenda.Admin
 * @subpackage  src.Model
 * @link        https://www.icagenda.com
 *
 * @author      Cyril Rezé
 * @copyright   (c) 2012-2024 Cyril Rezé / iCagenda. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 *
 * @since       3.4.0
 *----------------------------------------------------------------------------
*/

namespace WebiC\Component\iCagenda\Administrator\Model;

\defined('_JEXEC') or die;

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\Model\ListModel;

/**
 * iCagenda Component Custom Fields Model
 */
class CustomfieldsModel extends ListModel
{
	/**
	 * Constructor
	 *
	 * @param   array  $config  An optional associative array of configuration settings.
	 *
	 * @see     JControllerLegacy
	 */
	public function __construct($config = array())
	{
		if (empty($config['filter_fields']))
		{
			$config['filter_fields'] = array(
				'id', 'cf.id',
				'ordering', 'cf.ordering',
				'state', 'cf.state',
				'title', 'cf.title',
				'slug', 'cf.slug',
				'parent_form', 'cf.parent_form',
				'type', 'cf.type',
				'required', 'cf.required',
			);
		}

		parent::__construct($config);
	}

	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 */
	protected function populateState($ordering = 'cf.ordering', $direction = 'asc')
	{
		// Initialise variables.
		$app = Factory::getApplication('administrator');

		// Load the filter state.
		$search = $app->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
		$this->setState('filter.search', $search);

		$published = $app->getUserStateFromRequest($this->context . '.filter.state', 'filter_published', '', 'string');
		$this->setState('filter.state', $published);

		// Filter (dropdown) parent form
		$parent_form = $this->getUserStateFromRequest($this->context . '.filter.parent_form', 'filter_parent_form', '', 'string');
		$this->setState('filter.parent_form', $parent_form);

		// Filter (dropdown) field type
		$type = $this->getUserStateFromRequest($this->context . '.filter.type', 'filter_type', '', 'string');
		$this->setState('filter.type', $type);

		// Filter (dropdown) custom field group
		$group = $this->getUserStateFromRequest($this->context . '.filter.group', 'filter_group', '', 'string');
		$this->setState('filter.group', $group);

		// Load the parameters.
		$params = ComponentHelper::getParams('com_icagenda');
		$this->setState('params', $params);

		// List state information.
		parent::populateState($ordering, $direction);
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
	 */
	protected function getStoreId($id = '')
	{
		// Compile the store id.
		$id.= ':' . $this->getState('filter.search');
		$id.= ':' . $this->getState('filter.state');

		return parent::getStoreId($id);
	}

	/**
	 * Build an SQL query to load the list data.
	 *
	 * @return  JDatabaseQuery
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
				'cf.*'
			)
		);
		$query->from('#__icagenda_customfields AS cf');

		// Join over the users for the checked out user.
		$query->select('uc.name AS editor');
		$query->join('LEFT', '#__users AS uc ON uc.id = cf.checked_out');

		// Filter by published state
		$published = $this->getState('filter.state');

		if (is_numeric($published))
		{
			$query->where($db->qn('cf.state') . ' = ' . (int) $published);
		}
		elseif ($published === '')
		{
			$query->where($db->qn('cf.state') . ' IN (0, 1)');
		}

		// Filter by Parent Form
		$parent_form = $db->escape($this->getState('filter.parent_form'));

		if ( ! empty($parent_form))
		{
			$query->where($db->qn('cf.parent_form') . ' = ' . (int) $parent_form);
		}

		// Filter by Group
		$group = $db->escape($this->getState('filter.group'));

		if ( ! empty($group))
		{
			$query->where($db->qn('cf.groups') . ' LIKE "%' . $group . '%"');
		}

		// Filter by Field Type
		$type = $db->escape($this->getState('filter.type'));

		if ( ! empty($type))
		{
			$query->where($db->qn('cf.type') . ' = ' . (string) $db->q($type));
		}

		// Search Filters
		$search = $this->getState('filter.search');

		if ( ! empty($search))
		{
			if (stripos($search, 'id:') === 0)
			{
				$query->where($db->qn('cf.id') . ' = ' . (int) substr($search, 3));
			}
			else
			{
				$search = $db->Quote('%' . $db->escape($search, true) . '%');
				$query->where('( cf.title LIKE ' . $search . '  OR  cf.slug LIKE ' . $search . '  OR  cf.type LIKE ' . $search . ' )');
			}
		}

		// Add the list ordering clause.
		$orderCol  = $this->state->get('list.ordering');
		$orderDirn = $this->state->get('list.direction');

		if ($orderCol && $orderDirn)
		{
			$query->order($db->escape($orderCol . ' ' . $orderDirn));
		}

		return $query;
	}

	/**
	 * Gets list of Custom Field Groups.
	 *
	 * @since   3.6.0
	 */
	function getGroups()
	{
		$db = $this->getDbo();
		$query = $db->getQuery(true);
		$query->select('f.value, f.option');
		$query->from('#__icagenda_filters AS f');
		$query->where('f.type = "customfield"');
		$query->where('f.filter = "groups"');
		$query->order('f.option ASC');
		$db->setQuery($query);
		$groups = $db->loadObjectList();

		$list = array();

		foreach ($groups AS $g)
		{
			$list[$g->value] = $g->option;
		}

		return $list;
	}

	/**
	 * Gets a list of Field Types.
	 */
	function getFieldTypes()
	{
		$type['text']               = Text::_('COM_ICAGENDA_CUSTOMFIELD_TYPE_TEXT');
		$type['list']               = Text::_('COM_ICAGENDA_CUSTOMFIELD_TYPE_LIST');
		$type['radio']              = Text::_('COM_ICAGENDA_CUSTOMFIELD_TYPE_RADIO');
		$type['calendar']           = Text::_('COM_ICAGENDA_CUSTOMFIELD_TYPE_DATE');
		$type['url']                = Text::_('COM_ICAGENDA_CUSTOMFIELD_TYPE_URL');
		$type['email']              = Text::_('COM_ICAGENDA_CUSTOMFIELD_TYPE_EMAIL');
		$type['spacer_label']       = Text::_('COM_ICAGENDA_CUSTOMFIELD_TYPE_SPACER_LABEL');
		$type['spacer_description'] = Text::_('COM_ICAGENDA_CUSTOMFIELD_TYPE_SPACER_DESCRIPTION');
		$type['core_name']          = Text::_('COM_ICAGENDA_LABEL_OVERRIDE') . ': ' . Text::_('COM_ICAGENDA_REGISTRATION_NAME');
		$type['core_email']         = Text::_('COM_ICAGENDA_LABEL_OVERRIDE') . ': ' . Text::_('COM_ICAGENDA_REGISTRATION_EMAIL');
		$type['core_phone']         = Text::_('COM_ICAGENDA_LABEL_OVERRIDE') . ': ' . Text::_('COM_ICAGENDA_REGISTRATION_PHONE');
		$type['core_date']          = Text::_('COM_ICAGENDA_LABEL_OVERRIDE') . ': ' . Text::_('COM_ICAGENDA_REGISTRATION_DATE');
		$type['core_people']        = Text::_('COM_ICAGENDA_LABEL_OVERRIDE') . ': ' . Text::_('COM_ICAGENDA_REGISTRATION_TICKETS');

		return $type;
	}

	/**
	 * Gets list of Custom Field Groups.
	 *
	 * @since   3.6.0
	 */
	function getCustomFieldGroups()
	{
		$db = $this->getDbo();
		$query = $db->getQuery(true);
		$query->select('f.value, f.option');
		$query->from('#__icagenda_filters AS f');
		$query->where('f.type = "customfield"');
		$query->where('f.filter = "groups"');
		$query->order('f.option ASC');
		$db->setQuery($query);
		$groups = $db->loadObjectList();

		return $groups;
	}
}
