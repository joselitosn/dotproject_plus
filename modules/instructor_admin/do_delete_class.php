<?php
require_once (DP_BASE_DIR . "/modules/instructor_admin/class.class.php");
$obj = new CClass();
$obj->bind($_POST);
$response = array('err' => false, 'msg' => null);
try {
    $result=$obj->delete();
    if($result>0){
        $AppUI->setMsg("LBL_CLASS_DELETED", UI_MSG_OK);
        $response['msg'] = $AppUI->getMsg();
    }
} catch(Exception $e) {
    $response['err'] = true;
    $response['msg'] = $e->getMessage();
}
echo json_encode($response);
exit();
?>
