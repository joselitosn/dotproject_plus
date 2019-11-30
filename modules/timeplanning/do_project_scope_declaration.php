<?php
require_once (DP_BASE_DIR . '/modules/projects/projects.class.php');
$projectId = dPgetParam($_POST, "project_id", 0);
$description = $_POST["scope_declaration"];
$obj = new CProject();
$obj->load($projectId);
$obj->project_description = $description;
$obj->store();
$AppUI->setMsg($AppUI->_("Declaração do escopo registrada!",UI_OUTPUT_HTML), UI_MSG_OK, true);
echo $AppUI->getMsg();
exit();
?>
