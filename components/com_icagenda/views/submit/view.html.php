<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.9.12 2025-08-01
 *
 * @package     iCagenda.Site
 * @link        https://www.icagenda.com
 *
 * @author      Cyril Rezé
 * @copyright   (c) 2012-2024 Cyril Rezé / iCagenda. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 *
 * @since       3.2.0
 *----------------------------------------------------------------------------
*/

defined('_JEXEC') or die();

/**
 * HTML Submit View class - Submit an Event - iCagenda
 */
class iCagendaViewSubmit extends JViewLegacy
{
	protected $form;

	protected $item;

	protected $state;

	protected $params;

	/**
	 * Display the view
	 */
	public function display($tpl = null)
	{
		$app        = JFactory::getApplication();
		$user       = JFactory::getUser();
		$dispatcher = JEventDispatcher::getInstance();

		if ( ! icagendaCategories::getList('1'))
		{
			$app->enqueueMessage(
				JText::_('COM_ICAGENDA_SUBMIT_ERROR_NOT_ABLE_TO_SUBMIT')
				. '<ul><li>'
				. JText::_('COM_ICAGENDA_SUBMIT_ERROR_NO_CATEGORY_PUBLISHED')
				. '</li></ul>'
				. JText::_('COM_ICAGENDA_SUBMIT_ERROR_CONTACT_ADMIN')				
				, 'error'
			);

			return false;
		}

		// Initialiase variables.
		$this->form   = $this->get('Form');
		$this->item   = $this->get('Item');
		$this->state  = $this->get('State');
		$this->params = $this->state->get('params');

		// Shortcuts
		$item   = $this->item;
		$params = $this->params;

		// Get User Info (Access Levels, id, email)
		$userLevels = $user->getAuthorisedViewLevels();
		$user_id    = $user->get('id');

		// Get Access Levels to the form
		$submitAccess = $params->get('submitAccess', array('2'));

		// Get Content of the page for not logged-in users
		$NotLoginDefault = JText::_('COM_ICAGENDA_EVENT_SUBMISSION_ACCESS');

		$submitNotLogin_Content = ($params->get('submitNotLogin', '') == 2)
								? $params->get('submitNotLogin_Content', $NotLoginDefault)
								: $NotLoginDefault;
		
		// Get Content of the page for not authorised logged-in users
		$NoRightsDefault = JText::_('COM_ICAGENDA_EVENT_SUBMISSION_NO_RIGHTS');

		$submitNoRights_Content = ($params->get('submitNoRights', '') == 2)
								? $params->get('submitNoRights_Content', $NoRightsDefault)
								: $NoRightsDefault;

		// Set Return Page
		$uri    = JFactory::getUri();
		$return = base64_encode($uri); // Encode Return URL
		$rlink  = JRoute::_("index.php?option=com_users&view=login&return=$return", false);

		if ( ! $user_id && ! in_array('1', $submitAccess ))
		{
			// If not logged-in, and submission form not "public"
			$app->enqueueMessage($submitNotLogin_Content, 'info');
			$app->redirect($rlink);

			return false;
		}
		elseif ( ! array_intersect($userLevels, $submitAccess))
		{
			// No Access Permissions
			$app->enqueueMessage($submitNoRights_Content, 'warning');
			$app->redirect($rlink);

			return false;
		}

		// loading params
		$this->template = $params->get('template');
		$this->title    = $params->get('title');
		$this->format   = $params->get('format');
		$this->copy     = $params->get('copy');

		$this->submit_imageDisplay        = $params->get('submit_imageDisplay', 1);
//		$this->submit_periodDisplay       = $params->get('submit_periodDisplay', 1);
//		$this->submit_weekdaysDisplay     = $params->get('submit_weekdaysDisplay', 1);
//		$this->submit_datesDisplay        = $params->get('submit_datesDisplay', 1);
//		$this->submit_displaytimeDisplay  = $params->get('submit_displaytimeDisplay', 0);
		$this->submit_shortdescDisplay    = $params->get('submit_shortdescDisplay', 1);
		$this->submit_descDisplay         = $params->get('submit_descDisplay', 1);
		$this->submit_metadescDisplay     = $params->get('submit_metadescDisplay', 0);
		$this->submit_venueDisplay        = $params->get('submit_venueDisplay', 1);
		$this->submit_emailDisplay        = $params->get('submit_emailDisplay', 1);
		$this->submit_phoneDisplay        = $params->get('submit_phoneDisplay', 1);
		$this->submit_websiteDisplay      = $params->get('submit_websiteDisplay', 1);
		$this->submit_customfieldsDisplay = $params->get('submit_customfieldsDisplay', 1);
		$this->submit_fileDisplay         = $params->get('submit_fileDisplay', 1);
//		$this->submit_gmapDisplay         = $params->get('submit_gmapDisplay', 1);
		$this->submit_regoptionsDisplay   = $params->get('submit_regoptionsDisplay', 1);
		$this->statutReg                  = $params->get('statutReg', 0);
		$this->ShortDescLimit             = $params->get('ShortDescLimit', '160');
//		$this->submit_imageMaxSize        = $params->get('submit_imageMaxSize', '800');
		$this->submit_captcha             = $params->get('submit_captcha', 0);
		$this->submit_form_validation     = $params->get('submit_form_validation', '');

		// Process the plugins.
		JPluginHelper::importPlugin('content');
		$dispatcher->trigger('iCagendaOnSubmitPrepare', array ('com_icagenda.submit', &$item, &$this->params));

		$this->pluginEvent = new stdClass;

		$results = $dispatcher->trigger('iCagendaOnSubmitBeforeDisplay', array('com_icagenda.submit', &$item, &$this->params));
		$this->pluginEvent->iCagendaOnSubmitBeforeDisplay = trim(implode("\n", $results));

		$results = $dispatcher->trigger('iCagendaOnSubmitAfterDisplay', array('com_icagenda.submit', &$item, &$this->params));
		$this->pluginEvent->iCagendaOnSubmitAfterDisplay = trim(implode("\n", $results));

		// Escape strings for HTML output
		$this->pageclass_sfx = htmlspecialchars($params->get('pageclass_sfx', ''));

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			throw new Exception(implode("\n", $errors), 500);

			return false;
		}

