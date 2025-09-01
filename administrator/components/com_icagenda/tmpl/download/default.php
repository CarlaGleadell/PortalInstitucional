<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.8.0 2021-09-22
 *
 * @package     iCagenda.Admin
 * @subpackage  tmpl.download
 * @link        https://www.icagenda.com
 *
 * @author      Cyril Rezé
 * @copyright   (c) 2012-2024 Cyril Rezé / iCagenda. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 *
 * @since       3.5
 *----------------------------------------------------------------------------
*/

defined('_JEXEC') or die;

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Router\Route;
use \Joomla\CMS\Session\Session;
?>

<div class="container-popup">
	<form
		class="form-horizontal form-validate"
		id="download-form"
		name="adminForm"
		action="<?php echo Route::_('index.php?option=com_icagenda&task=registrations.display&format=raw&' . Session::getFormToken() . '=1'); ?>"
		method="post">

		<?php foreach ($this->form->getFieldset() as $field) : ?>
			<?php echo $this->form->renderField($field->fieldname); ?>
		<?php endforeach; ?>

		<button class="visually-hidden"
			id="exportBtn"
			type="button"
			onclick="this.form.submit();window.top.setTimeout('window.parent.Joomla.Modal.getCurrent().close()', 700);">
		</button>
		<!--button class="hidden"
			id="closeBtn"
			type="button"
			onclick="window.parent.jQuery('#modal-download').modal('hide');">
		</button-->
		<!--button class="hidden"
			id="exportBtn"
			type="button"
			onclick="this.form.submit();window.top.setTimeout('window.parent.jQuery(\'#downloadModal\').modal(\'hide\')', 700);">
		</button-->
	</form>
</div>
