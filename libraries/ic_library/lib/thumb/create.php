<?php
/**
 *----------------------------------------------------------------------------
 * iC Library   Library by Jooml!C, for Joomla!
 *----------------------------------------------------------------------------
 * @version     2.2.0 2024-02-23
 *
 * @package     iC Library
 * @subpackage  Thumb
 * @link        https://www.joomlic.com
 *
 * @author      Cyril Rezé
 * @copyright   (c) 2013-2024 Cyril Rezé / iCagenda. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 *
 * @since       1.0.0
 *----------------------------------------------------------------------------
*/

\defined('_JEXEC') or die;

/**
 * iC Library thumb class
 */
class iCThumbCreate
{
	/**
	 * Create thumbnail image by php using the GD Library
	 *
	 * @since   1.0.0
	 */
	static public function createThumb($source_image, $destination_image_url, $width, $height, $quality, $crop = null, $prefix = null, $checksize = null)
	{
		//Set image ratio
		list($w, $h) = getimagesize($source_image);

		// resize
		if ($crop === true)
		{
			if ($checksize
				&& ($w < $width or $h < $height)
				)
			{
				$width	= $w+1;
				$height	= $h+1;
				$x		= 0;
			}
			else
			{
				$ratio	= max($width/$w, $height/$h);
				$h		= $height / $ratio;
				$x		= ($w - $width / $ratio) / 2;
				$w		= $width / $ratio;
			}
		}
		else
		{
			if ($checksize
				&& ($w < $width or $h < $height)
				)
			{
				$width	= $w;
				$height	= $h;
				$x		= 0;
			}
			else
			{
				$ratio	= min($width/$w, $height/$h);
				$width	= $w * $ratio;
				$height	= $h * $ratio;
				$x		= 0;
			}
		}

		$width = (int) $width;
		$height = (int) $height;
		$w = (int) $w;
		$h = (int) $h;
		$x = (int) $x;

		if (preg_match("/.jpg/i","$source_image") OR preg_match("/.jpeg/i","$source_image"))
		{
			//JPEG type thumbnail
			$destImage = imagecreatetruecolor($width, $height);
			$sourceImage = imagecreatefromjpeg($source_image);
			imagecopyresampled($destImage, $sourceImage, 0, 0, $x, 0, $width, $height, $w, $h);
			imagejpeg($destImage, $destination_image_url, $quality);
			imagedestroy($destImage);
		}
		elseif (preg_match("/.png/i", "$source_image"))
		{
			//PNG type thumbnail
			$destImage = imagecreatetruecolor ($width, $height);
			$sourceImage = imagecreatefrompng($source_image);
			imagealphablending($destImage, false);
			imagecopyresampled($destImage, $sourceImage, 0, 0, $x, 0, $width, $height, $w, $h);
			imagesavealpha($destImage, true);
			imagepng($destImage, $destination_image_url);
		}
		elseif (preg_match("/.gif/i", "$source_image"))
		{
			//GIF type thumbnail
			$destImage = imagecreatetruecolor($width, $height);
			$sourceImage = imagecreatefromgif($source_image);
			$bgc = imagecolorallocate ($destImage, 255, 255, 255);
			imagefilledrectangle ($destImage, 0, 0, $width, $height, $bgc);
			imagecopyresampled($destImage, $sourceImage, 0, 0, $x, 0, $width, $height, $w, $h);

			if(function_exists('imagegif'))
			{
				// Pour GIF
				header('Content-Type: image/gif');

				imagegif($destImage, $destination_image_url);
			}

			imagedestroy($destImage);
		}
		else
		{
			echo 'unable to load image source';
		}
	}
}
