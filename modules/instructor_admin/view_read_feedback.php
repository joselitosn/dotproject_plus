<?php
/**
 * This page was designed to display which feedback messages already have being read by a certain user.
 */

if(!isset($_GET["user_id"])){
  die("This page may not be accessed without parameters");   
}
require_once (DP_BASE_DIR . "/modules/projects/projects.class.php");
require_once (DP_BASE_DIR . "/modules/admin/admin.class.php");
require_once (DP_BASE_DIR . "/modules/instructor_admin/class.class.php");
require_once (DP_BASE_DIR . "/modules/dotproject_plus/feedback/feedback_controller.php");
$userId=$_GET["user_id"];
$projectId= $_GET["project_id"];
$classId=$_GET["class_id"];
$project= new CProject();
$user = new CUser();
$class= new CClass();
$project->load($projectId);
$user->load($userId);
$class->load($classId);
$feedbackManager=new InstructionalFeebackManager();
$readFeeback=$feedbackManager->getUserReadFeedback($userId);
$kaList=$feedbackManager->getKaList();
?>

<div class="alert alert-secondary" role="alert">
    <b><?=$AppUI->_("LBL_PROJECT")?>: </b>
    <?=$project->project_name?>
    |
    <b><?=$AppUI->_("LBL_LOGIN")?>: </b>
    <?=$user->user_username?>
</div>

<?php
foreach ($kaList as $ka) {
    $feedbackListKa = $feedbackManager->getAllFeedbackPerKnoledgeArea($ka);
    ?>
        <div class="card inner-card">
            <div class="card-header"><?=$ka?></div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-sm table-bordered table-responsive-md">
                            <thead class="thead-dark">
                                <tr>
                                    <th width="32%"><?php echo $AppUI->_("LBL_FEEDBACK_SHORT_FORMAT"); ?></th>
                                    <th width="40%"><?php echo $AppUI->_("Feedback"); ?></th>
                                    <th width="10%"><?php echo $AppUI->_("LBL_TRIGGER"); ?></th>
                                    <th width="10%"><?php echo $AppUI->_("LBL_FEEDBACK_READ_ON"); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($feedbackListKa as $feedback) {
                                    $id = $feedback->getId();
                                    $short = $feedback->getShort();
                                    $description = $feedback->getDescription();
                                    $wasTriggered= $feedback->getIsTriggerFiredForCurrentUser();
                                    $wasRead = isset($readFeeback[$id])?$readFeeback[$id]:"-----";
                                    ?>
                                    <tr>
                                        <td><?php echo $short ?></td>
                                        <td><?php echo $description ?></td>
                                        <td style="text-align: center;<?php echo $wasTriggered?"background-color:#ff9999":"" ?>">
                                            <?php echo $AppUI->_($wasTriggered?"LBL_YES":"LBL_NO") ?>
                                        </td>
                                        <td style="text-align: center"><?php echo $wasRead ?></td>
                                    </tr>
                                    <?php
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    <?php
}
exit();
?>
