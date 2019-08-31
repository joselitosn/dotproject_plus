<?php
if (!defined('DP_BASE_DIR')) {
    die('You should not access this file directly.');
}
//limpar a sessão
unset($_SESSION['receptors']);
unset($_SESSION['emitters']);
$project_id = $_GET["project_id"];

require_once DP_BASE_DIR . '/modules/communication/communication.class.php';

$q = new DBQuery();
$q->addQuery('c.communication_id, c.communication_title, c.communication_information, ch.*, fr.*, p.project_name');
$q->addTable('communication', 'c');
$q->addJoin('communication_channel', 'ch', 'ch.communication_channel_id=c.communication_channel_id');
$q->addJoin('communication_frequency', 'fr', 'fr.communication_frequency_id=c.communication_frequency_id');
$q->addJoin('projects', 'p', 'p.project_id=c.communication_project_id');
$q->addwhere('c.communication_project_id=' . $project_id);
$list = $q->loadList();
$q->clear();

$q = new DBQuery();
$q->addQuery('c.communication_id, co.contact_first_name as emissor_first_name, co.contact_last_name as emissor_last_name');
$q->addTable('communication', 'c');
$q->addJoin('communication_issuing', 'ci', 'ci.communication_id=c.communication_id');
$q->addJoin('initiating_stakeholder', 'st', 'st.initiating_stakeholder_id=ci.communication_stakeholder_id');
$q->addJoin('contacts', 'co', 'co.contact_id=ci.communication_stakeholder_id');
$list_Emissor = $q->loadList();

$q->clear();

$q = new DBQuery();
$q->addQuery('c.communication_id, cor.contact_first_name as receptor_first_name, cor.contact_last_name as receptor_last_name');
$q->addTable('communication', 'c');
$q->addJoin('communication_receptor', 'cr', 'cr.communication_id=c.communication_id');
$q->addJoin('initiating_stakeholder', 'str', 'str.initiating_stakeholder_id=cr.communication_stakeholder_id');
$q->addJoin('contacts', 'cor', 'cor.contact_id=cr.communication_stakeholder_id');
$list_Receptor = $q->loadList();

$q->clear();
?>

<h4><?=$AppUI->_('LBL_PROJECT_COMMUNICATION');?></h4>
<hr>

<div class="row">
    <div class="col-md-12 text-right">
        <a class="btn btn-sm btn-secondary" href="javascript:void(0)" onclick="communication.new(<?=$project_id?>)">
            <?=ucfirst($AppUI->_("LBL_NEW_COMMUNICATION"))?>
        </a>
    </div>
