<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.9.0 2024-01-11
 *
 * @package     iCagenda.Admin
 * @subpackage  src.Controller
 * @link        https://www.icagenda.com
 *
 * @author      Cyril Rezé
 * @copyright   (c) 2012-2024 Cyril Rezé / iCagenda. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 *
 * @since       3.4.0
 *----------------------------------------------------------------------------
*/

namespace WebiC\Component\iCagenda\Administrator\Controller;

\defined('_JEXEC') or die;

use iCutilities\Ajax\Filter as icagendaAjaxFilter;
use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Controller\FormController;

/**
 * iCagenda Component Custom Field Controller
 */
class CustomfieldController extends FormController
{
	/**
	 * Return Ajax to check if a custom field group is set to any custom field
	 *
	 * @since   3.6.0
	 */
	function checkGroup()
	{
		icagendaAjaxFilter::checkCustomFieldGroup();
	}

	/**
	 * Return Ajax to delete a custom field group
	 *
	 * @since   3.6.0
	 */
	function deleteGroup()
	{
		icagendaAjaxFilter::deleteCustomFieldGroup();
	}

	/**
	 * Return Ajax to save a new custom field group
	 *
	 * @since   3.6.0
	 */
	function newGroup()
	{
		icagendaAjaxFilter::saveCustomFieldGroup();
	}

	/**
	 * Return Ajax to save a new custom field group
	 *
	 * @since   3.8.0
	 */
	function updateGroups()
	{
		icagendaAjaxFilter::getOptionsCustomfieldGroups();
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
		if ($user->authorise('core.edit', 'com_icagenda.customfield.' . $recordId)) {
			return true;
		}

		// Check edit own on the record asset (explicit or inherited)
		if ($user->authorise('core.edit.own', 'com_icagenda.customfield.' . $recordId)) {
			// Existing record already has an owner, get it
			$record = $this->getModel()->getItem($recordId);

			if (empty($record)) {
				return false;
			}

			// Grant if current user is owner of the record
			return $user->id == $record->created_by;
		}

		return false;
	}
}
