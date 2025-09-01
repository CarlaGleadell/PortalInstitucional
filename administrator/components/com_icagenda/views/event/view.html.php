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
 * @since       1.0
 *----------------------------------------------------------------------------
*/

defined('_JEXEC') or die;

/**
 * View class Admin - Edit an Event - iCagenda
 */
class iCagendaViewEvent extends JViewLegacy
{
	protected $state;
	protected $item;
	protected $form;

	/**
	 * Display the view
	 *
	 * @since   1.0
	 */
	public function display($tpl = null)
	{
		$app = JFactory::getApplication();

		// Initialiase variables.
		$this->form  = $this->get('Form');
		$this->item  = $this->get('Item');
		$this->state = $this->get('State');

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			throw new Exception(implode("\n", $errors), 500);

			return false;
		}

		$icagenda_categories = class_exists('icagendaCategories') ? icagendaCategories::getList('1') : false;

		if ($icagenda_categories)
		{
			$this->addToolbar();
		}
		else
		{
			$app->enqueueMessage(JText::_('COM_ICAGENDA_ALERT_NO_CATEGORY_PUBLISHED')
								. '<br /><br /><a class="btn btn-success" href="index.php?option=com_icagenda&view=category&layout=edit" >'
								. JText::_('COM_ICAGENDA_LEGEND_NEW_CATEGORY') . '</a>'
								. ' <a class="btn btn-inverse btn-mini" href="index.php?option=com_icagenda&view=categories" >'
								. JText::_('ICCATEGORIES')
								. '</a>', 'warning');
			$app->redirect(htmlspecialchars_decode('index.php?option=com_icagenda&view=events'));
		}

		parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @since   1.0
	 */
	protected function addToolbar()
	{
		$app      = JFactory::getApplication();
		$document = JFactory::getDocument();
		$jinput   = $app->input;

		$jinput->set('hidemainmenu', true);

		$user       = JFactory::getUser();
		$userId     = $user->get('id');
		$isNew      = ($this->item->id == 0);
		$checkedOut = ! ($this->item->checked_out == 0 || $this->item->checked_out == $userId);
		$canDo      = iCagendaHelper::getActions();

		// Set Title
		JToolBarHelper::title($isNew    ? 'iCagenda <span style="font-size:14px;">- ' . JText::_('COM_ICAGENDA_LEGEND_NEW_EVENT') . '</span>'
										: 'iCagenda <span style="font-size:14px;">- ' . JText::_('COM_ICAGENDA_LEGEND_EDIT_EVENT') . '</span>',
										$isNew ? 'new' : 'pencil-2');

		$icTitle = $isNew ? JText::_('COM_ICAGENDA_LEGEND_NEW_EVENT') : JText::_('COM_ICAGENDA_LEGEND_EDIT_EVENT');

		$sitename = $app->getCfg('sitename');
		$title    = $sitename . ' - ' . JText::_('JADMINISTRATION') . ' - iCagenda: ' . $icTitle;

		$document->setTitle($title);

		// Build the actions for new and existing records.
		if ($isNew)
		{
			// For new records, check the create permission.
			if ($canDo->get('core.create'))
			{
				JToolBarHelper::apply('event.apply', 'JTOOLBAR_APPLY');
				JToolBarHelper::save('event.save', 'JTOOLBAR_SAVE');
				JToolBarHelper::custom('event.save2new', 'save-new.png', 'save-new_f2.png', 'JTOOLBAR_SAVE_AND_NEW', false);
			}

			JToolBarHelper::cancel('event.cancel', 'JTOOLBAR_CANCEL');
		}
		else
		{
			// Can't save the record if it's checked out.
			if ( ! $checkedOut)
			{
				// Since it's an existing record, check the edit permission, or fall back to edit own if the owner.
				if ($canDo->get('core.edit') || ($canDo->get('core.edit.own') && $this->item->created_by == $userId))
				{
					// We can save the new record
					JToolBarHelper::apply('event.apply', 'JTOOLBAR_APPLY');
					JToolBarHelper::save('event.save', 'JTOOLBAR_SAVE');

					// We can save this record, but check the create permission to see
					// if we can return to make a new one.
					if ($canDo->get('core.create'))
					{
						JToolBarHelper::custom('event.save2new', 'save-new.png', 'save-new_f2.png', 'JTOOLBAR_SAVE_AND_NEW', false);
					}
				}
			}

			// If checked out, we can still save
			if ($canDo->get('core.create'))
			{
				JToolBarHelper::custom('event.save2copy', 'save-copy.png', 'save-copy_f2.png', 'JTOOLBAR_SAVE_AS_COPY', false);
			}

			JToolBarHelper::cancel('event.cancel', 'JTOOLBAR_CLOSE');
		}
	}
}
