/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.8.0 2021-07-24
 *
 * @package     iCagenda.Media
 * @subpackage  js
 * @link        https://www.icagenda.com
 *
 * @author      Cyril Rezé
 * @copyright   (c) 2012-2024 Cyril Rezé / iCagenda. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 *
 * @since       3.4
 *----------------------------------------------------------------------------
*/

/**
 * Function Text Counter
 */
function iCtextCounter(field, livecounter, maxlimit) {
	if (field.value.length > maxlimit) {
		field.value = field.value.substring(0, maxlimit);
		livecounter.value = 0;
		livecounter.innerHTML = 0;
		//jQuery(field).addClass("ic-counter-limit");
		// document.getElementsByName(field).classList.add("ic-counter-limit"); // min IE10
		document.getElementsByName(field).className += " ic-counter-limit";
		return false;
	} else {
		livecounter.value = maxlimit - field.value.length;
		livecounter.innerHTML = maxlimit - field.value.length;
		document.getElementsByName(field).className -= " ic-counter-limit";
	}
	if (field.value.length >= maxlimit) {
		livecounter.setAttribute("class", "ic-livecounter-0");
	} else if (field.value.length > (maxlimit-10)) {
		livecounter.setAttribute("class", "ic-livecounter-10");
	} else if (field.value.length > (maxlimit-20)) {
		livecounter.setAttribute("class", "ic-livecounter-20");
	} else {
		livecounter.removeAttribute("class");
	}
}

function iCliveCounter() {
	var field = this;
	var livecounter = document.getElementById(this.id + '_livecounter');
	var maxlimit = this.getAttribute('maxlength');
	return iCtextCounter(field, livecounter, maxlimit);
}

/**
 * fieldname, warningname, remainingname, maxchars // DEV.
 */
function CheckFieldLength(fn, wn, rn, maxlimit) {
	var length = fn.value.length;
	if (length > maxlimit) {
		fn.value = fn.value.substring(0,maxlimit);
		length = maxlimit;
		return false;
	}
	document.getElementById(wn).innerHTML = length;
	document.getElementById(rn).innerHTML = maxlimit - length;
}

/**
 * Function in array
 */
function inArray(needle, haystack) {
	var length = haystack.length;
	for (var i = 0; i < length; i++) {
		if (haystack[i] == needle) return true;
	}
	return false;
}

/**
 * Function to read uploaded image file information and render preview
 * Append to div with id ic-upload-preview
 */
function readImage(input, file, accept, mimetype, limitSize) {
	var reader = new FileReader();
	var image  = new Image();
	var uploadPreview = document.getElementById('ic-upload-preview');
	reader.readAsDataURL(file);
	reader.onload = function(_file) {
		image.src    = _file.target.result;
		image.onload = function() {
			var w = file.width,
				h = file.height,
				t = file.type,
				n = file.name,
				size = ~~(file.size/1024),
				s = ~~(file.size/1024) + Joomla.JText._('IC_LIBRARY_KILO_BYTES');
			if ( inArray(t, mimetype) ) {
				if (size < limitSize) {
					uploadPreview.innerHTML = '<div class="ic-media-preview"><img src="'+ image.src +'" class="media-preview" style="max-width:'+ input.getAttribute("preview_width") +'px; max-height:'+ input.getAttribute("preview_height") +'px;"/><br />'+t+' - '+s+'</div><br />';
				} else {
					var uploadInvalidSize_string = Joomla.JText._('IC_LIBRARY_UPLOAD_INVALID_SIZE').replace('%1$s', '<strong>'+ n +'</strong>').replace('%2$s', size).replace('%3$s', limitSize);
					uploadPreview.innerHTML = '<div class="alert alert-error alert-danger">'+uploadInvalidSize_string+'</div>';
					input.value = '';
				}
			} else {
				uploadPreview.innerHTML = '<div class="alert alert-error alert-danger">' + Joomla.JText._('IC_LIBRARY_UPLOAD_INVALID_FILE_TYPE').replace('%1$s', '<strong>'+ n +'</strong>').replace('%2$s', accept) + '</div>';
				input.value = '';
			}
		};
		image.onerror= function() {
			alert(Joomla.JText._('IC_LIBRARY_UPLOAD_INVALID_FILE_TYPE_ALERT') + ' ' + file.type);
		};
	};
}

/**
 * Function to check image upload
 */
function checkImage(input) {
	if (input.disabled) return alert(Joomla.JText._('IC_LIBRARY_UPLOAD_NOT_SUPPORTED'));
	var F = input.files;
	if (F && F[0]) for (var i=0; i<F.length; i++) readImage (input, F[i], "jpg, jpeg, png, gif", ['image/jpg','image/jpeg','image/png','image/gif'], limitSize);
}
