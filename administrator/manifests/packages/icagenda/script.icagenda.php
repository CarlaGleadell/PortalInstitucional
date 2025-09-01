<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.9.8 2024-12-20
 *
 * @package     iCagenda
 * @link        https://www.joomlic.com
 *
 * @author      Cyril Reze
 * @copyright   (c) 2012-2024 Cyril Reze / JoomliC - All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 *
 * @since       1.0
 *----------------------------------------------------------------------------
*/

\defined('_JEXEC') or die;

use Joomla\CMS\Application\ApplicationHelper as JApplicationHelper;
use Joomla\CMS\Cache\Cache as JCache;
use Joomla\CMS\Component\ComponentHelper as JComponentHelper;
use Joomla\CMS\Factory as JFactory;
use Joomla\CMS\Filesystem\File as JFile;
use Joomla\CMS\Filesystem\Folder as JFolder;
use Joomla\CMS\Installer\Installer as JInstaller;
use Joomla\CMS\Installer\InstallerHelper as JInstallerHelper;
use Joomla\CMS\Language\Text as JText;
use Joomla\CMS\Log\Log as JLog;

#[AllowDynamicProperties]
class Pkg_iCagendaInstallerScript
{
	private $ictype = 'core';

	/**
	 * The name of our package, e.g. pkg_example. Used for dependency tracking.
	 *
	 * @var  string
	 */
	protected $packageName = 'pkg_icagenda';

	/**
	 * The name of our component, e.g. com_example. Used for dependency tracking.
	 *
	 * @var  string
	 */
	protected $componentName = 'com_icagenda';

	/**
	 * The minimum PHP version required to install this extension
	 *
	 * @var   string
	 */
	protected $minimumPHPVersion = '7.2.0';

	/**
	 * The minimum Joomla! version required to install this extension
	 *
	 * @var   string
	 */
	protected $minimumJoomlaVersion = '3.10.0';

	/**
	 * The maximum Joomla! version this extension can be installed on
	 *
	 * @var   string
	 */
	protected $maximumJoomlaVersion = '5.3.99';

	/**
	 * A list of extensions (modules, plugins) to enable after installation. Each item has four values, in this order:
	 * type (plugin, module, ...), name (of the extension), client (0=site, 1=admin), group (for plugins).
	 *
	 * @var array
	 */
	protected $extensionsToEnable = [
		// Actionlog plugins
		['plugin', 'icagenda', 1, 'actionlog'],

		// Privacy plugins
		['plugin', 'icagenda', 1, 'privacy'],

		// Quick Icon plugins
		['plugin', 'icagendaupdate', 1, 'quickicon'],

		// Search plugins
		['plugin', 'icagenda', 1, 'search'],

		// System plugins
		['plugin', 'ic_autologin', 1, 'system'],
		['plugin', 'ic_library', 1, 'system'],
		['plugin', 'icagenda', 1, 'system'],
	];

	/**
	 * Like above, but enable these extensions on installation OR update. Use this sparingly. It overrides the
	 * preferences of the user. Ideally, this should only be used for installer plugins.
	 *
	 * @var array
	 */
	protected $extensionsToAlwaysEnable = [
		['plugin', 'icagenda', 1, 'installer'],
	];

	/**
	 * A list of extensions (library, modules, plugins) installed in this package. Each item has five values, in this order:
	 * type (plugin, module, ...), element (of the extension), client (0=site, 1=admin), group (for plugins), name (of the extension).
	 *
	 * @var array
	 */
	protected $packageExtensions = [

		// Component
		['component', 'com_icagenda', 1, '', 'iCagenda'],

		// Library
		['library', 'ic_library', 0, '', 'iC Library'],

		// Modules
		['module', 'mod_iccalendar', 0, '', 'iCagenda - Calendar'],

		// Plugins
		['plugin', 'icagenda', 1, 'actionlog', 'Action Log - iCagenda'],
		['plugin', 'icagenda', 1, 'installer', 'Installer - iCagenda'],
		['plugin', 'icagenda', 1, 'privacy', 'Privacy - iCagenda'],
		['plugin', 'icagendaupdate', 1, 'quickicon', 'Quick Icon - iCagenda :: Update Notification'],
		['plugin', 'icagenda', 1, 'search', 'Search - iCagenda'],
		['plugin', 'ic_autologin', 1, 'system', 'System - iCagenda :: Autologin'],
		['plugin', 'ic_library', 1, 'system', 'System - iC Library'],
		['plugin', 'icagenda', 1, 'system', 'System - iCagenda'],
	];

