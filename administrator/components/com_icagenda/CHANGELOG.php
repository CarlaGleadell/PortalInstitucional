<?php defined('_JEXEC') or die; ?>

<div style="text-align:center"><img src='../media/com_icagenda/images/iconicagenda48.png' alt='iCagenda' /><br/><big style="color:#555">ChangeLog</big></div>
================================================================================
? <center><strong><big>Welcome to iCagenda 3.9.12 release!</big></strong></center><br /><p><strong></strong></p><p>iCagenda 3.9 is cross-platform Joomla 3, 4 and 5. This version is the last version to support Joomla 3.</p><p>Update and take advantage of all the improvements done in latest version of iCagenda!</p><p><strong>Enjoy!</strong></p><br />
================================================================================
: <span class="ic-box-important ic-box-12">!</span><span class="ic-important">important</span>&nbsp;<span class="ic-box-added ic-box-12">+</span><span class="ic-added">added</span>&nbsp;<span class="ic-box-removed ic-box-12">-</span><span class="ic-removed">removed</span>&nbsp;<span class="ic-box-changed ic-box-12">~</span><span class="ic-changed">changed</span>&nbsp;<span class="ic-box-fixed ic-box-12">#</span><span class="ic-fixed">fixed</span><br/><i>Info: access to the beta versions and pre-releases are reserved to users with a valid pro subscription.</i><br/>iCagenda™ is distributed under the terms of the GNU General Public License version 3 or later; see LICENSE.txt.
================================================================================


iCagenda 3.9.12 <small style="font-weight:normal;">(2025.08.14)</small>
================================================================================
~ Changed: Add robots metadata for frontend list of events and submit form views.
~ [J4/J5] Changed: Integrate Category Order option for frontend search filter category.
# [LOW] Fixed: Quicktask text for Add Event in admin menu.
# [LOW][J3] Fixed: typo JText.

* Changed files in 3.9.12
~ com_icagenda/icagenda.xml
~ com_icagenda/site/src/Model/EventsModel.php
~ com_icagenda/site/src/View/Events/HtmlView.php
~ com_icagenda/site/src/View/Submit/HtmlView.php
~ com_icagenda/site/views/list/view.html.php
~ com_icagenda/site/views/list/tmpl/default_categories.php
~ com_icagenda/site/views/submit/view.html.php


iCagenda 3.9.11 <small style="font-weight:normal;">(2025.04.22)</small>
================================================================================
~ Changed: Remove clean cache from component (processed in package install).
# [LOW] Fixed: Checks if the image is readable before generating thumbnails.
# [LOW] Fixed: Additional end day error on a period, if start and end dates are at the same time.

* Changed files in 3.9.11
~ script.com_icagenda.php
~ [LIBRARY] lib_ic_library/Date/Period.php
~ [LIBRARY] lib_ic_library/lib/date/period.php
~ [LIBRARY] lib_ic_library/lib/thumb/get.php
~ [LIBRARY] lib_ic_library/Thumb/Get.php


iCagenda 3.9.10 <small style="font-weight:normal;">(2025.03.10)</small>
================================================================================
~ [MODULE] Improve: Registration counter to be more accurate.
# [LOW][MODULE] Fixed: Missing past full periods (no week days) in calendar module.
# [LOW][MODULE] Fixed: Registration status could be wrong depending on timezone.

* Changed files in 3.9.10
~ com_icagenda/admin/src/Utilities/Registration/Registration.php
~ com_icagenda/admin/utilities/registration/registration.php
~ [LIBRARY] lib_ic_library/Date/Period.php
~ [LIBRARY] lib_ic_library/lib/date/period.php
~ [MODULE] mod_iccalendar/helper.php


iCagenda 3.9.9 <small style="font-weight:normal;">(2025.01.29)</small>
================================================================================
~ [MODULE] Changed: Improve participants total counter in module tooltip.
# [LOW][J4/J5] Fixed: Missing Notes button in admin registrations list.
# [LOW] Fixed: Current day and time control (timezone issue).
# [LOW] Fixed: Date in session for period.
# [LOW] Fixed: user_action if null.

* Changed files in 3.9.9
~ com_icagenda/admin/src/Utilities/Registration/Participants.php
~ com_icagenda/admin/src/Utilities/Registration/Registration.php
~ com_icagenda/admin/tmpl/registrations/default.php
~ com_icagenda/admin/utilities/registration/participants.php
~ com_icagenda/admin/utilities/registration/registration.php
~ com_icagenda/site/models/event.php
~ com_icagenda/site/src/Model/EventModel.php
~ [MODULE] mod_iccalendar/helper.php


iCagenda 3.9.8 <small style="font-weight:normal;">(2024.12.20)</small>
================================================================================
~ Changed: Update Joomla max version to 5.3 (next version, iCagenda tested on alpha release).
# [LOW] Fixed: The option to show/hide the language field in the Submit an Event form was not effective.
# [LOW][J4/J5] Fixed: Namespace Uri if site_itemid in admin events list view.
# [LOW][J4/J5] Fixed: PHP deprecated method in RSS feed view.

* Changed files in 3.9.8
~ script.icagenda.php
~ com_icagenda/admin/tmpl/events/default.php
~ com_icagenda/site/models/submit.php
~ com_icagenda/site/src/Model/SubmitModel.php
~ com_icagenda/site/src/View/Events/FeedView.php


iCagenda 3.9.7 <small style="font-weight:normal;">(2024.10.14)</small>
================================================================================
~ Changed: Remove update server info for theme packs (not accurate nor used).
~ [MEDIA] Changed: Improve iCicon font.
# [LOW] Fixed: Filter query for null dates in admin list of events search for past dates.
# [LOW][MODULE][PRO] Fixed: Check layout if not empty.

* Changed files in 3.9.7
~ com_icagenda/admin/layouts/icagenda/admin/theme_pack_item.php
~ com_icagenda/admin/models/events.php
~ com_icagenda/admin/src/Model/EventsModel.php
~ com_icagenda/admin/views/themes/tmpl/default.php
~ [MEDIA] com_icagenda/media/icicons/style.css
~ [MODULE][PRO] mod_ic_event_list-pro/mod_ic_event_list.php


iCagenda 3.9.6 <small style="font-weight:normal;">(2024.08.21)</small>
================================================================================
# [LOW][MODULE][PRO] Fixed: The date time display for current full period events (wrong time).

* Changed files in 3.9.6
~ com_icagenda/admin/src/Utilities/Registration/Registration.php
~ com_icagenda/admin/utilities/registration/registration.php


iCagenda 3.9.5 <small style="font-weight:normal;">(2024.07.20)</small>
================================================================================
~ [J4/J5] Changed: Update Bootstrap classes Registration Authentication required message.
~ Changed: Improve custom fields of type separator.
# [LOW][J4/J5] Fixed: Add missing spacer_class (option to set css classes to a separator custom field).
# [LOW][J4/J5] Fixed: Missing custom field description.
# [LOW][J4/J5] Fixed: Image uploaded with space(s) in filename (Since J4, Joomla media manager allows space in media filename).
# [LOW][PRO][J4/J5] Fixed: Check-in issue for frontend after cancellation of event edition.
# [LOW][PRO][J4/J5] Fixed: Button edit event URL (possible issue depending on SEF settings and submenus).
# [LOW][MODULE][PRO] Fixed: The date time display for current full period events (wrong time).

* Changed files in 3.9.5
~ script.icagenda.php
~ com_icagenda/admin/src/Utilities/Customfields/Customfields.php
~ com_icagenda/admin/src/Utilities/Field/Icagenda/SeparatorField.php
~ com_icagenda/admin/tmpl/customfield/edit.php
~ com_icagenda/admin/utilities/customfields/customfields.php
~ com_icagenda/site/src/View/Registration/HtmlView.php
~ [FILE][PRO] file_icagenda-pro/site/src/Controller/EventController.php
~ [LIBRARY] lib_ic_library/Thumb/Get.php
~ [MODULE][PRO] mod_ic_event_list-pro/mod_ic_event_list.php
~ [PLUGIN][PRO] plg_icagenda-pro/src/Extension/Pro.php


iCagenda 3.9.4.1 [PATCH J3] <small style="font-weight:normal;">(2024.06.18)</small>
================================================================================
# [LOW][J3] Fixed: Class "Text" not found if frontend filter "Category" is enabled.

* Changed files in 3.9.4.1
~ com_icagenda/site/views/list/tmpl/default_filters.php


iCagenda 3.9.4 <small style="font-weight:normal;">(2024.06.17)</small>
================================================================================
+ Added: Option to display Language form field in frontend Submit an Event form (disabled by default).
+ Added: The possibility of a translatable category label (eg. using a custom language string override).
+ Added: Set control for new pro update serveur.
+ [J4] Added: Label class for registration Dates form field.
~ Changed: Remove obsolete Update Site (new joomlic update site server since 3.9.4).
# [LOW] Fixed: Form field class for language option in admin event edit.
# [LOW][J4/J5] Fixed: Back link routing after event edition in frontend.
# [LOW][J4/J5][PRO] Fixed: Return page if language is changed after event edition.
# [LOW][PLUGIN][PRO] Fixed: Description specialchars in PayPal script.

* Changed files in 3.9.4
~ pkg_icagenda.xml
~ script.icagenda.php
~ com_icagenda/script.com_icagenda.php
~ com_icagenda/admin/config.xml
~ com_icagenda/admin/models/forms/event.xml
~ com_icagenda/admin/src/Utilities/Event/Event.php
~ com_icagenda/admin/src/Utilities/Field/Icagenda/CategorySelectField.php
~ com_icagenda/admin/src/Utilities/Field/Icagenda/FilterCategoriesField.php
~ com_icagenda/admin/src/Utilities/Field/Icagenda/RegistrationDatesField.php
~ com_icagenda/admin/tmpl/events/default.php
~ com_icagenda/admin/utilities/field/categoryselect.php
~ com_icagenda/admin/utilities/field/filtercategories.php
~ com_icagenda/admin/views/events/tmpl/default.php
~ com_icagenda/site/add/elements/icevent_vars.php
~ com_icagenda/site/add/elements/iclist_vars.php
~ com_icagenda/site/models/submit.php
~ com_icagenda/site/models/forms/submit.xml
~ com_icagenda/site/src/Assets/EventShortcuts.php
~ com_icagenda/site/src/Assets/ListShortcuts.php
~ com_icagenda/site/src/Model/SubmitModel.php
~ com_icagenda/site/src/View/Submit/HtmlView.php
~ com_icagenda/site/tmpl/events/default_categories.php
~ com_icagenda/site/tmpl/events/default_filters.php
~ com_icagenda/site/tmpl/submit/default.xml
~ com_icagenda/site/views/list/tmpl/default_categories.php
~ com_icagenda/site/views/list/tmpl/default_filters.php
~ com_icagenda/site/views/submit/tmpl/default.xml
~ [FILE][PRO] file_icagenda-pro/site/src/Controller/EventController.php
~ [FILE][PRO] file_icagenda-pro/site/tmpl/manager/event_edit.php
~ [PLUGIN][PRO] plg_icagenda_payment_paypal-pro/payment_paypal.php
~ [PLUGIN][PRO] plg_icagenda_payment_paypal-pro/src/Extension/Payment_Paypal.php
~ [PLUGIN] plg_installer_icagenda/icagenda.php
~ [PLUGIN] plg_installer_icagenda/src/Extension/Icagenda.php


iCagenda 3.9.3 <small style="font-weight:normal;">(2024.04.18)</small>
================================================================================
~ Changed: Add 'p' tag in allowed HTML tags for auto-introtext option.
~ Changed: Feed RSS, do not control the format to allow a custom name for it (and get all dates in the RSS feed).
# [LOW] Fixed: Registration custom notification emails HTML filtering.

* Changed files in 3.9.3
~ com_icagenda/admin/config.xml
~ com_icagenda/admin/src/Utilities/Events/Events.php
~ com_icagenda/admin/utilities/events/events.php
~ com_icagenda/site/src/Model/EventsModel.php


iCagenda 3.9.2 <small style="font-weight:normal;">(2024.03.22)</small>
================================================================================
~ [J4/J5] Changed: some code improvements and cleanups.
# [LOW] Fixed: Updated the filter attribute in the editor type form field, to use the namespace (error on J5 if the compatibility plugin is disabled).
# [LOW] Fixed: Defined setting of the option to cancel registrations if defined in the event options.

* Changed files in 3.9.2
~ com_icagenda/admin/config.xml
~ com_icagenda/admin/models/forms/category.xml
~ com_icagenda/admin/models/forms/customfield.xml
~ com_icagenda/admin/models/forms/event.xml
~ com_icagenda/admin/models/forms/feature.xml
~ com_icagenda/admin/models/forms/registration.xml
~ com_icagenda/site/src/Model/RegistrationModel.php
~ com_icagenda/site/tmpl/registration/cancel.php
~ com_icagenda/site/views/registration/tmpl/cancel.php
~ [FILE][PRO] file_icagenda-pro/site/models/forms/event.xml
~ [MODULE][PRO] mod_ic_event_list-pro/mod_ic_event_list.xml
~ [MODULE] mod_iccalendar/mod_iccalendar.xml


iCagenda 3.9.1 <small style="font-weight:normal;">(2024.03.13)</small>
================================================================================
~ [MODULE] Changed: Prevent click event when swiping on mobile in module calendar.
# [LOW][J3] Fixed: ListField not found when frontend search filters are enable.
# [LOW][J4/J5] Fixed: tel rule error when telephone field is required in registration form.

* Changed files in 3.9.1
~ com_icagenda/site/layouts/joomla/form/field/file.php
~ com_icagenda/site/models/fields/categories.php
~ com_icagenda/site/models/fields/month.php
~ com_icagenda/site/models/fields/year.php
~ com_icagenda/site/src/Model/RegistrationModel.php
~ [MODULE] mod_iccalendar/mod_iccalendar.php


iCagenda 3.9.0 <small style="font-weight:normal;">(2024.03.11)</small>
================================================================================
! Full Compatibility Joomla 5
1 This version can run without the need to enable the compatibility plugin on Joomla 5.
~ Refactory: All form fields recoded.
~ Refactory: All plugins updated to Joomla 5.
~ Refactory: Numerous code reviews, improvements and clean-up.
~ Changed: Removal of all out-dated code used by versions prior to Joomla 3.10.
~ Changed: Improve retrieving of mail sending errors.
~ [PLUGIN][PRO] Changed: Improve finder Smart Search description result.
# [LOW][J4/J5] Fixed: Missing form-select class for core_people override custom field.
# [LOW][J4/J5] Fixed: Thumbnail image link not sanitized + add image as feed enclosure.
# [LOW] Fixed: Don't route edit url (prevent issue with suffix url).
# [LOW] Fixed: Falang routing on language switch for event details view.
# [LOW][PHP8.2] Fixed: Thumb create float to int error.

* Changed files in 3.9.0
~ script.icagenda.php
~ com_icagenda/script.com_icagenda.php
~ com_icagenda/admin/config.xml
~ com_icagenda/admin/icagenda.php
- com_icagenda/admin/assets/elements/desc.php
- com_icagenda/admin/assets/elements/title.php
- com_icagenda/admin/assets/elements/titleheader.php
- com_icagenda/admin/assets/elements/titleimg.php
~ com_icagenda/admin/assets/jcms/info.php
~ com_icagenda/admin/controllers/registrations.raw.php
~ com_icagenda/admin/language/en-GB/en-GB.com_icagenda.ini
~ com_icagenda/admin/layouts/icagenda/updater/liveupdate.php
~ com_icagenda/admin/layouts/joomla/form/field/subform/repeatable/section.php
~ com_icagenda/admin/models/download.php
- com_icagenda/admin/models/fields.php
~ com_icagenda/admin/models/mail.php
~ com_icagenda/admin/models/themes.php
- com_icagenda/admin/models/fields/list.php
- com_icagenda/admin/models/fields/config/emailtags.php
- com_icagenda/admin/models/fields/iclist/globalization.php
- com_icagenda/admin/models/fields/icmap/city.php
- com_icagenda/admin/models/fields/icmap/country.php
- com_icagenda/admin/models/fields/icmap/lat.php
- com_icagenda/admin/models/fields/icmap/lng.php
- com_icagenda/admin/models/fields/modal/cat.php
- com_icagenda/admin/models/fields/modal/checkdnsrr.php
- com_icagenda/admin/models/fields/modal/coordinate.php
- com_icagenda/admin/models/fields/modal/evt.php
- com_icagenda/admin/models/fields/modal/evt_date.php
- com_icagenda/admin/models/fields/modal/ic_editor.php
- com_icagenda/admin/models/fields/modal/ic_password.php
- com_icagenda/admin/models/fields/modal/icalert_msg.php
- com_icagenda/admin/models/fields/modal/icfile.php
- com_icagenda/admin/models/fields/modal/icvalue_field.php
- com_icagenda/admin/models/fields/modal/icvalue_opt.php
- com_icagenda/admin/models/fields/modal/menulink.php
- com_icagenda/admin/models/fields/modal/multicat.php
- com_icagenda/admin/models/fields/modal/template.php
- com_icagenda/admin/models/fields/modal/thumbs.php
- com_icagenda/admin/models/fields/spacer/description.php
- com_icagenda/admin/models/fields/spacer/label.php
~ com_icagenda/admin/models/forms/customfield.xml
~ com_icagenda/admin/models/forms/download.xml
~ com_icagenda/admin/models/forms/event.xml
~ com_icagenda/admin/models/forms/feature.xml
~ com_icagenda/admin/models/forms/filter_events.xml
~ com_icagenda/admin/models/forms/mail.xml
~ com_icagenda/admin/models/forms/registration.xml
~ com_icagenda/admin/src/Controller/CustomfieldController.php
~ com_icagenda/admin/src/Controller/EventController.php
~ com_icagenda/admin/src/Controller/RegistrationController.php
~ com_icagenda/admin/src/Extension/iCagendaComponent.php
- com_icagenda/admin/src/Field/CustomfieldGroupsField.php
- com_icagenda/admin/src/Field/MultipleCategoryField.php
~ com_icagenda/admin/src/Helper/iCagendaHelper.php
~ com_icagenda/admin/src/Model/MailModel.php
~ com_icagenda/admin/src/Model/RegistrationModel.php
~ com_icagenda/admin/src/Model/RegistrationsModel.php
~ com_icagenda/admin/src/Model/ThemesModel.php
~ com_icagenda/admin/src/Table/RegistrationTable.php
~ com_icagenda/admin/src/Utilities/Customfields/Customfields.php
~ com_icagenda/admin/src/Utilities/Event/Event.php
+ com_icagenda/admin/src/Utilities/Field/Icagenda/CategorySelectField.php
+ com_icagenda/admin/src/Utilities/Field/Icagenda/CheckdnsrrField.php
+ com_icagenda/admin/src/Utilities/Field/Icagenda/ConfigTermsDefaultField.php
+ com_icagenda/admin/src/Utilities/Field/Icagenda/CustomfieldGroupsField.php
+ com_icagenda/admin/src/Utilities/Field/Icagenda/CustomFormField.php
+ com_icagenda/admin/src/Utilities/Field/Icagenda/DeadlineBeforeField.php
+ com_icagenda/admin/src/Utilities/Field/Icagenda/DeadlineTimeField.php
+ com_icagenda/admin/src/Utilities/Field/Icagenda/EventDatesListField.php
+ com_icagenda/admin/src/Utilities/Field/Icagenda/EventsListField.php
+ com_icagenda/admin/src/Utilities/Field/Icagenda/EventsMenuItemsField.php
+ com_icagenda/admin/src/Utilities/Field/Icagenda/FilterCategoriesField.php
+ com_icagenda/admin/src/Utilities/Field/Icagenda/FilterCustomfieldGroupsField.php
+ com_icagenda/admin/src/Utilities/Field/Icagenda/FilterCustomfieldTypesField.php
+ com_icagenda/admin/src/Utilities/Field/Icagenda/FilterDatesField.php
+ com_icagenda/admin/src/Utilities/Field/Icagenda/FilterEventsField.php
+ com_icagenda/admin/src/Utilities/Field/Icagenda/FilterSubmitMenuItemidField.php
+ com_icagenda/admin/src/Utilities/Field/Icagenda/MapCountryField.php
+ com_icagenda/admin/src/Utilities/Field/Icagenda/MapLatitudeField.php
+ com_icagenda/admin/src/Utilities/Field/Icagenda/MapLocalityField.php
+ com_icagenda/admin/src/Utilities/Field/Icagenda/MapLongitudeField.php
+ com_icagenda/admin/src/Utilities/Field/Icagenda/MediaUploadField.php
+ com_icagenda/admin/src/Utilities/Field/Icagenda/MultipleCategoryField.php
+ com_icagenda/admin/src/Utilities/Field/Icagenda/RegistrationDatesField.php
+ com_icagenda/admin/src/Utilities/Field/Icagenda/RegistrationPeopleField.php
+ com_icagenda/admin/src/Utilities/Field/Icagenda/SeparatorField.php
+ com_icagenda/admin/src/Utilities/Field/Icagenda/TermsField.php
+ com_icagenda/admin/src/Utilities/Field/Icagenda/TextareaCounterField.php
+ com_icagenda/admin/src/Utilities/Field/Icagenda/ThemeField.php
+ com_icagenda/admin/src/Utilities/Field/Icagenda/ThumbnailField.php
~ com_icagenda/admin/src/Utilities/Manager/Manager.php
~ com_icagenda/admin/src/Utilities/Maps/Maps.php
~ com_icagenda/admin/src/Utilities/Theme/Theme.php
~ com_icagenda/admin/src/Utilities/Update/icagendaUpdate.php
~ com_icagenda/admin/tables/customfield.php
~ com_icagenda/admin/tables/feature.php
~ com_icagenda/admin/tables/registration.php
~ com_icagenda/admin/tmpl/categories/default.php
~ com_icagenda/admin/tmpl/customfield/edit.php
~ com_icagenda/admin/tmpl/event/edit.php
~ com_icagenda/admin/tmpl/events/default.php
~ com_icagenda/admin/tmpl/icagenda/default.php
- com_icagenda/admin/tmpl/icagenda/default_modal_pro.php
~ com_icagenda/admin/tmpl/registration/edit.php
~ com_icagenda/admin/tmpl/registrations/default.php
+ com_icagenda/admin/utilities/field/categoryselect.php
+ com_icagenda/admin/utilities/field/checkdnsrr.php
+ com_icagenda/admin/utilities/field/configtermsdefault.php
+ com_icagenda/admin/utilities/field/customfieldgroups.php
+ com_icagenda/admin/utilities/field/customform.php
+ com_icagenda/admin/utilities/field/deadlinebefore.php
+ com_icagenda/admin/utilities/field/deadlinetime.php
+ com_icagenda/admin/utilities/field/eventdateslist.php
+ com_icagenda/admin/utilities/field/eventslist.php
+ com_icagenda/admin/utilities/field/eventsmenuitems.php
+ com_icagenda/admin/utilities/field/filtercategories.php
+ com_icagenda/admin/utilities/field/filtercustomfieldgroups.php
+ com_icagenda/admin/utilities/field/filtercustomfieldtypes.php
+ com_icagenda/admin/utilities/field/filterdates.php
+ com_icagenda/admin/utilities/field/filterevents.php
+ com_icagenda/admin/utilities/field/filtersubmitmenuitemid.php
+ com_icagenda/admin/utilities/field/mapcountry.php
+ com_icagenda/admin/utilities/field/maplatitude.php
+ com_icagenda/admin/utilities/field/maplocality.php
+ com_icagenda/admin/utilities/field/maplongitude.php
+ com_icagenda/admin/utilities/field/mediaupload.php
+ com_icagenda/admin/utilities/field/multiplecategory.php
+ com_icagenda/admin/utilities/field/registrationdates.php
+ com_icagenda/admin/utilities/field/registrationpeople.php
+ com_icagenda/admin/utilities/field/separator.php
+ com_icagenda/admin/utilities/field/terms.php
+ com_icagenda/admin/utilities/field/textareacounter.php
+ com_icagenda/admin/utilities/field/theme.php
+ com_icagenda/admin/utilities/field/thumbnail.php
- com_icagenda/admin/utilities/form/field/categoryselect.php
- com_icagenda/admin/utilities/form/field/configtermsdefault.php
- com_icagenda/admin/utilities/form/field/customfieldgroups.php
- com_icagenda/admin/utilities/form/field/customform.php
- com_icagenda/admin/utilities/form/field/deadlinefield.php
- com_icagenda/admin/utilities/form/field/deadlinetimefield.php
- com_icagenda/admin/utilities/form/field/filtercategories.php
- com_icagenda/admin/utilities/form/field/filtercustomfieldgroups.php
- com_icagenda/admin/utilities/form/field/filtercustomfieldtypes.php
- com_icagenda/admin/utilities/form/field/filterdates.php
- com_icagenda/admin/utilities/form/field/filterevents.php
- com_icagenda/admin/utilities/form/field/multiplecategory.php
- com_icagenda/admin/utilities/form/field/registrationdates.php
- com_icagenda/admin/utilities/form/field/registrationpeople.php
- com_icagenda/admin/utilities/form/field/registrationterms.php
- com_icagenda/admin/utilities/form/field/separator.php
- com_icagenda/admin/utilities/form/field/submitmenuitemid.php
- com_icagenda/admin/utilities/form/field/terms.php
- com_icagenda/admin/utilities/form/field/textareacounter.php
- com_icagenda/admin/utilities/theme/joomla25.php
~ com_icagenda/admin/utilities/theme/theme.php
~ com_icagenda/admin/views/categories/view.html.php
~ com_icagenda/admin/views/categories/tmpl/default.php
~ com_icagenda/admin/views/category/view.html.php
~ com_icagenda/admin/views/category/tmpl/edit.php
~ com_icagenda/admin/views/customfield/view.html.php
~ com_icagenda/admin/views/customfield/tmpl/edit.php
~ com_icagenda/admin/views/customfields/view.html.php
~ com_icagenda/admin/views/customfields/tmpl/default.php
~ com_icagenda/admin/views/download/tmpl/default.php
~ com_icagenda/admin/views/event/view.html.php
~ com_icagenda/admin/views/events/view.html.php
~ com_icagenda/admin/views/events/tmpl/default.php
~ com_icagenda/admin/views/feature/view.html.php
~ com_icagenda/admin/views/feature/tmpl/edit.php
~ com_icagenda/admin/views/features/view.html.php
~ com_icagenda/admin/views/icagenda/view.html.php
~ com_icagenda/admin/views/icagenda/tmpl/default.php
~ com_icagenda/admin/views/info/view.html.php
~ com_icagenda/admin/views/mail/view.html.php
~ com_icagenda/admin/views/mail/tmpl/edit.php
~ com_icagenda/admin/views/registration/view.html.php
~ com_icagenda/admin/views/registration/tmpl/edit.php
~ com_icagenda/admin/views/registrations/view.html.php
~ com_icagenda/admin/views/registrations/view.raw.php
~ com_icagenda/admin/views/themes/view.html.php
~ [MEDIA] com_icagenda/media/css/icagenda-back.css
- [MEDIA] com_icagenda/media/css/icagenda-back.j25.css
- [MEDIA] com_icagenda/media/css/icagenda-front.j25.css
- [MEDIA] com_icagenda/media/css/template.j25.css
- [MEDIA] com_icagenda/media/images/payment/icon_cca.gif
- [MEDIA] com_icagenda/media/images/payment/icon_chk.gif
- [MEDIA] com_icagenda/media/images/payment/icon_pal.gif
- [MEDIA] com_icagenda/media/images/payment/icon_wtr.gif
~ com_icagenda/site/language/en-GB/en-GB.com_icagenda.ini
~ com_icagenda/site/layouts/icagenda/registration/button/box.php
~ com_icagenda/site/layouts/icagenda/registration/button/cancel.php
~ com_icagenda/site/layouts/icagenda/registration/button/info.php
~ com_icagenda/site/layouts/icagenda/registration/button/register.php
~ com_icagenda/site/layouts/joomla/form/field/file.php
~ com_icagenda/site/layouts/joomla/form/field/subform/repeatable.php
~ com_icagenda/site/layouts/joomla/form/field/subform/repeatable/section.php
~ com_icagenda/site/models/fields/categories.php
~ com_icagenda/site/models/fields/month.php
~ com_icagenda/site/models/fields/year.php
~ com_icagenda/site/models/forms/registration.xml
~ com_icagenda/site/models/forms/submit.xml
~ com_icagenda/site/router.php
~ com_icagenda/site/src/Assets/EventShortcuts.php
~ com_icagenda/site/src/Assets/ListShortcuts.php
~ com_icagenda/site/src/Model/EventsModel.php
~ com_icagenda/site/src/Model/RegistrationModel.php
~ com_icagenda/site/src/Model/SubmitModel.php
~ com_icagenda/site/src/Service/Router.php
~ com_icagenda/site/src/View/Event/HtmlView.php
~ com_icagenda/site/src/View/Events/FeedView.php
~ com_icagenda/site/src/View/Events/HtmlView.php
~ com_icagenda/site/src/View/Registration/HtmlView.php
~ com_icagenda/site/src/View/Submit/HtmlView.php
~ com_icagenda/site/tmpl/events/default.xml
~ com_icagenda/site/tmpl/events/default_filters.php
~ com_icagenda/site/tmpl/registration/complete.php
~ com_icagenda/site/tmpl/registration/default.php
~ com_icagenda/site/tmpl/submit/default.php
~ com_icagenda/site/tmpl/submit/default.xml
~ com_icagenda/site/views/event/view.html.php
~ com_icagenda/site/views/list/tmpl/default.xml
~ com_icagenda/site/views/registration/view.html.php
~ com_icagenda/site/views/submit/view.html.php
~ com_icagenda/site/views/submit/tmpl/default.xml
~ [FILE][PRO] file_icagenda-pro/file_icagenda.xml
~ [FILE][PRO] file_icagenda-pro/site/controllers/event.php
~ [FILE][PRO] file_icagenda-pro/site/layouts/icagenda/manager/button/edit.php
~ [FILE][PRO] file_icagenda-pro/site/models/forms/event.xml
~ [FILE][PRO] file_icagenda-pro/site/src/Controller/EventController.php
~ [FILE][PRO] file_icagenda-pro/site/src/Model/ManagerModel.php
~ [FILE][PRO] file_icagenda-pro/site/tmpl/manager/event_edit.php
~ [FILE][PRO] file_icagenda-pro/site/views/manager/tmpl/event_edit.php
~ LIBRARY] lib_ic_library/lib_ic_library.xml
+ [LIBRARY] lib_ic_library/Field/IC/LocaleDateFormatsField.php
+ [LIBRARY] lib_ic_library/Field/IC/SortableFieldsField.php
- [LIBRARY] lib_ic_library/Form/Field/SortableFieldsField.php
- [LIBRARY] lib_ic_library/Form/Rule/PositiveIntegerRule.php
~ [LIBRARY] lib_ic_library/language/en-GB/en-GB.lib_ic_library.ini
+ [LIBRARY] lib_ic_library/lib/form/field/localedateformats.php
~ [LIBRARY] lib_ic_library/lib/form/field/sortablefields.php
~ [LIBRARY] lib_ic_library/lib/form/rule/tel.php
~ [LIBRARY] lib_ic_library/lib/thumb/create.php
~ [LIBRARY] lib_ic_library/Library/Library.php
+ [LIBRARY] lib_ic_library/Rule/IC/PositiveIntegerRule.php
+ [LIBRARY] lib_ic_library/Rule/IC/TelRule.php
~ [LIBRARY] lib_ic_library/Thumb/Create.php
~ [LIBRARY] lib_ic_library/Thumb/Get.php
~ [MODULE][PRO] mod_ic_event_list-pro/mod_ic_event_list.php
~ [MODULE][PRO] mod_ic_event_list-pro/mod_ic_event_list.xml
~ [MODULE][PRO] mod_ic_event_list-pro/src/Helper/EventsHelper.php
~ [MODULE] mod_iccalendar/helper.php
~ [MODULE] mod_iccalendar/mod_iccalendar.php
~ [MODULE] mod_iccalendar/mod_iccalendar.xml
~ [PLUGIN] plg_actionlog_icagenda/icagenda.xml
+ [PLUGIN] plg_actionlog_icagenda/services/provider.php
+ [PLUGIN] plg_actionlog_icagenda/src/Extension/Icagenda.php
~ [PLUGIN][PRO] plg_finder_icagenda-pro/icagenda.php
~ [PLUGIN][PRO] plg_finder_icagenda-pro/icagenda.xml
+ [PLUGIN][PRO] plg_finder_icagenda-pro/services/provider.php
+ [PLUGIN][PRO] plg_finder_icagenda-pro/src/Extension/Icagenda.php
~ [PLUGIN][PRO] plg_icagenda-pro/pro.xml
~ [PLUGIN][PRO] plg_icagenda-pro/forms/config_pro.xml
+ [PLUGIN][PRO] plg_icagenda-pro/services/provider.php
+ [PLUGIN][PRO] plg_icagenda-pro/src/Extension/Pro.php
~ [PLUGIN][PRO] plg_icagenda_payment_paypal-pro/payment_paypal.xml
~ [PLUGIN][PRO] plg_icagenda_payment_paypal-pro/layouts/payment_details.php
+ [PLUGIN][PRO] plg_icagenda_payment_paypal-pro/services/provider.php
+ [PLUGIN][PRO] plg_icagenda_payment_paypal-pro/src/Extension/Payment_Paypal.php
~ [PLUGIN][PRO] plg_icagenda_tickets-pro/tickets.xml
~ [PLUGIN][PRO] plg_icagenda_tickets-pro/layouts/payment.php
+ [PLUGIN][PRO] plg_icagenda_tickets-pro/services/provider.php
+ [PLUGIN][PRO] plg_icagenda_tickets-pro/src/Extension/Tickets.php
~ [PLUGIN] plg_installer_icagenda/icagenda.xml
+ [PLUGIN] plg_installer_icagenda/services/provider.php
+ [PLUGIN] plg_installer_icagenda/src/Extension/Icagenda.php
~ [PLUGIN] plg_privacy_icagenda/icagenda.xml
~ [PLUGIN] plg_privacy_icagenda/language/en-GB/en-GB.plg_privacy_icagenda.ini
+ [PLUGIN] plg_privacy_icagenda/services/provider.php
+ [PLUGIN] plg_privacy_icagenda/src/Extension/Icagenda.php
~ [PLUGIN] plg_quickicon_icagendaupdate/icagendaupdate.xml
+ [PLUGIN] plg_quickicon_icagendaupdate/services/provider.php
+ [PLUGIN] plg_quickicon_icagendaupdate/src/Extension/Icagendaupdate.php
~ [PLUGIN] plg_search_icagenda/icagenda.xml
~ [PLUGIN] plg_system_ic_autologin/ic_autologin.xml
+ [PLUGIN] plg_system_ic_autologin/services/provider.php
+ [PLUGIN] plg_system_ic_autologin/src/Extension/Ic_AutoLogin.php
~ [PLUGIN] plg_system_ic_library/ic_library.php
~ [PLUGIN] plg_system_ic_library/ic_library.xml
+ [PLUGIN] plg_system_ic_library/services/provider.php
+ [PLUGIN] plg_system_ic_library/src/Extension/Ic_Library.php
~ [PLUGIN] plg_system_icagenda/icagenda.xml
+ [PLUGIN] plg_system_icagenda/services/provider.php
+ [PLUGIN] plg_system_icagenda/src/Extension/Icagenda.php


iCagenda 3.9.0-rc1 <small style="font-weight:normal;">(2024.03.04)</small>
================================================================================
! Full Compatibility Joomla 5
1 This version can run without the need to enable the compatibility plugin on Joomla 5.
~ Refactory: All form fields recoded.
~ Refactory: All plugins updated to Joomla 5.
~ Refactory: Numerous code reviews, improvements and cleanup.
~ Changed: Removal of all out-dated code used by versions prior to Joomla 3.10.
~ Changed: Improve retrieving of mail sending errors.
# [LOW][J4/J5] Fixed: Missing form-select class for core_people override custom field.
# [LOW][J4/J5] Fixed: Thumbnail image link not sanitized + add image as feed enclosure.
# [LOW] Fixed: Don't route edit url (prevent issue with suffix url).
# [LOW][PHP8.2] Fixed: Thumb create float to int error.

* Changed files in 3.9.0-rc1 (See 3.9.0)


iCagenda 3.8.24 <small style="font-weight:normal;">(2023.12.06)</small>
================================================================================
+ Added: Add 'translateformat' to calendar type custom field (date formatted in current language).
~ Changed: Exclude separator form field from csv export.
# [LOW][PHP8] Fixed : missing $key for customfields insertObject (does not generate any bugs, but it is better to fix them).
# [LOW][J3][PHP8] Fixed : Submit event frontend form, count() error, Argument #1 ($value) must be of type Countable|array, null given.
# [LOW][PLUGIN][PRO] Fixed : possible error during payment validation.

* Changed files in 3.8.24
~ com_icagenda/admin/models/registrations.php
~ com_icagenda/admin/src/Model/RegistrationsModel.php
~ com_icagenda/admin/utilities/customfields/customfields.php
~ com_icagenda/admin/src/Utilities/Customfields/Customfields.php
~ com_icagenda/site/models/submit.php
~ com_icagenda/site/src/Model/SubmitModel.php
~ [PLUGIN][PRO] plg_icagenda_payment_paypal-pro/payment_paypal.php


iCagenda 3.8.23 <small style="font-weight:normal;">(2023.12.03)</small>
================================================================================
# [MEDIUM] Fixed : Custom fields on the registration form are not linked to the registration ID (parent_id) in database table #__icagenda_customfields_data. The data is stored, but unfortunately it is also impossible to know which record it is linked to.
# [LOW] Fixed : ic_rounded aria-label for icons not well generated.

* Changed files in 3.8.23
~ com_icagenda/site/models/registration.php
~ com_icagenda/site/src/Model/RegistrationModel.php
~ [THEME] com_icagenda/site/themes/packs/ic_rounded/ic_rounded_event.php


iCagenda 3.8.22 <small style="font-weight:normal;">(2023.11.29)</small>
================================================================================
~ Changed: Improve Media folder control when site installed on a sub-directory, and images path is changed in Media manager.
# [LOW] Fixed : Default typeReg value. To correctly set registration dates in notification emails for older events.
# [LOW] Fixed : cache registration counter for visitors.
# [LOW] Fixed : insertObject id.
# [LOW][J4/J5] Fixed : Remaining deprecated JText.
# [LOW][J4/J5] Fixed : Event URL routing in registration notification email.
# [LOW][PHP8.2] Fixed : explode() in participants list.
# [LOW][PHP8.1] Fixed : Implicit incompatible float to int conversion is deprecated.

* Changed files in 3.8.22
~ com_icagenda/admin/src/Model/CustomfieldModel.php
~ com_icagenda/admin/src/Model/EventModel.php
~ com_icagenda/admin/src/Model/FeatureModel.php
~ com_icagenda/admin/src/Model/IcategoryModel.php
~ com_icagenda/admin/src/Model/RegistrationModel.php
~ com_icagenda/admin/src/Utilities/Ajax/Ajax.php
~ com_icagenda/admin/src/Utilities/Customfields/Customfields.php
~ com_icagenda/admin/src/Utilities/Maps/Maps.php
~ com_icagenda/admin/src/Utilities/Media/Media.php
~ com_icagenda/admin/src/Utilities/Registration/Participants.php
~ com_icagenda/admin/src/Utilities/Registration/Registration.php
~ com_icagenda/admin/tmpl/customfields/default.php
~ com_icagenda/admin/tmpl/event/edit.php
~ com_icagenda/admin/tmpl/events/default.php
~ com_icagenda/admin/tmpl/features/default.php
~ com_icagenda/admin/tmpl/icagenda/default_modal_pro.php
~ com_icagenda/admin/utilities/ajax/ajax.php
~ com_icagenda/admin/utilities/customfields/customfields.php
~ com_icagenda/admin/utilities/form/field/registrationdates.php
~ com_icagenda/admin/utilities/registration/participants.php
~ com_icagenda/site/models/registration.php
~ com_icagenda/site/models/submit.php
~ com_icagenda/site/src/Assets/EventShortcuts.php
~ com_icagenda/site/src/Model/RegistrationModel.php
~ com_icagenda/site/src/Model/SubmitModel.php
~ [THEME] com_icagenda/site/themes/packs/default/default_day.php
~ [THEME] com_icagenda/site/themes/packs/default/default_event.php
~ [THEME] com_icagenda/site/themes/packs/default/default_events.php
~ [THEME] com_icagenda/site/themes/packs/ic_rounded/ic_rounded_day.php
~ [THEME] com_icagenda/site/themes/packs/ic_rounded/ic_rounded_event.php
~ [THEME] com_icagenda/site/themes/packs/ic_rounded/ic_rounded_events.php
~ com_icagenda/site/tmpl/submit/default.php
~ [FILE][PRO] file_icagenda-pro/site/models/manager.php
~ [FILE][PRO] file_icagenda-pro/site/src/Model/ManagerModel.php
~ [LIBRARY] lib_ic_library/Globalize/Globalize.php
~ [LIBRARY] lib_ic_library/Globalize/culture/fa-IR.php
~ [LIBRARY] lib_ic_library/lib/thumb/create.php
~ [LIBRARY] lib_ic_library/Thumb/Create.php
~ [MODULE][PRO] mod_ic_event_list-pro/mod_ic_event_list.php
~ [MODULE][PRO] mod_ic_event_list-pro/tmpl/default.php
~ [MODULE][PRO] mod_ic_event_list-pro/tmpl/icrounded.php
~ [MODULE] mod_iccalendar/helper.php
~ [PLUGIN][PRO] plg_icagenda_payment_paypal-pro/payment_paypal.php


iCagenda 3.8.21 <small style="font-weight:normal;">(2023.11.02)</small>
================================================================================
~ Changed: Improve alias generation on save as a copy (events and categories).
# [LOW] Fixed : Registration validation, reverts the empty value for custom_fields.
# [LOW] Fixed : null hits on new event.
# [LOW] Fixed : Wrong element id (checking for com_icagenda instead of pkg_icagenda) for updater notification.
# [LOW] Fixed : Missing time for created/modified form fields in registration edition form.
# [LOW][PHP8.2] Fixed : created/modified warning deprecated message calendar form field.
# [LOW][J4/J5] Fixed : Time selector in calendar picker for event dates.
# [LOW][J3] Fixed : Thumbs generation, broken iCagenda image path directory.
# [LOW][J3] Fixed : Ordering of categories in admin filter by option set.

* Changed files in 3.8.21
~ [LANGUAGE] com_icagenda/admin/language/en-GB/en-GB.com_icagenda.ini
~ com_icagenda/admin/models/category.php
~ com_icagenda/admin/models/event.php
~ com_icagenda/admin/models/events.php
~ com_icagenda/admin/models/registrations.php
~ com_icagenda/admin/models/forms/customfield.xml
~ com_icagenda/admin/models/forms/event.xml
~ com_icagenda/admin/models/forms/registration.xml
~ com_icagenda/admin/src/Model/EventModel.php
~ com_icagenda/admin/src/Model/IcategoryModel.php
~ com_icagenda/admin/src/Table/EventTable.php
~ com_icagenda/admin/src/Table/IcategoryTable.php
~ com_icagenda/admin/src/Utilities/Update/icagendaUpdate.php
~ com_icagenda/admin/tables/category.php
~ com_icagenda/admin/tables/event.php
~ com_icagenda/admin/utilities/thumb/thumb.php
~ com_icagenda/admin/utilities/update/update.php
~ com_icagenda/site/models/registration.php
~ com_icagenda/site/src/Model/RegistrationModel.php
~ [PLUGIN] plg_quickicon_icagendaupdate/icagendaupdate.php


iCagenda 3.8.20 <small style="font-weight:normal;">(2023.10.21)</small>
================================================================================
! A bug was introduced in J4.4 and J5 with calendar form field picker when week numbers are hidden and time format is 24h.
1 And it's all my fault (Oops)! I introduced this issue by improving the display of the core calendar picker in the official Joomla CMS.
1 There the Joomla PR with the patch: https://github.com/joomla/joomla-cms/pull/42185
# [LOW][J4.4-J5] Fixed : Time selector in calendar picker for event dates.

* Changed files in 3.8.20
~ com_icagenda/admin/models/forms/event.xml
~ com_icagenda/site/models/forms/submit.xml
~ [FILE][PRO] file_icagenda-pro/site/models/forms/event.xml


iCagenda 3.8.19 <small style="font-weight:normal;">(2023.10.19)</small>
================================================================================
! Joomla 5 Ready!
+ Added: Admin dark mode support on Joomla 5.
+ Added: Workaround for utf8_encode and _decode being deprecated in PHP 8.2
~ [THEMES] Changed: Improve Date/Dates label (singular/plural) in event details view.
# [LOW] Fixed : Improve retrieved path of iCagenda images folder.
# [LOW] Fixed : AddThis End of Service message on fresh install (do not displayed).
# [LOW][PHP 8.2] Fixed : Dynamic properties warning in module calendar.
# [LOW][PHP 8.2] Fixed : Deprecated Passing null warning messages in admin registration.
# [LOW][PHP 8.x] Fixed : Registration form, 1364 Field 'Custom_Fields' doesn't have a default value.
# [LOW][PRO] Fixed : Missing Features in frontend edition.
# [LOW][J4] Fixed : Improve category check in admin events list.
# [LOW][J3] Fixed : Call to a member function format() on bool in event edit depending on language used.

* Changed files in 3.8.19
~ script.icagenda.php
~ com_icagenda/script.com_icagenda.php
~ com_icagenda/admin/models/event.php
~ com_icagenda/admin/models/fields/modal/evt_date.php
~ com_icagenda/admin/models/forms/registration.xml
~ com_icagenda/admin/src/Utilities/Event/Event.php
+ com_icagenda/admin/src/Utilities/Media/Media.php
~ com_icagenda/admin/src/Utilities/Thumb/Thumb.php
~ com_icagenda/admin/src/View/Events/HtmlView.php
~ com_icagenda/admin/tmpl/events/default.php
~ com_icagenda/admin/tmpl/features/default.php
~ com_icagenda/admin/tmpl/icagenda/default.php
~ com_icagenda/admin/tmpl/info/default.php
~ com_icagenda/admin/utilities/form/field/textareacounter.php
+ com_icagenda/admin/utilities/media/media.php
~ com_icagenda/admin/utilities/thumb/thumb.php
~ com_icagenda/admin/views/events/tmpl/default.php
~ com_icagenda/admin/views/features/tmpl/default.php
~ com_icagenda/admin/views/icagenda/tmpl/default.php
~ [MEDIA] com_icagenda/media/css/icagenda-back.css
~ [MEDIA] com_icagenda/media/css/icagenda.css
~ com_icagenda/site/add/elements/icevent_vars.php
~ com_icagenda/site/add/elements/iclist_vars.php
~ com_icagenda/site/language/en-GB/en-GB.com_icagenda.ini
~ com_icagenda/site/layouts/joomla/form/field/subform/repeatable.php
~ com_icagenda/site/layouts/joomla/form/field/subform/repeatable/section.php
~ com_icagenda/site/models/registration.php
~ com_icagenda/site/src/Assets/EventShortcuts.php
~ com_icagenda/site/src/Assets/ListShortcuts.php
~ com_icagenda/site/src/Model/RegistrationModel.php
~ [THEME] com_icagenda/site/themes/packs/default/default_event.php
~ [THEME] com_icagenda/site/themes/packs/ic_rounded/ic_rounded_event.php
~ [FILE][PRO] file_icagenda-pro/site/models/forms/event.xml
~ [FILE][PRO] file_icagenda-pro/site/tmpl/manager/event_edit.php
~ [FILE][PRO] file_icagenda-pro/site/views/manager/tmpl/event_edit.php
~ [LIBRARY] lib_ic_library/Form/Field/SortableFieldsField.php
~ [LIBRARY] lib_ic_library/lib/form/field/sortablefields.php
+ [LIBRARY] lib_ic_library/Utf8/Utf8.php
~ [LIBRARY] lib_ic_library/Vendor/Icalcreator/src/Util/HttpFactory.php
~ [MODULE][PRO] mod_ic_event_list-pro/mod_ic_event_list.php
~ [MODULE] mod_iccalendar/helper.php
~ [MODULE] mod_iccalendar/mod_iccalendar.php


iCagenda 3.8.18 <small style="font-weight:normal;">(2023.06.05)</small>
================================================================================
! Notice of Termination of AddThis Services.
1 Oracle has made the business decision to terminate all AddThis services effective as of May 31, 2023.
1 Existing AddThis users can expect that after May 31, 2023:
1 - AddThis buttons may disappear from the user’s websites
1 - the AddThis dashboard associated with the user’s registration for AddThis, and all support for AddThis services, will no longer be available.
! All code associated with AddThis is therefore removed
1 iCagenda's social sharing is currently discontinued until a new solution is offered.
! In the meantime, you can use a third-party extension for social sharing
1 -> https://extensions.joomla.org/category/social-web/social-share/
1 or use the sharing functionality of your site template if it includes one.
1 Sorry for the inconvenience beyond our control.
- Removed : All AddThis related code.
+ Added : Display venue with [VENUE] tag placeholder in custom registration notification email.

* Changed files in 3.8.18
~ com_icagenda/admin/config.xml
~ com_icagenda/admin/language/en-GB/en-GB.com_icagenda.ini
~ com_icagenda/admin/language/en-GB/en-GB.com_icagenda.sys.ini
~ com_icagenda/admin/models/forms/event.xml
~ com_icagenda/admin/src/Utilities/AddThis/AddThis.php
~ com_icagenda/admin/tmpl/event/edit.php
~ com_icagenda/admin/tmpl/icagenda/default.php
~ com_icagenda/admin/utilities/addthis/addthis.php
~ com_icagenda/admin/views/event/tmpl/edit.php
~ com_icagenda/admin/views/icagenda/tmpl/default.php
~ com_icagenda/site/add/elements/icevent_vars.php
~ com_icagenda/site/models/registration.php
~ com_icagenda/site/src/Assets/EventShortcuts.php
~ com_icagenda/site/src/Model/RegistrationModel.php
~ com_icagenda/site/src/View/Events/HtmlView.php
~ [THEME] com_icagenda/site/themes/packs/default/default_event.php
~ [THEME] com_icagenda/site/themes/packs/ic_rounded/ic_rounded_event.php
~ com_icagenda/site/tmpl/events/default.php
~ com_icagenda/site/views/list/view.html.php
~ com_icagenda/site/views/list/tmpl/default.php
~ [PLUGIN] plg_privacy_icagenda/icagenda.php
~ [PLUGIN] plg_privacy_icagenda/language/en-GB/en-GB.plg_privacy_icagenda.ini


iCagenda 3.8.17 <small style="font-weight:normal;">(2023.05.24)</small>
================================================================================
+ Added : ic-event-id-{id} class in main list of events to allow individual custom css per event in the list.
+ Added : ic-event-cancelled class to parent element when event is cancelled.
+ Added : Extend Customfields loader to allow form fields load by slug.
~ Changed[J4] : Improve dashboard icon buttons display.
~ Changed[J4] : Improve open graph for event.
~ Changed[SQL] : Set custom field value to mediumtext.
# [LOW][J4] Fixed : Registrations filtering by Category in admin list.
# [LOW][J4] Fixed : Opengraph image url.
# [LOW][PRO][PHP8] Fixed : return_page base64 if empty.
# [LOW][PRO] Fixed : Possible error 404 with Helix template on saving an event.
# [LOW][PRO][J4] Fixed : created_by replaced by modified_by in frontend edit.
# [LOW][J3] Fixed : Missing filter to iso format for calendar form field when using translateformat in admin edit.
# [LOW][MODULE] Fixed : Empty inline style in module calendar weekdays.

* Changed files in 3.8.17
~ com_icagenda/admin/models/event.php
~ com_icagenda/admin/sql/updates/3.8.17.sql
~ com_icagenda/admin/src/Model/EventModel.php
~ com_icagenda/admin/src/Utilities/Customfields/Customfields.php
~ com_icagenda/admin/src/Utilities/Update/icagendaUpdate.php
~ com_icagenda/admin/tmpl/icagenda/default.php
~ com_icagenda/admin/utilities/customfields/customfields.php
~ com_icagenda/admin/utilities/form/field/FilterCategories.php
~ [MEDIA] com_icagenda/media/css/icagenda-back.css
~ com_icagenda/site/models/registration.php
~ com_icagenda/site/src/Model/RegistrationModel.php
~ com_icagenda/site/src/View/Event/HtmlView.php
~ com_icagenda/site/tmpl/event/default.php
~ com_icagenda/site/tmpl/events/default.php
~ com_icagenda/site/tmpl/registration/cancel.php
~ com_icagenda/site/views/event/tmpl/default.php
~ com_icagenda/site/views/list/tmpl/default.php
~ com_icagenda/site/views/registration/tmpl/cancel.php
~ [FILE][PRO] file_icagenda-pro/site/models/manager.php
~ [FILE][PRO] file_icagenda-pro/site/models/forms/event.xml
~ [FILE][PRO] file_icagenda-pro/site/src/Model/ManagerModel.php
~ [MODULE] mod_iccalendar/helper.php


iCagenda 3.8.16 <small style="font-weight:normal;">(2023.03.27)</small>
================================================================================
# [LOW] Fixed : Possible error on auto short description rendering.
# [LOW][J4] Fixed : Custom field of type email override error when in use.
# [LOW][J3] Fixed : Submit event form error when period dates empty.

* Changed files in 3.8.16
~ com_icagenda/admin/src/Utilities/Customfields/Customfields.php
~ com_icagenda/admin/src/Utilities/Events/Events.php
~ com_icagenda/admin/utilities/events/events.php
~ com_icagenda/site/models/submit.php


iCagenda 3.8.15 <small style="font-weight:normal;">(2023.03.13)</small>
================================================================================
# [LOW][PHP8] Fixed : Possible error message related to array_merge() on form registration validation (depending on your settings).

* Changed files in 3.8.15
~ com_icagenda/site/models/registration.php
~ com_icagenda/site/src/Model/RegistrationModel.php


iCagenda 3.8.14 <small style="font-weight:normal;">(2023.03.11)</small>
================================================================================
~ Changed : Improve Custom field individual handler for tag in user email notification for registration. Use [CUSTOMFIELD:slug] to get a custom field value from its slug for Registration and Event custom fields.
~ Changed : Improve managers sql query for notification email in frontend Submit an Event.
~ [J4] Changed : Replace not needed form-horizontal class with icagenda-form.
~ [PHP8] Changed : Improve thumbnails settings retrieving.
# [LOW] Fixed : Default user id on Registration form validation.
# [LOW] Fixed : Manager notification email for frontend Submit an Event form not sent to all.
# [LOW][J4] Fixed : Double description display for Name and Email in frontend submit form, when not logged-in user (public).
# [LOW][J4] Fixed : wrong tooltip text on hover admin search field in Registrations list.
# [LOW][J3] Fixed : PATCH for issue with J3 calendar picker not returning the translated date format to iso sql standard date format. Wrong check for period start and end dates with a few languages. (No issue on J4, as Joomla core calendar picker form field includes the filter function to return correct formatted datetime.)

* Changed files in 3.8.14
~ com_icagenda/admin/language/en-GB/en-GB.com_icagenda.ini
~ com_icagenda/admin/models/forms/filter_registrations.xml
~ com_icagenda/admin/tmpl/events/default.php
~ com_icagenda/admin/views/events/tmpl/default.php
~ com_icagenda/site/models/registration.php
~ com_icagenda/site/models/submit.php
~ com_icagenda/site/src/Model/RegistrationModel.php
~ com_icagenda/site/src/Model/SubmitModel.php
~ com_icagenda/site/tmpl/registration/default.php
~ com_icagenda/site/tmpl/submit/default.php


iCagenda 3.8.13 <small style="font-weight:normal;">(2023.02.15)</small>
================================================================================
! [PRO] Smart Search plugin for iCagenda events.
+ [J4] Added : Feed RSS/atom support for list of events.
~ Changed : Add registration state to registrations export and code improvement.
~ Changed : Improve code and data validation for Submit an Event form.
~ Changed : Improve latitude and longitude field values validation.
~ [THEMES] Changed : Clean code.
# [LOW] Fixed : Custom field separator (label and description), in case required was enabled on a previous different field type (error invalid form on registration editing).

* Changed files in 3.8.13
~ pkg_icagenda.xml
~ script.icagenda.php
~ com_icagenda/admin/controllers/registrations.raw.php
~ com_icagenda/admin/models/registrations.php
~ com_icagenda/admin/models/fields/icmap/lat.php
~ com_icagenda/admin/models/fields/icmap/lng.php
~ com_icagenda/admin/models/forms/download.xml
~ com_icagenda/admin/src/Controller/RegistrationsController.php
~ com_icagenda/admin/src/Model/RegistrationsModel.php
~ com_icagenda/admin/src/Utilities/Customfields/Customfields.php
~ com_icagenda/admin/utilities/customfields/customfields.php
~ com_icagenda/site/language/en-GB/en-GB.com_icagenda.ini
~ com_icagenda/site/models/submit.php
~ com_icagenda/site/src/Model/SubmitModel.php
+ com_icagenda/site/src/View/Events/FeedView.php
~ [THEME] com_icagenda/site/themes/packs/default/default_day.php
~ [THEME] com_icagenda/site/themes/packs/default/default_event.php
~ [THEME] com_icagenda/site/themes/packs/default/default_events.php
~ [THEME] com_icagenda/site/themes/packs/default/default_registration.php
~ [THEME] com_icagenda/site/themes/packs/ic_rounded/ic_rounded_day.php
~ [THEME] com_icagenda/site/themes/packs/ic_rounded/ic_rounded_event.php
~ [THEME] com_icagenda/site/themes/packs/ic_rounded/ic_rounded_events.php
~ [THEME] com_icagenda/site/themes/packs/ic_rounded/ic_rounded_registration.php
+ [PLUGIN][PRO] plg_finder_icagenda-pro/icagenda.php
+ [PLUGIN][PRO] plg_finder_icagenda-pro/icagenda.xml
+ [PLUGIN][PRO] plg_finder_icagenda-pro/language/en-GB/en-GB.plg_finder_icagenda.ini
+ [PLUGIN][PRO] plg_finder_icagenda-pro/language/en-GB/en-GB.plg_finder_icagenda.sys.ini


iCagenda 3.8.12 <small style="font-weight:normal;">(2022.12.31)</small>
================================================================================
~ [PRO][J4] Changed : Improve params processing for Pro Manager.
# [LOW][J4] Fixed : Improve params processing (issue to get event params in Pro module iC event list).
# [LOW][MODULE][PRO] Fixed : Display of registration info should be hidden if registration is disabled.
# [LOW][THEME] Fixed : Duplicated Category display in default theme list of events.

* Changed files in 3.8.12
~ com_icagenda/site/src/Assets/ListShortcuts.php
~ com_icagenda/site/src/Model/EventsModel.php
~ com_icagenda/site/themes/packs/default/default_events.php
~ [FILE][PRO] file_icagenda-pro/site/src/Model/ManagerModel.php
~ [FILE][PRO] file_icagenda-pro/site/src/View/Manager/HtmlView.php
~ [MODULE][PRO] mod_ic_event_list-pro/mod_ic_event_list.php
~ [PLUGIN][PRO] plg_icagenda-pro/pro.php


iCagenda 3.8.11 <small style="font-weight:normal;">(2022.12.21)</small>
================================================================================
+ Added : List filtered by category on Category label click (main list of events).
~ Changed : Use created by alias if set instead of username, in approval notification email body.
~ Changed : Prepare Finder Smart Search plugin (iCagenda Category state change).
# [LOW] Fixed : created_by_email for event.
# [LOW] Fixed : Text not found issue when error message; Event Edit and Registration forms.
# [LOW] Fixed : Published state in frontend event edit (hidden until display of unpublished events allowed with permissions).
# [LOW][J4] Fixed : Language layout rendering.

* Changed files in 3.8.11
~ com_icagenda/admin/models/event.php
~ com_icagenda/admin/src/Model/EventModel.php
~ com_icagenda/admin/src/Model/EventsModel.php
~ com_icagenda/admin/src/Model/iCategoryModel.php
~ com_icagenda/admin/src/Utilities/Event/Event.php
~ com_icagenda/admin/src/Utilities/Events/EventsList.php
~ com_icagenda/admin/src/Utilities/Manager/Manager.php
~ com_icagenda/admin/tmpl/event/edit.php
~ com_icagenda/admin/utilities/form/field/DeadlineTimeField.php
~ com_icagenda/admin/utilities/manager/manager.php
~ com_icagenda/admin/views/event/tmpl/edit.php
~ com_icagenda/icagenda.xml
~ [MEDIA] com_icagenda/media/css/icagenda.css
+ com_icagenda/site/helpers/route.php
~ com_icagenda/site/icagenda.php
~ com_icagenda/site/models/event.php
~ com_icagenda/site/models/registration.php
~ com_icagenda/site/src/Assets/ListShortcuts.php
+ com_icagenda/site/src/Helper/RouteHelper.php
~ com_icagenda/site/src/Model/EventModel.php
~ com_icagenda/site/src/Model/RegistrationModel.php
~ [THEME] com_icagenda/site/themes/packs/default/default_events.php
~ com_icagenda/site/tmpl/events/default_categories.php
~ com_icagenda/site/tmpl/registration/complete.php
~ com_icagenda/site/views/list/tmpl/default_categories.php
~ com_icagenda/site/views/registration/tmpl/complete.php
~ [FILE][PRO] file_icagenda-pro/site/models/forms/event.xml


iCagenda 3.8.10 <small style="font-weight:normal;">(2022.11.08)</small>
================================================================================
~ Changed : Improve Approval system.
~ Changed : Notification to the creator of an event in frontend, when this event is approved on admin side (same as approval in frontend).
# [LOW][J3] Fixed : Features storing on new event creation left empty.
# [LOW][THEME] Fixed : Wrong image variable in event details view (default theme)

* Changed files in 3.8.10
~ com_icagenda/admin/models/event.php
~ com_icagenda/admin/src/Model/EventModel.php
~ com_icagenda/admin/src/Utilities/Event/Event.php
~ com_icagenda/admin/src/Utilities/Manager/Manager.php
~ com_icagenda/admin/src/Utilities/Menus/Menus.php
~ com_icagenda/admin/tmpl/event/edit.php
~ com_icagenda/admin/utilities/event/event.php
~ com_icagenda/admin/utilities/manager/manager.php
~ com_icagenda/admin/views/event/tmpl/edit.php
~ [THEME] com_icagenda/site/themes/packs/default/default_event.php


iCagenda 3.8.9 <small style="font-weight:normal;">(2022.09.28)</small>
================================================================================
~ Changed : Integrate translateformat in calendar date picker.
~ Changed : Deprecate plugin event iCagendaOnListPrepare (Replace by onICagendaEventsPrepare).
~ Changed : Improve thumbs generator utilities.
# [LOW] Fixed : jquery-ui loading (sortable search filters).
# [LOW] Fixed : admin events list filtering (by date).
# [LOW] Fixed : status (published) filter for export registrations.
# [LOW] Fixed : Check date period (format error).
# [LOW] Fixed : gif thumb creatio error on PHP >= 8.1
# [LOW] Fixed : Frontend search filters default value fix on PHP >= 8.1
# [LOW] Fixed : Do not send newsletter to cancelled registrations.

* Changed files in 3.8.9
~ com_icagenda/admin/models/event.php
~ com_icagenda/admin/models/events.php
~ com_icagenda/admin/models/mail.php
~ com_icagenda/admin/models/forms/event.xml
~ com_icagenda/admin/src/Controller/RegistrationsController.php
~ com_icagenda/admin/src/Model/EventModel.php
~ com_icagenda/admin/src/Model/EventsModel.php
~ com_icagenda/admin/src/Model/MailModel.php
~ com_icagenda/admin/src/Utilities/Thumb/Thumb.php
~ com_icagenda/admin/utilities/thumb/thumb.php
~ com_icagenda/site/models/forms/submit.xml
~ com_icagenda/site/src/View/Events/HtmlView.php
~ com_icagenda/site/tmpl/events/default_filters.php
~ com_icagenda/site/views/list/view.html.php
~ com_icagenda/site/views/list/tmpl/default_filters.php
~ [FILE][PRO] file_icagenda-pro/site/models/forms/event.xml
~ [LIBRARY] lib_ic_library/Form/Field/SortableFieldsField.php
~ [LIBRARY] lib_ic_library/lib/form/field/sortablefields.php
~ [LIBRARY] lib_ic_library/lib/thumb/create.php
~ [LIBRARY] lib_ic_library/Thumb/Create.php


iCagenda 3.8.8 <small style="font-weight:normal;">(2022.08.17)</small>
================================================================================
+ Added : Registration deadline options in frontend Submit an Event form.
+ Added : Add button to clear Category filter + improvements.
+ [PRO] Added : Add missing registration options in frontend edit.
~ Changed : Improve controls if no categories published.
~ Changed : Improve Registration Cancellation date select with auto-select.
~ Changed : Improve email control for registration (in case a user register as a visitor with an email used by a joomla user, when logged-in, he will see his registration).
~ Changed : Replace deprecated icagendaEvents::dateToTimeFormat with icagendaRender::dateToFormat.
~ Changed : Improve isDate and isSerialized functions.
~ Changed : a few minor display improvements.
~ [PRO] Changed : Improve edit button layout and manager tools.
# [LOW] Fixed : deprecated functions on PHP >= 8.1
# [LOW] Fixed : htmlspecialchars(): Passing null to parameter on PHP >= 8.1
# [LOW] Fixed : Submit event form on PHP8 >= 8.1 (error on submit).
# [LOW][PRO] Fixed : Script error paypal plugin on event edit page.

* Changed files in 3.8.8
~ com_icagenda/admin/src/Utilities/Categories/Categories.php
~ com_icagenda/admin/src/Utilities/Customfields/Customfields.php
~ com_icagenda/admin/src/Utilities/Events/Events.php
~ com_icagenda/admin/src/Utilities/Events/EventsList.php
~ com_icagenda/admin/src/Utilities/Manager/Manager.php
~ com_icagenda/admin/src/Utilities/Registration/Registration.php
~ com_icagenda/admin/src/View/Event/HtmlView.php
~ com_icagenda/admin/src/View/Events/HtmlView.php
~ com_icagenda/admin/tmpl/icagenda/default.php
~ com_icagenda/admin/utilities/categories/categories.php
~ com_icagenda/admin/utilities/customfields/customfields.php
~ com_icagenda/admin/utilities/events/events.php
~ com_icagenda/admin/utilities/form/field/deadlinetimefield.php
~ com_icagenda/admin/utilities/list/list.php
~ com_icagenda/admin/utilities/manager/manager.php
~ com_icagenda/admin/utilities/registration/registration.php
~ com_icagenda/admin/views/icagenda/tmpl/default.php
~ [MEDIA] com_icagenda/media/css/icagenda.css
~ com_icagenda/site/add/elements/iclist_vars.php
~ com_icagenda/site/language/en-GB/en-GB.com_icagenda.ini
~ com_icagenda/site/models/registration.php
~ com_icagenda/site/models/submit.php
~ com_icagenda/site/models/forms/submit.xml
~ com_icagenda/site/src/Assets/ListShortcuts.php
~ com_icagenda/site/src/Model/RegistrationModel.php
~ com_icagenda/site/src/Model/SubmitModel.php
~ com_icagenda/site/src/View/Event/HtmlView.php
~ com_icagenda/site/src/View/Events/HtmlView.php
~ com_icagenda/site/src/View/Registration/HtmlView.php
~ com_icagenda/site/src/View/Submit/HtmlView.php
~ com_icagenda/site/tmpl/registration/cancel.php
~ com_icagenda/site/tmpl/submit/default.php
~ com_icagenda/site/views/event/view.html.php
~ com_icagenda/site/views/list/view.feed.php
~ com_icagenda/site/views/list/view.html.php
~ com_icagenda/site/views/registration/view.html.php
~ com_icagenda/site/views/registration/tmpl/cancel.php
~ com_icagenda/site/views/submit/view.html.php
~ [FILE][PRO] file_icagenda-pro/site/layouts/icagenda/manager/button/edit.php
~ [FILE][PRO] file_icagenda-pro/site/models/forms/event.xml
~ [FILE][PRO] file_icagenda-pro/site/src/View/Manager/HtmlView.php
~ [FILE][PRO] file_icagenda-pro/site/tmpl/manager/event_edit.php
~ [FILE][PRO] file_icagenda-pro/site/views/manager/view.html.php
~ [FILE][PRO] file_icagenda-pro/site/views/manager/tmpl/event_edit.php
~ [LIBRARY] lib_ic_library/Date/Date.php
~ [LIBRARY] lib_ic_library/Globalize/Globalize.php
~ [LIBRARY] lib_ic_library/lib/date/date.php
~ [LIBRARY] lib_ic_library/lib/globalize/globalize.php
~ [LIBRARY] lib_ic_library/lib/string/string.php
~ [LIBRARY] lib_ic_library/String/StringHelper.php
~ [MODULE][PRO] mod_ic_event_list-pro/helper.php
~ [MODULE] mod_iccalendar/helper.php
~ [MODULE] mod_iccalendar/mod_iccalendar.php
~ [PLUGIN][PRO] plg_icagenda-pro/pro.php
~ [PLUGIN][PRO] plg_icagenda_payment_paypal-pro/payment_paypal.php


iCagenda 3.8.7 <small style="font-weight:normal;">(2022.06.10)</small>
================================================================================
+ [J4] Added : Integrate Toggle Inline Help button in config and admin edit pages (new J4 functionality since 4.1).
+ [PLUGIN][PRO] Added : Option to display a text for information on the Payment Process page.
~ Changed : Improve registration actions layout display.
~ Changed : Improve getNext function when run in frontend submit an event form.
~ [THEME] Changed : Improve information details display (default theme).
~ [PLUGIN][PRO] Changed : Improve label/value display payment page.
- Removed : not needed option class ic-btn in submit an event form.
# [LOW][THEME] Fixed : Information details display when long text for detail (default theme).
# [LOW] Fixed : Icagenda/Administrator/table/Factory not found on registration storing in admin.
# [LOW] Fixed : Cancellation option display when use global on admin event edit.
# [LOW] Fixed : Frontend submit event form validation issue if registration options disabled.

* Changed files in 3.8.7
~ com_icagenda/admin/config.xml
~ com_icagenda/admin/language/en-GB/en-GB.com_icagenda.ini
~ com_icagenda/admin/models/event.php
~ com_icagenda/admin/models/forms/category.xml
~ com_icagenda/admin/models/forms/customfield.xml
~ com_icagenda/admin/models/forms/event.xml
~ com_icagenda/admin/models/forms/feature.xml
~ com_icagenda/admin/models/forms/registration.xml
~ com_icagenda/admin/src/Model/EventModel.php
~ com_icagenda/admin/src/Table/RegistrationTable.php
~ com_icagenda/admin/src/Utilities/Event/Event.php
~ com_icagenda/admin/src/View/Customfield/HtmlView.php
~ com_icagenda/admin/src/View/Event/HtmlView.php
~ com_icagenda/admin/src/View/Feature/HtmlView.php
~ com_icagenda/admin/src/View/Icategory/HtmlView.php
~ com_icagenda/admin/src/View/Registration/HtmlView.php
~ com_icagenda/admin/tmpl/icagenda/default.php
~ com_icagenda/admin/utilities/event/event.php
~ com_icagenda/admin/views/icagenda/tmpl/default.php
~ [MEDIA] com_icagenda/media/css/icagenda-front.css
~ com_icagenda/site/models/submit.php
~ com_icagenda/site/models/forms/submit.xml
~ com_icagenda/site/src/Model/SubmitModel.php
~ [THEME] com_icagenda/site/themes/packs/default/default_event.php
~ [THEME] com_icagenda/site/themes/packs/default/css/default_component.css
~ [THEME] com_icagenda/site/themes/packs/default/css/default_component_xsmall.css
~ com_icagenda/site/tmpl/registration/actions.php
~ com_icagenda/site/views/registration/tmpl/actions.php
~ [PLUGIN][PRO] plg_icagenda_payment_paypal-pro/forms/config_payment.xml
~ [PLUGIN][PRO] plg_icagenda_payment_paypal-pro/language/en-GB/en-GB.plg_icagenda_payment_paypal.ini
~ [PLUGIN][PRO] plg_icagenda_tickets-pro/layouts/payment.php
~ [PLUGIN] plg_system_icagenda/icagenda.php


iCagenda 3.8.6 <small style="font-weight:normal;">(2022.05.12)</small>
================================================================================
~ [THEME] Changed : Improve long filename display for attachment in event view.
# [LOW][PHP8] Fixed : Warning Undefined array key "extension" in event view.
# [LOW][PHP8] Fixed : Non-https URLs of OpenStreeMaps tileLayer.
# [LOW][J3] Fixed : Not-namespaced class Text in Submit frontend form.

* Changed files in 3.8.6
~ com_icagenda/admin/src/Utilities/Maps/Maps.php
~ com_icagenda/admin/src/Utilities/Render/Render.php
~ com_icagenda/admin/utilities/maps/maps.php
~ com_icagenda/admin/utilities/render/render.php
~ [THEME] com_icagenda/site/themes/packs/default/css/default_component.css
~ [THEME] com_icagenda/site/themes/packs/ic_rounded/css/ic_rounded_component.css
~ com_icagenda/site/views/submit/tmpl/default.php


iCagenda 3.8.5 <small style="font-weight:normal;">(2022.05.11)</small>
================================================================================
+ Added : Individual option for cancellation per event.
+ [PLUGIN][PRO] Added : Payment Conditions Consent option.
~ Changed : Improve consents and terms display and styles.
~ Changed : Use sprintf for thank you name language string on registration complete.
~ Changed : Improve cancel message when extended action on registration.
~ Changed : Add filename of attachment instead of "download".
# [LOW] Fixed : Wrong date invalid message on registration form submission when full period and registration type 'for all dates'.
# [LOW] Fixed : Not defined key on PHP8 in event edition.
# [LOW][J4] Fixed : json encoding error in custom form field groups manager on group creation.
# [LOW][J4] Fixed : Missing max tickets per registration form fields.
# [LOW][J4] Fixed : Missing checked_out support for null.
# [LOW][J4] Fixed : Empty trash button missing (custom fields and features).
# [LOW][J3] Fixed : Not-namespaced classes.
# [LOW][PLUGIN][PRO] Fixed : Paypal payment check when non-SEF URLs was broken.

* Changed files in 3.8.5
~ com_icagenda/admin/models/event.php
~ com_icagenda/admin/models/registrations.php
~ com_icagenda/admin/models/forms/event.xml
~ com_icagenda/admin/src/Model/EventModel.php
~ com_icagenda/admin/src/Model/RegistrationsModel.php
~ com_icagenda/admin/src/Table/CustomfieldTable.php
~ com_icagenda/admin/src/Table/FeatureTable.php
~ com_icagenda/admin/src/Table/RegistrationTable.php
~ com_icagenda/admin/src/Utilities/Event/Event.php
~ com_icagenda/admin/src/Utilities/Render/Render.php
~ com_icagenda/admin/src/View/Categories/HtmlView.php
~ com_icagenda/admin/src/View/Customfields/HtmlView.php
~ com_icagenda/admin/src/View/Events/HtmlView.php
~ com_icagenda/admin/src/View/Features/HtmlView.php
~ com_icagenda/admin/tmpl/event/edit.php
~ com_icagenda/admin/tmpl/registrations/default.php
~ com_icagenda/admin/utilities/event/event.php
~ com_icagenda/admin/utilities/form/field/customfieldgroups.php
~ com_icagenda/admin/utilities/form/field/registrationdates.php
~ com_icagenda/admin/utilities/form/field/terms.php
~ com_icagenda/admin/utilities/render/render.php
~ com_icagenda/admin/views/registrations/tmpl/default.php
~ [MEDIA] com_icagenda/media/css/icagenda-front.css
~ com_icagenda/site/controllers/registration.php
~ com_icagenda/site/language/en-GB/en-GB.com_icagenda.ini
~ com_icagenda/site/models/registration.php
~ com_icagenda/site/models/forms/registration.xml
~ com_icagenda/site/src/Controller/RegistrationController.php
~ com_icagenda/site/src/Model/RegistrationModel.php
~ com_icagenda/site/src/View/Registration/HtmlView.php
~ com_icagenda/site/tmpl/registration/complete.php
~ com_icagenda/site/tmpl/registration/default.php
~ com_icagenda/site/tmpl/submit/default.php
~ com_icagenda/site/views/registration/view.html.php
~ com_icagenda/site/views/registration/tmpl/complete.php
~ com_icagenda/site/views/registration/tmpl/default.php
~ com_icagenda/site/views/submit/tmpl/default.php
~ [PLUGIN][PRO] plg_icagenda_payment_paypal-pro/payment_paypal.php
~ [PLUGIN][PRO] plg_icagenda_payment_paypal-pro/forms/config_payment.xml
~ [PLUGIN][PRO] plg_icagenda_payment_paypal-pro/forms/event_actions.xml
+ [PLUGIN][PRO] plg_icagenda_payment_paypal-pro/forms/payment_consent.xml
~ [PLUGIN][PRO] plg_icagenda_payment_paypal-pro/language/en-GB/en-GB.plg_icagenda_payment_paypal.ini
~ [PLUGIN][PRO] plg_icagenda_payment_paypal-pro/layouts/payment_details.php


iCagenda 3.8.4 <small style="font-weight:normal;">(2022.04.15)</small>
================================================================================
! New Plugin System iCagenda to check iCagenda installation.
~ Changed : Improve form xmls (remove captcha desc, add website description).
~ Changed : Improve performance and code for some functions.
~ [MODULE] Changed : Ordering by event title when same time in tooltip.
# [MEDIUM] Fixed : Don't allow registration without date selected.
# [LOW] Fixed : End date not displayed for period and event was submitted in frontend.
# [LOW][J4] Fixed : After upgrade to J4, menu links to list of events broken (it was needed to set the menu item type again).
# [LOW][PRO] Fixed : Uninstall (protected extension can be used only by Joomla core extensions).

* Changed files in 3.8.4
~ pkg_icagenda.xml
~ script.icagenda.php
~ com_icagenda/script.com_icagenda.php
~ com_icagenda/admin/config.xml
~ com_icagenda/admin/icagenda.php
~ com_icagenda/admin/language/en-GB/en-GB.com_icagenda.ini
~ com_icagenda/admin/src/Controller/DisplayController.php
~ com_icagenda/admin/src/Extension/iCagendaComponent.php
~ com_icagenda/admin/src/Utilities/Registration/Registration.php
~ com_icagenda/admin/utilities/event/event.php
~ com_icagenda/admin/utilities/registration/registration.php
~ com_icagenda/admin/views/events/view.html.php
~ com_icagenda/site/icagenda.php
~ com_icagenda/site/language/en-GB/en-GB.com_icagenda.ini
~ com_icagenda/site/models/registration.php
~ com_icagenda/site/models/submit.php
~ com_icagenda/site/models/forms/registration.xml
~ com_icagenda/site/models/forms/submit.xml
~ com_icagenda/site/src/Controller/DisplayController.php
~ com_icagenda/site/src/Model/RegistrationModel.php
~ com_icagenda/site/src/Model/SubmitModel.php
~ com_icagenda/site/views/list/tmpl/default.xml
~ [FILE][PRO] file_icagenda-pro/site/models/forms/event.xml
~ [MODULE][PRO] mod_ic_event_list-pro/mod_ic_event_list.php
~ [MODULE][PRO] mod_ic_event_list-pro/language/en-GB/en-GB.mod_ic_event_list.ini
~ [MODULE] mod_iccalendar/helper.php
~ [MODULE] mod_iccalendar/mod_iccalendar.php
~ [MODULE] mod_iccalendar/language/en-GB/en-GB.mod_iccalendar.ini
~ [PLUGIN][PRO] plg_icagenda-pro/script.php
~ [PLUGIN] plg_system_ic_library/ic_library.php
+ [PLUGIN] plg_system_icagenda/icagenda.php
+ [PLUGIN] plg_system_icagenda/icagenda.xml
+ [PLUGIN] plg_system_icagenda/LICENSE.txt
+ [PLUGIN] plg_system_icagenda/language/en-GB/en-GB.plg_system_icagenda.ini
+ [PLUGIN] plg_system_icagenda/language/en-GB/en-GB.plg_system_icagenda.sys.ini


iCagenda 3.8.3 <small style="font-weight:normal;">(2022.03.24)</small>
================================================================================
~ Changed : Improve tags in registration notification email.
# [LOW] Fixed : ReCaptcha issue on Submit an Event form.
# [LOW][J4] Fixed : Theme pack installer on Joomla 4.1 (JArchive removed from Joomla api).
# [LOW][J4] Fixed : Error message if custom theme pack update url does not exist.
# [LOW][J4] Fixed : Routing missing itemid on captcha failed.

* Changed files in 3.8.3
~ com_icagenda/admin/src/Model/ThemesModel.php
~ com_icagenda/admin/src/Service/HTML/Themes.php
~ com_icagenda/site/models/registration.php
~ com_icagenda/site/models/submit.php
~ com_icagenda/site/models/forms/submit.xml
~ com_icagenda/site/src/Controller/SubmitController.php
~ com_icagenda/site/src/Model/RegistrationModel.php
~ com_icagenda/site/src/Model/SubmitModel.php
~ com_icagenda/site/tmpl/submit/default.php


iCagenda 3.8.2 <small style="font-weight:normal;">(2022.03.09)</small>
================================================================================
~ Changed : Improve Categories Information layout in header list.
~ Changed : Review registration complete layout.
~ Changed : Improve release controls.
~ [PRO] Changed : Review payment processed layout.
# [MEDIUM] Fixed : Registration State set on Cancelled (Should be set on Completed instead since 3.8.0; Not used before) for registrations processed prior to 3.8, and first iCagenda install prior to 3.6.
# [LOW] Fixed : Telephone should be hidden in frontend if empty.
# [LOW] Fixed : Unsupported operand types: string - string on new registration admin side.
# [LOW] Fixed : Missing default value for empty params on new registration created admin side.
# [LOW] Fixed : Missing default value for empty params on event submitted in frontend.
# [LOW][J4] Fixed : Changing Category status.
# [LOW][J4] Fixed : iCFilterOutput namespace missing in list view if metadesscription set.
# [LOW][J3] Fixed : Theme pack installer.

* Changed files in 3.8.2
~ com_icagenda/script.com_icagenda.php
~ com_icagenda/admin/config.xml
~ com_icagenda/admin/controllers/themes.php
~ com_icagenda/admin/models/registration.php
~ com_icagenda/admin/models/themes.php
~ com_icagenda/admin/src/Controller/CategoriesController.php
~ com_icagenda/admin/src/Model/IcategoryModel.php
~ com_icagenda/admin/src/Model/RegistrationModel.php
~ com_icagenda/admin/src/Utilities/Render/Render.php
~ com_icagenda/admin/utilities/render/render.php
~ com_icagenda/site/models/submit.php
~ com_icagenda/site/src/Model/EventsModel.php
~ com_icagenda/site/src/Model/SubmitModel.php
~ [THEME] com_icagenda/site/themes/packs/default/css/default_component.css
~ [THEME] com_icagenda/site/themes/packs/ic_rounded/css/ic_rounded_component.css
~ com_icagenda/site/tmpl/events/default_categories.php
~ com_icagenda/site/tmpl/registration/complete.php
~ com_icagenda/site/views/list/tmpl/default_categories.php
~ [PLUGIN] plg_icagenda_tickets-pro/layouts/payment.php


iCagenda 3.8.1 <small style="font-weight:normal;">(2022.03.02)</small>
================================================================================
~ Changed : Improve Telephone field in event contact details. Clickable phone number in frontend.
~ Changed : Update and include jQuery UI v.1.13.1
~ Changed : Update Utilities Maps using jQuery UI 1.13.1
# [LOW] Fixed : Google Maps JS API map display in admin edition.
# [LOW] Fixed : Namespace not found iClib\Url\Url if link to url.
# [LOW] Fixed : Routing when main list of events on home page.
# [LOW] Fixed : Registration button wrong text if user already registered for this date, but remaining tickets for this date.
# [LOW] Fixed : Registration button status when registration is in public access, email not required, and another visitor has already registered to this date.
# [LOW][PRO] Fixed : Missing namespace iCutilities in module iC Event List.

* Changed files in 3.8.1
~ com_icagenda/script.com_icagenda.php
~ com_icagenda/admin/models/forms/event.xml
~ com_icagenda/admin/src/Utilities/Event/Event.php
~ com_icagenda/admin/src/Utilities/Maps/Maps.php
~ com_icagenda/admin/src/Utilities/Maps/Google/Search.php
~ com_icagenda/admin/src/Utilities/Registration/Registration.php
~ com_icagenda/admin/src/Utilities/Render/Render.php
~ com_icagenda/admin/tmpl/event/edit.php
~ com_icagenda/admin/utilities/event/event.php
~ com_icagenda/admin/utilities/maps/maps.php
~ com_icagenda/admin/utilities/maps/google/search.php
~ com_icagenda/admin/utilities/registration/registration.php
~ com_icagenda/admin/utilities/render/render.php
~ com_icagenda/admin/views/event/tmpl/edit.php
- [MEDIA] com_icagenda/media/css/jquery-ui-1.8.17.custom.css
+ [MEDIA] com_icagenda/media/css/jquery-ui.css
+ [MEDIA] com_icagenda/media/css/jquery-ui.min.css
- [MEDIA] com_icagenda/media/css/images/ui-bg_flat_0_aaaaaa_40x100.png
- [MEDIA] com_icagenda/media/css/images/ui-bg_flat_0_eeeeee_40x100.png
- [MEDIA] com_icagenda/media/css/images/ui-bg_flat_55_c0402a_40x100.png
- [MEDIA] com_icagenda/media/css/images/ui-bg_flat_55_eeeeee_40x100.png
- [MEDIA] com_icagenda/media/css/images/ui-bg_flat_75_ffffff_40x100.png
- [MEDIA] com_icagenda/media/css/images/ui-bg_glass_100_f8f8f8_1x400.png
- [MEDIA] com_icagenda/media/css/images/ui-bg_glass_35_dddddd_1x400.png
- [MEDIA] com_icagenda/media/css/images/ui-bg_glass_55_fbf9ee_1x400.png
- [MEDIA] com_icagenda/media/css/images/ui-bg_glass_60_eeeeee_1x400.png
- [MEDIA] com_icagenda/media/css/images/ui-bg_glass_65_ffffff_1x400.png
- [MEDIA] com_icagenda/media/css/images/ui-bg_glass_75_dadada_1x400.png
- [MEDIA] com_icagenda/media/css/images/ui-bg_glass_75_e6e6e6_1x400.png
- [MEDIA] com_icagenda/media/css/images/ui-bg_glass_95_fef1ec_1x400.png
- [MEDIA] com_icagenda/media/css/images/ui-bg_highlight-soft_75_cccccc_1x100.png
- [MEDIA] com_icagenda/media/css/images/ui-bg_inset-hard_75_999999_1x100.png
- [MEDIA] com_icagenda/media/css/images/ui-bg_inset-soft_50_c9c9c9_1x100.png
- [MEDIA] com_icagenda/media/css/images/ui-icons_222222_256x240.png
- [MEDIA] com_icagenda/media/css/images/ui-icons_2e83ff_256x240.png
- [MEDIA] com_icagenda/media/css/images/ui-icons_3383bb_256x240.png
+ [MEDIA] com_icagenda/media/css/images/ui-icons_444444_256x240.png
- [MEDIA] com_icagenda/media/css/images/ui-icons_454545_256x240.png
+ [MEDIA] com_icagenda/media/css/images/ui-icons_555555_256x240.png
- [MEDIA] com_icagenda/media/css/images/ui-icons_63a459_256x240.png
+ [MEDIA] com_icagenda/media/css/images/ui-icons_777620_256x240.png
+ [MEDIA] com_icagenda/media/css/images/ui-icons_777777_256x240.png
- [MEDIA] com_icagenda/media/css/images/ui-icons_888888_256x240.png
- [MEDIA] com_icagenda/media/css/images/ui-icons_999999_256x240.png
+ [MEDIA] com_icagenda/media/css/images/ui-icons_cc0000_256x240.png
- [MEDIA] com_icagenda/media/css/images/ui-icons_cd0a0a_256x240.png
- [MEDIA] com_icagenda/media/css/images/ui-icons_fbc856_256x240.png
+ [MEDIA] com_icagenda/media/css/images/ui-icons_ffffff_256x240.png
+ [MEDIA] com_icagenda/media/js/jquery-ui.js
+ [MEDIA] com_icagenda/media/js/jquery-ui.min.js
~ com_icagenda/site/add/elements/icevent_vars.php
~ com_icagenda/site/layouts/icagenda/registration/button/register.php
~ com_icagenda/site/src/Assets/EventShortcuts.php
~ com_icagenda/site/src/Model/SubmitModel.php
~ com_icagenda/site/tmpl/submit/default.php
~ com_icagenda/site/views/submit/tmpl/default.php
~ [PLUGIN] plg_system_ic_library/ic_library.php


iCagenda 3.8.0 <small style="font-weight:normal;">(2022.02.22)</small>
================================================================================
! Announcement : Joomla 4 Ready.
1 Install/Update on Joomla 3 as well as on Joomla 4. It's a cross-platform version.
1 Joomla 3.10 as minimum version required.
1 PHP 8.0+ recommended (minimum 7.2).
! [PRO] New Frontend Event Edition: Bouton 'Edit' on event page when user has ACL permissions to edit event.
! [PRO] New plugin iCagenda Pro to load Pro Options and Functionalities.
! [PRO] Payment PayPal plugin
1 Set ticket price and sell event tickets!
! [PRO] Tickets plugin
1 To integrate add-on plugins with iCagenda registration form (payment, ...)
! [PRO][J4] Versioning, automatically save old versions of a Category or an Event.
! New router integration with ID removal option (check new tab 'Integration').
! New Map Service: Leaflet OpenStreetMap.
! Registration cancellation feature.
! Register button redesign (integration of cancellation system, HTML moved to a layout...)
! Refactoring : a bunch of files and code changes, improvements, reviews.
+ Added : New svg icons for Calendars in Add to Cal.
+ Added : New iCdropdown script (Add to Cal and select another date button dropdown).
+ Added : New option Deadline for Registration.
+ Added : New SEF routing.
+ Added : Custom field individual handler for tag in email notification for registration. Use [CUSTOMFIELD slug]
+ Added : registration status.
+ Added[J4] : Admin emptystate layouts.
+ [PRO] Added : New layout for edit button.
+ [PRO] Added :  Frontend event edit to Joomla actionlogs.
~ Changed : Update iCalcreator library to 2.30.10
~ Changed : Improve alias control (we need unique alias for no-ids routing).
~ Changed : Run stored settings in data control only on iCagenda Update (not required on fresh install).
~ Changed : Set OpenStreetMap as default on fresh install.
~ Changed : iCicons v2
~ Changed : Update iCagenda admin Menu Items.
~ Changed : Config xml files Display Review (component, menus, modules).
~ Changed : Update Outlook Live Calendar link.
~ Changed : Update Add to Cal button - Use svg icons for calendars.
~ Changed : 'datesDisplay' field, update value of option 'No'.
~ Changed : Improve Search Filters options display.
~ Changed : Set search current list as default (fresh install).
~ Changed : Clear all options xml files.
~ Changed : Update & clean addtocal icons.
~ Changed : Default registration access level set to Registered.
~ Changed : Set 'ic-map-wrapper' class for all map services; Clean and improve map display.
~ Changed : Improve Register button layouts.
~ Changed : Improve and fix file attachment uploader.
~ Changed : Update deprecated Factory getSession.
~ Changed : Improve registration status checking.
~ Changed : Event top layout: migrate icons library utility.
~ Changed : Remove time picker library (use Joomla calendar field). New single dates layout.
~ Changed : Refactory Custom Fields loader (for consistency with submit).
~ Changed : Refactory multiple form fields and move to utilities.
~ Changed : Code improvements and cleaning.
~ [THEME] Changed : Improve themes css.
~ [THEME] Changed : Improve H tag for event titles in list view.
~ [THEME] Changed : Deprecate 'ic-event' class for 'ic-list-event'.
- Removed : Form validation method option (front & server as default).
- Removed : Map width and height options in menu options (use custom css on 'ic-map-wrapper' class)
- Removed : Deprecated icagendaGooglemaps class (use icagendaMaps).
- Removed : Deprecated form fields.
- [THEME] Removed : class 'ic-event'.
# [MEDIUM] Fixed : Event duplicate aliases.
# [LOW] Fixed : Removal not used All Categories option in multiple categories select list.
# [LOW] Fixed : Option one registration per date not working as expected (issue wrong button on event details view).
# [LOW] Fixed : Error if short_open_tag disabled on server.
# [LOW][MODULE] Fixed : 'Close'/'No ticket left' status in module iC Calendar.
# [LOW][PHP8] Fixed : Warning deprecated $typeReg.
# [LOW][PHP8] Fixed : php warning for thumbs on fresh install.
# [LOW][PHP8] Fixed : SQL 'desc' error.
# [LOW][PHP8] Fixed : Cancel button issue in admin Newsletter (send mail to participants).
# [LOW][PRO TESTING][FIX RC2] Fixed : SEF urls in Registration notification emails.
# [LOW][PRO TESTING][FIX RC2] Fixed : Required id for renderField layout (no control from Joomla layout if isset).
# [LOW][PRO TESTING][FIX RC2] Fixed : 'm.id' and 'm.language' errors on frontend submit form.
# [LOW][PRO TESTING][FIX RC2] Fixed : Select another dater list Urls (wrong menu item used for routing).

* Changed files in 3.8.0 (since 3.7.21)
~ pkg_icagenda.xml
~ script.icagenda.php
~ com_icagenda/icagenda.xml
~ com_icagenda/script.com_icagenda.php
~ com_icagenda/admin/config.xml
~ com_icagenda/admin/icagenda.php
~ com_icagenda/admin/assets/elements/desc.php
~ com_icagenda/admin/assets/elements/titleimg.php
~ com_icagenda/admin/controllers/categories.php
~ com_icagenda/admin/controllers/category.php
~ com_icagenda/admin/controllers/customfield.php
~ com_icagenda/admin/controllers/customfields.php
~ com_icagenda/admin/controllers/event.php
~ com_icagenda/admin/controllers/events.php
~ com_icagenda/admin/controllers/feature.php
~ com_icagenda/admin/controllers/features.php
~ com_icagenda/admin/controllers/icagenda.php
~ com_icagenda/admin/controllers/registration.php
~ com_icagenda/admin/controllers/registrations.php
~ com_icagenda/admin/controllers/registrations.raw.php
~ com_icagenda/admin/helpers/icagenda.php
~ com_icagenda/admin/helpers/html/events.php
~ com_icagenda/admin/language/en-GB/en-GB.com_icagenda.ini
~ com_icagenda/admin/language/en-GB/en-GB.com_icagenda.sys.ini
+ com_icagenda/admin/layouts/icagenda/admin/footer.php
+ com_icagenda/admin/layouts/icagenda/admin/logo.php
+ com_icagenda/admin/layouts/icagenda/admin/theme_pack_item.php
+ com_icagenda/admin/layouts/joomla/form/field/subform/repeatable/section.php
~ com_icagenda/admin/models/customfield.php
~ com_icagenda/admin/models/event.php
~ com_icagenda/admin/models/events.php
~ com_icagenda/admin/models/registrations.php
~ com_icagenda/admin/models/fields/list.php
+ com_icagenda/admin/models/fields/config/emailtags.php
~ com_icagenda/admin/models/fields/iclist/globalization.php
~ com_icagenda/admin/models/fields/icmap/city.php
~ com_icagenda/admin/models/fields/modal/cat.php
- com_icagenda/admin/models/fields/modal/color.php
- com_icagenda/admin/models/fields/modal/date.php
- com_icagenda/admin/models/fields/modal/enddate.php
~ com_icagenda/admin/models/fields/modal/evt.php
~ com_icagenda/admin/models/fields/modal/evt_date.php
~ com_icagenda/admin/models/fields/modal/ic_editor.php
~ com_icagenda/admin/models/fields/modal/icfile.php
- com_icagenda/admin/models/fields/modal/iclink_article.php
- com_icagenda/admin/models/fields/modal/iclink_type.php
- com_icagenda/admin/models/fields/modal/iclink_url.php
- com_icagenda/admin/models/fields/modal/icmulti_checkbox.php
- com_icagenda/admin/models/fields/modal/icmulti_opt.php
- com_icagenda/admin/models/fields/modal/ictext_content.php
- com_icagenda/admin/models/fields/modal/ictext_placeholder.php
- com_icagenda/admin/models/fields/modal/ictext_type.php
- com_icagenda/admin/models/fields/modal/ictextarea_counter.php
- com_icagenda/admin/models/fields/modal/ictxt_article.php
- com_icagenda/admin/models/fields/modal/ictxt_content.php
- com_icagenda/admin/models/fields/modal/ictxt_default.php
- com_icagenda/admin/models/fields/modal/ictxt_type.php
- com_icagenda/admin/models/fields/modal/media.php
~ com_icagenda/admin/models/fields/modal/menulink.php
~ com_icagenda/admin/models/fields/modal/multicat.php
- com_icagenda/admin/models/fields/modal/param_place.php
- com_icagenda/admin/models/fields/modal/period.php
- com_icagenda/admin/models/fields/modal/ph_regbt.php
- com_icagenda/admin/models/fields/modal/startdate.php
~ com_icagenda/admin/models/fields/modal/thumbs.php
- com_icagenda/admin/models/fields/modal/tos_article.php
- com_icagenda/admin/models/fields/modal/tos_content.php
- com_icagenda/admin/models/fields/modal/tos_default.php
- com_icagenda/admin/models/fields/modal/tos_type.php
~ com_icagenda/admin/models/forms/category.xml
~ com_icagenda/admin/models/forms/customfield.xml
~ com_icagenda/admin/models/forms/event.xml
~ com_icagenda/admin/models/forms/feature.xml
+ com_icagenda/admin/models/forms/filter_categories.xml
+ com_icagenda/admin/models/forms/filter_customfields.xml
+ com_icagenda/admin/models/forms/filter_events.xml
+ com_icagenda/admin/models/forms/filter_features.xml
+ com_icagenda/admin/models/forms/filter_registrations.xml
~ com_icagenda/admin/models/forms/mail.xml
~ com_icagenda/admin/models/forms/registration.xml
+ com_icagenda/admin/services/provider.php
~ com_icagenda/admin/sql/install/mysql/icagenda.install.sql
+ com_icagenda/admin/src/Controller/CategoriesController.php
+ com_icagenda/admin/src/Controller/CustomfieldController.php
+ com_icagenda/admin/src/Controller/CustomfieldsController.php
+ com_icagenda/admin/src/Controller/DisplayController.php
+ com_icagenda/admin/src/Controller/EventController.php
+ com_icagenda/admin/src/Controller/EventsController.php
+ com_icagenda/admin/src/Controller/FeatureController.php
+ com_icagenda/admin/src/Controller/FeaturesController.php
+ com_icagenda/admin/src/Controller/IcategoryController.php
+ com_icagenda/admin/src/Controller/MailController.php
+ com_icagenda/admin/src/Controller/RegistrationController.php
+ com_icagenda/admin/src/Controller/RegistrationsController.php
+ com_icagenda/admin/src/Controller/ThemesController.php
+ com_icagenda/admin/src/Extension/iCagendaComponent.php
+ com_icagenda/admin/src/Field/CustomfieldGroupsField.php
+ com_icagenda/admin/src/Field/MultipleCategoryField.php
+ com_icagenda/admin/src/Helper/iCagendaHelper.php
+ com_icagenda/admin/src/Model/CategoriesModel.php
+ com_icagenda/admin/src/Model/CustomfieldModel.php
+ com_icagenda/admin/src/Model/CustomfieldsModel.php
+ com_icagenda/admin/src/Model/DownloadModel.php
+ com_icagenda/admin/src/Model/EventModel.php
+ com_icagenda/admin/src/Model/EventsModel.php
+ com_icagenda/admin/src/Model/FeatureModel.php
+ com_icagenda/admin/src/Model/FeaturesModel.php
+ com_icagenda/admin/src/Model/IcagendaModel.php
+ com_icagenda/admin/src/Model/IcategoryModel.php
+ com_icagenda/admin/src/Model/InfoModel.php
+ com_icagenda/admin/src/Model/MailModel.php
+ com_icagenda/admin/src/Model/RegistrationModel.php
+ com_icagenda/admin/src/Model/RegistrationsModel.php
+ com_icagenda/admin/src/Model/ThemesModel.php
+ com_icagenda/admin/src/Service/HTML/Themes.php
+ com_icagenda/admin/src/Table/CustomfieldTable.php
+ com_icagenda/admin/src/Table/EventTable.php
+ com_icagenda/admin/src/Table/FeatureTable.php
+ com_icagenda/admin/src/Table/IcategoryTable.php
+ com_icagenda/admin/src/Table/RegistrationTable.php
+ com_icagenda/admin/src/Utilities/AddThis/AddThis.php
+ com_icagenda/admin/src/Utilities/AddToCal/AddToCal.php
+ com_icagenda/admin/src/Utilities/Ajax/Ajax.php
+ com_icagenda/admin/src/Utilities/Ajax/Filter.php
+ com_icagenda/admin/src/Utilities/Categories/Categories.php
+ com_icagenda/admin/src/Utilities/Customfields/Customfields.php
+ com_icagenda/admin/src/Utilities/Event/Event.php
+ com_icagenda/admin/src/Utilities/Event/EventData.php
+ com_icagenda/admin/src/Utilities/Events/Events.php
+ com_icagenda/admin/src/Utilities/Events/EventsData.php
+ com_icagenda/admin/src/Utilities/Events/EventsList.php
+ com_icagenda/admin/src/Utilities/Form/Form.php
+ com_icagenda/admin/src/Utilities/Icons/Icons.php
+ com_icagenda/admin/src/Utilities/Info/Info.php
+ com_icagenda/admin/src/Utilities/Manager/Manager.php
+ com_icagenda/admin/src/Utilities/Maps/Maps.php
+ com_icagenda/admin/src/Utilities/Maps/Google/Google.php
+ com_icagenda/admin/src/Utilities/Maps/Google/Search.php
+ com_icagenda/admin/src/Utilities/Maps/Leaflet/Leaflet.php
+ com_icagenda/admin/src/Utilities/Maps/Leaflet/Search.php
+ com_icagenda/admin/src/Utilities/Menus/Menus.php
+ com_icagenda/admin/src/Utilities/Registration/Participants.php
+ com_icagenda/admin/src/Utilities/Registration/Registration.php
+ com_icagenda/admin/src/Utilities/Render/Render.php
+ com_icagenda/admin/src/Utilities/Router/Router.php
+ com_icagenda/admin/src/Utilities/Theme/Style.php
+ com_icagenda/admin/src/Utilities/Theme/Theme.php
+ com_icagenda/admin/src/Utilities/Thumb/Thumb.php
+ com_icagenda/admin/src/Utilities/Tiptip/Tiptip.php
+ com_icagenda/admin/src/Utilities/Update/icagendaUpdate.php
+ com_icagenda/admin/src/Utilities/Utilities/Utilities.php
+ com_icagenda/admin/src/View/Categories/HtmlView.php
+ com_icagenda/admin/src/View/Customfield/HtmlView.php
+ com_icagenda/admin/src/View/Customfields/HtmlView.php
+ com_icagenda/admin/src/View/Download/HtmlView.php
+ com_icagenda/admin/src/View/Event/HtmlView.php
+ com_icagenda/admin/src/View/Events/HtmlView.php
+ com_icagenda/admin/src/View/Feature/HtmlView.php
+ com_icagenda/admin/src/View/Features/HtmlView.php
+ com_icagenda/admin/src/View/Icagenda/HtmlView.php
+ com_icagenda/admin/src/View/Icategory/HtmlView.php
+ com_icagenda/admin/src/View/Info/HtmlView.php
+ com_icagenda/admin/src/View/Mail/HtmlView.php
+ com_icagenda/admin/src/View/Registration/HtmlView.php
+ com_icagenda/admin/src/View/Registrations/HtmlView.php
+ com_icagenda/admin/src/View/Registrations/RawView.php
+ com_icagenda/admin/src/View/Themes/HtmlView.php
~ com_icagenda/admin/tables/category.php
~ com_icagenda/admin/tables/customfield.php
~ com_icagenda/admin/tables/event.php
~ com_icagenda/admin/tables/feature.php
~ com_icagenda/admin/tables/registration.php
+ com_icagenda/admin/tmpl/categories/default.php
+ com_icagenda/admin/tmpl/categories/emptystate.php
+ com_icagenda/admin/tmpl/customfield/edit.php
+ com_icagenda/admin/tmpl/customfields/default.php
+ com_icagenda/admin/tmpl/customfields/emptystate.php
+ com_icagenda/admin/tmpl/download/default.php
+ com_icagenda/admin/tmpl/event/edit.php
+ com_icagenda/admin/tmpl/events/default.php
+ com_icagenda/admin/tmpl/events/emptystate.php
+ com_icagenda/admin/tmpl/feature/edit.php
+ com_icagenda/admin/tmpl/features/default.php
+ com_icagenda/admin/tmpl/features/emptystate.php
+ com_icagenda/admin/tmpl/icagenda/color.php
+ com_icagenda/admin/tmpl/icagenda/default.php
+ com_icagenda/admin/tmpl/icagenda/default_modal_changelog.php
+ com_icagenda/admin/tmpl/icagenda/default_modal_pro.php
+ com_icagenda/admin/tmpl/icategory/edit.php
+ com_icagenda/admin/tmpl/info/default.php
+ com_icagenda/admin/tmpl/mail/edit.php
+ com_icagenda/admin/tmpl/registration/edit.php
+ com_icagenda/admin/tmpl/registrations/default.php
+ com_icagenda/admin/tmpl/registrations/emptystate.php
+ com_icagenda/admin/tmpl/themes/default.php
~ com_icagenda/admin/utilities/addtocal/addtocal.php
~ com_icagenda/admin/utilities/ajax/filter.php
~ com_icagenda/admin/utilities/customfields/customfields.php
~ com_icagenda/admin/utilities/event/event.php
~ com_icagenda/admin/utilities/events/data.php
~ com_icagenda/admin/utilities/form/form.php
+ com_icagenda/admin/utilities/form/field/categoryselect.php
~ com_icagenda/admin/utilities/form/field/configtermsdefault.php
~ com_icagenda/admin/utilities/form/field/customfieldgroups.php
~ com_icagenda/admin/utilities/form/field/customform.php
+ com_icagenda/admin/utilities/form/field/deadlinefield.php
+ com_icagenda/admin/utilities/form/field/deadlinetimefield.php
+ com_icagenda/admin/utilities/form/field/filtercategories.php
+ com_icagenda/admin/utilities/form/field/filtercustomfieldgroups.php
+ com_icagenda/admin/utilities/form/field/filtercustomfieldtypes.php
+ com_icagenda/admin/utilities/form/field/filterdates.php
+ com_icagenda/admin/utilities/form/field/filterevents.php
+ com_icagenda/admin/utilities/form/field/multiplecategory.php
~ com_icagenda/admin/utilities/form/field/registrationdates.php
~ com_icagenda/admin/utilities/form/field/registrationpeople.php
~ com_icagenda/admin/utilities/form/field/registrationterms.php
+ com_icagenda/admin/utilities/form/field/separator.php
+ com_icagenda/admin/utilities/form/field/submitmenuitemid.php
~ com_icagenda/admin/utilities/form/field/terms.php
+ com_icagenda/admin/utilities/form/field/textareacounter.php
- com_icagenda/admin/utilities/googlemaps/googlemaps.php
+ com_icagenda/admin/utilities/icons/icons.php
~ com_icagenda/admin/utilities/manager/manager.php
+ com_icagenda/admin/utilities/maps/google.php
+ com_icagenda/admin/utilities/maps/leaflet.php
+ com_icagenda/admin/utilities/maps/maps.php
+ com_icagenda/admin/utilities/maps/google/search.php
+ com_icagenda/admin/utilities/maps/leaflet/search.php
~ com_icagenda/admin/utilities/menus/menus.php
~ com_icagenda/admin/utilities/params/params.php
~ com_icagenda/admin/utilities/registration/participants.php
~ com_icagenda/admin/utilities/registration/registration.php
~ com_icagenda/admin/utilities/render/render.php
+ com_icagenda/admin/utilities/tiptip/tiptip.php
~ com_icagenda/admin/views/event/view.html.php
~ com_icagenda/admin/views/event/tmpl/edit.php
~ com_icagenda/admin/views/events/tmpl/default.php
~ com_icagenda/admin/views/icagenda/tmpl/default.php
~ com_icagenda/admin/views/info/tmpl/default.php
~ com_icagenda/admin/views/registration/tmpl/edit.php
~ com_icagenda/admin/views/registrations/view.html.php
~ com_icagenda/admin/views/registrations/tmpl/default.php
~ com_icagenda/admin/views/themes/tmpl/default.php
~ [MEDIA] com_icagenda/media/css/icagenda-back.css
~ [MEDIA] com_icagenda/media/css/icagenda-front.css
~ [MEDIA] com_icagenda/media/css/icagenda.css
~ [MEDIA] com_icagenda/media/icicons/style.css
~ [MEDIA] com_icagenda/media/icicons/fonts/iCicons.eot
~ [MEDIA] com_icagenda/media/icicons/fonts/iCicons.svg
~ [MEDIA] com_icagenda/media/icicons/fonts/iCicons.ttf
~ [MEDIA] com_icagenda/media/icicons/fonts/iCicons.woff
- [MEDIA] com_icagenda/media/icicons/ie7/ie7.css
- [MEDIA] com_icagenda/media/icicons/ie7/ie7.js
- [MEDIA] com_icagenda/media/images/addthis_16.png
- [MEDIA] com_icagenda/media/images/addthis_16x16.png
- [MEDIA] com_icagenda/media/images/addthis_32x32.png
- [MEDIA] com_icagenda/media/images/all_cats-16.png
- [MEDIA] com_icagenda/media/images/all_events-16.png
- [MEDIA] com_icagenda/media/images/blanck.png
- [MEDIA] com_icagenda/media/images/border_title.png
- [MEDIA] com_icagenda/media/images/btn-regis.png
- [MEDIA] com_icagenda/media/images/customfields-16.png
- [MEDIA] com_icagenda/media/images/features-16.png
- [MEDIA] com_icagenda/media/images/generic-48.png
+ [MEDIA] com_icagenda/media/images/icagenda-light.png
+ [MEDIA] com_icagenda/media/images/icagenda.png
- [MEDIA] com_icagenda/media/images/icon-add-16.png
- [MEDIA] com_icagenda/media/images/icon-edit.png
- [MEDIA] com_icagenda/media/images/icon_all-events.png
- [MEDIA] com_icagenda/media/images/iconevent-add48.png
- [MEDIA] com_icagenda/media/images/iconevent48.png
- [MEDIA] com_icagenda/media/images/iconicagenda16.png
- [MEDIA] com_icagenda/media/images/iconicagenda16_agenda.png
- [MEDIA] com_icagenda/media/images/image.png
- [MEDIA] com_icagenda/media/images/info-16.png
- [MEDIA] com_icagenda/media/images/info.png
- [MEDIA] com_icagenda/media/images/joomlic_iCagenda.png
- [MEDIA] com_icagenda/media/images/loader.gif
- [MEDIA] com_icagenda/media/images/new_cat-16.png
- [MEDIA] com_icagenda/media/images/new_event-16.png
- [MEDIA] com_icagenda/media/images/newsletter-16.png
- [MEDIA] com_icagenda/media/images/no-photo.jpg
- [MEDIA] com_icagenda/media/images/photo.jpg
- [MEDIA] com_icagenda/media/images/registration-16.png
- [MEDIA] com_icagenda/media/images/shadow.png
- [MEDIA] com_icagenda/media/images/technical_requirements-16.png
- [MEDIA] com_icagenda/media/images/themes-16.png
+ [MEDIA][FOLDER] com_icagenda/media/images/addtocal/png/
+ [MEDIA][FOLDER] com_icagenda/media/images/addtocal/svg/
- [MEDIA][FOLDER] com_icagenda/media/images/cal/
- [MEDIA][FOLDER] com_icagenda/media/images/manager/
- [MEDIA] com_icagenda/media/js/icagenda.js
- [MEDIA] com_icagenda/media/js/icdates.js
+ [MEDIA] com_icagenda/media/js/iCdropdown.js
+ [MEDIA] com_icagenda/media/js/iCdropdown.min.js
~ [MEDIA] com_icagenda/media/js/icform.js
- [MEDIA] com_icagenda/media/js/timepicker.js
+ [MEDIA][FOLDER] com_icagenda/media/leaflet/
+ [MEDIA][FOLDER] com_icagenda/media/leaflet/images/
+ [MEDIA][FOLDER] com_icagenda/media/leaflet/plugins/
+ [MEDIA][FOLDER] com_icagenda/media/leaflet/plugins/images/
+ [MEDIA][FOLDER] com_icagenda/media/leaflet/plugins/search/
~ com_icagenda/site/controller.php
~ com_icagenda/site/icagenda.php
~ com_icagenda/site/router.php
~ com_icagenda/site/add/elements/icevent_vars.php
~ com_icagenda/site/add/elements/iclist_vars.php
~ com_icagenda/site/controllers/registration.php
+ com_icagenda/site/controllers/submit.php
- com_icagenda/site/helpers/iCicons.class.php
~ com_icagenda/site/language/en-GB/en-GB.com_icagenda.ini
+ com_icagenda/site/layouts/icagenda/registration/button/box.php
+ com_icagenda/site/layouts/icagenda/registration/button/cancel.php
+ com_icagenda/site/layouts/icagenda/registration/button/info.php
+ com_icagenda/site/layouts/icagenda/registration/button/register.php
+ com_icagenda/site/layouts/joomla/form/field/subform/repeatable.php
+ com_icagenda/site/layouts/joomla/form/field/subform/repeatable/section.php
~ com_icagenda/site/models/event.php
~ com_icagenda/site/models/events.php
~ com_icagenda/site/models/list.php
~ com_icagenda/site/models/registration.php
~ com_icagenda/site/models/submit.php
~ com_icagenda/site/models/fields/categories.php
~ com_icagenda/site/models/fields/year.php
~ com_icagenda/site/models/forms/registration.xml
~ com_icagenda/site/models/forms/submit.xml
+ com_icagenda/site/src/Assets/EventShortcuts.php
+ com_icagenda/site/src/Assets/ListShortcuts.php
+ com_icagenda/site/src/Controller/DisplayController.php
+ com_icagenda/site/src/Controller/RegistrationController.php
+ com_icagenda/site/src/Controller/SubmitController.php
+ com_icagenda/site/src/Model/EventModel.php
+ com_icagenda/site/src/Model/EventsModel.php
+ com_icagenda/site/src/Model/RegistrationModel.php
+ com_icagenda/site/src/Model/SubmitModel.php
+ com_icagenda/site/src/Service/Router.php
+ com_icagenda/site/src/View/Event/HtmlView.php
+ com_icagenda/site/src/View/Events/HtmlView.php
+ com_icagenda/site/src/View/Registration/HtmlView.php
+ com_icagenda/site/src/View/Submit/HtmlView.php
~ [THEME] com_icagenda/site/themes/default.xml
~ [THEME] com_icagenda/site/themes/ic_rounded.xml
~ [THEME] com_icagenda/site/themes/packs/default/default_event.php
~ [THEME] com_icagenda/site/themes/packs/default/default_events.php
~ [THEME] com_icagenda/site/themes/packs/default/css/default_component.css
~ [THEME] com_icagenda/site/themes/packs/ic_rounded/ic_rounded_event.php
~ [THEME] com_icagenda/site/themes/packs/ic_rounded/ic_rounded_events.php
~ [THEME] com_icagenda/site/themes/packs/ic_rounded/css/ic_rounded_component.css
+ com_icagenda/site/tmpl/event/default.php
+ com_icagenda/site/tmpl/event/default_top.php
+ com_icagenda/site/tmpl/event/default_vcal.php
+ com_icagenda/site/tmpl/events/default.php
+ com_icagenda/site/tmpl/events/default.xml
+ com_icagenda/site/tmpl/events/default_categories.php
+ com_icagenda/site/tmpl/events/default_filters.php
+ com_icagenda/site/tmpl/registration/actions.php
+ com_icagenda/site/tmpl/registration/cancel.php
+ com_icagenda/site/tmpl/registration/complete.php
+ com_icagenda/site/tmpl/registration/default.php
+ com_icagenda/site/tmpl/submit/default.php
+ com_icagenda/site/tmpl/submit/default.xml
+ com_icagenda/site/tmpl/submit/send.php
~ com_icagenda/site/views/event/view.html.php
~ com_icagenda/site/views/event/tmpl/default_top.php
~ com_icagenda/site/views/event/tmpl/default_vcal.php
~ com_icagenda/site/views/list/view.html.php
~ com_icagenda/site/views/list/tmpl/default.php
~ com_icagenda/site/views/list/tmpl/default.xml
~ com_icagenda/site/views/list/tmpl/default_categories.php
~ com_icagenda/site/views/list/tmpl/default_filters.php
~ com_icagenda/site/views/registration/view.html.php
~ com_icagenda/site/views/registration/tmpl/actions.php
~ com_icagenda/site/views/registration/tmpl/cancel.php
~ com_icagenda/site/views/registration/tmpl/complete.php
~ com_icagenda/site/views/registration/tmpl/default.php
~ com_icagenda/site/views/submit/view.html.php
~ com_icagenda/site/views/submit/tmpl/default.php
~ com_icagenda/site/views/submit/tmpl/default.xml
~ com_icagenda/site/views/submit/tmpl/send.php

+ [FILE][PRO] file_icagenda-pro/file_icagenda.xml
+ [FILE][PRO] file_icagenda-pro/site/controllers/event.php
+ [FILE][PRO] file_icagenda-pro/site/layouts/icagenda/manager/button/edit.php
+ [FILE][PRO] file_icagenda-pro/site/models/manager.php
+ [FILE][PRO] file_icagenda-pro/site/models/forms/event.xml
+ [FILE][PRO] file_icagenda-pro/site/src/Controller/EventController.php
+ [FILE][PRO] file_icagenda-pro/site/src/Model/ManagerModel.php
+ [FILE][PRO] file_icagenda-pro/site/src/View/Manager/HtmlView.php
+ [FILE][PRO] file_icagenda-pro/site/tmpl/manager/event_edit.php
+ [FILE][PRO] file_icagenda-pro/site/views/manager/view.html.php
+ [FILE][PRO] file_icagenda-pro/site/views/manager/tmpl/event_edit.php

~ [LIBRARY] lib_ic_library/lib_ic_library.xml
~ [LIBRARY] lib_ic_library/ -> lib_ic_library/lib/ (J3 lib)
~ [LIBRARY] lib_ic_library/Color/Color.php
~ [LIBRARY] lib_ic_library/Date/Date.php
~ [LIBRARY] lib_ic_library/Date/Period.php
~ [LIBRARY] lib_ic_library/File/File.php
~ [LIBRARY] lib_ic_library/Filter/Output.php
+ [LIBRARY] lib_ic_library/Form/Field/SortableFieldsField.php
+ [LIBRARY] lib_ic_library/Form/Rule/PositiveIntegerRule.php
~ [LIBRARY] lib_ic_library/Globalize/Convert.php
~ [LIBRARY] lib_ic_library/Globalize/Globalize.php
- [LIBRARY] lib_ic_library/iCalcreator/iCalcreator.class.php
~ [LIBRARY] lib_ic_library/language/en-GB/en-GB.lib_ic_library.ini
~ [LIBRARY] lib_ic_library/Library/Library.php
~ [LIBRARY] lib_ic_library/Render/Render.php
- [LIBRARY] lib_ic_library/String/string.php
+ [LIBRARY] lib_ic_library/String/StringHelper.php
~ [LIBRARY] lib_ic_library/Thumb/Create.php
~ [LIBRARY] lib_ic_library/Thumb/Get.php
~ [LIBRARY] lib_ic_library/Thumb/Image.php
~ [LIBRARY] lib_ic_library/Url/Url.php
+ [LIBRARY] lib_ic_library/Vendor/IcalcreatorLibrary.php
+ [LIBRARY][FOLDER] lib_ic_library/Vendor/Icalcreator/

~ [MODULE][PRO] mod_ic_event_list-pro/helper.php
~ [MODULE][PRO] mod_ic_event_list-pro/mod_ic_event_list.php
~ [MODULE][PRO] mod_ic_event_list-pro/mod_ic_event_list.xml
~ [MODULE][PRO] mod_ic_event_list-pro/language/en-GB/en-GB.mod_ic_event_list.sys.ini
+ [MODULE][PRO] mod_ic_event_list-pro/src/Helper/EventsHelper.php

~ [MODULE] mod_iccalendar/helper.php
~ [MODULE] mod_iccalendar/mod_iccalendar.php
~ [MODULE] mod_iccalendar/mod_iccalendar.xml
~ [MODULE] mod_iccalendar/language/en-GB/en-GB.mod_iccalendar.ini

~ [PLUGIN] plg_actionlog_icagenda/icagenda.php
~ [PLUGIN] plg_actionlog_icagenda/icagenda.xml
~ [PLUGIN] plg_actionlog_icagenda/language/en-GB/en-GB.plg_actionlog_icagenda.ini

+ [PLUGIN][PRO] plg_icagenda-pro/pro.php
+ [PLUGIN][PRO] plg_icagenda-pro/pro.xml
+ [PLUGIN][PRO] plg_icagenda-pro/script.php
+ [PLUGIN][PRO] plg_icagenda-pro/forms/config_pro.xml
+ [PLUGIN][PRO] plg_icagenda-pro/language/en-GB/en-GB.plg_icagenda_pro.ini
+ [PLUGIN][PRO] plg_icagenda-pro/language/en-GB/en-GB.plg_icagenda_pro.sys.ini

+ [PLUGIN][PRO] plg_icagenda_payment_paypal-pro/payment_paypal.php
+ [PLUGIN][PRO] plg_icagenda_payment_paypal-pro/payment_paypal.xml
+ [PLUGIN][PRO] plg_icagenda_payment_paypal-pro/forms/config_payment.xml
+ [PLUGIN][PRO] plg_icagenda_payment_paypal-pro/forms/event_actions.xml
+ [PLUGIN][PRO] plg_icagenda_payment_paypal-pro/language/en-GB/en-GB.plg_icagenda_payment_paypal.ini
+ [PLUGIN][PRO] plg_icagenda_payment_paypal-pro/language/en-GB/en-GB.plg_icagenda_payment_paypal.sys.ini
+ [PLUGIN][PRO] plg_icagenda_payment_paypal-pro/layouts/payment_details.php
+ [PLUGIN][PRO] plg_icagenda_payment_paypal-pro/sql/

+ [PLUGIN][PRO] plg_icagenda_tickets-pro/tickets.php
+ [PLUGIN][PRO] plg_icagenda_tickets-pro/tickets.xml
+ [PLUGIN][PRO] plg_icagenda_tickets-pro/forms/event.xml
+ [PLUGIN][PRO] plg_icagenda_tickets-pro/language/en-GB/en-GB.plg_icagenda_tickets.ini
+ [PLUGIN][PRO] plg_icagenda_tickets-pro/language/en-GB/en-GB.plg_icagenda_tickets.sys.ini
+ [PLUGIN][PRO] plg_icagenda_tickets-pro/layouts/payment.php
+ [PLUGIN][PRO] plg_icagenda_tickets-pro/sql/

~ [PLUGIN] plg_privacy_icagenda/icagenda.php
~ [PLUGIN] plg_privacy_icagenda/icagenda.xml
~ [PLUGIN] plg_privacy_icagenda/language/en-GB/en-GB.plg_privacy_icagenda.ini

~ [PLUGIN] plg_search_icagenda/icagenda.php
~ [PLUGIN] plg_search_icagenda/icagenda.xml

~ [PLUGIN] plg_system_ic_autologin/ic_autologin.php
~ [PLUGIN] plg_system_ic_autologin/ic_autologin.xml

~ [PLUGIN] plg_system_ic_library/ic_library.php
~ [PLUGIN] plg_system_ic_library/ic_library.xml


iCagenda 3.8.0-rc2.1 [J3 patch] <small style="font-weight:normal;">(2022.02.16)</small>
================================================================================
! See 3.8.0-rc2 Releases Notes for more details on this release.
# [MEDIUM][J3] Fixed : Storing of global config and event (issue with Access option on J3).

* Changed files in 3.8.0-rc2.1
~ com_icagenda/admin/config.xml
~ com_icagenda/admin/models/forms/event.xml
~ [FILE] file_icagenda-pro/site/models/forms/event.xml


iCagenda 3.8.0-rc2 <small style="font-weight:normal;">(2022.02.15)</small>
================================================================================
! Announcement : Joomla 4.1 Ready.
! Announcement : Joomla 3.10 as minimum version required. PHP 8.0+ recommended (minimum 7.2).
~ Changed : Clear all options xml files.
~ Changed : Update & clean addtocal icons.
~ Changed : Default registration access level set to Registered.
~ Changed : Set ic-map-wrapper class for all map services; Clean and improve map display.
~ Changed : Simplify event_edit view (Remove icagendaForm::panelsTags).
~ [THEME] Changed : Improve H tag for event titles in list view.
# [MEDIUM] Fixed : menu items xml, not possible to create new menu items.
# [LOW] Fixed : Registration Deadline time option data loading and storing.
# [LOW] Fixed : Custom fields database storing.
# [LOW] Fixed : Description fieldset in Submit an Event form.
# [LOW][PHP8] Fixed : php warning for thumbs on fresh install.
# [LOW][J4] Fixed : Custom field of type radio buttons display.
# [LOW][J3] Fixed : Frontend search filters styling (input height issue).

* Changed files in 3.8.0-rc2
~ pkg_icagenda.xml
~ com_icagenda/script.com_icagenda.php
~ com_icagenda/admin/config.xml
~ com_icagenda/admin/language/en-GB/en-GB.com_icagenda.sys.ini
~ com_icagenda/admin/models/event.php
~ com_icagenda/admin/models/fields/modal/thumbs.php
~ com_icagenda/admin/models/forms/customfield.xml
~ com_icagenda/admin/models/forms/event.xml
~ com_icagenda/admin/sql/install/mysql/icagenda.install.sql
+ com_icagenda/admin/sql/updates/3.8.0-rc2.sql
~ com_icagenda/admin/src/Field/CustomfieldGroupsField.php
~ com_icagenda/admin/src/Model/EventModel.php
~ com_icagenda/admin/src/Utilities/Customfields/Customfields.php
~ com_icagenda/admin/src/Utilities/Form/Form.php
~ com_icagenda/admin/src/Utilities/Icons/Icons.php
~ com_icagenda/admin/src/Utilities/Maps/Maps.php
~ com_icagenda/admin/src/Utilities/Maps/Google/Search.php
~ com_icagenda/admin/src/Utilities/Registration/Registration.php
~ com_icagenda/admin/tmpl/event/edit.php
~ com_icagenda/admin/tmpl/icagenda/default.php
~ com_icagenda/admin/utilities/form/form.php
~ com_icagenda/admin/utilities/form/field/customfieldgroups.php
~ com_icagenda/admin/utilities/form/field/deadlinetimefield.php
~ com_icagenda/admin/utilities/form/field/separator.php
~ com_icagenda/admin/utilities/icons/icons.php
~ com_icagenda/admin/utilities/maps/maps.php
~ com_icagenda/admin/utilities/maps/google/search.php
~ com_icagenda/admin/utilities/registration/registration.php
~ com_icagenda/admin/views/event/tmpl/edit.php
~ com_icagenda/admin/views/icagenda/tmpl/default.php
~ [MEDIA] com_icagenda/media/css/icagenda-front.css
~ [MEDIA] com_icagenda/media/css/icagenda.css
+ [MEDIA] com_icagenda/media/images/icagenda-light.png
+ [MEDIA] com_icagenda/media/images/icagenda.png
+ [MEDIA][FOLDER] com_icagenda/media/images/addtocal/
- [MEDIA][FOLDER] com_icagenda/media/images/cal/
- [MEDIA][FOLDER] com_icagenda/media/images/svgs/
~ com_icagenda/site/add/elements/iclist_vars.php
~ com_icagenda/site/models/forms/submit.xml
~ com_icagenda/site/src/Assets/ListShortcuts.php
~ [THEME] com_icagenda/site/themes/packs/default/default_event.php
~ [THEME] com_icagenda/site/themes/packs/default/default_events.php
~ [THEME] com_icagenda/site/themes/packs/default/css/default_component.css
~ [THEME] com_icagenda/site/themes/packs/ic_rounded/ic_rounded_event.php
~ [THEME] com_icagenda/site/themes/packs/ic_rounded/ic_rounded_events.php
~ [THEME] com_icagenda/site/themes/packs/ic_rounded/css/ic_rounded_component.css
~ com_icagenda/site/tmpl/events/default.xml
~ com_icagenda/site/tmpl/events/default_filters.php
~ com_icagenda/site/tmpl/submit/default.php
~ com_icagenda/site/tmpl/submit/default.xml
~ com_icagenda/site/views/list/tmpl/default.xml
~ com_icagenda/site/views/list/tmpl/default_filters.php
~ com_icagenda/site/views/submit/tmpl/default.php
~ com_icagenda/site/views/submit/tmpl/default.xml
~ [FILE] file_icagenda-pro/site/models/forms/event.xml
~ [FILE] file_icagenda-pro/site/tmpl/manager/event_edit.php
~ [FILE] file_icagenda-pro/site/views/manager/tmpl/event_edit.php
~ [LIBRARY] lib_ic_library/Form/Field/SortableFieldsField.php
~ [LIBRARY] lib_ic_library/lib/form/field/sortablefields.php
~ [MODULE][PRO] mod_ic_event_list-pro/mod_ic_event_list.xml
~ [MODULE] mod_iccalendar/mod_iccalendar.xml
~ [PLUGIN][PRO] plg_icagenda-pro/forms/config_pro.xml


iCagenda 3.8.0-rc1 <small style="font-weight:normal;">(2022.02.05)</small>
================================================================================
! Announcement : Joomla 3.10 as minimum version required. PHP 8.0+ recommended (minimum 7.2).
+ Added : New svg icons for Calendars in Add to Cal.
~ Changed : Config xml files Display Review (component, menus, modules).
~ Changed : Update Outlook Live Calendar link.
~ Changed : Update Add to Cal button - Use svg icons for calendars.
~ Changed : 'datesDisplay' field, update value of option 'No'.
~ Changed : Improve Search Filters options display.
~ Changed : Set search current list as default (fresh install).
~ Changed : Include 3.7.21 changes.
- Removed : Form validation method option (front & server as default).
- Removed : Map width and height options in menu options (use custom css on 'ic-map-wrapper' class)
# [LOW] Fixed : Menu link control functions (retrieve only site "list of events" links).
# [LOW] Fixed : Removal not used All Categories option in multiple categories select list.
# [LOW] Fixed : Option one registration per date not working as expected (issue wrong button on event details view).
# [LOW] Fixed : Multiple categories select form field.
# [LOW][MODULE] Fixed : 'Close'/'No ticket left' status in module iC Calendar.
# [LOW][J4] Fixed : Link to newsletter from menu icon.
# [LOW][J4] Fixed : Error if short_open_tag disabled on server.
# [LOW][J4] Fixed : Cancel button issue in admin Newsletter (send mail to participants) on php8.
# [LOW][J4] Fixed : Slide effect not working for Participants list.
# [LOW][J4] Fixed : ArrayHelper error in categories frontend search filter.
# [LOW][MODULE][J4] Fixed : Model loading for module iC Event List (new helper on J4).
# [LOW][J3] Fixed : Publishing state change in admin lists.

* Changed files in 3.8.0-rc1
~ pkg_icagenda.xml
~ com_icagenda/icagenda.xml
~ com_icagenda/script.com_icagenda.php
~ com_icagenda/admin/CHANGELOG.php
~ com_icagenda/admin/config.xml
~ com_icagenda/admin/icagenda.php
~ com_icagenda/admin/language/en-GB/en-GB.com_icagenda.ini
~ com_icagenda/admin/language/en-GB/en-GB.com_icagenda.sys.ini
~ com_icagenda/admin/models/fields/modal/menulink.php
~ com_icagenda/admin/models/fields/modal/multicat.php
~ com_icagenda/admin/src/Controller/MailController.php
~ com_icagenda/admin/src/Utilities/AddToCal/AddToCal.php
~ com_icagenda/admin/src/Utilities/Events/EventsData.php
~ com_icagenda/admin/src/Utilities/Icons/Icons.php
~ com_icagenda/admin/src/Utilities/Maps/Maps.php
~ com_icagenda/admin/src/Utilities/Menus/Menus.php
~ com_icagenda/admin/src/Utilities/Registration/Participants.php
~ com_icagenda/admin/src/Utilities/Registration/Registration.php
~ com_icagenda/admin/tables/category.php
~ com_icagenda/admin/tables/customfield.php
~ com_icagenda/admin/tables/event.php
~ com_icagenda/admin/tables/feature.php
~ com_icagenda/admin/tables/registration.php
~ com_icagenda/admin/tmpl/categories/default.php
~ com_icagenda/admin/tmpl/customfields/default.php
~ com_icagenda/admin/tmpl/events/default.php
~ com_icagenda/admin/tmpl/features/default.php
~ com_icagenda/admin/tmpl/registrations/default.php
~ com_icagenda/admin/utilities/addtocal/addtocal.php
~ com_icagenda/admin/utilities/event/event.php
~ com_icagenda/admin/utilities/events/data.php
~ com_icagenda/admin/utilities/form/field/multiplecategory.php
~ com_icagenda/admin/utilities/form/field/separator.php
~ com_icagenda/admin/utilities/icons/icons.php
~ com_icagenda/admin/utilities/maps/maps.php
~ com_icagenda/admin/utilities/menus/menus.php
~ com_icagenda/admin/utilities/registration/registration.php
~ [MEDIA] com_icagenda/media/css/icagenda-front.css
~ [MEDIA] com_icagenda/media/css/icagenda.css
~ [MEDIA] com_icagenda/media/icicons/style.css
+ [MEDIA] com_icagenda/media/images/svgs/png/apple-calendar-mobile.png
+ [MEDIA] com_icagenda/media/images/svgs/png/apple-calendar.png
+ [MEDIA] com_icagenda/media/images/svgs/png/google-calendar.png
+ [MEDIA] com_icagenda/media/images/svgs/png/outlook-calendar.png
+ [MEDIA] com_icagenda/media/images/svgs/png/outlook.png
+ [MEDIA] com_icagenda/media/images/svgs/png/yahoo-calendar.png
+ [MEDIA] com_icagenda/media/images/svgs/svg/apple-calendar-mobile.svg
+ [MEDIA] com_icagenda/media/images/svgs/svg/apple-calendar.svg
+ [MEDIA] com_icagenda/media/images/svgs/svg/google-calendar.svg
+ [MEDIA] com_icagenda/media/images/svgs/svg/outlook-calendar.svg
+ [MEDIA] com_icagenda/media/images/svgs/svg/outlook.svg
+ [MEDIA] com_icagenda/media/images/svgs/svg/yahoo-calendar.svg
~ com_icagenda/site/language/en-GB/en-GB.com_icagenda.ini
~ com_icagenda/site/models/fields/categories.php
~ com_icagenda/site/tmpl/submit/default.xml
~ com_icagenda/site/views/submit/tmpl/default.xml
~ [LIBRARY] lib_ic_library/lib_ic_library.xml
~ [LIBRARY] lib_ic_library/lib/form/field/sortablefields.php
~ [MODULE][PRO] mod_ic_event_list-pro/helper.php
~ [MODULE][PRO] mod_ic_event_list-pro/mod_ic_event_list.php
~ [MODULE][PRO] mod_ic_event_list-pro/mod_ic_event_list.xml
+ [MODULE][PRO] mod_ic_event_list-pro/src/Helper/EventsHelper.php
~ [MODULE] mod_iccalendar/helper.php
~ [MODULE] mod_iccalendar/mod_iccalendar.xml
~ [MODULE] mod_iccalendar/language/en-GB/en-GB.mod_iccalendar.ini
~ [PLUGIN][PRO] plg_icagenda-pro/pro.xml
~ [PLUGIN][PRO] plg_icagenda-pro/forms/config_pro.xml
~ [PLUGIN] plg_system_ic_library/ic_library.php
~ [PLUGIN] plg_system_ic_library/ic_library.xml


iCagenda 3.7.21 <small style="font-weight:normal;">(2022.02.04)</small>
================================================================================
# [LOW] Fixed : Event URL generated from modules when on home page were using home page as parent instead of expected List of Events menu item link.
# [LOW] Fixed : redo patch for ics file URL (iCal, Outlook) if no menu item of type list of events is published.

* Changed files in 3.7.21
~ com_icagenda/admin/utilities/event/event.php
~ com_icagenda/site/views/event/tmpl/default_top.php


iCagenda 3.7.20 <small style="font-weight:normal;">(2022.02.01)</small>
================================================================================
+ Added : Tab State in admin edit views (to remember tab with a session).
~ Changed : URL routing for event from modules.
# [LOW] Fixed : ics file URL (iCal, Outlook) if no menu item of type list of events is published.

* Changed files in 3.7.20
~ com_icagenda/script.com_icagenda.php
~ com_icagenda/admin/config.xml
~ com_icagenda/admin/icagenda.php
~ com_icagenda/admin/utilities/event/event.php
~ [MODULE] mod_ic_event_list-pro/mod_ic_event_list.php
~ [MODULE] mod_iccalendar/helper.php


iCagenda 3.8.0-beta2 <small style="font-weight:normal;">(2022.01.11)</small>
================================================================================
! Announcement : Joomla 3.10 as minimum version required. PHP 8.0+ recommended (minimum 7.2).
+ Added[J4] : missing backslash (Exception, stdClass).
+ Added[J4] : emptystate layouts.
~ Changed : Improve Deadline Fields.
~ Changed : iCicons v2
~ Changed : Update iCagenda admin Menu Items.
~ Changed[J4] : Improve event manager control.
~ Changed[J4] : Improve payment details in admin registrations list.
# [LOW] Fixed : OpenStreetMap initialization on loading event edit (admin).
# [LOW][J4] Fixed : Warning deprecated $typeReg PHP8.
# [MEDIUM][J4] Fixed : Remaining deprecated $db->query.
# [MEDIUM][J3] Fixed : load() error on admin event creation.

* Changed files in 3.8.0-beta2
~ script.icagenda.php
~ com_icagenda/icagenda.xml
~ com_icagenda/script.com_icagenda.php
~ com_icagenda/admin/language/en-GB/en-GB.com_icagenda.ini
~ com_icagenda/admin/language/en-GB/en-GB.com_icagenda.sys.ini
~ com_icagenda/admin/models/event.php
~ com_icagenda/admin/models/registrations.php
~ com_icagenda/admin/models/fields/list.php
~ com_icagenda/admin/models/forms/event.xml
~ com_icagenda/admin/src/Model/EventModel.php
~ com_icagenda/admin/src/Model/RegistrationsModel.php
~ com_icagenda/admin/src/Model/ThemesModel.php
~ com_icagenda/admin/src/Utilities/Events/EventsData.php
~ com_icagenda/admin/src/Utilities/Manager/Manager.php
~ com_icagenda/admin/src/Utilities/Maps/Leaflet/Search.php
~ com_icagenda/admin/src/View/Categories/HtmlView.php
~ com_icagenda/admin/src/View/Customfields/HtmlView.php
~ com_icagenda/admin/src/View/Events/HtmlView.php
~ com_icagenda/admin/src/View/Features/HtmlView.php
~ com_icagenda/admin/src/View/Icagenda/HtmlView.php
~ com_icagenda/admin/src/View/Info/HtmlView.php
~ com_icagenda/admin/src/View/Mail/HtmlView.php
~ com_icagenda/admin/src/View/Registrations/HtmlView.php
~ com_icagenda/admin/src/View/Registrations/RawView.php
~ com_icagenda/admin/src/View/Themes/HtmlView.php
+ com_icagenda/admin/tmpl/categories/emptystate.php
+ com_icagenda/admin/tmpl/customfields/emptystate.php
~ com_icagenda/admin/tmpl/events/default.php
+ com_icagenda/admin/tmpl/events/emptystate.php
+ com_icagenda/admin/tmpl/registrations/emptystate.php
~ com_icagenda/admin/utilities/ajax/filter.php
~ com_icagenda/admin/utilities/customfields/customfields.php
~ com_icagenda/admin/utilities/form/form.php
+ com_icagenda/admin/utilities/form/field/deadlinefield.php
~ com_icagenda/admin/utilities/form/field/deadlinetimefield.php
~ com_icagenda/admin/views/events/tmpl/default.php
~ [MEDIA] com_icagenda/media/css/icagenda-front.css
~ [MEDIA] com_icagenda/media/css/icagenda.css
~ [MEDIA] com_icagenda/media/icicons/style.css
~ [MEDIA] com_icagenda/media/icicons/fonts/iCicons.eot
~ [MEDIA] com_icagenda/media/icicons/fonts/iCicons.svg
~ [MEDIA] com_icagenda/media/icicons/fonts/iCicons.ttf
~ [MEDIA] com_icagenda/media/icicons/fonts/iCicons.woff
- [MEDIA] com_icagenda/media/icicons/ie7/ie7.css
- [MEDIA] com_icagenda/media/icicons/ie7/ie7.js
~ com_icagenda/site/controllers/registration.php
~ com_icagenda/site/controllers/submit.php
~ com_icagenda/site/layouts/icagenda/registration/button/register.php
~ com_icagenda/site/models/event.php
~ com_icagenda/site/models/registration.php
~ com_icagenda/site/models/submit.php
~ com_icagenda/site/models/fields/year.php
~ com_icagenda/site/src/Model/EventModel.php
~ com_icagenda/site/src/View/Event/HtmlView.php
~ com_icagenda/site/src/View/Events/HtmlView.php
~ com_icagenda/site/src/View/Registration/HtmlView.php
~ com_icagenda/site/src/View/Submit/HtmlView.php
~ [FILE] file_icagenda-pro/site/controllers/event.php
~ [FILE] file_icagenda-pro/site/src/Controller/EventController.php
~ [LIBRARY] lib_ic_library/lib/form/field/sortablefields.php
~ [MODULE] mod_iccalendar/helper.php
~ [PLUGIN] plg_icagenda-pro/pro.php
~ [PLUGIN] plg_icagenda_payment_paypal-pro/payment_paypal.php
~ [PLUGIN] plg_icagenda_payment_paypal-pro/layouts/payment_details.php
~ [PLUGIN] plg_system_ic_autologin/ic_autologin.php
~ [PLUGIN] plg_system_ic_library/ic_library.php


iCagenda 3.8.0-beta1 <small style="font-weight:normal;">(2021.12.18)</small>
================================================================================
! Announcement : Joomla 3.10 as minimum version required. PHP 8.0+ recommended (minimum 7.2).
! New plugin iCagenda Pro to load Pro Options and Functionalities.
! New router integration with ID removal option (check new tab 'Integration').
+ Added[J4] : New iCdropdown script (Add to Cal and select another date button dropdown).
~ Changed : Set OpenStreetMap as default on fresh install.
~ Changed : Run stored settings in data control only on iCagenda Update (not required on fresh install).
~ Changed : Improve alias control (we need unique alias for no-ids routing).
~ Changed : Update iCalcreator library to 2.30.10
~ Changed[J4] : Improve Google Maps API form fields rendering in frontend Submit an Event.
- Removed : Deprecated icagendaGooglemaps class (use icagendaMaps).
# [LOW] Fixed : Upload found button in control panel.
# [MEDIUM][J4] Fixed : created and ticket_id data on saving payment.
# [LOW][J4] Fixed : SQL 'desc' error on php8.
# [LOW][J4] Fixed : Missing use statements for namespaces in Render emailTag.
# [LOW][J4] Fixed : loading Maps on DOMContentLoaded (event details view).
# [LOW][J3] Fixed : tipTip options settings (keepAlive issue).

* Changed files in 3.8.0-beta1
~ pkg_icagenda.xml
~ script.icagenda.php
~ com_icagenda/script.com_icagenda.php
~ com_icagenda/admin/config.xml
~ com_icagenda/admin/language/en-GB/en-GB.com_icagenda.ini
~ com_icagenda/admin/models/event.php
~ com_icagenda/admin/models/forms/feature.xml
~ com_icagenda/admin/sql/install/mysql/icagenda.install.sql
+ com_icagenda/admin/sql/updates/3.8.0-beta1.sql
~ com_icagenda/admin/src/Extension/iCagendaComponent.php
~ com_icagenda/admin/src/Model/EventModel.php
~ com_icagenda/admin/src/Model/IcategoryModel.php
~ com_icagenda/admin/src/Table/EventTable.php
~ com_icagenda/admin/src/Utilities/Icons/Icons.php
~ com_icagenda/admin/src/Utilities/Manager/Manager.php
~ com_icagenda/admin/src/Utilities/Maps/Maps.php
~ com_icagenda/admin/src/Utilities/Maps/Google/Search.php
~ com_icagenda/admin/src/Utilities/Maps/Leaflet/Search.php
~ com_icagenda/admin/src/Utilities/Render/Render.php
~ com_icagenda/admin/src/Utilities/Tiptip/Tiptip.php
~ com_icagenda/admin/src/View/Event/HtmlView.php
~ com_icagenda/admin/src/View/Icategory/HtmlView.php
~ com_icagenda/admin/tables/event.php
~ com_icagenda/admin/tmpl/event/edit.php
~ com_icagenda/admin/tmpl/feature/edit.php
+ com_icagenda/admin/utilities/form/field/separator.php
- com_icagenda/admin/utilities/googlemaps/googlemaps.php
~ com_icagenda/admin/utilities/manager/manager.php
~ com_icagenda/admin/utilities/maps/maps.php
~ com_icagenda/admin/utilities/maps/leaflet/search.php
~ com_icagenda/admin/utilities/tiptip/tiptip.php
~ com_icagenda/admin/views/event/tmpl/edit.php
~ com_icagenda/media/css/icagenda-back.css
~ com_icagenda/media/css/icagenda-front.css
~ com_icagenda/media/css/icagenda.css
+ com_icagenda/media/js/iCdropdown.js
+ com_icagenda/media/js/iCdropdown.min.js
~ com_icagenda/site/icagenda.php
~ com_icagenda/site/router.php
~ com_icagenda/site/add/elements/icevent_vars.php
~ com_icagenda/site/controllers/submit.php
~ com_icagenda/site/language/en-GB/en-GB.com_icagenda.ini
~ com_icagenda/site/layouts/icagenda/registration/button/register.php
~ com_icagenda/site/models/event.php
~ com_icagenda/site/models/submit.php
~ com_icagenda/site/models/forms/submit.xml
~ com_icagenda/site/src/Model/EventModel.php
~ com_icagenda/site/src/Service/Router.php
~ com_icagenda/site/tmpl/event/default_vcal.php
~ com_icagenda/site/tmpl/submit/default.php
~ com_icagenda/site/views/event/view.html.php
~ com_icagenda/site/views/event/tmpl/default_vcal.php
~ com_icagenda/site/views/submit/tmpl/default.php
+ file_icagenda-pro/file_icagenda.xml
+ file_icagenda-pro/site/controllers/event.php
+ file_icagenda-pro/site/layouts/icagenda/manager/button/edit.php
+ file_icagenda-pro/site/models/manager.php
+ file_icagenda-pro/site/models/forms/event.xml
+ file_icagenda-pro/site/src/Controller/EventController.php
+ file_icagenda-pro/site/src/Model/ManagerModel.php
+ file_icagenda-pro/site/src/View/Manager/HtmlView.php
+ file_icagenda-pro/site/tmpl/manager/event_edit.php
+ file_icagenda-pro/site/views/manager/view.html.php
+ file_icagenda-pro/site/views/manager/tmpl/event_edit.php
- lib_ic_library/lib/iCalcreator/iCalcreator.php
+ lib_ic_library/Library/Library.php
+ lib_ic_library/Vendor/IcalcreatorLibrary.php
+ lib_ic_library/Vendor/Icalcreator/autoload.php
+ [FOLDER] lib_ic_library/Vendor/Icalcreator/src/
+ [FOLDER] lib_ic_library/Vendor/Icalcreator/src/Traits/
+ [FOLDER] lib_ic_library/Vendor/Icalcreator/src/Util/
+ plg_icagenda-pro/pro.php
+ plg_icagenda-pro/pro.xml
+ plg_icagenda-pro/script.php
+ plg_icagenda-pro/forms/config_pro.xml
+ plg_icagenda-pro/language/en-GB/en-GB.plg_icagenda_pro.ini
+ plg_icagenda-pro/language/en-GB/en-GB.plg_icagenda_pro.sys.ini
~ plg_icagenda_payment_paypal-pro/payment_paypal.php
- plg_icagenda_payment_paypal-pro/forms/config.xml
+ plg_icagenda_payment_paypal-pro/forms/config_payment.xml
- plg_icagenda_payment_paypal-pro/forms/event.xml
+ plg_icagenda_payment_paypal-pro/forms/event_actions.xml


iCagenda 3.8.0-alpha5.1 <small style="font-weight:normal;">(2021.11.25)</small>
================================================================================
! Announcement : Joomla 3.10 as minimum version required. PHP 8.0+ recommended (minimum 7.2).
~ Changed : Remove not used libraries, index.html and code clean in modules.
# [MEDIUM] Fixed : Broken control panel, and category edit views in admin.

* Changed files in 3.8.0-alpha5.1
~ com_icagenda/script.com_icagenda.php
~ [PRO] mod_ic_event_list-pro/mod_ic_event_list.php
~ [PRO] mod_ic_event_list-pro/mod_ic_event_list.xml
~ mod_iccalendar/mod_iccalendar.php
~ mod_iccalendar/mod_iccalendar.xml


iCagenda 3.8.0-alpha5 <small style="font-weight:normal;">(2021.11.22)</small>
================================================================================
! Announcement : Joomla 3.10 as minimum version required. PHP 8.0+ recommended (minimum 7.2).
+ Added : New option Deadline for Registration.
+ Added[J4] : New SEF routing.
~ Changed : Improve Register button layouts.
~ Changed : Improve and fix file attachment uploader.
~ Changed : Improve themes css.
~ Changed : Code improvements and cleaning.
~ Changed[J4] : Improve Manager Toolbar.
~ Changed[J4] : Remove iCtip library from Event view.
~ Changed[J4] : Cancel layout update.
~ Changed[J4] : Improve and fix Google Maps API integration.
~ Changed[J4] : Update context from com_icagenda.list to com_icagenda.events
# [MEDIUM] Fixed : Event duplicate aliases.
# [LOW][J4] Fixed : Fix admin single dates display (layout).
# [LOW][J4] Fixed : Form field people to integrate Paypal Payment.
# [LOW][J4] Fixed : Paypal triggerEvent integration in Registration.
# [LOW][J4] Fixed : Fix missing iCDate on payment abandon.
# [LOW][J4] Fixed : iCalcreator path.
# [LOW][J4] Fixed : Improve and Fix AddtoCal button dropdown.
# [LOW][J4] Fixed : Change cancel function name to cancellation to prevent conflict with Jcore.
# [LOW][J4] Fixed : Missing iCDate on payment abandon.
# [LOW][J4] Fixed : Registration header event URL.
# [LOW][J4] Fixed : admin search filters.
# [LOW][J3] Fixed : Add missing iCtip library.
# [LOW][J3] Fixed : Improve empty data retrieving.

* Changed files in 3.8.0-alpha5
~ script.icagenda.php
~ com_icagenda/script.com_icagenda.php
~ com_icagenda/admin/config.xml
~ com_icagenda/admin/language/en-GB/en-GB.com_icagenda.ini
~ com_icagenda/admin/layouts/icagenda/admin/footer.php
~ com_icagenda/admin/layouts/joomla/form/field/subform/repeatable/section.php
~ com_icagenda/admin/models/event.php
~ com_icagenda/admin/models/fields/modal/icfile.php
~ com_icagenda/admin/models/forms/category.xml
~ com_icagenda/admin/models/forms/event.xml
~ com_icagenda/admin/models/forms/filter_events.xml
~ com_icagenda/admin/sql/install/mysql/icagenda.install.sql
+ com_icagenda/admin/sql/updates/3.7.19.sql
+ com_icagenda/admin/sql/updates/3.8.0-alpha5.sql
~ com_icagenda/admin/src/Model/EventModel.php
~ com_icagenda/admin/src/Table/EventTable.php
~ com_icagenda/admin/src/Utilities/Event/Event.php
~ com_icagenda/admin/src/Utilities/Icons/Icons.php
~ com_icagenda/admin/src/Utilities/Manager/Manager.php
~ com_icagenda/admin/src/Utilities/Maps/Maps.php
~ com_icagenda/admin/src/Utilities/Maps/Google/Search.php
~ com_icagenda/admin/src/Utilities/Registration/Registration.php
+ com_icagenda/admin/src/Utilities/Router/Router.php
~ com_icagenda/admin/tmpl/event/edit.php
~ com_icagenda/admin/tmpl/info/default.php
+ com_icagenda/admin/utilities/form/field/DeadlineTimeField.php
~ com_icagenda/admin/utilities/form/field/registrationpeople.php
~ com_icagenda/admin/utilities/manager/manager.php
~ com_icagenda/admin/utilities/registration/registration.php
~ com_icagenda/admin/utilities/theme/theme.php
~ com_icagenda/media/css/icagenda-back.css
~ com_icagenda/media/css/icagenda-front.css
~ com_icagenda/media/css/icagenda.css
~ com_icagenda/site/add/elements/icevent_vars.php
~ com_icagenda/site/add/elements/iclist_vars.php
~ com_icagenda/site/icagenda.php
~ com_icagenda/site/language/en-GB/en-GB.com_icagenda.ini
~ com_icagenda/site/layouts/icagenda/manager/button/edit.php
~ com_icagenda/site/layouts/icagenda/registration/button/cancel.php
~ com_icagenda/site/layouts/icagenda/registration/button/info.php
~ com_icagenda/site/layouts/icagenda/registration/button/register.php
~ com_icagenda/site/models/list.php
~ com_icagenda/site/models/submit.php
~ com_icagenda/site/models/forms/event.xml
~ com_icagenda/site/src/Assets/EventShortcuts.php
~ com_icagenda/site/src/Assets/ListShortcuts.php
~ com_icagenda/site/src/Controller/EventController.php
~ com_icagenda/site/src/Controller/RegistrationController.php
~ com_icagenda/site/src/Model/EventsModel.php
~ com_icagenda/site/src/Model/ManagerModel.php
~ com_icagenda/site/src/Model/RegistrationModel.php
~ com_icagenda/site/src/Model/SubmitModel.php
~ com_icagenda/site/src/Service/Router.php
~ com_icagenda/site/src/View/Event/HtmlView.php
~ com_icagenda/site/src/View/Events/HtmlView.php
~ com_icagenda/site/themes/packs/default/css/default_component.css
~ com_icagenda/site/themes/packs/ic_rounded/css/ic_rounded_component.css
~ com_icagenda/site/tmpl/events/default_categories.php
~ com_icagenda/site/tmpl/event/default_vcal.php
~ com_icagenda/site/tmpl/manager/event_edit.php
~ com_icagenda/site/tmpl/registration/actions.php
~ com_icagenda/site/tmpl/registration/cancel.php
~ com_icagenda/site/views/manager/tmpl/event_edit.php
~ com_icagenda/site/views/registration/tmpl/cancel.php
~ com_icagenda/site/views/registration/tmpl/default.php
~ com_icagenda/site/views/submit/tmpl/default.php
~ plg_icagenda_payment_paypal-pro/forms/event.xml
~ plg_icagenda_payment_paypal-pro/payment_paypal.php
~ plg_icagenda_tickets-pro/forms/event.xml
~ plg_icagenda_tickets-pro/tickets.php


iCagenda 3.7.19 <small style="font-weight:normal;">(2021.11.01)</small>
================================================================================
~ Changed : Set chosen library only for icagenda form field select.
# [LOW] Fixed : error 0 Undefined Constant "id" on php8 after submitting event in frontend.

* Changed files in 3.7.19
~ com_icagenda/site/models/submit.php
~ com_icagenda/site/views/registration/tmpl/cancel.php
~ com_icagenda/site/views/registration/tmpl/default.php


iCagenda 3.8.0-alpha4.1 <small style="font-weight:normal;">(2021.10.13)</small>
================================================================================
! Announcement : Joomla 3.10 as minimum version required. PHP 8.0+ recommended (minimum 7.2).
1 See 3.8.0-alpha4 Release Notes for more information on alpha4 version changes.
~ Changed : Update deprecated Factory getSession.
# [MEDIUM] Fixed : Fix sql install/update.
# [MEDIUM][J4] Fixed : Missing default value for modified Admin Event, Customfield, Registration Tables.
# [LOW][J4] Fixed : Missing Itemid in routing Site Registration.
# [LOW][J4] Fixed : Missing default value for params Site Registration.
# [LOW][J4] Fixed : Missing default value for modified Site Registration.
# [LOW][J4] Fixed : Missing default value for period and dates Site Submit Event.
# [LOW][J4] Fixed : Remove not defined email confirm language string registration form frontend.
# [LOW][J3] Fixed : nodeValue error in admin themes.
# [MODULE][LOW][J4] Fixed : Link to menu option not showing menu items.

* Changed files in 3.8.0-alpha4.1
~ com_icagenda/admin/models/fields/modal/menulink.php
~ com_icagenda/admin/sql/install/mysql/icagenda.install.sql
+ com_icagenda/admin/sql/updates/3.8.0-alpha4.1.sql
~ com_icagenda/admin/src/Table/CustomfieldTable.php
~ com_icagenda/admin/src/Table/EventTable.php
~ com_icagenda/admin/src/Table/RegistrationTable.php
~ com_icagenda/admin/views/themes/tmpl/default.php
~ com_icagenda/site/models/forms/registration.xml
~ com_icagenda/site/src/Assets/EventShortcuts.php
~ com_icagenda/site/src/Controller/RegistrationController.php
~ com_icagenda/site/src/Model/EventModel.php
~ com_icagenda/site/src/Model/RegistrationModel.php
~ com_icagenda/site/src/Model/SubmitModel.php
~ com_icagenda/site/src/View/Registration/HtmlView.php
~ com_icagenda/site/tmpl/registration/default.php


iCagenda 3.8.0-alpha4 <small style="font-weight:normal;">(2021.10.11)</small>
================================================================================
! Announcement : Joomla 3.10 as minimum version required. PHP 8.0+ recommended (minimum 7.2).
1 Install/Update on Joomla 3 / Joomla 4
1 
! DO NOT INSTALL THIS VERSION ON A PRODUCTION SITE! For testing purpose only.
1 Don't hesitate to report any bug, error or issue encountered with this version on the pro ticket support as well as what you have find nice! ;-)

* Changed files in 3.8.0-alpha4
+ script.icagenda.php
~ com_icagenda/icagenda.xml
+ com_icagenda/script.com_icagenda.php
- com_icagenda/script.icagenda.php
~ com_icagenda/admin/config.xml
~ com_icagenda/admin/icagenda.php
~ com_icagenda/admin/assets/elements/desc.php
~ com_icagenda/admin/assets/elements/titleimg.php
~ com_icagenda/admin/helpers/icagenda.php
~ com_icagenda/admin/helpers/html/events.php
+ com_icagenda/admin/layouts/icagenda/admin/footer.php
+ com_icagenda/admin/layouts/icagenda/admin/logo.php
+ com_icagenda/admin/layouts/icagenda/admin/theme_pack_item.php
+ com_icagenda/admin/layouts/icagenda/updater/liveupdate.php
~ com_icagenda/admin/layouts/joomla/form/field/subform/repeatable/section.php
- com_icagenda/admin/liveupdate
~ com_icagenda/admin/models/customfield.php
~ com_icagenda/admin/models/event.php
~ com_icagenda/admin/models/icagenda.php
~ com_icagenda/admin/models/info.php
~ com_icagenda/admin/models/fields/iclist/globalization.php
~ com_icagenda/admin/models/fields/icmap/city.php
~ com_icagenda/admin/models/fields/modal/cat.php
~ com_icagenda/admin/models/fields/modal/evt.php
~ com_icagenda/admin/models/fields/modal/evt_date.php
~ com_icagenda/admin/models/fields/modal/ic_editor.php
~ com_icagenda/admin/models/fields/modal/icfile.php
~ com_icagenda/admin/models/fields/modal/multicat.php
- com_icagenda/admin/models/fields/modal/ph_regbt.php
~ com_icagenda/admin/models/fields/modal/thumbs.php
~ com_icagenda/admin/models/forms/category.xml
~ com_icagenda/admin/models/forms/customfield.xml
~ com_icagenda/admin/models/forms/event.xml
~ com_icagenda/admin/models/forms/feature.xml
+ com_icagenda/admin/models/forms/filter_categories.xml
+ com_icagenda/admin/models/forms/filter_customfields.xml
+ com_icagenda/admin/models/forms/filter_events.xml
+ com_icagenda/admin/models/forms/filter_features.xml
+ com_icagenda/admin/models/forms/filter_registrations.xml
~ com_icagenda/admin/models/forms/mail.xml
~ com_icagenda/admin/models/forms/registration.xml
+ com_icagenda/admin/services/provider.php
~ com_icagenda/admin/sql/install/mysql/icagenda.install.sql
+ com_icagenda/admin/sql/updates/3.7.18.sql
+ com_icagenda/admin/sql/updates/3.8.0-alpha4.sql
+ com_icagenda/admin/src/Controller/CategoriesController.php
+ com_icagenda/admin/src/Controller/CustomfieldController.php
+ com_icagenda/admin/src/Controller/CustomfieldsController.php
+ com_icagenda/admin/src/Controller/DisplayController.php
+ com_icagenda/admin/src/Controller/EventController.php
+ com_icagenda/admin/src/Controller/EventsController.php
+ com_icagenda/admin/src/Controller/FeatureController.php
+ com_icagenda/admin/src/Controller/FeaturesController.php
+ com_icagenda/admin/src/Controller/IcategoryController.php
+ com_icagenda/admin/src/Controller/MailController.php
+ com_icagenda/admin/src/Controller/RegistrationController.php
+ com_icagenda/admin/src/Controller/RegistrationsController.php
+ com_icagenda/admin/src/Controller/ThemesController.php
+ com_icagenda/admin/src/Extension/iCagendaComponent.php
+ com_icagenda/admin/src/Field/CustomfieldGroupsField.php
+ com_icagenda/admin/src/Field/MultipleCategoryField.php
+ com_icagenda/admin/src/Helper/iCagendaHelper.php
+ com_icagenda/admin/src/Model/CategoriesModel.php
+ com_icagenda/admin/src/Model/CustomfieldModel.php
+ com_icagenda/admin/src/Model/CustomfieldsModel.php
+ com_icagenda/admin/src/Model/DownloadModel.php
+ com_icagenda/admin/src/Model/EventModel.php
+ com_icagenda/admin/src/Model/EventsModel.php
+ com_icagenda/admin/src/Model/FeatureModel.php
+ com_icagenda/admin/src/Model/FeaturesModel.php
+ com_icagenda/admin/src/Model/IcagendaModel.php
+ com_icagenda/admin/src/Model/IcategoryModel.php
+ com_icagenda/admin/src/Model/InfoModel.php
+ com_icagenda/admin/src/Model/MailModel.php
+ com_icagenda/admin/src/Model/RegistrationModel.php
+ com_icagenda/admin/src/Model/RegistrationsModel.php
+ com_icagenda/admin/src/Model/ThemesModel.php
+ com_icagenda/admin/src/Service/HTML/Themes.php
+ com_icagenda/admin/src/Table/CustomfieldTable.php
+ com_icagenda/admin/src/Table/EventTable.php
+ com_icagenda/admin/src/Table/FeatureTable.php
+ com_icagenda/admin/src/Table/IcategoryTable.php
+ com_icagenda/admin/src/Table/RegistrationTable.php
+ com_icagenda/admin/src/Utilities/AddThis/AddThis.php
+ com_icagenda/admin/src/Utilities/AddToCal/AddToCal.php
+ com_icagenda/admin/src/Utilities/Ajax/Ajax.php
+ com_icagenda/admin/src/Utilities/Ajax/Filter.php
+ com_icagenda/admin/src/Utilities/Categories/Categories.php
+ com_icagenda/admin/src/Utilities/Customfields/Customfields.php
+ com_icagenda/admin/src/Utilities/Event/Event.php
+ com_icagenda/admin/src/Utilities/Event/EventData.php
+ com_icagenda/admin/src/Utilities/Events/Events.php
+ com_icagenda/admin/src/Utilities/Events/EventsData.php
+ com_icagenda/admin/src/Utilities/Events/EventsList.php
+ com_icagenda/admin/src/Utilities/Form/Form.php
+ com_icagenda/admin/src/Utilities/Icons/Icons.php
+ com_icagenda/admin/src/Utilities/Info/Info.php
+ com_icagenda/admin/src/Utilities/Manager/Manager.php
+ com_icagenda/admin/src/Utilities/Maps/Maps.php
+ com_icagenda/admin/src/Utilities/Maps/Google/Google.php
+ com_icagenda/admin/src/Utilities/Maps/Google/Search.php
+ com_icagenda/admin/src/Utilities/Maps/Leaflet/Leaflet.php
+ com_icagenda/admin/src/Utilities/Maps/Leaflet/Search.php
+ com_icagenda/admin/src/Utilities/Menus/Menus.php
+ com_icagenda/admin/src/Utilities/Registration/Participants.php
+ com_icagenda/admin/src/Utilities/Registration/Registration.php
+ com_icagenda/admin/src/Utilities/Render/Render.php
+ com_icagenda/admin/src/Utilities/Theme/Style.php
+ com_icagenda/admin/src/Utilities/Theme/Theme.php
+ com_icagenda/admin/src/Utilities/Thumb/Thumb.php
+ com_icagenda/admin/src/Utilities/Tiptip/Tiptip.php
+ com_icagenda/admin/src/Utilities/Update/icagendaUpdate.php
+ com_icagenda/admin/src/Utilities/Utilities/Utilities.php
+ com_icagenda/admin/src/View/Categories/HtmlView.php
+ com_icagenda/admin/src/View/Customfield/HtmlView.php
+ com_icagenda/admin/src/View/Customfields/HtmlView.php
+ com_icagenda/admin/src/View/Download/HtmlView.php
+ com_icagenda/admin/src/View/Event/HtmlView.php
+ com_icagenda/admin/src/View/Events/HtmlView.php
+ com_icagenda/admin/src/View/Feature/HtmlView.php
+ com_icagenda/admin/src/View/Features/HtmlView.php
+ com_icagenda/admin/src/View/Icagenda/HtmlView.php
+ com_icagenda/admin/src/View/Icategory/HtmlView.php
+ com_icagenda/admin/src/View/Info/HtmlView.php
+ com_icagenda/admin/src/View/Mail/HtmlView.php
+ com_icagenda/admin/src/View/Registration/HtmlView.php
+ com_icagenda/admin/src/View/Registrations/HtmlView.php
+ com_icagenda/admin/src/View/Registrations/RawView.php
+ com_icagenda/admin/src/View/Themes/HtmlView.php
+ com_icagenda/admin/tmpl/categories/default.php
+ com_icagenda/admin/tmpl/customfield/edit.php
+ com_icagenda/admin/tmpl/customfields/default.php
+ com_icagenda/admin/tmpl/download/default.php
+ com_icagenda/admin/tmpl/event/edit.php
+ com_icagenda/admin/tmpl/events/default.php
+ com_icagenda/admin/tmpl/feature/edit.php
+ com_icagenda/admin/tmpl/features/default.php
+ com_icagenda/admin/tmpl/features/emptystate.php
+ com_icagenda/admin/tmpl/icagenda/color.php
+ com_icagenda/admin/tmpl/icagenda/default.php
+ com_icagenda/admin/tmpl/icagenda/default_modal_changelog.php
+ com_icagenda/admin/tmpl/icagenda/default_modal_pro.php
+ com_icagenda/admin/tmpl/icategory/edit.php
+ com_icagenda/admin/tmpl/info/default.php
+ com_icagenda/admin/tmpl/mail/edit.php
+ com_icagenda/admin/tmpl/registration/edit.php
+ com_icagenda/admin/tmpl/registrations/default.php
+ com_icagenda/admin/tmpl/themes/default.php
~ com_icagenda/admin/utilities/customfields/customfields.php
~ com_icagenda/admin/utilities/event/event.php
~ com_icagenda/admin/utilities/form/form.php
~ com_icagenda/admin/utilities/form/field/categoryselect.php
~ com_icagenda/admin/utilities/form/field/configtermsdefault.php
~ com_icagenda/admin/utilities/form/field/customfieldgroups.php
~ com_icagenda/admin/utilities/form/field/customform.php
+ com_icagenda/admin/utilities/form/field/FilterCategories.php
+ com_icagenda/admin/utilities/form/field/FilterCustomfieldGroups.php
+ com_icagenda/admin/utilities/form/field/FilterCustomfieldTypes.php
+ com_icagenda/admin/utilities/form/field/FilterDates.php
+ com_icagenda/admin/utilities/form/field/FilterEvents.php
+ com_icagenda/admin/utilities/form/field/multiplecategory.php
~ com_icagenda/admin/utilities/form/field/registrationdates.php
~ com_icagenda/admin/utilities/form/field/registrationpeople.php
~ com_icagenda/admin/utilities/form/field/registrationterms.php
+ com_icagenda/admin/utilities/form/field/SubmitMenuitemidField.php
~ com_icagenda/admin/utilities/form/field/textareacounter.php
~ com_icagenda/admin/utilities/manager/manager.php
~ com_icagenda/admin/utilities/menus/menus.php
~ com_icagenda/admin/utilities/params/params.php
~ com_icagenda/admin/utilities/render/render.php
~ com_icagenda/admin/utilities/theme/theme.php
+ com_icagenda/admin/utilities/update/update.php
~ com_icagenda/admin/views/icagenda/tmpl/color.php
~ com_icagenda/admin/views/icagenda/tmpl/default.php
~ com_icagenda/admin/views/info/tmpl/default.php
~ com_icagenda/media/css/icagenda-back.css
~ com_icagenda/media/css/icagenda-front.css
~ com_icagenda/media/css/icagenda.css
- com_icagenda/media/js/icagenda.js
+ com_icagenda/media/js/icagendaupdatecheck.js
~ com_icagenda/media/js/icform.js
~ com_icagenda/media/leaflet/plugins/search/leaflet-search.css
~ com_icagenda/site/icagenda.php
~ com_icagenda/site/add/elements/icevent_vars.php
~ com_icagenda/site/add/elements/iclist_vars.php
~ com_icagenda/site/layouts/icagenda/registration/button/register.php
~ com_icagenda/site/layouts/joomla/form/field/subform/repeatable.php
~ com_icagenda/site/layouts/joomla/form/field/subform/repeatable/section.php
~ com_icagenda/site/models/events.php
~ com_icagenda/site/models/fields/categories.php
~ com_icagenda/site/models/fields/year.php
~ com_icagenda/site/models/forms/event.xml
~ com_icagenda/site/models/forms/registration.xml
~ com_icagenda/site/models/forms/submit.xml
+ com_icagenda/site/src/Assets/EventShortcuts.php
+ com_icagenda/site/src/Assets/ListShortcuts.php
+ com_icagenda/site/src/Controller/DisplayController.php
+ com_icagenda/site/src/Controller/EventController.php
+ com_icagenda/site/src/Controller/RegistrationController.php
+ com_icagenda/site/src/Controller/SubmitController.php
+ com_icagenda/site/src/Model/EventModel.php
+ com_icagenda/site/src/Model/EventsModel.php
+ com_icagenda/site/src/Model/ManagerModel.php
+ com_icagenda/site/src/Model/RegistrationModel.php
+ com_icagenda/site/src/Model/SubmitModel.php
+ com_icagenda/site/src/Service/Router.php
+ com_icagenda/site/src/View/Event/HtmlView.php
+ com_icagenda/site/src/View/Events/HtmlView.php
+ com_icagenda/site/src/View/Manager/HtmlView.php
+ com_icagenda/site/src/View/Registration/HtmlView.php
+ com_icagenda/site/src/View/Submit/HtmlView.php
~ com_icagenda/site/themes/default.xml
~ com_icagenda/site/themes/ic_rounded.xml
~ com_icagenda/site/themes/packs/default/css/default_component.css
~ com_icagenda/site/themes/packs/ic_rounded/ic_rounded_event.php
~ com_icagenda/site/themes/packs/ic_rounded/ic_rounded_events.php
~ com_icagenda/site/themes/packs/ic_rounded/css/ic_rounded_component.css
+ com_icagenda/site/tmpl/event/default.php
+ com_icagenda/site/tmpl/event/default_top.php
+ com_icagenda/site/tmpl/event/default_vcal.php
+ com_icagenda/site/tmpl/events/default.php
+ com_icagenda/site/tmpl/events/default.xml
+ com_icagenda/site/tmpl/events/default_categories.php
+ com_icagenda/site/tmpl/events/default_filters.php
+ com_icagenda/site/tmpl/manager/event_edit.php
+ com_icagenda/site/tmpl/registration/actions.php
+ com_icagenda/site/tmpl/registration/cancel.php
+ com_icagenda/site/tmpl/registration/complete.php
+ com_icagenda/site/tmpl/registration/default.php
+ com_icagenda/site/tmpl/submit/default.php
+ com_icagenda/site/tmpl/submit/default.xml
+ com_icagenda/site/tmpl/submit/send.php
+ language/en-GB/en-GB.pkg_icagenda.sys.ini
+ language/fr-FR/fr-FR.pkg_icagenda.sys.ini
~ lib_ic_library/lib_ic_library.xml
~ lib_ic_library/LICENSE.txt
~ lib_ic_library/Color/Color.php
~ lib_ic_library/Date/Date.php
~ lib_ic_library/Date/Period.php
~ lib_ic_library/File/File.php
~ lib_ic_library/Filter/Output.php
~ lib_ic_library/Form/Field/SortableFieldsField.php
~ lib_ic_library/Form/Rule/PositiveIntegerRule.php
~ lib_ic_library/Globalize/Convert.php
~ lib_ic_library/Globalize/Globalize.php
~ lib_ic_library/Globalize/culture/cs-CZ.php
~ lib_ic_library/Globalize/culture/de-CH.php
~ lib_ic_library/Globalize/culture/de-DE.php
~ lib_ic_library/Globalize/culture/el-GR.php
~ lib_ic_library/Globalize/culture/en-GB.php
~ lib_ic_library/Globalize/culture/en-US.php
~ lib_ic_library/Globalize/culture/es-ES.php
~ lib_ic_library/Globalize/culture/fa-IR.php
~ lib_ic_library/Globalize/culture/fi-FI.php
~ lib_ic_library/Globalize/culture/fo-FO.php
~ lib_ic_library/Globalize/culture/fr-FR.php
~ lib_ic_library/Globalize/culture/hr-HR.php
~ lib_ic_library/Globalize/culture/hu-HU.php
~ lib_ic_library/Globalize/culture/iso.php
~ lib_ic_library/Globalize/culture/it-IT.php
~ lib_ic_library/Globalize/culture/lv-LV.php
~ lib_ic_library/Globalize/culture/nb-NO.php
~ lib_ic_library/Globalize/culture/nl-NL.php
~ lib_ic_library/Globalize/culture/pl-PL.php
~ lib_ic_library/Globalize/culture/pt-BR.php
~ lib_ic_library/Globalize/culture/pt-PT.php
~ lib_ic_library/Globalize/culture/ru-RU.php
~ lib_ic_library/Globalize/culture/sk-SK.php
~ lib_ic_library/Globalize/culture/sv-SE.php
~ lib_ic_library/Globalize/culture/tr-TR.php
~ lib_ic_library/Globalize/culture/uk-UA.php
~ lib_ic_library/Globalize/culture/zh-CN.php
~ lib_ic_library/Globalize/culture/zh-TW.php
~ lib_ic_library/ -> lib_ic_library/lib/ (J3 lib)
~ lib_ic_library/Render/Render.php
~ lib_ic_library/String/StringHelper.php
~ lib_ic_library/Thumb/Create.php
~ lib_ic_library/Thumb/Get.php
~ lib_ic_library/Thumb/Image.php
~ lib_ic_library/Url/Url.php
~ mod_ic_event_list-pro/helper.php
~ mod_ic_event_list-pro/mod_ic_event_list.php
~ mod_ic_event_list-pro/mod_ic_event_list.xml
~ mod_iccalendar/helper.php
~ mod_iccalendar/mod_iccalendar.php
~ mod_iccalendar/mod_iccalendar.xml
~ plg_actionlog_icagenda/icagenda.php
~ plg_actionlog_icagenda/icagenda.xml
~ plg_icagenda_payment_paypal-pro/payment_paypal.php
~ plg_icagenda_payment_paypal-pro/payment_paypal.xml
~ plg_icagenda_payment_paypal-pro/forms/event.xml
~ plg_icagenda_payment_paypal-pro/layouts/payment_details.php
~ plg_icagenda_payment_paypal-pro/sql/install/mysql/icagendapaymentpaypal.install.sql
+ plg_icagenda_payment_paypal-pro/sql/updates/1.0.0-beta2.sql
~ plg_icagenda_tickets-pro/tickets.php
~ plg_icagenda_tickets-pro/tickets.xml
~ plg_icagenda_tickets-pro/forms/event.xml
~ plg_icagenda_tickets-pro/sql/install/mysql/icagendatickets.install.sql
+ plg_icagenda_tickets-pro/sql/updates/1.0.0-beta2.sql
~ plg_installer_icagenda/icagenda.xml
~ plg_privacy_icagenda/icagenda.php
~ plg_privacy_icagenda/icagenda.xml
~ plg_quickicon_icagendaupdate/icagendaupdate.php
~ plg_quickicon_icagendaupdate/icagendaupdate.xml
~ plg_search_icagenda/icagenda.php
~ plg_search_icagenda/icagenda.xml
~ plg_system_ic_autologin/ic_autologin.xml
~ plg_system_ic_library/ic_library.php
~ plg_system_ic_library/ic_library.xml


iCagenda 3.7.18 <small style="font-weight:normal;">(2021.08.31)</small>
================================================================================
! New Installation Package
1 One install/uninstall package for all extensions (component, library, modules, plugins).
1 Clear information provided to Joomla 3.10 on iCagenda compatibility.
! Removal of LiveUpdate to use Joomla Updater
1 New quickicon plugin integrating Joomla Updater check.
1 New live update checking icon on iCagenda control panel.
1 Remove all LiveUpdate library (Control panel errors on php8).
! PHP 8 compatibility
~ Changed : minor CSS changes in admin control panel.
~ [MODULE] Changed : changes in tip rendering script in module calendar.
# [MODULE][MEDIUM] Fixed : error query on php 8 if no events to be displayed.
# [MODULE][LOW] Fixed : option to display only start date of a period to apply only on full period (with no days of the week selected).
# [LOW] Fixed : registration terms Text option unset after update.
# [LOW] Fixed : admin thumbnail medium size generation (duplicated wrong large thumb using medium size settings).

* Changed files in 3.7.18
+ pkg_icagenda.xml
+ script.icagenda.php
~ com_icagenda/icagenda.xml
- com_icagenda/script.icagenda.php
+ com_icagenda/script.com_icagenda.php
~ com_icagenda/admin/icagenda.php
~ com_icagenda/admin/language/en-GB/en-GB.com_icagenda.ini
+ com_icagenda/admin/layouts/icagenda/updater/liveupdate.php
- com_icagenda/admin/liveupdate/config.php
- com_icagenda/admin/liveupdate/index.html
- com_icagenda/admin/liveupdate/LICENSE.txt
- com_icagenda/admin/liveupdate/liveupdate.php
- com_icagenda/admin/liveupdate/assets/current-32.png
- com_icagenda/admin/liveupdate/assets/fail-24.png
- com_icagenda/admin/liveupdate/assets/liveupdate-48.png
- com_icagenda/admin/liveupdate/assets/liveupdate.css
- com_icagenda/admin/liveupdate/assets/nosupport-32.png
- com_icagenda/admin/liveupdate/assets/ok-24.png
- com_icagenda/admin/liveupdate/assets/update-32.png
- com_icagenda/admin/liveupdate/assets/warn-24.png
- com_icagenda/admin/liveupdate/classes/abstractconfig.php
- com_icagenda/admin/liveupdate/classes/controller.php
- com_icagenda/admin/liveupdate/classes/download.php
- com_icagenda/admin/liveupdate/classes/inihelper.php
- com_icagenda/admin/liveupdate/classes/model.php
- com_icagenda/admin/liveupdate/classes/updatefetch.php
- com_icagenda/admin/liveupdate/classes/view.php
- com_icagenda/admin/liveupdate/classes/xmlslurp.php
- com_icagenda/admin/liveupdate/classes/storage/component.php
- com_icagenda/admin/liveupdate/classes/storage/file.php
- com_icagenda/admin/liveupdate/classes/storage/storage.php
- com_icagenda/admin/liveupdate/classes/tmpl/install.php
- com_icagenda/admin/liveupdate/classes/tmpl/nagscreen.php
- com_icagenda/admin/liveupdate/classes/tmpl/overview.php
- com_icagenda/admin/liveupdate/classes/tmpl/startupdate.php
- com_icagenda/admin/liveupdate/language/bg-BG/bg-BG.liveupdate.ini
- com_icagenda/admin/liveupdate/language/bs-BA/bs-BA.liveupdate.ini
- com_icagenda/admin/liveupdate/language/cs-CZ/cs-CZ.liveupdate.ini
- com_icagenda/admin/liveupdate/language/da-DK/da-DK.liveupdate.ini
- com_icagenda/admin/liveupdate/language/de-DE/de-DE.liveupdate.ini
- com_icagenda/admin/liveupdate/language/el-GR/el-GR.liveupdate.ini
- com_icagenda/admin/liveupdate/language/en-GB/en-GB.liveupdate.ini
- com_icagenda/admin/liveupdate/language/es-ES/es-ES.liveupdate.ini
- com_icagenda/admin/liveupdate/language/et-EE/et-EE.liveupdate.ini
- com_icagenda/admin/liveupdate/language/fa-IR/fa-IR.liveupdate.ini
- com_icagenda/admin/liveupdate/language/fi-FI/fi-FI.liveupdate.ini
- com_icagenda/admin/liveupdate/language/fr-FR/fr-FR.liveupdate.ini
- com_icagenda/admin/liveupdate/language/hu-HU/hu-HU.liveupdate.ini
- com_icagenda/admin/liveupdate/language/it-IT/it-IT.liveupdate.ini
- com_icagenda/admin/liveupdate/language/lt-LT/lt-LT.liveupdate.ini
- com_icagenda/admin/liveupdate/language/nb-NO/nb-NO.liveupdate.ini
- com_icagenda/admin/liveupdate/language/nl-NL/nl-NL.liveupdate.ini
- com_icagenda/admin/liveupdate/language/pl-PL/pl-PL.liveupdate.ini
- com_icagenda/admin/liveupdate/language/pt-BR/pt-BR.liveupdate.ini
- com_icagenda/admin/liveupdate/language/pt-PT/pt-PT.liveupdate.ini
- com_icagenda/admin/liveupdate/language/ru-RU/ru-RU.liveupdate.ini
- com_icagenda/admin/liveupdate/language/sk-SK/sk-SK.liveupdate.ini
- com_icagenda/admin/liveupdate/language/sl-SI/sl-SI.liveupdate.ini
- com_icagenda/admin/liveupdate/language/sv-SE/sv-SE.liveupdate.ini
- com_icagenda/admin/liveupdate/language/tr-TR/tr-TR.liveupdate.ini
- com_icagenda/admin/liveupdate/language/uk-UA/uk-UA.liveupdate.ini
+ com_icagenda/admin/utilities/update/update.php
~ com_icagenda/admin/views/events/tmpl/default.php
~ com_icagenda/admin/views/icagenda/tmpl/default.php
~ [MEDIA] com_icagenda/media/css/icagenda-back.css
+ [MEDIA] com_icagenda/media/js/icagendaupdatecheck.js
~ [MODULE] mod_iccalendar/mod_iccalendar.php
~ [PLUGIN] plg_quickicon_icagendaupdate/icagendaupdate/icagendaupdate.php
~ [PLUGIN] plg_quickicon_icagendaupdate/icagendaupdate/icagendaupdate.xml
~ [PLUGIN] plg_quickicon_icagendaupdate/icagendaupdate/language/en-GB/en-GB.plg_quickicon_icagendaupdate.ini


iCagenda 3.8.0-alpha3 <small style="font-weight:normal;">(2021.06.08)</small>
================================================================================
! Announcement : Joomla 3.7 as minimum version required. PHP 7.2+ recommended (minimum 5.6).
! Payment PayPal plugin
1 Set ticket price and sell event tickets!
! Tickets plugin
1 To integrate add-on plugins with iCagenda registration form (payment, ...)
! Refactoring : Many files and code changes to prepare upcoming functionalities and future evolution.
+ Added: Custom field individual handler for tag in email notification for registration. Use [CUSTOMFIELD slug]
+ Added: New layout for edit button.
~ [THEME] Changed: Deprecate 'ic-event' class for 'ic-list-event'.
- [THEME] Removed: Remove in theme 'ic-event' class.

* Changed files in 3.8.0-alpha3
~ icagenda.xml
~ script.icagenda.php
~ admin/CHANGELOG.php
~ admin/controllers/registrations.raw.php
~ admin/language/en-GB/en-GB.com_icagenda.ini
~ admin/models/event.php
~ admin/models/registrations.php
~ admin/models/fields/modal/icfile.php
~ admin/models/forms/event.xml
~ admin/models/forms/registration.xml
~ admin/sql/install/mysql/icagenda.install.sql
~ admin/utilities/customfields/customfields.php
~ admin/utilities/event/event.php
~ admin/utilities/events/data.php
~ admin/utilities/form/field/registrationpeople.php
~ admin/utilities/manager/manager.php
~ admin/utilities/registration/participants.php
~ admin/utilities/registration/registration.php
~ admin/utilities/render/render.php
~ admin/views/event/tmpl/edit.php
~ admin/views/registration/tmpl/edit.php
~ admin/views/registrations/view.html.php
~ admin/views/registrations/tmpl/default.php
~ [LIBRARY] libraries/ic_library/lib_ic_library.xml
- [LIBRARY] libraries/ic_library/iCalcreator/iCalcreator.class.php
~ [MEDIA] media/css/icagenda-back.css
~ [MEDIA] media/css/icagenda-front.css
~ [MEDIA] media/js/icform.js
~ [MODULE] modules/mod_iccalendar/helper.php
+ [PLUGIN][PRO] plugins/icagenda/payment_paypal/payment_paypal.php
+ [PLUGIN][PRO] plugins/icagenda/payment_paypal/payment_paypal.xml
+ [PLUGIN][PRO] plugins/icagenda/payment_paypal/forms/config.xml
+ [PLUGIN][PRO] plugins/icagenda/payment_paypal/forms/event.xml
+ [PLUGIN][PRO] plugins/icagenda/payment_paypal/language/en-GB/en-GB.plg_icagenda_payment_paypal.ini
+ [PLUGIN][PRO] plugins/icagenda/payment_paypal/language/en-GB/en-GB.plg_icagenda_payment_paypal.sys.ini
+ [PLUGIN][PRO] plugins/icagenda/payment_paypal/layouts/payment_details.php
+ [PLUGIN][PRO] plugins/icagenda/payment_paypal/sql/install/mysql/icagendapaymentpaypal.install.sql
+ [PLUGIN][PRO] plugins/icagenda/payment_paypal/sql/uninstall/mysql/icagendapaymentpaypal.uninstall.sql
+ [PLUGIN][PRO] plugins/icagenda/payment_paypal/sql/updates/1.0.sql
+ [PLUGIN][PRO] plugins/icagenda/tickets/tickets.php
+ [PLUGIN][PRO] plugins/icagenda/tickets/tickets.xml
+ [PLUGIN][PRO] plugins/icagenda/tickets/forms/event.xml
+ [PLUGIN][PRO] plugins/icagenda/tickets/language/en-GB/en-GB.plg_icagenda_tickets.ini
+ [PLUGIN][PRO] plugins/icagenda/tickets/language/en-GB/en-GB.plg_icagenda_tickets.sys.ini
+ [PLUGIN][PRO] plugins/icagenda/tickets/layouts/payment.php
+ [PLUGIN][PRO] plugins/icagenda/tickets/sql/install/mysql/icagendatickets.install.sql
+ [PLUGIN][PRO] plugins/icagenda/tickets/sql/uninstall/mysql/icagendatickets.uninstall.sql
+ [PLUGIN][PRO] plugins/icagenda/tickets/sql/updates/1.0.sql
~ site/add/elements/icevent_vars.php
~ site/controllers/event.php
~ site/controllers/registration.php
- site/helpers/iCicons.class.php
~ site/language/en-GB/en-GB.com_icagenda.ini
+ site/layouts/icagenda/manager/button/edit.php
~ site/models/manager.php
~ site/models/registration.php
~ site/models/submit.php
~ site/models/forms/registration.xml
~ site/models/forms/submit.xml
~ [THEME] site/themes/packs/default/default_events.php
~ [THEME] site/themes/packs/default/css/default_component.css
~ [THEME] site/themes/packs/ic_rounded/ic_rounded_events.php
~ [THEME] site/themes/packs/ic_rounded/css/ic_rounded_component.css
~ site/views/event/tmpl/default_vcal.php
~ site/views/list/tmpl/default.php
~ site/views/manager/view.html.php
~ site/views/manager/tmpl/event_edit.php
~ site/views/registration/view.html.php
~ site/views/registration/tmpl/actions.php
~ site/views/registration/tmpl/complete.php
~ site/views/registration/tmpl/default.php
~ site/views/submit/view.html.php
~ site/views/submit/tmpl/default.php


iCagenda 3.7.17 <small style="font-weight:normal;">(2021.06.06)</small>
================================================================================
# [MEDIUM] Fixed : access to registration form while registration is turned off.
# [LOW] Fixed : set correctly the usergroup of event managers for approve button in frontend (excluding parent user groups).
# [LOW] Fixed : wrong language filtering in admin registration edition for labels of the tabs.

* Changed files in 3.7.17
~ admin/utilities/manager/manager.php
~ admin/views/registration/tmpl/edit.php
~ site/views/registration/view.html.php


iCagenda 3.7.16 <small style="font-weight:normal;">(2021.04.02)</small>
================================================================================
~ Changed : Update page_heading in list view.
~ Changed : load formbehavior chosen library for select fields.
# [LOW] Fixed : Registration until end date for single dates.
# [LOW] Fixed : Autofilling custom fieds overrides core_name and core_email.
# [PLUGIN][LOW] Fixed : Possible conflict with extensions loading actionlogs plugin.

* Changed files in 3.7.16
~ admin/utilities/customfields/customfields.php
~ admin/utilities/registration/registration.php
~ [PLUGIN] plugins/actionlog/icagenda/icagenda.php
~ site/models/registration.php
~ site/views/list/tmpl/default.php
~ site/views/registration/view.html.php
~ site/views/registration/tmpl/default.php


iCagenda 3.7.15 <small style="font-weight:normal;">(2020.08.29)</small>
================================================================================
# [LOW] Fixed : Missing default utf-8 title formatting if no option set.
# [LOW] Fixed : Category filter interaction with module list.
# [LOW] Fixed : Add next date control in admin list and event view.

* Changed files in 3.7.15
~ admin/models/events.php
~ admin/utilities/event/event.php
~ admin/utilities/events/data.php
~ admin/utilities/render/render.php
~ site/add/elements/icevent_vars.php


iCagenda 3.8.0-alpha2 <small style="font-weight:normal;">(2020.04.28)</small>
================================================================================
! Announcement : Joomla 3.7 as minimum version required. PHP 7.2+ recommended (minimum 5.6).
! New Frontend Event Edition: Bouton 'Edit' on event page when user has ACL permissions to edit event.
! Refactoring : Many files and code changes to prepare upcoming functionalities and future evolution.
+ Added :  Frontend event edit to Joomla actionlogs.
~ Changed : Improve registration status checking.
~ Changed : Event top layout: migrate icons library utility.
# [LOW] Fixed : Form field notes in information fieldset.
# [LOW] Fixed : Leaflet search input width.
# [LOW] Fixed : Wrong option called for Google embed api key.
# [LOW] Fixed : Registration state should stay published after cancellation.
# [LOW] Fixed : Status default to 1 for first install prior to 2016.
# [LOW] Fixed : Custom registration emails missing.

* Changed files in 3.8.0-alpha2
~ script.icagenda.php
~ admin/access.xml
~ admin/config.xml
~ admin/language/en-GB/en-GB.com_icagenda.ini
~ admin/models/event.php
~ admin/models/registrations.php
~ admin/models/forms/event.xml
~ admin/sql/install/mysql/icagenda.install.sql
~ admin/utilities/customfields/customfields.php
~ admin/utilities/event/data.php
~ admin/utilities/event/event.php
~ admin/utilities/events/data.php
~ admin/utilities/form/form.php
+ admin/utilities/icons/icons.php
~ admin/utilities/manager/manager.php
~ admin/utilities/maps/leaflet/search.php
~ admin/utilities/registration/participants.php
~ admin/utilities/registration/registration.php
~ admin/views/event/tmpl/edit.php
~ admin/views/events/tmpl/default.php
~ admin/views/registrations/view.html.php
~ admin/views/registrations/tmpl/default.php
~ [LIBRARY] libraries/ic_library/date/date.php
+ [LIBRARY] libraries/ic_library/iCalcreator/iCalcreator.php
~ [MEDIA] media/css/icagenda-back.css
~ [MEDIA] media/css/icagenda-front.css
~ [MEDIA] media/css/icagenda.css
~ [MEDIA] media/leaflet/plugins/search/leaflet-search.css
~ [MODULE][PRO] modules/mod_ic_event_list/mod_ic_event_list.php
~ [MODULE][PRO] modules/mod_ic_event_list/tmpl/default.php
~ [MODULE][PRO] modules/mod_ic_event_list/tmpl/icrounded.php
~ [MODULE] modules/mod_iccalendar/helper.php
~ [PLUGIN] plugins/actionlog/icagenda/icagenda.php
~ [PLUGIN] plugins/actionlog/icagenda/icagenda.xml
~ [PLUGIN] plugins/search/icagenda/icagenda.php
~ [PLUGIN] plugins/search/icagenda/icagenda.xml
~ site/controller.php
~ site/add/elements/icevent_vars.php
+ site/controllers/event.php
~ site/language/en-GB/en-GB.com_icagenda.ini
~ site/layouts/icagenda/registration/button/cancel.php
~ site/layouts/icagenda/registration/button/register.php
~ site/models/event.php
+ site/models/manager.php
~ site/models/registration.php
~ site/models/submit.php
+ site/models/forms/event.xml
~ site/models/forms/submit.xml
~ [THEME] site/themes/packs/default/css/default_component.css
~ [THEME] site/themes/packs/ic_rounded/css/ic_rounded_component.css
~ site/views/event/view.html.php
~ site/views/event/tmpl/default_top.php
~ site/views/event/tmpl/default_vcal.php
~ site/views/list/view.feed.php
~ site/views/list/tmpl/default.xml
~ site/views/list/tmpl/default_filters.php
+ site/views/manager/view.html.php
+ site/views/manager/tmpl/event_edit.php
~ site/views/registration/view.html.php
~ site/views/registration/tmpl/cancel.php
~ site/views/registration/tmpl/default.php
~ site/views/submit/tmpl/default.php
~ site/views/submit/tmpl/default.xml


iCagenda 3.7.14 <small style="font-weight:normal;">(2020.04.27)</small> Security & Bug Fix Release
================================================================================
# [SECURITY][LOW] Exploit type: Blind SQLi
1 Severity: Low
1 Versions: 3.6.0 through 3.7.13
1 Description: The lack of type casting of a variable in SQL statement leads to a SQL injection vulnerability in the events list view.
+ Added : Search in custom fields value with the frontend global search field.
# [LOW] Fixed : Missing custom cancellation label in detailled view of event.
# [LOW] Fixed : Showon display of custom label options (should be hidden if default label).
# [LOW] Fixed : Removed not used asset_id.

* Changed files in 3.7.14
~ admin/access.xml
~ admin/models/forms/event.xml
~ admin/sql/install/mysql/icagenda.install.sql
~ admin/utilities/event/event.php
~ admin/utilities/events/data.php
~ site/models/submit.php
~ site/models/forms/submit.xml
~ site/views/list/tmpl/default_filters.php


iCagenda 3.7.13 <small style="font-weight:normal;">(2020.04.02)</small>
================================================================================
+ Added : Custom 'Cancelled' label options (text + style).
# [LOW] Fixed : Module language missing string.
# [LOW] Fixed : Missing redirection on Registration form if closed.
# [LOW][PLUGIN] Fixed : Search routing on multilanguages site.

* Changed files in 3.7.13
~ admin/models/forms/event.xml
~ admin/utilities/event/event.php
~ [PLUGIN] plugins/search/icagenda/icagenda.php
~ [PLUGIN] plugins/search/icagenda/icagenda.xml
~ site/views/registration/view.html.php


iCagenda 3.7.12 <small style="font-weight:normal;">(2020.03.28)</small>
================================================================================
+ Added : Event cancellation feature to inform your visitors of events cancelled.
+ Added : Possibility to create custom language constants (Language Overrides) for translation of custom fields (Label and value).
+ Added : Falang custom fields element content xml files (version 2.0).
# [LOW] Fixed : Participants access view for organizers.
# [LOW] Fixed : JCE image input width.
# [LOW] Fixed : Event Title "h" tag in default theme for event details view.

* Changed files in 3.7.12
~ admin/models/event.php
~ admin/models/registrations.php
~ admin/models/forms/event.xml
~ admin/utilities/event/data.php
~ admin/utilities/event/event.php
~ admin/utilities/registration/participants.php
~ admin/views/event/tmpl/edit.php
~ admin/views/events/tmpl/default.php
~ admin/views/registrations/tmpl/default.php
~ [MEDIA] media/css/icagenda-back.css
~ [MODULE][PRO] modules/mod_ic_event_list/mod_ic_event_list.php
~ [MODULE][PRO] modules/mod_ic_event_list/tmpl/default.php
~ [MODULE][PRO] modules/mod_ic_event_list/tmpl/icrounded.php
~ [MODULE] modules/mod_iccalendar/helper.php
~ site/add/elements/icevent_vars.php
~ site/models/registration.php
~ [THEME] site/themes/packs/default/default_event.php
~ [THEME] site/themes/packs/default/css/default_component.css
~ [THEME] site/themes/packs/ic_rounded/css/ic_rounded_component.css
~ site/views/event/tmpl/default_top.php
~ site/views/registration/view.html.php


iCagenda 3.8.0-alpha1 <small style="font-weight:normal;">(2019.12.30)</small>
================================================================================
! Announcement : Joomla 3.7 as minimum version required. PHP 7.2+ recommended (minimum 5.6).
! New Map Service: Leaflet OpenStreetMap.
! Possibility for user to cancel his registration.
! Register button redesign (integration of cancellation system, HTML moved to a layout...)
! Submit an Event frontend form refactory.
! Refactoring : many files and code changes to prepare upcoming functionalities and future evolution.
+ Added : registration status.
~ Changed : Remove time picker library (use Joomla calendar field). New single dates layout.
~ Changed : Refactory Custom Fields loader (for consistency with submit).
~ Changed : Refactory multiple form fields and move to utilities.
- Removed : deprecated form fields.

* Changed files in 3.8.0-alpha1
~ icagenda.xml
~ script.icagenda.php
~ admin/CHANGELOG.php
~ admin/config.xml
~ admin/controllers/categories.php
~ admin/controllers/category.php
~ admin/controllers/customfield.php
~ admin/controllers/customfields.php
~ admin/controllers/event.php
~ admin/controllers/events.php
~ admin/controllers/feature.php
~ admin/controllers/features.php
~ admin/controllers/icagenda.php
~ admin/controllers/registration.php
~ admin/controllers/registrations.php
~ admin/controllers/registrations.raw.php
~ admin/language/en-GB/en-GB.com_icagenda.ini
+ admin/layouts/joomla/form/field/subform/repeatable/section.php
~ admin/models/event.php
~ admin/models/events.php
~ admin/models/registrations.php
+ admin/models/fields/config/emailtags.php
- admin/models/fields/modal/color.php
- admin/models/fields/modal/date.php
- admin/models/fields/modal/enddate.php
- admin/models/fields/modal/iclink_article.php
- admin/models/fields/modal/iclink_type.php
- admin/models/fields/modal/iclink_url.php
- admin/models/fields/modal/icmulti_checkbox.php
- admin/models/fields/modal/icmulti_opt.php
- admin/models/fields/modal/ictext_content.php
- admin/models/fields/modal/ictext_placeholder.php
- admin/models/fields/modal/ictext_type.php
- admin/models/fields/modal/ictextarea_counter.php
- admin/models/fields/modal/ictxt_article.php
- admin/models/fields/modal/ictxt_content.php
- admin/models/fields/modal/ictxt_default.php
- admin/models/fields/modal/ictxt_type.php
- admin/models/fields/modal/media.php
- admin/models/fields/modal/param_place.php
- admin/models/fields/modal/period.php
- admin/models/fields/modal/startdate.php
- admin/models/fields/modal/tos_article.php
- admin/models/fields/modal/tos_content.php
- admin/models/fields/modal/tos_default.php
- admin/models/fields/modal/tos_type.php
~ admin/models/forms/event.xml
~ admin/models/forms/registration.xml
+ admin/sql/updates/3.8.0-alpha1.sql
+ admin/sql/updates/3.8.sql
~ admin/tables/event.php
~ admin/utilities/customfields/customfields.php
~ admin/utilities/event/event.php
~ admin/utilities/events/data.php
~ admin/utilities/form/form.php
+ admin/utilities/form/field/categoryselect.php
~ admin/utilities/form/field/registrationdates.php
~ admin/utilities/form/field/terms.php
+ admin/utilities/form/field/textareacounter.php
+ admin/utilities/maps/google.php
+ admin/utilities/maps/leaflet.php
+ admin/utilities/maps/maps.php
+ admin/utilities/maps/google/search.php
+ admin/utilities/maps/leaflet/search.php
~ admin/utilities/registration/participants.php
~ admin/utilities/registration/registration.php
~ admin/utilities/render/render.php
+ admin/utilities/tiptip/tiptip.php
~ admin/views/event/view.html.php
~ admin/views/event/tmpl/edit.php
~ admin/views/events/tmpl/default.php
~ admin/views/registration/tmpl/edit.php
~ admin/views/registrations/view.html.php
~ admin/views/registrations/tmpl/default.php
~ [LIBRARY] libraries/ic_library/lib_ic_library.xml
~ [LIBRARY] libraries/ic_library/language/en-GB/en-GB.lib_ic_library.ini
~ [MEDIA] media/css/icagenda-front.css
~ [MEDIA] media/css/icagenda.css
~ [MEDIA] media/icicons/style.css
- [MEDIA] media/js/icdates.js
~ [MEDIA] media/js/icform.js
- [MEDIA] media/js/timepicker.js
+ [MEDIA] media/leaflet/leaflet.css
+ [MEDIA] media/leaflet/leaflet.js
+ [MEDIA] media/leaflet/images/layers-2x.png
+ [MEDIA] media/leaflet/images/layers.png
+ [MEDIA] media/leaflet/images/marker-icon-2x.png
+ [MEDIA] media/leaflet/images/marker-icon.png
+ [MEDIA] media/leaflet/images/marker-shadow.png
+ [MEDIA] media/leaflet/plugins/search/leaflet-search-geocoder.js
+ [MEDIA] media/leaflet/plugins/search/leaflet-search.css
+ [MEDIA] media/leaflet/plugins/search/leaflet-search.js
+ [MEDIA] media/leaflet/plugins/search/leaflet-search.mobile.css
+ [MEDIA] media/leaflet/plugins/search/images/loader.gif
+ [MEDIA] media/leaflet/plugins/search/images/search-icon-mobile.png
+ [MEDIA] media/leaflet/plugins/search/images/search-icon.png
~ [MODULE][PRO] modules/mod_ic_event_list/mod_ic_event_list.php
~ [MODULE][PRO] modules/mod_ic_event_list/mod_ic_event_list.xml
~ [MODULE] modules/mod_iccalendar/helper.php
~ [MODULE] modules/mod_iccalendar/mod_iccalendar.php
~ [MODULE] modules/mod_iccalendar/mod_iccalendar.xml
~ site/icagenda.php
~ site/add/elements/icevent_vars.php
~ site/add/elements/iclist_vars.php
+ site/controllers/submit.php
~ site/language/en-GB/en-GB.com_icagenda.ini
+ site/layouts/icagenda/registration/button/box.php
+ site/layouts/icagenda/registration/button/cancel.php
+ site/layouts/icagenda/registration/button/info.php
+ site/layouts/icagenda/registration/button/register.php
~ site/layouts/joomla/form/field/file.php
+ site/layouts/joomla/form/field/subform/repeatable.php
+ site/layouts/joomla/form/field/subform/repeatable/section.php
~ site/models/event.php
~ site/models/events.php
~ site/models/list.php
~ site/models/registration.php
~ site/models/submit.php
~ site/models/forms/registration.xml
~ site/models/forms/submit.xml
~ [THEME] site/themes/default.xml
~ [THEME] site/themes/ic_rounded.xml
~ [THEME] site/themes/packs/default/default_event.php
~ [THEME] site/themes/packs/default/css/default_component.css
~ [THEME] site/themes/packs/ic_rounded/ic_rounded_event.php
~ [THEME] site/themes/packs/ic_rounded/css/ic_rounded_component.css
~ site/views/event/view.html.php
~ site/views/list/view.html.php
~ site/views/list/tmpl/default.xml
~ site/views/list/tmpl/default_categories.php
~ site/views/registration/view.html.php
~ site/views/registration/tmpl/actions.php
~ site/views/registration/tmpl/cancel.php
~ site/views/registration/tmpl/complete.php
~ site/views/registration/tmpl/default.php
~ site/views/submit/view.html.php
~ site/views/submit/tmpl/default.php
~ site/views/submit/tmpl/default.xml
~ site/views/submit/tmpl/send.php


iCagenda 3.7.11 <small style="font-weight:normal;">(2019.12.19)</small>
================================================================================
~ [THEME] Changed : Remove css max-height for image in event details view
# [LOW] Fixed : missing alt text for images.

* Changed files in 3.7.11
~ [LIBRARY] libraries/ic_library/thumb/get.php
~ [THEME] site/themes/packs/default/css/default_component.css
~ [THEME] site/themes/packs/ic_rounded/css/ic_rounded_component.css


iCagenda 3.7.10 <small style="font-weight:normal;">(2019.08.31)</small>
================================================================================
+ Added : Prefixe for Search in registrations admin list. EVENTID: to search for an event ID.
+ Added : Missing no results message for Search in admin events list.
+ [GLOBALIZATION] Added : zh-CN Chinese (Simplified) date formats.
+ [MODULE] Added : Chinese languages zh-CN and zh-TW as year before month used for date display of the calendar header.
~ Changed : Removed not needed max-width CSS applied on module calendar for ic_rounded theme

* Changed files in 3.7.10
~ admin/models/registrations.php
~ admin/views/events/tmpl/default.php
~ admin/views/icagenda/tmpl/default.php
+ [LIBRARY] libraries/ic_library/globalize/culture/zh-CN.php
~ [MODULE] modules/mod_iccalendar/helper.php
~ site/themes/packs/ic_rounded/css/ic_rounded_module.css


iCagenda 3.7.9 <small style="font-weight:normal;">(2019.06.13)</small>
================================================================================
+ Added : character encoding Windows-1256 for registrations export.
+ [GLOBALIZATION] Added : de-CH German (Switzerland) date formats.
# [LOW] Fixed : admin events list search with non-Latin characters.
# [LOW] Fixed : zero "0" value not saved if used as an option value in dropdown list type custom field.
# [LOW] Fixed : x-small thumbnail crop option not saved if disabled.
# [LOW] Fixed : default published state for registrations export when not firstly filtered.

* Changed files in 3.7.9
~ admin/controllers/registrations.raw.php
~ admin/models/events.php
~ admin/models/fields/modal/thumbs.php
~ admin/models/forms/download.xml
~ admin/utilities/customfields/customfields.php
+ [LIBRARY] libraries/ic_library/globalize/culture/de-CH.php
~ site/models/registration.php
~ site/models/forms/registration.xml


iCagenda 3.7.8 <small style="font-weight:normal;">(2019.01.10)</small>
================================================================================
# [LOW] Fixed : while adding a patch for old Joomla 3 versions, Published state control was broken with Joomla 3.9 in release 3.7.7 of iCagenda.

* Changed files in 3.7.8
~ admin/tables/category.php
~ admin/tables/customfield.php
~ admin/tables/event.php
~ admin/tables/feature.php
~ admin/tables/registration.php


iCagenda 3.7.7 <small style="font-weight:normal;">(2019.01.09)</small>
================================================================================
+ Added : column indexes to improve sql queries performance.
+ Added : search in dates/period (admin).
# [LOW] Fixed : for Joomla version before 3.7: do not set column alias.
# [LOW] Fixed : COM_ICAGENDA_REGISTRATION_REGISTER_BTN string override
# [LOW] Fixed : missing Export button Joomla before 3.7
# [LOW] Fixed : gravatar on mobile display.

* Changed files in 3.7.7
~ admin/models/events.php
~ admin/sql/install/mysql/icagenda.install.sql
~ admin/tables/category.php
~ admin/tables/customfield.php
~ admin/tables/event.php
~ admin/tables/feature.php
~ admin/tables/registration.php
~ admin/views/registrations/view.html.php
~ admin/views/registrations/tmpl/default.php
~ site/icagenda.php
~ site/views/registration/tmpl/default.php


iCagenda 3.7.6 <small style="font-weight:normal;">(2018.11.28)</small>
================================================================================
# [LOW] Patch for Joomla 3.9.1: Due to a change in Published state control in Joomla core, the side-effect is published toggle/change state is broken in iCagenda.
1 This affects too a few parts of Joomla core: com_banners Clients, com_users Notes, com_finder Filters and com_messages. I've created PRs to fix this in next Joomla 3.9.2

* Changed files in 3.7.6
~ admin/tables/category.php
~ admin/tables/customfield.php
~ admin/tables/event.php
~ admin/tables/feature.php
~ admin/tables/registration.php


iCagenda 3.7.5 <small style="font-weight:normal;">(2018.10.30)</small>
================================================================================
! Privacy Tools part.2 : integration with Joomla 3.9 Privacy Tools Suite
! Joomla! Integration:
1 - Action logs include user actions in iCagenda extension. (Enable "Action Log - iCagenda" plugin).
1 - iCagenda will include events, registrations and related custom fields to the user data exported on privacy information requests. (Enable "Privacy - iCagenda" plugin).
1 - iCagenda reports its privacy related capabilities to the Joomla Privacy system. 
+ Added : Plugin Privacy - iCagenda.
+ Added : Plugin Action Log - iCagenda.
~ [MODULE][PRO] Changed : Improve module font color when image background.
# [LOW] Fixed : Fix the registrations csv export zip file not unzippable.
# [LIBRARY][LOW] Fixed : Fix de-DE date formats missing dot.
# [THEME][LOW] Fixed : Add missing ic-details-cat class default theme.

* Changed files in 3.7.5
~ script.icagenda.php
~ admin/models/registrations.php
~ admin/utilities/registration/registration.php
~ [LIBRARY] libraries/ic_library/globalize/culture/de-DE.php
~ [MODULE][PRO] modules/mod_ic_event_list/mod_ic_event_list.xml
~ [MODULE][PRO] modules/mod_ic_event_list/tmpl/icrounded.php
+ [PLUGIN][ACTIONLOG] plugins/actionlog/icagenda/icagenda.php
+ [PLUGIN][ACTIONLOG] plugins/actionlog/icagenda/icagenda.xml
+ [PLUGIN][PRIVACY] plugins/privacy/icagenda/icagenda.php
+ [PLUGIN][PRIVACY] plugins/privacy/icagenda/icagenda.xml
~ site/themes/packs/default/default_event.php


iCagenda 3.7.4 <small style="font-weight:normal;">(2018.09.26)</small> Security Release
================================================================================
# [SECURITY][MEDIUM] Exploit type: XSS
1 Severity: Low
1 Versions: 3.6.7 through 3.7.3
1 Description: Inadequate input filtering on the list of events page, when frontend search filters enable. This may lead to reflective XSS via injection of arbitrary parameters and/or values on the current page url.


iCagenda 3.7.3 <small style="font-weight:normal;">(2018.08.23)</small>
================================================================================
~ Changed : improve category color brightness control to set font color for date (https://www.w3.org/TR/AERT/#color-contrast).
~ Changed : a few code clean, minor reviews and deprecated code removal.
~ Changed : improve words breaking on Chrome for event details information.
# [LOW] Fixed : Google Maps embed api script broken for a few languages using quotes in translation strings (French...).
# [LOW] Fixed : Google maps embed iframe url.
# [LOW] Fixed : php warning on email cloaking if Joomla EmailCloaking plugin is disabled.
# [LOW] Fixed : frontend search filters Month and Year not displaying events with period on a single month.
# [LOW] Fixed : missing https ssl url for external calendars and usage stats.

* Changed files in 3.7.3
~ script.icagenda.php
~ admin/assets/jcms/info.php
~ admin/utilities/addtocal/addtocal.php
~ admin/utilities/events/data.php
~ admin/utilities/googlemaps/googlemaps.php
~ admin/utilities/info/info.php
~ admin/utilities/render/render.php
~ admin/views/event/tmpl/edit.php
~ [LIBRARY] libraries/ic_library/color/color.php
~ [MODULE][PRO] modules/mod_ic_event_list/helper.php
~ [MODULE][PRO] modules/mod_ic_event_list/mod_ic_event_list.php
~ site/helpers/iCicons.class.php
~ site/models/event.php
~ site/models/registration.php
~ [THEME] site/themes/packs/default/css/default_component.css
~ [THEME] site/themes/packs/ic_rounded/css/ic_rounded_component.css
~ site/views/list/view.feed.php
~ site/views/submit/view.html.php
~ site/views/submit/tmpl/default.php


iCagenda 3.7.2 <small style="font-weight:normal;">(2018.06.11)</small>
================================================================================
! New Google Maps Platform
1 - Starting June 11th, 2018, Google Maps API no longer support keyless access.
1 - Starting July 16th, 2018, the billing requirement will be enforced.
1 - All projects must be associated with a billing account, or will not be able to make Javascript API requests over $200/mo.
1 - However it is advised to use new option Google Maps Embed API for Maps Service, that remains free with unlimited usage.
+ Added : Google Maps Embed API (New option for Maps Service).
+ Added : Privacy consent information (badge) in admin registrations list.
+ Added : Prefixes for Search in registrations. USERID: to search for a user ID and EMAIL: to search for a user Email address.
~ Changed : Option for Name Consent allows now to require name visibility consent even if List of Participants is disabled.

* Changed files in 3.7.2
~ script.icagenda.php
~ admin/config.xml
~ admin/models/registrations.php
~ admin/utilities/googlemaps/googlemaps.php
~ admin/views/event/tmpl/edit.php
~ admin/views/registrations/tmpl/default.php
~ site/models/registration.php
~ site/views/registration/tmpl/default.php
~ site/views/submit/tmpl/default.php


iCagenda 3.7.1 <small style="font-weight:normal;">(2018.05.28)</small>
================================================================================
+ Added : option to set Gravatar consent on Registration form: 'Auto' (as for 3.7.0, if list of participants is enabled), 'Require Explicite Consent' (even if list of participants hidden), 'Disabled' (not ask for consent, but never use Gravatar to show avatar. Future options will be developed for 'Avatar', to enable upload or other avatar system integration like Community Builder).
# [LOW] Fixed PHP 7.2 : count returns php warning when no registration (if Error Reporting is enabled. Don't forget to keep this Joomla configuration option turn off or on default when site is in production).
# [PLUGIN][SEARCH][LOW] Fixed : global search in private events not returning results even if user logged-in with access permissions.

* Changed files in 3.7.1
~ script.icagenda.php
~ admin/config.xml
~ admin/utilities/registration/registration.php
~ [PLUGIN][SEARCH] plugins/search/icagenda/icagenda.php
~ [PLUGIN][SEARCH] plugins/search/icagenda/icagenda.xml
~ site/models/registration.php
~ site/views/registration/tmpl/default.php


iCagenda 3.7.0 <small style="font-weight:normal;">(2018.05.25)</small>
================================================================================
! Privacy Tools part.1 : GDPR REGULATION (EU) 2016/679.
! New separated database table 'icagenda_user_actions' to store all explicit consents as required by GDPR.
1 - Registration form: name visibility, Gravatar, Organiser, Terms.
1 - Submit an Event form: Terms of Service (tos).
! Fresh New Installation (only!): new default settings
1 - Event view: participants list hidden by default.
1 - Registration form: 'Terms & Conditions' consent checkbox enabled by default.
1 - Registration form: 'Phone' core field disabled by default.
! Update Installation: new options preset to Privacy by default
1 - Event view: participant name and gravatar not public by default (require explicite consent).
1 - Registration form: improved 'Terms & Conditions' consent checkbox.
1 - Registration form: new 'Name Visibility' consent checkbox (enabled by a new config option in 'Registration' tab).
1 - Registration form: new 'Gravatar' consent checkbox (auto-enabled if list of participants is active).
1 - Registration form: new 'Consent to Organiser' consent checkbox (new config option in 'Registration' tab).
! Announcement : First version to support Joomla 3 only (support for J2.5 has ended).
~ Changed UX : integrates showon feature in global options of component (showon was introduced in Joomla 3.2.4).
~ [MEDIA] Changed : iCicons font updated.
# [LOW] Fixed : Google Maps initialize possible conflict with other extensions using maps.
# [MODULE][LOW] Fixed : escape div html tags in calendar script to prevent unexpected manipulation of generated content by a third party extension.

* Changed files in 3.7.0
~ script.icagenda.php
~ admin/config.xml
~ admin/models/registration.php
~ admin/utilities/customfields/customfields.php
+ admin/utilities/form/field/configtermsdefault.php
+ admin/utilities/form/field/terms.php
~ admin/utilities/googlemaps/googlemaps.php
~ admin/utilities/registration/participants.php
~ [MEDIA] media/css/icagenda-front.css
~ [MEDIA] media/icicons/style.css
~ [MEDIA] media/icicons/fonts/iCicons.eot
~ [MEDIA] media/icicons/fonts/iCicons.svg
~ [MEDIA] media/icicons/fonts/iCicons.ttf
~ [MEDIA] media/icicons/fonts/iCicons.woff
~ [MEDIA] media/js/icmap-front.js
~ [MODULE] modules/mod_iccalendar/mod_iccalendar.php
~ site/models/registration.php
~ site/models/submit.php
~ site/models/forms/registration.xml
~ [THEME] site/themes/packs/default/css/default_component.css
~ [THEME] site/themes/packs/ic_rounded/css/ic_rounded_component.css
~ site/views/registration/tmpl/default.php


iCagenda 3.6.14 <small style="font-weight:normal;">(2018.05.04)</small>
================================================================================
! Cleanup : Spring cleaning time ;-)
1 - Replace Joomla Framework commonly used but deprecated functions from iCagenda.
1 - Code Standards review (part 1).
# [HIGH] Fixed : Permissions Access levels for registration form broken in version 3.6.13.
# [LOW] Fixed : syntax error, unexpected T_OBJECT_OPERATOR on PHP 5.3 and below (Please, ask your hoster to upgrade your PHP version! Recommended: Joomla 3 + PHP 7).
# [LOW] Fixed : event's contact email not receiving notification email when new registration.
# [LOW] Fixed : missing integer control for title characters limit.
# [MODULE][LOW] Fixed PHP 7.2 : refactory deprecated php code.
# [MODULE][PRO][LOW] Fixed : PHP Notice in backend log for module template layout icrounded.

* Changed files in 3.6.14
~ script.icagenda.php
~ admin/config.xml
~ admin/controller.php
~ admin/icagenda.php
~ admin/assets/jcms/info.php
~ admin/controllers/event.php
~ admin/controllers/events.php
~ admin/controllers/mail.php
~ admin/controllers/registrations.raw.php
~ admin/controllers/themes.php
~ admin/helpers/icagenda.php
~ admin/models/categories.php
~ admin/models/customfields.php
~ admin/models/download.php
~ admin/models/event.php
~ admin/models/events.php
~ admin/models/mail.php
~ admin/models/registrations.php
~ admin/models/themes.php
~ admin/models/fields/icmap/lat.php
~ admin/models/fields/icmap/lng.php
~ admin/models/fields/modal/coordinate.php
~ admin/models/fields/modal/date.php
~ admin/models/fields/modal/evt_date.php
~ admin/models/fields/modal/ic_password.php
~ admin/models/fields/modal/icfile.php
~ admin/models/fields/modal/iclink_article.php
~ admin/models/fields/modal/iclink_type.php
~ admin/models/fields/modal/iclink_url.php
~ admin/models/fields/modal/ictext_placeholder.php
~ admin/models/fields/modal/ictxt_article.php
~ admin/models/fields/modal/media.php
~ admin/models/fields/modal/menulink.php
~ admin/models/fields/modal/multicat.php
~ admin/models/fields/modal/param_place.php
~ admin/models/fields/modal/ph_regbt.php
~ admin/models/fields/modal/tos_article.php
~ admin/tables/category.php
~ admin/tables/customfield.php
~ admin/tables/event.php
~ admin/tables/feature.php
~ admin/tables/registration.php
~ admin/utilities/addtocal/addtocal.php
~ admin/utilities/ajax/ajax.php
~ admin/utilities/event/event.php
~ admin/utilities/events/data.php
~ admin/utilities/form/field/customfieldgroups.php
~ admin/utilities/form/field/customform.php
~ admin/utilities/form/field/registrationdates.php
~ admin/utilities/form/field/registrationpeople.php
~ admin/utilities/form/field/registrationterms.php
~ admin/utilities/list/list.php
~ admin/utilities/manager/manager.php
~ admin/utilities/menus/menus.php
~ admin/utilities/params/params.php
~ admin/utilities/registration/registration.php
~ admin/views/categories/view.html.php
~ admin/views/category/view.html.php
~ admin/views/customfield/view.html.php
~ admin/views/customfields/view.html.php
~ admin/views/download/view.html.php
~ admin/views/event/view.html.php
~ admin/views/events/view.html.php
~ admin/views/events/tmpl/default.php
~ admin/views/feature/view.html.php
~ admin/views/features/view.html.php
~ admin/views/icagenda/view.html.php
~ admin/views/icagenda/tmpl/default.php
~ admin/views/info/view.html.php
~ admin/views/mail/view.html.php
~ admin/views/registration/view.html.php
~ admin/views/registrations/view.html.php
~ admin/views/registrations/tmpl/default.php
~ admin/views/themes/view.html.php
~ admin/views/themes/tmpl/default.php
~ [LIBRARY] libraries/ic_library/globalize/convert.php
~ [LIBRARY] libraries/ic_library/globalize/globalize.php
~ [LIBRARY] libraries/ic_library/globalize/culture/cs-CZ.php
~ [LIBRARY] libraries/ic_library/globalize/culture/de-DE.php
~ [LIBRARY] libraries/ic_library/globalize/culture/el-GR.php
~ [LIBRARY] libraries/ic_library/globalize/culture/en-GB.php
~ [LIBRARY] libraries/ic_library/globalize/culture/en-US.php
~ [LIBRARY] libraries/ic_library/globalize/culture/es-ES.php
~ [LIBRARY] libraries/ic_library/globalize/culture/fa-IR.php
~ [LIBRARY] libraries/ic_library/globalize/culture/fi-FI.php
~ [LIBRARY] libraries/ic_library/globalize/culture/fo-FO.php
~ [LIBRARY] libraries/ic_library/globalize/culture/fr-FR.php
~ [LIBRARY] libraries/ic_library/globalize/culture/hr-HR.php
~ [LIBRARY] libraries/ic_library/globalize/culture/hu-HU.php
~ [LIBRARY] libraries/ic_library/globalize/culture/iso.php
~ [LIBRARY] libraries/ic_library/globalize/culture/it-IT.php
~ [LIBRARY] libraries/ic_library/globalize/culture/lv-LV.php
~ [LIBRARY] libraries/ic_library/globalize/culture/nb-NO.php
~ [LIBRARY] libraries/ic_library/globalize/culture/nl-NL.php
~ [LIBRARY] libraries/ic_library/globalize/culture/pl-PL.php
~ [LIBRARY] libraries/ic_library/globalize/culture/pt-BR.php
~ [LIBRARY] libraries/ic_library/globalize/culture/pt-PT.php
~ [LIBRARY] libraries/ic_library/globalize/culture/ru-RU.php
~ [LIBRARY] libraries/ic_library/globalize/culture/sk-SK.php
~ [LIBRARY] libraries/ic_library/globalize/culture/sv-SE.php
~ [LIBRARY] libraries/ic_library/globalize/culture/tr-TR.php
~ [LIBRARY] libraries/ic_library/globalize/culture/uk-UA.php
~ [LIBRARY] libraries/ic_library/globalize/culture/zh-TW.php
~ [LIBRARY] libraries/ic_library/thumb/get.php
~ [MODULE][PRO] modules/mod_ic_event_list/mod_ic_event_list.php
~ [MODULE][PRO] modules/mod_ic_event_list/mod_ic_event_list.xml
~ [MODULE][PRO] modules/mod_ic_event_list/tmpl/icrounded.php
~ [MODULE] modules/mod_iccalendar/helper.php
~ [MODULE] modules/mod_iccalendar/mod_iccalendar.php
~ [MODULE] modules/mod_iccalendar/mod_iccalendar.xml
~ [PLUGIN] plugins/search/icagenda/icagenda.php
~ [PLUGIN] plugins/search/icagenda/icagenda.xml
~ [PLUGIN] plugins/system/ic_autologin/ic_autologin.php
~ [PLUGIN] plugins/system/ic_autologin/ic_autologin.xml
~ site/controller.php
~ site/icagenda.php
~ site/controllers/registration.php
~ site/models/event.php
~ site/models/events.php
~ site/models/icagenda.php
~ site/models/list.php
~ site/models/registration.php
~ site/models/submit.php
~ site/models/fields/categories.php
~ [THEME] site/themes/default.xml
~ [THEME] site/themes/ic_rounded.xml
~ [THEME] site/themes/packs/default/default_calendar.php
~ [THEME] site/themes/packs/default/default_day.php
~ [THEME] site/themes/packs/default/default_event.php
~ [THEME] site/themes/packs/default/default_events.php
~ [THEME] site/themes/packs/default/default_registration.php
~ [THEME] site/themes/packs/ic_rounded/ic_rounded_calendar.php
~ [THEME] site/themes/packs/ic_rounded/ic_rounded_day.php
~ [THEME] site/themes/packs/ic_rounded/ic_rounded_event.php
~ [THEME] site/themes/packs/ic_rounded/ic_rounded_events.php
~ [THEME] site/themes/packs/ic_rounded/ic_rounded_registration.php
~ site/views/event/view.html.php
~ site/views/list/view.html.php
~ site/views/registration/view.html.php
~ site/views/submit/view.html.php
~ site/views/submit/tmpl/default.php


iCagenda 3.6.13 <small style="font-weight:normal;">(2018.04.17)</small>
================================================================================
! New : integrates iCagenda component in Joomla extensions updater (LiveUpdate kept for install on J2.5).
+ Added : integrate Falang into frontend search results.
+ Added : character encoding option for registration csv exported file.
+ [PLUGIN] Added : integrate Falang into search plugin.
~ Changed : moves iCagendaOnListPrepare sooner for a better retrieving of data by plugin event.
~ Changed : improve registration export modal on Joomla 3.
~ Changed : improve year frontend filter.
~ Changed : update snippets (title, meta description) length according to new Google limits (December 2017).
~ Changed : improve pagination html/css.
- [MODULE] Removed : Ctrl+Alt+C shortcut to focus on calendar module, to prevent conflict with Polish language shortcut alt+C Ć.
# [LOW] Fixed : multiple groups select in custom field admin edition.
# [LOW] Fixed : naming issue with JHtml select.genericlist for people and date form field. Default Joomla api returns not well formed ID : should be jform_people instead of jformpeople (missing underscore).
# [LOW] Fixed : only positive integer allowed for 'Number/page' in menu options.
# [LOW] Fixed : missing context in description content.prepare (event view).
# [LOW] Fixed : full month too large in date box with default theme pack.
# [LOW] Fixed : first day of the week option for datetime picker.
# [LOW] Fixed : time display (show/hide option not working properly) in RSS feeds.
# [LOW] Fixed : back button routing after click on calendar icon was broken.
# [LOW] Fixed : missing selected date in url on Cancel registration routing back to event view.
# [LOW] Fixed : print preview error 404 when event title starts with a numeric.
# [LOW] Fixed : approval error in admin on some server.
# [LOW] Fixed : conflict with JCE media manager in the feature form field to select an image (files not displayed).
# [LOW] Fixed : frontend search for ongoing full periods sometimes missing in results.
# [LOW] Fixed : missing control if no selected single date in registration admin form.
# [MODULE][LOW] Fixed PHP 7.2 : count returns php warning when null.
# [MODULE][PRO][LOW] Fixed : module width not 100% when only 1 event, and minor flexbox css improvements.
# [MODULE][PRO][LOW] Fixed : time format am/pm broken in module iC Event List.

* Changed files in 3.6.13
~ admin/config.xml
~ admin/models/event.php
~ admin/models/registration.php
~ admin/models/registrations.php
~ admin/models/fields/modal/evt.php
~ admin/models/fields/modal/ictextarea_counter.php
~ admin/models/forms/customfield.xml
~ admin/models/forms/download.xml
~ admin/models/forms/event.xml
~ admin/models/forms/feature.xml
~ admin/utilities/ajax/ajax.php
~ admin/utilities/event/event.php
~ admin/utilities/events/data.php
~ admin/utilities/form/field/customfieldgroups.php
~ admin/utilities/form/field/customform.php
~ admin/utilities/form/field/registrationdates.php
~ admin/utilities/form/field/registrationpeople.php
~ admin/utilities/form/field/registrationterms.php
~ admin/utilities/list/list.php
~ admin/utilities/render/render.php
~ admin/views/download/tmpl/default.php
~ admin/views/event/tmpl/edit.php
~ admin/views/registrations/view.html.php
~ admin/views/registrations/view.raw.php
~ admin/views/registrations/tmpl/default.php
~ icagenda.xml
~ [MEDIA] media/css/icagenda-front.css
~ [MODULE][PRO] modules/mod_ic_event_list/css/default_style.css
~ [MODULE][PRO] modules/mod_ic_event_list/css/icrounded_style.css
~ [MODULE] modules/mod_iccalendar/helper.php
~ [MODULE] modules/mod_iccalendar/mod_iccalendar.php
+ [PLUGIN] plugins/installer/icagenda/icagenda.php
+ [PLUGIN] plugins/installer/icagenda/icagenda.xml
+ [PLUGIN] plugins/installer/icagenda/LICENSE.txt
+ [PLUGIN] plugins/installer/icagenda/language/en-GB/en-GB.plg_installer_icagenda.ini
+ [PLUGIN] plugins/installer/icagenda/language/en-GB/en-GB.plg_installer_icagenda.sys.ini
~ [PLUGIN] plugins/quickicon/icagendaupdate/icagendaupdate.php
~ [PLUGIN] plugins/search/icagenda/icagenda.php
~ [PLUGIN] plugins/search/icagenda/icagenda.xml
~ [PLUGIN] plugins/system/ic_autologin/ic_autologin.php
~ script.icagenda.php
~ site/router.php
~ site/add/elements/icevent_vars.php
~ site/add/elements/iclist_vars.php
~ site/controllers/registration.php
~ site/helpers/iCicons.class.php
~ site/models/list.php
~ site/models/registration.php
~ site/models/fields/year.php
~ site/views/event/view.html.php
~ site/views/event/tmpl/default_top.php
~ site/views/list/view.feed.php
~ site/views/list/view.html.php
~ site/views/list/tmpl/default.xml
~ site/views/registration/view.html.php
~ site/views/registration/tmpl/actions.php
+ site/views/registration/tmpl/cancel.php
~ site/views/registration/tmpl/complete.php
~ site/views/registration/tmpl/default.php
~ site/views/submit/tmpl/default.php


iCagenda 3.6.12 <small style="font-weight:normal;">(2017.07.27)</small>
================================================================================
+ Added : filter by approval state in admin list of events.
~ [MODULE][PRO] Changed : added flexbox CSS to improve multiple columns display in module iC Event list.
~ [THEMES] Changed : minor improvement of frontend search field height on some site templates.
~ [THEME][ic_rounded] Changed : in list view, date boxes use now category color for background if no event image.
# [MEDIUM] Fixed : entering past single date(s) and no period for one event returning a no valid date error warning.
# [LOW] Fixed : missing Joomla api getMaxUploadSize in J3.6.x (file field type), but if you have kept your Joomla 3 installation safe and up-to-date you didn't have the issue of a broken 'Submit an Event' frontend form.

* Changed files in 3.6.12
~ admin/models/events.php
~ admin/tables/event.php
~ admin/views/events/view.html.php
~ [MODULE][PRO] modules/mod_ic_event_list/css/default_style.css
~ [MODULE][PRO] modules/mod_ic_event_list/css/icrounded_style.css
~ site/layouts/joomla/form/field/file.php
~ [THEME] site/themes/packs/default/css/default_component.css
~ [THEME] site/themes/packs/ic_rounded/css/ic_rounded_component.css
~ [THEME] site/themes/packs/ic_rounded/ic_rounded_events.php


iCagenda 3.6.11 <small style="font-weight:normal;">(2017.07.20)</small>
================================================================================
~ Changed : minor improvements of the frontend search results (more accurate when Search Mode is Current List).
# [LOW] Fixed : iCalcreator library php7 issue, rename constructor to __construct.
# [LOW] Fixed : full period (no weekdays) not displayed when display all dates turned-off.
# [LOW] Fixed : wrong alert message in admin event edit, if no start and end date for period, and weekday(s) selected.
# [MODULE][LOW] Patched : possible issue with inconstant "null" date saved by new Joomla calendar picker (since J. 3.7.0), preventing the module calendar to auto-load on current month (This happened when a valid date was previously selected, then by removing date from "Load on date" option field and saving the module, it saved in database a null datetime. A second save of module with this option field empty was needed to remove not expected null datetime saved in database by new joomla calendar picker. The patch included in module does not fix Joomla bug, but fixes possible issue of not loading on current month the iCagenda calendar module).

* Changed files in 3.6.11
~ admin/tables/event.php
~ admin/utilities/events/data.php
~ [LIBRARY] libraries/ic_library/iCalcreator/iCalcreator.class.php
~ [MODULE] modules/mod_iccalendar/helper.php
~ [MODULE] modules/mod_iccalendar/mod_iccalendar.php


iCagenda 3.6.10 <small style="font-weight:normal;">(2017.07.06)</small>
================================================================================
# [MEDIUM] Fixed : datetime picker returning wrong value when empty (period start/end dates and/or single date(s)) that can prevent possibility to save event.
# [LOW] Fixed : filtering by date broken when 'Display all Dates' option is set on 'no'.

* Changed files in 3.6.10
~ admin/models/event.php
~ admin/models/fields/modal/date.php
~ admin/tables/event.php
~ admin/utilities/events/data.php
~ [LIBRARY] libraries/ic_library/date/date.php
~ script.icagenda.php
~ site/models/submit.php


iCagenda 3.6.9 <small style="font-weight:normal;">(2017.07.05)</small>
================================================================================
+ Added : global option to select if the search filters apply as a global search or only to the current list.
+ Added : menu options for frontend search filters (not available on Joomla 2.5).
+ Added : [DESC], [SHORTDESC] and [METADESC] tags for email notification (frontend 'Registration' and 'Submit an Event' forms).
+ Added : set datetime picker for event dates in am/pm mode when option enabled for am/pm time.
# [Joomla 2.5][HIGH] Fixed : list of events white screen of death (Catchable fatal error : JInput::getArray()).
# [Joomla][LOW] Patched : minimum digits for phone number validation is 7 in Joomla api. Adds a custom rule for phone check to allow a minimum of 6 digits (New Caledonia).
# [Joomla 3.7][LOW] Fixed : display of Max Server upload limit in frontend file type field, not accurate with iCagenda settings (Joomla! api issue on this new feature, where Joomla layout forces to display the server max upload limit...).
# [Joomla 3.7][LOW] Fixed : width of the "override" label when selected in dropdown list of custom field types.
# [MODULE][LOW] Fixed : Hungarian hu-HU year before month in calendar module.
# [THEME][LOW] Fixed : z-index added to class .ic-share, for AddThis sharing buttons in float position (minor issue with a few site templates screen sizes).

* Changed files in 3.6.9
~ admin/config.xml
~ admin/assets/elements/title.php
~ admin/assets/elements/titleimg.php
~ admin/models/event.php
+ admin/models/fields/list.php
~ admin/tables/event.php
~ admin/utilities/events/data.php
~ admin/utilities/list/list.php
~ admin/views/customfield/tmpl/edit.php
~ admin/views/event/tmpl/edit.php
~ [LIBRARY] libraries/ic_library/form/field/sortablefields.php
+ [LIBRARY] libraries/ic_library/form/rule/positiveinteger.php
+ [LIBRARY] libraries/ic_library/form/rule/tel.php
- [LIBRARY][FOLDER] libraries/ic_library/form/rules
~ [MEDIA] media/css/icagenda-back.css
~ [MEDIA] media/css/icagenda-back.j25.css
~ [MEDIA] media/js/icdates.js
~ [MODULE] modules/mod_iccalendar/helper.php
~ [PLUGIN] plugins/system/ic_library/ic_library.php
~ script.icagenda.php
+ site/layouts/joomla/form/field/file.php
~ site/models/fields/categories.php
~ site/models/fields/year.php
~ site/models/registration.php
~ site/models/submit.php
~ [THEME] site/themes/packs/default/css/default_component.css
~ [THEME] site/themes/packs/ic_rounded/css/ic_rounded_component.css
~ site/views/list/tmpl/default.php
~ site/views/list/tmpl/default.xml
~ site/views/list/tmpl/default_filters.php
~ site/views/submit/tmpl/default.php


iCagenda 3.6.8 <small style="font-weight:normal;">(2017.05.03)</small>
================================================================================
+ Added : get mode option from Joomla plugin email.cloak to set contact email as linkable mailto address (default) or not.
# [LOW] Fixed : missing addslashes for event stats labels in control panel (broken when double-quotes in event/category title).
# [LOW] Fixed : issue with counting of events to be displayed when filter by Features is used in main list of events, resulting in extra pages with no events (bug introduced since patch for module in 3.6.7).
# [LOW] Fixed : URLs after frontend search filters submit and reset click when SEF enable.
# [LOW] Fixed : [STARTDATETIME] and [ENDDATETIME] tags in registration custom notification email broken if only one period and no single date for the event.

* Changed files in 3.6.8
~ admin/utilities/events/data.php
~ admin/utilities/render/render.php
~ admin/views/icagenda/tmpl/default.php
~ site/add/elements/icevent_vars.php
~ site/models/registration.php
~ site/views/list/tmpl/default.php


iCagenda 3.6.7 <small style="font-weight:normal;">(2017.04.27)</small>
================================================================================
! Joomla 3.7 Ready
+ Added : icagenda plugin event 'iCagendaOnNewEvent' (When a new event is first published and approved. In plugin group 'icagenda').
+ Added : content plugin event 'iCagendaOnEventPrepare' (Preparing content for output. To modify 'event' data).
+ Added : content plugin event 'iCagendaOnEventBeforeDisplay' (Before the generated content of the 'event details' view).
+ Added : content plugin event 'iCagendaOnEventAfterDisplay' (After the generated content of the 'event details' view).
+ Added : content plugin event 'iCagendaOnListPrepare' (Preparing content for output. To modify 'list of events' data).
+ Added : content plugin event 'iCagendaOnListBeforeDisplay' (Before the generated content of the 'list of events' view).
+ Added : content plugin event 'iCagendaOnListAfterDisplay' (After the generated content of the 'list of events' view).
+ Added : content plugin event 'iCagendaOnRegistrationPrepare' (Preparing content for output. To modify 'event/registration' data).
+ Added : content plugin event 'iCagendaOnRegistrationBeforeDisplay' (Before the generated form of the 'registration' view).
+ Added : content plugin event 'iCagendaOnRegistrationAfterDisplay' (After the generated form of the 'registration' view).
+ Added : content plugin event 'iCagendaOnSubmitPrepare' (Preparing content for output. To modify 'submit an event' data).
+ Added : content plugin event 'iCagendaOnSubmitBeforeDisplay' (Before the generated form of the 'submit an event' view).
+ Added : content plugin event 'iCagendaOnSubmitAfterDisplay' (After the generated form of the 'submit an event' view).
~ Changed : extends Frontend search results. Add search in Short Description and Address.
~ Changed : frontend search filters improvement (form method changed from 'post' to 'get', and search enhancement).
~ Changed : update copyright 2017.
~ [THEMES] Changed : minor improvements to be more friendly with dark site templates.
- Removed : hardcoded 'input-large' class from custom form input field type text (to be constant with core fields)
# [MEDIUM] Fixed : alert message on registration after submit, blocking redirect to confirmation page (registration correctly processed), due to new Joomla Custom Fields system plugin (loading admin helper in frontend).
# [LOW] Fixed : button style issue with Joomla 3.7.
# [LOW] Fixed : missing dropdown selection of available dates for register button.
# [LOW] Fixed : access to complete view if no tickets available after registration.
# [LOW] Fixed : thumbnails generation from distant url.
# [LOW] Fixed : registration counter when option 'Display All Dates' is turned off.
# [LOW] Fixed : custom fields required failled to be checked if form validation is set only on 'Server' for 'Submit an event' form.
# [LOW] Fixed : html tags error in admin category edit view.
# [LOW] Fixed : should not display date (wrong format because null) in registration custom notification email when using tags [DATE] and [TIME] and registration mode is 'for all dates'.
# [MODULE][PRO][LOW] Fixed : wrong counting of events to be displayed when filter by Features is used.
# [THEMES][LOW] Fixed : frontend search input height on some site templates.

* Changed files in 3.6.7
~ admin/models/event.php
~ admin/models/forms/event.xml
~ admin/utilities/customfields/customfields.php
~ admin/utilities/events/data.php
~ admin/utilities/list/list.php
~ admin/utilities/manager/manager.php
~ admin/utilities/registration/registration.php
~ admin/utilities/render/render.php
~ admin/views/category/tmpl/edit.php
~ admin/views/icagenda/tmpl/default.php
~ admin/views/themes/tmpl/default.php
~ [LIBRARY] libraries/ic_library/url/url.php
~ [MEDIA] media/css/icagenda-front.css
~ [MODULE][PRO] modules/mod_ic_event_list/mod_ic_event_list.xml
~ script.icagenda.php
~ site/add/elements/iclist_vars.php
~ site/models/events.php
~ site/models/registration.php
~ site/models/submit.php
~ [THEME] site/themes/packs/default/css/default_component.css
~ [THEME] site/themes/packs/ic_rounded/css/ic_rounded_component.css
~ site/views/event/tmpl/default.php
~ site/views/event/view.html.php
~ site/views/list/tmpl/default_filters.php
~ site/views/list/tmpl/default.php
~ site/views/list/view.html.php
~ site/views/registration/tmpl/default.php
~ site/views/registration/view.html.php
~ site/views/submit/tmpl/default.php
~ site/views/submit/view.html.php


iCagenda 3.6.6 <small style="font-weight:normal;">(2016.12.20)</small>
================================================================================
+ [LIBRARY] Added : form field positive integer.
~ Changed : improved session management for current date of the event.
~ Changed : improved registrations status (number of tickets booked/available).
~ [VIEW] Changed : simplifies event/default_top view file.
~ [VIEW] Changed : removed header param control in list/default.php view file.
~ [VIEW] Changed : removed registration type control for custom fields in registration/default.php view file.
~ [MODULES] Changed : improves routing to event details view.
# [MEDIUM] Fixed : access to registration form not possible for languages where joomla transliteration returns empty value (Asian languages...).
# [LOW] Fixed : disallow zero value for number of tickets per registration.
# [LOW] Fixed : empty space for values of options in custom field type 'List', when no usage of value/label pairs.
# [LOW] Fixed : time displayed in list of all dates in event details view, even if display time option turned off.
# [LOW] Fixed : code tags {} inside Short Description should be removed.
# [LOW] Fixed : do not display a dropdown list of dates if only one option.
# [LOW] Fixed : no event message not displayed if header subtitle not enabled.

* Changed files in 3.6.6
~ admin/config.xml
~ admin/utilities/customfields/customfields.php
~ admin/utilities/event/event.php
~ admin/utilities/events/data.php
~ admin/utilities/events/events.php
~ admin/utilities/form/field/registrationdates.php
~ admin/utilities/list/list.php
~ admin/utilities/registration/participants.php
~ admin/utilities/registration/registration.php
+ [LIBRARY] libraries/ic_library/form/rules/positiveinteger.php
~ [MEDIA] media/css/icagenda-back.css
~ [MODULE][PRO] modules/mod_ic_event_list/mod_ic_event_list.php
~ [MODULE] modules/mod_iccalendar/helper.php
~ site/add/elements/icevent_vars.php
~ site/models/event.php
~ site/models/list.php
~ site/models/registration.php
~ site/router.php
~ [VIEW] site/views/event/tmpl/default_top.php
~ [VIEW] site/views/list/tmpl/default.php
~ [VIEW] site/views/registration/tmpl/default.php
~ site/views/registration/view.html.php


iCagenda 3.6.5 <small style="font-weight:normal;">(2016.11.23)</small>
================================================================================
+ Added : Ajax refresh of number of tickets in the frontend registration form, depending of the date selected.
+ Added : new css classes to able custom styles on period dates in event details view 'List of Dates'.
~ Changed : refactory of the functions to count number of registrations booked/available for component and modules.
~ Changed : default status is set to 'published' in status filter when accessing the registrations list in admin (and for export csv).
~ Changed : improve telephone field type check if valid phone number (registration form).
# [LOW] Fixed : dates display in admin registrations list, after adding a new registetration using admin, and event registration type is for all dates.
# [LOW] Fixed : control maximum of available tickets when creating a registration in admin.
# [LOW] Fixed : control maximum of available tickets in frontend registration form was broken if less available places than selected number of tickets.
# [LOW] Fixed : missing required attribute when using a custom field of type override name.
# [LOW] Fixed : submission of empty required custom fields filled with space (missing trim spaces).
# [LOW] Fixed : search in frontend with from/to the same day returned no event even if events exist for this date.
# [LOW] Fixed : image select input width when using JCE media field type.
# [LOW] Fixed : possible wrong date display in event view (when multiple dates).
# [LOW] Fixed : bugs in RSS feeds (missing category, wrong date...) and minor improvement (using global joomla RSS feeds limit).
# [MODULE][PRO][LOW] Fixed : introduction text broken (short description).

* Changed files in 3.6.5
~ admin/models/fields/modal/evt.php
~ admin/models/fields/modal/icmulti_checkbox.php
~ admin/models/forms/registration.xml
~ admin/models/registration.php
~ admin/models/registrations.php
~ admin/tables/registration.php
~ admin/utilities/ajax/ajax.php
~ admin/utilities/customfields/customfields.php
~ admin/utilities/event/event.php
~ admin/utilities/events/data.php
~ admin/utilities/form/field/registrationdates.php
~ admin/utilities/form/field/registrationpeople.php
~ admin/utilities/registration/participants.php
~ admin/utilities/registration/registration.php
~ admin/views/registration/tmpl/edit.php
~ admin/views/registrations/view.html.php
~ [LIBRARY] libraries/ic_library/form/field/sortablefields.php
~ [MEDIA] media/css/icagenda-back.css
~ [MODULE][PRO] modules/mod_ic_event_list/mod_ic_event_list.php
~ [MODULE] modules/mod_iccalendar/helper.php
~ script.icagenda.php
~ site/add/elements/icevent_vars.php
~ site/controller.php
~ site/controllers/registration.php
~ site/models/event.php
~ site/models/events.php
~ site/models/forms/registration.xml
~ site/models/list.php
~ site/models/registration.php
~ site/views/event/tmpl/default_top.php
~ site/views/list/view.feed.php
~ site/views/registration/tmpl/default.php
~ site/views/registration/view.html.php


iCagenda 3.6.4.1 (patch Joomla 2.5) <small style="font-weight:normal;">(2016.10.18)</small>
================================================================================
# [Joomla 2.5] Fixed : empty list of events in component List Events view (patch for iCagenda 3.6.4).

* Changed files in 3.6.4.1
~ admin/utilities/events/data.php


iCagenda 3.6.4 <small style="font-weight:normal;">(2016.10.15)</small>
================================================================================
+ [MODULE][CALENDAR] Added : option to display an event only if a menu item of type 'List Events' (or the selected menu item in the 'Link to Menu Item' option) matches the event depending on the menu options and event settings.
~ [MODULE][PRO] Changed : code improvement.
# [LOW] Fixed : https for gravatar on ssl server.
# [LOW][MODULES] Fixed : alert message for admins in frontend (and improvement of events to be displayed controls).
# [LOW] Fixed : issue with multiple xml attribute due to a bug with 'showon' in Joomla 3.2.4 > 3.4.1 (custom field admin edit).
# [LOW] Fixed : event individual option to show or hide AddThis on event details view.
# [LOW] Fixed : custom fields input width with site templates not supporting Joomla core fields layouts.
# [LOW] Fixed : global registrations counter.

* Changed files in 3.6.4
~ admin/config.xml
~ admin/models/event.php
~ admin/models/fields/modal/menulink.php
~ admin/models/forms/customfield.xml
~ admin/tables/event.php
~ admin/utilities/addthis/addthis.php
~ admin/utilities/customfields/customfields.php
~ admin/utilities/events/data.php
~ admin/utilities/form/field/customfieldgroups.php
~ admin/utilities/menus/menus.php
~ admin/utilities/registration/participants.php
~ [MODULE][PRO] modules/mod_ic_event_list/mod_ic_event_list.php
~ [MODULE][PRO] modules/mod_ic_event_list/mod_ic_event_list.xml
~ [MODULE][PRO] modules/mod_ic_event_list/tmpl/icrounded.php
~ [MODULE] modules/mod_iccalendar/helper.php
~ [MODULE] modules/mod_iccalendar/mod_iccalendar.xml
~ site/models/event.php
~ site/models/forms/registration.xml
~ site/views/list/tmpl/default.php


iCagenda 3.6.3 <small style="font-weight:normal;">(2016.09.26)</small>
================================================================================
+ [MODULE][PRO] Added : options to filter events by Feature(s) in Module iC Event List.
+ Added : plugin event handler on Event Prepare (to allow individually by event, enabling/disabling jComments integration by using JComments tag {jcomments off} in event full description editor. Needs version 1.1.0 of iC JComments plugin: http://icagenda.joomlic.com/resources/addons/jcomments-content-plugin)
~ [MODULES] Changed : Mode 'auto' for Menu Item Link option now will allow to display events even if no menu item link set properly to display this event (will generate a standard sef url using the home menu item (Joomla! routing system), in the identical way Joomla Articles modules behave).
~ [THEME PACKS] Changed : Responsive improvements for Frontend Search Filters buttons.
~ Changed : 'complete' view (landing page after registration with a bit more information on user's registration).
~ Changed : a bit of code clean-up and minor improvements as well as css corrections to prevent possible css conflict with a few site templates.
# [LOW][MODULE] Fixed : Registration status fixes in module Calendar (could return close when still open).
# [LOW][MODULE] Fixed : Events disappearing in module Calendar (issue with date control).
# [LOW] Fixed : Registration dates if Type is set for registration to all the dates (fix on registration process and update database to fix issue).
# [LOW] Fixed : Missing event state control if access through event's direct url.
# [LOW] Fixed : Missing one translation string in confirmation page after registration has been processed ('Registration Information').
# [LOW] Fixed : Missing checkbox label for Terms and Conditions in Registration form.
# [LOW] Fixed : Hits only on published events (could have counts on other status).
# [LOW] Fixed : Script error from a deprecated script (not used anymore) in registration complete view.
# [LOW] Fixed : Script error in menu item options.

* Changed files in 3.6.3
~ admin/models/fields/modal/icmulti_opt.php
~ admin/utilities/customfields/customfields.php
~ admin/utilities/event/event.php
~ admin/utilities/events/data.php
~ admin/utilities/form/field/registrationdates.php
~ admin/utilities/form/field/registrationterms.php
~ admin/utilities/registration/participants.php
~ [MEDIA] media/css/icagenda-front.css
~ [MODULE][PRO] modules/mod_ic_event_list/helper.php
~ [MODULE][PRO] modules/mod_ic_event_list/mod_ic_event_list.php
~ [MODULE][PRO] modules/mod_ic_event_list/mod_ic_event_list.xml
~ [MODULE] modules/mod_iccalendar/helper.php
~ [MODULE] modules/mod_iccalendar/mod_iccalendar.php
~ [MODULE] modules/mod_iccalendar/mod_iccalendar.xml
~ [INSTALL] script.icagenda.php
~ site/models/event.php
~ site/models/events.php
~ site/models/forms/registration.xml
~ [THEME PACKS] site/themes/packs/default/css/default_component.css
~ [THEME PACKS] site/themes/packs/default/css/default_component_medium.css
~ [THEME PACKS] site/themes/packs/default/css/default_component_small.css
~ [THEME PACKS] site/themes/packs/default/css/default_component_xsmall.css
~ [THEME PACKS] site/themes/packs/ic_rounded/css/ic_rounded_component.css
~ [THEME PACKS] site/themes/packs/ic_rounded/css/ic_rounded_component_medium.css
~ [THEME PACKS] site/themes/packs/ic_rounded/css/ic_rounded_component_small.css
~ [THEME PACKS] site/themes/packs/ic_rounded/css/ic_rounded_component_xsmall.css
~ site/views/event/view.html.php
~ site/views/registration/tmpl/complete.php
~ site/views/registration/tmpl/default.php
~ site/views/registration/view.html.php


iCagenda 3.6.2 <small style="font-weight:normal;">(2016.09.01)</small>
================================================================================
# [LOW] Fixed : Custom fields not saved in 3.6.1 in admin event edit (issue due to frontend fix).
# [LOW] Fixed : Approval icon in frontend broken (no effect).
# [LOW] Fixed : Registration button type if no other dates, and period registration complete.
# [LOW] Fixed : Search input filter not returning past events in search results.

* Changed files in 3.6.2
~ admin/utilities/customfields/customfields.php
~ admin/utilities/events/data.php
~ admin/utilities/form/field/registrationdates.php
~ admin/utilities/manager/manager.php
~ admin/utilities/registration/registration.php


iCagenda 3.6.1 <small style="font-weight:normal;">(2016.08.29)</small>
================================================================================
+ Added : force system slugs for overrides of core fields and protect from usage in other custom field types.
~ Changed : minor UI improvement of custom field edition.
~ Changed : set properly required column setting in Custom Fields admin list.
~ Changed : don't show 'slug' option if type is a core field override in custom field edition form (needs minimum Joomla 3.2.4 for showon feature).
~ Changed : Check if user ID exists to prevent alert message in events admin list. This Joomla alert message is kept for the event edition where user does not exist anymore, but hidden in the admin list of events to prevent misunderstanding.
# [MEDIUM] Fixed : Custom fields data not saved in frontend 'Submit an Event' form.
# [LOW] Fixed : event add to cal in .ics format (iCal and Outlook).
# [LOW] Fixed : warning message if error reporting turn on, and no custom fields set for Submit an Event form.
# [LOW] Fixed : usage of core_* slug names in not-core field types.
# [LOW] Fixed : required option broken for core field 'Phone' in registration form.
# [LOW] Fixed : search filter of type calendar better adjustment to fit with site template not managing well Joomla core calendar picker input field.
# [LOW] Fixed : advanced email check options in registration form broken.
# [LOW] Fixed : RSS feeds broken.

* Changed files in 3.6.1
~ admin/models/events.php
~ admin/models/forms/customfield.xml
~ admin/tables/customfield.php
~ admin/utilities/customfields/customfields.php
~ admin/utilities/event/data.php
~ admin/views/customfield/tmpl/edit.php
~ admin/views/customfields/tmpl/default.php
~ admin/views/customfields/view.html.php
~ [MEDIA] media/css/icagenda-front.css
~ site/models/registration.php
~ site/views/event/tmpl/default_vcal.php
~ site/views/list/tmpl/default_filters.php
~ site/views/list/view.feed.php
~ site/views/submit/tmpl/default.php


iCagenda 3.6.0 <small style="font-weight:normal;">(2016.08.19)</small>
================================================================================
! New : Frontend Search Filters
1 - Global Options: Component Options > List tab.
1 - Drag & drop filters options to change frontend ordering.
1 - Options of display (responsive, full width, show or hide 'Search' label...).
! Refactory : List of events
1 - The list view is now divided into 3 separate views: list, event and registration.
1 - Performance improvement: up to 10 times faster to load the main list of events!
! Refactory : Registration Form
1 - A full new view HTML structure, to load all fields with a maximum of flexibility
1 &nbsp; and performance (see custom field groups)
! New : Admin Custom Fields List and Edition Reviewed
1 - List: useful info directly in the list of custom fields.
1 - Edition: Will show option(s) depending on selected parent form and field type
1 &nbsp; (minimum Joomla 3.2.4 to get this functionnality).
! New : Custom Field Groups
1 - Create and manage groups for custom fields (directly in Custom Field edition).
1 - Set individually in each event edition (Registration tab) the groups of custom fields to use for the registration form.
! New : Custom Field Types
1 - New field types: date (calendar), url, email
1 - New field type "label (separator)": to add label headings inside form (with custom CSS class option)
1 - New field type "description (separator)": to add a description text inside form (with custom CSS class option)
1 - Override field types: name, email, phone, date and tickets
1 &nbsp; (replace the core iCagenda fields, to allow ordering inside custom fields as you want!)
! New : Statistics
1 - Hits on events.
1 - Charts for category and event hits, displayed in the control panel of iCagenda.
! Anonymous Usage Statistics
1 Usage statistics or 'Telemetry' is a feature in iCagenda that sends anonymously and automatically your system information (Similar to the Joomla! Statistics plugin introduced in Joomla 3.5).
1 This will only submit the Joomla! version, PHP version, database engine and version, and iCagenda version. Usage statistics are collected during the update, and help us improve future versions of iCagenda.
1 The code used for this anonymous data submission can be read in the install script file of the extension (script.icagenda.php).
1 You can disable this feature in global options of iCagenda component, 'General Settings' tab.
! New : Module iC Event List (PRO)
1 - Option to display all dates.
+ Added : option to set month format in the date box (list of events).
+ Added : missing options in filter by custom field type (admin list of custom fields).
+ Added : control for current page in list pagination before rendering.
+ Added : select user in registration edition, with auto-filled name and email (depending on your global settings).
+ Added : admin filter (dropdown) to select custom fields by group.
+ Added : landing page for registration form in frontend.
- Removed : all not needed anymore rel="nofollow" in modules event links.
~ Changed : date drop down in registration form is auto selected on the date clicked.
~ [THEME PACKS] Changed : in THEME_events.php, variable $EVENT_MONTHSHORT replaced by $EVENT_MONTH (to use new month format option).
~ Changed : includes all fixes and improvements of versions 3.5.14 and 3.5.21 (see releases notes for additional info).

* Changed files in 3.6.0
~ admin/config.xml
~ admin/controllers/customfield.php
~ admin/controllers/registration.php
~ admin/icagenda.php
~ admin/liveupdate/liveupdate.php
~ admin/models/category.php
~ admin/models/customfield.php
~ admin/models/customfields.php
~ admin/models/event.php
~ admin/models/events.php
~ admin/models/fields/modal/cat.php
~ admin/models/fields/modal/checkdnsrr.php
~ admin/models/fields/modal/ic_editor.php
~ admin/models/fields/modal/ictext_placeholder.php
+ admin/models/fields/spacer/description.php
+ admin/models/fields/spacer/label.php
~ admin/models/forms/customfield.xml
~ admin/models/forms/event.xml
~ admin/models/forms/registration.xml
~ admin/models/icagenda.php
~ admin/models/registrations.php
~ admin/sql/install/mysql/icagenda.install.sql
~ admin/sql/uninstall/mysql/icagenda.uninstall.sql
~ admin/tables/customfield.php
~ admin/tables/event.php
+ [FOLDER] admin/utilities/addthis/
+ admin/utilities/addthis/addthis.php
+ [FOLDER] admin/utilities/addtocal/
+ admin/utilities/addtocal/addtocal.php
~ admin/utilities/ajax/ajax.php
+ admin/utilities/ajax/filter.php
~ admin/utilities/customfields/customfields.php
+ [FOLDER] admin/utilities/event/
+ admin/utilities/event/data.php
+ admin/utilities/event/event.php
~ admin/utilities/events/data.php
~ admin/utilities/events/events.php
+ [FOLDER] admin/utilities/form/field/
+ admin/utilities/form/field/customfieldgroups.php
+ admin/utilities/form/field/customform.php
+ admin/utilities/form/field/registrationdates.php
+ admin/utilities/form/field/registrationpeople.php
+ admin/utilities/form/field/registrationterms.php
~ admin/utilities/form/form.php
+ [FOLDER] admin/utilities/googlemaps/
+ admin/utilities/googlemaps/googlemaps.php
+ [FOLDER] admin/utilities/list/
+ admin/utilities/list/list.php
+ [FOLDER] admin/utilities/manager/
+ admin/utilities/manager/manager.php
+ [FOLDER] admin/utilities/registration/
+ admin/utilities/registration/participants.php
+ admin/utilities/registration/registration.php
+ [FOLDER] admin/utilities/render/
+ admin/utilities/render/render.php
+ admin/utilities/theme/joomla25.php
+ admin/utilities/theme/style.php
~ admin/utilities/theme/theme.php
~ admin/views/customfield/tmpl/edit.php
~ admin/views/customfields/tmpl/default.php
~ admin/views/customfields/view.html.php
~ admin/views/event/tmpl/edit.php
~ admin/views/event/view.html.php
~ admin/views/events/tmpl/default.php
~ admin/views/icagenda/tmpl/default.php
~ admin/views/icagenda/view.html.php
~ admin/views/info/tmpl/default.php
~ admin/views/registration/tmpl/edit.php
~ admin/views/registrations/tmpl/default.php
~ icagenda.xml
~ [LIBRARY] libraries/ic_library/date/date.php
~ [LIBRARY] libraries/ic_library/filter/output.php
+ [LIBRARY][FOLDER] libraries/ic_library/form/
+ [LIBRARY][FOLDER] libraries/ic_library/form/field/
+ [LIBRARY]libraries/ic_library/form/field/sortablefields.php
~ [LIBRARY] libraries/ic_library/globalize/culture/cs-CZ.php
~ [LIBRARY] libraries/ic_library/globalize/culture/en-GB.php
+ [LIBRARY][FOLDER] libraries/ic_library/iCalcreator/
+ [LIBRARY]libraries/ic_library/iCalcreator/iCalcreator.class.php
+ [LIBRARY][FOLDER] libraries/ic_library/render/
+ [LIBRARY] libraries/ic_library/render/render.php
~ [LIBRARY] libraries/ic_library/url/url.php
~ [MEDIA] media/css/icagenda-back.css
~ [MEDIA] media/css/icagenda-front.css
~ [MEDIA] media/css/icagenda-front.j25.css
~ [MEDIA][iCicons] media/icicons/fonts
~ [MEDIA][iCicons] media/icicons/style.css
- [MEDIA] media/images/video_poster_icagenda.jpg
- [MEDIA] media/images/youtube_iCagenda.png
+ [MEDIA] media/js/Chart.js
+ [MEDIA] media/js/Chart.min.js
~ [MEDIA] media/js/icagenda.js
~ [MODULE][PRO] modules/mod_ic_event_list/helper.php
~ [MODULE][PRO] modules/mod_ic_event_list/mod_ic_event_list.php
~ [MODULE][PRO] modules/mod_ic_event_list/mod_ic_event_list.xml
~ [MODULE][PRO] modules/mod_ic_event_list/tmpl/default.php
~ [MODULE][PRO] modules/mod_ic_event_list/tmpl/icrounded.php
~ [MODULE] modules/mod_iccalendar/helper.php
~ [MODULE] modules/mod_iccalendar/mod_iccalendar.php
~ [PLUGIN] plugins/search/icagenda/icagenda.php
~ [PLUGIN] plugins/system/ic_library/ic_library.php
~ script.icagenda.php
+ site/add/elements/icevent_vars.php
+ site/add/elements/iclist_vars.php
- site/add/elements/icsetvar.php
~ site/controller.php
+ [FOLDER] site/controllers/
+ site/controllers/registration.php
- site/helpers/iCalcreator.class.php
- site/helpers/ichelper.php
- site/helpers/icmodel.php
- site/helpers/media_css.class.php
~ site/icagenda.php
+ site/models/event.php
~ site/models/events.php
+ [FOLDER] site/models/fields/
+ site/models/fields/categories.php
+ site/models/fields/month.php
+ site/models/fields/year.php
~ site/models/forms/registration.xml
~ site/models/forms/submit.xml
~ site/models/list.php
+ site/models/registration.php
~ site/models/submit.php
~ site/router.php
~ [THEME PACK] site/themes/packs/default/css/default_component.css
~ [THEME PACK] site/themes/packs/default/css/default_component_medium.css
~ [THEME PACK] site/themes/packs/default/css/default_component_small.css
~ [THEME PACK] site/themes/packs/default/css/default_component_xsmall.css
~ [THEME PACK] site/themes/packs/default/default_day.php
~ [THEME PACK] site/themes/packs/default/default_events.php
~ [THEME PACK] site/themes/packs/ic_rounded/css/ic_rounded_component.css
~ [THEME PACK] site/themes/packs/ic_rounded/css/ic_rounded_component_medium.css
~ [THEME PACK] site/themes/packs/ic_rounded/css/ic_rounded_component_small.css
~ [THEME PACK] site/themes/packs/ic_rounded/css/ic_rounded_component_xsmall.css
~ [THEME PACK] site/themes/packs/ic_rounded/ic_rounded_day.php
~ [THEME PACK] site/themes/packs/ic_rounded/ic_rounded_events.php
+ [FOLDER] site/views/event/
+ [FOLDER] site/views/event/tmpl/
+ site/views/event/tmpl/default.php
+ site/views/event/tmpl/default_top.php
+ site/views/event/tmpl/default_vcal.php
+ site/views/event/view.html.php
- site/views/list/tmpl/actions.php
~ site/views/list/tmpl/default.php
~ site/views/list/tmpl/default.xml
~ site/views/list/tmpl/default_categories.php
+ site/views/list/tmpl/default_filters.php
- site/views/list/tmpl/default_vcal.php
- site/views/list/tmpl/event.php
- site/views/list/tmpl/registration.php
~ site/views/list/view.feed.php
~ site/views/list/view.html.php
+ [FOLDER] site/views/registration/
+ [FOLDER] site/views/registration/tmpl/
+ site/views/registration/tmpl/actions.php
+ site/views/registration/tmpl/complete.php
+ site/views/registration/tmpl/default.php
+ site/views/registration/view.html.php
~ site/views/submit/tmpl/default.php
~ site/views/submit/view.html.php


iCagenda 3.5.21 <small style="font-weight:normal;">(2016.08.04)</small>
================================================================================
# [HIGH] Fixed : Fresh install and udpate could fail with a broken admin (only free version).
# [LOW] Fixed : warning if library disabled in control panel

* Changed files in 3.5.21
~ admin/icagenda.php
~ icagenda.xml


$ iCagenda 3.6.0-rc1 <small style="font-weight:normal;">(2016.07.30)</small>
================================================================================
! Anonymous Usage Statistics
1 Usage statistics or 'Telemetry' is a feature in iCagenda that sends anonymously and automatically your system information (Similar to the Joomla! Statistics plugin introduced in Joomla 3.5).
1 This will only submit the Joomla! version, PHP version, database engine and version, and iCagenda version. Usage statistics are collected during the update, and help us improve future versions of iCagenda.
1 The code used for this anonymous data submission can be read in the install script file of the extension (script.icagenda.php).
1 You can disable this feature in global options of iCagenda component, 'General Settings' tab.
# [LOW] Fixed : default setting for new option DateBox Month Format
# [LOW] Fixed : warning if library disabled in control panel
# [LOW] Fixed : warning if no custom fields in registration form

* Changed files in 3.6.0-rc1
~ admin/config.xml
~ admin/icagenda.php
~ admin/views/icagenda/tmpl/default.php
~ admin/views/info/tmpl/default.php
~ [MEDIA] media/icicons/style.css
~ [MODULE][PRO] modules/mod_ic_event_list/mod_ic_event_list.php
~ [MODULE] modules/mod_iccalendar/mod_iccalendar.php
~ script.icagenda.php
~ site/add/elements/iclist_vars.php
~ site/views/registration/tmpl/default.php


iCagenda 3.5.20 <small style="font-weight:normal;">(2016.07.28)</small>
================================================================================
# [LOW] Fixed : missing Google API Key when set, in frontend 'Submit an Event' form.
# [LOW] Fixed : missing some current dates (single dates) when 'Current and upcoming events' filtering.
# [LOW] Fixed : possible issue of css conflict between iCagenda icons font and site template icons font.

* Changed files in 3.5.20
~ admin/utilities/events/data.php
~ [MEDIA] media/icicons/style.css
~ site/views/submit/tmpl/default.php


$ iCagenda 3.6.0-beta1 <small style="font-weight:normal;">(2016.07.13)</small>
================================================================================
! Refactory : Custom Fields admin list
1 - improve Custom Fields admin list usability and readability.
! New : Custom Field Types
1 - new field type "label (separator)": to add label headings inside form (with custom CSS class option)
1 - new field type "description (separator)": to add a description text inside form (with custom CSS class option)
+ Added : option to set month format in the date box (list of events).
+ Added : missing options in filter by custom field type (admin list of custom fields).
+ Added : control for current page in list pagination before rendering.
- Removed : all not needed anymore rel="nofollow" in modules event links.
~ Changed : includes all fixes and improvements of versions 3.5.18 and 3.5.19 (see releases notes for additional info).
~ [THEME PACKS] Changed : in THEME_events.php, variable $EVENT_MONTHSHORT replaced by $EVENT_MONTH (to use new month format option).
# Fixed : event individual option to set registration deadline.
# Fixed : missing custom fields filled in admin registration edit page.
# Fixed : minor search filters grid css adjustement.
# Fixed : Error page if page searched does not exist.

* Changed files in 3.6.0-beta1
~ admin/config.xml
~ admin/models/category.php
~ admin/models/customfield.php
~ admin/models/customfields.php
~ admin/models/event.php
~ admin/models/fields/modal/cat.php
+ admin/models/fields/spacer/description.php
+ admin/models/fields/spacer/label.php
~ admin/models/forms/customfield.xml
~ admin/tables/customfield.php
~ admin/utilities/customfields/customfields.php
~ admin/utilities/event/event.php
~ admin/utilities/events/data.php
~ admin/utilities/events/events.php
~ admin/utilities/form/field/registrationdates.php
~ admin/utilities/googlemaps/googlemaps.php
~ admin/utilities/list/list.php
~ admin/utilities/registration/registration.php
~ admin/views/customfield/tmpl/edit.php
~ admin/views/customfields/tmpl/default.php
~ admin/views/event/tmpl/edit.php
~ admin/views/registration/tmpl/edit.php
~ [LIBRARY] libraries/ic_library/date/date.php
~ [MEDIA] media/css/icagenda-front.css
~ [MODULE][PRO] modules/mod_ic_event_list/mod_ic_event_list.php
~ [MODULE][PRO] modules/mod_ic_event_list/tmpl/default.php
~ [MODULE][PRO] modules/mod_ic_event_list/tmpl/icrounded.php
~ [PLUGIN] plugins/quickicon/icagendaupdate/icagendaupdate.php
~ site/add/elements/iclist_vars.php
~ site/models/list.php
~ site/models/registration.php
~ site/models/submit.php


iCagenda 3.5.19 <small style="font-weight:normal;">(2016.06.30)</small>
================================================================================
! Since June 22, 2016, Google Maps API no longer support keyless access.
1 Active domains created before June 22, 2016, continue to be able to access the Google Maps JavaScript API, Static Maps API, and Street View Image API without an API key. They are not affected by keyless access being unavailable for new domains.
1 However it is advised to use a key in order to guarantee the quality of service.
+ Added : Option to set Google Maps browser API key (Global Options > General Settings).
# [LOW] Fixed : Error sql 00000 in event edition and registration form on PDO sql server.
# [LOW] Fixed : error page not found if sh404sef extension installed.
# [LOW][THEME PACK default] Fixed : size of Date Box conteneur when a site template declares a 'box-sizing: border-box' style to all div.

* Changed files in 3.5.19
~ admin/config.xml
~ admin/utilities/customfields/customfields.php
~ admin/views/event/tmpl/edit.php
~ site/helpers/icmodel.php
~ site/models/list.php
~ [THEME PACK] site/themes/packs/default/css/default_component.css


iCagenda 3.5.18 <small style="font-weight:normal;">(2016.06.19)</small> Security Release [J! 2.5]
================================================================================
+ Added : username of the user who has submitted event in frontend (event admin edition, Publishing tab).
~ Changed : a few minor code cleaning.
# [SECURITY][MEDIUM][Joomla 2.5.x ONLY!] Fixed : possible non-persistent (or reflected) cross-site scripting vulnerability (Note: if you have a Joomla 3 website, you DON'T have this security vulnerability. If you use iCagenda 3.6.0-alpha, NO ISSUE on both Joomla versions 2.5 and 3). A reflected attack is typically delivered via email or a neutral web site. The bait is an innocent-looking URL, pointing to a trusted site but containing the XSS vector. If the trusted site is vulnerable to the vector, clicking the link can cause the victim's browser to execute the injected script (your joomla 2.5 site is not directly compromised, but your visitor could be... please, update!). Again, it's recommended for all users still using Joomla 2.5, to upgrade to Joomla 3 as soon as possible! (Joomla 2.5 is not updated and end of life since December 2014. iCagenda will stop support Joomla 2.5 after iCagenda 3.6 release.)
# [LOW] Fixed : alias generation if unicode enabled in Joomla global configuration.
# [LOW] Fixed : user name display in list of events when created_by not set.
# [LOW] Fixed : possible issue on multiple clicks on submit button (frontend form) resulting in duplicated data.
# [LOW] Fixed : ordering by username in admin events list.
# [LOW] Fixed : white page in admin control panel on pdo-mysql database.
# [LOW] Fixed : issue with some date formats in en-US language.
# [LOW] Fixed : issue with quick icon plugin on iCagenda uninstall (Joomla admin control panel white page).
# [LOW] Fixed : 'Place' name translation with Falang.
# [LOW] Fixed & improved : Image name control (space replacement) and renaming if needed, in frontend 'Submit an Event' form.
# [LOW][MODULE][PRO] Fixed : missing events if date filter is set to display only past events.

* Changed files in 3.5.18
~ admin/models/category.php
~ admin/models/event.php
~ admin/models/events.php
~ admin/models/forms/event.xml
~ admin/utilities/categories/categories.php
~ admin/utilities/events/data.php
~ admin/utilities/form/form.php
~ admin/views/event/tmpl/edit.php
~ admin/views/event/view.html.php
~ admin/views/events/tmpl/default.php
~ admin/views/icagenda/tmpl/default.php
~ admin/views/info/tmpl/default.php
~ [LIBRARY] libraries/ic_library/globalize/culture/en-US.php
~ [MODULE][PRO] modules/mod_ic_event_list/mod_ic_event_list.php
~ [PLUGIN] plugins/quickicon/icagendaupdate/icagendaupdate.php
~ site/helpers/ichelper.php
~ site/helpers/icmodel.php
~ site/models/list.php
~ site/models/submit.php
~ site/views/list/view.html.php
~ site/views/submit/tmpl/default.php


$ iCagenda 3.6.0-alpha2.1 <small style="font-weight:normal;">(2016.06.02)</small>
================================================================================
+ Added : missing files of alpha2 (Thanks to the first beta-testers report!)

* Changed files in 3.6.0-alpha2.1
~ admin/utilities/customfields/customfields.php
~ admin/utilities/event/data.php
~ admin/utilities/event/event.php


$ iCagenda 3.6.0-alpha2 <small style="font-weight:normal;">(2016.05.31)</small>
================================================================================
! New : Registration Form Refactory
1 - a full new view HTML structure, to load all fields with a maximum of flexibility
1 &nbsp; and performance (see custom field groups)
! New : Custom Field Types
1 - new field types: date (calendar), url, email (new ones will be added in beta1!)
1 - override field types: name, email, phone, date and tickets
1 &nbsp; (replace the core iCagenda fields, to allow ordering inside custom fields as you want!)
! New : Admin Custom Field List and Edition Reviewed
1 - List: useful info directly in the list of custom fields.
1 - Edition: Will show option(s) depending on selected parent form and field type
1 &nbsp; (minimum Joomla 3.2.4 to get this functionnality).
! And a lot of code improvements! ;-)
+ Added : select user in registration edition, with auto-filled name and email (depending on your global settings).
+ Added : admin filter (dropdown) to select custom fields by group.
+ Added : landing page for registration form in frontend.
~ Changed : includes all fixes and improvements of versions 3.5.14 to 3.5.17 (see releases notes for additional info).
# [J2.5][MEDIUM] Fixed (alpha1) : was broken on Joomla 2.5
# [LOW] Fixed (alpha1) : redirection on successfull registration.
# [LOW] Fixed (alpha1) : multiple words search in frontend.
# [LOW] Fixed (alpha1) : pagination issue on Chrome when search filters disabled.

* Changed files in 3.6.0-alpha2
~ admin/config.xml
~ admin/controllers/registration.php
~ admin/controllers/registrations.raw.php
~ admin/icagenda.php
~ admin/liveupdate/liveupdate.php
~ admin/models/category.php
~ admin/models/customfield.php
~ admin/models/customfields.php
~ admin/models/event.php
~ admin/models/events.php
~ admin/models/forms/customfield.xml
~ admin/models/forms/download.xml
~ admin/models/forms/event.xml
~ admin/models/forms/registration.xml
~ admin/models/mail.php
~ admin/models/registrations.php
~ admin/tables/customfield.php
~ admin/utilities/ajax/ajax.php
~ admin/utilities/categories/categories.php
~ admin/utilities/customfields/customfields.php
~ admin/utilities/event/event.php
~ admin/utilities/events/data.php
~ admin/utilities/form/field/customfieldgroups.php
+ admin/utilities/form/field/registrationdates.php
+ admin/utilities/form/field/registrationpeople.php
+ admin/utilities/form/field/registrationterms.php
~ admin/utilities/form/form.php
~ admin/utilities/list/list.php
~ admin/utilities/registration/participants.php
~ admin/utilities/registration/registration.php
~ admin/utilities/theme/joomla25.php
~ admin/utilities/theme/theme.php
~ admin/views/customfield/tmpl/edit.php
~ admin/views/customfields/tmpl/default.php
~ admin/views/customfields/view.html.php
~ admin/views/event/tmpl/edit.php
~ admin/views/event/view.html.php
~ admin/views/events/tmpl/default.php
~ admin/views/icagenda/tmpl/default.php
~ admin/views/info/tmpl/default.php
~ admin/views/registration/tmpl/edit.php
~ admin/views/registrations/tmpl/default.php
~ icagenda.xml
~ [LIBRARY] libraries/ic_library/filter/output.php
~ [LIBRARY] libraries/ic_library/form/field/sortablefields.php
~ [LIBRARY] libraries/ic_library/globalize/culture/cs-CZ.php
~ [LIBRARY] libraries/ic_library/globalize/culture/en-GB.php
~ [LIBRARY] libraries/ic_library/globalize/culture/en-US.php
~ [LIBRARY] libraries/ic_library/globalize/culture/fa-IR.php
+ [LIBRARY] libraries/ic_library/globalize/culture/fo-FO.php
~ [LIBRARY] libraries/ic_library/globalize/globalize.php
~ [LIBRARY] libraries/ic_library/lib_ic_library.xml
+ [LIBRARY] libraries/ic_library/render/render.php
~ [LIBRARY] libraries/ic_library/thumb/get.php
~ [LIBRARY] libraries/ic_library/url/url.php
~ [MEDIA] media/css/icagenda-back.css
~ [MEDIA] media/css/icagenda-front.css
~ [MEDIA] media/css/icagenda-front.j25.css
~ [MEDIA] media/js/icagenda.js
~ [MODULE][PRO] modules/mod_ic_event_list/helper.php
~ [MODULE][PRO] modules/mod_ic_event_list/mod_ic_event_list.php
~ [MODULE][PRO] modules/mod_ic_event_list/mod_ic_event_list.xml
~ [MODULE][PRO] modules/mod_ic_event_list/tmpl/default.php
~ [MODULE][PRO] modules/mod_ic_event_list/tmpl/icrounded.php
~ [MODULE] modules/mod_iccalendar/helper.php
~ [MODULE] modules/mod_iccalendar/mod_iccalendar.php
~ [MODULE] modules/mod_iccalendar/mod_iccalendar.xml
+ [PLUGIN] plugins/quickicon/icagendaupdate/icagendaupdate.php
+ [PLUGIN] plugins/quickicon/icagendaupdate/icagendaupdate.xml
+ [PLUGIN] plugins/quickicon/icagendaupdate/language/
~ [PLUGIN] plugins/system/ic_library/ic_library.php
~ script.icagenda.php
~ site/add/elements/icevent_vars.php
~ site/controllers/registration.php
~ site/icagenda.php
~ site/models/event.php
~ site/models/events.php
~ site/models/forms/registration.xml
~ site/models/forms/submit.xml
~ site/models/list.php
~ site/models/registration.php
~ site/models/submit.php
~ site/router.php
~ [THEME PACK] site/themes/packs/default/css/default_component.css
~ [THEME PACK] site/themes/packs/default/css/default_module.css
~ [THEME PACK] site/themes/packs/default/default_day.php
~ [THEME PACK] site/themes/packs/default/default_event.php
~ [THEME PACK] site/themes/packs/ic_rounded/css/ic_rounded_component.css
~ [THEME PACK] site/themes/packs/ic_rounded/css/ic_rounded_module.css
~ [THEME PACK] site/themes/packs/ic_rounded/ic_rounded_day.php
~ [THEME PACK] site/themes/packs/ic_rounded/ic_rounded_event.php
~ site/views/event/tmpl/default.php
~ site/views/event/view.html.php
~ site/views/list/tmpl/default.php
~ site/views/list/tmpl/default_categories.php
~ site/views/list/view.html.php
~ site/views/registration/tmpl/complete.php
~ site/views/registration/tmpl/default.php
~ site/views/registration/view.html.php
~ site/views/submit/tmpl/default.php


iCagenda 3.5.17 <small style="font-weight:normal;">(2016.04.12)</small>
================================================================================
+ Added : Patches and controls for Joomla 3.5.1 mail issues, returning a "0 - Invalid address: " error depending of your settings and server.
~ Changed : admin module options minor UI change, with replacement of icon image (in info message) by iCagenda font icons.
# [LIBRARY][LOW] Fixed : error if uploaded image is too big for php thumbnails generator (control was broken).
# [MODULE iC Calendar][J2.5][LOW] Fixed : All categories option select bug on Joomla 2.5
# [MODULE iC Event List][PRO][LOW] Fixed : wrong info message for admin logged-in on frontend.

* Changed files in 3.5.17
~ admin/models/mail.php
~ [LIBRARY] libraries/ic_library/thumb/get.php
~ [MODULE][PRO] modules/mod_ic_event_list/mod_ic_event_list.php
~ [MODULE] modules/mod_iccalendar/helper.php
~ [MODULE] modules/mod_iccalendar/mod_iccalendar.xml
~ site/helpers/icmodel.php
~ site/models/submit.php


iCagenda 3.5.16 <small style="font-weight:normal;">(2016.03.19)</small>
================================================================================
! New : Quick Icon plugin for update notification
1 - Checks for iCagenda updates and notifies you when you visit the Control Panel page of Joomla! administration.
! Info : iCagenda 3.5 is Joomla! 3.5 and PHP 7 READY!
+ [GLOBALIZATION] Added : fo-FO Faroese (Faroe Islands) date formats.
# [MODULE iC Calendar][LOW] Fixed : time loading when a lot of past events.
# [MODULE iC Event List][PRO][MEDIUM] Fixed : issue with number of events option (not always displaying all the events as set by this option).

* Changed files in 3.5.16
~ admin/models/events.php
~ icagenda.xml
~ [MODULE][PRO] modules/mod_ic_event_list/helper.php
~ [MODULE][PRO] modules/mod_ic_event_list/mod_ic_event_list.php
~ [MODULE] modules/mod_iccalendar/helper.php
~ script.icagenda.php
~ site/models/events.php
+ [LIBRARY] libraries/ic_library/globalize/culture/fo-FO.php
+ plugins/quickicon/icagendaupdate/icagendaupdate.php
+ plugins/quickicon/icagendaupdate/icagendaupdate.xml
+ [FOLDER] plugins/quickicon/icagendaupdate/language/


iCagenda 3.5.15 <small style="font-weight:normal;">(2016.02.19)</small>
================================================================================
# [Registrations CSV Export][LOW] Fixed : html tags in 'Notes' when this field was filled in admin registration edition.
# [Registrations CSV Export][LOW] Fixed : missing period dates (when registration for a full period) in 'date' column.
# [MODULE iC Event List][PRO][MEDIUM] Fixed : category filter was broken in 3.5.14.
# [MODULE iC Event List][PRO][LOW] Fixed : some events not displayed, depending on your settings.
# [MODULE iC Event List][PRO][LOW] Fixed : direct url to event when full period with no weekdays selected.
# [MODULE iC Calendar][LOW] Fixed : conflict on windows with Ctrl+C and the a11y shortcut keyaccess used for direct-focus on module calendar (replaced by Ctrl+Alt+C).

* Changed files in 3.5.15
~ admin/models/registrations.php
~ [MODULE][PRO] modules/mod_ic_event_list/mod_ic_event_list.php
~ [MODULE] modules/mod_iccalendar/mod_iccalendar.php


iCagenda 3.5.14 <small style="font-weight:normal;">(2016.01.23)</small>
================================================================================
+ [MODULE iC Event List][PRO] Added : option to display all dates.
+ [Registrations CSV Export] Added : option to export registration ID (disabled by default).
+ [a11y](experimental) : keyboard accessibility for navigation in the calendar module on focus.
+ [THEME PACKS] Added : additional class names for each information fields, to allow individual css styling (ic-info-email, ic-info-website,... and for custom fields, ic-info-[slug]).
~ Changed : improvement of file renaming on upload of file attachment.
~ Changed : allow to show registration options in 'Submit an Event' frontend form, even if registrations are disabled in global options, and will set to OFF by default in the form field option.
~ [THEME PACKS] Changed : review of clearfix in event details view, to allow custom CSS flexibility for floating positions.
# [LOW] Fixed : if 'Display all dates' option is set to NO, do not show an event in past events, if at least this event has one current/upcoming date.
# [LOW] Fixed : do not display time in frontend confirmation message after a successful registration, if display time option for this event is set to off.
# [LOW] Fixed : depending on your site, possibility of an invalid date format (1 January 1970) for registrations notification on a full period (from ... to ...).
# [LOW] Fixed : remove sup html tags in notification emails, if the date format includes exposant suffix for day (en-US).

* Changed files in 3.5.14
~ admin/controllers/registrations.raw.php
~ admin/models/event.php
~ admin/models/forms/download.xml
~ admin/models/registrations.php
~ admin/utilities/events/data.php
~ admin/utilities/menus/menus.php
~ [LIBRARY] libraries/ic_library/filter/output.php
~ [LIBRARY] libraries/ic_library/globalize/globalize.php
~ [LIBRARY] libraries/ic_library/thumb/get.php
~ [MODULE][PRO] modules/mod_ic_event_list/helper.php
~ [MODULE][PRO] modules/mod_ic_event_list/mod_ic_event_list.php
~ [MODULE][PRO] modules/mod_ic_event_list/mod_ic_event_list.xml
~ [MODULE] modules/mod_iccalendar/helper.php
~ [MODULE] modules/mod_iccalendar/mod_iccalendar.php
~ site/add/elements/icsetvar.php
~ site/helpers/icmodel.php
~ site/models/events.php
~ site/models/submit.php
~ [THEME PACKS] site/themes/packs/default/css/default_module.css
~ [THEME PACKS] site/themes/packs/default/default_day.php
~ [THEME PACKS] site/themes/packs/default/default_event.php
~ [THEME PACKS] site/themes/packs/ic_rounded/css/ic_rounded_module.css
~ [THEME PACKS] site/themes/packs/ic_rounded/ic_rounded_day.php
~ [THEME PACKS] site/themes/packs/ic_rounded/ic_rounded_event.php
~ site/views/list/tmpl/registration.php
~ site/views/submit/tmpl/default.php


$ iCagenda 3.6.0-alpha1 <small style="font-weight:normal;">(2015.12.28)</small>
================================================================================
! New : Frontend Search Filters
1 - Global Options: Component Options > List tab.
1 - Drag & drop filters options to change frontend ordering.
1 - Options of display (responsive, full width, show or hide 'Search' label...).
! New : Statistics
1 - Hits on events.
1 - Charts for category and event hits, displayed in the control panel of iCagenda.
! New : Custom Field Groups
1 - Create and manage groups for custom fields (directly in Custom Field edition).
1 - Set individually in each event edition (Registration tab) the groups of custom fields to use for the registration form.
! New : Module iC Event List (PRO)
1 - Option to display all dates.
~ Changed : the list view is now divided into 3 separated views: list, event and registration.
~ Changed : date drop down in registration form is auto selected on the date clicked.

* Changed files in 3.6.0-alpha1
~ admin/config.xml
~ admin/controllers/customfield.php
~ admin/icagenda.php
~ admin/models/customfield.php
~ admin/models/event.php
~ admin/models/events.php
~ admin/models/fields/modal/checkdnsrr.php
~ admin/models/fields/modal/ic_editor.php
~ admin/models/forms/customfield.xml
~ admin/models/forms/event.xml
~ admin/models/icagenda.php
~ admin/sql/install/mysql/icagenda.install.sql
~ admin/sql/uninstall/mysql/icagenda.uninstall.sql
~ admin/tables/customfield.php
~ admin/tables/event.php
+ [FOLDER] admin/utilities/addthis/
+ admin/utilities/addthis/addthis.php
+ [FOLDER] admin/utilities/addtocal/
+ admin/utilities/addtocal/addtocal.php
+ admin/utilities/ajax/filter.php
~ admin/utilities/customfields/customfields.php
+ [FOLDER] admin/utilities/event/
+ admin/utilities/event/data.php
+ admin/utilities/event/event.php
~ admin/utilities/events/data.php
~ admin/utilities/events/events.php
+ [FOLDER] admin/utilities/form/field/
+ admin/utilities/form/field/customfieldgroups.php
+ admin/utilities/form/field/customform.php
+ [FOLDER] admin/utilities/googlemaps/
+ admin/utilities/googlemaps/googlemaps.php
+ [FOLDER] admin/utilities/list/
+ admin/utilities/list/list.php
+ [FOLDER] admin/utilities/manager/
+ admin/utilities/manager/manager.php
+ [FOLDER] admin/utilities/registration/
+ admin/utilities/registration/participants.php
+ admin/utilities/registration/registration.php
+ [FOLDER] admin/utilities/render/
+ admin/utilities/render/render.php
+ admin/utilities/theme/joomla25.php
+ admin/utilities/theme/style.php
~ admin/utilities/theme/theme.php
~ admin/views/customfield/tmpl/edit.php
~ admin/views/event/tmpl/edit.php
~ admin/views/events/tmpl/default.php
~ admin/views/icagenda/tmpl/default.php
~ admin/views/icagenda/view.html.php
~ admin/views/info/tmpl/default.php
~ admin/views/registrations/tmpl/default.php
~ icagenda.xml
+ [LIBRARY][FOLDER] libraries/ic_library/form/
+ [LIBRARY][FOLDER] libraries/ic_library/form/field/
+ [LIBRARY]libraries/ic_library/form/field/sortablefields.php
+ [LIBRARY][FOLDER] libraries/ic_library/iCalcreator/
+ [LIBRARY]libraries/ic_library/iCalcreator/iCalcreator.class.php
~ [MEDIA] media/css/icagenda-back.css
~ [MEDIA] media/css/icagenda-front.css
~ [MEDIA][iCicons] media/icicons/
+ [MEDIA] media/js/Chart.js
+ [MEDIA] media/js/Chart.min.js
~ [MODULE][PRO] modules/mod_ic_event_list/helper.php
~ [MODULE][PRO] modules/mod_ic_event_list/mod_ic_event_list.php
~ [MODULE][PRO] modules/mod_ic_event_list/mod_ic_event_list.xml
~ [MODULE] modules/mod_iccalendar/helper.php
~ [MODULE] modules/mod_iccalendar/mod_iccalendar.php
~ [PLUGIN] plugins/search/icagenda/icagenda.php
~ script.icagenda.php
+ site/add/elements/icevent_vars.php
+ site/add/elements/iclist_vars.php
- site/add/elements/icsetvar.php
~ site/controller.php
+ [FOLDER] site/controllers/
+ site/controllers/registration.php
- site/helpers/iCalcreator.class.php
- site/helpers/ichelper.php
- site/helpers/icmodel.php
- site/helpers/media_css.class.php
~ site/icagenda.php
+ site/models/event.php
~ site/models/events.php
+ [FOLDER] site/models/fields/
+ site/models/fields/categories.php
+ site/models/fields/month.php
+ site/models/fields/year.php
~ site/models/forms/registration.xml
~ site/models/list.php
+ site/models/registration.php
~ site/models/submit.php
~ site/router.php
~ [THEME PACK] site/themes/packs/default/css/default_component.css
~ [THEME PACK] site/themes/packs/default/css/default_component_medium.css
~ [THEME PACK] site/themes/packs/default/css/default_component_small.css
~ [THEME PACK] site/themes/packs/default/css/default_component_xsmall.css
~ [THEME PACK] site/themes/packs/ic_rounded/css/ic_rounded_component.css
~ [THEME PACK] site/themes/packs/ic_rounded/css/ic_rounded_component_medium.css
~ [THEME PACK] site/themes/packs/ic_rounded/css/ic_rounded_component_small.css
~ [THEME PACK] site/themes/packs/ic_rounded/css/ic_rounded_component_xsmall.css
+ [FOLDER] site/views/event/
+ [FOLDER] site/views/event/tmpl/
+ site/views/event/tmpl/default.php
+ site/views/event/tmpl/default_top.php
+ site/views/event/tmpl/default_vcal.php
+ site/views/event/view.html.php
- site/views/list/tmpl/actions.php
~ site/views/list/tmpl/default.php
~ site/views/list/tmpl/default.xml
~ site/views/list/tmpl/default_categories.php
+ site/views/list/tmpl/default_filters.php
- site/views/list/tmpl/default_vcal.php
- site/views/list/tmpl/event.php
- site/views/list/tmpl/registration.php
~ site/views/list/view.feed.php
~ site/views/list/view.html.php
+ [FOLDER] site/views/registration/
+ [FOLDER] site/views/registration/tmpl/
+ site/views/registration/tmpl/actions.php
+ site/views/registration/tmpl/complete.php
+ site/views/registration/tmpl/default.php
+ site/views/registration/view.html.php
~ site/views/submit/tmpl/default.php


iCagenda 3.5.13 <small style="font-weight:normal;">(2015.12.05)</small>
================================================================================
+ Added : in registrations csv export, new option to display 'created date' (when the user registered).
# [MEDIUM] Fixed : error getimagesize if original image deleted.
# [MEDIUM] Fixed : not able to get user groups after logged-in user changed his password in frontend (return error message in component and modules).
# [LOW] Fixed : issue since Joomla 3.4.5 with upload of custom Theme Packs.
# [LOW] Fixed : issue with date and DST (Day Saving Time) with export to Outlook.
# [LOW] Fixed : private access level for event information was broken if custom fields.
# [LOW] Fixed : admin language ordering in events list.
# [LOW] Fixed : category and search list filters with no effect in registration csv export.
# [MODULE iC calendar][LOW] Fixed : Loading calendar on defined month/year not working in some cases.

* Changed files in 3.5.13
~ admin/controllers/registrations.raw.php
~ admin/models/events.php
~ admin/models/forms/download.xml
~ admin/models/registrations.php
~ admin/models/themes.php
~ admin/utilities/events/data.php
~ admin/utilities/events/events.php
~ admin/utilities/menus/menus.php
~ [LIBRARY] libraries/ic_library/thumb/get.php
~ [MODULE][PRO] modules/mod_ic_event_list/mod_ic_event_list.php
~ [MODULE] modules/mod_iccalendar/helper.php
~ [MODULE] modules/mod_iccalendar/mod_iccalendar.php
~ [PLUGIN] plugins/search/icagenda/icagenda.php
~ site/helpers/icmodel.php
~ site/models/events.php
~ site/models/submit.php
~ site/themes/packs/default/default_day.php
~ site/themes/packs/default/default_event.php
~ site/themes/packs/ic_rounded/ic_rounded_day.php
~ site/themes/packs/ic_rounded/ic_rounded_event.php
~ site/views/list/tmpl/default_vcal.php
~ site/views/list/tmpl/event.php

iCagenda 3.5.12 <small style="font-weight:normal;">(2015.10.12)</small>
================================================================================
+ Added : option field 'Time display' in 'Submit an event' form.
+ Added : global option to Select if 'Time Display' option is set by default on 'Show' or 'hide' when creating a new event ('General Settings' tab of the Global Options of the component).
+ Added : missing separator option in global options for date format.
+ [MODULE iC calendar] Added : option to set custom limit for auto-intro description.
+ [MODULE iC Event List][PRO] Added : option to set HTML filtering for auto-intro description.
~ Changed : use global date format option in registrations list, and display start and end date when registration for a period.
# [MODULE iC calendar][LOW] Fixed : display of "booking closed" when events only singles dates, and all upcoming, but registration type option for this event is set to "for all dates of the event".
# [MODULE iC calendar][LOW] Fixed : in "auto" mode for option "Link to Menu Item", the global option for filter by dates was not defined, if not on a list of events page ("auto" not working properly in this case, in a few cases, depending of your settings, if one at least of the menu item(s) to a list of events was set to use global options for Filter by dates).
# [MODULE iC calendar][LOW] Fixed : option for filtering HTML tags of intro-text not working in tooltip if not on list of events page.
# [LOW] Fixed : not displaying full address if country and/or city included in the place name.
# [LOW] Fixed : display of long content in registrations admin list (overlap in data display).

* Changed files in 3.5.12
~ admin/config.xml
~ admin/models/event.php
~ admin/models/forms/event.xml
~ admin/models/registrations.php
~ admin/utilities/events/events.php
~ admin/utilities/menus/menus.php
~ admin/views/registrations/tmpl/default.php
~ [MODULE][PRO] modules/mod_ic_event_list/helper.php
~ [MODULE][PRO] modules/mod_ic_event_list/mod_ic_event_list.php
~ [MODULE][PRO] modules/mod_ic_event_list/mod_ic_event_list.xml
~ [MODULE][PRO] modules/mod_ic_event_list/tmpl/default.php
~ [MODULE][PRO] modules/mod_ic_event_list/tmpl/icrounded.php
~ [MODULE] modules/mod_iccalendar/helper.php
~ [MODULE] modules/mod_iccalendar/mod_iccalendar.xml
~ script.icagenda.php
~ site/add/elements/icsetvar.php
~ site/helpers/icmodel.php
~ site/models/forms/submit.xml
~ site/views/submit/tmpl/default.php
~ site/views/submit/tmpl/default.xml
~ site/views/submit/view.html.php


iCagenda 3.5.11 <small style="font-weight:normal;">(2015.09.05)</small>
================================================================================
# [LOW] Fixed : date format with short month broken (in 3.5.10).
# [LOW] Fixed : date format with separator broken (since 3.5.6).
# [MODULE iC calendar][LOW] Fixed : display of current month, whereas option 'Loading on Date' is set on a day of the previous month.

* Changed files in 3.5.11
~ [LIBRARY] libraries/ic_library/globalize/culture/en-GB.php
~ [LIBRARY] libraries/ic_library/globalize/culture/en-US.php
~ [LIBRARY] libraries/ic_library/globalize/culture/fa-IR.php
~ [LIBRARY] libraries/ic_library/globalize/globalize.php
~ [MODULE] modules/mod_iccalendar/helper.php
~ [MODULE] modules/mod_iccalendar/mod_iccalendar.php
~ site/helpers/ichelper.php
~ site/helpers/icmodel.php


iCagenda 3.5.10 <small style="font-weight:normal;">(2015.09.01)</small>
================================================================================
+ Added : Compatible with Jalali/Persian calendar in admin (use of Joomla calendar for datetime picker).
+ Added : missing field to select 'Registration type' in the 'Submit an event' form, if registration options displayed.
+ Added : function to disable the submit button in frontend forms, after first click (to prevent multiple clicks during data process).
~ Changed : Asynchronous Loading of the AddThis widget script
~ Changed : auto-detect if https/ssl server for loading the AddThis widget script.
~ [MODULE iC calendar] Changed : you can now select one of the seven days of the week, as the first day of the calendar.
~ [THEME PACK] Changed : registration header is simplified, and use now its own css classes (ic-reg + suffix)
~ [THEME PACK] Changed : new ic-current-period class used to replace inline css when for the overline when period started and current (box date in list of events)
# [LOW] Fixed : a few issues with Jalali calendar in frontend (day 31 of a period not dislayed in calendar, possible datetime contruct error if no single dates in event details view).
# [LOW] Fixed : if only single dates with no period, and registration type option set to "for all dates of event", the date in registration email notifications was wrong.
# [LOW] Fixed : loading of event custom fields in registrations list if same id (missing parent_form control).
# [LOW] Fixed : date could include non-breaking space (&nbsp;) in notification email.
# [MODULE iC Event List][LOW] Fixed : display of month in the date box, on last day of a month (eg. 31 August) was next month, and not current month.

* Changed files in 3.5.10
~ admin/models/fields/modal/date.php
~ admin/models/fields/modal/enddate.php
~ admin/models/fields/modal/startdate.php
~ admin/tables/event.php
~ admin/utilities/customfields/customfields.php
~ admin/utilities/events/events.php
~ admin/utilities/form/form.php
~ admin/views/icagenda/tmpl/default.php
~ admin/views/registrations/tmpl/default.php
+ [LIBRARY] libraries/ic_library/globalize/convert.php
~ [LIBRARY] libraries/ic_library/globalize/globalize.php
+ [MEDIA] media/images/loader.gif
~ [MEDIA] media/js/icdates.js
~ [MODULE][PRO] modules/mod_ic_event_list/mod_ic_event_list.php
~ [MODULE] modules/mod_iccalendar/helper.php
~ [MODULE] modules/mod_iccalendar/mod_iccalendar.php
~ [MODULE] modules/mod_iccalendar/mod_iccalendar.xml
~ site/add/elements/icsetvar.php
~ site/helpers/icmodel.php
~ site/models/forms/submit.xml
~ site/models/submit.php
~ [THEME PACK] site/themes/packs/default/css/default_component.css
~ [THEME PACK] site/themes/packs/default/default_registration.php
~ [THEME PACK] site/themes/packs/ic_rounded/css/ic_rounded_component.css
~ [THEME PACK] site/themes/packs/ic_rounded/ic_rounded_events.php
~ [THEME PACK] site/themes/packs/ic_rounded/ic_rounded_registration.php
~ site/views/list/tmpl/registration.php
~ site/views/submit/tmpl/default.php


iCagenda 3.5.9 <small style="font-weight:normal;">(2015.08.01)</small>
================================================================================
! Added options for export in csv of a list of registrations (select info to be exported, and select between comma or semicolon as separator for values)
! You can now send a newsletter for all users registered for an event (all dates), or only for users registered to only one date (or period) of the event.
~ Changed : dropdowns for event and date in registration form, as well as for newsletter now use ajax for updating the date depending of the selected event, without reloading the page.
~ Changed : set 'All Events' as default value for 'Filter by Dates' global option (on new install).
~ [THEME PACK] Changed : ic_rounded module calendar css for table, by addition of class ic-table (minor change to prevent some possible css conflict with site template).
~ Changed : minimum joomla 3 release is now 3.2.3 (for websites using iCagenda on Joomla 3).
# [MEDIUM] Fixed : when trying to change state of a registration (broken since 3.5.7) the event state was sometimes changed in the same time (but not removed from database). Sorry for any inconvenience.
# [LOW] Fixed : change state not working in admin registrations list (not possible to trash or unpublished a registration entry).
# [LOW] Fixed : lost of changes if changing event in registration admin edition (fixed by using ajax to generate the date list).
# [MODULES][LOW] Fixed : 'Filter by dates' could be broken for modules, if set in all menus to 'Use Global', and set to 'All Events' in global options.
# [MODULE iC Event List][LOW] Fixed : notice error if no events to be displayed (undefined variable).

* Changed files in 3.5.9
~ admin/config.xml
~ admin/controllers/mail.php
~ admin/controllers/registration.php
~ admin/controllers/registrations.php
~ admin/controllers/registrations.raw.php
~ admin/models/fields/modal/evt.php
~ admin/models/fields/modal/evt_date.php
- admin/models/fields/modal/mailinglist.php
~ admin/models/forms/download.xml
~ admin/models/forms/mail.xml
~ admin/models/forms/registration.xml
~ admin/models/mail.php
~ admin/models/registration.php
~ admin/models/registrations.php
- admin/tables/mail.php
~ admin/tables/registration.php
+ admin/utilities/ajax/ajax.php
~ admin/utilities/events/events.php
~ admin/utilities/menus/menus.php
~ admin/views/mail/tmpl/edit.php
~ admin/views/mail/view.html.php
~ admin/views/registration/tmpl/edit.php
~ admin/views/registrations/tmpl/default.php
~ admin/views/registrations/view.html.php
~ [MODULE][PRO] modules/mod_ic_event_list/mod_ic_event_list.php
~ [MODULE] modules/mod_iccalendar/helper.php
~ script.icagenda.php
~ [THEME PACK] site/themes/packs/ic_rounded/css/ic_rounded_module.css


iCagenda 3.5.8 <small style="font-weight:normal;">(2015.07.17)</small>
================================================================================
# [MODULE iC Calendar][LOW] Fixed : no events displayed in the calendar if menus option 'Filter by dates' is set to 'Use Global' (Sorry for any inconvenience).

* Changed files in 3.5.8
~ admin/utilities/menus/menus.php


iCagenda 3.5.7 <small style="font-weight:normal;">(2015.07.16)</small>
================================================================================
+ Added : publishing info for registrations; Created date/by (null if registration processed before update to 3.5.7 or later) and Modified date/by.
+ [MODULES] Added: alert message with number of events not displayed, when a user with admin permissions is logged-in in frontend (see current fix in modules for events with no menu link to allow the display.).
~ Changed : end time for single dates is now assumed to be midnight, when filtering today's events.
~ [THEME PACK] Changed : remove inline css used before to display Terms of Service, and use new names for the css classes.
# [MODULES][LOW] Fixed : no display of events if no menu link allows the display.
# [MODULE iC Calendar][LOW] Fixed : possible script conflict if joomla timezone or server timezone selected (highlightToday issue).
# [MODULE iC Calendar][LOW] Fixed : no display of December events.
# [LOW] Fixed : filtering of space for dates in notification emails if mail received in plain text.
# [LOW] Fixed : possible notice 'Undefined variable: dateglobalize_#' in backend, related to date format option (depends on your admin language).
# [LOW] Fixed : wrong place of a closing div in list of events (3.5.6).
# [LOW] Fixed : edit own access for registrations (user with only edit own access permissions, will see only its own registrations in the list).
# [LOW] Fixed : JS notice empty value if Terms of Services not displayed in registration form.

* Changed files in 3.5.7
~ admin/controllers/registration.php
~ admin/controllers/registrations.php
~ admin/models/fields/iclist/globalization.php
~ admin/models/forms/customfield.xml
~ admin/models/forms/event.xml
~ admin/models/forms/registration.xml
~ admin/models/registration.php
~ admin/models/registrations.php
~ admin/sql/install/mysql/icagenda.install.sql
~ admin/utilities/events/data.php
~ admin/utilities/events/events.php
~ admin/utilities/form/form.php
~ admin/utilities/menus/menus.php
~ admin/views/event/tmpl/edit.php
~ admin/views/registration/tmpl/edit.php
~ admin/views/registrations/tmpl/default.php
~ [LIBRARY] libraries/ic_library/globalize/globalize.php
~ [MODULE][PRO] modules/mod_ic_event_list/css/default_style.css
~ [MODULE][PRO] modules/mod_ic_event_list/css/icrounded_style.css
~ [MODULE][PRO] modules/mod_ic_event_list/helper.php
~ [MODULE][PRO] modules/mod_ic_event_list/mod_ic_event_list.php
~ [MODULE] modules/mod_iccalendar/helper.php
~ [MODULE] modules/mod_iccalendar/mod_iccalendar.php
~ site/helpers/icmodel.php
~ site/models/events.php
~ site/models/submit.php
~ [THEME PACK] site/themes/packs/default/css/default_component.css
~ [THEME PACK] site/themes/packs/default/css/default_module.css
~ [THEME PACK] site/themes/packs/ic_rounded/css/ic_rounded_component.css
~ [THEME PACK] site/themes/packs/ic_rounded/css/ic_rounded_module.css
~ site/views/list/tmpl/default.php
~ site/views/list/tmpl/registration.php
~ site/views/submit/tmpl/default.php


iCagenda 3.5.6 <small style="font-weight:normal;">(2015.06.29)</small>
================================================================================
+ Added : Compatible with Jalali/Persian calendar in frontend.
~ Changed : Update of Info page (addition of credits: languages and external libraries).
~ Changed : Improvement of add to cal function for a better export to external calendars.
~ Changed : Improvement of valid dates control in frontend submit an event form.
~ Changed : migration of globalize date format function to the iC Library, and improvement.
# [MEDIUM] Fixed : delete custom fields data of an event if this one is deleted.
# [LOW] Fixed : add to cal issue when hide time selected.
# [LOW] Fixed : date/dates display in event details depending of number of dates for a period.
# [LOW] Fixed : registration button was not active when event with a past period, but upcoming single dates.
# [LOW] Fixed : line return in Notes field, when exporting list of registrations to csv.
# [LOW] Fixed : confirmed email field (registration form) was not removed from the session.
# [LOW] Fixed : no display of a few dates in today's events filtering (when period with weekdays, and event running).

* Changed files in 3.5.6
~ admin/add/ renamed admin/assets/
+ admin/assets/jcms/info.php
~ admin/config.xml
~ admin/controller.php
- [FOLDER] admin/globalization/
~ admin/icagenda.php
~ admin/models/event.php
~ admin/models/events.php
~ admin/models/fields/iclist/globalization.php
~ admin/models/fields/modal/date.php
~ admin/models/fields/modal/evt_date.php
~ admin/models/forms/event.xml
~ admin/models/registration.php
~ admin/models/registrations.php
~ admin/sql/install/mysql/icagenda.install.sql
~ admin/tables/registration.php
~ admin/utilities/customfields/customfields.php
~ admin/utilities/events/data.php
~ admin/utilities/events/events.php
+ admin/utilities/info/info.php
~ admin/views/category/tmpl/edit.php
~ admin/views/customfield/tmpl/edit.php
~ admin/views/event/tmpl/edit.php
~ admin/views/event/view.html.php
~ admin/views/events/tmpl/default.php
~ admin/views/events/view.html.php
~ admin/views/feature/tmpl/edit.php
~ admin/views/icagenda/tmpl/default.php
~ admin/views/icagenda/view.html.php
~ admin/views/info/tmpl/default.php
~ admin/views/info/view.html.php
~ admin/views/mail/tmpl/edit.php
~ admin/views/registration/tmpl/edit.php
~ admin/views/registration/view.html.php
~ admin/views/registrations/view.html.php
~ admin/views/themes/tmpl/default.php
~ icagenda.xml
~ [LIBRARY] libraries/ic_library/date/date.php
+ [LIBRARY][FOLDER] libraries/ic_library/globalize/
+ [LIBRARY] libraries/ic_library/globalize/culture/fa-IR.php
+ [LIBRARY] libraries/ic_library/globalize/globalize.php
~ [LIBRARY] libraries/ic_library/lib_ic_library.xml
~ [LIBRARY] libraries/ic_library/library/library.php
~ [LIBRARY] libraries/ic_library/string/string.php
~ [MEDIA] media/css/icagenda-back.css
~ [MEDIA] media/css/icagenda-front.css
~ [MEDIA] media/css/icagenda-front.j25.css
~ [MEDIA] media/js/icdates.js
~ [MODULE][PRO] modules/mod_ic_event_list/mod_ic_event_list.php
~ [MODULE][PRO] modules/mod_ic_event_list/mod_ic_event_list.xml
~ [MODULE] modules/mod_iccalendar/helper.php
~ [MODULE] modules/mod_iccalendar/mod_iccalendar.php
~ [MODULE] modules/mod_iccalendar/mod_iccalendar.xml
~ script.icagenda.php
~ site/helpers/ichelper.php
~ site/helpers/icmodel.php
~ site/models/events.php
~ site/models/forms/submit.xml
~ site/models/list.php
~ site/models/submit.php
~ [THEME PACK] site/themes/packs/default/css/default_component.css
~ [THEME PACK] site/themes/packs/default/css/default_module.css
~ [THEME PACK] site/themes/packs/default/default_calendar.php
~ [THEME PACK] site/themes/packs/default/default_day.php
~ [THEME PACK] site/themes/packs/default/default_event.php
~ [THEME PACK] site/themes/packs/default/default_events.php
~ [THEME PACK] site/themes/packs/default/default_registration.php
~ [THEME PACK] site/themes/packs/ic_rounded/css/ic_rounded_component.css
~ [THEME PACK] site/themes/packs/ic_rounded/css/ic_rounded_module.css
~ [THEME PACK] site/themes/packs/ic_rounded/ic_rounded_calendar.php
~ [THEME PACK] site/themes/packs/ic_rounded/ic_rounded_day.php
~ [THEME PACK] site/themes/packs/ic_rounded/ic_rounded_event.php
~ [THEME PACK] site/themes/packs/ic_rounded/ic_rounded_events.php
~ [THEME PACK] site/themes/packs/ic_rounded/ic_rounded_registration.php
~ site/views/list/tmpl/actions.php
~ site/views/list/tmpl/default.php
~ site/views/list/tmpl/default.xml
~ site/views/list/tmpl/default_vcal.php
~ site/views/list/tmpl/event.php
~ site/views/list/tmpl/registration.php
~ site/views/list/view.html.php
~ site/views/submit/tmpl/default.php
~ site/views/submit/tmpl/default.xml
~ site/views/submit/tmpl/send.php
~ site/views/submit/view.html.php


iCagenda 3.5.5 <small style="font-weight:normal;">(2015.04.27)</small>
================================================================================
~ Changed : removal of v1 and v2 release notes (link to online change log for these versions).
# [MEDIUM] Fixed : issue with admin event edition on IE (script error only on Internet Explorer).
# [LOW] Fixed : wrong file jquery.tipTip.js was included in 3.5.4. This version includes the correct new one with tooltip position fix (as announced in previous release).

* Changed files in 3.5.5
~ admin/CHANGELOG.php
~ [MEDIA] media/js/icdates.js
~ [MEDIA] media/js/jquery.tipTip.js


iCagenda 3.5.4 <small style="font-weight:normal;">(2015.04.24)</small>
================================================================================
! JComments ready : You can download the free iC JComments plugin to enable comments on events (http://icagenda.joomlic.com/resources/addons).
+ [GLOBALIZATION] Added : fa-IR Persan (Iran) date formats.
+ Added : global option to set text transformation of the event title (Global Options > General Settings tab).
+ Added : check image name in frontend 'Submit an Event form', and if file extension is missing, add the correct one.
~ Changed : location of css and js core files (removed from admin and site folder, and moved to media).
~ Changed : improve datetime picker validation (no need to click on validate button to be sure date entered is saved).
# [MEDIUM] Fixed : missing 404 error page, when SEF is enabled, and event alias in url doesn't exist (was returning the first event found).
# [MEDIUM] Fixed : Persan language issue, fixed date construct fatal error.
# [LOW] Fixed : possible iCtip position issue (add to cal, print... tooltip) if another script is changing window top offset().
# [LOW] Fixed : broken url to event details view in registration form header.
# [LOW] Fixed : broken approval icon function in admin list of events.
# [LOW] Fixed : special characters in breadcrumbs.
# [LOW] Fixed : Nb of registered user, if only one single date, and registration type is changed after a few registrations occured.

* Changed files in 3.5.4
- [FOLDER] admin/add/css/
~ admin/add/elements/desc.php
~ admin/add/elements/title.php
+ admin/add/elements/titleheader.php
~ admin/add/elements/titleimg.php
- [FOLDER] admin/add/image/
~ admin/config.xml
+ admin/globalization/fa-IR.php
~ admin/models/event.php
~ admin/models/forms/event.xml
~ admin/tables/feature.php
~ admin/utilities/customfields/customfields.php
~ admin/utilities/events/data.php
~ admin/utilities/events/events.php
~ admin/utilities/form/form.php
~ admin/views/categories/view.html.php
~ admin/views/category/tmpl/edit.php
~ admin/views/customfield/tmpl/edit.php
~ admin/views/customfields/view.html.php
~ admin/views/event/tmpl/edit.php
~ admin/views/events/tmpl/default.php
~ admin/views/events/view.html.php
~ admin/views/feature/tmpl/edit.php
~ admin/views/feature/view.html.php
~ admin/views/features/view.html.php
~ admin/views/icagenda/tmpl/default.php
~ admin/views/icagenda/view.html.php
~ admin/views/info/tmpl/default.php
~ admin/views/info/view.html.php
~ admin/views/mail/view.html.php
~ admin/views/registration/tmpl/edit.php
~ admin/views/registrations/view.html.php
~ admin/views/themes/tmpl/default.php
~ icagenda.xml
~ [LIBRARY] libraries/ic_library/date/period.php
~ [LIBRARY] libraries/ic_library/thumb/get.php
~ [MEDIA] media/css/icagenda-back.css
+ [MEDIA] media/css/icagenda-back.j25.css
~ [MEDIA] media/css/icagenda-front.css
+ [MEDIA] media/css/icagenda-front.j25.css
~ [MEDIA] media/css/icagenda.css
+ [MEDIA][FOLDER] media/css/images/
+ [MEDIA] media/css/jquery-ui-1.8.17.custom.css
+ [MEDIA] media/css/template.j25.css
~ [MEDIA][ICICONS][UPDATE] media/icicons/
~ [MEDIA][IMAGES][UPDATE] media/images/
~ [MEDIA] media/js/icdates.js
+ [MEDIA] media/js/icmap-front.js
~ [MEDIA] media/js/jquery.tipTip.js
~ [MODULE][PRO] modules/mod_ic_event_list/mod_ic_event_list.php
~ [MODULE] modules/mod_iccalendar/helper.php
~ [MODULE] modules/mod_iccalendar/mod_iccalendar.php
~ script.icagenda.pro.php
- [FOLDER] site/add/css/
~ site/add/elements/icsetvar.php
- [FOLDER] site/add/image/
~ site/controller.php
~ site/helpers/ichelper.php
~ site/helpers/icmodel.php
- [FOLDER] site/js/
~ site/models/forms/submit.xml
~ site/models/list.php
~ site/models/submit.php
~ site/router.php
~ [THEME PACK] site/themes/packs/default/css/default_component.css
~ [THEME PACK] site/themes/packs/default/default_events.php
~ [THEME PACK] site/themes/packs/default/default_registration.php
~ [THEME PACK] site/themes/packs/ic_rounded/css/ic_rounded_component.css
~ [THEME PACK] site/themes/packs/ic_rounded/ic_rounded_events.php
~ [THEME PACK] site/themes/packs/ic_rounded/ic_rounded_registration.php
+ site/views/list/tmpl/actions.php
~ site/views/list/tmpl/default.php
~ site/views/list/tmpl/default_categories.php
~ site/views/list/tmpl/event.php
~ site/views/list/tmpl/registration.php
~ site/views/list/view.html.php
~ site/views/submit/tmpl/default.php
~ site/views/submit/tmpl/send.php
~ site/views/submit/view.html.php


iCagenda 3.5.3 <small style="font-weight:normal;">(2015.03.25)</small>
================================================================================
+ Added : Confirm Email field in frontend Registration form, for not logged-in user.
+ Added : BOM utf-8 to csv export file (special characters).
~ Changed : improvement of the model for admin event edition.
~ Changed : postal code is now displayed (if available) in frontend address field.
# [MEDIUM] Fixed : saving of custom fields and features when new event (with not yet an ID) was broken (no data saved).
# [THEME PACKS][LOW] Fixed : display of empty participants list when registration not enabled.
# [LOW] Fixed : display of information details in event details view, was not always displayed depending of options and data filled.
# [LOW] Fixed : register button when only a single date, and the list display type is not set to display all dates.
# [LOW] Fixed : added back the alert message on Joomla 2.5 about the impossibility of trashing frontend submitted events if not edited (the issue with trash and empty asset_id is fixed in latest version of Joomla 3).

* Changed files in 3.5.3
~ admin/config.xml
~ admin/models/event.php
~ admin/models/fields/modal/ictext_placeholder.php
~ admin/models/forms/event.xml
~ admin/models/registrations.php
~ admin/tables/event.php
~ admin/utilities/events/data.php
~ admin/views/events/tmpl/default.php
~ admin/views/registrations/view.raw.php
~ [MODULE] modules/mod_iccalendar/helper.php
~ site/add/elements/icsetvar.php
~ site/helpers/ichelper.php
~ site/helpers/icmodel.php
~ site/models/list.php
~ [THEME PACKS] site/themes/packs/default/default_event.php
~ [THEME PACKS] site/themes/packs/ic_rounded/ic_rounded_event.php
~ site/views/list/tmpl/event.php
~ site/views/list/tmpl/registration.php
~ site/views/list/view.html.php


iCagenda 3.5.2 <small style="font-weight:normal;">(2015.03.13)</small>
================================================================================
~ Changed : name/username allows now numeric characters (server-side iCagenda validator), and addition of joomla client-side username validation.
~ Changed : minor re-ordering of period options in admin edition of an event, with info text for weekdays.
# [MEDIUM] Fixed : function to generate small icons in Features was broken.
# [LOW] Fixed : Custom fields data broken in csv export of registrations.
# [LOW] Fixed : Number of registered users, for events over a period with no weekdays selected.
# [LOW] Fixed : List of participants was broken if 'Avatar' and/or 'Username' list display option was selected (option 'Full' was working as expected).
# [LOW] Fixed : Url to event details view could return a wrong number of registered user if a period with no weekdays selected (component list of events).
# [LOW] Fixed : notice error when php function dateInterval does not exist on your server.
# [LOW] Fixed : improvement of the function to get the current layout.
# [LOW] Fixed : a few date format buggy depending of your settings, and the current language used.
# [LOW] Fixed : notice error $translator not defined in control panel (language issue) only on free version.
# [LOW] Fixed : filters display issue in registration admin list on Joomla 2.5 when event title length too high.
# [MODULE iC Event List][LOW] Fixed : blur x-small thumbs when created in admin.

* Changed files in 3.5.2
~ admin/add/css/icagenda.j25.css
~ admin/models/fields/modal/thumbs.php
~ admin/models/forms/event.xml
~ admin/models/registrations.php
~ admin/tables/feature.php
~ admin/views/event/tmpl/edit.php
~ admin/views/events/tmpl/default.php
~ admin/views/icagenda/tmpl/default.php
~ admin/views/registrations/tmpl/default.php
~ [LIBRARY] libraries/ic_library/date/period.php
~ [LIBRARY] libraries/ic_library/thumb/get.php
~ [MODULE][PRO] modules/mod_ic_event_list/tmpl/icrounded.php
~ site/add/elements/icsetvar.php
~ site/helpers/ichelper.php
~ site/helpers/icmodel.php
~ site/models/forms/submit.xml
~ site/models/list.php
~ [THEME PACKS] site/themes/packs/default/css/default_component.css
~ [THEME PACKS] site/themes/packs/ic_rounded/css/ic_rounded_component.css
~ site/views/list/tmpl/registration.php
~ site/views/list/view.html.php
~ site/views/submit/tmpl/default.php
~ site/views/submit/view.html.php


iCagenda 3.5.1 <small style="font-weight:normal;">(2015.03.01)</small>
================================================================================
~ [MODULE iC calendar] Cleaned : not needed data attributs in arrows navigation.
# [MEDIUM] Fixed : no display of events if access registered, and user logged-in is not a Super User.
# [LOW] Fixed : possible issue with Joomla 3.4.0 (not saving event due to a script conflict), if admin module 'Multilanguage status' published (change of the modal for this module, using now Bootstrap).
# [LOW] Fixed : minor error issue in script used in edit form (admin) for link option on register button.
# [LOW] Fixed : 'No tickets are available for this date' displayed if no registration done for an event.
# [LOW] Fixed : incorrect count of available and booked tickets when Registration Type is set to 'all dates of the period'.
# [LOW] Fixed : link to view event after registration, not linking to registered date event view.

* Changed files in 3.5.1
~ admin/models/event.php
~ admin/models/fields/modal/iclink_article.php
~ admin/models/fields/modal/iclink_type.php
~ admin/models/fields/modal/iclink_url.php
~ admin/utilities/events/data.php
~ admin/utilities/form/form.php
~ admin/views/event/tmpl/edit.php
~ admin/views/events/view.html.php
~ [MODULE] modules/mod_iccalendar/helper.php
~ site/add/elements/icsetvar.php
~ site/helpers/icmodel.php
~ site/models/events.php
~ site/views/list/tmpl/registration.php


iCagenda 3.5.0 <small style="font-weight:normal;">(2015.02.25)</small>
================================================================================
! [EXPORT CSV][Registrations] : integration of CSV exportation for a list of registrations. Use the filter dropdowns to select state, event and/or date, and export the list of registered users by clicking on the 'Export' button in the toolbar.
! [Registrations] : the max number of tickets is now applied to each individual date of an event, if registration per date is selected.
! [MODULES] Added : event url goes directly to the date selected in the event details view.
! [FORMS] Improvement in Form Validation. By default, the form validation is process first client-side, using now the joomla core form-validate, and in second validation is server-side, processed by iCagenda. You have option both for the 'Registration' and 'Submit an Event' forms, to select default (2 controls) or only server-side form validation (the most advanced and secured one. The client-side validation adds a more user-friendly way which is faster for user (page not reloaded) to know when a field or more are invalid).
+ Added : Admin filter by registered date in registrations list.
+ Added : Admin filter by category in registrations list.
+ [RSS] Added : get current menu options to filter the RSS feeds (Filter by date, ordering...).
+ [MODULE iC calendar] Added : option to close automatically the tooltip on Mouseout.
+ [PLUGIN Search] Added : Search in shortdesc and metadesc text.
~ [MODULE iC calendar] Changed : improvement of the tool tip design, and addition of auto vertical scrolling inside tooltip.
~ [MODULE iC calendar] Changed : All mktime php function changed to be standardized with component refactory.
~ Many code improvements and minor bugs fixed.
# [MEDIUM] Fixed : Possible blank page in frontend, or very slow loading of iCagenda. The issue was not identified (seems to be related to php 5.4.37), but the new release 3.5.0 fixes this problem.
# [MEDIUM] Fixed : Slow loading in frontend, when using distant images, the parent image to generate thumbnails was always controlled, and should not if thumb already existed.
# [LOW] Fixed : W3C validation.
# [LOW] Fixed : issue in checking menu item if published (could return a 404 error page if menu item not published).
# [LOW] Fixed : missing displaytime checking in list of dates rendering (could not display an event over a period, with week days selected, if time not set).
# [LOW] Fixed : when only single dates filled in 'Submit an Event' form, the event was unpublished.
# [LOW] Fixed : "Notice: Undefined index:" if some fields are not filled when captcha solution was incorrect in registration form.
# [LOW] Fixed : issue if captcha plugin option is not set correctly, and set to be shown in form options.
# [LOW] Fixed : do not display event not approved in RSS feeds.
# [LOW] Fixed : no display of toolbar in list of events (admin) if no category created (display issue hiding page header).
# [LOW] Fixed : infotips in registration form not working on Joomla 2.5 (bug introduced in 3.4.1).
# [LOW][PRO MODULE iC Event List] Fixed : wrong date if period has a start date before today, and end date after today (was displaying tomorrow).
# [LOW][PRO MODULE iC Event List] Fixed : missing ic- prefix for columns classes in default layout, and rtl files.
# [SQL] Fixed : possible issue when update from an old version of iCagenda (before 3.2.14 and 3.2.0), with sql updating using the joomla core sql updates system.

* Changed files in 3.5.0
- admin/add/css/icmap.css
~ admin/config.xml
+ admin/controllers/registrations.raw.php
~ admin/globalization/en-GB.php
+ admin/models/download.php
~ admin/models/events.php
~ admin/models/fields/icmap/city.php
~ admin/models/fields/icmap/country.php
~ admin/models/fields/icmap/lat.php
~ admin/models/fields/icmap/lng.php
~ admin/models/fields/modal/date.php
~ admin/models/fields/modal/ictextarea_counter.php
~ admin/models/fields/modal/thumbs.php
+ admin/models/forms/download.xml
~ admin/models/forms/event.xml
~ admin/models/registrations.php
~ admin/sql/updates/3.2.0.sql
~ admin/sql/updates/3.2.14.sql
~ admin/sql/updates/3.2.sql
~ admin/utilities/customfields/customfields.php
+ admin/utilities/events/data.php
~ admin/utilities/events/events.php
~ admin/utilities/form/form.php
~ admin/utilities/menus/menus.php
~ admin/utilities/thumb/thumb.php
~ admin/views/categories/view.html.php
+ admin/views/download/tmpl/default.php
+ admin/views/download/view.html.php
~ admin/views/event/tmpl/edit.php
~ admin/views/event/view.html.php
~ admin/views/events/tmpl/default.php
~ admin/views/events/view.html.php
~ admin/views/registrations/tmpl/default.php
~ admin/views/registrations/view.html.php
+ admin/views/registrations/view.raw.php
~ [LIBRARY] libraries/ic_library/date/date.php
~ [LIBRARY] libraries/ic_library/thumb/create.php
~ [LIBRARY] libraries/ic_library/thumb/get.php
~ [MEDIA] media/css/icagenda-back.css
~ [MEDIA] media/css/icagenda-front.css
+ [MEDIA] media/css/icagenda.css
~ [MEDIA] media/icicons/style.css
~ [MEDIA] media/js/icdates.js
~ [MEDIA] media/js/icform.js
~ [MODULE][PRO] modules/mod_ic_event_list/css/default_style-rtl.css
~ [MODULE][PRO] modules/mod_ic_event_list/css/default_style.css
~ [MODULE][PRO] modules/mod_ic_event_list/css/icrounded_style-rtl.css
~ [MODULE][PRO] modules/mod_ic_event_list/css/icrounded_style.css
~ [MODULE][PRO] modules/mod_ic_event_list/mod_ic_event_list.php
~ [MODULE][PRO] modules/mod_ic_event_list/tmpl/default.php
~ [MODULE] modules/mod_iccalendar/helper.php
~ [MODULE] modules/mod_iccalendar/mod_iccalendar.php
~ [MODULE] modules/mod_iccalendar/mod_iccalendar.xml
~ [MODULE] modules/mod_iccalendar/mod_iccalendar.xml
~ [PLUGIN] plugins/search/icagenda/icagenda.php
~ script.icagenda.php
- site/add/css/icmap.css
~ site/add/elements/icsetvar.php
~ site/helpers/ichelper.php
~ site/helpers/icmodel.php
~ site/models/events.php
~ site/models/forms/submit.xml
~ site/models/list.php
~ site/models/submit.php
~ [THEME PACKS] site/themes/packs/default/css/default_component.css
~ [THEME PACKS] site/themes/packs/default/css/default_component_xsmall.css
~ [THEME PACKS] site/themes/packs/default/css/default_module.css
~ [THEME PACKS] site/themes/packs/default/default_day.php
~ [THEME PACKS] site/themes/packs/default/default_event.php
~ [THEME PACKS] site/themes/packs/default/default_events.php
~ [THEME PACKS] site/themes/packs/ic_rounded/css/ic_rounded_component.css
~ [THEME PACKS] site/themes/packs/ic_rounded/css/ic_rounded_component_xsmall.css
~ [THEME PACKS] site/themes/packs/ic_rounded/css/ic_rounded_module.css
~ [THEME PACKS] site/themes/packs/ic_rounded/ic_rounded_day.php
~ [THEME PACKS] site/themes/packs/ic_rounded/ic_rounded_event.php
~ [THEME PACKS] site/themes/packs/ic_rounded/ic_rounded_events.php
~ site/views/list/tmpl/default.php
~ site/views/list/tmpl/default_categories.php
~ site/views/list/tmpl/event.php
~ site/views/list/tmpl/registration.php
~ site/views/submit/tmpl/default.php
~ site/views/submit/view.html.php

iCagenda 3.4.1 <small style="font-weight:normal;">(2015.01.30)</small>
================================================================================
! Changed : To fix an issue when using a custom captcha plugin (not joomla core reCaptcha plugin), the options has been changed. Now, there's only one place where you can set the captcha plugin used in iCagenda: 'General Settings' tab of the Global Options of the component. And you have individual option to show/hide captcha in 'Registration' and 'Submit an Event' forms. The update script will try to migrate your settings, but it's possible that you will have to set again this option in the menu options of 'Submit en Event' menu item type.
! Fixed : the 404 error page on multi-language site (when clicking on a module link).
+ Added : Get menu id and title of an event submitted in frontend (notification email, and filter in admin list of events).
+ Added : Options to show/hide period, weekdays and single dates in 'Submit an Event' form.
+ Added : Filter RSS feeds by category filter set in the menu options.
+ Added : Tooltip legends to pagination.
+ Added : Option to show/hide time in date box (list of events).
+ Added : Global Option to set access level to registration form.
+ [Plugin Search] Added : Next date added in search result (after title of the event).
~ Changed : You can now enter date before 1970/1/1 and after 2038/1/19 (no more unix limitation due to mktime php function, removed from date functions).
~ Changed : pageclass_sfx moved from id icagenda to a class (ic-list, ic-event, ic-registration, ic-submit, ic-send) to follow joomla standard.
~ Changed : Google Maps script checking (if api not loaded, iCagenda will load it).
~ Changed : Main list of events filter by date is improved (full recoding of the dates filtering functions).
~ Changed : The option 'list of all dates/only next/last date' is changed into 'Display All Dates' yes/no option.
~ [PRO MODULE iC Event List] Changed : ic- prefix added to section, group and col class names (to prevent class names CSS conflict).
~ Changed : Many code improvements.
# [MEDIUM] Fixed : 'auto' mode for menu link in modules was not well filtering language when joomla multi-language enabled. Improvement of the language detection for the menu items to retrieve the correct url.
# [LOW] Fixed : do not send user notification email after an event submission in frontend, if user has permissions to approve an event.
# [LOW] Fixed : no thumbnails were generated when '.' found in the image filename (eg. image.name.jpg).
# [LOW] Fixed : detects if an image file is too large, depending of server memory_limit setting, to prevent a blank page in admin when thumbnails cannot be generated (alert message displayed when a file is too large).
# [LOW] Fixed : filtering by category in events admin list was broken.
# [LOW] Fixed : Minor warning message in admin 'Themes manager' page (don't worry, nothing is broken!), 'Error loading component: COM_ICAGENDA, Component not found'.
# [LOW] Fixed : a few minor issue in admin list of events (date in current language, notice error $list var, ...).
# [LOW] Fixed : no display of events if category is unpublished.
# [LOW] Fixed : Keep in session Terms and Conditions checked, when reCaptcha is not correct.
# [LOW] Fixed : alias not generated when latin and non-latin characters in title (no datetime url safe alias, depending of unicode slug joomla global config setting).
# [LOW] Fixed : wrong display of menu option 'Features' on Joomla 2.5.
# [LOW] Fixed : if click on cancel on registration form, and when back to event details view, the back arrow was returning to registration form (now returns to parent list of events).

* Changed files in 3.4.1
~ admin/add/css/jquery-ui-1.8.17.custom.css
~ admin/config.xml
~ admin/globalization/uk-UA.php
~ admin/models/events.php
~ admin/models/fields/modal/cat.php
~ admin/models/fields/modal/date.php
~ admin/models/fields/modal/enddate.php
~ admin/models/fields/modal/ictextarea_counter.php
~ admin/models/fields/modal/startdate.php
~ admin/models/fields/modal/template.php
~ admin/models/forms/event.xml
~ admin/tables/event.php
~ admin/utilities/events/events.php
~ admin/utilities/form/form.php
~ admin/utilities/menus/menus.php
~ admin/views/event/tmpl/edit.php
~ admin/views/event/view.html.php
~ admin/views/events/tmpl/default.php
~ admin/views/events/view.html.php
~ admin/views/themes/tmpl/default.php
~ admin/views/themes/view.html.php
~ [LIBRARY] libraries/ic_library/date/date.php
~ [LIBRARY] libraries/ic_library/date/period.php
~ [LIBRARY] libraries/ic_library/thumb/create.php
~ [LIBRARY] libraries/ic_library/thumb/get.php
~ [MEDIA] media/css/icagenda-front.css
~ [MEDIA] media/js/icdates.js
~ [MODULE][PRO] modules/mod_ic_event_list/css/default_style.css
~ [MODULE][PRO] modules/mod_ic_event_list/css/icrounded_style.css
~ [MODULE][PRO] modules/mod_ic_event_list/helper.php
~ [MODULE][PRO] modules/mod_ic_event_list/mod_ic_event_list.php
~ [MODULE][PRO] modules/mod_ic_event_list/mod_ic_event_list.xml
~ [MODULE][PRO] modules/mod_ic_event_list/tmpl/default.php
~ [MODULE][PRO] modules/mod_ic_event_list/tmpl/icrounded.php
~ [MODULE] modules/mod_iccalendar/helper.php
~ [PLUGIN] plugins/search/icagenda/icagenda.php
~ script.icagenda.php
~ site/add/css/jquery-ui-1.8.17.custom.css
~ site/helpers/ichelper.php
~ site/helpers/icmodel.php
~ site/models/events.php
+ site/models/forms/registration.xml
~ site/models/forms/submit.xml
~ site/models/list.php
~ site/models/submit.php
~ [THEME PACKS] site/themes/packs/default/css/default_component.css
~ [THEME PACKS] site/themes/packs/default/default_event.php
~ [THEME PACKS] site/themes/packs/default/default_events.php
~ [THEME PACKS] site/themes/packs/default/default_registration.php
~ [THEME PACKS] site/themes/packs/ic_rounded/css/ic_rounded_component.css
~ [THEME PACKS] site/themes/packs/ic_rounded/css/ic_rounded_module.css
~ [THEME PACKS] site/themes/packs/ic_rounded/ic_rounded_day.php
~ [THEME PACKS] site/themes/packs/ic_rounded/ic_rounded_event.php
~ [THEME PACKS] site/themes/packs/ic_rounded/ic_rounded_events.php
~ [THEME PACKS] site/themes/packs/ic_rounded/ic_rounded_registration.php
~ site/views/list/tmpl/default.php
~ site/views/list/tmpl/default.xml
+ site/views/list/tmpl/default_categories.php
~ site/views/list/tmpl/default_vcal.php
~ site/views/list/tmpl/event.php
~ site/views/list/tmpl/registration.php
~ site/views/list/view.feed.php
~ site/views/list/view.html.php
~ site/views/submit/tmpl/default.php
~ site/views/submit/tmpl/default.xml
~ site/views/submit/tmpl/send.php
~ site/views/submit/view.html.php


iCagenda 3.4.0 <small style="font-weight:normal;">(2014.12.22)</small>
================================================================================
! New : Custom fields.
1 - Available in registration and event edition forms.
1 - Field types : text, list, radio buttons.
! New : Feature Icons.
1 - Create icons for each feature.
1 - Attribute one or more features individually for each event.
1 - Feature can be for example: Parking, Refreshments, Restaurant, Hotel, Free, TV, Toilets, Swimming, Airport... (no limit of usage!).
! New : Librairies
1 - iC Library : standalone library (loaded by a plugin).
1 - iCagenda Utilities : integrated library of iCagenda.
! New : Full Thumbnails generator
1 - Options for 4 predetermined sizes : large, medium, small, xsmall.
1 - For each thumbnail size, individual options : width, height, quality, crop.
! Improvement and new options:
1 - Captcha option added in 'Registration' and 'Submit an event' forms.
1 - RTL integration (component and modules)
1 - SQL requests improvement (faster process of database queries)
1 - Link to event details from modules and search plugin now detect the category filter setting from each menu items.
1 - Notification email to user who has submitted an event in frontend, with an Event Reference Number.
1 - ...
! Please check all release notes since 3.4.0-alpha1 to review all the changes and new options added since 3.3.8

* Release Notes 3.4.0
~ Changed : 'btn' class renamed in 'ic-btn' for frontend (mainly used for buttons).
~ Changed : a few css improvement (ic_rounded theme, liveupdate design...), and new classes added for a few core functions (date time display...).
# [LOW] Fixed : minor issues with 3.4.0-rc.

* Changed files in 3.4.0
~ admin/config.xml
~ admin/icagenda.php
~ admin/liveupdate/assets/liveupdate.css
~ admin/liveupdate/classes/abstractconfig.php
~ admin/liveupdate/classes/tmpl/nagscreen.php
~ admin/liveupdate/classes/tmpl/overview.php
~ admin/liveupdate/classes/updatefetch.php
~ admin/utilities/customfields/customfields.php
~ admin/utilities/events/events.php
+ admin/utilities/params/params.php
~ admin/views/icagenda/tmpl/default.php
~ admin/views/info/tmpl/default.php
~ [LIBRARY] libraries/ic_library/date/date.php
+ [LIBRARY] libraries/ic_library/date/period.php
~ [MEDIA] media/css/icagenda-front.css
+ [MEDIA] media/js/icagenda.js
~ [MODULE][PRO] modules/mod_ic_event_list/helper.php
~ [MODULE][PRO] modules/mod_ic_event_list/mod_ic_event_list.php
~ [MODULE] modules/mod_iccalendar/helper.php
~ [MODULE] modules/mod_iccalendar/mod_iccalendar.php
~ [PLUGIN] plugins/system/ic_library/ic_library.php
~ script.icagenda.php
~ site/helpers/ichelper.php
~ site/helpers/icmodel.php
~ site/helpers/media_css.class.php
~ site/models/events.php
~ [THEME PACKS] site/themes/packs/default/css/default_component.css
~ [THEME PACKS] site/themes/packs/default/css/default_component_xsmall.css
~ [THEME PACKS] site/themes/packs/default/default_event.php
~ [THEME PACKS] site/themes/packs/ic_rounded/css/ic_rounded_component.css
~ [THEME PACKS] site/themes/packs/ic_rounded/css/ic_rounded_component_medium.css
~ [THEME PACKS] site/themes/packs/ic_rounded/css/ic_rounded_component_small.css
~ [THEME PACKS] site/themes/packs/ic_rounded/css/ic_rounded_component_xsmall.css
~ [THEME PACKS] site/themes/packs/ic_rounded/ic_rounded_event.php
~ site/views/list/tmpl/default.php
~ site/views/list/tmpl/event.php
~ site/views/list/tmpl/registration.php
~ site/views/list/view.html.php
~ site/views/submit/tmpl/default.php
~ site/views/submit/tmpl/send.php
~ site/views/submit/view.html.php


$ iCagenda 3.4.0-rc <small style="font-weight:normal;">(2014.12.14)</small>
================================================================================
! RTL integration (component and modules)
! SQL requests improvement (faster process of database queries)
! [MODULES & PLUGIN] Link to event details from modules and search plugin now detect the category filter setting from each menu items.
! Notification email to user who has submitted an event in frontend, with an Event Reference Number (of type YYYYMMDDID where YYYY is year, MM is month, DD is day and ID is event id).
+ Added : form fields saved to session to keep data after submission of the form if a wrong captcha value was entered.
+ Added : nofollow for 'registration' and 'submit an event' form links (to not been read by search engine).
+ Added : option to set ordering of categories in drop-down field.
+ Added : option to set a category as default in drop-down field.
~ Changed : auto-generation of alias improved.
~ Changed : default order of categories in drop-down field by title (previously by id).
# [LOW] Fixed : issue with single date before 1999-11-30.
# [LOW] Fixed : tooltip not working in 'Submit an Event' form on Joomla 3.3.6 (fixed since alpha-1).
# [LOW] Fixed : pixelated event image in details view, if original image is too small.
# [LOW] Fixed : notice error 'DS' in admin and frontend after Joomla upgrade from 2.5 to 3.3.
# [LOW] Fixed : text counter bug in frontend 'Submit an Event' form on IE11.

* Changed files in 3.4.0-rc
~ admin/config.xml
~ admin/icagenda.php
~ admin/models/category.php
~ admin/models/event.php
~ admin/models/events.php
~ admin/models/feature.php
~ admin/models/fields/icmap/city.php
~ admin/models/fields/icmap/country.php
~ admin/models/fields/icmap/lat.php
~ admin/models/fields/icmap/lng.php
~ admin/models/fields/modal/cat.php
~ admin/models/fields/modal/date.php
~ admin/models/fields/modal/iclink_type.php
~ admin/models/fields/modal/ictextarea_counter.php
~ admin/models/fields/modal/multicat.php
~ admin/models/forms/feature.xml
~ admin/tables/category.php
~ admin/tables/customfield.php
~ admin/tables/event.php
~ admin/tables/feature.php
~ admin/tables/registration.php
~ admin/utilities/customfields/customfields.php
~ admin/utilities/events/events.php
~ admin/utilities/form/form.php
+ admin/utilities/menus/menus.php
~ admin/utilities/thumb/thumb.php
~ libraries/ic_library/filter/output.php
~ libraries/ic_library/url/url.php
~ media/css/icagenda-front.css
~ media/js/icform.js
+ [MODULE][PRO] modules/mod_ic_event_list/css/default_style-rtl.css
~ [MODULE][PRO] modules/mod_ic_event_list/css/default_style.css
+ [MODULE][PRO] modules/mod_ic_event_list/css/icrounded_style-rtl.css
~ [MODULE][PRO] modules/mod_ic_event_list/css/icrounded_style.css
~ [MODULE][PRO] modules/mod_ic_event_list/helper.php
~ [MODULE][PRO] modules/mod_ic_event_list/mod_ic_event_list.php
~ [MODULE][PRO] modules/mod_ic_event_list/mod_ic_event_list.xml
~ [MODULE][PRO] modules/mod_ic_event_list/tmpl/default.php
~ [MODULE][PRO] modules/mod_ic_event_list/tmpl/icrounded.php
~ [MODULE] modules/mod_iccalendar/helper.php
~ [MODULE] modules/mod_iccalendar/mod_iccalendar.php
~ site/add/css/style.css
~ site/add/elements/icsetvar.php
~ site/helpers/ichelper.php
~ site/helpers/icmodel.php
~ site/icagenda.php
+ site/models/events.php
~ site/models/forms/submit.xml
~ site/models/list.php
~ site/models/submit.php
+ [THEME PACKS] site/themes/packs/default/css/default_component-rtl.css
~ [THEME PACKS] site/themes/packs/default/css/default_component.css
+ [THEME PACKS] site/themes/packs/default/css/default_module-rtl.css
~ [THEME PACKS] site/themes/packs/default/default_day.php
~ [THEME PACKS] site/themes/packs/default/default_registration.php
+ [THEME PACKS] site/themes/packs/ic_rounded/css/ic_rounded_component-rtl.css
~ [THEME PACKS] site/themes/packs/ic_rounded/css/ic_rounded_component.css
+ [THEME PACKS] site/themes/packs/ic_rounded/css/ic_rounded_module-rtl.css
~ [THEME PACKS] site/themes/packs/ic_rounded/ic_rounded_day.php
~ [THEME PACKS] site/themes/packs/ic_rounded/ic_rounded_event.php
~ [THEME PACKS] site/themes/packs/ic_rounded/ic_rounded_events.php
~ [THEME PACKS] site/themes/packs/ic_rounded/ic_rounded_registration.php
~ site/views/list/tmpl/default.php
~ site/views/list/tmpl/default.xml
~ site/views/list/tmpl/event.php
~ site/views/list/tmpl/registration.php
~ site/views/list/view.html.php
~ site/views/submit/tmpl/default.php
~ site/views/submit/tmpl/default.xml
~ site/views/submit/tmpl/send.php
~ site/views/submit/view.html.php


$ iCagenda 3.4.0-beta2 <small style="font-weight:normal;">(2014.11.09)</small>
================================================================================
+ Captcha option added in 'Registration' and 'Submit an event' forms. You can select the joomla captcha plugin that will be used in the form.
+ Added : Custom fields filled added in the registration notification emails.
+ Added : 3 tags in registration notification emails : [CUSTOMFIELDS] (list of custom fields), [DATE] (only date) and [TIME] (only time).
+ Added : Option to redirect after validation of the frontend 'Submit an Event' form (default, article or url).
+ Added : Option to set a characters limit for Title in List of Events.
+ Added : Option to create custom CSS stylesheets to add to the iCagenda styles or to override existing CSS styles and classes (Global Options).
+ [PRO MODULE iC Event List] Added : Options to set a header and/or footer custom text.
+ [MODULE iC Calendar] Added : Option to select the date on which the calendar will load (month and year).
+ [MODULE iC Calendar] Added : Option to show/hide Month and/or Year navigation.
~ Changed : display of "LiveUpdate" button only to user with component global options permissions.
~ [MODULE iC Calendar] Changed : navigation routing improved in calendar (now compatible with Advanced Module Manager by NoNumber).
~ [Theme Packs] Changed : load animated png is replaced by a animated gif (to prevent not working on not compatible browsers).
# [LOW] Fixed : 'view event' redirect link, after registration submission.
# [LOW] Fixed : nofollow for 'print' and 'add to cal' icons links.
# [LOW] Fixed : issue with custom field type 'list' if set to 'required'. Field was not checked properly if 'alias' and 'slug' identical.
# [LOW] Fixed : Add to iCal if SEF not activated (wrong url).
# [LOW] Fixed : displays users registered depending on the date (when 'All dates of each event' option is selected in menu options).
# [LOW] Fixed : possibility to edit or removed a registered user when the event is not published.
# [LOW] Fixed : changed 'all period' to 'all dates' in registration option, and fix an issue in data saved when no period for an event.
# [LOW] Fixed : possible issues with "edit own" permission for event edition.
# [LOW] Fixed : auto-increment of image name in frontend submit an event form, if image name already exists.
# [LOW] Fixed : error when searching in event with special characters (ą ę ć ś ź ł ż ó ż ń).
# [PRO MODULE iC Event List] [LOW] Fixed : wrong date depending of the time zone (only if datetime or date display is selected).

* Changed files in 3.4.0-beta2
~ admin/config.xml
~ admin/models/events.php
~ admin/models/fields/modal/evt.php
~ admin/models/fields/modal/evt_date.php
~ admin/models/fields/modal/iclink_type.php
~ admin/models/fields/modal/thumbs.php
~ admin/models/forms/event.xml
~ admin/models/forms/registration.xml
~ admin/utilities/customfields/customfields.php
+ admin/utilities/events/events.php
~ admin/utilities/form/form.php
~ admin/views/events/tmpl/default.php
~ admin/views/events/view.html.php
~ admin/views/icagenda/tmpl/default.php
~ admin/views/registrations/tmpl/default.php
+ libraries/ic_library/date/date.php
~ libraries/ic_library/lib_ic_library.xml
~ libraries/ic_library/thumb/create.php
~ libraries/ic_library/url/url.php
~ [MODULE][PRO] modules/mod_ic_event_list/mod_ic_event_list.php
~ [MODULE][PRO] modules/mod_ic_event_list/mod_ic_event_list.xml
~ [MODULE][PRO] modules/mod_ic_event_list/tmpl/default.php
~ [MODULE][PRO] modules/mod_ic_event_list/tmpl/icrounded.php
~ [MODULE] modules/mod_iccalendar/helper.php
~ [MODULE] modules/mod_iccalendar/mod_iccalendar.php
~ [MODULE] modules/mod_iccalendar/mod_iccalendar.xml
~ [PLUGIN] plugins/system/ic_library/ic_library.php
~ site/add/elements/icsetvar.php
~ site/helpers/iCicons.class.php
~ site/helpers/icmodel.php
~ site/helpers/media_css.class.php
~ site/models/list.php
~ site/models/submit.php
~ [THEME PACKS] site/themes/packs/default/css/default_component.css
~ [THEME PACKS] site/themes/packs/default/css/default_module.css
+ [THEME PACKS] site/themes/packs/default/images/ic_load.gif
- [THEME PACKS] site/themes/packs/default/images/ic_load.png
~ [THEME PACKS] site/themes/packs/ic_rounded/css/ic_rounded_component.css
~ [THEME PACKS] site/themes/packs/ic_rounded/css/ic_rounded_module.css
~ [THEME PACKS] site/themes/packs/ic_rounded/ic_rounded_event.php
+ [THEME PACKS] site/themes/packs/ic_rounded/images/ic_load.gif
- [THEME PACKS] site/themes/packs/ic_rounded/images/ic_load.png
~ site/views/list/tmpl/default.php
~ site/views/list/tmpl/default_vcal.php
~ site/views/list/tmpl/event.php
~ site/views/list/tmpl/registration.php
~ site/views/list/view.html.php
~ site/views/submit/tmpl/default.php
~ site/views/submit/tmpl/default.xml
~ site/views/submit/view.html.php


$ iCagenda 3.4.0-beta1 <small style="font-weight:normal;">(2014.07.23)</small>
================================================================================
+ Added : Short Description field (you can now enter a special short description, to be used in the list of events as Intro Text).
+ Added : Limit options for Short Description, Auto-Introtext and Meta Description. Addition of a live counter of remaining characters both in admin event edit and submit an event forms.
+ Added : Option for maximum size of the uploaded image in frontend 'submit an event' form. This new function controls the file before upload, check the size and file type, and display a preview if the file is conformed.
+ Added : image added to rss feeds
+ [SQL] Added : 'shortdesc' in '#__icagenda_events' table
~ Changed : 'Meta' is replaced by 'Auto-Introtext' in Intro Text option (global component and modules options).
~ [THEME PACKS] Changed : Begin of renaming of existing CSS classes of ic_rounded theme pack (to use standardized naming, and prevent CSS conflicts with site templates and other third party extensions. Don't forget to update your custom theme pack if needed!)
~ Changed : a few code improvements, and control alert messages added.
# [LOW] Fixed : possible issue on a fresh install, with a wrong installation of the iC Library.
# [LOW] Fixed : wrong display in frontend of radio buttons, when using a Gantry Template.

* Changed files in 3.4.0-beta1
~ admin/add/css/icagenda.j25.css
~ admin/config.xml
+ admin/models/fields/modal/ictextarea_counter.php
~ admin/models/forms/event.xml
~ admin/views/event/tmpl/edit.php
~ admin/views/event/view.html.php
~ admin/views/icagenda/tmpl/color.php
~ icagenda.xml
~ libraries/ic_library/lib_ic_library.xml
~ media/css/icagenda-back.css
~ media/css/icagenda-front.css
+ media/js/icform.js
~ [MODULE][PRO] modules/mod_ic_event_list/css/default_style.css
~ [MODULE][PRO] modules/mod_ic_event_list/mod_ic_event_list.php
~ [MODULE][PRO] modules/mod_ic_event_list/mod_ic_event_list.xml
~ [MODULE] modules/mod_iccalendar/helper.php
~ [MODULE] modules/mod_iccalendar/mod_iccalendar.xml
~ script.icagenda.pro.php
~ site/add/css/icagenda.j25.css
~ site/add/elements/icsetvar.php
~ site/helpers/icmodel.php
~ site/icagenda.php
~ site/models/forms/submit.xml
~ site/models/list.php
~ site/models/submit.php
~ [THEME PACKS] site/themes/packs/default/css/default_component.css
~ [THEME PACKS] site/themes/packs/default/default_event.php
~ [THEME PACKS] site/themes/packs/ic_rounded/css/ic_rounded_component.css
~ [THEME PACKS] site/themes/packs/ic_rounded/ic_rounded_event.php
~ site/views/list/tmpl/default.php
~ site/views/list/tmpl/default.xml
~ site/views/list/view.html.php
~ site/views/submit/tmpl/default.php
~ site/views/submit/tmpl/default.xml
~ site/views/submit/view.html.php


$ iCagenda 3.4.0-alpha2 <small style="font-weight:normal;">(2014.07.16)</small>
================================================================================
+ Added : Alert message with list of custom theme packs not updated to be compatible with custom fields and feature icons.
+ Added : Check if at least 1 category is published before adding/editing an event.
+ Added : Option to show/hide custom fields in frontend 'Submit an event' form (Menu Item params and Global Options).
+ Added : Own server for Testing Updates (alpha & beta).
# [MEDIUM] Fixed : SQL error 1064 in event edit if no custom fields exists.
# [LOW] Fixed : bug in checking if a slug already exists (custom fields) (could display multiple times the custom field in event details view).
# [LOW] Fixed : bug in display of information option in Event Details view.
# [LOW] Fixed : bug in fields display options in the form to submit an event in frontend.
# [LOW][PRO][MODULE iC Event List] Fixed : today date was not always properly set depending on your hosting location (now uses Joomla config offset).

* Changed files in 3.4.0-alpha2
~ admin/config.xml
~ admin/liveupdate/classes/abstractconfig.php
~ admin/liveupdate/config.php
~ admin/models/customfields.php
+ admin/sql/install/mysql/icagenda.install.sql
- admin/sql/install.mysql.utf8.sql
+ admin/sql/uninstall/mysql/icagenda.uninstall.sql
- admin/sql/uninstall.mysql.utf8.sql
~ admin/tables/customfield.php
~ admin/utilities/categories/categories.php
~ admin/utilities/customfields/customfields.php
+ admin/utilities/theme/theme.php
~ admin/views/customfields/tmpl/default.php
~ admin/views/event/tmpl/edit.php
~ admin/views/event/view.html.php
~ admin/views/events/view.html.php
~ admin/views/features/tmpl/default.php
~ admin/views/icagenda/tmpl/color.php
~ admin/views/icagenda/tmpl/default.php
~ admin/views/info/tmpl/default.php
~ admin/views/themes/tmpl/default.php
~ icagenda.xml
+ [iC Library] libraries/ic_library/file/file.php
~ [iC Library] libraries/ic_library/lib_ic_library.xml
~ [MODULE][PRO] modules/mod_ic_event_list/css/icrounded_style.css
~ [MODULE][PRO] modules/mod_ic_event_list/mod_ic_event_list.php
~ [PLUGIN][iC Library] plugins/system/ic_library/ic_library.php
~ script.icagenda.pro.php
~ site/add/elements/icsetvar.php
~ site/helpers/icmodel.php
~ [THEME PACKS] site/themes/packs/default/default_event.php
~ [THEME PACKS] site/themes/packs/ic_rounded/ic_rounded_event.php
~ site/views/submit/tmpl/default.php
~ site/views/submit/tmpl/default.xml
~ site/views/submit/view.html.php


$ iCagenda 3.4.0-alpha1 <small style="font-weight:normal;">(2014.07.11)</small>
================================================================================
! New : Custom fields.
1 Available in registration and event edition forms.
1 Field types : text, list, radio buttons.
! New : Feature Icons.
1 Create icons for each feature.
1 Attribute one or more features individually for each event.
1 Feature can be for example: Parking, Refreshments, Restaurant, Hotel, Free, TV, Toilets, Swimming, Airport... (no limit of usage!).
! New : Librairies
1 iC Library : standalone library (loaded by a plugin).
1 iCagenda Utilities : integrated library of iCagenda.
! New : Full Thumbnails generator
1 Options for 4 predetermined sizes : large, medium, small, xsmall.
1 For each thumbnail size, individual options : width, height, quality, crop.
! Many code lines cleaned up, and global improvement. (zip is now 0,4 mb lighter!)
+ Added : Modified Date and Modified By fields in admin event edit form.
+ [PRO][MODULE iC Event List] Added : Detection of the categor(y)ies set in the menu items to generate link of an event.
+ [PRO][MODULE iC Event List] Added : Show/Hide venue name
~ [PRO][MODULE iC Event List] Changed : Improved design of icrounded layout.
~ Changed : default ordering of admin list of events is now ID descendant (latest created event in first position).
~ Changed : default ordering of admin list of registered users is now ID descendant (latest registered user in first position).
~ Changed : option to set 'Intro Text'; auto, hide, short desc or meta (global options and modules params).
# [LOW] Fixed : created date was missing in old versions of iCagenda (before 3.1.5). This version update database to set a valid created date for events created with versions of iCagenda < 3.1.5, and set in this order : modified date if valid or next/last date if valid or, at the end, will use current date. (this fix is to prevent wrong 'Created on 30 November -0001' in search results)

* Changed files in 3.4.0-alpha1
~ admin/access.xml
~ admin/add/elements/title.php
~ admin/add/elements/titleimg.php
~ admin/config.xml
+ admin/controllers/customfield.php
+ admin/controllers/customfields.php
~ admin/controllers/event.php
+ admin/controllers/feature.php
+ admin/controllers/features.php
~ admin/helpers/icagenda.php
~ admin/icagenda.php
+ admin/models/customfield.php
+ admin/models/customfields.php
~ admin/models/event.php
~ admin/models/events.php
+ admin/models/feature.php
+ admin/models/features.php
~ admin/models/fields/modal/date.php
+ admin/models/fields/modal/thumbs.php
+ admin/models/forms/customfield.xml
~ admin/models/forms/event.xml
+ admin/models/forms/feature.xml
~ admin/models/forms/registration.xml
~ admin/models/icagenda.php
~ admin/models/registration.php
~ admin/models/registrations.php
+ admin/tables/customfield.php
~ admin/tables/event.php
+ admin/tables/feature.php
~ admin/tables/icagenda.php
~ admin/tables/registration.php
+ admin/utilities/categories/categories.php
+ admin/utilities/class/class.php
+ admin/utilities/customfields/customfields.php
+ admin/utilities/form/form.php
+ admin/utilities/thumb/thumb.php
~ admin/views/category/tmpl/edit.php
+ admin/views/customfield/tmpl/edit.php
+ admin/views/customfield/view.html.php
+ admin/views/customfields/tmpl/default.php
+ admin/views/customfields/view.html.php
~ admin/views/event/tmpl/edit.php
~ admin/views/events/tmpl/default.php
~ admin/views/events/view.html.php
+ admin/views/feature/tmpl/edit.php
+ admin/views/feature/view.html.php
+ admin/views/features/tmpl/default.php
+ admin/views/features/view.html.php
~ admin/views/icagenda/tmpl/default.php
~ admin/views/icagenda/view.html.php
~ admin/views/info/tmpl/default.php
~ admin/views/registration/tmpl/edit.php
~ admin/views/registrations/tmpl/default.php
~ icagenda.xml
+ media/css/icagenda-back.css
+ media/css/icagenda-front.css
~ [iCicons][New icons] media/icicons/
+ media/images/customfields-16.png
+ media/images/customfields-48.png
+ media/images/features-16.png
+ media/images/features-48.png
+ media/images/panel_denied/customfields-48.png
+ media/images/panel_denied/features-48.png
~ [IMAGES][All png optimized] media/images/
~ media/js/icdates.js
- [FOLDER] media/scripts/
~ [MODULE][PRO] modules/mod_ic_event_list/css/default_style.css
~ [MODULE][PRO] modules/mod_ic_event_list/css/icrounded_style.css
~ [MODULE][PRO] modules/mod_ic_event_list/helper.php
~ [MODULE][PRO] modules/mod_ic_event_list/mod_ic_event_list.php
~ [MODULE][PRO] modules/mod_ic_event_list/mod_ic_event_list.xml
~ [MODULE][PRO] modules/mod_ic_event_list/tmpl/default.php
~ [MODULE][PRO] modules/mod_ic_event_list/tmpl/icrounded.php
~ [MODULE] modules/mod_iccalendar/helper.php
~ [MODULE] modules/mod_iccalendar/mod_iccalendar.php
~ [MODULE] modules/mod_iccalendar/mod_iccalendar.xml
- [FOLDER] plugins/search/plg_icagenda/
+ [FOLDER] plugins/search/icagenda/
- [FOLDER] plugins/system/plg_ic_autologin/
+ [FOLDER] plugins/system/ic_autologin/
+ plugins/system/ic_library/ic_library.php
+ plugins/system/ic_library/ic_library.xml
~ script.icagenda.pro.php
~ site/add/elements/icsetvar.php
~ site/helpers/ichelper.php
~ site/helpers/iCicons.class.php
~ site/helpers/icmodel.php
~ site/icagenda.php
~ site/js/icmap.js
~ site/models/forms/submit.xml
~ site/models/list.php
~ site/models/submit.php
~ site/router.php
~ [THEME PACKS] site/themes/packs/default/css/default_component.css
~ [THEME PACKS] site/themes/packs/default/css/default_module.css
~ [THEME PACKS] site/themes/packs/default/default_day.php
~ [THEME PACKS] site/themes/packs/default/default_event.php
~ [THEME PACKS] site/themes/packs/default/default_events.php
~ [THEME PACKS] site/themes/packs/ic_rounded/css/ic_rounded_component.css
~ [THEME PACKS] site/themes/packs/ic_rounded/css/ic_rounded_component_small.css
~ [THEME PACKS] site/themes/packs/ic_rounded/css/ic_rounded_component_xsmall.css
~ [THEME PACKS] site/themes/packs/ic_rounded/css/ic_rounded_module.css
~ [THEME PACKS] site/themes/packs/ic_rounded/ic_rounded_day.php
~ [THEME PACKS] site/themes/packs/ic_rounded/ic_rounded_event.php
~ [THEME PACKS] site/themes/packs/ic_rounded/ic_rounded_events.php
~ site/views/list/tmpl/default.php
~ site/views/list/tmpl/default.xml
~ site/views/list/tmpl/event.php
~ site/views/list/tmpl/registration.php
~ site/views/list/view.feed.php
~ site/views/list/view.html.php
~ site/views/submit/tmpl/default.php
+ [iC Library] libraries/ic_library/color/color.php
+ [iC Library] libraries/ic_library/filter/output.php
+ [iC Library] libraries/ic_library/lib_ic_library.xml
+ [iC Library] libraries/ic_library/library/library.php
+ [iC Library] libraries/ic_library/string/string.php
+ [iC Library] libraries/ic_library/thumb/create.php
+ [iC Library] libraries/ic_library/thumb/get.php
+ [iC Library] libraries/ic_library/thumb/image.php
+ [iC Library] libraries/ic_library/url/url.php
+ [SQL] #__icagenda_customfields_data
+ [SQL] #__icagenda_feature
+ [SQL] #__icagenda_feature_xref


iCagenda 3.3.8 <small style="font-weight:normal;">(2014.07.04)</small>
================================================================================
+ Added : Events RSS feeds integrated to Joomla (This is a partial integration, displaying all events. An advanced integration with options, and events image in the RSS feed, will be added in 3.4.0 version, thanks to the new iC Library not yet implemented).
~ Changed : ChangeLog design
# [HIGH] Fixed : did not save the date selected during registration in datetime database format , depending on date format settings (was not working properly with name of the day of the week display displayed, eg. Saturday, 21 June 2014, or if AM/PM selected).
# [LOW] Fixed : quote issue in short description when sharing on facebook.

* Changed files in 3.3.8
~ admin/add/css/icagenda.css
+ admin/CHANGELOG.php
- admin/UPDATELOGS.php
~ admin/models/fields/modal/evt_date.php
~ admin/views/icagenda/tmpl/color.php
~ admin/views/icagenda/tmpl/default.php
~ admin/views/icagenda/view.html.php
~ icagenda.xml
~ script.icagenda.php
~ site/helpers/icmodel.php
~ site/models/list.php
~ site/views/list/tmpl/default.php
~ site/views/list/tmpl/event.php
~ site/views/list/tmpl/registration.php
+ site/views/list/view.feed.php


iCagenda 3.3.7 <small style="font-weight:normal;">(2014.05.29)</small>
================================================================================
! New : Custom notification emails to a user after registration to an event can be edit in html using your favorite editor.
+ Added : individual options for the display of fields in menu "Submit an event".
~ Changed : New registration button (uses icons, colors, and a redirect to login with return page, if user has no permission).
# [MEDIUM] Fixed : link to past event if "only next/last date" selected in the menu option, returned a view with no data, depending of value set in option 'Selection of events'.
# [LOW] Fixed : bug if 'today' and 'all dates' selected, could display no events (missing offset in date controls).
# [LOW] Fixed : in iCagenda 3.3.6, the notification emails to a user after registration to an event, do not account for newlines.
# [LOW] Fixed : Print popup view, if SEF disabled.

* Changed files in 3.3.7
~ admin/add/css/icagenda.j25.css
~ admin/config.xml
+ admin/models/fields/modal/ic_editor.php
~ [iCicons][Update] media/icicons/
~ site/helpers/icmodel.php
~ site/models/forms/submit.xml
~ site/models/list.php
~ site/models/submit.php
~ [THEME PACKS] site/themes/packs/default/css/default_component.css
~ [THEME PACKS] site/themes/packs/default/css/default_module.css
~ [THEME PACKS] site/themes/packs/ic_rounded/css/ic_rounded_component.css
~ [THEME PACKS] site/themes/packs/ic_rounded/css/ic_rounded_module.css
~ site/views/list/tmpl/event.php
~ site/views/list/tmpl/registration.php
~ site/views/submit/tmpl/default.php
~ site/views/submit/tmpl/default.xml
~ site/views/submit/view.html.php


iCagenda 3.3.6 <small style="font-weight:normal;">(2014.05.16)</small>
================================================================================
+ Added : Option for Intro Text : hide, the short description (generated from full description) or the meta description (Global Options of the component, and options of the modules).
+ [GLOBALIZATION] Added : tr-TR Turkish (Turkey) date formats.
+ [MODULE Calendar] Added : ID is added next to title of the menu, in option to select 'link to menu'.
+ [PRO] Added : Option to set minimum release stability for update notifications. (PRO OPTIONS tab in global options of iCagenda Pro)
~ [Optimization] : SQL request filtering improved in order to fix an issue, and speed up loading (more optimization to come concerning speed of page loading).
~ Changed : Division of the events tab in the global configuration into 2 tabs : Events (list of events options) and Event (details view options).
~ Changed : Updated addthis script (v300).
# [Optimization] Fixed : the list model was running the loading of data twice, and with this issue fixed the execution time for displaying a list is now halved (Thanks doorknob!).
# [HIGH]Fixed : Access to registration form if registration not activated in options.
# [LOW] Fixed : (only on Joomla 2.5) wrong display of print page if 'All Dates for each event' is selected in menu option.
# [LOW][MODULE Calendar][JS] Fixed : It was correctly deleting and adding the class style_Today but not the reverse for style_Day (by doorknob).
# [LOW]Fixed : Issue with Turkish language in admin events list (due to setlocale function, not used anymore).
# [LOW]Fixed : wrong closing select tags in 2 fields of the registration form.
# [LOW]Fixed : missing div tag in registration form.

* Changed files in 3.3.6
~ admin/config.xml
~ admin/globalization/iso.php
+ admin/globalization/tr-TR.php
~ admin/models/fields/modal/menulink.php
~ admin/views/events/tmpl/default.php
~ admin/views/events/view.html.php
~ media/scripts/icthumb.php
~ [MODULE][PRO] modules/mod_ic_event_list/helper.php
~ [MODULE] modules/mod_iccalendar/helper.php
~ [MODULE] modules/mod_iccalendar/js/jQuery.highlightToday.js
~ [MODULE] modules/mod_iccalendar/js/jQuery.highlightToday.min.js
~ site/add/elements/icsetvar.php
~ site/helpers/ichelper.php
~ site/helpers/icmodel.php
~ site/models/list.php
~ site/views/list/tmpl/default.php
~ site/views/list/tmpl/event.php
~ site/views/list/tmpl/registration.php
~ site/views/list/view.html.php


iCagenda 3.3.5-1 (patch) <small style="font-weight:normal;">(2014.04.29)</small>
================================================================================
# Fixed : possible issue with uploaded files (image and/or file) not attached correctly in frontend submission form.

* Changed files in 3.3.5-1
~ site/views/submit/tmpl/default.php


iCagenda 3.3.5 <small style="font-weight:normal;">(2014.04.27)</small>
================================================================================
+ Added : Control if event is published before editing a user registered for an event. (prevent error if user is registered to an unpublished event)
+ Added : Control if registered date still exists when editing a user registered for an event.
~ Changed : can convert date format depending on the option setting for date format (menu or global), when registration saved since version 3.3.3.
# [MEDIUM] Fixed : Date selection in Registration edition.
# [LOW] Fixed : missing loading of template.js on registration edition (Joomla 2.5).
# [LOW] Fixed : wrong css styling of pagination (Joomla 2.5).

* Changed files in 3.3.5
~ admin/add/css/template.css
~ admin/models/fields/modal/evt_date.php
~ admin/models/fields/modal/evt.php
~ admin/models/registrations.php
~ admin/views/registration/tmpl/edit.php
~ admin/views/registrations/tmpl/default.php
~ site/helpers/icmodel.php


iCagenda 3.3.4 <small style="font-weight:normal;">(2014.04.25)</small>
================================================================================
! Joomla 3.3 Ready! This version has been tested on 3.3.0 rc, and a few improvements has been done to run well on the new Joomla 3.3 available soon !
+ Added : Displays 'Home Page' and 'Submit a New Event' buttons, after validation of the event submission form, if user is logged in (was only displayed when user not logged in).
+ Added : Show 'Registration Options' in frontend submission form, only if registration is activated in global options.
~ Changed : Hide User ID when logged-in in registration form (was visible only for registered user).
~ Changed : no more 'onload' to initialize Google Maps (could prevent onload conflict with other extensions).
# [MEDIUM][Joomla 3.2.x & 3.3-beta] Fixed : in the frontend submission form, when user logged-in, 'disabled' changed to 'readonly' for user name and email, as it will not be submitted on a Joomla 3.3 website, and was giving the bug of double-click-needed on the submit button on J3.2.
# [MEDIUM][Joomla 2.5] Fixed : Global Options BUG with options not accessible -> Not correct path for js files in admin (after change of location for the scripts files in 3.3.3), on Joomla 2.5.
# [LOW] Fixed : Possible missing close div in submission form, if registration not displayed.
# [LOW][MODULE Calendar] Fixed : time was displayed even if the option to show time in event edition was disabled. Control missing in default theme pack.

* Changed files in 3.3.4
~ admin/add/elements/desc.php
~ admin/add/elements/title.php
~ admin/icagenda.php
- admin/models/fields/modal/time.php
~ admin/models/forms/event.xml
~ admin/views/event/tmpl/edit.php
~ admin/views/icagenda/tmpl/default.php
~ admin/views/icagenda/view.html.php
~ script.icagenda.php
~ site/helpers/icmodel.php
~ site/models/submit.php
~ [THEME PACKS] site/themes/packs/default/default_day.php
~ site/views/list/tmpl/registration.php
~ site/views/submit/tmpl/default.php
~ site/views/submit/tmpl/send.php
~ site/views/submit/view.html.php
+ media/js/jquery.noconflict.js


iCagenda 3.3.3 <small style="font-weight:normal;">(2014.04.20)</small>
================================================================================
! New : Edition in admin of a user registered for an event, and possibility to create a new registered user.
! New : Advanced options for Registration Button. You can now replace individually for each event, the link on the button by an external url or an article ('Options' tab, in admin event edition). Another option is added for browser target of the registration button.
+ Added : Global option to set a default date format (general settings tab).
+ [MODULE Calendar] Added : Option to display a custom text in header of calendar. (Thanks doorknob)
+ [MODULE Calendar] Added : Option to set a padding for the tooltip, on mobile devices. (Thanks doorknob)
~ Changed : Date selected during a registration will be saved in database without formatting.
~ Changed : IcoMoon replaced by iCicon font (iCagenda vector icons font), for print and calendar icons on Joomla 3.
~ Changed : forms (Submission and Registration): uses iCtip script to generate information tooltips (replaces css3 tooltips, and adds responsive behaviour to detect screen border).
~ Changed : folder 'add/js' moved from admin and site folders to media folder.
~ [THEME PACKS] Changed : in THEME_day.php file, 'cal_date' changed to 'data-cal-date' (to avoid possible future conflicts as html5 is developed).
~ [ROUTER SEF] Changed : "event_registration" to "registration" at the end of url to registration form (when SEF enabled).
~ Code : many code cleaned and/or improved (Thank you Doorknob for your precious contribution!).
- [ROUTER SEF] Removed : "event_details" at the end of url to event details view (when SEF enabled) and provides a better SEO score.
- [MODULE] Removed : br tags after date/close header in calendar tooltip.
# [MEDIUM] Fixed : error in a php function which changes event time (winter/summer time) when event over a period starting before daylight saving, and finishing after daylight saving.
# [MEDIUM] Fixed : displaying of today, and/or upcoming, or past events are now using Joomla config time zone, to prevent issue with server timezone.
# [LOW] Fixed : ordering of categories (admin).
# [LOW] Fixed : possibility of a PHP Warning: Invalid argument supplied for foreach() in /components/com_icagenda/views/list/tmpl/event.php on line 48, in your site error log.
# [LOW] Fixed : Not sending notification to the user who registers for an event, if email is not set as required.
# [LOW] Fixed : Error if no events, with Addthis button.
# [LOW] Fixed : Bug in All Dates, when only sunday for period (wrong display : "Sunday & Sunday").
# [LOW] Fixed : It was not loading Google Maps script if only coordinates were indicated (empty address).
# [LOW][PLUGIN Search] Fixed : Display of events not filtered by current language.
# [LOW][PRO][MODULE iC Event List] Fixed : Possible missing thumbnail, if no leading "/" in image url.

* Changed files in 3.3.3
~ admin/add/css/icagenda.css
~ admin/add/image/joomlic_iCagenda.png
~ admin/add/image/logo_icagenda.png
- [FOLDER] admin/add/js/
~ admin/config.xml
~ admin/controllers/categories.php
~ admin/controllers/event.php
+ admin/controllers/registration.php
~ admin/helpers/icagenda.php
~ admin/liveupdate/liveupdate.php
~ admin/models/event.php
~ admin/models/events.php
~ admin/models/fields/modal/date.php
+ admin/models/fields/modal/evt.php
+ admin/models/fields/modal/evt_date.php
~ admin/models/fields/modal/icalert_msg.php
+ admin/models/fields/modal/iclink_article.php
+ admin/models/fields/modal/iclink_type.php
+ admin/models/fields/modal/iclink_url.php
~ admin/models/forms/event.xml
+ admin/models/forms/registration.xml
+ admin/models/registration.php
~ admin/tables/category.php
~ admin/tables/event.php
+ admin/tables/registration.php
~ admin/views/categories/tmpl/default.php
~ admin/views/event/tmpl/edit.php
~ admin/views/events/tmpl/default.php
~ admin/views/icagenda/tmpl/default.php
~ admin/views/info/tmpl/default.php
+ admin/views/registration/tmpl/edit.php
+ admin/views/registration/tmpl/index.html
+ admin/views/registration/view.html.php
~ admin/views/registrations/tmpl/default.php
~ admin/views/registrations/view.html.php
~ admin/views/themes/tmpl/default.php
~ media/scripts/icthumb.php
~ [MODULE][PRO] modules/mod_ic_event_list/css/default_style.css
~ [MODULE][PRO] modules/mod_ic_event_list/css/icrounded_style.css
~ [MODULE][PRO] modules/mod_ic_event_list/helper.php
~ [MODULE] modules/mod_iccalendar/helper.php
~ [MODULE] modules/mod_iccalendar/js/jQuery.highlightToday.js
~ [MODULE] modules/mod_iccalendar/js/jQuery.highlightToday.min.js
~ [MODULE] modules/mod_iccalendar/mod_iccalendar.php
~ [MODULE] modules/mod_iccalendar/mod_iccalendar.xml
~ [PLUGIN] plugins/search/plg_icagenda/icagenda.php
~ script.icagenda.php
~ site/add/css/icagenda.css
~ site/add/css/style.css
~ site/add/elements/icsetvar.php
- [FOLDER] site/add/js/
~ site/helpers/ichelper.php
~ site/helpers/iCicons.class.php
~ site/helpers/icmodel.php
~ site/router.php
~ [THEME PACKS] site/themes/packs/default/css/default_component.css
~ [THEME PACKS] site/themes/packs/default/css/default_component_xsmall.css
~ [THEME PACKS] site/themes/packs/default/css/default_module.css
~ [THEME PACKS] site/themes/packs/default/default_day.php
~ [THEME PACKS] site/themes/packs/default/default_registration.php
~ [THEME PACKS] site/themes/packs/ic_rounded/css/ic_rounded_component.css
~ [THEME PACKS] site/themes/packs/ic_rounded/css/ic_rounded_component_xsmall.css
~ [THEME PACKS] site/themes/packs/ic_rounded/css/ic_rounded_module.css
~ [THEME PACKS] site/themes/packs/ic_rounded/css/ic_rounded_module_small.css
~ [THEME PACKS] site/themes/packs/ic_rounded/ic_rounded_day.php
~ [THEME PACKS] site/themes/packs/ic_rounded/ic_rounded_event.php
~ [THEME PACKS] site/themes/packs/ic_rounded/ic_rounded_registration.php
~ site/views/list/tmpl/default.php
~ site/views/list/tmpl/event.php
~ site/views/list/tmpl/registration.php
~ site/views/submit/tmpl/default.php


iCagenda 3.3.2 <small style="font-weight:normal;">(2014.03.17)</small>
================================================================================
! [PLUGIN] New plugin iCagenda search, enables searching in events.
- Removed : option 'All options' (by individual date and for all period) in 'Registration type' (not logical).
# [MEDIUM] Fixed : Not displaying singles dates in registration form.
# [LOW] Fixed : Not setting default value correctly for new global options: show/hide venue's name, city, country and short description.
# [LOW][THEME PACKS] Fixed : Missing ic-box-date class in ic_rounded xsmall media css file.
# [LOW][MODULE] Fixed : possibility of a notice message related to the jquery checking.

* Changed files in 3.3.2
~ admin/models/forms/event.xml
~ admin/tables/event.php
~ icagenda.xml
~ [MODULE] modules/mod_iccalendar/mod_iccalendar.php
+ [PLUGIN] plugins/search/plg_icagenda/icagenda.php
+ [PLUGIN] plugins/search/plg_icagenda/icagenda.xml
+ [PLUGIN] plugins/search/plg_icagenda/index.html
+ [PLUGIN][FOLDER] language
~ script.icagenda.php
~ site/add/elements/icsetvar.php
~ site/helpers/icmodel.php
~ [THEME PACKS] site/themes/packs/ic_rounded/css/ic_rounded_component.css
~ [THEME PACKS] site/themes/packs/ic_rounded/css/ic_rounded_component_xsmall.css
~ site/views/list/view.html.php


iCagenda 3.3.1 <small style="font-weight:normal;">(2014.03.14)</small>
================================================================================
+ Added : Global options to show/hide information in list of events (venue's name, city, country, short description).
+ Added : Global options to show/hide day, month and/or year in date box of the list of events.
+ Added : Global option to set HTML filtering in Short Description (All italicized, No HTML or Authorized tags: <br />, <b>, <strong>, <i>, <em>, <u>).
+ Added : Global option to set first day of the week (used when list of weekdays is displayed).
+ [MODULE iC Calendar] Added : Options to select a background color for days with only one event or more than one event.
+ [MODULE iC Calendar] Added : HTML Filtering Option for Short Description in tooltip.
~ Changed : redirect to login page if user has no access to submission form or is not logged-in.
~ [THEME PACKS] Changed : display order of Venue's name, city and country in tooltip of the calendar (now on the same line).
~ [THEME PACKS][ic_rounded] Changed class names for day, month and year in date box.
- [THEME PACKS] Removed, module iC calendar : <i> tags for short description in tooltip.
# [MEDIUM] Fixed : Duplicate display of alert message, and not display of event details, if event not approved, and user logged-in with approval permissions.
# [MODULE iC Calendar][LOW] Fixed : Not displaying events in module calendar on Joomla 2.5, if all categories selected.

* Changed files in 3.3.1
~ admin/config.xml
~ admin/models/registrations.php
~ admin/views/icagenda/tmpl/default.php
~ admin/views/info/tmpl/default.php
~ [MODULE] modules/mod_iccalendar/helper.php
~ [MODULE] modules/mod_iccalendar/mod_iccalendar.php
~ [MODULE] modules/mod_iccalendar/mod_iccalendar.xml
~ site/add/elements/icsetvar.php
~ site/helpers/icmodel.php
~ [THEME PACKS] site/themes/packs/default/css/default_component.css
~ [THEME PACKS] site/themes/packs/default/css/default_module.css
~ [THEME PACKS] site/themes/packs/default/default_day.php
~ [THEME PACKS] site/themes/packs/default/default_events.php
~ [THEME PACKS] site/themes/packs/ic_rounded/css/ic_rounded_component.css
~ [THEME PACKS] site/themes/packs/ic_rounded/css/ic_rounded_module.css
~ [THEME PACKS] site/themes/packs/ic_rounded/ic_rounded_day.php
~ [THEME PACKS] site/themes/packs/ic_rounded/ic_rounded_events.php
~ site/views/list/tmpl/default.php
~ site/views/list/tmpl/event.php
~ site/views/list/view.html.php
~ site/views/submit/tmpl/default.php


iCagenda 3.3.0 <small style="font-weight:normal;">(2014.03.06)</small>
================================================================================
! [Theme Packs] Added media css files for Responsive Design.
! [SQL] Creates table #__icagenda_customfields database to prepare future Custom Fields System.
+ Added : Options to show/hide the fields in Event Submission Form.
+ Added : Option "Contact's email" in Admin notification mailing list for registrations.
+ Added : Filter by Upcoming/Past/Today events (Admin Events List).
+ Added : Filter by event (Admin Registrations List).
+ [MODULE iC Calendar] Added : Multi-selection of categories.
+ [MODULE iC Calendar] Added : Option to select a default font color for calendar.
+ [SQL] Added : 'metadesc' field to store an event meta-description (if not set, uses the new function to generate a short meta-description based on full description (limited to 160 characters to give the best SEO performance).
+ [SQL] Added : 'custom_fields' field to store data from custom fields (not yet available).
~ [Theme Pack] DEFAULT : major changes in event details view (default_event.php) and list view (default_events.php) by removing table tags, and using div to display content. Many class names changed with a leading prefix 'ic-' added to prevent possible conflict of naming with site templates css files.
~ Updated : iCalcreator updated from v2.16.12 to v2.18 (Add to iCal and Outlook).
~ Changed : limited length for url when adding an event to Yahoo and Google calendar (to prevent errors).
~ Changed : Updating preview of event image in edit admin when mouseover preview link.
~ Changed : Removal of the 404 block in order to prevent double display of an error page (depending of the site template used).
~ [SEO] Changed and enhanced : meta title and description are improved, better filtering, and give the best possible SEO performance.
~ [PRO][MODULE iC Event List] Changed : Using user timezone or if not set, Joomla server time zone, to set today time.
# [HIGH] Security : Fixed access to registration form when an event is unpublished or finished (prevents spamming).
# [MEDIUM] Fixed : conflict with module login, when a user log-in or log-out on the event details view, if 'add to cal' activated (loading iCal/outlook .ics file).
# [MEDIUM] Fixed : redirect to login page if user has no access to registration form and event details page (if direct visit to this page).
# [LOW] Fixed : not sending if missing space after comma, in custom list of emails for notification email.
# [LOW] Fixed : add to outlook calendar if no end date.
# [LOW] Fixed : Error introduced in a previous version with 'add to cal' function, concerning Windows live and yahoo calendars (url broken).
# [LOW] Fixed : Error to get show_page_heading from menu, when not set.
# [LOW] Fixed : conflict of 'date' variable between event details view and calendar (renamed 'iccaldate' in calendar).
# [LOW] Fixed : Error in setting next date if only one date (and/or only sunday) selected as weekday (period events).
#  Many minor bugs fixed, and many code improvement.

* Changed files in 3.3.0
~ admin/config.xml
~ admin/models/category.php
~ admin/models/event.php
~ admin/models/events.php
~ admin/models/forms/event.xml
~ admin/models/mail.php
~ admin/models/registrations.php
~ admin/sql/install.mysql.utf8.sql
~ admin/sql/uninstall.mysql.utf8.sql
~ admin/tables/event.php
~ admin/views/categories/tmpl/default.php
~ admin/views/event/tmpl/edit.php
~ admin/views/events/tmpl/default.php
~ admin/views/events/view.html.php
~ admin/views/icagenda/tmpl/default.php
~ admin/views/info/tmpl/default.php
~ admin/views/registrations/tmpl/default.php
~ admin/views/registrations/view.html.php
~ [MODULE][PRO] modules/mod_ic_event_list/mod_ic_event_list.php
~ [MODULE][PRO] modules/mod_ic_event_list/mod_ic_event_list.xml
~ [MODULE] modules/mod_iccalendar/helper.php
~ [MODULE] modules/mod_iccalendar/mod_iccalendar.php
~ [MODULE] modules/mod_iccalendar/mod_iccalendar.xml
~ script.icagenda.php
~ site/add/css/icagenda.css
~ site/add/css/style.css
~ site/add/elements/icsetvar.php
~ site/helpers/iCalcreator.class.php
~ site/helpers/ichelper.php
~ site/helpers/iCicons.class.php
~ site/helpers/icmodel.php
+ site/helpers/media_css.class.php
~ site/models/list.php
~ site/models/submit.php
~ [THEME PACKS] site/themes/packs/default/css/default_component.css
+ [THEME PACKS] site/themes/packs/default/css/default_component_large.css
+ [THEME PACKS] site/themes/packs/default/css/default_component_medium.css
+ [THEME PACKS] site/themes/packs/default/css/default_component_small.css
+ [THEME PACKS] site/themes/packs/default/css/default_component_xsmall.css
~ [THEME PACKS] site/themes/packs/default/css/default_module.css
+ [THEME PACKS] site/themes/packs/default/css/default_module_large.css
+ [THEME PACKS] site/themes/packs/default/css/default_module_medium.css
+ [THEME PACKS] site/themes/packs/default/css/default_module_small.css
+ [THEME PACKS] site/themes/packs/default/css/default_module_xsmall.css
~ [THEME PACKS] site/themes/packs/default/default_event.php
~ [THEME PACKS] site/themes/packs/default/default_events.php
~ [THEME PACKS] site/themes/packs/ic_rounded/css/ic_rounded_component.css
+ [THEME PACKS] site/themes/packs/ic_rounded/css/ic_rounded_component_large.css
+ [THEME PACKS] site/themes/packs/ic_rounded/css/ic_rounded_component_medium.css
+ [THEME PACKS] site/themes/packs/ic_rounded/css/ic_rounded_component_small.css
+ [THEME PACKS] site/themes/packs/ic_rounded/css/ic_rounded_component_xsmall.css
~ [THEME PACKS] site/themes/packs/ic_rounded/css/ic_rounded_module.css
+ [THEME PACKS] site/themes/packs/ic_rounded/css/ic_rounded_module_large.css
+ [THEME PACKS] site/themes/packs/ic_rounded/css/ic_rounded_module_medium.css
+ [THEME PACKS] site/themes/packs/ic_rounded/css/ic_rounded_module_small.css
+ [THEME PACKS] site/themes/packs/ic_rounded/css/ic_rounded_module_xsmall.css
~ [THEME PACKS] site/themes/packs/ic_rounded/ic_rounded_event.php
~ [THEME PACKS] site/themes/packs/ic_rounded/ic_rounded_events.php
~ site/views/list/tmpl/default.php
~ site/views/list/tmpl/default.xml
~ site/views/list/tmpl/default_vcal.php
~ site/views/list/tmpl/event.php
~ site/views/list/tmpl/registration.php
~ site/views/submit/tmpl/default.php
~ site/views/submit/view.html.php
+ SQL : Adding 'metadesc' column to table #__icagenda_events
+ SQL : Adding 'custom_fields' column to table #__icagenda_registration
+ SQL : Create table #__icagenda_customfields



iCagenda 3.2.13 <small style="font-weight:normal;">(2014.02.01)</small>
================================================================================
! [COMPONENT] Advanced Admin ACL (manage access permissions in iCagenda Backend).
! [MODULE iC Calendar] Enhancement of tooltip display on mobile device. Addition of new options in params of the module. (Thanks doorknob!)
! [MODULE iC Calendar] Beta timezone options removed. A new script, developped by doorknob, is now setting "today" highlight according to visitor local time. You keep option to use Joomla Server Time Zone, and you can set highlight on UTC time zone.
! [GNU/GLP License] Update license to version 3 (or later).
+ Added : Category filtering in administration list of events.
+ Added : Category ordering in administration list of events.
~ [Source Language] Fixed of a few errors in english (en-GB British) source translations files (centre, information...). (Thanks Phil Winsor!)
# [LOW] Fixed : limited length to 2068 bytes of the url to add an event to Google Calendar, to prevent 404 error (url length limitation).
# [LOW] Fixed : missing [...] for short description, in default Theme Pack.
# [LOW] Fixed : attachment field in event form (mouseover).
# [LOW] Fixed : Some global styling error in main css files, and some other needed replacements.
# [LOW] Fixed : Conflict Bootstrap/Google Maps, on Zoom Control and street view button (Joomla 3.2).
# [LOW][THEME PACKS] Fixed : Email cloacking click in 'Default' Theme Pack.
# [LOW][MODULE iC Calendar] Fixed : Missing <tr> tags in week days thead.
# [MEDIUM][MODULE iC Calendar] Fixed : Removed limit of sql request.

* Changed files in 3.2.13
! [GNU/GLP License v3] LICENSE.txt
~ admin/access.xml
~ admin/add/css/icagenda.css
~ admin/helpers/icagenda.php
~ admin/icagenda.php
~ admin/liveupdate/classes/tmpl/nagscreen.php
~ admin/models/events.php
~ admin/models/fields/modal/icalert_msg.php
~ admin/models/fields/modal/icfile.php
~ admin/tables/event.php
~ admin/views/categories/tmpl/default.php
~ admin/views/category/tmpl/edit.php
~ admin/views/event/tmpl/edit.php
~ admin/views/events/tmpl/default.php
~ admin/views/events/view.html.php
~ admin/views/icagenda/tmpl/default.php
~ admin/views/info/tmpl/default.php
~ admin/views/mail/tmpl/edit.php
~ admin/views/registrations/tmpl/default.php
~ admin/views/themes/tmpl/default.php
+ media/images/global_options-48.png
+ [Folder] media/images/panel_denied/
+ [MODULE][PRO] modules/mod_ic_event_list/LICENSE.txt
~ [MODULE][PRO] modules/mod_ic_event_list/mod_ic_event_list.php
~ [MODULE][PRO] modules/mod_ic_event_list/mod_ic_event_list.xml
~ [MODULE][PRO] modules/mod_ic_event_list/tmpl/icrounded.php
~ [MODULE] modules/mod_iccalendar/helper.php
- [MODULE] modules/mod_iccalendar/js/function.js
- [MODULE] modules/mod_iccalendar/js/function_312.js
- [MODULE] modules/mod_iccalendar/js/function_316.js
- [MODULE] modules/mod_iccalendar/js/ictip.js
+ [MODULE] modules/mod_iccalendar/js/jQuery.highlightToday.js
+ [MODULE] modules/mod_iccalendar/js/jQuery.highlightToday.min.js
+ [MODULE] modules/mod_iccalendar/LICENSE.txt
~ [MODULE] modules/mod_iccalendar/mod_iccalendar.php
~ [MODULE] modules/mod_iccalendar/mod_iccalendar.xml
~ [PLUGIN] plugins/plg_ic_autologin/ic_autologin.php
+ [PLUGIN] plugins/plg_ic_autologin/LICENSE.txt
~ script.icagenda.php
~ site/add/css/icagenda.css
~ site/add/css/style.css
~ site/helpers/ichelper.php
~ site/helpers/icmodel.php
~ site/icagenda.php
~ site/js/icmap.js
- site/js/map.js
~ [THEME PACKS] site/themes/packs/default/css/default_module.css
~ [THEME PACKS] site/themes/packs/default/default_day.php
~ [THEME PACKS] site/themes/packs/default/default_event.php
~ [THEME PACKS] site/themes/packs/default/default_events.php
~ [THEME PACKS] site/themes/packs/ic_rounded/css/ic_rounded_module.css
~ [THEME PACKS] site/themes/packs/ic_rounded/ic_rounded_day.php
~ site/views/list/tmpl/event.php


iCagenda 3.2.12 <small style="font-weight:normal;">(2014.01.08)</small>
================================================================================
! [MODULE iC Calendar] Disabling by default the function detecting the visitor time zone in module calendar, in order to highlight 'today'. This script function was giving some issue depending of your server, settings, and joomla version. You can now find an option in parameters of the module calendar, where you can use the visitor time zone to set 'today' highlight. If option 'Beta 1 - Visitor Time Zone' selected, when a new visitor comes to your website, it sets a variable containing his time zone in session cookies (so could slow a little when first visit of this user, as it reloads the page one time). And it keeps this information in browser cookies. If option 'Beta 2 - Visitor Time Zone' selected, retrieves the time zone of the visitor each time a page with a calendar module is loaded. If you encounter an error or problem during loading of a page where a module calendar is displayed, select 'Joomla - Server Time Zone' to use the global configuration Time Zone set for your website, and clean your cookies. A better and more advanced solution will be developped to set the detection of visitor time zone.
~ Minor enhancements and corrections in code.
~ [iCicons] Update of iCagenda iCicons font.
# [LOW][MODULE iC Event List][PRO] Fixed : Issue on joomla 2.5 with option 'All' in multi-select of categories resulting in an empty list.
# [LOW] Fixed : missing strip_tags in event.php tmpl view file.

* Changed files in 3.2.12
~ [FOLDER][iCicons] media/icicons
+ media/js/detect_timezone.js
+ media/js/jquery.detect_timezone.js
~ [MODULE][PRO] modules/mod_ic_event_list/mod_ic_event_list.php
~ [MODULE] modules/mod_iccalendar/helper.php
~ [MODULE] modules/mod_iccalendar/mod_iccalendar.php
~ [MODULE] modules/mod_iccalendar/mod_iccalendar.xml
~ script.icagenda.php
~ site/helpers/icmodel.php
~ site/models/forms/submit.xml
~ [THEME PACKS] site/themes/packs/default/css/default_component.css
~ [THEME PACKS] site/themes/packs/default/default_registration.php
~ [THEME PACKS] site/themes/packs/ic_rounded/css/ic_rounded_component.css


iCagenda 3.2.11 FIX for 3.2.10 <small style="font-weight:normal;">(2014.01.04)</small>
================================================================================
! The function detecting the visitor time zone, in order to highlight 'today', and introduced in version 3.2.10, is now disabled on Joomla 2.5 website due to a possible alert message (no error on Joomla 3). This feature needs more developpement and testing before being introduced again for Joomla 2.5 sites, because of all possible script conflicts that happen on this platform (Joomla 3.2.1 is much more fluid!).
# [HIGH][MODULE iC Calendar] Fixed : Possible issue with calendar (redirecting to home page if script for setting visitor time zone failed).
# [MEDIUM] Fixed : Issue when 'All Dates' selected, and SEF not activated, in opening event details (error 404).

* Changed files in 3.2.11
~ [MODULE] modules/mod_iccalendar/helper.php
~ site/views/list/tmpl/default.php


iCagenda 3.2.10 <small style="font-weight:normal;">(2014.01.03)</small>
================================================================================
+ Added : Options for emails of notification and confirmation - Registration form.
+ [MODULE iC Event List][PRO] Added : 'Upcoming & Today' and 'Today' filter options.
+ [MODULE iC Event List][PRO] Added : Multi-selection of categories.
+ [MODULE iC Calendar] Added : Option to display 'country'.
~ Updated : Translation Credits and Contributors informations.
~ [MODULE iC Calendar] Enhancement : get visitor timezone and set it to session using javascript (client side) to highlight correctly 'today'.
~ [MODULE iC Calendar] Changed : get option 'display time' and global setting 'time format', in tooltip.
# [LOW] Fixed : Filtering of html content of the tip related to 'Add to Cal' button.
# [LOW][MODULE iC Calendar] Fixed : Error on a php 5.2 server, because of the new function to order events per hour in the tooltip (We really recommend switching to minimum php 5.3).
# [LOW][THEME PACKS] Fixed : Link on title in default theme pack.

* Changed files in 3.2.10
~ admin/config.xml
+ admin/models/fields/modal/ictext_placeholder.php
~ admin/views/icagenda/tmpl/default.php
~ admin/views/info/tmpl/default.php
~ [MODULE][PRO] modules/mod_ic_event_list/helper.php
~ [MODULE][PRO] modules/mod_ic_event_list/mod_ic_event_list.php
~ [MODULE][PRO] modules/mod_ic_event_list/mod_ic_event_list.xml
~ [MODULE] modules/mod_iccalendar/helper.php
~ [MODULE] modules/mod_iccalendar/mod_iccalendar.php
~ [MODULE] modules/mod_iccalendar/mod_iccalendar.xml
~ script.icagenda.php
~ site/helpers/iCicons.class.php
~ site/helpers/icmodel.php
~ [THEME PACKS] site/themes/packs/default/default_day.php
~ [THEME PACKS] site/themes/packs/ic_rounded/ic_rounded_day.php


iCagenda 3.2.9 <small style="font-weight:normal;">(2013.12.28)</small>
================================================================================
+ Added : Add to calendar icon (iCal, Google, Yahoo, Windows Live and Outlook calendars) in event details view.
+ Added : Print icon in event details view.
+ [MODULE Calendar] Added : Time for each event, in infotip.
+ [MODULE Calendar] Added : Option to used the text 'Close' in the infotip, translated in your current language, or use of a custom value.
+ [MODULE iC Event List][PRO] Added : Option to display list in columns (1 to 4 columns per row).
~ [THEME PACKS] Changed : style class 'content' renamed in 'ic-content'.
~ [THEME PACKS] Removed : Back button from Theme Packs, and added it in view file (to add future options for this button).
# [LOW] Fixed : Missing date in url, when clicking on [...] in short description, if 'All Dates' option selected for the list of events page view.
# [MEDIUM] Fixed : Change og tag description to full description, for sharing on social networks (remove html tags).
# [MEDIUM][MODULES] Fixed : Date display in Event details view after click on module links, was wrong if 'All Dates' option selected for the list of events page view.
# [MEDIUM][MODULE CALENDAR] Fixed : missing closing div in loading html (may in a rare cases give an error in displaying script code).

* Changed files in 3.2.9
~ admin/config.xml
~ admin/models/fields/modal/ictxt_default.php
~ admin/models/fields/modal/icvalue_opt.php
~ admin/views/info/tmpl/default.php
+ [FOLDER] media/images/cal/
~ [MODULE][PRO] modules/mod_ic_event_list/css/default_style.css
~ [MODULE][PRO] modules/mod_ic_event_list/css/icrounded_style.css
~ [MODULE][PRO] modules/mod_ic_event_list/mod_ic_event_list.php
~ [MODULE][PRO] modules/mod_ic_event_list/mod_ic_event_list.xml
~ [MODULE][PRO] modules/mod_ic_event_list/tmpl/default.php
~ [MODULE][PRO] modules/mod_ic_event_list/tmpl/icrounded.php
~ [MODULE] modules/mod_iccalendar/helper.php
~ [MODULE] modules/mod_iccalendar/mod_iccalendar.php
~ [MODULE] modules/mod_iccalendar/mod_iccalendar.xml
~ site/add/css/style.css
~ site/add/elements/icsetvar.php
~ site/controller.php
+ site/helpers/iCalcreator.class.php
~ site/helpers/ichelper.php
+ site/helpers/iCicons.class.php
~ site/helpers/icmodel.php
~ site/models/list.php
~ [THEME PACKS] site/themes/packs/default/css/default_component.css
~ [THEME PACKS] site/themes/packs/default/css/default_module.css
~ [THEME PACKS] site/themes/packs/default/default_day.php
~ [THEME PACKS] site/themes/packs/default/default_event.php
~ [THEME PACKS] site/themes/packs/default/default_events.php
~ [THEME PACKS] site/themes/packs/default/default_registration.php
~ [THEME PACKS] site/themes/packs/ic_rounded/css/ic_rounded_component.css
~ [THEME PACKS] site/themes/packs/ic_rounded/css/ic_rounded_module.css
~ [THEME PACKS] site/themes/packs/ic_rounded/ic_rounded_day.php
~ [THEME PACKS] site/themes/packs/ic_rounded/ic_rounded_event.php
~ [THEME PACKS] site/themes/packs/ic_rounded/ic_rounded_events.php
~ site/views/list/tmpl/default.php
+ site/views/list/tmpl/default_vcal.php
~ site/views/list/tmpl/event.php
~ site/views/list/view.html.php


iCagenda 3.2.8 <small style="font-weight:normal;">(2013.12.15)</small>
================================================================================
! New : Option to display All Dates for each event (or Next/last date of each event as it was before this release).
! [THEME PACKS] Important : New file THEME_events.php to replace THEME_list.php, and new names of data variables.
+ Added : Globalization Date Format file for Ukrainian uk-UA
+ Added : Localization of Google-maps based on the current language of the site. (Thanks SLV!)
~ [MODULE iC Event List][PRO] Changed : Enhancement of Date and Time option.
~ Changed : Enhancement of css of Submission form on J2.5 websites.
# [LOW] Fixed : alone div tag, which can give problem of display of submission form page.
# [LOW] Fixed : issue in style display of category title.
# [LOW] Fixed : category title and description in header of list of events sometimes in double.
# [THEME PACKS][LOW] Fixed : css missing style for category name in header of the list of events.
# [MODULE iC Event List][PRO][LOW] Fixed : Possible issue with module display.
# [MODULE Calendar][MEDIUM] Fixed : Possible issue with module changing months, due to a bug in text "loading...".

* Changed files in 3.2.8
~ admin/add/css/icagenda.css
~ admin/config.xml
+ admin/globalization/uk-UA.php
+ admin/models/fields/modal/icalert_msg.php
~ admin/views/event/tmpl/edit.php
~ [MODULE][PRO] modules/mod_ic_event_list/css/icrounded_style.css
~ [MODULE][PRO] modules/mod_ic_event_list/mod_ic_event_list.php
~ [MODULE][PRO] modules/mod_ic_event_list/mod_ic_event_list.xml
~ [MODULE][PRO] modules/mod_ic_event_list/tmpl/default.php
~ [MODULE][PRO] modules/mod_ic_event_list/tmpl/icrounded.php
~ [MODULE] modules/mod_iccalendar/helper.php
~ [MODULE] modules/mod_iccalendar/mod_iccalendar.php
~ script.icagenda.php
~ site/add/css/icagenda.j25.css
- site/add/css/template.css
+ site/add/elements/icsetvar.php
~ site/helpers/ichelper.php
~ site/helpers/icmodel.php
~ site/models/list.php
~ [THEME PACKS] site/themes/packs/default/css/default_component.css
~ [THEME PACKS] site/themes/packs/default/css/default_module.css
~ [THEME PACKS] site/themes/packs/default/default_event.php
+ [THEME PACKS] site/themes/packs/default/default_events.php
- [THEME PACKS] site/themes/packs/default/default_list.php
~ [THEME PACKS] site/themes/packs/default/default_registration.php
~ [THEME PACKS] site/themes/packs/ic_rounded/css/ic_rounded_component.css
~ [THEME PACKS] site/themes/packs/ic_rounded/css/ic_rounded_module.css
~ [THEME PACKS] site/themes/packs/ic_rounded/ic_rounded_event.php
+ [THEME PACKS] site/themes/packs/ic_rounded/ic_rounded_events.php
- [THEME PACKS] site/themes/packs/ic_rounded/ic_rounded_list.php
~ [THEME PACKS] site/themes/packs/ic_rounded/ic_rounded_registration.php
~ site/views/list/tmpl/default.php
~ site/views/list/tmpl/default.xml
~ site/views/list/tmpl/event.php
~ site/views/list/tmpl/registration.php
~ site/views/list/view.html.php
~ site/views/submit/tmpl/default.php
~ site/views/submit/tmpl/send.php


iCagenda 3.2.7 <small style="font-weight:normal;">(2013.11.23)</small>
================================================================================
~ [MODULE iC calendar] Changed : minor edit in sql request of module iC calendar
# [LOW] Fixed : bug in breadcrumbs event details view.
# [THEME PACKS][LOW] Fixed : possible issue of display break when using ic_rounded theme (depending of your site template).

* Changed files in 3.2.7
~ [MODULE] modules/mod_iccalendar/helper.php
~ [THEME PACKS] site/themes/packs/ic_rounded/ic_rounded_list.php
~ site/views/list/tmpl/event.php


iCagenda 3.2.6 <small style="font-weight:normal;">(2013.11.21)</small>
================================================================================
! New : Option (menu and global) to display Category informations; title and/or description (in header of list of events).
+ Added : Event Details view added to Breadcrumbs.
+ Added : Option Top & Bottom for navigation arrows (list of events).
# [MODULE iC Event List][PRO] Fixed : time not displayed correctly in module iC Event List.
# [MODULE iC Event List][PRO] Fixed : clic to event details views was not working on IE 9 (and under) with icrounded layout.

* Changed files in 3.2.6
~ admin/config.xml
+ admin/models/fields/modal/icmulti_checkbox.php
+ admin/models/fields/modal/icmulti_opt.php
~ admin/models/forms/category.xml
~ admin/views/icagenda/tmpl/default.php
~ [MODULE][PRO] modules/mod_ic_event_list/mod_ic_event_list.php
~ [MODULE][PRO] modules/mod_ic_event_list/tmpl/default.php
~ [MODULE][PRO] modules/mod_ic_event_list/tmpl/icrounded.php
~ site/helpers/icmodel.php
~ site/models/list.php
~ [THEME PACKS] site/themes/packs/ic_rounded/css/ic_rounded_component.css
~ site/views/list/tmpl/default.php
~ site/views/list/tmpl/default.xml
~ site/views/list/tmpl/event.php
~ site/views/list/view.html.php


iCagenda 3.2.5 <small style="font-weight:normal;">(2013.11.11)</small>
================================================================================
! Terms and Conditions Option added to registration form.
! Design compatibility with Joomla 3.2.0 (admin header html) and enhancements in admin display.
+ [THEME PACKS] Added css and php integration of registration infos in calendar tooltip.
+ [MODULE iC Calendar] Added : Options to display city, name of venue, short description, and registration infos (number of seats, seats available and already registered).
~ [MODULE iC Calendar] Changed : 'today' day is now using joomla timezone (was server timezone before).

* Changed files in 3.2.5
~ admin/add/css/icagenda.css
~ admin/add/css/icagenda.j25.css
~ admin/config.xml
- admin/models/fields/eventtitle.php
+ admin/models/fields/modal/ictxt_article.php
+ admin/models/fields/modal/ictxt_content.php
+ admin/models/fields/modal/ictxt_default.php
+ admin/models/fields/modal/ictxt_type.php
~ admin/models/forms/category.xml
~ admin/models/forms/event.xml
~ admin/views/categories/view.html.php
~ admin/views/category/tmpl/edit.php
~ admin/views/category/view.html.php
~ admin/views/event/tmpl/edit.php
~ admin/views/event/view.html.php
~ admin/views/events/view.html.php
~ admin/views/icagenda/view.html.php
~ admin/views/info/view.html.php
~ admin/views/mail/view.html.php
~ admin/views/registrations/view.html.php
~ admin/views/themes/view.html.php
~ [MODULE][PRO] modules/mod_ic_event_list/mod_ic_event_list.xml
~ [MODULE] modules/mod_iccalendar/helper.php
~ [MODULE] modules/mod_iccalendar/mod_iccalendar.xml
~ script.icagenda.php
- site/add/js/address.js
- site/add/js/dates.js
~ [THEME PACKS] site/themes/packs/default/css/default_module.css
~ [THEME PACKS] site/themes/packs/default/default_day.php
~ [THEME PACKS] site/themes/packs/ic_rounded/css/ic_rounded_module.css
~ [THEME PACKS] site/themes/packs/ic_rounded/ic_rounded_day.php
~ site/views/list/tmpl/registration.php


iCagenda 3.2.4 <small style="font-weight:normal;">(2013.10.29)</small>
================================================================================
+ [MODULE iC Event List][PRO] Added : category color as background of the date, in 'default' layout.
~ [MODULE iC Calendar] Changed : authorizes <br /> and <br> html tags in Short Description.
# Fixed : Issue when only sunday selected for period events, all days of the week were displayed.
# Fixed : Not display of Google Maps (blank) after update to last release 3.2.3, when Google Maps Global Options were not set before.
# Fixed : safehtml filter from joomla not working in frontend (skipping html tags, as should not). Filter set now to raw to not skip tags.
# Fixed : issue when access levels to Event Submission Form set to multiple levels (was not filtering access levels as expected).
# [THEME PACKS] Fixed : Issue Alignement of editor buttons in submission form.
# [MODULE iC Event List][PRO] Fixed : wrong display of events in column, due to a conflict in some site templates.

* Changed files in 3.2.4
~ admin/views/event/tmpl/edit.php
~ [MODULE][PRO] modules/mod_ic_event_list/css/default_style.css
~ [MODULE][PRO] modules/mod_ic_event_list/css/icrounded_style.css
~ [MODULE][PRO] modules/mod_ic_event_list/helper.php
~ [MODULE][PRO] modules/mod_ic_event_list/mod_ic_event_list.php
~ [MODULE][PRO] modules/mod_ic_event_list/mod_ic_event_list.xml
~ [MODULE][PRO] modules/mod_ic_event_list/tmpl/default.php
~ [MODULE][PRO] modules/mod_ic_event_list/tmpl/icrounded.php
~ [MODULE] modules/mod_iccalendar/helper.php
~ site/helpers/icmodel.php
~ site/models/forms/submit.xml
~ site/models/submit.php
~ [THEME PACKS] site/themes/packs/default/css/default_component.css
~ [THEME PACKS] site/themes/packs/ic_rounded/css/ic_rounded_component.css
~ site/views/list/tmpl/default.php
~ site/views/list/view.html.php
~ site/views/submit/tmpl/default.php
~ site/views/submit/view.html.php


iCagenda 3.2.3 <small style="font-weight:normal;">(2013.10.20)</small>
================================================================================
! [THEME PACKS] Updated : enhancements of ic_rounded theme pack, to give a better responsive experience. All table tags have been removed, and replace with div tags, and with addition of @media css styling depending of the device (mobile, tablet, desktop). This new version of ic_rounded theme pack will now have version number respectively to the component version. (to improve tracking updates by users creating their own theme. For your information, a website page is in preparation for you to get more information and documentation about creating and updating a personal Theme Pack, and new features for Theme Pack manager are in brainstorming!).
! No loading of Google Maps scripts, if no address is set, or if global option is set on Hide (to speed up loading when this files are not needed).
+ Added : missing Options Week Days in Frontend Submission Form.
+ [MODULE iC Event List][PRO] Added : Options to display date and time, city, short description, and registration infos (number of seats, seats available and already booked).
~ [THEME PACKS] Changed : enhancements of the back arrow to detect if a previous page has been visited. Code in themes php file is now simplified.
~ Changed : enhancements of Open Graph tags (title, type, image, url, description, sitename).
~ Changed : enhancements and changes in <hn> tags used in iCagenda, to able a better structural hierarchy of list of events. (auto-detect if page heading is displayed in content or not, to set properly the Hn tag).
~ Changed : views php files to speed up loading of iCagenda (list of events, event details and event registration).
# Fixed : Calendar Issue; Bug in some countries about the time change. If a date of an event over a period was the day of the time change, it was generated 2 times. The new feature integrates this setting to not double this day.


* Changed files in 3.2.3
+ admin/models/fields/modal/icvalue_field.php
+ admin/models/fields/modal/icvalue_opt.php
~ [MODULE][PRO] modules/mod_ic_event_list/css/default_style.css
~ [MODULE][PRO] modules/mod_ic_event_list/css/icrounded_style.css
~ [MODULE][PRO] modules/mod_ic_event_list/helper.php
~ [MODULE][PRO] modules/mod_ic_event_list/mod_ic_event_list.php
~ [MODULE][PRO] modules/mod_ic_event_list/mod_ic_event_list.xml
~ [MODULE][PRO] modules/mod_ic_event_list/tmpl/default.php
~ [MODULE][PRO] modules/mod_ic_event_list/tmpl/icrounded.php
~ [MODULE] modules/mod_iccalendar/helper.php
+ site/helpers/ichelper.php
~ site/helpers/icmodel.php
~ site/models/forms/submit.xml
~ site/models/list.php
~ site/models/submit.php
~ [THEME PACKS] site/themes/packs/default/css/default_component.css
~ [THEME PACKS] site/themes/packs/default/css/default_module.css
~ [THEME PACKS] site/themes/packs/default/default_calendar.php
~ [THEME PACKS] site/themes/packs/default/default_day.php
~ [THEME PACKS] site/themes/packs/default/default_event.php
~ [THEME PACKS] site/themes/packs/default/default_list.php
~ [THEME PACKS] site/themes/packs/default/default_registration.php
~ [THEME PACKS] site/themes/packs/ic_rounded/css/ic_rounded_component.css
~ [THEME PACKS] site/themes/packs/ic_rounded/css/ic_rounded_module.css
+ [THEME PACKS] site/themes/packs/ic_rounded/ic_rounded_alldates.php
~ [THEME PACKS] site/themes/packs/ic_rounded/ic_rounded_calendar.php
~ [THEME PACKS] site/themes/packs/ic_rounded/ic_rounded_day.php
~ [THEME PACKS] site/themes/packs/ic_rounded/ic_rounded_event.php
~ [THEME PACKS] site/themes/packs/ic_rounded/ic_rounded_list.php
~ [THEME PACKS] site/themes/packs/ic_rounded/ic_rounded_registration.php
~ site/views/list/tmpl/default.php
~ site/views/list/tmpl/event.php
~ site/views/list/tmpl/registration.php
~ site/views/list/view.html.php
~ site/views/submit/tmpl/default.php
~ site/views/submit/tmpl/send.php


iCagenda 3.2.2 <small style="font-weight:normal;">(2013.10.10)</small>
================================================================================
! [iCicons] Use of integrated vector icons 'iCicons' designed for iCagenda (will evolve!).
# Fixed : List of dates in registration form (was not filtering by weekdays).
# [iCicons] Fixed : Android not display of arrows in ascii code (calendar, back button, back/next navigation).
# [iCicons] Fixed : Iphone/Ipad, arrows were not clickable (calendar, back button, back/next navigation).
# Fixed : ACL access levels filtering for events in front-end.
# Fixed : Request of Itemid in submit form.
~ Changed : better filtering of Approval access.
~ Changed : clean-up of some php functions, and sql request in frontend.
~ [THEME PACKS] Changed : enhancements of module css, and adding vector icon for back button.

* Changed files in 3.2.2
~ admin/views/events/tmpl/default.php
~ icagenda.xml
~ [MODULE] modules/mod_iccalendar/helper.php
~ [MODULE] modules/mod_iccalendar/mod_iccalendar.php
~ [MODULE] modules/mod_iccalendar/mod_iccalendar.xml
~ site/helpers/icmodel.php
~ site/models/submit.php
~ [THEME PACKS] site/themes/default.xml
~ [THEME PACKS] site/themes/ic_rounded.xml
~ [THEME PACKS] site/themes/packs/default/css/default_module.css
~ [THEME PACKS] site/themes/packs/default/default_event.php
~ [THEME PACKS] site/themes/packs/ic_rounded/css/ic_rounded_module.css
~ [THEME PACKS] site/themes/packs/ic_rounded/ic_rounded_event.php
~ site/views/list/view.html.php
~ site/views/submit/tmpl/default.php
+ [FOLDER] media/icicons/
+ [FOLDER] media/icicons/fonts/
+ media/icicons/fonts/iCicons.eot
+ media/icicons/fonts/iCicons.svg
+ media/icicons/fonts/iCicons.ttf
+ media/icicons/fonts/iCicons.woff
+ media/icicons/lte-ie7.js
+ media/icicons/style.css


iCagenda 3.2.1 <small style="font-weight:normal;">(2013.10.07)</small>
================================================================================
! First Stable release with 'Submit an Event' feature. For Users of the free version, see all the Release Notes of previous RC versions (available for Pro).
~ Changed : Use of DATE_FORMAT_LC3 in list of events, admin (to get date in Russian on windows server).
# Fixed : Remove nowrap css class attribute, to prevent not wrapping to the next line for long title (this is solved in iCagenda, but you may have the same problem in Joomla 3 articles. Proposal of modification added on Joomla core Github).
# Fixed : Error message when updating from an older version, if category filter was set to one category (new option multiple-categories filtering).

* Changed files in 3.2.1
~ admin/views/events/tmpl/default.php
~ site/helpers/icmodel.php
~ site/models/list.php


iCagenda 3.2.0 RC4 <small style="font-weight:normal;">(2013.10.04)</small>
================================================================================
! Added : New option, Multi-selection of categories, in parameters of the menu link to list of events.
! Changed : Updated Google Maps API to V3 https
+ Added : Notification email to a user when his event submitted has been approved by a manager.
+ Added : Redirect to login page if Approval Manager is not connected on event details page (replacing 404 page).
+ Added : New icons for 'Approve this event' (J2.5 using icons, and J3 using icomoon).
+ Added : New tooltip script for manager icons.
+ Added : Router SEF for Submit an Event.
# [LOW] Bug : inserting an extra number data at the end of the footer text line, in notification email send to Approval managers.
# [LOW] Bug : Number of events in header was not well set, when an Approval Manager is logged-in.
# [LOW] Display : Display of info tooltip when Phone Field not shown in registration form.
# [MEDIUM] Bug : display of 'sunday', when no days of the week selected for a period event, in event details view.
~ [THEME PACKS] Changed : Manager Icons are removed from theme packs (to prevent not display in personal theme pack) and added in event.php file.
~ Changed : Attachment opens now in a new window (target blank).

* Changed files in 3.2.0 RC4
~ admin/config.xml
~ admin/models/fields/modal/cat.php
+ admin/models/fields/modal/multicat.php
~ admin/models/forms/event.xml
~ admin/views/event/tmpl/edit.php
~ admin/views/events/tmpl/default.php
~ admin/views/icagenda/tmpl/default.php
~ admin/views/info/tmpl/default.php
~ icagenda.xml
~ site/add/css/style.css
~ site/helpers/icmodel.php
~ site/models/forms/submit.xml
~ site/models/list.php
~ site/models/submit.php
~ site/router.php
~ [THEME PACKS] site/themes/default.xml
~ [THEME PACKS] site/themes/ic_rounded.xml
~ [THEME PACKS] site/themes/packs/default/css/default_component.css
~ [THEME PACKS] site/themes/packs/default/default_event.php
~ [THEME PACKS] site/themes/packs/default/default_list.php
~ [THEME PACKS] site/themes/packs/ic_rounded/css/ic_rounded_component.css
~ [THEME PACKS] site/themes/packs/ic_rounded/ic_rounded_event.php
~ [THEME PACKS] site/themes/packs/ic_rounded/ic_rounded_list.php
~ site/views/list/tmpl/default.xml
~ site/views/list/tmpl/event.php
~ site/views/list/tmpl/registration.php
~ site/views/list/view.html.php
~ site/views/submit/tmpl/default.php
~ site/views/submit/tmpl/send.php
~ site/views/submit/view.html.php
+ [FOLDER] media/css/
+ media/css/tipTip.css
+ [FOLDER] media/css/manager/
+ media/images/manager/approval_16.png
+ [FOLDER] media/js/
+ media/js/jquery.tipTip.js



iCagenda 3.2.0 RC3 <small style="font-weight:normal;">(2013.09.26)</small>
================================================================================
! Changes in the display of Global Options (added General Settings Tab)
! Fixed : important issue in notification emails send to managers authorized to approve events (due to a bug if user is depending of more than one user groups)
! Changed : Approval can be processed directly in Frontend, at event preview page.
+ Added : Check if managers with Approval permissions are Enabled and Activated.
+ Added : Option to select Template in menu-item link 'Submit an Event'.
+ Added : Global option to enable or disable auto login in url links included in notification emails.
+ Added : implemented Page Header and page class suffix in 'Submit an Event' page.
~ Changed : Events submitted in Frontend by a user (manager) belonging to an authorized group will be automatically approved.
~ Changed : Back button in event details view return to list of events ( replace history.go(-1) ).

* Changed files in 3.2.0 RC3
+ admin/add/elements/desc.php
~ admin/config.xml
~ admin/models/forms/event.xml
~ script.icagenda.pro.php
~ site/helpers/icmodel.php
~ site/models/forms/submit.xml
~ site/models/list.php
~ site/models/submit.php
~ [THEME PACKS] site/themes/packs/default/default_event.php
~ [THEME PACKS] site/themes/packs/ic_rounded/ic_rounded_event.php
~ site/views/list/tmpl/registration.php
~ site/views/submit/tmpl/default.php
~ site/views/submit/tmpl/default.xml
~ site/views/submit/tmpl/send.php
~ site/views/submit/view.html.php


iCagenda 3.2.0 RC2 <small style="font-weight:normal;">(2013.09.22)</small>
================================================================================
# Fixed : Access Permissions to 'Submit an Event' form (missing global option).
+ Added : Options to customize the content when a user access to the 'Submit an Event' page, and this user is not connected, or connected but does not have sufficient rights.

* Changed files in 3.2.0 RC2
~ admin/config.xml
+ admin/models/fields/modal/ictext_content.php
+ admin/models/fields/modal/ictext_type.php
~ site/helpers/icmodel.php
~ site/models/list.php
~ site/views/submit/tmpl/default.php


iCagenda 3.2.0 RC <small style="font-weight:normal;">(2013.09.20)</small>
================================================================================
! NEW : Menu Type to 'Submit an Event' in frontend.
! NEW : Selection of days of the week for period events (additional options to come for dates settings!).
! NEW : Plugin iCagenda Autologin.

* Changed files in 3.2.0 RC
~ admin/config.xml
~ admin/models/event.php
~ admin/models/events.php
+ admin/models/fields/modal/tos_article.php
~ admin/models/fields/modal/tos_content.php
+ admin/models/fields/modal/tos_default.php
+ admin/models/fields/modal/tos_type.php
~ admin/tables/event.php
~ admin/views/event/tmpl/edit.php
~ [MODULE PRO] modules/mod_ic_event_list/mod_ic_event_list.php
~ [MODULE] modules/mod_iccalendar/helper.php
~ script.icagenda.php
~ site/helpers/icmodel.php
~ site/models/list.php
~ site/models/submit.php
~ [THEME PACKS] site/themes/default.xml
~ [THEME PACKS] site/themes/packs/default/default_event.php
~ [THEME PACKS] site/themes/packs/default/default_list.php
~ [THEME PACKS] site/themes/ic_rounded.xml
~ [THEME PACKS] site/themes/packs/ic_rounded/ic_rounded_event.php
~ [THEME PACKS] site/themes/packs/ic_rounded/ic_rounded_list.php
+ site/views/submit/tmpl/default.php
+ site/views/submit/tmpl/default.xml
+ site/views/submit/tmpl/send.php
+ site/views/submit/view.html.php
+ [PLUGIN] plugins/plg_ic_autologin/ic_autologin.php
+ [PLUGIN] plugins/plg_ic_autologin/ic_autologin.xml
+ SQL : Adding 'daystime' column to table icagenda_events


iCagenda 3.1.13 <small style="font-weight:normal;">(2013.09.20)</small>
================================================================================
# Fixed : display in frontend of the fake date 30 november 1999, if no single date is set.

* Changed files in 3.1.13
~ site/helpers/icmodel.php


iCagenda 3.1.12 <small style="font-weight:normal;">(2013.09.17)</small>
================================================================================
# Fixed : A problem with the control of the upcoming date for events over a period (unpublished event and message 'no valid date'). This bug is present since version 3.1.5, and rarely appeared.
# Fixed : conflict CSS days font color in calendar module with some Shape5 templates.

* Changed files in 3.1.12
~ site/helpers/icmodel.php
~ [THEME PACKS] site/themes/default.xml
~ [THEME PACKS] site/themes/ic_rounded.xml
~ [THEME PACKS] site/themes/packs/default/css/default_module.css
~ [THEME PACKS] site/themes/packs/ic_rounded/css/ic_rounded_module.css


iCagenda 3.1.11 <small style="font-weight:normal;">(2013.09.13)</small>
================================================================================
# Fixed : Italian bug in translation files, responsible of missing features in event edit (admin).
# Fixed : Error mktime when saving a new event (due to no filling of single dates). A fix should update in the same way events with this issue in the frontend.

* Changed files in 3.1.11
~ admin/models/fields/modal/date.php
~ admin/views/event/tmpl/edit.php
~ site/helpers/icmodel.php


iCagenda 3.1.10 <small style="font-weight:normal;">(2013.09.12)</small>
================================================================================
+ added : control if allow_url_fopen and GD are enabled (thumbnails generator)
+ added : files to prepare the next release with Submit an Event feature!
+ added : Approval option in event edit (will be operating in release 3.2!).
~ Changed : new dates control when saving an event, display now an alert message for new event, and block saving of a new event if no valid date.
~ Changed : enhancement of period datepicker (not possible now to have end date before start date)
# Fixed : not generation of thumbs when extension of a file in caps.
# MODULE iC calendar : Fixed possible conflicts due to div tags enclosed within scripts (rare conflict, manifested by the appearance of a part of the script on the page, and the non-functioning of the calendar).
# THEME IC_ROUNDED : display of next date (Time 2 times), list of events.

* Changed files in 3.1.10
~ admin/add/js/icdates.js
~ admin/config.xml
~ admin/controllers/events.php
+ admin/helpers/html/events.php
~ admin/models/event.php
~ admin/models/fields/modal/date.php
~ admin/models/fields/modal/enddate.php
~ admin/models/fields/modal/startdate.php
+ admin/models/fields/modal/tos_content.php
~ admin/models/forms/event.xml
~ admin/tables/event.php
~ admin/views/event/tmpl/edit.php
~ admin/views/events/tmpl/default.php
~ [PRO] modules/mod_ic_event_list/mod_ic_event_list.php
~ modules/mod_iccalendar/helper.php
~ modules/mod_iccalendar/mod_iccalendar.php
~ site/add/js/icdates.js
~ site/helpers/icmodel.php
+ site/models/forms/submit.xml
~ site/models/list.php
+ site/models/submit.php
~ [THEME PACKS] site/themes/ic_rounded.xml
~ [THEME PACKS] site/themes/packs/ic_rounded/ic_rounded_list.php
+ SQL : Adding 'approval' column to table icagenda_events


iCagenda 3.1.9 <small style="font-weight:normal;">(2013.09.06)</small>
================================================================================
! MODULE iC calendar : possibility now to publish many calendars on a single page.
+ Added : Extra-control if mime-type of the event's image is correct (in order to process thumbnails creation).
+ Added : Complete or not form fields 'Name' and 'Email' with the profile information of a Joomla user connected, in registration form.
+ Added : Option to enable or disable the thumbnail generator.
+ Added : 'Notes' field text area in Registration form (set disabled as default).
+ Added : Option Show/Hide 'Notes' in registration form.
+ Added : Option Show/Hide 'Phone' in registration form.
+ Added : Information and control of folder creation used by iCagenda (thumbnails, attachments).
~ THEME PACKS : version 2.0 (default and ic_rounded).
~ Changed : period of dates with start date the same day than end date is now displayed as 'date start time - end time' (eg. 23 April 2013 10:00-19:00)
~ Changed : list of date formats was without <optgroup> infos in Joomla 3
# MODULE iC calendar : Fixed, Tooltip Close X button was not working on Apple mobile devices.
# Fixed : bugs in thumbnails generator if ROOT/images folder doesn't exist. Solve an issue if path to images is not 'images'.

* Changed files in 3.1.9
~ admin/config.xml
~ admin/models/event.php
~ admin/models/fields/iclist/globalization.php
~ admin/models/fields/modal/enddate.php
~ admin/models/fields/modal/startdate.php
~ admin/models/forms/event.xml
~ admin/models/registrations.php
~ admin/tables/event.php
~ admin/views/event/tmpl/edit.php
~ admin/views/events/tmpl/default.php
~ admin/views/registrations/tmpl/default.php
~ media/scripts/icthumb.php
~ [PRO] modules/mod_ic_event_list/mod_ic_event_list.php
~ [PRO] modules/mod_ic_event_list/mod_ic_event_list.xml
+ modules/mod_iccalendar/helper.php
~ modules/mod_iccalendar/mod_iccalendar.php
~ modules/mod_iccalendar/mod_iccalendar.xml
~ script.icagenda.php
- site/helpers/icmodcalendar.php
~ site/helpers/icmodel.php
~ site/models/list.php
~ [THEME PACKS] site/themes/default.xml
~ [THEME PACKS] site/themes/ic_rounded.xml
~ [THEME PACKS] site/themes/packs/default/default_event.php
~ [THEME PACKS] site/themes/packs/ic_rounded/css/ic_rounded_module.css
~ [THEME PACKS] site/themes/packs/ic_rounded/ic_rounded_day.php
~ [THEME PACKS] site/themes/packs/ic_rounded/ic_rounded_event.php
~ site/views/list/tmpl/registration.php
~ site/views/list/view.html.php
+ SQL : Adding 'created_by_email' column to table icagenda_events
+ SQL : Adding 'weekdays' column to table icagenda_events
+ SQL : Adding 'notes' column to table icagenda_registration


iCagenda 3.1.8 <small style="font-weight:normal;">(2013.08.30)</small>
================================================================================
# Fixed : Error message in liveupdate (developed by Nicholas from Akeeba) to work under php 5.2. I've added a php control to be able to load storage.php file. But, we truly recommend every user to upgrade their php version to a minimum of 5.3, as recommended by Joomla core, and as minimum to be able to install Joomla 3. In the future, you can encounter other such issue, or error message, if you're still in a PHP version lower than 5.3.
+ Added : Alert Message in control panel of the component, if PHP version is lower than 5.3.

* Changed files in 3.1.8
~ admin/liveupdate/classes/storage/storage.php
~ admin/views/icagenda/tmpl/default.php


iCagenda 3.1.7 <small style="font-weight:normal;">(2013.08.29)</small>
================================================================================
+ Added : Created_by filter in list of registered users (admin).
+ Added : Option to use php function checkdnsrr in registration form, to check if email provider is valid (this option is now disabled by default).
+ Added : Options for event details view: show/hide dates, Google Maps, information... and set access level for some.
+ Added : Options to order by dates list of single dates, and display a vertical or horizontal list.
+ Added : Option for registration form : auto-filled name or username, in name's form field (was only name before).
+ MODULE iC calendar : Option to display only start date in the calendar, in case of an event over a period.
~ MODULE iC calendar : Changes in script code of function.js file to prevent some conflict.
~ Changed : Search in registrations list extended: username, name, email, date, phone, people... (only search in Title before this release)
~ Changed : Default value is now set to "by individual date" in 'Registration Type' field.
~ Changed : Upgraded files of LiveUpdate by Akeeba, updates system integrated in iCagenda.
# Fixed : sending notification email to author of an event, when new registration. Fixed of [AUTHOREMAIL] tag.
# Fixed : Error Debug of Google Maps (icmap.js).

* Changed files in 3.1.7
~ admin/config.xml
~ admin/liveupdate/ (All php files of this folder updated)
+ admin/models/fields/modal/checkdnsrr.php
~ admin/models/forms/event.xml
~ admin/models/registrations.php
~ admin/views/icagenda/tmpl/default.php
~ admin/views/registrations/tmpl/default.php
~ modules/mod_iccalendar/js/function.js
~ modules/mod_iccalendar/mod_iccalendar.xml
~ site/helpers/icmodcalendar.php
~ site/helpers/icmodel.php
~ site/js/icmap.js
~ site/themes/packs/default/default_event.php
~ site/views/list/tmpl/registration.php


iCagenda 3.1.6 <small style="font-weight:normal;">(2013.08.20)</small>
================================================================================
# Fixed : NextDate control when event set on a period in the future.
+ Added : Control of time when event with a date in a period the same as a single date.
+ Added : On windows server and php version < 5.3, disable check function if provider of an email address during registration is valid, as checkdnsrr is implemented on windows server only since php 5.3.0

* Changed files in 3.1.6
~ site/helpers/icmodel.php


iCagenda 3.1.5 Security Release and enhancements! <small style="font-weight:normal;">(2013.08.19)</small>
================================================================================
! Security Release : fixed a XSS vulnerability discovered by Stefan Horlacher from Compass Security AG (www.csnc.ch) (many thanks Stefan to keep the web clean and secured!). Another issue was resolved, discovered by Giusebos, which allowed sending spam to the administrator and the creator of the event, using cookies via registration form. And that's not all! As we always want to add much more security, some filtering enhancements have been added to the registration form (see below).
! Change : Now, when an event over a period with an end date and its time set to 00:00:00, this end date is displayed in frontend (list of events, and modules).
+ Added : New options in filtering events in menuitem. Now you can display all events, upcoming events, past events, events of the day and upcoming, or today's events.
+ Added : Page 404 when event not found.
+ Added : Enhancement of Email control during registration. Test if provider is valid.
+ Added : Test of the Name during registration. Now, a name cannot start with a number and cannot contain any of the following characters: / \ < > "_QQ_" [ ] ( ) " ; = + &.
+ Added : Control in front-end if dates of events are valid (control was before only in admin edit)
# Fixed : was counted archived events in header of list of events, and should not.
# Fixed : if end time is lower or equal to start time of an event over a period, end date is displayed.
# Fixed : Author name and username were not correctly displayed in admin events list, and now display correctly the user selected in 'created by'.

* Changed files in 3.1.5
~ admin/models/events.php
~ admin/tables/event.php
~ admin/views/events/tmpl/default.php
~ site/helpers/icmodcalendar.php
~ site/helpers/icmodel.php
~ site/views/list/tmpl/default.xml
~ site/views/list/tmpl/event.php
~ site/views/list/tmpl/registration.php


iCagenda 3.1.4 <small style="font-weight:normal;">(2013.08.13)</small>
================================================================================
# Fixed : bug in function for detecting wrong dates entered by user, which was not always working as expected, depending of time setting in joomla config
# Fixed : change in function for globalized date format of month and of day, to prevent some errors due to locale (Russian...)
# Fixed : Not sending notification email to the registered user (if his email address is entered and required)
+ Added : Control of event ID to prevent spamming emails to administrator by a robot (notification email admin)
~ Changed : Translation of Date in current language (admin - list of events)

* Changed files in 3.1.4
~ admin/tables/event.php
~ admin/views/events/tmpl/default.php
~ site/helpers/icmodcalendar.php
~ site/helpers/icmodel.php
~ site/themes/packs/default/css/default_module.css
~ site/themes/packs/ic_rounded/css/ic_rounded_module.css


iCagenda 3.1.3 <small style="font-weight:normal;">(2013.08.09)</small>
================================================================================
# Fixed : global option to hide the participants list not working properly
# Fixed : notice message above registration option field, in event edit
~ MODULE iC calendar : changed, access levels control, to speed up loading of pages with calendar
+ MODULE iC calendar : loading picture when charging a new month

* Changed files in 3.1.3
~ admin/models/fields/modal/ph_regbt.php
~ modules/mod_iccalendar/js/function.js
~ modules/mod_iccalendar/mod_iccalendar.php
~ site/helpers/icmodcalendar.php
~ site/helpers/icmodel.php
~ site/themes/packs/default/css/default_module.css
~ site/themes/packs/ic_rounded/css/ic_rounded_module.css
~ (added) site/themes/packs/default/images/ic_load.png
~ (added) site/themes/packs/ic_rounded/images/ic_load.png


iCagenda 3.1.2 <small style="font-weight:normal;">(2013.08.05)</small>
================================================================================
! Important editing of thumbnails generator (List of events in admin, Calendar module, and Event List module). Now, file renaming for thumbnails (remove all special caracters to get a clean url for image), and copy of distant pictures (to prevent broken link). Accepted as image extensions (File Types) for event image : jpg, jpeg, png, gif, bmp
# Fixed : Slow change of month of the calendar (thumbnail generator error function)
# Fixed : Slow display of events in module iC Event List (Pro Version)
~ changed : [J3 issue] jQuery UI version in admin, from 1.9.2 to 1.8.23 to prevent a conflict with description tooltip (appeared since joomla 3.1.4)

* Changed files in 3.1.2
~ admin/views/event/tmpl/edit.php
~ admin/views/events/tmpl/default.php
~ media/scripts/icthumb.php
~ script.icagenda.php
~ site/helpers/icmodcalendar.php


iCagenda 3.1.1 <small style="font-weight:normal;">(2013.07.29)</small>
================================================================================
# Fixed : Wrong filtering of Viewing Access Levels in list of events page
# Fixed : error in modules (front-end), when url to image is broken or invalid
~ changed : url of image when sharing on facebook (other enhancements planned)

* Changed files in 3.1.1
~ admin/views/icagenda/view.html.php
~ script.icagenda.php
~ site/helpers/icmodcalendar.php
~ site/helpers/icmodel.php
~ site/views/list/tmpl/event.php


iCagenda 3.1.0 <small style="font-weight:normal;">(2013.07.26)</small>
================================================================================
! New : Automatic thumbnails generator in modules (some options, and enhancements will be added later in theme packs)
# Fixed : Issues with J3 after upgrade from joomla 3.1.x to 3.1.4 (error 500 default layout missing, and JFile not found)
# Fixed : not sending admin notification email (error in 3.0.1 and 3.0 pre-releases)
# Fixed : No updating of Next Date when menu set to Upcoming Events
# Fixed : participant slide effect and display options not working
+ Added : Global Option for email field in frontend registration (required or not)
~ many code review

* Changed files in 3.1.0
~ admin/config.xml
~ admin/models/categories.php
~ admin/models/fields/modal/ph_regbt.php
~ admin/tables/event.php
~ admin/views/events/tmpl/default.php
~ admin/views/icagenda/view.html.php
~ media/scripts/icthumb.php
~ script.icagenda.php
~ site/helpers/icmodcalendar.php
~ site/helpers/icmodel.php
~ site/models/list.php
~ site/themes/packs/default/default_event.php
~ site/themes/packs/default/css/default_component.css
~ site/themes/packs/ic_rounded/ic_rounded_event.php
~ site/themes/packs/ic_rounded/css/ic_rounded_component.css
~ site/views/list/tmpl/registration.php


iCagenda 3.0.1 <small style="font-weight:normal;">(2013.07.04)</small>
================================================================================
# Fixed : auto-play of the tutorial video on Chrome and Safari (the video should not autoplay)
# Fixed : missing admin pagination in categories list
# Fixed : buttons display over the datepicker (time show/hide button activated)

* Changed files in 3.0.1
~ admin/add/css/jquery-ui-1.8.17.custom.css
~ admin/views/categories/tmpl/default.php
~ admin/views/categories/view.html.php
~ admin/views/event/tmpl/edit.php
~ admin/views/event/view.html.php


iCagenda 3.0 RC <small style="font-weight:normal;">(2013.06.30)</small>
================================================================================
# Fixed : Thumbnail generator in events list admin : error when using a distant url
# Fixed : Position and zooming in admin, in events created before update
# Fixed : Colors of options buttons in event admin edit : not always visible, in events created before update
# Fixed : Theme ic_rounded : problem of display with long title
+ Added : Custom text option for registration button
+ Added : Control if link to event picture is valid, in admin
~ updated : display in Global Options of the component and modules


iCagenda 3.0 beta 1 <small style="font-weight:normal;">(2013.06.09)</small>
================================================================================
! First beta version compatible with Joomla 3 and Joomla 2.5

* Changed files in 3.0
! Given that this new version brings compatibility with Joomla 3, all php files were reviewed to allow dual Joomla 2.5 / 3.x compatibility. Other files were also reviewed, with a major overhaul of logic and graphic structure of iCagenda. The list of modified files is reset with this new version 3.0 of iCagenda and the list of modified files will be detailed again from future release 3.0.1


iCagenda v2 ChangeLog <small style="font-weight:normal;">(2012.12.31 > 2013.05.29)</small>
? <a href="https://www.icagenda.com/docs/changelog/v2-changelog" target="_blank" style="color:#fff">https://www.icagenda.com/docs/changelog/v2-changelog</a>

iCagenda v1 ChangeLog <small style="font-weight:normal;">(2012.08.07 > 2012.08.29)</small>
? <a href="https://www.icagenda.com/docs/changelog/v1-changelog" target="_blank" style="color:#fff">https://www.icagenda.com/docs/changelog/v1-changelog</a>

;
