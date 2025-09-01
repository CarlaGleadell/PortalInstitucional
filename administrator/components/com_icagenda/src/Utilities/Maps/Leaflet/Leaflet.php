<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.8.0 2021-09-28
 *
 * @package     iCagenda.Admin
 * @subpackage  src.Utilities.Maps
 * @link        https://www.icagenda.com
 *
 * @author      Cyril Rezé
 * @copyright   (c) 2012-2024 Cyril Rezé / iCagenda. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 *
 * @since       3.8
 *----------------------------------------------------------------------------
*/

namespace iCutilities\Maps\Leaflet;

\defined('_JEXEC') or die;

use Joomla\CMS\HTML\HTMLHelper;

/**
 * class icagendaMapsLeaflet
 */
class Leaflet
{
	/**
	 * Load Leaflet Library.
	 */
	public static function addLibrary()
	{
		HTMLHelper::_('styleSheet', 'media/com_icagenda/leaflet/leaflet.css');
		HTMLHelper::_('script', 'media/com_icagenda/leaflet/leaflet.js');
	}
}
