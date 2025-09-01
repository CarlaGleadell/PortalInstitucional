<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Plugin Privacy - iCagenda
 *----------------------------------------------------------------------------
 * @version     2.0.0 2024-02-14
 *
 * @package     iCagenda
 * @subpackage  Plugin.Privacy.Icagenda
 * @link        https://www.icagenda.com
 *
 * @author      Cyril Rezé
 * @copyright   (c) 2013-2024 Cyril Rezé / iCagenda. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 *
 * @since       iCagenda 3.7.5
 *----------------------------------------------------------------------------
*/

namespace W3biC\Plugin\Privacy\Icagenda\Extension;

use iCutilities\Customfields\Customfields as icagendaCustomfields;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;
use Joomla\CMS\User\User;
use Joomla\Component\Privacy\Administrator\Plugin\PrivacyPlugin;
use Joomla\Component\Privacy\Administrator\Table\RequestTable;

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

/**
 * Privacy Icagenda Plugin.
 */
final class Icagenda extends PrivacyPlugin
{
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
	protected $events = [];

	/**
	 * Participants array
	 *
	 * @var    Array
	 * @since  1.0
	 */
	protected $participants = [];

	/**
	 * Processes an export request for Joomla core user registration data
	 *
	 * This event will collect data for the contact core tables:
	 *
	 * - Contact custom fields
	 *
	 * @param   RequestTable  $request  The request record being processed
	 * @param   User          $user     The user account associated with this request if available
	 *
	 * @return  \Joomla\Component\Privacy\Administrator\Export\Domain[]
	 *
	 * @since   1.0
	 */
	public function onPrivacyExportRequest(RequestTable $request, User $user = null)
	{
		if ( ! $user && ! $request->email) {
			return [];
		}

		$domains   = [];

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
	 * @param   RequestTable  $request  The request record being processed
	 * @param   User          $user     The user account associated with this request if available
	 *
	 * @return  \Joomla\Component\Privacy\Administrator\Export\Domain[]
	 *
	 * @since   1.0
	 */
	private function createEventsDomain(RequestTable $request, User $user = null)
	{
		$domain = $this->createDomain(
			'icagenda_events',
			'user_icagenda_events_data'
		);

		$db = $this->getDatabase();

		if ($user) {
			$query = $db->getQuery(true)
				->select('*')
				->from($db->quoteName('#__icagenda_events'))
				->where($db->quoteName('created_by') . ' = ' . (int) $user->id
					. ' OR ' . $db->quoteName('created_by_email') . ' = ' . $db->quote($request->email))
				->order($db->quoteName('ordering') . ' ASC');
		} else {
			$query = $db->getQuery(true)
				->select('*')
				->from($db->quoteName('#__icagenda_events'))
				->where($db->quoteName('created_by_email') . ' = ' . $db->quote($request->email))
				->order($db->quoteName('ordering') . ' ASC');
		}

		$items = $db->setQuery($query)->loadAssocList();

		foreach ($items as $item) {
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

		foreach ($events as $event) {
			// Get item's fields, also preparing their value property for manual display
			$fields = icagendaCustomfields::getList($event->id, '2', '1');

			if ($fields) {
				foreach ($fields as $field) {
					$data = [
						'event_id'    => $event->id,
						'field_slug'  => $field->cf_slug,
						'field_title' => $field->cf_title,
						'field_value' => $field->cf_value,
					];

					$domain->addItem($this->createItemFromArray($data));
				}
			}
		}

		return $domain;
	}

	/**
	 * Create the domain for the user registration data
	 *
	 * @param   RequestTable  $request  The request record being processed
	 * @param   User          $user     The user account associated with this request if available
	 *
	 * @return  \Joomla\Component\Privacy\Administrator\Export\Domain[]
	 *
	 * @since   1.0
	 */
	private function createRegistrationDomain(RequestTable $request, User $user = null)
	{
		$domain = $this->createDomain(
			'icagenda_registration',
			'user_icagenda_registration_data'
		);

		$db = $this->getDatabase();

		if ($user) {
			$query = $db->getQuery(true)
				->select('*')
				->from($db->quoteName('#__icagenda_registration'))
				->where($db->quoteName('userid') . ' = ' . (int) $user->id
					. ' OR ' . $db->quoteName('email') . ' = ' . $db->quote($request->email))
				->order($db->quoteName('ordering') . ' ASC');
		} else {
			$query = $db->getQuery(true)
				->select('*')
				->from($db->quoteName('#__icagenda_registration'))
				->where($db->quoteName('email') . ' = ' . $db->quote($request->email))
				->order($db->quoteName('ordering') . ' ASC');
		}

		$items = $db->setQuery($query)->loadAssocList();

		foreach ($items as $item) {
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

		foreach ($registrations as $registration) {
			// Get item's fields, also preparing their value property for manual display
			$fields = icagendaCustomfields::getList($registration->id, '1', '1');

			if ($fields) {
				foreach ($fields as $field) {
					$data = [
						'registration_id' => $registration->id,
						'field_slug'      => $field->cf_slug,
						'field_title'     => $field->cf_title,
						'field_value'     => $field->cf_value,
					];

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

		$joomlaIntegration  = Text::_('PLG_PRIVACY_ICAGENDA_JOOMLA_INTEGRATION')
			. '<ul>'
			. '<li>' . Text::sprintf('PLG_PRIVACY_ICAGENDA_JOOMLA_ACTION_LOGS',
				Route::_('index.php?option=com_plugins&view=plugins&filter_folder=actionlog&filter_element=icagenda') . '" target="_blank') . '</li>'
			. '<li>' . Text::sprintf('PLG_PRIVACY_ICAGENDA_JOOMLA_PRIVACY_INFORMATION_REQUESTS',
				Route::_('index.php?option=com_plugins&view=plugins&filter_folder=privacy&filter_element=icagenda')) . '</li>'
			. '</ul>'
			. '<em>' . Text::_('PLG_PRIVACY_ICAGENDA_NOTE') . ' ' . Text::_('PLG_PRIVACY_ICAGENDA_JOOMLA_PRIVACY_CONSENTS_BY_ICAGENDA') . '</em><br /><br />';

		$componentNetwork   = Text::_('PLG_PRIVACY_ICAGENDA_COMPONENT_NETWORK')
			. '<ul>'
			. '<li>' . Text::_('PLG_PRIVACY_ICAGENDA_COMPONENT_NETWORK_UPDATES') . '</li>'
			. '<li>' . Text::_('PLG_PRIVACY_ICAGENDA_COMPONENT_NETWORK_HELP') . '</li>'
			. '</ul><br />';

		$thirdpartyServices = Text::_('PLG_PRIVACY_ICAGENDA_COMPONENT_THIRDPARTY') . '*'
			. '<br /><small>*&#160;' . Text::_('PLG_PRIVACY_ICAGENDA_COMPONENT_THIRDPARTY_SUBLABEL') . '</small>'
			. '<ul>'
			. '<li><strong>' . Text::_('PLG_PRIVACY_ICAGENDA_COMPONENT_CAPTCHA_LABEL') . '</strong><br />'
			. Text::_('PLG_PRIVACY_ICAGENDA_COMPONENT_CAPTCHA_DESC') . '</li>'
			. '<li><strong>' . Text::_('PLG_PRIVACY_ICAGENDA_COMPONENT_GOOGLEMAPS_LABEL') . '</strong><br />'
			. Text::sprintf('PLG_PRIVACY_ICAGENDA_COMPONENT_GOOGLEMAPS_DESC', 'https://cloud.google.com/maps-platform/terms/" target="_blank" rel="noopener noreferrer') . '</li>'
			. '<li><strong>' . Text::_('PLG_PRIVACY_ICAGENDA_COMPONENT_GRAVATAR_LABEL') . '</strong><br />'
			. Text::sprintf('PLG_PRIVACY_ICAGENDA_COMPONENT_GRAVATAR_DESC', 'https://gravatar.com" target="_blank" rel="noopener noreferrer') . '</li>'
			. '</ul>';
		
		return [
			Text::_('iCagenda') => [
				$joomlaIntegration,
				$componentNetwork,
				$thirdpartyServices,
			],
		];
	}
}
