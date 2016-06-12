<?php

$feedback_list=array();
$feedback_list[0]= new Feedback(0, "","",true,"");//dummy feedback, just to fill index 0
//The comments at the right mean the file which includes the refered feedback
/* Integração*/
$feedback_list[1]= new Feedback(1,$AppUI->_("LBL_FEEDBACK_SHORT_1"), $AppUI->_("LBL_FEEDBACK_DESCRIPTION_1",UI_OUTPUT_HTML), true, $AppUI->_("LBL_FEEDBACK_INTEGRATION") );// modules\initiating\addedit.php
$feedback_list[2]= new Feedback(2,$AppUI->_("LBL_FEEDBACK_SHORT_2"), $AppUI->_("LBL_FEEDBACK_DESCRIPTION_2",UI_OUTPUT_HTML), false, $AppUI->_("LBL_FEEDBACK_INTEGRATION")); // modules\initiating\addedit.php
$feedback_list[3]= new Feedback(3,$AppUI->_("LBL_FEEDBACK_SHORT_3"), $AppUI->_("LBL_FEEDBACK_DESCRIPTION_3",UI_OUTPUT_HTML),true, $AppUI->_("LBL_FEEDBACK_INTEGRATION")); // modules\timeplanning\projects_tab.2integratedmodules.php
/*Escopo*/
$feedback_list[4]= new Feedback(4,$AppUI->_("LBL_FEEDBACK_SHORT_4"),$AppUI->_("LBL_FEEDBACK_DESCRIPTION_4",UI_OUTPUT_HTML),true, $AppUI->_("LBL_FEEDBACK_SCOPE"));// \modules\dotproject_plus\projects_tab.planning_and_monitoring.php
$feedback_list[5]= new Feedback(5,$AppUI->_("LBL_FEEDBACK_SHORT_5"),$AppUI->_("LBL_FEEDBACK_DESCRIPTION_5",UI_OUTPUT_HTML),true,$AppUI->_("LBL_FEEDBACK_SCOPE")); //\modules\dotproject_plus\projects_tab.planning_and_monitoring.php
$feedback_list[6]= new Feedback(6,$AppUI->_("LBL_FEEDBACK_SHORT_6") ,$AppUI->_("LBL_FEEDBACK_DESCRIPTION_6",UI_OUTPUT_HTML) ,false,$AppUI->_("LBL_FEEDBACK_SCOPE")); //\modules\dotproject_plus\projects_tab.planning_and_monitoring.php
//$feedback_list[7]= new Feedback(7, "Pacotes de trabalho não podem ser decompostos em novos items da EAP.","Você já está derivando as atividades do projeto dos pacotes de trabalho. Cuidado para não criar subitens da EAP para pacotes de trabalho que já tiveram atividades derivadas.",true,"Escopo");//será bloqueado a criação de pacotes de trabalho para items da EAP que já tem atividades //\modules\dotproject_plus\projects_tab.planning_and_monitoring.php
/* Tempo */
$feedback_list[8]= new Feedback(8,$AppUI->_("LBL_FEEDBACK_SHORT_8") ,$AppUI->_("LBL_FEEDBACK_DESCRIPTION_8",UI_OUTPUT_HTML),true,$AppUI->_("LBL_FEEDBACK_TIME")); // modules\timeplanning\view\projects_mdp.php
$feedback_list[9]= new Feedback(9,$AppUI->_("LBL_FEEDBACK_SHORT_9") ,$AppUI->_("LBL_FEEDBACK_DESCRIPTION_9",UI_OUTPUT_HTML),true,$AppUI->_("LBL_FEEDBACK_TIME"));// \modules\dotproject_plus\projects_tab.planning_and_monitoring.php
//$feedback_list[10]= new Feedback(10, "Granularidade das atividades","Existem atividades com menos de 1 hora de duração. Verifique se a granularidade das atividades está adequada.",true,"Tempo");// \modules\dotproject_plus\projects_tab.planning_and_monitoring.php
$feedback_list[11]= new Feedback(11, $AppUI->_("LBL_FEEDBACK_SHORT_11") ,$AppUI->_("LBL_FEEDBACK_DESCRIPTION_11",UI_OUTPUT_HTML),false,$AppUI->_("LBL_FEEDBACK_TIME"));// \modules\dotproject_plus\projects_tab.planning_and_monitoring.php
$feedback_list[12]= new Feedback(12, $AppUI->_("LBL_FEEDBACK_SHORT_12") ,$AppUI->_("LBL_FEEDBACK_DESCRIPTION_12",UI_OUTPUT_HTML),false,$AppUI->_("LBL_FEEDBACK_TIME"));// \modules\dotproject_plus\projects_tab.planning_and_monitoring.php
$feedback_list[13]= new Feedback(13, $AppUI->_("LBL_FEEDBACK_SHORT_13") ,$AppUI->_("LBL_FEEDBACK_DESCRIPTION_13",UI_OUTPUT_HTML),false,$AppUI->_("LBL_FEEDBACK_TIME")); //\modules\initiating\addedit.php
/*Custo*/
$feedback_list[14]= new Feedback(14, $AppUI->_("LBL_FEEDBACK_SHORT_14") ,$AppUI->_("LBL_FEEDBACK_DESCRIPTION_14",UI_OUTPUT_HTML),true,$AppUI->_("LBL_FEEDBACK_COST"));//modules\costs\view_budget.php
$feedback_list[15]= new Feedback(15, $AppUI->_("LBL_FEEDBACK_SHORT_15") ,$AppUI->_("LBL_FEEDBACK_DESCRIPTION_15",UI_OUTPUT_HTML),true,$AppUI->_("LBL_FEEDBACK_COST"));// modules\costs\view_costs.php
$feedback_list[16]= new Feedback(16, $AppUI->_("LBL_FEEDBACK_SHORT_16") ,$AppUI->_("LBL_FEEDBACK_DESCRIPTION_16",UI_OUTPUT_HTML),true,$AppUI->_("LBL_FEEDBACK_COST"));//modules\costs\view_budget.php
$feedback_list[17]= new Feedback(17, $AppUI->_("LBL_FEEDBACK_SHORT_17") ,$AppUI->_("LBL_FEEDBACK_DESCRIPTION_17",UI_OUTPUT_HTML),true,$AppUI->_("LBL_FEEDBACK_COST")); //\modules\human_resources\view_hr.php
$feedback_list[18]= new Feedback(18, $AppUI->_("LBL_FEEDBACK_SHORT_18") ,$AppUI->_("LBL_FEEDBACK_DESCRIPTION_18",UI_OUTPUT_HTML),true,$AppUI->_("LBL_FEEDBACK_COST"));//modules\costs\view_costs.php
/*Qualidade*/
$feedback_list[19]= new Feedback(19,  $AppUI->_("LBL_FEEDBACK_SHORT_19") ,$AppUI->_("LBL_FEEDBACK_DESCRIPTION_19",UI_OUTPUT_HTML),false,$AppUI->_("LBL_FEEDBACK_QUALITY"));//\modules\timeplanning\view\quality\project_quality_planning.php
/*Recursos Humanos*/
$feedback_list[20]= new Feedback(20,  $AppUI->_("LBL_FEEDBACK_SHORT_20") ,$AppUI->_("LBL_FEEDBACK_DESCRIPTION_20",UI_OUTPUT_HTML),true,$AppUI->_("LBL_FEEDBACK_HR")); //modules\costs\view_costs.php
$feedback_list[21]= new Feedback(21,  $AppUI->_("LBL_FEEDBACK_SHORT_21") ,$AppUI->_("LBL_FEEDBACK_DESCRIPTION_21",UI_OUTPUT_HTML),true,$AppUI->_("LBL_FEEDBACK_HR")); //modules\costs\view_costs.php
$feedback_list[22]= new Feedback(22, $AppUI->_("LBL_FEEDBACK_SHORT_22") ,$AppUI->_("LBL_FEEDBACK_DESCRIPTION_22",UI_OUTPUT_HTML),false,$AppUI->_("LBL_FEEDBACK_HR")); //modules\human_resources\view_company_roles.php
/*Comunicação*/
$feedback_list[23]= new Feedback(23, $AppUI->_("LBL_FEEDBACK_SHORT_23") ,$AppUI->_("LBL_FEEDBACK_DESCRIPTION_23",UI_OUTPUT_HTML),true,$AppUI->_("LBL_FEEDBACK_COMUNICATION")); //modules\communication\index_project.php
$feedback_list[24]= new Feedback(24, $AppUI->_("LBL_FEEDBACK_SHORT_24") ,$AppUI->_("LBL_FEEDBACK_DESCRIPTION_24",UI_OUTPUT_HTML),false,$AppUI->_("LBL_FEEDBACK_COMUNICATION"));//modules\communication\addedit_channel.php
$feedback_list[25]= new Feedback(25, $AppUI->_("LBL_FEEDBACK_SHORT_25") ,$AppUI->_("LBL_FEEDBACK_DESCRIPTION_25",UI_OUTPUT_HTML),false,$AppUI->_("LBL_FEEDBACK_COMUNICATION"));//modules\communication\index_project.php
/*Riscos*/
$feedback_list[26]= new Feedback(26, $AppUI->_("LBL_FEEDBACK_SHORT_26") ,$AppUI->_("LBL_FEEDBACK_DESCRIPTION_26",UI_OUTPUT_HTML),true,$AppUI->_("LBL_FEEDBACK_RISK"));//\modules\risks\do_risks_aed.php
$feedback_list[27]= new Feedback(27, $AppUI->_("LBL_FEEDBACK_SHORT_27") ,$AppUI->_("LBL_FEEDBACK_DESCRIPTION_27",UI_OUTPUT_HTML),true,$AppUI->_("LBL_FEEDBACK_RISK"));//\modules\risks\do_risks_aed.php
$feedback_list[28]= new Feedback(28, $AppUI->_("LBL_FEEDBACK_SHORT_28") ,$AppUI->_("LBL_FEEDBACK_DESCRIPTION_28",UI_OUTPUT_HTML),false,$AppUI->_("LBL_FEEDBACK_RISK"));//modules\risks\indexProjectView.php
/*Aquisição*/
$feedback_list[29]= new Feedback(29, $AppUI->_("LBL_FEEDBACK_SHORT_29") ,$AppUI->_("LBL_FEEDBACK_DESCRIPTION_29",UI_OUTPUT_HTML),true,$AppUI->_("LBL_FEEDBACK_ACQUISITIONS"));//\modules\timeplanning\view\acquisition\acquisition_planning.php
$feedback_list[30]= new Feedback(30, $AppUI->_("LBL_FEEDBACK_SHORT_30") ,$AppUI->_("LBL_FEEDBACK_DESCRIPTION_30",UI_OUTPUT_HTML),false,$AppUI->_("LBL_FEEDBACK_ACQUISITIONS"));//\modules\timeplanning\view\acquisition\acquisition_planning.php
/*Stakeholder*/
$feedback_list[31]= new Feedback(31, $AppUI->_("LBL_FEEDBACK_SHORT_31"), $AppUI->_("LBL_FEEDBACK_DESCRIPTION_31",UI_OUTPUT_HTML),true,"Stakeholder");//\modules\stakeholder\project_stakeholder.php
$feedback_list[32]= new Feedback(32, $AppUI->_("LBL_FEEDBACK_SHORT_32"), $AppUI->_("LBL_FEEDBACK_DESCRIPTION_32",UI_OUTPUT_HTML),true,"Stakeholder");//\modules\stakeholder\project_stakeholder.php
$feedback_list[32]= new Feedback(32, $AppUI->_("LBL_FEEDBACK_SHORT_32"), $AppUI->_("LBL_FEEDBACK_DESCRIPTION_32",UI_OUTPUT_HTML),true,"Stakeholder");//\modules\stakeholder\project_stakeholder.php
$feedback_list[33]= new Feedback(33, $AppUI->_("LBL_FEEDBACK_SHORT_33"), $AppUI->_("LBL_FEEDBACK_DESCRIPTION_33",UI_OUTPUT_HTML),false,"Stakeholder");//\modules\stakeholder\project_stakeholder.php
$sid=session_id();
if(!isset($sid)){
    session_start();
}
//unset($_SESSION["user_feedback"]);
if( !isset($_SESSION["user_feedback"]) ){
    $_SESSION["user_feedback"]=array();
    $_SESSION["user_feedback_read"]=array();
    $_SESSION["user_especific_feedback"]=1;
    $_SESSION["user_generic_feedback"]=1;
}

