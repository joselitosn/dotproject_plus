<?php
if (!defined('DP_BASE_DIR')){
	die('You should not access this file directly');
}
class WBSItemActivityRelationship {
	
	function WBSItemActivityRelationship (){
	}
	
	function store($taskId, $workPackage, $taskOrder) {
			$q = new DBQuery();
			$q->addTable('tasks_workpackages');
            $q->addInsert('task_id', $taskId);
            $q->addInsert('eap_item_id', $workPackage);
            $q->addInsert("activity_order", $taskOrder);
			$q->exec();
			$q->clear();
	}

	
	function delete($id){
		$q = new DBQuery();
		$q->setDelete('tasks_workpackages');
		$q->addWhere('task_id=' . $id);
		$q->exec();
		$q->clear();
	}
	
}
