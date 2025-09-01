<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.9.0 2024-02-19
 *
 * @package     iCagenda.Admin
 * @subpackage  Utilities.Field.Icagenda
 * @link        https://www.icagenda.com
 *
 * @author      Cyril Rezé
 * @copyright   (c) 2012-2024 Cyril Rezé / iCagenda. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 *
 * @since       3.8.0
 *----------------------------------------------------------------------------
*/

namespace iCutilities\Field\Icagenda;

use Joomla\CMS\Factory;
use Joomla\CMS\Form\Field\ListField;
use Joomla\CMS\Language\Text;

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

/**
 * Frontend Submit Form Menu ItemID Select Filter Field
 */
class FilterSubmitMenuItemidField extends ListField
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
		$db = Factory::getDbo();

		$query = $db->getQuery(true);

		// Select the required fields from the table.
		$query->select('m.id AS itemid, m.link AS menu_link, m.title AS menu_title')
			->from('#__menu AS m');

		// Filter by published state
		$query->where('(m.link = "index.php?option=com_icagenda&view=submit")')
			->where('(m.published IN (0,1))');

		$db->setQuery($query);

		$itemids = $db->loadObjectList();

		$options = [];

		$options['']  = Text::_('COM_ICAGENDA_SELECT_SITE_ITEMID');
		$options['0'] = Text::_('COM_ICAGENDA_SELECT_CREATED_IN_ADMIN');

		if (\count($itemids) > 0) {
			foreach ($itemids as $itemid) {
				$options[$itemid->itemid] = $itemid->itemid . ' - ' . $itemid->menu_title;
			}
		}

		return $options;
	}
}
