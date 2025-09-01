<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.8.8 2022-08-01
 *
 * @package     iCagenda.Admin
 * @subpackage  src.Utilities.Categories
 * @link        https://www.icagenda.com
 *
 * @author      Cyril Rezé
 * @copyright   (c) 2012-2024 Cyril Rezé / iCagenda. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 *
 * @since       3.4
 *----------------------------------------------------------------------------
*/

namespace iCutilities\Categories;

\defined('_JEXEC') or die;

use Joomla\CMS\Factory;

/**
 * class icagendaCategories
 */
class Categories
{
	/**
	 * Function to return list of categories
	 *
	 * @param   $state  (if not defined, state is published ('1'))
	 *
	 * @return  list array of categories
	 */
	static public function getList($state = null)
	{
		$state = $state ? (int) $state : '1';

		// Preparing connection to db
		$db = Factory::getDbo();

		// Preparing the query
		$query = $db->getQuery(true);
		$query->select('c.color AS color, c.title AS title')
			->from('#__icagenda_category AS c');

		$query->where('c.state = ' . (int) $state);

		$db->setQuery($query);
		$list = $db->loadObjectList();

		if ($list)
		{
			return $list;
		}

		return false;
	}

	/**
	 * Function to return title of a category with id.
	 *
	 * @param   $id     id of the category
	 *
	 * @return  string  Title of the category
	 * 
	 * @since   3.8.8
	 */
	static public function getTitle($id = null)
	{
		// Preparing connection to db
		$db = Factory::getDbo();

		// Preparing the query
		$query = $db->getQuery(true);
		$query->select('title')
			->from($db->quoteName('#__icagenda_category'));

		$query->where($db->quoteName('id') . ' = ' . (int) $id);

		$db->setQuery($query);
		$title = $db->loadResult();

		if ($title)
		{
			return $title;
		}

		return false;
	}
}
