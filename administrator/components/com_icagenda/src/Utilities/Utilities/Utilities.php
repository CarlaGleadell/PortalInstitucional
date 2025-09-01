<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.8.0 2021-09-29
 *
 * @package     iCagenda.Admin
 * @subpackage  src.Utilities.Utilities
 * @link        https://www.icagenda.com
 *
 * @author      Cyril Rezé
 * @copyright   (c) 2012-2024 Cyril Rezé / iCagenda. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 *
 * @since       3.4
 *----------------------------------------------------------------------------
*/

namespace iCutilities\Utilities;

\defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;

/**
 * class icagendaUtilities
 */
class Utilities
{
	/**
	 * Function to set an alert message if a class from Utilities is not loaded
	 */
	static public function isLoaded($class = null)
	{
		if ( ! class_exists($class) && $class)
		{
			$app = Factory::getApplication();

			$alert_message = Text::sprintf('ICAGENDA_CLASS_NOT_FOUND', '<strong>' . $class . '</strong>') . '<br />'
							. Text::_('ICAGENDA_IS_NOT_CORRECTLY_INSTALLED');

			// Get the message queue
			$messages = $app->getMessageQueue();

			$display_alert_message = false;

			// If we have messages
			if (is_array($messages) && count($messages))
			{
				// Check each message for the one we want
				foreach ($messages as $key => $value)
				{
					if ($value['message'] == $alert_message)
					{
						$display_alert_message = true;
					}
				}
			}

			if ( ! $display_alert_message)
			{
				$app->enqueueMessage($alert_message, 'error');
			}

			return false;
		}
		else
		{
			return true;
		}
	}
}
