<?php
/**
 *----------------------------------------------------------------------------
 * iC Library   Library by Jooml!C, for Joomla!
 *----------------------------------------------------------------------------
 * @version     2.0.0 2021-09-27
 *
 * @package     iC Library
 * @subpackage  Render
 * @link        https://www.joomlic.com
 *
 * @author      Cyril Rezé
 * @copyright   (c) 2013-2024 Cyril Rezé / iCagenda. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 *
 * @since       1.4.0
 *----------------------------------------------------------------------------
*/

namespace iClib\Render;

\defined('_JEXEC') or die;

use iClib\Url\Url as iCUrl;

/**
 * class iCRender
 */
class Render
{
	/**
	 * Function to return Url TAG
	 */
	static public function urlTag($url, $target = null, $nofollow = true)
	{
		$target   = $target ? $target : '_blank';
		$nofollow = $nofollow ? ' rel="nofollow"' : '';

		$link = iCUrl::urlParsed($url, 'scheme');

		return '<a href="' . $link . '"' . $nofollow . ' target="' . $target . '">' . $url . '</a>';
	}
}
