<?php
if (!defined('DP_BASE_DIR')) {
    die('You should not access this file directly.');
}

require_once (DP_BASE_DIR . '/modules/tasks/tasks.class.php');

//save
$project_id = $_POST["project_id"];
$task_id= $_POST["activity_id"];
$task_order= $_POST["task_order"];
$direction= $_POST["direction"];

$q = new DBQuery();
$q->addQuery("t.task_id, t.task_order");
$q->addTable("tasks", "t");
$q->addWhere("t.task_project=" . $project_id);
$q->addOrder("t.task_order");
$sql = $q->prepare();
$records = db_loadList($sql);

//Get activities order
$indexMovedTask=-1;
$i=0;
foreach($records as $record){
    if($record[0]==$task_id){
        $indexMovedTask=$i;
    }
    $i++;
}
//Get activity the will be moved
$movedActivity=$records[$indexMovedTask+$direction];
//Set new orders
$new_order=$movedActivity[1];
$current_order=$task_order;
if($current_order==$new_order){
    $new_order+=$direction;
}

//Update new orders on database
$obj = new CTask();
$obj->load($task_id);
$obj->task_order=$new_order;
$obj->store();

$obj = new CTask();
$obj->load($movedActivity[0]);
$obj->task_order=$current_order;
$obj->store();


$AppUI->redirect('m=projects&a=view&project_id='.$project_id);
?>