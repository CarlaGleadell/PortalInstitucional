<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.9.0 2024-02-18
 *
 * @package     iCagenda.Admin
 * @subpackage  Utilities.Form
 * @link        https://www.icagenda.com
 *
 * @author      Cyril Rezé
 * @copyright   (c) 2012-2024 Cyril Rezé / iCagenda. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 *
 * @since       3.8
 *----------------------------------------------------------------------------
*/

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;

JFormHelper::loadFieldClass('list');

/**
 * Deadline Before Form Field
 */
class icagendaFormFieldDeadlineBefore extends JFormFieldList
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
		$rowJ3 = (version_compare(JVERSION, '4.0', 'lt')) ? '-fluid' : '';

		$script = '
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
		';
		
		Factory::getDocument()->addScriptDeclaration($script);

		$labelClass = (isset($this->value) && $this->value != '')
					? 'text-success ic-bolder'
					: 'text-muted';

		$attr = '';

		// Initialize the field attributes.
		$attr .= !empty($this->class) ? ' class="form-select ' . $this->class . '"' : ' class="form-select"';
		$attr .= !empty($this->size) ? ' size="' . $this->size . '"' : '';
		$attr .= !empty($this->description) ? ' aria-describedby="' . $this->name . '-desc"' : '';
		$attr .= ' onchange="deadlineLabel(this)"';

		$html = '<div class="row' . $rowJ3 . '">';
		$html.= '<div class="col-12 col-xxl-3 py-2 px-4">';

		$html.= '<label id="deadline-label" class="' . $labelClass . '">' . $this->title . '</label>';
		$html.= '</div>';

		$options = (array) $this->getOptions();

		$listoptions = array();
		$listoptions['option.key'] = 'value';
		$listoptions['option.text'] = 'text';
		$listoptions['list.select'] = $this->value;
		$listoptions['id'] = $this->id;
		$listoptions['list.translate'] = false;
		$listoptions['option.attr'] = 'optionattr';
		$listoptions['list.attr'] = trim($attr);
		$html.= '<div class="col-12 col-xxl-9">';
		$html.= HTMLHelper::_('select.genericlist', $options, $this->name, $listoptions);
		$html.= '</div>';
		$html.= '</div>';

		return $html;
	}
}
