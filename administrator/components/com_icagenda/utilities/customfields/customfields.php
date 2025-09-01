<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.9.5 2024-07-18
 *
 * @package     iCagenda.Admin
 * @subpackage  Utilities
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

use Joomla\CMS\Form\Form;
use WebiC\Component\iCagenda\Administrator\Model\EventModel;
use WebiC\Component\iCagenda\Site\Model\ManagerModel;
use WebiC\Component\iCagenda\Site\Model\RegistrationModel;
use WebiC\Component\iCagenda\Site\Model\SubmitModel;

/**
 * class icagendaCustomfields
 */
class icagendaCustomfields
{
	/**
	 * The form object errors array.
	 *
	 * @var    array
	 * @since  1.7.0
	 */
	protected $form;

	/**
	 * Function to return array of slugs from object list array of custom fields
	 *
	 * @access	public static
	 * @param	$customFields (self::getCustomFields())
	 * @return	array
	 *
	 * @since   3.6.0
	 */
	public static function listSlugs($customFields)
	{
		if ($customFields)
		{
			$iCparams = JComponentHelper::getParams('com_icagenda');

			$listSlugs = array();

			foreach ($customFields AS $cf)
			{
				$listSlugs[]= $cf->slug;

				if ($cf->slug == 'core_email'
					&& $iCparams->get('emailConfirm', 1))
				{
					$listSlugs[]= 'core_email2';
				}
			}

			return $listSlugs;
		}

		return array();
	}

	/**
	 * Gets the custom fields for this event (frontend)
	 *
	 * @param	$parentForm (1 = registration form; 2 = event form)
	 * 			$customForm (filtering by selected custom field groups) (since 3.6.0)
	 *
	 * @return object list
	 *
	 * @since	3.4.0
	 */
	public static function getCustomfields($parentForm, $customForm = array())
	{
		$app = JFactory::getApplication();
		$id = $app->input->getInt('id', $app->input->getInt('event_id'));

		// Get the database connector.
		$db = JFactory::getDbo();

		$list_slugs = array();

		if ($id)
		{
			// Get the query from the database connector.
			$query = $db->getQuery(true);

			// Build the query
			$query->select('id, slug')
				->from($db->qn('#__icagenda_customfields').' AS cf');
			$query->where($db->qn('cf.parent_form').' = ' .$db->q($parentForm));

			// Run Query
			$db->setQuery($query);

			// Invoke the Query
			$Customfields = $db->loadObjectList();

			// Create array of custom fields slugs for this event
			foreach ($Customfields as $cf)
			{
				$list_slugs[] = '"' . $cf->slug . '"';
			}

			$list_slugs = implode(',', $list_slugs);
		}

		// Get the query from the database connector.
		$query = $db->getQuery(true);

		// Build the query
		$query->select('cf.*')
			->from($db->qn('#__icagenda_customfields').' AS cf');

		if ($id && $list_slugs)
		{
			// Build the query
			$query->select('cfd.value AS value')
				->leftJoin($db->qn('#__icagenda_customfields_data') . ' AS cfd'
					. ' ON (' . $db->qn('cfd.parent_id') . ' = ' . (int)$id
					. ' AND ' . $db->qn('cfd.slug') . ' = ' .$db->qn('cf.slug') . ')')
				->where($db->qn('cf.slug').' IN (' . $list_slugs . ')');
		}

		$query->where($db->qn('cf.parent_form') . ' = ' . $db->q($parentForm));
		$query->where($db->qn('cf.state') . ' = 1');

		$query->order('cf.ordering ASC');

		$db->setQuery($query);

		$list = $db->loadObjectList();

		// Invoke the query.
		if ($list)
		{
			$customForm = ! is_array($customForm) ? explode(',', $customForm ?? '') : $customForm;

			foreach ($list AS $l)
			{
				$groups = ! is_array($l->groups) ? explode(',', $l->groups) : $l->groups;

				if ( ! array_intersect($customForm, $groups)
					&& ! in_array('', $groups))
				{
					$key = array_search($l, $list);

					if ($key !== false)
					{
						unset($list[$key]);
					}
				}
			}

			return $list;
		}

		return false;
	}

