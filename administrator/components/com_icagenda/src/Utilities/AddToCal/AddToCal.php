<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.8.0 2022-01-30
 *
 * @package     iCagenda.Admin
 * @subpackage  src.Utilities.AddToCal
 * @link        https://www.icagenda.com
 *
 * @author      Cyril Rezé
 * @copyright   (c) 2012-2024 Cyril Rezé / iCagenda. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 *
 * @since       3.6
 *----------------------------------------------------------------------------
*/

namespace iCutilities\AddToCal;

\defined('_JEXEC') or die;

use iClib\String\StringHelper as iCString;
use iCutilities\Event\Event as icagendaEvent;
use Joomla\CMS\Factory;

/**
 * class icagendaAddtocal
 */
class AddToCal
{
	/**
	 * Function to return Url to add to Google Calendar
	 */
	static public function googleCalendar($i)
	{
		$app    = Factory::getApplication();
		$jinput = $app->input;

		$text         = $i->title . ' (' . $i->cat_title . ')';
		$details      = $i->desc;
		$venue        = $i->place;
		$s_dates      = $i->dates;
		$single_dates = iCString::isSerialized($i->dates) ? unserialize($i->dates) : array(); // returns array
		$website      = icagendaEvent::eventURL($i);
		$location     = $venue ? $venue . ' - ' . $i->address : $i->address;

		$get_date = '';
		$href     = '#';

		if ($jinput->get('date'))
		{
			// if 'All Dates' set
			$get_date = $jinput->get('date');
		}
		else
		{
			// if 'Only Next/Last Date' set
			$get_date = date('Y-m-d-H-i', strtotime($i->next));
		}

		$ex        = explode('-', $get_date);
		$this_date = $ex['0'] . '-' . $ex['1'] . '-' . $ex['2'] . ' ' . $ex['3'] . ':' . $ex['4'];

		$startdate = date('Y-m-d-H-i', strtotime($i->startdate));
		$enddate   = date('Y-m-d-H-i', strtotime($i->enddate));

		if (icagendaEvent::eventHasPeriod($i->period, $i->startdate, $i->enddate)
			&& ($get_date >= $startdate)
			&& ($get_date <= $enddate)
			&& (!in_array($this_date, $single_dates))
			)
		{
			$weekdays = ($i->weekdays || $i->weekdays == '0') ? true : false;

			if ($weekdays)
			{
				$startdate = date('Y-m-d-H-i', strtotime($this_date));
				$enddate   = date('Y-m-d', strtotime($this_date)) . '-' . date('H-i', strtotime($i->enddate));
			}

			$ex_S = explode('-', $startdate);
			$ex_E = explode('-', $enddate);

			$dateday = $ex_S['0'] . $ex_S['1'] . $ex_S['2'] . 'T' . $ex_S['3'] . $ex_S['4'];
			$dateday.= '00/' . $ex_E['0'] . $ex_E['1'] . $ex_E['2'] . 'T' . $ex_E['3'] . $ex_E['4'] . '00';
		}
		else
		{
			$dateday = $ex['0'] . $ex['1'] . $ex['2'] . 'T' . $ex['3'] . $ex['4'];
			$dateday.= '00/' . $ex['0'] . $ex['1'] . $ex['2'] . 'T' . $ex['3'] . $ex['4'] . '00';
		}

		// Get the site name
		$sitename = $app->getCfg('sitename');

		$href = 'https://www.google.com/calendar/event?action=TEMPLATE';

		$mbString = extension_loaded('mbstring');
		$text     = $mbString ? mb_substr($text, 0, 100, 'UTF-8') : substr($text, 0, 100);
		$len      = strrpos($text, ' ');  // interruption on a space
		$text     = substr($text, 0, $len);

		$href.= '&text=' . urlencode($text) . '...';
		$href.= '&dates=' . $dateday;
		$href.= '&location=' . urlencode($location);
		$href.= '&trp=true';

		$limit_reduc     = '37'; // 37 chars (&trp=true&details=&sf=true&output=xml)
		$limit_notlogged = '785';
		$lenpart         = strlen($href);
		$lenlast         = 2068 - $lenpart - $limit_reduc - $limit_notlogged; // max link length minus (title+location)
		$details         = urlencode(strip_tags($details));
		$details         = substr($details, 0 , $lenlast);
		$len             = strrpos($details, '+');
		$details         = substr($details, 0 , $len);

		$href.= '&details=' . substr($details, 0, $lenlast) . '...';

		return $href;
	}

