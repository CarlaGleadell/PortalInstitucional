<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.9.4 2024-06-13
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
 * Categories Filter Field
 */
class FilterCategoriesField extends ListField
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 */
	protected $type = 'FilterCategories';

	/**
	 * Method to get the field options.
	 *
	 * @return  array  The field option objects.
	 */
	protected function getOptions()
	{
		// Create a new query object.
		$db    = Factory::getDbo();
		$query = $db->getQuery(true);

		// Select the required fields from the table.
		$query->select('c.id AS cat_id, c.title AS cat_title')
			->from('#__icagenda_category AS c');

		// Join over the events.
		$query->select('e.id AS id')
			->join('LEFT', '#__icagenda_events AS e ON e.catid=c.id');

		// Join over the registrations.
		$query->select('r.eventid AS event_id')
			->join('LEFT', '#__icagenda_registration AS r ON r.eventid=e.id')
			->where('(e.id = r.eventid)')
			->order('c.ordering ASC')
			->group('c.id');

		$db->setQuery($query);

		$categories = $db->loadObjectList();

		$list = [];

		foreach ($categories as $k => &$c) {
			$list[] = [
				'value' => $c->cat_id,
				'text' => Text::_($c->cat_title) . ' [' . $c->cat_id . ']',
			];
		}

		return array_merge(parent::getOptions(), $list);
	}
}
