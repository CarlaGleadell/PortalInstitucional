<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.8.3 2022-03-22
 *
 * @package     iCagenda.Admin
 * @subpackage  src.Service.HTML
 * @link        https://www.icagenda.com
 *
 * @author      Cyril Rezé
 * @copyright   (c) 2012-2024 Cyril Rezé / iCagenda. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 *
 * @since       3.8.0
 *----------------------------------------------------------------------------
*/

namespace WebiC\Component\iCagenda\Administrator\Service\HTML;

\defined('_JEXEC') or die;

use iClib\Url\Url as iCUrl;
use Joomla\CMS\Application\ApplicationHelper;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Uri\Uri;

/**
 * iCagenda component HTML Helper.
 */
class Themes
{
	/**
	 * Display the themes list.
	 *
	 * @return  string  The html string
	 */
	public function list()
	{
		$url    = JPATH_SITE . '/components/com_icagenda/themes/packs';
		$urlxml = JPATH_SITE . '/components/com_icagenda/themes/';

		$nb_themes = 0;

		$html = '<div class="row">';

		if ($dossier = opendir($url))
		{
			$excluded_files = array('index.php', 'index.html', '.', '..', '.DS_Store', '.thumbs');

			$displayData = array();

			while (false !== ($pack = readdir($dossier)))
			{
				if ( ! in_array($pack, $excluded_files))
				{
					// Theme found
					$nb_themes++;

					$xml = '.xml';
					$themeurl = $urlxml . $pack . $xml;

					// Get elements from manifest install file.
					$dom = new \DomDocument;
					$dom->load($themeurl);

					$themeUpdate = $dom->getElementsByTagName('themeUpdate');
					$themeUpdate = (($themeUpdate->length > 0) && $themeUpdate[0]->firstChild) ? $themeUpdate[0]->firstChild->nodeValue : '';

					$urltheme = $themeUpdate . '/' . $pack . '/update.xml'; // @todo: change for a full update theme server url.

					// Test if update server.
					if (iCUrl::url_exists($urltheme))
					{
						// Get info from distant update server.
						$update_dom = new \DomDocument;
						$update_dom->load($urltheme);

						$updateVersion  = $update_dom->getElementsByTagName('version');
						$updateDownload = $update_dom->getElementsByTagName('download');

						$updateVersion  = (($updateVersion->length > 0) && $updateVersion[0]->firstChild) ? $updateVersion[0]->firstChild->nodeValue : '';
						$updateDownload = (($updateDownload->length > 0) && $updateDownload[0]->firstChild) ? $updateDownload[0]->firstChild->nodeValue : '';
					}
					else
					{
						$updateVersion  = '';
						$updateDownload = '';
					}

					$name          = $dom->getElementsByTagName('name');
					$version       = $dom->getElementsByTagName('version');
					$creationDate  = $dom->getElementsByTagName('creationDate');
					$author        = $dom->getElementsByTagName('author');
					$authorEmail   = $dom->getElementsByTagName('authorEmail');
					$authorWebsite = $dom->getElementsByTagName('authorWebsite');
					$authorUrl     = $dom->getElementsByTagName('authorUrl');
					$description   = $dom->getElementsByTagName('description');

					$name          = (($name->length > 0) && $name[0]->firstChild) ? $name[0]->firstChild->nodeValue : '';
					$version       = (($version->length > 0) && $version[0]->firstChild) ? $version[0]->firstChild->nodeValue : '';
					$creationDate  = (($creationDate->length > 0) && $creationDate[0]->firstChild) ? $creationDate[0]->firstChild->nodeValue : '';
					$author        = (($author->length > 0) && $author[0]->firstChild) ? $author[0]->firstChild->nodeValue : '';
					$authorEmail   = (($authorEmail->length > 0) && $authorEmail[0]->firstChild) ? $authorEmail[0]->firstChild->nodeValue : '';
					$authorWebsite = (($authorWebsite->length > 0) && $authorWebsite[0]->firstChild) ? $authorWebsite[0]->firstChild->nodeValue : '';
					$authorUrl     = (($authorUrl->length > 0) && $authorUrl[0]->firstChild) ? $authorUrl[0]->firstChild->nodeValue : '';
					$description   = (($description->length > 0) && $description[0]->firstChild) ? $description[0]->firstChild->nodeValue : '';

					$displayData = array(
						'author'         => $author,
						'authorEmail'    => $authorEmail,
						'authorUrl'      => $authorUrl,
						'authorWebsite'  => $authorWebsite,
						'description'    => $description,
						'name'           => $name,
						'pack'           => $pack,
						'updateDownload' => $updateDownload,
						'updateVersion'  => $updateVersion,
						'version'        => $version,
					);

					$html.= '<div class="col-3">';
					$html.= LayoutHelper::render('icagenda.admin.theme_pack_item', $displayData);
					$html.= '</div>';
				}
			}

			closedir($dossier);
		}
		else
		{
			$html.=  'ERROR: Folder not opened!';
		}

		$html.= '</div>';
		$html.= '<br />';
		$html.= '<div>';
		$html.= Text::plural('COM_ICAGENDA_THEMES_TOTAL_INSTALLED_N', $nb_themes);
		$html.= '</div>';

		return $html;
	}