	/**
	 * Function to return Url to add to Windows Live (Hotmail) Calendar
	 */
	static public function windowsliveCalendar($i)
	{
		$jinput = Factory::getApplication()->input;

		$text         = $i->title.' ('.$i->cat_title.')';
		$details      = $i->desc;
		$venue        = $i->place;
		$s_dates      = $i->dates;
//		$single_dates = unserialize($s_dates);
		$single_dates = iCString::isSerialized($i->dates) ? unserialize($i->dates) : array(); // returns array
		$website      = icagendaEvent::eventURL($i);
		$location     = $venue ? $venue . ' - ' . $i->address : $i->address;

		$get_date = '';
		$href     = '#';
		$endday   = '';

		if ($jinput->get('date'))
		{
			// if 'All Dates' set
			$get_date = $jinput->get('date');
		}
		else
		{
			// if 'Only Next/Last Date' set
			$get_date = date('Y-m-d-H-i', strtotime($i->next));
		}

		$ex        = explode('-', $get_date);
		$this_date = $ex['0'] . '-' . $ex['1'] . '-' . $ex['2'] . ' ' . $ex['3'] . ':' . $ex['4'];

		$startdate = date('Y-m-d-H-i', strtotime($i->startdate));
		$enddate   = date('Y-m-d-H-i', strtotime($i->enddate));

		if ( icagendaEvent::eventHasPeriod($i->period, $i->startdate, $i->enddate)
			&& $get_date >= $startdate
			&& $get_date <= $enddate
			&& !in_array($this_date, $single_dates)
			)
		{
			$weekdays = ($i->weekdays || $i->weekdays == '0') ? true : false;

			if ($weekdays)
			{
//				$startdate = date('Y-m-d-H-i', strtotime($this_date));
//				$enddate   = date('Y-m-d', strtotime($this_date)) . '-' . date('H-i', strtotime($i->enddate));
				$dateday = date('Y-m-d', strtotime($this_date)) . 'T' . date('H:i', strtotime($this_date)) . ':00Z';
				$endday  = date('Y-m-d', strtotime($this_date)) . 'T' . date('H:i', strtotime($i->enddate)) . ':00Z';
			}
			else
			{
				$dateday = date('Y-m-d', strtotime($i->startdate)) . 'T' . date('H:i', strtotime($i->startdate)) . ':00Z';
				$endday  = date('Y-m-d', strtotime($i->enddate)) . 'T' . date('H:i', strtotime($i->enddate)) . ':00Z';
			}

//			$ex_S = explode('-', $startdate);
//			$ex_E = explode('-', $enddate);

//			$dateday = $ex_S['0'] . $ex_S['1'] . $ex_S['2'] . 'T' . $ex_S['3'] . $ex_S['4'] . '00';
//			$endday  = $ex_E['0'] . $ex_E['1'] . $ex_E['2'] . 'T' . $ex_E['3'] . $ex_E['4'] . '00';
		}
		else
		{
//			$dateday = $ex['0'] . $ex['1'] . $ex['2'] . 'T' . $ex['3'] . $ex['4'] . '00';
			$dateday = date('Y-m-d', strtotime($this_date)) . 'T' . date('H:i', strtotime($this_date)) . ':00Z';
		}

		$href = "https://outlook.live.com/calendar/0/deeplink/compose?rru=addevent";
		$href.= "&path=" . urlencode('/calendar/action/compose');
//		$href.= "&dtstart=" . $dateday;
		$href.= "&startdt=" . $dateday;
//		$href.= isset($endday) ? "&dtend=" . $endday : '';
		$href.= isset($endday) ? "&enddt=" . $endday : '';
//		$href.= "&summary=" . urlencode($text);
		$href.= "&subject=" . urlencode($text);
		$href.= "&location=" . urlencode($location);

		// Shortens the description, if more than 1000 characters
		$lengthMax        = '1000';
		$details          = urlencode(strip_tags($details));
		$details          = substr($details, 0, $lengthMax);
		$shortenedDetails = strrpos($details, '+');
		$details          = substr($details, 0, $shortenedDetails);

//		$href.= "&description=" . substr($details, 0, $lengthMax) . '...';
		$href.= "&body=" . substr($details, 0, $lengthMax) . '...';

		return $href;
	}

