<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.8.22 2023-11-28
 *
 * @package     iCagenda.Admin
 * @subpackage  src.Utilities.Media
 * @link        https://www.icagenda.com
 *
 * @author      Cyril Rezé
 * @copyright   (c) 2012-2024 Cyril Rezé / iCagenda. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 *
 * @since       3.8.19
 *----------------------------------------------------------------------------
*/

namespace iCutilities\Media;

\defined('_JEXEC') or die;

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Filesystem\Folder;

/**
 * class icagendaMedia
 */
class Media
{
	/**
	 * Return the iCagenda images path
	 */
	static public function iCagendaImagesPath()
	{
		// Get media path
		$params_media = ComponentHelper::getParams('com_media');
		$image_path   = $params_media->get('image_path', 'images');

		if (Factory::getApplication()->isClient('administrator')) {
			$image_path_control = '../' . $image_path;
		} else {
			$image_path_control = $image_path;
		}

		// Paths to icagenda folder
		$imagesPath = rtrim($image_path, "/") . '/icagenda';

		if (Folder::exists($image_path_control)) {
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
		$image_path = self::iCagendaImagesPath();

		if (Factory::getApplication()->isClient('administrator')) {
			$image_path_control = '../' . $image_path;
		} else {
			$image_path_control = $image_path;
		}

		// Paths to thumbs folder
		$thumbsPath = $image_path . '/thumbs';

		$thumbs_path_control = $image_path_control . '/thumbs';

		if (Folder::exists($thumbs_path_control)) {
			return $thumbsPath;
		} else {
			return 'images/icagenda/thumbs';
		}
	}
}
