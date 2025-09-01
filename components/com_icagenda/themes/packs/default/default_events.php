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
 * @themepack   default
 * @template    events
 *----------------------------------------------------------------------------
*/

defined('_JEXEC') or die;

use Joomla\CMS\Language\Text as JText;
?>

<!-- Event -->

<?php // List of Events Template ?>

	<?php // Show event ?>
	<?php if ($EVENT_NEXT) : ?>

		<?php // Display Date ?>
			<div class="ic-box">
				<div class="ic-box-date ic-float-left ic-align-center <?php echo $CATEGORY_FONTCOLOR; ?>" style="background:<?php echo $CATEGORY_COLOR; ?>;">
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

				<?php // Right-Box with Infos ?>
				<div class="ic-content">
					<div>

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

						<?php // Category ?>
						<div class="ic-cat">
							<a href="<?php echo $LIST_FILTERED_BY_CATEGORY_URL; ?>">
								<?php echo $CATEGORY_TITLE; ?>
							</a>
						</div>

						<?php // Event Title with link to event + Manager Icons (included in titlebar) ?>
						<<?php echo $EVENT_TITLE_HTAG; ?>>
							<a href="<?php echo $EVENT_URL; ?>">
								<?php echo $EVENT_TITLEBAR; ?>
							</a>
						</<?php echo $EVENT_TITLE_HTAG; ?>>

						<?php // Location (different display, depending on the fields filled) ?>
						<?php if ($EVENT_VENUE || $EVENT_CITY) : ?>
						<div class="ic-place">

							<?php // Venue name ?>
							<?php if ($EVENT_VENUE) : ?>
								<strong><?php echo JText::_('COM_ICAGENDA_EVENT_PLACE'); ?>:</strong> <?php echo $EVENT_VENUE; ?>
							<?php endif; ?>

							<?php // If Venue Name exists and city set (Google Maps). Displays Country if set. ?>
							<?php if ($EVENT_VENUE && $EVENT_CITY) : ?>
								<span>&nbsp;|&nbsp;</span>
								<strong><?php echo JText::_('COM_ICAGENDA_EVENT_CITY'); ?>:</strong> <?php echo $EVENT_CITY; ?><?php if ($EVENT_COUNTRY): ?>, <?php echo $EVENT_COUNTRY; ?><?php endif; ?>
							<?php endif; ?>

							<?php // If Venue Name doesn't exist and city set (Google Maps). Displays Country if set. ?>
							<?php if (!$EVENT_VENUE && $EVENT_CITY) : ?>
								<strong><?php echo JText::_('COM_ICAGENDA_EVENT_CITY'); ?>:</strong> <?php echo $EVENT_CITY; ?><?php if ($EVENT_COUNTRY): ?>, <?php echo $EVENT_COUNTRY; ?><?php endif; ?>
							<?php endif; ?>

						</div>
						<?php endif; ?>

						<?php // Short Description ?>
						<?php if ($EVENT_DESC) : ?>
							<div class="ic-descshort">
								<?php echo $EVENT_INTRO_TEXT; ?><?php echo $READ_MORE; ?>
							</div>
						<?php endif; ?>

						<?php // Addons Plugins (JComments, ...) - onListAddEventInfo ?>
						<?php if ($IC_LIST_ADD_EVENT_INFO) : ?>
							<?php echo $IC_LIST_ADD_EVENT_INFO; ?>
						<?php endif; ?>

					</div>
				</div>
			</div>

	<?php endif; ?>
<?php // END Event ?>
