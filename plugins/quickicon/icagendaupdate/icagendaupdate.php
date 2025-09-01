<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Plugin Quick Icon - iCagenda Update Notification
 *----------------------------------------------------------------------------
 * @version     3.0.0 2023-11-02
 *
 * @package     iCagenda.Plugin
 * @subpackage  Quickicon.icagendaupdate
 * @link        https://www.icagenda.com
 *
 * @author      Cyril Rezé
 * @copyright   (c) 2012-2024 Cyril Rezé / iCagenda. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 *
 *  This program is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation, either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @since       iCagenda 3.5.16
 *----------------------------------------------------------------------------
*/

defined('_JEXEC') or die;

/**
 * iCagenda Quick Icon update notification plugin
 */
class PlgQuickiconiCagendaupdate extends JPlugin
{
	/**
	 * Load the language file on instantiation.
	 *
	 * @var    boolean
	 * @since  1.0
	 */
	protected $autoloadLanguage = true;

	/**
	 * This method is called when the Quick Icons module is constructing its set
	 * of icons. You can return an array which defines a single icon and it will
	 * be rendered right after the stock Quick Icons.
	 *
	 * @param  string  $context  The calling context
	 *
	 * @return array  A list of icon definition associative arrays, consisting of the
	 *                 keys link, image, text and access.
	 *
	 * @since  1.0
	 */
	public function onGetIcons($context)
	{
		if (version_compare(JVERSION, '3.0', 'lt'))
		{
			return;
		}
		else
		{
			if ($context !== $this->params->get('context', 'mod_quickicon') || !JFactory::getUser()->authorise('core.manage', 'com_installer'))
			{
				return;
			}

			JHtml::_('jquery.framework');

			// Load Vector iCicons Font
			JHtml::_('stylesheet', 'media/com_icagenda/icicons/style.css');

			$db = JFactory::getDbo();
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

			$alertMessage = JText::_('ICAGENDA_LIVEUPDATE_UPDATEFOUND_MESSAGE', true);

			$token    = JSession::getFormToken() . '=' . 1;
			$url      = JUri::base() . 'index.php?option=com_installer&view=update&filter_search=EID:' . $eid . '&' . $token;
			$ajax_url = JUri::base() . 'index.php?option=com_installer&view=update&task=update.ajax&' . $token;
			$script   = array();
			$script[] = 'var icagendaupdate_eid = \'' . $eid . '\';';
			$script[] = 'var icagendaupdate_url = \'' . $url . '\';';
			$script[] = 'var icagendaupdate_ajax_url = \'' . $ajax_url . '\';';
			$script[] = 'var icagendaupdate_text = {'
				. '"UPTODATE" : "' . JText::_('ICAGENDA_LIVEUPDATE_UPTODATE', true) . '",'
				. '"UPDATEFOUND": "<strong>' . JText::_('ICAGENDA_LIVEUPDATE_UPDATEFOUND', true) . '</strong>",'
				. '"UPDATEFOUND_MESSAGE": "' . $alertMessage . '",'
				. '"UPDATEFOUND_BUTTON": "' . JText::_('ICAGENDA_LIVEUPDATE_UPDATEFOUND_BUTTON', true) . '",'
				. '"ERROR": "' . JText::_('ICAGENDA_LIVEUPDATE_ERROR', true) . '",'
				. '};';
			JFactory::getDocument()->addScriptDeclaration(implode("\n", $script));
			JHtml::_('script', 'com_icagenda/icagendaupdatecheck.js', array('version' => 'auto', 'relative' => true));

			$script = array();
			$script[]= 'jQuery(document).ready(function(){';
			$script[]= '	jQuery(".icon-iclogo").addClass("iCicon-iclogo").removeClass("icon-iclogo");';
			$script[]= '	jQuery(".iCicon-iclogo").css("margin-right", "9px");';
			$script[]= '});';

			JFactory::getDocument()->addScriptDeclaration(implode("\n", $script));

			return array(
				array(
					'link'  => 'index.php?option=com_installer&view=update&filter_search=EID:' . $eid . '&' . $token,
					'image' => 'iclogo',
					'icon'  => 'header/icon-48-extension.png',
					'text'  => JText::_('ICAGENDA_LIVEUPDATE_CHECKING'),
					'id'    => 'icagendaupdate',
					'group' => 'MOD_QUICKICON_MAINTENANCE'
				)
			);
		}
	}
}
