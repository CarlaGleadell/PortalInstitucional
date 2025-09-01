<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.9.0 2024-02-25
 *
 * @package     iCagenda.Site
 * @subpackage  Layouts.joomla
 * @link        https://www.icagenda.com
 *
 * @author      Cyril Rezé
 * @copyright   (c) 2012-2024 Cyril Rezé / iCagenda. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 *
 * @since       3.8
 *----------------------------------------------------------------------------
*/

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text as JText;

extract($displayData);

/**
 * Layout variables
 * -----------------
 * @var   JForm   $tmpl             The Empty form for template
 * @var   array   $forms            Array of JForm instances for render the rows
 * @var   bool    $multiple         The multiple state for the form field
 * @var   int     $min              Count of minimum repeating in multiple mode
 * @var   int     $max              Count of maximum repeating in multiple mode
 * @var   string  $name             Name of the input field. (J4)
 * @var   string  $fieldname        The field name
 * @var   string  $fieldId          The field ID (J4)
 * @var   string  $control          The forms control
 * @var   string  $label            The field label
 * @var   string  $description      The field description
 * @var   array   $buttons          Array of the buttons that will be rendered
 * @var   bool    $groupByFieldset  Whether group the subform fields by it`s fieldset
 */

// Add script
if ($multiple)
{
	if (version_compare(JVERSION, '4.0', 'lt'))
	{
		JHtml::_('jquery.ui', array('core', 'sortable'));
		JHtml::_('script', 'system/subform-repeatable.js', array('version' => 'auto', 'relative' => true));
	}
	else
	{
		Factory::getDocument()->getWebAssetManager()
			->useScript('webcomponent.field-subform');
	}
}

$sublayout = empty($groupByFieldset) ? 'section' : 'section-byfieldsets';

$unique_subform_id = (isset($unique_subform_id)) ? $unique_subform_id : '';
?>

<?php if (version_compare(JVERSION, '4.0', 'lt')) : ?>

<div class="row-fluid">
	<div class="subform-repeatable-wrapper subform-layout">
		<div class="subform-repeatable"
			data-bt-add="a.group-add-<?php echo $unique_subform_id; ?>"
			data-bt-remove="a.group-remove-<?php echo $unique_subform_id; ?>"
			data-bt-move="a.group-move-<?php echo $unique_subform_id; ?>"
			data-repeatable-element="div.subform-repeatable-group-<?php echo $unique_subform_id; ?>"
			data-minimum="<?php echo $min; ?>" data-maximum="<?php echo $max; ?>"
		>

		<?php
		foreach ($forms as $k => $form) :
			echo $this->sublayout(
				$sublayout,
				array(
					'form' => $form,
					'basegroup' => $fieldname,
					'group' => $fieldname . $k,
					'buttons' => $buttons,
					'unique_subform_id' => $unique_subform_id,
				)
			);
		endforeach;
		?>
		<?php if ($multiple) : ?>
			<?php if ( ! $unique_subform_id) : ?>
			<script type="text/subform-repeatable-template-section" class="subform-repeatable-template-section">
				<?php echo $this->sublayout($sublayout, array('form' => $tmpl, 'basegroup' => $fieldname, 'group' => $fieldname . 'X', 'buttons' => $buttons)); ?>
			</script>
			<?php else : ?>
			<template class="subform-repeatable-template-section" style="display: none;"><?php
				// Use str_replace to escape HTML in a simple way, it need for IE compatibility, and should be removed later
				echo str_replace(
						array('<', '>'),
						array('SUBFORMLT', 'SUBFORMGT'),
						trim(
							$this->sublayout(
								$sublayout,
								array(
									'form' => $tmpl,
									'basegroup' => $fieldname,
									'group' => $fieldname . 'X',
									'buttons' => $buttons,
									'unique_subform_id' => $unique_subform_id,
								)
							)
						)
				);
				?></template>
			<?php endif; ?>
		<?php endif; ?>
			<div class="subform-repeatable-group-<?php echo $unique_subform_id; ?>"
				data-base-name="dates"
				data-group="<?php echo $group; ?>"
			>
				<a class="controls btn btn-small button btn-success group-add group-add-<?php echo $unique_subform_id; ?>">
					<span class="icon-plus" aria-hidden="true"></span> <strong><?php echo JText::_('JGLOBAL_FIELD_ADD'); ?></strong>
				</a>
			</div>
			<br />
		</div>
	</div>
</div>

<?php else : ?>

<div class="subform-repeatable-wrapper subform-layout">
	<joomla-field-subform class="subform-repeatable" name="<?php echo $name; ?>"
		button-add=".group-add" button-remove=".group-remove" button-move="<?php echo empty($buttons['move']) ? '' : '.group-move' ?>"
		repeatable-element=".subform-repeatable-group" minimum="<?php echo $min; ?>" maximum="<?php echo $max; ?>">
		<?php if (!empty($buttons['add'])) : ?>
		<div class="btn-toolbar">
			<div class="btn-group">
				<button type="button" class="group-add btn btn-sm button btn-success">
					<span class="icon-plus icon-white" aria-hidden="true"></span> <?php echo JText::_('JGLOBAL_FIELD_ADD'); ?>
				</button>
			</div>
		</div>
		<?php endif; ?>
	<?php
	foreach ($forms as $k => $form) :
		echo $this->sublayout($sublayout, array('form' => $form, 'basegroup' => $fieldname, 'group' => $fieldname . $k, 'buttons' => $buttons));
	endforeach;
	?>
	<?php if ($multiple) : ?>
	<template class="subform-repeatable-template-section hidden"><?php
		echo trim($this->sublayout($sublayout, array('form' => $tmpl, 'basegroup' => $fieldname, 'group' => $fieldname . 'X', 'buttons' => $buttons)));
	?></template>
	<?php endif; ?>
	</joomla-field-subform>
</div>

<?php endif; ?>
