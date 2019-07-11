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


<div class="container-fluid">
    <div class="row header-2">
        <div class="col-md-12">
            <h4><?=htmlspecialchars($obj->company_name)?></h4>
            <small>
                <?=$AppUI->_("Owner")?>:
                <?=htmlspecialchars($obj->contact_first_name) . '&nbsp;' . htmlspecialchars($obj->contact_last_name)?>
                |
                <?=$AppUI->_('Address')?>:
                <?=$obj->company_address1 ? htmlspecialchars($obj->company_address1) : 'Não informado'?>
                |
                <?=$AppUI->_('Zip')?>:
                <?=$obj->company_zip ? htmlspecialchars($obj->company_zip) : 'Não informado'?>
                |
                <?=$AppUI->_('City')?>:
                <?=$obj->company_city ? dPformSafe($obj->company_city) : 'Não informado'?>
                |
                <?=$AppUI->_('State')?>:
                <?=$obj->company_state ? htmlspecialchars($obj->company_state) : 'Não informado'?>
                |
                <?=$AppUI->_('Email')?>:
                <?=$obj->company_email ? htmlspecialchars($obj->company_email) : 'Não informado'?>
                |
                <?=$AppUI->_("Phone")?>:
                <?=$obj->company_phone1 ? htmlspecialchars($obj->company_phone1) : 'Não informado'?>
            </small>
            <small class="float-right">
                <a href="javascript:void(0)" id="linkCopanyPolicies">
                    <?=$AppUI->_('LBL_ORGANIZATIONAL_POLICY')?>
                </a>
            </small>
        </div>
    </div>
</div>

<div class="modal" tabindex="-1" role="dialog" id="modalCompanyPolicies">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?=$AppUI->_('LBL_ORGANIZATIONAL_POLICY')?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <h6><?=$AppUI->_("LBL_REWARDS")?></h6>
                <p><?=$policies->company_policies_recognition?></p>

                <h6><?=$AppUI->_("LBL_RUGULATIONS")?></h6>
                <p><?=$policies->company_policies_policy?></p>

                <h6><?=$AppUI->_("Safety")?></h6>
                <p><?=$policies->company_policies_safety?></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal"><?=$AppUI->_('LBL_CLOSE')?></button>
            </div>
        </div>
    </div>
</div>

<div class="wrapper">
    <!-- Sidebar -->
    <nav id="sidebar" style="margin-top:70px;">
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
    <div id="content" style="margin-top:70px;">
        <fieldset>
            <div class="row">
                <div class="col-md-12">
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
                </div>
            </div>

            <div class="modal" id="addEditProjectModal" tabindex="-1" role="dialog">
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
                    console.log($('#linkCopanyPolicies'));
                    $('#linkCopanyPolicies').on('click', company.showPolicies);
                });

            },

            showPolicies: function () {
                console.log('aquiiii');
                console.log($('#modalCompanyPolicies'));
                $('#modalCompanyPolicies').modal();
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
                        $("#addEditProjectModal").modal();
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
                        $("#addEditProjectModal").modal();
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
                            $("#addEditProjectModal").modal("hide");
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
