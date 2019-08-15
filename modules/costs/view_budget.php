<?php
if (!defined('DP_BASE_DIR')) {
    die('You should not access this file directly.');
}
require_once DP_BASE_DIR . "/modules/costs/cost_baseline_parts/cost_baseline_setup.php";
?>

<!-- ############################## ESTIMATIVAS CUSTOS HUMANOS ############################################ -->

<table class="table table-sm table-bordered table-responsive text-center">
    <thead class="thead-dark">
    <?php require_once DP_BASE_DIR . "/modules/costs/cost_baseline_parts/cost_baseline_initiated_header.php"; ?>
        <tr>
            <th width="15%"><?php echo $AppUI->_('Item'); ?></th>
            <?php
            for ($i = 0; $i <= $meses; $i++) {
                $mes = $monthStartProject;
                $monthStartProject++;
                if ($mes == 12)
                    $monthStartProject = 1;
                ?>
                <th>
                    <?php echo strlen($mes) < 2 ? "0" . $mes : $mes; ?>
                </th>
                <?php
                $counter++;
            }
            ?>
            <th width="15%"><?php echo $AppUI->_('Total Cost'); ?> (<?php echo dPgetConfig("currency_symbol") ?>)</th>
            <th></th>
        </tr>
    </thead>
    <tr >
        <td colspan="<?php echo $meses + 3 ?>"><!-- + 3 is because of the <td> used for labels that are unecessary for this <tr>   -->
            <b><?php echo $AppUI->_('HUMAN RESOURCE ESTIMATIVE'); ?></b>
        </td>
    </tr>

    <?php 
        $sumH=0;
        foreach ($humanCost as $row) {
        ?>
        <tr>
            <td width="20%"><?php echo $row['cost_description']; ?></td>
            <?php
            //The function costsBudget prints the <td> tags for every month.
            $mtz = costsBudget($meses, $c, $row, substr($datesProject->fields['project_start_date'], 5, -12), substr($datesProject->fields['project_end_date'], 5, -12), $mtz,$monthsYearsIndex);
            $c++;
            ?>
            <td class="text-right"><?php echo number_format($row['cost_value_total'], 2, ',', '.'); ?></td>
        </tr>
        <?php
        $sumH = $sumH + $row['cost_value_total'];
    }
    ?>
    <tr>
        <td nowrap="nowrap"><b><?php echo $AppUI->_("Subtotal Human Estimatives"); ?> &nbsp;(<?php echo dPgetConfig("currency_symbol") ?>):  </b></td>
        <?php
        $sumColumns = subTotalBudget($meses, $c, $mtz, 0, $sumColumns);
        ?>
        <td cellpadding="3" class="text-right"><b><?php echo number_format($sumH, 2, ',', '.'); ?></b></td>
    </tr>
    <!-- ############################## ESTIMATIVAS CUSTOS NAO HUMANOS ############################################ -->
    <?php
    $c = 0;
    ?>
    <tr>
        <td colspan="<?php echo $meses + 3 ?>">
            <b> <?php echo $AppUI->_('NON-HUMAN RESOURCE ESTIMATIVE'); ?></b>
        </td>
    </tr>

    <?php 
    $sumNH=0;
    foreach ($notHumanCost as $row) {
        ?>
        <tr>
            <td width="20%"><?php echo $row['cost_description']; ?></td>
            <?php
            //The function costsBudget prints the <td> tags for every month.
            $mtzNH = costsBudget($meses, $c, $row, substr($datesProject->fields['project_start_date'], 5, -12), substr($datesProject->fields['project_end_date'], 5, -12), $mtzNH, $monthsYearsIndex);
            $c++;
            ?>
            <td class="text-right"><?php echo number_format($row['cost_value_total'], 2, ',', '.'); ?></td>
        </tr>
        <?php
        $sumNH = $sumNH + $row['cost_value_total'];
    }
    ?>
    <tr>
        <td nowrap="nowrap"><b><?php echo $AppUI->_("Subtotal Not Human Estimatives"); ?> &nbsp;(<?php echo dPgetConfig("currency_symbol") ?>):</b> </td>
        <?php
        $sumColumns = subTotalBudget($meses, $c, $mtzNH, 1, $sumColumns);
        ?>
        <td cellpadding="3" class="text-right"><b><?php echo number_format($sumNH, 2, ',', '.'); ?></b></td>
    </tr>

    <!-- ############################## CONTINGENCY RESERVE  ############################################ -->  
    <?php
    $q->clear();
    $q->addQuery('*');
    $q->addTable('budget_reserve', 'b');
    $q->addWhere("budget_reserve_project_id = " . $projectSelected);
    $q->addOrder('budget_reserve_risk_id');
    $risks = $q->loadList();
    ?>
    <tr>
        <td colspan="<?php echo $meses + 3 ?>">
            <b><?php echo $AppUI->_('CONTINGENCY RESERVE'); ?></b>
        </td>
    </tr>

    <?php
    $k = 0;
    $c = 0;
    foreach ($risks as $row) {
        ?>
        <tr>
            <td width="20%">
                <?php echo $row['budget_reserve_description'] ?>
            </td>

            <?php
            $mtzC = costsContingency($meses, $c, $row, $monthSProject, $monthEndProject, $mtzC,$monthsYearsIndex);
            $c++;
            ?>
            <td class="text-right">
                <?php
                $sumRowContingency = subTotalBudgetRow($meses, $c, $mtzC, $k);
                echo number_format($sumRowContingency, 2, ',', '.');
                ?>
            </td>
            <td nowrap="nowrap" align="center">
                <button type="button" class="btn btn-sm btn-secondary" title="Alterar" onclick="costs.editContingencyReserve(<?=$row['budget_reserve_id'].','.$projectSelected?>)">
                    <i class="far fa-edit"></i>
                </button>
            </td>
        </tr>
        <?php
        $k++;
        $sumC = $sumC + $sumRowContingency;
    }
    ?>
    <tr>
        <td nowrap="nowrap"> <b><?php echo $AppUI->_('Subtotal Contingency'); ?> &nbsp;(<?php echo dPgetConfig("currency_symbol") ?>):</b> </td>
        <?php
        $sumColumns = subTotalBudget($meses, $c, $mtzC, 2, $sumColumns);
        ?>
        <td class="text-right"><b><?php echo number_format($sumC, 2, ',', '.'); ?></b></td>
    </tr>

    <tr>
        <td colspan="<?php echo $meses + 3 ?>">&nbsp;</td>
    </tr>

    <tr>
        <td>
            <b><?php echo $AppUI->_('TOTAL'); ?> &nbsp;(<?php echo dPgetConfig("currency_symbol") ?>): </b>
        </td>
        <?php
        $costsBaselinePlannedArray = totalBudget($meses, $sumColumns);
        ?>
        <td class="text-right">
            <b>
                <?php
                $subTotal = $sumH + $sumNH + $sumC;
                echo number_format($subTotal, 2, ',', '.');
                ?>
            </b>
        </td>
    </tr>
