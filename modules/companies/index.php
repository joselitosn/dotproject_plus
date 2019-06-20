<?php /* COMPANIES $Id: index.php 6149 2012-01-09 11:58:40Z ajdonnison $ */
if (!defined('DP_BASE_DIR')) {
  die('You should not access this file directly.');
}

// First order check if we are allowed to view
if (!$canAccess) {
	$AppUI->redirect('m=public&a=access_denied');
}
$AppUI->savePlace();

$valid_ordering = array('company_name', 'countp', 'inactive', 'company_type');

$search_string = dPgetCleanParam($_REQUEST, 'search_string', '');

if (isset($_GET['orderby']) && in_array($_GET['orderby'], $valid_ordering)) {
	$orderdir = (($AppUI->getState('CompIdxOrderDir')
				  ? (($AppUI->getState('CompIdxOrderDir') == 'asc') ? 'desc' : 'asc') : 'desc'));
	$AppUI->setState('CompIdxOrderBy', $_GET['orderby']);
    $AppUI->setState('CompIdxOrderDir', $orderdir);
}
$orderby = (($AppUI->getState('CompIdxOrderBy'))
            ? $AppUI->getState('CompIdxOrderBy') : 'company_name');
$orderdir = (($AppUI->getState('CompIdxOrderDir')) ? $AppUI->getState('CompIdxOrderDir') : 'asc');

$owner_filter_id = intval(dPgetParam($_REQUEST, 'owner_filter_id', 0));
if ($owner_filter_id !== 0) {
	$AppUI->setState('owner_filter_id', $owner_filter_id_pre);
}
// load the company types
$types = dPgetSysVal('CompanyType');

// get any records denied from viewing
$obj = new CCompany();
$allowedCompanies = $obj->getAllowedRecords($AppUI->user_id, 'company_id, company_name');

// retrieve list of records
$q  = new DBQuery;
$q->addTable('companies', 'c');
$q->addQuery('c.company_id, c.company_name, c.company_type, c.company_description'
    . ', count(distinct p.project_id) as countp'
    . ', count(distinct p2.project_id) as inactive'
    . ', con.contact_first_name, con.contact_last_name');
$q->addJoin('projects', 'p', 'c.company_id = p.project_company AND p.project_status <> 7');
$q->addJoin('users', 'u', 'c.company_owner = u.user_id');
$q->addJoin('contacts', 'con', 'u.user_contact = con.contact_id');
$q->addJoin('projects', 'p2', 'c.company_id = p2.project_company AND p2.project_status = 7');
if (count($allowedCompanies) > 0) {
    $q->addWhere('c.company_id IN (' . implode(',', array_keys($allowedCompanies)) . ')');
}
if ($companiesType) {
    $q->addWhere('c.company_type = ' . $company_type_filter);
}
if ($search_string != '') {
    $q->addWhere("c.company_name LIKE " . $q->quote_sanitised('%' . $search_string . '%') );
}
if ($owner_filter_id > 0) {
    $q->addWhere('c.company_owner = ' . $owner_filter_id);
}
$q->addGroup('c.company_id');
$q->addOrder($orderby . ' ' . $orderdir);
$rows = $q->loadList();

$perms=&$AppUI->acl();
$owner_list = array(-1 => $AppUI->_('All', UI_OUTPUT_RAW)) + $perms->getPermittedUsers('companies');

?>

<div id="content">
    <fieldset>
        <legend><?=$AppUI->_('Companies')?></legend>
        <div class="row">
            <div class="col-md-12">

                <form name="searchform"
                      action="?m=companies"
                      method="post">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <input type="text"
                                    style="height: 34px;"
                                    class="form-control form-control-sm"
                                    name="search_string"
                                    placeholder="Empresa"
                                    value="<?=$search_string?>">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <select class="form-control form-control-sm select-responsible"
                                    name="owner_filter_id"
                                    onchange="javascript:document.searchform.submit()">
                                    <option></option>
                                    <?php

                                        foreach ($owner_list as $key => $option) {
                                            $selected = (isset($owner_filter_id) && $owner_filter_id != 0 && $owner_filter_id == $key) ? 'selected="selected"' : '';
                                    ?>
                                            <option value="<?=$key?>" <?=$selected?>><?=$option?></option>
                                    <?php
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3"></div>
                        <div class="col-md-3 text-right">
                            <div class="dropdown">
                                <a href="javascript:void(0)" class="link-primary" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-bars"></i>
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink">
                                    <a class="dropdown-item" href="javascript:void(0)" onclick="company.create()">
                                        <i class="far fa-plus-square"></i>
                                        Nova empresa
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>

                <?php

                foreach ($rows as $company) {
                    $badgeClass = '';
                    switch ($company['company_type']) {
                        case 0:
                            $badgeClass = ' badge-secondary';
                            break;
                        case 1:
                            $badgeClass = ' badge-primary';
                            break;
                        case 2:
                            $badgeClass = ' badge-dark';
                            break;
                        case 3:
                            $badgeClass = ' badge-info';
                            break;
                        case 4:
                            $badgeClass = ' badge-secondary';
                            break;
                        case 5:
                            $badgeClass = ' badge-success';
                            break;
                        case 6:
                            $badgeClass = ' badge-warning';
                            break;
                    }
                    ?>
                    <div class="card inner-card mouse-cursor-pointer">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h5><a href="?m=companies&a=view&company_id=<?=$company['company_id']?>"><?=$company['company_name']?></a></h5>
                                </div>
                                <div class="col-md-5">
                                    <span><?=$AppUI->_('Active Projects').': '.$company['countp'].' | '.$AppUI->_('Archived Projects').': '.$company['inactive']?></span>
                                </div>
                                <div class="col-md-1 text-right">
                                    <span class="badge <?=$badgeClass?>">Tipo: <?=$AppUI->_($types[$company['company_type']])?></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <?php
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
                            <div class="modal-body">

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
    </fieldset>
</div>
<script>

    var company = {
        init: function() {
            $(".select-responsible").select2({
                placeholder: "Responsável",
                allowClear: true,
                theme: "bootstrap"
            });
            $("#addEditCompanyModal").on("hidden.bs.modal", function() {
                $("#btnSaveCompany").off("click");
                $(this).find(".modal-body").html("");
            });

            $(".card").on("click", function() {
                company.view($(this).find("a").attr("href"));
            });
        },
        create: function() {
            $.ajax({
                type: "get",
                url: "?m=companies&template=addedit"
            }).done(function(response) {
                $(".modal-body").html(response);
                $(".modal-title").html("<?=$AppUI->_('new company')?>");
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
        view: function(url) {
            window.location.href = url;
        }
    };
    company.init();

</script>