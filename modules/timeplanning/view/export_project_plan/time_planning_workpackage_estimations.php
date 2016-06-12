<?php
require_once (DP_BASE_DIR . "/modules/timeplanning/model/wbs_item_estimation.class.php");
require_once (DP_BASE_DIR . "/modules/timeplanning/control/controller_wbs_items.class.php");
$controllerWBSItem = new ControllerWBSItem();
?>
<table class="printTable">
    <tr>
        <th>
            <?php echo $AppUI->_("LBL_ID",UI_OUTPUT_HTML); ?>
        </th>
        <th>
            <?php echo $AppUI->_("LBL_WORK_PACKAGE",UI_OUTPUT_HTML); ?>
        </th>
        <th>
            <?php echo $AppUI->_("LBL_ESTIMATION_SIZE",UI_OUTPUT_HTML); ?>
        </th>
    </tr>
    <?php
    $items = $controllerWBSItem->getWorkPackages($projectId);
    foreach ($items as $item) {
        $id = $item->getId();
        $name = $item->getName();
        $number = $item->getNumber();
        ?>
        <tr >
            <td>
                <?php echo $number; ?>   
            </td>
            <td>
                <?php echo $name; ?>
            </td>
            <?php
            //start: add column for size estimation
            $eapItem = new WBSItemEstimation();
            $eapItem->load($id);
            ?>
            <td>
                <?php echo $eapItem->getSize() ?> /
                <?php echo $eapItem->getSizeUnit() ?>
            </td>
        </tr>
        <?php
    }
    ?>
</table>