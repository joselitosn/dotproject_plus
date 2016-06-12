<?php
require_once (DP_BASE_DIR . "/modules/timeplanning/control/controller_wbs_items.class.php");
$controllerWBSItem= new ControllerWBSItem();
$project_id=dPgetParam($_POST, 'project_id');
$wbs_item_id=dPgetParam($_POST, 'wbs_item_id');
$wbs_item_name=dPgetParam($_POST, 'wbs_item_name');
$controllerWBSItem->delete($wbs_item_id);

$AppUI->setMsg($AppUI->_("LBL_THE_WORK_PACKAGE") . " ($wbs_item_name) " . $AppUI->_("LBL_WAS_EXCLUDED",UI_OUTPUT_HTML) .".", UI_MSG_OK, true);
$AppUI->redirect('m=projects&a=view&project_id='.$project_id);
?>
