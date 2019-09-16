<script>

// function displayActivityLogPanel(activityId){
//     //document.getElementById("new_activity_log_div_"+activityId).style.display="block";
//     $("#new_activity_log_div_"+activityId).slideDown();
// }

// function closeActivityLogPanel(activityId){
//     //document.getElementById("new_activity_log_div_"+activityId).style.display="none";
//     $("#new_activity_log_div_"+activityId).slideUp();
// }

// function displayActivityLogList(activityId){
//     $("#list_activity_logs_"+activityId).slideToggle();
// }

// function validateActivityLog(activityId){
//     var result=false;
//     var dateField=document.getElementById("task_log_date_"+activityId);
//     var dateValue=dateField.value;
//     if(validateDateField(dateValue)){
//         result=true;
//     }else{
//         dateField.style.backgroundColor="#ffcccc";
//         result=false;
//     }
//     if(result){
//         document.getElementById("activity_form_"+activityId).submit();
//     }
// }


//     function filterActivitiesByUser() {
//         document.select_human_resource_filter_form.submit();
//     }
    
//  /**
//  * This function verifies if the value inputted in a date field is numericaly correct.
//  * Empty values are consideted false.
//  * This function return a boolean value.
//  */
// function validateDateField(value){
//     var result=false;
//     if(value!=""){
//         var dateParts=value.split("/");
//         if(dateParts.length==3){
//             var day=dateParts[0];
//             var month=dateParts[1];
//             var year=dateParts[2];
//             if (!isNaN(parseInt(day)) && !isNaN(parseInt(month)) && !isNaN(parseInt(year)) ){
//                 result=true;
//             }
//         }
//     }
//     return result;
// }

//    function expandControlWorkpackageActivities(id) {
//         if (document.getElementById("collapse_icon_" + id).style.display == "none") {
//             expandActivities(id);
//         } else {
//             collapseActivities(id);
//         }
//     }
    
//       function expandActivities(id) {
//         var table = document.getElementById("tb_eap");
//         for (var i = 0; i < table.rows.length; i++) {
//             row = table.rows[i];
//             if (row.id.indexOf("wbs_id_" + id) != -1) {
//                 row.style.display = "none";
//             }
//         }
//         document.getElementById("collapse_icon_" + id).style.display = "inline";
//         document.getElementById("expand_icon_" + id).style.display = "none";
//     }

//     function collapseActivities(id) {
//         var table = document.getElementById("tb_eap");
//         for (var i = 0; i < table.rows.length; i++) {
//             row = table.rows[i];
//             if (row.id.indexOf("wbs_id_" + id) != -1 && row.id.indexOf("activity_details_id_") == -1) {
//                 row.style.display = "table-row";
//             }
//         }
//         document.getElementById("collapse_icon_" + id).style.display = "none";
//         document.getElementById("expand_icon_" + id).style.display = "inline";
//     }

</script>

<?php
require_once (DP_BASE_DIR . "/modules/timeplanning/model/project_task_estimation.class.php");
require_once (DP_BASE_DIR . "/modules/dotproject_plus/model/ActivityLog.php");
require_once (DP_BASE_DIR . "/modules/timeplanning/model/wbs_item_estimation.class.php");
require_once (DP_BASE_DIR . "/modules/timeplanning/control/controller_wbs_items.class.php");
require_once (DP_BASE_DIR . "/modules/timeplanning/control/controller_wbs_item_activity_relationship.php");
require_once (DP_BASE_DIR . "/modules/timeplanning/control/controller_company_role.class.php");
require_once (DP_BASE_DIR . "/modules/tasks/tasks.class.php");
require_once (DP_BASE_DIR . "/modules/projects/projects.class.php");

$projectId = dPgetParam($_GET, 'project_id', 0);
$activitiesIdsForDisplay; //updated by /modules/timeplanning/view/export_project_plan/time_planning_initializing_logical_ids.php
require_once (DP_BASE_DIR . "/modules/timeplanning/view/export_project_plan/time_planning_initializing_logical_ids.php");



