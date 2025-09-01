<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.8.0 2018-12-17
 *
 * @package     iCagenda.Admin
 * @subpackage  Utilities.maps
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

/**
 * class icagendaMapsLeaflet
 */
class icagendaMapsLeaflet
{
	/**
	 * Load Leaflet Library.
	 *
	 * @since   3.8.0
	 */
	public static function addLibrary()
	{
		JHtml::_('styleSheet', 'media/com_icagenda/leaflet/leaflet.css');
		JHtml::_('script', 'media/com_icagenda/leaflet/leaflet.js');
	}
}
