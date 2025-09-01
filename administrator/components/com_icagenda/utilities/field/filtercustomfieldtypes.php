<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.8.0 2021-09-07
 *
 * @package     iCagenda.Admin
 * @subpackage  Utilities.Form
 * @link        https://www.icagenda.com
 *
 * @author      Cyril Rezé
 * @copyright   (c) 2012-2024 Cyril Rezé / iCagenda. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 *
 * @since       3.8.0
 *----------------------------------------------------------------------------
*/

defined('_JEXEC') or die;

use Joomla\CMS\Language\Text;

JFormHelper::loadFieldClass('list');

/**
 * Categories Select Filter Field
 */
class icagendaFormFieldFilterCustomfieldTypes extends JFormFieldList
{
	protected $type = 'FilterCustomfieldTypes';

	protected function getOptions()
	{
		$list['text']               = Text::_('COM_ICAGENDA_CUSTOMFIELD_TYPE_TEXT');
		$list['list']               = Text::_('COM_ICAGENDA_CUSTOMFIELD_TYPE_LIST');
		$list['radio']              = Text::_('COM_ICAGENDA_CUSTOMFIELD_TYPE_RADIO');
		$list['calendar']           = Text::_('COM_ICAGENDA_CUSTOMFIELD_TYPE_DATE');
		$list['url']                = Text::_('COM_ICAGENDA_CUSTOMFIELD_TYPE_URL');
		$list['email']              = Text::_('COM_ICAGENDA_CUSTOMFIELD_TYPE_EMAIL');
		$list['spacer_label']       = Text::_('COM_ICAGENDA_CUSTOMFIELD_TYPE_SPACER_LABEL');
		$list['spacer_description'] = Text::_('COM_ICAGENDA_CUSTOMFIELD_TYPE_SPACER_DESCRIPTION');
		$list['core_name']          = Text::_('COM_ICAGENDA_LABEL_OVERRIDE') . ': ' . Text::_('COM_ICAGENDA_REGISTRATION_NAME');
		$list['core_email']         = Text::_('COM_ICAGENDA_LABEL_OVERRIDE') . ': ' . Text::_('COM_ICAGENDA_REGISTRATION_EMAIL');
		$list['core_phone']         = Text::_('COM_ICAGENDA_LABEL_OVERRIDE') . ': ' . Text::_('COM_ICAGENDA_REGISTRATION_PHONE');
		$list['core_date']          = Text::_('COM_ICAGENDA_LABEL_OVERRIDE') . ': ' . Text::_('COM_ICAGENDA_REGISTRATION_DATE');
		$list['core_people']        = Text::_('COM_ICAGENDA_LABEL_OVERRIDE') . ': ' . Text::_('COM_ICAGENDA_REGISTRATION_TICKETS');

		return array_merge(parent::getOptions(), $list);
	}
}