	/**
	 * Return the HTML body of Custom fields for this parent form (parent_id)
	 *
	 * @param	$parentForm (1 = registration form; 2 = event form)
	 * 			$customForm (filtering by selected custom form ids) (since 3.6.0)
	 *
	 * @return	HTML fields
	 *
	 * @since	3.4.0
	 */
	public static function loader($parentForm, $customForm = null, $slug = [])
	{
		$app      = JFactory::getApplication();
		$document = JFactory::getDocument();

		$params = $app->isClient('site') ? $app->getParams() : JComponentHelper::getParams('com_icagenda');

		if ($parentForm == '1')
		{
			$model = (version_compare(JVERSION, '4.0', 'lt')) ? new iCagendaModelRegistration : new RegistrationModel;
		}
		elseif ($app->isClient('site') && ($app->input->get('view') == 'submit'))
		{
			$model = (version_compare(JVERSION, '4.0', 'lt')) ? new iCagendaModelSubmit : new SubmitModel;
		}
		elseif ($app->isClient('site') && ($app->input->get('view') == 'manager'))
		{
			$model = (version_compare(JVERSION, '4.0', 'lt')) ? new iCagendaModelManager : new ManagerModel;
		}
		else
		{
			$model = (version_compare(JVERSION, '4.0', 'lt')) ? new iCagendaModelEvent : new EventModel;
		}

		$form = $model->getForm();

		$customfields = icagendaCustomfields::getCustomfields($parentForm, $customForm);

		// New 3.6.0 : Core iCagenda registration fields overrides
		$iCagendaCoreFields = array('core_name', 'core_email', 'core_phone', 'core_date', 'core_people');

		// Custom fields where options are needed
		$options_required = array('list', 'radio');

		$slug = is_array($slug) ? $slug : (array) $slug;

		$cf_display = '';

		if ($customfields)
		{
			foreach ($customfields as $icf)
			{
				// Filtering by slug.
				if (!empty($slug)
					&& !in_array($icf->slug, $slug)) continue;

				// Reset the label and input values.
				$label = null;
				$input = null;

				$user_name = $user_mail = '';

//				if (empty($icf->value)) $icf->value = '';

				$cf_type = $icf->type;
				$cf_slug = $icf->slug;

				if ($cf_type == 'calendar') {
					$icf->translateformat = true;
				}

				// Setup iCagenda core registration fields overrides
				if ($app->isClient('site')
					&& in_array($cf_type, $iCagendaCoreFields))
				{
					$core_name	= str_replace('core_', '', $icf->type);
					$icf->slug	= $core_name;

					// Autofill name and email if registered user log in
					$user = JFactory::getUser();

					$user_id   = $user->get('id');

					if ($user_id && $params->get('autofilluser', 1) == 1)
					{
						// logged-in Users: Name/User Name Option
						$user_name = ($params->get('nameJoomlaUser', 1) == 1) ? $user->get('name') : $user->get('username');
						$user_mail = $user->get('email');
					}

					// Set core fields attributes
					switch ($icf->type)
					{
						case 'core_name':
							$icf->type		= 'text';
							$icf->required	= $user_name ? false : true;
							$icf->readonly	= $user_name ? true : false;
							$icf->value	    = $user_name ? $user_name : '';
							break;

						case 'core_email':
							$icf->type		= 'email';
							$icf->required	= ($params->get('emailRequired', 1) && ! $user_mail) ? 'true' : 'false';
							$icf->readonly	= $user_mail ? true : false;
							$icf->field		= 'id';
							$icf->filter	= 'string';
							$icf->validate	= 'email';
							$icf->value	    = $user_mail ? $user_mail : '';
							break;

						case 'core_phone':
							$icf->type		= 'tel';
							$icf->required	= $params->get('phoneRequired', 0) ? 'true' : 'false';
							break;

						case 'core_date':
							$icf->type		= 'icagenda.registrationdates';
							break;

						case 'core_people':
							$icf->type		= 'icagenda.registrationpeople';
							break;

						default:
							$icf->type		= 'text';
							break;
					}

					if ( $app->isClient('site') && $parentForm == 1 )
					{
						$reg_data	= $app->getUserState('com_icagenda.registration.data', array());
						$icf->value	= isset($reg_data[$icf->slug]) ? trim($reg_data[$icf->slug]) : '';
					}
					elseif ( $app->isClient('site') && $parentForm == 2 )
					{
						if ($app->input->get('view') == 'submit')
						{
							$submit_data = $app->getUserState('com_icagenda.submit.data', array());
							$icf->value  = isset($submit_data[$icf->slug]) ? trim($submit_data[$icf->slug]) : '';
						}
//						elseif ($app->input->get('view') == 'manager'
//							&& $app->input->get('layout') == 'event_edit')
//						{
//							$event_data = $app->getUserState('com_icagenda.edit.event.data', array());
//							$icf->value  = isset($event_data[$icf->slug]) ? trim($event_data[$icf->slug]) : '';
//						}
					}
				}
				else
				{
					if ( $app->isClient('site') && $parentForm == 1 )
					{
						$reg_data	= $app->getUserState('com_icagenda.registration.data', array());
						$icf->value	= isset($reg_data['custom_fields'][$icf->slug]) ? trim($reg_data['custom_fields'][$icf->slug]) : '';
					}
					elseif ( $app->isClient('site') && $parentForm == 2 )
					{
						if ($app->input->get('view') == 'submit')
						{
							$submit_data = $app->getUserState('com_icagenda.submit.data', array());
							$icf->value  = isset($submit_data['custom_fields'][$icf->slug]) ? trim($submit_data['custom_fields'][$icf->slug]) : '';
						}
//						elseif ($app->input->get('view') == 'manager'
//							&& $app->input->get('layout') == 'event_edit')
//						{
//							$event_data = $app->getUserState('com_icagenda.event_edit.data', array());
//							$icf->value  = isset($event_data['custom_fields'][$icf->slug]) ? trim($event_data['custom_fields'][$icf->slug]) : '';
//						}
					}

					// Set Slug
					$icf->slug	= 'custom_fields][' . $icf->slug;
				}

				// If type is list or radio, should have options. Other types, not needed.
				if ((in_array($icf->type, $options_required) && $icf->options)
					|| ! in_array($icf->type, $options_required))
				{
					switch ($icf->type)
					{
						case 'email':
							$icf->validate    = 'email';
							break;

						case 'spacer_label':
							$icf->description = '';
							$icf->type        = $app->isClient('administrator') ? 'hidden' : 'icagenda.Separator';
							$icf->separatortype = 'subheader';
							$icf->class       = $icf->options;
							$icf->required    = false;
							break;

						case 'spacer_description':
							$icf->title       = $icf->description;
							$icf->description = '';
							$icf->type        = $app->isClient('administrator') ? 'hidden' : 'icagenda.Separator';
							$icf->separatortype = 'information';
							$icf->class       = $icf->options;
							$icf->required    = false;
							break;

						default:
							break;
					}

					if (($params->get('form_field_description') && $app->isClient('site'))
						|| (! $icf->description && $app->isClient('site')))
					{
						$note_description = $icf->description;
						$icf->description = '';

						if (in_array($cf_type, $iCagendaCoreFields))
						{
							$document->addStyleDeclaration('#jform_' . $icf->slug . '-lbl { cursor: auto; }');
						}
						else
						{
							$document->addStyleDeclaration('#jform_custom_fields__' . $cf_slug . '-lbl { cursor: auto; }');
						}
					}

					// Generate new form field element
					$type_field = icagendaCustomfields::setupCustomField($icf);

					if ($type_field)
					{
						$form->setField($type_field);

						if ( ! in_array($cf_type, $iCagendaCoreFields) && isset($icf->value))
						{
							$form->setValue($icf->slug, null, trim($icf->value));
						}

						if ( ! in_array($cf_type, $iCagendaCoreFields)
							|| ($app->isClient('site') && in_array($cf_type, $iCagendaCoreFields))
							)
						{
							$label = $form->getLabel($icf->slug);
							$input = $form->getInput($icf->slug);

							$cf_display.= JLayoutHelper::render('joomla.form.renderfield',
									array('input' => $input, 'label' => $label, 'options' => ''));

							// If display description inline under the field.
							if ($params->get('form_field_description') && $app->isClient('site'))
							{
								$cf_display.= JLayoutHelper::render('joomla.form.renderfield',
										array('input' => $note_description, 'label' => '', 'options' => array('class' => 'ic-input-note')));
							}
						}
					}

					// If Override for core email, we set the email2 confirm
					if ($app->isClient('site')
						&& $cf_type == 'core_email'
						&& $params->get('emailConfirm', 1)
						&& ! $user_mail)
					{
						$form->setField($type_field);
						$email2 = new \stdClass();

						$email2->slug		= 'email2';
						$email2->type		= 'email';
						$email2->title		= JText::_('IC_FORM_EMAIL_CONFIRM_LBL');
						$email2->required	= $params->get('emailRequired', 1) ? 'true' : 'false';
						$email2->field		= 'email';
						$email2->filter		= 'string';
						$email2->message	= JText::_('COM_ICAGENDA_FORM_VALIDATE_FIELD_EMAIL2_MESSAGE');
						$email2->validate	= 'equals';
						$email2->options	= JText::_('IC_FORM_EMAIL_CONFIRM_HINT');

						// Generate new form field element
						$type_field = icagendaCustomfields::setupCustomField($email2);

						if ($type_field)
						{
							$form->setField($type_field);

							if ( ! in_array($cf_type, $iCagendaCoreFields))
							{
								$form->setValue($icf->slug, null, trim($icf->value));
							}

							$document->addStyleDeclaration('#jform_email2-lbl { cursor: auto; }');
							$label = $form->getLabel('email2');
							$input = $form->getInput('email2');

							$cf_display.= JLayoutHelper::render('joomla.form.renderfield',
									array('input' => $input, 'label' => $label, 'options' => ''));
						}
					}
				}
			}

			if ($app->isClient('administrator')) $cf_display.= '<hr>';
		}
		elseif ($app->isClient('administrator'))
		{
			$cf_display.= '<div class="alert alert-info">';
			$cf_display.= JText::_('COM_ICAGENDA_CUSTOMFIELDS_NONE');
			$cf_display.= '</div>';
		}
		elseif ($app->isClient('site'))
		{
			return false;
		}

		return $cf_display;
	}

