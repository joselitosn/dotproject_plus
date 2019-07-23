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

<!--<link rel="stylesheet" type="text/css" media="all" href="./modules/initiating/libraries/modal/modal.css"  />-->
<!--<link rel="stylesheet" type="text/css" media="all" href="./modules/initiating/libraries/modal/table_form.css"  />-->
<!--<!-- include libraries for lightweight messages -->
<!--<link type="text/css" rel="stylesheet" href="./modules/initiating/libraries/alertifyjs/alertify.min.css" media="screen"></link>-->
<!--<script type="text/javascript" src="./modules/initiating/libraries/alertifyjs/alertify.min.js"></script>-->
<!--<!-- jquery -->
<!--<script type="text/javascript" src="./modules/initiating/libraries/jquery/jquery-3.2.1.min.js"></script>-->
<!--<script type="text/javascript" src="./modules/initiating/libraries/jquery/jquery-ui.js"></script>-->
<!--<link type="text/css" rel="stylesheet" href="./modules/initiating/libraries/jquery/jquery-ui.css" media="screen"></link>-->
<!--<script type="text/javascript" src="./modules/initiating/libraries/jquery/jquery-datepicker-customizations.js"></script>-->
<script type="text/javascript" src="./modules/initiating/libraries/jquery/jquery.maskMoney.js"></script>
<?php
//get user dateformat preference
GLOBAL $AppUI;
$userDateFormat=$AppUI->user_prefs["SHDATEFORMAT"]; 
$_SESSION["dateFormatPHP"]=$userDateFormat;
$userDateFormat=str_replace("%d", "dd", $userDateFormat);
$userDateFormat=str_replace("%m", "mm", $userDateFormat); 
$userDateFormat=str_replace("%Y", "YY", $userDateFormat);
$userDateFormat=strtolower($userDateFormat); 
$_SESSION["dateFormat"]=$userDateFormat;
$AppUI->savePlace();
?>
<script language="javascript">

      
    function submitIt() {
        validateForm();
        var f = document.uploadFrm;
        f.submit();
    }
	
	function newMilestone(){
		$("#new_milestone").val("1");
		submitIt();
	}
	
	function delMilestone(id){
		$("#delete_milestone_id").val(id);
		submitIt();
	}
	
    // função para marcar como concluido o preenchimento do termo de abertura
    function completedIt() {
        alertify.confirm("<?php echo $AppUI->_("Do you confirm the project charter conclusion?") ?>", function () {
            validateForm();
            var f = document.uploadFrm;
            f.initiating_completed.value='1';
            f.submit();
        }, function() {
            // user clicked "cancel"
        });      
    }
    
    // função para marcar como aprovado o termo de abertura
    function approvedIt() {
        validateForm();
        var f = document.uploadFrm;
        f.initiating_approved.value='1';
        f.submit();
    }
    //função para marcar como não aprovado o termo de abertura
    function notapprovedIt() {
        validateForm();
        var f = document.uploadFrm;
        f.initiating_approved.value='0';
        f.initiating_completed.value='0';
        f.submit();
    }
    // função para marcar como autorizado o termo de abertura
    function authorizedIt() {
        validateForm();
        var f = document.uploadFrm;
        f.initiating_authorized.value='1';
        f.action_authorized_performed.value="1";
        f.submit();
    }
    //função para marcar como n�o autorizado o termo de abertura
    function notauthorizedIt() {
        validateForm();
        var f = document.uploadFrm;
        f.initiating_authorized.value='0';
        f.initiating_approved.value='0';
        f.initiating_completed.value='0';
        f.submit();
    }
 $( document ).ready(function() {
    $("#initiating_budget").maskMoney({
        prefix:'', // R$, U$ The symbol to be displayed before the value entered by the user
        allowZero:true, // Prevent users from inputing zero
        allowNegative:false, // Prevent users from inputing negative values
        defaultZero:false, // when the user enters the field, it sets a default mask using zero
        thousands: '.', // The thousands separator
        decimal: ',' , // The decimal separator
        precision: 2, // How many decimal places are allowed
        affixesStay : false, // set if the symbol will stay in the field after the user exits the field.
        symbolPosition : 'left' // use this setting to position the symbol at the left or right side of the value. default 'left'
    }); 
	
	$("#date1").datepicker({dateFormat: "<?php echo $_SESSION["dateFormat"] ?>"});
	$("#date2").datepicker({dateFormat: "<?php echo $_SESSION["dateFormat"] ?>"});
	
	
 });

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

