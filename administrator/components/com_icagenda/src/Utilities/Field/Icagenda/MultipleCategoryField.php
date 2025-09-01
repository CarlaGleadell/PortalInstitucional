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

use Joomla\CMS\Factory;
use Joomla\CMS\Form\Field\ListField;

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

/**
 * Multiple Category Form Field class for iCagenda.
 */
class MultipleCategoryField extends ListField
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
	 * Method to get the field options.
	 *
	 * @return  array  The field option objects.
	 */
	protected function getOptions()
	{
		// Query List of Categories
		$db = Factory::getDbo();

		$query = $db->getQuery(true);

		$query->select('c.title AS text, c.id AS value')
			->from('#__icagenda_category AS c');

		$db->setQuery($query);

		try {
			$options = $db->loadObjectList();
		} catch (\RuntimeException $e) {
			$options = array();

			if (Factory::getUser()->authorise('core.admin')) {
				Factory::getApplication()->enqueueMessage($e->getMessage(), 'error');
			}
		}

		return $options;
	}
}
