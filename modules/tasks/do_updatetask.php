<?php /* TASKS $Id: do_updatetask.php 6149 2012-01-09 11:58:40Z ajdonnison $ */
if (!defined('DP_BASE_DIR')) {
  die('You should not access this file directly.');
}

/*
 * There is an issue with international UTF characters, when stored in the database an accented 
 * letter actually takes up two letters per say in the field length, this is a problem with 
 * costcodes sincE they are limited in size so saving a costcode as REDACI�N would actually 
 * save REDACI� since the accent takes two characters, so lets unaccent them, other languages 
 * should add to the replacements array too...
*/
function cleanText($text) {
	//This text file is not utf, its iso so we have to decode/encode      JESUS CHRIST
	$text = utf8_decode($text);
	$trade = array('�'=>'a','�'=>'a','�'=>'a',
                 '�'=>'a','�'=>'a',
                 '�'=>'A','�'=>'A','�'=>'A',
                 '�'=>'A','�'=>'A',
                 '�'=>'e','�'=>'e',
                 '�'=>'e','�'=>'e',
                 '�'=>'E','�'=>'E',
                 '�'=>'E','�'=>'E',
                 '�'=>'i','�'=>'i',
                 '�'=>'i','�'=>'i',
                 '�'=>'I','�'=>'I',
                 '�'=>'I','�'=>'I',
                 '�'=>'o','�'=>'o','�'=>'o',
                 '�'=>'o','�'=>'o',
                 '�'=>'O','�'=>'O','�'=>'O',
                 '�'=>'O','�'=>'O',
                 '�'=>'u','�'=>'u',
                 '�'=>'u','�'=>'u',
                 '�'=>'U','�'=>'U',
                 '�'=>'U','�'=>'U',
                 '�'=>'N','�'=>'n');
    $text = strtr($text,$trade);
	$text = utf8_encode($text);

	return $text;
}

$notify_owner = ((isset($_POST['task_log_notify_owner'])) ? $_POST['task_log_notify_owner'] : 0);

$del = (int)dPgetParam($_POST, 'del', 0);

$obj = new CTaskLog();

if (!($obj->bind($_POST))) {
	$AppUI->setMsg($obj->getError(), UI_MSG_ERROR);
}

if ($obj->task_log_date) {
	$date = new CDate($obj->task_log_date);
	$obj->task_log_date = $date->format(FMT_DATETIME_MYSQL);
}
$dot = mb_strpos($obj->task_log_hours, ':');
if ($dot > 0) {
	$log_duration_minutes = sprintf('%.3F', mb_substr($obj->task_log_hours, $dot + 1)/60.0);
	$obj->task_log_hours = sprintf('%.3F', floor($obj->task_log_hours) + $log_duration_minutes);
}
$obj->task_log_hours = sprintf('%.3F', round($obj->task_log_hours, 3));

// prepare (and translate) the module name ready for the suffix
$AppUI->setMsg('Task Log');
if ($del) {
	if (($msg = $obj->delete())) {
		$AppUI->setMsg($msg, UI_MSG_ERROR);
	} else {
		$AppUI->setMsg('deleted', UI_MSG_ALERT);
	}
} else {
	$obj->task_log_costcode = cleanText($obj->task_log_costcode);
	if (($msg = $obj->store())) {
		$AppUI->setMsg($msg, UI_MSG_ERROR);
	} else {
		$AppUI->setMsg(@$_POST['task_log_id'] ? 'atualizado' : 'inserido', UI_MSG_OK);
	}
}
$task = new CTask();
$task->load($obj->task_log_task);
$task->htmlDecode();
$task->check();
$task_end_date = new CDate($task->task_end_date);
$task->task_percent_complete = dPgetCleanParam($_POST, 'task_percent_complete', null);

if (dPgetCleanParam($_POST, 'task_end_date', '') != '') {
	$new_date = new CDate($_POST['task_end_date']);
	$new_date->setTime($task_end_date->hour, $task_end_date->minute, $task_end_date->second);
	$task->task_end_date = $new_date->format(FMT_DATETIME_MYSQL);
}

if ($task->task_percent_complete >= 100 && (!($task->task_end_date) 
                                            || $task->task_end_date == '0000-00-00 00:00:00')) {
	$task->task_end_date = $obj->task_log_date;
}

if (($msg = $task->store())) {
	$AppUI->setMsg($msg, UI_MSG_ERROR);
}

$new_task_end = new CDate($task->task_end_date);
if ($new_task_end->dateDiff($task_end_date)) {
	$task->addReminder();
}
if ($notify_owner && $msg = $task->notifyOwner()) {
	$AppUI->setMsg($msg, UI_MSG_ERROR);
}

// Check if we need to email the task log to anyone.
$email_assignees = dPgetCleanParam($_POST, 'email_assignees', null);
$email_task_contacts = dPgetCleanParam($_POST, 'email_task_contacts', null);
$email_project_contacts = dPgetCleanParam($_POST, 'email_project_contacts', null);
$email_others = dPgetCleanParam($_POST, 'email_others', '');
$email_extras = dPgetCleanParam($_POST, 'email_extras', null);

if ($task->email_log($obj, $email_assignees, $email_task_contacts, $email_project_contacts, 
    $email_others, $email_extras)) {
	$obj->store(); // Save the updated message. It is not an error if this fails.
}

echo $AppUI->getMsg();
exit();
?>
