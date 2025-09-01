<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Plugin Installer
 *----------------------------------------------------------------------------
 * @version     2.1.0 2024-06-02
 *
 * @package     iCagenda.Plugin
 * @subpackage  Installer.icagenda
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
 * @since       iCagenda 3.6.13
 *----------------------------------------------------------------------------
*/

defined('_JEXEC') or die;

/**
 * Handle iCagenda extension update authorization
 */
class plgInstallerIcagenda extends JPlugin
{
	/**
	 * Handle adding credentials to package download request
	 *
	 * @param   string  $url      url from which package is going to be downloaded
	 * @param   array   $headers  headers to be sent along the download request (key => value format)
	 *
	 * @return  boolean           true if credentials have been added to request or not our business,
	 *                            false otherwise (credentials not set by user)
	 *
	 * @since   1.0
	 */
	public function onInstallerBeforePackageDownload(&$url, &$headers)
	{
		if (strpos($url, 'joomlic.com') === false)
		{
			return true;
		}

		// Get the component information from the #__extensions table
		JLoader::import('joomla.application.component.helper');
		$component  = JComponentHelper::getComponent('com_icagenda');
		$downloadId = $component->params->get('downloadid', '');

		if (empty($downloadId) && (strpos($url, 'pro.joomlic.com') !== false || strpos($url, 'icagenda-pro') !== false))
		{
			// Test if translation is missing, set to en-GB by default
			$language = JFactory::getLanguage();
			$language->load('plg_installer_icagenda', JPATH_ADMINISTRATOR, 'en-GB', true);
			$language->load('plg_installer_icagenda', JPATH_ADMINISTRATOR, null, true);

			$app = JFactory::getApplication();
			$app->enqueueMessage(JText::_('PLG_INSTALLER_ICAGENDA_DLID_NOTICE'), 'notice');
			$app->enqueueMessage(JText::sprintf('PLG_INSTALLER_ICAGENDA_AUTHORIZATION_WARNING', JText::_('PLG_INSTALLER_ICAGENDA_PRO_ID_NOT_FOUND')), 'warning');

			return true;
		}

		// Bind credentials to request by appending it to the download url
		if ( ! empty($downloadId) && (strpos($url, 'pro.joomlic.com') !== false || strpos($url, 'icagenda-pro') !== false))
		{
			$separator = strpos($url, '?') !== false ? '&' : '?';
			$url .= $separator . 'dlid=' . $downloadId;
		}

		return true;
	}
}
