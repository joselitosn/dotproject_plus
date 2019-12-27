<?php
//header("Content-Type: text/html; charset=utf-8",true);
//header('Content-Type: application/vnd.ms-word');
require_once DP_BASE_DIR . "/modules/instructor_admin/PHPWord_0.6.2_Beta/PHPWord.php";
require_once DP_BASE_DIR . "/modules/dotproject_plus/feedback/feedback_controller.php";
require_once DP_BASE_DIR."/modules/human_resources/configuration_functions.php";
require_once DP_BASE_DIR . "/modules/instructor_admin/assessment_assistent.php";


$user_id=$_GET["user_id"];
$project=new CProject();
$project->load($_GET["project_id"]);
// New Word Document
$PHPWord = new PHPWord();
// New portrait section
$section = $PHPWord->createSection();

$assessmentAssistant = new AssessmentAssistent($_GET["project_id"]);
$document = $PHPWord->loadTemplate(DP_BASE_DIR . "/modules/instructor_admin/template_avaliacao.docx"); //deve ser salvo mantendo compatibilidade com versões anteriores
//integrantes dos grupos
$res = getDetailedUsersByCompanyId($project->project_company);
$members="";
for ($res; !$res->EOF; $res->MoveNext()) {
    $members.= $res->fields["contact_first_name"]. " ". $res->fields["contact_last_name"] . "; ";
}
$section->addText("Integrantes do grupo: ". utf8_decode($members));


$section->addText(utf8_decode("A avaliação será realizada da seguinte maneira: 0.7 trabalho escrito + 0.3 apresentação."));
$section->addTextBreak(1);
// Add table
$table = $section->addTable();
//Termo de abertura do projeto
$table->addRow();
$table->addCell(2500)->addText("Termo de abertura do projeto");
$table->addCell(500)->addText("2pt");
$table->addCell(500)->addText("");
$_SESSION["cell"]=$table->addCell(6000);
//$table->addCell(6000)->addText($feedbackManager->getEvaluationMessagesPerKA($AppUI->_("LBL_FEEDBACK_INTEGRATION")));
$feedbackManager->getEvaluationMessagesPerKA($AppUI->_("LBL_FEEDBACK_INTEGRATION"));
$messages=$assessmentAssistant->getIntegrationMessages();
$assessmentAssistant->formatMessagesForWord($messages);
//Planejamento do escopo
$table->addRow();
$table->addCell(2500)->addText("Planejamento do escopo");
$table->addCell(500)->addText("1pt");
$table->addCell(500)->addText("");
$_SESSION["cell"]=$table->addCell(6000);
//$table->addCell(6000)->addText($feedbackManager->getEvaluationMessagesPerKA($AppUI->_("LBL_FEEDBACK_SCOPE")));
$feedbackManager->getEvaluationMessagesPerKA($AppUI->_("LBL_FEEDBACK_SCOPE"));
$messages=$assessmentAssistant->getScopeMessages();
$assessmentAssistant->formatMessagesForWord($messages);
//Planejamento de tempo 
$table->addRow();
$table->addCell(2500)->addText("Planejamento de tempo");
$table->addCell(500)->addText("2pt");
$table->addCell(500)->addText("");
$_SESSION["cell"]=$table->addCell(6000);
//$table->addCell(6000)->addText($feedbackManager->getEvaluationMessagesPerKA($AppUI->_("LBL_FEEDBACK_TIME")));
$feedbackManager->getEvaluationMessagesPerKA($AppUI->_("LBL_FEEDBACK_TIME"));	
$messages=$assessmentAssistant->getTimeMessages();
$assessmentAssistant->formatMessagesForWord($messages);
//Planejamento  de custo 
$table->addRow();
$table->addCell(2500)->addText("Planejamento de custo");
$table->addCell(500)->addText("1pt");
$table->addCell(500)->addText("");
$_SESSION["cell"]=$table->addCell(6000);
//$table->addCell(6000)->addText($feedbackManager->getEvaluationMessagesPerKA($AppUI->_("LBL_FEEDBACK_COST")));	
$feedbackManager->getEvaluationMessagesPerKA($AppUI->_("LBL_FEEDBACK_COST"));
$messages=$assessmentAssistant->getCostMessages();
$assessmentAssistant->formatMessagesForWord($messages);


//Planejamento de qualidade 
$table->addRow();
$table->addCell(2500)->addText("Planejamento da qualidade");
$table->addCell(500)->addText("1pt");
$table->addCell(500)->addText("");
$_SESSION["cell"]=$table->addCell(6000);
//$table->addCell(6000)->addText($feedbackManager->getEvaluationMessagesPerKA($AppUI->_("LBL_FEEDBACK_QUALITY")));
$feedbackManager->getEvaluationMessagesPerKA($AppUI->_("LBL_FEEDBACK_QUALITY"));
$messages=$assessmentAssistant->getQualityMessages();
$assessmentAssistant->formatMessagesForWord($messages);

