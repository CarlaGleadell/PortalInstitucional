<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.8.8 2022-07-28
 *
 * @package     iCagenda.Site
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

/**
 * HTML View class - iCagenda - RSS Feeds.
 */
class icagendaViewList extends JViewLegacy
{
	public function display($cachable = false, $urlparams = false)
	{
		$app      = JFactory::getApplication();
		$document = JFactory::getDocument();
		$params   = $app->getParams();
		$Itemid   = $app->input->getInt('Itemid');
		$items    = $this->get('Items');

		$mcatid          = $params->get('mcatid', '');
		$filter_category = ! is_array($mcatid) ? array($mcatid) : $mcatid;

		// Set events for the current page
		$getAllDates = icagendaEventsData::getAllDates();
		$new_items   = array();
		$evt         = array();

		if (count($getAllDates) > 0)
		{
			$number = JFactory::getConfig()->get('feed_limit', $params->get('number', 5));

			// Set number of events to be displayed per page
			$currentPageDates = array_slice($getAllDates, '0', $number, true);

			foreach ($currentPageDates as $date_id)
			{
				// Get id and date for each event to be displayed
				$ex_date_id = explode('_', $date_id);
				$evt[]      = $ex_date_id['0'];
				$evt_id     = $ex_date_id['1'];

				foreach ($items as $item)
				{
					if ($evt_id == $item->id)
					{
						$new_items[] = $item;
					}
				}
			}
		}

		$items  = $new_items;

		foreach ($items as $k => $item)
		{
			if ( ! in_array('', $filter_category) && ! in_array('0', $filter_category)
				&& in_array($item->catid, $filter_category)
				|| in_array('', $filter_category)
				|| in_array('0', $filter_category)
				)
			{
				$EVENT_TIME = ($item->displaytime == 1)
							? ' ' . icagendaRender::dateToTime($evt[$k])
							: '';

				// Load individual item creator class.
				$feeditem               = new JFeedItem;
				$feeditem->title        = $item->title . ' (' . $item->cat_title . ')';
				$feeditem->link         = JROUTE::_('index.php?option=com_icagenda&view=event&Itemid='
											. (int) $Itemid . '&id=' . (int) $item->id . ':' . $item->alias);
				$feeditem->image        = icagendaThumb::sizeMedium($item->image);
				$feeditem->description  = icagendaRender::dateToFormat($evt[$k]) . $EVENT_TIME . '<br /><img src="' . $feeditem->image . '" alt="" style="margin: 5px; float: left;">' . $item->description;

				// Set date
				$timezone = self::getTimeZone();
				$date     = new JDate($evt[$k], $timezone);
				$date     = $date->format('Y-m-d H:i:s', false, false);

				$feeditem->date         = $date;
				$feeditem->category     = $item->cat_title;
//				$feeditem->comments     = json_encode(array("location" => $item->place, "city" => $item->city));

				// Loads item information into RSS array
				$document->addItem($feeditem);
			}
		}
	}

	/**
	 * Returns the userTime zone if the user has set one, or the global config one
	 * @return mixed
	 */
	public function getTimeZone()
	{
		$userTz   = JFactory::getUser()->get('timezone');
		$timeZone = JFactory::getConfig()->get('offset');

		if ($userTz)
		{
			$timeZone = $userTz;
		}

		return new DateTimeZone($timeZone);
	}
}
