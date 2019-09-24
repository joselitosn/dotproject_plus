<?php
require_once DP_BASE_DIR . "/modules/initiating/initiating.class.php";
$project_id = dPgetParam($_GET, "project_id", 0);
$initiating = CInitiating::findByProjectId($project_id);
if (is_null($initiating)) {
    $initiating_id = -1;
} else {
    $initiating_id = $initiating->initiating_id;
}

if ($initiating_id != -1){
$q = new DBQuery();
$q->addQuery("*");
$q->addTable("initiating_stakeholder", "stk");
$q->addJoin("initiating", "i", "i.initiating_id = stk.initiating_id");
$q->addJoin("contacts", "c", "c.contact_id = stk.contact_id");
$q->addWhere("i.initiating_id=".$initiating_id);
$q->addOrder("stk.initiating_id");
$q->addOrder("stk.contact_id");
$list = $q->loadList();

require_once (DP_BASE_DIR . "/modules/stakeholder/stakeholder.class.php");
?>

<h4><?=$AppUI->_("Stakeholder",UI_OUTPUT_HTML)?></h4>
<hr>
    <?php
        if (count($list) == 0) {
    ?>
            <div class="alert alert-secondary text-center" role="alert">
                <?=$AppUI->_("LBL_THERE_IS_NO_STAKEHOLDER") ?>
                <?=$AppUI->_("LBL_CLICK"); ?>
                <a class="alert-link" href="javascript:void(0)" onclick="stakeholder.new()">
                    <?php echo $AppUI->_("LBL_HERE"); ?>
                </a>
                <?php echo $AppUI->_("LBL_TO_CREATE_A_STAKEHOLDER"); ?>
            </div>
    <?php
        } else {
    ?>
            <div class="row">
                <div class="col-md-12 text-right">
                    <a class="btn btn-sm btn-secondary" href="javascript:void(0)" onclick="stakeholder.new()">
                        Adicionar
                    </a>

                </div>
            </div>
    <?php
            foreach ($list as $row) {
                $stakeholderId = $row['initiating_stakeholder_id'];
                $power = $row["stakeholder_power"] != "1" ? $AppUI->_("LBL_PROJECT_STAKEHOLDER_LOW") : $AppUI->_("LBL_PROJECT_STAKEHOLDER_HIGH");
                $interest = $row["stakeholder_interest"] != "1" ? $AppUI->_("LBL_PROJECT_STAKEHOLDER_LOW") : $AppUI->_("LBL_PROJECT_STAKEHOLDER_HIGH");
                $obj = new CStakeholder();
                $canDelete = $obj->canDelete($msg, $stakeholderId);
    ?>
                <div class="card inner-card">
                    <div class="card-body shrink">
                        <div class="row">
                            <div class="col-md-11">
                                <h5>
                                    <a id="<?=$stakeholderId?>" data-toggle="collapse"
                                       href="#stakeholder_details_<?=$row['initiating_stakeholder_id']?>">
                                        <?=$row["contact_first_name"] . ' ' .$row["contact_last_name"]?>
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
                                           onclick="stakeholder.edit(<?=$stakeholderId?>)">
                                            <i class="far fa-edit"></i>
                                            Alterar
                                        </a>
                                        <?php
                                        if ($canDelete) {
                                            ?>
                                            <a class="dropdown-item" href="javascript:void(0)"
                                               onclick="stakeholder.delete(<?=$stakeholderId?>)">
                                                <i class="far fa-trash-alt"></i>
                                                Excluir
                                            </a>
                                            <?php
                                        }
                                        ?>
                                        <a class="dropdown-item"
                                           href="?m=stakeholder&amp;a=pdf&amp;id=<?=$stakeholderId?>&amp;suppressHeaders=1"
                                            target="_blank">
                                            <i class="far fa-file-pdf"></i>
                                            <?=$AppUI->_('Gerar PDF')?>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="stakeholder_details_<?=$stakeholderId?>" class="collapse">
                            <div class="row">
                                <table class="table table-sm no-border">
                                    <tr>
                                        <th class="text-right" width="15%"><?=$AppUI->_("Responsibilities")?>:</th>
                                        <td><?=$row["stakeholder_responsibility"]?></td>
                                    </tr>
                                    <tr>
                                        <th class="text-right" width="15%"><?php echo $AppUI->_("Interest")?>:</th>
                                        <td><?=$interest?></td>
                                        <th class="text-right" width="15%"><?php echo $AppUI->_("Power"); ?>:</th>
                                        <td><?=$power?></td>
                                        <th class="text-right" width="15%"><?php echo $AppUI->_("Strategy"); ?>:</th>
                                        <td><?=$row["stakeholder_strategy"]?></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
    <?php

            }
        }
    ?>
<?php
}
?>
<div class="modal" id="addEditStakeholderModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body stakeholder-modal">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal"><?=$AppUI->_('LBL_CLOSE')?></button>
                <button type="button" class="btn btn-primary btn-sm" id="btnSaveStakeholder" ><?=$AppUI->_('LBL_SAVE')?></button>
            </div>
        </div>
    </div>
