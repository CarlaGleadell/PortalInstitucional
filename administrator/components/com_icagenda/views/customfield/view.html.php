<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.9.0 2023-10-25
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
 * View class Admin - Edit a Custom Field - iCagenda
 */
class iCagendaViewCustomfield extends JViewLegacy
{
	protected $state;
	protected $item;
	protected $form;

	/**
	 * Display the view
	 */
	public function display($tpl = null)
	{
		$this->state = $this->get('State');
		$this->item  = $this->get('Item');
		$this->form  = $this->get('Form');

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

		$user  = JFactory::getUser();
		$isNew = ($this->item->id == 0);

		if (isset($this->item->checked_out))
		{
			$checkedOut = ! ($this->item->checked_out == 0 || $this->item->checked_out == $user->get('id'));
		}
		else
		{
			$checkedOut = false;
		}

		$canDo = iCagendaHelper::getActions();

		// Set Title
		JToolBarHelper::title($isNew ? 'iCagenda <span style="font-size:14px;">- ' . JText::_('COM_ICAGENDA_CUSTOMFIELD_LEGEND_NEW') . '</span>'  : 'iCagenda <span style="font-size:14px;">- ' . JText::_('COM_ICAGENDA_CUSTOMFIELD_LEGEND_EDIT') . '</span>' , $isNew ? 'new' : 'pencil-2');

		$icTitle = $isNew ? JText::_('COM_ICAGENDA_CUSTOMFIELD_LEGEND_NEW') : JText::_('COM_ICAGENDA_CUSTOMFIELD_LEGEND_EDIT');

		$sitename = $app->getCfg('sitename');
		$title    = $app->getCfg('sitename') . ' - ' . JText::_('JADMINISTRATION') . ' - iCagenda: ' . $icTitle;

		$document->setTitle($title);

		// If not checked out, can save the item.
		if ( ! $checkedOut && ($canDo->get('core.edit')||($canDo->get('core.create'))))
		{
			JToolBarHelper::apply('customfield.apply', 'JTOOLBAR_APPLY');
			JToolBarHelper::save('customfield.save', 'JTOOLBAR_SAVE');
		}

		if ( ! $checkedOut && ($canDo->get('core.create')))
		{
			JToolBarHelper::custom('customfield.save2new', 'save-new.png', 'save-new_f2.png', 'JTOOLBAR_SAVE_AND_NEW', false);
		}

		// If an existing item, can save to a copy.
		if ( ! $isNew && $canDo->get('core.create'))
		{
			JToolBarHelper::custom('customfield.save2copy', 'save-copy.png', 'save-copy_f2.png', 'JTOOLBAR_SAVE_AS_COPY', false);
		}

		if (empty($this->item->id))
		{
			JToolBarHelper::cancel('customfield.cancel', 'JTOOLBAR_CANCEL');
		}
		else
		{
			JToolBarHelper::cancel('customfield.cancel', 'JTOOLBAR_CLOSE');
		}
	}
}
