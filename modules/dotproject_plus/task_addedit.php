<?php
require_once (DP_BASE_DIR . "/modules/timeplanning/control/controller_company_role.class.php");
require_once (DP_BASE_DIR . "/modules/projects/projects.class.php");

$taskId = $_GET['task_id'];
$itemId = $_GET['item_id'];
$projectId = $_GET['project_id'];
$companyId = $_GET['company_id'];
$arrData = array(
    'activity_description' => null,
    'planned_start_date_activity' => null,
    'planned_end_date_activity' => null,
    'planned_effort' => null,
    'planned_effort_unit' => null,
    'task_owner' => null,
    'item_id' => null
);
$project = new CProject();
$project->load($projectId);
$controllerCompanyRole = new ControllerCompanyRole();
$rolesArr = $controllerCompanyRole->getCompanyRoles($project->project_company);
$roles = array();
foreach ($rolesArr as $r) {

    $roles[] = array(
        'id' => $r->getId(),
        'name' => $r->getDescription()
    );
}
$jsonRoles = json_encode($roles);

$q = new DBQuery();
$q->addTable('contacts', 'c');
$q->addQuery('user_id, human_resource_id, contact_id,u.user_username');
$q->innerJoin('users', 'u', 'u.user_contact = c.contact_id');
$q->innerJoin('human_resource', 'h', 'h.human_resource_user_id = u.user_id');
$q->addWhere('c.contact_company = ' . $project->project_company);
$q->addOrder("u.user_username");
$sql = $q->prepare();
$hr = db_loadList($sql);

if ($taskId) {
    // TODO buscar dados da atividade
}

$effortMetrics = array();
$effortMetrics[0] = 'Pessoas/Hora';
$effortMetrics[1] = 'Pessoas/Minuto';
$effortMetrics[2] = 'Pessoas/Dia';


