<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.7.12 2020-02-19
 *
 * @package     iCagenda.Admin
 * @subpackage  Utilities
 * @link        https://www.icagenda.com
 *
 * @author      Cyril Rezé
 * @copyright   (c) 2012-2024 Cyril Rezé / iCagenda. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 *
 * @since       3.6.0
 *----------------------------------------------------------------------------
*/

defined('_JEXEC') or die;

/**
 * class icagendaEvent
 */
class icagendaEventData
{
	/**
	 * Loads the Event's custom fields for this item
	 *
	 * @return object list.
	 * @since   3.6.0
	 */
	static public function loadEventCustomFields($id = null)
	{
		// Get the database connector.
		$db = JFactory::getDbo();

		// Get the query from the database connector.
		$query = $db->getQuery(true);

		// Build the query programatically (using chaining if desired).
		$query->select('cfd.*, cf.*')
			// Use the qn alias for the quoteName method to quote table names.
			->from($db->qn('#__icagenda_customfields_data') . ' AS cfd');

		$query->leftJoin('#__icagenda_customfields AS cf ON cf.slug = cfd.slug');

		$query->where($db->qn('cfd.parent_id') . ' = ' . (int) $id);
		$query->where($db->qn('cfd.parent_form') . ' = 2');
		$query->where($db->qn('cf.parent_form') . ' = 2');
		$query->where($db->qn('cfd.state') . ' = 1');
		$query->where($db->qn('cf.state') . ' = 1');
		$query->where($db->qn('cfd.value') . ' NOT IN ("", "{}")');
		$query->order('cf.ordering ASC');

		// Tell the database connector what query to run.
		$db->setQuery($query);

		// Invoke the query or data retrieval helper.
		return $db->loadObjectList();
	}
}
