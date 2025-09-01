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
 * @since       1.0
 *----------------------------------------------------------------------------
*/

\defined('_JEXEC') or die;

/**
 * Returns Latitude from Google Maps address auto-complete field.
 */
class icagendaFormFieldMapLatitude extends JFormField
{
	protected $type = 'MapLatitude';

	protected function getInput()
	{
		$class     = isset($this->class) ? ' class="' . $this->class . '"' : '';
		$lat_value = $this->value;

		if ($lat_value != '0.0000000000000000') {
			$lat_value = $lat_value;
		} else {
			$lat_value = '0.0000000000000000';
		}

		$lat_value = str_replace(',', '.', $lat_value);

		$html = '<div class="clr"></div>';
		$html.= '<label class="icmap-label">' . JText::_('COM_ICAGENDA_GOOGLE_MAPS_LATITUDE_LBL') . '</label>';
		$html.= '<input name="' . $this->name . '" id="lat" type="text"' . $class . ' value="' . $lat_value . '"/>';

		return $html;
	}
}
