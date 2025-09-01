<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.9.0 2024-03-03
 *
 * @package     iCagenda.Admin
 * @subpackage  src.Utilities.Manager
 * @link        https://www.icagenda.com
 *
 * @author      Cyril Rezé
 * @copyright   (c) 2012-2024 Cyril Rezé / iCagenda. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 *
 * @since       3.6
 *----------------------------------------------------------------------------
*/

namespace iCutilities\Manager;

\defined('_JEXEC') or die;

use Joomla\CMS\Access\Access;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Log\Log;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Uri\Uri;

/**
 * class icagendaManager
 */
class Manager
{
	/**
	 * Function to return manager icons toolbar
	 */
	public static function toolBar($item, $options = array())
	{
		$app   = Factory::getApplication();
		$input = $app->input;

		// Get Current Itemid
		$this_itemid = $input->getInt('Itemid');

		// Set Manager Actions Url
		$event_slug        = empty($item->alias) ? $item->id : $item->id . ':' . $item->alias;
		$managerActionsURL = 'index.php?option=com_icagenda&view=event&id=' . $event_slug . '&Itemid=' . $this_itemid;

		// Set Email Notification Url to event
		$linkEmailUrl = Uri::base() . 'index.php?option=com_icagenda&view=event&id=' . $event_slug . '&Itemid=' . $this_itemid;

		// Get Approval Status
		$approved = $item->approval;

		$icu_approve = $input->get('manageraction', '');

		$view          = $input->get('view', '');
		$layout        = $input->get('layout', '');
		$layouts_array = array('event', 'registration');
		$icu_layout    = in_array($layout, $layouts_array) ? $layout : '';

		$iconSize    = ($view == 'event') ? 'fs-1' : 'fs-4';
		$btnSize     = ($view == 'event') ? '' : ' btn-sm';
		$alertMargin = ($view == 'event') ? 'mt-2 mb-2' : 'mb-1';

		$approveIcon = $approval_msg = $approval_title = $approval_type = '';

		$managerTools = '';

		if (self::isEventManager()
			&& $approved == 1)
		{
			$approvalButton = '<a class="ic-tooltip"'
							. ' href="' . Route::_($managerActionsURL . '&manageraction=approve') . '">'
							. '<button type="button" class="btn btn-warning' . $btnSize . ' ms-1">'
							. '<i class="icon-checkmark"></i>&#160;'
							. Text::_('COM_ICAGENDA_APPROVE')
							. '</button>'
							. '<span class="ic-tooltip-content ic-tooltip-top"><div class="ic-tooltip-legend">'
							. Text::_('COM_ICAGENDA_EVENT_APPROVE_BUTTON_DESC_LEGEND')
							. '</div><br />'
							. htmlspecialchars($item->title)
							. '</span>'
							. '</a>';

			if ($icu_approve != 'approve'
				&& $view == 'event')
			{
				$approveIcon = '<span class="icon-checkmark"></span>&#160;' . Text::_('COM_ICAGENDA_APPROVE');

				$approval_msg   = Text::sprintf('COM_ICAGENDA_APPROVE_AN_EVENT_NOTICE', $approveIcon);
				$approval_title = Text::_('COM_ICAGENDA_APPROVE_AN_EVENT_LBL');
				$approval_type  = 'notice';
			}

			if ($icu_approve == 'approve'
				&& $input->get('view', '') == 'event')
			{
				$db    = Factory::getDbo();
				$query = $db->getQuery(true);
				$query->clear();
				$query->update('#__icagenda_events');
				$query->set('approval = 0');
				$query->where(' id = ' . (int) $item->id);
				$db->setQuery((string) $query);
				$db->execute($query);

				$approveSuccess = '"' . $item->title . '"';
				$alertmsg       = Text::sprintf('COM_ICAGENDA_APPROVED_SUCCESS', $approveSuccess);
				$alerttitle     = Text::_('COM_ICAGENDA_APPROVED');
				$alerttype      = 'success';
				$approvedLink   = Route::_($managerActionsURL);

				if (isset($item->created_by_email))
				{
					self::approvalNotification($item, $linkEmailUrl);
				}

				// Plugin Event handler 'onICagendaNewEvent'
				$app->triggerEvent('iCagendaOnNewEvent', array($item)); // @Deprecated 4.0
				$app->triggerEvent('onICagendaNewEvent', array($item));

				// System Message Approval
				$app->enqueueMessage($alertmsg, $alerttitle, $alerttype);
			}
			else
			{
				$managerTools.= $approvalButton;
			}
		}

		// Check if additional elements to be added to the manager toolbar.
		$managerToolsExtended = $app->triggerEvent('onICagendaManagerTools', array($item, $options));

		$managerTools.= trim(implode("\n", $managerToolsExtended));

		if ($managerTools)
		{
			if (version_compare(JVERSION, '4.0', 'ge'))
			{
				$managerBar = '<div class="' . $alertMargin . ' p-1 alert alert-info"><span class="icon icon-options ic-float-left m-1 ' . $iconSize . '"></span>';
				$managerBar.= $managerTools;
				$managerBar.= '</div>';
			}
			else
			{
				$managerBar = $managerTools;
			}

			return $managerBar;
		}

		return;
	}
	/**
	 * Function to check if a user is an event manager.
	 */
	public static function isEventManager($userID = null)
	{
		// Get User groups allowed to approve event submitted
		$approvalGroups = ComponentHelper::getParams('com_icagenda')->get('approvalGroups', array("8"));
		$approvalGroups = is_array($approvalGroups) ? $approvalGroups : array($approvalGroups);

		// Get User ID if not defined
		if ($userID === null)
		{
			$userID = Factory::getUser()->get('id');
		}

		// Get User groups of the user logged-in
		$userGroups = Access::getGroupsByUser($userID, false);

		return (boolean) (array_intersect($userGroups, $approvalGroups) || in_array('8', $userGroups));
	}

