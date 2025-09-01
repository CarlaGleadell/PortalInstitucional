<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.9.0 2024-02-20
 *
 * @package     iCagenda.Admin
 * @subpackage  Utilities.Field.Icagenda
 * @link        https://www.icagenda.com
 *
 * @author      Cyril Rezé
 * @copyright   (c) 2012-2024 Cyril Rezé / iCagenda. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 *
 * @since       1.0.0
 *----------------------------------------------------------------------------
*/

namespace iCutilities\Field\Icagenda;

use iCutilities\Thumb\Thumb as icagendaThumb;
use Joomla\CMS\Form\FormField;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

HTMLHelper::_('script', 'com_icagenda/icform.js', array('relative' => true, 'version' => 'auto'), array('async' => 'async'));

/**
 * Media Upload Form Field class for iCagenda.
 */
class MediaUploadField extends FormField
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 */
	public $type = 'MediaUpload';

	/**
	 * Method to get the field input markup for a media upload.
	 *
	 * @return  string  The field input markup.
	 */
	protected function getInput()
	{
		$link = (string) $this->element['link'];

		// Initialize variables.
		$html = '';
		$attr = '';

		// Initialize some field attributes.
		$attr .= $this->element['class'] ? ' class="' . (string) $this->element['class'] . '"' : '';
		$attr .= $this->element['size'] ? ' size="' . (int) $this->element['size'] . '"' : '';
		$attr .= $this->element['accept'] ? ' accept="' . (string) $this->element['accept'] . '"' : '';
		$attr .= $this->element['directory'] ? ' directory="' . (string) $this->element['directory'] . '"' : '';
		$attr .= ((string) $this->element['disabled'] == 'true') ? ' disabled="disabled"' : '';
		$attr .= ((string) $this->element['preview'] == 'true') ? ' preview="true"' : '';
		$attr .= (int) $this->element['preview_width'] ? ' preview_width="' . $this->element['preview_width'] . '"' : ' preview_width="400"';
		$attr .= (int) $this->element['preview_height'] ? ' preview_height="' . $this->element['preview_height'] . '"' : ' preview_height="400"';

		// Initialize JavaScript field attributes.
		$attr .= $this->element['onchange'] ? ' onchange="' . (string) $this->element['onchange'] . '"' : '';

		$btn_clear = '';

		$btn_clear_empty = '<a class="button btn btn-outline-secondary ic-button-clear" title="' . Text::_('JLIB_FORM_BUTTON_CLEAR') . '"' . ' href="#" onclick="';
		$btn_clear_empty.= ' document.getElementById(\'' . $this->id . '\').value=\'\';';

		if ($this->element['preview'] == 'true') {
			$btn_clear_empty.= ' document.getElementById(\'ic-upload-preview\').innerHTML=\'\';';
		}

		$btn_clear_empty.= 'return false;';
		$btn_clear_empty.= '">';
		$btn_clear_empty.= Text::_('JLIB_FORM_BUTTON_CLEAR') . '</a>';
		
		if (empty($this->value)) {
			$btn_clear = $btn_clear_empty;
		} else {
			$file_select_new = "";
			$file_select_new.= "<span class=\'input-group\'>";
			$file_select_new.= "<input type=\'file\' style=\'cursor: pointer;\' name=\'" . $this->name . "\' id=\'" . $this->id . "\'" . " value=\'"
				. htmlspecialchars($this->value, ENT_COMPAT, "UTF-8") . "\'" . " " . str_replace("\"", "\\'", $attr) .  " />";
			$file_select_new.= addslashes($btn_clear_empty);
			$file_select_new.= "</span>";

			$btn_clear.= '<a class="button btn btn-outline-secondary ic-button-clear" title="' . Text::_('JLIB_FORM_BUTTON_CLEAR') . '"' . ' href="#" onclick="';
			$btn_clear.= ' document.getElementById(\'' . $this->id . '\').value=\'\';';
			$btn_clear.= ' document.getElementById(\'file-in-data\').innerHTML=\'\';';
			$btn_clear.= ' document.getElementById(\'file-input-hidden\').innerHTML=\'\';';

			if ($this->element['preview'] == 'true') {
				$btn_clear.= ' document.getElementById(\'ic-upload-preview\').innerHTML=\'\';';
			}

			$btn_clear.= ' document.getElementById(\'file-select-new\').innerHTML=\'' . htmlspecialchars($file_select_new, ENT_QUOTES) . '\';';
			$btn_clear.= ' return false;';
			$btn_clear.= '">';
			$btn_clear.= Text::_('JLIB_FORM_BUTTON_CLEAR') . '</a>';
		}

		// The text field.
		if (empty($this->value)) {
			$html.= '<span class="input-group">';
			$html.= '<input type="file" style="cursor: pointer" name="' . $this->name . '" id="' . $this->id . '"' . ' value="'
				. htmlspecialchars($this->value, ENT_COMPAT, 'UTF-8') . '"' . ' ' . $attr . ' />';
			$html.= $btn_clear;
			$html.= '</span>';
		} else {
			$html.= '<span id="file-in-data">';
			$html.= '<span class="input-group">';
			$html.= '<input type="text" name="' . $this->name . '_display" id="' . $this->id . '_display"' . ' value="'
				. htmlspecialchars($this->value, ENT_COMPAT, 'UTF-8') . '"' . ' readonly="readonly"' . $attr . ' />';
			$html.= $btn_clear;
			$html.= '</span>';
			$html.= '</span>';

			$html.= '<span id="file-input-hidden">';
			$html.= '<input type="hidden" name="' . $this->name . '" id="' . $this->id . '"' . ' value="'
				. htmlspecialchars($this->value, ENT_COMPAT, 'UTF-8') . '"' . ' readonly="readonly"' . $attr . ' />';
			$html.= '</span>';

			$html.= '<span id="file-select-new">';
			$html.= '</span>';
		}

		if ($this->element['preview'] == 'true') {
			$preview_width  = (int) $this->element['preview_width'] ? $this->element['preview_width'] : '500';
			$preview_height = (int) $this->element['preview_height'] ? $this->element['preview_height'] : '200';

			$uploadedImage  = (! empty($this->value))
							? '<img src="' . icagendaThumb::sizeMedium($this->value) . '" alt="image-preview" id="jform_image_preview" class="media-preview h-64" height="200" />'
							: '';

			$html.= '<p><div id="ic-upload-preview">' . $uploadedImage . '</div></p>';
		}

		return $html;
	}
}
