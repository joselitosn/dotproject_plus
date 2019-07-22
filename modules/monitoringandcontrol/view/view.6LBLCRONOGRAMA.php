<?php
if (!defined('DP_BASE_DIR')) {
    die('You should not access this file directly.');
}
require_once(DP_BASE_DIR . "/modules/monitoringandcontrol/control/controller_earn_value.class.php");
require_once (DP_BASE_DIR . "/modules/monitoringandcontrol/control/controller_baseline.class.php");
require_once(DP_BASE_DIR . "/modules/monitoringandcontrol/control/controller_util.class.php");

$project_id = dPgetParam($_GET, 'project_id', 0);

ini_set('max_execution_time', 180);
ini_set('memory_limit', $dPconfig['reset_memory_limit']);
global $AppUI;

$titGrafico = $AppUI->_('LBL_GRAF_PRAZO');
$titVP = $AppUI->_('LBL_VALOR_PLANEJADO');
$titVA = $AppUI->_('LBL_VALOR_AGREGADO');

// se a data não estiver setada pega atual senão usa a passada
if (isset($_POST['date_edit']) &&
        eregi("^[0-9]{2}/[0-9]{2}/[0-9]{4}$", $_POST['date_edit']) &&
        checkdate(substr($_POST['date_edit'], 3, 2), substr($_POST['date_edit'], 0, 2), substr($_POST['date_edit'], 7, 4))) {
    $dtAtual = $_POST['date_edit'];
} else {
    $dtAtual = date('d/m/Y');
}
$cmbBaseline = dPgetParam($_POST, 'cmbBaseline');
?>

<script type="text/javascript" src="./modules/monitoringandcontrol/js/util.js"></script>

