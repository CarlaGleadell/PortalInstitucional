<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.9.0 2024-02-19
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
 * Frontend Submit Form Menu ItemID Select Filter Field
 */
class icagendaFormFieldFilterSubmitMenuItemid extends JFormFieldList
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 */
	protected $type = 'FilterSubmitMenuItemid';

	/**
	 * Method to get the field options.
	 *
	 * @return  array  The field option objects.
	 */
	protected function getOptions()
	{
		// Create a new query object.
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);

		// Select the required fields from the table.
		$query->select('m.id AS itemid, m.link AS menu_link, m.title AS menu_title');
		$query->from('#__menu AS m');

		// Filter by published state
		$query->where('(m.link = "index.php?option=com_icagenda&view=submit")');
		$query->where('(m.published IN (0,1))');

		$db->setQuery($query);
		$itemids = $db->loadObjectList();

		$options = array();

		$options['']  = JTEXT::_('COM_ICAGENDA_SELECT_SITE_ITEMID');
		$options['0'] = JTEXT::_('COM_ICAGENDA_SELECT_CREATED_IN_ADMIN');

		if (count($itemids) > 0)
		{
			foreach ($itemids as $itemid)
			{
				$options[$itemid->itemid] = $itemid->itemid . ' - ' . $itemid->menu_title;
			}
		}

		return $options;
	}
}
