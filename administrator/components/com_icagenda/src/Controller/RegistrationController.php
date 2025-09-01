<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.9.0 2023-12-22
 *
 * @package     iCagenda.Admin
 * @subpackage  src.Controller
 * @link        https://www.icagenda.com
 *
 * @author      Cyril Rezé
 * @copyright   (c) 2012-2024 Cyril Rezé / iCagenda. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 *
 * @since       3.3.3
 *----------------------------------------------------------------------------
*/

namespace WebiC\Component\iCagenda\Administrator\Controller;

\defined('_JEXEC') or die;

use iCutilities\Ajax\Ajax as icagendaAjax;
use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Controller\FormController;

/**
 * iCagenda Component Registration Controller
 */
class RegistrationController extends FormController
{
	/**
	 * Return Ajax to load date select options
	 *
	 * @since   3.5.9
	 */
	function dates()
	{
		icagendaAjax::getOptionsEventDates('registration');
	}

	/**
	 * Return Full name/Username using Ajax (depending on Global Options)
	 *
	 * @since   3.6.0
	 */
	function registrationName()
	{
		$userId = Factory::getApplication()->input->get('userid', '');

		icagendaAjax::getRegistrationName($userId);
	}

	/**
	 * Method override to check if you can edit an existing record.
	 *
	 * @param   array   $data  An array of input data.
	 * @param   string  $key   The name of the key for the primary key.
	 *
	 * @return  boolean
	 *
	 * @since   3.9.0
	 */
	protected function allowEdit($data = [], $key = 'id')
	{
		$recordId = (int) isset($data[$key]) ? $data[$key] : 0;
		$user     = $this->app->getIdentity();

		// Zero record (id:0), return component edit permission by calling parent controller method
		if (!$recordId) {
			return parent::allowEdit($data, $key);
		}

		// Check edit on the record asset (explicit or inherited)
		if ($user->authorise('core.edit', 'com_icagenda.registration.' . $recordId)) {
			return true;
		}

		// Check edit own on the record asset (explicit or inherited)
		if ($user->authorise('core.edit.own', 'com_icagenda.registration.' . $recordId)) {
			// Existing record already has an owner, get it
			$record = $this->getModel()->getItem($recordId);

			if (empty($record)) {
				return false;
			}

			// Grant if current user is owner of the record
			if ($user->id == $record->created_by || $user->id == $record->userid) {
				return true;
			}

			// Grant if current user is owner of the event related to the record
			if (isset($record->eventAuthor) && $user->id == $record->eventAuthor) {
				return true;
			}
		}

		return false;
	}
}
