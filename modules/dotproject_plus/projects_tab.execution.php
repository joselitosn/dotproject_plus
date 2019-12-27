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
                <a href="?m=projects&a=view&project_id=31&tab=1&subtab=0">
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
                                } else {
                                    $duration = ' - ';
                                }

                                
                                //actual dates
                                $activityLog=new ActivityLog();
                                $actualDates=$activityLog->getActivitiesActualDates($task_id);
                                $actualDuration=$activityLog->getActivityActualDuration();
                                if ($actualDuration != "") {
                                    $actualDuration = $actualDuration . ' dia(s)';
                                } else {
                                    $actualDuration = ' - ';
                                }

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
                                        $activityStatus = $dom->createElement('span', 'Não Iniciada');
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
                                        $activityStatus = $dom->createElement('span', 'Iniciada');
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
                                $dropdownItemOC->value = 'taskLog.add('.$task_id.')';
                                $dropdownItem->appendChild($dropdownItemOC);
                                $icon = $dom->createElement('i');
                                $iconClass = $dom->createAttribute('class');
                                $iconClass->value = 'far fa-edit';
                                $icon->appendChild($iconClass);
                                $dropdownItem->appendChild($icon);
                                $dropdownItemSpan = $dom->createElement('span', ' Novo registro de trabalho');
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
                                $span = $dom->createElement('span', 'Início planejado: ' . $startDateTxt . ' | Início real: ' . $startDateActualTxt);
                                $spanClass = $dom->createAttribute('class');
                                $spanClass->value = 'd-block';
                                $span->appendChild($spanClass);
                                $carCol6->appendChild($span);

                                // Data fim
                                $span = $dom->createElement('span', 'Fim planejado: ' . $endDateTxt . ' | Fim real: ' . $endDateActualTxt);
                                $spanClass = $dom->createAttribute('class');
                                $spanClass->value = 'd-block';
                                $span->appendChild($spanClass);
                                $carCol6->appendChild($span);
                                $taskCardRow->appendChild($carCol6);
                                
                                $carCol6 = $dom->createElement('div');
                                $carCol6Class = $dom->createAttribute('class');
                                $carCol6Class->value = 'col-md-6';
                                $carCol6->appendChild($carCol6Class);
                                $taskCardRow->appendChild($carCol6);
                                
                                // Duração
                                $span = $dom->createElement('span', 'Duração planejada: ' . $duration . ' | Duração real: ' . $actualDuration);
                                $spanClass = $dom->createAttribute('class');
                                $spanClass->value = 'd-block';
                                $span->appendChild($spanClass);
                                $carCol6->appendChild($span);

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

                                $taskCardRow = $dom->createElement('div');
                                $taskCardRowClass = $dom->createAttribute('class');
                                $taskCardRowClass->value = 'row';
                                $taskCardRow->appendChild($taskCardRowClass);

                                $carCol12 = $dom->createElement('div');
                                $carCol12Class = $dom->createAttribute('class');
                                $carCol12Class->value = 'col-md-12';
                                $carCol12->appendChild($carCol12Class);
                                $carCol12Id= $dom->createAttribute('id');
                                $carCol12Id->value = 'logContainer_'.$task_id;
                                $carCol12->appendChild($carCol12Id);
                                $taskCardRow->appendChild($carCol12);
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


<div class="modal" tabindex="-1" role="dialog" id="modalWorkRecords">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?=$AppUI->_('LBL_ORGANIZATIONAL_POLICY')?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form name="taskLogForm">
                    <input name="dosql" type="hidden" value="do_new_activity_log" />
                    <input type="hidden" name="task_log_creator" value="<?=$AppUI->user_id?>" />
                    <input type="hidden" name="project_id" value="<?=$project_id?>" />
                    <input type="hidden" name="activity_id" value="" />

                    <div class="form-group">
                        <span class="required"></span>
                        <?=$AppUI->_('requiredField');?>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="task_log_date" class="required">
                                    <?php echo $AppUI->_("LBL_DATE"); ?>
                                </label>
                                <input type="text" class="form-control form-control-sm datepicker" name="task_log_date" />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="task_log_hours" class="required">
                                    <?php echo $AppUI->_("LBL_TIME"); ?>
                                </label>
                                <select name="task_log_hours" class="form-control form-control-sm select">
                                    <option value="0.5">0:30</option>
                                    <option value="1">1:00</option>
                                    <option value="1.5">1:30</option>
                                    <option value="2">2:00</option>
                                    <option value="2.5">2:30</option>
                                    <option value="3">3:00</option>
                                    <option value="3.5">3:30</option>
                                    <option value="4">4:00</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="task_log_description">
                            <?php echo $AppUI->_("LBL_DESCRIPTION"); ?>
                        </label>
                        <textarea name="task_log_description" rows="4" class="form-control form-control-sm"></textarea>
                    </div>
                    <div class="form-group">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="1" name="activity_concluded" id="activity_concluded">
                            <label class="form-check-label" for="activity_concluded">
                            <?php echo $AppUI->_("LBL_ACTIVITY_CONCLUDED"); ?>
                            </label>
                        </div>
                    </div>

                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal"><?=$AppUI->_('LBL_CLOSE')?></button>
                <button type="button" class="btn btn-primary btn-sm" onclick="taskLog.save()"><?=$AppUI->_('LBL_SAVE')?></button>
            </div>
        </div>
    </div>
