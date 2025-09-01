<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.8.0 2021-12-17
 *
 * @package     iCagenda.Admin
 * @subpackage  Utilities
 * @link        https://www.icagenda.com
 *
 * @author      Cyril Rezé
 * @copyright   (c) 2012-2024 Cyril Rezé / iCagenda. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 *
 * @since       3.8
 *----------------------------------------------------------------------------
*/

defined('_JEXEC') or die;

/**
 * class icagendaTiptip
 */
abstract class icagendaTiptip
{
	/**
	 * @var    array  Array containing information for loaded files
	 */
	protected static $loaded = array();

	/**
	 * Function to load and set plug-in library jQuery.tipTip used for iCtips
	 *
	 * @param   string  $selector  Common class for the tooltip.
	 * @param   array   $params    An array of options for the tooltip.
	 *                             Options for the tooltip can be:
	 *                             - activation       string   jQuery method TipTip is activated with.
	 *                                                         Can be set to: "hover", "focus" or "click".
	 *                             - keepAlive        boolean  When set to true the TipTip will only fadeout when you hover over
	 *                                                         the actual TipTip and then hover off of it.
	 *                             - maxWidth         string   CSS max-width property for the TipTip element.
	 *                                                         This is a string so you can apply a percentage rule or 'auto'.
	 *                             - edgeOffset       number   Distances the TipTip popup from the element with TipTip applied to it
	 *                                                         by the number of pixels specified.
	 *                             - defaultPosition  string   Default orientation TipTip should show up as.
	 *                                                         You can set it to: "top", "bottom", "left" or "right".
	 *
	 * @return  void
	 */
	public static function setTooltip($selector = '.iCtip', $params = array())
	{
		$sig = md5(serialize(array($selector, $params)));

		// Only load once
		if ( ! empty(static::$loaded[__METHOD__][$sig]))
		{
			return;
		}

		JHtml::_('jquery.framework');
		JHtml::_('stylesheet', 'com_icagenda/tipTip.css', array('relative' => true, 'version' => 'auto'));
		JHtml::_('script', 'com_icagenda/jquery.tipTip.js', array('relative' => true, 'version' => 'auto'));

		// Setup options object
		$opt['activation']      = isset($params['activation']) ? $params['activation'] : 'hover';
		$opt['keepAlive']       = isset($params['keepAlive']) ? (boolean) $params['keepAlive'] : false;
		$opt['maxWidth']        = isset($params['maxWidth']) ? $params['maxWidth'] : '200px';
		$opt['edgeOffset']      = isset($params['edgeOffset']) ? $params['edgeOffset'] : 1;
		$opt['defaultPosition'] = isset($params['defaultPosition']) ? $params['defaultPosition'] : 'top';

		$options = json_encode($opt);

		// Attach the tooltip to the document
		JFactory::getDocument()->addScriptDeclaration(
			'jQuery(function($){ $("' . $selector . '").tipTip(' . $options . '); });'
		);

		static::$loaded[__METHOD__][$sig] = true;

		return;
	}
}
