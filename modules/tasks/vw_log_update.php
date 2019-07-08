<?php /* TASKS $Id: vw_log_update.php 6221 2013-05-04 02:51:53Z cyberhorse $ */
if (!defined('DP_BASE_DIR')) {
  die('You should not access this file directly.');
}

global $AppUI, $percent;

$task_id = $_GET['task_id'];

// check permissions
if (!getPermission('tasks', 'edit', $task_id)) {
	$AppUI->redirect('m=public&a=access_denied');
}

$canEdit = getPermission('task_log', 'edit', $task_id);
$canAdd = getPermission('task_log', 'add', $task_id);

$task_log_id = intval(dPgetParam($_GET, 'task_log_id', 0));
$log = new CTaskLog();
if ($task_log_id) {
	if (!($canEdit)) {
		$AppUI->redirect('m=public&a=access_denied');
	}
	$log->load($task_log_id);
} else {
	if (!($canAdd)) {
		$AppUI->redirect('m=public&a=access_denied');
	}
	$log->task_log_task = $task_id;
	$log->task_log_name = $obj->task_name;
}

$obj = new CTask();
$obj->peek($task_id); // No idea what id does!

$q = new DBQuery;

$q->addTable('tasks', "tsk");
$q->leftJoin('users', 'u1', 'u1.user_id = task_owner');
$q->leftJoin('projects', 'p', 'p.project_id = task_project');
$q->leftJoin('task_log', 'tl', 'tl.task_log_task = task_id');
$q->addWhere('task_id = ' . $task_id);
$q->addQuery('tsk.*');
$q->addQuery('project_name, project_color_identifier');
$q->addQuery('u1.user_username as username');
$q->addQuery('ROUND(SUM(task_log_hours),2) as log_hours_worked');
$q->addGroup('task_id');
$sql = $q->prepare();
$q->clear();

db_loadObject($sql, $obj, true, false);

// Check that the user is at least assigned to a task
$task = new CTask;
$task->load($task_id);
if (!($task->canAccess($AppUI->user_id))) {
	$AppUI->redirect('m=public&a=access_denied');
}

$q = new DBQuery;
$q->addTable('tasks', 't');
$q->innerJoin('projects', 'p', 'p.project_id = t.task_project');
$q->innerJoin('billingcode', 'b', 'b.company_id = p.project_company OR b.company_id = 0');
$q->addQuery('b.billingcode_id, b.billingcode_name');
$q->addWhere('b.billingcode_status = 0');
$q->addWhere('t.task_id = ' . $task_id);
$q->addOrder('b.billingcode_name');
$task_log_costcodes = $q->loadHashList();
$task_log_costcodes[0] = '';
$q->clear();

$taskLogReference = dPgetSysVal('TaskLogReference');

// Task Update Form
$df = $AppUI->getPref('SHDATEFORMAT');
$log_date = new CDate($log->task_log_date);

//task log e-mail checkboxes
$tl = $AppUI->getPref('TASKLOGEMAIL');
$ta = $tl & 1;
$tt = $tl & 2;
$tp = $tl & 4;

//notify owner checkbox 
$notify_own = $AppUI->getPref('MAILALL'); 

//task log e-mail list
$task_email_title = array();
$q->addTable('task_contacts', 'tc');
$q->leftJoin('contacts', 'c', 'c.contact_id = tc.contact_id');
$q->addWhere('tc.task_id = ' . $task_id);
$q->addQuery('tc.contact_id');
$q->addQuery('c.contact_first_name, c.contact_last_name');
$req =& $q->exec();
$cid = array();
for ($req; !($req->EOF); $req->MoveNext()) {
	$cid[] = $req->fields['contact_id'];
	$task_email_title[] = ($req->fields['contact_first_name'] . ' ' 
	                       . $req->fields['contact_last_name']);
}
$q->clear();

