<?php
if (!defined('DP_BASE_DIR')) {
    die('You should not access this file directly.');
}

// add communication frequency
if (isset($_GET['communication_frequency'])){
    $add = ($_GET['communication_frequency']);
    $radd = new DBQuery;
    $radd->addInsert('communication_frequency', $add);
    if($_GET['showdate']=='true'){
        $radd->addInsert('communication_frequency_hasdate', 'Sim');  
    }
    $radd->addTable('communication_frequency');
    $radd->exec();
    $AppUI->setMsg("LBL_COMUNICATION_FREQUENCY_INCLUDED", UI_MSG_OK, true);
    $AppUI->redirect("m=communication&a=addedit_frequency&project_id=" .$_GET["project_id"]);
}

// del communication frequency
if (isset($_GET['communication_frequency_id'])){
    $del = ($_GET['communication_frequency_id']);
    $rdel = new DBQuery;
    $rdel->setDelete('communication_frequency');
    $rdel->addWhere('communication_frequency_id=' .$del);
    $rdel->exec();
    $AppUI->setMsg("LBL_COMUNICATION_FREQUENCY_EXCLUDED", UI_MSG_OK, true);
    $AppUI->redirect("m=communication&a=addedit_frequency&project_id=" . $_GET["project_id"]);
}

// list of frequencies
$frequencies = new DBQuery();
$frequencies->addQuery('f.*');
$frequencies->addTable('communication_frequency', 'f');
$frequencies = $frequencies->loadList();

?>
 <script language="javascript"> 
        var showdate = false;
        function submitIt(frequency){
            window.location = ('?m=communication&a=addedit_frequency&communication_frequency='+frequency+'&showdate='+showdate+"&project_id=<?php echo $_GET["project_id"] ?>")
        }
         function DelFrequency(id_frequency){
            window.location = ('?m=communication&a=addedit_frequency&communication_frequency_id='+id_frequency +"&project_id=<?php echo $_GET["project_id"] ?>")    
        }
        function SetShowDate(showdateValue){
           showdate = showdateValue;     
        }
</script>

<table width="50%" border="0" cellpadding="3" cellspacing="3" class="std" charset=UTF-8>
    <form name="uploadFrm" action="?m=communication" method="post">
        <input type="hidden" name="del" value="0" />        
        <input type="hidden" name="communication_frequency_id" value="<?php echo $communication_frequency_id; ?>" />
        <tr><td style="font-size: 12px; font-weight: bold; color: #006"><?php echo $AppUI->_("LBL_TITLE_FREQUENCIES")?></td></tr>
        <tr>
            <td width="80%" valign="top" align="left">
                <table cellspacing="1" cellpadding="2" width="10%">
                    <tr>
                        <td align="left" nowrap="nowrap" width="10%"><?php echo $AppUI->_("LBL_FREQUENCY")?>: </td>
                        <td>
                            <input type="text" align="left" name="communication_frequency" cols="50" rows="1" value=""></input>
                        </td>
                        </td>
                        <td>
                            <input type="button" class="button" value="<?php echo ucfirst($AppUI->_("LBL_SUBMIT")) ?>" onclick="submitIt(communication_frequency.value)" />
                        </td>
                        <td>
                            <script> var targetScreenOnProject="/modules/communication/index_project.php";</script>
                           <?php require_once (DP_BASE_DIR . "/modules/timeplanning/view/subform_back_button_project.php"); ?>
                        </td>
                    <tr>
                        <td style="min-width:120px"></td>
                        <td style="min-width:120px">
                            <input type="checkbox" align="left" name="communication_frequency_showdate" onchange="SetShowDate(this.checked)"><?php echo $AppUI->_("LBL_SHOW_DATE")?></input>
                        </td>
                    </tr>
                    </tr>
                    <td><br></br></td><td><hr></hr></td>
                    <tr>
                        <td align="left" nowrap="nowrap" width="10%"><?php echo $AppUI->_("LBL_LIST_FREQUENCIES")?>: </td>
                        <td>
                            <select id="frequency" name="frequency" style="min-width:150px">
                                <?php
                                foreach ($frequencies as $registro) {
                                    echo '<option value="'.$registro['communication_frequency_id'].'">'. $registro['communication_frequency'].'</option>';
                                }
                                ?>
                            </select>
                            <td><input type="button" value="x" style="color: #aa0000; font-weight: bold" onclick="DelFrequency(frequency.value)"/></td>
                        </td>
                    </tr>
                </table>
    </form>
</table>
