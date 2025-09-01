<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.8.4 2022-04-13
 *
 * @package     iCagenda.Admin
 * @subpackage  src.Controller
 * @link        https://www.icagenda.com
 *
 * @author      Cyril Rezé
 * @copyright   (c) 2012-2024 Cyril Rezé / iCagenda. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 *
 * @since       3.8
 *----------------------------------------------------------------------------
*/

namespace WebiC\Component\iCagenda\Administrator\Controller;

\defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\MVC\Controller\BaseController;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Plugin\PluginHelper;

/**
 * iCagenda Component Controller
 */
class DisplayController extends BaseController
{
	/**
	 * The default view
	 *
	 * @var    string
	 */
	protected $default_view = 'icagenda';

	/**
	 * Method to display a view
	 *
	 * @param   boolean  $cachable   If true, the view output will be cached.
	 * @param   array    $urlparams  An array of safe URL parameters and their variable types, for valid values see {@link \JFilterInput::clean()}.
	 *
	 * @return  static|boolean  This object to support chaining.
	 */
	public function display($cachable = false, $urlparams = array())
	{
		// Get Application
		$app = Factory::getApplication();

		if (!PluginHelper::isEnabled('system', 'icagenda'))
		{
			$alert_message = Text::sprintf('COM_ICAGENDA_ERROR_PLUGIN_DISABLED', Text::_('COM_ICAGENDA_PLUGIN_SYSTEM_ICAGENDA'));

			$app->enqueueMessage($alert_message, 'error');

			echo Text::_('COM_ICAGENDA_ERROR_LOAD');

			return;
		}

		$view   = $this->input->get('view', 'icagenda');
		$layout = $this->input->get('layout', 'icagenda');
		$id     = $this->input->getInt('id');

		// Access check.
		if ( ! Factory::getUser()->authorise('core.manage', 'com_icagenda'))
		{
			$this->setMessage(Text::_('JERROR_ALERTNOAUTHOR'), 'error');

			return false;
		}

		// Check for edit form.
		if ($view == 'event' && $layout == 'edit' && !$this->checkEditId('com_icagenda.edit.event', $id))
		{
			// Somehow the person just went to the form - we don't allow that.
			if (!\count($this->app->getMessageQueue()))
			{
				$this->setMessage(Text::sprintf('JLIB_APPLICATION_ERROR_UNHELD_ID', $id), 'error');
			}

			$this->setRedirect(Route::_('index.php?option=com_icagenda&view=events', false));

			return false;
		}
		elseif ($view == 'iCategory' && $layout == 'edit' && !$this->checkEditId('com_icagenda.edit.category', $id))
		{
			// Somehow the person just went to the form - we don't allow that.
			if (!\count($this->app->getMessageQueue()))
			{
				$this->setMessage(Text::sprintf('JLIB_APPLICATION_ERROR_UNHELD_ID', $id), 'error');
			}

			$this->setRedirect(Route::_('index.php?option=com_icagenda&view=categories', false));

			return false;
		}
		elseif ($view == 'registration' && $layout == 'edit' && !$this->checkEditId('com_icagenda.edit.registration', $id))
		{
			// Somehow the person just went to the form - we don't allow that.
			if (!\count($this->app->getMessageQueue()))
			{
				$this->setMessage(Text::sprintf('JLIB_APPLICATION_ERROR_UNHELD_ID', $id), 'error');
			}

			$this->setRedirect(Route::_('index.php?option=com_icagenda&view=registrations', false));

			return false;
		}
		elseif ($view == 'customfield' && $layout == 'edit' && !$this->checkEditId('com_icagenda.edit.customfield', $id))
		{
			// Somehow the person just went to the form - we don't allow that.
			if (!\count($this->app->getMessageQueue()))
			{
				$this->setMessage(Text::sprintf('JLIB_APPLICATION_ERROR_UNHELD_ID', $id), 'error');
			}

			$this->setRedirect(Route::_('index.php?option=com_icagenda&view=customfields', false));

			return false;
		}
		elseif ($view == 'feature' && $layout == 'edit' && !$this->checkEditId('com_icagenda.edit.feature', $id))
		{
			// Somehow the person just went to the form - we don't allow that.
			if (!\count($this->app->getMessageQueue()))
			{
				$this->setMessage(Text::sprintf('JLIB_APPLICATION_ERROR_UNHELD_ID', $id), 'error');
			}

			$this->setRedirect(Route::_('index.php?option=com_icagenda&view=features', false));

			return false;
		}

		// Load translations
		$language = Factory::getLanguage();
		$language->load('com_icagenda', JPATH_ADMINISTRATOR, 'en-GB', true);
		$language->load('com_icagenda', JPATH_ADMINISTRATOR, null, true);

		// Load Vector iCicons Font
		HTMLHelper::_('stylesheet', 'media/com_icagenda/icicons/style.css');

		// Shared CSS
		HTMLHelper::_('stylesheet', 'com_icagenda/icagenda.css', array('relative' => true, 'version' => 'auto'));

		// CSS files which could be overridden into the site template. (eg. /templates/my_template/css/com_icagenda/icagenda-back.css)
		HTMLHelper::_('stylesheet', 'com_icagenda/icagenda-back.css', array('relative' => true, 'version' => 'auto'));

		// Check iCagenda System Errors
		$systemReady = $app->triggerEvent('onICagendaSystemCheck');

		if (\is_array($systemReady) && \count($systemReady) > 0 && \in_array(false, $systemReady, true) && $view != 'icagenda')
		{
			echo Text::_('COM_ICAGENDA_ERROR_LOAD');

			return;
		}

		return parent::display();
	}
}
