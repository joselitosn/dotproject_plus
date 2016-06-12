<?php
require_once (DP_BASE_DIR . "/modules/timeplanning/control/controller_wbs_items.class.php");
$controllerWBSItem = new ControllerWBSItem();
?>
<br />
<table class="printTable">
    <tr>
        <th class="shortTD"><?php echo $AppUI->_("LBL_ID",UI_OUTPUT_HTML); ?> </th>
        <th style="text-align: left"><?php echo $AppUI->_("LBL_WBS",UI_OUTPUT_HTML); ?> Item</th>
    </tr>
    <?php
    $items = $controllerWBSItem->getWBSItems($projectId);
    foreach ($items as $item) {
        ?>
        <tr>
            <td><?php echo $item->getNumber() . " " . ($item->isLeaf() == 1 ? "*" : "") ?></td>
            <td><?php echo $item->getIdentation() . $item->getName() ?></td>
        </tr>    
        <?php
    }
    ?>
</table>      