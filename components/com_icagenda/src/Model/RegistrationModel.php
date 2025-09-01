<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.9.2 2024-03-20
 *
 * @package     iCagenda.Site
 * @subpackage  src.Model
 * @link        https://www.icagenda.com
 *
 * @author      Cyril Rezé
 * @copyright   (c) 2012-2024 Cyril Rezé / iCagenda. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 *
 * @since       3.6
 *----------------------------------------------------------------------------
*/

namespace WebiC\Component\iCagenda\Site\Model;

\defined('_JEXEC') or die;

use iClib\Date\Date as iCDate;
use iClib\Utf8\Utf8 as iCUtf8;
use iCutilities\Customfields\Customfields as icagendaCustomfields;
use iCutilities\Event\Event as icagendaEvent;
use iCutilities\Registration\Registration as icagendaRegistration;
use iCutilities\Render\Render as icagendaRender;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Log\Log;
use Joomla\CMS\MVC\Model\FormModel;
use Joomla\CMS\Plugin\PluginHelper;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Uri\Uri;
use Joomla\Registry\Registry;
use Joomla\Utilities\ArrayHelper;

/**
 * iCagenda Component Registration Model
 */
class RegistrationModel extends FormModel
{
	/**
	 * Model context string.
	 *
	 * @var  string
	 */
	protected $_context = 'com_icagenda.registration';

	/**
	 * Form Data.
	 *
	 * @var  object
	 */
	protected $data;

	/**
	 * Method to get the registration form data.
	 *
	 * The base form data is loaded and then an event is fired
	 * for users plugins to extend the data.
	 *
	 * @return  mixed  Data object on success, false on failure.
	 */
	public function getData()
	{
		if ($this->data === null)
		{
			$this->data = new \stdClass;
			$app = Factory::getApplication();
			$params = ComponentHelper::getParams('com_icagenda');

			// Override the base user data with any data in the session.
			$temp = (array) $app->getUserState('com_icagenda.registration.data', array());

			foreach ($temp as $k => $v)
			{
				$this->data->$k = $v;
			}
		}

		return $this->data;
	}

	/**
	 * Method to get the registration form.
	 *
	 * The base form is loaded from XML and then an event is fired
	 * for users plugins to extend the form with extra fields.
	 *
	 * @param   array    $data      An optional array of data for the form to interogate.
	 * @param   boolean  $loadData  True if the form is to load its own data (default case), false if not.
	 *
	 * @return  JForm  A JForm object on success, false on failure
	 */
	public function getForm($data = array(), $loadData = true)
	{
		// Get the form.
		$form = $this->loadForm('com_icagenda.registration', 'registration', array('control' => 'jform', 'load_data' => $loadData));

		if (empty($form))
		{
			return false;
		}

		// Get the application object.
		$app = Factory::getApplication();
		$params = $app->getParams();

		$user = Factory::getUser();
		$user_id = $user->get('id');

		// Autofill name and email if registered user log in
		if ($user_id && $params->get('autofilluser', 1) == 1)
		{
			$form->setValue('uid', null, $user_id);

			// logged-in Users: Name/User Name Option
			$user_name = ($params->get('nameJoomlaUser', 1) == 1) ? $user->get('name') : $user->get('username');
			$user_mail = $user->get('email');
		}
		else
		{
			$form->setFieldAttribute('uid', 'disabled', 'disabled');

			$user_name = '';
			$user_mail = '';
		}

		if ($user_name)
		{
			$form->setValue('name', null, $user_name);
			$form->setFieldAttribute('name', 'readonly', 'true');
			$form->setFieldAttribute('name', 'required', 'false');
			$form->setFieldAttribute('name', 'description', '');
		}

		// Set CORE 'Email' attributes
		if ($user_mail)
		{
			$form->setValue('email', null, $user_mail);
			$form->setFieldAttribute('email', 'readonly', 'true');
			$form->setFieldAttribute('email', 'required', 'false');
			$form->setFieldAttribute('email', 'description', '');
			$form->removeField('email2');
		}
		else
		{
			if ( ! $params->get('emailConfirm', 1))
			{
				$form->removeField('email2');
			}

			if ($params->get('emailRequired', 1))
			{
				$form->setFieldAttribute('email', 'required', 'true');

				if ($params->get('emailConfirm', 1))
				{
					$form->setFieldAttribute('email2', 'required', 'true');
				}
			}
		}

		// Set CORE 'Phone' attributes
		if ( ! $params->get('phoneDisplay', 0))
		{
			$form->removeField('phone');
		}
		elseif ($params->get('phoneRequired', 0))
		{
			$form->setFieldAttribute('phone', 'required', 'true');
			$form->setFieldAttribute('phone', 'validate', 'IC.Tel');
		}

		// Check if Custom Fields required @commented 3.6.5
//		$listCustomFields = icagendaCustomfields::getCustomFields(1, $params->get('custom_form', ''), 1);

//		if ($listCustomFields)
//		{
//			foreach ($listCustomFields AS $cf)
//			{
//				if ($cf->required == 1)
//				{
//					$form->setFieldAttribute(str_replace('core_', '', $cf->slug), 'required', 'true');
//				}
//			}
//		}

		$eventParams = icagendaEvent::getParams($app->input->get('id'));
		$maxTicketsPerRegistration = ($eventParams->get('maxRlistGlobal') == 2)
									? $eventParams->get('maxRlist')
									: $params->get('maxRlist');

		if ($maxTicketsPerRegistration == 1)
		{
			$form->setFieldAttribute('people', 'label', '');
		}

		$form->setValue('custom_fields', null);

		// If Participants List is not enabled, we remove validation for related privacy boxes
		if ($params->get('participantList', 0) == 0)
		{
			// Do not remove name consent if required
			if ($params->get('participant_name_consent') != '1')
			{
				$form->removeField('consent_name_public', 'consent');
				$form->removeField('consent_name_users', 'consent');
			}

			// Do not remove Gravatar consent if required
			if ($params->get('participant_gravatar_consent') != '1')
			{
				$form->removeField('consent_gravatar', 'consent');
			}
		}
		else
		{
			$participant_name_visibility = $params->get('participant_name_visibility');

			if ($params->get('participant_name_consent') == '0')
			{
				$form->removeField('consent_name_public', 'consent');
				$form->removeField('consent_name_users', 'consent');
			}
			elseif ($participant_name_visibility == '1')
			{
				$form->removeField('consent_name_users', 'consent');
			}
			elseif ($participant_name_visibility == '2')
			{
				$form->removeField('consent_name_public', 'consent');
			}
			else
			{
				$form->removeField('consent_name_public', 'consent');
				$form->removeField('consent_name_users', 'consent');
			}

			// Remove Gravatar consent if disabled
			if ($params->get('participant_gravatar_consent') == '0')
			{
				$form->removeField('consent_gravatar', 'consent');
			}
		}

		// If Consent Organiser is not enabled, we remove validation for related privacy box
		if ($params->get('privacy_organiser', 0) == '0')
		{
			$form->removeField('consent_organiser', 'consent');
		}

		// If Terms and Conditions is not enabled, we remove validation for checkbox
		if ($params->get('terms', 1) == '0')
		{
			$form->removeField('consent_terms', 'consent');
		}

		// If Captcha not displayed, we remove validation for captcha form field
		if ($params->get('reg_captcha', 0) == '0')
		{
			$form->removeField('captcha');
		}

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
		$data = $this->getData();

//		$this->preprocessData('com_icagenda.registration', $data);

		return $data;
	}

