<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.9.0 2024-02-18
 *
 * @package     iCagenda.Admin
 * @subpackage  Utilities.Field.Icagenda
 * @link        https://www.icagenda.com
 *
 * @author      Cyril Rezé
 * @copyright   (c) 2012-2024 Cyril Rezé / iCagenda. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 *
 * @since       3.8
 *----------------------------------------------------------------------------
*/

namespace iCutilities\Field\Icagenda;

use Joomla\CMS\Factory;
use Joomla\CMS\Form\FormField;
use Joomla\CMS\Language\Text;

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

/**
 * Deadline Time Form Field
 */
class DeadlineTimeField extends FormField
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 */
	protected $type = 'DeadlineTime';

	/**
	 * Method to get the field input markup.
	 *
	 * @return  string  The field input markup.
	 */
	protected function getLabel()
	{
		return $this->title;
	}
	/**
	 * Method to get the field input markup for a generic list.
	 * Use the multiple attribute to enable multiselect.
	 *
	 * @return  string  The field input markup.
	 */
	protected function getInput()
	{
		Factory::getDocument()->addScriptDeclaration('
			function updateField() {
				const selectCal = document.getElementById("selectCal");
				const selectTime = document.getElementById("selectTime");
				const calArray = [];
				const timeArray = [];
				document.querySelectorAll(".input-deadline").forEach((item, index) => {
					if (item.value > 0) {
						item.classList.add("border-success", "border-2");
						if (index <= 2) {
							calArray.push(index);
						} else {
							timeArray.push(index);
						}
					}
				});
				if (calArray.length !== 0) {
					selectCal.classList.remove("text-muted");
					selectCal.classList.add("text-success");
				} else {
					selectCal.classList.remove("text-success");
					selectCal.classList.add("text-muted");
				}
				if (timeArray.length !== 0) {
					selectTime.classList.remove("text-muted");
					selectTime.classList.add("text-success");
				} else {
					selectTime.classList.remove("text-success");
					selectTime.classList.add("text-muted");
				}
			}

			function clearStyle(e) {
				if (e.value < 1) {
					e.classList.remove("valid", "form-control-success", "border-success", "border-2");
				}
			}

			function checkIsSet(e) {
				var lbl = document.getElementById(e.id+"-lbl");
				if (e.value > 0) {
					e.classList.remove("ic-field-muted");
					e.classList.add("border-success", "border-2");
					lbl.classList.remove("text-muted");
					lbl.classList.add("text-success", "ic-bolder");
				} else {
					e.classList.add("ic-field-muted");
					e.classList.remove("border-success", "border-2");
					lbl.classList.add("text-muted");
					lbl.classList.remove("text-success", "ic-bolder");
				}
				updateField();
			}
		');

		if (isset($this->value)) {
			$value = $this->value;

			$value_month = isset($value['month']) ? $value['month'] : '';
			$value_week  = isset($value['week'])  ? $value['week']  : '';
			$value_day   = isset($value['day'])   ? $value['day']   : '';
			$value_hour  = isset($value['hour'])  ? $value['hour']  : '';
			$value_min   = isset($value['min'])   ? $value['min']   : '';

			$iconCalClass   = ($value_day || $value_week || $value_month)
							? ' text-success'
							: ' text-muted';
			$iconTimeClass   = ($value_min || $value_hour)
							? ' text-success'
							: ' text-muted';

			$monthClass  = $value_month ? 'text-success ic-bolder'   : 'text-muted';
			$weekClass   = $value_week  ? 'text-success ic-bolder'   : 'text-muted';
			$dayClass    = $value_day   ? 'text-success ic-bolder'   : 'text-muted';
			$hourClass   = $value_hour  ? 'text-success ic-bolder'   : 'text-muted';
			$minClass    = $value_min   ? 'text-success ic-bolder'   : 'text-muted';

			$monthInput  = $value_month ? ' border-success border-2' : ' ic-field-muted';
			$weekInput   = $value_week  ? ' border-success border-2' : ' ic-field-muted';
			$dayInput    = $value_day   ? ' border-success border-2' : ' ic-field-muted';
			$hourInput   = $value_hour  ? ' border-success border-2' : ' ic-field-muted';
			$minInput    = $value_min   ? ' border-success border-2' : ' ic-field-muted';
		} else {
			$value_min = $value_hour = $value_day = $value_week = $value_month = '';
			$iconCalClass = $iconTimeClass = $minClass = $hourClass = $dayClass = $weekClass = $monthClass = ' text-muted';
		}

		$attributes = [
			'class="input-deadline form-control ic-input-number-col-2"',
			'placeholder="0"',
//			'onchange="checkIsSet(this)"',
			'oninput="this.value=this.value.replace(/[^0-9]/g,\'\').replace(/(\..*?)\..*/g,\'$1\');checkIsSet(this)"',
			'onfocusout="clearStyle(this)"',
		];

		$onChange = ' onchange="checkIsSet(this)"';
		$onInput  = ' oninput="this.value = this.value.replace(/[^0-9]/g, \'\').replace(/(\..*?)\..*/g, \'$1\');"';

		$html = '<div class="row ic-row">';

		$html.= '<div id="selectCal" class="col-2 col-xxxl-1' . $iconCalClass . '">';
		$html.= '<br /><span class="iCicon-calendar-days ic-fs-1-5 ms-2 mt-2"></span>';
		$html.= '</div>';

		$html.= '<div class="col-3 col-xxxl-2">';
		$html.= '<label id="selectMonths-lbl" class="' . $monthClass . '">' . Text::_('IC_MONTHS') . '</label>';
		$html.= '<input id="selectMonths"'
				. ' class="input-deadline form-control ic-input-number-col-2' . $monthInput . '"'
				. ' type="text" name="' . $this->name . '[month]" value="' . $value_month . '"'
				. implode(' ', $attributes) . '>';
		$html.= '</div>';

		$html.= '<div class="col-3 col-xxxl-2">';
		$html.= '<label id="selectWeeks-lbl" class="' . $weekClass . '">' . Text::_('IC_WEEKS') . '</label>';
		$html.= '<input id="selectWeeks"'
				. ' class="input-deadline form-control ic-input-number-col-2' . $weekInput . '"'
				. ' type="text" name="' . $this->name . '[week]" value="' . $value_week . '"'
				. implode(' ', $attributes) . '>';
		$html.= '</div>';

		$html.= '<div class="col-3 col-xxxl-2">';
		$html.= '<label id="selectDays-lbl" class="' . $dayClass . '">' . Text::_('IC_DAYS') . '</label>';
		$html.= '<input id="selectDays"'
				. ' class="input-deadline form-control ic-input-number-col-2' . $dayInput . '"'
				. ' type="text" name="' . $this->name . '[day]" value="' . $value_day . '"'
				. implode(' ', $attributes) . '>';
		$html.= '</div>';

		$html.= '<div id="selectTime" class="col-2 col-xxxl-1' . $iconTimeClass . '">';
		$html.= '<br /><span class="iCicon-clock ic-fs-1-5 ms-2 mt-2"></span>';
		$html.= '</div>';

		$html.= '<div class="col-3 col-xxxl-2">';
		$html.= '<label id="selectHours-lbl" class="' . $hourClass . '">' . Text::_('IC_HOURS') . '</label>';
		$html.= '<input id="selectHours"'
				. ' class="input-deadline form-control ic-input-number-col-2' . $hourInput . '"'
				. ' type="text" name="' . $this->name . '[hour]" value="' . $value_hour . '"'
				. implode(' ', $attributes) . '>';
		$html.= '</div>';

		$html.= '<div class="col-3 col-xxxl-2">';
		$html.= '<label id="selectMinutes-lbl" class="' . $minClass . '">' . Text::_('IC_MINUTES') . '</label>';
		$html.= '<input id="selectMinutes"'
				. ' class="input-deadline form-control ic-input-number-col-2' . $minInput . '"'
				. ' type="text" name="' . $this->name . '[min]" value="' . $value_min . '"'
				. implode(' ', $attributes) . '>';
		$html.= '</div>';

		$html.= '</div>';

		return $html;
	}
}
