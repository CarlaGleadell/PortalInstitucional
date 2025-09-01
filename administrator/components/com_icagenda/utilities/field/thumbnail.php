<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.9.0 2024-02-21
 *
 * @package     iCagenda.Admin
 * @link        https://www.icagenda.com
 *
 * @author      Cyril Rezé
 * @copyright   (c) 2012-2024 Cyril Rezé / iCagenda. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 *
 * @since       3.5.0
 *----------------------------------------------------------------------------
*/

defined('_JEXEC') or die;

jimport( 'joomla.filesystem.path' );
jimport('joomla.form.formfield');

class icagendaFormFieldThumbnail extends JFormField
{
	protected $type='Thumbnail';

	protected function getInput()
	{
		$replace = array("jform", "params", "[", "]");
		$name_input = str_replace($replace, "", $this->name);

		jimport('joomla.application.component.helper');

		$iCparams = JComponentHelper::getParams('com_icagenda');

		if ($name_input == 'thumb_large')
		{
			$thumbOptions = $iCparams->get('thumb_large', array('900', '600', '100', 0));
			$width = is_numeric($thumbOptions[0]) ? $thumbOptions[0] : '900';
			$height = is_numeric($thumbOptions[1]) ? $thumbOptions[1] : '600';
			$quality = is_numeric($thumbOptions[2]) ? $thumbOptions[2] : '100';
			$crop = isset($thumbOptions[3]) ? $thumbOptions[3] : false;
			$default_width = '900';
			$default_height = '600';
			$default_quality = '100';
			$default_crop = '0';
		}
		elseif ($name_input == 'thumb_medium')
		{
			$thumbOptions = $iCparams->get('thumb_medium', array('300', '300', '100', 0));
			$width = is_numeric($thumbOptions[0]) ? $thumbOptions[0] : '300';
			$height = is_numeric($thumbOptions[1]) ? $thumbOptions[1] : '300';
			$quality = is_numeric($thumbOptions[2]) ? $thumbOptions[2] : '100';
			$crop = isset($thumbOptions[3]) ? $thumbOptions[3] : false;
			$default_width = '300';
			$default_height = '300';
			$default_quality = '100';
			$default_crop = '0';
		}
		elseif ($name_input == 'thumb_small')
		{
			$thumbOptions = $iCparams->get('thumb_small', array('100', '100', '100', 0));
			$width = is_numeric($thumbOptions[0]) ? $thumbOptions[0] : '100';
			$height = is_numeric($thumbOptions[1]) ? $thumbOptions[1] : '100';
			$quality = is_numeric($thumbOptions[2]) ? $thumbOptions[2] : '100';
			$crop = isset($thumbOptions[3]) ? $thumbOptions[3] : false;
			$default_width = '100';
			$default_height = '100';
			$default_quality = '100';
			$default_crop = '0';
		}
		elseif ($name_input == 'thumb_xsmall')
		{
			$thumbOptions = $iCparams->get('thumb_xsmall', array('48', '48', '100', 1));
			$width = is_numeric($thumbOptions[0]) ? $thumbOptions[0] : '48';
			$height = is_numeric($thumbOptions[1]) ? $thumbOptions[1] : '48';
			$quality = is_numeric($thumbOptions[2]) ? $thumbOptions[2] : '80';
			$crop = isset($thumbOptions[3]) ? $thumbOptions[3] : true;
			$default_width = '48';
			$default_height = '48';
			$default_quality = '80';
			$default_crop = '1';
		}

		$crop_false = $crop_true = '';

		if ($crop)
		{
			$crop_true = ' selected="selected"';
		}
		else
		{
			$crop_false = ' selected="selected"';
		}

		$quality_80 = '';

		if ($quality == '80')
		{
			$quality_80 =  ' selected="selected"';
		}

		$quality_values = array('100', '95', '90', '85', '80', '75', '70', '60', '50');

		$col    = version_compare(JVERSION, '4.0', 'lt') ? 'span2' : 'col-lg-6 col-xl-3';
		$input  = version_compare(JVERSION, '4.0', 'lt') ? 'input-mini' : 'form-control';
		$select = version_compare(JVERSION, '4.0', 'lt') ? 'input-small' : 'form-select';

		$html = array();

		$html[] = version_compare(JVERSION, '4.0', 'lt') ? '' : '<div class="row">';

		$html[] = '<div class="' . $col . '">' . JText::_('IC_WIDTH') . '<br />';
		$html[] = '<input type="text" class="' . $input . '" name="' . $this->name . '[]" value="' . $width . '" default="' . $default_width . '"/></div>';

		$html[] = '<div class="' . $col . '">' . JText::_('IC_HEIGHT') . '<br />';
		$html[] = '<input type="text" class="' . $input . '" name="' . $this->name . '[]" value="' . $height . '" default="' . $default_height . '"/></div>';

		$html[] = '<div class="' . $col . '">' . JText::_('IC_QUALITY') . '<br />';
		$html[] = '<select id="Thumb_quality" class="' . $select . '" name="' . $this->name . '[]" value="' . $quality . '">';

		foreach ($quality_values AS $qv)
		{
			$html[] = '<option value="' . $qv . '"';

			if ($qv == $quality)
			{
				$html[] = ' selected="selected"';
			}

			$html[] = '>' . JText::_('IC' . $qv . '') . '</option>';
		}

		$html[] = '</select></div>';

		$html[] = '<div class="' . $col . '">' . JText::_('IC_CROPPED') . '<br />';
		$html[] = '<select id="Thumb_crop" class="' . $select . '" name="' . $this->name . '[]" value="' . $crop . '">';
		$html[] = '<option value="0" ' . $crop_false . '>' . JText::_('JNO') . '</option>';
		$html[] = '<option value="1" ' . $crop_true . '>' . JText::_('JYES') . '</option>';
		$html[] = '</select></div>';

		$html[] = version_compare(JVERSION, '4.0', 'lt') ? '' : '</div>';

		return implode("\n", $html);
	}
}
