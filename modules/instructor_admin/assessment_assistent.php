<?php
require_once DP_BASE_DIR . "/modules/initiating/initiating.class.php";
require_once DP_BASE_DIR . "/modules/projects/projects.class.php";
require_once (DP_BASE_DIR . "/modules/timeplanning/control/controller_wbs_items.class.php");
require_once (DP_BASE_DIR . "/modules/timeplanning/model/wbs_item_estimation.class.php");
require_once (DP_BASE_DIR . "/modules/timeplanning/model/wbs_dictionary_entry.class.php");
require_once (DP_BASE_DIR . "/modules/timeplanning/control/controller_wbs_item_activity_relationship.php");
require_once (DP_BASE_DIR . "/modules/timeplanning/model/project_task_estimation.class.php");

class AssessmentMessage{
    var $message;
    var $deterministic=true;
    
    public function __construct($message, $deter){
      $this->message=$message;
      $this->deterministic=$deter;
    } 
}

class AssessmentAssistent{
    private $project=null;
    
    public function __construct($projectId) {
        $this->projectId = $projectId;
        $this->project = new CProject();
        $this->project->load($projectId);
    }
    
    function getIntegrationMessages(){    
        $messages=array();
        $initiating_obj = CInitiating::findByProjectId($this->project->project_id);
        //objetivo
        $objective=$initiating_obj->initiating_objective;
        if( !stristr($objective, "TCC") && !stristr($objective, "Conclusão Curso")) {
           $message = new AssessmentMessage("A realizacao do TCC deveria estar entre os objetivos do projeto.",false);
           array_push($messages,$message);
        }else{
          $message = new AssessmentMessage("O termo de abertura está imcompleto: sem objetivo.",true);
           array_push($messages,$message);
        }
        //datas
        $start_date =$initiating_obj->initiating_start_date;
        $end_date =$initiating_obj->initiating_end_date;
        if($start_date != "" && $end_date !=""){
            $data_inicio = new DateTime($start_date);
            $data_fim = new DateTime($end_date);
            $dateInterval = $data_inicio->diff($data_fim);
            $duracaoMesses=$dateInterval->m; 
            $duracaoYears= $dateInterval->y;
            if( $duracaoYears==0 && $duracaoMesses <10){
                $message = new AssessmentMessage("O termo de abertura estimou apenas ".$duracaoMesses." meses de duração. E um projeto de TCC deveria durar ao menos 2 semestres completos.",true);
                array_push($messages,$message);
            }
        }else{
           $message = new AssessmentMessage("O termo de abertura está incompleto: sem as datas de início e término.",true);
           array_push($messages,$message);
        }
        //resultados
        $results=$initiating_obj->initiating_expected_result;
        if( !stristr($results, "TCC")) {
           $message = new AssessmentMessage("O relatório de TCC deveria estar entre os resultados do projeto.",false);
           array_push($messages,$message);
        }
        
        if( !stristr($results, "Defesa")) {
           $message = new AssessmentMessage("A realização da defesa deveria estar entre os resultados do projeto.",false);
           array_push($messages,$message);
        }
        
        if( trim($results)=="" ) {        
          $message = new AssessmentMessage("O termo de abertura está imcompleto: sem resultados esperados.",true);
          array_push($messages,$message);
        }
        //criterio de aceite
        $acceptance_criteria=$initiating_obj->initiating_success;
         if( trim($acceptance_criteria)=="" ) {        
          $message = new  AssessmentMessage("O termo de abertura está imcompleto: sem critérios de aceite.",true);
          array_push($messages,$message);
        }
        if( !stristr( $acceptance_criteria, "Aprova") ) {
           $message = new AssessmentMessage("A aprovação do TCC deveria estar entre os critérios de aceite.",false);
           array_push($messages,$message);
        }
        return $messages;
    }
    
