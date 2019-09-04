<?php
if (!defined('DP_BASE_DIR')) {
    die('You should not access this file directly.');
}

include "functions.php";
require_once DP_BASE_DIR . '/modules/communication/communication.class.php';
$project_id = $_GET['project_id'];
$communication_id = $_GET['communication_id'];
$obj = new CCommunication();
$receptors = array();
$issuers = array();
if (null !== $communication_id) {
    $obj->load($communication_id);
    $q = new DBQuery();
    $q->addQuery('communication_stakeholder_id');
    $q->addTable('communication_receptor');
    $q->addWhere('communication_id=' . $communication_id);
    $list = $q->loadList();
    $q->clear();
    foreach($list as $item) {
        $receptors[] = $item['communication_stakeholder_id'];
    }
    
    $q = new DBQuery();
    $q->addQuery('communication_stakeholder_id');
    $q->addTable('communication_issuing');
    $q->addWhere('communication_id=' . $communication_id);
    $list = $q->loadList();
    $q->clear();
    foreach($list as $item) {
        $issuers[] = $item['communication_stakeholder_id'];
    }
}
?>

<div class="alert alert-secondary" role="alert">
    <?php echo $AppUI->_("LBL_EMISSOR_RECIPTORS"); ?>
</div>
<div class="alert alert-secondary" role="alert">
    <?php echo $AppUI->_("LBL_EMISSOR_RECIPTORS_AVAILABILITY"); ?>
</div>