	protected $installedExtensions = [];

	/**
	 * Joomla! pre-flight event. This runs before Joomla! installs or updates the package. This is our last chance to
	 * tell Joomla! if it should abort the installation.
	 *
	 * @param   string                     $type    Installation type (install, update, discover_install)
	 * @param   \JInstallerAdapterPackage  $parent  Parent object
	 *
	 * @return  boolean  True to let the installation proceed, false to halt the installation
	 */
	public function preflight($type, $parent)
	{
		// Do not run on uninstall.
		if ($type === 'uninstall') {
			return true;
		}

		// Check the minimum PHP version
		if ( ! version_compare(PHP_VERSION, $this->minimumPHPVersion, 'ge')) {
			$msg = '<p><strong>' . JText::sprintf('PKG_ICAGENDA_WARNING_MINIMUM_PHP', $this->minimumPHPVersion) . '</strong></p>';
			JLog::add($msg, JLog::WARNING, 'jerror');

			return false;
		}

		// Check the minimum Joomla! version
		if ( ! version_compare(JVERSION, $this->minimumJoomlaVersion, 'ge')) {
			$msg = '<p><strong>' . JText::sprintf('PKG_ICAGENDA_WARNING_MINIMUM_JOOMLA', $this->minimumJoomlaVersion) . '</strong></p>';
			JLog::add($msg, JLog::WARNING, 'jerror');

			return false;
		}

		// Check the maximum Joomla! version
		if ( ! version_compare(JVERSION, $this->maximumJoomlaVersion, 'le')) {
			$msg = '<p><strong>' . JText::sprintf('PKG_ICAGENDA_WARNING_MAXIMUM_JOOMLA', $this->maximumJoomlaVersion) . '</strong></p>';
			JLog::add($msg, JLog::WARNING, 'jerror');

			return false;
		}

		// HHVM made sense in 2013, now PHP 7 is a way better solution than an hybrid PHP interpreter
		if (\defined('HHVM_VERSION')) {
			$minPHP = '7';
			$msg = '<p><strong>' . JText::sprintf('PKG_ICAGENDA_WARNING_HHVM', $minPHP) . '</strong></p>';
			JLog::add($msg, JLog::WARNING, 'jerror');

			return false;
		}

		$this->checkInstalled();

		return true;
	}

