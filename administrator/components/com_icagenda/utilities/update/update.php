<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.8.21 2023-11-02
 *
 * @package     iCagenda.Admin
 * @subpackage  com_icagenda.Utilities
 * @link        https://www.icagenda.com
 *
 * @author      Cyril Rezé
 * @copyright   (c) 2012-2024 Cyril Rezé / iCagenda. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 *
 * @since       3.7.18
 *----------------------------------------------------------------------------
*/

defined('_JEXEC') or die;

/**
 * class icagendaUpdate
 */
class icagendaUpdate
{
	/**
	 * iCagenda Update Check
	 *
	 * @since  3.7.18
	 */
	static public function checkUpdate()
	{
		$db       = JFactory::getDbo();
		$document = JFactory::getDocument();

		JHtml::_('jquery.framework');
		JHtml::_('script', 'com_icagenda/icagendaupdatecheck.js', array('version' => 'auto', 'relative' => true));

		// Load Vector iCicons Font
		JHtml::_('stylesheet', 'media/com_icagenda/icicons/style.css');

		$query = $db
			->getQuery(true)
			->select('extension_id')
			->from($db->quoteName('#__extensions'))
			->where($db->quoteName('element') . " = " . $db->quote('pkg_icagenda'));
		$db->setQuery($query);
		$eid = $db->loadResult();

		if (empty($eid))
		{
			return;
		}

		$token    = JSession::getFormToken() . '=' . 1;
		$url      = JUri::base() . 'index.php?option=com_installer&view=update&filter_search=EID:' . $eid . '&' . $token;
		$ajax_url = JUri::base() . 'index.php?option=com_installer&view=update&task=update.ajax&' . $token;

		$script   = array();
		$script[] = 'var icagendaupdate_eid = \'' . $eid . '\';';
		$script[] = 'var icagendaupdate_url = \'' . $url . '\';';
		$script[] = 'var icagendaupdate_ajax_url = \'' . $ajax_url . '\';';
		$script[] = 'var icagendaupdate_text = {'
					. '"UPTODATE" : "<br />' . JText::_('ICAGENDA_LIVEUPDATE_UPTODATE', true) . '",'
					. '"UPDATEFOUND": "<br /><strong>' . JText::_('ICAGENDA_LIVEUPDATE_UPDATEFOUND', true) . '</strong>",'
					. '"UPDATEFOUND_MESSAGE": "' . JText::_('ICAGENDA_LIVEUPDATE_UPDATEFOUND_MESSAGE', true) . '",'
					. '"UPDATEFOUND_BUTTON": "' . JText::_('ICAGENDA_LIVEUPDATE_UPDATEFOUND_BUTTON', true) . '",'
					. '"ERROR": "<br />' . JText::_('ICAGENDA_LIVEUPDATE_ERROR', true) . '",'
					. '};';

		$document->addScriptDeclaration(implode("\n", $script));

		return array(
					'link'  => 'index.php?option=com_installer&view=update&filter_search=EID:' . $eid . '&' . $token,
					'image' => 'iCicon-iclogo',
					'text'  => '<br />' . JText::_('ICAGENDA_LIVEUPDATE_CHECKING'),
					'id'    => 'icagendaupdate',
		);
	}
}
