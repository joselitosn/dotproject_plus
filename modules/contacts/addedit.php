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
$q->addQuery('dept_id, dept_name, dept_company');
$q->addOrder('dept_name');
$department_list = $q->loadHashList();

?>

<script language="javascript" type="text/javascript">
<?php
//	echo "window.company_id=" . (int)dPgetParam($company_detail, 'company_id', 0) . ";\n";
//	echo "window.company_value='" . dPgetCleanParam($company_detail, 'company_name', "") . "';\n";
//?>
//
//function submitIt() {
//	var form = document.changecontact;
//	if (form.contact_last_name.value.length < 1) {
//		alert("<?php //echo $AppUI->_('contactsValidName', UI_OUTPUT_JS);?>//");
//		form.contact_last_name.focus();
//	} else if (form.contact_order_by.value.length < 1) {
//		alert("<?php //echo $AppUI->_('contactsOrderBy', UI_OUTPUT_JS);?>//");
//		form.contact_order_by.focus();
//	} else {
//		form.submit();
//	}
//}

//function popDepartment() {
////due to a bug in Firefox we CANNOT do a window open with &amp; separating the parameters
////this bug does not occur if the window open occurs in an onclick event
////this bug does NOT occur in Internet explorer
////        window.open('./index.php?m=public&amp;a=selector&amp;dialog=1&amp;callback=setDepartment&amp;table=departments&amp;hide_company=1&amp;company_id=' + window.company_id, 'department','left=50,top=50,height=250,width=400,resizable');
//	window.open("?m=contacts&a=select_contact_company&dialog=1&table_name=departments&company_id="+window.company_id, "company", "left=50,top=50,height=250,width=400,resizable");
//}

//function setDepartment(key, val) {
//	var f = document.changecontact;
// 	if (val != '') {
//    	f.contact_department.value = key;
//			f.contact_department_name.value = val;
//    }
//}

//function popCompany() {
////due to a bug in Firefox we CANNOT do a window open with &amp; separating the parameters
////this bug does not occur if the window open occurs in an onclick event
////this bug does NOT occur in Internet explorer
////        window.open('./index.php?m=public&amp;a=selector&amp;dialog=1&amp;callback=setCompany&amp;table=companies', 'company','left=50,top=50,height=250,width=400,resizable');
//	window.open("?m=contacts&a=select_contact_company&dialog=1&table_name=companies&company_id=<?php //echo $company_detail['company_id'];?>//", "company", "left=50,top=50,height=250,width=400,resizable");
//}

//function setCompany(key, val) {
//	var f = document.changecontact;
// 	if (val != '') {
//    	f.contact_company.value = key;
//			f.contact_company_name.value = val;
//    	if (window.company_id != key) {
//    		f.contact_department.value = "";
//				f.contact_department_name.value = "";
//    	}
//    	window.company_id = key;
//    	window.company_value = val;
//    }
//}

//function delIt() {
<?php
//if ($userDeleteProtect) {
//?>
//	alert("<?php //echo $AppUI->_('contactsDeleteUserError', UI_OUTPUT_JS);?>//");
<?php
//} else {
//?>
//	var form = document.changecontact;
//	if (confirm("<?php //echo $AppUI->_('contactsDelete', UI_OUTPUT_JS);?>//")) {
//		form.del.value = "<?php //echo $contact_id;?>//";
//		form.submit();
//	}
<?php
//}
//?>
//}

//function orderByName(x) {
//	var form = document.changecontact;
//	if (x == "name") {
//		form.contact_order_by.value = form.contact_last_name.value + ", " + form.contact_first_name.value;
//	} else {
//		form.contact_order_by.value = form.contact_company_name.value;
//	}
//}
//
//function companyChange() {
//	var f = document.changecontact;
//	if (f.contact_company.value != window.company_value) {
//		f.contact_department.value = "";
//	}
//}

</script>

<form name="changecontact" action="?m=contacts" method="post">
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
                    <?php echo $AppUI->_('Company'); ?>
                </label>

                <select class="form-control form-control-sm" name="contact_company" id="companySelect">
                    <?php
                    foreach ($company_list as $key=>$value) {
                        ?>
                        <option value="<?=$key?>"><?=$value?></option>
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
                        ?>
                        <option value="<?=$key?>"><?=$value?></option>
                        <?php
                    }
                    ?>
                </select>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="company">
                    <?php echo $AppUI->_('Company'); ?>
                </label>


            </div>
        </div>
    </div>








