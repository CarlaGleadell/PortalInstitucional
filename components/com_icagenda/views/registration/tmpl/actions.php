<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.8.7 2022-05-26
 *
 * @package     iCagenda.Site
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

$app = JFactory::getApplication();

$actions = $app->getUserState('com_icagenda.registration.actions', '');

if ($actions)
{
	$action      = explode('.', $actions);
	$layout      = new JLayoutFile($action[1], $basePath = JPATH_PLUGINS . '/icagenda/' . $action[0] . '/layouts');
	$displayData = array('item' => $this->item, 'actions' => $this->registrationActions, 'params' => $this->params);
	$actionsHTML = $layout->render($displayData);
}
else
{
	$actionsHTML = JText::_('COM_ICAGENDA_REGISTRATION_ACTIONS_NONE');
}
?>

<div id="icagenda" class="ic-actions-view<?php echo $this->pageclass_sfx; ?>">
	<h1>
		<?php echo $this->escape($this->item->title); ?>
	</h1>
	<?php echo $actionsHTML; ?>
	<div class="com-icagenda-registration__actions-buttons control-group">
		<div class="controls">
			<a href="<?php echo JRoute::_('index.php?option=com_icagenda&view=registration&id=' . $app->input->getInt('id') . '&Itemid=' . (int) $app->input->getInt('Itemid')); ?>" class="btn btn-small btn-info button">
				<i class="icon-previous icon-white"></i>&nbsp;<?php echo JText::_('COM_ICAGENDA_REGISTRATION_ACTIONS_PREVIOUS'); ?>
			</a>
			<a class="btn btn-small btn-danger button" href="<?php echo JRoute::_('index.php?option=com_icagenda&task=registration.actions&eventID=' . $app->input->getInt('id') . '&action=abandon'); ?>" title="<?php echo JTEXT::_('COM_ICAGENDA_CANCEL'); ?>">
				<i class="icon-cancel"></i>&nbsp;<?php echo JTEXT::_('COM_ICAGENDA_REGISTRATION_CANCEL_BTN'); ?>
			</a>
		</div>
	</div>
	<br />
</div>
