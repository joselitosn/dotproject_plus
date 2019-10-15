<?php
if (!defined("DP_BASE_DIR")) {
    die("You should not access this file directly.");
}

global $tabbed, $currentTabName, $currentTabId, $AppUI;
$company_id = intval(dPgetParam($_GET, "company_id", 0));
$query = new DBQuery();
$query->addTable("users", "u");
$query->addQuery("user_id, user_username, contact_last_name, contact_first_name, contact_id");
$query->addJoin("contacts", "c", "u.user_contact = c.contact_id");
$query->addWhere("c.contact_company = " . $company_id);
$query->addOrder("contact_last_name");
$res = $query->exec();
?>
    <h4><?=$AppUI->_('2LBLHumanResources');?></h4>
    <hr>
<?php
if (!$res->fields) {
?>
    <div class="alert alert-secondary text-center" role="alert">
        <?=$AppUI->_("LBL_THERE_IS_NO_HR") ?>
        <?=$AppUI->_("LBL_CLICK"); ?>
        <a class="alert-link" href="javascript:void(0)" onclick="hr.new()">
            <?php echo $AppUI->_("LBL_HERE"); ?>
        </a>
        <?php echo $AppUI->_("LBL_TO_CREATE_A_HR"); ?>
    </div>
<?php
} else {
?>
    <div class="row">
        <div class="col-md-12 text-right">
            <a class="btn btn-sm btn-secondary" href="javascript:void(0)" onclick="hr.new()">
                Adicionar
            </a>
        </div>
    </div>
<?php
    require_once DP_BASE_DIR . "/modules/human_resources/human_resources.class.php";
    require_once DP_BASE_DIR . "/modules/human_resources/configuration_functions.php";
    require_once DP_BASE_DIR . "/modules/human_resources/allocation_functions.php";

    function cal_work_day_conv($val) {
        global $locale_char_set;
        setlocale(LC_ALL, 'en_AU' . (($locale_char_set) ? ('.' . $locale_char_set) : '.utf8'));
        $wk = Date_Calc::getCalendarWeek(null, null, null, "%a", LOCALE_FIRST_DAY);
        setlocale(LC_ALL, $AppUI->user_lang);

        $day_name = $wk[($val - LOCALE_FIRST_DAY) % 7];
        if ($locale_char_set == "utf-8" && function_exists("utf8_encode")) {
            $day_name = utf8_encode($day_name);
        }
        return htmlentities($day_name, ENT_COMPAT, $locale_char_set);
    }

    for ($res; !$res->EOF; $res->MoveNext()) {
        $user_id = $res->fields["user_id"];
        $human_resource_id = getHumanResourceId($user_id);
        $user_has_human_resource = $human_resource_id != -1;
        $contact_id = $res->fields["contact_id"];

        $roles = getUserRolesByUserId($user_id);
        $concat_roles_names = "";
        if ($roles != null) {
            $roles_array = array();
            foreach ($roles as $role) {
                array_unshift($roles_array, $role["human_resources_role_name"]);
            }
            $concat_roles_names = implode(", ", $roles_array);
        }

        $cwd = array();
        $cwd[0] = '0';
        $cwd[1] = '1';
        $cwd[2] = '2';
        $cwd[3] = '3';
        $cwd[4] = '4';
        $cwd[5] = '5';
        $cwd[6] = '6';
        $cwd_conv = array_map('cal_work_day_conv', $cwd);
        //translation to portuguese
        $cwd_conv[0]= $AppUI->_("LBL_SUNDAY" );
        $cwd_conv[1]=$AppUI->_("LBL_MONDAY");
        $cwd_conv[2]=$AppUI->_("LBL_TUESDAY" );
        $cwd_conv[3]=$AppUI->_("LBL_WEDNESDAY");
        $cwd_conv[4]=$AppUI->_("LBL_THURSDAY");
        $cwd_conv[5]=$AppUI->_("LBL_FRIDAY");
        $cwd_conv[6]=$AppUI->_("LBL_SATURDAY");

        $badgeClass = '';
        switch ($user_has_human_resource) {
            case true:
                $badgeClass = ' badge-success';
                $status = $AppUI->_("User with human resources configured");
                break;
            default:
                $badgeClass = ' badge-danger';
                $status = $AppUI->_("User with human resources not configured");
                break;

        }

        require_once (DP_BASE_DIR . "/modules/monitoringandcontrol/control/controller_resources_costs.class.php");
        require_once (DP_BASE_DIR . "/modules/monitoringandcontrol/control/controller_util.class.php");
        $resCost = new ControllerResourcesCosts();
        $controllerUtil = new ControllerUtil();

        $userCosts = $resCost->getRecordsByUser($user_id);

        ?>

        <div class="card inner-card">
            <div class="card-body shrink">
                <div class="row">
                    <div class="col-md-9">
                        <h5>
                            <a id="<?=$user_id?>" data-toggle="collapse" href="#hr_details_<?=$user_id?>">
                                <?=$res->fields["contact_first_name"].' '.$res->fields["contact_last_name"];?>
                                <i class="fas fa-caret-down"></i>
                            </a>
                        </h5>
                    </div>
                    <div class="col-md-3 text-right">
                            <span class="badge <?=$badgeClass?>">
                                <?=$status?>
                            </span>
                        <div class="dropdown" style="width: 10%; float: right;">
                            <a href="javascript:void(0)" class="link-primary" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-bars"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink">
                                <a class="dropdown-item" href="javascript:void(0)" onclick="hr.edit(<?=$human_resource_id?>, <?=$user_id?>, <?=$contact_id?>)">
                                    <i class="far fa-edit"></i>
                                    <?=$AppUI->_('Update Human Resource')?>
                                </a>
                                <?php
                                $obj = new CHumanResource();
                                $canDelete=false;
                                if ($human_resource_id != -1) {
                                    $obj->load($human_resource_id);
                                    $canDelete=$obj->canDelete();
                                }else{
                                    $canDelete=true;
                                }

                                if ($canDelete) {
                                    ?>
                                    <a class="dropdown-item" href="javascript:void(0)" onclick="hr.delete(<?=$human_resource_id?>, <?=$user_id?>, <?=$contact_id?>)">
                                        <i class="far fa-trash-alt"></i>
                                        <?=$AppUI->_('Delete Human Resource')?>
                                    </a>
                                    <?php
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="hr_details_<?=$user_id?>" class="collapse">
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-sm no-border">
                                <tr>
                                    <th class="text-right" width="15%"><?=$AppUI->_("Roles")?>:</th>
                                    <td><?=$concat_roles_names?></td>
                                </tr>
                                <tr>
                                    <th class="text-right" width="15%"><?=$AppUI->_("Lattes URL")?>:</th>
                                    <td>
                                        <a class="card-link" href="<?=$obj->human_resource_lattes_url; ?>"><?=$obj->human_resource_lattes_url; ?></a>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-right" width="15%"><?=$AppUI->_('LBL_EVENTUAL_INVOLVIMENT')?>:</th>
                                    <td>
                                        <?=$obj->eventual == 0 ? $AppUI->_("LBL_NO") : $AppUI->_("LBL_YES")?>
                                    </td>
                                </tr>
                                <?php
                                if ($obj->eventual == 0) {
                                    ?>
                                    <tr>
                                        <th class="text-right" width="15%"><?= $AppUI->_("Weekday working hours") ?>
                                            :
                                        </th>
                                        <td>
                                            <table class="table table-sm table-bordered text-center">
                                                <thead class="thead-dark">
                                                <tr>
                                                    <th><?= $cwd_conv[0] ?></th>
                                                    <th><?= $cwd_conv[1] ?></th>
                                                    <th><?= $cwd_conv[2] ?></th>
                                                    <th><?= $cwd_conv[3] ?></th>
                                                    <th><?= $cwd_conv[4] ?></th>
                                                    <th><?= $cwd_conv[5] ?></th>
                                                    <th><?= $cwd_conv[6] ?></th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <tr>
                                                    <td><?= $obj->human_resource_mon ?></td>
                                                    <td><?= $obj->human_resource_tue ?></td>
                                                    <td><?= $obj->human_resource_wed ?></td>
                                                    <td><?= $obj->human_resource_thu ?></td>
                                                    <td><?= $obj->human_resource_fri ?></td>
                                                    <td><?= $obj->human_resource_sat ?></td>
                                                    <td><?= $obj->human_resource_sun ?></td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                    <?php
                                }
                                ?>
                                <tr>
                                    <th class="text-right" width="15%">Custos do recurso humano:</th>
                                    <td>
                                        <table class="table table-sm table-bordered table-striped text-center">
                                            <thead class="thead-dark">
                                            <tr>
                                                <th><?=$AppUI->_('LBL_INICIO_VIGENCIA')?></th>
                                                <th><?=$AppUI->_('LBL_FIM_VIGENCIA')?></th>
                                                <th><?=$AppUI->_('LBL_TAXA_PADRAO')?> (<?=dPgetConfig("currency_symbol") ?>)</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php
                                            if (count($userCosts)) {

                                                foreach ($userCosts as $cost) {
                                                    $dt_ini = $cost[4];
                                                    $dt_fim = $cost[5];
                                                    $dt_ini = $controllerUtil->formatDate($dt_ini);
                                                    $dt_fim = $controllerUtil->formatDate($dt_fim);
                                                    ?>
                                                    <tr>
                                                        <td><?= $dt_ini ?></td>
                                                        <td><?= $dt_fim ?></td>
                                                        <td>R$ <?=number_format($cost[2], 2, ',', '.') ?></td>
                                                    </tr>
                                                    <?php
                                                }
                                            } else {
                                                ?>
                                                <tr>
                                                    <td class="text-center" colspan="3">Nenhum custo cadastrado</td>
                                                </tr>
                                                <?php
                                            }
                                            ?>

                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php
    }
    $query->clear();
}
?>



<div class="modal" id="addHrModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?=$AppUI->_('LBL_NEW_UR')?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form name="new_user">

                    <input type="hidden" name="dosql" value="do_user_contact_creation" />
                    <input type="hidden" name="company_id" value="<?=$company_id ?>">

                    <div class="form-group">
                        <span class="required"></span>
                        <?=$AppUI->_('requiredField');?>
                    </div>

                    <div class="form-group">
                        <label for="first_name" class="required">
                            <?php echo $AppUI->_('First Name'); ?>
                        </label>
                        <input type="text" class="form-control form-control-sm" name="first_name" value="" maxlength="100" />
                    </div>

                    <div class="form-group">
                        <label for="first_name" class="required">
                            <?php echo $AppUI->_('Last Name'); ?>
                        </label>
                        <input type="text" class="form-control form-control-sm" name="last_name" value="" maxlength="100" />
                    </div>
                </form>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal"><?=$AppUI->_('LBL_CLOSE')?></button>
                <button type="button" class="btn btn-primary btn-sm" id="btnAddHr"><?=$AppUI->_('LBL_SAVE')?></button>
            </div>
        </div>
    </div>
</div>



<div class="modal" id="editHrModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?=$AppUI->_('LBL_EDIT_UR')?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body edit-hr-body">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal"><?=$AppUI->_('LBL_CLOSE')?></button>
                <button type="button" class="btn btn-primary btn-sm" id="btnEditHr" ><?=$AppUI->_('LBL_SAVE')?></button>
            </div>
        </div>
    </div>
</div>

<script>

    var hr = {

        costs: [],

        init: function () {
            $(document).ready(function(e) {
                $('a[data-toggle=collapse]').on('click', hr.show);

                $('#addHrModal').on('hidden.bs.modal', function (e) {
                    $("#btnAddHr").off("click");
                });

                $('#editHrModal').on('hidden.bs.modal', function (e) {
                    $("#btnEditHr").off("click");
                    hr.costs = [];
                });
            });

        },

        show: function (e) {
            const collapseRole = $(e.target);
            $('#hr_details_'+e.target.id).on('shown.bs.collapse', function () {
                collapseRole.find('i').removeClass('fa-caret-down');
                collapseRole.find('i').addClass('fa-caret-up');
            });
            $('#hr_details_'+e.target.id).on('hidden.bs.collapse', function () {
                collapseRole.find('i').removeClass('fa-caret-up');
                collapseRole.find('i').addClass('fa-caret-down');
            });
        },

        new: function () {
            $("#btnAddHr").on("click", function() {
                hr.saveNew();
            });
            $("#addHrModal").modal();
        },

        saveNew: function () {
            var firstName = $("input[name=first_name]").val().trim();
            var lastName = $("input[name=last_name]").val().trim();

            if (firstName=="" || lastName=="") {
                $.alert({
                    icon: "far fa-times-circle",
                    type: "red",
                    title: "<?=$AppUI->_('Error', UI_OUTPUT_JS); ?>",
                    content: "<?=$AppUI->_('VALIDATE_NEW_USER_FORM', UI_OUTPUT_JS); ?>"
                });
                return;
            }
            $.ajax({
                url: "?m=human_resources",
                type: "post",
                datatype: "json",
                data: $("form[name=new_user]").serialize(),
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
        },

        edit: function (id, userId, contactId) {
            $.ajax({
                type: "get",
                url: "?m=human_resources&template=addedit_hr&human_resource_id="+id+"&contact_id="+contactId+"&company_id=<?=$company_id?>&user_id="+userId
            }).done(function(response) {
                $(".edit-hr-body").html(response);
                $("#btnEditHr").on("click", function() {
                    hr.saveEdit();
                });
                var selectedRoles = $('#rolesHidden').val();
                if (selectedRoles) {
                    selectedRoles = JSON.parse(selectedRoles);
                }

                $('.select-roles')
                    .select2({
                        theme: "bootstrap"
                    });

                $('.select-roles')
                    .val(selectedRoles)
                    .trigger('change');

                $( ".datepicker" ).datepicker({
                    dateFormat: 'dd/mm/yy'
                });

                $("#tx_pad").mask('000.000.000.000.000,00', {reverse: true});

                var registeredCosts = $('#costsHidden').val();
                var newCosts = [];
                if (registeredCosts) {
                    hr.costs = JSON.parse(registeredCosts);
                    hr.costs.forEach(function(cost) {

                        var startDate =  cost.cost_dt_begin.substring(0, 10);
                        var endDate =  cost.cost_dt_end.substring(0, 10);
                        var arrStartDate = startDate.split('-');
                        var arrEndDate = endDate.split('-');
                        var objStartDate = new Date(arrStartDate[0], arrStartDate[1]-1, arrStartDate[2]);
                        var objEndDate = new Date(arrEndDate[0], arrEndDate[1]-1, arrEndDate[2]);

                        var data = {
                            startDate: arrStartDate[2] +'/'+ arrStartDate[1] +'/'+ arrStartDate[0],
                            objStartDate: objStartDate,
                            endDate: arrEndDate[2] +'/'+ arrEndDate[1] +'/'+ arrEndDate[0],
                            objEndDate: objEndDate,
                            cost: cost.cost_value,
                            timestamp: new Date().getTime()
                        };
                        hr.createTableRow(data);
                        newCosts.push(data);
                    });
                }
                hr.costs = newCosts;

                hr.displayWorkingDays();

                $("#editHrModal").modal();
            });
        },

        saveEdit: function () {
            $("#btnEditHr").off("click");
            var $mon = $('input[name=human_resource_mon]');
            var $tue = $('input[name=human_resource_tue]');
            var $wed = $('input[name=human_resource_wed]');
            var $thu = $('input[name=human_resource_thu]');
            var $fri = $('input[name=human_resource_fri]');
            var $sat = $('input[name=human_resource_sat]');
            var $sun = $('input[name=human_resource_sun]');
            var $maxWh = $('input[name=daily_working_hours]');

            if($mon.val() > $maxWh.val()
                || $tue.val() > $maxWh.val()
                || $wed.val() > $maxWh.val()
                || $thu.val() > $maxWh.val()
                || $fri.val() > $maxWh.val()
                || $sat.val() > $maxWh.val()
                || $sun.val() > $maxWh.val()) {
                $.alert({
                    icon: "far fa-times-circle",
                    type: "red",
                    title: "<?=$AppUI->_('Error', UI_OUTPUT_JS); ?>",
                    content: "<?=$AppUI->_('Number greater than daily working hours')?>"
                });
                return;
            }

            $('#costsHidden').val(JSON.stringify(hr.costs));

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

        },

        delete: function(hrId, userId, contactId) {
            $.confirm({
                title: '<?=$AppUI->_("LBL_CONFIRM", UI_OUTPUT_JS); ?>',
                content: '<?=$AppUI->_("LBL_ASK_HUMAN_RESOURCE_DELETE", UI_OUTPUT_JS); ?>',
                buttons: {
                    sim: {
                        action: function () {
                            $.ajax({
                                url: "?m=human_resources",
                                type: "post",
                                datatype: "json",
                                data: {
                                    dosql: 'do_hr_aed',
                                    del: 1,
                                    human_resource_user_id: userId,
                                    contact_id: contactId,
                                    company_id: <?=$company_id?>,
                                    human_resource_id: hrId
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

        addCost: function () {
            var startDate = $('input[name=date_edit2]').val();
            var endDate = $('input[name=date_edit3]').val();
            var cost = $('input[name=tx_pad]').val();
            cost = cost.replace(/\./g, '').replace(',', '.');

            if (!startDate || !endDate || !cost) {
                $.alert({
                    icon: "far fa-times-circle",
                    type: "red",
                    title: "<?=$AppUI->_('Error', UI_OUTPUT_JS); ?>",
                    content: "Preencha as datas de início e fim da vigência e a remuneração"
                });
                return;
            }

            var arrStartDate = startDate.split('/');
            var arrEndDate = endDate.split('/');
            var objStartDate = new Date(arrStartDate[2], arrStartDate[1]-1, arrStartDate[0]);
            var objEndDate = new Date(arrEndDate[2], arrEndDate[1]-1, arrEndDate[0]);

            if (objStartDate > objEndDate) {
                $.alert({
                    icon: "far fa-times-circle",
                    type: "red",
                    title: "<?=$AppUI->_('Error', UI_OUTPUT_JS); ?>",
                    content: "<?= $AppUI->_("LBL_DATE_BEGIN_BEFORE_DATE_END")?>"
                });
                return;
            }

            var err = false;
            hr.costs.forEach(function(cost, i) {
                if((cost.objStartDate >= objStartDate &&
                        cost.objStartDate <= objEndDate) ||
                    (cost.objEndDate >= objStartDate &&
                        cost.objEndDate <= objEndDate)){
                    $.alert({
                        icon: "far fa-times-circle",
                        type: "red",
                        title: "<?=$AppUI->_('Error', UI_OUTPUT_JS); ?>",
                        content: "<?=$AppUI->_("LBL_DATE_INVALID_RANGE")?>"
                    });
                    err = true;
                }
            });

            if (err) {
                return;
            }

            var data = {
                startDate: startDate,
                objStartDate: objStartDate,
                endDate: endDate,
                objEndDate: objEndDate,
                cost: cost,
                timestamp: new Date().getTime()
            };

            hr.costs.push(data);

            // Adds new table row
            hr.createTableRow(data);

            // Clean input fields
            $('input[name=date_edit2]').val('');
            $('input[name=date_edit3]').val('');
            $('input[name=tx_pad]').val('');
        },

        createTableRow: function (data) {
            var tr = '<tr id="'+data.timestamp+'">';
            var cost = parseFloat(data.cost).toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
            tr = tr
                .concat('<td>')
                .concat(data.startDate)
                .concat('</td>')
                .concat('<td>')
                .concat(data.endDate)
                .concat('</td>')
                .concat('<td>')
                .concat(cost)
                .concat('</td>')
                .concat('<td>')
                .concat('<button class="btn btn-sm btn-danger" type="button" title="Remover">')
                .concat('<i class="far fa-trash-alt"></i>')
                .concat('</button>')
                .concat('</td>')
                .concat('</tr>');

            tr = $('.table-costs').append(tr);

            $(tr).find('button').on('click', function() {
                hr.removeCost(this);
            });
        },

        removeCost: function (button) {
            var $tr = $(button).parent().parent();
            var id = $tr.attr('id');
            $tr.remove();
            var indice = null;
            hr.costs.forEach(function(cost, i) {
               if (cost.timestamp == id) {
                   indice = i;
               }
            });
            hr.costs.splice(indice, 1);
        },

        displayWorkingDays: function () {
            var eventual = $('input[name=eventual]:checked').val() == 1;
            if (eventual) {
                $('input[name=human_resource_mon]').val('');
                $('input[name=human_resource_tue]').val('');
                $('input[name=human_resource_wed]').val('');
                $('input[name=human_resource_thu]').val('');
                $('input[name=human_resource_fri]').val('');
                $('input[name=human_resource_sat]').val('');
                $('input[name=human_resource_sun]').val('');
                $('#weekly_working_hours').hide()
            } else {
                $('#weekly_working_hours').show();
            }
        }

    };

    hr.init();
</script>