	/**
	 * Method to get a control group with label and input.
	 *
	 * @param   JForm   $form     The form
	 * @param   string  $slug     The slug of the custom field for which to get the value.
	 * @param   string  $group    The optional dot-separated form group path on which to get the value.
	 * @param   mixed   $default  The optional default value of the field value is empty.
	 * @param   array   $options  Any options to be passed into the rendering of the field
	 *
	 * @return  string  A string containing the html for the control goup
	 *
	 * @since   3.8.0
	 */
	public static function renderCustomField(JForm $form, $slug, $group = null, $default = null, $options = array())
	{
		$name = 'custom_fields][' . $slug;

		if (empty($options))
		{
			$options = array('class' => 'customfield_' . $slug);
		}

		$field = $form->getField($name);

		if ($field)
		{
			return $form->renderField($name, $group, $default, $options);
		}
	}

	/**
	 * Create a new SimpleXMLElement field element
	 *
	 * @return SimpleXMLElement
	 *
	 * @since		3.6.0
	 */
	public static function setupCustomField($icf)
	{
		if ( ! isset($icf->slug)) return false;

		// Check if Gantry based template (Gantry is not compatible with all Joomla Form Fields)
		$template = JFactory::getApplication()->getTemplate();
		$isGantry = is_dir(JPATH_ROOT . '/templates/' . $template . '/gantry/') || $template == 'gantry';

		// Set field classes
		switch ($icf->type)
		{
			case 'radio':
				$class		= $isGantry ? '' : 'btn-group';
				$labelclass	= 'control-label';
				break;

			case 'list':
				$class		= 'select-large';
				$labelclass	= '';
				break;

			default:
				$class		= '';
				$labelclass	= '';
				$size		= '30';
				break;
		}

		// Start the field
		$type_field = new SimpleXMLElement('<field />');

		// Set the field attributes
		$attributes = array(
			'name'				=> $icf->slug,
			'type'				=> $icf->type,
			'label'				=> $icf->title,
			'description'		=> isset($icf->description) ? $icf->description : '',
			'required'			=> isset($icf->required) ? $icf->required : '',
			'readonly'			=> isset($icf->readonly) ? $icf->readonly : '',
			'field'				=> isset($icf->field) ? $icf->field : '',
			'translateformat'	=> isset($icf->translateformat) ? $icf->translateformat : '',
			'showtime'			=> isset($icf->showtime) ? $icf->showtime : '',
			'filter'			=> isset($icf->filter) ? $icf->filter : '',
			'validate'			=> isset($icf->validate) ? $icf->validate : '',
			'message'			=> isset($icf->message) ? $icf->message : '',
			'class'				=> isset($icf->class) ? $icf->class : $class,
			'labelclass'		=> $labelclass,
			'size'				=> isset($size) ? $size : '',
		);

		if (isset($icf->separatortype)) {
			$attributes['separatortype'] = $icf->separatortype;
		}

		foreach ($attributes AS $attr => $value)
		{
			if ($value)
			{
				$type_field->addAttribute($attr, $value);
			}
		}

		// Set the field options (if exist)
		if (isset($icf->options))
		{
			if (in_array($icf->type, array('list', 'radio')))
			{
				$opts_list = str_replace("\n", "##BREAK##", $icf->options); // Why i did this ?... :|
				$opts_list = explode("##BREAK##", $opts_list);

				$options	= array();
				$emptyValue	= 0;
				$default	= '';

				foreach ($opts_list as $opts)
				{
					$opt		= explode("=", $opts);
					$emptyValue	= (trim($opt[0]) == '') ? $emptyValue+1 : $emptyValue;
					$default	= (isset($opt[2]) && ($default == '')) ? $opt[0] : $default;

					$options[trim($opt[0])] = isset($opt[1]) ? trim($opt[1]) : trim($opt[0]);
				}

				// If default is set in options, set this one as selected.
				if ($default != '')
				{
					$type_field->addAttribute('default', $default);
				}

				// If no option set by default, and no option with an empty value, we add a default "Select an option".
				if ($icf->type == 'list'
					&& $emptyValue == 0
					&& $default == '')
				{
					$child = $type_field->addChild('option', '- ' . JText::_('IC_SELECT_AN_OPTION') . ' -');
					$child->addAttribute('value', '');
				}

				foreach ($options as $value => $label)
				{
					$child = $type_field->addChild('option', $label);
					$child->addAttribute('value', trim($value));

					if ($icf->type == 'list'
						&& isset($icf->value) && ! empty($icf->value) && trim($icf->value) == trim($value))
					{
						$child->addAttribute('selected', 'selected');
					}
					elseif ($icf->type == 'radio'
						&& isset($icf->value) && ! empty($icf->value) && trim($icf->value) == trim($value))
					{
						$child->addAttribute('checked', 'checked');
						$child->addAttribute('class', 'btn active');
					}
				}
			}
			else
			{
				// Set Placeholder
				$type_field->addAttribute('hint', $icf->options);
			}
		}

		return $type_field;
	}

