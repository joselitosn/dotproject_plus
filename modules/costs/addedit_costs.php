<?php
if (!defined('DP_BASE_DIR')) {
    die('You should not access this file directly.');
}
require_once DP_BASE_DIR . "/modules/costs/costs_functions.php";

$cost_id = intval(dPgetParam($_GET, 'cost_id', 0));
$project_id = intval(dPgetParam($_GET, 'project_id', 0));

// check permissions for this record
$canEdit = getPermission($m, 'edit', $cost_id);
if (!(($canEdit && $cost_id) || ($canAuthor && !($cost_id)))) {
    $AppUI->redirect('m=public&a=access_denied');
}

$q = new DBQuery();
$q->addQuery('*');
$q->addTable('costs');
$q->addWhere('cost_id = ' . $cost_id);

// check if this record has dependancies to prevent deletion
$msg = '';
$obj = new CCosts();
$canDelete = $obj->canDelete($msg, $cost_id);

// load the record data
$obj = null;
if (!db_loadObject($q->prepare(), $obj) && ($cost_id > 0)) {
    $AppUI->setMsg('Estimative Costs');
    $AppUI->setMsg("invalidID", UI_MSG_ERROR, true);
    $AppUI->redirect();
}

/* transform date to dd/mm/yyyy */
$date_begin = intval($obj->cost_date_begin) ? new CDate($obj->cost_date_begin) : null;
$date_end = intval($obj->cost_date_end) ? new CDate($obj->cost_date_end) : null;
$df = $AppUI->getPref('SHDATEFORMAT');

/* Get date end project */
$q->clear();
$q->addQuery('project_start_date,project_end_date');
$q->addTable('projects');
$q->addWhere("project_id = '$project_id'");
$datesProject = & $q->exec();
$dateTemp = substr($datesProject->fields['project_end_date'], 0, -9);
$dateEP = (string) $dateTemp;

$projectEndDate=new CDate($datesProject->fields["project_end_date"]);
$projectEndDateUserFormat=$projectEndDate->format($df);

?>
<div class="alert alert-secondary" role="alert">
    <?=$AppUI->_('Name') . ': ' . dPformSafe($obj->cost_description)?>
</div>

<form name="hrCostsForm" id="hrCostsForm" method="post">
    <input type="hidden" name="dosql" value="do_costs_aed" />
    <input type="hidden" name="del" value="0" />
    <input type="hidden" name="cost_id" value="<?=$cost_id?>" />
    <input type="hidden" name="cost_project_id" value="<?=$project_id?>" />
    <input type="hidden" name="cost_type_id" value="0" />
    <input type="hidden" name="project_end_date" id="projectEndDate" value="<?=$dateEP?>" />

    <div class="form-group">
        <span class="required"></span>
        <?=$AppUI->_('requiredField');?>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label for="data_begin" class="required">
                    <?=$AppUI->_('Date Begin')?>
                </label>
                <input type="hidden" name="cost_date_begin" id="cost_date_begin"  value="<?=(($date_begin) ? $date_begin->format(FMT_TIMESTAMP_DATE) : '')?>" />
                <input type="text" class="form-control form-control-sm datepicker-start" name="date_begin" id="date0" value="<?=(($date_begin) ? $date_begin->format($df) : '')?>" />
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="data_end" class="required">
                    <?=$AppUI->_('Date End')?>
                </label>
                <input type="hidden" name="cost_date_end" id="cost_date_end"  value="<?=(($date_end) ? $date_end->format(FMT_TIMESTAMP_DATE) : '')?>" />
                <input type="text" class="form-control form-control-sm datepicker-end" name="date_end" id="date1" value="<?=(($date_end) ? $date_end->format($df) : '')?>" />
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="cost_quantity" class="required">
                    <?=$AppUI->_('Hours per Month')?>
                </label>
                <input name="cost_quantity" class="form-control form-control-sm" id="cost_quantity" onblur="sumTotalValue()" value="<?=dPformSafe($obj->cost_quantity)?>" />
            </div>
        </div>
        <div class="col-md-12">
            <small><?=$AppUI->_("LBL_VALIDATION_DATE_CONTINGENCY_PROJECT")?>&nbsp; (<?=$projectEndDateUserFormat?>)</small>
        </div>
    </div>
    <hr>
    <span><b><?=$AppUI->_('Unitary Value'). ':</b> ' . dPgetConfig("currency_symbol") . '</span><span id="text_unit_value">' . dPformSafe($obj->cost_value_unitary)?></span>
    <input type="hidden" name="cost_value_unitary"  id="cost_value_unitary" value="<?=dPformSafe($obj->cost_value_unitary)?>" />
    <br>
    <span><b><?=$AppUI->_('Total Value'). ':</b> ' . dPgetConfig("currency_symbol") . '</span><span id="text_total">' . dPformSafe($obj->cost_value_total)?></span>
    <input type="hidden" name="cost_value_total"  id="cost_value_total" value="<?=dPformSafe($obj->cost_value_total)?>" />
    <br>
    <small><?=$AppUI->_("LBL_COST_HR_RULE_OF_CALCULUS")?></small>
</form>
<script>

    $(document).ready(function() {
        $( ".datepicker-start" ).datepicker({
            dateFormat: 'dd/mm/yy',
            onSelect: function () {
                var dateArr = $(this).val().split('/');
                $('#cost_date_begin').val(dateArr[2]+dateArr[1]+dateArr[0]);
            }
        });

        $( ".datepicker-end" ).datepicker({
            dateFormat: 'dd/mm/yy',
            onSelect: function () {
                var dateArr = $(this).val().split('/');
                $('#cost_date_end').val(dateArr[2]+dateArr[1]+dateArr[0]);
            }
        });
    });

    function monthDiff(d1, d2) {
        var months;
        months = (d2.getFullYear() - d1.getFullYear()) * 12;
        months -= d1.getMonth() + 1;
        months += d2.getMonth();
        return months;
    }

    function sumTotalValue(){
        console.log('Atualizando custo total...');
        var VU = document.getElementById('cost_value_unitary').value;
        var HM = document.getElementById('cost_quantity').value;
        var date1 = document.getElementById('cost_date_begin').value;
        var date2 = document.getElementById('cost_date_end').value;
        var total = 0;


        var year1 =  date1.substring(0,4);
        var month1 =  date1.substring(4,6);
        var day1 = date1.substring(6);

        var year2 =  date2.substring(0,4);
        var month2 =  date2.substring(4,6);
        var day2 = date2.substring(6);

        var diffMonths = monthDiff(new Date(year1,month1,day1),new Date(year2,month2,day2));

        var diff_date = new Date(year2,month2,day2) - new Date(year1,month1,day1) ;
        var num_months = (diff_date % 31536000000)/2628000000;

        if(diffMonths < 0)
            total = VU * HM;
        else
            total = (VU * HM) * (Math.floor(num_months)+1) + '.00';

        document.getElementById("cost_value_total").value = total;
        document.getElementById("text_total").innerHTML= total;
    }
</script>
<?php
    exit();
?>