<?php
if (!defined("DP_BASE_DIR")) {
    die("You should not access this file directly.");
}

$risk_id = intval(dPgetParam($_GET, "id", 0));
$project_id = intval(dPgetParam($_GET, "project_id", 0));
$riskProbability = dPgetSysVal("RiskProbability");


require_once DP_BASE_DIR . "/modules/risks/probability_impact_matrix_read.php";

foreach ($riskProbability as $key => $value) {
    $riskProbability[$key] = str_replace("&amp;eacute;", "é", htmlspecialchars($AppUI->_($value)));
}
$riskStatus = dPgetSysVal("RiskStatus");
foreach ($riskStatus as $key => $value) {
    $riskStatus[$key] = str_replace("&amp;atilde;", "ã", htmlspecialchars($AppUI->_($value)));
}
$riskImpact = dPgetSysVal("RiskImpact");
foreach ($riskImpact as $key => $value) {
    $riskImpact[$key] = str_replace("&amp;eacute;", "é", htmlspecialchars($AppUI->_($value)));
}
$riskPotential = dPgetSysVal("RiskPotential");
foreach ($riskPotential as $key => $value) {
    $riskPotential[$key] = str_replace("&amp;atilde;", "ã", htmlspecialchars($AppUI->_($value)));
}
$riskActive = dPgetSysVal("RiskActive");
foreach ($riskActive as $key => $value) {
    $riskActive[$key] = str_replace("&amp;atilde;", "ã", htmlspecialchars($AppUI->_($value)));
}
$riskStrategy = dPgetSysVal("RiskStrategy");
foreach ($riskStrategy as $key => $value) {
    $riskStrategy[$key] = $AppUI->_($value);
}

// check permissions for this record
$canEdit = getPermission($m, "edit", $risk_id);
if (!(($canEdit && $risk_id) || ($canAuthor && !($risk_id)))) {
    $AppUI->redirect("m=public&a=access_denied");
}

$q = new DBQuery();
$q->addQuery("*");
$q->addTable("risks");
$q->addWhere("risk_id = " . $risk_id);

// check if this record has dependancies to prevent deletion
$msg = "";
$obj = new CRisks();
$canDelete = $obj->canDelete($msg, $risk_id);

// load the record data
$obj = null;
if (!db_loadObject($q->prepare(), $obj) && ($risk_id > 0)) {
    $AppUI->setMsg("LBL_RISKS");
    $AppUI->setMsg("invalidID", UI_MSG_ERROR, true);
    $AppUI->redirect();
}

// collect all the users for the company owner list
$project = new CProject();
$project->load($project_id);
$q = new DBQuery();
$q->addTable('users','u');
$q->addTable('contacts','con');
$q->addQuery('user_id');
$q->addQuery('CONCAT_WS(", ",contact_last_name,contact_first_name)');
$q->addOrder('contact_last_name');
$q->addWhere("u.user_contact = con.contact_id and con.contact_company=".$project->project_company); //contatos devem ser da empresa selecionada
$owners = $q->loadHashList();

$q->clear();
$q->addQuery("project_id, project_name");
$q->addTable("projects");
$q->addOrder("project_name");
$projects = $q->loadHashList();

$projectSelected = intval(dPgetParam($_GET, "project_id"));
$t = intval(dPgetParam($_GET, "tab"));
$vw = dPgetParam($_GET, "vw");
// setup the title block
$ttl = $risk_id ? "LBL_EDIT" : "LBL_ADD";
$titleBlock = new CTitleBlock($ttl, "risks.png", $m, "$m.$a");
if ($projectSelected == null || $projectSelected == "") {
    $titleBlock->addCrumb("?m=$m", "LBL_RISK_LIST");
    $href = "?m=$m";
    $projectSelected = $obj->risk_project;
} else {
        $titleBlock->addCrumb("?m=projects&a=view&project_id=" . $projectSelected . "&tab=" . $t."&targetScreenOnProject=/modules/risks/projects_risks.php", "LBL_RISK_LIST");
        $href = "?m=projects&a=view&project_id=" . $projectSelected . "&tab=" . $t."&targetScreenOnProject=/modules/risks/projects_risks.php";
}

