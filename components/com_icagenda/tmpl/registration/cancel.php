<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.9.2 2024-03-20
 *
 * @package     iCagenda.Site
 * @subpackage  tmpl.registration
 * @link        https://www.icagenda.com
 *
 * @author      Cyril Rezé
 * @copyright   (c) 2012-2024 Cyril Rezé / iCagenda. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 *
 * @since       3.6.13
 *----------------------------------------------------------------------------
*/

defined('_JEXEC') or die;

use iClib\Date\Date as iCDate;
use iCutilities\Event\Event as icagendaEvent;
use iCutilities\Render\Render as icagendaRender;
use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Router\Route;

/** @var Joomla\CMS\WebAsset\WebAssetManager $wa */
$wa = $this->document->getWebAssetManager();
$wa->useScript('keepalive')
	->useScript('form.validate');

$app   = Factory::getApplication();
$input = $app->input;

$date_time_separator = ' - '; // @todo: create custom/globalized option
$start_end_separator = ' &#x279c; '; // @todo: create custom/globalized option

$listURL     = Route::_('index.php?option=com_icagenda&view=events&Itemid=' . $input->getInt('Itemid'));
$cancelURL   = Route::_('index.php?option=com_icagenda&view=registration&layout=cancel&id=' . $input->getInt('id') . '&Itemid=' . $input->getInt('Itemid'));
$sessionDate = iCDate::dateToAlias(Factory::getSession()->get('event_date'), 'Y-m-d-H-i');
$vars        = array('date' => $sessionDate);
$eventURL    = icagendaEvent::url($this->item->id, $this->item->alias, $input->getInt('Itemid'), $vars);

