/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.7.0 2018-05-15
 *
 * @package     iCagenda.Media
 * @subpackage  js
 * @link        https://www.icagenda.com
 *
 * @author      Cyril Rezé
 * @copyright   (c) 2012-2024 Cyril Rezé / iCagenda. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 *
 * @since       1.0
 *----------------------------------------------------------------------------
*/

var lat,
	lng,
	id;

function icMapInitialize(lat, lng, id){
	function initialize(){
		var latlng = new google.maps.LatLng(lat, lng);
		var mapOptions = {
			zoom: 16,
			center: latlng,
			mapTypeId: google.maps.MapTypeId.ROADMAP
		};

		var mapid = new google.maps.Map(document.getElementById('map_canvas'+id), mapOptions);

		var newMarker = new google.maps.Marker({
			map: mapid,
			draggable: false,
			position: latlng
		});
	}

	google.maps.event.addDomListener(window, 'load', initialize);	
}
