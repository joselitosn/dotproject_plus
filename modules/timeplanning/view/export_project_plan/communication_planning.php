<?php
require_once DP_BASE_DIR . "/modules/communication/comunication_controller.php";
$comunicationController = new ComunicationController();
$list = $comunicationController->getCommunictionByProject($projectId);
$list_Emissor = $comunicationController->getListOfEmissor();
$list_Receptor = $comunicationController->getListOfReceptor();

?>



<?php
foreach ($list as $row) {
    ?>
    <table class="printTable">
        <tr>
            <td width="30%" class="labelCell"><?php echo $AppUI->_("LBL_PROJECT_COMMUNICATION_TITLE",UI_OUTPUT_HTML); ?> </td>
            <td width="70%"><?php echo $row["communication_title"] ?></td>
        </tr>
        <tr>    
            <td class="labelCell"><?php echo $AppUI->_("LBL_PROJECT_COMMUNICATION_TO",UI_OUTPUT_HTML); ?></td>
            <td>

                <?php
                foreach ($list_Receptor as $receptor) {
                    if ($receptor["communication_id"] == $row["communication_id"]) {
                        echo " - " . $receptor["receptor_first_name"] . " " . $receptor["receptor_last_name"] ;
                    }
                }
                ?>
                &nbsp;
            </td>   
        </tr>

        <tr>
            <td class="labelCell"><?php echo $AppUI->_("LBL_PROJECT_COMUNICATION_FROM",UI_OUTPUT_HTML); ?></td>
            <td>
                <?php
                foreach ($list_Emissor as $emissor) {
                    if ($emissor["communication_id"] == $row["communication_id"]) {
                        echo  " - ". $emissor["emissor_first_name"] . " " . $emissor["emissor_last_name"] ;
                    }
                }
                ?> 
                &nbsp;

            </td>
        </tr>

        <tr>
            <td class="labelCell"><?php echo $AppUI->_("LBL_COMMUNICATION",UI_OUTPUT_HTML); ?></td>
            <td><?php echo $row["communication_information"] ?>&nbsp;</td></tr>
        <tr>
            <td class="labelCell"><?php echo $AppUI->_("LBL_PROJECT_COMMUNICATION_MODE",UI_OUTPUT_HTML); ?></td>
            <td><?php echo $row["communication_channel"] ?>&nbsp;</td></tr>
        <tr>
            <td class="labelCell"><?php echo $AppUI->_("LBL_PROJECT_COMMUNICATION_FREQUENCY",UI_OUTPUT_HTML); ?></td> 
            <td><?php echo $row["communication_frequency"] ?>&nbsp;</td></tr>
        <tr>
            <td class="labelCell"><?php echo $AppUI->_("LBL_PROJECT_COMMUNICATION_CONSTRAINTS",UI_OUTPUT_HTML); ?></td>
            <td>

                <?php echo $row["communication_restrictions"]; ?>
            </td>
        </tr>
    </table>
    <br/>
<?php } ?>
