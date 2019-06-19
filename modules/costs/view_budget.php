<?php
if (!defined('DP_BASE_DIR')) {
    die('You should not access this file directly.');
}
require_once DP_BASE_DIR . "/modules/costs/cost_baseline_parts/cost_baseline_setup.php";
?>
<script language="javascript">
    function submitIt() {
        var f = document.uploadFrm;
        f.submit();
    }
    function delIt() {
        if (confirm("<?php echo $AppUI->_('Delete this registry?', UI_OUTPUT_JS); ?>")) {
            var f = document.uploadFrm;
            f.del.value = '1';
            f.submit();
        }
    }
</script>

<!-- ############################## ESTIMATIVAS CUSTOS HUMANOS ############################################ -->

<table width="100%" border="0" cellpadding="3" cellspacing="3" class="tbl">
    <?php require_once DP_BASE_DIR . "/modules/costs/cost_baseline_parts/cost_baseline_initiated_header.php"; ?>

    <tr>
        <th nowrap="nowrap" width="15%"><?php echo $AppUI->_('Item'); ?></th>
        <?php
        for ($i = 0; $i <= $meses; $i++) {
            $mes = $monthStartProject;
            $monthStartProject++;
            if ($mes == 12)
                $monthStartProject = 1;
            ?>
            <th style="text-align: center; text-wrap: none">
                <?php echo strlen($mes) < 2 ? "0" . $mes : $mes; ?>
            </th>
            <?php
            $counter++;
        }
        ?>
        <th nowrap="nowrap" width="10%"><?php echo $AppUI->_('Total Cost'); ?> (<?php echo dPgetConfig("currency_symbol") ?>)</th>
    </tr>    
    <tr >
        <td style="background-color: silver" nowrap='nowrap' align="center" colspan="<?php echo $meses + 3 ?>"><!-- + 3 is because of the <td> used for labels that are unecessary for this <tr>   -->
            <b><?php echo $AppUI->_('HUMAN RESOURCE ESTIMATIVE'); ?></b>
        </td>
    </tr>

    <?php 
        $sumH=0;
        foreach ($humanCost as $row) {
        ?>
        <tr>
            <td nowrap="nowrap"><?php echo $row['cost_description']; ?></td>
            <?php
            //The function costsBudget prints the <td> tags for every month.
            $mtz = costsBudget($meses, $c, $row, substr($datesProject->fields['project_start_date'], 5, -12), substr($datesProject->fields['project_end_date'], 5, -12), $mtz,$monthsYearsIndex);
            $c++;
            ?>
            <td nowrap="nowrap" style="text-align: right"><?php echo number_format($row['cost_value_total'], 2, ',', '.'); ?></td>
        </tr>
        <?php
        $sumH = $sumH + $row['cost_value_total'];
    }
    ?>
    <tr>
        <td nowrap="nowrap" width="15%" style="text-align: right"><b><?php echo $AppUI->_("Subtotal Human Estimatives"); ?> &nbsp;(<?php echo dPgetConfig("currency_symbol") ?>):  </b></td>
        <?php
        $sumColumns = subTotalBudget($meses, $c, $mtz, 0, $sumColumns);
        ?>
        <td nowrap="nowrap" cellpadding="3" style="text-align: right"><b><?php echo number_format($sumH, 2, ',', '.'); ?></b></td>
    </tr>
    <br />
    <!-- ############################## ESTIMATIVAS CUSTOS NAO HUMANOS ############################################ -->
    <?php
    $c = 0;
    ?>
    <tr >
        <td style="background-color: silver" nowrap='nowrap' align="center" colspan="<?php echo $meses + 3 ?>">
            <b> <?php echo $AppUI->_('NON-HUMAN RESOURCE ESTIMATIVE'); ?></b>
        </td>
    </tr>

    <?php 
    $sumNH=0;
    foreach ($notHumanCost as $row) {
        ?>
        <tr>
            <td nowrap="nowrap" width="15%"><?php echo $row['cost_description']; ?></td>
            <?php
            //The function costsBudget prints the <td> tags for every month.
            $mtzNH = costsBudget($meses, $c, $row, substr($datesProject->fields['project_start_date'], 5, -12), substr($datesProject->fields['project_end_date'], 5, -12), $mtzNH, $monthsYearsIndex);
            $c++;
            ?>
            <td nowrap="nowrap" style="text-align: right"><?php echo number_format($row['cost_value_total'], 2, ',', '.'); ?></td>
        </tr>
        <?php
        $sumNH = $sumNH + $row['cost_value_total'];
    }
    ?>
    <tr>
        <td nowrap="nowrap"  width="15%" style="text-align: right"> <b><?php echo $AppUI->_("Subtotal Not Human Estimatives"); ?> &nbsp;(<?php echo dPgetConfig("currency_symbol") ?>):</b> </td>
        <?php
        $sumColumns = subTotalBudget($meses, $c, $mtzNH, 1, $sumColumns);
        ?>
        <td nowrap="nowrap" cellpadding="3" style="text-align: right"><b><?php echo number_format($sumNH, 2, ',', '.'); ?></b></td>
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
    <tr >
        <td style="background-color: silver" nowrap='nowrap' width='100%' align="center" colspan="<?php echo $meses + 3 ?>">
            <b><?php echo $AppUI->_('CONTINGENCY RESERVE'); ?></b>
        </td>
    </tr>

    <?php
    $k = 0;
    $c = 0;
    foreach ($risks as $row) {
        ?>
        <tr>
            <td nowrap="nowrap"> <a href="index.php?m=costs&a=addedit_budget_reserve&budget_reserve_id=<?php echo $row['budget_reserve_id']; ?>&project_id=<?php echo $projectSelected ?>">
                    <img src="./modules/costs/images/stock_edit-16.png" border="0" width="12" height="12">
                </a>&nbsp;
                <?php echo $row['budget_reserve_description'] ?>
            </td>

            <?php
            $mtzC = costsContingency($meses, $c, $row, $monthSProject, $monthEndProject, $mtzC,$monthsYearsIndex);
            $c++;
            ?>

            <td nowrap="nowrap" style="text-align: right">
                <?php
                $sumRowContingency = subTotalBudgetRow($meses, $c, $mtzC, $k);
                echo number_format($sumRowContingency, 2, ',', '.');
                ?>
            </td>
        </tr>
        <?php
        $k++;
        $sumC = $sumC + $sumRowContingency;
    }
    ?>
    <tr>
        <td nowrap="nowrap" width="15%" style="text-align: right"> <b><?php echo $AppUI->_('Subtotal Contingency'); ?> &nbsp;(<?php echo dPgetConfig("currency_symbol") ?>):</b> </td>
        <?php
        $sumColumns = subTotalBudget($meses, $c, $mtzC, 2, $sumColumns);
        ?>
        <td nowrap="nowrap" style="text-align: right" cellpadding="3"><b><?php echo number_format($sumC, 2, ',', '.'); ?></b></td>
    </tr>

    <tr>
        <td nowrap='nowrap'  align="center" colspan="<?php echo $meses + 3 ?>"></td>
    </tr>

    <tr>
        <td style="text-align: right">
            <b><?php echo $AppUI->_('TOTAL'); ?> &nbsp;(<?php echo dPgetConfig("currency_symbol") ?>): </b>
        </td>
        <?php
        $costsBaselinePlannedArray = totalBudget($meses, $sumColumns);
        ?>
        <td nowrap="nowrap" style="text-align: right">
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