function addFeedbackToUser($feedback){
    if(!isset($_SESSION["user_feedback_read"][$feedback->getId()])){//adiciona o feedback apenas se o mesmo ainda não foi lido
        $_SESSION["user_feedback"][$feedback->getId()]=$feedback->getId();
    }
}

function getIconByKnowledgeArea($knowledgeArea){
    global $AppUI;
    switch ($knowledgeArea) {
        case $AppUI->_("LBL_FEEDBACK_INTEGRATION"):
            return "integration";
            break;
        case $AppUI->_("LBL_FEEDBACK_SCOPE"):
            return "scope";
            break;
        case $AppUI->_("LBL_FEEDBACK_TIME"):
            return "time";
            break;
        case $AppUI->_("LBL_FEEDBACK_COST"):
            return "cost";
            break;
        case $AppUI->_("LBL_FEEDBACK_QUALITY"):
            return "quality";
            break;
        case $AppUI->_("LBL_FEEDBACK_HR"):
            return "hr";
            break;
        case $AppUI->_("LBL_FEEDBACK_COMUNICATION"):
            return "comunication";
            break;
        case $AppUI->_("LBL_FEEDBACK_RISK"):
            return "risks";
            break;
        case $AppUI->_("LBL_FEEDBACK_ACQUISITIONS"):
           return "aquisition";
           break;
       case "Stakeholder":
           return "integration";
           break;   
    }
}
global $AppUI;
//functions to verify feedback trigger
function wbsItemWithSingleWorkPackage($projectId){
    $list=array();
    $q = new DBQuery();
    $q->addQuery('number, count(*)');
    $q->addTable('project_eap_items', 't');
    $q->addWhere("length(number)>1 and project_id=$projectId group by left(number,length(number)-1) having count(*)=1");
    $sql = $q->prepare();
    $items = db_loadList($sql);
    $result=false;
    if(sizeof($items)>0){
        $result=true;
    }
    return $result;
}
//SELECT number,count(*) FROM dotproject_usability.dotp_project_eap_items where length(number)>1 and project_id=2 group by left(number,length(number)-1) having count(*)=1

