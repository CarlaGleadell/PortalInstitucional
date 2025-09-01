<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.8.0 2021-09-17
 *
 * @package     iCagenda.Admin
 * @subpackage  tmpl.mail
 * @link        https://www.icagenda.com
 *
 * @author      Cyril Rezé
 * @copyright   (c) 2012-2024 Cyril Rezé / iCagenda. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 *
 * @since       1.0
 *----------------------------------------------------------------------------
*/

defined('_JEXEC') or die();

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
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

<form action="<?php echo Route::_('index.php?option=com_icagenda&view=mail&layout=edit'); ?>" name="adminForm" method="post" id="mail-form" aria-label="<?php echo Text::_('COM_ICAGENDA_TITLE_MAIL'); ?>"  class="form-validate" enctype="multipart/form-data">

	<div class="ic-edit-header-title d-none d-lg-block">
		<?php echo Text::_('COM_ICAGENDA_TITLE_MAIL'); ?>&nbsp;<span>iCagenda</span>
	</div>
	<br />

	<div class="main-card p-4 row mt-2">
		<div class="col-md-9">
			<fieldset class="adminform">
				<div class="form-group">
					<?php echo $this->form->getLabel('subject'); ?>
					<span class="input-group">
						<?php echo $this->form->getInput('subject'); ?>
					</span>
				</div>
				<div class="form-group">
					<?php echo $this->form->getLabel('message'); ?>
					<?php echo $this->form->getInput('message'); ?>
				</div>
			</fieldset>
			<input type="hidden" name="option" value="com_icagenda" />
			<input type="hidden" name="view" value="icagenda" />
			<input type="hidden" name="task" value="">
			<?php echo HTMLHelper::_('form.token'); ?>
		</div>
		<div class="col-md-3">
			<?php echo $this->form->renderField('eventid'); ?>
			<?php echo $this->form->renderField('date'); ?>
		</div>
	</div>
</form>
