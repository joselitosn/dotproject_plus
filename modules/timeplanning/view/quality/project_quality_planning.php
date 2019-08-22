<?php
require_once (DP_BASE_DIR . "/modules/timeplanning/control/quality/controller_quality_planning.class.php");
$controller = new ControllerQualityPlanning();
$projectId = $_GET["project_id"];
$object = $controller->getQualityPlanningPerProject($projectId);
$quality_planning_id = $object->getId();
?>
<a name="project_quality_planning"></a>
<style>
    textarea{
        width:80%;
        height: 150px;
        text-align: left;
    }
</style>
<script>
    function newAuditItem() {
        document.quality_form.form_action.value = 1;
        document.quality_form.submit();
    }

    function newRequirement() {
        document.quality_form.form_action.value = 2;
        document.quality_form.submit();
    }

    function newGoal() {
        document.quality_form.form_action.value = 3;
        document.quality_form.submit();
    }

    function newQuestion(goal_id_new_question) {
        document.quality_form.form_action.value = 4;
        document.quality_form.goal_id_new_question.value = goal_id_new_question;
        document.quality_form.submit();
    }

    function newMetric(question_id_new_metric) {
        document.quality_form.form_action.value = 5;
        document.quality_form.question_id_new_metric.value = question_id_new_metric;
        document.quality_form.submit();
    }

    function deleteRecord(action, id) {
        document.quality_form.form_action.value = action;
        document.quality_form.id_for_delete.value = id;
        document.quality_form.submit();
    }

</script>

<h4><?=$AppUI->_("7LBLQUALIDADE",UI_OUTPUT_HTML)?></h4>
<hr>

<div class="card inner-card">
    <div class="card-body">
        <div class="row">
            <div class="col-md-11">
                <h5 class="card-title"><?=$AppUI->_("LBL_QUALITY_POLICIES")?></h5>
            </div>
            <div class="col-md-1 text-right">
                <div class="dropdown">
                    <a href="javascript:void(0)" class="link-primary" role="button" data-toggle="dropdown"
                       aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-bars"></i>
                    </a>

                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink">
                        <a class="dropdown-item" href="javascript:void(0)" data-toggle="modal" data-target="#qualityPoliciesModal">
                            <i class="far fa-edit"></i>
                            Incluir/alterar
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-11">
                <?=$object->getQualityPolicies()?>
            </div>
        </div>
    </div>
</div>

<br>

