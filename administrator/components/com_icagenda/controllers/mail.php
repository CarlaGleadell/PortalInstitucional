<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.6.14 2018-04-26
 *
 * @package     iCagenda.Admin
 * @link        https://www.icagenda.com
 *
 * @author      Cyril Rezé
 * @copyright   (c) 2012-2024 Cyril Rezé / iCagenda. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 *
 * @since       1.0
 *----------------------------------------------------------------------------
*/

defined('_JEXEC') or die;

jimport('joomla.application.component.controllerform');

/**
 * Event controller class.
 */
class iCagendaControllerMail extends JControllerForm
{
	function __construct()
	{
		$this->view_list = 'icagenda';

		parent::__construct();
	}

	/**
	 * Return Ajax to load date select options
	 *
	 * @since   3.5.9
	 */
	function dates()
	{
		icagendaAjax::getOptionsEventDates('mail');

		// Cut the execution short
//		JFactory::getApplication()->close();
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
		JSession::checkToken('request') or jexit(JText::_('JINVALID_TOKEN'));

		$app    = JFactory::getApplication();
		$jinput = $app->input;
		$model  = $this->getModel('Mail');

		if ( ! $model->send())
		{
			// Get the user data.
			$requestData = $this->input->post->get('jform', array(), 'array');

			// Save the data in the session.
			$app->setUserState('com_icagenda.mail.data', $requestData);

			// Redirect back to the newsletter screen.
			$this->setRedirect(JRoute::_('index.php?option=com_icagenda&view=mail&layout=edit', false));

			return false;
		}

		// Flush the data from the session.
		$app->setUserState('com_icagenda.mail.data', null);

		// Redirect back to the newsletter screen.
		$this->setRedirect(JRoute::_('index.php?option=com_icagenda&view=mail&layout=edit', false));

		return true;
	}

	/**
	 * Cancel the mail
	 *
	 * @return  void
	 *
	 * @since   3.5.9
	 */
	public function cancel($key = null)
	{
		// Check for request forgeries.
		JSession::checkToken('request') or jexit(JText::_('JINVALID_TOKEN'));

		$app = JFactory::getApplication();

		// Flush the data from the session.
		$app->setUserState('com_icagenda.mail.data', null);

		$this->setRedirect(JRoute::_('index.php?option=com_icagenda', false));
	}
}
