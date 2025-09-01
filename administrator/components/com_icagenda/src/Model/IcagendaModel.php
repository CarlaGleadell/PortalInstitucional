<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.8.0 2021-10-11
 *
 * @package     iCagenda.Admin
 * @subpackage  src.Model
 * @link        https://www.icagenda.com
 *
 * @author      Cyril Rezé
 * @copyright   (c) 2012-2024 Cyril Rezé / iCagenda. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 *
 * @since       1.0
 *----------------------------------------------------------------------------
*/

namespace WebiC\Component\iCagenda\Administrator\Model;

\defined('_JEXEC') or die;

use Joomla\CMS\MVC\Model\ListModel;

/**
 * iCagenda Component CPanel Model.
 */
class IcagendaModel extends ListModel
{
	/**
	 * Build an SQL query to load CATEGORY STATS.
	 *
	 * @return  JDatabaseQuery
	 */
	public function getCategoryStats()
	{
		// Get database object
		$db    = $this->getDbo();
		$query = $db->getQuery(true);

		$query->select('e.catid AS catid, sum(e.hits) AS hits')
			->from('#__icagenda_events AS e');

		// Join over the category
		$query->select('c.id AS cat_id, c.title AS cat_title, c.title AS stats_label, c.color AS cat_color');
		$query->join('LEFT', '#__icagenda_category AS c ON c.id = e.catid');

		$query->group('c.id');
		$query->order('hits DESC');

		$db->setQuery($query, 0, 10);

		$list = $db->loadObjectList();

		return $list;
	}

	/**
	 * Build an SQL query to return total of event hits.
	 *
	 * @return  JDatabaseQuery
	 */
	public function getEventHitsTotal()
	{
		// Get database object
		$db    = $this->getDbo();
		$query = $db->getQuery(true);

		$query->select('sum(e.hits) AS hits')
			->from('#__icagenda_events AS e');

		$db->setQuery($query);

		$list = $db->loadResult();

		return $list;
	}

	/**
	 * Build an SQL query to load EVENT STATS.
	 *
	 * @return  JDatabaseQuery
	 */
	public function getEventStats()
	{
		// Get database object
		$db    = $this->getDbo();
		$query = $db->getQuery(true);

		$query->select('e.title AS stats_label, e.hits AS hits')
			->from('#__icagenda_events AS e');

		// Join over the category
		$query->select('c.id AS cat_id, c.title AS cat_title, c.color AS cat_color');
		$query->join('LEFT', '#__icagenda_category AS c ON c.id = e.catid');

		$query->order('hits DESC');

		$db->setQuery($query, 0, 10);

		$list = $db->loadObjectList();

		return $list;
	}
}
