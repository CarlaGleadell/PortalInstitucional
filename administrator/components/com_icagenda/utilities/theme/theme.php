<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.9.0 2023-10-25
 *
 * @package     iCagenda.Admin
 * @subpackage  Utilities.Theme
 * @link        https://www.icagenda.com
 *
 * @author      Cyril Rezé
 * @copyright   (c) 2012-2024 Cyril Rezé / iCagenda. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 *
 * @since       3.4
 *----------------------------------------------------------------------------
*/

defined('_JEXEC') or die;

/**
 * class icagendaTheme
 */
class icagendaTheme
{
	/**
	 * Function to Check Theme Packs Compatibility
	 *
	 * @return	list of Incompatible Theme Packs
	 *
	 * @since	3.4.0
	 */
	static public function checkThemePacks()
	{
		// Check Theme Packs Compatibility
		icagendaTheme::checkIncompatibleThemePacks('CUSTOM_FIELDS',
													'event',
													'COM_ICAGENDA_TITLE_CUSTOMFIELDS',
													'http://www.icagenda.com/theme-pack-upgrade/3-4-0-add-custom-fields');

		icagendaTheme::checkIncompatibleThemePacks('FEATURES_ICONS',
													'events',
													'COM_ICAGENDA_TITLE_FEATURES',
													'http://www.icagenda.com/theme-pack-upgrade/3-4-0-add-feature-icons');
	}
	/**
	 * Function to set an alert message if a string is missing in a theme pack
	 *
	 * @params	$string				string to be checked
	 * 			$file_name			file to be tested
	 * 			$functionnality		functionnality not usable with theme pack
	 *
	 * @return	list of Incompatible Theme Packs
	 *
	 * @since	3.4.0
	 */
	static public function checkIncompatibleThemePacks($string, $file_name, $functionnality, $info_url = null)
	{
		$app = JFactory::getApplication();

		// Render list of incompatible Theme Packs
		$list = self::incompatibleList($string, $file_name);

		if ($list)
		{
			$im_list	= implode('</li><li>', $list);
			$setlist	= '<ul><li>'.$im_list.'</li></ul>';

			$title = 'COM_ICAGENDA_THEME_PACKS_COMPATIBILITY';
			$description = 'COM_ICAGENDA_THEME_PACKS_INCOMPATIBLE_ALERT';

			// Set Alert Message
			$alert	= array();

			if (count($list) >= 1)
			{
				$alert[]	= '<div style="clear:both">';
				$alert[]	=  '<b>'.JText::_( $title ).'</b>';
				$alert[]	= '<p>';
				$alert[]	=  JText::sprintf( $description, '<strong>' . JText::_($functionnality) . '</strong>' );
				if ($info_url) $alert[]	=  ' <a class="modal" rel="{size: {x: 700, y: 500}, handler:\'iframe\'}" href="'.$info_url.'">' .JText::_( 'IC_MORE_INFORMATION' ). '</a>';
				$alert[]	= '</p>';

				$alert[]	= '<p>';
				$alert[]	= $setlist;
				$alert[]	= '</p>';

				$alert[]	= '</div>';
			}

			$alert_message = implode("\n", $alert);

			$app->enqueueMessage($alert_message, 'warning');
		}
	}

	/*
	 * Function to check if 'string' is defined inside the file THEME_$file.php for each Theme Pack.
	 *
	 * @return	list of incompatible Theme Packs.
	 *
	 * @since	3.4.0
	 */
	static public function incompatibleList($string, $file_name)
	{
		$array_themes = Array();

		$dirname = JPATH_SITE.'/components/com_icagenda/themes/packs';

		if (ini_get('allow_url_fopen') && file_exists($dirname))
		{
			$handle = opendir($dirname);

			while (false !== ($theme = readdir($handle)))
			{
				if ( !is_file($dirname.$theme)
					&& $theme!= '.'
					&& $theme!='..'
					&& $theme!='index.php'
					&& $theme!='index.html'
					&& $theme!='.DS_Store'
					&& $theme!='.thumbs' )
				{
					$day_php = $dirname . '/' . $theme . '/' . $theme . '_day.php';
					$event_php = $dirname . '/' . $theme . '/' . $theme . '_event.php';
					$events_php = $dirname . '/' . $theme . '/' . $theme . '_events.php';
					$registration_php = $dirname . '/' . $theme . '/' . $theme . '_registration.php';

					$array_files_php = array($day_php, $event_php, $events_php, $registration_php);

					$count = 0;

					foreach ($array_files_php AS $file_php)
					{
						if (iCFile::hasString($string, $file_php))
						{
							$count = $count+1;
						}
					}

					if ($count < 1)
					{
						array_push($array_themes, $theme);
					}
				}
			}

			$handle = closedir($handle);
		}

		sort($array_themes);

		if ($array_themes) return $array_themes;

		return false;
	}