//$canDelete = getPermission($m, "delete", $risk_id);
if (/*$canDelete &&*/ $risk_id > 0) {
    $titleBlock->addCrumbDelete("LBL_DELETE", $canDelete, $msg);
}

$titleBlock->show();
?>
<script language="javascript">
    function submitIt() {

        //    var f = document.uploadFrm;
        //    f.submit();
        var f = document.uploadFrm;
        var msg = "";
        var foc = false;
        if (f.risk_name.value.length < 3) {
            msg += "\n<?php echo $AppUI->_("LBL_VALID_RISK_NAME", UI_OUTPUT_JS); ?>";
            if ((foc == false) && (navigator.userAgent.indexOf("MSIE") == -1)) {
                f.risk_name.focus();
                foc = true;
            }
        }
        if (f.risk_description.value.length < 3) {
            msg += "\n<?php echo $AppUI->_("LBL_VALID_RISK_DESCRIPTION", UI_OUTPUT_JS); ?>";
            if ((foc == false) && (navigator.userAgent.indexOf("MSIE") == -1)) {
                f.risk_description.focus();
                foc = true;
            }
        }
        if (msg.length < 1) {
            document.getElementById("risk_period_start_date").disabled = false;
            document.getElementById("risk_period_end_date").disabled = false;
            f.submit();
        } else {
            alert(msg);
        }
    }
    function delIt() {
        if (confirm("<?php echo $AppUI->_("LBL_DELETE_MSG", UI_OUTPUT_JS); ?>")) {
            var f = document.uploadFrm;
            f.del.value = "1";
            f.submit();
        }
    }

    function cancelFormChanges() {
        if (confirm("<?php echo $AppUI->_("Are you sure you want to cancel?", UI_OUTPUT_JS); ?>")) {
            window.location.href = "<?php echo $href; ?>";
        }
    }

    function updateRisksReponseFieldsBasedOnStartegy() {
        var riskStrategy = document.uploadFrm.risk_strategy.value;
        if (riskStrategy == 0) {
            with (document.uploadFrm) {
                risk_prevention_actions.disabled = true;
                //risk_contingency_plan.disabled = true;
                //risk_responsible.disabled = true;
                //risk_triggers.disabled = true;
            }
        } else {
            with (document.uploadFrm) {
                risk_prevention_actions.disabled = false;
                risk_contingency_plan.disabled = false;
                risk_responsible.disabled = false;
                //risk_triggers.disabled = false;
            }
        }
    }

</script>
<link href="modules/timeplanning/css/table_form.css" type="text/css" rel="stylesheet" />
<!-- calendar goodies -->
<link type="text/css" rel="stylesheet" href="./modules/timeplanning/js/jsLibraries/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112" media="screen"></link>
<script type="text/javascript" src="./modules/timeplanning/js/jsLibraries/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=20060118"></script>

