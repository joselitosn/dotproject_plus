<?php  /* PROJECTS $Id: index.php 6182 2012-11-02 09:17:02Z ajdonnison $ */
if (!defined('DP_BASE_DIR')) {
	die('You should not access this file directly.');
}

global $cBuffer;

// load the companies class to retrieved denied companies
require_once ($AppUI->getModuleClass('companies'));

$df = $AppUI->getPref('SHDATEFORMAT');
$owner = intval($_POST['show_owner']) !== NULL ? intval($_POST['show_owner']) : 0;
$orderby = 'project_end_date';
$q = new DBQuery();
$q->addTable('users', 'u');
$q->addJoin('contacts', 'c', 'c.contact_id = u.user_contact');
$q->addQuery('user_id');
$q->addQuery("CONCAT(contact_last_name, ', ', contact_first_name, ' (', user_username, ')')" 
             . ' AS label');
$q->addOrder('contact_last_name, contact_first_name, user_username');
$userRows = array(0 => $AppUI->_('All Users', UI_OUTPUT_RAW)) + $q->loadHashList();
$bufferUser = arraySelect(
	$userRows, 
	'show_owner', 
	 'class="form-control form-sm select-owner" onchange="javascript:document.pickUser.submit()""', 
	 $owner
);
if (isset($_POST['show_owner'])) {
	$owner = $_POST['show_owner'];
}
if (isset($_POST['department'])) {
	$company_id = $_POST['department'];
}
projects_list_data();
var_dump($projects);
?>
<div id="content">
    <fieldset>
        <legend><?=$AppUI->_('Projects')?></legend>
        <div class="row">
			<div class="col-md-3">
				<form action="?m=projects" method="post" name="pickUser">
					<div class="form-group">
						<?=$bufferUser?>
					</div>
				</form>
			</div>
			<div class="col-md-3">
				<form action="?m=projects" method="post" name="pickCompany">
					<div class="form-group">
						<?=$cBuffer?>
					</div>
				</form>
			</div>
			<div class="col-md-6 text-right">
				<a class="btn btn-sm btn-secondary" href="javascript:void(0)" onclick="">
					Novo projeto
				</a>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<?php
					foreach($projects as $project) {
						$id = $project['project_id'];
						$startDate = new CDate($project["project_start_date"]);
						$startDateFormated = $startDate->format($df);
						$endDate = new CDate($project["project_end_date"]);
						$endDateFormated = $endDate->format($df);
						$project_name = htmlspecialchars($project["project_name"]);
						$responsible = htmlspecialchars($project["user_username"]);
						$status = $AppUI->_($pstatus[$project["project_status"]]);
						$percentComplete = sprintf('%.1f%%', $project['project_percent_complete']);
						$badgeClass = '';
						switch ($project["project_status"]) {
							case 0:
								$badgeClass = ' badge-light';
								break;
							case 1:
								$badgeClass = ' badge-primary';
								break;
							case 2:
								$badgeClass = ' badge-danger';
								break;
							case 3:
								$badgeClass = ' badge-info';
								break;
							case 4:
								$badgeClass = ' badge-secondary';
								break;
							case 5:
								$badgeClass = ' badge-success';
								break;
							case 6:
								$badgeClass = ' badge-warning';
								break;
							case 7:
								$badgeClass = ' badge-dark';
								break;
						}
						?>
						<div class="card inner-card">
							<div class="card-body shrink">
								<div class="row">
									<div class="col-md-5 action mouse-cursor-pointer">
										<h5>
											<a id="<?=$id?>" data-toggle="collapse"
												href="#project_details_<?=$id?>">
												<?=$project_name?>
												<i class="fas fa-caret-down"></i>
											</a>
										</h5>
									</div>
									<div class="col-md-5">
										
									</div>
									<div class="col-md-2 text-right">
										<span class="badge <?=$badgeClass?>"><?=$status?></span>

										<div class="dropdown" style="width: 20%; float: right;">
											<a href="javascript:void(0)" class="link-primary" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
												<i class="fas fa-bars"></i>
											</a>
											<div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink">
												<a class="dropdown-item" href="?m=contacts">
													<i class="fas fa-address-book"></i>
													Contatos
												</a>
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-10">
										<span>% <?=$AppUI->_('LBL_COMPLETE').': '.$percentComplete.
											' | '.$AppUI->_('LBL_OWNER').': '.$responsible.
											' | '.$AppUI->_('LBL_PERIOD').': '.$startDateFormated.' até '.$endDateFormated?></span>
									</div>
									<div class="col-md-2 text-right">
										<span class="badge <?=$badgeClass?>"><?=$status?></span>
									</div>
								</div>
								<div id="project_details_<?= $id ?>" class="collapse">
									<div class="row">
										<table class="table table-sm no-border">
											<tr>
												<th class="text-right" width="15%"><?php echo $AppUI->_("Role responsability"); ?>:</th>
												<!-- <td><?php echo $role['obj']->human_resources_role_responsability ?></td> -->
											</tr>
											<tr>
												<th class="text-right" width="15%"><?php echo $AppUI->_("Role authority"); ?>:</th>
												<!-- <td><?php echo $role['obj']->human_resources_role_authority ?></td> -->
											</tr>
											<tr>
												<th class="text-right" width="15%"><?php echo $AppUI->_("Role competence"); ?>:</th>
												<!-- <td><?php echo $role['obj']->human_resources_role_competence ?></td> -->
											</tr>
										</table>
									</div>
								</div>
							</div>
						</div>
						<?php
					}
				?>
			</div>
		</div>
	</fieldset>
