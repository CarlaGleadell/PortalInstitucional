<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.8.0 2021-09-28
 *
 * @package     iCagenda.Admin
 * @subpackage  src.Utilities.Info
 * @link        https://www.icagenda.com
 *
 * @author      Cyril Rezé
 * @copyright   (c) 2012-2024 Cyril Rezé / iCagenda. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 *
 * @since       3.5.6
 *----------------------------------------------------------------------------
*/

namespace iCutilities\Info;

\defined('_JEXEC') or die;

use Joomla\CMS\Component\ComponentHelper;

/**
 * class icagendaInfo
 */
class Info
{
	/**
	 * Function to add comment with iCagenda version (used for faster support)
	 */
	static public function commentVersion()
	{
		$params   = ComponentHelper::getParams('com_icagenda');
		$release  = $params->get('release', '');
		$icsys    = $params->get('icsys', 'core');

		$icagenda = 'iCagenda ' . strtoupper($icsys) . ' ' . $release;

		if ($icsys == 'core')
		{
			$icagenda.= ' by Jooml!C - https://www.icagenda.com';
		}

		echo "<!-- " . $icagenda . " -->";

		return true;
	}
}
