<?php
$project_id=$_GET["project_id"];
$q = new DBQuery();
$q->addQuery('*');
$q->addTable('risks');
$q->addWhere('risk_project='.$project_id);
$q->addOrder('risk_id');
$q->setLimit(100);
$list1 = $q->loadList();
//require_once (DP_BASE_DIR . "/modules/risks/translations.php");
require_once DP_BASE_DIR . "/modules/risks/probability_impact_matrix_read.php";
foreach ($list1 as $line) {
    $risk_id = $line['risk_id'];
    $risk_probability = intval($line['risk_probability']);
    $risk_impact = intval($line['risk_impact']);

    $Priority=$impactProbabilityMatrix[$risk_probability][$risk_impact];
    $dbprefix = dPgetConfig('dbprefix', '');
    $consulta = "UPDATE {$dbprefix}risks SET risk_priority = '$Priority' WHERE risk_id = '$risk_id'";
    $resultado = mysql_query($consulta) or die($AppUI->_("LBL_QUERY_FAIL"));
}

$q->clear();
$q->addQuery('user_id');
$q->addQuery('CONCAT( contact_first_name, \' \', contact_last_name)');
$q->addTable('users');
$q->leftJoin('contacts', 'c', 'user_contact = contact_id');
$q->addOrder('contact_first_name, contact_last_name');
$users = $q->loadHashList();

$q->clear();
$q->addQuery('project_id, project_name');
$q->addTable('projects');
$q->addOrder('project_name');
$projects = $q->loadHashList();

$q->clear();
$q->addQuery('task_id, task_name');
$q->addTable('tasks');
$q->addOrder('task_name');
$tasks = $q->loadHashList();

$riskProbability = dPgetSysVal('RiskProbability');
foreach ($riskProbability as $key => $value) {
    $riskProbability[$key] = str_replace("&amp;eacute;", "é", htmlspecialchars($AppUI->_($value)));
}
$riskStatus = dPgetSysVal('RiskStatus');
foreach ($riskStatus as $key => $value) {
    $riskStatus[$key] = str_replace("&amp;atilde;", "ã", htmlspecialchars($AppUI->_($value)));
}
$riskImpact = dPgetSysVal('RiskImpact');
foreach ($riskImpact as $key => $value) {
    $riskImpact[$key] = str_replace("&amp;eacute;", "é", htmlspecialchars($AppUI->_($value)));
}
$riskPotential = dPgetSysVal('RiskPotential');
foreach ($riskPotential as $key => $value) {
    $riskPotential[$key] = str_replace("&amp;atilde;", "ã", htmlspecialchars($AppUI->_($value)));
}
$riskActive = dPgetSysVal('RiskActive');
foreach ($riskActive as $key => $value) {
    $riskActive[$key] = str_replace("&amp;atilde;", "ã", htmlspecialchars($AppUI->_($value)));
}
$riskStrategy = dPgetSysVal('RiskStrategy');
foreach ($riskStrategy as $key => $value) {
    $riskStrategy[$key] = $AppUI->_($value);
}
$riskPriority = dPgetSysVal('RiskPriority');
foreach ($riskPriority as $key => $value) {
    $riskPriority[$key] = str_replace("&amp;eacute;", "é", htmlspecialchars($AppUI->_($value)));
}

$valid_ordering = array(
    'risk_id',
    'risk_name',
    'risk_description',
    'risk_probability',
    'risk_impact',
    'risk_priority',
    'risk_answer_to_risk',
    'risk_status',
    'risk_responsible',
    'risk_project',
    'risk_task',
    'risk_notes',
    'risk_potential_other_projects',
    'risk_lessons_learned',
    `risk_strategy`,
    `risk_prevention_action`,
    `risk_contingency_plan`,
);

$orderdire = $AppUI->getState('RisksIdxOrderDir') ? $AppUI->getState('RisksIdxOrderDir') : 'desc';
if ((isset($_GET['orderbyy'])) && (in_array($_GET['orderbyy'], $valid_ordering))) {
    $orderdire = (($AppUI->getState('RisksIdxOrderDir') == 'asc') ? 'desc' : 'asc');
    $AppUI->setState('RisksIdxOrderBy', $_GET['orderbyy']);
}
$orderbyy = (($AppUI->getState('RisksIdxOrderBy')) ? $AppUI->getState('RisksIdxOrderBy') : 'risk_priority');
$AppUI->setState('RisksIdxOrderDir', $orderdire);


