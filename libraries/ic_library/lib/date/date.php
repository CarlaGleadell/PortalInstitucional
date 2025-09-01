<?php
/**
 *----------------------------------------------------------------------------
 * iC Library   Library by Jooml!C, for Joomla!
 *----------------------------------------------------------------------------
 * @version     2.0.1 2022-07-28
 *
 * @package     iC Library
 * @subpackage  Date
 * @link        https://www.joomlic.com
 *
 * @author      Cyril Rezé
 * @copyright   (c) 2013-2024 Cyril Rezé / iCagenda. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 *
 * @since       1.0.3
 *----------------------------------------------------------------------------
*/

// No direct access to this file
defined('_JEXEC') or die();

/**
 * class iCDate
 */
class iCDate
{
	/**
	 * Function to test if date is a valid Datetime
	 *
	 * @access	public static
	 * @param	$date : date to be tested (eg. 1993-04-30 14:33:00)
	 * @return	alias
	 *
	 * @since   1.1.0
	 */
	static public function isDate($date)
	{
		if ($date == '-0001-11-30 00:00') return false;

		if ( ! isset($date) || ! $date) return false;

		$stamp = strtotime($date);

		$date_numeric = iCDate::dateToNumeric($date);

		$date_valid = str_replace('0', '', $date_numeric);

		if ( ! is_numeric($stamp)
			|| ! $date_valid)
		{
			return false;
		}

		return true;
	}

	/**
	 * Function to convert datetime to numeric
	 *
	 * @access	public static
	 * @param	$date: date to convert (1993-04-30 14:33:00 -> 19930430143300)
	 * @return	alias
	 *
	 * @since   1.1.0
	 */
	static public function dateToNumeric($date)
	{
		$date = preg_replace("/[^0-9]/","", $date);

		return $date;
	}

	/**
	 * Function to convert datetime to alias
	 *
	 * @access	public static
	 * @param	$date: date to convert (1993-04-30 14:33:00 -> 1993-04-30-14-33-00)
	 * 			$format: format of the date before convertion (eg. Y-m-d if only date)
	 * 			default format: use parent format
	 * @return	alias
	 *
	 * @since   1.0.3
	 */
	static public function dateToAlias($date, $format = null)
	{
		if ( ! self::isDate($date)) return;

		if ($format)
		{
			$date = date($format, strtotime($date));
		}

		$replace = array(' ', ':');
		$date = str_replace($replace, '-', $date);

		return $date;
	}

	/**
	 * Format Month Short Core - From Joomla language file xx-XX.ini (eg. Apr)
	 *
	 * @access	public static
	 * @param	$date: date to convert (1993-04-30 14:33:00 -> Apr)
	 * @return	translation string for MONTH_SHORT
	 *
	 * @since   1.2.0
	 */
	static public function monthShortJoomla($date)
	{
		$month            = date('F', strtotime($date));
		$monthShortJoomla = JText::_($month . '_SHORT');

		return $monthShortJoomla;
	}

	/**
	 * Format Month Core - From Joomla language file xx-XX.ini (eg. April)
	 *
	 * @access	public static
	 * @param	$date: date to convert (1993-04-30 14:33:00 -> April)
	 * @return	translation string for MONTH
	 *
	 * @since   1.4.0
	 */
	static public function monthJoomla($date)
	{
		$month       = date('F', strtotime($date));
		$monthJoomla = JText::_($month);

		return $monthJoomla;
	}
}
