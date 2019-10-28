<?php

if (!defined("DP_BASE_DIR")) {
    die("You should not access this file directly.");
}

require_once (DP_BASE_DIR . "/modules/companies/companies.class.php");
require_once (DP_BASE_DIR . "/modules/contacts/contacts.class.php"); //for contact creation
require_once (DP_BASE_DIR . "/modules/admin/admin.class.php"); //for user creation

$userFirstName = dPgetParam($_POST, "first_name", 0);
$userLastName = dPgetParam($_POST, "last_name", 0);
$companyId = dPgetParam($_POST, "company_id", 0);
$userName =  $userFirstName . " " . $userLastName;

$redirectPath="m=companies&a=view&company_id=" . $companyId;
//pull a list of existing usernames
$q = new DBQuery;
$q->addTable('users', 'u');
$q->addQuery('user_username');
$q->addWhere("user_username like '{$userName}'");
$q->addWhere("user_company = {$companyId}");
$userEx = $q->loadResult();
// If userName already exists quit with error and do nothing

$resposta = array('err' => false, 'msg' => '');
if ($userEx) {
    $resposta['err'] = true;
    $resposta['msg'] = 'Usuário já existe. Tente outro nome de usuário.';
    echo json_encode($resposta);
    exit();
} else {
    $companyObj = new CCompany();
    $companyObj->load($companyId);

    //Verify if already exist a contact with this name.
    $q = new DBQuery();
    $q->addTable('contacts', 'c');
    $q->addQuery('contact_id');
    $q->addWhere("contact_first_name= '{$userFirstName}' and contact_last_name='{$userLastName}' and contact_company = {$companyId}");
    $sql = $q->prepare();
    $contactId = -1;
    $contacts = db_loadList($sql);
    foreach ($contacts as $contact) {
        $contactId = $contact[0];
    }
    $contactObj = new CContact();
    if($contactId==-1){
    $contactObj->contact_company = $companyId;
    $contactObj->contact_last_name = $userLastName;
    $contactObj->contact_first_name = $userFirstName;
    $contactObj->contact_id=0;
    $contactObj->store();
    }else{
        $contactObj->load($contactId);
        $contactObj->contact_company = $companyId;
        $contactObj->store();
    }
    
    $userObj = new CUser();
    $userObj->user_username = $userName;
    $userObj->user_company = $companyId;
    $userObj->user_contact = $contactObj->contact_id;
    $userObj->user_id=0;
    $userObj->store();


    $msg = $AppUI->_("Human Resource") . " " . $AppUI->_("added");


    $resposta['err'] = false;
    $resposta['msg'] = $msg;
    echo json_encode($resposta);
    exit();
}
?>
