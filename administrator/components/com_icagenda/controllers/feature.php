<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.8.0 2019-07-19
 *
 * @package     iCagenda.Admin
 * @link        https://www.icagenda.com
 *
 * @author      doorknob / Cyril Rezé
 * @copyright   (c) 2012-2024 Cyril Rezé / iCagenda. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 *
 * @since       3.4.0
 *----------------------------------------------------------------------------
*/

defined('_JEXEC') or die;

/**
 * The feature controller
 */
class iCagendaControllerFeature extends JControllerForm
{
	/**
	 * Class constructor.
	 *
	 * @param   array  $config  A named array of configuration variables.
	 *
	 * @since   3.4.0
	 */
	public function __construct($config = array())
	{
		parent::__construct($config);
	}
}
