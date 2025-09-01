<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.8.5 2022-05-10
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

namespace WebiC\Component\iCagenda\Administrator\View\Features;

\defined('_JEXEC') or die;

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\View\GenericDataException;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\CMS\Toolbar\Toolbar;
use Joomla\CMS\Toolbar\ToolbarHelper;
use WebiC\Component\iCagenda\Administrator\Extension\iCagendaComponent;
use WebiC\Component\iCagenda\Administrator\Helper\iCagendaHelper;

/**
 * Features HTML view class.
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
	 * @var  boolean
	 */
	private $isEmptyState = false;


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

		if ( ! $this->canDo->get('icagenda.access.features'))
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
		$user = Factory::getApplication()->getIdentity();

		// Get the toolbar object instance
		$toolbar = Toolbar::getInstance('toolbar');

		// Get Permissions
		$canDo = $this->canDo;

		// Prepare the toolbar
		ToolbarHelper::title('iCagenda - ' . Text::_('COM_ICAGENDA_TITLE_FEATURES'), 'iCicon-features');

		if ($canDo->get('core.create') || count($user->getAuthorisedCategories('com_icagenda', 'core.create')) > 0)
		{
			$toolbar->addNew('feature.add');
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
				$childBar->publish('features.publish')->listCheck(true);

				$childBar->unpublish('features.unpublish')->listCheck(true);

				$childBar->archive('features.archive')->listCheck(true);
			}

			if ($user->authorise('core.admin'))
			{
				$childBar->checkin('features.checkin')->listCheck(true);
			}

			if ($canDo->get('core.edit.state') && $this->state->get('filter.published') != -2)
			{
				$childBar->trash('features.trash')->listCheck(true);
			}
		}

		if (!$this->isEmptyState && $this->state->get('filter.state') == iCagendaComponent::CONDITION_TRASHED && $canDo->get('core.delete', 'com_icagenda'))
		{
			$toolbar->delete('features.delete')
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
