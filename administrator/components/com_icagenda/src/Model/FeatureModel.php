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

use Joomla\CMS\Factory;
use Joomla\CMS\Filter\OutputFilter;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\Model\AdminModel;

/**
 * iCagenda Component Feature Model.
 */
class FeatureModel extends AdminModel
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
	public function getTable($name = 'Feature', $prefix = 'Table', $config = array())
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
	 * @param   array    $data       An optional array of data for the form to interogate.
	 * @param   boolean  $loadData  True if the form is to load its own data (default case), false if not.
	 *
	 * @return  JForm  A JForm object on success, false on failure
	 */
	public function getForm($data = array(), $loadData = true)
	{
		// Get the form.
		$form = $this->loadForm('com_icagenda.feature', 'feature',
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
		$data = Factory::getApplication()->getUserState('com_icagenda.edit.feature.data', array());

		if (empty($data))
		{
			$data = $this->getItem();
		}

		return $data;
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
			// Set ordering to the last item if not set
			if (empty($table->ordering))
			{
				$db = Factory::getDbo();
				$query = $db->getQuery(true)
					->select('MAX(ordering)')
					->from($db->quoteName('#__icagenda_feature'));
				$db->setQuery($query);
				$max = $db->loadResult();

				$table->ordering = $max + 1;
			}
		}
	}

	/**
	 * Method to save the form data.
	 *
	 * @param   array  $data  The form data.
	 *
	 * @return  boolean  True on success.
	 */
	public function save($data)
	{
		$date = Factory::getDate();

		if (empty($data['created']))
		{
			$data['created'] = ! empty($data['created']) ? $data['created'] : $date->toSql();
		}

		// Generates Alias if empty
		// Alias is not generated if non-latin characters, so we fix it by using created date, or title if unicode is activated, as alias
		if ($data['alias'] == null || empty($data['alias']))
		{
			$data['alias'] = OutputFilter::stringURLSafe($data['title']);

			if ($data['alias'] == null || empty($data['alias']))
			{
				if (Factory::getConfig()->get('unicodeslugs') == 1)
				{
					$data['alias'] = OutputFilter::stringURLUnicodeSlug($data['title']);
				}
				else
				{
					$data['alias'] = OutputFilter::stringURLSafe($data['created']);
				}
			}
		}

		$return = parent::save($data);

		return $return;
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
}
