<?php
require_once (DP_BASE_DIR . "/modules/timeplanning/control/controller_company_role.class.php");
require_once DP_BASE_DIR . "/modules/system/roles/roles.class.php";
$controllerCompanyRole = new ControllerCompanyRole();
?>
<table class="printTable">
    <tr>
        <td>
            <ol type="disc">
                <?php
                $roles = $controllerCompanyRole->getCompanyRoles($companyId);
                foreach ($roles as $role) {
                    $id = $role->getId();
                    $name = $role->getDescription();
                    $identation = $role->getIdentation();
                    ?>
                    <li>
                        <?php echo $identation . $name ?>
                    </li>
                    <?php
                }
                ?>
            </ol>
        </td>
    </tr>
</table>