<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.8.5 2022-04-29
 *
 * @package     iCagenda.Site
 * @link        https://www.icagenda.com
 *
 * @author      Cyril Rezé
 * @copyright   (c) 2012-2024 Cyril Rezé / iCagenda. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 *
 * @since       3.6.0
 *----------------------------------------------------------------------------
*/

defined('_JEXEC') or die;

require_once JPATH_COMPONENT . '/controller.php';

/**
 * Registration controller class for Users.
 *
 * @since   3.6.0
 */
class iCagendaControllerRegistration extends iCagendaController
{
	/**
	 * Method to register a user.
	 *
	 * @return  boolean  True on success, false on failure.
	 *
	 * @since   3.6.0
	 */
	public function register()
	{
		// Check for request forgeries.
//		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		// If registration is disabled - Redirect to login page.
//		if (JComponentHelper::getParams('com_users')->get('allowUserRegistration') == 0)
//		{
//			$this->setRedirect(JRoute::_('index.php?option=com_users&view=login', false));

//			return false;
//		}

		$app    = JFactory::getApplication();
		$jinput = $app->input;

		$eventID = $jinput->getInt('eventID');

		$model = $this->getModel('Registration');

		// Get the user data.
		$requestData = $jinput->post->get('jform', array(), 'array');

		// Validate the posted data.
		$form = $model->getForm();

		if ( ! $form)
		{
			throw new \Exception($model->getError(), 500);

			return false;
		}

		$data = $model->validate($form, $requestData);

		// Check for validation errors.
		if ($data === false)
		{
			// Get the validation messages.
			$errors = $model->getErrors();

			// Push up to five validation messages out to the user.
			for ($i = 0, $n = count($errors); $i < $n && $i < 5; $i++)
			{
				if ($errors[$i] instanceof \Exception)
				{
					$app->enqueueMessage($errors[$i]->getMessage(), 'warning');
				}
				else
				{
					$app->enqueueMessage($errors[$i], 'warning');
				}
			}

			// Save the data in the session.
			$app->setUserState('com_icagenda.registration.data', $requestData);

			// Redirect back to the registration screen.
			$this->setRedirect(JRoute::_('index.php?option=com_icagenda&view=registration&id=' . $eventID, false));

			return false;
		}

		// Set data to user State
//		$app->setUserState('com_icagenda.registration.data', $requestData);

		// Attempt to save the data.
		$return = $model->register($data);

		// Check for errors.
		if ($return === false)
		{
			// Save the data in the session.
			$app->setUserState('com_icagenda.registration.data', $data);

			// Redirect back to the edit screen.
			$this->setMessage($model->getError(), 'warning');
			$this->setRedirect(JRoute::_('index.php?option=com_icagenda&view=registration&id=' . $eventID, false));

			return false;
		}

		// Flush the data from the session.
		$app->setUserState('com_icagenda.registration.data', null);

		// Redirect to the complete layout.
		if ($return === 'complete')
		{
			$this->setMessage(JText::_('COM_ICAGENDA_REGISTRATION_COMPLETE_SUCCESS'));
			$this->setRedirect(JRoute::_('index.php?option=com_icagenda&view=registration&layout=complete&id=' . $eventID, false));
		}

		// Redirect to the actions layout.
		elseif ($return)
		{
			// Save the data in the session.
			$app->setUserState('com_icagenda.registration.data', $data);
			$app->setUserState('com_icagenda.registration.regdata', $data);
			$app->setUserState('com_icagenda.registration.actions', $return);

			$this->setRedirect(JRoute::_('index.php?option=com_icagenda&view=registration&layout=actions&id=' . $eventID, false));
		}

		else
		{
			// Save the data in the session.
			$app->setUserState('com_icagenda.registration.data', $data);

			// Redirect back to the registration form.
			$this->setMessage($model->getError(), 'warning');
			$this->setRedirect(JRoute::_('index.php?option=com_icagenda&view=registration&id=' . $eventID, false));

			return false;
		}

		return true;
	}

