$(document).ready(function(e) {

    let collapseProject = $('#projectDetailsLink');
    $('#project_details').on('shown.bs.collapse', function () {
        collapseProject.find('i').removeClass('fa-caret-down');
        collapseProject.find('i').addClass('fa-caret-up');
    });
    $('#project_details').on('hidden.bs.collapse', function () {
        collapseProject.find('i').removeClass('fa-caret-up');
        collapseProject.find('i').addClass('fa-caret-down');
    });

    $('#sidebarCollapse').on('click', function () {
        $('#sidebar').toggleClass('active');

        if ($(this).hasClass('fa-angle-double-right')) {
            $(this).removeClass('fa-angle-double-right');
            $(this).addClass('fa-angle-double-left');
        } else {
            $(this).removeClass('fa-angle-double-left');
            $(this).addClass('fa-angle-double-right');
        }
    });
})