	/**
	 * Function to send approval notification emails
	 * 
	 * @since   3.6
	 */
	public static function approvalNotification($item, $eventLink)
	{
		Factory::getLanguage()->load('com_icagenda', JPATH_SITE);

		$app = Factory::getApplication();

		if (isset($item->created_by_email) && $item->created_by_email)
		{
			// Load Joomla Config Mail Options
			$sitename = $app->getCfg('sitename');
			$mailfrom = $app->getCfg('mailfrom');
			$fromname = $app->getCfg('fromname');

			// Create User Mailer
			$approvedmailer = Factory::getMailer();

			// Set Sender of Notification Email
			$approvedmailer->setSender(array( $mailfrom, $fromname ));

			// Set Recipient of Notification Email
			$approvedmailer->addRecipient($item->created_by_email);

			// Set Subject of Notification Email
			$approvedsubject = Text::sprintf('COM_ICAGENDA_APPROVED_USEREMAIL_SUBJECT', $item->title);
			$approvedmailer->setSubject($approvedsubject);

			// Set Body of Notification Email
			$creator = $item->created_by_alias ?: $item->username;
			$approvedbodycontent = Text::sprintf('COM_ICAGENDA_SUBMISSION_ADMIN_EMAIL_HELLO', $creator) . ',<br /><br />';
			$approvedbodycontent.= Text::sprintf('COM_ICAGENDA_APPROVED_USEREMAIL_BODY_INTRO', $sitename) . '<br /><br />';

			$eventLink_html = '<br /><a href="' . $eventLink . '">' . $eventLink . '</a>';

			$approvedbodycontent.= Text::sprintf('COM_ICAGENDA_APPROVED_USEREMAIL_EVENT_LINK', $eventLink_html).'<br /><br />';
			$approvedbodycontent.= '<hr><small>' . Text::_('COM_ICAGENDA_APPROVED_USEREMAIL_EVENT_LINK_INFO') . '</small><br /><br />';

			$approvedbody = rtrim($approvedbodycontent);

			$approvedmailer->isHTML(true);

			// JDocs: When sending HTML emails you should normally set the Encoding to base64
			//        in order to avoid unwanted characters in the output.
			//        See https://docs.joomla.org/Sending_email_from_extensions
			$approvedmailer->Encoding = 'base64'; // JDocs Sending HTML Email

			$approvedmailer->setBody($approvedbody);

			// Try to send Notification Email
			try {
				$return = $approvedmailer->send();
			} catch (\Exception $exception) {
				try {
					Log::add(Text::_($exception->getMessage()), Log::WARNING, 'icagenda');

					$return = false;
				} catch (\RuntimeException $exception) {
					Factory::getApplication()->enqueueMessage(Text::_($exception->errorMessage()), 'warning');

					$return = false;
				}
			}

			// Check for an error.
			if ($return !== true) {
				return false;
			}
		}
		else
		{
			$app->enqueueMessage('Recipient of Notification Email not set.');
		}
	}
}
