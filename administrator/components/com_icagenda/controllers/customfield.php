<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.8.0 2019-07-19
 *
 * @package     iCagenda.Admin
 * @link        https://www.icagenda.com
 *
 * @author      Cyril Rezé
 * @copyright   (c) 2012-2024 Cyril Rezé / iCagenda. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 *
 * @since       3.4.0
 *----------------------------------------------------------------------------
*/

defined('_JEXEC') or die;

/**
 * The customfield controller
 */
class iCagendaControllerCustomfield extends JControllerForm
{
	/**
	 * Class constructor.
	 *
	 * @param   array  $config  A named array of configuration variables.
	 *
	 * @since   3.4.0
	 */
	public function __construct($config = array())
	{
		parent::__construct($config);
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
}
