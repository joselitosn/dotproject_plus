<?php
if (!defined('DP_BASE_DIR')) {
  die('You should not access this file directly.');
}
require_once (DP_BASE_DIR . "/modules/monitoringandcontrol/control/controller_baseline.class.php");
require_once (DP_BASE_DIR . "/modules/monitoringandcontrol/control/controller_util.class.php");
$acao = new ControllerBaseline();
$controllerUtil = new ControllerUtil();
global $AppUI;

	
$project_id = dPgetParam($_POST,'project_id');
$idBaseline = dPgetParam($_POST,'idBaseline');
$nmBaseline = dPgetParam($_POST,'nmBaseline');
$nmVersao = dPgetParam($_POST,'nmVersao');
$dsObservacao = dPgetParam($_POST,'dsObservacao');
$user_id = dPgetParam($_POST,'user');
$dateTime = dPgetParam($_POST,'dateTime');

$response = array('err' => false);

try {
    if (isset($_POST['acao']) && $_POST['acao']=='insert'){
        $dtAtual = date("Y-m-d h:i:s");
        $id = $acao->insertRow($project_id,$nmBaseline,$nmVersao,$dsObservacao,$user_id, $dtAtual);
        $response['msg'] = 'Baseline cadastrada';
        $response['newBaselineId'] = $id;
        $response['dateTime'] = $controllerUtil->formatDateTime($dtAtual);
    } elseif (isset($_POST['acao']) &&  $_POST['acao'] == 'delete'){
        $acao->deleteRow($idBaseline);
        $response['msg'] = 'Baseline excluída';
    } elseif (isset($_POST['acao']) &&  $_POST['acao'] == 'update'){
        $id = $acao->updateRow($idBaseline,$nmBaseline,$nmVersao,$dsObservacao);
        $response['msg'] = 'Baseline alterada';
        $response['newBaselineId'] = $id;
        $response['dateTime'] = $controllerUtil->formatDateTime($dateTime);
    }
} catch (Exception $e) {
    $response['err'] = true;
    $response['msg'] = $e->getMessage();
}
echo json_encode($response);
exit();
?>