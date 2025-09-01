<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Plugin Action Log
 *----------------------------------------------------------------------------
 * @version     1.3 2021-10-07
 *
 * @package     iCagenda.Plugin
 * @subpackage  Actionlog.icagenda
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
 * @since       iCagenda 3.7.5
 *----------------------------------------------------------------------------
*/

defined('_JEXEC') or die;

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory as JFactory;
use Joomla\CMS\Filesystem\Path as JPath;
use Joomla\CMS\MVC\Model\BaseDatabaseModel as JModelLegacy;
use Joomla\CMS\User\User;
//use Joomla\Component\Actionlogs\Administrator\Helper\ActionlogsHelper;
//use Joomla\Component\Actionlogs\Administrator\Plugin\ActionLogPlugin;
use Joomla\Utilities\ArrayHelper;

if (!class_exists('ActionLogPlugin', true))
{
	if (version_compare(JVERSION, '4.0', 'lt'))
	{
		JLoader::register('ActionLogPlugin', JPATH_ADMINISTRATOR . '/components/com_actionlogs/libraries/actionlogplugin.php');
	}
	else
	{
		\JLoader::register('ActionLogPlugin', JPATH_ADMINISTRATOR . '/components/com_actionlogs/Plugin/ActionLogPlugin.php');
	}
}

/**
 * iCagenda Actions Logging Plugin.
 */
class PlgActionlogIcagenda extends ActionLogPlugin
{
	/**
	 * Array of loggable extensions.
	 *
	 * @var    array
	 */
	protected $loggableExtensions = array();

	/**
	 * Application object.
	 *
	 * @var    JApplicationCms
	 */
	protected $app;

	/**
	 * Database object.
	 *
	 * @var    JDatabaseDriver
	 */
	protected $db;

	/**
	 * Load plugin language file automatically so that it can be used inside component
	 *
	 * @var    boolean
	 */
	protected $autoloadLanguage = true;

	/**
	 * Constructor.
	 *
	 * @param   object  &$subject  The object to observe.
	 * @param   array   $config    An optional associative array of configuration settings.
	 */
	public function __construct(&$subject, $config)
	{
		parent::__construct($subject, $config);

		$params = ComponentHelper::getComponent('com_actionlogs')->getParams();

		$db = JFactory::getDbo();

		$query = $db->getQuery(true);

		$query->select($db->quoteName('extension'));
		$query->from($db->quoteName('#__action_logs_extensions'));
		$query->where($db->quoteName('extension') . ' = '. $db->quote('com_icagenda'));

		$db->setQuery($query);

		$result = $db->loadResult();

		if ( ! $result)
		{
			$extension = new \stdClass();
			$extension->extension = 'com_icagenda';

			// Insert iCagenda (com_icagenda) into the action logs extensions table.
			$return = $db->insertObject('#__action_logs_extensions', $extension);
		}

		$this->loggableExtensions = $params->get('loggable_extensions', array());
	}

	/**
	 * After save content logging method
	 * This method adds a record to #__action_logs contains (message, date, context, user)
	 * Method is called right after the content is saved
	 *
	 * @param   string   $context  The context of the content passed to the plugin
	 * @param   object   $article  A JTableContent object
	 * @param   boolean  $isNew    If the content is just about to be created
	 *
	 * @return  void
	 */
	public function onContentAfterSave($context, $item, $isNew)
	{
		$user = JFactory::getUser();

		$option = $this->app->input->getCmd('option');

		if (!$this->checkLoggable($option))
		{
			return;
		}

//		$params = self::getLogContentTypeParams($context);

		// Not found a valid content type, don't process further
//		if ($params !== null)
//		{
//			return;
//		}

		list($extension, $contentType) = explode('.', $context);

		if ($extension !== 'com_icagenda')
		{
			return;
		}

		// Frontend Manager set context contentType
		if ($contentType == 'manager')
		{
			$event_id = $this->app->input->getInt('event_id');

			if ($event_id)
			{
				$contentType = 'event';
			}
		}

		if ($contentType == 'icategory')
		{
			$contentType = 'category';
		}

		if ($isNew)
		{
			$messageLanguageKey = strtoupper('PLG_ACTIONLOG_ICAGENDA_' . $contentType . '_ADDED');
			$defaultLanguageKey = strtoupper('PLG_SYSTEM_ACTIONLOGS_CONTENT_ADDED');
		}
		else
		{
			$messageLanguageKey = strtoupper('PLG_ACTIONLOG_ICAGENDA_' . $contentType . '_UPDATED');
			$defaultLanguageKey = strtoupper('PLG_SYSTEM_ACTIONLOGS_CONTENT_UPDATED');
		}

		// If the content type doesn't has it own language key, use default language key
		if (!JFactory::getLanguage()->hasKey($messageLanguageKey))
		{
			$messageLanguageKey = $defaultLanguageKey;
		}

		$title = array(
			'event'        => 'title',
			'category'     => 'title',
			'registration' => 'name',
			'feature'      => 'title',
			'customfield'  => 'title',
		);

		$message = array(
			'action'      => $isNew ? 'add' : 'update',
			'type'        => strtoupper('PLG_ACTIONLOG_ICAGENDA_TYPE_' . $contentType),
			'id'          => $item->get('id'),
			'title'       => $item->get('title', $item->get($title[$contentType])),
			'itemlink'    => self::getContentTypeLink($option, $contentType, $item->get('id')),
			'userid'      => $user->id,
			'username'    => $user->username,
			'accountlink' => 'index.php?option=com_users&task=user.edit&id=' . $user->id,
			'app'         => strtoupper('PLG_ACTIONLOG_ICAGENDA_APPLICATION_' . $this->app->getName()),
		);

		$this->addLog(array($message), $messageLanguageKey, $context);
	}

