<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.8.5 2022-04-30
 *
 * @package     iCagenda.Admin
 * @link        https://www.icagenda.com
 *
 * @author      Cyril Rezé
 * @copyright   (c) 2012-2024 Cyril Rezé / iCagenda. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 *
 * @since       2.0
 *----------------------------------------------------------------------------
*/

defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');

JHtml::_('bootstrap.tooltip');
JHtml::_('formbehavior.chosen', 'select');
JHtml::_('dropdown.init');
JHtml::_('behavior.modal');
JHtml::_('behavior.multiselect');

$app       = JFactory::getApplication();
$user      = JFactory::getUser();
$userId    = $user->get('id');
$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn  = $this->escape($this->state->get('list.direction'));
$saveOrder = $listOrder == 'a.ordering';

$dateFormat    = $this->params->get('date_format_global', 'Y - m - d');
$dateSeparator = $this->params->get('date_separator', ' ');
$timeFormat    = ($this->params->get('timeformat', '1') == 1) ? 'H:i' : 'h:i A';

if ($saveOrder)
{
	$saveOrderingUrl = 'index.php?option=com_icagenda&task=registrations.saveOrderAjax&tmpl=component';
	JHtml::_('sortablelist.sortable', 'registrationsList', 'adminForm', strtolower($listDirn), $saveOrderingUrl);
}
?>

<form action="<?php echo JRoute::_('index.php?option=com_icagenda&view=registrations'); ?>" method="post" name="adminForm" id="adminForm">
<?php if (!empty( $this->sidebar)) : ?>
	<div id="j-sidebar-container" class="span2">
		<?php echo $this->sidebar; ?>
	</div>
	<div id="j-main-container" class="span10">
<?php else : ?>
	<div id="j-main-container">
