<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.9.0 2023-10-26
 *
 * @package     iCagenda.Admin
 * @link        https://www.icagenda.com
 *
 * @author      Cyril Rezé
 * @copyright   (c) 2012-2024 Cyril Rezé / iCagenda. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 *
 * @since       1.2.6
 *----------------------------------------------------------------------------
*/

defined('_JEXEC') or die;

jimport('joomla.filesystem.path');

// Access check.
if (JFactory::getUser()->authorise('core.admin', 'com_icagenda'))
{
	JToolBarHelper::preferences('com_icagenda');
}

/**
 * View class for a list of iCagenda.
 */
class iCagendaViewinfo extends JViewLegacy
{
	/**
	 * Display the view
	 */
	public function display($tpl = null)
	{
		JHtml::_('behavior.modal');

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			throw new Exception(implode("\n", $errors), 500);

			return false;
		}

		// We don't need toolbar in the modal window.
		if ($this->getLayout() !== 'modal')
		{
			$this->addToolbar();

			$this->sidebar = JHtmlSidebar::render();
		}

		parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @since	1.6
	 */
	protected function addToolbar()
	{
		require_once JPATH_COMPONENT . '/helpers/icagenda.php';

		$app      = JFactory::getApplication();
		$document = JFactory::getDocument();

		$state = $this->get('State');

		// Set Title
		JToolBarHelper::title('iCagenda <span style="font-size:14px;">- ' . JText::_('COM_ICAGENDA_INFO') . '</span>', 'info-2');

		$icTitle = JText::_('COM_ICAGENDA_INFO');

		$sitename = $app->getCfg('sitename');
		$title    = $app->getCfg('sitename') . ' - ' . JText::_('JADMINISTRATION') . ' - iCagenda: ' . $icTitle;

		$document->setTitle($title);
	}
}
