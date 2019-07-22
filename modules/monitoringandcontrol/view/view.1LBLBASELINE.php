<?php
if (!defined('DP_BASE_DIR')) {
  die('You should not access this file directly.');
}
require_once (DP_BASE_DIR . "/modules/monitoringandcontrol/control/controller_baseline.class.php");
require_once (DP_BASE_DIR . "/modules/monitoringandcontrol/control/controller_util.class.php");

$project_id = dPgetParam( $_GET, 'project_id', 0 );
$controllerBaseline= new ControllerBaseline();
$controllerUtil = new ControllerUtil();

?>
<script src="./modules/monitoringandcontrol/js/respons.js"></script>
<div class="row">
    <div class="col-md-12 text-right">
        <button type="button" class="btn btn-sm btn-secondary" onclick="baseline.new()">
            <?=$AppUI->_('LBL_NOVA') . ' ' . $AppUI->_('LBL_BASELINE')?>
        </button>
    </div>
</div>

<br>

<table class="table table-sm table-bordered text-center">
    <thead class="thead-dark">
    <tr>
        <th width="45%"><?php echo $AppUI->_('LBL_NOME'); ?></th>
        <th width="10%"><?php echo $AppUI->_('LBL_VERSAO'); ?></th>
        <th width="30%"><?php echo $AppUI->_('LBL_DATA'); ?></th>
        <th width="15%"></th>
    </tr>
    </thead>
    <tbody id="baselinesTableBody">
    <?php
        $list = array();
        $list = $controllerBaseline ->listBaseline($project_id);
        foreach($list as $row) {
            $id = $row['baseline_id'];
            ?>
            <tr id="tableBaselines_line_<?=$id?>">
                <td><?php echo $row['baseline_name']; ?> </td>
                <td><?php echo $row['baseline_version']; ?> </td>
                <td><?php echo $controllerUtil->formatDateTime($row['baseline_date']);  ?> </td>
                <td>
                    <button type="button" class="btn btn-sm btn-danger" onclick="baseline.delete(<?=$id?>)" title="Remover baseline">
                        <i class="far fa-trash-alt"></i>
                    </button>
                    <button type="button" class="btn btn-sm btn-secondary" onclick="baseline.edit(<?=$id?>)" title="Alterar baseline">
                        <i class="far fa-edit"></i>
                    </button>
                </td>
            </tr>
    <?php
        }
    ?>
    </tbody>

</table>