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
 * @since       1.0.0
 *----------------------------------------------------------------------------
*/

namespace iCutilities\Field\Icagenda;

use Joomla\CMS\Form\FormField;

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

/**
 * Map Latitude Form Field class for iCagenda.
 * Returns Latitude from Google Maps address auto-complete field.
 */
class MapLatitudeField extends FormField
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 */
	protected $type = 'MapLatitude';

	/**
	 * Method to get the field input markup.
	 *
	 * @return  string  The field input markup.
	 */
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
		$html.= '<label class="icmap-label">' . $this->title . '</label>';
		$html.= '<input name="' . $this->name . '" id="lat" type="text"' . $class . ' value="' . $lat_value . '"/>';

		return $html;
	}
}