    function getScopeMessages(){    
        $messages=array();
        //declaracao do scopo
        if(trim($this->project->project_description)==""){
            $message = new AssessmentMessage("A declaracao do escopo não foi definida.",true);
            array_push($messages,$message);
        }
        
        //EAP
        $controllerWBSItem = new ControllerWBSItem();
        $items = $controllerWBSItem->getWBSItems($this->project->project_id);
        if ( count($items) == 0 ){
            $message = new AssessmentMessage("A EAP não foi definida.",true);
            array_push($messages,$message);
        }
        
        if ( count($items) <8){
            $message = new AssessmentMessage("A EAP contém apenas "+count($items) +" itens, talvez tenha sido pouco subdividida.",true);
            array_push($messages,$message);
        }
        
        //dicionario EAP e estimativa de tamanho
        foreach ($items as $item) {
            if( $item->isLeaf() == 1){
                
                $obj = new WBSDictionaryEntry();
                $obj->load($item->getId());
                if ( trim($obj->getDescription()) == "" ){               
                    $message = new AssessmentMessage("Existem pacotes de trabalho não detalhados no dicionario da EAP. Exemplo: ".$item->getNumber() . " ". $item->getName() ,true);
                    array_push($messages,$message);
                }
                $eapItem = new WBSItemEstimation();
                $eapItem->load($item->getId());
                
                if ( trim($eapItem->getSize()) == "" || $eapItem->getSize() == 0 ){
                    $message = new AssessmentMessage("Existem pacotes de trabalho sem tamanho estimado. Exemplo: ". $item->getNumber() . " ".$item->getName() ,true);
                    array_push($messages,$message);
                }
                $sizeUnit=$eapItem->getSizeUnit();
                if ( trim($sizeUnit) == ""){
                    $message = new AssessmentMessage("Existem pacotes de trabalho sem unidade de tamanho definida. Exemplo: ". $item->getNumber() . " ".$item->getName() ,true);
                    array_push($messages,$message);
                }
                
                if ( stristr($sizeUnit, "dia") || stristr($sizeUnit, "hora")) {
                    $message = new AssessmentMessage("Dias ou horas são unidades de duração, e não de tamanho. Exemplo: ". $item->getNumber() . " ".$item->getName() . ", unidade - " . $sizeUnit,false);
                    array_push($messages,$message);
                }
               
            }         
        }
        return $messages;
    }
    
    function getTimeMessages(){
         $messages=array();
         $controllerWBSItem = new ControllerWBSItem();
         $items = $controllerWBSItem->getWBSItems($this->project->project_id);
         $activietiesWPController= new ControllerWBSItemActivityRelationship();
          foreach ($items as $item) {
            if( $item->isLeaf() == 1){
                $activities=$activietiesWPController->getActivitiesByWorkPackage($item->getId());
                if(count($activities)==0){
                     $message = new AssessmentMessage("Existem pacotes de trabalho sem atividades derivadas. Exemplo: ".$item->getNumber() . " ". $item->getName(),true);
                    array_push($messages,$message);
                }
                 if(count($activities)==1){
                     $message = new AssessmentMessage("Existem pacotes de trabalho com uma única atividade derivada. Deste modo o item da EAP pai não precisaria ter sido subdividido.  Exemplo: ". $item->getNumber() . " ". $item->getName(),true);
                    array_push($messages,$message);
                }          
                                
                foreach ($activities as $activity) {
                    $projectTaskEstimation = new ProjectTaskEstimation();
                    $projectTaskEstimation->load($activity->task_id);
                    $estimatedDuration=$projectTaskEstimation->getDuration();
                    if($estimatedDuration>14){
                        $message = new AssessmentMessage("A atividade " . $activity->task_name . " tem duração estimada de ". $estimatedDuration ." dias, sendo uma duração muito longa para um projeto de TCC.",true);
                        array_push($messages,$message);
                    }
                    
                     if($estimatedDuration==0){
                        $message = new AssessmentMessage("Existem atividades sem duração estimada. Exemplo: ". $activity->task_name,true);
                        array_push($messages,$message);
                    }
                    
                    $roles= $projectTaskEstimation->getRoles();
                    if ( count($roles) == 0 ) {
                        $message = new AssessmentMessage("Existem atividades sem recursos estimados. Exemplo: ". $activity->task_name,true);
                        array_push($messages,$message);
                    }                       
                }
            }
          }
         
         return $messages;
    }
    
