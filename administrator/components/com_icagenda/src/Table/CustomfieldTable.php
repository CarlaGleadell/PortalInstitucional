<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.8.5 2022-05-10
 *
 * @package     iCagenda.Admin
 * @subpackage  src.Table
 * @link        https://www.icagenda.com
 *
 * @author      Cyril Rezé
 * @copyright   (c) 2012-2024 Cyril Rezé / iCagenda. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 *
 * @since       3.4
 *----------------------------------------------------------------------------
*/

namespace WebiC\Component\iCagenda\Administrator\Table;

\defined('_JEXEC') or die;

use iClib\Filter\Output as iCFilterOutput;
use Joomla\CMS\Factory;
use Joomla\CMS\Filter\OutputFilter;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Table\Table;
use Joomla\Database\DatabaseDriver;
use Joomla\Registry\Registry;

/**
 * iCagenda Component Custom Field Table
 */
class CustomfieldTable extends Table
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
		parent::__construct('#__icagenda_customfields', 'id', $db);

		// Set the alias for 'published' since the column is called 'state'
		$this->setColumnAlias('published', 'state');
	}

	/**
	 * Overloaded bind function.
	 *
	 * @param   array        Named array
	 *
	 * @return  null|string  null is operation was satisfactory, otherwise returns an error
	 * @see     JTable:bind
	 */
	public function bind($array, $ignore = '')
	{
		// Set Creator infos
		$user   = Factory::getUser();
		$userId = $user->get('id');

		if ($array['created_by'] == '0')
		{
			$array['created_by'] = (int) $userId;
		}

		// Set Params
		if (isset($array['params']) && is_array($array['params']))
		{
			$registry = new Registry();
			$registry->loadArray($array['params']);
			$array['params'] = (string) $registry;
		}

		// Force system slugs for core fields and protect from usage in other field types
		$coreFields = array('core_name', 'core_email', 'core_phone', 'core_date', 'core_people');

		if (in_array($array['type'], $coreFields))
		{
			$array['slug'] = $array['type'];
		}
		elseif (in_array($array['slug'], $coreFields)
			&& ! in_array($array['type'], $coreFields))
		{
			$array['slug'] = $array['slug'] . '_copy';
		}

		// Set Placeholder to Options, and unset placeholder (if input type with hint)
		$hintFields = array('text', 'url', 'tel', 'email', 'core_name', 'core_email', 'core_phone');

		if (isset($array['placeholder'])
			&& in_array($array['type'], $hintFields))
		{
			$array['options'] = $array['placeholder'];
			unset($array['placeholder']);
		}

		// Set Class to Options, and unset spacer_class (if input type is spacer)
		$spacerFields = array('spacer_label', 'spacer_description');

		if (isset($array['spacer_class'])
			&& in_array($array['type'], $spacerFields))
		{
			$array['options'] = $array['spacer_class'];
			unset($array['spacer_class']);
		}

		// Set Groups
		if ( ! isset($array['groups']))
		{
			$array['groups'] = '';
		}
		elseif (is_array($array['groups']))
		{
			$array['groups'] = implode(',', $array['groups']);
		}

		return parent::bind($array, $ignore);
	}

	/**
	* Overloaded check function
	*/
	public function check()
	{
		// If there is an ordering column and this is a new row then get the next ordering value
		if (property_exists($this, 'ordering')
			&& $this->id == 0)
		{
			$this->ordering = self::getNextOrder();
		}

		// Set modified to created if not set
		if (!$this->modified)
		{
			$this->modified = $this->created;
		}

		// URL alias
		if (empty($this->alias))
		{
			$this->alias = $this->title;
		}

		$this->alias = OutputFilter::stringURLSafe($this->alias);

		// Alias is not generated if non-latin characters, so we fix it by using created date, or title if unicode is activated, as alias
		if ($this->alias == null || empty($this->alias))
		{
			if (Factory::getConfig()->get('unicodeslugs') == 1)
			{
				$this->alias = OutputFilter::stringURLUnicodeSlug($this->title);
			}
			else
			{
				$this->alias = OutputFilter::stringURLSafe($this->created);
			}
		}

		// Slug auto-create
		$slug_empty = empty($this->slug) ? true : false;

		if ($slug_empty)
		{
			$this->slug = $this->title;
		}

		$this->slug = iCFilterOutput::stringToSlug($this->slug);

		// Slug is not generated if non-latin characters, so we fix it by using created date as a slug
		if ($this->slug == null)
		{
			$this->slug = iCFilterOutput::stringToSlug($this->created);
		}

		// Check if Slug already exists
		$db = Factory::getDbo();
		$query = $db->getQuery(true)
			->select('slug')
			->from($db->qn('#__icagenda_customfields'))
			->where($db->qn('slug') . ' = ' . $db->q($this->slug));

		if (!empty($this->id))
		{
			$query->where('id <> ' . (int) $this->id);
		}

		$db->setQuery($query);
		$slug_exists = $db->loadResult();

		if ($slug_exists)
		{
			$error_slug = $slug_empty
						? Text::sprintf('COM_ICAGENDA_CUSTOMFIELD_DATABASE_ERROR_AUTO_SLUG',
										'<strong>' . $this->title . '</strong>', '<strong>' . $this->slug . '</strong>')
						: '<strong>' . Text::_('COM_ICAGENDA_CUSTOMFIELD_DATABASE_ERROR_UNIQUE_SLUG') . '</strong>';

			$this->setError($error_slug . '<br /><br /><span class="iCicon-info-circle"></span> <i>'
							. Text::_('COM_ICAGENDA_CUSTOMFIELD_SLUG_DESC').'</i>');

			return false;
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
	 * @since   3.8.5
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

		return parent::store($updateNulls);
	}
}