	/**
	 * Function to return Url to add to Yahoo Calendar
	 */
	static public function yahooCalendar($i)
	{
		$jinput = Factory::getApplication()->input;

		$text         = $i->title . ' (' . $i->cat_title . ')';
		$details      = $i->desc;
		$venue        = $i->place;
		$s_dates      = $i->dates;
//		$single_dates = unserialize($s_dates);
		$single_dates = iCString::isSerialized($i->dates) ? unserialize($i->dates) : array(); // returns array
		$website      = icagendaEvent::eventURL($i);
		$location     = $venue ? $venue . ' - ' . $i->address : $i->address;

		$get_date = '';
		$href     = '#';
		$endday   = '';

		if ($jinput->get('date'))
		{
			// if 'All Dates' set
			$get_date = $jinput->get('date');
		}
		else
		{
			// if 'Only Next/Last Date' set
			$get_date = date('Y-m-d-H-i', strtotime($i->next));
		}

		$ex        = explode('-', $get_date);
		$this_date = $ex['0'] . '-' . $ex['1'] . '-' . $ex['2'] . ' ' . $ex['3'] . ':' . $ex['4'];

		$startdate = date('Y-m-d-H-i', strtotime($i->startdate));
		$enddate   = date('Y-m-d-H-i', strtotime($i->enddate));

		if ( icagendaEvent::eventHasPeriod($i->period, $i->startdate, $i->enddate)
			&& $get_date >= $startdate
			&& $get_date <= $enddate
			&& ! in_array($this_date, $single_dates)
			)
		{
			$weekdays = ($i->weekdays || $i->weekdays == '0') ? true : false;

			if ($weekdays)
			{
				$startdate = date('Y-m-d-H-i', strtotime($this_date));
				$enddate   = date('Y-m-d', strtotime($this_date)) . '-' . date('H-i', strtotime($i->enddate));
			}

			$ex_S = explode('-', $startdate);
			$ex_E = explode('-', $enddate);

			$dateday = $ex_S['0'] . $ex_S['1'] . $ex_S['2'] . 'T' . $ex_S['3'] . $ex_S['4'] . '00';

//			$diff = strtotime($i->enddate) - strtotime($i->startdate);
//			$M = (floor($diff /60)) % 60;
//			$M = sprintf("%02d", $M);
//			$H = (floor($diff / 3600));

//			$duration = ($H <= 24) ? $H . $M : '';
			$endday = $ex_E['0'] . $ex_E['1'] . $ex_E['2'] . 'T' . $ex_E['3'] . $ex_E['4'] . '00';
		}
		else
		{
			$dateday = $ex['0'] . $ex['1'] . $ex['2'] . 'T' . $ex['3'] . $ex['4'] . '00';
//			$duration = '';
		}

		// Shortens the description, if more than 1000 characters
		$lengthMax        = '1000';
		$details          = urlencode(strip_tags($details));
		$details          = substr($details, 0, $lengthMax);
		$shortenedDetails = strrpos($details, '+');
		$details          = substr($details, 0, $shortenedDetails);

		$href = "https://calendar.yahoo.com/?v=60";
		$href.= "&VIEW=d";
		$href.= "&in_loc=" . urlencode($location);
//		$href.= "&type=20";
		$href.= "&TITLE=" . urlencode($text);
		$href.= "&ST=" . $dateday;
		$href.= "&ET=" . $endday;
//		$href.= "&DUR=";
//		$href.= $duration ? "&DUR=" . $duration : '';
		$href.= "&DESC=" . substr($details, 0, $lengthMax) . '...';
		$href.= "&URL=" . urlencode($website);

		return $href;
	}
}
