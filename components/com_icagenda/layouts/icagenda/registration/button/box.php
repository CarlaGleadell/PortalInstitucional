<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.9.0 2024-02-25
 *
 * @package     iCagenda.Site
 * @subpackage  Layout
 * @link        https://www.icagenda.com
 *
 * @author      Cyril Rezé
 * @copyright   (c) 2012-2024 Cyril Rezé / iCagenda. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 *
 * @since       3.8.0
 *----------------------------------------------------------------------------
*/

defined('_JEXEC') or die;

use Joomla\CMS\Layout\LayoutHelper as JLayoutHelper;

extract($displayData);

/**
 * Layout variables
 * -----------------
 * @var   string   basePath         Base path to use when loading layout files (theme pack)
 * @var   string   cancelUrl        Cancel Registration Url.
 * @var   string   customLink       Custom registration link.
 * @var   array    extraDates       List of availables dates.
 * @var   string   registered       Number of registered users.
 * @var   string   registerTarget   Register button browser target.
 * @var   string   registerUrl      Registration Url.
 * @var   string   status           Status of registration button.
 * @var   string   textButton       Custom register text for button.
 * @var   string   userBooked       Logged-in user has registration(s) for this event.
*/
?>

<div class="ic-registration-box">

	<div class="ic-button-box ic-btn-register-<?php echo $status; ?>">
		<?php echo JLayoutHelper::render('icagenda.registration.button.register', $displayData, $basePath); ?>
	</div>

	<div class="ic-button-box ic-btn-register-cancel">
		<?php echo JLayoutHelper::render('icagenda.registration.button.cancel', $displayData, $basePath); ?>
	</div>

	<div class="ic-button-box ic-registered-info">
		<?php echo JLayoutHelper::render('icagenda.registration.button.info', $displayData, $basePath); ?>
	</div>

</div>