<form name="communicationForm" id="communicationForm">
    <input type="hidden" name="dosql" value="do_communication_aed" />
    <input type="hidden" name="del" value="0" />      
    <input type="hidden" name="project" value="<?=$project_id?>" />  
    <input type="hidden" name="project_id" value="<?=$project_id?>" />  
    <input type="hidden" name="communication_id" value="<?=$communication_id?>" />

    <div class="form-group">
        <span class="required"></span>
        <?=$AppUI->_('requiredField');?>
    </div>

    <div class="form-group">
        <label for="communication_title" class="required">
            <?=$AppUI->_('LBL_TITLE')?>
        </label>
        <input type="text" maxlength="45" class="form-control form-control-sm" name="communication_title" value="<?=$obj->communication_title?>" />
    </div>

    <div class="form-group">
        <label for="communication_information" class="required">
            <?=$AppUI->_('LBL_COMMUNICATION')?>
        </label>
        <textarea name="communication_information" rows="2" class="form-control form-control-sm"><?=$obj->communication_information?></textarea>
    </div>

    <div  style="display: <?=$communication_id > 0 ? 'block' :'none'?>">
        <div class="row" id="selectIssuerReceptor">
            <div class="col-md-6">
                <div class="form-group" >
                    <label for="issuing">
                        <?=$AppUI->_('LBL_ISSUING')?>
                    </label>
                    <select class="form-control form-control-sm select-issuer-receptor" name="issuing[]" multiple="multiple">
                        <?php
                            foreach ($rlista as $registro) {
                                $selected = in_array($registro['contact_id'], $issuers) ? ' selected' : '';
                        ?>
                                <option value="<?=$registro['contact_id']?>" <?=$selected?>>
                                    <?=$registro['contact_first_name'] . ' ' . $registro['contact_last_name']?>
                                </option>
                        <?php
                            }
                        ?>
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="receptor">
                        <?=$AppUI->_('LBL_RECEPTOR')?>
                    </label>
                    <select class="form-control form-control-sm select-issuer-receptor" name="receptor[]" multiple="multiple">
                        <?php
                            foreach ($rlista as $registro) {
                                $selected = in_array($registro['contact_id'], $receptors) ? ' selected' : '';
                        ?>
                                <option value="<?=$registro['contact_id']?>" <?=$selected?>>
                                    <?=$registro['contact_first_name'] . ' ' . $registro['contact_last_name']?>
                                </option>
                        <?php
                            }
                        ?>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div class="row" id="selectChannelFrequency">
        <div class="col-md-6">
            <div class="form-group">
                <label for="channel">
                    <?=$AppUI->_('LBL_CHANNEL')?>
                </label>
                <select class="form-control form-control-sm select-channel-frequency" name="channel">
                    <option value=""></option>
                    <?php
                        foreach ($channels as $registro) {
                            $value = $obj->communication_channel_id;
                            $selected = $registro['communication_channel_id'] == $value ? ' selected="selected"' : '';
                    ?>
                            <option value="<?=$registro['communication_channel_id']?>" <?=$selected?>>
                                <?=$registro['communication_channel']?>
                            </option>
                    <?php
                        }
                    ?>
                </select>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="frequency">
                    Frequência
                </label>
                <select class="form-control form-control-sm select-channel-frequency" name="frequency" id="communicationFrequency">
                    <option value=""></option>
                    <?php
                        foreach ($frequency as $registro) {
                            $value = $obj->communication_frequency_id;
                            $selected = $registro['communication_frequency_id'] == $value ? ' selected="selected"' : '';
                    ?>
                            <option value="<?=$registro['communication_frequency_id']?>" <?=$selected?> data="<?=$registro['communication_frequency_hasdate']?>">
                                <?=$registro['communication_frequency']?>
                            </option>
                    <?php
                        }
                    ?>
                </select>
            </div>
        </div>
    </div>
    <div class="row" id="dateContainer" style="display:none">
        <div class="col-md-6">
            <div class="form-group">
                <label for="date">
                    Data
                </label>
                <input type="text" maxlength="10" class="form-control form-control-sm datepicker" name="communication_date" value="<?=$obj->communication_date?>" />
            </div>
        </div>
    </div>
    <div class="form-group">
        <label for="communication_restrictions">
            <?=$AppUI->_('LBL_RESTRICTIONS')?>
        </label>
        <textarea name="communication_restrictions" rows="2" class="form-control form-control-sm"><?=$obj->communication_restrictions?></textarea>
    </div>

    <div class="form-group" id="selectResponsible">
        <label for="responsible">
            <?=$AppUI->_('LBL_RESPONSIBLE')?>
        </label>
        <select class="form-control form-control-sm select-responsible" name="responsible">
            <option value=""></option>
            <?php
                foreach ($rlista as $registro) {
                    $value = $obj->communication_responsible_authorization;
                    $selected = $registro['contact_id'] == $value ? ' selected="selected"' : '';
            ?>
                    <option value="<?=$registro['contact_id']?>" <?=$selected?>>
                        <?=$$registro['contact_first_name'] . ' ' . $registro['contact_last_name']?>
                    </option>
            <?php
                }
            ?>
        </select>
    </div>

</form>

<script>
    $(document).ready(function(){
        $(".select-issuer-receptor").select2({
            placeholder: "",
            allowClear: true,
            theme: "bootstrap",
            dropdownParent: $("#selectIssuerReceptor")
        });
        $(".select-channel-frequency").select2({
            placeholder: "",
            allowClear: true,
            theme: "bootstrap",
            dropdownParent: $("#selectChannelFrequency")
        });
        $(".select-responsible").select2({
            placeholder: "",
            allowClear: true,
            theme: "bootstrap",
            dropdownParent: $("#selectResponsible")
        });

        $( ".datepicker" ).datepicker({
            dateFormat: 'dd/mm/yy'
        });

        $('#communicationFrequency').on('change', function(){
            checkFrequencyHasDate(this);
        });

        function checkFrequencyHasDate(select) {
            var hasDate = $(select.selectedOptions[0]).attr('data');
            if (hasDate == 'Sim') {
                $('#dateContainer').show();
            } else {
                $('#dateContainer').hide();
                $('input[name=communication_date]').val('');
            }
        }

        checkFrequencyHasDate($('#communicationFrequency')[0]);

    });
</script>
<?php
    exit();
?>