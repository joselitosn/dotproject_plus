<!-- Add edit project modal -->
<div class="modal" id="addEditProjectModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Modal title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body company-modal">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal"><?=$AppUI->_('LBL_CLOSE')?></button>
                <button type="button" class="btn btn-primary btn-sm" id="btnSaveCompany" ><?=$AppUI->_('LBL_SAVE')?></button>
            </div>
        </div>
    </div>
</div>

<script>

    var project = {

        init: function() {
            $('#addEditProjectModal').on('hidden.bs.modal', function() {
                $("#btnSaveCompany").off("click");
            });
        },

        new: function() {
            $.ajax({
                type: "get",
                url: "?m=projects&template=addedit&company_id=<?=$company_id?>"
            }).done(function(response) {
                $(".company-modal").html(response);
                $(".modal-title").html("<?=$AppUI->_('new project')?>");
                $("#btnSaveCompany").on("click", function() {
                    project.save();
                })
                $("#addEditProjectModal").modal();
            });
        },

        edit: function(projectId) {
            $.ajax({
                type: "get",
                url: "?m=projects&template=addedit&company_id=<?=$company_id?>&project_id=" + projectId
            }).done(function(response) {
                $(".company-modal").html(response);
                $(".modal-title").html("<?=$AppUI->_('Update Project')?>");
                $("#btnSaveCompany").on("click", function() {
                    project.save();
                });
                $("#addEditProjectModal").modal();
            });
        },

        save: function() {
            var name = $("input[name=project_name]").val();
            var company = $("select[name=project_company]").val();

            if (!name || company == 0) {
                var msg = [];
                if (!name) msg.push("Informe o nome do projeto");
                if (company == 0) msg.push("Selecione a empresa");
                $.alert({
                    icon: "far fa-times-circle",
                    type: "red",
                    title: "<?=$AppUI->_('Error', UI_OUTPUT_JS); ?>",
                    content: msg.join("<br>")
                });
                return;
            }
            $.ajax({
                url: "?m=projects",
                type: "post",
                datatype: "json",
                data: $("form[name=addEditProject]").serialize(),
                success: function(resposta) {
                    $.alert({
                        icon: "far fa-check-circle",
                        type: "green",
                        title: "<?=$AppUI->_('Success', UI_OUTPUT_JS); ?>",
                        content: resposta,
                        onClose: function() {
                            window.location.reload(true);
                        }
                    });
                    $("#addEditProjectModal").modal("hide");
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

        setShortName: function () {
            var x = 10;
            var $projectName = $('input[name=project_name]');

            var name = $projectName.val().trim();

            if (name.length < 11) {
                x = $projectName.val().length;
            }
            $('input[name=project_short_name]').val($projectName.val().trim().substr(0, x));
        },

        dateSelected: function (date, element) {
            var arrDate = date.split('/');
            $date = $('#'+element.id);
            if ($date.attr('name') == 'start_date') {
                $('input[name=project_start_date]').val(arrDate[2] + arrDate[1] + arrDate[0]);
            } else {
                $('input[name=project_end_date]').val(arrDate[2] + arrDate[1] + arrDate[0]);
            }
        },

        formatDates: function () {
            var startDate = $('input[name=project_start_date]').val();
            var endDate = $('input[name=project_end_date]').val();

            if (startDate) {
                $('input[name=start_date]').val(startDate.substr(6, 2)+'/'+startDate.substr(4, 2)+'/'+startDate.substr(0, 4));
            }
            if (endDate) {
                $('input[name=end_date]').val(endDate.substr(6, 2)+'/'+endDate.substr(4, 2)+'/'+endDate.substr(0, 4));
            }
        }
    };

    $(document).ready(project.init);
</script>