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
 * @since       1.0.0
 *----------------------------------------------------------------------------
*/

defined('_JEXEC') or die;

jimport( 'joomla.filesystem.path' );
jimport('joomla.form.formfield');

/**
 * Returns City from Google Maps address auto-complete field.
 */
class icagendaFormFieldMapLocality extends JFormField
{
	protected $type = 'MapLocality';

	protected function getInput()
	{
		$session = JFactory::getSession();
		$ic_submit_city = $session->get('ic_submit_city', '');

		$city_value = $ic_submit_city ? $ic_submit_city : $this->value;

		$class = isset($this->class) ? ' class="' . $this->class . '"' : '';

		$html = '<div class="clr"></div>';
		$html.= '<label class="icmap-label">' . JText::_('COM_ICAGENDA_FORM_LBL_EVENT_CITY') . '</label> <input id="locality" name="' . $this->name . '" type="text"' . $class . ' value="' . $city_value . '"/>';

		// clear the data so we don't process it again
		$session->clear('ic_submit_city');

		return $html;
	}
}
