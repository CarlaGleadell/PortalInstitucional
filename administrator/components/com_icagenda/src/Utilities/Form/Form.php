<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.8.0 2022-02-14
 *
 * @package     iCagenda.Admin
 * @subpackage  src.Utilities.Form
 * @link        https://www.icagenda.com
 *
 * @author      Cyril Rezé
 * @copyright   (c) 2012-2024 Cyril Rezé / iCagenda. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 *
 * @since       3.4.0
 *----------------------------------------------------------------------------
*/

namespace iCutilities\Form;

\defined('_JEXEC') or die;

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;

/**
 * class icagendaForm @deprecated? (only used in site event_edit, to be @removed)
 */
class Form
{
	/**
	 * Function to return script validation for a form used in iCagenda
	 *
	 * @param   $parent_form form type ID ('1' registration, '2' event edit or new)
	 *
	 * @return  script
	 */
	static public function submit($parent_form = null)
	{
		if (!$parent_form) return false;
		if ($parent_form == 1) $parent_name = 'registration';
		if ($parent_form == 2) $parent_name = 'event';

		$app  = Factory::getApplication();
		$lang = Factory::getLanguage();

		$id_suffix = ($lang->getTag() == 'fa-IR') ? '_jalali' : '';

		if ($app->isClient('administrator'))
		{
			$params = ComponentHelper::getParams('com_icagenda');
		}
		elseif ($app->isClient('site'))
		{
			$params = $app->getParams();
		}

		$submit_periodDisplay = $params->get('submit_periodDisplay', 1);
		$submit_datesDisplay  = $params->get('submit_datesDisplay', 1);

		Text::script('COM_ICAGENDA_REGISTRATION_NO_EVENT_SELECTED_ALERT');
		Text::script('COM_ICAGENDA_FORM_NC');
		Text::script('COM_ICAGENDA_FORM_NO_DATES_ALERT');
		Text::script('COM_ICAGENDA_TERMS_AND_CONDITIONS_NOT_CHECKED_REGISTRATION');
		Text::script('COM_ICAGENDA_ALERT_TEXT_EXCEEDS_CHARACTER_LIMIT');

		$prefix_id = $app->isClient('administrator') ? 'jform_' : '';

		// Copyleft function strpos
		// +   original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
		// +   improved by: Onno Marsman
		// +   bugfixed by: Daniel Esteban
		// +   improved by: Brett Zamir (http://brett-zamir.me)
		// +     edited by: Cyril Rezé (https://www.icagenda.com)
		// *     example 1: strpos('Kevin van Zonneveld', 'e', 5);
		// *     returns 1: 14

		$ic_script = array();

		if ( $app->isClient('site') )
		{
			$ic_script[] = '	function iCheckForm() {';
			$ic_script[] = '		var agree = document.getElementById("formAgree");';

			if ($parent_form == 2)
			{
				$ic_script[] = '		if (agree.checked) {';
				$ic_script[] = '			document.getElementById("tos").value = "checked";';
				$ic_script[] = '		}';
			}
		}
		elseif ( $app->isClient('administrator') )
		{
			$ic_script[] = 'jQuery(document).ready(function() {';
			$ic_script[] = '	Joomla.submitbutton = function(task) {';
		}

		if ($parent_form == 1 && $app->isClient('administrator'))
		{
			$ic_script[] = '		var eventid = document.getElementById("' . $prefix_id . 'eventid_id");';
			$ic_script[] = '		if ((eventid.value == "") && (task != "' . $parent_name . '.cancel")) {';
			$ic_script[] = '			alert(Joomla.Text._("COM_ICAGENDA_REGISTRATION_NO_EVENT_SELECTED_ALERT"));';
			$ic_script[] = '			return false;';
			$ic_script[] = '		}';
		}

		if ($parent_form == 2)
		{
			$ic_script[] = '		function strpos (haystack, needle, offset) {';
			$ic_script[] = '			var i = (haystack + "").indexOf(needle, (offset || 0));';
			$ic_script[] = '			return i === -1 ? false : i;';
			$ic_script[] = '		}';

			$ic_script[] = '		var nodate = "0";';
			$ic_script[] = '		var noserialdate = \'a:1:{i:0;s:19:"0000-00-00 00:00:00";}\';';
			$ic_script[] = '		var noserialdate2 = \'a:1:{i:0;s:16:"0000-00-00 00:00";}\';';
			$ic_script[] = '		var emptydatetime = "0000-00-00 00:00:00";';

			if ($submit_periodDisplay && $app->isClient('site'))
			{
				$ic_script[] = '		var startDate = document.getElementById("startdate' . $id_suffix . '");';
				$ic_script[] = '		var endDate = document.getElementById("enddate' . $id_suffix . '");';
				$ic_script[] = '		var isValidStartDate = strpos(startDate.value, nodate, 0);';
				$ic_script[] = '		var isValidEndDate = strpos(endDate.value, nodate, 0);';
			}
			elseif ($app->isClient('administrator'))
			{
				$ic_script[] = '		var startDate = document.getElementById("startdate' . $id_suffix . '");';
				$ic_script[] = '		var endDate = document.getElementById("enddate' . $id_suffix . '");';
				$ic_script[] = '		var isValidStartDate = strpos(startDate.value, nodate, 0);';
				$ic_script[] = '		var isValidEndDate = strpos(endDate.value, nodate, 0);';
			}
			if ($submit_datesDisplay && $app->isClient('site'))
			{
				$ic_script[] = '		var Dates = document.getElementById("' . $prefix_id . 'dates_id");';
				$ic_script[] = '		var isValidSingleDate = strpos(Dates.value, nodate, 2);';
			}
			elseif ($app->isClient('administrator'))
			{
				$ic_script[] = '		var Dates = document.getElementById("' . $prefix_id . 'dates_id");';
				$ic_script[] = '		var isValidSingleDate = strpos(Dates.value, nodate, 2);';
			}

			$ic_script[] = '		if (';

			if ($submit_datesDisplay && $app->isClient('site'))
			{
				$ic_script[] = '			( !isValidSingleDate';
				$ic_script[] = '			|| (Dates.value == noserialdate && isValidSingleDate)';
				$ic_script[] = '			|| (Dates.value == noserialdate2 && isValidSingleDate)';
				$ic_script[] = '			|| Dates.value == "" )';
			}
			elseif ($app->isClient('administrator'))
			{
				$ic_script[] = '			( !isValidSingleDate';
				$ic_script[] = '			|| (Dates.value == noserialdate && isValidSingleDate)';
				$ic_script[] = '			|| (Dates.value == noserialdate2 && isValidSingleDate)';
				$ic_script[] = '			|| Dates.value == "" )';
			}

			if ($submit_periodDisplay && $submit_datesDisplay && $app->isClient('site'))
			{
				$ic_script[] = '			&& ';
			}

			if ($submit_periodDisplay && $app->isClient('site'))
			{
				$ic_script[] = '			( (!isValidStartDate || (startDate.value == emptydatetime)) )';
			}
			elseif ($app->isClient('administrator'))
			{
				$ic_script[] = '			&& ( (!isValidStartDate || (startDate.value == emptydatetime)) )';
			}

			if ($app->isClient('administrator')) $ic_script[] = '			&& ( task != "' . $parent_name . '.cancel" ) ';

			$ic_script[] = '		) {';
			$ic_script[] = '			alert(Joomla.Text._("COM_ICAGENDA_FORM_NO_DATES_ALERT"));';
			$ic_script[] = '			document.getElementById("message_error").innerHTML = "'
											. Text::_("COM_ICAGENDA_FORM_NO_DATES_ALERT") . '";';
			$ic_script[] = '			document.getElementById("form_errors").style.display = "block";';

			if ($submit_periodDisplay && $app->isClient('site'))
			{
				$ic_script[] = '			document.getElementById("startdate' . $id_suffix . '").value = emptydatetime;';
				$ic_script[] = '			document.getElementById("enddate' . $id_suffix . '").value = emptydatetime;';
				$ic_script[] = '			document.getElementById("startdate' . $id_suffix . '").addClass("ic-date-invalid");';
				$ic_script[] = '			document.getElementById("enddate' . $id_suffix . '").addClass("ic-date-invalid");';
			}
			elseif ($app->isClient('administrator'))
			{
				$ic_script[] = '			document.getElementById("startdate' . $id_suffix . '").value = emptydatetime;';
				$ic_script[] = '			document.getElementById("enddate' . $id_suffix . '").value = emptydatetime;';
				$ic_script[] = '			document.getElementById("startdate' . $id_suffix . '").addClass("ic-date-invalid");';
				$ic_script[] = '			document.getElementById("enddate' . $id_suffix . '").addClass("ic-date-invalid");';
			}

			if ($submit_datesDisplay && $app->isClient('site'))
			{
				$ic_script[] = '			document.getElementById("dTable' . $id_suffix . '").addClass("ic-date-invalid");';
			}
			elseif ($app->isClient('administrator'))
			{
				$ic_script[] = '			document.getElementById("dTable' . $id_suffix . '").addClass("ic-date-invalid");';
			}

			$ic_script[] = '			scroll_to = document.getElementById("ic-dates-fieldset");';
			$ic_script[] = '			scroll_to.scrollIntoView();';
			$ic_script[] = '			return false;';
			$ic_script[] = '		}';
			$ic_script[] = '		else {';

			if ($app->isClient('site'))
			{
				// Disable submit button after first click
				$ic_script[] = '
					jQuery(function($) {
						$("#submitevent").one("submit", function() {
							$(this).find(\'button[type="submit"]\')
								.attr("disabled","disabled")
								.css({
									"background-color": "transparent",
									"color": "grey"
								});
							$("#submit").addClass("ic-loader");
						});
					});
				';
			}

			$ic_script[] = '			document.getElementById("form_errors").style.display = "none";';

			if ($submit_periodDisplay && $app->isClient('site'))
			{
				$ic_script[] = '			document.getElementById("startdate' . $id_suffix . '").removeClass("ic-date-invalid");';
				$ic_script[] = '			document.getElementById("enddate' . $id_suffix . '").removeClass("ic-date-invalid");';
			}
			elseif ($app->isClient('administrator'))
			{
				$ic_script[] = '			document.getElementById("startdate' . $id_suffix . '").removeClass("ic-date-invalid");';
				$ic_script[] = '			document.getElementById("enddate' . $id_suffix . '").removeClass("ic-date-invalid");';
			}

			if ($submit_datesDisplay && $app->isClient('site'))
			{
				$ic_script[] = '			document.getElementById("dTable' . $id_suffix . '").removeClass("ic-date-invalid");';
			}
			elseif ($app->isClient('administrator'))
			{
				$ic_script[] = '			document.getElementById("dTable' . $id_suffix . '").removeClass("ic-date-invalid");';
			}

			$ic_script[] = '		}';

			if ($submit_periodDisplay && $app->isClient('site'))
			{
				$ic_script[] = '		if (isValidStartDate && !isValidEndDate) {';
				$ic_script[] = '			document.getElementById("enddate' . $id_suffix . '").value = startDate.value;';
				$ic_script[] = '		}';
			}
			elseif ($app->isClient('administrator'))
			{
				$ic_script[] = '		if (isValidStartDate && !isValidEndDate) {';
				$ic_script[] = '			document.getElementById("enddate' . $id_suffix . '").value = startDate.value;';
				$ic_script[] = '		}';
			}
		}

		if ($app->isClient('administrator'))
		{
			$ic_script[] = '		if (task == "' . $parent_name . '.cancel"';
			$ic_script[] = '			|| document.formvalidator.isValid(document.id("' . $parent_name . '-form")))';
			$ic_script[] = '		{';
			$ic_script[] = '			// do field validation';
			$ic_script[] = '			Joomla.submitform(task, document.getElementById("' . $parent_name . '-form"));';
			$ic_script[] = '		}';
			$ic_script[] = '		else {';
			$ic_script[] = '			alert("' . Text::_("JGLOBAL_VALIDATION_FORM_FAILED") . '");';
			$ic_script[] = '		}';
		}

		if ($app->isClient('site'))
		{
			$ic_script[] = '		if (!agree.checked) {';
			if ($parent_form == 1) $ic_script[] = '			alert(Joomla.Text._("COM_ICAGENDA_TERMS_AND_CONDITIONS_NOT_CHECKED_REGISTRATION"));';
			if ($parent_form == 2) $ic_script[] = '			alert(Joomla.Text._("COM_ICAGENDA_TERMS_OF_SERVICE_NOT_CHECKED_SUBMIT_EVENT"));';
			$ic_script[] = '			scroll_to = document.getElementById("content");';
			$ic_script[] = '			scroll_to.scrollIntoView();';
			$ic_script[] = '			return false;';
			$ic_script[] = '		}';
		}

		$ic_script[] = '	}';

		if ($app->isClient('administrator'))
		{
			$ic_script[] = '});';
		}

		return implode("\n", $ic_script);
	}

	/**
	 * Function to set timepicker.js and date function strings of translation
	 *
	 * @since   3.4.1
	 * @deprecated 3.8.0
	 */
	public static function loadDateTimePickerJSLanguage()
	{
		// icdates.js Strings of Translation
		Text::script('COM_ICAGENDA_DELETE_DATE');

		// timepicker.js Strings of Translation
		Text::script('JANUARY');
		Text::script('FEBRUARY');
		Text::script('MARCH');
		Text::script('APRIL');
		Text::script('MAY');
		Text::script('JUNE');
		Text::script('JULY');
		Text::script('AUGUST');
		Text::script('SEPTEMBER');
		Text::script('OCTOBER');
		Text::script('NOVEMBER');
		Text::script('DECEMBER');

		Text::script('SA');
		Text::script('SU');
		Text::script('MO');
		Text::script('TU');
		Text::script('WE');
		Text::script('TH');
		Text::script('FR');

		Text::script('COM_ICAGENDA_TP_CURRENT');
		Text::script('COM_ICAGENDA_TP_CLOSE');
		Text::script('COM_ICAGENDA_TP_TITLE');
		Text::script('COM_ICAGENDA_TP_TIME');
		Text::script('COM_ICAGENDA_TP_HOUR');
		Text::script('COM_ICAGENDA_TP_MINUTE');
	}
}
