<?php
/* PROJECTS $Id: addedit.php 5433 2007-10-17 15:02:46Z gregorerhardt $ */
if (!defined('DP_BASE_DIR')) {
    die('You should not access this file directly');
}
require_once DP_BASE_DIR . "/modules/projects/projects.class.php";
require_once DP_BASE_DIR . "/modules/closure/closure.class.php";

$project_id = $_GET["project_id"];
$df = $AppUI->getPref('SHDATEFORMAT');

$projectObj = new CProject();
$projectObj->load($project_id);

$row = new CClosure();

$q = new DBQuery();
$q->addTable('post_mortem_analysis');
$q->addQuery('*');
$q->addWhere("project_name='" . $projectObj->project_name . "'");
$res = $q->loadList();

$pma_id = 0;

if (count($res) > 0) {

    $res = current($res);
    $pma_id = $res['pma_id'];
    $row->bind($res);
}
if ($pma_id == 0) {
    $row->planned_budget = $projectObj->project_target_budget;
    $row->project_planned_start_date = $projectObj->project_start_date;
    $row->project_planned_end_date = $projectObj->project_end_date;
    $row->project_name = $projectObj->project_name;

    $end_date = intval($row->project_planned_end_date) ? new CDate($row->project_planned_end_date) : null;
    $meeting_date = new CDate();
    $start_date = new CDate($projectObj->project_start_date);
    $actual_start_date = null;
    $actual_end_date = null;
} else {
    // format dates  
    $start_date = new CDate($row->project_planned_start_date);
    $end_date = intval($row->project_planned_end_date) ? new CDate($row->project_planned_end_date) : null;
    $meeting_date = intval($row->project_meeting_date) ? new CDate($row->project_meeting_date) : null;
    $actual_start_date = intval($row->project_start_date) ? new CDate($row->project_start_date) : null;
    $actual_end_date = intval($row->project_end_date) ? new CDate($row->project_end_date) : null;
    $participants = $row->participants;
}

$q = new DBQuery;
$q->addTable('contacts');
$q->addQuery('contact_id, contact_first_name, contact_last_name');
$res = & $q->exec();
$pieces = explode(",", $participants);
?>

<h4><?=$AppUI->_("LBL_CLOSURE_POST_MORTEM_TITLE", UI_OUTPUT_HTML)?></h4>
<hr>