	/**
	 * Runs after install, update or discover_update. In other words, it executes after Joomla! has finished installing
	 * or updating your component. This is the last chance you've got to perform any additional installations, clean-up,
	 * database updates and similar housekeeping functions.
	 *
	 * @param   string                       $type    install, update or discover_update
	 * @param   \JInstallerAdapterComponent  $parent  Parent object
	 */
	public function postflight($type, $parent)
	{
		// Do not run on uninstall.
		if ($type === 'uninstall') {
			return;
		}

		// Always enable these extensions
		if (isset($this->extensionsToAlwaysEnable) && !empty($this->extensionsToAlwaysEnable)) {
			$this->enableExtensions($this->extensionsToAlwaysEnable);
		}

		/**
		 * Clean the cache after installing the package.
		 *
		 * See bug report https://github.com/joomla/joomla-cms/issues/16147
		 */
		$conf         = JFactory::getConfig();
		$clearGroups  = ['_system', 'com_modules', 'mod_menu', 'com_plugins', 'com_modules'];
		$cacheClients = [0, 1];

		foreach ($clearGroups as $group) {
			foreach ($cacheClients as $client_id) {
				try {
					$options = [
						'defaultgroup' => $group,
						'cachebase'    => ($client_id) ? JPATH_ADMINISTRATOR . '/cache' : $conf->get('cache_path', JPATH_SITE . '/cache'),
					];

					/** @var JCache $cache */
					$cache = JCache::getInstance('callback', $options);
					$cache->clean();
				} catch (\Exception $exception) {
					$options['result'] = false;
				}

				// Trigger the onContentCleanCache event.
				try {
					JFactory::getApplication()->triggerEvent('onContentCleanCache', $options);
				} catch (\Exception $e) {
					// Nothing
				}
			}
		}

		// Get manifest file version
		$this->release = $parent->getManifest()->version;

		echo '<div style="background: var(--body-bg); padding: 2rem; border-radius: .2rem;">';

		echo '<p>';
		echo '<img src="../media/com_icagenda/images/iconicagenda48.png" />';

		if ($this->ictype != 'core') echo '<span class="text-body-secondary" style="font-weight: bold; font-size: 1.25rem;"> ' . strtoupper($this->ictype) . '</span>';

		echo '<br />';
		echo '<span style="font-size: 16px; color: #555; margin-left: 70px;">' . JText::_('PKG_ICAGENDA_EXTENSION_DESCRIPTION') . '</span>';
		echo '</p>';

		echo '<hr />';

		echo '<p>';
		echo '<div style="float: left; margin-right: 30px;">';
		echo '<img src="../media/com_icagenda/images/logo_icagenda.png" />';
		echo '</div>';

		echo '<span style="letter-spacing: 1px; font-size: 1rem">'
			. JText::_('COM_ICAGENDA_WELCOME')
			. '</span>'
			. '<br />';

		if ($type == 'install') {
			echo '<span style="text-transform:uppercase; font-size: .875rem; font-weight: bold;">'
				. JText::sprintf('COM_ICAGENDA_WELCOME_1', '<strong>iCagenda</strong>') . ' ' . $this->release
				. ' ' . JText::_('COM_ICAGENDA_WELCOME_2') . '</span>'
				. '<br /><br />';
		}

		// Extension Update
		if ($type == 'update') {
			echo '<span style="font-size: 1rem; font-weight: bold;">'
				. JText::sprintf('PKG_ICAGENDA_UPDATED_TO_VERSION', 'iCagenda', $this->release)
				. '</span>'
				. '<br /><br />';
		}

		echo '<div class="small">';
		echo JText::_('COM_ICAGENDA_FEATURES_BACKEND') . '<br />';
		echo JText::_('COM_ICAGENDA_FEATURES_FRONTEND');
		echo '</div>';

		echo '</p>';

		echo '<div style="clear: both"></div>';

		echo '<hr />';


		$translationPacks =  [
			'af'    => 'Afrikaans (South Africa)',
			'ar'    => 'Arabic (Unitag)',
			'eu_es' => 'Basque (Spain)',
			'bg'    => 'Bulgarian (Bulgaria)',
			'ca'    => 'Catalan (Spain)',
			'zh'    => 'Chinese (China)',
			'tw'    => 'Chinese (Taiwan)',
			'hr'    => 'Croatian (Croatia)',
			'cz'    => 'Czech (Czech Republic)',
			'dk'    => 'Danish (Denmark)',
			'nl'    => 'Dutch (Netherlands)',
			'en'    => 'English (United Kingdom)',
			'us'    => 'English (United States)',
			'eo'    => 'Esperanto',
			'et'    => 'Estonian (Estonia)',
			'fi'    => 'Finnish (Finland)',
			'fr'    => 'French (France)',
			'gl'    => 'Galician (Spain)',
			'de'    => 'German (Germany)',
			'el'    => 'Greek (Greece)',
			'hu'    => 'Hungarian (Hungary)',
			'it'    => 'Italian (Italy)',
			'ja'    => 'Japanese (Japan)',
			'lv'    => 'Latvian (Latvia)',
			'lt'    => 'Lithuanian (Lithuania)',
			'none'  => 'Luxembourgish (Luxembourg)',
			'mk'    => 'Macedonian (Macedonia)',
			'no'    => 'Norwegian Bokmål (Norway)',
			'fa_ir' => 'Persian (Iran)',
			'pl'    => 'Polish (Poland)',
			'pt_br' => 'Portuguese (Brazil)',
			'pt'    => 'Portuguese (Portugal)',
			'ro'    => 'Romanian (Romania)',
			'ru'    => 'Russian (Russia)',
			'sr'    => 'Serbian (latin)',
			'sk'    => 'Slovak (Slovakia)',
			'sl'    => 'Slovenian (Slovenia)',
			'es'    => 'Spanish (Spain)',
			'sv'    => 'Swedish (Sweden)',
			'th'    => 'Thai (Thailand)',
			'tr'    => 'Turkish (Turkey)',
			'uk'    => 'Ukrainian (Ukraine)',
		];

		echo '<div class="text-body-secondary" style="font-size: 1.125rem; font-weight: bold; margin-bottom: 10px;">'
			. JText::sprintf('COM_ICAGENDA_FEATURES_TRANSLATION_PACKS', \count($translationPacks))
			. '</div>';

		echo '<p>';

		foreach ($translationPacks as $code => $lang) {
			$flagIcon = ($code == 'none') ? 'icon-16-language.png' : $code . '.gif';

			echo '<span rel="tooltip" data-placement="top" class="editlinktip hasTip" style="margin: 2px;" title="' . $lang . '">'
				. '<img src="../media/mod_languages/images/' . $flagIcon . '" border="0" alt="Tooltip"/>'
				. '</span>';
		}

		echo '<br /><br />';
		echo '<a href="https://www.icagenda.com/resources/translations" target="_blank" class="btn" style="color: #2a69b8;">'
			. JText::_('COM_ICAGENDA_TRANSLATION_PACKS_DONWLOAD')
			. '</a>';

		echo '</p>';

		echo '<hr />';

		echo '<div class="text-body-secondary" style="font-size: 1.125rem; font-weight: bold; margin-bottom: 10px;">'
			. JText::_('COM_ICAGENDA_INSTALL_LABEL')
			. '</div>';

		// Load language
		JFactory::getLanguage()->load('com_installer', JPATH_ADMINISTRATOR);

		if ($type == 'install') {
			echo '<div><i>' . JText::_('JTOOLBAR_INSTALL') . '</i></div>';
		} elseif ($type == 'update') {
			echo '<div><i>' . JText::_('COM_INSTALLER_TOOLBAR_UPDATE') . '</i></div>';
		}

		$this->postMessages();

		$this->createFolders();

		// Remove Obsolete Update Sites
		// When switching from/to Core/Pro
		// If current version installed before update older than 3.9.4 (new update site)
		$icagendaParams = JComponentHelper::getParams('com_icagenda');
		$currentRelease = $icagendaParams->get('release', '');

		if ($icagendaParams->get('icsys') !== $this->ictype || version_compare($currentRelease, '3.9.3', 'le')) {
			// Get com_icagenda extension_id
			$db = JFactory::getDbo();

			$query = $db->getQuery(true);

			$query->select('extension_id')
				->from('#__extensions')
				->where('element = "pkg_icagenda"');
			$db->setQuery($query);

			$eid = $db->loadResult();

			if ($eid) {
				$this->removeObsoleteUpdateSites($eid);
			}
		}

		echo '<span style="font-size: 11px; font-style: italic; font-weight: bold;">iCagenda &#8226; <a href="https://www.joomlic.com/extensions/icagenda" rel="noopener noreferrer" target="_blank">www.joomlic.com</a></span>';

		echo '<hr /></div>';
	}

