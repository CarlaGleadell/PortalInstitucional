<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.8.0 2021-10-17
 *
 * @package     iCagenda.Admin
 * @subpackage  src.Utilities.Router
 * @link        https://www.icagenda.com
 *
 * @author      Cyril Rezé
 * @copyright   (c) 2012-2024 Cyril Rezé / iCagenda. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 *
 * @since       3.8
 *----------------------------------------------------------------------------
*/

namespace iCutilities\Router;

\defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Filter\OutputFilter;
use Joomla\CMS\Language\Text;

/**
 * class icagendaRouter
 */
class Router
{
	/**
	 * Translate Alias
	 *
	 * @param   string  $segment  One piece of segment of the URL to parse
	 *
	 * @return  string  The segment localized.
	 */
	static public function translateSegment($key)
	{
		// Load translations
		$language = Factory::getLanguage();
		$language->load('com_icagenda', JPATH_SITE, 'en-GB', true);
		$language->load('com_icagenda', JPATH_SITE, null, true);

		$segment = Text::_('COM_ICAGENDA_ROUTE_SEGMENT_' . strtoupper($key));

		if ($segment !== 'COM_ICAGENDA_ROUTE_SEGMENT_' . strtoupper($key))
		{
			if (Factory::getConfig()->get('unicodeslugs') == 1)
			{
				$segment = OutputFilter::stringURLUnicodeSlug($segment);
			}
			else
			{
				$segment = OutputFilter::stringURLSafe($segment);
			}

			if (trim($segment) == '') $segment = $key;
		}
		else
		{
			$segment = $key;
		}

		return $segment;
	}
}
