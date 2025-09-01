<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.8.21 2023-10-22
 *
 * @package     iCagenda.Admin
 * @subpackage  src.Table
 * @link        https://www.icagenda.com
 *
 * @author      Cyril Rezé
 * @copyright   (c) 2012-2024 Cyril Rezé / iCagenda. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 *
 * @since       1.0
 *----------------------------------------------------------------------------
*/

namespace WebiC\Component\iCagenda\Administrator\Table;

\defined('_JEXEC') or die;

use Joomla\CMS\Application\ApplicationHelper;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Filter\OutputFilter;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Table\Table;
use Joomla\CMS\Versioning\VersionableTableInterface;
use Joomla\Database\DatabaseDriver;
use Joomla\Registry\Registry;
use Joomla\String\StringHelper;
use Joomla\Utilities\ArrayHelper;

/**
 * iCagenda Component Event Table
 */
class EventTable extends Table implements VersionableTableInterface
{
	/**
	 * Indicates that columns fully support the NULL value in the database
	 *
	 * @var    boolean
	 */
	protected $_supportNullValue = true;

	/**
	 * Constructor
	 *
	 * @param   DatabaseDriver  $db  Database connector object
	 */
	public function __construct(DatabaseDriver $db)
	{
		$this->typeAlias = 'com_icagenda.event';

		parent::__construct('#__icagenda_events', 'id', $db);

		// Set the alias for 'published' since the column is called 'state' (since Joomla 3.7)
		$this->setColumnAlias('published', 'state');

//		if (version_compare(JVERSION, '4.0', '<' ) == 1)
//		{
//			\JTableObserverContenthistory::createObserver($this, array('typeAlias' => 'com_icagenda.event'));
//		}
	}

	/**
	 * Overloaded bind function to pre-process the params.
	 *
	 * @param   array        Named array
	 *
	 * @return  null|string  null is operation was satisfactory, otherwise returns an error
	 *
	 * @see     JTable:bind
	 */
	public function bind($array, $ignore = '')
	{
		if (isset($array['params']) && is_array($array['params']))
		{
			$registry = new Registry();
			$registry->loadArray($array['params']);
			$array['params'] = (string) $registry;
		}

		return parent::bind($array, $ignore);
	}

	/**
	 * Overloaded check function
	 */
	public function check()
	{
		// If there is an ordering column and this is a new row then get the next ordering value
		if (property_exists($this, 'ordering') && $this->id == 0)
		{
			$this->ordering = self::getNextOrder();
		}

		// Set modified to created if not set
		if (!$this->modified)
		{
			$this->modified = $this->created;
		}

		/**
		 * Ensure any new items have compulsory fields set. This is needed for things like
		 * frontend editing where we don't show all the fields or using some kind of API
		 */
		if (!$this->id) {
			// Hits must be zero on a new item
			$this->hits = 0;
		}

		return parent::check();
	}

	/**
	 * Overrides Table::store to set modified data.
	 *
	 * @param   boolean  $updateNulls  True to update fields even if they are null.
	 *
	 * @return  boolean  True on success.
	 *
	 * @since   3.8
	 */
	public function store($updateNulls = true)
	{
		$date   = Factory::getDate()->toSql();
		$user   = Factory::getUser();
		$userId = $user->get('id');

		// Set created date if not set.
		if (!(int) $this->created)
		{
			$this->created = $date;
		}

		if ($this->id)
		{
			// Existing item
			$this->modified_by = $userId;
			$this->modified    = $date;
		}
		else
		{
			// Field created_by can be set by the user, so we don't touch it if it's set.
			if (empty($this->created_by))
			{
				$this->created_by = $userId;
			}

			// Set modified to created date if not set
			if (!(int) $this->modified)
			{
				$this->modified = $this->created;
			}

			// Set modified_by to created_by user if not set
			if (empty($this->modified_by))
			{
				$this->modified_by = $this->created_by;
			}
		}

		if (Factory::getApplication()->isClient('administrator'))
		{
			$alias = $this->alias;

			// Get an instance of the table
			/** @var EventTable $table */
			$table = Table::getInstance('EventTable', __NAMESPACE__ . '\\', ['dbo' => $this->getDbo()]);

			// Verify that the alias is unique
			if ($table->load(array('alias' => $this->alias)) && ($table->id != $this->id || $this->id == 0)) {
				$this->setError('test' . Text::_('COM_ICAGENDA_EVENT_ERROR_UNIQUE_ALIAS'));

				if ($table->state === -2) {
					$this->setError('test' . Text::_('COM_ICAGENDA_EVENT_ERROR_UNIQUE_ALIAS_TRASHED'));
				}

				return false;
			}

			if (empty($this->alias)) {
				if (Factory::getApplication()->get('unicodeslugs') == 1) {
					$set_alias = OutputFilter::stringUrlUnicodeSlug($this->title);
				} else {
					$set_alias = OutputFilter::stringURLSafe($this->title);
				}

				if (empty($set_alias)) {
					// Use created date in case alias is still empty
					$set_alias = OutputFilter::stringURLSafe($this->created);
				}

				if ($table->load(array('alias' => $set_alias)) && ($table->id != $this->id || $this->id == 0)) {
					// New generated alias already in database.
					$msg = Text::_('COM_ICAGENDA_EVENT_SAVE_ALIAS_INCREMENTED_WARNING');

					$this->alias = $this->generateNewAlias($table, $set_alias);
				} else {
					// Title changed and new generated alias different from previous alias.
					if ( ! empty($alias) && ($alias != $set_alias)) {
						$msg = Text::_('COM_ICAGENDA_EVENT_SAVE_ALIAS_AUTO_GENERATED_WARNING');
					}

					$this->alias = $set_alias;
				}

				if (isset($msg)) {
					Factory::getApplication()->enqueueMessage($msg, 'warning');
				}
			}
		}

		return parent::store($updateNulls);
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
	protected function generateNewAlias($table, $alias)
	{
		while ($table->load(array('alias' => $alias)))
		{
			$alias = StringHelper::increment($alias, 'dash');
		}

		return $alias;
	}

	/**
	 * Get the type alias for the history table
	 *
	 * @return  string  The alias as described above
	 *
	 * @since   3.8
	 */
	public function getTypeAlias()
	{
		return $this->typeAlias;
	}
}
