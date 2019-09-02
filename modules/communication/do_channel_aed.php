<?php
if (!defined('DP_BASE_DIR')) {
    die('You should not access this file directly.');
}
require_once (DP_BASE_DIR . "/modules/communication/comunication_controller.php");
$channelId = $_POST['channel_id'];
$controller=new ComunicationController();
// add communication channel
// if (isset($_GET['communication_channel'])){
//     if(!$controller->channelAlreadyExists($_GET['communication_channel'])){
//         $add = ($_GET['communication_channel']);
//         $radd = new DBQuery;
//         $radd->addInsert('communication_channel', $add);
//         $radd->addTable('communication_channel');
//         $radd->exec();
//         $AppUI->setMsg("LBL_COMUNICATION_CHANNEL_INCLUDED", UI_MSG_OK, true);
//     }else{
//         $AppUI->setMsg("LBL_VALIDATION_DUPLICATED_ITEMS", UI_MSG_WARNING, true);
//     }
//     $AppUI->redirect("m=communication&a=addedit_channel&project_id=" . $_GET["project_id"]);
//     //header('location:?m=communication&a=addedit_channel');
// }

$arr = array('sucesso' => false, 'erro'=> false, 'msg' => '');

if ($_POST['del'] == 1) {
    $count=$controller->channelIsBeenUtilized($channelId);
    if($count==0){
        $rdel = new DBQuery;
        $rdel->setDelete('communication_channel');
        $rdel->addWhere('communication_channel_id=' .$channelId);
        $rdel->exec();
        $AppUI->setMsg("LBL_COMUNICATION_CHANNEL_EXCLUDED", UI_MSG_OK, true);
        $arr['sucesso'] = true;
        $arr['erro'] = false;
        $arr['msg'] = 'Canal excluído';  
    }else{
        $arr['sucesso'] = false;
        $arr['erro'] = true;
        $arr['msg'] = 'Este canal está sendo utilizado e por isso não pode ser excluído';
    }
} else {
    $channel = $_POST['communication_channel'];
    if(!$controller->channelAlreadyExists($channel)){
        $radd = new DBQuery;
        $radd->addInsert('communication_channel', $channel);
        $radd->addTable('communication_channel');
        $radd->exec();
        $arr['sucesso'] = true;
        $arr['erro'] = false;
        $arr['msg'] = 'Canal incluído';
    }else{
        $arr['sucesso'] = false;
        $arr['erro'] = true;
        $arr['msg'] = 'Este canal já existe';
    }
}

echo json_encode($arr);
exit();