function wbsWorkPackageTotalEffort($projectId){
    $q = new DBQuery();
    $q->addQuery('effort');//SELECT * FROM dotproject_usability.dotp_project_tasks_estimations te inner join dotproject_usability.dotp_tasks t on t.task_id=te.task_id where te.effort>0 and t.task_project=2;
    $q->addTable('project_tasks_estimations', 'te');
    $q->addJoin("tasks", "t", "t.task_id=te.task_id", "inner");
    $q->addWhere("te.effort>0 and t.task_project=$projectId");
    $sql = $q->prepare();
    $items = db_loadList($sql);
    $result=false;
    if(sizeof( $items)>0){
        $result=true;
    }
    return $result;
}

function projectCharterIsCompleteAndNotAuhorized($projectId){
    require_once DP_BASE_DIR . "/modules/initiating/initiating.class.php";
    $obj = CInitiating::findByProjectId($projectId);
    $initiating_id = $obj->initiating_id;
    $initiating_completed = 0;
    // se for update verifica se ja esta concluido o preenchimento do termo de abertura do projeto
    if ($initiating_id) {
        $initiating_completed = $obj->initiating_completed;
    }

    // se o termo de abertura estiver concluido verifica se est� aprovado
    $initiating_approved = 0;
    if ($initiating_completed) {
        $initiating_approved = $obj->initiating_approved;
    }

    // se o termo de abertura estiver aprovado verifica se est� autorizado
    $initiating_authorized = 0;
    if ($initiating_approved) {
        $initiating_authorized = $obj->initiating_authorized;
    }

    if($initiating_completed==1 && $initiating_authorized==0){
        return true;
    }
    return false;
}

