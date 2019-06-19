<?php
require_once (DP_BASE_DIR . "/modules/timeplanning/control/controller_wbs_item_activity_relationship.php");
$ControllerWBSItemActivityRelationship= new ControllerWBSItemActivityRelationship();
$project_id=dPgetParam($_POST, 'project_id');
$activity_id=dPgetParam($_POST, 'activity_id');
// $activity_name=dPgetParam($_POST, 'activity_name');
try{

    $ControllerWBSItemActivityRelationship->delete($activity_id);
} catch(Exception $e) {
    var_dump($e);
    die;
}



// $AppUI->setMsg( $AppUI->_("LBL_THE_ACTIVITY"). " ($activity_name) " . $AppUI->_("LBL_WAS_EXCLUDED_F"), UI_MSG_OK, true);
$AppUI->redirect('m=projects&a=view&project_id='.$project_id);
    // echo $AppUI->_("LBL_THE_ACTIVITY") . ' ' . $AppUI->_("LBL_WAS_EXCLUDED_F");
    // exit();
?>
