<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.9.0 2024-02-22
 *
 * @package     iCagenda.Admin
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

JHtml::_('behavior.tabstate');

// Get Application
$app = JFactory::getApplication();

if (!JPluginHelper::isEnabled('system', 'icagenda'))
{
	$alert_message = JText::sprintf('COM_ICAGENDA_ERROR_PLUGIN_DISABLED', JText::_('COM_ICAGENDA_PLUGIN_SYSTEM_ICAGENDA'));

	$app->enqueueMessage($alert_message, 'error');

	echo JText::_('COM_ICAGENDA_ERROR_LOAD');

	return;
}

// Loads Utilities
JLoader::registerPrefix('icagenda', JPATH_ADMINISTRATOR . '/components/com_icagenda/utilities');

// Common fields
JFormHelper::addFieldPath(JPATH_ADMINISTRATOR . '/components/com_icagenda/utilities/field');

// Set Input
$jinput = JFactory::getApplication()->input;

// Set some css property
$document = JFactory::getDocument();
$document->addStyleDeclaration('.icon-48-icagenda {background-image: none;}');

// Load Vector iCicons Font
JHtml::_('stylesheet', 'media/com_icagenda/icicons/style.css');

// CSS files which could be overridden into your site template. (eg. /templates/my_template/css/com_icagenda/icagenda-back.css)
JHtml::_('stylesheet', 'com_icagenda/icagenda-back.css', array('relative' => true, 'version' => 'auto'));

// Check iCagenda System Errors
$systemReady = JEventDispatcher::getInstance()->trigger('onICagendaSystemCheck');

if (\is_array($systemReady) && \count($systemReady) > 0 && \in_array(false, $systemReady, true) && $view != 'icagenda')
{
	echo JText::_('COM_ICAGENDA_ERROR_LOAD');

	return;
}

// Load translations
$language = JFactory::getLanguage();
$language->load('com_icagenda', JPATH_ADMINISTRATOR, 'en-GB', true);
$language->load('com_icagenda', JPATH_ADMINISTRATOR, null, true);

// Access check.
if ( ! JFactory::getUser()->authorise('core.manage', 'com_icagenda'))
{
	$app->enqueueMessage(JText::_('JERROR_ALERTNOAUTHOR'), 'error');

	return false;
}

// Require helper file
JLoader::register('iCagendaHelper', dirname(__FILE__) . '/helpers/icagenda.php');

// Check config params
//icagendaParams::encryptPassword();

// Get an instance of the controller prefixed by iCagenda
$controller = JControllerLegacy::getInstance('iCagenda');

// Perform the Request task
$controller->execute($jinput->get('task'));

// Redirect if set by the controller
$controller->redirect();
