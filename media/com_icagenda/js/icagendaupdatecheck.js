/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.7.18 2021-07-08
 *
 * @package     iCagenda.Media
 * @subpackage  js
 * @link        https://www.icagenda.com
 *
 * @author      Cyril Rezé
 * @copyright   (c) 2012-2024 Cyril Rezé / iCagenda. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 *
 * @since       3.7.18
 *----------------------------------------------------------------------------
*/

var icagendaupdatecheck_ajax_structure = {};

jQuery(document).ready(function() {
	icagendaupdatecheck_ajax_structure = {
		success: function(data, textStatus, jqXHR) {
			var plglink = jQuery('#icagendaupdate').find('span.j-links-link');
			var iclink = jQuery('#icagendaupdate').find('span.ic-liveupdate-link');

			try {
				var updateInfoList = jQuery.parseJSON(data);
			} catch (e) {
				// An error occurred
				plglink.html(icagendaupdate_text.ERROR);
				iclink.html(icagendaupdate_text.ERROR);
			}

			if (updateInfoList instanceof Array) {
				if (updateInfoList.length < 1) {
					// No updates
					plglink.replaceWith(icagendaupdate_text.UPTODATE);
					iclink.replaceWith(icagendaupdate_text.UPTODATE);
				} else {
					var updateInfo = updateInfoList.shift();
					var updateString = icagendaupdate_text.UPDATEFOUND.replace("%s", '\u200E' + updateInfo.version + "");
					plglink.html(updateString);
					iclink.html(updateString);
					jQuery('#iCagendaLiveupdate').find('.iCicon-iclogo').addClass('icon-upload').removeClass('iCicon-iclogo');
					jQuery('#iCagendaLiveupdate').addClass('update-found');
					if (icagendaupdate_text.UPDATEFOUND_MESSAGE != '')
					{
						var updateString = icagendaupdate_text.UPDATEFOUND_MESSAGE.replace("%s", '\u200E' + updateInfo.version + "");
						jQuery('#system-message-container').prepend(
							'<div class="alert alert-error alert-joomlaupdate">'
							+ updateString
							+ ' <button class="btn btn-primary" onclick="document.location=\'' + icagendaupdate_url + '\'">'
							+ icagendaupdate_text.UPDATEFOUND_BUTTON + '</button>'
							+ '</div>'
						);
					}
				}
			} else {
				// An error occurred
				plglink.html(icagendaupdate_text.ERROR);
				iclink.html(icagendaupdate_text.ERROR);
			}
		},
		error: function(jqXHR, textStatus, errorThrown) {
			// An error occurred
			jQuery('#icagendaupdate').find('span.j-links-link').html(icagendaupdate_text.ERROR);
			jQuery('#icagendaupdate').find('span.ic-liveupdate-link').html(icagendaupdate_text.ERROR);
		},
		url: icagendaupdate_ajax_url + '&eid=' + icagendaupdate_eid + '&cache_timeout=3600'
	};
	setTimeout("ajax_object = new jQuery.ajax(icagendaupdatecheck_ajax_structure);", 2000);
});
