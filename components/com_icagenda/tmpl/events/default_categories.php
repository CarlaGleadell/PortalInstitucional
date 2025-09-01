<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.9.4 2024-06-13
 *
 * @package     iCagenda.Site
 * @subpackage  tmpl.events
 * @link        https://www.icagenda.com
 *
 * @author      Cyril Rezé
 * @copyright   (c) 2012-2024 Cyril Rezé / iCagenda. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 *
 * @since       3.4.1
 *----------------------------------------------------------------------------
*/

defined('_JEXEC') or die;

use iClib\Color\Color as iCColor;
use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Uri\Uri;
use WebiC\Component\iCagenda\Site\Helper\RouteHelper;

$catids     = [];
$categories = [];
$showTitle  = (bool) \in_array('1', $this->cat_options);
$showDesc   = (bool) \in_array('2', $this->cat_options);

if ($this->items
	&& $this->cat_description
	&& \count($this->getAllDates) > 0
	&& ($showTitle || $showDesc))
{
	foreach ($this->items AS $cat) {
		if (!\in_array($cat->cat_id, $catids)) {
			$catids[] = $cat->cat_id;

			$catData = [];

			$catData['id']      = $cat->cat_id;
			$catData['title']   = $cat->cat_title;
			$catData['color']   = $cat->cat_color;
			$catData['desc']    = $cat->cat_desc
								? HTMLHelper::_('content.prepare', $cat->cat_desc, $this->params, 'com_icagenda.events')
								: ' ';

			$categories[$cat->cat_id] = $catData;
		}
	}

	if (\count($categories)) {
		echo '<div class="ic-header-categories ic-clearfix">';

		foreach ($categories as $cat => $data) {
			if ($showTitle) {
				$Itemid = Factory::getApplication()->input->getInt('Itemid');
				$url    = RouteHelper::getListFilteredByCategoryRoute($data['id'], $this->params, $Itemid);

				echo '<a href="' . Route::_($url) . '" type="button"'
					. ' class="ic-title-cat-btn ic-button ic-margin-1 ic-padding-x-3 ic-padding-y-1 ic-radius-1'
					. ' ic-bg-' . iCColor::getBrightness($data['color']) . '"'
					. ' style="background:' . $data['color'] . '">';
				echo Text::_($data['title']);
				echo '</a>';
			}

			if ($showDesc) {
				echo '<div class="cat_header_desc">' . $data['desc'] . '</div>';
			}
		}

		echo '</div>';
	}
}
