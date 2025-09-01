<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.8.22 2023-11-06
 *
 * @package     iCagenda.Admin
 * @subpackage  tmpl.customfields
 * @link        https://www.icagenda.com
 *
 * @author      Cyril Rezé
 * @copyright   (c) 2012-2024 Cyril Rezé / iCagenda. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 *
 * @since       3.4
 *----------------------------------------------------------------------------
*/

defined('_JEXEC') or die;

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
$saveOrder = $listOrder == 'cf.ordering';

if ($saveOrder && ! empty($this->items))
{
	$saveOrderingUrl = 'index.php?option=com_icagenda&task=customfields.saveOrderAjax&tmpl=component&' . Session::getFormToken() . '=1';
	HTMLHelper::_('draggablelist.draggable');
}

// Disable forced table accent background from Atum template to use contextual classes to color tables, table rows or individual cells.
Factory::getDocument()->addStyleDeclaration('.table { --table-accent-bg: none; }');
?>

<form action="<?php echo Route::_('index.php?option=com_icagenda&view=customfields'); ?>" method="post" name="adminForm" id="adminForm">
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
							<?php echo Text::_('COM_ICAGENDA_CUSTOMFIELDS_TABLE_CAPTION'); ?>,
							<span id="orderedBy"><?php echo Text::_('JGLOBAL_SORTED_BY'); ?> </span>,
							<span id="filteredBy"><?php echo Text::_('JGLOBAL_FILTERED_BY'); ?></span>
						</caption>
						<thead>
							<tr>
								<td class="w-1 text-center">
									<?php echo HTMLHelper::_('grid.checkall'); ?>
								</td>
								<th scope="col" class="w-1 text-center d-none d-md-table-cell">
									<?php echo HTMLHelper::_('searchtools.sort', '', 'cf.ordering', $listDirn, $listOrder, null, 'asc', 'JGRID_HEADING_ORDERING', 'icon-sort'); ?>
								</th>
								<th scope="col" class="w-1 text-center">
									<?php echo HTMLHelper::_('searchtools.sort', 'JSTATUS', 'cf.state', $listDirn, $listOrder); ?>
								</th>
								<th scope="col" style="min-width:100px">
									<?php echo HTMLHelper::_('searchtools.sort', 'COM_ICAGENDA_CUSTOMFIELD_TITLE_LBL', 'cf.title', $listDirn, $listOrder); ?>
								</th>
								<th scope="col" class="w-10 d-none d-lg-table-cell">
									<?php echo HTMLHelper::_('searchtools.sort', 'COM_ICAGENDA_CUSTOMFIELD_SLUG_LBL', 'cf.slug', $listDirn, $listOrder); ?>
								</th>
								<th scope="col">
									<?php echo HTMLHelper::_('searchtools.sort', 'COM_ICAGENDA_CUSTOMFIELD_PARENT_FORM_LBL', 'cf.parent_form', $listDirn, $listOrder); ?>
								</th>
								<th scope="col" class="w-10 d-none d-lg-table-cell">
									<?php echo Text::_('COM_ICAGENDA_CUSTOMFIELDS_GROUPS_LBL'); ?>
								</th>
								<th scope="col" class="w-15 d-none d-md-table-cell">
									<?php echo HTMLHelper::_('searchtools.sort', 'COM_ICAGENDA_CUSTOMFIELD_TYPE_LBL', 'cf.type', $listDirn, $listOrder); ?>
								</th>
								<th scope="col" class="w-5 text-center d-none d-lg-table-cell">
									<?php echo HTMLHelper::_('searchtools.sort', 'COM_ICAGENDA_CUSTOMFIELD_REQUIRED_LBL', 'cf.required', $listDirn, $listOrder); ?>
								</th>
								<th scope="col" class="w-3 d-none d-lg-table-cell">
									<?php echo HTMLHelper::_('searchtools.sort', 'JGRID_HEADING_ID', 'cf.id', $listDirn, $listOrder); ?>
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

								// Custom Field Groups list
								$groups = array();

								if ($item->groups)
								{
									$cf_groups    = $this->cfGroups;
									$array_groups = explode(',', $item->groups);

									foreach ($cf_groups as $key)
									{
										if (in_array($key->value, $array_groups))
										{
											$groups[]= $key->option;
										}
									}
								}

								$overrideCore = (str_replace('core_', '', $item->type) !== $item->type) ? true : false;
								$separator    = (str_replace('spacer_', '', $item->type) !== $item->type) ? true : false;

								$rowClass = '';

								if ($overrideCore)
								{
									$rowClass = ' table-info';
								}
								elseif ($separator)
								{
									$rowClass = ' table-secondary';
								}
								?>
								<tr class="row<?php echo $i % 2; ?><?php echo $rowClass; ?>" data-draggable-group="none" item-id="<?php echo $item->id ?>">
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
				    				<td class="text-center<?php echo $rowClass; ?>">
										<?php echo HTMLHelper::_('jgrid.published', $item->state, $i, 'customfields.', $canChange); ?>
									</td>
									<th scope="row" class="has-context">
										<div class="break-word">
											<?php if ($item->checked_out) : ?>
												<?php echo HTMLHelper::_('jgrid.checkedout', $i, $item->editor, $item->checked_out_time, 'customfields.', $canCheckin); ?>
											<?php endif; ?>
											<?php if ($canEdit || $canEditOwn) : ?>
												<a href="<?php echo Route::_('index.php?option=com_icagenda&task=customfield.edit&id=' . $item->id); ?>" title="<?php echo Text::_('JACTION_EDIT'); ?> <?php echo $this->escape($item->title); ?>">
													<?php echo $this->escape($item->title); ?>
												</a>
											<?php else : ?>
												<span title="<?php echo Text::sprintf('JFIELD_ALIAS_LABEL', $this->escape($item->alias)); ?>"><?php echo $this->escape($item->title); ?></span>
											<?php endif; ?>
											<span class="d-table-cell d-md-none">
												<?php if ($item->slug) : ?>
													<span class="small">
														<?php echo Text::sprintf('COM_ICAGENDA_CUSTOMFIELDS_SLUG', $this->escape($item->slug)); ?>
													</span>
												<?php endif; ?>
											</span>
										</div>
									</th>
									<td class="d-none d-lg-table-cell">
										<?php echo $this->escape($item->slug); ?>
									</td>
									<td>
										<?php if ($item->parent_form == 1) : ?>
											<?php
