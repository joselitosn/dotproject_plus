<?php
if (!defined("DP_BASE_DIR")) {
    die("You should not access this file directly.");
}

$del = dPgetParam($_POST, "del", 0);
$obj = new CHumanResource();
$msg = "";
$roles_ids = dPgetParam($_POST, "roles");
$companyId = dPgetParam($_POST, "company_id", 0);

require_once (DP_BASE_DIR . "/modules/monitoringandcontrol/control/controller_resources_costs.class.php");
$resCost = new ControllerResourcesCosts();

if (!$obj->bind($_POST)) {
    $AppUI->setMsg($obj->getError(), UI_MSG_ERROR);
    echo $AppUI->getMsg();
    exit();
}

if ($del) {
    require_once (DP_BASE_DIR . "/modules/contacts/contacts.class.php"); //for contact
    require_once (DP_BASE_DIR . "/modules/admin/admin.class.php"); //for user
    $userId = $obj->human_resource_user_id;

    if ($obj->human_resource_id > 0 && !$obj->canDelete($msg)) {
        echo $msg;
        exit();
    }

    // delete hr costs
    $resCost->deleteAllByUserId($userId);

    // delete contact
    $contactObj = new CContact();
    $contactObj->load(dPgetParam($_POST, "contact_id", 0));
    $contactObj->delete();

    //delete roles
    if ($obj->human_resource_id > 0) {
        $human_resource_roles = new CHumanResourceRoles();
        $human_resource_roles->deleteAll($obj->human_resource_id);
    }

    //delete human resource
    $obj->delete();

    // delete user
    $userObj = new CUser();
    $userObj->load($userId);
    $userObj->delete();

    $msg = $AppUI->_("Human Resource") . " " . $AppUI->_("deleted");
    $AppUI->setMsg($msg, UI_MSG_OK, false);
    echo $AppUI->getMsg();
    exit();
} else {
    $userId = $obj->human_resource_user_id;
    // Inserts costs
    $costs = dPgetParam($_POST,'costs');
    if ($costs) {
        $costs = json_decode($costs);
    }
    $resCost->deleteAllByUserId($userId);
    if (count($costs)) {
        foreach ($costs as $cost) {
            $cost = json_decode(json_encode($cost), true);
            $tx_pad = $cost['cost'];
            $dt_begin = $cost['startDate'];
            $dt_end = $cost['endDate'];
            $resCost->insertCosts($tx_pad, null, $dt_begin, $dt_end, $userId);
        }
    }

    if (($msg = $obj->store())) {
        $AppUI->setMsg($msg, UI_MSG_ERROR);
    } else {
        $human_resource_roles = new CHumanResourceRoles();
        $human_resource_roles->deleteAll($obj->human_resource_id);
        if(count($roles_ids)) {
            foreach ($roles_ids as $role_id) {
                $human_resource_roles->store($role_id, $obj->human_resource_id);
            }
        }
        $msg = $AppUI->_("Human Resource") . " " . $AppUI->_($_POST["human_resource_id"] ? "updated" : "added");
        $AppUI->setMsg($msg, UI_MSG_OK, true);
    }
    echo $AppUI->getMsg();
    exit();
}
?>
