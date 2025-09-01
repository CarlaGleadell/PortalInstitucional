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

use iClib\Thumb\Get as iCThumbGet;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Filter\OutputFilter;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Table\Table;
use Joomla\Database\DatabaseDriver;

/**
 * iCagenda Component Feature Table
 */
class FeatureTable extends Table
{
	protected $new_icon = null;

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
		parent::__construct('#__icagenda_feature', 'id', $db);

		// Set the alias for 'published' since the column is called 'state'
		$this->setColumnAlias('published', 'state');
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
		if (isset($array['new_icon']))
		{
			// Get media path
			$params_media  = ComponentHelper::getParams('com_media');
			$image_path    = $params_media->get('image_path', 'images');

			// Paths to feature icons folder
			$thumbsPath    = $image_path . '/icagenda/feature_icons';

			// Get Image File Infos
			$img = HTMLHelper::cleanImageURL($array['new_icon']);

			$link_image = $img->url;

			$decomposition = explode( '/' , $link_image );

			// in each parent
			$i = 0;

			while ( isset($decomposition[$i]) )
				$i++;
			$i--;

			$imgname      = $decomposition[$i];
			$fichier      = explode( '.', $decomposition[$i] );
			$imgtitle     = $fichier[0];
			$imgextension = strtolower($fichier[1]);

			// Check file type if authorized to be generated as feature icon
			$authorized_types = array('jpg', 'jpeg', 'png', 'gif');

			if ( ! in_array($imgextension, $authorized_types) && $imgextension)
			{
				$this->setError('<strong>' . Text::_('COM_ICAGENDA_NOT_AUTHORIZED_IMAGE_TYPE') . '</strong><br />'
								. Text::_('COM_ICAGENDA_FORM_FEATURE_MIMETYPE_ERROR'));

				return false;
			}
			elseif ($imgextension)
			{
				// Clean icon name
				$icon_name = OutputFilter::stringURLSafe($imgtitle) . '.' . $imgextension;

				// Generate 16_bit if not exist
				iCThumbGet::thumbnail($link_image, $thumbsPath, '16_bit', '16', '16', '100', false, '', '', '', $icon_name);

				// Generate 24_bit if not exist
				iCThumbGet::thumbnail($link_image, $thumbsPath, '24_bit', '24', '24', '100', false, '', '', '', $icon_name);

				// Generate 32_bit if not exist
				iCThumbGet::thumbnail($link_image, $thumbsPath, '32_bit', '32', '32', '100', false, '', '', '', $icon_name);

				// Generate 48_bit if not exist
				iCThumbGet::thumbnail($link_image, $thumbsPath, '48_bit', '48', '48', '100', false, '', '', '', $icon_name);

				// Generate 64_bit if not exist
				iCThumbGet::thumbnail($link_image, $thumbsPath, '64_bit', '64', '64', '100', false, '', '', '', $icon_name);

				$array['icon'] = $icon_name;
			}
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

		return parent::check();
	}
}
