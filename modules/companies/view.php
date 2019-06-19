<?php
/* COMPANIES $Id: view.php 6080 2010-12-04 08:39:35Z ajdonnison $ */
if (!defined('DP_BASE_DIR')) {
    die('You should not access this file directly.');
}

$company_id = intval(dPgetParam($_GET, 'company_id', 0));

// check permissions for this record
$canRead = getPermission($m, 'view', $company_id);
$canEdit = getPermission($m, 'edit', $company_id);


if (!$canRead) {
    $AppUI->redirect('m=public&a=access_denied');
}

const TAB_PROJECTS = 0;
const TAB_ROLES = 1;
const TAB_ORGANOGRAM = 2;
const TAB_HUMAN_RESOURCES = 3;

$tab = isset($_GET['tab']) ? $_GET['tab'] : 0;

// check if this record has dependencies to prevent deletion
$msg = '';
$obj = new CCompany();
$canDelete = $obj->canDelete($msg, $company_id);

// load the record data
$q = new DBQuery;
$q->addTable('companies', 'co');
$q->addQuery('co.*');
$q->addQuery('con.contact_first_name');
$q->addQuery('con.contact_last_name');
$q->addJoin('users', 'u', 'u.user_id = co.company_owner');
$q->addJoin('contacts', 'con', 'u.user_contact = con.contact_id');
$q->addWhere('co.company_id = ' . $company_id);
$sql = $q->prepare();
$q->clear();

$obj = null;
if (!db_loadObject($sql, $obj)) {
    $AppUI->setMsg('Company');
    $AppUI->setMsg('invalidID', UI_MSG_ERROR, true);
    $AppUI->redirect();
} else {
    $AppUI->savePlace();
}

// load the list of project statii and company types
$pstatus = dPgetSysVal('ProjectStatus');
$types = dPgetSysVal('CompanyType');
?>

<!-- New form -->
<?php
require_once (DP_BASE_DIR . "/modules/human_resources/human_resources.class.php");
$query = new DBQuery();
$query->addTable('company_policies', 'p');
$query->addQuery('company_policies_id');
$query->addWhere('p.company_policies_company_id = ' . $company_id);
$res = & $query->exec();
$company_policies_id = $res->fields['company_policies_id'];
$query->clear();

$policies = new CCompaniesPolicies();
if ($company_policies_id != "") {
    $policies->load($company_policies_id);
}
?>

<!-- Sidebar -->
<nav id="sidebar">
    <div class="sidebar-header">
        <h5 class="text-right">
            <i id="sidebarCollapse" class="mouse-cursor-pointer fas fa-angle-double-left" style="margin-top: 20px;"></i>
        </h5>
    </div>

    <ul class="list-unstyled components">
        <li class="<?=$tab == 0 ? 'active' : '' ?>">
            <a href="?m=companies&amp;a=view&amp;company_id=<?=$company_id?>&amp;tab=0">
                <?=$AppUI->_("Projects");?>
            </a>
        </li>
        <li class="<?=$tab == 1 ? 'active' : '' ?>">
            <a href="?m=companies&amp;a=view&amp;company_id=<?=$company_id?>&amp;tab=1">
                <?=$AppUI->_("LBL_ORGANIZATION_ROLES");?>
            </a>
        </li>
        <li class="<?=$tab == 2 ? 'active' : '' ?>">
            <a href="?m=companies&amp;a=view&amp;company_id=<?=$company_id?>&amp;tab=2">
                <?=$AppUI->_("LBL_ORGONOGRAM");?>
            </a>
        </li>
        <li class="<?=$tab == 3 ? 'active' : '' ?>">
            <a href="?m=companies&amp;a=view&amp;company_id=<?=$company_id?>&amp;tab=3">
                <?=$AppUI->_('2LBLHumanResources');?>
            </a>
        </li>
    </ul>
</nav>

