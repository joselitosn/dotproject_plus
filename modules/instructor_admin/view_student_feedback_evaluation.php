<?php
require_once DP_BASE_DIR . "/modules/dotproject_plus/feedback/user_feedback_evaluation/feedback_evaluation_statistic.class.php";
require_once DP_BASE_DIR . "/modules/dotproject_plus/feedback/feedback_controller.php";
$feedbackManager=new InstructionalFeebackManager();
$kaList=$feedbackManager->getKaList();

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
                                <th width="35%"><?php echo $AppUI->_("LBL_FEEDBACK_SHORT_FORMAT"); ?></th>
                                <th width="45%"><?php echo $AppUI->_("Feedback"); ?></th>
                                <th width="5%"><?php echo $AppUI->_("LBL_FEADBACK_TOTAL_EVALUATIONS"); ?></th>
                                <th width="5%"><?php echo $AppUI->_("LBL_FEEDBACK_AVERAGE_EVALUATION"); ?></th>
                                <th width="5%"><?php echo $AppUI->_("LBL_FEEDBACK_STDV_EVALUATION"); ?></th>
                            </tr>
                        </thead>
                        <tboody>
                            <?php
                            foreach ($feedbackListKa as $feedback) {
                                $id = $feedback->getId();
                                $statistics=new FeedbackEvaluationStatistic($id);
                                $short = $feedback->getShort();
                                $description = $feedback->getDescription();
                                $average= number_format((float)$statistics->getAverage(), 2, '.', '');
                                $stdv = number_format((float)$statistics->getStdv(), 2, '.', '');;
                                $total =$statistics->getTotal();
                                ?>
                                <tr>
                                    <td><?php echo $short ?></td>
                                    <td><?php echo $description ?></td>
                                    <td><?php echo $total ?></td>
                                    <td><?php echo $average ?></td>
                                    <td><?php echo $stdv ?></td>
                                </tr>
                                <?php
                            }
                            ?>
                        </tboody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <?php
}
exit();
?>