<div>
    <h4><?=$AppUI->_("6LBLCRONOGRAMA",UI_OUTPUT_HTML)?></h4>
    <hr>
    <div class="row">
        <div class="col-md-12">
            <?php require_once (DP_BASE_DIR . "/modules/timeplanning/view/gantt_chart.php"); ?>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-md-12">
            <button type="button" class="btn btn-secondary btn-sm" data-toggle="modal" data-target="#baseLineModal">
                <?=$AppUI->_("View baselines")?>
            </button>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-md-3">
            <?php
                $controllerUtil = new ControllerUtil();
                $controllerEarnValue = new ControllerEarnValue();

                $vlValorAgregado = $controllerEarnValue->obterValorAgregado($project_id, $dtAtual, $cmbBaseline);
                $vlValorPlanejado = $controllerEarnValue->obterValorPlanejado($project_id, $dtAtual, $cmbBaseline);
                $vlVariacaoCronograma = $controllerEarnValue->obterVariacaoPrazo($project_id, $dtAtual, $cmbBaseline);
                $vlIndiceDesempenho = $controllerEarnValue->obterIndiceDesempenhoPrazo($project_id, $dtAtual, $cmbBaseline);
                $lstDataMinTask = $controllerEarnValue->obterInicioPeriodo($project_id, $cmbBaseline);

                foreach ($lstDataMinTask as $ini) {
                    $dtUtil = new CDate($ini[0]);
                    $dtInicioProjeto = $dtUtil->format('%d/%m/%Y');
                }

                $vlPlanejado = array();
                $vlAgregado = array();
                $dtConsultaArray = array();

                $arDtAtual = explode('/', $dtAtual);
                $diaDtAtual = $arDtAtual[0];
                $mesDtAtual = $arDtAtual[1];
                $anoDtAtual = $arDtAtual[2];

                $arInicioProjeto = explode('/', $dtInicioProjeto);
                $diaInicioProjeto = $arInicioProjeto[0];
                $mesInicioProjeto = $arInicioProjeto[1];
                $anoInicioProjeto = $arInicioProjeto[2];

                $difAno = ($anoDtAtual - $anoInicioProjeto) * 12;
                $difMes = ($mesDtAtual - $mesInicioProjeto) + 1;
                $nPlot = ($difMes + $difAno);

                if ($nPlot <= 12) {
                    array_push($vlPlanejado, 0);
                    array_push($vlAgregado, 0);
                    array_push($dtConsultaArray, $dtInicioProjeto);
                }

                for ($i = 1; $i <= $nPlot; ++$i) {
                    if (($nPlot - $i) > 12) {
                        continue;
                    }

                    $dtConsulta = date('d/m/Y', mktime(0, 0, 0, ($mesInicioProjeto + $i), $diaInicioProjeto, $anoInicioProjeto));

                    if ($controllerUtil->data_to_timestamp($dtConsulta) < $controllerUtil->data_to_timestamp($dtAtual)) {
                        array_push($dtConsultaArray, $dtConsulta);
                        array_push($vlPlanejado, $controllerEarnValue->obterValorPlanejado($project_id, $dtConsulta, $cmbBaseline));
                        array_push($vlAgregado, $controllerEarnValue->obterValorAgregado($project_id, $dtConsulta, $cmbBaseline));
                    } else {
                        if ($dtAtual == $dtInicioProjeto) {
                            continue;
                        }
                        array_push($dtConsultaArray, $dtAtual);
                        array_push($vlPlanejado, $controllerEarnValue->obterValorPlanejado($project_id, $dtAtual, $cmbBaseline));
                        array_push($vlAgregado, $controllerEarnValue->obterValorAgregado($project_id, $dtAtual, $cmbBaseline));
                        break;
                    }
                }
            ?>
            <form name="formdata" id="formdata" method="post">
                <div class="form-group">
                    <label for="<?=$AppUI->_('LBL_BASELINE')?>"><?=$AppUI->_('LBL_BASELINE')?></label>
                    <select name="cmbBaseline" class="form-control form-control-sm" id="cmbBaseline" onchange="submit();">
                        <?php
                            $controllerBaseline = new ControllerBaseline();
                            $lstBaseline = $controllerBaseline->listBaseline($project_id);
                            echo "<option value='0'>" . $AppUI->_('LBL_POSICAO_ATUAL') . "</option>";
                            for ($i = 0; $i < count($lstBaseline); $i++) {
                                if ($cmbBaseline == $lstBaseline[$i][baseline_id]) {
                                    echo "<option value='" . $lstBaseline[$i][baseline_id] . "' selected>" . $lstBaseline[$i][baseline_version] . "</option>";
                                } else {
                                    echo "<option value='" . $lstBaseline[$i][baseline_id] . "' >" . $lstBaseline[$i][baseline_version] . "</option>";
                                }
                            }
                        ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="<?=$AppUI->_('LBL_DATA')?>"><?=$AppUI->_('LBL_DATA')?></label>
                    <input type="text"
                        class="form-control form-control-sm"
                        name="date_edit"
                        id="date_edit"
                        value="<?=$dtAtual?>"
                        onchange="submit();"
                        maxlength="10"
                        onkeyup="formatadata(this, event)" />
                </div>

                <div class="form-group">
                    <label for="<?=$AppUI->_('LBL_VALOR_PLANEJADO')?>"><?=$AppUI->_('LBL_VALOR_PLANEJADO')?> (<?=$AppUI->_('LBL_VP')?>)</label>
                    <input type="text" class="form-control form-control-sm" name="vp" size="15" readonly="readonly" value="<?php
                        if (isset($vlValorPlanejado)) {
                            echo number_format($vlValorPlanejado, 2, ',', '.');
                        }
                        else
                            echo "";
                    ?>" />
                </div>

                <div class="form-group">
                    <label for="<?=$AppUI->_('LBL_VALOR_AGREGADO')?>"><?=$AppUI->_('LBL_VALOR_AGREGADO')?> (<?=$AppUI->_('LBL_VA')?>)</label>
                    <input type="text" class="form-control form-control-sm" name="va" size="15" readonly="readonly" value="<?php
                        if (isset($vlValorAgregado)) {
                            echo number_format($vlValorAgregado, 2, ',', '.');
                        }
                        else
                            echo "";
                        ?>" />
                </div>

                <div class="form-group">
                    <label for="<?=$AppUI->_('LBL_VARIACAO_PRAZO')?>"><?=$AppUI->_('LBL_VARIACAO_PRAZO')?> (<?=$AppUI->_('LBL_VPR')?>)</label>
                    <input type="text" class="form-control form-control-sm" name="vc" size="15" readonly="readonly" value="<?php
                        if (isset($vlVariacaoCronograma)) {
                            echo number_format($vlVariacaoCronograma, 2, ',', '.');
                        }
                        else
                            echo "";
                        ?>" />
                </div>

                <div class="form-group">
                    <label for="<?=$AppUI->_('LBL_INDICE_PRAZO')?>"><?=$AppUI->_('LBL_INDICE_PRAZO')?> (<?=$AppUI->_('LBL_IDP')?>)</label>
                    <input type="text" class="form-control form-control-sm" name="idp" size="15" readonly="readonly" value="<?php echo round($vlIndiceDesempenho, 2); ?>" />
                </div>

            </form>
        </div>
        <div class="col-md-9 text-center">
            <?php
                if ((!empty($vlPlanejado) || !isset($vlPlanejado)) || (!empty($vlAgregado) || !isset($vlAgregado))) {
                    $url = './modules/monitoringandcontrol/grafico/line_Graph_Schedule.php?titGrafico=' . urlencode(serialize($titGrafico)) . '&titVP=' . urlencode(serialize($titVP)) . '&titVA=' . urlencode(serialize($titVA)) . '&dtConsultaArray=' . urlencode(serialize($dtConsultaArray)) . '&vlPlanejado=' . urlencode(serialize($vlPlanejado)) . '&vlAgregado=' . urlencode(serialize($vlAgregado));
                } else {
                    $url = './modules/monitoringandcontrol/grafico/line_Graph_Schedule.php';
                }
            ?>
            <img src="<?=$url?>">
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <p><?php echo $AppUI->_('LBL_IDP'); ?> < 1: <?php echo $AppUI->_('LBL_IDP_MENOR'); ?>
                <br><?php echo $AppUI->_('LBL_IDP'); ?> > 1: <?php echo $AppUI->_('LBL_IDP_MAIOR'); ?>
                <br><?php echo $AppUI->_('LBL_IDP'); ?> = 1: <?php echo $AppUI->_('LBL_IDP_IGUAL'); ?></p>
        </div>
    </div>
