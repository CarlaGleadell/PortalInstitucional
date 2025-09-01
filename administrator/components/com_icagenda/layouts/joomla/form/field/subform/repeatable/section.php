<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.9.0 2024-02-24
 *
 * @package     iCagenda.Admin
 * @subpackage  Layout.joomla
 * @link        https://www.icagenda.com
 *
 * @author      Cyril Rezé
 * @copyright   (c) 2012-2023 Cyril Rezé / iCagenda. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 *
 * @since       3.8
 *----------------------------------------------------------------------------
*/

defined('_JEXEC') or die;

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Language\Text;

extract($displayData);

/**
 * Make thing clear
 *
 * @var JForm   $form       The form instance for render the section
 * @var string  $basegroup  The base group name
 * @var string  $group      Current group name
 * @var array   $buttons    Array of the buttons that will be rendered
 */

$unique_subform_id = (isset($unique_subform_id)) ? $unique_subform_id : '';
?>

<?php if (version_compare(JVERSION, '4.0', 'ge')) : ?>
<div class="subform-repeatable-group" data-base-name="<?php echo $basegroup; ?>" data-group="<?php echo $group; ?>">
	<div class="row form-vertical">
		<?php foreach ($form->getGroup('') as $field) : ?>
			<?php if ($field->type == 'Calendar') : ?>
				<?php
				$ampm = (ComponentHelper::getParams('com_icagenda')->get('timeformat', 1) == 1) ? '24' : '12';
				$field->timeformat = $ampm;
				?>
			<?php endif; ?>
			<div class="col-3">
				<?php echo $field->renderField(); ?>
			</div>
		<?php endforeach; ?>
		<?php if (!empty($buttons)) : ?>
			<div class="col-9 control-group">
				<div class="control-label"><label></label></div>
				<div class="btn-toolbar text-end">
					<div class="btn-group">
						<?php if (!empty($buttons['add'])) : ?>
							<button type="button" class="group-add btn btn-success" aria-label="<?php echo Text::_('JGLOBAL_FIELD_ADD'); ?>">
								<span class="icon-plus icon-white" aria-hidden="true"></span>
							</button>
						<?php endif; ?>
						<?php if (!empty($buttons['remove'])) : ?>
							<button type="button" class="group-remove btn btn-danger" aria-label="<?php echo Text::_('JGLOBAL_FIELD_REMOVE'); ?>">
								<span class="icon-minus icon-white" aria-hidden="true"></span>
							</button>
						<?php endif; ?>
						<?php if (!empty($buttons['move'])) : ?>
							<button type="button" class="group-move btn btn-primary" aria-label="<?php echo Text::_('JGLOBAL_FIELD_MOVE'); ?>">
								<span class="icon-arrows-alt icon-white" aria-hidden="true"></span>
							</button>
						<?php endif; ?>
					</div>
				</div>
			</div>
		<?php endif; ?>
	</div>
</div>
<?php else : ?>
<div
	class="subform-repeatable-group subform-repeatable-group-<?php echo $unique_subform_id; ?>"
	data-base-name="<?php echo $basegroup; ?>"
	data-group="<?php echo $group; ?>"
>
	<?php if (!empty($buttons)) : ?>
	<div class="btn-toolbar text-right">
		<div class="btn-group">
			<?php if (!empty($buttons['add'])) : ?>
				<a class="btn btn-mini button btn-success group-add group-add-<?php echo $unique_subform_id; ?>" aria-label="<?php echo Text::_('JGLOBAL_FIELD_ADD'); ?>">
					<span class="icon-plus" aria-hidden="true"></span>
				</a>
			<?php endif; ?>
			<?php if (!empty($buttons['remove'])) : ?>
				<a class="btn btn-mini button btn-danger group-remove group-remove-<?php echo $unique_subform_id; ?>" aria-label="<?php echo Text::_('JGLOBAL_FIELD_REMOVE'); ?>">
					<span class="icon-minus" aria-hidden="true"></span>
				</a>
			<?php endif; ?>
			<?php if (!empty($buttons['move'])) : ?>
				<a class="btn btn-mini button btn-primary group-move group-move-<?php echo $unique_subform_id; ?>" aria-label="<?php echo Text::_('JGLOBAL_FIELD_MOVE'); ?>">
					<span class="icon-move" aria-hidden="true"></span>
				</a>
			<?php endif; ?>
		</div>
	</div>
	<?php endif; ?>

<?php foreach ($form->getGroup('') as $field) : ?>
	<?php if ($field->type == 'Calendar') : ?>
		<?php
		$ampm = (ComponentHelper::getParams('com_icagenda')->get('timeformat', 1) == 1) ? '24' : '12';
		$field->timeformat = $ampm;
		?>
	<?php endif; ?>
	<?php echo $field->renderField(); ?>
<?php endforeach; ?>
</div>
<?php endif; ?>
