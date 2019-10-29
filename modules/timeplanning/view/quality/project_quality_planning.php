<?php
require_once (DP_BASE_DIR . "/modules/timeplanning/control/quality/controller_quality_planning.class.php");
$controller = new ControllerQualityPlanning();
$projectId = $_GET["project_id"];
$object = $controller->getQualityPlanningPerProject($projectId);
$quality_planning_id = $object->getId();
?>
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
        <br>
        <div class="row">
            <div class="col-md-12">
                <?=$object->getQualityPolicies()?>
            </div>
        </div>
    </div>
</div>

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
                            <i class="far fa-plus-square"></i>
                            <?=$AppUI->_("LBL_QUALITY_ADD_ITEM_TO_AUDIT")?>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-md-12">
                <?php
                    $assuranceItems = $controller->loadAssuranceItems($quality_planning_id);
                    if (count($assuranceItems)) {
                        ?>
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
                                    </td>
                                </tr>
                                <?php
                            }
                            ?>
                            </tbody>
                        </table>
                        <?php
                    }
                ?>

            </div>
        </div>
    </div>
</div>

<div class="card inner-card">
    <div class="card-body">
        <div class="row">
            <div class="col-md-11">
                <h5 class="card-title"><?=$AppUI->_("LBL_QUALITY_CONTROLLING")?></h5>
                <small><?=$AppUI->_("LBL_QUALITY_CONTROL_DESCRIPTION")?></small>
            </div>
            <div class="col-md-1 text-right">
                <div class="dropdown">
                    <a href="javascript:void(0)" class="link-primary" role="button" data-toggle="dropdown"
                       aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-bars"></i>
                    </a>

                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink">
                        <a class="dropdown-item" href="javascript:void(0)" data-toggle="modal" data-target="#qualityRequirementModal">
                            <i class="far fa-plus-square"></i>
                            <?=$AppUI->_("LBL_QUALITY_ADD_REQUIREMENT")?>
                        </a>
                        <a class="dropdown-item" href="javascript:void(0)" data-toggle="modal" data-target="#qualityGoalModal">
                            <i class="far fa-plus-square"></i>
                            <?=$AppUI->_("LBL_QUALITY_ADD_GOAL")?>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <?php
                    $requirements = $controller->loadControlRequirements($quality_planning_id);
                    if (count($requirements)) {
                        ?>
                        <div class="card inner-card">
                            <div class="card-body">
                                <h5 class="card-title"><?=$AppUI->_("LBL_QUALITY_REQUIREMENTS")?></h5>
                                <br>
                                <div class="row">
                                    <div class="col-md-12">
                                        <table class="table table-sm table-bordered">
                                            <thead class="thead-dark">
                                            <th width="92%">Requisito</th>
                                            <th width="8%"></th>
                                            </thead>
                                            <tbody>
                                            <?php
                                            $i = 0;
                                            foreach ($requirements as $id => $data) {
                                                $req_id = $data[0];
                                                $description = $data[1];
                                                ?>
                                                <tr>
                                                    <td>
                                                        <?php echo $i + 1 ?>. <?=$description?>
                                                    </td>
                                                    <td>
                                                        <button type="button" class="btn btn-xs btn-secondary"
                                                                onclick="quality.editQualityReqItem(<?=$req_id?>, '<?=$description?>')">
                                                            <i class="far fa-edit"></i>
                                                        </button>
                                                        <button type="button" class="btn btn-xs btn-danger"
                                                                onclick="quality.deleteQualityReqItem(<?=$req_id?>)">
                                                            <i class="far fa-trash-alt"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                                <?php
                                                $i++;
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
                ?>
            </div>
        </div>

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

                <div class="row">
                    <div class="col-md-12">
                        <div class="card inner-card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-11">
                                        <h5 class="card-title"><?= $AppUI->_("LBL_GOAL_OF_CONTROL") ?></h5>
                                    </div>
                                    <div class="col-md-1 text-right">
                                        <div class="dropdown">
                                            <a href="javascript:void(0)" class="link-primary" role="button" data-toggle="dropdown"
                                               aria-haspopup="true" aria-expanded="false">
                                                <i class="fas fa-bars"></i>
                                            </a>

                                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink">
                                                <a class="dropdown-item"
                                                   href="javascript:void(0)"
                                                   onclick="quality.editGoal(<?=$goal_id?>, '<?=$gqm_goal_object?>', '<?=$gqm_goal_propose?>', '<?=$gqm_goal_respect_to?>', '<?=$gqm_goal_point_of_view?>', '<?=$gqm_goal_context?>')">
                                                    <i class="far fa-edit"></i>
                                                    Alterar objetivo
                                                </a>
                                                <a class="dropdown-item" href="javascript:void(0)" onclick="quality.deleteGoal(<?=$goal_id?>)">
                                                    <i class="far fa-trash-alt"></i>
                                                    Excluir objetivo
                                                </a>
                                                <a class="dropdown-item" href="javascript:void(0)" onclick="quality.addAnalisysQuestion(<?=$goal_id?>)">
                                                    <i class="far fa-plus-square"></i>
                                                    Adicionar questão de análise
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-md-12">
                                        <span>
                                            <?=$AppUI->_("LBL_GQM_GOAL_OBJECT")?>
                                            <b><?=strtolower($gqm_goal_object)?></b>
                                            <?=$AppUI->_("LBL_GQM_GOAL_PURPOSE")?>
                                            <b><?=strtolower($gqm_goal_propose)?></b>
                                            <?=$AppUI->_("LBL_GQM_GOAL_RESPECT_TO")?>
                                            <b><?=strtolower($gqm_goal_respect_to)?></b>
                                            <?=$AppUI->_("LBL_GQM_GOAL_POINT_OF_VIEW")?>
                                            <b><?=strtolower($gqm_goal_point_of_view)?></b>
                                            <?=$AppUI->_("LBL_GQM_GOAL_CONTEXT")?>
                                            <b><?=strtolower($gqm_goal_context)?></b>
                                        </span>
                                    </div>
                                </div>
                                <?php
                                $questions = $controller->loadQuestions($goal_id);
                                foreach ($questions as $qid => $qdata) {
                                    $question_id = $qdata[0];
                                    $question = $qdata[1];
                                    $target = $qdata[2];
                                    ?>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="card inner-card">
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-md-11">
                                                            <h5 class="card-title"><?= $AppUI->_("LBL_QUESTION_OF_ANALYSIS") ?></h5>
                                                        </div>
                                                        <div class="col-md-1 text-right">
                                                            <div class="dropdown">
                                                                <a href="javascript:void(0)" class="link-primary" role="button" data-toggle="dropdown"
                                                                   aria-haspopup="true" aria-expanded="false">
                                                                    <i class="fas fa-bars"></i>
                                                                </a>

                                                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink">
                                                                    <a class="dropdown-item"
                                                                       href="javascript:void(0)"
                                                                       onclick="quality.editAnalisysQuestion(<?=$goal_id?>, '<?=$question_id?>', '<?=$question?>', '<?=$target?>')">
                                                                        <i class="far fa-edit"></i>
                                                                        Alterar
                                                                    </a>
                                                                    <a class="dropdown-item" href="javascript:void(0)" onclick="quality.deleteAnalisysQuestion(<?=$question_id?>)">
                                                                        <i class="far fa-trash-alt"></i>
                                                                        Excluir
                                                                    </a>
                                                                    <a class="dropdown-item" href="javascript:void(0)" onclick="quality.addMetric(<?=$question_id?>)">
                                                                        <i class="far fa-plus-square"></i>
                                                                        Adicionar métrica
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <br>
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <table width="100%">
                                                                <tr>
                                                                    <th width="20%" class="text-right"><?php echo $AppUI->_("LBL_QUESTION_OF_ANALYSIS"); ?>: </th>
                                                                    <td>&nbsp;<?=$question?></td>
                                                                </tr>
                                                                <tr>
                                                                    <th width="20%" class="text-right"><?php echo $AppUI->_("LBL_GQM_TARGET"); ?>: </th>
                                                                    <td>&nbsp;<?=$target?></td>
                                                                </tr>
                                                            </table>
                                                        </div>
                                                    </div>
                                                    <?php
                                                        $metrics = $controller->loadMetrics($question_id);
                                                        if (count($metrics) > 0) {
                                                        ?>
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="card inner-card">
                                                                    <div class="card-body">
                                                                        <div class="row">
                                                                            <div class="col-md-12">
                                                                                <h5 class="card-title"><?= $AppUI->_("LBL_GQM_METRIC") ?></h5>
                                                                            </div>
                                                                        </div>
                                                                        <div class="row">
                                                                            <div class="col-md-12">

                                                                                <table class="table table-sm table-bordered">
                                                                                    <thead class="thead-dark">
                                                                                    <tr>
                                                                                        <th width="30%"><?php echo $AppUI->_("LBL_GQM_METRIC"); ?></th>
                                                                                        <th width="62%"><?php echo $AppUI->_("LBL_GQM_HOW_DATA_IS_COLLECTED"); ?></th>
                                                                                        <th width="8%"></th>
                                                                                    </tr>
                                                                                    </thead>
                                                                                    <tbody>
                                                                                    <?php

                                                                                    foreach ($metrics as $mid => $mdata) {
                                                                                        $metric_id = $mdata[0];
                                                                                        $metric = $mdata[1];
                                                                                        $how_to_collect = $mdata[2];
                                                                                        ?>
                                                                                        <tr>
                                                                                            <td><?= $metric ?></td>
                                                                                            <td><?= $how_to_collect ?></td>
                                                                                            <td>
                                                                                                <button type="button"
                                                                                                        class="btn btn-xs btn-secondary"
                                                                                                        onclick="quality.editMetric(<?= $question_id ?>, <?= $metric_id ?>, '<?= $metric ?>', '<?= $how_to_collect ?>')">
                                                                                                    <i class="far fa-edit"></i>
                                                                                                </button>
                                                                                                <button type="button"
                                                                                                        class="btn btn-xs btn-danger"
                                                                                                        onclick="quality.deleteMetric(<?= $metric_id ?>)">
                                                                                                    <i class="far fa-trash-alt"></i>
                                                                                                </button>
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
                                                            </div>
                                                        </div>
                                                        <?php
                                                        }
                                                    ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
            }
        ?>
    </div>
</div>

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

<!-- MODAL POLITICAS DE QUALIDADE -->
<div id="qualityRequirementModal" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Adicionar requisito de qualidade</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="<?=$AppUI->_("LBL_CLOSE")?>">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form name="quality_requirement_form">
                    <input name="dosql" type="hidden" value="do_project_quality_planning" />
                    <input name="project_id" type="hidden" value="<?=$projectId?>" />
                    <input name="quality_planning_id" type="hidden" value="<?=$quality_planning_id?>" />
                    <input name="form_section" type="hidden" value="quality_requirement" />
                    <input name="requirement_item_id" type="hidden" value="" />

                    <div class="form-group">
                        <label for="quality_requirement">
                            &nbsp;
                        </label>
                        <input type="text" name="requirement_item_description" class="form-control form-control-sm" />
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal"><?=$AppUI->_("LBL_CLOSE")?></button>
                <button type="button" class="btn btn-primary btn-sm" onclick="quality.saveQualityRequirement()"><?=$AppUI->_("LBL_SAVE")?></button>
            </div>
        </div>
    </div>
</div>

<!-- MODAL OBJETIVO DE QUALIDADE -->
<div id="qualityGoalModal" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Adicionar objetivo</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="<?=$AppUI->_("LBL_CLOSE")?>">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form name="quality_goal_form">
                    <input name="dosql" type="hidden" value="do_project_quality_planning" />
                    <input name="project_id" type="hidden" value="<?=$projectId?>" />
                    <input name="quality_planning_id" type="hidden" value="<?=$quality_planning_id?>" />
                    <input name="form_section" type="hidden" value="quality_goal" />
                    <input name="goal_item_id" type="hidden" value="" />

                    <div class="form-group">
                        <label for="LBL_GQM_GOAL_OBJECT">
                            <?=$AppUI->_("LBL_GQM_GOAL_OBJECT")?>
                        </label>
                        <input type="text" name="goal_object" class="form-control form-control-sm" />
                    </div>
                    <div class="form-group">
                        <label for="LBL_GQM_GOAL_PURPOSE">
                            <?=$AppUI->_("LBL_GQM_GOAL_PURPOSE")?>
                        </label>
                        <input type="text" name="goal_purpose" class="form-control form-control-sm" />
                    </div>
                    <div class="form-group">
                        <label for="LBL_GQM_GOAL_RESPECT_TO">
                            <?=$AppUI->_("LBL_GQM_GOAL_RESPECT_TO")?>
                        </label>
                        <input type="text" name="goal_respect_to" class="form-control form-control-sm" />
                    </div>
                    <div class="form-group">
                        <label for="LBL_GQM_GOAL_POINT_OF_VIEW">
                            <?=$AppUI->_("LBL_GQM_GOAL_POINT_OF_VIEW")?>
                        </label>
                        <input type="text" name="goal_poit_view" class="form-control form-control-sm" />
                    </div>
                    <div class="form-group">
                        <label for="LBL_GQM_GOAL_CONTEXT">
                            <?=$AppUI->_("LBL_GQM_GOAL_CONTEXT")?>
                        </label>
                        <input type="text" name="goal_context" class="form-control form-control-sm" />
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal"><?=$AppUI->_("LBL_CLOSE")?></button>
                <button type="button" class="btn btn-primary btn-sm" onclick="quality.saveGoal()"><?=$AppUI->_("LBL_SAVE")?></button>
            </div>
        </div>
    </div>
</div>

<!-- MODAL QUESTÃO DE ANÁLISE -->
<div id="qualityAnalisysQuestionModal" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Adicionar questão de análise</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="<?=$AppUI->_("LBL_CLOSE")?>">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form name="quality_analisys_question_form">
                    <input name="dosql" type="hidden" value="do_project_quality_planning" />
                    <input name="project_id" type="hidden" value="<?=$projectId?>" />
                    <input name="quality_planning_id" type="hidden" value="<?=$quality_planning_id?>" />
                    <input name="form_section" type="hidden" value="quality_analisys_question" />
                    <input name="analisys_question_item_id" type="hidden" value="" />
                    <input name="goal_item_id" type="hidden" value="" />

                    <div class="form-group">
                        <label for="analisys_question">
                            <?=$AppUI->_("LBL_QUESTION_OF_ANALYSIS")?>
                        </label>
                        <input type="text" name="analisys_question" class="form-control form-control-sm" />
                    </div>
                    <div class="form-group">
                        <label for="analisys_question_target">
                            <?=$AppUI->_("LBL_GQM_TARGET")?>
                        </label>
                        <input type="text" name="analisys_question_target" class="form-control form-control-sm" />
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal"><?=$AppUI->_("LBL_CLOSE")?></button>
                <button type="button" class="btn btn-primary btn-sm" onclick="quality.saveAnalisysQuestion()"><?=$AppUI->_("LBL_SAVE")?></button>
            </div>
        </div>
    </div>
</div>

<!-- MODAL MÉTRICA -->
<div id="metricModal" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Adicionar questão de análise</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="<?=$AppUI->_("LBL_CLOSE")?>">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form name="metric_form">
                    <input name="dosql" type="hidden" value="do_project_quality_planning" />
                    <input name="project_id" type="hidden" value="<?=$projectId?>" />
                    <input name="quality_planning_id" type="hidden" value="<?=$quality_planning_id?>" />
                    <input name="form_section" type="hidden" value="quality_metric" />
                    <input name="analisys_question_item_id" type="hidden" value="" />
                    <input name="metric_id" type="hidden" value="" />

                    <div class="form-group">
                        <label for="metric">
                            <?=$AppUI->_("LBL_GQM_METRIC")?>
                        </label>
                        <input type="text" name="metric" class="form-control form-control-sm" />
                    </div>
                    <div class="form-group">
                        <label for="how_to_collect">
                            <?=$AppUI->_("LBL_GQM_HOW_DATA_IS_COLLECTED")?>
                        </label>
                        <input type="text" name="how_to_collect" class="form-control form-control-sm" />
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal"><?=$AppUI->_("LBL_CLOSE")?></button>
                <button type="button" class="btn btn-primary btn-sm" onclick="quality.saveMetric()"><?=$AppUI->_("LBL_SAVE")?></button>
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

            $('#qualityRequirementModal').on('hidden.bs.modal', function () {
                $('input[name=requirement_item_id]').val('');
                $('input[name=requirement_item_description]').val('');
                var modal = $('#qualityRequirementModal');
                modal.find('h5').html('Adicionar requisito de qualidade');
            });

            $('#qualityGoalModal').on('hidden.bs.modal', function () {
                $('input[name=goal_item_id]').val('');
                $('input[name=goal_object]').val('');
                $('input[name=goal_purpose]').val('');
                $('input[name=goal_respect_to]').val('');
                $('input[name=goal_poit_view]').val('');
                $('input[name=goal_context]').val('');
                var modal = $('#qualityGoalModal');
                modal.find('h5').html('Adicionar objetivo');
            });

            $('#qualityAnalisysQuestionModal').on('hidden.bs.modal', function () {
                $('input[name=goal_item_id]').val('');
                $('input[name=analisys_question_item_id]').val('');
                $('input[name=analisys_question]').val('');
                $('input[name=analisys_question_target]').val('');
                var modal = $('#qualityAnalisysQuestionModal');
                modal.find('f5').html('Adicionar questão de análise');
            });

            $('#metricModal').on('hidden.bs.modal', function () {
                $('input[name=analisys_question_item_id]').val('');
                $('input[name=metric_id]').val('');
                $('input[name=metric]').val('');
                $('input[name=how_to_collect]').val('');
                var modal = $('#metricModal');
                modal.find('f5').html('Adicionar métrica');
            });
        },

        submitForm: function (formName) {
            $.ajax({
                method: 'POST',
                url: "?m=timeplanning",
                data: $("form[name="+formName+"]").serialize(),
                success: function(resposta) {
                    var resp = JSON.parse(resposta);
                    if (resp.err) {
                        $.alert({
                            icon: "far fa-times-circle",
                            type: "red",
                            title: "Erro",
                            content: resp.msg
                        });
                    } else {
                        $.alert({
                            icon: "far fa-check-circle",
                            type: "green",
                            title: "Sucesso",
                            content: resp.msg,
                            onClose: function() {
                                window.location.reload(true);
                            }
                        });
                    }
                },
                error: function(resposta) {
                    $.alert({
                        icon: "far fa-times-circle",
                        type: "red",
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
            if (!$('textarea[name=what_audit]').val().trim() &&
                !$('textarea[name=who_audit]').val().trim() &&
                !$('textarea[name=when_audit]').val().trim()&&
                !$('textarea[name=how_audit]').val().trim()) {
                return;
            }
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
                                var resp = JSON.parse(resposta);
                                if (!resp.err) {
                                    $.alert({
                                        icon: "far fa-check-circle",
                                        type: "green",
                                        title: "Sucesso",
                                        content: resp.msg,
                                        onClose: function() {
                                            window.location.reload(true);
                                        }
                                    });
                                } else {
                                    $.alert({
                                        icon: "far fa-times-circle",
                                        type: "red",
                                        title: "Erro",
                                        content: resp.msg
                                    });
                                }

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
        },

        editQualityReqItem: function (id, description) {
            $('input[name=requirement_item_id]').val(id);
            $('input[name=requirement_item_description]').val(description);
            var modal = $('#qualityRequirementModal');
            modal.find('h5').html('Alterar requisito de qualidade');
            modal.modal();
        },

        deleteQualityReqItem: function (id) {
            $.confirm({
                title: 'Excluir item de requisito de qualidade',
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
                                    requirement_item_id: id,
                                    form_section: 'quality_requirement'
                                }
                            }).done(function(resposta) {
                                var resp = JSON.parse(resposta);
                                if (!resp.err) {
                                    $.alert({
                                        icon: "far fa-check-circle",
                                        type: "green",
                                        title: "Sucesso",
                                        content: resp.msg,
                                        onClose: function() {
                                            window.location.reload(true);
                                        }
                                    });
                                } else {
                                    $.alert({
                                        icon: "far fa-times-circle",
                                        type: "red",
                                        title: "Erro",
                                        content: resp.msg
                                    });
                                }
                            });
                        },
                    },
                    no: {
                        text: 'Não'
                    }
                }
            });
        },

        saveQualityRequirement: function () {
            if (!$('input[name=requirement_item_description]').val().trim()) {
                return;
            }
            quality.submitForm('quality_requirement_form');
        },

        deleteGoal: function (id) {
            $.confirm({
                title: 'Excluir objetivo de qualidade',
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
                                    goal_item_id: id,
                                    form_section: 'quality_goal'
                                }
                            }).done(function(resposta) {
                                var resp = JSON.parse(resposta);
                                if (!resp.err) {
                                    $.alert({
                                        icon: "far fa-check-circle",
                                        type: "green",
                                        title: "Sucesso",
                                        content: resp.msg,
                                        onClose: function() {
                                            window.location.reload(true);
                                        }
                                    });
                                } else {
                                    $.alert({
                                        icon: "far fa-times-circle",
                                        type: "red",
                                        title: "Erro",
                                        content: resp.msg
                                    });
                                }
                            });
                        },
                    },
                    no: {
                        text: 'Não'
                    }
                }
            });
        },

        editGoal: function (id, object, purpose, respectTo, pointView, context) {
            $('input[name=goal_item_id]').val(id);
            $('input[name=goal_object]').val(object);
            $('input[name=goal_purpose]').val(purpose);
            $('input[name=goal_respect_to]').val(respectTo);
            $('input[name=goal_poit_view]').val(pointView);
            $('input[name=goal_context]').val(context);
            var modal = $('#qualityGoalModal');
            modal.find('h5').html('Alterar objetivo');
            modal.modal();
        },

        saveGoal: function () {
            if (!$('input[name=goal_object]').val().trim() &&
                !$('input[name=goal_purpose]').val().trim() &&
                !$('input[name=goal_respect_to]').val().trim()&&
                !$('input[name=goal_poit_view]').val().trim()&&
                !$('input[name=goal_context]').val().trim()) {
                return;
            }
            quality.submitForm('quality_goal_form');
        },

        addAnalisysQuestion: function (goalId) {
            $('input[name=goal_item_id]').val(goalId);
            $('#qualityAnalisysQuestionModal').modal();
        },

        saveAnalisysQuestion: function () {
            if (!$('input[name=analisys_question]').val().trim() &&
                !$('input[name=analisys_question_target]').val().trim()) {
                return;
            }
            quality.submitForm('quality_analisys_question_form');
        },

        deleteAnalisysQuestion: function (id) {
            $.confirm({
                title: 'Excluir questão de análise',
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
                                    analisys_question_item_id: id,
                                    form_section: 'quality_analisys_question'
                                }
                            }).done(function(resposta) {
                                var resp = JSON.parse(resposta);
                                if (!resp.err) {
                                    $.alert({
                                        icon: "far fa-check-circle",
                                        type: "green",
                                        title: "Sucesso",
                                        content: resp.msg,
                                        onClose: function() {
                                            window.location.reload(true);
                                        }
                                    });
                                } else {
                                    $.alert({
                                        icon: "far fa-times-circle",
                                        type: "red",
                                        title: "Erro",
                                        content: resp.msg
                                    });
                                }
                            });
                        },
                    },
                    no: {
                        text: 'Não'
                    }
                }
            });
        },
        
        editAnalisysQuestion: function (goalId, id, question, target) {
            $('input[name=goal_item_id]').val(goalId);
            $('input[name=analisys_question_item_id]').val(id);
            $('input[name=analisys_question]').val(question);
            $('input[name=analisys_question_target]').val(target);
            var modal = $('#qualityAnalisysQuestionModal');
            modal.find('f5').html('Alterar questão de análise');
            modal.modal();
        },

        deleteMetric: function (id) {
            $.confirm({
                title: 'Excluir métrica',
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
                                    metric_id: id,
                                    form_section: 'quality_metric'
                                }
                            }).done(function (resposta) {
                                var resp = JSON.parse(resposta);
                                if (!resp.err) {
                                    $.alert({
                                        icon: "far fa-check-circle",
                                        type: "green",
                                        title: "Sucesso",
                                        content: resp.msg,
                                        onClose: function() {
                                            window.location.reload(true);
                                        }
                                    });
                                } else {
                                    $.alert({
                                        icon: "far fa-times-circle",
                                        type: "red",
                                        title: "Erro",
                                        content: resp.msg
                                    });
                                }
                            });
                        },
                    },
                    no: {
                        text: 'Não'
                    }
                }
            });
        },

        editMetric: function (analisysId, id, metric, howToCollect) {
            $('input[name=analisys_question_item_id]').val(analisysId);
            $('input[name=metric_id]').val(id);
            $('input[name=metric]').val(metric);
            $('input[name=how_to_collect]').val(howToCollect);
            var modal = $('#metricModal');
            modal.find('f5').html('Alterar métrica');
            modal.modal();
        },

        addMetric: function (analisysId) {
            $('input[name=analisys_question_item_id]').val(analisysId);
            var modal = $('#metricModal');
            modal.find('f5').html('Adicionar métrica');
            modal.modal();
        },

        saveMetric: function () {
            if (!$('input[name=metric]').val().trim() &&
                !$('input[name=how_to_collect]').val().trim()) {
                return;
            }
            quality.submitForm('metric_form');
        }
        
    };
    
    $(document).ready(quality.init);
</script>
