<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.8.0 2022-02-02
 *
 * @package     iCagenda.Admin
 * @subpackage  src.Controller
 * @link        https://www.icagenda.com
 *
 * @author      Cyril Rezé
 * @copyright   (c) 2012-2024 Cyril Rezé / iCagenda. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 *
 * @since       1.0
 *----------------------------------------------------------------------------
*/

namespace WebiC\Component\iCagenda\Administrator\Controller;

\defined('_JEXEC') or die;

use iCutilities\Ajax\Ajax as icagendaAjax;
use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Controller\BaseController;
use Joomla\CMS\Router\Route;

/**
 * iCagenda Component Mail Controller
 */
class MailController extends BaseController
{
	/**
	 * Return Ajax to load date select options
	 *
	 * @since   3.5.9
	 */
	function dates()
	{
		icagendaAjax::getOptionsEventDates('mail');

		// Cut the execution short
//		Factory::getApplication()->close();
	}

	/**
	 * Send the mail
	 *
	 * @return  void
	 *
	 * @since   3.5.9
	 */
	public function send()
	{
		// Check for request forgeries.
		$this->checkToken('request');

		$app   = Factory::getApplication();
		$model = $this->getModel('Mail');

		if ( ! $model->send())
		{
			// Get the user data.
			$requestData = $this->input->post->get('jform', array(), 'array');

			// Save the data in the session.
			$app->setUserState('com_icagenda.mail.data', $requestData);

			// Redirect back to the newsletter screen.
			$this->setRedirect(Route::_('index.php?option=com_icagenda&view=mail&layout=edit', false));

			return false;
		}

		// Flush the data from the session.
		$app->setUserState('com_icagenda.mail.data', null);

		// Redirect back to the newsletter screen.
		$this->setRedirect(Route::_('index.php?option=com_icagenda&view=mail&layout=edit', false));

		return true;
	}

	/**
	 * Cancel the mail
	 *
	 * @return  void
	 *
	 * @since   3.5.9
	 */
	public function cancel()
	{
		// Check for request forgeries.
		$this->checkToken('request');

		// Clear data from session.
		$this->app->setUserState('com_icagenda.mail.data', null);

		$this->setRedirect('index.php?option=com_icagenda&view=icagenda');
	}
}