	/**
	 * Create the HTML body of the custom fields
	 *
	 * @return object list
	 *
	 * @since		3.4.0
	 * @DEPRECATED	3.6.0
	 */
	public static function displayField($type = null, $title = null, $alias = null,
		$slug = null, $description = null, $value = null, $options = null, $required = null)
	{
		$deprecated = 'Method <em>displayField</em> is deprecated. Use <em>setupCustomField</em> instead.';

		return $deprecated;
	}


	/**
	 * Control if required Custom Fields is empty.
	 *
	 * @since	3.6.5
	 */
	public static function requiredIsEmpty($custom_fields, $parentForm, $state = 1, $language = '*')
	{
		if (isset($custom_fields) && is_array($custom_fields))
		{
			$requiredEmpty = array();

			foreach ($custom_fields as $name => $value)
			{
				$db     = JFactory::getDbo();
				$query  = $db->getQuery(true)
					->select('title')
					->from($db->qn('#__icagenda_customfields'))
					->where($db->qn('slug') . ' = ' . $db->q($name))
					->where($db->qn('required') . ' = 1')
					->where($db->qn('parent_form') . ' = ' . $db->q($parentForm));
				$db->setQuery($query);
				$result = $db->loadResult();

				if ($result && trim($value) == '')
				{
					$requiredEmpty[] = $result;
				}
			}

			if (count($requiredEmpty) > 0)
			{
				return $requiredEmpty;
			}

			return false;
		}
	}

