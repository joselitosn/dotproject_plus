<?php
if (!defined('DP_BASE_DIR')) {
    die('You should not access this file directly.');
}
$project_id = intval(dPgetParam($_GET, "project_id", 0));

require_once($AppUI->getModuleClass('projects'));

$extra = array();
$project = new CProject();
$projects = $project->getAllowedRecords($AppUI->user_id, 'project_id,project_name', 'project_name', null, $extra);
$projects = arrayMerge(array('0' => $AppUI->_('LBL_ALL', UI_OUTPUT_JS)), $projects);

?>

<div class="row">
    <div class="col-md-12 text-right">

        <button type="button" class="btn btn-sm btn-secondary" onclick="risks.openRisksManagementPlan(<?=$project_id?>)">
            <?=$AppUI->_("LBL_RISK_MANAGEMENT_PLAN")?>
        </button>

        <button type="button" class="btn btn-sm btn-secondary" onclick="risks.openChecklistAnalisis(<?=$project_id?>)">
            <?=$AppUI->_("LBL_CHECKLIST_ANALYSIS")?>
        </button>

        <button type="button" class="btn btn-sm btn-secondary" onclick="risks.openWatchList(<?=$project_id?>)">
            <?=$AppUI->_("LBL_WATCHLIST")?>
        </button>

        <button type="button" class="btn btn-sm btn-secondary" onclick="">
            <?=$AppUI->_("LBL_NEARTERM")?>
        </button>

        <button type="button" class="btn btn-sm btn-secondary" onclick="">
            <?=$AppUI->_("LBL_LESSONS_LIST")?>
        </button>

        <button type="button" class="btn btn-sm btn-secondary" onclick="">
            <?=$AppUI->_("LBL_STRATEGYS_LIST")?>
        </button>

        <button type="button" class="btn btn-sm btn-secondary" onclick="risks.new(<?=$project_id?>)">
            <?=$AppUI->_("LBL_NEW")?>
        </button>
    </div>
</div>

<!-- MODAL RISK MANAGEMENT PLAN -->
<div id="riskManagementPlanModal" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?=$AppUI->_("LBL_RISK_MANAGEMENT_PLAN")?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="<?=$AppUI->_("LBL_CLOSE")?>">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body risk-management-plan-modal">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal"><?=$AppUI->_("LBL_CLOSE")?></button>
                <button type="button" class="btn btn-primary btn-sm" id="riskManagementPlanModal_SaveBtn"><?=$AppUI->_("LBL_SAVE")?></button>
            </div>
        </div>
    </div>
</div>

<!-- MODAL RISK CHECKLIST ANALISIS -->
<div id="riskChecklistAnalisis" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?=$AppUI->_("LBL_CHECKLIST_ANALYSIS")?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="<?=$AppUI->_("LBL_CLOSE")?>">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body risk-checklist-analisis-modal">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal"><?=$AppUI->_("LBL_CLOSE")?></button>
                <button type="button" class="btn btn-primary btn-sm" onclick="risks.confirmIdentifiedRisks()"><?=$AppUI->_("LBL_RISKS_CHECKLIST_ACTION")?></button>
            </div>
        </div>
    </div>
</div>

<!-- MODAL RISK WATCH LIST -->
<div id="riskWatchList" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?=$AppUI->_("LBL_WATCHLIST")?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="<?=$AppUI->_("LBL_CLOSE")?>">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="risk-watch-list-table"></div>
                <div class="risk-watch-list-form"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light btn-sm" id="btnBack_watchList" onclick="risks.backToWatchList()">Voltar</button>
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal"><?=$AppUI->_("LBL_CLOSE")?></button>
                <button type="button" class="btn btn-primary btn-sm" onclick="risks.save()"><?=$AppUI->_("LBL_SAVE")?></button>
            </div>
        </div>
    </div>
</div>



<?php
    include("index_table.php");
?>