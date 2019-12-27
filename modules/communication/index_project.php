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

// list of channels
$channels = new DBQuery();
$channels->addQuery('c.*');
$channels->addTable('communication_channel', 'c');
$channels = $channels->loadList();

// list of frequencies
$frequencies = new DBQuery();
$frequencies->addQuery('f.*');
$frequencies->addTable('communication_frequency', 'f');
$frequencies = $frequencies->loadList();
?>

<h4><?=$AppUI->_('LBL_PROJECT_COMMUNICATION');?></h4>
<hr>

<div class="row">
    <div class="col-md-12 text-right">
        <a class="btn btn-sm btn-secondary" href="javascript:void(0)" onclick="communication.new(<?=$project_id?>)">
            <?=ucfirst($AppUI->_("LBL_NEW_COMMUNICATION"))?>
        </a>
        <a class="btn btn-sm btn-secondary" href="javascript:void(0)" data-toggle="modal" data-target="#communicationChannelModal">
            <?=ucfirst($AppUI->_("LBL_NEW_CCHANNEL"))?>
        </a>
        <a class="btn btn-sm btn-secondary" href="javascript:void(0)" data-toggle="modal" data-target="#communicationFrequencyModal">
            <?=ucfirst($AppUI->_("LBL_NEW_CFREQUENCY"))?>
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

<!-- MODAL ADD/EDIT CHANNEL -->
<div id="communicationChannelModal" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Adicionar canal</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="<?=$AppUI->_("LBL_CLOSE")?>">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body communication-channel-list">
                <div class="row">
                    <div class="col-md-12 text-right">
                        <button type="button" class="btn btn-secondary btn-sm" onclick="communication.newChannel()">Adicionar</button>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-sm table-bordered">
                            <thead class="thead-dark">
                                <tr>
                                    <th width="89%">Canal</th>
                                    <th width="11%"></th>
                                </tr>
                            </thead>
                            <tbody id="tableChannelList">
                            <?php
                                foreach ($channels as $registro) {
                                    ?>
                                    <tr id="row_channel_<?=$registro['communication_channel_id']?>">
                                        <td><?=$registro['communication_channel']?></td>
                                        <td>
                                        <button type="button" class="btn btn-xs btn-secondary"
                                            onclick="communication.editChannel(<?=$registro['communication_channel_id']?>)">
                                            <i class="far fa-edit"></i>
                                        </button>
                                        <button type="button" class="btn btn-xs btn-danger"
                                                onclick="communication.deleteChannel(<?=$registro['communication_channel_id']?>)">
                                            <i class="far fa-trash-alt"></i>
                                        </button>
                                        </td>
                                    </tr>
                                    <?php
                                }
                            ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-body communication-channel-form">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light btn-sm" id="communication_channelBack" onclick="communication.backToChannelList()">Voltar</button>
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal"><?=$AppUI->_("LBL_CLOSE")?></button>
                <button type="button" class="btn btn-primary btn-sm" id="communication_saveChannel" onclick="communication.saveChannel()"><?=$AppUI->_("LBL_SAVE")?></button>
            </div>
        </div>
    </div>
</div>

