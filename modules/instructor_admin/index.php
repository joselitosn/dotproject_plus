<?php
    if (!defined('DP_BASE_DIR')) {
        die('You should not access this file directly.');
    }
    require_once (DP_BASE_DIR . "/modules/instructor_admin/class.class.php");
?>

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
                                               onclick="course.viewClassGroups(<?=$class->class_id?>)">
                                                <i class="fas fa-users-cog"></i>
                                                <?=$AppUI->_('LBL_CLASS_DATA')?>
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
<div class="modal" tabindex="-1" role="dialog" id="modalNewClass">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?=$AppUI->_('LBL_ADD_NEW_CLASS')?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal"><?=$AppUI->_('LBL_CLOSE')?></button>
                <button type="button" class="btn btn-primary btn-sm" onclick="course.save()"><?=$AppUI->_("LBL_SAVE")?></button>
            </div>
        </div>
    </div>
</div>

<div class="modal" tabindex="-1" role="dialog" id="modalEvaluationFeedback">
    <div class="modal-dialog modal-xxl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?=$AppUI->_('LBL_FEEDBACK_EVALUATION_REPORT')?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal"><?=$AppUI->_('LBL_CLOSE')?></button>
            </div>
        </div>
    </div>
</div>

<div class="modal" tabindex="-1" role="dialog" id="modalClassGroups">
    <div class="modal-dialog modal-xxl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?=$AppUI->_('LBL_CLASS_DATA')?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="groupDetails"></div>
                <div class="groupFeedback">feedback</div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-light btn-sm btnBackToDetails" onclick="course.backToGroupDetails()">Voltar</button>
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal"><?=$AppUI->_('LBL_CLOSE')?></button>
            </div>
        </div>
    </div>
</div>