</div>
<!-- MODAL MINUTES -->
<div id="baseLineModal" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?=$AppUI->_("LBL_BASELINE")?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="<?=$AppUI->_("LBL_CLOSE")?>">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="baseLineList">
                    <?php
                        require_once  DP_BASE_DIR . '/modules/monitoringandcontrol/view/view.1LBLBASELINE.php';
                    ?>
                </div>
                <div id="baseLineForm" style="display: none;">

                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light btn-sm" style="display: none;" id="btnBackBaseline" onclick="baseline.backToList()">Voltar</button>
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal"><?=$AppUI->_("LBL_CLOSE")?></button>
                <button type="button" class="btn btn-primary btn-sm" style="display: none;" id="btnSaveBaseline" onclick="baseline.save()"><?=$AppUI->_("LBL_SAVE")?></button>
            </div>
        </div>
    </div>
</div>
<script>

    var baseline = {

        init: function () {
            $('#cmbBaseline').select2({
                placeholder: '',
                allowClear: true,
                theme: "bootstrap"
            });

            $( "#date_edit").datepicker({
                dateFormat: 'dd/mm/yy',
                onSelect: baseline.submitForm
            });

            $('#baseLineModal').on('hidden.bs.modal', function() {
                $("#baseLineList").show();
                $("#baseLineForm").hide();
            });
        },

        submitForm: function () {
            $('#formdata').submit();
        },

        new: function () {
            $.ajax({
                type: "get",
                url: "?m=monitoringandcontrol&template=addedit_baseline&project_id=<?=$project_id?>"
            }).done(function(response) {
                $('#baseLineList').hide();
                $('#baseLineForm').html(response).show();
                $('#btnSaveBaseline').show();
                $('#btnBackBaseline').show();
            });
        },

        save: function () {
            var baselineName = $('#nmBaseline').val();
            var baselineVersion = $('#nmVersao').val();
            var msg = [];
            var err = false;
            if (!baselineName) {
                err = true;
                msg.push('Informe o nome');
            }

            if (!baselineVersion) {
                err = true;
                msg.push('Informe a versão');
            }

            if (err) {
                $.alert({
                    title: "<?=$AppUI->_('Error', UI_OUTPUT_JS); ?>",
                    content: msg.join('<br>')
                });
                return;
            }

            $.ajax({
                url: "?m=monitoringandcontrol",
                type: "post",
                datatype: "json",
                data: $("form[name=form_baseline]").serialize(),
                success: function(resposta) {
                    resposta = JSON.parse(resposta);
                    var id = resposta.newBaselineId;
                    var dateTime = resposta.dateTime;
                    $.alert({
                        title: "<?=$AppUI->_('Success', UI_OUTPUT_JS); ?>",
                        content: resposta.msg,
                        onClose: function() {
                            var btnDelete = '<button type="button" class="btn btn-sm btn-danger" onclick="baseline.delete('+id+')" title="Remover baseline">' +
                                '<i class="far fa-trash-alt"></i>' +
                                '</button>';
                            var btnEdit = '<button type="button" class="btn btn-sm btn-secondary" onclick="baseline.edit('+id+')" title="Alterar baseline">' +
                                '<i class="far fa-edit"></i>' +
                                '</button>';

                            var cells = '<td>'
                                .concat(baselineName)
                                .concat('</td>')
                                .concat('<td>')
                                .concat(baselineVersion)
                                .concat('</td>')
                                .concat('<td>')
                                .concat(dateTime)
                                .concat('</td>')
                                .concat('<td>')
                                .concat(btnDelete)
                                .concat(btnEdit)
                                .concat('</td>');

                            var tableLine = $('#tableBaselines_line_'+id);
                            var newLine = false;
                            if (!tableLine.html()) {
                                newLine = true;
                                tableLine = $('<tr id="tableBaselines_line_'+id+'"></tr>');
                            }
                            tableLine.html('').html(cells);
                            if (newLine) {
                                $('#baselinesTableBody').append(tableLine.html());
                            }
                            baseline.backToList();
                        }
                    });
                },
                error: function(resposta) {
                    $.alert({
                        title: "<?=$AppUI->_('Error', UI_OUTPUT_JS); ?>",
                        content: "<?=$AppUI->_('Something went wrong.', UI_OUTPUT_JS); ?>"
                    });
                }
            });
        },

        delete: function () {
//            /**
//             * <form name="form_delete" method="post" action="?m=monitoringandcontrol&a=addedit_update_baseline&project_id=--><?php ////echo $project_id; ?>//<!--" enctype="multipart/form-data" >-->
//             <!--                    <input name="dosql" type="hidden" value="do_baseline_aed" />-->
//             <!--                    <input  type="hidden" name="acao" value="delete"  />-->
//             <!--                    <input name="idBaseline" type="hidden" id="idBaseline" value="--><?php ////echo $row['baseline_id']; ?>//<!--">-->
//             <!--                    <input name="project_id" type="hidden" id="project_id" value="--><?php ////echo $project_id; ?>//<!--">-->
//             <!--                    <input  type="image" alt="./images/icons/stock_delete-16.png" src="./images/icons/stock_delete-16.png" title="Deletar" name="deletar" value="deletar" onclick="deleteRow(excluir);"  />-->
//             <!--                </form>-->
//             */
        },

        edit: function () {
//            /**
//             * <!--                <form name="form_update" method="post" action="?m=monitoringandcontrol&a=addedit_update_baseline&project_id=--><?php ////echo $project_id; ?>//<!--&idBaseline=--><?php ////echo $row['baseline_id'] ; ?>//<!--" enctype="multipart/form-data" >-->
//             <!--                    <input  type="hidden" name="acao" value="update"  />-->
//             <!--                    <input name="project_id" type="hidden" id="project_id" value="--><?php ////echo $project_id; ?>//<!--">-->
//             <!--                    <input name="idBaseline" type="hidden" id="meeting_id" value="--><?php ////echo $row['baseline_id']; ?>//<!--">-->
//             <!--                    <input  type="image" alt="./images/icons/pencil.gif" src="./images/icons/pencil.gif" title="Editar" name="editar" value="editar" onclick="updateRow();"  />-->
//             <!--                </form>-->
//             */
        },

        backToList: function () {
            $('#btnSaveBaseline').hide();
            $('#btnBackBaseline').hide();
            $('#baseLineForm').hide();
            $('#baseLineList').show();
        }

    }

    $(document).ready(baseline.init);

</script>