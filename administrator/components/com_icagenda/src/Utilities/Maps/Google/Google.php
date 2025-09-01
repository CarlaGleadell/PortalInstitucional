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

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;

/**
 * class icagendaMapsGoogle
 */
class Google
{
	/**
	 * Load Google Maps Embed script.
	 * NOT YET USED!
	 *
	 * @param   string  $mapid    Element id used as wrapper for map
	 * @param   array   $options  Array of options. Example: array('apiKey' => 'xxx000', 'apiClient' => 'gme-xxx')
	 */
	public static function addJS($mapid, $options = array())
	{
		$options['apiKey'] = isset($options['apiKey']) ? $options['apiKey'] : '';

		Factory::getDocument()->addScriptDeclaration('
			// @todo: Script front display here.
		');
	}
}
