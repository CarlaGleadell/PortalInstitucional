<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Plugin Installer - iCagenda
 *----------------------------------------------------------------------------
 * @version     2.1.0 2024-06-02
 *
 * @package     iCagenda
 * @subpackage  Plugin.Installer.Icagenda
 * @link        https://www.icagenda.com
 *
 * @author      Cyril Rezé
 * @copyright   (c) 2013-2024 Cyril Rezé / iCagenda. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 *
 * @since       iCagenda 3.6.13
 *----------------------------------------------------------------------------
*/

namespace W3biC\Plugin\Installer\Icagenda\Extension;

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Plugin\CMSPlugin;
use Joomla\Database\DatabaseAwareTrait;

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

/**
 * Installer Icagenda Plugin.
 */
final class Icagenda extends CMSPlugin
{
	use DatabaseAwareTrait;

	/**
	 * Load the language file on instantiation.
	 *
	 * @var boolean
	 */
	protected $autoloadLanguage = true;

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
		if (strpos($url, 'joomlic.com') === false) {
			return true;
		}

		// Get the component information from the #__extensions table
		$component  = ComponentHelper::getComponent('com_icagenda');
		$downloadId = $component->params->get('downloadid', '');

		if (empty($downloadId)
			&& (strpos($url, 'pro.joomlic.com') !== false || strpos($url, 'icagenda-pro') !== false)
		) {
			// Test if translation is missing, set to en-GB by default
			$language = Factory::getLanguage();
			$language->load('plg_installer_icagenda', JPATH_ADMINISTRATOR, 'en-GB', true);
			$language->load('plg_installer_icagenda', JPATH_ADMINISTRATOR, null, true);

			$app = $this->getApplication();
			$app->enqueueMessage(Text::_('PLG_INSTALLER_ICAGENDA_DLID_NOTICE'), 'notice');
			$app->enqueueMessage(Text::sprintf('PLG_INSTALLER_ICAGENDA_AUTHORIZATION_WARNING', Text::_('PLG_INSTALLER_ICAGENDA_PRO_ID_NOT_FOUND')), 'warning');

			return true;
		}

		// Bind credentials to request by appending it to the download url
		if ( ! empty($downloadId)
			&& (strpos($url, 'pro.joomlic.com') !== false || strpos($url, 'icagenda-pro') !== false)
		) {
			$separator = strpos($url, '?') !== false ? '&' : '?';
			$url .= $separator . 'dlid=' . $downloadId;
		}

		return true;
	}
}