</div>

<script>
    var stakeholder = {
        init: function() {
            $(document).ready(function(e) {
                $('a[data-toggle=collapse]').on('click', stakeholder.show);
            });
        },

        show: function (e) {
            const collapseRole = $(e.target);
            $('#stakeholder_details_'+e.target.id).on('shown.bs.collapse', function () {
                collapseRole.find('i').removeClass('fa-caret-down');
                collapseRole.find('i').addClass('fa-caret-up');
            });
            $('#stakeholder_details_'+e.target.id).on('hidden.bs.collapse', function () {
                collapseRole.find('i').removeClass('fa-caret-up');
                collapseRole.find('i').addClass('fa-caret-down');
            });
        },
        
        new: function () {
            $.ajax({
                type: "get",
                url: "?m=stakeholder&template=addedit&project_id=<?=$project_id?>&initiating_id=<?=$initiating_id?>"
            }).done(function(response) {
                $(".stakeholder-modal").html(response);
                $(".modal-title").html("Adicionar stakeholder");
                $("#btnSaveStakeholder").on("click", function() {
                    stakeholder.save();
                })
                $("#addEditStakeholderModal").modal();
            });
        },

        edit: function (id) {
            $.ajax({
                type: "get",
                url: "?m=stakeholder&template=addedit&project_id=<?=$project_id?>&initiating_id=<?=$initiating_id?>&initiating_stakeholder_id="+id
            }).done(function(response) {
                $(".stakeholder-modal").html(response);
                $(".modal-title").html("Alterar stakeholder");
                $("#btnSaveStakeholder").on("click", function() {
                    stakeholder.save();
                });
                $("#addEditStakeholderModal").modal();
            });
        },

        delete: function (id) {
            $.ajax({
                url: "?m=stakeholder",
                type: "post",
                datatype: "json",
                data: {
                    dosql: 'do_stakeholder_aed',
                    initiating_stakeholder_id: id,
                    del: 1
                },
                success: function(resposta) {
                    $.alert({
                        icon: "far fa-check-circle",
                        type: "green",
                        title: "<?=$AppUI->_('Success', UI_OUTPUT_JS); ?>",
                        content: resposta,
                        onClose: function() {
                            $("#addEditStakeholderModal").modal("hide");
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
        },
        
        save: function () {
            var name = $('input[name=first_name]').val();

            var err = false;
            var msg = '';
            if (!name.trim()) {
                err = true;
                msg = 'O nome é obrigatório';
            }

            if (err) {
                $.alert({
                    icon: "far fa-times-circle",
                    type: "red",
                    title: "<?=$AppUI->_('Error', UI_OUTPUT_JS); ?>",
                    content: msg
                });
                return;
            }
            // submete o formulário tanto para inclusão como alteração
            $.ajax({
                url: "?m=stakeholder",
                type: "post",
                datatype: "json",
                data: $("form[name=stakeholderForm]").serialize(),
                success: function(resposta) {
                    $.alert({
                        icon: "far fa-check-circle",
                        type: "green",
                        title: "<?=$AppUI->_('Success', UI_OUTPUT_JS); ?>",
                        content: resposta,
                        onClose: function() {
                            $("#addEditStakeholderModal").modal("hide");
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

    }

    $(document).ready(stakeholder.init());
</script>
