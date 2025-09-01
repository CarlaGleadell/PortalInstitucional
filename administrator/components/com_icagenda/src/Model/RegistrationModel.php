<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.9.0 2023-12-22
 *
 * @package     iCagenda.Admin
 * @subpackage  src.Model
 * @link        https://www.icagenda.com
 *
 * @author      Cyril Rezé
 * @copyright   (c) 2012-2024 Cyril Rezé / iCagenda. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 *
 * @since       3.3.3
 *----------------------------------------------------------------------------
*/

namespace WebiC\Component\iCagenda\Administrator\Model;

\defined('_JEXEC') or die;

use iCutilities\Customfields\Customfields as icagendaCustomfields;
use iCutilities\Event\Event as icagendaEvent;
use iCutilities\Registration\Registration as icagendaRegistration;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\Model\AdminModel;
use Joomla\CMS\Object\CMSObject;
use Joomla\CMS\Table\Table;
use Joomla\Database\ParameterType;


/**
 * iCagenda Component Registration Model.
 */
class RegistrationModel extends AdminModel
{
	/**
	 * @var  string  The prefix to use with controller messages.
	 */
	protected $text_prefix = 'COM_ICAGENDA';

	/**
	 * Method to test whether a record can be deleted.
	 *
	 * @param   object  $record  A record object.
	 *
	 * @return  boolean  True if allowed to delete the record. Defaults to the permission set in the component.
	 *
	 * @since   3.5.6
	 */
	protected function canDelete($record)
	{
		if ( ! empty($record->id))
		{
			if ($record->state != -2)
			{
				return false;
			}

			$user = Factory::getUser();

			// Delete linked data from other tables
			if ($user->authorise('core.delete'))
			{
				// Delete Custom Fields data
				icagendaCustomfields::deleteData($record->id, 1);
				icagendaCustomfields::cleanData(1);

				return true;
			}
		}

		return false;
	}

	/**
	 * Method to get a table object, load it if necessary.
	 *
	 * @param   string  $name     The table name. Optional.
	 * @param   string  $prefix   The class prefix. Optional.
	 * @param   array   $options  Configuration array for model. Optional.
	 *
	 * @return  Table  A Table object
	 *
	 * @throws  \Exception
	 */
	public function getTable($name = 'Registration', $prefix = 'Table', $config = array())
	{
		if ($table = $this->_createTable($name, $prefix, $config))
		{
			return $table;
		}

		throw new \Exception(Text::sprintf('JLIB_APPLICATION_ERROR_TABLE_NAME_NOT_SUPPORTED', $name), 0);
	}

	/**
	 * Method to get the record form.
	 *
	 * @param   array    $data      Data for the form.
	 * @param   boolean  $loadData  True if the form is to load its own data (default case), false if not.
	 *
	 * @return  mixed  A JForm object on success, false on failure
	 */
	public function getForm($data = array(), $loadData = true)
	{
		// Get the form.
		$form = $this->loadForm('com_icagenda.registration', 'registration',
								array('control' => 'jform', 'load_data' => $loadData));
		if (empty($form))
		{
			return false;
		}

		return $form;
	}

	/**
	 * Method to get the data that should be injected in the form.
	 *
	 * @return  mixed  The data for the form.
	 */
	protected function loadFormData()
	{
		// Check the session for previously entered form data.
		$data_array = Factory::getApplication()->getUserState('com_icagenda.edit.registration.data', array());

		if (empty($data_array))
		{
			$data = $this->getItem();
		}
		else
		{
			$data = new CMSObject;
			$data->setProperties($data_array);
		}

		return $data;
	}

