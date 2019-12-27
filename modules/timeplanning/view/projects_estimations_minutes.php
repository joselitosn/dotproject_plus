<?php
if (!defined("DP_BASE_DIR")) {
    die("You should not access this file directly.");
}

require_once (DP_BASE_DIR . "/modules/timeplanning/control/controller_project_minute.class.php");
$controllerProjectMinute = new ControllerProjectMinute();
?>

<button type="button" class="btn btn-sm btn-secondary float-right" onclick="main.showMinutesForm()">
    <?=$AppUI->_("LBL_CREATE_MINUTE")?>
</button>
<br>
<br>

<table class="table table-sm table-bordered text-center">
    <thead class="thead-dark">
        <tr>
            <th>
                <?php echo $AppUI->_("LBL_DATE"); ?>
            </th>
            <th>
                <?php echo $AppUI->_("LBL_DESCRIPTION"); ?>
            </th>
            <th></th>
        </tr>
    </thead>
    <tbody id="minutesTableBody">
        <?php
        $minutes = $controllerProjectMinute->getProjectMinutes($_GET["project_id"]);
        foreach ($minutes as $minute) {
            $date = $minute->getDate();
            $id = $minute->getId();
            $description = $minute->getDescription();
            $description = explode("</p>", $description);
            $description = $description[0];
            ?>
            <tr id="tableMinutes_line_<?=$id?>">
                <td width="10%"><?php echo $date; ?></td>
                <td width="80%"><?php echo $description; ?></td>
                <td width="10%">
                    <button type="button" class="btn btn-sm btn-danger" onclick="main.deleteMinute(<?=$id?>)" title="Remover ata">
                        <i class="far fa-trash-alt"></i>
                    </button>
                    <button type="button" class="btn btn-sm btn-secondary" onclick="main.showMinutesForm(<?=$id?>)" title="Alterar ata">
                        <i class="far fa-edit"></i>
                    </button>
                </td>
            </tr>
        <?php } ?>
    </tbody>

</table>


<?php
    exit();
?>