     function getCostMessages(){
         $messages=array();
         //reserva gerencial
         $q = new DBQuery();
         $q->addQuery("*");
         $q->addTable("budget");
         $q->addWhere("budget_project_id = " . $this->project->project_id);
         $q->addOrder("budget_id");
         $v = $q->exec();
         
         if($v->fields["budget_reserve_management"]=="" || $v->fields["budget_reserve_management"]==0){
              $message = new AssessmentMessage("A reserva gerencial não foi informada.",true);
              array_push($messages,$message);
         }
         
         if($v->fields["budget_reserve_management"]<5 || $v->fields["budget_reserve_management"]>10){
              $message = new AssessmentMessage("A reserva gerencial de ".$v->fields["budget_reserve_management"]."% está fora dos valores considerados adequados para o gerenciamento de custos. ",true);
              array_push($messages,$message);
         }
         //reserva de contingencia
         $q = new DBQuery();
         $q->addTable("budget_reserve", "b");
         $q->addWhere("budget_reserve_project_id = " .  $this->project->project_id);
         $q->addOrder("budget_reserve_risk_id");
         $risks = $q->loadList();
         if(count ($risks)==0){
             $message = new AssessmentMessage("Não há nenhum risco na reserva de contingência do projeto.",true);
             array_push($messages,$message);
         }
         $q = new DBQuery();
         $q->addTable("budget_reserve", "b");
         $q->addWhere("budget_reserve_project_id = " .  $this->project->project_id  ." and budget_reserve_financial_impact=0");
         $q->addOrder("budget_reserve_risk_id");
         $risks = $q->loadList();
        foreach ($risks as $row) {
             $message = new AssessmentMessage("O risco '".$row["budget_reserve_description"]."' previsto na reserva de contingência, não teve valor informado.",true);
             array_push($messages,$message);
         }
         //custos com recursos humanos e não humanos
          require_once DP_BASE_DIR . "/modules/costs/costs_functions.php";
          $whereProject = " and cost_project_id=" .  $this->project->project_id ;
       
         //não humanos
          $notHumanCost = getResources("Non-Human",$whereProject);
         if(count($notHumanCost )==0){
             $message = new AssessmentMessage("Nenhum custo com recurso não humano foi informado.",true);
             array_push($messages,$message);
         }
          if(count($notHumanCost )<4 && count($notHumanCost)>0){
             $message = new AssessmentMessage("Apenas ".count($notHumanCost ). " recursos não humanos foram registrados para o projeto.",true);
             array_push($messages,$message);
         }
         //humanos
         $humanCost = getResources("Human",$whereProject);
         if(count($humanCost)==0){
             $message = new AssessmentMessage("Nenhum custo com recurso humano foi informado.",true);
             array_push($messages,$message);
         }
        foreach ($humanCost as $row) {
            if($row["cost_value_total"]==0){
             $message = new AssessmentMessage("O RH '".$row["cost_description"]."' não teve custos estimados",true);
             array_push($messages,$message);
            }
        }
         return $messages;
    }
    