</div>

<script>

    var taskLog = {

        loadedLogs: [],

        init: function() {
            $('#modalWorkRecords').on('hidden.bs.modal', function() {
                $('form[name=taskLogForm]').trigger('reset');
                $('.select').trigger('change');
            });

            $(".select").select2({
                allowClear: false,
                theme: "bootstrap",
                dropdownParent: $("#modalWorkRecords")
            });

            $( ".datepicker" ).datepicker({
                dateFormat: 'dd/mm/yy'
            });

            $('.inner-card').find('h6').on('click', function(e) {
                var taskId;
                try {
                    taskId = $(e.target).attr('data-target').split('_')[1];
                } catch (err) {
                    taskId = $(e.target).parent().attr('data-target').split('_')[1];
                }
                if ($(this).next('i').hasClass('fa-caret-down')) {
                    $(this).next('i').removeClass('fa-caret-down');
                    $(this).next('i').addClass('fa-caret-up');
                    taskLog.loadLogs(taskId);
                } else {
                    $(this).next('i').removeClass('fa-caret-up');
                    $(this).next('i').addClass('fa-caret-down');
                }
            });
        },

        add: function(taskId) {
            var modal = $('#modalWorkRecords');
            $('input[name=activity_id]').val(taskId);
            modal.modal();
        },

        save: function() {
            var date = $("input[name=task_log_date]").val();
            var time = $("select[name=task_log_hours]").val();
            var taskId = $('input[name=activity_id]').val();

            if (!date || !time) {
                var msg = [];
                if (!date) msg.push("Informe a data");
                if (!time) msg.push("Informe o período");
                $.alert({
                    icon: "far fa-times-circle",
                    type: "red",
                    title: "<?=$AppUI->_('Error', UI_OUTPUT_JS); ?>",
                    content: msg.join("<br>")
                });
                return;
            }

            $.ajax({
                url: "?m=dotproject_plus",
                type: "post",
                datatype: "json",
                data: $("form[name=taskLogForm]").serialize(),
                success: function(resposta) {
                    $.alert({
                        icon: "far fa-check-circle",
                        type: "green",
                        title: "<?=$AppUI->_('Success', UI_OUTPUT_JS); ?>",
                        content: resposta,
                        onClose: function() {
                            taskLog.requestLogs(taskId);
                        }
                    });
                    $("#modalWorkRecords").modal("hide");
                },
                error: function(resposta) {
                    $.alert({
                        icon: "far fa-times-circle",
                        type: "red",
                        title: "<?=$AppUI->_('Error', UI_OUTPUT_JS); ?>",
                        content: "<?=$AppUI->_('Something went wrong.', UI_OUTPUT_JS); ?>"
                    });
                }
            });
        },

        loadLogs: function(taskId) {
            if (taskLog.loadedLogs.includes(taskId)) {
                return;
            }
            var element = $('#logContainer_'+taskId);
            element.loading({
                message: 'Carregando logs...'
            });
            taskLog.requestLogs(taskId);
        },

        requestLogs: function(taskId) {
            var element = $('#logContainer_'+taskId);
            element.load(
                '?m=tasks&template=vw_log_short&task_id='+taskId,
                function() {
                    if (!taskLog.loadedLogs.includes(taskId)) {
                        taskLog.loadedLogs.push(taskId)
                    }
                    element.loading('stop');
                }
            );
        },

        delete: function(id, taskId) {
            $.ajax({
                url: "?m=dotproject_plus",
                type: "post",
                data: {
                    task_log_id: id,
                    dosql: 'do_delete_activity_log'
                },
                success: function(resposta) {
                    $('#taskLogTableRow_'+id).remove();
                    var totalLinesLeft = $('#tableLogTask_'+taskId).children();
                    if (totalLinesLeft.length == 0) {
                        $('#taskLogContainer_'+taskId).remove();
                        var index = taskLog.loadedLogs.findIndex(function(entry){
                            return entry === taskId;
                        });
                        taskLog.loadedLogs.splice(index, 1);
                    }
                    $.alert({
                        icon: "far fa-check-circle",
                        type: "green",
                        title: "<?=$AppUI->_('Success', UI_OUTPUT_JS); ?>",
                        content: resposta
                    });
                },
                error: function(resposta) {
                    $.alert({
                        icon: "far fa-times-circle",
                        type: "red",
                        title: "<?=$AppUI->_('Error', UI_OUTPUT_JS); ?>",
                        content: "<?=$AppUI->_('Something went wrong.', UI_OUTPUT_JS); ?>"
                    });
                }
            });
        }
    };

    $(document).ready(taskLog.init);

</script>
