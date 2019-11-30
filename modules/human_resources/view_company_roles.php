<?php
if (!defined('DP_BASE_DIR')) {
    die('You should not access this file directly.');
}

global $tabbed, $currentTabName, $currentTabId, $AppUI;

$company_id = intval(dPgetParam($_GET, 'company_id', 0));
?>


<?php
$query = new DBQuery;
$query->addTable('human_resources_role', 'r');
$query->addQuery('r.*');
$query->addWhere('r.human_resources_role_company_id = ' . $company_id);
$res_companies = & $query->exec();

?>
    <h4><?=$AppUI->_("LBL_ORGANIZATION_ROLES");?></h4>
    <hr>
<?php

require_once DP_BASE_DIR . "/modules/human_resources/configuration_functions.php";
$roles = array();
for ($res_companies; !$res_companies->EOF; $res_companies->MoveNext()) {
    $role_id = $res_companies->fields['human_resources_role_id'];

    $obj = new CHumanResourcesRole();
    $obj->load($role_id);
    $roles[] = array(
        'canDelete' => $obj->canDelete($msg),
        'obj' => $obj
    );
}
if (count($roles) === 0) {
    ?>

    <div class="alert alert-secondary text-center" role="alert">
        <?=$AppUI->_("LBL_THERE_IS_NO_ROLE") ?>
        <?=$AppUI->_("LBL_CLICK"); ?>
        <a class="alert-link" href="javascript:void(0)" onclick="roles.new()">
            <?php echo $AppUI->_("LBL_HERE"); ?>
        </a>
        <?php echo $AppUI->_("LBL_TO_CREATE_A_ROLE"); ?>
    </div>
    <?php
} else {
    ?>
    <div class="row">
        <div class="col-md-12 text-right">
            <a class="btn btn-sm btn-secondary" href="javascript:void(0)" onclick="roles.new()">
                Adicionar
            </a>

        </div>
    </div>


    <?php
    foreach ($roles as $role) {
        $human_resources_role_id = $role['obj']->human_resources_role_id;
        $canDelete = $role['canDelete'];
        $configured = isConfiguredRole($human_resources_role_id);
        $style = $configured ? '' : 'background-color:#ED9A9A; font-weight:bold';
        ?>

        <div class="card inner-card">
            <div class="card-body shrink">
                <div class="row">
                    <div class="col-md-11">
                        <h5>
                            <a id="<?= $human_resources_role_id ?>" data-toggle="collapse"
                               href="#role_details_<?= $human_resources_role_id ?>">
                                <?= $role['obj']->human_resources_role_name ?>
                                <i class="fas fa-caret-down"></i>
                            </a>
                        </h5>
                    </div>
                    <div class="col-md-1 text-right">
                        <div class="dropdown">
                            <a href="javascript:void(0)" class="link-primary" role="button" data-toggle="dropdown"
                               aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-bars"></i>
                            </a>

                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink">
                                <a class="dropdown-item" href="javascript:void(0)"
                                   onclick="roles.edit(<?= $human_resources_role_id ?>)">
                                    <i class="far fa-edit"></i>
                                    <?= $AppUI->_('LBL_UPDATE_ROLE') ?>
                                </a>
                                <?php
                                if ($canDelete && $human_resources_role_id > 0) {
                                    ?>
                                    <a class="dropdown-item" href="javascript:void(0)"
                                       onclick="roles.delete(<?= $human_resources_role_id ?>)">
                                        <i class="far fa-trash-alt"></i>
                                        <?= $AppUI->_('LBL_DELETE_ROLE') ?>
                                    </a>
                                    <?php
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="role_details_<?= $human_resources_role_id ?>" class="collapse">
                    <div class="row">
                        <table class="table table-sm no-border">
                            <tr>
                                <th class="text-right" width="15%"><?php echo $AppUI->_("Role responsability"); ?>:</th>
                                <td><?php echo $role['obj']->human_resources_role_responsability ?></td>
                            </tr>
                            <tr>
                                <th class="text-right" width="15%"><?php echo $AppUI->_("Role authority"); ?>:</th>
                                <td><?php echo $role['obj']->human_resources_role_authority ?></td>
                            </tr>
                            <tr>
                                <th class="text-right" width="15%"><?php echo $AppUI->_("Role competence"); ?>:</th>
                                <td><?php echo $role['obj']->human_resources_role_competence ?></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
    $query->clear();
}
?>

<div class="modal" id="addEditRoleModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body role-modal">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal"><?=$AppUI->_('LBL_CLOSE')?></button>
                <button type="button" class="btn btn-primary btn-sm" id="btnSaveRole" ><?=$AppUI->_('LBL_SAVE')?></button>
            </div>
        </div>
    </div>
</div>

<script>

    var roles = {

        init: function() {
            $(document).ready(function(e) {
                $('a[data-toggle=collapse]').on('click', roles.show);
            });
        },

        show: function (e) {
            const collapseRole = $(e.target);
            $('#role_details_'+e.target.id).on('shown.bs.collapse', function () {
                collapseRole.find('i').removeClass('fa-caret-down');
                collapseRole.find('i').addClass('fa-caret-up');
            });
            $('#role_details_'+e.target.id).on('hidden.bs.collapse', function () {
                collapseRole.find('i').removeClass('fa-caret-up');
                collapseRole.find('i').addClass('fa-caret-down');
            });
        },

        delete: function (id) {
            $.confirm({
                title: '<?=$AppUI->_("LBL_CONFIRM", UI_OUTPUT_JS); ?>',
                content: '<?=$AppUI->_("LBL_DELETE_MSG_ROLE", UI_OUTPUT_JS); ?>',
                buttons: {
                    sim: {
                        action: function () {
                            $.ajax({
                                url: "?m=human_resources",
                                type: "post",
                                datatype: "json",
                                data: {
                                    dosql: 'do_role_aed',
                                    del: 1,
                                    human_resources_role_id: id,
                                    human_resources_role_company_id: <?=$company_id?>
                                },
                                success: function(resposta) {
                                    $.alert({
                                        icon: "far fa-check-circle",
                                        type: "green",
                                        title: "<?=$AppUI->_('Success', UI_OUTPUT_JS); ?>",
                                        content: resposta,
                                        onClose: function() {
                                            window.location.reload(true);
                                        }
                                    });
                                },
                                error: function(resposta) {
                                    $.alert({
                                        icon: "far fa-times-circle",
                                        type: "red",
                                        title: "<?=$AppUI->_('Error', UI_OUTPUT_JS); ?>",
                                        content: "<?=$AppUI->_('Something went wrong.', UI_OUTPUT_JS); ?>"
                                    });
                                }
                            });
                        }
                    },
                    nao: {
                        text: 'Não'
                    },
                }
            });
        },

        new: function() {
            $.ajax({
                type: "get",
                url: "?m=human_resources&template=view_role&company_id=<?=$company_id?>"
            }).done(function(response) {
                $(".role-modal").html(response);
                $(".modal-title").html("<?=$AppUI->_('LBL_NEW_ROLE')?>");
                $("#btnSaveRole").on("click", function() {
                    roles.save();
                })
                $("#addEditRoleModal").modal();
            });
        },

        edit: function (id) {
            $.ajax({
                type: "get",
                url: "?m=human_resources&template=view_role&human_resources_role_id="+id+"&company_id=<?=$company_id?>"
            }).done(function(response) {
                $(".role-modal").html(response);
                $(".modal-title").html("<?=$AppUI->_('LBL_NEW_ROLE')?>");
                $("#btnSaveRole").on("click", function() {
                    roles.save();
                })
                $("#addEditRoleModal").modal();
            });
        },
        
        save: function () {

            var name = $("input[name=human_resources_role_name]").val();

            if (!name) {
                var msg = [];
                if (!name) msg.push("<?=$AppUI->_('You must enter a role name', UI_OUTPUT_JS); ?>");
                $.alert({
                    icon: "far fa-times-circle",
                    type: "red",
                    title: "<?=$AppUI->_('Error', UI_OUTPUT_JS); ?>",
                    content: msg.join("<br>")
                });
                return;
            }
            $.ajax({
                url: "?m=human_resources",
                type: "post",
                datatype: "json",
                data: $("form[name=editfrm]").serialize(),
                success: function(resposta) {
                    $.alert({
                        icon: "far fa-check-circle",
                        type: "green",
                        title: "<?=$AppUI->_('Success', UI_OUTPUT_JS); ?>",
                        content: resposta,
                        onClose: function() {
                            window.location.reload(true);
                        }
                    });
                    $("#addEditRoleModal").modal("hide");
                },
                error: function(resposta) {
                    $.alert({
                        icon: "far fa-times-circle",
                        type: "red",
                        title: "<?=$AppUI->_('Error', UI_OUTPUT_JS); ?>",
                        content: "<?=$AppUI->_('Something went wrong.', UI_OUTPUT_JS); ?>"
                    });
                }
            });
        }
    }

    roles.init();
</script>