?>
<div id="icagenda" class="ic-registration-cancel<?php echo $this->pageclass_sfx; ?>">

	<?php if ($this->params->get('show_page_heading')) : ?>
		<div class="page-header">
			<h1>
				<?php echo $this->escape($this->params->get('page_heading')); ?>
			</h1>
		</div>
	<?php endif; ?>

	<?php if ($input->get('dates_cancelled') && ! $this->participantEventRegistrations) : ?>

		<div class="ic-registration-cancel-content">
			<?php echo Text::sprintf('COM_ICAGENDA_REGISTRATION_CANCEL_CONFIRMED', $this->item->title); ?>
		</div>
		<br />
		<div class="ic-registration-cancel-buttons">
			<a class="btn" href="<?php echo Route::_(''); ?>">
				<span class="icon-home icon-white"></span>&nbsp;<?php echo Text::_('JERROR_LAYOUT_HOME_PAGE'); ?>
			</a>
			&nbsp;
			<a class="btn btn-primary" href="<?php echo $listURL; ?>">
				<span class="icon-eye icon-white"></span>&nbsp;<?php echo Text::_('COM_ICAGENDA_BUTTON_VIEW_LIST'); ?>
			</a>
			&nbsp;
			<a class="btn btn-info" href="<?php echo $eventURL; ?>">
				<span class="icon-eye icon-white"></span>&nbsp;<?php echo Text::_('COM_ICAGENDA_REGISTRATION_EVENT_LINK'); ?>
			</a>
		</div>
		<br />

	<?php elseif ($input->get('dates_cancelled') && $this->participantEventRegistrations) : ?>
		<div class="ic-registration-cancel-content">
			<p>
				<?php echo Text::sprintf('COM_ICAGENDA_REGISTRATION_CANCEL_CONFIRMED', $this->item->title); ?>
			</p>
		</div>
		<br />
		<div class="ic-registration-cancel-buttons">
			<a class="btn btn-primary" href="<?php echo $listURL; ?>">
				<?php echo Text::_('COM_ICAGENDA_BUTTON_VIEW_LIST'); ?>
			</a>
			<a class="btn btn-info" href="<?php echo $eventURL; ?>">
				<?php echo Text::_('COM_ICAGENDA_REGISTRATION_EVENT_LINK'); ?>
			</a>
			<a class="btn btn-default" href="<?php echo $cancelURL; ?>">
				<?php echo Text::_('COM_ICAGENDA_REGISTRATION_CANCEL_OTHER_DATES_BUTTON'); ?>
			</a>
		</div>
		<br />


	<?php elseif ($this->participantEventRegistrations && $this->item->params->get('reg_cancellation', 0)) : ?>

		<?php // START FORM ?>
		<form class="form-validate form-horizontal well"
			id="icagenda-registration-cancel"
			action="<?php echo Route::_('index.php?option=com_icagenda&task=registration.cancellation'); ?>"
			method="post"
			enctype="multipart/form-data"
			>
			<fieldset>
				<legend><?php echo Text::_('COM_ICAGENDA_REGISTRATION_CANCEL_LEGEND'); ?></legend>
				<?php
					$options       = array();
					$oneRegText    = '';
					$oneRegValue   = '';
					$selectedValue = '';
					$periodValue   = '';
					$allValue      = '';

					$registeredDates = \count($this->participantEventRegistrations);

					foreach ($this->participantEventRegistrations as $k => $v)
					{
						$value = $v->id;

						if (iCDate::isDate($v->date))
						{
							$text = icagendaRender::dateToFormat($v->date);
							$text.= $date_time_separator;
							$text.= icagendaRender::dateToTime($v->date);
						}
						elseif (! $v->date && $v->period == 0)
						{
							$text = strip_tags(icagendaRender::dateToFormat($this->item->startdate));
							$text.= $start_end_separator;
							$text.= strip_tags(icagendaRender::dateToFormat($this->item->enddate));
						}
						elseif (! $v->date && $v->period == 1)
						{
							$text = Text::_('COM_ICAGENDA_REGISTRATION_CANCEL_ALL_DATES');
						}

						$text.= ' (' . $v->name . ')';

						$options[] = array('text' => $text, 'value' => $value);

						// Auto select date if session date already booked.
						if ($sessionDate == iCDate::dateToAlias($v->date, 'Y-m-d-H-i') && iCDate::isDate($v->date))
						{
							$selectedValue = $selectedValue ?: $v->id;
						}
						elseif ( ! $sessionDate && ! $v->date && $v->period == 0)
						{
							$periodValue = $periodValue ?: $v->id;
						}
						elseif ( ! $sessionDate && ! $v->date && $v->period == 1)
						{
							$allValue = $allValue ?: $v->id;
						}

						if ($registeredDates === 1)
						{
							$oneRegText  = $text;
							$oneRegValue = $value;
						}
					}

					$selectedValue = $selectedValue ?: ($periodValue ?: $allValue);

					$data = array(
						'name'          => 'dates_cancelled[]',
						'label'         => Text::_('COM_ICAGENDA_REGISTRATION_CANCEL_SELECT_DATES'),
						'class'         => '',
						'id'            => 'dates_cancelled',
						'multiple'      => true,
						'required'      => true,
						'options'       => $options,
						'autofocus'     => '',
						'onchange'      => '',
						'dataAttribute' => '',
						'readonly'      => '',
						'disabled'      => '',
						'hint'          => '',
						'readonly'      => '',
						'value'         => $selectedValue,
					);
				?>

				<div class="control-group mt-5">
					<?php if ($registeredDates === 1) : ?>
						<input type="hidden" name="dates_cancelled[]" value="<?php echo $oneRegValue; ?>" />
						<?php echo Text::sprintf('COM_ICAGENDA_REGISTRATION_CANCEL_REGISTERED_DATE', '<strong>' . $oneRegText . '</strong>'); ?>
					<?php else : ?>
						<label id="dates_cancelled-lbl" for="dates_cancelled">
							<?php echo Text::_('COM_ICAGENDA_REGISTRATION_CANCEL_SELECT_DATES'); ?>
						</label>
						<?php echo LayoutHelper::render('joomla.form.field.list-fancy-select', $data); ?>
					<?php endif; ?>
				</div>

				<div class="control-group">
					<p>
						<?php if ($registeredDates == 1) : ?>
							<?php echo Text::sprintf('COM_ICAGENDA_REGISTRATION_CANCEL_CONFIRM_WARNING_1', '<strong>' . $this->item->title . '</strong>'); ?>
						<?php else : ?>
							<?php echo Text::sprintf('COM_ICAGENDA_REGISTRATION_CANCEL_CONFIRM_WARNING', '<strong>' . $this->item->title . '</strong>'); ?>
						<?php endif; ?>
					</p>
					<button type="submit" class="btn btn-danger validate">
						<?php echo Text::_('COM_ICAGENDA_REGISTRATION_CANCEL_CONFIRM_BUTTON'); ?>
					</button>
					<a class="btn btn-secondary" href="<?php echo $eventURL; ?>">
						<?php echo Text::_('COM_ICAGENDA_REGISTRATION_CANCEL_DENY_BUTTON'); ?>
					</a>
					<input type="hidden" name="itemID" value="<?php echo $input->getInt('Itemid'); ?>" />
					<input type="hidden" name="eventID" value="<?php echo $input->getInt('id'); ?>" />
					<input type="hidden" name="reg_id" value="<?php echo $input->getInt('reg_id'); ?>" />
					<input type="hidden" name="option" value="com_icagenda" />
					<input type="hidden" name="task" value="registration.cancellation" />
				</div>
			</fieldset>
			<?php echo HTMLHelper::_('form.token'); ?>
		</form>

	<?php elseif (isset($this->cancelled)) : ?>

		<div class="ic-registration-cancel-content">
			<?php echo Text::sprintf('COM_ICAGENDA_REGISTRATION_CANCEL_CONFIRMED', $this->item->title); ?>
		</div>
		<br />
		<div class="ic-registration-cancel-buttons">
			<a class="btn" href="<?php echo Route::_(''); ?>">
				<span class="icon-home icon-white"></span>&nbsp;<?php echo Text::_('JERROR_LAYOUT_HOME_PAGE'); ?>
			</a>
		</div>
		<br />

	<?php else : ?>

		<div class="ic-registration-cancel-content">
			<p class="alert alert-info"><?php echo Text::_('COM_ICAGENDA_REGISTRATION_CANCEL_NONE'); ?></p>
		</div>
		<div class="ic-registration-cancel-buttons">
			<a class="btn" href="<?php echo Route::_(''); ?>">
				<span class="icon-home icon-white"></span>&nbsp;<?php echo Text::_('JERROR_LAYOUT_HOME_PAGE'); ?>
			</a>
		</div>

	<?php endif; ?>

</div>
