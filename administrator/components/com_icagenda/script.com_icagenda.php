<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.9.11 2025-04-22
 *
 * @package     iCagenda
 * @subpackage  com_icagenda
 * @link        https://www.joomlic.com
 *
 * @author      Cyril Reze
 * @copyright   (c) 2012-2025 Cyril Reze / JoomliC. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 *
 * @since       2.0
 *----------------------------------------------------------------------------
*/

\defined('_JEXEC') or die;

use Joomla\CMS\Component\ComponentHelper as JComponentHelper;
use Joomla\CMS\Factory as JFactory;
use Joomla\CMS\Filesystem\File as JFile;
use Joomla\CMS\Filesystem\Folder as JFolder;
use Joomla\CMS\HTML\HTMLHelper as JHtml;
use Joomla\CMS\Language\Text as JText;
use Joomla\CMS\Uri\Uri as JUri;


// System Installation/Update, component iCagenda https://www.icagenda.com
#[AllowDynamicProperties]
class com_icagendaInstallerScript
{
	/*
	 * $parent is the class calling this method.
	 * $type is the type of change (install, update or discover_install, not uninstall).
	 * preflight runs before anything else and while the extracted files are in the uploaded temp folder.
	 * If preflight returns false, Joomla will abort the update and undo everything already done.
	 */
	private $ictype = 'core';