	/**
	 * Override preprocessForm to load the '#' plugin group instead of content.
	 *
	 * @param   JForm   $form   A JForm object.
	 * @param   mixed   $data   The data expected for the form.
	 * @param   string  $group  The name of the plugin group to import (defaults to "content").
	 *
	 * @return  void
	 * @throws  Exception if there is an error in the form event.
	 */
//	protected function preprocessForm(JForm $form, $data, $group = 'content')
//	{
//		parent::preprocessForm($form, $data, $group);
//	}

	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @return  void
	 */
	protected function populateState()
	{
		// Get the application object.
		$app = Factory::getApplication();

		// Load the parameters.
		$params = $app->getParams('com_icagenda');
		$this->setState('params', $params);

		// Load state from the request.
		$pk = $app->input->getInt('id');
		$this->setState('event.id', $pk);
	}

	/**
	 * Method to save the form data.
	 *
	 * @param   array  $temp  The form data.
	 *
	 * @return  mixed  @todo : update return (The user id on success, false on failure).
	 */
	public function register($temp)
	{
		$data = (array) $this->getData();

		foreach ($temp as $k => $v)
		{
			$data[$k] = $v;
		}

		$app  = Factory::getApplication();
		$user = Factory::getUser();

		$db     = Factory::getDbo();
		$date   = Factory::getDate();
		$params = $app->getParams();

		$eventTimeZone = null;

			$reg_data = new \stdClass();

			// Set the values
			$reg_data->userid           = isset($data['uid']) ? $data['uid'] : 0;
			$reg_data->name             = isset($data['name']) ? $data['name'] : '';
			$reg_data->email            = isset($data['email']) ? $data['email'] : '';
			$reg_data->phone            = isset($data['phone']) ? trim($data['phone']) : '';
			$reg_data->date             = isset($data['date']) ? $data['date'] : '';
			$reg_data->period           = isset($data['period']) ? $data['period'] : '0';
			$reg_data->people           = isset($data['people']) ? $data['people'] : '1';
//			$reg_data->notes            = isset($data['notes']) ? htmlentities(strip_tags($data['notes'])) : '';
			$reg_data->notes            = isset($data['notes']) ? $data['notes'] : '';
			$reg_data->eventid          = isset($data['eventid']) ? $data['eventid'] : '0';
			$reg_data->itemid           = isset($data['menuid']) ? $data['menuid'] : '';
			$reg_data->created          = $date->toSql();
			$reg_data->created_by       = $reg_data->userid;
			$reg_data->checked_out_time = date('Y-m-d H:i:s');
			$reg_data->modified         = $date->toSql();
			$reg_data->params           = isset($data['params']) ? $data['params'] : '';

			$type_registration = isset($data['type_registration']) ? $data['type_registration'] : '1';
			$max_nb_of_tickets = isset($data['max_nb_of_tickets']) ? $data['max_nb_of_tickets'] : '1000000';
			$custom_fields     = isset($data['custom_fields']) ? $data['custom_fields'] : false;
			$email2            = isset($data['email2']) ? $data['email2'] : false;
			$consent_data      = isset($data['consent']) ? $data['consent'] : array();

//			$reg_data->custom_fields = '';

			// Update date if "period" (Registration set 'for all dates')
			if ($reg_data->date == 'period')
			{
				$reg_data->date = '';
			}

			// Control if still ticket left
			$registered = icagendaRegistration::getRegisteredTotal($reg_data->eventid, $reg_data->date, $type_registration);

			// Check number of tickets left
			$tickets_left = $max_nb_of_tickets - $registered;

			// IF NO TICKETS LEFT
			if ($tickets_left <= 0 || $registered >= $max_nb_of_tickets)
			{
				$app->enqueueMessage(Text::_('COM_ICAGENDA_ALERT_NO_TICKETS_AVAILABLE'), 'warning');

				return false;
			}

			// IF NOT ENOUGH TICKETS LEFT
			elseif ($tickets_left < $reg_data->people)
			{
				$msg = Text::_('COM_ICAGENDA_ALERT_NOT_ENOUGH_TICKETS_AVAILABLE') . '<br />';
				$msg.= Text::sprintf('COM_ICAGENDA_ALERT_NOT_ENOUGH_TICKETS_AVAILABLE_NOW', $tickets_left) . '<br />';
				$msg.= Text::_('COM_ICAGENDA_ALERT_NOT_ENOUGH_TICKETS_AVAILABLE_CHANGE_NUMBER');

				$app->enqueueMessage($msg, 'error');

				return false;
			}


			// CONTROL NAME VALUE
			$name_isValid = '1';

			$pattern = "#[/\\\\/\<>/\"%;=\[\]\+()&]|^[0-9]#i";
			$pattern = "#[/\\\\/\<>/\";=\[\]\+()%&]#i";

			// Filter Name
			$data['name'] = str_replace("'", '’', $data['name']);
			
			// Remove non-printable characters.
			$data['name'] = (string) preg_replace('/[\x00-\x1F\x7F]/', '', $data['name']);

			if ($data['name'])
			{
				$nbMatches = preg_match($pattern, $data['name']);

				// Name contains invalid characters
				if ($nbMatches && $nbMatches == 1)
				{
					$name_isValid = '0';
					$app->enqueueMessage(Text::sprintf('COM_ICAGENDA_REGISTRATION_NAME_NOT_VALID', '<strong>' . htmlentities($data['name'], ENT_COMPAT, 'UTF-8') . '</strong>'), 'error');

					return false;
				}

				// Name is less than 2 characters
				// @TODO change minimum to an option or 1 letter ?...
				if (strlen(iCUtf8::utf8_decode(trim($data['name']))) < 2)
				{
					$name_isValid = '0';
					$app->enqueueMessage(Text::_('COM_ICAGENDA_REGISTRATION_NAME_MINIMUM_CHARACTERS'), 'error');

					return false;
				}
			}

			$reg_data->name = filter_var($reg_data->name, FILTER_SANITIZE_STRING);

			// Advanced Checkdnsrr email
			$emailCheckdnsrr = ComponentHelper::getParams('com_icagenda')->get('emailCheckdnsrr', '0');

			if ( ! empty($reg_data->email))
			{
				$validEmail = true;
				$checkdnsrr = true;

				if (($emailCheckdnsrr == 1) && function_exists('checkdnsrr'))
				{
					$provider = explode('@', $reg_data->email);

					if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN')
					{
						if (version_compare(phpversion(), '5.3.0', '<'))
						{
							$checkdnsrr = true;
						}
					}
					else
					{
						$checkdnsrr = checkdnsrr($provider[1]);
					}
				}
				else
				{
					$checkdnsrr = true;
				}
			}
			else
			{
				$checkdnsrr = true;
			}

			// Check if valid email address
			$validEmail = $validEmail ? icagendaRegistration::validEmail($reg_data->email) : false;

			if ( ! $checkdnsrr
				|| ! $validEmail
				&& $reg_data->email
				)
			{
				// message if email is invalid
				$app->enqueueMessage(Text::_('COM_ICAGENDA_REGISTRATION_EMAIL_NOT_VALID'), 'error');

				return false;
			}

			$eventid = (int) $reg_data->eventid;
			$period  = (int) $reg_data->period;
			$people  = (int) $reg_data->people;
			$userid  = (int) $reg_data->userid;
			$name    = $reg_data->name;
			$email   = $reg_data->email;
			$phone   = $reg_data->phone;
			$notes   = html_entity_decode($reg_data->notes);
			$dateReg = $reg_data->date;

			$limitRegEmail = $params->get('limitRegEmail', 1);
			$limitRegDate  = $params->get('limitRegDate', 1);

			if ($limitRegEmail == 1 || $limitRegDate == 1)
			{
				$query = $db->getQuery(true);

				if ($limitRegDate == 0)
				{
					$query = "
						SELECT COUNT(*)
						FROM `#__icagenda_registration`
						WHERE `email` = '$email' AND `eventid`='$eventid' AND `state`='1' AND `status`='1'
					";
				}
				elseif ($limitRegDate == 1)
				{
					$query = "
						SELECT COUNT(*)
						FROM `#__icagenda_registration`
						WHERE `email` = '$email' AND `eventid`='$eventid' AND `date`='$dateReg' AND `state`='1' AND `status`='1'
					";
				}

				$db->setQuery($query);

				if ($email != NULL)
				{
					if ($db->loadResult() > 0)
					{
						$app->enqueueMessage(Text::_( 'COM_ICAGENDA_REGISTRATION_EMAIL_ALERT' ) . ' ' . $reg_data->email, 'warning');

						return false;
					}
				}
			}


			// Control Custom Fields required if not empty
			if ($custom_fields && is_array($custom_fields))
			{
				$requiredEmptyFields = icagendaCustomfields::requiredIsEmpty($custom_fields, 1);

				if ( ! empty($requiredEmptyFields))
				{
					foreach ($requiredEmptyFields as $fieldEmpty)
					{
						$app->enqueueMessage(Text::sprintf('JLIB_FORM_VALIDATE_FIELD_INVALID', $fieldEmpty), 'warning');
					}

					return false;
				}
			}


		// Get Event params
		$evtParams = icagendaEvent::getParams((int) $eventid);
		$iCparams  = ComponentHelper::getParams('com_icagenda');

		// Check for Registration Actions
		$reg_actions = $evtParams->get('registration_actions', $params->get('registration_actions', 0));
//		$reg_actions = ($reg_actions !== 0) ? $reg_actions : '';

		if ($reg_actions)
		{
			$reg_data->state = 0;
			$reg_data->status = 2;
		}
//		else // @todo: check if needed in 3.8
//		{
//			$reg_data->state = 1;
//			$reg_data->status = 3;
//		}


		// Save Registration data.
		$regid = $app->getUserState('com_icagenda.registration.regid', '');

		if ($regid)
		{
			$reg_data->id = $regid;
			$db->updateObject('#__icagenda_registration', $reg_data, 'id');
		}
		else
		{
			$reg_data->id = null;
			$reg_data->custom_fields = '';
			$db->insertObject('#__icagenda_registration', $reg_data, 'id');
		}


		// Save Custom Fields to database.
		if ($custom_fields && is_array($custom_fields))
		{
			icagendaCustomfields::saveToData($custom_fields, $reg_data->id, 1);
		}

		// Save Consents to database.
		if ($consent_data && is_array($consent_data))
		{
			foreach ($consent_data as $key => $value)
			{
				if ($value)
				{
					$key_suffix = '';
					$key_string = $key;

//					if (is_array($value) && $key == 'consent_name')
//					{
//						$key_suffix.= '_';

//						foreach ($value as $k => $v)
//						{
//							$key_suffix.= '_' . $v;
//						}
//					}

					// Create and populate an object.
					$action = new \stdClass();

					$action->user_id        = $reg_data->userid;
					$action->user_action    = $key . $key_suffix;
					$action->parent_form    = 1; // Registration
					$action->parent_id      = $reg_data->id; // Registration ID

					switch($key_string)
					{
						// Name Visibility: Public
						case 'consent_name_public':
							$action->action_subject = Text::_('COM_ICAGENDA_REGISTRATION_CONSENT_NAME_LABEL');
							$action->action_body    = strip_tags(Text::_('COM_ICAGENDA_REGISTRATION_CONSENT_NAME'));
							break;

						// Name Visibility: Users
						case 'consent_name_users':
							$action->action_subject = Text::_('COM_ICAGENDA_REGISTRATION_CONSENT_NAME_LABEL');
							$action->action_body    = strip_tags(Text::_('COM_ICAGENDA_REGISTRATION_' . strtoupper($key_string)));
							break;

						// Terms
						case 'consent_terms':
							$action->action_subject = Text::_('COM_ICAGENDA_REGISTRATION_' . strtoupper($key_string) . '_LABEL');
							$action->action_body    = Text::sprintf('COM_ICAGENDA_REGISTRATION_' . strtoupper($key_string), Text::_('COM_ICAGENDA_REGISTRATION_CONSENT_TERMS_LABEL'));
							break;

						// Default
						default:
							$consentParams = ($evtParams->get($key_string) == '') ? $iCparams : $evtParams;

							if ($consentParams->get($key_string . '_text'))
							{
								$action->action_subject = $consentParams->get($key_string . '_label')
														? Text::_($consentParams->get($key_string . '_label'))
														: Text::_('COM_ICAGENDA_REGISTRATION_' . strtoupper($key_string) . '_LABEL');
								$action->action_body    = strip_tags(Text::_($consentParams->get($key_string . '_text')));
							}
							else
							{
								$action->action_subject = Text::_('COM_ICAGENDA_REGISTRATION_' . strtoupper($key_string) . '_LABEL');
								$action->action_body    = strip_tags(Text::_('COM_ICAGENDA_REGISTRATION_' . strtoupper($key_string)));
							}
							break;
					}

					$action->user_ip        = $app->input->server->get('REMOTE_ADDR', '', 'string');
					$action->user_agent     = $app->input->server->get('HTTP_USER_AGENT', '', 'string');
					$action->state          = 1;
					$action->created_time   = Factory::getDate()->toSql();

					// Insert the object into the user profile table. (@todo: array storing)
					$result = Factory::getDbo()->insertObject('#__icagenda_user_actions', $action);
				}
			}
		}


		// Set Registration ID in the session.
		$app->setUserState('com_icagenda.registration.regid', $reg_data->id);


		if ($reg_actions)
		{
			return $reg_actions;
		}
		else
		{
			$this->sendNotificationEmails($reg_data, '', 'complete');

			$this->cleanCache();

			return 'complete';
		}
	}


