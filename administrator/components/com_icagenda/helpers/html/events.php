<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.8.0 2021-09-26
 *
 * @package     iCagenda.Admin
 * @subpackage  helpers.html
 * @link        https://www.icagenda.com
 *
 * @author      Cyril Rezé
 * @copyright   (c) 2012-2024 Cyril Rezé / iCagenda. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 *
 * @since       3.1.10
 *----------------------------------------------------------------------------
*/

defined('_JEXEC') or die;

/**
 * Extended Utility class for the iCagenda component.
 */
class JHtmlEvents
{

	public static function approveEvents()
	{
		JFactory::getDocument()->addStyleDeclaration('
			.tbody-icon .icon-radio-checked {
				color: var(--info);
				border-color: #cdcdcd;
			}
			.tbody-icon .icon-radio-unchecked {
				color: #cdcdcd;
				border-color: var(--warning);
			}
		');

		$states = array(
			1 => array(
				'img'            => 'tick.png',
				'task'           => 'approve',
				'text'           => '',
				'active_title'   => 'COM_ICAGENDA_TOOLBAR_APPROVE',
				'inactive_title' => '',
				'tip'            => true,
				'active_class'   => 'radio-unchecked',
				'inactive_class' => 'radio-unchecked'
			),
			0 => array(
				'img'            => 'publish_x.png',
				'task'           => '',
				'text'           => '',
				'active_title'   => '',
				'inactive_title' => 'COM_ICAGENDA_APPROVED',
				'tip'            => false,
				'active_class'   => 'radio-checked',
				'inactive_class' => 'radio-checked'
			)
		);

		return $states;
	}
}