<form name="closureForm">
    <input type="hidden" name="dosql" value="do_closure_aed" />
    <input type="hidden" name="pma_id" value="<?php echo dPformSafe($pma_id); ?>" />
    <input type="hidden" name="project_name" value="<?php echo $projectObj->project_name; ?>" />

    <div class="card">
        <div class="card-header"><?=$AppUI->_("LBL_CLOSURE_MEETING_SETTINGS")?></div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-9">
                    <div class="form-group">
                        <label for="LBL_CLOSURE_PARTICIPANTS">
                            <?=$AppUI->_("LBL_CLOSURE_PARTICIPANTS")?>
                        </label>
                        <select class="form-control form-control-sm multiselect" name="list1[]" multiple>
                            <?php
                            for ($res; !$res->EOF; $res->MoveNext()) {
                                $contact_first_name = $res->fields['contact_first_name'];
                                $val = $res->fields['contact_id'];
                                $selected = in_array($val, $pieces) ? ' selected' : '';
                                    ?>
                                    <option value = <?=$val?> <?=$selected?>>
                                        <?php echo $contact_first_name; ?>
                                    </option>
                                    <?php
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="LBL_CLOSURE_MEETING_DATE">
                            <?=$AppUI->_("LBL_CLOSURE_MEETING_DATE")?>
                        </label>
                        <input type="hidden" 
                            name="project_meeting_date" 
                            id="project_meeting_date" 
                            value="<?=$meeting_date ? $meeting_date->format(FMT_TIMESTAMP_DATE) : ''?>"/>
                        <input type="text" 
                            onchange="setDateTimeHidden(this, '#project_meeting_date')" 
                            class="form-control form-control-sm datepicker" 
                            name="meeting_date" 
                            value="<?=$meeting_date ? $meeting_date->format($df) : ''?>" />
                    </div>
                </div>
            </div>

        </div>
    </div>

    <br>

    <div class="card">
        <div class="card-header"><?=$AppUI->_("LBL_CLOSURE_PROJECT_SUMMARY")?></div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="LBL_CLOSURE_PLANNED_START_DATE">
                            <?=$AppUI->_("LBL_CLOSURE_PLANNED_START_DATE")?>
                        </label>
                        <input type="hidden" 
                            name="project_planned_start_date" 
                            id="project_planned_start_date" 
                            value="<?php echo (($start_date) ? $start_date->format(FMT_TIMESTAMP_DATE) : ''); ?>"/>
                        <input type="text" 
                            onchange="setDateTimeHidden(this, '#project_planned_start_date')" 
                            class="form-control form-control-sm datepicker" 
                            name="planned_start_date" 
                            value="<?php echo (($start_date) ? $start_date->format($df) : ''); ?>" />
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="LBL_CLOSURE_ACTUAL_START_DATE">
                            <?=$AppUI->_("LBL_CLOSURE_ACTUAL_START_DATE")?>
                        </label>
                        <input type="hidden" 
                            name="project_start_date" 
                            id="project_start_date" 
                            value="<?php echo (($actual_start_date) ? $actual_start_date->format(FMT_TIMESTAMP_DATE) : ''); ?>"/>
                        <input type="text" 
                            onchange="setDateTimeHidden(this, '#project_start_date')" 
                            class="form-control form-control-sm datepicker" 
                            name="start_date" 
                            value="<?php echo (($actual_start_date) ? $actual_start_date->format($df) : ''); ?>" />
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="LBL_CLOSURE_PLANNED_END_DATE">
                            <?=$AppUI->_("LBL_CLOSURE_PLANNED_END_DATE")?>
                        </label>
                        <input type="hidden" 
                            name="project_planned_end_date" 
                            id="project_planned_end_date" 
                            value="<?php echo (($end_date) ? $end_date->format(FMT_TIMESTAMP_DATE) : ''); ?>"/>
                        <input type="text" 
                            onchange="setDateTimeHidden(this, '#project_planned_end_date')" 
                            class="form-control form-control-sm datepicker" 
                            name="planned_end_date" 
                            value="<?php echo (($end_date) ? $end_date->format($df) : ''); ?>" />
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="LBL_CLOSURE_ACTUAL_END_DATE">
                            <?=$AppUI->_("LBL_CLOSURE_ACTUAL_END_DATE")?>
                        </label>
                        <input type="hidden" 
                            name="project_end_date" 
                            id="project_end_date" 
                            value="<?php echo (($actual_end_date) ? $actual_end_date->format(FMT_TIMESTAMP_DATE) : ''); ?>"/>
                        <input type="text" 
                            onchange="setDateTimeHidden(this, '#project_end_date')" 
                            class="form-control form-control-sm datepicker" 
                            name="end_date" 
                            id="end_date" 
                            value="<?php echo (($actual_end_date) ? $actual_end_date->format($df) : ''); ?>" />
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="LBL_CLOSURE_PLANNED_BUDGET">
                            <?=$AppUI->_("LBL_CLOSURE_PLANNED_BUDGET")?> &nbsp;(<?php echo dPgetConfig("currency_symbol") ?>)
                        </label>
                        <input type="text" 
                            class="form-control form-control-sm budget" 
                            name="planned_budget" 
                            value="<?php echo dPformSafe($row->planned_budget) ?>" />
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="LBL_CLOSURE_ACTUAL_BUDGET">
                            <?=$AppUI->_("LBL_CLOSURE_ACTUAL_BUDGET")?> &nbsp;(<?php echo dPgetConfig("currency_symbol") ?>)
                        </label>
                        <input type="text" 
                            class="form-control form-control-sm budget" 
                            name="budget" 
                            value="<?php echo dPformSafe($row->budget) ?>" />
                    </div>
                </div>
            </div>
        </div>
    </div>

    <br>
    
    <div class="card">
        <div class="card-header"><?=$AppUI->_("LBL_CLOSURE_LEASSONS_LEARNED")?></div>
        <div class="card-body">
            <div class="form-group">
                <label for="LBL_CLOSURE_PROJECT_STRENGHTS">
                    <?=$AppUI->_("LBL_CLOSURE_PROJECT_STRENGHTS")?>
                </label>
                <textarea name='project_strength' class="form-control form-control-sm" rows="3"><?php echo dPformSafe($row->project_strength); ?></textarea>
                <small><?php echo $AppUI->_("LBL_CLOSURE_STRENGHTS_HINTS") ?></small>
            </div>

            <div class="form-group">
                <label for="LBL_CLOSURE_PROJECT_WEAKNESSES">
                    <?=$AppUI->_("LBL_CLOSURE_PROJECT_WEAKNESSES")?>
                </label>
                <textarea name='project_weaknesses' class="form-control form-control-sm" rows="3"><?php echo dPformSafe($row->project_weaknesses); ?></textarea>
                <small><?php echo $AppUI->_("LBL_CLOSURE_PROJECT_WEAKNESSES_HINTS") ?></small>
            </div>

            <div class="form-group">
                <label for="LBL_CLOSURE_IMPROMENT_SUGGESTIONS">
                    <?=$AppUI->_("LBL_CLOSURE_IMPROMENT_SUGGESTIONS")?>
                </label>
                <textarea name='improvement_suggestions' class="form-control form-control-sm" rows="3"><?php echo dPformSafe($row->improvement_suggestions); ?></textarea>
                <small><?php echo $AppUI->_("LBL_CLOSURE_IMPROMENT_SUGGESTIONS_HINTS") ?></small>
            </div>

            <div class="form-group">
                <label for="LBL_CLOSURE_CONCLUSIONS">
                    <?=$AppUI->_("LBL_CLOSURE_CONCLUSIONS")?>
                </label>
                <textarea name='conclusions' class="form-control form-control-sm" rows="3"><?php echo dPformSafe($row->conclusions); ?></textarea>
                <small><?php echo $AppUI->_("LBL_CLOSURE_CONCLUSIONS_HINTS") ?></small>
            </div>
        </div>
    </div>
    
    <br>

    <div class="form-group float-right">
        <button type="button" class="btn btn-sm btn-primary" onclick="save()">Salvar</button>
    </div>
</form>

<script type="text/javascript" language="javascript">

    $(document).ready(function() {
        $(".multiselect").select2({
            allowClear: false,
            placeholder: "",
            theme: "bootstrap"
       });

       $( ".datepicker" ).datepicker({
            dateFormat: 'dd/mm/yy',
            constrainInput: true
        });

        $(".budget").mask("000.000.000.000.000,00", {reverse: true});
        
    });

    function setDateTimeHidden(calendarElement, target) {
        var date = $(calendarElement).val();
        if (!date) {
            $(target).val('');
            return;
        }
        var dateArr = date.split('/');
        $(target).val(dateArr[2]+dateArr[1]+dateArr[0]);
        if (target == '#project_start_date') {
            $('#end_date').val(date);
            $('#project_end_date').val(dateArr[2]+dateArr[1]+dateArr[0]);
        }
    }

    function save() {
        $.ajax({
                method: 'POST',
                url: "?m=closure",
                data: $("form[name=closureForm]").serialize(),
                success: function(resposta) {
                    $.alert({
                        title: "Sucesso",
                        content: resposta
                    });
                },
                error: function(resposta) {
                    $.alert({
                        title: "Erro",
                        content: "Algo deu errado"
                    });
                }
            });
    }
</script>