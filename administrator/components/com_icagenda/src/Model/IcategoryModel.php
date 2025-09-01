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

use Joomla\CMS\Factory;
use Joomla\CMS\Filter\OutputFilter;
use Joomla\CMS\Form\Form;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\Model\AdminModel;
use Joomla\CMS\Object\CMSObject;
use Joomla\CMS\Plugin\PluginHelper;
use Joomla\CMS\Table\Table;
use Joomla\CMS\Versioning\VersionableModelTrait;
use Joomla\String\StringHelper;
use WebiC\Component\iCagenda\Administrator\Helper\iCagendaHelper;

/**
 * iCagenda Component Icategory Model.
 */
class IcategoryModel extends AdminModel
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
	public $typeAlias = 'com_icagenda.icategory';

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
	public function getTable($name = 'Icategory', $prefix = 'Table', $config = array())
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
		$form = $this->loadForm('com_icagenda.icategory', 'category',
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
		$data_array = Factory::getApplication()->getUserState('com_icagenda.edit.icategory.data', array());

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
		if (empty($table->id))
		{
			// Set ordering to the last item if not set
			if (empty($table->ordering))
			{
				$db = $this->getDbo();
				$query = $db->getQuery(true)
					->select('MAX(' . $db->quoteName('ordering') . ')')
					->from($db->quoteName('#__icagenda_category'));

				$db->setQuery($query);
				$max = $db->loadResult();

				$table->ordering = $max + 1;
			}
		}

		Factory::getApplication()->triggerEvent('onICagendaPrepareTable', array('com_icagenda.icategory', $table));
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
		$input = Factory::getApplication()->input;
		$date  = Factory::getDate();

		// Fix version before 3.4.0 to set a created date (will use last modified date if exists, or current date)
		if (empty($data['created']))
		{
			$data['created'] = ! empty($data['modified']) ? $data['modified'] : $date->toSql();
		}

		$origTable = clone $this->getTable();
		$origTable->load($input->getInt('id'));

		// Alter the title for save as copy
		if ($input->get('task') == 'save2copy') {
//			$origTable = clone $this->getTable();
//			$origTable->load($input->getInt('id'));

			if ($data['title'] == $origTable->title) {
				list($title, $alias) = $this->generateNewTitle(null, $data['alias'], $data['title']);
				$data['title'] = $title;
				$data['alias'] = $alias;
			} elseif ($data['alias'] == $origTable->alias) {
				$data['alias'] = '';
			}

			$data['state'] = 0;
		}

//		if ((int) $data['state'] !== (int) $origTable->state)
//		{
//			$this->publish($origTable->id, $data['state']);
//		}

		// Automatic handling of alias if empty
		if (in_array($input->get('task'), array('apply', 'save', 'save2new')) && $data['alias'] == null) {
			if (Factory::getConfig()->get('unicodeslugs') == 1) {
				$data['alias'] = OutputFilter::stringURLUnicodeSlug($data['title']);
			} else {
				$data['alias'] = OutputFilter::stringURLSafe($data['title']);
			}
		}

//		// Use created date in case alias is still empty
//		if ($data['alias'] == null || empty($data['alias']))
//		{
//			$data['alias'] = OutputFilter::stringURLSafe($data['created']);
//		}

		// Force to not add unicode characters if unicodeslugs is not enabled
		if (Factory::getConfig()->get('unicodeslugs') != 1)
		{
			$data['alias'] = OutputFilter::stringURLSafe($data['alias']);
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

	/**
	 * Method to change the published state of one or more records.
	 *
	 * @param   array    $pks    A list of the primary keys to change.
	 * @param   integer  $value  The value of the published state.
	 *
	 * @return  boolean  True on success.
	 *
	 * @since   2.5
	 */
	public function publish(&$pks, $value = 1)
	{
		if (parent::publish($pks, $value))
		{
			// Include the content plugins for the change of category state event.
			PluginHelper::importPlugin('content');

			// Trigger the onCategoryChangeState event.
			Factory::getApplication()->triggerEvent('onCategoryChangeState', array('com_icagenda', $pks, $value));

			return true;
		}
	}
}
