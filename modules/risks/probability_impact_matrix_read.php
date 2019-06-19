<?php
require_once DP_BASE_DIR . "/modules/risks/risks_management_plan.class.php";
$riskManagementPlan = new CRisksManagementPlan();
$riskManagementPlan->load($project_id);
//recovery the probability and impact matrix from the risk management plan (auxiliar record)
$impactProbabilityMatrix = array();
$impactProbabilityMatrix[0][0] = $riskManagementPlan->matrix_superlow_superlow;
$impactProbabilityMatrix[0][1] = $riskManagementPlan->matrix_low_superlow;
$impactProbabilityMatrix[0][2] = $riskManagementPlan->matrix_medium_superlow;
$impactProbabilityMatrix[0][3] = $riskManagementPlan->matrix_high_superlow;
$impactProbabilityMatrix[0][4] = $riskManagementPlan->matrix_superhigh_superlow;

$impactProbabilityMatrix[1][0] = $riskManagementPlan->matrix_superlow_low;
$impactProbabilityMatrix[1][1] = $riskManagementPlan->matrix_low_low;
$impactProbabilityMatrix[1][2] = $riskManagementPlan->matrix_medium_low;
$impactProbabilityMatrix[1][3] = $riskManagementPlan->matrix_high_low;
$impactProbabilityMatrix[1][4] = $riskManagementPlan->matrix_superhigh_low;

$impactProbabilityMatrix[2][0] = $riskManagementPlan->matrix_superlow_medium;
$impactProbabilityMatrix[2][1] = $riskManagementPlan->matrix_low_medium;
$impactProbabilityMatrix[2][2] = $riskManagementPlan->matrix_medium_medium;
$impactProbabilityMatrix[2][3] = $riskManagementPlan->matrix_high_medium;
$impactProbabilityMatrix[2][4] = $riskManagementPlan->matrix_superhigh_medium;

$impactProbabilityMatrix[3][0] = $riskManagementPlan->matrix_superlow_high;
$impactProbabilityMatrix[3][1] = $riskManagementPlan->matrix_low_high;
$impactProbabilityMatrix[3][2] = $riskManagementPlan->matrix_medium_high;
$impactProbabilityMatrix[3][3] = $riskManagementPlan->matrix_high_high;
$impactProbabilityMatrix[3][4] = $riskManagementPlan->matrix_superhigh_high;

$impactProbabilityMatrix[4][0] = $riskManagementPlan->matrix_superlow_superhigh;
$impactProbabilityMatrix[4][1] = $riskManagementPlan->matrix_low_superhigh;
$impactProbabilityMatrix[4][2] = $riskManagementPlan->matrix_medium_superhigh;
$impactProbabilityMatrix[4][3] = $riskManagementPlan->matrix_high_superhigh;
$impactProbabilityMatrix[4][4] = $riskManagementPlan->matrix_superhigh_superhigh;

$textExpositionFactor = array();
$textExpositionFactor[0] = "<span style=\"color:#006400\">" . $AppUI->_("LBL_LOW_F") . "</span>";
$textExpositionFactor[1] = "<span style=\"color:#B8860B\">" . $AppUI->_("LBL_MEDIUM_F") . "</span>";
$textExpositionFactor[2] = "<span style=\"color:#FF0000\">" . $AppUI->_("LBL_HIGH_F") . "</span>";
?>