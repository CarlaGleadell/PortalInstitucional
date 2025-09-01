<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.8.0 2021-11-16
 *
 * @package     iCagenda.Admin
 * @subpackage  Utilities.Form
 * @link        https://www.icagenda.com
 *
 * @author      Cyril Rezé
 * @copyright   (c) 2012-2024 Cyril Rezé / iCagenda. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 *
 * @since       3.6.0
 *----------------------------------------------------------------------------
*/

defined('_JEXEC') or die;

use WebiC\Component\iCagenda\Administrator\Model\RegistrationModel;
use WebiC\Component\iCagenda\Site\Model\RegistrationModel as SiteRegistrationModel;
use Joomla\CMS\Plugin\PluginHelper;

//jimport('joomla.form.formfield');
JFormHelper::loadFieldClass('list');

/**
 * Application :        SITE
 * Registration form :  Nb of people select form field
 */
class icagendaFormFieldRegistrationPeople extends JFormFieldList
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 * @since   3.6.0
	 */
	protected $type = 'registrationpeople';

	/**
	 * Method to get the field label markup.
	 *
	 * @return  string  The field label markup.
	 * @since   3.8.0
	 */
	protected function getLabel()
	{
		$app = JFactory::getApplication();

		$model  = (version_compare(JVERSION, '4.0', 'lt'))
				? new iCagendaModelRegistration
				: ($app->isClient('site') ? new SiteRegistrationModel : new RegistrationModel);

		$item    = $model->getItem();
		$tickets = isset($item->default_tickets) ? $item->default_tickets : $item->tickets;

		if ($tickets == 1)
		{
			return '';
		}
		else
		{
			$html = array();
			$class = !empty($this->labelclass) ? ' class="' . $this->labelclass . '"' : '';
			$html[] = '<span class="spacer">';
			$html[] = '<span class="before"></span>';
			$html[] = '<span' . $class . '>';

			$label = '';

			// Get the label text from the XML element, defaulting to the element name.
			$text = $this->element['label'] ? (string) $this->element['label'] : (string) $this->element['name'];
			$text = $this->translateLabel ? JText::_($text) : $text;

			// Build the class for the label.
			$class = ( ! empty($this->description) && version_compare(JVERSION, '4.0', 'lt')) ? 'hasPopover' : '';
			$class = $this->required == true ? $class . ' required' : $class;

			// Add the opening label tag and main attributes attributes.
			$label .= '<label id="' . $this->id . '-lbl" class="' . $class . '"';

			// If a description is specified, use it to build a tooltip.
			if ( ! empty($this->description) && version_compare(JVERSION, '4.0', 'lt'))
			{
				JHtml::_('bootstrap.popover');
				$label .= ' title="' . htmlspecialchars(trim($text, ':'), ENT_COMPAT, 'UTF-8') . '"';
				$label .= ' data-content="' . htmlspecialchars(
					$this->translateDescription ? JText::_($this->description) : $this->description,
					ENT_COMPAT,
					'UTF-8'
				) . '"';

				if (JFactory::getLanguage()->isRtl())
				{
					$label .= ' data-placement="left"';
				}
			}

			// Add the label text and closing tag.
			$label .= '> ' . $text . '</label>';
			$html[] = $label;

			$html[] = '</span>';
			$html[] = '<span class="after"></span>';
			$html[] = '</span>';

			return implode('', $html);
		}
	}

	/**
	 * Method to get the field input markup.
	 *
	 * @return  string  The field input markup.
	 * @since   3.6.0
	 */
	protected function getInput()
	{
		$app = JFactory::getApplication();

		$model  = (version_compare(JVERSION, '4.0', 'lt'))
				? new iCagendaModelRegistration
				: ($app->isClient('site') ? new SiteRegistrationModel : new RegistrationModel);

		$item    = $model->getItem();
		$tickets = isset($item->default_tickets) ? $item->default_tickets : $item->tickets;
		$class   = !empty($this->class) ? ' class="' . $this->class . '"' : '';

		$options = array();
		$i = '';

		// @todo: migrate to Vanilla
		JHtml::_('jquery.framework');

		// Ajax update of number of tickets bookable per registration, depending on date selected.
		JFactory::getDocument()->addScriptDeclaration('
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

		for ($i = 1; $i <= $tickets; $i++)
		{
			$options[] = JHtml::_('select.option', $i, $i);
		}

		$html = '';

		if ($tickets > 1)
		{
			$html.= JHtml::_('select.genericlist', $options, $this->name, $class, 'value', 'text', $this->value, $this->id);
		}
		else
		{
			$html.= '<input type="hidden" name="' . $this->name . '" value="1" />';
		}

		if ($item->params->get('registration_actions', 0))
		{
			if (version_compare(JVERSION, '4.0', 'lt'))
			{
				JPluginHelper::importPlugin('icagenda');

				$dispatcher  = JEventDispatcher::getInstance();
				$extraLayout = $dispatcher->trigger('onICagendaRegistrationActionsFieldPeople', array('com_icagenda.registration', &$item));
			}
			else
			{
				PluginHelper::importPlugin('icagenda');

				$extraLayout = $app->triggerEvent('onICagendaRegistrationActionsFieldPeople', array('com_icagenda.registration', &$item));
			}

			$html.= trim(implode("\n", $extraLayout));
		}

		return $html;
	}
}
