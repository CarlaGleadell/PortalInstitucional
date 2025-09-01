<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.8.6 2022-05-12
 *
 * @package     iCagenda.Admin
 * @subpackage  src.Utilities.Render
 * @link        https://www.icagenda.com
 *
 * @author      Cyril Rezé
 * @copyright   (c) 2012-2024 Cyril Rezé / iCagenda. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 *
 * @since       3.6
 *----------------------------------------------------------------------------
*/

namespace iCutilities\Render;

\defined('_JEXEC') or die;

use iClib\Date\Date as iCDate;
use iClib\Globalize\Globalize as iCGlobalize;
use iClib\String\StringHelper as iCString;
use iClib\Url\Url as iCUrl;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Plugin\PluginHelper;
use Joomla\Registry\Registry;

/**
 * class icagendaRender
 */
class Render
{
	/**
	 * Function to return formatted title
	 */
	static public function titleToFormat($title)
	{
		$text_transform = ComponentHelper::getParams('com_icagenda')->get('titleTransform', '');

		$mbString = extension_loaded('mbstring');

		// Escape the output.
		$title = htmlspecialchars($title, ENT_COMPAT, 'UTF-8');

		if ($text_transform == 1)
		{
			$titleFormat = $mbString ? iCString::mb_ucfirst(mb_strtolower($title)) : ucfirst(strtolower($title));

			return $titleFormat;
		}
		elseif ($text_transform == 2)
		{
			$titleFormat = $mbString ? mb_convert_case($title, MB_CASE_TITLE, "UTF-8") : ucwords(strtolower($title));

			return $titleFormat;
		}
		elseif ($text_transform == 3)
		{
			$titleFormat = $mbString ? mb_strtoupper($title, "UTF-8") : strtoupper($title);

			return $titleFormat;
		}
		elseif ($text_transform == 4)
		{
			$titleFormat = $mbString ? mb_strtolower($title, "UTF-8") : strtolower($title);

			return $titleFormat;
		}

		return $title;
	}

	/**
	 * Function to return formatted date (using globalization from iC Library)
	 */
	static public function dateToFormat($date)
	{
		if (iCDate::isDate($date))
		{
			$app = Factory::getApplication();

			if ($app->isClient('site'))
			{
				// Set Date Format option to be used
				$format = $app->getParams()->get('format', ComponentHelper::getParams('com_icagenda')->get('date_format_global', 'Y - m - d'));

				// Separator Option
				$separator = $app->getParams()->get('date_separator', ' ');
			}
			else
			{
				// Date Format Option (Global Component Option)
				$format = ComponentHelper::getParams('com_icagenda')->get('date_format_global', 'Y - m - d');

				// Separator Option
				$separator = ComponentHelper::getParams('com_icagenda')->get('date_separator', ' ');
			}

			if ( ! is_numeric($format))
			{
				// Update old Date Format options of versions before 2.1.7
				$format = str_replace(array('nosep', 'sepb', 'sepa'), '', $format);
				$format = str_replace('.', ' .', $format);
				$format = str_replace(',', ' ,', $format);
			}

			$dateFormatted = iCGlobalize::dateFormat($date, $format, $separator);

			return $dateFormatted;
		}
		else
		{
			return false;
		}
	}

	/**
	 * Function to return formatted time
	 */
	static public function dateToTime($date)
	{
		if (iCDate::isDate($date))
		{
			$eventTimeZone = null;
			$datetime      = HTMLHelper::date($date, 'Y-m-d H:i', $eventTimeZone);
			$timeformat    = ComponentHelper::getParams('com_icagenda')->get('timeformat', 1);
			$lang_time     = ($timeformat == 1) ? 'H:i' : 'h:i A';
			$time          = date($lang_time, strtotime($datetime));

			return $time;
		}

		return false;
	}

	/**
	 * Function to return Email Cloaked TAG
	 *
	 * @since   3.6.8
	 */
	static public function emailTag($email)
	{
		$plugin = PluginHelper::getPlugin('content', 'emailcloak');

		if ($plugin)
		{
			$params = new Registry($plugin->params);
			$mode   = $params->get('mode', 1);

			return HTMLHelper::_('email.cloak', $email, $mode);
		}

		return $email;
	}

	/**
	 * Function to return Phone TAG
	 */
	static public function phoneTag($number)
	{
		if ($number)
		{
			$phone = strip_tags(trim($number));

			return '<a href="tel:' . $phone . '">' . $phone . '</a>';
		}

		return;
	}

	/**
	 * Function to return Website TAG
	 */
	static public function websiteTag($url)
	{
		$targetOption = ComponentHelper::getParams('com_icagenda')->get('targetLink', '');
		$target       = ! empty($targetOption) ? '_blank' : '_parent';

		$link         = iCUrl::urlParsed($url, 'scheme');

		return '<a href="' . $link . '" rel="nofollow" target="' . $target . '">' . $url . '</a>';
	}

	/**
	 * Function to return File attachment TAG
	 */
	static public function fileTag($file)
	{
		$path_parts = pathinfo($file);
		$attachment = isset($path_parts['filename'])
					? '<div class="ic-attachment-filename">' . $path_parts['filename'] . '</div>'
						. (isset($path_parts['extension'])
							? '<div class="ic-attachment-extension">.' . $path_parts['extension']  . '</div>'
							: '')
					: Text::_('COM_ICAGENDA_EVENT_DOWNLOAD');

		$fileTag = '<div class="ic-attachment-download">';
		$fileTag.= '<a class="ic-attachment-link" href="' . $file . '" rel="noopener" target="_blank" title="' . Text::_('COM_ICAGENDA_EVENT_DOWNLOAD') . '">';
		$fileTag.= $attachment;
		$fileTag.= '</a>';
		$fileTag.= '</div>';

		return $fileTag;
	}
}
