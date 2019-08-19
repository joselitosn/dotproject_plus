<?php
if (!defined("DP_BASE_DIR")) {
    die("You should not access this file directly.");
}
$projectId = dPgetParam($_POST, "project_id", 0);
$risks=$_POST["risks"];
$obj = new CRisks();
foreach($risks as $risk){
    $obj->load($risk);
    $newRisk=$obj->duplicate();
    $newRisk->risk_project=$projectId;
    $newRisk->store();
}
$AppUI->setMsg($AppUI->_("LBL_CHECKLIST_ANALYSIS_SUCCESS",UI_OUTPUT_JS), UI_MSG_OK, false);
echo $AppUI->getMsg();
exit();
?>