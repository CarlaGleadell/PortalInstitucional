<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.8.10 2022-11-04
 *
 * @package     iCagenda.Admin
 * @subpackage  Utilities
 * @link        https://www.icagenda.com
 *
 * @author      Cyril Rezé
 * @copyright   (c) 2012-2024 Cyril Rezé / iCagenda. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 *
 * @since       3.6.0
 *----------------------------------------------------------------------------
*/

defined('_JEXEC') or die;

use Joomla\CMS\Language\Multilanguage;
use Joomla\Utilities\ArrayHelper;
use Joomla\Registry\Registry;

/**
 * class icagendaEvent
 */
class icagendaEvent
{
	/**
	 * Get Next Date (or Last Date) from event dates
	 *
	 * @since   3.7.15
	 */
	public static function getNextDate($item, $reset = false)
	{
		if (is_array($item))
		{
			$item = ArrayHelper::toObject($item);
		}

		$eventTimeZone = null;

		// If no stored datetime for next, or next date is past, we need to update next date
		if ( ! isset($item->next)
			|| ($item->next <= JHtml::date('now', 'Y-m-d H:i:s')
				|| ! iCDate::isDate($item->next))
			)
		{
			$reset = true;
		}

		if ($reset)
		{
			$next_singleDates = $last_singleDates = '';
			$next = $next_period = $last_period = '';

			// Single Dates Check
			$singleDates = iCString::isSerialized($item->dates) ? unserialize($item->dates) : array();

			$upcomingSingleDates = array_filter(
				$singleDates,
				function($date)
				{
					return $date >= JHtml::date('now', 'Y-m-d H:i:s');
				}
			);

			if (count($upcomingSingleDates) > 0)
			{
				$next_singleDates = min($upcomingSingleDates);
				$next_singleDates = iCDate::isDate($next_singleDates) ? JHtml::date($next_singleDates, 'Y-m-d H:i:s', $eventTimeZone) : '';
			}
			else
			{
				$pastSingleDates = array_filter(
					$singleDates,
					function ($date)
					{
						return $date < JHtml::date('now', 'Y-m-d H:i:s');
					}
				);

				if (count($pastSingleDates) > 0)
				{
					$last_singleDates = max($pastSingleDates);
					$last_singleDates = iCDate::isDate($last_singleDates) ? JHtml::date($last_singleDates, 'Y-m-d H:i:s', $eventTimeZone) : '';
				}
			}


			// Period Check
			$weekdays = array();

			if (isset($item->weekdays))
			{
				$weekdays = ! is_array($item->weekdays) ? explode(",", $item->weekdays) : $item->weekdays;
			}

			if (count($weekdays) > 0)
			{
				$period_all_dates_array = iCDatePeriod::listDates($item->startdate, $item->enddate);

				$period_array = array();

				foreach ($period_all_dates_array AS $date_in_weekdays)
				{
					$datetime_period_date = JHtml::date($date_in_weekdays, 'Y-m-d H:i', $eventTimeZone);

					if (in_array(date('w', strtotime($datetime_period_date)), $weekdays))
					{
						$period_array[] = $datetime_period_date;
					}
				}

				$upcomingPeriodDates = array_filter(
					$period_array,
					function($date)
					{
						return $date >= JHtml::date('now', 'Y-m-d H:i:s');
					}
				);

				if (count($upcomingPeriodDates) > 0)
				{
					$next_period = min($upcomingPeriodDates);
					$next_period = iCDate::isDate($next_period) ? JHtml::date($next_period, 'Y-m-d H:i:s', $eventTimeZone) : '';
				}
				else
				{
					$last_period = iCDate::isDate($item->startdate) ? $item->startdate : '';
				}
			}
			else
			{
				if ($item->startdate >= JHtml::date('now', 'Y-m-d H:i:s'))
				{
					$next_period = iCDate::isDate($item->startdate) ? $item->startdate : '';
				}
				elseif ($item->enddate >= JHtml::date('now', 'Y-m-d H:i:s'))
				{
					$next_period = iCDate::isDate($item->startdate) ? $item->startdate : '';
				}
				else
				{
					$last_period = iCDate::isDate($item->startdate) ? $item->startdate : '';
				}
			}


			// Return Next
			if ($next_singleDates && $next_period)
			{
				$next = min($next_singleDates, $next_period);
			}
			elseif ($next_singleDates)
			{
				$next = $next_singleDates;
			}
			elseif ($next_period)
			{
				$next = $next_period;
			}
			else
			{
				$next = max($last_singleDates, $last_period);
			}

			if ((isset($item->next) && $item->next != $next)
				|| (! isset($item->next) && isset($item->id)))
			{
				$item->next = $next;

				$db	   = Jfactory::getDbo();
				$query = $db->getQuery(true);

				$query->update('#__icagenda_events')
					->set($db->qn('next') . ' = ' . $db->q(JHtml::date($next, 'Y-m-d H:i:s', $eventTimeZone)))
					->where($db->qn('id') . ' = ' . (int) $item->id);

				$db->setQuery($query);
				$db->execute($query);
			}
			else
			{
				$item->next = $next;
			}
		}

		return $item->next;
	}

