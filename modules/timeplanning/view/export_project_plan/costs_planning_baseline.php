<?php
require_once DP_BASE_DIR . "/modules/costs/cost_baseline_parts/cost_baseline_setup.php";
?>

<!-- ############################## ESTIMATIVAS CUSTOS HUMANOS ############################################ -->
<table class="printTable" style="font-size:7px !important;">
<?php require_once DP_BASE_DIR . "/modules/costs/cost_baseline_parts/cost_baseline_initiated_header.php"; ?>

   
    <tr>
        <th  ><?php echo $AppUI->_("Item"); ?></th>
        <?php
        for ($i = 0; $i <= $meses; $i++) {
            $mes = $monthStartProject;
            $monthStartProject++;
            if ($mes == 12)
                $monthStartProject = 1;
            ?>

            <th style="text-align:center">
                <?php echo strlen($mes)<2?"0".$mes:$mes; ?>
            </th>
            <?php
            $counter++;
        }
        ?>
        <th  ><?php echo $AppUI->_("Total Cost",UI_OUTPUT_HTML); ?> (<?php echo $AppUI->_("LBL_PROJECT_CURRENCY",UI_OUTPUT_HTML); ?>)</th>
    </tr>

    <tr>
        <td  align="center" colspan="<?php echo $meses + 3 ?>">
            <b><?php echo $AppUI->_("HUMAN RESOURCE ESTIMATIVE",UI_OUTPUT_HTML); ?></b>
        </td>
    </tr>

<?php 
    $sumH=0;
    foreach ($humanCost as $row) {
    ?>
        <tr>
            <td style="width:170px"><?php echo $row["cost_description"]; ?></td>
            <?php
            $mtz = costsBudget($meses, $c, $row, substr($datesProject->fields["project_start_date"], 5, -12), substr($datesProject->fields["project_end_date"], 5, -12), $mtz,$monthsYearsIndex);
            $c++;
            ?>

            <td style="text-align:right"><?php echo formatCellContent(number_format($row["cost_value_total"], 2, ",", ".")); ?></td>
        </tr>
        <?php
        $sumH = $sumH + $row["cost_value_total"];
    }
    ?>
    <tr>
        <td> <b><?php echo $AppUI->_("Subtotal Human Estimatives",UI_OUTPUT_HTML); ?> (<?php echo $AppUI->_("LBL_PROJECT_CURRENCY",UI_OUTPUT_HTML); ?>)</b> </td>
        <?php
        $sumColumns = subTotalBudget($meses, $c, $mtz, 0, $sumColumns);
        ?>
        <td style="text-align:right"><b><?php echo formatCellContent(number_format($sumH, 2, ",", ".")); ?></b></td>

    </tr>


    <br>
    <!-- ############################## ESTIMATIVAS CUSTOS NAO HUMANOS ############################################ -->

    <tr>
        <td  align="center" colspan="<?php echo $meses + 3 ?>">
            <b> <?php echo $AppUI->_("Non-Human Resource Estimative",UI_OUTPUT_HTML); ?></b>
        </td>
    </tr>

    <?php
    $c = 0;
    $sumNH=0;
    foreach ($notHumanCost as $row) {
        ?>
        <tr>
            <td ><?php echo $row["cost_description"]; ?></td>
            <?php
            $mtzNH = costsBudget($meses, $c, $row, substr($datesProject->fields["project_start_date"], 5, -12), substr($datesProject->fields["project_end_date"], 5, -12), $mtzNH,$monthsYearsIndex);
            $c++;
            ?>

            <td style="text-align:right"><?php echo formatCellContent(number_format($row["cost_value_total"], 2, ",", ".")); ?></td>
        </tr>
        <?php
        $sumNH = $sumNH + $row["cost_value_total"];
    }
    ?>
    <tr>
        <td> <b><?php echo $AppUI->_("Subtotal Not Human Estimatives",UI_OUTPUT_HTML); ?>  (<?php echo $AppUI->_("LBL_PROJECT_CURRENCY",UI_OUTPUT_HTML); ?>)</b> </td>
        <?php
        $sumColumns = subTotalBudget($meses, $c, $mtzNH, 1, $sumColumns);
        ?>
        <td  cellpadding="3" style="text-align:right"><b><?php echo formatCellContent(number_format($sumNH, 2, ",", ".")); ?></b></td>

    </tr>


    <!-- ############################## CONTINGENCY RESERVE  ############################################ -->  

    <?php
    $q->clear();
    $q->addQuery("*");
    $q->addTable("budget_reserve", "b");
    $q->addWhere("budget_reserve_project_id = " . $projectId);
    $q->addOrder("budget_reserve_risk_id");
    $risks = $q->loadList();
    ?>

    <tr>
        <td  align="center" colspan="<?php echo $meses + 3 ?>">
            <b><?php echo $AppUI->_("CONTINGENCY RESERVE",UI_OUTPUT_HTML); ?></b>
        </td>
    </tr>

    <?php
    $k = 0;
    $c = 0;

    foreach ($risks as $row) {
        ?>
        <tr>
            <td>
            <?php echo $row["budget_reserve_description"] ?>
            </td>

            <?php
            $mtzC = costsContingency($meses, $c, $row, $monthSProject, substr($datesProject->fields["project_end_date"], 5, -12), $mtzC,$monthsYearsIndex);
            $c++;
            ?>

            <td style="text-align:right">
            <?php
                $sumRowContingency = subTotalBudgetRow($meses, $c, $mtzC, $k);
                echo formatCellContent(number_format($sumRowContingency, 2, ",", "."));
            ?>
            </td>
        </tr>
        <?php
        $k++;
        $sumC = $sumC + $sumRowContingency;
        
    }
    ?>
    <tr>
        <td> <b><?php echo $AppUI->_("Subtotal Contingency",UI_OUTPUT_HTML); ?> (<?php echo $AppUI->_("LBL_PROJECT_CURRENCY",UI_OUTPUT_HTML); ?>)</b> </td>
        <?php
        $sumColumns = subTotalBudget($meses, $c, $mtzC, 0, $sumColumns);
        ?>
        <td style="text-align:right"><b><?php echo formatCellContent(number_format($sumC, 2, ",", ".")); ?></b></td>
    </tr>

    <tr>
        <td   align="center" colspan="<?php echo $meses + 3 ?>"></td>
    </tr>

    <tr>
        <td   align="center">
            <b><?php echo $AppUI->_("TOTAL",UI_OUTPUT_HTML); ?> (<?php echo $AppUI->_("LBL_PROJECT_CURRENCY",UI_OUTPUT_HTML); ?>)</b>
        </td>
        <?php
        totalBudget($meses, $sumColumns);
        ?>
        <td style="text-align:right">
            <b>
                <?php 
                $subTotal = $sumH + $sumNH + $sumC;
                echo formatCellContent(number_format($subTotal, 2, ",", "."));
                ?>
            </b>
        </td>
    </tr>
</table>