	/**
	 * Display the thumb for the theme.
	 *
	 * @param   array    $theme      The info about the theme (Name, slug).
	 * @param   integer  $clientId   The application client ID the template applies to
	 *
	 * @return  string  The html string
	 */
	public function thumb($theme, $clientId = 0)
	{
		$themename = $theme['name'];
		$themepack = $theme['slug'];

		$themeImagesPath = 'components/com_icagenda/themes/packs/' . $themepack . '/images';

		$client   = ApplicationHelper::getClientInfo($clientId);
		$basePath = $client->path . '/' . $themeImagesPath;
		$thumb    = $basePath . '/' . $themepack . '_thumbnail.png';
		$preview  = $basePath . '/' . $themepack . '_preview.png';

		$html = '';

		if (file_exists($thumb))
		{
			$clientPath = ($clientId == 0) ? '' : 'administrator/';
			$thumb      = $clientPath . $themeImagesPath . '/' . $themepack . '_thumbnail.png';

			$html = HTMLHelper::_('image', $thumb, Text::_('COM_ICAGENDA_THEMES_PREVIEW'));

			if (file_exists($preview))
			{
				$html = '<button type="button" data-bs-target="#' . $themepack . '-Modal" class="thumbnail" data-bs-toggle="modal" title="'. Text::_('COM_ICAGENDA_THEMES_CLICK_TO_ENLARGE') . '">' . $html . '</button>';
			}
		}

		return $html;
	}

	/**
	 * Renders the html for the modal linked to thumb.
	 *
	 * @param   array    $theme      The info about the Theme (Name, slug).
	 * @param   integer  $clientId   The application client ID the template applies to
	 *
	 * @return  string  The html string
	 */
	public function thumbModal($theme, $clientId = 0)
	{
		$themename = $theme['name'];
		$themepack = $theme['slug'];

		$themeImagesPath = 'components/com_icagenda/themes/packs/' . $themepack . '/images';

		$client   = ApplicationHelper::getClientInfo($clientId);
		$basePath = $client->path . '/' . $themeImagesPath;
		$baseUrl  = ($clientId == 0) ? Uri::root(true) : Uri::root(true) . '/administrator';
		$thumb    = $basePath . '/' . $themepack . '_thumbnail.png';
		$preview  = $basePath . '/' . $themepack . '_preview.png';

		$html = '';

		if (file_exists($thumb) && file_exists($preview))
		{
			$preview = $baseUrl . '/' . $themeImagesPath . '/' . $themepack . '_preview.png';
			$footer = '<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">'
				. Text::_('JTOOLBAR_CLOSE') . '</button>';

			$html .= HTMLHelper::_(
				'bootstrap.renderModal',
				$themepack . '-Modal',
				array(
					'title'  => Text::sprintf('COM_ICAGENDA_THEMES_SCREENSHOT', $themename),
					'height' => '500px',
					'width'  => '800px',
					'footer' => $footer,
				),
				$body = '<div><img src="' . $preview . '" style="max-width:100%" alt="' . $themepack . '"></div>'
			);
		}

		return $html;
	}
}
