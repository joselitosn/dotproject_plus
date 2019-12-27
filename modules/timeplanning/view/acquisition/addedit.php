<?php
    require_once (DP_BASE_DIR . "/modules/timeplanning/control/acquisition/controller_acquisition_planning.class.php");
    require_once (DP_BASE_DIR . "/modules/timeplanning/model/acquisition/acquisition_planning.class.php");
    $controller = new ControllerAcquisitionPlanning();
    $projectId = $_GET["project_id"];
    $id = $_GET["acquisition_planning_id"];
    if (null === $id) {
        $id = -1;
    }
    $object = $controller->getAcquisitionPlanning($id);

    $criteria = $controller->loadCriteria($id);
    $requirements = $controller->loadRequirements($id);
    $roles = $controller->loadRoles($id);
?>
<form name="acquisitionForm">
    <input name="dosql" type="hidden" value="do_project_acquisition_planning" />
    <input name="project_id" type="hidden" value="<?=$projectId?>" />
    <input name="acquisition_planning_id" type="hidden" value="<?=$id?>" />

    <div class="form-group">
        <span class="required"></span>
        <?=$AppUI->_('requiredField');?>
    </div>
    <div class="row">
        <div class="col-md-8">
            <div class="form-group">
                <label for="items_to_be_acquired" class="required">
                    <?=$AppUI->_("LBL_ITEM_TO_ACQUIRE")?>
                </label>
                <input type="text" class="form-control form-control-sm" name="items_to_be_acquired" value="<?=$object->getItemsToBeAcquired()?>" />
            </div>
        </div>
        <div class="col-md-4 contract-type">
            <div class="form-group">
                <label for="contract_type">
                    <?=$AppUI->_("LBL_CONTRACT_TYPE")?>
                </label>
                <?php
                $types = array();
                $types[0] = "Preço fixo";
                $types[1] = "Tempo & material";
                $types[2] = "Custos reembolsáveis";
                ?>

                <select name="contract_type" class="form-control form-control-sm select-contract-type">
                    <?php
                        foreach ($types as $key => $type) {
                            $selected = $object->getContractType() == $type ? ' selected' : '';
                            ?>
                            <option value="<?=$type?>" <?=$selected?>>
                                <?=$type?>
                            </option>
                            <?php
                        }
                    ?>
                </select>
            </div>
        </div>
    </div>

    <div class="form-group">
        <label for="documents_to_acquisition">
            <?=$AppUI->_("LBL_DOCUMENTS_TO_ACQUIRE")?>
        </label>
        <textarea class="form-control form-control-sm" name="documents_to_acquisition"><?php echo $object->getDocumentsToAcquisition() ?></textarea>
    </div>

    <div class="form-group criteria" id="criteriaContainer">
        <label for="LBL_CRITERIA_TO_SUPPLIERS_SELECTION">
            <?=$AppUI->_("LBL_CRITERIA_TO_SUPPLIERS_SELECTION")?>
        </label>
        <br>
        <button type="button" class="btn btn-xs btn-secondary" onclick="addCriteria()"><?=$AppUI->_("LBL_ACQUISION_ADD_CRITERIA")?></button>
        <br>
        <br>
        <?php 
            foreach($criteria as $c) {
                ?>
                <div class="row">
                    <div class="col-md-9">
                        <input type="text" class="form-control form-control-sm" name="criteria[]" value="<?=$c['criteria']?>">
                        <br>
                    </div>
                    <div class="col-md-2">
                        <select class="form-control form-control-sm select-criteria" name="criteria_weight[]">
                            <option value="1" <?=$c['weight'] == 1 ? ' selected' : ''?>>1</option>
                            <option value="2" <?=$c['weight'] == 2 ? ' selected' : ''?>>2</option>
                            <option value="3" <?=$c['weight'] == 3 ? ' selected' : ''?>>3</option>
                            <option value="4" <?=$c['weight'] == 4 ? ' selected' : ''?>>4</option>
                            <option value="5" <?=$c['weight'] == 5 ? ' selected' : ''?>>5</option>
                            <option value="6" <?=$c['weight'] == 6 ? ' selected' : ''?>>6</option>
                            <option value="7" <?=$c['weight'] == 7 ? ' selected' : ''?>>7</option>
                            <option value="8" <?=$c['weight'] == 8 ? ' selected' : ''?>>8</option>
                            <option value="9" <?=$c['weight'] == 9 ? ' selected' : ''?>>9</option>
                            <option value="10" <?=$c['weight'] == 10 ? ' selected' : ''?>>10</option>
                        </select>
                    </div>
                    <div class="col-md-1">
                        <button type="button" class="btn btn-xs btn-danger" onclick="deleteCriteria(this, <?=$c['id']?>)">
                            <i class="far fa-trash-alt"></i>
                        </button>
                    </div>
                </div>
                <?php
            }
        ?>
    </div>

    <div class="form-group" id="requirementContainer">
        <label for="LBL_ACQUISITION_ADDITIONAL_REQUIRIMENTS">
            <?=$AppUI->_("LBL_ACQUISITION_ADDITIONAL_REQUIRIMENTS")?>
        </label>
        <br>
        <button type="button" class="btn btn-xs btn-secondary" onclick="addRequirement()"><?=$AppUI->_("LBL_ACQUISION_ADD_REQUIREMENT")?></button>
        <br>
        <br>
        <?php 
            foreach($requirements as $req) {
                ?>
                <div class="row">
                    <div class="col-md-11">
                        <input type="text" class="form-control form-control-sm" name="requirement[]" value="<?=$req['requirement']?>">
                        <br>
                    </div>
                    <div class="col-md-1">
                        <button type="button" class="btn btn-xs btn-danger" onclick="deleteRequirement(this, <?=$req['id']?>)">
                            <i class="far fa-trash-alt"></i>
                        </button>
                    </div>
                </div>
                <?php
            }
        ?>
    </div>

    <div class="form-group">
        <label for="supplier_management_process">
            <?=$AppUI->_("LBL_SUPPLIERS_PROCESSES_MANAGEMENT")?>
        </label>
        <textarea class="form-control form-control-sm" name="supplier_management_process"><?php echo $object->getSupplierManagementProcess() ?></textarea>
    </div>

    <div class="form-group" id="rolesContainer">
        <label for="LBL_ACQUISITION_ROLES_RESPONSABILITIES">
            <?=$AppUI->_("LBL_ACQUISITION_ROLES_RESPONSABILITIES")?>
        </label>
        <br>
        <button type="button" class="btn btn-xs btn-secondary" onclick="addRole()"><?=$AppUI->_("LBL_ACQUISION_ADD_ROLE")?></button>
        <br>
        <br>
        <?php 
            foreach($roles as $role) {
                ?>
                <div class="row">
                    <div class="col-md-5">
                        <input type="text" class="form-control form-control-sm" name="role[]" value="<?=$role['role']?>">
                        <br>
                    </div>
                    <div class="col-md-6">
                        <input type="text" class="form-control form-control-sm" name="responsability[]" value="<?=$role['responsability']?>">
                        <br>
                    </div>
                    <div class="col-md-1">
                        <button type="button" class="btn btn-xs btn-danger" onclick="deleteRole(this, <?=$role['id']?>)">
                            <i class="far fa-trash-alt"></i>
                        </button>
                    </div>
                </div>
                <?php
            }
        ?>
    </div>
