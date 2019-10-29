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
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 text-right">
            <button type="button" class="btn btn-primary btn-sm" onclick="risks.new(<?=$project_id?>)">
                <?=$AppUI->_("LBL_NEW")?>
            </button>

            <button type="button" class="btn btn-sm btn-secondary" onclick="risks.openRisksManagementPlan(<?=$project_id?>)">
                <?=$AppUI->_("LBL_RISK_MANAGEMENT_PLAN")?>
            </button>

            <button type="button" class="btn btn-sm btn-secondary" onclick="risks.openChecklistAnalisis(<?=$project_id?>)">
                <?=$AppUI->_("LBL_CHECKLIST_ANALYSIS")?>
            </button>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-md-12 text-right">
            <button type="button" class="btn btn-sm btn-secondary" onclick="risks.openWatchList(<?=$project_id?>)">
                <?=$AppUI->_("LBL_WATCHLIST")?>
            </button>

            <button type="button" class="btn btn-sm btn-secondary" onclick="risks.openShortTimeResponseList(<?=$project_id?>)">
                <?=$AppUI->_("LBL_NEARTERM")?>
            </button>

            <button type="button" class="btn btn-sm btn-secondary" onclick="risks.openLessonLearntList(<?=$project_id?>)">
                <?=$AppUI->_("LBL_LESSONS_LIST")?>
            </button>

            <button type="button" class="btn btn-sm btn-secondary" onclick="risks.openResponsesList(<?=$project_id?>)">
                <?=$AppUI->_("LBL_STRATEGYS_LIST")?>
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
                <button type="button" class="btn btn-primary btn-sm" id="btnSave_watchList" onclick="risks.save()"><?=$AppUI->_("LBL_SAVE")?></button>
            </div>
        </div>
    </div>
</div>

<!-- MODAL RISK SHORT TIME RESPONSE LIST -->
<div id="riskShortTimeRespList" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?=$AppUI->_("LBL_WATCHLIST")?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="<?=$AppUI->_("LBL_CLOSE")?>">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="risk-short-time-list-table"></div>
                <div class="risk-short-time-list-form"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light btn-sm" id="btnBack_shortTimeList" onclick="risks.backToShortTimeResponseList()">Voltar</button>
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal"><?=$AppUI->_("LBL_CLOSE")?></button>
                <button type="button" class="btn btn-primary btn-sm" id="btnSave_shortTimeList" onclick="risks.save()"><?=$AppUI->_("LBL_SAVE")?></button>
            </div>
        </div>
    </div>
</div>

<!-- MODAL RISK LESSON LEARNT LIST -->
<div id="riskLessonLearntList" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?=$AppUI->_("LBL_STRATEGYS_LIST")?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="<?=$AppUI->_("LBL_CLOSE")?>">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="risk-lesson-learnt-list-table"></div>
                <div class="risk-lesson-learnt-list-form"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light btn-sm" id="btnBack_lessonLearntList" onclick="risks.backToLessonLearntList()">Voltar</button>
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal"><?=$AppUI->_("LBL_CLOSE")?></button>
                <button type="button" class="btn btn-primary btn-sm" id="btnSave_lessonLearntList" onclick="risks.save()"><?=$AppUI->_("LBL_SAVE")?></button>
            </div>
        </div>
    </div>
</div>

<!-- MODAL RISK RESPONSES LIST -->
<div id="riskResponsestList" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?=$AppUI->_("LBL_LESSONS_LIST")?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="<?=$AppUI->_("LBL_CLOSE")?>">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="risk-responses-list-table"></div>
                <div class="risk-responses-list-form"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light btn-sm" id="btnBack_responsesList" onclick="risks.backToResponsesList()">Voltar</button>
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal"><?=$AppUI->_("LBL_CLOSE")?></button>
                <button type="button" class="btn btn-primary btn-sm" id="btnSave_responsesList" onclick="risks.save()"><?=$AppUI->_("LBL_SAVE")?></button>
            </div>
        </div>
    </div>
</div>



<?php
    include("index_table.php");
?>

</div>
