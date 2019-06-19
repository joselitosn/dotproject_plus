<?php
if (!defined('DP_BASE_DIR')) {
    die('You should not access this file directly.');
}

global $AppUI, $dPconfig, $locale_char_se;

$company_id = intval(dPgetParam($_GET, 'company_id', 0));
$query = new DBQuery;
$query->addTable('companies', 'c');
$query->addQuery('company_name');
$query->addWhere('c.company_id = ' . $company_id);
$res = & $query->exec();
$query->clear();

$human_resources_role_id = intval(dPgetParam($_GET, 'human_resources_role_id', 0));
$obj = new CHumanResourcesRole();
$obj->load($human_resources_role_id);
?>
<form name="editfrm">
    <input type="hidden" name="dosql" value="do_role_aed" />
    <input type="hidden" name="del_msg" value="<?php echo $AppUI->_("LBL_DELETE_MSG_ROLE", UI_OUTPUT_JS); ?>" />
    <input type="hidden" name="del" value="0" />
    <input type="hidden" name="human_resources_role_id" value="<?php echo dPformSafe($human_resources_role_id); ?>" />
    <input type="hidden" name="human_resources_role_company_id" value="<?php echo dPformSafe($company_id); ?>" />

    <div class="form-group">
        <span class="required"></span>
        <?=$AppUI->_('requiredField');?>
    </div>

    <div class="form-group">
        <label for="human_resources_role_name" class="required">
            <?php echo $AppUI->_('Role name'); ?>
        </label>
        <input type="text" class="form-control form-control-sm" name="human_resources_role_name" value="<?php echo $obj->human_resources_role_name; ?>" maxlength="100" />
    </div>

    <div class="form-group">
        <label for="human_resources_role_responsability">
            <?=$AppUI->_('Role responsability')?>
        </label>
        <textarea rows="4" class="form-control form-control-sm" name="human_resources_role_responsability"><?php echo $obj->human_resources_role_responsability; ?></textarea>
    </div>

    <div class="form-group">
        <label for="human_resources_role_authority">
            <?=$AppUI->_('Role authority')?>
        </label>
        <textarea rows="4" class="form-control form-control-sm" name="human_resources_role_authority"><?php echo $obj->human_resources_role_authority; ?></textarea>
    </div>

    <div class="form-group">
        <label for="human_resources_role_competence">
            <?=$AppUI->_('Role competence')?>
        </label>
        <textarea rows="4" class="form-control form-control-sm" name="human_resources_role_competence"><?php echo $obj->human_resources_role_competence; ?></textarea>
    </div>
</form>
<?php
    exit();
?>