<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.9.8 2024-12-18
 *
 * @package     iCagenda.Site
 * @subpackage  src.Model
 * @link        https://www.icagenda.com
 *
 * @author      Cyril Rezé
 * @copyright   (c) 2012-2024 Cyril Rezé / iCagenda. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 *
 * @since       3.2
 *----------------------------------------------------------------------------
*/

namespace WebiC\Component\iCagenda\Site\Model;

\defined('_JEXEC') or die;

use iClib\Date\Date as iCDate;
use iClib\Date\Period as iCDatePeriod;
use iClib\Filter\Output as iCFilterOutput;
use iClib\Url\Url as iCUrl;
use iClib\String\StringHelper as iCString;
use iCutilities\Customfields\Customfields as icagendaCustomfields;
use iCutilities\Event\Event as icagendaEvent;
use iCutilities\Menus\Menus as icagendaMenus;
use Joomla\CMS\Access\Access;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Filesystem\File;
use Joomla\CMS\Filesystem\Folder;
use Joomla\CMS\Filter\OutputFilter;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Log\Log;
use Joomla\CMS\MVC\Model\FormModel;
use Joomla\CMS\Plugin\PluginHelper;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Table\Table;
use Joomla\CMS\Uri\Uri;
use Joomla\Registry\Registry;
use Joomla\String\StringHelper;

/**
 * iCagenda Component Submit Model
 */
class SubmitModel extends FormModel
{
	/**
	 * Model context string.
	 *
	 * @var  string
	 */
	protected $_context = 'com_icagenda.submit';

	/**
	 * Form Data.
	 *
	 * @var  object
	 */
	protected $data;

	/**
	 * Method to get the submit form data.
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

			// Override the event data with any data in the session.
			$temp = (array) $app->getUserState('com_icagenda.submit.data', array());

			foreach ($temp as $k => $v)
			{
				$this->data->$k = $v;
			}
		}

		return $this->data;
	}


	/**
	 * Method to get the submit form.
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
		$form = $this->loadForm('com_icagenda.submit', 'submit', array('control' => 'jform', 'load_data' => $loadData));

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
			$form->setValue('created_by', null, $user_id);

			// logged-in Users: Name/User Name Option
			$user_name = ($params->get('nameJoomlaUser', 1) == 1) ? $user->get('name') : $user->get('username');
			$user_mail = $user->get('email');
		}
		else
		{
			$form->setFieldAttribute('created_by', 'disabled', 'disabled');

			$user_name = '';
			$user_mail = '';
		}

		// Set 'Name' attributes
		if ($user_name)
		{
			$form->setValue('username', null, $user_name);
			$form->setFieldAttribute('username', 'readonly', 'true');
			$form->setFieldAttribute('username', 'description', '');
			$form->removeField('note_username');
		}
		else
		{
			$form->setFieldAttribute('username', 'required', 'true');
		}

		// Set 'Email' attributes
		if ($user_mail)
		{
			$form->setValue('created_by_email', null, $user_mail);
			$form->setFieldAttribute('created_by_email', 'readonly', 'true');
			$form->setFieldAttribute('created_by_email', 'description', '');
			$form->removeField('note_created_by_email');
		}
		else
		{
			$form->setFieldAttribute('created_by_email', 'required', 'true');
		}

		// Remove Language field if hidden
		if ($params->get('submit_language_display', 0) == 0) {
			$form->removeField('language_event');
			$form->removeField('note_language');
		}

		// Remove Event Image field if hidden
		if ($params->get('submit_imageDisplay', 1) == 0)
		{
			$form->removeField('image');
			$form->removeField('note_image');
		}

		// Set time format calendar form field
		$ampm = ($params->get('timeformat', 1) == 1) ? '24' : '12';

		$form->setFieldAttribute('startdate', 'timeformat', $ampm);
//		$form->setFieldAttribute('startdate', 'weeknumbers', 'false');
		$form->setFieldAttribute('enddate', 'timeformat', $ampm);
//		$form->setFieldAttribute('enddate', 'weeknumbers', 'false');

		// Remove Period fields if hidden
		if ($params->get('submit_periodDisplay', 1) == 0)
		{
			$form->removeField('period_title');
			$form->removeField('startdate');
			$form->removeField('note_startdate');
			$form->removeField('enddate');
			$form->removeField('note_enddate');
			$form->removeField('weekdays');
			$form->removeField('note_weekdays');
		}
		elseif ($params->get('submit_periodDisplay', 1) && $params->get('submit_weekdaysDisplay', 1) == 0)
		{
			$form->removeField('weekdays');
			$form->removeField('note_weekdays');
		}

		// Remove Single Dates field if hidden
		if ($params->get('submit_datesDisplay', 1) == 0)
		{
			$form->removeField('dates_title');
			$form->removeField('note_dates');
			$form->removeField('dates');
		}
		elseif ($params->get('submit_periodDisplay', 1) == 0)
		{
			$form->setFieldAttribute('dates', 'min', '1');
		}

		// Remove option Show Time field if hidden
		if ($params->get('submit_displaytimeDisplay', 0) == 0)
		{
			$form->removeField('displaytime_title');
			$form->removeField('displaytime');
			$form->removeField('note_displaytime');
		}
		// Set default Time Display setting
		elseif ($params->get('displaytime', 1) == 0)
		{
			$form->setFieldAttribute('displaytime', 'default', '0');
		}

		// Remove Short Description field if hidden
		if ($params->get('submit_shortdescDisplay', 1) == 0)
		{
			$form->removeField('shortdesc_title');
			$form->removeField('shortdesc');
			$form->removeField('note_shortdesc');
		}

		// Remove Description field if hidden
		if ($params->get('submit_descDisplay', 1) == 0)
		{
			$form->removeField('desc_title');
			$form->removeField('desc');
			$form->removeField('note_desc');
		}

		// Remove Meta Description field if hidden
		if ($params->get('submit_metadescDisplay', 0) == 0)
		{
			$form->removeField('metadesc_title');
			$form->removeField('metadesc');
			$form->removeField('note_metadesc');
		}

		// Remove Place (venue) field if hidden
		if ($params->get('submit_venueDisplay', 1) == 0)
		{
			$form->removeField('place');
			$form->removeField('note_place');
		}

		// Remove Website field if hidden
		if ($params->get('submit_websiteDisplay', 1) == 0)
		{
			$form->removeField('website');
			$form->removeField('note_website');
		}

		if ($params->get('submit_venueDisplay', 1) == 0
			&& $params->get('submit_websiteDisplay', 1) == 0)
		{
			$form->removeField('global_info_title');
		}

		// Remove Email (contact) field if hidden
		if ($params->get('submit_emailDisplay', 1) == 0)
		{
			$form->removeField('email');
			$form->removeField('note_email');
		}

		// Remove Phone (contact) field if hidden
		if ($params->get('submit_phoneDisplay', 1) == 0)
		{
			$form->removeField('phone');
			$form->removeField('note_phone');
		}

		if ($params->get('submit_emailDisplay', 1) == 0
			&& $params->get('submit_phoneDisplay', 1) == 0)
		{
			$form->removeField('contact_title');
		}

		// Remove File (attachment) field if hidden
		if ($params->get('submit_fileDisplay', 1) == 0)
		{
			$form->removeField('attachments_title');
			$form->removeField('file');
			$form->removeField('note_file');
		}

		// Set default setting for Registration Status
		if ($params->get('statutReg', 0))
		{
			$form->setFieldAttribute('statutReg', 'default', '1', 'params');
		}

		// If Terms of Service is not enabled, we remove validation for checkbox
		if ($params->get('tos', 1) == 0)
		{
			$form->removeField('consent_tos', 'consent');
		}

		// If Captcha not displayed, we remove validation for captcha form field
		if ($params->get('submit_captcha', 0) == 0)
		{
			$form->removeField('captcha');
		}

		return $form;
	}

	/**
	 * Method to get the data that should be injected in the form.
	 *
	 * @return  mixed  The data for the form.
	 *
	 * @since   3.8
	 */
	protected function loadFormData()
	{
		$data = $this->getData();

		// Fix hidden field custom_field (Joomla change for hidden field returning error if array)
		if (isset($data->custom_fields) && \is_array($data->custom_fields))
		{
			$data->custom_fields = implode(',', $data->custom_fields);
		}

		$this->preprocessData('com_icagenda.submit', $data);

		return $data;
	}

	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @return  void
	 */
	protected function populateState()
	{
		$app = Factory::getApplication();

		// Load the parameters.
		$params = $app->getParams();

		$this->setState('params', $params);
	}