if ($_GET["show_external_page"] != "") {
    include_once DP_BASE_DIR . $_GET["show_external_page"];
} else {
    $project_id = dPgetParam($_GET, "project_id", 0);
    $project = new CProject();
    $project->load($project_id);
    global $pstatus;
    $controllerWBSItem = new ControllerWBSItem();
    $ControllerWBSItemActivityRelationship = new ControllerWBSItemActivityRelationship();
    $controllerCompanyRole = new ControllerCompanyRole();
    $items = $controllerWBSItem->getWorkPackages($project_id);
//start: build the roles list

    $roles = $controllerCompanyRole->getCompanyRoles($project->project_company);
    $i = 0;
    foreach ($roles as $role) {
        $roles[$role->getId()] = $role->getDescription();
        //start: build human resources list per role
        $q = new DBQuery();
        $q->addTable('contacts', 'c');
        $q->addQuery('user_id, h.human_resource_id, contact_id,u.user_username');
        $q->innerJoin('users', 'u', 'u.user_contact = c.contact_id');
        $q->innerJoin('human_resource', 'h', 'h.human_resource_user_id = u.user_id');
        $q->innerJoin('human_resource_roles', 'hr_roles', 'hr_roles.human_resource_id =h.human_resource_id and hr_roles.human_resources_role_id=' . $role->getId());
        $q->addWhere('c.contact_company = ' . $project->project_company);
        $q->addOrder("u.user_username");
        $sql = $q->prepare();
        $records = db_loadList($sql);
        $j = 0;
        foreach ($records as $record) {
            $userNameByHRid[$record[1]] = $record[3];
            $j++;
        }
        //end: build human resources list per role
        $i++;
    }
//end: build the roles list
//start: build hr list
    $q = new DBQuery();
    $q->addTable('contacts', 'c');
    $q->addQuery('user_id, human_resource_id, contact_id,u.user_username');
    $q->innerJoin('users', 'u', 'u.user_contact = c.contact_id');
    $q->innerJoin('human_resource', 'h', 'h.human_resource_user_id = u.user_id');
    $q->addWhere('c.contact_company = ' . $project->project_company);
    $q->addOrder("u.user_username");
    $sql = $q->prepare();
    $records = db_loadList($sql);
    $i = 0;
    $userNameByHRid = array();
    foreach ($records as $record) {
        $userNameByHRid[$record[1]] = $record[3];
        $i++;
    }
//end: build hr list
//start: buld hr list per role
//end: buld hr list per role 
    ?>
<?php 
        $project = new CProject();
        $project->load($project_id);
        $company_id = $project->project_company;
        $showFirstActivityCreation = false; //this variable make the controlling to showing of the message to create the first activity 
        $items = $controllerWBSItem->getWBSItems($project_id);

        if (count($items) == 0) {
    ?>
            <div class="alert alert-secondary text-center" role="alert">
                <?php echo $AppUI->_("LBL_CLICK") ?>
                <a href="#" onclick="wbs.new(<?=$project_id?>)">
                    <b><u><?php echo $AppUI->_("LBL_HERE") ?></u></b>
                </a>
                <?php echo $AppUI->_("LBL_CREATE_NEW_WBS_ITEM") ?>
            </div>
    <?php
        // WBS TREE DECLARATION
        } else {

            function createTree($i, $activitiesIdsForDisplay, $userNameByHRid, $roles, $project_id) {
                $tree = array();
                foreach($i as $node) {
                    createIndex($node->getNumber(), $tree, $node);
                }
                $dom = new DOMDocument('1.0');
                $mainCard = $dom->createElement('div');
                $dom->appendChild(createHtml($tree, $mainCard, $dom, $activitiesIdsForDisplay, $userNameByHRid, $roles, $project_id));
                echo $dom->saveHTML();
            }
             
             
            function createIndex($number, &$tree, $obj) {
                $index = strpos($number, '.');
                if (!$index) {
                    $tree[$number] = $obj->toArray();
                    $tree[$number]['parent'] = substr($obj->getNumber(), 0, strlen($obj->getNumber())-2);
                    return;
                }
                $newNumber = substr($number, $index+1);
                if (!($newNumber == '' || $newNumber == null)) {
                    return createIndex($newNumber, $tree[substr($number, 0, $index)], $obj);
                }
            }
            
            function createHtml($tree, $mainCard, $dom, $activitiesIdsForDisplay, $userNameByHRid, $roles, $project_id) {
                foreach($tree as $branch) {
                    if (is_array($branch)) {
                        $isLeaf = (bool)$branch['isLeaf'];
                        $numberOfTasks = '';
                        if ($isLeaf) {
                            $eapItem = new WBSItemEstimation();
                            $id = $branch['id'];
                            $eapItem->load($id);
                            $ControllerWBSItemActivityRelationship = new ControllerWBSItemActivityRelationship();
                            $tasks = $ControllerWBSItemActivityRelationship->getActivitiesByWorkPackage($id);
                            $numberOfTasks = $isLeaf ? ' (' . sizeof($tasks) . ')' : '';
                        }
                        $innerCard = strlen($branch['number']) > 1 ? ' inner-card' : '';
                        $card = $dom->createElement('div');
                        $cardClass = $dom->createAttribute('class');
                        $cardClass->value = 'card' . $innerCard;
                        $card->appendChild($cardClass);

                        $cardBody = $dom->createElement('div');
                        $cardBodyId = $dom->createAttribute('id');
                        $cardBodyId->value = $branch['number'];
                        $cardBody->appendChild($cardBodyId);
                        $cardBodyClass = $dom->createAttribute('class');
                        $cardBodyClass->value = 'card-body';
                        $cardBody->appendChild($cardBodyClass);

                        $cardRow = $dom->createElement('div');
                        $cardRowClass = $dom->createAttribute('class');
                        $cardRowClass->value = 'row';
                        $cardRow->appendChild($cardRowClass);

                        $carCol8 = $dom->createElement('div');
                        $carCol8Class = $dom->createAttribute('class');
                        $carCol8Class->value = 'col-md-8';
                        $carCol8->appendChild($carCol8Class);
                        $h5 = $dom->createElement('h5', $branch['number'] . ' ' . $branch['name'] . $numberOfTasks);
                        $carCol8->appendChild($h5);
                        
                        $carCol4 = $dom->createElement('div');
                        $carCol4Class = $dom->createAttribute('class');
                        $carCol4Class->value = 'col-md-4 text-right';
                        $carCol4->appendChild($carCol4Class);

                        $cardRow->appendChild($carCol8);
                        $cardRow->appendChild($carCol4);
                        $cardBody->appendChild($cardRow);

                        if ($isLeaf) {

                            foreach($tasks as $task) {
                                $task_id = $task->task_id;
                                $taskDescription = $task->task_name;
                                $projectTaskEstimation = new ProjectTaskEstimation();
                                $projectTaskEstimation->load($task_id);

                                //duration and start/end dates.
                                $obj = new CTask();
                                $obj->load($task_id);

                                // Start and end dates
                                $startDateTxt = "Não informado";
                                $endDateTxt = "Não informado";
                                if (isset($obj->task_start_date) && isset($obj->task_end_date)) {
                                    $startDateTxt = date("d/m/Y", strtotime($obj->task_start_date));
                                    $endDateTxt = date("d/m/Y", strtotime($obj->task_end_date));
                                }
                                $duration = "";
                                if ($projectTaskEstimation->getDuration() != "") {
                                    $duration = "" . $projectTaskEstimation->getDuration() . " dia(s)";
                                }   
                                
                                //actual dates
                                $activityLog=new ActivityLog();
                                $actualDates=$activityLog->getActivitiesActualDates($task_id);
                                $actualDuration=$activityLog->getActivityActualDuration();
                                $startDateActualTxt = "";
                                $endDateActualTxt = "";
                                if(sizeof($actualDates)==2) {
                                   if($actualDates[0]!=""){
                                        $startDateActualTxt  = date("d/m/Y", strtotime($actualDates[0]));
                                   }
                                   if($actualDates[1]!=""){
                                       $endDateActualTxt = date("d/m/Y", strtotime($actualDates[1]));
                                   }
                                   
                                }

                                // Start creating html structure
                                $taskCard = $dom->createElement('div');
                                $taskCardClass = $dom->createAttribute('class');
                                $taskCardClass->value = 'card inner-card';
                                $taskCard->appendChild($taskCardClass);
                                $taskCardData = $dom->createAttribute('data');
                                $taskCardData->value = $task_id;
                                $taskCard->appendChild($taskCardData);

                                $taskCardBody = $dom->createElement('div');
                                $taskCardBodyId = $dom->createAttribute('id');
                                $taskCardBodyId->value = $branch['number'];
                                $taskCardBody->appendChild($taskCardBodyId);
                                $taskCardBodyClass = $dom->createAttribute('class');
                                $taskCardBodyClass->value = 'card-body shrink';
                                $taskCardBody->appendChild($taskCardBodyClass);

                                $taskCardRow = $dom->createElement('div');
                                $taskCardRowClass = $dom->createAttribute('class');
                                $taskCardRowClass->value = 'row';
                                $taskCardRow->appendChild($taskCardRowClass);

                                $carCol8 = $dom->createElement('div');
                                $carCol8Class = $dom->createAttribute('class');
                                $carCol8Class->value = 'col-md-10';
                                $carCol8->appendChild($carCol8Class);
                                
                                switch ($obj->task_percent_complete) {
                                    case 0:
                                        $activityStatus = $dom->createElement('span', 'Iniciada');
                                        $activityStatusClass = $dom->createAttribute('class');
                                        $activityStatusClass->value = 'badge badge-primary';
                                        $activityStatus->appendChild($activityStatusClass);
                                        break;
                                    case 100:
                                        $activityStatus = $dom->createElement('span', 'Finalizada');
                                        $activityStatusClass = $dom->createAttribute('class');
                                        $activityStatusClass->value = 'badge badge-success';
                                        $activityStatus->appendChild($activityStatusClass);
                                        break;
                                    default:
                                        $activityStatus = $dom->createElement('span', 'Não iniciada');
                                        $activityStatusClass = $dom->createAttribute('class');
                                        $activityStatusClass->value = 'badge badge-info';
                                        $activityStatus->appendChild($activityStatusClass);
                                        break;
                                }
                                $h6 = $dom->createElement('h6', 'A.' . $activitiesIdsForDisplay[$task_id] . ' ' . $taskDescription . ' ');
                                $h6Class = $dom->createAttribute('class');
                                $h6Class->value = 'mouse-cursor-pointer';
                                $h6->appendChild($h6Class);
                                $h6Toggle = $dom->createAttribute('data-toggle');
                                $h6Toggle->value = 'collapse';
                                $h6->appendChild($h6Toggle);
                                $h6Target = $dom->createAttribute('data-target');
                                $h6Target->value = '#card_'.$task_id;
                                $h6->appendChild($h6Target);
                                $h6Style = $dom->createAttribute('style');
                                $h6Style->value = 'float: left;';
                                $h6->appendChild($h6Style);

                                $icon = $dom->createElement('i', '&nbsp;');
                                $iconClass = $dom->createAttribute('class');
                                $iconClass->value = 'fas fa-caret-down title';
                                $icon->appendChild($iconClass);
                                $carCol8->appendChild($h6);
                                $carCol8->appendChild($icon);

                                $carCol4 = $dom->createElement('div');
                                $carCol4Class = $dom->createAttribute('class');
                                $carCol4Class->value = 'col-md-2 text-right';
                                $carCol4->appendChild($carCol4Class);
                                $carCol4->appendChild($activityStatus);

                                // Dropdown activity options
                                $dropdown = $dom->createElement('div');
                                $dropdownClass = $dom->createAttribute('class');
                                $dropdownClass->value = 'dropdown';
                                $dropdown->appendChild($dropdownClass);
                                $dropdownStyle = $dom->createAttribute('style');
                                $dropdownStyle->value = 'width: 20%; float: right;';
                                $dropdown->appendChild($dropdownStyle);

                                // Dropdown link
                                $dropdownA = $dom->createElement('a');
                                $dropdownAClass = $dom->createAttribute('class');
                                $dropdownAClass->value = '';
                                $dropdownA->appendChild($dropdownAClass);
                                $dropdownAhref = $dom->createAttribute('href');
                                $dropdownAhref->value = 'javascript:void(0)';
                                $dropdownA->appendChild($dropdownAhref);
                                $dropdownAToggle = $dom->createAttribute('data-toggle');
                                $dropdownAToggle->value = 'dropdown';
                                $dropdownA->appendChild($dropdownAToggle);
                                $dropdownAId = $dom->createAttribute('id');
                                $dropdownAId->value = '#dropdown_' . $branch['id'];
                                $dropdownA->appendChild($dropdownAId);
                                $icon = $dom->createElement('i');
                                $iconClass = $dom->createAttribute('class');
                                $iconClass->value = 'fas fa-bars';
                                $icon->appendChild($iconClass);
                                $dropdownA->appendChild($icon);

                                $dropdown->appendChild($dropdownA);

                                $dropdownMenu = $dom->createElement('div');
                                $dropdownMenuClass = $dom->createAttribute('class');
                                $dropdownMenuClass->value = 'dropdown-menu dropdown-menu-right';
                                $dropdownMenu->appendChild($dropdownMenuClass);
                                $dropdownMenuAriaL = $dom->createAttribute('aria-labelledby');
                                $dropdownMenuAriaL->value = 'dropdown_'.$branch['id'];
                                $dropdownMenu->appendChild($dropdownMenuAriaL);

                                // Dropdown activity options item update activity
                                $dropdownItem = $dom->createElement('a');
                                $dropdownItemClass = $dom->createAttribute('class');
                                $dropdownItemClass->value = 'dropdown-item';
                                $dropdownItem->appendChild($dropdownItemClass);
                                $dropdownItemhref = $dom->createAttribute('href');
                                $dropdownItemhref->value = 'javascript:void(0)';
                                $dropdownItem->appendChild($dropdownItemhref);
                                $dropdownItemOC = $dom->createAttribute('onclick');
                                $dropdownItemOC->value = 'tasks.edit('.$branch['id'].','.$task_id.')';
                                $dropdownItem->appendChild($dropdownItemOC);
                                $icon = $dom->createElement('i');
                                $iconClass = $dom->createAttribute('class');
                                $iconClass->value = 'far fa-edit';
                                $icon->appendChild($iconClass);
                                $dropdownItem->appendChild($icon);
                                $dropdownItemSpan = $dom->createElement('span', ' Alterar Atividade');
                                $dropdownItem->appendChild($dropdownItemSpan);
                                $dropdownMenu->appendChild($dropdownItem);

                                $dropdown->appendChild($dropdownMenu);
                                $carCol4->appendChild($dropdown);
                                $taskCardRow->appendChild($carCol8);
                                $taskCardRow->appendChild($carCol4);
                                $taskCardBody->appendChild($taskCardRow);

                                $taskCardRow = $dom->createElement('div');
                                $taskCardRowClass = $dom->createAttribute('class');
                                $taskCardRowClass->value = 'row';
                                $taskCardRow->appendChild($taskCardRowClass);

                                $carCol6 = $dom->createElement('div');
                                $carCol6Class = $dom->createAttribute('class');
                                $carCol6Class->value = 'col-md-6';
                                $carCol6->appendChild($carCol6Class);

                                // Data início
                                $span = $dom->createElement('span', 'Início: ' . $startDateTxt);
                                $spanClass = $dom->createAttribute('class');
                                $spanClass->value = 'd-block';
                                $span->appendChild($spanClass);
                                $carCol6->appendChild($span);
                                // TODO actual

                                // <td style="text-align: center; vertical-align: top" nowrap="nowrap"> 
                                //                 <?php echo $AppUI->_("LBL_PLANNED");<br />
                                //                 <span id="activity_date_start_read_id_<?php echo $task_id">
                                //                     <?php echo $startDateTxt 
                                //                 </span>
                                //                 <br /><br />
                                //                 <?php echo $AppUI->_("LBL_ACTUAL") . "<br />"; 
                                //                 <?php echo $startDateActualTxt; ?
        

                                // Data fim
                                $span = $dom->createElement('span', 'Fim: ' . $endDateTxt);
                                $spanClass = $dom->createAttribute('class');
                                $spanClass->value = 'd-block';
                                $span->appendChild($spanClass);
                                $carCol6->appendChild($span);
                                // TODO actual
                                //             </td>                           
                                //             <td style="text-align: center;vertical-align: top" nowrap="nowrap">
                                //                 <?php echo $AppUI->_("LBL_PLANNED");<br />
                                //                 <span id="activity_date_end_read_id_<?php echo $task_id ">
                                //                     <?php echo $endDateTxt 
                                //                 </span>
                                //                 <br /><br />
                                //                     <?php 
                                //                 if($obj->task_percent_complete==100){
                                //                     echo $AppUI->_("LBL_ACTUAL");
                                //                     echo "<br />";
                                //                     echo $endDateActualTxt ;
                                //                 
                                //                  
                                //             </td>

                                // Duração
                                $span = $dom->createElement('span', 'Duração: ' . $duration);
                                $spanClass = $dom->createAttribute('class');
                                $spanClass->value = 'd-block';
                                $span->appendChild($spanClass);
                                $carCol6->appendChild($span);
                                $taskCardRow->appendChild($carCol6);
                                // TODO actual
                                //             <td style="text-align: center;width:100px; vertical-align: top">
                                //                 <?php echo $AppUI->_("LBL_PLANNED");><br />
                                //                 <?php echo $duration><br /><br />
                                //                 <?php 
                                //                 if($actualDuration!=""){
                                //                     echo $AppUI->_("LBL_ACTUAL")."<br />";
                                //                     echo $actualDuration . " " . $AppUI->_("LBL_PROJECT_DAYS_MULT");
                                //                 }
                                //                
                                //             </td>


                                $carCol6 = $dom->createElement('div');
                                $carCol6Class = $dom->createAttribute('class');
                                $carCol6Class->value = 'col-md-6';
                                $carCol6->appendChild($carCol6Class);
                                $taskCardRow->appendChild($carCol6);

                                // Recursos humanos
                                $estimatedRolesTxt = "";
                                $rolesNonGrouped = $projectTaskEstimation->getRolesNonGrouped($task_id);
                                $totalRoles = count($rolesNonGrouped);
                                $i = 1;
                                 if ($_POST["project_resources_filter"] == "") {
                                     $hasFilteredRH = true; //controls if will be some filter based on human resource
                                 } else {
                                     $hasFilteredRH = false;
                                 }
                                foreach ($rolesNonGrouped as $role) {
                                    $role_estimated_id = $role->getQuantity(); // the quantity field is been used to store the estimated role id
                                    $allocated_hr_id = ""; //Get the allocated HR  (maybe there is just the role without allocation, in this case write the role name)
                                    //Get id of a possible old allocation to delete it
                                    $q = new DBQuery();
                                    $q->addTable("human_resource_allocation");
                                    $q->addQuery("human_resource_id");
                                    $q->addWhere("project_tasks_estimated_roles_id=" . $role_estimated_id);
                                    $sql = $q->prepare();
                                    $records = db_loadList($sql);


                                    foreach ($records as $record) {
                                        $allocated_hr_id = $record[0];
                                    }

                                    if ($allocated_hr_id != "") {
                                        $estimatedRolesTxt.=$userNameByHRid[$allocated_hr_id];
                                    } else {
                                        $estimatedRolesTxt.= $roles[$role->getRoleId()];
                                    }
                                    if ($totalRoles > $i) {
                                        $estimatedRolesTxt.=", ";
                                    }
                                    $i++;
                                    // TODO Ver filtro posteriormente
                                     if (!$hasFilteredRH && $_POST["project_resources_filter"] == $allocated_hr_id) {
                                         $hasFilteredRH = true;
                                     }
                                }
                                $span = $dom->createElement('span', 'Recursos humanos: ' . $estimatedRolesTxt);
                                $spanClass = $dom->createAttribute('class');
                                $spanClass->value = 'd-block';
                                $span->appendChild($spanClass);
                                $carCol6->appendChild($span);

                                $divCollapse = $dom->createElement('div');
                                $divCollapseClass = $dom->createAttribute('class');
                                $divCollapseClass->value = 'collapse';
                                $divCollapse->appendChild($divCollapseClass);
                                $divCollapseId = $dom->createAttribute('id');
                                $divCollapseId->value = 'card_'.$task_id;
                                $divCollapse->appendChild($divCollapseId);

                                $divCollapse->appendChild($taskCardRow);
                                $taskCardBody->appendChild($divCollapse);
                                $taskCard->appendChild($taskCardBody);
                                $cardBody->appendChild($taskCard);
                            }
                        }
                        $card->appendChild($cardBody);
                        $mainCard->appendChild($card);
                        createHtml($branch, $cardBody, $dom, $activitiesIdsForDisplay, $userNameByHRid, $roles, $project_id);
                    }
                }
                return $mainCard;
            }
            createTree($items, $activitiesIdsForDisplay, $userNameByHRid, $roles, $project_id);
        }
        $projectTaskEstimation = new ProjectTaskEstimation();
    }
