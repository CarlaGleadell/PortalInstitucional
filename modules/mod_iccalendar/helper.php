<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.9.10 2025-03-01
 *
 * @package     iCagenda.Site
 * @subpackage  mod_iccalendar
 * @link        https://www.joomlic.com
 *
 * @author      Cyril Rezé
 * @copyright   (c) 2012-2025 Cyril Rezé / JoomliC. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 *
 * @since       3.1.9 (1.0)
 *----------------------------------------------------------------------------
*/

defined('_JEXEC') or die;

use iClib\Color\Color as iCColor;
use iClib\Date\Date as iCDate;
use iClib\Date\Period as iCDatePeriod;
use iClib\Filter\Output as iCFilterOutput;
use iClib\Globalize\Convert as iCGlobalizeConvert;
use iClib\Globalize\Globalize as iCGlobalize;
use iClib\String\StringHelper as iCString;
use iCutilities\Event\Event as icagendaEvent;
use iCutilities\Events\Events as icagendaEvents;
use iCutilities\Events\EventsData as icagendaEventsData;
use iCutilities\Media\Media as icagendaMedia;
use iCutilities\Menus\Menus as icagendaMenus;
use iCutilities\Registration\Registration as icagendaRegistration;
use iCutilities\Thumb\Thumb as icagendaThumb;
use Joomla\CMS\Component\ComponentHelper as JComponentHelper;
use Joomla\CMS\Factory as JFactory;
use Joomla\CMS\HTML\HTMLHelper as JHtml;
use Joomla\CMS\Language\Text as JText;
use Joomla\CMS\Uri\Uri as JUri;
use Joomla\Registry\Registry as JRegistry;

/**
 *	iCagenda - iC calendar
 */
class modiCcalendarHelper extends stdClass
{
	private function construct($params)
	{
		$app    = JFactory::getApplication();
		$jinput = $app->input;

		$this->modid				= $params->get('id');
		$this->template				= $params->get('template');
		$this->format				= $params->get('format');
		$this->date_separator		= $params->get('date_separator');
		$this->setTodayTimezone		= $params->get('setTodayTimezone');
		$this->displayDatesTimezone = $params->get('displayDatesTimezone');
		$this->filtering_shortDesc	= $params->get('filtering_shortDesc', '');
		$this->limit				= $params->get('paramlimit', '')
									? $params->get('paramlimit_Content')
									: false;
		$this->mcatid				= $params->get('mcatid', '');
		$this->number				= $params->get('number');
		$this->onlyStDate			= $params->get('onlyStDate');
		$this->firstMonth           = iCDate::isDate($params->get('firstMonth'))
									? $params->get('firstMonth')
									: '';
		$this->month_nav			= $params->get('month_nav', '1');
		$this->year_nav				= $params->get('year_nav', '1');

		$this->itemid				= $jinput->getInt('Itemid');
		$this->mod_iccalendar		= '#mod_iccalendar_' . $this->modid;

		// Get iCagenda images path
		$imagesPath                 = icagendaMedia::iCagendaImagesPath();

		// Features Options
		$this->features_icon_size	= $params->get('features_icon_size');
		$this->show_icon_title		= $params->get('show_icon_title');
		$this->features_icon_root	= JUri::base() . "{$imagesPath}/feature_icons/{$this->features_icon_size}/";

		// First day of the current month
		$this_month	= $this->firstMonth
//					? date("Y-m-d", strtotime("+1 month", strtotime($this->firstMonth)))
					? date("Y-m-d", strtotime($this->firstMonth))
					: JHtml::date('now', 'Y-m-01', null);

		$iccaldate	= $jinput->get('iccaldate', ''); // Get date set in month/year navigation

		// This should be the first day of a month
		$date_start = $iccaldate ? date('Y-m-01', strtotime($iccaldate)) : $this_month;

		// Add filter to restrict the number of events using the 'next' date
		if ($date_start > $this_month)
		{
			// Month to be displayed is in the future
			// Events required start from the current month
			$filter_start = $this_month;
		}
		else
		{
			// Month to be displayed is current or past
			// Events required start from the display month
			$filter_start = $date_start;
		}

		$this->date_start = $date_start;

		// Set Next date filtering
		$this->filter_start = $filter_start;

//		$this->addFilter('e.next', $filter_start, '>=');

		// An end date for selection is not possible because it may prevent display of past events where the next
		// scheduled instance of an event is after the end of the display month
//		$filter_end = date('Y-m-d', strtotime('+1 month', strtotime($this->date_start)));
//		$this->addFilter('e.next', "'$filter_end'",'<');
	}


	function start($params)
	{
		$this->construct($params);
	}


