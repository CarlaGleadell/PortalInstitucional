<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.9.0 2024-02-20
 *
 * @package     iCagenda.Admin
 * @link        https://www.icagenda.com
 *
 * @author      Cyril Rezé
 * @copyright   (c) 2012-2024 Cyril Rezé / iCagenda. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 *
 * @since       2.1.4
 *----------------------------------------------------------------------------
*/

\defined('_JEXEC') or die;

class icagendaFormFieldEventsMenuItems extends JFormField
{
	protected $type = 'EventsMenuItems';

	protected function getInput()
	{
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);

		if (version_compare(JVERSION, '4.0', 'lt'))
		{
			$listURL = 'index.php?option=com_icagenda&view=list';
		}
		else
		{
			$listURL = 'index.php?option=com_icagenda&view=events';
		}

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

		$html.='<option value="">- ' . JTEXT::_('JGLOBAL_AUTO') . ' -</option>';

		foreach ($links as &$link)
		{
			if ($link->published == '1')
			{
				$html.='<option value="' . $link->id . '"';
				$html.= ($this->value == $link->id) ? ' selected="selected"' : '';
				$html.='>[' . $link->id . '] ' . $link->title . '</option>';
			}
		}

		$html.='</select>';

		return $html;
	}
}