//project contacts
$q->addTable('project_contacts', 'pc');
$q->leftJoin('contacts', 'c', 'c.contact_id = pc.contact_id');
$q->addWhere('pc.project_id = ' . $obj->task_project);
$q->addQuery('pc.contact_id');
$q->addQuery('c.contact_first_name, c.contact_last_name');
$req =& $q->exec();
$cid = array();
$proj_email_title = array();
for ($req; !($req->EOF); $req->MoveNext()) {
	if (! in_array($req->fields['contact_id'], $cid)) {
		$cid[] = $req->fields['contact_id'];
		$proj_email_title[] = ($req->fields['contact_first_name'] . ' ' 
		                       . $req->fields['contact_last_name']);
	}
}

?>

<!-- TIMER RELATED SCRIPTS -->
<script type="text/javascript" language="javascript">
	// please keep these lines on when you copy the source
	// made by: Nicolas - http://www.javascript-page.com
	// adapted by: Juan Carlos Gonzalez jcgonz@users.sourceforge.net
	
	var timerID = 0;
	var tStart = null;
	var total_minutes = -1;
	
	function UpdateTimer() {
		if (timerID) {
			clearTimeout(timerID);
			clockID  = 0;
		}
		
		// One minute has passed
		total_minutes = total_minutes+1;
		
		document.getElementById("timerStatus").innerHTML = "("+total_minutes+" <?php 
echo $AppUI->_('minutes elapsed'); ?>)";

		// Lets round hours to two decimals
		var total_hours   = Math.round((total_minutes / 60) * 100) / 100;
		document.editFrm.task_log_hours.value = total_hours;
		
		timerID = setTimeout("UpdateTimer()", 60000);
	}
	
	function timerStart() {
		if (!timerID) { // this means that it needs to be started
			timerSet();
			document.editFrm.timerStartStopButton.value = "<?php echo $AppUI->_('Stop'); ?>";
			UpdateTimer();
		} else { // timer must be stoped
			document.editFrm.timerStartStopButton.value = "<?php echo $AppUI->_('Start'); ?>";
			document.getElementById("timerStatus").innerHTML = "";
			timerStop();
		}
	}
	
	function timerStop() {
		if (timerID) {
			clearTimeout(timerID);
			timerID  = 0;
			total_minutes = total_minutes-1;
		}
	}
	
	function timerReset() {
		document.editFrm.task_log_hours.value = "0.00";
		total_minutes = -1;
	}
	
	function timerSet() {
		total_minutes = Math.round(document.editFrm.task_log_hours.value * 60) -1;
	}
	<?php
if ($obj->canUserEditTimeInformation()) {
?>
	function popCalendar(field) {
		calendarField = field;
		idate = eval('document.editFrm.task_' + field + '.value');
		window.open('index.php?m=public&'+'a=calendar&'+'dialog=1&'+'callback=setCalendar&'+'date='
					+ idate, 'calwin', 'width=251, height=220, scrollbars=no, status=no');
	}
<?php
}
?>
</script>
<!-- END OF TIMER RELATED SCRIPTS -->

<a name="log"></a>

<form name="editFrm" action="?m=tasks&amp;a=view&amp;task_id=<?php echo $task_id; ?>" method="post"
  onsubmit='javascript:updateEmailContacts();'>
  <input type="hidden" name="uniqueid" value="<?php echo uniqid(""); ?>" />
  <input type="hidden" name="dosql" value="do_updatetask" />
  <input type="hidden" name="task_log_id" value="<?php echo $log->task_log_id; ?>" />
  <input type="hidden" name="task_log_task" value="<?php echo $log->task_log_task; ?>" />
  <input type="hidden" name="task_log_creator" value="<?php 
