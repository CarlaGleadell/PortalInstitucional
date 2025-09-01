<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.9.4 2024-06-13
 *
 * @package     iCagenda.Site
 * @subpackage  Add.Elements
 * @link        https://www.icagenda.com
 *
 * @author      Cyril Rezé
 * @copyright   (c) 2012-2024 Cyril Rezé / iCagenda. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 *
 * @since       3.6
 *----------------------------------------------------------------------------
*/

defined('_JEXEC') or die();

use iCutilities\Maps\Maps as icagendaMaps;

/**
 * Event view
 * Defines variables for use with Theme Packs
*/
$app     = JFactory::getApplication();
$session = JFactory::getSession();
$input   = $app->input;

$EVENT_TITLE					= icagendaRender::titleToFormat($item->title);
$EVENT_META_AS_SHORTDESC		= iCFilterOutput::fullCleanHTML($item->metadesc);
$EVENT_SHORT_DESCRIPTION		= JHtml::_('content.prepare', $item->shortdesc, $this->params, 'com_icagenda.event');
$EVENT_DESCRIPTION				= JHtml::_('content.prepare', $item->desc, $this->params, 'com_icagenda.event');
$AUTO_SHORT_DESCRIPTION			= icagendaEvents::shortDescription($item->desc); // Function to be renamed
$EVENT_SHARING					= icagendaAddthis::sharing($item); // @deprecated 3.8.18 - removed 4.0

$CUSTOM_FIELDS					= icagendaEvent::getCustomFields($item->id);
$EVENT_INFO						= icagendaEvent::infoDetails($item, $CUSTOM_FIELDS);
$EVENT_INFOS					= $EVENT_INFO; // TODO: changed official Theme Packs (Do not remove for B/C)

$EVENT_EMAIL_CLOAKING			= $item->email ? icagendaRender::emailTag($item->email) : '';
$EVENT_WEBSITE_LINK				= icagendaRender::websiteTag($item->website);
$GOOGLEMAPS_COORDINATES			= icagendaMaps::display($item); // @todo: change tag and migrate function to icagendaMaps
$EVENT_MAP						= icagendaMaps::map($item);
$EVENT_SINGLE_DATES				= icagendaEvent::displayListSingleDates($item);
$EVENT_PERIOD					= icagendaEvent::displayPeriodDates($item);
$EVENT_ATTACHEMENTS_TAG			= icagendaRender::fileTag($item->file);
$EVENT_IMAGE_TAG				= $item->image ? icagendaThumb::sizeLarge($item->image, 'imgTag', true) : '';

// Data from database
$CATEGORY_TITLE			= JText::_($item->cat_title);
$CATEGORY_COLOR			= $item->cat_color;
$EVENT_ATTACHEMENTS		= $item->file;
$EVENT_EMAIL			= $item->email;
$EVENT_IMAGE			= $item->image; // TODO: Check old icmodel function if needed...
$EVENT_NEXT				= $item->next;
$EVENT_PHONE            = icagendaRender::phoneTag($item->phone);
$EVENT_WEBSITE			= $item->website;


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
if (version_compare(JVERSION, '4.0', 'ge'))
{
	$img         = JHtml::cleanImageURL($EVENT_IMAGE);
	$EVENT_IMAGE = $img->url;
}

//$IMAGE_MEDIUM	= ($EVENT_IMAGE && icagendaClass::isLoaded('icagendaThumb'))
$IMAGE_LARGE_HTML	= $EVENT_IMAGE
					? icagendaThumb::sizeLarge($EVENT_IMAGE, 'imgTag', true)
					: '';


/**
 *	Description, Meta-description and Intro Text
 */
$EVENT_DESC				= ($item->desc || $item->shortdesc) ? true : false;
$EVENT_META				= $EVENT_META_AS_SHORTDESC;

$desc_display_event = $this->params->get('desc_display_event', '');

// Full description
if ($desc_display_event == '1')
{
	$EVENT_SHORTDESC	= false;
	$EVENT_DESCRIPTION	= $EVENT_DESCRIPTION ? $EVENT_DESCRIPTION : false;
}

