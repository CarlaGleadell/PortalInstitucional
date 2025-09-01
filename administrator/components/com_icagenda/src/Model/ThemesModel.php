<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.9.0 2023-10-25
 *
 * @package     iCagenda.Admin
 * @subpackage  src.Model
 * @link        https://www.icagenda.com
 *
 * @author      Cyril Rezé
 * @copyright   (c) 2012-2024 Cyril Rezé / iCagenda. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 *
 * @since       2.0
 *----------------------------------------------------------------------------
*/

namespace WebiC\Component\iCagenda\Administrator\Model;

\defined('_JEXEC') or die;

use Joomla\Archive\Archive;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Filesystem\File;
use Joomla\CMS\Filesystem\Folder;
use Joomla\CMS\Filesystem\Path;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Log\Log;
use Joomla\CMS\MVC\Model\ListModel;
use Joomla\CMS\Table\Table;

/**
 * iCagenda Component Themes Model
 */
class ThemesModel extends ListModel
{
	protected $_paths = array();

	protected $_manifest = null;

	protected $option = 'com_icagenda';

	protected $text_prefix = 'com_icagenda';

	/**
	 * Constructor.
	 *
	 * @param   array  $config  An optional associative array of configuration settings.
	 */
	public function __construct($config = array())
	{
		parent::__construct($config);
	}

	function install($theme)
	{
		$app     = Factory::getApplication();
		$db      = Factory::getDbo();
		$package = $this->_getPackageFromUpload();

		if ( ! $package)
		{
			$app->enqueueMessage(Text::_('COM_ICAGENDA_ERROR_FIND_INSTALL_PACKAGE'), 'warning');
			$this->deleteTempFiles();

			return false;
		}

		if ($package['dir'] && Folder::exists($package['dir']))
		{
			$this->setPath('source', $package['dir']);
		}
		else
		{
			$app->enqueueMessage(Text::_('COM_ICAGENDA_ERROR_INSTALL_PATH_NOT_EXISTS'), 'warning');
			$this->deleteTempFiles();

			return false;
		}

		// We need to find the installation manifest file
		if ( ! $this->_findManifest())
		{
			$app->enqueueMessage(Text::_('COM_ICAGENDA_ERROR_FIND_INFO_INSTALL_PACKAGE'), 'warning');
			$this->deleteTempFiles();

			return false;
		}

		// Files - copy files in manifest
		foreach ($this->_manifest->children() as $child)
		{
//			if (is_a($child, 'JXMLElement') && $child->getName() == 'files')
			if ($child->getName() == 'files')
			{
				if ($this->parseFiles($child) === false)
				{
					$app->enqueueMessage(Text::_('COM_ICAGENDA_ERROR_FIND_INFO_INSTALL_PACKAGE'), 'warning');
					$this->deleteTempFiles();

					return false;
				}
			}
		}

		// File - copy the xml file
		$copyFile     = array();
		$path['src']  = $this->getPath( 'manifest' ); // XML file will be copied too
		$path['dest'] = JPATH_SITE . '/components/com_icagenda/themes/' . basename($this->getPath('manifest'));
		$copyFile[]   = $path;

		$this->copyFiles($copyFile, array());
		$this->deleteTempFiles();

		// -------------------
		// Themes
		// -------------------
		// Params -  Get new themes params
		$paramsThemes = $this->getParamsThemes();


		// -------------------
		// Component
		// -------------------
		if (isset($theme['component']) && $theme['component'] == 1 )
		{
			$component = 'com_icagenda';
			$paramsC   = ComponentHelper::getParams($component) ;

			foreach ($paramsThemes as $keyT => $valueT)
			{
				$paramsC->set($valueT['name'], $valueT['value']);
			}

			$data['params'] = $paramsC->toArray();
			$table          = Table::getInstance('extension');

			$idCom          = $table->find( array('element' => $component ));

			$table->load($idCom);

			if ( ! $table->bind($data))
			{
				$app->enqueueMessage(Text::_('Not a valid component'), 'warning');

				return false;
			}

			// pre-save checks
			if ( ! $table->check())
			{
				$app->enqueueMessage(Text::_('Check Problem'), 'warning');

				return false;
			}

			// save the changes
			if ( ! $table->store())
			{
				$app->enqueueMessage(Text::_('Store Problem'), 'warning');

				return false;
			}
		}

		return true;
	}

