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
// retrieve any state parameters
if ($search_string) {
	$AppUI->setState('ContIdxWhere', $search_string);
	$get_search = $q->quote_sanitised('%' . $search_string . '%');
	$additional_filter = ("contact_first_name LIKE " . $get_search
	                      . " OR contact_last_name LIKE " . $get_search
	                      . " OR company_name LIKE " . $get_search
	                      . " OR contact_notes LIKE " . $get_search
	                      . " OR contact_email LIKE " . $get_search);
} else if (isset($_GET['where'])) {
	$AppUI->setState('ContIdxWhere', $_GET['where']);
}

$where = $q->quote_sanitised( $AppUI->getState('ContIdxWhere') ? ( $AppUI->getState('ContIdxWhere') . '%') : '%');

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
$q->addQuery('contact_first_name, contact_last_name, contact_phone, contact_owner');
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
$default_search_string = dPformSafe($AppUI->getState('ContIdxWhere'), true);

?>
<div id="content">
    <fieldset>
        <legend><?=$AppUI->_('Contacts')?></legend>
        <div class="row">
            <div class="col-md-4">
                <form action="./index.php" method="get">
                    <div class="form-group">
                        <input type="text" class="form-control form-control-sm" placeholder="<?=$AppUI->_('Search for')?>" name="search_string" value="<?=$default_search_string?>" />
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
                                    <div class="col-md-5 action mouse-cursor-pointer">
                                        <h5><a href="?m=companies&a=view&company_id=<?=$contact['contact_id']?>"><?=$contact['contact_first_name'].' '.$contact['contact_last_name']?></a></h5>

                                        <!-- TODO listar detalhes do contato em collapse, criar menu para: 1-Alterar; 2-Download vcard; 3-Excluir -->

                                        
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

<script>
    
    var contact = {
        
        init: function () {
            
        },
        
        new: function () {
            
        },
        
        importVCard: function () {
            var file = $('#fileInput')[0].files;

            if (file.length == 0) {
                $.alert({
                    title: "Error",
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



<table width="100%" border="0" cellpadding="1" cellspacing="1" style="height:400px;" class="contacts" summary="Contacts">
<tr>
<?php
for ($z = 0; $z < $carrWidth; $z++) {
?>
	<td valign="top" align="left" bgcolor="#f4efe3" width="<?php echo $tdw;?>%">
	<?php
	for ($x = 0; $x < @count($carr[$z]); $x++) {
	?>
		<table width="100%" cellspacing="1" cellpadding="1" summary="contact info">
		<tr>
			<td width="100%">
				<?php $contactid = $carr[$z][$x]['contact_id']; ?>
				<a href="?m=contacts&amp;a=view&amp;contact_id=<?php 
		echo $contactid; 
?>"><strong><?php 
		echo $AppUI->___(($carr[$z][$x]['contact_order_by']) 
		      ? $carr[$z][$x]['contact_order_by'] 
		      : ($carr[$z][$x]['contact_first_name'] . ' ' . $carr[$z][$x]['contact_last_name'])); 
?></strong></a>&nbsp;
				&nbsp;<a title="<?php 
		echo $AppUI->___($AppUI->_('Export vCard for') . ' ' . $carr[$z][$x]['contact_first_name'] . ' ' 
		      . $carr[$z][$x]['contact_last_name']); 
?>" href="?m=contacts&amp;a=vcardexport&amp;suppressHeaders=true&amp;contact_id=<?php 
		echo $contactid; ?>" >(vCard)</a>
				&nbsp;<a title="<?php 
		echo $AppUI->_('Edit'); ?>" href="?m=contacts&amp;a=addedit&amp;contact_id=<?php 
		echo $contactid; ?>"><?php echo $AppUI->_('Edit'); ?></a>
<?php
		$q = new DBQuery;
		$q->addTable('projects');
		$q->addQuery('count(*)');
		$q->addWhere('project_contacts LIKE "' . $carr[$z][$x]['contact_id'] 
		             .',%" OR project_contacts LIKE "%,' . $carr[$z][$x]['contact_id'] 
		             .',%" OR project_contacts LIKE "%,' . $carr[$z][$x]['contact_id'] 
		             .'" OR project_contacts LIKE "' . $carr[$z][$x]['contact_id'] .'"');
		
		$res = $q->exec();
		$projects_contact = db_fetch_row($res);
		$q->clear();
		if ($projects_contact[0] > 0) {
			echo ('&nbsp;<a href="" onclick="javascript:window.open(' 
			      . "'?m=public&amp;a=selector&amp;dialog=1&amp;callback=goProject&amp;table=projects" 
			      . '&user_id=' . $carr[$z][$x]['contact_id'] 
			      . "', 'selector', 'left=50,top=50,height=250,width=400,resizable');" 
			      . 'return false;">' . $AppUI->_('Projects') . '</a>');
		}
?>
			</td>
		</tr>
		<tr>
			<td class="hilite">
			<?php
		reset($showfields);
		while (list($key, $val) = each($showfields)) {
			if (mb_strlen($carr[$z][$x][$key]) > 0) {
				if ($val == "contact_email") {
					echo ('<a href="mailto:' . $carr[$z][$x][$key] . '" class="mailto">' 
					      . $carr[$z][$x][$key] . "</a>\n");
				} else if (!($val == "contact_company" && is_numeric($carr[$z][$x][$key]))) {
					echo  $carr[$z][$x][$key]. "<br />";
				}
			}
		} ?>
			</td>
		</tr>
		</table>
		<br />&nbsp;<br />
	<?php 
	} ?>
	</td>
<?php 
} ?>
</tr>
</table>
