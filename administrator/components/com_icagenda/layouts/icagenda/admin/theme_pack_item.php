<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.9.7 2024-10-10
 *
 * @package     iCagenda.Administrator
 * @subpackage  Layouts.icagenda
 * @link        https://www.joomlic.com
 *
 * @author      Cyril Reze
 * @copyright   (c) 2012-2024 Cyril Reze / JoomliC. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 *
 * @since       3.8.0
 *----------------------------------------------------------------------------
*/

defined('_JEXEC') or die;

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;

$author         = $displayData['author'] ?? '';
$authorEmail    = $displayData['authorEmail'] ?? '';
$authorUrl      = $displayData['authorUrl'] ?? '';
$authorWebsite  = $displayData['authorWebsite'] ?? '';
$description    = $displayData['description'] ?? '';
$name           = $displayData['name'] ?? '';
$pack           = $displayData['pack'] ?? '';
$updateDownload = $displayData['updateDownload'] ?? '';
$updateVersion  = $displayData['updateVersion'] ?? '';
$version        = $displayData['version'] ?? Text::_('COM_ICAGENDA_THEMES_VERSION_UNKNOWN');

$theme = array(
	'name' => $name,
	'slug' => $pack,
);
?>

<div class="icagenda-card ic-bg-grey-light">
	<div class="text-center">
		<h4>
			<?php echo $name; ?><br />
			<small>[&nbsp;<span style="color:grey"><?php echo $pack; ?></span>&nbsp;]</small>
		</h4>
	</div>
	<div class="text-center">
		<?php echo HTMLHelper::_('themes.thumb', $theme); ?>
		<?php echo HTMLHelper::_('themes.thumbModal', $theme); ?>
	</div>
	<p>
		<div>
			<i><?php echo $description; ?></i>
		</div>
	</p>
	<p>
		<?php if ($author) : ?>
			<div>
				<?php if ($authorEmail) : ?>
					<?php echo Text::sprintf('COM_ICAGENDA_THEMES_AUTHOR', '<a href="mailto:' . $authorEmail . '"><strong>' . $author . '</strong></a>'); ?>
				<?php else : ?>
					<?php echo Text::sprintf('COM_ICAGENDA_THEMES_AUTHOR', '<strong>' . $author . '</strong>'); ?>
				<?php endif; ?>
			</div>
		<?php endif; ?>
		<?php if ($authorWebsite) : ?>
			<div>
				<?php if ($authorUrl) : ?>
					<?php echo Text::sprintf('COM_ICAGENDA_THEMES_AUTHOR_WEBSITE', '<a href="' . $authorUrl . '" target="_blank"><strong>' . $authorWebsite . '</strong></a>'); ?>
				<?php else : ?>
					<?php echo Text::sprintf('COM_ICAGENDA_THEMES_AUTHOR_WEBSITE', '<strong>' . $authorWebsite . '</strong>'); ?>
				<?php endif; ?>
			</div>
		<?php endif; ?>
		<div>
			<?php echo Text::sprintf('COM_ICAGENDA_THEMES_VERSION', '<strong>' . $version . '</strong>'); ?>
		</div>
		<?php if ($updateVersion && ($updateVersion > $version)) : ?>
			<div class="text-danger">
				<?php echo Text::sprintf('COM_ICAGENDA_THEMES_LATEST_VERSION', '<strong>' . $updateVersion . '</strong>'); ?>
			</div>
		<?php endif; ?>
	</p>
	<!--p>
		<?php //if ($updateVersion && ($updateVersion > $version)) : ?>
			<div class="text-center">
				<a class="btn btn-danger" href="<?php //echo $updateDownload; ?>" target="_blank">
					<?php //echo Text::sprintf('COM_ICAGENDA_THEMES_UPDATE', $updateVersion); ?>
				</a>
			</div>
		<?php //elseif ( ! $updateVersion) : ?>
			<div class="text-center alert alert-warning">
				<?php //echo Text::_('COM_ICAGENDA_THEMES_PLEASE_CONTACT_AUTHOR'); ?>
			</div>
		<?php //else : ?>
			<div class="text-center alert alert-light">
				<?php //echo Text::_('COM_ICAGENDA_THEMES_LATEST_INSTALLED'); ?>
			</div>
		<?php //endif; ?>
	</p-->
</div>
