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

require_once (DP_BASE_DIR . "/modules/projects/project-template.php");
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
				<a class="btn btn-sm btn-secondary" href="javascript:void(0)" onclick="project.new()">
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
						$actualEndDate = ((intval($project['project_actual_end_date'])) ? new CDate($project['project_actual_end_date']) : null);
						$actualEndDateFormated = $actualEndDate ? $actualEndDate->format($df) : ' - ';
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
									<div class="col-md-10 mouse-cursor-pointer"> 
										<h5 class="project-card" data="<?=$id?>">
											<a id="<?=$id?>" data-toggle="collapse"
												href="#project_details_<?=$id?>">
												<?=$project_name?>
												<i class="fas fa-caret-down"></i>
											</a>
										</h5>
									</div>
									<div class="col-md-2 text-right">
										<span class="badge <?=$badgeClass?>"><?=$status?></span>

										<div class="dropdown" style="width: 20%; float: right;">
											<a href="javascript:void(0)" class="link-primary" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
												<i class="fas fa-bars"></i>
											</a>
											<div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink">
												<a class="dropdown-item" href="javascript:void(0)" onclick="project.edit(<?=$id?>)">
													<i class="far fa-edit"></i>
													Alterar projeto
												</a>
											</div>
										</div>
									</div>
								</div>
								<div id="project_details_<?= $id ?>" class="collapse">
									<div class="row">
										<table class="table table-sm no-border">
											<tr>
												<th class="text-right" width="15%"><?=$AppUI->_('Company')?>:</th>
												<td><?=$project['company_name']?></td>
												<th class="text-right" width="15%">% <?=$AppUI->_('LBL_COMPLETE')?>:</th>
												<td><?=$percentComplete?></td>
											</tr>
											
											<tr>
												<th class="text-right" width="15%"><?=$AppUI->_('Start')?>:</th>
												<td><?=$startDateFormated?></td>
												<th class="text-right" width="15%"><?=$AppUI->_('End')?>:</th>
												<td><?=$endDateFormated?></td>
											</tr>
											
											<tr>
												<th class="text-right" width="15%"><?=$AppUI->_('Actual')?>:</th>
												<td><?=$actualEndDateFormated?></td>
												<th class="text-right" width="15%"><?=$AppUI->_('Owner')?>:</th>
												<td><?=$responsible?></td>
											</tr>
											
											<tr>
												<th class="text-right" width="15%"><?=$AppUI->_('Tasks') . ' ('. $AppUI->_('My') . ')'?>:</th>
												<td><?=$project['total_tasks'] . ($project['my_tasks'] ? ' ('.$project['my_tasks'].')' : '')?></td>
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
	var main = {

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

			$('.project-card').on('click', main.redirect);
			$('a[data-toggle=collapse]').on('click', main.show);
		},
		
		redirect: function(e) {
			if ($(e.target).is('a')) {
				return;
			}
			const projectId = $(e.target).attr('data');
			window.location.href = '?m=projects&a=view&project_id=' + projectId;
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
		}
	}
	$(document).ready(main.init);
</script>