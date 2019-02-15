<?php
/**
 *  @author Klaas Eikelboom (CiviCooP) <klaas.eikelboom@civicoop.org>
 *  @date 2/15/19 6:28 PM
 *  @license AGPL-3.0
 */
class CRM_EmnMemberlist_EmnMember {

  /**
   * CRM_EmnMemberlist_EmnMember constructor.
   */
  public function __construct() {
    $this->cf = $this->customFields(['Type_of_Organisation','Description','EMN_member_since']);
  }

  private function customFields($fields){
    $result = [];
    foreach($fields as $field){
      $cf = civicrm_api3('CustomField', 'getsingle', [
        'name' => $field,
      ]);
      $cf['table_name'] = civicrm_api3('CustomGroup', 'getvalue', [
        'return' => "table_name",
        'id' => $cf['custom_group_id'],
      ]);
      $result[$field]=$cf;
    }
    return $result;
  }

  public function memberlist(){
    $result=[];
    $cf = $this->customFields(['Type_of_Organisation','Description','EMN_member_since']);;

    $sql = <<<"SQL"
select c.id contact_id
,      c.organization_name
,      ph.phone
,      em.email
,      w.url  website
,      ad.street_address
,      ad.city
,      ad.postal_code
,      cnt.name country
,      {$cf['Description']['column_name']}  description
,      {$cf['EMN_member_since']['column_name']} member_since 
,      ov.label   type_of_organization  
from   civicrm_contact c
join   civicrm_membership cm on c.id = cm.contact_id
left join   civicrm_phone ph on (c.id = ph.contact_id and ph.is_primary=1)
left join   civicrm_email em on (c.id = em.contact_id and em.is_primary=1)
left join   civicrm_website w on (c.id = w.contact_id and website_type_id=2)
left join   civicrm_address ad on (c.id= ad.contact_id and ad.is_primary=1)
left join   civicrm_country cnt on (ad.country_id = cnt.id)
left join   {$cf['Description']['table_name']} dsc on (c.id = dsc.entity_id) 
left join   {$cf['EMN_member_since']['table_name']} ms on (c.id = ms.entity_id) 
left join   {$cf['Type_of_Organisation']['table_name']} ot on (c.id = ot.entity_id)  
left join   civicrm_option_value ov on (ov.value = ot.type_of_organisation_1 and ov.option_group_id={$cf['Type_of_Organisation']['option_group_id']})  
where  contact_type='Organization'
SQL;


    $dao = CRM_CORE_DAO::executeQuery($sql);
    while($dao->fetch())
    {
      $result[] = [
        'contact_id' => $dao->contact_id,
        'organization_name' => $dao->organization_name,
        'phone' => $dao->phone,
        'email' => $dao->email,
        'website' => $dao->website,
        'city' => $dao->city,
        'postal_code' => $dao->postal_code,
        'country'     => $dao->country,
        'description' => $dao->description,
        'member_since' => substr($dao->member_since,0,4),
        'type_of_organization' => $dao->type_of_organization,
      ];
    }
    return $result;
  }

}