<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.8.0 2021-12-25
 *
 * @package     iCagenda.Admin
 * @subpackage  utilities
 * @link        https://www.icagenda.com
 *
 * @author      Cyril Rezé
 * @copyright   (c) 2012-2024 Cyril Rezé / iCagenda. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 *
 * @since       3.6
 *----------------------------------------------------------------------------
*/

defined('_JEXEC') or die;

jimport('joomla.form.formfield');

/**
 * class icagendaAjaxFilter
 */
class icagendaAjaxFilter
{
	/**
	 * Function to save a new custom field group
	 *
	 * @since	3.6.0
	 */
	static public function saveCustomFieldGroup()
	{
		$jinput			= JFactory::getApplication()->input;
		$group_option	= $jinput->get('group', '', 'raw');
		$group_value	= JFilterOutput::stringURLSafe($group_option);

		$db	= JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('f.*');
		$query->from($db->qn('#__icagenda_filters') . ' AS f');
		$query->where($db->qn('type') . ' = "customfield"');
		$query->where($db->qn('filter') . ' = "groups"');
		$query->where($db->qn('value') . ' = ' . $db->q($group_value));
		$query->where($db->qn('option') . ' = ' . $db->q($group_option));
		$db->setQuery($query);
		$option = $db->loadResult();

		if ( ! $option)
		{
			// Create and populate New Group object.
			$group = new \stdClass();
			$group->state	= 1;
			$group->type	= 'customfield';
			$group->filter	= 'groups';
			$group->value	= $group_value;
			$group->option	= $group_option;

			// Insert the object into the iCagenda filters table.
			$result = JFactory::getDbo()->insertObject('#__icagenda_filters', $group);

			echo $group_value;
		}

		Jexit();
	}

	/**
	 * Function to check if a custom field group is set to any custom field
	 *
	 * @since	3.6.0
	 */
	static public function checkCustomFieldGroup()
	{
		$jinput			= JFactory::getApplication()->input;
		$id				= $jinput->get('id', '');
		$group_option	= $jinput->get('group', '', 'raw');
		$group_value	= JFilterOutput::stringURLSafe($group_option);
		$count = 0;

		$db	= JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('groups, id');
		$query->from($db->qn('#__icagenda_customfields'));
		$query->where($db->qn('groups') . ' <> ""');
//		$query->where($db->qn('id') . ' <> ' . $db->q($id));
//		$query->where($db->qn('state') . ' = 1');
		$db->setQuery($query);
		$list = $db->loadObjectList();

		foreach ($list AS $l)
		{
			$groups = explode(',', $l->groups);

			if (in_array($group_value, $groups)
				&& $l->id !== $id)
			{
				$count++;
			}
		}

		if ($count)
		{
			echo $count;
		}

		Jexit();
	}

	/**
	 * Function to delete a custom field group
	 *
	 * @since	3.6.0
	 */
	static public function deleteCustomFieldGroup()
	{
		$jinput			= JFactory::getApplication()->input;
		$group_option	= $jinput->get('group', '', 'raw');
		$group_value	= JFilterOutput::stringURLSafe($group_option);

		$db = JFactory::getDbo();
 		$query = $db->getQuery(true);
 		$query->delete($db->qn('#__icagenda_filters'));
		$query->where($db->qn('type') . ' = "customfield"');
		$query->where($db->qn('filter') . ' = "groups"');
		$query->where($db->qn('value') . ' = ' . $db->q($group_value));
 		$db->setQuery($query);
 		$result = $db->execute();

		if ($result)
		{
			echo $group_value;
		}

		Jexit();
	}
}
