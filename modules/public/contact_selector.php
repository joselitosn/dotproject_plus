<?php
if (!defined('DP_BASE_DIR')) {
  die('You should not access this file directly.');
}

$show_all = (int)dPgetParam($_REQUEST, 'show_all', 0);
$company_id = (int)dPgetParam($_REQUEST, 'company_id', 0);
$contact_id = (int)dPgetParam($_POST, 'contact_id', 0);
$call_back = dPgetCleanParam($_GET, 'call_back', null);
$contacts_submited = (int)dPgetParam($_POST, 'contacts_submited', 0);
$selected_contacts_id = dPgetCleanParam($_GET, 'selected_contacts_id', '');
if (dPgetParam($_POST, 'selected_contacts_id'))	{
	$selected_contacts_id = dPgetCleanParam($_POST, 'selected_contacts_id');
}

function remove_invalid($arr) {
	$result = array();
	foreach ($arr as $val) {
		if (!(empty($val)) && trim($val) !== '' && is_numeric($val)) {
			$result[] = $val;
		}
	}	
	return $result;
}

// Remove any empty elements
$contacts_id = remove_invalid(explode(',', $selected_contacts_id));
$selected_contacts_id = implode(',', $contacts_id);

require_once($AppUI->getModuleClass('companies'));
$oCpy = new CCompany ();
$aCpies = $oCpy->getAllowedRecords ($AppUI->user_id, 'company_id, company_name', 'company_name');
$aCpies_esc = array();
foreach ($aCpies as $key => $company) {
	$aCpies_esc[$key] = db_escape($company);
}

$q = new DBQuery;

if (mb_strlen($selected_contacts_id) > 0 && ! $show_all && ! $company_id) {
	$q->addTable('contacts');
	$q->addQuery('DISTINCT contact_company');
	$q->addWhere('contact_id IN (' . $selected_contacts_id . ')');
	$where = implode(',', $q->loadColumn());
	$q->clear();
	if (mb_substr($where, 0, 1) == ',' && $where != ',') { 
		$where = '0'.$where; 
	} else if ($where == ',') {
		$where = '0';
	}
	$where = (($where)?('contact_company IN('.$where.')'):'');
} else if (! $company_id) {
	//  Contacts from all allowed companies
	$where = ("contact_company = ''"
	          ." OR (contact_company IN ('".implode('\',\'' , array_values($aCpies_esc)) ."'))"
	          ." OR (contact_company IN ('".implode('\',\'', array_keys($aCpies_esc)) ."'))") ;
	$company_name = $AppUI->_('Allowed Companies');
} else {
	// Contacts for this company only
	$q->addTable('companies', 'c');
	$q->addQuery('c.company_name');
	$q->addWhere('company_id = '.$company_id);
	$company_name = $q->loadResult();
	$q->clear();
	/*
		$sql = "select c.company_name from companies as c where company_id = $company_id";
		$company_name = db_loadResult($sql);
	*/
	$company_name_sql = db_escape($company_name);
	$where = " (contact_company = '$company_name_sql' or contact_company = '$company_id')";
}

// This should now work on company ID, but we need to be able to handle both
$q->addTable('contacts', 'a');
$q->leftJoin('companies', 'b', 'b.company_id = a.contact_company');
$q->leftJoin('departments', 'c', 'c.dept_id = a.contact_department');
$q->leftJoin('users', 'u', 'u.user_contact=a.contact_id');
$q->addQuery('a.contact_id, a.contact_first_name, a.contact_last_name,' 
             . ' a.contact_company, a.contact_department');
$q->addQuery('b.company_name');
$q->addQuery('c.dept_name');
$q->addQuery('u.user_id');
if ($where) { // Don't assume where is set. Change needed to fix Mantis Bug 0002056
	$q->addWhere($where);
}
$q->addWhere('(contact_owner = ' . $AppUI->user_id . ' OR contact_private = 0)');
//May need to review this order.
$q->addOrder('company_name, contact_company, dept_name, contact_department' 
             . ', contact_last_name');

$contacts = $q->loadHashList('contact_id');

global $task_id, $project_id;
$perms =& $AppUI->acl();
foreach ($contacts as $key => $row) {
	if ($row['user_id'] && !($perms->checkLogin($row['user_id']))) {
		$contacts[$key]['contact_extra'] .=  ' (' . $AppUI->_('Inactive') . ')';
	}
}

?>

<form action="index.php?m=public&a=contact_selector&dialog=1<?php 
echo ((!is_null($call_back)) ? '&call_back='.$call_back : ''); 
?>&company_id=<?php echo $company_id ?>" method='post' name='frmContactSelect'>

<?php
$pointer_department = '';
$pointer_company    = '';

?>
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <label><?php echo $AppUI->_('Select a company'); ?></label>
                <select class="form-control form-control-sm select-company-filter" name="company_id">
                    <option></option>
                    <?php
                    foreach ($aCpies as $key => $value) {
                        ?>
                        <option value="<?=$key?>">
                            <?=$value?>
                        </option>
                        <?php
                    }
                    ?>
                </select>
            </div>
        </div>
    </div>
<div id="contactList">
<?php	
foreach ($contacts as $contact_id => $contact_data) {
	$contact_company = (($contact_data['company_name']) 
	                    ? $contact_data['company_name'] : $contact_data['contact_company']);
	if ($contact_company  && $contact_company != $pointer_company) {
		echo '<h5 class="wildcard '.$contact_data['company_name'].'">'.$contact_company.'</h5>';
		$pointer_company = $contact_company;
	}
	
	$contact_department = (($contact_data['dept_name']) 
	                       ? $contact_data['dept_name'] : $contact_data['contact_department']);
	if ($contact_department && $contact_department != $pointer_department) {
		echo '<h6 class="wildcard '.$contact_data['company_name'].'">'.$contact_department.'</h6>';
		$pointer_department = $contact_department;
	}
	echo ('<input class="wildcard '.$contact_data['company_name'].'" type="checkbox" id="' . $contact_id . '" value="' . $contact_id . '" />');
	echo ('<label class="wildcard '.$contact_data['company_name'].'" for="contact_' . $contact_id . '">&nbsp;' . $contact_data['contact_first_name'] . ' '
	      . $contact_data['contact_last_name'] 
	      . (($contact_data['contact_extra']) ? ($contact_data['contact_extra']) : '') 
	      . '</label>');
	echo ('<br class="wildcard '.$contact_data['company_name'].'" />');
	}
?>
</div>
</form>

<script>
    $(document).ready(function() {
        $('.select-company-filter').select2({
            placeholder: '',
            allowClear: true,
            theme: "bootstrap",
            dropdownParent: $("#contactsModal")
        });

        $('.select-company-filter').on('select2:select', function(e) {
            var data = e.params.data;
            var cls = data.text.trim();
            var listItems = $('#contactList').find('.wildcard');
            for (var item of listItems) {
                if (!$(item).hasClass(cls)) {
                    $(item).hide();
                } else {
                    $(item).show();
                }
            }
        });

        $('.select-company-filter').on('select2:unselect', function() {
            $('#contactList').find('.wildcard').show();
        });
    });
</script>
<?php
    exit();
?>