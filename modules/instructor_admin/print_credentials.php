<?php
if (!defined('DP_BASE_DIR')) {
    die('You should not access this file directly.');
}
require_once (DP_BASE_DIR . "/modules/instructor_admin/class.class.php");
$class_id = "";
$class = new CClass();
if (isset($_GET["class_id"])) {
    $class_id = $_GET["class_id"];
    $class->load($class_id);
    ?>

    <table cellpadding="20">
    <?php
        $q = new DBQuery();
        $q->addTable("dpp_classes_users");
        $q->addQuery("user_login,user_password,user_company");
        $q->addWhere("class_id=" . $class_id);
        $sql = $q->prepare();

        $records = db_loadList($sql);
        $actual_link = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $actual_link=substr($actual_link,0,strpos($actual_link, "?"));
        foreach ($records as $record) {
            $login = $record[0];
            $password = $record[1];
            $companyId = $record[2];
            ?>

            <tr>
                <td>
                    <b><?php echo $AppUI->_("URL") ?></b>:<?php echo $actual_link; ?>
                    <br />
                    <b><?php echo $AppUI->_("LBL_LOGIN") ?></b>: <?php echo $login; ?>
                    <br />
                    <b><?php echo $AppUI->_("LBL_PASSWORD") ?></b>: <?php echo $password; ?>
                </td>

            </tr>
            <?php
        }

    ?>
    </table>
    <?php
    if (sizeof($records)==0){
        echo $AppUI->_("LBL_NO_GROUP_CREATED");
    }
}
exit();
?>

