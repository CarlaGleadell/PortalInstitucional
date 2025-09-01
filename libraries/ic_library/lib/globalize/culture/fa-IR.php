<?php
/**
 *----------------------------------------------------------------------------
 * iC Library   Library by Jooml!C, for Joomla!
 *----------------------------------------------------------------------------
 * @version     1.4.7 2018-04-24
 *
 * @package     iC Library
 * @subpackage  Globalize
 * @link        https://www.joomlic.com
 *
 * @author      Cyril Rezé
 * @copyright   (c) 2013-2024 Cyril Rezé / iCagenda. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 *
 * @since       1.3.0
 *----------------------------------------------------------------------------
*/

defined('_JEXEC') or die;

/**
 * Globalization: Date formats fa-IR - Persian (Iran)
 *
 * Reference date : birth of a free, open web to all!
 * On 30 April 1993, CERN put the World Wide Web software in the public domain.
 */

// TODO : Remove Short month (not available in Persian, and could return errors)

$dateglobalize_1 = '1372/02/10';
$dateformat_1    = 'Y / m / d';

$dateglobalize_2 = JText::_('FEBRUARY') . ' 10, 1372';
$dateformat_2    = 'F _ d , _ Y';

$dateglobalize_4 = JText::_('FEBRUARY') . ' 10';
$dateformat_4    = 'F _ d';

$dateglobalize_6 = JText::_('FEBRUARY') . ' 1372';
$dateformat_6    = 'F _ Y';

$dateglobalize_7 = JText::_('FRIDAY') . ' , _ ' . JText::_('FEBRUARY'). ' 10, 1372';
$dateformat_7    = 'l , _ F _ d , _ Y';

$dateglobalize_8 = JText::_('FRIDAY') . ' , _ ' . JText::_('FEBRUARY'). ' 10';
$dateformat_8    = 'l , _ F _ d';


$dmy_text_5 = '10␣' . JText::_('FEBRUARY') . '␣1372';
$dmy_text_6 = '10␣' . JText::_('FEBRUARY_SHORT') . '␣1372';

$mdy_text_5 = JText::_('FEBRUARY') . '␣10␣1372';
$mdy_text_6 = JText::_('FEBRUARY_SHORT') . '␣10␣1372';

$ymd_text_5 = '1372␣' . JText::_('FEBRUARY') . '␣10';
$ymd_text_6 = '1372␣' . JText::_('FEBRUARY_SHORT') . '␣10';
