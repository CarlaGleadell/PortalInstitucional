<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.9.5 2024-07-18
 *
 * @package     iCagenda.Site
 * @subpackage  src.View
 * @link        https://www.joomlic.com
 *
 * @author      Cyril Rezé
 * @copyright   (c) 2012-2024 Cyril Rezé / JoomliC. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 *
 * @since       3.6
 *----------------------------------------------------------------------------
*/

namespace WebiC\Component\iCagenda\Site\View\Registration;

\defined('_JEXEC') or die;

use iClib\Date\Date as iCDate;
use iClib\Library\Library as iCLibrary;
use iCutilities\Event\Event as icagendaEvent;
use iCutilities\Info\Info as icagendaInfo;
use iCutilities\Registration\Registration as icagendaRegistration;
use iCutilities\Render\Render as icagendaRender;
use iCutilities\Theme\Theme as icagendaTheme;
use iCutilities\Theme\Style as icagendaThemeStyle;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Form\FormHelper;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\CMS\Plugin\PluginHelper;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Uri\Uri;

/**
 * HTML Registration View class for the iCagenda component
 */
class HtmlView extends BaseHtmlView
{
	protected $data;

	protected $form;

	protected $state;

	protected $params;

	protected $item;

	public $document;

	protected $eventShortcuts = 'components/com_icagenda/src/Assets/EventShortcuts.php';