    function getQualityMessages() {
        $messages = array();
        require_once (DP_BASE_DIR . "/modules/timeplanning/control/quality/controller_quality_planning.class.php");
        $controllerQ = new ControllerQualityPlanning();
        $objectQ = $controllerQ->getQualityPlanningPerProject($this->project->project_id);
        $quality_planning_id = $objectQ->getId();
        $qualityPolicies = $objectQ->getQualityPolicies();
        if (trim($qualityPolicies) == "") {
            $message = new AssessmentMessage("As normas de confirmidade e políticas para qualidade não foram informadas.", true);
            array_push($messages, $message);
        }
        if (substr_count(strtoupper($qualityPolicies), "NBR") < 4) {
            $message = new AssessmentMessage("As normas da ABNT (NBRs) deveriam ser mais detalhadas quanto a confirmidade em relação às normas.", true);
            array_push($messages, $message);
        }
        $assuranceItems = $controllerQ->loadAssuranceItems($quality_planning_id);

        if (count($assuranceItems) == 0) {
            $message = new AssessmentMessage("Nenhum item para garantia da qualidade foi informado.", true);
            array_push($messages, $message);
        }

        foreach ($assuranceItems as $id => $data) {
            $what = $data[1];
            $how = $data[4];
            if (stristr($what, "Progresso") > 0 || stristr($what, "desempenho") || stristr($what, "performance" || stristr($what, "monitor") > 0)) {
                $message = new AssessmentMessage("Avaliação do progresso está relacionado ao monitoramento do projeto e não sua garantia de qualidade. Item com problema: " . $what, false);
                array_push($messages, $message);
            }
            if (stristr($how, "Progresso") > 0 || stristr($how, "desempenho") || stristr($how, "performance" || stristr($how, "monitor") > 0)) {
                $message = new AssessmentMessage("Avaliação do progresso está relacionado ao monitoramento do projeto e não sua garantia de qualidade. Item com problema: " . $how, false);
                array_push($messages, $message);
            }
        }
        $requirements = $controllerQ->loadControlRequirements($quality_planning_id);
        if (count($requirements) == 0) {
            $message = new AssessmentMessage("Nenhum requisito para o controle da qualidade foi informado.", true);
            array_push($messages, $message);
        }
        $goals = $controllerQ->loadControlGoals($quality_planning_id);
        if (count($goals) == 0) {
            $message = new AssessmentMessage("Não foram estabelecidos objetivos de controle da qualidade.", true);
            array_push($messages, $message);
        }
        return $messages;
    }
    
    function getRHMessages() {
        $messages = array();
        require_once (DP_BASE_DIR . "/modules/timeplanning/control/controller_company_role.class.php");
        require_once DP_BASE_DIR . "/modules/system/roles/roles.class.php";
        $controllerCompanyRole = new ControllerCompanyRole();
        $roles = $controllerCompanyRole->getCompanyRoles($this->project->project_company);
       
        if(count($roles)==0){
            $message = new AssessmentMessage("Não foram definidos os papeis para o projeto.", true);
            array_push($messages, $message);
        }
        
        if (count($roles)>0 && count($roles)<4){
            $message = new AssessmentMessage("Foram definidos apenas ". count($roles)." papeis para o projeto.", true);
            array_push($messages, $message);
        }
        //organograma
        $hasIdentation=false;
        foreach ($roles as $role) {
           $identation = $role->getIdentation();
           if ( strlen($identation)>0 ){
                $hasIdentation=true;
           }
        }
         if (! $hasIdentation){
            $message = new AssessmentMessage("O organograma não contém hierarquia de papeis.", true);
            array_push($messages, $message);
        }
        
        
        $query = new DBQuery();
        $query->addTable("human_resources_role", "r");
        $query->addQuery("r.*");
        $query->addWhere("r.human_resources_role_company_id = " . $this->project->project_company);
        $res_companies = & $query->exec();
        for ($res_companies; !$res_companies->EOF; $res_companies->MoveNext()) {
            $competency=$res_companies->fields["human_resources_role_competence"];
            if( stristr($competency,"UFSC") >0 || stristr($competency,"INE") >0 ) {
                 $message = new AssessmentMessage("A compentência de um papel deve ser descrita quanto a suas habilidades, conhecimentos, e atitudes. O local de trabalho não faz parte das competências. Exemplo: papel - ". $res_companies->fields["human_resources_role_name"], false);
                 array_push($messages, $message);
            }
        }
         return $messages;
    }