function projectCharterIsNotComplete($projectId){
    require_once DP_BASE_DIR . "/modules/initiating/initiating.class.php";
    $obj = CInitiating::findByProjectId($projectId);
    $initiating_id = $obj->initiating_id;
    $initiating_completed = 0;
    // se for update verifica se ja esta concluido o preenchimento do termo de abertura do projeto
    if ($initiating_id) {
        $initiating_completed = $obj->initiating_completed;
    }

    // se o termo de abertura estiver concluido verifica se est� aprovado
    $initiating_approved = 0;
    if ($initiating_completed) {
        $initiating_approved = $obj->initiating_approved;
    }

    // se o termo de abertura estiver aprovado verifica se est� autorizado
    $initiating_authorized = 0;
    if ($initiating_approved) {
        $initiating_authorized = $obj->initiating_authorized;
    }

    if($initiating_id!="" && $initiating_completed==0 && $initiating_authorized==0 && $initiating_approved==0){
        return true;
    }
    return false;
}

//get date diference
function monthsBetween($startDate, $endDate) {
    $timeStart = strtotime($startDate);
    $timeEnd = strtotime($endDate);
    // Adding current month + all months in each passed year
    $numMonths = 1 + (date("Y",$timeEnd)-date("Y",$timeStart))*12;
    // Add/subtract month difference
    $numMonths += date("m",$timeEnd)-date("m",$timeStart);
    return $numMonths;
}
function isProjectDurationLongerThanAYear($projectId){
    require_once DP_BASE_DIR . "/modules/initiating/initiating.class.php";
    $obj = CInitiating::findByProjectId($projectId);
    if($obj->initiating_start_date!="" && $obj->initiating_end_date!="" ){
        $monthsDiff=monthsBetween($obj->initiating_start_date, $obj->initiating_end_date);
        if($monthsDiff>13){
            return true;
        }
    }
    return false;
}

