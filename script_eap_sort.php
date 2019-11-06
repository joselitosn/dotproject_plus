<?php
/**
 * Created by PhpStorm.
 * User: jean
 * Date: 24/10/19
 * Time: 18:06
 */

$connection = mysql_connect('172.17.0.3:3306', 'root','123456');
if (!$connection) {
    die('Could not connect to database');
}

die('connected');

$select = 'SELECT id, sort_order FROM dotp_project_eap_items';

$result = mysql_query($select, $connection);

while ($row = mysql_fetch_assoc($result)) {
    $number = explode(".", $item['number']);
    $items[$j]['sort_order'] = array_sum($number);
    $order = array_sum($number);
    $id = $row['id'];

    $update = "UPDATE dotp_project_eap_items SET sort_order=$order WHERE id = $id";
}
die('done');


