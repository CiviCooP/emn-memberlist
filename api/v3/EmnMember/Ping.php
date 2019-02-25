<?php
use CRM_EmnMemberlist_ExtensionUtil as E;
/**
 * EmnMember.Ping API returns a simple pong, usefull to test if all
 * the connection parameters are correct
 *
 * @param array $params
 * @return array API result descriptor
 * @see civicrm_api3_create_success
 * @see civicrm_api3_create_error
 * @throws API_Exception
 */
function civicrm_api3_emn_member_Ping($params) {
  $returnValues[] = 'Pong';
  return civicrm_api3_create_success($returnValues, $params, 'EmnMeber', 'Ping');
}