function getActivitiesCount($projectId){
    $q = new DBQuery();
    $q->addQuery("t.task_id");
    $q->addTable("tasks", "t");
    $q->addWhere("t.task_project=" . $projectId);
    $sql = $q->prepare();
    $records = db_loadList($sql);
    return count($records);
}

function thereIsWBSItemWithSingleActivity($projectId){
    $q = new DBQuery();
    $q->addQuery("count(wbs_task.task_id)");
    $q->addTable("project_eap_items", "wbs");
    $q->addJoin("tasks_workpackages","wbs_task", "wbs.id=wbs_task.eap_item_id", "inner");
    $q->addWhere("wbs.project_id=$projectId group by wbs.id having count(wbs_task.task_id)=1");
    $sql = $q->prepare();
    $records = db_loadList($sql);
    if ( count($records) > 0){
        return true;
    }
    return false;
}

function thereIsActivityLongerThanTwoWeeksDuration($projectId){
    //SELECT  FROM dotproject_usability. t inner join dotp_project_tasks_estimations te on te.task_id=t.task_id where task_project=4 and  duration>14;
    $q = new DBQuery();
    $q->addQuery("t.task_id, te.duration");
    $q->addTable("tasks", "t");
    $q->addJoin("project_tasks_estimations","te", "te.task_id=t.task_id", "inner");
    $q->addWhere("task_project=$projectId and  duration>14");
    $sql = $q->prepare();
    $records = db_loadList($sql);
    if ( count($records) > 0){
        return true;
    }
    return false;
}

function thereIsNonConfiguredHR($projectId){
    $project = new CProject();
    $project->load($projectId);
    $company_id = $project->project_company;
    $query = new DBQuery();
    $query->addTable("users", "u");
    $query->addQuery("user_id, user_username, contact_last_name, contact_first_name, contact_id");
    $query->addJoin("contacts", "c", "u.user_contact = c.contact_id");
    $query->addJoin("human_resource", "h", "h.human_resource_user_id=u.user_id");
    $query->addWhere("c.contact_company = " . $company_id ." and h.human_resource_user_id is NULL");
    $query->addOrder("contact_last_name");
    //echo $query->prepare();
    $res =$query->exec();
    if(sizeof($res)>0){
        return true;
    }
    return false;
}

function thereIsNonHumanResources($projectId){
    require_once DP_BASE_DIR . "/modules/costs/costs_functions.php";
    $whereProject = ' and cost_project_id=' . $projectId;
    $notHumanCost = getResources("Non-Human", $whereProject);
     if(sizeof($notHumanCost)>0){
        return true;
    }
    return false;
}

