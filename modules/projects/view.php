<?php

    // Constants definition

    // Initiation
    const OPENING_TERM = 0;
    const STAKEHOLDER = 1;

    // Planning and monitoring
    const TAB_TASKS = 0;
    const TAB_SCHEDULE = 1;
    const TAB_COSTS = 2;
    const TAB_RISKS = 3;
    const TAB_QUALITY = 4;
    const TAB_COMUNICATION = 5;
    const TAB_AQUISITIONS = 6;
    const TAB_STAKEHOLDERS = 7;
    const TAB_PROJECT_PLANING = 8;

/* PROJECTS $Id: view.php 6048 2010-10-06 10:01:39Z ajdonnison $ */
if (!defined('DP_BASE_DIR')) {
    die('You should not access this file directly.');
}

GLOBAL $project_id;

$project_id = intval(dPgetParam($_GET, 'project_id', 0));
$q = new DBQuery;

//check permissions for this record
$canAccess = getPermission($m, 'access', $project_id);
$canRead = getPermission($m, 'view', $project_id);
$canEdit = getPermission($m, 'edit', $project_id);

$canAuthorTask = getPermission('tasks', 'add');

//Check if the proect is viewable.
if (!($canRead)) {
    $AppUI->redirect('m=public&a=access_denied');
}

//retrieve any state parameters
if (isset($_GET['tab'])) {
    $AppUI->setState('ProjVwTab', $_GET['tab']);
}
$tab = $AppUI->getState('ProjVwTab') !== NULL ? $AppUI->getState('ProjVwTab') : 0;

//check if this record has dependencies to prevent deletion
$msg = '';
$obj = new CProject();
$canDelete = $obj->canDelete($msg, $project_id);

//get critical tasks (criteria: task_end_date)
$criticalTasks = ($project_id > 0) ? $obj->getCriticalTasks($project_id) : NULL;

//get ProjectPriority from sysvals
$projectPriority = dPgetSysVal('ProjectPriority');
$projectPriorityColor = dPgetSysVal('ProjectPriorityColor');

$working_hours = ($dPconfig['daily_working_hours'] ? $dPconfig['daily_working_hours'] : 8);

//check that project has tasks; otherwise run seperate query
$q->addTable('tasks', 'tsk');
$q->addQuery("COUNT(distinct tsk.task_id) AS total_tasks");
$q->addWhere('task_project = ' . $project_id);
$hasTasks = $q->loadResult();
$q->clear();

//load the record data
//GJB: Note that we have to special case duration type 24 
//and this refers to the hours in a day, NOT 24 hours
$q->addTable('projects', 'p');
$q->addJoin('companies', 'com', 'com.company_id = project_company');
$q->addJoin('companies', 'com_internal', 'com_internal.company_id = project_company_internal');
$q->addJoin('users', 'u', 'user_id = project_owner');
$q->addJoin('contacts', 'con', 'contact_id = user_contact');
if ($hasTasks) {
    $q->addJoin('tasks', 't1', 'p.project_id = t1.task_project');
    $q->addQuery('com.company_name AS company_name, com_internal.company_name'
            . ' AS company_name_internal'
            . ", CONCAT_WS(', ',contact_last_name,contact_first_name) user_name"
            . ', p.*, SUM(t1.task_duration * t1.task_percent_complete'
            . " * IF(t1.task_duration_type = 24, {$working_hours}, t1.task_duration_type))"
            . " / SUM(t1.task_duration * IF(t1.task_duration_type = 24, {$working_hours},"
            . ' t1.task_duration_type)) AS project_percent_complete');
    $q->addWhere('t1.task_id = t1.task_parent');
} else {
    $q->addQuery('com.company_name AS company_name, com_internal.company_name'
            . ' AS company_name_internal'
            . ", CONCAT_WS(' ',contact_first_name,contact_last_name) user_name, p.*, "
            . '(0.0) AS project_percent_complete');
}
$q->addWhere('project_id = ' . $project_id);
$q->addGroup('project_id');
$sql = $q->prepare();
$q->clear();

$obj = null;
if (!db_loadObject($sql, $obj)) {
    $AppUI->setMsg('Project');
    $AppUI->setMsg('invalidID', UI_MSG_ERROR, true);
    $AppUI->redirect();
} else {
    $AppUI->savePlace();
}


