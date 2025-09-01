<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.8.6 2022-05-12
 *
 * @package     iCagenda.Admin
 * @subpackage  Utilities.maps
 * @link        https://www.icagenda.com
 *
 * @author      Cyril Rezé
 * @copyright   (c) 2012-2024 Cyril Rezé / iCagenda. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 *
 * @since       3.8
 *----------------------------------------------------------------------------
*/

defined('_JEXEC') or die;

/**
 * class icagendaMaps
 */
class icagendaMaps
{
	/**
	 * Function to check if Map should be displayed.
	 *
	 * @since   3.8.0
	 */
	static public function display($item)
	{
		$iCparams = JComponentHelper::getParams('com_icagenda');

		// Hide/Show Option
		$GoogleMaps = $iCparams->get('GoogleMaps', 1);

		// Access Levels Option
		$accessGoogleMaps = $iCparams->get('accessGoogleMaps', 1);

		$markerLat = self::lat($item);
		$markerLng = self::lng($item);

		if ($GoogleMaps == 1
			&& icagendaEvents::accessLevels($accessGoogleMaps)
			&& (($markerLat && $markerLng) || ($item->address && $iCparams->get('maps_service', 1) == '2'))
			)
		{
			return true;
		}

		return false;
	}

	/**
	 * Function to display Map (frontend)
	 *
	 * @since   3.8.0
	 */
	static public function map($item)
	{
		$document = JFactory::getDocument();
		$params   = JFactory::getApplication()->getParams();
		$mapID    = $item->id;

		$iCparams     = JComponentHelper::getParams('com_icagenda');
		$maps_service = $iCparams->get('maps_service', 1);

		$iCmap = '<!-- Event Map -->';

		if ($maps_service == '1')
		{
			$markerLat = self::lat($item);
			$markerLng = self::lng($item);

			JHtml::_('styleSheet', 'media/com_icagenda/leaflet/leaflet.css');
			JHtml::_('script', 'media/com_icagenda/leaflet/leaflet.js');

			$document->addScriptDeclaration("
				document.addEventListener('DOMContentLoaded', function() {
					var mapid = document.getElementById('ic-map-" . (int) $mapID . "');

					if (mapid !== null) {
						var lat = '" . $markerLat . "',
							lng = '" . $markerLng . "',
							map = L.map('ic-map-" . (int) $mapID . "').setView([lat, lng], 16);

						L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
							attribution: '&copy; <a href=\"https://www.openstreetmap.org/copyright\">OpenStreetMap</a> contributors'
						}).addTo(map);

						L.marker([lat, lng]).addTo(map)
//							.bindPopup('<strong>" . $item->title . "</strong><br />" . $item->address . "')
//							.openPopup();
					}
				});
			");

			$iCmap.= '<div class="ic-map-wrapper" id="ic-map-' . (int) $mapID . '"></div>';
		}
		elseif ($maps_service == '2')
		{
			$embedKey = $iCparams->get('googlemaps_embed_key', '');

			$iCmap.= '<div class="ic-map-wrapper" id="embed_map-' . (int) $mapID . '">';
			$iCmap.= '<iframe
				width="100%"
				height="100%"
				frameborder="0" style="border:0;" class="ic-map-iframe"
				src="https://www.google.com/maps/embed/v1/place?key=' . trim($embedKey)
					. '&q=' . urlencode(strip_tags($item->address)) . '" allowfullscreen>
				</iframe>';
			$iCmap.= '</div>';
		}
		elseif ($maps_service == '3')
		{
			$markerLat = self::lat($item);
			$markerLng = self::lng($item);

			if ($markerLat && $markerLng)
			{
				$document->addScriptDeclaration('
					document.addEventListener("DOMContentLoaded", function() {
						icMapInitialize(' . $markerLat . ', ' . $markerLng . ', ' . (int) $mapID . ');
					});
				');
			}

			$iCmap.= '<div class="ic-map-wrapper" id="map_canvas' . (int) $mapID . '">';
			$iCmap.= '</div>';
		}

		return $maps_service ? $iCmap : JText::_('COM_ICAGENDA_MAPS_SERVICE_NOT_AVAILABLE');
	}

	/**
	 * Function to return Latitude
	 *
	 * @since   3.6.0
	 */
	static public function lat($item)
	{
		// Convert old coordinate value to latitude
		if ($item->coordinate != null
			&& $item->lat == '0.0000000000000000')
		{
			$ex = explode(', ', $item->coordinate);

			$latitude = $ex[0];
		}
		else
		{
			$latitude = ($item->lat != '0.0000000000000000') ? $item->lat : false;
		}

		return $latitude;
	}

	/**
	 * Function to return Longitude
	 *
	 * @since   3.6.0
	 */
	static public function lng($item)
	{
		if ($item->coordinate != null
			&& $item->lng == '0.0000000000000000')
		{
			$ex = explode(', ', $item->coordinate);

			$longitude = $ex[1];
		}
		else
		{
			$longitude = ($item->lng != '0.0000000000000000') ? $item->lng : false;
		}

		return $longitude;
	}

	/**
	 * Load Google Maps Scripts.
	 *
	 * @param   type   show (display map) or edit (create and set map values)
	 *
	 * @since   3.6.0
	 */
	public static function loadGMapScripts($type = 'show')
	{
		$iCparams     = JComponentHelper::getParams('com_icagenda');
		$maps_service = $iCparams->get('maps_service', 1);

		if ($maps_service == '3')
		{
			JHtml::_('jquery.framework');

			// Load jQuery UI
			JHtml::_('stylesheet', 'com_icagenda/jquery-ui.min.css', array('relative' => true, 'version' => 'auto'), array('async' => 'async'));
			JHtml::_('script', 'com_icagenda/jquery-ui.min.js', array('relative' => true, 'version' => 'auto'));

			// Google Maps api V3
			$document      = JFactory::getDocument();
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
				$key       = $iCparams->get('googlemaps_browser_key', '');
				$client_id = $iCparams->get('googlemaps_client_id', '');
				$client    = (substr($client_id, 0, 4) === 'gme-') ? $client_id : 'gme-' . $client_id;

				// Google Maps API variables
				$apiLang   = '?language=' . trim($lang);
				$apiLib    = '&librairies=places';
				$apiKey    = ($key && ! $client_id) ? '&key=' . trim($key) : '';
				$apiClient = $client_id ? '&client=' . trim($client) : '';

				$document->addScript('https://maps.googleapis.com/maps/api/js' . $apiLang . $apiLib . $apiKey . $apiClient);
			}

			if ($type == 'show')
			{
				JHtml::script('com_icagenda/icmap-front.js', false, true);
			}
			else
			{
				JHtml::script('com_icagenda/icmap.js', false, true);
			}
		}
	}
}
