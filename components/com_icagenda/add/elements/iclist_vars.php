<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.9.4 2024-06-13
 *
 * @package     iCagenda.Site
 * @subpackage  Add.elements
 * @link        https://www.icagenda.com
 *
 * @author      Cyril Rezé
 * @copyright   (c) 2012-2024 Cyril Rezé / iCagenda. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 *
 * @since       3.6
 *----------------------------------------------------------------------------
*/

defined('_JEXEC') or die;

/**
 *------------------------------------------------------------------------------
 *	iCagenda Set Var for Theme Packs - List
 *------------------------------------------------------------------------------
*/

$EVENT_TITLE             = $item->titleFormat;
$EVENT_TITLE_HTAG        = (($item->params->get('headerList') < 3) && $this->params->get('show_page_heading')) ? 'h3' : 'h2';
$EVENT_TITLEBAR          = $item->titlebar; // @todo review in utilities icagendaEvent::titleBar
$EVENT_META_AS_SHORTDESC = $item->metaAsShortDesc;
$EVENT_SHORT_DESCRIPTION = $item->shortDescription;
$EVENT_DESCRIPTION       = $item->description;
$AUTO_SHORT_DESCRIPTION  = $item->descShort;
$CATEGORY_FONTCOLOR      = $item->fontColor;

// @todo : Improve this function depending on Filter by Dates and Frontend Search Filters settings.
$filterByDate = $this->params->get('time', 1);

if ($filterByDate == '0')
{
	// All Events
	$dateControl = '';
}
elseif ($filterByDate == '1')
{
	// Current and All Upcoming Events
	$dateControl = '&filter_from=' . JHtml::date('now', 'Y-m-d');
}
elseif ($filterByDate == '2')
{
	// Past Events
	$dateControl = '&filter_to=' . JHtml::date('now', 'Y-m-d');
}
elseif ($filterByDate == '3')
{
	// Upcoming Events
	$dateControl = '&filter_from=' . JHtml::date('now', 'Y-m-d');
}
elseif ($filterByDate == '4')
{
	// Current and Today's Upcoming Events
	$dateControl = '&filter_from=' . JHtml::date('now', 'Y-m-d') . '&filter_to=' . JHtml::date('now', 'Y-m-d');
}

$LIST_FILTERED_BY_CATEGORY_URL = JRoute::_('index.php?option=com_icagenda&view=events&Itemid=' . JFactory::getApplication()->input->get('Itemid')
								. $dateControl
								. '&filter_category=' . $item->cat_id);

$CATEGORY_CLASS = 'ic-bg-' . iCColor::getBrightness($item->cat_color);

$app			= JFactory::getApplication();

$datesDisplay	= $this->params->get('datesDisplay', 1);

$eventTimeZone	= null;
$weekdays       = ($item->weekdays || $item->weekdays == '0') ? true : false;

$this_date		= JHtml::date($evt, 'Y-m-d H:i', $eventTimeZone);
$date_today		= JHtml::date('now', 'Y-m-d');
$dates			= $item->dates ? unserialize($item->dates, ['allowed_classes' => false]) : '';
$dates			= is_array($dates) ? $dates : array();
$period			= $item->period ? unserialize($item->period, ['allowed_classes' => false]) : '';
$period			= is_array($period) ? $period : array();
$is_in_period	= (in_array($this_date, $period)) ? true : false;

if ($is_in_period
	&& $item->weekdays == ''
	&& strtotime($item->startdate) <= strtotime($date_today)
	&& strtotime($item->enddate) >= strtotime($date_today)
	)
{
	$ongoing = true;
}
else
{
	$ongoing = false;
}

// Day in Date Box (list of events)
$EVENT_DAY			= $this->params->get('day_display_global', 1)
					? icagendaEvents::day($evt, $item)
					: false;

// Month in Date Box (list of events)
$EVENT_MONTH		= $this->params->get('month_display_global', 1)
					? icagendaEvents::dateBox($this_date, $this->params->get('event_month_format', 'monthshort'), $ongoing)
					: false;

