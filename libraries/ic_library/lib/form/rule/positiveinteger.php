<?php
/**
 *----------------------------------------------------------------------------
 * iC Library   Library by Jooml!C, for Joomla!
 *----------------------------------------------------------------------------
 * @version     2.0.0 2021-10-10
 *
 * @package     iC Library
 * @subpackage  Form.Rule
 * @link        https://www.joomlic.com
 *
 * @author      Cyril Rezé
 * @copyright   (c) 2013-2024 Cyril Rezé / iCagenda. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 *
 * @since       1.4.2
 *----------------------------------------------------------------------------
*/

\defined('JPATH_PLATFORM') or die;

use Joomla\Registry\Registry;

/**
 * Form Rule class.
 *
 * Positive integer validation.
 */
class iCFormRulePositiveinteger extends JFormRule
{
	/**
	 * The regular expression to use in testing a form field value.
	 *
	 * @var     string
	 */
	protected $regex = '^(\s*|[1-9][0-9]*$)$';
}
