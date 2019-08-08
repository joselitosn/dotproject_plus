<?php
if (!defined('DP_BASE_DIR')) {
    die('You should not access this file directly.');
}
//$AppUI->savePlace();
////require_once (DP_BASE_DIR . "/modules/risks/translations.php");
//// retrieve any state parameters
//if (isset($_REQUEST['project_id'])) {
//    $AppUI->setState('RisksIdxProject', intval($_REQUEST['project_id']));
//}
$project_id = $AppUI->getState('RisksIdxProject') !== NULL ? $AppUI->getState('RisksIdxProject') : 0;
if (dPgetParam($_GET, 'tab', -1) != -1) {
    $AppUI->setState('RisksIdxTab', intval(dPgetParam($_GET, 'tab')));
}
$tab = $AppUI->getState('RisksIdxTab') !== NULL ? $AppUI->getState('RisksIdxTab') : 0;
$active = intval(!$AppUI->getState('RisksIdxTab'));

require_once($AppUI->getModuleClass('projects'));

$extra = array();
$project = new CProject();
$projects = $project->getAllowedRecords($AppUI->user_id, 'project_id,project_name', 'project_name', null, $extra);
$projects = arrayMerge(array('0' => $AppUI->_('LBL_ALL', UI_OUTPUT_JS)), $projects);

?>

<div class="row">
    <div class="col-md-12 text-right">
        <button type="button" class="btn btn-sm btn-secondary" onclick="">
            <?=$AppUI->_("LBL_RISK_MANAGEMENT_PLAN")?>
        </button>

        <button type="button" class="btn btn-sm btn-secondary" onclick="">
            <?=$AppUI->_("LBL_CHECKLIST_ANALYSIS")?>
        </button>

        <button type="button" class="btn btn-sm btn-secondary" onclick="">
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

<?php
    include("index_table.php");
?>