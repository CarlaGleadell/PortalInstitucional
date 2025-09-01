<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.7.3 2018-08-09
 *
 * @package     iCagenda.Admin
 * @subpackage  Utilities
 * @link        https://www.icagenda.com
 *
 * @author      Cyril Rezé
 * @copyright   (c) 2012-2024 Cyril Rezé / iCagenda. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 *
 * @since       3.5.6
 *----------------------------------------------------------------------------
*/

defined('_JEXEC') or die;

/**
 * class icagendaInfo
 */
class icagendaInfo
{
	/**
	 * Function to add comment with iCagenda version (used for faster support)
	 *
	 * @since	3.4.0
	 */
	static public function commentVersion()
	{
		$params		= JComponentHelper::getParams('com_icagenda');
		$release	= $params->get('release', '');
		$icsys		= $params->get('icsys', 'core');

		$icagenda	= 'iCagenda ' . strtoupper($icsys) . ' ' . $release;

		if ($icsys == 'core')
		{
			$icagenda.= ' by Jooml!C - https://www.joomlic.com';
		}

		echo "<!-- " . $icagenda . " -->";

		return true;
	}
}
