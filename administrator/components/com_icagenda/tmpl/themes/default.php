<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.8.0 2021-10-11
 *
 * @package     iCagenda.Admin
 * @subpackage  tmpl.themes
 * @link        https://www.icagenda.com
 *
 * @author      Cyril Rezé
 * @copyright   (c) 2012-2024 Cyril Rezé / iCagenda. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 *
 * @since       2.0
 *----------------------------------------------------------------------------
*/

defined('_JEXEC') or die;

use iCutilities\Theme\Theme as icagendaTheme;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;

$params  = ComponentHelper::getParams('com_icagenda');
$icsys   = $params->get('icsys');
$version = $params->get('version');

// Check Theme Packs Compatibility
icagendaTheme::checkThemePacks();
?>

<div id="container">
	<div class="row">
		<div class="col-12">
			<div class="row">
				<div class="col-lg-7">
					<div class="ic-panel ic-min-h-200">
						<div class="row">
							<form enctype="multipart/form-data" action="index.php" method="post" name="adminForm" id="themes-form" class="form-validate">
								<br />
								<div class="form-group">
									<label for="install_package" class="mt-2 pb-1"><strong><?php echo Text::_('COM_ICAGENDA_THEMES_INSTALL_LBL'); ?></strong></label>
									<span class="input-group">
										<input type="file" id="sfile-upload" class="form-control" name="Filedata" />
										<button onclick="submitbutton()" class="btn btn-primary" id="upload-submit">
											<i class="icon-upload icon-white"></i> <?php echo Text::_('COM_ICAGENDA_UPLOAD_AND_INSTALL'); ?>
										</button>
									</span>
									<small class="form-text">
										<?php echo Text::_('COM_ICAGENDA_THEMES_INSTALL_DESC'); ?>
									</small>
								</div>
								<input type="hidden" name="type" value="" />
								<input type="hidden" name="option" value="com_icagenda" />
								<input type="hidden" name="task" value="themes.themeinstall" />
								<?php echo HTMLHelper::_('form.token'); ?>
							</form>
						</div>
					</div>
				</div>
				<div class="col-lg-5">
					<?php echo LayoutHelper::render('icagenda.admin.logo', array('version' => $version)); ?>
				</div>
			</div>
		</div>
		<div class="col-12">
			<div class="row">
				<div class="col-12">
					<div class="ic-panel">
						<h2><?php echo Text::_('COM_ICAGENDA_THEMES_LIST_TITLE'); ?></h2>
						<?php echo HTMLHelper::_('themes.list'); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php echo LayoutHelper::render('icagenda.admin.footer'); ?>
</div>
