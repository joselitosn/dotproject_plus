<?php
if (!defined('DP_BASE_DIR')) {
    die('You should not access this file directly.');
}
require_once (DP_BASE_DIR . "/modules/communication/comunication_controller.php");
$channelId = $_POST['channel_id'];

$controller=new ComunicationController();

$arr = array('sucesso' => false, 'erro'=> false, 'msg' => '');

if ($_POST['del'] == 1) {
    $count=$controller->channelIsBeenUtilized($channelId);
    if($count==0){
        $rdel = new DBQuery;
        $rdel->setDelete('communication_channel');
        $rdel->addWhere('communication_channel_id=' .$channelId);
        $rdel->exec();
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
    if ('' !== $channelId) {
        $q = new DBQuery;
        $q->addUpdate('communication_channel', $channel);
        $q->addTable('communication_channel');
        $q->addWhere('communication_channel_id=' .$channelId);
        $q->exec();
        $arr['sucesso'] = true;
        $arr['erro'] = false;
        $arr['update'] = true;
        $arr['msg'] = 'Canal alterado';
    } else {
        if(!$controller->channelAlreadyExists($channel)){
            $radd = new DBQuery;
            $radd->addInsert('communication_channel', $channel);
            $radd->addTable('communication_channel');
            $radd->exec();
            $channelId = mysql_insert_id();
            $arr['sucesso'] = true;
            $arr['erro'] = false;
            $arr['update'] = false;
            $arr['msg'] = 'Canal incluído';
        }else{
            $arr['sucesso'] = false;
            $arr['erro'] = true;
            $arr['msg'] = 'Este canal já existe';
        }
    }

    $arr['data'] = array(
        'id' => $channelId,
        'name' => $channel
    );
}

echo json_encode($arr);
exit();