function thereIsContingenceReserve($projectId){
    $q = new DBQuery();
    $q->addQuery('*');
    $q->addTable('budget_reserve', 'b');
    $q->addWhere("budget_reserve_project_id = " . $projectId);
    $q->addOrder('budget_reserve_risk_id');
    $risks = $q->loadList();
    if(sizeof($risks)>0){
        return true;
    }
    return false;
}

function thereIsRiskResponse($projectId,$responseType){
    $q = new DBQuery();
    $q->addQuery('risk_id');
    $q->addTable('risks', 'r');
    $q->addWhere("risk_project = " . $projectId . " and risk_strategy=$responseType");
    $risks = $q->loadList();
    if(sizeof($risks)>0){
        return true;
    }
    return false;
}

function thereIsStakeholders($projectId){
    require_once DP_BASE_DIR . "/modules/initiating/initiating.class.php";
    $initiating = CInitiating::findByProjectId($projectId);
    if (!is_null($initiating)) {
        $initiating_id = $initiating->initiating_id;
        $q = new DBQuery();
        $q->addQuery("*");
        $q->addTable("initiating_stakeholder", "stk");
        $q->addJoin("initiating", "i", "i.initiating_id = stk.initiating_id");
        $q->addJoin("contacts", "c", "c.contact_id = stk.contact_id");
        $q->addWhere("i.initiating_id=".$initiating_id);
        $q->addOrder("stk.initiating_id");
        $q->addOrder("stk.contact_id");
        $list = $q->loadList();
        if(sizeof($list)>0){
            return true;
        }
    }
    return false;
}

function thereIsProjectCharter($projectId){
    require_once DP_BASE_DIR . "/modules/initiating/initiating.class.php";
    $initiating = CInitiating::findByProjectId($projectId);
    if (is_null($initiating)) {
        return false;
    }
    return true;
}

function thereIsOverAllocatedResources($projectId){
/*
SELECT t.task_id, t.task_start_date, t.task_end_date, er.role_id, count(hra.human_resource_id)
FROM dotp_tasks t 
inner join dotp_project_tasks_estimated_roles er on er.task_id=t.task_id
inner join dotp_human_resource_allocation hra on hra.project_tasks_estimated_roles_id=er.id
where t.task_project=2
group by hra.human_resource_id
having count(hra.human_resource_id)>1
*/           
    $q = new DBQuery();
    $q->addQuery("t.task_id, t.task_start_date, t.task_end_date, er.role_id, count(hra.human_resource_id)");
    $q->addTable("tasks", "t");
    $q->addJoin("project_tasks_estimated_roles","er", "er.task_id=t.task_id", "inner");
    $q->addJoin("human_resource_allocation","hra", "hra.project_tasks_estimated_roles_id=er.id", "inner");
    $q->addWhere("t.task_project=$projectId group by hra.human_resource_id having count(hra.human_resource_id)>1");
    $sql = $q->prepare();
    //echo $sql;
    $records = db_loadList($sql);
    if ( count($records) > 0){
        return true;
    }
    return false;
}

$projectId=$_GET["project_id"];
//function to include feedback accordingly with user action
$initiatingPath=$_POST["user_choosen_feature_initiating"];
if($_POST["user_choosen_feature_initiating"]=="" && $_GET["tab"]==0){
    $initiatingPath="/modules/initiating/addedit.php";
}
if($projectId!=""){
    switch ($initiatingPath) {
            case "/modules/initiating/addedit.php":
                //1,2 -initiating
                if(projectCharterIsCompleteAndNotAuhorized($projectId)){
                    addFeedbackToUser($feedback_list[1]);
                }
                if (projectCharterIsNotComplete($projectId)){
                    addFeedbackToUser($feedback_list[2]);
                }
                //13- time
                if(isProjectDurationLongerThanAYear($projectId)){
                    addFeedbackToUser($feedback_list[13]);
                }    
                break;
            case "/modules/stakeholder/project_stakeholder.php":
                //31,32,33 - stakeholder
                if(thereIsStakeholders($projectId)){
                    addFeedbackToUser($feedback_list[31]); 
                    addFeedbackToUser($feedback_list[32]); 
                }
                addFeedbackToUser($feedback_list[33]); 
                break;
    }
}

