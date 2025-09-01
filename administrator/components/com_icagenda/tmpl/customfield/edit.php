<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.9.5 2024-07-18
 *
 * @package     iCagenda.Admin
 * @subpackage  tmpl.customfield
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

HTMLHelper::_('jquery.framework');
// @todo Move to Vanilla.js

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
$editLabel     = $isNew ? Text::_('COM_ICAGENDA_CUSTOMFIELD_LEGEND_NEW') : Text::_('COM_ICAGENDA_CUSTOMFIELD_LEGEND_EDIT');
?>

<form action="<?php echo Route::_('index.php?option=com_icagenda&layout=' . $layout . $tmpl . '&id=' . (int) $this->item->id); ?>" method="post" name="adminForm" id="customfield-form" aria-label="<?php echo $editLabel; ?>" class="form-validate">

	<div class="ic-edit-header-title d-none d-lg-block">
		<?php echo $editLabel; ?> <span>iCagenda</span>
	</div>
	<br />

	<div class="main-card">
		<?php echo HTMLHelper::_('uitab.startTabSet', 'icTab', array('active' => 'customfield')); ?>

		<?php echo HTMLHelper::_('uitab.addTab', 'icTab', 'customfield', Text::_('COM_ICAGENDA_CUSTOMFIELD_PANEL_TITLE')); ?>

		<div class="row">
			<div class="col-lg-9">
				<div class="row">
					<div class="col-6">
						<?php echo $this->form->renderField('parent_form'); ?>
					</div>
					<div class="col-6">
						<?php echo $this->form->renderField('type'); ?>
					</div>
				</div>
				<fieldset id="fieldset-customfield" class="options-form">
					<legend><?php echo Text::_('COM_ICAGENDA_CUSTOMFIELD_PANEL_TITLE'); ?></legend>
					<section>
						<div class="row">
							<div class="col-6">
								<?php echo $this->form->renderField('title'); ?>
								<?php echo $this->form->renderField('slug'); ?>
								<?php echo $this->form->renderField('groups'); ?>
							</div>
							<div class="col-6">
								<?php //echo $this->form->renderField('spacer_options'); ?>
								<?php echo $this->form->renderField('placeholder'); ?>
								<?php echo $this->form->renderField('options'); ?>
								<?php echo $this->form->renderField('spacer_class'); ?>
								<?php echo $this->form->renderField('required'); ?>
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

		<?php echo HTMLHelper::_('uitab.addTab', 'icTab', 'notes', Text::_('COM_ICAGENDA_LEGEND_DESC')); ?>

		<div class="row">
			<?php echo $this->form->getLabel('description'); ?>
			<?php echo $this->form->getInput('description'); ?>
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

	<?php
	$js = '
document.addEventListener("DOMContentLoaded", doSomething, false);

function doSomething () {
	// code here...
	var options = document.getElementById("filter_type").options;
	for (let i = 0; i < options.length; i++) { 
		if (options[i].label == "CORE::NAME") {
			options[i].label = "<span class=\"label label-default\" style=\"display: inline; margin-right: 5px;\">' . Text::_('COM_ICAGENDA_LABEL_OVERRIDE') . '</span>&#160;";
		}
		console.log(options[i].label);//log the value
	}
}
	';

//	Factory::getDocument()->addScriptDeclaration($js);

	$js = '
	jQuery(document).ready(function($) {

		var $parent_form = $("#jform_parent_form").val();

		if ($parent_form !== "1") {
			removeOverrideOptions();
			updateTypeList();
		} else {
			updateTypeList();
		}

		$("#jform_parent_form").on("change",function(e) {

			var $parent_form = $("#jform_parent_form").val();

			if ($parent_form === "") {
				$("#jform_slug-lbl").css("display", "none");
				$("#jform_slug").css("display", "none");
			} else {
				$("#jform_slug-lbl").css("display", "block");
				$("#jform_slug").css("display", "block");
			}

			if ($parent_form !== "1") {
				removeOverrideOptions();
			} else {
				addOverrideOptions();
			}

			updateTypeList();
		})

		function addOverrideOptions() {
			$("#jform_type").append("<option value=\"core_name\">CORE::NAME</option>");
			$("#jform_type").append("<option value=\"core_email\">CORE::EMAIL</option>");
			$("#jform_type").append("<option value=\"core_phone\">CORE::PHONE</option>");
			$("#jform_type").append("<option value=\"core_date\">CORE::DATE</option>");
			$("#jform_type").append("<option value=\"core_people\">CORE::TICKETS</option>");
		}

		function removeOverrideOptions() {
			$("#jform_type option[value=\"core_name\"]").remove();
			$("#jform_type option[value=\"core_email\"]").remove();
			$("#jform_type option[value=\"core_phone\"]").remove();
			$("#jform_type option[value=\"core_date\"]").remove();
			$("#jform_type option[value=\"core_people\"]").remove();
		}

		function updateTypeList() {
			$("#jform_type option").each(
				function(){
					var arr = $(this).html().split("::");
					if (arr[1]) {
						var Text = arr[1] == "NAME"    ? "' . Text::_('COM_ICAGENDA_REGISTRATION_NAME') . '"    :
									arr[1] == "EMAIL"   ? "' . Text::_('COM_ICAGENDA_REGISTRATION_EMAIL') . '"   :
									arr[1] == "PHONE"   ? "' . Text::_('COM_ICAGENDA_REGISTRATION_PHONE') . '"   :
									arr[1] == "DATE"    ? "' . Text::_('COM_ICAGENDA_REGISTRATION_DATE') . '"    :
									arr[1] == "TICKETS" ? "' . Text::_('COM_ICAGENDA_REGISTRATION_TICKETS') . '" :
									$(this).html();
					}
					var html = arr[1] ? "<span class=\"badge bg-info\" style=\"display: inline; margin-right: 5px;\">' . Text::_('COM_ICAGENDA_LABEL_OVERRIDE') . '</span>:&#160;"+Text : arr[0];
					$(this).html(html);
				}
			);

			$("#jform_type").trigger("liszt:updated");
		}

	});';

	Factory::getDocument()->addScriptDeclaration($js);