	/**
	 * Method to validate the form data.
	 *
	 * @param   JForm   $form   The form to validate against.
	 * @param   array   $data   The data to validate.
	 * @param   string  $group  The name of the field group to validate.
	 *
	 * @return  array|boolean  Array of filtered data if valid, false otherwise.
	 *
	 * @see     JFormRule
	 * @see     JFilterInput
	 *
	 * @since   3.8
	 */
	public function validate($form, $data, $group = null)
	{
		$app = Factory::getApplication();

		// Makes sure if the weekdays option is emptied by user after a validation error,
		// new empty data is not override by previous value from user session. (multiple select)
		if ( ! isset($data['weekdays']))
		{
			$data['weekdays'] = '';
		}

		// Filter and validate the form data.
		$data   = $form->filter($data);
		$return = $form->validate($data, $group);

		// Check for an error.
		if ($return instanceof \Exception)
		{
			$app->enqueueMessage($return->getMessage(), 'error');

			return false;
		}

		// Check other form errors
		$errors = array();

		// Get Period Dates
		$startControl = iCDate::isDate($data['startdate']);
		$endControl   = iCDate::isDate($data['enddate']);

		// Check dates from the period
		if ($startControl && $endControl
			&& ($data['startdate'] > $data['enddate']))
		{
			$errors[] = Text::sprintf('COM_ICAGENDA_SUBMIT_ERROR_PERIOD', Text::_('COM_ICAGENDA_SUBMIT_ERROR_PERIOD_END_BEFORE_START'));
		}
		elseif ($startControl && ! $endControl)
		{
			$errors[] = Text::sprintf('COM_ICAGENDA_SUBMIT_ERROR_PERIOD', Text::_('COM_ICAGENDA_SUBMIT_ERROR_PERIOD_NO_ENDDATE'));
		}
		elseif ( ! $startControl && $endControl)
		{
			$errors[] = Text::sprintf('COM_ICAGENDA_SUBMIT_ERROR_PERIOD', Text::_('COM_ICAGENDA_SUBMIT_ERROR_PERIOD_NO_STARTDATE'));
		}
		elseif ( ! $startControl && ! $endControl && ! $data['dates'])
		{
			$errors[] = Text::sprintf('JLIB_FORM_VALIDATE_FIELD_REQUIRED', Text::_('COM_ICAGENDA_LEGEND_DATES'));
		}

		// Get Custom Fields
		$custom_fields = isset($data['custom_fields']) ? $data['custom_fields'] : '';

		// Control Custom Fields required if not empty
		if ($custom_fields && is_array($custom_fields))
		{
			$requiredEmptyFields = icagendaCustomfields::requiredIsEmpty($custom_fields, 2);

			if ( ! empty($requiredEmptyFields))
			{
				foreach ($requiredEmptyFields as $fieldEmpty)
				{
					$errors[] = Text::sprintf('JLIB_FORM_VALIDATE_FIELD_REQUIRED', $fieldEmpty);
				}
			}
		}

		// Check the validation results.
		if ($return === false || count($errors) > 0)
		{
			$app->enqueueMessage('<strong>' . Text::_('COM_ICAGENDA_FORM_NC') . '</strong>', 'warning');

			// Get the validation messages from the form.
			foreach ($form->getErrors() as $message)
			{
				if ($message instanceof \Exception)
				{
					$message = $message->getMessage();
				}

				$app->enqueueMessage($message, 'warning');
			}

			// Get the validation messages from the other form errors.
			foreach ($errors as $message)
			{
				$app->enqueueMessage($message, 'warning');
			}

			return false;
		}

		return $data;
	}

