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
 * @since       3.5
 *----------------------------------------------------------------------------
*/

namespace WebiC\Component\iCagenda\Administrator\View\Registrations;

\defined('_JEXEC') or die;

use Joomla\CMS\Application\CMSApplication;
use Joomla\CMS\Factory;
use Joomla\CMS\MVC\View\GenericDataException;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use WebiC\Component\iCagenda\Administrator\Model\RegistrationsModel;

/**
 * View class for a list of registrations.
 */
class RawView extends BaseHtmlView
{
	/**
	 * Display the view
	 *
	 * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
	 *
	 * @return  void
	 */
	public function display($tpl = null)
	{
		/** @var TracksModel $model */
		$model    = $this->getModel();
		$basename = $model->getBaseName();
		$fileType = $model->getFileType();
		$mimeType = $model->getMimeType();
		$content  = $model->getContent();

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			throw new GenericDataException(implode("\n", $errors), 500);
		}

		$this->document->setMimeEncoding($mimeType);

		$app = Factory::getApplication();
		$app->setHeader(
			'Content-disposition',
			'attachment; filename="' . $basename . '.' . $fileType . '"; creation-date="' . Factory::getDate()->toRFC822() . '"',
			true
		);

		echo $content;
	}
}
