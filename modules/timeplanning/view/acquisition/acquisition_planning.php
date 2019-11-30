<?php
require_once (DP_BASE_DIR . "/modules/timeplanning/control/acquisition/controller_acquisition_planning.class.php");
require_once (DP_BASE_DIR . "/modules/timeplanning/model/acquisition/acquisition_planning.class.php");
$controller = new ControllerAcquisitionPlanning();
$projectId = $_GET["project_id"];
?>

<h4><?=$AppUI->_("LBL_PROJECT_ACQUISITIONS", UI_OUTPUT_HTML)?></h4>
<hr>
<div class="row">
    <div class="col-md-12 text-right">
        <button type="buton" class="btn btn-secondary btn-xs" onclick="acquisition.new(<?=$projectId?>)">Adicionar</button>
    </div>
</div>
<br>
<?php
    $list = $controller->getAcquisitionPlanningsPerProject($projectId);
    foreach ($list as $object) {
        ?>
        <div class="card inner-card">
            <div class="card-body shrink">
                <div class="row">
                    <div class="col-md-11">
                        <h5>
                            <a id="<?=$object->getId()?>" data-toggle="collapse"
                               href="#acquisition_details_<?=$object->getId()?>">
                                <?=$object->getItemsToBeAcquired()?>
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
                                   onclick="acquisition.edit(<?=$object->getId()?>, <?=$projectId?>)">
                                    <i class="far fa-edit"></i>
                                    Alterar
                                </a>
                                <a class="dropdown-item" href="javascript:void(0)"
                                   onclick="acquisition.delete(<?=$object->getId()?>)">
                                    <i class="far fa-trash-alt"></i>
                                    Excluir
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="acquisition_details_<?=$object->getId()?>" class="collapse">
                    <div class="row">
                        <table class="table table-sm no-border">
                            <tr>
                                <th class="text-right" width="25%"><?= $AppUI->_('LBL_CONTRACT_TYPE') ?>:</th>
                                <td width="25%"><?= $object->getContractType()?></td>

                                <th class="text-right" width="25%"><?= $AppUI->_('LBL_DOCUMENTS_TO_ACQUIRE') ?>:</th>
                                <td width="25%"><?=$object->getDocumentsToAcquisition()?></td>
                            </tr>
                            <tr>
                                <th class="text-right" width="25%"><?= $AppUI->_('LBL_CRITERIA_TO_SUPPLIERS_SELECTION') ?>:</th>
                                <td width="25%"><?=$object->getCriteriaForSelection()?></td>
                                
                                <th class="text-right" width="25%"><?= $AppUI->_('LBL_ACQUISITION_ADDITIONAL_REQUIRIMENTS') ?>:</th>
                                <td width="25%"><?=$object->getAdditionalRequirements()?></td>
                            </tr>
                            <tr>
                                <th class="text-right" width="25%"><?= $AppUI->_('LBL_SUPPLIERS_PROCESSES_MANAGEMENT') ?>:</th>
                                <td width="25%"><?=$object->getSupplierManagementProcess()?></td>
                                
                                <th class="text-right" width="25%"><?= $AppUI->_('LBL_ACQUISITION_ROLES_RESPONSABILITIES') ?>:</th>
                                <td width="25%"><?=$object->getAcquisitionRoles()?></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
    ?>
<!-- MODAL ADD/EDIT RISK -->
<div id="acquisitionModal" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="<?=$AppUI->_("LBL_CLOSE")?>">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body acquisition-modal">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal"><?=$AppUI->_("LBL_CLOSE")?></button>
                <button type="button" class="btn btn-primary btn-sm" onclick="acquisition.save()"><?=$AppUI->_("LBL_SAVE")?></button>
            </div>
        </div>
    </div>
</div>





<script>
    var acquisition = {
        init: function() {
            $('a[data-toggle=collapse]').on('click', acquisition.show);
        },

        show: function (e) {
            const collapseRisk = $(e.target);
            $('#acquisition_details_'+e.target.id).on('show.bs.collapse', function () {
                collapseRisk.find('i').removeClass('fa-caret-down');
                collapseRisk.find('i').addClass('fa-caret-up');
            });
            $('#acquisition_details_'+e.target.id).on('hide.bs.collapse', function () {
                collapseRisk.find('i').removeClass('fa-caret-up');
                collapseRisk.find('i').addClass('fa-caret-down');
            });
        },

        new: function(projectId) {
            $.ajax({
                type: "get",
                url: "?m=timeplanning&template=view/acquisition/addedit&project_id="+projectId
            }).done(function(response) {
                var modal = $('#acquisitionModal');
                modal.find('h5').html('Adicionar aquisição');
                $('.acquisition-modal').html(response);
                modal.modal();
            });
        },

        edit: function(id, projectId) {
            $.ajax({
                type: "get",
                url: "?m=timeplanning&template=view/acquisition/addedit&acquisition_planning_id="+id+"&project_id="+projectId
            }).done(function(response) {
                var modal = $('#acquisitionModal');
                modal.find('h5').html('Alterar aquisição');
                $('.acquisition-modal').html(response);
                modal.modal();
            });
        },

        save: function() {
            var name = $('input[name=items_to_be_acquired]').val();
            if (!name) {
                $.alert({
                    icon: "far fa-times-circle",
                    type: "red",
                    title: "Erro",
                    content: 'Preencha o item a ser adquirido'
                });
                return;
            }

            $.ajax({
                method: 'POST',
                url: "?m=timeplanning",
                data: $('form[name=acquisitionForm]').serialize(),
                success: function(resposta) {
                    $.alert({
                        icon: "far fa-check-circle",
                        type: "green",
                        title: "Sucesso",
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
                        title: "Erro",
                        content: "Algo deu errado"
                    });
                }
            });
        },

        delete: function(id) {
            $.confirm({
                title: 'Excluir aquisição',
                content: 'Você tem certeza de que quer excluir esta aquisição?',
                buttons: {
                    yes: {
                        text: 'Sim',
                        action: function () {
                            $.ajax({
                                method: 'POST',
                                url: "?m=timeplanning",
                                data: {
                                    dosql: 'do_project_acquisition_deletion',
                                    acquisition_planning_id: id
                                }
                            }).done(function(resposta) {
                                $.alert({
                                    icon: "far fa-check-circle",
                                    type: "green",
                                    title: "Sucesso",
                                    content: resposta,
                                    onClose: function() {
                                        window.location.reload(true);
                                    }
                                });
                            });
                        },
                    },
                    no: {
                        text: 'Não'
                    }
                }
            });
        }
    }

    $(document).ready(acquisition.init);
</script>