	// Class Method
	function getStamp($params)
	{
		$db = JFactory::getDbo();
		$query	= $db->getQuery(true);
		$query->select('id AS nbevt')->from('`#__icagenda_events` AS e')->where('e.state = 1');
		$db->setQuery($query);
		$nbevt = $db->loadResult();

		$no_event_message = '<div class="ic-msg-no-event">' . JText::_('MOD_ICCALENDAR_NO_EVENT') . '</div>';

		if ( ! $nbevt)
		{
			echo $no_event_message;
		}

		$iCparams		= JComponentHelper::getParams('com_icagenda');

		// Global Joomla API objects
		$app    = JFactory::getApplication();
		$lang   = JFactory::getLanguage();

		$menu           = $app->getMenu();

		// Module Params
		$iCmenuitem     = $params->get('iCmenuitem', '');
		$iCmenu_filters = $params->get('iCmenu_filters', 0);

		$firstMonth     = iCDate::isDate($params->get('firstMonth'))
						? trim($params->get('firstMonth', ''))
						: '';

		$dp_city            = $params->get('dp_city', 1);
		$dp_country         = $params->get('dp_country', 1);
		$param_dp_regInfos  = $params->get('dp_regInfos', 1);
		$dp_shortDesc       = $params->get('dp_shortDesc', '');
		$dp_time            = $params->get('dp_time', 1);
		$dp_venuename       = $params->get('dp_venuename', 1);

		$eventTimeZone	= null;

		// Itemid Request (automatic detection of the first iCagenda menu-link, by menuID)
		$iC_list_menus	= icagendaMenus::iClistMenuItemsInfo();



		// Check if GD is enabled on the server
		if (extension_loaded('gd') && function_exists('gd_info'))
		{
			$thumb_generator = $iCparams->get('thumb_generator', 1);
		}
		else
		{
			$thumb_generator = 0;
		}

		$datetime_today	= JHtml::date('now', 'Y-m-d H:i');
		$timeformat		= $iCparams->get('timeformat', 1);
		$lang_time		= ($timeformat == 1) ? 'H:i' : 'h:i A';

		// Check if fopen is allowed
		$result	= ini_get('allow_url_fopen');
		$fopen	= empty($result) ? false : true;


		$this->start($params);

		// Set start/end dates of the current month
		$days				= self::getNbOfDaysInMonth($this->date_start);
		$current_date_start	= $this->date_start;
		$month_start		= date('m', strtotime($current_date_start));
		$month_end			= date('m', strtotime('+1 month', strtotime($current_date_start)));
		$day_end			= date('m', strtotime('+'.$days.' days', strtotime($current_date_start)));

		$year_end			= ($month_start == '12')
							? date('Y', strtotime("+1 year", strtotime($this->date_start)))
							: date('Y', strtotime($this->date_start));

		$current_date_end	= $year_end . '-' . $month_end . '-' . $day_end;

		// Get the database
		$query	= $db->getQuery(true);

		// Build the query
		$query->select('e.*,
				e.place as place_name,
				c.title as cat_title,
				c.alias as cat_alias,
				c.color as cat_color,
				c.ordering as cat_order
			')
			->from($db->qn('#__icagenda_events').' AS e')
			->leftJoin($db->qn('#__icagenda_category').' AS c ON ' . $db->qn('c.id') . ' = ' . $db->qn('e.catid'));

		// Where Category is Published
		$query->where('c.state = 1');

		// Where State is Published
		$query->where('e.state = 1');

		// Where event is Approved
		$query->where('e.approval = 0');

		// Filter next date
		if ( ! $firstMonth)
		{
//			$query->where('((' . $db->qn('e.next') . ' >= ' . $db->q($this->filter_start) . ') OR (' . $db->qn('e.startdate') . ' <= ' . $db->q($this->filter_start) . ' AND ' . $db->qn('e.enddate') . ' >= ' . $db->q($this->filter_start) . ' AND ' . $db->qn('e.weekdays') . ' = ""))');
//			$query->where('((' . $db->qn('e.next') . ' >= ' . $db->q($this->filter_start) . ') OR (' . $db->qn('e.weekdays') . ' <> ""))');
//			$query->where('((' . $db->qn('e.next') . ' >= ' . $db->q($this->filter_start) . '))');
		}


		// Filter by categories to be displayed
		$catFilter = ! is_array($this->mcatid) ? array($this->mcatid) : $this->mcatid;

		// Note: zero value kept for Joomla 2.5 B/C (option All categories not used on J3 sites)
		if ( $catFilter && ! in_array('0', $catFilter) && ! in_array('', $catFilter))
		{
			$cats_option = implode(', ', $catFilter);

			$query->where('e.catid IN (' . $cats_option . ')');
		}

		// Check Access Levels
		$user		= JFactory::getUser();
		$userID		= $user->id;
		$userLevels	= $user->getAuthorisedViewLevels();
		$userGroups = $user->getAuthorisedGroups();

		$userAccess = implode(', ', $userLevels);

		if ( ! in_array('8', $userGroups))
		{
			$query->where('e.access IN (' . $userAccess . ')');
		}

		// Filter by language
		$query->where('e.language IN (' . $db->q(JFactory::getLanguage()->getTag()) . ',' . $db->q('*') . ')');

		// Features - extract the number of displayable icons per event
		$query->select('feat.count AS features');
		$sub_query = $db->getQuery(true);
		$sub_query->select('fx.event_id, COUNT(*) AS count');
		$sub_query->from('`#__icagenda_feature_xref` AS fx');
		$sub_query->innerJoin("`#__icagenda_feature` AS f ON fx.feature_id=f.id AND f.state=1 AND f.icon<>'-1'");
		$sub_query->group('fx.event_id');
		$query->leftJoin('(' . (string) $sub_query . ') AS feat ON e.id=feat.event_id');

		// Registrations total
//		$query->select('r.count AS registered, r.date AS reg_date');
		$query->select('r.count AS reg_people, r.date AS reg_date');
		$sub_query = $db->getQuery(true);
		$sub_query->select('r.eventid, sum(r.people) AS count, r.date AS date');
		$sub_query->from('`#__icagenda_registration` AS r');
		$sub_query->where('r.state > 0');
		$sub_query->group('r.eventid');
		$query->leftJoin('(' . (string) $sub_query . ') AS r ON e.id=r.eventid');

		// Run the query
		$db->setQuery($query);

		// Invoke the query
		$result = $db->loadObjectList();

//		$registrations = icagendaEventsData::registeredList(); // @todo: migrate this function.

		foreach ($result AS &$record)
		{
			$record_registered = [];
			$registrations = icagendaEventsData::registeredList($record->id); // @todo: migrate this function.

			foreach ($registrations AS $reg_by_event)
			{
				$ex_reg_by_event = explode('@@', $reg_by_event);

				if ($ex_reg_by_event[0] == $record->id)
				{
					$record_registered[] = $ex_reg_by_event[0] . '@@' . $ex_reg_by_event[1] . '@@' . $ex_reg_by_event[2] . '@@' . $ex_reg_by_event[3];
				}
			}

			$record->registered = $record_registered;
		}

		// Get days of the current month
		$days = $this->getDays($this->date_start, 'Y-m-d H:i');

//		$total_items		= 0;
//		$displayed_items	= 0;

		foreach ($result as $item)
		{
			// Frontend Module Check Next
			$item->next = icagendaEvent::getNextDate($item);

			// Extract the feature details, if needed
			$features = array();

			if (is_null($item->features) || empty($this->features_icon_size))
			{
				$item->features = array();
			}
			else
			{
				$item->features = icagendaEvents::featureIcons($item->id);
			}

			if (isset($item->features) && is_array($item->features))
			{
				foreach ($item->features as &$feature)
				{
					$features[] = array('icon' => $feature->icon, 'icon_alt' => $feature->icon_alt);
				}
			}

			// list calendar dates
			$AllDates = array();

//			$next = isset($next) ? $next : '';

			// Get list of valid single dates for this event
			$allSingleDates_array = $this->getDatelist($item->dates);

			sort($allSingleDates_array);

			// If Single Dates, added to all dates for this event
//			if (isset($datemultiplelist)
//				&& $datemultiplelist != NULL
//				&& is_array($datemultiplelist))
//			{
//				$allSingleDates_array = array_merge($AllDates, $datemultiplelist);
//			}

			foreach ($allSingleDates_array as &$sd)
			{
				$this_date = JHtml::date($sd, 'Y-m-d', null);

				if (strtotime($this_date) >= strtotime($current_date_start)
					&& strtotime($this_date) < strtotime($current_date_end))
				{
//					array_push($AllDates, $sd);
					$AllDates[] = $sd;
				}
			}

			// Get WeekDays Array
			$WeeksDays			= iCDatePeriod::weekdaysToArray($item->weekdays);

			// Get Period Dates
			$startDate_TZ		= iCDate::isDate($item->startdate)
								? JHtml::date($item->startdate, 'Y-m-d H:i', $eventTimeZone)
								: false;
			$endDate_TZ			= iCDate::isDate($item->enddate)
								? JHtml::date($item->enddate, 'Y-m-d H:i', $eventTimeZone)
								: false;
			$perioddates		= iCDatePeriod::listDates($item->startdate, $item->enddate); // UTC

			$onlyStDate			= isset($this->onlyStDate) ? $this->onlyStDate : '';

			// Check the period if individual dates
			$only_startdate		= ($item->weekdays || $item->weekdays == '0') ? false : true;

			if ( ! empty($perioddates))
			{
				if ($onlyStDate == 1 && $only_startdate)
				{
					if (strtotime($startDate_TZ) >= strtotime($current_date_start)
						&& strtotime($startDate_TZ) < strtotime($current_date_end))
					{
//						array_push($AllDates, date('Y-m-d H:i', strtotime($item->startdate)));
						$AllDates[] = date('Y-m-d H:i', strtotime($item->startdate));
					}
				}
				else
				{
					foreach ($perioddates as &$Dat)
					{
						$this_date = JHtml::date($Dat, 'Y-m-d', null);

						if (in_array(date('w', strtotime($Dat)), $WeeksDays))
						{
							$SingleDate = date('Y-m-d H:i', strtotime($Dat));

							if (strtotime($this_date) >= strtotime($current_date_start)
								&& strtotime($this_date) < strtotime($current_date_end))
							{
//								array_push($AllDates, $SingleDate);
								$AllDates[] = $SingleDate;
							}
						}
					}
				}
			}

			rsort($AllDates);

//			$total_items = $total_items + 1;

			$descShort = icagendaEvents::shortDescription($item->desc, true, $this->filtering_shortDesc, $this->limit);


			/**
			 * Get Thumbnail
			 */

			// START iCthumb

			// Set if run iCthumb
			if (version_compare(JVERSION, '4.0', 'lt'))
			{
				$img_url = $item->image;
			}
			else
			{
				$img = JHtml::cleanImageURL($item->image);

				$img_url = $img->url;
			}

			if ($img_url
				&& $thumb_generator == 1)
			{
				// Generate small thumb if not exist
				$thumb_img = icagendaThumb::sizeSmall($img_url);
			}
			elseif ($img_url
				&& $thumb_generator == 0)
			{
				$thumb_img = $img_url;
			}
			else
			{
				$thumb_img = $img_url ? 'media/com_icagenda/images/nophoto.jpg' : '';
			}

			// END iCthumb



//			$evtParams = '';
			$evtParams = new JRegistry($item->params);

			// Display Time
			$r_time			= $dp_time ? true : false;

			// Display City
			$r_city			= $dp_city ? $item->city : false;

			// Display Country
			$r_country		= $dp_country ? $item->country : false;

			// Display Venue Name
			$r_place		= $dp_venuename ? $item->place_name : false;

			// Display Intro Text
			// Short Description
			if ($dp_shortDesc == '1')
			{
				$descShort		= $item->shortdesc ? $item->shortdesc : false;
			}
			// Auto-Introtext
			elseif ($dp_shortDesc == '2')
			{
				$descShort		= $descShort ? $descShort : false;
			}
			// Hide
			elseif ($dp_shortDesc == '0')
			{
				$descShort		= false;
			}
			// Auto (First Short Description, if does not exist, Auto-generated short description from the full description. And if does not exist, will use meta description if not empty)
			else
			{
				$e_shortdesc	= $item->shortdesc ? $item->shortdesc : $descShort;
				$descShort		= $e_shortdesc ? $e_shortdesc : $item->metadesc;
			}

			// Display Registration Infos
			$eventRegStatus = $evtParams->get('statutReg', $iCparams->get('statutReg', '0'));
			$dp_regInfos	= ($eventRegStatus == 1) ? $param_dp_regInfos : '';

			$maxReg			= ($dp_regInfos == 1) ? $evtParams->get('maxReg', '1000000') : false;
			$typeReg		= ($dp_regInfos == 1) ? $evtParams->get('typeReg', '1') : false;

			$reg_deadline	= $evtParams->get('reg_deadline', $iCparams->get('reg_deadline', '1'));

			$eventIsCancelled = icagendaEvent::cancelledButton($item->id);

			$eventTitle = $eventIsCancelled ? $item->title . '<div class="ic-float-right">' . $eventIsCancelled . '</div>' : $item->title;

			$event = array(
				'id'					=> (int)$item->id,
//				'Itemid'				=> (int)$linkid,
				'title'					=> $eventTitle,
//				'next'					=> $this->formatDate($item->next),
				'image'					=> $thumb_img,
				'file'					=> $item->file,
				'address'				=> $item->address,
				'city'					=> $r_city,
				'country'				=> $r_country,
				'place'					=> $r_place,
				'description'			=> $item->desc,
				'descShort'				=> $descShort,
				'cat_title'				=> $item->cat_title,
				'cat_order'				=> $item->cat_order,
				'cat_color'				=> $item->cat_color,
//				'nb_events'				=> count($item->id),
				'no_image'				=> JText::_('MOD_ICCALENDAR_NO_IMAGE'),
				'params'				=> $item->params,
				'features_icon_size'	=> $this->features_icon_size,
				'features_icon_root'	=> $this->features_icon_root,
				'show_icon_title'		=> $this->show_icon_title,
				'features'				=> $features,
				'item'					=> $item,
			);

			// Get Option Dislay Time
			$displaytime	= isset($item->displaytime) ? $item->displaytime : '';

			$events_per_day	= array();

			$countEventDates = count($AllDates);

			// Get List of Dates
			if (is_array($event))
			{
				$past_dates = 0;

				foreach ($AllDates as &$d)
				{
					// Control if date is past
					if (strtotime($d) < strtotime($datetime_today))
					{
						$past_dates = $past_dates + 1;
					}
				}

				unset($d);

				$iCmenuitem = is_numeric($iCmenuitem) ? $iCmenuitem : '';

				foreach ($AllDates as &$d)
				{
//					$urlevent       = '';
					$event_filters  = array(
										'date'      => $d,
										'catid'     => $item->catid,
										'language'  => $item->language,
										'access'    => $item->access,
									);

					// If use menu item filters
					if ($iCmenu_filters === '1')
					{
						$linkid = $iCmenuitem
								? icagendaMenus::displayEventItemid($iCmenuitem, $event_filters)
								: icagendaMenus::thisEventItemid($d, $item->catid, $iC_list_menus);
					}
					else
					{
						$linkid = $iCmenuitem ? $iCmenuitem : icagendaMenus::thisEventItemid($d, $item->catid, $iC_list_menus);
						$linkid = $linkid ? $linkid : $menu->getDefault($lang->getTag())->id;
					}

					$eventnumber	= $item->id ? $item->id : null;
					$event_slug		= $item->alias ? $item->id . ':' . $item->alias : $item->id;

//					if ( $linkid >= 0
//						&& JComponentHelper::getComponent('com_icagenda', true)->enabled
//						)
//					{
//						$urlevent   = 'index.php?option=com_icagenda&amp;view=event&amp;id='
//									. $event_slug . '&amp;Itemid=' . (int) $linkid;
//					}


					$this_date_utc  = date('Y-m-d H:i', strtotime($d));

					// Set variable date-alias in url & registration deadline datetime
					if ($only_startdate && in_array($this_date_utc, $perioddates))
					{
						$regDate = '';
//						$set_date_in_url = '';
						$vars = array();

						$regDeadlineDatetime	= ($reg_deadline == '2')
												? JHtml::date($item->enddate, 'Y-m-d H:i:s', $eventTimeZone)
												: JHtml::date($item->startdate, 'Y-m-d H:i:s', $eventTimeZone);
					}
					else
					{
//						$regDate = date('Y-m-d H:i:s', strtotime($d));
						$regDate = JHtml::date($d, 'Y-m-d H:i:s', $eventTimeZone);
//						$set_date_in_url = $date_var . iCDate::dateToAlias($d, 'Y-m-d H:i');
//						$set_date_in_url = '&amp;date=' . iCDate::dateToAlias($d, 'Y-m-d H:i');
						$vars = array('date' => iCDate::dateToAlias($d, 'Y-m-d-H-i'));

						if ($reg_deadline == '2')
						{
							$regDeadlineDatetime	= (in_array($this_date_utc, $perioddates))
													? JHtml::date($d, 'Y-m-d', $eventTimeZone) . ' ' . JHtml::date($item->enddate, 'H:i:s', $eventTimeZone)
													: JHtml::date($d, 'Y-m-d', $eventTimeZone) . ' 23:59:59';
						}
						else
						{
							$regDeadlineDatetime	= JHtml::date($d, 'Y-m-d H:i:s', $eventTimeZone);
						}
					}

					if ($r_time)
					{
						$time = array(
							'time'			=> date($lang_time, strtotime($d)),
							'displaytime'	=> $displaytime,
//							'url'			=> JRoute::_($urlevent . $set_date_in_url),
							'url'			=> icagendaEvent::url($item->id, $item->alias, $linkid, $vars),
						);
					}
					else
					{
						$time = array(
							'time'			=> '',
							'displaytime'	=> '',
//							'url'			=> JRoute::_($urlevent . $set_date_in_url),
							'url'			=> icagendaEvent::url($item->id, $item->alias, $linkid, $vars),
						);
					}

					$event = array_merge($event, $event_filters);

					$event = array_merge($event, $time);

//					$this_date = $item->reg_date ? date('Y-m-d H:i:s', strtotime($d)) : 'period';

					$registrations	= ($dp_regInfos == 1) ? true : false;
					$registered		= ($dp_regInfos == 1)
//									? self::getNbTicketsBooked($this_date, $item->registered, $eventnumber, $typeReg)
									? icagendaRegistration::getRegisteredTotal($item->id, $regDate, $typeReg)
									: false;
					$maxTickets		= ($maxReg != '1000000') ? $maxReg : false;
					$TicketsLeft	= ($dp_regInfos == 1 && $maxReg)
									? ($maxReg - $registered)
									: false;

					$canRegister	= (JHtml::date('Now', 'Y-m-d H:i:s', false) <= $regDeadlineDatetime)
									? true
									: false;


					// Registration for all dates, and no ticket left
					if ($typeReg == '2'
						&& $TicketsLeft <= 0)
					{
						$date_sold_out	= JText::_('MOD_ICCALENDAR_REGISTRATION_CLOSED');
					}

					// Registration by date, and no ticket left
					elseif ($TicketsLeft <= 0)
					{
						if ($regDeadlineDatetime < $datetime_today)
						{
							// Expired deadline, registration closed
							$date_sold_out	= JText::_('MOD_ICCALENDAR_REGISTRATION_CLOSED');
						}
						else
						{
							// Upcoming deadline, but no tickets left for this date
							$date_sold_out	= JText::_('MOD_ICCALENDAR_REGISTRATION_DATE_NO_TICKETS_LEFT');
						}
					}

					// Registration for all dates + Registration until START date + first date past.
					elseif ($typeReg == '2'
						&& ($reg_deadline != '2'
							&& ((iCDate::isDate($startDate_TZ) && $startDate_TZ < $datetime_today)
							|| (isset($allSingleDates_array[0]) && $allSingleDates_array[0] < $datetime_today))
							)
						)
					{
						$date_sold_out	= JText::_('MOD_ICCALENDAR_REGISTRATION_CLOSED');
					}

					// Registration for all dates + Registration until END date + first date past.
					elseif ($typeReg == '2'
						&& ($reg_deadline == '2'
							&& ((iCDate::isDate($endDate_TZ) && $endDate_TZ < $datetime_today)
							|| (iCDate::isDate(end($allSingleDates_array)) && end($allSingleDates_array) < $datetime_today))
							)
						)
					{
						$date_sold_out	= JText::_('MOD_ICCALENDAR_REGISTRATION_CLOSED');
					}

					// Registration by date, and registration deadline is over
					elseif ( ! $canRegister && $typeReg != '2')
					{
						$date_sold_out	= ($TicketsLeft <= 0 && $countEventDates > 1 && $past_dates < $countEventDates)
										? JText::_('MOD_ICCALENDAR_REGISTRATION_DATE_NO_TICKETS_LEFT')
										: JText::_('MOD_ICCALENDAR_REGISTRATION_CLOSED');
					}

					// @todo : test last change > can or cannot register ? (check if needed)
					elseif ($maxTickets
						&& $canRegister
						&& $typeReg != '2'
						)
					{
						$date_sold_out	= ($TicketsLeft <= 0)
										? JText::_('MOD_ICCALENDAR_REGISTRATION_DATE_NO_TICKETS_LEFT')
										: false;
					}

					else
					{
						$date_sold_out	= false;
					}

					$reg_infos = array(
						'registrations'	=> $registrations,
						'registered'	=> $registered,
						'maxTickets'	=> $maxTickets,
						'TicketsLeft'	=> $TicketsLeft,
						'date_sold_out'	=> $date_sold_out,
					);

					$event = array_merge($event, $reg_infos);

					foreach ($days as $k => $dy)
					{
						$d_date		= date('Y-m-d', strtotime($d));
						$dy_date	= date('Y-m-d', strtotime($dy['date']));

						if ($d_date == $dy_date && $linkid)
//						if ($d_date == $dy_date)
						{
							array_push($days[$k]['events'], $event);
//							$days[$k]['events'][]= $event;
						}
					}
				}

				unset($d);
			}
		}

		return $days;

	}

	/**
	 * Function to get number of tickets booked (for one date)
	 *
	 * @since   3.6.0 (Migrated from 3.5)
	 *
	 * @deprecated 3.9.9 - @removed 4.0
	 */
	public static function getNbTicketsBooked($date, $event_registered, $event_id, $typeReg)
	{
		$event_registered = is_array($event_registered) ? $event_registered : array();
		$nb_registrations = 0;

		foreach ($event_registered as $reg)
		{
			$ex_reg = explode('@@', $reg); // eventid@@date@@period@@people

			if ($typeReg == 1
				&& (date('Y-m-d H:i', strtotime($date)) == date('Y-m-d H:i', strtotime($ex_reg[1]))
					|| (! iCDate::isDate($ex_reg[1]) && $ex_reg[2] === 1))
				&& $event_id == $ex_reg[0]
				)
			{
				$nb_registrations = $nb_registrations + $ex_reg[3];
			}

			elseif ($typeReg == 1
				&& ! iCDate::isDate($date)
				&& $event_id == $ex_reg[0]
				)
			{
				$nb_registrations = $nb_registrations + $ex_reg[3];
			}

			elseif ($typeReg == 2)
			{
				$nb_registrations = $nb_registrations + $ex_reg[3];
			}
		}

		return $nb_registrations;
	}

	// Function to get Format Date (using option format, and translation)
	protected function formatDate($date, $tz = false)
	{
		// Date Format Option (Global Component Option)
		$date_format_global	= JComponentHelper::getParams('com_icagenda')->get('date_format_global', 'Y - m - d');
		$date_format_global	= ($date_format_global !== '0') ? $date_format_global : 'Y - m - d'; // Previous 3.5.6 setting

		// Date Format Option (Module Option)
		$date_format_module	= isset($this->format) ? $this->format : '';
		$date_format_module	= ($date_format_module !== '0') ? $date_format_module : ''; // Previous 3.5.6 setting

		// Set Date Format option to be used
		$format				= $date_format_module ? $date_format_module : $date_format_global;

		// Separator Option
		$separator			= isset($this->date_separator) ? $this->date_separator : ' ';

		if ( ! is_numeric($format))
		{
			// Update old Date Format options of versions before 2.1.7
			$format = str_replace(array('nosep', 'nosep', 'sepb', 'sepa'), '', $format);
			$format = str_replace('.', ' .', $format);
			$format = str_replace(',', ' ,', $format);
		}

		$dateFormatted = iCGlobalize::dateFormat($date, $format, $separator, $tz);

		return $dateFormatted;
	}


	// Function to get TimeZone offset
	function get_timezone_offset($remote_tz, $origin_tz = null)
	{
		if ($origin_tz === null)
		{
			if (!is_string($origin_tz = date_default_timezone_get()))
			{
				return false; // A UTC timestamp was returned -- bail out!
			}
		}

		$origin_dtz	= new DateTimeZone($origin_tz);
		$remote_dtz	= new DateTimeZone($remote_tz);
		$origin_dt	= new DateTime("now", $origin_dtz);
		$remote_dt	= new DateTime("now", $remote_dtz);
		$offset		= $origin_dtz->getOffset($origin_dt) - $remote_dtz->getOffset($remote_dt);

		return $offset;
	}

	function getNbOfDaysInMonth($date)
	{
		$lang = JFactory::getLanguage();

		// Get Nb of days in the month in Jalali/Persian calendar
		if ($lang->getTag() == 'fa-IR')
		{
			$date_to_persian	= $date;
			$persian_month		= date('m', strtotime($date_to_persian));
			$persian_year		= date('Y', strtotime($date_to_persian));
			$leap_year			= fa_IRDate::leap_persian($persian_year);

			if ($persian_month < 7)
			{
				$days = 31;
			}
			elseif ($persian_month == 12)
			{
				$days = $leap_year ? 30 : 29;
			}
			else
			{
				$days = 30;
			}
		}

		// Get Nb of days in the month in Gregorian calendar
		else
		{
			$days = date("t", strtotime($date));
		}

		return $days;
	}

	// Generate the days of the month
	function getDays($d, $f)
	{
		$lang = JFactory::getLanguage();
		$eventTimeZone = null;

		$days = self::getNbOfDaysInMonth($d);

		// Set Month and Year
		$ex_data	= explode('-', $d);
		$month		= $ex_data[1];
		$year		= $ex_data[0];
		$jour		= $ex_data[2];

		$list = array();

		//
		// Setting function of the visitor Time Zone
		//
		$today = time();

		$config			= JFactory::getConfig();
		$joomla_offset	= $config->get('offset');

		$displayDatesTimezone = '0'; // Option not active

		$opt_TimeZone = isset($this->setTodayTimezone) ? $this->setTodayTimezone : '';

		$gmt_today			= gmdate('Y-m-d H:i:s', $today);
		$today_timestamp	= strtotime($gmt_today);
		$GMT_timezone		= 'Etc/UTC';

		if ($opt_TimeZone == 'SITE')
		{
			// Joomla Server Time Zone
			$visitor_timezone	= $joomla_offset;
			$offset				= $this->get_timezone_offset($GMT_timezone, $visitor_timezone);
			$visitor_today		= JHtml::date(($today_timestamp+$offset), 'Y-m-d H:i:s', null);
			$UTCsite			= $offset / 3600;

			if ($UTCsite > 0) $UTCsite = '+'.$UTCsite;

			if ($displayDatesTimezone == '1')
			{
				echo '<small>' . JHtml::date('now', 'Y-m-d H:i:s', true) . ' UTC' . $UTCsite . '</small><br />';
			}
		}
		elseif ($opt_TimeZone == 'UTC')
		{
			// UTC Time Zone
			$offset			= 0;
			$visitor_today = JHtml::date(($today_timestamp+$offset), 'Y-m-d H:i:s', null);
			$UTC			= $offset / 3600;

			if ($UTC > 0) $UTC = '+'.$UTC;

			if ($displayDatesTimezone == '1')
			{
				echo '<small>' . gmdate('Y-m-d H:i:s', $today) . ' UTC' . $UTC . '</small><br />';
			}
		}
		else
		{
			$visitor_today = JHtml::date(($today_timestamp), 'Y-m-d H:i:s', null);
		}

		$date_today	= str_replace(' ', '-', $visitor_today);
		$date_today	= str_replace(':', '-', $date_today);
		$ex_data	= explode('-', $date_today);
		$v_month	= $ex_data[1];
		$v_year		= $ex_data[0];
		$v_day		= $ex_data[2];
		$v_hours	= $ex_data[3];
		$v_minutes	= $ex_data[4];

		for ($a = 1; $a <= $days; $a++)
		{
			$calday = $a;

			$this_date_a = $year . '-' . $month . '-' . $a;

			if ($lang->getTag() == 'fa-IR')
			{
				$this_date_cal = iCGlobalizeConvert::jalaliToGregorian($year, $month, $a, true);
			}
			else
			{
				$this_date_cal = $year . '-' . $month . '-' . $a;
			}

			if (($a == $v_day) && ($month == $v_month) && ($year == $v_year))
			{
				$classDay = 'style_Today';
			}
			else
			{
				$classDay = 'style_Day';
			}

			$datejour			= JHtml::date($this_date_cal, 'Y-m-d', $eventTimeZone);
			$this_year_month	= $year . '-' . $month . '-00';
			$list_a_date		= date('Y-m-d H:i', strtotime($this_date_a));

			// Set Date in tooltip header
			$date_to_format					= $this->formatDate($this_date_cal, false);
			$list[$calday]['dateTitle']		= $date_to_format;

//			$list[$calday]['datecal']		= JHtml::date($this_date_a, 'j', null);
//			$list[$calday]['monthcal']		= JHtml::date($this_date_a, 'm', null);
//			$list[$calday]['yearcal']		= JHtml::date($this_date_a, 'Y', null);

			$list[$calday]['date']			= date('Y-m-d H:i', strtotime($this_date_cal));

//			$list[$calday]['dateFormat']	= date($f, strtotime($this_date_a));
			$list[$calday]['week']			= date('N', strtotime($this_date_a));
			$list[$calday]['day']			= '<div class="' . $classDay . '">' . $a . '</div>';

			// Set cal_date
			$list[$calday]['this_day']		= date('Y-m-d', strtotime($this_date_a));

			// Added in 2.1.2 (change in NAME_day.php)
			$list[$calday]['ifToday']		= $classDay;
			$list[$calday]['Days']			= $a;

			// Set event array
			$list[$calday]['events']		= array();
		}

		return $list;
	}
	/***/


	/**
	 * Single Dates list for one event
	 */
	private function getDatelist($dates)
	{
		$dates  = iCString::isSerialized($dates) ? unserialize($dates) : array();
		$list   = array();

		foreach ($dates as &$d)
		{
			if (iCDate::isDate($d))
			{
//				array_push($list, date('Y-m-d H:i', strtotime($d)));
				$list[]= date('Y-m-d H:i', strtotime($d));
			}
		}

		return $list;
	}


	/** Systeme de navigation **/
	function getNav($date_start, $modid)
	{
		$app	= JFactory::getApplication();
		$isSef	= $app->getCfg( 'sef' );

		// Return Current URL
		$url	= JUri::getInstance()->toString() . '#tag';
		$url	= preg_replace('/&iccaldate=[^&]*/', '', $url);
		$url	= preg_replace('/\?iccaldate=[^\?]*/', '', $url);

		// Set Separator for Navigation Var
		$separator = strpos($url, '?') !== false ? '&amp;' : '?';

		// Remove fragment (hashtag could be added by a third party extension, eg. nonumber framework)
		$parsed_url	= parse_url($url);
		$fragment	= isset($parsed_url['fragment']) ? '#' . $parsed_url['fragment'] : '';

		$url	= str_replace($fragment, '', $url);

		// Return Current URL Filtered
		$url	= htmlspecialchars($url);

		// Start Date
		$ex_date	= explode('-', $date_start);
		$year		= $ex_date[0];
		$month		= $ex_date[1];
		$day		= 1;

		if ($month != 1)
		{
			$backMonth = $month-1;
			$backYear = $year;
		}
		elseif ($month == 1)
		{
			$backMonth = 12;
			$backYear = $year-1;
		}

		if ($month != 12)
		{
			$nextMonth = $month+1;
			$nextYear = $year;
		}
		elseif ($month == 12)
		{
			$nextMonth = 1;
			$nextYear = $year+1;
		}

		$backYYear = $year-1;
		$nextYYear = $year+1;

		// A11Y (experimental, since 3.5.14) : see https://www.w3.org/TR/2012/NOTE-WCAG20-TECHS-20120103/C7
		$icTitleAccess = 'height: 1px; width: 1px; position: absolute; overflow: hidden; top: -10px;';

		// Create Navigation Arrows
		$classBackYear	= 'backicY icagendabtn_' . $modid;
		$urlBackYear	= $url . $separator . 'iccaldate=' . $backYYear . '-' . $month . '-' . $day;
		$iconBackYear	= '<span class="iCicon iCicon-backicY"></span>';

		$backY	= '<a id="ic-prev-year" class="' . $classBackYear . '"'
				. ' href="' . $urlBackYear . '"'
//				. ' title="' . JText::_('MOD_ICCALENDAR_PREVIOUS_YEAR') . '"'
				. ' rel="nofollow">'
				. '<span style="' . $icTitleAccess . '" title="">' . JText::_('MOD_ICCALENDAR_PREVIOUS_YEAR') . '</span>'
				. $iconBackYear
				. '</a>';

		$classBackMonth	= 'backic icagendabtn_' . $modid;
		$urlBackMonth	= $url . $separator . 'iccaldate=' . $backYear . '-' . $backMonth . '-' . $day;
		$iconBackMonth	= '<span class="iCicon iCicon-backic"></span>';

		$back	= '<a id="ic-prev-month" class="' . $classBackMonth . '"'
				. ' href="' . $urlBackMonth . '"'
//				. ' title="' . JText::_('MOD_ICCALENDAR_PREVIOUS_MONTH') . '"'
				. ' rel="nofollow">'
				. '<span style="' . $icTitleAccess . '" title="">' . JText::_('MOD_ICCALENDAR_PREVIOUS_MONTH') . '</span>'
				. $iconBackMonth
				. '</a>';

		$classNextMonth	= 'nextic icagendabtn_' . $modid;
		$urlNextMonth	= $url . $separator . 'iccaldate=' . $nextYear . '-' . $nextMonth . '-' . $day;
		$iconNextMonth	= '<span class="iCicon iCicon-nextic"></span>';

		$next	= '<a id="ic-next-month" class="' . $classNextMonth . '"'
				. ' href="' . $urlNextMonth . '"'
//				. ' title="' . JText::_('MOD_ICCALENDAR_NEXT_MONTH') . '"'
				. ' rel="nofollow">'
				. '<span style="' . $icTitleAccess . '" title="">' . JText::_('MOD_ICCALENDAR_NEXT_MONTH') . '</span>'
				. $iconNextMonth
				. '</a>';

		$classNextYear	= 'nexticY icagendabtn_' . $modid;
		$urlNextYear	= $url . $separator . 'iccaldate=' . $nextYYear . '-' . $month . '-' . $day;
		$iconNextYear	= '<span class="iCicon iCicon-nexticY"></span>';

		$nextY	= '<a id="ic-next-year" class="' . $classNextYear . '"'
				. ' href="' . $urlNextYear . '"'
//				. ' title="' . JText::_('MOD_ICCALENDAR_NEXT_YEAR') . '"'
				. ' rel="nofollow">'
				. '<span style="' . $icTitleAccess . '" title="">' . JText::_('MOD_ICCALENDAR_NEXT_YEAR') . '</span>'
				. $iconNextYear
				. '</a>';

		if ( ! $this->month_nav) $back = $next = '';
		if ( ! $this->year_nav) $backY = $nextY = '';

		/** translate the month in the calendar module -- Leland Vandervort **/
		$dateFormat = date('Y-m-d', strtotime($date_start));

		// split out the month and year to obtain translation key for JText using joomla core translation
		$t_day		= date('d', strtotime($dateFormat));
		$t_month	= date('F', strtotime($dateFormat));
		$t_year		= date('Y', strtotime($dateFormat));

		$lang		= JFactory::getLanguage();
		$langTag	= $lang->getTag();

		$yearBeforeMonth = array('ar-AA', 'ja-JP', 'hu-HU', 'zh-CN', 'zh-TW');

		$monthBeforeYear = in_array($langTag, $yearBeforeMonth) ? 0 : 1;

		/**
		 * Get prefix, suffix and separator for month and year in calendar title
		 */

		// Separator Month/Year
		$separator_month_year = JText::_('SEPARATOR_MONTH_YEAR');
		if ($separator_month_year == 'CALENDAR_SEPARATOR_MONTH_YEAR_FACULTATIVE')
		{
			$separator_month_year = ' ';
		}
		elseif ($separator_month_year == 'NO_SEPARATOR')
		{
			$separator_month_year = '';
		}

		// Prefix Month (Facultative)
		$prefix_month = JText::_('PREFIX_MONTH');
		if ($prefix_month == 'CALENDAR_PREFIX_MONTH_FACULTATIVE')
		{
			$prefix_month = '';
		}

		// Suffix Month (Facultative)
		$suffix_month = JText::_('SUFFIX_MONTH');
		if ($suffix_month == 'CALENDAR_SUFFIX_MONTH_FACULTATIVE')
		{
			$suffix_month = '';
		}

		// Prefix Year (Facultative)
		$prefix_year = JText::_('PREFIX_YEAR');
		if ($prefix_year == 'CALENDAR_PREFIX_YEAR_FACULTATIVE')
		{
			$prefix_year = '';
		}

		// Suffix Year (Facultative)
		$suffix_year = JText::_('SUFFIX_YEAR');
		if ($suffix_year == 'CALENDAR_SUFFIX_YEAR_FACULTATIVE')
		{
			$suffix_year = '';
		}

		$SEP	= $separator_month_year;
		$PM		= $prefix_month;
		$SM		= $suffix_month;
		$PY		= $prefix_year;
		$SY		= $suffix_year;

		// Get MONTH_CAL string or if not translated, use MONTHS
		$array_months = array(
			'JANUARY', 'FEBRUARY', 'MARCH', 'APRIL', 'MAY', 'JUNE',
			'JULY', 'AUGUST', 'SEPTEMBER', 'OCTOBER', 'NOVEMBER', 'DECEMBER'
		);

		$cal_string			= $t_month . '_CAL';
		$missing_cal_string	= iCFilterOutput::stringToJText($cal_string);

		if ( in_array($missing_cal_string, $array_months) )
		{
			// if MONTHS_CAL strings not translated in current language, use MONTHS strings
			$month_J = JText::_( $t_month );
		}
		else
		{
			// Use MONTHS_CAL strings when translated in current language
			$month_J = JText::_( $t_month . '_CAL' );
		}

		// Set Calendar Title
		if ($monthBeforeYear == 0)
		{
			$title = $PY . $t_year . $SY . $SEP . $PM . $month_J . $SM;
		}
		else
		{
			$title = $PM . $month_J . $SM . $SEP . $PY . $t_year . $SY;
		}

		// Set Nav Bar for calendar
		$html = '<div class="icnav">' . $backY . $back . $nextY . $next;
		$html.= '<div class="titleic">' . $title . '</div>';
		$html.= '</div><div style="clear:both"></div>';

		return $html;
	}
}

class cal extends stdClass
{
	public $data;
	public $template;
	public $t_calendar;
	public $t_day;
	public $nav;
	public $fontcolor;
	private $header_text;

