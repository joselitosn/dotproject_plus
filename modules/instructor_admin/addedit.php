<?php
if (!defined('DP_BASE_DIR')) {
    die('You should not access this file directly.');
}
require_once (DP_BASE_DIR . "/modules/instructor_admin/class.class.php");
$class_id = "";
$class = new CClass();
if (isset($_GET["class_id"])) {
    $class_id = $_GET["class_id"];
    $class->load($class_id);
}
?>
<form name="formNewClass">
    <input type="hidden" name="dosql" value="do_register_class" />
    <input type="hidden" name="class_id" value="<?=$class->class_id?>" />

    <div class="form-group">
        <span class="required"></span>
        <?=$AppUI->_('requiredField');?>
    </div>

    <div class="form-group">
        <label for="educational_institution" class="required">
            <?=$AppUI->_('LBL_EDUCATIONAL_INSTITUTION')?>
        </label>
        <input type="text" class="form-control form-control-sm" name="educational_institution" value="<?=$class->educational_institution?>" />
    </div>

    <div class="form-group">
        <label for="course" class="required">
            <?=$AppUI->_('LBL_COURSE')?>
        </label>
        <input type="text" class="form-control form-control-sm" name="course" value="<?=$class->course?>" />
    </div>

    <div class="form-group">
        <label for="disciplin" class="required">
            <?=$AppUI->_('LBL_DISCIPLIN')?>
        </label>
        <input type="text" class="form-control form-control-sm" name="disciplin" value="<?=$class->disciplin?>" />
    </div>

    <div class="form-group">
        <label for="instructor" class="required">
            <?=$AppUI->_('LBL_INSTRUCTOR')?>
        </label>
        <input type="text" class="form-control form-control-sm" name="instructor" value="<?=$class->instructor?>" />
    </div>

    <div class="row row-period">
        <div class="col-md-5">
            <div class="form-group">
                <label for="number_of_students">
                    <?=$AppUI->_('LBL_NUMBER_OF_STUDENTS')?>
                </label>
                <input type="text" class="form-control form-control-sm" name="number_of_students" value="<?=$class->number_of_students?>" />
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="year" class="required">
                    <?=$AppUI->_('LBL_YEAR')?>
                </label>
                <select name="year" class="form-control form-control-sm select-2">
                    <?php
                    $year = date("Y");
                    for ($i = 2012; $i <= $year + 1; $i++) {
                        ?>
                        <option value="<?=$i?>" <?=$class->year == $i ? "selected" : ""?>  <?=($year == $i && $class->year == 0) ? "selected" : ""?>>
                            <?=$i?>
                        </option>
                        <?php
                    }
                    ?>
                </select>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label for="semester" class="required">
                    <?=$AppUI->_('LBL_SEMESTER')?>
                </label>
                <select name="semester" class="form-control form-control-sm select-2">
                    <option value="1" default <?=$class->semester == 1 ? "selected" : ""?>>1</option>
                    <option value="2" default <?=$class->semester == 2 ? "selected" : ""?>>2</option>
                </select>
            </div>
        </div>
    </div>
</form>
<script>

    $(document).ready(function() {
        $(".select-2").select2({
            allowClear: false,
            theme: "bootstrap",
            dropdownParent: $(".row-period")
        });
    });

</script>

<?php
    exit();
?>
