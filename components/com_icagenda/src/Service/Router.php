<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.9.0 2024-03-07
 *
 * @package     iCagenda.Site
 * @subpackage  src.Service
 * @link        https://www.icagenda.com
 *
 * @author      Cyril Rezé
 * @copyright   (c) 2012-2024 Cyril Rezé / iCagenda. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 *
 * @since       3.8
 *----------------------------------------------------------------------------
*/

namespace WebiC\Component\iCagenda\Site\Service;

\defined('_JEXEC') or die;

use iCutilities\Router\Router as icagendaRouter;
use Joomla\CMS\Application\SiteApplication;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Component\Router\RouterBase;
use Joomla\CMS\Factory;
use Joomla\CMS\Menu\AbstractMenu;
//use Joomla\CMS\Uri\Uri;
use Joomla\Database\ParameterType;

/**
 * Routing class from com_icagenda
 */
class Router extends RouterBase
{
	/**
	 * The db
	 *
	 * @var  DatabaseInterface
	 */
	private $db;

	/**
	 * Flag to remove IDs
	 *
	 * @var  boolean
	 */
	protected $noIDs = false;

	/**
	 * iCagenda Component router constructor
	 *
	 * @param   SiteApplication  $app   The application object
	 * @param   AbstractMenu     $menu  The menu object to work with
	 */
	public function __construct(SiteApplication $app, AbstractMenu $menu)
	{
		$this->db = Factory::getDbo();

		$params = ComponentHelper::getParams('com_icagenda');

		$this->noIDs = (bool) $params->get('sef_ids');

		parent::__construct($app, $menu);
	}

	/**
	 * Build the route for the com_icagenda component
	 *
	 * @param   array  $query  An array of URL arguments
	 *
	 * @return  array  The URL arguments to use to assemble the subsequent URL.
	 */
	public function build(&$query)
	{
		$segments = array();

		$id = '';

		if (isset($query['id'])) {
			$id = $query['id'];

			unset($query['id']);
		}

		$view = '';

		if (isset($query['view'])) {
			$view = $query['view'];

//			if (empty($query['Itemid']))
//			{
//				$segments[] = $view;
//			}

			unset($query['view']);
		}

		if ($view === 'event') {
			$segments[] = $this->getEventSegment($id, $query);

			if (isset($query['date'])) {
				$segments[] = $query['date'];

				unset($query['date']);
			}
		}

		if ($view === 'manager') {
			$segments[] = icagendaRouter::translateSegment($view);

			if (isset($query['layout'])) {
				$segments[] = icagendaRouter::translateSegment($query['layout']);

				unset($query['layout']);
			}

			$segments[] = $this->getEventSegment($query['event_id'], $query);

			unset($query['event_id']);

			if (isset($query['return'])) {
				$segments[] = $query['return'];

				unset($query['return']);
			}
		}

		if ($view === 'registration') {
			$segments[] = $this->getEventSegment($id, $query);
			$segments[] = icagendaRouter::translateSegment($view);

			if (isset($query['layout'])) {
				$segments[] = icagendaRouter::translateSegment($query['layout']);

				unset($query['layout']);
			}
		}

		if ($view === 'submit') {
			if (isset($query['layout']))
			{
				$segments[] = icagendaRouter::translateSegment($query['layout']);

				unset($query['layout']);
			}
		}

		return $segments;
	}

