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

use Joomla\CMS\Factory;
use Joomla\CMS\Form\FormField;

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

/**
 * Map Locality Form Field class for iCagenda.
 * Returns Locality from Google Maps address auto-complete field.
 */
class MapLocalityField extends FormField
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 */
	protected $type = 'MapLocality';

	/**
	 * Method to get the field input markup.
	 *
	 * @return  string  The field input markup.
	 */
	protected function getInput()
	{
		$session = Factory::getSession();

		$ic_submit_city = $session->get('ic_submit_city', '');

		$city_value = $ic_submit_city ? $ic_submit_city : $this->value;

		$class = isset($this->class) ? ' class="' . $this->class . '"' : '';

		$html = '<div class="clr"></div>';
		$html.= '<label class="icmap-label">' . $this->title . '</label>';
		$html.= '<input id="locality" name="' . $this->name . '" type="text"' . $class . ' value="' . $city_value . '"/>';

		// clear the data so we don't process it again
		$session->clear('ic_submit_city');

		return $html;
	}
}
