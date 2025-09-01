<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.8.18 2023-06-05
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

// Include the component HTML helpers.
JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');

JHtml::_('behavior.keepalive');
JHtml::_('behavior.formvalidator');
JHtml::_('formbehavior.chosen', 'select');
JHtml::_('behavior.tooltip');

JHtml::_('script', 'com_icagenda/icform.js', array('relative' => true, 'version' => 'auto'), array('async' => 'async'));

// CSS files which could be overridden into your admin template. (eg. /templates/my_template/css/com_icagenda/icagenda.css)
JHtml::stylesheet('com_icagenda/icagenda.css', false, true);

$app      = JFactory::getApplication();
$document = JFactory::getDocument();

$params = $this->state->get('params');

$maps_service = $params->get('maps_service', 1);

$ic_style = 'div.tip img.media-preview {display:none}';
$document->addStyleDeclaration($ic_style);

// Styles for multiple dates main Add Button
$add_date_btn_style = '.subform-repeatable > .btn-toolbar .group-add:after { content: " ' . JText::_('JGLOBAL_FIELD_ADD') . ' "; font-weight: bold; } .subform-repeatable > .btn-toolbar .group-add {width: 100%; }';
$document->addStyleDeclaration($add_date_btn_style);



$bootstrapType = '1';

$EventTag     = 'event';
$EventTitle   = JText::_('COM_ICAGENDA_TITLE_EVENT');
$DatesTag     = 'dates';
$DatesTitle   = JText::_('COM_ICAGENDA_LEGEND_DATES');
$DescTag      = 'desc';
$DescTitle    = JText::_('COM_ICAGENDA_LEGEND_DESC');
$InfosTag     = 'infos';
$InfosTitle   = JText::_('COM_ICAGENDA_LEGEND_INFORMATION');
$MapTag       = 'icmap';
$MapTitle     = JText::_('COM_ICAGENDA_LEGEND_MAP');
$RegTag       = 'registrations';
$RegTitle     = JText::_('COM_ICAGENDA_REGISTRATIONS_LABEL');
$OptionsTag   = 'options';
$OptionsTitle = JText::_('JOPTIONS');
$PubTag       = 'publishing';
$PubTitle     = JText::_('JGLOBAL_FIELDSET_PUBLISHING');

if ($bootstrapType == '1')
{
	$iCmapDisplay = '1';
	$startPane    = 'bootstrap.startTabSet';
	$addPanel     = 'bootstrap.addTab';
	$endPanel     = 'bootstrap.endTab';
	$endPane      = 'bootstrap.endTabSet';
	$EventTag1    = $EventTag;
	$EventTag2    = $EventTitle;
	$DatesTag1    = $DatesTag;
	$DatesTag2    = $DatesTitle;
	$DescTag1     = $DescTag;
	$DescTag2     = $DescTitle;
	$InfosTag1    = $InfosTag;
	$InfosTag2    = $InfosTitle;
	$MapTag1      = $MapTag;
	$MapTag2      = $MapTitle;
	$RegTag1      = $RegTag;
	$RegTag2      = $RegTitle;
	$OptionsTag1  = $OptionsTag;
	$OptionsTag2  = $OptionsTitle;
	$PubTag1      = $PubTag;
	$PubTag2      = $PubTitle;
}
elseif ($bootstrapType == '2')
{
	$iCmapDisplay = '2';
	$startPane    = 'bootstrap.startAccordion';
	$addPanel     = 'bootstrap.addSlide';
	$endPanel     = 'bootstrap.endSlide';
	$endPane      = 'bootstrap.endAccordion';
	$EventTag1    = $EventTitle;
	$EventTag2    = $EventTag;
	$DatesTag1    = $DatesTitle;
	$DatesTag2    = $DatesTag;
	$DescTag1     = $DescTitle;
	$DescTag2     = $DescTag;
	$InfosTag1    = $InfosTitle;
	$InfosTag2    = $InfosTag;
	$MapTag1      = $MapTitle;
	$MapTag2      = $MapTag;
	$RegTag1      = $RegTitle;
	$RegTag2      = $RegTag;
	$OptionsTag1  = $OptionsTitle;
	$OptionsTag2  = $OptionsTag;
	$PubTag1      = $PubTitle;
	$PubTag2      = $PubTag;
}




	$paramsfields = $this->form->getFieldsets('params');

