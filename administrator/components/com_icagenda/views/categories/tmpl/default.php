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
 * @copyright   (c) 2012-2024 Jooml!C / Cyril Rezé. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 *
 * @since       1.0.0
 *----------------------------------------------------------------------------
*/

defined('_JEXEC') or die;

$app = JFactory::getApplication();

// Access Administration Categories check.
if (JFactory::getUser()->authorise('icagenda.access.categories', 'com_icagenda'))
{
	$user = JFactory::getUser();
	$userId = $user->get('id');
	$listOrder = $this->escape($this->state->get('list.ordering'));
	$listDirn = $this->escape($this->state->get('list.direction'));
	$canOrder = $user->authorise('core.edit.state', 'com_icagenda');

	$saveOrder	= $listOrder == 'a.ordering';

	// Include the component HTML helpers.
	JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');
	JHtml::_('bootstrap.tooltip');
	JHtml::_('behavior.multiselect');
	JHtml::_('formbehavior.chosen', 'select');
	JHtml::_('dropdown.init');

	$extension = $this->escape($this->state->get('filter.extension'));

	$archived  = $this->state->get('filter.published') == 2 ? true : false;
	$trashed   = $this->state->get('filter.published') == -2 ? true : false;

	if ($saveOrder)
	{
		$saveOrderingUrl = 'index.php?option=com_icagenda&task=categories.saveOrderAjax&tmpl=component';
		JHtml::_('sortablelist.sortable', 'categoriesList', 'adminForm', strtolower($listDirn), $saveOrderingUrl, false, true);
	}
	$sortFields = array(); // Alchemy - tmp bug fix
	?>
	<script type="text/javascript">
		Joomla.orderTable = function()
		{
			table = document.getElementById("sortTable");
			direction = document.getElementById("directionTable");
			order = table.options[table.selectedIndex].value;
			if (order != '<?php echo $listOrder; ?>')
			{
				dirn = 'asc';
			}
			else
			{
				dirn = direction.options[direction.selectedIndex].value;
			}
			Joomla.tableOrdering(order, dirn, '');
		}
	</script>

	<form action="<?php echo JRoute::_('index.php?option=com_icagenda&view=categories'); ?>" method="post" name="adminForm" id="adminForm">
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
					<label for="filter_search" class="element-invisible"><?php echo JText::_('COM_ICAGENDA_FILTER_SEARCH_CATEGORIES_DESC'); ?></label>
					<input type="text" name="filter_search" placeholder="<?php echo JText::_('COM_ICAGENDA_FILTER_SEARCH_CATEGORIES_DESC'); ?>" id="filter_search" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" title="<?php echo JText::_('COM_ICAGENDA_FILTER_SEARCH_CATEGORIES_DESC'); ?>" />
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

			<table class="table table-striped" id="categoriesList">

				<thead>
					<tr>
						<th width="1%" class="nowrap center hidden-phone">
							<?php echo JHtml::_('grid.sort', '<i class="icon-menu-2"></i>', 'a.ordering', $listDirn, $listOrder, null, 'asc', 'JGRID_HEADING_ORDERING'); ?>
						</th>
						<th width="1%" class="hidden-phone">
							<input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
						</th>
						<th width="1%" style="min-width:55px" class="nowrap center">
							<?php echo JHtml::_('grid.sort', 'JSTATUS', 'a.state', $listDirn, $listOrder); ?>
						</th>
						<th width="5%" class="nowrap hidden-phone">
							<?php echo JHtml::_('grid.sort',  'COM_ICAGENDA_CATEGORIES_COLOR', 'a.color', $listDirn, $listOrder); ?>
						</th>
						<th>
							<?php echo JHtml::_('grid.sort',  'COM_ICAGENDA_CATEGORIES_TITLE', 'a.title', $listDirn, $listOrder); ?>
						</th>
						<th width="1%" class="nowrap hidden-phone">
							<?php echo JHtml::_('grid.sort', 'JGRID_HEADING_ID', 'a.id', $listDirn, $listOrder); ?>
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
				<?php foreach ($this->items as $i => $item) :
					$ordering	= ($listOrder == 'a.ordering');
					$canCreate	= $user->authorise('core.create', 'com_icagenda');
					$canEdit	= $user->authorise('core.edit', 'com_icagenda');
					$canCheckin	= $user->authorise('core.manage', 'com_icagenda');
					$canChange	= $user->authorise('core.edit.state', 'com_icagenda');
					$canEditOwn	= $user->authorise('core.edit.own', 'com_icagenda');
					?>
					<tr class="row<?php echo $i % 2; ?>">
						<td class="order nowrap center hidden-phone">
							<?php if ($canChange) :
								$disableClassName = '';
								$disabledLabel	  = '';
								if (!$saveOrder) :
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
								<?php echo JHtml::_('jgrid.published', $item->state, $i, 'categories.', $canChange, 'cb'); ?>
							</td>
						<?php endif; ?>
						<td class="small hidden-phone">
							<div style="display:block; width:50px; height:40px; border-radius:5px; background:<?php echo $item->color; ?>;"></div>
						</td>
						<td class="nowrap has-context">
							<div class="pull-left">
								<?php if ($item->checked_out) : ?>
									<?php echo JHtml::_('jgrid.checkedout', $i, $item->editor, $item->checked_out_time, 'categories.', $canCheckin); ?>
								<?php endif; ?>
								<?php if ($canEdit) : ?>
									<a href="<?php echo JRoute::_('index.php?option=com_icagenda&task=category.edit&id=' . $item->id); ?>" title="<?php echo JText::_('JACTION_EDIT'); ?>">
										<?php echo $this->escape($item->title); ?>
									</a>
								<?php else : ?>
									<span title="<?php echo JText::sprintf('JFIELD_ALIAS_LABEL', $this->escape($item->alias)); ?>">
										<?php echo $this->escape($item->title); ?>
									</span>
								<?php endif; ?>
							</div>
							<div class="pull-left">
								<?php
								// Create dropdown items
								JHtml::_('dropdown.edit', $item->id, 'category.');
								JHtml::_('dropdown.divider');
								if ($item->state) :
									JHtml::_('dropdown.unpublish', 'cb' . $i, 'categories.');
								else :
									JHtml::_('dropdown.publish', 'cb' . $i, 'categories.');
								endif;
								JHtml::_('dropdown.divider');
								if ($archived) :
									JHtml::_('dropdown.unarchive', 'cb' . $i, 'categories.');
								else :
									JHtml::_('dropdown.archive', 'cb' . $i, 'categories.');
								endif;
								if ($item->checked_out) :
									JHtml::_('dropdown.checkin', 'cb' . $i, 'categories.');
								endif;
								if ($trashed) :
									JHtml::_('dropdown.untrash', 'cb' . $i, 'categories.');
								else :
									JHtml::_('dropdown.trash', 'cb' . $i, 'categories.');
								endif;
								// Render dropdown list
								echo JHtml::_('dropdown.render');
								?>
							</div>
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
