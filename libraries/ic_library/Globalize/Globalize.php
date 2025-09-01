<?php
/**
 *----------------------------------------------------------------------------
 * iC Library   Library by Jooml!C, for Joomla!
 *----------------------------------------------------------------------------
 * @version     2.1.2 2023-11-06
 *
 * @package     iC Library
 * @subpackage  Globalize
 * @link        https://www.joomlic.com
 *
 * @author      Cyril Rezé
 * @copyright   (c) 2013-2024 Cyril Rezé / iCagenda. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 *
 * @since       1.3.0
 *----------------------------------------------------------------------------
*/

namespace iClib\Globalize;

\defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;

/**
 * iC Globalize
 */
class Globalize
{
	/**
	 * Function to get Format Date (using option format, and translation)
	 *
	 * @param   datetime  $date       Date to be formatted (1993-04-30 14:33:00)
	 * @param   string    $option     Date format selected
	 * @param   string    $separator  Separator for ISO formats
	 * @param   string    $tz         Use or not a specific TimeZone
	 *
	 * @return  Formatted date
	 */
	public static function dateFormat($date, $option, $separator, $tz = false)
	{
		$dateTimeZone = $tz ? $tz : null;

		$lang     = Factory::getLanguage();
		$langTag  = $lang->getTag();
		$langName = $lang->getName();

		if ( ! file_exists(JPATH_LIBRARIES . '/ic_library/Globalize/culture/' . $langTag . '.php'))
		{
			$langTag = 'en-GB';
		}

		$globalize = JPATH_LIBRARIES . '/ic_library/Globalize/culture/' . $langTag . '.php';
		$iso       = JPATH_LIBRARIES . '/ic_library/Globalize/culture/iso.php';

		// Languages with English ordinal suffix for the day of the month, 2 characters
		$en_langs = array('en-GB', 'en-US');

		// Set iso format if format is equal to zero (Y-m-d)
		$option = ($option == '0') ? 'Y - m - d' : $option;

		if (is_numeric($option))
		{
			require $globalize;

			// Format with "th" only for english languages
			if ( ! in_array($langTag, $en_langs))
			{
				if ($option == '5') $option = '4';
				if ($option == '9') $option = '7';
				if ($option == '10') $option = '8';
			}

			// No Short month for Persian language
			elseif ($langTag == 'fa-IR')
			{
				if ($option == '3') $option = '2';
				if ($option == '11') $option = '7';
				if ($option == '12') $option = '8';
			}

			$format = ${"dateformat_" . $option};
		}
		else
		{
			require $iso;

			$format = $option;
		}

		// Load Globalization Date Format if selected

		// Explode components of the date
		$exformat = explode (' ', $format);

		// Set datetime format
		$thisDate = date('Y-m-d H:i:s', strtotime($date));
//		$month_n  = HTMLHelper::date($thisDate, 'n', $dateTimeZone); // 1 through 12
//		$day_w    = HTMLHelper::date($thisDate, 'w', $dateTimeZone); // 0 (for Sunday) through 6 (for Saturday)

		// Strings of translation for convertion
//		$array_days = array(
//			'SUNDAY', 'MONDAY', 'TUESDAY', 'WEDNESDAY', 'THURSDAY', 'FRIDAY', 'SATURDAY'
//		);

//		$array_days_short = array(
//			'SUN', 'MON', 'TUE', 'WED', 'THU', 'FRI', 'SAT'
//		);

//		$array_months = array(
//			'JANUARY', 'FEBRUARY', 'MARCH', 'APRIL', 'MAY', 'JUNE',
//			'JULY', 'AUGUST', 'SEPTEMBER', 'OCTOBER', 'NOVEMBER', 'DECEMBER'
//		);

		$dateFormatted = '';

		// Creates date formatted
		foreach ($exformat as $k => $val)
		{
			switch($val)
			{
				// Day
				case 'd':
					$val = HTMLHelper::date($thisDate, 'd', $dateTimeZone);
					break;

				case 'j':
					$val = HTMLHelper::date($thisDate, 'j', $dateTimeZone);
					break;

				case 'D':
					// A textual representation of day of the week, three letters (use Joomla Translation string)
					$val = HTMLHelper::date($thisDate, 'D', $dateTimeZone);
					break;

				case 'l':
					// A full textual representation of the day of the week (use Joomla Translation string)
					$val = HTMLHelper::date($thisDate, 'l', $dateTimeZone);
					break;

				case 'S':
					$val = '<sup>' . HTMLHelper::date($thisDate, 'S', $dateTimeZone) . '</sup>';
					break;

				case 'jS':
					$val = HTMLHelper::date($thisDate, 'j', $dateTimeZone) . '<sup>' . HTMLHelper::date($thisDate, 'S', $dateTimeZone) . '</sup>';
					break;

				// Month
				case 'm':
					$val = HTMLHelper::date($thisDate, 'm', $dateTimeZone);
					break;

				case 'F':
					// A full textual representation of a month (use Joomla Translation string)
					$val = HTMLHelper::date($thisDate, 'F', $dateTimeZone);
					break;

				case 'M':
					// A short textual representation of a month (use Joomla Translation string)
					$val = HTMLHelper::date($thisDate, 'M', $dateTimeZone);
					break;

				case 'n':
					$val = HTMLHelper::date($thisDate, 'n', $dateTimeZone);
					break;

				// year (v3)
				case 'Y':
					$val = HTMLHelper::date($thisDate, 'Y', $dateTimeZone);
					break;

				case 'y':
					$val = HTMLHelper::date($thisDate, 'y', $dateTimeZone);
					break;

				// Separator of the components
				case '*':
					$val = $separator;
					break;
				case '_':
					$val = '&nbsp;';
//					$val = '&#160;';
					break;

				// day
				case 'N':
					$val = HTMLHelper::date($thisDate, 'N', $dateTimeZone);
					break;
				case 'w':
					$val = HTMLHelper::date($thisDate, 'w', $dateTimeZone);
					break;
				case 'z':
					$val = HTMLHelper::date($thisDate, 'z', $dateTimeZone);
					break;

				// week
				case 'W':
					$val = HTMLHelper::date($thisDate, 'W', $dateTimeZone);
					break;

				// Default
				default:
					$val;
					break;
			}

			$dateFormatted.= ($k !== 0) ? '' . $val : $val;
		}

		return $dateFormatted;
	}
}
