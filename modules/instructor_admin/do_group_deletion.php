<?php
if(isset($_POST["group_id_for_deletion"])){
    $response = array('err' => false, 'msg' => null);

    $class_id = $_POST["class_id"];
    $user_id = $_POST["group_id_for_deletion"];

    $query = new DBQuery();
    $query->addTable("dpp_classes_users");
    $query->addWhere("class_id=". $class_id ." and user_id=". $user_id);
    $sql = $query->prepareDelete();
    $return=db_exec($sql);
    if($return){
        $AppUI->setMsg($AppUI->_("LBL_DATA_SUCCESSFULLY_DELETED"), UI_OUTPUT_HTML);
        $response['msg'] = $AppUI->getMsg();
    } else {
        $AppUI->setMsg($AppUI->_("Something went wrong"), UI_OUTPUT_HTML);
        $response['err'] = true;
        $response['msg'] = $AppUI->getMsg();
    }
    echo json_encode($response);
}
exit();
?>
