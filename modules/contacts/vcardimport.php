<?php /* CONTACTS $Id: vcardimport.php 5872 2009-04-25 00:09:56Z merlinyoda $ */
if (!defined('DP_BASE_DIR')) {
  die('You should not access this file directly.');
}

$canAuthor = getPermission('contacts', 'add');
if (!($canAuthor)) {
	$AppUI->redirect('m=public&a=access_denied');
}
$vcf = $_FILES['file'];
// include PEAR vCard class
require_once($AppUI->getLibraryClass('PEAR/Contact_Vcard_Parse'));

if (is_uploaded_file($vcf['tmp_name'])) {

    // instantiate a parser object
    $parse = new Contact_Vcard_Parse();

    // parse a vCard file and store the data
    // in $cardinfo
    $cardinfo = $parse->fromFile($vcf['tmp_name']);

    // store the card info array

    foreach ($cardinfo as $ci) {

        $obj = new CContact();

        //transform the card info array to dP store format
        $contactValues["contact_last_name"] = $ci['N'][0]['value'][0][0];
        $contactValues["contact_first_name"] = $ci['N'][0]['value'][1][0];
        $contactValues["contact_title"] = $ci['N'][0]['value'][3][0];
        $contactValues["contact_birthday"] = $ci['BDAY'][0]['value'][0][0];
        $contactValues["contact_company"] = $ci['ORG'][0]['value'][0][0];
        $contactValues["contact_type"] = $ci['N'][0]['value'][2][0];
        $contactValues["contact_email"] = $ci['EMAIL'][0]['value'][0][0];
        $contactValues["contact_email2"] = $ci['EMAIL'][1]['value'][0][0];
        $contactValues["contact_phone"] = $ci['TEL'][0]['value'][0][0];
        $contactValues["contact_phone2"] = $ci['TEL'][1]['value'][0][0];
        $contactValues["contact_mobile"] = $ci['TEL'][2]['value'][0][0];
        if ($ci['ADR'][0]['value'][1][0]) {
            $contactValues['contact_address1'] = $ci['ADR']['0']['value']['1']['0'];
            $contactValues['contact_address2'] = $ci['ADR']['0']['value']['2']['0'];
        } else {
            $contactValues['contact_address1'] = $ci['ADR']['0']['value']['2']['0'];
            $contactValues['cotnact_address2'] = '';
        }
        $contactValues["contact_city"] = $ci['ADR'][0]['value'][3][0];
        $contactValues["contact_state"] = $ci['ADR'][0]['value'][4][0];
        $contactValues["contact_zip"] = $ci['ADR'][0]['value'][5][0];
        $contactValues["contact_country"] = $ci['ADR'][0]['value'][6][0];
        $contactValues["contact_notes"] = $ci['NOTE'][0]['value'][0][0];
        $contactValues["contact_order_by"] = $contactValues["contact_last_name"].', '.$contactValues["contact_first_name"];
        $contactValues["contact_id"] = 0;

        // bind array to object
        if (!$obj->bind($contactValues)) {
            $AppUI->setMsg($obj->getError(), UI_MSG_ERROR);
        }

        // store vCard data for this object
        if (($msg = $obj->store())) {
            $AppUI->setMsg($msg, UI_MSG_ERROR);
        }
    }
    // one or more vCard imports were successful
    $AppUI->setMsg('vCard(s) imported', UI_MSG_OK, true);

}
else {	// redirect in case of file upload trouble
    $AppUI->setMsg("vCardFileUploadError", UI_MSG_ERROR);
}
echo $AppUI->getMsg();
exit();
?>