function resetWorkflow(){
     alertify.confirm("<?php echo $AppUI->_("Do you want to rest the approvation/authorization workflow?") ?>", function () {
        var f = document.reset_workflow;
        f.submit();
    }, function() {
        // user clicked "cancel"
    });   
}
    
</script>


<h4><?=$AppUI->_("LBL_OPEN_PROJECT_CHARTER",UI_OUTPUT_HTML)?></h4>
<hr>

<form name="uploadFrm" action="?m=initiating" method="post">
    <input type="hidden" name="dosql" value="do_initiating_aed" />
    <input type="hidden" name="project_id" value="<?php echo $project_id; ?>" />
    <input type="hidden" name="initiating_title" value="<?php echo $obj->initiating_title; ?>" />
    <input type="hidden" name="del" value="0" />
    <input type="hidden" name="initiating_id" value="<?php echo $initiating_id; ?>" />
    <input type="hidden" name="initiating_completed" value="<?php echo $initiating_completed; ?>" />
    <input type="hidden" name="initiating_approved" value="<?php echo $initiating_approved; ?>" />
    <input type="hidden" name="initiating_authorized" value="<?php echo $initiating_authorized; ?>" />
    <input type="hidden" name="action_authorized_performed" value="0" />
    <input type="hidden" name="new_milestone" id="new_milestone" value="0" />
    <input type="hidden" name="delete_milestone_id" id="delete_milestone_id" value="0" />

    <div class="form-group">
        <span class="required"></span>
        <?=$AppUI->_('requiredField');?>
    </div>
    <div class="row">
        <div class="col-md-4">
            <label for="roles"><?=$AppUI->_('Project Manager')?></label>
            <div class="form-group">
                <select class="form-control form-control-sm select-manager" name="initiating_manager">
                    <?php
                    foreach ($owners as $key => $value) {
                        $selected = $obj->initiating_manager == $key ? 'selected="selected"' : '';
                        ?>
                        <option></option>
                        <option value="<?=$key?>" <?=$selected?>>
                            <?=$value?>
                        </option>
                        <?php
                    }
                    ?>
                </select>
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group">
                <label for="initiating_start_date"><?=$AppUI->_("Start Date")?></label>
                <input type="hidden" name="initiating_start_date" value="<?=$start_date->format(FMT_TIMESTAMP_DATE)?>" />
                <input type="text"
                       name="start_date"
                       class="form-control form-control-sm datepicker"
                       placeholder="dd/mm/yyyy"
                       value="<?=$start_date->format($df)?>" />
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group">
                <label for="initiating_start_date"><?=$AppUI->_("End Date")?></label>
                <input type="hidden" name="initiating_end_date" value="<?=$end_date->format(FMT_TIMESTAMP_DATE)?>" />
                <input type="text"
                       name="end_date"
                       class="form-control form-control-sm datepicker"
                       placeholder="dd/mm/yyyy"
                       value="<?=$end_date->format($df)?>" />
            </div>
        </div>
    </div>
    <?php if ($initiating_id) { ?>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="initiating_justification">
                        <?=$AppUI->_('Justification')?>
                    </label>
                    <textarea rows="4" class="form-control form-control-sm" name="initiating_justification"><?=$obj->initiating_justification?></textarea>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="initiating_objective">
                        <?=$AppUI->_('Objectives')?>
                    </label>
                    <textarea rows="4" class="form-control form-control-sm" name="initiating_objective"><?=$obj->initiating_objective?></textarea>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="initiating_expected_result">
                        <?=$AppUI->_('Expected Results')?>
                    </label>
                    <textarea rows="4" class="form-control form-control-sm" name="initiating_expected_result"><?=$obj->initiating_expected_result?></textarea>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="initiating_premise">
                        <?=$AppUI->_('Premises')?>
                    </label>
                    <textarea rows="4" class="form-control form-control-sm" name="initiating_premise"><?=$obj->initiating_premise?></textarea>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="initiating_restrictions">
                        <?=$AppUI->_('Restrictions')?>
                    </label>
                    <textarea rows="4" class="form-control form-control-sm" name="initiating_restrictions"><?=$obj->initiating_restrictions?></textarea>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="initiating_budget">
                        <?=$AppUI->_('Budget')?> (<?=dPgetConfig("currency_symbol")?>)
                    </label>
                    <textarea rows="4" class="form-control form-control-sm" name="initiating_budget"><?=$obj->initiating_budget?></textarea>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="initiating_success">
                        <?=$AppUI->_('Criteria for success')?>
                    </label>
                    <textarea rows="4" class="form-control form-control-sm" name="initiating_success"><?=str_replace("\n", "<br />", $obj->initiating_success)?></textarea>
                </div>
            </div>

        </div>

        <div class="row">
            <div class="col-md-6">
                <label for="initiating_restrictions">
                    <?=$AppUI->_('Milestones')?>
                </label>
                <br>
                <div class="form-group">
                    <button type="button" class="btn btn-sm btn-secondary" onclick="newMilestone()" title="Adicionar marco">
                        <i class="far fa-plus-square"></i>
                    </button>
                </div>
                <div id="milestones">
                <?php
                    $milestones =$obj->loadMillestones();
                    $i=0;

                    foreach($milestones as $milestone) {
                        $milestone_date = new CDate($milestone->task_start_date);
                        ?>
                        <div class="form-group">
                            <div class="row" id="milestone_row_id_<?=$i?>">
                                <div class="col-md-8">
                                    <input type="hidden" name="milestone_id_<?=$i?>"
                                           value="<?=$milestone->task_id?>"/>
                                    <input type="text"
                                       value="<?=$milestone->task_name?>"
                                       class="form-control form-control-sm"
                                       name="milestone_name_<?=$i?>"
                                       id="milestone_name_<?=$i?>" />
                                </div>
                                <div class="col-md-3">
                                    <input type="text"
                                       class="form-control form-control-sm datepicker"
                                       name="milestone_date_<?=$i?>"
                                       id="milestone_date_<?=$i?>"
                                       value="<?=$milestone_date->format($df)?>" />
                                </div>
                                <div class="col-md-1">
                                    <button type="button" class="btn btn-sm btn-danger" onclick="delMilestone(<?=$milestone->task_id?>)" title="Remover marco">
                                        <i class="far fa-trash-alt"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <?php
                        $i++;
                    }
                    ?>
                    <input type="hidden" name="total_milestones" value="<?=$i?>" />
                </div>
            </div>
        </div>

        <div class="alert alert-info" role="alert">
            <strong><?=$AppUI->_("Status")?>: </strong>
            <?=$AppUI->_($obj->getStatus())?>
        </div>

        <?php
            if ($obj->initiating_approved_comments !="") {
        ?>
            <div class="alert alert-secondary" role="alert">
                <strong><?=$AppUI->_('Approved/Not Approved Comments')?>: </strong>
                <?=str_replace("\n", "<br />", $obj->initiating_approved_comments)?>
            </div>
    <?php
        }
        if ($obj->initiating_authorized_comments !="") {
            ?>
            <div class="alert alert-secondary" role="alert">
                <strong><?=$AppUI->_('Authorized/Not Authorized Comments')?>: </strong>
                <?=str_replace("\n", "<br />", $obj->initiating_authorized_comments)?>
            </div>
            <?php
        }
    }
    ?>

    <div class="row">
        <div class="col-md-12 text-right">
            <a href="?m=initiating&amp;a=pdf&amp;id=$initiating_id&amp;suppressHeaders=1"
               target="_blank"
                class="btn btn-sm btn-secondary">
                <?=$AppUI->_("Gerar PDF")?>
            </a>

            <button style="visibility:<?=$initiating_completed != 1 ? 'visible' : 'hidden'?>" type="button" class="btn btn-sm btn-primary" onclick="submitIt()"><?=ucfirst($AppUI->_('LBL_SAVE'))?></button>

            <?php if ($initiating_id && !$initiating_completed) { ?>
                <button type="button" class="btn btn-sm btn-secondary" onclick="initiating.complete()"><?=ucfirst($AppUI->_('Completed'))?></button>
            <?php } if ($initiating_completed && !$initiating_approved) { ?>

                <button type="button" class="btn btn-sm btn-secondary" onclick=""><?=ucfirst($AppUI->_('Approved'))?></button>
<!--                <input type="button" class="button" value="--><?php //echo $AppUI->_('Approved'); ?><!--" onclick="document.getElementById('authorize_div').style.display='none';document.getElementById('approve_div').style.display='block';modal.style.display = 'block';" />-->

            <?php } if ($initiating_approved && !$initiating_authorized) { ?>
                <button type="button" class="btn btn-sm btn-secondary" onclick=""><?=ucfirst($AppUI->_('Authorized'))?></button>
<!--                <input type="button" class="button" value="--><?php //echo $AppUI->_('Authorized'); ?><!--" onclick="document.getElementById('authorize_div').style.display='block';document.getElementById('approve_div').style.display='none';modal.style.display = 'block';" />-->
            <?php } ?>
        </div>
    </div>

    <hr>