echo(($log->task_log_creator == 0) ? $AppUI->user_id : $log->task_log_creator) ?>" />
  <input type="hidden" name="task_log_name" value="Update :<?php echo $AppUI->___($log->task_log_name); ?>" />

    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label><?php echo $AppUI->_('Date'); ?></label>
                <input type="hidden" name="task_log_date" value="<?=$log_date->format(FMT_DATETIME_MYSQL)?>" />
                <input type="text" name="log_date" value="<?=$log_date->format($df)?>" class="form-control form-control-sm datepicker" />
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label><?php echo $AppUI->_('Progress'); ?></label>
                <div class="input-group mb-3">
                    <select class="form-control form-control-sm select-progress" name="task_percent_complete">
                        <option></option>
                        <?php
                        foreach ($percent as $key => $value) {
                            $selected = $obj->task_percent_complete == $key ? 'selected="selected"' : '';
                            ?>
                            <option value="<?=$key?>" <?=$selected?>>
                                <?=$value?>
                            </option>
                            <?php
                        }
                        ?>
                    </select>
                    <div class="input-group-append">
                        <label class="input-group-text form-control-sm">%</label>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <?php
                if ($obj->task_owner != $AppUI->user_id) {
                    ?>
                    <div class="form-group">
                        <label>&nbsp;</label>
                        <br>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input"
                                type="checkbox"
                                name="task_log_notify_owner"
                                id="task_log_notify_owner"
                                <?=(($notify_own) ? ' checked="checked"' : '')?> />
                            <label class="form-check-label">Notificar responsável</label>
                        </div>
                    </div>
                    <?php
                }
            ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-5">
            <label><?php echo $AppUI->_('Hours Worked'); ?></label>
            <div class="row">
                <div class="col-md-5">
                    <div class="form-group">
                        <input type="text"
                               class="form-control form-control-sm"
                               name="task_log_hours"
                               value="<?=$log->task_log_hours?>"
                               maxlength="8"
                               size="6" />
                    </div>
                    <small><span id='timerStatus'></span></small>
                </div>
                <div class="col-md-7">
                    <div class="form-group">
                        <button type="button" class="btn btn-sm btn-secondary" onclick="javascript:timerStart()" name="timerStartStopButton"><?=$AppUI->_('Start')?></button>
                        <button type="button" class="btn btn-sm btn-danger" onclick="javascript:timerReset()" name="timerResetButton"><?=$AppUI->_('Reset')?></button>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-7">
            <div class="form-group">
                <label>Código de custo</label>
                <div class="row">
                    <div class="col-md-5">
                        <select class="form-control form-control-sm select-cost-code"
                                name="task_log_costcodes"
                                onchange="javascript:task_log_costcode.value = this.options[this.selectedIndex].value;">
                            <option></option>
                            <?php
                            foreach ($task_log_costcodes as $key => $value) {
                                $selected = $log->task_log_costcode == $key ? 'selected="selected"' : '';
                                ?>
                                <option value="<?=$key?>" <?=$selected?>>
                                    <?=$value?>
                                </option>
                                <?php
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-md-2 text-center">
                        <i class="fas fa-arrow-right" style="margin-top:8px;"></i>
                    </div>
                    <div class="col-md-5">
                        <input type="text"
                               class="form-control form-control-sm"
                               name="task_log_costcode"
                               value="<?=$log->task_log_costcode?>"
                               maxlength="8"
                               size="8" />
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php
        if ($obj->canUserEditTimeInformation()) {
            $end_date = ((intval($obj->task_end_date)) ? new CDate($obj->task_end_date) : new CDate());
            ?>
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Data de fim</label>
                        <input type="hidden" name="task_end_date" value="<?=$end_date->format(FMT_DATETIME_MYSQL)?>" />
                        <input type="text" name="end_date" value="<?=$end_date->format($df)?>" class="form-control form-control-sm datepicker" />
                    </div>
                </div>
            </div>
            <?php
        }
    ?>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label><?=$AppUI->_('Summary')?></label>
                <input type="text" class="form-control form-control-sm" name="task_log_name" value="<?=$AppUI->___($log->task_log_name)?>" maxlength="255" size="30" />
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label>&nbsp;</label>
                <br>
                <div class="form-check form-check-inline">
                    <input class="form-check-input"
                           type="checkbox"
                           id="task_log_problem"
                           value="1"
                           name="task_log_problem"
                        <?=(($log->task_log_problem) ? 'checked="checked"' : '')?> />
                    <label class="form-check-label">Problema</label>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label><?php echo $AppUI->_('Reference'); ?></label>
                <select class="form-control form-control-sm select-reference" name="task_log_reference">
                    <option></option>
                    <?php
                    foreach ($taskLogReference as $key => $value) {
                        $selected = $log->task_log_reference == $key ? 'selected="selected"' : '';
                        ?>
                        <option value="<?=$key?>" <?=$selected?>>
                            <?=$value?>
                        </option>
                        <?php
                    }
                    ?>
                </select>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label><?php echo $AppUI->_('URL'); ?></label>
                <input type="text" class="form-control form-control-sm" name="task_log_related_url" value="<?php
                echo ($log->task_log_related_url); ?>" size="50" maxlength="255" title="<?php
                echo $AppUI->_('Must in general be entered with protocol name, e.g. http://...'); ?>" />
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <label><?=$AppUI->_('Description')?></label>
                <textarea name="task_log_description" class="form-control form-control-sm" rows="3"><?=$log->task_log_description?></textarea>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <label>Enviar email para</label>
                <br>
                <div class="form-check form-check-inline">
                    <input type="checkbox" name="email_assignees" id="email_assignees"<?php
                    echo (($ta) ? ' checked="checked"' : ''); ?> />
                    <label class="form-check-label">&nbsp;&nbsp;<?php echo $AppUI->_('Task Assignees'); ?></label>
                </div>

                <div class="form-check form-check-inline">
                    <input type="hidden" name="email_task_list" id="email_task_list" value="<?php
                    echo implode(',', $cid); ?>" />
                    <input type="checkbox" onmouseover="javascript:window.status = '<?php
                    echo addslashes(implode(',',$task_email_title));
                    ?>';" onmouseout="javascript:window.status = '';" name="email_task_contacts" id="email_task_contacts"<?php
                    echo (($tt) ? ' checked="checked"' : ''); ?> />
                    <label class="form-check-label">&nbsp;&nbsp;<?php echo $AppUI->_('Task Contacts'); ?></label>
                </div>

                <div class="form-check form-check-inline">
                    <input type="hidden" name="email_project_list" id="email_project_list" value="<?php
                    echo implode(',', $cid); ?>" />
                    <input type="checkbox" onmouseover="javascript:window.status = '<?php
                    echo addslashes(implode(',', $proj_email_title));
                    ?>';" onmouseout="javascript:window.status = '';" name="email_project_contacts" id="email_project_contacts" <?php
                    echo (($tp) ? ' checked="checked"' : ''); ?> />
                    <label class="form-check-label">&nbsp;&nbsp;<?php echo $AppUI->_('Project Contacts'); ?></label>
                </div>

                <input type='hidden' name='email_others' id='email_others' value='' />
                <?php
                if ($AppUI->isActiveModule('contacts') && getPermission('contacts', 'view')) {
                    ?>
                    <button type="button" class="btn btn-sm btn-secondary" onclick="javascript:popEmailContacts()"><?=$AppUI->_('Other Contacts...')?></button>
                    <?php
                }
                ?>

            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <label><?php echo $AppUI->_('Extra Recipients'); ?></label>
                <input type="text" class="form-control form-control-sm" name="email_extras" maxlength="255" size="30" />
            </div>
        </div>
    </div>

<!--<table cellspacing="1" cellpadding="2" border="0" width="100%">-->
<!--<tr>-->
<!--  <td width='40%' valign='top' align='center'>-->
<!--    <table width='100%'>-->
<!--      <tr>-->
<!--        <td align="right">--><?php //echo $AppUI->_('Date'); ?><!--</td>-->
<!--        <td nowrap="nowrap">-->
<!--          <input type="hidden" name="task_log_date" value="--><?php //
//echo $log_date->format(FMT_DATETIME_MYSQL); ?><!--" />-->
<!--          <input type="text" name="log_date" value="--><?php //
//echo $log_date->format($df); ?><!--" class="text" disabled="disabled" />-->
<!--		<a href="#" onclick="javascript:popCalendar('log_date')">-->
<!--          <img src="./images/calendar.gif" width="24" height="12" alt="--><?php //
//echo $AppUI->_('Calendar'); ?><!--" border="0" />-->
<!--          </a>-->
<!--        </td>-->
<!--      </tr>-->
<!--      <tr>-->
<!--        <td align="right">--><?php //echo $AppUI->_('Progress'); ?><!--</td>-->
<!--        <td>-->
<!--        <table>-->
<!--          <tr>-->
<!--            <td>-->
<!--              --><?php
//echo arraySelect($percent, 'task_percent_complete', 'size="1" class="text"',
//                 $obj->task_percent_complete) . '%';
//?>
<!--            </td>-->
<!--            <td valign="middle">--><?php
//if ($obj->task_owner != $AppUI->user_id) {
//?>
<!--              <input type="checkbox" name="task_log_notify_owner" id="task_log_notify_owner" --><?php //
//echo (($notify_own) ? ' checked="checked"' : ''); ?><!-- />-->
<!--            </td>-->
<!--            <td valign="middle">-->
<!--              <label for="task_log_notify_owner">--><?php //echo $AppUI->_('Notify owner'); ?><!--</label>-->
<?php //
//}
//?>
<!--            </td>-->
<!--          </tr>-->
<!--        </table>-->
<!--        </td>-->
<!--      </tr>-->
<!--      <tr>-->
<!--        <td align="right">--><?php //echo $AppUI->_('Hours Worked'); ?><!--</td>-->
<!--        <td>-->
<!--          <input type="text" class="text" name="task_log_hours" value="--><?php //
//echo $log->task_log_hours; ?><!--" maxlength="8" size="6" /> -->
<!--          <input type='button' class="button" value='--><?php //
//echo $AppUI->_('Start'); ?><!--' onclick='javascript:timerStart()' name='timerStartStopButton' />-->
<!--          <input type='button' class="button" value='--><?php //
//echo $AppUI->_('Reset'); ?><!--' onclick="javascript:timerReset()" name='timerResetButton' /> -->
<!--          <span id='timerStatus'></span>-->
<!--        </td>-->
<!--      </tr>-->
<!--      <tr>-->
<!--        <td align="right">--><?php //echo $AppUI->_('Cost Code'); ?><!--</td>-->
<!--        <td>-->
<!--          --><?php
//echo arraySelect($task_log_costcodes, 'task_log_costcodes',
//                 ('size="1" class="text" onchange="javascript:task_log_costcode.value'
//                  . ' = this.options[this.selectedIndex].value;"'), $log->task_log_costcode);
//?>
<!--           -&gt; <input type="text" class="text" name="task_log_costcode" value="--><?php //
//echo $log->task_log_costcode; ?><!--" maxlength="8" size="8" />-->
<!--        </td>-->
<!--      </tr>-->
<?php
//if ($obj->canUserEditTimeInformation()) {
//	$end_date = ((intval($obj->task_end_date)) ? new CDate($obj->task_end_date) : null);
//?>
<!--      <tr>-->
<!--        <td align='right'>--><?php //echo $AppUI->_("Task end date"); ?><!--</td>-->
<!--        <td>-->
<!--          <input type="hidden" name="task_end_date" value="--><?php //
//	echo (($end_date) ? $end_date->format(FMT_TIMESTAMP) : ''); ?><!--" />-->
<!--          <input type="text" name="end_date" value="--><?php //
//	echo (($end_date) ? $end_date->format($df) : ''); ?><!--" class="text" disabled="disabled" />-->
<!--			<a href="#" onclick="javascript:popCalendar('end_date')">-->
<!--          <img src="./images/calendar.gif" width="24" height="12" alt="--><?php //
//	echo $AppUI->_('Calendar'); ?><!--" border="0" />-->
<!--          </a>-->
<!--        </td>-->
<!--      </tr>-->
<?php
//}
//?>
<!--    </table>-->
<!--  </td>-->
<!--  <td width='60%' valign='top' align='center'>-->
<!--    <table width='100%'>-->
<!--      <tr>-->
<!--        <td align="right">--><?php //echo $AppUI->_('Summary'); ?><!--:</td>-->
<!--        <td valign="middle">-->
<!--          <table width="100%">-->
<!--            <tr>-->
<!--              <td align="left">-->
<!--                <input type="text" class="text" name="task_log_name" value="--><?php //
//echo $AppUI->___($log->task_log_name); ?><!--" maxlength="255" size="30" />-->
<!--              </td>-->
<!--              <td align="center">-->
<!--                <label for="task_log_problem">--><?php //echo $AppUI->_('Problem'); ?><!--:</label>-->
<!--                <input type="checkbox" value="1" name="task_log_problem" id="task_log_problem"--><?php //
//echo (($log->task_log_problem) ? 'checked="checked"' : ''); ?><!-- />-->
<!--              </td>-->
<!--            </tr>-->
<!--          </table>-->
<!--        </td>-->
<!--      </tr>-->
<!--      <tr>-->
<!--        <td align="right" valign="middle">--><?php //echo $AppUI->_('Reference'); ?><!--:</td>-->
<!--        <td valign="middle">-->
<!--          --><?php //
//echo arraySelect($taskLogReference, 'task_log_reference', 'size="1" class="text"',
//                 $log->task_log_reference, true);
//?>
<!--        </td>-->
<!--      </tr>-->
<!--      <tr>-->
<!--        <td align="right">--><?php //echo $AppUI->_('URL'); ?><!--:</td>-->
<!--        <td><input type="text" class="text" name="task_log_related_url" value="--><?php //
//echo ($log->task_log_related_url); ?><!--" size="50" maxlength="255" title="--><?php //
//echo $AppUI->_('Must in general be entered with protocol name, e.g. http://...'); ?><!--" /></td>-->
<!--      </tr>-->
<!--      <tr>-->
<!--        <td align="right" valign="top">--><?php //echo $AppUI->_('Description'); ?><!--:</td>-->
<!--        <td><textarea name="task_log_description" class="textarea" cols="50" rows="6">--><?php //
//echo $log->task_log_description; ?><!--</textarea></td>-->
<!--      </tr>-->
<!--      <tr>-->
<!--        <td align="right" valign="top">--><?php //echo $AppUI->_('Email Log to'); ?><!--:</td>-->
<!--        <td>-->
<!--          <input type="checkbox" name="email_assignees" id="email_assignees"--><?php //
//echo (($ta) ? ' checked="checked"' : ''); ?><!-- />-->
<!--          <label for="email_assignees">--><?php //echo $AppUI->_('Task Assignees'); ?><!--</label>-->
<!--          <input type="hidden" name="email_task_list" id="email_task_list" value="--><?php //
//echo implode(',', $cid); ?><!--" />-->
<!--          <input type="checkbox" onmouseover="javascript:window.status = '--><?php //
//echo addslashes(implode(',',$task_email_title));
//?><!--';" onmouseout="javascript:window.status = '';" name="email_task_contacts" id="email_task_contacts"--><?php
//echo (($tt) ? ' checked="checked"' : ''); ?><!--<!-- />-->
<!--          <label for="email_task_contacts">--><?php //echo $AppUI->_('Task Contacts'); ?><!--</label>-->
<!--          <input type="hidden" name="email_project_list" id="email_project_list" value="--><?php //
//echo implode(',', $cid); ?><!--" />-->
<!--          <input type="checkbox" onmouseover="javascript:window.status = '--><?php //
//echo addslashes(implode(',', $proj_email_title));
//?><!--//';" onmouseout="javascript:window.status = '';" name="email_project_contacts" id="email_project_contacts" --><?php //
//echo (($tp) ? ' checked="checked"' : ''); ?><!-- />-->
<!--          <label for="email_project_contacts">--><?php //echo $AppUI->_('Project Contacts'); ?><!--</label>-->
<!--          <input type='hidden' name='email_others' id='email_others' value='' />-->
<!--          --><?php
//if ($AppUI->isActiveModule('contacts') && getPermission('contacts', 'view')) {
//?>
<!--          <input type='button' class='button' value='--><?php //
//	echo $AppUI->_('Other Contacts...'); ?><!--' onclick='javascript:popEmailContacts();' />-->
<?php //
//}
//?>
<!--        </td>-->
<!--      </tr>-->
<!--      <tr>-->
<!--        <td align="right" valign="top">--><?php //echo $AppUI->_('Extra Recipients'); ?><!--:</td>-->
<!--        <td><input type="text" class="text" name="email_extras" maxlength="255" size="30" /></td>-->
<!--      </tr>-->
<!--      <tr>-->
<!--        <td colspan="2" valign="bottom" align="right">-->
<!--          <input type="button" class="button" value="--><?php //
//echo $AppUI->_('update task'); ?><!--" onclick="updateTask()" />-->
<!--        </td>-->
<!--      </tr>-->
<!--    </table>-->
<!--  </td>-->
<!--</tr>-->
<!--</table>-->
</form>

<script>

    var log = {
        init: function () {
            $('.datepicker').datepicker({
                dateFormat: 'dd/mm/yy'
            });

            $('.select-progress').select2({
                placeholder: '',
                width: '50%',
                allowClear: true,
                theme: "bootstrap",
                dropdownParent: $("#taskLogModal")
            });

            $('.select-cost-code').select2({
                placeholder: '',
                allowClear: true,
                theme: "bootstrap",
                dropdownParent: $("#taskLogModal")
            });

            $('.select-reference').select2({
                placeholder: '',
                allowClear: true,
                theme: "bootstrap",
                dropdownParent: $("#taskLogModal")
            });
        },
    };

    $(document).ready(log.init());



    function popEmailContacts() {

        updateEmailContacts();

        var email_others = document.getElementById('email_others');

        $.ajax({
            type: "get",
            url: "?m=public&template=contact_selector&selected_contacts_id="+ email_others.value
        }).done(function(response) {
            $(".modal-contacts-body").html(response);
            $("#contactsModal").modal();
        });
    }

    function setEmailContacts(contact_id_string) {
        if (! contact_id_string) {
            contact_id_string = "";
        }
        var email_others = document.getElementById('email_others');
        email_others.value = contact_id_string;

        $("#contactsModal").modal('hide');
    }

    function updateEmailContacts() {
        var email_others = document.getElementById('email_others');
        var task_emails = document.getElementById('email_task_list');
        var proj_emails = document.getElementById('email_project_list');
        var do_task_emails = document.getElementById('email_task_contacts');
        var do_proj_emails = document.getElementById('email_project_contacts');

        // Build array out of list of contact ids.
        var email_list = email_others.value.split(',');
        if (do_task_emails.checked) {
            var telist = task_emails.value.split(',');
            var full_list = email_list.concat(telist);
            email_list = full_list;
            do_task_emails.checked = false;
        }

        if (do_proj_emails.checked) {
            var prlist = proj_emails.value.split(',');
            var full_proj = email_list.concat(prlist);
            email_list = full_proj;
            do_proj_emails.checked = false;
        }

        // Now do a reduction
        email_list.sort();
        var output_array = new Array();
        var last_elem = -1;
        for (var i = 0; i < email_list.length; i++) {
            if (email_list[i] == last_elem) {
                continue;
            }
            last_elem = email_list[i];
            output_array.push(email_list[i]);
        }
        email_others.value = output_array.join();
    }

    function emailNumericCompare(a, b) {
        return a - b;
    }
</script>
<?php exit();?>