?>

 <!-- <form name="activity_form_<?php echo $obj->task_id ?>" id="activity_form_<?php echo $obj->task_id ?>" method="post" action="?m=dotproject_plus">
                                                        <input name="dosql" type="hidden" value="do_new_activity_log" />
                                                        <input type="hidden" name="project_id" value="<?php echo $project_id; ?>" />
                                                        <input type="hidden" name="activity_id" value="<?php echo $obj->task_id ?>" />
                                                        <input type="hidden" name="task_log_creator" value="<?php echo $AppUI->user_id ?>" />
                                                        <input type="hidden" name="tab" value="<?php echo $_GET["tab"] ?>" />
                                                    
                                                    <div align="center"><b><?php echo $AppUI->_("LBL_NEW_ACTVITY_LOG"); ?></b><br /><br /></div>
                                                    <table >
                                                                                     <tr>
                                                                                        <td style="text-align: center" nowrap="nowrap">
                                                                                        <?php $dateFieldId="task_log_date_" .$task_id ?>
                                                                                            <input type="text" class="text" name="<?php echo $dateFieldId; ?>" id="<?php echo $dateFieldId; ?>" placeholder="dd/mm/yyyy" size="12" maxlength="10" value=""  />                                                                
                                                                                        </td>                           
                                                                                        <td style="text-align: center" nowrap="nowrap"> 
                                                                                            <select name="task_log_hours">
                                                                                                <option value="0.5">0:30</option>
                                                                                                <option value="1">1:00</option>
                                                                                                <option value="1.5">1:30</option>
                                                                                                <option value="2">2:00</option>
                                                                                                <option value="2.5">2:30</option>
                                                                                                <option value="3">3:00</option>
                                                                                                <option value="3.5">3:30</option>
                                                                                                <option value="4">4:00</option>
                                                                                            </select>

                                                                                        </td>
                                                                                    </tr>

                                                                                    <tr>
                                                                                        <td colspan="2">
                                                                                            <textarea style="width: 100%;height:60px;resize: none;" name="task_log_description"></textarea>
                                                                                        </td>    
                                                                                    </tr>
                                                                                    <tr>

                                                                                        <td colspan="2">
                                                                                            <input type="checkbox" value="1" name="activity_concluded" /> <?php echo $AppUI->_("LBL_ACTIVITY_CONCLUDED") ?>
                                                                                        </td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td colspan="2" style="text-align: right">
                                                                                            <br />
                                                                                            <input class="button" type="button" value="<?php echo $AppUI->_("LBL_SAVE"); ?>" onclick="validateActivityLog(<?php echo $task_id ?>);" />
                                                                                            <input class="button" type="button" value="<?php echo ucfirst($AppUI->_("LBL_CANCEL")); ?>" onclick="closeActivityLogPanel(<?php echo $task_id ?>);"  />
                                                                                        </td>
                                                                                    </tr>
                                                                    </table>
                                                    </form> -->