if ($this->template == 'default'
	&& $this->params->get('event_month_format') == 'month')
{
	$style = '.ic-month {'
			. '  font-size: 12px;'
			. '}'; 
	JFactory::getDocument()->addStyleDeclaration($style);
}

// @deprecated since 3.6.0 (EVENT_MONTHSHORT kept for B/C)
$EVENT_MONTHSHORT	= $this->params->get('month_display_global', 1) ? icagendaEvents::dateBox($this_date, 'monthshort', $ongoing) : false;

// Year in Date Box (list of events)
$EVENT_YEAR			= $this->params->get('year_display_global', 1)
					? icagendaEvents::dateBox($evt, 'year', $ongoing)
					: false;

// Time in Date Box (list of events)
$EVENT_TIME			= ($this->params->get('time_display_global', 0) && $item->displaytime == 1)
					? icagendaRender::dateToTime($evt)
					: false;

// Load Event Data
$EVENT_DATE			= icagendaEvent::nextDate($evt, $item);
//$READ_MORE			= ($this->params->get('shortdesc_display_global', '') == '' && ! $item->shortdesc)
//					? icagendaEvent::readMore($item->url, $item->desc, '[&#46;&#46;&#46;]')
//					: false;

// URL to event details view (list of events)
//$vars   = ( ! $weekdays && in_array($this_date, $period))
//		? array()
//		: array('date' => iCDate::dateToAlias($evt, 'Y-m-d-H-i'));

//$EVENT_URL = icagendaEvent::url($item->id, $item->alias, '', $vars);


// URL to event details view (list of events)
$vars   = (($weekdays && in_array($this_date, $period))
			|| in_array($this_date, $dates))
		? array('date' => iCDate::dateToAlias($evt, 'Y-m-d-H-i'))
		: array();

$EVENT_URL = icagendaEvent::url($item->id, $item->alias, $app->input->get('Itemid'), $vars);

$READ_MORE  = ($this->params->get('shortdesc_display_global', '') == '' && ! $item->shortdesc)
			? icagendaEvent::readMore($EVENT_URL, $item->desc, '[&#46;&#46;&#46;]')
			: false;

// DEV. todo: review url from dates list // echo icagendaRegistration::registerButton($item);

/**
 *	Event Dates
 */
$EVENT_NEXT				= $item->next;

/**
 *	Feature Icons
 */
$FEATURES_ICONSIZE_LIST		= $this->params->get('features_icon_size_list');
$FEATURES_ICONSIZE_EVENT	= $this->params->get('features_icon_size_event');
$SHOW_ICON_TITLE			= $this->params->get('show_icon_title');

// Get iCagenda images path

$imagesPath = icagendaMedia::iCagendaImagesPath();

$FEATURES_ICONROOT_LIST		= JUri::root() . $imagesPath . '/feature_icons/' . $FEATURES_ICONSIZE_LIST . '/';
$FEATURES_ICONROOT_EVENT	= JUri::root() . $imagesPath . '/feature_icons/' . $FEATURES_ICONSIZE_EVENT . '/';
$FEATURES_ICONS				= array();

if (isset($item->features) && is_array($item->features)
	&& (!empty($FEATURES_ICONSIZE_LIST) || !empty($FEATURES_ICONSIZE_EVENT)))
{
	foreach ($item->features as $feature)
	{
		$FEATURES_ICONS[] = array('icon' => $feature->icon, 'icon_alt' => $feature->icon_alt);
	}
}


/**
 *	Event Image and Thumbnails
 */
$EVENT_IMAGE	= $item->image;

if (version_compare(JVERSION, '4.0', 'ge'))
{
	$img         = JHtml::cleanImageURL($EVENT_IMAGE);
	$EVENT_IMAGE = $img->url;
}

//$IMAGE_MEDIUM	= ($EVENT_IMAGE && icagendaClass::isLoaded('icagendaThumb'))
$IMAGE_MEDIUM	= $EVENT_IMAGE
				? icagendaThumb::sizeMedium($EVENT_IMAGE)
				: '';


