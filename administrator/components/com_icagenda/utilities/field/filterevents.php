<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.8.0 2021-08-23
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

use WebiC\Component\iCagenda\Administrator\Model\RegistrationsModel;

JFormHelper::loadFieldClass('list');

/**
 * Events Select Filter Field
 */
class icagendaFormFieldFilterEvents extends JFormFieldList
{
	protected $type = 'FilterEvents';

	protected function getOptions()
	{
		$model = new RegistrationsModel;

		// Create a new query object.
		$db    = JFactory::getDbo();

		$catId = $db->escape($model->getState('filter.categories'));

		$query = $db->getQuery(true);

		// Select the required fields from the table.
		$query->select('e.id AS event, e.title AS title');
		$query->from('#__icagenda_events AS e');

		if (! empty($catId))
		{
			$query->where('e.catid = ' . (int) $catId);
		}

		// Join over the categories.
		$query->select('c.id AS cat_id, c.title AS cat_title');
		$query->join('LEFT', '#__icagenda_category AS c ON c.id=e.catid');

		// Join over the registrations.
		$query->select('r.eventid AS eventid');
		$query->join('LEFT', '#__icagenda_registration AS r ON r.eventid=e.id');
		$query->where('(e.id = r.eventid)');

		$query->order('e.title ASC');

		$db->setQuery($query);
		$events = $db->loadObjectList();

		$list = array();

		foreach ($events as $e)
		{
			$list[$e->event] = array('value' => $e->event, 'text' => $e->title . ' [' . $e->event . ']');
		}

		return array_merge(parent::getOptions(), $list);
	}
}
