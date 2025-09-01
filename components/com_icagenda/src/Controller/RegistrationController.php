<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.8.5 2022-04-29
 *
 * @package     iCagenda.Site
 * @subpackage  src.Controller
 * @link        https://www.icagenda.com
 *
 * @author      Cyril Rezé
 * @copyright   (c) 2012-2024 Cyril Rezé / iCagenda. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 *
 * @since       3.6
 *----------------------------------------------------------------------------
*/

namespace WebiC\Component\iCagenda\Site\Controller;

\defined('_JEXEC') or die;

use iClib\Date\Date as iCDate;
use iCutilities\Ajax\Ajax as icagendaAjax;
use iCutilities\Event\Event as icagendaEvent;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\Controller\FormController;
use Joomla\CMS\Router\Route;
use Joomla\Utilities\ArrayHelper;

/**
 * Registration controller class for iCagenda.
 */
class RegistrationController extends FormController
{
	/**
	 * Method to register a user.
	 *
	 * @return  boolean  True on success, false on failure.
	 */
	public function register()
	{
		$app    = Factory::getApplication();
		$jinput = $app->input;

		$eventID = $jinput->getInt('eventID');
		$menuID  = $jinput->getInt('itemID');

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
			$this->setRedirect(Route::_('index.php?option=com_icagenda&view=registration&id=' . $eventID . '&Itemid=' . $menuID, false));

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
			$this->setRedirect(Route::_('index.php?option=com_icagenda&view=registration&id=' . $eventID . '&Itemid=' . $menuID, false));

			return false;
		}

		// Flush the data from the session.
		$app->setUserState('com_icagenda.registration.data', null);

		// Redirect to the complete layout.
		if ($return === 'complete')
		{
			$this->setMessage(Text::_('COM_ICAGENDA_REGISTRATION_COMPLETE_SUCCESS'));
			$this->setRedirect(Route::_('index.php?option=com_icagenda&view=registration&layout=complete&id=' . $eventID . '&Itemid=' . $menuID, false));
		}

		// Redirect to the actions layout.
		elseif ($return)
		{
			// Save the data in the session.
			$app->setUserState('com_icagenda.registration.data', $data);
			$app->setUserState('com_icagenda.registration.regdata', $data);
			$app->setUserState('com_icagenda.registration.actions', $return);

			$this->setRedirect(Route::_('index.php?option=com_icagenda&view=registration&layout=actions&id=' . $eventID . '&Itemid=' . $menuID, false));
		}

		else
		{
			// Save the data in the session.
			$app->setUserState('com_icagenda.registration.data', $data);

			// Redirect back to the registration form.
			$this->setMessage($model->getError(), 'warning');
			$this->setRedirect(Route::_('index.php?option=com_icagenda&view=registration&id=' . $eventID . '&Itemid=' . $menuID, false));

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
		$app    = Factory::getApplication();
		$jinput = $app->input;

		$event_id  = $jinput->getInt('eventID');
		$menu_id   = $jinput->getInt('Itemid');
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
						'date' => iCDate::dateToAlias($app->getSession()->get('event_date'), 'Y-m-d-H-i'),
					);
					
			$eventURL = icagendaEvent::url($event_id, '', $jinput->getInt('Itemid'), $vars);

			$this->setRedirect($eventURL, false);
		}
		elseif ($processed && $return)
		{
			$this->setMessage(Text::_('COM_ICAGENDA_REGISTRATION_COMPLETE_SUCCESS'));
			$this->setRedirect(Route::_('index.php?option=com_icagenda&view=registration&layout=complete&id=' . $event_id . '&Itemid=' . $menu_id, false));
		}
		else
		{
			$this->setMessage(Text::_('COM_ICAGENDA_REGISTRATION_ACTIONS_NONE'));
			$this->setRedirect(Route::_('index.php?option=com_icagenda&view=registration&layout=actions&id=' . $event_id . '&Itemid=' . $menu_id, false));
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
	public function cancellation()
	{
		$app    = Factory::getApplication();
		$jinput = $app->input;
		$user   = Factory::getUser();

		$dates_cancelled = $jinput->get('dates_cancelled');
		$dates_cancelled = is_array($dates_cancelled) ? $dates_cancelled : implode(',', $dates_cancelled);
		$event_id        = $jinput->getInt('eventID');
		$menu_id         = $jinput->getInt('itemID');
		$user_id         = $user->get('id');

		if (empty($dates_cancelled))
		{
			$this->setMessage(Text::_('COM_ICAGENDA_REGISTRATION_CANCEL_SELECT_DATES'), 'error');
			$this->setRedirect(Route::_('index.php?option=com_icagenda&view=registration&layout=cancel&id=' . $event_id . '&Itemid=' . $menu_id, false));
		}

		$model = $this->getModel('Registration');

		// Attempt to cancel date(s) registration.
		$return = $model->cancellation($dates_cancelled, $user_id);

		if ($return === false)
		{
			$this->setMessage(Text::_('JERROR_LAYOUT_PAGE_NOT_FOUND'), 'warning');
			$this->setRedirect(Route::_('index.php?option=com_icagenda&view=event&id=' . $event_id . '&Itemid=' . $menu_id, false));

			return false;
		}
		elseif ($return && $dates_cancelled && $user_id)
		{
			ArrayHelper::toInteger($dates_cancelled);
			$dates_cancelled = implode(',', $dates_cancelled);

			$this->setMessage(Text::_('COM_ICAGENDA_REGISTRATION_CANCEL_SUCCESS'), 'message');
			$this->setRedirect(Route::_('index.php?option=com_icagenda&view=registration&layout=cancel&id=' . $event_id . '&Itemid=' . $menu_id . '&dates_cancelled=' . $dates_cancelled, false));
		}
		else
		{
			$this->setMessage(Text::_('JERROR_LAYOUT_PAGE_NOT_FOUND'), 'warning');
			$this->setRedirect(Route::_('index.php?option=com_icagenda&view=event&id=' . $event_id . '&Itemid=' . $menu_id, false));

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
		$jinput = Factory::getApplication()->input;

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
