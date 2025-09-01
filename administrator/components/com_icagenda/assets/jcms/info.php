<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.9.0 2023-02-23
 *
 * @package     iCagenda.Admin
 * @link        https://www.icagenda.com
 *
 * @author      Adapted from Nicholas K. Dionysopoulos - www.akeebabackup.com
 * @copyright   (c) 2012-2024 Cyril Rezé / iCagenda. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 *
 * @since       3.5.6
 *----------------------------------------------------------------------------
*/

\defined('_JEXEC') or die;

class iCagendaSystemInfo
{
	/** @var string Unique identifier for the site, created from server variables */
	private $siteId;
	/** @var array Associative array of data being sent */
	private $data = [];
	/** @var string Remote url to upload the stats */
	private $remoteUrl = 'https://stats.joomlic.com/index.php';

	public function setSiteId($siteId)
	{
		$this->siteId = $siteId;
	}

	/**
	 * Sets the value of a collected variable. Use NULL as value to unset it
	 *
	 * @param   string  $key        Variable name
	 * @param   string  $value      Variable value
	 */
	public function setValue($key, $value)
	{
		if (is_null($value) && isset($this->data[$key])) {
			unset($this->data[$key]);
		} else {
			$this->data[$key] = $value;
		}
	}

	/**
	 * Uploads collected data to the remote server
	 *
	 * @param   bool         $useIframe  Should I create an iframe to upload data or should I use cURL/fopen?
	 *
	 * @return  string|bool  The HTML code if an iframe is requested or a boolean if we're using cURL/fopen
	 */
	public function sendInfo($useIframe = false)
	{
		// No site ID? Well, simply do nothing
		if ( ! $this->siteId) {
			return '';
		}

		// First of all let's add the siteId
		$this->setValue('sid', $this->siteId);

		// Then let's create the url
		$url = array();

		foreach ($this->data as $param => $value) {
			$url[] .= $param . '=' . $value;
		}

		$url = $this->remoteUrl . '?' . implode('&', $url);

		// Should I create an iframe?
		if ($useIframe) {
			return '<!-- Anonymous usage statistics collection for iCagenda software --><iframe style="display: none" src="' . $url . '"></iframe>';
		} else {
			// Do we have cURL installed?
			if (function_exists('curl_init')) {
				$ch = curl_init($url);

				curl_setopt($ch, CURLOPT_TIMEOUT, 5);

				return curl_exec($ch);
			} else {
				// Nope, let's try with fopen and cross our fingers
				return @fopen($url, 'r');
			}
		}
	}
}
