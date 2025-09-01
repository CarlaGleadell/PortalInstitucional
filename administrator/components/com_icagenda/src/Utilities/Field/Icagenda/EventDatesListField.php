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
 * @since       3.3.3
 *----------------------------------------------------------------------------
*/

namespace iCutilities\Field\Icagenda;

use Joomla\CMS\Factory;
use Joomla\CMS\Form\Field\ListField;
use Joomla\CMS\Language\Text;

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

/**
 * Event Dates List Form Field class for iCagenda.
 * Display a select list of the dates of an event.
 */
class EventDatesListField extends ListField
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 */
	protected $type = 'EventDatesList';

	/**
	 * Method to get the field input markup.
	 *
	 * @return  string  The field input markup.
	 */
	protected function getInput()
	{
		$input = Factory::getApplication()->input;

		$class = isset($this->class) ? ' class="' . $this->class . '"' : '';

		$view  = $input->get('view');

		$id = ($view == 'mail') ? $input->get('eventid', '0', 'int') : $input->get('id', '0', 'int');

		if ($id != 0) {
			$db = Factory::getDbo();

			$query = $db->getQuery(true);
			$query->select('r.id as reg_id, r.date AS reg_date, r.period AS reg_period, r.eventid AS reg_eventid, sum(r.people) AS reg_count')
				->from('#__icagenda_registration AS r');

			if ($view == 'mail') {
				$query->where('r.state = 1')
					->group('r.date');
			}

			$query->where('r.eventid = ' . (int) $id);

			$db->setQuery($query);

			if ($view == 'mail') {
				$result = $db->loadObjectList();
			} else {
				$result    = $db->loadObject();
				$event_id  = $result->reg_eventid;
				$saveddate = $result->reg_date;
			}
		} elseif ($view == 'registration') {
			$event_id  = '';
			$saveddate = '';
		}

		if ($view == 'registration') {
			// Test if date saved in in datetime data format
			$date_is_datetime_sql = false;
			$array_ex_date        = ['-', ' ', ':'];
			$d_ex                 = $saveddate ? str_replace($array_ex_date, '-', $saveddate) : '';
			$d_ex                 = explode('-', $d_ex);

			if (\count($d_ex) > 4) {
				if (\strlen($d_ex[0]) == 4
					&& \strlen($d_ex[1]) == 2
					&& \strlen($d_ex[2]) == 2
					&& \strlen($d_ex[3]) == 2
					&& \strlen($d_ex[4]) == 2
				) {
					$date_is_datetime_sql = true;
				}
			}

			// Test if registered date before 3.3.3 could be converted
			// Control if new date format (Y-m-d H:i:s)
			$control  = $saveddate ? trim($saveddate) : '';
			$is_valid = date('Y-m-d H:i:s', strtotime($control)) == $control;

			if ($is_valid
				&& strtotime($saveddate)
			) {
				$date_get     = $saveddate ? explode (' ', $saveddate) : [];
				$saved_date   = $date_get['0'];
				$saved_time   = date('H:i:s', strtotime($date_get['1']));
			} else {
				// Explode to test if stored in old format in database
				$ex_saveddate = $saveddate ? explode (' - ', $saveddate) : [];
				$saved_date   = isset($ex_saveddate['0']) ? trim($ex_saveddate['0']) : '';
				$saved_time   = isset($ex_saveddate['1']) ? trim(date('H:i:s', strtotime($ex_saveddate['1']))) : '';
			}

			$data_eventid = $event_id;

			$eventid_url = $input->get('eventid', 0, 'int');

			if ( ! $date_is_datetime_sql && $saveddate ) {
				$saveddate_text = '"<b>' . $saveddate . '</b>"';

				echo '<div class="ic-alert ic-alert-note"><span class="iCicon-info"></span> <strong>' . Text::_('NOTICE') . '</strong><br />'
					. Text::sprintf('COM_ICAGENDA_REGISTRATION_ERROR_DATE_CONTROL', $saveddate_text) . '</div>';
			}

			$event_id = isset($event_id) ? $eventid_url : '';
		}

		$html = '<select name="' . $this->name . '" id="' . $this->id . '_id"' . $class . '></select>';

		return $html;
	}
}
