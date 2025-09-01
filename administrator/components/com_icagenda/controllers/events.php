<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.8.0 2019-07-19
 *
 * @package     iCagenda.Admin
 * @link        https://www.icagenda.com
 *
 * @author      Cyril Rezé
 * @copyright   (c) 2012-2024 Cyril Rezé / iCagenda. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 *
 * @since       1.0
 *----------------------------------------------------------------------------
*/

defined('_JEXEC') or die;

/**
 * Events list controller class.
 */
class iCagendaControllerEvents extends JControllerAdmin
{
	/**
	 * Proxy for getModel.
	 *
	 * @param   string  $name    The model name. Optional.
	 * @param   string  $prefix  The class prefix. Optional.
	 * @param   array   $config  The array of possible config values. Optional.
	 *
	 * @return  JModelLegacy
	 *
	 * @since   1.0
	 */
	public function getModel($name = 'Event', $prefix = 'iCagendaModel', $config = array('ignore_request' => true))
	{
		return parent::getModel($name, $prefix, $config);
	}

	/**
	 * Method to save the submitted ordering values for records via AJAX.
	 *
	 * @return  void
	 *
	 * @since   3.0
	 */
	public function saveOrderAjax()
	{
		$app   = JFactory::getApplication();
		$input = $app->input;

		$pks   = $input->post->get('cid', array(), 'array');
		$order = $input->post->get('order', array(), 'array');

		// Sanitize the input
		JArrayHelper::toInteger($pks);
		JArrayHelper::toInteger($order);

		// Get the model
		$model = $this->getModel();

		// Save the ordering
		$return = $model->saveorder($pks, $order);

		if ($return)
		{
			echo "1";
		}

		// Close the application
		$app->close();
	}

	public function __construct($config = array())
	{
		parent::__construct($config);

		$this->registerTask('unapprove', 'approve');
	}

	/**
	 * Method to approve an event.
	 *
	 * @return  void
	 *
	 * @since   3.2
	 */
	public function approve()
	{
		// Check for request forgeries.
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		$app   = JFactory::getApplication();
		$input = $app->input;

		$ids   = $input->post->get('cid', array(), 'array');

		if (empty($ids))
		{
			$app->enqueueMessage(JText::_('JERROR_NO_ITEMS_SELECTED'), 'warning');
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
				$this->setMessage(JText::plural('COM_ICAGENDA_N_EVENTS_APPROVED', count($ids)));
			}
		}

		$this->setRedirect('index.php?option=com_icagenda&view=events');
	}
}