<div class="card inner-card">
    <div class="card-body">
        <div class="row">
            <div class="col-md-11">
                <h5 class="card-title"><?=$AppUI->_("LBL_QUALITY_ASSURANCE")?></h5>
                <small><?=$AppUI->_("LBL_QUALITY_ASSURANCE_DESCRIPTION")?></small>
            </div>
            <div class="col-md-1 text-right">
                <div class="dropdown">
                    <a href="javascript:void(0)" class="link-primary" role="button" data-toggle="dropdown"
                       aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-bars"></i>
                    </a>

                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink">
                        <a class="dropdown-item" href="javascript:void(0)" data-toggle="modal" data-target="#qualityAssuranceModal">
                            <i class="far fa-edit"></i>
                            <?=$AppUI->_("LBL_QUALITY_ADD_ITEM_TO_AUDIT")?>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-md-11">
                <table class="table table-sm table-bordered">
                    <thead class="thead-dark">
                        <tr>
                            <th width="23%"><?php echo $AppUI->_("LBL_WHAT_AUDIT"); ?></th>
                            <th width="23%"><?php echo $AppUI->_("LBL_WHO_AUDIT"); ?></th>
                            <th width="23%"><?php echo $AppUI->_("LBL_WHEN_AUDIT"); ?></th>
                            <th width="23%"><?php echo $AppUI->_("LBL_HOW_AUDIT"); ?></th>
                            <th width="8%"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $assuranceItems = $controller->loadAssuranceItems($quality_planning_id);
                        foreach ($assuranceItems as $id => $data) {
                            $ai_id = $data[0] != '' ? $data[0] : null;
                            $what = $data[1] != '' ? $data[1] : null;
                            $who = $data[2] != '' ? $data[2] : null;
                            $when = $data[3] != '' ? $data[3] : null;
                            $how = $data[4] != '' ? $data[4] : null;
                            ?>
                            <tr>
                                <td>
                                    <?=$what?>
                                </td>
                                <td>
                                    <?=$who?>
                                </td>
                                <td>
                                    <?=$when?>
                                </td>
                                <td>
                                    <?=$how?>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-xs btn-secondary"
                                            onclick="quality.editQualityAssuranceItem(<?=$ai_id?>, '<?=$what?>', '<?=$who?>', '<?=$when?>', '<?=$how?>')">
                                        <i class="far fa-edit"></i>
                                    </button>
                                    <button type="button" class="btn btn-xs btn-danger"
                                            onclick="quality.deleteQualityAssuranceItem(<?=$ai_id?>)">
                                        <i class="far fa-trash-alt"></i>
                                    </button>
<!--                                    <img style="cursor:pointer" src="./modules/dotproject_plus/images/trash_small.gif" onclick="deleteRecord(10,<?php //echo $ai_id ?>//)" />-->
                                </td>
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





<form name="quality_form" method="post" action="?m=timeplanning">
    <input name="dosql" type="hidden" value="do_project_quality_planning" />
    <input name="project_id" type="hidden" value="<?php echo $_GET["project_id"]; ?>" />
    <input name="form_action" type="hidden" value="" />
    <input name="goal_id_new_question" type="hidden" value="" />
    <input name="id_for_delete" type="hidden" value="" />
    <input name="question_id_new_metric" type="hidden" value="" /> 
    <input name="quality_planning_id" type="hidden" value="<?php echo $object->getId() ?>" />
    <br/>
    <table class="tbl" align="center" cellpadding="10" width="95%" style="border: 0px solid black">

        <tr>
            <th colspan="2" style="font-weight: bold;">
                <?php echo $AppUI->_("LBL_QUALITY_CONTROLLING"); ?>     
        <div style="font-weight: normal;font-size: 9px">
            <span style="color:red">*</span>
            <?php echo $AppUI->_("LBL_QUALITY_CONTROL_DESCRIPTION")?>
        </div>
        </th>
        <!--
        <td nowrap>
            <textarea name="quality_controlling"><?php echo $object->getQualityControlling() ?></textarea>
        </td>	
        -->
        </tr>
        <tr>
            <td colspan="2">
                <br />
                &nbsp;
                <input type="button" value="<?php echo $AppUI->_("LBL_QUALITY_ADD_REQUIREMENT") ?>" onclick="newRequirement();" />
                <br /><br />
                <table width="100%" class="tbl" style="border: 0px solid black" cellpadding="10">
                    <th><?php echo $AppUI->_("LBL_QUALITY_REQUIREMENTS"); ?></th>
                    <th style="width:4%;vertical-align:top">&nbsp;</th>
                    <?php
                    $requirements = $controller->loadControlRequirements($quality_planning_id);
                    $i = 0;
                    foreach ($requirements as $id => $data) {
                        $req_id = $data[0];
                        $description = $data[1];
                        ?>
                        <tr>
                            <td>
                                <input name="requirement_id_<?php echo $i ?>" value="<?php echo $req_id; ?>" type="hidden" />
                                <?php echo $i + 1 ?>. <input name="requirement_<?php echo $i ?>" type="text" style="width: 95%" value="<?php echo $description; ?>" />
                            </td>
                            <td style="vertical-align:top">
                                <img style="cursor:pointer" src="./modules/dotproject_plus/images/trash_small.gif" onclick="deleteRecord(9,<?php echo $req_id ?>)" />
                            </td>
                        </tr>
                        <?php
                        $i++;
                    }
                    ?>

                </table>
                <input type="hidden" name="number_requirements" value="<?php echo $i ?>">
            </td>

        </tr>

        <tr>
            <td colspan="2">
                <br />
                <input name="number_goals" type="hidden" value="2"  />
                &nbsp;
                <input type="button" value="<?php echo $AppUI->_("LBL_QUALITY_ADD_GOAL"); ?>" onclick="newGoal();" />
                <br /><br />
                <!-- Primeiro objetivo -->
                <?php
                $goals = $controller->loadControlGoals($quality_planning_id);
                $i = 0;
                foreach ($goals as $oid => $data) {
                    $goal_id = $data[0];
                    $gqm_goal_propose = $data[1];
                    $gqm_goal_object = $data[2];
                    $gqm_goal_respect_to = $data[3];
                    $gqm_goal_point_of_view = $data[4];
                    $gqm_goal_context = $data[5];
                    ?>

                    <input type="hidden" name="goal_id_<?php echo $i; ?>" value="<?php echo $goal_id; ?>">
                    <table width="100%" class="tbl" style="border: 0px solid black" cellpadding="10">
                        <tr>
                            <th style="width:300px"><?php echo $AppUI->_("LBL_GOAL_OF_CONTROL"); ?></th>
                            <th><?php echo $AppUI->_("LBL_QUESTIONS_OF_ANALYSIS"); ?></th>
                            <th style="width:4%;">&nbsp;</th>
                        </tr>
                        <tr>
                            <td style="vertical-align: top">
                                <table>
                                    <tr>
                                        <td>
                                            <label for="gqm_goal_object">
                                                <?php echo $AppUI->_("LBL_GQM_GOAL_OBJECT"); ?>
                                            </label>
                                        </td>
                                    </tr>
                                    <tr>

                                        <td style="vertical-align: top">
                                            <input type="text" name="gqm_goal_object_<?php echo $i ?>" style="width:97%;" value="<?php echo $gqm_goal_object; ?>"/>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <label for="gqm_goal_propose">
                                                <?php echo $AppUI->_("LBL_GQM_GOAL_PURPOSE"); ?>
                                            </label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="vertical-align: top">
                                            <input type="text" name="gqm_goal_propose_<?php echo $i ?>" style="width:97%;" value="<?php echo $gqm_goal_propose; ?>" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td >
                                            <label for="gqm_goal_respect_to">
                                                <?php echo $AppUI->_("LBL_GQM_GOAL_RESPECT_TO"); ?>
                                            </label>

                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <input type="text" name="gqm_goal_respect_to_<?php echo $i ?>" style="width:97%;" value ="<?php echo $gqm_goal_respect_to; ?>" /> 
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>
                                            <label for="gqm_goal_point_of_view">
                                               <?php echo $AppUI->_("LBL_GQM_GOAL_POINT_OF_VIEW") ?>
                                            </label>
                                        </td>

                                    </tr>
                                    <tr>
                                        <td>
                                            <input type="text" name="gqm_goal_point_of_view_<?php echo $i ?>" style="width:97%;" value="<?php echo $gqm_goal_point_of_view; ?>"  /> 
                                        </td>
                                    </tr>

                                    <tr>
                                        <td >
                                            <label for="gqm_goal_context">
                                                <?php echo $AppUI->_("LBL_GQM_GOAL_CONTEXT"); ?>
                                            </label>
                                        </td>
                                    </tr>
                                    <tr>

                                        <td style="vertical-align: top">
                                            <input type="text" name="gqm_goal_context_<?php echo $i ?>" style="width:97%;" value="<?php echo $gqm_goal_context; ?>" /> 
                                        </td>
                                    </tr>

                                </table>
                            </td>

                            <!--  question of analysis -->
                            <td style="vertical-align: top">
                                <table width="95%" align="center" cellpadding="10">
                                    <tr>
                                        <td>
                                            <input type="button" value="<?php echo $AppUI->_("LBL_ADD_QUESTION_OF_ANALYSIS") ?>" onclick="newQuestion(<?php echo $goal_id; ?>)" />
                                        </td>
                                    </tr>
                                </table>



                                <table width="95%" align="center" class="tbl" cellpadding="10" style="border: 0px solid white">
                                    <tr>
                                        <th><?php echo $AppUI->_("LBL_QUESTION_OF_ANALYSIS"); ?></th>
                                        <th><?php echo $AppUI->_("LBL_GQM_TARGET"); ?></th>
                                        <th><?php echo $AppUI->_("LBL_GQM_METRIC"); ?></th>
                                        <th>&nbsp;</th>
                                    </tr>
                                    <?php
                                    $questions = $controller->loadQuestions($goal_id);
                                    $j = 0;
                                    foreach ($questions as $qid => $qdata) {
                                        $question_id = $qdata[0];
                                        $question = $qdata[1];
                                        $target = $qdata[2];
                                        ?>
                                        <tr>
                                            <td style="vertical-align: top">
                                                <input type="hidden" name="question_<?php echo $i ?>_id_<?php echo $j ?>" value="<?php echo $question_id; ?>" />
                                                <textarea name="analysis_question_<?php echo $i ?>_<?php echo $j ?>" style="width:97%;resize: none;" ><?php echo $question ?></textarea>
                                            </td>
                                            <td style="vertical-align: top">
                                                <textarea name="analysis_question_<?php echo $i ?>_benchmark_<?php echo $j ?>" style="width:97%;resize: none;"  ><?php echo $target ?></textarea>
                                            </td>
                                            <td valign="top">
                                                <table width="95%" align="center">
                                                    <tr>
                                                        <td>
                                                            <input type="button" value="<?php echo $AppUI->_("LBL_ADD_METRIC"); ?>" onclick="newMetric(<?php echo $question_id ?>)" />
                                                        </td>
                                                    </tr>
                                                </table>

                                                <table width="95%" align="center" cellpadding="10" class="tbl" style="border: 0px solid black">
                                                    <tr>
                                                        <th width="50%">
                                                            <?php echo $AppUI->_("LBL_GQM_METRIC"); ?>
                                                        </th>
                                                        <th width="50%">
                                                            <?php echo $AppUI->_("LBL_GQM_HOW_DATA_IS_COLLECTED"); ?>
                                                        </th>
                                                        <th style="width:4%">
                                                            &nbsp;
                                                        </th>
                                                    </tr>

                                                    <?php
                                                    $metrics = $controller->loadMetrics($question_id);
                                                    $k = 0;
                                                    foreach ($metrics as $mid => $mdata) {
                                                        $metric_id = $mdata[0];
                                                        $metric = $mdata[1];
                                                        $how_to_collect = $mdata[2];
                                                        ?>           
                                                        <tr>
                                                            <td style="vertical-align: top">
                                                                <input type="hidden" name="metric_<?php echo $k ?>_qoa_<?php echo $question_id ?>_id" value="<?php echo $metric_id; ?>" />
                                                                <textarea  name="metric_<?php echo $k; ?>_qoa_<?php echo $question_id; ?>" style="width:97%;height:60px;resize: none;" ><?php echo $metric; ?></textarea>
                                                            </td>
                                                            <td style="vertical-align: top">
                                                                <textarea  name="metric_<?php echo $k; ?>_qoa_<?php echo $question_id; ?>_data_collection" style="width:97%;height:60px;resize: none;" ><?php echo $how_to_collect; ?></textarea>
                                                            </td>
                                                            <td style="vertical-align:top">
                                                                <img style="cursor:pointer" src="./modules/dotproject_plus/images/trash_small.gif" onclick="deleteRecord(6,<?php echo $metric_id; ?>)" />
                                                            </td>
                                                        </tr>
                                                        <?php
                                                        $k++;
                                                    }
                                                    ?>
                                                    <input name="number_metrics_<?php echo $question_id; ?>" type="hidden" value="<?php echo $k; ?>"  />

                                                </table> 
                                            </td>
                                            <td style="vertical-align:top">
                                                <img style="cursor:pointer" src="./modules/dotproject_plus/images/trash_small.gif" onclick="deleteRecord(7,<?php echo $question_id ?>)" />
                                            </td>
                                        </tr>
                                        <?php
                                        $j++;
                                    }
                                    ?>
                                    <input name="number_questions_<?php echo $goal_id; ?>" type="hidden" value="<?php echo $j ?>"  />
                                </table>
                            </td>
                            <td style="vertical-align:top">
                                <img style="cursor:pointer" src="./modules/dotproject_plus/images/trash_small.gif" onclick="deleteRecord(8,<?php echo $goal_id ?>)" />
                            </td>
                        </tr>
                    </table>
                    <br />
                    <?php
                    $i++;
                }
                ?>
                <input type="hidden" name="number_goals" value="<?php echo $i ?>">
            </td>
        </tr>
    </table>

    <table width="95%" align="center">
        <tr>
            <td colspan="2" align="right">
                <input type="submit" name="Salvar" value="<?php echo $AppUI->_("LBL_SAVE"); ?>" class="button" />
                <?php require_once (DP_BASE_DIR . "/modules/timeplanning/view/subform_back_button_project.php"); ?>
            </td>
        </tr>
    </table>
</form>




<!-- MODAL POLITICAS DE QUALIDADE -->
<div id="qualityPoliciesModal" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?=$AppUI->_("LBL_QUALITY_POLICIES")?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="<?=$AppUI->_("LBL_CLOSE")?>">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form name="quality_policies_form">
                    <input name="dosql" type="hidden" value="do_project_quality_planning" />
                    <input name="project_id" type="hidden" value="<?=$projectId?>" />
                    <input name="quality_planning_id" type="hidden" value="<?=$quality_planning_id?>" />
                    <input name="form_section" type="hidden" value="quality_policies" />

                    <div class="form-group">
                        <label for="quality_policies">
                            &nbsp;
                        </label>
                        <textarea name="quality_policies" rows="7" class="form-control form-control-sm" ><?php echo $object->getQualityPolicies() ?></textarea>
                    </div>



                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal"><?=$AppUI->_("LBL_CLOSE")?></button>
                <button type="button" class="btn btn-primary btn-sm" onclick="quality.saveQualityPolicy()"><?=$AppUI->_("LBL_SAVE")?></button>
            </div>
        </div>
    </div>
</div>

<!-- MODAL GARANTIA DE QUALIDADE -->
<div id="qualityAssuranceModal" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?=$AppUI->_("LBL_QUALITY_ASSURANCE")?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="<?=$AppUI->_("LBL_CLOSE")?>">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form name="quality_assurance_form">
                    <input name="dosql" type="hidden" value="do_project_quality_planning" />
                    <input name="project_id" type="hidden" value="<?=$projectId?>" />
                    <input name="quality_planning_id" type="hidden" value="<?=$quality_planning_id?>" />
                    <input name="form_section" type="hidden" value="quality_assurance" />
                    <input name="audit_item_id" type="hidden" value="" />

                    <div class="form-group">
                        <label for="quality_policies">
                            <?=$AppUI->_("LBL_WHAT_AUDIT")?>
                        </label>
                        <textarea name="what_audit" rows="2" class="form-control form-control-sm"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="quality_policies">
                            <?=$AppUI->_("LBL_WHO_AUDIT")?>
                        </label>
                        <textarea name="who_audit" rows="2" class="form-control form-control-sm"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="quality_policies">
                            <?=$AppUI->_("LBL_WHEN_AUDIT")?>
                        </label>
                        <textarea name="when_audit" rows="2" class="form-control form-control-sm"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="quality_policies">
                            <?=$AppUI->_("LBL_HOW_AUDIT")?>
                        </label>
                        <textarea name="how_audit" rows="2" class="form-control form-control-sm"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal"><?=$AppUI->_("LBL_CLOSE")?></button>
                <button type="button" class="btn btn-primary btn-sm" onclick="quality.saveQualityAssurance()"><?=$AppUI->_("LBL_SAVE")?></button>
            </div>
        </div>
    </div>
</div>

<script>
    
    var quality = {
        
        init: function () {
            $('#qualityAssuranceModal').on('hidden.bs.modal', function () {
                $('input[name=audit_item_id]').val('');
                $('textarea[name=what_audit]').val('');
                $('textarea[name=who_audit]').val('');
                $('textarea[name=when_audit]').val('');
                $('textarea[name=how_audit]').val('');
            });

        },

        submitForm: function (formName) {
            console.log(formName);
            $.ajax({
                method: 'POST',
                url: "?m=timeplanning",
                data: $("form[name="+formName+"]").serialize(),
                success: function(resposta) {
                    $.alert({
                        title: "Sucesso",
                        content: resposta,
                        onClose: function() {
                            window.location.reload(true);
                        }
                    });
                },
                error: function(resposta) {
                    $.alert({
                        title: "Erro",
                        content: "Algo deu errado"
                    });
                }
            });
        },

        saveQualityPolicy: function () {
            quality.submitForm('quality_policies_form');
        },

        saveQualityAssurance: function () {
            console.log('sjhfjlsfn');
            quality.submitForm('quality_assurance_form');
        },

        deleteQualityAssuranceItem: function (id) {
            $.confirm({
                title: 'Excluir item de controle de qualidade',
                content: 'Você tem certeza de que quer excluir este item?',
                buttons: {
                    yes: {
                        text: 'Sim',
                        action: function () {
                            $.ajax({
                                method: 'POST',
                                url: "?m=timeplanning",
                                data: {
                                    dosql: 'do_project_quality_planning',
                                    del: 1,
                                    audit_item_id: id,
                                    form_section: 'quality_assurance'
                                }
                            }).done(function(resposta) {
                                $.alert({
                                    title: "Sucesso",
                                    content: resposta,
                                    onClose: function() {
                                        window.location.reload(true);
                                    }
                                });
                            });
                        },
                    },
                    no: {
                        text: 'Não'
                    }
                }
            });
        },

        editQualityAssuranceItem: function (id, what, who, when, how) {
            $('input[name=audit_item_id]').val(id);
            $('textarea[name=what_audit]').val(what);
            $('textarea[name=who_audit]').val(who);
            $('textarea[name=when_audit]').val(when);
            $('textarea[name=how_audit]').val(how);

            $('#qualityAssuranceModal').modal();
        }
    };
    
    $(document).ready(quality.init);
</script>
