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
//    function submitIt() {
//        var f = document.uploadFrm;
//        var trans = "<?php //echo $dateEP; ?>//";
//        var str1 = String(trans); //project end date
//        var str2 = document.getElementById("budget_reserve_final_month").value; //risk end date
//        var str3 = document.getElementById("budget_reserve_inicial_month").value; //risk start date
//        var financialImpact = f.budget_reserve_financial_impact.value;
//        if(str1 != "" && str2 != "" && str3 != "" && financialImpact!="" ){
//            var yr1  = parseInt(str1.substring(0,4),10);
//            var mon1 = parseInt(str1.substring(5,7),10);
//            var dt1  = parseInt(str1.substring(8,10),10);
//
//            var yr2  = parseInt(str2.substring(0,4),10);
//            var mon2 = parseInt(str2.substring(4,6),10);
//            var dt2  = parseInt(str2.substring(6,8),10);
//
//            var yr3  = parseInt(str3.substring(0,4),10);
//            var mon3 = parseInt(str3.substring(4,6),10);
//            var dt3  = parseInt(str3.substring(6,8),10);
//
//            var date1 = new Date(yr1, mon1, dt1); // project end date
//            var date2 = new Date(yr2, mon2, dt2); // risk end date
//            var date3 = new Date(yr3, mon3, dt3); // risk start date
//
//
//            if(date2 > date1){ //risk end date has to be smaller than project end date
//                msg = "\n<?php //echo $AppUI->_("LBL_VALIDATION_DATE_CONTINGENCY_PROJECT", UI_OUTPUT_JS) . "  ($projectEndDateUserFormat)" ?>//";
//                alert(msg);
//                return false;
//            }
//
//            if(date3 > date2){ //risk start date has to be smaller than risk end date
//                msg = "\n<?php //echo $AppUI->_("LBL_VALIDATION_DATE_CONTINGENCY_RISK", UI_OUTPUT_JS) ?>//";
//                alert(msg);
//                return false;
//            }
//        }else{
//            msg = "\n<?php //echo $AppUI->_("LBL_MANDARORY_FIELDS", UI_OUTPUT_JS) ?>//";
//            alert(msg);
//            return false;
//        }
//        var msg = "";
//        var foc=false;
//        financialImpact =parseFloat(financialImpact);
//        if (isNaN(financialImpact) || financialImpact  == 0 || financialImpact  < 0) {
//            msg += "\n<?php //echo $AppUI->_("LBL_VALIDATION_FINANCIAL_IMPACT", UI_OUTPUT_JS); ?>//";
//            if ((foc==false) && (navigator.userAgent.indexOf("MSIE")== -1)) {
//                f.budget_reserve_financial_impact.focus();
//                foc=true;
//            }
//        }
//
//        if (msg.length < 1) {
//            f.submit();
//        } else {
//            alert(msg);
//        }
//
//    }
    
//    function delIt() {
//        if (confirm("<?php //echo $AppUI->_("Delete this Contingency cost?", UI_OUTPUT_JS); ?>//")) {
//            var f = document.uploadFrm;
//            f.del.value="1";
//            f.submit();
//        }
//    }
//
//    function monthDiff(d1, d2) {
//        var months;
//        months = (d2.getFullYear() - d1.getFullYear()) * 12;
//        months -= d1.getMonth() + 1;
//        months += d2.getMonth();
//        return months;
//    }
    
    function sumTotalValue(){
        var FI = document.getElementById("budget_reserve_financial_impact").value;
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

//        $("#budget_reserve_financial_impact").mask("000.000.000.000.000,00", {reverse: true});
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














<!--    <table width="100%" border="0" cellpadding="3" cellspacing="3" class="std" name="table_form">-->
<!---->
<!---->
<!--        <tr>-->
<!--            <td class="td_label">--><?php //echo $AppUI->_("Financial Impact"); ?><!--&nbsp;(--><?php //echo dPgetConfig("currency_symbol") ?><!--)<span class="span_mandatory">*</span>:</td>-->
<!--            <td>-->
<!--                <input name="budget_reserve_financial_impact" id="budget_reserve_financial_impact" value="--><?php //echo dPformSafe($obj->budget_reserve_financial_impact); ?><!--" />-->
<!--            </td>-->
<!--        </tr>-->
<!---->
<!--        <tr>-->
<!--            <td class="td_label">--><?php //echo $AppUI->_("Date Begin"); ?><!--<span class="span_mandatory">*</span>:</td>-->
<!--            <td>-->
<!--                <input type="hidden" name="budget_reserve_inicial_month" id="budget_reserve_inicial_month"  value="--><?php //echo (($date_begin) ? $date_begin->format(FMT_TIMESTAMP_DATE) : ""); ?><!--"/>-->
<!--                <!-- format(FMT_TIMESTAMP_DATE) -->
<!--                <input type="text" class="text" style="width:85px" name="reserve_inicial_month" id="date0" value="--><?php //echo (($date_begin) ? $date_begin->format($df) : ""); ?><!--" disabled="disabled" />-->
<!---->
<!--                <a href="#" onclick="popCalendar( 'reserve_inicial_month', 'reserve_inicial_month');">-->
<!--                    <img src="./images/calendar.gif" width="24" height="12" alt="{dPtranslate word='Calendar'}" border="0" />-->
<!--                </a>-->
<!--            </td>-->
<!--        </tr>-->

<!--        <tr>-->
<!--            <td class="td_label">--><?php //echo $AppUI->_("Date End"); ?><!--<span class="span_mandatory">*</span>:</td>-->
<!--            <td>-->
<!--                <input type="hidden" name="budget_reserve_final_month" id="budget_reserve_final_month" value="--><?php //echo (($date_end) ? $date_end->format(FMT_TIMESTAMP_DATE) : ""); ?><!--"/>-->
<!--                <!-- format(FMT_TIMESTAMP_DATE) -->
<!--                <input type="text" class="text" style="width:85px"  name="reserve_final_month" id="date1" value="--><?php //echo (($date_end) ? $date_end->format($df) : ""); ?><!--" disabled="disabled" />-->
<!---->
<!--                <a href="#" onclick="popCalendar('reserve_final_month', 'reserve_final_month');">-->
<!--                    <img src="./images/calendar.gif" width="24" height="12" alt="{dPtranslate word='Calendar'}" border="0" />-->
<!--                </a>-->
<!--                &nbsp;--><?php //echo $AppUI->_("LBL_VALIDATION_DATE_CONTINGENCY_PROJECT")?><!--&nbsp; (--><?php //echo $projectEndDateUserFormat ?><!--)-->
<!--            </td>-->
<!--        </tr>-->
<!--        <tr>   -->
<!--            <td align="right" colspan="2">-->
<!--                <input type="button" class="button" value="--><?php //echo ucfirst($AppUI->_("LBL_SUBMIT")); ?><!--" onclick="sumTotalValue();submitIt();" />-->
<!--                -->
<!--                 <script> var targetScreenOnProject="/modules/costs/view_budget.php";</script>-->
<!--                --><?php //require_once (DP_BASE_DIR . "/modules/timeplanning/view/subform_back_button_project.php"); ?>
<!--            </td>-->
<!--        </tr>-->
<!--    </table>-->
<!--    <span class="span_mandatory">*</span> --><?php //echo $AppUI->_("Required Fields"); ?>
<?php
    exit();
?>