	/**
	 * Get Current Date of an event.
	 * 
	 * @param   \stdClass[]  &$item          The event object.
	 * @param   string       $eventTimeZone  The event time zone.
	 * 
	 * @return  Datetime
	 *
	 * @since   3.8.10
	 */
	public static function currentDate($item, $eventTimeZone = null)
	{
		$currentDate = $singleDate = $pastDate = $period = $pastPeriod ='';

		// Single Dates Control
		$singleDates = iCString::isSerialized($item->dates) ? unserialize($item->dates) : array();

		$upcomingSingleDates = array_filter(
			$singleDates,
			function($date)
			{
				return $date >= JHtml::date('now', 'Y-m-d H:i:s');
			}
		);

		if (\count($upcomingSingleDates) > 0)
		{
			$singleDate = min($upcomingSingleDates);
			$singleDate = iCDate::isDate($singleDate) ? JHtml::date($singleDate, 'Y-m-d H:i:s', $eventTimeZone) : '';
		}
		else
		{
			$pastSingleDates = array_filter(
				$singleDates,
				function ($date)
				{
					return $date < JHtml::date('now', 'Y-m-d H:i:s');
				}
			);

			if (count($pastSingleDates) > 0)
			{
				$pastDate = max($pastSingleDates);
				$pastDate = iCDate::isDate($pastDate) ? JHtml::date($pastDate, 'Y-m-d H:i:s', $eventTimeZone) : '';
			}
		}


		// Period Control
		$weekdays = array();

		if (isset($item->weekdays) && $item->weekdays)
		{
			$weekdays = ! \is_array($item->weekdays) ? explode(",", $item->weekdays) : $item->weekdays;
		}

		if (\count($weekdays) > 0)
		{
			$period_all_dates_array = iCDatePeriod::listDates($item->startdate, $item->enddate);

			$period_array = array();

			foreach ($period_all_dates_array AS $date_in_weekdays)
			{
				$datetime_period_date = JHtml::date($date_in_weekdays, 'Y-m-d H:i', $eventTimeZone);

				if (\in_array(date('w', strtotime($datetime_period_date)), $weekdays))
				{
					$period_array[] = $datetime_period_date;
				}
			}

			$upcomingPeriodDates = array_filter(
				$period_array,
				function($date)
				{
					return $date >= JHtml::date('now', 'Y-m-d H:i:s');
				}
			);

			if (\count($upcomingPeriodDates) > 0)
			{
				$period = min($upcomingPeriodDates);
				$period = iCDate::isDate($period) ? JHtml::date($period, 'Y-m-d H:i:s', $eventTimeZone) : '';
			}
			else
			{
				$pastPeriod = iCDate::isDate($item->startdate) ? $item->startdate : '';
			}
		}
		else
		{
			if ($item->startdate >= JHtml::date('now', 'Y-m-d H:i:s'))
			{
				// Upcoming Period
				$period = iCDate::isDate($item->startdate) ? $item->startdate : '';
			}
			elseif ($item->enddate >= JHtml::date('now', 'Y-m-d H:i:s'))
			{
				// Current Period
				$period = JHtml::date('now', 'Y-m-d H:i:s');
			}
			else
			{
				// Past Period
				$pastPeriod = iCDate::isDate($item->startdate) ? $item->startdate : '';
			}
		}


		// Return Current Date
		if ($singleDate && $period)
		{
			$currentDate = min($singleDate, $period);
		}
		elseif ($singleDate)
		{
			$currentDate = $singleDate;
		}
		elseif ($period)
		{
			$currentDate = $period;
		}
		else
		{
			$currentDate = max($pastDate, $pastPeriod);
		}

		return $currentDate;
	}

	/**
	 * Function to get Event Params
	 *
	 * @return  JRegistry Event Params
	 *
	 * @since   3.6.0
	 */
	public static function evtParams($params)
	{
		$evtParams = new JRegistry($params);

		return $evtParams;
	}

	/**
	 * Function to get Event Params
	 *
	 * @param   integer  $id  Event id
	 *
	 * @return  JRegistry Event Params
	 *
	 * @since   3.6.5
	 */
	public static function getParams($id = null)
	{
		$db = JFactory::getDbo();

		$query = $db->getQuery(true)
			->select('e.params')
			->from($db->qn('#__icagenda_events', 'e'))
			->where($db->qn('e.id') . ' = ' . (int) $id);

		$db->setQuery($query);
		$result = $db->loadResult();

		$params = json_decode((string) $result, true);

		$eventParams = new JRegistry($params);

		return $eventParams;
	}

	/**
	 * Function to get Event Params merged with Global
	 *
	 * @param   integer  $id  Event id
	 *
	 * @return  JRegistry Event Params
	 *
	 * @since   3.8.0
	 */
	public static function getMergedParams($id = null)
	{
		$db = JFactory::getDbo();

		$query = $db->getQuery(true)
			->select('e.params')
			->from($db->qn('#__icagenda_events', 'e'))
			->where($db->qn('e.id') . ' = ' . (int) $id);

		$db->setQuery($query);
		$result = $db->loadResult();

		// Convert parameter fields to objects.
		$registry = new Registry;
		$registry->loadString($result);

		// Merge Event params to app params
		$eventMergedParams = clone JComponentHelper::getParams('com_icagenda');
		$eventMergedParams->merge($registry);

		return $eventMergedParams;
	}

