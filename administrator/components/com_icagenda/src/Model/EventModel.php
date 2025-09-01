<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.8.22 2023-11-24
 *
 * @package     iCagenda.Admin
 * @subpackage  src.Model
 * @link        https://www.icagenda.com
 *
 * @author      Cyril Rezé
 * @copyright   (c) 2012-2024 Cyril Rezé / iCagenda. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 *
 * @since       1.0
 *----------------------------------------------------------------------------
*/

namespace WebiC\Component\iCagenda\Administrator\Model;

\defined('_JEXEC') or die;

use iClib\Date\Date as iCDate;
use iClib\Date\Period as iCDatePeriod;
use iClib\Filter\Output as iCOutputFilter;
use iClib\String\StringHelper as iCString;
use iCutilities\Customfields\Customfields as icagendaCustomfields;
use iCutilities\Event\Event as icagendaEvent;
use iCutilities\Manager\Manager as icagendaManager;
use iCutilities\Menus\Menus as icagendaMenus;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Filesystem\File;
use Joomla\CMS\Filesystem\Folder;
use Joomla\CMS\Filter\InputFilter;
use Joomla\CMS\Filter\OutputFilter;
use Joomla\CMS\Form\Form;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Log\Log;
use Joomla\CMS\MVC\Model\AdminModel;
use Joomla\CMS\Object\CMSObject;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Table\Table;
use Joomla\CMS\Versioning\VersionableModelTrait;
use Joomla\Registry\Registry;
use Joomla\String\StringHelper;
use Joomla\Utilities\ArrayHelper;
use WebiC\Component\iCagenda\Administrator\Helper\iCagendaHelper;

/**
 * iCagenda Component Event Model.
 */
class EventModel extends AdminModel
{
	use VersionableModelTrait;

	/**
	 * The prefix to use with controller messages.
	 *
	 * @var    string
	 */
	protected $text_prefix = 'COM_ICAGENDA';

	/**
	 * The type alias for this content type (for example, 'com_content.article').
	 *
	 * @var    string
	 *
	 * @since  3.8
	 */
	public $typeAlias = 'com_icagenda.event';

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
	public function getTable($name = 'Event', $prefix = 'Table', $config = array())
	{
		if ($table = $this->_createTable($name, $prefix, $config))
		{
			return $table;
		}

		throw new \Exception(Text::sprintf('JLIB_APPLICATION_ERROR_TABLE_NAME_NOT_SUPPORTED', $name), 0);
	}

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
		if (empty($record->id) || ($record->state != -2))
		{
			return false;
		}

		if (Factory::getUser()->authorise('core.delete', 'com_icagenda.event.' . (int) $record->id))
		{
			icagendaCustomfields::deleteData($record->id, 2);
			icagendaCustomfields::cleanData(2);

			return true;
		}