	/** @var array Obsolete files and folders to remove from the iCagenda oldest releases*/
	private $icagendaRemoveFiles = [
		'files' => [
			'administrator/cache/com_icagenda.updates.php',
			'administrator/components/com_icagenda/script.icagenda.php',
			'administrator/components/com_icagenda/models/fields.php',
			'administrator/components/com_icagenda/tmpl/icagenda/default_modal_pro.php',
			'administrator/components/com_icagenda/utilities/theme/joomla25.php',
			'libraries/ic_library/iCalcreator/iCalcreator.class.php',
			'media/com_icagenda/js/icdates.js',
			'media/com_icagenda/js/timepicker.js',
			'media/com_icagenda/js/icagenda.js',
			'media/com_icagenda/images/addthis_16.png',
			'media/com_icagenda/images/addthis_16x16.png',
			'media/com_icagenda/images/addthis_32x32.png',
			'media/com_icagenda/images/all_cats-16.png',
			'media/com_icagenda/images/all_events-16.png',
			'media/com_icagenda/images/blanck.png',
			'media/com_icagenda/images/border_title.png',
			'media/com_icagenda/images/btn-regis.png',
			'media/com_icagenda/images/customfields-16.png',
			'media/com_icagenda/images/features-16.png',
			'media/com_icagenda/images/generic-48.png',
			'media/com_icagenda/images/icon-add-16.png',
			'media/com_icagenda/images/icon-edit.png',
			'media/com_icagenda/images/icon_all-events.png',
			'media/com_icagenda/images/iconevent-add48.png',
			'media/com_icagenda/images/iconevent48.png',
			'media/com_icagenda/images/iconicagenda16.png',
			'media/com_icagenda/images/iconicagenda16_agenda.png',
			'media/com_icagenda/images/image.png',
			'media/com_icagenda/images/info-16.png',
			'media/com_icagenda/images/info.png',
			'media/com_icagenda/images/joomlic_iCagenda.png',
			'media/com_icagenda/images/loader.gif',
			'media/com_icagenda/images/new_cat-16.png',
			'media/com_icagenda/images/new_event-16.png',
			'media/com_icagenda/images/newsletter-16.png',
			'media/com_icagenda/images/no-photo.jpg',
			'media/com_icagenda/images/photo.jpg',
			'media/com_icagenda/images/registration-16.png',
			'media/com_icagenda/images/shadow.png',
			'media/com_icagenda/images/technical_requirements-16.png',
			'media/com_icagenda/images/themes-16.png',
			'media/com_icagenda/css/jquery-ui-1.8.17.custom.css',
			'media/com_icagenda/css/images/ui-bg_flat_0_aaaaaa_40x100.png',
			'media/com_icagenda/css/images/ui-bg_flat_0_eeeeee_40x100.png',
			'media/com_icagenda/css/images/ui-bg_flat_55_c0402a_40x100.png',
			'media/com_icagenda/css/images/ui-bg_flat_55_eeeeee_40x100.png',
			'media/com_icagenda/css/images/ui-bg_flat_75_ffffff_40x100.png',
			'media/com_icagenda/css/images/ui-bg_glass_100_f8f8f8_1x400.png',
			'media/com_icagenda/css/images/ui-bg_glass_35_dddddd_1x400.png',
			'media/com_icagenda/css/images/ui-bg_glass_55_fbf9ee_1x400.png',
			'media/com_icagenda/css/images/ui-bg_glass_60_eeeeee_1x400.png',
			'media/com_icagenda/css/images/ui-bg_glass_65_ffffff_1x400.png',
			'media/com_icagenda/css/images/ui-bg_glass_75_dadada_1x400.png',
			'media/com_icagenda/css/images/ui-bg_glass_75_e6e6e6_1x400.png',
			'media/com_icagenda/css/images/ui-bg_glass_95_fef1ec_1x400.png',
			'media/com_icagenda/css/images/ui-bg_highlight-soft_75_cccccc_1x100.png',
			'media/com_icagenda/css/images/ui-bg_inset-hard_75_999999_1x100.png',
			'media/com_icagenda/css/images/ui-bg_inset-soft_50_c9c9c9_1x100.png',
			'media/com_icagenda/css/images/ui-icons_222222_256x240.png',
			'media/com_icagenda/css/images/ui-icons_2e83ff_256x240.png',
			'media/com_icagenda/css/images/ui-icons_3383bb_256x240.png',
			'media/com_icagenda/css/images/ui-icons_454545_256x240.png',
			'media/com_icagenda/css/images/ui-icons_63a459_256x240.png',
			'media/com_icagenda/css/images/ui-icons_888888_256x240.png',
			'media/com_icagenda/css/images/ui-icons_999999_256x240.png',
			'media/com_icagenda/css/images/ui-icons_cd0a0a_256x240.png',
			'media/com_icagenda/css/images/ui-icons_fbc856_256x240.png',
			'media/com_icagenda/css/icagenda-back.j25.css',
			'media/com_icagenda/css/icagenda-front.j25.css',
			'media/com_icagenda/css/template.j25.css',
		],
		'folders' => [
			'administrator/components/com_icagenda/assets/elements',
			'administrator/components/com_icagenda/liveupdate',
			'administrator/components/com_icagenda/models/fields',
			'administrator/components/com_icagenda/src/Field',
			'administrator/components/com_icagenda/utilities/form/field',
			'administrator/components/com_icagenda/utilities/googlemaps',
			'libraries/ic_library/lib/iCalcreator',
			'libraries/ic_library/form',
			'media/com_icagenda/icicons/ie7',
			'media/com_icagenda/images/cal',
			'media/com_icagenda/images/manager',
			'media/com_icagenda/images/payment',
		],
	];

	/**
	 * Remove obsolete files and folders.
	 */
	private function _removeObsoleteFilesAndFolders($icagendaRemoveFiles)
	{
		// Remove files
		if ( ! empty($icagendaRemoveFiles['files'])) {
			foreach ($icagendaRemoveFiles['files'] as $file) {
				$f = JPATH_ROOT . '/' . $file;

				if ( ! JFile::exists($f)) continue;

				JFile::delete($f);
			}
		}

		// Remove folders
		if ( ! empty($icagendaRemoveFiles['folders'])) {
			foreach ($icagendaRemoveFiles['folders'] as $folder) {
				$f = JPATH_ROOT . '/' . $folder;

				if ( ! JFolder::exists($f)) continue;

				JFolder::delete($f);
			}
		}
	}