<!--    <table width="95%" align="center" border="0" cellpadding="3" cellspacing="3" class="std" name="table_form" >-->


        <?php if ($initiating_id) { ?>
<!--            <tr>-->
<!--                <td class="td_label">--><?php //echo $AppUI->_('Justification'); ?><!--:</td>-->
<!--                <td >-->
<!--                    <textarea style="display:--><?php //echo $initiating_completed!=1?"block":"none" ?><!--" name="initiating_justification"   class="textarea">--><?php //echo $obj->initiating_justification; ?><!--</textarea>-->
<!--                    <span style="display:--><?php //echo $initiating_completed==1?"block":"none" ?><!--">--><?php //echo str_replace("\n", "<br />", $obj->initiating_justification); ?><!--</span>-->
<!--                </td>-->
<!--                <td class="td_label">--><?php //echo $AppUI->_('Objectives'); ?><!--:</td>-->
<!--                <td >-->
<!--                    <textarea style="display:--><?php //echo $initiating_completed!=1?"block":"none" ?><!--" name="initiating_objective" class="textarea">--><?php //echo  $obj->initiating_objective; //dPformSafe ?><!--</textarea>-->
<!--                    <span style="display:--><?php //echo $initiating_completed==1?"block":"none" ?><!--">--><?php //echo str_replace("\n", "<br />", $obj->initiating_objective); ?><!--</span>-->
<!--                </td>-->
<!--            </tr>-->
<!--            <tr>-->
<!--                <td class="td_label">--><?php //echo $AppUI->_('Expected Results'); ?><!--:</td>-->
<!--                <td>-->
<!--                    <textarea style="display:--><?php //echo $initiating_completed!=1?"block":"none" ?><!--" name="initiating_expected_result"   class="textarea">--><?php //echo $obj->initiating_expected_result ?><!--</textarea>-->
<!--                    <span style="display:--><?php //echo $initiating_completed==1?"block":"none" ?><!--">--><?php //echo str_replace("\n", "<br />", $obj->initiating_expected_result) ?><!--</span>-->
<!--                </td>-->
<!--                <td class="td_label">--><?php //echo $AppUI->_('Premises'); ?><!--:</td>-->
<!--                <td>-->
<!--                    <textarea style="display:--><?php //echo $initiating_completed!=1?"block":"none" ?><!--" name="initiating_premise"   class="textarea">--><?php //echo $obj->initiating_premise ?><!--</textarea>-->
<!--                    <span style="display:--><?php //echo $initiating_completed==1?"block":"none" ?><!--">--><?php //echo str_replace("\n", "<br />", $obj->initiating_premise) ?><!--</span>-->
<!--                </td>-->
<!--            </tr>-->
<!--            <tr>-->
<!--                <td class="td_label">--><?php //echo $AppUI->_('Restrictions'); ?><!--:</td>-->
<!--                <td>-->
<!--                    <textarea style="display:--><?php //echo $initiating_completed!=1?"block":"none" ?><!--" name="initiating_restrictions"   class="textarea">--><?php //echo $obj->initiating_restrictions; ?><!--</textarea>-->
<!--                    <span style="display:--><?php //echo $initiating_completed==1?"block":"none" ?><!--">--><?php //echo str_replace("\n", "<br />", $obj->initiating_restrictions) ?><!--</span>-->
<!--                </td>-->
<!---->
<!--                <td class="td_label">--><?php //echo $AppUI->_('Budget'); ?><!-- (--><?php //echo dPgetConfig("currency_symbol") ?><!--):</td>-->
<!--                <td>-->
<!--                    <input style="display:--><?php //echo $initiating_completed!=1?"block":"none" ?><!--" name="initiating_budget" id="initiating_budget" style="width: 100px;" maxlength="15" class="text" value="--><?php //echo number_format($obj->initiating_budget, 2, ',', '.'); ?><!--" />-->
<!--                    <span style="display:--><?php //echo $initiating_completed==1?"block":"none" ?><!--">--><?php //echo number_format($obj->initiating_budget, 2, ',', '.'); ?><!--</span>-->
<!--                </td>-->
<!--            </tr>-->
<!--            <tr>-->
<!--                <td class="td_label">--><?php //echo $AppUI->_('Start Date'); ?><!--</td>-->
<!--                <td nowrap="nowrap"><input type="hidden" name="initiating_start_date" value="--><?php //echo $start_date->format(FMT_TIMESTAMP_DATE); ?><!--" />-->
<!--                   <span style="display:--><?php //echo $initiating_completed!=1?"block":"none" ?><!--">-->
<!--                        <input type="text" style="width:80px" class="text" name="start_date" id="date1" value="--><?php //echo $start_date->format($df); ?><!--" class="text" />-->
<!--                    </span>-->
<!--                    <span style="display:--><?php //echo $initiating_completed==1?"block":"none" ?><!--">--><?php //echo $start_date->format($df); ?><!--</span>-->
<!--                </td>-->
<!--                <td class="td_label">--><?php //echo $AppUI->_('End Date'); ?><!--</td>-->
<!--                <td nowrap="nowrap"><input type="hidden" name="initiating_end_date" value="--><?php //echo $end_date->format(FMT_TIMESTAMP_DATE); ?><!--" />-->
<!--                    <span style="display:--><?php //echo $initiating_completed!=1?"block":"none" ?><!--">-->
<!--                        <input type="text" style="width:80px" class="text" name="end_date" id="date2" value="--><?php //echo $end_date->format($df); ?><!--" class="text" />-->
<!---->
<!--                    </span>-->
<!--                    <span style="display:--><?php //echo $initiating_completed==1?"block":"none" ?><!--">--><?php //echo $end_date->format($df); ?><!--</span>-->
<!--                </td>-->
<!--            </tr>-->
<!--            <tr style="display: block">-->
<!--                <td class="td_label">--><?php //echo $AppUI->_('Milestones'); ?><!--:</td>-->
<!--                <td>-->

<!--                     <textarea style="display:--><?php //echo $initiating_completed!=1?"block":"none" ?><!--" name="initiating_milestone"   class="textarea">--><?php //echo $obj->initiating_milestone; ?><!--</textarea> -->
<!--					<img src="./modules/initiating/images/add_button_icon.png" onclick="newMilestone()" style="cursor:pointer;width:18px;height:18px;display:--><?php //echo $initiating_completed!=1?"block":"none" ?><!--" />-->
					<?php
//					$milestones =$obj->loadMillestones();
//					$i=0;
//
//					foreach($milestones as $milestone){
//						$milestone_date = new CDate($milestone->task_start_date);
//						?>
<!--						<span style="display:--><?php //echo $initiating_completed!=1?"block":"none" ?><!--">-->
<!--							<br />-->
<!--							<input type="hidden" name="milestone_id_--><?php //echo $i ?><!--" value="--><?php //echo $milestone->task_id ?><!--" />-->
<!--							<input type="text" value="--><?php //echo $milestone->task_name ?><!--" style="width:260px"  class="text" name="milestone_name_--><?php //echo $i ?><!--" id="milestone_name_--><?php //echo $i ?><!--" />-->
<!--							<input type="text" style="width:80px" class="text" name="milestone_date_--><?php //echo $i ?><!--" id="milestone_date_--><?php //echo $i ?><!--" value="--><?php //echo $milestone_date->format($df); ?><!--" class="text" />-->
<!--							<script>-->
<!--								$("#milestone_date_--><?php //echo $i ?><!--").datepicker({dateFormat: "--><?php //echo $_SESSION["dateFormat"] ?><!--//"});-->
<!--//							</script>-->
<!--//-->
<!--//							<img src="./modules/initiating/images/trash-icon.png" onclick="delMilestone(--><?php ////echo $milestone->task_id ?><!--)" style="cursor:pointer;width:15px;height:15px;" />
<!--//-->
<!--/						</span>-->
<!--//						<span style="display:--><?php ////echo $initiating_completed==1?"block":"none" ?><!--">-->
<!--						--><?php //echo $milestone->task_name ?><!-- &nbsp; (--><?php //echo $milestone_date->format($df); ?><!--)-->
<!--						</span>-->
<!--						<br />-->
<!--						--><?php
//						$i++;
//					}
					?>
<!--					<input type="hidden" name="total_milestones" value="--><?php //echo $i ?><!--" />-->
<!---->
<!--					<input type="hidden" name="new_milestone" id="new_milestone" value="0" />-->
<!--					<input type="hidden" name="delete_milestone_id" id="delete_milestone_id" value="0" />-->

                   <!-- <span style="display:<?php //echo $initiating_completed==1?"block":"none" ?>"><?php //echo str_replace("\n", "<br />", $obj->initiating_milestone); ?></span> -->

<!--                </td>-->
<!--                <td class="td_label">--><?php //echo $AppUI->_('Criteria for success'); ?><!--:</td>-->
<!--                <td>-->
<!--                    <textarea style="display:--><?php //echo $initiating_completed!=1?"block":"none" ?><!--" name="initiating_success"   class="textarea">--><?php //echo $obj->initiating_success; ?><!--</textarea>-->
<!--                    <span style="display:--><?php //echo $initiating_completed==1?"block":"none" ?><!--">--><?php //echo str_replace("\n", "<br />", $obj->initiating_success); ?><!--</span>    -->
<!--                </td>-->
<!--            </tr>-->
            
<!--            <tr>-->
<!--                <td class="td_label" >-->
<!--                    --><?php //if ($obj->initiating_approved_comments !=""){ echo $AppUI->_('Approved/Not Approved Comments');} ?>
<!--                </td>-->
<!--                <td>-->
<!--                    --><?php //echo str_replace("\n", "<br />", $obj->initiating_approved_comments); ?>
<!--                </td>-->
<!--                <td class="td_label" >-->
<!--                    --><?php //if ($obj->initiating_authorized_comments != ""){ echo $AppUI->_('Authorized/Not Authorized Comments');} ?>
<!--                </td>-->
<!--                <td>-->
<!--                    --><?php //echo str_replace("\n", "<br />", $obj->initiating_authorized_comments); ?>
<!--                 </td>-->
<!--            </tr>-->
            
            
<!--        </table>-->
    <?php } ?>