	/**
	 * Save Custom Fields to the database if at least one is filled
	 * or update existing data from custom fields.
	 *
	 * @since	3.4.0
	 */
	public static function saveToData($custom_fields, $parent_id, $parentForm, $state = 1, $language = '*')
	{
		// Get the database connector.
		$db = JFactory::getDbo();

		if (isset($custom_fields) && is_array($custom_fields))
		{
			foreach ( $custom_fields as $name => $value )
			{
				$customfields_data = new \stdClass();
				$customfields_data->slug = $name;
				$customfields_data->value = $value;
				$customfields_data->state = $state;
				$customfields_data->parent_form = $parentForm;
				$customfields_data->parent_id = $parent_id;
				$customfields_data->language = $language;

				$query = $db->getQuery(true)
					->select('id')
					->from($db->qn('#__icagenda_customfields_data'))
					->where($db->qn('slug') . ' = ' . $db->q($customfields_data->slug))
					->where($db->qn('parent_form') . ' = ' . $db->q($customfields_data->parent_form))
					->where($db->qn('parent_id') . ' = ' . $db->q($customfields_data->parent_id));
				$db->setQuery($query);
				$id_exists = $db->loadResult();

				if ( ! $id_exists && (trim($customfields_data->value) != ''))
				{
					$db->insertObject('#__icagenda_customfields_data', $customfields_data, 'id');
				}
				elseif (trim($customfields_data->value) == '')
				{
					$query = $db->getQuery(true);

					// Delete any empty slug records from the __icagenda_customfields_data table if exists
					$conditions = array(
						$db->quoteName('parent_id') . ' = ' . $db->quote($customfields_data->parent_id),
						$db->quoteName('slug') . ' = ' . $db->quote($customfields_data->slug)
					);

					$query->delete($db->quoteName('#__icagenda_customfields_data'));
					$query->where($conditions);

					$db->setQuery($query);
					$db->execute($query);

					if ( ! $db->execute())
					{
						return false;
					}
				}
				else
				{
					$customfields_data->id = $id_exists;
					$db->updateObject('#__icagenda_customfields_data', $customfields_data, 'id');
				}
			}
		}
	}

