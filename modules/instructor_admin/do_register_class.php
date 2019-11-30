<?php
require_once (DP_BASE_DIR . "/modules/instructor_admin/class.class.php");
$obj = new CClass();
$obj->bind($_POST);
$msg = $obj->store();
$AppUI->setMsg("LBL_CLASS_REGISTERED", UI_MSG_OK);
echo $AppUI->getMsg();
exit();
?>