//worked hours
//now milestones are summed up, too, for consistence with the tasks duration sum
//the sums have to be rounded to prevent the sum form having many (unwanted) decimals because of the mysql floating point issue
//more info on http://www.mysql.com/doc/en/Problems_with_float.html
if ($hasTasks) {
    $q->addTable('task_log');
    $q->addTable('tasks');
    $q->addQuery('ROUND(SUM(task_log_hours),2)');
    $q->addWhere('task_log_task = task_id AND task_project = ' . $project_id);
    $sql = $q->prepare();
    $q->clear();
    $worked_hours = db_loadResult($sql);
    $worked_hours = rtrim($worked_hours, '.');

    //total hours
    //same milestone comment as above, also applies to dynamic tasks
    $q->addTable('tasks');
    $q->addQuery('ROUND(SUM(task_duration),2)');
    $q->addWhere('task_duration_type = 24 AND task_dynamic != 1 AND task_project = ' . $project_id);
    $sql = $q->prepare();
    $q->clear();
    $days = db_loadResult($sql);

    $q->addTable('tasks');
    $q->addQuery('ROUND(SUM(task_duration),2)');
    $q->addWhere('task_duration_type = 1 AND task_dynamic != 1 AND task_project = ' . $project_id);
    $sql = $q->prepare();
    $q->clear();
    $hours = db_loadResult($sql);
    $total_hours = $days * $dPconfig['daily_working_hours'] + $hours;

    $total_project_hours = 0;

    $q->addTable('tasks', 't');
    $q->addQuery('ROUND(SUM(t.task_duration*u.perc_assignment/100),2)');
    $q->addJoin('user_tasks', 'u', 't.task_id = u.task_id');
    $q->addWhere('t.task_duration_type = 24 AND t.task_dynamic != 1 AND t.task_project = '
            . $project_id);
    $total_project_days_sql = $q->prepare();
    $q->clear();

    $q->addTable('tasks', 't');
    $q->addQuery('ROUND(SUM(t.task_duration*u.perc_assignment/100),2)');
    $q->addJoin('user_tasks', 'u', 't.task_id = u.task_id');
    $q->addWhere('t.task_duration_type = 1 AND t.task_dynamic != 1 AND t.task_project = '
            . $project_id);
    $total_project_hours_sql = $q->prepare();
    $q->clear();

    $total_project_hours = (db_loadResult($total_project_days_sql) * $dPconfig['daily_working_hours'] + db_loadResult($total_project_hours_sql));
    //due to the round above, we don't want to print decimals unless they really exist
    //$total_project_hours = rtrim($total_project_hours, '0');
} else { //no tasks in project so "fake" project data
    $worked_hours = $total_hours = $total_project_hours = 0.00;
}
//get the prefered date format
$df = $AppUI->getPref('SHDATEFORMAT');
//create Date objects from the datetime fields
$start_date = (intval($obj->project_start_date) ? new CDate($obj->project_start_date) : null);
$end_date = (intval($obj->project_end_date) ? new CDate($obj->project_end_date) : null);
$actual_end_date = (intval($criticalTasks[0]['task_end_date']) ? new CDate($criticalTasks[0]['task_end_date']) : null);
$style = ((($actual_end_date > $end_date) && !empty($end_date)) ? 'style="color:red; font-weight:bold"' : '');
?>

<div class="container-fluid">
    <div class="row header-2">
        <div class="col-md-12">
            <h4><?=$obj->project_name?></h4>
            <small>
                <?=$AppUI->_('Start Date')?>:
                <?=$start_date ? $start_date->format($df) : '-'?>
                |
                <?=$AppUI->_('Target End Date')?>:
                <?=$end_date ? $end_date->format($df) : '-'?>
                |
                <?=$AppUI->_('Company')?>:
                <?='<a href="?m=companies&amp;a=view&amp;company_id=' . $obj->project_company . '">' . htmlspecialchars($obj->company_name, ENT_QUOTES) . "</a>"?>
                |
                <?=$AppUI->_('Status')?>:
                <?=$AppUI->_($pstatus[$obj->project_status])?>
                |
                <?=$AppUI->_('Priority')?>:
                <?=$AppUI->_($projectPriority[$obj->project_priority])?>
                |
                <?=$AppUI->_("LBL_OWNER")?>:
                <?=$obj->user_name?>
                |
                <?=$AppUI->_('Scheduled Hours')?>:
                <?=$total_hours?>
                |
                <?=$AppUI->_('Target Budget')?>(<?php echo $dPconfig['currency_symbol']?>):
                <?=number_format(@$obj->project_target_budget, 2, ',', '.')?>
            </small>
        </div>
    </div>
