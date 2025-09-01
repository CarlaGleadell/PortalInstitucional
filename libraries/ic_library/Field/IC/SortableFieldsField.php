<?php
/**
 *----------------------------------------------------------------------------
 * iC Library   Library by Jooml!C, for Joomla!
 *----------------------------------------------------------------------------
 * @version     2.2.0 2024-02-17
 *
 * @package     iC Library
 * @subpackage  Field.IC
 * @link        https://www.joomlic.com
 *
 * @author      Cyril Reze
 * @copyright   (c) 2013-2024 Cyril Reze / JoomliC. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 *
 * @since       1.4.0
 *----------------------------------------------------------------------------
*/

namespace iClib\Field\IC;

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Form\FormField;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Uri\Uri;

// phpcs:disable PSR1.Files.SideEffects
\defined('JPATH_PLATFORM') or die;
// phpcs:enable PSR1.Files.SideEffects

/**
 * Sortable fields form field
 */
class SortableFieldsField extends FormField
{
	/**
	 * The form field type.
	 *
	 * @var  string
	 */
	public $type = 'SortableFields';

	/**
	 * Method to get the field label markup.
	 *
	 * @return  string  The field label markup.
	 */
	public function getLabel()
	{
		$app        = Factory::getApplication();
		$document   = Factory::getDocument();

		$isMenu     = ($app->input->get('option') == 'com_menus');
		$menuParams = $isMenu ? 'params_' : '';

		if (empty($this->element['label'])
			&& empty($this->element['description'])
		) {
			return '';
		}

		$title          = $this->element['label']
						? (string) $this->element['label']
						: ($this->element['title'] ? (string) $this->element['title'] : '');
		$heading        = $this->element['heading'] ? (string) $this->element['heading'] : 'h4';
		$description    = (string) $this->element['infotext'];
		$class          = ! empty($this->class)
						? ' class="' . $this->class . '"'
						: '';

		$html = [];

		$html[] = '<div id="ic-sortable" class="controls mb-3">';

		$fieldnames = isset($this->value) ? $this->value : (string) $this->element['default'];
		$fieldnames = explode(',', $fieldnames);

		$group = $isMenu ? 'params' : '';

		foreach ($fieldnames AS $k => $fieldname) {
			$fieldname = trim($fieldname);
			$field     = $this->form->getField($fieldname, $group);

			// Make sure the selected field is hidden
			if ( ! isset($field->element['type'])
				|| $field->element['type'] !== 'hidden'
			) {
				$this->form->setFieldAttribute($fieldname, 'type', 'hidden');
			}

			// Settings attributes
			$name = isset($field->element['name']) ? $field->element['name'] : false;

			if ($name) {
				$rendertype  = isset($field->element['rendertype'])  ? $field->element['rendertype']  : 'text';
				$label       = isset($field->element['label'])       ? $field->element['label']       : '';
				$description = isset($field->element['description']) ? $field->element['description'] : '';
				$class       = isset($field->element['class'])       ? $field->element['class']       : '';
				$labelclass  = isset($field->element['labelclass'])  ? $field->element['labelclass']  : '';
				$default     = isset($field->element['default'])     ? $field->element['default']     : '';

				// Add new field to the sortable list
				$type_field = new \SimpleXMLElement('<field />');
				$type_field->addAttribute('name', $fieldname);
				$type_field->addAttribute('type', $rendertype);
				$type_field->addAttribute('label', $label);
				$type_field->addAttribute('description', $description);
				$type_field->addAttribute('class', $class);
				$type_field->addAttribute('labelclass', $labelclass);
				$type_field->addAttribute('default', $default);

				if (isset($field->element->option)) {
					$values = (array) $field->element->xpath('option');
					$options = (array) $field->element->option;
					unset($values['@attributes']);
					unset($options['@attributes']);

					// Add 'Use Global' with value
					if ($field->element['useglobal']) {
						$tmp        = new \stdClass;
						$tmp->value = '';
						$tmp->text  = Text::_('JGLOBAL_USE_GLOBAL');
						$component  = $app->input->getCmd('option');

						// Get correct component for menu items
						if ($component == 'com_menus') {
							$link      = $this->form->getData()->get('link');
							$uri       = new Uri($link);
							$component = $uri->getVar('option', 'com_menus');
						}

						$params = ComponentHelper::getParams($component);
						$value  = $params->get($fieldname);

						// Try with global configuration
						if (is_null($value)) {
							$value = Factory::getConfig()->get($fieldname);
						}

						// Try with menu configuration
						if (is_null($value) && $app->input->getCmd('option') == 'com_menus') {
							$value = ComponentHelper::getParams('com_menus')->get($fieldname);
						}

						if (!is_null($value)) {
							$value = (string) $value;

							foreach ($values as $key => $option) {
								if ((string) $values[$key]['value'] === $value) {
									$value = Text::_($option);

									break;
								}
							}

							$tmp->text  = Text::sprintf('JGLOBAL_USE_GLOBAL_VALUE', $value);
						}

						$child = $type_field->addChild('option', $tmp->text);
						$child->addAttribute('value', $tmp->value);
					}

					// Add Options
					foreach ($values as $key => $option) {
						$child = $type_field->addChild('option', $option);
						$child->addAttribute('value', $values[$key]['value']);
					}
				}

				$this->form->setField($type_field, $group);

				$label = $this->form->getLabel($fieldname, $group);
				$input = $this->form->getInput($fieldname, $group);

				$html[] = '<div id="' . $fieldname . '" class="ui-state-default">';

				$labelIcon = '<span class="icon-move mx-2 mt-2"></span> ' . $label;

				$html[] = LayoutHelper::render('joomla.form.renderfield',
							array('input' => $input, 'label' => $labelIcon,'name' => 'test', 'options' => []));
				$html[] = '</div>';

				$label_suffix = '';

				if ($fieldname == 'filter_search') {
					$label_suffix = Text::_('IC_FIELD_TYPE_TEXT');
				} elseif (in_array($fieldname, array('filter_from', 'filter_to'))) {
					$label_suffix = Text::_('IC_FIELD_TYPE_CALENDAR');
				} elseif (in_array($fieldname, array('filter_category', 'filter_month', 'filter_year'))) {
					$label_suffix = Text::_('IC_FIELD_TYPE_LIST');
				}

				$document->addScriptDeclaration('
					jQuery(document).ready(function($) {
						var label = $("#jform_' . $menuParams . $fieldname . '-lbl").text();
						$("#jform_' . $menuParams . $fieldname . '-lbl").html("<strong>"+label+"</strong> <small>(' . $label_suffix . ')</small>");
					});
				');
			}
		}

		$html[] = '</div>';

		$document->addScriptDeclaration('
			jQuery(document).ready(function($) {
				$( "#ic-sortable" ).sortable({
					placeholder: "ui-state-highlight",
					cursor: "crosshair",
					update: function(event, ui) {
						var order = $("#ic-sortable").sortable("toArray");
						$("#' . $this->id . '").val(order.join(","));
					}
				});
				$( "#ic-sortable" ).disableSelection();

				let searchShowGlobal = document.getElementById("jform_filter_search0");
				if (searchShowGlobal) {
					document.querySelector("[for=\"jform_filter_search0\"]").classList.remove("btn-outline-secondary");
					document.querySelector("[for=\"jform_filter_search0\"]").classList.add("btn-outline-success");
				}

				let searchShowMenu = document.getElementById("jform_params_filter_search2");
				if (searchShowMenu) {
					document.querySelector("[for=\"jform_params_filter_search2\"]").classList.remove("btn-outline-secondary");
					document.querySelector("[for=\"jform_params_filter_search2\"]").classList.add("btn-outline-success");
				}

				// Will hide control-group field, if input type is hidden
//				$("input[type=hidden]").parents(".control-group").css("margin-bottom", "0");
			});
		');

		$document->addStyleDeclaration('
			#ic-sortable {
				width: auto;
			}
			#ic-sortable .controls {
				width: auto;
			}
			#ic-sortable .controls {
				text-align: right;
			}
			.ui-state-default {
				background: rgba(155,155,155,0.05);
				border: 1px solid rgba(155,155,155,0.5);
				padding: 10px;
				margin-bottom: 5px;
			}
			.ui-sortable-helper{
				background; #5bc0de;
				background: rgba(91, 192, 222, 0.5);
				border: none;
				color: #fff;
				padding: 10px;
				margin-bottom: 5px;
			}
			.ui-state-default .control-group {
				margin-bottom: 0;
			}
			.icon-move:hover,
			.ui-state-default:hover {
				cursor: move;
			}
			.ui-state-highlight {
				background; #5bc0de;
				background: rgba(91, 192, 222, 0.5);
				border: 1px dotted #2aa8ce;
				margin-bottom: 0;
			}
		');

//		HTMLHelper::_('jquery.framework');

		$document->addScript('https://code.jquery.com/jquery-1.12.4.min.js');
		$document->addScript('https://code.jquery.com/ui/1.11.4/jquery-ui.min.js');

		return '</div><div>' . implode('', $html);
	}

	/**
	 * Method to get the field input markup.
	 *
	 * @return  string  The field input markup.
	 */
	public function getInput()
	{
		return '<input type="hidden" id="' . $this->id . '" name="' . $this->name . '" value="' . $this->value . '" />';
	}
}
