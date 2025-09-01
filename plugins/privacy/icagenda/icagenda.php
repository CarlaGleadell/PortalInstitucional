<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Plugin Privacy
 *----------------------------------------------------------------------------
 * @version     1.2.0 2023-06-04
 *
 * @package     iCagenda.Plugin
 * @subpackage  Privacy.icagenda
 * @link        https://www.icagenda.com
 *
 * @author      Cyril Rezé
 * @copyright   (c) 2012-2024 Cyril Rezé / iCagenda. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 *
 *  This program is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation, either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @since       iCagenda 3.7.5
 *----------------------------------------------------------------------------
*/

defined('_JEXEC') or die;

use iCutilities\Customfields\Customfields as icagendaCustomfields;
use Joomla\Utilities\ArrayHelper;

JLoader::register('PrivacyPlugin', JPATH_ADMINISTRATOR . '/components/com_privacy/helpers/plugin.php');

// Loads Utilities
JLoader::registerPrefix('icagenda', JPATH_ADMINISTRATOR . '/components/com_icagenda/utilities');

/**
 * Handle iCagenda integration in com_privacy capabilities (starting Joomla 3.9)
 */
class plgPrivacyIcagenda extends PrivacyPlugin
{
	/**
	 * Database object
	 *
	 * @var    JDatabaseDriver
	 * @since  1.0
	 */
	protected $db;

	/**
	 * Affects constructor behaviour. If true, language files will be loaded automatically.
	 *
	 * @var    boolean
	 * @since  1.0
	 */
	protected $autoloadLanguage = true;

	/**
	 * Events array
	 *
	 * @var    Array
	 * @since  1.0
	 */
	protected $events = array();

	/**
	 * Participants array
	 *
	 * @var    Array
	 * @since  1.0
	 */
	protected $participants = array();

	/**
	 * Processes an export request for Joomla core user registration data
	 *
	 * This event will collect data for the contact core tables:
	 *
	 * - Contact custom fields
	 *
	 * @param   PrivacyTableRequest  $request  The request record being processed
	 * @param   JUser                $user     The user account associated with this request if available
	 *
	 * @return  PrivacyExportDomain[]
	 *
	 * @since   1.0
	 */
	public function onPrivacyExportRequest(PrivacyTableRequest $request, JUser $user = null)
	{
		if ( ! $user && ! $request->email)
		{
			return array();
		}

		$domains   = array();

		// Create User Events Domain
		$domains[] = $this->createEventsDomain($request, $user);
		$domains[] = $this->createEventsCustomfieldsDomain($this->events);

		// Create User Registrations Domain
		$domains[] = $this->createRegistrationDomain($request, $user);
		$domains[] = $this->createRegistrationCustomfieldsDomain($this->participants);

		return $domains;
	}

	/**
	 * Create the domain for the user events data
	 *
	 * @param   PrivacyTableRequest  $request  The request record being processed
	 * @param   JUser                $user     The user account associated with this request if available
	 *
	 * @return  PrivacyExportDomain
	 *
	 * @since   1.0
	 */
	private function createEventsDomain(PrivacyTableRequest $request, JUser $user = null)
	{
		$domain = $this->createDomain(
			'icagenda_events',
			'user_icagenda_events_data'
		);

		if ($user)
		{
			$query = $this->db->getQuery(true)
				->select('*')
				->from($this->db->quoteName('#__icagenda_events'))
				->where($this->db->quoteName('created_by') . ' = ' . (int) $user->id
					. ' OR ' . $this->db->quoteName('created_by_email') . ' = ' . $this->db->quote($request->email))
				->order($this->db->quoteName('ordering') . ' ASC');
		}
		else
		{
			$query = $this->db->getQuery(true)
				->select('*')
				->from($this->db->quoteName('#__icagenda_events'))
				->where($this->db->quoteName('created_by_email') . ' = ' . $this->db->quote($request->email))
				->order($this->db->quoteName('ordering') . ' ASC');
		}

		$items = $this->db->setQuery($query)->loadAssocList();

		foreach ($items as $item)
		{
			$domain->addItem($this->createItemFromArray($item));
			$this->events[] = (object) $item;
		}

		return $domain;
	}

	/**
	 * Create the domain for the events custom fields
	 *
	 * @param   Object  $events  The events to process
	 *
	 * @return  PrivacyExportDomain
	 *
	 * @since   1.0
	 */
	private function createEventsCustomfieldsDomain($events)
	{
		$domain = $this->createDomain(
			'icagenda_events_customfields',
			'user_icagenda_events_customfields_data'
		);

		foreach ($events as $event)
		{
			// Get item's fields, also preparing their value property for manual display
			$fields = icagendaCustomfields::getList($event->id, '2', '1');

			if ($fields)
			{
				foreach ($fields as $field)
				{
					$data = array(
						'event_id'    => $event->id,
						'field_slug'  => $field->cf_slug,
						'field_title' => $field->cf_title,
						'field_value' => $field->cf_value,
					);

					$domain->addItem($this->createItemFromArray($data));
				}
			}
		}

		return $domain;
	}

