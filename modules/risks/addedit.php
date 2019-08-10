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
?>
<form name="riskForm" id="riskForm">
    <input type="hidden" name="dosql" value="do_risks_aed" />
    <input type="hidden" name="del" value="0" />
    <input type="hidden" name="risk_id" value="<?php echo $risk_id; ?>" />
    <input type="hidden" name="risk_project" value="<?php echo $projectSelected ?>" />

    <div class="form-group">
        <span class="required"></span>
        <?=$AppUI->_('requiredField');?>
    </div>

    <div class="alert alert-secondary" role="alert">
        <?=$AppUI->_("LBL_RISK_IDENTIFICATION")?>
    </div>

    <div class="form-group">
        <label for="risk_name" class="required">
            <?=$AppUI->_("LBL_RISK_NAME")?>
        </label>
        <input type="text" class="form-control form-control-sm" name="risk_name" value="<?=$obj->risk_name?>"  maxlength="100" />
    </div>

    <div class="form-group">
        <label for="risk_description" class="required">
            <?=$AppUI->_("LBL_DESCRIPTION")?>
        </label>
        <textarea name="risk_description" rows="2" maxlength="255" class="form-control form-control-sm"><?=$obj->risk_description?></textarea>
    </div>

    <div class="form-group">
        <label for="risk_cause">
            <?=$AppUI->_("LBL_RISK_CAUSE")?>
        </label>
        <textarea name="risk_cause" rows="2" maxlength="255" class="form-control form-control-sm"><?=$obj->risk_cause?></textarea>
    </div>

    <div class="form-group">
        <label for="risk_consequence">
            <?=$AppUI->_("LBL_RISK_CONSEQUENCE")?>
        </label>
        <textarea name="risk_consequence" rows="2" maxlength="255" class="form-control form-control-sm"><?=$obj->risk_consequence?></textarea>
    </div>

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
        $tasks[0] = str_replace("&atilde;", "ã", $AppUI->_("LBL_NOT_DEFINED"));
    ?>

    <div class="form-group risk-activity">
        <label for="risk_task">
            <?=$AppUI->_("LBL_TASK")?>
        </label>
        <select class="form-control form-control-sm select-activity" name="risk_task">
            <option value="-1"><?=$AppUI->_("LBL_ALL_TASKS")?></option>
            <?php
                foreach ($tasks as $key => $value) {
                    $selected = ($key ==  dPformSafe(@$obj->risk_task)) ? 'selected' : '';
                    ?>
                    <option value="<?=$key?>" <?=$selected?>><?=$value?></option>
                    <?php
                }
            ?>
        </select>
    </div>


    <div class="row">
        <div class="col-md-8">
            <div class="form-group">
                <label for="risk_period_period">
                    <?=$AppUI->_("LBL_RISK_PERIOD")?>
                </label>
                <div class="row">
                    <div class="col-md-6">
                        <input type="hidden" name="risk_period_start_date" id="riskStartDate" value="<?=dPformSafe(@$obj->risk_period_start_date)?>" />
                        <input type="text"
                           class="form-control form-control-sm datepicker-start"
                           name="risk_start_date"
                           onchange="formatStartDate()" />
                    </div>
                    <div class="col-md-6">
                        <input type="hidden" name="risk_period_end_date" id="riskEndDate" value="<?=dPformSafe(@$obj->risk_period_end_date)?>" />
                        <input type="text"
                           class="form-control form-control-sm datepicker-end"
                           name="risk_end_date"
                           onchange="formatEndDate()" />
                    </div>
                </div>
            </div>
        </div>
        <?php
            require_once DP_BASE_DIR . "/modules/risks/controlling/risks_controlling.php";
            $rcontrolling = new RisksControlling();
            $options_ear = $rcontrolling->getRisksEARCategories($projectSelected);
        ?>
        <div class="col-md-4 risk-classification">
            <div class="form-group">
                <label for="risk_period_end_date">
                    <?=$AppUI->_("LBL_RISK_EAR_CLASSIFICATION")?>
                </label>
                <select class="form-control form-control-sm select-classification" name="risk_ear_classification">
                    <?php
                        foreach ($options_ear as $key => $value) {
                            $selected = ($key ==  dPformSafe(@$obj->risk_ear_classification)) ? 'selected' : '';
                            ?>
                            <option value="<?=$key?>" <?=$selected?>><?=$value?></option>
                            <?php
                        }
                    ?>
                </select>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="form-group">
                <label for="risk_notes">
                    <?=$AppUI->_("LBL_NOTES")?>
                </label>
                <textarea name="risk_notes" rows="2" maxlength="100" class="form-control form-control-sm"><?=$obj->risk_notes?></textarea>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="risk_potencial">
                    <?=$AppUI->_("LBL_POTENTIAL")?>
                </label>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="risk_potential_other_projects" value="1" <?=$obj->risk_potential_other_projects == 1 ? "checked=true" : ""?>>
                    <label class="form-check-label" for="inlineRadio1">Sim</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="risk_potential_other_projects" value="0" <?=$obj->risk_potential_other_projects != 1 ? "checked=true" : ""?>>
                    <label class="form-check-label" for="inlineRadio2">Não</label>
                </div>
            </div>
        </div>
    </div>

    <div class="alert alert-secondary" role="alert">
        <?=$AppUI->_("LBL_QUALITATYVE_ANALISYS")?>
    </div>

    <div class="row risk-probability">
        <div class="col-md-4">
            <div class="form-group">
                <label for="risk_probability">
                    <?=$AppUI->_("LBL_PROBABILITY")?>
                </label>
                <select class="form-control form-control-sm select-probability" name="risk_probability">
                    <option></option>
                    <?php
                    foreach ($riskProbability as $key => $value) {
                        $selected = ($key ==  dPformSafe(@$obj->risk_probability)) ? 'selected' : '';
                        ?>
                        <option value="<?=$key?>" <?=$selected?>><?=$value?></option>
                        <?php
                    }
                    ?>
                </select>
            </div>
        </div>
        <div class="col-md-4 risk-impact">
            <div class="form-group">
                <label for="risk_impact">
                    <?=$AppUI->_("LBL_IMPACT")?>
                </label>
                <select class="form-control form-control-sm select-impact" name="risk_impact">
                    <option></option>
                    <?php
                    foreach ($riskImpact as $key => $value) {
                        $selected = ($key ==  dPformSafe(@$obj->risk_impact)) ? 'selected' : '';
                        ?>
                        <option value="<?=$key?>" <?=$selected?>><?=$value?></option>
                        <?php
                    }
                    ?>
                </select>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="risk_importance">
                    <?=$AppUI->_("LBL_RISK_IMPORTANCE")?>
                </label>
                <?php
                    $expositionImpact = $impactProbabilityMatrix[$obj->risk_probability][$obj->risk_impact];
                    echo $textExpositionFactor[$expositionImpact];
                ?>
            </div>
        </div>
    </div>

    <div class="alert alert-secondary" role="alert">
        <?=$AppUI->_("LBL_RESPONSE_PLAN")?>
    </div>

    <div class="row risk-strategy">
        <div class="col-md-4">
            <div class="form-group">
                <label for="risk_strategy">
                    <?=$AppUI->_("LBL_STRATEGY")?>
                </label>
                <select class="form-control form-control-sm select-strategy" name="risk_strategy">
                    <option></option>
                    <?php
                    foreach ($riskStrategy as $key => $value) {
                        $selected = ($key ==  dPformSafe(@$obj->risk_strategy)) ? 'selected' : '';
                        ?>
                        <option value="<?=$key?>" <?=$selected?>><?=$value?></option>
                        <?php
                    }
                    ?>
                </select>
            </div>
        </div>
        <div class="col-md-8">
            <div class="form-group">
                <label for="risk_is_contingency">
                    <?=$AppUI->_("LBL_INCLUDE_IN_CONTINGENCY_RESERVE")?>
                </label>
                <br>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="risk_is_contingency" id="inlineRadio1" value="1" <?=$obj->risk_is_contingency == 1 ? "checked=true" : ""?>>
                    <label class="form-check-label" for="inlineRadio1">Sim</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="risk_is_contingency" id="inlineRadio2" value="0" <?=$obj->risk_is_contingency != 1 ? "checked=true" : ""?>>
                    <label class="form-check-label" for="inlineRadio2">Não</label>
                </div>
            </div>
        </div>
    </div>

    <div class="form-group">
        <label for="risk_prevention_actions">
            <?=$AppUI->_("LBL_PREVENTION_ACTIONS")?>
        </label>
        <textarea name="risk_prevention_actions" rows="2" class="form-control form-control-sm" maxlength="255"><?=$obj->risk_prevention_actions?></textarea>
    </div>

    <div class="form-group">
        <label for="risk_contingency_plan">
            <?=$AppUI->_("LBL_CONTINGENCY_PLAN")?>
        </label>
        <textarea name="risk_contingency_plan" rows="2" class="form-control form-control-sm" maxlength="255"><?=$obj->risk_contingency_plan?></textarea>
    </div>

    <div class="form-group">
        <label for="risk_triggers">
            <?=$AppUI->_("LBL_TRIGGER")?>
        </label>
        <textarea name="risk_triggers" rows="2" class="form-control form-control-sm" maxlength="255"><?=$obj->risk_triggers?></textarea>
    </div>

    <div class="row risk-owner">
        <div class="col-md-6">
            <div class="form-group">
                <label for="risk_responsible">
                    <?=$AppUI->_("LBL_OWNER")?>
                </label>
                <select class="form-control form-control-sm select-owner" name="risk_responsible">
                    <option></option>
                    <?php
                    foreach ($owners as $key => $value) {
                        $selected = ($key ==  dPformSafe(@$obj->risk_responsible)) ? 'selected' : '';
                        ?>
                        <option value="<?=$key?>" <?=$selected?>><?=$value?></option>
                        <?php
                    }
                    ?>
                </select>
            </div>
        </div>
    </div>

    <div class="alert alert-secondary" role="alert">
        <?=$AppUI->_("LBL_RISK_CONTROLING")?>
    </div>

    <div class="row risk-status">
        <div class="col-md-4">
            <div class="form-group">
                <?php
                    $options = array();
                    $options[0] = $AppUI->_("LBL_RISK_STATUS_IDENTIFIED");
                    $options[1] = $AppUI->_("LBL_RISK_STATUS_MONITORED");
                    $options[2] = $AppUI->_("LBL_RISK_STATUS_MATERIALIZED");
                    $options[3] = $AppUI->_("LBL_RISK_STATUS_FINISHED");
                ?>
                <label for="risk_responsible">
                    <?=$AppUI->_("LBL_RISK_STATUS")?>
                </label>
                <select class="form-control form-control-sm select-status" name="risk_status">
                    <option></option>
                    <?php
                    foreach ($options as $key => $value) {
                        $selected = ($key ==  dPformSafe(@$obj->risk_status)) ? 'selected' : '';
                        ?>
                        <option value="<?=$key?>" <?=$selected?>><?=$value?></option>
                        <?php
                    }
                    ?>
                </select>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="risk_active">
                    <?=$AppUI->_("LBL_ACTIVE")?>
                </label>
                <br>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="risk_active" value="0" <?=$obj->risk_active != 1 ? "checked=true" : ""?>>
                    <label class="form-check-label" for="inlineRadio1">Sim</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="risk_active" value="1" <?=$obj->risk_active == 1 ? "checked=true" : ""?>>
                    <label class="form-check-label" for="inlineRadio2">Não</label>
                </div>
            </div>
        </div>
    </div>