	function _getPackageFromUpload()
	{
		$app = Factory::getApplication();

		// Get the uploaded file information
		// Do not change the filter type 'raw'. We need this to let files containing PHP code to upload. See JInputFiles::get.
		$userfile = $app->input->files->get('Filedata', null, 'raw');

		// Make sure that file uploads are enabled in php
		if ( ! (bool) ini_get('file_uploads'))
		{
			$app->enqueueMessage(Text::_('COM_ICAGENDA_ERROR_INSTALL_FILE_UPLOAD'), 'warning');

			return false;
		}

		// Make sure that zlib is loaded so that the package can be unzipped
		if ( ! extension_loaded('zlib'))
		{
			$app->enqueueMessage(Text::_('COM_ICAGENDA_ERROR_INSTALL_ZLIB'), 'warning');

			return false;
		}

		// If there is no uploaded file, we have a problem...
		if ( ! is_array($userfile))
		{
			$app->enqueueMessage(Text::_('COM_ICAGENDA_ERROR_NO_FILE_SELECTED'), 'warning');

			return false;
		}

		// Check if there was a problem uploading the file.
		if ($userfile['error'] || $userfile['size'] < 1)
		{
			$app->enqueueMessage(Text::_('COM_ICAGENDA_ERROR_UPLOAD_FILE'), 'warning');

			return false;
		}

		// Build the appropriate paths
		$config   = Factory::getConfig();
		$tmp_dest = $config->get('tmp_path') . '/' . $userfile['name'];

		$tmp_src = $userfile['tmp_name'];

		// Move uploaded file
		$uploaded = File::upload($tmp_src, $tmp_dest, false, true);

		// Unpack the downloaded package file
		$package = self::unpack($tmp_dest);

		$this->_manifest =& $manifest;

		$this->setPath('packagefile', $package['packagefile']);
		$this->setPath('extractdir', $package['extractdir']);

		return $package;
	}

	function getPath($name, $Default = null)
	{
		return ( ! empty($this->_paths[$name])) ? $this->_paths[$name] : $Default;
	}

	function setPath($name, $value)
	{
		$this->_paths[$name] = $value;
	}

	function _findManifest()
	{
		$app = Factory::getApplication();

		// Get an array of all the xml files from teh installation directory
		$xmlfiles = Folder::files($this->getPath('source'), '.xml$', 1, true);

		// If at least one xml file exists
		if (count($xmlfiles) > 0)
		{
			foreach ($xmlfiles as $file)
			{
				// Is it a valid joomla installation manifest file?
				$manifest = $this->_isManifest($file);

				if ( ! is_null($manifest))
				{
					$attr = $manifest->attributes();

					if ((string) $attr->method != 'icthemes')
					{
						$app->enqueueMessage(Text::_('COM_ICAGENDA_ERROR_NO_THEME_FILE'), 'warning');

						return false;
					}

					// Set the manifest object and path
					$this->_manifest =& $manifest;
					$this->setPath('manifest', $file);

					// Set the installation source path to that of the manifest file
					$this->setPath('source', dirname($file));

					return true;
				}
			}

			// None of the xml files found were valid install files
			$app->enqueueMessage(Text::_('COM_ICAGENDA_ERROR_XML_INSTALL_ICAGENDA'), 'warning');

			return false;
		}
		else
		{
			// No xml files were found in the install folder
			$app->enqueueMessage(Text::_('COM_ICAGENDA_ERROR_XML_INSTALL'), 'warning');

			return false;
		}
	}

