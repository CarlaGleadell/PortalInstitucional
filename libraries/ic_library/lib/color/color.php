<?php
/**
 *----------------------------------------------------------------------------
 * iC Library   Library by Jooml!C, for Joomla!
 *----------------------------------------------------------------------------
 * @version     1.4.7 2018-08-23
 *
 * @package     iC Library
 * @subpackage  Color
 * @link        https://www.joomlic.com
 *
 * @author      Cyril Rezé
 * @copyright   (c) 2012-2024 Cyril Rezé / iCagenda. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 *
 * @since       1.0.0
 *----------------------------------------------------------------------------
*/

defined('_JEXEC') or die;

/**
 * class iCColor
 */
abstract class iCColor
{
	/**
	 * Function to convert color : hexadecimal to RGB
	 *
	 * @param   color to be converted in hexadecimal ('#xxxxxx')
	 * 
	 * @return  converted color in RGB ('xxx,xxx,xxx')
	 *
	 * @since   1.0.0
	 */
	public static function hex_to_rgb($color)
	{
		if ( ! is_array($color)
			&& preg_match("/^[#]([0-9a-fA-F]{6})$/", $color))
		{
			$hex_R = substr($color, 1, 2);
			$hex_G = substr($color, 3, 2);
			$hex_B = substr($color, 5, 2);
			$RGB  = hexdec($hex_R) . ',' . hexdec($hex_G) . ',' . hexdec($hex_B);

			return $RGB;
		}
		else
		{
			return false;
		}
	}

	/**
	 * Function to convert color : RGB to hexadecimal
	 *
	 * @param   color to be converted in RGB ('xxx,xxx,xxx' or 'array(xxx,xxx,xxx)')
	 * 
	 * @return  converted color in hexadecimal ('#xxxxxx')
	 *
	 * @since   1.0.0
	 */
	public static function rgb_to_hex($color)
	{
		if ( ! is_array($color))
		{
			$color_array = explode(",", $color);
		}

		if (is_array($color_array) && count($color_array) === 3)
		{
			$hex_RGB = '';

			foreach ($color_array as $value)
			{
				$hex_value = dechex($value);

				if (strlen($hex_value) < 2)
				{
					$hex_value = '0' . $hex_value;
				}

				$hex_RGB.= $hex_value;
			}

			return '#' . $hex_RGB;
		}
		else
		{
			return false;
		}
	}

	/**
	 * Function to get brightness of hexadecimal color
	 * https://www.w3.org/TR/AERT/#color-contrast
	 *
	 * @param   color in hexadecimal ('#xxxxxx')
	 * 
	 * @return  string ('dark' or 'bright')
	 *
	 * @since   1.0.0
	 */
	public static function getBrightness($color)
	{
		if ( ! is_array($color)
			&& preg_match("/^[#]([0-9a-fA-F]{6})$/", $color)
			&& strlen($color) == 7)
		{
			$get_RGB = iCColor::hex_to_rgb($color);
			$RGB     = explode(',', $get_RGB);

			$brightness = ((($RGB[0] * '299') + ($RGB[1] * '587') + ($RGB[2] * '114')) / '1000'); 

			return ($brightness > '125') ? 'bright' : 'dark';
		}
		else
		{
			return false;
		}
	}
}
