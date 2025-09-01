<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.8.0 2022-02-02
 *
 * @package     iCagenda.Admin
 * @subpackage  src.Utilities.Events
 * @link        https://www.icagenda.com
 *
 * @author      Cyril Rezé
 * @copyright   (c) 2012-2024 Cyril Rezé / iCagenda. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 *
 * @since       3.5.0
 *----------------------------------------------------------------------------
*/

namespace iCutilities\Events;

\defined('_JEXEC') or die;

use iClib\Date\Date as iCDate;
use iClib\Date\Period as iCDatePeriod;
use iClib\String\StringHelper as iCString;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\Utilities\ArrayHelper;

/**
 * class icagendaEventsData
 * Transitional class and functions
 *
 * TO BE DEPRECATED and TO BE REMOVED in 4.0
 */
class EventsData
{
	/**
	 * ALL DATES
	 */
	public static function getAllDates($filterTime = null, $datesDisplay = '', $orderby = null, $mcatid = null, $moduleParams = false)
	{
		$app    = Factory::getApplication();
		$jinput = $app->input;
		$params = $app->getParams();

		$search_filters = $params->get('search_filters', 0);

		if ( ! $moduleParams)
		{
			$activeMenu = $app->getMenu()->getActive();

			$menu_search_filters    = $activeMenu
									? $activeMenu->getParams()->get('search_filters')
									: '';

			$filters_mode = ($menu_search_filters == 1)
							? $params->get('filters_mode', 1)
							: ComponentHelper::getParams('com_icagenda')->get('filters_mode', 1);
		}
		else
		{
			$menu_search_filters    = '0';

			$filters_mode = '';
		}


		// Filter time : upcoming, past, today...
		$filterTime = ($filterTime == 'no') ? '0' : $filterTime;

		if ($menu_search_filters == 1)
		{
			$filterTime     = (isset($filterTime) || $filterTime == '0')
							? $filterTime
							: ( ($menu_search_filters == 1)
								? $params->get('time', 1)
								: ComponentHelper::getParams('com_icagenda')->get('time', 1) );
		}
		else
		{
			$filterTime     = (isset($filterTime) || $filterTime == '0')
							? $filterTime
							: $params->get('time', 1);
		}

		// Dates display : all dates OR only next/last date per event
		$datesDisplay = ($datesDisplay != '') ? $datesDisplay : $params->get('datesDisplay', 1);

		// Ordering : date order ASC OR DESC
		$orderby = $orderby ? $orderby : $params->get('orderby', 2);

		// Filter category : multiple categories filtering
		if ($mcatid !== 'all')
		{
			$mcatid = $mcatid ? $mcatid : $params->get('mcatid', '');
		}

		// Set vars
		$nodate         = '0000-00-00 00:00:00';
		$ic_nodate      = '0000-00-00 00:00';
		$eventTimeZone  = null;
		$datetime_today = HTMLHelper::date('now', 'Y-m-d H:i'); // Joomla Time Zone
		$date_today     = HTMLHelper::date('now', 'Y-m-d'); // Joomla Time Zone

		// Get Data
		$db = Factory::getDbo();
		$query = $db->getQuery(true);
//		$query->select('e.next, e.dates, e.startdate, e.enddate, e.period, e.weekdays, e.displaytime, e.id, e.catid');
		$query->select('e.*');
		$query->from('#__icagenda_events AS e');
		$query->leftJoin('#__icagenda_category AS c ON c.id = e.catid');
		$query->leftJoin('#__icagenda_customfields_data AS cfd ON cfd.parent_id = e.id');

		// CATEGORY STATE Filtering
		$query->where('c.state = 1');

		// EVENT STATE Filtering
		$query->where('e.state = 1');

		// Search in current list (menu filtered list)
		$currentListSearch = ($filters_mode == 1 && $search_filters);

		// Filter by category
		if ($mcatid != 'all')
		{
//			$filterCategory = $jinput->getInt('filter_category', '');
			$filterCategory = $moduleParams ? '' : $jinput->getInt('filter_category', '');

			if (is_numeric($filterCategory) && ! empty($filterCategory))
			{
				$query->where('e.catid = ' . $filterCategory . '');
			}
			elseif (is_array($filterCategory) && ! empty($filterCategory) && ! in_array('0', $filterCategory))
			{
				ArrayHelper::toInteger($filterCategory);
				$categoryId = implode(',', $filterCategory);
				$query->where('e.catid IN (' . $categoryId . ')');
			}

			// No category search filter active, use menu Filter by Category option
			elseif (is_numeric($mcatid) && ! empty($mcatid))
			{
				$query->where('e.catid = ' . $db->quote($mcatid));
			}
			elseif (is_array($mcatid) &&  ! empty($mcatid) && ! in_array('0', $mcatid))
			{
				ArrayHelper::toInteger($mcatid);
				$list_catid = implode(',', $mcatid);
				$query->where('e.catid IN (' . $list_catid . ')');
			}

			if ($jinput->get('filter_category')
				&& ! $currentListSearch
				&& ! $moduleParams)
			{
				$filterTime = '0';
			}
		}


		// Frontend Search settings (not in modules)
		if ($moduleParams)
		{
			$filterFrom  = '';
			$filterTo    = '';
			$filterMonth = '';
			$filterYear  = '';
		}
		else
		{
			// Filter by search
			$search = $jinput->getString('filter_search');

			if ( ! empty($search))
			{
				// Check if Falang installed
				$tag = Factory::getLanguage()->getTag();
				$defaultSiteLang = ComponentHelper::getParams('com_languages')->get('site', 'en-GB');

				//$falang = defined('FALANG_PATH');
				$falang = is_a($db, 'JFalangDatabase');

				$falangSearch = ($falang && ($tag != $defaultSiteLang));

				// Run Falang query if needed
				if ($falang && $falangSearch)
				{
					$query->join('LEFT', $db->quoteName('#__falang_content', 'f') . ' ON (' . $db->quoteName('f.reference_id') . ' = ' . $db->quoteName('e.id') . ')');
					$query->join('LEFT', $db->quoteName('#__languages', 'l') . ' ON (' . $db->quoteName('f.language_id') . ' = ' . $db->quoteName('l.lang_id') . ')');
					$query->where('f.reference_table IN (' . $db->quote('icagenda_events') . ', ' . $db->quote('icagenda_category') . ', ' . $db->quote('icagenda_customfields_data') . ')');
					$query->where('l.lang_code in (' . $db->quote($tag) . ',' . $db->quote('*') . ')');
				}

				if (stripos($search, 'id:') === 0)
				{
					$query->where('e.id = ' . (int) substr($search, 3));
				}
				elseif ($falang && $falangSearch)
				{
					$search = $db->quote('%' . str_replace(' ', '%', $db->escape(trim($search), true) . '%'));
					$query->where('( f.value LIKE ' . $search . ')');
				}
				else
				{
					$search = $db->quote('%' . str_replace(' ', '%', $db->escape(trim($search), true) . '%'));

					$query->where('( e.title LIKE ' . $search .
								' OR e.id LIKE ' . $search .
								' OR e.shortdesc LIKE ' . $search .
								' OR e.desc LIKE ' . $search .
								' OR e.place LIKE ' . $search .
								' OR e.address LIKE ' . $search .
								' OR e.city LIKE ' . $search .
								' OR e.country LIKE ' . $search .
								' OR c.title LIKE ' . $search .
								' OR cfd.value LIKE ' . $search .
								')');
				}
			}

			// Get filters by dates
			$filterFrom  = $jinput->get('filter_from');
			$filterTo    = $jinput->get('filter_to');
			$filterMonth = $jinput->getInt('filter_month');
			$filterYear  = $jinput->getInt('filter_year');
		}

		// FEATURES Filtering
//		$query->where(self::getFeaturesFilter());
		$query->where(self::getFeaturesFilter($moduleParams));

		// LANGUAGE Filtering
		$query->where('e.language IN (' . $db->q(Factory::getLanguage()->getTag()) . ',' . $db->q('*') . ')');

		// ACCESS Filtering
		$user       = Factory::getUser();
		$userID     = $user->id;
		$userLevels = $user->getAuthorisedViewLevels();
		$userGroups = $user->getAuthorisedGroups();

		$groupid = ComponentHelper::getParams('com_icagenda')->get('approvalGroups', array("8"));
		$groupid = is_array($groupid) ? $groupid : array($groupid);

		if (!in_array('8', $userGroups) )
		{
			$useraccess = implode(', ', $userLevels);
			$query->where('e.access IN (' . $useraccess . ')');
		}

		// APPROVAL RIGHTS Filtering
		if (!array_intersect($userGroups, $groupid)
			&& !in_array('8', $userGroups))
		{
			$query->where('e.approval <> 1');
		}
		else
		{
			$query->where('e.approval < 2');
		}

		// Prevents duplicated results when search in Falang category translations
		$query->group('e.id');

		$db->setQuery($query);
		$list = $db->loadObjectList();

		$list_all_dates = array();

		foreach ($list AS $i)
		{
			$hasUpcomingDate = false;

			$i_id          = $i->id;
			$i_startdate   = $i->startdate;
			$i_enddate     = $i->enddate;
			$i_weekdays    = $i->weekdays;
			$i_dates       = $i->dates;
			$i_displaytime = $i->displaytime;

			// Declare AllDates array
			$AllDatesDisplay = array();

			// Get WeekDays Array
			$WeeksDays = iCDatePeriod::weekdaysToArray($i_weekdays);

			// If Single Dates, added each one to All Dates for this event
			$singledates      = iCString::isSerialized($i_dates) ? unserialize($i_dates) : array();
			$singleDatesArray = array();

			$no_filtering     = '0';

			foreach ($singledates as $sd)
			{
				$isValid = iCDate::isDate($sd);

				if ($isValid)
				{
					$date_Dat        = HTMLHelper::date($sd, 'Y-m-d', $eventTimeZone);
					$SingleDate      = HTMLHelper::date($sd, 'Y-m-d H:i', $eventTimeZone);

					$data_SingleDate = date('Y-m-d H:i', strtotime($sd));

					if (strtotime($sd) >= strtotime($datetime_today))
					{
						$hasUpcomingDate = true;
					}

					// Frontend Filtering
					if (
						! empty($filterFrom)
						|| ! empty($filterTo)
						|| ! empty($filterMonth)
						|| ! empty($filterYear)
						|| ! empty($search)
						)
					{
						$dateToDisplay = true;

						$controlDate = $SingleDate;


						// Filter by period - From
						if ( ! empty($filterFrom) && $dateToDisplay)
						{
							$dateToDisplay = (strtotime($controlDate) >= strtotime($filterFrom)) ? true : false;
						}

						// Filter by period - To
						if ( ! empty($filterTo) && $dateToDisplay)
						{
							$dateToDisplay = (strtotime($controlDate) <= (strtotime($filterTo) + 86400)) ? true : false;
						}

						// Filter by Month
						if ( ! empty($filterMonth) && $dateToDisplay)
						{
							$dateToDisplay = (date('n', strtotime($controlDate)) == $filterMonth) ? true : false;
						}

						// Filter by Year
						if ( ! empty($filterYear) && $dateToDisplay)
						{
							$dateToDisplay = (date('Y', strtotime($controlDate)) == $filterYear) ? true : false;
						}

						if ($currentListSearch && $dateToDisplay)
						{
							$controlDate = $SingleDate;

							// (1) Filter Dates : Ongoing and Upcoming
							if ($filterTime == 1)
							{
								$dateToDisplay = (strtotime($controlDate) >= strtotime($date_today)) ? true : false;
							}
							// (2) Filter Dates : Past Dates
							elseif ($filterTime == 2)
							{
								$dateToDisplay = (strtotime($controlDate) < strtotime($datetime_today)) ? true : false;
							}
							// (3) Filter Dates : Upcoming
							elseif ($filterTime == 3)
							{
								$dateToDisplay = (strtotime($controlDate) > strtotime($datetime_today)) ? true : false;
							}
							// (4) Filter Dates : Ongoing Events today
							elseif ($filterTime == 4)
							{
								$dateToDisplay = ((strtotime($controlDate) >= strtotime($date_today))
													&& (strtotime($controlDate) <= (strtotime($date_today) + 86400))) ? true : false;
							}
						}

						if ($dateToDisplay)
						{
							$singleDatesArray[] = $data_SingleDate . '_' . $i_id;
						}
					}

					// All Dates for each event
					elseif ($datesDisplay == 1)
					{
						$singleDatesArray[] = $data_SingleDate . '_' . $i_id;
					}

					// Current Today
					elseif ($filterTime == 4
						&& strtotime($SingleDate) >= strtotime($date_today)
						&& strtotime($SingleDate) < (strtotime($date_today) + 86400)
						)
					{
						$singleDatesArray[] = $data_SingleDate . '_' . $i_id;
					}

					// Upcoming Events
					elseif ($filterTime == 3
						&& strtotime($SingleDate) > strtotime($datetime_today)
						)
					{
						$singleDatesArray[] = $data_SingleDate . '_' . $i_id;
					}

					// Past event
					elseif ($filterTime == 2
						&& strtotime($SingleDate) < strtotime($datetime_today)
						)
					{
						$singleDatesArray[] = $data_SingleDate . '_' . $i_id;
					}

					// Current and Upcoming Events
					elseif ($filterTime == 1
						&& strtotime($SingleDate) >= strtotime($date_today)
						)
					{
						$singleDatesArray[] = $data_SingleDate . '_' . $i_id;
					}

					// All Dates
					elseif (!$filterTime)
					{
						// All Upcoming dates
						if (strtotime($SingleDate) >= strtotime($datetime_today))
						{
							$no_filtering = $no_filtering + 1;

							$singleDatesArray[] = $data_SingleDate . '_' . $i_id;
						}

						// If no Upcoming dates, get the last date
						elseif ($no_filtering == 0
							&& strtotime($SingleDate) < strtotime($datetime_today))
						{
							$no_filtering = $no_filtering + 1;

							$singleDatesArray[] = $data_SingleDate . '_' . $i_id;
						}
					}
				}
			}

			if (empty($datesDisplay)
				&& $filterTime == 2
				&& count($singleDatesArray) > 0) // Past Events
			{
				$AllDatesDisplay[] = max($singleDatesArray);
			}
			elseif (empty($datesDisplay)
				&& count($singleDatesArray) > 0)
			{
				$AllDatesDisplay[] = min($singleDatesArray);
			}
			else
			{
				$AllDatesDisplay = array_merge($AllDatesDisplay, $singleDatesArray);
			}

			// If Period Dates, added each one to All Dates for this event (filter week Days, and if date not null)
			$perioddates = iCDatePeriod::listDates($i_startdate, $i_enddate);

			$period_array = array();

			foreach ($perioddates AS $date_in_weekdays)
			{
				if (in_array(date('w', strtotime($date_in_weekdays)), $WeeksDays)
					&& iCDate::isDate($date_in_weekdays))
				{
					$period_array[] = $date_in_weekdays;
				}
			}

			$only_startdate = ($i_weekdays || $i_weekdays == '0') ? false : true;

			$StDate = HTMLHelper::date($i_startdate, 'Y-m-d H:i', $eventTimeZone);
			$EnDate = HTMLHelper::date($i_enddate, 'Y-m-d H:i', $eventTimeZone);

			$date_startdate = HTMLHelper::date($i_startdate, 'Y-m-d', $eventTimeZone);
			$date_enddate   = HTMLHelper::date($i_enddate, 'Y-m-d', $eventTimeZone);
			$time_startdate = HTMLHelper::date($i_startdate, 'H:i', $eventTimeZone);
			$time_enddate   = HTMLHelper::date($i_enddate, 'H:i', $eventTimeZone);

			$data_StDate         = date('Y-m-d H:i', strtotime($i_startdate));
			$data_time_startdate = date('H:i', strtotime($i_startdate));

			if (isset($period_array)
				&& ($period_array != NULL && $period_array)
				)
			{
				if ($only_startdate)
				{
					// Frontend Filtering
					if (
						! empty($filterFrom)
						|| ! empty($filterTo)
						|| ! empty($filterMonth)
						|| ! empty($filterYear)
						)
					{
						$dateToDisplay = true;

						// Full Period with no weekdays selected, ongoing events
						$ongoing_filter = in_array($filterTime, array('1', '4'));

						if ($ongoing_filter
							&& in_array($date_today . ' ' . $time_startdate, $perioddates)
							&& strtotime($EnDate) >= strtotime($datetime_today)
							&& strtotime($StDate) <= strtotime($datetime_today)
							)
						{
							$controlDate = $date_today;
						}
						else
						{
							$controlDate = $date_startdate;
						}

						// Filter by period - From
						if ( ! empty($filterFrom) && $dateToDisplay)
						{
//							$dateToDisplay = (strtotime($controlDate) >= strtotime($filterFrom)) ? true : false;
							$dateToDisplay = ((strtotime($controlDate) >= strtotime($filterFrom))
												|| ($ongoing_filter
													&& (strtotime($controlDate) <= strtotime($filterFrom))
													&& (strtotime($date_enddate) >= strtotime($filterFrom))
													)
												) ? true : false;
						}

						// Filter by period - To
						if ( ! empty($filterTo) && $dateToDisplay)
						{
							$dateToDisplay = (strtotime($controlDate) < (strtotime($filterTo) + 86400)) ? true : false;
						}

						// Filter by Month and/or Year
						if ( ! empty($filterMonth) || ! empty($filterYear))
						{
							$new_startdate = new \DateTime($i_startdate);
							$new_enddate   = new \DateTime($i_enddate);
							$start         = $new_startdate->modify('first day of this month');
							$end           = $new_enddate->modify('last day of this month');
							$interval      = \DateInterval::createFromDateString('1 month');
							$period        = new \DatePeriod($start, $interval, $end);
						}

						// Filter by Month
						if ( ! empty($filterMonth) && empty($filterYear) && $dateToDisplay)
						{
//							$dateToDisplay = (date('n', strtotime($controlDate)) == $filterMonth) ? true : false;

							$months_period = array('');

							foreach ($period as $dt)
							{
								$months_period[]= $dt->format('n');
							}

							$dateToDisplay = in_array($filterMonth, $months_period);
						}

						// Filter by Year
						if ( ! empty($filterYear) && empty($filterMonth) && $dateToDisplay)
						{
//							$dateToDisplay = (date('Y', strtotime($controlDate)) == $filterYear) ? true : false;

							$years_period = array('');

							foreach ($period as $dt)
							{
								$years_period[]= $dt->format('Y');
							}

							$dateToDisplay = in_array($filterYear, $years_period);
						}

						// Filter by Month & Year
						if ( ! empty($filterMonth) && ! empty($filterYear) && $dateToDisplay)
						{
//							$dateToDisplay = (date('Y', strtotime($controlDate)) == $filterYear) ? true : false;

							$monthsYears_period = array('');

							foreach ($period as $dt)
							{
								$monthsYears_period[]= $dt->format('n-Y');
							}

							$dateToDisplay = in_array($filterMonth . '-' . $filterYear, $monthsYears_period);
						}

						if ($dateToDisplay)
						{
							$AllDatesDisplay[] = $data_StDate . '_' . $i_id;
						}
					}
					else
					{
						$AllDatesDisplay[] = $data_StDate . '_' . $i_id;
					}
				}
				else
				{
					$dp = 0;
					$count_period = count($period_array);
					$cp = 0;
					$no_filtering = 0;

					foreach ($period_array as $Dat)
					{
						$date_Dat   = HTMLHelper::date($Dat, 'Y-m-d', $eventTimeZone);
						$SingleDate = HTMLHelper::date($Dat, 'Y-m-d H:i', $eventTimeZone);

						$data_date_Dat   = date('Y-m-d', strtotime($Dat));
						$data_SingleDate = date('Y-m-d H:i', strtotime($Dat));

						if (strtotime($Dat) >= strtotime($datetime_today))
						{
							$hasUpcomingDate = true;
						}

						if (in_array(date('w', strtotime($Dat)), $WeeksDays)
							&& $dp == 0
							)
						{
							// Frontend Filtering
							if (
								! empty($filterFrom)
								|| ! empty($filterTo)
								|| ! empty($filterMonth)
								|| ! empty($filterYear)
								|| ! empty($search)
								)
							{
								$dateToDisplay = true;

								$controlDate = $date_Dat;


								// Filter by period - From
								if ( ! empty($filterFrom) && $dateToDisplay)
								{
									$dateToDisplay = (strtotime($controlDate) >= strtotime($filterFrom)) ? true : false;
								}

								// Filter by period - To
								if ( ! empty($filterTo) && $dateToDisplay)
								{
									$dateToDisplay = (strtotime($controlDate) <= strtotime($filterTo)) ? true : false;
								}

								// Filter by Month
								if ( ! empty($filterMonth) && $dateToDisplay)
								{
									$dateToDisplay = (date('n', strtotime($controlDate)) == $filterMonth) ? true : false;
								}

								// Filter by Year
								if ( ! empty($filterYear) && $dateToDisplay)
								{
									$dateToDisplay = (date('Y', strtotime($controlDate)) == $filterYear) ? true : false;
								}

								if ($currentListSearch && $dateToDisplay)
								{
									// Full Period with no weekdays selected, ongoing events
									if ($only_startdate
										&& in_array($filterTime, array('1', '4'))
										&& in_array($date_get_datetime, $perioddates)
										&& strtotime($EnDate) >= strtotime($datetime_today)
										&& strtotime($StDate) <= strtotime($datetime_today)
										)
									{
										$controlDate = $datetime_today;
									}
									else
									{
										$controlDate = $SingleDate;
									}

									// (1) Filter Dates : Ongoing and Upcoming
									if ($filterTime == 1)
									{
										$dateToDisplay = (strtotime($controlDate) >= strtotime($date_today)) ? true : false;
									}
									// (2) Filter Dates : Past Dates
									elseif ($filterTime == 2)
									{
										$dateToDisplay = (strtotime($controlDate) < strtotime($datetime_today)) ? true : false;
									}
									// (3) Filter Dates : Upcoming
									elseif ($filterTime == 3)
									{
										$dateToDisplay = (strtotime($controlDate) > strtotime($datetime_today)) ? true : false;
									}
									// (4) Filter Dates : Ongoing Events today
									elseif ($filterTime == 4)
									{
										$dateToDisplay = ((strtotime($controlDate) >= strtotime($date_today))
															&& (strtotime($controlDate) < (strtotime($date_today) + 86400))) ? true : false;
									}
								}

								if ($dateToDisplay)
								{
									$AllDatesDisplay[] = $data_SingleDate . '_' . $i_id;
								}
							}

							// Current Today and Upcoming Today
							elseif ($filterTime == 4
								&& strtotime($date_Dat) == strtotime($date_today))
							{
								if ($i_displaytime == 1
									&& strtotime($date_Dat . ' ' . $time_enddate) >= strtotime($datetime_today))
								{
									$dp = (empty($datesDisplay)) ? $dp+1 : 0;

									$AllDatesDisplay[] = $data_SingleDate . '_' . $i_id;
								}
								else
								{
									$dp = (empty($datesDisplay)) ? $dp+1 : 0;

									$AllDatesDisplay[] = $data_SingleDate . '_' . $i_id;
								}
							}

							// Upcoming
							elseif ($filterTime == 3
								&& strtotime($SingleDate) > strtotime($datetime_today))
							{
								$dp = (empty($datesDisplay)) ? $dp+1 : 0;

								$AllDatesDisplay[] = $data_SingleDate . '_' . $i_id;
							}

							// Past
							elseif ($filterTime == 2
								&& strtotime($date_Dat) < strtotime($date_today))
							{
								$dp = (empty($datesDisplay)) ? $dp+1 : 0;

								$AllDatesDisplay[] = $data_SingleDate . '_' . $i_id;
							}

							// Current Today and Upcoming
							elseif ($filterTime == 1)
							{
								if ($i_displaytime == 1
									&& strtotime($date_Dat . ' ' . $time_enddate) >= strtotime($datetime_today))
								{
									$dp = (empty($datesDisplay)) ? $dp+1 : 0;

									$AllDatesDisplay[] = $data_SingleDate . '_' . $i_id;
								}
								elseif ($i_displaytime != 1
									&& strtotime($date_Dat) >= strtotime($date_today))
								{
									$dp = (empty($datesDisplay)) ? $dp+1 : 0;

									$AllDatesDisplay[] = $data_SingleDate . '_' . $i_id;
								}
							}

							// No Filtering
							elseif ( ! $filterTime)
							{
								// All Upcoming dates
								if (strtotime($SingleDate) >= strtotime($datetime_today))
								{
									$dp = (empty($datesDisplay)) ? $dp+1 : 0;
									$no_filtering = (empty($datesDisplay)) ? $no_filtering+1 : 0;

									$AllDatesDisplay[] = $data_SingleDate . '_' . $i_id;
								}

								// If no Upcoming dates, get the last date
								elseif ($no_filtering == 0 && empty($datesDisplay)
									&& strtotime($SingleDate) < strtotime($datetime_today))
								{
									$dp = $dp+1;

									$AllDatesDisplay[] = $data_SingleDate . '_' . $i_id;
								}

								// If display All Dates, get the last dates
								elseif ( $datesDisplay == 1
									&& strtotime($SingleDate) < strtotime($datetime_today))
								{
									$dp = 0;

									$AllDatesDisplay[] = $data_SingleDate . '_' . $i_id;
								}
							}
						}
					}
				}
			}

			// If not All Dates display (select only one date for each event)
			if ( empty($datesDisplay)
				&& count($AllDatesDisplay) > 0 )
			{
				$ex_min       = explode('_', min($AllDatesDisplay));
				$min_datetime = HTMLHelper::date($ex_min[0], 'Y-m-d H:i', null);
				$min_date     = HTMLHelper::date($ex_min[0], 'Y-m-d', null);

				$ex_max       = explode('_', max($AllDatesDisplay));
				$max_date     = HTMLHelper::date($ex_max[0], 'Y-m-d H:i', null);

				if ($filterTime != '4')
				{
					// Past Events and max date is upcoming
					if ( $filterTime == '2'
						&& $hasUpcomingDate )
					{
						$AllDatesDisplay = array();
					}

					// min date is today or upcoming
					elseif ( $filterTime == '1'
						&& $min_datetime >= $date_today )
					{
						$AllDatesDisplay = array(min($AllDatesDisplay));
					}

					// min date is upcoming
					elseif ( $min_datetime >= $datetime_today )
					{
						$AllDatesDisplay = array(min($AllDatesDisplay));
					}

					// All events
					elseif ($filterTime == '0')
					{
						// min date in Period and upcoming
						if (in_array($min_datetime, $period_array)
							&& $min_datetime >= $datetime_today )
						{
							$AllDatesDisplay = array(min($AllDatesDisplay));
						}

						// min date is Single date and not past
						elseif ( ! in_array($min_datetime, $period_array)
							&& ($min_datetime > $datetime_today) )
						{
							$AllDatesDisplay = array(min($AllDatesDisplay));
						}

						// min date is Single date and past
						else
						{
							$AllDatesDisplay = array(max($AllDatesDisplay));
						}
					}
					else
					{
						$AllDatesDisplay = array(max($AllDatesDisplay));
					}
				}
				elseif ($filterTime == '4')
				{
					$AllDatesDisplay    = ($min_datetime >= $date_today)
										? array(min($AllDatesDisplay))
										: array(max($AllDatesDisplay));
				}
				else
				{
					$AllDatesDisplay = array(min($AllDatesDisplay));
				}
			}

			$AllDatesFilterTime = array();

			foreach ($AllDatesDisplay as $fD)
			{
				$ex_date            = explode('_', $fD);
				$get_date           = $ex_date['0'];
				$date_get_date      = HTMLHelper::date($get_date, 'Y-m-d', $eventTimeZone);
				$date_get_datetime  = HTMLHelper::date($get_date, 'Y-m-d H:i', $eventTimeZone);
				$data_date_get_date = date('Y-m-d', strtotime($get_date));

				// Frontend Filtering
				if (
					! empty($filterFrom)
					|| ! empty($filterTo)
					|| ! empty($filterMonth)
					|| ! empty($filterYear)
					|| ! empty($search)
					)
				{
					$dateToDisplay = true;
					$controlDate = $date_get_date;

					// Filter by period - From
					if ( ! empty($filterFrom) && $dateToDisplay)
					{
						$dateToDisplay = (strtotime($controlDate) >= strtotime($filterFrom)) ? true : false;
					}

					// Filter by period - To
					if ( ! empty($filterTo) && $dateToDisplay)
					{
						$dateToDisplay = (strtotime($controlDate) <= strtotime($filterTo)) ? true : false;
					}

					// Filter by Month
					if ( ! empty($filterMonth) && $dateToDisplay)
					{
						$dateToDisplay = (date('n', strtotime($controlDate)) == $filterMonth) ? true : false;
					}

					// Filter by Year
					if ( ! empty($filterYear) && $dateToDisplay)
					{
						$dateToDisplay = (date('Y', strtotime($controlDate)) == $filterYear) ? true : false;
					}

					if ($currentListSearch)
					{
						// Full Period with no weekdays selected, ongoing events
						if ($only_startdate
							&& in_array($filterTime, array('1', '4'))
							&& in_array($date_get_datetime, $perioddates)
							&& strtotime($EnDate) >= strtotime($datetime_today)
							&& strtotime($StDate) <= strtotime($datetime_today)
							)
						{
							$controlDate = $datetime_today;
						}
						else
						{
							$controlDate = $date_get_datetime;
						}

						// (1) Filter Dates : Ongoing and Upcoming
						if ($filterTime == 1)
						{
							$dateToDisplay = (strtotime($controlDate) >= strtotime($date_today)) ? true : false;
						}
						// (2) Filter Dates : Past Dates
						elseif ($filterTime == 2)
						{
							$dateToDisplay = (strtotime($controlDate) < strtotime($datetime_today)) ? true : false;
						}
						// (3) Filter Dates : Upcoming
						elseif ($filterTime == 3)
						{
							$dateToDisplay = (strtotime($controlDate) > strtotime($datetime_today)) ? true : false;
						}
						// (4) Filter Dates : Ongoing Events today
						elseif ($filterTime == 4)
						{
							$dateToDisplay = ((strtotime($controlDate) >= strtotime($date_today))
												&& (strtotime($controlDate) < (strtotime($date_today) + 86400))) ? true : false;
						}
					}

					if ($dateToDisplay)
					{
						$AllDatesFilterTime[] = $fD;
					}
				}

				// (0) Filter Dates : All Dates
				elseif ($filterTime == 0)
				{
					// Period with no weekdays selected
					if ( in_array($get_date, $perioddates)
						&& $only_startdate
						&& ! in_array($StDate . '_' . $i_id, $AllDatesFilterTime)
						)
					{
						$AllDatesFilterTime[] = $data_StDate . '_' . $i_id;
					}

					// Period with weekdays selected
					elseif ( in_array($get_date, $perioddates)
						&& ! $only_startdate
						)
					{
						$AllDatesFilterTime[] = $fD;
					}

					// Single Dates
					elseif ( ! in_array($get_date, $perioddates) )
					{
						$AllDatesFilterTime[] = $fD;
					}
				}

				// (1) Filter Dates : Ongoing and Upcoming
				elseif ($filterTime == 1)
				{
					// Period with no weekdays selected
					if (in_array($get_date, $perioddates)
						&& $only_startdate
						&& strtotime($EnDate) >= strtotime($datetime_today)
						&& !in_array($StDate . '_' . $i_id, $AllDatesFilterTime)
						)
					{
						$AllDatesFilterTime[] = $data_StDate . '_' . $i_id;
					}

					// Period with weekdays selected
					elseif (in_array($get_date, $perioddates)
						&& !$only_startdate
						)
					{
						// If display time, control end time of the day
						if ($i_displaytime == 1
							&& strtotime($date_get_date . ' ' . $time_enddate) >= strtotime($datetime_today))
						{
							$AllDatesFilterTime[] = $fD;
						}

						// If do not display time, control start time of the day
						elseif ($i_displaytime != 1
							&& strtotime($date_get_date) >= strtotime($date_today))
						{
							$AllDatesFilterTime[] = $fD;
						}
					}

					// Single Dates
					elseif (!in_array($get_date, $perioddates)
//						&& strtotime($get_date) >= strtotime($datetime_today)
						// Changed because single dates have no end time, so admitted end time is midnight.
						&& strtotime($get_date) >= strtotime($date_today)
						)
					{
						$AllDatesFilterTime[] = $fD;
					}
				}

				// (2) Filter Dates : Past Dates
				elseif ($filterTime == 2)
				{
					// Period with no weekdays selected
					if ( in_array($get_date, $perioddates)
						&& $only_startdate
						&& (strtotime($EnDate) < strtotime($datetime_today))
						 )
					{
						$AllDatesFilterTime[] = $fD;
					}

					// Period with weekdays selected
					elseif ( in_array($get_date, $perioddates)
						&& !$only_startdate
						&&  strtotime($get_date) < strtotime($datetime_today)
						&&  strtotime($date_get_date . ' ' . $time_enddate) < strtotime($datetime_today)
						 )
					{
						$AllDatesFilterTime[] = $fD;
					}

					// Single Dates
					elseif ( !in_array($get_date, $perioddates)
						&& strtotime($get_date) < strtotime($datetime_today)
						 )
					{
						$AllDatesFilterTime[] = $fD;
					}
				}

				// (3) Filter Dates : Upcoming
				elseif ($filterTime == 3)
				{
					// Period with no weekdays selected
					if (in_array($get_date, $perioddates)
						&& $only_startdate
						&& (strtotime($StDate) > strtotime($datetime_today))
						)
					{
						$AllDatesFilterTime[] = $fD;
					}

					// Period with weekdays selected
					elseif (in_array($get_date, $perioddates)
						&& ! $only_startdate
						&&  strtotime($get_date) > strtotime($datetime_today)
						&&  strtotime($date_get_date . ' ' . $time_startdate) > strtotime($datetime_today)
						)
					{
						$AllDatesFilterTime[] = $fD;
					}

					// Single Dates
					elseif ( ! in_array($get_date, $perioddates)
						&& strtotime($get_date) > strtotime($datetime_today)
						)
					{
						$AllDatesFilterTime[] = $fD;
					}
				}

				// (4) Filter Dates : Ongoing Events today
				elseif ($filterTime == 4)
				{
					// Period with no weekdays selected
					if (in_array($get_date, $perioddates)
						&& $only_startdate
						&& strtotime($EnDate) >= strtotime($datetime_today)
						&& strtotime($StDate) <= (strtotime($date_today) + 86400)
						)
					{
						$AllDatesFilterTime[] = $data_date_get_date . ' ' . $data_time_startdate . '_' . $i_id;
					}

					// Period with weekdays selected
					elseif ( in_array($get_date, $perioddates)
						&& ! $only_startdate
						&& ( strtotime($date_get_date) == strtotime($date_today)
						&& strtotime($date_get_date . ' ' . $time_enddate) < (strtotime($date_today) + 86400) )
						 )
					{
						$AllDatesFilterTime[] = $fD;
					}

					// Single Dates
					elseif ( !in_array($get_date, $perioddates)
//						&& ( strtotime($get_date) >= strtotime($datetime_today)
						// Changed because single dates have no end time, so admitted end time is midnight.
						&& ( strtotime($get_date) >= strtotime($date_today)
						&& strtotime($get_date) < (strtotime($date_today) + 86400) )
						 )
					{
						$AllDatesFilterTime[] = $fD;
					}
				}
			}

			$list_all_dates = array_merge($list_all_dates, $AllDatesFilterTime);
		}

		if ($orderby == 2)
		{
			sort($list_all_dates);
		}
		else
		{
			rsort($list_all_dates);
		}

		return $list_all_dates;
	}

