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
 * @since       1.0
 *----------------------------------------------------------------------------
*/

namespace WebiC\Component\iCagenda\Administrator\View\Icategory;

\defined('_JEXEC') or die;

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Helper\ContentHelper;
use Joomla\CMS\Language\Multilanguage;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\View\GenericDataException;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\CMS\Pagination\Pagination;
use Joomla\CMS\Toolbar\Toolbar;
use Joomla\CMS\Toolbar\ToolbarHelper;
use WebiC\Component\iCagenda\Administrator\Helper\iCagendaHelper;

/**
 * Icategory HTML view class.
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
	 * Display the view.
	 *
	 * @param   string|null  $tpl  The name of the template file to parse; automatically searches through the template paths.
	 *
	 * @return  mixed  A string if successful, otherwise an Error object.
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

		if ( ! $this->canDo->get('icagenda.access.categories'))
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
		$app  = Factory::getApplication();
		$user = Factory::getUser();

		$app->input->set('hidemainmenu', true);

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
		ToolBarHelper::title($isNew ? 'iCagenda - ' . Text::_('COM_ICAGENDA_LEGEND_NEW_CATEGORY')  : 'iCagenda - ' . Text::_('COM_ICAGENDA_LEGEND_EDIT_CATEGORY'), $isNew ? 'new' : 'pencil-2');

		$toolbarButtons = [];

		// If not checked out, can save the item.
		if ( ! $checkedOut && ($canDo->get('core.edit')||($canDo->get('core.create'))))
		{
			ToolBarHelper::apply('icategory.apply', 'JTOOLBAR_APPLY');
			$toolbarButtons[] = ['save', 'icategory.save'];
		}

		if ( ! $checkedOut && ($canDo->get('core.create'))){
			$toolbarButtons[] = ['save2new', 'icategory.save2new'];
		}

		// If an existing item, can save to a copy.
		if ( ! $isNew && $canDo->get('core.create'))
		{
			$toolbarButtons[] = ['save2copy', 'icategory.save2copy'];
		}

		ToolbarHelper::saveGroup(
			$toolbarButtons,
			'btn-success'
		);

		if (empty($this->item->id))
		{
			ToolBarHelper::cancel('icategory.cancel', 'JTOOLBAR_CANCEL');
		}
		else
		{
			ToolBarHelper::cancel('icategory.cancel', 'JTOOLBAR_CLOSE');
		}	

		$app->triggerEvent('onICagendaToolBar', array('com_icagenda.icategory', $this->item));

		if (version_compare(JVERSION, '4.1.0', 'ge'))
		{
			ToolbarHelper::inlinehelp();
		}
	}
}
