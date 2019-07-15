<?php
    require_once (DP_BASE_DIR . "/modules/timeplanning/control/controller_project_minute.class.php");
    $controllerProjectMinute = new ControllerProjectMinute();
    $project_id = $_GET['project_id'];

    $all_users = $controllerProjectMinute->getAllProjectStakeholders($project_id);
    //update allocated users list with his names
    if (!is_array($members)) {
        $members = array();
    }
    foreach ($members as $member) {
        $key = $member . ""; //make key as string
        $members[$key] = $all_users[$key];
    }
    //clear any empty option
    for ($i = 0; $i < sizeof($members); $i++) {
        if ($members[$i] == "") {
            unset($members[$i]);
        }
    }
    //remove from all users array the users already allocated
    $all_users = array_diff($all_users, $members);

?>
<!-- TinyMCE -->
<script type="text/javascript" src="./style/<?php echo $uistyle; ?>/tinymce/tinymce.min.js"></script>
<script type="text/javascript">

    tinymce.init({
        selector: '#description_edit',
        branding: false,
        height: 350,
        setup: function (editor) {
            editor.on('change', function () {
                editor.save();
            });
        }
    });
</script>
<!-- /TinyMCE -->

<form name="minute_form">
    <input name="dosql" type="hidden" value="do_projects_estimations_aed" />
    <input type="hidden" name="minute_id" id="minute_id" value="<?=$minuteId?>" />
    <input type="hidden" name="project_id" value="<?=$project_id?>" />
    <input type="hidden" name="membersIds" id="membersIds" />
    <input type="hidden" name="action_estimation" id="action_estimation" value="" />

    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <labe for="type"><?=$AppUI->_('LBL_TYPE')?></labe><br>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" name="isEffort" value="1" <?=$isEffort == "" ? "" : "checked"?>>
                    <label class="form-check-label" for="isEffort"><?=$AppUI->_('LBL_EFFORT')?></label>
                </div>

                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" name="isDuration" value="1" <?=$isDuration == "" ? "" : "checked"?>>
                    <label class="form-check-label" for="isDuration"><?=$AppUI->_('LBL_DURATION')?></label>
                </div>

                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" name="isResource" value="1" <?=$isResource == "" ? "" : "checked"?>>
                    <label class="form-check-label" for="isResource"><?=$AppUI->_('LBL_RESOURCES')?></label>
                </div>

                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" name="isSize" value="1" <?=$isSize == "" ? "" : "checked"?>>
                    <label class="form-check-label" for="isSize"><?=$AppUI->_('LBL_SIZE')?></label>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <labe for="type"><?=$AppUI->_('LBL_DATE')?></labe>
                <input type="hidden" name="date" id="date">
                <input type="text" class="form-control form-control-sm" name="date_edit" value="<?=$date?>" id="date_edit" onchange="minutesForm.dateSelected()">
            </div>
        </div>
    </div>

    <div class="form-group">
        <labe for="type"><?=$AppUI->_('LBL_PARTICIPANTS')?></labe>
        <select class="form-control form-control-sm" multiple name="selected_members" id="selectMembers">
            <?php
                foreach ($all_users as $key => $value) {
                ?>
                    <option value="<?=$key?>"><?=$value?></option>
                <?php
                }
            ?>
        </select>
    </div>

    <div class="form-group">
        <labe for="type"><?=$AppUI->_('LBL_REPORT')?></labe>
        <textarea id="description_edit" name="description_edit" rows="7" class="form-control form-control-sm"><?=$description?></textarea>
    </div>
</form>

<script>
    var minutesForm = {

        init: function () {
            $("#selectMembers").select2({
                placeholder: "",
                allowClear: true,
                theme: "bootstrap",
                dropdownParent: $("#minutesModal")
            });

            $( "#date_edit" ).datepicker({
                dateFormat: 'dd/mm/yy',
                onSelect: minutesForm.dateSelected
            });
        },

        dateSelected: function () {
            var source = $('#date_edit');
            var target = $('#date');
            var date = source.val();
            if (!date) {
                return;
            }
            var arrDate = date.split('/');
            target.val(arrDate[2] + '-' + arrDate[1] + '-' + arrDate[0]);
        }
    }

    $(document).ready(minutesForm.init);
</script>
<?php
    exit();
?>