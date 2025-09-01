<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.9.0 2024-02-17
 *
 * @package     iCagenda.Site
 * @subpackage  src.View
 * @link        https://www.icagenda.com
 *
 * @author      Cyril Rezé
 * @copyright   (c) 2012-2024 Cyril Rezé / iCagenda. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 *
 * @since       3.6
 *----------------------------------------------------------------------------
*/

namespace WebiC\Component\iCagenda\Site\View\Event;

\defined('_JEXEC') or die;

use iClib\Library\Library as iCLibrary;
use iCutilities\Event\Event as icagendaEvent;
use iCutilities\Info\Info as icagendaInfo;
use iCutilities\Maps\Maps as icagendaMaps;
use iCutilities\Theme\Theme as icagendaTheme;
use iCutilities\Theme\Style as icagendaThemeStyle;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\CMS\Plugin\PluginHelper;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Uri\Uri;

/**
 * HTML Event View class for the iCagenda component
 */
class HtmlView extends BaseHtmlView
{
	protected $item;

	protected $state;

	protected $user;

	protected $params;

	protected $eventShortcuts = 'components/com_icagenda/src/Assets/EventShortcuts.php';

	protected $print;

	/**
	 * Execute and display a template script.
	 *
	 * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
	 *
	 * @return  mixed  A string if successful, otherwise a Error object.
	 */
	public function display($tpl = null)
	{
		$app  = Factory::getApplication();
		$user = Factory::getUser();

		$this->item   = $this->get('Item');
		$this->state  = $this->get('State');
		$this->params = $this->state->get('params');
		$this->user   = $user;

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			throw new \Exception(implode("\n", $errors), 500);

			return false;
		}

		// Shortcuts
		$item   = $this->item;
		$params = $this->params;

		// For Dev.
		$time_loading = $params->get('time_loading', '');

		if ($time_loading) {
			$starttime_list = iCLibrary::getMicrotime();
		}

		// Check Access
		$userLevels = $user->getAuthorisedViewLevels();
		$userGroups = $user->getAuthorisedGroups();

		$groupid = ComponentHelper::getParams('com_icagenda')->get('approvalGroups', array("8"));
		$groupid = is_array($groupid) ? $groupid : array($groupid);

		$uri    = Uri::getInstance();
		$return = base64_encode($uri); // Encode Return URL
		$rlink  = Route::_("index.php?option=com_users&view=login&return=$return", false);

		if ( ! in_array('8', $userGroups)
			&& ( ! in_array($item->access, $userLevels)
			|| ($item->approval == 1 && ! array_intersect($userGroups, $groupid)) ) )
		{
			if ($user->id)
			{
				$app->enqueueMessage(Text::_('JERROR_LOGIN_DENIED'), 'warning');
			}
			else
			{
				$app->enqueueMessage(Text::_('JGLOBAL_YOU_MUST_LOGIN_FIRST'), 'info');
			}

			$app->redirect($rlink);
		}

		// Load Theme pack layout for event details view
		$this->template = $params->get('template', 'default');
		$themeEvent     = icagendaTheme::getThemeLayout($this->template, 'event');

		// Check if errors (file missing)
		if ($themeEvent[1])
		{
			$msg = ($themeEvent[1] !== 'deprecated')
					? 'iCagenda ' . Text::_('PHPMAILER_FILE_OPEN') . ' <strong>' . $this->template . '_event.php</strong>'
					: Text::_('COM_ICAGENDA_ERROR_THEME_PACK_OUTDATED') . '<br/>' .
						Text::sprintf('COM_ICAGENDA_ERROR_THEME_PACK_EDIT_OR_CHANGE', '<strong>' . $this->template . '_list.php</strong>');
			$app->enqueueMessage($msg, 'warning');

			if ($themeEvent[1] !== 'alert')
			{
				return false;
			}
		}

		$this->themeEvent = $themeEvent[0];


		// Component Options
		$this->GoogleMaps   = $params->get('GoogleMaps', 1);
		$this->iconPrint    = ($params->get('iconPrint_global', 0) == 2);
		$this->iconAddToCal = ($params->get('iconAddToCal_global', 0) == 2);


		// Increment the hit counter of the event.
		$model = $this->getModel();
		$model->hit();

		// Check vcal view (ics file for export to calendar)
		$vcal = $app->input->get('vcal');

		if ($vcal)
		{
			$tpl = 'vcal';
		}
		else
		{
			icagendaInfo::commentVersion();
		}


		// Content Object needs a "text" property
		$item->text = $item->desc;

		// Process the content plugins.
		PluginHelper::importPlugin('content');

//		$app->triggerEvent('onContentPrepare', array ('com_icagenda.event', &$item, &$this->params, '0'));

		$app->triggerEvent('onEventPrepare', array('com_icagenda.event', &$item, &$this->params)); // @deprecated. Kept for B/C
		$app->triggerEvent('iCagendaOnEventPrepare', array('com_icagenda.event', &$item, &$this->params));

		$item->event = new \stdClass;

		$results = $app->triggerEvent('iCagendaOnEventBeforeDisplay', array('com_icagenda.event', &$item, &$this->params));
		$item->event->iCagendaOnEventBeforeDisplay = trim(implode("\n", $results));

		$results = $app->triggerEvent('iCagendaOnEventAfterDisplay', array('com_icagenda.event', &$item, &$this->params));
		$item->event->iCagendaOnEventAfterDisplay = trim(implode("\n", $results));


		// Escape strings for HTML output
		$this->pageclass_sfx = htmlspecialchars($this->item->params->get('pageclass_sfx', ''));

		$this->_prepareDocument();

		parent::display($tpl);

		$app->triggerEvent('onEventAfterDisplay', array('com_icagenda.event', &$item, &$this->params)); // @deprecated. Kept for B/C