	/**
	 * Delete Custom Fields from the database
	 * or update existing data from custom fields.
	 *
	 * @since	3.5.6
	 */
	public static function deleteData($parent_id, $parentForm)
	{
		// Get the database connector.
		$db = JFactory::getDbo();

		// Delete any unwanted customfields records from the __icagenda_customfields_data table
		$query = $db->getQuery(true);
		$query->delete($db->qn('#__icagenda_customfields_data'));
		$query->where('parent_id = ' . (int) $parent_id);
		$query->where('parent_form = ' . (int) $parentForm);

		$db->setQuery($query);
		$db->execute($query);

		if ( ! $db->execute())
		{
			return false;
		}

		return true;
	}

	/**
	 * Clean Custom Fields from the database (fix for previous versions)
	 *
	 * @since	3.5.6
	 */
	public static function cleanData($parentForm)
	{
		// Get the database connector.
		$db = JFactory::getDbo();

		// Get Registrations ids
		if ($parentForm == 1)
		{
			$query = $db->getQuery(true)
				->select('id')
				->from($db->qn('#__icagenda_registration'));
			$db->setQuery($query);
			$list = $db->loadColumn();
		}

		// Get Events ids
		elseif ($parentForm == 2)
		{
			// Get Registrations ids
			$query = $db->getQuery(true)
				->select('id')
				->from($db->qn('#__icagenda_events'));
			$db->setQuery($query);
			$list = $db->loadColumn();
		}

		$parent_ids = isset($list) && is_array($list) ? implode(',', $list) : '';

		// Delete any unwanted customfields records from the __icagenda_customfields_data table
		$query = $db->getQuery(true);
		$query->delete($db->qn('#__icagenda_customfields_data'));
		$query->where('parent_form = ' . (int) $parentForm);
		$query->where('parent_id NOT IN (' . $parent_ids . ')');

		$db->setQuery($query);
		$db->execute($query);

		if ( ! $db->execute())
		{
			return false;
		}

		return true;
	}


