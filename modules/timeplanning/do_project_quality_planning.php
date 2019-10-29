<?php
require_once (DP_BASE_DIR . "/modules/timeplanning/control/quality/controller_quality_planning.class.php");
$response = array('err'=>false, 'msg'=>'');
try {
    $projectId = dPgetParam($_POST, "project_id");

    $section = $_POST["form_section"];
    $controller  = new ControllerQualityPlanning ();
    $id= dPgetParam($_POST,"quality_planning_id");
    if (null === $id) {
        $id = -1;
    }
     if ($section != 'quality_policies' && $id == -1 && $_POST['del'] != 1) {
         $response['err'] = true;
         $response['msg'] = 'É necessário registrar a "Necessidade de conformidade em relação às normas, políticas, e diretrizes de qualidade" antes de qualquer outro item.';
     } else {
         switch ($section) {
             case 'quality_policies':
                 $qualityPolicies = dPgetParam($_POST,"quality_policies");
                 $controller->storeQualityPolicies($id, $projectId, $qualityPolicies);
                 $response['msg'] = 'Política de qualidade registrada';
                 break;

             case 'quality_assurance':
                 $auditId = $_POST["audit_item_id"];
                 if ($_POST['del'] == 1) {
                     $controller->deleteAssuranceItem($auditId);
                     $response['msg'] = 'Item de garantia de qualidade excluído';
                 } else {
                     $what = $_POST["what_audit"];
                     $who = $_POST["who_audit"];
                     $when = $_POST["when_audit"];
                     $how = $_POST["how_audit"];
                     $controller->saveAssuranceItem($id, $what, $who, $when, $how, $auditId);
                     $response['msg'] = 'Item de garantia de qualidade registrado';
                 }
                 break;

             case 'quality_requirement':
                 $reqId = $_POST["requirement_item_id"];
                 if ($_POST['del'] == 1) {
                     $controller->deleteControlRequirement($reqId);
                     $response['msg'] = 'Item de requisito de qualidade excluído';
                 } else {
                     $description = $_POST["requirement_item_description"];
                     $controller->saveControlRequirement($id, $description, $reqId);
                     $response['msg'] = 'Item de requisito de qualidade registrado';
                 }
                 break;

             case 'quality_goal':
                 $goalId = $_POST["goal_item_id"];
                 if ($_POST['del'] == 1) {
                     $controller->deleteControlGoal($goalId);
                     $response['msg'] = 'Objetivo de qualidade excluído';
                 } else {
                     $gqm_goal_object = $_POST["goal_object"];
                     $gqm_goal_propose = $_POST["goal_purpose"];
                     $gqm_goal_respect_to = $_POST["goal_respect_to"];
                     $gqm_goal_point_of_view = $_POST["goal_poit_view"];
                     $gqm_goal_context = $_POST["goal_context"];
                     $controller->saveControlGoal($id, $gqm_goal_object, $gqm_goal_propose, $gqm_goal_respect_to, $gqm_goal_point_of_view, $gqm_goal_context, $goalId);
                     $response['msg'] = 'Objetivo de qualidade registrado';
                 }
                 break;

             case 'quality_analisys_question':
                 $analisysId = $_POST["analisys_question_item_id"];
                 if ($_POST['del'] == 1) {
                     $controller->deleteQuestion($analisysId);
                     $response['msg'] = 'Questão de análise excluída';
                 } else {
                     $goalId = $_POST["goal_item_id"];
                     $question = $_POST["analisys_question"];
                     $target = $_POST["analisys_question_target"];
                     $controller->saveQuestion($goalId, $question, $target, $analisysId);
                     $response['msg'] = 'Questão de análise registrada';
                 }
                 break;

             case 'quality_metric':
                 $metricId = $_POST["metric_id"];
                 if ($_POST['del'] == 1) {
                     $controller->deleteMetric($metricId);
                     $response['msg'] = 'Métrica excluída';
                 } else {
                     $questionId = $_POST["analisys_question_item_id"];
                     $metric = $_POST["metric"];
                     $howToCollect = $_POST["how_to_collect"];
                     $controller->saveMetric($questionId, $metric, $howToCollect, $metricId);
                     $response['msg'] = 'Métrica registrada';
                 }
                 break;
         }
     }
    echo json_encode($response);
} catch (Exception $e) {
    $response['err'] = true;
    $response['msg'] = 'Ocorreu um erro desconhecido';
    echo json_encode($response);
}

exit();
