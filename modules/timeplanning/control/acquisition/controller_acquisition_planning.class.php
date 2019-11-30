<?php

require_once (DP_BASE_DIR . "/modules/timeplanning/model/acquisition/acquisition_planning.class.php");

/**
 * Class to provides acquisition plannig data.
 * @author Rafael Queiroz Gonçalves
 */
class ControllerAcquisitionPlanning {

    function ControllerAcquisitionPlanning() {
        
    }

    public function sendDataToBeStored($id, $projectId, $acquisitionRoles, $supplierManagementProcess, $itemsToBeAcquired, $documentsToAcquisition, $criteriaForSelection, $contractType, $additionalRequirements) {
        $acquisition = new AcquisitionPlanning();
        $acquisition->setId($id);
        $acquisition->setProjectId($projectId);
        $acquisition->setItemsToBeAcquired($itemsToBeAcquired);
        $acquisition->setContractType($contractType);
        $acquisition->setDocumentsToAcquisition($documentsToAcquisition);
        $acquisition->setCriteriaForSelection($criteriaForSelection);
        $acquisition->setAdditionalRequirements($additionalRequirements);
        $acquisition->setSupplierManagementProcess($supplierManagementProcess);
        $acquisition->setAcquisitionRoles($acquisitionRoles);
        return $acquisition->store();
    }

    public function getAcquisitionPlanningsPerProject($projectId) {
        $object = new AcquisitionPlanning();
        $list = $object->loadAll($projectId);
        return $list;
    }

    public function getAcquisitionPlanning($id) {
        $object = new AcquisitionPlanning();
        $object->load($id);
        return $object;
    }

    public function delete($id) {
        $object = new AcquisitionPlanning();
        $object->delete($id);
    }

    function deleteRoles($acquisitionId) {
        $q = new DBQuery();
        $q->setDelete("acquisition_planning_roles");
        $q->addWhere("acquisition_id=" . $acquisitionId);
        $q->exec();
        $q->clear();
    }

    function deleteCriteria($aquisitionId) {
        $q = new DBQuery();
        $q->setDelete("acquisition_planning_criteria");
        $q->addWhere("acquisition_id=" . $aquisitionId);
        $q->exec();
        $q->clear();
    }

    function deleteRequirements($aquisitionId) {
        $q = new DBQuery();
        $q->setDelete("acquisition_planning_requirements");
        $q->addWhere("acquisition_id=" . $aquisitionId);
        $q->exec();
        $q->clear();
    }

    function storeRoles($roles, $responsabilities, $acquisitionId) {
        foreach($roles as $key => $role) {
            $resp = $responsabilities[$key];
            $q = new DBQuery();
            $q->addTable("acquisition_planning_roles");
            $q->addInsert("acquisition_id", $acquisitionId);
            $q->addInsert("responsability", $resp);
            $q->addInsert("role", $role);
            $q->exec();
        }
    }

    function storeCriteria($criterias, $weights, $acquisitionId) {
        foreach($criterias as $key => $criteria) {
            $weight = $weights[$key];
            $q = new DBQuery();
            $q->addTable("acquisition_planning_criteria");
            $q->addInsert("acquisition_id", $acquisitionId);
            $q->addInsert("criteria", $criteria);
            $q->addInsert("weight", $weight);
            $q->exec();
        }
    }

    function storeRequirements($requirements, $acquisitionId) {
        foreach($requirements as $key => $requirement) {
            $q = new DBQuery();
            $q->addTable("acquisition_planning_requirements");
            $q->addInsert("acquisition_id", $acquisitionId);
            $q->addInsert("requirement", $requirement);
            $q->exec();
        }
    }
    
    public function loadCriteria($acquisitionId){
        $q = new DBQuery();
        $q->addQuery("c.id, c.criteria, c.weight");
        $q->addTable("acquisition_planning_criteria", "c");
        $q->addWhere("acquisition_id =" . $acquisitionId);
        $results = db_loadHashList($q->prepare(true), "id");
        return $results;
    }
    
     public function loadRoles($acquisitionId){
        $q = new DBQuery();
        $q->addQuery("r.id, r.role, r.responsability");
        $q->addTable("acquisition_planning_roles", "r");
        $q->addWhere("acquisition_id =" . $acquisitionId);
        $results = db_loadHashList($q->prepare(true), "id");
        return $results;
    }
    
    public function loadRequirements($acquisitionId){
        $q = new DBQuery();
        $q->addQuery("r.id, r.requirement");
        $q->addTable("acquisition_planning_requirements", "r");
        $q->addWhere("acquisition_id =" . $acquisitionId);
        $results = db_loadHashList($q->prepare(true), "id");
        return $results;
    }
    

}

?>
