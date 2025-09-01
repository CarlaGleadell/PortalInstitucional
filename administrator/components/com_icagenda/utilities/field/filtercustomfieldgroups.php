<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.8.0 2021-09-06
 *
 * @package     iCagenda.Admin
 * @subpackage  Utilities.Form
 * @link        https://www.icagenda.com
 *
 * @author      Cyril Rezé
 * @copyright   (c) 2012-2024 Cyril Rezé / iCagenda. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 *
 * @since       3.8.0
 *----------------------------------------------------------------------------
*/

defined('_JEXEC') or die;

JFormHelper::loadFieldClass('list');

/**
 * Categories Select Filter Field
 */
class icagendaFormFieldFilterCustomfieldGroups extends JFormFieldList
{
	protected $type = 'FilterCustomfieldGroups';

	protected function getOptions()
	{
		$db = JFactory::getDbo();

		$query = $db->getQuery(true);

		$query->select('f.value, f.option');
		$query->from('#__icagenda_filters AS f');
		$query->where('f.type = "customfield"');
		$query->where('f.filter = "groups"');
		$query->order('f.option ASC');

		$db->setQuery($query);

		$groups = $db->loadObjectList();

		$list = array();

		foreach ($groups as $g)
		{
			$list[$g->value] = $g->option;
		}

		return array_merge(parent::getOptions(), $list);
	}
}