	/**
	 * Function to return the back arrow button (No item needed)
	 *
	 * @return  HTML
	 *
	 * @since   3.6.0
	 */
	public static function backArrow($item = null)
	{
		$app    = JFactory::getApplication();
		$jinput = $app->input;
		$view   = $jinput->get('view');

		// Get Current Itemid
		$this_itemid = $jinput->getInt('Itemid', 0);

		// TODO: Remove jlayout control (3.6)
		$jlayout       = $jinput->get('layout', '');
		$layouts_array = array('event', 'registration');
		$layout        = in_array($jlayout, $layouts_array) ? $jlayout : '';

		$manageraction = $jinput->get('manageraction', '');
		$referer       = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';

		// RTL css if site language is RTL
		$lang      = JFactory::getLanguage();
		$back_icon = ($lang->isRTL()) ? 'iCicon iCicon-nextic' : 'iCicon iCicon-backic';

		if ( ($layout != '' || $view == 'event')
			&& strpos($referer,'registration') === false
			&& strpos($referer,'event_edit') === false
			&& ! $manageraction)
		{
			if ($referer != "")
			{
				$BackArrow = '<div class="ic-back"><a class="iCtip" href="' . str_replace(array('"', '<', '>', "'"), '', $referer) .'" title="' . JText::_('COM_ICAGENDA_BACK') . '"><span class="' . $back_icon . '"></span> <span class="small">' . JText::_('COM_ICAGENDA_BACK') .'</span></a></div>';
			}
			else
			{
				$BackArrow = '';
			}
		}
		elseif ($manageraction || strpos($referer,'registration') !== false || strpos($referer,'event_edit') !== false)
		{
			$BackArrow = '<div class="ic-back"><a class="iCtip" href="' . JRoute::_('index.php?option=com_icagenda&Itemid=' . (int) $this_itemid) .'" title="' . JText::_('COM_ICAGENDA_BACK') . '"><span class="' . $back_icon . '"></span> <span class="small">' . JText::_('COM_ICAGENDA_BACK') . '</span></a></div>';
		}
		else
		{
			$BackArrow = '';
		}

		// Header Message if event cancelled
		$evtParams = self::getParams($item->id);
		$cancellation_info = '';

		if ($evtParams->get('event_cancelled'))
		{
			$text        = JText::_('COM_ICAGENDA_EVENT_CANCELLED_TEXT');
			$description = $evtParams->get('event_cancelled_desc');

			if ($evtParams->get('event_cancelled_customlabel'))
			{
				$text  = JText::_($evtParams->get('event_cancelled_customlabel_text', 'COM_ICAGENDA_EVENT_CANCELLED_TEXT'));
//				$class = $evtParams->get('event_cancelled_customlabel_css', $class);
			}

			$cancellation_info = '<br /><div class="ic-event-cancelled-alert alert alert-danger">'
								. '<h2 class="alert-heading">' . $text . '</h2>'
								. '<div class="alert-message">' . $description . '</div>'
								. '</div>';
		}

		return $BackArrow . $cancellation_info;
	}

	/**
	 * Event View URL
	 *
	 * @since   3.6.0
	 */
	public static function url($id, $alias = null, $Itemid = null, $vars = array())
	{
		$app = JFactory::getApplication();
		$lang = JFactory::getLanguage();
		$menu = $app->getMenu();

		// Set the event slug
		$event_slug = $alias ? (int) $id . ':' . $alias : (int) $id;

		$extra = '';

		if (is_array($vars))
		{
			foreach ($vars as $var => $value)
			{
				if ($value)
				{
					$extra.= '&' . $var . '=' . $value;
				}
			}
		}

		// Look for the home menu
		if (Multilanguage::isEnabled())
		{
			$home = $menu->getDefault($lang->getTag());
		}
		else
		{
			$home  = $menu->getDefault();
		}

		if ((int) $Itemid === $home->id)
		{
			$ItemidSegment = ($home->link == 'index.php?option=com_icagenda&view=events')
							? '&Itemid=' . $Itemid
							: '';
		}
		elseif ((int) $Itemid > 0)
		{
			$ItemidSegment = '&Itemid=' . $Itemid;
		}
		else
		{
			$ItemidSegment = '&Itemid=' . $app->input->getInt('Itemid', 0);
		}

		$url = JRoute::_('index.php?option=com_icagenda&view=event&id=' . $event_slug . $ItemidSegment . $extra);

		return $url;
	}

	/**
	 * Title + Manager Icons
	 *
	 * @since   3.6.0
	 * @todo    Review and split into sub-functions
	 */
	public static function titleBar($i)
	{
		$jinput = JFactory::getApplication()->input;

		$this_itemid       = $jinput->getInt('Itemid');
		$list_title_length = (int) JComponentHelper::getParams('com_icagenda')->get('list_title_length', '');

		$i_title = icagendaRender::titleToFormat($i->title);

		$jlayout       = $jinput->get('layout', '');
		$layouts_array = array('event', 'registration');
		$layout        = in_array($jlayout, $layouts_array) ? $jlayout : '';

		$mbString = extension_loaded('mbstring');

		$title_length = $mbString ? mb_strlen($i_title, 'UTF-8') : strlen($i_title);

		if (empty($layout)
			&& ! empty($list_title_length))
		{
			$title  = $mbString
					? trim(mb_substr($i_title, 0, $list_title_length, 'UTF-8'))
					: trim(substr($i_title, 0, $list_title_length));

			$new_title_length = $mbString ? mb_strlen($title, 'UTF-8') : strlen($title);

			if ($new_title_length < $title_length)
			{
				$title.= '...';
			}
		}
		else
		{
			$title = $i_title;
		}

		$approval = $i->approval;

		$event_slug = empty($i->alias) ? $i->id : $i->id . ':' . $i->alias;

		// Set Manager Actions Url
		$managerActionsURL = 'index.php?option=com_icagenda&view=event&id=' . $event_slug . '&Itemid=' . $this_itemid;

		$unapproved = '<a class="iCtip" href="' . JRoute::_($managerActionsURL) . '" title="' . JText::_('COM_ICAGENDA_APPROVE_AN_EVENT_LBL') . '"><small><span class="iCicon-open-details"></span></small></a>';

		$titleBar = '';

		if ($title != NULL && $approval == 1)
		{
			$titleBar.= $title . ' ' . $unapproved;
		}
		elseif ($title != NULL && $approval != 1)
		{
			$titleBar.= $title;
		}

		$eventIsCancelled = self::cancelledButton($i->id, 'btn btn-danger');

		if ($eventIsCancelled)
		{
			$titleBar.= ' ' . $eventIsCancelled;
		}

		return $titleBar;
	}

