<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.8.10 2022-11-02
 *
 * @package     iCagenda.Admin
 * @subpackage  src.Utilities.Menus
 * @link        https://www.icagenda.com
 *
 * @author      Cyril Rezé
 * @copyright   (c) 2012-2024 Cyril Rezé / iCagenda. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 *
 * @since       3.4
 *----------------------------------------------------------------------------
*/

namespace iCutilities\Menus;

\defined('_JEXEC') or die;

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Multilanguage;
use Joomla\Registry\Registry;

/**
 * class icagendaMenus
 */
class Menus
{
	/**
	 * Function to return all published 'List of Events' menu items
	 *
	 * @param   None
	 *
	 * @return  Array of menu item info this way : Itemid-mcatid-lang
	 */
	public static function iClistMenuItemsInfo()
	{
		$app = Factory::getApplication();

		$options_time = ComponentHelper::getParams('com_icagenda')->get('time', '0');

		if (version_compare(JVERSION, '4.0', 'lt'))
		{
			$listURL = 'index.php?option=com_icagenda&view=list';
		}
		else
		{
			$listURL = 'index.php?option=com_icagenda&view=events';
		}

		// List all menu items linking to list of events
		$db    = Factory::getDbo();
		$query = $db->getQuery(true);

		$query
			->select($db->quoteName(array('id', 'title', 'link', 'published', 'client_id', 'params', 'language')))
			->from($db->quoteName('#__menu'))
			->where($db->quoteName('link') . ' = ' . $db->quote($listURL))
			->where($db->quoteName('published') . ' > 0')
			->where($db->quoteName('client_id') . ' = 0');

		if (Multilanguage::isEnabled())
		{
			$query->where('language in (' . $db->quote(Factory::getLanguage()->getTag()) . ',' . $db->quote('*') . ')');
		}

		$db->setQuery($query);

		$link = $db->loadObjectList();

		$iC_list_menus = array();

		foreach ($link as $iClistMenu)
		{
			$menuitemid	= $iClistMenu->id;

			if ($menuitemid)
			{
//				$menu       = $app->getMenu();
//				$menuparams = $menu->getParams($menuitemid);
				$Registry   = new Registry();
				$menuparams = $Registry->loadString($iClistMenu->params);

				$mcatid     = $menuparams->get('mcatid', '');
				$menufilter = ($menuparams->get('time', '') != '')
							? $menuparams->get('time')
							: $options_time;

				if (is_array($mcatid))
				{
					$mcatid = implode(',', $mcatid);
				}

				array_push($iC_list_menus, $menuitemid . '_' . $mcatid . '_' . $menufilter);
			}
		}

		return $iC_list_menus;
	}

	/**
	 * Function to return all published 'List of Events' menu items
	 *
	 * @param   None
	 *
	 * @return  Array of menu item info this way : Itemid-mcatid-lang
	 */
	public static function iClistMenuItems()
	{
		$app = Factory::getApplication();

		if (version_compare(JVERSION, '4.0', 'lt'))
		{
			$listURL = 'index.php?option=com_icagenda&view=list';
		}
		else
		{
			$listURL = 'index.php?option=com_icagenda&view=events';
		}

		// List all menu items linking to list of events
		$db    = Factory::getDbo();
		$query = $db->getQuery(true);

		$query
			->select($db->quoteName(array('id', 'title', 'link', 'published', 'client_id', 'params', 'language')))
			->from($db->quoteName('#__menu'))
			->where($db->quoteName('link') . ' = ' . $db->quote($listURL))
			->where($db->quoteName('published') . ' > 0')
			->where($db->quoteName('client_id') . ' = 0');

		if (Multilanguage::isEnabled())
		{
			$query->where('language in (' . $db->quote(Factory::getLanguage()->getTag()) . ',' . $db->quote('*') . ')');
		}

		$query->order('id ASC');

		$db->setQuery($query);

		$iC_list_menu_items = $db->loadObjectList();

		if ($iC_list_menu_items)
		{
			return $iC_list_menu_items;
		}
		else
		{
			return array();
		}
	}

