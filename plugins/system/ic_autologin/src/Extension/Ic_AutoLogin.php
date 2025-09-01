<?php
/**
 *----------------------------------------------------------------------------
 * iC Tools     Plugin System Auto Login
 *----------------------------------------------------------------------------
 * @version     2.0.0 2024-01-23
 *
 * @package     iCagenda
 * @subpackage  Plugin.System
 * @link        https://www.joomlic.com
 *
 * @author      Cyril Rezé
 * @copyright   (c) 2013-2024 Cyril Rezé / JoomliC. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 *
 * @since       1.0
 *----------------------------------------------------------------------------
*/

namespace W3biC\Plugin\System\Ic_AutoLogin\Extension;

use Joomla\CMS\Factory;
use Joomla\CMS\Plugin\CMSPlugin;
use Joomla\CMS\Plugin\PluginHelper;
use Joomla\CMS\Uri\Uri;
use Joomla\Database\DatabaseAwareTrait;

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

/**
 * Ic AutoLogin System Plugin
 */
final class Ic_AutoLogin extends CMSPlugin
{
	use DatabaseAwareTrait;

	protected $ic_un;
	protected $ic_pw;

	function __construct(& $subject, $config)
	{
		parent::__construct($subject, $config);

		$this->loadLanguage();
	}

	function onAfterInitialise()
	{
		$app    = Factory::getApplication();
		$input = $app->input;

		$this->ic_un = $input->get('icu', null, 'raw');
		$this->ic_pw = $input->get('icp', null, 'raw');

		if ( ! empty($this->ic_un) && ! empty($this->ic_pw)) {
			$result = $this->icLogin();

			$urllink  = Uri::getInstance()->toString();
			$cleanurl = preg_replace('/&icu=[^&]*/', '', $urllink);
			$cleanurl = preg_replace('/&icp=[^&]*/', '', $cleanurl);

			// Redirect to target URL after success login
			if ( ! $result instanceof \Exception) {
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
		$app = Factory::getApplication();

		$db = Factory::getDbo();
		$query = 'SELECT id, username, password'
				. ' FROM #__users'
				. ' WHERE username=' . $db->Quote($this->ic_un)
				. '   AND password=' . $db->Quote($this->ic_pw)
		;

		$db->setQuery($query);

		$result = $db->loadObject();

		if ($result) {
			PluginHelper::importPlugin('user');

			$options = array();

			$options['action'] = 'core.login.site';

			$response['username'] = $result->username;

			$result = $app->triggerEvent('onUserLogin', array((array)$response, $options));
		}
	}
}
