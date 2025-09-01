<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.8.0 2022-02-10
 *
 * @package     iCagenda.Admin
 * @subpackage  Utilities.Form.Field
 * @link        https://www.icagenda.com
 *
 * @author      Cyril Rezé
 * @copyright   (c) 2012-2024 Cyril Rezé / iCagenda. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 *
 * @since       3.8
 *----------------------------------------------------------------------------
*/

\defined('JPATH_PLATFORM') or die;

use Joomla\CMS\Factory as JFactory;
use Joomla\CMS\Form\FormField as JFormField;
use Joomla\CMS\HTML\HTMLHelper as JHtml;
use Joomla\CMS\Language\Text as JText;

JHtml::_('stylesheet', 'com_icagenda/icagenda.css', array('relative' => true, 'version' => 'auto'));

// Test if translation is missing, set to en-GB by default
$language = JFactory::getLanguage();
$language->load('com_icagenda', JPATH_ADMINISTRATOR, 'en-GB', true);
$language->load('com_icagenda', JPATH_ADMINISTRATOR, null, true);

/**
 * Form Field class for iCagenda.
 * Supports a label/description with optional icon as a text field separator.
 */
class icagendaFormFieldSeparator extends JFormField
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 */
	protected $type = 'Separator';

	/**
	 * Hide the label when rendering the form field.
	 *
	 * @var    boolean
	 */
	protected $hiddenLabel = true;

	/**
	 * Hide the description when rendering the form field.
	 *
	 * @var    boolean
	 */
	protected $hiddenDescription = true;

	/**
	 * Method to get the field label markup.
	 *
	 * @return  string  The field label markup.
	 */
	protected function getLabel()
	{
		$separatorType = $this->element['separatortype'];
		$class         = $this->element['class'] ? ' class="' . $this->element['class'] . '"' : '';

		if (version_compare(JVERSION, '4.0', 'lt'))
		{
			JFactory::getDocument()->addStyleDeclaration('.ic-lead{padding:0.75rem;width:auto;}summary{font-weight:bolder;padding-top:3px;padding-bottom:5px;cursor:pointer;}');
			$emptyHR = '<div>&nbsp;</div>';
		}
		else
		{
			$emptyHR = '';
		}

		if (empty($this->element['label']) && empty($this->element['description']))
//		if (empty($this->element['label']))
		{
			return '';
		}

		$html = [];

		if ($separatorType == 'subheader')
		{
			$label      = $this->element['label'] ? (string) $this->element['label'] : ($this->element['title'] ? (string) $this->element['title'] : '');
			$title      = $this->element['labelclass'] ? '<span class="' . $this->element['labelclass'] . '">' . JText::_($label) . '</span>' : JText::_($label);
			$hr         = $this->element['hr'];
			$class      = $this->element['class'] ? ' class="' . $this->element['class'] . '"' : '';

			$html[] = !empty($hr) ? '<hr>' : $emptyHR;
			$html[] = !empty($title) ? $title : '';
		}
		elseif ($separatorType == 'note')
		{
			if (version_compare(JVERSION, '4.0', 'lt'))
			{
				$dft_alert   = (strpos($this->element['class'], 'alert') === false) ? ' alert alert-info' : '';
				$class       = ' class="' . $this->element['class'] . ' ic-separator-input' . $dft_alert . '"';
				$hiddenClass = ' class="hidden"';
			}
			else
			{
				$class       = $this->element['class'] ? ' class="' . $this->element['class'] . '"' : '';
				$hiddenClass = ' class="hidden"';
			}

			$label      = $this->element['label'] ? (string) $this->element['label'] : ($this->element['title'] ? (string) $this->element['title'] : '');
			$title      = $this->element['labelclass'] ? '<span class="' . $this->element['labelclass'] . '">' . JText::_($label) . '</span>' : JText::_($label);
			$iconPrefix = $this->element['iconprefix'] ? $this->element['iconprefix'] : 'icon-';
			$icon       = (string) $this->element['icon'];
			$iconClass  = $this->element['iconclass'] ? ' ' . (string) $this->element['iconclass'] : '';

			$html[] = '<div class="hidden">&nbsp;</div>';
			$html[] = !empty($icon) ? '<span class="' . $iconPrefix . $icon . $iconClass . '"></span>&ensp;' : '';
			$html[] = !empty($title) ? $title : '';
		}
		elseif ($separatorType == 'details')
		{
			$class = '';
		
			$html[] = '<div class="control-label"> </div>';
		}
		elseif ($separatorType == 'information')
		{
			$class = '';
		
			$html[] = '<div class="control-label"></div>';
		}
		else
		{
			// separatorType is 'header' by default.
			if ($this->element['imagelabel'])
			{
				$label      = $this->element['label'] ? (string) $this->element['label'] : ($this->element['title'] ? (string) $this->element['title'] : '');
				$imageClass = $this->element['imageclass'] ? ' class="' . (string) $this->element['imageclass'] . '"' : '';
				$image      = '<img' . $imageClass . ' src="' . JUri::root() . 'media/com_icagenda/images/' . $this->element['imagelabel'] . '.png" width="270" height="48" alt="' . JText::_($label) . '" />';
				$title      = $image;
			}
			else
			{
				$label = $this->element['label'] ? (string) $this->element['label'] : ($this->element['title'] ? (string) $this->element['title'] : '');
				$title = $this->element['labelclass'] ? '<span class="' . $this->element['labelclass'] . '">' . JText::_($label) . '</span>' : JText::_($label);
			}

			$class      = $this->element['class'] ? ' class="' . $this->element['class'] . '"' : '';
			$iconPrefix = $this->element['iconprefix'] ? $this->element['iconprefix'] : 'icon-';
			$icon       = (string) $this->element['icon'];
			$iconClass  = $this->element['iconclass'] ? ' ' . (string) $this->element['iconclass'] : '';

//			$heading     = $this->element['heading'] ? (string) $this->element['heading'] : 'h4';
//			$description = (string) $this->element['description'];

//			$html[]      = !empty($title) ? '<' . $heading . '>' . $title . '</' . $heading . '>' : '';
//			$html[]      = !empty($description) ? Text::_($description) : '';

			$html[] = !empty($icon) ? '<span class="' . $iconPrefix . $icon . $iconClass . '"></span>&ensp;' : '';
			$html[] = !empty($title) ? $title : '';

			$position = $this->element['position'];

			if ($position == 'label-desc' && version_compare(JVERSION, '4.0', 'lt'))
			{
				return '<div ' . $class . '>' . implode('', $html) . '</div>';
			}			
		}

		return '</div><div ' . $class . '>' . implode('', $html);
	}

	/**
	 * Method to get the field input markup.
	 *
	 * @return  string  The field input markup.
	 */
	protected function getInput()
	{
		if (version_compare(JVERSION, '4.0', 'lt'))
		{
			$class  = ' class="alert alert-info ic-separator-input"';
		}
		else
		{
			$class = $this->element['class'] ? ' class="' . $this->element['class'] . '"' : '';
		}

		$html = [];

		$separatorType = $this->element['separatortype'];

		if ($separatorType == 'details')
		{
			$label       = $this->element['label'] ? (string) $this->element['label'] : ($this->element['title'] ? (string) $this->element['title'] : '');
			$title       = $this->element['labelclass'] ? '<span class="' . $this->element['labelclass'] . '">' . JText::_($label) . '</span>' : JText::_($label);
			$description = (string) $this->element['description'];
			$hr          = $this->element['hr'];

			$html[] = '<details' . $class . '>';
			$html[] = '<summary class="rule-notes">';
			$html[] = $title;
			$html[] = '</summary>';
			$html[] = '<div class="rule-notes">';
			$html[] = !empty($description) ? JText::_($description) : '';
			$html[] = '</div>';
			$html[] = '</details>';
		}
		elseif ($separatorType == 'information')
		{
			$label       = (string) $this->element['label'] ? JText::_($this->element['label']) : '';
			$description = (string) $this->element['description'];
			$hr          = $this->element['hr'];
			$labelClass  = $this->element['labelclass'] ? ' class="' . $this->element['labelclass'] . '"' : '';
			$iconPrefix = $this->element['iconprefix'] ? $this->element['iconprefix'] : 'icon-';
			$icon       = (string) $this->element['icon'] ? $this->element['icon'] : '';
			$iconClass  = $this->element['iconclass'] ? ' ' . (string) $this->element['iconclass'] : '';

			$html[] = '<div' . $class . '>';
			$html[] = !empty($label) ? '<label' . $labelClass . '>' : '';
			$html[] = !empty($icon) ? '<span class="' . $iconPrefix . $icon . $iconClass . '"></span>&ensp;' : '';
			$html[] = !empty($label) ? $label : '';
			$html[] = !empty($label) ? '</label>' : '';
			$html[] = '<div>';
			$html[] = !empty($description) ? JText::_($description) : '';
			$html[] = '</div>';
			$html[] = '</div>';
		}
		else
		{
			$position = $this->element['position'];
			$description = (string) $this->element['description'];

			if ($description == 'COM_ICAGENDA_COMPONENT_DESC')
			{
				$desc = '<p class="ms-5 ms-sm-0"><strong>';
//				$desc.= '<span class="fs-2">iCagenda</span>';
//				$desc.= '<br />';
				$desc.= '<span class="fs-5">' . JText::_($description) . '</span>';
				$desc.= '<br />';
				$desc.= '<a href="https://www.icagenda.com" target="_blank">www.icagenda.com</a>';
				$desc.= '</strong></p>';				
			}
			else
			{
				$desc = !empty($description) ? JText::_($description) : '';
			}

			if ($position == 'label-desc')
			{
//				$html[] = '<div' . $class . '>';
				$html[] = $desc;
//				$html[] = '</div>';
			}			
		}

		return implode('', $html);
	}
}
