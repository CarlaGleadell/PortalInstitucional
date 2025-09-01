<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.9.0 2024-02-24
 *
 * @package     iCagenda.Admin
 * @subpackage  Layouts.icagenda
 * @link        https://www.icagenda.com
 *
 * @author      Cyril Rezé
 * @copyright   (c) 2012-2023 Cyril Rezé / iCagenda. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 *
 * @since       3.7.18
 *----------------------------------------------------------------------------
*/

use Joomla\CMS\Filter\OutputFilter as JFilterOutput;

defined('_JEXEC') or die;

$id      = empty($displayData['id']) ? '' : (' id="' . $displayData['id'] . '"');
$target  = empty($displayData['target']) ? '' : (' target="' . $displayData['target'] . '"');
$onclick = empty($displayData['onclick']) ? '' : (' onclick="' . $displayData['onclick'] . '"');
$title   = empty($displayData['title']) ? '' : (' title="' . $this->escape($displayData['title']) . '"');
$text    = empty($displayData['text']) ? '' : ('<span class="ic-liveupdate-link">' . $displayData['text'] . '</span>')
?>

<div<?php echo $id; ?>>
	<a href="<?php echo JFilterOutput::ampReplace($displayData['link']); ?>"<?php echo $target . $onclick . $title; ?>>
		<div class="ic-liveupdate-content">
			<span class="<?php echo $displayData['image']; ?> ic-liveupdate-icon" aria-hidden="true"></span>
			<br /><?php echo $text; ?>
		</div>
	</a>
</div>
