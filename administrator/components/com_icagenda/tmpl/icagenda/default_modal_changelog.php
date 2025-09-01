<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.8.0 2021-09-29
 *
 * @package     iCagenda.Admin
 * @subpackage  tmpl.icagenda
 * @link        https://www.icagenda.com
 *
 * @author      Cyril Rezé
 * @copyright   (c) 2012-2024 Cyril Rezé / iCagenda. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 *
 * @since       3.8
 *----------------------------------------------------------------------------
*/

defined('_JEXEC') or die;

use Joomla\CMS\HTML\HTMLHelper;

require_once dirname(__FILE__) . '/color.php';

HTMLHelper::_('stylesheet', 'com_icagenda/icagenda-back.css', array('relative' => true, 'version' => 'auto'));
?>

<div id="icagenda-changelog">
	<?php echo iCagendaUpdateLogsColoriser::colorise(JPATH_COMPONENT_ADMINISTRATOR . '/CHANGELOG.php'); ?>
</div>
