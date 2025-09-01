<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.9.0 2024-02-24
 *
 * @package     iCagenda.Admin
 * @subpackage  src.Helper
 * @link        https://www.icagenda.com
 *
 * @author      Cyril Rezé
 * @copyright   (c) 2012-2024 Cyril Rezé / iCagenda. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 *
 * @since       3.8
 *----------------------------------------------------------------------------
*/

namespace WebiC\Component\iCagenda\Administrator\Helper;

\defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Object\CMSObject;

/**
 * iCagenda Helper.
 */
class iCagendaHelper
{
	/**
	 * Gets a list of the actions that can be performed.
	 */
	public static function getActions($messageId = 0)
	{
		$user   = Factory::getUser();
		$result = new CMSObject;

		if (empty($messageId)) {
			$assetName = 'com_icagenda';
		} else {
			$assetName = 'com_icagenda.message.'.(int) $messageId;
		}

		$actions = [
			'core.admin',
			'core.manage',
			'core.create',
			'core.edit',
			'core.delete',
			'core.edit.state',
			'core.edit.own',
			'icagenda.access.categories',
			'icagenda.access.events',
			'icagenda.access.registrations',
			'icagenda.access.newsletter',
			'icagenda.access.customfields',
			'icagenda.access.features',
			'icagenda.access.themes'
		];

		foreach ($actions as $action) {
			$result->set($action, $user->authorise($action, $assetName));
		}

		return $result;
	}

	/**
	 * Tests whether a string is serialized before attempting to unserialize it
	 *
	 * ( TO BE REMOVED WHEN ALL CALLS FROM IC LIBRARY !!! )
	 */
	public static function isSerialized($str)
	{
		return ($str == serialize(false) || @unserialize($str) !== false);
	}

	/**
	 * Switch Approval State for Events
	 */
	public static function approveEvents()
	{
		Factory::getDocument()->addStyleDeclaration('
			.tbody-icon .icon-radio-checked {
				color: var(--info);
				border-color: #cdcdcd;
			}
			.tbody-icon .icon-radio-unchecked {
				color: #cdcdcd;
				border-color: var(--warning);
			}
		');

		$states = [
			1 => [
				'img'            => 'tick.png',
				'task'           => 'approve',
				'text'           => '',
				'active_title'   => 'COM_ICAGENDA_TOOLBAR_APPROVE',
				'inactive_title' => '',
				'tip'            => true,
				'active_class'   => 'radio-unchecked',
				'inactive_class' => 'radio-unchecked'
			],
			0 => [
				'img'            => 'publish_x.png',
				'task'           => '',
				'text'           => '',
				'active_title'   => '',
				'inactive_title' => 'COM_ICAGENDA_APPROVED',
				'tip'            => false,
				'active_class'   => 'radio-checked',
				'inactive_class' => 'radio-checked'
			],
		];

		return $states;
	}
}
