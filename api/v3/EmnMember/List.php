<?php
use CRM_EmnMemberlist_ExtensionUtil as E;

/**
 * EmnMember.List API specification (optional)
 * This is used for documentation and validation.
 *
 * @param array $spec description of fields supported by this API call
 * @return void
 * @see http://wiki.civicrm.org/confluence/display/CRMDOC/API+Architecture+Standards
 */
function _civicrm_api3_emn_member_List_spec(&$spec) {
}

/**
 * EmnMember.List API
 *
 * @param array $params
 * @return array API result descriptor
 * @see civicrm_api3_create_success
 * @see civicrm_api3_create_error
 * @throws API_Exception
 */
function civicrm_api3_emn_member_List($params) {
  try{

    $emnMember = new CRM_EmnMemberlist_EmnMember();
    $result = $emnMember->memberlist();
    return civicrm_api3_create_success($result, $params, 'EmnMember', 'List');
  } catch(Exception $ex) {

    throw new API_Exception($ex);
  }
}
