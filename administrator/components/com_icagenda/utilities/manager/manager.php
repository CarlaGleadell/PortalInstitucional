<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.8.11 2022-12-09
 *
 * @package     iCagenda.Admin
 * @subpackage  Utilities
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

use Joomla\CMS\Access\Access;

/**
 * class icagendaManager
 */
class icagendaManager
{
	/**
	 * Function to return manager icons toolbar
	 *
	 * @since   3.6.0
	 */
	public static function toolBar($item, $options = array())
	{
		$app    = JFactory::getApplication();
		$input = $app->input;

		// Get Current Itemid
		$this_itemid = $input->getInt('Itemid');

		// Set Manager Actions Url
		$event_slug        = empty($item->alias) ? $item->id : $item->id . ':' . $item->alias;
		$managerActionsURL = 'index.php?option=com_icagenda&view=event&id=' . $event_slug . '&Itemid=' . $this_itemid;

		// Set Email Notification Url to event
		$linkEmailUrl = JURI::base() . 'index.php?option=com_icagenda&view=event&id=' . $event_slug . '&Itemid=' . $this_itemid;

		// Get Approval Status
		$approved = $item->approval;

		// Get User groups allowed to approve event submitted
		$iCparams = JComponentHelper::getParams('com_icagenda');
		$groupid  = $iCparams->get('approvalGroups', array("8"));

		$groupid  = is_array($groupid) ? $groupid : array($groupid);

		// Get User Infos
		$user = JFactory::getUser();

		$icid = $user->get('id');
		$icu  = $user->get('username');
		$icp  = $user->get('password');

		// Get User groups of the user logged-in
//		$userGroups = $user->getAuthorisedGroups();
		$userGroups = Access::getGroupsByUser($icid, false);

		$icu_approve = $input->get('manageraction', '');

		$approveIcon = '<button class="btn btn-micro btn-warning btn-xs "><i class="icon-checkmark"></i></button>';

		$approval_msg   = JText::sprintf('COM_ICAGENDA_APPROVE_AN_EVENT_NOTICE', $approveIcon);
		$approval_title = JText::_('COM_ICAGENDA_APPROVE_AN_EVENT_LBL');
		$approval_type  = 'notice';

		$jlayout        = $input->get('layout', '');
		$layouts_array  = array('event', 'registration');
		$icu_layout     = in_array($jlayout, $layouts_array) ? $jlayout : '';

		$dispatcher = JEventDispatcher::getInstance();

		JPluginHelper::importPlugin('icagenda');

		$managerBar = '';

		if ((array_intersect($userGroups, $groupid) || in_array('8', $userGroups))
			&& $approved == 1)
		{
			$approvalButton = '<a'
							. ' class="iCtip"'
							. ' href="' . JRoute::_($managerActionsURL . '&manageraction=approve') . '"'
							. ' title="' . JText::_('COM_ICAGENDA_APPROVE_AN_EVENT_LBL') . '">'
							. '<button type="button" class="btn btn-warning btn-xs">'
							. '<i class="icon-checkmark"></i>'
							. JText::_('COM_ICAGENDA_APPROVE')
							. '</button>'
							. '</a>&nbsp;';

			if ($icu_approve != 'approve'
				&& $input->get('view', '') == 'event')
			{
				$app->enqueueMessage($approval_msg, $approval_title, $approval_type);
			}

			if ($icu_approve == 'approve'
				&& $input->get('view', '') == 'event')
			{
				$db    = Jfactory::getDbo();
				$query = $db->getQuery(true);
				$query->clear();
				$query->update('#__icagenda_events');
				$query->set('approval = 0');
				$query->where(' id = ' . (int) $item->id);
				$db->setQuery((string) $query);
				$db->query($query);

				$approveSuccess = '"' . $item->title . '"';
				$alertmsg       = JText::sprintf('COM_ICAGENDA_APPROVED_SUCCESS', $approveSuccess);
				$alerttitle     = JText::_('COM_ICAGENDA_APPROVED');
				$alerttype      = 'success';
				$approvedLink   = JRoute::_($managerActionsURL);

				if (isset($item->created_by_email))
				{
					self::approvalNotification($item, $linkEmailUrl);
				}

				// Plugin Event handler 'onICagendaNewEvent'
				$dispatcher->trigger('iCagendaOnNewEvent', array($item)); // @Deprecated 4.0
				$dispatcher->trigger('onICagendaNewEvent', array($item));

				// System Message Approval
				$app->enqueueMessage($alertmsg, $alerttitle, $alerttype);
			}
			else
			{
				$managerBar.= $approvalButton;
			}
		}

		// Check if additional elements to be added to the manager toolbar.
		$managerToolsExtended = $dispatcher->trigger('onICagendaManagerTools', array($item, $options));

		$managerBar.= trim(implode("\n", $managerToolsExtended));

		return $managerBar;
	}

	/**
	 * Function to send approval notification emails
	 *
	 * @since   3.6.0
	 */
	public static function approvalNotification($item, $eventLink)
	{
		JFactory::getLanguage()->load('com_icagenda', JPATH_SITE);

		$app = JFactory::getApplication();

		if (isset($item->created_by_email) && $item->created_by_email)
		{
			// Load Joomla Config Mail Options
			$sitename = $app->getCfg('sitename');
			$mailfrom = $app->getCfg('mailfrom');
			$fromname = $app->getCfg('fromname');

			// Create User Mailer
			$approvedmailer = JFactory::getMailer();

			// Set Sender of Notification Email
			$approvedmailer->setSender(array( $mailfrom, $fromname ));

			// Set Recipient of Notification Email
			$approvedmailer->addRecipient($item->created_by_email);

			// Set Subject of Notification Email
			$approvedsubject = JText::sprintf('COM_ICAGENDA_APPROVED_USEREMAIL_SUBJECT', $item->title);
			$approvedmailer->setSubject($approvedsubject);

			// Set Body of Notification Email
			$creator = $item->created_by_alias ?: $item->username;
			$approvedbodycontent = JText::sprintf('COM_ICAGENDA_SUBMISSION_ADMIN_EMAIL_HELLO', $creator) . ',<br /><br />';
			$approvedbodycontent.= JText::sprintf('COM_ICAGENDA_APPROVED_USEREMAIL_BODY_INTRO', $sitename) . '<br /><br />';

			$eventLink_html = '<br /><a href="' . $eventLink . '">' . $eventLink . '</a>';

			$approvedbodycontent.= JText::sprintf('COM_ICAGENDA_APPROVED_USEREMAIL_EVENT_LINK', $eventLink_html).'<br /><br />';
			$approvedbodycontent.= '<hr><small>' . JText::_('COM_ICAGENDA_APPROVED_USEREMAIL_EVENT_LINK_INFO') . '</small><br /><br />';

			$approvedbody = rtrim($approvedbodycontent);

			$approvedmailer->isHTML(true);

			// JDocs: When sending HTML emails you should normally set the Encoding to base64
			//        in order to avoid unwanted characters in the output.
			//        See https://docs.joomla.org/Sending_email_from_extensions
			$approvedmailer->Encoding = 'base64'; // JDocs Sending HTML Email

			$approvedmailer->setBody($approvedbody);

			// Send User Notification Email
			$send = $approvedmailer->Send();
		}
		else
		{
			$app->enqueueMessage('Recipient of Notification Email not set.');
		}
	}
}