//											$reg_label = $groups ? 'secondary' : 'success';
											?>
											<?php //echo '<span class="badge bg-' . $reg_label . '">' . Text::_('COM_ICAGENDA_CUSTOMFIELD_PARENT_REGISTRATION_FORM') . '</span>'; ?>
											<div class="badge bg-success px-2">
												<?php echo Text::_('COM_ICAGENDA_CUSTOMFIELD_PARENT_REGISTRATION_FORM'); ?>
												<?php if (count($groups) > 0) : ?>
													<span id="list-groups-badge" class="badge bg-light text-dark itemnumber d-inline-block d-lg-none ms-2">
														&nbsp;<?php echo count($groups); ?>&nbsp;
													</span>
													<div id="list-groups-badge-desc" class="small" role="tooltip"><?php echo Text::_('COM_ICAGENDA_CUSTOMFIELDS_GROUPS_LBL'); ?></div>
												<?php endif; ?>
											</div>
										<?php elseif ($item->parent_form == 2) : ?>
											<?php echo '<span class="badge bg-info px-2">' . Text::_('COM_ICAGENDA_CUSTOMFIELD_PARENT_EVENT_EDIT') . '</span>'; ?>
										<?php endif; ?>
									</td>
									<td class="d-none d-lg-table-cell">
										<?php if ($item->groups) : ?>
											<?php
											echo '<span class="badge bg-primary itemnumber">';
											echo implode('</span> <span class="badge bg-primary itemnumber">', array_values($groups));
											echo '</span>';
											?>
										<?php endif; ?>
									</td>
									<td class="d-none d-md-table-cell">
										<?php if ($item->type) : ?>
											<?php
											$type_ex        = explode('_', $item->type);
											$type_prefix    = isset($type_ex[1]) ? $type_ex[0] : '';
											$type_label     = ($type_prefix == 'core')
															? Text::_('COM_ICAGENDA_LABEL_OVERRIDE')
															: Text::_('COM_ICAGENDA_LABEL_SPACER');
											$type           = in_array($type_prefix, array('core', 'spacer'))
															? Text::_('COM_ICAGENDA_CUSTOMFIELD_TYPE_' . strtoupper(str_replace($type_prefix . '_', '' , $item->type)))
															: Text::_('COM_ICAGENDA_CUSTOMFIELD_TYPE_' . strtoupper($item->type));
											?>
											<?php if ($type_prefix) : ?>
												<?php echo '<span class="badge' . ($type_prefix == 'core' ? ' bg-info' : ' bg-secondary') . '">' . $type_label . '</span>'; ?>
											<?php endif; ?>
											<?php echo $this->escape($type); ?>
										<?php endif; ?>
									</td>
									<td class="d-none d-lg-table-cell">
										<?php if ($type_prefix == 'spacer') : ?>
											<?php echo ''; ?>
										<?php elseif ($type_prefix == 'core') : ?>
											<?php echo '<span class="badge bg-info">' . Text::_('JGLOBAL_USE_GLOBAL') . '</span>'; ?>
										<?php elseif ($item->required == 1) : ?>
											<?php echo '<span class="badge bg-success">' . Text::_('JYES') . '</span>'; ?>
										<?php else : ?>
											<?php echo '<span class="badge bg-danger">' . Text::_('JNO') . '</span>'; ?>
										<?php endif; ?>
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