	/**
	 * Get and update NEXT DATE
	 *
	 * @since   3.5.4
	 */

	public static function getNext()
	{
		$app = Factory::getApplication();
		$params = $app->getParams();

		// Get Settings
		$filterTime = $params->get('time', 1);

		// Set vars
		$nodate         = '0000-00-00 00:00:00';
		$eventTimeZone  = null;
		$datetime_today = HTMLHelper::date('now', 'Y-m-d H:i:s'); // Joomla Time Zone
		$date_today     = HTMLHelper::date('now', 'Y-m-d'); // Joomla Time Zone
		$time_today     = HTMLHelper::date('now', 'H:i:s'); // Joomla Time Zone

		// Preparing connection to db
		$db	= Factory::getDbo();

		// Preparing the query
		$query = $db->getQuery(true);

		$query->select('next AS tNext, dates AS tDates, startdate AS tStartdate, enddate AS tEnddate,
						weekdays AS tWeekdays, id AS tId, state AS tState, access AS tAccess');
		$query->from('#__icagenda_events AS e');
//		$query->where(' e.state = 1 OR e.state = 0 ');
		$query->where('e.state = 1');
		$query->where('e.next < ' . $db->quote($datetime_today) . ' AND e.next > (DATE(NOW() - INTERVAL 2 MONTH))');
//		$query->where('e.next < ' . $db->quote($datetime_today));
		$db->setQuery($query);

		$all_next_dates = $db->loadObjectList();

		foreach ($all_next_dates as $nd)
		{
			$nd_next      = $nd->tNext;
			$nd_id        = $nd->tId;
			$nd_state     = $nd->tState;
			$nd_dates     = $nd->tDates;
			$nd_startdate = $nd->tStartdate;
			$nd_enddate   = $nd->tEnddate;
			$nd_weekdays  = $nd->tWeekdays;

			// If Single Dates, added to all dates for this event
			$singleDates	= iCString::isSerialized($nd_dates) ? unserialize($nd_dates) : array();

			$AllDates = array();

			// Get WeekDays Array
			$WeeksDays = iCDatePeriod::weekdaysToArray($nd_weekdays);

			if (isset ($singleDates)
				&& $singleDates != NULL
				&& !in_array($nodate, $singleDates)
				&& !in_array('', $singleDates)
				)
			{
				$AllDates = array_merge($AllDates, $singleDates);
			}
			elseif (in_array('', $singleDates))
			{
				$datesarray  = array();
				$nodate      = array('0000-00-00 00:00');
				$datesmerger = array_push($datesarray, $nodate);
				$DatesUpdate = serialize($nodate);

				$query	= $db->getQuery(true);
				$query->update('#__icagenda_events');
				$query->set("dates='" . (string)$DatesUpdate . "'");
				$query->where('id=' . (int)$nd_id);
				$db->setQuery($query);
				$db->execute($query);

				$nosingledates = unserialize($DatesUpdate);
				$AllDates      = array_merge($AllDates, $nosingledates);
			}

			$StDate = date('Y-m-d H:i', strtotime($nd_startdate));
			$EnDate = date('Y-m-d H:i', strtotime($nd_enddate));

			$date_enddate   = date('Y-m-d', strtotime($nd_enddate));
			$time_enddate   = date('H:i', strtotime($nd_enddate));
			$date_startdate = date('Y-m-d', strtotime($nd_startdate));
			$time_startdate = date('H:i', strtotime($nd_startdate));

			$perioddates    = iCDatePeriod::listDates($nd_startdate, $nd_enddate);

			$only_startdate = ($nd_weekdays || $nd_weekdays == '0') ? false : true;

			if (isset($perioddates)
				&& $perioddates != NULL
				)
			{
				// Period with no weekdays in Upcoming and Past options
				if ($only_startdate
					&& ($filterTime == '3' || $filterTime == '2')
					)
				{
					array_push($AllDates, $StDate);
				}
				else
				{
					foreach ($perioddates as $Dat)
					{
						if (in_array(date('w', strtotime($Dat)), $WeeksDays))
						{
							$date_Dat   = date('Y-m-d', strtotime($Dat));
							$SingleDate = date('Y-m-d H:i', strtotime($Dat));

							if ( $date_Dat == $date_today && $filterTime != 3 )
							{
								// Next in Period is today, so set end time
								array_push($AllDates, $date_Dat . ' ' .$time_startdate);
							}
							else
							{
								array_push($AllDates, $SingleDate);
							}
						}
					}
				}
			}

			rsort($AllDates);

			if ($AllDates == NULL)
			{
				$next ='0000-00-00 00:00:00';
			}
			else
			{
				$date_lastdate     = date('Y-m-d', strtotime($AllDates[0]));
				$datetime_lastdate = date('Y-m-d H:i:s', strtotime($AllDates[0]));

				$date_startdate    = date('Y-m-d', strtotime($nd_startdate));
				$date_enddate      = date('Y-m-d', strtotime($nd_enddate));

				$time_startdate    = date('H:i:s', strtotime($nd_startdate));
				$time_enddate      = date('H:i:s', strtotime($nd_enddate));

				$returnNext        = $nd_next;

				$next_is_set       = '0';

				$today_upcoming_SD = '0';
				$upcoming_SD       = '0';

				foreach ($AllDates as $a)
				{
					$tsdate_a = date('Y-m-d', strtotime($a));

					if ($tsdate_a == $date_today)
					{
						// All single dates today and not yet started
						$today_upcoming_SD	= (strtotime($a) > strtotime($datetime_today)) ? ($today_upcoming_SD + 1) : $today_upcoming_SD;
					}

					if ($tsdate_a >= $datetime_today)
					{
						// All upcoming single dates
						$upcoming_SD = $upcoming_SD + 1;
					}
				}

				$total_today_SD = $today_upcoming_SD;

				foreach ($AllDates as $a)
				{
					$tsdatetime_a = date('Y-m-d H:i:s', strtotime($a));
					$tsdate_a     = date('Y-m-d', strtotime($a));

					// Only past single dates
					if ($datetime_lastdate < $datetime_today
						&& $date_lastdate != $date_today
						&& $next_is_set == '0')
					{
						$returnNext = date('Y-m-d H:i:s', strtotime($AllDates[0]));
						$next_is_set = $next_is_set + 1;
					}

					// The last date is today
					elseif ($date_lastdate == $date_today
						&& $next_is_set == '0'
						&& $total_today_SD == 1)
					{
						// Period divided into days
						if ($nd_startdate != $nodate
							&& $nd_enddate != $nodate
							&& in_array($a, $perioddates)
							&& !$only_startdate
							)
						{
							$returnNext = date('Y-m-d', strtotime($nd_enddate)) . ' ' . $time_startdate;
							$next_is_set = $next_is_set + 1;
						}

						// Full period (from ... to ...)
						elseif ($nd_startdate != $nodate
							&& $nd_enddate != $nodate
							&& in_array($a, $perioddates)
							&& $only_startdate
							)
						{
							$returnNext = date('Y-m-d', strtotime($nd_startdate)) . ' ' . $time_startdate;
							$next_is_set = $next_is_set + 1;
						}

						// Single date
						else
						{
							if ($datetime_lastdate > $datetime_today)
							{
								$today_upcoming_SD = $today_upcoming_SD - 1;
							}

							if ($datetime_lastdate > $datetime_today
								&& $today_upcoming_SD == '0')
							{
								$returnNext = date('Y-m-d H:i:s', strtotime($AllDates[0]));
								$next_is_set = $next_is_set + 1;
							}
						}
					}

					// Multiple upcoming single dates
//					elseif ($tsdatetime_a > $datetime_today)
					// Changed because single dates have no end time, so admitted end time is midnight.
					elseif ($tsdatetime_a > $date_today
						&& $next_is_set == '0')
					{
						// Remaining Today's upcoming dates
						if ($tsdate_a == $date_today)
						{
							if ($tsdatetime_a > $datetime_today)
							{
								$today_upcoming_SD = $today_upcoming_SD - 1;
							}
						}

						// Remaining Upcoming dates
						if ($tsdate_a >= $datetime_today)
						{
							$upcoming_SD = $upcoming_SD - 1;
						}

						if ($today_upcoming_SD == '0'
							&& $upcoming_SD == '0')
						{
							$returnNext = date('Y-m-d H:i:s', strtotime($a));
							$next_is_set = $next_is_set + 1;
						}
					}
				}

				// Test End Date if Next Date or Last Date (3.1.5)
				$date_returnNext = date('Y-m-d', strtotime($returnNext));
				$time_returnNext = date('H:i:s', strtotime($returnNext));

				if ( ($date_enddate != '0000-00-00')
					&& ( $date_today == $date_enddate || $date_today == $date_returnNext) )
				{
					$time_LastTime = $time_startdate;
				}
				else
				{
					$time_LastTime = $time_returnNext;
				}

				// Fix 3.1.12 (removed isset($tPeriod))
				if ( ($nd_enddate != $nodate)
					&& ($date_startdate < $date_today)
					&& ($date_enddate == $date_today)
					&& ($time_LastTime >= $time_today) )
				{
					$returnNextPediod = date('Y-m-d', strtotime($nd_enddate)) . ' ' . $time_startdate;
				}
				else
				{
					$returnNextPediod = $returnNext;
				}

				// Set next var
				if ( ($date_returnNext == $date_enddate)
					&& ($date_enddate == $date_today) )
				{
					$next = $returnNextPediod;
				}
				elseif (strtotime($date_startdate) < strtotime($date_today)
					&& strtotime($date_enddate) >= strtotime($date_today)
					&& strtotime($time_enddate) != strtotime($time_returnNext)
					&& strtotime($time_LastTime) > strtotime($time_today)
					)
				{
					$next = $date_returnNext . ' ' . date('H:i:s', strtotime($time_LastTime));
				}
				else
				{
					$next = $returnNext;
				}
			}
			// 3.1.12 Fixed and update events with bug
			if ($nd_next == $nodate
				&& $nd_state == 0
				&& $nd_startdate != $nodate
				&& $nd_enddate != $nodate
				&& strtotime($nd_enddate) >= strtotime($nd_startdate)
				)
			{
				$next = $returnNext;

				$query = $db->getQuery(true);
				$query->update('#__icagenda_events');
				$query->set('state=1');
				$query->where('id='.(int)$nd_id);
				$db->setQuery($query);
				$db->execute($query);
			}

			if ($next != $nd_next)
			{
				$query = $db->getQuery(true);
				$query->update('#__icagenda_events');
				$query->set("next='".$next."'");
				$query->where('id='.(int)$nd_id);
				$db->setQuery($query);
				$db->execute($query);
			}
		}
	}

	/**
	 * Returns the element of a SQL query WHERE clause to support filtering the selection of Events using Event Features
	 *
	 * Controlled by menu parameters:
	 *  features_filter - array of Feature IDs
	 *  features_incl_excl - indicates whether the Feature IDs are to be used to include or exclude Events
	 *  features_any_all - indicates whether any Feature ID or all Feature IDs required to include or exclude an Event
	 *
	 * One or more sub-queries is referenced in a WHERE clause with IN() or NOT IN() to include or exclude Events.
	 *
	 * If any Feature ID in isolation is to include or exclude Event records then a single sub-query is used that
	 * uses a simple inner join between the feature and feature_xref tables to identify the distinct set of Event IDs
	 * linked to any one of the spacific Feature IDs.
	 *
	 * If all Feature IDs combined are required to include or exclude Events then separate sub-queries are used for
	 * each of the spacific Feature IDs. For this case, a more efficient option is available involving a direct join
	 * with either an inner or outer join, according to whether records are being included or excluded but this
	 * puts an unreasonable constraint on the overall syntax of the query.
	 */
	public static function getFeaturesFilter($params = null)
	{
		// Get the params
		if ( ! $params)
		{
			$app = Factory::getApplication();
			$params = $app->getParams();
		}

		// Initialise a return value that can be included harmlessly in a WHERE clause, if necessary
		$filter = ' TRUE ';
		$featureids = $params->get('features_filter', '');

		if (is_array($featureids) && !empty($featureids))
		{
			$db = Factory::getDbo();
			$incl_excl = $params->get('features_incl_excl', '1') == '1' ? '' : 'NOT';

			if ($params->get('features_any_all', '1') == '1')
			{
				// Any single Feature ID will include or exclude events
				// Create comma separated list of Feature IDs
				$featureids = implode(',', $featureids);
				// Create a single sub-query
				$sub_query = $db->getQuery(true);
				$sub_query->select('fx.event_id')
					->from('#__icagenda_feature_xref AS fx')
					->innerJoin("#__icagenda_feature AS f ON fx.feature_id=f.id AND f.state=1 AND f.show_filter=1 AND f.id IN($featureids)");
				// Join the sub-query to the main query
				$filter = "(e.id $incl_excl IN(" . (string) $sub_query . '))';
			}
			else
			{
				// All Feature IDs combined will include or exclude events
				// Create a separate sub-query for each of the Feature IDs
				$sub_queries = array();

				foreach ($featureids as $featureid)
				{
					$sub_query = $db->getQuery(true);
					$sub_query->select('fx.event_id')
						->from('#__icagenda_feature_xref AS fx')
						->innerJoin("#__icagenda_feature AS f ON fx.feature_id=f.id AND f.state=1 AND f.show_filter=1 AND f.id=$featureid");
					$sub_queries[] = "e.id $incl_excl IN(" . (string) $sub_query . ')';
				}

				// Combine the sub-queries depending on inclusion or exclusion of events
				$filter = "(" . implode($incl_excl == 'NOT' ? " \nOR " : " \nAND ", $sub_queries) . ')';
			}
		}

		return $filter;
	}

	/**
	 * Return Array of all registrations from an event (date@@people)
	 * date : registered date
	 * people : nb of tickets for this registration
	 *
	 * @todo  migrate to Registration utilities
	 */
	public static function registeredList($id = null)
	{
		// Registrations total
		$db    = Factory::getDbo();
		$query = $db->getQuery(true);
		$query->select('r.date AS date, r.period AS period, r.eventid AS eventid, r.people AS people');
		$query->from('#__icagenda_registration AS r');
		$query->where('r.status = 1');
		$query->where('r.state = 1');

		if ($id)
		{
			$query->where('r.eventid = ' . $db->q($id));
		}

		$db->setQuery($query);
		$result = $db->loadObjectList();

		$registeredList = array();

		foreach ($result AS $r)
		{
			$reg_date = $r->date ? $r->date : 'period';
			$registeredList[] = $r->eventid . '@@' . $reg_date . '@@' . $r->period . '@@' . $r->people;
		}

		return $registeredList;
	}

	/**
	 * Return list of all dates (singles and period) from an event
	 *
	 * @since   3.5.0 (Not Yet Used)
	 */
	public static function thisEventDates($id)
	{
		// Set vars
		$nodate        = '0000-00-00 00:00:00';
		$ic_nodate     = '0000-00-00 00:00';
		$eventTimeZone = null;

		// Get Data
		$db    = Factory::getDbo();
		$query = $db->getQuery(true);
        $query->select('e.next, e.dates, e.startdate, e.enddate, e.period, e.weekdays, e.displaytime, e.id');
        $query->from('#__icagenda_events AS e');
		$query->leftJoin('#__icagenda_category AS c ON c.id = e.catid');
		$query->where('c.state = 1');
		$query->where('e.id = ' . $db->q($id));
		$db->setQuery($query);
		$result = $db->loadObjectList();

		// Get Data
		$tId        = $id;
		$tDates     = $result->dates;
		$tStartdate = $result->startdate;
		$tEnddate   = $result->enddate;
		$tWeekdays  = $result->weekdays;

		// Declare AllDates array
		$thisEventDates = array();

		// Get WeekDays Array
		$WeeksDays = iCDatePeriod::weekdaysToArray($tWeekdays);

		// If Single Dates, added each one to All Dates for this event
		$singledates = unserialize($tDates);

		foreach ($singledates as $sd)
		{
			$isValid = iCDate::isDate($sd);

			if ($isValid)
			{
				array_push($thisEventDates, $sd);
			}
		}

		$perioddates = iCDatePeriod::listDates($tStartdate, $tEnddate, $eventTimeZone);

		if (isset ($perioddates)
			&& $perioddates != NULL)
		{
			foreach ($perioddates as $Dat)
			{
				if (in_array(date('w', strtotime($Dat)), $WeeksDays))
				{
					$isValid = iCDate::isDate($Dat);

					if ($isValid)
					{
						$SingleDate = date('Y-m-d H:i:s', strtotime($Dat));

						array_push($thisEventDates, $SingleDate);
					}
				}
			}
		}

		return $thisEventDates;
	}
}
