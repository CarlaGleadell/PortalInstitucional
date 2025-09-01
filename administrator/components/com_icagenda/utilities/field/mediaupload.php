<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.9.0 2024-02-20
 *
 * @package     iCagenda.Admin
 * @link        https://www.icagenda.com
 *
 * @author      Cyril Rezé
 * @copyright   (c) 2012-2024 Cyril Rezé / iCagenda. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 *
 * @since       1.0
 *----------------------------------------------------------------------------
*/

defined('_JEXEC') or die;

use iCutilities\Thumb\Thumb as icagendaThumb;

jimport('joomla.form.formfield');

class icagendaFormFieldMediaUpload extends JFormField
{
	public $type = 'MediaUpload';

//	protected static $initialised = false;

	/**
	 * Method to get the field input markup for a media selector.
	 * Use attributes to identify specific created_by and asset_id fields
	 *
	 * @return  string  The field input markup.
	 *
	 * @since   11.1
	 */
	protected function getInput()
	{
		$assetField  = $this->element['asset_field'] ? (string) $this->element['asset_field'] : 'asset_id';
		$authorField = $this->element['created_by_field'] ? (string) $this->element['created_by_field'] : 'created_by';
		$asset       = $this->form->getValue($assetField) ? $this->form->getValue($assetField) : (string) $this->element['asset_id'];

		if ($asset == '')
		{
			$asset = JFactory::getApplication()->input->get('option');
		}

		$link = (string) $this->element['link'];

//		if ( ! self::$initialised)
//		{
			// Load the modal behavior script.
//			JHtml::_('behavior.modal');

			// Build the script.
//			$script = array();
//			$script[] = '	function jInsertFieldValue(value, id) {';
//			$script[] = '		var old_id = document.id(id).value;';
//			$script[] = '		if (old_id != id) {';
//			$script[] = '			var elem = document.id(id)';
//			$script[] = '			elem.value = value;';
//			$script[] = '			elem.fireEvent("change");';
//			$script[] = '		}';
//			$script[] = '	}';
			

			// Add the script to the document head.
//			JFactory::getDocument()->addScriptDeclaration(implode("\n", $script));

//			self::$initialised = true;
//		}

		// Initialize variables.
		$html = array();
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


//		$folder = 'icagenda_doc';

		// The button.
//		$html[] = '<div class="button2-left">';
//		$html[] = '	<div class="blank">';
//		$html[] = '		<a class="modal" title="' . JText::_('JLIB_FORM_BUTTON_SELECT') . '"' . ' href="'
//			. ($this->element['readonly'] ? ''
//			: ($link ? $link
//				: 'index.php?option=com_media&amp;view=images&amp;tmpl=component&amp;asset=' . $asset . '&amp;author='
//				. $this->form->getValue($authorField)) . '&amp;fieldid=' . $this->id . '&amp;folder=' . $folder) . '"'
//			. ' rel="{handler: \'iframe\', size: {x: 800, y: 500}}">';
//		$html[] = JText::_('JLIB_FORM_BUTTON_SELECT') . '</a>';
//		$html[] = '	</div>';
//		$html[] = '</div>';

		$btn_clear = '';

			$btn_clear_empty = '<a class="button btn btn-outline-secondary ic-button-clear" title="' . JText::_('JLIB_FORM_BUTTON_CLEAR') . '"' . ' href="#" onclick="';
			$btn_clear_empty.= ' document.getElementById(\'' . $this->id . '\').value=\'\';';
			if ($this->element['preview'] == 'true')
			{
			$btn_clear_empty.= ' document.getElementById(\'ic-upload-preview\').innerHTML=\'\';';
			}
			$btn_clear_empty.= 'return false;';
			$btn_clear_empty.= '">';
			$btn_clear_empty.= JText::_('JLIB_FORM_BUTTON_CLEAR') . '</a>';
		
		if (empty($this->value))
		{
//			$html[] = '<div class="button2-left">';
//			$html[] = '	<div class="blank">';
			$btn_clear = $btn_clear_empty;
//			$html[] = '</div>';
//			$html[] = '</div>';
		}
		else
		{
//			$file_select_new = '<input type="file" style="cursor: pointer" name="' . $this->name . '" id="' . $this->id . '" value="'
//				. htmlspecialchars($this->value, ENT_COMPAT, 'UTF-8') . '" ' . $attr .  ' />';
			$file_select_new = "";
			$file_select_new.= "<span class=\'input-group\'>";
			$file_select_new.= "<input type=\'file\' style=\'cursor: pointer;\' name=\'" . $this->name . "\' id=\'" . $this->id . "\'" . " value=\'"
				. htmlspecialchars($this->value, ENT_COMPAT, "UTF-8") . "\'" . " " . str_replace("\"", "\\'", $attr) .  " />";
			$file_select_new.= addslashes($btn_clear_empty);
			$file_select_new.= "</span>";

//			$html[] = '<div class="button2-left">';
//			$html[] = '	<div class="blank">';
			$btn_clear.= '		<a class="button btn btn-outline-secondary ic-button-clear" title="' . JText::_('JLIB_FORM_BUTTON_CLEAR') . '"' . ' href="#" onclick="';
			$btn_clear.= ' document.getElementById(\'' . $this->id . '\').value=\'\';';
			$btn_clear.= ' document.getElementById(\'file-in-data\').innerHTML=\'\';';
			$btn_clear.= ' document.getElementById(\'file-input-hidden\').innerHTML=\'\';';
			if ($this->element['preview'] == 'true')
			{
				$btn_clear.= ' document.getElementById(\'ic-upload-preview\').innerHTML=\'\';';
			}
			$btn_clear.= ' document.getElementById(\'file-select-new\').innerHTML=\'' . htmlspecialchars($file_select_new, ENT_QUOTES) . '\';';
//			$btn_clear.= ' document.getElementById(\'' . $this->id . '\').fireEvent(\'change\');';
			$btn_clear.= ' return false;';
			$btn_clear.= '">';
			$btn_clear.= JText::_('JLIB_FORM_BUTTON_CLEAR') . '</a>';
//			$html[] = '</div>';
//			$html[] = '</div>';
		}

		// The text field.
		if (empty($this->value))
		{
			$html[] = '<span class="input-group">';
			$html[] = '	<input type="file" style="cursor: pointer" name="' . $this->name . '" id="' . $this->id . '"' . ' value="'
				. htmlspecialchars($this->value, ENT_COMPAT, 'UTF-8') . '"' . ' ' . $attr . ' />';
			$html[] = $btn_clear;
			$html[] = '</span>';
		}
		else
		{
			$html[] = '<span id="file-in-data">';
			$html[] = '<span class="input-group">';
			$html[] = '	<input type="text" name="' . $this->name . '_display" id="' . $this->id . '_display"' . ' value="'
				. htmlspecialchars($this->value, ENT_COMPAT, 'UTF-8') . '"' . ' readonly="readonly"' . $attr . ' />';
			$html[] = $btn_clear;
			$html[] = '</span>';
			$html[] = '</span>';

			$html[] = '<span id="file-input-hidden">';
			$html[] = '	<input type="hidden" name="' . $this->name . '" id="' . $this->id . '"' . ' value="'
				. htmlspecialchars($this->value, ENT_COMPAT, 'UTF-8') . '"' . ' readonly="readonly"' . $attr . ' />';
			$html[] = '</span>';

			$html[] = '<span id="file-select-new">';
//			$html[] = '<span class="input-group">';
//			$html[] = '	<input type="file" style="cursor: pointer" name="' . $this->name . '" id="' . $this->id . '"' . ' value="'
//				. htmlspecialchars($this->value, ENT_COMPAT, 'UTF-8') . '"' . ' ' . $attr . ' />';
//			$html[] = $btn_clear;
//			$html[] = '</span>';
			$html[] = '</span>';
		}

//		if (file_exists(JPATH_ROOT . '/' . ComponentHelper::getParams('com_media')->get('image_path', 'images') . '/' . $this->element['directory']))

		if ($this->element['preview'] == 'true')
		{
			$preview_width  = (int) $this->element['preview_width'] ? $this->element['preview_width'] : '500';
			$preview_height = (int) $this->element['preview_height'] ? $this->element['preview_height'] : '200';

			$uploadedImage = (! empty($this->value)) ? '<img src="' . icagendaThumb::sizeMedium($this->value) . '" alt="image-preview" id="jform_image_preview" class="media-preview h-64" height="200" />' : '';

			$html[] = '<p><div id="ic-upload-preview">' . $uploadedImage . '</div></p>';
		}

		return implode("\n", $html);
	}
}
