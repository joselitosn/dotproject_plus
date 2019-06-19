<?php
require_once (DP_BASE_DIR . "/modules/timeplanning/control/controller_company_role.class.php");
$company_id = dPgetParam($_GET, 'company_id');
$controllerCompanyRole = new ControllerCompanyRole();
$json = dPgetParam($_POST, 'chart_data');

// Delete old json
$controllerCompanyRole->deleteByCompanyId($company_id);

// Insert the updated one
$controllerCompanyRole->storeJson($json, $company_id) ;
exit();
?>
