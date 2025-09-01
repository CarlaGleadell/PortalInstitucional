<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.9.5 2024-07-18
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
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Uri\Uri;

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

HTMLHelper::_('stylesheet', 'com_icagenda/icagenda.css', array('relative' => true, 'version' => 'auto'));

// Test if translation is missing, set to en-GB by default
$language = Factory::getLanguage();
$language->load('com_icagenda', JPATH_ADMINISTRATOR, 'en-GB', true);
$language->load('com_icagenda', JPATH_ADMINISTRATOR, null, true);

/**
 * Separator Form Field class for iCagenda.
 * Supports a label/description with optional icon as a text field separator.
 */
class SeparatorField extends FormField
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

		if (empty($this->element['label']) && empty($this->element['description'])) {
			return '';
		}

		$html = '';

		switch ($separatorType) {
			case 'label':
			case 'subheader':
				$label      = $this->element['label'] ? (string) $this->element['label'] : ($this->element['title'] ? (string) $this->element['title'] : '');
				$title      = $this->element['labelclass'] ? '<span class="' . $this->element['labelclass'] . '">' . Text::_($label) . '</span>' : Text::_($label);
				$hr         = $this->element['hr'];
				$class      = $this->element['class'] ? ' class="' . $this->element['class'] . '"' : '';

				$html.= !empty($hr) ? '<hr>' : '';
				$html.= !empty($title) ? $title : '';
				break;

			case 'note':
				$class       = $this->element['class'] ? ' class="' . $this->element['class'] . '"' : '';
				$hiddenClass = ' class="hidden"';

				$label      = $this->element['label'] ? (string) $this->element['label'] : ($this->element['title'] ? (string) $this->element['title'] : '');
				$title      = $this->element['labelclass'] ? '<span class="' . $this->element['labelclass'] . '">' . Text::_($label) . '</span>' : Text::_($label);
				$iconPrefix = $this->element['iconprefix'] ? $this->element['iconprefix'] : 'icon-';
				$icon       = (string) $this->element['icon'];
				$iconClass  = $this->element['iconclass'] ? ' ' . (string) $this->element['iconclass'] : '';

				$html.= '<div class="hidden">&nbsp;</div>';
				$html.= !empty($icon) ? '<span class="' . $iconPrefix . $icon . $iconClass . '"></span>&ensp;' : '';
				$html.= !empty($title) ? $title : '';
				break;

			case 'description':
			case 'details':
			case 'information':
				$class = '';
				$html.= '<div class="control-label"> </div>';
				break;

			default:
				// separatorType is 'header' by default.
				if ($this->element['imagelabel']) {
					$label      = $this->element['label'] ? (string) $this->element['label'] : ($this->element['title'] ? (string) $this->element['title'] : '');
					$imageClass = $this->element['imageclass'] ? ' class="' . (string) $this->element['imageclass'] . '"' : '';
					$image      = '<img' . $imageClass . ' src="' . Uri::root() . 'media/com_icagenda/images/' . $this->element['imagelabel'] . '.png" width="270" height="48" alt="' . Text::_($label) . '" />';
					$title      = $image;
				} else {
					$label = $this->element['label'] ? (string) $this->element['label'] : ($this->element['title'] ? (string) $this->element['title'] : '');
					$title = $this->element['labelclass'] ? '<span class="' . $this->element['labelclass'] . '">' . Text::_($label) . '</span>' : Text::_($label);
				}

				$class      = $this->element['class'] ? ' class="' . $this->element['class'] . '"' : '';
				$iconPrefix = $this->element['iconprefix'] ? $this->element['iconprefix'] : 'icon-';
				$icon       = (string) $this->element['icon'];
				$iconClass  = $this->element['iconclass'] ? ' ' . (string) $this->element['iconclass'] : '';

//				$heading     = $this->element['heading'] ? (string) $this->element['heading'] : 'h4';
//				$description = (string) $this->element['description'];

//				$html.= !empty($title) ? '<' . $heading . '>' . $title . '</' . $heading . '>' : '';
//				$html.= !empty($description) ? Text::_($description) : '';

				$html.= !empty($icon) ? '<span class="' . $iconPrefix . $icon . $iconClass . '"></span>&ensp;' : '';
				$html.= !empty($title) ? $title : '';

				$position = $this->element['position'];

		}

		return '</div><div ' . $class . '>' . $html;
	}

	/**
	 * Method to get the field input markup.
	 *
	 * @return  string  The field input markup.
	 */
	protected function getInput()
	{
		$separatorType = $this->element['separatortype'];
		$class         = $this->element['class'] ? ' class="' . $this->element['class'] . '"' : '';

		$html = '';

		switch ($separatorType) {
			case 'details':
				$label       = $this->element['label'] ? (string) $this->element['label'] : ($this->element['title'] ? (string) $this->element['title'] : '');
				$title       = $this->element['labelclass'] ? '<span class="' . $this->element['labelclass'] . '">' . Text::_($label) . '</span>' : Text::_($label);
				$description = (string) $this->element['description'];
				$hr          = $this->element['hr'];

				$html.= '<details' . $class . '>'
					. '<summary class="rule-notes">'
					. $title
					. '</summary>'
					. '<div class="rule-notes">'
					. (!empty($description) ? Text::_($description) : '')
					. '</div>'
					. '</details>';
				break;

			case 'description':
			case 'information':
				$label       = (string) $this->element['label'] ? Text::_($this->element['label']) : '';
				$description = (string) $this->element['description'];
				$hr          = $this->element['hr'];
				$labelClass  = $this->element['labelclass'] ? ' class="' . $this->element['labelclass'] . '"' : '';
				$iconPrefix = $this->element['iconprefix'] ? $this->element['iconprefix'] : 'icon-';
				$icon       = (string) $this->element['icon'] ? $this->element['icon'] : '';
				$iconClass  = $this->element['iconclass'] ? ' ' . (string) $this->element['iconclass'] : '';

				$html.= '<div' . $class . '>'
					. (!empty($label) ? '<label' . $labelClass . '>' : '')
					. (!empty($icon) ? '<span class="' . $iconPrefix . $icon . $iconClass . '"></span>&ensp;' : '')
					. (!empty($label) ? $label : '')
					. (!empty($label) ? '</label>' : '')
					. '<div>'
					. (!empty($description) ? Text::_($description) : '')
					. '</div>'
					. '</div>';
				break;

			default:
				$position = $this->element['position'];
				$description = (string) $this->element['description'];

				if ($description == 'COM_ICAGENDA_COMPONENT_DESC') {
					$desc = '<p class="ms-5 ms-sm-0"><strong>';
					$desc.= '<span class="fs-5">' . Text::_($description) . '</span>';
					$desc.= '<br />';
					$desc.= '<a href="https://www.icagenda.com" target="_blank">www.icagenda.com</a>';
					$desc.= '</strong></p>';
				} else {
					$desc = !empty($description) ? Text::_($description) : '';
				}

				if ($position == 'label-desc') {
					$html.= $desc;
				}
		}

		return $html;
	}
}
