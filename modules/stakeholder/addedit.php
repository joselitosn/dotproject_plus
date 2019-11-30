<?php
if (!defined('DP_BASE_DIR')) {
    die('You should not access this file directly.');
}

$initiating_id = $_GET["initiating_id"];
$obj = new CStakeholder();
$canDelete=false;
$initiating_stakeholder_id = intval(dPgetParam($_GET, 'initiating_stakeholder_id', 0));
if(isset($initiating_stakeholder_id)){
    require_once (DP_BASE_DIR . "/modules/contacts/contacts.class.php");
    $q = new DBQuery();
    $q->addQuery('*');
    $q->addTable('initiating_stakeholder');
    $q->addWhere('initiating_stakeholder_id = ' . $initiating_stakeholder_id);
    // check if this record has dependancies to prevent deletion
    $msg = '';
    $canDelete = $obj->canDelete($msg, $initiating_stakeholder_id);
}
// load the record data
if (!db_loadObject($q->prepare(), $obj) && $initiating_stakeholder_id > 0) {
    $AppUI->setMsg('Initiating');
    $AppUI->setMsg("invalidID", UI_MSG_ERROR, true);
    $AppUI->redirect();
}
if(!isset($initiating_id)){
  $initiating_id=  $obj->initiating_id;
} 

$opt = array('1' => $AppUI->_("LBL_PROJECT_STAKEHOLDER_HIGH"), '2' => $AppUI->_("LBL_PROJECT_STAKEHOLDER_LOW"));
?>

<form name="stakeholderForm">
    <input type="hidden" name="dosql" value="do_stakeholder_aed" />
    <input type="hidden" name="del" value="0" />
    <input type="hidden" name="strategy" />
    <input type="hidden" name="contact_id" />
    <input type="hidden" name="initiating_stakeholder_id" value="<?php echo $initiating_stakeholder_id; ?>" />
    <input type="hidden" name="initiating_id" value="<?php echo $initiating_id; ?>" />
    <input type="hidden" name="project_id" value="<?php echo $_GET["project_id"]; ?>" />
       
    <?php
        $firstName = "";
        $lastName = "";
        if ($obj->contact_id > 0) {
            $contactObj = new CContact();
            $contactObj->load($obj->contact_id);
            $firstName = $contactObj->contact_first_name;
            $lastName = $contactObj->contact_last_name;
        }
    ?>

    <div class="form-group">
        <span class="required"></span>
        <?=$AppUI->_('requiredField');?>
    </div>

    <div class="form-group">
        <label for="stakeholder_name" class="required">
            <?=$AppUI->_('LBL_NAME')?>
        </label>
        <input type="text" class="form-control form-control-sm" name="first_name" value="<?=$firstName . (strlen($lastName)>0? (' '.$lastName):'')?>" maxlength="100" />
    </div>

    <div class="form-group">
        <label for="responsibilities">
            <?=$AppUI->_('Responsibilities')?>
        </label>
        <textarea rows="3" class="form-control form-control-sm" name="stakeholder_responsibility"><?=dPformSafe(@$obj->stakeholder_responsibility)?></textarea>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="power">
                    <?=$AppUI->_('Power'); ?>
                </label>
                <select class="form-control form-control-sm select-power" name="stakeholder_power">
                    <option>&nbsp;</option>
                    <?php
                    foreach ($opt as $key => $option) {
                        $selected = $obj->stakeholder_power == $key ? 'selected="selected"' : '';
                        ?>
                        <option value="<?=$key?>" <?=$selected?>><?=$option?></option>
                        <?php
                    }
                    ?>
                </select>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="interest">
                    <?=$AppUI->_('Interest'); ?>
                </label>
                <select class="form-control form-control-sm select-interest" name="stakeholder_interest">
                    <option>&nbsp;</option>
                    <?php
                    foreach ($opt as $key => $option) {
                        $selected = $obj->stakeholder_interest == $key ? 'selected="selected"' : '';
                        ?>
                        <option value="<?=$key?>"<?=$selected?>><?=$AppUI->_($option)?></option>
                        <?php
                    }
                    ?>
                </select>
            </div>
        </div>
    </div>

    <div class="form-group">
        <label for="responsibilities">
            <?=$AppUI->_('Strategy')?>
        </label>
        <textarea rows="3" class="form-control form-control-sm" name="stakeholder_strategy"><?=dPformSafe(@$obj->stakeholder_strategy)?></textarea>
    </div>

</form>
<script>

    $(document).ready(function() {
        $(".select-power").select2({
            placeholder: "",
            allowClear: true,
            theme: "bootstrap",
            dropdownParent: $("#addEditStakeholderModal")
        });
        $(".select-interest").select2({
            placeholder: "",
            allowClear: true,
            theme: "bootstrap",
            dropdownParent: $("#addEditStakeholderModal")
        });
    });

</script>
<?php exit(); ?>