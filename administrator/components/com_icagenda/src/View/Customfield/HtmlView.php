<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.8.7 2022-06-09
 *
 * @package     iCagenda.Admin
 * @subpackage  src.View
 * @link        https://www.icagenda.com
 *
 * @author      Cyril Rezé
 * @copyright   (c) 2012-2024 Cyril Rezé / iCagenda. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 *
 * @since       3.4
 *----------------------------------------------------------------------------
*/

namespace WebiC\Component\iCagenda\Administrator\View\Customfield;

\defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\View\GenericDataException;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\CMS\Toolbar\ToolbarHelper;
use WebiC\Component\iCagenda\Administrator\Helper\iCagendaHelper;

/**
 * Custom Field HTML view class.
 */
class HtmlView extends BaseHtmlView
{
	/**
	 * The \JForm object
	 *
	 * @var  \JForm
	 */
	protected $form;

	/**
	 * The active item
	 *
	 * @var  object
	 */
	protected $item;

	/**
	 * The model state
	 *
	 * @var  \JObject
	 */
	protected $state;

	/**
	 * The actions the user is authorised to perform
	 *
	 * @var  \JObject
	 */
	protected $canDo;

	/**
	 * Display the view
	 */
	public function display($tpl = null)
	{
		$this->form  = $this->get('Form');
		$this->item  = $this->get('Item');
		$this->state = $this->get('State');
		$this->canDo = iCagendaHelper::getActions();

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			throw new GenericDataException(implode("\n", $errors), 500);
		}

		$this->addToolbar();

		if ( ! $this->canDo->get('icagenda.access.customfields'))
		{
			$app = Factory::getApplication();

			$app->enqueueMessage(Text::_('JERROR_ALERTNOAUTHOR'), 'warning');
			$app->redirect(htmlspecialchars_decode('index.php?option=com_icagenda&view=icagenda'));
		}

		parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @return  void
	 */
	protected function addToolbar()
	{
		Factory::getApplication()->input->set('hidemainmenu', true);

		$user  = Factory::getUser();
		$isNew = ($this->item->id == 0);

		if (isset($this->item->checked_out))
		{
			$checkedOut = ! ($this->item->checked_out == 0 || $this->item->checked_out == $user->get('id'));
		}
		else
		{
			$checkedOut = false;
		}

		$canDo = $this->canDo;

		// Set Title
		ToolBarHelper::title($isNew ? 'iCagenda - ' . Text::_('COM_ICAGENDA_CUSTOMFIELD_LEGEND_NEW')  : 'iCagenda - ' . Text::_('COM_ICAGENDA_CUSTOMFIELD_LEGEND_EDIT'), $isNew ? 'new' : 'pencil-2');

		$toolbarButtons = [];

		// If not checked out, can save the item.
		if ( ! $checkedOut && ($canDo->get('core.edit')||($canDo->get('core.create'))))
		{
			ToolBarHelper::apply('customfield.apply', 'JTOOLBAR_APPLY');
			$toolbarButtons[] = ['save', 'customfield.save'];
		}

		if ( ! $checkedOut && ($canDo->get('core.create'))){
			$toolbarButtons[] = ['save2new', 'customfield.save2new'];
		}

		// If an existing item, can save to a copy.
		if ( ! $isNew && $canDo->get('core.create'))
		{
			$toolbarButtons[] = ['save2copy', 'customfield.save2copy'];
		}

		ToolbarHelper::saveGroup(
			$toolbarButtons,
			'btn-success'
		);

		if (empty($this->item->id))
		{
			ToolBarHelper::cancel('customfield.cancel', 'JTOOLBAR_CANCEL');
		}
		else
		{
			ToolBarHelper::cancel('customfield.cancel', 'JTOOLBAR_CLOSE');
		}	

		if (version_compare(JVERSION, '4.1.0', 'ge'))
		{
			ToolbarHelper::inlinehelp();
		}
	}
}
