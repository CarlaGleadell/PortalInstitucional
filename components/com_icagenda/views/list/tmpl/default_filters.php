<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.9.4.1 2024-06-18
 *
 * @package     iCagenda.Site
 * @link        https://www.icagenda.com
 *
 * @author      Cyril Rezé
 * @copyright   (c) 2012-2024 Cyril Rezé / iCagenda. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 *
 * @since       3.6.0
 *----------------------------------------------------------------------------
*/

defined('_JEXEC') or die;

$app        = JFactory::getApplication();
$document   = JFactory::getDocument();
$jinput     = $app->input;
$menuParams = $app->getMenu()->getActive()->params;
$iCparams   = JComponentHelper::getParams('com_icagenda');

JFormHelper::addFieldPath(JPATH_COMPONENT . '/models/fields');

// Get Category options
$categories         = JFormHelper::loadFieldType('Categories', false);
$categoryOptions    = $categories->getOptions();

foreach ($categoryOptions as $k => $v) {
	$v->cattitle = JText::_($v->cattitle);
}

// Get Month options
$month              = JFormHelper::loadFieldType('Month', false);
$monthOptions       = $month->getOptions();

// Get Year options
$year               = JFormHelper::loadFieldType('Year', false);
$yearOptions        = $year->getOptions();

// Params
$menuOptions        = ($menuParams->get('search_filters') == 1);
$menuSuffix         = $menuOptions ? '_menu' : '';

$icfilterDisplay    = $menuOptions
					? $this->params->get('filters_display', '2')
					: $iCparams->get('filters_display', '2');

// Display option (responsive grid or full width)
$icDisplayClass     = ($icfilterDisplay == '1') ? ' ic-col-full' : '';

// Label "Search"
$icfilterLabel      = $menuOptions
					? $this->params->get('filters_label', '1')
					: $iCparams->get('filters_label', '1');

if ($icfilterLabel)
{
	$icfilterLabel  = ($icfilterDisplay == '1') ? '12' : '4'; // 4=columns, 12=full width
}

// Get config ordering for filters
$default_ordering   = 'filter_search,filter_from,filter_to,filter_category,filter_month,filter_year';
$filter_ordering    = $menuOptions
					? $this->params->get('filter_ordering', $default_ordering)
					: $iCparams->get('filter_ordering', $default_ordering);
$icfilterList       = explode(',', $filter_ordering);

// Get Filters
$filter_search      = $menuOptions
					? ($this->params->get('filter_search', 2) * 6)
					: ($iCparams->get('filter_search', 2) * 6);
$filter_from        = $menuOptions
					? ($this->params->get('filter_from', 1) * 6)
					: ($iCparams->get('filter_from', 1) * 6);
$filter_to          = $menuOptions
					? ($this->params->get('filter_to', 1) * 6)
					: ($iCparams->get('filter_to', 1) * 6);
$filter_category    = $menuOptions
					? ($this->params->get('filter_category', 1) * 6)
					: ($iCparams->get('filter_category', 1) * 6);
$filter_month       = $menuOptions
					? ($this->params->get('filter_month', 1) * 6)
					: ($iCparams->get('filter_month', 1) * 6);
$filter_year        = $menuOptions
					? ($this->params->get('filter_year', 1) * 6)
					: ($iCparams->get('filter_year', 1) * 6);

$button_more        = 6;

$filters = $fields = $total = $option1 = $lastMain = '0';

foreach ($icfilterList as $filter)
{
	// Filter is disabled
	if ( ! ${$filter})
	{
		$key = array_search($filter, $icfilterList);
		unset($icfilterList[$key]);
	}

	// Filter is enabled
	else
	{
		$total    = $total + (${$filter} / 6);

		if ($total <= 6)
		{
			$filters  = $filters + 1;
			$fields   = $fields + (${$filter} / 6);
		}
	}
}

$adjustTotal      = ($total > 6) ? ($fields - $filters) : '0';
$countMainFilters = $filters - $adjustTotal;

$mainFilters      = array_slice($icfilterList, 0, $countMainFilters);

if ($total > 6)
{
	$mainFilters[]    = 'button_more';
}

$optionsFilters   = array_slice($icfilterList, $countMainFilters);


// Full Width
if ($icfilterDisplay == 1)
{
	$colList      = '12';
	$rowLabel     = '1';
	$rowSubmit    = '2';
	$rowReset     = '1';

	$colFilters   = '12';
	$colMore      = '12';
	$colControls  = '12';
	$colSubmit    = '6';
	$colReset     = '6';
}

