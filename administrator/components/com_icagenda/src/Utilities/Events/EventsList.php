<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.8.11 2022-12-19
 *
 * @package     iCagenda.Admin
 * @subpackage  src.Utilities.Events
 * @link        https://www.icagenda.com
 *
 * @author      Cyril Rezé
 * @copyright   (c) 2012-2024 Cyril Rezé / iCagenda. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 *
 * @since       3.6.0
 *----------------------------------------------------------------------------
*/

namespace iCutilities\Events;

\defined('_JEXEC') or die;

use iCutilities\Categories\Categories as icagendaCategories;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;

/**
 * class icagendaList
 */
class EventsList
{
	/**
	 * Function to return iCagenda header (Title/Subtitle) for list of events
	 */
	public static function header($total, $number_per_page, $search = false)
	{
		// loading iCagenda PARAMS
		$app     = Factory::getApplication();
		$jinput  = $app->input;

		$getpage = $jinput->get('page', '1');

		$params  = $app->getParams();

		$time       = $params->get('time', '1');
		$headerList = $params->get('headerList', 1);
		$resetBtn   = '';

		if ($search)
		{
			$filter_category = $jinput->get('filter_category', '');

			if ( ! $params->get('search_filters', '0') && $filter_category)
			{
				$cat_title    = icagendaCategories::getTitle($filter_category);
				$resetBtn     = '<p><a class="btn btn-info btn-sm ic-button-reset-category" href="' . Route::_('index.php?option=com_icagenda&view=events&Itemid=' . $jinput->get('Itemid')) . '">
									' . $cat_title . '&nbsp;&nbsp;&nbsp;<span class="icon-cancel icon-white"></span>
								</a></p>';

				$header_title = Text::_('COM_ICAGENDA_HEADER_SEARCH_TITLE');
				$header_sub   = Text::plural('COM_ICAGENDA_HEADER_SEARCH_FILTER_CATEGORY_N_EVENTS', $total);
				$header_many  = $header_sub;
				$header_one   = $header_sub;
				$header_noevt = $header_sub;
			}
			else
			{
				$header_title = Text::_('COM_ICAGENDA_HEADER_SEARCH_TITLE');
				$header_many  = Text::sprintf('COM_ICAGENDA_HEADER_SEARCH_MANY_EVENTS', $total);
				$header_one   = Text::sprintf('COM_ICAGENDA_HEADER_SEARCH_ONE_EVENT', $total);
				$header_noevt = Text::_('COM_ICAGENDA_HEADER_SEARCH_NO_EVENT');
			}
		}
		elseif ($time == '0')
		{
			// COM_ICAGENDA_ALL
			$header_title = Text::_('COM_ICAGENDA_HEADER_ALL_TITLE');
			$header_many  = Text::sprintf('COM_ICAGENDA_HEADER_ALL_MANY_EVENTS', $total);
			$header_one   = Text::sprintf('COM_ICAGENDA_HEADER_ALL_ONE_EVENT', $total);
			$header_noevt = Text::_('COM_ICAGENDA_HEADER_ALL_NO_EVENT');
		}
		elseif ($time == '1')
		{
			// COM_ICAGENDA_OPTION_TODAY_AND_UPCOMING
			$header_title = Text::_('COM_ICAGENDA_HEADER_TODAY_AND_UPCOMING_TITLE');
			$header_many  = Text::sprintf('COM_ICAGENDA_HEADER_TODAY_AND_UPCOMING_MANY_EVENTS', $total);
			$header_one   = Text::sprintf('COM_ICAGENDA_HEADER_TODAY_AND_UPCOMING_ONE_EVENT', $total);
			$header_noevt = Text::_('COM_ICAGENDA_HEADER_TODAY_AND_UPCOMING_NO_EVENT');
		}
		elseif ($time == '2')
		{
			// COM_ICAGENDA_OPTION_PAST
			$header_title = Text::_('COM_ICAGENDA_HEADER_PAST_TITLE');
			$header_many  = Text::sprintf('COM_ICAGENDA_HEADER_PAST_MANY_EVENTS', $total);
			$header_one   = Text::sprintf('COM_ICAGENDA_HEADER_PAST_ONE_EVENT', $total);
			$header_noevt = Text::_('COM_ICAGENDA_HEADER_PAST_NO_EVENT');
		}
		elseif ($time == '3')
		{
			// COM_ICAGENDA_OPTION_FUTURE
			$header_title = Text::_('COM_ICAGENDA_HEADER_UPCOMING_TITLE');
			$header_many  = Text::sprintf('COM_ICAGENDA_HEADER_UPCOMING_MANY_EVENTS', $total);
			$header_one   = Text::sprintf('COM_ICAGENDA_HEADER_UPCOMING_ONE_EVENT', $total);
			$header_noevt = Text::_('COM_ICAGENDA_HEADER_UPCOMING_NO_EVENT');
		}
		elseif ($time == '4')
		{
			// COM_ICAGENDA_OPTION_TODAY
			$header_title = Text::_('COM_ICAGENDA_HEADER_TODAY_TITLE');
			$header_many  = Text::sprintf('COM_ICAGENDA_HEADER_TODAY_MANY_EVENTS', $total);
			$header_one   = Text::sprintf('COM_ICAGENDA_HEADER_TODAY_ONE_EVENT', $total);
			$header_noevt = Text::_('COM_ICAGENDA_HEADER_TODAY_NO_EVENT');
		}

		$report = $report2 = '';

		if ($total == 1)
		{
			$report.= '<span class="ic-subtitle-string">' . $header_one . '</span>';
		}
		elseif ($total == 0)
		{
			$report.= '<span class="ic-subtitle-string">' . $header_noevt . '</span>';
		}
		elseif ($total > 1)
		{
			$report.= '<span class="ic-subtitle-string">' . $header_many . '</span>';
		}

		$num = $number_per_page;

		// No display if number does not exist
		$pages = ($num == NULL) ? 1 : ceil($total/$num);

		$page_nb = $getpage;

		if ($getpage == NULL)
		{
			$page_nb = 1;
		}

		$report2.= ($pages <= 1)
					? ''
					: ' <span class="ic-subtitle-pages"> - ' . Text::_('COM_ICAGENDA_EVENTS_PAGE') . ' '
						. $page_nb . ' / ' . $pages . '</span>';

		// Tag for header title depending of show_page_heading setting
//		$menuItem = $app->getMenu()->getActive();

		if ($params->get('show_page_heading', 1))
		{
			$tag = 'h2';
		}
		else
		{
			$tag = 'h1';
		}

		$pageNotFound = '<p>'
					. Text::_('COM_ICAGENDA_PAGE_NOT_FOUND')
					. '</p>'
					. '<button class="ic-btn ic-btn-info"'
					. ' name="' . Text::_('COM_ICAGENDA_EVENTS_PAGE') . ' 1"'
					. ' type="submit">'
					. Text::_('COM_ICAGENDA_EVENTS_PAGE') . ' 1'
					. '</button>';

		// Display Header: title & subtitle
		if ($headerList == 1)
		{
			$header = '<div class="ic-header-container">';
			$header.= '<' . $tag . ' class="ic-header-title">' . $header_title . '</' . $tag . '>' . $resetBtn;
			$header.= ($getpage <= $pages || $getpage == '1')
					? '<div class="ic-header-subtitle">' . $report . ' ' . $report2 . '</div>'
					: $pageNotFound;
		}

		// Display Header: only title
		elseif ($headerList == 2)
		{
			$header = '<div class="ic-header-container">';
			$header.= '<' . $tag . ' class="ic-header-title">' . $header_title . '</' . $tag . '>' . $resetBtn;
			$header.= ($getpage <= $pages || $getpage == '1')
					? (($search || $total == 0)
						? '<div class="ic-header-subtitle">' . $report . '</div>'
						: '')
					: $pageNotFound;
		}

		// Display Header: only subtitle
		elseif ($headerList == 3)
		{
			$header = '<div class="ic-header-container">';
			$header.= ($getpage <= $pages || $getpage == '1')
					? '<br />' . $resetBtn . '<div class="ic-header-subtitle">' . $report . ' ' . $report2 . '</div>'
					: $pageNotFound;
		}

		// Display Header: none
		else
		{
			$header = ($getpage <= $pages || $getpage == '1')
					? (($search || $total == 0)
						? '<div class="ic-header-container"><br />' . $resetBtn . '<div class="ic-header-subtitle">' . $report . '</div>'
						: '<div>')
					: $pageNotFound;
		}

		$header.='</div>'; // @todo move to settings for none. (and all)
		$header.= '<br/>';

		return $header;
	}

