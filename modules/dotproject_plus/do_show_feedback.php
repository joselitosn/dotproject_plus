<?php
if (!defined('DP_BASE_DIR')) {
    die('You should not access this file directly.');
}
require_once (DP_BASE_DIR . "/modules/dotproject_plus/feedback/feedback.php");
require_once (DP_BASE_DIR . "/modules/dotproject_plus/feedback/feedback_controller.php");
$url = $_POST['url'];
$feedback_id=$_POST["feedback_id"];

//$AppUI->setMsg($feedback_list[$feedback_id]->getDescription(), '',true);
//remove feedback from user feedback list
session_start();
unset($_SESSION["user_feedback"][$feedback_id]);
$_SESSION["user_feedback_read"][$feedback_id]=$feedback_id;

$img="<div style='text-align:left'><img src='./style/default/images/icon_info.png' />&nbsp;&nbsp;&nbsp;";
$img.="<img src='./style/dotproject_plus/img/feedback/". getIconByKnowledgeArea($feedback_list[$feedback_id]->getKnowledgeArea()) .".png' style='width:25px; height: 25px' />&nbsp;&nbsp;&nbsp;";
if(!$feedback_list[$feedback_id]->getGeneric()){
    $img.="<img src='./style/dotproject_plus/img/feedback/TCC_icon.png' style='width:25px; height: 25px' />";
}
$img.="</div><br />";
$title="<b>:::: ". $AppUI->_("LBL_FEEDBACK_FOR_THE KNOWLEDGEAREA_OF") ." ". $feedback_list[$feedback_id]->getKnowledgeArea() ." :::: </b> <br /> <br />";
$_SESSION["user_feedback_display_message"]=$img . $title . $feedback_list[$feedback_id]->getDescription();
$AppUI->redirect($url);

?>

<pre>
<?php //print_r($_SESSION["user_feedback"]); ?>    
</pre>