<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.9.0 2024-02-20
 *
 * @package     iCagenda.Admin
 * @subpackage  Utilities.Field.Icagenda
 * @link        https://www.icagenda.com
 *
 * @author      Cyril Rezé
 * @copyright   (c) 2012-2024 Cyril Rezé / iCagenda. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 *
 * @since       2.1.4
 *----------------------------------------------------------------------------
*/

namespace iCutilities\Field\Icagenda;

use Joomla\CMS\Factory;
use Joomla\CMS\Form\FormField;
use Joomla\CMS\Language\Text;

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

/**
 * Events Menu Items Form Field class for iCagenda.
 * Select list of menu items of type "List of events".
 */
class EventsMenuItemsField extends FormField
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 */
	protected $type = 'EventsMenuItems';

	/**
	 * Method to get the field input markup for a media upload.
	 *
	 * @return  string  The field input markup.
	 */
	protected function getInput()
	{
		$listURL = 'index.php?option=com_icagenda&view=events';

		$db = Factory::getDbo();

		$query = $db->getQuery(true);

		$query
			->select($db->quoteName(array('id', 'title', 'link', 'published', 'client_id')))
			->from($db->quoteName('#__menu'))
			->where($db->quoteName('link') . ' = ' . $db->quote($listURL))
			->where($db->quoteName('published') . ' > 0')
			->where($db->quoteName('client_id') . ' = 0');

		$db->setQuery($query);

		$links = $db->loadObjectList();

		$class = isset($this->class) ? ' class="' . $this->class . '"' : '';

		$html = '<select id="' . $this->id . '_id"' . $class . ' name="' . $this->name . '">';

		$html.='<option value="">- ' . Text::_('JGLOBAL_AUTO') . ' -</option>';

		foreach ($links as &$link) {
			if ($link->published == '1') {
				$html.='<option value="' . $link->id . '"';
				$html.= ($this->value == $link->id) ? ' selected="selected"' : '';
				$html.='>[' . $link->id . '] ' . $link->title . '</option>';
			}
		}

		$html.='</select>';

		return $html;
	}
}
