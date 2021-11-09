<?php
// namespace administrator\components\com_jmap\framework\google;
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

class Google_Service_AnalyticsData_BatchRunPivotReportsRequest extends Google_Collection
{
  protected $collection_key = 'requests';
  protected $requestsType = 'Google_Service_AnalyticsData_RunPivotReportRequest';
  protected $requestsDataType = 'array';

  /**
   * @param Google_Service_AnalyticsData_RunPivotReportRequest[]
   */
  public function setRequests($requests)
  {
    $this->requests = $requests;
  }
  /**
   * @return Google_Service_AnalyticsData_RunPivotReportRequest[]
   */
  public function getRequests()
  {
    return $this->requests;
  }
}
