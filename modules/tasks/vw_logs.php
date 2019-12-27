<?php /* TASKS $Id: vw_logs.php 6221 2013-05-04 02:51:53Z cyberhorse $ */
if (!defined('DP_BASE_DIR')) {
  die('You should not access this file directly.');
}

global $AppUI, $m;
$df = $AppUI->getPref('SHDATEFORMAT');
$task_id = intval(dPgetParam($_GET, 'task_id', 0));

if (!(getPermission('task_log', 'access'))) {
	$AppUI->redirect('m=public&a=access_denied');
}

$problem = intval(dPgetParam($_GET, 'problem', null));
// get sysvals
$taskLogReference = dPgetSysVal('TaskLogReference');
$taskLogReferenceImage = dPgetSysVal('TaskLogReferenceImage');

$canView = getPermission('task_log', 'view');
$canEdit = getPermission('task_log', 'edit');
$canDelete = getPermission('task_log', 'delete');

?>
<hr>
<h6>Logs</h6>
<?php
// Pull the task comments
$q = new DBQuery;
$q->addTable('task_log', 'tl');
$q->addQuery('tl.*, u.user_username, bc.billingcode_name as task_log_costcode');
$q->leftJoin('billingcode','bc','bc.billingcode_id = tl.task_log_costcode');
$q->leftJoin('users', 'u', 'u.user_id = tl.task_log_creator');
$q->addWhere('task_log_task=' . $task_id . (($problem) ? ' AND task_log_problem > 0' : ''));
$q->addOrder('tl.task_log_date');
 
$logs = (($canView) ? $q->loadList() : array());

$s = '';
$hrs = 0;
    foreach ($logs as $row) {
        $task_log_date = intval($row['task_log_date']) ? new CDate($row['task_log_date']) : null;
        $style = $row['task_log_problem'] ? 'background-color:#cc6666;color:#ffffff' : '';

        $reference_image = '-';
        if ($row['task_log_reference'] > 0) {
            if (isset($taskLogReferenceImage[$row['task_log_reference']])) {
                $reference_image = dPshowImage($taskLogReferenceImage[$row['task_log_reference']], 16,
                    16, $taskLogReference[$row['task_log_reference']],
                    $taskLogReference[$row['task_log_reference']]);
            } else if (isset($taskLogReference[$row['task_log_reference']])) {
                $reference_image = $taskLogReference[$row['task_log_reference']];
            }
        }

        $link = '-';
        if (!empty($row['task_log_related_url'])) {
            $link = '<a target="_blank" href="http://' . @$row['task_log_related_url'] . '" title="' . @$row['task_log_related_url'] . '">' . $AppUI->_('URL') . '</a>';
        }

        $hours = '-';
        $minutes = (int)(($row['task_log_hours'] - ((int)$row['task_log_hours'])) * 60);
        $minutes = ((mb_strlen($minutes) == 1) ? ('0' . $minutes) : $minutes);

        $comments = '-';
        $transbrk = "\n[translation]\n";
        $descrip = $row['task_log_description'];
        $tranpos = mb_strpos($descrip, $transbrk);
        if ($tranpos === false) {
            $comments = $AppUI->___($descrip);
        } else {
            $descrip = mb_substr($descrip, 0, $tranpos);
            $tranpos = mb_strpos($row['task_log_description'], $transbrk);
            $transla = mb_substr($row['task_log_description'], $tranpos + mb_strlen($transbrk));
            $transla = trim(str_replace("'", '"', $transla));
            $comments = $AppUI->___($descrip) . '<div style="font-weight: bold; text-align: right"><a title="' . $AppUI->___($transla)
                . '" class="hilite">["' . $AppUI->_('translation') . '"]</a></div>';
        }
        ?>
        <table class="table table-sm table-bordered text-center" id="table_log_<?=$row['task_log_id']?>">
            <!-- Line one -->
            <thead class="thead-dark">
            <tr>
                <th width="10%"><?php echo $AppUI->_('Date'); ?></th>
                <th width="10%"><?php echo $AppUI->_('Ref'); ?></th>
                <th width="30%"><?php echo $AppUI->_('Summary'); ?></th>
                <th width="25%"><?php echo $AppUI->_('URL'); ?></th>
                <th width="15%"><?php echo $AppUI->_('User'); ?></th>
                <th width="10%"><?php echo $AppUI->_('Hours'); ?></th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td><?= (($task_log_date) ? $task_log_date->format($df) : '-') ?></td>
                <td><?= $reference_image ?></td>
                <td style="<?= $style ?>"><?= $AppUI->___(@$row['task_log_name']) ?></td>
                <td><?= $link ?></td>
                <td><?= $AppUI->___($row['user_username']) ?></td>
                <td><?= sprintf('%.2f', $row['task_log_hours']) . ' (' . $row['task_log_hours'] . ':' . $minutes . ')' ?></td>
            </tr>
            </tbody>

            <!-- Line two -->
            <thead class="thead-dark">
            <tr>
                <th width="10%"><?php echo $AppUI->_('Cost Code'); ?></th>
                <th width="80%" colspan="4"><?php echo $AppUI->_('Comments'); ?></th>
                <th width="10%">Ações</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td><?= $AppUI->___($row['task_log_costcode']) ?></td>
                <td colspan="4"><?= $comments ?></td>
                <td class="text-center">
                    <button type="button" class="btn btn-sm btn-danger text-center" title="Remover log" onclick="tasks.deleteLog(<?=$row['task_log_id'] ?>)">
                        <i class="far fa-trash-alt"></i>
                    </button>
                    <button type="button" class="btn btn-sm btn-secondary text-center" title="Alterar log" onclick="tasks.editLog(<?=$task_id?>, <?=$row['task_log_id']?>)">
                        <i class="far fa-edit"></i>
                    </button>
                </td>
            </tr>
            </tbody>
        </table>


<?php
    }
    if (count($logs) === 0) {
        echo '<h6>Nenhum log cadastrado</h6>';
    } else {
?>
        <table style="margin-bottom: 20px;">
            <tr>
                <td>Legenda</td>
                <td>&nbsp; &nbsp;</td>
                <td bgcolor="#ffffff" style="border: 1px solid #cecece; width: 20px;"></td>
                <td> <?php echo $AppUI->_('Normal Log'); ?>&nbsp;&nbsp;&nbsp;</td>
                <td bgcolor="#CC6666" style="border: 1px solid #cecece; width: 20px;"></td>
                <td> <?php echo $AppUI->_('Problem Report'); ?></td>
            </tr>
        </table>
<?php
    }
    exit();
?>