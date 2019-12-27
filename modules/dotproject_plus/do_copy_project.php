<?php
require_once (DP_BASE_DIR . "/modules/dotproject_plus/copy_project/ProjectTemplate.php");
$projectTemplate= new ProjectTemplate();
$sourceProjectId=$_POST["project_to_copy"];
$targetProjectId=$_POST["target_project_id"];
$result=$projectTemplate->closeWBS($sourceProjectId, $targetProjectId);
$resp = array('err' => false, 'msg' => '');
if($result===0){
    $resp['msg'] = $AppUI->_("LBL_COPY_FROM_TEMPLATE_SUCCESS");
}else if ($result===1){
    $resp['err'] = true;
    $resp['msg'] = $AppUI->_("LBL_COPY_FROM_TEMPLATE_ERROR_1");
}
echo json_encode($resp);
exit();
?>