		return false;
	}

	/**
	 * Method to test whether a record can have its state edited.
	 *
	 * @param   object  $record  A record object.
	 *
	 * @return  boolean  True if allowed to change the state of the record. Defaults to the permission set in the component.
	 *
	 * @since   3.8
	 */
	protected function canEditState($record)
	{
		$user = Factory::getUser();

		// Check for existing event.
		if (!empty($record->id))
		{
			return $user->authorise('core.edit.state', 'com_icagenda.event.' . (int) $record->id);
		}

		// New event, so check against the category.
//		if (!empty($record->catid))
//		{
//			return $user->authorise('core.edit.state', 'com_icagenda.category.' . (int) $record->catid);
//		}

		// Default to component settings if neither article nor category known.
		return parent::canEditState($record);
	}

	/**
	 * Prepare and sanitise the table prior to saving.
	 *
	 * @param   JTable  $table  A JTable object.
	 *
	 * @return  void
	 */
	protected function prepareTable($table)
	{
		$date = Factory::getDate();
		$user = Factory::getUser();

		$table->name = htmlspecialchars_decode($table->name, ENT_QUOTES);

		if (empty($table->id))
		{
			// Set the values
			$table->created = $date->toSql();

			// Set ordering to the last item if not set
			if (empty($table->ordering))
			{
				$db = $this->getDbo();
				$query = $db->getQuery(true)
					->select('MAX(' . $db->quoteName('ordering') . ')')
					->from($db->quoteName('#__icagenda_events'));

				$db->setQuery($query);
				$max = $db->loadResult();

				$table->ordering = $max + 1;
			}
		}
		else
		{
			// Set the values
			$table->modified    = $date->toSql();
			$table->modified_by = $user->get('id');
		}

		Factory::getApplication()->triggerEvent('onICagendaPrepareTable', array('com_icagenda.event', $table));
	}

	/**
	 * Method to get a single record.
	 *
	 * @param   integer  $pk  The id of the primary key.
	 *
	 * @return  mixed  Object on success, false on failure.
	 */
	public function getItem($pk = null)
	{
		if ($item = parent::getItem($pk))
		{
			// Do any processing on fields here if needed
		}

		return $item;
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
		$form = $this->loadForm('com_icagenda.event', 'event',
								array('control' => 'jform', 'load_data' => $loadData));

		if (empty($form))
		{
			return false;
		}

		$iCparams = ComponentHelper::getParams('com_icagenda');

		// Set time format calendar form field
		$ampm = ($iCparams->get('timeformat', 1) == 1) ? '24' : '12';

		$form->setFieldAttribute('startdate', 'timeformat', $ampm);
		$form->setFieldAttribute('enddate', 'timeformat', $ampm);

		// Frontend edit form
		$app = Factory::getApplication();

//		if ($app->isClient('site') && Factory::getApplication()->input->get('layout') != 'event_edit')
		if ($app->isClient('site'))
		{
			$params = $app->getParams();

			// Remove Event Image fields if hidden
			if ($params->get('submit_imageDisplay', 1) == 0)
			{
				$form->removeField('image');
			}

			// Remove Period fields if hidden
			if ($params->get('submit_periodDisplay', 1) == 0)
			{
				$form->removeField('period_title');
				$form->removeField('startdate');
				$form->removeField('enddate');
				$form->removeField('weekdays');
				$form->removeField('weekdays_info');
				$form->removeField('period_spacer');
			}
			elseif ($params->get('submit_periodDisplay', 1) && $params->get('submit_weekdaysDisplay', 1) == 0)
			{
				$form->removeField('weekdays');
				$form->removeField('weekdays_info');
			}

			// Remove Single Dates field if hidden
			if ($params->get('submit_datesDisplay', 1) == 0)
			{
				$form->removeField('dates_title');
				$form->removeField('dates');
				$form->removeField('dates_spacer');
			}
			elseif ($params->get('submit_periodDisplay', 1) == 0)
			{
				$form->setFieldAttribute('dates', 'min', '1');
			}

			// Remove option Show Time field if hidden
			if ($params->get('submit_displaytimeDisplay', 0) == 0)
			{
				$form->removeField('dates_options_title');
				$form->removeField('displaytime');
				$form->removeField('dates_options_spacer');
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
				$form->removeField('shortdesc_info');
				$form->removeField('shortdesc');
				$form->removeField('shortdesc_spacer');
			}

			// Remove Description field if hidden
			if ($params->get('submit_descDisplay', 1) == 0)
			{
				$form->removeField('desc_title');
				$form->removeField('desc');
				$form->removeField('desc_spacer');
			}

			// Remove Meta Description field if hidden
			if ($params->get('submit_metadescDisplay', 0) == 0)
			{
				$form->removeField('metadesc_title');
				$form->removeField('metadesc_info');
				$form->removeField('metadesc');
				$form->removeField('metadesc_spacer');
			}

			// Remove Place (venue) field if hidden
			if ($params->get('submit_venueDisplay', 1) == 0)
			{
				$form->removeField('place');
			}

			// Remove Website field if hidden
			if ($params->get('submit_websiteDisplay', 1) == 0)
			{
				$form->removeField('website');
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
			}

			// Remove Phone (contact) field if hidden
			if ($params->get('submit_phoneDisplay', 1) == 0)
			{
				$form->removeField('phone');
			}

			if ($params->get('submit_emailDisplay', 1) == 0
				&& $params->get('submit_phoneDisplay', 1) == 0)
			{
				$form->removeField('contact_title');
			}

			if ($params->get('submit_venueDisplay', 1) == 0
				&& $params->get('submit_websiteDisplay', 1) == 0
				&& $params->get('submit_emailDisplay', 1) == 0
				&& $params->get('submit_phoneDisplay', 1) == 0)
			{
				$form->removeField('global_info_spacer');
			}

			// Remove File (attachment) field if hidden
			if ($params->get('submit_fileDisplay', 1) == 0)
			{
				$form->removeField('attachments_title');
				$form->removeField('file');
				$form->removeField('attachments_spacer');
			}

			// If Captcha not displayed, we remove validation for captcha form field
			if ($params->get('submit_captcha', 0) == 0)
			{
				$form->removeField('captcha');
			}
		}

		if ($iCparams->get('statutReg') == 1)
		{
			$form->setFieldAttribute('registration_spacer', 'showon', 'statutReg!:0', 'params');
			$form->setFieldAttribute('registration_options_title', 'showon', 'statutReg!:0', 'params');
			$form->setFieldAttribute('accessReg', 'showon', 'statutReg!:0', 'params');
			$form->setFieldAttribute('reg_deadline_time', 'showon', 'statutReg!:0', 'params');
			$form->setFieldAttribute('reg_deadline', 'showon', 'statutReg!:0', 'params');
			$form->setFieldAttribute('reg_cancellation', 'showon', 'statutReg!:0', 'params');
			$form->setFieldAttribute('custom_form', 'showon', 'statutReg!:0', 'params');
			$form->setFieldAttribute('registration_button_title', 'showon', 'statutReg!:0', 'params');
			$form->setFieldAttribute('RegButtonText', 'showon', 'statutReg!:0', 'params');
			$form->setFieldAttribute('RegButtonLink', 'showon', 'statutReg!:0', 'params');
			$form->setFieldAttribute('RegButtonLink_Article', 'showon', 'statutReg!:0[AND]RegButtonLink:1', 'params');
			$form->setFieldAttribute('RegButtonLink_Url', 'showon', 'statutReg!:0[AND]RegButtonLink:2', 'params');
			$form->setFieldAttribute('RegButtonTarget', 'showon', 'statutReg!:0', 'params');
			$form->setFieldAttribute('registration_tickets_title', 'showon', 'statutReg!:0', 'params');
			$form->setFieldAttribute('typeReg', 'showon', 'statutReg!:0', 'params');
			$form->setFieldAttribute('maxReg', 'showon', 'statutReg!:0', 'params');
			$form->setFieldAttribute('tickets', 'showon', 'statutReg!:0', 'params');
//			$form->setFieldAttribute('tickets_note', 'showon', 'statutReg!:0', 'params');
			$form->setFieldAttribute('maxRlistGlobal', 'showon', 'statutReg!:0', 'params');
			$form->setFieldAttribute('maxRlist', 'showon', 'statutReg!:0[AND]maxRlistGlobal:2', 'params');
			$form->setFieldAttribute('registration_tickets_spacer', 'showon', 'statutReg!:0', 'params');
		}

		$form->setFieldAttribute('RegButtonText', 'hint', Text::_($iCparams->get('RegButtonText', 'COM_ICAGENDA_REGISTRATION_REGISTER')), 'params');

//		foreach ($form->getGroup('') as $field)
//		{
//			if ($field->fieldname == 'dates')
//			{
//				$subForm = $field->loadSubForm();
//				$subForm->setFieldAttribute('date', 'timeformat', $ampm);
//			}
//		}

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
		$app = Factory::getApplication();

		$data_array = $app->getUserState('com_icagenda.edit.event.data', array());

		if (empty($data_array))
		{
			$data = $this->getItem();
		}
		else
		{
			$data = new CMSObject;
			$data->setProperties($data_array);
		}

//		if (Factory::getUser($data->created_by)->get('name') == false)
//		{
//			Log::add(Text::_('The author of this event is no longer a user on this site'), Log::WARNING, 'error');
//		}

		// Decode reg_deadline_time Months, Weeks, Days, Hours, Minutes.
		if (isset($data->params['reg_deadline_time']))
		{
			$reg_deadline_time = $data->params['reg_deadline_time'];

			if (\is_object($reg_deadline_time))
			{
				$reg_deadline_time = ArrayHelper::fromObject($reg_deadline_time);
			}
			elseif (!\is_array($reg_deadline_time))
			{
				$reg_deadline_time = json_decode($reg_deadline_time, true);
			}

			$data->params['reg_deadline_time'] = $reg_deadline_time;
		}

		// Set correctly param 'first_published_and_approved' if not set (for frontend submitted and approved events)
		if ($data->state == 1
			&& $data->approval == 0)
		{
			$data->params['first_published_and_approved'] = 1;
		}
		
		if (iCString::isSerialized($data->dates) && $data->dates)
		{
			$single_dates = unserialize($data->dates);

			rsort($single_dates);

			$dates = array();

			foreach ($single_dates as $key => $date)
			{
				if (iCDate::isDate($date))
				{
					$dates[] = array('date' => date('Y-m-d H:i', strtotime($date)));
				}
			}

			$data->dates = json_encode($dates, true);
		}

		// If not array, creates array with week days data
		if (isset($data->weekdays))
		{
			$data->weekdays = ( ! is_array($data->weekdays)) ? explode(',', $data->weekdays) : implode(',', $data->weekdays);
		}

		// Set displaytime default value
		if ( ! isset($data->displaytime))
		{
			$data->displaytime = ComponentHelper::getParams('com_icagenda')->get('displaytime', '1');
		}

		// Set Features
		$data->features = $this->getFeatures($data->id);

		// Convert features into an array so that the form control can be set
		if ( ! isset($data->features))
		{
			$data->features = array();
		}
		elseif ( ! is_array($data->features))
		{
			$data->features = explode(',', $data->features);
		}

		if ( ! isset($data->version_features))
		{
			$version_features = array();
		}
		elseif ( ! is_array($data->version_features))
		{
			$version_features = explode(',', $data->version_features);
		}

		if (isset($data->version_features) && ($version_features != $data->features) && ! is_array($data->version_features))
		{
			$data->features = $version_features;

			// Save Features to database
			$this->maintainFeatures(ArrayHelper::fromObject($data));
		}

		$customfields_data = icagendaCustomfields::getCustomfields(2);

		if (isset($data->version_customfields) && $data->version_customfields && $customfields_data && count($customfields_data) > 0)
		{
			$custom_fields = json_decode($data->version_customfields);

			foreach ($customfields_data as $icf)
			{
				$slug = $icf->slug;

				$version_value = isset($custom_fields->$slug)
								? $custom_fields->$slug
								: '';

				if ($icf->value !== $version_value)
				{
					// Save Custom Fields to database
					icagendaCustomfields::saveToData((array) $custom_fields, $app->input->get('id'), 2);
//					$app->enqueueMessage('Updated Custom Fields');
				}
			}
		}

//		$this->preprocessData('com_icagenda.event', $data);

		return $data;
	}

	/**
	 * Method to change the title & alias.
	 *
	 * @param   integer  $categoryId  The id of the category.
	 * @param   string   $alias       The alias.
	 * @param   string   $title       The title.
	 *
	 * @return  array  Contains the modified title and alias.
	 *
	 * @since   3.8
	 */
	protected function generateNewTitle($categoryId, $alias, $title)
	{
		// Alter the title & alias
		$table = $this->getTable();

		while ($table->load(array('title' => $title, 'alias' => $alias)))
		{
			$title = StringHelper::increment($title);
			$alias = StringHelper::increment($alias, 'dash');
		}

		return array($title, $alias);
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
		$table = $this->getTable();

		while ($table->load(array('alias' => $alias)))
		{
			$alias = StringHelper::increment($alias, 'dash');
		}

		return $alias;
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

		// Check Period Dates
		$isDate_startdate = iCDate::isDate($data['startdate']);
		$isDate_enddate   = iCDate::isDate($data['enddate']);
		$SQL_startDate    = $isDate_startdate ? \DateTime::createFromFormat(Text::_('DATE_FORMAT_FILTER_DATETIME'), $data['startdate'])->format('Y-m-d H:i:s') : '';
		$SQL_endDate      = $isDate_enddate ? \DateTime::createFromFormat(Text::_('DATE_FORMAT_FILTER_DATETIME'), $data['enddate'])->format('Y-m-d H:i:s') : '';

		// Check other form errors
		$errors = array();

		// Check dates from the period
		if ($isDate_startdate && $isDate_enddate
			&& ($SQL_startDate > $SQL_endDate))
		{
			$errors[] = Text::sprintf('COM_ICAGENDA_EVENT_ERROR_PERIOD', Text::_('COM_ICAGENDA_EVENT_ERROR_PERIOD_END_BEFORE_START'));
		}
		elseif ($isDate_startdate && ! $isDate_enddate)
		{
			$errors[] = Text::sprintf('COM_ICAGENDA_EVENT_ERROR_PERIOD', Text::_('COM_ICAGENDA_EVENT_ERROR_PERIOD_NO_ENDDATE'));
		}
		elseif ( ! $isDate_startdate && $isDate_enddate)
		{
			$errors[] = Text::sprintf('COM_ICAGENDA_EVENT_ERROR_PERIOD', Text::_('COM_ICAGENDA_EVENT_ERROR_PERIOD_NO_STARTDATE'));
		}
		elseif ( ! $isDate_startdate && ! $isDate_enddate && ! $data['dates'])
		{
			$errors[] = Text::sprintf('JLIB_FORM_VALIDATE_FIELD_REQUIRED', Text::_('COM_ICAGENDA_LEGEND_DATES'));
		}

		// Check the validation results.
		if (count($errors) > 0)
		{
			$app->enqueueMessage('<strong>' . Text::_('COM_ICAGENDA_FORM_NC') . '</strong>', 'error');

			// Get the validation messages from the other form errors.
			foreach ($errors as $message)
			{
				$app->enqueueMessage($message, 'error');
			}

			return false;
		}

		// Don't allow to change the users if not allowed to access com_users.
		if ($app->isClient('administrator') && !Factory::getUser()->authorise('core.manage', 'com_users'))
		{
			if (isset($data['created_by']))
			{
				unset($data['created_by']);
			}

			if (isset($data['modified_by']))
			{
				unset($data['modified_by']);
			}
		}

		return parent::validate($form, $data, $group);
	}

	/**
	 * Method to save the form data.
	 *
	 * @param   array  $data  The form data.
	 *
	 * @return  boolean  True on success.
	 *
	 * @since   3.4
	 */
	public function save($data)
	{
		$app    = Factory::getApplication();
		$input  = $app->input;
		$date   = Factory::getDate();
		$user   = Factory::getUser();
		$filter = InputFilter::getInstance();

		// Fix version before 3.4.0 to set a created date (will use last modified date if exists, or current date)
		if (empty($data['created']))
		{
			$data['created'] = ( ! empty($data['modified'])) ? $data['modified'] : $date->toSql();
		}

		// Event cancelled date
		if (empty($data['params']['event_cancelled_date']) && $data['params']['event_cancelled'] == 1)
		{
			$data['params']['event_cancelled_date'] = $date->toSql();
		}
		elseif ($data['params']['event_cancelled'] == 0)
		{
			unset($data['params']['event_cancelled_date']);
		}

		// Check first published and approved
		if ($data['state'] == 1
			&& $data['approval'] == 0
			&& $data['params']['first_published_and_approved'] == 0)
		{
			$data['params']['first_published_and_approved'] = 1;
			$first_published_and_approved = true;
		}
		else
		{
			$first_published_and_approved = false;
		}

		// Check maxReg zero value (Unlimited tickets)
		$nb_of_tickets = isset($data['params']['maxReg']) ? $data['params']['maxReg'] : '';

		if ($nb_of_tickets <= 0)
		{
			$data['params']['maxReg'] = '';
		}

		// Control max number of tickets per registration
		$max_tickets_per_registration        = isset($data['params']['maxRlistGlobal']) ? $data['params']['maxRlistGlobal'] : '';
		$max_tickets_per_registration_custom = isset($data['params']['maxRlist']) ? $data['params']['maxRlist'] : '';

		if ($max_tickets_per_registration == 2
			&& ($max_tickets_per_registration_custom > $data['params']['maxReg'])
			&& ($data['params']['maxReg'] > 0)
			)
		{
			$data['params']['maxRlist'] = $data['params']['maxReg'];
		}

		if ($max_tickets_per_registration_custom <= 0)
		{
			$data['params']['maxRlist'] = '';
		}

		// Encode reg_deadline_time Months, Weeks, Days, Hours, Minutes.
		if (isset($data['params']['reg_deadline_time']))
		{
			$data['params']['reg_deadline_time'] = json_encode($data['params']['reg_deadline_time']);
		}
		
		// Alter the title for save as copy
		if ($input->get('task') == 'save2copy') {
			$origTable = clone $this->getTable();
			$origTable->load($input->getInt('id'));

			if ($data['title'] == $origTable->title) {
				list($title, $alias) = $this->generateNewTitle($data['catid'], $data['alias'], $data['title']);
				$data['title'] = $title;
				$data['alias'] = $alias;
			} elseif ($data['alias'] == $origTable->alias) {
				$data['alias'] = '';
			}

//			if (empty($data['alias']))
//			{
//				// Use created date in case alias is still empty
//				$data['alias'] = OutputFilter::stringURLSafe($data['created']);
//			}

			$data['state'] = 0;
		}

		// Automatic handling of alias if empty (New Event)
		if (in_array($input->get('task'), array('apply', 'save', 'save2new')) && (!isset($data['id']) || (int) $data['id'] == 0)) {
			if ($data['alias'] == null) {
				if (Factory::getApplication()->get('unicodeslugs') == 1) {
					$data['alias'] = OutputFilter::stringUrlUnicodeSlug($data['title']);
				} else {
					$data['alias'] = OutputFilter::stringURLSafe($data['title']);
				}

//				if (empty($data['alias']))
//				{
//					// Use created date in case alias is still empty
//					$data['alias'] = OutputFilter::stringURLSafe($data['created']);
//				}

				$table = Table::getInstance('EventTable', 'WebiC\\Component\\iCagenda\\Administrator\\Table\\');

				if ($table->load(array('alias' => $data['alias']))) {
					$msg = Text::_('COM_ICAGENDA_EVENT_SAVE_ALIAS_INCREMENTED_WARNING');
				}

				$alias = $this->generateNewAlias($data['alias']);

				$data['alias'] = $alias;

				if (isset($msg)) {
					Factory::getApplication()->enqueueMessage($msg, 'warning');
				}
			}
		}

		// Force to not add unicode characters if unicodeslugs is not enabled.
		if ($app->isClient('administrator') && Factory::getConfig()->get('unicodeslugs') != 1) {
			$data['alias'] = OutputFilter::stringURLSafe($data['alias']);
		}

		// Check Period Dates
		$isDate_startdate = iCDate::isDate($data['startdate']);
		$isDate_enddate   = iCDate::isDate($data['enddate']);

		// Set Period Dates
		if ($isDate_startdate && $isDate_enddate)
		{
			$data['startdate'] = date('Y-m-d H:i:s', strtotime($data['startdate']));
			$data['enddate']   = date('Y-m-d H:i:s', strtotime($data['enddate']));

			$period_all_dates_array = iCDatePeriod::listDates($data['startdate'], $data['enddate']);

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
					$data['period'] = serialize($period_array);
				}
			}
			else
			{
				$data['period'] = serialize($period_all_dates_array);
			}
		}
		else
		{
			$data['startdate'] = '0000-00-00 00:00:00';
			$data['enddate']   = '0000-00-00 00:00:00';
			$data['period']    = '';
		}

		$data['weekdays'] = (isset($data['weekdays']) && is_array($data['weekdays'])) ? implode(",", $data['weekdays']) : '';


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
			$data['dates'] = serialize($dates);
		}
		else
		{
			$data['dates'] = '';
		}

		// Check Next Date
		$data['next'] = icagendaEvent::getNextDate($data, true);

		// Control of additional fields on the map.
		if ( ! isset($data['city']))    $data['city']    = '';
		if ( ! isset($data['country'])) $data['country'] = '';
		if ( ! isset($data['lat']))     $data['lat']     = '0';
		if ( ! isset($data['lng']))     $data['lng']     = '0';

		// Set File Uploaded
		if ( ! isset($data['file']))
		{
			$files   = $input->files->get('jform', null);
			$fileUrl = ! empty($files['file']['name']) ? $this->upload($files['file']) : '';

			$data['file'] = $fileUrl;
		}

		if ( ! isset($data['image']))
		{
			$files = $input->files->get('jform');
			$image = $files['image'];

			$data['image'] = ! empty($image['name']) ? $this->frontendImageUpload($image) : '';
		}

		// Set Creator infos
		if ($app->isClient('administrator')) {
			$userId   = $user->get('id');
			$userName = $user->get('name');

			if (empty($data['created_by']) && empty($data['username'])) {
				// Event created in admin, set current logged-in user as creator
				$data['created_by'] = (int) $userId;
				$data['username']   = $userName;
			} elseif ( ! empty($data['created_by']) && $data['created_by'] != $userId) {
				// Event edited in admin, created_by not empty, but creator is not the current logged-in user
				$data['username'] = Factory::getUser($data['created_by'])->get('name', '');
			}
		}

		if (isset($data['created_by_alias']))
		{
			$data['created_by_alias'] = $filter->clean($data['created_by_alias'], 'TRIM');
		}

		// Set Params
		if (isset($data['params']) && is_array($data['params']))
		{
			// Keep params for later control
			$params = $data['params'];

			// Convert the params field to a string.
			$parameter = new Registry;
			$parameter->loadArray($data['params']);

			$data['params'] = (string)$parameter;
		}

		$custom_fields = isset($data['custom_fields']) ? $data['custom_fields'] : '';

		$data['version_customfields'] = json_encode($custom_fields);

		if (isset($data['features']) && is_array($data['features']))
		{
			$data['version_features'] = implode(',', $data['features']);
		}
		else
		{
			$data['version_features'] = '';
		}

		// Save Data
		if (parent::save($data))
		{
			// Get Event ID after saving.
			$eventId = $this->getState($this->getName() . '.id');

			// Save Features to database
			$this->maintainFeatures($data);

//			$data['version_features'] = $this->getFeatures($eventId);

			// Store again for saving version_features (sic...)
//			parent::save($data);

			// Save Custom Fields to database
			if (isset($custom_fields) && is_array($custom_fields))
			{
				icagendaCustomfields::saveToData($custom_fields, $eventId, 2);
			}

			// Plugin Event handler 'iCagendaOnNewEvent'
			if ($first_published_and_approved)
			{
				Factory::getApplication()->triggerEvent('iCagendaOnNewEvent', array((object) $data)); // Deprecated 4.0
				Factory::getApplication()->triggerEvent('onICagendaNewEvent', array((object) $data));

				$currentDate  = icagendaEvent::currentDate((object) $data);
				$itemID       = icagendaMenus::thisEventItemid($currentDate, $data['catid']);
				$itemID       = $itemID ? '&Itemid=' . $itemID : ''; 
				$eventUrl     = 'index.php?option=com_icagenda&view=event&id=' . (int) $data['id'] . $itemID;
				$linkEmailUrl = Route::link('site', $eventUrl, '', '', true);
	
				icagendaManager::approvalNotification((object) $data, $linkEmailUrl);
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
	 * Upload File
	 *
	 * @since   3.5.3
	 */
	protected function upload($file)
	{
		// Get Joomla Images PATH setting
		$image_path = ComponentHelper::getParams('com_media')->get('image_path', 'images');

		// Get filename (name + ext)
		$filename = $file['name'];

		// Get file extension
		$fileExtension = File::getExt($filename);

		// Clean up file name to url safe string
		$fileTitle = iCOutputFilter::stringToSlug(File::stripExt($filename), '-');

		// If slug generated is empty, new slug based on current date/time
		if ( ! $fileTitle)
		{
			$fileTitle = Factory::getDate()->format("YmdHis");
		}

		// Return new filename
		$filename = $fileTitle . '.' . $fileExtension;

		// Increment file name if filename already exists
		while (File::exists(JPATH_SITE . '/' . $image_path . '/icagenda/files/' . $filename))
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
			$src  = $file['tmp_name'];
			$dest = JPATH_SITE . '/' . $image_path . '/icagenda/files/' . $filename;

			// Create folder 'files' in ROOT/IMAGES_PATH/icagenda/ if does not exist
			$folder[0][0] = 'icagenda/files/' ;
			$folder[0][1] = JPATH_SITE . '/' . $image_path . '/' . $folder[0][0];

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

			// Return file path on success
			if (File::upload($src, $dest, false))
			{
				return $image_path . '/icagenda/files/' . $filename;
			}
		}
	}

	/**
	 * Maintain features to data
	 *
	 * @since   3.4
	 */
	protected function maintainFeatures($data)
	{
		// Get the list of feature ids to be linked to the event
		$features = isset($data['features']) && is_array($data['features']) ? implode(',', $data['features']) : '';

		$db = Factory::getDbo();

		// Write any new feature records to the icagenda_feature_xref table
		if ( ! empty($features))
		{
			// Get a list of the valid features already present for this event
			$query = $db->getQuery(true);

			$query->select('feature_id')
				->from($db->qn('#__icagenda_feature_xref'));

			$query->where('event_id = ' . (int) $data['id']);
			$query->where('feature_id IN (' . $features . ')');

			$db->setQuery($query);

			$existing_features = $db->loadColumn(0);

			// Identify the insert list
			if (empty($existing_features))
			{
				$new_features = $data['features'];
			}
			else
			{
				$new_features = array();

				foreach ($data['features'] as $feature)
				{
					if ( ! in_array($feature, $existing_features))
					{
						$new_features[] = $feature;
					}
				}
			}
			// Write the needed xref records
			if ( ! empty($new_features))
			{
				$xref = new CMSObject;
				$xref->set('event_id', $data['id']);

				foreach ($new_features as $feature)
				{
					$xref->set('feature_id', $feature);
					$db->insertObject('#__icagenda_feature_xref', $xref);
					$db->setQuery($query);

					if ( ! $db->execute())
					{
						return false;
					}
				}
			}
		}

		// Delete any unwanted feature records from the icagenda_feature_xref table
		$query = $db->getQuery(true);
		$query->delete($db->qn('#__icagenda_feature_xref'));
		$query->where('event_id = ' . (int) $data['id']);

		if ( ! empty($features))
		{
			// Delete only unwanted features
			$query->where('feature_id NOT IN (' . $features . ')');
		}

		$db->setQuery($query);
		$db->execute($query);

		if ( ! $db->execute())
		{
			return false;
		}

		return true;
	}

	/**
	 * Extracts the list of Feature IDs linked to the event and returns an array
	 *
	 * @param   integer  $event_id
	 *
	 * @return  array/integer  Set of Feature IDs
	 *
	 * @since   3.5.3
	 */
	protected function getFeatures($event_id)
	{
		// Write any new feature records to the icagenda_feature_xref table
		if (empty($event_id))
		{
			return '';
		}
		else
		{
			$db = Factory::getDbo();

			// Get a comma separated list of the ids of features present for this event
			// Note: Direct extraction of a comma separated list is avoided because each db type uses proprietary syntax
			$query = $db->getQuery(true);
			$query->select('fx.feature_id')
				->from($db->qn('#__icagenda_events', 'e'))
				->innerJoin('#__icagenda_feature_xref AS fx ON e.id=fx.event_id')
				->innerJoin('#__icagenda_feature AS f ON fx.feature_id=f.id AND f.state=1');
			$query->where('e.id = ' . (int) $event_id);
			$db->setQuery($query);
			$features = $db->loadColumn(0);

			// Return a comma separated list
			return implode(',', $features);
		}
	}

	/**
	 * Approve Function.
	 *
	 * @since   3.2
	 */
	function approve($cids, $approval = 0)
	{
		if (\count($cids) > 0)
		{
			ArrayHelper::toInteger($cids);

			$eventids = implode( ',', $cids );

			$db    = Factory::getDbo();
			$query = $db->getQuery(true);

			$query
				->update($db->quoteName('#__icagenda_events'))
				->set('approval = ' . (int) $approval)
				->where('id IN (' . $eventids . ')');

			$db->setQuery($query);

			try
			{
				$result = $db->execute();
			}
			catch (\RuntimeException $e)
			{
				throw new \Exception($e->getMessage(), 500, $e);
			}

			if ($approval == 0)
			{
				foreach ($cids as $id)
				{
					$query = $db->getQuery(true);
			
					$query
						->select($db->quoteName(array('id', 'catid', 'next', 
							'created_by_alias', 'created_by_email', 'username', 'title',
							'startdate', 'enddate', 'weekdays', 'dates')))
						->from($db->quoteName('#__icagenda_events'))
						->where($db->quoteName('id') . ' = ' . (int) $id);

					$db->setQuery($query);

					$event = $db->loadObject();

					$currentDate  = icagendaEvent::currentDate($event);
					$itemID       = icagendaMenus::thisEventItemid($currentDate, $event->catid);
					$itemID       = $itemID ? '&Itemid=' . $itemID : ''; 
					$eventUrl     = 'index.php?option=com_icagenda&view=event&id=' . $id . $itemID;
					$linkEmailUrl = Route::link('site', $eventUrl, '', '', true);

					icagendaManager::approvalNotification($event, $linkEmailUrl);
				}
			}
		}

		return true;
	}

	/**
	 * Image Upload
	 *
	 * @since   3.8
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
		$imageTitle = iCOutputFilter::stringToSlug(File::stripExt($imagename), '-');

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
}
