<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Plugin System - Autologin
 *----------------------------------------------------------------------------
 * @version     1.5.0 2021-12-25
 *
 * @package     iCagenda.Plugin
 * @subpackage  System.ic_autologin
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

JPluginHelper::importPlugin('icagenda'); // @todo: move to a new iCagenda system plugin

jimport('joomla.plugin.plugin');

class PlgSystemic_autologin extends JPlugin
{
	protected $ic_un;
	protected $ic_pw;

	function __construct(& $subject, $config)
	{
		parent::__construct($subject, $config);

		$this->loadLanguage();
	}

	function onAfterInitialise()
	{
		$app    = JFactory::getApplication();
		$jinput = $app->input;

		$this->ic_un = $jinput->get('icu', null, 'raw');
		$this->ic_pw = $jinput->get('icp', null, 'raw');

		if ( ! empty($this->ic_un) && ! empty($this->ic_pw))
		{
			$result = $this->icLogin();

			$urllink  = JUri::getInstance()->toString();
			$cleanurl = preg_replace('/&icu=[^&]*/', '', $urllink);
			$cleanurl = preg_replace('/&icp=[^&]*/', '', $cleanurl);

			// Redirect to target URL after success login
			if ( ! $result instanceof \Exception)
			{
				$app->redirect($cleanurl);
			}
		}

		return true;
	}

	/**
	 * Login with ENCRYPT PASSWORD
	 */
	function icLogin()
	{
		// Get the application object.
		$app = JFactory::getApplication();

		$db = JFactory::getDbo();
		$query = 'SELECT id, username, password'
				. ' FROM #__users'
				. ' WHERE username=' . $db->Quote($this->ic_un)
				. '   AND password=' . $db->Quote($this->ic_pw)
		;

		$db->setQuery($query);

		$result = $db->loadObject();

		if ($result)
		{
			JPluginHelper::importPlugin('user');

			$options = array();

			$options['action'] = 'core.login.site';

			$response['username'] = $result->username;

			$result = $app->triggerEvent('onUserLogin', array((array)$response, $options));
		}
	}
}
