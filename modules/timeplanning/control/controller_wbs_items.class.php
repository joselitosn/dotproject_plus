<?php
require_once (DP_BASE_DIR . "/modules/timeplanning/model/wbs_item.class.php");
class ControllerWBSItem {
	
	function ControllerWBSItem(){
	}
	
	function insert($id, $projectId, $description, $number = null, $sortOrder = null, $isLeaf = null) {
		$WBSItem= new WBSItem();
		$WBSItem->store($id, $projectId, $description, $number, $sortOrder, $isLeaf);
	}

	function setNotLeaf($itemId) {
        $WBSItem= new WBSItem();
        $WBSItem->setNotLeaf($itemId);
    }

	function getLastChildByParentNumber($projectId, $number) {
        $WBSItem= new WBSItem();
        return $WBSItem->getLastChildByParentNumber($projectId, $number);
    }


	function delete($id){
		$WBSItem= new WBSItem(); 
		$WBSItem->delete($id);
	}
	
        function getWBSItemById($wbsItemId){
            $q = new DBQuery();
            $q->addQuery('t.id, t.item_name,t.identation,t.number,t.is_leaf,t.sort_order');
            $q->addTable('project_eap_items', 't');
            $q->addWhere('t.id='.$wbsItemId);
            $sql = $q->prepare();
            $items = db_loadList($sql);
            $WBSItem= new WBSItem();
            foreach ($items as $item) {
                    $id = $item['id'];
                    $name = $item['item_name'];
                    $identation= $item['identation'];
                    $number = $item['number'];
                    $is_leaf= $item['is_leaf'];                    
                    $WBSItem->load($id,$name,$identation,$number,$is_leaf);
                    $WBSItem->setSortOrder($item['sort_order']);
            }
            return $WBSItem;
	}
        
	function getWBSItems($projectId){

        function sortWBSItemsByOrder($a, $b) {
            return $a['sort_order'] - $b['sort_order'];
        }

		$list=array();
		$q = new DBQuery();
		$q->addQuery('t.id, t.item_name,t.identation,t.number,t.is_leaf,t.sort_order, ie.size, ie.size_unit');
		$q->addTable('project_eap_items', 't');
        $q->addJoin('eap_item_estimations', 'ie', 'ie.eap_item_id = t.id');
		$q->addWhere('project_id = '.$projectId .' order by number');
		$sql = $q->prepare();
		$items = db_loadList($sql);

		// Workaround. needs to recalculate the sort order in the database for each item.
        // This is not reliable as the sort order is used to create new items.
        // Temporary for user display only.
        $j=0;
        foreach ($items as $item) {
            $number = explode(".", $item['number']);
            $items[$j]['sort_order'] = array_sum($number);
            $j++;
        }

        usort($items, 'sortWBSItemsByOrder');

        $i=0;
        foreach ($items as $item) {
			$id = $item['id'];
			$name = $item['item_name'];
			$identation= $item['identation'];
			$number = $item['number'];
			$is_leaf= $item['is_leaf'];
            $size = $item['size'];
            $sizeUnit = $item['size_unit'];
			$WBSItem= new WBSItem();
			$WBSItem->load($id,$name,$identation,$number,$is_leaf, null, $size, $sizeUnit);
            $WBSItem->setSortOrder($item['sort_order']);
			$list[$i]=$WBSItem;
            $i++;
		}

		return $list;
	}

	
	function getWorkPackages($projectId){
		$list=array();
		$q = new DBQuery();
		$q->addQuery('t.id, t.item_name,t.identation,t.number,t.is_leaf');
		$q->addTable('project_eap_items', 't');
		$q->addWhere("project_id = $projectId order by sort_order");
		$sql = $q->prepare();
		$items = db_loadList($sql);
		foreach ($items as $item) {
			$id = $item['id'];
			$name = $item['item_name'];
			$identation= $item['identation'];
			$number = $item['number'];
			$is_leaf= $item['is_leaf'];
			$WBSItem= new WBSItem();
			$WBSItem->load($id,$name,$identation,$number,$is_leaf);
			$list[$id]=$WBSItem;
		}
		return $list;
	}
	
	function getWBSItemByTask($task_id){
		$q = new DBQuery();
		$q->addQuery('t.id, t.item_name,t.identation,t.number,t.is_leaf');
		$q->addTable('project_eap_items', 't');
		$q->addTable('tasks_workpackages', 'tw');
		$q->addWhere("tw.task_id=$task_id and t.id= tw.eap_item_id order by sort_order");
		$sql = $q->prepare();
		$items = db_loadList($sql);
		$activities=array();
		$WBSItem= new WBSItem();
		foreach ($items as $item) {
			$id = $item['id'];
			$name = $item['item_name'];
			$identation= $item['identation'];
			$number = $item['number'];
			$is_leaf= $item['is_leaf']; 
			$WBSItem->load($id,$name,$identation,$number,$is_leaf);
		}
		return $WBSItem;
	}
	
}