	/**
	 * Return Event Cancelled button with infotip
	 *
	 * @since   3.7.12
	 */
	public static function cancelledButton($id, $class = 'label label-important')
	{
		$evtParams = self::getParams($id);

		if ($evtParams->get('event_cancelled'))
		{
			$language = JFactory::getLanguage();
			$language->load('com_icagenda', JPATH_SITE, 'en-GB', false, true);
			$language->load('com_icagenda', JPATH_SITE, null, true);

			$text        = JText::_('COM_ICAGENDA_EVENT_CANCELLED_TEXT');
			$description = $evtParams->get('event_cancelled_desc');

			if ($evtParams->get('event_cancelled_customlabel'))
			{
				$text  = JText::_($evtParams->get('event_cancelled_customlabel_text', 'COM_ICAGENDA_EVENT_CANCELLED_TEXT'));
				$class = $evtParams->get('event_cancelled_customlabel_css', $class);
			}

			$title = $tipclass = $position = '';

			if (!empty($description))
			{
				if ($text && $text !== $description)
				{
					JHtml::_('bootstrap.popover');
					$tipclass = ' hasPopover';
					$title    = ' title="' . htmlspecialchars(trim($text, ':')) . '"'
						. ' data-content="'. htmlspecialchars($description) . '"';

					if (!$position && JFactory::getLanguage()->isRtl())
					{
						$position = ' data-placement="left" ';
					}
				}
				else
				{
					JHtml::_('bootstrap.tooltip');
					$tipclass = ' hasTooltip';
					$title    = ' title="' . JHtml::_('tooltipText', trim($text, ':'), JText::_($description), 0) . '"';
					$position = 'top';
				}
			}

			$cancelledButton = '<span class="ic-event-cancelled ' . $class . $tipclass . '"' . $title . $position . '>' . $text . '</span>';

			return $cancelledButton;
		}

		return false;
	}

	/**
	 * Next Date Text
	 *
	 * @since   3.6.0
	 */
	public static function dateText($i)
	{
		$eventTimeZone = null;

		$dates         = iCString::isSerialized($i->dates) ? unserialize($i->dates) : array(); // returns array
		$period        = iCString::isSerialized($i->period) ? unserialize($i->period) : array(); // returns array
		$weekdays      = $i->weekdays;
		$startdatetime = iCDate::isDate($i->startdate) ? date('Y-m-d H:i', strtotime($i->startdate)) : '';

		$site_date     = JHtml::date('now', 'Y-m-d');
		$site_datetime = JHtml::date('now', 'Y-m-d H:i');

		$alldates_array = array_merge($dates, $period);
 		$alldates       = array_filter($alldates_array, function($var) {return $var == iCDate::isDate($var);});

		$next_date     = date('Y-m-d', strtotime($i->next));
		$next_datetime = date('Y-m-d H:i', strtotime($i->next));

		$next_is_in_period = in_array($next_datetime, $period) ? true : false;

		$totDates = count($alldates);

		if ($totDates > 1
			&& $next_date > $site_date)
		{
			rsort($alldates);

			$last_date = JHtml::date($alldates[0], 'Y-m-d', $eventTimeZone);

			if ( ! $next_is_in_period
				&& $last_date == $next_date)
			{
				$dateText = JText::_('COM_ICAGENDA_EVENT_DATE_LAST');
			}
			elseif ( ! $next_is_in_period)
			{
				$dateText = JText::_('COM_ICAGENDA_EVENT_DATE_FUTUR');
			}
			elseif ($next_is_in_period
				&& $weekdays == NULL)
			{
				$dateText = JText::_('COM_ICAGENDA_LEGEND_DATES');
			}
			else
			{
				$dateText = JText::_('COM_ICAGENDA_EVENT_DATE');
			}
		}
		elseif ($totDates > 1
			&& $next_date < $site_date)
		{
			if ($totDates == 2)
			{
				$dateText   = $next_is_in_period
							? JText::_('COM_ICAGENDA_EVENT_DATE')
							: JText::_('COM_ICAGENDA_EVENT_DATE_PAST');
			}
			else
			{
				$dateText   = ($next_is_in_period && $weekdays == NULL)
							? JText::_('COM_ICAGENDA_LEGEND_DATES')
							: JText::_('COM_ICAGENDA_EVENT_DATE_PAST');
			}
		}
		elseif ($next_date == $site_date)
		{
			$dateText   = ($next_is_in_period && ($next_datetime < $site_datetime || $next_datetime != $startdatetime))
						? JText::_('COM_ICAGENDA_EVENT_DATE_PERIOD_NOW')
						: JText::_('COM_ICAGENDA_EVENT_DATE_TODAY');
		}
		else
		{
			$dateText = JText::_( 'COM_ICAGENDA_EVENT_DATE' );
		}

		return $dateText;
	}

