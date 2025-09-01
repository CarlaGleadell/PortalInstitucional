<?php
/**
 *----------------------------------------------------------------------------
 * iC Library   Library by Jooml!C, for Joomla!
 *----------------------------------------------------------------------------
 * @version     2.2.3 2025-04-22
 *
 * @package     iC Library
 * @subpackage  Thumb
 * @link        https://www.joomlic.com
 *
 * @author      Cyril Reze
 * @copyright   (c) 2012-2025 Cyril Reze / JoomliC. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 *
 * @since       1.0.0
 *----------------------------------------------------------------------------
*/

defined('_JEXEC') or die;

/**
 * iC Library image class
 */
class iCThumbGet
{
	/**
	 * Return the link to the thumbnail of an image
	 *
	 * @since   1.0.0
	 */
	static public function thumbnail($image, $thumbsPath, $subFolder,
		$width, $height, $quality, $crop = null, $prefix = null, $type = null, $checksize = null, $name = null)
	{
		$app = JFactory::getApplication();

		$file_local		= JPATH_ROOT . '/' . $image;
		$file_distant	= $image;

		$linkToImage	= filter_var($image, FILTER_VALIDATE_URL) ? $file_distant : $file_local;
		$original_exist	= file_exists($linkToImage) ? true : false;

		// Set memory_limit if possible to 512mo, and check needed memory to generate thumbnails
		ini_set('memory_limit','512M');

		// Check if fopen is allowed
		$fopen = true;
		$result = ini_get('allow_url_fopen');

		if (empty($result))
		{
			$fopen = false;
		}

		// Initialize Vars
		$Image_Link 			= '';
		$Thumb_Link 			= '';
		$Display_Thumb 			= false;
		$MimeTypeOK 			= true;
		$MimeTypeERROR			= false;
		$Invalid_Link 			= false;
		$Invalid_Img_Format		= false;
		$fopen_bmp_error_msg	= false;

		$Invalid_LinkMsg		= '<i class="icon-warning"></i><br /><span style="color:red;"><strong>' . JText::_('ICLIB_INVALID_PICTURE_LINK') . '</strong></span>';
		$Wrong_img_format		= '<i class="icon-warning"></i><br/><span style="color:red;"><strong>' . JText::_('ICLIB_NOT_AUTHORIZED_IMAGE_TYPE') . '</strong><br/>' . JText::_('ICLIB_NOT_AUTHORIZED_IMAGE_TYPE_INFO') . '</span>';
		$fopen_bmp_error		= '<i class="icon-warning"></i><br/><span style="color:red;"><strong>' . JText::_('ICLIB_PHP_ERROR_FOPEN_COPY_BMP') . '</strong><br/>' . JText::_('ICLIB_PHP_ERROR_FOPEN_COPY_BMP_INFO') . '</span>';
		$error_icthumb			= '<i class="icon-warning"></i><br/><span style="color:red;"><strong>' . JText::_('ICLIB_ERROR_ICTHUMB') . '</strong><br/>' . JText::_('ICLIB_ERROR_ICTHUMB_INFO') . '</span>';
		$img_not_readable 		= '<i class="icon-warning text-danger"></i><br /><span class="text-danger"><strong>' . JText::_('Image is not readable!') . '</strong><br><small>' . JText::_('No thumbnails have been created.') . '</small></span>';

		// Mime-Type pre-settings
		$errorMimeTypeMsg 		= '<i class="icon-warning"></i><br /><span style="color:red;"><strong>' . JText::_('ICLIB_ERROR_MIME_TYPE') . '</strong><br/>' . JText::_('ICLIB_ERROR_MIME_TYPE_NO_THUMBNAIL');

		// Folder for copy of distant images, and jpg created from bmp
		$copyPath	 			= $thumbsPath . '/copy';

		$cropped = $crop ? 'c' : '';

		// Thumb Destination
		if ($name)
		{
			$thumb_name				= '';
		}
		elseif ($prefix)
		{
			$thumb_name				= $prefix.'_w'.$width.'h'.$height.'q'.$quality.$cropped.'_';
		}
		else
		{
			$thumb_name				= 'w'.$width.'h'.$height.'q'.$quality.$cropped.'_';
		}
		$thumb_destination		= $subFolder . '/' . $thumb_name;

		// Get Image File Infos
		$image_info		= pathinfo($image);
		$imgtitle		= $image_info['filename'];
		$imgextension	= strtolower($image_info['extension']); // To be checked if error with uppercase extension type

		// Clean file name
		jimport( 'joomla.filter.output' );
		$cleanFileName	= JFilterOutput::stringURLSafe($imgtitle) . '.' . $imgextension;
		$cleanTitle		= JFilterOutput::stringURLSafe($imgtitle);

		// Image pre-settings
		$image_value 			= $image;
		$Image_Link 			= $image;

		// url to thumbnails already created
		$Thumb_Link 			= $thumbsPath . '/' . $thumb_destination . $cleanFileName;
		$Thumb_aftercopy_Link 	= $thumbsPath . '/' . $thumb_destination . $cleanTitle . '.jpg';

		// url to copy original jpg created
		$MAX_aftercopy_Link 	= $copyPath . '/' . $cleanTitle . '.jpg';

		$file_copy		= JPATH_ROOT . '/' . $MAX_aftercopy_Link;

		// Fix for previous version, if original image is too small (ic_large image only, used in event details view)
		$original_too_small = false;

		if ($prefix == 'ic_large')
		{
			if ($original_exist)
			{
				$file_to_check	= filter_var($image, FILTER_VALIDATE_URL) ? $file_copy : $file_local;
				list($w, $h)	= getimagesize($file_to_check);

				if ($w < $width or $h < $height)
				{
					$max_image_size		= filter_var($image, FILTER_VALIDATE_URL) ? $MAX_aftercopy_Link : $image;
					$original_too_small	= true;
				}
			}
		}

		// Check if thumbnails already created
		if (file_exists(JPATH_ROOT . '/' . $Thumb_Link)
			|| $original_too_small)
		{
			$Thumb_Link = $original_too_small ? $max_image_size : $Thumb_Link;
			$Display_Thumb = true;
		}
		elseif (file_exists(JPATH_ROOT . '/' . $Thumb_aftercopy_Link)
			|| $original_too_small)
		{
			$Thumb_Link = $original_too_small ? $max_image_size : $Thumb_aftercopy_Link;
			$Display_Thumb = true;
		}

		// if thumbnails not already created, create thumbnails
		else
		{
			// If file cannot be read, we can't process thumbnails.
			$imageIsReadable = @file_get_contents($linkToImage);

			if ($imageIsReadable === false) {
				if ($linkToImage == $file_local) {
					// If image is local, we display original image.
					if ($type == 'imgTag' || $type == 'imgTagLinkModal') {
						$style = $app->isClient('administrator') ? ' style="outline: 5px solid var(--danger)"' : '';
						$img = '<img src="' . $image . '" width="' . $width . '" alt=""/' . $style . '>';
						$img.= $app->isClient('administrator') ? $img_not_readable : '';
						return $img;
					} else {
						$img = $image;
						$img.= $app->isClient('administrator') ? $img_not_readable : '';
						return $img;
					}
				} else {
					// If the image is an external URL and we can't read it to create a local copy, we should be cautious and not display it.
					return $app->isClient('administrator') ? $img_not_readable : '';
				}
			}

			if (file_exists($linkToImage))
			{
				// Test Mime-Type
				$fileinfos = getimagesize($linkToImage);
				$mimeType = $fileinfos['mime'];
				$extensionType = 'image/' . $imgextension;

				// SETTINGS ICTHUMB
				$errorMimeTypeInfo = '<span style="color:black;"><br/>' . JText::sprintf('ICLIB_ERROR_MIME_TYPE_INFO', $imgextension, $mimeType);

				// Error message if Mime-Type is not the same as extension
				if (($imgextension == 'jpeg') OR ($imgextension == 'jpg'))
				{
					if (($mimeType != 'image/jpeg') AND ($mimeType != 'image/jpg'))
					{
						$MimeTypeOK 	= false;
						$MimeTypeERROR 	= true;
					}
				}
				elseif ($imgextension == 'bmp')
				{
					if (($mimeType != 'image/bmp') AND ($mimeType != 'image/x-ms-bmp'))
					{
						$MimeTypeOK 	= false;
						$MimeTypeERROR 	= true;
					}
				}
				elseif ($mimeType != $extensionType)
				{
					$MimeTypeOK 	= false;
					$MimeTypeERROR 	= true;
				}
			}

			// If Error mime-type, no thumbnail creation
			if ($MimeTypeOK)
			{
				// Call function and create image thumbnail for events list in admin

				// If Image JPG, JPEG, PNG or GIF
				if (($imgextension == "jpg") OR ($imgextension == "jpeg") OR ($imgextension == "png") OR ($imgextension == "gif"))
				{
					$Thumb_Link = $Thumb_Link;

					if ( ! file_exists(JPATH_ROOT . '/' . $Thumb_Link))
					{
						if (filter_var($image_value, FILTER_VALIDATE_URL))
						{
							if ((iCUrl::url_exists($image_value)) AND ($fopen))
							{
								$testFile = JPATH_ROOT . '/' . $copyPath . '/' . $cleanFileName;

								if ( ! file_exists($testFile))
								{
									// Get the file
									$content = file_get_contents($image_value);

									// Store in the filesystem.
									$fp = fopen(JPATH_ROOT . '/' . $copyPath . '/' . $cleanFileName, "w");
									fwrite($fp, $content);
									fclose($fp);
								}

								$linkToImage = JPATH_ROOT . '/' . $copyPath . '/' . $cleanFileName;
								$image_value = $copyPath . '/' . $cleanFileName;
							}
							else
							{
								$linkToImage = $image_value;
							}
						}
						else
						{
							$linkToImage = JPATH_ROOT . '/' . $image_value;
						}

						if ((iCUrl::url_exists($linkToImage)) OR (file_exists($linkToImage)))
						{
							self::checkServerLimit($linkToImage);
							iCThumbCreate::createThumb($linkToImage, JPATH_ROOT . '/' . $Thumb_Link, $width, $height, $quality, $crop, $prefix, $checksize);
						}
						else
						{
							$Invalid_Link = true;
						}
					}
				}

				// If Image BMP
				elseif ($imgextension == "bmp")
				{
					$Image_Link = $copyPath . '/' . $cleanTitle . '.jpg';
					$Thumb_Link = $Thumb_aftercopy_Link;

					if (!file_exists(JPATH_ROOT . '/' . $Thumb_Link))
					{
						if (filter_var($image_value, FILTER_VALIDATE_URL))
						{
							if ((iCUrl::url_exists($image_value)) AND ($fopen))
							{
								$testFile = JPATH_ROOT . '/' . $copyPath . '/' . $cleanTitle . '.jpg';

								if (!file_exists($testFile))
								{
									// Get the file
									$content = file_get_contents($image_value);

									// Store in the filesystem.
									$fp = fopen(JPATH_ROOT . '/' . $copyPath . '/' . $cleanFileName, "w");
									fwrite($fp, $content);
									fclose($fp);
									$imageNewValue = JPATH_ROOT . '/' . $copyPath . '/' . $cleanFileName;
									imagejpeg(iCThumbImage::createFromBMP($imageNewValue), JPATH_ROOT . '/' . $copyPath . '/' . $cleanTitle . '.jpg', 100);
									unlink($imageNewValue);
								}
							}
							else
							{
								$linkToImage = $image_value;
							}
						}
						else
						{
							imagejpeg(iCThumbImage::createFromBMP(JPATH_ROOT . '/' . $image_value), JPATH_ROOT . '/' . $copyPath . '/' . $cleanTitle . '.jpg', 100);
						}

						$image_value = $copyPath . '/' . $cleanTitle . '.jpg';
						$linkToImage = JPATH_ROOT . '/' . $image_value;

						if (!$fopen)
						{
							$fopen_bmp_error_msg = true;
						}
						elseif ((iCUrl::url_exists($linkToImage)) OR (file_exists($linkToImage)))
						{
							self::checkServerLimit($linkToImage);
							iCThumbCreate::createThumb($linkToImage, JPATH_ROOT . '/' . $Thumb_Link, $width, $height, $quality, $crop, $prefix, $checksize);
						}
						else
						{
							$Invalid_Link = true;
						}
					}
				}

				// If Not authorized Image Format
				else
				{
					if ((iCUrl::url_exists($linkToImage)) OR (file_exists($linkToImage)))
					{
						$Invalid_Img_Format = true;
					}
					else
					{
						$Invalid_Link = true;
					}
				}

				if (!$Invalid_Link)
				{
					$Display_Thumb = true;
				}
			}
			// If error Mime-Type
			else
			{
				if (($imgextension == "jpg")
					OR ($imgextension == "jpeg")
					OR ($imgextension == "png")
					OR ($imgextension == "gif")
					OR ($imgextension == "bmp"))
				{
					$MimeTypeERROR = true;
				}
				else
				{
					$Invalid_Img_Format = true;
					$MimeTypeERROR = false;
				}
			}
		}

		if ($type == 'imgTag'
			||  $type == 'imgTagLinkModal')
		{
			// Display Thumbnail Image tag
			$thumbnailImgTag = '';
			if ($Invalid_Img_Format)
			{
				$thumbnailImgTag.= $app->isClient('administrator') ? $Wrong_img_format : '';
			}

			if ($Invalid_Link)
			{
				$thumbnailImgTag.= $app->isClient('administrator') ? $Invalid_LinkMsg : '';
			}

			if ($MimeTypeERROR)
			{
				$thumbnailImgTag.= $app->isClient('administrator') ? $errorMimeTypeMsg : '';
				$thumbnailImgTag.= $app->isClient('administrator') ? $errorMimeTypeInfo : '';
			}

			if ($fopen_bmp_error_msg)
			{
				$thumbnailImgTag.= $app->isClient('administrator') ? $fopen_bmp_error : '';
			}

			if ($Display_Thumb)
			{
				if ($imgextension == "bmp")
				{
					if ($type == 'imgTagLinkModal' && version_compare(JVERSION, '4.0', 'lt'))
					{
						$thumbnailImgTag.= '<a href="' . JURI::root( true ) . '/' . $MAX_aftercopy_Link.'" class="modal">';
					}

					$thumbnailImgTag.= '<img src="' . JURI::root( true ) . '/' . $Thumb_aftercopy_Link.'" alt="' . $imgtitle . '" />';

					if ($type == 'imgTagLinkModal' && version_compare(JVERSION, '4.0', 'lt'))
					{
						$thumbnailImgTag.= '</a>';
					}
				}
				else
				{
					if ($type == 'imgTagLinkModal' && version_compare(JVERSION, '4.0', 'lt'))
					{
						if (filter_var($Image_Link, FILTER_VALIDATE_URL))
						{
							$thumbnailImgTag.= '<a href="'.$Image_Link.'" class="modal">';
						}
						else
						{
							$thumbnailImgTag.= '<a href="' . JURI::root( true ) . '/' . $Image_Link.'" class="modal">';
						}
					}

					$thumbnailImgTag.= '<img src="' . JURI::root( true ) . '/' . $Thumb_Link.'" alt="' . $imgtitle . '" />';

					if ($type == 'imgTagLinkModal' && version_compare(JVERSION, '4.0', 'lt'))
					{
						$thumbnailImgTag.= '</a>';
					}
				}
			}

			if ((!file_exists(JPATH_ROOT . '/' . $Thumb_Link)) && ($image) && (!$fopen))
			{
				$thumbnailImgTag.=  $app->isClient('administrator') ? $error_icthumb : '';
			}

			return $thumbnailImgTag;
		}
		else
		{
			// Display Thumbnail Image tag
			$thumb_img = '';

			// Set Thumbnail
			$default_thumbnail = 'media/com_icagenda/images/nophoto.jpg';

			if ( $Invalid_Img_Format
				|| $Invalid_Link
				|| $MimeTypeERROR
				|| $fopen_bmp_error_msg
				|| ((!file_exists(JPATH_ROOT . '/' . $Thumb_Link)) && ($image)) )
			{
				$thumb_img = $default_thumbnail;
			}
			elseif ($Display_Thumb)
			{
				$thumb_img = $Thumb_Link;
			}

			return $thumb_img;
		}
	}

