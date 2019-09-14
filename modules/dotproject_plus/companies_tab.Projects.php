<?php
if (!defined("DP_BASE_DIR")) {
    die("You should not access this file directly.");
}

//Get global variables
include_once ($AppUI->getModuleClass('projects'));
session_start();
$AppUI->savePlace();
global $AppUI, $company_id, $pstatus, $dPconfig;
$df = $AppUI->getPref('SHDATEFORMAT');

//Retrieve data to be presented
$q = new DBQuery();
$q->addTable('projects', 'prj');
$q->addQuery('project_id, project_name, project_start_date, project_status, project_target_budget, project_end_date, project_priority, contact_first_name, contact_last_name, project_percent_complete');
$q->addJoin('users', 'u', 'u.user_id = prj.project_owner');
$q->addJoin('contacts', 'con', 'u.user_contact = con.contact_id');
$q->addWhere('prj.project_company = ' . $company_id);
$projObj = new CProject();
$projList = $projObj->getDeniedRecords($AppUI->user_id);
if (count($projList)) {
    $q->addWhere('NOT (project_id IN (' . implode(',', $projList) . '))');
}
$q->addWhere('prj.project_status <> 7');
$q->addOrder($sort);
?>

<h4><?=$AppUI->_("Projects");?></h4>
<hr>

<?php
if (!($rows = $q->loadList())) {

?>

    <div class="alert alert-secondary text-center" role="alert">
        <?=$AppUI->_("LBL_THERE_IS_NO_PROJECT") ?>
        <?=$AppUI->_("LBL_CLICK"); ?>
        <a class="alert-link" href="javascript:void(0)" onclick="project.new()">
            <?php echo $AppUI->_("LBL_HERE"); ?>
        </a>
        <?php echo $AppUI->_("LBL_TO_CREATE_A_PROJECT"); ?>
    </div>
    <?php
} else {
    ?>
    <div class="row">
        <div class="col-md-12 text-right">
            <a class="btn btn-sm btn-secondary" href="javascript:void(0)" onclick="project.new()">
                Adicionar
            </a>
        </div>
    </div>

    <?php

    foreach ($rows as $row) {
        $project_id=$row["project_id"];
        $start_date = new CDate($row["project_start_date"]);
        $start_date_formated = $start_date->format($df);
        $end_date = new CDate($row["project_end_date"]);
        $end_date_formated = $end_date->format($df);
        $project_name = htmlspecialchars($row["project_name"]);
        $responsible = htmlspecialchars($row["contact_first_name"]) . ' ' . htmlspecialchars($row['contact_last_name']);
        $status = $AppUI->_($pstatus[$row["project_status"]]);
        $project_percent_complete = $row["project_percent_complete"];

        $badgeClass = '';
        switch ($row["project_status"]) {
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

        $urlPrefix = "?m=projects&a=view&project_id=$project_id";
        $urlSufix = ($pstatus[$row["project_status"]] == 'Proposed') ? '&tab=0&subtab=0' : '&tab=1&subtab=0';
        ?>
        <div class="card inner-card">
            <div class="card-body shrink">
                <div class="row">
                    <div class="col-md-11 mouse-cursor-pointer card-project">
                        <h5><a class="project-link" href="<?=$urlPrefix . $urlSufix?>"><?=$project_name?></a></h5>
                    </div>
                    <div class="col-md-1 text-right">
                        <div class="dropdown">
                            <a href="javascript:void(0)" class="link-primary" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-bars"></i>
                            </a>

                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink">
                                <a class="dropdown-item" href="javascript:void(0)" onclick="project.edit(<?=$project_id?>)">
                                    <i class="far fa-edit"></i>
                                    Alterar projeto
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-10">
                        <span>% <?=$AppUI->_('LBL_COMPLETE').': '.$project_percent_complete.
                            ' | '.$AppUI->_('LBL_OWNER').': '.$responsible.
                            ' | '.$AppUI->_('LBL_PERIOD').': '.$start_date_formated.' até '.$end_date_formated?></span>
                    </div>
                    <div class="col-md-2 text-right">
                        <span class="badge <?=$badgeClass?>"><?=$status?></span>
                    </div>
                </div>
            </div>
        </div>

        <?php
    }

}
?>
<script>
    $(document).ready(function () {
        $(".card-project").on("click", function() {
            window.location.href = $(this).find("a.project-link").attr("href");
        });
    });
</script>
