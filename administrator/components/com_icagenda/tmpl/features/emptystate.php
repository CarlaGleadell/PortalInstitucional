<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.8.0 2021-09-11
 *
 * @package     iCagenda.Admin
 * @subpackage  tmpl.features
 * @link        https://www.icagenda.com
 *
 * @author      Cyril Rezé
 * @copyright   (c) 2012-2024 Cyril Rezé / iCagenda. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 *
 * @since       3.8.0
 *----------------------------------------------------------------------------
*/

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Layout\LayoutHelper;

$displayData = [
	'textPrefix' => 'COM_ICAGENDA_FEATURES',
	'formURL'    => 'index.php?option=com_icagenda',
//	'helpURL'    => 'https://www.icagenda.com/docs/documentation',
	'icon'       => 'icon-checkbox checkbox',
];

$user = Factory::getApplication()->getIdentity();

if ($user->authorise('core.create', 'com_icagenda') || count($user->getAuthorisedCategories('com_icagenda', 'core.create')) > 0)
{
	$displayData['createURL'] = 'index.php?option=com_icagenda&task=feature.add';
}

echo LayoutHelper::render('joomla.content.emptystate', $displayData);
