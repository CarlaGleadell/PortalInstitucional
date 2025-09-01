<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.8.0 2021-11-29
 *
 * @package     iCagenda.Admin
 * @subpackage  tmpl.feature
 * @link        https://www.icagenda.com
 *
 * @author      Cyril Rezé
 * @copyright   (c) 2012-2024 Cyril Rezé / iCagenda. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 *
 * @since       3.4
 *----------------------------------------------------------------------------
*/

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Router\Route;

/** @var Joomla\CMS\WebAsset\WebAssetManager $wa */
$wa = $this->document->getWebAssetManager();
$wa->useScript('keepalive')
	->useScript('form.validate');

$app      = Factory::getApplication();
$document = Factory::getDocument();
$input    = $app->input;

// In case of modal
$isModal = $input->get('layout') === 'modal';
$layout  = $isModal ? 'modal' : 'edit';
$tmpl    = $isModal || $input->get('tmpl', '', 'cmd') === 'component' ? '&tmpl=component' : '';

$isNew         = ((int) $this->item->id === 0);
$newEventValue = $isNew ? '1' : '0';
$editLabel     = $isNew ? Text::_('COM_ICAGENDA_LEGEND_NEW_FEATURE') : Text::_('COM_ICAGENDA_LEGEND_EDIT_FEATURE');
?>


<form action="<?php echo Route::_('index.php?option=com_icagenda&layout=' . $layout . $tmpl . '&id=' . (int) $this->item->id); ?>" method="post" name="adminForm" id="feature-form" aria-label="<?php echo $editLabel; ?>" class="form-validate">

	<div class="ic-edit-header-title d-none d-lg-block">
		<?php echo $editLabel; ?> <span>iCagenda</span>
	</div>

	<?php echo LayoutHelper::render('joomla.edit.title_alias', $this); ?>

	<div class="main-card">
		<?php echo HTMLHelper::_('uitab.startTabSet', 'icTab', array('active' => 'feature')); ?>

		<?php echo HTMLHelper::_('uitab.addTab', 'icTab', 'feature', Text::_('COM_ICAGENDA_TITLE_FEATURE')); ?>

		<div class="row">
			<div class="col-lg-9">
				<fieldset id="fieldset-feature" class="options-form">
					<legend><?php echo Text::_('COM_ICAGENDA_TITLE_FEATURE'); ?></legend>
					<section>
						<div class="row">
							<div class="col-6">
								<?php echo $this->form->renderField('icon'); ?>
								<?php echo $this->form->renderField('new_icon'); ?>
								<?php echo $this->form->renderField('icon_alt'); ?>
							</div>
							<div class="col-6">
								<?php echo $this->form->renderField('show_filter'); ?>
								<?php echo $this->form->renderField('desc'); ?>
							</div>
						</div>
					</section>
				</fieldset>
			</div>
			<div class="col-lg-3">
				<?php
				$globalFields = clone $this;
				$globalFields->fields = array('state');
				?>
				<?php echo LayoutHelper::render('joomla.edit.global', $this); ?>
				<input type="hidden" name="language" value="*" />
			</div>
		</div>

		<?php echo HTMLHelper::_('uitab.endTab'); ?>

		<?php echo HTMLHelper::_('uitab.addTab', 'icTab', 'publishing', Text::_('JGLOBAL_FIELDSET_PUBLISHING')); ?>

		<div class="row">
			<div class="col-lg-6">
				<fieldset id="fieldset-publishingdata" class="options-form">
					<legend><?php echo Text::_('JGLOBAL_FIELDSET_PUBLISHING'); ?></legend>
					<?php echo LayoutHelper::render('joomla.edit.publishingdata', $this); ?>
				</fieldset>
			</div>
		</div>

		<?php echo HTMLHelper::_('uitab.endTab'); ?>

		<?php echo HTMLHelper::_('uitab.endTabSet'); ?>

		<input type="hidden" name="task" value="">
		<?php echo HTMLHelper::_('form.token'); ?>
	</div>
</form>
