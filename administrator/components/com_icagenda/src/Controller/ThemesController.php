<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.8.0 2021-09-26
 *
 * @package     iCagenda.Admin
 * @subpackage  src.Controller
 * @link        https://www.icagenda.com
 *
 * @author      Cyril Rezé
 * @copyright   (c) 2012-2024 Cyril Rezé / iCagenda. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 *
 * @since       2.0
 *----------------------------------------------------------------------------
*/

namespace WebiC\Component\iCagenda\Administrator\Controller;

\defined('_JEXEC') or die;

use Joomla\CMS\Client\ClientHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\Controller\FormController;
use Joomla\CMS\MVC\Factory\MVCFactoryInterface;

/**
 * iCagenda Component Themes Controller
 */
class ThemesController extends FormController
{
	protected $option = 'com_icagenda';

	/**
	 * Constructor
	 *
	 * @param   array                $config   An optional associative array of configuration settings.
	 * Recognized key values include 'name', 'default_task', 'model_path', and
	 * 'view_path' (this list is not meant to be comprehensive).
	 * @param   MVCFactoryInterface  $factory  The factory.
	 * @param   CMSApplication       $app      The JApplication for the dispatcher
	 * @param   Input                $input    Input
	 */
	public function __construct($config = array(), MVCFactoryInterface $factory = null, $app = null, $input = null)
	{
		parent::__construct($config, $factory, $app, $input);

		$this->registerTask('themeinstall', 'themeinstall');
	}

	/**
	 * Theme Install
	 */
	function themeinstall()
	{
		// Check for request forgeries.
		$this->checkToken('request');

		$post  = Factory::getApplication()->input->post;
		$theme = array();

		if ($post->get('theme_component'))
		{
			$theme['component'] = 1;
		}

		if (empty($theme))
		{
			$ftp = ClientHelper::setCredentialsFromRequest('ftp');

			$model = $this->getModel('themes');

			if ($model->install($theme))
			{
				$cache = Factory::getCache('mod_menu');
				$cache->clean();

				$msg = Text::_('COM_ICAGENDA_SUCCESS_THEME_INSTALLED');
			}
		}
		else
		{
			$msg = Text::_('COM_ICAGENDA_ERROR_THEME_APPLICATION_AREA');
		}

		$this->setRedirect('index.php?option=com_icagenda&view=themes', $msg);
	}
}
