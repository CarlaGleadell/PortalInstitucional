<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.8.22 2023-11-06
 *
 * @package     iCagenda.Admin
 * @subpackage  tmpl.features
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

use iCutilities\Media\Media as icagendaMedia;
use Joomla\CMS\Component\ComponentHelper;
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

if ($saveOrder && ! empty($this->items))
{
	$saveOrderingUrl = 'index.php?option=com_icagenda&task=features.saveOrderAjax&tmpl=component&' . Session::getFormToken() . '=1';
	HTMLHelper::_('draggablelist.draggable');
}

// Disable forced table accent background from Atum template to use contextual classes to color tables, table rows or individual cells.
Factory::getDocument()->addStyleDeclaration('.table { --table-accent-bg: none; }');

// Get icagenda images path
$imagesPath = icagendaMedia::iCagendaImagesPath();
?>

<form action="<?php echo Route::_('index.php?option=com_icagenda&view=features'); ?>" method="post" name="adminForm" id="adminForm">
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
							<?php echo Text::_('COM_ICAGENDA_FEATURES_TABLE_CAPTION'); ?>,
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
									<?php echo HTMLHelper::_('searchtools.sort', 'JSTATUS', 'a.state', $listDirn, $listOrder); ?>
								</th>
								<th scope="col" style="min-width:100px">
									<?php echo HTMLHelper::_('searchtools.sort', 'COM_ICAGENDA_FEATURES_TITLE', 'a.title', $listDirn, $listOrder); ?>
								</th>
								<th scope="col">
									<?php echo HTMLHelper::_('searchtools.sort', 'COM_ICAGENDA_FEATURES_ICON', 'a.icon', $listDirn, $listOrder); ?>
								</th>
								<th scope="col" class="w-10">
									<?php echo HTMLHelper::_('searchtools.sort', 'COM_ICAGENDA_FORM_FEATURE_ICON_ALT_LABEL', 'a.icon_alt', $listDirn, $listOrder); ?>
								</th>
								<th scope="col" class="w-3 text-center">
									<?php echo HTMLHelper::_('searchtools.sort', 'COM_ICAGENDA_FEATURES_SHOW_FILTER', 'a.show_filter', $listDirn, $listOrder); ?>
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
//								$canEditOwn = $user->authorise('core.edit.own',   'com_icagenda') && $item->created_by == $userId;
								$canChange  = $user->authorise('core.edit.state', 'com_icagenda') && $canCheckin;
								?>
								<tr class="row<?php echo $i % 2; ?>" data-draggable-group="none" item-id="<?php echo $item->id ?>">
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
										<?php echo HTMLHelper::_('jgrid.published', $item->state, $i, 'customfields.', $canChange); ?>
									</td>
									<th scope="row" class="has-context">
										<div class="break-word">
											<?php if ($item->checked_out) : ?>
												<?php echo HTMLHelper::_('jgrid.checkedout', $i, $item->editor, $item->checked_out_time, 'features.', $canCheckin); ?>
											<?php endif; ?>
											<?php //if ($canEdit || $canEditOwn) : ?>
											<?php if ($canEdit) : ?>
												<a href="<?php echo Route::_('index.php?option=com_icagenda&task=feature.edit&id=' . $item->id); ?>" title="<?php echo Text::_('JACTION_EDIT'); ?> <?php echo $this->escape($item->title); ?>">
													<?php echo $this->escape($item->title); ?>
												</a>
											<?php else : ?>
												<span title="<?php echo Text::sprintf('JFIELD_ALIAS_LABEL', $this->escape($item->alias)); ?>"><?php echo $this->escape($item->title); ?></span>
											<?php endif; ?>
											<span class="d-table-cell d-md-none">
												<?php if ($item->slug) : ?>
													<span class="small">
														<?php echo Text::sprintf('JGLOBAL_LIST_ALIAS', $this->escape($item->slug)); ?>
													</span>
												<?php endif; ?>
											</span>
										</div>
									</th>
									<td>
										<div>
											<?php echo '<img src="../' . $imagesPath . '/feature_icons/24_bit/' . $item->icon . '" alt="[' . $item->icon . ']" />'; ?>
											<?php echo $item->icon == -1 ? Text::_('JOPTION_DO_NOT_USE') : $item->icon; ?>
										</div>
									</td>
									<td>
										<div>
											<?php echo $this->escape($item->icon_alt) ?>
										</div>
									</td>
									<td class="text-center">
										<div>
											<i class="icon-<?php echo $item->show_filter ? 'publish' : 'unpublish';// Note:'publish/unpublish' preferred to 'checkmark/cancel' because of colour ?>"></i>
										</div>
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
