<?php /* PROJECTS $Id: addedit.php 6216 2013-04-08 12:52:57Z cyberhorse $ */
if (!defined('DP_BASE_DIR')) {
	die('You should not access this file directly.');
}


$project_id = intval(dPgetParam($_GET, 'project_id', 0));
$company_id = intval(dPgetParam($_GET, 'company_id', 0));
$company_internal_id = intval(dPgetParam($_GET, 'company_internal_id', 0));
$contact_id = intval(dPgetParam($_GET, 'contact_id', 0));

// check permissions for this record
$canEdit = getPermission($m, 'edit', $project_id);
$canAuthor = getPermission($m, 'add', $project_id);
if (!(($canEdit && $project_id) || ($canAuthor && !($project_id)))) {
	$AppUI->redirect('m=public&a=access_denied');
}

// get a list of permitted companies
require_once($AppUI->getModuleClass ('companies'));

$row = new CCompany();
$companies = $row->getAllowedRecords($AppUI->user_id, 'company_id,company_name', 'company_name');
$companies = arrayMerge(array('0'=>''), $companies);

// get internal companies
// 6 is standard value for internal companies
$companies_internal = $row->listCompaniesByType(array('6')); 
$companies_internal = arrayMerge(array('0'=>''), $companies_internal);

// pull users
$q = new DBQuery();
$q->addTable('users','u');
$q->addTable('contacts','con');
$q->addQuery('user_id');
$q->addQuery('CONCAT_WS(", ",contact_last_name,contact_first_name)');
$q->addOrder('contact_last_name');
$q->addWhere('u.user_contact = con.contact_id');
$users = $q->loadHashList();

// load the record data
$row = new CProject();

if (!$row->load($project_id, false) && $project_id > 0) {
	$AppUI->setMsg('Project');
	$AppUI->setMsg("invalidID", UI_MSG_ERROR, true);
	$AppUI->redirect();
} else if (count($companies) < 2 && $project_id == 0) {
	$AppUI->setMsg("noCompanies", UI_MSG_ERROR, true);
	$AppUI->redirect();
}

if ($project_id == 0 && $company_id > 0) {
	$row->project_company = $company_id;
}

// add in the existing company if for some reason it is dis-allowed
if ($project_id && !array_key_exists($row->project_company, $companies)) {
	$q  = new DBQuery;
	$q->addTable('companies', 'co');
	$q->addQuery('company_name');
	$q->addWhere('co.company_id = '.$row->project_company);
	$sql = $q->prepare();
	$q->clear();
	$companies[$row->project_company] = db_loadResult($sql);
}

// get critical tasks (criteria: task_end_date)
$criticalTasks = ($project_id > 0) ? $row->getCriticalTasks() : NULL;

// get ProjectPriority from sysvals
$projectPriority = dPgetSysVal('ProjectPriority');

// format dates
$df = $AppUI->getPref('SHDATEFORMAT');

$start_date = intval($row->project_start_date) ? new CDate($row->project_start_date) : null;
$end_date = intval($row->project_end_date) ? new CDate($row->project_end_date) : null;
$actual_end_date = intval($criticalTasks[0]['task_end_date']) ? new CDate($criticalTasks[0]['task_end_date']) : null;
$style = (($actual_end_date > $end_date) && !empty($end_date)) ? 'style="color:red; font-weight:bold"' : '';

//Build display list for departments
$company_id = $row->project_company;
$selected_departments = array();
if ($project_id) {
	$q = new DBQuery;
	$q->addTable('project_departments');
	$q->addQuery('department_id');
	$q->addWhere('project_id = ' . $project_id);
	$selected_departments = $q->loadColumn();
}
$departments_count = 0;
$department_selection_list = getDepartmentSelectionList($company_id, $selected_departments);
if ($department_selection_list!='' || $project_id) {
	$department_selection_list = ($AppUI->_('Departments')."<br />\n"
	                              . '<select name="dept_ids[]" class="text">' . "\n"
	                              . '<option value="0"></option>' . "\n"
	                              . $department_selection_list . "\n" .'</select>');
} else {
	$department_selection_list = ('<input type="button" class="button" value="'
	                              . $AppUI->_('Select department...') 
	                              . '" onclick="javascript:popDepartment();" />' 
	                              . '<input type="hidden" name="project_departments"');
}

// Get contacts list
$selected_contacts = array();
if ($project_id) {
	$q = new DBQuery;
	$q->addTable('project_contacts');
	$q->addQuery('contact_id');
	$q->addWhere('project_id = ' . $project_id);
	$res =& $q->exec();
	for ($res; ! $res->EOF; $res->MoveNext())
		$selected_contacts[] = $res->fields['contact_id'];
	$q->clear();
}
if ($project_id == 0 && $contact_id > 0) {
	$selected_contacts[] = $contact_id;
}
?>

