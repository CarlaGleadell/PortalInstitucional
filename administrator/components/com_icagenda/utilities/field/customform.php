<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.8.0 2021-09-22
 *
 * @package     iCagenda.Admin
 * @subpackage  Utilities.Form
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

//jimport('joomla.form.formfield');
JFormHelper::loadFieldClass('list');

/**
 * Custom form groups multiple select form field
 */
class icagendaFormFieldCustomform extends JFormFieldList
{
	/**
	 * The form field type.
	 *
	 * @var     string
	 * @since   3.6.0
	 */
	protected $type = 'customform';

	/**
	 * Method to get the field input markup.
	 *
	 * @return  string  The field input markup.
	 * @since   3.6.0
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
			$value = isset($this->value) ? $this->value : '';

			$options = array();

			foreach($this->getOptions() as $opt)
			{
				$options[] = JHTML::_('select.option', $opt->value, $opt->option);
			}

			return JHtml::_('select.genericlist', $options, $this->name, 'class="' . $this->class . '" multiple', 'value', 'text', $value);
		}
	}

	/**
	 * Method to get the field options.
	 *
	 * @return  array  The field option objects.
	 * @since   3.6.0
	 */
	protected function getOptions()
	{
		$options = array();

		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);

		if (version_compare(JVERSION, '4.0', 'ge'))
		{
			$query->select('f.*, f.option as text');
		}
		else
		{
			$query->select('f.*');
		}

		$query->from($db->qn('#__icagenda_filters') . ' AS f');
		$query->where($db->qn('type') . ' = "customfield"');
		$query->where($db->qn('filter') . ' = "groups"');
		$query->where($db->qn('state') . ' = 1');

		$query->order('f.option ASC');

		// Get the options.
		$db->setQuery($query);

		try
		{
			$options = $db->loadObjectList();
		}
		catch (\RuntimeException $e)
		{
			throw new \Exception($e->getMessage(), 500, $e);
		}

		return $options;
	}
}
