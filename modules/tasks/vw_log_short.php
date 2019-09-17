<?php /* TASKS $Id: vw_logs.php 6221 2013-05-04 02:51:53Z cyberhorse $ */
if (!defined('DP_BASE_DIR')) {
  die('You should not access this file directly.');
}

if (!(getPermission('task_log', 'access'))) {
	$AppUI->redirect('m=public&a=access_denied');
}
global $AppUI, $m;
$df = $AppUI->getPref('SHDATEFORMAT');
$task_id = intval(dPgetParam($_GET, 'task_id', 0));

// Pull the task comments
$q = new DBQuery;
$q->addTable('task_log', 'tl');
$q->addQuery('tl.task_log_id, tl.task_log_date, tl.task_log_description, u.user_username');
$q->leftJoin('users', 'u', 'u.user_id = tl.task_log_creator');
$q->addWhere('task_log_task=' . $task_id);
$q->addOrder('tl.task_log_date desc');
$logs = $q->loadList();
if (count($logs) > 0) {
    ?>
        <hr>
    <?php
}
?>
<table class="table table-sm table-bordered text-center" id="table_log_<?=$row['task_log_id']?>">
    <!-- Line one -->
    <thead class="thead-dark">
        <tr>
            <th width="17%"><?php echo $AppUI->_('Date'); ?></th>
            <th width="50%"><?php echo $AppUI->_('Description'); ?></th>
            <th width="25%">Responsável</th>
            <th width="8%"></th>
        </tr>
    </thead>
    <tbody>
<?php
    foreach ($logs as $row) {
        $task_log_date = new CDate($row['task_log_date']);
        $descrip = $row['task_log_description'];
        ?>
        
                <tr>
                    <td><?=$task_log_date->format($df)?></td>
                    <td><?=$descrip?></td>
                    <td><?= $AppUI->___($row['user_username']) ?></td>
                    <td>botão</td>
                </tr>
                <?php
    }
        ?>
        </tbody>
    </table>
    <?php
exit();
?>