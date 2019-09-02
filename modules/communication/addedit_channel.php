<?php
if (!defined('DP_BASE_DIR')) {
    die('You should not access this file directly.');
}
$channelId = $_GET['channel_id'];
// //$communication_channel_id = intval(dPgetParam($_GET, 'communication_channel_id'));
// require_once (DP_BASE_DIR . "/modules/communication/comunication_controller.php");
// $controller=new ComunicationController();
// // add communication channel
// if (isset($_GET['communication_channel'])){
//     if(!$controller->channelAlreadyExists($_GET['communication_channel'])){
//         $add = ($_GET['communication_channel']);
//         $radd = new DBQuery;
//         $radd->addInsert('communication_channel', $add);
//         $radd->addTable('communication_channel');
//         $radd->exec();
//         $AppUI->setMsg("LBL_COMUNICATION_CHANNEL_INCLUDED", UI_MSG_OK, true);
//     }else{
//         $AppUI->setMsg("LBL_VALIDATION_DUPLICATED_ITEMS", UI_MSG_WARNING, true);
//     }
//     $AppUI->redirect("m=communication&a=addedit_channel&project_id=" . $_GET["project_id"]);
//     //header('location:?m=communication&a=addedit_channel');
// }

// // del communication channel
// if (isset($_GET['communication_channel_id'])){
    
//     $count=$controller->channelIsBeenUtilized($_GET['communication_channel_id']);
//     if($count==0){
//         $del = ($_GET['communication_channel_id']);
//         $rdel = new DBQuery;
//         $rdel->setDelete('communication_channel');
//         $rdel->addWhere('communication_channel_id=' .$del);
//         $rdel->exec();
//         $AppUI->setMsg("LBL_COMUNICATION_CHANNEL_EXCLUDED", UI_MSG_OK, true);    
//     }else{
//         $AppUI->setMsg("LBL_NOT_POSSIBLE_TO_DELETE_DUE_TO_RELATIONSHIP", UI_MSG_ALERT, true);
//     }
//     $AppUI->redirect("m=communication&a=addedit_channel&project_id=" . $_GET["project_id"]);
//     //header('location:?m=communication&a=addedit_channel');
// }
?>
<form name="channelForm">
    <input type="hidden" name="dosql" value="do_channel_aed" />
    <input type="hidden" name="del" value="0" />        
    <input type="hidden" name="channel_id" value="<?=$channelId?>" />

    <div class="form-group">
        <span class="required"></span>
        <?=$AppUI->_('requiredField');?>
    </div>

    <div class="form-group">
        <label for="communication_channel" class="required">
            <?=$AppUI->_('LBL_NEW_CHANNEL')?>
        </label>
        <input type="text" maxlength="255" class="form-control form-control-sm" name="communication_channel" value="" />
    </div>

</form>
<?php
    exit();
?>