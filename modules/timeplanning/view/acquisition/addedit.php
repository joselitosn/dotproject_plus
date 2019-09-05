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
?>
<form name="acquisitionForm">
    <input name="dosql" type="hidden" value="do_project_acquisition_planning" />
    <input name="project_id" type="hidden" value="<?$projectId?>" />
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
        <div class="col-md-4">
            <div class="form-group">
                <label for="contract_type">
                    <?=$AppUI->_("LBL_CONTRACT_TYPE")?>
                </label>
                <?php
                $types = array();
                $types[0] = $AppUI->_("LBL_ACQUISITION_CONTRACT_TYPE_FIXED_PRICE");
                $types[1] = $AppUI->_("LBL_ACQUISITION_CONTRACT_TYPE_TIME_MATERIAL");
                $types[2] = $AppUI->_("LBL_ACQUISITION_CONTRACT_TYPE_COST_REPAID") ;
                ?>

                <select name="contract_type" class="form-control form-control-sm select">
                    <?php
                        for ($i = 0; $i < sizeof($types); $i++) {
                            ?>
                            <option value="<?php echo $types[$i] ?>" <?php echo $types[$i] == $object->getContractType() ? "selected" : ""; ?> >
                                <?php echo $types[$i] ?>
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

    <div class="form-group" id="criteriaContainer">
        <label for="LBL_CRITERIA_TO_SUPPLIERS_SELECTION">
            <?=$AppUI->_("LBL_CRITERIA_TO_SUPPLIERS_SELECTION")?>
        </label>
        <br>
        <button type="button" class="btn btn-xs btn-secondary" onclick="addCriteria()"><?=$AppUI->_("LBL_ACQUISION_ADD_CRITERIA")?></button>
        <br>
        <br>
    </div>

    <div class="form-group" id="requirementContainer">
        <label for="LBL_ACQUISITION_ADDITIONAL_REQUIRIMENTS">
            <?=$AppUI->_("LBL_ACQUISITION_ADDITIONAL_REQUIRIMENTS")?>
        </label>
        <br>
        <button type="button" class="btn btn-xs btn-secondary" onclick="addRequirement()"><?=$AppUI->_("LBL_ACQUISION_ADD_REQUIREMENT")?></button>
        <br>
        <br>
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
    </div>
</form>

<script>
    $(document).ready(function() {
        $(".select").select2({
            allowClear: true,
            placeholder: "",
            theme: "bootstrap",
            dropdownParent: $("#acquisitionModal")
        });
    });

    function addCriteria(criteriaId, name, weight) {
        var container = $('#criteriaContainer');
        var row = $('<div class="row"></div>');
        var col1 = $('<div class="col-md-9"></div>');
        var col2 = $('<div class="col-md-2"></div>');
        var col3 = $('<div class="col-md-1"></div>');

        var input = $('<input type="text" class="form-control form-control-sm" name="criteria" /><br>');
        var btn = $('<button type="button" class="btn btn-xs btn-danger" onclick="deleteCriteria(this, '+criteriaId+')"><i class="far fa-trash-alt"></i></button>')

        var select = $('<select class="form-control form-control-sm select" name="criteria_weight"></select>');

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
        // TODO ajax to delete, then 
        $(element).parent().parent().remove();
    }

    function addRequirement(reqId, req) {
        var container = $('#requirementContainer');
        var row = $('<div class="row"></div>');
        var col1 = $('<div class="col-md-11"></div>');
        var col2 = $('<div class="col-md-1"></div>');

        var input = $('<input type="text" class="form-control form-control-sm" name="requirement" /><br>');
        var btn = $('<button type="button" class="btn btn-xs btn-danger" onclick="deleteRequirement(this, '+reqId+')"><i class="far fa-trash-alt"></i></button>')

        input.val(req);
        input.appendTo(col1);
        btn.appendTo(col2);
        col1.appendTo(row);
        col2.appendTo(row);
        container.append(row);
    }

    function deleteRequirement(element, id) {
        // TODO ajax to delete, then 
        $(element).parent().parent().remove();
    }

    function addRole(id, role, responsability) {
        var container = $('#rolesContainer');
        var row = $('<div class="row"></div>');
        var col1 = $('<div class="col-md-5"></div>');
        var col2 = $('<div class="col-md-6"></div>');
        var col3 = $('<div class="col-md-1"></div>');

        var input1 = $('<input type="text" class="form-control form-control-sm" name="requirement" /><br>');
        var input2 = $('<input type="text" class="form-control form-control-sm" name="requirement" /><br>');
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
        // TODO ajax to delete, then 
        $(element).parent().parent().remove();
    }
</script>


<?php
    exit();
?>