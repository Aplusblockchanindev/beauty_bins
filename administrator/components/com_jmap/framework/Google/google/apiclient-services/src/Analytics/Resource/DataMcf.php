<?php

namespace Google\Service\Analytics\Resource;

/**
 *
 * @package JMAP::FRAMEWORK::administrator::components::com_jmap
 * @subpackage framework
 * @subpackage google
 * @author Joomla! Extensions Store
 * @copyright (C) 2021 - Joomla! Extensions Store
 * @license GNU/GPLv2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined ( '_JEXEC' ) or die ();

use Google\Service\Analytics\McfData;

/**
 * The "mcf" collection of methods.
 * Typical usage is:
 * <code>
 * $analyticsService = new Google\Service\Analytics(...);
 * $mcf = $analyticsService->mcf;
 * </code>
 */
class DataMcf extends \Google\Service\Resource {
	/**
	 * Returns Analytics Multi-Channel Funnels data for a view (profile).
	 * (mcf.get)
	 *
	 * @param string $ids
	 *        	Unique table ID for retrieving Analytics data. Table ID is
	 *        	of the form ga:XXXX, where XXXX is the Analytics view (profile) ID.
	 * @param string $startDate
	 *        	Start date for fetching Analytics data. Requests can
	 *        	specify a start date formatted as YYYY-MM-DD, or as a relative date (e.g.,
	 *        	today, yesterday, or 7daysAgo). The default value is 7daysAgo.
	 * @param string $endDate
	 *        	End date for fetching Analytics data. Requests can
	 *        	specify a start date formatted as YYYY-MM-DD, or as a relative date (e.g.,
	 *        	today, yesterday, or 7daysAgo). The default value is 7daysAgo.
	 * @param string $metrics
	 *        	A comma-separated list of Multi-Channel Funnels
	 *        	metrics. E.g., 'mcf:totalConversions,mcf:totalConversionValue'. At least one
	 *        	metric must be specified.
	 * @param array $optParams
	 *        	Optional parameters.
	 *        	
	 * @opt_param string dimensions A comma-separated list of Multi-Channel Funnels
	 * dimensions. E.g., 'mcf:source,mcf:medium'.
	 * @opt_param string filters A comma-separated list of dimension or metric
	 * filters to be applied to the Analytics data.
	 * @opt_param int max-results The maximum number of entries to include in this
	 * feed.
	 * @opt_param string samplingLevel The desired sampling level.
	 * @opt_param string sort A comma-separated list of dimensions or metrics that
	 * determine the sort order for the Analytics data.
	 * @opt_param int start-index An index of the first entity to retrieve. Use this
	 * parameter as a pagination mechanism along with the max-results parameter.
	 * @return McfData
	 */
	public function get($ids, $startDate, $endDate, $metrics, $optParams = [ ]) {
		$params = [ 
				'ids' => $ids,
				'start-date' => $startDate,
				'end-date' => $endDate,
				'metrics' => $metrics
		];
		$params = array_merge ( $params, $optParams );
		return $this->call ( 'get', [ 
				$params
		], McfData::class );
	}
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias ( DataMcf::class, 'Google_Service_Analytics_Resource_DataMcf' );