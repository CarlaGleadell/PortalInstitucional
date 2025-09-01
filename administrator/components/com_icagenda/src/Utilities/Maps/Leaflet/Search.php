<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.8.0 2021-12-25
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

namespace iCutilities\Maps\Leaflet;

\defined('_JEXEC') or die;

use iCutilities\Maps\Leaflet\Leaflet as icagendaMapsLeaflet;
use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;

/**
 * class icagendaMapsLeafletSearch
 */
class Search
{
	/**
	 * Load Leaflet Search scripts.
	 *
	 * @param   string  $mapid    Element id used as wrapper for map
	 * @param   string  $fieldid  Element id used as address field for search
	 * @param   array   $options  Array of options. Example: array('tab' => 'a[href=\"#googlemap\"]')
	 */
	public static function addJS($mapid, $fieldid = 'jform_address', $options = array())
	{
		$tab     = isset($options['tab']) ? $options['tab'] : '';
		$library = isset($options['library']) ? $options['library'] : true;

		if ($library)
		{
			icagendaMapsLeaflet::addLibrary();
		}

		HTMLHelper::_('styleSheet', 'media/com_icagenda/leaflet/plugins/search/leaflet-search.css');
		HTMLHelper::_('script', 'media/com_icagenda/leaflet/plugins/search/leaflet-search.js');

		Factory::getDocument()->addScriptDeclaration("
			document.addEventListener('DOMContentLoaded', function() {
				document.getElementById('" . $fieldid . "').setAttribute('type', 'hidden');

				var address = document.getElementById('" . $fieldid . "').value;
				var tab = '" . $tab . "';

				if (tab !== '') {
					var icmapTab = document.querySelector(tab);
				}

				loadMap();

				function loadMap() {
					var lat = document.getElementById('lat').value,
						lng = document.getElementById('lng').value,
						zoom = '16';

					if (lat == '' && lng == '') {
						var lat = '25',
							lng = '0',
							zoom = '1';
					}
				
					var map = new L.Map('" . $mapid . "', {
								zoom: zoom,
								center: new L.latLng([lat,lng])
							});

					if (tab !== '') {
//						icmapTab.addEventListener('click', function() {
						document.addEventListener('click', function() {
							//	setTimeout(() => {
								map.invalidateSize();
							//	}, 100);
						});
					}


					map.addLayer(new L.TileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
					    	attribution: '&copy; <a href=\"https://www.openstreetmap.org/copyright\">OpenStreetMap</a> contributors'
					    }
					));	//base layer

//					function formatJSON(rawjson) {	// callback that remap fields name
//						var json = {},
//						key, loc, disp, country = [];

//						for(var i in rawjson)
//						{
//							displayName = rawjson[i].display_name;	

//							if (displayName) {
//								disp = displayName.split(',');
//								key = disp[0] +','+ disp[1] +','+ disp[2];
			
//								loc = L.latLng( rawjson[i].lat, rawjson[i].lon );
			
//								json[ key ]= loc;	//key,value format
//							}
//						}
		
//						return json;
//					}
	
					var searchOpts = {
						container: 'findbox',
						url: 'https://nominatim.openstreetmap.org/search?format=json&q={s}&addressdetails=1',
						jsonpParam: 'json_callback',
						propertyName: 'display_name',
						propertyLoc: ['lat','lon'],
//						marker: L.circleMarker([0,0],{radius:30}),
						marker: L.marker([0,0]),
						zoom: 16,
						minLength: 2,
						autoResize: false,
						collapsed: false
					};

					var searchControl = new L.Control.Search(searchOpts);

					// Override Leaflet-search library _handleAutoresize
					searchControl._handleAutoresize = function() {	//autoresize this._input
						//TODO refact _handleAutoresize now is not accurate
						if (this._input.style.maxWidth != this._map._container.offsetWidth) //If maxWidth isn't the same as when first set, reset to current Map width
							this._input.style.maxWidth = '90%';

						if(this.options.autoResize && (this._container.offsetWidth + 45 < this._map._container.offsetWidth))
							this._input.size = this._input.value.length<this._inputMinSize ? this._inputMinSize : this._input.value.length;
					}

					// Override Leaflet-search library cancel
					searchControl.cancel = function() {
						this._input.value = '';
						this._handleKeypress({ keyCode: 8 });//simulate backspace keypress
						this._input.size = '70';
						this._input.focus();
						this._cancel.style.display = 'none';
						this._hideTooltip();
						this.fire('search:cancel');
						return this;
					}

					// Override Leaflet-search library _createInput
					searchControl._createInput = function (text, className) {
						var label = L.DomUtil.create('label', className, this._container);
						var input = L.DomUtil.create('input', className + ' form-control', this._container);
						input.type = 'text';
						input.size = '70';
						input.value = '';
						input.autocomplete = 'off';
						input.autocorrect = 'off';
						input.autocapitalize = 'off';
						input.placeholder = text;
						input.style.display = 'none';
						input.role = 'search';
						input.id = 'search-" . $mapid . "';
		
						label.htmlFor = input.id;
						label.style.display = 'none';
						label.value = text;

						L.DomEvent
							.disableClickPropagation(input)
							.on(input, 'keyup', this._handleKeypress, this)
							.on(input, 'blur', this.collapseDelayed, this)
							.on(input, 'focus', this.collapseDelayedStop, this);
		
						return input;
					}

					// Override Leaflet-search library _createCancel
					searchControl._createCancel =  function (title, className) {
						var cancel = L.DomUtil.create('a', className, this._container);
						cancel.href = '#';
						cancel.title = title;
						if (address) { 
							cancel.style.display = 'block';
						} else {
							cancel.style.display = 'none';
						}
						cancel.innerHTML = '<span>&otimes;</span>';

						L.DomEvent
							.on(cancel, 'click', L.DomEvent.stop, this)
							.on(cancel, 'click', this.cancel, this);

						return cancel;
					}

					searchControl.on('search:locationfound', function(e) {
						// Set form values
						document.getElementById('" . $fieldid . "').value = e.text;
						document.getElementById('lat').value = e.latlng['lat'];
						document.getElementById('lng').value = e.latlng['lng'];

						// JQuery
//						$.getJSON('https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat='+e.latlng['lat']+'&lon='+e.latlng['lng'], function(data) {
							//data is the JSON string
//						});

						// Vanilla
						var request = new XMLHttpRequest();
						request.open('GET', 'https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat='+e.latlng['lat']+'&lon='+e.latlng['lng'], true);

						request.onload = function() {
							if (request.status >= 200 && request.status < 400) {
								// Success!
								var data = JSON.parse(request.responseText);
								if (data.address.city !== undefined) {
									document.getElementById('locality').value = data.address.city;
								} else if (data.address.town !== undefined) {
									document.getElementById('locality').value = data.address.town;
								} else if (data.address.village !== undefined) {
									document.getElementById('locality').value = data.address.village;
								}
								document.getElementById('country').value = data.address.country;
//								console.log('getDetails', data.address);
							} else {
								// We reached our target server, but it returned an error
							}
						};

						request.onerror = function() {
							// There was a connection error of some sort
						};

						request.send();
					});

					searchControl.on('search:cancel', function(e) {
						// Set form values
						document.getElementById('" . $fieldid . "').value = '';
						document.getElementById('locality').value = '';
						document.getElementById('country').value = '';
						document.getElementById('lat').value = '';
						document.getElementById('lng').value = '';
					});

					map.addControl(searchControl);

					if (document.getElementById('lat').value && document.getElementById('lng').value) {
						document.getElementById('search-" . $mapid . "').value = address;
						L.marker([lat,lng]).addTo(map);
					}
				};
			});
		");
	}
}
