<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.9.0 2024-02-24
 *
 * @package     iCagenda.Admin
 * @subpackage  tmpl.event
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

use iCutilities\Customfields\Customfields as icagendaCustomfields;
use iCutilities\Maps\Maps as icagendaMaps;
use iCutilities\Maps\Leaflet\Search as icagendaMapsLeafletSearch;
use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Router\Route;

/** @var Joomla\CMS\WebAsset\WebAssetManager $wa */
$wa = $this->document->getWebAssetManager();
$wa->getRegistry()->addExtensionRegistryFile('com_contenthistory');
$wa->useScript('keepalive')
	->useScript('form.validate')
	->useScript('com_contenthistory.admin-history-versions');

// Script files which could be overridden into your admin template. (eg. /templates/my_template/css/com_icagenda/icagenda.css)
HTMLHelper::_('script', 'com_icagenda/icform.js', array('relative' => true, 'version' => 'auto'), array('async' => 'async'));

$app      = Factory::getApplication();
$document = Factory::getDocument();
$input    = $app->input;

// In case of modal
$isModal = $input->get('layout') === 'modal';
$layout  = $isModal ? 'modal' : 'edit';
$tmpl    = $isModal || $input->get('tmpl', '', 'cmd') === 'component' ? '&tmpl=component' : '';

$params = $this->state->get('params');

$maps_service = $params->get('maps_service', 1);

$ic_style = 'div.tip img.media-preview {display:none}';
$document->addStyleDeclaration($ic_style);

// Styles for multiple dates main Add Button
$add_date_btn_style = '.subform-repeatable > .btn-toolbar .group-add:after { content: " ' . Text::_('JGLOBAL_FIELD_ADD') . ' "; font-weight: bold; } .subform-repeatable > .btn-toolbar .group-add {width: 100%; margin-bottom: 20px;}';
$document->addStyleDeclaration($add_date_btn_style);

// Google Map
$zoom      = $this->item->address ? '16' : '1';
$mapTypeId = 'ROADMAP'; // HYBRID, ROADMAP, SATELLITE, TERRAIN
$coords    = '0, 0';
$lat       = $this->item->lat;
$lng       = $this->item->lng;
// Notes: zoomControl: false, mapTypeControl: false

$isNew         = ((int) $this->item->id === 0);
$newEventValue = $isNew ? '1' : '0';
$editLabel     = $isNew ? Text::_('COM_ICAGENDA_LEGEND_NEW_EVENT') : Text::_('COM_ICAGENDA_LEGEND_EDIT_EVENT');
?>

<form action="<?php echo Route::_('index.php?option=com_icagenda&layout=' . $layout . $tmpl . '&id=' . (int) $this->item->id); ?>" method="post" name="adminForm" id="event-form" aria-label="<?php echo $editLabel; ?>" class="form-validate" enctype="multipart/form-data">

	<div class="ic-edit-header-title d-none d-lg-block">
		<?php echo $editLabel; ?> <span>iCagenda</span>
	</div>

	<?php echo LayoutHelper::render('joomla.edit.title_alias', $this); ?>

	<div class="main-card">
		<?php echo HTMLHelper::_('uitab.startTabSet', 'icTab', ['active' => 'event', 'recall' => true, 'breakpoint' => 768]); ?>
		<?php echo HTMLHelper::_('uitab.addTab', 'icTab', 'event', Text::_('COM_ICAGENDA_TITLE_EVENT')); ?>

		<div class="row">
			<div class="col-lg-9">
				<?php echo $this->form->renderField('image'); ?>
			</div>
			<div class="col-lg-3">
				<?php
				$globalFields = clone $this;
