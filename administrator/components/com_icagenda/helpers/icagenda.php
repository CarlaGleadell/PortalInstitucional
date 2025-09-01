<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.8.0 2021-09-27
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

/**
 * iCagenda helper.
 */
class iCagendaHelper
{
	/**
	 * Configure the Linkbar.
	 */
	public static function addSubmenu($submenu)
	{
		if (version_compare(JVERSION, '4.0', 'lt'))
		{
			JHtmlSidebar::addEntry(
				JText::_('COM_ICAGENDA_TITLE_ICAGENDA'),
				'index.php?option=com_icagenda&view=icagenda',
				$submenu == 'icagenda'
			);
			if (JFactory::getUser()->authorise('icagenda.access.categories', 'com_icagenda'))
			{
				JHtmlSidebar::addEntry(
					JText::_('COM_ICAGENDA_TITLE_CATEGORIES'),
					'index.php?option=com_icagenda&view=categories',
					$submenu == 'categories'
				);
			}
			if (JFactory::getUser()->authorise('icagenda.access.events', 'com_icagenda'))
			{
				JHtmlSidebar::addEntry(
					JText::_('COM_ICAGENDA_TITLE_EVENTS'),
					'index.php?option=com_icagenda&view=events',
					$submenu == 'events'
				);
			}
			if (JFactory::getUser()->authorise('icagenda.access.registrations', 'com_icagenda'))
			{
				JHtmlSidebar::addEntry(
					JText::_('COM_ICAGENDA_TITLE_REGISTRATION'),
					'index.php?option=com_icagenda&view=registrations',
					$submenu == 'registrations'
				);
			}
			if (JFactory::getUser()->authorise('icagenda.access.newsletter', 'com_icagenda'))
			{
				JHtmlSidebar::addEntry(
					JText::_('COM_ICAGENDA_TITLE_NEWSLETTER'),
					'index.php?option=com_icagenda&view=mail&layout=edit',
					$submenu == 'newsletter'
				);
			}
			if (JFactory::getUser()->authorise('icagenda.access.customfields', 'com_icagenda'))
			{
				JHtmlSidebar::addEntry(
					JText::_('COM_ICAGENDA_TITLE_CUSTOMFIELDS'),
					'index.php?option=com_icagenda&view=customfields',
					$submenu == 'customfields'
				);
			}
			if (JFactory::getUser()->authorise('icagenda.access.features', 'com_icagenda'))
			{
				JHtmlSidebar::addEntry(
					JText::_('COM_ICAGENDA_TITLE_FEATURES'),
					'index.php?option=com_icagenda&view=features',
					$submenu == 'features'
				);
			}
			if (JFactory::getUser()->authorise('icagenda.access.themes', 'com_icagenda'))
			{
				JHtmlSidebar::addEntry(
					JText::_('COM_ICAGENDA_THEMES'),
					'index.php?option=com_icagenda&view=themes',
					$submenu == 'themes'
				);
			}
			JHtmlSidebar::addEntry(
				JText::_('COM_ICAGENDA_INFO'),
				'index.php?option=com_icagenda&view=info',
				$submenu == 'info'
			);
		}
	}

	/**
	 * Gets a list of the actions that can be performed.
	 */
	public static function getActions($messageId = 0)
	{
		$user   = JFactory::getUser();
		$result = new JObject;

		if (empty($messageId))
		{
			$assetName = 'com_icagenda';
		}
		else
		{
			$assetName = 'com_icagenda.message.'.(int) $messageId;
		}

		$actions = array(
			'core.admin',
			'core.manage',
			'core.create',
			'core.edit',
			'core.delete',
			'core.edit.state',
			'core.edit.own',
			'icagenda.access.categories',
			'icagenda.access.events',
			'icagenda.access.registrations',
			'icagenda.access.newsletter',
			'icagenda.access.customfields',
			'icagenda.access.features',
			'icagenda.access.themes'
		);

		foreach ($actions as $action)
		{
			$result->set($action, $user->authorise($action, $assetName));
		}

		return $result;
	}

	/**
	 * Tests whether a string is serialized before attempting to unserialize it
	 *
	 * ( TO BE REMOVED WHEN ALL CALLS FROM IC LIBRARY !!! )
	 */
	public static function isSerialized($str)
	{
		return ($str == serialize(false) || @unserialize($str) !== false);
	}
}