    function getCommunicationMessages() {
        $messages = array();
        
        require_once DP_BASE_DIR . "/modules/communication/comunication_controller.php";
        $comunicationController = new ComunicationController();
        $comunications=$comunicationController->getCommunictionByProject($this->project->project_id);
        if(count($comunications)==0){
            $message = new AssessmentMessage("Nenhuma comunicação foi planejada para o projeto.", true);
            array_push($messages, $message);
        }
          if(count($comunications)>0 && count($comunications)<3){
            $message = new AssessmentMessage("Apenas ".count($comunications) ." comunicações foram planejadas para o projeto.", true);
            array_push($messages, $message);
        }
        $list_Emissor = $comunicationController->getListOfEmissor();
        $list_Receptor = $comunicationController->getListOfReceptor();
        foreach ($comunications as $row) {       
            $hasEmissor=false;
             foreach ($list_Emissor as $emissor) {
                if ($emissor["communication_id"] == $row["communication_id"]) {
                    $hasEmissor=true;
                }
            }
            if( !$hasEmissor){
                $message = new AssessmentMessage("A comunicação '".$row["communication_title"]."' não tem emissores.", true);
                array_push($messages, $message);
            }

            $hasReceptor=false;
            foreach ($list_Receptor as $receptor) {
                if ($receptor["communication_id"] == $row["communication_id"]) {
                    $hasReceptor=true;
                }
            }
             if( !$hasReceptor){
                $message = new AssessmentMessage("A comunicação '".$row["communication_title"]."' não tem receptores.", true);
                array_push($messages, $message);
            }
        }
         return $messages;
    }
    
function getProcurementMessages() {
    $messages = array();
        require_once (DP_BASE_DIR . "/modules/timeplanning/control/acquisition/controller_acquisition_planning.class.php");
        require_once (DP_BASE_DIR . "/modules/timeplanning/model/acquisition/acquisition_planning.class.php");
        $controller = new ControllerAcquisitionPlanning();
        $list = $controller->getAcquisitionPlanningsPerProject($this->project->project_id);
        if (count($list) == 0) {
            $message = new AssessmentMessage("Nenhuma aquisição foi planejada para o projeto.", true);
            array_push($messages, $message);
        }
        if (count($list) > 0 && count($list) < 4) {
            $message = new AssessmentMessage("Foram planejadas apenas " . count($list) . " aquisições para o projeto.", true);
            array_push($messages, $message);
        }
        foreach ($list as $object) {
            if(trim($object->getDocumentsToAcquisition())==""){
                 $message = new AssessmentMessage("Os documentos para aquisição não foram informados para aquisição '".$object->getItemsToBeAcquired()."'", true);
                array_push($messages, $message);
            }
            if(trim($object->getCriteriaForSelection())==""){
                 $message = new AssessmentMessage("Os critérios para seleção de fornecedor não foram informados para aquisição '".$object->getItemsToBeAcquired()."'", true);
                array_push($messages, $message);
            }
            if(trim($object->getSupplierManagementProcess())==""){
                 $message = new AssessmentMessage("O processo para gerenciamento do fornecedor não foi informado para aquisição '".$object->getItemsToBeAcquired()."'", true);
                 array_push($messages, $message);
            }
            
        }
        return $messages;
    }
    
    public function formatMessagesForWord($messages){
        foreach ($messages as $message){
            $message=$message->message;    
            $format = array();// $message->deterministic == true ? array('italic'=>'false', 'color'=>'000000') : array('italic'=>'true', 'color'=>'006699');
            $_SESSION["cell"]->addText(utf8_decode($message), $format); 
             //$_SESSION["cell"]->addTextBreak(1);//quebra linha se necessário
        }
    }
    
}