</table>


<!-- ############################## CALCULO DO BUDGET ############################################ -->

<?php
insertBudget($projectSelected, $subTotal);
$q->clear();
$q->addQuery('*');
$q->addTable('budget');
$q->addWhere('budget_project_id = ' . $projectSelected);
$q->addOrder('budget_id');
$v = $q->exec();
?>

<table class="table table-sm table-bordered text-center">
    <thead class="thead-dark">
        <tr>
            <th colspan="4">
                <?php echo $AppUI->_('Budget'); ?>
            </th>
        </tr>
        <tr>
            <th nowrap="nowrap"><?php echo $AppUI->_('Managememt Reserve(%)'); ?></th>
            <th nowrap="nowrap"><?php echo $AppUI->_('Subtotal Budget'); ?>&nbsp;(<?php echo dPgetConfig("currency_symbol") ?>):</th>
            <th nowrap="nowrap"><?php echo $AppUI->_('Total Value'); ?>&nbsp;(<?php echo dPgetConfig("currency_symbol") ?>):</th>
            <th nowrap="nowrap" width="5%"></th>
        </tr>
    </thead>
    <tr>
        <td nowrap="nowrap"><?php echo $bud->budget_reserve_management ?></td>
        <td nowrap="nowrap"><?php echo number_format($subTotal, 2, ',', '.'); ?></td>
        <td nowrap="nowrap"><?php
            $budget = ($subTotal + ($subTotal * ($bud->budget_reserve_management / 100)));
            echo number_format($budget, 2, ',', '.');
            ?>
        </td>
        <td nowrap="nowrap" align="center">
            <button type="button" class="btn btn-sm btn-secondary" title="Alterar" onclick="costs.editReserve(<?=$v->fields['budget_id'].','.$projectSelected?>)">
                <i class="far fa-edit"></i>
            </button>
        </td>
    </tr>
</table>
<?php
    exit();
?>