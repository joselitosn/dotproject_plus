<?php /* CONTACTS $Id: addedit.php 6149 2012-01-09 11:58:40Z ajdonnison $ */
if (!defined('DP_BASE_DIR')) {
  die('You should not access this file directly.');
}

$contact_id = intval(dPgetParam($_GET, 'contact_id', 0));
$company_id = intval(dPgetParam($_REQUEST, 'company_id', 0));
$company_name = dPgetCleanParam($_REQUEST, 'company_name', null);

// check permissions for this record
$canEdit = getPermission($m, 'edit', $contact_id);
if (!(($canEdit && $contact_id) || ($canAuthor && !($contact_id)))) {
	$AppUI->redirect('m=public&a=access_denied');
}

// load the record data
$msg = '';
$row = new CContact();

$canDelete = $row->canDelete($msg, $contact_id);
if ($msg == $AppUI->_('contactsDeleteUserError', UI_OUTPUT_JS)) {
	$userDeleteProtect=true;
}

if (!$row->load($contact_id) && $contact_id > 0) {
	$AppUI->setMsg('Contact');
	$AppUI->setMsg("invalidID", UI_MSG_ERROR, true);
	$AppUI->redirect();
} else if ($row->contact_private && $row->contact_owner != $AppUI->user_id
	&& $row->contact_owner && $contact_id != 0) {
// check only owner can edit
	$AppUI->redirect('m=public&a=access_denied');
}

$company_detail = $row->getCompanyDetails();
$dept_detail = $row->getDepartmentDetails();
if ($contact_id == 0 && $company_id > 0) {
	$company_detail['company_id'] = $company_id;
	$company_detail['company_name'] = $company_name;
	echo $company_name;
}

$q  = new DBQuery;
$q->addTable('companies');
$q->addQuery('company_id, company_name');
$q->addOrder('company_name');
$company_list = $q->loadHashList();

$q  = new DBQuery;
$q->addTable('departments');
$q->addQuery('dept_id, dept_name');
$q->addOrder('dept_name');
$department_list = $q->loadHashList();

?>

