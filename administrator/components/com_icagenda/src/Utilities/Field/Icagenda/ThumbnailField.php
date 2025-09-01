<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.9.0 2024-02-21
 *
 * @package     iCagenda.Admin
 * @subpackage  Utilities.Field.Icagenda
 * @link        https://www.icagenda.com
 *
 * @author      Cyril Rezé
 * @copyright   (c) 2012-2024 Cyril Rezé / iCagenda. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 *
 * @since       3.5.0
 *----------------------------------------------------------------------------
*/

namespace iCutilities\Field\Icagenda;

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Form\FormField;
use Joomla\CMS\Language\Text;

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

/**
 * Thumbnail Form Field class for iCagenda.
 * Supports width, height, quality and crop options for thumbnail generator.
 */
class ThumbnailField extends FormField
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 */
	protected $type = 'Thumbnail';

	/**
	 * Method to get the field input markup.
	 *
	 * @return  string  The field input markup.
	 */
	protected function getInput()
	{
		$iCparams = ComponentHelper::getParams('com_icagenda');

		$replace   = ['jform', 'params', '[', ']'];
		$nameInput = str_replace($replace, '', $this->name);

		switch ($nameInput) {
			case 'thumb_large':
				$thumbOptions = $iCparams->get('thumb_large', array('900', '600', '100', 0));
				$width   = is_numeric($thumbOptions[0]) ? $thumbOptions[0] : '900';
				$height  = is_numeric($thumbOptions[1]) ? $thumbOptions[1] : '600';
				$quality = is_numeric($thumbOptions[2]) ? $thumbOptions[2] : '100';
				$crop    = isset($thumbOptions[3]) ? $thumbOptions[3] : false;
				break;

			case 'thumb_medium':
				$thumbOptions = $iCparams->get('thumb_medium', array('300', '300', '100', 0));
				$width   = is_numeric($thumbOptions[0]) ? $thumbOptions[0] : '300';
				$height  = is_numeric($thumbOptions[1]) ? $thumbOptions[1] : '300';
				$quality = is_numeric($thumbOptions[2]) ? $thumbOptions[2] : '100';
				$crop    = isset($thumbOptions[3]) ? $thumbOptions[3] : false;
				break;

			case 'thumb_small':
				$thumbOptions = $iCparams->get('thumb_small', array('100', '100', '100', 0));
				$width   = is_numeric($thumbOptions[0]) ? $thumbOptions[0] : '100';
				$height  = is_numeric($thumbOptions[1]) ? $thumbOptions[1] : '100';
				$quality = is_numeric($thumbOptions[2]) ? $thumbOptions[2] : '100';
				$crop    = isset($thumbOptions[3]) ? $thumbOptions[3] : false;
				break;

			case 'thumb_xsmall':
				$thumbOptions = $iCparams->get('thumb_xsmall', array('48', '48', '100', 1));
				$width   = is_numeric($thumbOptions[0]) ? $thumbOptions[0] : '48';
				$height  = is_numeric($thumbOptions[1]) ? $thumbOptions[1] : '48';
				$quality = is_numeric($thumbOptions[2]) ? $thumbOptions[2] : '80';
				$crop    = isset($thumbOptions[3]) ? $thumbOptions[3] : true;
				break;

			default:
				$width   = '900';
				$height  = '600';
				$quality = '100';
				$crop    = '0';
		}

		$crop_false = $crop_true = '';

		if ($crop) {
			$crop_true = ' selected="selected"';
		} else {
			$crop_false = ' selected="selected"';
		}

		$quality_values = ['100', '95', '90', '85', '80', '75', '70', '60', '50'];

		$col    = 'col-lg-6 col-xl-3';
		$input  = 'form-control';
		$select = 'form-select';

		$html = '';

		$html.= '<div class="row">';

		$html.= '<div class="' . $col . '">' . Text::_('IC_WIDTH') . '<br />';
		$html.= '<input type="text" class="' . $input . '" name="' . $this->name . '[]" value="' . $width . '"/></div>';

		$html.= '<div class="' . $col . '">' . Text::_('IC_HEIGHT') . '<br />';
		$html.= '<input type="text" class="' . $input . '" name="' . $this->name . '[]" value="' . $height . '"/></div>';

		$html.= '<div class="' . $col . '">' . Text::_('IC_QUALITY') . '<br />';
		$html.= '<select id="Thumb_quality" class="' . $select . '" name="' . $this->name . '[]" value="' . $quality . '">';

		foreach ($quality_values as $qv) {
			$html.= '<option value="' . $qv . '"';

			if ($qv == $quality) {
				$html.= ' selected="selected"';
			}

			$html.= '>' . Text::_('IC' . $qv . '') . '</option>';
		}

		$html.= '</select></div>';

		$html.= '<div class="' . $col . '">' . Text::_('IC_CROPPED') . '<br />';
		$html.= '<select id="Thumb_crop" class="' . $select . '" name="' . $this->name . '[]" value="' . $crop . '">';
		$html.= '<option value="0" ' . $crop_false . '>' . Text::_('JNO') . '</option>';
		$html.= '<option value="1" ' . $crop_true . '>' . Text::_('JYES') . '</option>';
		$html.= '</select></div>';

		$html.= '</div>';

		return $html;
	}
}
