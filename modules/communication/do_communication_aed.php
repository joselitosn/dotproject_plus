<?php
if (!defined('DP_BASE_DIR')) {
die('You should not access this file directly.');
}

$communication_id = intval(dPgetParam($_POST, 'communication_id'), 0);

$del = intval(dPgetParam($_POST, 'del', 0));

$not = dPgetParam($_POST, 'notify', '0');
if ($not!='0') {
    $not='1';
}
$obj = new CCommunication();
$obj->communication_project_id = dPgetParam($_POST, 'project');
$obj->communication_frequency_id = dPgetParam($_POST, 'frequency');
$obj->communication_channel_id = dPgetParam($_POST, 'channel');
$obj->communication_responsible_authorization = dPgetParam($_POST, 'responsible');
$issuers= dPgetParam($_POST, 'issuing');
$receptors = dPgetParam($_POST, 'receptor');

if ($communication_id) {
    $obj->_message = 'updated';
}else {
    $obj->_message = 'added';
}

if (!$obj->bind($_POST)) {
    $AppUI->setMsg($obj->getError(), UI_MSG_ERROR);
    echo $AppUI->getMsg();
    exit();
}

// delete the item
if ($del) {
    $obj->load($communication_id);
    if (($msg = $obj->delete())) {
        $AppUI->setMsg($msg, UI_MSG_ERROR);
        echo $AppUI->getMsg();
        exit();
    } else {
        $q = new DBQuery();
        $q->setDelete('communication_receptor');
        $q->addWhere('communication_id='.$communication_id);
        $q->exec();
        
        $q = new DBQuery();
        $q->setDelete('communication_issuing');
        $q->addWhere('communication_id='.$communication_id);
        $q->exec();
        
        if ($not=='1'){
            $obj->notify();
        }
        $AppUI->setMsg("LBL_COMUNICATION_EXCLUDED", UI_MSG_OK, true);
      }
      echo $AppUI->getMsg();
      exit();
}

if (($msg = $obj->store())) {
    $AppUI->setMsg($msg, UI_MSG_ERROR);
} else {
    $q = new DBQuery();
    $q->setDelete('communication_receptor');
    $q->addWhere('communication_id='.$communication_id);
    $q->exec();
    if (null !== $receptors) {
        foreach($receptors as $value){
            $q = new DBQuery();
            $q->addInsert('communication_id', $obj->communication_id);
            $q->addInsert('communication_stakeholder_id', $value);
            $q->addTable('communication_receptor');
            $q->exec();
        }
    }
    
    $q = new DBQuery();
    $q->setDelete('communication_issuing');
    $q->addWhere('communication_id='.$communication_id);
    $q->exec();
    if (null !== $issuers) {
        foreach($issuers as $value){
            $q = new DBQuery();
            $q->addInsert('communication_id', $obj->communication_id);
            $q->addInsert('communication_stakeholder_id', $value);
            $q->addTable('communication_issuing');
            $q->exec();
        }
    }
    
    $obj->load($obj->communication_id);
    if ($not=='1') {
        $obj->notify();
    }
    $AppUI->setMsg("LBL_COMUNICATION_REGISTERED" , UI_MSG_OK, true);
  }
  echo $AppUI->getMsg();
  exit();
?>