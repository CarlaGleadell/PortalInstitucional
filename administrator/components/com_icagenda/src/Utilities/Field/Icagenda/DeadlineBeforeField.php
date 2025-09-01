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
use Joomla\CMS\Form\Field\ListField;
use Joomla\CMS\HTML\HTMLHelper;

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

/**
 * Deadline Before Form Field
 */
class DeadlineBeforeField extends ListField
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 */
	protected $type = 'DeadlineBefore';

	/**
	 * Method to get the field input markup.
	 *
	 * @return  string  The field input markup.
	 */
	protected function getLabel()
	{
		return;
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
			function deadlineLabel(e) {
				const deadlineLabel = document.getElementById("deadline-label");
				if (e.value !== "") {
					deadlineLabel.classList.remove("text-muted");
					deadlineLabel.classList.add("text-success", "ic-bolder");
				} else {
					deadlineLabel.classList.remove("text-success", "ic-bolder");
					deadlineLabel.classList.add("text-muted");
				}
			}
		');

		$labelClass = (isset($this->value) && $this->value != '')
					? 'text-success ic-bolder'
					: 'text-muted';

		// Initialize the field attributes.
		$attr = !empty($this->class) ? ' class="form-select ' . $this->class . '"' : ' class="form-select"';
		$attr.= !empty($this->size) ? ' size="' . $this->size . '"' : '';
		$attr.= !empty($this->description) ? ' aria-describedby="' . $this->name . '-desc"' : '';
		$attr.= ' onchange="deadlineLabel(this)"';

		$html = '<div class="row">';
		$html.= '<div class="col-12 col-xxl-3 py-2 px-4">';

		$html.= '<label id="deadline-label" class="' . $labelClass . '">' . $this->title . '</label>';
		$html.= '</div>';

		$options = (array) $this->getOptions();

		$listOptions = [
			'option.key'     => 'value',
			'option.text'    => 'text',
			'list.select'    => $this->value,
			'id'             => $this->id,
			'list.translate' => false,
			'option.attr'    => 'optionattr',
			'list.attr'      => trim($attr),
		];

		$html.= '<div class="col-12 col-xxl-9">';
		$html.= HTMLHelper::_('select.genericlist', $options, $this->name, $listOptions);
		$html.= '</div>';
		$html.= '</div>';

		return $html;
	}
}