	/**
	 * Preflight
	 */
	function preflight ($type, $parent)
	{
		// Load translations
		$language = JFactory::getLanguage();
		$language->load('com_icagenda.sys', JPATH_ADMINISTRATOR, 'en-GB', true);
		$language->load('com_icagenda.sys', JPATH_ADMINISTRATOR, null, true);
	}

	/*
	 * $parent is the class calling this method.
	 * $type is the type of change (install, update or discover_install, not uninstall).
	 * postflight is run after the extension is registered in the database.
	 */
	function postflight($type, $parent)
	{
		// Updating Params to ensure a correct value
		$this->release           = $parent->getManifest()->version;
		$icagendaParams          = JComponentHelper::getParams('com_icagenda');
		$currentRelease          = $icagendaParams->get('release'); // Since 3.6.0
		$currentReleaseInstalled = $icagendaParams->get('release_installed'); // Since 3.8.2
		$oldSys                  = $icagendaParams->get('icsys');

		if ($type == 'update') {
			// Fix for 'period' value in db when registration mode was set on "for all dates" (versions 3.6.0 to 3.6.2)
			// Due to possible older issue, good to run this check/update for all version before 3.6
			if (version_compare($currentRelease, '3.6.6', 'lt')) {
				$db = JFactory::getDbo();
				$db->setQuery('SELECT id, params FROM #__icagenda_events');
				$listEvtParams = $db->loadObjectList();

				foreach ($listEvtParams as $event) {
					$evtID     = $event->id;
					$evtParams = json_decode($event->params, true);

					if ($evtParams['typeReg'] == 2) {
						$query = $db->getQuery(true)
							->update($db->qn('#__icagenda_registration'))
							->set($db->qn('period') . ' = 1')
							->where($db->qn('eventid') . ' = ' . intval($evtID))
							->where($db->qn('date') . ' = ""');
						$db->setQuery($query);
						$db->execute();
					}
				}
			}

			// Remove obsolete files and folders
			$icagendaRemoveFiles = $this->icagendaRemoveFiles;

			$this->_removeObsoleteFilesAndFolders($icagendaRemoveFiles);
		}

		// Always create or modify these parameters
		$ictype_label = ($this->ictype == 'core') ? '' : strtoupper($this->ictype);

		$params['version']                 = $ictype_label;
		$params['release']                 = $this->release;
		$params['release_installed']       = JHtml::date('now', 'Y-m-d H:i:s'); // Since 3.8.2
		$params['prior_release']           = $currentRelease; // Since 3.8.2
		$params['prior_release_installed'] = $currentReleaseInstalled; // Since 3.8.2
		$params['author']                  = 'Cyril Reze';
		$params['icsys']                   = $this->ictype;

		if ($this->ictype == 'core') $params['copy'] = '1';

		// Get com_icagenda extension_id
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);

		$query->select('extension_id')
			->from('#__extensions')
			->where('element = "com_icagenda"');
		$db->setQuery($query);

		$eid = $db->loadResult();

		if ($eid) {
			// Delete com_icagenda Update Server.
			// Using Package Update Server since 3.7.18
			$this->removeObsoleteUpdateSites($eid);

			if (version_compare(JVERSION, '4.0.0', 'ge')) {
				$db = JFactory::getDbo();
				$query = $db->getQuery(true)
					->delete('#__menu')
					->where($db->quoteName('menutype') . ' = ' . $db->quote('main'))
					->where($db->quoteName('title') . ' = ' . $db->quote('COM_ICAGENDA_MENUS_MAIL'))
					->where($db->quoteName('alias') . ' = ' . $db->quote('com-icagenda-menus-mail'));
				$db->setQuery($query);
				$db->execute();
			}
		}

		if (($type == 'install' || $oldSys !== $params['icsys']) && $this->ictype != 'core') {
			$params['copy'] = NULL;
		}

