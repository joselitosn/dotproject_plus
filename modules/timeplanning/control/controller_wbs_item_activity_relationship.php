<?php
require_once (DP_BASE_DIR . "/modules/timeplanning/model/wbs_item_activity_relationship.class.php");
require_once (DP_BASE_DIR . '/modules/tasks/tasks.class.php');
require_once (DP_BASE_DIR . "/modules/timeplanning/control/controller_wbs_items.class.php");
class ControllerWBSItemActivityRelationship{
	
	function ControllerWBSItemActivityRelationship(){
           
	}
	
	function insert($id, $work_package, $order) {
		$WBSItemActivityRelationship = new WBSItemActivityRelationship();
		return $WBSItemActivityRelationship->store($id, $work_package, $order);
	}
	
	function delete($id){
		$WBSItemActivityRelationship = new WBSItemActivityRelationship();
		$WBSItemActivityRelationship->delete($id);
	}
	
	function getActivitiesByWorkPackage($WBSItemId, $allocatedHrId = null){
		$list= array();
		$q = new DBQuery();
		$q->addQuery('t.task_id');
		$q->addTable('tasks_workpackages', 't');
		$q->addTable('tasks', 'pt');
		if ($allocatedHrId !== null && $allocatedHrId != -1) {
            $q->addJoin('project_tasks_estimated_roles', 'pter', 'pter.task_id = t.task_id');
            $q->addJoin('human_resource_allocation', 'hra', 'hra.project_tasks_estimated_roles_id = pter.id ');
            $q->addWhere("hra.human_resource_id = $allocatedHrId");
        }
		$q->addWhere('eap_item_id = '.$WBSItemId .' and pt.task_id=t.task_id and pt.task_milestone<>1 order by t.activity_order');
		$sql = $q->prepare();


        /**
         *
        left join `dotp_project_tasks_estimated_roles` as pter on pter.task_id = t.task_id
        left join `dotp_human_resource_allocation` as hra on hra.project_tasks_estimated_roles_id = pter.id
         */

		$tasks = db_loadList($sql);
		foreach ($tasks as $task) {
            $obj = new CTask();
            $obj->load($task['task_id']);
            $list[$task['task_id']]=$obj;
		}
		return $list;
	}
	
	function getAllActivities($project_id){
		$list= array();
		$q = new DBQuery();
		$q->addQuery('pt.task_id');
		$q->addTable('tasks', 'pt');
		$q->addWhere("pt.task_milestone<>1 and pt.task_project=$project_id");
		$sql = $q->prepare();
		$tasks = db_loadList($sql);
		foreach ($tasks as $task) {
                    $obj = new CTask();
                    $obj->load($task['task_id']);
                    $list[$task['task_id']]=$obj;
		}
		return $list;
	}
	
	
}