//				$globalFields->fields = empty($this->item->site_itemid)
//										? array('catid', 'state', 'access', 'language', 'note', 'version_note')
//										: array('catid', 'state', 'approval', 'access', 'language', 'note', 'version_note');
				$globalFields->fields = array('catid', 'state', 'approval', 'access', 'language', 'note', 'version_note');
				?>
				<?php echo LayoutHelper::render('joomla.edit.global', $globalFields); ?>
				<?php //if (empty($this->item->site_itemid)) : ?>
					<?php //$this->form->setFieldAttribute('approval', 'type', 'hidden'); ?>
					<?php //echo $this->form->getInput('approval'); ?>
				<?php //endif; ?>
			</div>
		</div>

		<?php echo HTMLHelper::_('uitab.endTab'); ?>

		<?php echo HTMLHelper::_('uitab.addTab', 'icTab', 'dates', Text::_('COM_ICAGENDA_LEGEND_DATES')); ?>

		<div class="row">
			<div class="col-12">
				<fieldset id="fieldset-perioddata" class="options-form">
					<legend><?php echo Text::_('COM_ICAGENDA_LEGEND_PERIOD_DATES'); ?></legend>
					<section>
						<div class="row">
							<div class="col-lg-3">
								<?php echo $this->form->renderField('startdate'); ?>
							</div>
							<div class="col-lg-3">
								<?php echo $this->form->renderField('enddate'); ?>
							</div>
							<div class="col-lg-6">
								<?php echo $this->form->renderField('weekdays'); ?>
								<br />
								<?php echo $this->form->renderField('weekdays_note'); ?>
							</div>
						</div>
					</section>
				</fieldset>
				<fieldset id="fieldset-singledatesdata" class="options-form">
					<legend><?php echo Text::_('COM_ICAGENDA_LEGEND_SINGLE_DATES'); ?></legend>
					<div class="col-12">
						<?php echo $this->form->getInput('dates'); ?>
					</div>
				</fieldset>
				<fieldset id="fieldset-datesoptionsdata" class="options-form">
					<legend><?php echo Text::_('JOPTIONS'); ?></legend>
					<?php echo $this->form->renderField('displaytime'); ?>
				</fieldset>
			</div>
		</div>

		<?php echo HTMLHelper::_('uitab.endTab'); ?>

		<?php echo HTMLHelper::_('uitab.addTab', 'icTab', 'desc', Text::_('COM_ICAGENDA_LEGEND_DESC')); ?>

		<div class="row">
			<div class="col-12">
				<fieldset id="fieldset-shortdescdata" class="options-form">
					<legend><?php echo Text::_('COM_ICAGENDA_FORM_EVENT_SHORT_DESCRIPTION_LBL'); ?></legend>
					<?php echo $this->form->renderField('shortdesc_note'); ?>
					<?php echo $this->form->getInput('shortdesc'); ?>
				</fieldset>
				<fieldset id="fieldset-descdata" class="options-form">
					<legend><?php echo Text::_('COM_ICAGENDA_FORM_DESC_EVENT_DESC'); ?></legend>
					<?php echo $this->form->getInput('desc'); ?>
				</fieldset>
				<fieldset id="fieldset-metadescdata" class="options-form">
					<legend><?php echo Text::_('COM_ICAGENDA_FORM_EVENT_METADESC_LBL'); ?></legend>
					<?php echo $this->form->renderField('metadesc_note'); ?>
					<?php echo $this->form->getInput('metadesc'); ?>
				</fieldset>
			</div>
		</div>

		<?php echo HTMLHelper::_('uitab.endTab'); ?>

		<?php echo HTMLHelper::_('uitab.addTab', 'icTab', 'infos', Text::_('COM_ICAGENDA_LEGEND_INFORMATION')); ?>

		<div class="row">
			<div class="col-12">
				<div class="row">
					<div class="col-lg-6">
						<fieldset id="fieldset-venuedata" class="options-form">
							<legend><?php echo Text::_('COM_ICAGENDA_EVENT_GLOBAL_INFO_TITLE'); ?></legend>
							<?php echo $this->form->renderField('place'); ?>
							<?php echo $this->form->renderField('website'); ?>
						</fieldset>
						<fieldset id="fieldset-contactdata" class="options-form">
							<legend><?php echo Text::_('COM_ICAGENDA_LEGEND_CONTACT'); ?></legend>
							<?php echo $this->form->renderField('email'); ?>
							<?php echo $this->form->renderField('phone'); ?>
						</fieldset>
						<fieldset id="fieldset-filedata" class="options-form">
							<legend><?php echo Text::_('COM_ICAGENDA_EVENT_ATTACHMENTS_TITLE'); ?></legend>
							<?php echo $this->form->renderField('file'); ?>
						</fieldset>
					</div>
					<div class="col-lg-6">
						<fieldset id="fieldset-filedata" class="options-form">
							<legend><?php echo Text::_('COM_ICAGENDA_LEGEND_FEATURES'); ?></legend>
							<?php echo $this->form->renderField('features'); ?>
						</fieldset>
						<fieldset id="fieldset-filedata" class="options-form">
							<legend><?php echo Text::_('COM_ICAGENDA_CUSTOMFIELDS'); ?></legend>
							<?php echo icagendaCustomfields::loader(2); // Load Custom fields - Event form (2) ?>
						</fieldset>
					</div>
				</div>
			</div>
		</div>

		<?php echo HTMLHelper::_('uitab.endTab'); ?>

		<?php echo HTMLHelper::_('uitab.addTab', 'icTab', 'icmap', Text::_('COM_ICAGENDA_LEGEND_MAP')); ?>

		<div class="row">
			<div class="col-12">
				<div class="row">
					<!--fieldset id="fieldset-venuedata" class="options-form">
						<legend><?php echo Text::_('COM_ICAGENDA_LEGEND_MAP'); ?></legend-->
						<?php if ( ! $maps_service) : ?>
							<div class="col-12">
								<div class="ic-map-box">
									<?php echo $this->form->renderField('address'); ?>
								</div>
								<div class="alert alert-warning">
									<?php echo Text::_('COM_ICAGENDA_MAPS_SERVICE_NOT_SET'); ?>
								</div>
							</div>

						<!-- LeafLet OpenStreetMap -->
						<?php elseif ($maps_service == '1') : ?>
							<div class="row">
								<div class="col-lg-12 icmap-address">
									<fieldset id="fieldset-addressmapdata" class="options-form">
									<legend><?php echo $this->form->getLabel('address'); ?></legend>
									<div id="findbox"></div>
									<?php echo $this->form->getInput('address'); ?>
									<div class="clearfix"></div>
									<?php echo $this->form->renderField('address_note'); ?>
									</fieldset>
								</div>
							</div>
							<br />
							<div class="row">
								<div class="col-lg-4">
									<fieldset id="fieldset-detailsmapdata" class="options-form">
										<legend><?php echo Text::_('JDETAILS'); ?></legend>
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
									</fieldset>
								</div>
								<div class="col-lg-8">
									<div class="ic-map-container">
										<div class="ic-map-wrapper" id="ic-map-<?php echo (int) $this->item->id; ?>"></div>
									</div>
								</div>
							</div>
							<?php icagendaMapsLeafletSearch::addJS('ic-map-' . (int) $this->item->id, 'jform_address', array('tab' => '#icmap')); ?>

						<!-- Google Maps Embed API -->
						<?php elseif ($maps_service == '2') : ?>
							<div class="row">
								<div class="col-lg-12">
									<fieldset id="fieldset-addressmapdata" class="options-form">
										<legend><?php echo $this->form->getLabel('address'); ?></legend>
										<div class="input-group">
											<?php echo $this->form->getInput('address'); ?>
											<button class="btn btn-primary" type="button" onclick="myFunction(document.getElementById('jform_address').value)"><?php echo Text::_('IC_CHECK'); ?></button>
										</div>
										<?php echo $this->form->renderField('address_note'); ?>
									</fieldset>
								</div>
							</div>
							<div class="row">
								<div id="embed_map" class="ic-map-embed">
									<?php if ($this->item->address) : ?>
										<?php echo '<iframe width="100%" height="100%" frameborder="0" style="border:0;" class="ic-map-iframe" src="https://www.google.com/maps/embed/v1/place?key=' . $params->get('googlemaps_embed_key', '') . '&q=' . urlencode(strip_tags($this->item->address)) . '" allowfullscreen></iframe>'; ?>
									<?php endif; ?>
								</div>
							</div>
							<script>
								function myFunction(address) {
									var embedKey = "<?php echo $params->get('googlemaps_embed_key', ''); ?>";
									if (address){ 
										html = '<iframe width="100%" height="100%" frameborder="0" style="border:0;" class="ic-map-iframe" src="https://www.google.com/maps/embed/v1/place?key=' + embedKey + '&q=' + encodeURI(address) + '" allowfullscreen></iframe>';
									} else {
										html = '<div class="alert alert-warning"><?php echo addslashes(Text::_("COM_ICAGENDA_MAPS_FILL_IN_ADDRESS_ALERT")); ?></div>';
									}
									document.getElementById('embed_map').innerHTML = html;
								}
							</script>


						<!-- Google Maps Javascript API -->
						<?php elseif ($maps_service == '3') : ?>
							<h3><?php echo Text::_('COM_ICAGENDA_GOOGLE_MAPS_SUBTITLE_LBL'); ?></h3>
							<div>
								<?php echo Text::_('COM_ICAGENDA_GOOGLE_MAPS_NOTE1'); ?>
							</div>
							<div>
								<?php echo Text::_('COM_ICAGENDA_GOOGLE_MAPS_NOTE2'); ?>
							</div>
							<div class="row">
								<div class="col-lg-12">
									<br />
									<fieldset id="fieldset-addressmapdata" class="options-form">
										<legend><?php echo $this->form->getLabel('address'); ?></legend>
										<?php echo $this->form->getInput('address'); ?>
										<?php echo $this->form->renderField('address_note'); ?>
									</fieldset>
								</div>
							</div>
							<div class="row">
								<div class="col-lg-4">
									<br />
									<fieldset id="fieldset-detailsmapdata" class="options-form">
										<legend><?php echo Text::_('JDETAILS'); ?></legend>
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
									</fieldset>
								</div>
								<div class="col-lg-8">
									<div class="ic-map-container">
										<div class="ic-reverse-geocode text-end mb-2">
											<label id="geo_label" for="reverseGeocode"><?php echo Text::_('COM_ICAGENDA_GOOGLE_MAPS_REVERSE'); ?></label>
											<select id="reverseGeocode">
												<option value="false" selected><?php echo Text::_('JNO'); ?></option>
												<option value="true"><?php echo Text::_('JYES'); ?></option>
											</select>
										</div>
										<div id="map" class="ic-map-wrapper"></div>
										<div id="legend" class="ic-map-legend"><?php echo Text::_('COM_ICAGENDA_GOOGLE_MAPS_LEGEND'); ?></div>
									</div>
								</div>
							</div>
							<!--div class='input-positioned'>
								<label>Callback: </label>
								<textarea id='callback_result' rows="15"></textarea>
							</div-->
						<?php endif; ?>
					<!--/fieldset-->
				</div>
			</div>
		</div>

		<?php echo HTMLHelper::_('uitab.endTab'); ?>

		<?php echo HTMLHelper::_('uitab.addTab', 'icTab', 'registrations', Text::_('COM_ICAGENDA_REGISTRATIONS_LABEL')); ?>
		<?php $fieldSets = $this->form->getFieldsets(); ?>
		<div class="row">
			<div class="col-12">
				<fieldset id="fieldset-registration-data" class="options-form">
					<legend><?php echo Text::_('COM_ICAGENDA_REGISTRATION_LABEL'); ?></legend>
					<?php echo $this->form->renderField('statutReg', 'params'); ?>
					<?php echo $this->form->renderField('accessReg', 'params'); ?>
				</fieldset>
			</div>
		</div>
		<div class="row">
			<div class="col-12">
				<fieldset id="fieldset-tickets-data" class="options-form">
					<legend><?php echo Text::_('COM_ICAGENDA_REGISTRATION_TICKETS_LABEL'); ?></legend>
					<div class="row">
						<div class="col-lg-8">
							<?php echo $this->form->renderField('maxReg', 'params'); ?>
						</div>
						<div class="col-lg-4">
							<?php echo $this->form->getInput('typeReg', 'params'); ?>
						</div>
					</div>
					<?php echo $this->form->renderField('maxRlistGlobal', 'params'); ?>
					<?php echo $this->form->renderField('maxRlist', 'params'); ?>
					<!--div class="row">
						<?php //echo $this->form->renderField('tickets_note', 'params'); ?>
					</div-->
				</fieldset>
			</div>
		</div>
		<div class="row">
			<div class="col-12">
				<?php foreach($fieldSets as $fieldset) : ?>
					<?php if ($fieldset->name == 'registration_actions') : ?>
						<?php $fields = $this->form->getFieldset($fieldset->name);?>
						<?php if (count($fields) > 0):?>
							<fieldset id="fieldset-<?php echo $fieldset->name; ?>" class="options-form">
								<?php if (isset($fieldset->label) && $fieldset->label != '') : ?>
									<legend><?php echo Text::_($fieldset->label); ?></legend>
								<?php endif;?>
								<?php echo $this->form->renderFieldset($fieldset->name); ?>
							</fieldset>
						<?php endif; ?>
					<?php endif; ?>
				<?php endforeach;?>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-6">
				<?php foreach($fieldSets as $fieldset) : ?>
					<?php if ($fieldset->name == 'registration_options') : ?>
						<?php $fields = $this->form->getFieldset($fieldset->name);?>
						<?php if (count($fields)):?>
							<fieldset id="fieldset-<?php echo $fieldset->name; ?>" class="options-form">
								<?php if (isset($fieldset->label) && $fieldset->label != '') : ?>
									<legend><?php echo Text::_($fieldset->label); ?></legend>
								<?php endif;?>
								<?php echo $this->form->renderFieldset($fieldset->name); ?>
							</fieldset>
						<?php endif; ?>
					<?php endif; ?>
				<?php endforeach;?>
			</div>
			<div class="col-lg-6">
				<?php foreach($fieldSets as $fieldset) : ?>
					<?php if ($fieldset->name == 'registration_button') : ?>
						<?php $fields = $this->form->getFieldset($fieldset->name);?>
						<?php if (count($fields)):?>
							<fieldset id="fieldset-<?php echo $fieldset->name; ?>" class="options-form">
								<?php if (isset($fieldset->label) && $fieldset->label != '') : ?>
									<legend><?php echo Text::_($fieldset->label); ?></legend>
								<?php endif;?>
								<?php echo $this->form->renderFieldset($fieldset->name); ?>
							</fieldset>
						<?php endif; ?>
					<?php endif; ?>
				<?php endforeach;?>
			</div>

			<?php foreach ($this->form->getFieldsets('params') as $name => $fieldSet) : ?>
				<?php if ( ! in_array($name, array('frontend', 'options', 'publishing', 'registration', 'registration_options', 'registration_button', 'registration_tickets', 'registration_actions'))) : ?>
					<?php foreach ($this->form->getFieldset($name) as $field) : ?>
						<?php echo $field->renderField(); ?>
					<?php endforeach; ?>
				<?php endif; ?>
			<?php endforeach; ?>
		</div>

