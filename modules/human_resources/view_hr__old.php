<?php
if (!defined('DP_BASE_DIR')) {
    die('You should not access this file directly.');
}

global $AppUI, $dPconfig, $locale_char_se;


$titleBlock = new CTitleBlock('View Human Resource Configurations', 'applet3-48.png', $m, "$m.$a");
$company_id = intval(dPgetParam($_GET, 'company_id', 0));
$query = new DBQuery;
$query->addTable('companies', 'c');
$query->addQuery('company_name');
$query->addWhere('c.company_id = ' . $company_id);
$res = & $query->exec();
//$titleBlock->addCrumb(('?m=companies&amp;a=view&amp;company_id=' . $company_id), $res->fields['company_name']);
$query->clear();

$user_id = intval(dPgetParam($_GET, 'user_id', 0));
$query = new DBQuery;
$query->addTable('human_resource', 'h');
$query->addQuery('human_resource_id');
$query->addWhere('h.human_resource_user_id = ' . $user_id);
$res = & $query->exec();
$human_resource_id = $res->fields['human_resource_id'];
$query->clear();

$obj = new CHumanResource();

$existsHumanResource = $obj->load($human_resource_id);
if ($existsHumanResource) {
    $AppUI->savePlace();
}
$contact_id = intval(dPgetParam($_GET, 'contact_id', 0));
$query = new DBQuery();
$query->addTable('contacts', 'c');
$query->addQuery('contact_last_name, contact_first_name');
$query->addWhere('c.contact_id = ' . $contact_id);
$res = $query->exec();
$contact_name = $res->fields['contact_first_name'] . " ". $res->fields['contact_last_name'];
//$titleBlock->addCrumb('?m=human_resources&amp;a=view_company_users&amp;company_id=' . $company_id, 'users list');

if ($existsHumanResource) {
    $canDelete = $obj->canDelete();
    if ($canDelete) {
        $titleBlock->addCrumbDelete('delete human resource', true, 'no delete permission');
    } else {
        $titleBlock->addCell('<p style="color: green;">' . $AppUI->_("LBL_RH_CANT_BE_DELETED") . '</p>');
    }
} else {
   // $titleBlock->addCrumb("?m=human_resources&amp;a=addedit_hr&amp;human_resource_id=$human_resource_id&amp;contact_id=$contact_id&amp;company_id=$company_id&amp;user_id=$user_id", $AppUI->_("configure human resource"));
}

$titleBlock->show();

$concat_roles_names = "";
if ($existsHumanResource) {
    $query = new DBQuery;
    $query->addTable('human_resource_roles', 'r');
    $query->addQuery('h.human_resources_role_name, h.human_resources_role_id');
    $query->innerJoin('human_resources_role', 'h', 'h.human_resources_role_id = r.human_resources_role_id');
    $query->addWhere('r.human_resource_id = ' . $human_resource_id);
    $sql = $query->prepare();
    $roles = db_loadList($sql);
    $roles_array = array();
    foreach ($roles as $role) {
        array_unshift($roles_array, $role['human_resources_role_name']);
    }
    $concat_roles_names = implode(', ', $roles_array);
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
$query->clear();
$query->addTable('users', 'u');
$query->addQuery('user_username');
$query->addWhere('u.user_id = ' . $user_id);
$res = $query->exec();

?>

<table border="0" cellpadding="4" cellspacing="0" width="100%" class="std" summary="human_resources">
    <tr>
        <td colspan="2"> <strong><?php echo $AppUI->_('Details'); ?></strong> </td>
    </tr>
    <tr>
        <td align="right" nowrap="nowrap"><?php echo $AppUI->_('User username'); ?>:</td>
        <td class="hilite" width="100%"><?php echo $contact_name ?>    (<a href="index.php?m=contacts&a=addedit&contact_id=<?php echo $contact_id ?>" target="_blank">Acessar contato</a>)</td>
    </tr>
    <tr>
        <td align="right" nowrap="nowrap"><?php echo $AppUI->_('Roles'); ?>:</td>
        <td class="hilite" width="100%"><?php echo $concat_roles_names; ?></td>
    </tr>
    <tr>
        <td align="right" nowrap="nowrap"><?php echo $AppUI->_('Lattes URL'); ?>:</td>
        <td width="2%" align="left" >
            <a href="<?php echo $obj->human_resource_lattes_url; ?>"><?php echo $obj->human_resource_lattes_url; ?></a>
        </td>
    </tr>
    ;l';,;l,;lm;
    <tr>
        <td align="right" nowrap="nowrap"><?php echo $AppUI->_('Weekday working hours'); ?>:</td>
    </tr>
    <tr>
        <td align="right" nowrap="nowrap"><?php echo $cwd_conv[0]; ?>:</td>
        <td class="hilite" width="100%"><?php echo $obj->human_resource_mon; ?></td>
    </tr>
    <tr>
        <td align="right" nowrap="nowrap"><?php echo $cwd_conv[1]; ?>:</td>
        <td class="hilite" width="100%"><?php echo $obj->human_resource_tue; ?></td>
    </tr>
    <tr>
        <td align="right" nowrap="nowrap"><?php echo $cwd_conv[2]; ?>:</td>
        <td class="hilite" width="100%"><?php echo $obj->human_resource_wed; ?></td>
    </tr>
    <tr>
        <td align="right" nowrap="nowrap"><?php echo $cwd_conv[3]; ?>:</td>
        <td class="hilite" width="100%"><?php echo $obj->human_resource_thu; ?></td>
    </tr>
    <tr>
        <td align="right" nowrap="nowrap"><?php echo $cwd_conv[4]; ?>:</td>
        <td class="hilite" width="100%"><?php echo $obj->human_resource_fri; ?></td>
    </tr>
    <tr>
        <td align="right" nowrap="nowrap"><?php echo $cwd_conv[5]; ?>:</td>
        <td class="hilite" width="100%"><?php echo $obj->human_resource_sat; ?></td>
    </tr>
    <tr>
        <td align="right" nowrap="nowrap"><?php echo $cwd_conv[6]; ?>:</td>
        <td class="hilite" width="100%"><?php echo $obj->human_resource_sun; ?></td>
    </tr>
    <tr>
        <td colspan="2" align="right">
            <a href="<?php echo "index.php?m=human_resources&a=addedit_hr&human_resource_id=$human_resource_id&contact_id=$contact_id&company_id=$company_id&user_id=$user_id" ?>">
                <input type="submit" value="<?php echo $AppUI->_("Edit"); ?>" />
            </a>
                <script>goCompanyHome=true;</script>
                <?php require_once (DP_BASE_DIR . "/modules/timeplanning/view/subform_back_button_company.php"); ?>         
        </td>
    </tr>
</table>
<?php
$query->clear();
?>
<br />
<table width="100%" class="std">    <tr>
        <th align="center">
            <?php echo $AppUI->_("LBL_USER_COSTS"); ?>
        </th>
    </tr>
    <tr>
        <td>
            <?php require_once DP_BASE_DIR . "/modules/monitoringandcontrol/admin_tab.viewuser.5LBLCUSTO.php" ?>
        </td>
    </tr>
</table>