$planningPath=$_GET["targetScreenOnProject"]!=""?$_GET["targetScreenOnProject"]:$_POST["user_choosen_feature"];
if($planningPath=="" && $_GET["tab"]==1){ //planning tab
    $planningPath="/modules/dotproject_plus/projects_tab.planning_and_monitoring.php";
}
switch($planningPath){
    case "/modules/dotproject_plus/projects_tab.planning_and_monitoring.php":
       //4,5,6 - scope
        if($projectId!=""){
            if(getActivitiesCount($projectId)>0){
                if(wbsItemWithSingleWorkPackage($projectId)){
                    addFeedbackToUser($feedback_list[4]);
                }
                if (wbsWorkPackageTotalEffort($projectId)){
                    addFeedbackToUser($feedback_list[5]);
                }
                addFeedbackToUser($feedback_list[6]); 
                
                //9,11,12 - time
                addFeedbackToUser($feedback_list[12]);
                if(thereIsWBSItemWithSingleActivity($projectId)){
                    addFeedbackToUser($feedback_list[9]);
                }
                if(thereIsActivityLongerThanTwoWeeksDuration($projectId)){
                    addFeedbackToUser($feedback_list[11]);
                }
            }    
        }
        
        break;
    case "/modules/costs/view_costs.php":
        //15, 18 - cost
        if(thereIsNonConfiguredHR($projectId)){
            addFeedbackToUser($feedback_list[15]); 
        }
        if (thereIsNonHumanResources($projectId)){
            addFeedbackToUser($feedback_list[18]); 
        }
        //20,21 - rh
        if(thereIsOverAllocatedResources($projectId)){
            addFeedbackToUser($feedback_list[20]); 
        }
        addFeedbackToUser($feedback_list[21]); 
        break;
    case "/modules/costs/view_budget.php":
        //14,16 - cost
        if(!thereIsContingenceReserve($projectId)){
            addFeedbackToUser($feedback_list[14]);
            addFeedbackToUser($feedback_list[16]);
        }
        break;
    case "/modules/timeplanning/view/quality/project_quality_planning.php":
        addFeedbackToUser($feedback_list[19]);
        break;
    case "/modules/communication/index_project.php":
        //23, 25 - comunication
        addFeedbackToUser($feedback_list[23]);
        addFeedbackToUser($feedback_list[25]);
        break;
    case "/modules/risks/projects_risks.php":
        //28
        addFeedbackToUser($feedback_list[28]);
        //26 e 27
        if(thereIsRiskResponse($projectId,1)){
            addFeedbackToUser($feedback_list[26]);
        }
        if(thereIsRiskResponse($projectId,0)){
            addFeedbackToUser($feedback_list[27]);
        }
        break;
    case "/modules/timeplanning/view/acquisition/acquisition_planning.php":
        //29 e 30
        addFeedbackToUser($feedback_list[29]); 
        addFeedbackToUser($feedback_list[30]); 
        break;
    case "/modules/stakeholder/project_stakeholder.php":
        //31,32,33 - stakeholder
        if(thereIsStakeholders($projectId)){
            addFeedbackToUser($feedback_list[31]); 
            addFeedbackToUser($feedback_list[32]); 
        }
        addFeedbackToUser($feedback_list[33]); 
        break;
}  

switch($_GET["show_external_page"]){
    case "/modules/timeplanning/view/projects_mdp.php":
        //8 - time
        addFeedbackToUser($feedback_list[8]);
        break;
}

if($_GET["a"]=="view_hr"){
    //17 - cost
    addFeedbackToUser($feedback_list[17]);
}
if($_GET["m"]=="companies" && $_GET["a"]=="view" && $_GET["tab"]==1 ){
   // 22- rh
    addFeedbackToUser($feedback_list[22]);
}

if($projectId!=""){
    if($_GET["m"]=="communication" && $_GET["a"]=="addedit_channel"){
        //24- comunication
        addFeedbackToUser($feedback_list[24]);
    }

    if ($_SESSION["gqs_tab"]=="1"){
        //3- initiation
        if (thereIsProjectCharter($projectId)){
            addFeedbackToUser($feedback_list[3]);
        }
    }
}
?>