<!-- Page Content -->
<div id="content">
    <fieldset>
        <legend><?=$AppUI->_('Company')?></legend>
        <div class="row">
            <div class="col-md-12 text-right">
                <div class="dropdown">
                    <a href="javascript:void(0)" class="link-primary" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-bars"></i>
                    </a>

                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink">
                        <?php
                            if ($canEdit) {
                        ?>
                                <a class="dropdown-item" href="javascript:void(0)" onclick="company.edit()">
                                    <i class="far fa-edit"></i>
                                    Alterar empresa
                                </a>
                        <?php
                            }
                            if ($canDelete) {
                        ?>
                                <a class="dropdown-item" href="javascript:void(0)" onclick="company.delete()">
                                    <i class="far fa-trash-alt"></i>
                                    Excluir empresa
                                </a>
                        <?php
                            }
                        ?>
                        <a class="dropdown-item" href="?m=admin"">
                        <i class="fas fa-users"></i>
                            Usuários
                        </a>
                        <a class="dropdown-item" href="?m=contacts"">
                        <i class="fas fa-address-book"></i>
                            Contatos
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="alert alert-primary" role="alert">
                    <a class="alert-link" id="companyDetailsLink" data-toggle="collapse" href="#company_details">
                        Dados da empresa
                        <i class="fas fa-caret-down"></i>
                    </a>
                    <div id="company_details" class="collapse">
                        <table class="table table-sm company-details">
                            <tr>
                                <th style="text-align:right"><?php echo $AppUI->_("Name"); ?>:</th>
                                <td><?php echo htmlspecialchars($obj->company_name) ?></td>
                                <th style="text-align:right"><?php echo $AppUI->_('Address'); ?>:</th>
                                <td><?php echo $obj->company_address1 ? htmlspecialchars($obj->company_address1) : 'Não informado'; ?></td>
                            </tr>
                            <tr>
                                <th style="text-align:right"><?php echo $AppUI->_("Owner"); ?>:</th>
                                <td><?php echo htmlspecialchars($obj->contact_first_name) . '&nbsp;' . htmlspecialchars($obj->contact_last_name) ?></td>
                                <th style="text-align:right"><?php echo $AppUI->_('City'); ?>:</th>
                                <td><?php echo $obj->company_city ? dPformSafe($obj->company_city) : 'Não informado'; ?></td>
                            </tr>
                            <tr>
                                <th style="text-align:right"><?php echo $AppUI->_("Phone"); ?>:</th>
                                <td ><?php echo $obj->company_phone1 ? htmlspecialchars($obj->company_phone1) : 'Não informado' ?></td>
                                <th style="text-align:right"><?php echo $AppUI->_('State'); ?>:</th>
                                <td><?php echo $obj->company_state ? htmlspecialchars($obj->company_state) : 'Não informado'; ?></td>
                            </tr>
                            <tr>
                                <th style="text-align:right"><?php echo $AppUI->_('Email'); ?>:</th>
                                <td><?php echo $obj->company_email ? htmlspecialchars($obj->company_email) : 'Não informado'; ?></td>
                                <th style="text-align:right"><?php echo $AppUI->_('Zip'); ?>:</th>
                                <td><?php echo $obj->company_zip ? htmlspecialchars($obj->company_zip) : 'Não informado'; ?></td>
                            </tr>
                        </table>
                        <h6><?=$AppUI->_("LBL_ORGANIZATIONAL_POLICY") ?></h6>
                        <table class="table table-sm company-details">
                            <tr>
                                <th style="text-align:right; width:15%"><?php echo $AppUI->_("LBL_REWARDS"); ?>:</th>
                                <td><?php echo $policies->company_policies_recognition ? $policies->company_policies_recognition : 'Não informado' ?></td>
                            </tr>
                            <tr>
                                <th style="text-align:right; width:15%"><?php echo $AppUI->_("LBL_RUGULATIONS"); ?>:</th>
                                <td><?php echo $policies->company_policies_policy ? $policies->company_policies_policy : 'Não informado'; ?></td>
                            </tr>
                            <tr>
                                <th style="text-align:right; width:15%"><?php echo $AppUI->_("Safety"); ?>:</th>
                                <td><?php echo $policies->company_policies_safety ? $policies->company_policies_safety : 'Não informado'; ?></td>
                            </tr>
                        </table>
                    </div>
                </div>

                <?php
                switch ($tab) {
                    case TAB_PROJECTS :
                        require_once DP_BASE_DIR . "/modules/dotproject_plus/companies_tab.Projects.php";
                        break;
                    case TAB_ROLES :
                        require_once DP_BASE_DIR . "/modules/human_resources/view_company_roles.php";
                        break;
                    case TAB_ORGANOGRAM :
                        require_once DP_BASE_DIR . "/modules/timeplanning/companies_organizational_diagram.php";
                        break;
                    case TAB_HUMAN_RESOURCES :
                        require_once DP_BASE_DIR . "/modules/human_resources/view_company_users.php";
                        break;
                }
                ?>

                <div class="modal" id="addEditCompanyModal" tabindex="-1" role="dialog">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Modal title</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body company-modal">

                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal"><?=$AppUI->_('LBL_CLOSE')?></button>
                                <button type="button" class="btn btn-primary" id="btnSaveCompany" ><?=$AppUI->_('LBL_SAVE')?></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php
            //require (DP_BASE_DIR . '/style/' . $uistyle . '/end-page.php');
        ?>
    </fieldset>
