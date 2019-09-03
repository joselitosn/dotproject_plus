<?php
if (!defined('DP_BASE_DIR')) {
    die('You should not access this file directly.');
}
$channelId = $_GET['channel_id'];

$obj = null;
if ($channelId) {
    $q = new DBQuery();
    $q->addQuery("*");
    $q->addTable("communication_channel");
    $q->addWhere("communication_channel_id=".$channelId);
    db_loadObject($q->prepare(), $obj);
}

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
            Descrição do canal
        </label>
        <input type="text" maxlength="255" class="form-control form-control-sm" name="communication_channel" value="<?=$obj->communication_channel?>" />
    </div>

</form>
<?php
    exit();
?>