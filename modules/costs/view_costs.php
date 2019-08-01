<?php
if (!defined('DP_BASE_DIR')) {
    die('You should not access this file directly.');
}

$projectSelected = intval(dPgetParam($_GET, 'project_id'));
$project = new CProject();
$project->load($projectSelected);
$company_id = $project->project_company;
$compartionDateFunction= strtotime($project->project_start_date) != false && strtotime($project->project_end_date) != false;
$compartionEmptyFormat= $project->project_start_date!="0000-00-00 00:00:00" && $project->project_end_date != "0000-00-00 00:00:00";
if ($compartionDateFunction && $compartionEmptyFormat) {
    require_once DP_BASE_DIR . "/modules/costs/costs_functions.php";
    $cost_id = intval(dPgetParam($_GET, 'cost_id', 0));

    $perms = & $AppUI->acl();

    $q = new DBQuery;
    $q->clear();
    $q->addQuery('*');
    $q->addTable('costs');
    $q->addWhere('cost_project_id = ' . $projectSelected);

// check if this record has dependancies to prevent deletion
    $msg = '';
// load the record data
    $obj = null;
    if ((!db_loadObject($q->prepare(), $obj)) && ($cost_id > 0)) {
        $AppUI->setMsg('Estimative Costs');
        $AppUI->setMsg("invalidID", UI_MSG_ERROR, true);
        $AppUI->redirect();
    }

    /* Funcao para inserir na tabela de custos  */
    insertCostValues($projectSelected);

    $whereProject = '';
    if ($projectSelected != null) {
        $whereProject = ' and cost_project_id=' . $projectSelected;
    }

    /* transform date to dd/mm/yyyy */
    $date_begin = intval($obj->cost_date_begin) ? new CDate($obj->cost_date_begin) : null;
    $date_end = intval($obj->cost_date_end) ? new CDate($obj->cost_date_end) : null;
    $df = $AppUI->getPref('SHDATEFORMAT');

// Get humans estimatives
    $humanCost = getResources("Human", $whereProject);

// Get non humans estimatives
    $notHumanCost = getResources("Non-Human", $whereProject);
    ?>
<h4><?=$AppUI->_("5LBLCUSTO",UI_OUTPUT_HTML)?></h4>
<hr>
<div class="row">
    <div class="col-md-12 text-right">
        <button type="button" class="btn btn-sm btn-secondary" onclick="">
            <!-- /modules/costs/view_budget.php -->
            <?=$AppUI->_("LBL_PROJECT_BUDGET")?>
        </button>
    </div>
</div>

<!-- ############################## ESTIMATIVAS CUSTOS HUMANOS ############################################ -->
<br>
<div class="row">
    <div class="col-md-12">
        <div class="alert alert-secondary" role="alert">
            <h4 class="alert-heading"><?=$AppUI->_("Human Resource Estimative")?></h4>
            <?=$AppUI->_("LBL_COST_HUMAN_RESOURCE_HELP", UI_OUTPUT_JS)?>
            <hr>
            <small><?=$AppUI->_("LBL_RH_AUTOMATICALLY_ADDED_COST_BASELINE")?></small>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12 text-right">
        <button type="button" class="btn btn-sm btn-secondary" onclick="">
            <!-- ?m=companies&a=view&company_id=<?php //echo $company_id; ?>&rh_config=1&tab=3 -->
            <?=$AppUI->_("LBL_CONFIG_RH")?>
        </button>
    </div>
</div>

<?php
    foreach ($humanCost as $row) {
        /* transform date to dd/mm/yyyy */
        $date_begin = intval($row['cost_date_begin']) ? new CDate($row['cost_date_begin']) : null;
        $date_end = intval($row['cost_date_end']) ? new CDate($row['cost_date_end']) : null;
        ?>
        <div class="card inner-card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <h5>
                            <?=$row['cost_description']?>
                        </h5>
                    </div>
                    <div class="col-md-8">
                        <span>
                            <?=$AppUI->_('Date Begin')?>: <?=$date_begin ? $date_begin->format($df) : ''?> |
                        </span>
                        <span>
                            <?=$AppUI->_('Date End')?>: <?=$date_end ? $date_end->format($df) : ''?> |
                        </span>
                        <span>
                            <?=$AppUI->_('Hours/Month')?>: <?=$row['cost_quantity']?> |
                        </span>
                        <span>
                            <?=$AppUI->_('Hour Cost')?>: <?=dPgetConfig("currency_symbol") . number_format($row['cost_value_unitary'], 2, ',', '.')?> |
                        </span>
                        <span>
                            <?=$AppUI->_('Total Cost')?>: <?=dPgetConfig("currency_symbol") . number_format($row['cost_value_total'], 2, ',', '.')?>
                        </span>
                    </div>
                    <div class="col-md-1 text-right">
                        <div class="dropdown">
                            <a href="javascript:void(0)" class="link-primary" role="button" data-toggle="dropdown"
                               aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-bars"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink">
                                <a class="dropdown-item" href="javascript:void(0)" onclick="costs.hr.edit(<?=$row['cost_id']?>,<?=$projectSelected?>)">
                                    <i class="far fa-edit"></i>
                                    Alterar
                                </a>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <?php
        $sumH = $sumH + $row['cost_value_total'];
    }
?>
    <br>
    <p>
        <?=$AppUI->_("Subtotal Human Estimatives")?>: <?=dPgetConfig("currency_symbol") . number_format($sumH, 2, ',', '.')?>
    </p>

<!-- ############################## ESTIMATIVAS CUSTOS NAO HUMANOS ############################################ -->
<br>
<div class="row">
    <div class="col-md-12">
        <div class="alert alert-secondary" role="alert">
            <h4 class="alert-heading"><?=$AppUI->_("Non-Human Resource Estimative")?></h4>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12 text-right">
        <button type="button" class="btn btn-sm btn-secondary" onclick="">
            <!-- ?m=costs&a=addedit_costs_not_human&project_id=<?php //echo $projectSelected ?> -->
            <?=$AppUI->_("LBL_INCLUDE_NON_HUMAN_RESOURCE")?>
        </button>
    </div>
</div>


<?php
    foreach ($notHumanCost as $row) {
        /* transform date to dd/mm/yyyy */
        $date_begin = intval($row['cost_date_begin']) ? new CDate($row['cost_date_begin']) : null;
        $date_end = intval($row['cost_date_end']) ? new CDate($row['cost_date_end']) : null;
        ?>
        <div class="card inner-card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <h5>
                            <?=$row['cost_description']?>
                        </h5>
                    </div>
                    <div class="col-md-8">
                        <span>
                            <?=$AppUI->_('Date Begin')?>: <?=$date_begin ? $date_begin->format($df) : ''?> |
                        </span>
                        <span>
                            <?=$AppUI->_('Date End')?>: <?=$date_end ? $date_end->format($df) : ''?> |
                        </span>
                        <span>
                            <?=$AppUI->_('Quantity')?>: <?=$row['cost_quantity']?> |
                        </span>
                        <span>
                            <?=$AppUI->_('Unitary Cost')?>: <?=dPgetConfig("currency_symbol") . number_format($row['cost_value_unitary'], 2, ',', '.')?> |
                        </span>
                        <span>
                            <?=$AppUI->_('Total Cost')?>: <?=dPgetConfig("currency_symbol") . number_format($row['cost_value_total'], 2, ',', '.')?>
                        </span>
                    </div>
                    <div class="col-md-1 text-right">
                        <div class="dropdown">
                            <a href="javascript:void(0)" class="link-primary" role="button" data-toggle="dropdown"
                               aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-bars"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink">
                                <a class="dropdown-item" href="javascript:void(0)" onclick="contact.update(<? ?>)">
                                    <i class="far fa-edit"></i>
                                    Alterar
                                </a>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <?php
        $sumNH = $sumNH + $row['cost_value_total'];
    }
?>
<br>
<p>
    <?=$AppUI->_("Subtotal Not Human Estimatives")?>: <?=dPgetConfig("currency_symbol") . number_format($sumNH, 2, ',', '.')?>
</p>

<!-- MODAL EDIT HUMMAN COSTS -->
<div id="hrCostModal" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Alterar custo - Recurso humano</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="<?=$AppUI->_("LBL_CLOSE")?>">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body cost-modal">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal"><?=$AppUI->_("LBL_CLOSE")?></button>
                <button type="button" class="btn btn-primary btn-sm" onclick=""><?=$AppUI->_("LBL_SAVE")?></button>
            </div>
        </div>
    </div>
</div>

<script>
    var costs = {

        init: function () {

        },
        
        hr: {
            edit: function (costId, projectId) {
                $.ajax({
                    type: "get",
                    url: "?m=costs&template=addedit_costs&cost_id="+costId+"&project_id="+projectId
                }).done(function(response) {
                    var modal = $('#hrCostModal');
                    $('.cost-modal').html(response);
                    modal.modal();
                });
            }
        }
    };

    $(document).ready(costs.init);

</script>

<!--    <table align="center"  width="95%" border="0" cellpadding="3" cellspacing="3" class="tbl">-->
<!---->
<!--        <tr>-->
<!--            <th nowrap='nowrap' width='100%' colspan="7">-->
<!--                --><?php //echo $AppUI->_('Human Resource Estimative'); ?>
<!--            </th>-->
<!--        </tr>-->
<!--        <tr>-->
<!--            <th nowrap="nowrap" width="1%"></th>-->
<!--            <th nowrap="nowrap" width="20%">--><?php //echo $AppUI->_('Name'); ?><!--</th>-->
<!--            <th nowrap="nowrap">--><?php //echo $AppUI->_('Date Begin'); ?><!--</th>-->
<!--            <th nowrap="nowrap">--><?php //echo $AppUI->_('Date End'); ?><!--</th>-->
<!--            <th nowrap="nowrap" width="10%">--><?php //echo $AppUI->_('Hours/Month'); ?><!--</th>-->
<!--            <th nowrap="nowrap" width="15%">--><?php //echo $AppUI->_('Hour Cost'); ?><!--  &nbsp;(--><?php //echo dPgetConfig("currency_symbol") ?><!--)</th>-->
<!--            <th nowrap="nowrap" >--><?php //echo $AppUI->_("Total Cost"); ?><!--&nbsp;(--><?php //echo dPgetConfig("currency_symbol") ?><!--)-->
<!--            </th>-->
<!--        </tr>-->
<!--        --><?php
//        foreach ($humanCost as $row) {
//            /* transform date to dd/mm/yyyy */
//            $date_begin = intval($row['cost_date_begin']) ? new CDate($row['cost_date_begin']) : null;
//            $date_end = intval($row['cost_date_end']) ? new CDate($row['cost_date_end']) : null;
//            ?>
<!--            <tr>-->
<!--                <td nowrap="nowrap" align="center">-->
<!--                    <a href="index.php?m=costs&a=addedit_costs&cost_id=--><?php //echo ($row['cost_id']); ?><!--&project_id=--><?php //echo $projectSelected ?><!--">-->
<!--                        <img src="./modules/costs/images/stock_edit-16.png" border="0" width="12" height="12">-->
<!--                    </a>-->
<!--                </td>-->
<!--                <td nowrap="nowrap">--><?php //echo $row['cost_description']; ?><!--</td>-->
<!--                <td nowrap="nowrap">--><?php //echo $date_begin ? $date_begin->format($df) : ''; ?><!--</td>-->
<!--                <td nowrap="nowrap">--><?php //echo $date_end ? $date_end->format($df) : ''; ?><!--</td>-->
<!--                <td nowrap="nowrap" style="text-align: center">--><?php //echo $row['cost_quantity']; ?><!--</td>-->
<!--                <td nowrap="nowrap" style="text-align: right">--><?php //echo number_format($row['cost_value_unitary'], 2, ',', '.'); ?><!--</td>-->
<!--                <td nowrap="nowrap" style="text-align: right">--><?php //echo number_format($row['cost_value_total'], 2, ',', '.'); ?><!--</td>-->
<!--            </tr>-->
<!--            --><?php
//            $sumH = $sumH + $row['cost_value_total'];
//        }
//        ?>
<!--        <tr>-->
<!--            <td nowrap="nowrap" align="right" colspan="6" cellpadding="3"> <b>--><?php //echo $AppUI->_("Subtotal Human Estimatives"); ?><!-- &nbsp;(--><?php //echo dPgetConfig("currency_symbol") ?><!--):  </b> </td>-->
<!--            <td nowrap="nowrap" cellpadding="3" style="text-align: right"><b>--><?php //echo number_format($sumH, 2, ',', '.'); ?><!--</b></td>-->
<!--        </tr>-->
<!--    </table>-->
<!--    <table width="95%" align="center">-->
<!--        <tr>-->
<!--            <td>-->
<!--                <span style='color:red'>*</span>-->
<!--                <span style='color:black;font-size: 11px'>-->
<!--                    --><?php //echo $AppUI->_("LBL_RH_AUTOMATICALLY_ADDED_COST_BASELINE") ?>
<!--                </span>-->
<!--            </td>-->
<!--        </tr>-->
<!--    </table>-->

<!--    <br />-->
<!---->
<!--    <table align="center" width="95%">-->
<!--        <tr>-->
<!--            <td align="right">-->
<!--                <form action="?m=costs&a=addedit_costs_not_human&project_id=--><?php //echo $projectSelected ?><!--" method="post">-->
<!--                    <input type="submit" class="button" value="--><?php //echo $AppUI->_("LBL_INCLUDE_NON_HUMAN_RESOURCE", UI_OUTPUT_JS) ?><!--" />-->
<!--                </form>-->
<!--            </td>-->
<!--        </tr>-->
<!--    </table>-->

<!--    <table align="center" width="95%" border="0" cellpadding="3" cellspacing="3" class="tbl">-->
<!--        <tr>-->
<!--            <th nowrap='nowrap' width='100%' colspan="7">-->
<!--                --><?php //echo $AppUI->_('Non-Human Resource Estimative'); ?>
<!--            </th>-->
<!--        </tr>-->
<!--        <tr>-->
<!--            <th nowrap="nowrap" width="1%"></th>-->
<!--            <th nowrap="nowrap" width="20%">--><?php //echo $AppUI->_('Description'); ?><!--</th>-->
<!--            <th nowrap="nowrap">--><?php //echo $AppUI->_('Date Begin'); ?><!--</th>-->
<!--            <th nowrap="nowrap">--><?php //echo $AppUI->_('Date End'); ?><!--</th>-->
<!--            <th nowrap="nowrap" width="10%">--><?php //echo $AppUI->_('Quantity'); ?><!--</th>-->
<!--            <th nowrap="nowrap" width="15%">--><?php //echo $AppUI->_('Unitary Cost'); ?><!--  &nbsp;(--><?php //echo dPgetConfig("currency_symbol") ?><!--)</th>-->
<!--            <th nowrap="nowrap">--><?php //echo $AppUI->_('Total Cost'); ?><!-- &nbsp;(--><?php //echo dPgetConfig("currency_symbol") ?><!--)</th>-->
<!--        </tr>-->
<!--        --><?php
//        foreach ($notHumanCost as $row) {
//            /* transform date to dd/mm/yyyy */
//            $date_begin = intval($row['cost_date_begin']) ? new CDate($row['cost_date_begin']) : null;
//            $date_end = intval($row['cost_date_end']) ? new CDate($row['cost_date_end']) : null;
//            ?>
<!--            <tr>-->
<!--                <td nowrap="nowrap" align="center">-->
<!--                    <a href="index.php?m=costs&a=addedit_costs_not_human&cost_id=--><?php //echo($row['cost_id']); ?><!--&project_id=--><?php //echo $projectSelected ?><!--">-->
<!--                        <img src="./modules/costs/images/stock_edit-16.png" border="0" width="12" height="12">-->
<!--                    </a>-->
<!--                </td>-->
<!--                <td nowrap="nowrap">--><?php //echo $row['cost_description']; ?><!--</td>-->
<!--                <td nowrap="nowrap">--><?php //echo $date_begin ? $date_begin->format($df) : ''; ?><!--</td>-->
<!--                <td nowrap="nowrap">--><?php //echo $date_end ? $date_end->format($df) : ''; ?><!--</td>-->
<!--                <td nowrap="nowrap" style="text-align: center">--><?php //echo $row['cost_quantity']; ?><!--</td>-->
<!--                <td nowrap="nowrap" style="text-align: right">--><?php //echo number_format($row['cost_value_unitary'], 2, ',', '.'); ?><!--</td>-->
<!--                <td nowrap="nowrap" style="text-align: right">--><?php //echo number_format($row['cost_value_total'], 2, ',', '.'); ?><!--</td>-->
<!--            </tr>-->
            <?php
//            $sumNH = $sumNH + $row['cost_value_total'];
//        }
        ?>
<!--        <tr>-->
<!--            <td nowrap="nowrap" align="right" colspan="6" cellpadding="3"> <b>--><?php //echo $AppUI->_("Subtotal Not Human Estimatives"); ?><!-- &nbsp;(--><?php //echo dPgetConfig("currency_symbol") ?><!--): </b> </td>-->
<!--            <td nowrap="nowrap" cellpadding="3" style="text-align: right"><b>--><?php //echo number_format($sumNH, 2, ',', '.'); ?><!--</b></td>-->
<!--        </tr>-->
<!--    </table>-->
    <?php
} else {
    ?>
    <div>
    <?php echo $AppUI->_("LBL_COST_PLANNING_NEEDS_PROJECT_DATES"); ?>
    </div>
<?php
    }
?>
