<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Plugin Search
 *----------------------------------------------------------------------------
 * @version     2.5 2021-09-29
 *
 * @package     iCagenda.Plugin
 * @subpackage  Search.icagenda
 * @link        https://www.icagenda.com
 *
 * @author      Cyril Rezé
 * @copyright   (c) 2012-2024 Cyril Rezé / iCagenda. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 *
 *  This program is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation, either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @since       1.0
 *----------------------------------------------------------------------------
*/

defined('_JEXEC') or die;

use iCutilities\Menus\Menus as icagendaMenus;

// Require the component's router file
require_once JPATH_SITE . '/components/com_icagenda/router.php';

/**
 * iCagenda Search Plugin
 */
class PlgSearchiCagenda extends JPlugin
{
	/**
	 * Constructor
	 *
	 * @param   object  $subject  The object to observe
	 * @param   array   $config   An array that holds the plugin configuration
	 *
	 * @since   1.0
	 */
	public function __construct(& $subject, $config)
	{
		parent::__construct($subject, $config);

		$this->loadLanguage();
	}

	/**
	 * Determine areas searchable by this plugin.
	 *
	 * @return  array  An array of search areas.
	 *
	 * @since   1.0
	 */
	public function onContentSearchAreas()
	{
		$search_name = $this->params->get('search_name', JText::_('ICAGENDA_PLG_SEARCH_SECTION_EVENTS'));

		if ($search_name == 'ICAGENDA_PLG_SEARCH_SECTION_EVENTS') $search_name = 'Events';

		return array('icagenda' => $search_name);
	}

