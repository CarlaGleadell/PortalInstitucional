<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.9.0 2023-10-26
 *
 * @package     iCagenda.Site
 * @link        https://www.icagenda.com
 *
 * @author      Cyril Rezé
 * @copyright   (c) 2012-2024 Cyril Rezé / iCagenda. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 *
 * @since       3.6
 *----------------------------------------------------------------------------
*/

defined('_JEXEC') or die;

/**
 * HTML Event View class for the iCagenda component
 */
class iCagendaViewEvent extends JViewLegacy
{
	protected $item;

	protected $state;

	protected $user;

	protected $params;

	protected $icevent_vars = 'components/com_icagenda/add/elements/icevent_vars.php';

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
		$app        = JFactory::getApplication();
		$user       = JFactory::getUser();
		$dispatcher = JEventDispatcher::getInstance();

		$this->item   = $this->get('Item');
		$this->state  = $this->get('State');
		$this->params = $this->state->get('params');
		$this->user   = $user;

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			throw new Exception(implode("\n", $errors), 500);

			return false;
		}

		// Shortcuts
		$item   = $this->item;
		$params = $this->params;

		// For Dev.
		$time_loading = $params->get('time_loading', '');

		if ($time_loading)
		{
			$starttime_list = iCLibrary::getMicrotime();
		}

		// Check Access
		$userLevels = $user->getAuthorisedViewLevels();
		$userGroups = $user->getAuthorisedGroups();

		$groupid = JComponentHelper::getParams('com_icagenda')->get('approvalGroups', array("8"));
		$groupid = is_array($groupid) ? $groupid : array($groupid);

		$uri    = JFactory::getUri();
		$return = base64_encode($uri); // Encode Return URL
		$rlink  = JRoute::_("index.php?option=com_users&view=login&return=$return", false);

		if ( ! in_array('8', $userGroups)
			&& ( ! in_array($item->access, $userLevels)
			|| ($item->approval == 1 && ! array_intersect($userGroups, $groupid)) ) )
		{
			if ($user->id)
			{
				$app->enqueueMessage(JText::_('JERROR_LOGIN_DENIED'), 'warning');
			}
			else
			{
				$app->enqueueMessage(JText::_('JGLOBAL_YOU_MUST_LOGIN_FIRST'), 'info');
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
					? 'iCagenda ' . JText::_('PHPMAILER_FILE_OPEN') . ' <strong>' . $this->template . '_event.php</strong>'
					: JText::_('COM_ICAGENDA_ERROR_THEME_PACK_OUTDATED') . '<br/>' .
						JText::sprintf('COM_ICAGENDA_ERROR_THEME_PACK_EDIT_OR_CHANGE', '<strong>' . $this->template . '_list.php</strong>');
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
		JPluginHelper::importPlugin('content');

		$dispatcher->trigger('onEventPrepare', array('com_icagenda.event', &$item, &$this->params)); // @deprecated. Kept for B/C
		$dispatcher->trigger('iCagendaOnEventPrepare', array('com_icagenda.event', &$item, &$this->params));

		$item->event = new stdClass;

		$results = $dispatcher->trigger('iCagendaOnEventBeforeDisplay', array('com_icagenda.event', &$item, &$this->params));
		$item->event->iCagendaOnEventBeforeDisplay = trim(implode("\n", $results));

		$results = $dispatcher->trigger('iCagendaOnEventAfterDisplay', array('com_icagenda.event', &$item, &$this->params));
		$item->event->iCagendaOnEventAfterDisplay = trim(implode("\n", $results));


		// Escape strings for HTML output
		$this->pageclass_sfx = htmlspecialchars($this->item->params->get('pageclass_sfx', ''));

		$this->_prepareDocument();

		$this->icevent_vars = 'components/com_icagenda/add/elements/icevent_vars.php';

		parent::display($tpl);

		$dispatcher->trigger('onEventAfterDisplay', array('com_icagenda.event', &$item, &$this->params)); // @deprecated. Kept for B/C

		// Loads jQuery Library
		JHtml::_('bootstrap.framework');
		JHtml::_('jquery.framework');

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

		// Set Tooltip
		icagendaTiptip::setTooltip('.iCtip');
		icagendaTiptip::setTooltip('.ic-addtocal', array('keepAlive' => true, 'defaultPosition' => 'bottom'));

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
		$app     = JFactory::getApplication();
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
			$this->params->def('page_heading', JText::_('IC_EVENT'));
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
			$title = JText::sprintf('JPAGETITLE', $app->get('sitename'), $title);
		}
		elseif ($app->get('sitename_pagetitles', 0) == 2)
		{
			$title = JText::sprintf('JPAGETITLE', $title, $app->get('sitename'));
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

		$this->document->setMetadata('keywords', $this->params->get('menu-meta_keywords'));

		if ($this->params->get('robots'))
		{
			$this->document->setMetadata('robots', $this->params->get('robots'));
		}

		// If there is a pagebreak heading or title, add it to the page title
		if ( ! empty($this->item->page_title))
		{
			$this->item->title = $this->item->title . ' - ' . $this->item->page_title;
			$this->document->setTitle(
				$this->item->page_title . ' - ' . JText::sprintf('PLG_CONTENT_PAGEBREAK_PAGE_NUM', $this->state->get('list.offset') + 1)
			);
		}

		if ($this->print)
		{
			$this->document->setMetaData('robots', 'noindex, nofollow');
		}

		// Open Graph Tags
		$eventTitle = icagendaEvent::setMetaTitle($this->item);
		$eventType  = 'article';
		$eventImage = $this->item->image;
		$imgLink    = filter_var($eventImage, FILTER_VALIDATE_URL);
		$eventUrl   = JUri::getInstance()->toString();
		$sitename   = $app->getCfg('sitename');
		$og_desc    = icagendaEvent::setMetaDesc($this->item);

		if ($app->input->get('tmpl') != 'component')
		{
			if ($eventTitle)
			{
				$this->document->setTitle($title);
				$this->document->addCustomTag('<meta property="og:title" content="' . $eventTitle . '" />');
			}
			if ($eventType)
			{
				$this->document->addCustomTag('<meta property="og:type" content="' . $eventType . '" />');
			}
			if ($eventImage)
			{
				if ($imgLink)
				{
					$this->document->addCustomTag('<meta property="og:image" content="' . $eventImage . '" />');
				}
				else
				{
					$this->document->addCustomTag('<meta property="og:image" content="' . JURI::base() . $eventImage . '" />');
				}
			}
			if ($eventUrl)
			{
				$this->document->addCustomTag('<meta property="og:url" content="' . $eventUrl . '" />');
			}
			if ($og_desc)
			{
				$this->document->setDescription($og_desc);
				$this->document->addCustomTag('<meta property="og:description" content="' . $og_desc . '" />');
			}
			if ($sitename)
			{
				$this->document->addCustomTag('<meta property="og:site_name" content="' . $sitename . '" />');
			}
		}
	}
}
