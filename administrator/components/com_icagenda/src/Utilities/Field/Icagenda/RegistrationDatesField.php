<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.9.4 2024-05-06
 *
 * @package     iCagenda.Admin
 * @subpackage  Utilities.Field.Icagenda
 * @link        https://www.icagenda.com
 *
 * @author      Cyril Rezé
 * @copyright   (c) 2012-2024 Cyril Rezé / iCagenda. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 *
 * @since       3.6.0
 *----------------------------------------------------------------------------
*/

namespace iCutilities\Field\Icagenda;

use iCutilities\Event\Event as icagendaEvent;
use iCutilities\Registration\Registration as icagendaRegistration;
use Joomla\CMS\Factory;
use Joomla\CMS\Form\Field\ListField;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use WebiC\Component\iCagenda\Administrator\Model\RegistrationModel;
use WebiC\Component\iCagenda\Site\Model\RegistrationModel as SiteRegistrationModel;

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

/**
 * Dates List Form Field class for iCagenda.
 * Registration form : dates select list/all dates form field
 */
class RegistrationDatesField extends ListField
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 */
	protected $type = 'registrationdates';

	/**
	 * The active item
	 *
	 * @var  object
	 */
	protected $item;

	/**
	 * Constructor
	 */
	public function __construct()
	{
		parent::__construct();

		// Load item object from model.
		$model  = (Factory::getApplication()->isClient('site')
				? new SiteRegistrationModel
				: new RegistrationModel);

		$this->item = $model->getItem();
	}

	/**
	 * Method to get the field label markup.
	 *
	 * @return  string  The field label markup.
	 */
	protected function getLabel()
	{
		$label = '';

		if ($this->hidden) {
			return $label;
		}

		// Build the class for the label.
		$class = 'ic-date-label';
		$class = !empty($this->element['labelclass']) ? $class . ' ' . $this->element['labelclass'] : $class;

		// Add the opening label tag and main attributes attributes.
		$label .= '<label id="' . $this->id . '-lbl" for="' . $this->id . '" class="' . $class . '">';
		$label .= Text::plural('COM_ICAGENDA_REGISTRATION_DATES_LABEL', \count($this->getOptions()));
		$label .= '</label>';

		return $label;
	}

	/**
	 * Method to get the field input markup.
	 *
	 * @return  string  The field input markup.
	 */
	protected function getInput()
	{
		$typeReg = $this->item->params->get('typeReg', 1);

		// Get date from url if set, and from session if set
		$url_date     = Factory::getApplication()->input->get('date', '');
		$session_date = Factory::getSession()->get('session_date', '');

		if ($url_date) {
			$ex_date     = explode('-', $url_date);
			$defaultDate = $ex_date[0] . '-' . $ex_date[1] . '-' . $ex_date[2] . ' ' . $ex_date[3] . ':' . $ex_date[4] . ':00';
		} else {
			$defaultDate = $session_date;
		}

		$value = ! empty($this->value) ? $this->value : $defaultDate;
		$class = ! empty($this->class) ? ' class="' . $this->class . '"' : '';

		$html = '';

		if ($typeReg == 1) {
			// Return Select list of dates
			$options = [];

			if ( ! $this->getOptions()) return false;

			if (count($this->getOptions()) > 1) {
				$options[] = HTMLHelper::_('select.option', '', Text::_('COM_ICAGENDA_REGISTRATION_DATES_SELECT_1'));

				foreach ($this->getOptions() as $opt) {
					$date_get    = explode('@@', $opt);
					$date_value  = $date_get[0];
					$date_option = $date_get[1];

					$options[] = HTMLHelper::_('select.option', $date_value, $date_option);
				}

				$html.= HTMLHelper::_('select.genericlist', $options, $this->name, $class, 'value', 'text', $value, $this->id);

				return $html;
			} else {
				foreach ($this->getOptions() as $opt) {
					$date_get    = explode('@@', $opt);
					$date_value  = $date_get[0];
					$date_option = $date_get[1];
				}

				$html.= '<input id="' . $this->id . '" type="hidden" name="' . $this->name . '" value="' . $date_value . '" />';
				$html.= '<div style="padding-top: 5px;" class="ic-inlinetext-input">' . $date_option . '</div>';

				return $html;
			}
		} else {
			// Return for all dates
			$html.= '<div>';

			$EVENT_SINGLE_DATES = icagendaEvent::displayListSingleDates($this->item);
			$EVENT_PERIOD       = icagendaEvent::displayPeriodDates($this->item);

			if ($EVENT_PERIOD) {
				$html.= $EVENT_PERIOD;
			}

			if ($EVENT_SINGLE_DATES) {
				$html.= $EVENT_SINGLE_DATES;
			}

			$html.= '<input id="jform_period" type="hidden" name="jform[period]" value="1" />';
			$html.= '<input id="jform_date" type="hidden" name="jform[date]" value="period" />';
			$html.= '</div>';

			return $html;
		}
	}

	/**
	 * Method to get the field options.
	 *
	 * @return  array  The field option objects.
	 */
	protected function getOptions()
	{
		$options = [];

		if ($this->item) {
			$options = icagendaRegistration::formDatesList($this->item);
		}

		return $options;
	}
}
