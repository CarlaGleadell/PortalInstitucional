<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.9.9 2025-01-15
 *
 * @package     iCagenda.Admin
 * @subpackage  tmpl.registrations
 * @link        https://www.joomlic.com
 *
 * @author      Cyril Rezé
 * @copyright   (c) 2012-2025 Cyril Rezé / JoomliC. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 *
 * @since       2.0
 *----------------------------------------------------------------------------
*/

defined('_JEXEC') or die;

use iClib\Date\Date as iCDate;
use iClib\Globalize\Globalize as iCGlobalize;
use iCutilities\Customfields\Customfields as icagendaCustomfields;
//use iCutilities\Render\Render as icagendaRender;
use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Session\Session;

HTMLHelper::_('behavior.multiselect');

$app       = Factory::getApplication();
$user      = Factory::getUser();
$userId    = $user->get('id');
$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn  = $this->escape($this->state->get('list.direction'));
$saveOrder = $listOrder == 'a.ordering';

$dateFormat    = $this->params->get('date_format_global', 'Y - m - d');
$dateSeparator = $this->params->get('date_separator', ' ');
$timeFormat    = ($this->params->get('timeformat', '1') == 1) ? 'H:i' : 'h:i A';

if ($saveOrder && ! empty($this->items))
{
	$saveOrderingUrl = 'index.php?option=com_icagenda&task=registrations.saveOrderAjax&tmpl=component&' . Session::getFormToken() . '=1';
	HTMLHelper::_('draggablelist.draggable');
}

// Disable forced table accent background from Atum template to use contextual classes to color tables, table rows or individual cells.
Factory::getDocument()->addStyleDeclaration('.table { --table-accent-bg: none; }');
?>

