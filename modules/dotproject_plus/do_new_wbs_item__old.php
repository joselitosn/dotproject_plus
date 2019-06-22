<?php
require_once (DP_BASE_DIR . "/modules/timeplanning/control/controller_wbs_items.class.php");
$controllerWBSItem= new ControllerWBSItem();

$id=dPgetParam($_POST, 'item_id', -1);
$project_id = dPgetParam($_POST, 'project_id');
$description = dPgetParam($_POST, 'wbs_item_description');
$parentNumber = $_POST["parent_number"];
//$estimated_size=$_POST["estimated_size_".$wbs_item_id];
//$estimated_size_unit=$_POST["estimated_size_unit_".$wbs_item_id];

$isLeaf= 1; // New item is always leaf
//$sortOrder=$_POST["sort_order"];


// Ver se é alteração ou inclusão. A geração de número só vale para inclusão
// primeiro identificar o pai
// segundo, pegar o último filho
// terceiro, pegar o número do último filho e acrescentar 1 - $number gerado
// quarto, somar os dígitos de $number - $sortOrder garada. (A ordem deve ser atualizada para todos os itens do projeto - Talvez não - Testar)

$lastChildNumber = $controllerWBSItem->getLastChildByParentNumber($project_id, $parentNumber);

// Gets the prefix of the number
$prefix = substr($lastChildNumber, 0, -2);
// Extracts the last digit and adds 1 to it
$lastDigit = (int) substr($lastChildNumber, -1) + 1;
// Generates the next number
$number  = $prefix . '.' . $lastDigit;

$sortOrder = array_sum(explode('.', $number));


$items = $controllerWBSItem->getWBSItems($project_id);
$foundParent=false;
foreach ($items as $item) {



    if($item->getNumber() == $number_parent){ //found parent
        $foundParent=true;
    }else if( $foundParent && (strlen ($item->getNumber())>strlen($number_parent)) ){

        $sortOrder=$item->getSortOrder();//the new sort order will be equals the current last children

    }else{
        $foundParent=false;
    }
}

$controllerWBSItem->insert($project_id,$description,$number,$sortOrder,$isLeaf, $id);
exit();
//$AppUI->setMsg($AppUI->_("LBL_DATA_SUCCESSFULLY_PROCESSED"), UI_MSG_OK, true);
$id_new_eap_item=mysql_insert_id();
$AppUI->redirect('m=projects&a=view&project_id='.$project_id."&id_new_eap_item=".$id_new_eap_item);
?>