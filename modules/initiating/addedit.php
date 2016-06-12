<?php
if (!defined('DP_BASE_DIR')) {
    die('You should not access this file directly.');
}
require_once DP_BASE_DIR . "/modules/initiating/initiating.class.php";
require_once (DP_BASE_DIR . "/modules/projects/projects.class.php");
$project_id = dPgetParam($_GET, "project_id", 0);
$projectObj = new CProject();
$projectObj->load($project_id);
$obj = CInitiating::findByProjectId($project_id);
$initiating_id = "";
if (is_null($obj)) {
    $obj = new CInitiating();
}else{
    $initiating_id = $obj->initiating_id;
}
$obj->initiating_title=$projectObj->project_name;
$msg = '';
$initiating_completed = 0;
// se for update verifica se ja esta concluido o preenchimento do termo de abertura do projeto
if ($initiating_id) {
    $initiating_completed = $obj->initiating_completed;
}
// se o termo de abertura estiver concluido verifica se está aprovado
$initiating_approved = 0;
if ($initiating_completed) {
    $initiating_approved = $obj->initiating_approved;
}
// se o termo de abertura estiver aprovado verifica se está autorizado
$initiating_authorized = 0;
if ($initiating_approved) {
    $initiating_authorized = $obj->initiating_authorized;
}
// collect all the users for the company owner list
$q = new DBQuery();
$q->addTable('contacts', 'con');
$q->addJoin("users", "u", "u.user_contact=con.contact_id");
$q->addQuery('u.user_id, CONCAT_WS(" ",contact_first_name,contact_last_name)');
$q->addWhere("con.contact_company=".$projectObj->project_company);
$owners = $q->loadHashList();
// format dates
$df = $AppUI->getPref('SHDATEFORMAT');
$start_date = new CDate($obj->initiating_start_date);
$end_date = new CDate($obj->initiating_end_date);
?>
<link rel="stylesheet" type="text/css" media="all" href="<?php echo DP_BASE_URL; ?>/lib/calendar/calendar-dp.css" title="blue" />
<!-- import the calendar script -->
<script type="text/javascript" src="<?php echo DP_BASE_URL; ?>/lib/calendar/calendar.js"></script>
<!-- import the language module -->
<script type="text/javascript" src="<?php echo DP_BASE_URL; ?>/lib/calendar/lang/calendar-<?php echo $AppUI->user_locale; ?>.js"></script>
<script language="javascript">
    var calendarField = '';
    var calWin = null;
    function popCalendar(field) {
        //due to a bug in Firefox (where window.open, when in a function, does not properly unescape a url)
        // we CANNOT do a window open with &amp; separating the parameters
        //this bug does not occur if the window open occurs in an onclick event
        //this bug does NOT occur in Internet explorer
        calendarField = field;
        idate = eval('document.uploadFrm.initiating_' + field + '.value');
        window.open('index.php?m=public&a=calendar&dialog=1&callback=setCalendar&date=' + idate, 'calwin', 'width=280, height=250, scrollbars=no, status=no');
    }
    /**
     *	@param string Input date in the format YYYYMMDD
     *	@param string Formatted date
     */
    function setCalendar(idate, fdate) {
        fld_date = eval('document.uploadFrm.initiating_' + calendarField);
        fld_fdate = eval('document.uploadFrm.' + calendarField);
        fld_date.value = idate;
        fld_fdate.value = fdate;
    }
      
    function submitIt() {
        validateForm();
        var f = document.uploadFrm;
        f.submit();
    }
    // função para marcar como concluido o preenchimento do termo de abertura
    function completedIt() {
        var f = document.uploadFrm;
        f.initiating_completed.value='1';
        f.submit();
    }
    // função para marcar como aprovado o termo de abertura
    function approvedIt() {
        var f = document.uploadFrm;
        f.initiating_approved.value='1';
        f.submit();
    }
    //função para marcar como não aprovado o termo de abertura
    function notapprovedIt() {
        var f = document.uploadFrm;
        f.initiating_approved.value='0';
        f.initiating_completed.value='0';
        f.submit();
    }
    // função para marcar como autorizado o termo de abertura
    function authorizedIt() {
        var f = document.uploadFrm;
        f.initiating_authorized.value='1';
        f.action_authorized_performed.value="1";
        f.submit();
    }
    //função para marcar como n�o autorizado o termo de abertura
    function notauthorizedIt() {
        var f = document.uploadFrm;
        f.initiating_authorized.value='0';
        f.initiating_approved.value='0';
        f.initiating_completed.value='0';
        f.submit();
    }
    
