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

use Joomla\CMS\Factory as JFactory;
use Joomla\CMS\Language\Text as JText;

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

<div
	class="subform-repeatable-group subform-repeatable-group-<?php echo $unique_subform_id; ?>"
	data-base-name="<?php echo $basegroup; ?>"
	data-group="<?php echo $group; ?>"
>
	<?php if (version_compare(JVERSION, '4.0', 'lt')) : ?>
	<div class="ic-float-right">
		<a class="btn btn-mini button btn-danger group-remove group-remove-<?php echo $unique_subform_id; ?>">
			<span class="icon-cancel" aria-hidden="true"></span> <?php echo JText::_('JGLOBAL_FIELD_REMOVE'); ?>
		</a>
	</div>
	<?php endif; ?>

<?php foreach ($form->getGroup('') as $field) : ?>
	<?php if ($field->type == 'Calendar') : ?>
		<?php
		$ampm = (JFactory::getApplication()->getParams()->get('timeformat', 1) == 1) ? '24' : '12';
		$field->timeformat = $ampm;
		?>
	<?php endif; ?>

	<?php if (version_compare(JVERSION, '4.0', 'lt')) : ?>
	<?php echo $field->renderField(); ?>
	<?php else : ?>
	<div class="row">
		<div class="col-8 col-lg-10">
			<?php echo $field->renderField(); ?>
		</div>
		<div class="col-4 col-lg-2 ic-text-right">
			<div class="control-label">
				<label> </label>
			</div>
			<div class="control-group">
				<a class="btn btn-mini button btn-danger group-remove group-remove-<?php echo $unique_subform_id; ?>">
					<span class="icon-cancel" aria-hidden="true"></span> <?php echo JText::_('JGLOBAL_FIELD_REMOVE'); ?>
				</a>
			</div>
		</div>
	</div>
	<?php endif; ?>
<?php endforeach; ?>
</div>
