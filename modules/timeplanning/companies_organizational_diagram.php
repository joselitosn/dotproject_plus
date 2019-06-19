<?php
if (!defined('DP_BASE_DIR')) {
    die('You should not access this file directly.');
}
$AppUI->savePlace();
require_once (DP_BASE_DIR . "/modules/timeplanning/control/controller_company_role.class.php");
$controllerCompanyRole = new ControllerCompanyRole();
$company_id = intval(dPgetParam($_GET, 'company_id', 0));

require_once DP_BASE_DIR . "/modules/system/roles/roles.class.php";
$crole = new CRole;
$query = new DBQuery;
$query->addTable('human_resources_role', 'r');
$query->addQuery('human_resources_role_name as name');
$query->addWhere('r.human_resources_role_company_id = ' . $company_id);
$sql = $query->prepare();
$query->clear();
$roles = db_loadList($sql);

$companyRoles = $controllerCompanyRole->getCompanyRoles($company_id);
$companyRole = current($companyRoles);
if ($companyRole) {
    $jsonChart = $companyRole->getDescription();
}
?>

<div class="row">
    <div class="col-md-12">
        <div class="alert alert-secondary text-center" role="alert" id="sectionNew">
            O organograma ainda não foi criado. Clique
            <a class="alert-link" href="javascript:void(0)" onclick="chart.new()">
                <?php echo $AppUI->_("LBL_HERE"); ?>
            </a>
            para criá-lo
        </div>

        <form name="form_orgonogram">
            <input name="dosql" type="hidden" value="do_company_aed" />
            <input type="hidden" name="chart_data" id="chartDataSource" value='<?=$jsonChart?>' />
        </form>

        <!-- CHART -->
        <div class="orgchart" id="people"></div>

        <!-- MODAL -->
        <div class="modal" id="dialogOrganogram" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="hdnId" />

                        <div class="form-group">
                            <span class="required"></span>
                            <?=$AppUI->_('requiredField');?>
                        </div>

                        <div class="form-group">
                            <label for="role" class="required">
                                <?=$AppUI->_('LBL_ROLE'); ?>
                            </label>
                            <select class="form-control form-control-sm select-role" id="selectRole" name="role">
                                <?php
                                foreach ($roles as $option) {
                                    ?>
                                    <option></option>
                                    <option value="<?=$option['name']?>"><?=$option['name']?></option>
                                    <?php
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal"><?=$AppUI->_('LBL_CLOSE')?></button>
                        <button type="button" class="btn btn-primary" id="btnSave">Adicionar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>

    var chart = {

        element: null,
        dataSource: null,

        new: function () {
            $('#sectionNew').hide();
            this.createButtons();

            this.element = new getOrgChart(document.getElementById("people"), {
                primaryFields: ["role"],
                theme: "monica",
                enableEdit: false,
                enableDetailsView: false,
                scale: 0.6,
                updatedEvent: function () {
                    chart.init();
                },
                dataSource: chart.dataSource
            });

            document.getElementById("btnSave").addEventListener(
                "click",
                function () {
                    var node = chart.element.nodes[document.getElementById("hdnId").value];
                    node.data.role = document.getElementById("selectRole").value;

                    chart.element.updateNode(node.id, node.pid, node.data);
                    $("#dialogOrganogram").modal('hide');

                    org.save();
                });

            this.init();
        },

        init: function () {

            var btns = document.getElementsByClassName("button");

            for (var i = 0; i < btns.length; i++) {
                btns[i].addEventListener("click", function () {
                    var nodeElement = chart.getNodeByClickedBtn(this);
                    var action = this.getAttribute("data-action");
                    var id = nodeElement.getAttribute("data-node-id");
                    var node = chart.element.nodes[id];

                    switch (action) {
                        case "add":
                            var newNodeId = new Date().getTime();
                            chart.element.insertNode(id, [], newNodeId);
                            document.getElementById("hdnId").value = newNodeId;
                            $("#selectRole").val(null);
                            $("#selectRole").trigger('change');
                            $("#dialogOrganogram").find('h5').html('Inclusão de nodo no organograma');
                            $('#btnSave').html('Adicionar');
                            $("#dialogOrganogram").modal();
                            break;
                        case "edit":
                            document.getElementById("hdnId").value = node.id;
                            $("#selectRole").val(node.data.role);
                            $("#selectRole").trigger('change');
                            $("#dialogOrganogram").find('h5').html('Alteração de nodo no organograma');
                            $('#btnSave').html('Alterar');
                            $("#dialogOrganogram").modal();
                            break;
                        case "delete":
                            chart.element.removeNode(id);
                            org.save();
                            break;
                    }
                });
            }
        },

        createButtons: function () {
            var btnAdd = '<g data-action="add" class="button" transform="matrix(0.14,0,0,0.14,0,0)"><rect style="opacity:0" x="0" y="0" height="300" width="300" /><path  fill="#ffffff" d="M149.996,0C67.157,0,0.001,67.158,0.001,149.997c0,82.837,67.156,150,149.995,150s150-67.163,150-150 C299.996,67.156,232.835,0,149.996,0z M149.996,59.147c25.031,0,45.326,20.292,45.326,45.325 c0,25.036-20.292,45.328-45.326,45.328s-45.325-20.292-45.325-45.328C104.671,79.439,124.965,59.147,149.996,59.147z M168.692,212.557h-0.001v16.41v2.028h-18.264h-0.864H83.86c0-44.674,24.302-60.571,40.245-74.843 c7.724,4.15,16.532,6.531,25.892,6.601c9.358-0.07,18.168-2.451,25.887-6.601c7.143,6.393,15.953,13.121,23.511,22.606h-7.275 v10.374v13.051h-13.054h-10.374V212.557z M218.902,228.967v23.425h-16.41v-23.425h-23.428v-16.41h23.428v-23.425H218.9v23.425 h23.423v16.41H218.902z"/></g>';
            var btnEdit = '<g data-action="edit" class="button" transform="matrix(0.14,0,0,0.14,50,0)"><rect style="opacity:0" x="0" y="0" height="300" width="300" /><path fill="#ffffff" d="M149.996,0C67.157,0,0.001,67.161,0.001,149.997S67.157,300,149.996,300s150.003-67.163,150.003-150.003 S232.835,0,149.996,0z M221.302,107.945l-14.247,14.247l-29.001-28.999l-11.002,11.002l29.001,29.001l-71.132,71.126 l-28.999-28.996L84.92,186.328l28.999,28.999l-7.088,7.088l-0.135-0.135c-0.786,1.294-2.064,2.238-3.582,2.575l-27.043,6.03 c-0.405,0.091-0.817,0.135-1.224,0.135c-1.476,0-2.91-0.581-3.973-1.647c-1.364-1.359-1.932-3.322-1.512-5.203l6.027-27.035 c0.34-1.517,1.286-2.798,2.578-3.582l-0.137-0.137L192.3,78.941c1.678-1.675,4.404-1.675,6.082,0.005l22.922,22.917 C222.982,103.541,222.982,106.267,221.302,107.945z"/></g>';
            var btnDel = '<g data-action="delete" class="button" transform="matrix(0.14,0,0,0.14,100,0)"><rect style="opacity:0" x="0" y="0" height="300" width="300" /><path fill="#ffffff" d="M112.782,205.804c10.644,7.166,23.449,11.355,37.218,11.355c36.837,0,66.808-29.971,66.808-66.808 c0-13.769-4.189-26.574-11.355-37.218L112.782,205.804z"/> <path stroke="#ffffff" fill="#ffffff" d="M150,83.542c-36.839,0-66.808,29.969-66.808,66.808c0,15.595,5.384,29.946,14.374,41.326l93.758-93.758 C179.946,88.926,165.595,83.542,150,83.542z"/><path stroke="#ffffff" fill="#ffffff" d="M149.997,0C67.158,0,0.003,67.161,0.003,149.997S67.158,300,149.997,300s150-67.163,150-150.003S232.837,0,149.997,0z M150,237.907c-48.28,0-87.557-39.28-87.557-87.557c0-48.28,39.277-87.557,87.557-87.557c48.277,0,87.557,39.277,87.557,87.557 C237.557,198.627,198.277,237.907,150,237.907z"/></g>';

            getOrgChart.themes.monica.box += '<g transform="matrix(1,0,0,1,350,10)">'
                + btnAdd
                + btnEdit
                + btnDel
                + '</g>';
        },

        getDataSource: function() {
            var data = chart.element.nodes;
            var dataKeys = Object.keys(data);

            var dataSource = [];
            dataKeys.forEach(function(k, i) {
                var obj = {
                    id: chart.element.nodes[k].id,
                    parantId: chart.element.nodes[k].parent.id,
                    role: chart.element.nodes[k].data.role
                };
                dataSource.push(obj);
            });
            return dataSource;
        },

        getNodeByClickedBtn: function (el) {
            while (el.parentNode) {
                el = el.parentNode;
                if (el.getAttribute("data-node-id"))
                    return el;
            }
            return null;
        }
    };

    var org = {

        init: function () {
            $(document).ready(function() {
                $("#selectRole").select2({
                    placeholder: "Papel",
                    allowClear: true,
                    theme: "bootstrap",
                    dropdownParent: $("#dialogOrganogram")
                });

                /**
                 * Get json data source, if present.
                 * Otherwise, create single "blank node"
                 */
                var dataSource = $('#chartDataSource').val();
                if (!dataSource) {
                    chart.dataSource = [
                        { id: 1, parentId: null, role: "Papel"}
                    ];
                    $('#sectionNew').show();
                } else {
                    try {
                        chart.dataSource = JSON.parse(dataSource);
                        $('#sectionNew').hide();
                        chart.new();
                    } catch(err) {
                        chart.dataSource = [
                            { id: 1, parentId: null, role: "Papel"}
                        ];
                    }
                }
            });

        },

        save: function () {
            var dataSource = chart.getDataSource();
            $('#chartDataSource').val(JSON.stringify(dataSource));

            $.ajax({
                url: "?m=timeplanning&a=view&company_id=<?php echo $company_id; ?>",
                type: "post",
                datatype: "json",
                data: $("form[name=form_orgonogram]").serialize(),
                success: function(resposta) {
//                    $.alert({
//                        title: "<?//=$AppUI->_('Success', UI_OUTPUT_JS); ?>//",
//                        content: resposta,
//                        onClose: function() {
//                            window.location.reload(true);
//                        }
//                    });
                },
                error: function(resposta) {
                    $.alert({
                        title: "<?=$AppUI->_('Error', UI_OUTPUT_JS); ?>",
                        content: "<?=$AppUI->_('Something went wrong.', UI_OUTPUT_JS); ?>"
                    });
                }
            });
        }
    }

    org.init();

</script>