<script>
    var course = {

        init: function() {

            $('a[data-toggle=collapse]').on('click', course.show);

            $('#modalClassGroups').on('hidden.bs.modal', function() {
                $(this).find('h5').html('<?=$AppUI->_("LBL_CLASS_DATA")?>')
                $('.groupDetails').show().html('');
                $('.groupFeedback').hide().html('');
                $('.btnBackToDetails').hide();
            });
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
            $.ajax({
                type: "get",
                url: "?m=instructor_admin&template=addedit"
            }).done(function(response) {
                var modal = $('#modalNewClass');
                modal.find('h5').html('<?=$AppUI->_('LBL_ADD_NEW_CLASS')?>');
                modal.find('.modal-body').html(response);
                modal.modal();
            });
        },

        edit: function (id) {
            $.ajax({
                type: "get",
                url: "?m=instructor_admin&template=addedit&class_id="+id
            }).done(function(response) {
                var modal = $('#modalNewClass');
                modal.find('h5').html('<?=$AppUI->_('LBL_EDIT_CLASS')?>');
                modal.find('.modal-body').html(response);
                modal.modal();
            });
        },

        save: function () {

            var institution = $('input[name=educational_institution]').val();
            var course = $('input[name=course]').val();
            var instructor = $('input[name=instructor]').val();
            var disciplin = $('input[name=disciplin]').val();
            var year = $('select[name=year]').val();
            var semester = $('select[name=semester]').val();

            var msg = [];
            var err = false;
            if (!institution.trim()) {
                err = true;
                msg.push('Favor informar a instituição de ensino');
            }
            if (!course.trim()) {
                err = true;
                msg.push('Favor informar o curso');
            }
            if (!instructor.trim()) {
                err = true;
                msg.push('Favor informar o instrutor');
            }
            if (!disciplin.trim()) {
                err = true;
                msg.push('Favor informar a disciplina');
            }
            if (!year) {
                err = true;
                msg.push('Favor informar o ano');
            }
            if (!semester) {
                err = true;
                msg.push('Favor informar o semestre');
            }

            if (err) {
                $.alert({
                    icon: "far fa-times-circle",
                    type: "red",
                    title: "Erro",
                    content: msg.join('<br>')
                });
                return;
            }

            $.ajax({
                url: "?m=instructor_admin",
                type: "post",
                datatype: "json",
                data: $("form[name=formNewClass]").serialize(),
                success: function(resposta) {
                    $.alert({
                        icon: "far fa-check-circle",
                        type: "green",
                        title: "Sucesso",
                        content: resposta,
                        onClose: function() {
                            window.location.reload(true);
                        }
                    });
                },
                error: function(resposta) {
                    $.alert({
                        icon: "far fa-times-circle",
                        type: "red",
                        title: "Erro",
                        content: "Algo deu errado"
                    });
                }
            });
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

        printCredentials: function (id) {
            $.ajax({
                type: "get",
                url: "?m=instructor_admin&template=print_credentials&class_id="+id
            }).done(function(response) {
                course.popup(response);
            });
        },

        popup: function (data) {

            var mywindow = window.open();
            mywindow.document.write('<html><head><title>Impressão</title>');
            mywindow.document.write('</head><body>');
            mywindow.document.write(data);
            mywindow.document.write('</body></html>');
            mywindow.document.close(); // necessary for IE >= 10
            window.focus(); // necessary for IE >= 10
            mywindow.print();

            return true;
        },

        viewFeedback: function () {
            $.ajax({
                type: "get",
                url: "?m=instructor_admin&template=view_student_feedback_evaluation"
            }).done(function(response) {
                var modal = $('#modalEvaluationFeedback');
                modal.find('.modal-body').html(response);
                modal.modal();
            });
        },

        viewClassGroups: function (id) {
            $.ajax({
                type: "get",
                url: "?m=instructor_admin&template=class_groups&class_id="+id
            }).done(function(response) {
                var modal = $('#modalClassGroups');
                $('.groupFeedback').hide();
                $('.btnBackToDetails').hide();
                $('.groupDetails').html(response);
                modal.modal();
            });
        },

        saveGroups: function (classId) {
            var number = $('input[name=number_of_groups]').val();

            if (!number.trim() || number == 0) {
                $.alert({
                    icon: "far fa-times-circle",
                    type: "red",
                    title: "Erro",
                    content: 'Informe o número de grupos. O número deve ser maior que 0.'
                });
                return;
            }

            $.ajax({
                url: "?m=instructor_admin",
                type: "post",
                datatype: "json",
                data: $("form[name=formNumberOfGroups]").serialize(),
                success: function(resposta) {
                    var element = $('#goupsTableContainer');
                    element.loading({
                        message: 'Carregando grupos...'
                    });
                    element.load(
                        "?m=instructor_admin&template=groups_table&class_id="+classId,
                        function () {
                            element.loading('stop');
                        }
                    );
                    $('input[name=number_of_groups]').val('');
                    $.alert({
                        icon: "far fa-check-circle",
                        type: "green",
                        title: "Sucesso",
                        content: resposta,
                    });
                },
                error: function(resposta) {
                    $.alert({
                        icon: "far fa-times-circle",
                        type: "red",
                        title: "Erro",
                        content: "Algo deu errado"
                    });
                }
            });
        },

        deleteGroup: function (classId, groupId) {
            $.confirm({
                title: '',
                content: "<?=$AppUI->_('LBL_DELETE_GROUP')?>",
                buttons: {
                    sim: function () {
                        $.ajax({
                            url: "?m=instructor_admin",
                            type: "post",
                            datatype: "json",
                            data: {
                                dosql: 'do_group_deletion',
                                class_id: classId,
                                group_id_for_deletion: groupId
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
                                            var element = $('#goupsTableContainer');
                                            element.loading({
                                                message: 'Carregando grupos...'
                                            });
                                            element.load(
                                                "?m=instructor_admin&template=groups_table&class_id="+classId,
                                                function () {
                                                    element.loading('stop');
                                                }
                                            );
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
        
        showGroupFeedback: function (userId, projectId, classId) {
            $.ajax({
                type: "get",
                url: "?m=instructor_admin&template=view_read_feedback&user_id="+userId+"&project_id="+projectId+"&class_id="+classId
            }).done(function(response) {
                $('#modalClassGroups').find('h5').html('<?=$AppUI->_("LBL_FEEDBACK_REPORT")?>')
                $('.groupDetails').hide();
                $('.btnBackToDetails').show();
                $('.groupFeedback').html(response).show();
            });
        },

        backToGroupDetails: function () {
            $('#modalClassGroups').find('h5').html('<?=$AppUI->_("LBL_CLASS_DATA")?>')
            $('.groupDetails').show();
            $('.groupFeedback').hide();
            $('.btnBackToDetails').hide();
        }
        
    };
    $(document).ready(course.init);
</script>