// Responsive Extendable Grid of 12
else
{
	$colList      = $icfilterLabel ? '8' : '12';
	$rows         = round($total/2, 0, PHP_ROUND_HALF_UP);
	$rowLabel     = ($rows <= 3) ? $rows : '3';
	$rowSubmit    = ($total > 4) ? '2' : '1';
	$rowReset     = '1';

	$colFilters   = '9';
	$colMore      = '16';
	$colControls  = '3';
	$colSubmit    = ($total <= 2) ? '8' : '12';
	$colReset     = ($total <= 2) ? '4' : '12';
}

$script = array();

// Add script declaration
$script[] = '	jQuery(document).ready(function($){';
$script[] = '		$("#ic-toggle-filters").click(function(){';
$script[] = '			$("#ic-more-filters").toggle("500",';
$script[] = '				function(){';
$script[] = '					if ($(this).is(":visible")) {';
$script[] = '						$(".ic-filter-more-btn").removeClass("ic-inactive").addClass("ic-active");';
$script[] = '						$(".ic-filter-more-btn > span").removeClass("iCicon-arrow-down").addClass("iCicon-arrow-up");';
$script[] = '					} else {';
$script[] = '						$(".ic-filter-more-btn").removeClass("ic-active").addClass("ic-inactive");';
$script[] = '						$(".ic-filter-more-btn > span").removeClass("iCicon-arrow-up").addClass("iCicon-arrow-down");';
$script[] = '					}';
$script[] = '				}';
$script[] = '			);';
$script[] = '		});';

foreach ($optionsFilters as $filter)
{
	$script[] = '		if($("#' . $filter . '").val() !== ""){';
	$script[] = '			$("#ic-more-filters").css("display", "block");';
	$script[] = '			$(".ic-filter-more-btn > span").removeClass("iCicon-arrow-down").addClass("iCicon-arrow-up");';
	$script[] = '			$(".ic-filter-more-btn").removeClass("ic-inactive").addClass("ic-active");';
	$script[] = '		}';
}

$script[] = '	});';


$script[] = 'jQuery(document).ready(function($){';

$script[] = '  $("#icagenda-list").submit(function() {';
$script[] = '    $(this).find(":input:not([type=hidden])").filter(function(){ return !this.value; }).attr("disabled", "disabled");';
$script[] = '    return true;';
$script[] = '  });';

$script[] = '  $("#icagenda-list").find(":input:not([type=hidden])").attr("disabled", false);';

$script[] = '  $(".ic-filter-reset-btn").click(function(){';
$script[] = '    $("#icagenda-list").find(":input:not([type=hidden])").val("");';
$script[] = '    $("#icagenda-list").submit();';
$script[] = '  });';

$script[] = '});';


$document->addScriptDeclaration(implode("\n", $script));


// Filters HTML rendering
$html_filter_search     = '<input id="filter_search" name="filter_search" type="search"'
						. ' value="' . htmlspecialchars($jinput->getString('filter_search', ''), ENT_COMPAT, 'UTF-8') . '"'
						. ' title="' . htmlspecialchars(JText::_('COM_ICAGENDA_FILTERS_SEARCH_PLACEHOLDER'), ENT_COMPAT, 'UTF-8') . '"'
						. ' placeholder="' . htmlspecialchars(JText::_('COM_ICAGENDA_FILTERS_SEARCH_PLACEHOLDER'), ENT_COMPAT, 'UTF-8') . '" />';

$html_filter_from       = $filter_from
						? JHtml::_('calendar', $jinput->get('filter_from', ''), 'filter_from', 'filter_from', '%Y-%m-%d',
						array('placeholder' => JText::_('COM_ICAGENDA_FILTERS_PERIOD_FROM'), 'class' => ''))
						: '';

$html_filter_to         = $filter_to
						? JHtml::_('calendar', $jinput->get('filter_to', ''), 'filter_to', 'filter_to', '%Y-%m-%d',
						array('placeholder' => JText::_('COM_ICAGENDA_FILTERS_PERIOD_TO'), 'class' => ''))
						: '';

