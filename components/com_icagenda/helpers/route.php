<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.8.11 2022-12-20
 *
 * @package     iCagenda.Site
 * @subpackage  com_icagenda.Helper
 * @link        https://www.icagenda.com
 *
 * @author      Cyril Rezé
 * @copyright   (c) 2012-2024 Cyril Rezé / iCagenda. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 *
 * @since       3.8.11
 *----------------------------------------------------------------------------
*/

defined('_JEXEC') or die;

/**
 * iCagenda Component Route Helper.
 *
 * @since  3.8.11
 */
abstract class iCagendaHelperRoute
{
	/**
	 * Get the list filtered by iCagenda Category route.
	 *
	 * @param   integer    $id      The Category ID.
	 * @param   Registery  $params  A Registry Object of the list params.
	 * @param   integer    $Itemid  The menu Itemid to use for results display.
	 *
	 * @return  string  The list of events filtered by category route.
	 *
	 * @since   3.8.11
	 * @todo    Add fallbacks for params and Itemid.
	 */
	public static function getListFilteredByCategoryRoute($id, $params = '', $Itemid = '')
	{
		// @todo : Improve this function depending on Filter by Dates and Frontend Search Filters settings.
		$filterByDate = $params->get('time', 1);

		switch ($filterByDate) {
			case 0:
				// All Events
				$dateControl = '';
				break;
			case 1:
				// Current and All Upcoming Events
				$dateControl = '&filter_from=' . JHtml::date('now', 'Y-m-d');
				break;
			case 2:
				// Past Events
				$dateControl = '&filter_to=' . JHtml::date('now', 'Y-m-d');
				break;
			case 3:
				// Upcoming Events
				$dateControl = '&filter_from=' . JHtml::date('now', 'Y-m-d');
				break;
			case 4:
				// Current and Today's Upcoming Events
				$dateControl = '&filter_from=' . JHtml::date('now', 'Y-m-d') . '&filter_to=' . JHtml::date('now', 'Y-m-d');
				break;
		}

		return 'index.php?option=com_icagenda&view=list&Itemid=' . $Itemid
				. $dateControl
				. '&filter_category=' . $id;
	}
}