// Google Map
$zoom      = $this->item->address ? '16' : '1';
$mapTypeId = 'ROADMAP'; // HYBRID, ROADMAP, SATELLITE, TERRAIN
$coords    = '0, 0';
$lat       = $this->item->lat;
$lng       = $this->item->lng;
// Notes: zoomControl: false, mapTypeControl: false

JFactory::getDocument()->addScriptDeclaration('
	Joomla.submitbutton = function(task)
	{
		if (task == "event.cancel" || document.formvalidator.isValid(document.getElementById("event-form")))
		{
			Joomla.submitform(task, document.getElementById("event-form"));
		}
	};
');

$new_event_value = empty($this->item->id) ? '1' : '0';
?>

<form action="<?php echo JRoute::_('index.php?option=com_icagenda&layout=edit&id=' . (int) $this->item->id); ?>" method="post" name="adminForm" id="event-form" class="form-validate" enctype="multipart/form-data">
	<div class="container">
		<header>
			<h1>
				<?php echo '<input type="hidden" value="' . $new_event_value . '" name="new_event" />'; ?>
				<?php echo empty($this->item->id) ? JText::_('COM_ICAGENDA_LEGEND_NEW_EVENT') : JText::sprintf('COM_ICAGENDA_LEGEND_EDIT_EVENT', $this->item->id); ?>&nbsp;<span>iCagenda</span>
			</h1>
			<h2>
				<?php echo JText::_('COM_ICAGENDA_COMPONENT_DESC'); ?>
			</h2>
		</header>

		<div>&nbsp;</div>

		<!-- Begin Content -->
		<div class="row-fluid">
			<div class="span10 form-horizontal">

				<!-- Open Panel Set -->
				<?php echo JHtml::_($startPane, 'icTab', array('active' => 'event')); ?>

				<!-- Panel Event -->
				<?php echo JHtml::_($addPanel, 'icTab', $EventTag1, $EventTag2); ?>
				<div class="icpanel iCleft">
					<h2>
						<?php echo empty($this->item->id) ? JText::_('COM_ICAGENDA_LEGEND_NEW_EVENT') : JText::sprintf('COM_ICAGENDA_LEGEND_EDIT_EVENT', $this->item->id); ?>
					</h2>
					<hr />
					<div class="row-fluid">
						<div class="span6 iCleft">
							<div class="control-group">
								<div class="control-label">
									<?php echo $this->form->getLabel('title'); ?>
								</div>
								<div class="controls">
									<?php echo $this->form->getInput('title'); ?>
								</div>
							</div>
							<div class="control-group">
								<div class="control-label">
									<?php echo $this->form->getLabel('catid'); ?>
								</div>
								<div class="controls">
									<?php echo $this->form->getInput('catid'); ?>
								</div>
							</div>
						</div>
						<div class="span6 iCleft">
							<?php echo $this->form->renderField('image'); ?>
							<?php if ($this->item->image) : ?>
								<div class="control-group">
									<img src="../<?php echo $this->item->image; ?>" alt="" id="jform_image_preview" class="media-preview" style="float:right; max-width:100%; max-height:350px;">
								</div>
							<?php endif; ?>
						</div>
					</div>
				</div>
				<?php echo JHtml::_($endPanel); ?>

				<!-- Panel Dates -->
				<?php echo JHtml::_($addPanel, 'icTab', $DatesTag1, $DatesTag2); ?>
				<div class="icpanel iCleft">
					<h2>
						<?php echo JText::_('COM_ICAGENDA_LEGEND_DATES'); ?>
					</h2>
					<hr />
					<div class="row-fluid">
						<div class="span6 iCleft">
							<h3>
								<?php echo JText::_('COM_ICAGENDA_LEGEND_PERIOD_DATES'); ?>
							</h3>
							<div class="control-group">
								<div class="control-label">
									<?php echo $this->form->getLabel('startdate'); ?>
								</div>
								<div class="controls">
									<?php echo $this->form->getInput('startdate'); ?>
								</div>
							</div>
							<div class="control-group">
								<div class="control-label">
									<?php echo $this->form->getLabel('enddate'); ?>
								</div>
								<div class="controls">
									<?php echo $this->form->getInput('enddate'); ?>
								</div>
							</div>
						</div>
						<div class="span6 iCleft">
							<h3>
								&nbsp;
							</h3>
							<div class="control-group">
								<div class="control-label">
									<?php echo $this->form->getLabel('weekdays'); ?>
								</div>
								<div class="controls">
									<?php echo $this->form->getInput('weekdays'); ?>
								</div>
							</div>
							<div class="control-group">
								<div class="alert alert-info">
									<h4>
										<?php echo JText::_('COM_ICAGENDA_FORM_WEEK_DAYS_INFO_TITLE'); ?>
									</h4>
									<?php echo JText::_('COM_ICAGENDA_FORM_WEEK_DAYS_INFO_DESC'); ?>
								</div>
							</div>
						</div>
					</div>
					<hr />

					<div class="row-fluid">
						<h3>
							<?php echo JText::_('COM_ICAGENDA_LEGEND_SINGLE_DATES'); ?>
						</h3>
						<?php echo $this->form->getInput('dates'); ?>
						<br />
					</div>
					<hr />

					<div class="row-fluid">
						<div class="span6 iCleft">
							<h3>
								<?php echo JText::_('JOPTIONS'); ?>
							</h3>
							<div class="control-group">
								<div class="control-label">
									<?php echo $this->form->getLabel('displaytime'); ?>
								</div>
								<div class="controls">
									<?php echo $this->form->getInput('displaytime'); ?>
								</div>
							</div>
						</div>
					</div>
					<hr />
				</div>
				<?php echo JHtml::_($endPanel); ?>

				<!-- Panel Description -->
				<?php echo JHtml::_($addPanel, 'icTab', $DescTag1, $DescTag2); ?>
				<div class="icpanel iCleft">
					<h2>
						<?php echo JText::_('COM_ICAGENDA_LEGEND_DESC'); ?>
					</h2>
					<hr />
					<div class="row-fluid">
						<h3>
							<?php echo JText::_('COM_ICAGENDA_FORM_EVENT_SHORT_DESCRIPTION_LBL'); ?>
						</h3>
						<div class="alert alert-info">
							<?php echo JText::_('COM_ICAGENDA_FORM_EVENT_SHORT_DESCRIPTION_DESC'); ?>
						</div>
						<?php echo $this->form->getInput('shortdesc'); ?>
					</div>
					<hr />

					<div class="row-fluid">
						<h3>
							<?php echo JText::_('COM_ICAGENDA_FORM_DESC_EVENT_DESC'); ?>
						</h3>
						<?php echo $this->form->getInput('desc'); ?>
					</div>
					<hr />

					<div class="row-fluid">
						<h3>
							<?php echo JText::_('COM_ICAGENDA_FORM_EVENT_METADESC_LBL'); ?>
						</h3>
						<div class="alert alert-info">
							<?php echo JText::_('COM_ICAGENDA_FORM_EVENT_METADESC_DESC'); ?>
						</div>
						<?php echo $this->form->getInput('metadesc'); ?>
					</div>
				</div>
				<?php echo JHtml::_($endPanel); ?>

				<!-- Panel Information -->
				<?php echo JHtml::_($addPanel, 'icTab', $InfosTag1, $InfosTag2); ?>
				<div class="icpanel iCleft">
					<h2>
						<?php echo JText::_('COM_ICAGENDA_LEGEND_INFORMATION'); ?>
					</h2>
					<hr />
					<div class="row-fluid">
						<div class="span6 iCleft">
							<h3>
								<?php echo JText::_('COM_ICAGENDA_LEGEND_VENUE'); ?>
							</h3>
							<div class="control-group">
								<div class="control-label">
									<?php echo $this->form->getLabel('place'); ?>
								</div>
								<div class="controls">
									<?php echo $this->form->getInput('place'); ?>
								</div>
							</div>
							<hr />

							<h3>
								<?php echo JText::_('COM_ICAGENDA_LEGEND_CONTACT'); ?>
							</h3>
							<div class="control-group">
								<div class="control-label">
									<?php echo $this->form->getLabel('email'); ?>
								</div>
								<div class="controls">
									<?php echo $this->form->getInput('email'); ?>
								</div>
							</div>
							<div class="control-group">
								<div class="control-label">
									<?php echo $this->form->getLabel('phone'); ?>
								</div>
								<div class="controls">
									<?php echo $this->form->getInput('phone'); ?>
								</div>
							</div>
							<div class="control-group">
								<div class="control-label">
									<?php echo $this->form->getLabel('website'); ?>
								</div>
								<div class="controls">
									<?php echo $this->form->getInput('website'); ?>
								</div>
							</div>
							<hr />

							<h3>
								<?php echo JText::_('COM_ICAGENDA_EVENT_ATTACHMENTS_TITLE'); ?>
							</h3>
							<div class="control-group">
								<div class="control-label">
									<?php echo $this->form->getLabel('file'); ?>
								</div>
								<div class="controls">
									<?php echo $this->form->getInput('file'); ?>
								</div>
							</div>
							<hr/>
						</div>
						<div class="span6 iCleft">
							<h3>
								<?php echo JText::_('COM_ICAGENDA_LEGEND_FEATURES'); ?>
							</h3>
							<div class="control-group">
								<div class="control-label">
									<?php echo $this->form->getLabel('features'); ?>
								</div>
								<div class="controls">
									<?php echo $this->form->getInput('features'); ?>
								</div>
							</div>
							<hr />

							<h3>
								<?php echo JText::_('COM_ICAGENDA_CUSTOMFIELDS'); ?>
							</h3>
							<?php echo icagendaCustomfields::loader(2); // Load Custom fields - Event form (2) ?>
						</div>
					</div>
				</div>
				<?php echo JHtml::_($endPanel); ?>

				<!-- Panel Map -->
				<?php echo JHtml::_($addPanel, 'icTab', $MapTag1, $MapTag2); ?>
				<div class="icpanel iCleft" id="maptab">
					<h2>
						<?php echo JText::_('COM_ICAGENDA_LEGEND_MAP'); ?>
					</h2>
					<hr />
					<div class="row-fluid">
						<?php if ( ! $maps_service) : ?>
							<div class="span12">
								<div class="ic-map-box">
									<div class="control-group">
										<div class="control-label">
											<?php echo $this->form->getLabel('address'); ?>
										</div>
										<div class="controls">
											<?php echo $this->form->getInput('address'); ?>
										</div>
									</div>
								</div>
								<div class="alert alert-warning">
									<?php echo JText::_('COM_ICAGENDA_MAPS_SERVICE_NOT_SET'); ?>
								</div>
								<script>
									document.getElementById('jform_address').setAttribute('class', 'input-xxlarge');
								</script>
							</div>

						<!-- LeafLet OpenStreetMap -->
						<?php elseif ($maps_service == '1') : ?>
							<div class="span12">
								<div class="icmap-address">
									<div class="control-group">
										<div class="control-label">
											<?php echo $this->form->getLabel('address'); ?>
										</div>
										<div class="controls">
											<div id="findbox"></div>
											<?php echo $this->form->getInput('address'); ?>
										</div>
									</div>
								</div>
							</div>
							<div>
								<div class="span3">
									<div class="icmap-field">
										<?php echo $this->form->getInput('city'); ?>
									</div>
									<div class="icmap-field">
										<?php echo $this->form->getInput('country'); ?>
									</div>
									<div class="icmap-field">
										<?php echo $this->form->getInput('lat'); ?>
									</div>
									<div class="icmap-field">
										<?php echo $this->form->getInput('lng'); ?>
									</div>
								</div>
								<div class="span9">
									<div class="ic-map-container">
										<div class="ic-map-wrapper" id="ic-map-<?php echo (int) $this->item->id; ?>"></div>
									</div>
								</div>
							</div>
							<?php icagendaMapsLeafletSearch::addJS('ic-map-' . (int) $this->item->id, 'jform_address', array('tab' => 'a[href=\"#icmap\"]')); ?>								

						<!-- Google Maps Embed API -->
						<?php elseif ($maps_service == '2') : ?>
							<div class="span12">
								<div class="ic-map-box">
									<div class="control-group">
										<div class="control-label">
											<?php echo $this->form->getLabel('address'); ?>
										</div>
										<div class="controls">
											<div class="input-append">
												<?php echo $this->form->getInput('address'); ?>
												<span class="btn btn-default" onclick="myFunction(document.getElementById('jform_address').value)"><?php echo JText::_('IC_CHECK'); ?></span>
											</div>
										</div>
									</div>
								</div>
								<div id="embed_map">
									<?php if ($this->item->address) : ?>
										<?php echo '<iframe width="100%" height="100%" frameborder="0" style="border:0;" class="ic-map-iframe" src="https://www.google.com/maps/embed/v1/place?key=' . $params->get('googlemaps_embed_key', '') . '&q=' . urlencode(strip_tags($this->item->address)) . '" allowfullscreen></iframe>'; ?>
									<?php endif; ?>
								</div>
								<script>
									document.getElementById('jform_address').setAttribute('class', 'input-xxlarge');
									function myFunction(address) {
										var embedKey = "<?php echo $params->get('googlemaps_embed_key', ''); ?>";
										if (address){ 
											html = '<iframe width="100%" height="100%" frameborder="0" style="border:0;" class="ic-map-iframe" src="https://www.google.com/maps/embed/v1/place?key=' + embedKey + '&q=' + encodeURI(address) + '" allowfullscreen></iframe>';
										} else {
											html = '<div class="alert alert-warning"><?php echo addslashes(JText::_("COM_ICAGENDA_MAPS_FILL_IN_ADDRESS_ALERT")); ?></div>';
										}
										document.getElementById('embed_map').innerHTML = html;
									}
								</script>
							</div>

						<!-- Google Maps Javascript API -->
						<?php elseif ($maps_service == '3') : ?>
							<div class="span6 iCleft">

								<h3><?php echo JText::_('COM_ICAGENDA_GOOGLE_MAPS_SUBTITLE_LBL'); ?></h3>
								<div>
									<?php echo JText::_('COM_ICAGENDA_GOOGLE_MAPS_NOTE1'); ?>
									<br/>
									<?php echo JText::_('COM_ICAGENDA_GOOGLE_MAPS_NOTE2'); ?><br/>
								</div>
								<!--div class='clearfix'-->
								<div class="ic-map-box">
									<div class="control-group">
										<div class="control-label">
											<?php echo $this->form->getLabel('address'); ?>
										</div>
										<div class="controls">
											<?php echo $this->form->getInput('address'); ?>
										</div>
									</div>
									<div class="icmap-field">
										<?php echo $this->form->getInput('city'); ?>
									</div>
									<div class="icmap-field">
										<?php echo $this->form->getInput('country'); ?>
									</div>
									<div class="icmap-field">
										<?php echo $this->form->getInput('lat'); ?>
									</div>
									<div class="icmap-field">
										<?php echo $this->form->getInput('lng'); ?>
									</div>
									<!--label>District: </label> <input id="administrative_area_level_2" disabled=disabled> <br/>
									<label>State/Province: </label> <input id="administrative_area_level_1" disabled=disabled> <br/-->
									<!--label>route: </label> <input id="route"> <br/>
									<label>Postal Code: </label> <input id="postal_code" disabled=disabled> <br/>
									<label>type: </label> <input id="type" disabled=disabled> <br/-->
								</div>
							</div>
							<div class="span6 iCleft">
								<div class="ic-map-container">
									<div class="ic-reverse-geocode text-end">
										<label id="geo_label" for="reverseGeocode"><?php echo JText::_('COM_ICAGENDA_GOOGLE_MAPS_REVERSE'); ?></label>
										<select id="reverseGeocode" class="input-mini">
											<option value="false" selected><?php echo JText::_('JNO'); ?></option>
											<option value="true"><?php echo JText::_('JYES'); ?></option>
										</select>
									</div>
									<br />
									<div id="map" class="ic-map-wrapper"></div>
									<div id="legend" class="ic-map-legend"><?php echo JText::_('COM_ICAGENDA_GOOGLE_MAPS_LEGEND'); ?></div>
								</div>
							</div>

							<!--div class='input-positioned'>
								<label>Callback: </label>
								<textarea id='callback_result' rows="15"></textarea>
							</div-->
						<?php endif; ?>
					</div>
				</div>
				<?php echo JHtml::_($endPanel); ?>

				<!-- Panel Registrations -->
				<?php echo JHtml::_($addPanel, 'icTab', $RegTag1, $RegTag2); ?>
				<div class="icpanel iCleft">
					<h2>
						<?php echo JText::_('COM_ICAGENDA_REGISTRATIONS_LABEL'); ?>
					</h2>
					<hr />
					<?php foreach ($this->form->getFieldset('registration') as $field) : ?>
						<?php echo $field->renderField(); ?>
					<?php endforeach; ?>
					<?php foreach ($this->form->getFieldset('registration_tickets') as $field) : ?>
						<?php echo $field->renderField(); ?>
					<?php endforeach; ?>
							<?php foreach ($this->form->getFieldset('registration_actions') as $field) : ?>
								<?php echo $field->renderField(); ?>
							<?php endforeach; ?>
					<div class="row-fluid">
						<div class="span6 iCleft">
							<?php foreach ($this->form->getFieldset('registration_options') as $field) : ?>
								<?php echo $field->renderField(); ?>
							<?php endforeach; ?>
						</div>
						<div class="span6 iCleft">
							<?php foreach ($this->form->getFieldset('registration_button') as $field) : ?>
								<?php echo $field->renderField(); ?>
							<?php endforeach; ?>
						</div>
					</div>
					<?php foreach ($paramsfields as $name => $fieldSet) : ?>
						<?php if ( ! in_array($name, array('frontend', 'options', 'publishing', 'registration', 'registration_options', 'registration_button', 'registration_tickets', 'registration_actions'))) : ?>
							<?php foreach ($this->form->getFieldset($name) as $field) : ?>
								<?php echo $field->renderField(); ?>
							<?php endforeach; ?>
						<?php endif; ?>
					<?php endforeach; ?>
				</div>
				<?php echo JHtml::_($endPanel); ?>

				<!-- Panel Registrations -->
				<?php //echo JHtml::_($addPanel, 'icTab', $RegTag1, $RegTag2); ?>
				<!--div class="icpanel iCleft">
					<h2>
						<?php echo JText::_('COM_ICAGENDA_REGISTRATIONS_LABEL'); ?>
					</h2>
					<hr />
					<div class="row-fluid">
						<?php foreach ($paramsfields as $name => $fieldSet) : ?>
							<?php if ( ! in_array($name, array('frontend', 'options', 'publishing'))) : ?>
								<?php if (isset($fieldSet->description) && trim($fieldSet->description)) : ?>
									<p class="tip"><?php echo $this->escape(JText::_($fieldSet->description));?></p>
								<?php endif; ?>
								<div class="span6 iCleft">
									<h3><?php echo $this->escape(JText::_($fieldSet->label)); ?></h3>
									<?php foreach ($this->form->getFieldset($name) as $field) : ?>
										<?php echo $field->renderField(); ?>
									<?php endforeach; ?>
								</div>
							<?php endif; ?>
						<?php endforeach; ?>
					</div>
				</div-->
				<?php //echo JHtml::_($endPanel); ?>

				<!-- Panel Options -->
				<?php if ($this->form->renderFieldset('options')) : ?>
				<?php echo JHtml::_($addPanel, 'icTab', $OptionsTag1, $OptionsTag2); ?>
				<div class="icpanel iCleft">
					<h2>
						<?php echo JText::_('JOPTIONS'); ?>
					</h2>
					<hr />
					<div class="row-fluid">
						<?php foreach ($paramsfields as $name => $fieldSet) : ?>
							<?php if ($name == 'options') : ?>
								<?php if (isset($fieldSet->description) && trim($fieldSet->description)) : ?>
									<p class="tip"><?php echo $this->escape(JText::_($fieldSet->description));?></p>
								<?php endif; ?>
								<div class="span6 iCleft">
									<h3><?php echo $this->escape(JText::_($fieldSet->label)); ?></h3>
									<?php foreach ($this->form->getFieldset($name) as $field) : ?>
										<div class="control-group">
											<div class="control-label">
												<?php echo $field->label; ?>
											</div>
											<div class="controls">
												<?php
												$language = JFactory::getLanguage();
												$language->load('com_icagenda', JPATH_SITE, 'en-GB', true);
												$language->load('com_icagenda', JPATH_SITE, null, true);
												echo $field->input;
												?>
											</div>
										</div>
									<?php endforeach; ?>
								</div>
							<?php endif; ?>
						<?php endforeach; ?>
					</div>
				</div>
				<?php echo JHtml::_($endPanel); ?>
				<?php endif; ?>

				<!-- Panel Publishing -->
				<?php echo JHtml::_($addPanel, 'icTab', $PubTag1, $PubTag2); ?>
				<div class="icpanel iCleft">
					<h2>
						<?php echo JText::_('JGLOBAL_FIELDSET_PUBLISHING'); ?>
					</h2>
					<hr />
					<div class="row-fluid">
						<?php foreach ($paramsfields as $name => $fieldSet) : ?>
							<?php if ($name == 'publishing') : ?>
								<?php if (isset($fieldSet->description) && trim($fieldSet->description)) : ?>
									<p class="tip"><?php echo $this->escape(JText::_($fieldSet->description));?></p>
								<?php endif; ?>
								<div class="iCleft">
									<h3><?php echo $this->escape(JText::_($fieldSet->label)); ?></h3>
									<?php foreach ($this->form->getFieldset($name) as $field) : ?>
										<?php echo $field->renderField(); ?>
									<?php endforeach; ?>
								</div>
							<?php endif; ?>
						<?php endforeach; ?>
					</div>
					<div class="row-fluid">
						<div class="span6 iCleft">
							<div class="control-group">
								<div class="control-label">
									<?php echo $this->form->getLabel('alias'); ?>
								</div>
								<div class="controls">
									<?php echo $this->form->getInput('alias'); ?>
								</div>
							</div>
							<div class="control-group">
								<div class="control-label">
									<?php echo $this->form->getLabel('id'); ?>
								</div>
								<div class="controls">
									<?php echo $this->form->getInput('id'); ?>
								</div>
							</div>
							<div class="control-group">
								<div class="control-label">
									<?php echo $this->form->getLabel('created'); ?>
								</div>
								<div class="controls">
									<?php echo $this->form->getInput('created'); ?>
								</div>
							</div>
							<div class="control-group">
								<div class="control-label">
									<?php echo $this->form->getLabel('created_by'); ?>
								</div>
								<div class="controls">
									<?php echo $this->form->getInput('created_by'); ?>
								</div>
							</div>
							<div class="control-group">
								<div class="control-label">
									<?php echo $this->form->getLabel('created_by_alias'); ?>
								</div>
								<div class="controls">
									<?php echo $this->form->getInput('created_by_alias'); ?>
								</div>
							</div>
							<?php if (empty($this->item->site_itemid)) : ?>
								<div class="control-group">
									<div class="control-label">
										<?php echo $this->form->getLabel('created_by_email'); ?>
									</div>
									<div class="controls">
										<?php echo $this->form->getInput('created_by_email'); ?>
									</div>
								</div>
							<?php endif; ?>
							<div class="control-group">
								<div class="control-label">
									<?php echo $this->form->getLabel('modified'); ?>
								</div>
								<div class="controls">
									<?php echo $this->form->getInput('modified'); ?>
								</div>
							</div>
							<div class="control-group">
								<div class="control-label">
									<?php echo $this->form->getLabel('modified_by'); ?>
								</div>
								<div class="controls">
									<?php echo $this->form->getInput('modified_by'); ?>
								</div>
							</div>
							<div class="control-group">
								<div class="control-label">
									<?php echo $this->form->getLabel('checked_out'); ?>
								</div>
								<div class="controls">
									<?php echo $this->form->getInput('checked_out'); ?>
								</div>
							</div>
							<div class="control-group">
								<div class="control-label">
									<?php echo $this->form->getLabel('checked_out_time'); ?>
								</div>
								<div class="controls">
									<?php echo $this->form->getInput('checked_out_time'); ?>
								</div>
							</div>
							<?php if (!empty($this->item->site_itemid)) : ?>
								<h2>
									<?php echo $this->escape(JText::_('COM_ICAGENDA_FORM_FRONTEND_OPTIONS'));?>
								</h2>
								<hr />
								<div class="control-group">
									<div class="control-label">
										<?php echo $this->form->getLabel('site_itemid'); ?>
									</div>
									<div class="controls">
										<?php echo $this->form->getInput('site_itemid'); ?>
									</div>
								</div>
								<div class="control-group">
									<div class="control-label">
										<?php echo $this->form->getLabel('username'); ?>
									</div>
									<div class="controls">
										<?php echo $this->form->getInput('username'); ?>
									</div>
								</div>
								<div class="control-group">
									<div class="control-label">
										<?php echo $this->form->getLabel('created_by_email'); ?>
									</div>
									<div class="controls">
										<?php echo $this->form->getInput('created_by_email'); ?>
									</div>
								</div>
							<?php endif; ?>
						</div>
					</div>
				</div>
				<?php echo JHtml::_($endPanel); ?>

				<?php echo JHtml::_($endPane, 'icTab'); ?>
			</div>

			<!-- Begin Sidebar -->
			<div class="span2 iCleft">
				<h3>
					<?php echo JText::_('COM_ICAGENDA_TITLE_SIDEBAR_DETAILS'); ?>
				</h3>
				<hr />
				<div class="control-group">
					<div class="control-label">
						<?php echo $this->form->getLabel('state'); ?>
					</div>
					<div class="controls">
						<?php echo $this->form->getInput('state'); ?>
					</div>
				</div>
				<?php //if (empty($this->item->site_itemid)) : ?>
					<?php //$this->form->setFieldAttribute('approval', 'type', 'hidden'); ?>
					<?php //echo $this->form->getInput('approval'); ?>
				<?php //else : ?>
					<div class="control-group">
						<div class="control-label">
							<?php echo $this->form->getLabel('approval'); ?>
						</div>
						<div class="controls">
							<?php echo $this->form->getInput('approval'); ?>
						</div>
					</div>
				<?php //endif; ?>
				<div class="control-group">
					<div class="control-label">
						<?php echo $this->form->getLabel('access'); ?>
					</div>
					<div class="controls">
						<?php echo $this->form->getInput('access'); ?>
					</div>
				</div>
				<div class="control-group">
					<div class="control-label">
						<?php echo $this->form->getLabel('language'); ?>
					</div>
					<div class="controls">
						<?php echo $this->form->getInput('language'); ?>
					</div>
				</div>
				<div class="control-group">
					<div class="control-label">
						<?php echo $this->form->getLabel('hits'); ?>
					</div>
					<div class="controls">
						<?php echo $this->form->getInput('hits'); ?>
					</div>
				</div>
			</div>
			<!-- End Sidebar -->
		</div>
		<div class="clr"></div>
	</div>
	<div>
		<input type="hidden" name="task" value="" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>


<?php // Maps service ?>
<?php if ($maps_service == '3') : ?>
	<script type="text/javascript">
		//<![CDATA[
		var iCmapDisplay = '<?php echo $iCmapDisplay; ?>';

		jQuery(function($) {
			// Tabs
			if (iCmapDisplay=='1') {
				$iCgvar='a[href="#icmap"]';
				$iCmapShow='shown';
			}
			if (iCmapDisplay=='3') {
				$iCgvar='.icmap';
				$iCmapShow='click';
			}
			// Slides
			if (iCmapDisplay=='2') {
				$iCgvar='#icmap';
				$iCmapShow='shown';
			}

			$(''+$iCgvar+'').on(''+$iCmapShow+'', function() {   // When tab is displayed...

				var addresspicker = $( "#addresspicker" ).addresspicker();
				var addresspickerMap = $( '#jform_address' ).addresspicker({
					regionBias: "fr",
					updateCallback: showCallback,
					mapOptions: {
						zoom: <?php echo $zoom; ?>,
						center: new google.maps.LatLng(<?php echo $coords; ?>),
						scrollwheel: false,
						mapTypeId: google.maps.MapTypeId.<?php echo $mapTypeId; ?>,
						streetViewControl: false
					},
					elements: {
						map: "#map",
						lat: "#lat",
						lng: "#lng",
						street_number: '#street_number',
						route: '#route',
						locality: '#locality',
						administrative_area_level_2: '#administrative_area_level_2',
						administrative_area_level_1: '#administrative_area_level_1',
						country: '#country',
						postal_code: '#postal_code',
						type: '#type',
					}
				});

				var gmarker = addresspickerMap.addresspicker( "marker");
				gmarker.setVisible(true);
				addresspickerMap.addresspicker( "updatePosition");

				$('#reverseGeocode').change(function(){
					$("#jform_address").addresspicker("option", "reverseGeocode", ($(this).val() === 'true'));
				});

				function showCallback(geocodeResult, parsedGeocodeResult){
					$('#callback_result').text(JSON.stringify(parsedGeocodeResult, null, 4));
				}
			});
		});
		//]]>
	</script>

	<?php // Google Maps Javascript API V3
		icagendaMaps::loadGMapScripts('edit');
	?>
<?php endif; ?>

<?php
// Script validation for Event Edit form (2)
//	$iCheckForm = icagendaForm::submit(2);
//	$document->addScriptDeclaration($iCheckForm);
