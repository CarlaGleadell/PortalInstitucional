<?php
/**
 *----------------------------------------------------------------------------
 * iCagenda     Events Management Extension for Joomla!
 *----------------------------------------------------------------------------
 * @version     3.9.0 2024-03-07
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

use iCutilities\Router\Router as icagendaRouterHelper;
use Joomla\CMS\Component\ComponentHelper as JComponentHelper;
use Joomla\CMS\Component\Router\RouterBase as JComponentRouterBase;
use Joomla\CMS\Factory as JFactory;

/**
 * Routing class from com_icagenda
 */
class iCagendaRouter extends JComponentRouterBase
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
	 * @param   JApplicationCms  $app   The application object
	 * @param   JMenu            $menu  The menu object to work with
	 */
	public function __construct($app = null, $menu = null)
	{
		$this->db = JFactory::getDbo();

		$params = JComponentHelper::getParams('com_icagenda');

		$this->noIDs = (bool) $params->get('sef_ids');

		parent::__construct($app, $menu);
	}

public function build( &$query )
{
		$segments = array();

		$id = '';

		if (isset($query['id']))
		{
			$id = $query['id'];

			unset($query['id']);
		}

		$view = '';

		if (isset($query['view']))
		{
			$view = $query['view'];

//			if (empty($query['Itemid']))
//			{
//				$segments[] = $view;
//			}

			unset($query['view']);
		}

		if ($view === 'event')
		{
			$segments[] = $this->getEventSegment($id, $query);

			if (isset($query['date']))
			{
				$segments[] = $query['date'];

				unset($query['date']);
			}
		}

		if ($view === 'manager')
		{
			$segments[] = icagendaRouterHelper::translateSegment($view);

			if (isset($query['layout']))
			{
				$segments[] = icagendaRouterHelper::translateSegment($query['layout']);

				unset($query['layout']);
			}

			$segments[] = $this->getEventSegment($query['event_id'], $query);

			unset($query['event_id']);

			if (isset($query['return']))
			{
				$segments[] = $query['return'];

				unset($query['return']);
			}
		}

		if ($view === 'registration')
		{
			$segments[] = $this->getEventSegment($id, $query);
			$segments[] = icagendaRouterHelper::translateSegment($view);

			if (isset($query['layout']))
			{
				$segments[] = icagendaRouterHelper::translateSegment($query['layout']);

				unset($query['layout']);
			}
		}

		if ($view === 'submit')
		{
			if (isset($query['layout']))
			{
				$segments[] = icagendaRouterHelper::translateSegment($query['layout']);

				unset($query['layout']);
			}
		}

		return $segments;
}

public function parse( &$segments )
{
		$vars = array();

		// Count route segments
		$count = \count($segments);

		if ($count == 4)
		{
			if ($segments[0] == icagendaRouterHelper::translateSegment('manager'))
			{
				if ($segments[1] == icagendaRouterHelper::translateSegment('event_edit'))
				{
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

		if ($count == 3)
		{
			if ($segments[1] == icagendaRouterHelper::translateSegment('registration'))
			{
				// Event Registration form Complete
				$vars['id']     = $this->fixSegmentID($segments[0]);
				$vars['view']   = 'registration';

				if ($segments[2] == icagendaRouterHelper::translateSegment('complete'))
				{
					$vars['layout'] = 'complete';
				}

				elseif ($segments[2] == icagendaRouterHelper::translateSegment('cancel'))
				{
					$vars['layout'] = 'cancel';
				}

				elseif ($segments[2] == icagendaRouterHelper::translateSegment('actions'))
				{
					$vars['layout'] = 'actions';
				}

				unset($segments[0]);
				unset($segments[1]);
				unset($segments[2]);
			}
		}

		elseif ($count == 2)
		{
			if ($segments[1] == icagendaRouterHelper::translateSegment('registration'))
			{
				// Event Registration form
				$vars['id']   = $this->fixSegmentID($segments[0]);
				$vars['view'] = 'registration';

				unset($segments[0]);
				unset($segments[1]);
			}
			else
			{
				// Event with single date
				$vars['id']   = $this->fixSegmentID($segments[0]);
				$vars['view'] = 'event';
				$vars['date'] = $segments[1];

				unset($segments[0]);
				unset($segments[1]);
			}
		}

		elseif ($count == 1)
		{
			if ($segments[0] == icagendaRouterHelper::translateSegment('send'))
			{
				// Submit form send
				$vars['view']   = 'submit';
				$vars['layout'] = 'send';
			}
			else
			{
				// Event with period
				$vars['id']   = $this->fixSegmentID($segments[0]);
				$vars['view'] = 'event';
			}

			unset($segments[0]);
		}

		elseif ($count == 0)
		{
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

		if (!strpos($id, ':'))
		{
			// Add alias if missing.
			$id      = (int) $id;
			$dbquery = $this->db->getQuery(true);
			$dbquery->select($this->db->quoteName('alias'))
				->from($this->db->quoteName('#__icagenda_events'))
				->where($this->db->quoteName('id') . ' = ' . $id);
			$this->db->setQuery($dbquery);

			$id .= ':' . $this->db->loadResult();
		}

		$ex       = explode(':', $id);
		$id       = str_replace(':', '-', $id);
		$position = strpos($id, '-');

		if ($position && $this->noIDs)
		{
			// Control for duplicate aliases.
			// @todo: Create a function to fix duplicate aliases.
//			if ($ex[0] == $this->getEventId($ex[1]))
//			{
				// Remove id from segment.
				$segment = $ex[1];
//			}
//			else
//			{
				// Keep id in segment of duplicate alias.
//				$segment = $id;
//			}
		}
		else
		{
			$ex_control = explode('-', $ex[1]);

			if ($ex[0] == $ex_control[0])
			{
				// Remove id from fixed alias.
				$segment = $ex[1];
			}
			else
			{
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

		if ($position)
		{
			$segment = $segment;
		}
		else
		{
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
			->where($this->db->quoteName('alias') . ' = ' . $this->db->quote($alias));

		$id = $this->db->setQuery($query)->loadResult();

		return $id;
	}
}

/**
 * Content router functions
 *
 * These functions are proxys for the new router interface
 * for old SEF extensions.
 *
 * @param   array  &$query  An array of URL arguments
 *
 * @return  array  The URL arguments to use to assemble the subsequent URL.
 *
 * @deprecated  4.0  Use Class based routers instead
 */
function iCagendaBuildRoute(&$query)
{
	$router = new iCagendaRouter;

	return $router->build($query);
}

/**
 * Parse the segments of a URL.
 *
 * This function is a proxy for the new router interface
 * for old SEF extensions.
 *
 * @param   array  $segments  The segments of the URL to parse.
 *
 * @return  array  The URL attributes to be used by the application.
 *
 * @since   3.3
 * @deprecated  4.0  Use Class based routers instead
 */
function iCagendaParseRoute($segments)
{
	$router = new iCagendaRouter;

	return $router->parse($segments);
}
