<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.9.0 2023-10-25
 *
 * @package     iCagenda.Admin
 * @link        https://www.icagenda.com
 *
 * @author      Cyril Rezé
 * @copyright   (c) 2012-2024 Cyril Rezé / iCagenda. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 *
 * @since       3.4.0
 *----------------------------------------------------------------------------
*/

defined('_JEXEC') or die;

/**
 * feature Table class
 */
class iCagendaTablefeature extends JTable
{
	protected $new_icon = null;

	/**
	 * Constructor
	 *
	 * @param   JDatabaseDriver  $db  Database connector object
	 *
	 * @since   3.4.0
	 */
	public function __construct(&$db)
	{
		parent::__construct('#__icagenda_feature', 'id', $db);

		// Set the alias for 'published' since the column is called 'state'
		$this->setColumnAlias('published', 'state');
	}

	/**
	 * Overloaded bind function to pre-process the params.
	 *
	 * @param	array		Named array
	 * @return	null|string	null is operation was satisfactory, otherwise returns an error
	 * @see		JTable:bind
	 * @since	3.4.0
	 */
	public function bind($array, $ignore = '')
	{
		if (isset($array['new_icon']))
		{
			// Get media path
			$params_media	= JComponentHelper::getParams('com_media');
			$image_path		= $params_media->get('image_path', 'images');

			// Paths to feature icons folder
			$thumbsPath		= $image_path . '/icagenda/feature_icons';

			// Get Image File Infos
			$link_image		= $array['new_icon'];
			$decomposition	= explode( '/' , $link_image );

			// in each parent
			$i = 0;

			while ( isset($decomposition[$i]) )
				$i++;
			$i--;

			$imgname		= $decomposition[$i];
			$fichier		= explode( '.', $decomposition[$i] );
			$imgtitle		= $fichier[0];
			$imgextension	= strtolower($fichier[1]);

			// Check file type if authorized to be generated as feature icon
			$authorized_types = array('jpg', 'jpeg', 'png', 'gif');

			if (!in_array($imgextension, $authorized_types) && $imgextension)
			{
				$this->setError('<strong>' . JText::_('COM_ICAGENDA_NOT_AUTHORIZED_IMAGE_TYPE') . '</strong><br />'
								. JText::_('COM_ICAGENDA_FORM_FEATURE_MIMETYPE_ERROR'));

				return false;
			}
			elseif ($imgextension)
			{
				// Clean icon name
				jimport( 'joomla.filter.output' );
				$icon_name = JFilterOutput::stringURLSafe($imgtitle) . '.' . $imgextension;

				// Generate 16_bit if not exist
				iCThumbGet::thumbnail($array['new_icon'], $thumbsPath, '16_bit', '16', '16', '100', false, '', '', '', $icon_name);

				// Generate 24_bit if not exist
				iCThumbGet::thumbnail($array['new_icon'], $thumbsPath, '24_bit', '24', '24', '100', false, '', '', '', $icon_name);

				// Generate 32_bit if not exist
				iCThumbGet::thumbnail($array['new_icon'], $thumbsPath, '32_bit', '32', '32', '100', false, '', '', '', $icon_name);

				// Generate 48_bit if not exist
				iCThumbGet::thumbnail($array['new_icon'], $thumbsPath, '48_bit', '48', '48', '100', false, '', '', '', $icon_name);

				// Generate 64_bit if not exist
				iCThumbGet::thumbnail($array['new_icon'], $thumbsPath, '64_bit', '64', '64', '100', false, '', '', '', $icon_name);

				$array['icon'] = $icon_name;
			}
		}

		return parent::bind($array, $ignore);
	}

	/**
	 * Overloaded check function
	 * @since	3.4.0
	*/
	public function check()
	{
		// If there is an ordering column and this is a new row then get the next ordering value
		if (property_exists($this, 'ordering') && $this->id == 0)
		{
			$this->ordering = self::getNextOrder();
		}

		return parent::check();
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
		/** @var iCagendaTableFeature $table */
		$table = JTable::getInstance('Feature', 'iCagendaTable');

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