	/**
	 * Method to complete actions.
	 *
	 * @return  boolean  True on success, false on failure.
	 *
	 * @since   3.6.13
	 */
	public function actions()
	{
		$app    = JFactory::getApplication();
		$jinput = $app->input;

		$event_id  = $jinput->getInt('eventID');
		$action    = $jinput->get('action', '');
		$data      = $jinput->get('data', null);
		$processed = $jinput->get('processed', '');

		$regid    = $app->getUserState('com_icagenda.registration.regid', '');
		$regData  = $app->getUserState('com_icagenda.registration.regdata', null );

		// Get registration ID
		$regData['id'] = $regid;

		$app->setUserState('com_icagenda.actions.data', $data);

		$model = $this->getModel('Registration');

		// Attempt to validate the action.
		$return = $model->actions($action, $data, $regData, $event_id);

		// Flush the data from the session.
		$app->setUserState('com_icagenda.registration.data', null);

		if ($action == 'abandon')
		{
			$vars = array(
						'date' => iCDate::dateToAlias(JFactory::getSession()->get('event_date'), 'Y-m-d-H-i'),
					);
					
			$eventURL = icagendaEvent::url($event_id, '', $jinput->getInt('Itemid'), $vars);

			$this->setRedirect($eventURL, false);
		}
		elseif ($processed && $return)
		{
			$this->setMessage(JText::_('COM_ICAGENDA_REGISTRATION_COMPLETE_SUCCESS'));
			$this->setRedirect(JRoute::_('index.php?option=com_icagenda&view=registration&layout=complete&id=' . $event_id, false));
		}
		else
		{
			$this->setMessage(JText::_('COM_ICAGENDA_REGISTRATION_ACTIONS_NONE'));
			$this->setRedirect(JRoute::_('index.php?option=com_icagenda&view=registration&layout=actions&id=' . $event_id, false));
		}

		return true;
	}

	/**
	 * Method to cancel one registration.
	 *
	 * @return  boolean  True on success, false on failure.
	 *
	 * @since   3.6.13
	 */
	public function cancel()
	{
		$app    = JFactory::getApplication();
		$jinput = $app->input;
		$user   = JFactory::getUser();

		$dates_cancelled = $jinput->get('dates_cancelled');
		$event_id        = $jinput->getInt('eventID');
		$user_id         = $user->get('id');

		if (empty($dates_cancelled))
		{
			$this->setMessage(JText::_('COM_ICAGENDA_REGISTRATION_CANCEL_SELECT_DATES'), 'error');
			$this->setRedirect(JRoute::_('index.php?option=com_icagenda&view=registration&layout=cancel&id=' . $event_id, false));
		}

		$model = $this->getModel('Registration');

		// Attempt to cancel date(s) registration.
		$return = $model->cancel($dates_cancelled, $user_id);

		if ($return === false)
		{
			$this->setMessage(JText::_('JERROR_LAYOUT_PAGE_NOT_FOUND'), 'warning');
			$this->setRedirect(JRoute::_('index.php?option=com_icagenda&view=event&id=' . $event_id, false));

			return false;
		}
		elseif ($return && $dates_cancelled && $user_id)
		{
			JArrayHelper::toInteger($dates_cancelled);
			$dates_cancelled = implode(',', $dates_cancelled);
			$this->setMessage(JText::_('COM_ICAGENDA_REGISTRATION_CANCEL_SUCCESS'), 'message');
			$this->setRedirect(JRoute::_('index.php?option=com_icagenda&view=registration&layout=cancel&id=' . $event_id . '&dates_cancelled=' . $dates_cancelled, false));
		}
		else
		{
			$this->setMessage(JText::_('JERROR_LAYOUT_PAGE_NOT_FOUND'), 'warning');
			$this->setRedirect(JRoute::_('index.php?option=com_icagenda&view=event&id=' . $event_id, false));

			return false;
		}

		return true;
	}

	/**
	 * Return Ajax to get total of registered people for one event and one date
	 *
	 * @since   3.6.5
	 */
	public function ticketsBookable()
	{
		$jinput = JFactory::getApplication()->input;

		$eventID = $jinput->get('eventID', '');

		$regDate = $jinput->get('regDate', '');
		$regDate = str_replace('space', ' ', $regDate);
		$regDate = str_replace('_', ':', $regDate);

		$typeReg = $jinput->get('typeReg', '');
		$maxReg  = $jinput->get('maxReg', '');
		$tickets = $jinput->get('tickets', '');

		icagendaAjax::getTicketsBookable($eventID, $regDate, $typeReg, $maxReg, $tickets);
	}
}