<!--    <table width="95%" align="center">-->
<!--        <tr>-->
<!--            <td align="right">-->
<!--                --><?php //print("<a href='?m=initiating&amp;a=pdf&amp;id=$initiating_id&amp;suppressHeaders=1'><b>" . $AppUI->_("Print PDF") . "</b></a>\n"); ?>
<!--                <input style="visibility:--><?php //echo $initiating_completed!=1?"visible":"hidden" ?><!--" type="button" class="button" value="--><?php //echo ucfirst($AppUI->_('submit')); ?><!--" onclick="submitIt()" />-->
<!--        -->
<!--                --><?php //if ($initiating_id && !$initiating_completed) { ?>
<!--                    <input type="button" class="button" value="--><?php //echo $AppUI->_('Completed'); ?><!--" onclick="completedIt()" />-->
<!--                --><?php //} if ($initiating_completed && !$initiating_approved) { ?>
<!--                    <input type="button" class="button" value="--><?php //echo $AppUI->_('Approved'); ?><!--" onclick="document.getElementById('authorize_div').style.display='none';document.getElementById('approve_div').style.display='block';modal.style.display = 'block';" />-->
<!--                --><?php //} if ($initiating_approved && !$initiating_authorized) { ?>
<!--                    <input type="button" class="button" value="--><?php //echo $AppUI->_('Authorized'); ?><!--" onclick="document.getElementById('authorize_div').style.display='block';document.getElementById('approve_div').style.display='none';modal.style.display = 'block';" />-->
<!--               --><?php //} ?>
<!--            </td>-->
<!--        </tr>-->
<!--    </table>-->

     
    <div id="myModal" class="modal">
        <!-- Modal content -->
        <div class="modal-content" style="color:#000">
            <span class="close">&times;</span>
            <p>
                <div id="approve_div" style="display:none">
                    <br />
                    <?php echo $AppUI->_('Approved/Not Approved Comments'); ?>:
                    <br />
                    <textarea name="initiating_approved_comments"   class="textarea"><?php echo $obj->initiating_approved_comments; ?></textarea>
                    <br />
                    <input type="button" class="button" value="<?php echo $AppUI->_('Approved'); ?>" onclick="approvedIt()" />
                    <input type="button" class="button" value="<?php echo $AppUI->_('Not approved'); ?>" onclick="notapprovedIt()" />
                </div>
                <div id="authorize_div" style="display:none">
                    <br />
                    <?php echo $AppUI->_('Authorized/Not Authorized Comments'); ?>
                    <br />
                    <textarea name="initiating_authorized_comments" class="textarea"><?php echo $obj->initiating_authorized_comments; ?></textarea>
                    <br />
                    <input type="button" class="button" value="<?php echo $AppUI->_('Authorized'); ?>" onclick="authorizedIt()" />
                    <input type="button" class="button" value="<?php echo $AppUI->_('Not authorized'); ?>" onclick="notauthorizedIt()" />
                </div>
            </p>
        </div>
    </div>
