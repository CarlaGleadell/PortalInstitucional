<?php
/**
 *----------------------------------------------------------------------------
 * iC Library   Plugin System
 *----------------------------------------------------------------------------
 * @version     2.0.0 2024-02-23
 *
 * @package     iC Library
 * @subpackage  Plugin.System
 * @link        https://www.joomlic.com
 *
 * @author      Cyril Rezé
 * @copyright   (c) 2013-2024 Cyril Rezé / iCagenda. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 *
 * @since       1.0
 *----------------------------------------------------------------------------
*/

defined('_JEXEC') or die;

use Joomla\CMS\Component\ComponentHelper as JComponentHelper;
use Joomla\CMS\Factory as JFactory;
use Joomla\CMS\Form\FormHelper;

/**
 * iC Library plugin class.
 */
class plgSystemiC_library extends JPlugin
{
	/**
	 * Method to register iC library.
	 *
	 * return  void
	 */
	public function onAfterInitialise()
	{
		if (is_dir(JPATH_LIBRARIES . '/ic_library/lib')) {
			// Ensure that autoloaders are set
			\JLoader::setup();

			// Set iC Library prefix
			\JLoader::registerPrefix('iC', JPATH_LIBRARIES . '/ic_library/lib');

			// Set iC Library namespace
			\JLoader::registerNamespace('iClib', JPATH_LIBRARIES . '/ic_library', false, false, 'psr4');

			// Add field and rule prefixes
			JFormHelper::addFieldPath(JPATH_LIBRARIES . '/ic_library/lib/form/field');
			JFormHelper::addRulePath(JPATH_LIBRARIES . '/ic_library/lib/form/rule');

			// Test if translation is missing, set to en-GB by default
			$language = JFactory::getLanguage();
			$language->load('lib_ic_library', JPATH_SITE, 'en-GB', true);
			$language->load('lib_ic_library', JPATH_SITE, null, true);
		}
	}
}
