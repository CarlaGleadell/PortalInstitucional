<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.8.0 2021-09-17
 *
 * @package     iCagenda.Admin
 * @subpackage  tmpl.icategory
 * @link        https://www.icagenda.com
 *
 * @author      Cyril Rezé
 * @copyright   (c) 2012-2024 Cyril Rezé / iCagenda. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 *
 * @since       1.0
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

$app = Factory::getApplication();
$input = $app->input;

// In case of modal
$isModal = $input->get('layout') === 'modal';
$layout  = $isModal ? 'modal' : 'edit';
$tmpl    = $isModal || $input->get('tmpl', '', 'cmd') === 'component' ? '&tmpl=component' : '';
?>

<form action="<?php echo Route::_('index.php?option=com_icagenda&layout=' . $layout . $tmpl . '&id=' . (int) $this->item->id); ?>" method="post" name="adminForm" id="category-form" aria-label="<?php echo ((int) $this->item->id === 0 ? Text::_('COM_ICAGENDA_LEGEND_NEW_CATEGORY') : Text::_('COM_ICAGENDA_LEGEND_EDIT_CATEGORY')); ?>" class="form-validate">

	<div class="ic-edit-header-title d-none d-lg-block">
		<?php echo empty($this->item->id) ? Text::_('COM_ICAGENDA_LEGEND_NEW_CATEGORY') : Text::_('COM_ICAGENDA_LEGEND_EDIT_CATEGORY'); ?> <span>iCagenda</span>
	</div>

	<?php echo LayoutHelper::render('joomla.edit.title_alias', $this); ?>

	<div class="main-card">
		<?php echo HTMLHelper::_('uitab.startTabSet', 'icTab', array('active' => 'category')); ?>
		<?php echo HTMLHelper::_('uitab.addTab', 'icTab', 'category', Text::_('COM_ICAGENDA_TITLE_CATEGORY')); ?>

		<div class="row">
			<div class="col-lg-9">
				<?php echo $this->form->getLabel('desc'); ?>
				<?php echo $this->form->getInput('desc'); ?>
			</div>
			<div class="col-lg-3">
				<?php echo LayoutHelper::render('joomla.edit.global', $this); ?>
			</div>
		</div>

		<?php echo HTMLHelper::_('uitab.endTab'); ?>

		<?php echo HTMLHelper::_('uitab.addTab', 'icTab', 'options', Text::_('JGLOBAL_FIELDSET_OPTIONS')); ?>

		<div class="row">
			<div class="col-12">
				<fieldset id="fieldset-optionsdata" class="options-form">
					<legend><?php echo Text::_('JGLOBAL_FIELDSET_OPTIONS'); ?></legend>
					<div class="col-lg-6">
						<?php echo $this->form->renderField('color'); ?>
					</div>
				</fieldset>
			</div>
		</div>

		<?php echo HTMLHelper::_('uitab.endTab'); ?>

		<?php echo HTMLHelper::_('uitab.addTab', 'icTab', 'publishing', Text::_('JGLOBAL_FIELDSET_PUBLISHING')); ?>
		<div class="row">
			<div class="col-12">
				<fieldset id="fieldset-publishingdata" class="options-form">
					<legend><?php echo Text::_('JGLOBAL_FIELDSET_PUBLISHING'); ?></legend>
					<div class="col-lg-6">
					<?php echo LayoutHelper::render('joomla.edit.publishingdata', $this); ?>
					</div>
				</fieldset>
			</div>
		</div>

		<?php echo HTMLHelper::_('uitab.endTab'); ?>
		<?php echo HTMLHelper::_('uitab.endTabSet'); ?>

		<input type="hidden" name="task" value="">
		<?php echo HTMLHelper::_('form.token'); ?>
	</div>
</form>