?>
<form name="taskForm">

    <div class="form-group">
        <span class="required"></span>
        <?=$AppUI->_('requiredField');?>
    </div>

    <div class="form-group">
        <label class="required" for="<?=$AppUI->_("LBL_DESCRICAO")?>"><?=$AppUI->_("LBL_DESCRICAO")?></label>
        <input type="text"
           name="activity_description"
           id="taskDescription"
           class="form-control form-control-sm"
           maxlength="50"
           value="<?=$arrData['activity_description']?>" />
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="<?=$AppUI->_("LBL_DATA_INICIO")?>"><?=$AppUI->_("LBL_DATA_INICIO")?></label>
                <input type="text"
                   name="planned_start_date_activity"
                   class="form-control form-control-sm datepicker"
                   id="planned_start_date_activity"
                   placeholder="dd/mm/yyyy"
                   value="<?=$arrData['planned_start_date_activity']?>" />
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="<?=$AppUI->_("LBL_DATA_FIM")?>"><?=$AppUI->_("LBL_DATA_FIM")?></label>
                <input type="text"
                    name="planned_end_date_activity"
                    class="form-control form-control-sm datepicker"
                    id="planned_end_date_activity"
                    placeholder="dd/mm/yyyy"
                    value="<?=$arrData['planned_end_date_activity']?>" />
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="<?=$AppUI->_("LBL_EFFORT")?>"><?=$AppUI->_("LBL_EFFORT")?></label>
                <input type="text"
                    name="planned_effort"
                    id="taskDuration"
                    class="form-control form-control-sm"
                    value="<?=$arrData['planned_effort']?>" />
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="empty">&nbsp;</label>
                <select class="form-control form-control-sm"
                    name="planned_effort_unit"
                    id="effortSelect"
                    value="<?=$arrData['planned_effort_unit']?>">
                    <option></option>
                    <?php
                        $i = 0;
                        foreach ($effortMetrics as $metric) {
//                        $selected = $i == $projectTaskEstimation->getEffortUnit() ? "selected" : "";
                            echo "<option value=\"$i\">$metric</option>";
                            $i++;
                        }

                    ?>
                </select>
            </div>
        </div>
    </div>
    <div class="form-group">
        <label for="<?=$AppUI->_("LBL_OWNER")?>"><?=$AppUI->_("LBL_OWNER")?></label>
        <select class="form-control form-control-sm"
            id="taskOwner"
            name="task_owner"
            value="<?=$arrData['task_owner']?>">
            <?php
            $query = new DBQuery();
            $query->addTable("users", "u");
            $query->addQuery("user_id, user_username, contact_last_name, contact_first_name, contact_id");
            $query->addJoin("contacts", "c", "u.user_contact = c.contact_id");
            $query->addWhere("c.contact_company = " . $companyId);
            $query->addOrder("contact_last_name");
            $res = & $query->exec();
            for ($res; !$res->EOF; $res->MoveNext()) {
                $user_id = $res->fields["user_id"];
                $user_name = $res->fields["contact_first_name"] . " " . $res->fields["contact_last_name"];
                ?>
                <option value="<?php echo $user_id; ?>">
                    <?php echo $user_name; ?>
                </option>
                <?php
            }
            ?>
        </select>
    </div>

    <div class="row">
        <div class="col-md-12">
            <label for="roles">Recursos</label>
            <div class="row row-role-hr">
                <div class="col-md-6">
                    <div class="form-group">
                        <select class="form-control form-control-sm">
                            <?php
                            foreach ($roles as $r) {
                                ?>
                                <option></option>
                                <option value="<?=$r['id']?>">
                                    <?=$r['name']?>
                                </option>
                                <?php
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <select class="form-control form-control-sm">
                            <?php
                            foreach ($hr as $record) {
                                ?>
                                <option></option>
                                <option value="<?=$record["human_resource_id"]?>">
                                    <?=$record["user_username"]?>
                                </option>
                                <?php
                            }
                            ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <button type="button" class="btn btn-secondary btn-sm" id="btnAddResource">Adicionar recurso</button>
                </div>
            </div>
        </div>
    </div>
    <input name="dosql" type="hidden" value="do_save_activity_estimations" />
    <input name="roles_human_resources" type="hidden" value="" id="rolesHrHidden"/>
    <input type="hidden" id="taskWbsItemId" name="item_id" value="<?=$itemId?>" />
</form>

<script>

    var form = {

        roles: JSON.parse('<?=$jsonRoles?>'),

        hrs: JSON.parse('<?=json_encode($hr)?>'),

        init: function() {
            $('.datepicker').datepicker({
                dateFormat: 'dd/mm/yy'
            });

            $('#btnAddResource').on('click', form.addResource);

            // Initializes selects
            form.initSelect($("#taskOwner"), '');
            form.initSelect($("#effortSelect"), 'Unidade de medida');

            var selectsRoleHr = $(".row-role-hr").find('select');
            form.initSelect($(selectsRoleHr[0]), 'Papel');
            form.initSelect($(selectsRoleHr[1]), 'Recurso humano');
        },

        initSelect: function (select, placeholder) {
            select.select2({
                placeholder: placeholder,
                allowClear: true,
                theme: "bootstrap",
                dropdownParent: $("#taskModal")
            });
        },

        addResource: function() {

            var row = $('.row-role-hr');

            var colRole = $('<div class="col-md-6"></div>');
            var groupRole = $('<div class="form-group"></div>');
            var selectRole = $('<select class="form-control form-control-sm"></select>');
            $('<option></option>').appendTo(selectRole);
            form.roles.forEach(function(role) {
                $('<option value="'+role.id+'">'+role.name+'</option>').appendTo(selectRole);
            });

            selectRole.appendTo(groupRole);
            groupRole.appendTo(colRole);

            var colHr = $('<div class="col-md-6"></div>');
            var groupHr = $('<div class="form-group"></div>');
            var selectHr = $('<select class="form-control form-control-sm"></select>');
            $('<option></option>').appendTo(selectHr);
            form.hrs.forEach(function(hr) {
                $('<option value="'+hr.human_resource_id+'">'+hr.user_username+'</option>').appendTo(selectHr);
            });

            selectHr.appendTo(groupHr);
            groupHr.appendTo(colHr);

            colRole.appendTo(row);
            colHr.appendTo(row);

            form.initSelect(selectRole, 'Papel');
            form.initSelect(selectHr, 'Recurso humano');

        }
    };


    $(document).ready(function() {
        form.init();
    });

</script>
<?php
exit();
?>