</div>

<script>

    var company = {
        init: function() {

            $(document).ready(function(e) {
                const collapseCompany = $('#companyDetailsLink');
                $('#company_details').on('shown.bs.collapse', function () {
                    collapseCompany.find('i').removeClass('fa-caret-down');
                    collapseCompany.find('i').addClass('fa-caret-up');
                });
                $('#company_details').on('hidden.bs.collapse', function () {
                    collapseCompany.find('i').removeClass('fa-caret-up');
                    collapseCompany.find('i').addClass('fa-caret-down');
                });

                $("#addEditCompanyModal").on("hidden.bs.modal", function() {
                    $("#btnSaveCompany").off("click");
                    $(this).find(".company-modal").html("");
                });

                $('#sidebarCollapse').on('click', function () {
                    $('#sidebar').toggleClass('active');

                    if ($(this).hasClass('fa-angle-double-right')) {
                        $(this).removeClass('fa-angle-double-right');
                        $(this).addClass('fa-angle-double-left');
                    } else {
                        $(this).removeClass('fa-angle-double-left');
                        $(this).addClass('fa-angle-double-right');
                    }
                });
            });

        },
        delete: function () {
            $.confirm({
                title: '<?=$AppUI->_("LBL_CONFIRM", UI_OUTPUT_JS); ?>',
                content: '<?=$AppUI->_("delete company", UI_OUTPUT_JS); ?>',
                buttons: {
                    confirmar: {
                        btnClass: 'btn-blue',
                        action: function () {
                            $.ajax({
                                url: "?m=companies",
                                type: "post",
                                datatype: "json",
                                data: {
                                    dosql: 'do_company_aed',
                                    del: 1,
                                    company_id: <?=$company_id?>
                                },
                                success: function(resposta) {
                                    $.alert({
                                        title: "<?=$AppUI->_('Success', UI_OUTPUT_JS); ?>",
                                        content: resposta,
                                        onClose: function() {
                                            window.location.href = "?m=companies";
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
                        }
                    },
                    cancelar: function () {},
                }
            });
        },
        edit: function() {
            $.ajax({
                type: "get",
                url: "?m=companies&template=addedit&company_id=<?=$company_id?>"
            }).done(function(response) {
                $(".company-modal").html(response);
                $(".modal-title").html("<?=$AppUI->_('edit this company')?>");
                $("#btnSaveCompany").on("click", function() {
                    company.save();
                })
                $("#addEditCompanyModal").modal();
            });
        },
        save: function() {
            var name = $("input[name=company_name]").val();
            var type = $("select[name=company_type]").val();
            var owner = $("select[name=company_owner]").val();

            if (!name || owner == null || type == null) {
                var msg = [];
                if (!name) msg.push("<?=$AppUI->_('companyValidName', UI_OUTPUT_JS); ?>");
                if (owner == null) msg.push("<?=$AppUI->_('companyValidOwner', UI_OUTPUT_JS); ?>");
                if (type == null) msg.push("<?=$AppUI->_('companyValidType', UI_OUTPUT_JS); ?>");
                $.alert({
                    title: "<?=$AppUI->_('Attention', UI_OUTPUT_JS); ?>",
                    content: msg.join("<br>")
                });
                $("input[name=company_name]").focus();
                return;
            }
            $.ajax({
                url: "?m=companies",
                type: "post",
                datatype: "json",
                data: $("form[name=changeclient]").serialize(),
                success: function(resposta) {
                    $.alert({
                        title: "<?=$AppUI->_('Success', UI_OUTPUT_JS); ?>",
                        content: resposta,
                        onClose: function() {
                            window.location.reload(true);
                        }
                    });
                    $("#addEditCompanyModal").modal("hide");
                },
                error: function(resposta) {
                    $.alert({
                        title: "<?=$AppUI->_('Error', UI_OUTPUT_JS); ?>",
                        content: "<?=$AppUI->_('Something went wrong.', UI_OUTPUT_JS); ?>"
                    });
                }
            });
        },
        project: {

            new: function() {
                $.ajax({
                    type: "get",
                    url: "?m=projects&template=addedit&company_id=<?=$company_id?>"
                }).done(function(response) {
                    $(".company-modal").html(response);
                    $(".modal-title").html("<?=$AppUI->_('new project')?>");
                    $("#btnSaveCompany").on("click", function() {
                        company.project.save();
                    })
                    $("#addEditCompanyModal").modal();
                });
            },

            edit: function(projectId) {
                $.ajax({
                    type: "get",
                    url: "?m=projects&template=addedit&company_id=<?=$company_id?>&project_id=" + projectId
                }).done(function(response) {
                    $(".company-modal").html(response);
                    $(".modal-title").html("<?=$AppUI->_('Update Project')?>");
                    $("#btnSaveCompany").on("click", function() {
                        company.project.save();
                    })
                    $("#addEditCompanyModal").modal();
                });
            },

            save: function() {
                var name = $("input[name=project_name]").val();
                var company = $("select[name=project_company]").val();

                if (!name || company == null) {
                    var msg = [];
                    if (!name) msg.push("Informe o nome do projeto");
                    if (company == null) msg.push("<?=$AppUI->_('projectsBadCompany', UI_OUTPUT_JS); ?>");
                    $.alert({
                        title: "<?=$AppUI->_('Attention', UI_OUTPUT_JS); ?>",
                        content: msg.join("<br>")
                    });
                    return;
                }
                $.ajax({
                    url: "?m=projects",
                    type: "post",
                    datatype: "json",
                    data: $("form[name=addEditProject]").serialize(),
                    success: function(resposta) {
                        console.log(resposta);
                        $.alert({
                            title: "<?=$AppUI->_('Success', UI_OUTPUT_JS); ?>",
                            content: resposta,
                            onClose: function() {
                                window.location.reload(true);
                            }
                        });
                        $("#addEditCompanyModal").modal("hide");
                    },
                    error: function(resposta) {
                        $.alert({
                            title: "<?=$AppUI->_('Error', UI_OUTPUT_JS); ?>",
                            content: "<?=$AppUI->_('Something went wrong.', UI_OUTPUT_JS); ?>"
                        });
                    }
                });
            },

            setShortName: function () {
                var x = 10;
                var $projectName = $('input[name=project_name]');

                var name = $projectName.val().trim();

                if (name.length < 11) {
                    x = $projectName.val().length;
                }
                $('input[name=project_short_name]').val($projectName.val().trim().substr(0, x));
            },

            dateSelected: function (date, element) {
                var arrDate = date.split('/');
                $date = $('#'+element.id);
                if ($date.attr('name') == 'start_date') {
                    $('input[name=project_start_date]').val(arrDate[2] + arrDate[1] + arrDate[0]);
                } else {
                    $('input[name=project_end_date]').val(arrDate[2] + arrDate[1] + arrDate[0]);
                }
            },

            formatDates: function () {
                var startDate = $('input[name=project_start_date]').val();
                var endDate = $('input[name=project_end_date]').val();

                if (startDate) {
                    $('input[name=start_date]').val(startDate.substr(6, 2)+'/'+startDate.substr(4, 2)+'/'+startDate.substr(0, 4));
                }
                if (endDate) {
                    $('input[name=end_date]').val(endDate.substr(6, 2)+'/'+endDate.substr(4, 2)+'/'+endDate.substr(0, 4));
                }
            }
        }
    };
    company.init();

</script>

<!--<div style="background-color: #D8D8D8">-->
<!--    <br />-->
<!--<table width="95%" align="center">-->
<!--    <tr>-->
<!--        <td style="font-size: 14px;font-weight: bold;cursor:pointer;" onclick="showCompanyForm()" >-->
<!--            --><?php //echo htmlspecialchars($obj->company_name); ?><!--  &nbsp;<img src='./modules/dotproject_plus/images/icone_seta_cima.png' id="img_expand_form" />-->
<!--        </td>-->
<!--        <td style="text-align: right">-->
<!--            <input type="button" onclick="window.location='index.php?m=companies&a=addedit&company_id=--><?php //echo $company_id ?><!-- ';" value="<?php //echo $AppUI->_("Edit"); ?><!--" class="button" /> -->
<!--            <!-- <input type="button" onclick="document.getElementById('rh_div').style.display='block';" value="RH" class="button" /> -->
<!--            <input type="button" onclick="window.location='index.php?m=admin';" value="--><?php //echo $AppUI->_("Users"); ?><!--" class="button" />-->
<!--            <input type="button" onclick="window.location='index.php?m=contacts';" value="--><?php //echo $AppUI->_("Contacts"); ?><!--" class="button" />-->
<!--            --><?php //if($canDelete){ ?>
<!--                <input type="button" onclick="delIt()" value="--><?php //echo $AppUI->_("LBL_EXCLUSION"); ?><!--" class="button" />-->
<!--            --><?php //} ?>
<!--        </td>-->
<!--    </tr>-->
<!--</table>-->
<!--<br />-->
<!--<div id="company_form_container">-->
<!--    <h1>OLOCO bicho!!!</h1>-->
<!--<table  border="0" cellpadding="4" cellspacing="0" width="95%" align="center" class="std" id="table_company_form">-->
<!--    <tr>-->
<!--        <td class="label_dpp" style="width:60px">--><?php //echo $AppUI->_("Name"); ?><!--<span style="color:red">*</span>:</td>-->
<!--        <td> --><?php //echo htmlspecialchars($obj->company_name); ?><!-- </td>-->
<!--        <td class="label_dpp" style="width:60px"> --><?php //echo $AppUI->_("Owner"); ?><!--</td>-->
<!--        <td> --><?php //echo (htmlspecialchars($obj->contact_first_name) . '&nbsp;' . htmlspecialchars($obj->contact_last_name)); ?><!-- </td>-->
<!--    </tr>-->
<!--    <tr>-->
<!--        <th colspan="4"><b>--><?php //echo $AppUI->_("LBL_CONTACT"); ?><!--</b></th>-->
<!--    </tr>-->
<!--    <tr>-->
<!--        <td class="label_dpp">--><?php //echo $AppUI->_('Phone'); ?><!--:</td>-->
<!--        <td colspan="3">--><?php //echo htmlspecialchars(@$obj->company_phone1); ?><!--</td>-->
<!--    </tr>-->
<!--    <tr>-->
<!--        <td class="label_dpp">--><?php //echo $AppUI->_('Email'); ?><!--: </td>-->
<!--        <td colspan="3">--><?php //echo htmlspecialchars($obj->company_email); ?><!--</td>-->
<!--    </tr>-->
<!--    <tr>-->
<!--        <td class="label_dpp"> --><?php //echo $AppUI->_('Address'); ?><!--: </td>-->
<!--        <td colspan="3">--><?php //echo $obj->company_address1; ?><!--</td>-->
<!--    </tr>-->
<!--    <tr>-->
<!--        <td class="label_dpp"> --><?php //echo $AppUI->_("City"); ?><!--: </td>-->
<!--        <td>--><?php //echo htmlspecialchars($obj->company_city); ?><!--  </td>-->
<!--        <td class="label_dpp">-->
<!--            --><?php //echo $AppUI->_("State"); ?>
<!--        </td>-->
<!--        <td> -->
<!--            --><?php //echo htmlspecialchars($obj->company_state); ?><!--&nbsp;&nbsp;-->
<!--            <b>CEP:</b>&nbsp;&nbsp; --><?php //echo htmlspecialchars($obj->company_zip); ?>
<!--        </td>-->
<!--    </tr>-->
<!--    <tr>-->
<!--        <th colspan="4"><b>--><?php //echo $AppUI->_("LBL_ORGANIZATIONAL_POLICY") ?><!--</b></th>-->
<!--    </tr> -->
<!--    <tr>-->
<!--        <td class="label_dpp">--><?php //echo $AppUI->_("LBL_REWARDS"); ?><!--:</td>-->
<!--        <td colspan="3">--><?php //echo $policies->company_policies_recognition; ?><!--</td>-->
<!--    </tr>-->
<!--    <tr>-->
<!--        <td class="label_dpp">--><?php //echo $AppUI->_('LBL_RUGULATIONS'); ?><!--:</td>-->
<!--        <td colspan="3">--><?php //echo $policies->company_policies_policy; ?><!--</td>-->
<!--    </tr>-->
<!--    <tr>-->
<!--        <td class="label_dpp">--><?php //echo $AppUI->_('Safety'); ?><!--:</td>-->
<!--        <td colspan="3">--><?php //echo $policies->company_policies_safety; ?><!--</td>-->
<!--    </tr>-->
<!--</table>-->
<!--</div>-->
<!--    <br />-->
<!--<!-- old form -->
<!--<table border="0" cellpadding="4" cellspacing="0" width="100%" class="std" style="display:none">-->
<!--    <tr>-->
<!--        <td valign="top" width="50%">-->
<!--            <strong>--><?php //echo $AppUI->_('Details'); ?><!--</strong>-->
<!--            <table cellspacing="1" cellpadding="2" width="100%">-->
<!--                <tr>-->
<!--                    <td align="right" nowrap="nowrap">--><?php //echo $AppUI->_('Company'); ?><!--:</td>-->
<!--                    <td class="hilite" width="100%">--><?php //echo htmlspecialchars($obj->company_name); ?><!--</td>-->
<!--                </tr>-->
<!--                <tr>-->
<!--                    <td align="right" nowrap="nowrap">--><?php //echo $AppUI->_('Owner'); ?><!--:</td>-->
<!--                    <td class="hilite" width="100%">--><?php
//            echo (htmlspecialchars($obj->contact_first_name) . '&nbsp;'
//            . htmlspecialchars($obj->contact_last_name));
//            ?><!--</td>-->
<!--                </tr>-->
<!--                <tr>-->
<!--                    <td align="right" nowrap="nowrap">--><?php //echo $AppUI->_('Email'); ?><!--:</td>-->
<!--                    <td class="hilite" width="100%">--><?php //echo htmlspecialchars($obj->company_email); ?><!--</td>-->
<!--                </tr>-->
<!--                <tr>-->
<!--                    <td align="right" nowrap="nowrap">--><?php //echo $AppUI->_('Phone'); ?><!--:</td>-->
<!--                    <td class="hilite">--><?php //echo htmlspecialchars(@$obj->company_phone1); ?><!--</td>-->
<!--                </tr>-->
<!--                <tr>-->
<!--                    <td align="right" nowrap="nowrap">--><?php //echo $AppUI->_('Phone'); ?><!--2:</td>-->
<!--                    <td class="hilite">--><?php //echo htmlspecialchars(@$obj->company_phone2); ?><!--</td>-->
<!--                </tr>-->
<!--                <tr>-->
<!--                    <td align="right" nowrap="nowrap">--><?php //echo $AppUI->_('Fax'); ?><!--:</td>-->
<!--                    <td class="hilite">--><?php //echo htmlspecialchars(@$obj->company_fax); ?><!--</td>-->
<!--                </tr>-->
<!--                <tr valign="top">-->
<!--                    <td align="right" nowrap="nowrap">--><?php //echo $AppUI->_('Address'); ?><!--:</td>-->
<!--                    <td class="hilite">-->
<!--                        --><?php //if (!empty($obj->company_country)) { ?>
<!--                            <span style="float: right"><a href="http://maps.google.com/maps?q=--><?php //echo dPformSafe(@$obj->company_address1, DP_FORM_URI); ?><!--+--><?php //echo dPformSafe(@$obj->company_address2, DP_FORM_URI); ?><!--+--><?php //echo dPformSafe(@$obj->company_city, DP_FORM_URI); ?><!--+--><?php //echo dPformSafe(@$obj->company_state, DP_FORM_URI); ?><!--+--><?php //echo dPformSafe(@$obj->company_zip, DP_FORM_URI); ?><!--+--><?php //echo dPformSafe(@$obj->company_country, DP_FORM_URI); ?><!--" target="_blank">-->
<!--                                    --><?php
//                                    echo dPshowImage('./images/googlemaps.gif', 55, 22, 'Find It on Google');
//                                    ?>
<!--                                --><?php //} ?>
<!--                            </a></span>-->
<!--                        --><?php
//                        echo (htmlspecialchars(@$obj->company_address1)
//                        . (($obj->company_address2) ? '<br />' : '') . htmlspecialchars($obj->company_address2)
//                        . (($obj->company_city) ? '<br />' : '') . htmlspecialchars($obj->company_city)
//                        . (($obj->company_state) ? ', ' : '') . htmlspecialchars($obj->company_state)
//                        . (($obj->company_zip) ? ' ' : '') . htmlspecialchars($obj->company_zip));
//                        ?>
<!--                    </td>-->
<!--                </tr>-->
<!--                <tr>-->
<!--                    <td align="right" nowrap="nowrap">--><?php //echo $AppUI->_('URL'); ?><!--:</td>-->
<!--                    <td class="hilite">-->
<!--                        <a href="http://--><?php //echo dPformSafe(@$obj->company_primary_url, DP_FORM_URI); ?><!--" target="Company">--><?php //echo htmlspecialchars(@$obj->company_primary_url); ?><!--</a>-->
<!--                    </td>-->
<!--                </tr>-->
<!--                <tr>-->
<!--                    <td align="right" nowrap="nowrap">--><?php //echo $AppUI->_('Type'); ?><!--:</td>-->
<!--                    <td class="hilite">--><?php //echo $AppUI->_($types[@$obj->company_type]); ?><!--</td>-->
<!--                </tr>-->
<!--            </table>-->
<!---->
<!--        </td>-->
<!--        <td width="50%" valign="top">-->
<!--            <strong>--><?php //echo $AppUI->_('Description'); ?><!--</strong>-->
<!--            <table cellspacing="0" cellpadding="2" border="0" width="100%" summary="company description">-->
<!--                <tr>-->
<!--                    <td class="hilite">-->
<!--                        --><?php //echo str_replace(chr(10), '<br />', htmlspecialchars($obj->company_description)); ?><!--&nbsp;-->
<!--                    </td>-->
<!--                </tr>-->
<!---->
<!--            </table>-->
<!--            --><?php
//            require_once($AppUI->getSystemClass('CustomFields'));
//            $custom_fields = New CustomFields($m, $a, $obj->company_id, 'view');
//            $custom_fields->printHTML();
//            ?>
<!--        </td>-->
<!--    </tr>-->
<!--</table>-->
<!--</div>-->
<!--<br />-->
<!--<table width="95%" align="center">-->
<!--    <tr>-->
<!--        <td>-->
<?php
//$tabBox = new CTabBox(('?m=companies&amp;a=view&amp;company_id=' . $company_id), '', $tab);
//$tabBox->add(DP_BASE_DIR . "/modules/dotproject_plus/companies_tab.Projects", $AppUI->_("Projects"));
//$tabBox->add(DP_BASE_DIR . "/modules/human_resources/view_company_roles", $AppUI->_("LBL_ORGANIZATION_ROLES"),UI_OUTPUT_HTML);
//$tabBox->add(DP_BASE_DIR . "/modules/timeplanning/companies_organizational_diagram", $AppUI->_("LBL_ORGONOGRAM"));
//$tabBox->add(DP_BASE_DIR . "/modules/human_resources/view_company_users", $AppUI->_('2LBLHumanResources') );
//$tabBox->show();
//?>
<!--    </td>-->
<!--    </tr>-->
<!--</table>-->
<?php
// tabbed information boxes
/*
  $moddir = DP_BASE_DIR . '/modules/companies/';
  $tabBox = new CTabBox(('?m=companies&amp;a=view&amp;company_id=' . $company_id), '', $tab);
  $tabBox->add($moddir . 'vw_active', 'Active Projects');
  $tabBox->add($moddir . 'vw_archived', 'Archived Projects');
  $tabBox->add($moddir . 'vw_depts', 'Departments');
  $tabBox->add($moddir . 'vw_users', 'Users');
  $tabBox->add($moddir . 'vw_contacts', 'Contacts');
  $tabBox->loadExtras($m);
  $tabBox->loadExtras($m, 'view');
  $tabBox->show();
 */
?>