	function __construct ($data, $t_calendar, $t_day, $nav,
		$firstday, $columns_bg_color,
		$calfontcolor, $OneEventbgcolor, $Eventsbgcolor, $bgcolor, $bgimage, $bgimagerepeat,
		$moduleclass_sfx, $modid, $template, $ictip_ordering, $header_text)
	{
		$this->data				= $data;
		$this->t_calendar		= $t_calendar;
		$this->t_day			= $t_day;
		$this->nav				= $nav;
		$this->firstday			= $firstday;
		$this->calfontcolor		= $calfontcolor;
		$this->OneEventbgcolor	= $OneEventbgcolor;
		$this->Eventsbgcolor	= $Eventsbgcolor;
		$this->bgcolor			= $bgcolor;
		$this->bgimage			= $bgimage;
		$this->bgimagerepeat	= $bgimagerepeat;
		$this->moduleclass_sfx	= $moduleclass_sfx;
		$this->modid			= $modid;
		$this->template			= $template;
		$this->ictip_ordering	= $ictip_ordering;
		$this->header_text		= $header_text;

		// Columns Background colors
		$cbc					= $columns_bg_color;

		$this->weekdays = array('MON', 'TUE', 'WED', 'THU', 'FRI', 'SAT', 'SUN');

		switch ($this->firstday)
		{
			case 0:
				$this->colbg	= array($cbc[0], $cbc[1], $cbc[2], $cbc[3], $cbc[4], $cbc[5], $cbc[6]);
				$this->day		= array(7, 1, 2, 3, 4, 5, 6);
				break;

			case 1:
				$this->colbg	= array($cbc[1], $cbc[2], $cbc[3], $cbc[4], $cbc[5], $cbc[6], $cbc[0]);
				$this->day		= array(1, 2, 3, 4, 5, 6, 7);
				break;

			case 2:
				$this->colbg	= array($cbc[2], $cbc[3], $cbc[4], $cbc[5], $cbc[6], $cbc[0], $cbc[1]);
				$this->day		= array(2, 3, 4, 5, 6, 7, 1);
				break;

			case 3:
				$this->colbg	= array($cbc[3], $cbc[4], $cbc[5], $cbc[6], $cbc[0], $cbc[1], $cbc[2]);
				$this->day		= array(3, 4, 5, 6, 7, 1, 2);
				break;

			case 4:
				$this->colbg	= array($cbc[4], $cbc[5], $cbc[6], $cbc[0], $cbc[1], $cbc[2], $cbc[3]);
				$this->day		= array(4, 5, 6, 7, 1, 2, 3);
				break;

			case 5:
				$this->colbg	= array($cbc[5], $cbc[6], $cbc[0], $cbc[1], $cbc[2], $cbc[3], $cbc[4]);
				$this->day		= array(5, 6, 7, 1, 2, 3, 4);
				break;

			case 6:
				$this->colbg	= array($cbc[6], $cbc[0], $cbc[1], $cbc[2], $cbc[3], $cbc[4], $cbc[5]);
				$this->day		= array(6, 7, 1, 2, 3, 4, 5);
				break;

			default:
				$this->colbg	= array($cbc[0], $cbc[1], $cbc[2], $cbc[3], $cbc[4], $cbc[5], $cbc[6]);
				$this->day		= array(7, 1, 2, 3, 4, 5, 6);
				break;
		}
	}