<script>
//    // Get the modal
//    var modal = document.getElementById('myModal');
//     // Get the <span> element that closes the modal
//    var span = document.getElementsByClassName("close")[0];
//
//    // When the user clicks on <span> (x), close the modal
//    span.onclick = function() {
//        modal.style.display = "none";
//    }
//
//    // When the user clicks anywhere outside of the modal, close it
//    window.onclick = function(event) {
//        if (event.target == modal) {
//            modal.style.display = "none";
//        }
//    }

    var initiating = {

        init: function () {
            $('.datepicker').datepicker({
                dateFormat: 'dd/mm/yy'
            });

            $('.select-manager').select2({
                placeholder: '',
                allowClear: true,
                theme: "bootstrap",
            });
        },
        
        newMilestone: function () {
            
        },

        removeMilestone: function (id) {
            $('#milestone_row_id_'+id).remove();
        },

        complete: function () {
            // refers to completedIt()
        }
    };

    $(document).ready(initiating.init());
</script>
</form>
 <?php if ($initiating_authorized==1){ ?>
<div align="center">
    <div style="background-color: #FFF; color:#000; width:93%;text-align: left;padding: 12px;">
        <form name="reset_workflow" action="?m=initiating" method="post">
            <input type="hidden" name="dosql" value="do_reset_workflow" />
            <input type="hidden" name="initiating_id" value="<?php echo $initiating_id; ?>" />
            <?php echo $AppUI->_("Reset approvation/authorization workflow") ?>
            <br />
            <input type="button" value="<?php echo $AppUI->_("Reset workflow") ?>" onclick="resetWorkflow()" />
        </form>
    </div>
</div>
    <?php } ?>


<?php require_once DP_BASE_DIR . "/modules/initiating/authorization_workflow.php" ?>