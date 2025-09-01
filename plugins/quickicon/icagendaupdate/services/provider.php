<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Plugin Quickicon - iCagenda Update Notification
 *----------------------------------------------------------------------------
 * @version     2.0.0 2024-02-14
 *
 * @package     iCagenda
 * @subpackage  Plugin.Quickicon.Icagenda
 * @link        https://www.icagenda.com
 *
 * @author      Cyril Rezé
 * @copyright   (c) 2013-2024 Cyril Rezé / iCagenda. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 *
 * @since       2.0.0
 *----------------------------------------------------------------------------
*/

\defined('_JEXEC') or die;

use Joomla\CMS\Extension\PluginInterface;
use Joomla\CMS\Factory;
use Joomla\CMS\Plugin\PluginHelper;
use Joomla\Database\DatabaseInterface;
use Joomla\DI\Container;
use Joomla\DI\ServiceProviderInterface;
use Joomla\Event\DispatcherInterface;
use W3biC\Plugin\Quickicon\Icagendaupdate\Extension\Icagendaupdate;

return new class () implements ServiceProviderInterface {
	/**
	 * Registers the service provider with a DI container.
	 *
	 * @param   Container  $container  The DI container.
	 *
	 * @return  void
	 *
	 * @since   2.0.0
	 */
	public function register(Container $container)
	{
		$container->set(
			PluginInterface::class,
			function (Container $container) {
				$config     = (array) PluginHelper::getPlugin('quickicon', 'icagendaupdate');
				$dispatcher = $container->get(DispatcherInterface::class);
				$document   = Factory::getApplication()->getDocument();

				$plugin = new Icagendaupdate(
					$dispatcher,
					$document,
					$config
				);

				$plugin->setApplication(Factory::getApplication());

				return $plugin;
			}
		);
	}
};
