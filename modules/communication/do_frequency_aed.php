<?php
if (!defined('DP_BASE_DIR')) {
    die('You should not access this file directly.');
}
require_once (DP_BASE_DIR . "/modules/communication/comunication_controller.php");
$frequencyId = $_POST['frequency_id'];
$controller=new ComunicationController();

$arr = array('sucesso' => false, 'erro'=> false, 'msg' => '');

if ($_POST['del'] == 1) {
    $count=$controller->frequencyIsBeenUtilized($frequencyId);
    if($count==0){
        $rdel = new DBQuery;
        $rdel->setDelete('communication_frequency');
        $rdel->addWhere('communication_frequency_id=' .$frequencyId);
        $rdel->exec();
        $arr['sucesso'] = true;
        $arr['erro'] = false;
        $arr['msg'] = 'Frequência excluída';  
    }else{
        $arr['sucesso'] = false;
        $arr['erro'] = true;
        $arr['msg'] = 'Esta frequência está sendo utilizada e por isso não pode ser excluída';
    }
} else {
    $frequency = $_POST['communication_frequency'];
    $hasDate = $_POST['communication_frequency_showdate'] != null ? 'Sim' : 'Não';
    
    if ('' !== $frequencyId) {
        $q = new DBQuery;
        $q->addUpdate('communication_frequency', $frequency);
        $q->addUpdate('communication_frequency_hasdate', $hasDate);
        $q->addTable('communication_frequency');
        $q->addWhere('communication_frequency_id=' .$frequencyId);
        $q->exec();
        $arr['sucesso'] = true;
        $arr['erro'] = false;
        $arr['update'] = true;
        $arr['msg'] = 'Frequência alterada';
    } else {
        if(!$controller->frequencyAlreadyExists($frequency)) {
            $radd = new DBQuery;
            $radd->addInsert('communication_frequency', $frequency);
            $radd->addInsert('communication_frequency_hasdate', $hasDate);
            $radd->addTable('communication_frequency');
            $radd->exec();
            $frequencyId = mysql_insert_id();
            $arr['sucesso'] = true;
            $arr['erro'] = false;
            $arr['update'] = false;
            $arr['msg'] = 'Frequência incluída';
        }else{
            $arr['sucesso'] = false;
            $arr['erro'] = true;
            $arr['msg'] = 'Esta frequência já existe';
        }
    }

    $arr['data'] = array(
        'id' => $frequencyId,
        'name' => $frequency,
        'hasDate' => $hasDate
    );
}

echo json_encode($arr);
exit();
