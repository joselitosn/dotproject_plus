<?php
if (!defined("DP_BASE_DIR")) {
    die("You should not access this file directly.");
}
$project_id = intval(dPgetParam($_GET, "project_id", 0));
?>
<script src="./modules/risks/ear.js"></script>

<?php
require_once DP_BASE_DIR . "/modules/risks/risks_management_plan.class.php";
require_once (DP_BASE_DIR . "/modules/risks/controller_wbs_items.class.php");
$obj = new CRisksManagementPlan();
$q = new DBQuery();
$q->addQuery("*");
$q->addTable("risks_management_plan");
$q->addWhere("project_id = " . $project_id);
if (!db_loadObject($q->prepare(), $obj) && ($project_id >= 0)) {
    $obj = new CRisksManagementPlan();
}
$obj->loadDefaultValues();
?>

<form name="riskManagementPlanForm">
    <input type="hidden" name="dosql" value="do_risks_management_plan" />
    <input type="hidden" name="del" value="0" />
    <input type="hidden" name="project_id" value="<?php echo $project_id; ?>" />
    <input type="hidden" name="risk_plan_id" value="<?php echo $obj->risk_plan_id; ?>" />

    <div class="form-group">
        <span class="required"></span>
        <?=$AppUI->_('requiredField');?>
    </div>

    <div class="alert alert-secondary" role="alert">
        <?=$AppUI->_("LBL_PROBABILITY_IMPACT_CONFIGURATION")?>
    </div>

    <div class="card">
        <div class="card-header"><?=$AppUI->_("LBL_PROBABILITY")?></div>
        <div class="card-body">

            <div class="form-group">
                <label for="probability_super_low">
                    <?=$AppUI->_("LBL_SUPER_LOW_F")?>
                </label>
                <input type="text" class="form-control form-control-sm" maxlength="100" name="probability_super_low" value="<?=$obj->probability_super_low?>" />
            </div>

            <div class="form-group">
                <label for="probability_low">
                    <?=$AppUI->_("LBL_LOW_F")?>
                </label>
                <input type="text" class="form-control form-control-sm" maxlength="100" name="probability_low" value="<?=$obj->probability_low?>" />
            </div>

            <div class="form-group">
                <label for="probability_medium">
                    <?=$AppUI->_("LBL_MEDIUM_F")?>
                </label>
                <input type="text" class="form-control form-control-sm" maxlength="100" name="probability_medium" value="<?=$obj->probability_medium?>" />
            </div>

            <div class="form-group">
                <label for="probability_high">
                    <?=$AppUI->_("LBL_HIGH_F")?>
                </label>
                <input type="text" class="form-control form-control-sm" maxlength="100" name="probability_high" value="<?=$obj->probability_high?>" />
            </div>

            <div class="form-group">
                <label for="probability_super_high">
                    <?=$AppUI->_("LBL_SUPER_HIGH_F")?>
                </label>
                <input type="text" class="form-control form-control-sm" maxlength="100" name="probability_super_high" value="<?=$obj->probability_super_high?>" />
            </div>

        </div>
    </div>

    <br>

    <div class="card">
        <div class="card-header"><?=$AppUI->_("LBL_IMPACT")?></div>
        <div class="card-body">

            <div class="form-group">
                <label for="impact_super_low">
                    <?=$AppUI->_("LBL_SUPER_LOW_M")?>
                </label>
                <input type="text" class="form-control form-control-sm" maxlength="100" name="impact_super_low" value="<?=$obj->impact_super_low?>" />
            </div>

            <div class="form-group">
                <label for="impact_low">
                    <?=$AppUI->_("LBL_LOW_F")?>
                </label>
                <input type="text" class="form-control form-control-sm" maxlength="100" name="impact_low" value="<?=$obj->impact_low?>" />
            </div>

            <div class="form-group">
                <label for="impact_medium">
                    <?=$AppUI->_("LBL_MEDIUM_F")?>
                </label>
                <input type="text" class="form-control form-control-sm" maxlength="100" name="impact_medium" value="<?=$obj->impact_medium?>" />
            </div>

            <div class="form-group">
                <label for="impact_high">
                    <?=$AppUI->_("LBL_HIGH_F")?>
                </label>
                <input type="text" class="form-control form-control-sm" maxlength="100" name="impact_high" value="<?=$obj->impact_high?>" />
            </div>

            <div class="form-group">
                <label for="impact_super_high">
                    <?=$AppUI->_("LBL_SUPER_HIGH_F")?>
                </label>
                <input type="text" class="form-control form-control-sm" maxlength="100" name="impact_super_high" value="<?=$obj->impact_super_high?>" />
            </div>

        </div>
    </div>

    <br>

    <div class="alert alert-secondary" role="alert">
        <?=$AppUI->_("LBL_RISK_MATRIX")?>
    </div>


    <!-- Matriz de probabilidade e impacto -->
    <?php

    //options for the expositon factor
    function insertMatrixOptions($value) {
        global $AppUI;
        $selectedLow = $value == 0 ? "selected=\"true\"" : "";
        $selectedMedium = $value == 1 ? "selected=\"true\"" : "";
        $selectedHigh = $value == 2 ? "selected=\"true\"" : "";
        echo "<option value=\"0\" $selectedLow>" . $AppUI->_("LBL_LOW_F") . "</option>";
        echo "<option value=\"1\" $selectedMedium>" . $AppUI->_("LBL_MEDIUM_F") . "</option>";
        echo "<option value=\"2\" $selectedHigh>" . $AppUI->_("LBL_HIGH_F") . "</option>";
    }
    ?>

    <table class="table table-sm table-bordered" id="risk_matrix">
        <tr>
            <th colspan="2" rowspan="2">&nbsp;</th>
            <th colspan="5" class="text-center"><?=$AppUI->_("LBL_PROBABILITY")?></th>
        </tr>
        <tr class="table-secondary">
            <th class="text-left"><?=$AppUI->_("LBL_SUPER_LOW_M")?></th>
            <th class="text-left"><?=$AppUI->_("LBL_LOW_M")?></th>
            <th class="text-left"><?=$AppUI->_("LBL_MEDIUM_M")?></th>
            <th class="text-left"><?=$AppUI->_("LBL_HIGH_M")?></th>
            <th class="text-left"><?=$AppUI->_("LBL_SUPER_HIGH_M")?></th>
        </tr>
        <tr>
            <th rowspan="6" width="15" class="align-middle"><?=$AppUI->_("LBL_IMPACT")?></th>
        </tr>
        <?php
        $superlow_superlow = $obj->matrix_superlow_superlow;
        $superlow_low = $obj->matrix_superlow_low;
        $superlow_medium = $obj->matrix_superlow_medium;
        $superlow_high = $obj->matrix_superlow_high;
        $superlow_superhigh = $obj->matrix_superlow_superhigh;
        ?>
        <tr>
            <th class="table-secondary text-right"><?=$AppUI->_("LBL_SUPER_LOW_F")?></th>
            <td>
                <select name="matrix_superlow_superlow" class="form-control form-control-sm select-2">
                    <?php insertMatrixOptions($superlow_superlow)?>
                </select>
            </td>
            <td>
                <select name="matrix_superlow_low" class="form-control form-control-sm select-2">
                    <?php insertMatrixOptions($superlow_low)?>
                </select>
            </td>
            <td>
                <select name="matrix_superlow_medium" class="form-control form-control-sm select-2">
                    <?php insertMatrixOptions($superlow_medium)?>
                </select>
            </td>
            <td>
                <select name="matrix_superlow_high" class="form-control form-control-sm select-2">
                    <?php insertMatrixOptions($superlow_high)?>
                </select>
            </td>
            <td>
                <select name="matrix_superlow_superhigh" class="form-control form-control-sm select-2">
                    <?php insertMatrixOptions($superlow_superhigh)?>
                </select>
            </td>
        </tr>

        <?php
        $low_superlow = $obj->matrix_low_superlow;
        $low_low = $obj->matrix_low_low;
        $low_medium = $obj->matrix_low_medium;
        $low_high = $obj->matrix_low_high;
        $low_superhigh = $obj->matrix_low_superhigh;
        ?>
        <tr>
            <th class="table-secondary text-right"><?=$AppUI->_("LBL_LOW_F")?></th>
            <td>
                <select name="matrix_low_superlow" class="form-control form-control-sm select-2">
                    <?php insertMatrixOptions($low_superlow)?>
                </select>
            </td>
            <td>
                <select name="matrix_low_low" class="form-control form-control-sm select-2">
                    <?php insertMatrixOptions($low_low)?>
                </select>
            </td>
            <td>
                <select name="matrix_low_medium" class="form-control form-control-sm select-2">
                    <?php insertMatrixOptions($low_medium)?>
                </select>
            </td>
            <td>
                <select name="matrix_low_high" class="form-control form-control-sm select-2">
                    <?php insertMatrixOptions($low_high)?>
                </select>
            </td>
            <td>
                <select name="matrix_low_superhigh" class="form-control form-control-sm select-2">
                    <?php insertMatrixOptions($low_superhigh)?>
                </select>
            </td>
        </tr>
        <?php
        $medium_superlow = $obj->matrix_medium_superlow;
        $medium_low = $obj->matrix_medium_low;
        $medium_medium = $obj->matrix_medium_medium;
        $medium_high = $obj->matrix_medium_high;
        $medium_superhigh = $obj->matrix_medium_superhigh;
        ?>

        <tr>
            <th class="table-secondary text-right"><?=$AppUI->_("LBL_MEDIUM_F")?></th>
            <td>
                <select name="matrix_medium_superlow" class="form-control form-control-sm select-2">
                    <?php insertMatrixOptions($medium_superlow)?>
                </select>
            </td>
            <td>
                <select name="matrix_medium_low" class="form-control form-control-sm select-2">
                    <?php insertMatrixOptions($medium_low)?>
                </select>
            </td>
            <td>
                <select name="matrix_medium_medium" class="form-control form-control-sm select-2">
                    <?php insertMatrixOptions($medium_medium)?>
                </select>
            </td>
            <td>
                <select name="matrix_medium_high" class="form-control form-control-sm select-2">
                    <?php insertMatrixOptions($medium_high)?>
                </select>
            </td>
            <td>
                <select name="matrix_medium_superhigh" class="form-control form-control-sm select-2">
                    <?php insertMatrixOptions($medium_superhigh)?>
                </select>
            </td>
        </tr>

        <?php
        $high_superlow = $obj->matrix_high_superlow;
        $high_low = $obj->matrix_high_low;
        $high_medium = $obj->matrix_high_medium;
        $high_high = $obj->matrix_high_high;
        $high_superhigh = $obj->matrix_high_superhigh;
        ?>

        <tr>
            <th class="table-secondary text-right"><?=$AppUI->_("LBL_HIGH_F")?></th>
            <td>
                <select name="matrix_high_superlow" class="form-control form-control-sm select-2">
                    <?php insertMatrixOptions($high_superlow)?>
                </select>
            </td>
            <td>
                <select name="matrix_high_low" class="form-control form-control-sm select-2">
                    <?php insertMatrixOptions($high_low)?>
                </select>
            </td>
            <td>
                <select name="matrix_high_medium" class="form-control form-control-sm select-2">
                    <?php insertMatrixOptions($high_medium)?>
                </select>
            </td>
            <td>
                <select name="matrix_high_high" class="form-control form-control-sm select-2">
                    <?php insertMatrixOptions($high_high)?>
                </select>
            </td>
            <td>
                <select name="matrix_high_superhigh" class="form-control form-control-sm select-2">
                    <?php insertMatrixOptions($high_superhigh)?>
                </select>
            </td>
        </tr>

        <?php
        $superhigh_superlow = $obj->matrix_superhigh_superlow;
        $superhigh_low = $obj->matrix_superhigh_low;
        $superhigh_medium = $obj->matrix_superhigh_medium;
        $superhigh_high = $obj->matrix_superhigh_high;
        $superhigh_superhigh = $obj->matrix_superhigh_superhigh;
        ?>

        <tr>
            <th class="table-secondary text-right"><?=$AppUI->_("LBL_SUPER_HIGH_F")?></th>
            <td>
                <select name="matrix_superhigh_superlow" class="form-control form-control-sm select-2">
                    <?php insertMatrixOptions($superhigh_superlow)?>
                </select>
            </td>
            <td>
                <select name="matrix_superhigh_low" class="form-control form-control-sm select-2">
                    <?php insertMatrixOptions($superhigh_low)?>
                </select>
            </td>
            <td>
                <select name="matrix_superhigh_medium" class="form-control form-control-sm select-2">
                    <?php insertMatrixOptions($superhigh_medium)?>
                </select>
            </td>
            <td>
                <select name="matrix_superhigh_high" class="form-control form-control-sm select-2">
                    <?php insertMatrixOptions($superhigh_high)?>
                </select>
            </td>
            <td>
                <select name="matrix_superhigh_superhigh" class="form-control form-control-sm select-2">
                    <?php insertMatrixOptions($superhigh_superhigh)?>
                </select>
            </td>
        </tr>
    </table>

    <br>

    <div class="alert alert-secondary" role="alert">
        <?=$AppUI->_("LBL_RISKS_MONITORING_AND_CONTROLLING_DEFINITIONS")?>
    </div>

    <div class="form-group">
        <label for="risk_contengency_reserve_protocol" class="required">
            <?=$AppUI->_("LBL_CONTINGENCY_RESERVE_PROTOCOL")?>
        </label>
        <textarea name="risk_contengency_reserve_protocol" rows="3" class="form-control form-control-sm"><?=$obj->risk_contengency_reserve_protocol?></textarea>
    </div>

    <div class="form-group">
        <label for="risk_revision_frequency" class="required">
            <?=$AppUI->_("LBL_RISK_REVISION_FREQUENCY")?>
        </label>
        <input type="text" name="risk_revision_frequency" maxlength="3" class="form-control form-control-sm" value="<?=$obj->risk_revision_frequency?>" />
    </div>

    <br>

    <div class="alert alert-secondary" role="alert">
        <?=$AppUI->_("LBL_RISK_BREAKDOWN_STRUCTURE")?>
    </div>

    <input name="eap_items_ids" id="eap_items_ids" type="hidden" />
    <input type="hidden" name="items_ids_to_delete" id="items_ids_to_delete" value="" />

    <div class="form-group">
        <button type="button" class="btn btn-sm btn-secondary" onclick="addItem('', '', '', '')"><?=$AppUI->__("LBL_ADD")?></button>
    </div>

    <table class="table table-sm table-bordered" id="tb_eap">
        <tr class="table-secondary">
            <th width="5%"><?=$AppUI->_("LBL_ID")?> </th>
            <th width="6%"><?=$AppUI->_("LBL_ORDER")?></th>
            <th width="6%"><?=$AppUI->_("LBL_IDENTATION")?></th>
            <th width="78%"><?=$AppUI->_("LBL_EAR_ITEM")?></th>
            <th width="5%"> &nbsp</th>
        </tr>
    </table>
    <?php
    $controllerWBSItem = new ControllerWBSItem();
    $items = $controllerWBSItem->getWBSItems($project_id);
    foreach ($items as $item) {
        echo '<script>addItem(' . $item->getId() . ',"' . $item->getName() . '",0,"' . $item->getIdentation() . '");</script>';
    }
    ?>

    <!-- Insert deafult values for RBS when it is a new risk managenment plan -->
    <?php
    if (sizeof($items) == 0 && $obj->risk_plan_id == "") {
        ?>
        <script>addItem(1,"Riscos",0,"");</script>
        <script>addItem(2,"Interno",0,"&nbsp;&nbsp;&nbsp;");</script>
        <script>addItem(3,"Organizacional",0,"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;");</script>
        <script>addItem(4,"Tecnológico",0,"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;");</script>
        <script>addItem(5,"Externo",0,"&nbsp;&nbsp;&nbsp;");</script>
        <script>addItem(6,"Político",0,"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;");</script>
        <script>addItem(7,"Catastrofe",0,"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;");</script>
        <?php
    }
    ?>