	/**
	 * Get Next Date (or Last Date)
	 *
	 * @since   3.4.0
	 */
	public static function nextDate($evt, $i)
	{
		$eventTimeZone = null;

		$singledates   = iCString::isSerialized($i->dates) ? unserialize($i->dates) : array(); // returns array
		$period        = iCString::isSerialized($i->period) ? unserialize($i->period) : array(); // returns array
		$startdatetime = $i->startdate;
		$enddatetime   = $i->enddate;
		$weekdays      = $i->weekdays;

		$site_date      = JHtml::date('now', 'Y-m-d');
		$UTC_today_date = JHtml::date('now', 'Y-m-d', $eventTimeZone);

		$next_date     = JHtml::date($evt, 'Y-m-d', $eventTimeZone);
		$next_datetime = JHtml::date($evt, 'Y-m-d H:i', $eventTimeZone);

		$start_date = JHtml::date($i->startdate, 'Y-m-d', $eventTimeZone);
		$end_date   = JHtml::date($i->enddate, 'Y-m-d', $eventTimeZone);

		// Check if date from a period with weekdays has end time of the period set in next.
//		$time_next_datetime = JHtml::date($next_datetime, 'H:i', $eventTimeZone);
		$time_next_datetime = date('H:i', strtotime($next_datetime));
		$time_startdate     = JHtml::date($i->startdate, 'H:i', $eventTimeZone);
		$time_enddate       = JHtml::date($i->enddate, 'H:i', $eventTimeZone);

		$data_next_datetime = date('Y-m-d H:i', strtotime($evt));

		if ($next_date == $site_date
			&& $time_next_datetime == $time_enddate)
		{
			$next_datetime = $next_date . ' ' . $time_startdate;
		}

		if ($period != NULL
			&& in_array($data_next_datetime, $period))
		{
			$next_is_in_period = true;
		}
		elseif ($i->period == ''
			&& ! in_array($data_next_datetime, $singledates))
		{
			$next_is_in_period = true;
		}
		else
		{
			$next_is_in_period = false;
		}

		// Highlight event in progress
		if ($next_date == $site_date)
		{
			$start_span = '<span class="ic-next-today">';
			$end_span   = '</span>';
		}
		else
		{
			$start_span = $end_span = '';
		}

		$separator = '<span class="ic-datetime-separator"> - </span>';

		// Format Next Date
		if ($next_is_in_period
			&& ($start_date == $end_date || $weekdays != null))
		{
			// Next in the period & (same start/end date OR one or more weekday selected)
			$nextDate = $start_span;
			$nextDate.= '<span class="ic-single-startdate">';
			$nextDate.= icagendaRender::dateToFormat($evt);
			$nextDate.= '</span>';

			if ($i->displaytime == 1)
			{
				$nextDate.= ' <span class="ic-single-starttime">' . icagendaRender::dateToTime($i->startdate) . '</span>';

				if (icagendaRender::dateToTime($i->startdate) != icagendaRender::dateToTime($i->enddate))
				{
					$nextDate.= $separator . '<span class="ic-single-endtime">' . icagendaRender::dateToTime($i->enddate) . '</span>';
				}
			}

			$nextDate.= $end_span;
		}
		elseif ($next_is_in_period
			&& ($weekdays == null))
		{
			// Next in the period & different start/end date & no weekday selected
			$start = '<span class="ic-period-startdate">';
			$start.= icagendaRender::dateToFormat($i->startdate);
			$start.= '</span>';

			$end = '<span class="ic-period-enddate">';
			$end.= icagendaRender::dateToFormat($i->enddate);
			$end.= '</span>';

			if ($i->displaytime == 1)
			{
				$start.= ' <span class="ic-period-starttime">' . icagendaRender::dateToTime($i->startdate) . '</span>';

				$end.= ' <span class="ic-period-endtime">' . icagendaRender::dateToTime($i->enddate) . '</span>';
			}

			$nextDate = $start_span . $start . $separator . $end . $end_span;
		}
		else
		{
			// Next is a single date
			$nextDate = $start_span;
			$nextDate.= '<span class="ic-single-next">';
			$nextDate.= icagendaRender::dateToFormat($evt);
			$nextDate.= '</span>';

			if ($i->displaytime == 1)
			{
				$nextDate.= ' <span class="ic-single-starttime">' . icagendaRender::dateToTime($evt) . '</span>';
			}

			$nextDate.= $end_span;
		}

		return $nextDate;
	}

	/*
	 * Function to detect if info details exist in an event,
	 * and to hide or show it depending of Options (display and access levels)
	 *
	 * @since   3.6.0
	 */
	public static function infoDetails($item, $CUSTOM_FIELDS)
	{
		// Hide/Show Option
		$infoDetails = JComponentHelper::getParams('com_icagenda')->get('infoDetails', 1);

		// Access Levels Option
		$accessInfoDetails = JComponentHelper::getParams('com_icagenda')->get('accessInfoDetails', 1);

		if ( ($infoDetails == 1 && icagendaEvents::accessLevels($accessInfoDetails))
			&& ( ($item->params->get('statutReg', '') == '1' && $item->params->get('maxReg'))
				|| $item->phone
				|| $item->email
				|| $item->website
				|| $item->address
				|| $item->file
				|| $CUSTOM_FIELDS )
			)
		{
			return true;
		}

		return false;
	}

