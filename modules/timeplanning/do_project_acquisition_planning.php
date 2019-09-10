<?php
require_once (DP_BASE_DIR . "/modules/timeplanning/control/acquisition/controller_acquisition_planning.class.php");
$projectId = dPgetParam($_POST, "project_id");
$id= dPgetParam($_POST,"acquisition_planning_id");
$itemsToBeAcquired = dPgetParam($_POST,"items_to_be_acquired");
$contractType= dPgetParam($_POST,"contract_type");
$documentsToAcquisition = dPgetParam($_POST,"documents_to_acquisition");
$supplierManagementProcess = dPgetParam($_POST,"supplier_management_process");

$requirements = dPgetParam($_POST,"requirement");
$criteriaList = dPgetParam($_POST,"criteria");
$criteriaWeight = dPgetParam($_POST,"criteria_weight");
$roles= dPgetParam($_POST,"role");
$responsabilities= dPgetParam($_POST,"responsability");

$criteriaForSelection = '';
if (count($criteriaList) > 0) {
    foreach ($criteriaList as $key => $criteria) {
        $weight = $criteriaWeight[$key];
        $criteriaForSelection .= $criteria . " (Peso: $weight)<br/>";
    }
}
$additionalRequirements = '';
if (count($requirements) > 0) {
    foreach ($requirements as $key => $requirement) {
        $additionalRequirements .= $requirement . "<br/>";
    }
}
$acquisitionRoles = '';
if (count($roles) > 0) {
    foreach ($roles as $key => $role) {
        $resp = $responsabilities[$key];
        $acquisitionRoles .= $role . ": $resp<br/>";
    }
}

$controller  = new ControllerAcquisitionPlanning(); 
$acquisitionId=$controller->sendDataToBeStored($id, $projectId, $acquisitionRoles, $supplierManagementProcess, $itemsToBeAcquired, $documentsToAcquisition, $criteriaForSelection, $contractType, $additionalRequirements);

$controller->deleteCriteria($acquisitionId);
if (count($criteriaList) > 0) {
    $controller->storeCriteria($criteriaList, $criteriaWeight, $acquisitionId);
}

$controller->deleteRequirements($acquisitionId);
if (count($requirements) > 0) {
    $controller->storeRequirements($requirements, $acquisitionId);
}

$controller->deleteRoles($acquisitionId);
if (count($roles) > 0) {
    $controller->storeRoles($roles, $responsabilities, $acquisitionId);
}
$AppUI->setMsg($AppUI->_("LBL_ACQUISITION_ITEM_REGISTERED", UI_OUTPUT_HTML), UI_MSG_OK);

echo $AppUI->getMsg();
exit();
?>
