<?php /* COMPANIES $Id: do_company_aed.php 6149 2012-01-09 11:58:40Z ajdonnison $ */
if (!defined('DP_BASE_DIR')) {
  die('You should not access this file directly.');
}

$del = (int)dPgetParam($_POST, 'del', 0);
$edit = (int)dPgetParam($_POST, 'company_id', 0);

include_once (DP_BASE_DIR . '/modules/human_resources/human_resources.class.php');

$obj = new CCompany();
$objPolicies = new CCompaniesPolicies;

$err = null;

if (!$obj->bind($_POST)) {
   $err = $obj->getError();
}

if (!$objPolicies->bind($_POST)) {
    $err = $objPolicies->getError();
}

if (null != $err) {
    echo $err;
    exit();
}

require_once($AppUI->getSystemClass('CustomFields'));

if ($del) {
	if (!$obj->canDelete($msg)) {
        $AppUI->setMsg($msg, UI_MSG_ERROR);
        echo $AppUI->getMsg();
        exit();
	}

	if (($msg = $obj->delete())) {
		$AppUI->setMsg($msg, UI_MSG_ERROR);
        echo $AppUI->getMsg();
        exit();
	} else {
		$AppUI->setMsg('Company deleted', UI_MSG_ALERT, true);
        echo $AppUI->getMsg();
        exit();
	}
} else {
	if ($msg = $obj->store()) {
        $AppUI->setMsg($msg, UI_MSG_ERROR);
        echo $AppUI->getMsg();
        exit();
	} else {
        $custom_fields = New CustomFields($m, 'addedit', $obj->company_id, 'edit');
        $custom_fields->bind($_POST);
        $sql = $custom_fields->store($obj->company_id);
        if ($edit) {
            $objPolicies->store();
        }
        $AppUI->setMsg(((@$_POST['company_id']) ? 'Company updated' : 'Company added'), UI_MSG_OK, true);
        echo $AppUI->getMsg();
        exit();
	}
}

echo $msg;
exit();
