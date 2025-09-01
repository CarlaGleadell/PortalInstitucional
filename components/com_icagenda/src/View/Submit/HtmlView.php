<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.9.4 2024-06-13
 *
 * @package     iCagenda.Site
 * @subpackage  src.View
 * @link        https://www.icagenda.com
 *
 * @author      Cyril Rezé
 * @copyright   (c) 2012-2024 Cyril Rezé / iCagenda. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 *
 * @since       3.2
 *----------------------------------------------------------------------------
*/

namespace WebiC\Component\iCagenda\Site\View\Submit;

\defined('_JEXEC') or die;

use iCutilities\Categories\Categories as icagendaCategories;
use iCutilities\Info\Info as icagendaInfo;
use iCutilities\Theme\Theme as icagendaTheme;
use iCutilities\Theme\Style as icagendaThemeStyle;
use Joomla\CMS\Factory;
use Joomla\CMS\Form\FormHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\CMS\Plugin\PluginHelper;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Uri\Uri;

/**
 * HTML Submit View class for the iCagenda component
 */
class HtmlView extends BaseHtmlView
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
		$app  = Factory::getApplication();
		$user = Factory::getUser();

		if ( ! icagendaCategories::getList('1'))
		{
			$app->enqueueMessage(
				Text::_('COM_ICAGENDA_SUBMIT_ERROR_NOT_ABLE_TO_SUBMIT')
				. '<ul><li>'
				. Text::_('COM_ICAGENDA_SUBMIT_ERROR_NO_CATEGORY_PUBLISHED')
				. '</li></ul>'
				. Text::_('COM_ICAGENDA_SUBMIT_ERROR_CONTACT_ADMIN')				
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
		$NotLoginDefault = Text::_('COM_ICAGENDA_EVENT_SUBMISSION_ACCESS');

		$submitNotLogin_Content = ($params->get('submitNotLogin', '') == 2)
								? $params->get('submitNotLogin_Content', $NotLoginDefault)
								: $NotLoginDefault;
		
		// Get Content of the page for not authorised logged-in users
		$NoRightsDefault = Text::_('COM_ICAGENDA_EVENT_SUBMISSION_NO_RIGHTS');

		$submitNoRights_Content = ($params->get('submitNoRights', '') == 2)
								? $params->get('submitNoRights_Content', $NoRightsDefault)
								: $NoRightsDefault;

		// Set Return Page
		$uri    = Uri::getInstance();
		$return = base64_encode($uri); // Encode Return URL
		$rlink  = Route::_("index.php?option=com_users&view=login&return=$return", false);

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

		// @TODO: Delete the unused code after checking it.
		$this->submit_language_display    = $params->get('submit_language_display', 0);
		$this->submit_imageDisplay        = $params->get('submit_imageDisplay', 1);
		$this->submit_shortdescDisplay    = $params->get('submit_shortdescDisplay', 1);
		$this->submit_descDisplay         = $params->get('submit_descDisplay', 1);
		$this->submit_metadescDisplay     = $params->get('submit_metadescDisplay', 0);
		$this->submit_venueDisplay        = $params->get('submit_venueDisplay', 1);
		$this->submit_emailDisplay        = $params->get('submit_emailDisplay', 1);
		$this->submit_phoneDisplay        = $params->get('submit_phoneDisplay', 1);
		$this->submit_websiteDisplay      = $params->get('submit_websiteDisplay', 1);
		$this->submit_customfieldsDisplay = $params->get('submit_customfieldsDisplay', 1);
		$this->submit_fileDisplay         = $params->get('submit_fileDisplay', 1);
		$this->submit_regoptionsDisplay   = $params->get('submit_regoptionsDisplay', 1);
		$this->statutReg                  = $params->get('statutReg', 0);
		$this->ShortDescLimit             = $params->get('ShortDescLimit', '160');
		$this->submit_captcha             = $params->get('submit_captcha', 0);
		$this->submit_form_validation     = $params->get('submit_form_validation', '');

		// Process the plugins.
		PluginHelper::importPlugin('content');
		$app->triggerEvent('iCagendaOnSubmitPrepare', array ('com_icagenda.submit', &$item, &$this->params)); // Deprecated 3.8

		$this->pluginEvent = new \stdClass;

		$results = $app->triggerEvent('iCagendaOnSubmitBeforeDisplay', array('com_icagenda.submit', &$item, &$this->params)); // Deprecated 3.8
		$this->pluginEvent->iCagendaOnSubmitBeforeDisplay = trim(implode("\n", $results));

		$results = $app->triggerEvent('iCagendaOnSubmitAfterDisplay', array('com_icagenda.submit', &$item, &$this->params)); // Deprecated 3.8
		$this->pluginEvent->iCagendaOnSubmitAfterDisplay = trim(implode("\n", $results));

		// Escape strings for HTML output
		$this->pageclass_sfx = htmlspecialchars($params->get('pageclass_sfx', ''));

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			throw new \Exception(implode("\n", $errors), 500);

			return false;
		}

		$this->_prepareDocument();

		icagendaInfo::commentVersion();

		parent::display($tpl);

		// Add CSS
		icagendaTheme::loadComponentCSS($this->template);
		icagendaThemeStyle::addMediaCss($this->template, 'component');

		// Image Upload Size/Type control (@todo: create a custom image upload form field)
		Text::script('IC_LIBRARY_UPLOAD_NOT_SUPPORTED');
		Text::script('IC_LIBRARY_UPLOAD_INVALID_FILE_TYPE_ALERT');
		Text::script('IC_LIBRARY_UPLOAD_INVALID_SIZE');
		Text::script('IC_LIBRARY_UPLOAD_INVALID_FILE_TYPE');
		Text::script('IC_LIBRARY_KILO_BYTES');
	}

	/**
	 * Prepares the document
	 *
	 * @return  void
	 */
	protected function _prepareDocument()
	{
		$app     = Factory::getApplication();
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
			$this->params->def('page_heading', Text::_('JGLOBAL_ARTICLES'));
		}

		$title = $this->params->get('page_title', '');

		if (empty($title))
		{
			$title = $app->getCfg('sitename');
		}
		elseif ($app->getCfg('sitename_pagetitles', 0) == 1)
		{
			$title = Text::sprintf('JPAGETITLE', $app->getCfg('sitename'), $title);
		}
		elseif ($app->getCfg('sitename_pagetitles', 0) == 2)
		{
			$title = Text::sprintf('JPAGETITLE', $title, $app->getCfg('sitename'));
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

		if ($app->getCfg('MetaTitle') == '1'
			&& $this->params->get('menupage_title', ''))
		{
			$this->document->setMetaData('title', $this->params->get('page_title', ''));
		}
	}
}
