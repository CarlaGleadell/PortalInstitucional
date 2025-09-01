<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.9.8 2024-11-29
 *
 * @package     iCagenda.Admin
 * @subpackage  tmpl.events
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

use iClib\Date\Date as iCDate;
use iClib\Thumb\Get as iCThumbGet;
use iCutilities\Event\Event as icagendaEvent;
use iCutilities\Manager\Manager as icagendaManager;
use iCutilities\Media\Media as icagendaMedia;
use iCutilities\Render\Render as icagendaRender;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Multilanguage;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Session\Session;
use Joomla\CMS\Uri\Uri;
use WebiC\Component\iCagenda\Administrator\Helper\iCagendaHelper;

HTMLHelper::_('behavior.multiselect');

$app       = Factory::getApplication();
$user      = Factory::getUser();
$userId    = $user->get('id');
$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn  = $this->escape($this->state->get('list.direction'));
$saveOrder = ($listOrder == 'e.ordering' && strtolower($listDirn) == 'asc');

// Include the component HTML helpers.
HTMLHelper::addIncludePath(JPATH_COMPONENT . '/helpers/html');

$archived = $this->state->get('filter.published') == 2 ? true : false;
$trashed  = $this->state->get('filter.published') == -2 ? true : false;

if ($saveOrder && ! empty($this->items))
{
	$saveOrderingUrl = 'index.php?option=com_icagenda&task=events.saveOrderAjax&tmpl=component&' . Session::getFormToken() . '=1';
	HTMLHelper::_('draggablelist.draggable');
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

	$app->enqueueMessage(Text::_('COM_ICAGENDA_PHP_ERROR_GD'), 'warning');
}

// Check if fopen is allowed
$fopen  = true;
$result = ini_get('allow_url_fopen');

if (empty($result))
{
	$fopen = false;

	$app->enqueueMessage(Text::_('COM_ICAGENDA_PHP_ERROR_FOPEN'), 'warning');
}
?>

<form action="<?php echo Route::_('index.php?option=com_icagenda&view=events'); ?>" method="post" name="adminForm" id="adminForm">
	<div class="row">
		<div class="col-md-12">
			<div id="j-main-container">
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
							<?php echo Text::_('COM_ICAGENDA_EVENTS_TABLE_CAPTION'); ?>,
							<span id="orderedBy"><?php echo Text::_('JGLOBAL_SORTED_BY'); ?> </span>,
							<span id="filteredBy"><?php echo Text::_('JGLOBAL_FILTERED_BY'); ?></span>
						</caption>
						<thead>
							<tr>
								<td class="w-1 text-center">
									<?php echo HTMLHelper::_('grid.checkall'); ?>
								</td>
								<th scope="col" class="w-1 text-center d-none d-md-table-cell">
									<?php echo HTMLHelper::_('searchtools.sort', '', 'e.ordering', $listDirn, $listOrder, null, 'asc', 'JGRID_HEADING_ORDERING', 'icon-sort'); ?>
								</th>
								<th scope="col" class="w-1 text-center">
									<?php echo HTMLHelper::_('searchtools.sort', 'JSTATUS', 'e.state', $listDirn, $listOrder); ?>
								</th>
								<th scope="col" class="w-1 text-center d-none d-lg-table-cell">
									<?php echo HTMLHelper::_('searchtools.sort', 'COM_ICAGENDA_EVENTS_APPROVAL', 'e.approval', $listDirn, $listOrder); ?>
								</th>
								<th scope="col" class="w-1 text-center d-none d-lg-table-cell">
									<?php echo HTMLHelper::_('searchtools.sort', 'COM_ICAGENDA_EVENTS_IMAGE', 'e.image', $listDirn, $listOrder); ?>
								</th>
								<th scope="col" style="min-width:200px">
									<?php echo HTMLHelper::_('searchtools.sort', 'COM_ICAGENDA_EVENTS_TITLE', 'e.title', $listDirn, $listOrder); ?>&nbsp;|&nbsp;
									<?php echo HTMLHelper::_('searchtools.sort', 'COM_ICAGENDA_TITLE_CATEGORY', 'category', $listDirn, $listOrder); ?>
								</th>
								<th scope="col" class="d-none d-md-table-cell" style="min-width:250px">
									<?php echo HTMLHelper::_('searchtools.sort', 'COM_ICAGENDA_EVENTS_NEXT', 'e.next', $listDirn, $listOrder); ?>
								</th>
								<th scope="col" class="w-3 d-none d-lg-table-cell">
									<?php echo HTMLHelper::_('searchtools.sort', 'JGRID_HEADING_ACCESS', 'e.access', $listDirn, $listOrder); ?>
								</th>
								<th scope="col" class="w-3 d-none d-lg-table-cell">
									<?php echo HTMLHelper::_('searchtools.sort', 'JAUTHOR', 'e.author_name', $listDirn, $listOrder); ?>
								</th>
								<?php if (Multilanguage::isEnabled()) : ?>
									<th scope="col" class="w-3 d-none d-lg-table-cell">
										<?php echo HTMLHelper::_('searchtools.sort', 'JGRID_HEADING_LANGUAGE', 'e.language', $listDirn, $listOrder); ?>
									</th>
								<?php endif; ?>
								<th scope="col" class="w-3 d-none d-lg-table-cell">
									<?php echo HTMLHelper::_('searchtools.sort', 'JGLOBAL_HITS', 'e.hits', $listDirn, $listOrder); ?>
								</th>
								<th scope="col" class="w-3 d-none d-lg-table-cell">
									<?php echo HTMLHelper::_('searchtools.sort', 'JGRID_HEADING_ID', 'e.id', $listDirn, $listOrder); ?>
								</th>
							</tr>
						</thead>
						<tbody<?php if ($saveOrder) : ?> class="js-draggable" data-url="<?php echo $saveOrderingUrl; ?>" data-direction="<?php echo strtolower($listDirn); ?>" data-nested="false"<?php endif; ?>>
							<?php foreach ($this->items as $i => $item) : ?>
								<?php
								$canEdit    = $user->authorise('core.edit',       'com_icagenda');
								$canCheckin = $user->authorise('core.manage',     'com_icagenda') || $item->checked_out == $userId || is_null($item->checked_out);
								$canEditOwn = $user->authorise('core.edit.own',   'com_icagenda') && $item->created_by == $userId;
								$canChange  = $user->authorise('core.edit.state', 'com_icagenda') && $canCheckin;
								$isManager  = icagendaManager::isEventManager($userId);

								// Get Today and Next Date (Y-m-d)
								$eventTimeZone = null;
								$today         = HTMLHelper::date('now', 'Y-m-d');
								$nextdate      = HTMLHelper::date($item->next, 'Y-m-d', $eventTimeZone);
