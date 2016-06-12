<?php
require_once (DP_BASE_DIR . "/modules/timeplanning/control/quality/controller_quality_planning.class.php");
$controllerQ = new ControllerQualityPlanning();
$objectQ = $controllerQ->getQualityPlanningPerProject($projectId);
$quality_planning_id = $objectQ->getId();
?>
<table class="printTable" >  
    <tr>
        <th style="font-weight: bold"><?php echo $AppUI->_("LBL_QUALITY_POLICIES", UI_OUTPUT_HTML); ?>:</th>
    </tr>
    <tr>
        <td>
            <?php 
               $qualityPolicies =preg_replace( "/\r|\n/", "<br />", $objectQ->getQualityPolicies() );
               //caracteres não interpretados pelo pdf generator
               $qualityPolicies =str_replace( "–", "-", $qualityPolicies );
               echo $qualityPolicies;
            ?>
        </td>
    </tr>
</table>
<br />
<h3> 4.1 <?php echo $AppUI->_("LBL_QUALITY_ASSURANCE"); ?> </h3>
<br />
<table class="printTable" > 
    
    <tr>
        
        <th><?php echo $AppUI->_("LBL_WHAT_AUDIT"); ?></th>
        <th><?php echo $AppUI->_("LBL_WHO_AUDIT"); ?></th> 
        <th><?php echo $AppUI->_("LBL_WHEN_AUDIT"); ?></th> 
        <th><?php echo $AppUI->_("LBL_HOW_AUDIT"); ?></th> 
        
    </tr>
    <?php
    $assuranceItems = $controllerQ->loadAssuranceItems($quality_planning_id);
    $i = 0;
    foreach ($assuranceItems as $id => $data) {
        $ai_id = $data[0];
        $what = $data[1];
        $who = $data[2];
        $when = $data[3];
        $how = $data[4];
        ?>
        <tr>
            <td valign="top">
                <?php echo $what; ?>
            </td>
            <td valign="top">
                <?php echo $who; ?>
            </td>
            <td valign="top">
                <?php echo $when; ?>
            </td>
            <td valign="top">
                <?php echo $how; ?>
            </td>
        </tr>  
        <?php
        $i++;
    }
    ?>
</table>
<br />
<h3>4.2 <?php echo $AppUI->_("LBL_QUALITY_CONTROLLING", UI_OUTPUT_HTML); ?> </h3>
<br />
<table class="printTable">
    <tr>
        <th><?php echo $AppUI->_("LBL_QUALITY_REQUIREMENTS", UI_OUTPUT_HTML); ?></th>
    </tr>
    <?php
    $requirements = $controllerQ->loadControlRequirements($quality_planning_id);

    $r = 0;

    foreach ($requirements as $id => $data) {
        $req_id = $data[0];
        $description = $data[1];
        ?>
        <tr>
            <td>
                <?php echo ($r + 1) ?>. &nbsp;<?php echo $AppUI->_($description, UI_OUTPUT_HTML); ?>
            </td>
        </tr>
        <?php
        $r++;
    }
    ?>
</table>

<br />

