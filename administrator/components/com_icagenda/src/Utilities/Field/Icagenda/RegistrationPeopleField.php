<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.9.0 2024-02-19
 *
 * @package     iCagenda.Admin
 * @subpackage  Utilities.Field.Icagenda
 * @link        https://www.icagenda.com
 *
 * @author      Cyril Rezé
 * @copyright   (c) 2012-2024 Cyril Rezé / iCagenda. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 *
 * @since       3.6.0
 *----------------------------------------------------------------------------
*/

namespace iCutilities\Field\Icagenda;

use Joomla\CMS\Factory;
use Joomla\CMS\Form\Field\ListField;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Plugin\PluginHelper;
use WebiC\Component\iCagenda\Administrator\Model\RegistrationModel;
use WebiC\Component\iCagenda\Site\Model\RegistrationModel as SiteRegistrationModel;

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

/**
 * Tickets (people) List Form Field class for iCagenda.
 * Registration form :  Nb of people select form field
 */
class RegistrationPeopleField extends ListField
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 */
	protected $type = 'registrationpeople';

	/**
	 * The active item
	 *
	 * @var  object
	 */
	protected $item;

	/**
	 * Constructor
	 */
	public function __construct()
	{
		parent::__construct();

		// Load item object from model.
		$model  = (Factory::getApplication()->isClient('site')
				? new SiteRegistrationModel
				: new RegistrationModel);

		$this->item = $model->getItem();
	}

	/**
	 * Method to get the field label markup.
	 *
	 * @return  string  The field label markup.
	 */
	protected function getLabel()
	{
		$item = $this->item;

		$tickets = isset($item->default_tickets) ? $item->default_tickets : $item->tickets;

		if ($tickets == 1) {
			return '';
		} else {
			$labelclass = !empty($this->labelclass) ? ' class="' . $this->labelclass . '"' : '';

			$html = '';

			$html.= '<span class="spacer">';
			$html.= '<span class="before"></span>';
			$html.= '<span' . $labelclass . '>';

			$label = '';

			// Get the label text from the XML element, defaulting to the element name.
			$text = $this->element['label'] ? (string) $this->element['label'] : (string) $this->element['name'];
			$text = $this->translateLabel ? Text::_($text) : $text;

			// Build the class for the label.
			$class = $this->required == true ? ' required' : '';

			// Add the opening label tag and main attributes attributes.
			$label.= '<label id="' . $this->id . '-lbl" class="' . $class . '"';

			// Add the label text and closing tag.
			$label .= '> ' . $text . '</label>';
			$html.= $label;

			$html.= '</span>';
			$html.= '<span class="after"></span>';
			$html.= '</span>';

			return $html;
		}
	}

	/**
	 * Method to get the field input markup.
	 *
	 * @return  string  The field input markup.
	 */
	protected function getInput()
	{
		$item = $this->item;

		$tickets = isset($item->default_tickets) ? $item->default_tickets : $item->tickets;
		$class   = !empty($this->class) ? ' class="' . $this->class . '"' : '';

		$options = [];
		$i = '';

		// @todo: migrate to Vanilla
		HTMLHelper::_('jquery.framework');

		// Ajax update of number of tickets bookable per registration, depending on date selected.
		Factory::getDocument()->addScriptDeclaration('
			jQuery(document).ready(function($) {
				var date = $("#jform_date");
				$(date).change(function() {
					var selectedDate = $(date).val().replace(/ /g, "space").replace(/:/g, "_");
					$.ajax({
						type: "POST",
						url: "index.php?option=com_icagenda&task=registration.ticketsBookable",
						data: {
								eventID: "' . $item->id . '",
								regDate: selectedDate,
								typeReg: "' . $item->params->get('typeReg', '1') . '",
								maxReg: "' . $item->params->get('maxReg', '1000000') . '",
								tickets: "' . $item->tickets . '"
							},
						dataType: "text"
					})
					.done(function( data ) {
						var tickets = parseInt(data),
							sel = $( "#' . $this->id . '" );
						sel.empty();
						for (var i = 1; i <= tickets; i++) {
							$selected = (i == "' . $this->value . '") ? " selected" : "";
							sel.append($("<option"+$selected+">").attr("value",i).text(i));
						}
					})
					.fail(function( data ) {
						if ( data.responseCode ) {
							console.log( data.responseCode );
						}
					});
				})
			});
		');

		for ($i = 1; $i <= $tickets; $i++) {
			$options[] = HTMLHelper::_('select.option', $i, $i);
		}

		$html = '';

		if ($tickets > 1) {
			$html.= HTMLHelper::_('select.genericlist', $options, $this->name, $class, 'value', 'text', $this->value, $this->id);
		} else {
			$html.= '<input type="hidden" name="' . $this->name . '" value="1" />';
		}

		if ($item->params->get('registration_actions', 0)) {
			PluginHelper::importPlugin('icagenda');

			$extraLayout = Factory::getApplication()->triggerEvent('onICagendaRegistrationActionsFieldPeople', array('com_icagenda.registration', &$item));

			$html.= trim(implode("\n", $extraLayout));
		}

		return $html;
	}
}
