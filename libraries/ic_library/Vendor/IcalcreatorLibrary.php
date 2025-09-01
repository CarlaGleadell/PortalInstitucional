<?php
/**
 *----------------------------------------------------------------------------
 * iC Library   Library by Jooml!C, for Joomla!
 *----------------------------------------------------------------------------
 * @version     2.0.0 2022-02-18
 *
 * @package     iC Library
 * @subpackage  Library
 * @link        https://www.joomlic.com
 *
 * @author      Cyril Rezé
 * @copyright   (c) 2013-2024 Cyril Rezé / iCagenda. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 *
 * @since       1.5
 *----------------------------------------------------------------------------
*/

namespace iClib\Vendor;

\defined('_JEXEC') or die;

require_once JPATH_LIBRARIES . '/ic_library/Vendor/Icalcreator/autoload.php';

use iClib\Date\Date as iCDate;
use Joomla\CMS\Factory;
use Joomla\CMS\Filter\OutputFilter;
use Joomla\CMS\Uri\Uri;
use Kigkonsult\Icalcreator\Vcalendar;

/**
 * class iCalcreator
 */
class IcalcreatorLibrary
{
	/**
	 * Function to create and return Calendar ICS file
	 *
	 * @param   object   $item    The item
	 */
	public static function returnIcs($item)
	{
		if (empty($item))
		{
			return;
		}

		$app = Factory::getApplication();

		// Load Joomla config options
		$sitename = $app->getCfg('sitename');
		$offset   = $app->getCfg('offset');

		// Get date from the session (current date of the event page)
		$session      = Factory::getSession();
		$session_date = $session->get('event_date');

		if (iCDate::isDate($session_date))
		{
			$eventDateTime = $session_date;
		}
		else
		{
			$url_date = $app->input->get('date', '');

			if ($url_date)
			{
				$sd_ex         = explode('-', $url_date);
				$eventDateTime = $sd_ex['0'] . '-' . $sd_ex['1'] . '-' . $sd_ex['2'] . ' ' . $sd_ex['3'] . ':' . $sd_ex['4'] . ':00';
			}
		}

		$eventDateTime = isset($eventDateTime) ? $eventDateTime : $item->next;

		// Set Time Offset for date of the event
		$dateTimeZone = new \DateTimeZone($offset);
		$dateTime     = new \DateTime($eventDateTime, $dateTimeZone);
		$timeOffset   = $dateTimeZone->getOffset($dateTime);
		$timezone     = ($timeOffset / 3600);

		$event_date = isset($eventDateTime)
					? date('Y-m-d-H-i', (strtotime($eventDateTime) - $timeOffset))
					: date('Y-m-d-H-i', (strtotime($item->next) - $timeOffset));

		$s_dates      = $item->dates;
		$single_dates = unserialize($s_dates);
		$single_dates = is_array($single_dates) ? $single_dates : array();

		$ex        = explode('-', $event_date);
		$this_date = $ex['0'].'-'.$ex['1'].'-'.$ex['2'].' '.$ex['3'].':'.$ex['4'];

		$startdate = date('Y-m-d-H-i', (strtotime($item->startdate) - $timeOffset));
		$enddate   = date('Y-m-d-H-i', (strtotime($item->enddate) - $timeOffset));

		if ( ($event_date >= $startdate)
			&& ($event_date <= $enddate)
			&& ( ! in_array($this_date, $single_dates)) )
		{
			$weekdays = ($item->weekdays || $item->weekdays == '0') ? true : false;
			$hasWeekdays = (boolean) ($item->weekdays || $item->weekdays == '0');

			if ($weekdays)
			{
				$startdate = date('Y-m-d-H-i', strtotime($this_date));
				$enddate   = date('Y-m-d', strtotime($this_date)) . '-' . date('H-i', strtotime($item->enddate)-$timeOffset);
			}

			$ex_S = explode('-', $startdate);
			$ex_E = explode('-', $enddate);

			$start_Datetime = $ex_S['0'] . $ex_S['1'] . $ex_S['2'] . 'T' . $ex_S['3'] . $ex_S['4'] . '00Z';
			$end_Datetime   = $ex_E['0'] . $ex_E['1'] . $ex_E['2'] . 'T' . $ex_E['3'] . $ex_E['4'] . '00Z';
		}
		else
		{
			$start_Datetime = $end_Datetime = $ex['0'] . $ex['1'] . $ex['2'] . 'T' . $ex['3'] . $ex['4'] . '00Z';
		}

		// Get URL to event details page.
		$uriString = Uri::getInstance()->toString();
		$eventURL  = preg_replace('/&tmpl=[^&]*/', '', $uriString);
		$eventURL  = preg_replace('/&vcal=[^&]*/', '', $eventURL);
		$eventURL  = preg_replace('/\?tmpl=[^\?]*/', '', $eventURL);
		$eventURL  = preg_replace('/\?vcal=[^\?]*/', '', $eventURL);


		// Create a new calendar
		$vcalendar = Vcalendar::factory( [ Vcalendar::UNIQUE_ID => $sitename, ] );

		// with calendaring info
		$vcalendar->setMethod( Vcalendar::PUBLISH )
			->setXprop(
				Vcalendar::X_WR_CALNAME,
				"iCagenda"
			)
			->setXprop(
				Vcalendar::X_WR_CALDESC,
				"Event Management Extension for Joomla!"
			)
			->setXprop(
				Vcalendar::X_WR_RELCALID,
				"3E26604A-50F4-4449-8B3E-E4F4932D05B5"
			)
			->setXprop(
				Vcalendar::X_WR_TIMEZONE,
				$offset
			);

		// Create a new event
		$event1 = $vcalendar->newVevent()
			->setTransp( Vcalendar::OPAQUE )
			->setClass( Vcalendar::P_BLIC )
			->setSequence( 1 )

			// describe the event
			->setCategories($item->cat_title)
			->setSummary($item->title)
			->setDescription(
				strip_tags($item->desc),
				[ Vcalendar::ALTREP =>
					'CID:<FFFF__=0ABBE548DFE235B58f9e8a93d@coffeebean.com>' ]
			)
//			->setComment( 'It\'s going to be fun...' )

			// place the event
			->setLocation($item->place)
			->setGeo($item->lat, $item->lng)
			->setUrl($eventURL)
			// set the time
			->setDtstart(
				new \DateTime(
					$start_Datetime
				)
			)
			->setDtend(
				new \DateTime(
					$end_Datetime
				)
			)

			// with recurrence rule
//			->setRrule(
//				[
//					Vcalendar::FREQ  => Vcalendar::WEEKLY,
//					Vcalendar::COUNT => 5,
//				]
//			)

			// and set another on a specific date
//			->setRdate(
//				[
//					new \DateTime(
//						'20190609T090000',
//						new \DateTimeZone( 'Europe/Stockholm' )
//					),
//					new \DateTime(
//						'20190609T110000',
//						new \DateTimeZone( 'Europe/Stockholm' )
//					),
//				],
//				[ Vcalendar::VALUE => Vcalendar::PERIOD ]
//			)

			// and revoke a recurrence date
//			->setExdate(
//				new \DateTime(
//					'2019-05-12 09:00:00',
//					new \DateTimeZone( 'Europe/Stockholm' )
//				)
//			)

			// organizer, chair and some participants
			->setOrganizer(
				$item->contact_email,
				[ Vcalendar::CN => $item->contact_name ]
			);
//			->setAttendee(
//				'president@coffeebean.com',
//				[
//					Vcalendar::ROLE     => Vcalendar::CHAIR,
//					Vcalendar::PARTSTAT => Vcalendar::ACCEPTED,
//					Vcalendar::RSVP     => Vcalendar::FALSE,
//					Vcalendar::CN       => 'President CoffeeBean',
//				]
//			)
//			->setAttendee(
//				'participant1@coffeebean.com',
//				[
//					Vcalendar::ROLE     => Vcalendar::REQ_PARTICIPANT,
//					Vcalendar::PARTSTAT => Vcalendar::NEEDS_ACTION,
//					Vcalendar::RSVP     => Vcalendar::TRUE,
//					Vcalendar::CN       => 'Participant1 CoffeeBean',
//				]
//			);

		// add alarm for the event
//		$alarm = $event1->newValarm()
//			->setAction( Vcalendar::DISPLAY )

			// copy description from event
//			->setDescription( $event1->getDescription())

			// fire off the alarm one day before
//			->setTrigger( '-P1D' );

		// alter day and time for one event in recurrence set
//		$event2 = $vcalendar->newVevent()
//			->setTransp( Vcalendar::OPAQUE )
//			->setClass( Vcalendar::P_BLIC )

			// reference to event in recurrence set
//			->setUid( $event1->getUid())
//			->setSequence( 2 )

			// pointer to event in the recurrence set
//			->setRecurrenceid( '20190505T090000 Europe/Stockholm' )

			// reason text
//			->setDescription(
//				'Altered day and time for event 2019-05-05',
//				[ Vcalendar::ALTREP =>
//					'CID:<FFFF__=0ABBE548DFE235B58f9e8a93d@coffeebean.com>' ]
//			)
//			->setComment( 'Now we are working hard for two hours' )

			// the altered day and time with duration
//			->setDtstart(
//				new \DateTime(
//					'20190504T100000',
//					new \DateTimeZone( 'Europe/Stockholm' )
//				)
//			)
//			->setDuration( 'PT2H' )

			// add alarm (copy from event1)
//			->setComponent(
//				$event1->getComponent( Vcalendar::VALARM )
//			);

		$vcalendarString =
			// apply appropriate Vtimezone with Standard/DayLight components
			$vcalendar->vtimezonePopulate()
			// and create the (string) calendar
			->createCalendar();

		if ($app->get('unicodeslugs') == 1)
		{
			$name = OutputFilter::stringUrlUnicodeSlug($item->title);
		}
		else
		{
			$name = OutputFilter::stringURLSafe($item->title);
		}

		if (empty($name))
		{
			// Use 'icagenda' in case name is still empty
			$name = 'icagenda';
		}

		$utf8Encode = false;
		$gzip       = false;
		$cdType     = true;
		$filename   = $name . '.ics';

		$vcalendar->returnCalendar($utf8Encode, $gzip, $cdType, $filename);

		exit;
	}
}
