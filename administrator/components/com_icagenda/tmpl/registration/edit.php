<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.9.0 2024-02-24
 *
 * @package     iCagenda.Admin
 * @subpackage  tmpl.registration
 * @link        https://www.icagenda.com
 *
 * @author      Cyril Rezé
 * @copyright   (c) 2012-2024 Cyril Rezé / iCagenda. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 *
 * @since       3.3.3
 *----------------------------------------------------------------------------
*/

defined('_JEXEC') or die;

use iCutilities\Customfields\Customfields as icagendaCustomfields;
use iCutilities\Event\Event as icagendaEvent;
//use iCutilities\Form\Form as icagendaForm;
use Joomla\CMS\Component\ComponentHelper;
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
$editLabel     = $isNew ? Text::_('COM_ICAGENDA_LEGEND_NEW_REGISTRATION') : Text::_('COM_ICAGENDA_LEGEND_EDIT_REGISTRATION');
?>

<form action="<?php echo Route::_('index.php?option=com_icagenda&layout=' . $layout . $tmpl . '&id=' . (int) $this->item->id); ?>" method="post" name="adminForm" id="registration-form" aria-label="<?php echo $editLabel; ?>" class="form-validate">

	<div class="ic-edit-header-title d-none d-lg-block">
		<?php echo $editLabel; ?> <span>iCagenda</span>
	</div>
	<br />

	<div class="main-card">
		<?php echo HTMLHelper::_('uitab.startTabSet', 'icTab', array('active' => 'registration')); ?>

		<?php echo HTMLHelper::_('uitab.addTab', 'icTab', 'registration', Text::_('COM_ICAGENDA_REGISTRATION_INFORMATION')); ?>

		<div class="row">
			<div class="col-lg-9">
				<fieldset id="fieldset-registration" class="options-form">
					<legend><?php echo Text::_('COM_ICAGENDA_REGISTRATION_INFORMATION'); ?></legend>
					<section>
						<div class="row">
							<div class="col-6">
								<?php echo $this->form->renderField('userid'); ?>
								<div class="control-group" id="userid_msg" style="display: none">
									<div class="control-label">
									</div>
									<div class="controls" id="fill_user_info_msg">
									</div>
								</div>
								<?php echo $this->form->renderField('name'); ?>
								<?php echo $this->form->renderField('email'); ?>
								<?php echo $this->form->renderField('phone'); ?>
								<fieldset id="fieldset-customfields" class="options-form">
									<legend><?php echo Text::_('COM_ICAGENDA_CUSTOMFIELDS'); ?></legend>
									<?php
									// Load Custom fields - Registration form (1)
									$customForm   = icagendaEvent::getCustomfieldGroups($this->item->eventid);
									echo icagendaCustomfields::loader(1, $customForm);
									?>
								</fieldset>
							</div>
							<div class="col-6">
								<?php echo $this->form->renderField('eventid'); ?>
								<?php echo $this->form->renderField('date'); ?>
								<?php echo $this->form->renderField('period'); ?>
								<?php echo $this->form->renderField('people'); ?>
								<input id="jform_data_people" type="hidden" name="jform[data_people]" value="<?php echo $this->item->people; ?>" />
							</div>
						</div>
					</section>
				</fieldset>
			</div>
			<div class="col-lg-3">
				<?php
				$globalFields = clone $this;
				$globalFields->fields = array('status');
				?>
				<?php echo LayoutHelper::render('joomla.edit.global', $globalFields); ?>
			</div>
		</div>

		<?php echo HTMLHelper::_('uitab.endTab'); ?>

		<?php echo HTMLHelper::_('uitab.addTab', 'icTab', 'notes', Text::_('COM_ICAGENDA_REGISTRATION_NOTES_DISPLAY_LABEL')); ?>

		<div class="row">
			<?php echo $this->form->getLabel('notes'); ?>
			<?php echo $this->form->getInput('notes'); ?>
		</div>

		<?php echo HTMLHelper::_('uitab.endTab'); ?>

		<?php echo HTMLHelper::_('uitab.addTab', 'icTab', 'publishing', Text::_('JGLOBAL_FIELDSET_PUBLISHING')); ?>

		<div class="row">
			<div class="col-lg-6">
				<fieldset id="fieldset-publishingdata" class="options-form">
					<legend><?php echo Text::_('JGLOBAL_FIELDSET_PUBLISHING'); ?></legend>
					<?php echo LayoutHelper::render('joomla.edit.publishingdata', $this); ?>
					<?php echo $this->form->renderField('state'); ?>
					<?php echo $this->form->renderField('itemid'); ?>
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
	// Build the select user update script.
	$script = array();
	$script[] = '	function setUserID() {';

	if (ComponentHelper::getParams('com_icagenda')->get('autofilluser', 1) == 1)
	{
		$script[] = '		jQuery(document).ready(function($){';
		$script[] = '			var userid = $("#jform_userid_id").val();';
		$script[] = '			$.ajax({url: "index.php?option=com_icagenda&task=registration.registrationName&userid="+userid,';
		$script[] = '				success: function(output) {';
		$script[] = '					userInfo = output.split(",");';

		if ( ! $this->item->name && ! $this->item->email)
		{
			$script[] = '					$("#jform_name").val(userInfo[0]);';
			$script[] = '					$("#jform_email").val(userInfo[1]);';
		}
		else
		{
			$script[] = '					$("#userid_msg").show();';
			$script[] = '					$("#fill_user_info_msg").html("<div class=\"alert alert-info alert-small\"><p>' . Text::_("COM_ICAGENDA_REGISTRATION_UPDATE_NAME_AND_EMAIL_ALERT") . '</p><div id=\"update-info\" class=\"btn btn-success\">' . Text::_("JYES") . '</div> <div id=\"no-update-info\" class=\"btn btn-danger\">' . Text::_("JNO") . '</div></div>");';
    		$script[] = '					$("#update-info").on("click",function(){';
			$script[] = '						$("#jform_name").val(userInfo[0]);';
			$script[] = '						$("#jform_email").val(userInfo[1]);';
			$script[] = '						$("#fill_user_info_msg").html("<div class=\"alert alert-success alert-small\"><p>' . Text::_("COM_ICAGENDA_REGISTRATION_UPDATE_NAME_AND_EMAIL_SUCCESS") . '</p></div>");';
			$script[] = '						$("#userid_msg").delay(700).fadeOut(300);';
			$script[] = '					});';
			$script[] = '					$("#no-update-info").on("click",function(){';
			$script[] = '						$("#userid_msg").fadeOut(300);';
			$script[] = '					});';
		}

		$script[] = '				},';
		$script[] = '				error: function (xhr, ajaxOptions, thrownError) {';
		$script[] = '					alert(xhr.status + " "+ thrownError);';
		$script[] = '				}';
		$script[] = '			});';
		$script[] = '		});';
	}
	else
	{
		$script[] = '		console.log("iCagenda info: Auto-fill Name and Email disabled");';
	}

	$script[] = '	}';

	// Add the script to the document head.
	$document->addScriptDeclaration(implode("\n", $script));

	// Script validation for Registration form (1)
//	$iCheckForm = icagendaForm::submit(1);
//	$document->addScriptDeclaration($iCheckForm);
