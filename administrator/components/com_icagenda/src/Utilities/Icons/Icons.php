<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.8.0 2022-02-10
 *
 * @package     iCagenda.Admin
 * @subpackage  src.Utilities.Icons
 * @link        https://www.icagenda.com
 *
 * @author      Cyril Rezé
 * @copyright   (c) 2012-2024 Cyril Rezé / iCagenda. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 *
 * @since       3.2.9
 *----------------------------------------------------------------------------
*/

namespace iCutilities\Icons;

\defined('_JEXEC') or die;

use iCutilities\AddToCal\AddToCal as icagendaAddtocal;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Uri\Uri;

/**
 * class icagendaIcons
 */
class Icons
{
	/**
	 * Shows the button corresponding to the action.
	 *
	 * @param   $type  Icon action
	 * @param   $item  Item to be handled
	 *
	 * @return  $html  string
	 * @todo	Move HTML to icagenda layouts.
	 */
	public static function showIcon($type, $item = '')
	{
		$app = Factory::getApplication();

		// loading Global Options
		$iCparams = ComponentHelper::getParams('com_icagenda');

		$eventId  = $app->input->getInt('id');
		$itemId   = $app->input->getInt('Itemid');
		$date     = $app->input->get('date');
		$varDate  = $date ? '&date=' . $date : '';
		$eventURL = 'index.php?option=com_icagenda&view=event&id=' . $eventId . '&Itemid=' . $itemId . $varDate;

		$printURL = Route::_($eventURL . '&tmpl=component&print=1');
		$vcalURL  = Route::_($eventURL . '&vcal=1');

		// Start HTML rendering of icons
		$html = array();

		switch (strtolower($type))
		{
			case 'printpreview':

				$html[]= '<a tabindex="0" class="btn btn-sm btn-secondary" href="' . $printURL . '" onclick="window.open(this.href,\'win2\',\'status=no,toolbar=no,scrollbars=yes,titlebar=no,menubar=no,resizable=yes,width=640,height=480,directories=no,location=no\'); return false;" rel="nofollow">';
				$html[]= '<span class="iCicon iCicon-print"></span>&#160;' . Text::_('JGLOBAL_PRINT');
				$html[]= '</a>';

				break;

			case 'print':

				$html[]= '<a href="#" onclick="window.print();return false;" rel="nofollow">';
				$html[]= '<span class="iCicon iCicon-print"></span>&#160;' . Text::_('JGLOBAL_PRINT') . '&#160;';
				$html[]= '</a>';

				break;

			case 'addtocal':

				// Selected Calendar Services
				$iconAddToCal_options = $iCparams->get('iconAddToCal_options', '');

				if (is_array($iconAddToCal_options) && $item)
				{
					// Load iC Dropdown script
					HTMLHelper::_('script', 'com_icagenda/iCdropdown.js', array('relative' => true, 'version' => 'auto'), array('defer' => 'defer'));

					$gcal = icagendaAddtocal::googleCalendar($item);
					$wcal = icagendaAddtocal::windowsliveCalendar($item);
					$ycal = icagendaAddtocal::yahooCalendar($item);

					$svgPath = Uri::root() . 'media/com_icagenda/images/addtocal/svg/';
					$pngPath = Uri::root() . 'media/com_icagenda/images/addtocal/png/';

					$iconSize = $iCparams->get('iconAddToCal_size', '24');

					$addtocal = '';

					// Google Calendar - link
					if (in_array('1', $iconAddToCal_options))
					{
						$addtocal.= '<li><a class="ic-dropdown-item ic-addtocal-link-' . $iconSize . '" href="' . $gcal . '" rel="nofollow" target="_blank">';
						$addtocal.= '<img class="ic-svg ic-addtocal-svg-' . $iconSize . '"'
									. ' src="' . $pngPath . 'google-calendar.png"'
									. ' alt="' . Text::_('COM_ICAGENDA_GCALENDAR_LABEL') . '"'
									. ' srcset="' . $svgPath . 'google-calendar.svg" />';
						$addtocal.= '<span class="ic-addtocal-text-' . $iconSize . '">' . Text::_('COM_ICAGENDA_GCALENDAR_LABEL') . '</span>';
						$addtocal.= '</a></li>';
					}

					// iCal Calendar - ics
					if (in_array('2', $iconAddToCal_options))
					{
						$addtocal.= '<li><a class="ic-dropdown-item ic-addtocal-link-' . $iconSize . '" href="' . $vcalURL . '" rel="nofollow" target="_blank">';
						$addtocal.= '<img class="ic-svg ic-addtocal-svg-' . $iconSize . '"'
									. ' src="' . $pngPath . 'apple-calendar.png"'
									. ' alt="' . Text::_('COM_ICAGENDA_VCAL_ICAL_LABEL') . '"'
									. ' srcset="' . $svgPath . 'apple-calendar.svg" />';
						$addtocal.= '<span class="ic-addtocal-text-' . $iconSize . '">' . Text::_('COM_ICAGENDA_VCAL_ICAL_LABEL') . '</span>';
						$addtocal.= '</a></li>';
					}

					// Outlook iCalendar - ics
					if (in_array('3', $iconAddToCal_options))
					{
						$addtocal.= '<li><a class="ic-dropdown-item ic-addtocal-link-' . $iconSize . '" href="' . $vcalURL . '" rel="nofollow" target="_blank">';
						$addtocal.= '<img class="ic-svg ic-addtocal-svg-' . $iconSize . '"'
									. ' src="' . $pngPath . 'outlook-calendar.png"'
									. ' alt="' . Text::_('COM_ICAGENDA_OUTLOOK_LABEL') . '"'
									. ' srcset="' . $svgPath . 'outlook-calendar.svg" />';
						$addtocal.= '<span class="ic-addtocal-text-' . $iconSize . '">' . Text::_('COM_ICAGENDA_OUTLOOK_LABEL') . '</span>';
						$addtocal.= '</a></li>';
					}

					// Outlook Live Calendar - link
					if (in_array('4', $iconAddToCal_options))
					{
						$addtocal.= '<li><a class="ic-dropdown-item ic-addtocal-link-' . $iconSize . '" href="' . $wcal . '" rel="nofollow" target="_blank">';
						$addtocal.= '<img class="ic-svg ic-addtocal-svg-' . $iconSize . '"'
									. ' src="' . $pngPath . 'outlook.png"'
									. ' alt="' . Text::_('COM_ICAGENDA_LIVE_CALENDAR_LABEL') . '"'
									. ' srcset="' . $svgPath . 'outlook.svg" />';
						$addtocal.= '<span class="ic-addtocal-text-' . $iconSize . '">' . Text::_('COM_ICAGENDA_LIVE_CALENDAR_LABEL') . '</span>';
						$addtocal.= '</a></li>';
					}

					// Yahoo Calendar - link
					if (in_array('5', $iconAddToCal_options))
					{
						$addtocal.= '<li><a class="ic-dropdown-item ic-addtocal-link-' . $iconSize . '" href="' . $ycal . '" rel="nofollow" target="_blank">';
						$addtocal.= '<img class="ic-svg ic-addtocal-svg-' . $iconSize . '"'
									. ' src="' . $pngPath . 'yahoo-calendar.png"'
									. ' alt="' . Text::_('COM_ICAGENDA_YAHOO_CALENDAR_LABEL') . '"'
									. ' srcset="' . $svgPath . 'yahoo-calendar.svg" />';
						$addtocal.= '<span class="ic-addtocal-text-' . $iconSize . '">' . Text::_('COM_ICAGENDA_YAHOO_CALENDAR_LABEL') . '</span>';
						$addtocal.= '</a></li>';
					}

					$return_atc = htmlspecialchars($addtocal);

					$html[]= '<div class="ic-dropdown">';
					$html[]= '<button tabindex="0" type="button" class="btn btn-sm btn-info ic-dropdown-toggle" id="dropdownAddToCal" aria-expanded="false" aria-describedby="buttonAddToCal">';
					$html[]= '<span class="iCicon iCicon-calendar"></span>&#160;' . Text::_('COM_ICAGENDA_ADD_TO_CALL_LABEL') . '';
					$html[]= '</button>';
					$html[]= '<ul class="ic-dropdown-menu text-left" aria-labelledby="dropdownAddToCal">';
					$html[]= $addtocal;
					$html[]= '</div>';

					break;
				}

			default:
		}

		return implode("\n", $html);
	}

	/**
	 * Removes variable from URL
	 *
	 * @param   $url      Url to change
	 * @param   $varname  Var name to remove from string
	 */
	public static function removeqsvar($url, $varname)
	{
		return preg_replace('/([?&])'.$varname.'=[^&]+(&|$)/','$1',$url);
	}
}