//								$isDate        = iCDate::isDate($item->next);
								$isDate        = true; // DEV. 3.8
								?>
								<tr class="row<?php echo $i % 2; ?>" data-draggable-group="none" item-id="<?php echo $item->id ?>">
									<td class="text-center">
										<?php echo HTMLHelper::_('grid.id', $i, $item->id, false, 'cid', 'cb', $item->title); ?>
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
										<?php $eventIsCancelled = icagendaEvent::cancelledButton($item->id); ?>
										<?php if ($eventIsCancelled) : ?>
											<?php echo $eventIsCancelled ?>
										<?php // Control of dates if valid ?>
										<?php elseif ( ! $isDate) : ?>
											<br/>
											<i class="icon-warning"></i>
											<br/>
											<span style="color:red;">
												<strong><?php echo Text::_('COM_ICAGENDA_NO_VALID_DATE'); ?></strong>
											</span>
											<?php
											if ($item->state == '1')
											{
												$db    = Factory::getDbo();
												$query = $db->getQuery(true);
												$query->clear();
												$query->update(' #__icagenda_events ');
												$query->set(' state = 0 ' );
												$query->where(' id = ' . (int) $item->id );
												$db->setQuery((string)$query);
												$db->execute($query);
 											}
 											?>
										<?php else : ?>
											<?php echo HTMLHelper::_('jgrid.published', $item->state, $i, 'events.', ($this->iCategories && $canChange)); ?>
										<?php endif; ?>
									</td>
									<td class="text-center d-none d-lg-table-cell">
										<?php
										$approved = empty( $item->approval ) ? 0 : 1;
										?>
										<?php echo HTMLHelper::_('jgrid.state', iCagendaHelper::approveEvents(), $approved, $i, 'events.', ($this->iCategories && $isManager && (boolean) $approved)); ?>
									</td>
									<td class="text-center d-none d-lg-table-cell">
										<div style="background:#F4F4F4; padding:5px; width:130px; text-align:center; overflow:hidden;">
											<?php
											$img     = HTMLHelper::cleanImageURL($item->image);
											$img_url = $img->url;

											// Set if run iCthumb
											if (($img_url) && ($thumb_generator == 1))
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
												iCThumbGet::thumbnail($img_url, $thumbsPath, 'themes',
													$l_width, $l_height, $l_quality, $l_crop, 'ic_large', null, true);

												// Generate medium thumb if not exist
												iCThumbGet::thumbnail($img_url, $thumbsPath, 'themes',
													$m_width, $m_height, $m_quality, $m_crop, 'ic_medium');

												// Generate small thumb if not exist
												iCThumbGet::thumbnail($img_url, $thumbsPath, 'themes',
													$s_width, $s_height, $s_quality, $s_crop, 'ic_small');

												// Generate x-small thumb if not exist
												iCThumbGet::thumbnail($img_url, $thumbsPath, 'themes',
													$xs_width, $xs_height, $xs_quality, $xs_crop, 'ic_xsmall');

												// Sub-folder Destination ($thumbsPath / 'subfolder' /)
												$subFolder = 'system';

												// Display thumbnail in admin events list
												echo iCThumbGet::thumbnailImgTagLinkModal($img_url, $thumbsPath, $subFolder, '120', '100', '100', false);
											}
											elseif ($img_url
												&& $thumb_generator == 0)
											{
												if (filter_var($img_url, FILTER_VALIDATE_URL))
												{
													echo '<a href="' . $img_url . '" class="modal">';
													echo '<img src="' . $img_url . '" alt="" /></a>';
												}
												else
												{
													echo '<a href="../' . $img_url . '" class="modal">';
													echo '<img src="../' . $img_url . '" alt="" /></a>';
												}
											}
											else
											{
												echo '<img style="max-width:120px; max-height:100px;" src="../media/com_icagenda/images/nophoto.jpg" alt="" />';
											}
											?>
										</div>
									</td>
									<th scope="row">
										<?php if ($item->checked_out) : ?>
											<?php echo HTMLHelper::_('jgrid.checkedout', $i, $item->editor, $item->checked_out_time, 'events.', $canCheckin); ?>
										<?php endif; ?>
										<?php if ($this->iCategories && ($canEdit || $canEditOwn)) : ?>
											<a href="<?php echo Route::_('index.php?option=com_icagenda&task=event.edit&id=' . $item->id); ?>" title="<?php echo Text::_('JACTION_EDIT'); ?> <?php echo $this->escape($item->title); ?>">
												<?php echo $this->escape($item->title); ?>
											</a>
										<?php else : ?>
											<?php echo $this->escape($item->title); ?>
										<?php endif; ?>
										<div>
										<span class="small">
											<?php if (empty($item->note)) : ?>
												<?php echo Text::sprintf('JGLOBAL_LIST_ALIAS', $this->escape($item->alias)); ?>
											<?php else : ?>
												<?php echo Text::sprintf('JGLOBAL_LIST_ALIAS_NOTE', $this->escape($item->alias), $this->escape($item->note)); ?>
											<?php endif; ?>
										</span>
										</div>
										<div>
										<span class="small">
											<?php echo Text::_('JCATEGORY') . ": " . $this->escape(Text::_($item->category)); ?>
										</span>
										</div>
										<?php if (($item->place) || ($item->city) || ($item->country)) : ?>
											<p>
												<?php if ($item->place) : ?>
													<div class="small iC-italic-grey">
														<?php echo Text::_('COM_ICAGENDA_TITLE_LOCATION') . ": " . $this->escape($item->place); ?>
													</div>
												<?php endif; ?>
												<?php if ($item->city) : ?>
													<div class="small iC-italic-grey">
														<?php echo Text::_('COM_ICAGENDA_FORM_LBL_EVENT_CITY') . ": " . $this->escape($item->city); ?>
													</div>
												<?php endif; ?>
												<?php if ($item->country) : ?>
													<div class="small iC-italic-grey">
														<?php echo Text::_('COM_ICAGENDA_FORM_LBL_EVENT_COUNTRY') . ": " . $this->escape($item->country); ?>
													</div>
												<?php endif; ?>
											</p>
										<?php endif; ?>
										<?php if (!empty($item->site_itemid)) : ?>
											<a href="<?php echo Uri::root() . 'index.php?option=com_icagenda&view=submit&Itemid=' . $item->site_itemid; ?>" title="<?php echo Text::_('COM_ICAGENDA_FORM_FRONTEND_SUBMIT_ITEMID_DESC'); ?>">
												<div class="btn btn-primary btn-sm">
													<?php echo Text::_('COM_ICAGENDA_FORM_FRONTEND_SUBMIT_ITEMID_LBL') . ": " . $this->escape($item->site_itemid); ?>
												</div>
											</a>
										<?php endif; ?>
									</th>
									<td class="text-left d-none d-md-table-cell">
										<?php
										$nextdate  = HTMLHelper::date($item->next, 'Y-m-d', $eventTimeZone);
										$eventDate = icagendaRender::dateToFormat($item->next);
										$eventTime = $item->displaytime ? ' ' . icagendaRender::dateToTime($item->next) : '';
										$spacer1   = '&nbsp;';
										$spacer2   = '&nbsp;&nbsp;';
										$dateshow  = $spacer2 . $eventDate . $eventTime . $spacer2;

										// Upcoming Next Date
										if (iCDate::isDate($item->next) && $item->startdate <= $item->enddate)
										{
											$upcomingLabel = Text::_('COM_ICAGENDA_EVENTS_NEXT_UPCOMING');
											$currentLabel  = Text::_('COM_ICAGENDA_EVENTS_NEXT_TODAY');
											$pastLabel     = Text::_('COM_ICAGENDA_EVENTS_NEXT_PAST');

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
													$upcomingLabel  = Text::_('COM_ICAGENDA_EVENTS_NEXT_UPCOMING_PERIOD');
													$currentLabel   = Text::_('COM_ICAGENDA_EVENTS_NEXT_TODAY_PERIOD');
													$pastLabel      = Text::_('COM_ICAGENDA_EVENTS_NEXT_PAST_PERIOD');
												}

												$separator = ($item->displaytime || $eventEndDate) ? $spacer2 . '<br />-' . $spacer1 : '';
												$dateshow  = $spacer2 . $eventStartDate . $eventStartTime . $separator . $eventEndDate . $eventEndTime . $spacer2;

												if ($nextdate < $today && $item->enddate > $today)
												{
													$nextdate       = $today;
												}

												$currentLabel   = ($nextdate >= $today && $item->next > HTMLHelper::date('now', 'Y-m-d H:i:s'))
																? Text::_('COM_ICAGENDA_EVENTS_NEXT_TODAY')
																: Text::_('COM_ICAGENDA_EVENTS_NEXT_TODAY_PERIOD');
											}

											if ($nextdate > $today)
											{
												echo '<strong class="small">' . $upcomingLabel . '</strong><br />';
												echo '<div class="d-inline-block ic-nextdate ic-upcoming">';
												echo '<center>' . $dateshow . '</center>';
												echo '</div>';
											}
											elseif ($nextdate == $today)
											{
												echo '<strong class="small">' . $currentLabel . '</strong><br />';
												echo '<div class="d-inline-block ic-nextdate ic-today">';
												echo $dateshow;
//												echo '<center>' . $dateshow . '</center>';
												echo '</div>';
											}
											elseif ($nextdate < $today)
											{
												echo '<i class="small">' . $pastLabel . '</i><br />';
												echo '<div class="d-inline-block ic-nextdate ic-past">';
												echo '<center>' . $dateshow . '</center>';
												echo '</div>';
											}
										}
										else
										{
											echo '<div class="ic-nextdate ic-no-date">';
											echo Text::_('COM_ICAGENDA_EVENTS_NEXT_ALERT');
											echo '</div>';
										}
										?>
									</td>
									<td class="small d-none d-lg-table-cell">
										<?php echo $this->escape($item->access_level); ?>
									</td>
									<td class="small d-none d-lg-table-cell">
										<?php if ($item->username == '' && ! $item->created_by) : ?>
											<i><?php echo Text::_('JUNDEFINED'); ?></i>
										<?php elseif ( ! $item->created_by || ! $item->author_name) : ?>
											<?php echo $this->escape($item->username); ?>
										<?php else : ?>
											<?php echo $this->escape($item->author_name); ?>
											<?php echo ' [' . $this->escape($item->author_username) . ']'; ?>
										<?php endif; ?>
										<?php if ($item->created_by_alias) : ?>
											<div class="smallsub"><?php echo Text::sprintf('JGLOBAL_LIST_ALIAS', $this->escape($item->created_by_alias)); ?></div>
										<?php endif; ?>
									</td>
									<?php if (Multilanguage::isEnabled()) : ?>
										<td class="small d-none d-md-table-cell">
											<?php echo LayoutHelper::render('joomla.content.language', $item); ?>
										</td>
									<?php endif; ?>
									<td class="d-none d-lg-table-cell text-center">
										<span class="badge bg-info">
											<?php echo (int) $item->hits; ?>
										</span>
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
				<!--input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
				<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" /-->
				<?php echo HTMLHelper::_('form.token'); ?>
			</div>
		</div>
	</div>
</form>