	/**
	 * Function to return menu Itemid to display an event
	 *
	 * @return  menu Itemid
	 *
	 * @since   3.5.7
	 */
	public static function thisEventItemid($date, $category, $array_menuitems = null)
	{
		$iC_list_menus = $array_menuitems ? $array_menuitems : self::iClistMenuItemsInfo();

		$datetime_today = HTMLHelper::date('now', 'Y-m-d H:i');
		$date_today     = HTMLHelper::date('now', 'Y-m-d');

		// set menu link for each event (itemID) depending of category and/or language
		$onecat   = $multicat   = '0';
		$link_one = $link_multi = '';

		$menu_IDs_category = array();
		$menu_IDs_all      = array();
		$itemID_is_set     = 0;

		foreach ($iC_list_menus as &$iCm)
		{
			$value         = explode('_', $iCm);
			$iCmenu_id     = $value['0'];
			$iCmenu_mcatid = $value['1'];
			$iCmenu_filter = $value['2'];

			$iCmenu_mcatid_array = ! is_array($iCmenu_mcatid) ? explode(',', $iCmenu_mcatid) : array();

			// Menu can display past events
			if ($iCmenu_filter == 2
				&& strtotime($date) < strtotime($datetime_today)
				&& ! $itemID_is_set)
			{
				// If menu category filter is set, and item category is in filtered categories
				if (in_array($category, $iCmenu_mcatid_array))
				{
					$menu_IDs_category[] = $iCmenu_id;
					$itemID_is_set = $itemID_is_set + 1;
				}
				elseif ( ! $iCmenu_mcatid)
				{
					$menu_IDs_all[] = $iCmenu_id;
				}
			}

			// Menu can display today's events
			elseif ($iCmenu_filter == 4
				&& strtotime($date) > strtotime($date_today)
				&& strtotime($date) < strtotime("+1 DAY", strtotime($date_today))
				&& ! $itemID_is_set)
			{
				// If menu category filter is set, and item category is in filtered categories
				if (in_array($category, $iCmenu_mcatid_array))
				{
					$menu_IDs_category[] = $iCmenu_id;
					$itemID_is_set = $itemID_is_set + 1;
				}
				elseif ( ! $iCmenu_mcatid)
				{
					$menu_IDs_all[] = $iCmenu_id;
				}
			}

			// Menu can display today's events and upcoming events
			elseif ($iCmenu_filter == 1
				&& strtotime($date) >= strtotime($date_today)
				&& ! $itemID_is_set)
			{
				// If menu category filter is set, and item category is in filtered categories
				if (in_array($category, $iCmenu_mcatid_array))
				{
					$menu_IDs_category[] = $iCmenu_id;
					$itemID_is_set = $itemID_is_set + 1;
				}
				elseif ( ! $iCmenu_mcatid)
				{
					$menu_IDs_all[] = $iCmenu_id;
				}
			}

			// Menu can display upcoming events
			elseif ($iCmenu_filter == 3
				&& strtotime($date) > strtotime($datetime_today)
				&& ! $itemID_is_set)
			{
				// If menu category filter is set, and item category is in filtered categories
				if (in_array($category, $iCmenu_mcatid_array))
				{
					$menu_IDs_category[] = $iCmenu_id;
					$itemID_is_set = $itemID_is_set + 1;
				}
				elseif ( ! $iCmenu_mcatid)
				{
					$menu_IDs_all[] = $iCmenu_id;
				}
			}

			// Menu can display all events
			elseif ($iCmenu_filter == '0'
				&&  ! $itemID_is_set)
			{
				// If menu category filter is set, and item category is in filtered categories
				if (in_array($category, $iCmenu_mcatid_array))
				{
					$menu_IDs_category[] = $iCmenu_id;
					$itemID_is_set = $itemID_is_set + 1;
				}
				elseif ( ! $iCmenu_mcatid)
				{
					$menu_IDs_all[] = $iCmenu_id;
				}
			}

			if ($iCmenu_mcatid)
			{
				$nb_cat_filter = count($iCmenu_mcatid_array);

				for ($i = $category; in_array($i, $iCmenu_mcatid_array); $i++)
				{
					if ($nb_cat_filter == 1)
					{
						$link_one = $iCmenu_id;
					}
					elseif ($nb_cat_filter > 1)
					{
						$link_multi = $iCmenu_id;
					}
				}
			}
		}

		if (\count($menu_IDs_category) > 0)
		{
			if ($link_one)
			{
				$linkid = $link_one;
			}
			elseif ($link_multi)
			{
				$linkid = $link_multi;
			}
			else
			{
				$linkid = $menu_IDs_category[0];
			}
		}
		elseif (\count($menu_IDs_all) > 0)
		{
			$linkid = $menu_IDs_all[0];
		}
		else
		{
			//$app = Factory::getApplication();
			//$sitemenu = $app->getMenu('site');
			//$defaultMenuitem = $sitemenu->getDefault();
			//$linkid = (int) $defaultMenuitem->id;
			$linkid = 0;
		}

		return $linkid;
	}

