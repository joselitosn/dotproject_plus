<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ComunicationController
 *
 * @author rafael
 */
class ComunicationController {
    
    public function channelAlreadyExists($channel){
        $sql="SELECT  FROM dotp_ where ucase(communication_channel) = ucase(".  ($channel). ")";
        $q = new DBQuery();
        $q->addQuery("communication_channel_id");
        $q->addTable("communication_channel");
        $q->addWhere("ucase(communication_channel) = ucase('" . ($channel) . "')");
        $sql = $q->prepare();

        $records= db_loadList($sql);
        return sizeof($records) >0?true:false;
    }
    
    public function frequencyAlreadyExists($frequency){
        $q = new DBQuery();
        $q->addQuery("communication_frequency_id");
        $q->addTable("communication_frequency");
        $q->addWhere("ucase(communication_frequency) = ucase('" . ($frequency) . "')");
        $sql = $q->prepare();
       
        $records= db_loadList($sql);
        return sizeof($records) >0?true:false;
    }
    
    public function channelIsBeenUtilized($channelId){
        $count=0;
        $q = new DBQuery();
        $q->addQuery("count(communication_channel_id)");
        $q->addTable("communication");
        $q->addWhere("communication_channel_id=" . ($channelId));
        $sql = $q->prepare();
        $records= db_loadList($sql);
        foreach($records as $record){
           $count=$record[0];
        }
        return $count;
    }
    
    public function frequencyIsBeenUtilized($frequencyId){
        $count=0;
        $q = new DBQuery();
        $q->addQuery("count(communication_frequency_id)");
        $q->addTable("communication");
        $q->addWhere("communication_frequency_id=" . ($frequencyId));
        $sql = $q->prepare();
        $records= db_loadList($sql);
        foreach($records as $record){
           $count=$record[0];
        }
        return $count;
    }
    
    public function getCommunictionByProject($projectId){
       $q = new DBQuery();
        $q->addQuery("c.communication_id, c.communication_title, c.communication_information, ch.*, fr.*, p.project_name,  c.communication_restrictions as communication_restrictions");
        $q->addTable("communication", "c");
        $q->addJoin("communication_channel", "ch", "ch.communication_channel_id=c.communication_channel_id");
        $q->addJoin("communication_frequency", "fr", "fr.communication_frequency_id=c.communication_frequency_id");
        $q->addJoin("projects", "p", "p.project_id=c.communication_project_id");
        $q->addwhere("c.communication_project_id=" . $projectId);
        $list = $q->loadList();
        return $list;
    }
    
 public function getListOfEmissor(){   
    $q = new DBQuery();
    $q->addQuery("c.communication_id, co.contact_first_name as emissor_first_name, co.contact_last_name as emissor_last_name");
    $q->addTable("communication", "c");
    $q->addJoin("communication_issuing", "ci", "ci.communication_id=c.communication_id");
    $q->addJoin("initiating_stakeholder", "st", "st.initiating_stakeholder_id=ci.communication_stakeholder_id");
    $q->addJoin("contacts", "co", "co.contact_id=st.contact_id");
    $list_Emissor = $q->loadList();
    return  $list_Emissor;
 }

 public function getListOfReceptor(){  
    $q = new DBQuery();
    $q->addQuery("c.communication_id, cor.contact_first_name as receptor_first_name, cor.contact_last_name as receptor_last_name");
    $q->addTable("communication", "c");
    $q->addJoin("communication_receptor", "cr", "cr.communication_id=c.communication_id");
    $q->addJoin("initiating_stakeholder", "str", "str.initiating_stakeholder_id=cr.communication_stakeholder_id");
    $q->addJoin("contacts", "cor", "cor.contact_id=str.contact_id");
    $list_Receptor = $q->loadList();
    return  $list_Receptor;
 }
    
}