	/**
	 * Function to return list of custom fields depending on the item ID
	 * USED IN : Registrations Export / Privacy Plugin
	 *
	 * @param   $id          item ID
	 * @param   $parentForm  1 registration, 2 event edit
	 * @param   $state       if not defined, state is published ('1')
	 * @param   $onlyFields  if true, return data form fields and exclude separator field type.
	 *
	 * @return  object  list array of custom fields depending on the item ID
	 */
	public static function getList($id, $parentForm = null, $state = null, $onlyFields = null)
	{
		$filter_state = isset($state) ? $state : 1;
		$only_fields  = isset($onlyFields) ? $onlyFields : false;

		// Create a new query object.
		$db		= JFactory::getDbo();
		$query	= $db->getQuery(true);
		$query->select('cf.slug AS cf_slug, cfd.value AS cf_value, cfd.parent_id AS cf_parent_id, cf.title AS cf_title, cf.required AS cf_required')
			->from('#__icagenda_customfields AS cf')
			->leftJoin($db->qn('#__icagenda_customfields_data') . ' AS cfd'
				. ' ON ' . $db->qn('cfd.parent_id') .' = ' . (int)$id
				. ' AND ' . $db->qn('cf.slug') .' = ' . $db->qn('cfd.slug'))
			->where($db->qn('cf.state') . ' = ' . $db->q($filter_state))
			->where($db->qn('cf.parent_form') . ' = ' . $db->q($parentForm));

		if ($only_fields) {
			$query->where($db->qn('cf.type') . ' NOT LIKE ' . $db->q('spacer_%'));
		}

		$query->order('cf.ordering ASC');
		$db->setQuery($query);
		$list = $db->loadObjectList();

		if ($list) return $list;

		return false;
	}

	/**
	 * Function to return a list of filled custom fields depending on the item ID
	 *
	 * @access	public static
	 * @param	$id item ID
	 * 			$parentForm (1 registration, 2 event edit)
	 * 			$state (if not defined, state is published ('1'))
	 * @return	object list array of custom fields not empty depending on the item ID
	 *
	 * @since   3.4.0
	 */
	public static function getListNotEmpty($id, $parentForm = null, $state = null)
	{
		$filter_state = isset($state) ? $state : 1;

		// Create a new query object.
		$db		= JFactory::getDbo();
		$query	= $db->getQuery(true);
		$query->select('cfd.slug AS cf_slug, cfd.value AS cf_value, cfd.parent_id AS cf_parent_id, cf.title AS cf_title')
			->from('#__icagenda_customfields_data AS cfd')
			->leftJoin($db->qn('#__icagenda_customfields') . ' AS cf'
				. ' ON ' . $db->qn('cf.slug') .' = ' . $db->qn('cfd.slug'))
			->where($db->qn('cf.state') . ' = ' . $db->q($filter_state));

		if ($parentForm)
		{
			$query->where($db->qn('cfd.parent_form') . ' = ' . $db->q($parentForm));
		}

		$query->where($db->qn('cfd.parent_id') . ' = ' . (int)$id);
		$query->order('cf.ordering ASC');
		$db->setQuery($query);
		$list = $db->loadObjectList();

		if ($list) return $list;

		return false;
	}


	/**
	 * Function to return list of custom fields depending on the parent form
	 *
	 * @access	public static
	 * @param	$parentForm (1 registration, 2 event edit)
	 * 			$customForm (filtering by selected custom field groups) (since 3.6.0)
	 * 			$state (if not defined, state is published ('1'))
	 * @return	object list array of custom fields
	 *
	 * @since   3.4.0
	 * @deprecated 3.6.0
	 */
	public static function getListCustomFields($parentForm, $customForm = array(), $state = null)
	{
		$filter_state = isset($state) ? $state : 1;

		// Create a new query object.
		$db		= JFactory::getDbo();
		$query	= $db->getQuery(true);
		$query->select('cf.*')
			->from('#__icagenda_customfields AS cf')
			->where($db->qn('state') . ' = ' . $db->q($filter_state))
			->where($db->qn('parent_form') . ' = ' . $db->q($parentForm))
			->order('ordering ASC');
		$db->setQuery($query);
		$list = $db->loadObjectList();

		// Invoke the query.
		if ($list)
		{
			$customForm = ! is_array($customForm) ? explode(',', $customForm) : $customForm;

			foreach ($list AS $l)
			{
				$groups = ! is_array($l->groups) ? explode(',', $l->groups) : $l->groups;

				if ( ! array_intersect($customForm, $groups)
					&& ! in_array('', $groups))
				{
					$key = array_search($l, $list);

					if ($key !== false)
					{
						unset($list[$key]);
					}
				}
			}

			return $list;
		}

		return array();
	}
}