		// Define the following parameters only if it is an original install // To be checked!
		if ($type == 'install') {
			$params['first_installed'] = JHtml::date('now', 'Y-m-d H:i:s'); // Since 3.8.2
			$params['atlist'] = '1';
			$params['atevent'] = '1';
			$params['atfloat'] = '2';
			$params['aticon'] = '2';
			$params['arrowtext'] = '1';
			$params['statutReg'] = '1';
			$params['maxRlist'] = '5';
			$params['navposition'] = '0';
			$params['targetLink'] = '1';
			$params['participantList'] = '0';
			$params['participantSlide'] = '1';
			$params['participantDisplay'] = '1';
			$params['fullListColumns'] = 'tiers';
			$params['regEmailUser'] = '1';
			$params['timeformat'] = '1';
			$params['ShortDescLimit'] = '100';
			$params['limitRegEmail'] = '1';
			$params['limitRegDate'] = '1';
			$params['phoneRequired'] = '2';
			$params['headerList'] = '1';
			$params['addthis_removal'] = '1';

			if (version_compare(JVERSION, '4.0.0', 'ge')) {
				$params['upgrade_menu_events'] = '1';
			}
		} else {
			$extparticipantList   = $icagendaParams->get('participantList');
			$extparticipantSlide  = $icagendaParams->get('participantSlide');
			$extstatutReg         = $icagendaParams->get('statutReg');
			$extlimitRegEmail     = $icagendaParams->get('limitRegEmail');
			$extlimitRegDate      = $icagendaParams->get('limitRegDate');
			$extphoneRequired     = $icagendaParams->get('phoneRequired');
			$extregEmailUser      = $icagendaParams->get('regEmailUser');
			$largewidththreshold  = $icagendaParams->get('largewidththreshold', '1201');
			$mediumwidththreshold = $icagendaParams->get('mediumwidththreshold', '769');
			$smallwidththreshold  = $icagendaParams->get('smallwidththreshold', '481');

			$params['largewidththreshold']  = $largewidththreshold;
			$params['mediumwidththreshold'] = $mediumwidththreshold;
			$params['smallwidththreshold']  = $smallwidththreshold;

			if ($extparticipantList == '2') {
				$params['participantList'] = '0';
			}

			if ($extparticipantSlide == '2') {
				$params['participantSlide'] = '0';
			}

			if ($extstatutReg == '2') {
				$params['statutReg'] = '0';
			}

			if ($extlimitRegEmail == '2') {
				$params['limitRegEmail'] = '0';
			}

			if ($extlimitRegDate == '2') {
				$params['limitRegDate'] = '0';
			}

			if ($extphoneRequired == '2') {
				$params['phoneRequired'] = '0';
			}

			if ($extregEmailUser == '2') {
				$params['regEmailUser'] = '0';
			}

			// UPDATE 3.1.1
			if ($icagendaParams->get('emailRequired') == '') {
				$params['emailRequired'] = '1';
			}

			// UPDATE 3.4.1
			// Change option name.
			$datesDisplay_global = $icagendaParams->get('datesDisplay_global');

			if ($datesDisplay_global) {
				$params['datesDisplay'] = $datesDisplay_global;
			}

			// Convert old options to new common option for Captcha Plugin.
			$reg_captcha         = $icagendaParams->get('reg_captcha', '');
			$submit_captcha      = $icagendaParams->get('submit_captcha', '');
			$captcha             = $icagendaParams->get('captcha', '');

			if (\in_array($reg_captcha, ['', '0'])
				&& \in_array($submit_captcha, ['', '0'])
			) {
				$params['captcha'] = $captcha;
			} elseif ( ! \in_array($reg_captcha, ['', '0', '1'])) {
				$params['captcha'] = $reg_captcha;
			} elseif ( ! \in_array($submit_captcha, ['', '0', '1'])) {
				$params['captcha'] = $submit_captcha;
			} else {
				$params['captcha'] = $captcha;
			}

			$params['reg_captcha']    = (\in_array($reg_captcha, ['', '0'])) ? '0' : '1';
			$params['submit_captcha'] = (\in_array($submit_captcha, ['', '0'])) ? '0' : '1';

			// UPDATE 3.6.9
			// Convert changed value
			$filters_mode = $icagendaParams->get('filters_mode');

			if (version_compare($currentRelease, '3.6.9', 'lt')
				&& ! $filters_mode
			) {
				$params['filters_mode'] = '2';
			}
		
			// Patch for 3.6.9
			if ($currentRelease == '3.6.9') {
				$db    = JFactory::getDbo();
				$query = $db->getQuery(true);

				$query->update($db->qn('#__icagenda_events'))
					->set($db->qn('startdate') . ' = "0000-00-00 00:00:00"')
					->where($db->qn('startdate') . ' = "1970-01-01 01:00:00"')
					->update($db->qn('#__icagenda_events'))
					->set($db->qn('enddate') . ' = "0000-00-00 00:00:00"')
					->where($db->qn('enddate') . ' = "1970-01-01 01:00:00"')
					->update($db->qn('#__icagenda_events'))
					->set($db->qn('next') . ' = "0000-00-00 00:00:00"')
					->where($db->qn('next') . ' = "1970-01-01 01:00:00"');
				$db->setQuery($query);
				$db->execute();
			}

			// UPDATE 3.7.0
			if (version_compare($currentRelease, '3.7.0', 'lt')) {
				$terms_Type = $icagendaParams->get('terms_Type');

				if ($terms_Type == '') {
					$terms_Type = 3;
				}

				$params['terms_type'] = $terms_Type;

				$accessParticipantList = $icagendaParams->get('accessParticipantList');

				$params['participant_name_visibility']  = ($accessParticipantList < 3 ) ? $accessParticipantList : '';
				$params['participant_gravatar_consent'] = '0';
			}

			// UPDATE 3.7.2
			// Keep Google Maps JS API as previously used.
			if (version_compare($currentRelease, '3.7.2', 'lt')) {
				$params['maps_service'] = '3';
			}

			// UPDATE 3.8.0
			// Hidden form validation. Used for dev. debug only.
			$params['reg_form_validation']    = 0;
			$params['submit_form_validation'] = 0;

			// Convert old option "No" (value '2') to new option value '0'.
			if ($icagendaParams->get('datesDisplay') == '2') {
				$params['datesDisplay'] = '0';
			}

			// UPDATE 3.8.2 (3.6 -> 3.8.1) Fix Registration State if first ever version installed < 3.6
			if (version_compare($currentRelease, '3.8.2', 'lt')) {
				// Prior release to current release '$currentRelease' to be updated (the latest version installed before this update)
				$priorRelease = $icagendaParams->get('prior_release', '');

				$db = JFactory::getDbo();

				$query = $db->getQuery(true);

				$query->update($db->qn('#__icagenda_registration'))
					->set($db->qn('status') . ' = "1"')
					->where($db->qn('status') . ' = "0"');

				if (version_compare($currentRelease, '3.7.21', 'le')) {
					$query->where($db->qn('created') . ' < ' . $db->q(JHtml::date('now', 'Y-m-d H:i:s')))
						->where($db->qn('modified') . ' < ' . $db->q(JHtml::date('now', 'Y-m-d H:i:s')));
				} elseif (version_compare($currentRelease, '3.8.1', 'le')
					&& (!$priorRelease || version_compare($priorRelease, '3.7.21', 'le'))
				) {
					$query->where($db->qn('created') . ' < "2022-03-02 08:00:00"')
						->where($db->qn('modified') . ' < "2022-03-02 08:00:00"');
				} else {
					$query->where($db->qn('created') . ' < "2022-02-22 08:00:00"')
						->where($db->qn('modified') . ' < "2022-02-22 08:00:00"');
				}

				$db->setQuery($query);
				$db->execute();
			}

			// UPDATE 3.9.4
			// Instantiate a new option.
			if (version_compare($currentRelease, '3.9.4', 'lt')) {
				$params['submit_language_display'] = 0;
			}
		}

