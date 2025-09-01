<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.9.9 2025-01-23
 *
 * @package     iCagenda.Admin
 * @subpackage  src.Utilities.Registration
 * @link        https://www.joomlic.com
 *
 * @author      Cyril Rezé
 * @copyright   (c) 2012-2025 Cyril Rezé / JoomliC. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 *
 * @since       3.6
 *----------------------------------------------------------------------------
*/

namespace iCutilities\Registration;

\defined('_JEXEC') or die;

use iCutilities\Event\Event as icagendaEvent;
use iCutilities\Events\Events as icagendaEvents;
use iCutilities\Render\Render as icagendaRender;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\HTML\HTMLHelper;

/**
 * class icagendaRegistrationParticipants
 */
class Participants
{
	/**
	 * Function to check if display of participants list
	 */
	static public function listDisplay($item)
	{
		$iCparams = ComponentHelper::getParams('com_icagenda');

		// Get Option if usage of iCagenda registration form for this event
		$evtParams = icagendaEvent::evtParams($item);
		$regLink   = $evtParams->get('RegButtonLink', '');

		// Hide/Show Option
		$participantList = $iCparams->get('participantList', 0);

		// Access Levels Option
		$accessParticipantList = $iCparams->get('accessParticipantList', 1);

		if ($participantList == 1
			&& ! $regLink
			&& icagendaEvents::accessLevels($accessParticipantList)
			)
		{
			return $participantList;
		}

		return false;
	}

	/**
	 * Function to display title List of Participants (if no slide effect)
	 */
	static public function listTitle($item)
	{
		$params = Factory::getApplication()->getParams();

		// Get Option if usage of iCagenda registration form for this event
		$evtParams = icagendaEvent::evtParams($item);
		$regLink   = $evtParams->get('RegButtonLink', '');

		$participantList  = $params->get('participantList', 0);
		$participantSlide = $params->get('participantSlide', '');

		$registration = $item->params->get('statutReg', '');

		if ($participantSlide == 0
			&& $registration == 1
			&& $participantList == 1
			&& ! $regLink
			)
		{
			return Text::_('COM_ICAGENDA_EVENT_LIST_OF_PARTICIPANTS');
		}
	}

