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

<div class="alert alert-secondary" role="alert">
    <h4 class="alert-heading"><?=$class->course?></h4>
    <p><?=$class->disciplin .' - '.$class->year . '/' . $class->semester?></p>
</div>
<div class="card inner-card">
    <div class="card-header"><?=$AppUI->_("LBL_ADD_NEW_GROUPS_FOR_THIS_CLASS")?></div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-12">
                <form name="formNumberOfGroups">
                    <input type="hidden" name="dosql" value="do_groups_creation" />
                    <input type="hidden" name="class_id" value="<?=$class_id?>" />

                    <div class="form-group">
                        <span class="required"></span>
                        <?=$AppUI->_('requiredField');?>
                    </div>

                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="number_of_groups" class="required">
                                    <?=$AppUI->_('LBL_NUMBER_OF_GROUPS')?>
                                </label>
                                <input type="number" class="form-control form-control-sm" name="number_of_groups" />
                            </div>
                        </div>
                        <div class="col-md-9">
                            <div class="form-group">
                                <label for="button" class="d-block">
                                    &nbsp;
                                </label>
                                <button type="button" class="btn btn-primary btn-sm" onclick="course.saveGroups(<?=$class_id?>)"><?=$AppUI->_("LBL_CREATE_GROUPS_ACCOUNTS")?></button>
                            </div>
                        </div>
                    </div>

                    <small><?php echo$AppUI->_("LBL_MAXIMUM_NUMBER_OF_GROUPS") ?></small>
                </form>
            </div>
        </div>
    </div>
</div>

<br>

<div class="card inner-card">
    <div class="card-header"><?=$AppUI->_("LBL_USERS_FOR_THIS_CLASS")?></div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-12" id="goupsTableContainer">
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        var element = $('#goupsTableContainer');
        element.loading({
            message: 'Carregando grupos...'
        });
        element.load(
            "?m=instructor_admin&template=groups_table&class_id=<?=$class_id?>",
            function () {
                element.loading('stop');
            }
        );
    });
</script>

<?php
    exit();