	/**
	 * Runs on installation (but not on upgrade). This happens in install and discover_install installation routes.
	 *
	 * @param   \JInstallerAdapterPackage  $parent  Parent object
	 *
	 * @return  bool
	 */
	public function install($parent)
	{
		// Enable the extensions we need to install
		$this->enableExtensions();

		return true;
	}

	/**
	 * Runs on uninstallation
	 *
	 * @param   \JInstallerAdapterPackage  $parent  Parent object
	 *
	 * @return  bool
	 */
	public function uninstall($parent)
	{
		return true;
	}

	/**
	 * Enable modules and plugins after installing them
	 */
	private function enableExtensions($extensions = [])
	{
		if (empty($extensions)) {
			$extensions = $this->extensionsToEnable;
		}

		foreach ($extensions as $ext) {
			$this->enableExtension($ext[0], $ext[1], $ext[2], $ext[3]);
		}
	}

	/**
	 * Enable an extension
	 *
	 * @param   string   $type    The extension type.
	 * @param   string   $name    The name of the extension (the element field).
	 * @param   integer  $client  The application id (0: Joomla CMS site; 1: Joomla CMS administrator).
	 * @param   string   $group   The extension group (for plugins).
	 */
	private function enableExtension($type, $name, $client = 1, $group = null)
	{
		try {
			$db = JFactory::getDbo();

			$query = $db->getQuery(true)
				->update('#__extensions')
				->set($db->qn('enabled') . ' = ' . $db->q(1))
				->where('type = ' . $db->quote($type))
				->where('element = ' . $db->quote($name));
		} catch (\Exception $e) {
			return;
		}

		switch ($type) {
			case 'plugin':
				// Plugins have a folder but not a client
				$query->where('folder = ' . $db->quote($group));
				break;

			case 'language':
			case 'module':
			case 'template':
				// Languages, modules and templates have a client but not a folder
				$query->where('client_id = ' . (int) $client);
				break;

			default:
			case 'library':
			case 'package':
			case 'component':
				break;
		}

		try {
			$db->setQuery($query);
			$db->execute();
		} catch (\Exception $e) {
			// Nothing
		}
	}