	/**
	 * Method to save the form data.
	 *
	 * @param   array  $data  The form data.
	 *
	 * @return  boolean  True on success.
	 *
	 * @since   3.5.6
	 */
	public function save($data)
	{
		$app   = Factory::getApplication();
		$input = $app->input;
		$date  = Factory::getDate();
		$user  = Factory::getUser();

		// Set registration creator
		if (empty($data['created_by']))
		{
			$data['created_by'] = (int) $data['userid'];
		}

		// Set Params
		if (isset($data['params']) && is_array($data['params']))
		{
			// Convert the params field to a string.
			$parameter = new JRegistry;
			$parameter->loadArray($data['params']);
			$data['params'] = (string)$parameter;
		}

		if ($input->get('task') == 'delete')
		{
			icagendaCustomfields::deleteData($data['custom_fields'], $data['id'], 1);
			// To Be Tested $app->enqueueMessage('Test');
		}

		// Control number of tickets available
		$iCParams   = ComponentHelper::getParams('com_icagenda');
		$evtParams  = icagendaEvent::getParams($data['eventid']);
		$typeReg    = $evtParams->get('typeReg', 1);
		$maxReg     = $evtParams->get('maxReg', '1000000');
		$tickets    = ($evtParams->get('maxRlistGlobal', '') == 2)
					? $evtParams->get('maxRlist', $iCParams->get('maxRlist', '5'))
					: $iCParams->get('maxRlist', '5');

		$ticketsAvailable = icagendaRegistration::getTicketsBookable($data['eventid'], $data['date'], $typeReg, $maxReg, $tickets);
		$extraTickets     = ((int) $data['people'] - (int) $data['data_people']);
		$ticketsBookable  = ((int) $data['data_people'] + (int) $ticketsAvailable);

		if ($data['people'] > $ticketsBookable)
		{
			$msg = '<strong>' . Text::_('COM_ICAGENDA_REGISTRATION_ERROR_NOT_ENOUGH_TICKETS_AVAILABLE') . '</strong><br />';

			if ($data['data_people'])
			{
				$msg.= Text::sprintf('COM_ICAGENDA_REGISTRATION_ERROR_NOT_ENOUGH_TICKETS_ALREADY_BOOKED', $data['data_people']);
				$msg.= Text::sprintf('COM_ICAGENDA_REGISTRATION_ERROR_NOT_ENOUGH_TICKETS_EXTRA_PEOPLE', $extraTickets, $ticketsAvailable)
					. '<br />';
			}

			$msg.= '<strong>' . Text::sprintf('COM_ICAGENDA_REGISTRATION_ERROR_MAXIMUM_TICKETS_BOOKABLE', $ticketsBookable)
				. '</strong>'
				. '<br />';
			$msg.= Text::_('COM_ICAGENDA_REGISTRATION_ERROR_NOT_ENOUGH_TICKETS_CHANGE_NUMBER_OF_PEOPLE');


//			$msg.= '<br /><br />$maxReg: ' . $maxReg;
//			$msg.= '<br />$tickets: ' . $tickets;
//			$msg.= '<br />$ticketsAvailable: ' . $ticketsAvailable;

			$this->setError($msg);

			return false;
		}

		if ($typeReg == 1 && $data['date'] == '')
		{
			$msg = '<strong>' . Text::_('COM_ICAGENDA_REGISTRATION_NO_DATE_SELECTED') . '</strong>';

			$this->setError($msg);

			return false;
		}

		if ($data['date'] == 'from_to')
		{
			$data['date'] = '';
		}

		if (!isset($data['params']))
		{
			$data['params'] = '';
		}

		// Get Registration ID from the result back to the Table after saving.
//		$table = $this->getTable();

//		if ($table->save($data) === true)
//		{
//			$data['id'] = $table->id;
//		}
//		else
//		{
//			$data['id'] = null;
//		}

		if (parent::save($data))
		{
			// Get Registration ID after saving.
//			$regId = $this->getState($this->getName() . '.id');

			// Save Custom Fields to database
			if (isset($data['custom_fields']) && is_array($data['custom_fields']))
			{
				icagendaCustomfields::saveToData($data['custom_fields'], $data['id'], 1);
			}

			return true;
		}

		return false;
	}

	/**
	 * Cleans the cache of com_icagenda and iCagenda modules
	 *
	 * @param   string   $group     The cache group
	 * @param   integer  $clientId  @deprecated   J5.0   No longer used.
	 *
	 * @return  void
	 *
	 * @since   3.8
	 */
	protected function cleanCache($group = null, $clientId = 0)
	{
		parent::cleanCache('com_icagenda');
		parent::cleanCache('mod_iccalendar');
		parent::cleanCache('mod_ic_event_list');
	}

	/**
	 * Method to get a single record.
	 *
	 * @param   integer  The id of the primary key.
	 *
	 * @return  mixed  Object on success, false on failure.
	 */
	public function getItem($pk = null)
	{
		if ($item = parent::getItem($pk))
		{
			// Do any procesing on fields here if needed
			$db = Factory::getDbo();
			$query = $db->getQuery(true)
				->select('created_by AS eventAuthor')
				->from($db->quoteName('#__icagenda_events'))
				->where($db->quoteName('id') . ' = :eventid')
				->bind(':eventid', $item->eventid, ParameterType::INTEGER);
			$db->setQuery($query);
			$result = $db->loadResult();

			if ($result) {
				$item->eventAuthor = $result;
			}
		}

		return $item;
	}

	/**
	 * Prepare and sanitise the table prior to saving.
	 */
	protected function prepareTable($table)
	{
		$date = Factory::getDate();
		$user = Factory::getUser();

		$table->name = htmlspecialchars_decode($table->name, ENT_QUOTES);

		if (empty($table->id))
		{
			// Set the values
			$table->created		= $date->toSql();
			$table->created_by	= $user->get('id');

			// Set ordering to the last item if not set
			if (empty($table->ordering))
			{
				$db = Factory::getDbo();
				$query = $db->getQuery(true)
					->select('MAX(ordering)')
					->from($db->quoteName('#__icagenda_registration'));
				$db->setQuery($query);
				$max = $db->loadResult();

				$table->ordering = $max + 1;
			}
		}
		else
		{
			// Set the values
			$table->modified	= $date->toSql();
			$table->modified_by	= $user->get('id');
		}
	}
}
