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
                    <?php
                    //metric index is db key
                    $effortMetrics = array();
                    $effortMetrics[0] = $AppUI->_("LBL_EFFORT_HOURS");
                    $effortMetrics[1] = $AppUI->_("LBL_EFFORT_MINUTES");
                    $effortMetrics[2] = $AppUI->_("LBL_EFFORT_DAYS");
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
                        <select class="form-control form-control-sm select-role">
                            <?php
                            foreach ($rolesArr as $record) {
                                ?>
                                <option></option>
                                <option value="<?=$record->getId()?>">
                                    <?=$record->getDescription()?>
                                </option>
                                <?php
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <select class="form-control form-control-sm select-hr">
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
            <div class="row">
                <div class="col-md-12">
                    <button type="button" class="btn btn-secondary btn-sm" onclick="form.addResource()">Adicionar recurso</button>
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

        init: function() {
            $('.datepicker').datepicker();

            $("#effortSelect").select2({
                placeholder: "",
                allowClear: true,
                theme: "bootstrap",
                dropdownParent: $("#taskModal")
            });

            $("#taskOwner").select2({
                placeholder: "",
                allowClear: true,
                theme: "bootstrap",
                dropdownParent: $("#taskModal")
            });

            $(".select-role").select2({
                placeholder: "Papel",
                allowClear: true,
                theme: "bootstrap",
                dropdownParent: $("#taskModal")
            });

            $(".select-hr").select2({
                placeholder: "Recurso humano",
                allowClear: true,
                theme: "bootstrap",
                dropdownParent: $("#taskModal")
            });
        },

        addResource: function() {
            var children = $('.row-role-hr').children();

            $(children[0]).find('select').select2("destroy").end().clone().appendTo('.row-role-hr');
            $(children[1]).find('select').select2("destroy").end().clone().appendTo('.row-role-hr');

            $(".select-role").select2({
                placeholder: "Papel",
                allowClear: true,
                theme: "bootstrap",
                dropdownParent: $("#taskModal")
            });

            $(".select-hr").select2({
                placeholder: "Recurso humano",
                allowClear: true,
                theme: "bootstrap",
                dropdownParent: $("#taskModal")
            });

        }
    };


    $(document).ready(function() {
        form.init();
    });

</script>
<?php
exit();
?>
