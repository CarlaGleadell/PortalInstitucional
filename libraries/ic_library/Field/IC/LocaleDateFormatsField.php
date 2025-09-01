<?php
/**
 *----------------------------------------------------------------------------
 * iC Library   Library by Jooml!C, for Joomla!
 *----------------------------------------------------------------------------
 * @version     2.2.0 2024-02-19
 *
 * @package     iC Library
 * @subpackage  Field.IC
 * @link        https://www.joomlic.com
 *
 * @author      Cyril Reze
 * @copyright   (c) 2013-2024 Cyril Reze / JoomliC. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 *
 * @since       2.2.0
 *----------------------------------------------------------------------------
*/

namespace iClib\Field\IC;

use Joomla\CMS\Factory;
use Joomla\CMS\Form\Field\ListField;
use Joomla\CMS\Language\Text;

// phpcs:disable PSR1.Files.SideEffects
\defined('JPATH_PLATFORM') or die;
// phpcs:enable PSR1.Files.SideEffects

/**
 * Locale Date Formats form field
 */
class LocaleDateFormatsField extends ListField
{
	/**
	 * The form field type.
	 *
	 * @var  string
	 */
	protected $type = 'LocaleDateFormats';

	/**
	 * Method to get the field input markup.
	 *
	 * @return  string  The field input markup.
	 */
	protected function getInput()
	{
		$lang     = Factory::getLanguage();
		$langTag  = $lang->getTag();
		$langName = $lang->getName();

		$langPath = 'Globalize/culture';

		if ( ! file_exists(JPATH_LIBRARIES . '/ic_library/' . $langPath . '/' . $langTag . '.php')) {
			$langTag = 'en-GB';
			$currentText = Text::sprintf('ICLIB_DATE_FORMAT_DEFAULT', '[' . $langTag . ']');
		} else {
			$currentText = Text::sprintf('ICLIB_DATE_FORMAT_CURRENT', '[' . $langTag . ']');
		}

		$globalize = JPATH_LIBRARIES . '/ic_library/' . $langPath . '/' . $langTag . '.php';
		$iso       = JPATH_LIBRARIES . '/ic_library/' . $langPath . '/iso.php';

		require_once $globalize;
		require_once $iso;

		$class    = isset($this->class) ? ' class="' . $this->class . '"' : '';
		$selected = ' selected="selected" style="background: #d4d4d4;"';

		// Start Select List of Date Formats
		$html = '<select id="' . $this->id . '_id"' . $class . ' name="' . $this->name . '" >';

		if ($this->name != 'jform[format]'
			&& $this->name != 'format'
		) {
			$html.= '<option value="" style="text-align:center;">- ' . Text::_('ICLIB_SELECT_DATE_FORMAT') . ' -</option>';
		}

		// Date Formats in Current Language of User (admin)
		$html.= '<optgroup label="' . $currentText . '">';

		$dateglobalize_array = [
			'1'  => $dateglobalize_1,
			'2'  => $dateglobalize_2,
			'3'  => $dateglobalize_3,
			'4'  => $dateglobalize_4,
			'5'  => (isset($dateglobalize_5) ? $dateglobalize_5 : ''), // en-GB, en-US,
			'6'  => $dateglobalize_6,
			'7'  => $dateglobalize_7,
			'8'  => $dateglobalize_8,
			'9'  => (isset($dateglobalize_9) ? $dateglobalize_9 : ''), // en-GB
			'10' => (isset($dateglobalize_10) ? $dateglobalize_10 : ''), // en-GB
			'11' => $dateglobalize_11,
			'12' => $dateglobalize_12,
		];

		foreach ($dateglobalize_array as $format => $label) {
			if (isset($label) && $label) {
				$html.= '<option value="' . $format . '"';
				$html.= ($this->value == $format) ? $selected : '';
				$html.= '>' . $label . '</option>';
			}
		}

		$html.= '</optgroup>';

		// Other date format in English (if 'en-GB' is current language)
		if ($langTag == 'en-GB') {
			// Extra en-US
			$html.= '<optgroup label="en-US (more formats if current language) :">';

			$extra_array = [
				$extravalue_1 => $extra_1,
				$extravalue_2 => $extra_2,
				$extravalue_3 => $extra_3,
				$extravalue_4 => $extra_4,
				$extravalue_5 => $extra_5,
			];

			foreach ($extra_array as $format => $label) {
				$html.= '<option value="' . $format . '"';
				$html.= ($this->value == $format) ? $selected : '';
				$html.= '>' . $label . '</option>';
			}

			$html.= '</optgroup>';

			// Extra en-CA
			$html.= '<optgroup label="en-CA :">';

			$html.= '<option value="' . $extravalue_6 . '"';
			$html.= ($this->value == $extravalue_6) ? $selected : '';
			$html.= '>' . $extra_6 . '</option>';

			$html.= '</optgroup>';

			// Extra en-SG
			$html.= '<optgroup label="en-SG :">';

			$html.= '<option value="' . $extravalue_7 . '"';
			$html.= ($this->value == $extravalue_7) ? $selected : '';
			$html.= '>' . $extra_7 . '</option>';

			$html.= '</optgroup>';
		}


		// International Date Format (ISO)
		$html.= '<optgroup label="' . Text::_('ICLIB_DATE_FORMAT_ISO') . '">';

		$html.= '<option value="' . $iso . '"';
		$html.= ($this->value == $iso) ? $selected : '';
		$html.= '>1993-04-30</option>';

		$html.= '</optgroup>';

		// DMY Little-endian (day, month, year), e.g. 22.04.96 or 22/04/96
		$html.= '<optgroup label="' . Text::_('ICLIB_DATE_FORMAT_DMY') . '">';

		$dmy_array = [
			$dmy_1 => '30␣04␣1993',
			$dmy_2 => '30␣04␣93',
			$dmy_3 => '30␣04',
			$dmy_4 => '04␣93',
			$dmy_5 => isset($dmy_text_5) ? $dmy_text_5 : '',
			$dmy_6 => isset($dmy_text_6) ? $dmy_text_6 : '',
		];

		foreach ($dmy_array as $format => $label) {
			if ($label) {
				$html.= '<option value="' . $format . '"';
				$html.= ($this->value == $format) ? $selected : '';
				$html.= '>' . $label . '</option>';
			}
		}

		$html.= '</optgroup>';

		// MDY Middle-endian (month, day, year), e.g. 04/22/96
		$html.= '<optgroup label="' . Text::_('ICLIB_DATE_FORMAT_MDY') . '">';

		$mdy_array = [
			$mdy_1 => '04␣30␣1993',
			$mdy_2 => '04␣30␣93',
			$mdy_3 => '04␣30',
			$mdy_4 => '04␣93',
			$mdy_5 => isset($mdy_text_5) ? $mdy_text_5 : '',
			$mdy_6 => isset($mdy_text_6) ? $mdy_text_6 : '',
		];

		foreach ($mdy_array as $format => $label) {
			if ($label) {
				$html.= '<option value="' . $format . '"';
				$html.= ($this->value == $format) ? $selected : '';
				$html.= '>' . $label . '</option>';
			}
		}

		$html.= '</optgroup>';

		// YMD Big-endian (year, month, day), e.g. 1996-04-22
		$html.= '<optgroup label="' . Text::_('ICLIB_DATE_FORMAT_YMD') . '">';

		$ymd_array = [
			$ymd_1 => '1993␣04␣30',
			$ymd_2 => '93␣04␣30',
			$ymd_3 => '04␣30',
			$ymd_4 => '93␣04',
			$ymd_5 => isset($ymd_text_5) ? $ymd_text_5 : '',
			$ymd_6 => isset($ymd_text_6) ? $ymd_text_6 : ''
		];

		foreach ($ymd_array as $format => $label) {
			if ($label) {
				$html.= '<option value="' . $format . '"';
				$html.= ($this->value == $format) ? $selected : '';
				$html.= '>' . $label . '</option>';
			}
		}

		$html.= '</optgroup>';

		$html.= '</select>';

		return $html;
	}
}
