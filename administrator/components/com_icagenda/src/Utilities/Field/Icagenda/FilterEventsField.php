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
use WebiC\Component\iCagenda\Administrator\Model\RegistrationsModel;

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

/**
 * Events Select Filter Field
 */
class FilterEventsField extends ListField
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 */
	protected $type = 'FilterEvents';

	/**
	 * Name of the layout being used to render the field
	 *
	 * @var  string
	 */
//	protected $layout = 'joomla.form.field.list-fancy-select';

	/**
	 * Method to get the field options.
	 *
	 * @return  array  The field option objects.
	 */
	protected function getOptions()
	{
		$model = new RegistrationsModel;

		// Create a new query object.
		$db = Factory::getDbo();

		$catId = $db->escape($model->getState('filter.categories'));

		$query = $db->getQuery(true);

		// Select the required fields from the table.
		$query->select('e.id AS event, e.title AS title')
			->from('#__icagenda_events AS e');

		if (! empty($catId)) {
			$query->where('e.catid = ' . (int) $catId);
		}

		// Join over the categories.
		$query->select('c.id AS cat_id, c.title AS cat_title')
			->join('LEFT', '#__icagenda_category AS c ON c.id=e.catid');

		// Join over the registrations.
		$query->select('r.eventid AS eventid')
			->join('LEFT', '#__icagenda_registration AS r ON r.eventid=e.id')
			->where('(e.id = r.eventid)');

		$query->order('e.title ASC');

		$db->setQuery($query);

		$events = $db->loadObjectList();

		$list = [];

		foreach ($events as $e) {
			$list[$e->event] = [
				'value' => $e->event,
				'text'  => $e->title . ' [' . $e->event . ']'
			];
		}

		return array_merge(parent::getOptions(), $list);
	}
}
