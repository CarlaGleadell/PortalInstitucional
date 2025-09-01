<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.8.22 2023-11-06
 *
 * @package     iCagenda.Site
 * @subpackage  Themes.Packs
 * @link        https://www.icagenda.com
 *
 * @author      Cyril Rezé
 * @copyright   (c) 2012-2024 Cyril Rezé / iCagenda. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 *
 * @since       3.2.8
 *----------------------------------------------------------------------------
 * @themepack   ic_rounded
 * @template    events
 *----------------------------------------------------------------------------
*/

\defined('_JEXEC') or die;

use Joomla\CMS\Language\Text as JText;
?>

<!-- Event -->

<?php // List of Events Template ?>

	<?php // START Event ?>
	<?php if ($EVENT_NEXT) : ?>

		<?php // Link to Event ?>
		<a href="<?php echo $EVENT_URL; ?>" title="<?php echo $EVENT_TITLE; ?>" class="ic-text-decoration-none">
		<?php // START Date Box with Event Image as background ?>
		<?php // If no Event Image set ?>
		<?php if ( ! $EVENT_IMAGE) : ?>
		<div class="ic-box-date" style="background-color: <?php echo $CATEGORY_COLOR; ?>; border-color: <?php echo $CATEGORY_COLOR; ?>">
		<?php // In case of Event Image ?>
		<?php else : ?>
		<div class="ic-box-date" style="background-image:url(<?php echo $IMAGE_MEDIUM; ?>); border-color: <?php echo $CATEGORY_COLOR; ?>">
		<?php endif; ?>
			<div class="ic-date">
				<?php // Day ?>
				<div class="ic-day">
					<?php echo $EVENT_DAY; ?>
				</div>
				<?php // Month ?>
				<div class="ic-month">
					<?php echo $EVENT_MONTH; ?>
				</div>
				<?php // Year ?>
				<div class="ic-year">
					<?php echo $EVENT_YEAR; ?>
				</div>
				<?php // Time ?>
				<div class="ic-time">
					<?php echo $EVENT_TIME; ?>
				</div>
			</div>
		</div>
		</a>

		<?php // START Right Content ?>
		<div class="ic-content">

			<?php // Header (Title/Category) of the event ?>
			<div class="ic-event-title">

				<?php // Title of the event ?>
				<div class="ic-title-header ic-margin-0 ic-padding-0">
					<<?php echo $EVENT_TITLE_HTAG; ?>>
						<a href="<?php echo $EVENT_URL; ?>" class="ic-text-decoration-none">
							<?php echo $EVENT_TITLEBAR; ?>
						</a>
					</<?php echo $EVENT_TITLE_HTAG; ?>>
				</div>
				<div class="ic-title-cat ic-margin-0 ic-padding-0">
					<a href="<?php echo $LIST_FILTERED_BY_CATEGORY_URL; ?>" type="button" class="ic-title-cat-btn ic-button ic-padding-1 ic-radius-1 <?php echo $CATEGORY_CLASS; ?>" style="background:<?php echo $CATEGORY_COLOR; ?>">
						<?php echo $CATEGORY_TITLE; ?>
					</a>
				</div>

			</div>

			<?php // Feature icons ?>
			<?php if (!empty($FEATURES_ICONSIZE_LIST)) : ?>
			<div class="ic-features-container">
				<?php foreach ($FEATURES_ICONS as $icon) : ?>
				<div class="ic-feature-icon">
					<img class="iCtip" src="<?php echo $FEATURES_ICONROOT_LIST . $icon['icon']; ?>" alt="<?php echo $icon['icon_alt']; ?>" title="<?php echo $SHOW_ICON_TITLE == '1' ? $icon['icon_alt'] : ''; ?>">
				</div>
				<?php endforeach; ?>
			</div>
			<?php endif; ?>

			<?php // Next Date ('next' 'today' or 'last date' if no next date) ?>
			<?php if ($EVENT_DATE) : ?>
			<div class="nextdate ic-next-date ic-clearfix">
				<strong><?php echo $EVENT_DATE; ?></strong>
			</div>
			<?php endif; ?>

			<?php // Location (different display, depending on the fields filled) ?>
			<?php if ($EVENT_VENUE || $EVENT_CITY) : ?>
			<div class="place ic-place">

				<?php // Place name ?>
				<?php if ($EVENT_VENUE) : ?><?php echo $EVENT_VENUE; ?><?php endif; ?>

				<?php // If Place Name exists and city set (Google Maps). Displays Country if set. ?>
				<?php if ($EVENT_CITY && $EVENT_VENUE) : ?>
					<span> - </span>
					<?php echo $EVENT_CITY; ?><?php if ($EVENT_COUNTRY) : ?>, <?php echo $EVENT_COUNTRY; ?><?php endif; ?>
				<?php endif; ?>

				<?php // If Place Name doesn't exist and city set (Google Maps). Displays Country if set. ?>
				<?php if ($EVENT_CITY && !$EVENT_VENUE) : ?>
					<?php echo $EVENT_CITY; ?><?php if ($EVENT_COUNTRY) : ?>, <?php echo $EVENT_COUNTRY; ?><?php endif; ?>
				<?php endif; ?>

			</div>
			<?php endif; ?>

			<?php // Short Description ?>
			<?php if ($EVENT_DESC) : ?>
			<div class="descshort ic-descshort">
				<?php echo $EVENT_INTRO_TEXT; ?><?php echo $READ_MORE; ?>
			</div>
			<?php endif; ?>

			<?php // Addons Plugins (JComments, ...) - onListAddEventInfo ?>
			<?php if ($IC_LIST_ADD_EVENT_INFO) : ?>
				<?php echo $IC_LIST_ADD_EVENT_INFO; ?>
			<?php endif; ?>

			<?php // + infos Text ?>
			<div class="moreinfos ic-more-info">
			 	<a href="<?php echo $EVENT_URL; ?>" title="<?php echo $EVENT_TITLE; ?>">
			 		<?php echo JText::_('COM_ICAGENDA_EVENTS_MORE_INFO'); ?>
			 	</a>
			</div>

		</div><?php // END Right Content ?>

	<?php endif; ?>
<?php // END Event ?>