	function _isManifest($file)
	{
//		$xml = Factory::getXML($file, true);
		$xml = simplexml_load_file($file);

		if ( ! $xml)
		{
			unset ($xml);

			return null;
		}

		if ( ! is_object($xml) || ($xml->getName() != 'install' && $xml->getName() != 'installtheme'))
		{
			unset ($xml);

			return null;
		}

		return $xml;
	}

	function parseFiles($element, $cid = 0)
	{
		$copyfiles   = array();
		$copyfolders = array();

//		if ( ! is_a($element, 'JXMLElement') || ! count($element->children()))
		if ( ! count($element->children()))
		{
			return 0; // Either the tag does not exist or has no children therefore we return zero files processed.
		}

		$files = $element->children();// Get the array of file nodes to process

		if (count($files) == 0)
		{
			return 0; // No files to process
		}

		$source       = $this->getPath('source');
		$destination  = JPATH_SITE . '/components/com_icagenda/themes';
		$destination2 = JPATH_SITE . '/components/com_icagenda/themes/packs';

		if ( ! empty($files->folder))
		{
			foreach ($files->folder as $fk => $fv)
			{
				$path['src']   = $source . '/' . $fv;
				$path['dest']  = $destination2 . '/' . $fv;
				$copyfolders[] = $path;
			}
		}

		if ( ! empty($files->filename))
		{
			foreach ($files->filename as $fik => $fiv)
			{
				$path['src']   = $source . '/' . $fiv;
				$path['dest']  = $destination . '/' . $fiv;
				$copyfiles[]   = $path;
			}
		}

		return $this->copyFiles($copyfiles, $copyfolders);
	}

	function copyFiles($files, $folders)
	{
		$app = Factory::getApplication();

		$i = 0;
		$fileIncluded = $folderIncluded = 0;

		if (is_array($folders) && count($folders) > 0)
		{
			foreach ($folders as $folder)
			{
				// Get the source and destination paths
				$foldersource = Path::clean($folder['src']);
				$folderdest   = Path::clean($folder['dest']);

				if ( ! Folder::exists($foldersource))
				{
					$app->enqueueMessage(Text::sprintf('COM_ICAGENDA_FOLDER_NOT_EXISTS', $foldersource), 'warning');

					return false;
				}
				else
				{
					if ( ! (Folder::copy($foldersource, $folderdest, '', true)))
					{
						$app->enqueueMessage(Text::sprintf('COM_ICAGENDA_ERROR_COPY_FOLDER_TO', $foldersource, $folderdest), 'warning');

						return false;
					}
					else
					{
						$i++;
					}
				}
			}

			$folderIncluded = 1;
		}

		if (is_array($files) && count($files) > 0)
		{
			foreach ($files as $file)
			{
				// Get the source and destination paths
				$filesource = Path::clean($file['src']);
				$filedest   = Path::clean($file['dest']);

				if ( ! file_exists($filesource))
				{
					$app->enqueueMessage(Text::sprintf('COM_ICAGENDA_FILE_NOT_EXISTS', $filesource), 'warning');

					return false;
				}
				else
				{
					if ( ! (File::copy($filesource, $filedest)))
					{
						$app->enqueueMessage(Text::sprintf('COM_ICAGENDA_ERROR_COPY_FILE_TO', $filesource, $filedest), 'warning');

						return false;
					}
					else
					{
						$i++;
					}
				}
			}

			$fileIncluded = 1;
		}

		if ($fileIncluded == 0 && $folderIncluded ==0)
		{
			$app->enqueueMessage(Text::_('COM_ICAGENDA_ERROR_INSTALL_FILE'), 'warning');

			return false;
		}

		return $i; // Possible TO DO, now it returns count folders and files togeter, //return count($files);
	}

