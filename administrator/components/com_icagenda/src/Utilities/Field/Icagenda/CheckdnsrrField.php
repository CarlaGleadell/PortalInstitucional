<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.9.0 2024-02-20
 *
 * @package     iCagenda.Admin
 * @subpackage  Utilities.Field.Icagenda
 * @link        https://www.icagenda.com
 *
 * @author      Cyril Rezé
 * @copyright   (c) 2012-2024 Cyril Rezé / iCagenda. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 *
 * @since       3.1.7
 *----------------------------------------------------------------------------
*/

namespace iCutilities\Field\Icagenda;

use Joomla\CMS\Form\FormField;
use Joomla\CMS\Language\Text;

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

/**
 * Checkdnsrr Form Field class for iCagenda.
 * Display an alert if Checkdnsrr is not enable on the server.
 */
class CheckdnsrrField extends FormField
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 */
	protected $type = 'Checkdnsrr';

	/**
	 * Method to get the field input markup.
	 *
	 * @return  string  The field input markup.
	 */
	protected function getInput()
	{
		if ( ! function_exists('checkdnsrr')) {
			$html = '<div class="alert alert-error"><span class="icon-warning"></span>';
			$html.= '<strong> ' . Text::_('COM_ICAGENDA_REGISTRATION_EMAIL_CHECKDNSRR_NOT_PRESENT_1') . '</strong><br>';
			$html.= Text::_('COM_ICAGENDA_REGISTRATION_EMAIL_CHECKDNSRR_NOT_PRESENT_2');
			$html.= '</div>';
		} else {
			$html = '<script>document.getElementById("jform_Checkdnsrr-lbl").style.display = "none"; document.getElementById("jform_Checkdnsrr-lbl").style.display = "none";</script>';
		}

		return $html;
	}
}