<table width="100%" border="0" cellpadding="3" cellspacing="3" class="tbl">
    <tr>
        <th nowrap='nowrap' width='100%' colspan="6">
            <?php echo $AppUI->_('Budget'); ?>
        </th>
    </tr>
    <tr>
        <th nowrap="nowrap" width="25"></th>
        <th nowrap="nowrap"><?php echo $AppUI->_('Managememt Reserve(%)'); ?></th>
        <th nowrap="nowrap"><?php echo $AppUI->_('Subtotal Budget'); ?>&nbsp;(<?php echo dPgetConfig("currency_symbol") ?>):</th>
        <th nowrap="nowrap"><?php echo $AppUI->_('Total Value'); ?>&nbsp;(<?php echo dPgetConfig("currency_symbol") ?>):</th>
    </tr>
    <tr>
        <td nowrap="nowrap" align="center">
            <a href="index.php?m=costs&a=addedit_budget&budget_id=<?php echo $v->fields['budget_id'] ?>&project_id=<?php echo $projectSelected ?>">
                <img src="./modules/costs/images/stock_edit-16.png" border="0" width="12" height="12">
            </a>
        </td>
        <td nowrap="nowrap" style="text-align: center"><?php echo $bud->budget_reserve_management ?></td>
        <td nowrap="nowrap" style="text-align: right"><?php echo number_format($subTotal, 2, ',', '.'); ?></td>
        <td nowrap="nowrap" style="text-align: right"><?php
            $budget = ($subTotal + ($subTotal * ($bud->budget_reserve_management / 100)));
            echo number_format($budget, 2, ',', '.');
            ?>
        </td>
    </tr>
</table>