	/**
	 * Method to display the view.
	 *
	 * @param   string  $tpl  The template file to include
	 *
	 * @return  mixed
	 */
	public function display($tpl = null)
	{
		$app  = Factory::getApplication();
		$user = Factory::getUser();

		// Get the view data.
		$this->data         = $this->get('Data');
		$this->form         = $this->get('Form');
		$this->state        = $this->get('State');
		$this->item         = $this->get('Item');
		$this->registration = $this->get('Registration');

		$this->participantEventRegistrations = $this->get('ParticipantEventRegistrations');

		$this->params = $this->state->get('params');

		$this->coreFields   = array(
								'uid'    => true,
								'name'   => true,
								'email'  => true,
								'email2' => true,
								'phone'  => $this->params->get('phoneDisplay', 1) ? true : false,
								'date'   => true,
								'people' => true,
							);

		$this->extraFields  = array(
								'notes'   => $this->params->get('notesDisplay', 0) ? true : false,
								'terms'   => $this->params->get('terms', 0) ? true : false,
								'captcha' => $this->params->get('reg_captcha', 0) ? true : false,
							);

		// Shortcuts
		$params = $this->params;
		$item   = $this->item;

		// For Dev.
		$time_loading = $params->get('time_loading', '');

		if ($time_loading) {
			$starttime_reg = iCLibrary::getMicrotime();
		}

		// Get Options
		$this->reg_captcha         = $params->get('reg_captcha', 0);
		$this->reg_form_validation = $params->get('reg_form_validation', '');

		// Check Access
		$userLevels = $user->getAuthorisedViewLevels();
		$userGroups = $user->getAuthorisedGroups();

		$groupid = ComponentHelper::getParams('com_icagenda')->get('approvalGroups', array("8"));
		$groupid = is_array($groupid) ? $groupid : array($groupid);

		$uri    = Uri::getInstance();
		$return = base64_encode($uri); // Encode Return URL
		$rlink  = Route::_("index.php?option=com_users&view=login&return=$return", false);

		// Event URL
		$vars = array(
					'date' => iCDate::dateToAlias($app->getSession()->get('event_date'), 'Y-m-d-H-i'),
				);

		// Start controls
		if ($item == NULL
			|| $item->state != 1
			|| $item->approval == 1
			|| $item->params->get('event_cancelled') == 1
			|| ! $item->params->get('statutReg')
			)
		{
			$app->enqueueMessage(Text::_('JERROR_LAYOUT_PAGE_NOT_FOUND'), 'error');

			return false;
		}

		// Warning 'Registration closed' if no ticket available for the current form
		// @TODO: set this as a content message, replacing the form, and keeping the event info header.
		elseif ($app->input->get('layout', 'default') == 'default'
			&& (
				$item->ticketsBookable <= 0
				|| ! icagendaRegistration::upcomingDatesBooking($item)
				)
			)
		{
			$app->enqueueMessage(Text::_('COM_ICAGENDA_REGISTRATION_CLOSED'), 'warning');

			$eventURL = icagendaEvent::url($item->id, $item->alias, $app->input->getInt('Itemid'), $vars);

			$app->redirect($eventURL);

			return false;
		}

		// Layout Cancel: for "actions" plugins. // @todo: action cancel request system
		elseif ($this->item->params->get('registration_actions')
			&& $app->input->get('layout', 'default') == 'cancel'
			)
		{
			$actionString = strtoupper(str_replace('.', '_', $this->item->params->get('registration_actions')));
			
			// Get Author Email
			$creator    = Factory::getUser($item->created_by);
			$authormail = $creator->get('email') ? : $app->getCfg('mailfrom');

			echo Text::sprintf('COM_ICAGENDA_REGISTRATION_' . $actionString . '_STRING_CANCELLATION_EMAIL', icagendaRender::emailTag($authormail));

			return false;
		}

		// Layout Cancel: logged-in user access only
		elseif ( ! $user->id
			&& $app->input->get('layout', 'default') == 'cancel'
			)
		{
			$msg = '';

			// Redirect to login page if user not logged-in to be able to cancel registration.
			$msg.= '<h4 class="alert-heading">' . Text::_('IC_AUTH_REQUIRED') . '</h4>';
			$msg.= '<p>' . Text::_('COM_ICAGENDA_LOGIN_TO_ACCESS_REGISTRATION_CANCELLATION') . '</p>';

			$app->enqueueMessage($msg, '');

			$app->redirect($rlink);
		}

		elseif ( ! in_array('8', $userGroups)
			&& ! in_array(icagendaRegistration::accessReg($item), $userLevels)
			)
		{
			if ($user->id)
			{
				$app->enqueueMessage(Text::_('COM_ICAGENDA_REGISTRATION_ACCESS_DENIED'), 'warning');
			}
			else
			{
//				$app->enqueueMessage(Text::_( 'JGLOBAL_YOU_MUST_LOGIN_FIRST' ), 'info');

				// Redirect to login page if no access to registration form.
				$msg = '<div>';
				$msg.= '<h2>';
				$msg.= Text::_('IC_AUTH_REQUIRED');
				$msg.= '</h2>';
				$msg.= '<div>';
				$msg.= Text::_("COM_ICAGENDA_LOGIN_TO_ACCESS_REGISTRATION_FORM");
				$msg.= '</div>';
				$msg.= '<br />';
				$msg.= '<div>';
				$msg.= '<a href="' . icagendaEvent::eventURL($item) . '" class="btn btn-danger btn-sm">';
				$msg.= '<i class="iCicon iCicon-backic"></i>&nbsp;' . Text::_('COM_ICAGENDA_BACK') . '';
				$msg.= '</a>';
				$msg.= '&nbsp;';
				$msg.= '<a href="index.php" class="btn btn-info btn-sm">';
				$msg.= '<i class="icon-home"></i>&nbsp;' . Text::_('JERROR_LAYOUT_HOME_PAGE') . '';
				$msg.= '</a>';
				$msg.= '</div>';
				$msg.= '</div>';

				// if not login, and registration form not "public".
				$app->enqueueMessage($msg);
			}

			$app->redirect($rlink);
		}

		$this->eventURL = icagendaEvent::url($item->id, $item->alias, $app->input->getInt('Itemid'), $vars);

		// Load Theme Pack layout for event details view.
		$this->template    = $params->get('template', 'default');
		$themeRegistration = icagendaTheme::getThemeLayout($this->template, 'registration');

		// Check for Theme Pack errors (layout file missing).
		if ($themeRegistration[1])
		{
			$msg = ($themeRegistration[1] !== 'deprecated')
					? 'iCagenda ' . Text::_('PHPMAILER_FILE_OPEN') . ' <strong>' . $this->template . '_registration.php</strong>'
					: Text::_('COM_ICAGENDA_ERROR_THEME_PACK_OUTDATED') . '<br/>' .
						Text::sprintf('COM_ICAGENDA_ERROR_THEME_PACK_EDIT_OR_CHANGE', '<strong>' . $this->template . '_registration.php</strong>');
			$app->enqueueMessage($msg, 'warning');

			if ($themeRegistration[1] !== 'alert')
			{
				return false;
			}
		}

		$this->themeRegistration = $themeRegistration[0];

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			throw new \Exception(implode("\n", $errors), 500);

			return false;
		}

