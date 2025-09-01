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

/**
 * Controller class - iCagenda.
 */
class iCagendaController extends JControllerLegacy
{
	/**
	 * Method to display a view.
	 *
	 * @param   boolean      $cachable   If true, the view output will be cached
	 * @param   array        $urlparams  An array of safe url parameters and their variable types, for valid values see {@link JFilterInput::clean()}.
	 *
	 * @return  JController  This object to support chaining.
	 * @since   1.0
	 */
	public function display($cachable = false, $urlparams = false)
	{
		require_once JPATH_COMPONENT . '/helpers/icagenda.php';

		$jinput = JFactory::getApplication()->input;

		// Load the submenu.
		iCagendaHelper::addSubmenu($jinput->get('view', 'icagenda'));
		$view = $jinput->get('view', 'icagenda');
		$jinput->set('view', $view);

		parent::display();

		return $this;
	}
}
