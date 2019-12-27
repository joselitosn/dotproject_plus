<?php
if (!defined('DP_BASE_DIR')) {
    die('You should not access this file directly.');
}

$budget_id = intval(dPgetParam($_GET, 'budget_id', 0));
// check permissions for this record
$canEdit = getPermission($m, 'edit', $budget_id);
if (!(($canEdit && $budget_id) || ($canAuthor && !($budget_id)))) {
    $AppUI->redirect('m=public&a=access_denied');
}

$q = new DBQuery();
$q->addQuery('*');
$q->addTable('budget');
$q->addWhere('budget_id = ' . $budget_id);
//$project_id = $q->loadList();
// check if this record has dependancies to prevent deletion
$msg = '';
// load the record data
$obj = null;
if (!db_loadObject($q->prepare(), $obj) && ($budget_id > 0)) {
    $AppUI->setMsg('Budget');
    $AppUI->setMsg("invalidID", UI_MSG_ERROR, true);
    $AppUI->redirect();
}
?>
<script language="javascript">

    function budgetTotal(){
        var management = document.getElementById('budget_reserve_management').value;
        var subtotal = <?php echo $obj->budget_sub_total ?>;
        var total = (management/100) * subtotal;
        total = total + subtotal;

        document.getElementById('budget_total').value = total;
        document.getElementById('text_total').innerHTML = total;
    }
</script>

<form name="managReserveForm" id="managReserveForm" method="post">
    <input type="hidden" name="dosql" value="do_budget_aed" />
    <input type="hidden" name="project_id" value="<?php echo $_GET["project_id"]; ?>" />
    <input type="hidden" name="del" value="0" />
    <input type="hidden" name="budget_id" value="<?php echo $budget_id; ?>" />

    <div class="form-group">
        <span class="required"></span>
        <?=$AppUI->_('requiredField');?>
    </div>

    <div class="row">
        <div class="col-md-3">
            <div class="form-group">
                <label for="Management Reserve" class="required">
                    <?=$AppUI->_('Management Reserve')?> (%)
                </label>
                <input name="budget_reserve_management"
                   class="form-control form-control-sm "
                   id="budget_reserve_management"
                   onblur="budgetTotal()"
                   value="<?=dPformSafe($obj->budget_reserve_management)?>" />
            </div>
        </div>
    </div>

    <hr>
    <span><b><?=$AppUI->_('SubTotal'). ':</b> ' . dPgetConfig("currency_symbol") . '</span><span id="text_subtotal">' . dPformSafe($obj->budget_sub_total)?></span>
    <input type="hidden" name="budget_sub_total"  id="budget_sub_total" value="<?=dPformSafe($obj->budget_sub_total)?>" />
    <br>
    <span><b><?=$AppUI->_('Total Budget'). ':</b> ' . dPgetConfig("currency_symbol") . '</span><span id="text_total">' . dPformSafe($obj->budget_total)?></span>
    <input type="hidden" name="budget_total"  id="budget_total" value="<?=dPformSafe($obj->budget_total)?>" />
</form>
<?php
    exit();
?>


