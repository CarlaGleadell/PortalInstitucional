<?php
/**
 *----------------------------------------------------------------------------
 * iC Library   Library by Jooml!C, for Joomla!
 *----------------------------------------------------------------------------
 * @version     2.0.0 2021-09-27
 *
 * @package     iC Library
 * @subpackage  Url
 * @link        https://www.joomlic.com
 *
 * @author      Cyril Rezé
 * @copyright   (c) 2013-2024 Cyril Rezé / iCagenda. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 *
 * @since       1.0.0
 *----------------------------------------------------------------------------
*/

namespace iClib\Url;

\defined('_JEXEC') or die;

/**
 * class iCUrl
 */
class Url
{
	/**
	 * Function to check if an url exists
	 *
	 * @param   $url  Url to be tested
	 *
	 * @return  boolean
	 */
	public static function url_exists($url)
	{
		if (@file_get_contents($url))
		{
			return true;
		}
		else
		{
			return false;
		}

		// Deprecated
//		$a_url = parse_url($url);
//		if (!isset($a_url['port'])) $a_url['port'] = 80;
//		$errno = 0;
//		$errstr = '';
//		$timeout = 30;
//		if(isset($a_url['host']) && $a_url['host']!=gethostbyname($a_url['host']))
//		{
//			$fid = fsockopen($a_url['host'], $a_url['port'], $errno, $errstr, $timeout);
//			if (!$fid) return false;
//			$page = isset($a_url['path']) ?$a_url['path']:'';
//			$page .= isset($a_url['query'])?'?'.$a_url['query']:'';
//			fputs($fid, 'HEAD '.$page.' HTTP/1.0'."\r\n".'Host: '.$a_url['host']."\r\n\r\n");
//			$head = fread($fid, 4096);
//			fclose($fid);
//			return preg_match('#^HTTP/.*\s+[200|302]+\s#i', $head);
//		}
//		else
//		{
//			return false;
//		}
	}

	/**
	 * Function to parse an url and apply control of component set
	 *
	 * @param   $url        Url to be parsed
	 * @param   $component  Component to be checked (default 'scheme')
	 *
	 * @return  validate url
	 */
	public static function urlParsed($url, $component = 'scheme')
	{
		$parsed = parse_url($url);

		// Add http:// if scheme missing
		if ($component == 'scheme' && empty($parsed['scheme']))
		{
			$link = 'http://' . ltrim($url, '/');
		}
		else
		{
			$link = ltrim($url, '/');
		}

		return $link;
	}
}
