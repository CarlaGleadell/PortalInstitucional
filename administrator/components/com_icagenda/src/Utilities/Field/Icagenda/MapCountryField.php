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
 * Map Country Form Field class for iCagenda.
 * Returns Country from Google Maps address auto-complete field.
 */
class MapCountryField extends FormField
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 */
	protected $type = 'MapCountry';

	/**
	 * Method to get the field input markup.
	 *
	 * @return  string  The field input markup.
	 */
	protected function getInput()
	{
		$session = Factory::getSession();

		$ic_submit_country = $session->get('ic_submit_country', '');

		$country_value = $ic_submit_country ? $ic_submit_country : $this->value;

		$class = isset($this->class) ? ' class="' . $this->class . '"' : '';

		$html = '<div class="clr"></div>';
		$html.= '<label class="icmap-label">' . $this->title . '</label>';
		$html.= '<input name="' . $this->name . '" id="country" type="text"' . $class . ' value="' . $country_value . '" />';

		// clear the data so we don't process it again
		$session->clear('ic_submit_country');

		return $html;
	}
}
