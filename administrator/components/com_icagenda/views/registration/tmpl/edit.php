<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.9.0 2023-10-26
 *
 * @package     iCagenda.Admin
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

JHtml::_('behavior.tooltip');
JHtml::_('behavior.keepalive');
JHtml::_('behavior.formvalidation');

$app = JFactory::getApplication();

// Access Administration Categories check.
if (JFactory::getUser()->authorise('icagenda.access.registrations', 'com_icagenda'))
{
	$document			= JFactory::getDocument();
	$RegistrationTag	= 'Registration';
	$RegistrationTitle	= JText::_('COM_ICAGENDA_REGISTRATION_INFORMATION');
	$DescTag			= 'desc';
	$DescTitle			= JText::_('COM_ICAGENDA_REGISTRATION_NOTES_DISPLAY_LABEL');
	$PublishingTag		= 'publishing';
	$PublishingTitle	= JText::_('JGLOBAL_FIELDSET_PUBLISHING');

	JHtml::_('formbehavior.chosen', 'select');
	jimport('joomla.html.html.bootstrap');

	$icPanRegistration	= 'icTab';
	$icPanDesc			= 'icTab';
	$icPanPublishing	= 'icTab';

	$iCmapDisplay		= '1';
	$startPane			= 'bootstrap.startTabSet';
	$addPanel			= 'bootstrap.addTab';
	$endPanel			= 'bootstrap.endTab';
	$endPane			= 'bootstrap.endTabSet';
	$RegistrationTag1	= $RegistrationTag;
	$RegistrationTag2	= $RegistrationTitle;
	$DescTag1			= $DescTag;
	$DescTag2			= $DescTitle;
	$PublishingTag1		= $PublishingTag;
	$PublishingTag2		= $PublishingTitle;
	?>

	<?php // ERROR ALERT ?>
	<div id="form_errors" class="alert alert-danger" style="display:none">
		<strong><?php echo JText::_('JGLOBAL_VALIDATION_FORM_FAILED'); ?></strong>
		<div id="message_error">
		</div>
	</div>

	<form action="<?php echo JRoute::_('index.php?option=com_icagenda&layout=edit&id='.(int) $this->item->id); ?>" method="post" name="adminForm" id="registration-form" class="form-validate" enctype="multipart/form-data">
		<div class="container">
			<header>
				<h1>
					<?php echo empty($this->item->id) ? JText::_('COM_ICAGENDA_LEGEND_NEW_REGISTRATION') : JText::sprintf('COM_ICAGENDA_LEGEND_EDIT_REGISTRATION', $this->item->id); ?>&nbsp;<span>iCagenda</span>
				</h1>
				<h2>
					<?php echo JText::_('COM_ICAGENDA_COMPONENT_DESC'); ?>
				</h2>
			</header>
			<div>&nbsp;</div>
			<div class="row-fluid">
				<div class="span10 form-horizontal">
					<?php echo JHtml::_($startPane, 'icTab', array('active' => 'Registration')); ?>
						<?php echo JHtml::_($addPanel, $icPanRegistration, $RegistrationTag1, $RegistrationTag2); ?>
							<div class="icpanel iCleft">
								<h1>
									<?php echo empty($this->item->id) ? JText::_('COM_ICAGENDA_LEGEND_NEW_REGISTRATION') : JText::sprintf('COM_ICAGENDA_LEGEND_EDIT_REGISTRATION', $this->item->id); ?>
								</h1>
								<hr>
								<div class="row-fluid">
									<div class="span6 iCleft">
										<div class="control-group">
											<div class="control-label">
												<?php echo $this->form->getLabel('userid'); ?>
											</div>
											<div class="controls">
												<?php echo $this->form->getInput('userid'); ?>
											</div>
										</div>
										<div class="control-group" id="userid_msg" style="display: none">
											<div class="control-label">
											</div>
											<div class="controls" id="fill_user_info_msg">
											</div>
										</div>
										<div class="control-group">
											<div class="control-label">
												<?php echo $this->form->getLabel('name'); ?>
											</div>
											<div class="controls">
												<?php echo $this->form->getInput('name'); ?>
											</div>
										</div>
										<div class="control-group">
											<div class="control-label">
												<?php echo $this->form->getLabel('email'); ?>
											</div>
											<div class="controls">
												<?php echo $this->form->getInput('email'); ?>
											</div>
										</div>
										<div class="control-group">
											<div class="control-label">
												<?php echo $this->form->getLabel('phone'); ?>
											</div>
											<div class="controls">
												<?php echo $this->form->getInput('phone'); ?>
											</div>
										</div>
										<h3><?php echo JText::_('COM_ICAGENDA_CUSTOMFIELDS'); ?></h3>
										<?php
										// Load Custom fields - Registration form (1)
										$customForm		= icagendaEvent::getCustomfieldGroups($this->item->eventid);
										echo icagendaCustomfields::loader(1, $customForm);
										?>
									</div>
									<div class="span6 iCleft">
										<div class="control-group">
											<div class="control-label">
												<?php echo $this->form->getLabel('eventid'); ?>
											</div>
											<div class="controls">
												<?php echo $this->form->getInput('eventid'); ?>
											</div>
										</div>
										<div class="control-group">
											<div class="control-label">
												<?php echo $this->form->getLabel('date'); ?>
											</div>
											<div class="controls">
												<?php echo $this->form->getInput('date'); ?>
											</div>
										</div>
										<div class="control-group">
											<div class="control-label">
												<?php echo $this->form->getLabel('period'); ?>
											</div>
											<div class="controls">
												<?php echo $this->form->getInput('period'); ?>
											</div>
										</div>
										<div class="control-group">
											<div class="control-label">
												<?php echo $this->form->getLabel('people'); ?>
											</div>
											<div class="controls">
												<?php echo $this->form->getInput('people'); ?>
											</div>
										</div>
										<input id="jform_data_people" type="hidden" name="jform[data_people]" value="<?php echo $this->item->people; ?>" />
									</div>
								</div>
							</div>
						<?php echo JHtml::_($endPanel); ?>
						<?php echo JHtml::_($addPanel, $icPanDesc, $DescTag1, $DescTag2); ?>
							<div class="icpanel iCleft">
								<h1><?php echo JText::_('COM_ICAGENDA_REGISTRATION_NOTES_DISPLAY_LABEL'); ?></h1>
								<hr>
								<div class="row-fluid">
									<div class="span12 iCleft">
										<!--h3><?php echo JText::_('COM_ICAGENDA_FORM_DESC_REGISTRATION_DESC'); ?></h3-->
										<?php echo $this->form->getInput('notes'); ?>
									</div>
								</div>
							</div>
						<?php echo JHtml::_($endPanel); ?>
						<?php echo JHtml::_($addPanel, $icPanPublishing, $PublishingTag1, $PublishingTag2); ?>
							<div class="icpanel iCleft">
								<h1><?php echo JText::_('JGLOBAL_FIELDSET_PUBLISHING'); ?></h1>
								<hr>
								<div class="row-fluid">
									<div class="span6 iCleft">
										<div class="control-group">
											<div class="control-label">
												<?php echo $this->form->getLabel('id'); ?>
											</div>
											<div class="controls">
												<?php echo $this->form->getInput('id'); ?>
											</div>
										</div>
										<div class="control-group">
											<div class="control-label">
												<?php echo $this->form->getLabel('state'); ?>
											</div>
											<div class="controls">
												<?php echo $this->form->getInput('state'); ?>
											</div>
										</div>
										<div class="control-group">
											<div class="control-label">
												<?php echo $this->form->getLabel('created'); ?>
											</div>
											<div class="controls">
												<?php echo $this->form->getInput('created'); ?>
											</div>
										</div>
										<div class="control-group">
											<div class="control-label">
												<?php echo $this->form->getLabel('created_by'); ?>
											</div>
											<div class="controls">
												<?php echo $this->form->getInput('created_by'); ?>
											</div>
										</div>
										<div class="control-group">
											<div class="control-label">
												<?php echo $this->form->getLabel('modified'); ?>
											</div>
											<div class="controls">
												<?php echo $this->form->getInput('modified'); ?>
											</div>
										</div>
										<div class="control-group">
											<div class="control-label">
												<?php echo $this->form->getLabel('modified_by'); ?>
											</div>
											<div class="controls">
												<?php echo $this->form->getInput('modified_by'); ?>
											</div>
										</div>
										<div class="control-group">
											<div class="control-label">
												<?php echo $this->form->getLabel('checked_out'); ?>
											</div>
											<div class="controls">
												<?php echo $this->form->getInput('checked_out'); ?>
											</div>
										</div>
										<div class="control-group">
											<div class="control-label">
												<?php echo $this->form->getLabel('checked_out_time'); ?>
											</div>
											<div class="controls">
												<?php echo $this->form->getInput('checked_out_time'); ?>
											</div>
										</div>
									</div>
								</div>
							</div>
						<?php echo JHtml::_($endPanel); ?>
					<?php echo JHtml::_($endPane, 'icTab'); ?>
				</div>
				<div class="span2 iCleft">
					<h4><?php echo JText::_('COM_ICAGENDA_TITLE_SIDEBAR_DETAILS'); ?></h4>
					<hr>
					<div class="control-group">
						<div class="control-label">
							<?php echo $this->form->getLabel('status'); ?>
						</div>
						<div class="controls">
							<?php echo $this->form->getInput('status'); ?>
						</div>
					</div>
				</div>
			</div>
			<div class="clr"></div>
		</div>
		<input type="hidden" name="task" value="" />
		<?php echo JHtml::_('form.token'); ?>
	</form>

<?php
	// Build the select user update script.
	$script = array();
	$script[] = '	function setUserID() {';

	if (JComponentHelper::getParams('com_icagenda')->get('autofilluser', 1) == 1)
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
			$script[] = '					$("#fill_user_info_msg").html("<div class=\"alert alert-info alert-small\"><p>' . JText::_("COM_ICAGENDA_REGISTRATION_UPDATE_NAME_AND_EMAIL_ALERT") . '</p><div id=\"update-info\" class=\"btn btn-success\">' . JText::_("JYES") . '</div> <div id=\"no-update-info\" class=\"btn btn-danger\">' . JText::_("JNO") . '</div></div>");';
			$script[] = '					$("#update-info").on("click",function(){';
			$script[] = '						$("#jform_name").val(userInfo[0]);';
			$script[] = '						$("#jform_email").val(userInfo[1]);';
			$script[] = '						$("#fill_user_info_msg").html("<div class=\"alert alert-success alert-small\"><p>' . JText::_("COM_ICAGENDA_REGISTRATION_UPDATE_NAME_AND_EMAIL_SUCCESS") . '</p></div>");';
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
	JFactory::getDocument()->addScriptDeclaration(implode("\n", $script));

	// Script validation for Registration form (1)
	$iCheckForm = icagendaForm::submit(1);
	$document->addScriptDeclaration($iCheckForm);

}
else
{
	$app->enqueueMessage(JText::_('JERROR_ALERTNOAUTHOR'), 'warning');
	$app->redirect(htmlspecialchars_decode('index.php?option=com_icagenda&view=icagenda'));
}
