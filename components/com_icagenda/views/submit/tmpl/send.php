<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.8.0 2018-10-26
 *
 * @package     iCagenda.Site
 * @link        https://www.icagenda.com
 *
 * @author      Cyril Rezé
 * @copyright   (c) 2012-2024 Cyril Rezé / iCagenda. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 *
 * @since       3.2.0
 *----------------------------------------------------------------------------
*/

defined('_JEXEC') or die;

$app  = JFactory::getApplication();
$lang = JFactory::getLanguage();
$user = JFactory::getUser();

// Look for the home menu
$menu   = $app->getMenu();
$home   = (JLanguageMultilang::isEnabled())
		? $menu->getDefault($lang->getTag())
		: $menu->getDefault();

// Get the site name
$sitename = $app->getCfg('sitename');

// Get Authorized user groups (approval managers)
$approvalGroups = $this->params->get('approvalGroups', array("8"));

// Control: if Manager
jimport('joomla.access.access');

$u_id = $user->get('id');

$adminUsersArray = array();

foreach ($approvalGroups AS $ag)
{
	$adminUsers = JAccess::getUsersByGroup($ag, false);
	$adminUsersArray = array_merge($adminUsersArray, $adminUsers);
}

$isManager = in_array($u_id, $adminUsersArray) ? true : false;

// Clear the data so we don't process it again
$app->setUserState('com_icagenda.submit.data', null);
?>

<div id="icagenda" class="ic-send<?php echo $this->pageclass_sfx; ?>">
<?php if ( ! $isManager) : ?>
	<div><?php echo JText::_('COM_ICAGENDA_EVENT_SUBMISSION_EDITOR_REVIEW'); ?></div>
	<div><?php echo JText::_('COM_ICAGENDA_EVENT_SUBMISSION_CONFIRMATION_EMAIL'); ?></div>
	<div><?php echo JText::sprintf('COM_ICAGENDA_EVENT_SUBMISSION_THANK_YOU', $sitename); ?></div>
<?php endif; ?>
	<br />
	<div>
		<a href="<?php echo JRoute::_('index.php?Itemid=' . $home->id); ?>" class="btn btn-small btn-info button">
			<i class="icon-home icon-white"></i>&nbsp;<?php echo JText::_('JERROR_LAYOUT_HOME_PAGE'); ?>
		</a>
		&nbsp;
		<a href="<?php echo JRoute::_('index.php?option=com_icagenda&view=submit'); ?>" class="btn btn-small btn-success button">
			<i class="icon-plus icon-white"></i>&nbsp;<?php echo JText::_('COM_ICAGENDA_EVENT_SUBMISSION_SUBMIT_NEW_EVENT'); ?>
		</a>
	</div>
	<br />
</div>
