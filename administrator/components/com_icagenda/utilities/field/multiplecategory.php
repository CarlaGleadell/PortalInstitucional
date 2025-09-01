<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.8.0 2022-02-05
 *
 * @package     iCagenda.Admin
 * @subpackage  Utilities.Form
 * @link        https://www.icagenda.com
 *
 * @author      Cyril Rezé
 * @copyright   (c) 2012-2024 Cyril Rezé / iCagenda. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 *
 * @since       3.2
 * @deprecated  J4 - iCagenda 4.0
 *              @see WebiC\Component\iCagenda\Administrator\Field
 *----------------------------------------------------------------------------
*/

//namespace WebiC\Component\iCagenda\Administrator\Field;

\defined('JPATH_PLATFORM') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Form\FormHelper;

FormHelper::loadFieldClass('list');

/**
 * Form Field class for iCagenda.
 * Custom Field Groups multiple select form field with Groups Manager.
 */
class icagendaFormFieldMultipleCategory extends JFormFieldList
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 */
	protected $type = 'MultipleCategory';

	/**
	 * Name of the layout being used to render the field
	 *
	 * @var    string
	 */
	protected $layout = 'joomla.form.field.list-fancy-select';

	/**
	 * Method to get the field input markup for a generic list.
	 * Use the multiple attribute to enable multiselect.
	 *
	 * @return  string  The field input markup.
	 */
	protected function getInput()
	{
		if (version_compare(JVERSION, '4.0', 'ge'))
		{
			$data = $this->getLayoutData();

			$data['options'] = (array) $this->getOptions();

			return $this->getRenderer($this->layout)->render($data);
		}
		else
		{
			// Initialize some field attributes.
			$class = ! empty($this->class) ? ' class="' . $this->class . '"' : '';

			// Query List of Categories
			$db    = JFactory::getDbo();
			$query = $db->getQuery(true);

			$query->select('a.title, a.state, a.id')
				->from('#__icagenda_category AS a');

			$db->setQuery($query);

			$cat = $db->loadObjectList();

			if ( ! is_array($this->value))
			{
//				if ( ! $this->value) $this->value = "0";
				$this->value = array($this->value);
			}

			$html = ' <select multiple id="' . $this->id . '_id" name="' . $this->name . '"' . $class . '>';

			foreach ($cat as $c)
			{
				if ($c->state == '1')
				{
					$html.= '<option value="' . $c->id . '"';

//					if (in_array($c->id, $this->value) && ! in_array('0', $this->value))
					if (in_array($c->id, $this->value))
					{
						$html.= ' selected="selected"';
					}

					$html.= '>' . $c->title . '</option>';
				}
			}

			$html.= '</select>';

			return $html;
		}
	}

	/**
	 * Method to get the field options.
	 *
	 * @return  array  The field option objects.
	 */
	protected function getOptions()
	{
		// Query List of Categories
		$db    = Factory::getDbo();
		$query = $db->getQuery(true);

		$query->select('c.title AS text, c.id AS value')
			->from('#__icagenda_category AS c');

		$db->setQuery($query);

		try
		{
			$options = $db->loadObjectList();
		}
		catch (\RuntimeException $e)
		{
			$options = array();

			if (Factory::getUser()->authorise('core.admin'))
			{
				Factory::getApplication()->enqueueMessage($e->getMessage(), 'error');
			}
		}

		return $options;
	}
}