	/**
	 * Function to return menu Itemid to display an event
	 *
	 * @return  Menu Itemid
	 *
	 * @since   3.6.4
	 */
	public static function displayEventItemid($Itemid, $event_filters)
	{
		$menu_filters   = self::getFiltersMenuItem($Itemid);
		$datetime_today = HTMLHelper::date('now', 'Y-m-d H:i');
		$date_today     = HTMLHelper::date('now', 'Y-m-d');
		$linkid         = null;

		$iCmenu_id      = $Itemid;
		$iCmenu_time    = $menu_filters['time'];
		$iCmenu_mcatid  = ! is_array($menu_filters['mcatid'])
						? explode(',', $menu_filters['mcatid'])
						: $menu_filters['mcatid'];

		$event_date     = $event_filters['date'];
		$event_category = $event_filters['catid'];

		// Language and Access not currently used as items already filtered in frontend
		$event_language = $event_filters['language'];
		$event_access   = $event_filters['access'];

		// If menu category filter is set, and item category is in filtered categories OR No category filter (display all)
		if (in_array($event_category, $iCmenu_mcatid)
			|| in_array('', $iCmenu_mcatid))
		{
			// Menu can display past events
			if ($iCmenu_time == 2
				&& strtotime($event_date) < strtotime($datetime_today)
				)
			{
				$linkid = $iCmenu_id;
			}

			// Menu can display today's events
			elseif ($iCmenu_time == 4
				&& strtotime($event_date) > strtotime($date_today)
				&& strtotime($event_date) < strtotime("+1 DAY", strtotime($date_today))
				)
			{
				$linkid = $iCmenu_id;
			}

			// Menu can display today's events and upcoming events
			elseif ($iCmenu_time == 1
				&& strtotime($event_date) >= strtotime($date_today)
				)
			{
				$linkid = $iCmenu_id;
			}

			// Menu can display upcoming events
			elseif ($iCmenu_time == 3
				&& strtotime($event_date) > strtotime($datetime_today)
				)
			{
				$linkid = $iCmenu_id;
			}

			// Menu can display all events
			elseif ($iCmenu_time == '0')
			{
				$linkid = $iCmenu_id;
			}
		}

		return $linkid;
	}

	/**
	 * Function to return filtering settings of one menu item
	 *
	 * @param   None
	 *
	 * @return  Array of menu item info this way : Itemid-mcatid-lang
	 *
	 * @since   3.6.4
	 */
	public static function getFiltersMenuItem($Itemid)
	{
		if ($Itemid)
		{
			$app            = Factory::getApplication();
			$menu           = $app->getMenu();
			$menuItem       = $menu->getItem($Itemid);
			$menuParams     = $menu->getParams($Itemid);

			$iCparams_time  = ComponentHelper::getParams('com_icagenda')->get('time', '0');

			$mcatid         = $menuParams->get('mcatid', '');
			$time           = ($menuParams->get('time', '') != '')
							? $menuParams->get('time')
							: $iCparams_time;
			$language       = $menuItem->language;
			$access         = $menuItem->access;

			$ItemidFilters = array(
				'mcatid'   => $mcatid,
				'time'     => $time,
				'language' => $language,
				'access'   => $access,
			);

			return $ItemidFilters;
		}

		return false;
	}
}