	function days()
	{
		$this_calfontcolor	= isset($this->calfontcolor) ? str_replace(' ', '', $this->calfontcolor) : '';
		$calfontcolor		= ! empty($this_calfontcolor) ? ' color:' . $this->calfontcolor . ';' : '';
		$this_bgcolor		= isset($this->bgcolor) ? str_replace(' ', '', $this->bgcolor) : '';
		$bgcolor			= ! empty($this_bgcolor) ? ' background-color:' . $this->bgcolor . ';' : '';
		$this_bgimage		= isset($this->bgimage) ? str_replace(' ', '', $this->bgimage) : '';
		$bgimage			= ! empty($this_bgimage) ? ' background-image:url(\'' . $this->bgimage . '\');' : '';
		$this_bgimagerepeat	= isset($this->bgimagerepeat) ? str_replace(' ', '', $this->bgimagerepeat) : '';
		$bgimagerepeat		= ! empty($this_bgimagerepeat) ? ' background-repeat:' . $this->bgimagerepeat . ';' : '';
		$iCcal_style		= '';

		if ( ! empty($this_calfontcolor)
			|| ! empty($this_bgcolor)
			|| ! empty($this_bgimage)
			|| ! empty($this_bgimagerepeat) )
		{
			$iCcal_style.= 'style="';
		}

		$iCcal_style.= $calfontcolor . $bgcolor . $bgimage;
		$iCcal_style.= ($this_bgimagerepeat && $this_bgimage) ? $bgimagerepeat : '';
		$iCcal_style.= (empty($this_bgcolor) && empty($this_bgimage)) ? ' background-color: transparent; background-image: none;' : '';
		$iCcal_style.= '"';

		// Verify Hex color strings
		$OneEventbgcolor	= preg_match('/^#[a-f0-9]{6}$/i', $this->OneEventbgcolor) ? $this->OneEventbgcolor : '';
		$Eventsbgcolor		= preg_match('/^#[a-f0-9]{6}$/i', $this->Eventsbgcolor) ? $this->Eventsbgcolor : '';

		// Start HTML rendering of calendar
		$calendar = '';

		$calendar.= '<div class="' . $this->template . ' iccalendar ' . $this->moduleclass_sfx . '" ' . $iCcal_style . ' id="' . $this->modid . '">';

		$daywidth = 'width:14.2857143%;'; // @TODO replace with a class.

		$colbg_0 = ! empty($this->colbg[0]) ? 'background:' . $this->colbg[0] . ';' : '';
		$colbg_1 = ! empty($this->colbg[1]) ? 'background:' . $this->colbg[1] . ';' : '';
		$colbg_2 = ! empty($this->colbg[2]) ? 'background:' . $this->colbg[2] . ';' : '';
		$colbg_3 = ! empty($this->colbg[3]) ? 'background:' . $this->colbg[3] . ';' : '';
		$colbg_4 = ! empty($this->colbg[4]) ? 'background:' . $this->colbg[4] . ';' : '';
		$colbg_5 = ! empty($this->colbg[5]) ? 'background:' . $this->colbg[5] . ';' : '';
		$colbg_6 = ! empty($this->colbg[6]) ? 'background:' . $this->colbg[6] . ';' : '';

		$calendar.= '<div id="mod_iccalendar_' . $this->modid . '">
			<div class="icagenda_header">' . $this->header_text . '
			</div>' . $this->nav . '
			<table id="icagenda_calendar" class="ic-table" style="width:100%;">
				<thead>
					<tr>
						<th style="' . $daywidth . $colbg_0 . '">' . JText::_($this->weekdays[($this->day[0]-1)]) . '</th>
						<th style="' . $daywidth . $colbg_1 . '">' . JText::_($this->weekdays[($this->day[1]-1)]) . '</th>
						<th style="' . $daywidth . $colbg_2 . '">' . JText::_($this->weekdays[($this->day[2]-1)]) . '</th>
						<th style="' . $daywidth . $colbg_3 . '">' . JText::_($this->weekdays[($this->day[3]-1)]) . '</th>
						<th style="' . $daywidth . $colbg_4 . '">' . JText::_($this->weekdays[($this->day[4]-1)]) . '</th>
						<th style="' . $daywidth . $colbg_5 . '">' . JText::_($this->weekdays[($this->day[5]-1)]) . '</th>
						<th style="' . $daywidth . $colbg_6 . '">' . JText::_($this->weekdays[($this->day[6]-1)]) . '</th>
					</tr>
				</thead>
		';

		switch ($this->data[1]['week'])
		{
			case $this->day[0]:
				break;

			case $this->day[1]:
				$calendar.= '<tr><td colspan="1"></td>';
				break;

			case $this->day[2]:
				$calendar.= '<tr><td colspan="2"></td>';
				break;

			case $this->day[3]:
				$calendar.= '<tr><td colspan="3"></td>';
				break;

			case $this->day[4]:
				$calendar.= '<tr><td colspan="4"></td>';
				break;

			case $this->day[5]:
				$calendar.= '<tr><td colspan="5"></td>';
				break;

			case $this->day[6]:
				$calendar.= '<tr><td colspan="6"></td>';
				break;

			default:
				$calendar.= '<tr><td colspan="' . ($this->data[1]['week']-$this->firstday) . '"></td>';
				break;
		}

		foreach ($this->data as &$d)
		{
			$stamp = new day($d);

			switch($stamp->week)
			{
				case $this->day[0]:
					$calendar.= '<tr><td style="background:' . $this->colbg[0] . ';">';
					break;

				case $this->day[1]:
					$calendar.= '<td style="background:' . $this->colbg[1] . ';">';
					break;

				case $this->day[2]:
					$calendar.= '<td style="background:' . $this->colbg[2] . ';">';
					break;

				case $this->day[3]:
					$calendar.= '<td style="background:' . $this->colbg[3] . ';">';
					break;

				case $this->day[4]:
					$calendar.= '<td style="background:' . $this->colbg[4] . ';">';
					break;

				case $this->day[5]:
					$calendar.= '<td style="background:' . $this->colbg[5] . ';">';
					break;

				case $this->day[6]:
					$calendar.= '<td style="background:' . $this->colbg[6] . ';">';
					break;

				default:
					$calendar.= '<td>';
					break;
			}

			$count_events = count($stamp->events);

			if ($OneEventbgcolor
				&& $OneEventbgcolor != ' '
				&& $count_events == '1')
			{
				$bg_day = $OneEventbgcolor;
			}
			elseif ($Eventsbgcolor
				&& $Eventsbgcolor != ' '
				&& $count_events > '1')
			{
				$bg_day = $Eventsbgcolor;
			}
			else
			{
				$bg_day = isset($stamp->events[0]['cat_color']) ? $stamp->events[0]['cat_color'] : '#d4d4d4';
			}

			$bgcolor		= iCColor::getBrightness($bg_day);
			$bgcolor		= ($bgcolor == 'bright') ? 'ic-bright' : 'ic-dark';
			$order			= 'first';

			$multi_events	= isset($stamp->events[1]['cat_color']) ? 'icmulti' : '';

			// Ordering by time New Theme Packs (since 3.2.9)
			$events			= $stamp->events;

			// Option for Ordering is not yet finished. This developpement is in brainstorming...
//			$ictip_ordering = '1';
//			$ictip_ordering = $this->ictip_ordering;

//			if ($ictip_ordering == '1_ASC-1_ASC' || $ictip_ordering == '1_ASC-1_DESC') $ictip_ordering = '1_ASC';
//			if ($ictip_ordering == '2_ASC-2_ASC' || $ictip_ordering == '2_ASC-2_DESC') $ictip_ordering = '2_ASC';
//			if ($ictip_ordering == '1_DESC-1_ASC' || $ictip_ordering == '1_DESC-1_DESC') $ictip_ordering = '1_DESC';
//			if ($ictip_ordering == '2_DESC-2_ASC' || $ictip_ordering == '2_DESC-2_DESC') $ictip_ordering = '2_DESC';

			// Create Functions for Ordering
			// Default $newfunc_1_ASC_2_ASC - edited 2015-07-01 to fix ordering by Time when am/pm
			
			// @deprecated 3.6.14 (php 7.2 deprecated)
// 			$newfunc_1_ASC_2_ASC = create_function('$a, $b', 'if ($a["time"] == $b["time"]){ return strcasecmp($a["cat_title"], $b["cat_title"]); } else { return strcasecmp(date("H:i", strtotime($a["time"])), date("H:i", strtotime($b["time"]))); }');

//			$newfunc_1_ASC_2_DESC = create_function('$a, $b', 'if ($a["time"] == $b["time"]){ return strcasecmp($b["cat_title"], $a["cat_title"]); } else { return strcasecmp($a["time"], $b["time"]); }');
//			$newfunc_1_DESC_2_ASC = create_function('$a, $b', 'if ($a["time"] == $b["time"]){ return strcasecmp($a["cat_title"], $b["cat_title"]); } else { return strcasecmp($b["time"], $a["time"]); }');
//			$newfunc_1_DESC_2_DESC = create_function('$a, $b', 'if ($a["time"] == $b["time"]){ return strcasecmp($b["cat_title"], $a["cat_title"]); } else { return strcasecmp($b["time"], $a["time"]); }');

//			$newfunc_2_ASC_1_ASC = create_function('$a, $b', 'if ($a["cat_title"] == $b["cat_title"]){ return strcasecmp($a["time"], $b["time"]); } else { return strcasecmp($a["cat_title"], $b["cat_title"]); }');
//			$newfunc_2_ASC_1_DESC = create_function('$a, $b', 'if ($a["cat_title"] == $b["cat_title"]){ return strcasecmp($b["time"], $a["time"]); } else { return strcasecmp($a["cat_title"], $b["cat_title"]); }');
//			$newfunc_2_DESC_1_ASC = create_function('$a, $b', 'if ($a["cat_title"] == $b["cat_title"]){ return strcasecmp($a["time"], $b["time"]); } else { return strcasecmp($b["cat_title"], $a["cat_title"]); }');
//			$newfunc_2_DESC_1_DESC = create_function('$a, $b', 'if ($a["cat_title"] == $b["cat_title"]){ return strcasecmp($b["time"], $a["time"]); } else { return strcasecmp($b["cat_title"], $a["cat_title"]); }');

//			$newfunc_1_ASC = create_function('$a, $b', 'return strcasecmp($a["time"], $b["time"]);');
//			$newfunc_2_ASC = create_function('$a, $b', 'return strcasecmp($a["cat_title"], $b["cat_title"]);');

//			$newfunc_1_DESC = create_function('$a, $b', 'return strcasecmp($b["time"], $a["time"]);');
//			$newfunc_2_DESC = create_function('$a, $b', 'return strcasecmp($b["cat_title"], $a["cat_title"]);');

			// Order by time - Old Theme Packs (before 3.2.9) : Update Theme Pack to get all options
//			usort($stamp->events, $newfunc_1_ASC_2_ASC);
			usort($stamp->events,
				function($a, $b)
				{
					if ($a["time"] == $b["time"])
					{
						return strcasecmp($a["title"], $b["title"]);
					}
					else
					{
						return strcasecmp(date("H:i", strtotime($a["time"])), date("H:i", strtotime($b["time"])));
					}
				}
			);

			// Time ASC and if same time : Category Title ASC (default)
//			if ($ictip_ordering == '1_ASC-2_ASC')
//			{
//				usort($events, $newfunc_1_ASC_2_ASC);
				usort($events,
					function($a, $b)
					{
						if ($a["time"] == $b["time"])
						{
							return strcasecmp($a["title"], $b["title"]);
						}
						else
						{
							return strcasecmp(date("H:i", strtotime($a["time"])), date("H:i", strtotime($b["time"])));
						}
					}
				);
//			}
			// Time ASC and if same time : Category Title DESC
//			elseif ($ictip_ordering == '1_ASC-2_DESC')
//			{
//				usort($events, $newfunc_1_ASC_2_DESC);
//			}
			// Time DESC and if same time : Category Title ASC
//			elseif ($ictip_ordering == '1_DESC-2_ASC')
//			{
//				usort($events, $newfunc_1_DESC_2_ASC);
//			}
			// Time DESC and if same time : Category Title DESC
//			elseif ($ictip_ordering == '1_DESC-2_DESC')
//			{
//				usort($events, $newfunc_1_DESC_2_DESC);
//			}

			// Category Title ASC and if same category : Time ASC
//			elseif ($ictip_ordering == '2_ASC-1_ASC')
//			{
//				usort($events, $newfunc_2_ASC_1_ASC);
//			}
			// Category Title ASC and if same category : Time DESC
//			elseif ($ictip_ordering == '2_ASC-1_DESC')
//			{
//				usort($events, $newfunc_2_ASC_1_DESC);
//			}
			// Category Title DESC and if same category : Time ASC
//			elseif ($ictip_ordering == '2_DESC-1_ASC')
//			{
//				usort($events, $newfunc_2_DESC_1_ASC);
//			}
			// Category Title DESC and if same category : Time DESC
//			elseif ($ictip_ordering == '2_DESC-1_DESC')
//			{
//				usort($events, $newfunc_2_DESC_1_DESC);
//			}

			// If main ordering and sub-ordering on Time : set TIME ASC (with no sub-ordering)
//			elseif ($ictip_ordering == '1_ASC')
//			{
//				usort($events, $newfunc_1_ASC);
//			}
			// If main ordering and sub-ordering on Category Title : set CATEGORY TITLE ASC (with no sub-ordering)
//			elseif ($ictip_ordering == '2_ASC')
//			{
//				usort($events, $newfunc_2_ASC);
//			}


			// Load template for day infotip
//			require $this->t_day;
			// Check to see if we have a valid template file
			if (file_exists($this->t_day))
			{
				// Store the file path
				$this->_file = $this->t_day;

				// Get the file content
				ob_start();
				require $this->t_day;
				$cal_day_layout = ob_get_contents();
				ob_end_clean();
			}

			$calendar.= $cal_day_layout;

			switch('week')
			{
				case $this->day[6]:
					$calendar.= '</td></tr>';
					break;

				default:
					$calendar.= '</td>';
					break;
			}
		}

		unset($d);

		switch ($stamp->week)
		{
			case $this->day[6]:
				break;

			default:
				$calendar.= '<td colspan="' . (7-$stamp->week) . '"></td></tr>';
				break;
		}

		$calendar.= '</table></div>';

		$calendar.= '</div>';

		echo $calendar;
	}
}

class day extends stdClass
{
	public $date;
	public $week;
	public $day;
	public $month;
	public $year;
	public $events;
	public $fontcolor;

	function __construct($day)
	{
		foreach ($day as $k => $v)
		{
			$this->$k = $v;
		}
	}
}