	/**
	 * Method to complete action on the form data.
	 *
	 * @since   3.6.13
	 */
	public function actions($action, $data, $regData, $event_id)
	{
		$app = Factory::getApplication();

		if ($action == 'abandon')
		{
			PluginHelper::importPlugin('icagenda');

			$abandon = $app->triggerEvent('onICagendaRegistrationActionsAbandon', array('com_icagenda.registration', $data, $regData['id']));

			if (count($abandon) && in_array(false, $abandon, true))
			{
				return false;
			}
//			else
//			{
//				$this->sendNotificationEmails((object)$regData, $data, 'cancel');
//			}

			// Flush the data from the session.
			$app->setUserState('com_icagenda.registration.data', null);
			$app->setUserState('com_icagenda.registration.regdata', null);
			$app->setUserState('com_icagenda.registration.actions', null);
			$app->setUserState('com_icagenda.registration.regid', null);
		}

		if ($data)
		{
			PluginHelper::importPlugin('icagenda');

			$complete = $app->triggerEvent('onICagendaRegistrationActionsComplete', array('com_icagenda.registration', $data, $regData, $event_id));

			if (count($complete) && in_array(false, $complete, true))
			{
				return false;
			}
			else
			{
				$this->sendNotificationEmails((object)$regData, $data, 'complete');
			}
		}

		return true;
	}


