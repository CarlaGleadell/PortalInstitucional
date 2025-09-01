<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Plugin System iCagenda
 *----------------------------------------------------------------------------
 * @version     2.0.0 2024-02-24
 *
 * @package     iCagenda
 * @subpackage  Plugin.System
 * @link        https://www.icagenda.com
 *
 * @author      Cyril Rezé
 * @copyright   (c) 2013-2024 Cyril Rezé / iCagenda. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 *
 * @since       iCagenda 3.8.4
 *----------------------------------------------------------------------------
*/

namespace W3biC\Plugin\System\Icagenda\Extension;

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Form\Form;
use Joomla\CMS\Form\FormHelper;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Log\Log;
use Joomla\CMS\Plugin\CMSPlugin;
use Joomla\CMS\Plugin\PluginHelper;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Uri\Uri;
use Joomla\Database\DatabaseAwareTrait;

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

/**
 * iCagenda System Plugin
 */
final class Icagenda extends CMSPlugin
{
	use DatabaseAwareTrait;

	/**
	 * Constructor.
	 *
	 * @param   object  $subject  The object to observe.
	 * @param   array   $config   An optional associative array of configuration settings.
	 */
	public function __construct(&$subject, $config)
	{
		parent::__construct($subject, $config);

		// Import icagenda plugin group so that these plugins will be triggered for events
		PluginHelper::importPlugin('icagenda');
	}

	/**
	 * Method to perform a check on iCagenda installation.
	 *
	 * return  void
	 */
	public function onICagendaSystemCheck()
	{
		// Get Application
		$app = Factory::getApplication();

		$errors =  [];

		// Check if iC Library is loaded
		if (!class_exists('\iClib\Library\Library')) {
			$errors[]= 'iCLibrary';
		}

		if (\count($errors) > 0) {
			$alert_message  = ($app->input->get('view') == 'icagenda')
							? Text::plural('PLG_SYSTEM_ICAGENDA_ERROR_INSTALL', \count($errors))
							: Text::plural('PLG_SYSTEM_ICAGENDA_CAN_NOT_LOAD', \count($errors));

			$alert_message.= '<ul>';

			// List error(s)
			if (\in_array('iCLibrary', $errors)) {
				$alert_message.= '<li>' . Text::_('PLG_SYSTEM_ICAGENDA_IC_LIBRARY_NOT_LOADED') . '</li>';
			}

			$alert_message.= '</ul>';

			// Inform about action(s) to be taken.
			if (\in_array('iCLibrary', $errors)) {
				$alert_message.= Text::_('PLG_SYSTEM_ICAGENDA_IC_LIBRARY_CHECK_PLUGIN_AND_LIBRARY');
			}

			// Get the message queue
			$messages = $app->getMessageQueue();

			$display_alert_message = true;

			// If we have messages
			if (\is_array($messages) && \count($messages)) {
				// Check each message for the one we want
				foreach ($messages as $key => $value) {
					if ($value['message'] == $alert_message) {
						$display_alert_message = false;
					}
				}
			}

			if ($display_alert_message) {
				$app->enqueueMessage($alert_message, 'error');
			}

			return false;
		}
	}

	/**
	 * New functionality messages
	 *
	 * @param   Form   $form  The form to be altered.
	 * @param   mixed  $data  The associated data for the form.
	 *
	 * @return  boolean
	 *
	 * @since   1.1
	 *
	 * @throws  Exception
	 */
	function onContentPrepareForm(Form $form, $data)
	{
		$app = Factory::getApplication();

		$name = $form->getName();

		$iCparams = ComponentHelper::getParams('com_icagenda');

		if ($name == 'com_config.component'
			&& $app->input->get('component') == 'com_icagenda'
			&& version_compare(JVERSION, '4.1.0', 'ge')
		) {
			$info_inlinehelp = $app->input->get('info_inlinehelp', '');

			// Get Current URL
			$thisURL = Uri::getInstance()->toString();

			$return_url = preg_replace('/&info_inlinehelp=[^&]*/', '', $thisURL);

			$db = Factory::getDbo();

			$query = $db->getQuery(true);

			$query->select($db->quoteName('params'))
				->from($db->quoteName('#__icagenda'))
				->where($db->quoteName('id') . ' = "3"');

			$db->setQuery($query);

			$result = $db->loadObject();

			$icagenda_params = json_decode($result->params, true);

			if ($info_inlinehelp == -1) {
				$this->setIcagendaParam($info_inlinehelp, 'msg_info_inlinehelp', '-1', $icagenda_params);
				$app->enqueueMessage(Text::_('IC_HIDE_THIS_MESSAGE_SUCCESS'), 'message');
				$app->redirect($return_url);
			} elseif ($info_inlinehelp == 1) {
				$this->setIcagendaParam($info_inlinehelp, 'msg_info_inlinehelp', '1', $icagenda_params);
				$app->redirect($return_url);
			}

			$msg_info_inlinehelp = isset($icagenda_params['msg_info_inlinehelp']) ? $icagenda_params['msg_info_inlinehelp'] : '1';

			if ($msg_info_inlinehelp == 1) {
				$app->enqueueMessage('<h2>' . Text::_('COM_ICAGENDA_MSG_NEW_FUNCTIONALITY_LABEL') . '</h2>'
					. '<p>' . Text::sprintf('COM_ICAGENDA_MSG_INFO_TOGGLE_INLINEHELP_BUTTON', Text::_('JINLINEHELP'), Text::_('JINLINEHELP')) . '</p>'
					. '<div>'
					. '<a href="' . Route::_($thisURL.'&info_inlinehelp=-1') . '">'
					. '<div class="btn btn-info">' . Text::_('IC_HIDE_THIS_MESSAGE') . '</div>'
					. '</a>'
					. '</div>'
					, 'info');
			}
		}
	}

