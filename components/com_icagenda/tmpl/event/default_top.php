<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.8.0 2021-10-02
 *
 * @package     iCagenda.Site
 * @subpackage  tmpl.event
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

use iCutilities\Event\Event as icagendaEvent;
use iCutilities\Icons\Icons as icagendaIcons;
use iCutilities\Manager\Manager as icagendaManager;
use Joomla\CMS\Factory;
?>

<?php // Top Buttons ?>
<div class="ic-top-buttons">

<?php if (Factory::getApplication()->input->get('tmpl') != 'component') : ?>

	<?php // Back button ?>
	<?php echo icagendaEvent::backArrow($this->item); ?>

	<?php // Manager Icons ?>
	<div class="ic-manager-toolbar">
		<?php echo icagendaManager::toolBar($this->item); ?>
	</div>

	<div class="ic-buttons">

		<?php // Print icon ?>
		<?php if ($this->iconPrint) : ?>
		<div class="ic-icon">
			<?php echo icagendaIcons::showIcon('printpreview'); ?>
		</div>
		<?php endif; ?>

		<?php // Add to Cal icon ?>
		<?php if ($this->iconAddToCal) :  ?>
		<div class="ic-icon">
			<?php echo icagendaIcons::showIcon('addtocal', $this->item); ?>
		</div>
		<?php endif; ?>

	</div>

<?php else : ?>

	<?php // Print Icon ?>
	<div class="ic-printpopup-btn">
		<?php echo icagendaIcons::showIcon('print'); ?>
	</div>

<?php endif; ?>

</div>
