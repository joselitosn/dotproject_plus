<?php /* $Id: index.php 6200 2013-01-15 06:24:08Z ajdonnison $ */
if (!defined('DP_BASE_DIR')) {
  die('You should not access this file directly.');
}

if (!($canAccess)) {
	$AppUI->redirect('m=public&a=access_denied');
}

$perms =& $AppUI->acl();
$q = new DBQuery;
$search_string = dPgetCleanParam($_GET, 'search_string', null);

// To configure an aditional filter to use in the search string
$additional_filter = '';
if ($search_string) {
	$get_search = $q->quote_sanitised('%' . $search_string . '%');
	$additional_filter = ("contact_first_name LIKE " . $get_search
	                      . " OR contact_last_name LIKE " . $get_search
	                      . " OR company_name LIKE " . $get_search
	                      . " OR contact_notes LIKE " . $get_search
	                      . " OR contact_email LIKE " . $get_search);
}
$where = $q->quote_sanitised( $search_string . '%');
// Pull First Letters
$let = ":";
$search_map = array('contact_order_by', 'contact_first_name', 'contact_last_name');
foreach ($search_map as $search_name) {
	$q->addTable('contacts', 'c');
	$q->leftJoin('users', 'u', 'u.user_contact=c.contact_id');
	$q->addQuery('DISTINCT UPPER(SUBSTRING(' . $search_name . ',1,1)) as L, user_id');
	$q->addWhere('contact_private = 0 OR (contact_private = 1 AND contact_owner = ' 
	             . $AppUI->user_id . ') OR contact_owner IS NULL OR contact_owner = 0');
	$arr = $q->loadList();
	foreach ($arr as $L) {
		if (!($L['user_id']) || $perms->checkLogin($L['user_id'])) {
			$let .= $L['L'];
		}
	}
}
$q->clear();

// optional fields shown in the list (could be modified to allow breif and verbose, etc)
$showfields = array('contact_company' => 'contact_company', 'company_name' => 'company_name',
                    'contact_phone' => 'contact_phone','contact_email' => 'contact_email');

require_once $AppUI->getModuleClass('companies');
$company = new CCompany;
$allowedCompanies = $company->getAllowedSQL($AppUI->user_id);

// assemble the sql statement
$q->addTable('contacts', 'a');
$q->leftJoin('companies', 'b', 'a.contact_company = b.company_id');
$q->leftJoin('users', 'u', 'u.user_contact=a.contact_id');
$q->addQuery('contact_id, contact_order_by');
$q->addQuery('contact_first_name, contact_last_name, contact_address1, contact_phone, contact_owner, contact_mobile, contact_email, contact_notes, contact_job');
$q->addQuery($showfields);
$q->addQuery('user_id');
foreach ($search_map as $search_name) {
	$where_filter .= (' OR ' . $search_name . " LIKE $where");
}
$where_filter = mb_substr($where_filter, 4);
$where_filter .= (($additional_filter) ? (' OR ' . $additional_filter) : '');
$q->addWhere('(' . $where_filter . ')');
$q->addWhere('(contact_private = 0 OR (contact_private = 1 AND contact_owner = ' . $AppUI->user_id 
             . ') OR contact_owner IS NULL OR contact_owner = 0)');
if (count($allowedCompanies)) {
	$comp_where = implode(' AND ', $allowedCompanies);
	$q->addWhere('((' . $comp_where . ') OR contact_company = 0)');
}
$q->addOrder('contact_order_by');
$sql = $q->prepare();
$q->clear();

$rn = 0;
$disp_arr = array();
if (!($res = db_exec($sql))) {
	echo db_error();
} else {
	while ($row = db_fetch_assoc($res)) {
		if (!($row['user_id']) || $perms->checkLogin($row['user_id'])) {
			$disp_arr[] = $row;
			$rn++;
		}
	}
}