	/**
	 * Check if extension installed and set extension_id to packageExtensions array.
	 */
	private function checkInstalled()
	{
		foreach ($this->packageExtensions as $k => $ext) {
			$extension_id = $this->extensionIsInstalled($ext[0], $ext[1], $ext[3]);

			$this->packageExtensions[$k][5] = $extension_id;

			if ( ! $extension_id) {
				$this->extensionsToAlwaysEnable[] = [$ext[0], $ext[1], $ext[2], $ext[3]];
			}
		}
	}

	/**
	 * Check if extension is installed
	 *
	 * @param   string   $type          The extension type.
	 * @param   string   $element       The element field.
	 * @param   string   $group         The extension group (for plugins).
	 */
	private function extensionIsInstalled($type, $element, $group = null)
	{
		try {
			$db = JFactory::getDbo();

			$query = $db->getQuery(true)
				->select('e.extension_id')
				->from('`#__extensions` AS e')
				->where('type = ' . $db->quote($type))
				->where('element = ' . $db->quote($element));
		} catch (\Exception $e) {
			return;
		}

		switch ($type) {
			case 'component':
				break;

			case 'language':
			case 'library':
				break;

			case 'module':
				break;

			case 'plugin':
				// Plugins have a folder but not a client
				$query->where('folder = ' . $db->quote($group));
				break;

			default:
			case 'template':
			case 'package':
				break;
		}

		try {
			$db->setQuery($query);
			$extension_id = $db->loadResult();
		} catch (\Exception $e) {
			// Nothing
		}

		return $extension_id;
	}

	/**
	 * Check and set extension message
	 */
	private function postMessages($extensions = [])
	{
		if (empty($extensions)) {
			$extensions = $this->packageExtensions;
		}

		foreach ($extensions as $ext) {
			$this->postMessage($ext[0], $ext[1], $ext[2], $ext[3], $ext[4], $ext[5]);
		}

		echo '<br /><br />';
	}

	/**
	 * Set extension message
	 *
	 * @param   string   $type          The extension type.
	 * @param   string   $element       The element field.
	 * @param   string   $client        0=site, 1=admin.
	 * @param   string   $group         The extension group (for plugins).
	 * @param   string   $name          The name of the extension (the title).
	 * @param   boolean  $extension_id  The extension id (if already installed).
	 */
	private function postMessage($type, $element, $client, $group, $name, $extension_id = null)
	{
		$labelClass = '';

		switch ($type) {
			case 'component':
				// Components, packages and libraries don't have a folder or client.
				// Included for completeness.
				$labelClass = version_compare(JVERSION, '4.0.0', 'lt') ? 'label label-primary' : 'badge bg-success';
				break;

			case 'file':
				$labelClass = version_compare(JVERSION, '4.0.0', 'lt') ? 'label label-inverse' : 'badge bg-dark text-white';
				break;

			case 'language':
			case 'library':
				$labelClass = version_compare(JVERSION, '4.0.0', 'lt') ? 'label label-warning' : 'badge bg-warning text-dark';
				break;

			case 'module':
				$labelClass = version_compare(JVERSION, '4.0.0', 'lt') ? 'label label-danger label-important' : 'badge bg-danger';
				break;

			case 'plugin':
				// Plugins have a folder but not a client
				$labelClass = version_compare(JVERSION, '4.0.0', 'lt') ? 'label label-info' : 'badge bg-primary';
				break;

			case 'template':

			default:
			case 'package':
				break;
		}

		$extensionLabel = '<span class="' . $labelClass . '" style="text-transform: capitalize;">&nbsp;' . JText::_('COM_INSTALLER_TYPE_TYPE_' . strtoupper($type)) . '&nbsp;</span>';
		$extensionName  = '<strong>' . $name . '</strong>';

		if ($extension_id) {
			echo '<div>' . $extensionLabel . '&nbsp;&nbsp;<small>' . JText::sprintf('COM_INSTALLER_MSG_UPDATE_SUCCESS', $extensionName) . '</small></div>';
		} else {
			echo '<div>' . $extensionLabel . '&nbsp;&nbsp;<small>' . JText::sprintf('COM_INSTALLER_INSTALL_SUCCESS', $extensionName) . '</small>'
				. ' &#8680; <span class="text-success"><strong>' . JText::_('JPUBLISHED') . '</strong></span></div>';
		}
	}