</div>
<div class="wrapper">
    <!-- start main section - left project menu and EAP and tasks tree -->
    <!-- Sidebar -->
    <nav id="sidebar" style="margin-top:70px;">
        <div class="sidebar-header">
            <h5 class="text-right">
                <i id="sidebarCollapse" class="mouse-cursor-pointer fas fa-angle-double-left" style="margin-top: 20px;"></i>
            </h5>
        </div>
        <?php

            if (!isset($_GET["subtab"])) {
                $subtab = 0;
            } else {
                $subtab = $_GET["subtab"];
            }
        ?>

        <ul class="list-unstyled components">
            <li class="<?=$tab == 0 ? 'active' : '' ?>">
                <a href="#iniciacaoSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                    <?=$AppUI->_("1initiation", UI_OUTPUT_HTML);?>
                </a>
                <ul class="collapse list-unstyled<?=$tab == 0 ? ' show' : ' hide'?>" id="iniciacaoSubmenu">
                    <li class="<?=$subtab == 0 ? 'active' : '' ?>">
                        <a href="?m=projects&amp;a=view&amp;project_id=<?=$project_id?>&amp;tab=0&amp;subtab=0">
                            <?=$AppUI->_("LBL_OPEN_PROJECT_CHARTER",UI_OUTPUT_HTML)?>
                        </a>
                    </li>
                    <li class="<?=$subtab == 1 ? 'active' : '' ?>">
                        <a href="?m=projects&amp;a=view&amp;project_id=<?=$project_id?>&amp;tab=0&amp;subtab=1">
                            <?=$AppUI->_("LBL_PROJECT_STAKEHOLDER",UI_OUTPUT_HTML)?>
                        </a>
                    </li>
                </ul>
            </li>
            <li class="<?=$tab == 1 ? 'active' : '' ?>">
                <a href="#pmSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                    <?=$AppUI->_("Planning and monitoring", UI_OUTPUT_HTML);?>
                </a>
                <ul class="collapse list-unstyled<?=$tab == 1 ? ' show' : ' hide'?>" id="pmSubmenu">
                    <li class="<?=$tab == 1 && $subtab == 0 ? 'active' : '' ?>">
                        <a href="?m=projects&amp;a=view&amp;project_id=<?=$project_id?>&amp;tab=1&amp;subtab=0">
                            <?=$AppUI->_("LBL_WBS",UI_OUTPUT_HTML)?>
                        </a>
                    </li>
                    <li class="<?=$tab == 1 && $subtab == 1 ? 'active' : '' ?>">
                        <a href="?m=projects&amp;a=view&amp;project_id=<?=$project_id?>&amp;tab=1&amp;subtab=1">
                            <?=$AppUI->_("6LBLCRONOGRAMA",UI_OUTPUT_HTML)?>
                        </a>
                    </li>
                    <li class="<?=$tab == 1 && $subtab == 2 ? 'active' : '' ?>">
                        <a href="?m=projects&amp;a=view&amp;project_id=<?=$project_id?>&amp;tab=1&amp;subtab=2">
                            <?=$AppUI->_("5LBLCUSTO",UI_OUTPUT_HTML)?>
                        </a>
                    </li>
                    <li class="<?=$tab == 1 && $subtab == 3 ? 'active' : '' ?>">
                        <a href="?m=projects&amp;a=view&amp;project_id=<?=$project_id?>&amp;tab=1&amp;subtab=3">
                            <?=$AppUI->_("LBL_PROJECT_RISKS",UI_OUTPUT_HTML)?>
                        </a>
                    </li>
                    <li class="<?=$tab == 1 && $subtab == 4 ? 'active' : '' ?>">
                        <a href="?m=projects&amp;a=view&amp;project_id=<?=$project_id?>&amp;tab=1&amp;subtab=4">
                            <?=$AppUI->_("7LBLQUALIDADE",UI_OUTPUT_HTML)?>
                        </a>
                    </li>
                    <li class="<?=$tab == 1 && $subtab == 5 ? 'active' : '' ?>">
                        <a href="?m=projects&amp;a=view&amp;project_id=<?=$project_id?>&amp;tab=1&amp;subtab=5">
                            <?=$AppUI->_("LBL_PROJECT_COMMUNICATION",UI_OUTPUT_HTML)?>
                        </a>
                    </li>
                    <li class="<?=$tab == 1 && $subtab == 6 ? 'active' : '' ?>">
                        <a href="?m=projects&amp;a=view&amp;project_id=<?=$project_id?>&amp;tab=1&amp;subtab=6">
                            <?=$AppUI->_("LBL_PROJECT_ACQUISITIONS",UI_OUTPUT_HTML)?>
                        </a>
                    </li>
                    <li class="<?=$tab == 1 && $subtab == 7 ? 'active' : '' ?>">
                        <a href="?m=projects&amp;a=view&amp;project_id=<?=$project_id?>&amp;tab=1&amp;subtab=7">
                            <?=$AppUI->_("Stakeholder",UI_OUTPUT_HTML)?>
                        </a>
                    </li>
                    <li>
                        <a href="modules/timeplanning/view/export_project_plan/export.php?project_id=<?php echo $project_id; ?>&print=0" target = "_blank">
                            <?=$AppUI->_("LBL_PROJECT_PLAN",UI_OUTPUT_HTML)?>
                        </a>
                    </li>
                </ul>
            </li>
            <li class="<?=$tab == 2 ? 'active' : '' ?>">
                <a href="?m=projects&amp;a=view&amp;project_id=<?=$project_id?>&amp;tab=2">
                    <?=$AppUI->_("3execution", UI_OUTPUT_HTML);?>
                </a>
            </li>
            <li class="<?=$tab == 3 ? 'active' : '' ?>">
                <a href="?m=projects&amp;a=view&amp;project_id=<?=$project_id?>&amp;tab=3">
                    <?=$AppUI->_("5closing", UI_OUTPUT_HTML);?>
                </a>
            </li>
        </ul>
    </nav>

    <!-- Page Content -->
    <div id="content" style="margin-top:70px;">

        <fieldset>
            <?php
            if ($tab == 0) {
                switch ($subtab) {
                    case OPENING_TERM :
                        require_once DP_BASE_DIR . '/modules/initiating/addedit.php';
                        break;
                    case STAKEHOLDER :
                        require_once DP_BASE_DIR . '/modules/stakeholder/project_stakeholder.php';
                        break;
                }
            } else if ($tab == 1) {
                switch ($subtab) {
                    case TAB_TASKS :
                        require_once DP_BASE_DIR . '/modules/dotproject_plus/projects_tab.planning_and_monitoring.php';
                        break;
                    case TAB_SCHEDULE :
                        require_once DP_BASE_DIR . '/modules/monitoringandcontrol/view/view.6LBLCRONOGRAMA.php';
                        break;
                    case TAB_COSTS :
                        require_once DP_BASE_DIR . '/modules/costs/view_costs.php';
                        break;
                    case TAB_RISKS :
                        require_once DP_BASE_DIR . '/modules/risks/projects_risks.php';
                        break;
                    case TAB_QUALITY :
                        require_once DP_BASE_DIR . '/modules/timeplanning/view/quality/project_quality_planning.php';
                        break;
                    case TAB_COMUNICATION :
                        require_once DP_BASE_DIR . '/modules/communication/index_project.php';
                        break;
                    case TAB_AQUISITIONS :
                        require_once DP_BASE_DIR . '/modules/timeplanning/view/acquisition/acquisition_planning.php';
                        break;
                    case TAB_STAKEHOLDERS :
                        require_once DP_BASE_DIR . '/modules/stakeholder/project_stakeholder.php';
                        break;

                }
            }
            ?>
        </fieldset>
    </div>

    <script type="text/javascript">
        $(document).ready(function(e) {

            $('#sidebarCollapse').on('click', function () {
                $('#sidebar').toggleClass('active');

                if ($(this).hasClass('fa-angle-double-right')) {
                    $(this).removeClass('fa-angle-double-right');
                    $(this).addClass('fa-angle-double-left');
                } else {
                    $(this).removeClass('fa-angle-double-left');
                    $(this).addClass('fa-angle-double-right');
                }
            });
        });

    </script>