	/**
	 * Return the thumbnail from an image, embed inside html tags
	 *
	 * @since   1.0.0
	 */
	static public function thumbnailImgTag($image, $thumbsPath, $subFolder,
		$width, $height, $quality, $crop = null, $prefix = null)
	{
		$thumbnailImgTag = iCThumbGet::thumbnail($image, $thumbsPath, $subFolder, $width, $height, $quality, $crop, $prefix, 'imgTag');

		return $thumbnailImgTag;
	}

	/**
	 * Return the thumbnail from an image, embed inside html tags
	 * Link on thumbnail opens original image in modal
	 *
	 * @since   1.0.0
	 */
	static public function thumbnailImgTagLinkModal($image, $thumbsPath, $subFolder,
		$width, $height, $quality, $crop = null, $prefix = null)
	{
		$thumbnailImgTag = iCThumbGet::thumbnail($image, $thumbsPath, $subFolder, $width, $height, $quality, $crop, $prefix, 'imgTagLinkModal');

		return $thumbnailImgTag;
	}

	/**
	 * Function to estimate memory limit to generate thumbs depending on image size
	 * $linkToImage = url to image
	 *
	 * @since   1.3.5
	 */
	static public function checkServerLimit($linkToImage)
	{
		$app = JFactory::getApplication();

		$memory_limit 		= ini_get('memory_limit');
		list($w, $h)		= getimagesize($linkToImage);
		$rgba_factor		= 4;
		$security_factor	= 1.8;

		if (preg_match('/^(\d+)(.)$/', $memory_limit, $matches))
		{
			if ($matches[2] == 'M')
			{
				$memory_limit = $matches[1] * 1024 * 1024; // nnnM -> nnn MB
			}
			elseif ($matches[2] == 'K')
			{
				$memory_limit = $matches[1] * 1024; // nnnK -> nnn KB
			}
		}

		if (function_exists('memory_get_usage')
			&& ( (($w * $h * $rgba_factor * $security_factor) + memory_get_usage()) > $memory_limit )
			)
		{
			if ( $app->isClient('administrator') )
			{
				$alert_message = JText::sprintf('ICLIB_ERROR_ALERT_IMAGE_TOO_LARGE', $image);

				// Get the message queue
				$messages = $app->getMessageQueue();

				$display_alert_message = false;

				// If we have messages
				if (is_array($messages) && count($messages))
				{
					// Check each message for the one we want
					foreach ($messages as $key => $value)
					{
						if ($value['message'] == $alert_message)
						{
							$display_alert_message = true;
						}
					}
				}

				if ( ! $display_alert_message)
				{
					$app->enqueueMessage($alert_message, 'Warning');
				}

				return JText::_('ICLIB_ERROR_IMAGE_TOO_LARGE');
			}
			else
			{
				return false;
			}
		}
	}
}
