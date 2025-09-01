<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.7.18 2021-08-30
 *
 * @package     iCagenda.Admin
 * @subpackage  com_icagenda
 * @link        https://www.icagenda.com
 *
 * @author      Cyril Rezé
 * @copyright   (c) 2012-2024 Cyril Rezé / iCagenda. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 *
 * @since       1.0
 *----------------------------------------------------------------------------
*/

defined('_JEXEC') or die();


class iCagendaUpdateLogsColoriser
{
	public static function colorise($file, $onlyLast = false)
	{
		$ret = '';

		$lines = @file($file);

		if(empty($lines)) return $ret;

		array_shift($lines);

		foreach($lines as $line)
		{
			$line = trim($line);

			if(empty($line)) continue;

			$type = substr($line,0,1);

			switch($type)
			{
				case '=':
//					continue;
					break;

				case ':':
					$ret .= "\t".'<div style="font-size:8pt;">Legend'.$line."</div>\n";
					break;

				case '?':
					$ret .= "<div class=\"ic-message-info\">".trim(substr($line,2))."</div>\n";
					break;

				case '!':
					$ret .= "\t".'<li class="ic-bold ic-important"><div class="ic-box-16 ic-box-important">!</div>'
							. htmlentities(trim(substr($line,2))) . "</li>\n";
					break;

				case '1':
					$ret .= "\t".'<li class="ic-changelog-important-sub"><span></span> '.trim(substr($line,2))."</li>\n";
					break;

				case '+':
					$ret .= "\t".'<li class="ic-added"><div class="ic-box-16 ic-box-added">+</div>'
							. htmlentities(trim(substr($line,2))) . "</li>\n";
					break;

				case '-':
					$ret .= "\t".'<li class="ic-removed"><div class="ic-box-16 ic-box-removed">-</div>'
							. htmlentities(trim(substr($line,2))) . "</li>\n";
					break;

				case '~':
					$ret .= "\t".'<li class="ic-changed"><div class="ic-box-16 ic-box-changed">~</div>'
							. htmlentities(trim(substr($line,2))) . "</li>\n";
					break;

				case '#':
					$ret .= "\t".'<li class="ic-fixed"><div class="ic-box-16 ic-box-fixed">#</div>'
							. htmlentities(trim(substr($line,2))) . "</li>\n";
					break;

//				case 'H':
//					$ret .= "\t".'<li class="ic-fixed"><div class="ic-box-16 ic-box-fixed">#</div><div class="ic-box ic-box-removed">HIGH</div> '
//							. htmlentities(trim(substr($line,2))) . "</li>\n";
//					break;

				case '*':
					$ret .= "\t".'<h4 class="ic-changelog">' . htmlentities(trim(substr($line,2))) . "</h4>\n";
					break;

				case '$':
					$ret .= "</ul>";
					$ret .= "<h3 class=\"ic-changelog-pro\">&nbsp;&nbsp;" . substr($line,2) . " <SUP>[ PRO Testing ]</SUP></h3>\n";
					$ret .= "<ul class=\"ic-changelog\">\n";
					break;

				// End
				case ';':
					$ret .= "</ul>";
					break;

				default:

					if(!empty($ret))
					{
						$ret .= "</ul>";
						if($onlyLast) return $ret;
					}

					if(!$onlyLast) $ret .= "<h3 class=\"ic-changelog\">&nbsp;&nbsp;$line</h3>\n";

					$ret .= "<ul class=\"ic-changelog\">\n";
					break;
			}
		}

		return $ret;
	}
}