	/**
	 * Method to cancel date(s) registration for one event.
	 *
	 * @since   3.6.13
	 */
	public function cancellation($dates_cancelled = array(), $user_id = null)
	{
		if ( ! empty($dates_cancelled) && (int) $user_id)
		{
			$app  = Factory::getApplication();
			$user = Factory::getUser($user_id);
			$date = Factory::getDate();
			$db   = Factory::getDbo();

			ArrayHelper::toInteger($dates_cancelled);
			$dates_cancelled = implode(',', $dates_cancelled);

			// Conditions for which records should be cancelled.
			$conditions = array(
				$db->quoteName('state') . ' = 1', 
				$db->quoteName('status') . ' = 1', 
				$db->quoteName('id') . ' IN (' . $db->quote($dates_cancelled) . ')', 
				'(' . $db->quoteName('userid') . ' = ' . $db->quote($user_id)
					. 'OR' . $db->quoteName('email') . ' = ' . $db->quote($user->email) . ')',
			);

			// Check if any to be cancelled.
			$query = $db->getQuery(true);
			$query->select('r.*');
			$query->from('#__icagenda_registration AS r');
			$query->where($conditions);
			$db->setQuery($query);
			$result = $db->loadObjectList();

			if ( ! $result) return false;

			// Update registration(s) cancelled
			$query = $db->getQuery(true);

			$user_ip     = $app->input->server->get('REMOTE_ADDR', '', 'string');
			$user_agent  = $app->input->server->get('HTTP_USER_AGENT', '', 'string');
			$date_cancel = $date->toSql();

			$regData = $result[0];
			$regData->dates_cancelled = array();

			// Save user action for each registration cancelled.
			foreach ($result as $k => $v)
			{
				// Create and populate an object.
				$action = new \stdClass();

				$action->user_id        = $user_id;
				$action->user_action    = 'cancel_registration';
				$action->parent_form    = 1; // Registration
				$action->parent_id      = $v->id; // Registration ID
				$action->action_subject = Text::_('COM_ICAGENDA_REGISTRATION_CANCEL_USERACTION_SUBJECT');
				$action->action_body    = strip_tags(Text::_('COM_ICAGENDA_REGISTRATION_CANCEL_USERACTION_BODY'));
				$action->user_ip        = $user_ip;
				$action->user_agent     = $user_agent;
				$action->state          = 1;
				$action->created_time   = $date_cancel;

				// Insert the object into the user profile table.
				$result = $db->insertObject('#__icagenda_user_actions', $action);

				// Update params for each registration cancelled, adding cancelled date and by user id.
//				$params = json_decode($v->params, true);

//				$params['cancelled']    = (string) $date->toSql();
//				$params['cancelled_by'] = (string) $user_id;

				// Store the combined new and existing values back as a JSON string
//				$paramsString = json_encode($params);

//				$query->update($db->quoteName('#__icagenda_registration'))->set($db->quoteName('params') . ' = ' . $db->quote($paramsString));
//				$db->setQuery($query);

				$regData->dates_cancelled[]= $v->date ? $v->date : $v->period;
			}

			// Common fields to update.
			$fields = array(
//				$db->quoteName('state') . ' = 0',
				$db->quoteName('status') . ' = 0',
			);

			$query->update($db->quoteName('#__icagenda_registration'))->set($fields)->where($conditions);
			$db->setQuery($query);

			$result = $db->execute();

			$this->sendNotificationEmails($regData, '', 'cancel');

			$this->cleanCache();

			return $result;
		}
	}


	/**
	 * Method to get event data.
	 *
	 * @param   integer  $pk  The id of the event.
	 *
	 * @return  mixed  Event item data object on success, false on failure.
	 */
	public function getItem($pk = null)
	{
		$app     = Factory::getApplication();
		$session = Factory::getSession();
		$user    = Factory::getUser();

		$input = $app->input;

		$pk = ( ! empty($pk)) ? $pk : (int) $this->getState('event.id');

		if ( ! isset($this->_item[$pk]))
		{
			try
			{
				$db = $this->getDbo();
				$query = $db->getQuery(true)
					->select(
						$this->getState(
							'item.select', 'e.*'
						)
					);
				$query->from('#__icagenda_events AS e');

				// Join on category table.
				$query->select('c.title AS cat_title, c.color AS cat_color')
					->join('LEFT', '#__icagenda_category AS c on c.id = e.catid');

				// Filter by language
				if ($this->getState('filter.language'))
				{
					$query->where('e.language in (' . $db->quote(Factory::getLanguage()->getTag()) . ',' . $db->quote('*') . ')');
				}

				$query->where('e.id = ' . (int) $pk);

				// Features - extract the number of displayable icons per event
				$query->select('feat.count AS features');
				$sub_query = $db->getQuery(true);
				$sub_query->select('fx.event_id, COUNT(*) AS count');
				$sub_query->from('`#__icagenda_feature_xref` AS fx');
				$sub_query->innerJoin("`#__icagenda_feature` AS f ON fx.feature_id=f.id AND f.state=1 AND f.icon<>'-1'");
				$sub_query->group('fx.event_id');
				$query->leftJoin('(' . (string) $sub_query . ') AS feat ON e.id=feat.event_id');

				// Registrations - Get total of registered people
				$evtParams  = icagendaEvent::getParams((int) $pk);

				$typeReg    = $evtParams->get('typeReg', 1);

				$query->select($db->qn('r.count', 'registered'));
				$sub_query  = $db->getQuery(true)
							->select(array(
									$db->qn('r.eventid'),
									'sum(' . $db->qn('r.people') . ') AS count',
								))
							->from($db->qn('#__icagenda_registration', 'r'))
							->where($db->qn('r.state') . ' = 1');

				// Get var event date alias if set or var 'event_date' set to session in event details view
				$event_date = $session->get('event_date', '');
				$get_date   = $input->get('date', ($event_date ? date('Y-m-d-H-i', strtotime($event_date)) : ''));

				// Convert to SQL datetime if set, or return empty.
				$dateday    = icagendaEvent::convertDateAliasToSQLDatetime($get_date);

				// Redirect and remove date var, if not correctly set
				if ($get_date
					&& ! $dateday)
				{
					$event_url = Uri::getInstance()->toString();
					$cleanurl  = preg_replace('/&date=[^&]*/', '', $event_url);
					$cleanurl  = preg_replace('/\?date=[^\?]*/', '', $cleanurl);

					$app->redirect($cleanurl);

					return false;
				}

				// Registration type: by single date/period (1)
				if ($dateday && $typeReg == 1)
				{
//					$sub_query->where('r.date = ' . $db->q($dateday)); // This is the good logic if correctly set
					$sub_query->where('(r.date = ' . $db->q($dateday) . ' OR (r.date = "" AND r.period = 1))');
				}
				elseif ( ! $dateday && $typeReg == 1)
				{
//					$sub_query->where('r.date = "" AND r.period = 0'); // This is the good logic if correctly set
					$sub_query->where('r.date = ""');
				}

				$sub_query->group('r.eventid');
				$query->leftJoin('(' . (string) $sub_query . ') AS r ON e.id = r.eventid');

				$db->setQuery($query);

				$item = $db->loadObject();

//				if (empty($item))
//				{
//					throw new \Exception(Text::_('COM_ICAGENDA_ERROR_EVENT_NOT_FOUND'), 404);
//				}
//				else
				if ($item)
				{
					// Convert parameter fields to objects.
					$registry = new Registry;
					$registry->loadString($item->params);

					// Merge Event params to app params
					$item->params = clone $this->getState('params');
					$item->params->merge($registry);

					// iCagenda event view variables
//					$item->typeReg             = $item->params->get('typeReg', ''); // DEPRECATED 3.6.0
//					$item->maxNbTicketsPerReg  = icagendaRegistration::maxNbTicketsPerReg($item->params); // DEPRECATED (to be refactoried)
//					$item->maxNbTickets = icagendaRegistration::maxNbTickets($item); // DEPRECATED 3.6.0

					$item->typeReg      = $item->params->get('typeReg', '1');
					$item->maxReg       = $item->params->get('maxReg', '1000000');
					$maxNbTicketsPerReg = icagendaRegistration::maxNbTicketsPerReg($item->params);

					// Set default nb of tickets bookable
					$session_date       = $session->get('session_date', '');

					$item->ticketsBookable = icagendaRegistration::getTicketsBookable($item->id, $session_date, $item->typeReg, $item->maxReg, $maxNbTicketsPerReg);

					if ($session_date)
					{
						$item->default_tickets      = $item->ticketsBookable;
					}

					$item->tickets      = ($item->maxReg >= $maxNbTicketsPerReg) ? $maxNbTicketsPerReg : $item->maxReg;

					$item->eventHasPeriod      = icagendaEvent::eventHasPeriod($item->period, $item->startdate, $item->enddate);
					$item->periodIsNotFinished = icagendaEvent::periodIsNotFinished($item->enddate);

					// Extract the feature details, if needed
					if (is_null($item->features))
					{
						$item->features = array();
					}
					else
					{
						$db = $this->getDbo();
						$query = $db->getQuery(true);
						$query->select('DISTINCT f.icon, f.icon_alt');
						$query->from('`#__icagenda_feature_xref` AS fx');
						$query->innerJoin("`#__icagenda_feature` AS f ON fx.feature_id=f.id AND f.state=1 AND f.icon<>'-1'");
						$query->where('fx.event_id=' . $item->id);
						$query->order('f.ordering DESC'); // Order descending because the icons are floated right
						$db->setQuery($query);
						$item->features = $db->loadObjectList();
					}
				}

				$this->_item[$pk] = $item;
			}
			catch (\Exception $e)
			{
				if ($e->getCode() == 404)
				{
					// Need to go thru the error handler to allow Redirect to work.
					throw new \Exception($e->getMessage(), 404);
				}
				else
				{
					$this->setError($e);
					$this->_item[$pk] = false;
				}
			}
		}

		return $this->_item[$pk];
	}

