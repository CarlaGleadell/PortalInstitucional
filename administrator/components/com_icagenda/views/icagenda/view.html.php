<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.9.0 2023-10-26
 *
 * @package     iCagenda.Admin
 * @link        https://www.icagenda.com
 *
 * @author      Cyril Rezé
 * @copyright   (c) 2012-2024 Cyril Rezé / iCagenda. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 *
 * @since       1.0
 *----------------------------------------------------------------------------
*/

defined('_JEXEC') or die;

jimport('joomla.filesystem.path');

// Access check.
if (JFactory::getUser()->authorise('core.admin', 'com_icagenda'))
{
	JToolBarHelper::preferences('com_icagenda');
}

/**
 * View class for a list of iCagenda.
 */
class iCagendaViewicagenda extends JViewLegacy
{
	/**
	 * Display the view
	 */
	public function display($tpl = null)
	{
		$document = JFactory::getDocument();

		$this->categoryStats  = $this->get('CategoryStats');
		$this->eventStats     = $this->get('EventStats');
		$this->eventHitsTotal = $this->get('EventHitsTotal');

		JHtml::_('behavior.modal');

		// Add Charts.js
		$document->addScript( JURI::root( true ) . '/media/com_icagenda/js/Chart.min.js' );

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			throw new Exception(implode("\n", $errors), 500);

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

		$app      = JFactory::getApplication();
		$document = JFactory::getDocument();

		$state = $this->get('State');
		$canDo = iCagendaHelper::getActions($state->get('filter.category_id'));

		// Set Title
		$logo_icagenda_url = '../media/com_icagenda/images/iconicagenda36.png';

		if (file_exists($logo_icagenda_url))
		{
			$logo_icagenda = '<img src="' . $logo_icagenda_url . '" height="36px" alt="iCagenda" />';
		}
		else
		{
			$logo_icagenda = 'iCagenda :: ' . JText::_('COM_ICAGENDA_TITLE_ICAGENDA') . '';
		}

		JToolBarHelper::title($logo_icagenda, 'icagenda');

		$icTitle = JText::_('COM_ICAGENDA_TITLE_ICAGENDA');

		$sitename = $app->getCfg('sitename');
		$title    = $app->getCfg('sitename') . ' - ' . JText::_('JADMINISTRATION') . ' - iCagenda: ' . $icTitle;

		$document->setTitle($title);
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
		$db = JFactory::getDbo();
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
		$db->query();
	}
}