?>
<div id="content">
    <fieldset>
        <legend><?=$AppUI->_('Contacts')?></legend>
        <div class="row">
            <div class="col-md-4">
                <form action="./index.php" method="get">
                    <div class="form-group">
                        <input type="text" class="form-control form-control-sm" placeholder="<?=$AppUI->_('Search for')?>" name="search_string" value="<?=$search_string?>" />
                        <input type="hidden" name="m" value="contacts" />
                    </div>
                </form>
            </div>
            <div class="col-md-8 text-right">
                <a href="?m=contacts&amp;a=csvexport&amp;suppressHeaders=true" class="btn btn-sm btn-secondary">
                    <?=$AppUI->_('CSV Download')?>
                </a>
                <button type="button" class="btn btn-sm btn-secondary" data-toggle="modal" data-target="#vcardModal">
                    <?=$AppUI->_('Import vCard')?>
                </button>
                <button type="button" class="btn btn-sm btn-secondary" onclick="contact.new()">
                    <?=$AppUI->_('new contact')?>
                </button>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <?php
                    foreach ($disp_arr as $contact) {
                ?>
                        <div class="card inner-card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-10 action mouse-cursor-pointer">
                                        <h5 data-toggle="collapse" data-target="#contactDetails_<?=$contact['contact_id']?>">
                                            <?=$contact['contact_first_name'].' '.$contact['contact_last_name']?>
                                            <i class="fa fa-caret-down"></i>
                                        </h5>
                                    </div>
                                    <div class="col-md-2 text-right">
                                        <div class="dropdown">
                                            <a href="javascript:void(0)" class="link-primary" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="fas fa-bars"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink">
                                                <a class="dropdown-item" href="javascript:void(0)" onclick="contact.update(<?= $contact['contact_id'] ?>)">
                                                    <i class="far fa-edit"></i>
                                                    Alterar
                                                </a>
                                                <?php
                                                    $row = new CContact();
                                                    $canDelete = $row->canDelete($msg, $contact_id);
                                                    if ($canDelete) {
                                                        ?>
                                                        <a class="dropdown-item" href="javascript:void(0)"
                                                           onclick="contact.delete(<?= $contact['contact_id'] ?>)">
                                                            <i class="far fa-trash-alt"></i>
                                                            Excluir
                                                        </a>
                                                        <?php
                                                    }
                                                    ?>
                                                <a class="dropdown-item" href="?m=contacts&a=vcardexport&suppressHeaders=true&contact_id=<?=$contact['contact_id']?>">
                                                    <i class="fas fa-download"></i>
                                                    Baixar vCard
                                                </a>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <div class="collapse" id="contactDetails_<?=$contact['contact_id']?>">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="row">
                                                <div class="col-md-5 text-right"><b>Cargo:</b></div>
                                                <div class="col-md-7">
                                                    <?=$contact['contact_job']?>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-5 text-right"><b>Endereço:</b></div>
                                                <div class="col-md-7">
                                                    <?=$contact['contact_address1']?>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-5 text-right"><b>Telefone:</b></div>
                                                <div class="col-md-7">
                                                    <?=$contact['contact_phone']?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="row">
                                                <div class="col-md-5 text-right"><b>Celular:</b></div>
                                                <div class="col-md-7">
                                                    <?=$contact['contact_mobile']?>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-5 text-right"><b>Email:</b></div>
                                                <div class="col-md-7">
                                                    <?=$contact['contact_email']?>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-5 text-right"><b>Empresa:</b></div>
                                                <div class="col-md-7">
                                                    <?=$contact['company_name']?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="row">
                                                <div class="col-md-5 text-right"><b>Observações:</b></div>
                                                <div class="col-md-7">
                                                    <?=$contact['contact_notes']?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                <?php
                    }
                ?>
            </div>
        </div>
    </fieldset>
</div>