	/**
	 * Method to register iCagenda.
	 *
	 * return  void
	 */
	public function onAfterInitialise()
	{
		// Load plugin language files.
		$this->loadLanguage();

		// Ensure that autoloaders are set
		\JLoader::setup();

		// Set iCagenda Utilities namespace
		// @todo check if required
		\JLoader::registerNamespace('iCutilities', JPATH_ADMINISTRATOR . '/components/com_icagenda/src/Utilities', false, false);

		// Add Field prefix
		FormHelper::addFieldPrefix('iCutilities\Field');

		// Update menu items link when upgrade from J3 to J4
		$upgrade_menu_events = ComponentHelper::getParams('com_icagenda')->get('upgrade_menu_events');

		if (!$upgrade_menu_events) {
			// Update menu links from J3 to J4
			self::updateMenuItems();

			// Store update processed in com_icagenda config
			$params['upgrade_menu_events'] = '1';
			self::setParams('com_icagenda', $params);

			Log::add('iCagenda has updated menu links for List of Events. J3 "list" view converted to J4 "events" view', Log::INFO, 'system-icagenda');
		}
	}

	/**
	 * Method to load Media files for iCagenda
	 *     - iCicons font (everywhere)
	 *     - icagenda.css (admin)
	 *     - Custom CSS from Component Config option (everywhere)
	 */
	public function onAfterDispatch()
	{
		// Load Vector iCicons Font
		HTMLHelper::_('stylesheet', 'media/com_icagenda/icicons/style.css', array('relative' => false, 'version' => 'auto'));

		// Load iCagenda CSS
		if (Factory::getApplication()->isClient('administrator')) {
			HTMLHelper::_('stylesheet', 'com_icagenda/icagenda.css', array('relative' => true, 'version' => 'auto'));
		}

		// Check if component is installed
		if (file_exists(JPATH_ADMINISTRATOR . '/components/com_icagenda/icagenda.php')) {
			// Custom CSS loading
			$customCSS_activation = ComponentHelper::getParams('com_icagenda')->get('customCSS_activation', '0');

			$customCSS = ComponentHelper::getParams('com_icagenda')->get('customCSS', '');

			if (!empty($customCSS_activation) && $customCSS) {
				Factory::getDocument()->addStyleDeclaration($customCSS);
			}
		}
	}

	/*
	 * Update menu items link(s)
	 */
	protected function updateMenuItems()
	{
		$db = Factory::getDbo();

		$query = $db->getQuery(true);

		// Fields to update.
		$fields = [
			$db->quoteName('link') . ' = ' . $db->quote('index.php?option=com_icagenda&view=events'),
		];

		// Conditions for which records should be updated.
		$conditions = [
			$db->quoteName('link') . ' = ' . $db->quote('index.php?option=com_icagenda&view=list'),
		];

		$query->update($db->quoteName('#__menu'))->set($fields)->where($conditions);

		$db->setQuery($query);

		$result = $db->execute();
	}

	/*
	 * Sets parameter values in the component's row of the extension table
	 */
	protected function setParams($element, $params_array)
	{
		if (\count($params_array) > 0) {
			// read the existing component value(s)
			$db = Factory::getDbo();

			$query = $db->getQuery(true);

			$query->select($db->quoteName('params'))
				->from($db->quoteName('#__extensions'))
				->where($db->quoteName('element') . ' = ' . $db->quote($element));

			$db->setQuery($query);

			$params = json_decode($db->loadResult(), true);

			// Add the new variable(s) to the existing one(s)
			foreach ($params_array as $name => $value) {
				$params[(string) $name] = (string) $value;
			}

			// Store the combined new and existing values back as a JSON string
			$paramsString = json_encode($params);

			$query = $db->getQuery(true);

			$query->update($db->quoteName('#__extensions'))
				->set($db->quoteName('params') . ' = ' . $db->quote($paramsString))
				->where($db->quoteName('element') . ' = ' . $db->quote($element));
				
			$db->setQuery($query);
			$db->execute();
		}
	}

	/**
	 * Save iCagenda Params
	 *
	 * @since   3.8.7
	 */
	public function setIcagendaParam($var, $name, $value, $params)
	{
		if ($var) {
			$params[$name] = $value;

			$this->updateIcagendaParams($params);
		}
	}

	/**
	 * Update iCagenda Params
	 *
	 * Update Database
	 *
	 * @since   3.8.7
	 */
	protected function updateIcagendaParams($params_array)
	{
		// Read the existing component value(s)
		$db = Factory::getDbo();

		$query = $db->getQuery(true);

		$query->select($db->quoteName('params'))
			->from($db->quoteName('#__icagenda'))
			->where($db->quoteName('id') . ' = "3"');

		$db->setQuery($query);

		$params = json_decode($db->loadResult(), true);

		// Add the new variable(s) to the existing one(s)
		foreach ( $params_array as $name => $value ) {
			$params[ (string) $name ] = $value;
		}

		// Store the combined new and existing values back as a JSON string
		$paramsString = json_encode( $params );

		$query = $db->getQuery(true);

		$query->update($db->quoteName('#__icagenda'))
			->set($db->quoteName('params') . ' = ' . $db->quote($paramsString))
			->where($db->quoteName('id') . ' = "3"');

		$db->setQuery($query);
		$db->execute();
	}
}
