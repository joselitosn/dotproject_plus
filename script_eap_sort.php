<?php
/**
 * Created by PhpStorm.
 * User: jean
 * Date: 24/10/19
 * Time: 18:06
 */

$connection = mysqli_connect('172.17.0.3:3306', 'root','123456');
if (!$connection) {
    die('Could not connect to database');
}

$select = 'SELECT id, number FROM dot_project.dotp_project_eap_items';

$result = mysqli_query($connection, $select);

if (!$result) {
    die("Error \n");
}
while ($row = mysqli_fetch_assoc($result)) {
    $number = explode(".", $row['number']);
    $order = array_sum($number);
    $id = $row['id'];
    $update = "UPDATE dot_project.dotp_project_eap_items SET sort_order=$order WHERE id = $id";
    mysqli_query($connection, $update);
}
die("done \n");


