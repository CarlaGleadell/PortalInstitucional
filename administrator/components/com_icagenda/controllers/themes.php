<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.8.2 2022-03-04
 *
 * @package     iCagenda.Admin
 * @link        https://www.icagenda.com
 *
 * @author      Cyril Rezé
 * @copyright   (c) 2012-2024 Cyril Rezé / iCagenda. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 *
 * @since       2.0
 *----------------------------------------------------------------------------
*/

defined('_JEXEC') or die;

jimport('joomla.application.component.controllerform');
jimport('joomla.client.helper');

class iCagendaControllerthemes extends JControllerForm
{
	protected $option = 'com_icagenda';

	function __construct()
	{
		parent::__construct();

		$this->registerTask('themeinstall', 'themeinstall');
	}

	function themeinstall()
	{
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		$post  = JFactory::getApplication()->input->post;
		$theme = array();

		if ($post->get('theme_component'))
		{
			$theme['component'] = 1;
		}

		if (empty($theme))
		{
			$ftp =& JClientHelper::setCredentialsFromRequest('ftp');

			$model = &$this->getModel('themes');

			if ($model->install($theme))
			{
				$cache = JFactory::getCache('mod_menu');
				$cache->clean();

				$msg = JText::_('COM_ICAGENDA_SUCCESS_THEME_INSTALLED');
			}
		}
		else
		{
			$msg = JText::_('COM_ICAGENDA_ERROR_THEME_APPLICATION_AREA');
		}

		$this->setRedirect('index.php?option=com_icagenda&view=themes', $msg);
	}
}
