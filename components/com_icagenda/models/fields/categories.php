<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.9.1 2024-03-12
 *
 * @package     iCagenda.Site
 * @link        https://www.icagenda.com
 *
 * @author      Cyril Rezé
 * @copyright   (c) 2012-2024 Cyril Rezé / iCagenda. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 *
 * @since       3.6
 *----------------------------------------------------------------------------
*/

defined('_JEXEC') or die;

use Joomla\CMS\Component\ComponentHelper as JComponentHelper;
use Joomla\CMS\Factory as JFactory;
use Joomla\Utilities\ArrayHelper;

JFormHelper::loadFieldClass('list');

/**
 * Category frontend search filter.
 */
class JFormFieldCategories extends JFormFieldList
{
	/**
	 * The form field type.
	 *
	 * @var     string
	 * @since   3.6.0
	 */
	protected $type = 'categories';

	/**
	 * Method to get the field options.
	 *
	 * @return  array   The field option objects.
	 * @since   3.6.0
	 */
	public function getOptions()
	{
		$app            = JFactory::getApplication();
		$params         = $app->getParams();

		$filters_mode   = ($app->getMenu()->getActive()->getParams()->get('search_filters') == 1)
						? $params->get('filters_mode', 1)
						: JComponentHelper::getParams('com_icagenda')->get('filters_mode', 1);

		// Initialize variables.
		$options = array();

		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);

		$query->select('id AS catid, title AS cattitle');
		$query->from('#__icagenda_category AS c');
		$query->where('state = 1');

		// Search in menu filtered list
		if ($filters_mode == 1)
		{
			$mcatid = $params->get('mcatid', '');

			$list_catid = \is_array($mcatid)
						? implode(',', ArrayHelper::toInteger($mcatid))
						: trim($mcatid);

			if ($mcatid
				&& \count($mcatid) > 0
				&& ! \in_array('0', $mcatid))
			{
				$query->where('c.id IN (' . $list_catid . ')');
			}
		}

		$query->order('c.title');

		// Get the options.
		$db->setQuery($query);

		$options = $db->loadObjectList();

		// Check for a database error.
//		if ($db->getErrorNum())
//		{
//			throw new Exception($db->getErrorMsg(), 500);
//		}

		return $options;
	}
}