/**
 *	Events List - Intro Text (TO DO: MIGRATE TO UTILITIES)
 */
$EVENT_DESC = ($item->desc || $item->shortdesc) ? true : false;

$shortdesc_display_global = $this->params->get('shortdesc_display_global', '');
$Filtering_ShortDesc_Global = JComponentHelper::getParams('com_icagenda')->get('Filtering_ShortDesc_Global', '');

if ($shortdesc_display_global == '1') // short desc
{
	$EVENT_DESCSHORT	= $item->shortdesc ? $item->shortdesc : false;

	if ($EVENT_DESCSHORT)
	{
		$EVENT_DESCSHORT	= empty($Filtering_ShortDesc_Global) ? '<i>' . $EVENT_DESCSHORT . '</i>' : $EVENT_DESCSHORT;
	}
}
elseif ($shortdesc_display_global == '2') // Auto-Introtext
{
	$EVENT_DESCSHORT	= $AUTO_SHORT_DESCRIPTION ? $AUTO_SHORT_DESCRIPTION : false;
}
elseif ($shortdesc_display_global == '0') // Hide
{
	$EVENT_DESCSHORT	= false;
}
else // Auto (First Short Description, if does not exist, Auto-generated short description from the full description. And if does not exist, will use meta description if not empty)
{
	$shortDescription = $item->shortdesc ? $item->shortdesc : $AUTO_SHORT_DESCRIPTION;

	$metaAsShortDesc = $EVENT_META_AS_SHORTDESC;

	if ($metaAsShortDesc)
	{
		$metaAsShortDesc	= empty($Filtering_ShortDesc_Global) ? '<i>' . $metaAsShortDesc . '</i>' : $metaAsShortDesc;
	}

	$EVENT_DESCSHORT	= $shortDescription ? $shortDescription : $metaAsShortDesc;
}

$EVENT_INTRO_TEXT = $EVENT_DESCSHORT; // New var name since 3.4.0



$EVENT_VENUE			= $this->params->get('venue_display_global', 1) ? $item->place : false;
$EVENT_POSTAL_CODE		= $this->params->get('city_display_global', 1) ? $item->city : false;
$EVENT_CITY				= $this->params->get('city_display_global', 1) ? $item->city : false;
$EVENT_COUNTRY			= $this->params->get('country_display_global', 1) ? $item->country : false;

$CATEGORY_TITLE			= JText::_($item->cat_title);
$CATEGORY_COLOR			= $item->cat_color;


/**
 *	Add Event Info from plugins (if exists)
 */
$onListAddEventInfo = JFactory::getApplication()->triggerEvent('onListAddEventInfo', array('com_icagenda.list', &$item, &$this->params));

$IC_LIST_ADD_EVENT_INFO = '';

foreach ($onListAddEventInfo as $added_info)
{
	$IC_LIST_ADD_EVENT_INFO.= '<div class="ic-list-add-event-info">' . $added_info . '</div>';
}



// B/C Theme Packs (to be checked, and added if needed)
if ( ! in_array($this->template, array('default', 'ic_rounded')))
{
	$item->place_name				= $item->place;
	$item->startdatetime			= $item->startdate;
	$item->enddatetime				= $item->enddate;

	$item->startDate				= icagendaRender::dateToFormat($item->startdate);
	$item->endDate					= icagendaRender::dateToFormat($item->enddate);

	$item->startTime				= icagendaRender::dateToTime($item->startdate);
	$item->endTime					= icagendaRender::dateToTime($item->enddate);

	$item->day						= $EVENT_DAY;
	$item->monthShort				= $EVENT_MONTHSHORT;
	$item->year						= $EVENT_YEAR;
	$item->evenTime					= $EVENT_TIME;

	$this->atlist					= '';

	// To be checked
	$CUSTOM_FIELDS				= icagendaEventData::loadEventCustomFields($item->id);
}

// Deprecated 3.8.0
$EVENT_SET_DATE = icagendaEvent::urlDateVar($evt);
