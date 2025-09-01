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
 * @since       1.0.0
 *----------------------------------------------------------------------------
*/

defined('_JEXEC') or die;

JHtml::_('behavior.tooltip');
JHtml::_('behavior.keepalive');
JHtml::_('behavior.formvalidation');
JHtml::_('formbehavior.chosen', 'select');

$app = JFactory::getApplication();

// Access Administration Newsletter check.
if (JFactory::getUser()->authorise('icagenda.access.newsletter', 'com_icagenda'))
{
	?>
	<form action="<?php echo JRoute::_('index.php?option=com_icagenda&view=mail&layout=edit') ?>" method="post" name="adminForm" id="adminForm" class="form-validate" enctype="multipart/form-data">
		<div class="container">
			<header>
				<h1>
					<?php echo JText::_('COM_ICAGENDA_TITLE_MAIL'); ?>&nbsp;<span>iCagenda</span>
				</h1>
				<h2>
					<?php echo JText::_('COM_ICAGENDA_COMPONENT_DESC'); ?>
				</h2>
			</header>
			<div>&nbsp;</div>
			<h4><?php echo JText::_('COM_ICAGENDA_FORM_LBL_NEWSLETTER_LIST'); ?></h4>
			<div class="row-fluid">
				<div class="span12">
					<div class="span4 iCleft">
						<div class="control-group">
							<?php echo $this->form->getLabel('eventid'); ?>
							<div class="controls">
								<?php echo $this->form->getInput('eventid'); ?>
							</div>
						</div>
					</div>
					<div class="span4 iCleft">
						<div class="control-group">
							<?php echo $this->form->getLabel('date'); ?>
							<div class="controls">
								<?php echo $this->form->getInput('date'); ?>
							</div>
						</div>
					</div>
				</div>
			</div>
			<hr>
			<h4><?php echo JText::_('COM_ICAGENDA_TITLE_NEWSLETTER'); ?></h4>
			<div class="row-fluid">
				<div class="span12">
					<div class="control-group">
						<?php echo $this->form->getLabel('subject'); ?>
						<div class="controls">
							<?php echo $this->form->getInput('subject'); ?>
						</div>
					</div>
					<div class="control-group">
						<?php echo $this->form->getLabel('message'); ?>
						<div class="controls">
							<?php echo $this->form->getInput('message'); ?>
						</div>
					</div>
				</div>
			</div>
			<input type="hidden" name="option" value="com_icagenda" />
			<input type="hidden" name="task" value="" />
			<?php echo JHtml::_('form.token'); ?>
		</div>
		<div class="clr"></div>
	</form>
	<?php
}
else
{
	$app->enqueueMessage(JText::_('JERROR_ALERTNOAUTHOR'), 'warning');
	$app->redirect(htmlspecialchars_decode('index.php?option=com_icagenda&view=icagenda'));
}