<script>
	var statutReg = "<?php echo $params->get('statutReg', '0'); ?>";
	var registration_tickets = document.getElementById("fieldset-tickets-data");
	var registration_actions = document.getElementById("fieldset-registration_actions");
	var registration_options = document.getElementById("fieldset-registration_options");
	var registration_button = document.getElementById("fieldset-registration_button");

    function checkRegStatus(event) {
        if (event.target.value == "0" || (event.target.value == "" && statutReg != "1")) {
			registration_tickets.style.display = "none";       	
			registration_actions.style.display = "none";       	
			registration_options.style.display = "none";       	
			registration_button.style.display = "none";       	
        } else {
			registration_tickets.style.display = "block";       	
			registration_actions.style.display = "block";       	
			registration_options.style.display = "block";       	
			registration_button.style.display = "block";       	
        }
    }
    document.querySelectorAll("input[name='jform[params][statutReg]']").forEach((input) => {

        if ((input.value == "" && input.checked && statutReg == "1") || (input.value == "1" && input.checked)) {
			registration_tickets.style.display = "block";       	
 			registration_actions.style.display = "block";       	
 			registration_options.style.display = "block";       	
 			registration_button.style.display = "block";       	
		}
		if ((input.value == "" && input.checked && statutReg != "1") || (input.value == "0" && input.checked)) {
			registration_tickets.style.display = "none";       	
 			registration_actions.style.display = "none";       	
 			registration_options.style.display = "none";       	
 			registration_button.style.display = "none";       	
		}
		input.addEventListener("change", checkRegStatus);
    });
