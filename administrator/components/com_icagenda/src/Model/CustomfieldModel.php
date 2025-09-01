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
 * @since       3.4.0
 *----------------------------------------------------------------------------
*/

namespace WebiC\Component\iCagenda\Administrator\Model;

\defined('_JEXEC') or die;

use iClib\String\StringHelper as iCString;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\Model\AdminModel;

/**
 * iCagenda Component Custom Field Model.
 */
class CustomfieldModel extends AdminModel
{
	/**
	 * @var  string  The prefix to use with controller messages.
	 */
	protected $text_prefix = 'COM_ICAGENDA';

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
	public function getTable($name = 'Customfield', $prefix = 'Table', $config = array())
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
	 * @param   array    $data      An optional array of data for the form to interogate.
	 * @param   boolean  $loadData  True if the form is to load its own data (default case), false if not.
	 *
	 * @return  JForm  A JForm object on success, false on failure
	 */
	public function getForm($data = array(), $loadData = true)
	{
		// Get the form.
		$form = $this->loadForm('com_icagenda.customfield', 'customfield',
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
		$data = Factory::getApplication()->getUserState('com_icagenda.edit.customfield.data', array());

		if (empty($data))
		{
			$data = $this->getItem();
		}

		// Set Options to placeholder, and unset options (if input type with hint)
		$hintFields = array('text', 'url', 'tel', 'email', 'core_name', 'core_email', 'core_phone');

		if (isset($data->options)
			&& in_array($data->type, $hintFields))
		{
			$data->placeholder = $data->options;
			unset($data->options);
		}

		// Set Options to spacer_class, and unset options (if input type is spacer)
		$spacerFields = array('spacer_label', 'spacer_description');

		if (isset($data->options)
			&& in_array($data->type, $spacerFields))
		{
			$data->spacer_class = $data->options;
			unset($data->options);
		}

		// If not array, creates array with custom forms data
		if (isset($data->groups)
			&&  ! is_array($data->groups))
		{
			$data->groups = explode(',', $data->groups);
		}

		return $data;
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
			// Do any processing on fields here if needed
		}

		return $item;
	}

	/**
	 * Prepare and sanitise the table prior to saving.
	 */
	protected function prepareTable($table)
	{
		$app = Factory::getApplication();

		$date = Factory::getDate();
		$user = Factory::getUser();

		if (empty($table->id))
		{
			// Set the values
			$table->created = $date->toSql();

			// Set ordering to the last item if not set
			if (empty($table->ordering))
			{
				$db = Factory::getDbo();
				$query = $db->getQuery(true)
					->select('MAX(ordering)')
					->from($db->quoteName('#__icagenda_customfields'));
				$db->setQuery($query);
				$max = $db->loadResult();

				$table->ordering = $max + 1;
			}
		}
		else
		{
			// Set the values
			$table->modified = $date->toSql();
			$table->modified_by = $user->get('id');
		}

		// Alter the title for save as copy
		if ($app->input->get('task') == 'save2copy')
		{
			$table->title = iCString::increment($table->title);
			$table->alias = iCString::increment($table->alias, 'dash');
			$table->slug = iCString::increment($table->slug, 'underscore');
			$table->state = '0';
		}
	}
}