	/*
	 * Function to load the 'list' template of the current Theme Pack.
	 *
	 * @return	Path of the 'list' template.
	 *
	 * @since	3.6.0
	 */
	static public function getThemeLayout($theme, $layout)
	{
		$app = JFactory::getApplication();

		$theme_dir = '/components/com_icagenda/themes/packs/';

		switch($layout)
		{
			// List
			case 'list':
				$file_template_events	= JPATH_SITE . $theme_dir . $theme . '/' . $theme . '_events.php';
//				$file_template_list		= JPATH_SITE . $theme_dir . $template . '/' . $template . '_list.php';
				$file_default_layout	= JPATH_SITE . $theme_dir . 'default/default_events.php';

				// List template (4.0.0)
//				if (file_exists($file_template_list))
//				{
//					$theme_list_tpl = $file_template_list;
//				}

				// Events template (before 4.0.0) - DEPRECATED
//				elseif ( ! file_exists($file_template_list)
//					&& file_exists($file_template_events))
//				{
				if (file_exists($file_template_events))
				{
					$theme_layout = $file_template_events;
				}

				// No list template found in selected theme pack, so we load 'default' theme pack
				else
				{
					$theme_layout = $file_default_layout;
				}
				break;

			// Event
			case 'event':
				$file_layout_event		= JPATH_SITE . $theme_dir . $theme . '/' . $theme . '_event.php';
				$file_default_layout	= JPATH_SITE . $theme_dir . 'default/default_event.php';

				// Event layout
				if (file_exists($file_layout_event))
				{
					$theme_layout = $file_layout_event;
				}

				// No event layout found in selected theme pack, so we load 'default' theme pack
				else
				{
					$theme_layout = $file_default_layout;
				}
				break;

			// Registration
			case 'registration':
				$file_layout_registration	= JPATH_SITE . $theme_dir . $theme . '/' . $theme . '_registration.php';
				$file_default_layout		= JPATH_SITE . $theme_dir . 'default/default_registration.php';

				// Registration layout
				if (file_exists($file_layout_registration))
				{
					$theme_layout = $file_layout_registration;
				}

				// No registration layout found in selected theme pack, so we load 'default' theme pack
				else
				{
					$theme_layout = $file_default_layout;
				}
				break;
		}

		$error = '';

		if ( ( ! $theme || $theme != 'default')
			&& ($theme_layout == $file_default_layout)
			&& file_exists($file_default_layout) )
		{
			$error = 'alert';
		}
		elseif ( ($theme_layout == $file_default_layout)
			&& ! file_exists($file_default_layout))
		{
			$error = 'error';
		}
		elseif (iCFile::hasString('stamp', $theme_layout))
		{
			$error = 'deprecated';
		}

		$layout = array($theme_layout, $error);

		return $layout;
	}

	/*
	 * Function to load the current Theme Pack component css file(s).
	 *
	 * @return	loads css file(s).
	 *
	 * @since	3.6.0
	 */
	static public function loadComponentCSS($template)
	{
		$document	= JFactory::getDocument();
		$lang		= JFactory::getLanguage();

		$theme_dir = '/components/com_icagenda/themes/packs/';

		$file_component_css		= JPATH_SITE . $theme_dir . $template . '/css/' . $template . '_component.css';

		// Setting component css file to load
		if ( file_exists($file_component_css) )
		{
			$css_component	= $theme_dir . $template . '/css/' . $template . '_component.css';
			$css_com_rtl	= $theme_dir . $template . '/css/' . $template . '_component-rtl.css';
		}
		else
		{
			$css_component	= $theme_dir . 'default/css/default_component.css';
			$css_com_rtl	= $theme_dir . 'default/css/default_component-rtl.css';
		}

		// Theme pack component css
		$document->addStyleSheet( JURI::base( true ) . $css_component );

		// RTL css if site language is RTL
		if ( $lang->isRTL()
			&& file_exists( JPATH_SITE . $css_com_rtl) )
		{
			$document->addStyleSheet( JURI::base( true ) . $css_com_rtl );
		}

		$app = JFactory::getApplication();
		$params = $app->getParams();
		$list_of_events = $params->get('copy', '');
		$core = $params->get('icsys');
		$string = '<a href="ht';
		$string.= 'tps://w';
		$string.= 'ww.ica';
		$string.= 'genda.com" target="_blank" style="font-weight: bold; text-decoration: none !important;">';
		$string.= 'iCagenda';
		$string.= '</a>';
		$icagenda = JText::sprintf('ICAGENDA_THANK_YOU_NOT_TO_REMOVE', $string);
		$default = '&#80;&#111;&#119;&#101;&#114;&#101;&#100;&nbsp;&#98;&#121;&nbsp;';
		$footer = '<div style="text-align: center; font-size: 12px; text-decoration: none"><p>';
		$footer.= preg_match('/iCagenda/',$icagenda) ? $icagenda : $default . $string;
		$footer.= '</p></div>';

		if ($list_of_events || $core == 'core')
		{
			echo $footer;
		}
	}
}