	/**
	 * Method to get registration data.
	 *
	 * @param   integer  $regid  The id of the registration.
	 *
	 * @return  mixed  Registration processed data object on success, false on failure.
	 */
	public function getRegistration($regid = null)
	{
		$app = Factory::getApplication();

		$regid = ( ! empty($regid)) ? $regid : $app->getUserState('com_icagenda.registration.regid', '');

		// Flush the data from the session.
		if ($app->input->get('layout') == 'complete')
		{
			$app->setUserState('com_icagenda.registration.regid', null);
		}

		if ($regid)
		{
			try
			{
				$db = $this->getDbo();
				$query = $db->getQuery(true)
					->select(
						$this->getState(
							'item.select', 'r.*'
						)
					);
				$query->from('#__icagenda_registration AS r');
				$query->where('r.id = ' . (int) $regid);
				$db->setQuery($query);

				$registration = $db->loadObject();

				if (empty($registration))
				{
					return false;
				}
			}
			catch (\Exception $e)
			{
				if ($e->getCode() == 404)
				{
					// Need to go thru the error handler to allow Redirect to work.
					throw new \Exception($e->getMessage(), 404);
				}
				else
				{
					$this->setError($e);
					$registration = false;
				}
			}
		}
		else
		{
			return false;
		}

		return $registration;
	}

	/**
	 * Method to get registrations of the current user for one event.
	 *
	 * @param   integer  $eventID  The id of the event.
	 *
	 * @since   3.6.13
	 */
	public function getParticipantEventRegistrations($eventID = null) // getRegistrationsPublished
	{
		$app  = Factory::getApplication();
		$user = Factory::getUser();

//		$eventID = ( ! empty($eventID)) ? $eventID : $app->input->getInt('id');
		$eventID = ( ! empty($eventID)) ? $eventID : (int) $this->getState('event.id');

		if ($eventID && $user->get('id'))
		{
			try
			{
				$db = $this->getDbo();
				$query = $db->getQuery(true);

				$query->select(
					[
						$db->quoteName('r.id'),
						$db->quoteName('r.state'),
						$db->quoteName('r.date'),
						$db->quoteName('r.period'),
						$db->quoteName('r.name')
					]
				)
					->from($db->quoteName('#__icagenda_registration', 'r'))
					->where($db->quoteName('r.state') . ' = 1')
					->where($db->quoteName('r.status') . ' = 1')
					->where($db->quoteName('r.eventid') . ' = ' . (int) $eventID)

					->where('(' . $db->quoteName('r.userid') . ' = ' . (int) $user->get('id')
							. ' OR ' . $db->quoteName('r.email') . ' = ' . $db->quote($user->email) . ')');

				$db->setQuery($query);

				$registrationIDs = $db->loadObjectList();

				if (empty($registrationIDs))
				{
					return false;
				}
			}
			catch (\Exception $e)
			{
				if ($e->getCode() == 404)
				{
					// Need to go thru the error handler to allow Redirect to work.
					throw new \Exception($e->getMessage(), 404);
				}
				else
				{
					$this->setError($e);
					$registrationIDs = false;
				}
			}
		}
		else
		{
			return false;
		}

		return $registrationIDs;
	}


