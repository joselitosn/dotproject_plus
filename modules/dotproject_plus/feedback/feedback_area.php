
<?php
    require_once (DP_BASE_DIR . "/modules/dotproject_plus/feedback/feedback.php");
    require_once (DP_BASE_DIR . "/modules/dotproject_plus/feedback/feedback_controller.php");
?>
    <!-- CSS for switch button -->

    <span>
        <ul style="width: 305px;list-style: none;padding: 0 30px;">
            <br />
            <h5 class="text-center">::<?php echo $AppUI->_("LBL_FEEDBACK_INSTRUCTIONAL_FEEDBACK"); ?>::</h5>
            <br />
            <form method="post" action="?m=dotproject_plus" name="feedback_preferences">
                <input name="dosql" type="hidden" value="do_save_feedback_preferences" />
                <input type="hidden" name="url" value="<?php echo substr($_SERVER["REQUEST_URI"], strrpos($_SERVER["REQUEST_URI"], "?") + 1, strlen($_SERVER["REQUEST_URI"])); ?>" />
    
                    <div class="custom-control custom-switch">
                        <input type="checkbox" 
                            name="generic_feedback"
                            value="1" 
                            class="custom-control-input" 
                            id="onoffswitch_generic" 
                            <?=$_SESSION["user_generic_feedback"] == 1 ? "checked" : ""?>
                            onclick="document.feedback_preferences.submit()">
                        <label class="custom-control-label" for="onoffswitch_generic"><?php echo $AppUI->_("LBL_SEE_GENERIC_FEEDBACK"); ?></label>
                    </div>
                    
                    <div class="custom-control custom-switch">
                        <input type="checkbox" 
                            name="especific_feedback"
                            value="1" 
                            class="custom-control-input" 
                            id="onoffswitch_especific"
                            <?=$_SESSION["user_especific_feedback"] == 1 ? "checked" : ""?>
                            onclick="document.feedback_preferences.submit()" 
                            >
                        <label class="custom-control-label" for="onoffswitch_especific"><?php echo $AppUI->_("LBL_SEE_SPECIFIC_FEEDBACK"); ?></label>
                    </div>
                    <br />
            </form>
            <hr />
            <?php
                $feedback_count = 0;
                foreach ($_SESSION["user_feedback"] as $feedback_id) {
                    $feedback = $feedback_list[$feedback_id];
                    if (($feedback->getGeneric() && $_SESSION["user_generic_feedback"] == 1) || (!$feedback->getGeneric() && $_SESSION["user_especific_feedback"] == 1) ) {
                        $feedback_count++;
                        if ($feedback_count <= 5) {
                            ?>
                            <li>
                                <a style="line-height: 120%" href="#" onclick="showFeedBack(<?=$feedback->getId()?>)">
                                    <form name="show_feedback_<?php echo $feedback->getId() ?>" id="show_feedback_<?php echo $feedback->getId() ?>" method="post" action="?m=dotproject_plus">
                                        <img src="./style/dotproject_plus/img/feedback/<?php echo InstructionalFeebackManager::getIconByKnowledgeArea($feedback->getKnowledgeArea()) ?>.png" style="width:20px; height: 20px" />
                                        <b><?php echo $feedback->getKnowledgeArea(); ?></b>
                                        <?php
                                        if (!$feedback->getGeneric()) {
                                            ?>
                                            &nbsp;&nbsp;&nbsp;<img src="./style/dotproject_plus/img/feedback/TCC_icon.png" style="width:20px; height: 20px" />
    
                                            <?php
                                        }
                                        ?>
                                        <br />
                                        <?php echo $feedback->getShort(); ?>
                                        <input name="dosql" type="hidden" value="do_show_feedback" />
    
                                        <input type="hidden" name="feedback_id" value="<?php echo $feedback->getId() ?>" />
                                        <input type="hidden" name="url" value="<?php echo substr($_SERVER["REQUEST_URI"], strrpos($_SERVER["REQUEST_URI"], "?") + 1, strlen($_SERVER["REQUEST_URI"])); ?>" />
                                    </form>
                                </a>
                                <br />
                            </li>
                            <?php
                        }
                    }
                }
                if ($feedback_count>0){
            ?>
                <script>
                    $("#feedback_count").html("<?=$feedback_count?>");

                    function showFeedBack(id) {
                        $.ajax({
                            type: "POST",
                            url: "?m=dotproject_plus",
                            datatype: "json",
                            data: {
                                feedback_id: id,
                                dosql: 'do_show_feedback'
                            }
                        }).done(function(response) {

                            $.alert({
                                title: "Feedback",
                                content: response
                            });
                        });
                    }
                </script>
            <?php
            }
            ?>
        </ul>
    </span>
    