	/*
	 * Function to return a list of all single dates, HTML formatted.
	 * TO BE REFACTORED
	 *
	 * @since   3.6.0
	 */
	public static function displayListSingleDates($item)
	{
		$iCparams   = JComponentHelper::getParams('com_icagenda');
		$timeformat = JFactory::getApplication()->getParams()->get('timeformat', 1);

		// Hide/Show Option
		$SingleDates = $iCparams->get('SingleDates', 1);

		// Access Levels Option (to be checked!)
//		$accessSingleDates = $iCparams->get('accessSingleDates', 1);

		// Order by Dates
		$SingleDatesOrder = $iCparams->get('SingleDatesOrder', 1);

		// List Model
		$SingleDatesListModel = $iCparams->get('SingleDatesListModel', 1);

		if ($SingleDates == 1)
		{
//			if ($this->accessLevels($accessSingleDates))
//			{
				$days = iCString::isSerialized($item->dates) ? unserialize($item->dates) : array(); // returns array

				if ($SingleDatesOrder == 1)
				{
					rsort($days);
				}
				elseif ($SingleDatesOrder == 2)
				{
					sort($days);
				}

				$totDates = count($days);

				if ($timeformat == 1)
				{
					$lang_time = 'H:i';
				}
				else
				{
					$lang_time = 'h:i A';
				}

				// Detect if Singles Dates, and no single date with null value
				$displayDates = false;
				$nbDays       = count($days);

				foreach ($days as $k => $d)
				{
					if (iCDate::isDate($d) && $nbDays != 0)
					{
						$displayDates = true;
					}
				}

				$daysUl = '';

				if ($displayDates)
				{
					if ($SingleDatesListModel == '2')
					{
						$n = 0;
						$daysUl.= '<div class="alldates"><i>' . JText::_('COM_ICAGENDA_LEGEND_DATES') . ': </i>';

						foreach ($days as $k => $d)
						{
							$n  = $n+1;
							$fd = icagendaRender::dateToFormat($d);

							$timeDate   = ($item->displaytime == 1)
										? ' <span class="evttime">' . date($lang_time, strtotime($d)) . '</span>'
										: '';

							if ($n <= ($totDates-1))
							{
								$daysUl.= '<span class="alldates">' . $fd . $timeDate . '</span> - ';
							}
							elseif ($n == $totDates)
							{
	   							$daysUl.= '<span class="alldates">' . $fd . $timeDate . '</span>';
							}
						}

						$daysUl.= '</div>';
					}
					else
					{
						$daysUl.= '<ul class="alldates">';

						foreach ($days as $k => $d)
						{
							$fd = icagendaRender::dateToFormat($d);

							$timeDate   = ($item->displaytime == 1)
										? ' <span class="evttime">' . date($lang_time, strtotime($d)) . '</span>'
										: '';

							$daysUl.= '<li class="alldates">' . $fd . $timeDate . '</li>';
						}

						$daysUl.= '</ul>';
					}
				}

				if ($totDates > '0')
				{
					return $daysUl;
				}
				else
				{
					return false;
				}
//			}
//			else
//			{
//				return false;
//			}
		}
		else
		{
			return false;
		}
	}

