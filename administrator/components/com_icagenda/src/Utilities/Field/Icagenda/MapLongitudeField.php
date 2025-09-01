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
 * Map Longitude Form Field class for iCagenda.
 * Returns Longitude from Google Maps address auto-complete field.
 */
class MapLongitudeField extends FormField
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 */
	protected $type = 'MapLongitude';

	/**
	 * Method to get the field input markup.
	 *
	 * @return  string  The field input markup.
	 */
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
		$html.= '<label class="icmap-label">' . $this->title . '</label>';
		$html.= '<input name="' . $this->name . '" id="lng" type="text"' . $class . ' value="' . $lng_value . '"/>';

		return $html;
	}
}