$html_filter_category   = '<select class="ic-input-small" id="filter_category" name="filter_category">
						<option value="">' . JText::_('COM_ICAGENDA_FILTERS_SELECT_CATEGORY') . '</option>
						' . JHtml::_('select.options', $categoryOptions, 'catid', 'cattitle',
						$jinput->getInt('filter_category', '')) . '</select>';

$html_filter_month      = '<select class="ic-input-small" id="filter_month" name="filter_month">
						<option value="">' . JText::_('COM_ICAGENDA_FILTERS_SELECT_MONTH') . '</option>
						' . JHtml::_('select.options', $monthOptions, 'value', 'label',
						$jinput->getInt('filter_month', '')) . '</select>';

$html_filter_year       = '<select class="ic-input-small" id="filter_year" name="filter_year">
						<option value="">' . JText::_('COM_ICAGENDA_FILTERS_SELECT_YEAR') . '</option>
						' . JHtml::_('select.options', $yearOptions, 'value', 'label',
						$jinput->getInt('filter_year', '')) . '</select>';

$html_button_more       = '<div class="ic-filter-more-btn" id="ic-toggle-filters">'
						. JText::_('COM_ICAGENDA_FILTERS_MORE_OPTIONS')
						. ' <span class="iCicon iCicon-arrow-down"></span></div>';
?>

<div class="ic-filters ic-clearfix">
	<div class="ic-col-<?php echo $colFilters; ?>">

		<?php // Label ?>
		<?php if ($icfilterLabel) : ?>
		<div class="ic-col-<?php echo $icfilterLabel; ?>">
			<div class="ic-filters-label ic-cell">
				<div class="ic-filters-label-title-<?php echo $rowLabel; ?>"><?php echo JText::_('COM_ICAGENDA_FILTERS'); ?></div>
			</div>
		</div>
		<?php endif; ?>

		<?php // Filters ?>
		<div class="ic-col-<?php echo $colList . $icDisplayClass; ?>">

			<?php foreach ($mainFilters as $filter) : ?>
			<div class="ic-col-<?php echo ${$filter} . $icDisplayClass; ?>">
				<div class="ic-<?php echo str_replace('_', '-', $filter); ?> ic-cell">
					<div class="ic-row1">
						<?php echo ${"html_" . $filter}; ?>
					</div>
				</div>
			</div>
			<?php endforeach; ?>

		</div>

		<?php // More Options ?>
		<?php if ($total >= 6) : ?>
		<div id="ic-more-filters" class="ic-col-<?php echo $colMore . $icDisplayClass; ?>" style="display: none;">

			<?php foreach ($optionsFilters as $filter) : ?>
			<div class="ic-col-<?php echo (${$filter} / 2) . $icDisplayClass; ?>">
				<div class="ic-<?php echo str_replace('_', '-', $filter); ?> ic-cell">
					<div class="ic-row1">
						<?php echo ${"html_" . $filter}; ?>
					</div>
				</div>
			</div>
			<?php endforeach; ?>

		</div>
		<?php endif; ?>

	</div>
	<div class="ic-col-<?php echo $colControls; ?> ic-fluid">

		<?php // Buttons ?>
		<div class="ic-search-submit ic-col-<?php echo $colSubmit . $icDisplayClass; ?>">
			<div class="ic-filters-controls ic-control-submit ic-cell">
				<button class="ic-filter-submit-btn ic-filters-btn-<?php echo $rowSubmit; ?>"
					name="<?php echo JText::_('COM_ICAGENDA_FILTERS_SUBMIT'); ?>"
					type="submit">
					<span class="iCicon iCicon-search"></span>
					<?php if ($total > 2 || $icfilterDisplay == 1) : ?>
						<?php echo JText::_('COM_ICAGENDA_FILTERS_SUBMIT'); ?>
					<?php endif; ?>
				</button>
			</div>
		</div>
		<div class="ic-search-reset ic-col-<?php echo $colReset . $icDisplayClass; ?>">
			<div class="ic-filters-controls ic-control-reset ic-cell">
				<button class="ic-filter-reset-btn ic-filters-btn-<?php echo $rowReset; ?>"
					name="<?php echo JText::_('COM_ICAGENDA_FILTERS_RESET'); ?>"
					type="reset">
					<span class="iCicon iCicon-reset"> </span>
					<?php if ($total > 2 || $icfilterDisplay == 1) : ?>
						<?php echo JText::_('COM_ICAGENDA_FILTERS_RESET'); ?>
					<?php endif; ?>
				</button>
			</div>
		</div>

	</div>
</div>