</div>
<script>
	var projects = {

		init: function() {
			$(".select-owner").select2({
				placeholder: "Responsável",
				allowClear: true,
				theme: "bootstrap"
			});
			$(".select-company").select2({
				placeholder: "Empresa",
				allowClear: true,
				theme: "bootstrap"
			});

			$('a[data-toggle=collapse]').on('click', projects.show);
        },

        show: function (e) {
            const collapseRole = $(e.target);
            $('#project_details_'+e.target.id).on('show.bs.collapse', function () {
                collapseRole.find('i').removeClass('fa-caret-down');
                collapseRole.find('i').addClass('fa-caret-up');
            });
            $('#project_details_'+e.target.id).on('hide.bs.collapse', function () {
                collapseRole.find('i').removeClass('fa-caret-up');
                collapseRole.find('i').addClass('fa-caret-down');
            });
        },
	}
	$(document).ready(projects.init);
</script>





<?php
// // Let's update project status!
// if (isset($_GET['update_project_status']) && isset($_GET['project_status']) 
//    && isset($_GET['project_id'])) {
// 	$projects_id = $_GET['project_id']; // This must be an array
	
// 	foreach ($projects_id as $project_id) {
// 		if (! getPermission('projects', 'edit', (int)$project_id)) {
// 			continue; /* Cannot update the status of a project we can't edit */
// 		}
// 		$q->addTable('projects');
// 		$q->addUpdate('project_status', $_GET['project_status']);
// 		$q->addWhere('project_id = ' . (int)$project_id);
// 		$q->exec();
// 		$q->clear();
// 	}
// 	// Insert our closing for the select
// 	$bufferUser .= '</select>'."\n";
// }

// End of project status update
// retrieve any state parameters
// if (isset($_GET['tab'])) {
// 	$AppUI->setState('ProjIdxTab', intval(dPgetCleanParam($_GET, 'tab')));
// }

// $tab = $AppUI->getState('ProjIdxTab') !== NULL ? $AppUI->getState('ProjIdxTab') : 500;
// $currentTabId = $tab;
// $active = intval(!$AppUI->getState('ProjIdxTab'));

// if (isset($_POST['company_id'])) {
// 	$AppUI->setState('ProjIdxCompany', intval($_POST['company_id']));
// }
// $company_id = (($AppUI->getState('ProjIdxCompany') !== NULL) 
//                ? $AppUI->getState('ProjIdxCompany') 
//                : $AppUI->user_company);


// if (isset($_POST['department'])) {
// 	$AppUI->setState('ProjIdxDepartment', dPgetCleanParam($_POST, 'department'));
	
// 	//if department is set, ignore the company_id field
// 	unset($company_id);
// }
// $department = (($AppUI->getState('ProjIdxDepartment') !== NULL) 
//                ? $AppUI->getState('ProjIdxDepartment') 
//                : ($company_prefix . $AppUI->user_company));

// //if $department contains the $company_prefix string that it's requesting a company
// // and not a department.  So, clear the $department variable, and populate the $company_id variable.
// if (!(mb_strpos($department, $company_prefix)===false)) {
// 	$company_id = mb_substr($department,mb_strlen($company_prefix));
// 	$AppUI->setState('ProjIdxCompany', $company_id);
// 	unset($department);
// }

// $valid_ordering = array('project_name', 'user_username', 'my_tasks desc', 'total_tasks desc',
//                         'total_tasks', 'my_tasks', 'project_color_identifier', 'company_name', 
//                         'project_end_date', 'project_start_date', 'project_actual_end_date', 
//                         'task_log_problem DESC,project_priority', 'project_status', 
//                         'project_percent_complete');

