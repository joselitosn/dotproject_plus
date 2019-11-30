<?php

if (!defined('DP_BASE_DIR')) {
  die('You should not access this file directly.');
}

//add costs sql
$budget_id = intval(dPgetParam($_POST, 'budget_id', 0));
$del = intval(dPgetParam($_POST, 'del', 0));

$not = dPgetParam($_POST, 'notify', '0');
if ($not!='0') {
    $not='1';
}
$obj = new CBudget();
if ($budget_id) { 
	$obj->_message = 'updated';
} else {
	$obj->_message = 'added';
}

if (!$obj->bind($_POST)) {
	$AppUI->setMsg($obj->getError(), UI_MSG_ERROR);
	//$AppUI->redirect();
}
 $projectSelected = intval(dPgetParam($_POST, 'project_id'));
//delete the item
if ($del) {
	$obj->load($budget_id);
	if (($msg = $obj->delete())) {
		$AppUI->setMsg($msg, UI_MSG_ERROR);
	} else {
        $AppUI->setMsg("Orçamento excluído!", UI_MSG_ALERT, true);
	}
}

if (($msg = $obj->store())) {
    $AppUI->setMsg("Reserva gerencial registrada!", UI_MSG_OK, true);
} else {
    $AppUI->setMsg("Reserva gerencial registrada!", UI_MSG_OK, true);
}

echo $AppUI->getMsg();
exit();
?>