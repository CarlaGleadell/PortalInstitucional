<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.8.13 2023-02-09
 *
 * @package     iCagenda.Admin
 * @subpackage  src.Controller
 * @link        https://www.icagenda.com
 *
 * @author      Cyril Rezé
 * @copyright   (c) 2012-2024 Cyril Rezé / iCagenda. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 *
 * @since       2.0.0
 *----------------------------------------------------------------------------
*/

namespace WebiC\Component\iCagenda\Administrator\Controller;

\defined('_JEXEC') or die;

use Joomla\CMS\Application\ApplicationHelper;
use Joomla\CMS\MVC\Controller\AdminController;

/**
 * iCagenda Component Registrations Controller
 */
class RegistrationsController extends AdminController
{
	/**
	 * The prefix to use with controller messages.
	 *
	 * @var    string
	 * @since  3.8.0
	 */
	protected $context = 'com_icagenda.registrations';

	/**
	 * Proxy for getModel.
	 *
	 * @param   string  $name    The model name. Optional.
	 * @param   string  $prefix  The class prefix. Optional.
	 * @param   array   $config  The array of possible config values. Optional.
	 *
	 * @return  JModelLegacy
	 */
	public function getModel($name = 'Registration', $prefix = 'Administrator', $config = array('ignore_request' => true))
	{
		return parent::getModel($name, $prefix, $config);
	}

	/**
	 * Display method for the raw track data.
	 *
	 * @param   boolean  $cachable   If true, the view output will be cached
	 * @param   array    $urlparams  An array of safe url parameters and their variable types, for valid values see {@link JFilterInput::clean()}.
	 *
	 * @return  static  This object to support chaining.
	 *
	 * @since   3.8.0
	 */
	public function display($cachable = false, $urlparams = array())
	{
		$vName = 'registrations';

		// Get and render the view.
		if ($view = $this->getView($vName, 'raw'))
		{
			// Check for request forgeries.
			$this->checkToken('GET');

			// Get the model for the view.
			/** @var \WebiC\Component\iCagenda\Administrator\Model\RegistrationsModel $model */
			$model = $this->getModel($vName);

			// Load the filter state.
			$app = $this->app;

			$search = $app->getUserState($this->context . '.filter.search');
			$model->setState('filter.search', $search);

			$registration_state = $app->getUserState($this->context . '.filter.registration_state');
			$model->setState('filter.registration_state', $registration_state);

			$published = $app->getUserState($this->context . '.filter.published', '1');
			$model->setState('filter.published', $published);

			$categoryId = $app->getUserState($this->context . '.filter.categories');
			$model->setState('filter.categories', $categoryId);

			$eventId = $app->getUserState($this->context . '.filter.events');
			$model->setState('filter.events', $eventId);

			$date = $app->getUserState($this->context . '.filter.dates');
			$model->setState('filter.dates', $date);

			$model->setState('list.limit', 0);
			$model->setState('list.start', 0);

			$form = $this->input->get('jform', array(), 'array');

			$model->setState('event_title', $form['event_title']);
			$model->setState('registration_state', $form['registration_state']);
			$model->setState('date', $form['date']);
			$model->setState('tickets', $form['tickets']);
			$model->setState('name', $form['name']);
			$model->setState('email', $form['email']);
			$model->setState('phone', $form['phone']);
			$model->setState('customfields', $form['customfields']);
			$model->setState('notes', $form['notes']);
			$model->setState('status', $form['status']);
			$model->setState('created', $form['created']);
			$model->setState('reg_id', $form['reg_id']);

			$model->setState('basename', $form['basename']);
			$model->setState('separator', $form['separator']);
			$model->setState('compressed', $form['compressed']);

			// Create one year cookies.
			$cookieLifeTime = time() + 365 * 86400;
			$cookieDomain   = $app->get('cookie_domain', '');
			$cookiePath     = $app->get('cookie_path', '/');
			$isHttpsForced  = $app->isHttpsForced();

			$this->input->cookie->set(
				ApplicationHelper::getHash($this->context . '.basename'),
				$form['basename'],
				$cookieLifeTime,
				$cookiePath,
				$cookieDomain,
				$isHttpsForced,
				true
			);

			$this->input->cookie->set(
				ApplicationHelper::getHash($this->context . '.compressed'),
				$form['compressed'],
				$cookieLifeTime,
				$cookiePath,
				$cookieDomain,
				$isHttpsForced,
				true
			);

			// Push the model into the view (as default).
			$view->setModel($model, true);

			// Push document object into the view.
			$view->document = $this->app->getDocument();

			$view->display();
		}

		return $this;
	}
}
