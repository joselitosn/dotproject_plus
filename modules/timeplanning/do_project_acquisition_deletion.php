<?php
require_once (DP_BASE_DIR . "/modules/timeplanning/control/acquisition/controller_acquisition_planning.class.php");
$id= dPgetParam($_POST, "acquisition_planning_id");
$controller  = new ControllerAcquisitionPlanning(); 
$controller->delete($id); 
$AppUI->setMsg($AppUI->_("LBL_ACQUISITION_ITEM_EXCLUDED",UI_OUTPUT_HTML), UI_MSG_OK);
echo $AppUI->getMsg();
exit();
?>
