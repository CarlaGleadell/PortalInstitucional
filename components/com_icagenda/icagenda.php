<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.8.11 2022-12-20
 *
 * @package     iCagenda.Site
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
 * @deprecated  J4
 *----------------------------------------------------------------------------
*/

defined('_JEXEC') or die;

// Get Application
$app = JFactory::getApplication();

if (!JPluginHelper::isEnabled('system', 'icagenda'))
{
	$alert_message = JText::sprintf('COM_ICAGENDA_ERROR_PLUGIN_DISABLED', JText::_('COM_ICAGENDA_PLUGIN_SYSTEM_ICAGENDA'));

	$app->enqueueMessage($alert_message, 'error');

	echo JText::_('COM_ICAGENDA_ERROR_LOAD');

	return;
}

$time_loading = JComponentHelper::getParams('com_icagenda')->get('time_loading', '');

if ($time_loading)
{
	$starttime = iCLibrary::getMicrotime();
}

JLoader::register('iCagendaHelperRoute', JPATH_SITE . '/components/com_icagenda/helpers/route.php');

// Load Utilities
JLoader::registerPrefix('icagenda', JPATH_ADMINISTRATOR . '/components/com_icagenda/utilities');

// Set Utilities namespace for common j3/j4 files (for J3, set psr4 instead of psr0).
JLoader::registerNamespace('iCutilities', JPATH_ADMINISTRATOR . '/components/com_icagenda/src/Utilities', false, false, 'psr4');

// Set JInput
$jinput = $app->input;

// Redirect old layouts of the view 'list' to new separated views for event details and registration (since 3.6.0)
$layout = $jinput->get('layout', '');
$id     = $jinput->get('id', '');
$Itemid = $jinput->get('Itemid', '');

if (in_array($layout, array('event', 'registration')))
{
	$new_event_url = 'index.php?option=com_icagenda&view=' . $layout . '&id=' . $id . '&Itemid=' . $Itemid;
	$app->redirect((string) $new_event_url, 301);
}

// Test if translation is missing, set to en-GB by default
$language = JFactory::getLanguage();
$language->load('com_icagenda', JPATH_SITE, 'en-GB', true);
$language->load('com_icagenda', JPATH_SITE, null, true);

// Load Vector iCicons Font
JHtml::_('stylesheet', 'media/com_icagenda/icicons/style.css');

// Shared CSS
JHtml::_('stylesheet', 'com_icagenda/icagenda.css', array('relative' => true, 'version' => 'auto'));

// CSS files which could be overridden into your site template. (eg. /templates/my_template/css/com_icagenda/icagenda-front.css)
JHtml::_('stylesheet', 'com_icagenda/icagenda-front.css', array('relative' => true, 'version' => 'auto'));
JHtml::_('stylesheet', 'com_icagenda/tipTip.css', array('relative' => true, 'version' => 'auto'));

// Load jQuery (J3)
JHtml::_('jquery.framework');

// Set iCtip
JHtml::_('script', 'com_icagenda/jquery.tipTip.js', array('relative' => true, 'version' => 'auto'));

$iCtip   = array();
$iCtip[] = '	jQuery(document).ready(function(){';
$iCtip[] = '		jQuery(".iCtip").tipTip({maxWidth: "200", defaultPosition: "top", edgeOffset: 1});';
$iCtip[] = '	});';

// Add the script to the document head.
JFactory::getDocument()->addScriptDeclaration(implode("\n", $iCtip));

// Check iCagenda System Errors
$systemReady = JEventDispatcher::getInstance()->trigger('onICagendaSystemCheck');

if (\is_array($systemReady) && \count($systemReady) > 0 && \in_array(false, $systemReady, true))
{
	echo JText::_('COM_ICAGENDA_ERROR_LOAD');

	return;
}

// Perform the Requested task
$controller = JControllerLegacy::getInstance('iCagenda');
$controller->execute(JFactory::getApplication()->input->get('task', 'display'));

// Redirect if set by the controller
$controller->redirect();

// Time to create content
if ($time_loading)
{
	$endtime = iCLibrary::getMicrotime();

	echo '<center style="font-size:8px;">Time to create content: ' . round($endtime-$starttime, 3) . ' seconds</center>';
}
