<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.8.1 2022-02-26
 *
 * @package     iCagenda.Admin
 * @subpackage  src.Utilities.Maps
 * @link        https://www.icagenda.com
 *
 * @author      Cyril Rezé
 * @copyright   (c) 2012-2024 Cyril Rezé / iCagenda. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 *
 * @since       3.8
 *----------------------------------------------------------------------------
*/

namespace iCutilities\Maps\Google;

\defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;

/**
 * class icagendaMapsGoogleSearch
 */
class Search
{
	/**
	 * Load Google Maps Javascript API script.
	 *
	 * @param   string  $mapid    Element id used as wrapper for map
	 * @param   string  $fieldid  Element id used as address field for search
	 * @param   array   $options  Array of options. Example: array('apiKey' => 'xxx000', 'apiClient' => 'gme-xxx')
	 */
	public static function addJS($mapid, $fieldid = 'jform_address', $options = array())
	{
		$options['apiKey']    = isset($options['apiKey']) ? $options['apiKey'] : '';
		$options['apiClient'] = isset($options['apiClient']) ? $options['apiClient'] : '';

		HTMLHelper::_('jquery.framework');

		// Load jQuery UI
		HTMLHelper::_('stylesheet', 'com_icagenda/jquery-ui.min.css', array('relative' => true, 'version' => 'auto'), array('async' => 'async'));
		HTMLHelper::_('script', 'com_icagenda/jquery-ui.min.js', array('relative' => true, 'version' => 'auto'));

		// Google Maps api V3
		$document      = Factory::getDocument();
		$scripts       = array_keys($document->_scripts);
		$gmapApiLoaded = false;

		for ($i = 0; $i < count($scripts); $i++)
		{
    		if ( stripos($scripts[$i], 'maps.googleapis.com') !== false
    			&& stripos($scripts[$i], 'maps.gstatic.com') !== false )
			{
				$gmapApiLoaded = true;
			}
		}

		if ( ! $gmapApiLoaded)
		{
			$curlang   = $document->language;
			$lang      = substr($curlang, 0, 2);
			$key       = $options['apiKey'];
			$client_id = $options['apiClient'];
			$client    = (substr($client_id, 0, 4) === 'gme-') ? $client_id : 'gme-' . $client_id;

			// Google Maps API variables
			$apiLang   = '?language=' . trim($lang);
			$apiLib    = '&librairies=places';
			$apiKey    = ($key && ! $client_id) ? '&key=' . trim($key) : '';
			$apiClient = $client_id ? '&client=' . trim($client) : '';

			$document->addScript('https://maps.googleapis.com/maps/api/js' . $apiLang . $apiLib . $apiKey . $apiClient);
		}

		HTMLHelper::_('script', 'com_icagenda/icmap.js', array('relative' => true, 'version' => 'auto'));

		// Set default values for Google Maps
		$zoom      = '16';
		$mapTypeId = 'ROADMAP'; // HYBRID, ROADMAP, SATELLITE, TERRAIN

		$regionBias = array(
						'en-gb' => 'uk',
						'fr-fr' => 'fr',
					);

		$region = (isset($regionBias[$curlang])) ? $regionBias[$curlang] : null;

		$document->addScriptDeclaration('
			jQuery(function($) {

				var addresspicker = $("#addresspicker").addresspicker();
				var addresspickerMap = $("#' . $fieldid . '").addresspicker({
					regionBias: "' . $region . '",
					mapOptions: {
						zoom: ' . $zoom . ',
						center: new google.maps.LatLng(0,0),
						scrollwheel: false,
						mapTypeId: google.maps.MapTypeId.' . $mapTypeId . ',
						streetViewControl: false
					},
					elements: {
						map: "#' . $mapid . '",
						lat: "#lat",
						lng: "#lng",
						street_number: "#street_number",
						route: "#route",
						locality: "#locality",
						administrative_area_level_2: "#administrative_area_level_2",
						administrative_area_level_1: "#administrative_area_level_1",
						country: "#country",
						postal_code: "#postal_code",
						type: "#type",
					}
				});

				var gmarker = addresspickerMap.addresspicker("marker");
				gmarker.setVisible(true);
				addresspickerMap.addresspicker("updatePosition");

				$("#reverseGeocode").change(function(){
					$("#' . $fieldid . '").addresspicker("option", "reverseGeocode", ($(this).val() === "true"));
				});
  			});
		');
	}

	/**
	 * Load Google Maps Embed script.
	 *
	 * @param   string  $mapid    Element id used as wrapper for map
	 * @param   string  $fieldid  Element id used as address field for search
	 * @param   array   $options  Array of options. Example: array('apiKey' => 'xxx000')
	 */
	public static function addEmbedJS($mapid, $fieldid = 'jform_address', $options = array())
	{
		$apiKey = isset($options['apiKey']) ? $options['apiKey'] : '';

		Factory::getDocument()->addScriptDeclaration('
			document.addEventListener("DOMContentLoaded", function(event) {
				document.getElementById("' . $fieldid . '").setAttribute("class", "form-control");
				var address = document.getElementById("' . $fieldid . '").value;
				if (address) {
					displayGoogleMapIframe(address);
				}
			});
			function checkGoogleMap(address) {
				if (address){ 
					displayGoogleMapIframe(address);
				} else {
					html = "<div class=\"alert alert-warning\">' . addslashes(Text::_('COM_ICAGENDA_MAPS_FILL_IN_ADDRESS_ALERT')) . '</div>";
					document.getElementById("' . $mapid . '").innerHTML = html;
				}
			}
			function displayGoogleMapIframe(address) {
				var embedKey = "' . $apiKey . '";
				html = "<iframe width=\"100%\" height=\"100%\" frameborder=\"0\" style=\"border:0;\" class=\"ic-map-iframe\" src=\"https://www.google.com/maps/embed/v1/place?key=" + embedKey + "&q=" + encodeURI(address) + "\" allowfullscreen><\/iframe>";
				document.getElementById("' . $mapid . '").innerHTML = html;
			}
		');
	}
}
