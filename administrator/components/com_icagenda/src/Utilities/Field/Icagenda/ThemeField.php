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
 * @since       1.0.0
 *----------------------------------------------------------------------------
*/

namespace iCutilities\Field\Icagenda;

use Joomla\CMS\Form\Field\ListField;

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

/**
 * Theme Form Field class for iCagenda.
 */
class ThemeField extends ListField
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 */
	protected $type = 'Theme';

	/**
	 * Method to get the field input markup for a media upload.
	 *
	 * @return  string  The field input markup.
	 */
	protected function getInput()
	{
		$class = !empty($this->class) ? ' class="' . $this->class . '"' : '';

		$html = '<select id="' . $this->id . '_id"' . $class . ' name="' . $this->name . '">';

		$url  = JPATH_SITE.'/components/com_icagenda/themes/packs';
		$list = $this->getThemesList($url);

		foreach ($list as $theme) {
			$html.= '<option value="' . $theme . '"';

			if ($this->value == $theme) {
				$html.= ' selected="selected"';
			}

			$html.= '>' . $theme . '</option>';
		}

		$html.= '</select>';

		return $html;
	}

	/**
	 * Method to get the list of installed theme packs.
	 *
	 * @return  array  The list of installed theme packs.
	 */
	function getThemesList($dirname)
	{
		$themes = [];

		if (file_exists($dirname)) {
			$handle = opendir($dirname);

			while (false !== ($file = readdir($handle))) {
				if (!is_file($dirname.$file)
					&& $file != '.'
					&& $file != '..'
					&& $file != '.DS_Store'
					&& $file != '.htaccess'
					&& $file != '.thumbs'
					&& $file != 'index.php'
					&& $file != 'index.html'
					&& $file != 'php.ini'
				) {
					array_push($themes, $file);
				}
			}

			$handle = closedir($handle);
		}

		sort($themes);

		return $themes;
	}
}