<div id="vcardModal" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?=$AppUI->_('Import vCard')?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="<?=$AppUI->_("LBL_CLOSE")?>">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form name="vcfFrm">
                    <input type="hidden" name="max_file_size" value="109605000" />
                    <input type="hidden" name="dosql" value="vcardimport" />
                    <div class="form-group">
                        <label for="<?=$AppUI->_('Fetch vCard(s) File')?>"><?=$AppUI->_('Fetch vCard(s) File')?></label>
                        <input type="File" class="form-control-file" name="file" id="fileInput" accept="text/x-vcard" />
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal"><?=$AppUI->_("LBL_CLOSE")?></button>
                <button type="button" class="btn btn-primary btn-sm" onclick="contact.importVCard()"><?=$AppUI->_("Enviar")?></button>
            </div>
        </div>
    </div>
</div>

<div id="addEditModal" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="<?=$AppUI->_("LBL_CLOSE")?>">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body addEditContact">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal"><?=$AppUI->_("LBL_CLOSE")?></button>
                <button type="button" class="btn btn-primary btn-sm" onclick="contact.save()"><?=$AppUI->_("LBL_SAVE")?></button>
            </div>
        </div>
    </div>
</div>

<script>
    
    var contact = {
        
        init: function () {
            $('.inner-card').find('h5').on('click', function(e) {;
                if ($(this).find('i').hasClass('fa-caret-down')) {
                    $(this).find('i').removeClass('fa-caret-down');
                    $(this).find('i').addClass('fa-caret-up');
                } else {
                    $(this).find('i').removeClass('fa-caret-up');
                    $(this).find('i').addClass('fa-caret-down');
                }
            });

        },
        
        new: function () {
            $.ajax({
                type: "get",
                url: "?m=contacts&template=addedit"
            }).done(function(response) {
                var modal = $('#addEditModal');
                modal.find('h5').html('Adicionar contato');
                $('.addEditContact').html(response);
                modal.modal();
            });
        },

        update: function (id) {
            $.ajax({
                type: "get",
                url: "?m=contacts&template=addedit&contact_id="+id
            }).done(function(response) {
                var modal = $('#addEditModal');
                modal.find('h5').html('Alterar contato');
                $('.addEditContact').html(response);
                modal.modal();
            });
        },

        save: function () {

            var cfn = $('input[name=contact_first_name]').val();
            var cln = $('input[name=contact_last_name]').val();
            if (!cfn || !cln) {
                $.alert({
                    title: "Erro",
                    content: "O primeiro e último nome são obrigatórios"
                });
                return;
            }

            $.ajax({
                method: 'POST',
                url: "?m=contacts",
                data: $("form[name=changecontact]").serialize(),
            }).done(function(resposta) {
                $.alert({
                    title: "Sucesso",
                    content: resposta,
                    onClose: function() {
                        window.location.reload(true);
                    }
                });
            });
        },

        delete: function (id) {
            $.confirm({
                title: 'Excluir contato',
                content: 'Você tem certeza de que quer excluir esse contato?',
                buttons: {
                    yes: {
                        text: 'Sim',
                        action: function () {
                            $.ajax({
                                method: 'POST',
                                url: "?m=contacts",
                                data: {
                                    dosql: 'do_contact_aed',
                                    del: 1,
                                    contact_id: id
                                }
                            }).done(function(resposta) {
                                $.alert({
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
        
        importVCard: function () {
            var file = $('#fileInput')[0].files;

            if (file.length == 0) {
                $.alert({
                    title: "Erro",
                    content: "Nenhum arquivo selecionado"
                });
                return;
            }
            var data = new FormData($("form[name=vcfFrm]")[0]);

            $.ajax({
                method: 'POST',
                url: "?m=contacts",
                data: data,
                processData: false,
                contentType: false,
                success: function(resposta) {
                    $.alert({
                        title: "Sucesso",
                        content: resposta,
                        onClose: function() {
                            window.location.reload(true);
                        }
                    });
                },
                error: function(resposta) {
                    $.alert({
                        title: "Erro",
                        content: "Algo deu errado"
                    });
                }
            });
        }
    };

    $(document).ready(contact.init);
</script>