		// UPDATE PARAMS
		$this->setParams($params);

		// Set default Access Permissions for iCagenda component
		$rules['core.manage']                   = ['6' => 1];
		$rules['icagenda.access.categories']    = ['7' => 1];
		$rules['icagenda.access.events']        = ['6' => 1];
		$rules['icagenda.access.registrations'] = ['7' => 1];
		$rules['icagenda.access.newsletter']    = ['7' => 1];
		$rules['icagenda.access.themes']        = ['7' => 1];
		$rules['icagenda.access.customfields']  = ['7' => 1];
		$rules['icagenda.access.features']      = ['7' => 1];

		// UPDATE RULES
		$this->setRules($rules);

		$usageStats = $icagendaParams->get('usage_stats', '1');

		$sendSystemInfo = $this->getSystemInfo($type, $parent);

		if ($sendSystemInfo && $usageStats) {
			echo $sendSystemInfo;
		}

		echo '<span style="font-size: 11px; font-style: italic; font-weight: bold;">iCagenda &#8226; <a href="https://www.icagenda.com" target="_blank">www.icagenda.com</a></span>';

		echo '<hr />';
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

	/*
	 * $parent is the class calling this method
	 * uninstall runs before any other action is taken (file removal or database processing).
	 */
	function uninstall( $parent )
	{
		echo '<p>' . JText::_('COM_ICAGENDA_UNINSTALL') . '</p>';
	}