// $orderdir = $AppUI->getState('ProjIdxOrderDir') ? $AppUI->getState('ProjIdxOrderDir') : 'asc';
// if (isset($_GET['orderby']) && in_array($_GET['orderby'], $valid_ordering)) {
// 	$orderdir = (($AppUI->getState('ProjIdxOrderDir') == 'asc') ? 'desc' : 'asc');
// 	$AppUI->setState('ProjIdxOrderBy', $_GET['orderby']);
// }
// $AppUI->setState('ProjIdxOrderDir', $orderdir);

// prepare the users filter
// if (isset($_POST['show_owner'])) {
// 	$AppUI->setState('ProjIdxowner', intval($_POST['show_owner']));
// }


/* setting this to filter project_list_data function below
 0 = undefined
 3 = active
 5 = completed
 7 = archived

Because these are "magic" numbers, if the values for ProjectStatus change under 'System Admin', 
they'll need to change here as well (sadly).
*/
?>





<?php
// if ($tab != 7 && $tab != 8) {
// 	$project_status = $tab;
// } else if ($tab == 0) {
// 	$project_status = 0;
// }
// if ($tab == 5 || $tab == 7) {
// 	$project_active = 0;
// }

//for getting permissions for records related to projects
// $obj_project = new CProject();
// collect the full (or filtered) projects list data via function in projects.class.php
// // setup the title block
// $titleBlock = new CTitleBlock('Projects', 'applet3-48.png', $m, ($m . '.' . $a));
// $titleBlock->addCell($AppUI->_('Owner') . ':');
// $titleBlock->addCell(('<form action="?m=projects" method="post" name="pickUser">' . "\n" 
//                       . $bufferUser . "\n" . '</form>' . "\n"));
// $titleBlock->addCell($AppUI->_('Company') . '/' . $AppUI->_('Division') . ':');
// $titleBlock->addCell(('<form action="?m=projects" method="post" name="pickCompany">' . "\n" 
//                       . $cBuffer . "\n" .  '</form>' . "\n"));
// $titleBlock->addCell();
// if ($canAuthor) {
// 	$titleBlock->addCell(('<form action="?m=projects&amp;a=addedit" method="post">' . "\n" 
// 	                      . '<input type="submit" class="button" value="' 
// 	                      . $AppUI->_('new project') . '" />'. "\n" . '</form>' . "\n"));
// }
// $titleBlock->show();

// $project_types = dPgetSysVal('ProjectStatus');


// // count number of projects per project_status
// $q->addTable('projects', 'p');
// $q->addQuery('p.project_status, COUNT(p.project_id) as count');
// $obj_project->setAllowedSQL($AppUI->user_id, $q, null, 'p');
// if ($owner > 0) {
// 	$q->addWhere('p.project_owner = ' . $owner);
// }
// if (isset($department)) {
// 	$q->addJoin('project_departments', 'pd', 'pd.project_id = p.project_id');
// 	if (!$addPwOiD) { // Where is this set??
// 		$q->addWhere('pd.department_id = ' . (int)$department);
// 	}
// } else if ($company_id &&!$addPwOiD) {
// 	$q->addWhere('p.project_company = ' . $company_id);
// }
// $q->addGroup('project_status');
// $statuses = $q->loadHashList('project_status');
// $q->clear();
// $all_projects = 0;
// foreach ($statuses as $k => $v) {
// 	$project_status_tabs[$v['project_status']] = ($AppUI->_($project_types[$v['project_status']]) 
// 													  . ' (' . $v['count'] . ')');
// 	//count all projects
// 	$all_projects += $v['count'];
// }

// //set file used per project status title
// $fixed_status = array('In Progress' => 'vw_idx_active',
// 					  'Complete' => 'vw_idx_complete',
// 					  'Archived' => 'vw_idx_archived');

/**
* Now, we will figure out which vw_idx file are available
* for each project status using the $fixed_status array 
*/
// $project_status_file = array();
// foreach ($project_types as $status_id => $status_title) {
// 	//if there is no fixed vw_idx file, we will use vw_idx_proposed
// 	$project_status_file[$status_id] = ((isset($fixed_status[$status_title])) 
// 										? $fixed_status[$status_title] : 'vw_idx_proposed');
// }

// // tabbed information boxes
// $tabBox = new CTabBox('?m=projects', DP_BASE_DIR . '/modules/projects/', $tab);


// foreach ($project_types as $psk => $project_status) {
// 	var_dump($project_status_file[$psk]);
//     $tabBox->add($project_status_file[$psk], (($project_status_tabs[$psk]) ? $project_status_tabs[$psk] : $AppUI->_($project_status)), true, $psk);
// }
// $min_view = true;
// $tabBox->add('vw_idx_proposed', $AppUI->_('All') . ' (' . $all_projects . ')',true);
// $tabBox->add('viewgantt', 'Gantt');
// $tabBox->show();
?>