<form action="<?php echo Route::_('index.php?option=com_icagenda&view=registrations'); ?>" method="post" name="adminForm" id="adminForm">
	<div class="row">
		<div class="col-md-12">
			<div id="j-main-container">
				<div class="alert alert-info">
					<span class="icon-lamp"></span> <strong><?php echo Text::_('IC_TIPS'); ?></strong><br /><?php echo Text::_('COM_ICAGENDA_REGISTRATIONS_TIPS'); ?>
				</div>
				<?php
				// Search tools bar
				echo LayoutHelper::render('joomla.searchtools.default', array('view' => $this));
				?>
				<?php if (empty($this->items)) : ?>
					<div class="alert alert-info">
						<span class="icon-info-circle" aria-hidden="true"></span><span class="visually-hidden"><?php echo Text::_('INFO'); ?></span>
						<?php echo Text::_('JGLOBAL_NO_MATCHING_RESULTS'); ?>
					</div>
				<?php else : ?>
					<table class="table itemList" id="eventList">
						<caption class="visually-hidden">
							<?php echo Text::_('COM_ICAGENDA_REGISTRATIONS_TABLE_CAPTION'); ?>,
							<span id="orderedBy"><?php echo Text::_('JGLOBAL_SORTED_BY'); ?> </span>,
							<span id="filteredBy"><?php echo Text::_('JGLOBAL_FILTERED_BY'); ?></span>
						</caption>
						<thead>
							<tr>
								<td class="w-1 text-center">
									<?php echo HTMLHelper::_('grid.checkall'); ?>
								</td>
								<th scope="col" class="w-1 text-center d-none d-md-table-cell">
									<?php echo HTMLHelper::_('searchtools.sort', '', 'a.ordering', $listDirn, $listOrder, null, 'asc', 'JGRID_HEADING_ORDERING', 'icon-sort'); ?>
								</th>
								<th scope="col" class="w-1 text-center">
									<?php echo HTMLHelper::_('searchtools.sort', 'COM_ICAGENDA_REGISTRATION_STATE', 'a.status', $listDirn, $listOrder); ?>
								</th>
								<th scope="col" style="min-width:100px">
									<?php echo Text::_('COM_ICAGENDA_REGISTRATION_INFORMATION'); ?><span class="d-none d-xl-inline-block">:</span><span class="d-block d-xl-none"></span>
									<?php echo HTMLHelper::_('searchtools.sort', 'IC_NAME', 'name', $listDirn, $listOrder); ?>&nbsp;|&nbsp;
									<?php echo HTMLHelper::_('searchtools.sort', 'COM_ICAGENDA_REGISTRATION_USER_ID', 'userid', $listDirn, $listOrder); ?>&nbsp;|&nbsp;
									<?php echo HTMLHelper::_('searchtools.sort', 'COM_ICAGENDA_REGISTRATION_EMAIL', 'email', $listDirn, $listOrder); ?>&nbsp;|&nbsp;
									<?php echo HTMLHelper::_('searchtools.sort', 'COM_ICAGENDA_REGISTRATION_PHONE', 'phone', $listDirn, $listOrder); ?>&nbsp;|&nbsp;
									<?php echo HTMLHelper::_('searchtools.sort', 'COM_ICAGENDA_REGISTRATION_NUMBER_PLACES', 'a.people', $listDirn, $listOrder); ?>
									<span class="d-none d-xl-inline-block">&nbsp;-&nbsp;</span><span class="d-block d-xl-none"></span>
									<?php echo HTMLHelper::_('searchtools.sort', 'COM_ICAGENDA_REGISTRATION_EVENTID', 'event', $listDirn, $listOrder); ?>&nbsp;|&nbsp;
									<?php echo HTMLHelper::_('searchtools.sort', 'ICDATE', 'a.date', $listDirn, $listOrder); ?>&nbsp;|&nbsp;
									<?php echo HTMLHelper::_('searchtools.sort', 'JGLOBAL_FIELD_CREATED_BY_LABEL', 'evt_created_by', $listDirn, $listOrder); ?>
								</th>
								<th scope="col" class="w-1 text-center d-none d-lg-table-cell">
									<?php echo HTMLHelper::_('searchtools.sort', 'JSTATUS', 'a.state', $listDirn, $listOrder); ?>
								</th>
								<th scope="col" class="w-3 d-none d-lg-table-cell">
									<?php echo HTMLHelper::_('searchtools.sort', 'JGRID_HEADING_ID', 'a.id', $listDirn, $listOrder); ?>
								</th>
							</tr>
						</thead>
						<tbody<?php if ($saveOrder) : ?> class="js-draggable" data-url="<?php echo $saveOrderingUrl; ?>" data-direction="<?php echo strtolower($listDirn); ?>" data-nested="false"<?php endif; ?>>
							<?php foreach ($this->items as $i => $item) : ?>
								<?php
								$canEdit    = $user->authorise('core.edit',       'com_icagenda');
								$canCheckin = $user->authorise('core.manage',     'com_icagenda') || $item->checked_out == $userId || is_null($item->checked_out);
								$canEditOwn = $user->authorise('core.edit.own',   'com_icagenda') && ($item->created_by == $userId || $item->userid == $userId || $item->evt_created_by == $userId);
								$canChange  = $user->authorise('core.edit.state', 'com_icagenda') && $canCheckin;

								// Get participant privacy consents
								$user_actions          = isset($item->user_action) ? explode(',', $item->user_action) : array();
								$user_actions_datetime = isset($item->user_action_datetime) ? explode(',', $item->user_action_datetime) : array();

								// Get Gravatar consent
								if (in_array('consent_gravatar', $user_actions))
								{
									$avatar             = md5(strtolower(trim($item->email)));
									$participant_avatar = '<div class="d-block"><img class="icon-gravatar-img" alt="' . $item->name . '"  src="https://www.gravatar.com/avatar/' . $avatar . '?s=48&d=mm"/><br />'
														. '<span class="small iC-italic-grey"><small>&#10003; Gravatar</small></span></div>';
								}
								else
								{
									$participant_avatar = '<div class="d-block text-center"><div class="icon-no-consent"><span class="iCicon-avatar ic-avatar"></span><span class="no-consent-slash"></span></div></div>';
								}

								// Get Username and name
								$data_name     = ($item->userid) ? $item->fullname : $item->name;
								$data_username = ($item->userid) ? $item->username : false;

								// Load Custom fields DATA
								$customfields	= icagendaCustomfields::getListNotEmpty($item->id, 1);

								$rowClass = ($item->state == '0') ? ' unpublished' : '';
								?>
								<tr class="row<?php echo $i % 2; ?> <?php echo $rowClass; ?>" data-draggable-group="none" item-id="<?php echo $item->id ?>">
									<td class="text-center">
										<?php echo HTMLHelper::_('grid.id', $i, $item->id); ?>
									</td>
									<td class="text-center d-none d-md-table-cell">
										<?php
										$iconClass = '';
										if (!$canChange)
										{
											$iconClass = ' inactive';
										}
										elseif (!$saveOrder)
										{
											$iconClass = ' inactive" title="' . Text::_('JORDERINGDISABLED');
										}
										?>
										<span class="sortable-handler<?php echo $iconClass ?>">
											<span class="icon-ellipsis-v"></span>
										</span>
										<?php if ($canChange && $saveOrder) : ?>
											<input type="text" class="hidden" name="order[]" size="5" value="<?php echo $item->ordering; ?>">
										<?php endif; ?>
									</td>
									<td class="text-center">
										<?php
										$reg_badge =  array(
											'-2' => 'secondary',
											'-1' => 'danger',
											'0'  => 'danger',
											'1'  => 'success',
											'2'  => 'warning',
										);
										$reg_status =  array(
											'-2' => Text::_('IC_REJECTED'),
//											'-2' => Text::_('IC_ABANDONED'),
											'-1' => Text::_('IC_ERROR'),
											'0'  => Text::_('IC_CANCELLED'),
											'1'  => Text::_('IC_COMPLETED'),
											'2'  => Text::_('IC_PENDING'),
										);

										//$user_actions_array = array_combine($user_actions, $user_actions_datetime);
										?>
										<span class="badge bg-<?php echo $reg_badge[$item->status]; ?>">
											<?php echo $reg_status[$item->status]; ?>
										</span>
										<?php //if (isset($user_actions_array['cancel_registration'])) : ?>
											<!--br />
											<small>
												<?php //echo icagendaRender::dateToFormat($user_actions_array['cancel_registration']); ?>
												<?php //echo icagendaRender::dateToTime($user_actions_array['cancel_registration']); ?>
											</small-->
										<?php //endif; ?>
									</td>
									<td>
										<div class="ic-float-left d-none d-lg-block text-center" style="width:8%;">
											<?php echo $participant_avatar; ?>
										</div>
										<div class="ic-float-left" style="width:46%">
											<?php //if ($item->checked_out) : ?>
												<?php //echo JHtml::_('jgrid.checkedout', $i, $item->username, $item->checked_out_time, 'registrations.', $canCheckin); ?>
											<?php //endif; ?>
											<?php if ($data_name) : ?>
												<p class="smallsub">
													<?php echo Text::_('IC_NAME') . ': '; ?>
													<?php if ($canEdit || $canEditOwn) : ?>
														<a href="<?php echo Route::_('index.php?option=com_icagenda&task=registration.edit&id=' . $item->id); ?>" title="<?php echo Text::_('JACTION_EDIT'); ?>">
															<?php echo '<strong>' . $this->escape($item->name). '</strong>'; ?>
														</a>
													<?php else : ?>
														<?php echo '<strong>' . $this->escape($item->name). '</strong>'; ?>
													<?php endif; ?>
													<?php // Name Visibility Consent. ?>
													<?php if (in_array('consent_name_public', $user_actions)) : ?>
														<br />
														<span class="badge bg-success">
															<?php echo Text::_('COM_ICAGENDA_PRIVACY_PARTICIPANT_NAME_VISIBILITY_LABEL'); ?>: <?php echo Text::_('COM_ICAGENDA_PRIVACY_PUBLIC'); ?>
														</span>
													<?php elseif (in_array('consent_name_users', $user_actions)) : ?>
														<br />
														<span class="badge bg-info">
															<?php echo Text::_('COM_ICAGENDA_PRIVACY_PARTICIPANT_NAME_VISIBILITY_LABEL'); ?>: <?php echo Text::_('COM_ICAGENDA_PRIVACY_USERS'); ?>
														</span>
													<?php //elseif ( ! empty($user_actions)) : ?>
														<!--span class="badge bg-secondary"><?php //echo Text::_('COM_ICAGENDA_PRIVACY_ORGANISER'); ?></span-->
													<?php endif; ?>
												</p>
												<?php if ($data_username) : ?>
													<?php echo '<strong>' . $this->escape($data_username) . '</strong>'; ?>
													<?php echo '<small>[' . $this->escape($data_name) . ']</small>'; ?>
												<?php endif; ?>
												<?php if ($item->userid != '0') : ?>
													<p class="smallsub">
														<?php echo Text::_('COM_ICAGENDA_REGISTRATION_USER_ID') . ": " . $this->escape($item->userid); ?>
													</p>
												<?php else:?>
													<p class="smallsub">
														<?php echo Text::_('COM_ICAGENDA_REGISTRATION_NO_USER_ID'); ?>
													</p>
												<?php endif; ?>
												<?php if (($item->email) OR ($item->phone)) : ?>
													<p>
													<?php if ($item->email) : ?>
														<div class="small iC-italic-grey">
															<?php echo Text::_('COM_ICAGENDA_REGISTRATION_EMAIL') . ": <b>" . $this->escape($item->email) . "</b>"; ?>
														</div>
													<?php endif; ?>
													<?php if ($item->phone) : ?>
														<div class="small iC-italic-grey">
															<?php echo Text::_('COM_ICAGENDA_REGISTRATION_PHONE') . ": <b>" . $this->escape($item->phone) . "</b>"; ?>
														</div>
													<?php endif; ?>
													</p>
												<?php endif; ?>
											<?php endif; ?>
											<?php if ($item->notes) : ?>
												<button type="button" data-bs-target="#notes-modal-<?php echo $item->id; ?>" class="btn btn-info btn-sm mb-2" data-bs-toggle="modal" title="<?php echo Text::_('COM_ICAGENDA_REGISTRATION_NOTES_DISPLAY_LABEL'); ?>">
													<?php echo Text::_('COM_ICAGENDA_REGISTRATION_NOTES_DISPLAY_LABEL'); ?>
												</button>
												<?php
												echo HTMLHelper::_(
													'bootstrap.renderModal',
													'notes-modal-' . $item->id,
													[
														'title'  => Text::_('COM_ICAGENDA_REGISTRATION_NOTES_DISPLAY_LABEL'),
														'height' => '500px',
														'width'  => '800px',
														'footer' => '<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">'
																	. Text::_('JTOOLBAR_CLOSE') . '</button>',
													],
													$body = '<div class="contentpane">' . nl2br(html_entity_decode($item->notes)) . '</div>'
												);
												?>
											<?php endif; ?>
											<?php // Custom Fields ?>
 											<?php if ($customfields) : ?>
												<?php foreach ($customfields AS $customfield) : ?>
													<?php $cf_value = isset($customfield->cf_value) ? $customfield->cf_value : Text::_('IC_NOT_SPECIFIED'); ?>
													<div class="small iC-italic-grey">
														<?php echo Text::_($customfield->cf_title) . ': <strong>' . Text::_($cf_value) . '</strong>'; ?>
													</div>
												<?php endforeach; ?>
											<?php endif; ?>
											<p class="smallsub">
												<?php if (in_array('consent_organiser', $user_actions)) : ?>
													<span class="badge bg-secondary">&#10003; <?php echo Text::_('COM_ICAGENDA_REGISTRATION_CONSENT_ORGANISER_LABEL'); ?></span>
												<?php endif; ?>
												<?php if (in_array('consent_terms', $user_actions)) : ?>
													<span class="badge bg-secondary">&#10003; <?php echo Text::_('COM_ICAGENDA_REGISTRATION_TERMS_LABEL'); ?></span>
												<?php endif; ?>
											</p>
										</div>
										<div class="ic-float-right d-block d-lg-none text-center" style="width:8%;">
											<?php echo $participant_avatar; ?>
										</div>
										<div class="ic-float-left" style="width:46%">
											<?php if ($item->evt_state != 1) : ?>
												<div class="small">
													<div style="font-weight:bold; background:#c30000; color:#fff; padding: 2px 5px; border-radius: 5px;">
														<?php echo Text::_('COM_ICAGENDA_REGISTRATION_EVENT_NOT_PUBLISHED'); ?>
													</div>
												</div>
											<?php endif; ?>
											<div class="small">
												<?php echo Text::_('ICEVENT'); ?>
											</div>
											<div class="small iC-italic-grey">
												<?php echo Text::_('ICTITLE') . ': <strong>' . $this->escape($item->event) . '</strong>'; ?>
											</div>
											<div class="small iC-italic-grey">
												<?php if (( ! $item->date && $item->period == 0) || ($item->period == 1)) : ?>
													<?php echo Text::_('ICDATES') . ': '; ?>
												<?php else : ?>
													<?php echo Text::_('ICDATE') . ': '; ?>
												<?php endif; ?>
												<strong>
												<?php if ( ! $item->date && $item->period == 0) : ?>
													<?php if (iCDate::isDate($item->startdate)) : ?>
														<?php $period = iCGlobalize::dateFormat($item->startdate, $dateFormat, $dateSeparator); ?>
														<?php if ($item->displaytime) : ?>
															<?php $period.= ' - ' . date($timeFormat, strtotime($item->startdate)); ?>
														<?php endif; ?>
													<?php else : ?>
														<?php $period = $item->startdate; ?>
													<?php endif; ?>
													<?php if ($item->enddate) $period.= ' > '; ?>
													<?php if (iCDate::isDate($item->enddate)) : ?>
														<?php $period.= iCGlobalize::dateFormat($item->enddate, $dateFormat, $dateSeparator); ?>
														<?php if ($item->displaytime) : ?>
															<?php $period.= ' - ' . date($timeFormat, strtotime($item->enddate)); ?>
														<?php endif; ?>
													<?php else : ?>
														<?php $period.= $item->enddate; ?>
													<?php endif; ?>
													<?php echo $period; ?>
												<?php elseif ( ! $item->date && $item->period == 1) : ?>
													<?php echo Text::_('COM_ICAGENDA_ADMIN_REGISTRATION_FOR_ALL_DATES'); ?>
												<?php else : ?>
													<?php if (iCDate::isDate($item->date)) : ?>
														<?php echo iCGlobalize::dateFormat($item->date, $dateFormat, $dateSeparator); ?>
														<?php if ($item->displaytime) : ?>
															<?php echo ' - ' . date($timeFormat, strtotime($item->date)); ?>
														<?php endif; ?>
													<?php else : ?>
														<?php echo $item->date; ?>
													<?php endif; ?>
												<?php endif; ?>
												</strong>
											</div>
											<?php if ($item->evt_created_by) :
												// Get Author Name
												$db = Factory::getDBO();
												$db->setQuery(
													'SELECT name' .
													' FROM #__users' .
													' WHERE id = '. (int) $item->evt_created_by
												);
												$authorname = $db->loadObject()->name;
 											?>
											<div class="small iC-italic-grey">
												<?php echo Text::_('JGLOBAL_FIELD_CREATED_BY_LABEL') . ': <strong>' . $this->escape($authorname) . '</strong>'; ?>
											</div>
											<?php endif; ?>
											<p>
											<div class="small">
												<?php echo Text::_('ICINFORMATION'); ?>
											</div>
											<div class="small iC-italic-grey">
												<?php echo Text::_('COM_ICAGENDA_REGISTRATION_NUMBER_PLACES') . ': <strong>' . $item->people . '</strong>'; ?>
											</div>
											</p>
											<?php if (isset($item->actions) && \is_array($item->actions)) : ?>
												<?php foreach ($item->actions as $k => $v) : ?>
													<?php
														$action_subject = explode(',', $item->action_subject);
														$action_body    = explode(',', $item->action_body);
														$actionsArray   =  array();
														foreach ($user_actions as $key => $value)
														{
															$actionsArray[$value] = array(
																						'action_subject' => $action_subject[$key],
																						'action_body' => $action_body[$key],
																					);
														}
														$action      = explode('.', $k);
														$layout      = new JLayoutFile($action[1], $basePath = JPATH_PLUGINS . '/icagenda/' . $action[0] . '/layouts');
														$displayData = array('item' => $item, 'params' => $this->params, 'user_actions' => $actionsArray);
														$actionHTML  = $layout->render($displayData);
													?>
													<?php echo $actionHTML; ?>
												<?php endforeach; ?>
											<?php endif; ?>
										</div>
									</td>
									<td class="text-center d-none d-lg-table-cell">
										<?php echo HTMLHelper::_('jgrid.published', $item->state, $i, 'registrations.', $canChange); ?>
									</td>
									<td class="d-none d-lg-table-cell">
										<?php echo (int) $item->id; ?>
									</td>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>

					<?php // load the pagination. ?>
					<?php echo $this->pagination->getListFooter(); ?>

				<?php endif; ?>

				<input type="hidden" name="task" value="" />
				<input type="hidden" name="boxchecked" value="0" />
				<?php echo HTMLHelper::_('form.token'); ?>
			</div>
		</div>
	</div>
</form>
