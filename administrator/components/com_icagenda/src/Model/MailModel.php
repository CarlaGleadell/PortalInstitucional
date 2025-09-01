<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.9.0 2024-03-03
 *
 * @package     iCagenda.Admin
 * @subpackage  src.Model
 * @link        https://www.icagenda.com
 *
 * @author      Cyril Rezé
 * @copyright   (c) 2012-2024 Cyril Rezé / iCagenda. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 *
 * @since       2.0
 *----------------------------------------------------------------------------
*/

namespace WebiC\Component\iCagenda\Administrator\Model;

\defined('_JEXEC') or die;

use iClib\Date\Date as iCDate;
use Joomla\CMS\Access\Access;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Log\Log;
use Joomla\CMS\MVC\Model\AdminModel;
use Joomla\CMS\Uri\Uri;

/**
 * iCagenda Component Mail Model.
 */
class MailModel extends AdminModel
{
	/**
	 * @var  string  The prefix to use with controller messages.
	 */
	protected $text_prefix = 'COM_ICAGENDA';

	/**
	 * Method to get the row form.
	 *
	 * @param   array    $data      An optional array of data for the form to interogate.
	 * @param   boolean  $loadData  True if the form is to load its own data (default case), false if not.
	 *
	 * @return  JForm  A JForm object on success, false on failure
	 */
	public function getForm($data = array(), $loadData = true)
	{
		// Get the form.
		$form = $this->loadForm('com_icagenda.mail', 'mail', array('control' => 'jform', 'load_data' => $loadData));

		if (empty($form))
		{
			return false;
		}

		return $form;
	}

	/**
	 * Method to get the data that should be injected in the form.
	 *
	 * @return  mixed  The data for the form.
	 */
	protected function loadFormData()
	{
		$app = Factory::getApplication();

		// Check the session for previously entered form data.
		$data = $app->getUserState('com_icagenda.display.mail.data', array());

		if (empty($data))
		{
			$data = $app->getUserState('com_icagenda.mail.data', array());
		}

		$this->preprocessData('com_icagenda.mail', $data);

		return $data;
	}