// Short description
elseif ($desc_display_event == '2')
{
	$EVENT_SHORTDESC	= $EVENT_SHORT_DESCRIPTION ? $EVENT_SHORT_DESCRIPTION : false;
	$EVENT_DESCRIPTION	= false;
}

// Short and full descriptions
elseif ($desc_display_event == '3')
{
	$EVENT_SHORTDESC	= $EVENT_SHORT_DESCRIPTION ? $EVENT_SHORT_DESCRIPTION : false;
	$EVENT_DESCRIPTION	= $EVENT_DESCRIPTION ? $EVENT_DESCRIPTION : false;
}

// Hide
elseif ($desc_display_event == '0')
{
	$EVENT_SHORTDESC	= false;
	$EVENT_DESC			= false;
	$EVENT_DESCRIPTION	= false;
}

// Auto (First Full Description, if does not exist, will use Short Description if not empty)
else
{
	$EVENT_SHORTDESC	= false;
	$EVENT_DESCRIPTION	= $EVENT_DESCRIPTION ? $EVENT_DESCRIPTION : $EVENT_SHORT_DESCRIPTION;
}


/**
 *	Event Address
 */
$EVENT_VENUE			= $this->params->get('venue_display_global') ? $item->place : false;
$EVENT_CITY				= $this->params->get('city_display_global') ? $item->city : false;
$EVENT_COUNTRY			= $this->params->get('country_display_global') ? $item->country : false;

if ($item->address)
{
	// Create an array to separate all strings between comma in individual parts
	$EVENT_STREET		= $item->address;
	$ADDRESS_EX			= explode(',', $EVENT_STREET);

	$country_to_check	= ($EVENT_COUNTRY == 'United States') ? 'USA' : $EVENT_COUNTRY;
	$country_removed	= false;
	$city_removed		= false;

	$streetAndCity = $street = '';

	$i = 0;
	$count_ADDRESS_EX = count($ADDRESS_EX);

	for ($i; $i < $count_ADDRESS_EX; $i++)
	{
		// Remove the country from the full address
		if ($EVENT_COUNTRY && ! $country_removed
			&& strpos($EVENT_STREET, $country_to_check) !== false)
		{
			$country_removed	= true;

			// Remove country
			$EVENT_STREET		= substr( $EVENT_STREET, 0, strripos( $EVENT_STREET, ',' ) );
			$streetAndCity		= $EVENT_STREET;
		}
		elseif ($EVENT_CITY && ! $city_removed
			&& strpos($EVENT_STREET, $EVENT_CITY) !== false)
		{
			$city_removed		= true;

			// Remove last value, until city is not found in the string
			$EVENT_STREET		= substr( $EVENT_STREET, 0, strripos( $EVENT_STREET, ',' ) );
			$street				= $EVENT_STREET;

			if (str_replace($street, '', $streetAndCity) != $EVENT_CITY)
			{
				$EVENT_CITY_POSTALCODE = str_replace($street, '', $streetAndCity);
				$EVENT_CITY_POSTALCODE = str_replace(', ', '', $EVENT_CITY_POSTALCODE);
			}
		}
	}

	$EVENT_CITY_POSTALCODE = isset($EVENT_CITY_POSTALCODE) ? $EVENT_CITY_POSTALCODE : $EVENT_CITY;


	// Generate EVENT_ADDRESS Html tag
	$EVENT_ADDRESS = $EVENT_STREET ? $EVENT_STREET . '<br />' : '';

	if ($EVENT_CITY_POSTALCODE && $EVENT_COUNTRY)
	{
		$EVENT_ADDRESS.= $EVENT_CITY_POSTALCODE . ', ' . $EVENT_COUNTRY . '<br />';
	}
	elseif ( ! $EVENT_COUNTRY && $EVENT_CITY_POSTALCODE)
	{
		$EVENT_ADDRESS.= $EVENT_CITY_POSTALCODE . '<br />';
	}
	elseif ( ! $EVENT_CITY_POSTALCODE && $EVENT_COUNTRY)
	{
		$EVENT_ADDRESS.= $EVENT_COUNTRY . '<br />';
	}
}
else
{
	$EVENT_ADDRESS = false;
}


