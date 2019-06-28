<?php /* COMPANIES $Id: addedit.php 6048 2010-10-06 10:01:39Z ajdonnison $ */
if (!defined('DP_BASE_DIR')) {
  die('You should not access this file directly.');
}

include_once (DP_BASE_DIR . '/modules/human_resources/human_resources.class.php');
$company_id = intval(dPgetParam($_GET, 'company_id', 0));

// check permissions for this company
// If the company exists we need edit permission,
// If it is a new company we need add permission on the module.
if ($company_id) {
  $canEdit = getPermission($m, 'edit', $company_id);
} else {
  $canEdit = getPermission($m, 'add');
}

if (!$canEdit) {
	$AppUI->redirect('m=public&a=access_denied');
}

// load the company types
$types = dPgetSysVal('CompanyType');

// load the record data
$q = new DBQuery;
$q->addTable('companies', 'co');
$q->addQuery('co.*');
$q->addQuery('con.contact_first_name');
$q->addQuery('con.contact_last_name');
$q->addJoin('users', 'u', 'u.user_id = co.company_owner');
$q->addJoin('contacts', 'con', 'u.user_contact = con.contact_id');
$q->addWhere('co.company_id = '.$company_id);
$sql = $q->prepare();
$q->clear();

$obj = null;
db_loadObject($sql, $obj);


// collect all the users for the company owner list
$q = new DBQuery;
$q->addTable('users','u');
$q->addTable('contacts','con');
$q->addQuery('user_id');
$q->addQuery('CONCAT_WS(", ",contact_last_name,contact_first_name)'); 
$q->addOrder('contact_last_name');
$q->addWhere('u.user_contact = con.contact_id');
$owners = $q->loadHashList();

// Load company policies
$query = new DBQuery();
$query->addTable('company_policies', 'p');
$query->addQuery('company_policies_id');
$query->addWhere('p.company_policies_company_id = ' . $company_id);
$res = & $query->exec();
$company_policies_id = $res->fields['company_policies_id'];
$query->clear();

$policies = new CCompaniesPolicies();
$policies->load($company_policies_id);