		// Common fields
		JFormHelper::addFieldPath(JPATH_ADMINISTRATOR . '/components/com_icagenda/utilities/field');

		$this->_prepareDocument();

		icagendaInfo::commentVersion();

		parent::display($tpl);

		// Add CSS
		icagendaTheme::loadComponentCSS($this->template);
		icagendaThemeStyle::addMediaCss($this->template, 'component');

		// Image Upload Size/Type control (@todo: create a custom image upload form field)
		JText::script('IC_LIBRARY_UPLOAD_NOT_SUPPORTED');
		JText::script('IC_LIBRARY_UPLOAD_INVALID_FILE_TYPE_ALERT');
		JText::script('IC_LIBRARY_UPLOAD_INVALID_SIZE');
		JText::script('IC_LIBRARY_UPLOAD_INVALID_FILE_TYPE');
		JText::script('IC_LIBRARY_KILO_BYTES');
	}

	/**
	 * Prepares the document
	 *
	 * @return  void
	 */
	protected function _prepareDocument()
	{
		$app     = JFactory::getApplication();
		$menus   = $app->getMenu();
		$pathway = $app->getPathway();

		$title = null;
		$menu  = $menus->getActive();

		if ($menu)
		{
			$this->params->def('page_heading', $this->params->get('page_title', $menu->title));
		}
		else
		{
			$this->params->def('page_heading', JText::_('JGLOBAL_ARTICLES'));
		}

		$title = $this->params->get('page_title', '');

		if (empty($title))
		{
			$title = $app->getCfg('sitename');
		}
		elseif ($app->getCfg('sitename_pagetitles', 0) == 1)
		{
			$title = JText::sprintf('JPAGETITLE', $app->getCfg('sitename'), $title);
		}
		elseif ($app->getCfg('sitename_pagetitles', 0) == 2)
		{
			$title = JText::sprintf('JPAGETITLE', $title, $app->getCfg('sitename'));
		}

		$this->document->setTitle($title);

		if ($this->params->get('menu-meta_description', ''))
		{
			$this->document->setDescription($this->params->get('menu-meta_description', ''));
		}

		if ($this->params->get('menu-meta_keywords', ''))
		{
			$this->document->setMetadata('keywords', $this->params->get('menu-meta_keywords', ''));
		}

		if ($this->params->get('robots', ''))
		{
			$this->document->setMetadata('robots', $this->params->get('robots', ''));
		}

		if ($app->getCfg('MetaTitle') == '1'
			&& $this->params->get('menupage_title', ''))
		{
			$this->document->setMetaData('title', $this->params->get('page_title', ''));
		}
	}
}