function MascaraMoeda(objTextBox, SeparadorMilesimo, SeparadorDecimal, e){
    var sep = 0;
    var key = '';
    var i = j = 0;
    var len = len2 = 0;
    var strCheck = '0123456789';
    var aux = aux2 = '';
    var whichCode = (window.Event) ? e.which : e.keyCode;
    if (whichCode == 13) return true;
    key = String.fromCharCode(whichCode); // Valor para o código da Chave
    if (strCheck.indexOf(key) == -1) return false; // Chave inválida
    len = objTextBox.value.length;
    for(i = 0; i < len; i++)
        if ((objTextBox.value.charAt(i) != '0') && (objTextBox.value.charAt(i) != SeparadorDecimal)) break;
    aux = '';
    for(; i < len; i++)
        if (strCheck.indexOf(objTextBox.value.charAt(i))!=-1) aux += objTextBox.value.charAt(i);
    aux += key;
    len = aux.length;
    if (len == 0) objTextBox.value = '';
    if (len == 1) objTextBox.value = '0'+ SeparadorDecimal + '0' + aux;
    if (len == 2) objTextBox.value = '0'+ SeparadorDecimal + aux;
    if (len > 2) {
        aux2 = '';
        for (j = 0, i = len - 3; i >= 0; i--) {
            if (j == 3) {
                aux2 += SeparadorMilesimo;
                j = 0;
            }
            aux2 += aux.charAt(i);
            j++;
        }
        objTextBox.value = '';
        len2 = aux2.length;
        for (i = len2 - 1; i >= 0; i--)
        objTextBox.value += aux2.charAt(i);
        objTextBox.value += SeparadorDecimal + aux.substr(len - 2, len);
    }
    return false;
}

function replaceAll(str, de, para){
    var pos = str.indexOf(de);
    while (pos > -1){
		str = str.replace(de, para);
		pos = str.indexOf(de);
	}
    return (str);
}

function  validateForm(){
    var fieldBudget=document.uploadFrm.initiating_budget;
    if(typeof fieldBudget !== "undefined"){
    var newValue=fieldBudget.value;
    if(newValue!=""){
        newValue = replaceAll(newValue, ".","");
        newValue = replaceAll(newValue, ",",".");
        fieldBudget.value=newValue;
    }else{
        newValue="0";
    }
    return true;
    }else{
        return true;//If the field does not exist, do not complaint about it
    }
}
    
</script>
<link href="modules/timeplanning/css/table_form.css" type="text/css" rel="stylesheet" />
<style>
    textarea{
        width: 350px;
        height: 50px;
        text-wrap: avoid;
    }