<!-- MODAL ADD/EDIT FREQUENCY -->
<div id="communicationFrequencyModal" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Adicionar frequência</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="<?=$AppUI->_("LBL_CLOSE")?>">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body communication-frequency-list">
                <div class="row">
                    <div class="col-md-12 text-right">
                        <button type="button" class="btn btn-secondary btn-sm" onclick="communication.newFrequency()">Adicionar</button>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-sm table-bordered">
                            <thead class="thead-dark">
                                <tr>
                                    <th width="78%">Frequência</th>
                                    <th width="11%">Com data</th>
                                    <th width="11%"></th>
                                </tr>
                            </thead>
                            <tbody id="tableFrequencyList">
                            <?php
                                foreach ($frequencies as $registro) {
                                    ?>
                                    <tr id="row_frequency_<?=$registro['communication_frequency_id']?>">
                                        <td><?=$registro['communication_frequency']?></td>
                                        <td><?=$registro['communication_frequency_hasdate']?></td>
                                        <td>
                                        <button type="button" class="btn btn-xs btn-secondary"
                                            onclick="communication.editFrequency(<?=$registro['communication_frequency_id']?>)">
                                            <i class="far fa-edit"></i>
                                        </button>
                                        <button type="button" class="btn btn-xs btn-danger"
                                                onclick="communication.deleteFrequency(<?=$registro['communication_frequency_id']?>)">
                                            <i class="far fa-trash-alt"></i>
                                        </button>
                                        </td>
                                    </tr>
                                    <?php
                                }
                            ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-body communication-frequency-form">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light btn-sm" id="communication_frequencyBack" onclick="communication.backToFrequencyList()">Voltar</button>
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal"><?=$AppUI->_("LBL_CLOSE")?></button>
                <button type="button" class="btn btn-primary btn-sm" id="communication_saveFrequency" onclick="communication.saveFrequency()"><?=$AppUI->_("LBL_SAVE")?></button>
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
            $('#communicationChannelModal').on('hidden.bs.modal', function() {
                $(this).find('h5').html('Canais de comunicação');
                $('.communication-channel-list').show();
                $('.communication-channel-form').hide();
                $('#communication_channelBack').hide();
                $('#communication_saveChannel').hide();
            });
            $('#communicationFrequencyModal').on('hidden.bs.modal', function() {
                $(this).find('h5').html('Frequências de comunicação');
                $('.communication-frequency-list').show();
                $('.communication-frequency-form').hide();
                $('#communication_frequencyBack').hide();
                $('#communication_saveFrequency').hide();
            });
            
            $('#communication_channelBack').hide();
            $('#communication_saveChannel').hide();
            $('.communication-channel-form').hide();
            
            $('#communication_frequencyBack').hide();
            $('#communication_saveFrequency').hide();
            $('.communication-frequency-form').hide();
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
                    icon: "far fa-times-circle",
                    type: "red",
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
        },

        newChannel: function() {
            $.ajax({
                type: "get",
                url: "?m=communication&template=addedit_channel"
            }).done(function(response) {
                var modal = $('#communicationChannelModal');
                modal.find('h5').html('Adicionar canal');
                $('#communication_channelBack').show();
                $('#communication_saveChannel').show();
                $('.communication-channel-list').hide();
                $('.communication-channel-form').html(response).show();
            });
        },

        editChannel: function(id) {
            $.ajax({
                type: "get",
                url: "?m=communication&template=addedit_channel&channel_id="+id
            }).done(function(response) {
                var modal = $('#communicationChannelModal');
                modal.find('h5').html('Alterar canal');
                $('#communication_channelBack').show();
                $('#communication_saveChannel').show();
                $('.communication-channel-list').hide();
                $('.communication-channel-form').html(response).show();
            });
        },

        backToChannelList: function() {
            $(this).find('h5').html('Canais de comunicação');
            $('#communication_channelBack').hide();
            $('#communication_saveChannel').hide();
            $('.communication-channel-list').show();
            $('.communication-channel-form').html('').hide();
        },

        deleteChannel: function(id) {
            $.confirm({
                title: 'Excluir canal de comunicação',
                content: 'Deseja realmente excluir este canal de comunicação?',
                buttons: {
                    yes: {
                        text: 'Sim',
                        action: function () {
                            $.ajax({
                                method: 'POST',
                                url: "?m=communication",
                                data: {
                                    dosql: 'do_channel_aed',
                                    del: 1,
                                    channel_id: id
                                }
                            }).done(function(resposta) {
                                resposta = JSON.parse(resposta);
                                var titulo = 'Erro';
                                var icon = 'far fa-times-circle';
                                var type = 'red';
                                if (resposta.sucesso) {
                                    titulo = 'Sucesso';
                                    icon = 'far fa-check-circle';
                                    type = 'green';
                                    $('#row_channel_'+id).remove();
                                }
                                $.alert({
                                    icon: icon,
                                    type: type,
                                    title: titulo,
                                    content: resposta.msg
                                });
                            });
                        },
                    },
                    no: {
                        text: 'Não'
                    }
                }
            });
        },

        saveChannel: function() {
            var name = $('input[name=communication_channel]').val();

            var msg = [];
            var err = false;
            if (!name.trim()) {
                err = true;
                msg.push('Preencha a descrição do canal');
            }
            if (err) {
                $.alert({
                    icon: "far fa-times-circle",
                    type: "red",
                    title: "Erro",
                    content: msg.join('<br>')
                });
                return;
            }

            $.ajax({
                method: 'POST',
                url: "?m=communication",
                data: $("form[name=channelForm]").serialize(),
                success: function(resposta) {
                    resposta = JSON.parse(resposta);
                    if (resposta.erro) {
                        $.alert({
                            icon: "far fa-times-circle",
                            type: "red",
                            title: "Erro",
                            content: resposta.msg
                        });
                    } else {
                        $.alert({
                            icon: "far fa-check-circle",
                            type: "green",
                            title: "Sucesso",
                            content: resposta.msg,
                            onClose: function() {

                                if (resposta.update) {
                                    $('#row_channel_'+resposta.data.id).find('td').first().html(resposta.data.name);
                                } else {
                                    var line = $('<tr id="row_channel_'+resposta.data.id+'"></tr>');
                                    var tdName = $('<td>'+resposta.data.name+'</td>');
                                    var tdActions = $('<td></td>');
                                    var btnEdit = $('<button type="button" class="btn btn-xs btn-secondary"><i class="far fa-edit"></i></button>');
                                    var btnDelete = $('<button type="button" class="btn btn-xs btn-danger"><i class="far fa-trash-alt"></i></button>');

                                    btnEdit.click(function(){
                                        communication.editChannel(resposta.data.id);
                                    });
                                    btnDelete.click(function(){
                                        communication.deleteChannel(resposta.data.id);
                                    });
                                    btnEdit.appendTo(tdActions);
                                    btnDelete.appendTo(tdActions);

                                    tdName.appendTo(line);
                                    tdActions.appendTo(line);
                                    line.appendTo($('#tableChannelList'));
                                }
                                communication.backToChannelList();
                            }
                        });
                    }
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

        newFrequency: function() {
            $.ajax({
                type: "get",
                url: "?m=communication&template=addedit_frequency"
            }).done(function(response) {
                var modal = $('#communicationFrequencyModal');
                modal.find('h5').html('Adicionar frequência');
                $('#communication_frequencyBack').show();
                $('#communication_saveFrequency').show();
                $('.communication-frequency-list').hide();
                $('.communication-frequency-form').html(response).show();
            });
        },

        editFrequency: function(id) {
            $.ajax({
                type: "get",
                url: "?m=communication&template=addedit_frequency&frequency_id="+id
            }).done(function(response) {
                var modal = $('#communicationFrequencyModal');
                modal.find('h5').html('Alterar frequência');
                $('#communication_frequencyBack').show();
                $('#communication_saveFrequency').show();
                $('.communication-frequency-list').hide();
                $('.communication-frequency-form').html(response).show();
            });
        },

        backToFrequencyList: function() {
            $(this).find('h5').html('Frequências de comunicação');
            $('#communication_frequencyBack').hide();
            $('#communication_saveFrequency').hide();
            $('.communication-frequency-list').show();
            $('.communication-frequency-form').html('').hide();
        },

        deleteFrequency: function(id) {
            $.confirm({
                title: 'Excluir frequência de comunicação',
                content: 'Deseja realmente excluir esta frequência de comunicação?',
                buttons: {
                    yes: {
                        text: 'Sim',
                        action: function () {
                            $.ajax({
                                method: 'POST',
                                url: "?m=communication",
                                data: {
                                    dosql: 'do_frequency_aed',
                                    del: 1,
                                    frequency_id: id
                                }
                            }).done(function(resposta) {
                                resposta = JSON.parse(resposta);
                                var titulo = 'Erro';
                                var icon = 'far fa-times-circle';
                                var type = 'red';
                                if (resposta.sucesso) {
                                    titulo = 'Sucesso';
                                    icon = 'far fa-check-circle';
                                    type = 'green';
                                    $('#row_frequency_'+id).remove();
                                }
                                $.alert({
                                    icon: icon,
                                    type: type,
                                    title: titulo,
                                    content: resposta.msg
                                });
                            });
                        },
                    },
                    no: {
                        text: 'Não'
                    }
                }
            });
        },

        saveFrequency: function() {
            var name = $('input[name=communication_frequency]').val();

            var msg = [];
            var err = false;
            if (!name.trim()) {
                err = true;
                msg.push('Preencha a descrição da frequência');
            }
            if (err) {
                $.alert({
                    icon: "far fa-times-circle",
                    type: "red",
                    title: "Erro",
                    content: msg.join('<br>')
                });
                return;
            }

            $.ajax({
                method: 'POST',
                url: "?m=communication",
                data: $("form[name=frequencyForm]").serialize(),
                success: function(resposta) {
                    resposta = JSON.parse(resposta);
                    if (resposta.erro) {
                        $.alert({
                            icon: "far fa-times-circle",
                            type: "red",
                            title: "Erro",
                            content: resposta.msg
                        });
                    } else {
                        $.alert({
                            icon: "far fa-check-circle",
                            type: "green",
                            title: "Sucesso",
                            content: resposta.msg,
                            onClose: function() {

                                if (resposta.update) {
                                    $('#row_frequency_'+resposta.data.id).find('td').first().html(resposta.data.name);
                                    $('#row_frequency_'+resposta.data.id).find('td').first().next().html(resposta.data.hasDate);
                                } else {
                                    var line = $('<tr id="row_frequency_'+resposta.data.id+'"></tr>');
                                    var tdName = $('<td>'+resposta.data.name+'</td>');
                                    var tdDate = $('<td>'+resposta.data.hasDate+'</td>');
                                    var tdActions = $('<td></td>');
                                    var btnEdit = $('<button type="button" class="btn btn-xs btn-secondary"><i class="far fa-edit"></i></button>');
                                    var btnDelete = $('<button type="button" class="btn btn-xs btn-danger"><i class="far fa-trash-alt"></i></button>');

                                    btnEdit.click(function(){
                                        communication.editFrequency(resposta.data.id);
                                    });
                                    btnDelete.click(function(){
                                        communication.deleteFrequency(resposta.data.id);
                                    });
                                    btnEdit.appendTo(tdActions);
                                    btnDelete.appendTo(tdActions);

                                    tdName.appendTo(line);
                                    tdDate.appendTo(line);
                                    tdActions.appendTo(line);
                                    line.appendTo($('#tableFrequencyList'));
                                }
                                communication.backToFrequencyList();
                            }
                        });
                    }
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

    }

    $(document).ready(communication.init);
</script>
