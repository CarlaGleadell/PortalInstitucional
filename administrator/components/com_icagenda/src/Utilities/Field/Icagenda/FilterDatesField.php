<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.9.0 2024-02-18
 *
 * @package     iCagenda.Admin
 * @subpackage  Utilities.Field.Icagenda
 * @link        https://www.icagenda.com
 *
 * @author      Cyril Rezé
 * @copyright   (c) 2012-2024 Cyril Rezé / iCagenda. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 *
 * @since       3.8.0
 *----------------------------------------------------------------------------
*/

namespace iCutilities\Field\Icagenda;

use iClib\Date\Date as iCDate;
use iClib\Globalize\Globalize as iCGlobalize;
use Joomla\CMS\Factory;
use Joomla\CMS\Form\Field\ListField;
use Joomla\CMS\Language\Text;
use WebiC\Component\iCagenda\Administrator\Model\RegistrationsModel;

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

/**
 * Event Dates Filter Field
 */
class FilterDatesField extends ListField
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 */
	protected $type = 'FilterDates';

	/**
	 * Name of the layout being used to render the field
	 *
	 * @var  string
	 */
//	protected $layout = 'joomla.form.field.list-fancy-select';

	/**
	 * Method to get the field options.
	 *
	 * @return  array  The field option objects.
	 */
	protected function getOptions()
	{
		$model = new RegistrationsModel;

		$params        = $model->getState('params');
		$dateFormat    = $params->get('date_format_global', 'Y - m - d');
		$dateSeparator = $params->get('date_separator', ' ');
		$timeFormat    = ($params->get('timeformat', '1') == 1) ? 'H:i' : 'h:i A';

		// Create a new query object.
		$db    = Factory::getDbo();
		$query = $db->getQuery(true);

		// Select the required fields from the table.
		$query->select('r.date AS date, r.period AS period, r.eventid AS eventid')
			->from('#__icagenda_registration AS r');

		// Join over the events (period).
		$query->select('e.startdate AS startdate, e.enddate AS enddate, e.displaytime AS displaytime')
			->join('LEFT', '#__icagenda_events AS e ON e.id=r.eventid');

		$db->setQuery($query);
		$dates = $db->loadObjectList();

		$list = [];

		$eventId = $db->escape($model->getState('filter.events'));

		$p = $e = 0;

		// Add to select dropdown the filters 'For all dates of the event' and/or 'For all the period',
		// depending of registrations in data, and selected event
		foreach ($dates as $d) {
			$period = (empty($d->date) && ($d->period == 1 || $d->period == ''))
					? '[ ' . ucfirst(Text::_('COM_ICAGENDA_ADMIN_REGISTRATION_FOR_ALL_DATES')) . ' ]'
					: '';

			if (empty($d->date)
				&& $d->period == 1
				&& $e == 0
			) {
				if ( ! empty($eventId)
					&& $eventId == $d->eventid
				) {
					$e = $e+1;

					$list['all'] = [
						'value' => 'all',
						'text'  => $period,
					];
				} elseif (empty($eventId)) {
					$e = $e+1;

					$list['all'] = [
						'value' => 'all',
						'text'  => $period,
					];
				}
			}
		}

		// Add to select dropdown the list of dates,
		// depending of registrations in data, and selected event
		foreach ($dates as $d) {
			$date = '';

			if (empty($d->date) && $d->period == 0) {
				if ( ! empty($eventId) && $eventId == $d->eventid) {
					if (iCDate::isDate($d->startdate)) {
						$date = iCGlobalize::dateFormat($d->startdate, $dateFormat, $dateSeparator);

						if ($d->displaytime) {
							$date.= ' - ' . date($timeFormat, strtotime($d->startdate));
						}
					}

					if (iCDate::isDate($d->enddate)) {
						$date.= "\n" . '> ' . iCGlobalize::dateFormat($d->enddate, $dateFormat, $dateSeparator);

						if ($d->displaytime) {
							$date.= ' - ' . date($timeFormat, strtotime($d->enddate));
						}
					}
				} elseif (empty($eventId)) {
					if (iCDate::isDate($d->startdate)) {
						$date = iCGlobalize::dateFormat($d->startdate, $dateFormat, $dateSeparator);

						if ($d->displaytime) {
							$date.= ' - ' . date($timeFormat, strtotime($d->startdate));
						}
					}

					if (iCDate::isDate($d->enddate)) {
						$date.= "\n" . '> ' . iCGlobalize::dateFormat($d->enddate, $dateFormat, $dateSeparator);

						if ($d->displaytime) {
							$date.= ' - ' . date($timeFormat, strtotime($d->enddate));
						}
					}
				} else {
					$date = '[ ' . ucfirst(Text::_('COM_ICAGENDA_ADMIN_REGISTRATION_FOR_ALL_PERIOD')) . ' ]';
				}
			} else {
				$deprecatedDate = iCDate::isDate($d->date)
								? $d->date
//								: Text::_('JUNDEFINED') . "\n" . '&#x279F; ' . Text::_('COM_ICAGENDA_LEGEND_EDIT_REGISTRATION');
								: Text::_('JUNDEFINED');

				if  ( ! empty($eventId)) {
					$date   = iCDate::isDate($d->date)
							? iCGlobalize::dateFormat($d->date, $dateFormat, $dateSeparator) . ' - ' . date('H:i', strtotime($d->date))
							: '&#9888; ' . $deprecatedDate;
				} else {
					$date   = iCDate::isDate($d->date)
							? iCGlobalize::dateFormat($d->date, $dateFormat, $dateSeparator) . ' - ' . date('H:i', strtotime($d->date))
							: '&#9888; ' . $deprecatedDate;
				}
			}

			$display_date	= ($date != '0000-00-00 00:00:00' && $d->date) ? true : false;
			$display_period	= ($date != '0000-00-00 00:00:00' && $d->startdate) ? true : false;

			if ($display_date
				&& ! empty($eventId)
				&& $eventId == $d->eventid
			) {
				$list[$d->date] = array('value' => $d->date, 'text' => $date);
			} elseif ($display_date && empty($eventId)) {
				if (iCDate::isDate($d->date)) {
					$list[date('Y-m-d', strtotime($d->date))] = [
						'value' => date('Y-m-d', strtotime($d->date)),
						'text'  => $date,
					];
				} else {
					$list[$d->date] = [
						'value' => $d->date,
						'text'  => $date,
					];
				}
			} elseif ($display_period && empty($eventId)) {
				$list[date('Y-m-d', strtotime($d->startdate))] = [
					'value' => date('Y-m-d', strtotime($d->startdate)),
					'text'  => $date,
				];
			}

			if (empty($d->date)
				&& $d->period == 0
				&& $p == 0
			) {
				if ( ! empty($eventId) && $eventId == $d->eventid) {
					$p = $p+1;

					$list[1] = [
						'value' => 1,
						'text'  => $date,
					];
				} elseif (empty($eventId)) {
					$p = $p+1;
				}
			}
		}

		krsort($list);

		return array_merge(parent::getOptions(), $list);
	}
}