</div>
<?php foreach ($list as $row) { 
    $comId = $row['communication_id'];    
?>
    <div class="card inner-card">
        <div class="card-body shrink">
            <div class="row">
                <div class="col-md-11">
                    <h5>
                        <a id="<?=$comId?>" data-toggle="collapse" href="#com_details_<?=$comId?>">
                            <?=$row['communication_title']?>
                            <i class="fas fa-caret-down"></i>
                        </a>
                    </h5>
                </div>
                <div class="col-md-1 text-right">
                    <div class="dropdown">
                        <a href="javascript:void(0)" class="link-primary" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-bars"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink">
                            <a class="dropdown-item" href="javascript:void(0)" onclick="communication.edit(<?=$comId?>, <?=$project_id?>)">
                                <i class="far fa-edit"></i>
                                Alterar
                            </a>
                            <?php
                            $obj = new CCommunication();
                            $canDelete=true;
                            if ($$comId != -1) {
                                $obj->load($comId);
                                $canDelete=$obj->canDelete($msg);
                            }

                            if ($canDelete) {
                                ?>
                                <a class="dropdown-item" href="javascript:void(0)" onclick="communication.delete(<?=$comId?>)">
                                    <i class="far fa-trash-alt"></i>
                                    Excluir
                                </a>
                                <?php
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
            <div id="com_details_<?=$comId?>" class="collapse">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-sm no-border">
                            <tr>
                                <th class="text-right" width="15%"><?=$AppUI->_("LBL_COMMUNICATION")?>:</th>
                                <td><?=$row['communication_information']?></td>
                            </tr>
                            <tr>
                                <th class="text-right" width="15%"><?=$AppUI->_("LBL_CHANNEL")?>:</th>
                                <td><?=$row['communication_channel']?></td>
                            </tr>
                            <tr>
                                <th class="text-right" width="15%"><?=$AppUI->_('LBL_FREQUENCY')?>:</th>
                                <td><?=$row['communication_frequency']?></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php } ?>

<!-- MODAL ADD/EDIT COMMUNICATION -->
<div id="communicationModal" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="<?=$AppUI->_("LBL_CLOSE")?>">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body communication-modal">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal"><?=$AppUI->_("LBL_CLOSE")?></button>
                <button type="button" class="btn btn-primary btn-sm" onclick="communication.save()"><?=$AppUI->_("LBL_SAVE")?></button>
            </div>
        </div>
    </div>
</div>

<script>
    var communication = {
        init: function() {
            $('a[data-toggle=collapse]').on('click', communication.show);  
            $('#communicationModal').on('hidden.bs.modal', function() {
                $(this).find('h5').html('Adicionar comunicação');
                $('.communication-modal').html('');
            });
        },

        show: function(e) {
            const collapse = $(e.target);
            $('#com_details_'+e.target.id).on('show.bs.collapse', function () {
                collapse.find('i').removeClass('fa-caret-down');
                collapse.find('i').addClass('fa-caret-up');
            });
            $('#com_details_'+e.target.id).on('hide.bs.collapse', function () {
                collapse.find('i').removeClass('fa-caret-up');
                collapse.find('i').addClass('fa-caret-down');
            });
        },

        new: function(projectId) {
            $.ajax({
                type: "get",
                url: "?m=communication&template=addedit&project_id="+projectId
            }).done(function(response) {
                var modal = $('#communicationModal');
                modal.find('h5').html('Adicionar comunicação');
                $('.communication-modal').html(response);
                modal.modal();
            });
        },

        edit: function(id, projectId) {
            $.ajax({
                type: "get",
                url: "?m=communication&template=addedit&communication_id="+id+"&project_id="+projectId
            }).done(function(response) {
                var modal = $('#communicationModal');
                modal.find('h5').html('Alterar comunicação');
                $('.communication-modal').html(response);
                modal.modal();
            });
        },

        save: function() {
            var name = $('input[name=communication_title]').val();
            var desc = $('textarea[name=communication_information]').val();

            var msg = [];
            var err = false;
            if (!name.trim()) {
                err = true;
                msg.push('Preencha o título');
            }
            if (!desc.trim()) {
                err = true;
                msg.push('Preencha a comunicação');
            }
            if (err) {
                $.alert({
                    title: "Erro",
                    content: msg.join('<br>')
                });
                return;
            }

            $.ajax({
                method: 'POST',
                url: "?m=communication",
                data: $("#communicationForm").serialize(),
                success: function(resposta) {
                    $.alert({
                        title: "Sucesso",
                        content: resposta,
                        onClose: function() {
                            window.location.reload(true);
                        }
                    });
                },
                error: function(resposta) {
                    $.alert({
                        title: "Erro",
                        content: "Algo deu errado"
                    });
                }
            });
        },

        delete: function(id) {
            $.confirm({
                title: '<?=$AppUI->_("LBL_CONFIRM", UI_OUTPUT_JS); ?>',
                content: '<?=$AppUI->_("LBL_ANSWER_DELETE", UI_OUTPUT_JS); ?>',
                buttons: {
                    yes: {
                        text: 'Sim',
                        action: function () {
                            $.ajax({
                                method: 'POST',
                                url: "?m=communication",
                                data: {
                                    dosql: 'do_communication_aed',
                                    del: 1,
                                    communication_id: id
                                }
                            }).done(function(resposta) {
                                $.alert({
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

    $(document).ready(communication.init);
</script>