</script>

		<?php echo HTMLHelper::_('uitab.endTab'); ?>

		<?php if ($this->form->renderFieldset('options')) : ?>
			<?php echo HTMLHelper::_('uitab.addTab', 'icTab', 'options', Text::_('JOPTIONS')); ?>
			<div class="row">
				<div class="col-12">
					<fieldset id="fieldset-optionsdata" class="options-form">
						<legend><?php echo Text::_('JOPTIONS'); ?></legend>
						<div class="col-lg-6">
							<?php echo $this->form->renderFieldset('options'); ?>
						</div>
					</fieldset>
				</div>
			</div>
			<?php echo HTMLHelper::_('uitab.endTab'); ?>
		<?php endif; ?>

		<?php echo HTMLHelper::_('uitab.addTab', 'icTab', 'publishing', Text::_('JGLOBAL_FIELDSET_PUBLISHING')); ?>
		<div class="row">
			<div class="col-lg-6">
				<fieldset id="fieldset-publishingdata" class="options-form">
					<legend><?php echo Text::_('JGLOBAL_FIELDSET_PUBLISHING'); ?></legend>
					<?php echo LayoutHelper::render('joomla.edit.publishingdata', $this); ?>
					<?php if (empty($this->item->site_itemid)) : ?>
						<?php //$this->form->setFieldAttribute('created_by_email', 'type', 'hidden'); ?>
						<?php //echo $this->form->getInput('created_by_email'); ?>
						<?php echo $this->form->renderField('created_by_email'); ?>
					<?php endif; ?>
				</fieldset>
			</div>
			<div class="col-lg-6">
				<?php foreach($fieldSets as $fieldset) : ?>
					<?php if ($fieldset->name == 'publishing') : ?>
						<?php $fields = $this->form->getFieldset($fieldset->name);?>
						<?php if (count($fields)):?>
							<fieldset id="fieldset-<?php echo $fieldset->name; ?>" class="options-form">
								<?php if (isset($fieldset->label) && $fieldset->label != '') : ?>
									<legend><?php echo Text::_($fieldset->label); ?></legend>
								<?php endif;?>
								<?php //echo $this->form->renderFieldset($fieldset->name); ?>
									<?php foreach ($this->form->getFieldset($fieldset->name) as $field) : ?>
										<?php echo $field->renderField(); ?>
									<?php endforeach; ?>
							</fieldset>
						<?php endif; ?>
					<?php endif; ?>
				<?php endforeach;?>
				<?php if ( ! empty($this->item->site_itemid)) : ?>
					<fieldset id="fieldset-itemiddata" class="options-form">
						<legend><?php echo Text::_('COM_ICAGENDA_FORM_FRONTEND_OPTIONS'); ?></legend>
						<?php echo $this->form->renderField('site_itemid'); ?>
						<?php echo $this->form->renderField('username'); ?>
						<?php echo $this->form->renderField('created_by_email'); ?>
					</fieldset>
				<?php endif; ?>
			</div>
		</div>

		<?php echo HTMLHelper::_('uitab.endTab'); ?>

		<?php echo HTMLHelper::_('uitab.endTabSet'); ?>

		<input type="hidden" name="task" value="">
		<?php echo HTMLHelper::_('form.token'); ?>
	</div>