/**
 *	Set Date from session
 */
$view = $input->get('view');

if ($view == 'event')
{
	$get_date   = $input->get('date', '');
	$event_date = $session->get('event_date', '');

	if  ( ! $get_date
		&& ! $event_date)
	{
		$get_date = date('Y-m-d-H-i', strtotime(icagendaEvent::getNextDate($item)));
	}
	elseif ( ! $get_date
		&& $event_date)
	{
		$get_date = date('Y-m-d-H-i', strtotime($event_date));
	}
	elseif ( ! $get_date
		&& iCDate::isDate($item->startdate)
		&& iCDate::isDate($item->enddate)
		)
	{
		$get_date = date('Y-m-d-H-i', strtotime($item->startdate));
		$session->set('event_date', '');
	}
}
else
{
	$session_date = $session->get('session_date', '');
	$get_date     = date('Y-m-d-H-i', strtotime($session_date));
}

if ($get_date)
{
	$ex = explode('-', $get_date);

	if (count($ex) == 5)
	{
		$dateday = $ex['0'].'-'.$ex['1'].'-'.$ex['2'].' '.$ex['3'].':'.$ex['4'];
	}
	else
	{
		$dateday = '';
	}
}

$TEXT_FOR_NEXTDATE = icagendaEvent::dateText($item);

$timeformat  = $this->params->get('timeformat');
$timedisplay = $item->displaytime;

if ($get_date)
{
//	$item->registered = icagendaRegistration::getRegisteredTotal($item->id, date('Y-m-d H:i:s', strtotime($dateday)), $item->params->get('typeReg', '1'));
	$EVENT_NEXTDATE  = icagendaEvent::nextDate($dateday, $item);

	$EVENT_THIS_DATE = icagendaRender::dateToFormat($dateday);

	$weekdays_array = $item->weekdays ? explode (',', $item->weekdays) : '';
	$weekdays       = $weekdays_array ? count($weekdays_array) : '';

	$period_array	= unserialize($item->period);
	$period_array	= is_array($period_array) ? $period_array : array();

	if ( ! $weekdays
		&& in_array($dateday, $period_array) ) {
		// Full period (no weekdays selected)
		$EVENT_VIEW_DATE_LABEL = JTEXT::_('COM_ICAGENDA_EVENT_DATES_LABEL');
	} else {
		// Single date
		$EVENT_VIEW_DATE_LABEL = JTEXT::_('COM_ICAGENDA_EVENT_DATES_LABEL_1');
	}

	$EVENT_VIEW_DATE_TEXT = JTEXT::_('COM_ICAGENDA_EVENT_DATE');

	$EVENT_VIEW_DATE = $EVENT_NEXTDATE;

	$vars = array('date' => iCDate::dateToAlias($dateday, 'Y-m-d-H-i'));
}

// If multiple dates and Display all dates OFF: Use Next date
else
{
	$EVENT_NEXTDATE         = icagendaEvent::nextDate($item->next, $item);

	$EVENT_VIEW_DATE_TEXT	= $TEXT_FOR_NEXTDATE;
	$EVENT_VIEW_DATE = $EVENT_NEXTDATE;

	$item->registered       = icagendaRegistration::getRegisteredTotal($item->id, $item->next, $item->params->get('typeReg', '1'));

	$session->set('event_date', $item->next);

	$vars = array('date' => iCDate::dateToAlias($item->next, 'Y-m-d-H-i'));
}

$EVENT_URL = ($view == 'registration')
			? $this->eventURL
			: icagendaEvent::url($item->id, $item->alias, $app->input->get('Itemid'), $vars);

/**
 *	Registration info
 */
