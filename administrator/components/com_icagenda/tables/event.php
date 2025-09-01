<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.8.21 2023-10-23
 *
 * @package     iCagenda.Admin
 * @link        https://www.icagenda.com
 *
 * @author      Cyril Rezé
 * @copyright   (c) 2012-2024 Cyril Rezé / iCagenda. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 *
 * @since       1.0
 *----------------------------------------------------------------------------
*/

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Filter\OutputFilter;
use Joomla\String\StringHelper;

/**
 * Event Table class
 */
class iCagendaTableEvent extends JTable
{
	/**
	 * Constructor
	 *
	 * @param   JDatabaseDriver  $db  Database connector object
	 *
	 * @since   1.0
	 */
	public function __construct(&$db)
	{
		parent::__construct('#__icagenda_events', 'id', $db);

		// Set the alias for 'published' since the column is called 'state'
		$this->setColumnAlias('published', 'state');
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
		$date = Factory::getDate()->toSql();
		$user = Factory::getUser();

		// Set created date if not set.
		if (!(int) $this->created)
		{
			$this->created = $date;
		}

		if ($this->id)
		{
			// Existing item
			$this->modified_by = $user->get('id');
			$this->modified    = $date;
		}
		else
		{
			// Field created_by can be set by the user, so we don't touch it if it's set.
			if (empty($this->created_by))
			{
				$this->created_by = $user->get('id');
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
			$table = JTable::getInstance('Event', 'iCagendaTable', array('dbo' => $this->_db));

			// Verify that the alias is unique (Existing Events. Fix 3.8)
			if ($table->load(array('alias' => $this->alias)) && ($table->id != $this->id || $this->id == 0))
			{
				$this->setError('test' . JText::_('COM_ICAGENDA_EVENT_ERROR_UNIQUE_ALIAS'));

				if ($table->state === -2) {
					$this->setError('test' . JText::_('COM_ICAGENDA_EVENT_ERROR_UNIQUE_ALIAS_TRASHED'));
				}

				return false;
			}

			if (empty($this->alias))
			{
				if (Factory::getApplication()->get('unicodeslugs') == 1)
				{
					$set_alias = OutputFilter::stringUrlUnicodeSlug($this->title);
				}
				else
				{
					$set_alias = OutputFilter::stringURLSafe($this->title);
				}

				if (empty($set_alias)) {
					// Use created date in case alias is still empty
					$set_alias = OutputFilter::stringURLSafe($this->created);
				}

				if ($table->load(array('alias' => $set_alias)) && ($table->id != $this->id || $this->id == 0))
				{
					// New generated alias already in database.
					$msg = JText::_('COM_ICAGENDA_EVENT_SAVE_ALIAS_INCREMENTED_WARNING');

					$this->alias = $this->generateNewAlias($table, $set_alias);
				}
				else
				{
					// Title changed and new generated alias different from previous alias.
					if ( ! empty($alias) && ($alias != $set_alias))
					{
						$msg = JText::_('COM_ICAGENDA_EVENT_SAVE_ALIAS_AUTO_GENERATED_WARNING');
					}

					$this->alias = $set_alias;
				}

				if (isset($msg))
				{
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
	 * Method to set the sticky state for a row or list of rows in the database
	 * table.  The method respects checked out rows by other users and will attempt
	 * to checkin rows that it can after adjustments are made.
	 *
	 * @param   mixed    $pks     An optional array of primary key values to update.  If not set the instance property value is used.
	 * @param   integer  $state   The sticky state. eg. [0 = unsticked, 1 = sticked]
	 * @param   integer  $userId  The user id of the user performing the operation.
	 *
	 * @return  boolean  True on success.
	 *
	 * @since   3.8
	 */
	public function stick($pks = null, $state = 1, $userId = 0)
	{
		$k = $this->_tbl_key;

		// Sanitize input.
		$pks    = ArrayHelper::toInteger($pks);
		$userId = (int) $userId;
		$state  = (int) $state;

		// If there are no primary keys set check to see if the instance key is set.
		if (empty($pks))
		{
			if ($this->$k)
			{
				$pks = array($this->$k);
			}
			// Nothing to set publishing state on, return false.
			else
			{
				$this->setError(JText::_('JLIB_DATABASE_ERROR_NO_ROWS_SELECTED'));

				return false;
			}
		}

		// Get an instance of the table
		/** @var iCagendaTableEvent $table */
		$table = JTable::getInstance('Event', 'iCagendaTable');

		// For all keys
		foreach ($pks as $pk)
		{
			// Load the banner
			if (!$table->load($pk))
			{
				$this->setError($table->getError());
			}

			// Verify checkout
			if (is_null($table->checked_out) || $table->checked_out == $userId)
			{
				// Change the state
				$table->sticky = $state;
				$table->checked_out = null;
				$table->checked_out_time = null;

				// Check the row
				$table->check();

				// Store the row
				if (!$table->store())
				{
					$this->setError($table->getError());
				}
			}
		}

		return count($this->getErrors()) == 0;
	}
}
