<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.8.0 2018-12-14
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
 * class icagendaMapsGoogle
 */
class icagendaMapsGoogle
{
	/**
	 * Load Google Maps Embed script.
	 *
	 * @param   string  $mapid    Element id used as wrapper for map
	 * @param   array   $options  Array of options. Example: array('apiKey' => 'xxx000', 'apiClient' => 'gme-xxx')
	 *
	 * @since   3.8.0
	 */
	public static function addJS($mapid, $options = array())
	{
		$options['apiKey'] = isset($options['apiKey']) ? $options['apiKey'] : '';

		JFactory::getDocument()->addScriptDeclaration('
			// @todo: Script front display here.
		');
	}
}
