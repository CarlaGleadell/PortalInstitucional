<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.8.0 2021-09-26
 *
 * @package     iCagenda.Admin
 * @subpackage  src.Controller
 * @link        https://www.icagenda.com
 *
 * @author      Cyril Rezé
 * @copyright   (c) 2012-2024 Cyril Rezé / iCagenda. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 *
 * @since       1.0
 *----------------------------------------------------------------------------
*/

namespace WebiC\Component\iCagenda\Administrator\Controller;

\defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\Controller\AdminController;
use Joomla\CMS\MVC\Factory\MVCFactoryInterface;
use Joomla\CMS\Session\Session;

/**
 * iCagenda Component Events Controller
 */
class EventsController extends AdminController
{
	/**
	 * Constructor
	 *
	 * @param   array                $config   An optional associative array of configuration settings.
	 * Recognized key values include 'name', 'default_task', 'model_path', and
	 * 'view_path' (this list is not meant to be comprehensive).
	 * @param   MVCFactoryInterface  $factory  The factory.
	 * @param   CMSApplication       $app      The JApplication for the dispatcher
	 * @param   Input                $input    Input
	 */
	public function __construct($config = array(), MVCFactoryInterface $factory = null, $app = null, $input = null)
	{
		parent::__construct($config, $factory, $app, $input);

		$this->registerTask('unapprove', 'approve');
	}

	/**
	 * Proxy for getModel.
	 *
	 * @param   string  $name    The model name. Optional.
	 * @param   string  $prefix  The class prefix. Optional.
	 * @param   array   $config  The array of possible config values. Optional.
	 *
	 * @return  \Joomla\CMS\MVC\Model\BaseDatabaseModel  The model.
	 */
	public function getModel($name = 'Event', $prefix = 'Administrator', $config = array('ignore_request' => true))
	{
		return parent::getModel($name, $prefix, $config);
	}

	/**
	 * Method to approve an event.
	 *
	 * @return  void
	 *
	 * @since   3.2.0
	 */
	public function approve()
	{
		// Check for request forgeries.
		$this->checkToken('request');

		$app   = Factory::getApplication();
		$input = $app->input;

		$ids   = $input->post->get('cid', array(), 'array');

		if (empty($ids))
		{
			$app->enqueueMessage(Text::_('JERROR_NO_ITEMS_SELECTED'), 'warning');
		}
		else
		{
			// Get the model.
			$model = $this->getModel();

			// Change the state of the records.
			if ( ! $model->approve($ids))
			{
				$app->enqueueMessage($model->getError(), 'warning');
			}
			else
			{
				$this->setMessage(Text::plural('COM_ICAGENDA_N_EVENTS_APPROVED', count($ids)));
			}
		}

		$this->setRedirect('index.php?option=com_icagenda&view=events');
	}
}
