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
 * @since       3.5.0
 *----------------------------------------------------------------------------
*/

defined('_JEXEC') or die;

JHtml::_('bootstrap.tooltip', '.hasTooltip', array('placement' => 'bottom'));
?>

<div class="container-popup">
	<form
		class="form-horizontal form-validate"
		id="download-form"
		name="adminForm"
		action="<?php echo JRoute::_('index.php?option=com_icagenda&task=registrations.display&format=raw'); ?>"
		method="post">

		<?php foreach ($this->form->getFieldset() as $field) : ?>
			<?php echo $this->form->renderField($field->fieldname); ?>
		<?php endforeach; ?>

		<button class="hidden"
			id="closeBtn"
			type="button"
			onclick="window.parent.jQuery('#modal-download').modal('hide');">
		</button>
		<button class="hidden"
			id="exportBtn"
			type="button"
			onclick="this.form.submit();window.top.setTimeout('window.parent.jQuery(\'#downloadModal\').modal(\'hide\')', 700);">
		</button>
	</form>
</div>
