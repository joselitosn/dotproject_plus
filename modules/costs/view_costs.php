<?php
if (!defined('DP_BASE_DIR')) {
    die('You should not access this file directly.');
}

$projectSelected = intval(dPgetParam($_GET, 'project_id'));
$project = new CProject();
$project->load($projectSelected);
$company_id = $project->project_company;
$compartionDateFunction= strtotime($project->project_start_date) != false && strtotime($project->project_end_date) != false;
$compartionEmptyFormat= $project->project_start_date!="0000-00-00 00:00:00" && $project->project_end_date != "0000-00-00 00:00:00";
if ($compartionDateFunction && $compartionEmptyFormat) {
    require_once DP_BASE_DIR . "/modules/costs/costs_functions.php";
    require_once DP_BASE_DIR . "/modules/costs/costs.class.php";
    $cost_id = intval(dPgetParam($_GET, 'cost_id', 0));

    $perms = & $AppUI->acl();

    $q = new DBQuery;
    $q->clear();
    $q->addQuery('*');
    $q->addTable('costs');
    $q->addWhere('cost_project_id = ' . $projectSelected);

// check if this record has dependancies to prevent deletion
    $msg = '';
// load the record data
    $obj = null;
    if ((!db_loadObject($q->prepare(), $obj)) && ($cost_id > 0)) {
        $AppUI->setMsg('Estimative Costs');
        $AppUI->setMsg("invalidID", UI_MSG_ERROR, true);
        $AppUI->redirect();
    }

    /* Funcao para inserir na tabela de custos  */
    insertCostValues($projectSelected);

    $whereProject = '';
    if ($projectSelected != null) {
        $whereProject = ' and cost_project_id=' . $projectSelected;
    }

    /* transform date to dd/mm/yyyy */
    $date_begin = intval($obj->cost_date_begin) ? new CDate($obj->cost_date_begin) : null;
    $date_end = intval($obj->cost_date_end) ? new CDate($obj->cost_date_end) : null;
    $df = $AppUI->getPref('SHDATEFORMAT');

// Get humans estimatives
    $humanCost = getResources("Human", $whereProject);

// Get non humans estimatives
    $notHumanCost = getResources("Non-Human", $whereProject);

?>
<h4><?=$AppUI->_("5LBLCUSTO",UI_OUTPUT_HTML)?></h4>
<hr>
<div class="row">
    <div class="col-md-12 text-right">
        <button type="button" class="btn btn-sm btn-secondary" onclick="costs.showBudget(<?=$projectSelected?>)">
            <?=$AppUI->_("LBL_PROJECT_BUDGET")?>
        </button>
        <a class="btn btn-sm btn-secondary" href="?m=companies&a=view&company_id=<?=$company_id?>&tab=3">
            <?=$AppUI->_("LBL_CONFIG_RH")?>
        </a>
        <button type="button" class="btn btn-sm btn-secondary" onclick="costs.nhr.new(<?=$projectSelected?>)">
            <?=$AppUI->_("LBL_INCLUDE_NON_HUMAN_RESOURCE")?>
        </button>
    </div>
</div>

<!-- ############################## ESTIMATIVAS CUSTOS HUMANOS ############################################ -->
<br>

<div class="card inner-card">
    <div class="card-body">
        <h5 class="card-title"><?=$AppUI->_("Human Resource Estimative")?></h5>
        <h6 class="card-subtitle mb-2 text-muted"><?=$AppUI->_("LBL_COST_HUMAN_RESOURCE_HELP", UI_OUTPUT_JS)?></h6>
        <hr>
        <small><?=$AppUI->_("LBL_RH_AUTOMATICALLY_ADDED_COST_BASELINE")?></small>
        <br>
        <br>

        <div class="row">
            <div class="col-md-12">
                <table class="table table-sm table-bordered">
                    <thead class="thead-dark">
                        <tr>
                            <th width="40%"><?=$AppUI->_('Name')?></th>
                            <th><?=$AppUI->_('Date Begin')?></th>
                            <th><?=$AppUI->_('Date End')?></th>
                            <th width="10%"><?=$AppUI->_('Hours/Month')?></th>
                            <th width="15%"><?=$AppUI->_('Hour Cost')?>  &nbsp;(<?=dPgetConfig("currency_symbol")?>)</th>
                            <th ><?=$AppUI->_("Total Cost")?>&nbsp;(<?=dPgetConfig("currency_symbol")?>)</th>
                            <th width="3%"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            foreach ($humanCost as $row) {
                                $date_begin = intval($row['cost_date_begin']) ? new CDate($row['cost_date_begin']) : null;
                                $date_end = intval($row['cost_date_end']) ? new CDate($row['cost_date_end']) : null;
                                ?>
                                <tr>
                                    <td><?php echo $row['cost_description']; ?></td>
                                    <td><?php echo $date_begin ? $date_begin->format($df) : ''; ?></td>
                                    <td><?php echo $date_end ? $date_end->format($df) : ''; ?></td>
                                    <td class="text-center"><?php echo $row['cost_quantity']; ?></td>
                                    <td class="text-right"><?php echo number_format($row['cost_value_unitary'], 2, ',', '.'); ?></td>
                                    <td class="text-right"><?php echo number_format($row['cost_value_total'], 2, ',', '.'); ?></td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-xs btn-secondary" onclick="costs.hr.edit(<?=$row['cost_id']?>,<?=$projectSelected?>)">
                                            <i class="far fa-edit"></i>
                                        </button>
                                    </td>
                                </tr>
                                <?php
                                $sumH = $sumH + $row['cost_value_total'];
                            }
                        ?>
                        <tr>
                            <td class="text-right" colspan="6"> <b><?php echo $AppUI->_("Subtotal Human Estimatives"); ?> &nbsp;(<?php echo dPgetConfig("currency_symbol") ?>):  </b> </td>
                            <td class="text-right"><b><?php echo number_format($sumH, 2, ',', '.'); ?></b></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- ############################## ESTIMATIVAS CUSTOS NAO HUMANOS ############################################ -->
<br>

<div class="card inner-card">
    <div class="card-body">
        <h5 class="card-title"><?=$AppUI->_("Non-Human Resource Estimative")?></h5>

        <div class="row">
            <div class="col-md-12">
                <table class="table table-sm table-bordered">
                    <thead class="thead-dark">
                        <tr>
                            <th width="40%"><?php echo $AppUI->_('Description')?></th>
                            <th><?=$AppUI->_('Date Begin')?></th>
                            <th><?=$AppUI->_('Date End')?></th>
                            <th width="10%"><?=$AppUI->_('Quantity')?></th>
                            <th width="15%"><?=$AppUI->_('Unitary Cost')?>  &nbsp;(<?=dPgetConfig("currency_symbol") ?>)</th>
                            <th><?=$AppUI->_('Total Cost')?> &nbsp;(<?=dPgetConfig("currency_symbol") ?>)</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            foreach ($notHumanCost as $row) {
                                /* transform date to dd/mm/yyyy */
                                $date_begin = intval($row['cost_date_begin']) ? new CDate($row['cost_date_begin']) : null;
                                $date_end = intval($row['cost_date_end']) ? new CDate($row['cost_date_end']) : null;
                                $obj = new CCosts();
                                $canDelete = $obj->canDelete($msg, $row['cost_id']);
                                ?>
                                <tr>
                                    <td><?=$row['cost_description']?></td>
                                    <td><?=$date_begin ? $date_begin->format($df) : ''?></td>
                                    <td><?=$date_end ? $date_end->format($df) : ''?></td>
                                    <td class="text-center"><?=$row['cost_quantity']?></td>
                                    <td class="text-right"><?=number_format($row['cost_value_unitary'], 2, ',', '.')?></td>
                                    <td class="text-right"><?=number_format($row['cost_value_total'], 2, ',', '.')?></td>
                                    <td class="text-center" width="<?=$canDelete ? '8%' : '3%'?>">
                                        <button type="button" class="btn btn-xs btn-danger" onclick="costs.nhr.delete(<?=$row['cost_id']?>)">
                                            <i class="far fa-trash-alt"></i>
                                        </button>
                                        <?php
                                            if ($canDelete) {
                                            ?>
                                                <button type="button" class="btn btn-xs btn-secondary" onclick="costs.nhr.edit(<?=$row['cost_id']?>,<?=$projectSelected?>)">
                                                    <i class="far fa-edit"></i>
                                                </button>
                                            <?php
                                            }
                                        ?>
                                    </td>
                                </tr>
                                <?php
                                $sumNH = $sumNH + $row['cost_value_total'];
                            }
                        ?>
                        <tr>
                            <td class="text-right" colspan="6"> <b><?=$AppUI->_("Subtotal Not Human Estimatives")?> &nbsp;(<?=dPgetConfig("currency_symbol") ?>): </b> </td>
                            <td class="text-right"><b><?=number_format($sumNH, 2, ',', '.')?></b></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>
<br>
<!-- MODAL EDIT HUMMAN COSTS -->
<div id="hrCostModal" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Alterar custo - Recurso humano</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="<?=$AppUI->_("LBL_CLOSE")?>">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body cost-modal">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal"><?=$AppUI->_("LBL_CLOSE")?></button>
                <button type="button" class="btn btn-primary btn-sm" onclick="costs.hr.save()"><?=$AppUI->_("LBL_SAVE")?></button>
            </div>
        </div>
    </div>
</div>

<!-- MODAL ADD/EDIT NON HUMMAN COSTS -->
<div id="nhrCostModal" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="<?=$AppUI->_("LBL_CLOSE")?>">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body nhr-cost-modal">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal"><?=$AppUI->_("LBL_CLOSE")?></button>
                <button type="button" class="btn btn-primary btn-sm" onclick="costs.nhr.save()"><?=$AppUI->_("LBL_SAVE")?></button>
            </div>
        </div>
    </div>
</div>

<!-- MODAL BUDGET -->
<div id="budgetModal" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?=$AppUI->_("LBL_PROJECT_BUDGET")?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="<?=$AppUI->_("LBL_CLOSE")?>">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="budget-modal"></div>
                <div class="reserva-gerencial-form">

                </div>
                <div class="reserva-contingencia-form">
                    dl;g,dlg,
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light btn-sm" id="btnBackBudget" onclick="costs.backToBudget(<?=$projectSelected?>)">Voltar</button>
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal"><?=$AppUI->_("LBL_CLOSE")?></button>
                <button type="button" class="btn btn-primary btn-sm" id="btSaveBudgetReserve" onclick="costs.saveReserve()"><?=$AppUI->_("LBL_SAVE")?></button>
                <button type="button" class="btn btn-primary btn-sm" id="btSaveBudgetContReserve" onclick="costs.saveContingencyReserve()"><?=$AppUI->_("LBL_SAVE")?></button>
            </div>
        </div>
    </div>
</div>

<script>
    var costs = {

        init: function () {

        },

        backToBudget: function (projectId) {
            $.ajax({
                type: "get",
                url: "?m=costs&template=view_budget&project_id="+projectId
            }).done(function(response) {
                $('#budgetModal').find('h5').html('Orçamento');
                $('.reserva-gerencial-form').html('').hide();
                $('.reserva-contingencia-form').html('').hide();
                $('#btnBackBudget').hide();
                $('#btSaveBudgetReserve').hide();
                $('#btSaveBudgetContReserve').hide();
                $('.budget-modal').html(response).show();
            });
        },

        showBudget: function (projectId) {
            $.ajax({
                type: "get",
                url: "?m=costs&template=view_budget&project_id="+projectId
            }).done(function(response) {
                var modal = $('#budgetModal');
                modal.on('hidden.bs.modal', function () {
                    modal.off('hidden.bs.modall');
                    $('.reserva-gerencial-form').hide();
                    $('.reserva-contingencia-form').hide();
                    $('#btnBackBudget').hide();
                    $('#btSaveBudgetReserve').hide();
                    $('#btSaveBudgetContReserve').hide();
                    $('.budget-modal').show();
                });
                $('.reserva-gerencial-form').hide();
                $('.reserva-contingencia-form').hide();
                $('#btnBackBudget').hide();
                $('#btSaveBudgetReserve').hide();
                $('#btSaveBudgetContReserve').hide();
                $('.budget-modal').html(response);
                modal.modal();
            });
        },

        editReserve: function (budgetId, projectId) {
            $.ajax({
                type: "get",
                url: "?m=costs&template=addedit_budget&budget_id="+budgetId+"&project_id="+projectId
            }).done(function(response) {
                $('#budgetModal').find('h5').html('Orçamento - Reserva Gerencial');
                $('.budget-modal').hide();
                $('#btnBackBudget').show();
                $('#btSaveBudgetReserve').show();
                $('#btSaveBudgetContReserve').hide();
                $('.reserva-gerencial-form').html(response).show();
            });

        },

        saveReserve: function () {

            var perc = $('#budget_reserve_management').val();

            if (!perc || Number.isNaN(parseInt(perc)) || parseInt(perc) < 0) {
                $.alert({
                    icon: "far fa-times-circle",
                    type: "red",
                    title: "Erro",
                    content: "Percentual inválido"
                });
                return;
            }

            $.ajax({
                method: 'POST',
                url: "?m=costs",
                data: $("#managReserveForm").serialize(),
                success: function(resposta) {
                    $.alert({
                        icon: "far fa-check-circle",
                        type: "green",
                        title: "Sucesso",
                        content: resposta,
                        onClose: function() {
                            costs.backToBudget(<?=$projectSelected?>)
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

        editContingencyReserve: function (budgetId, projectId) {
            $.ajax({
                type: "get",
                url: "?m=costs&template=addedit_budget_reserve&budget_reserve_id="+budgetId+"&project_id="+projectId
            }).done(function(response) {
                $('#budgetModal').find('h5').html('Orçamento - Reserva de contingência');
                $('.budget-modal').hide();
                $('#btnBackBudget').show();
                $('#btSaveBudgetReserve').hide();
                $('#btSaveBudgetContReserve').show();
                $('.reserva-contingencia-form').html(response).show();
            });
        },

        saveContingencyReserve: function () {
            var dateStart = $('#date0').val();
            var dateEnd = $('#date1').val();
            var impact = $('#budget_reserve_financial_impact').val();

            var msg = [];
            var err = false;

            if (!impact) {
                err = true;
                msg.push('Preencha o valor do impacto financeiro');
            } else if (Number.isNaN(parseInt(impact)) || parseInt(impact) < 0) {
                err = true;
                msg.push('Valor do impacto financeiro é inválido');
            }
            if (!dateStart) {
                err = true;
                msg.push('Preencha a data de início');
            } else if (!moment(dateStart, 'DD/MM/YYYY', true).isValid()) {
                err = true;
                msg.push('Data de início inválida');
            }
            if (!dateEnd) {
                err = true;
                msg.push('Preencha a data de fim');
            } else if (!moment(dateEnd, 'DD/MM/YYYY', true).isValid()) {
                err = true;
                msg.push('Data de fim inválida');
            }

            var projectEndDate = $('#projectEndDate').val();
            var arrProjectEndDate = projectEndDate.split('-');
            var objProjectEndDate = new Date(arrProjectEndDate[0], arrProjectEndDate[1]-1, arrProjectEndDate[2]);

            var arrDateStart = dateStart.split('/');
            var arrDateEnd = dateEnd.split('/');

            var objDateStart = new Date(arrDateStart[2], arrDateStart[1]-1, arrDateStart[0]);
            var objDateEnd = new Date(arrDateEnd[2], arrDateEnd[1]-1, arrDateEnd[0]);

            if(objDateEnd > objProjectEndDate) {
                err = true;
                msg.push("<?=$AppUI->_("LBL_VALIDATION_DATE_CONTINGENCY_PROJECT", UI_OUTPUT_JS)?>");
            }
            if (objDateStart > objDateEnd) {
                err = true;
                msg.push("A data de início não pode ser maior que a data de fim");
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
                url: "?m=costs",
                data: $("#contingencyReserveForm").serialize(),
                success: function(resposta) {
                    $.alert({
                        icon: "far fa-check-circle",
                        type: "green",
                        title: "Sucesso",
                        content: resposta,
                        onClose: function() {
                            costs.backToBudget(<?=$projectSelected?>)
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
        
        hr: {
            edit: function (costId, projectId) {
                $.ajax({
                    type: "get",
                    url: "?m=costs&template=addedit_costs&cost_id="+costId+"&project_id="+projectId
                }).done(function(response) {
                    var modal = $('#hrCostModal');
                    modal.on('hidden.bs.modal', function () {
                       modal.off('hidden.bs.modall');
                        $('.cost-modal').html('');
                    });
                    $('.cost-modal').html(response);
                    modal.modal();
                });
            },
            save: function () {
                var dateStart = $('#date0').val();
                var dateEnd = $('#date1').val();
                var hours = $('#cost_quantity').val();

                var msg = [];
                var err = false;

                if (!dateStart) {
                    err = true;
                    msg.push('Preencha a data de início');
                } else if (!moment(dateStart, 'DD/MM/YYYY', true).isValid()) {
                    err = true;
                    msg.push('Data de início inválida');
                }
                if (!dateEnd) {
                    err = true;
                    msg.push('Preencha a data de fim');
                } else if (!moment(dateEnd, 'DD/MM/YYYY', true).isValid()) {
                    err = true;
                    msg.push('Data de fim inválida');
                }
                if (!hours) {
                    err = true;
                    msg.push('Preencha a quantidade de horas');
                }

                var projectEndDate = $('#projectEndDate').val();
                var arrProjectEndDate = projectEndDate.split('-');
                var objProjectEndDate = new Date(arrProjectEndDate[0], arrProjectEndDate[1]-1, arrProjectEndDate[2]);

                var arrDateStart = dateStart.split('/');
                var arrDateEnd = dateEnd.split('/');

                var objDateStart = new Date(arrDateStart[2], arrDateStart[1]-1, arrDateStart[0]);
                var objDateEnd = new Date(arrDateEnd[2], arrDateEnd[1]-1, arrDateEnd[0]);

                if(objDateEnd > objProjectEndDate) {
                    err = true;
                    msg.push("<?=$AppUI->_("LBL_VALIDATION_DATE_CONTINGENCY_PROJECT", UI_OUTPUT_JS)?>");
                }
                if (objDateStart > objDateEnd) {
                    err = true;
                    msg.push("A data de início não pode ser maior que a data de fim");
                }
                if (hours == 0 || hours < 0) {
                    err = true;
                    msg.push("Favor informe um valor válido de horas por mês (maior do que 0)");
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
                sumTotalValue();

                $.ajax({
                    method: 'POST',
                    url: "?m=costs",
                    data: $("#hrCostsForm").serialize(),
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
            }
        },

        nhr: {
            new: function (projectId) {
                $.ajax({
                    type: "get",
                    url: "?m=costs&template=addedit_costs_not_human&project_id="+projectId
                }).done(function(response) {
                    var modal = $('#nhrCostModal');
                    modal.on('hidden.bs.modal', function () {
                        modal.off('hidden.bs.modal');
                        $('.cost-modal').html('');
                    });
                    modal.find('h5').html('Adicionar custo - Recurso não humano');
                    $('.nhr-cost-modal').html(response);
                    modal.modal();
                });
            },

            edit: function (costId, projectId) {
                $.ajax({
                    type: "get",
                    url: "?m=costs&template=addedit_costs_not_human&cost_id="+costId+"&project_id="+projectId
                }).done(function(response) {
                    var modal = $('#nhrCostModal');
                    modal.on('hidden.bs.modal', function () {
                        modal.off('hidden.bs.modal');
                        $('.cost-modal').html('');
                    });
                    modal.find('h5').html('Alterar custo - Recurso não humano');
                    $('.nhr-cost-modal').html(response);
                    modal.modal();
                });
            },

            delete: function (costId) {
                $.confirm({
                    title: 'Excluir Custo',
                    content: 'Você tem certeza de que quer excluir este custo?',
                    buttons: {
                        yes: {
                            text: 'Sim',
                            action: function () {
                                $.ajax({
                                    method: 'POST',
                                    url: "?m=costs",
                                    data: {
                                        dosql: 'do_costs_aed',
                                        del: 1,
                                        cost_id: costId
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

            save: function () {
                var name = $('input[name=cost_description]').val();
                var qtd = $('input[name=cost_quantity]').val();
                var dateStart = $('#date0').val();
                var dateEnd = $('#date1').val();
                var uniValue = $('#cost_value_unitary').val();

                var msg = [];
                var err = false;

                if (!name) {
                    err = true;
                    msg.push('Preencha o nome');
                }
                if (!qtd) {
                    err = true;
                    msg.push('Informe a quantidade');
                } else {
                    if (Number.isNaN(parseInt(qtd)) || parseInt(qtd) <= 0) {
                        err = true;
                        msg.push('A quantidade deve ser maior que 0');
                    }
                }
                if (!dateStart) {
                    err = true;
                    msg.push('Preencha a data de início');
                } else if (!moment(dateStart, 'DD/MM/YYYY', true).isValid()) {
                    err = true;
                    msg.push('Data de início inválida');
                }
                if (!dateEnd) {
                    err = true;
                    msg.push('Preencha a data de fim');
                } else if (!moment(dateEnd, 'DD/MM/YYYY', true).isValid()) {
                    err = true;
                    msg.push('Data de fim inválida');
                }
                if (!uniValue) {
                    err = true;
                    msg.push('Informe valor unitário');
                } else {
                    if (Number.isNaN(parseInt(uniValue)) || parseInt(uniValue) <= 0) {
                        err = true;
                        msg.push('O valor unitário deve ser maior que 0');
                    }
                }

                var projectEndDate = $('#projectEndDate').val();
                var arrProjectEndDate = projectEndDate.split('-');
                var objProjectEndDate = new Date(arrProjectEndDate[0], arrProjectEndDate[1]-1, arrProjectEndDate[2]);

                var arrDateStart = dateStart.split('/');
                var arrDateEnd = dateEnd.split('/');

                var objDateStart = new Date(arrDateStart[2], arrDateStart[1]-1, arrDateStart[0]);
                var objDateEnd = new Date(arrDateEnd[2], arrDateEnd[1]-1, arrDateEnd[0]);

                if(objDateEnd > objProjectEndDate) {
                    err = true;
                    msg.push("<?=$AppUI->_("LBL_VALIDATION_DATE_CONTINGENCY_PROJECT", UI_OUTPUT_JS)?>");
                }
                if (objDateStart > objDateEnd) {
                    err = true;
                    msg.push("A data de início não pode ser maior que a data de fim");
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
                    url: "?m=costs",
                    data: $("#nonHrCostForm").serialize(),
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
            }
        }
    };

    $(document).ready(costs.init);

</script>
<?php
    } else {
    ?>
        <div class="row">
            <div class="col-md-12">
                <div class="alert alert-secondary" role="alert">
                    Para realizar o planejamento de custos, o projeto precisa ter suas datas de início e fim estimadas. Por favor, insira estas informações por meio do formulário do projeto antes de continuar. Atenção! Se estas informações ainda não foram preenchidas, talvez o termo de abertura ainda não tenha sido autorizado.
                </div>
            </div>
        </div>
    <?php
    }
?>
