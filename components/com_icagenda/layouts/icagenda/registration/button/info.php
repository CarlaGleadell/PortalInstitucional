<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.8.0 2021-11-05
 *
 * @package     iCagenda.Site
 * @subpackage  Layout.icagenda
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

<?php if ( ! $customLink) : ?>
	<?php if (version_compare(JVERSION, '4.0', 'lt')) : ?>
	<span class="iCicon iCicon-people ic-people"></span>
	<div class="ic-registered">
		<?php echo $registered; ?>
	</div>
	<?php else : ?>
	<div class="ic-registered-box">
		<button type="button" class="btn btn-light disabled"><span class="iCicon iCicon-people ic-people text-dark"></span>&#160;<strong><?php echo $registered; ?></strong></button>
	</div>
	<?php endif; ?>
<?php endif; ?>