	/*
	 * get a variable from the manifest file (actually, from the manifest cache).
	 */
	function getParam($name)
	{
		$db = JFactory::getDbo();

		$db->setQuery('SELECT manifest_cache FROM #__extensions WHERE element = "com_icagenda"');

		$manifest = json_decode($db->loadResult(), true);

		return $manifest[$name];
	}

	/*
	 * sets parameter values in the component's row of the extension table
	 */
	function setParams($param_array)
	{
		if (\count($param_array) > 0) {
			// Read the existing component value(s)
			$db = JFactory::getDbo();

			$db->setQuery('SELECT params FROM #__extensions WHERE element = "com_icagenda"');

			$params = json_decode($db->loadResult(), true);

			// Add the new variable(s) to the existing one(s)
			foreach ($param_array as $name => $value) {
				$params[ (string) $name ] = (string) $value;
			}

			// Store the combined new and existing values back as a JSON string
			$paramsString = json_encode($params);

			$db->setQuery('UPDATE #__extensions SET params = ' .
				$db->quote( $paramsString ) .
				' WHERE element = "com_icagenda"' );
				$db->execute();
		}
	}

	/*
	 * Sets access permissions values (rules) in the component's row of the assets table
	 */
	function setRules($rule_array)
	{
		if (\count($rule_array) > 0) {
			// Read the existing rules values
			$db = JFactory::getDbo();

			$db->setQuery('SELECT rules FROM #__assets WHERE name = "com_icagenda"');

			$rules = json_decode($db->loadResult(), true);

			// Add the new variable(s) to the existing one(s)
			foreach ($rule_array as $name => $value) {
				if ($rules != null && !array_key_exists($name, $rules)) {
					$rules[ (string) $name ] = (array) $value;
				}
			}

			// Store the combined new and existing values back as a JSON string
			$rulesString = json_encode($rules);

			$db->setQuery('UPDATE #__assets SET rules = ' .
				$db->quote( stripslashes($rulesString) ) .
				' WHERE name = "com_icagenda"' );
			$db->execute();
		}
	}

