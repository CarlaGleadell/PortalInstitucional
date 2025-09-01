<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.9.2 2024-03-20
 *
 * @package     iCagenda.Site
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

JHtml::_('behavior.keepalive');
JHtml::_('behavior.formvalidator');
JHtml::_('formbehavior.chosen', '#icagenda-registration-cancel select');

$app   = JFactory::getApplication();
$input = $app->input;

$date_time_separator = ' - '; // @todo: create custom/globalized option
$start_end_separator = ' &#x279c; '; // @todo: create custom/globalized option

$listURL     = JRoute::_('index.php?option=com_icagenda&view=list&Itemid=' . $input->getInt('Itemid'));
$cancelURL   = JRoute::_('index.php?option=com_icagenda&view=registration&layout=cancel&id=' . $input->getInt('id') . '&Itemid=' . $input->getInt('Itemid'));
$sessionDate = iCDate::dateToAlias(JFactory::getSession()->get('event_date'), 'Y-m-d-H-i');
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
			<?php echo JText::sprintf('COM_ICAGENDA_REGISTRATION_CANCEL_CONFIRMED', $this->item->title); ?>
		</div>
		<br />
		<div class="ic-registration-cancel-buttons">
			<a class="btn" href="<?php echo JRoute::_(''); ?>">
				<span class="icon-home icon-white"></span>&nbsp;<?php echo JTEXT::_('JERROR_LAYOUT_HOME_PAGE'); ?>
			</a>
			&nbsp;
			<a class="btn btn-primary" href="<?php echo $listURL; ?>">
				<span class="icon-eye icon-white"></span>&nbsp;<?php echo JTEXT::_('COM_ICAGENDA_BUTTON_VIEW_LIST'); ?>
			</a>
			&nbsp;
			<a class="btn btn-info" href="<?php echo $eventURL; ?>">
				<span class="icon-eye icon-white"></span>&nbsp;<?php echo JTEXT::_('COM_ICAGENDA_REGISTRATION_EVENT_LINK'); ?>
			</a>
		</div>
		<br />

	<?php elseif ($input->get('dates_cancelled') && $this->participantEventRegistrations) : ?>
		<div class="ic-registration-cancel-content">
			<p>
				<?php echo JText::sprintf('COM_ICAGENDA_REGISTRATION_CANCEL_CONFIRMED', $this->item->title); ?>
			</p>
		</div>
		<br />
		<div class="ic-registration-cancel-buttons">
			<a class="btn btn-primary" href="<?php echo $listURL; ?>">
				<?php echo JTEXT::_('COM_ICAGENDA_BUTTON_VIEW_LIST'); ?>
			</a>
			<a class="btn btn-info" href="<?php echo $eventURL; ?>">
				<?php echo JTEXT::_('COM_ICAGENDA_REGISTRATION_EVENT_LINK'); ?>
			</a>
			<a class="btn btn-default" href="<?php echo $cancelURL; ?>">
				<?php echo JTEXT::_('COM_ICAGENDA_REGISTRATION_CANCEL_OTHER_DATES_BUTTON'); ?>
			</a>
		</div>
		<br />


	<?php elseif ($this->participantEventRegistrations && $this->item->params->get('reg_cancellation', 0)) : ?>

		<?php // START FORM ?>
		<form class="form-validate form-horizontal well"
			id="icagenda-registration-cancel"
			action="<?php echo JRoute::_('index.php?option=com_icagenda&task=registration.cancel'); ?>"
			method="post"
			enctype="multipart/form-data"
			>
			<fieldset>
				<legend><?php echo JText::_('COM_ICAGENDA_REGISTRATION_CANCEL_LEGEND'); ?></legend>
				<div class="control-group">
					<label id="dates_cancelled-lbl" for="dates_cancelled">
						<?php echo JText::_('COM_ICAGENDA_REGISTRATION_CANCEL_SELECT_DATES'); ?>
					</label>
					<?php
					$options         = '';
					$oneRegText      = '';
					$oneRegValue     = '';
					$registeredDates = \count($this->participantEventRegistrations);
					?>
					<?php foreach ($this->participantEventRegistrations as $key => $value) : ?>
						<?php
						$text = '';
						$selectedValue = '';
						// Auto select date if session date already booked.
						if ($sessionDate == iCDate::dateToAlias($value->date, 'Y-m-d-H-i'))
						{
							$selectedValue = ! $selectedValue ? ' selected="true"' : '';
						}
						?>
						<?php if (iCDate::isDate($value->date)) : ?>
							<?php
							$text.= icagendaRender::dateToFormat($value->date);
							$text.= $date_time_separator;
							$text.= icagendaRender::dateToTime($value->date);
							?>
						<?php elseif ( ! $value->date && $value->period == 0) : ?>
							<?php
							$text.= strip_tags(icagendaRender::dateToFormat($this->item->startdate));
							$text.= $start_end_separator;
							$text.= strip_tags(icagendaRender::dateToFormat($this->item->enddate));
							?>
						<?php elseif ( ! $value->date && $value->period == 1) : ?>
							<?php
							$text.= Jtext::_('COM_ICAGENDA_REGISTRATION_CANCEL_ALL_DATES');
							?>
						<?php endif; ?>
						<?php $text.= ' (' . $v->name . ')'; ?>
						<?php
						if ($registeredDates === 1)
						{
							$oneRegText  = $text;
							$oneRegValue = $value->id;
						}
						else
						{
							$options.= '<option value="' . $value->id . '"' . $selectedValue . '>';
							$options.= $text;
							$options.= '</option>';	
						}
						?>
					<?php endforeach; ?>
					<?php if ($registeredDates === 1) : ?>
						<input type="hidden" name="dates_cancelled[]" value="<?php echo $oneRegValue; ?>" />
						<?php echo JText::sprintf('COM_ICAGENDA_REGISTRATION_CANCEL_REGISTERED_DATE', '<strong>' . $oneRegText . '</strong>'); ?>
					<?php else : ?>
						<select id="dates_cancelled" name="dates_cancelled[]" multiple required aria-required="true" message="missing dates">
							<?php echo $options; ?>
						</select>
					<?php endif; ?>
				</div>
				<div class="control-group">
					<p>
						<?php echo JText::sprintf('COM_ICAGENDA_REGISTRATION_CANCEL_CONFIRM_WARNING', $this->item->title); ?>
					</p>
					<button type="submit" class="btn btn-danger validate">
						<?php echo JText::_('COM_ICAGENDA_REGISTRATION_CANCEL_CONFIRM_BUTTON'); ?>
					</button>
					<a class="btn" href="<?php echo $eventURL; ?>">
						<?php echo JTEXT::_('COM_ICAGENDA_REGISTRATION_CANCEL_DENY_BUTTON'); ?>
					</a>
					<input type="hidden" name="eventID" value="<?php echo $input->getInt('id'); ?>" />
					<input type="hidden" name="reg_id" value="<?php echo $input->getInt('reg_id'); ?>" />
					<input type="hidden" name="option" value="com_icagenda" />
					<input type="hidden" name="task" value="registration.cancel" />
				</div>
			</fieldset>
			<?php echo JHtml::_('form.token'); ?>
		</form>

	<?php elseif (isset($this->cancelled)) : ?>

		<div class="ic-registration-cancel-content">
			<?php echo JText::sprintf('COM_ICAGENDA_REGISTRATION_CANCEL_CONFIRMED', $this->item->title); ?>
		</div>
		<br />
		<div class="ic-registration-cancel-buttons">
			<a class="btn" href="<?php echo JRoute::_(''); ?>">
				<span class="icon-home icon-white"></span>&nbsp;<?php echo JTEXT::_('JERROR_LAYOUT_HOME_PAGE'); ?>
			</a>
		</div>
		<br />

	<?php else : ?>

		<div class="ic-registration-cancel-content">
			<p class="alert alert-info"><?php echo JTEXT::_('COM_ICAGENDA_REGISTRATION_CANCEL_NONE'); ?></p>
		</div>
		<div class="ic-registration-cancel-buttons">
			<a class="btn" href="<?php echo JRoute::_(''); ?>">
				<span class="icon-home icon-white"></span>&nbsp;<?php echo JTEXT::_('JERROR_LAYOUT_HOME_PAGE'); ?>
			</a>
		</div>

	<?php endif; ?>

</div>
