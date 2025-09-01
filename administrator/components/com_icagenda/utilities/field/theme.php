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
 * @since       1.0.0
 *----------------------------------------------------------------------------
*/

defined('_JEXEC') or die;

jimport( 'joomla.filesystem.path' );
jimport('joomla.form.formfield');

class icagendaFormFieldTheme extends JFormField
{
	protected $type = 'Theme';

	protected function getInput()
	{
		$url	= JPATH_SITE.'/components/com_icagenda/themes/packs';
		$list	= $this->getList($url);
		$class 	= !empty($this->class) ? ' class="' . $this->class . '"' : '';
		$html	= '<select id="' . $this->id . '_id"' . $class . ' name="' . $this->name . '">';

		foreach ($list as $l)
		{
			$html.= '<option value="' . $l . '"';

			if ($this->value == $l)
			{
				$html.= ' selected="selected"';
			}

			$html.= '>' . $l . '</option>';
		}

		$html.= '</select>';

		return $html;
	}

	function getList($dirname)
	{
		$arrayfiles = Array();

		if (file_exists($dirname))
		{
			$handle = opendir($dirname);

			while (false !== ($file = readdir($handle)))
			{
				if (!is_file($dirname.$file)
					&& $file != '.'
					&& $file != '..'
					&& $file != '.DS_Store'
					&& $file != '.htaccess'
					&& $file != '.thumbs'
					&& $file != 'index.php'
					&& $file != 'index.html'
					&& $file != 'php.ini'
					)
				{
					array_push($arrayfiles, $file);
				}
			}

			$handle = closedir($handle);
		}

		sort($arrayfiles);

		return $arrayfiles;
	}
}
