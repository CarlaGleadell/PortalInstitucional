<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.9.0 2024-02-22
 *
 * @package     iCagenda.Admin
 * @subpackage  src.Extension
 * @link        https://www.icagenda.com
 *
 * @author      Cyril Rezé
 * @copyright   (c) 2012-2024 Cyril Rezé / iCagenda. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 *
 * @since       3.8
 *----------------------------------------------------------------------------
*/

namespace WebiC\Component\iCagenda\Administrator\Extension;

\defined('JPATH_PLATFORM') or die;

use Joomla\CMS\Application\SiteApplication;
use Joomla\CMS\Component\Router\RouterServiceInterface;
use Joomla\CMS\Component\Router\RouterServiceTrait;
use Joomla\CMS\Extension\BootableExtensionInterface;
use Joomla\CMS\Extension\MVCComponent;
use Joomla\CMS\HTML\HTMLRegistryAwareTrait;
use Joomla\CMS\Plugin\PluginHelper;
use Psr\Container\ContainerInterface;
use WebiC\Component\iCagenda\Administrator\Service\HTML\Themes;

/**
 * Component class for com_icagenda
 */
class iCagendaComponent extends MVCComponent implements
	RouterServiceInterface,
	BootableExtensionInterface
{
	use RouterServiceTrait;
	use HTMLRegistryAwareTrait;

	/**
	 * The trashed condition
	 */
	const CONDITION_NAMES = [
		self::CONDITION_PUBLISHED   => 'JPUBLISHED',
		self::CONDITION_UNPUBLISHED => 'JUNPUBLISHED',
		self::CONDITION_ARCHIVED    => 'JARCHIVED',
		self::CONDITION_TRASHED     => 'JTRASHED',
	];

	/**
	 * The archived condition
	 */
	const CONDITION_ARCHIVED = 2;

	/**
	 * The published condition
	 */
	const CONDITION_PUBLISHED = 1;

	/**
	 * The unpublished condition
	 */
	const CONDITION_UNPUBLISHED = 0;

	/**
	 * The trashed condition
	 */
	const CONDITION_TRASHED = -2;

	/**
	 * Booting the extension. This is the function to set up the environment of the extension like
	 * registering new class loaders, etc.
	 *
	 * If required, some initial set up can be done from services of the container, eg.
	 * registering HTML services.
	 *
	 * @param   ContainerInterface  $container  The container
	 *
	 * @return  void
	 */
	public function boot(ContainerInterface $container)
	{
		// Loads Utilities
		\JLoader::registerNamespace('iCutilities', JPATH_ADMINISTRATOR . '/components/com_icagenda/src/Utilities');

		PluginHelper::importPlugin('icagenda');

		$this->getRegistry()->register('themes', new Themes);
	}
}
