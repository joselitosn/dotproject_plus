<?php
if (!defined('DP_BASE_DIR')) {
    die('You should not access this file directly.');
}
require_once (DP_BASE_DIR . "/modules/instructor_admin/class.class.php");
?>
<script>

//    function rightClickMenuViewClass() {
//        if (contextObject.parentNode.id.indexOf("class_id_") != -1 || contextObject.parentNode.parentNode.id.indexOf("class_id_") != -1) {
//            var parentId = contextObject.parentNode.id.indexOf("class_id_") != -1 ? contextObject.parentNode.id : contextObject.parentNode.parentNode.id;
//            var classId = parentId.split("class_id_")[1];
//            window.location = "?m=instructor_admin&a=addedit&class_id=" + classId;
//
//        }
//    }
//
//    function rightClickMenuDeleteClass() {
//        if (contextObject.parentNode.id.indexOf("class_id_") != -1 || contextObject.parentNode.parentNode.id.indexOf("class_id_") != -1) {
//            alertify.confirm("<?php //echo $AppUI->_("LBL_DELETE_MSG") ?>//", function () {
//
//                var parentId = contextObject.parentNode.id.indexOf("class_id_") != -1 ? contextObject.parentNode.id : contextObject.parentNode.parentNode.id;
//                var classId = parentId.split("class_id_")[1];
//                document.delete_class.class_id.value = classId;
//                document.delete_class.submit();
//            });
//        }
//    }
//
//    function rightClickMenuPrintCredentials() {
//        if (contextObject.parentNode.id.indexOf("class_id_") != -1 || contextObject.parentNode.parentNode.id.indexOf("class_id_") != -1) {
//            var parentId = contextObject.parentNode.id.indexOf("class_id_") != -1 ? contextObject.parentNode.id : contextObject.parentNode.parentNode.id;
//            var classId = parentId.split("class_id_")[1];
//            window.location = "?m=instructor_admin&a=print_credentials&class_id=" + classId;
//        }
//    }
//
//    with (milonic = new menuname("contextMenu")) {
//        margin = 9;
//        style = contextStyle;
//        top = "offset=5";
//        aI("image=./images/icons/stock_edit-16.png;text=<?php //echo $AppUI->_("LBL_VIEW") ?>//;url=javascript:rightClickMenuViewClass();");
//        aI("image=./images/icons/stock_print-16.png;text=<?php //echo $AppUI->_("LBL_PRINT_CREDENTIALS"); ?>//;url=javascript:rightClickMenuPrintCredentials();");
//        aI("image=./modules/dotproject_plus/images/trash_small.gif;text=<?php //echo $AppUI->_("LBL_DELETE_CLASS"); ?>//;url=javascript:rightClickMenuDeleteClass();");
//    }
//
//    drawMenus();
</script>

    <div id="content">
        <fieldset>
            <legend><?=$AppUI->_('Instructor Admin')?></legend>
            <div class="row">
                <div class="col-md-12 text-right">
                    <a class="btn btn-sm btn-secondary" href="javascript:void(0)" onclick="course.add()">
                        <?=$AppUI->_('LBL_ADD_NEW_CLASS')?>
                    </a>
                    <a class="btn btn-sm btn-secondary" href="javascript:void(0)" onclick="course.viewFeedback()">
                        <?=$AppUI->_('LBL_VIEW_FEEDBACK_EVALUATIONS')?>
                    </a>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                <?php
                    $classes = CClass::getAllClasses();
                    foreach ($classes as $class) {
                        ?>
                        <div class="card inner-card" id="card_<?=$class->class_id?>">
                            <div class="card-body shrink">
                                <div class="row">
                                    <div class="col-md-10">
                                        <h5 class="courset-card">
                                            <a id="<?=$class->class_id?>" data-toggle="collapse"
                                               href="#course_details_<?=$class->class_id?>">
                                                <?=$class->course?>
                                                <i class="fas fa-caret-down"></i>
                                            </a>
                                        </h5>
                                    </div>
                                    <div class="col-md-2 text-right">
                                        <span class="badge badge-primary">
                                            <?=$class->year . '/' . $class->semester?>
                                        </span>

                                        <div class="dropdown" style="width: 20%; float: right;">
                                            <a href="javascript:void(0)" class="link-primary" role="button"
                                               data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="fas fa-bars"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-right"
                                                 aria-labelledby="dropdownMenuLink">
                                                <a class="dropdown-item" href="javascript:void(0)"
                                                   onclick="course.edit(<?=$class->class_id?>)">
                                                    <i class="far fa-edit"></i>
                                                    <?=$AppUI->_('LBL_UPDATE_CLASS')?>
                                                </a>
                                                <a class="dropdown-item" href="javascript:void(0)"
                                                   onclick="course.printCredentials(<?=$class->class_id?>)">
                                                    <i class="fas fa-print"></i>
                                                    <?=$AppUI->_('LBL_PRINT_CREDENTIALS')?>
                                                </a>
                                                <a class="dropdown-item" href="javascript:void(0)"
                                                   onclick="course.delete(<?=$class->class_id?>)">
                                                    <i class="far fa-trash-alt"></i>
                                                    <?=$AppUI->_('LBL_DELETE_CLASS')?>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div id="course_details_<?=$class->class_id?>" class="collapse">
                                    <div class="row">
                                        <table class="table table-sm no-border">
                                            <tr>
                                                <th class="text-right" width="15%"><?= $AppUI->_('LBL_EDUCATIONAL_INSTITUTION') ?>:</th>
                                                <td width="35%"><?=$class->educational_institution?></td>
                                                <th class="text-right" width="15%"><?= $AppUI->_('LBL_INSTRUCTOR') ?>:</th>
                                                <td width="35%"><?=$class->instructor?></td>
                                            </tr>

                                            <tr>
                                                <th class="text-right" width="15%"><?= $AppUI->_('LBL_DISCIPLIN') ?>:</th>
                                                <td width="35%"><?=$class->disciplin?></td>
                                                <th class="text-right" width="15%"><?= $AppUI->_('LBL_NUMBER_OF_STUDENTS') ?>:</th>
                                                <td width="35%"><?=$class->number_of_students?></td>
                                            </tr>
                                        </table>
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

    <script>
        var course = {

            init: function() {

                $('a[data-toggle=collapse]').on('click', course.show);
            },

            show: function (e) {
                const collapseRole = $(e.target);
                $('#course_details_'+e.target.id).on('show.bs.collapse', function () {
                    collapseRole.find('i').removeClass('fa-caret-down');
                    collapseRole.find('i').addClass('fa-caret-up');
                });
                $('#course_details_'+e.target.id).on('hide.bs.collapse', function () {
                    collapseRole.find('i').removeClass('fa-caret-up');
                    collapseRole.find('i').addClass('fa-caret-down');
                });
            },

            add: function () {
                console.log('add course');
            },

            edit: function () {
                console.log('edit course');
            },

            delete: function (id) {
                $.confirm({
                    title: '',
                    content: "<?=$AppUI->_('LBL_DELETE_MSG')?>",
                    buttons: {
                        sim: function () {
                            $.ajax({
                                url: "?m=instructor_admin",
                                type: "post",
                                datatype: "json",
                                data: {
                                    dosql: 'do_delete_class',
                                    class_id: id
                                },
                                success: function(resposta) {
                                    var resp = JSON.parse(resposta);
                                    var icon = 'far fa-check-circle';
                                    var type = 'green';
                                    var title = "<?= $AppUI->_('Success', UI_OUTPUT_JS); ?>";
                                    var msg = resp.msg;
                                    if (resp.err) {
                                        icon = 'far fa-times-circle';
                                        type = 'red';
                                        title = "<?= $AppUI->_('Error', UI_OUTPUT_JS); ?>";
                                        msg = "<?=$AppUI->_('Something went wrong.', UI_OUTPUT_JS); ?>"
                                    }
                                    $.alert({
                                        icon: icon,
                                        type: type,
                                        title: title,
                                        content: msg,
                                        onClose: function () {
                                            if (!resp.err) {
                                                $("#card_" + id).remove();
                                            }
                                        }
                                    });
                                },
                                error: function(resposta) {
                                    $.alert({
                                        icon: "far fa-times-circle",
                                        type: "red",
                                        title: "<?=$AppUI->_('Error', UI_OUTPUT_JS); ?>",
                                        content: "<?=$AppUI->_('Something went wrong.', UI_OUTPUT_JS); ?>"
                                    });
                                }
                            });
                        },
                        no: {
                            text: 'Não'
                        }
                    }
                });
            },
        };
        $(document).ready(course.init);
    </script>


<form action="?m=instructor_admin&a=addedit" method="post">
    <div style="float:left">
        <input type="submit" value="<?php echo $AppUI->_("LBL_ADD_NEW_CLASS"); ?>" />
    </div>
    <div style="float:right">
        <a href="index.php?m=instructor_admin&a=view_student_feedback_evaluation">
            <input type="button" value="<?php echo $AppUI->_("LBL_VIEW_FEEDBACK_EVALUATIONS"); ?>" style="cursor:pointer " />
        </a>
    </div>
</form>