</form>

<script>
    $(document).ready(function() {

        $(".select-2").select2({
            allowClear: false,
            width: '100%',
            placeholder: "",
            theme: "bootstrap",
            dropdownParent: $("#risk_matrix")
       });

        $('#riskManagementPlanModal_SaveBtn').on('click', function () {
            saveEAP();

            var protocol = $('textarea[name=risk_contengency_reserve_protocol]').val();
            var frequency = $('input[name=risk_revision_frequency]').val();

            var msg = [];
            var err = false;

            if (!protocol) {
                err = true;
                msg.push('Preencha o protocolo para aplicação da reserva de contingência');
            }

            if (!frequency) {
                err = true;
                msg.push('Preencha a frequência para revisão dos riscos');
            }

            if (err) {
                $.alert({
                    title: "Erro",
                    content: msg.join('<br>')
                });
                return;
            }
            $.ajax({
                method: 'POST',
                url: "?m=risks",
                data: $("form[name=riskManagementPlanForm]").serialize(),
                success: function(resposta) {
                    $.alert({
                        title: "Sucesso",
                        content: resposta,
                        onClose: function() {
                            $('#riskManagementPlanModal').modal('hide');
                        }
                    });
                },
                error: function(resposta) {
                    $.alert({
                        title: "Erro",
                        content: "Algo deu errado"
                    });
                }
            });
        });
    });

</script>
<?php
    exit();
?>