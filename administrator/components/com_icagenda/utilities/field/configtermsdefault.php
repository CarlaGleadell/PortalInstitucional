<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.8.0 2021-09-29
 *
 * @package     iCagenda.Admin
 * @subpackage  Utilities.Form
 * @link        https://www.icagenda.com
 *
 * @author      Cyril Rezé
 * @copyright   (c) 2012-2024 Cyril Rezé / iCagenda. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 *
 * @since       3.7
 *----------------------------------------------------------------------------
*/

defined('_JEXEC') or die;

JFormHelper::loadFieldClass('list');

/**
 * Registration form: Terms and Conditions - By Default (ADMIN)
 */
class icagendaFormFieldConfigTermsDefault extends JFormField
{
	/**
	 * The form field type.
	 */
	protected $type = 'configtermsdefault';

	/**
	 * Method to create a blank label.
	 */
	protected function getLabel()
	{
		return ' ';
	}

	/**
	 * Method to get the field input markup.
	 */
	protected function getInput()
	{
		$defaultConstant = $this->element['constant'];

		$html = array();

		$html[] = '<fieldset class="span9 iCleft">';
		$html[] = '<div class="alert alert-error">';
		$html[] = '<i class="icon-warning-2"></i> ' . JText::sprintf('COM_ICAGENDA_TERMS_IMPORTANT_INFOS', $defaultConstant) . '</div>';
		$html[] = '<div>' . JText::_('COM_ICAGENDA_SUBMIT_TOS_TYPE_DEFAULT_LBL') . '<br /><small>' . $defaultConstant . '</small></div><br />';
		$html[] = '<div class="alert alert-info">' . JText::_($defaultConstant) . '</div>';
		$html[] = '</fieldset>';

		return implode("\n", $html);
	}
}
