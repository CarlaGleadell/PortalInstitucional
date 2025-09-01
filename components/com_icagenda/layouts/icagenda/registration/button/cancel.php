<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.8.0 2021-11-05
 *
 * @package     iCagenda.Site
 * @subpackage  Layout
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

use Joomla\CMS\Language\Text as JText;

extract($displayData);

/**
 * Layout variables
 * -----------------
 * @var   string   basePath         Base path to use when loading layout files (theme pack)
 * @var   string   canCancel        user right to cancel.
 * @var   string   cancelUrl        Cancel Registration Url.
 * @var   string   customLink       Custom registration link.
 * @var   array    extraDates       List of availables dates.
 * @var   string   registered       Number of registered users.
 * @var   string   registerTarget   Register button browser target.
 * @var   string   registerUrl      Registration Url.
 * @var   string   status           Status of registration button.
 * @var   string   textButton       Custom register text for button.
 * @var   string   userBooked       Logged-in user has registration(s) for this event.
*/
?>

<?php if ($userBooked && $canCancel) : ?>
<button class="btn btn-danger" type="button" onclick="window.open('<?php echo $cancelUrl; ?>', '_parent')">
	<span class="icon-cancel"></span>&nbsp;<?php echo JText::_('COM_ICAGENDA_REGISTRATION_CANCEL_BTN'); ?>
</button>
<?php endif; ?>