		// Google Maps api V3
		// @TODO: simplify this loading!
		if ( ! empty($item->lng)
			&& ! empty($item->lat)
			&& $item->lng != '0.0000000000000000'
			&& $item->lat != '0.0000000000000000'
			&& $this->GoogleMaps == 1)
		{
			icagendaMaps::loadGMapScripts();
		}

		// Add CSS
		icagendaTheme::loadComponentCSS($this->template);
		icagendaThemeStyle::addMediaCss($this->template, 'component');

		// For Dev.
		if ($time_loading)
		{
			$endtime_list = iCLibrary::getMicrotime();

			echo '<center style="font-size:8px;">Time to create page: ' . round($endtime_list-$starttime_list, 3) . ' seconds</center>';
		}
	}

	/**
	 * Prepares the document.
	 *
	 * @return  void.
	 */
	protected function _prepareDocument()
	{
		$app     = Factory::getApplication();
		$menus   = $app->getMenu();
		$pathway = $app->getPathway();
		$title   = null;

		// Because the application sets a default page title,
		// we need to get it from the menu item itself
		$menu = $menus->getActive();

		if ($menu)
		{
			$this->params->def('page_heading', $this->params->get('page_title', $menu->title));
		}
		else
		{
			$this->params->def('page_heading', Text::_('IC_EVENT'));
		}

		$title = $this->params->get('page_title', '');

		$id = (int) @$menu->query['id'];

		// If the menu item does not concern this event
		if ($menu && ($menu->query['option'] != 'com_icagenda' || $menu->query['view'] != 'event' || $id != $this->item->id))
		{
			// If this is not a single event menu item, set the page title to the event title
			if ($this->item->title)
			{
				$title = $this->item->title;
			}

			$pathway->addItem($this->item->title, '');
		}

		// Check for empty title and add site name if param is set
		if (empty($title))
		{
			$title = $app->get('sitename');
		}
		elseif ($app->get('sitename_pagetitles', 0) == 1)
		{
			$title = Text::sprintf('JPAGETITLE', $app->get('sitename'), $title);
		}
		elseif ($app->get('sitename_pagetitles', 0) == 2)
		{
			$title = Text::sprintf('JPAGETITLE', $title, $app->get('sitename'));
		}

		if (empty($title))
		{
			$title = $this->item->title;
		}

		$this->document->setTitle($title);

		if ($this->item->metadesc)
		{
			$this->document->setDescription($this->item->metadesc);
		}
		elseif ($this->params->get('menu-meta_description'))
		{
			$this->document->setDescription($this->params->get('menu-meta_description'));
		}

//		if ($this->item->metakey)
//		{
//			$this->document->setMetadata('keywords', $this->item->metakey);
//		}
//		elseif ($this->params->get('menu-meta_keywords'))
//		{
			$this->document->setMetadata('keywords', $this->params->get('menu-meta_keywords'));
//		}

		if ($this->params->get('robots'))
		{
			$this->document->setMetadata('robots', $this->params->get('robots'));
		}

//		if ($app->get('MetaAuthor') == '1')
//		{
//			$author = $this->item->created_by_alias ? $this->item->created_by_alias : $this->item->author;
//			$this->document->setMetaData('author', $author);
//		}

//		$mdata = $this->item->metadata->toArray();

//		foreach ($mdata as $k => $v)
//		{
//			if ($v)
//			{
//				$this->document->setMetadata($k, $v);
//			}
//		}

		// If there is a pagebreak heading or title, add it to the page title
		if ( ! empty($this->item->page_title))
		{
			$this->item->title = $this->item->title . ' - ' . $this->item->page_title;
			$this->document->setTitle(
				$this->item->page_title . ' - ' . Text::sprintf('PLG_CONTENT_PAGEBREAK_PAGE_NUM', $this->state->get('list.offset') + 1)
			);
		}

		if ($this->print)
		{
			$this->document->setMetaData('robots', 'noindex, nofollow');
		}

		// Open Graph Tags
		$eventTitle = icagendaEvent::setMetaTitle($this->item);
		$eventType  = 'website';
		$img        = HTMLHelper::cleanImageURL($this->item->image);
		$eventImage = $img->url;
		$imgLink    = filter_var($img->url, FILTER_VALIDATE_URL);
		$eventUrl   = Uri::getInstance()->toString();
		$sitename   = $app->getCfg('sitename');
		$og_desc    = icagendaEvent::setMetaDesc($this->item);

		if ($app->input->get('tmpl') != 'component') {
			if ($eventTitle) {
				$this->document->setTitle($title);
				$this->document->addCustomTag('<meta property="og:title" content="' . $eventTitle . '" />');
			}
			if ($eventType) {
				$this->document->addCustomTag('<meta property="og:type" content="' . $eventType . '" />');
			}

			if ($eventImage) {
				if (!$imgLink) {
					$eventImage = Uri::base() . $eventImage;
				}

				$this->document->addCustomTag('<meta property="og:image" content="' . $eventImage . '" />');
				$this->document->addCustomTag('<meta property="og:image:width" content="' . $img->attributes['width'] . '" />');
				$this->document->addCustomTag('<meta property="og:image:height" content="' . $img->attributes['height'] . '" />');
			}

			if ($eventUrl) {
				$this->document->addCustomTag('<meta property="og:url" content="' . $eventUrl . '" />');
			}

			if ($og_desc) {
				$this->document->setDescription($og_desc);
				$this->document->addCustomTag('<meta property="og:description" content="' . $og_desc . '" />');
			}

			if ($sitename) {
				$this->document->addCustomTag('<meta property="og:site_name" content="' . $sitename . '" />');
			}
		}
	}
}
