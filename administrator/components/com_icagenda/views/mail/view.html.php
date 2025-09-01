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
 * @since       2.0
 *----------------------------------------------------------------------------
*/

defined('_JEXEC') or die;

/**
 * View class Admin - Mail Newsletter - iCagenda
 */
class iCagendaViewMail extends JViewLegacy
{
	protected $data;

	protected $state;

	protected $item;

	protected $form;

	/**
	 * Display the view
	 */
	public function display($tpl = null)
	{
		$this->form = $this->get('Form');

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			throw new Exception(implode("\n", $errors), 500);

			return false;
		}

		$this->addToolbar();

		parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 */
	protected function addToolbar()
	{
		$app      = JFactory::getApplication();
		$document = JFactory::getDocument();
		$jinput   = $app->input;

		$jinput->set('hidemainmenu', true);

		$user = JFactory::getUser();

		$canDo = iCagendaHelper::getActions();

		// Set Title
		JToolBarHelper::title('iCagenda <span style="font-size:14px;">- ' . JText::_('COM_ICAGENDA_TITLE_MAIL') . '</span>', 'mail');

		$icTitle = JText::_('COM_ICAGENDA_TITLE_MAIL');

		$sitename = $app->getCfg('sitename');
		$title    = $app->getCfg('sitename') . ' - ' . JText::_('JADMINISTRATION') . ' - iCagenda: ' . $icTitle;

		$document->setTitle($title);

		JToolbarHelper::custom('mail.send', 'envelope.png', 'send_f2.png', 'ICAGENDA_JTOOLBAR_SEND', false);

		JToolBarHelper::cancel('mail.cancel', 'JTOOLBAR_CLOSE');
	}
}
