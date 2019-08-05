<?php
if (!defined('DP_BASE_DIR')) {
    die('You should not access this file directly.');
}

require_once DP_BASE_DIR . "/modules/costs/costs_functions.php";

$cost_id = intval(dPgetParam($_GET, 'cost_id', 0));
$project_id = intval(dPgetParam($_GET, 'project_id', 0));

// check if this record has dependancies to prevent deletion
$msg = '';
$obj = new CCosts();
if ($cost_id > 0) {
    // check permissions for this record
    $canEdit = getPermission($m, 'edit', $cost_id);
    if (!(($canEdit && $cost_id) || ($canAuthor && !($cost_id)))) {
        $AppUI->redirect('m=public&a=access_denied');
    }

    $canDelete = $obj->canDelete($msg, $cost_id);
    $obj->load($cost_id);
} else {
    $cost_id = 0;
}

/* transform date to dd/mm/yyyy */
$date_begin = intval($obj->cost_date_begin) ? new CDate($obj->cost_date_begin) : null;
$date_end = intval($obj->cost_date_end) ? new CDate($obj->cost_date_end) : null;
$df = $AppUI->getPref('SHDATEFORMAT');

/* Get end date project */
$q = new DBQuery();
$q->addQuery('project_start_date,project_end_date');
$q->addTable('projects');
$q->addWhere("project_id = '$project_id'");
$datesProject = & $q->exec();
$dateTemp = substr($datesProject->fields['project_end_date'], 0, -9);
$dateEP = (string) $dateTemp;

$projectEndDate=new CDate($datesProject->fields["project_end_date"]);
$projectEndDateUserFormat=$projectEndDate->format($df);
?>
<!-- import the calendar script -->
<script type="text/javascript" src="<?php echo DP_BASE_URL; ?>/lib/calendar/calendar.js"></script>
<!-- import the language module -->
<script type="text/javascript" src="<?php echo DP_BASE_URL; ?>/lib/calendar/lang/calendar-<?php echo $AppUI->user_locale; ?>.js"></script>

<?php
    if ($cost_id != 0) {
        ?>
        <div class="alert alert-secondary" role="alert">
            <?=$AppUI->_('Name') . ': ' . dPformSafe($obj->cost_description)?>
        </div>
        <?php
    }
?>

<form name="nonHrCostForm" id="nonHrCostForm" method="post">
    <input type="hidden" name="dosql" value="do_costs_aed" />
    <input type="hidden" name="del" value="0" />
    <input type="hidden" name="cost_id" value="<?=$cost_id?>" />
    <input type="hidden" name="cost_project_id" value="<?=$project_id?>" />
    <input type="hidden" name="cost_type_id" value="1" />
    <input type="hidden" name="project_end_date" id="projectEndDate" value="<?=$dateEP?>" />

    <div class="form-group">
        <span class="required"></span>
        <?=$AppUI->_('requiredField');?>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="form-group">
                <label for="Name" class="required">
                    <?=$AppUI->_('Name')?>
                </label>
                <input type="text" name="cost_description" class="form-control form-control-sm" value="<?php echo $obj->cost_description ?>" />
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="Quantity" class="required">
                    <?=$AppUI->_('Quantity')?>
                </label>
                <input type="text" name="cost_quantity" class="form-control form-control-sm" value="<?php echo $obj->cost_quantity ?>" />
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label for="data_begin" class="required">
                    <?=$AppUI->_('Date Begin')?>
                </label>
                <input type="hidden" name="cost_date_begin" id="cost_date_begin"  value="<?=(($date_begin) ? $date_begin->format(FMT_TIMESTAMP_DATE) : '')?>"/>
                <input type="text" class="form-control form-control-sm datepicker-start" onblur="selectDateStart()" name="date_begin" id="date0" value="<?=(($date_begin) ? $date_begin->format($df) : '')?>" />

            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="data_end" class="required">
                    <?=$AppUI->_('Date End')?>
                </label>
                <input type="hidden" name="cost_date_end" id="cost_date_end"  value="<?=(($date_end) ? $date_end->format(FMT_TIMESTAMP_DATE) : '')?>"/>
                <input type="text" class="form-control form-control-sm datepicker-end" onblur="selectDateEnd()" name="date_end" id="date1" value="<?=(($date_end) ? $date_end->format($df) : '')?>" />

            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="cost_quantity" class="required">
                    <?=$AppUI->_('Unitary Value')?>
                </label>
                <input name="cost_value_unitary" class="form-control form-control-sm" id="cost_value_unitary" onblur="calcTotalCost()" value="<?=dPformSafe($obj->cost_value_unitary)?>" />
            </div>
        </div>
        <div class="col-md-12">
            <small><?=$AppUI->_("LBL_VALIDATION_DATE_CONTINGENCY_PROJECT")?>&nbsp; (<?=$projectEndDateUserFormat?>)</small>
        </div>
    </div>
    <hr>
    <span><b><?=$AppUI->_('Total Value'). ':</b> ' . dPgetConfig("currency_symbol") . '</span><span id="text_total">' . number_format($obj->cost_value_total, 2, ',', '.')?></span>
    <input type="hidden" name="cost_value_total"  id="cost_value_total" value="<?=dPformSafe($obj->cost_value_total)?>" />
    <br>
    <small><?=$AppUI->_("LBL_COST_NHR_RULE_OF_CALCULUS")?></small>
</form>
    <script>

        $(document).ready(function() {
            $( ".datepicker-start" ).datepicker({
                dateFormat: 'dd/mm/yy',
                onSelect: selectDateStart
            });

            $( ".datepicker-end" ).datepicker({
                dateFormat: 'dd/mm/yy',
                onSelect: selectDateEnd
            });
        });

        $("#cost_value_unitary").mask(
            "000.000.000,00",
            {
                reverse: true
            }
        );

        function selectDateStart() {
            var dateArr = $('#date0').val().split('/');
            $('#cost_date_begin').val(dateArr[2]+dateArr[1]+dateArr[0]);
        }

        function selectDateEnd() {
            var dateArr = $('#date1').val().split('/');
            $('#cost_date_end').val(dateArr[2]+dateArr[1]+dateArr[0]);
        }

        function calcTotalCost() {
            var qtd =  $('input[name=cost_quantity]').val();
            var uniValue = $('#cost_value_unitary').val();
            if (!qtd || !uniValue) {
                return;
            }

            var floatValue = uniValue.replace('.', '');
            floatValue = floatValue.replace(',', '.');
            floatValue = parseFloat(floatValue);

            qtd = parseInt(qtd);
            var total = qtd * floatValue;

            $('#cost_value_total').val(total);
            var stringTotal = new String(total);
            stringTotal = stringTotal.replace('.', ',');
            $('#text_total').html(stringTotal+',00');
        }

    </script>
<?php
    exit();
?>