$projectSelected = intval(dPgetParam($_GET, 'project_id'));
$whereProject = '';
if ($projectSelected != null) {
    $whereProject = ' and risk_project=' . $projectSelected;
}
$t = intval(dPgetParam($_GET, 'tab'));
$q->clear();
$q->addQuery('*');
$q->addTable('risks');
$q->addWhere("risk_active = '0' $whereProject");
$q->addOrder($orderbyy . ' ' . $orderdire);
$activeList = $q->loadList();

$q->clear();
$q->addQuery('*');
$q->addTable('risks');
$q->addWhere("risk_active = '1' $whereProject");
$q->addOrder($orderbyy . ' ' . $orderdire);
$inactiveList = $q->loadList();
require_once DP_BASE_DIR . "/modules/risks/risks.class.php";;
?>
<br>
<div class="row">
    <div class="col-md-12">
        <div class="alert alert-secondary" role="alert">
            <h5><?=$AppUI->_('LBL_ACTIVE_RISKS')?></h5>
        </div>
    </div>
</div>

<?php
    foreach ($activeList as $row) {
        $obj = new CRisks();
        $canDelete = $obj->canDelete($msg, $row['risk_id']);
        ?>
        <div class="card inner-card">
            <div class="card-body shrink">
                <div class="row">
                    <div class="col-md-8">
                        <h5>
                            <a id="<?= $row['risk_id'] ?>" data-toggle="collapse"
                               href="#active_risk_details_<?= $row['risk_id'] ?>">
                                <?= $row['risk_name'] ?>
                                <i class="fas fa-caret-down"></i>
                            </a>
                        </h5>
                    </div>
                    <div class="col-md-3 text-right">
                        <?php
                        switch ($row['risk_priority']) {
                            case 0:
                                $labelClass = 'badge badge-success';
                                break;
                            case 1:
                                $labelClass = 'badge badge-warning';
                                break;
                            case 2:
                                $labelClass = 'badge badge-danger';
                                break;
                        }
                        ?>
                        <span class="<?= $labelClass ?>">
                            <?= $AppUI->_('LBL_PRIORITY') . ': ' . $textExpositionFactor[$row['risk_priority']] ?>
                        </span>
                    </div>
                    <div class="col-md-1 text-right">
                        <div class="dropdown">
                            <a href="javascript:void(0)" class="link-primary" role="button" data-toggle="dropdown"
                               aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-bars"></i>
                            </a>

                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink">
                                <a class="dropdown-item" href="javascript:void(0)"
                                   onclick="risks.edit(<?=$row['risk_id']?>,<?=$projectSelected?>)">
                                    <i class="far fa-edit"></i>
                                    Alterar
                                </a>
                                <?php
                                if ($canDelete) {
                                    ?>
                                    <a class="dropdown-item" href="javascript:void(0)"
                                       onclick="risks.delete(<?=$row['risk_id']?>)">
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
                <div id="active_risk_details_<?= $row['risk_id'] ?>" class="collapse">
                    <div class="row">
                        <table class="table table-sm no-border">
                            <tr>
                                <th class="text-right" width="15%"><?= $AppUI->_('LBL_DESCRIPTION') ?>:</th>
                                <td colspan="5"><?= $row['risk_description'] ?></td>
                            </tr>
                            <tr>
                                <th class="text-right" width="15%"><?= $AppUI->_("LBL_PROBABILITY") ?>:</th>
                                <td width="18%"><?= $riskProbability[$row['risk_probability']] ?></td>
                                <th class="text-right" width="15%"><?php echo $AppUI->_("LBL_IMPACT"); ?>:</th>
                                <td width="18%"><?= $riskImpact[$row['risk_impact']] ?></td>
                                <th class="text-right" width="15%"><?php echo $AppUI->_("LBL_STATUS"); ?>:</th>
                                <td width="19%"><?= $riskStatus[$row['risk_status']] ?></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
?>


<br>
<div class="row">
    <div class="col-md-12">
        <div class="alert alert-secondary" role="alert">
            <h5><?=$AppUI->_('LBL_INACTIVE_RISKS')?></h5>
        </div>
    </div>
</div>
<?php
    foreach ($inactiveList as $row) {
        $obj = new CRisks();
        $canDelete = $obj->canDelete($msg, $row['risk_id']);
        ?>
        <div class="card inner-card">
            <div class="card-body shrink">
                <div class="row">
                    <div class="col-md-8">
                        <h5>
                            <a id="<?= $row['risk_id'] ?>" data-toggle="collapse"
                               href="#active_risk_details_<?=$row['risk_id']?>">
                                <?= $row['risk_name'] ?>
                                <i class="fas fa-caret-down"></i>
                            </a>
                        </h5>
                    </div>
                    <div class="col-md-3 text-right">
                        <?php
                        switch ($row['risk_priority']) {
                            case 0:
                                $labelClass = 'badge badge-success';
                                break;
                            case 1:
                                $labelClass = 'badge badge-warning';
                                break;
                            case 2:
                                $labelClass = 'badge badge-danger';
                                break;
                        }
                        ?>
                        <span class="<?= $labelClass ?>">
                                <?= $AppUI->_('LBL_PRIORITY') . ': ' . $textExpositionFactor[$row['risk_priority']] ?>
                            </span>
                    </div>
                    <div class="col-md-1 text-right">
                        <div class="dropdown">
                            <a href="javascript:void(0)" class="link-primary" role="button" data-toggle="dropdown"
                               aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-bars"></i>
                            </a>

                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink">
                                <a class="dropdown-item" href="javascript:void(0)"
                                   onclick="risks.edit(<?=$row['risk_id']?>,<?=$projectSelected?>)">
                                    <i class="far fa-edit"></i>
                                    Alterar
                                </a>
                                <?php
                                if ($canDelete) {
                                    ?>
                                    <a class="dropdown-item" href="javascript:void(0)"
                                       onclick="risks.delete(<?=$row['risk_id']?>)">
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
                <div id="active_risk_details_<?= $row['risk_id'] ?>" class="collapse">
                    <div class="row">
                        <table class="table table-sm no-border">
                            <tr>
                                <th class="text-right" width="15%"><?= $AppUI->_('LBL_DESCRIPTION') ?>:</th>
                                <td colspan="5"><?= $row['risk_description'] ?></td>
                            </tr>
                            <tr>
                                <th class="text-right" width="15%"><?= $AppUI->_("LBL_PROBABILITY") ?>:</th>
                                <td width="18%"><?= $riskProbability[$row['risk_probability']] ?></td>
                                <th class="text-right" width="15%"><?php echo $AppUI->_("LBL_IMPACT"); ?>:</th>
                                <td width="18%"><?= $riskImpact[$row['risk_impact']] ?></td>
                                <th class="text-right" width="15%"><?php echo $AppUI->_("LBL_STATUS"); ?>:</th>
                                <td width="19%"><?= $riskStatus[$row['risk_status']] ?></td>
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
<div id="riskModal" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="<?=$AppUI->_("LBL_CLOSE")?>">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body risk-modal">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal"><?=$AppUI->_("LBL_CLOSE")?></button>
                <button type="button" class="btn btn-primary btn-sm" onclick="risks.save()"><?=$AppUI->_("LBL_SAVE")?></button>
            </div>
        </div>
    </div>
</div>

<script>
    var risks = {

        init: function() {
            $('a[data-toggle=collapse]').on('click', risks.show);
        },

        show: function (e) {
            const collapseRisk = $(e.target);
            $('#active_risk_details_'+e.target.id).on('shown.bs.collapse', function () {
                collapseRisk.find('i').removeClass('fa-caret-down');
                collapseRisk.find('i').addClass('fa-caret-up');
            });
            $('#active_risk_details_'+e.target.id).on('hidden.bs.collapse', function () {
                collapseRisk.find('i').removeClass('fa-caret-up');
                collapseRisk.find('i').addClass('fa-caret-down');
            });
        },

        delete: function (id) {
            $.confirm({
                title: 'Excluir Risco',
                content: 'Você tem certeza de que quer excluir este risco?',
                buttons: {
                    yes: {
                        text: 'Sim',
                        action: function () {
                            $.ajax({
                                method: 'POST',
                                url: "?m=risks",
                                data: {
                                    dosql: 'do_risks_aed',
                                    del: 1,
                                    risk_id: id
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

        new: function (projectId) {
            $.ajax({
                type: "get",
                url: "?m=risks&template=addedit&project_id="+projectId
            }).done(function(response) {
                var modal = $('#riskModal');
                modal.find('h5').html('Adicionar risco');
                $('.risk-modal').html(response);
                modal.modal();
            });
        },

        edit: function (riskId, projectId) {
            $.ajax({
                type: "get",
                url: "?m=risks&template=addedit&id="+riskId+"&project_id="+projectId
            }).done(function(response) {
                var modal = $('#riskModal');
                modal.find('h5').html('Alterar risco');
                $('.risk-modal').html(response);
                modal.modal();
            });
        },

        save: function () {
            var name = $('input[name=risk_name]').val();
            var desc = $('textarea[name=risk_description]').val();
            var dateStart = $('input[name=risk_start_date]').val();
            var dateEnd = $('input[name=risk_end_date]').val();

            var msg = [];
            var err = false;

            if (!name) {
                err = true;
                msg.push('Preencha o nome');
            }

            if (!desc) {
                err = true;
                msg.push('Preencha a descrição');
            }

            if (dateStart) {
                if (!moment(dateStart, 'DD/MM/YYYY', true).isValid()) {
                    err = true;
                    msg.push('Data de início do período de vigência é inválida');
                }
            }

            if (dateEnd) {
                if (!moment(dateEnd, 'DD/MM/YYYY', true).isValid()) {
                    err = true;
                    msg.push('Data de fim do período de vigência é inválida');
                }
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
                url: "?m=risks",
                data: $("#riskForm").serialize(),
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

        openRisksManagementPlan: function (projectId) {
            $.ajax({
                type: "get",
                url: "?m=risks&template=risk_management_plan&project_id="+projectId
            }).done(function(response) {
                var modal = $('#riskManagementPlanModal');
                modal.on('hidden.bs.modal', function () {
                    $('#riskManagementPlanModal_SaveBtn').off('click');
                });
                $('.risk-management-plan-modal').html(response);
                modal.modal();
            });
        },

        openChecklistAnalisis: function (projectId) {
            $.ajax({
                type: "get",
                url: "?m=risks&template=checklist_risks_model&project_id="+projectId
            }).done(function(response) {
                var modal = $('#riskChecklistAnalisis');
                $('.risk-checklist-analisis-modal').html(response);
                modal.modal();
            });
        },
        
        confirmIdentifiedRisks: function () {
            $.ajax({
                method: 'POST',
                url: "?m=risks",
                data: $('form[name=checklist_analisys]').serialize(),
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

        openWatchList: function (projectId) {
            var url = "?m=risks&template=vw_watchlist&project_id="+projectId;

            var callback = function(response) {
                $('.risk-watch-list-form').html('').hide();
                $('#btnBack_watchList').hide();
                $('#btnSave_watchList').hide();
                var modal = $('#riskWatchList');
                modal.find('h5').html('Lista de observação');
                $('.risk-watch-list-table').html(response);
                $('.risk-watch-list-table').show();
                modal.modal();
            }
            risks.execRequest(url, callback);
        },

        switchEditWatchList: function (riskId, projectId) {
            var url = "?m=risks&template=addedit&id="+riskId+"&project_id="+projectId;

            var callback = function(response) {
                $('#riskWatchList').find('h5').html('Alterar risco');
                $('#btnBack_watchList').show();
                $('#btnSave_watchList').show();
                $('.risk-watch-list-table').hide();
                $('.risk-watch-list-form').html(response).show();
            }
            risks.execRequest(url, callback);
        },

        backToWatchList: function () {
            $('#riskWatchList').find('h5').html('Lista de observação');
            $('#btnBack_watchList').hide();
            $('#btnSave_watchList').hide();
            $('.risk-watch-list-form').html('').hide();
            $('.risk-watch-list-table').show();
        },

        openShortTimeResponseList: function (projectId) {
            var url = "?m=risks&template=vw_near_term_responses_list&project_id="+projectId;

            var callback = function (response) {
                $('.risk-short-time-list-form').html('').hide();
                $('#btnBack_shortTimeList').hide();
                $('#btnSave_shortTimeList').hide();
                var modal = $('#riskShortTimeRespList');
                modal.find('h5').html('Lista de resposta a curto prazo');
                $('.risk-short-time-list-table').html(response);
                $('.risk-short-time-list-table').show();
                modal.modal();
            }
            risks.execRequest(url, callback);
        },

        switchEditShortTerm: function (riskId, projectId) {
            var url = "?m=risks&template=addedit&id="+riskId+"&project_id="+projectId;

            var callback = function(response) {
                $('#riskShortTimeRespList').find('h5').html('Alterar risco');
                $('#btnBack_shortTimeList').show();
                $('#btnSave_shortTimeList').show();
                $('.risk-short-time-list-table').hide();
                $('.risk-short-time-list-form').html(response).show();
            }
            risks.execRequest(url, callback);
        },

        backToShortTimeResponseList: function () {
            $('#riskShortTimeRespList').find('h5').html('Lista de resposta a curto prazo');
            $('#btnBack_shortTimeList').hide();
            $('#btnSave_shortTimeList').hide();
            $('.risk-short-time-list-form').html('').hide();
            $('.risk-short-time-list-table').show();
        },

        openLessonLearntList: function (projectId) {
            var url = "?m=risks&template=vw_lessons_learned_list&project_id="+projectId;

            var callback = function (response) {
                $('.risk-lesson-learnt-list-form').html('').hide();
                $('#btnBack_lessonLearntList').hide();
                $('#btnSave_lessonLearntList').hide();
                var modal = $('#riskLessonLearntList');
                modal.find('h5').html('Lista de lições aprendidas');
                $('.risk-lesson-learnt-list-table').html(response);
                $('.risk-lesson-learnt-list-table').show();
                modal.modal();
            }
            risks.execRequest(url, callback);
        },

        switchEditLessonLearnt: function (riskId, projectId) {
            var url = "?m=risks&template=addedit&id="+riskId+"&project_id="+projectId;

            var callback = function(response) {
                $('#riskLessonLearntList').find('h5').html('Alterar risco');
                $('#btnBack_lessonLearntList').show();
                $('#btnSave_lessonLearntList').show();
                $('.risk-lesson-learnt-list-table').hide();
                $('.risk-lesson-learnt-list-form').html(response).show();
            }
            risks.execRequest(url, callback);
        },

        backToLessonLearntList: function () {
            $('#riskLessonLearntList').find('h5').html('Lista de lições aprendidas');
            $('#btnBack_lessonLearntList').hide();
            $('#btnSave_lessonLearntList').hide();
            $('.risk-lesson-learnt-list-form').html('').hide();
            $('.risk-lesson-learnt-list-table').show();
        },

        openResponsesList: function (projectId) {
            var url = "?m=risks&template=vw_strategys_list&project_id="+projectId;

            var callback = function (response) {
                $('.risk-responses-list-form').html('').hide();
                $('#btnBack_responsesList').hide();
                $('#btnSave_responsesList').hide();
                var modal = $('#riskResponsestList');
                modal.find('h5').html('Lista de resposta aos riscos');
                $('.risk-responses-list-table').html(response);
                $('.risk-responses-list-table').show();
                modal.modal();
            }
            risks.execRequest(url, callback);
        },

        switchResponses: function (riskId, projectId) {
            var url = "?m=risks&template=addedit&id="+riskId+"&project_id="+projectId;

            var callback = function(response) {
                $('#riskResponsestList').find('h5').html('Alterar risco');
                $('#btnBack_responsesList').show();
                $('#btnSave_responsesList').show();
                $('.risk-responses-list-table').hide();
                $('.risk-responses-list-form').html(response).show();
            }
            risks.execRequest(url, callback);
        },

        backToResponsesList: function () {
            $('#riskResponsestList').find('h5').html('Lista de resposta aos riscos');
            $('#btnBack_responsesList').hide();
            $('#btnSave_responsesList').hide();
            $('.risk-responses-list-form').html('').hide();
            $('.risk-responses-list-table').show();
        },

        execRequest: function (url, callback) {
            $.ajax({
                type: "get",
                url: url
            }).done(callback);
        }

    };

    $(document).ready(risks.init);
</script>