	/**
	 * Create the domain for the user registration data
	 *
	 * @param   PrivacyTableRequest  $request  The request record being processed
	 * @param   JUser                $user     The user account associated with this request if available
	 *
	 * @return  PrivacyExportDomain
	 *
	 * @since   1.0
	 */
	private function createRegistrationDomain(PrivacyTableRequest $request, JUser $user = null)
	{
		$domain = $this->createDomain(
			'icagenda_registration',
			'user_icagenda_registration_data'
		);

		if ($user)
		{
			$query = $this->db->getQuery(true)
				->select('*')
				->from($this->db->quoteName('#__icagenda_registration'))
				->where($this->db->quoteName('userid') . ' = ' . (int) $user->id
					. ' OR ' . $this->db->quoteName('email') . ' = ' . $this->db->quote($request->email))
				->order($this->db->quoteName('ordering') . ' ASC');
		}
		else
		{
			$query = $this->db->getQuery(true)
				->select('*')
				->from($this->db->quoteName('#__icagenda_registration'))
				->where($this->db->quoteName('email') . ' = ' . $this->db->quote($request->email))
				->order($this->db->quoteName('ordering') . ' ASC');
		}

		$items = $this->db->setQuery($query)->loadAssocList();

		foreach ($items as $item)
		{
			$domain->addItem($this->createItemFromArray($item));
			$this->participants[] = (object) $item;
		}

		return $domain;
	}

	/**
	 * Create the domain for the registrations custom fields
	 *
	 * @param   Object  $registrations  The registrations to process
	 *
	 * @return  PrivacyExportDomain
	 *
	 * @since   1.0
	 */
	private function createRegistrationCustomfieldsDomain($registrations)
	{
		$domain = $this->createDomain(
			'icagenda_registration_customfields',
			'user_icagenda_registration_customfields_data'
		);

		foreach ($registrations as $registration)
		{
			// Get item's fields, also preparing their value property for manual display
			$fields = icagendaCustomfields::getList($registration->id, '1', '1');

			if ($fields)
			{
				foreach ($fields as $field)
				{
					$data = array(
						'registration_id' => $registration->id,
						'field_slug'      => $field->cf_slug,
						'field_title'     => $field->cf_title,
						'field_value'     => $field->cf_value,
					);

					$domain->addItem($this->createItemFromArray($data));
				}
			}
		}

		return $domain;
	}

	/**
	 * Reports the privacy related capabilities for iCagenda to site administrators.
	 *
	 * @return  array
	 *
	 * @since   1.0
	 */
	public function onPrivacyCollectAdminCapabilities()
	{
		$this->loadLanguage();

		$joomlaIntegration  = JText::_('PLG_PRIVACY_ICAGENDA_JOOMLA_INTEGRATION')
							. '<ul>'
							. '<li>' . JText::sprintf('PLG_PRIVACY_ICAGENDA_JOOMLA_ACTION_LOGS',
								JRoute::_('index.php?option=com_plugins&view=plugins&filter_folder=actionlog&filter_element=icagenda') . '" target="_blank') . '</li>'
							. '<li>' . JText::sprintf('PLG_PRIVACY_ICAGENDA_JOOMLA_PRIVACY_INFORMATION_REQUESTS',
								JRoute::_('index.php?option=com_plugins&view=plugins&filter_folder=privacy&filter_element=icagenda')) . '</li>'
							. '</ul>'
							. '<em>' . JText::_('PLG_PRIVACY_ICAGENDA_NOTE') . ' ' . JText::_('PLG_PRIVACY_ICAGENDA_JOOMLA_PRIVACY_CONSENTS_BY_ICAGENDA') . '</em><br /><br />';

		$componentNetwork   = JText::_('PLG_PRIVACY_ICAGENDA_COMPONENT_NETWORK')
							. '<ul>'
							. '<li>' . JText::_('PLG_PRIVACY_ICAGENDA_COMPONENT_NETWORK_UPDATES') . '</li>'
							. '<li>' . JText::_('PLG_PRIVACY_ICAGENDA_COMPONENT_NETWORK_HELP') . '</li>'
							. '</ul><br />';

		$thirdpartyServices = JText::_('PLG_PRIVACY_ICAGENDA_COMPONENT_THIRDPARTY') . '*'
							. '<br /><small>*&#160;' . JText::_('PLG_PRIVACY_ICAGENDA_COMPONENT_THIRDPARTY_SUBLABEL') . '</small>'
							. '<ul>'
							. '<li><strong>' . JText::_('PLG_PRIVACY_ICAGENDA_COMPONENT_CAPTCHA_LABEL') . '</strong><br />'
							. JText::_('PLG_PRIVACY_ICAGENDA_COMPONENT_CAPTCHA_DESC') . '</li>'
							. '<li><strong>' . JText::_('PLG_PRIVACY_ICAGENDA_COMPONENT_GOOGLEMAPS_LABEL') . '</strong><br />'
							. JText::sprintf('PLG_PRIVACY_ICAGENDA_COMPONENT_GOOGLEMAPS_DESC', 'https://cloud.google.com/maps-platform/terms/" target="_blank" rel="noopener noreferrer') . '</li>'
							. '<li><strong>' . JText::_('PLG_PRIVACY_ICAGENDA_COMPONENT_GRAVATAR_LABEL') . '</strong><br />'
							. JText::sprintf('PLG_PRIVACY_ICAGENDA_COMPONENT_GRAVATAR_DESC', 'https://gravatar.com" target="_blank" rel="noopener noreferrer') . '</li>'
							. '</ul>';
		
		return array(
			JText::_('iCagenda') => array(
				$joomlaIntegration,
				$componentNetwork,
				$thirdpartyServices,
			),
		);
	}
}
