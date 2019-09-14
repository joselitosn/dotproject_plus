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
require_once (DP_BASE_DIR . "/modules/projects/project-template.php");
?>


<div class="container-fluid">
    <div class="row header-2 bg-primary">
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
                <a href="javascript:void(0)" data-toggle="modal" data-target="#modalCompanyPolicies">
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
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal"><?=$AppUI->_('LBL_CLOSE')?></button>
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
                });

                $("#addEditProjectModal").on("hidden.bs.modal", function() {
                    $("#btnSaveCompany").off("click");
                });
            }
        };
        company.init();

    </script>
