<?php
if (!defined('DP_BASE_DIR')) {
    die('You should not access this file directly.');
}

global $AppUI, $dPconfig, $locale_char_se;

$human_resource_id = intval(dPgetParam($_GET, 'human_resource_id'));
if ($human_resource_id == -1) {
    $human_resource_id = 0;
}

$hr = new CHumanResource();

if ($human_resource_id && !$hr->load($human_resource_id)) {
    $AppUI->setMsg('Human Resources');
    $AppUI->setMsg('invalidID', UI_MSG_ERROR, true);
    echo $AppUI->getMsg();
    exit();
}


$user_id = intval(dPgetParam($_GET, 'user_id', 0));
if (!$human_resource_id)
    $hr->human_resource_user_id = $user_id;

$company_id = intval(dPgetParam($_GET, 'company_id', 0));
$query = new DBQuery();
$query->addTable('companies', 'c');
$query->addQuery('company_name');
$query->addWhere('c.company_id = ' . $company_id);
$res = & $query->exec();
$query->clear();

$contact_id = intval(dPgetParam($_GET, 'contact_id', 0));
$query = new DBQuery;
$query->addTable('contacts', 'c');
$query->addQuery('contact_last_name, contact_first_name');
$query->addWhere('c.contact_id = ' . $contact_id);
$res = & $query->exec();
$contact_name = $res->fields['contact_first_name'] ." ". $res->fields['contact_last_name'];
$query->clear();

function cal_work_day_conv($val) {
    global $locale_char_set;
    setlocale(LC_ALL, 'en_AU' . (($locale_char_set) ? ('.' . $locale_char_set) : '.utf8'));
    $wk = Date_Calc::getCalendarWeek(null, null, null, "%a", LOCALE_FIRST_DAY);
    setlocale(LC_ALL, $AppUI->user_lang);

    $day_name = $wk[($val - LOCALE_FIRST_DAY) % 7];
    if ($locale_char_set == "utf-8" && function_exists("utf8_encode")) {
        $day_name = utf8_encode($day_name);
    }
    return htmlentities($day_name, ENT_COMPAT, $locale_char_set);
}

$cwd = array();
$cwd[0] = '0';
$cwd[1] = '1';
$cwd[2] = '2';
$cwd[3] = '3';
$cwd[4] = '4';
$cwd[5] = '5';
$cwd[6] = '6';
$cwd_conv = array_map('cal_work_day_conv', $cwd);
 
//translation to portuguese
$cwd_conv[0]= $AppUI->_("LBL_SUNDAY" );
$cwd_conv[1]=$AppUI->_("LBL_MONDAY");
$cwd_conv[2]=$AppUI->_("LBL_TUESDAY" );
$cwd_conv[3]=$AppUI->_("LBL_WEDNESDAY");
$cwd_conv[4]=$AppUI->_("LBL_THURSDAY");
$cwd_conv[5]=$AppUI->_("LBL_FRIDAY");
$cwd_conv[6]=$AppUI->_("LBL_SATURDAY");

$roles = array();
if ($human_resource_id) {
    $query = new DBQuery;
    $query->addTable("human_resource_roles", "r");
    $query->addQuery("h.human_resources_role_name, h.human_resources_role_id");
    $query->innerJoin("human_resources_role", "h", "h.human_resources_role_id = r.human_resources_role_id");
    $query->addWhere("r.human_resource_id = " . $human_resource_id);
    $sql = $query->prepare();
    $roles = db_loadList($sql);
}

$selectedRolesIds = array();
foreach ($roles as $r) {
    $selectedRolesIds[] = $r['human_resources_role_id'];
}

$query = new DBQuery;
$query->addTable("human_resources_role", "r");
$query->addQuery("r.human_resources_role_name, r.human_resources_role_id");
$query->addWhere("r.human_resources_role_company_id = " . $company_id);
$sql = $query->prepare();
$company_roles = db_loadList($sql);

require_once (DP_BASE_DIR . "/modules/monitoringandcontrol/control/controller_resources_costs.class.php");
$controllerResourceCost = new ControllerResourcesCosts();
$costsList = array();
$costsList = $controllerResourceCost->getRecordsByUser($user_id);

?>

