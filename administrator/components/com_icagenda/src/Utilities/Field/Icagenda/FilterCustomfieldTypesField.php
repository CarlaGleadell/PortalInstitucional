<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.9.0 2024-02-18
 *
 * @package     iCagenda.Admin
 * @subpackage  Utilities.Field.Icagenda
 * @link        https://www.icagenda.com
 *
 * @author      Cyril Rezé
 * @copyright   (c) 2012-2024 Cyril Rezé / iCagenda. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 *
 * @since       3.8.0
 *----------------------------------------------------------------------------
*/

namespace iCutilities\Field\Icagenda;

use Joomla\CMS\Form\Field\ListField;
use Joomla\CMS\Language\Text;

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

/**
 * Categories Select Filter Field
 */
class FilterCustomfieldTypesField extends ListField
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 */
	protected $type = 'FilterCustomfieldTypes';

	/**
	 * Method to get the field options.
	 *
	 * @return  array  The field option objects.
	 */
	protected function getOptions()
	{
		$list = [
			'text'               => Text::_('COM_ICAGENDA_CUSTOMFIELD_TYPE_TEXT'),
			'list'               => Text::_('COM_ICAGENDA_CUSTOMFIELD_TYPE_LIST'),
			'radio'              => Text::_('COM_ICAGENDA_CUSTOMFIELD_TYPE_RADIO'),
			'calendar'           => Text::_('COM_ICAGENDA_CUSTOMFIELD_TYPE_DATE'),
			'url'                => Text::_('COM_ICAGENDA_CUSTOMFIELD_TYPE_URL'),
			'email'              => Text::_('COM_ICAGENDA_CUSTOMFIELD_TYPE_EMAIL'),
			'spacer_label'       => Text::_('COM_ICAGENDA_CUSTOMFIELD_TYPE_SPACER_LABEL'),
			'spacer_description' => Text::_('COM_ICAGENDA_CUSTOMFIELD_TYPE_SPACER_DESCRIPTION'),
			'core_name'          => Text::_('COM_ICAGENDA_LABEL_OVERRIDE') . ': ' . Text::_('COM_ICAGENDA_REGISTRATION_NAME'),
			'core_email'         => Text::_('COM_ICAGENDA_LABEL_OVERRIDE') . ': ' . Text::_('COM_ICAGENDA_REGISTRATION_EMAIL'),
			'core_phone'         => Text::_('COM_ICAGENDA_LABEL_OVERRIDE') . ': ' . Text::_('COM_ICAGENDA_REGISTRATION_PHONE'),
			'core_date'          => Text::_('COM_ICAGENDA_LABEL_OVERRIDE') . ': ' . Text::_('COM_ICAGENDA_REGISTRATION_DATE'),
			'core_people'        => Text::_('COM_ICAGENDA_LABEL_OVERRIDE') . ': ' . Text::_('COM_ICAGENDA_REGISTRATION_TICKETS'),
		];

		return array_merge(parent::getOptions(), $list);
	}
}
