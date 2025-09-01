<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.8.0 2022-02-10
 *
 * @package     iCagenda.Admin
 * @subpackage  Utilities
 * @link        https://www.icagenda.com
 *
 * @author      Cyril Rezé
 * @copyright   (c) 2012-2024 Cyril Rezé / iCagenda. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 *
 * @since       3.2.9
 *----------------------------------------------------------------------------
*/

defined('_JEXEC') or die;

/**
 * class icagendaIcons
 */
class icagendaIcons
{
	/**
	 * Shows the button corresponding to the action.
	 *
	 * @param   $type  icon action
	 * @param   $item  to be handled
	 *
	 * @return  $html  string
	 */
	public static function showIcon($type, $item = '')
	{
		$app = JFactory::getApplication();

		// loading Global Options
		$iCparams = JComponentHelper::getParams('com_icagenda');

		$eventId  = $app->input->getInt('id');
		$itemId   = $app->input->getInt('Itemid');
		$date     = $app->input->get('date');
		$varDate  = $date ? '&date=' . $date : '';
		$eventURL = 'index.php?option=com_icagenda&view=event&id=' . $eventId . '&Itemid=' . $itemId . $varDate;

		$printURL = JRoute::_($eventURL . '&tmpl=component&print=1');
		$vcalURL  = JRoute::_($eventURL . '&vcal=1');

		// Start HTML rendering of icons
		$html = array();

		switch (strtolower($type))
		{
			case 'printpreview':

				$html[]= '<a class="iCtip" href="' . $printURL . '" onclick="window.open(this.href,\'win2\',\'status=no,toolbar=no,scrollbars=yes,titlebar=no,menubar=no,resizable=yes,width=640,height=480,directories=no,location=no\'); return false;" title="' . JText::_('JGLOBAL_PRINT') . '" rel="nofollow">';
				$html[]= '<span class="iCicon iCicon-print"></span>';
				$html[]= '</a>';

				break;

			case 'print':

				$html[]= '<a href="#" onclick="window.print();return false;" title="' . JText::_('JGLOBAL_PRINT') . '" rel="nofollow">';
				$html[]= '<span class="iCicon iCicon-print"></span>&#160;' . JText::_('JGLOBAL_PRINT') . '&#160;';
				$html[]= '</a>';

				break;

			case 'addtocal':

				// Selected Calendar Services
				$iconAddToCal_options = $iCparams->get('iconAddToCal_options', '');

				if (is_array($iconAddToCal_options) && $item)
				{
					$gcal = icagendaAddtocal::googleCalendar($item);
					$wcal = icagendaAddtocal::windowsliveCalendar($item);
					$ycal = icagendaAddtocal::yahooCalendar($item);

					$svgPath = JUri::root() . 'media/com_icagenda/images/addtocal/svg/';
					$pngPath = JUri::root() . 'media/com_icagenda/images/addtocal/png/';

					$iconSize = $iCparams->get('iconAddToCal_size', '24');

					$addtocal = '';
					$addtocal.= '<div class="ic-tip-title">' . JText::_('COM_ICAGENDA_ADD_TO_CALL_LABEL') . '</div>';

					// Google Calendar - link
					if (in_array('1', $iconAddToCal_options))
					{
						$addtocal.= '<div class="ic-tip-link">';
						$addtocal.= '<a href="' . $gcal . '" class="ic-title-cal-tip" rel="nofollow" target="_blank">';
						$addtocal.= '<img class="ic-svg ic-addtocal-svg-' . $iconSize . '"'
									. ' src="' . $pngPath . 'google-calendar.png"'
									. ' alt="' . JText::_('COM_ICAGENDA_GCALENDAR_LABEL') . '"'
									. ' srcset="' . $svgPath . 'google-calendar.svg" />';
						$addtocal.= '<span class="ic-addtocal-text-' . $iconSize . '">' . JText::_('COM_ICAGENDA_GCALENDAR_LABEL') . '</span>';
						$addtocal.= '</a>';
						$addtocal.= '</div>';
					}

					// iCal Calendar - ics
					if (in_array('2', $iconAddToCal_options))
					{
						$addtocal.= '<div class="ic-tip-link">';
						$addtocal.= '<a href="' . $vcalURL . '" class="ic-title-cal-tip" rel="nofollow" target="_blank">';
						$addtocal.= '<img class="ic-svg ic-addtocal-svg-' . $iconSize . '"'
									. ' src="' . $pngPath . 'apple-calendar.png"'
									. ' alt="' . JText::_('COM_ICAGENDA_VCAL_ICAL_LABEL') . '"'
									. ' srcset="' . $svgPath . 'apple-calendar.svg" />';
						$addtocal.= '<span class="ic-addtocal-text-' . $iconSize . '">' . JText::_('COM_ICAGENDA_VCAL_ICAL_LABEL') . '</span>';
						$addtocal.= '</a>';
						$addtocal.= '</div>';
					}

					// Outlook iCalendar - ics
					if (in_array('3', $iconAddToCal_options))
					{
						$addtocal.= '<div class="ic-tip-link">';
						$addtocal.= '<a href="' . $vcalURL . '" class="ic-title-cal-tip" rel="nofollow" target="_blank">';
						$addtocal.= '<img class="ic-svg ic-addtocal-svg-' . $iconSize . '"'
									. ' src="' . $pngPath . 'outlook-calendar.png"'
									. ' alt="' . JText::_('COM_ICAGENDA_OUTLOOK_LABEL') . '"'
									. ' srcset="' . $svgPath . 'outlook-calendar.svg" />';
						$addtocal.= '<span class="ic-addtocal-text-' . $iconSize . '">' . JText::_('COM_ICAGENDA_OUTLOOK_LABEL') . '</span>';
						$addtocal.= '</a>';
						$addtocal.= '</div>';
					}

					// Outlook Live Calendar - link
					if (in_array('4', $iconAddToCal_options))
					{
						$addtocal.= '<div class="ic-tip-link">';
						$addtocal.= '<a href="' . $wcal . '" class="ic-title-cal-tip" rel="nofollow" target="_blank">';
						$addtocal.= '<img class="ic-svg ic-addtocal-svg-' . $iconSize . '"'
									. ' src="' . $pngPath . 'outlook.png"'
									. ' alt="' . JText::_('COM_ICAGENDA_LIVE_CALENDAR_LABEL') . '"'
									. ' srcset="' . $svgPath . 'outlook.svg" />';
						$addtocal.= '<span class="ic-addtocal-text-' . $iconSize . '">' . JText::_('COM_ICAGENDA_LIVE_CALENDAR_LABEL') . '</span>';
						$addtocal.= '</a>';
						$addtocal.= '</div>';
					}

					// Yahoo Calendar - link
					if (in_array('5', $iconAddToCal_options))
					{
						$addtocal.= '<div class="ic-tip-link">';
						$addtocal.= '<a href="' . $ycal . '" class="ic-title-cal-tip" rel="nofollow" target="_blank">';
						$addtocal.= '<img class="ic-svg ic-addtocal-svg-' . $iconSize . '"'
									. ' src="' . $pngPath . 'yahoo-calendar.png"'
									. ' alt="' . JText::_('COM_ICAGENDA_YAHOO_CALENDAR_LABEL') . '"'
									. ' srcset="' . $svgPath . 'yahoo-calendar.svg" />';
						$addtocal.= '<span class="ic-addtocal-text-' . $iconSize . '">' . JText::_('COM_ICAGENDA_YAHOO_CALENDAR_LABEL') . '</span>';
						$addtocal.= '</a>';
						$addtocal.= '</div>';
					}

					$return_atc = htmlspecialchars($addtocal);

					$html[]= '<a class="ic-addtocal" style="cursor: pointer;" title="' . $return_atc . '" rel="nofollow">'; // @todo: remove style, and replace <a> by <button>
					$html[]= '<span class="iCicon iCicon-calendar"></span>';
					$html[]= '</a>';

					break;
				}

			default:
		}

		return implode("\n", $html);
	}

	/**
	 * Removes variable from URL
	 *
	 * @param $url to change
	 * @param $varname to remove from string
	 */
	public static function removeqsvar($url, $varname)
	{
		return preg_replace('/([?&])'.$varname.'=[^&]+(&|$)/','$1',$url);
	}
}