<form name="editfrm">
    <input type="hidden" name="dosql" value="do_hr_aed" />
    <input type="hidden" name="company_id" value="<?php echo $company_id; ?>" />
    <input type="hidden" id="costsHidden" name="costs" value='<?=json_encode($costsList, JSON_UNESCAPED_SLASHES)?>' />
    <input type="hidden" id="rolesHidden" value='<?=json_encode($selectedRolesIds, JSON_UNESCAPED_SLASHES)?>' />
    <input type="hidden" name="human_resource_id" value="<?php echo dPformSafe($human_resource_id); ?>" />
    <input type="hidden" name="human_resource_user_id" value="<?php echo dPformSafe($hr->human_resource_user_id); ?>" />
    <input type="hidden" name="daily_working_hours" value="<?php echo dPformSafe($dPconfig['daily_working_hours']); ?>" />

    <div class="form-group">
        <span class="required"></span>
        <?=$AppUI->_('requiredField');?>
    </div>

    <div class="form-group">
        <label for="first_name">
            Nome
        </label>
        <br>
        <label>
            <?php echo $contact_name ?>
            (<a class="link" href="index.php?m=contacts&a=addedit&contact_id=<?php echo $contact_id ?>" target="_blank">Acessar contato</a>)
        </label>
    </div>

    <div class="form-group">
        <label for="human_resource_lattes_url">
            <?php echo $AppUI->_('Lattes URL'); ?>
        </label>
        <input type="text" class="form-control form-control-sm" name="human_resource_lattes_url" value="<?=dPformSafe($hr->human_resource_lattes_url)?>" maxlength="100" />
    </div>

    <div class="form-group">
        <label for="human_resource_lattes_url">
            <?php echo $AppUI->_('Role'); ?>
        </label>
        <select class="form-control form-control-sm select-roles" name="roles[]" multiple="multiple">
            <?php
                foreach ($company_roles as $role) {
            ?>
                    <option name="<?=$role["human_resources_role_name"]?>" value="<?=$role["human_resources_role_id"]?>">
                        <?=$role["human_resources_role_name"]?>
                    </option>
            <?php
                }
            ?>
        </select>
    </div>

    <div class="form-group">
        <label for="human_resource_eventual_envolviment" class="required">
            <?php echo $AppUI->_('LBL_EVENTUAL_INVOLVIMENT'); ?>
        </label>
        <input type="radio" name="eventual" value="0" <?=$hr->eventual != "1" ? "checked=\"checked\"":""; ?> onchange="hr.displayWorkingDays()" />
            <?=$AppUI->_("LBL_NO") ?>
        <input type="radio" name="eventual" value="1" <?=$hr->eventual == "1" ? "checked=\"checked\"":""; ?> onchange="hr.displayWorkingDays()" />
            <?=$AppUI->_("LBL_YES") ?>
        <div class="alert alert-secondary" role="alert">
            <small><?=$AppUI->_("LBL_EVENTUAL_INVOLVIMENT_HINT"); ?></small>
        </div>
    </div>

    <div id="weekly_working_hours" class="form-group">
        <label for="weekly_working_hours">
            <?=$AppUI->_('Weekday working hours')?>
        </label>
        <table class="table table-sm table-bordered text-center">
            <thead class="thead-dark">
                <tr>
                    <th><?=$cwd_conv[0]?></th>
                    <th><?=$cwd_conv[1]?></th>
                    <th><?=$cwd_conv[2]?></th>
                    <th><?=$cwd_conv[3]?></th>
                    <th><?=$cwd_conv[4]?></th>
                    <th><?=$cwd_conv[5]?></th>
                    <th><?=$cwd_conv[6]?></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <input type="text" class="form-control form-control-sm" name="human_resource_mon" value="<?=dPformSafe($hr->human_resource_mon)?>" size="2" maxlength="5" />
                    </td>
                    <td>
                        <input type="text" class="form-control form-control-sm" name="human_resource_tue" value="<?=dPformSafe($hr->human_resource_tue)?>" size="2" maxlength="5" />
                    </td>
                    <td>
                        <input type="text" class="form-control form-control-sm" name="human_resource_wed" value="<?=dPformSafe($hr->human_resource_wed)?>" size="2" maxlength="5" />
                    </td>
                    <td>
                        <input type="text" class="form-control form-control-sm" name="human_resource_thu" value="<?=dPformSafe($hr->human_resource_thu)?>" size="2" maxlength="5" />
                    </td>
                    <td>
                        <input type="text" class="form-control form-control-sm" name="human_resource_fri" value="<?=dPformSafe($hr->human_resource_fri)?>" size="2" maxlength="5" />
                    </td>
                    <td>
                        <input type="text" class="form-control form-control-sm" name="human_resource_sat" value="<?=dPformSafe($hr->human_resource_sat)?>" size="2" maxlength="5" />
                    </td>
                    <td>
                        <input type="text" class="form-control form-control-sm" name="human_resource_sun" value="<?=dPformSafe($hr->human_resource_sun)?>" size="2" maxlength="5" />
                    </td>
                </tr>
            </tbody>
            <tfoot class="text-left">
                <tr>
                    <td colspan="7">
                        <small><?=$AppUI->_('Daily working hours') . ': ' . $dPconfig['daily_working_hours']?></small>
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>

    <div class="form-group">
        <label for="weekly_working_hours">
            <?=$AppUI->_('LBL_USER_COSTS')?>
        </label>
        <div class="row">
            <div class="col-md-4">
                <label for="date_edit2">
                    <?=$AppUI->_('LBL_INICIO_VIGENCIA')?>
                </label>
                <input type="text" name="date_edit2" class="form-control form-control-sm datepicker" value="" />
            </div>
            <div class="col-md-4">
                <label for="date_edit3">
                    <?=$AppUI->_('LBL_FIM_VIGENCIA')?>
                </label>
                <input type="text" name="date_edit3" class="form-control form-control-sm datepicker" value="" />
            </div>
            <div class="col-md-3">
                <label for="date_edit3">
                    <?=$AppUI->_('LBL_TAXA_PADRAO')?>
                </label>
                <input type="text" class="form-control form-control-sm" id="tx_pad" name="tx_pad" value="" />
            </div>
            <div class="col-md-1">
                <label for="date_edit3">
                    &nbsp;
                </label>
                <button type="button" class="btn btn-sm btn-primary" title="<?=$AppUI->_('LBL_ADD')?>" onclick="hr.addCost()">
                    <i class="far fa-plus-square"></i>
                </button>
            </div>
        </div>
    </div>
    <div class="form-group">
        <table class="table table-sm table-bordered text-center">
            <thead class="thead-dark">
            <tr>
                <th><?=$AppUI->_('LBL_INICIO_VIGENCIA')?></th>
                <th><?=$AppUI->_('LBL_FIM_VIGENCIA')?></th>
                <th><?=$AppUI->_('LBL_TAXA_PADRAO')?> (<?=dPgetConfig("currency_symbol") ?>)</th>
                <th>&nbsp;</th>
            </tr>
            </thead>
            <tbody class="table-costs"></tbody>
        </table>
    </div>
</form>

<script>

</script>

<?php
    exit();
?>