		// Check for layout override
		$active = $app->getMenu()->getActive();

		if (isset($active->query['layout']))
		{
			$this->setLayout($active->query['layout']);
		}

		// Process the content plugins.
		PluginHelper::importPlugin('icagenda');

		$this->registrationActions = $app->triggerEvent('onICagendaRegistrationActions', array('com_icagenda.actions', &$item, &$this->params));

		$app->triggerEvent('iCagendaOnRegistrationPrepare', array('com_icagenda.registration', &$item, &$this->params));

		// @deprecated J4 $item->event (plugin event handler should start with "on") - To be removed iCagenda 4
		$item->event = new \stdClass;

		$results = $app->triggerEvent('iCagendaOnRegistrationBeforeDisplay', array('com_icagenda.registration', &$item, &$this->params));
		$item->event->iCagendaOnRegistrationBeforeDisplay = trim(implode("\n", $results));

		$results = $app->triggerEvent('iCagendaOnRegistrationAfterDisplay', array('com_icagenda.registration', &$item, &$this->params));
		$item->event->iCagendaOnRegistrationAfterDisplay = trim(implode("\n", $results));

		// pluginEvent
		$item->pluginEvent = new \stdClass;

		$results = $app->triggerEvent('onICagendaRegistrationBeforeDisplay', array('com_icagenda.registration', &$item, &$this->params));
		$item->pluginEvent->beforeDisplayRegistration = trim(implode("\n", $results));

		$results = $app->triggerEvent('onICagendaRegistrationAfterDisplay', array('com_icagenda.registration', &$item, &$this->params));
		$item->pluginEvent->afterDisplayRegistration = trim(implode("\n", $results));

		$results = $app->triggerEvent('onICagendaRegistrationCompleteAfterDataDisplay', array('com_icagenda.registration', &$item, &$this->params));
		$item->pluginEvent->afterDisplayCompleteData = trim(implode("\n", $results));


		// Escape strings for HTML output
		$this->pageclass_sfx = htmlspecialchars($this->params->get('pageclass_sfx', ''));

		icagendaInfo::commentVersion();

		$this->prepareDocument();

		parent::display($tpl);


		// Loads Scripts and CSS
		$document = Factory::getDocument();

		// Loads jQuery Library
//		HTMLHelper::_('bootstrap.framework');
//		HTMLHelper::_('jquery.framework');

		// Add CSS
		icagendaTheme::loadComponentCSS($this->template);
		icagendaThemeStyle::addMediaCss($this->template, 'component');

		// For Dev.
		if ($time_loading)
		{
			$endtime_reg = iCLibrary::getMicrotime();

			echo '<center style="font-size:8px;">Time to create page: ' . round($endtime_reg-$starttime_reg, 3) . ' seconds</center>';
		}
	}

	/**
	 * Prepares the document.
	 *
	 * @return  void
	 */
	protected function prepareDocument()
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
			$this->params->def('page_heading', Text::_('COM_ICAGENDA_REGISTRATION_TITLE'));
		}

		$title = $this->params->get('page_title', '');

		$id = (int) @$menu->query['id'];

		// If the menu item does not concern this event
		if ($menu && ($menu->query['option'] != 'com_icagenda' || $menu->query['view'] != 'registration' || $id != $this->item->id))
		{
			// If this is not a single event menu item, set the page title to the event title
			if ($this->item->title)
			{
				$title = $this->item->title;
			}

			$pathway->addItem($this->item->title . ' (' . Text::_('COM_ICAGENDA_REGISTRATION_TITLE') . ')', '');
		}

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

		$this->document->setTitle($title . ' - ' . Text::_('COM_ICAGENDA_REGISTRATION_TITLE'));

		if ($this->params->get('menu-meta_description'))
		{
			$this->document->setDescription($this->params->get('menu-meta_description'));
		}

		if ($this->params->get('menu-meta_keywords'))
		{
			$this->document->setMetadata('keywords', $this->params->get('menu-meta_keywords'));
		}

		if ($this->params->get('robots'))
		{
			$this->document->setMetadata('robots', $this->params->get('robots'));
		}
	}
}
