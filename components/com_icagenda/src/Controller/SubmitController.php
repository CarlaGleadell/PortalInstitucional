<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.8.3 2022-03-18
 *
 * @package     iCagenda.Site
 * @subpackage  src.Controller
 * @link        https://www.icagenda.com
 *
 * @author      Cyril Rezé
 * @copyright   (c) 2012-2024 Cyril Rezé / iCagenda. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 *
 * @since       3.8
 *----------------------------------------------------------------------------
*/

namespace WebiC\Component\iCagenda\Site\Controller;

\defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\Controller\FormController;
use Joomla\CMS\Router\Route;

/**
 * Submit controller class for iCagenda.
 */
class SubmitController extends FormController
{
	/**
	 * Method to submit an event.
	 *
	 * @return  boolean  True on success, false on failure.
	 */
	public function submit()
	{
		// Check for request forgeries.
		$this->checkToken();

		$app    = Factory::getApplication();
//		$id     = $this->input->getInt('eventID');
		$menuID = $this->input->getInt('itemID');
		$model  = $this->getModel('Submit');

		// Get the user data.
		$requestData = $this->input->post->get('jform', array(), 'array');

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

			foreach ($errors as $error)
			{
				$errorMessage = $error;

				if ($error instanceof \Exception)
				{
					$errorMessage = $error->getMessage();
				}

				$app->enqueueMessage($errorMessage, 'warning');
			}

			// Push up to five validation messages out to the user.
//			for ($i = 0, $n = count($errors); $i < $n && $i < 5; $i++)
//			{
//				if ($errors[$i] instanceof \Exception)
//				{
//					$app->enqueueMessage($errors[$i]->getMessage(), 'warning');
//				}
//				else
//				{
//					$app->enqueueMessage($errors[$i], 'warning');
//				}
//			}

			// Save the data in the session.
			$app->setUserState('com_icagenda.submit.data', $requestData);

			// Redirect back to the submit screen.
			$this->setRedirect(Route::_('index.php?option=com_icagenda&view=submit&Itemid=' . $menuID, false));

			return false;
		}

		// Attempt to submit the data.
		$return = $model->submit($data);

		// Check for errors.
		if ($return === false)
		{
			// Save the data in the session.
			$app->setUserState('com_icagenda.submit.data', $data);

			// Redirect back to the edit screen.
			$this->setMessage($model->getError(), 'warning');
			$this->setRedirect(Route::_('index.php?option=com_icagenda&view=submit&Itemid=' . $menuID, false));

			return false;
		}

		// Flush the data from the session.
		$app->setUserState('com_icagenda.submit.data', null);

		// Redirect to the complete layout.
		if ($return)
		{
//			$this->setMessage(Text::_('COM_ICAGENDA_SUBMIT_COMPLETE_SUCCESS'));
			$this->setRedirect($return);
		}

		// Redirect to the actions layout.
//		elseif ($return)
//		{
			// Save the data in the session.
//			$app->setUserState('com_icagenda.submit.regdata', $data);
//			$app->setUserState('com_icagenda.submit.actions', $return);

//			$this->setRedirect(Route::_('index.php?option=com_icagenda&view=submit&layout=actions&id=' . $id . '&Itemid=' . $menuID, false));
//		}

		else
		{
			// Redirect back to the submit form.
			$this->setMessage($model->getError(), 'warning');
			$this->setRedirect(Route::_('index.php?option=com_icagenda&view=submit&Itemid=' . $menuID, false));

			return false;
		}

		return true;
	}
}