	protected function getParamsThemes()
	{
		$element = $this->_manifest->children()->params;

		if ( ! is_a($element, 'JXMLElement') || ! count($element->children()))
		{
			return null; // Either the tag does not exist or has no children therefore we return zero files processed.
		}

		$params = $element->children();

		if (count($params) == 0)
		{
			return null; // No params to process
		}

		// Process each parameter in the $params array.
		$paramsArray = array();
		$i = 0;

		foreach ($params as $param)
		{
			if ( ! $name = $param['name'])
			{
				continue;
			}
			if ( ! $value = $param['default'])
			{
				continue;
			}

			$paramsArray[$i]['name']  = (string)$name;
			$paramsArray[$i]['value'] = (string)$value;
			$i++;
		}

		return $paramsArray;
	}

	function deleteTempFiles()
	{
		$path = $this->getPath('source');

		if (is_dir($path))
		{
			$val = Folder::delete($path);
		}
		elseif (is_file($path))
		{
			$val = File::delete($path);
		}

		$packageFile = $this->getPath('packagefile');

		if (is_file($packageFile))
		{
			$val = File::delete($packageFile);
		}

		$extractDir = $this->getPath('extractdir');

		if (is_dir($extractDir))
		{
			$val = Folder::delete($extractDir);
		}
	}


	/*
	 * Added @since 3.0.
	 */
	public static function unpack($p_filename)
	{
		// Path to the archive
		$archivename = $p_filename;

		// Temporary folder to extract the archive into
		$tmpdir = uniqid('install_');

		// Clean the paths to use for archive extraction
		$extractdir  = Path::clean(dirname($p_filename) . '/' . $tmpdir);
		$archivename = Path::clean($archivename);

		// Do the unpacking of the archive
		try
		{
			$archive = new Archive(array('tmp_path' => Factory::getApplication()->get('tmp_path')));
			$extract = $archive->extract($archivename, $extractdir);
		}
		catch (\Exception $e)
		{
			return false;
		}

		/*
		 * Let's set the extraction directory and package file in the result array so we can
		 * cleanup everything properly later on.
		 */
		$retval['extractdir']  = $extractdir;
		$retval['packagefile'] = $archivename;

		/*
		 * Try to find the correct install directory.  In case the package is inside a
		 * subdirectory detect this and set the install directory to the correct path.
		 *
		 * List all the items in the installation directory.  If there is only one, and
		 * it is a folder, then we will set that folder to be the installation folder.
		 */
		$dirList = array_merge(Folder::files($extractdir, ''), Folder::folders($extractdir, ''));

		if (count($dirList) == 1)
		{
			if (Folder::exists($extractdir . '/' . $dirList[0]))
			{
				$extractdir = Path::clean($extractdir . '/' . $dirList[0]);
			}
		}

		/*
		 * We have found the install directory so lets set it and then move on
		 * to detecting the extension type.
		 */
		$retval['dir'] = $extractdir;

		/*
		 * Get the extension type and return the directory/type array on success or
		 * false on fail.
		 */
		$retval['type'] = self::detectType($extractdir);

		if ($retval['type'])
		{
			return $retval;
		}
		else
		{
			return false;
		}
	}

	/*
	 * Added @since 3.0.
	 */
	public static function detectType($p_dir)
	{
		// Search the install dir for an XML file
		$files = Folder::files($p_dir, '\.xml$', 1, true);

		if ( ! count($files))
		{
			Log::add(Text::_('JLIB_INSTALLER_ERROR_NOTFINDXMLSETUPFILE'), Log::WARNING, 'jerror');

			return false;
		}

		foreach ($files as $file)
		{
			$xml = \simplexml_load_file($file);

			if ( ! $xml)
			{
				continue;
			}

			if ($xml->getName() != 'install' && $xml->getName() != 'installtheme')
			{
				unset($xml);

				continue;
			}

			$type = (string) $xml->attributes()->type;

			// Free up memory
			unset($xml);

			return $type;
		}

		Log::add(Text::_('JLIB_INSTALLER_ERROR_NOTFINDJOOMLAXMLSETUPFILE'), Log::WARNING, 'jerror');

		// Free up memory.
		unset($xml);

		return false;
	}
}
