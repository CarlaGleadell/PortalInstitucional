<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Plugin Quickicon - iCagenda Update Notification
 *----------------------------------------------------------------------------
 * @version     2.0.0 2024-02-14
 *
 * @package     iCagenda
 * @subpackage  Plugin.Quickicon.Icagenda
 * @link        https://www.icagenda.com
 *
 * @author      Cyril Rezé
 * @copyright   (c) 2013-2024 Cyril Rezé / iCagenda. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 *
 * @since       iCagenda 3.5.16
 *----------------------------------------------------------------------------
*/

namespace W3biC\Plugin\Quickicon\Icagendaupdate\Extension;

use Joomla\CMS\Document\Document;
use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Plugin\CMSPlugin;
use Joomla\CMS\Session\Session;
use Joomla\CMS\Uri\Uri;
use Joomla\Event\DispatcherInterface;
use Joomla\Event\SubscriberInterface;
use Joomla\Module\Quickicon\Administrator\Event\QuickIconsEvent;

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

/**
 * Privacy Icagenda Plugin.
 */
final class Icagendaupdate extends CMSPlugin implements SubscriberInterface
{
	/**
	 * Affects constructor behaviour. If true, language files will be loaded automatically.
	 *
	 * @var    boolean
	 */
	protected $autoloadLanguage = true;

	/**
	 * The document.
	 *
	 * @var Document
	 */
	private $document;

	/**
	 * Returns an array of events this subscriber will listen to.
	 *
	 * @return  array
	 *
	 * @since   4.0.0
	 */
	public static function getSubscribedEvents(): array
	{
		return [
			'onGetIcons' => 'getICagendaUpdateNotification',
		];
	}

	/**
	 * Constructor
	 *
	 * @param   DispatcherInterface  $subject   The object to observe
	 * @param   Document             $document  The document
	 * @param   array                $config    An optional associative array of configuration settings.
	 *                                          Recognized key values include 'name', 'group', 'params', 'language'
	 *                                          (this list is not meant to be comprehensive).
	 */
	public function __construct(DispatcherInterface $subject, Document $document, $config = [])
	{
		parent::__construct($subject, $config);

		$this->document = $document;
	}

	/**
	 * This method is called when the Quick Icons module is constructing its set
	 * of icons. You can return an array which defines a single icon and it will
	 * be rendered right after the stock Quick Icons.
	 *
	 * @param   QuickIconsEvent  $event  The event object
	 *
	 * @return  void
	 */
	public function getICagendaUpdateNotification(QuickIconsEvent $event)
	{
		// No longer used for icagenda update notification in the administration dashboard (For all versions of Joomla after 4.0).
		if (version_compare(JVERSION, '4.0', 'ge')) {
			return;
		}

		$context = $event->getContext();

		if (version_compare(JVERSION, '5.0', 'lt')) {
			if ($context !== $this->params->get('context', 'mod_quickicon')
				|| !$this->getApplication()->getIdentity()->authorise('core.manage', 'com_installer')
			) {
				return;
			}
		} else {
			if ($context !== $this->params->get('context', 'update_quickicon')
				|| !$this->getApplication()->getIdentity()->authorise('core.manage', 'com_installer')
			) {
				return;
			}
		}

		HTMLHelper::_('jquery.framework');

		// Load Vector iCicons Font
		HTMLHelper::_('stylesheet', 'media/com_icagenda/icicons/style.css');

		$db = Factory::getDbo();
		$query = $db
			->getQuery(true)
			->select('extension_id')
			->from($db->quoteName('#__extensions'))
			->where($db->quoteName('element') . " = " . $db->quote('pkg_icagenda'));

		$db->setQuery($query);
		$eid = $db->loadResult();

		if (empty($eid)) {
			return;
		}

		$alertMessage = Text::_('ICAGENDA_LIVEUPDATE_UPDATEFOUND_MESSAGE', true);

		$token    = Session::getFormToken() . '=' . 1;
		$url      = Uri::base() . 'index.php?option=com_installer&view=update&filter_search=EID:' . $eid . '&' . $token;
		$ajax_url = Uri::base() . 'index.php?option=com_installer&view=update&task=update.ajax&' . $token;

		$this->document->addScriptDeclaration('
			var icagendaupdate_eid = "' . $eid . '";
			var icagendaupdate_url = "' . $url . '";
			var icagendaupdate_ajax_url = "' . $ajax_url . '";
			var icagendaupdate_text = {
				"UPTODATE" : "' . Text::_('ICAGENDA_LIVEUPDATE_UPTODATE', true) . '",
				"UPDATEFOUND": "<strong>' . Text::_('ICAGENDA_LIVEUPDATE_UPDATEFOUND', true) . '</strong>",
				"UPDATEFOUND_MESSAGE": "' . $alertMessage . '",
				"UPDATEFOUND_BUTTON": "' . Text::_('ICAGENDA_LIVEUPDATE_UPDATEFOUND_BUTTON', true) . '",
				"ERROR": "' . Text::_('ICAGENDA_LIVEUPDATE_ERROR', true) . '",
			};
		');

		$this->document->getWebAssetManager()
			->registerAndUseScript('com_icagenda', 'com_icagenda/icagendaupdatecheck.js', [], ['defer' => true])
			->registerAndUseStyle('iCicons', 'media/com_icagenda/icicons/style.css');

		$this->document->addScriptDeclaration('
			document.addEventListener("DOMContentLoaded", function() {
				iclogo = document.getElementsByClassName("icon-iclogo");
				iclogo.classList.add("iCicon-iclogo");
				iclogo.classList.add("me-3");
				iclogo.classList.remove("icon-iclogo");
			});
		');

		// Add the icon to the result array
		$result = $event->getArgument('result', []);

		$result[] = [
			[
				'link'  => 'index.php?option=com_installer&view=update&filter_search=EID:' . $eid . '&' . $token,
				'image' => 'iCicon-iclogo',
				'icon'  => 'header/icon-48-extension.png',
				'text'  => Text::_('ICAGENDA_LIVEUPDATE_CHECKING'),
				'id'    => 'icagendaupdate',
				'group' => 'MOD_QUICKICON_MAINTENANCE'
			],
		];

		$event->setArgument('result', $result);
	}
}
