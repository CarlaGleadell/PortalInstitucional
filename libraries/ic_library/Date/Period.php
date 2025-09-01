<?php
/**
 *----------------------------------------------------------------------------
 * iC Library   Library by Jooml!C, for Joomla!
 *----------------------------------------------------------------------------
 * @version     2.2.3 2025-04-22
 *
 * @package     iC Library
 * @subpackage  Date
 * @link        https://www.joomlic.com
 *
 * @author      Cyril Rezé
 * @copyright   (c) 2013-2025 Cyril Rezé / JoomliC. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 *
 * @since       1.1.0
 *----------------------------------------------------------------------------
*/

namespace iClib\Date;

\defined('_JEXEC') or die;

use Joomla\CMS\HTML\HTMLHelper;
use iClib\Date\Date as iCDate;

/**
 * class iCDatePeriod
 */
class Period
{
	/**
	 * Function to return all dates of a period (from ... to ...)
	 *
	 * @param   $startdate  Start datetime of the period (0000-00-00 00:00:00)
	 * @param   $enddate    End datetime of the period (0000-00-00 00:00:00)
	 * @param   $timezone   Time zone to be used for the date.
	 *                      Special cases: boolean true for user setting, boolean false for server setting.
	 *                      Default: null, no timezone.
	 *
	 * @return  Array of all dates of the period
	 */
	public static function listDates($startdate, $enddate, $timezone = null)
	{
		$test_startdate = iCDate::isDate($startdate);
		$test_enddate   = iCDate::isDate($enddate);

		$out = [];

		if ($test_startdate && $test_enddate) {
			$timestartdate = HTMLHelper::date($startdate, 'H:i:s', $timezone);
			$timeenddate   = HTMLHelper::date($enddate, 'H:i:s', $timezone);

			// Create array with all dates of the period
			$start = new \DateTime($startdate);

			$interval = '+1 days';
			$date_interval = \DateInterval::createFromDateString($interval);

			if ($timeenddate < $timestartdate) {
				$end = new \DateTime("$enddate +1 days");
			} else {
				$end = new \DateTime($enddate);
			}

			// Return all dates.
			if (version_compare(PHP_VERSION, '8.2.0', '>=')) {
				$perioddates = new \DatePeriod($start, $date_interval, $end, \DatePeriod::INCLUDE_END_DATE);
			} else {
				$perioddates = new \DatePeriod($start, $date_interval, $end);
			}

			foreach ($perioddates as $date) {
				$out[] = (
					$date->format('Y-m-d H:i')
				);
			}
		}

		return $out;
	}

	/**
	 * Return weekdays data to array of days of the week selected
	 *
	 * @param   $weekdays  Weekdays saved in database (x,x,x)
	 *
	 * @return  Array of all weekdays of the period
	 *
	 * @since   1.2.0
	 */
	public static function weekdaysToArray($i_weekdays)
	{
		$allWeekDays = [0, 1, 2, 3, 4, 5, 6];

		$weekdays = isset($i_weekdays) ? $i_weekdays : [];
		$weekdays = explode (',', $weekdays);

		$weekdaysarray = [];

		foreach ($weekdays as $day) {
			array_push($weekdaysarray, $day);
		}

		if (\in_array('', $weekdaysarray)) {
			// B/C Joomla 2.5 multiple select
			$arrayWeekDays = $allWeekDays;
		} elseif ($i_weekdays) {
			$arrayWeekDays = $weekdaysarray;
		} elseif (\in_array('0', $weekdaysarray)) {
			// Sunday only selected
			$arrayWeekDays = $weekdaysarray;
		} else {
			$arrayWeekDays = $allWeekDays;
		}

		return $arrayWeekDays;
	}
}