</form>

<script>

    function updateRisksReponseFieldsBasedOnStartegy() {
        var riskStrategy = $('.select-strategy').val();
        if (riskStrategy == 0) {
            $('textarea[name=risk_prevention_actions]').attr('readonly', true);
        } else {
            $('textarea[name=risk_prevention_actions]').attr('readonly', false);
        }
    }

    function formatStartDate() {
        var date = $('.datepicker-start').val();
        var arrDate = date.split('/');
        $('#riskStartDate').val(arrDate[2] + '-' + arrDate[1] + '-' + arrDate[0]);
    }

    function formatEndDate() {
        var date = $('.datepicker-end').val();
        var arrDate = date.split('/');
        $('#riskEndDate').val(arrDate[2] + '-' + arrDate[1] + '-' + arrDate[0]);
    }

    function getStartDate() {
        var date = $('#riskStartDate').val();
        if (!date) return;
        var arrDate = date.split('-');
        $('.datepicker-start').val(arrDate[2] + '/' + arrDate[1] + '/' + arrDate[0]);
    }

    function getEndDate() {
        var date = $('#riskEndDate').val();
        if (!date) return;
        var arrDate = date.split('-');
        $('.datepicker-end').val(arrDate[2] + '/' + arrDate[1] + '/' + arrDate[0]);
    }

    $(document).ready(function() {

        $(".select-activity").select2({
            allowClear: true,
            placeholder: "",
            theme: "bootstrap",
            dropdownParent: $(".risk-activity")
        });

        $(".select-classification").select2({
            allowClear: true,
            placeholder: "",
            theme: "bootstrap",
            dropdownParent: $(".risk-classification")
        });

        $(".select-probability").select2({
            allowClear: true,
            placeholder: "",
            theme: "bootstrap",
            dropdownParent: $(".risk-probability")
        });

        $(".select-impact").select2({
            allowClear: true,
            placeholder: "",
            theme: "bootstrap",
            dropdownParent: $(".risk-impact")
        });

        $(".select-strategy").select2({
            allowClear: true,
            placeholder: "",
            theme: "bootstrap",
            dropdownParent: $(".risk-strategy")
        });

        $(".select-owner").select2({
            allowClear: true,
            placeholder: "",
            theme: "bootstrap",
            dropdownParent: $(".risk-owner")
        });

        $(".select-status").select2({
            allowClear: true,
            placeholder: "",
            theme: "bootstrap",
            dropdownParent: $(".risk-status")
        });

        $(".select-strategy").on("change", updateRisksReponseFieldsBasedOnStartegy);

        $( ".datepicker-start" ).datepicker({
            dateFormat: 'dd/mm/yy',
            onSelect: formatStartDate
        });
        $( ".datepicker-end" ).datepicker({
            dateFormat: 'dd/mm/yy',
            onSelect: formatEndDate
        });

        updateRisksReponseFieldsBasedOnStartegy();
        getStartDate();
        getEndDate();
    });
</script>
<?php
    exit();
?>