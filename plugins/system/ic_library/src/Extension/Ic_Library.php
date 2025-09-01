<?php
/**
 *----------------------------------------------------------------------------
 * iC Library   Plugin System
 *----------------------------------------------------------------------------
 * @version     2.0.0 2024-02-17
 *
 * @package     iC Library
 * @subpackage  Plugin.System
 * @link        https://www.joomlic.com
 *
 * @author      Cyril Rezé
 * @copyright   (c) 2013-2024 Cyril Rezé / JoomliC. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 *
 * @since       1.0
 *----------------------------------------------------------------------------
*/

namespace W3biC\Plugin\System\Ic_Library\Extension;

use Joomla\CMS\Factory;
use Joomla\CMS\Form\FormHelper;
use Joomla\CMS\Plugin\CMSPlugin;
use Joomla\Database\DatabaseAwareTrait;

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

/**
 * iC Library System Plugin
 */
final class Ic_Library extends CMSPlugin
{
	use DatabaseAwareTrait;

	/**
	 * Load plugin language files automatically
	 *
	 * @var    boolean
	 * @since  2.0
	 */
	protected $autoloadLanguage = true;

	/**
	 * Method to register iC library.
	 *
	 * return  void
	 */
	public function onAfterInitialise()
	{
		if (is_dir(JPATH_LIBRARIES . '/ic_library')) {
			// Ensure that autoloaders are set
			\JLoader::setup();

			// Set iC Library namespace
			\JLoader::registerNamespace('iClib', JPATH_LIBRARIES . '/ic_library', false, false);

			// Add Field and Rule prefixes
			FormHelper::addFieldPrefix('iClib\Field');
			FormHelper::addRulePrefix('iClib\Rule');

			// Test if translation is missing, set to en-GB by default
			$language = Factory::getLanguage();
			$language->load('lib_ic_library', JPATH_SITE, 'en-GB', true);
			$language->load('lib_ic_library', JPATH_SITE, null, true);
		}
	}
}
