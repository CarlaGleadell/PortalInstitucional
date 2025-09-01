<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.9.4 2024-06-13
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
class icagendaFormFieldFilterCategories extends JFormFieldList
{
	protected $type = 'FilterCategories';

	protected function getOptions()
	{
		// Create a new query object.
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);

		// Select the required fields from the table.
		$query->select('c.id AS cat_id, c.title AS cat_title');
		$query->from('#__icagenda_category AS c');

		// Join over the events.
		$query->select('e.id AS id');
		$query->join('LEFT', '#__icagenda_events AS e ON e.catid=c.id');

		// Join over the registrations.
		$query->select('r.eventid AS event_id');
		$query->join('LEFT', '#__icagenda_registration AS r ON r.eventid=e.id');
		$query->where('(e.id = r.eventid)');
		$query->order('c.ordering ASC');
		$query->group('c.id');

		$db->setQuery($query);
		$categories = $db->loadObjectList();

		$list = [];

		foreach ($categories as $k => &$c)
		{
			$list[] = array('value' => $c->cat_id, 'text' => JText::_($c->cat_title) . ' [' . $c->cat_id . ']');
		}

		return array_merge(parent::getOptions(), $list);
	}
}
