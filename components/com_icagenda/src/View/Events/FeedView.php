<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.9.8 2024-12-18
 *
 * @package     iCagenda.Site
 * @link        https://www.icagenda.com
 *
 * @author      Cyril Rezé
 * @copyright   (c) 2012-2024 Cyril Rezé / iCagenda. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 *
 * @since       3.8.13
 *----------------------------------------------------------------------------
*/

namespace WebiC\Component\iCagenda\Site\View\Events;

use iCutilities\Events\EventsData as icagendaEventsData;
use iCutilities\Render\Render as icagendaRender;
use iCutilities\Thumb\Thumb as icagendaThumb;
use Joomla\CMS\Date\Date;
use Joomla\CMS\Document\Feed\FeedEnclosure;
use Joomla\CMS\Document\Feed\FeedItem;
use Joomla\CMS\Factory;
use Joomla\CMS\Filter\InputFilter;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Uri\Uri;

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

/**
 * HTML View class for the iCagenda component
 */
class FeedView extends BaseHtmlView
{
	/**
	 * Execute and display a template script.
	 *
	 * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
	 *
	 * @return  mixed  A string if successful, otherwise an Error object.
	 */
	public function display($tpl = null)
	{
		$app      = Factory::getApplication();
		$document = Factory::getDocument();
		$params   = $app->getParams();
		$Itemid   = $app->input->getInt('Itemid');
		$items    = $this->get('Items');

		$mcatid          = $params->get('mcatid', '');
		$filter_category = ! \is_array($mcatid) ? [$mcatid] : $mcatid;

		// Set events for the current page
		$getAllDates = icagendaEventsData::getAllDates();
		$new_items   = [];
		$evt         = [];

		if (count($getAllDates) > 0) {
			$number = Factory::getConfig()->get('feed_limit', $params->get('number', 5));

			// Set number of events to be displayed per page
			$currentPageDates = \array_slice($getAllDates, '0', $number, true);

			foreach ($currentPageDates as $date_id) {
				// Get id and date for each event to be displayed
				$ex_date_id = explode('_', $date_id);
				$evt[]      = $ex_date_id['0'];
				$evt_id     = $ex_date_id['1'];

				foreach ($items as $item) {
					if ($evt_id == $item->id) {
						$new_items[] = $item;
					}
				}
			}
		}

		$this->document->link = Route::_('index.php?option=com_icagenda&view=events&Itemid=' . $Itemid);

		$items  = $new_items;

		foreach ($items as $k => $item) {
			if ( ! \in_array('', $filter_category) && ! \in_array('0', $filter_category)
				&& \in_array($item->catid, $filter_category)
				|| \in_array('', $filter_category)
				|| \in_array('0', $filter_category)
			) {
				$EVENT_TIME = ($item->displaytime == 1)
							? ' ' . icagendaRender::dateToTime($evt[$k])
							: '';

				// Load individual item creator class.
				$feeditem = new FeedItem();

				$feeditem->title = $item->title;
				$feeditem->link  = Route::_('index.php?option=com_icagenda&view=event&Itemid='
					. (int) $Itemid . '&id=' . (int) $item->id . ':' . $item->alias);

				$imageTag   = '';
				$item_image = '';

				if ($item->image) {
					$img     = HTMLHelper::cleanImageURL($item->image);
					$img_url = $img->url;

					$item_image = icagendaThumb::sizeMedium($img_url);

					$imageTag = '<img src="' . $item_image . '" alt="" style="margin: 5px; float: left;">';
				}

				if ($item_image) {
					$info = getimagesize($item_image);

					$enclosure = new FeedEnclosure;

					$enclosure->url    = Uri::root() . $item_image;
					$enclosure->length = filesize($item_image);
					$enclosure->type   = $info['mime'];

					$feeditem->setEnclosure($enclosure); 
				}

				$feeditem->description = icagendaRender::dateToFormat($evt[$k]) . $EVENT_TIME . '<br />' . $imageTag . $item->description;

				// Set date
				$timezone = self::getTimeZone();
				$date     = new Date($evt[$k], $timezone);
				$date     = $date->format('Y-m-d H:i:s', false, false);

				$feeditem->date     = $date;
				$feeditem->category = $item->cat_title;
//				$feeditem->comments = json_encode(array("location" => $item->place, "city" => $item->city));

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
		$userTz   = Factory::getUser()->get('timezone');
		$timeZone = Factory::getConfig()->get('offset');

		if ($userTz) {
			$timeZone = $userTz;
		}

		return new \DateTimeZone($timeZone);
	}
}
