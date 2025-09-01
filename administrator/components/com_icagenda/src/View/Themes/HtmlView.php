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
 * @since       1.0
 *----------------------------------------------------------------------------
*/

namespace WebiC\Component\iCagenda\Administrator\View\Themes;

\defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\View\GenericDataException;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\CMS\Toolbar\Toolbar;
use Joomla\CMS\Toolbar\ToolbarHelper;
use WebiC\Component\iCagenda\Administrator\Helper\iCagendaHelper;

/**
 * Themes HTML view class.
 */
class HtmlView extends BaseHtmlView
{
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
		$this->canDo = iCagendaHelper::getActions();

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

		if ( ! $this->canDo->get('icagenda.access.themes'))
		{
			$app = Factory::getApplication();

			$app->enqueueMessage(Text::_('JERROR_ALERTNOAUTHOR'), 'warning');
			$app->redirect(htmlspecialchars_decode('index.php?option=com_icagenda&view=icagenda'));
		}

		parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 */
	protected function addToolbar()
	{
		// Get the toolbar object instance
		$toolbar = Toolbar::getInstance('toolbar');

		// Get the results for each action.
		$canDo = $this->canDo;

		// Set Title
		ToolbarHelper::title('iCagenda - ' . Text::_('COM_ICAGENDA_THEME_MANAGER'), 'iCicon-themes');

		if ($canDo->get('core.admin', 'com_icagenda') || $canDo->get('core.options', 'com_icagenda'))
		{
			$toolbar->preferences('com_icagenda');
		}
	}
}
