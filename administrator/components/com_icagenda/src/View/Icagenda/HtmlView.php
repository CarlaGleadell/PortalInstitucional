<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.8.0 2021-12-25
 *
 * @package     iCagenda.Admin
 * @subpackage  src.View
 * @link        https://www.icagenda.com
 *
 * @author      Cyril Rezé
 * @copyright   (c) 2012-2024 Cyril Rezé / iCagenda. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 *
 * @since       1.0
 *----------------------------------------------------------------------------
*/

namespace WebiC\Component\iCagenda\Administrator\View\Icagenda;

\defined('_JEXEC') or die;

use WebiC\Component\iCagenda\Administrator\Helper\iCagendaHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\View\GenericDataException;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\CMS\Toolbar\Toolbar;
use Joomla\CMS\Toolbar\ToolbarHelper;
use Joomla\CMS\Uri\Uri;

/**
 * iCagenda Control Panel HTML view class.
 */
class HtmlView extends BaseHtmlView
{
	/**
	 * Display the view
	 */
	public function display($tpl = null)
	{
		$this->categoryStats  = $this->get('CategoryStats');
		$this->eventStats     = $this->get('EventStats');
		$this->eventHitsTotal = $this->get('EventHitsTotal');

		// Add Charts.js
		Factory::getDocument()->addScript(Uri::root( true ) . '/media/com_icagenda/js/Chart.min.js');

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			throw new GenericDataException(implode("\n", $errors), 500);

			return false;
		}

		$this->addToolbar();

		parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 */
	protected function addToolbar()
	{
		require_once JPATH_COMPONENT . '/helpers/icagenda.php';

		$app      = Factory::getApplication();
		$document = Factory::getDocument();

		$state = $this->get('State');
		$canDo = iCagendaHelper::getActions($state->get('filter.category_id'));

		$logo_icagenda_url = '../media/com_icagenda/images/iconicagenda36.png';

		if (file_exists($logo_icagenda_url))
		{
			$logo_icagenda = '<img src="' . $logo_icagenda_url . '" height="36px" alt="iCagenda" />';
		}
		else
		{
			$logo_icagenda = 'iCagenda :: ' . Text::_('COM_ICAGENDA_TITLE_ICAGENDA') . '';
		}

		ToolbarHelper::title($logo_icagenda, 'icagenda');

		$icTitle = Text::_('COM_ICAGENDA_TITLE_ICAGENDA');

		$sitename = $app->getCfg('sitename');
		$title    = $app->getCfg('sitename') . ' - ' . Text::_('JADMINISTRATION') . ' - iCagenda: ' . $icTitle;

		$document->setTitle($title);

		if ($canDo->get('core.admin') || $canDo->get('core.options'))
		{
			ToolbarHelper::preferences('com_icagenda');
		}
	}

	/**
	 * Save iCagenda Params
	 *
	 * Update Database
	 *
	 * @since   3.3.8
	 */
	public function saveDefault($var, $name, $value)
	{
		if ($var)
		{
			$params[$name] = $value;

			$this->updateParams( $params );
		}
	}

	/**
	 * Update iCagenda Params
	 *
	 * Update Database
	 *
	 * @since   3.3.8
	 */
	protected function updateParams($params_array)
	{
		// Read the existing component value(s)
		$db = Factory::getDbo();
		$db->setQuery('SELECT params FROM #__icagenda WHERE id = "3"');

		$params = json_decode( $db->loadResult(), true );

		// Add the new variable(s) to the existing one(s)
		foreach ( $params_array as $name => $value )
		{
			$params[ (string) $name ] = $value;
		}

		// Store the combined new and existing values back as a JSON string
		$paramsString = json_encode( $params );

		$db->setQuery('UPDATE #__icagenda SET params = ' .
		$db->quote( $paramsString ) . ' WHERE id = "3"' );
		$db->execute();
	}
}
