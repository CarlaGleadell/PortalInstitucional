<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.8.1 2022-03-01
 *
 * @package     iCagenda.Site
 * @subpackage  Layouts.icagenda
 * @link        https://www.icagenda.com
 *
 * @author      Cyril Rezé
 * @copyright   (c) 2012-2024 Cyril Rezé / iCagenda. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 *
 * @since       3.8
 *----------------------------------------------------------------------------
*/

\defined('_JEXEC') or die;

use iCutilities\Tiptip\Tiptip as icagendaTiptip;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text as JText;

extract($displayData);

/**
 * Layout variables
 * -----------------
 * @var   string   basePath         Base path to use when loading layout files (theme pack)
 * @var   string   cancelUrl        Cancel Registration Url.
 * @var   string   customLink       Custom registration link.
 * @var   array    extraDates       List of availables dates.
 * @var   string   registered       Number of registered users.
 * @var   string   registerTarget   Register button browser target.
 * @var   string   registerUrl      Registration Url.
 * @var   string   status           Status of registration button.
 * @var   string   textButton       Custom register text for button.
 * @var   string   userBooked       Logged-in user has registration(s) for this event.
 * @var   string   userRegStatus    Logged-in user has registered for this date.
*/


/*
 * Buttons HTML
 */

// Button Select Date
$selectButtonText = ($userRegStatus == 'registeredDate') ? JText::_('COM_ICAGENDA_REGISTRATION_ALREADY_BOOKED') : JText::_('COM_ICAGENDA_REGISTRATION_DATE_NO_TICKETS_LEFT');

if (version_compare(JVERSION, '4.0', 'lt'))
{
	// Set Tooltip
	icagendaTiptip::setTooltip('.ic-tip-button', array('keepAlive' => true, 'defaultPosition' => 'bottom'));

	// Set the select list of available dates.
	$dates_list_select = '<div class="ic-tip-scroll-list">';

	foreach ($extraDates as $k => $v)
	{
		$dates_list_select .= '<div class="ic-tip-link">';
		$dates_list_select .= '<a href="' . $k . '" rel="nofollow" target="_parent">&#160;' . $v . '&#160;</a>';
		$dates_list_select .= '</div>';
	}

	$dates_list_select .= '</div>';

	$btn_register_select  = '<button class="btn btn-info" type="button">';
	$btn_register_select .= '<span class="iCicon iCicon-people"></span>&nbsp;' . $selectButtonText;
	$btn_register_select .= '</button><br />';
	$btn_register_select .= '<a class="ic-tip-button" title="' . htmlspecialchars($dates_list_select) . '" rel="nofollow">';
	$btn_register_select .= '<span class="ic-select-another-date">' . JText::_('COM_ICAGENDA_REGISTRATION_REGISTER_ANOTHER_DATE') . '</span>';
	$btn_register_select .= '</a>';

	$styleGroupJ3  = ' style="width:100%;"';
	$styleButtonJ3 = ' style="width:inherit;"';
}
else
{
	// Load iC Dropdown script
	HTMLHelper::_('script', 'com_icagenda/iCdropdown.js', array('relative' => true, 'version' => 'auto'), array('defer' => 'defer'));

	// Set the select list of available dates.
	$dates_list_select = '<ul class="ic-dropdown-menu w-100 text-center" aria-labelledby="dropdownDatesListSelect">';

	foreach ($extraDates as $k => $v)
	{
		$dates_list_select .= '<li>';
		$dates_list_select .= '<a class="ic-dropdown-item small" href="' . $k . '" rel="nofollow" target="_parent">' . $v . '</a>';
		$dates_list_select .= '</li>';
	}

	$dates_list_select .= '</ul>';

	$btn_register_select  = '<div class="btn-group-vertical ic-dropdown">';
	$btn_register_select .= '<button class="btn btn-info disabled mb-0" type="button">';
	$btn_register_select .= '<span class="iCicon iCicon-people"></span>&nbsp;' . $selectButtonText;
	$btn_register_select .= '</button><br />';
	$btn_register_select .= '<button class="btn btn-outline-info btn-sm ic-dropdown-toggle" type="button" id="dropdownDatesListSelect" aria-expanded="false">';
	$btn_register_select .= '<span class="ic-select-another-date">' . JText::_('COM_ICAGENDA_REGISTRATION_REGISTER_ANOTHER_DATE') . '</span>';
	$btn_register_select .= '</button>';
	$btn_register_select .= $dates_list_select;
	$btn_register_select .= '</div>';

	$styleGroupJ3 = $styleButtonJ3 = '';
}

// Button Register OK
$btn_register_ok  = ($deadlineDate) ? '<div class="btn-group-vertical"' . $styleGroupJ3 . '>' : '';
$btn_register_ok .= '<button class="btn btn-success" type="button" onclick="window.open(\'' . $registerUrl . '\', \'' . $registerTarget . '\')"' . $styleButtonJ3 . '>';
$btn_register_ok .= '<span class="iCicon iCicon-register"></span>&nbsp;' . $textButton;
$btn_register_ok .= '</button>';
$btn_register_ok .= ($deadlineDate) ? '<button class="btn btn-outline-success btn-sm disabled ic-opacity-1" type="button"' . $styleButtonJ3 . '>' : '';
$btn_register_ok .= ($deadlineDate) ? '<span class="ic-register-deadline">' . Jtext::sprintf('COM_ICAGENDA_REGISTRATION_DEADLINE_DATE', $deadlineDate) . '</span>' : '';
$btn_register_ok .= ($deadlineDate) ? '</button>' : '';
$btn_register_ok .= ($deadlineDate) ? '</div>' : '';

// Button Register Private
$btn_register_private  = '<button class="btn btn-primary" type="button" onclick="window.open(\'' . $registerUrl . '\', \'' . $registerTarget . '\')">';
$btn_register_private .= '<span class="iCicon iCicon-private"></span>&nbsp;' . $textButton;
$btn_register_private .= '</button>';

// Button Registration Close
$btn_register_close  = '<button class="btn btn-default btn-secondary" type="button" disabled>';
$btn_register_close .= '<span class="iCicon iCicon-blocked"></span>&nbsp;' . JText::_('COM_ICAGENDA_REGISTRATION_CLOSED');
$btn_register_close .= '</button>';

// Button Registration Complete
$btn_register_complete  = '<button class="btn btn-info" type="button">';
$btn_register_complete .= '<span class="iCicon iCicon-people"></span>&nbsp;' . JText::_('COM_ICAGENDA_REGISTRATION_EVENT_FULL');
$btn_register_complete .= '</button>';

// Button Registration Already Booked
$btn_register_booked  = '<button class="btn btn-primary" type="button" disabled>';
$btn_register_booked .= '<span class="icon-checkmark-circle"></span>&nbsp;' . JText::_('COM_ICAGENDA_REGISTRATION_ALREADY_BOOKED');
$btn_register_booked .= '</button>';
?>

<?php echo ${'btn_register_' . $status}; ?>