<form name="uploadFrm" action="?m=risks" method="post">
    <input type="hidden" name="dosql" value="do_risks_aed" />
    <input type="hidden" name="del" value="0" />
    <input type="hidden" name="risk_id" value="<?php echo $risk_id; ?>" />
    <input type="hidden" name="risk_project" value="<?php echo $projectSelected ?>" />
    <table  width="100%" border="0" class="std" name="table_form">
        <tr>
            <td colspan="2" class="td_section">
                <?php echo $AppUI->_("LBL_RISK_IDENTIFICATION"); ?>
            </td>
        </tr>
        <tr>
            <td class="td_label">
                <label for="risk_name"><?php echo $AppUI->_("LBL_RISK_NAME"); ?></label><span class="span_mandatory">*</span>:
            </td>
            <td>
                <input type="text" name="risk_name" value="<?php echo $obj->risk_name; ?>"  maxlength="100" />
            </td>
        </tr>
        <tr>
            <td class="td_label">
                <label for="risk_cause"><?php echo $AppUI->_("LBL_RISK_CAUSE"); ?></label>:
            </td>
            <td>
                <textarea name="risk_cause" cols="50" rows="2" style="wrap:virtual;" maxlength="255" class="textarea"><?php echo $obj->risk_cause; ?></textarea>
            </td>
        </tr>
        <tr>
            <td class="td_label">
                <label for="risk_consequence"><?php echo $AppUI->_("LBL_RISK_CONSEQUENCE"); ?></label>:
            </td>
            <td>
                <textarea name="risk_consequence" cols="50" rows="2" style="wrap:virtual;" maxlength="255" class="textarea"><?php echo $obj->risk_consequence; ?></textarea>
            </td>
        </tr>
        <tr>
            <td class="td_label">
                <label for="risk_description"><?php echo $AppUI->_("LBL_DESCRIPTION"); ?></label><span class="span_mandatory">*</span>:
            </td>
            <td>
                <textarea name="risk_description" cols="50" rows="2" style="wrap:virtual;" maxlength="255" class="textarea"><?php echo $obj->risk_description; ?></textarea>
            </td>
        </tr>

        <!--
        <tr>
            <td class="td_label">
                <label for="risk_project"><?php echo $AppUI->_("LBL_PROJECT"); ?></label>:
            </td>
            <td>
        <?php
        $q = new DBQuery();
        $q->addQuery("project_id, project_name");
        $q->addTable("projects");
        $q->addWhere("project_id = " . $projectSelected);
        $project = $q->loadHashList();
        if ($projectSelected == null) {
            $projectSelected = @$obj->risk_project;
            $projectName = $projects[@$obj->risk_project];
            $project[@$obj->risk_project] = $projectName;
        }
        echo arraySelect($project, "risk_project", "size=\"1\" class=\"text\"", (@$obj->risk_project ? $obj->risk_project : $projectSelected));
        ?>         
            </td>
        </tr>
        -->
        <tr>
            <td class="td_label">
                <label for="risk_task"><?php echo $AppUI->_("LBL_TASK"); ?></label>:
            </td>
            <td>
                <?php
                $tasks = array();
                $results = array();
                $perms = $AppUI->acl();
                if ($perms->checkModule("tasks", "view")) {
                    $q = new DBQuery();
                    $q->addQuery("t.task_id, t.task_name");
                    $q->addTable("tasks", "t");
                    $q->addWhere("task_project = " . (int) $projectSelected);
                    $results = $q->loadHashList("task_id");
                }
                $taskList = $results;

                foreach ($taskList as $key => $value) {
                    $tasks[$key] = $value["task_name"];
                }
                $tasks[-1] = $AppUI->_("LBL_ALL_TASKS");
                $tasks[0] = str_replace("&atilde;", "ã", $AppUI->_("LBL_NOT_DEFINED"));
                echo arraySelect($tasks, "risk_task", "size=\"1\" class=\"text\"", dPformSafe(@$obj->risk_task));
                ?>
            </td>
        </tr>

        <tr>
            <td class="td_label">
                <label for="risk_period_start_date"><?php echo $AppUI->_("LBL_RISK_PERIOD"); ?></label>:
            </td>
            <td>
                <input type="text" class="text" style="width:80px;" disabled="true" name="risk_period_start_date" value="<?php echo dPformSafe(@$obj->risk_period_start_date); ?>" id="risk_period_start_date" />
                <img src="./modules/timeplanning/images/img.gif" id="calendar_trigger" style="cursor:pointer" onclick="displayCalendar(document.getElementById('risk_period_start_date'), 'yyyy-mm-dd', this)" />
    
                <input type="text" class="text" style="width:80px;" disabled="true" name="risk_period_end_date" value="<?php echo dPformSafe(@$obj->risk_period_end_date); ?>" id="risk_period_end_date" />
                <img src="./modules/timeplanning/images/img.gif" id="calendar_trigger" style="cursor:pointer" onclick="displayCalendar(document.getElementById('risk_period_end_date'), 'yyyy-mm-dd', this)" />
            </td>
        </tr>

        <tr>
            <td class="td_label">
                <label for="risk_ear_classification"><?php echo $AppUI->_("LBL_RISK_EAR_CLASSIFICATION"); ?></label>:
            </td>
            <td>
                <?php
                require_once DP_BASE_DIR . "/modules/risks/controlling/risks_controlling.php";
                $rcontrolling = new RisksControlling();
                $options_ear = $rcontrolling->getRisksEARCategories($projectSelected);
                echo arraySelect($options_ear, "risk_ear_classification", "size=\"1\" class=\"text\"", dPformSafe(@$obj->risk_ear_classification));
                ?>
            </td>
        </tr>

        <tr>
            <td class="td_label">
                <label for="risk_notes"><?php echo $AppUI->_("LBL_NOTES"); ?></label>:
            </td>
            <td>
                <textarea name="risk_notes" cols="50" rows="2" maxlength="100" style="wrap:virtual;" class="textarea"><?php echo $obj->risk_notes; ?></textarea>
            </td>
        </tr>
        <tr>
            <td class="td_label">
                <label for="risk_potential_other_projects"><?php echo $AppUI->_("LBL_POTENTIAL"); ?></label>:
            </td>
            <td>
                <?php
                echo arraySelect($riskPotential, "risk_potential_other_projects", "size=\"1\" class=\"text\"", $obj->risk_potential_other_projects);
                ?>
            </td>
        </tr>


        <tr>
            <td colspan="2" class="td_section">
                <?php echo $AppUI->_("LBL_QUALITATYVE_ANALISYS"); ?>
            </td>
        </tr>

        <tr>
            <td class="td_label">
                <label for="risk_probability"><?php echo $AppUI->_("LBL_PROBABILITY"); ?></label>:
            </td>
            <td>
                <?php
                echo arraySelect($riskProbability, "risk_probability", "size=\"1\" class=\"text\"", $obj->risk_probability);
                ?>
            </td>
        </tr>
        <tr>
            <td class="td_label">
                <label for="risk_impact"><?php echo $AppUI->_("LBL_IMPACT"); ?></label>:
            </td>
            <td>
                <?php
                echo arraySelect($riskImpact, "risk_impact", "size=\"1\" class=\"text\"", $obj->risk_impact);
                ?>
            </td>
        </tr>
        <tr>
            <td class="td_label">
                <label for="risk_importance"><?php echo $AppUI->_("LBL_RISK_IMPORTANCE"); ?></label>:
            </td>
            <td>
                <?php
                $expositionImpact = $impactProbabilityMatrix[$obj->risk_probability][$obj->risk_impact];
                echo $textExpositionFactor[$expositionImpact];
                ?>
            </td>
        </tr>

        <tr>
            <td colspan="2" class="td_section">
                <?php echo $AppUI->_("LBL_RESPONSE_PLAN"); ?>
            </td>
        </tr>
        <tr>

            <td class="td_label">
                <label for="risk_strategy"><?php echo $AppUI->_("LBL_STRATEGY"); ?></label>:
            </td>
            <td>
                <?php
                echo arraySelect($riskStrategy, "risk_strategy", "size=\"1\" class=\"text\" onchange=\"updateRisksReponseFieldsBasedOnStartegy()\"", $obj->risk_strategy);
                ?>
            </td>
        </tr>
        <tr>
            <td class="td_label">
                <label for="risk_prevention_actions"><?php echo $AppUI->_("LBL_PREVENTION_ACTIONS"); ?></label>:
            </td>
            <td>
                <textarea name="risk_prevention_actions" cols="50" rows="4" style="wrap:virtual;" class="textarea" maxlength="255"><?php echo $obj->risk_prevention_actions; ?></textarea>
            </td>
        </tr>
        <tr>
            <td class="td_label">
                <label for="risk_is_contingency"><?php echo $AppUI->_("LBL_INCLUDE_IN_CONTINGENCY_RESERVE"); ?></label>:
            </td>
            <td>
                <input type="radio" name="risk_is_contingency" value="1" <?php echo $obj->risk_is_contingency == 1 ? "checked=true" : "" ?> /> <?php echo $AppUI->_("LBL_YES"); ?>
                <input type="radio" name="risk_is_contingency" value="0" <?php echo $obj->risk_is_contingency != 1 ? "checked=true" : "" ?> /> <?php echo $AppUI->_("LBL_NO"); ?>
            </td>
        </tr>
        <tr>
            <td class="td_label">
                <label for="risk_contingency_plan"><?php echo $AppUI->_("LBL_CONTINGENCY_PLAN"); ?></label>:
            </td>
            <td>
                <textarea name="risk_contingency_plan" cols="50" rows="4" style="wrap:virtual;" class="textarea" maxlength="255"><?php echo $obj->risk_contingency_plan; ?></textarea>
            </td>
        </tr>
        <tr>
            <td class="td_label">
                <label for="risk_triggers"><?php echo $AppUI->_("LBL_TRIGGER"); ?></label>:
            </td>
            <td>
                <textarea name="risk_triggers" cols="50" rows="4" style="wrap:virtual;" class="textarea" maxlength="255"><?php echo $obj->risk_triggers; ?></textarea>
            </td>
        </tr>
        <tr>
            <td class="td_label">
                <label for="risk_responsible"><?php echo $AppUI->_("LBL_OWNER"); ?></label>:
            </td>
            <td>
                <?php
                echo arraySelect($owners, "risk_responsible", "size=\"1\" class=\"text\"", $obj->risk_responsible);
                ?>
            </td>
        </tr>
        <tr>
            <td colspan="2" class="td_section">
                <?php echo $AppUI->_("LBL_RISK_CONTROLING"); ?>
            </td>
        </tr>
        <tr>
            <td class="td_label">
                <label for="risk_status"><?php echo $AppUI->_("LBL_RISK_STATUS"); ?></label>:
            </td>
            <td>
                <?php
                $options = array();
                $options[0] = $AppUI->_("LBL_RISK_STATUS_IDENTIFIED");
                $options[1] = $AppUI->_("LBL_RISK_STATUS_MONITORED");
                $options[2] = $AppUI->_("LBL_RISK_STATUS_MATERIALIZED");
                $options[3] = $AppUI->_("LBL_RISK_STATUS_FINISHED");
                echo arraySelect($options, "risk_status", "size=\"1\" class=\"text\"", dPformSafe(@$obj->risk_status));
                ?>
            </td>
        </tr>


        <!--
        <tr>
            <td class="td_label">
                <label for="risk_lessons_learned"><?php echo $AppUI->_("LBL_LESSONS"); ?></label>:
            </td>
            <td>
                <textarea name="risk_lessons_learned" cols="50" rows="4" style="wrap:virtual;" class="textarea" maxlength="100"><?php echo dPformSafe(@$obj->risk_lessons_learned); ?></textarea>
            </td>
        </tr>
        -->
        <tr>
            <td class="td_label">
                <label for="risk_active"><?php echo $AppUI->_("LBL_ACTIVE"); ?></label>:
            </td>
            <td>
                <?php
                echo arraySelect($riskActive, "risk_active", "size=\"1\" class=\"text\"", dPformSafe(@$obj->risk_active));
                ?>
            </td>
        </tr>
        <tr>
            <td colspan="2" align="right">
                 <input type="button" class="button" value="<?php echo $AppUI->_("LBL_SUBMIT"); ?>" onclick="submitIt()" />
                 <?php require_once (DP_BASE_DIR . "/modules/timeplanning/view/subform_back_button_project.php"); ?>
            </td>
        </tr>

    </table>
</form>
<span class="span_mandatory">*</span>&nbsp;<?php echo $AppUI->_("LBL_REQUIRED_FIELD"); ?>
<script>
    updateRisksReponseFieldsBasedOnStartegy();
</script>