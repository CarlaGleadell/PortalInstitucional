<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.8.23 2023-12-01
 *
 * @package     iCagenda.Site
 * @subpackage  Themes.Packs
 * @link        https://www.icagenda.com
 *
 * @author      Cyril Rezé
 * @copyright   (c) 2012-2024 Cyril Rezé / iCagenda. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 *
 * @since       1.0
 *----------------------------------------------------------------------------
 * @themepack   ic_rounded
 * @template    event
 *----------------------------------------------------------------------------
*/

defined('_JEXEC') or die;

use Joomla\CMS\Language\Text as JText;
?>

<!-- Event details -->

<?php // Event Details Template ?>

	<?php // Header (Title/Category) of the event ?>
	<div class="event-header ic-event-header ic-clearfix">

		<?php // Title of the event ?>
		<div class="title-header ic-title-header ic-float-left">
			<h1>
				<?php echo $EVENT_TITLE; ?>
			</h1>
		</div>

		<?php // Feature icons ?>
		<?php if (!empty($FEATURES_ICONSIZE_EVENT)) : ?>
		<div class="ic-features-container">
			<?php foreach ($FEATURES_ICONS as $icon) : ?>
			<div class="ic-feature-icon">
				<img class="iCtip" src="<?php echo $FEATURES_ICONROOT_EVENT . $icon['icon']; ?>" alt="<?php echo $icon['icon_alt']; ?>" title="<?php echo $SHOW_ICON_TITLE == '1' ? $icon['icon_alt'] : ''; ?>">
			</div>
			<?php endforeach; ?>
		</div>
		<?php endif; ?>

		<?php // Category ?>
		<div class="title-cat ic-title-cat ic-float-right ic-details-cat" style="color:<?php echo $CATEGORY_COLOR; ?>;">
			<?php echo $CATEGORY_TITLE; ?>
		</div>

	</div>

	<?php // Sharing and Registration ?>
	<div class="ic-clearfix">

		<?php // AddThis Social Sharing // @deprecated 3.8.18 - removed 3.9 ?>
		<div class="ic-event-addthis ic-float-left">
			<?php echo $EVENT_SHARING; ?>
		</div>

		<?php // Registration button ?>
		<div class="ic-event-registration ic-float-right">
			<?php echo $EVENT_REGISTER_BUTTON; ?>
		</div>

	</div>

	<?php // Event Informations Display ?>
	<div class="icinfo ic-info ic-clearfix">

		<?php // Show Image of the event ?>
		<?php if ($IMAGE_LARGE_HTML) : ?>
			<div class="image ic-image">
				<?php echo $IMAGE_LARGE_HTML; ?>
			</div>
		<?php endif; ?>

		<?php // Details of the event ?>
		<div class="details ic-details">

			<?php // Next Date ('next' 'today' or 'last date' if no next date) ?>
			<div class="ic-event-date">
				<span class="iCicon iCicon-calendar" arial-label="<?php echo $EVENT_VIEW_DATE_LABEL; ?>"></span>&nbsp; <?php echo $EVENT_VIEW_DATE; ?>
			</div>

			<?php // Location (different display, depending on the fields filled) ?>
			<p>

				<?php // Venue name ?>
				<?php if ($EVENT_VENUE) : ?>
					<span class="iCicon iCicon-location" arial-label="<?php echo JText::_('COM_ICAGENDA_EVENT_PLACE'); ?>"></span>&nbsp; <?php echo $EVENT_VENUE;?>
				<?php endif; ?>

				<?php // If Venue Name exists and city set (Google Maps). Displays Country if set. ?>
				<?php if ($EVENT_VENUE && $EVENT_CITY) : ?>
					<span>&nbsp;|&nbsp;</span>
					<?php echo $EVENT_CITY; ?><?php if ($EVENT_COUNTRY): ?>, <?php echo $EVENT_COUNTRY; ?><?php endif; ?>
				<?php endif; ?>

				<?php // If Venue Name doesn't exist and city set (Google Maps). Displays Country if set. ?>
				<?php if (!$EVENT_VENUE && $EVENT_CITY) : ?>
					<span class="iCicon iCicon-location" arial-label="<?php echo JText::_('COM_ICAGENDA_EVENT_CITY'); ?>"></span> <?php echo $EVENT_CITY; ?><?php if ($EVENT_COUNTRY): ?>, <?php echo $EVENT_COUNTRY; ?><?php endif; ?>
				<?php endif; ?>

			</p>

		</div>

		<?php if ($EVENT_DESC || $EVENT_INFOS) : ?>

		<?php // description text ?>
		<?php if ($EVENT_DESC) : ?>
		<div id="ic-detail-desc" class="ic-detail-desc">
			<div class="ic-short-description">
				<?php echo $EVENT_SHORTDESC; ?>
			</div>
			<div class="ic-full-description">
				<?php echo $EVENT_DESCRIPTION; ?>
			</div>
		<?php endif; ?>

		<?php if (!$EVENT_DESC && $EVENT_INFOS) : ?>
		<div id="ic-detail-info" class="ic-detail-info">
		<?php endif; ?>

			<p>&nbsp;</p>

			<?php // Information ?>
			<?php if ($EVENT_INFOS) : ?>
			<div class="ic-info-box">

				<?php // Title Box Information ?>
				<div class="ic-info-box-header">
					<label><?php echo JText::_('COM_ICAGENDA_EVENT_INFOS'); ?></label>
				</div>

				<?php // Information Details ?>
				<div class="ic-info-box-content ic-divTable ic-align-left ic-clearfix">

					<?php // file attached ?>
					<?php if ($EVENT_ATTACHEMENTS) : ?>
					<div class="ic-info-box-file">
						<label><?php echo JText::_('COM_ICAGENDA_EVENT_FILE'); ?></label>
						<div class="ic-download"><?php echo $EVENT_ATTACHEMENTS_TAG; ?></div>
					</div>
					<?php endif; ?>

					<?php // Nb of tickets available ?>
					<?php if ($SEATS_AVAILABLE) : ?>
					<div class="ic-divRow ic-info-tickets-left">
						<div class="ic-divCell ic-label"><?php echo JText::_('COM_ICAGENDA_REGISTRATION_PLACES_LEFT'); ?></div>
						<div class="ic-divCell ic-value"><?php echo $SEATS_AVAILABLE; ?></div>
					</div>
					<?php endif; ?>

					<?php // Max. Nb of tickets ?>
					<?php if ($MAX_NB_OF_SEATS) : ?>
					<div class="ic-divRow ic-info-tickets">
						<div class="ic-divCell ic-label"><?php echo JText::_('COM_ICAGENDA_REGISTRATION_NUMBER_PLACES'); ?></div>
						<div class="ic-divCell ic-value"><?php echo $MAX_NB_OF_SEATS; ?></div>
					</div>
					<?php endif; ?>

					<?php // Phone Number ?>
					<?php if ($EVENT_PHONE) : ?>
					<div class="ic-divRow ic-info-phone">
						<div class="ic-divCell ic-label"><?php echo JText::_('COM_ICAGENDA_EVENT_PHONE'); ?></div>
						<div class="ic-divCell ic-value"><?php echo $EVENT_PHONE; ?></div>
					</div>
					<?php endif; ?>

					<?php // Email ?>
					<?php if ($EVENT_EMAIL) : ?>
					<div class="ic-divRow ic-info-email">
						<div class="ic-divCell ic-label"><?php echo JText::_('COM_ICAGENDA_EVENT_MAIL'); ?></div>
						<div class="ic-divCell ic-value"><?php echo $EVENT_EMAIL_CLOAKING; ?></div>
					</div>
					<?php endif; ?>

					<?php // Website ?>
					<?php if ($EVENT_WEBSITE) : ?>
					<div class="ic-divRow ic-info-website">
						<div class="ic-divCell ic-label"><?php echo JText::_('COM_ICAGENDA_EVENT_WEBSITE'); ?></div>
						<div class="ic-divCell ic-value"><?php echo $EVENT_WEBSITE_LINK; ?></div>
					</div>
					<?php endif; ?>

					<?php // Custom Fields ?>
					<?php if ($CUSTOM_FIELDS) : ?>
						<?php foreach ($CUSTOM_FIELDS as $FIELD) : ?>
							<?php if ($FIELD->title && $FIELD->value) : ?>
								<div class="ic-divRow ic-info-<?php echo $FIELD->slug; ?>">
									<div class="ic-divCell ic-label"><?php echo $FIELD->title; ?></div>
									<div class="ic-divCell ic-value"><?php echo $FIELD->value; ?></div>
								</div>
							<?php endif; ?>
						<?php endforeach; ?>
					<?php endif; ?>

					<?php // Address ?>
					<?php if ($EVENT_ADDRESS) : ?>
					<div class="ic-divRow ic-info-address">
						<div class="ic-divCell ic-label"><?php echo JText::_('COM_ICAGENDA_EVENT_ADDRESS'); ?></div>
						<div class="ic-divCell ic-value"><?php echo $EVENT_ADDRESS; ?></div>
					</div>
					<?php endif; ?>
				</div>

			</div><?php // end div.details ?>
			<?php endif; ?>

		</div>
		<?php endif; ?>

	</div>

	<?php // Google Maps ?>
	<?php if ($GOOGLEMAPS_COORDINATES) : ?>
	<p>&nbsp;</p>
	<div id="ic-detail-map" class="ic-clearfix">
		<h3><?php echo JText::_('COM_ICAGENDA_EVENT_MAP'); ?></h3><br />
		<div id="icagenda_map">
			<?php echo $EVENT_MAP; ?>
		</div>
	</div>
	<?php endif; ?>

	<?php // List of all dates (multi-dates and/or period from to) ?>
	<?php if ($EVENT_SINGLE_DATES || $EVENT_PERIOD) : ?>
	<p>&nbsp;</p>
	<div id="detail-date-list" class="ic-clearfix">
		<h3 class="alldates"><?php echo JText::_('COM_ICAGENDA_EVENT_DATES'); ?></h3><br />
		<div class="datesList">
			<?php echo $EVENT_PERIOD; ?>
			<?php echo $EVENT_SINGLE_DATES; ?>
		</div>
	</div>
	<?php endif; ?>

	<?php // List of Participants ?>
	<?php if ($PARTICIPANTS_DISPLAY == 1 && $EVENT_PARTICIPANTS) : ?>
	<p>&nbsp;</p>
	<div class="ic-participants ic-rounded-10 ic-clearfix">
		<h3><?php echo $PARTICIPANTS_HEADER; ?></h3>
		<?php echo $EVENT_PARTICIPANTS; ?>
	</div>
	<?php endif; ?>
