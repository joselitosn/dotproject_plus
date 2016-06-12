<?php
// chama a classe 'class.ezpdf.php' necess�ria para se gerar o documento
//include "lib/ezpdf/class.ezpdf.php"; 
$font_dir = DP_BASE_DIR.'/lib/ezpdf/fonts';
require($AppUI->getLibraryClass('ezpdf/class.ezpdf'));
$id=intval(dPgetParam($_GET, 'id', 0));
$q = new DBQuery();
$q->addQuery('*');
$q->addTable('initiating');
$q->addWhere('initiating_id = ' . $id);
$obj = new CInitiating(); 
// load the record data
$obj = null;
if (!db_loadObject($q->prepare(), $obj) && $id > 0) {
	$AppUI->setMsg('Initiating');
	$AppUI->setMsg("invalidID", UI_MSG_ERROR, true);
	$AppUI->redirect();
}
$q = new DBQuery();
$q->addQuery("*");
$q->addTable("contacts","con");
$q->addJoin("users", "u", "u.user_contact=con.contact_id");
$q->addWhere("user_id = " . $obj->initiating_manager);
$contact = $q->loadHash();

// instancia um novo documento com o nome de pdf
$pdf = new Cezpdf();

// seta a fonte que será usada para apresentar os dados
//essas fontes são aquelas dentro do diret�rio GeraPDF/fonts
//$pdf->selectFont('lib/ezpdf/Helvetica.afm'); 
$pdf->selectFont("$font_dir/Helvetica.afm");

// chama o m�todo ezText e passa o texto que dever� ser apresentado no documento
//o numero ap�s o texto se refere ao tamanho da fonte
$pdf->ezText("\n");
$pdf->ezText("<b>".$AppUI->_("LBL_PROJECT_CHARTER")."</b>",18,array('justification'=>'center')); 
$pdf->ezText('');
$pdf->ezText('');
$pdf->ezText(utf8_decode("<b>".$AppUI->_("Project Title",UI_OUTPUT_HTML).": </b>" . $obj->initiating_title),10);
$pdf->ezText('');
$pdf->ezText(utf8_decode("<b>".$AppUI->_("LBL_PROJECT_PROJECT_MANAGER").": </b>" . $contact['contact_first_name'] . " " .  $contact['contact_last_name']),10);
$pdf->ezText('');
$pdf->ezText(utf8_decode("<b>".$AppUI->_("Justification").": </b>" . $obj->initiating_justification),10,array('justification'=>'full'));
$pdf->ezText('');
$pdf->ezText(utf8_decode("<b>".$AppUI->_("Objectives").":</b>" .  $obj->initiating_objective),10,array('justification'=>'full'));
$pdf->ezText('');
$pdf->ezText(utf8_decode("<b>".$AppUI->_("Expected Results").": </b>\n" .  $obj->initiating_expected_result),10);
$pdf->ezText('');
$pdf->ezText(utf8_decode("<b>".$AppUI->_("Premises").": </b>" .  $obj->initiating_premise),10);
$pdf->ezText('');
$pdf->ezText(utf8_decode("<b>".($AppUI->_("Restrictions",UI_OUTPUT_HTML)). ":</b>" .  $obj->initiating_restrictions),10);
$pdf->ezText('');
$pdf->ezText(utf8_decode("<b>".($AppUI->_("Budget",UI_OUTPUT_HTML)). " (R$): </b>" .  number_format($obj->initiating_budget, 2, ',', '.')),10);
$pdf->ezText('');

$dateStart=date("d/m/Y", strtotime($obj->initiating_start_date));
$dateEnd=date("d/m/Y", strtotime($obj->initiating_end_date));

$pdf->ezText(utf8_decode("<b>".$AppUI->_("Start Date",UI_OUTPUT_HTML).": </b>" .  $dateStart),10);
$pdf->ezText('');
$pdf->ezText(utf8_decode("<b>".$AppUI->_("End Date",UI_OUTPUT_HTML).": </b>" . $dateEnd ),10);
$pdf->ezText('');
$pdf->ezText(utf8_decode("<b>".$AppUI->_("Milestones",UI_OUTPUT_HTML).": </b>\n" .  $obj->initiating_milestone),10);
$pdf->ezText('');
$pdf->ezText(utf8_decode("<b>".$AppUI->_("Criteria for success",UI_OUTPUT_HTML).": </b>\n" .  $obj->initiating_success),10);
$pdf->ezText('');
$pdf->ezText('');
$pdf->ezText('');
$pdf->ezText("<b>".$AppUI->_("LBL_SIGNATURE",UI_OUTPUT_HTML)."</b>",10,array('justification'=>'center')); 

// gera o PDF
$pdf->ezStream(); 
?>