<form name="changecontact">
	<input type="hidden" name="dosql" value="do_contact_aed" />
	<input type="hidden" name="del" value="0" />
	<input type="hidden" name="contact_project" value="0" />
	<input type="hidden" name="contact_unique_update" value="<?php echo uniqid("");?>" />
	<input type="hidden" name="contact_id" value="<?php echo $contact_id;?>" />
	<input type="hidden" name="contact_owner" value="<?php echo $row->contact_owner ? $row->contact_owner : $AppUI->user_id;?>" />

    <div class="form-group">
        <span class="required"></span>
        <?=$AppUI->_('requiredField');?>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="first_name" class="required">
                    <?php echo $AppUI->_('First Name'); ?>
                </label>
                <input type="text" class="form-control form-control-sm" name="contact_first_name" value="<?=@$row->contact_first_name?>" maxlength="50" />
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="last_name" class="required">
                    <?php echo $AppUI->_('Last Name'); ?>
                </label>
                <input type="text" class="form-control form-control-sm" name="contact_last_name" value="<?=@$row->contact_last_name?>" maxlength="50" />
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="display_name">
                    <?php echo $AppUI->_('Display Name'); ?>
                </label>
                <input type="text" class="form-control form-control-sm" name="contact_order_by" value="<?=@$row->contact_order_by?>" maxlength="50" />
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <br>
                <div class="form-check form-check-inline">
                    <label for="private_entry" class="form-check-label">
                        <?php echo $AppUI->_('Private Entry'); ?>&nbsp;
                    </label>
                    <input type="checkbox" class="form-check-input" name="contact_private" id="contact_private" <?=@$row->contact_private ? 'checked="checked"' : ''?> />
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="job_title">
                    <?php echo $AppUI->_('Job Title'); ?>
                </label>
                <input type="text" class="form-control form-control-sm" name="contact_job" value="<?=@$row->contact_job?>" maxlength="100" />
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="company">
                    <?php echo $AppUI->_('Company');?>
                </label>

                <select class="form-control form-control-sm" name="contact_company" id="companySelect">
                    <?php
                        foreach ($company_list as $key=>$value) {
                            $selected = $row->contact_company == $key ? ' selected' : '';
                        ?>
                            <option value="<?=$key?>" <?=$selected?>><?=$value?></option>
                        <?php
                        }
                    ?>
                </select>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="department">
                    <?php echo $AppUI->_('Department'); ?>
                </label>
                <!-- TODO FILTRAR DEPARTAMENTO BASEADO NA SELELEÇÃO DA EMPRESA -->
                <select class="form-control form-control-sm" name="contact_department" id="departmentSelect">
                    <?php
                    foreach ($department_list as $key=>$value) {
                        $selected = $row->contact_department == $key ? ' selected' : '';
                        ?>
                        <option value="<?=$key?>" <?=$selected?>><?=$value?></option>
                        <?php
                    }
                    ?>
                </select>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="Title">
                    <?php echo $AppUI->_('Title'); ?>
                </label>
                <input type="text" class="form-control form-control-sm" name="contact_title" value="<?=@$row->contact_title?>" maxlength="50" />
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="Type">
                    <?php echo $AppUI->_('Type'); ?>
                </label>
                <input type="text" class="form-control form-control-sm" name="contact_type" value="<?php echo @$row->contact_type;?>" maxlength="50" />
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="Address">
                    <?php echo $AppUI->_('Address'); ?>
                </label>
                <input type="text" class="form-control form-control-sm" name="contact_address1" value="<?=@$row->contact_address1?>" maxlength="60" />
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="Address">
                    <?php echo $AppUI->_('Address2'); ?>
                </label>
                <input type="text" class="form-control form-control-sm" name="contact_address2" value="<?php echo @$row->contact_address2;?>" maxlength="60" />
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="City">
                    <?php echo $AppUI->_('City'); ?>
                </label>
                <input type="text" class="form-control form-control-sm" name="contact_city" value="<?=@$row->contact_city?>" maxlength="30" />
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="State">
                    <?php echo $AppUI->_('State'); ?>
                </label>
                <input type="text" class="form-control form-control-sm" name="contact_state" value="<?php echo @$row->contact_state;?>" maxlength="30" />
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="Zip">
                    <?php echo $AppUI->_('Zip'); ?>
                </label>
                <input type="text" class="form-control form-control-sm" name="contact_zip" id="contactZip" value="<?=@$row->contact_zip?>" maxlength="11" />
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="Country">
                    <?php echo $AppUI->_('Country'); ?>
                </label>
                <input type="text" class="form-control form-control-sm" name="contact_country" value="<?php echo @$row->contact_country;?>" maxlength="30" />
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="Phone">
                    <?php echo $AppUI->_('Phone'); ?>
                </label>
                <input type="text" class="form-control form-control-sm" name="contact_phone" id="contactPhone" value="<?=@$row->contact_phone?>" maxlength="30" />
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="Mobile Phone">
                    <?php echo $AppUI->_('Mobile Phone'); ?>
                </label>
                <input type="text" class="form-control form-control-sm" name="contact_mobile" id="contactCellPhone" value="<?php echo @$row->contact_mobile;?>" maxlength="30" />
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="Email">
                    <?php echo $AppUI->_('Email'); ?>
                </label>
                <input type="text" class="form-control form-control-sm" name="contact_email" value="<?=@$row->contact_email?>" maxlength="255" />
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <label for="Contact Notes">
                    <?php echo $AppUI->_('Contact Notes'); ?>
                </label>
                <textarea class="form-control form-control-sm" name="contact_notes" rows="3"><?=@$row->contact_notes?></textarea>
            </div>
        </div>
    </div>
