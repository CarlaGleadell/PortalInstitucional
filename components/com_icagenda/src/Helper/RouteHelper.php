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

namespace WebiC\Component\iCagenda\Site\Helper;

use Joomla\CMS\Categories\CategoryNode;
use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Multilanguage;

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

/**
 * iCagenda Component Route Helper.
 *
 * @since  3.8.11
 */
abstract class RouteHelper
{
	/**
	 * Get the event route. (DEV)
	 *
	 * @param   integer  $id        The route of the event item.
	 * @param   integer  $catid     The category ID.
	 * @param   integer  $language  The language code.
	 * @param   string   $layout    The layout value.
	 *
	 * @return  string  The event route.
	 *
	 * @since   3.8.11
	 */
	public static function getEventRoute($id, $catid = 0, $language = 0, $layout = null, $date = null, $Itemid = null)
	{
		// Create the link
		$link = 'index.php?option=com_icagenda&view=event&id=' . $id;

//		if ((int) $catid > 1) {
//			$link .= '&catid=' . $catid;
//		}

		if ($Itemid) {
			$link .= '&Itemid=' . $Itemid;
		}

		if ($date) {
			$link .= '&date=' . $date;
		}

		if ($language && $language !== '*' && Multilanguage::isEnabled()) {
			$link .= '&lang=' . $language;
		}

		if ($layout) {
			$link .= '&layout=' . $layout;
		}

		return $link;
	}

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
				$dateControl = '&filter_from=' . HTMLHelper::date('now', 'Y-m-d');
				break;
			case 2:
				// Past Events
				$dateControl = '&filter_to=' . HTMLHelper::date('now', 'Y-m-d');
				break;
			case 3:
				// Upcoming Events
				$dateControl = '&filter_from=' . HTMLHelper::date('now', 'Y-m-d');
				break;
			case 4:
				// Current and Today's Upcoming Events
				$dateControl = '&filter_from=' . HTMLHelper::date('now', 'Y-m-d') . '&filter_to=' . HTMLHelper::date('now', 'Y-m-d');
				break;
		}

		if (version_compare(JVERSION, '4.0', 'lt')) {
			return 'index.php?option=com_icagenda&view=list&Itemid=' . $Itemid
				. $dateControl
				. '&filter_category=' . $id;
		} else {
			return 'index.php?option=com_icagenda&view=events&Itemid=' . $Itemid
				. $dateControl
				. '&filter_category=' . $id;
		}
	}
}
