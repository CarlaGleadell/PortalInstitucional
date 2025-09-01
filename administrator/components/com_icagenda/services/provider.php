<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.8.0 2021-09-29
 *
 * @package     iCagenda.Admin
 * @subpackage  services
 * @link        https://www.icagenda.com
 *
 * @author      Cyril Rezé
 * @copyright   (c) 2012-2024 Cyril Rezé / iCagenda. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 *
 * @since       3.8.0
 *----------------------------------------------------------------------------
*/

defined('_JEXEC') or die;

//use Joomla\CMS\Association\AssociationExtensionInterface;
//use Joomla\CMS\Categories\CategoryFactoryInterface;
use Joomla\CMS\Component\Router\RouterFactoryInterface;
use Joomla\CMS\Dispatcher\ComponentDispatcherFactoryInterface;
use Joomla\CMS\Extension\ComponentInterface;
//use Joomla\CMS\Extension\Service\Provider\CategoryFactory;
use Joomla\CMS\Extension\Service\Provider\ComponentDispatcherFactory;
use Joomla\CMS\Extension\Service\Provider\MVCFactory;
use Joomla\CMS\Extension\Service\Provider\RouterFactory;
use Joomla\CMS\HTML\Registry;
use Joomla\CMS\MVC\Factory\MVCFactoryInterface;
use Joomla\DI\Container;
use Joomla\DI\ServiceProviderInterface;
use WebiC\Component\iCagenda\Administrator\Extension\iCagendaComponent;
//use WebiC\Component\iCagenda\Administrator\Helper\AssociationsHelper;

/**
 * The iCagenda service provider.
 */
return new class implements ServiceProviderInterface
{
	/**
	 * Registers the service provider with a DI container.
	 *
	 * @param   Container  $container  The DI container.
	 *
	 * @return  void
	 */
	public function register(Container $container)
	{
//		$container->set(AssociationExtensionInterface::class, new AssociationsHelper);

//		$container->registerServiceProvider(new CategoryFactory('\\WebiC\\Component\\iCagenda'));
		$container->registerServiceProvider(new MVCFactory('\\WebiC\\Component\\iCagenda'));
		$container->registerServiceProvider(new ComponentDispatcherFactory('\\WebiC\\Component\\iCagenda'));
		$container->registerServiceProvider(new RouterFactory('\\WebiC\\Component\\iCagenda'));

		$container->set(
			ComponentInterface::class,
			function (Container $container)
			{
				$component = new iCagendaComponent($container->get(ComponentDispatcherFactoryInterface::class));

				$component->setRegistry($container->get(Registry::class));
				$component->setMVCFactory($container->get(MVCFactoryInterface::class));
//				$component->setCategoryFactory($container->get(CategoryFactoryInterface::class));
//				$component->setAssociationExtension($container->get(AssociationExtensionInterface::class));
				$component->setRouterFactory($container->get(RouterFactoryInterface::class));

				return $component;
			}
		);
	}
};
