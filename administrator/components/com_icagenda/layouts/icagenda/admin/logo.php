<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.8.0 2021-09-19
 *
 * @package     iCagenda.Admin
 * @subpackage  Layouts.icagenda
 * @link        https://www.icagenda.com
 *
 * @author      Cyril Rezé
 * @copyright   (c) 2012-2023 Cyril Rezé / iCagenda. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 *
 * @since       3.8.0
 *----------------------------------------------------------------------------
*/

defined('_JEXEC') or die;

use Joomla\CMS\Language\Text;

$version = $displayData['version'] ?? '';
?>

<div class="icagenda-card ic-bg-grey-light d-none d-lg-block">
	<img src="../media/com_icagenda/images/logo_icagenda.png" alt="logo_icagenda" class="d-none d-xl-block" style="float: right; width: 100%; max-width: 160px;" />
	<br/>
	<h2 style="font-size:2em;">
		<span style="color:#cc0000;font-weight:400;">iC</span><span style="color:#666;font-weight:300;">agenda<sup style="font-size:0.5em;">&trade;</sup></span>
		<?php if ($version) : ?>
			<sup class="badge bg-info" style="font-size: 0.5em;font-weight:500;">&nbsp;<?php echo $version; ?>&nbsp;</sup>
		<?php endif; ?>
	</h2>
	<h5>
		<?php echo Text::_('COM_ICAGENDA_COMPONENT_DESC') ?>
	</h5>
</div>