	/**
	 * Function to display list of participants
	 * TO BE REFACTORED (in process)
	 * @todo: Use iCdropdown instead.
	 */
	static public function registeredUsers($i)
	{
		HTMLHelper::_('bootstrap.framework');

		$app   = Factory::getApplication();
		$input = $app->input;

		$eventTimeZone = null;

		// Get Component PARAMS
		$iCparams = ComponentHelper::getParams('com_icagenda');

		// Get Type Registration (for all dates or per date)
		$typeReg = $i->params->get('typeReg', 1);

		// Preparing connection to db
		$db = Factory::getDbo();

		// Preparing the query
		$query = $db->getQuery(true);
		$query->select(' r.id AS regID, r.userid AS userid, r.name AS registeredUsers, r.date as regDate, r.people as regPeople, r.email as regEmail,
						u.name AS name, u.username AS username, GROUP_CONCAT(ua.user_action) AS user_action')
			->from('#__icagenda_registration AS r')
			->leftJoin('#__users as u ON u.id = r.userid')
			->leftJoin('#__icagenda_user_actions as ua ON ua.parent_id = r.id')
			->group('r.id, ua.parent_id')
			->where('r.eventid = ' . (int) $i->id)
			->where('r.status = 1')
			->where('r.state = 1');

		// Get var event date alias if set or var 'event_date' set to session in event details view
		$session    = Factory::getSession();
		$event_date = $session->get('event_date', '');
		$get_date   = $input->get('date', ($event_date ? date('Y-m-d-H-i', strtotime($event_date)) : ''));

		// Convert to SQL datetime if set, or return empty.
		$dateday = icagendaEvent::convertDateAliasToSQLDatetime($get_date);

		// Registration type: by single date/period (1)
		if ($dateday && $typeReg == 1)
		{
//			$query->where('r.date = ' . $db->q($dateday)); // This is the good logic if correctly set
			$query->where('(r.date = ' . $db->q($dateday) . ' OR (r.date = "" AND r.period = 1))');
		}
		elseif ( ! $dateday && $typeReg == 1)
		{
//			$query->where('r.date = "" AND r.period = 0'); // This is the good logic if correctly set
			$query->where('r.date = ""');
		}

		// Add the list ordering clause.
//		$orderOpt = $this->state->get('list.ordering');
//		$orderDir = $this->state->get('list.direction');

//		if ($orderOpt && $orderDir)
//		{
//			$query->order($db->escape($orderOpt . ' ' . $orderDir));
//		}

		$db->setQuery($query);

		$registeredUsers = $db->loadObjectList();
		$nbusers         = count($registeredUsers);
//		$nbusers         = $i->registered;
		$nbmax           = $nbusers-1;
		$registration    = $i->params->get('statutReg', '');
		$n               = '0';

		// Slide Params
		$participantList    = $iCparams->get('participantList', 0);
		$participantSlide   = $iCparams->get('participantSlide', 1);
		$participantDisplay = $iCparams->get('participantDisplay', 1);
		$fullListColumns    = $iCparams->get('fullListColumns', 'tiers');

		// logged-in Users: Name/User Name Option
		$nameJoomlaUser = $iCparams->get('nameJoomlaUser', 1);

		$this_date = HTMLHelper::date($dateday, 'Y-m-d H:i', $eventTimeZone);

		// Start List of Participants
		$slider_c = '';

		$list_participants = '';

		if ($participantList == 1 && $registration == 1)
		{
			$n_list='names_noslide';

			// Check Admin / Organiser permissions to access registration data
			$organiser_rights = Factory::getUser()->authorise('icagenda.access.registrations', 'com_icagenda');


			if ($participantSlide == 1)
			{
				$n_list   = 'names_slide';
				$slider_c = 'class="accordion content" id="accordion1"';
				$showButton = (\count($registeredUsers) > 0) ? ' <span class="ms-2 btn btn-light btn-sm iCicon-eye"> ' . Text::_('JSHOW') . '</span>' : '';

				$list_participants.= '<div class="accordion-header pb-3">';
				$list_participants.= '<h3 class="accordion-header" id="participants1">';
				$list_participants.= '	<button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#listParticipants" aria-expanded="false" aria-controls="listParticipants">';
				$list_participants.= Text::_('COM_ICAGENDA_EVENT_LIST_OF_PARTICIPANTS');
				$list_participants.= '	</button>';
				$list_participants.= '</h3>';
				$list_participants.= '</div>';
			}

			foreach ($registeredUsers as $reguser)
			{
				$this_reg_date  = strtotime($reguser->regDate)
								? HTMLHelper::date($reguser->regDate, 'Y-m-d H:i', $eventTimeZone)
								: $reguser->regDate;

				if ( ($this_reg_date == $this_date)
					|| ($typeReg == 2)
					)
				{
					$n = $n+1;
				}

				// Registration by dates, and registration date is not filled
				elseif ($typeReg == 1
					&& ! $this_reg_date)
				{
					$n = $n+1;
				}
			}

			if ($nbusers == NULL || ($n == 0 && ! empty($get_date)))
			{
				$list_participants.= '<div ' . $slider_c . '>';
				$list_participants.= '&nbsp;' . Text::_('COM_ICAGENDA_NO_REGISTRATION') . '&nbsp;';
				$list_participants.= '</div>';
			}

			// Full display
			elseif ($participantDisplay == 1)
			{
				$column = isset($fullListColumns) ? $fullListColumns : 'tiers';

				$list_participants.= '<div ' . $slider_c . '>';

				if ($participantSlide == 1) $list_participants.= '<div class="collapse" id="listParticipants">';

				foreach ($registeredUsers as $reguser)
				{
					$this_reg_date  = strtotime($reguser->regDate)
									? HTMLHelper::date($reguser->regDate, 'Y-m-d H:i', $eventTimeZone)
									: $reguser->regDate;

					if ( ($this_reg_date == $this_date || empty($get_date))
						|| $typeReg == 2
						|| ($typeReg == 1 && ! $this_reg_date)
						)
					{
						// Get participant privacy consents
						$user_actions = $reguser->user_action ? explode(',', $reguser->user_action) : [];

						// Get Name consent
						$name_consents = array();

//						foreach ($user_actions AS $k => $action)
//						{
//							$ex_action = explode('__', $action);

//							if ($ex_action[0] == 'consent_name'
//								&& isset($ex_action[1]))
//							{
//								$name_consents = explode('_', $ex_action[1]);
//							}
//						}

						if (in_array('consent_name_public', $user_actions)) $name_consents = array('public');
						if (in_array('consent_name_users', $user_actions)) $name_consents = array('users');

						$force_name_visibility = $iCparams->get('participant_name_consent', '');

						if ($force_name_visibility == '0')
						{
							$participant_name_visibility = $iCparams->get('participant_name_visibility', '');

							if ($participant_name_visibility == 1)
							{
								$name_consents = array('public');
							}
							elseif ($participant_name_visibility == 2)
							{
								$name_consents = array('users');
							}
						}

						if (in_array('public', $name_consents)
							|| (in_array('users', $name_consents) && Factory::getUser()->id > 0))
						{
							// Get Username and name (To check: Is it needed ?)
							if ( ! empty($reguser->userid))
							{
								$data_name     = $reguser->name;
								$data_username = $reguser->username;

								if ($nameJoomlaUser == 1)
								{
									$reguser->registeredUsers = $reguser->registeredUsers;
								}
								else
								{
									$reguser->registeredUsers = $data_username;
								}
							}

							$participant_name = $reguser->registeredUsers;
						}
						elseif($organiser_rights)
						{
							// Information to be extended for logged-in user with admin/organiser permissions...
							$participant_name = $reguser->registeredUsers;
						}
						else
						{
							$participant_name = Text::_('IC_ANONYMOUS');
						}

						// Get Gravatar consent
						if (in_array('consent_gravatar', $user_actions))
						{
							$avatar             = md5(strtolower(trim($reguser->regEmail)));
							$participant_avatar = '<img alt="' . $participant_name . '"  src="https://www.gravatar.com/avatar/' . $avatar . '?s=36&d=mm"/>';
						}
						else
						{
							$participant_avatar = '<span class="iCicon-avatar ic-avatar"></span>';
						}

						$regDate = '';


						// Test if registered date before 3.3.3 could be converted
						if (strtotime($reguser->regDate))
						{
							// Control if date valid format (Y-m-d H:i)
							$datetime_format   = 'Y-m-d H:i:s';
							$datetime_input    = $reguser->regDate;
							$datetime_input    = trim($datetime_input);
							$datetime_is_valid = date($datetime_format, strtotime($datetime_input)) == $datetime_input;

							if ($datetime_is_valid) // New Data value (since 3.3.3)
							{
								$ex_reg_datetime_db = explode (' ', $datetime_input);
								$registered_date    = icagendaRender::dateToFormat(date('Y-m-d', strtotime($ex_reg_datetime_db['0'])));
								$reg_time_get       = isset($ex_reg_datetime_db['1']) ? $ex_reg_datetime_db['1'] : '';
							}
							else // Test if old date format (before 3.3.3) could be converted. If not, displays old format.
							{
								$ex_reg_datetime    = explode (' - ', trim($reguser->regDate));

								// Control if date valid format (Y-m-d) - Means could be converted
								$date_format   = 'Y-m-d H:i:s';
								$date_input    = $ex_reg_datetime['0'];
								$date_input    = trim($date_input);
								$date_str      = strtotime($date_input);
								$date_is_valid = date($date_format, $date_str) == $date_input;

								if ($date_is_valid)
								{
									$registered_date = icagendaRender::dateToFormat(date('Y-m-d', $date_str));
								}
								else
								{
									$registered_date = $ex_reg_datetime['0'];
								}

								$reg_time_get = isset($ex_reg_datetime['1']) ? $ex_reg_datetime['1'] : '';
							}

							$regDate.= $registered_date;

							if ($reg_time_get)
							{
								$regDate.= ' - ' . date('H:i', strtotime($reg_time_get));
							}
						}
						else
						{
							$regDate.= $reguser->regDate;
						}

						// Display List of Participants
						if ($n <= $nbmax || $n == $nbusers)
						{
							$list_participants.= '<table class="list_table ' . $column . '" cellpadding="0">'
												. '<tbody>'
												. '<tr>';

							$list_participants.= '<td class="imgbox">';
							$list_participants.= $participant_avatar;
							$list_participants.= '</td>';

							$list_participants.= '<td valign="middle">'
												. '<span class="list_name">' . $participant_name . '</span>'
												. '<span class="list_places"> (' . $reguser->regPeople . ')</span><br />'
												. '<span class="list_date">' . $regDate . '</span>'
												. '</td>';

							$list_participants.= '</tr></tbody></table>';
						}
					}
				}

				if ($participantSlide == 1) $list_participants.= '</div>';

				$list_participants.= '</div>';
			}

			// Avatar display
			elseif ($participantDisplay == 2)
			{
				$list_participants.= '<div ' . $slider_c . '>';

				if ($participantSlide == 1) $list_participants.= '<div class="collapse" id="listParticipants">';

				foreach ($registeredUsers as $reguser)
				{
					$this_reg_date = strtotime($reguser->regDate)
									? HTMLHelper::date($reguser->regDate, 'Y-m-d H:i', $eventTimeZone)
									: $reguser->regDate;

					if ( ($this_reg_date == $this_date || empty($get_date))
						|| $typeReg == 2
						|| ($typeReg == 1 && ! $this_reg_date)
						)
					{
						// Get participant privacy consents
						$user_actions = explode(',', $reguser->user_action);

						// Get Name consent
						$name_consents = array();

//						foreach ($user_actions AS $k => $action)
//						{
//							$ex_action = explode('__', $action);

//							if ($ex_action[0] == 'consent_name'
//								&& isset($ex_action[1]))
//							{
//								$name_consents = explode('_', $ex_action[1]);
//							}
//						}

						if (in_array('consent_name_public', $user_actions)) $name_consents = array('public');
						if (in_array('consent_name_users', $user_actions)) $name_consents = array('users');

						$force_name_visibility = $iCparams->get('participant_name_consent', '');

						if ($force_name_visibility == '0')
						{
							$participant_name_visibility = $iCparams->get('participant_name_visibility', '');

							if ($participant_name_visibility == 1)
							{
								$name_consents = array('public');
							}
							elseif ($participant_name_visibility == 2)
							{
								$name_consents = array('users');
							}
						}

						if (in_array('public', $name_consents)
							|| (in_array('users', $name_consents) && Factory::getUser()->id > 0))
						{
							// Get Username and name (To check: Is it needed ?)
							if ( ! empty($reguser->userid))
							{
								$data_name     = $reguser->name;
								$data_username = $reguser->username;

								if ($nameJoomlaUser == 1)
								{
									$reguser->registeredUsers = $reguser->registeredUsers;
								}
								else
								{
									$reguser->registeredUsers = $data_username;
								}
							}

							$participant_name = $reguser->registeredUsers;
						}
						elseif($organiser_rights)
						{
							// Information to be extended for logged-in user with admin/organiser permissions...
							$participant_name = $reguser->registeredUsers;
						}
						else
						{
							$participant_name = Text::_('IC_ANONYMOUS');
						}

						// Get Gravatar consent
						if (in_array('consent_gravatar', $user_actions))
						{
							$avatar             = md5(strtolower(trim($reguser->regEmail)));
							$participant_avatar = '<img alt="' . $participant_name . '"  src="https://www.gravatar.com/avatar/' . $avatar . '?s=36&d=mm"/>';
						}
						else
						{
							$participant_avatar = '<span class="iCicon-avatar ic-avatar"></span>';
						}

						if ($n <= $nbmax || $n == $nbusers)
						{
							$list_participants.= '<div style="float:left; margin:3px; padding: 5px 0; text-align:center;"><div class="imgbox">' . $participant_avatar . '</div><strong style="text-align:center; font-size:9px;">' . $participant_name . '</strong></div>';
						}
					}
				}

				if ($participantSlide == 1) $list_participants.= '</div>';

				$list_participants.= '</div>';
			}

			// Name/username display
			elseif ($participantDisplay == 3)
			{
				$list_participants.= '<div ' . $slider_c . '>';

				if ($participantSlide == 1) $list_participants.= '<div class="collapse" id="listParticipants">';

				$list_participants.= '<div class="' . $n_list . '">';

				$list_username = '';

				foreach ($registeredUsers as $reguser)
				{
					$this_reg_date  = strtotime($reguser->regDate)
									? HTMLHelper::date($reguser->regDate, 'Y-m-d H:i', $eventTimeZone)
									: $reguser->regDate;

					if ( ($this_reg_date == $this_date ||empty($get_date))
						|| $typeReg == 2
						|| ($typeReg == 1 && ! $this_reg_date)
						)
					{
						// Get participant privacy consents
						$user_actions = (isset($reguser->user_action) && $reguser->user_action) ? explode(',', $reguser->user_action) : [];

						// Get Name consent
						$name_consents = [];

//						foreach ($user_actions AS $k => $action)
//						{
//							$ex_action = explode('__', $action);

//							if ($ex_action[0] == 'consent_name'
//								&& isset($ex_action[1]))
//							{
//								$name_consents = explode('_', $ex_action[1]);
//							}
//						}

						if (in_array('consent_name_public', $user_actions)) $name_consents = array('public');
						if (in_array('consent_name_users', $user_actions)) $name_consents = array('users');

						$force_name_visibility = $iCparams->get('participant_name_consent', '');

						if ($force_name_visibility == '0')
						{
							$participant_name_visibility = $iCparams->get('participant_name_visibility', '');

							if ($participant_name_visibility == 1)
							{
								$name_consents = array('public');
							}
							elseif ($participant_name_visibility == 2)
							{
								$name_consents = array('users');
							}
						}

						if (in_array('public', $name_consents)
							|| (in_array('users', $name_consents) && Factory::getUser()->id > 0))
						{
							// Get Username and name (To check: Is it needed ?)
							if ( ! empty($reguser->userid))
							{
								$data_name     = $reguser->name;
								$data_username = $reguser->username;

								if ($nameJoomlaUser == 1)
								{
									$reguser->registeredUsers = $reguser->registeredUsers;
								}
								else
								{
									$reguser->registeredUsers = $data_username;
								}
							}

							$participant_name = $reguser->registeredUsers;
						}
						elseif($organiser_rights)
						{
							// Information to be extended for logged-in user with admin/organiser permissions...
							$participant_name = $reguser->registeredUsers;
						}
						else
						{
							$participant_name = Text::_('IC_ANONYMOUS');
						}


						$list_username.= $participant_name . ', ';
					}
				}

				$list_participants.= trim($list_username, ", ");

				$list_participants.= '</div>';

				if ($participantSlide == 1) $list_participants.= '</div>';

				$list_participants.= '</div>';
			}
		}
		else
		{
			$list_participants.= '';
		}

		return $list_participants;
	}
}
