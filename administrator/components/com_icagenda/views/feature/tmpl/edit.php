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
 * @since       3.4.0
 *----------------------------------------------------------------------------
*/

defined('_JEXEC') or die;

JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');

$app = JFactory::getApplication();
$document = JFactory::getDocument();

// Access Administration Features check.
if (JFactory::getUser()->authorise('icagenda.access.features', 'com_icagenda'))
{
	$PanelOne_Tag    = 'feature';
	$PanelOne_Title  = JText::_('COM_ICAGENDA_TITLE_FEATURE', true);
	$PanelTwo_Tag    = 'desc';
	$PanelTwo_Title  = JText::_('COM_ICAGENDA_LEGEND_DESC', true);
	$PublishingTag   = 'publishing';
	$PublishingTitle = JText::_('JGLOBAL_FIELDSET_PUBLISHING', true);

	JHtml::_('formbehavior.chosen', 'select');
	jimport('joomla.html.html.bootstrap');

	$icPanOne        = 'icTab';
	$icPanTwo        = 'icTab';
	$icPanPublishing = 'icTab';

	$iCmapDisplay   = '1';
	$startPane      = 'bootstrap.startTabSet';
	$addPanel       = 'bootstrap.addTab';
	$endPanel       = 'bootstrap.endTab';
	$endPane        = 'bootstrap.endTabSet';
	$PanelOne_Tag1  = $PanelOne_Tag;
	$PanelOne_Tag2  = $PanelOne_Title;
	$PanelTwo_Tag1  = $PanelTwo_Tag;
	$PanelTwo_Tag2  = $PanelTwo_Title;
	$PublishingTag1 = $PublishingTag;
	$PublishingTag2 = $PublishingTitle;
	?>

	<script type="text/javascript">
		Joomla.submitbutton = function(task)
		{
			if (task == 'feature.cancel' || document.formvalidator.isValid(document.id('feature-form'))) {
				Joomla.submitform(task, document.getElementById('feature-form'));
			}
			else {
				alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED'));?>');
			}
		}
	</script>

	<form action="<?php echo JRoute::_('index.php?option=com_icagenda&layout=edit&id='.(int) $this->item->id); ?>" method="post" name="adminForm" id="feature-form" class="form-validate">
		<div class="container">
			<header>
				<h1>
					<?php echo empty($this->item->id) ? JText::_('COM_ICAGENDA_LEGEND_NEW_FEATURE') : JText::sprintf('COM_ICAGENDA_LEGEND_EDIT_FEATURE', $this->item->id); ?>&nbsp;<span>iCagenda</span>
				</h1>
				<h2>
					<?php echo JText::_('COM_ICAGENDA_COMPONENT_DESC'); ?>
				</h2>
			</header>
			<div>&nbsp;</div>
			<div class="row-fluid">
				<div class="span10 form-horizontal">
					<?php echo JHtml::_($startPane, 'icTab', array('active' => 'feature')); ?>
						<?php echo JHtml::_($addPanel, $icPanOne, $PanelOne_Tag1, $PanelOne_Tag2); ?>
							<div class="icpanel iCleft">
								<h1>
									<?php echo empty($this->item->id) ? JText::_('COM_ICAGENDA_LEGEND_NEW_FEATURE') : JText::sprintf('COM_ICAGENDA_LEGEND_EDIT_FEATURE', $this->item->id); ?>
								</h1>
								<hr>
								<div class="row-fluid">
									<div class="span12 iCleft">
										<div class="control-group">
											<div class="control-label">
												<?php echo $this->form->getLabel('title'); ?>
											</div>
											<div class="controls">
												<?php echo $this->form->getInput('title'); ?>
											</div>
										</div>
									</div>
								</div>
								<div class="row-fluid">
									<div class="span12 iCleft">
										<div class="control-group">
											<div class="control-label">
												<?php echo $this->form->getLabel('icon'); ?>
											</div>
											<div class="controls">
												<?php echo $this->form->getInput('icon'); ?>
											</div>
										</div>
									</div>
								</div>
								<div class="row-fluid">
									<div class="span12 iCleft">
										<div class="control-group">
											<div class="control-label">
												<?php echo $this->form->getLabel('new_icon'); ?>
											</div>
											<div class="controls">
												<?php echo $this->form->getInput('new_icon'); ?>
											</div>
										</div>
									</div>
								</div>
								<div class="row-fluid">
									<div class="span12 iCleft">
										<div class="control-group">
											<div class="control-label">
												<?php echo $this->form->getLabel('icon_alt'); ?>
											</div>
											<div class="controls">
												<?php echo $this->form->getInput('icon_alt'); ?>
											</div>
										</div>
									</div>
								</div>
								<div class="row-fluid">
									<div class="span12 iCleft">
										<div class="control-group">
											<div class="control-label">
												<?php echo $this->form->getLabel('show_filter'); ?>
											</div>
											<div class="controls">
												<?php echo $this->form->getInput('show_filter'); ?>
											</div>
										</div>
									</div>
								</div>
							</div>
						<?php echo JHtml::_($endPanel); ?>
						<?php echo JHtml::_($addPanel, $icPanPublishing, $PublishingTag1, $PublishingTag2); ?>
							<div class="icpanel iCleft">
								<h1>
									<?php echo JText::_('JGLOBAL_FIELDSET_PUBLISHING'); ?>
								</h1>
								<hr>
								<div class="row-fluid">
									<div class="span6 iCleft">
										<div class="control-group">
											<div class="control-label">
												<?php echo $this->form->getLabel('alias'); ?>
											</div>
											<div class="controls">
												<?php echo $this->form->getInput('alias'); ?>
											</div>
										</div>
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
					<h4>
						<?php echo JText::_('COM_ICAGENDA_TITLE_SIDEBAR_DETAILS'); ?>
					</h4>
					<hr>
					<div class="control-group">
						<div class="control-label">
							<?php echo $this->form->getLabel('state'); ?>
						</div>
						<div class="controls">
							<?php echo $this->form->getInput('state'); ?>
						</div>
					</div>
				</div>
				<div class="clr"></div>
			</div>
		</div>
		<input type="hidden" name="task" value="" />
		<?php echo JHtml::_('form.token'); ?>
		<div class="clr"></div>
	</form>

	<?php
	JHtml::_('bootstrap.framework');
	JHtml::_('jquery.framework');
}
else
{
	$app->enqueueMessage(JText::_('JERROR_ALERTNOAUTHOR'), 'warning');
	$app->redirect(htmlspecialchars_decode('index.php?option=com_icagenda&view=icagenda'));
}
