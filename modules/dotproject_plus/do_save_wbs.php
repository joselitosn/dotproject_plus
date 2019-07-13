<?php
if (!defined('DP_BASE_DIR')) {
    die('You should not access this file directly.');
}

const WBS_FIRST_ITEM = 1;
const WBS_FIRST_ITEM_ORDER = 1;

require_once (DP_BASE_DIR . "/modules/timeplanning/control/controller_wbs_items.class.php");
require_once (DP_BASE_DIR . "/modules/timeplanning/model/project_task_estimation.class.php");
require_once (DP_BASE_DIR . "/modules/timeplanning/model/wbs_item_estimation.class.php");
require_once (DP_BASE_DIR . '/modules/tasks/tasks.class.php');
set_time_limit(300);

$controllerWBSItem= new ControllerWBSItem();

$id = dPgetParam($_POST, 'item_id', -1);

// Control variable. If $id is equal to -1 then it is an insert
$update = $id != -1;

$projectId = dPgetParam($_POST, 'project_id');
$description = dPgetParam($_POST, 'wbs_item_description');
$estimatedSize=$_POST["wbs_item_size"];
$estimatedSizeUnit=$_POST["wbs_item_size_unit"];
$parentNumber = $_POST['parent_number'];
$itemNumber = $_POST['number'];
$sortOrder = $_POST['parent'];
$isLeaf= dPgetParam($_POST, 'is_leaf');
if ($isLeaf == '') {
    $isLeaf = 1;
}

$firstItem = false;


if (!$update) {
    if ($parentNumber == '') {
        // It is the first WBS item
        $number = WBS_FIRST_ITEM;
        $sortOrder = WBS_FIRST_ITEM_ORDER;
        $firstItem = true;
    } else {
        $parentId = $_POST['parent_id'];
        $lastChildNumber = $controllerWBSItem->getLastChildByParentNumber($projectId, $parentNumber);

        if (!$lastChildNumber) {
            // It is the first child
            $number  = $parentNumber . '.' . WBS_FIRST_ITEM;
            $sortOrder = array_sum(explode('.', $number));
        } else {
            // Gets the prefix of the number
            // Extracts the last digit and adds 1 to it
            $lastDigit = (int) substr($lastChildNumber, -1) + 1;
            // Generates the next number
            $number  = $parentNumber . '.' . $lastDigit;
            $sortOrder = array_sum(explode('.', $number));
        }
    }
    $controllerWBSItem->insert(
        $id,
        $projectId,
        $description,
        $number,
        $sortOrder,
        $isLeaf
    );
    $id = mysql_insert_id();

    if (!$firstItem) {
        $controllerWBSItem->setNotLeaf($parentId);
    }
} else {
    $sortOrder = array_sum(explode('.', $itemNumber));
    $controllerWBSItem->insert(
        $id,
        $projectId,
        $description,
        $itemNumber,
        $sortOrder,
        $isLeaf
    );
}

$eapItem = new WBSItemEstimation();
$eapItem->store($id, $estimatedSize, $estimatedSizeUnit);

$AppUI->setMsg($AppUI->_("LBL_THE_WBS_ITEM") ." ($description) " . $AppUI->_("LBL_WAS_SAVED_M")  , UI_MSG_OK, true);
echo $AppUI->getMsg();
exit();

?>