	/*
	 * Function to display the period text width formatted dates (eg. from 00-00-0000 to 00-00-0000).
	 * TO BE REFACTORED
	 * @TODO remove inline style html tags (check css and add class declarations)
	 * @TODO (3.7) remove old deprecated class names
	 *
	 * @since   3.6.0
	 */
	public static function displayPeriodDates($item)
	{
		$iCparams = JComponentHelper::getParams('com_icagenda');

		// Hide/Show Option
		$PeriodDates = $iCparams->get('PeriodDates', 1);

		// List Model
		$SingleDatesListModel = $iCparams->get('SingleDatesListModel', 1);

		// First day of the week
		$firstday_week_global = $iCparams->get('firstday_week_global', 1);

		// Predefined variables
		$wdays = $showDays = $timeOneDay = $end = '';

		// WeekDays
		$weekdays    = $item->weekdays;
		$weekdaysall = empty($weekdays) ? true : false;

		if ($firstday_week_global == '1')
		{
			$weekdays_array = explode (',', $weekdays);

			if (in_array('0', $weekdays_array))
			{
				$weekdays = str_replace('0', '', $weekdays);
				$weekdays = $weekdays . ',7';
			}
		}

		if ( ! $weekdaysall)
		{
			$weekdays_array = explode (',', $weekdays);
			$wdaysArray     = array();

			foreach ($weekdays_array AS $wd)
			{
				if ($firstday_week_global != '1')
				{
					if ($wd == 0) $wdaysArray[] = JText::_('SUNDAY');
				}
				if ($wd == 1) $wdaysArray[] = JText::_('MONDAY');
				if ($wd == 2) $wdaysArray[] = JText::_('TUESDAY');
				if ($wd == 3) $wdaysArray[] = JText::_('WEDNESDAY');
				if ($wd == 4) $wdaysArray[] = JText::_('THURSDAY');
				if ($wd == 5) $wdaysArray[] = JText::_('FRIDAY');
				if ($wd == 6) $wdaysArray[] = JText::_('SATURDAY');
				if ($firstday_week_global == '1')
				{
					if ($wd == 7) $wdaysArray[] = JText::_('SUNDAY');
				}
			}

			$last  = array_slice($wdaysArray, -1);
			$first = join(', ', array_slice($wdaysArray, 0, -1));
			$both  = array_filter(array_merge(array($first), $last));

			// RTL css if site language is RTL
			$lang       = JFactory::getLanguage();
			$arrow_list = $lang->isRTL() ? '&#8629;' : '&#8627;';

			$wdays = $arrow_list . ' <small><i><span class="ic-period-weekdays">' . join(' & ', $both) . '</span></i></small>';
		}

		if ($PeriodDates == 1
			&& self::eventHasPeriod($item->period, $item->startdate, $item->enddate)
			)
		{
			$startDate = icagendaRender::dateToFormat($item->startdate);
			$endDate   = icagendaRender::dateToFormat($item->enddate);
			$startTime = icagendaRender::dateToTime($item->startdate);
			$endTime   = icagendaRender::dateToTime($item->enddate);

			if ($startDate == $endDate)
			{
				$start = '<span class="ic-period-startdate">';
				$start.= $startDate;
				$start.= '</span>';

				if ($item->displaytime == 1)
				{
					$timeOneDay = '<span class="evttime ic-period-time">' . $startTime;
					$timeOneDay.= ($startTime !== $endTime) ? ' - ' . $endTime : '';
					$timeOneDay.= '</span>';
				}
			}
			else
			{
				$start = '<span class="ic-period-text-from">'
						. ucfirst(JText::_('COM_ICAGENDA_PERIOD_FROM'))
						. '</span> ';
				$start.= '<span class="ic-period-startdate">'
						. $startDate
						. '</span>';

				if ($item->displaytime == 1)
				{
					$start.= ' <span class="evttime ic-period-starttime">'
							. $startTime
							. '</span>';
				}

				$end = '<span class="ic-period-text-to">'
						. JText::_('COM_ICAGENDA_PERIOD_TO')
						. '</span> ';
				$end.= '<span class="ic-period-enddate">'
						. $endDate
						. '</span>';

				if ($item->displaytime == 1)
				{
					$end.= ' <span class="evttime ic-period-endtime">'
							. $endTime
							. '</span>';
				}

				$showDays = $wdays;
			}

			// Horizontal List
			if ($SingleDatesListModel == 2)
			{
				$period = '<div class="ic-date-horizontal">' . JText::_('COM_ICAGENDA_EVENT_PERIOD') . ': ';
				$period.= $start . ' ' . $end . ' ' . $timeOneDay;

				if ( ! empty($showDays))
				{
					$period.= '<br /><span style="margin-left:30px">' . $showDays . '</span>';
				}

				$period.= '</div>';
			}

			// Vertical List
			else
			{
				$period = '<ul class="ic-date-vertical"><li>';
				$period.= $start . ' ' . $end . ' ' . $timeOneDay;

				if ( ! empty($showDays))
				{
					$period.= '<br/>' . $showDays;
				}

				$period.= '</li></ul>';
			}

			return $period;
		}
		else
		{
			return false;
		}
	}

	/*
	 * Function to check if period dates exist for this event
	 *
	 * @since   3.6.0
	 */
	public static function eventHasPeriod($period, $startdate, $enddate)
	{
		$period_dates = iCString::isSerialized($period) ? unserialize($period) : array(); // returns array

		if (count($period_dates) > 0
			&& iCDate::isDate($startdate)
			&& iCDate::isDate($enddate))
		{
			return true;
		}

		return false;
	}

	/*
	 * Function to check if period is not finished
	 *
	 * @since   3.6.0
	 */
	public static function periodIsNotFinished($enddate)
	{
		$eventTimeZone    = null;
		$datetime_today   = JHtml::date('now', 'Y-m-d H:i');
		$datetime_enddate = JHtml::date($enddate, 'Y-m-d H:i', $eventTimeZone);

		if (strtotime($datetime_enddate) > strtotime($datetime_today))
		{
			return true;
		}

		return false;
	}

	/*
	 * Function to set Meta-title for an event
	 *
	 * @since   3.6.0
	 */
	public static function setMetaTitle($item)
	{
		$limit     = '60';
		$metaTitle = iCFilterOutput::fullCleanHTML($item->title);

		if (strlen($metaTitle) > $limit)
		{
			$string_cut = substr($metaTitle, 0, $limit);
			$last_space = strrpos($string_cut, ' ');
			$string_ok  = substr($string_cut, 0, $last_space);
			$metaTitle  = $string_ok;
		}

		return $metaTitle;
	}

	/*
	 * Function to set Meta-description for an event
	 *
	 * @since   3.6.0
	 */
	public static function setMetaDesc($item)
	{
		$iCparams = JComponentHelper::getParams('com_icagenda');
		$limit    = $iCparams->get('char_limit_meta_description', '320');

		$metaDesc = iCFilterOutput::fullCleanHTML($item->metadesc);
		$metaDesc = (empty($metaDesc)) ? iCFilterOutput::fullCleanHTML($item->desc) : $metaDesc;

		if (strlen($metaDesc) > $limit)
		{
			$string_cut = substr($metaDesc, 0, $limit);
			$last_space = strrpos($string_cut, ' ');
			$string_ok  = substr($string_cut, 0, $last_space);
			$metaDesc   = $string_ok;
		}

		return $metaDesc;
	}

