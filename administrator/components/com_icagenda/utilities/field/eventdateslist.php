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
 * @since       3.3.3
 *----------------------------------------------------------------------------
*/

defined('_JEXEC') or die;

jimport( 'joomla.filesystem.path' );
jimport('joomla.form.formfield');

class icagendaFormFieldEventDatesList extends JFormField
{
	protected $type = 'EventDatesList';

	protected function getInput()
	{
		$jinput = JFactory::getApplication()->input;
		$view   = $jinput->get('view');
		$class  = isset($this->class) ? ' class="' . $this->class . '"' : '';

		$id = ($view == 'mail') ? $jinput->getInt('eventid', '0') : $jinput->getInt('id', '0');

		if ($id != 0)
		{
			$db    = JFactory::getDbo();
			$query = $db->getQuery(true);
			$query->select('r.id as reg_id, r.date AS reg_date, r.period AS reg_period, r.eventid AS reg_eventid, sum(r.people) AS reg_count')
				->from('#__icagenda_registration AS r');

			if ($view == 'mail')
			{
				$query->where('r.state = 1');
				$query->group('r.date');
			}

			$query->where('r.eventid = ' . (int) $id);

			$db->setQuery($query);

			if ($view == 'mail')
			{
				$result = $db->loadObjectList();
			}
			else
			{
				$result    = $db->loadObject();
				$event_id  = $result->reg_eventid;
				$saveddate = $result->reg_date;
			}
		}
		elseif ($view == 'registration')
		{
			$event_id  = '';
			$saveddate = '';
		}

		if ($view == 'registration')
		{
			// Test if date saved in in datetime data format
			$date_is_datetime_sql = false;
			$array_ex_date        = array('-', ' ', ':');
			$d_ex                 = $saveddate ? str_replace($array_ex_date, '-', $saveddate) : '';
			$d_ex                 = explode('-', $d_ex);

			if (count($d_ex) > 4)
			{
				if (   strlen($d_ex[0]) == 4
					&& strlen($d_ex[1]) == 2
					&& strlen($d_ex[2]) == 2
					&& strlen($d_ex[3]) == 2
					&& strlen($d_ex[4]) == 2   )
				{
					$date_is_datetime_sql = true;
				}
			}

			// Test if registered date before 3.3.3 could be converted
			// Control if new date format (Y-m-d H:i:s)
			$input    = $saveddate ? trim($saveddate) : '';
			$is_valid = date('Y-m-d H:i:s', strtotime($input)) == $input;

			if ($is_valid
				&& strtotime($saveddate))
			{
				$date_get     = $saveddate ? explode (' ', $saveddate) : [];
				$saved_date   = $date_get['0'];
				$saved_time   = date('H:i:s', strtotime($date_get['1']));
			}
			else
			{
				// Explode to test if stored in old format in database
				$ex_saveddate = $saveddate ? explode (' - ', $saveddate) : [];
				$saved_date   = isset($ex_saveddate['0']) ? trim($ex_saveddate['0']) : '';
				$saved_time   = isset($ex_saveddate['1']) ? trim(date('H:i:s', strtotime($ex_saveddate['1']))) : '';
			}

			$data_eventid = $event_id;

			$eventid_url = $jinput->getInt('eventid', '');

			if ( ! $date_is_datetime_sql && $saveddate )
			{
				$saveddate_text = '"<b>' . $saveddate . '</b>"';
				echo '<div class="ic-alert ic-alert-note"><span class="iCicon-info"></span> <strong>' . JText::_('NOTICE') . '</strong><br />'
					. JText::sprintf('COM_ICAGENDA_REGISTRATION_ERROR_DATE_CONTROL', $saveddate_text) . '</div>';
			}

			$event_id = isset($event_id) ? $eventid_url : '';
		}

		$dataChosen = (version_compare(JVERSION, '4.0', 'lt')) ? ' data-chosen="true"' : '';
		$html = '<select name="' . $this->name . '" id="' . $this->id . '_id"' . $class . $dataChosen . '></select>';

		return $html;
	}
}
