<?php

if (!defined('DP_BASE_DIR')) {
  die('You should not access this file directly.');
}

//add costs sql
$budget_reserve_id = intval(dPgetParam($_POST, 'budget_reserve_id', 0));
$del = intval(dPgetParam($_POST, 'del', 0));

$not = dPgetParam($_POST, 'notify', '0');
if ($not!='0') {
    $not='1';
}
$obj = new CBudgetReserve();
if ($budget_reserve_id) { 
	$obj->_message = 'updated';
} else {
	$obj->_message = 'added';
}

if (!$obj->bind($_POST)) {
	$AppUI->setMsg($obj->getError(), UI_MSG_ERROR);
	$AppUI->redirect();
}
$projectSelected = intval(dPgetParam($_POST, 'project_id'));
// delete the item
if ($del) {
	$obj->load($budget_reserve_id);
	if (($msg = $obj->delete())) {
		$AppUI->setMsg("LBL_CONTINGENCY_RESERVE_EXCLUDED", UI_MSG_ERROR);
	} else {
		if ($not=='1') {
            $obj->notify();
        }
	}
}
$value = $obj->budget_reserve_financial_impact;
$intValue = substr($value, 0, strlen($value)-3);
$intValue = str_replace(".", "", $intValue);
$obj->budget_reserve_financial_impact = $intValue;


if (($msg = $obj->store())) {
        $AppUI->setMsg($msg, UI_MSG_ERROR);
} else {
        $obj->load($obj->budget_reserve_id);
        if ($not=='1') {
        $obj->notify();
        }
        $AppUI->setMsg("LBL_CONTINGENCY_RESERVE_SAVED", UI_MSG_OK, true);
}
echo $AppUI->getMsg();
exit();

?>