</form>

<script>
    $(document).ready(function() {

        $(".select-contract-type").select2({
            placeholder: "",
            theme: "bootstrap",
            dropdownParent: $(".contract-type")
        });

        $(".select-criteria").select2({
            placeholder: "",
            theme: "bootstrap",
            dropdownParent: $(".criteria")
        });
    });

    function addCriteria(criteriaId, name, weight) {
        var container = $('#criteriaContainer');
        var row = $('<div class="row"></div>');
        var col1 = $('<div class="col-md-9"></div>');
        var col2 = $('<div class="col-md-2"></div>');
        var col3 = $('<div class="col-md-1"></div>');

        var input = $('<input type="text" class="form-control form-control-sm" name="criteria[]" /><br>');
        var btn = $('<button type="button" class="btn btn-xs btn-danger" onclick="deleteCriteria(this, '+criteriaId+')"><i class="far fa-trash-alt"></i></button>')

        var select = $('<select class="form-control form-control-sm select" name="criteria_weight[]"></select>');

        for(i=1;i<=10;i++){
            var option = $('<option value="'+i+'">'+i+'</option>');
            if(i==weight){
                option.attr('selected', 'selected');
            }
            option.appendTo(select);
        }

        input.val(name);
        input.appendTo(col1);
        btn.appendTo(col3);
        select.appendTo(col2);

        select.select2({
            placeholder: "",
            theme: "bootstrap",
            dropdownParent: $("#criteriaContainer")
        });

        col1.appendTo(row);
        col2.appendTo(row);
        col3.appendTo(row);
        container.append(row);
    }

    function deleteCriteria(element, id) {
        $(element).parent().parent().remove();
    }

    function addRequirement(reqId, req) {
        var container = $('#requirementContainer');
        var row = $('<div class="row"></div>');
        var col1 = $('<div class="col-md-11"></div>');
        var col2 = $('<div class="col-md-1"></div>');

        var input = $('<input type="text" class="form-control form-control-sm" name="requirement[]" /><br>');
        var btn = $('<button type="button" class="btn btn-xs btn-danger" onclick="deleteRequirement(this, '+reqId+')"><i class="far fa-trash-alt"></i></button>')

        input.val(req);
        input.appendTo(col1);
        btn.appendTo(col2);
        col1.appendTo(row);
        col2.appendTo(row);
        container.append(row);
    }

    function deleteRequirement(element, id) {
        $(element).parent().parent().remove();
    }

    function addRole(id, role, responsability) {
        var container = $('#rolesContainer');
        var row = $('<div class="row"></div>');
        var col1 = $('<div class="col-md-5"></div>');
        var col2 = $('<div class="col-md-6"></div>');
        var col3 = $('<div class="col-md-1"></div>');

        var input1 = $('<input type="text" class="form-control form-control-sm" name="role[]" /><br>');
        var input2 = $('<input type="text" class="form-control form-control-sm" name="responsability[]" /><br>');
        var btn = $('<button type="button" class="btn btn-xs btn-danger" onclick="deleteRole(this, '+id+')"><i class="far fa-trash-alt"></i></button>')

        input1.val(role);
        input2.val(responsability);
        input1.appendTo(col1);
        input2.appendTo(col2);
        btn.appendTo(col3);
        col1.appendTo(row);
        col2.appendTo(row);
        col3.appendTo(row);
        container.append(row);
    }

    function deleteRole(element, id) {
        $(element).parent().parent().remove();
    }
</script>


<?php
    exit();
?>