<table border="0" cellpadding="4" cellspacing="0" width="100%" class="std" summary="contact information">
<tr>
		<tr>
			<td align="right"><?php echo $AppUI->_('Title');?>:</td>
			<td><input type="text" class="text" name="contact_title" value="<?php echo @$row->contact_title;?>" maxlength="50" size="25" /></td>
		</tr>
		<tr>
			<td align="right"><?php echo $AppUI->_('Type');?>:</td>
			<td><input type="text" class="text" name="contact_type" value="<?php echo @$row->contact_type;?>" maxlength="50" size="25" /></td>
		</tr>
		<tr>
			<td align="right" width="100"><?php echo $AppUI->_('Address');?>1:</td>
			<td><input type="text" class="text" name="contact_address1" value="<?php echo @$row->contact_address1;?>" maxlength="60" size="25" /></td>
		</tr>
		<tr>
			<td align="right"><?php echo $AppUI->_('Address');?>2:</td>
			<td><input type="text" class="text" name="contact_address2" value="<?php echo @$row->contact_address2;?>" maxlength="60" size="25" /></td>
		</tr>
		<tr>
			<td align="right"><?php echo $AppUI->_('City');?>:</td>
			<td><input type="text" class="text" name="contact_city" value="<?php echo @$row->contact_city;?>" maxlength="30" size="25" /></td>
		</tr>
		<tr>
			<td align="right"><?php echo $AppUI->_('State');?>:</td>
			<td><input type="text" class="text" name="contact_state" value="<?php echo @$row->contact_state;?>" maxlength="30" size="25" /></td>
		</tr>
		<tr>
			<td align="right"><?php echo $AppUI->_('Postcode').' / '.$AppUI->_('Zip');?>:</td>
			<td><input type="text" class="text" name="contact_zip" value="<?php echo @$row->contact_zip;?>" maxlength="11" size="25" /></td>
		</tr>
		<tr>
			<td align="right"><?php echo $AppUI->_('Country');?>:</td>
			<td><input type="text" class="text" name="contact_country" value="<?php echo @$row->contact_country;?>" maxlength="30" size="25" /></td>
		</tr>
		<tr>
			<td align="right" width="100"><?php echo $AppUI->_('Phone');?>:</td>
			<td>
				<input type="text" class="text" name="contact_phone" value="<?php echo @$row->contact_phone;?>" maxlength="30" size="25" />
			</td>
		</tr>
		<tr>
			<td align="right"><?php echo $AppUI->_('Phone');?>2:</td>
			<td>
				<input type="text" class="text" name="contact_phone2" value="<?php echo @$row->contact_phone2;?>" maxlength="30" size="25" />
			</td>
		</tr>
		<tr>
			<td align="right"><?php echo $AppUI->_('Fax');?>:</td>
			<td>
				<input type="text" class="text" name="contact_fax" value="<?php echo @$row->contact_fax;?>" maxlength="30" size="25" />
			</td>
		</tr>
		<tr>
			<td align="right"><?php echo $AppUI->_('Mobile Phone');?>:</td>
			<td>
				<input type="text" class="text" name="contact_mobile" value="<?php echo @$row->contact_mobile;?>" maxlength="30" size="25" />
			</td>
		</tr>
		<tr>
			<td align="right" width="100"><?php echo $AppUI->_('Email');?>:</td>
			<td nowrap="nowrap">
				<input type="text" class="text" name="contact_email" value="<?php echo @$row->contact_email;?>" maxlength="255" size="25" />
			</td>
		</tr>
		<tr>
			<td align="right"><?php echo $AppUI->_('Email');?>2:</td>
			<td>
				<input type="text" class="text" name="contact_email2" value="<?php echo @$row->contact_email2;?>" maxlength="255" size="25" />
			</td>
		</tr>
		<tr>
			<td align="right"><?php echo $AppUI->_('URL');?>:</td>
			<td>
				<input type="text" class="text" name="contact_url" value="<?php echo @$row->contact_url;?>" maxlength="255" size="25" />
			</td>
		</tr>
		<tr>
			<td align="right">Jabber:</td>
			<td>
				<input type="text" class="text" name="contact_jabber" value="<?php echo @$row->contact_jabber;?>" maxlength="255" size="25" />
			</td>
		</tr>
		<tr>
			<td align="right">ICQ:</td>
			<td>
				<input type="text" class="text" name="contact_icq" value="<?php echo @$row->contact_icq;?>" maxlength="20" size="25" />
			</td>
		</tr>
		<tr>
			<td align="right">AOL:</td>
			<td>
				<input type="text" class="text" name="contact_aol" value="<?php echo @$row->contact_aol;?>" maxlength="20" size="25" />
			</td>
                </tr>
		<tr>
			<td align="right">MSN:</td>
			<td>
				<input type="text" class="text" name="contact_msn" value="<?php echo @$row->contact_msn;?>" maxlength="255" size="25" />
			</td>
		</tr>
		<tr>
			<td align="right">Yahoo:</td>
			<td>
				<input type="text" class="text" name="contact_yahoo" value="<?php echo @$row->contact_yahoo;?>" maxlength="255" size="25" />
			</td>
		</tr>
		<tr>
			<td align="right"><?php echo $AppUI->_('Birthday');?>:</td>
			<td nowrap="nowrap">
				<input type="text" class="text" name="contact_birthday" value="<?php echo @mb_substr($row->contact_birthday, 0, 10);?>" maxlength="10" size="25" />(<?php echo $AppUI->_('yyyy-mm-dd');?>)
			</td>
		</tr>
		</table>
	</td>
	<td valign="top" width="50%">
		<strong><?php echo $AppUI->_('Contact Notes');?></strong><br />
		<textarea class="textarea" name="contact_notes" rows="20" cols="40"><?php echo @$row->contact_notes;?></textarea>
	</td>
</tr>
<tr>
	<td>
		<input type="button" value="<?php echo $AppUI->_('back');?>" class="button" onclick="javascript:window.location='./index.php?m=contacts';" />
	</td>
	<td align="right">
		<input type="button" value="<?php echo $AppUI->_('submit');?>" class="button" onclick="javascript:submitIt()" />
	</td>
</tr>
</table>
</form>

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
    });

</script>
<?php
    exit();
?>
