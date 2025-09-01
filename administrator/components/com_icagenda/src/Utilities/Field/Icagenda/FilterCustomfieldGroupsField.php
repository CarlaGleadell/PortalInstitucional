<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.9.0 2024-02-18
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

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

/**
 * Custom Field Groups Filter Field
 */
class FilterCustomfieldGroupsField extends ListField
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 */
	protected $type = 'FilterCustomfieldGroups';

	/**
	 * Method to get the field options.
	 *
	 * @return  array  The field option objects.
	 */
	protected function getOptions()
	{
		$db = Factory::getDbo();

		$query = $db->getQuery(true);

		$query->select('f.value, f.option')
			->from('#__icagenda_filters AS f')
			->where('f.type = "customfield"')
			->where('f.filter = "groups"')
			->order('f.option ASC');

		$db->setQuery($query);

		$groups = $db->loadObjectList();

		$list = [];

		foreach ($groups as $g) {
			$list[$g->value] = $g->option;
		}

		return array_merge(parent::getOptions(), $list);
	}
}