<?php
$goals = $controllerQ->loadControlGoals($quality_planning_id);
$i = 0;
foreach ($goals as $oid => $data) {
    $goal_id = $data[0];
    $gqm_goal_propose = $data[1];
    $gqm_goal_object = $data[2];
    $gqm_goal_respect_to = $data[3];
    $gqm_goal_point_of_view = $data[4];
    $gqm_goal_context = $data[5];
    ?>

    <br />


    <table class="printTable">
        <tr>
            <th> <?php echo ($i+1) ?>. <?php echo $AppUI->_("LBL_GOAL_OF_CONTROL"); ?></th>
        </tr>
        <tr>
            <td style="font-weight: bold">
                <label for="gqm_goal_object">
                   <?php echo $AppUI->_("LBL_GQM_GOAL_OBJECT"); ?>
                </label>
            </td>
        </tr>
        <tr>
            <td style="vertical-align: top">
                <?php echo $gqm_goal_object; ?>
            </td>
        </tr>
        <tr>
            <td style="font-weight: bold">
                <label for="gqm_goal_propose">
                    <?php echo $AppUI->_("LBL_GQM_GOAL_PURPOSE"); ?>
                </label>
            </td>
        </tr>
        <tr>
            <td style="vertical-align: top">
                <?php echo $gqm_goal_propose; ?>
            </td>
        </tr>
        <tr>
            <td style="font-weight: bold">
                <label for="gqm_goal_respect_to">
                   <?php echo $AppUI->_("LBL_GQM_GOAL_RESPECT_TO"); ?>
                </label>
            </td>
        </tr>
        <tr>
            <td>
                <?php echo $gqm_goal_respect_to; ?>
            </td>
        </tr>
        <tr>
            <td style="font-weight: bold">
                <label for="gqm_goal_point_of_view">
                     <?php echo $AppUI->_("LBL_GQM_GOAL_POINT_OF_VIEW") ?>
                </label>
            </td>
        </tr>
        <tr>
            <td>
                <?php echo $gqm_goal_point_of_view; ?>
            </td>
        </tr>

        <tr>
            <td style="font-weight: bold">
                <label for="gqm_goal_context">
                    <?php echo $AppUI->_("LBL_GQM_GOAL_CONTEXT"); ?>
                </label>
            </td>
        </tr>
        <tr>
            <td style="vertical-align: top">
                <?php echo $gqm_goal_context; ?>
            </td>
        </tr>
    </table>
    <br />
    <?php
    $questions = $controllerQ->loadQuestions($goal_id);
    $j = 0;
    foreach ($questions as $qid => $qdata) {
        $question_id = $qdata[0];
        $question = $qdata[1];
        $target = $qdata[2];
        ?>

        <table class="printTable" >
            <tr>
                <th style="font-weight: bold;vertical-align: top"><?php echo ($i+1).".".($j+1)  ?>. <?php echo $AppUI->_("LBL_QUESTION_OF_ANALYSIS"); ?></th>
                <th style="font-weight: bold;vertical-align: top"><?php echo $AppUI->_("LBL_GQM_TARGET"); ?></th>
            </tr>

            <tr>
                <td style="vertical-align: top">
                    <?php echo $question ?>
                </td>
                <td style="vertical-align: top">
                    <?php echo $target ?>
                </td>
            </tr>
        </table>
        <br />
        <table class="printTable" >
             <tr>
                <th colspan="2" style="text-align: center"><?php echo $AppUI->_("LBL_GQM_METRIC"); ?>s</th>
            </tr>
            <tr>
                <td style="font-weight: bold;vertical-align: top" width="50%">
                    <?php echo $AppUI->_("LBL_GQM_METRIC"); ?>
                </td>
                <td style="font-weight: bold;vertical-align: top" width="50%">
                    <?php echo $AppUI->_("LBL_GQM_HOW_DATA_IS_COLLECTED", UI_OUTPUT_HTML); ?>
                </td>
            </tr>

            <?php
            $metrics = $controllerQ->loadMetrics($question_id);
            $k = 0;
            foreach ($metrics as $mid => $mdata) {
                $metric_id = $mdata[0];
                $metric = $mdata[1];
                $how_to_collect = $mdata[2];
                ?>           
                <tr>
                    <td style="vertical-align: top;">
                        <?php echo ($i+1).".".($j+1).".". ($k+1)  ?>.<?php echo $metric; ?>
                    </td>
                    <td style="vertical-align: top; ">
                        <?php echo $how_to_collect; ?>
                    </td>
                </tr>
                <?php
                $k++;
            }
            ?>
        </table> 
        <br />
        <?php
        $j++;
    }
    ?>
    <br />
    <?php
    $i++;
}
?>
