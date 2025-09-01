<?php
/**
 *----------------------------------------------------------------------------
 * iC Library   Library by Jooml!C, for Joomla!
 *----------------------------------------------------------------------------
 * @version     2.0.0 2021-08-17
 *
 * @package     iC Library
 * @subpackage  Filter
 * @link        https://www.joomlic.com
 *
 * @author      Cyril Rezé
 * @copyright   (c) 2013-2024 Cyril Rezé / iCagenda. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 *
 * @since       1.0.0
 *----------------------------------------------------------------------------
*/

namespace iClib\Filter;

\defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\String\StringHelper;

/**
 * class iCFilterOutput
 */
class Output
{
	/**
	 * Process a string in a JOOMLA_TRANSLATION_STRING standard.
	 * This method processes a string and replaces all accented UTF-8 characters by unaccented
	 * ASCII-7 "equivalents" and the string is uppercase. Spaces replaced by underscore.
	 *
	 * @param   string  $string  String to process
	 *
	 * @return  string  Processed string
	 */
	public static function stringToJText($string)
	{
		// Remove any '_' from the string since they will be used as concatenaters
		$str = str_replace('_', ' ', $string);

		$lang = Factory::getLanguage();
		$str = $lang->transliterate($str);

		// Trim white spaces at beginning and end of translation string and make uppercase
		$str = trim(StringHelper::strtoupper($str));

		// Remove any duplicate whitespace, and ensure all characters are alphanumeric
		$str = preg_replace('/(\s|[^A-Za-z0-9\-])+/', '_', $str);

		// Trim underscores at beginning and end of translation string
		$str = trim($str, '_');

		return $str;
	}

	/**
	 * Process a string in slug format.
	 * This method processes a string and replaces all accented UTF-8 characters by unaccented
	 * ASCII-7 "equivalents" and the string is lowercase. Spaces replaced by underscore.
	 *
	 * @param   string     $string        String to process
	 *          string(1)  $concatenater  Concatener (default underscore - data slug)
	 *
	 * @return  string  Processed string
	 */
	public static function stringToSlug($string, $concatenater = '_')
	{
		// Remove any '$concatenater' from the string since they will be used as concatenaters
		$concatenater = (strlen($concatenater) == 1) ? $concatenater : '_';
		$str = str_replace($concatenater, ' ', $string);

		// Replaces all accented UTF-8 characters by unaccented ASCII-7 "equivalents"
		$lang = Factory::getLanguage();
		$str = $lang->transliterate($str);

		// Trim white spaces at beginning and end of translation string and make lowercase
		$str = trim(StringHelper::strtolower($str));

		// Remove any duplicate whitespace, and ensure all characters are alphanumeric
		$str = preg_replace('/(\s|[^A-Za-z0-9])+/', $concatenater, $str);

		// Trim '$concatenater' at beginning and end of translation string
		$str = trim($str, $concatenater);

		return $str;
	}

	/**
	 * Convert a HTML string in a text single line.
	 * This method processes a string, cleans all HTML tags and converts special characters to HTML entities.
	 * The string is lowercase. Spaces replaced by underscore.
	 *
	 * @param   string  $string  String to process
	 *
	 * @return  string  Processed string
	 */
	public static function fullCleanHTML($string)
	{
		// Clean text of all formatting and scripting code
		$str = preg_replace("'<script[^>]*>.*?</script>'si", '', $string);
		$str = preg_replace('/<a\s+.*?href="([^"]+)"[^>]*>([^<]+)<\/a>/is', '\2 (\1)', $str);
		$str = preg_replace('/<!--.+?-->/', '', $str);
		$str = preg_replace('/{.+?}/', '', $str);

		// Strip HTML and PHP tags
		$str = strip_tags($str);

		// Replace all sequences of two or more spaces, tabs, and/or line breaks with a single space
		$str = preg_replace('/[\p{Z}\s]{2,}/u', ' ', $str);

		// Convert special characters to HTML entities
		$str = htmlspecialchars($str, ENT_QUOTES, 'UTF-8');

		// Trim spaces at beginning and end of translation string
		$str = trim($str);

		return $str;
	}
}
