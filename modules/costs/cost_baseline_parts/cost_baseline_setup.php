<?php
require_once DP_BASE_DIR . "/modules/costs/costs_functions.php";

$projectSelected = intval(dPgetParam($_GET, 'project_id'));
$q = new DBQuery();
$q->clear();
$q->addQuery("*");
$q->addTable("budget");
$q->addWhere("budget_Project_id = " . $projectSelected);
$v = $q->exec();
$budget_id = $v->fields["budget_id"];

// check if this record has dependancies to prevent deletion
$msg = '';
$bud = null;
if ((!db_loadObject($q->prepare(), $bud)) && ($budget_id > 0)) {
    $AppUI->setMsg('Budget');
    $AppUI->setMsg("invalidID", UI_MSG_ERROR, true);
    $AppUI->redirect();
}
//INSERT NA TABELA BUDGET_RESERVE

insertReserveBudget($projectSelected);

$whereProject = '';
if ($projectSelected != null) {
    $whereProject = ' and cost_project_id=' . $projectSelected;
}
// Get humans estimatives
$q->clear();
$q->addQuery('*');
$q->addTable('costs');
$q->addWhere("cost_type_id = '0' $whereProject");
$q->addOrder('cost_description');
$humanCost = $q->loadList();

// Get not humans estimatives
$q->clear();
$q->addQuery('*');
$q->addTable('costs');
$q->addWhere("cost_type_id = '1' $whereProject");
$q->addOrder('cost_description');
$notHumanCost = $q->loadList();
?>