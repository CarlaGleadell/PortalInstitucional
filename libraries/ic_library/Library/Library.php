<?php
/**
 *----------------------------------------------------------------------------
 * iC Library   Library by Jooml!C, for Joomla!
 *----------------------------------------------------------------------------
 * @version     2.2.0 2024-02-19
 *
 * @package     iC Library
 * @subpackage  Library
 * @link        https://www.joomlic.com
 *
 * @author      Cyril Rezé
 * @copyright   (c) 2013-2024 Cyril Rezé / iCagenda. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 *
 * @since       1.0
 *----------------------------------------------------------------------------
*/

namespace iClib\Library;

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;

// phpcs:disable PSR1.Files.SideEffects
\defined('JPATH_PLATFORM') or die;
// phpcs:enable PSR1.Files.SideEffects

/**
 * class iCLibrary
 */
class Library
{
	/**
	 * Function to set an alert message if a class from Utilities is not loaded
	 */
	public static function isLoaded($class = null)
	{
		if (!class_exists($class) && $class) {
			$app = Factory::getApplication();

			$alert_message = Text::sprintf('ICAGENDA_CLASS_NOT_FOUND', '<strong>' . $class . '</strong>') . '<br />'
				. Text::_('ICAGENDA_IS_NOT_CORRECTLY_INSTALLED');

			// Get the message queue
			$messages = $app->getMessageQueue();

			$display_alert_message = false;

			// If we have messages
			if (\is_array($messages) && \count($messages)) {
				// Check each message for the one we want
				foreach ($messages as $key => $value) {
					if ($value['message'] == $alert_message) {
						$display_alert_message = true;
					}
				}
			}

			if (!$display_alert_message) {
				$app->enqueueMessage($alert_message, 'error');
			}

			return false;
		}

		return true;
	}

	/**
	 * Function to get microtime
	 *
	 * @since   1.3
	 */
	public static function getMicrotime()
	{
		list($usec_cal, $sec_cal) = explode(" ",microtime());

		return ((float)$usec_cal + (float)$sec_cal);
	}
}
