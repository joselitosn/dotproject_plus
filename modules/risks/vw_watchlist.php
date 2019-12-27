<?php
$q = new DBQuery();
$q->clear();
$q->addQuery('user_id');
$q->addQuery('CONCAT( contact_first_name, \' \', contact_last_name)');
$q->addTable('users');
$q->leftJoin('contacts', 'c', 'user_contact = contact_id');
$q->addOrder('contact_first_name, contact_last_name');
$users = $q->loadHashList();

$q->clear();
$q->addQuery('project_id, project_name');
$q->addTable('projects');
$q->addOrder('project_name');
$projects = $q->loadHashList();

$q->clear();
$q->addQuery('task_id, task_name');
$q->addTable('tasks');
$q->addOrder('task_name');
$tasks = $q->loadHashList();

$riskProbability = dPgetSysVal('RiskProbability');
foreach ($riskProbability as $key => $value) {
    $riskProbability[$key] = str_replace("&amp;eacute;", "é", htmlspecialchars($AppUI->_($value)));
}
$riskStatus = dPgetSysVal('RiskStatus');
foreach ($riskStatus as $key => $value) {
    $riskStatus[$key] = str_replace("&amp;atilde;", "ã", htmlspecialchars($AppUI->_($value)));
}
$riskImpact = dPgetSysVal('RiskImpact');
foreach ($riskImpact as $key => $value) {
    $riskImpact[$key] = str_replace("&amp;eacute;", "é", htmlspecialchars($AppUI->_($value)));
}
$riskPotential = dPgetSysVal('RiskPotential');
foreach ($riskPotential as $key => $value) {
    $riskPotential[$key] = str_replace("&amp;atilde;", "ã", htmlspecialchars($AppUI->_($value)));
}
$riskPriority = dPgetSysVal('RiskPriority');
foreach ($riskPriority as $key => $value) {
    $riskPriority[$key] = str_replace("&amp;eacute;", "é", htmlspecialchars($AppUI->_($value)));
}
$riskStrategy = dPgetSysVal('RiskStrategy');
foreach ($riskStrategy as $key => $value) {
    $riskStrategy[$key] = $AppUI->_($value);
}

$projectSelected = intval(dPgetParam($_GET, 'project_id'));
$whereProject = ' and risk_project=' . $projectSelected;
$q->clear();

$bgYellow = "FFFF66";
$bgGreen = "33CC66";

$q->clear();
$q->addQuery('*');
$q->addTable('risks');
$q->addWhere("risk_active = '0' and (risk_priority = 0 or risk_priority = 1) $whereProject");
$activeList = $q->loadList();

$q->clear();
$q->addQuery('*');
$q->addTable('risks');
$q->addWhere("risk_active = '1' and (risk_priority = 0 or risk_priority = 1) $whereProject");
$inactiveList = $q->loadList();
?>

<div class="card inner-card">
    <div class="card-header"><?=$AppUI->_("LBL_ACTIVE_RISKS")?></div>
    <div class="card-body">

        <div class="row">
            <div class="col-md-12">

                <table class="table table-sm table-bordered">
                    <thead class="thead-dark">
                        <tr>
                            <th width="20%"><?php echo $AppUI->_('LBL_RISK_NAME'); ?></th>
                            <th width="32%"><?php echo $AppUI->_('LBL_DESCRIPTION'); ?></th>
                            <th width="10%"><?php echo $AppUI->_('LBL_PROBABILITY'); ?></th>
                            <th width="8%"><?php echo $AppUI->_('LBL_IMPACT'); ?></th>
                            <th width="14%"><?php echo $AppUI->_('LBL_PRIORITY'); ?></th>
                            <th width="8%"><?php echo $AppUI->_('LBL_STATUS'); ?></th>
                            <th width="8%"></th>
                        </tr>
                    </thead>
                    <?php foreach ($activeList as $row) {
                        $bgColor;
                        if ($row['risk_priority'] == 0) {
                            $bgColor = $bgGreen;
                        } else if ($row['risk_priority'] == 1) {
                            $bgColor = $bgYellow;
                        }
                        ?>
                        <tbody>
                        <tr>

                            <td><?php echo $row['risk_name'] ?></td>
                            <td><?php echo $row['risk_description'] ?></td>
                            <td><?php echo $riskProbability[$row['risk_probability']] ?></td>
                            <td><?php echo $riskImpact[$row['risk_impact']] ?></td>
                            <td style="background-color:<?='#'.$bgColor?>"><?php echo $riskPriority[$row['risk_priority']] ?></td>
                            <td><?php echo $riskStatus[$row['risk_status']] ?></td>
                            <td>
                                <button type="button" class="btn btn-xs btn-secondary" onclick="risks.switchEditWatchList(<?=$row['risk_id']?>, <?=$projectSelected?>)">
                                    <i class="far fa-edit"></i>
                                </button>
                                <button type="button" class="btn btn-xs btn-danger" onclick="risks.delete(<?=$row['risk_id']?>)">
                                    <i class="far fa-trash-alt"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                    <?php } ?>
                </table>
            </div>
        </div>
    </div>
</div>

</br>

<div class="card inner-card">
    <div class="card-header"><?=$AppUI->_("LBL_INACTIVE_RISKS")?></div>
    <div class="card-body">

        <div class="row">
            <div class="col-md-12">

                <table class="table table-sm table-bordered">
                    <thead class="thead-dark">
                        <tr>
                            <th width="20%"><?php echo $AppUI->_('LBL_RISK_NAME'); ?></th>
                            <th width="31%"><?php echo $AppUI->_('LBL_DESCRIPTION'); ?></th>
                            <th width="10%"><?php echo $AppUI->_('LBL_PROBABILITY'); ?></th>
                            <th width="8%"><?php echo $AppUI->_('LBL_IMPACT'); ?></th>
                            <th width="14%"><?php echo $AppUI->_('LBL_PRIORITY'); ?></th>
                            <th width="8%"><?php echo $AppUI->_('LBL_STATUS'); ?></th>
                            <th width="8%"></th>
                        </tr>
                    </thead>
                    <?php foreach ($inactiveList as $row) {
                        $bgColor;
                        if ($row['risk_priority'] == 0) {
                            $bgColor = $bgGreen;
                        } else if ($row['risk_priority'] == 1) {
                            $bgColor = $bgYellow;
                        }
                        ?>
                        <tr>
                            <td><?php echo $row['risk_name'] ?></td>
                            <td><?php echo $row['risk_description'] ?></td>
                            <td><?php echo $riskProbability[$row['risk_probability']] ?></td>
                            <td><?php echo $riskImpact[$row['risk_impact']] ?></td>
                            <td style="background-color:<?='#'.$bgColor?>"><?php echo $riskPriority[$row['risk_priority']] ?></td>
                            <td><?php echo $riskStatus[$row['risk_status']] ?></td>
                            <td>
                                <button type="button" class="btn btn-xs btn-secondary" onclick="risks.switchEditWatchList(<?=$row['risk_id']?>, <?=$projectSelected?>)">
                                    <i class="far fa-edit"></i>
                                </button>
                                <button type="button" class="btn btn-xs btn-danger" onclick="risks.delete(<?=$row['risk_id']?>)">
                                    <i class="far fa-trash-alt"></i>
                                </button>
                            </td>
                        </tr>
                    <?php } ?>
                </table>
            </div>
        </div>
    </div>
</div>

<?php
    exit();
?>