	/**
	 * Method to change the alias.
	 *
	 * @param   JTable  $table  A JTable object.
	 * @param   string  $alias  The alias.
	 *
	 * @return  string  Contains the modified alias.
	 *
	 * @since   3.8
	 */
	protected function generateNewAlias($alias)
	{
		$table = Table::getInstance('EventTable', 'WebiC\\Component\\iCagenda\\Administrator\\Table\\');

		while ($table->load(array('alias' => $alias)))
		{
			$alias = StringHelper::increment($alias, 'dash');
		}

		return $alias;
	}

	/**
	 * Method to save the form data.
	 *
	 * @param   array  $temp  The form data.
	 */
	public function submit($temp)
	{
		$data = (array) $this->getData();

		foreach ($temp as $k => $v)
		{
			$data[$k] = $v;
		}

		$app     = Factory::getApplication();
		$user    = Factory::getUser();
		$lang    = Factory::getLanguage();

		$jinput = $app->input;

		jimport('joomla.filter.output');

		$eventTimeZone  = null;
		$error_messages = array();

		// Get Params
		$params = $app->getParams();

		$submitAccess   = $params->get('submitAccess', '');
		$approvalGroups = $params->get('approvalGroups', array("8"));

		$user_id = $user->get('id');

		// logged-in Users: Name/User Name Option
		$nameJoomlaUser = $params->get('nameJoomlaUser', 1);
		$u_name         = ($nameJoomlaUser == 1) ? $user->get('name') : $user->get('username');

		// Redirection settings
		$baseURL    = Uri::base();
		$subpathURL = Uri::base(true);

		$baseURL    = str_replace('/administrator', '', $baseURL);
		$subpathURL = str_replace('/administrator', '', $subpathURL);

		$urlsend = str_replace('&amp;','&', Route::_('index.php?option=com_icagenda&view=submit&layout=send&Itemid=' . $data['itemid']));

		// Sub Path filtering
		$subpathURL = ltrim($subpathURL, '/');

		// URL List filtering
		$urlsend = ltrim($urlsend, '/');

		if (substr($urlsend, 0, strlen($subpathURL)+1) == "$subpathURL/")
		{
			$urlsend = substr($urlsend, strlen($subpathURL)+1);
		}

		$urlsend = rtrim($baseURL, '/') . '/' . ltrim($urlsend, '/');

		// Get return params
		$submit_return         = $params->get('submitReturn', '');
		$submit_return_article = $params->get('submitReturn_Article', $urlsend);
		$submit_return_url     = $params->get('submitReturn_Url', $urlsend);

		if (($submit_return == 1) && is_numeric($submit_return_article))
		{
			$url_return = Uri::root().'index.php?option=com_content&view=article&id=' . $submit_return_article;
		}
		elseif ($submit_return == 2)
		{
			$url_return = $submit_return_url;
		}
		else
		{
			$url_return = $urlsend;
		}

		// Set alert messages
		$alert_title          = $params->get('alert_title', '');
		$alert_body           = $params->get('alert_body', '');
		$url_redirect         = isset($urlsend_custom) ? $urlsend_custom : $urlsend; // Url custom not yet available.
		$alert_title_redirect = $alert_title ? $alert_title : 'message';
		$alert_body_redirect  = $alert_body ? $alert_body : Text::_( 'COM_ICAGENDA_EVENT_SUBMISSION_CONFIRMATION' );


		// Control: if Manager
		$adminUsersArray = array();

		foreach ($approvalGroups AS $ag)
		{
			$adminUsers      = Access::getUsersByGroup($ag, false);
			$adminUsersArray = array_merge($adminUsersArray, $adminUsers);
		}

		$noDateTime      = '0000-00-00 00:00:00';
		$noDateTimeShort = '0000-00-00 00:00';


		// Attachments
		$files = $jinput->files->get('jform');
		$image = $files['image'];
		$file  = $files['file'];

		// Set Event Data
		$eventData = new \stdClass();

		$eventData->state            = 1;
		$eventData->approval         = (in_array($user_id, $adminUsersArray)) ? '0' : '1';
		$eventData->access           = 1 ;
		$eventData->language         = isset($data['language_event']) ? $data['language_event'] : '*';
		$eventData->username         = $data['username'];
		$eventData->created_by_email = isset($data['created_by_email']) ? $data['created_by_email'] : '';
		$eventData->title            = $data['title'];
		$eventData->catid            = $data['catid'];
		$eventData->image            = ! empty($image['name']) ? $this->frontendImageUpload($image) : '';
		$eventData->displaytime      = $params->get('submit_displaytimeDisplay', 0) ? $data['displaytime'] : $params->get('displaytime', 1);
		$eventData->desc             = isset($data['desc']) ? $data['desc'] : '';
		$eventData->shortdesc        = isset($data['shortdesc']) ? $data['shortdesc'] : '';
		$eventData->metadesc         = isset($data['metadesc']) ? $data['metadesc'] : '';
		$eventData->place            = isset($data['place']) ? $data['place'] : '';
		$eventData->website          = isset($data['website']) ? $data['website'] : '';
		$eventData->email            = isset($data['email']) ? $data['email'] : '';
		$eventData->phone            = isset($data['phone']) ? $data['phone'] : '';
		$eventData->file             = ! empty($file['name']) ? $this->frontendFileUpload($file) : '';
		$eventData->address          = isset($data['address']) ? $data['address'] : '';
		$eventData->city             = isset($data['city']) ? $data['city'] : '';
		$eventData->country          = isset($data['country']) ? $data['country'] : '';
		$eventData->lat              = isset($data['lat']) ? $data['lat'] : '0';
		$eventData->lng              = isset($data['lng']) ? $data['lng'] : '0';
		$eventData->created_by       = $user_id;
		$eventData->created_by_alias = isset($data['created_by_alias']) ? $data['created_by_alias'] : '';
		$eventData->created          = HTMLHelper::Date('now', 'Y-m-d H:i:s');
		$eventData->modified         = '0000-00-00 00:00:00';
		$eventData->params           = isset($data['params']) ? $data['params'] : '';

		// Site Submit an Event page info
		$eventData->site_itemid      = $data['itemid'];

		$site_menu_title = $data['itemid_title'];

		// Set default value for extra version data
		$eventData->version_customfields = json_encode($data['custom_fields']);

		if (isset($data['features']) && is_array($data['features']))
		{
			$eventData->version_features = implode(',', $data['features']);
		}
		else
		{
			$eventData->version_features = '';
		}


		// Get Single Dates
		$single_dates = $data['dates'];

		$dates = array();

		foreach ($single_dates as $key => $date)
		{
			if (iCDate::isDate($date['date']))
			{
				$dates[] = date('Y-m-d H:i', strtotime($date['date']));
			}
		}

		rsort($dates);

		if (count($dates) > 0)
		{
			$eventData->dates = serialize($dates);
		}
		else
		{
			$eventData->dates = '';
		}

		// Check Period Dates
		$isDate_startdate = iCDate::isDate($data['startdate']);
		$isDate_enddate   = iCDate::isDate($data['enddate']);

		// Set Period Dates
		if ($isDate_startdate && $isDate_enddate)
		{
			$eventData->startdate = date('Y-m-d H:i:s', strtotime($data['startdate']));
			$eventData->enddate   = date('Y-m-d H:i:s', strtotime($data['enddate']));

			$period_all_dates_array = iCDatePeriod::listDates($eventData->startdate, $eventData->enddate);

			if (is_array($data['weekdays']) && count($data['weekdays']) > 0)
			{
				$period_array = array();

				foreach ($period_all_dates_array AS $date_in_weekdays)
				{
					$datetime_period_date = HTMLHelper::date($date_in_weekdays, 'Y-m-d H:i', $eventTimeZone);

					if (in_array(date('w', strtotime($datetime_period_date)), $data['weekdays']))
					{
						$period_array[] = $datetime_period_date;
					}
				}

				if (count($period_array) > 0)
				{
					$eventData->period   = serialize($period_array);
					$eventData->weekdays = is_array($data['weekdays']) ? implode(",", $data['weekdays']) : '';
				}
				else
				{
					$eventData->period   = '';
					$eventData->weekdays = '';
				}
			}
			else
			{
				$eventData->period   = '';
				$eventData->weekdays = '';
			}
		}
		else
		{
			$eventData->startdate = '0000-00-00 00:00:00';
			$eventData->enddate   = '0000-00-00 00:00:00';
			$eventData->period    = '';
		}

		// Set Next Date from event dates
		$eventData->next = icagendaEvent::getNextDate($eventData);

		// Period and Single Dates not displayed
		if ( (in_array($noDateTime, $dates) || in_array($noDateTimeShort, $dates))
			&& ( ! $isDate_startdate || ! $isDate_enddate) )
		{
			$eventData->state = '0';
		}

		if ( ! iCDate::isDate($eventData->next))
		{
			// Message if no date set
			$error_messages[] = Text::_('COM_ICAGENDA_FORM_ERROR_NO_DATES');
		}

		// Generate Alias
		if (Factory::getApplication()->get('unicodeslugs') == 1)
		{
			$eventData->alias = OutputFilter::stringUrlUnicodeSlug($eventData->title);
		}
		else
		{
			$eventData->alias = OutputFilter::stringURLSafe($eventData->title);
		}

		if (empty($eventData->alias))
		{
			// Use created date in case alias is still empty
			$eventData->alias = OutputFilter::stringURLSafe($eventData->created);
		}

		$table = Table::getInstance('EventTable', 'WebiC\\Component\\iCagenda\\Administrator\\Table\\');

		if ($table->load(array('alias' => $eventData->alias)))
		{
			$alias = $this->generateNewAlias($eventData->alias);
			$eventData->alias = $alias;
		}

		// Force to not add unicode characters if unicodeslugs is not enabled.
		if (Factory::getConfig()->get('unicodeslugs') != 1)
		{
			$eventData->alias = OutputFilter::stringURLSafe($eventData->alias);
		}

		if ( ! isset($eventData->params) || ! is_array($eventData->params))
		{
			$eventData->params = [];
		}

		// Convert the params field to a string.
		if (isset($eventData->params)
			&& is_array($eventData->params))
		{
			// Update param 'first_published_and_approved'
			if ($eventData->state == 1
				&& $eventData->approval == 0)
			{
				$eventData->params['first_published_and_approved'] = '1';

				$first_published_and_approved = true;
			}
			else
			{
				$first_published_and_approved = false;
			}

			$parameter = new Registry;
			$parameter->loadArray($eventData->params);

			$eventData->params = (string) $parameter;
		}

		// Captcha Control
//		$captcha = $data['recaptcha_response_field'];

//		$captcha_plugin = $params->get('captcha') ? $params->get('captcha') : $app->getCfg('captcha');
//		$submit_captcha = $params->get('submit_captcha', 1);

//		if ($captcha_plugin && $submit_captcha != '0')
//		{
//			PluginHelper::importPlugin('captcha');

//			$res = $app->triggerEvent('onCheckAnswer', $captcha);

//			if ( ! $res[0])
//			{
//				// Message if captcha is invalid
//				$error_messages[] = Text::sprintf('COM_ICAGENDA_FORM_ERROR', Text::_('COM_ICAGENDA_FORM_ERROR_INCORRECT_CAPTCHA_SOL'));
//			}
//		}


		// Get the message queue
		if (\count($error_messages) > 0)
		{
			$app->enqueueMessage('<strong>' . Text::_( 'COM_ICAGENDA_FORM_NC' ) . '</strong>', 'error');

			foreach ($error_messages AS $msg)
			{
				$app->enqueueMessage($msg, 'error');
			}

			return false;
		}


		// Insert Event in Database
		$db = Factory::getDbo();

		if ($eventData->username
			&& $eventData->title
			&& $eventData->created_by_email)
		{
			$db->insertObject('#__icagenda_events', $eventData, 'id');
		}
		elseif (count($errors = $this->get('Errors')))
		{
			throw new \Exception(implode("\n", $errors), 500);

			return false;
		}


		// Save Custom Fields to database
		$custom_fields = $data['custom_fields'];

		if (isset($custom_fields) && is_array($custom_fields))
		{
			icagendaCustomfields::saveToData($custom_fields, $eventData->id, 2);
		}


		// Save Consents to database.
		if ($params->get('tos', 1) && $data['consent']['consent_tos'])
		{
			// Create and populate an object.
			$action = new \stdClass();
			$action->user_id        = $user_id;
			$action->user_action    = 'consent';
			$action->parent_form    = 2; // Submit an Event
			$action->parent_id      = $eventData->id; // Event ID
			$action->action_subject = Text::_('COM_ICAGENDA_TERMS_OF_SERVICE');
			$action->action_body    = strip_tags(Text::_('COM_ICAGENDA_TERMS_OF_SERVICE_AGREE'));
			$action->user_ip        = $app->input->server->get('REMOTE_ADDR', '', 'string');
			$action->user_agent     = $app->input->server->get('HTTP_USER_AGENT', '', 'string');;
			$action->state          = 1;
			$action->created_time   = Factory::getDate()->toSql();

			// Insert the object into the user profile table.
			$result = $db->insertObject('#__icagenda_user_actions', $action);
		}


		// Send Notification Emails
		if (isset($eventData->id) && $eventData->id != '0')
		{
			// Manager Notification Email
			self::notificationManagerEmail($eventData, $site_menu_title, $user_id);

			// User Notification Email
			if ( ! in_array($user_id, $adminUsersArray ))
			{
				self::notificationUserEmail($eventData, $urlsend);
			}

			// Plugin Event handler 'iCagendaOnNewEvent'
			PluginHelper::importPlugin('icagenda');

			if ($first_published_and_approved)
			{
				$app->triggerEvent('iCagendaOnNewEvent', array(&$eventData));
			}
		}
		elseif (\count($errors = $this->get('Errors')))
		{
			throw new \Exception(implode("\n", $errors), 500);

			return false;
		}

		// Redirect after successful submission
		if ($submit_return != 2)
		{
			$app->enqueueMessage($alert_body_redirect, $alert_title_redirect);

//			$app->redirect(htmlspecialchars_decode($url_return));
			return htmlspecialchars_decode($url_return);
		}
		else
		{
			$url_return = iCUrl::urlParsed($url_return, 'scheme');

//			$app->redirect($url_return);
			return $url_return;
		}
	}

