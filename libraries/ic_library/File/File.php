<?php
/**
 *----------------------------------------------------------------------------
 * iC Library   Library by Jooml!C, for Joomla!
 *----------------------------------------------------------------------------
 * @version     2.0.0 2021-09-27
 *
 * @package     iC Library
 * @subpackage  File
 * @link        https://www.joomlic.com
 *
 * @author      Cyril Rezé
 * @copyright   (c) 2013-2024 Cyril Rezé / iCagenda. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 *
 * @since       1.0.1
 *----------------------------------------------------------------------------
*/

namespace iClib\File;

\defined('_JEXEC') or die;

/**
 * class iCFile
 */
class File
{
	/**
	 * Function to check if a string is defined inside a file.
	 *
	 * @param   $string         String to be checked
	 * @param   $file_location  Path or url to the file to be tested
	 *
	 * @return  true/false.
	 */
	public static function hasString($string, $file_location)
	{
		if (ini_get('allow_url_fopen') && is_file($file_location))
		{
			$file_to_test = file_get_contents($file_location);

			$has_string = strpos($file_to_test, $string) ? true : false;

			return $has_string;
		}
	}
}
