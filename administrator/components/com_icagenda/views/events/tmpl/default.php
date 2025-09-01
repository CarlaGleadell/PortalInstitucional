<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.9.4 2024-06-16
 *
 * @package     iCagenda.Admin
 * @link        https://www.icagenda.com
 *
 * @author      Cyril Rezé
 * @copyright   (c) 2012-2024 Cyril Rezé / iCagenda. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 *
 * @since       1.0
 *----------------------------------------------------------------------------
*/

defined('_JEXEC') or die;

JHtml::_('behavior.modal');
JHtml::_('behavior.multiselect');

$app       = JFactory::getApplication();
$user      = JFactory::getUser();
$userId    = $user->get('id');
$listOrder = $this->state->get('list.ordering');
$listDirn  = $this->state->get('list.direction');
$canOrder  = $user->authorise('core.edit.state', 'com_icagenda');
$saveOrder = $listOrder == 'e.ordering';

// Include the component HTML helpers.
JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');
JHtml::_('bootstrap.tooltip');
JHtml::_('formbehavior.chosen', 'select');
JHtml::_('dropdown.init');

$archived = $this->state->get('filter.published') == 2 ? true : false;
$trashed  = $this->state->get('filter.published') == -2 ? true : false;

if ($saveOrder)
{
	$saveOrderingUrl = 'index.php?option=com_icagenda&task=events.saveOrderAjax&tmpl=component';
	JHtml::_('sortablelist.sortable', 'eventsList', 'adminForm', strtolower($listDirn), $saveOrderingUrl);
}

// Check if GD is enabled
if (extension_loaded('gd') && function_exists('gd_info'))
{
	$thumb_generator = $this->params->get('thumb_generator', 1);
//	echo "It looks like GD is installed";
}
else
{
	$thumb_generator = 0;

	$app->enqueueMessage(JText::_('COM_ICAGENDA_PHP_ERROR_GD'), 'warning');
}

// Check if fopen is allowed
$fopen  = true;
$result = ini_get('allow_url_fopen');

if (empty($result))
{
	$fopen = false;

	$app->enqueueMessage(JText::_('COM_ICAGENDA_PHP_ERROR_FOPEN'), 'warning');
}

// 3.3.3
$sortFields = array();
?>
<form action="<?php echo JRoute::_('index.php?option=com_icagenda&view=events'); ?>" method="post" name="adminForm" id="adminForm">
<?php if (!empty($this->sidebar)) : ?>
	<div id="j-sidebar-container" class="span2">
		<?php echo $this->sidebar; ?>
	</div>
	<div id="j-main-container" class="span10">
<?php else : ?>
	<div id="j-main-container">
