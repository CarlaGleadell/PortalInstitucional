<?php
/**
 *----------------------------------------------------------------------------
 * iC Library   Library by Jooml!C, for Joomla!
 *----------------------------------------------------------------------------
 * @version     2.2.0 2024-02-15
 *
 * @package     iC Library
 * @subpackage  Form.Field
 * @link        https://www.joomlic.com
 *
 * @author      Cyril Rezé
 * @copyright   (c) 2013-2024 Cyril Rezé / iCagenda. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 *
 * @since       1.4.0
 *----------------------------------------------------------------------------
*/

defined('_JEXEC') or die();

JFormHelper::loadFieldClass('list');

/**
 * Sortable fields form field
 */
class iCFormFieldSortableFields extends JFormFieldList
{
	public $type = 'sortablefields';

	public function getLabel()
	{
		$app        = JFactory::getApplication();
		$document   = JFactory::getDocument();

		$isMenu     = ($app->input->get('option') == 'com_menus');
		$menuParams = $isMenu ? 'params_' : '';

		if (empty($this->element['label']) && empty($this->element['description']))
		{
			return '';
		}

		$title			= $this->element['label']
						? (string) $this->element['label']
						: ($this->element['title'] ? (string) $this->element['title'] : '');
		$heading		= $this->element['heading'] ? (string) $this->element['heading'] : 'h4';
		$description	= (string) $this->element['infotext'];
		$class			= ! empty($this->class)
						? ' class="' . $this->class . '"'
//						: ' class="alert alert-info input-xxlarge" style="display:block;clear:both;"';
						: '';
//		$close			= (string) $this->element['close'];

		$html = array();

//		if ($close)
//		{
//			$close = $close == 'true' ? 'alert' : $close;
//			$html[] = '<button type="button" class="close" data-dismiss="' . $close . '">&times;</button>';
//		}

//		$html[] = '<div class="ic-clearfix"></div>';
//		$html[] = '<div' . $class . '>';
//		$html[] = ! empty($description) ? JText::_($description) : '';
//		$html[] = '</div>';

		if (version_compare(JVERSION, '4.0', 'lt'))
		{
			$inlineStyle = ' style="min-width: 510px; margin-bottom: 24px; padding: 0; width: max-content;"';
		}
		else
		{
			$inlineStyle = '';
		}

		$html[] = '<div id="ic-sortable" class="controls mb-3"' . $inlineStyle . '>';

		$fieldnames = isset($this->value) ? $this->value : (string) $this->element['default'];
		$fieldnames = explode(',', $fieldnames);

		$group      = $isMenu ? 'params' : '';

		foreach ($fieldnames AS $k => $fieldname)
		{
			$fieldname = trim($fieldname);
			$field  = $this->form->getField($fieldname, $group);

			// Make sure the selected field is hidden
			if ( ! isset($field->element['type']) || $field->element['type'] !== 'hidden')
			{
				$this->form->setFieldAttribute($fieldname, 'type', 'hidden');
			}

			// Settings attributes
			$name = isset($field->element['name']) ? $field->element['name'] : false;

			if ($name)
			{
				$rendertype		= isset($field->element['rendertype'])	? $field->element['rendertype']		: 'text';
				$label			= isset($field->element['label'])		? $field->element['label']			: '';
				$description	= isset($field->element['description'])	? $field->element['description']	: '';
				$class			= isset($field->element['class'])		? $field->element['class']			: '';
				$labelclass		= isset($field->element['labelclass'])	? $field->element['labelclass']		: '';
				$default		= isset($field->element['default'])		? $field->element['default']		: '';

				// Add new field to the sortable list
				$type_field = new SimpleXMLElement('<field />');
				$type_field->addAttribute('name', $fieldname);
				$type_field->addAttribute('type', $rendertype);
				$type_field->addAttribute('label', $label);
				$type_field->addAttribute('description', $description);
				$type_field->addAttribute('class', $class);
				$type_field->addAttribute('labelclass', $labelclass);
				$type_field->addAttribute('default', $default);
//				$type_field->addAttribute('fieldset', 'list');

				if (isset($field->element->option))
				{
					$values = (array) $field->element->xpath('option');
					$options = (array) $field->element->option;
					unset($values['@attributes']);
					unset($options['@attributes']);


					// Add 'Use Global' with value
					if ($field->element['useglobal'])
					{
						$tmp        = new \stdClass;
						$tmp->value = '';
						$tmp->text  = JText::_('JGLOBAL_USE_GLOBAL');
						$component  = JFactory::getApplication()->input->getCmd('option');

						// Get correct component for menu items
						if ($component == 'com_menus')
						{
							$link      = $this->form->getData()->get('link');
							$uri       = new JUri($link);
							$component = $uri->getVar('option', 'com_menus');
						}

						$params = JComponentHelper::getParams($component);
						$value  = $params->get($fieldname);

						// Try with global configuration
						if (is_null($value))
						{
							$value = JFactory::getConfig()->get($fieldname);
						}

						// Try with menu configuration
						if (is_null($value) && JFactory::getApplication()->input->getCmd('option') == 'com_menus')
						{
							$value = JComponentHelper::getParams('com_menus')->get($fieldname);
						}

						if (!is_null($value))
						{
							$value = (string) $value;

							foreach ($values as $key => $option)
							{
								if ((string) $values[$key]['value'] === $value)
								{
									$value = JText::_($option);

									break;
								}
							}

							$tmp->text = JText::sprintf('JGLOBAL_USE_GLOBAL_VALUE', $value);
						}

						$child = $type_field->addChild('option', $tmp->text);
						$child->addAttribute('value', $tmp->value);
					}


					// Add Options
					foreach ($values as $key => $option)
					{
						$child = $type_field->addChild('option', $option);
						$child->addAttribute('value', $values[$key]['value']);
					}
				}

				$this->form->setField($type_field, $group);

				$label = $this->form->getLabel($fieldname, $group);
				$input = $this->form->getInput($fieldname, $group);

				$html[] = '<div id="' . $fieldname . '" class="ui-state-default">';

				// Switch Joomla 3 / 4
				if (version_compare(JVERSION, '4.0', 'lt'))
				{
					$html[] = '<span class="icon-move" style="line-height: 26px; float: left">&nbsp;&nbsp;</span>';
					$html[] = JLayoutHelper::render('joomla.form.renderfield',
								array('input' => $input, 'label' => $label,'name' => 'test', 'options' => array('rel' => ''))); // 'rel' kept for older J3 version (3.2.5...)
				}
				else
				{
//					$html[] = '&nbsp;<span class="icon-move" style="line-height: 36px; float: left">&nbsp;&nbsp;</span>';
					$labelIcon = '<span class="icon-move mx-2 mt-2"></span> ' . $label;
					$html[] = JLayoutHelper::render('joomla.form.renderfield',
								array('input' => $input, 'label' => $labelIcon,'name' => 'test', 'options' => array('rel' => ''))); // 'rel' kept for older J3 version (3.2.5...)
				}

				$html[] = '</div>';

				$label_suffix = '';

				if ($fieldname == 'filter_search')
				{
					$label_suffix = Jtext::_('IC_FIELD_TYPE_TEXT');
				}
				elseif (in_array($fieldname, array('filter_from', 'filter_to')))
				{
					$label_suffix = Jtext::_('IC_FIELD_TYPE_CALENDAR');
				}
				elseif (in_array($fieldname, array('filter_category', 'filter_month', 'filter_year')))
				{
					$label_suffix = Jtext::_('IC_FIELD_TYPE_LIST');
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

		if (version_compare(JVERSION, '4.0', 'ge'))
		{
			$document->addScript('https://code.jquery.com/jquery-1.12.4.min.js');
			$document->addScript('https://code.jquery.com/ui/1.11.4/jquery-ui.min.js');
		}
		else
		{
			JHtml::_('jquery.ui', array('core', 'sortable'));
		}

		return '</div><div>' . implode('', $html);
	}

	public function getInput()
	{
		return '<input type="hidden" id="' . $this->id . '" name="' . $this->name . '" value="' . $this->value . '" />';
	}
}