	/**
	 * Search iCagenda (events).
	 * The SQL must return the following fields that are used in a common display
	 * routine: href, title, section, created, text, browsernav.
	 *
	 * @param   string  $text      Target search string.
	 * @param   string  $phrase    Matching option (possible values: exact|any|all).  Default is "any".
	 * @param   string  $ordering  Ordering option (possible values: newest|oldest|popular|alpha|category). Default is "newest".
	 * @param   mixed   $areas     An array if the search it to be restricted to areas or null to search all areas.
	 *
	 * @return  array   Search results.
	 *
	 * @since   1.0
	 */
	public function onContentSearch($text, $phrase = '', $ordering = '', $areas = null)
	{
		$db     = JFactory::getDbo();
		$app    = JFactory::getApplication();
		$tag    = JFactory::getLanguage()->getTag();
		$user   = JFactory::getUser();
		$groups = implode(',', $user->getAuthorisedViewLevels());

		$menu   = $app->getMenu();

		// Load iCagenda Utilities
		JLoader::registerPrefix('icagenda', JPATH_ADMINISTRATOR . '/components/com_icagenda/utilities');

		// Itemid Request (automatic detection of the first iCagenda menu-link, by menuID)
		$iC_list_menus	= icagendaMenus::iClistMenuItemsInfo();

		// If the array is not correct, return it:
		if (is_array($areas) && ! array_intersect($areas, array_keys($this->onContentSearchAreas())))
		{
			return array();
		}

		// Now retrieve the plugin parameters
		$search_name    = $this->params->get('search_name', JText::_('ICAGENDA_PLG_SEARCH_SECTION_EVENTS'));
		$search_limit   = $this->params->get('search_limit', '50' );
		$search_target  = $this->params->get('search_target', '0' );

		if ($search_name == 'ICAGENDA_PLG_SEARCH_SECTION_EVENTS') $search_name = 'Events';

		// Use the PHP function trim to delete spaces in front of or at the back of the searching terms
		$text = trim($text);

		// Return Array when nothing was filled in.
		if ($text == '')
		{
			return array();
		}

		// Database part.
		$wheres = array();

		// Check if Falang installed
		$defaultSiteLang = JComponentHelper::getParams('com_languages')->get('site', 'en-GB');

		//$falang = defined('FALANG_PATH');
		$falang = is_a($db, 'JFalangDatabase');

		$falangSearch = ($falang && ($tag != $defaultSiteLang));

		switch ($phrase)
		{
			// Search exact
			case 'exact':
				$text    = $db->Quote('%' . $db->escape($text, true) . '%', false);
				$wheres2 = array();

				if ($falang && $falangSearch)
				{
					$wheres2[] = 'LOWER(f.value) LIKE ' . $text;
				}
				else
				{
					$wheres2[] = 'LOWER(e.title) LIKE ' . $text;
					$wheres2[] = 'LOWER(e.shortdesc) LIKE ' . $text;
					$wheres2[] = 'LOWER(e.desc) LIKE ' . $text;
					$wheres2[] = 'LOWER(e.metadesc) LIKE ' . $text;
					$wheres2[] = 'LOWER(e.place) LIKE ' . $text;
					$wheres2[] = 'LOWER(e.city) LIKE ' . $text;
					$wheres2[] = 'LOWER(e.country) LIKE ' . $text;
					$wheres2[] = 'LOWER(e.address) LIKE ' . $text;
					$wheres2[] = 'LOWER(c.title) LIKE ' . $text;
				}

				$where = '(' . implode( ') OR (', $wheres2 ) . ')';
				break;

			// Search all or any
			case 'all':
			case 'any':

			// Set default
			default:
				$words  = explode(' ', $text);
				$wheres = array();

				foreach ($words as $word)
				{
					$word    = $db->Quote('%' . $db->escape($word, true) . '%', false);
					$wheres2 = array();

					if ($falang && $falangSearch)
					{
						$wheres2[] = 'LOWER(f.value) LIKE ' . $word;
					}
					else
					{
						$wheres2[] = 'LOWER(e.title) LIKE ' . $word;
						$wheres2[] = 'LOWER(e.shortdesc) LIKE ' . $word;
						$wheres2[] = 'LOWER(e.desc) LIKE ' . $word;
						$wheres2[] = 'LOWER(e.metadesc) LIKE ' . $word;
						$wheres2[] = 'LOWER(e.place) LIKE ' . $word;
						$wheres2[] = 'LOWER(e.city) LIKE ' . $word;
						$wheres2[] = 'LOWER(e.country) LIKE ' . $word;
						$wheres2[] = 'LOWER(e.address) LIKE ' . $word;
						$wheres2[] = 'LOWER(c.title) LIKE ' . $word;
					}

					$wheres[] = implode( ' OR ', $wheres2 );
				}

				$where = '(' . implode(($phrase == 'all' ? ') AND (' : ') OR ('), $wheres) . ')';
				break;
		}

		// Ordering of the results
		switch ($ordering)
		{
			//Alphabetic, ascending
			case 'alpha':
				$order = 'e.title ASC';
				break;

			// Oldest first
			case 'oldest':
				$order = 'e.next ASC';
				break;

			// Popular first
			case 'popular':

			// Newest first
			case 'newest':
				$order = 'e.next DESC';
				break;

			// Category
			case 'category':
				$order = 'c.title ASC';
				break;

			// Default setting: alphabetic, ascending
			default:
				$order = 'e.title ASC';
		}

		// Section
		$section = $search_name;

		// List of Events menu Itemid Request
		$nb_menu        = count($iC_list_menus);
		$nolink         = ($nb_menu < 1);

		// Get User groups allowed to approve event submitted
		$userID         = $user->id;
		$userLevels     = $user->getAuthorisedViewLevels();
		$userGroups     = $user->getAuthorisedGroups();

		$groupid        = JComponentHelper::getParams('com_icagenda')->get('approvalGroups', array("8"));

		jimport('joomla.access.access');

		$adminUsersArray = array();

		foreach ($groupid as $gp)
		{
			$adminUsers      = JAccess::getUsersByGroup($gp, false);
			$adminUsersArray = array_merge($adminUsersArray, $adminUsers);
		}

		// The database query;
		$query  = $db->getQuery(true);
		$query->select(
			'e.title AS title, e.created AS created, e.next AS next, e.displaytime AS displaytime, e.alias AS alias, '
				. 'e.desc AS text, e.id AS eventID, '
				. 'c.id AS catid, c.title AS cattitle, e.language AS language, '
				. '"' . $search_target . '" AS browsernav'
		);

//		$query->select($query->concatenate(array($db->Quote($section), 'c.title'), " / ").' AS section');

		$query->from('#__icagenda_events AS e');
		$query->join('INNER', $db->quoteName('#__icagenda_category', 'c') . ' ON (' . $db->quoteName('c.id') . ' = ' . $db->quoteName('e.catid') . ')');

		$query->where('e.state = 1');
		$query->where('c.state = 1');
		$query->where('e.access IN (' . $groups . ')');

		if ($falang && $falangSearch)
		{
			$query->join('LEFT', $db->quoteName('#__falang_content', 'f') . ' ON (' . $db->quoteName('f.reference_id') . ' = ' . $db->quoteName('e.id') . ')');
			$query->join('LEFT', $db->quoteName('#__languages', 'l') . ' ON (' . $db->quoteName('f.language_id') . ' = ' . $db->quoteName('l.lang_id') . ')');
			$query->where('f.reference_table IN (' . $db->quote('icagenda_events') . ', ' . $db->quote('icagenda_category') . ')');
			$query->where('l.lang_code in (' . $db->quote($tag) . ',' . $db->quote('*') . ')');
		}

		// START Hack for Upcoming Filtering
//		$datetime_today	= JHtml::date('now', 'Y-m-d H:i'); // Joomla Time Zone

//		$query->where('e.next >= ' . $db->q($datetime_today));
		// END Hack for Upcoming Filtering

		$query->where('(' . $where . ')');


		// if user logged-in has no Approval Rights, not approved events won't be displayed.
		if ( ! in_array($userID, $adminUsersArray)
			&& ! in_array('8', $userGroups))
		{
			$query->where('e.approval <> 1');
		}

		// Filter by language.
		if ($app->isClient('site') && JLanguageMultilang::isEnabled())
		{
			$query->where('e.language in (' . $db->quote($tag) . ',' . $db->quote('*') . ')');
		}

		// Prevents duplicated results when search in Falang category translations
		$query->group('e.id');

		$query->order($order);

		// Set query
		$db->setQuery($query, 0, $search_limit);

		$iCevents = $db->loadObjectList();

		// The 'output' of the displayed link.
		if (isset($iCevents))
		{
			foreach($iCevents as $key => $iCevent)
			{
				$event_slug   = empty($iCevent->alias) ? $iCevent->eventID : $iCevent->eventID . ':' . $iCevent->alias;

				$date_next    = JHtml::date($iCevent->next, JText::_( 'DATE_FORMAT_LC3' ), null);
				$time_next    = JHtml::date($iCevent->next, 'H:i', null);

				$display_time = $iCevent->displaytime ? ' ' . $time_next : '';

				$linkid = icagendaMenus::thisEventItemid($iCevent->next, $iCevent->catid, $iC_list_menus);
				$linkid = $linkid ? $linkid : $menu->getDefault($tag)->id;

				$iCevents[$key]->title   = $iCevent->title . ' (' . $date_next . $display_time . ')';
				$iCevents[$key]->section = $section . ' / ' . $iCevent->cattitle;
				$iCevents[$key]->href    = 'index.php?option=com_icagenda&view=event&id=' . $event_slug . '&Itemid=' . $linkid;
			}
		}

		// If menu item iCagenda list of events exists, returns events found.
		if ($nolink)
		{
			// Displays a warning that no menu item to the list of events is published.
			$app->enqueueMessage(JText::_('ICAGENDA_PLG_SEARCH_ALERT_NO_ICAGENDA_MENUITEM'), 'warning');
		}
		else
		{
			//Return the search results in an array
			return $iCevents;
		}
	}
}