</form>
<!--		<tr>-->
<!--			<td align="right">--><?php //echo $AppUI->_('Phone');?><!--2:</td>-->
<!--			<td>-->
<!--				<input type="text" class="text" name="contact_phone2" value="--><?php //echo @$row->contact_phone2;?><!--" maxlength="30" size="25" />-->
<!--			</td>-->
<!--		</tr>-->
<!--		<tr>-->
<!--			<td align="right">--><?php //echo $AppUI->_('Fax');?><!--:</td>-->
<!--			<td>-->
<!--				<input type="text" class="text" name="contact_fax" value="--><?php //echo @$row->contact_fax;?><!--" maxlength="30" size="25" />-->
<!--			</td>-->
<!--		</tr>-->
<!--		<tr>-->
<!--			<td align="right">--><?php //echo $AppUI->_('Email');?><!--2:</td>-->
<!--			<td>-->
<!--				<input type="text" class="text" name="contact_email2" value="--><?php //echo @$row->contact_email2;?><!--" maxlength="255" size="25" />-->
<!--			</td>-->
<!--		</tr>-->
<!--		<tr>-->
<!--			<td align="right">--><?php //echo $AppUI->_('URL');?><!--:</td>-->
<!--			<td>-->
<!--				<input type="text" class="text" name="contact_url" value="--><?php //echo @$row->contact_url;?><!--" maxlength="255" size="25" />-->
<!--			</td>-->
<!--		</tr>-->
<!--		<tr>-->
<!--			<td align="right">Jabber:</td>-->
<!--			<td>-->
<!--				<input type="text" class="text" name="contact_jabber" value="--><?php //echo @$row->contact_jabber;?><!--" maxlength="255" size="25" />-->
<!--			</td>-->
<!--		</tr>-->
<!--		<tr>-->
<!--			<td align="right">ICQ:</td>-->
<!--			<td>-->
<!--				<input type="text" class="text" name="contact_icq" value="--><?php //echo @$row->contact_icq;?><!--" maxlength="20" size="25" />-->
<!--			</td>-->
<!--		</tr>-->
<!--		<tr>-->
<!--			<td align="right">AOL:</td>-->
<!--			<td>-->
<!--				<input type="text" class="text" name="contact_aol" value="--><?php //echo @$row->contact_aol;?><!--" maxlength="20" size="25" />-->
<!--			</td>-->
<!--                </tr>-->
<!--		<tr>-->
<!--			<td align="right">MSN:</td>-->
<!--			<td>-->
<!--				<input type="text" class="text" name="contact_msn" value="--><?php //echo @$row->contact_msn;?><!--" maxlength="255" size="25" />-->
<!--			</td>-->
<!--		</tr>-->
<!--		<tr>-->
<!--			<td align="right">Yahoo:</td>-->
<!--			<td>-->
<!--				<input type="text" class="text" name="contact_yahoo" value="--><?php //echo @$row->contact_yahoo;?><!--" maxlength="255" size="25" />-->
<!--			</td>-->
<!--		</tr>-->
<!--		<tr>-->
<!--			<td align="right">--><?php //echo $AppUI->_('Birthday');?><!--:</td>-->
<!--			<td nowrap="nowrap">-->
<!--				<input type="text" class="text" name="contact_birthday" value="--><?php //echo @mb_substr($row->contact_birthday, 0, 10);?><!--" maxlength="10" size="25" />(--><?php //echo $AppUI->_('yyyy-mm-dd');?><!--)-->
<!--			</td>-->
<!--		</tr>-->

<script>

    $(document).ready(function() {
        $('#companySelect').select2({
            placeholder: '',
            allowClear: true,
            theme: "bootstrap",
            dropdownParent: $("#addEditModal")

        });
        $('#departmentSelect').select2({
            placeholder: '',
            allowClear: true,
            theme: "bootstrap",
            dropdownParent: $("#addEditModal")

        });

        $("#contactZip").mask("00000-000");
        $("#contactPhone").mask("(00)0000-0000");
        $("#contactCellPhone").mask("(00)00000-0000");
    });

</script>
<?php
    exit();
?>
