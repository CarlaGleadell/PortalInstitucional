<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.8.8 2022-08-02
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

namespace WebiC\Component\iCagenda\Administrator\View\Event;

\defined('_JEXEC') or die;

use iCutilities\Categories\Categories as icagendaCategories;
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
 * Event HTML view class.
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
	 * At least one iCagenda category exists
	 *
	 * @var  object
	 */
	protected $iCategories;

	/**
	 * Display the view.
	 *
	 * @param   string|null  $tpl  The name of the template file to parse; automatically searches through the template paths.
	 *
	 * @return  mixed  A string if successful, otherwise an Error object.
	 */
	public function display($tpl = null)
	{
		$app = Factory::getApplication();

		$this->form        = $this->get('Form');
		$this->item        = $this->get('Item');
		$this->state       = $this->get('State');
		$this->canDo       = iCagendaHelper::getActions();
		$this->iCategories = icagendaCategories::getList('1');

		// No Event Categories
		if ( ! $this->iCategories)
		{
			$app->enqueueMessage(Text::_('COM_ICAGENDA_ALERT_NO_CATEGORY_PUBLISHED'), 'warning' );
			$app->redirect(htmlspecialchars_decode('index.php?option=com_icagenda&view=categories'));
		}

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			throw new GenericDataException(implode("\n", $errors), 500);
		}

		$this->addToolbar();

		if ( ! $this->canDo->get('icagenda.access.events'))
		{
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
		ToolBarHelper::title($isNew ? 'iCagenda - ' . Text::_('COM_ICAGENDA_LEGEND_NEW_EVENT')  : 'iCagenda - ' . Text::_('COM_ICAGENDA_LEGEND_EDIT_EVENT'), $isNew ? 'new' : 'pencil-2');

		$toolbarButtons = [];

		// If not checked out, can save the item.
		if ( ! $checkedOut && ($canDo->get('core.edit')||($canDo->get('core.create'))))
		{
			ToolBarHelper::apply('event.apply', 'JTOOLBAR_APPLY');
			$toolbarButtons[] = ['save', 'event.save'];
		}

		if ( ! $checkedOut && ($canDo->get('core.create'))){
			$toolbarButtons[] = ['save2new', 'event.save2new'];
		}

		// If an existing item, can save to a copy.
		if ( ! $isNew && $canDo->get('core.create'))
		{
			$toolbarButtons[] = ['save2copy', 'event.save2copy'];
		}

		ToolbarHelper::saveGroup(
			$toolbarButtons,
			'btn-success'
		);

		if (empty($this->item->id))
		{
			ToolBarHelper::cancel('event.cancel', 'JTOOLBAR_CANCEL');
		}
		else
		{
			ToolBarHelper::cancel('event.cancel', 'JTOOLBAR_CLOSE');
		}	

		$app->triggerEvent('onICagendaToolBar', array('com_icagenda.event', $this->item));

		if (version_compare(JVERSION, '4.1.0', 'ge'))
		{
			ToolbarHelper::inlinehelp();
		}
	}
}
