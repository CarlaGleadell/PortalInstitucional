<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.9.0 2024-02-20
 *
 * @package     iCagenda.Admin
 * @link        https://www.icagenda.com
 *
 * @author      Cyril Rezé
 * @copyright   (c) 2012-2024 Cyril Rezé / iCagenda. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 *
 * @since       3.1.7
 *----------------------------------------------------------------------------
*/

defined('_JEXEC') or die;

jimport( 'joomla.filesystem.path' );
jimport('joomla.form.formfield');

class icagendaFormFieldCheckdnsrr extends JFormField
{
	protected $type = 'Checkdnsrr';

	protected function getInput()
	{
		if ( ! function_exists('checkdnsrr'))
		{
			$html = '<div class="alert alert-error"><span class="icon-warning"></span><b> ' . JText::_('COM_ICAGENDA_REGISTRATION_EMAIL_CHECKDNSRR_NOT_PRESENT_1') . '</b><br/>' . JText::_('COM_ICAGENDA_REGISTRATION_EMAIL_CHECKDNSRR_NOT_PRESENT_2') . '</div>';
		}
		else
		{
			$html = '<script>document.getElementById("jform_Checkdnsrr-lbl").style.display = "none"; document.getElementById("jform_Checkdnsrr-lbl").style.display = "none";</script>';
		}

		return $html;
	}
}