//Planejamento de RH e comunicações
$table->addRow();
$table->addCell(2500)->addText(utf8_decode("Planejamento de RH e comunicação"));
$table->addCell(500)->addText("1pt");
$table->addCell(500)->addText("");
$_SESSION["cell"]=$table->addCell(6000);
//$table->addCell(6000)->addText($feedbackManager->getEvaluationMessagesPerKA($AppUI->_("LBL_FEEDBACK_HR")) . "; " . $feedbackManager->getEvaluationMessagesPerKA($AppUI->_("LBL_FEEDBACK_COMUNICATION")));	
$feedbackManager->getEvaluationMessagesPerKA($AppUI->_("LBL_FEEDBACK_HR"));
$messages=$assessmentAssistant->getRHMessages();
$assessmentAssistant->formatMessagesForWord($messages);

$feedbackManager->getEvaluationMessagesPerKA($AppUI->_("LBL_FEEDBACK_COMUNICATION"));
$messages=$assessmentAssistant->getCommunicationMessages();
$assessmentAssistant->formatMessagesForWord($messages);

//Planejamento de aquisições 
$table->addRow();
$table->addCell(2500)->addText(utf8_decode("Planejamento de aquisições"));
$table->addCell(500)->addText("0.5pt");
$table->addCell(500)->addText("");
$_SESSION["cell"]=$table->addCell(6000);
//$table->addCell(6000)->addText($feedbackManager->getEvaluationMessagesPerKA($AppUI->_("LBL_FEEDBACK_ACQUISITIONS")));	
$feedbackManager->getEvaluationMessagesPerKA($AppUI->_("LBL_FEEDBACK_ACQUISITIONS"));
$messages=$assessmentAssistant->getProcurementMessages();
$assessmentAssistant->formatMessagesForWord($messages);



//Planejamento de riscos 
$table->addRow();
$table->addCell(2500)->addText("Planejamento de riscos");
$table->addCell(500)->addText("1pt");
$table->addCell(500)->addText("");
$_SESSION["cell"]=$table->addCell(6000);
//$table->addCell(6000)->addText($feedbackManager->getEvaluationMessagesPerKA($AppUI->_("LBL_FEEDBACK_RISK")));
$feedbackManager->getEvaluationMessagesPerKA($AppUI->_("LBL_FEEDBACK_RISK"));
//Planejamento de stakeholders
$table->addRow();
$table->addCell(2500)->addText("Planejamento de stakeholders");
$table->addCell(500)->addText("0.5pt");
$table->addCell(500)->addText("");
$_SESSION["cell"]=$table->addCell(6000);
//$table->addCell(6000)->addText($feedbackManager->getEvaluationMessagesPerKA($AppUI->_("Stakeholder")));	
$feedbackManager->getEvaluationMessagesPerKA($AppUI->_("Stakeholder"));
//nota do trabalho escrito
$section->addText("Nota do trabalho escrito: ",array('bold'=>true));

$section->addText(utf8_decode("Avaliação da apresentação"),array('bold'=>true));
// Add table
$table = $section->addTable();

$table->addRow();
$table->addCell(2500)->addText(utf8_decode("Preparação"));
$table->addCell(500)->addText("2pt");
$table->addCell(5500)->addText("");

$table->addRow();
$table->addCell(2500)->addText(utf8_decode("Organização"));
$table->addCell(500)->addText("2pt");
$table->addCell(5500)->addText("");

$table->addRow();
$table->addCell(2500)->addText(utf8_decode("Uso de linguagem"));
$table->addCell(500)->addText("2pt");
$table->addCell(5500)->addText("");

$table->addRow();
$table->addCell(2500)->addText(utf8_decode("Perguntas"));
$table->addCell(500)->addText("2pt");
$table->addCell(5500)->addText("");

$table->addRow();
$table->addCell(2500)->addText(utf8_decode("Duração da apresentação"));
$table->addCell(500)->addText("2pt");
$table->addCell(6500)->addText("");

//nota da apresentação
$section->addText(utf8_decode("Nota da apresentação: "),array('bold'=>true));
//nota geral
$section->addText("Nota do trabalho A1: ",array('bold'=>true));

// Save File
$fileName="modules/timeplanning/view/export_project_plan/temp/avaliacao_grupo_". $user_id .".docx";
unlink ($fileName);
$objWriter = PHPWord_IOFactory::createWriter($PHPWord, 'Word2007');
$objWriter->save($fileName);
header("location:".$fileName);
?>
