<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.8.19 2023-10-11
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

namespace WebiC\Component\iCagenda\Administrator\View\Events;

\defined('_JEXEC') or die;

use iClib\Date\Date as iCDate;
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
use WebiC\Component\iCagenda\Administrator\Extension\iCagendaComponent;
use WebiC\Component\iCagenda\Administrator\Helper\iCagendaHelper;

/**
 * Events HTML view class.
 */
class HtmlView extends BaseHtmlView
{
	/**
	 * An array of items
	 *
	 * @var  array
	 */
	protected $items;

	/**
	 * The pagination object
	 *
	 * @var  Pagination
	 */
	protected $pagination;

	/**
	 * The model state
	 *
	 * @var  object
	 */
	protected $state;

	/**
	 * The actions the user is authorised to perform
	 *
	 * @var  \JObject
	 */
	protected $canDo;

	/**
	 * Form object for search filters
	 *
	 * @var  \JForm
	 */
	public $filterForm;

	/**
	 * The active search filters
	 *
	 * @var  array
	 */
	public $activeFilters;

	/**
	 * Is this view an Empty State
	 *
	 * @var    boolean
	 * @since  3.8.0
	 */
	private $isEmptyState = false;

	/**
	 * The component params
	 *
	 * @var  object
	 */
	protected $params;

	/**
	 * At least one iCagenda category exists
	 *
	 * @var  object
	 */
	protected $iCategories;

	/**
	 * List of options for Upcoming (Events) Filter
	 *
	 * @var  object
	 */
	protected $upcoming;

	/**
	 * List of menu item itemid for iCagenda type List of Events
	 *
	 * @var  object
	 */
	protected $itemids;

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

		$this->items         = $this->get('Items');
		$this->pagination    = $this->get('Pagination');
		$this->state         = $this->get('State');
		$this->canDo         = iCagendaHelper::getActions();
		$this->filterForm    = $this->get('FilterForm');
		$this->activeFilters = $this->get('ActiveFilters');
		$this->iCategories   = icagendaCategories::getList('1');

		$this->params        = ComponentHelper::getParams('com_icagenda');
//		$this->categories    = $this->get('Categories');
//		$this->upcoming      = $this->get('Upcoming');
//		$this->itemids       = $this->get('MenuItemID');

		// Event Categories but no Events
		if ($this->iCategories
			&& (!isset($this->items) || (isset($this->items) && !\count((array) $this->items) && $this->isEmptyState = $this->get('IsEmptyState')))
			)
		{
			$this->setLayout('emptystate');
		}

		// No Event Categories and no Events
		if (!$this->iCategories
			&& (!isset($this->items) || (isset($this->items) && !\count((array) $this->items) && $this->isEmptyState = $this->get('IsEmptyState')))
			)
		{
			$app->enqueueMessage(Text::_('COM_ICAGENDA_ALERT_NO_CATEGORY_PUBLISHED'), 'warning' );
			$app->redirect(htmlspecialchars_decode('index.php?option=com_icagenda&view=categories'));
		}

		// Check for errors.
		if (\count($errors = $this->get('Errors')))
		{
			throw new GenericDataException(implode("\n", $errors), 500);
		}

		// Message if no category published.
		if (!$this->iCategories)
		{
			$app->enqueueMessage( Text::_('COM_ICAGENDA_ALERT_NO_CATEGORY_PUBLISHED')
								. '<br /><br /><a class="btn btn-success" href="index.php?option=com_icagenda&view=icategory&layout=edit" >'
								. Text::_('COM_ICAGENDA_LEGEND_NEW_CATEGORY') . '</a>'
								. ' <a class="btn btn-outline-info" href="index.php?option=com_icagenda&view=categories" >'
								. Text::_('ICCATEGORIES')
								. '</a>', 'warning' );
		}

		// We don't need toolbar in the modal window.
		if ($this->getLayout() !== 'modal')
		{
			$this->addToolbar();
		}

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
		$user = Factory::getApplication()->getIdentity();

		// Get the toolbar object instance
		$toolbar = Toolbar::getInstance('toolbar');

		// Get Permissions and Categories control
		$canDo       = $this->canDo;
		$iCategories = $this->iCategories;

		// Prepare the toolbar
		ToolBarHelper::title('iCagenda - ' . Text::_('COM_ICAGENDA_TITLE_EVENTS'), 'iCicon-events');

		if ($iCategories
			&& ($canDo->get('core.create') || count($user->getAuthorisedCategories('com_icagenda', 'core.create')) > 0)
			)
		{
			$toolbar->addNew('event.add');
		}

		if ($iCategories
			&& ($canDo->get('core.edit.state') || $user->authorise('core.admin'))
			)
		{
			$dropdown = $toolbar->dropdownButton('status-group')
				->text('JTOOLBAR_CHANGE_STATUS')
				->toggleSplit(false)
				->icon('icon-ellipsis-h')
				->buttonClass('btn btn-action')
				->listCheck(true);

			$childBar = $dropdown->getChildToolbar();

			if ($canDo->get('core.edit.state'))
			{
				$childBar->publish('events.publish')->listCheck(true);

				$childBar->unpublish('events.unpublish')->listCheck(true);

				$childBar->archive('events.archive')->listCheck(true);
			}

			if ($user->authorise('core.admin'))
			{
				$childBar->checkin('events.checkin')->listCheck(true);
			}

			if ($this->state->get('filter.published') != iCagendaComponent::CONDITION_TRASHED)
			{
				$childBar->trash('events.trash')->listCheck(true);
			}
		}

		if (!$this->isEmptyState && $this->state->get('filter.published') == iCagendaComponent::CONDITION_TRASHED && $canDo->get('core.delete', 'com_icagenda'))
		{
			$toolbar->delete('events.delete')
				->text('JTOOLBAR_EMPTY_TRASH')
				->message('JGLOBAL_CONFIRM_DELETE')
				->listCheck(true);
		}

		if ($canDo->get('core.admin', 'com_icagenda') || $canDo->get('core.options', 'com_icagenda'))
		{
			$toolbar->preferences('com_icagenda');
		}
	}
}
