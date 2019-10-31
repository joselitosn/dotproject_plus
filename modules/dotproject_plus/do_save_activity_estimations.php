<?php
if (!defined('DP_BASE_DIR')) {
    die('You should not access this file directly.');
}
require_once (DP_BASE_DIR . "/modules/timeplanning/model/project_minute.class.php");
require_once (DP_BASE_DIR . "/modules/timeplanning/model/project_task_estimation.class.php");
require_once (DP_BASE_DIR . "/modules/timeplanning/model/wbs_item_estimation.class.php");
require_once (DP_BASE_DIR . "/modules/tasks/tasks.class.php");
require_once (DP_BASE_DIR . "/modules/human_resources/configuration_functions.php");
require_once (DP_BASE_DIR . "/modules/human_resources/human_resources.class.php");
require_once (DP_BASE_DIR . "/modules/timeplanning/control/controller_wbs_item_activity_relationship.php");
set_time_limit(300);

$project_id = $_POST['project_id'];
$work_package = $_POST['item_id'];
$taskId = $_POST['task_id'];
$description=$_POST["activity_description"];
$owner_id=$_POST["task_owner"];
$effort = $_POST["planned_effort"];
$effortUnit = $_POST["planned_effort_unit"];
$startDate = $_POST["planned_start_date_activity"];
$endDate = $_POST["planned_end_date_activity"];
$task_percent_complete=$_POST["task_percent_complete"];


$rolesHr = $_POST['roles_human_resources'];

$rolesHr = json_decode($rolesHr, true);
$rolesIds = array();

$taskWbsRelation = new ControllerWBSItemActivityRelationship();
$activity_order=sizeof($taskWbsRelation->getActivitiesByWorkPackage($work_package))+1;

$duration;
if ($taskId) {
    // UPDATE
    $obj = new CTask();
    $obj->task_id = $taskId;
    $obj->load();

    $dateStart = null;
    $dateEnd = null;
    $duration = 0;
    $obj->task_percent_complete=$task_percent_complete;
    $calculateDuratation = true;
    if ($startDate != "") {
        $dateStart = new DateTime();
        $dateParts = explode("/", $startDate);
        $dateStart->setDate($dateParts[2], $dateParts[1], $dateParts[0]);
        $dateStart->setTime(0, 0, 0);
        $obj->task_start_date = $dateStart->format("Y-m-d H:i:s");
        $d1 = mktime(0, 0, 0, (int) $dateParts[1], (int) $dateParts[0], (int) $dateParts[2]);
    } else {
        $obj->task_start_date = null;
        $calculateDuratation = false;
    }
    if ($endDate != "") {
        $dateEnd = new DateTime();
        $dateParts = explode("/", $endDate);
        $dateEnd->setDate($dateParts[2], $dateParts[1], $dateParts[0]);
        $dateEnd->setTime(0, 0, 0);
        $obj->task_end_date = $dateEnd->format("Y-m-d H:i:s");
        $d2 = mktime(0, 0, 0, (int) $dateParts[1], (int) $dateParts[0], (int) $dateParts[2]);
    } else {
        $obj->task_end_date = null;
        $calculateDuratation = false;
    }

    if ($calculateDuratation) {
        $duration = floor(($d2 - $d1) / 86400);
        $duration++;
        $obj->task_duration = $duration;
        $obj->task_duration_type = "24";
    }

    $obj->task_name = $description;
    $obj->task_owner = $owner_id;
    $obj->task_order = $activity_order;
    $obj->task_percent_complete=$task_percent_complete;
    $obj->task_project = $project_id;
    $obj->store();

    // Remove old relation
    $taskWbsRelation->delete($taskId);
    // Insert new one
    $taskWbsRelation->insert($taskId, $work_package, $activity_order);
} else {
    // INSERT
    $obj = new CTask();
    $dateStart = null;
    $dateEnd = null;
    $duration = 0;
    $obj->task_percent_complete=$task_percent_complete;
    $calculateDuratation = true;
    if ($startDate != "") {
        $dateStart = new DateTime();
        $dateParts = explode("/", $startDate);
        $dateStart->setDate($dateParts[2], $dateParts[1], $dateParts[0]);
        $dateStart->setTime(0, 0, 0);
        $obj->task_start_date = $dateStart->format("Y-m-d H:i:s");
        $d1 = mktime(0, 0, 0, (int) $dateParts[1], (int) $dateParts[0], (int) $dateParts[2]);
    } else {
        $obj->task_start_date = null;
        $calculateDuratation = false;
    }
    if ($endDate != "") {
        $dateEnd = new DateTime();
        $dateParts = explode("/", $endDate);
        $dateEnd->setDate($dateParts[2], $dateParts[1], $dateParts[0]);
        $dateEnd->setTime(0, 0, 0);
        $obj->task_end_date = $dateEnd->format("Y-m-d H:i:s");
        $d2 = mktime(0, 0, 0, (int) $dateParts[1], (int) $dateParts[0], (int) $dateParts[2]);
    } else {
        $obj->task_end_date = null;
        $calculateDuratation = false;
    }

    if ($calculateDuratation) {
        $duration = floor(($d2 - $d1) / 86400);
        $duration++;
        $obj->task_duration = $duration;
        $obj->task_duration_type = "24";
    }

    $obj->task_name = $description;
    $obj->task_owner = $owner_id;
    $obj->task_order = $activity_order;
    $obj->task_percent_complete=$task_percent_complete;
    $obj->task_project = $project_id;
    $obj->store();

    $taskId = $obj->task_id;

    // Insert relation to wbs item
    $taskWbsRelation->insert($taskId, $work_package, $activity_order);
}

// Save estimations
// TODO validar se os campos foram preenchidos para salvar
foreach ($rolesHr as $line) {
    $rolesIds[] = $line['role'];
}

$projectTaskEstimation = new ProjectTaskEstimation();
$projectTaskEstimation->store($taskId, $duration, $effort, $effortUnit, $rolesIds);

//Save HR allocation
// Delete old records
$allocationRoleHR= new CHumanResourceAllocation();
$allocationRoleHR->delete($taskId);

// Insert the new ones
foreach ($rolesHr as $line) {
    if (null !== $line['hr']) {
        $q = new DBQuery();
        $q->addTable("project_tasks_estimated_roles");
        $q->addQuery("id");
        $q->addWhere("role_id=".$line['role']." and task_id=".$taskId);
        $sql = $q->prepare();
        $q->clear();
        $records = db_loadList($sql);
//        $allocationRoleHR->human_resource_id = $line['hr'];
//        $allocationRoleHR->project_tasks_estimated_roles_id=$records[0][0];
        $userId=getUserIdByHR($line['hr']);
        $allocationRoleHR->store($taskId, $userId, $line['hr'], $records[0][0]);
    }

}

$AppUI->setMsg($AppUI->_("LBL_THE_ACTIVITY"). " ($description) ". $AppUI->_("LBL_WAS_SAVED"), UI_MSG_OK, true);
echo $AppUI->getMsg();
exit();
?>