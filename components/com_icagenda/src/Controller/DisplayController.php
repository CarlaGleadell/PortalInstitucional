<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.8.4 2022-04-13
 *
 * @package     iCagenda.Site
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

namespace WebiC\Component\iCagenda\Site\Controller;

\defined('_JEXEC') or die;

//use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\Controller\BaseController;
use Joomla\CMS\MVC\Factory\MVCFactoryInterface;
use Joomla\CMS\Plugin\PluginHelper;

/**
 * iCagenda Component Controller
 */
class DisplayController extends BaseController
{
	/**
	 * Method to display a view.
	 *
	 * @param   boolean  $cachable   If true, the view output will be cached.
	 * @param   boolean  $urlparams  An array of safe URL parameters and their variable types, for valid values see {@link JFilterInput::clean()}.
	 *
	 * @return  \Joomla\CMS\MVC\Controller\BaseController  This object to support chaining.
	 */
	public function display($cachable = false, $urlparams = false)
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

		$cachable = true;

		/**
		 * Set the default view name and format from the Request.
		 */
		$vName = $this->input->getCmd('view', 'events');
		$this->input->set('view', $vName);

		$user = Factory::getUser();

		if ($user->get('id')
			|| ($vName === 'manager')
			|| ($vName === 'registration')
			|| ($vName === 'submit')
			|| ($this->input->getMethod() === 'POST' && $vName === 'events'))
		{
			$cachable = false;
		}

		$safeurlparams = array(
			'catid'           => 'INT',
			'id'              => 'INT',
			'date'            => 'CMD',
			'page'            => 'INT',
			'year'            => 'INT',
			'month'           => 'INT',
			'return'          => 'BASE64',
			'print'           => 'BOOLEAN',
			'lang'            => 'CMD',
			'Itemid'          => 'INT',
			'filter_search'   => 'STRING',
			'filter_from'     => 'CMD',
			'filter_to'       => 'CMD',
			'filter_category' => 'INT',
			'filter_month'    => 'INT',
			'filter_year'     => 'INT',
		);

		$event_id = $this->input->getInt('event_id');

		// Check for edit form.
		if ($vName === 'manager' && $this->input->get('layout') === 'event_edit' && !$this->checkEditId('com_icagenda.edit.event', $event_id))
		{
			// Somehow the person just went to the form - we don't allow that.
			throw new \Exception(Text::sprintf('JLIB_APPLICATION_ERROR_UNHELD_ID', $event_id), 403);
		}

//		if ($vName === 'event')
//		{
			// Get/Create the model
//			if ($model = $this->getModel($vName))
//			{
//				if (ComponentHelper::getParams('com_icagenda')->get('record_hits', 1) == 1)
//				{
//					$model->hit();
//				}
//			}
//		}

		// Load translations
		$language = Factory::getLanguage();
		$language->load('com_icagenda', JPATH_SITE, 'en-GB', true);
		$language->load('com_icagenda', JPATH_SITE, null, true);

		// Load Vector iCicons Font
		HTMLHelper::_('stylesheet', 'media/com_icagenda/icicons/style.css');

		// Shared CSS
		HTMLHelper::_('stylesheet', 'com_icagenda/icagenda.css', array('relative' => true, 'version' => 'auto'));

		// CSS files which could be overridden into the site template. (eg. /templates/my_template/css/com_icagenda/icagenda-front.css)
		HTMLHelper::_('stylesheet', 'com_icagenda/icagenda-front.css', array('relative' => true, 'version' => 'auto'));

		// Check iCagenda System Errors
		$systemReady = $app->triggerEvent('onICagendaSystemCheck');

		if (\is_array($systemReady) && \count($systemReady) > 0 && \in_array(false, $systemReady, true))
		{
			echo Text::_('COM_ICAGENDA_ERROR_LOAD');

			return;
		}

		parent::display($cachable, $safeurlparams);

		return $this;
	}
}
