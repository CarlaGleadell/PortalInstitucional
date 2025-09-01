<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.9.0 2023-10-25
 *
 * @package     iCagenda.Admin
 * @link        https://www.icagenda.com
 *
 * @author      Cyril Rezé
 * @copyright   (c) 2012-2024 Cyril Rezé / iCagenda. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 *
 * @since       3.4.0
 *----------------------------------------------------------------------------
*/

defined('_JEXEC') or die;

$app = JFactory::getApplication();

// Access Administration Customfields check.
if (JFactory::getUser()->authorise('icagenda.access.customfields', 'com_icagenda'))
{
	// Check Theme Packs Compatibility
	if (class_exists('icagendaTheme')) icagendaTheme::checkThemePacks();

	$user = JFactory::getUser();
	$userId = $user->get('id');
	$listOrder = $this->escape($this->state->get('list.ordering'));
	$listDirn = $this->escape($this->state->get('list.direction'));
	$canOrder = $user->authorise('core.edit.state', 'com_icagenda');

	$saveOrder = $listOrder == 'cf.ordering';

	// Include the component HTML helpers.
	JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');
	JHtml::_('bootstrap.tooltip');
	JHtml::_('behavior.multiselect');
	JHtml::_('formbehavior.chosen', 'select');
	JHtml::_('dropdown.init');

	$extension = $this->escape($this->state->get('filter.extension'));
	$archived = $this->state->get('filter.published') == 2 ? true : false;
	$trashed = $this->state->get('filter.published') == -2 ? true : false;

	if ($saveOrder)
	{
		$saveOrderingUrl = 'index.php?option=com_icagenda&task=customfields.saveOrderAjax&tmpl=component';
		JHtml::_('sortablelist.sortable', 'customfieldsList', 'adminForm', strtolower($listDirn), $saveOrderingUrl, false, true);
	}
	$sortFields = array();
	?>

	<script type="text/javascript">
	Joomla.orderTable = function()
	{
		table = document.getElementById("sortTable");
		direction = document.getElementById("directionTable");
		order = table.options[table.selectedIndex].value;
		if (order != '<?php echo $listOrder; ?>') {
			dirn = 'asc';
		} else {
			dirn = direction.options[direction.selectedIndex].value;
		}
		Joomla.tableOrdering(order, dirn, '');
	}
	</script>

	<form action="<?php echo JRoute::_('index.php?option=com_icagenda&view=customfields'); ?>" method="post" name="adminForm" id="adminForm">
	<?php if (!empty( $this->sidebar)) : ?>
		<div id="j-sidebar-container" class="span2">
			<?php echo $this->sidebar; ?>
		</div>
		<div id="j-main-container" class="span10">
	<?php else : ?>
		<div id="j-main-container">
	<?php endif;?>
			<div id="filter-bar" class="btn-toolbar">
				<div class="filter-search btn-group pull-left">
					<label for="filter_search" class="element-invisible"><?php echo JText::_('COM_ICAGENDA_CUSTOMFIELDS_FILTER_SEARCH_DESC'); ?></label>
					<input type="text" name="filter_search" placeholder="<?php echo JText::_('COM_ICAGENDA_CUSTOMFIELDS_FILTER_SEARCH_DESC'); ?>" id="filter_search" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" title="<?php echo JText::_('COM_ICAGENDA_CUSTOMFIELDS_FILTER_SEARCH_DESC'); ?>" />
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

			<table class="table table-striped" id="customfieldsList">
				<thead>
					<tr>
						<th width="1%" class="nowrap center hidden-phone">
							<?php echo JHtml::_('grid.sort', '<i class="icon-menu-2"></i>', 'cf.ordering', $listDirn, $listOrder, null, 'asc', 'JGRID_HEADING_ORDERING'); ?>
						</th>
						<th width="1%" class="hidden-phone">
							<input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
						</th>
						<th width="1%" style="min-width:55px" class="nowrap center">
							<?php echo JHtml::_('grid.sort', 'JSTATUS', 'cf.state', $listDirn, $listOrder); ?>
						</th>
						<th>
							<?php echo JHtml::_('grid.sort',  'COM_ICAGENDA_CUSTOMFIELD_TITLE_LBL', 'cf.title', $listDirn, $listOrder); ?>
						</th>
						<th class="hidden-phone">
							<?php echo JHtml::_('grid.sort',  'COM_ICAGENDA_CUSTOMFIELD_SLUG_LBL', 'cf.slug', $listDirn, $listOrder); ?>
						</th>
						<th width="20%">
							<?php echo JHtml::_('grid.sort',  'COM_ICAGENDA_CUSTOMFIELD_PARENT_FORM_LBL', 'cf.parent_form', $listDirn, $listOrder); ?>
						</th>
						<th width="10%" class="hidden-phone">
							<?php echo JText::_('COM_ICAGENDA_CUSTOMFIELDS_GROUPS_LBL'); ?>
						</th>
						<th width="20%" class="hidden-phone">
							<?php echo JHtml::_('grid.sort',  'COM_ICAGENDA_CUSTOMFIELD_TYPE_LBL', 'cf.type', $listDirn, $listOrder); ?>
						</th>
						<th class="hidden-phone">
							<?php echo JHtml::_('grid.sort',  'COM_ICAGENDA_CUSTOMFIELD_REQUIRED_LBL', 'cf.required', $listDirn, $listOrder); ?>
						</th>
						<th width="1%" class="nowrap hidden-phone">
							<?php echo JHtml::_('grid.sort', 'JGRID_HEADING_ID', 'cf.id', $listDirn, $listOrder); ?>
						</th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<td colspan="10">
							<?php echo $this->pagination->getListFooter(); ?>
						</td>
					</tr>
				</tfoot>
				<tbody>
				<?php foreach ($this->items as $i => $item) : ?>
					<?php
					$ordering	= ($listOrder == 'cf.ordering');
					$canCreate	= $user->authorise('core.create', 'com_icagenda');
					$canEdit	= $user->authorise('core.edit', 'com_icagenda');
					$canCheckin	= $user->authorise('core.manage', 'com_icagenda');
					$canChange	= $user->authorise('core.edit.state', 'com_icagenda');
					$canEditOwn	= $user->authorise('core.edit.own', 'com_icagenda');

					// Custom Field Groups list
					$groups = array();

					if ($item->groups)
					{
						$cf_groups    = $this->cfGroups;
						$array_groups = explode(',', $item->groups);
						$groups       = array();

						foreach ($cf_groups as $key)
						{
							if (in_array($key->value, $array_groups))
							{
								$groups[]= $key->option;
							}
						}
					}

					$overrideCore = (str_replace('core_', '', $item->type) !== $item->type) ? true : false;
					$separator = (str_replace('spacer_', '', $item->type) !== $item->type) ? true : false;

					if ($overrideCore)
					{
						$rowStyle = ' style="background: #d9edf7; color: #31708f;"';
					}
					elseif ($separator)
					{
						$rowStyle = ' style="background: #e9e9e9; color: #333;"';
					}
					else
					{
						$rowStyle = '';
					}
					?>
					<tr class="row<?php echo $i % 2; ?>">
						<td class="order nowrap center hidden-phone"<?php echo $rowStyle;?>>
							<?php if ($canChange) :
								$disableClassName = '';
								$disabledLabel	  = '';
								if (!$saveOrder) :
									$disabledLabel    = JText::_('JORDERINGDISABLED');
									$disableClassName = 'inactive tip-top';
								endif;
								?>
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
						<td class="center hidden-phone"<?php echo $rowStyle;?>>
							<?php echo JHtml::_('grid.id', $i, $item->id); ?>
						</td>
						<?php if (isset($this->items[0]->state)) : ?>
							<td class="center"<?php echo $rowStyle;?>>
								<?php echo JHtml::_('jgrid.published', $item->state, $i, 'customfields.', $canChange, 'cb'); ?>
							</td>
						<?php endif; ?>
						<td class="nowrap has-context"<?php echo $rowStyle;?>>
							<div class="pull-left">
								<?php if ($item->checked_out) : ?>
									<?php echo JHtml::_('jgrid.checkedout', $i, $item->editor, $item->checked_out_time, 'customfields.', $canCheckin); ?>
								<?php endif; ?>
								<?php if ($canEdit) : ?>
									<a href="<?php echo JRoute::_('index.php?option=com_icagenda&task=customfield.edit&id=' . $item->id); ?>" title="<?php echo JText::_('JACTION_EDIT'); ?>">
									<?php echo $this->escape($item->title); ?></a>
								<?php else : ?>
									<span title="<?php echo JText::sprintf('JFIELD_ALIAS_LABEL', $this->escape($item->alias)); ?>"><?php echo $this->escape($item->title); ?></span>
								<?php endif; ?>
								<span class="hidden-desktop">
									<?php if ($item->slug) : ?>
										<small>
											<?php echo ' (' . JText::_('COM_ICAGENDA_CUSTOMFIELD_SLUG_LBL') . ': ' . $this->escape($item->slug) . ')'; ?>
										</small>
									<?php endif; ?>
								</span>
							</div>
							<div class="pull-left">
								<?php
								// Create dropdown items
								JHtml::_('dropdown.edit', $item->id, 'customfield.');
								JHtml::_('dropdown.divider');
								if ($item->state) :
									JHtml::_('dropdown.unpublish', 'cb' . $i, 'customfields.');
								else :
									JHtml::_('dropdown.publish', 'cb' . $i, 'customfields.');
								endif;
								JHtml::_('dropdown.divider');
								if ($archived) :
									JHtml::_('dropdown.unarchive', 'cb' . $i, 'customfields.');
								else :
									JHtml::_('dropdown.archive', 'cb' . $i, 'customfields.');
								endif;
								if ($item->checked_out) :
									JHtml::_('dropdown.checkin', 'cb' . $i, 'customfields.');
								endif;
								if ($trashed) :
									JHtml::_('dropdown.untrash', 'cb' . $i, 'customfields.');
								else :
									JHtml::_('dropdown.trash', 'cb' . $i, 'customfields.');
								endif;
								// Render dropdown list
								echo JHtml::_('dropdown.render');
								?>
							</div>
						</td>
						<td class="hidden-phone"<?php echo $rowStyle;?>>
							<?php if ($item->slug) : ?>
								<?php echo $this->escape($item->slug); ?>
							<?php endif; ?>
						</td>
						<td<?php echo $rowStyle;?>>
							<?php if ($item->parent_form == 1) : ?>
								<?php
								$reg_label = $groups ? 'inverse' : 'success';
								?>
								<?php echo '<span class="label label-' . $reg_label . '">' . JText::_('COM_ICAGENDA_CUSTOMFIELD_PARENT_REGISTRATION_FORM') . '</span>'; ?>
								<?php if (count($groups)) : ?>
									<?php echo '<span class="hasTooltip badge badge-default" title="' . JText::_('COM_ICAGENDA_CUSTOMFIELDS_GROUPS_LBL') . '">' . count($groups) . '</span>'; ?>
								<?php endif; ?>
							<?php elseif ($item->parent_form == 2) : ?>
								<?php echo '<span class="label label-info">' . JText::_('COM_ICAGENDA_CUSTOMFIELD_PARENT_EVENT_EDIT') . '</span>'; ?>
							<?php endif; ?>
						</td>
						<td class="hidden-phone"<?php echo $rowStyle;?>>
							<?php if ($item->groups) : ?>
								<?php
								echo '<span class="label">';
								echo implode('</span> <span class="label">', array_values($groups));
								echo '</span>';
								?>
							<?php endif; ?>
						</td>
						<td class="hidden-phone"<?php echo $rowStyle;?>>
							<?php if ($item->type) : ?>
								<?php
								$type_ex        = explode('_', $item->type);
								$type_prefix    = isset($type_ex[1]) ? $type_ex[0] : '';
								$type_label     = ($type_prefix == 'core')
												? JText::_('COM_ICAGENDA_LABEL_OVERRIDE')
												: JText::_('COM_ICAGENDA_LABEL_SPACER');
								$type           = in_array($type_prefix, array('core', 'spacer'))
												? JText::_('COM_ICAGENDA_CUSTOMFIELD_TYPE_' . strtoupper(str_replace($type_prefix . '_', '' , $item->type)))
												: JText::_('COM_ICAGENDA_CUSTOMFIELD_TYPE_' . strtoupper($item->type));
								if ($type_prefix) :
									echo '<span class="label' . ($type_prefix == 'core' ? ' label-info' : '') . '">' . $type_label . '</span>';
								endif;
								?>
								<?php echo $this->escape($type); ?>
							<?php endif; ?>
						</td>
						<td class="hidden-phone"<?php echo $rowStyle;?>>
							<?php if ($type_prefix == 'spacer') : ?>
								<?php echo ''; ?>
							<?php elseif ($type_prefix == 'core') : ?>
								<?php echo '<span class="label label-info">' . JText::_('JGLOBAL_USE_GLOBAL') . '</span>'; ?>
							<?php elseif ($item->required == 1) : ?>
								<?php echo '<span class="label label-success">' . JText::_('JYES') . '</span>'; ?>
							<?php else : ?>
								<?php echo '<span class="label">' . JText::_('JNO') . '</span>'; ?>
							<?php endif; ?>
						</td>
						<?php if (isset($this->items[0]->id)) : ?>
							<td class="center hidden-phone"<?php echo $rowStyle;?>>
								<?php echo (int) $item->id; ?>
							</td>
						<?php endif; ?>
					</tr>
				<?php endforeach; ?>
				</tbody>
			</table>
			<div>
				<input type="hidden" name="task" value="" />
				<input type="hidden" name="boxchecked" value="0" />
				<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
				<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
				<?php echo JHtml::_('form.token'); ?>
			</div>
		</div>
	</form>
<?php
}
else
{
	$app->enqueueMessage(JText::_('JERROR_ALERTNOAUTHOR'), 'warning');
	$app->redirect(htmlspecialchars_decode('index.php?option=com_icagenda&view=icagenda'));
}
