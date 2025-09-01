<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.8.13 2023-02-10
 *
 * @package     iCagenda.Site
 * @subpackage  Themes.Packs
 * @link        https://www.icagenda.com
 *
 * @author      Cyril Rezé
 * @copyright   (c) 2012-2024 Cyril Rezé / iCagenda. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 *
 * @since       2.0
 *----------------------------------------------------------------------------
 * @themepack   ic_rounded
 * @template    registration
 *----------------------------------------------------------------------------
*/

defined('_JEXEC') or die;
?>

<!-- Event registration -->

<?php // Header of Registration page ?>
<?php // Show event ?>
<div class="ic-reg-event ic-clearfix">
	<div class="ic-reg-box">
		<?php if ($EVENT_NEXT) : ?>
		<div class="ic-reg-icon ic-float-left">
		</div>
		<?php endif; ?>
		<div class="ic-reg-content">

			<?php // Category ?>
			<div class="ic-reg-cat">
				<?php echo $CATEGORY_TITLE; ?>
			</div>

			<?php // Event Title with link to event ?>
			<div class="ic-reg-event-title">
				<a href="<?php echo $EVENT_URL; ?>" title="<?php echo $EVENT_TITLE; ?>"><?php echo $EVENT_TITLE; ?></a>
			</div>
		</div>
	</div>
</div>
<?php // END Header ?>
