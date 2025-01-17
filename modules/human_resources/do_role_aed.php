<?php
if (!defined('DP_BASE_DIR')) {
  die('You should not access this file directly.');
}

$del = dPgetParam($_POST, 'del', 0);
$obj = new CHumanResourcesRole();
$msg = '';

if (! $obj->bind($_POST)) {
    $AppUI->setMsg($obj->getError(), UI_MSG_ERROR);
    echo $AppUI->getMsg();
    exit();
}

//var_dump($obj);die;

$AppUI->setMsg('Role');
if ($del) {
    if (($msg = $obj->delete())) {
        $AppUI->setMsg($msg, UI_MSG_ERROR);
    } else {
        $AppUI->setMsg('deleted', UI_MSG_ALERT, true);
    }
    echo $AppUI->getMsg();
    exit();
} else {
    if (($msg = $obj->store())) {
        $AppUI->setMsg($msg, UI_MSG_ERROR);
    } else {
        $AppUI->setMsg($_POST['human_resources_role_id'] ? 'updated' : 'added', UI_MSG_OK, true);
    }
    echo $AppUI->getMsg();
    exit();
}
?>
