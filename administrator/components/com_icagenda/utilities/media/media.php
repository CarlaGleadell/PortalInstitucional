<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.8.19 2023-10-11
 *
 * @package     iCagenda.Admin
 * @subpackage  utilities
 * @link        https://www.icagenda.com
 *
 * @author      Cyril Rezé
 * @copyright   (c) 2012-2024 Cyril Rezé / iCagenda. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 *
 * @since       3.8.19
 *----------------------------------------------------------------------------
*/

// No direct access to this file
defined('_JEXEC') or die();

/**
 * class icagendaMedia
 */
class icagendaMedia
{
	/**
	 * Return the iCagenda images path
	 */
	static public function iCagendaImagesPath()
	{
		// Get media path
		$params_media = JComponentHelper::getParams('com_media');
		$image_path   = $params_media->get('image_path', 'images');

		// Paths to icagenda folder
		$imagesPath = rtrim($image_path, "/") . '/icagenda';

		if (JFolder::exists($imagesPath)) {
			return $imagesPath;
		} else {
			return 'images/icagenda';
		}
	}

	/**
	 * Return the iCagenda thumbs path
	 */
	static public function iCagendaThumbsPath()
	{
		// Paths to thumbs folder
		$thumbsPath = self::iCagendaImagesPath() . '/thumbs';

		if (JFolder::exists($thumbsPath)) {
			return $thumbsPath;
		} else {
			return 'images/icagenda/thumbs';
		}
	}
}
