<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.9.1 2024-03-12
 *
 * @package     iCagenda.Site
 * @link        https://www.icagenda.com
 *
 * @author      Cyril Rezé
 * @copyright   (c) 2012-2024 Cyril Rezé / iCagenda. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 *
 * @since       3.6
 *----------------------------------------------------------------------------
*/

defined('JPATH_BASE') or die;

use Joomla\CMS\Language\Text as JText;

JFormHelper::loadFieldClass('list');
/**
 * Month frontend search filter.
 */
class JFormFieldMonth extends JFormFieldList
{
	/**
	 * The form field type.
	 *
	 * @var		string
	 * @since	3.6.0
	 */
	protected $type = 'month';

	/**
	 * Method to get the field options.
	 *
	 * @return	array	The field option objects.
	 * @since	3.6.0
	 */
	public function getOptions()
	{
		// Initialize variables.
		$options = array(
					'1' => JText::_('JANUARY'),
					'2' => JText::_('FEBRUARY'),
					'3' => JText::_('MARCH'),
					'4' => JText::_('APRIL'),
					'5' => JText::_('MAY'),
					'6' => JText::_('JUNE'),
					'7' => JText::_('JULY'),
					'8' => JText::_('AUGUST'),
					'9' => JText::_('SEPTEMBER'),
					'10' => JText::_('OCTOBER'),
					'11' => JText::_('NOVEMBER'),
					'12' => JText::_('DECEMBER'),
					);

		return $options;
	}
}