	/**
	 * Send the email
	 *
	 * @return  boolean
	 */
	public function send()
	{
		$app    = Factory::getApplication();
		$user   = Factory::getUser();

		$return = '';
		$data   = $app->input->post->get('jform', array(), 'array');
		$access = new Access;

		// Set Form Data to Session
		$session = Factory::getSession();
		$session->set('ic_newsletter', $data);

		$mailfrom = $app->get('mailfrom');
		$fromname = $app->get('fromname');
		$sitename = $app->get('sitename');

		$eventid = array_key_exists('eventid', $data) ? $data['eventid'] : '';
		$date    = array_key_exists('date', $data) ? $data['date'] : '';

		$db    = $this->getDbo();
		$query = $db->getQuery(true);

		$query->select('r.email, r.eventid, r.state, r.status, r.date, r.people')
			->from('#__icagenda_registration AS r');
		$query->where('r.state = 1');
		$query->where('r.status = 1');
		$query->where('r.email <> ""');
		$query->where('r.eventid = ' . (int) $eventid);

		if ($date != 'all')
		{
			if (iCDate::isDate($date))
			{
				$query->where('r.date = ' . $db->q($date));
			}
			elseif ($date == 1)
			{
				$query->where('r.period = 1');
			}
			elseif ($date)
			{
				// Fix for old date saving data
				$query->where('r.date = ' . $db->q($date));
			}
			else
			{
				$query->where('r.period = 0');
			}
		}

		$db->setQuery($query);

		$result = $db->loadObjectList();

		$list   = '';
		$people = 0;

		foreach ($result as $v)
		{
			$list.= $v->email . ', ';
			$people = ($people + $v->people);
		}

		$subject = array_key_exists('subject', $data) ? $data['subject'] : '';
		$message = array_key_exists('message', $data) ? $data['message'] : '';

		$list_emails = explode(', ', $list);

		// Remove dupplicated email addresses
		$recipient = array_unique($list_emails);
		$recipient = array_filter($recipient);

		$content = stripcslashes($message);
		$body    = str_replace('src="images/', 'src="' . Uri::root() . '/images/', $content);

		// Set Mail
		$mail = Factory::getMailer();
		$mail->addRecipient($app->getCfg('mailfrom'));
		$mail->addBCC($recipient);

		// FIX Joomla 3.5.1 issue on some servers, by addition of "Optional" ReplyTo, not previously set.
		// JOOMLA 3.x/2.5 SWITCH
		$mail->addReplyTo($mailfrom, $fromname);

		$mail->setSender(array($mailfrom, $fromname));
		$mail->setSubject($subject);

		$mail->isHTML(true);

		// JDocs: When sending HTML emails you should normally set the Encoding to base64
		//        in order to avoid unwanted characters in the output.
		//        See https://docs.joomla.org/Sending_email_from_extensions
		$mail->Encoding = 'base64'; // JDocs Sending HTML Email

		$mail->setBody($body);

		// Send Mail
		if ($subject
			&& $body
			&& $eventid
			&& ($date || $date == '0')
		) {
			// Try to send Email
			try {
				$return = $mail->send();
			} catch (\Exception $exception) {
				try {
					Log::add(Text::_($exception->getMessage()), Log::WARNING, 'icagenda');

					$return = false;
				} catch (\RuntimeException $exception) {
					Factory::getApplication()->enqueueMessage(Text::_($exception->errorMessage()), 'warning');

					$return = false;
				}
			}
		}

		// Check for an error.
		if ($return !== true) {
			$app->enqueueMessage(Text::_('COM_ICAGENDA_NEWSLETTER_ERROR_ALERT'), 'error');

			if ( ! $subject) {
				$app->enqueueMessage('- ' . Text::_('COM_ICAGENDA_NEWSLETTER_NO_OBJ_ALERT'), 'error');
			}

			if ( ! $body) {
				$app->enqueueMessage('- ' . Text::_('COM_ICAGENDA_NEWSLETTER_NO_BODY_ALERT'), 'error');
			}

			if ( ! $eventid
				&& ( ! $date && $date != '0')
			) {
				$app->enqueueMessage('- ' . Text::_('COM_ICAGENDA_NEWSLETTER_NO_EVENT_SELECTED'), 'error');
			} elseif ($eventid
				&& ( ! $date && $date != '0')
			) {
				$app->enqueueMessage('- ' . Text::_('COM_ICAGENDA_NEWSLETTER_NO_DATE_SELECTED'), 'error');
			}

			return false;
		}

		$app->enqueueMessage('<h2>' . Text::_('COM_ICAGENDA_NEWSLETTER_SUCCESS') . '</h2>', 'message');

		$app->enqueueMessage($this->listSend($recipient, 0, $people), 'message');

		$dupplicated_emails = count($list_emails) - count($recipient);

		if ($dupplicated_emails) {
			$app->enqueueMessage('<em>' . Text::sprintf('COM_ICAGENDA_NEWSLETTER_NB_EMAIL_NOT_SEND', $dupplicated_emails) . '</em>', 'message');
		}

		return true;
	}

	/**
	 * Html list of emails send
	 *
	 * @return  HTML
	 */
	public function listSend($recipient, $level = 0, $people = null)
	{
		$number    = 0;
		$list_send = '';

		foreach($recipient AS $key => $value)
		{
			if (is_array($value) | is_object($value))
			{
				parent::listArray($value, $level+=1);
			}
			else
			{
				$number = ($number + 1);

				$list_send.= str_repeat("&nbsp;", $level*3);
				$list_send.= $number . " : " . $value . "<br>";
			}
		}

		$list_send.= '<h4>' . Text::_('COM_ICAGENDA_NEWSLETTER_NB_EMAIL_SEND') . ' = ' . $number . '';
		$list_send.= '<small> (' . Text::_('COM_ICAGENDA_REGISTRATION_TICKETS') . ': ' . $people . ')</small></h4>';

		return $list_send;
	}
}