<form name="addEditProject" action="?m=projects" method="post">
	<input type="hidden" name="dosql" value="do_project_aed" />
	<input type="hidden" name="project_id" value="<?php echo $project_id;?>" />
	<input type="hidden" name="project_creator" value="<?php echo $AppUI->user_id;?>" />
    <input name='project_contacts' type='hidden' value="<?php echo implode(',', $selected_contacts); ?>" />
    <input name='project_short_name' type='hidden' value="" />

    <div class="form-group">
        <span class="required"></span>
        <?=$AppUI->_('requiredField');?>
    </div>

    <div class="form-group">
        <label for="project_name" class="required">
            <?php echo $AppUI->_("LBL_NOME"); ?>
        </label>
        <input type="text" class="form-control form-control-sm" name="project_name"
               value="<?php echo dPformSafe($row->project_name); ?>" size="40" maxlength="50"  onblur="project.setShortName();"/>
    </div>

    <div class="form-group companyContainer">
        <label for="company_name" class="required">
            <?php echo $AppUI->_("Company"); ?>
        </label>

        <select class="form-control form-control-sm select-company" name="project_company">
            <?php
            foreach ($companies as $key => $option) {
                $selected = $row->project_company == $key ? 'selected="selected"' : '';
                ?>
                <option value="<?=$key?>" <?=$selected?>><?=$option?></option>
                <?php
            }
            ?>
        </select>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="start_date">
                    <?php echo $AppUI->_("Start Date");?>
                </label>
                <input type="hidden" name="project_start_date" value="<?=$start_date ? $start_date->format(FMT_TIMESTAMP_DATE) : ''?>" />
                <input type="text" class="form-control form-control-sm datepicker" name="start_date" value="" />
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="end_date">
                    <?php echo $AppUI->_("Target End Date"); ?>
                </label>
                <input type="hidden" name="project_end_date" value="<?=$end_date ? $end_date->format(FMT_TIMESTAMP_DATE) : ''?>" />
                <input type="text" class="form-control form-control-sm datepicker" name="end_date" value="" />
            </div>
        </div>
    </div>
    <div class="row statusPriorityContainer">
        <div class="col-md-6">
            <div class="form-group">
                <label for="project_status">
                    <?php echo $AppUI->_("Status"); ?>
                </label>
                <select class="form-control form-control-sm project-status" name="project_status">
                    <?php
                    $status = $row->project_status != null ? $row->project_status : 1;
                    foreach ($pstatus as $key => $option) {
                        $selected = $status == $key ? 'selected="selected"' : '';
                        ?>
                        <option value="<?=$key?>" <?=$selected?>><?=$AppUI->_($option);?></option>
                        <?php
                    }
                    ?>
                </select>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="project_priority">
                    <?php echo $AppUI->_("Priority"); ?>
                </label>

                <select class="form-control form-control-sm project-priority" name="project_priority">
                    <?php
                    foreach ($projectPriority as $key => $option) {
                        $selected = $row->project_priority == $key ? 'selected="selected"' : '';
                        ?>
                        <option value="<?=$key?>" <?=$selected?>><?=$AppUI->_($option);?></option>
                        <?php
                    }
                    ?>
                </select>
            </div>
        </div>
    </div>

    <div class="row ownerContainet">
        <div class="col-md-6">
            <div class="form-group">
                <label for="project_owner">
                    <?php echo $AppUI->_("LBL_OWNER"); ?>
                </label>

                <select class="form-control form-control-sm project-responsible" name="project_owner">
                    <?php
                    $owner = $row->project_owner ? $row->project_owner : $AppUI->user_id;
                    foreach ($users as $key => $option) {
                        $selected = $owner == $key ? 'selected="selected"' : '';
                        ?>
                        <option value="<?=$key?>" <?=$selected?>><?=$option;?></option>
                        <?php
                    }
                    ?>
                </select>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="budget">
                    <?php echo $AppUI->_("Target Budget"); ?>
                </label>
                <input type="text" class="form-control form-control-sm" id="budget" name="project_target_budget" value="<?=$row->project_target_budget?>" maxlength="10" />
            </div>
        </div>
    </div>

    <div class="form-group">
        <label for="project_description">
            <?php echo $AppUI->_("LBL_PROJECT_SCOPE_DECLARATION"); ?>
        </label>
        <textarea class="form-control form-control-sm" rows="5" name="project_description"><?=$row->project_description?></textarea>
    </div>
</form>

<script>

    $(document).ready(function() {
        $(".select-company").select2({
            allowClear: true,
            placeholder: "",
            theme: "bootstrap",
            dropdownParent: $(".companyContainer")
        });

        $(".project-status").select2({
            allowClear: true,
            placeholder: "",
            theme: "bootstrap",
            dropdownParent: $(".statusPriorityContainer")
        });

        $(".project-priority").select2({
            allowClear: true,
            placeholder: "",
            theme: "bootstrap",
            dropdownParent: $(".statusPriorityContainer")
        });

        $(".project-responsible").select2({
            allowClear: true,
            placeholder: "",
            theme: "bootstrap",
            dropdownParent: $(".ownerContainet")
        });

        $( ".datepicker" ).datepicker({
            dateFormat: 'dd/mm/yy',
            onSelect: project.dateSelected
        });

        $("#budget").mask("000.000.000.000.000,00", {reverse: true});

        project.formatDates();
        project.setShortName();
    });

</script>


<?php
function getDepartmentSelectionList($company_id, $checked_array = array(), $dept_parent=0, $spaces = 0) {
	global $departments_count;
	$parsed = '';

	if ($departments_count < 6) $departments_count++;
	
	$q  = new DBQuery;
	$q->addTable('departments');
	$q->addQuery('dept_id, dept_name');
	$q->addWhere("dept_parent = '$dept_parent' and dept_company = '$company_id'");
	$q->addOrder('dept_name');

	$depts_list = $q->loadHashList("dept_id");

	foreach ($depts_list as $dept_id => $dept_info) {
		$selected = in_array($dept_id, $checked_array) ? ' selected="selected"' : '';

		$parsed .= ('<option value="' . $dept_id . '"' . $selected . '>' 
		            . str_repeat('&nbsp;', $spaces) . $dept_info['dept_name'] . '</option>');
		$parsed .= getDepartmentSelectionList($company_id, $checked_array, $dept_id, $spaces+5);
	}
	
	return $parsed;
}

exit();
?>