	/*
	 * Function to return event Url
	 *
	 * @since   3.6.0
	 */
	public static function eventURL($i)
	{
		$app    = JFactory::getApplication();
		$jinput = $app->input;

		$itemID = $jinput->get('Itemid', '0');

		$eventnumber = $i->id;
		$event_slug  = empty($i->alias) ? $i->id : $i->id . ':' . $i->alias;
		$date        = $i->next;

		// Get the "event" URL
		$baseURL    = JURI::base();
		$subpathURL = JURI::base(true);

		$baseURL    = str_replace('/administrator', '', $baseURL);
		$subpathURL = str_replace('/administrator', '', $subpathURL);

		$urlevent = str_replace('&amp;','&', JRoute::_('index.php?option=com_icagenda&view=event&Itemid=' . (int) $itemID . '&id=' . $event_slug));

		// Sub Path filtering
		$subpathURL = ltrim($subpathURL, '/');

		// URL Event Details filtering
		$urlevent = ltrim($urlevent, '/');

		if (substr($urlevent, 0, strlen($subpathURL)+1) == "$subpathURL/")
		{
			$urlevent = substr($urlevent, strlen($subpathURL)+1);
		}

		$urlevent = rtrim($baseURL,'/') . '/' . ltrim($urlevent,'/');

		$url = $urlevent;

		if (is_numeric($itemID) && is_numeric($eventnumber)
			&& ! is_array($itemID) && ! is_array($eventnumber)
			)
		{
			return $url;
		}
		else
		{
			$url = JRoute::_('index.php');

			return JURI::base() . $url;
		}
	}

	/*
	 * Function to convert a datetime to URL alias
	 * (see iCDate::dateToAlias from iC Library for general function)
	 *
	 * @since   3.6.0
	 *
	 * @todo    3.8.0 (add &date= to this function to generate full date var)
	 */
	public static function urlDateVar($datetime)
	{
		if ( ! iCDate::isDate($datetime)) return false;

		return '&date=' . iCDate::dateToAlias($datetime, 'Y-m-d-H-i');
	}

	/*
	 * Function to convert a URL date alias in an SQL datetime string.
	 *
	 * @return  string  The date string in SQL datetime format.
	 *
	 * @since   3.6.0
	 */
	public static function convertDateAliasToSQLDatetime($dateAlias)
	{
		if (strlen(iCDate::dateToNumeric($dateAlias)) != '12') return '';

		$ex         = explode('-', $dateAlias);
		$datetime   = (count($ex) == 5)
					? $ex['0'] . '-' . $ex['1'] . '-' . $ex['2'] . ' ' . $ex['3'] . ':' . $ex['4'] . ':00'
					: '';

		return $datetime;
	}

	/*
	 * Function to generate the read more for introduction description
	 *
	 * @since   3.6.0
	 */
	public static function readMore ($url, $desc, $content = '')
	{
		$iCparams    = JComponentHelper::getParams('com_icagenda');
		$limitGlobal = $iCparams->get('limitGlobal', 0);

		if ($limitGlobal == 1)
		{
			$limit = $iCparams->get('ShortDescLimit', '100');
		}
		elseif ($limitGlobal == 0)
		{
			$customlimit = $iCparams->get('limit', '100');

			$limit = is_numeric($customlimit) ? $customlimit : $iCparams->get('ShortDescLimit', '100');
		}

		$limit = is_numeric($limit) ? $limit : '1';

		$readmore = '';

		$readmore = ($limit <= 1) ? '' : $content;
		$text     = preg_replace('/<img[^>]*>/Ui', '', $desc);

		if (strlen($text) > $limit)
		{
			$string_cut = substr($text, 0, $limit);
			$last_space = strrpos($string_cut, ' ');
			$string_ok  = substr($string_cut, 0, $last_space);
			$text       = $string_ok . ' ';
			$url        = $url;
			$text       = '<a href="' . $url . '" class="more">' . $readmore . '</a>';
		}
		else
		{
			$text = '';
		}

		return $text;
	}

	/**
	 * Loads the list of filled custom fields for this event
	 *
	 * @return  array
	 *
	 * @since   3.6.0
	 */
	public static function getCustomFields($id = null)
	{
		$customFields = icagendaEventData::loadEventCustomFields($id);

		foreach ($customFields as $cf)
		{
			if ($cf->title && $cf->value)
			{
				switch ($cf->type)
				{
					case 'url':
						$cf->value = iCRender::urlTag($cf->value);
						break;

					case 'email':
						$cf->value = JHtml::_('email.cloak', $cf->value);
						break;

					default:
						$cf->value = JText::_($cf->value);
						break;
				}
			}

			$cf->title = JText::_($cf->title);
		}

		return $customFields;
	}

	/**
	 * Function to get custom field groups of an event
	 *
	 * @param   integer  $id  Event id
	 *
	 * @return  array
	 *
	 * @since   3.6.0
	 */
	public static function getCustomfieldGroups($id = null)
	{
		// Create a new query object.
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);

		$query->select('e.params')
			->from('#__icagenda_events AS e')
			->where($db->qn('id') . ' = ' . $db->q($id));
		$db->setQuery($query);

		$result = json_decode($db->loadResult());

		if (isset($result->custom_form))
		{
			return $result->custom_form;
		}

		return false;
	}
}
