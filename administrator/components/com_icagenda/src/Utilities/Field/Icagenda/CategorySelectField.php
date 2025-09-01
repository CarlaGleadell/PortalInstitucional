<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.9.4 2024-06-13
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
use Joomla\CMS\Form\Field\ListField;
use Joomla\CMS\Language\Text;

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

/**
 * Category Select Form Field
 */
class CategorySelectField extends ListField
{
	protected $type = 'CategorySelect';

	protected function getInput()
	{
		$app = Factory::getApplication();

		// Initialize some field attributes.
		$class = ! empty($this->class) ? ' class="' . $this->class . '"' : '';
		$onchange = ! empty($this->onchange) ? ' onchange="' . $this->onchange . '"' : '';

		$params = ($app->isClient('administrator'))
				? ComponentHelper::getParams('com_icagenda')
				: $app->getParams();

		$orderby_catlist = $params->get('orderby_catlist', 'alpha');
		$default_catlist = $params->get('default_catlist', '');

		$admin_status_catlist = $params->get('admin_status_catlist', '1');
		$site_status_catlist  = $params->get('site_status_catlist', '1');

		$admin_status_array = \is_array($admin_status_catlist) ? $admin_status_catlist : array($admin_status_catlist);
		$site_status_array  = \is_array($site_status_catlist) ? $site_status_catlist : array($site_status_catlist);

		$admin_status = implode(',', $admin_status_array);
		$site_status  = implode(',', $site_status_array);

		// Query List of Categories
		$db = Factory::getDbo();

		$query = $db->getQuery(true);
		$query->select('c.ordering, c.title, c.state, c.id');
		$query->from($db->qn('#__icagenda_category') . ' AS c');

		// Not display Trashed Categories
		$query->where($db->qn('c.state') . ' <> -2');

		if ($app->isClient('administrator')) {
			$query->where($db->qn('c.state') . ' IN (' . $admin_status . ') ');
		} else {
			$query->where($db->qn('c.state') . ' IN (' . $site_status . ') ');
		}

		if ($orderby_catlist == 'alpha') {
			$query->order('c.title ASC');
		} elseif ($orderby_catlist == 'ralpha') {
			$query->order('c.title DESC');
		} elseif ($orderby_catlist == 'order') {
			$query->order('c.ordering ASC');
		}

		$db->setQuery($query);

		$categories = $db->loadObjectList();

		$html = '<select id="' . $this->id . '" name="' . $this->name . '"' . $class . $onchange . '>';

		$html.= '<option value="">' . Text::_('JOPTION_SELECT_CATEGORY') . '</option>';

		foreach ($categories as $c) {
			$html.= '<option value="' . $c->id . '"';

			if ($c->state == '0') {
//				$html.= ' style="color:red"';
				$c->title = '[' . $c->title . '] (' . Text::_('JUNPUBLISHED') . ')';
			} elseif ($c->state == '2') {
//				$html.= ' style="color:orange"';
				$c->title = $c->title . ' (' . Text::_('JARCHIVED') . ')';
			}

			if ($this->value == $c->id) {
				$html.= ' selected="selected"';
			}

			if (empty($this->value)
				&& ($c->id == $default_catlist)
			) {
				$html.= ' selected="selected"';
			}

			$html.= '>' . Text::_($c->title) . '</option>';
		}

		$html.= '</select>';

		return $html;
	}
}