	/**
	 * Send site system information
	 * Adapted from Nicholas K. Dionysopoulos's code (Akeeba - www.akeebabackup.com).
	 */
	public function getSystemInfo($type, $parent)
	{
		$this->release = $parent->getManifest()->version;

		// Do not system info on localhost
		if ((strpos(JUri::root(), 'localhost') !== false)
			|| (strpos(JUri::root(), '127.0.0.1') !== false)
		) {
			return false;
		}

		// Set site ID
		$siteId = md5(JUri::base());

		// If info file is missing, stop it!
		if ( ! file_exists(JPATH_ROOT . '/administrator/components/com_icagenda/assets/jcms/info.php')) {
			return false;
		}

		if ( ! class_exists('iCagendaSystemInfo', false)) {
			require_once JPATH_ROOT . '/administrator/components/com_icagenda/assets/jcms/info.php';
		}

		if ( ! class_exists('iCagendaSystemInfo', false)) {
			return false;
		}

		$params = JComponentHelper::getParams('com_icagenda');

		// Stop if system info is turned off
		if ( ! $params->get('system_info', 1)) {
			return false;
		}

		$db = JFactory::getDbo();
		$stats = new iCagendaSystemInfo();

		$stats->setSiteId($siteId);

		// Get iCagenda release
		$ic_parts = explode('.', $this->release);
		$ic_major = $ic_parts[0];
		$ic_minor = isset($ic_parts[1]) ? $ic_parts[1] : '';
		$ic_revision = isset($ic_parts[2]) ? $ic_parts[2] : '';

		// Get PHP version
		list($php_major, $php_minor, $php_revision) = explode('.', phpversion());
		$php_qualifier = strpos($php_revision, '~') !== false ? substr($php_revision, strpos($php_revision, '~')) : '';

		// Get Joomla version
		list($cms_major, $cms_minor, $cms_revision) = explode('.', JVERSION);

		// Get Database version
		list($db_major, $db_minor, $db_revision) = explode('.', $db->getVersion());
		$db_qualifier = strpos($db_revision, '~') !== false ? substr($db_revision, strpos($db_revision, '~')) : '';

		// Get Database type
		$db_driver = get_class($db);

		if (stripos($db_driver, 'mysql') !== false) {
			$db_type = '1';
		} elseif (stripos($db_driver, 'sqlsrv') !== false || stripos($db_driver, 'sqlazure')) {
			$db_type = '2';
		} elseif (stripos($db_driver, 'postgresql') !== false) {
			$db_type = '3';
		} else {
			$db_type = '0';
		}

		$installtype = ($type == 'install') ? '1' : '2';
		$ictype      = $this->ictype;

		$stats->setValue('ins', $installtype); // software_install

		// Version : major(x).minor(y).revision/patch(z)
		$stats->setValue('swn', 'iCagenda'); // software_name
		$stats->setValue('swt', $ictype); // software_type
		$stats->setValue('swx', $ic_major); // software_major
		$stats->setValue('swy', $ic_minor); // software_minor
		$stats->setValue('swz', $ic_revision); // software_revision

		$stats->setValue('cmst', 1); // cms_type
		$stats->setValue('cmsx', $cms_major); // cms_major
		$stats->setValue('cmsy', $cms_minor); // cms_minor
		$stats->setValue('cmsz', $cms_revision); // cms_revision

		$stats->setValue('phpx', $php_major); // php_major
		$stats->setValue('phpy', $php_minor); // php_minor
		$stats->setValue('phpz', $php_revision); // php_revision
		$stats->setValue('phpq', $php_qualifier); // php_qualifiers

		$stats->setValue('dbt', $db_type); // db_type
		$stats->setValue('dbx', $db_major); // db_major
		$stats->setValue('dby', $db_minor); // db_minor
		$stats->setValue('dbz', $db_revision); // db_revision
		$stats->setValue('dbq', $db_qualifier); // db_qualifiers

		$return = $stats->sendInfo(true);

		return $return;
	}
}