</form>


<?php // Maps service ?>
<?php if ($maps_service == '3') : ?>
	<?php
	HTMLHelper::_('jquery.framework');
	?>
	<script type="text/javascript">
		//<![CDATA[

		jQuery(function($) {
//			$("a[href='#icmap']").on("shown", function() {   // When tab is displayed...
				var addresspicker = $("#addresspicker").addresspicker();
				var addresspickerMap = $("#jform_address").addresspicker({
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
						street_number: "#street_number",
						route: "#route",
						locality: "#locality",
						administrative_area_level_2: "#administrative_area_level_2",
						administrative_area_level_1: "#administrative_area_level_1",
						country: "#country",
						postal_code: "#postal_code",
						type: "#type",
					}
				});

				var gmarker = addresspickerMap.addresspicker( "marker");
				gmarker.setVisible(true);
				addresspickerMap.addresspicker( "updatePosition");

				$("#reverseGeocode").change(function(){
					$("#jform_address").addresspicker("option", "reverseGeocode", ($(this).val() === 'true'));
				});

				function showCallback(geocodeResult, parsedGeocodeResult){
					$("#callback_result").text(JSON.stringify(parsedGeocodeResult, null, 4));
				}
//			});
		});
		//]]>
	</script>

	<?php // Google Maps Javascript API V3
		icagendaMaps::loadGMapScripts('edit');
	?>
<?php endif; ?>