	/**
	 * Method to send notification email to Manager.
	 */
	protected function notificationManagerEmail($data, $site_menu_title, $user_id)
	{
		$event_id          = $data->id;
		$event_title       = $data->title;
		$event_site_itemid = $data->site_itemid;
		$event_username    = $data->username;
		$event_user_email  = $data->created_by_email;
		$event_ref         = HTMLHelper::date('now', 'Ymd') . $data->id;

		// Load iCagenda Global Options
		$iCparams = ComponentHelper::getParams('com_icagenda');

		// Load Joomla Application
		$app = Factory::getApplication();

		// Load Joomla Config Mail Options
		$sitename = $app->get('sitename');
		$mailfrom = $app->get('mailfrom');
		$fromname = $app->get('fromname');

		$siteURL = Uri::base();
		$siteURL = rtrim($siteURL,'/');

		// Itemid Request (automatic detection of the first iCagenda menu-link, by menuID, and depending of current language)
		$menu_items   = icagendaMenus::iClistMenuItems();
		$itemid_array = array();

		foreach ($menu_items as $l) {
			array_push($itemid_array, $l->id);
		}

		sort($itemid_array);

		$itemID = $itemid_array[0];

		// Set Notification Email to each User groups allowed to approve event submitted
		$groupid = $iCparams->get('approvalGroups', array("8"));
		$groupid = \is_array($groupid) ? $groupid : explode(',', $groupid);

		// Load Global Option for Autologin
		$autologin = $iCparams->get('auto_login', 1);

		$adminUsersArray = array();

		foreach ($groupid as $gp) {
			$adminUsers      = Access::getUsersByGroup($gp, False);
			$adminUsersArray = array_unique(array_merge($adminUsersArray, $adminUsers));
		}

		if ($user_id == NULL) {
			$user_id = 0;
		}

		$db    = Factory::getDbo();
		$query = $db->getQuery(true);

		$query->select(
			[
				$db->qn('username'),
				$db->qn('email'),
				$db->qn('password'),
				$db->qn('block'),
				$db->qn('activation'),
			]
		)
			->from($db->qn('#__users'));

		if (!in_array($user_id, $adminUsersArray)) {
			$query->where($db->qn('id') . ' IN (' . implode(',', $adminUsersArray) . ')');
		} else {
			$query->where($db->qn('id') . ' = ' . (int) $user_id);
		}

		$db->setQuery($query);
		$managers = $db->loadObjectList();

		foreach ($managers as $manager) {
			// Mail Replacements
			$replacements = [
				"\\n"               => "\n",
				'[SITENAME]'        => $sitename,
				'[USERNAME]'        => $event_username,
				'[EMAIL]'           => $event_user_email,
				'[EVENT_TITLE]'     => $event_title,
				'[EVENT_REF]'       => $event_ref,
				'[SHORTDESC]'       => isset($data->shortdesc) ? $data->shortdesc : '',
				'[DESC]'            => isset($data->desc)      ? $data->desc      : '',
				'[METADESC]'        => isset($data->metadesc)  ? $data->metadesc  : '',
				'&nbsp;'            => ' ',
			];

			if (!in_array($user_id, $adminUsersArray)) {
				$type = 'approval';
			} else {
				$type = 'confirmation';
			}

			// Create Admin Mailer
			$adminmailer = Factory::getMailer();

			// Set Sender of Notification Email
			$adminmailer->setSender(array( $mailfrom, $fromname ));

			// Set ReplyTo.
			$adminmailer->addReplyTo($mailfrom, $fromname);

			$username = $manager->username;
			$passw    = $manager->password;
			$email    = $manager->email;

			// Set Recipient of Notification Email
			$adminrecipient = $email;
			$adminmailer->addRecipient($adminrecipient);

			// Set Subject of Admin Notification Email
			if ( ! in_array($user_id, $adminUsersArray)) {
				$adminsubject = Text::sprintf('COM_ICAGENDA_SUBMISSION_ADMIN_EMAIL_SUBJECT', $event_username, $sitename);
			} else {
				$adminsubject = Text::sprintf('COM_ICAGENDA_SUBMIT_MANAGER_APPROVED_EMAIL_SUBJECT', $event_title);
			}

			// Set Url to preview and checking of event submitted
			$baseURL    = Uri::base();
			$subpathURL = Uri::base(true);

			$baseURL    = str_replace('/administrator', '', $baseURL);
			$subpathURL = str_replace('/administrator', '', $subpathURL);

			if ($autologin == 1) {
				$urlpreview = str_replace('&amp;', '&', Route::_('index.php?option=com_icagenda&view=event&id='.(int)$event_id.'&Itemid='.(int)$itemID.'&icu='.$username.'&icp='.$passw));
			} else {
				$urlpreview = str_replace('&amp;', '&', Route::_('index.php?option=com_icagenda&view=event&id='.(int)$event_id.'&Itemid='.(int)$itemID));
			}

			$urlpreviewshort = str_replace('&amp;', '&', Route::_('index.php?option=com_icagenda&view=event&id='.(int)$event_id.'&Itemid='.(int)$itemID));

			// Sub Path filtering
			$subpathURL = ltrim($subpathURL, '/');

			// URL Event Preview filtering
			$urlpreview      = ltrim($urlpreview, '/');
			$urlpreviewshort = ltrim($urlpreviewshort, '/');

			if (substr($urlpreview, 0, strlen($subpathURL)+1) == "$subpathURL/") {
				$urlpreview = substr($urlpreview, strlen($subpathURL)+1);
			}

			if (substr($urlpreviewshort, 0, strlen($subpathURL)+1) == "$subpathURL/") {
				$urlpreviewshort = substr($urlpreviewshort, strlen($subpathURL)+1);
			}

			$urlpreview      = rtrim($baseURL, '/') . '/' . ltrim($urlpreview, '/');
			$urlpreviewshort = rtrim($baseURL, '/') . '/' . ltrim($urlpreviewshort, '/');

			// Set Body of User Notification Email
			$adminbodycontent = Text::sprintf('COM_ICAGENDA_SUBMISSION_ADMIN_EMAIL_HELLO', $username) . ',<br /><br />';

			if ($type == 'approval') {
				$adminbodycontent.= Text::_('COM_ICAGENDA_SUBMISSION_ADMIN_EMAIL_NEW_EVENT') . '<br /><br />';
				$adminbodycontent.= Text::sprintf('COM_ICAGENDA_SUBMISSION_ADMIN_EMAIL_APPROVE_INFO', $sitename) . '<br /><br />';
				$adminbodycontent.= Text::_('COM_ICAGENDA_SUBMISSION_ADMIN_EMAIL_APPROVE_LINK') . ': <a href="' . $urlpreview . '">' . $urlpreviewshort . '</a><br /><br />';
			}

			if ($type == 'confirmation') {
				$adminbodycontent.= Text::_('COM_ICAGENDA_SUBMISSION_ADMIN_EMAIL_APPROVED_REVIEW') . '<br /><br />';
				$adminbodycontent.= '<a href="' . $urlpreview . '">' . $urlpreviewshort . '</a><br /><br />';
			}

			$user_email_mailto = '<a href="mailto:' . $event_user_email . '">' . $event_user_email . '</a>';

			$adminbodycontent.= Text::sprintf('COM_ICAGENDA_SUBMISSION_ADMIN_EMAIL_SITE_MENUID', $event_site_itemid, $site_menu_title) . '<br />';
			$adminbodycontent.= Text::sprintf('COM_ICAGENDA_SUBMISSION_ADMIN_EMAIL_USER_INFO', $event_username, $user_email_mailto) . '<br /><br />';

			if ($autologin == 1) {
				$adminbodycontent.= '<hr><small>' . Text::sprintf('COM_ICAGENDA_SUBMISSION_ADMIN_EMAIL_FOOTER', $sitename) . '<small>';
			} else {
				$adminbodycontent.= '<hr><small>' . Text::sprintf('COM_ICAGENDA_SUBMISSION_ADMIN_EMAIL_FOOTER_NO_AUTOLOGIN', $sitename) . '<small>';
			}

			$adminbody = rtrim($adminbodycontent);

			// Apply Replacements
			foreach ($replacements as $key => $value) {
				$adminsubject = str_replace($key, $value, $adminsubject);
				$adminbody    = str_replace($key, $value, $adminbody);
			}

			$adminmailer->isHTML(true);

			// JDocs: When sending HTML emails you should normally set the Encoding to base64
			//        in order to avoid unwanted characters in the output.
			//        See https://docs.joomla.org/Sending_email_from_extensions
			$adminmailer->Encoding = 'base64'; // JDocs Sending HTML Email

			// Set Subject
			$adminmailer->setSubject($adminsubject);

			// Set Body
			$adminmailer->setBody($adminbody);

			// Send User Notification Email
			if (isset($email)
				&& $manager->block == '0'
				&& empty($manager->activation)
			) {
				// Try to send Admin Notification Email
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

		return true;
	}

	/**
	 * Method to send notification email to User.
	 */
	protected function notificationUserEmail ($data, $url)
	{
		$email       = $data->created_by_email;
		$username    = $data->username;
		$event_title = $data->title;
		$event_ref   = HTMLHelper::date( 'now', 'Ymd' ) . $data->id;

		// Load Joomla Application
		$app = Factory::getApplication();

		// Create User Mailer
		$mailer = Factory::getMailer();

		// Load Joomla Config Mail Options
		$sitename = $app->get('sitename');
		$mailfrom = $app->get('mailfrom');
		$fromname = $app->get('fromname');

		// Set Sender of Notification Email
		$mailer->setSender(array($mailfrom, $fromname));

		// Set ReplyTo.
		$mailer->addReplyTo($mailfrom, $fromname);

		// Set Recipient of User Notification Email
		$userrecipient = $data->created_by_email;
		$mailer->addRecipient($userrecipient);

		// MAIL
		$replacements = array(
			"\\n"               => "\n",
			'[SITENAME]'        => $sitename,
			'[EMAIL]'           => $email,
			'[EVENT_TITLE]'     => $event_title,
			'[EVENT_REF]'       => $event_ref,
			'&nbsp;'            => ' ',
		);

		// Set Body of Notification Email
		$user_submit_body = Text::sprintf('COM_ICAGENDA_USER_EMAIL_HELLO', $username) . ',<br /><br />';
		$user_submit_body.= Text::sprintf('COM_ICAGENDA_EVENT_SUBMISSION_THANK_YOU', $sitename) . '<br />';
		$user_submit_body.= Text::_('COM_ICAGENDA_EVENT_SUBMISSION_EDITOR_REVIEW') . '<br />';
		$user_submit_body.= Text::_('COM_ICAGENDA_EVENT_SUBMISSION_CONFIRMATION_EMAIL') . '<br /><br />';
		$user_submit_body.= Text::sprintf('COM_ICAGENDA_USER_EMAIL_EVENT_TITLE_AND_REF_NO', $event_title, $event_ref) . '<br /><br />';
		$user_submit_body.= Text::_('COM_ICAGENDA_USER_EMAIL_BEST_REGARDS') . '<br />';

		$user_submit_body = rtrim($user_submit_body);

		foreach ($replacements as $key => $value)
		{
			$subject          = str_replace($key, $value, $subject);
			$user_submit_body = str_replace($key, $value, $user_submit_body);
		}

		$mailer->isHTML(true);

		// JDocs: When sending HTML emails you should normally set the Encoding to base64
		//        in order to avoid unwanted characters in the output.
		//        See https://docs.joomla.org/Sending_email_from_extensions
		$mailer->Encoding = 'base64'; // JDocs Sending HTML Email

		// Set Subject of User Notification Email
		$subject = Text::sprintf('COM_ICAGENDA_EVENT_SUBMISSION_THANK_YOU', $sitename);
		$mailer->setSubject($subject);

		// Set Body of User Notification Email
		$mailer->setBody($user_submit_body);

		// Send User Notification Email
		if (isset($email)) {
			// Try to send Admin Notification Email
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
				$this->setError(Text::_('COM_ICAGENDA_MAIL_FAILED'));

				return false;
			}
		}
	}

	/**
	 * Get Dates
	 *
	 * @todo: check if still used.
	 */
	protected function getDates($dates)
	{
		$dates    = str_replace('d=', '', $dates);
		$dates    = str_replace('+', ' ', $dates);
		$dates    = str_replace('%3A', ':', $dates);
		$ex_dates = explode('&', $dates);

		$setDates = array();

		foreach ($ex_dates as $date)
		{
			$setDates[] = iCDate::isDate($date)
						? date('Y-m-d H:i', strtotime($date))
						: '0000-00-00 00:00';
		}

		return $setDates;
	}

	/**
	 * Get Period
	 *
	 * @todo: check if still used.
	 */
	protected function getPeriod($period)
	{
		$period    = str_replace('d=', '', $period);
		$period    = str_replace('+', ' ', $period);
		$period    = str_replace('%3A', ':', $period);
		$ex_period = explode('&', $period);

		return $ex_period;
	}

	/**
	 * Image Upload
	 */
	protected function frontendImageUpload($image)
	{
		// Get Joomla Images PATH setting
		$image_path = ComponentHelper::getParams('com_media')->get('image_path', 'images');

		// Get imagename (name + ext)
		$imagename = $image['name'];

		// Get image extension
		$imageExtension = File::getExt($imagename);

		// Clean up image name to url safe string
		$imageTitle = iCFilterOutput::stringToSlug(File::stripExt($imagename), '-');

		// If slug generated is empty, new slug based on current date/time
		if ( ! $imageTitle)
		{
			$imageTitle = Factory::getDate()->format("YmdHis");
		}

		// Return new filename
		$imagename = $imageTitle . '.' . $imageExtension;

		$src = $image['tmp_name'];

		// Controls image mimetype, and fixes file extension if missing in filename
		$allowed_mimetypes = array('jpg', 'jpeg', 'png', 'gif');

		if ( ! in_array($imageExtension, $allowed_mimetypes))
		{
			$fileinfos      = getimagesize($src);
			$mimeType       = $fileinfos['mime'];
			$ex_mimeType    = explode('/', $mimeType);
			$file_extension = $ex_mimeType[1];

			$imagename = $imageTitle . '.' . $file_extension;
		}

		// Process filename
		while (File::exists(JPATH_SITE . '/' . $image_path . '/icagenda/frontend/images/' . $imagename))
		{
			// Get image extension
			$imageExtension = File::getExt($imagename);

			// Get image title
			$imageTitle = File::stripExt($imagename);

			// Increment image title if already exists (eg. filename-3.jpg)
			$imageTitle = iCString::increment($imageTitle, 'dash');

			$imagename = $imageTitle . '.' . $imageExtension;
		}

		if ($imagename != '')
		{
			// Set up the source and destination of the file
			$src  = $image['tmp_name'];
			$dest = JPATH_SITE . '/' . $image_path . '/icagenda/frontend/images/' . $imagename;

			// Create Folder iCagenda in ROOT/IMAGES_PATH/icagenda and sub-folders if do not exist
			$folder[0][0] = 'icagenda/frontend/' ;
			$folder[0][1] = JPATH_ROOT . '/' . $image_path . '/' . $folder[0][0];
			$folder[1][0] = 'icagenda/frontend/images/';
			$folder[1][1] = JPATH_ROOT . '/' . $image_path . '/' . $folder[1][0];

			$error = array();

			foreach ($folder as $key => $value)
			{
				if ( ! Folder::exists( $value[1]))
				{
					if (Folder::create( $value[1], 0755 ))
					{
						$this->data = "<html>\n<body bgcolor=\"#FFFFFF\">\n</body>\n</html>";
						File::write($value[1] . "/index.html", $this->data);
						$error[] = 0;
					}
					else
					{
						$error[] = 1;
					}
				}
				else //Folder exist
				{
					$error[] = 0;
				}
			}

			if (File::upload($src, $dest, false))
			{
				return $image_path . '/icagenda/frontend/images/' . $imagename;
			}
		}
	}

	/**
	 * File Upload
	 */
	protected function frontendFileUpload($file)
	{
		// Get Joomla Images PATH setting
		$image_path = ComponentHelper::getParams('com_media')->get('image_path', 'images');

		// Get filename (name + ext)
		$filename = $file['name'];

		// Get file extension
		$fileExtension = File::getExt($filename);

		// Clean up file name to url safe string
		$fileTitle = iCFilterOutput::stringToSlug(File::stripExt($filename), '-');

		// If slug generated is empty, new slug based on current date/time
		if ( ! $fileTitle)
		{
			$fileTitle = Factory::getDate()->format("YmdHis");
		}

		// Return new filename
		$filename = $fileTitle . '.' . $fileExtension;

		// Increment file name if filename already exists
		while (File::exists(JPATH_SITE . '/' . $image_path . '/icagenda/frontend/attachments/' . $filename))
		{
			// Get file extension
			$fileExtension = File::getExt($filename);

			// Get file title
			$fileTitle = File::stripExt($filename);

			// Increment file title (eg. filename-3.jpg)
			$fileTitle = iCString::increment($fileTitle, 'dash');

			$filename = $fileTitle . '.' . $fileExtension;
		}

		// Save file
		if ($filename != '')
		{
			// Set up the temporary source and destination of the file
			$src = $file['tmp_name'];
			$dest =  JPATH_SITE . '/' . $image_path . '/icagenda/frontend/attachments/' . $filename;

			// Create Folder iCagenda in ROOT/IMAGES_PATH/icagenda and sub-folders if do not exist
			$folder[0][0] = 'icagenda/frontend/' ;
			$folder[0][1] = JPATH_ROOT . '/' . $image_path . '/' . $folder[0][0];
			$folder[1][0] = 'icagenda/frontend/attachments/';
			$folder[1][1] = JPATH_ROOT . '/' . $image_path . '/' . $folder[1][0];

			$error = array();

			foreach ($folder as $key => $value)
			{
				if ( ! Folder::exists( $value[1]))
				{
					if (Folder::create( $value[1], 0755 ))
					{
						$this->data = "<html>\n<body bgcolor=\"#FFFFFF\">\n</body>\n</html>";
						File::write($value[1] . "/index.html", $this->data);
						$error[] = 0;
					}
					else
					{
						$error[] = 1;
					}
				}
				else //Folder exist
				{
					$error[] = 0;
				}
			}

			if (File::upload($src, $dest, false))
			{
				return $image_path . '/icagenda/frontend/attachments/' . $filename;
			}
		}
	}
}
