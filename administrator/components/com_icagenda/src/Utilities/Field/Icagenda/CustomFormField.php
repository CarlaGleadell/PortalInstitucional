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
 * @since       3.6.0
 *----------------------------------------------------------------------------
*/

namespace iCutilities\Field\Icagenda;

use Joomla\CMS\Factory;
use Joomla\CMS\Form\Field\ListField;

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

/**
 * Custom form groups multiple select form field
 */
class CustomFormField extends ListField
{
	/**
	 * The form field type.
	 *
	 * @var     string
	 */
	protected $type = 'CustomForm';

	/**
	 * Name of the layout being used to render the field
	 *
	 * @var  string
	 *
	 * @since   3.9.0
	 */
	protected $layout = 'joomla.form.field.list-fancy-select';

	/**
	 * Method to get the field input markup.
	 *
	 * @return  string  The field input markup.
	 */
	protected function getInput()
	{
		$data = $this->getLayoutData();

		$data['options'] = (array) $this->getOptions();

		return $this->getRenderer($this->layout)->render($data);
	}

	/**
	 * Method to get the field options.
	 *
	 * @return  array  The field option objects.
	 */
	protected function getOptions()
	{
		$options = [];

		$db = Factory::getDbo();

		$query = $db->getQuery(true);
		$query->select('f.*, f.option as text');
		$query->from($db->qn('#__icagenda_filters') . ' AS f');
		$query->where($db->qn('type') . ' = "customfield"');
		$query->where($db->qn('filter') . ' = "groups"');
		$query->where($db->qn('state') . ' = 1');
		$query->order('f.option ASC');

		// Get the options.
		$db->setQuery($query);

		try {
			$options = $db->loadObjectList();
		} catch (\RuntimeException $e) {
			throw new \Exception($e->getMessage(), 500, $e);
		}

		return $options;
	}
}
