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
}
?>

<table class="table table-sm table-bordered table-responsive-md">
    <thead class="thead-dark">
        <tr>
            <th><?php echo $AppUI->_("LBL_LOGIN") ?></th>
            <th><?php echo $AppUI->_("LBL_PASSWORD") ?></th>
            <th><?php echo $AppUI->_("Project") ?></th>
            <th><?php echo $AppUI->_("LBL_PROJECT_CHARTER") ?></th>
            <th><?php echo $AppUI->_("LBL_PROJECT_PLAN") ?></th>
            <th><?php echo $AppUI->_("LBL_AUTO_EVALUATION") ?></th>
            <th>Feedback</th>
            <th>Excluir</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $q = new DBQuery();
        $q->addTable("dpp_classes_users");
        $q->addQuery("user_login,user_password,user_company,user_id");
        $q->addWhere("class_id=" . $class_id);
        $sql = $q->prepare();
        //echo $sql;
        $records = db_loadList($sql);
        require_once DP_BASE_DIR . "/modules/initiating/initiating.class.php";

        foreach ($records as $record) {
            $login = $record[0];
            $password = $record[1];
            $companyId = $record[2];
            $userId = $record[3];
            //get project and initiating Ids
            $initiating_id = -1;
            $project_id = -1;
            $q = new DBQuery();
            $q->addTable("projects");
            $q->addQuery("project_id");
            $q->addWhere("project_company=" . $companyId);
            $sql = $q->prepare();
            //echo $sql;
            $projects = db_loadList($sql);
            foreach ($projects as $project) {
                $project_id = $project["project_id"];
                $initiating = CInitiating::findByProjectId($project_id);
                if (!is_null($initiating)) {
                    $initiating_id = $initiating->initiating_id;
                }
            }
            ?>
            <tr>
                <td><?php echo $login; ?></td>
                <td><?php echo $password; ?></td>
                <td>
                    <a href="index.php?m=companies&a=view&company_id=<?php echo $companyId; ?>" target="_blank">
                        <?php echo $AppUI->_("LBL_ACCESS_GROUP_PROJECT") ?>
                    </a>
                </td>
                <td align="center">
                    <?php
                    if ($initiating_id != -1) {
                        ?>
                        <a href='?m=initiating&a=pdf&id=<?php echo $initiating_id ?>&suppressHeaders=1'>
                            <img src="./modules/instructor_admin/images/pdf_icon.png" />
                        </a>
                        <?php
                    } else {
                        ?>
                        <img src="./modules/instructor_admin/images/pdf_icon_disable.png" />
                        <?php
                    }
                    ?>
                </td>
                <td align="center">
                    <?php
                    if ($project_id != -1) {
                        ?>
                        <a href = "modules/timeplanning/view/export_project_plan/export.php?project_id=<?php echo $project_id; ?>&print=0" target = "_blank" style="font-weight: bold">
                            <img src="./modules/instructor_admin/images/pdf_icon.png" />
                        </a>
                        <?php
                    } else {
                        ?>
                        <img src="./modules/instructor_admin/images/pdf_icon_disable.png" />
                        <?php
                    }
                    ?>
                </td>

                <td align="center">

                    <?php
                    if ($project_id != -1) {
                        ?>
                        <a href="index.php?m=instructor_admin&a=do_auto_evaluation&user_id=<?php echo $userId; ?>&project_id=<?php echo $project_id ?>&class_id=<?php echo $class_id ?>">
                            <img src="./modules/instructor_admin/images/word_icon.png" />
                        </a>
                        <?php
                    } else {
                        ?>
                        <img src="./modules/instructor_admin/images/word_icon_disable.png" />
                        <?php
                    }
                    ?>
                    </a>
                </td>
                <td>
                    <a href="javascript:void(0)" onclick="course.showGroupFeedback(<?=$userId?>, <?=$project_id?>, <?=$class_id?>)">
                        <?php echo $AppUI->_("LBL_VIEW_READ_FEEDBACK") ?>
                    </a>
                </td>
                <td>
                    <button type="button" class="btn btn-xs btn-danger"
                            onclick="course.deleteGroup(<?=$class_id?>, <?=$userId?>)">
                        <i class="far fa-trash-alt"></i>
                    </button>
                </td>
            </tr>
            <?php
        }
        ?>
    </tbody>
</table>
<?php
    exit();