<?php endif;?>
		<div id="filter-bar" class="btn-toolbar">
			<div class="filter-search btn-group pull-left">
				<label for="filter_search" class="element-invisible"><?php echo JText::_('COM_ICAGENDA_FILTER_SEARCH_EVENTS_DESC'); ?></label>
				<input class="tip hasTooltip" type="text" name="filter_search" placeholder="<?php echo JText::_('JSEARCH_FILTER'); ?>" id="filter_search" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" title="<?php echo JText::_('COM_ICAGENDA_FILTER_SEARCH_EVENTS_DESC'); ?>" />
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
		<div class="clearfix"> </div>

	<?php if (empty($this->items)) : ?>
		<div class="alert alert-no-items">
			<?php echo JText::_('JGLOBAL_NO_MATCHING_RESULTS'); ?>
		</div>
	<?php else : ?>
		<table class="table table-striped" id="eventsList">
			<thead>
				<tr>
					<th width="1%" class="nowrap center hidden-phone">
						<?php echo JHtml::_('grid.sort', '<i class="icon-menu-2"></i>', 'e.ordering', $listDirn, $listOrder, null, 'asc', 'JGRID_HEADING_ORDERING'); ?>
					</th>
					<th width="1%" class="hidden-phone">
						<input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
					</th>
					<th width="1%" style="min-width:55px" class="nowrap center">
						<?php echo JHtml::_('grid.sort', 'JSTATUS', 'e.state', $listDirn, $listOrder); ?>
					</th>
					<th width="1%" style="min-width:55px" class="nowrap center">
						<?php echo JHtml::_('grid.sort', 'COM_ICAGENDA_EVENTS_APPROVAL', 'e.approval', $listDirn, $listOrder); ?>
					</th>
					<th width="130px" class="nowrap center hidden-phone">
						<?php echo JHtml::_('grid.sort', 'COM_ICAGENDA_EVENTS_IMAGE', 'e.image', $listDirn, $listOrder); ?>
					</th>
					<th>
						<?php echo JHtml::_('grid.sort', 'COM_ICAGENDA_EVENTS_TITLE', 'e.title', $listDirn, $listOrder); ?> |
						<?php echo JHtml::_('grid.sort', 'COM_ICAGENDA_TITLE_CATEGORY', 'category', $listDirn, $listOrder); ?>
					</th>
					<th width="15%" class="nowrap hidden-phone">
						<?php echo JHtml::_('grid.sort',  'COM_ICAGENDA_EVENTS_NEXT', 'e.next', $listDirn, $listOrder); ?>
					</th>
					<th width="10%" class="nowrap hidden-phone">
						<?php echo JHtml::_('grid.sort', 'JGRID_HEADING_ACCESS', 'access', $listDirn, $listOrder); ?>
					</th>
					<th width="10%" class="nowrap hidden-phone">
						<?php echo JHtml::_('grid.sort',  'JAUTHOR', 'author_name', $listDirn, $listOrder); ?>
					</th>
					<th width="5%" class="nowrap hidden-phone">
						<?php echo JHtml::_('grid.sort', 'JGRID_HEADING_LANGUAGE', 'language', $listDirn, $listOrder); ?>
					</th>
					<th width="1%" class="nowrap hidden-phone">
						<?php echo JHtml::_('grid.sort', 'JGLOBAL_HITS', 'e.hits', $listDirn, $listOrder); ?>
					</th>
					<th width="1%" class="nowrap hidden-phone">
						<?php echo JHtml::_('grid.sort', 'JGRID_HEADING_ID', 'e.id', $listDirn, $listOrder); ?>
					</th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<td colspan="12">
						<?php echo $this->pagination->getListFooter(); ?>
					</td>
				</tr>
			</tfoot>
			<tbody valign="top">
			<?php foreach ($this->items as $i => $item) : ?>
				<?php
				$ordering	= ($listOrder == 'e.ordering');
				$canCreate	= $user->authorise('core.create', 'com_icagenda');
				$canEdit	= $user->authorise('core.edit', 'com_icagenda');
				$canCheckin	= $user->authorise('core.manage', 'com_icagenda') || $item->checked_out == $userId || $item->checked_out == 0;
				$canChange	= $user->authorise('core.edit.state', 'com_icagenda') && $canCheckin;
				$canEditOwn	= $user->authorise('core.edit.own', 'com_icagenda') && $item->created_by == $userId;

				// Get Access Names
				$db = JFactory::getDBO();
				$db->setQuery(
					'SELECT `title`' .
					' FROM `#__viewlevels`' .
					' WHERE `id` = '. (int) $item->access
				);
				$access_title = $db->loadObject()->title;

				// Get Today and Next Date (Y-m-d)
				$eventTimeZone	= null;
				$today			= JHtml::date('now', 'Y-m-d');
				$nextdate		= JHtml::date($item->next, 'Y-m-d', $eventTimeZone);
				$isDate			= true; // DEV. 3.8
				?>
				<tr class="row<?php echo $i % 2; ?>" sortable-group-id="<?php echo $item->catid?>">
					<td class="order nowrap center hidden-phone">
					<?php if ($canChange) :
						$disableClassName = '';
						$disabledLabel	  = '';

						if ( ! $saveOrder) :
							$disabledLabel    = JText::_('JORDERINGDISABLED');
							$disableClassName = 'inactive tip-top';
						endif; ?>
						<span class="sortable-handler hasTooltip <?php echo $disableClassName; ?>" title="<?php echo $disabledLabel; ?>">
							<i class="icon-menu"></i>
						</span>
						<input type="text" style="display:none" name="order[]" size="5" value="<?php echo $item->ordering; ?>" class="width-20 text-area-order " />
					<?php else : ?>
						<span class="sortable-handler inactive" >
							<i class="icon-menu"></i>
						</span>
					<?php endif; ?>
					</td>
					<td class="center hidden-phone">
						<?php echo JHtml::_('grid.id', $i, $item->id); ?>
					</td>
					<?php if (isset($this->items[0]->state)) : ?>
						<td class="center">
							<?php
							$eventIsCancelled = icagendaEvent::cancelledButton($item->id);

							if ($eventIsCancelled)
							{
								echo $eventIsCancelled;
							}

							// Control of dates if valid
							elseif ( ! $isDate)
							{
								echo '<br/><i class="icon-warning"></i><br/>';
								echo '<span style="color:red;"><strong>' . JText::_('COM_ICAGENDA_NO_VALID_DATE') . '</strong></span>';
								if ($item->state == '1')
								{
									$db		= Jfactory::getDbo();
									$query	= $db->getQuery(true);
									$query->clear();
									$query->update(' #__icagenda_events ');
									$query->set(' state = 0 ' );
									$query->where(' id = ' . (int) $item->id );
									$db->setQuery((string)$query);
									$db->execute($query);
 								}
							}
							else
							{
							?>
								<div class="btn-group">
									<?php echo JHtml::_('jgrid.published', $item->state, $i, 'events.', $canChange, 'cb'); ?>
									<?php //echo JHtml::_('contentadministrator.featured', $item->featured, $i, $canChange); ?>
									<?php // Create dropdown items and render the dropdown list.
									if ($canChange)
									{
										JHtml::_('actionsdropdown.' . ((int) $item->state === 2 ? 'un' : '') . 'archive', 'cb' . $i, 'events');
										JHtml::_('actionsdropdown.' . ((int) $item->state === -2 ? 'un' : '') . 'trash', 'cb' . $i, 'events');
										echo JHtml::_('actionsdropdown.render', $this->escape($item->title));
									}
									?>
								</div>
							<?php
							}
							?>
						</td>
						<td class="center">
							<?php
							require_once JPATH_COMPONENT .'/helpers/html/events.php';
							$approved = empty( $item->approval ) ? 0 : 1;
							echo JHtml::_('jgrid.state', JHtmlEvents::approveEvents(), $approved, $i, 'events.', (boolean) $approved);
							?>
						</td>
					<?php endif; ?>
					<td class="small hidden-phone">
						<div style="background:#F4F4F4; padding:5px; width:120px; text-align:center; overflow:hidden;">
							<?php
							// Set if run iCthumb
							if (($item->image) && ($thumb_generator == 1))
							{
								// Path to iCagenda thumbs folder
								$thumbsPath = icagendaMedia::iCagendaThumbsPath();

								// Large Size Options
								$l_thumbOptions = $this->params->get('thumb_large', ['900', '600', '100', '0']);
								$l_width        = is_numeric($l_thumbOptions[0]) ? $l_thumbOptions[0] : '900';
								$l_height       = is_numeric($l_thumbOptions[1]) ? $l_thumbOptions[1] : '600';
								$l_quality      = is_numeric($l_thumbOptions[2]) ? $l_thumbOptions[2] : '100';
								$l_crop         = ! empty($l_thumbOptions[3]) ? true : false;

								// Medium Size Options
								$m_thumbOptions = $this->params->get('thumb_medium', ['300', '300', '100', '0']);
								$m_width        = is_numeric($m_thumbOptions[0]) ? $m_thumbOptions[0] : '300';
								$m_height       = is_numeric($m_thumbOptions[1]) ? $m_thumbOptions[1] : '300';
								$m_quality      = is_numeric($m_thumbOptions[2]) ? $m_thumbOptions[2] : '100';
								$m_crop         = ! empty($m_thumbOptions[3]) ? true : false;

								// Small Size Options
								$s_thumbOptions = $this->params->get('thumb_small', ['100', '100', '100', '0']);
								$s_width        = is_numeric($s_thumbOptions[0]) ? $s_thumbOptions[0] : '100';
								$s_height       = is_numeric($s_thumbOptions[1]) ? $s_thumbOptions[1] : '100';
								$s_quality      = is_numeric($s_thumbOptions[2]) ? $s_thumbOptions[2] : '100';
								$s_crop         = ! empty($s_thumbOptions[3]) ? true : false;

								// XSmall Size Options
								$xs_thumbOptions = $this->params->get('thumb_xsmall', ['48', '48', '80', '1']);
								$xs_width        = is_numeric($xs_thumbOptions[0]) ? $xs_thumbOptions[0] : '48';
								$xs_height       = is_numeric($xs_thumbOptions[1]) ? $xs_thumbOptions[1] : '48';
								$xs_quality      = is_numeric($xs_thumbOptions[2]) ? $xs_thumbOptions[2] : '80';
								$xs_crop         = ! empty($xs_thumbOptions[3]) ? true : false;

								// Generate large thumb if not exist
								iCThumbGet::thumbnail($item->image, $thumbsPath, 'themes',
									$l_width, $l_height, $l_quality, $l_crop, 'ic_large', null, true);

								// Generate medium thumb if not exist
								iCThumbGet::thumbnail($item->image, $thumbsPath, 'themes',
									$m_width, $m_height, $m_quality, $m_crop, 'ic_medium');

								// Generate small thumb if not exist
								iCThumbGet::thumbnail($item->image, $thumbsPath, 'themes',
									$s_width, $s_height, $s_quality, $s_crop, 'ic_small');

								// Generate x-small thumb if not exist
								iCThumbGet::thumbnail($item->image, $thumbsPath, 'themes',
									$xs_width, $xs_height, $xs_quality, $xs_crop, 'ic_xsmall');

								// Sub-folder Destination ($thumbsPath / 'subfolder' /)
								$subFolder = 'system';

								// Display thumbnail in admin events list
								echo iCThumbGet::thumbnailImgTagLinkModal($item->image, $thumbsPath, $subFolder, '120', '100', '100', false);
							}
							elseif ($item->image
								&& $thumb_generator == 0)
							{
								if (filter_var($item->image, FILTER_VALIDATE_URL))
								{
									echo '<a href="' . $item->image . '" class="modal">';
									echo '<img src="' . $item->image . '" alt="" /></a>';
								}
								else
								{
									echo '<a href="../' . $item->image . '" class="modal">';
									echo '<img src="../' . $item->image . '" alt="" /></a>';
								}
							}
							else
							{
								echo '<img style="max-width:120px; max-height:100px;" src="../media/com_icagenda/images/nophoto.jpg" alt="" />';
							}
							?>
						</div>
					</td>
					<td class="has-context">
						<div class="pull-left">
							<?php if ($item->checked_out) : ?>
								<?php echo JHtml::_('jgrid.checkedout', $i, $item->editor, $item->checked_out_time, 'events.', $canCheckin); ?>
							<?php endif; ?>
							<?php if ($item->language == '*'):?>
								<?php $language = JText::alt('JALL', 'language'); ?>
							<?php else:?>
								<?php $language = $item->language ? $this->escape($item->language) : JText::_('JUNDEFINED'); ?>
							<?php endif;?>
							<?php if ($canEdit || $canEditOwn) : ?>
								<a href="<?php echo JRoute::_('index.php?option=com_icagenda&task=event.edit&id=' . $item->id); ?>" title="<?php echo JText::_('JACTION_EDIT'); ?>">
									<?php echo $this->escape($item->title); ?></a>
							<?php else : ?>
								<span title="<?php echo JText::sprintf('JFIELD_ALIAS_LABEL', $this->escape($item->alias)); ?>"><?php echo $this->escape($item->title); ?></span>
							<?php endif; ?>
							<div class="small">
								<?php echo JText::_('JCATEGORY') . ": " . $this->escape(JText::_($item->category)); ?>
							</div>
							<?php if (($item->place) OR ($item->city) OR ($item->country)) : ?>
							<p>
								<?php if ($item->place) : ?>
								<div class="small iC-italic-grey">
									<?php echo JText::_('COM_ICAGENDA_TITLE_LOCATION') . ": " . $this->escape($item->place); ?>
								</div>
								<?php endif; ?>
								<?php if ($item->city) : ?>
								<div class="small iC-italic-grey">
									<?php echo JText::_('COM_ICAGENDA_FORM_LBL_EVENT_CITY') . ": " . $this->escape($item->city); ?>
								</div>
								<?php endif; ?>
								<?php if ($item->country) : ?>
								<div class="small iC-italic-grey">
									<?php echo JText::_('COM_ICAGENDA_FORM_LBL_EVENT_COUNTRY') . ": " . $this->escape($item->country); ?>
								</div>
							</p>
							<?php endif; ?>
							<?php endif; ?>
							<?php if (!empty($item->site_itemid)) : ?>
							<a class="hasTooltip" href="<?php echo JURI::root() . 'index.php?option=com_icagenda&view=submit&Itemid=' . $item->site_itemid; ?>" title="<?php echo JText::_('COM_ICAGENDA_FORM_FRONTEND_SUBMIT_ITEMID_DESC'); ?>" target="_blank">
								<div class="btn btn-primary btn-mini">
									<?php echo JText::_('COM_ICAGENDA_FORM_FRONTEND_SUBMIT_ITEMID_LBL') . ": " . $this->escape($item->site_itemid); ?>
								</div>
							</a>
							<?php endif; ?>
						</div>
						<div class="pull-left">
							<?php
							if ($canChange || $canEditOwn)
							{
								// Create dropdown items
								JHtml::_('dropdown.edit', $item->id, 'event.');
								JHtml::_('dropdown.divider');
								if ($item->state) :
									JHtml::_('dropdown.unpublish', 'cb' . $i, 'events.');
								else :
									JHtml::_('dropdown.publish', 'cb' . $i, 'events.');
								endif;
								JHtml::_('dropdown.divider');
								if ($archived) :
									JHtml::_('dropdown.unarchive', 'cb' . $i, 'events.');
								else :
									JHtml::_('dropdown.archive', 'cb' . $i, 'events.');
								endif;
								if ($item->checked_out) :
									JHtml::_('dropdown.checkin', 'cb' . $i, 'events.');
								endif;
								if ($trashed) :
									JHtml::_('dropdown.untrash', 'cb' . $i, 'events.');
								else :
									JHtml::_('dropdown.trash', 'cb' . $i, 'events.');
								endif;
								// Render dropdown list
								echo JHtml::_('dropdown.render');
							}
							?>
						</div>
					</td>
					<td class="small hidden-phone">
						<?php
						$nextdate   = JHtml::date($item->next, 'Y-m-d', $eventTimeZone);
						$eventDate  = icagendaRender::dateToFormat($item->next);
						$eventTime  = $item->displaytime ? ' ' . icagendaRender::dateToTime($item->next) : '';
						$dateshow   = $eventDate . $eventTime;

						// Upcoming Next Date
						if (iCDate::isDate($item->next) && $item->startdate <= $item->enddate)
						{
							$upcomingLabel = JText::_('COM_ICAGENDA_EVENTS_NEXT_UPCOMING');
							$currentLabel  = JText::_('COM_ICAGENDA_EVENTS_NEXT_TODAY');
							$pastLabel     = JText::_('COM_ICAGENDA_EVENTS_NEXT_PAST');

							// Period is current
							if ($item->next == $item->startdate
								&& iCDate::isDate($item->enddate))
							{
								$eventStartDate = icagendaRender::dateToFormat($item->startdate);
								$eventStartTime = $item->displaytime ? ' ' . icagendaRender::dateToTime($item->startdate) : '';
								$eventEndDate   = icagendaRender::dateToFormat($item->enddate);
								$eventEndTime   = $item->displaytime ? ' ' . icagendaRender::dateToTime($item->enddate) : '';

								if ($eventStartDate == $eventEndDate)
								{
									$eventEndDate = '';
								}
								else
								{
									$upcomingLabel  = JText::_('COM_ICAGENDA_EVENTS_NEXT_UPCOMING_PERIOD');
									$currentLabel   = JText::_('COM_ICAGENDA_EVENTS_NEXT_TODAY_PERIOD');
									$pastLabel      = JText::_('COM_ICAGENDA_EVENTS_NEXT_PAST_PERIOD');
								}

								$separator = ($item->displaytime || $eventEndDate) ? ' > ' : '';
								$dateshow  = $eventStartDate . $eventStartTime . $separator . $eventEndDate . $eventEndTime;

								if ($nextdate < $today && $item->enddate > $today)
								{
									$nextdate       = $today;
								}

								$currentLabel   = ($nextdate >= $today && $item->next > JHtml::date('now', 'Y-m-d H:i:s'))
												? JText::_('COM_ICAGENDA_EVENTS_NEXT_TODAY')
												: JText::_('COM_ICAGENDA_EVENTS_NEXT_TODAY_PERIOD');
							}

							if ($nextdate > $today)
							{
								echo '<strong>' . $upcomingLabel . '</strong><br />';
								echo '<div class="ic-nextdate ic-upcoming">';
								echo '<center>' . $dateshow . '</center>';
								echo '</div>';
							}
							elseif ($nextdate == $today)
							{
								echo '<strong>' . $currentLabel . '</strong><br />';
								echo '<div class="ic-nextdate ic-today">';
								echo '<center>' . $dateshow . '</center>';
								echo '</div>';
							}
							elseif ($nextdate < $today)
							{
								echo '<i>' . $pastLabel . '</i><br />';
								echo '<div class="ic-nextdate ic-past">';
								echo '<center>' . $dateshow . '</center>';
								echo '</div>';
							}
						}
						else
						{
							echo '<div class="ic-nextdate ic-no-date">';
							echo JText::_('COM_ICAGENDA_EVENTS_NEXT_ALERT');
							echo '</div>';
						}
						?>
					</td>
					<td class="small hidden-phone">
						<?php echo $this->escape($access_title); ?>
					</td>
					<td class="small hidden-phone">
						<?php
						if ($item->username == '' && ! $item->created_by)
						{
							$undefined = '<i>' . JText::_('JUNDEFINED') . '</i>';
							echo $undefined;
						}
						elseif ( ! $item->created_by || ! $item->author_name)
						{
							echo $this->escape($item->username);
						}
						else
						{
							echo $this->escape($item->author_name);
							echo ' [' . $this->escape($item->author_username) . ']';
						}
						?>
						<?php //echo JText::_('JGLOBAL_USERNAME').': '.$this->escape($username); ?>
						<?php if ($item->created_by_alias) : ?>
						<p class="smallsub">
							<?php echo JText::sprintf('JGLOBAL_LIST_ALIAS', $this->escape($item->created_by_alias)); ?>
						</p>
						<?php endif; ?>
					</td>
					<td class="small hidden-phone">
						<?php if ($item->language == '*'):?>
							<?php echo JText::alt('JALL', 'language'); ?>
						<?php else:?>
							<?php echo $item->language ? $this->escape($item->language) : JText::_('JUNDEFINED'); ?>
						<?php endif; ?>
					</td>
					<td class="hidden-phone">
						<?php echo (int) $item->hits; ?>
					</td>
					<?php if (isset($this->items[0]->id)) : ?>
					<td class="center hidden-phone">
						<?php echo (int) $item->id; ?>
					</td>
					<?php endif; ?>
				</tr>
			<?php endforeach; ?>
			</tbody>
		</table>
	<?php endif; ?>

		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
		<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>
