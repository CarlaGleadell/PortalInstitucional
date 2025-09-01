<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.9.0 2024-02-24
 *
 * @package     iCagenda.Admin
 * @subpackage  src.Utilities.Update
 * @link        https://www.icagenda.com
 *
 * @author      Cyril Rezé
 * @copyright   (c) 2012-2024 Cyril Rezé / iCagenda. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 *
 * @since       3.7.18
 *----------------------------------------------------------------------------
*/

namespace iCutilities\Update;

\defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Session\Session;
use Joomla\CMS\Uri\Uri;

/**
 * class icagendaUpdate
 */
class icagendaUpdate
{
	/**
	 * iCagenda Update Check
	 */
	static public function checkUpdate()
	{
		$db       = Factory::getDbo();
		$document = Factory::getDocument();

		HTMLHelper::_('jquery.framework');
		HTMLHelper::_('script', 'com_icagenda/icagendaupdatecheck.js', array('version' => 'auto', 'relative' => true));

		// Load Vector iCicons Font
		HTMLHelper::_('stylesheet', 'media/com_icagenda/icicons/style.css');

		$query = $db
			->getQuery(true)
			->select('extension_id')
			->from($db->quoteName('#__extensions'))
			->where($db->quoteName('element') . " = " . $db->quote('pkg_icagenda'));
		$db->setQuery($query);
		$eid = $db->loadResult();

		if (empty($eid)) {
			return [];
		}

		$token    = Session::getFormToken() . '=' . 1;
		$url      = Uri::base() . 'index.php?option=com_installer&view=update&filter_search=EID:' . $eid . '&' . $token;
		$ajax_url = Uri::base() . 'index.php?option=com_installer&view=update&task=update.ajax&' . $token;

		$script   = array();
		$script[] = 'var icagendaupdate_eid = \'' . $eid . '\';';
		$script[] = 'var icagendaupdate_url = \'' . $url . '\';';
		$script[] = 'var icagendaupdate_ajax_url = \'' . $ajax_url . '\';';
		$script[] = 'var icagendaupdate_text = {'
					. '"UPTODATE" : "<br /><div class=\"icpanel-icon-text ic-bg-green\">' . Text::_('ICAGENDA_LIVEUPDATE_UPTODATE', true) . '</div>",'
					. '"UPDATEFOUND": "<br /><div class=\"icpanel-icon-text ic-bg-red\"><strong>' . Text::_('ICAGENDA_LIVEUPDATE_UPDATEFOUND', true) . '</strong></div>",'
					. '"UPDATEFOUND_MESSAGE": "' . Text::_('ICAGENDA_LIVEUPDATE_UPDATEFOUND_MESSAGE', true) . '",'
					. '"UPDATEFOUND_BUTTON": "' . Text::_('ICAGENDA_LIVEUPDATE_UPDATEFOUND_BUTTON', true) . '",'
					. '"ERROR": "<br />' . Text::_('ICAGENDA_LIVEUPDATE_ERROR', true) . '",'
					. '};';

		$document->addScriptDeclaration(implode("\n", $script));

		return [
			'link'    => 'index.php?option=com_installer&view=update&filter_search=EID:' . $eid . '&' . $token,
			'image'   => 'iCicon-iclogo',
			'text'    => '<div class="icpanel-icon-text-container ic-bg-blue"><div class="icpanel-icon-text">' . Text::_('ICAGENDA_LIVEUPDATE_CHECKING') . '</div></div>',
			'title'   => 'iCagenda Updater',
			'target'  => '',
			'onclick' => '',
			'id'      => 'icagendaupdate',
		];
	}
}
