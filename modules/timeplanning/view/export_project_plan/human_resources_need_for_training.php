<?php
require_once (DP_BASE_DIR . "/modules/timeplanning/model/need_for_training.class.php");
$obj = new NeedForTraining();
$obj->load($projectId);
?>
<table class="printTable">
    <tr>
        <td style="text-align: justify">
<?php echo $obj->getDescription(); ?>
        </td>
    </tr>
</table>
