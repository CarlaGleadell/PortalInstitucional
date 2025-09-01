<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.6.14 2018-05-01
 *
 * @package     iCagenda.Site
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

jimport('joomla.application.component.model');

/**
 * iCagenda Model
 */
class iCagendaModelicagenda extends JModelItem
{
	/**
	 * @var  object  item
	 */
	protected $item;

	function getText()
	{
		$db = JFactory::getDbo();
		$query = 'SELECT * FROM #__icagenda';
		$db->setQuery( $query );

		$value = $db->loadObjectList();

		return $value;
	}
 
	/**
	 * Method to auto-populate the model state.
	 *
	 * This method should only be called once per instantiation and is designed
	 * to be called on the first call to the getState() method unless the model
	 * configuration flag to ignore the request is set.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	protected function populateState()
	{
		$app    = JFactory::getApplication();
		$jinput = $app->input;

		// Get the message id
		$id = $jinput->getInt('id');

		$this->setState('message.id', $id);

		// Load the parameters.
		$params = $app->getParams();

		$this->setState('params', $params);

		parent::populateState();
	}
 
	/**
	 * Returns a reference to the a Table object, always creating it.
	 *
	 * @param   type    The table type to instantiate
	 * @param   string  A prefix for the table class name. Optional.
	 * @param   array   Configuration array for model. Optional.
	 * @return  JTable  A database object
	 *
	 * @since   1.0
	 */
	public function getTable($type = 'iCagenda', $prefix = 'iCagendaTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}
 
}