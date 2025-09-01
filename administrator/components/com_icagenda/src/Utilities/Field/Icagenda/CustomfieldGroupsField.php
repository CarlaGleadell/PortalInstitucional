<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.9.0 2024-02-18
 *
 * @package     iCagenda.Admin
 * @subpackage  Utilities.Field.Icagenda
 * @link        https://www.icagenda.com
 *
 * @author      Cyril Rezé
 * @copyright   (c) 2012-2024 Cyril Rezé / iCagenda. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 *
 * @since       3.6
 *----------------------------------------------------------------------------
*/

namespace iCutilities\Field\Icagenda;

use Joomla\CMS\Factory;
use Joomla\CMS\Form\Field\ListField;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

/**
 * Form Field class for iCagenda.
 * Custom Field Groups multiple select form field with Groups Manager.
 */
class CustomfieldGroupsField extends ListField
{
	/**
	 * The form field type.
	 *
	 * @var  string
	 */
	protected $type = 'CustomfieldGroups';

	/**
	 * Name of the layout being used to render the field
	 *
	 * @var  string
	 */
	protected $layout = 'joomla.form.field.list-fancy-select';

	/**
	 * Method to get the field input markup.
	 *
	 * @return  string  The field input markup.
	 */
	protected function getInput()
	{
		HTMLHelper::_('jquery.framework');
		// @todo Move to Vanilla.js

		$jinput = Factory::getApplication()->input;
		$view   = $jinput->get('view', '');
		$id     = $jinput->get('id', '');

		$value = isset($this->value) ? $this->value : '';

		$getOptions = $this->getOptions();

		$options = array();

		foreach ($getOptions as $opt) {
			$options[] = HTMLHelper::_('select.option', $opt->value, $opt->option);
		}

		// Move to Ajax Library
		$fieldid = $this->id;

		$data = $this->getLayoutData();

		$data['options'] = (array) $options;

		$html = '<div id="groups-select">' . $this->getRenderer($this->layout)->render($data) . '</div>';

		// Custom Field Groups Manager
		$html.= '<div id="groups-manager" class="alert alert-info mt-4">'; // Start groups-manager

		$html.= '<strong>' . Text::_('COM_ICAGENDA_CUSTOMFIELD_GROUPS_MANAGER_LABEL') . '</strong><hr />';

		// Add a new group
		$html.= '<div class="ic-group-add-label">' . Text::_('COM_ICAGENDA_CUSTOMFIELD_GROUPS_ADD_LABEL') . '</div>';
		$html.= '<div class="input-group">';
		$html.= '<input id="newgroup" name="a" type="text" class="form-control" aria-label="' . Text::_('COM_ICAGENDA_CUSTOMFIELD_GROUPS_ADD_LABEL') . '" aria-describedby="button-add-group">';
		$html.= '<button class="add-group btn btn-success" type="button" id="button-add-group">' . Text::_('JACTION_CREATE') . '</button>';
		$html.= '</div>';
		$html.= '<div class="add-group-info"></div>';

		// Delete an existing group
		$html.= '<div class="ic-group-delete-label mt-3">' . Text::_('COM_ICAGENDA_CUSTOMFIELD_GROUPS_DELETE_LABEL') . '</div>';
		$html.= '<div class="input-group">';
		$html.= '<select class="form-select" id="deletegroup" aria-label="' . Text::_('JGLOBAL_SELECT_AN_OPTION') . ' ">';
		$html.= '<option value="">' . Text::_('JGLOBAL_SELECT_AN_OPTION') . '</option>';

		foreach ($getOptions as $opt) {
			$html.= '<option value="' . $opt->value . '">' . $opt->option . '</option>';
		}

		$html.= '</select>';
		$html.= '<button class="delete-group btn btn-danger" type="button" id="button-delete-group">' . Text::_('JACTION_DELETE') . '</button>';
		$html.= '</div>';
		$html.= '<div class="delete-group-info"></div>';

		$html.= '</div>'; // End groups-manager
		?>

		<script>
			jQuery(document).ready(function($) {

				var view = '<?php echo $view; ?>',
					id = '<?php echo $id; ?>',
					fieldid = '<?php echo $fieldid; ?>',
					url = 'index.php?option=com_icagenda&task=customfield';

				$(".add-group").on("click",function(e){
					e.preventDefault();

					var newgroup = $('#newgroup').val();

					if (newgroup == "") {
						$(".add-group-info").html('<div class="alert alert-warning alert-small"><?php echo Text::_("COM_ICAGENDA_CUSTOMFIELD_GROUPS_ADD_WARNING"); ?></div>').show().delay(2000).fadeOut(500);
						$(".delete-group-info").hide();
					} else {
						newGroup(url, newgroup, fieldid);
					}
				});

				$(".delete-group").on("click",function(e){
					e.preventDefault();

					var deletegroup = $('#deletegroup').val();

					if (deletegroup == "") {
						$(".delete-group-info").html('<div class="alert alert-warning alert-small"><?php echo Text::_("COM_ICAGENDA_CUSTOMFIELD_GROUPS_DELETE_WARNING"); ?></div>').show().delay(2000).fadeOut(500);
					} else {
						$.ajax({
							url: url+'.checkGroup',
							data : {
								group : deletegroup,
								id : id,
							},
							success: function( output ) {
								if (output) {
									$(".delete-group-info").html('<div class="alert alert-error alert-small"><p><?php echo Text::sprintf("COM_ICAGENDA_CUSTOMFIELD_GROUPS_DELETE_ERROR", "'+output+'"); ?></p><p><strong><?php echo Text::_("COM_ICAGENDA_CUSTOMFIELD_GROUPS_DELETE_ALERT"); ?></strong></p><div id="force-delete" class="btn btn-success"><?php echo Text::_("COM_ICAGENDA_CUSTOMFIELD_GROUPS_DELETE_YES"); ?></div> <div id="no-delete" class="btn btn-danger"><?php echo Text::_("COM_ICAGENDA_CUSTOMFIELD_GROUPS_DELETE_NO"); ?></div></div>').show();
									$("#force-delete").on("click",function(){
										deleteGroup(url, deletegroup, id, fieldid)
									});
									$("#no-delete").on("click",function(){
										$('#deletegroup').val("");
										$(".delete-group-info").fadeOut(500);
									});
								} else {
									deleteGroup(url, deletegroup, id, fieldid)
								}
							},
							error: function() {
								alert("An error occurred");
							}
						});
					}
				});

				function newGroup(url, newgroup, fieldid) {
					$.ajax({
						url: url+'.newGroup',
						data : {
							group : newgroup
						},
						success: function( value ) {
							if (value) {
								$(".add-group-info").html('<div class="alert alert-success alert-small"><p><?php echo Text::sprintf("COM_ICAGENDA_CUSTOMFIELD_GROUPS_ADD_TO_FIELD", "'+newgroup+'"); ?></p><div id="add-to-field" class="btn btn-success"><?php echo Text::_("COM_ICAGENDA_CUSTOMFIELD_GROUPS_ADD_TO_FIELD_YES"); ?></div> <div id="no-add-to-field" class="btn btn-danger"><?php echo Text::_("COM_ICAGENDA_CUSTOMFIELD_GROUPS_ADD_TO_FIELD_NO"); ?></div></div>').show();
								$("#add-to-field").on("click",function(){
									addGroup(url, newgroup, value, fieldid, true);
								});
								$("#no-add-to-field").on("click",function(){
									addGroup(url, newgroup, value, fieldid, '');
								});
							} else {
								$(".add-group-info").html('<div class="alert alert-error alert-small"><?php echo Text::sprintf("COM_ICAGENDA_CUSTOMFIELD_GROUPS_ADD_ERROR", "<strong>'+newgroup+'</strong>"); ?></div>').show().delay(2000).fadeOut(500);
							}

							$('#deletegroup').val("");
							$(".delete-group-info").hide();
						},
						error: function(xhr, ajaxOptions, thrownError) {
							alert(xhr.status + " "+ thrownError);
						}
					});
				}

				function addGroup(url, newgroup, value, fieldid, selected) {
					$(".add-group-info").html('<div class="alert alert-success alert-small"><?php echo Text::_("COM_ICAGENDA_CUSTOMFIELD_GROUPS_ADD_SUCCESS"); ?></div>').show().delay(2000).fadeOut(500);
					$('#deletegroup').append('<option value="'+value+'" style="font-weight: bold">'+newgroup+'</option>');
					$('#newgroup').val("");

					updateGroups(url, value, selected);
				}

				function deleteGroup(url, deletegroup, id, fieldid) {
					$.ajax({
						url: url+'.deleteGroup',
						data : {
							group : deletegroup,
							id : id,
						},
						success: function( value ) {
							if (value) {
								$(".delete-group-info").html('<div class="alert alert-success alert-small"><?php echo Text::_("COM_ICAGENDA_CUSTOMFIELD_GROUPS_DELETE_SUCCESS"); ?></div>').show().delay(2000).fadeOut(500);
								$('#deletegroup option[value="'+value+'"]').remove();
								$('#deletegroup').val("");

								updateGroups(url, value, '');
							}
						},
						error: function() {
							alert("An error occurred");
						}
					});
				}

				function updateGroups(url, updatedvalue, selected) {
					var values = [];
					for (var option of document.getElementById('<?php echo $this->id; ?>').options)
					{
						if (option.selected) {
							values.push(option.value);
						}
					}
					$.ajax({
						type: "POST",
						dataType: "text",
						url: url+'.updateGroups',
						data: {
							fieldid: id,
							value: updatedvalue,
							selected: selected,
							values: values,
							jsondata: JSON.stringify(<?php echo json_encode($data); ?>),
						},
						success: function(output) {
							$('#groups-select').html(output);
						},
						error: function (xhr, ajaxOptions, thrownError) {
							alert(xhr.status + " "+ thrownError);
						}
					});
				}
			});
		</script>
		<?php

		return $html;
	}

	/**
	 * Method to get the field options.
	 *
	 * @return  array  The field option objects.
	 */
	protected function getOptions()
	{
		$db    = Factory::getDbo();
		$query = $db->getQuery(true);

		$query->select('f.*');
		$query->from($db->qn('#__icagenda_filters') . ' AS f');
		$query->where($db->qn('type') . ' = "customfield"');
		$query->where($db->qn('filter') . ' = "groups"');
		$query->where($db->qn('state') . ' = 1');
		$query->order('f.option ASC');

		// Get the options.
		$db->setQuery($query);

		$options = $db->loadObjectList();

		return $options;
	}
}
