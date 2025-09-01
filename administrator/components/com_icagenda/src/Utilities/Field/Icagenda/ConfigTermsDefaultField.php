<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.9.0 2024-02-17
 *
 * @package     iCagenda.Admin
 * @subpackage  Utilities.Field.Icagenda
 * @link        https://www.icagenda.com
 *
 * @author      Cyril Rezé
 * @copyright   (c) 2012-2024 Cyril Rezé / iCagenda. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 *
 * @since       3.7
 *----------------------------------------------------------------------------
*/

namespace iCutilities\Field\Icagenda;

use Joomla\CMS\Form\FormField;
use Joomla\CMS\Language\Text;

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

/**
 * Registration form: Terms and Conditions - By Default (ADMIN)
 */
class ConfigTermsDefaultField extends FormField
{
	/**
	 * The form field type.
	 */
	protected $type = 'ConfigTermsDefault';

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

		$html = '<fieldset>'
			. '<div class="alert alert-error">'
			. '<span class="icon-warning-2"></span> ' . Text::sprintf('COM_ICAGENDA_TERMS_IMPORTANT_INFOS', $defaultConstant)
			. '</div>'
			. '<p>' . Text::_('COM_ICAGENDA_SUBMIT_TOS_TYPE_DEFAULT_LBL') . '<br /><small>' . $defaultConstant . '</small></p>'
			. '<div class="alert alert-info">' . Text::_($defaultConstant) . '</div>'
			. '</fieldset>';

		return $html;
	}
}
