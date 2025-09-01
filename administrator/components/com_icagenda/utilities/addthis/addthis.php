<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.8.18 2023-06-05
 *
 * @package     iCagenda.Admin
 * @link        https://www.icagenda.com
 *
 * @author      Cyril Rezé
 * @copyright   (c) 2012-2024 Cyril Rezé / iCagenda. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 *
 * @since       3.6.0
 * @deprecated  3.8.18 - removed 4.0
 *----------------------------------------------------------------------------
*/

defined('_JEXEC') or die;

/**
 * ADDTHIS - Social Networks
 * 
 * deprecated 3.8.18
 * removed 4.0
 */
class icagendaAddthis
{
	/*
	 * Function to display sharing on social networks
	 *
	 * VIEW: Event Details
	 * 
	 * deprecated 3.8.18
	 * removed 4.0
	 */
	static public function sharing($item)
	{
		$addthisEvent = $item->params->get('atevent', 1);

		if ($addthisEvent == 1)
		{
			return self::share();
		}

		return false;
	}

	/*
	 * Function AddThis social networks sharing
	 * 
	 * deprecated 3.8.18
	 * removed 4.0
	 */
	static public function share()
	{
		$params = JComponentHelper::getParams('com_icagenda');

		$addthis_removal = $params->get('addthis_removal', '');

		if (!$addthis_removal) {
			JLog::add('Oracle has made the business decision to terminate all AddThis services effective as of May 31, 2023. All code associated with AddThis is therefore removed.', JLog::WARNING, 'deprecated');

			$user = JFactory::getUser();

			$language = JFactory::getLanguage();
			$language->load('com_icagenda', JPATH_ADMINISTRATOR, 'en-GB', true);
			$language->load('com_icagenda', JPATH_ADMINISTRATOR, null, true);

			if ($user->authorise('core.admin', 'com_icagenda')) {
				JFactory::getApplication()->enqueueMessage('<h2>' . JText::_('COM_ICAGENDA_MSG_ADDTHIS_TERMINATION_TITLE') . '</h2>'
					. '<p><span class="icon icon-warning"></span> <strong>' . JText::_('COM_ICAGENDA_MSG_ADMIN_NOTICE') . '</strong></p>'
					. '<p>' . JText::sprintf('COM_ICAGENDA_MSG_ADDTHIS_TERMINATION_STATEMENT', '<a href="https://www.addthis.com/" target="_blank" rel="noopener">' . JText::_('IC_READMORE') . '</a>') . '</p>'
					. '<p>' . JText::_('COM_ICAGENDA_MSG_ADDTHIS_TERMINATION_RESULT') . '</p>'
					. '<p>' . JText::sprintf('COM_ICAGENDA_MSG_ADDTHIS_TERMINATION_SOLUTIONS', '<a href="https://extensions.joomla.org/category/social-web/social-share/" target="_blank" rel="noopener">JED</a>') . '</p>'
					. '<p>' . JText::_('COM_ICAGENDA_MSG_ADDTHIS_TERMINATION_SORRY') . '</p>'
					. '<p><small><strong>' . JText::_('COM_ICAGENDA_MSG_ADMIN_FRONTEND_HIDE') . '</strong></small></p>'
					, 'warning');
			}
		}

		return;
	}
}