	/**
	 * Function to return pagination for list of events
	 */
	public static function pagination($count_items, $arrowtext, $number_per_page, $pagination)
	{
		$app     = Factory::getApplication();
		$jinput  = $app->input;
		$getpage = $jinput->get('page', 1);

		// If number of pages < or = 1, no display of pagination
		if (($count_items / $number_per_page) <= 1)
		{
			$nav = '';
		}
		else
		{
			// Total pages
			$pages  = ($number_per_page == NULL) ? 1 : ceil($count_items / $number_per_page);

			// Set display none if page does not exist to hide pagination
			$style  = ($getpage > ceil($count_items / $number_per_page))
					? ' style="display: none;"'
					: '';

			/* Pagination */
			if ($getpage == 1)
			{
				$nav = '<div class="ic-pagination ic-page-first"' . $style . '>';
			}
			elseif ($getpage == $pages)
			{
				$nav = '<div class="ic-pagination ic-page-last"' . $style . '>';
			}
			else
			{
				$nav = '<div class="ic-pagination ic-page-mid"' . $style . '>';
			}

			// In the case of text next/prev
			// If no text, use w3c recommendation on accessibility: https://www.w3.org/TR/2012/NOTE-WCAG20-TECHS-20120103/C7
			$textNext   = ($arrowtext == 1)
						? Text::_('JNEXT') . '&nbsp;'
						: '<span class="ic-hidden-link-text">' . Text::_('JNEXT') . '</span>';

			$textBack   = ($arrowtext == 1)
						? '&nbsp;' . Text::_('JPREV')
						: '<span class="ic-hidden-link-text">' . Text::_('JPREV') . '</span>';

			// Style CSS
			$style_goto_page    = 'ic-go-to-page';
			$style_goto_btn     = 'ic-btn ic-btn-small';
			$style_current_page = 'ic-current-page';
			$style_current_btn  = 'ic-btn ic-btn-info ic-active';

			// Set page numbers for previous and next
			$pageBack = ($getpage > 2) ? '&page=' . ($getpage - 1) : '';
			$pageNext = '&page=' . ($getpage + 1);

			// Set Input vars to URL after filtering
			$listUrl = 'index.php?option=com_icagenda&view=events';

			$Itemid  = $jinput->get('Itemid', '');
			$listUrl.= $Itemid ? '&Itemid=' . $Itemid : '';

			$vars    = array(
							'filter_search'   => '',
							'filter_from'     => '',
							'filter_to'       => '',
							'filter_category' => '',
							'filter_month'    => '',
							'filter_year'     => '',
						);

			$filters = $jinput->getArray($vars);

			foreach ($filters as $key => $value)
			{
				if ($value)
				{
					$listUrl.= '&' . $key . '=' . $value;
				}
			}

			// Set Prev and Next buttons
			$iC_btnPrev = '<div class="ic-prev">'
						. '<a rel="prev" href="' . Route::_($listUrl . $pageBack) . '" title=" ">'
						. '<span class="iCicon iCicon-backic"></span>'
						. '<span class="ic-prev-text">' . $textBack . '</span>'
						. '</a>'
						. '</div>';

			$iC_btnNext = '<div class="ic-next">'
						. '<a rel="next" href="' . Route::_($listUrl . $pageNext) . '" title=" ">'
						. '<span class="ic-next-text">' . $textNext . '</span>'
						. '<span class="iCicon iCicon-nextic"></span>'
						. '</a>'
						. '</div>';

			// Display PREVIOUS button
			if ($pages > 1
					&& ($getpage > 1 && $getpage <= $pages))
			{
				$nav.= $iC_btnPrev;
			}

			// Display pages navigation
			if ($pagination == 1)
			{
				$nav.= '<div class="ic-pages">';
				

				// Generate pagination
				for ($i = 1 ; $i <= $pages ; $i++)
				{
					if ($i==1 || (($getpage-5) < $i && $i < ($getpage+5)) || $i==$pages)
					{
						if ($i == $pages && $getpage < ($pages-5))
						{
							$nav.= '<span class="ic-hidden-pages">...</span>';
						}

						$pageVar = ($i > 1) ? '&page=' . $i : '';

						if ($i == $getpage)
						{
							$nav.= '<span class="' . $style_current_page . '">'
								. '<span class="' . $style_current_btn . '">' . $i . '</span>'
								. '</span>';
						}
						else
						{
							$nav.= '<span class="' . $style_goto_page . '">'
								. '<a class="iCtip ' . $style_goto_btn . '"'
								. ' href="' . Route::_($listUrl . $pageVar) . '"'
								. ' rel="nofollow"'
								. ' title="' . Text::sprintf('COM_ICAGENDA_EVENTS_PAGE_PER_TOTAL', $i, $pages) . '">'
								. $i
								. '</a>'
								. '</span>';
						}

						if ($i == 1 && $getpage > 6)
						{
							$nav.= '<span class="ic-hidden-pages">...</span>';
						}
					}
				}

				$nav.= '</div>';
			}

			// Display NEXT button
			if ($pages > 1
				&& $getpage < $pages)
			{
				$nav.= $iC_btnNext;
			}

			$nav.= '</div>';
		}

		return $nav;
	}
}
