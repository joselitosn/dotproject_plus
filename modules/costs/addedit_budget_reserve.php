<?php
if (!defined("DP_BASE_DIR")) {
    die("You should not access this file directly.");
}

require_once DP_BASE_DIR . "/modules/costs/costs_functions.php";

$budget_reserve_id = intval(dPgetParam($_GET, "budget_reserve_id", 0));
$projectSelected = intval(dPgetParam($_GET, "project_id"));

$q = new DBQuery();
$q->addQuery("*");
$q->addTable("budget_reserve");
$q->addWhere("budget_reserve_id = " . $budget_reserve_id);
//$project_id = $q->loadList();
// check if this record has dependancies to prevent deletion
$msg = "";
$obj = new CBudgetReserve();
$canDelete = $obj->canDelete($msg, $budget_reserve_id);

// load the record data
$obj = null;
if (!db_loadObject($q->prepare(), $obj) && ($budget_reserve_id > 0)) {
    $AppUI->setMsg("Budget");
    $AppUI->setMsg("invalidID", UI_MSG_ERROR, true);
    $AppUI->redirect();
}

$q->clear();
$q->addQuery("project_start_date,project_end_date");
$q->addTable("projects");
$q->addWhere("project_id = \"$projectSelected\"");
$datesProject = & $q->exec();
$dateSP = substr($datesProject->fields["project_start_date"], 0, -9);
$dateTemp = substr($datesProject->fields["project_end_date"], 0, -9);
$dateEP = (string) $dateTemp;
// format dates
$date_begin = intval($obj->budget_reserve_inicial_month) ? new CDate($obj->budget_reserve_inicial_month) : null;
$date_end = intval($obj->budget_reserve_final_month) ? new CDate($obj->budget_reserve_final_month) : null;
$df = $AppUI->getPref("SHDATEFORMAT");
$projectEndDate=new CDate($datesProject->fields["project_end_date"]);
$projectEndDateUserFormat=$projectEndDate->format($df);

?>

<!-- import the calendar script -->
<script type="text/javascript" src="<?php echo DP_BASE_URL; ?>/lib/calendar/calendar.js"></script>
<!-- import the language module -->
<script type="text/javascript" src="<?php echo DP_BASE_URL; ?>/lib/calendar/lang/calendar-<?php echo $AppUI->user_locale; ?>.js"></script>
<script language="javascript">
    
    function sumTotalValue(){
        var FI = $("#budget_reserve_financial_impact").cleanVal();
        console.log(FI);
        document.getElementById("budget_reserve_value_total").value = FI;
    }

    $(document).ready(function() {
        $( ".datepicker-begin" ).datepicker({
            dateFormat: 'dd/mm/yy',
            onSelect: function () {
                var dateArr = $(this).val().split('/');
                $('#budget_reserve_inicial_month').val(dateArr[2]+dateArr[1]+dateArr[0]);
            }
        });

        $( ".datepicker-finish" ).datepicker({
            dateFormat: 'dd/mm/yy',
            onSelect: function () {
                var dateArr = $(this).val().split('/');
                $('#budget_reserve_final_month').val(dateArr[2]+dateArr[1]+dateArr[0]);
            }
        });
        var val = $("#budget_reserve_financial_impact").val();
        val = val + '00';
        $("#budget_reserve_financial_impact").val(val);
        $("#budget_reserve_financial_impact").mask(
            "000.000.000.000.000,00",
            {
                reverse: true
            }
        );
    });
</script>


<div class="alert alert-secondary" role="alert">
    <?=$AppUI->_('Name') . ': ' . dPformSafe($obj->budget_reserve_description)?>
</div>

<form name="contingencyReserveForm" id="contingencyReserveForm" method="post">
    <input type="hidden" name="dosql" value="do_budget_reserve_aed" />
    <input type="hidden" name="del" value="0" />
    <input type="hidden" name="project_id" value="<?=$_GET["project_id"]?>" />
    <input type="hidden" name="budget_reserve_id" value="<?=$budget_reserve_id?>" />
    <input type="hidden" name="budget_reserve_value_total"  id="budget_reserve_value_total" value="<?=dPformSafe($obj->budget_reserve_value_total)?>" />
    <input type="hidden" name="project_end_date" id="projectEndDate" value="<?=$dateEP?>" />

    <div class="form-group">
        <span class="required"></span>
        <?=$AppUI->_('requiredField');?>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label for="Financial Impact" class="required">
                    <?=$AppUI->_('Financial Impact')?>
                </label>
                <input name="budget_reserve_financial_impact"
                   class="form-control form-control-sm"
                   id="budget_reserve_financial_impact"
                   value="<?=dPformSafe($obj->budget_reserve_financial_impact)?>"
                   onblur="sumTotalValue()"/>
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group">
                <label for="data_begin" class="required">
                    <?=$AppUI->_('Date Begin')?>
                </label>
                <input type="hidden" name="budget_reserve_inicial_month" id="budget_reserve_inicial_month"  value="<?=(($date_begin) ? $date_begin->format(FMT_TIMESTAMP_DATE) : "")?>" />
                <input type="text" class="form-control form-control-sm datepicker-begin" name="reserve_inicial_month" id="date0" value="<?=(($date_begin) ? $date_begin->format($df) : '')?>" />
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group">
                <label for="data_end" class="required">
                    <?=$AppUI->_('Date End')?>
                </label>
                <input type="hidden" name="budget_reserve_final_month" id="budget_reserve_final_month" value="<?=(($date_end) ? $date_end->format(FMT_TIMESTAMP_DATE) : "")?>" />
                <input type="text" class="form-control form-control-sm datepicker-finish" name="reserve_final_month" id="date1" value="<?=(($date_end) ? $date_end->format($df) : '')?>" />
            </div>
        </div>
        <div class="col-md-12">
            <small><?=$AppUI->_("LBL_VALIDATION_DATE_CONTINGENCY_PROJECT")?>&nbsp; (<?=$projectEndDateUserFormat?>)</small>
        </div>
    </div>
</form>
<?php
    exit();
?>