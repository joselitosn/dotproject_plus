<?php
if (!defined('DP_BASE_DIR')) {
    die('You should not access this file directly.');
}
$project_id = dPgetParam($_GET, 'project_id', 0);

require_once (DP_BASE_DIR . "/modules/monitoringandcontrol/control/controller_util.class.php");

$controllerUtil = new ControllerUtil();
?>
<script src="./modules/monitoringandcontrol/js/baseline.js"></script>

<form method="post" name="form_baseline" id="form_baseline">
    <input name="dosql" type="hidden" value="do_baseline_aed" />
    <input name="project_id" type="hidden" id="project_id" value="<?=$project_id?>">
    <input name="user" type="hidden" id="user" value="1">
    <input  type="hidden" name="acao" value="insert"  />

    <div class="form-group">
        <span class="required"></span>
        <?=$AppUI->_('requiredField');?>
    </div>

    <div class="row">
        <div class="col-md-7">
            <div class="form-group">
                <label for="baseline_name" class="required">
                    <?php echo $AppUI->_("LBL_NOME"); ?>
                </label>
                <input type="text" class="form-control form-control-sm" name="nmBaseline" maxlength="20" id="nmBaseline" />
            </div>
        </div>
        <div class="col-md-5">
            <div class="form-group">
                <label for="baseline_version" class="required">
                    <?php echo $AppUI->_("LBL_VERSAO"); ?>
                </label>
                <input type="text" class="form-control form-control-sm" name="nmVersao" maxlength="20" id="nmVersao" />
            </div>
        </div>
    </div>

    <div class="form-group">
        <label for="baseline_obs">
            <?php echo $AppUI->_("LBL_OBSERVACAO"); ?>
        </label>
        <textarea class="form-control form-control-sm" name="dsObservacao" rows="3" maxlength="200"></textarea>
    </div>
</form>

<?php
    exit();
?>