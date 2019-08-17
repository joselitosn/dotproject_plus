<?php
require_once (DP_BASE_DIR . "/modules/timeplanning/control/controller_company_role.class.php");
require_once (DP_BASE_DIR . "/modules/projects/projects.class.php");
require_once (DP_BASE_DIR . "/modules/tasks/tasks.class.php");

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

$obj = new CTask();
$tasksEstimations = array('effort' => '', 'effort_unit' => '');
if ($taskId) {
    $obj->task_id = $taskId;
    $obj->load();

    $q = new DBQuery();
    $q->addQuery("t.effort, t.effort_unit");
    $q->addTable("project_tasks_estimations", "t");
    $q->addWhere("t.task_id= " . $taskId);
    $sql = $q->prepare();
    $tasksEstimations = db_loadList($sql);
    $tasksEstimations = current($tasksEstimations);

    // select r.role_id, hra.human_resource_id from dot_project.dotp_project_tasks_estimated_roles r
    // left join dot_project.dotp_human_resource_allocation hra on hra.project_tasks_estimated_roles_id = r.id;
    $q = new DBQuery();
    $q->addQuery("r.role_id, hra.human_resource_id");
    $q->addTable("project_tasks_estimated_roles", "r");
    $q->addJoin("human_resource_allocation", "hra", "hra.project_tasks_estimated_roles_id = r.id");
    $q->addWhere("r.task_id= " . $taskId);
    $sql = $q->prepare();
    $resources = db_loadList($sql);
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
           value="<?=$obj->task_name?>" />
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
                   value="<?=date("d/m/Y", strtotime($obj->task_start_date))?>" />
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
                    value="<?=date("d/m/Y", strtotime($obj->task_end_date))?>" />
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
                    value="<?=$tasksEstimations['effort']?>" />
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="empty">&nbsp;</label>
                <select class="form-control form-control-sm"
                    name="planned_effort_unit"
                    id="effortSelect">
                    <option></option>
                    <?php
                        $i = 0;
                        foreach ($effortMetrics as $metric) {
                            $selected = $tasksEstimations['effort_unit'] == $i ? " selected" : "";
                            echo "<option value=\"$i\" $selected>$metric</option>";
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
            name="task_owner">
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
                $selected = $obj->task_owner == $user_id ? " selected" : "";
                ?>
                <option></option>
                <option value="<?php echo $user_id; ?>" <?=$selected?>>
                    <?php echo $user_name; ?>
                </option>
                <?php
            }
            ?>
        </select>
    </div>

    <div class="row">
        <div class="col-md-12 resources-container">
            <label for="roles">Recursos</label>
            <div class="row">

                <div class="col-md-6">
                    <div class="form-group">
                        <select class="form-control form-control-sm select-role">
                            <?php
                            foreach ($roles as $r) {
                                $selected = $resources[0]['role_id'] == $r['id'] ? " selected" : "";
                                ?>
                                <option></option>
                                <option value="<?=$r['id']?>"<?=$selected?>>
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
                        <select class="form-control form-control-sm select-hr">
                            <?php
                            foreach ($hr as $record) {
                                $selected = $resources[0]['human_resource_id'] == $record['human_resource_id'] ? " selected" : "";
                                ?>
                                <option></option>
                                <option value="<?=$record["human_resource_id"]?>"<?=$selected?>>
                                    <?=$record["user_username"]?>
                                </option>
                                <?php
                            }
                            ?>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <button type="button" class="btn btn-secondary btn-sm" id="btnAddResource">Adicionar recurso</button>
        </div>
    </div>
    <input name="dosql" type="hidden" value="do_save_activity_estimations" />
    <input name="roles_human_resources" type="hidden" value='<?=$resources ? json_encode($resources) : ''?>' id="rolesHrHidden"/>
    <input type="hidden" id="taskWbsItemId" name="item_id" value="<?=$itemId?>" />
    <input type="hidden" id="taskId" name="task_id" value="<?=$taskId?>" />
</form>
<?php
?>

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

            var selectsRoleHr = $(".resources-container").find('select');
            form.initSelect($(selectsRoleHr[0]), 'Papel');

            var dis = ($(selectsRoleHr[0]).val() || $(selectsRoleHr[1]).val()) ? false : true;
            form.initSelect($(selectsRoleHr[1]), 'Recurso humano', null, dis);

            var resourcesJson = $('#rolesHrHidden').val();
            if (resourcesJson) {
                var res = JSON.parse(resourcesJson);
                res.splice(0, 1);
                res.forEach(function(r) {
                   form.addResource(r.role_id, r.human_resource_id, false);
                });
            }
        },

        initSelect: function (select, placeholder, parent, disabled) {
            select.select2({
                placeholder: placeholder,
                allowClear: true,
                disabled: (select.hasClass('select-hr') && disabled) ? true : false,
                theme: "bootstrap",
                dropdownParent: parent || $(".resources-container")
            });
            if (select.hasClass('select-role')) {
                var selectHr = select.parent().parent().next().children().children();
                select.on('select2:select', function() {
                    selectHr.attr('disabled', false);
                });
                select.on('select2:unselect', function() {
                    selectHr.val(null).trigger('change');
                    selectHr.attr('disabled', true);
                });
            }
        },

        addResource: function(roleId, hrId, disabled) {
            var container = $('.resources-container');

            var row = $('<div class="row"></div>');

            var colRole = $('<div class="col-md-6"></div>');
            var groupRole = $('<div class="form-group"></div>');
            var selectRole = $('<select class="form-control form-control-sm select-role"></select>');
            $('<option></option>').appendTo(selectRole);
            form.roles.forEach(function(role) {
                var selected = role.id == roleId ? ' selected' : '';
                $('<option value="'+role.id+'"'+selected+'>'+role.name+'</option>').appendTo(selectRole);
            });

            selectRole.appendTo(groupRole);
            groupRole.appendTo(colRole);

            var colHr = $('<div class="col-md-5"></div>');
            var groupHr = $('<div class="form-group"></div>');
            var selectHr = $('<select class="form-control form-control-sm select-hr" disabled></select>');
            $('<option></option>').appendTo(selectHr);
            form.hrs.forEach(function(hr) {
                var selected = hr.human_resource_id == hrId ? ' selected' : '';
                $('<option value="'+hr.human_resource_id+'"'+selected+'>'+hr.user_username+'</option>').appendTo(selectHr);
            });

            var colBtn = $('<div class="col-md-1"></div>');
            var groupBtn = $('<div class="form-group"></div>');
            var btn = $('<button type="button" class="btn btn-sm btn-danger" title="Remover recurso"></button>');
            btn.on('click', form.removeResource);
            var icon = $('<i class="far fa-trash-alt"></i>');
            icon.appendTo(btn);
            btn.appendTo(groupBtn);
            groupBtn.appendTo(colBtn);

            selectHr.appendTo(groupHr);
            groupHr.appendTo(colHr);

            colRole.appendTo(row);
            colHr.appendTo(row);
            colBtn.appendTo(row);

            row.appendTo(container);

            form.initSelect(selectRole, 'Papel', row, disabled);
            var dis = (disabled != null && disabled != undefined) ? disabled : true;
            form.initSelect(selectHr, 'Recurso humano', row, dis);

        },

        removeResource: function () {
            $(this).parent().parent().parent().remove();
        }
    };


    $(document).ready(function() {
        form.init();
    });

</script>
<?php
exit();
?>
