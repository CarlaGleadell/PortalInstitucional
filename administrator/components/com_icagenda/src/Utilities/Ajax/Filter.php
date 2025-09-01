<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.8.0 2021-09-27
 *
 * @package     iCagenda.Admin
 * @subpackage  src.Utilities.Ajax
 * @link        https://www.icagenda.com
 *
 * @author      Cyril Rezé
 * @copyright   (c) 2012-2024 Cyril Rezé / iCagenda. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 *
 * @since       3.6
 *----------------------------------------------------------------------------
*/

namespace iCutilities\Ajax;

\defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Filter\OutputFilter;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Layout\FileLayout;

/**
 * class icagendaAjaxFilter
 */
class Filter
{
	/**
	 * Method to get the Custom Field Groups options.
	 *
	 * @return  layout  Field layout HTML renderer.
	 *
	 * @since   3.8.0
	 */
	static public function getOptionsCustomfieldGroups()
	{
		$input = Factory::getApplication()->input;

		$jsondata = $input->get('jsondata', '', 'raw');
		$value    = $input->get('value');
		$selected = $input->get('selected');
		$values   = $input->get('values', array());
		$fieldid  = $input->getInt('fieldid');

		$db    = Factory::getDbo();
		$query = $db->getQuery(true);

		$query->select('f.*');
		$query->from($db->qn('#__icagenda_filters') . ' AS f');
		$query->where($db->qn('type') . ' = "customfield"');
		$query->where($db->qn('filter') . ' = "groups"');
		$query->where($db->qn('state') . ' = 1');
		$query->order('f.option ASC');
		$db->setQuery($query);

		$getOptions = $db->loadObjectList();

		$options = array();

		foreach ($getOptions as $opt)
		{
			$options[] = HTMLHelper::_('select.option', $opt->value, $opt->option);
		}

		if ($jsondata)
		{
			$data = json_decode($jsondata, true);

			$data['options'] = (array) $options;

//			if ($value)
//			{
				if ($selected && ! in_array($value, $values))
				{
					$values[] = $value;
				}
				elseif (! $selected)
				{
					$key = array_search($value, $values);

					if ($key)
					{
						unset($values[$key]);
					}
				}

				$data['value'] = $values;

				$updated_values = implode(',', $values);

				$query = $db->getQuery(true);
				$query->update($db->quoteName('#__icagenda_customfields'))
					->set($db->quoteName('groups') . ' = ' . $db->quote($updated_values))
					->where($db->quoteName('id') . ' = ' . (int) $fieldid);
				$db->setQuery($query);

				try
				{
						$db->execute();
				}
				catch (\RuntimeException $e)
				{
					Factory::getApplication()->enqueueMessage($e->getMessage(), 'error');

//					return false;
				}
//			}
//			else
//			{
//				echo 'NO VALUE!';
//			}

			$layout = new FileLayout('joomla.form.field.list-fancy-select');

			echo $layout->render($data); 
		}

		Jexit();
	}

	/**
	 * Function to save a new custom field group
	 */
	static public function saveCustomFieldGroup()
	{
		$input = Factory::getApplication()->input;

		$group_option = $input->get('group', '', 'raw');
		$group_value  = OutputFilter::stringURLSafe($group_option);

		$db    = Factory::getDbo();
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
			$group->state  = 1;
			$group->type   = 'customfield';
			$group->filter = 'groups';
			$group->value  = $group_value;
			$group->option = $group_option;

			// Insert the object into the iCagenda filters table.
			$result = Factory::getDbo()->insertObject('#__icagenda_filters', $group);

			echo $group_value;
		}

		Jexit();
	}

	/**
	 * Function to check if a custom field group is set to any custom field
	 */
	static public function checkCustomFieldGroup()
	{
		$input = Factory::getApplication()->input;

		$id           = $input->getInt('id');
		$group_option = $input->get('group', '', 'raw');
		$group_value  = OutputFilter::stringURLSafe($group_option);
		$count        = 0;

		$db    = Factory::getDbo();
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
	 */
	static public function deleteCustomFieldGroup()
	{
		$input = Factory::getApplication()->input;

		$group_option = $input->get('group', '', 'raw');
		$group_value  = OutputFilter::stringURLSafe($group_option);

		$db    = Factory::getDbo();
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
