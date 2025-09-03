<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.9.12 2025-07-31
 *
 * @package     iCagenda.Admin
 * @subpackage  Utilities
 * @link        https://www.joomlic.com
 *
 * @author      Cyril Reze
 * @copyright   (c) 2012-2025 Cyril Reze / JoomliC. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 *
 * @since       3.4.1
 *----------------------------------------------------------------------------
*/

defined('_JEXEC') or die;

$catids     = array();
$categories = array();
$showTitle  = (bool) \in_array('1', $this->cat_options);
$showDesc   = (bool) \in_array('2', $this->cat_options);

if ($this->items && $this->cat_description && \count($this->getAllDates) > 0
	&& ($showTitle || $showDesc)
	)
{
	foreach ($this->items AS $cat)
	{
		if (!\in_array($cat->cat_id, $catids))
		{
			$catids[] = $cat->cat_id;

			$catData = array();

			$catData['id']      = $cat->cat_id;
			$catData['title']   = $cat->cat_title;
			$catData['color']   = $cat->cat_color;
			$catData['desc']    = $cat->cat_desc
								? JHtml::_('content.prepare', $cat->cat_desc, $this->params, 'com_icagenda.events')
								: ' ';

			$categories[$cat->cat_id] = $catData;
		}
	}

	if (\count($categories))
	{
		echo '<div class="ic-header-categories ic-clearfix">';

		foreach ($categories as $cat => $data)
		{
			if ($showTitle)
			{
				$Itemid = JFactory::getApplication()->input->getInt('Itemid');
				$url    = iCagendaHelperRoute::getListFilteredByCategoryRoute($data['id'], $this->params, $Itemid);

				echo '<a href="' . JRoute::_($url) . '" type="button"'
					. ' class="ic-title-cat-btn ic-button ic-margin-1 ic-padding-x-3 ic-padding-y-1 ic-radius-1'
					. ' ic-bg-' . iCColor::getBrightness($data['color']) . '"'
					. ' style="background:' . $data['color'] . '">';
				echo JText::_($data['title']);
				echo '</a>';
			}

			if ($showDesc)
			{
				echo '<div class="cat_header_desc">' . $data['desc'] . '</div>';
			}
		}

		echo '</div>';
	}
}
