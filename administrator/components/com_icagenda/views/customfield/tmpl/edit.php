<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.9.0 2023-10-25
 *
 * @package     iCagenda.Admin
 * @link        https://www.icagenda.com
 *
 * @author      Cyril Rezé
 * @copyright   (c) 2012-2024 Cyril Rezé / iCagenda. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 *
 * @since       3.4.0
 *----------------------------------------------------------------------------
*/

defined('_JEXEC') or die;

JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');

$app = JFactory::getApplication();
$document = JFactory::getDocument();

// Access Administration Categories check.
if (JFactory::getUser()->authorise('icagenda.access.customfields', 'com_icagenda'))
{
	$PanelOne_Tag		= 'customfield';
	$PanelOne_Title		= JText::_('COM_ICAGENDA_CUSTOMFIELD_PANEL_TITLE', true);
	$PanelTwo_Tag		= 'desc';
	$PanelTwo_Title		= JText::_('COM_ICAGENDA_LEGEND_DESC', true);
	$PublishingTag		= 'publishing';
	$PublishingTitle	= JText::_('JGLOBAL_FIELDSET_PUBLISHING', true);

	JHtml::_('formbehavior.chosen', 'select');
	jimport('joomla.html.html.bootstrap');

	$icPanFirst			= 'icTab';
	$icPanDesc			= 'icTab';
	$icPanPublishing	= 'icTab';

	$iCmapDisplay	= '1';
	$startPane		= 'bootstrap.startTabSet';
	$addPanel		= 'bootstrap.addTab';
	$endPanel		= 'bootstrap.endTab';
	$endPane		= 'bootstrap.endTabSet';
	$PanelOne_Tag1	= $PanelOne_Tag;
	$PanelOne_Tag2	= $PanelOne_Title;
	$PanelTwo_Tag1	= $PanelTwo_Tag;
	$PanelTwo_Tag2	= $PanelTwo_Title;
	$PublishingTag1	= $PublishingTag;
	$PublishingTag2	= $PublishingTitle;
	?>

	<script type="text/javascript">
	Joomla.submitbutton = function(task)
	{
		if (task == 'customfield.cancel' || document.formvalidator.isValid(document.id('customfield-form'))) {
			Joomla.submitform(task, document.getElementById('customfield-form'));
		}
		else {
			alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED'));?>');
		}
	}
	</script>
	<form action="<?php echo JRoute::_('index.php?option=com_icagenda&layout=edit&id='.(int) $this->item->id); ?>" method="post" name="adminForm" id="customfield-form" class="form-validate">
		<div class="container">
			<header>
				<h1>
					<?php echo empty($this->item->id) ? JText::_('COM_ICAGENDA_CUSTOMFIELD_LEGEND_NEW') : JText::sprintf('COM_ICAGENDA_CUSTOMFIELD_LEGEND_EDIT', $this->item->id); ?>&nbsp;<span>iCagenda</span>
				</h1>
				<h2>
					<?php echo JText::_('COM_ICAGENDA_COMPONENT_DESC'); ?>
				</h2>
			</header>
			<div>&nbsp;</div>
			<div class="row-fluid">
				<div class="span10 form-horizontal">
					<?php echo JHtml::_($startPane, 'icTab', array('active' => 'customfield')); ?>
						<?php echo JHtml::_($addPanel, $icPanFirst, $PanelOne_Tag1, $PanelOne_Tag2); ?>
							<div class="icpanel iCleft">
								<h2><?php echo empty($this->item->id) ? JText::_('COM_ICAGENDA_CUSTOMFIELD_LEGEND_NEW') : JText::sprintf('COM_ICAGENDA_CUSTOMFIELD_LEGEND_EDIT', $this->item->id); ?></h2>
								<hr>
								<h3><?php echo JText::_('COM_ICAGENDA_CUSTOMFIELD_FORM_AND_TYPE_LABEL'); ?></h3>
								<div class="row-fluid">
									<div class="span6 iCleft">
										<div class="control-group">
											<div class="control-label">
												<?php echo $this->form->getLabel('parent_form'); ?>
											</div>
											<div class="controls">
												<?php echo $this->form->getInput('parent_form'); ?>
											</div>
										</div>
									</div>
									<div class="span6 iCleft">
										<div class="control-group">
											<div class="control-label">
												<?php echo $this->form->getLabel('type'); ?>
											</div>
											<div class="controls">
												<?php echo $this->form->getInput('type'); ?>
											</div>
										</div>
									</div>
								</div>
								<hr>
								<div class="row-fluid">
									<div class="span6 iCleft">
										<?php echo $this->form->renderField('spacer_settings'); ?>
										<?php echo $this->form->renderField('title'); ?>
										<?php echo $this->form->renderField('slug'); ?>
										<?php echo $this->form->renderField('groups'); ?>
									</div>
									<div class="span6 iCleft">
										<?php echo $this->form->renderField('spacer_options'); ?>
										<?php echo $this->form->renderField('placeholder'); ?>
										<?php echo $this->form->renderField('spacer_class'); ?>
										<?php echo $this->form->renderField('options'); ?>
										<?php echo $this->form->renderField('required'); ?>
									</div>
								</div>
								<hr>
							</div>
						<?php echo JHtml::_($endPanel); ?>
						<?php echo JHtml::_($addPanel, $icPanDesc, $PanelTwo_Tag1, $PanelTwo_Tag2); ?>
							<div class="icpanel iCleft">
								<h1><?php echo JText::_('COM_ICAGENDA_LEGEND_DESC'); ?></h1>
								<hr>
								<div class="row-fluid">
									<div class="span12 iCleft">
										<h3><?php echo JText::_('COM_ICAGENDA_CUSTOMFIELD_DESCRIPTION_DESC'); ?></h3>
										<?php echo $this->form->getInput('description'); ?>
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
												<?php echo $this->form->getLabel('alias'); ?>
											</div>
											<div class="controls">
												<?php echo $this->form->getInput('alias'); ?>
											</div>
										</div>
										<div class="control-group">
											<?php echo $this->form->getLabel('created'); ?>
											<div class="controls">
												<?php echo $this->form->getInput('created'); ?>
											</div>
										</div>
										<div class="control-group">
											<?php echo $this->form->getLabel('created_by'); ?>
											<div class="controls">
												<?php echo $this->form->getInput('created_by'); ?>
											</div>
										</div>
										<div class="control-group">
											<?php echo $this->form->getLabel('created_by_alias'); ?>
											<div class="controls">
												<?php echo $this->form->getInput('created_by_alias'); ?>
											</div>
										</div>
										<div class="control-group">
											<?php echo $this->form->getLabel('modified'); ?>
											<div class="controls">
												<?php echo $this->form->getInput('modified'); ?>
											</div>
										</div>
										<div class="control-group">
											<?php echo $this->form->getLabel('modified_by'); ?>
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
							<?php echo $this->form->getLabel('state'); ?>
						</div>
						<div class="controls">
							<?php echo $this->form->getInput('state'); ?>
						</div>
					</div>
					<input type="hidden" name="language" value="*" />
				</div>
			</div>
		</div>
		<input type="hidden" name="task" value="" />
		<?php echo JHtml::_('form.token'); ?>
	</form>

	<?php
	JHtml::_('bootstrap.framework');
	JHtml::_('jquery.framework');

	$js = '
	(function($){
		$(window).load(function(){
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
						var jtext = arr[1] == "NAME"    ? "' . JText::_('COM_ICAGENDA_REGISTRATION_NAME') . '"    :
									arr[1] == "EMAIL"   ? "' . JText::_('COM_ICAGENDA_REGISTRATION_EMAIL') . '"   :
									arr[1] == "PHONE"   ? "' . JText::_('COM_ICAGENDA_REGISTRATION_PHONE') . '"   :
									arr[1] == "DATE"    ? "' . JText::_('COM_ICAGENDA_REGISTRATION_DATE') . '"    :
									arr[1] == "TICKETS" ? "' . JText::_('COM_ICAGENDA_REGISTRATION_TICKETS') . '" :
									$(this).html();
					}
					var html = arr[1] ? "<span class=\"label label-default\" style=\"display: inline; margin-right: 5px;\">' . JText::_('COM_ICAGENDA_LABEL_OVERRIDE') . '</span>&#160;"+jtext : arr[0];
					$(this).html(html);
				}
			);
			$("#jform_type").trigger("liszt:updated");
		}
		});
	})(jQuery);
	';

	JFactory::getDocument()->addScriptDeclaration($js);
}
else
{
	$app->enqueueMessage(JText::_('JERROR_ALERTNOAUTHOR'), 'warning');
	$app->redirect(htmlspecialchars_decode('index.php?option=com_icagenda&view=icagenda'));
}
