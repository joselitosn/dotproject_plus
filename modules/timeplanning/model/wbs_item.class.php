<?php

if (!defined('DP_BASE_DIR')) {
    die('You should not access this file directly');
}

class WBSItem {

    private $id = NULL;
    private $name = NULL;
    private $projectId = NULL;
    private $number = NULL;
    private $isLeaf = NULL;
    private $identation = NULL;
    private $sortOrder = NULL;
    private $parent = NULL;
    private $size = NULL;
    private $sizeUnit = NULL;

    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public function getProjectId() {
        return $this->projectId;
    }

    public function getNumber() {
        return $this->number;
    }

    public function isLeaf() {
        return $this->isLeaf;
    }

    public function getIdentation() {
        return $this->identation;
    }

    public function getSortOrder() {
        return $this->sortOrder;
    }
    
    public function setSortOrder($sortOrder) {
        $this->sortOrder = $sortOrder;
    }
    public function setParent($parent) {
        $this->parent = $parent;
    }

    public function getParent() {
        return $this->parent;
    }

    public function setSize($size) {
        $this->size = $size;
    }

    public function getSize() {
        return $this->size;
    }

    public function setSizeUnit($sizeUnit) {
        $this->sizeUnit = $sizeUnit;
    }

    public function getSizeUnit() {
        return $this->sizeUnit;
    }
    
    function WBSItem() {
        
    }

    function getLastChildByParentNumber($projectId, $number) {
        $numberLength = strlen($number);
        $q = new DBQuery();
        $q->addQuery('number');
        $q->addTable('project_eap_items');
        $q->addWhere('project_id =' . $projectId);
        $q->addWhere("substring(number, 1, $numberLength) = '$number'");
        $q->addWhere("char_length(number) - 2 = char_length('$number')");
        $q->addOrder('sort_order desc');
        return $q->loadResult();
    }

    function store($id, $projectId, $description, $number, $sortOrder, $isLeaf) {
        $q = new DBQuery();
        $q->addQuery('id');
        $q->addTable('project_eap_items');
        $q->addWhere('id =' . $id);
        $record = $q->loadResult();
        $q->clear();
        $q->addTable('project_eap_items');
        if (empty($record)) {
            $q->addInsert('project_id', $projectId);
            $q->addInsert('item_name', $description);
            $q->addInsert('sort_order', $sortOrder);
            $q->addInsert('number', $number);
            $q->addInsert('is_leaf', $isLeaf);
            
        } else {
            $q->addUpdate('item_name', $description);
            $q->addWhere('id = ' . $id);
        }
        $q->exec();
        $q->clear();
    }

    function load($idValue, $name, $identation, $number, $is_leaf, $parent = null, $size = null, $sizeUnit = null) {
        $this->id = $idValue;
        $this->name = $name;
        $this->identation = $identation;
        $this->number = $number;
        $this->isLeaf = $is_leaf;
        $this->parent = $parent;
        $this->size = $size;
        $this->sizeUnit = $sizeUnit;
    }

    function delete($id) {
        $q = new DBQuery();
        $q->setDelete('project_eap_items');
        $q->addWhere('id=' . $id);
        $q->exec();
        $q->clear();
    }

    function setNotLeaf($id) {
        $q = new DBQuery();
        $q->addTable('project_eap_items');
        $q->addUpdate('is_leaf', 0);
        $q->addWhere('id = ' . $id);
        $q->exec();
        $q->clear();
    }

    function toArray() {
        return array(
            'id' => $this->id,
            'name' => $this->name,
            'identation' => $this->identation,
            'number' => $this->number,
            'isLeaf' => $this->isLeaf,
            'parent' => $this->parent,
            'sort' => $this->sortOrder,
            'size' => $this->size,
            'sizeUnit' => $this->sizeUnit
        );
    }

}