	/**
	 * Method to send Notification emails.
	 *
	 * @since   3.6.13
	 */
	private function sendNotificationEmails($reg_data, $extraData = null, $action = null)
	{
		$app  = Factory::getApplication();
		$db   = Factory::getDbo();
		$user = Factory::getUser();

		$input  = $app->input;
		$params = $app->getParams();

		$defaultemail          = $params->get('regEmailUser', '1');
		$emailUserSend         = $params->get('emailUserSend', '1');
		$emailAdminSend        = $params->get('emailAdminSend', '1');
		$emailAdminSend_select = $params->get('emailAdminSend_select', array('0'));
		$emailAdminSend_custom = $params->get('emailAdminSend_Placeholder', '');

		// Get the site name
		$sitename = $app->getCfg('sitename');
		$siteURL  = Uri::base();
		$siteURL  = rtrim($siteURL, '/');

		$eventID = $input->getInt('eventID');

		// Get Event Details
		$query = $db->getQuery(true);
		$query->select('e.*,
				e.created_by AS authorID, e.email AS contactemail, e.phone AS contactphone')
			->from('#__icagenda_events AS e')
			->where('e.id = ' . (int) $eventID);
		$db->setQuery($query);
		$event = $db->loadObject();

		$authorID    = $event->authorID;
		$displayTime = $event->displaytime;

		// Get Author Email
		$authormail = '';

		if ((int) $authorID)
		{
			$query = $db->getQuery(true);
			$query->select('email AS authormail, name AS authorname')
				->from($db->quoteName('#__users', 'u'))
				->where($db->quoteName('u.id') . ' = ' . (int) $authorID);
			$db->setQuery($query);
			$result = $db->loadObject();

			$authormail = $result->authormail;
			$authorname = $result->authorname;

			if ( ! $authormail)
			{
				$authormail = $app->getCfg('mailfrom');
			}
		}

		// Set Date in url
		$dateInUrl  = ($reg_data->date && $params->get('datesDisplay', 1) == 1)
					? '&amp;date=' . iCDate::dateToAlias($reg_data->date, 'Y-m-d-H-i')
					: '';

		// Set URLs
		$baseURL    = Uri::base();
		$subpathURL = Uri::base(true);

		$baseURL    = str_replace('/administrator', '', $baseURL);
		$subpathURL = str_replace('/administrator', '', $subpathURL);

		// Sub Path filtering
		$subpathURL = ltrim($subpathURL, '/');

		// URL Event Details filtering
//		$urlEvent = str_replace('&amp;', '&', Route::_('index.php?option=com_icagenda&view=event&id=' . (int) $eventID . $dateInUrl));
//		$urlEvent = ltrim($urlEvent, '/');

		$vars   = ($reg_data->date && $params->get('datesDisplay', 1) == 1)
				? array('date' => iCDate::dateToAlias($reg_data->date, 'Y-m-d-H-i'))
				: '';
		$urlEvent = icagendaEvent::url((int) $eventID, $input->get('alias'), $reg_data->itemid, $vars);
		$urlEvent = ltrim($urlEvent, '/');

		if (substr($urlEvent, 0, strlen($subpathURL) + 1) == "$subpathURL/")
		{
			$urlEvent = substr($urlEvent, strlen($subpathURL) + 1);
		}

		$urlEvent = rtrim($baseURL, '/') . '/' . ltrim($urlEvent, '/');

		// URL Registration Cancellation filtering
		$urlRegistrationCancel = str_replace('&amp;', '&', Route::_('index.php?option=com_icagenda&view=registration&layout=cancel&id=' . (int) $eventID . '&Itemid=' . $reg_data->itemid));
		$urlRegistrationCancel = ltrim($urlRegistrationCancel, '/');

		if (substr($urlRegistrationCancel, 0, strlen($subpathURL) + 1) == "$subpathURL/")
		{
			$urlRegistrationCancel = substr($urlRegistrationCancel, strlen($subpathURL) + 1);
		}

		$urlRegistrationCancel = rtrim($baseURL, '/') . '/' . ltrim($urlRegistrationCancel, '/');

		// Dates formatting Registration notification
		$startDate = strip_tags(icagendaRender::dateToFormat($event->startdate));
		$endDate   = strip_tags(icagendaRender::dateToFormat($event->enddate));

		$startTime = ! empty($displayTime) ? icagendaRender::dateToTime($event->startdate) : '';
		$endTime   = ! empty($displayTime) ? icagendaRender::dateToTime($event->enddate) : '';

		$regDate = strip_tags(icagendaRender::dateToFormat($reg_data->date));
		$regTime = icagendaRender::dateToTime($reg_data->date);

		$regDateTime      = ! empty($displayTime) ? $regDate . ' ' . $regTime : $regDate;
		$regStartDateTime = ! empty($displayTime) ? $startDate . ' ' . $startTime : $startDate;
		$regEndDateTime   = ! empty($displayTime) ? $endDate . ' ' . $endTime : $endDate;

		$regPeriod = $reg_data->period;

		// Generates filled custom fields into email body
		$customfields = icagendaCustomfields::getListNotEmpty($reg_data->id, 1);

		$custom_fields = '';

		$newline = ($defaultemail == '0') ? "<br />" : "\n";

		if ($customfields)
		{
			foreach ($customfields as $customfield)
			{
				$cf_value = isset($customfield->cf_value) ? $customfield->cf_value : Text::_('IC_NOT_SPECIFIED');

				$custom_fields.= Text::_($customfield->cf_title) . ": " . Text::_($cf_value) . $newline;
			}
		}

		// Set filled form fields
		$fieldEmail = $reg_data->email ? $reg_data->email : Text::_('COM_ICAGENDA_NOT_SPECIFIED');
		$fieldPhone = $reg_data->phone ? $reg_data->phone : Text::_('COM_ICAGENDA_NOT_SPECIFIED');
		

		// MAIL REPLACEMENTS
		$replacements = array(
			'\\n'              => '\n',
			'[SITENAME]'       => $sitename,
			'[SITEURL]'        => $siteURL,
			'[AUTHOR]'         => $authorname,
			'[AUTHOREMAIL]'    => $authormail,
			'[CONTACTEMAIL]'   => $event->contactemail,
			'[CONTACTPHONE]'   => $event->contactphone,
			'[TITLE]'          => $event->title,
			'[SHORTDESC]'      => $event->shortdesc,
			'[DESC]'           => $event->desc,
			'[METADESC]'       => $event->metadesc,
			'[EVENTID]'        => (int) $eventID,
			'[EVENTURL]'       => $urlEvent,
			'[VENUE]'          => $event->place,
			'[URL_CANCEL]'     => $reg_data->userid
									? $urlRegistrationCancel
									: '',
			'[NAME]'           => $reg_data->name,
			'[USERID]'         => $reg_data->userid,
			'[EMAIL]'          => $reg_data->email
									? $reg_data->email
									: Text::_('COM_ICAGENDA_NOT_SPECIFIED'),
			'[PHONE]'          => $reg_data->phone
									? $reg_data->phone
									: Text::_('COM_ICAGENDA_NOT_SPECIFIED'),
			'[TICKETS]'        => $reg_data->people,
			'[CUSTOMFIELDS]'   => $custom_fields,
			'[NOTES]'          => html_entity_decode($reg_data->notes),
			'[STARTDATE]'      => $startDate,
			'[ENDDATE]'        => $endDate,
			'[DATE]'           => $regDate          ? $regDate          : '',
			'[TIME]'           => $regDate          ? $regTime          : '',
			'[DATETIME]'       => ($regPeriod != 1) ? $regDateTime      : Text::_('COM_ICAGENDA_REG_FOR_ALL_DATES'),
			'[STARTDATETIME]'  => $startDate        ? $regStartDateTime : '',
			'[ENDDATETIME]'    => $endDate          ? $regEndDateTime   : '',
			'&nbsp;'           => ' ',
			'[STRING_EMAIL]'   => Text::sprintf('COM_ICAGENDA_REGISTRATION_EMAIL_STRING_EMAIL', $fieldEmail),
			'[STRING_NAME]'    => Text::sprintf('COM_ICAGENDA_REGISTRATION_EMAIL_STRING_NAME', $reg_data->name),
			'[STRING_NOTES]'   => $params->get('notesDisplay', 0)
									? Text::sprintf('COM_ICAGENDA_REGISTRATION_EMAIL_STRING_NOTES', html_entity_decode($reg_data->notes))
									: '',
			'[STRING_PHONE]'   => $params->get('phoneDisplay', 0)
									? Text::sprintf('COM_ICAGENDA_REGISTRATION_EMAIL_STRING_PHONE', $fieldPhone)
									: '',
			'[STRING_TICKETS]' => Text::sprintf('COM_ICAGENDA_REGISTRATION_EMAIL_STRING_TICKETS', $reg_data->people),
			'[PLACES]'         => $reg_data->people,
		);



		if ($action == 'complete')
		{
//			$defaultUserSubjectPeriod = Text::_('COM_ICAGENDA_REGISTRATION_EMAIL_USER_PERIOD_DEFAULT_SUBJECT');
//			$emailUserSubjectPeriod   = $params->get('emailUserSubjectPeriod', $defaultUserSubjectPeriod);

//			$defaultUserBodyPeriod    = Text::_('COM_ICAGENDA_REGISTRATION_EMAIL_USER_PERIOD_DEFAULT_BODY');
//			$emailUserBodyPeriod      = $params->get('emailUserBodyPeriod', $defaultUserBodyPeriod);

//			$defaultUserSubjectDate   = Text::_('COM_ICAGENDA_REGISTRATION_EMAIL_USER_DATE_DEFAULT_SUBJECT');
//			$emailUserSubjectDate     = $params->get('emailUserSubjectDate', $defaultUserSubjectDate);

//			$defaultUserBodyDate      = Text::_('COM_ICAGENDA_REGISTRATION_EMAIL_USER_DATE_DEFAULT_BODY');
//			$emailUserBodyDate        = $params->get('emailUserBodyDate', $defaultUserBodyDate);


			$bodyAdmin    = Text::_('COM_ICAGENDA_REGISTRATION_NEW_EMAIL_ADMIN_DEFAULT_BODY');
			$subjectAdmin = Text::_('COM_ICAGENDA_REGISTRATION_NEW_EMAIL_ADMIN_DEFAULT_SUBJECT');

			$defaultUserBody    = Text::_('COM_ICAGENDA_REGISTRATION_NEW_EMAIL_USER_DEFAULT_BODY');
			$defaultUserSubject = Text::_('COM_ICAGENDA_REGISTRATION_NEW_EMAIL_USER_DEFAULT_SUBJECT');
//			$customUserBody     = $params->get('emailUserBodyDate', $defaultUserBody);
//			$customUserSubject  = $params->get('emailUserSubjectDate', $defaultUserSubject);

			// B/C for Custom Emails in global options
			$emailUserBodyDate      = $params->get('emailUserBodyDate', $defaultUserBody);
			$emailUserBodyPeriod    = $params->get('emailUserBodyPeriod', $defaultUserBody);
			$emailUserSubjectDate   = $params->get('emailUserSubjectDate', $defaultUserSubject);
			$emailUserSubjectPeriod = $params->get('emailUserSubjectPeriod', $defaultUserSubject);


			$registered_row_suffix = '_1'; // To extend when multiple-dates registration integrated.

			$dates_registered_list = Text::_('COM_ICAGENDA_REGISTRATION_NEW_EMAIL_DATES_REGISTERED_LABEL' . $registered_row_suffix);

			$subjectUser = $defaultUserSubject;
			$bodyUser    = $defaultUserBody;

			// Registration Type is 'select list of dates' (single dates + period)
			// Period (no 'date', and 'period' = 0)
			if ($reg_data->date == '' && ! $regPeriod)
			{
				$registered_date = $regStartDateTime . ' - ' . $regEndDateTime;

//				$bodyAdmin = Text::_( 'COM_ICAGENDA_REGISTRATION_EMAIL_ADMIN_PERIOD_DEFAULT_BODY' );

				if ($defaultemail == 0)
				{
					$subjectUser = $emailUserSubjectPeriod;
					$bodyUser    = $emailUserBodyPeriod;
				}
//				else
//				{
//					$subjectUser = $defaultUserSubjectPeriod;
//					$bodyUser    = $defaultUserBodyPeriod;
//				}
			}

			// Registration Type is 'select list of dates' (single dates + period) OR 'for all dates'
			// Single date OR all event date(s)/period
			else
			{
				$registered_date = ($regPeriod != 1) ? $regDateTime : Text::_('COM_ICAGENDA_REG_FOR_ALL_DATES');

//				$bodyAdmin = Text::_( 'COM_ICAGENDA_REGISTRATION_EMAIL_ADMIN_DATE_DEFAULT_BODY' );

				if ($defaultemail == 0)
				{
					$subjectUser = $emailUserSubjectDate;
					$bodyUser    = $emailUserBodyDate;
				}
//				else
//				{
//					$subjectUser = $defaultUserSubjectDate;
//					$bodyUser    = $defaultUserBodyDate;
//				}
			}

			$dates_registered_list .= Text::sprintf('COM_ICAGENDA_REGISTRATION_NEW_EMAIL_DATES_REGISTERED_LIST_ROW' . $registered_row_suffix, html_entity_decode($registered_date));

			// Cancel option
			if ($params->get('reg_cancellation', 0))
			{
				$cancellation_string    = $reg_data->userid
										? Text::sprintf('COM_ICAGENDA_REGISTRATION_EMAIL_STRING_CANCELLATION_LINK', $authormail, $urlRegistrationCancel)
										: Text::sprintf('COM_ICAGENDA_REGISTRATION_EMAIL_STRING_CANCELLATION_EMAIL', $authormail);
			}
			else
			{
				$cancellation_string    = '';
			}

			$newReplacements = array(
				'[STRING_CANCELLATION]'   => $cancellation_string,
				'[DATES_REGISTERED_LIST]' => $dates_registered_list,
			);

			// Tags $replacements will replace or override tags $newReplacements.
			$replacements = array_merge($newReplacements, $replacements);


			if ($extraData)
			{
				PluginHelper::importPlugin('icagenda');

				$extraReplacements = $app->triggerEvent('onICagendaRegistrationActionsCompleteEmail', array('com_icagenda.registration', $extraData, $reg_data));

				list($extraReplacements) = $extraReplacements;

				// Tags $extraReplacements will replace or override tags $replacements.
				$replacements = array_merge($replacements, $extraReplacements);
			}
		}
		elseif ($action == 'cancel')
		{
			$subjectAdmin = Text::_('COM_ICAGENDA_REGISTRATION_CANCEL_EMAIL_ADMIN_DEFAULT_SUBJECT');
			$subjectUser  = Text::_('COM_ICAGENDA_REGISTRATION_CANCEL_EMAIL_USER_DEFAULT_SUBJECT');

			$bodyAdmin = Text::_('COM_ICAGENDA_REGISTRATION_CANCEL_EMAIL_ADMIN_DEFAULT_BODY');
			$bodyUser  = Text::_('COM_ICAGENDA_REGISTRATION_CANCEL_EMAIL_USER_DEFAULT_BODY');

			if (isset($reg_data->dates_cancelled))
			{
				$dates_cancelled_list = Text::plural('COM_ICAGENDA_REGISTRATION_CANCEL_EMAIL_DATES_CANCELLED_LABEL', count($reg_data->dates_cancelled));
//				$app->enqueueMessage(Text::plural('COM_ICAGENDA_REGISTRATION_CANCEL_EMAIL_DATES_CANCELLED_LABEL', count($reg_data->dates_cancelled)));

				foreach ($reg_data->dates_cancelled as $date)
				{
					if (iCDate::isDate($date))
					{
						$cancelled_date = icagendaRender::dateToFormat($date) . ' ' . icagendaRender::dateToTime($date);
					}
					elseif ($date == 1)
					{
						$cancelled_date = Text::_('COM_ICAGENDA_REGISTRATION_CANCEL_ALL_DATES');
					}
					else
					{
						$cancelled_date = icagendaRender::dateToFormat($event->startdate) . ' ' . icagendaRender::dateToTime($event->startdate)
										. ' - ' . icagendaRender::dateToFormat($event->enddate) . ' ' . icagendaRender::dateToTime($event->enddate);
					}

					$cancelled_row_suffix = (count($reg_data->dates_cancelled) == 1) ? '_1' : '';

					$dates_cancelled_list .= Text::sprintf('COM_ICAGENDA_REGISTRATION_CANCEL_EMAIL_DATES_CANCELLED_LIST_ROW' . $cancelled_row_suffix, html_entity_decode($cancelled_date));
//					$app->enqueueMessage('- ' . $cancelled_date . '<br />');
				}
			}

//			$cancelReplacements = array(
//				'[DATES_CANCELLED_LIST]' => $dates_cancelled_list,
//			);

//			$replacements = array_merge($replacements, $cancelReplacements);
			$replacements['[DATES_CANCELLED_LIST]'] = $dates_cancelled_list;
		}

		// Process tags replacements
		foreach ($replacements as $key => $value)
		{
			$subjectUser  = str_replace($key, $value, $subjectUser);
			$bodyUser     = str_replace($key, $value, $bodyUser);
			$subjectAdmin = str_replace($key, $value, $subjectAdmin);
			$bodyAdmin    = str_replace($key, $value, $bodyAdmin);
		}

		// Individual Custom Field tag replacements.
		$eventCustomfields = icagendaCustomfields::getListNotEmpty((int) $eventID, 2);
		$eventCustomfields = $eventCustomfields ?: [];
		$allCustomfields   = $customfields ? array_merge($customfields, $eventCustomfields) : $eventCustomfields;

		// Expression to search for CUSTOMFIELD tag.
		$regex = '/\[CUSTOMFIELD[\s:](.*?)\]/i';

		// Find all instances and put in $matches for load custom field.
		// $match[0] is full pattern match, $match[1] is the slug.
		preg_match_all($regex, $bodyUser, $matches, PREG_SET_ORDER);

		// No matches, skip this
		if ($matches && \is_array($allCustomfields)) {
			foreach ($matches as $match) {
				$output = Text::_('COM_ICAGENDA_NOT_SPECIFIED');

				$match[1]    = str_replace(':', ' ', $match[1]);
				$matcheslist = explode(' ', $match[1]);

				$slug = trim($matcheslist[0]);

				foreach ($allCustomfields as $customfield) {
					if ($customfield->cf_slug == $slug) {
						$output = $customfield->cf_value;
						break;
					}
				}

				$bodyUser = str_replace($match[0], $output, $bodyUser);
			}
		}
		// Debug Dev
//		$app->enqueueMessage($bodyUser);
//		return false;

		// Set Sender of USER and ADMIN emails (@todo: add option to set sender)
		$mailer      = Factory::getMailer();
		$adminmailer = Factory::getMailer();

		$mailfrom = $app->getCfg('mailfrom');
		$fromname = $app->getCfg('fromname');

		$mailer->setSender(array( $mailfrom, $fromname ));
		$adminmailer->setSender(array( $mailfrom, $fromname ));

		// Set Recipient of USER email
		if ( ! isset($reg_data->email))
		{
			$recipient = $user->email;
		}
		else
		{
			$recipient = $reg_data->email;
		}

		if ($recipient)
		{
			$mailer->addRecipient($recipient);
		}

		// Set Recipient of ADMIN email
		$admin_array = array();

		if (in_array('0', $emailAdminSend_select))
		{
			$admin_array[] = $mailfrom;
		}

		if (in_array('1', $emailAdminSend_select))
		{
			if ($adminmailer->validateAddress($authormail))
			{
				$admin_array[] = $authormail;
			}
		}

		if (in_array('2', $emailAdminSend_select))
		{
			$custom_emails = explode(',', $emailAdminSend_custom);
			$custom_emails = str_replace(' ','', $custom_emails);

			foreach ($custom_emails as $custom_email)
			{
				if ($adminmailer->validateAddress($custom_email))
				{
					$admin_array[] = $custom_email;
				}
			}
		}

		if (in_array('3', $emailAdminSend_select))
		{
			if ($adminmailer->validateAddress($event->contactemail))
			{
				$admin_array[] = $event->contactemail;
			}
		}

		// Set ADMIN recipient
		$adminrecipient = count($admin_array) ? $admin_array : $mailfrom;
		$adminmailer->addRecipient($adminrecipient);

		// FIX Joomla 3.5.1 issue on some servers, by addition of "Optional" ReplyTo, not previously set.
		$mailer->addReplyTo($mailfrom, $fromname);
		$adminmailer->addReplyTo($mailfrom, $fromname);

		// Set Subject of USER and ADMIN email
		$mailer->setSubject($subjectUser);
		$adminmailer->setSubject($subjectAdmin);

		// Set Encoding for USER email
		if ($defaultemail == 0
			&& $action == 'complete')
		{
			// HTML custom notification email send to user
			$mailer->isHTML(true);

			// JDocs: When sending HTML emails you should normally set the Encoding to base64
			//        in order to avoid unwanted characters in the output.
			//        See https://docs.joomla.org/Sending_email_from_extensions
			$mailer->Encoding = 'base64'; // JDocs Sending HTML Email
		}

		$bodyAdmin = str_replace("<br />", "\n", $bodyAdmin);

		// Set Body of USER and ADMIN email
		$mailer->setBody($bodyUser);
		$adminmailer->setBody($bodyAdmin);


		// Send USER email confirmation, if enabled
		if ($emailUserSend == 1
			&& isset($reg_data->email)
			&& $reg_data->email
			)
		{
			// Try to send the user confirmation email.
			try {
				$return = $mailer->send();
			} catch (\Exception $exception) {
				try {
					Log::add(Text::_($exception->getMessage()), Log::WARNING, 'icagenda');

					$return = false;
				} catch (\RuntimeException $exception) {
					Factory::getApplication()->enqueueMessage(Text::_($exception->errorMessage()), 'warning');

					$return = false;
				}
			}

			// Check for an error.
			if ($return !== true) {
				$this->setError(Text::_('COM_USERS_MAIL_FAILED'));

				return false;
			}
		}

		// Send ADMIN email notification, if enabled
		if ($emailAdminSend == 1
			&& isset($reg_data->eventid)
			&& $reg_data->eventid != '0'
			&& $reg_data->name != NULL
			)
		{
			// Try to send the admin notification email.
			try {
				$return = $adminmailer->send();
			} catch (\Exception $exception) {
				try {
					Log::add(Text::_($exception->getMessage()), Log::WARNING, 'icagenda');

					$return = false;
				} catch (\RuntimeException $exception) {
					Factory::getApplication()->enqueueMessage(Text::_($exception->errorMessage()), 'warning');

					$return = false;
				}
			}

			// Check for an error.
			if ($return !== true) {
				$this->setError(Text::_('COM_ICAGENDA_MAIL_FAILED'));

				return false;
			}
		}
	}

	/**
	 * Cleans the cache of com_icagenda and iCagenda modules
	 *
	 * @param   string   $group     The cache group
	 * @param   integer  $clientId  No longer used, will be removed without replacement
	 *                              @deprecated   4.3 will be removed in 6.0
	 *
	 * @return  void
	 *
	 * @since   3.8.22
	 */
	protected function cleanCache($group = null, $clientId = 0)
	{
		parent::cleanCache('com_icagenda');
		parent::cleanCache('mod_iccalendar');
		parent::cleanCache('mod_ic_event_list');
	}
}
