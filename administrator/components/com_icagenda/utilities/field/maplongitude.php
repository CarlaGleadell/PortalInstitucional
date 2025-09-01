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
class icagendaFormFieldMapLongitude extends JFormField
{
	protected $type = 'MapLongitude';

	protected function getInput()
	{
		$class     = isset($this->class) ? ' class="' . $this->class . '"' : '';
		$lng_value = $this->value;

		if ($lng_value != '0.0000000000000000') {
			$lng_value = $lng_value;
		} else {
			$lng_value = '0.0000000000000000';
		}

		$lng_value = str_replace(',', '.', $lng_value);

		$html = '<div class="clr"></div>';
		$html.= '<label class="icmap-label">' . JText::_('COM_ICAGENDA_GOOGLE_MAPS_LONGITUDE_LBL') . '</label>';
		$html.= '<input name="' . $this->name . '" id="lng" type="text"' . $class . ' value="' . $lng_value . '"/>';

		return $html;
	}
}