<?php endif;?>

		<div class="alert alert-info">
			<span class="icon-lamp"></span><strong><?php echo JText::_('IC_TIPS'); ?></strong><br /><?php echo JText::_('COM_ICAGENDA_REGISTRATIONS_TIPS'); ?>
		</div>
		<div id="filter-bar" class="btn-toolbar">
			<div class="filter-search btn-group pull-left">
				<label for="filter_search" class="element-invisible"><?php echo JText::_('JSEARCH_FILTER'); ?></label>
				<input class="tip hasTooltip" type="text" name="filter_search" placeholder="<?php echo JText::_('JSEARCH_FILTER'); ?>" id="filter_search" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" title="<?php echo JText::_('JSEARCH_FILTER'); ?>" />
			</div>
			<div class="btn-group pull-left">
				<button class="btn tip hasTooltip" type="submit" title="<?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?>"><i class="icon-search"></i></button>
				<button class="btn tip hasTooltip" type="button" onclick="document.id('filter_search').value='';this.form.submit();" title="<?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?>"><i class="icon-remove"></i></button>
			</div>
			<div class="btn-group pull-right hidden-phone">
				<label for="limit" class="element-invisible"><?php echo JText::_('JFIELD_PLG_SEARCH_SEARCHLIMIT_DESC'); ?></label>
				<?php echo $this->pagination->getLimitBox(); ?>
			</div>
		</div>
		<div class="alert">
			<?php echo JText::_('COM_ICAGENDA_REGISTRATIONS_FILTER_SEARCH_DESC'); ?>
		</div>
		<div class="clearfix"></div>

		<?php if (empty($this->items)) : ?>
			<div class="alert alert-no-items">
				<?php echo JText::_('JGLOBAL_NO_MATCHING_RESULTS'); ?>
			</div>
		<?php else : ?>
			<table class="table table-striped" id="registrationList">
				<thead>
					<tr>
						<?php // Ordering HEADER ?>
 						<th width="1%" class="nowrap center hidden-phone">
							<?php echo JHtml::_('grid.sort', '<i class="icon-menu-2"></i>', 'a.ordering', $listDirn, $listOrder, null, 'asc', 'JGRID_HEADING_ORDERING'); ?>
						</th>

						<?php // CheckBox HEADER ?>
						<th width="1%" class="hidden-phone">
							<input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
						</th>

						<?php // Registration State HEADER ?>
						<th width="1%" style="min-width:55px" class="nowrap center">
							<?php echo JHtml::_('grid.sort', 'COM_ICAGENDA_REGISTRATION_STATE', 'a.status', $listDirn, $listOrder); ?>
						</th>

						<?php // User HEADER ?>
						<th>
							<?php echo JText::_('COM_ICAGENDA_REGISTRATION_INFORMATION'); ?><span class="hidden-phone">:</span><span class="visible-phone"></span>
							<?php echo JHtml::_('grid.sort',  'IC_NAME', 'name', $listDirn, $listOrder); ?>&nbsp;|
							<?php echo JHtml::_('grid.sort',  'COM_ICAGENDA_REGISTRATION_USER_ID', 'userid', $listDirn, $listOrder); ?>&nbsp;|
							<?php echo JHtml::_('grid.sort',  'COM_ICAGENDA_REGISTRATION_EMAIL', 'email', $listDirn, $listOrder); ?>&nbsp;|
							<?php echo JHtml::_('grid.sort',  'COM_ICAGENDA_REGISTRATION_PHONE', 'phone', $listDirn, $listOrder); ?>&nbsp;|
							<?php echo JHtml::_('grid.sort',  'COM_ICAGENDA_REGISTRATION_NUMBER_PLACES', 'a.people', $listDirn, $listOrder); ?>&nbsp;-
							<?php echo JHtml::_('grid.sort',  'COM_ICAGENDA_REGISTRATION_EVENTID', 'event', $listDirn, $listOrder); ?>&nbsp;|
							<?php echo JHtml::_('grid.sort',  'ICDATE', 'a.date', $listDirn, $listOrder); ?>&nbsp;|
							<?php echo JHtml::_('grid.sort',  'JGLOBAL_FIELD_CREATED_BY_LABEL', 'evt_created_by', $listDirn, $listOrder); ?>
						</th>

						<?php // Status HEADER ?>
						<th width="1%" style="min-width:55px" class="nowrap center hidden-phone">
							<?php echo JHtml::_('grid.sort', 'JSTATUS', 'a.state', $listDirn, $listOrder); ?>
						</th>

						<?php // *** ID HEADER *** ?>
						<th width="1%" class="nowrap hidden-phone">
							<?php echo JHtml::_('grid.sort', 'JGRID_HEADING_ID', 'a.id', $listDirn, $listOrder); ?>
						</th>

					</tr>
				</thead>
				<tfoot>
					<tr>
						<td colspan="5">
							<?php echo $this->pagination->getListFooter(); ?>
						</td>
					</tr>
				</tfoot>
				<tbody valign="top">
				<?php foreach ($this->items as $i => $item) :
					$ordering		= ($listOrder == 'a.ordering');
					$canCreate		= $user->authorise('core.create', 'com_icagenda');
					$canEdit		= $user->authorise('core.edit', 'com_icagenda');
					$canCheckin		= $user->authorise('core.manage', 'com_icagenda') || $item->checked_out == $userId || $item->checked_out == 0;
					$canChange		= $user->authorise('core.edit.state', 'com_icagenda') && $canCheckin;
					$canEditOwn		= $user->authorise('core.edit.own', 'com_icagenda') && $item->userid == $userId;

					// Get participant privacy consents
					$user_actions          = isset($item->user_action) ? explode(',', $item->user_action) : array();
					$user_actions_datetime = isset($item->user_action_datetime) ? explode(',', $item->user_action_datetime) : array();

					// Get Gravatar consent
					if (in_array('consent_gravatar', $user_actions))
					{
						$avatar             = md5(strtolower(trim($item->email)));
						$participant_avatar = '<img alt="' . $item->name . '"  src="https://www.gravatar.com/avatar/' . $avatar . '?s=36&d=mm"/><br /><span class="small iC-italic-grey"><small>Gravatar</small></span>';
					}
					else
					{
						$participant_avatar = '<div style="display: block; height:36px; width: 36px; font-size: 36px; background: #fff;"><span class="iCicon-avatar ic-avatar"></span></div>';
					}

					// Get Username and name
					$data_name     = ($item->userid) ? $item->fullname : $item->name;
					$data_username = ($item->userid) ? $item->username : false;

					// Load Custom fields DATA
					$customfields	= icagendaCustomfields::getListNotEmpty($item->id, 1);

					$rowClass = ($item->state == '0') ? ' unpublished' : '';
					?>
					<tr class="row<?php echo $i % 2; ?><?php echo $rowClass; ?>">

						<?php // Ordering ?>
						<td class="order nowrap center hidden-phone">
							<?php
							$iconClass = '';
							if (!$canChange)
							{
								$iconClass = ' inactive';
							}
							elseif (!$saveOrder)
							{
								$iconClass = ' inactive tip-top hasTooltip" title="' . JHtml::_('tooltipText', 'JORDERINGDISABLED');
							}
							?>
							<span class="sortable-handler<?php echo $iconClass ?>">
								<span class="icon-menu" aria-hidden="true"></span>
							</span>
							<?php if ($canChange && $saveOrder) : ?>
								<input type="text" style="display:none" name="order[]" size="5" value="<?php echo $item->ordering; ?>" class="width-20 text-area-order" />
							<?php endif; ?>
						</td>

						<?php // CheckBox ?>
						<td class="center hidden-phone">
							<?php echo JHtml::_('grid.id', $i, $item->id); ?>
						</td>

 						<?php // *** Status *** ?>
				    	<td class="center">
				    		<?php
							$reg_label =  array(
								'-2' => 'default',
								'-1' => 'important',
								'0'  => 'important',
								'1'  => 'success',
								'2'  => 'warning',
							);
							$reg_status =  array(
								'-2' => JText::_('IC_REJECTED'),
//								'-2' => JText::_('IC_ABANDONED'),
								'-1' => JText::_('IC_ERROR'),
								'0'  => JText::_('IC_CANCELLED'),
								'1'  => JText::_('IC_COMPLETED'),
								'2'  => JText::_('IC_PENDING'),
							);

				    		//$user_actions_array = array_combine($user_actions, $user_actions_datetime);				    		
				    		?>
					    	<span class="label label-<?php echo $reg_label[$item->status]; ?>">
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

 						<?php // *** User Information *** ?>
						<td class="has-context">
							<div class="pull-left hidden-phone" style="margin-right:10px;">
								<?php echo $participant_avatar; ?>
							</div>
							<div class="pull-left" style="width:45%">
								<?php if ($item->checked_out) : ?>
									<?php echo JHtml::_('jgrid.checkedout', $i, $item->username, $item->checked_out_time, 'registrations.', $canCheckin); ?>
								<?php endif; ?>
								<?php //if ($item->language == '*'):?>
									<?php //$language = JText::alt('JALL', 'language'); ?>
								<?php //else:?>
									<?php //$language = $item->language ? $this->escape($item->language) : JText::_('JUNDEFINED'); ?>
								<?php //endif;?>
								<?php //if ($canEdit || $canEditOwn) : ?>
								<!--a href="<?php //echo JRoute::_('index.php?option=com_icagenda&task=registration.edit&id=' . $item->id); ?>" title="<?php //echo JText::_('JACTION_EDIT'); ?>"-->


								<?php if ($data_name) : ?>
									<p class="smallsub">
										<?php echo JText::_('IC_NAME') . ': '; ?>
										<?php //if ($canEdit && $item->evt_state == 1) : ?>
										<?php if ($canEdit || $canEditOwn) : ?>
											<a href="<?php echo JRoute::_('index.php?option=com_icagenda&task=registration.edit&id=' . $item->id); ?>" title="<?php echo JText::_('JACTION_EDIT'); ?>">
												<?php echo '<strong>' . $this->escape($item->name). '</strong>'; ?>
											</a>
										<?php else : ?>
												<?php echo '<strong>' . $this->escape($item->name). '</strong>'; ?>
										<?php endif; ?>
										<?php // Name Visibility Consent. ?>
										<?php if (in_array('consent_name_public', $user_actions)) : ?>
											<br />
											<span class="badge badge-success">
												<?php echo JText::_('COM_ICAGENDA_PRIVACY_PARTICIPANT_NAME_VISIBILITY_LABEL'); ?>: <?php echo JText::_('COM_ICAGENDA_PRIVACY_PUBLIC'); ?>
											</span>
										<?php elseif (in_array('consent_name_users', $user_actions)) : ?>
											<br />
											<span class="badge badge-info">
												<?php echo JText::_('COM_ICAGENDA_PRIVACY_PARTICIPANT_NAME_VISIBILITY_LABEL'); ?>: <?php echo JText::_('COM_ICAGENDA_PRIVACY_USERS'); ?>
											</span>
										<?php //elseif ( ! empty($user_actions)) : ?>
											<!--span class="badge"><?php //echo JText::_('COM_ICAGENDA_PRIVACY_ORGANISER'); ?></span-->
										<?php endif; ?>
									</p>
									<?php if ($data_username) : ?>
										<?php echo '<strong>' . $this->escape($data_username) . '</strong>'; ?>
										<?php echo '<small>[' . $this->escape($data_name) . ']</small>'; ?>
									<?php endif; ?>

									<!--/a-->
									<?php //else : ?>
										<!--span title="<?php //echo JText::sprintf('JFIELD_ALIAS_LABEL', $this->escape($item->alias)); ?>"--><?php //echo $this->escape($item->name); ?><!--/span-->
									<?php //endif; ?>
									<?php if ($item->userid != '0') : ?>
										<p class="smallsub">
											<?php echo JText::_('COM_ICAGENDA_REGISTRATION_USER_ID') . ": " . $this->escape($item->userid); ?>
										</p>
									<?php else:?>
										<p class="smallsub">
											<?php echo JText::_('COM_ICAGENDA_REGISTRATION_NO_USER_ID'); ?>
										</p>
									<?php endif; ?>
									<?php if (($item->email) OR ($item->phone)) : ?>
										<!--div class="small" style="height:5px; border-bottom: solid 1px #D4D4D4">
										</div-->
										<p>
										<?php if ($item->email) : ?>
											<div class="small iC-italic-grey">
												<?php echo JText::_('COM_ICAGENDA_REGISTRATION_EMAIL') . ": <b>" . $this->escape($item->email) . "</b>"; ?>
											</div>
										<?php endif; ?>
										<?php if ($item->phone) : ?>
											<div class="small iC-italic-grey">
												<?php echo JText::_('COM_ICAGENDA_REGISTRATION_PHONE') . ": <b>" . $this->escape($item->phone) . "</b>"; ?>
											</div>
										<?php endif; ?>
										</p>
									<?php endif; ?>
								<?php endif; ?>

								<?php if ($item->notes) : ?>
									<br />
									<a href="#loadDiv<?php echo $item->id; ?>" class="modal" rel="{size: {x: 600, y: 350}}">
										<input type="submit" class="btn" value="<?php echo JText::_( 'COM_ICAGENDA_REGISTRATION_NOTES_DISPLAY_LABEL' ); ?>" />
									</a>
									<div style="display:none;">
										<div id="loadDiv<?php echo $item->id; ?>">
											<?php echo "<h3>".JText::_('COM_ICAGENDA_REGISTRATION_NOTES_DISPLAY_LABEL') . ": </h3><hr>" . nl2br(html_entity_decode($item->notes)); ?>
										</div>
									</div>
								<?php endif; ?>

 								<?php // Custom Fields ?>
 								<?php if ($customfields) : ?>
									<?php foreach ($customfields AS $customfield) : ?>
										<?php $cf_value = isset($customfield->cf_value) ? $customfield->cf_value : JText::_('IC_NOT_SPECIFIED'); ?>
										<div class="small iC-italic-grey">
											<?php echo JText::_($customfield->cf_title) . ': <strong>' . JText::_($cf_value) . '</strong>'; ?>
										</div>
									<?php endforeach; ?>
								<?php endif; ?>

								<p class="smallsub">
									<?php if (in_array('consent_organiser', $user_actions)) : ?>
										<span class="badge">&#10003; <?php echo JText::_('COM_ICAGENDA_REGISTRATION_CONSENT_ORGANISER_LABEL'); ?></span>
									<?php endif; ?>
									<?php if (in_array('consent_terms', $user_actions)) : ?>
										<span class="badge">&#10003; <?php echo JText::_('COM_ICAGENDA_REGISTRATION_TERMS_LABEL'); ?></span>
									<?php endif; ?>
								</p>


							</div>
							<div class="pull-right visible-phone" style="margin-right:5%;">
								<?php echo $participant_avatar; ?>
							</div>
							<div class="pull-left" style="width:50%">
								<?php if ( $item->evt_state != 1) : ?>
									<div class="small">
										<div style="font-weight:bold; background:#c30000; color:#FFFFFF; padding: 2px 5px; border-radius: 5px;">
											<?php echo JText::_( 'COM_ICAGENDA_REGISTRATION_EVENT_NOT_PUBLISHED' ); ?>
										</div>
									</div>
								<?php endif; ?>
								<div class="small">
									<?php echo JText::_('ICEVENT'); ?>
								</div>
								<div class="small iC-italic-grey">
									<?php echo JText::_('ICTITLE') . ': <strong>' . $this->escape($item->event) . '</strong>'; ?>
								</div>
								<div class="small iC-italic-grey">
									<?php if (( ! $item->date && $item->period == 0) || ($item->period == 1)) : ?>
										<?php echo JText::_('ICDATES') . ': '; ?>
									<?php else : ?>
										<?php echo JText::_('ICDATE') . ': '; ?>
									<?php endif; ?>
									<strong>
									<?php if ( ! $item->date && $item->period == 0) : ?>
										<?php // echo JText::_( 'COM_ICAGENDA_ADMIN_REGISTRATION_FOR_ALL_PERIOD' ); ?>
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
										<?php echo JText::_( 'COM_ICAGENDA_ADMIN_REGISTRATION_FOR_ALL_DATES' ); ?>
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
									$db = JFactory::getDBO();
									$db->setQuery(
										'SELECT name' .
										' FROM #__users' .
										' WHERE id = '. (int) $item->evt_created_by
									);
									$authorname = $db->loadObject()->name;
 								?>
								<div class="small iC-italic-grey">
									<?php echo JText::_('JGLOBAL_FIELD_CREATED_BY_LABEL') . ': <strong>' . $this->escape($authorname) . '</strong>'; ?>
								</div>
								<?php endif; ?>
								<p>
								<div class="small">
									<?php echo JText::_('ICINFORMATION'); ?>
								</div>
								<div class="small iC-italic-grey">
									<?php echo JText::_('COM_ICAGENDA_REGISTRATION_NUMBER_PLACES') . ': <strong>' . $item->people . '</strong>'; ?>
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

 						<?php // Published state ?>
				    	<td class="center hidden-phone">
               				<?php //if (isset($this->items[0]->state)) : ?>
					    		<?php echo JHtml::_('jgrid.published', $item->state, $i, 'registrations.', $canChange, 'cb'); ?>
                			<?php //endif; ?>
				    	</td>

						<?php // *** ID *** ?>
						<td class="center hidden-phone">
							<?php if (isset($this->items[0]->id)) : ?>
								<?php echo (int) $item->id; ?>
							<?php endif; ?>
						</td>

					</tr>
				<?php endforeach; ?>

				</tbody>
			</table>
			<?php // Load the export form ?>
			<?php echo JHtml::_(
				'bootstrap.renderModal',
				'downloadModal',
				array(
					'title'       => JText::_('JTOOLBAR_EXPORT'),
					'url'         => JRoute::_('index.php?option=com_icagenda&amp;view=download&amp;tmpl=component'),
					'height'      => '900px',
					'width'       => '300px',
					'bodyHeight'  => '70',
					'modalWidth'  => '40',
					'footer'      => '<a class="btn" data-dismiss="modal" type="button"'
									. ' onclick="jQuery(\'#downloadModal iframe\').contents().find(\'#closeBtn\').click();">'
									. JText::_('COM_ICAGENDA_CANCEL') . '</a>'
									. '<button class="btn btn-success" type="button"'
									. ' onclick="jQuery(\'#downloadModal iframe\').contents().find(\'#exportBtn\').click();">'
									. JText::_('COM_ICAGENDA_REGISTRATIONS_EXPORT') . '</button>',
				)
			); ?>
		<?php endif;?>

		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
		<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>