</style>
<form name="uploadFrm" action="?m=initiating" method="post">
    <input type="hidden" name="dosql" value="do_initiating_aed" />
    <input type="hidden" name="project_id" value="<?php echo $project_id; ?>" />
    <input type="hidden" name="initiating_title" value="<?php echo $obj->initiating_title; ?>" />
    <input type="hidden" name="del" value="0" />
    <input type="hidden" name="initiating_id" value="<?php echo $initiating_id; ?>" />
    <input type="hidden" name="initiating_completed" value="<?php echo $initiating_completed; ?>" />
    <input type="hidden" name="initiating_approved" value="<?php echo $initiating_approved; ?>" />
    <input type="hidden" name="initiating_authorized" value="<?php echo $initiating_authorized; ?>" />
    <input type="hidden" name="action_authorized_performed" value="0" /> <!-- field set to 1 after execute the authorized action -->
    <table width="95%" align="center" border="0" cellpadding="3" cellspacing="3" class="std" name="table_form">
        <tr>
            <th colspan="4" align="center">
                <?php echo $AppUI->_("LBL_OPEN_PROJECT_CHARTER"); ?>
            </th>
        </tr>
        <tr>
            <td class="td_label" >
                <?php echo $AppUI->_('Project Title'); ?>:
            </td>
            <td align="left" nowrap="nowrap">
                <?php echo $obj->initiating_title ?>
            </td>

            <td class="td_label">
                <?php echo $AppUI->_('Project Manager'); ?>:
            </td>
            <td align="left">
                <?php echo arraySelect($owners, 'initiating_manager', 'size="1" class="text"', ((@$obj->initiating_manager) ? $obj->initiating_manager : $AppUI->contact_id)); ?>
            </td>
        </tr>
        <?php if ($initiating_id) { ?>
            <tr>
                <td class="td_label"><?php echo $AppUI->_('Justification'); ?>:</td>
                <td>
                    <textarea name="initiating_justification"   class="textarea"><?php echo $obj->initiating_justification; ?></textarea>
                </td>
                <td class="td_label"><?php echo $AppUI->_('Objectives'); ?>:</td>
                <td>
                    <textarea name="initiating_objective"   class="textarea"><?php echo  $obj->initiating_objective; //dPformSafe ?></textarea>
                </td>
            </tr>
            <tr>
                <td class="td_label"><?php echo $AppUI->_('Expected Results'); ?>:</td>
                <td>
                    <textarea name="initiating_expected_result"   class="textarea"><?php echo $obj->initiating_expected_result ?></textarea>
                </td>
                <td class="td_label"><?php echo $AppUI->_('Premises'); ?>:</td>
                <td>
                    <textarea name="initiating_premise"   class="textarea"><?php echo $obj->initiating_premise ?></textarea>
                </td>
            </tr>
            <tr>
                <td class="td_label"><?php echo $AppUI->_('Restrictions'); ?>:</td>
                <td>
                    <textarea name="initiating_restrictions"   class="textarea"><?php echo $obj->initiating_restrictions; ?></textarea>
                </td>

                <td class="td_label"><?php echo $AppUI->_('Budget'); ?> (<?php echo dPgetConfig("currency_symbol") ?>):</td>
                <td>
                    <input name="initiating_budget" style="width: 100px;" maxlength="15" onKeyPress="return(MascaraMoeda(this,'.',',',event))" class="text" value="<?php echo number_format($obj->initiating_budget, 2, ',', '.'); ?>" />
                </td>
            </tr>
            <tr>
                <td class="td_label"><?php echo $AppUI->_('Start Date'); ?></td>
                <td nowrap="nowrap"><input type="hidden" name="initiating_start_date" value="<?php echo $start_date->format(FMT_TIMESTAMP_DATE); ?>" />
                    <input type="text" style="width:80px" class="text" name="start_date" id="date1" value="<?php echo $start_date->format($df); ?>" class="text" disabled="disabled" />

                    <a href="#" onclick="javascript:popCalendar('start_date', 'start_date');">
                        <img src="./images/calendar.gif" width="24" height="12" alt="<?php echo $AppUI->_('Calendar'); ?>" border="0" />
                    </a>
                </td>
                <td class="td_label"><?php echo $AppUI->_('End Date'); ?></td>
                <td nowrap="nowrap"><input type="hidden" name="initiating_end_date" value="<?php echo $end_date->format(FMT_TIMESTAMP_DATE); ?>" />
                    <input type="text" style="width:80px" class="text" name="end_date" id="date1" value="<?php echo $end_date->format($df); ?>" class="text" disabled="disabled" />
                    <a href="#" onclick="javascript:popCalendar('end_date', 'end_date');">
                        <img src="./images/calendar.gif" width="24" height="12" alt="<?php echo $AppUI->_('Calendar'); ?>" border="0" />
                    </a>
                </td>
            </tr>
            <tr>
                <td class="td_label"><?php echo $AppUI->_('Milestones'); ?>:</td>
                <td>
                    <textarea name="initiating_milestone"   class="textarea"><?php echo $obj->initiating_milestone; ?></textarea>
                </td>
                <td class="td_label"><?php echo $AppUI->_('Criteria for success'); ?>:</td>
                <td>
                    <textarea name="initiating_success"   class="textarea"><?php echo $obj->initiating_success; ?></textarea>
                </td>
            </tr>
            <tr>
                <td class="td_label"><?php echo $AppUI->_('Approved/Not Approved Comments'); ?>:</td>
                <td align="left" >
                    <textarea name="initiating_approved_comments"   class="textarea"><?php echo $obj->initiating_approved_comments; ?></textarea>
                </td>
                <td class="td_label"><?php echo $AppUI->_('Authorized/Not Authorized Comments'); ?>:</td>
                <td>
                    <textarea name="initiating_authorized_comments"   class="textarea"><?php echo $obj->initiating_authorized_comments; ?></textarea>
                </td>
            </tr>
            <tr>
                <td class="td_label" > <?php echo $AppUI->_("LBL_STATUS"); ?>: </td>
                <td colspan="3"> <?php echo $AppUI->_($obj->getStatus()); ?> </td>
            </tr>
        </table>
    <?php } ?>
    <table width="95%" align="center">
        <tr>
            <td align="right">
                <?php print("<a href='?m=initiating&amp;a=pdf&amp;id=$initiating_id&amp;suppressHeaders=1'><b>" . $AppUI->_('Gerar PDF') . "</b></a>\n"); ?>
                <?php if ($initiating_id && !$initiating_completed) { ?>
                    <input type="button" class="button" value="<?php echo $AppUI->_('Completed'); ?>" onclick="completedIt()" />
                <?php } if ($initiating_completed && !$initiating_approved) { ?>
                    <input type="button" class="button" value="<?php echo $AppUI->_('Approved'); ?>" onclick="approvedIt()" />
                    <input type="button" class="button" value="<?php echo $AppUI->_('Not approved'); ?>" onclick="notapprovedIt()" />
                <?php } if ($initiating_approved && !$initiating_authorized) { ?>
                    <input type="button" class="button" value="<?php echo $AppUI->_('Authorized'); ?>" onclick="authorizedIt()" />
                    <input type="button" class="button" value="<?php echo $AppUI->_('Not authorized'); ?>" onclick="notauthorizedIt()" />
                <?php } ?>
                    <input type="button" class="button" value="<?php echo ucfirst($AppUI->_('submit')); ?>" onclick="submitIt()" />
            </td>
        </tr>
    </table>
</form>