?>
<form name="changeclient">

    <input type="hidden" name="dosql" value="do_company_aed" />
	<input type="hidden" name="company_id" value="<?php echo dPformSafe($company_id); ?>" />
    <input type="hidden" name="company_policies_id" value="<?php echo dPformSafe($company_policies_id); ?>" />
    <input type="hidden" name="company_policies_company_id" value="<?php echo dPformSafe($company_id); ?>" />

    <div class="form-group">
        <span class="required"></span>
        <?=$AppUI->_('requiredField');?>
    </div>

    <div class="form-group">
        <label for="company_name" class="required">
            <?php echo $AppUI->_('Company Name'); ?>
        </label>
        <input type="text" class="form-control form-control-sm" name="company_name" value="<?php echo dPformSafe(@$obj->company_name); ?>" size="50" maxlength="255" />
    </div>

    <div class="form-group">
        <label for="company_email">
            <?php echo $AppUI->_('Camel_Case_Email'); ?>
        </label>
        <input type="email" class="form-control form-control-sm" name="company_email" value="<?php echo dPformSafe(@$obj->company_email); ?>" size="30" maxlength="255" />
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label for="company_phone">
                    <?php echo $AppUI->_('Phone'); ?>
                </label>
                <input type="text" class="form-control form-control-sm phone" name="company_phone1" value="<?php echo dPformSafe(@$obj->company_phone1); ?>" maxlength="30" />
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="company_fax">
                    <?php echo $AppUI->_('Fax'); ?>
                </label>
                <input type="text" class="form-control form-control-sm phone" name="company_phone2" value="<?php echo dPformSafe(@$obj->company_phone2); ?>" maxlength="30" />
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="company_zip">
                    <?php echo $AppUI->_('Zip'); ?>
                </label>
                <input type="text" class="form-control form-control-sm zip" name="company_zip" value="<?php echo dPformSafe(@$obj->company_zip); ?>" />
            </div>
        </div>
    </div>

    <div class="form-group">
        <label for="company_address">
            <?php echo $AppUI->_('Address'); ?>
        </label>
        <input type="text" class="form-control form-control-sm" name="company_address1" value="<?php echo dPformSafe(@$obj->company_address1); ?>" size="50" maxlength="255" />
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="company_city">
                    <?php echo $AppUI->_('City'); ?>
                </label>
                <input type="text" class="form-control form-control-sm" name="company_city" value="<?php echo dPformSafe(@$obj->company_city); ?>" size="50" maxlength="50" />
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="company_state">
                    <?php echo $AppUI->_('State'); ?>
                </label>
                <input type="text" class="form-control form-control-sm" name="company_state" value="<?php echo dPformSafe(@$obj->company_state); ?>" maxlength="50" />
            </div>
        </div>
    </div>

    <div class="form-group">
        <label for="company_url">
            URL http://
        </label>
        <input type="text" class="form-control form-control-sm" name="company_primary_url" value="<?php echo dPformSafe(@$obj->company_primary_url); ?>" />
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="company_owner" class="required">
                    <?=$AppUI->_('Company Owner'); ?>
                </label>
                <select class="form-control form-control-sm select-owner"
                        name="company_owner">
                    <?php
                    $owner = (($obj->company_owner) ? $obj->company_owner : $AppUI->user_id);
                    foreach ($owners as $key => $option) {
                        $selected = $owner == $key ? 'selected="selected"' : '';
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
                <label for="company_type" class="required">
                    <?=$AppUI->_('Type'); ?>
                </label>
                <select class="form-control form-control-sm select-type"
                        name="company_type">
                    <?php
                        foreach ($types as $key => $option) {
                            $selected = $obj->company_type == $key ? 'selected="selected"' : '';
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
        <label for="company_url">
            <?=$AppUI->_('Description')?>
        </label>
        <textarea rows="3" class="form-control form-control-sm" name="company_description"><?php echo htmlspecialchars(@$obj->company_description); ?></textarea>
    </div>

    <?php
        if ($company_id>0){
            $query = new DBQuery();
            $query->addTable('company_policies', 'p');
            $query->addQuery('company_policies_id');
            $query->addWhere('p.company_policies_company_id = ' . $company_id);
            $res = & $query->exec();
            $company_policies_id = $res->fields['company_policies_id'];
            $query->clear();

            $policies = new CCompaniesPolicies();
            $policies->load($company_policies_id);

    ?>
            <div class="form-group">
                <label for="company_rewards">
                    <?=$AppUI->_('Rewards and recognition')?>
                </label>
                <textarea rows="3" class="form-control form-control-sm" name="company_policies_recognition"><?php echo dPformSafe($policies->company_policies_recognition); ?></textarea>
            </div>

            <div class="form-group">
                <label for="company_regulations">
                    <?=$AppUI->_('Regulations, standards, and policy compliance')?>
                </label>
                <textarea rows="3" class="form-control form-control-sm" name="company_policies_policy"><?php echo dPformSafe($policies->company_policies_policy); ?></textarea>
            </div>

            <div class="form-group">
                <label for="company_safety">
                    <?=$AppUI->_('Safety')?>
                </label>
                <textarea rows="3" class="form-control form-control-sm" name="company_policies_safety"><?php echo dPformSafe($policies->company_policies_safety); ?></textarea>
            </div>
    <?php
        }
    ?>
</form>

    <script>

        $(".phone").mask("(99)9999-?9999", {translation:  {'?': {pattern: /[0-9]/, optional: true}}});
        $(".zip").mask("00000-000");
        $(".select-owner").select2({
            placeholder: "Dono",
            allowClear: true,
            theme: "bootstrap",
            dropdownParent: $("#addEditCompanyModal")
        });
        $(".select-type").select2({
            placeholder: "Tipo",
            allowClear: true,
            theme: "bootstrap",
            dropdownParent: $("#addEditCompanyModal")
        });

    </script>


<?php
    exit();
?>