<?php
/**
 * Created by PhpStorm.
 * User: jean
 * Date: 24/10/19
 * Time: 18:06
 */
//        function sortWBSItemsByOrder($a, $b) {
//            return $a['sort_order'] - $b['sort_order'];
//        }

$list=array();
$q = new DBQuery();
$q->addQuery('t.id, t.item_name,t.identation,t.number,t.is_leaf,t.sort_order, ie.size, ie.size_unit');
$q->addTable('project_eap_items', 't');
$q->addJoin('eap_item_estimations', 'ie', 'ie.eap_item_id = t.id');
$q->addWhere('project_id = '.$projectId .' order by number');
$sql = $q->prepare();
$items = db_loadList($sql);


// TODO remover tudo com comentário abaixo
// Workaround. needs to recalculate the sort order in the database for each item.
// This is not reliable as the sort order is used to create new items.
// Temporary for user display only.
//        $j=0;
//        foreach ($items as $item) {
//            $number = explode(".", $item['number']);
//            $items[$j]['sort_order'] = array_sum($number);
//
//            $q = new DBQuery();
//            $q->addTable('project_eap_items');
//            $q->addUpdate('sort_order', array_sum($number));
//            $q->addWhere('id = ' . $item['id']);
//            $q->exec();
//            $q->clear();
//
//            $j++;
//        }
//        die('done');
//
//
//
//
//
//        usort($items, 'sortWBSItemsByOrder');