	/**
	 * Create folders used by iCagenda
	 */
	private function createFolders()
	{
		// Get Joomla Images PATH setting
		$image_path = JComponentHelper::getParams('com_media')->get('image_path');

		$icagenda_image_path = $image_path . '/icagenda';

		// Create Folder iCagenda in ROOT/IMAGES_PATH/icagenda
		$folders = [
			$icagenda_image_path,
			$icagenda_image_path . '/files',
			$icagenda_image_path . '/thumbs',
			$icagenda_image_path . '/thumbs/system',
			$icagenda_image_path . '/thumbs/themes',
			$icagenda_image_path . '/thumbs/copy',
			$icagenda_image_path . '/feature_icons',
			$icagenda_image_path . '/feature_icons/16_bit',
			$icagenda_image_path . '/feature_icons/24_bit',
			$icagenda_image_path . '/feature_icons/32_bit',
			$icagenda_image_path . '/feature_icons/48_bit',
			$icagenda_image_path . '/feature_icons/64_bit',
		];

		$message = '<div><i>' . JText::_('COM_ICAGENDA_FOLDER_CREATION') . '</i></div>';

		$labelClass = version_compare(JVERSION, '4.0.0', 'lt') ? 'label label-default' : 'badge bg-secondary';

		foreach ($folders as $folder) {
			$message .= '<div><span class="' . $labelClass . '">&nbsp;' . JText::_('COM_ICAGENDA_FOLDER') . '&nbsp;</span>&nbsp;&nbsp;<small>';

			if ( ! JFolder::exists(JPATH_ROOT . '/' . $folder)) {
				if (JFolder::create(JPATH_ROOT . '/' . $folder, 0755)) {
					$data = '<html>\n<body bgcolor="#FFFFFF">\n</body>\n</html>';
					JFile::write(JPATH_ROOT . '/' . $folder . '/index.html', $data);

					$message .= '<strong><span class="text-success">' . $folder . ' ' . JText::_('COM_ICAGENDA_CREATED') . '</span></strong>';
				} else {
					$message .= '<strong><span style="text-danger">' . $folder . ' ' . JText::_('COM_ICAGENDA_CREATION_FAILED') . '</span></strong> ' . JText::_('COM_ICAGENDA_PLEASE_CREATE_MANUALLY');
				}
			} else {
				// Folder exists
				$message .= '<strong>' . $folder . '</strong> <span>' . JText::_('COM_ICAGENDA_EXISTS') . '</span>';
			}

			$message .= '</small></div>';
		}

		$message.= '<br><br>';

		echo $message;
	}

	/*
	 * Delete unused update site
	 *
	 * $eid  int  extension_id
	 */
	private function removeObsoleteUpdateSites($eid)
	{
		if ($eid) {
			$db = JFactory::getDbo();

			$query = $db->getQuery(true)
				->delete('#__update_sites_extensions')
				->where('extension_id = ' . $eid);
			$db->setQuery($query);
			$db->execute();

			// Delete any unused update sites
			$query->clear()
				->select('update_site_id')
				->from('#__update_sites_extensions');
			$db->setQuery($query);
			$results = $db->loadColumn();

			if (\is_array($results)) {
				// So we need to delete the update sites and their associated updates
				$updatesite_delete = $db->getQuery(true);
				$updatesite_delete->delete('#__update_sites');
				$updatesite_query = $db->getQuery(true);
				$updatesite_query->select('update_site_id')
					->from('#__update_sites');

				// If we get results back then we can exclude them
				if (\count($results)) {
					$updatesite_query->where('update_site_id NOT IN (' . implode(',', $results) . ')');
					$updatesite_delete->where('update_site_id NOT IN (' . implode(',', $results) . ')');
				}

				// So let's find what update sites we're about to nuke and remove their associated extensions
				$db->setQuery($updatesite_query);
				$update_sites_pending_delete = $db->loadColumn();

				if (\is_array($update_sites_pending_delete) && \count($update_sites_pending_delete)) {
					// Nuke any pending updates with this site before we delete it
					// TODO: investigate alternative of using a query after the delete below with a query and not in like above
					$query->clear()
						->delete('#__updates')
						->where('update_site_id IN (' . implode(',', $update_sites_pending_delete) . ')');
					$db->setQuery($query);
					$db->execute();
				}

				// Note: this might wipe out the entire table if there are no extensions linked
				$db->setQuery($updatesite_delete);
				$db->execute();
			}

			// Last but not least we wipe out any pending updates for the extension
			$query->clear()
				->delete('#__updates')
				->where('extension_id = ' . $eid);
			$db->setQuery($query);
			$db->execute();
		}
	}
}