	/**
	 * Parse the segments of a URL.
	 *
	 * @param   array  $segments  The segments of the URL to parse.
	 *
	 * @return  array  The URL attributes to be used by the application.
	 */
	public function parse(&$segments)
	{
		$vars = array();

		// Count route segments
		$count = \count($segments);

		if ($count == 4) {
			if ($segments[0] == icagendaRouter::translateSegment('manager')) {
				if ($segments[1] == icagendaRouter::translateSegment('event_edit')) {
					// Manager Event Edit form
					$vars['view'] = 'manager';
					$vars['layout'] = 'event_edit';
					$vars['event_id'] = $this->fixSegmentID($segments[2]);
					$vars['return'] = $segments[3];

					unset($segments[0]);
					unset($segments[1]);
					unset($segments[2]);
					unset($segments[3]);
				}
			}
		}

		if ($count == 3) {
			if ($segments[1] == icagendaRouter::translateSegment('registration')) {
				// Event Registration form Complete
				$vars['id']     = $this->fixSegmentID($segments[0]);
				$vars['view']   = 'registration';

				if ($segments[2] == icagendaRouter::translateSegment('complete')) {
					$vars['layout'] = 'complete';
				} elseif ($segments[2] == icagendaRouter::translateSegment('cancel')) {
					$vars['layout'] = 'cancel';
				} elseif ($segments[2] == icagendaRouter::translateSegment('actions')) {
					$vars['layout'] = 'actions';
				}

				unset($segments[0]);
				unset($segments[1]);
				unset($segments[2]);
			}
		} elseif ($count == 2) {
			if ($segments[1] == icagendaRouter::translateSegment('registration')) {
				// Event Registration form
				$vars['id']   = $this->fixSegmentID($segments[0]);
				$vars['view'] = 'registration';

				unset($segments[0]);
				unset($segments[1]);
			} else {
				// Event with single date
				$vars['id']   = $this->fixSegmentID($segments[0]);
				$vars['view'] = 'event';
				$vars['date'] = $segments[1];

				unset($segments[0]);
				unset($segments[1]);
			}
		} elseif ($count == 1) {
			if ($segments[0] == icagendaRouter::translateSegment('send')) {
				// Submit form send
				$vars['view']   = 'submit';
				$vars['layout'] = 'send';
			} else {
				// Event with period
				$vars['id']   = $this->fixSegmentID($segments[0]);
				$vars['view'] = 'event';
			}

			unset($segments[0]);
		} elseif ($count == 0) {
			$vars['view'] = 'events';
		}

		return $vars;
	}

	/**
	 * Method to get the segment(s) for an article
	 *
	 * @param   string  $id     ID of the article to retrieve the segments for
	 * @param   array   $query  The request that is built right now
	 *
	 * @return  string  The segments of this item
	 */
	protected function getEventSegment($id, $query)
	{
		$id = ltrim($id, ':'); // Falang Fix

		if (!strpos($id, ':')) {
			// Add alias if missing.
			$id      = (int) $id;
			$dbquery = $this->db->getQuery(true);
			$dbquery->select($this->db->quoteName('alias'))
				->from($this->db->quoteName('#__icagenda_events'))
				->where($this->db->quoteName('id') . ' = :id')
				->bind(':id', $id, ParameterType::INTEGER);
			$this->db->setQuery($dbquery);

			$id .= ':' . $this->db->loadResult();
		}

		$ex       = explode(':', $id);
		$id       = str_replace(':', '-', $id);
		$position = strpos($id, '-');

		if ($position && $this->noIDs) {
			// Remove id from segment.
			$segment = $ex[1];
		} else {
			$ex_control = explode('-', $ex[1]);

			if ($ex[0] == $ex_control[0]) {
				// Remove id from fixed alias.
				$segment = $ex[1];
			} else {
				$segment = $id;
			}
		}

		return $segment;
	}

	/**
	 * Try to add missing id to segment
	 *
	 * @param   string  $segment  One piece of segment of the URL to parse
	 *
	 * @return  string  The segment with founded id
	 */
	protected function fixSegmentID($segment)
	{
		$position = strpos($segment, ':');

		if ($position) {
			$segment = $segment;
		} else {
			$segment = $this->getEventId($segment) . ':' . $segment;
		}

		return $segment;
	}


	/**
	 * Try to add missing id to segment
	 *
	 * @param   string  $segment  One piece of segment of the URL to parse
	 *
	 * @return  string  The segment with founded id
	 */
	protected function getEventId($segment)
	{
		// Try to find tag id
		$alias = str_replace(':', '-', $segment);

		$query = $this->db->getQuery(true)
			->select($this->db->quoteName('id'))
			->from($this->db->quoteName('#__icagenda_events'))
			->where($this->db->quoteName('alias') . ' = :alias')
			->bind(':alias', $alias);

		$id = $this->db->setQuery($query)->loadResult();

		return $id;
	}
}
