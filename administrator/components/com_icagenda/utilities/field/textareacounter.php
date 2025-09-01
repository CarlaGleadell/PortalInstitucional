<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.8.19 2023-10-19
 *
 * @package     iCagenda.Admin
 * @subpackage  Utilities.Form
 * @link        https://www.icagenda.com
 *
 * @author      Cyril Rezé
 * @copyright   (c) 2012-2024 Cyril Rezé / iCagenda. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 *
 * @since       3.8.0
 *----------------------------------------------------------------------------
*/

defined('_JEXEC') or die;

use iClib\Utf8\Utf8 as iCUtf8;

JFormHelper::loadFieldClass('textarea');

JHtml::_('script', 'com_icagenda/icform.js', array('relative' => true, 'version' => 'auto'), array('async' => 'async'));

/**
 * Textarea with a live character count limit.
 *
 * @since   3.8.0
 */
class icagendaFormFieldTextareaCounter extends JFormFieldTextarea
{
	protected $type = 'textareacounter';

	protected function getInput()
	{
		$app = JFactory::getApplication();

		// Get characters limit setting
		$params	= $app->isClient('administrator')
				? JComponentHelper::getParams('com_icagenda')
				: $app->getParams();

		$name = $this->fieldname;

		if ($name == 'shortdesc')
		{
			$ic_max = $params->get('char_limit_short_description', '100');
		}
		elseif ($name == 'metadesc')
		{
			$ic_max = $params->get('char_limit_meta_description', '320');
		}
		else
		{
			$ic_max = $params->get('ShortDescLimit', '100');
		}

		// Set maxLength for textarea
		$this->maxlength = $ic_max;

		// Trim the trailing line in the layout file
		$textarea = rtrim($this->getRenderer($this->layout)->render($this->getLayoutData()), PHP_EOL);

		// Set remaining number of characters.
		$nb_chars    = strlen(trim(iCUtf8::utf8_decode($this->value)));
		$count_value = $nb_chars ? ($ic_max - $nb_chars) : $ic_max;

		// Live counter HTML
		$livecounter = '<div id="' . $this->id . '_livecounter" class="ic-livecounter" style="display: inline-block;">' . $count_value . '</div>';

		// Script
		$script = 'document.addEventListener("DOMContentLoaded", function() {' . "\n";
		$script.= "\t" . 'var ' . $this->id . '_textarea = document.getElementById("' . $this->id . '");' . "\n";

		// Update counter on input value
		$script.= "\t" . $this->id . '_textarea.oninput = function() {' . "\n";
		$script.= "\t\t" . 'this.onkeyup = this.onkeypress = this.onkeydown = this.onmouseout = null;' . "\n";
		$script.= "\t\t" . 'iCliveCounter.call(this);' . "\n";
		$script.= "\t" . '};' . "\n";

		// Fallbacks oninput
		$script.= "\t" . $this->id . '_textarea.onkeyup = function() { iCliveCounter.call(this); };' . "\n";
		$script.= "\t" . $this->id . '_textarea.onkeypress = function() { iCliveCounter.call(this); };' . "\n";
		$script.= "\t" . $this->id . '_textarea.onkeydown = function() { iCliveCounter.call(this); };' . "\n";
		$script.= "\t" . $this->id . '_textarea.onmouseout = function() { iCliveCounter.call(this); };' . "\n";

		$script.= '});' . "\n\n";

		JFactory::getDocument()->addScriptDeclaration($script);

		// Alert if text stored in the database exceeds the character limit currently set.
		$alert = '';

		if ($nb_chars > $ic_max
			&& $app->isClient('administrator'))
		{
			$alert  = '<div class="alert alert-danger">'
					. '<h3>' . JText::_('WARNING') . '</h3>'
					. '<strong>' . JText::sprintf('COM_ICAGENDA_ALERT_S_TEXT_S_EXCEEDS_CHARACTER_LIMIT', $this->title) . '</strong><br />'
					. JText::_('COM_ICAGENDA_ALERT_EDIT_TEXT_TO_FIT_CHAR_LIMIT') . '<br /><br />'
					. '<u>' . JText::sprintf('COM_ICAGENDA_ALERT_S_TEXT_S_CURRENTLY_STORED_IN_DATABASE', $this->title) . '</u> :<br/>'
					. '<i>' . $this->value . '</i>'
					. '</div>';
		}

		// Maximum characters live counter
		$counter = '<div id="' . $this->id . '-counter-container" class="ic-counter-container">';
		$counter.= '<div class="ic-counter">';
		$counter.= JText::sprintf('COM_ICAGENDA_MAXIMUM_N_CHARACTERS', $ic_max);
		$counter.= '</div> ';
		$counter.= '<div class="ic-counter">';
		$counter.= JText::sprintf('COM_ICAGENDA_N_REMAINING', $livecounter);
		$counter.= '</div>';
		$counter.= '</div>';
		$counter.= '<div>&nbsp;</div>';

		return $alert . $textarea . $counter;

		return $html;
	}
}
