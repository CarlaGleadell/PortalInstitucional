<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.8.0 2022-01-06
 *
 * @package     iCagenda.Admin
 * @subpackage  src.View
 * @link        https://www.icagenda.com
 *
 * @author      Cyril Rezé
 * @copyright   (c) 2012-2024 Cyril Rezé / iCagenda. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 *
 * @since       2.0
 *----------------------------------------------------------------------------
*/

namespace WebiC\Component\iCagenda\Administrator\View\Registrations;

\defined('_JEXEC') or die;

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\FileLayout;
use Joomla\CMS\MVC\View\GenericDataException;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Toolbar\Toolbar;
use Joomla\CMS\Toolbar\ToolbarHelper;
use WebiC\Component\iCagenda\Administrator\Extension\iCagendaComponent;
use WebiC\Component\iCagenda\Administrator\Helper\iCagendaHelper;

/**
 * Registrations HTML view class.
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
	 * List of options for Events form field
	 *
	 * @var  object
	 */
	protected $events;

	/**
	 * List of options for Dates form field
	 *
	 * @var  object
	 */
	protected $dates;

	/**
	 * Display the view
	 */
	public function display($tpl = null)
	{
		$this->items         = $this->get('Items');
		$this->pagination    = $this->get('Pagination');
		$this->state         = $this->get('State');
		$this->canDo         = iCagendaHelper::getActions();
		$this->filterForm    = $this->get('FilterForm');
		$this->activeFilters = $this->get('ActiveFilters');

		$this->params        = ComponentHelper::getParams('com_icagenda');
		$this->events        = $this->get('Events');
		$this->dates         = $this->get('Dates');

		if (!\count($this->items) && $this->isEmptyState = $this->get('IsEmptyState'))
		{
			$this->setLayout('emptystate');
		}

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			throw new GenericDataException(implode("\n", $errors), 500);
		}

		// We don't need toolbar in the modal window.
		if ($this->getLayout() !== 'modal')
		{
			$this->addToolbar();
		}

		if ( ! $this->canDo->get('icagenda.access.registrations'))
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
		$component = $this->state->get('filter.component');
		$user      = Factory::getApplication()->getIdentity();

		// Get the toolbar object instance
		$toolbar = Toolbar::getInstance('toolbar');

		// Avoid nonsense situation.
		if ($component == 'com_icagenda')
		{
			return;
		}

		// Get the results for each action.
		$canDo = $this->canDo;

		// Set Title
		ToolbarHelper::title('iCagenda - ' . Text::_('COM_ICAGENDA_TITLE_REGISTRATION'), 'iCicon-users');

		if ($canDo->get('core.create') || count($user->getAuthorisedCategories($component, 'core.create')) > 0)
		{
			$toolbar->addNew('registration.add');
		}

		if (($canDo->get('core.edit.state') || $user->authorise('core.admin')))
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
				$childBar->publish('registrations.publish')->listCheck(true);

				$childBar->unpublish('registrations.unpublish')->listCheck(true);

				$childBar->archive('registrations.archive')->listCheck(true);
			}

			if ($user->authorise('core.admin'))
			{
				$childBar->checkin('registrations.checkin')->listCheck(true);
			}

			if ($canDo->get('core.edit.state') && $this->state->get('filter.published') != -2)
			{
				$childBar->trash('registrations.trash')->listCheck(true);
			}
		}

		if (!$this->isEmptyState)
		{
			$toolbar->popupButton()
				->url(Route::_('index.php?option=com_icagenda&view=download&tmpl=component'))
				->text('JTOOLBAR_EXPORT')
				->selector('downloadModal')
				->icon('icon-download')
				->footer('<button class="btn btn-secondary" data-bs-dismiss="modal" type="button"'
					. ' onclick="window.parent.Joomla.Modal.getCurrent().close();">'
					. Text::_('COM_ICAGENDA_CANCEL') . '</button>'
					. '<button class="btn btn-success" type="button"'
					. ' onclick="Joomla.iframeButtonClick({iframeSelector: \'#downloadModal\', buttonSelector: \'#exportBtn\'})">'
					. Text::_('COM_ICAGENDA_REGISTRATIONS_EXPORT') . '</button>'
				);
		}

		if (!$this->isEmptyState && $this->state->get('filter.published') == iCagendaComponent::CONDITION_TRASHED && $canDo->get('core.delete', $component))
		{
			$toolbar->delete('registrations.delete')
				->text('JTOOLBAR_EMPTY_TRASH')
				->message('JGLOBAL_CONFIRM_DELETE')
				->listCheck(true);
		}

		if ($canDo->get('core.admin') || $canDo->get('core.options'))
		{
			$toolbar->preferences('com_icagenda');
		}
	}
}
