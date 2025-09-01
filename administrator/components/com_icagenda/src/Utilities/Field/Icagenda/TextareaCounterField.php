<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.9.0 2024-02-19
 *
 * @package     iCagenda.Admin
 * @subpackage  Utilities.Field.Icagenda
 * @link        https://www.icagenda.com
 *
 * @author      Cyril Rezé
 * @copyright   (c) 2012-2024 Cyril Rezé / iCagenda. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 *
 * @since       3.8.0
 *----------------------------------------------------------------------------
*/

namespace iCutilities\Field\Icagenda;

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Form\FormField;

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

/**
 * Textarea with a live character count limit Form Field class for iCagenda.
 */
class TextareaCounterField extends FormField
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 */
	protected $type = 'TextareaCounter';

	/**
	 * The number of rows in textarea.
	 *
	 * @var    mixed
	 * @since  3.9.0
	 */
	protected $rows;

	/**
	 * The number of columns in textarea.
	 *
	 * @var    mixed
	 * @since  3.9.0
	 */
	protected $columns;

	/**
	 * The maximum number of characters in textarea.
	 *
	 * @var    mixed
	 * @since  3.9.0
	 */
	protected $maxlength;

	/**
	 * Does this field support a character counter?
	 *
	 * @var    boolean
	 * @since  3.9.0
	 */
	protected $charcounter = true;

	/**
	 * Name of the layout being used to render the field
	 *
	 * @var    string
	 * @since  3.9.0
	 */
	protected $layout = 'joomla.form.field.textarea';

	/**
	 * Method to get certain otherwise inaccessible properties from the form field object.
	 *
	 * @param   string  $name  The property name for which to get the value.
	 *
	 * @return  mixed  The property value or null.
	 *
	 * @since   3.9.0
	 */
	public function __get($name)
	{
		switch ($name) {
			case 'rows':
			case 'columns':
			case 'maxlength':
			case 'charcounter':
				return $this->$name;
		}

		return parent::__get($name);
	}

	/**
	 * Method to set certain otherwise inaccessible properties of the form field object.
	 *
	 * @param   string  $name   The property name for which to set the value.
	 * @param   mixed   $value  The value of the property.
	 *
	 * @return  void
	 *
	 * @since   3.9.0
	 */
	public function __set($name, $value)
	{
		switch ($name) {
			case 'rows':
			case 'columns':
			case 'maxlength':
				$this->$name = (int) $value;
				break;

			case 'charcounter':
				$this->charcounter = strtolower($value) === 'true';
				break;

			default:
				parent::__set($name, $value);
		}
	}

	/**
	 * Method to attach a Form object to the field.
	 *
	 * @param   \SimpleXMLElement  $element  The SimpleXMLElement object representing the `<field>` tag for the form field object.
	 * @param   mixed              $value    The form field value to validate.
	 * @param   string             $group    The field name group control value. This acts as an array container for the field.
	 *                                      For example if the field has name="foo" and the group value is set to "bar" then the
	 *                                      full field name would end up being "bar[foo]".
	 *
	 * @return  boolean  True on success.
	 *
	 * @see     FormField::setup()
	 * @since   3.9.0
	 */
	public function setup(\SimpleXMLElement $element, $value, $group = null)
	{
		$return = parent::setup($element, $value, $group);

		if ($return) {
			$this->rows        = isset($this->element['rows']) ? (int) $this->element['rows'] : false;
			$this->columns     = isset($this->element['cols']) ? (int) $this->element['cols'] : false;
//			$this->maxlength   = isset($this->element['maxlength']) ? (int) $this->element['maxlength'] : false;
//			$this->charcounter = isset($this->element['charcounter']) ? strtolower($this->element['charcounter']) === 'true' : false;

			$app = Factory::getApplication();

			// Get characters limit setting
			$params	= $app->isClient('administrator')
					? ComponentHelper::getParams('com_icagenda')
					: $app->getParams();
	
			$name = $this->fieldname;
	
			if ($name == 'shortdesc') {
				$ic_max = $params->get('char_limit_short_description', '100');
			} elseif ($name == 'metadesc') {
				$ic_max = $params->get('char_limit_meta_description', '320');
			} else {
				$ic_max = $params->get('ShortDescLimit', '100');
			}
	
			// Set maxLength for textarea
			$this->maxlength = $ic_max;

			// Enable counter
			$this->charcounter = true;
		}

		return $return;
	}

	/**
	 * Method to get the textarea field input markup.
	 * Use the rows and columns attributes to specify the dimensions of the area.
	 *
	 * @return  string  The field input markup.
	 */
	protected function getInput()
	{
		// Trim the trailing line in the layout file
		return rtrim($this->getRenderer($this->layout)->render($this->getLayoutData()), PHP_EOL);
	}

	/**
	 * Method to get the data to be passed to the layout for rendering.
	 *
	 * @return  array
	 *
	 * @since   3.9.0
	 */
	protected function getLayoutData()
	{
		$data = parent::getLayoutData();

		// Initialize some field attributes.
		$columns      = $this->columns ? ' cols="' . $this->columns . '"' : '';
		$rows         = $this->rows ? ' rows="' . $this->rows . '"' : '';
		$maxlength    = $this->maxlength ? ' maxlength="' . $this->maxlength . '"' : '';

		$extraData = [
			'maxlength'   => $maxlength,
			'rows'        => $rows,
			'columns'     => $columns,
			'charcounter' => $this->charcounter,
		];

		return array_merge($data, $extraData);
	}
}