$maxNbTickets	= $item->params->get('maxReg', '1000000'); // TODO: check all functions using max nb of tickets, and replace by empty for unlimited
$statutReg		= $item->params->get('statutReg', '');

// TEST $item->listTicketsAvailable = icagendaRegistration::getListTicketsAvailable($item->id, $item->params->get('typeReg', '1'), $maxNbTickets);

// Set Registration button + registered people info
if ($input->get('tmpl') != 'component'
	&& $input->get('print') != 1
	&& ! $item->params->get('event_cancelled')
	)
{
	$EVENT_REGISTER_BUTTON = icagendaRegistration::registerButton($item);
}
else
{
	$EVENT_REGISTER_BUTTON = '';
}

// $EVENT_REGISTRATION     = icagendaRegistration::reg($item); // @deprecated 3.8 - Replaced by $EVENT_REGISTER_BUTTON
$EVENT_REGISTRATION = $EVENT_REGISTER_BUTTON; // Kept for B/C (Removed 4.0)

// Set Event information on tickets available/bookable
if ($maxNbTickets != '1000000'
	&& $statutReg == '1')
{
	$SEATS_AVAILABLE	= ($maxNbTickets - $item->registered);

	if ($SEATS_AVAILABLE === 0)
	{
		$SEATS_AVAILABLE	= JText::_('COM_ICAGENDA_REGISTRATION_DATE_NO_TICKETS_LEFT');
	}

	$MAX_NB_OF_SEATS	= $maxNbTickets;
}

// No limit for nb of tickets available
else
{
	$SEATS_AVAILABLE		= false;
	$MAX_NB_OF_SEATS		= false;
}

// Set Participants list
$PARTICIPANTS_DISPLAY   = icagendaRegistrationParticipants::listDisplay($item);
$PARTICIPANTS_HEADER    = icagendaRegistrationParticipants::listTitle($item);
$EVENT_PARTICIPANTS     = icagendaRegistrationParticipants::registeredUsers($item);


// DEPRECATED (old custom theme packs)
if ( ! in_array($this->template, array('default', 'ic_rounded')))
{
	$item->coordinate				= $GOOGLEMAPS_COORDINATES;
	$item->datelistUl				= $EVENT_SINGLE_DATES;
	$item->dateText					= $TEXT_FOR_NEXTDATE;
	$item->emailLink				= $EVENT_EMAIL_CLOAKING;
	$item->fileTag					= $EVENT_ATTACHEMENTS_TAG;
	$item->imageTag					= $EVENT_IMAGE_TAG;
	$item->infoDetails				= $EVENT_INFO;
	$item->loadEventCustomFields	= $CUSTOM_FIELDS;
	$item->map						= $EVENT_MAP;
	$item->nextDate					= $EVENT_NEXTDATE;
	$item->participantList			= $PARTICIPANTS_DISPLAY;
	$item->participantListTitle		= $PARTICIPANTS_HEADER;
	$item->periodDates				= $EVENT_PERIOD;
	$item->reg						= $EVENT_REGISTRATION;
	$item->registeredUsers			= $EVENT_PARTICIPANTS;
	$item->share_event				= $EVENT_SHARING;
	$item->websiteLink				= $EVENT_WEBSITE_LINK;
	$item->periodDisplay			= ($EVENT_SINGLE_DATES || $EVENT_PERIOD) ? true : false;
	$item->description				= $EVENT_DESCRIPTION;

	// B/C Theme Packs (to be checked, and added if needed)
	$item->place_name				= $item->place;
	$item->startdatetime			= $item->startdate;
	$item->enddatetime				= $item->enddate;

	$item->startDate				= icagendaRender::dateToFormat($item->startdate);
	$item->endDate					= icagendaRender::dateToFormat($item->enddate);

	$item->startTime				= icagendaRender::dateToTime($item->startdate);
	$item->endTime					= icagendaRender::dateToTime($item->enddate);

	$EVENT_INFOS					= $EVENT_INFO; // DEPRECATED (Not removed for B/C)
}
