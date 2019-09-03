<?php
if (!defined('DP_BASE_DIR')) {
    die('You should not access this file directly.');
}
$frequencyId = $_GET['frequency_id'];

$hasDate = '';
$obj = null;
if ($frequencyId) {
    $q = new DBQuery();
    $q->addQuery("*");
    $q->addTable("communication_frequency");
    $q->addWhere("communication_frequency_id=".$frequencyId);
    db_loadObject($q->prepare(), $obj);

    $hasDate = $obj->communication_frequency_hasdate == 'Sim' ? ' checked' : '';
}


?>
<form name="frequencyForm">
    <input type="hidden" name="dosql" value="do_frequency_aed" />
    <input type="hidden" name="del" value="0" />        
    <input type="hidden" name="frequency_id" value="<?=$frequencyId?>" />

    <div class="form-group">
        <span class="required"></span>
        <?=$AppUI->_('requiredField');?>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="form-group">
                <label for="communication_frequency" class="required">
                    Descrição da freqência
                </label>
                <input type="text" maxlength="255" class="form-control form-control-sm" name="communication_frequency" value="<?=$obj->communication_frequency?>" />
            </div>
        </div>
        <div class="col-md-4">
            <label>&nbsp;</label>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="communication_frequency_showdate" <?=$hasDate?> />
                <label class="form-check-label" for="communication_frequency_showdate">
                    <?php echo $AppUI->_("LBL_SHOW_DATE")?>
                </label>
            </div>
        </div>
    </div>
</form>
<?php
    exit();
?>
