<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.8.0 2020-01-16
 *
 * @package     iCagenda.Site
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
 * Controller class for iCagenda.
 */
class iCagendaController extends JControllerLegacy
{
	public function display($cachable = false, $urlparams = false)
	{
		$params   = JComponentHelper::getParams('com_icagenda');
		$cache    = $params->get('enable_cache', 0);
		$cachable = false;

		if ($cache == 1)
		{
			$cachable = true;
		}

		$document = JFactory::getDocument();

		$safeurlparams = array(
			'catid'  => 'INT',
			'id'     => 'INT',
			'date'   => 'STRING',
			'page'   => 'INT',
			'year'   => 'INT',
			'month'  => 'INT',
			'return' => 'BASE64',
			'print'  => 'BOOLEAN',
			'lang'   => 'CMD',
			'Itemid' => 'INT',
		);

		parent::display($cachable, $safeurlparams);

		return $this;
	}
}