	/**
	 * Proxy for ActionlogsModelUserlog addLog method
	 *
	 * This method adds a record to #__action_logs contains (message_language_key, message, date, context, user)
	 *
	 * @param   array   $messages            The contents of the messages to be logged
	 * @param   string  $messageLanguageKey  The language key of the message
	 * @param   string  $context             The context of the content passed to the plugin
	 * @param   int     $userId              ID of user perform the action, usually ID of current logged in user
	 *
	 * @return  void
	 */
//	protected function addLog($messages, $messageLanguageKey, $context, $userId = null)
//	{
//		\JLoader::register('ActionlogsModelActionlog', JPATH_ADMINISTRATOR . '/components/com_actionlogs/models/actionlog.php');

		/* @var ActionlogsModelActionlog $model */
//		$model = JModelLegacy::getInstance('Actionlog', 'ActionlogsModel');
//		$model->addLog($messages, $messageLanguageKey, $context, $userId);
//	}

	/**
	 * Function to check if a component is loggable or not
	 *
	 * @param   string  $extension  The extension that triggered the event
	 *
	 * @return  boolean
	 */
	protected function checkLoggable($extension)
	{
		return in_array($extension, $this->loggableExtensions);
	}

	/**
	 * Get link to an item of given content type
	 *
	 * @param   string   $component
	 * @param   string   $contentType
	 * @param   integer  $id
	 * @param   string   $urlVar
	 * @param   JObject  $object
	 *
	 * @return  string  Link to the content item
	 *
	 * @since   1.4
	 */
	public static function getContentTypeLink($component, $contentType, $id, $urlVar = 'id', $object = null)
	{
		// Try to find the component helper.
		$eName = str_replace('com_', '', $component);
		$file  = JPath::clean(JPATH_ADMINISTRATOR . '/components/' . $component . '/helpers/' . $eName . '.php');

		if (file_exists($file))
		{
			$prefix = ucfirst(str_replace('com_', '', $component));
			$cName  = $prefix . 'Helper';

			\JLoader::register($cName, $file);

			if (class_exists($cName) && is_callable(array($cName, 'getContentTypeLink')))
			{
				return $cName::getContentTypeLink($contentType, $id, $object);
			}
		}

		if (empty($urlVar))
		{
			$urlVar = 'id';
		}

		// Return default link to avoid having to implement getContentTypeLink in most of our components
		return 'index.php?option=' . $component . '&task=' . $contentType . '.edit&' . $urlVar . '=' . $id;
	}

	/**
	 * Get parameters to be
	 *
	 * @param   string  $context  The context of the content
	 *
	 * @return  mixed  An object contains content type parameters, or null if not found
	 *
	 * @since   3.9.0
	 */
	public static function getLogContentTypeParams($context)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select('a.*')
			->from($db->quoteName('#__action_log_config', 'a'))
			->where($db->quoteName('a.type_alias') . ' = :context')
			->bind(':context', $context);

		$db->setQuery($query);

		return $db->loadObject();
	}
}
