<!--<script src="./modules/timeplanning/js/estimations.js"></script>-->
<!--<script src="./modules/timeplanning/js/eap.js"></script>-->
<!--<script src="./modules/timeplanning/js/ajax_service_activator.js"></script>-->
<!---->
<!--<!-- include libraries for right click menu -->
<!--<script type="text/javascript" src="./modules/dotproject_plus/milonic_right_click_menu/milonic_src.js"></script> -->
<!--<script type="text/javascript" src="./modules/dotproject_plus/milonic_right_click_menu/mmenudom.js"></script> -->
<!--<script type="text/javascript" src="./modules/dotproject_plus/milonic_right_click_menu/contextmenu_activities_wbs.js"></script>-->
<!--<script type="text/javascript" src="./modules/dotproject_plus/milonic_right_click_menu/menu_data_activities.js"></script>-->
<!--<script type="text/javascript" src="./modules/dotproject_plus/milonic_right_click_menu/menu_data_wbs.js"></script>-->
<!--<script type="text/javascript" src="./modules/dotproject_plus/milonic_right_click_menu/menu_data_wbs_new_activity.js"></script>-->
<!--<script type="text/javascript" src="./modules/dotproject_plus/milonic_right_click_menu/menu_data_wbs_non_workpackage.js"></script>-->

<!-- This script code  was moved to here due to translation reasons -->
<script>
//    with (milonic = new menuname("contextMenu")) {
//        margin = 7;
//        style = contextStyle;
//        top = "offset=8";
//        aI("image=./modules/dotproject_plus/images/activities_details.png;text=<?php //echo $AppUI->_("Details"); ?>//;url=javascript:rightClickMenuShowActivity();");
//        aI("image=./modules/dotproject_plus/images/lapis.png;text=<?php //echo $AppUI->_("Edit"); ?>//;url=javascript:rightClickMenuEditActivity();");
//        aI("image=./modules/dotproject_plus/images/trash_small.gif;text=<?php //echo $AppUI->_("LBL_EXCLUSION"); ?>//;url=javascript:rightClickMenuExcludeActivity();");
//    }
//    drawMenus();
//
//    with (milonic = new menuname("contextMenuWBS")) {
//        margin = 9;
//        style = contextStyle;
//        top = "offset=5";
//        aI("image=./modules/dotproject_plus/images/mais_azul.png;text=<?php //echo $AppUI->_("LBL_MENU_NEW_WBS_ITEM"); ?>//;url=javascript:rightClickMenuNewEAPItem();");
//        aI("image=./modules/dotproject_plus/images/mais_verde.png;text=<?php //echo $AppUI->_("LBL_MENU_NEW_ACTIVITY"); ?>//;url=javascript:rightClickMenuNewActivity();");
//        aI("image=./modules/dotproject_plus/images/lapis.png;text=<?php //echo $AppUI->_("Edit"); ?>//;url=javascript:rightClickMenuEditEAPItem();");
//        aI("image=./modules/dotproject_plus/images/trash_small.gif;text=<?php //echo $AppUI->_("LBL_EXCLUSION"); ?>//;url=javascript:rightClickMenuDeleteWBSItem();");
//        aI("image=./modules/dotproject_plus/images/scope_declaration.jpg?eee=5;text=<?php //echo $AppUI->_("LBL_PROJECT_SCOPE_DECLARATION"); ?>//;url=javascript:rightClickMenuShowScopeDeclaration();");
//        aI("image=./modules/dotproject_plus/images/dicionario.png?eee=5;text=<?php //echo $AppUI->_("LBL_WBS_DICTIONARY"); ?>//;url=javascript:rightClickMenuShowWBSDictionary();");
//    }
//
//    drawMenus();
//
//    with (milonic = new menuname("contextMenuWBSNewActivity")) {
//        margin = 9;
//        style = contextStyle;
//        top = "offset=5";
//        aI("image=./modules/dotproject_plus/images/mais_verde.png;text=<?php //echo $AppUI->_("LBL_MENU_NEW_ACTIVITY"); ?>//;url=javascript:rightClickMenuFirstActivity();");
//    }
//    drawMenus();
//
//    with (milonic = new menuname("contextMenuWBSNonWorkPackage")) {
//        margin = 9;
//        style = contextStyle;
//        top = "offset=5";
//        aI("image=./modules/dotproject_plus/images/mais_azul.png;text=<?php //echo $AppUI->_("LBL_MENU_NEW_WBS_ITEM"); ?>//;url=javascript:rightClickMenuNewEAPItem();");
//        aI("image=./modules/dotproject_plus/images/lapis.png;text=<?php //echo $AppUI->_("Edit"); ?>//;url=javascript:rightClickMenuEditEAPItem();");
//        aI("image=./modules/dotproject_plus/images/trash_small.gif;text=<?php //echo $AppUI->_("LBL_EXCLUSION"); ?>//;url=javascript:rightClickMenuDeleteWBSItem();");
//        aI("image=./modules/dotproject_plus/images/scope_declaration.jpg?eee=5;text=<?php //echo $AppUI->_("LBL_PROJECT_SCOPE_DECLARATION"); ?>//;url=javascript:rightClickMenuShowScopeDeclaration();");
//        aI("image=./modules/dotproject_plus/images/dicionario.png?eee=5;text=<?php //echo $AppUI->_("LBL_WBS_DICTIONARY"); ?>//;url=javascript:rightClickMenuShowWBSDictionary();");
//    }
//    drawMenus();

</script>


<!--<a href="http://www.milonic.com/" style="display: none">DHTML JavaScript Menu By Milonic.com</a>-->
<!--<!-- include libraries for calendar goodies -->
<!--<link type="text/css" rel="stylesheet" href="./modules/timeplanning/js/jsLibraries/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112" media="screen"></link>-->
<!--<script type="text/javascript" src="./modules/timeplanning/js/jsLibraries/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=20060118"></script>-->

<script>
//    //Definition of messages as glocal variables
//    var mensagem_1 = "<?php //echo $AppUI->_("LBL_CREATE_ACTIVITIES_BEFORE_SEQUENCING", UI_OUTPUT_JS); ?>//";
//    var mensagem_2 = "<?php //echo $AppUI->_("LBL_CONFIRM_WBS_ITEM_EXCLUSION", UI_OUTPUT_JS); ?>//";
//    var mensagem_2_activity = "<?php //echo $AppUI->_("LBL_CONFIRM_ACTIVITY_EXCLUSION", UI_OUTPUT_JS); ?>//";
//
//    var mensagem_5_WBS = "<?php //echo $AppUI->_("LBL_CONFIRM_ITEM_CANCEL", UI_OUTPUT_JS); ?>//";
//    var mensagem_5 = "<?php //echo $AppUI->_("LBL_CONFIRM_ITEM_CANCEL", UI_OUTPUT_JS); ?>//";
//
//    function showMessage(msg) {
//        setAppMessage(msg, APP_MESSAGE_TYPE_INFO);
//        //window.alert(msg);
//    }
//
//    function showConfirmMessage(msg) {
//        return window.confirm(msg);
//    }
//
//
//    function saveWBSItem(wbsItemId) {
//        //window.alert(mensagem_4);
//        document.getElementById("save_wbs_" + wbsItemId).submit();
//        //showWBSItemEdit(wbsItemId);//this call will change to read mode the WBS item
//    }
//
//    function cancelSaveWBSItem(wbsItemId) {
//        if (showConfirmMessage(mensagem_5_WBS)) {
//            reload();
//        }
//    }
//
//    function newWBSItem(wbsItemId) {
//        document.getElementById("new_wbs_item_" + wbsItemId).submit();
//        //showWBSItemEdit(wbsItemId);//this call will change to read mode the WBS item
//    }
//
//    function createFirstActivity(wbsItemId) {
//        document.getElementById("new_first_activity_for_an_wbs_id_" + wbsItemId).submit();
//    }
//
//    function rightClickMenuFirstActivity() {
//        if (contextObject.id.indexOf("new_activity_wbs_item_id_") != -1) {
//            var wbsItemId = contextObject.id.split("new_activity_wbs_item_id_")[1];
//            createFirstActivity(wbsItemId);
//        }
//    }
//
//    function rightClickMenuShowActivity() {
//        if (contextObject.parentNode.id.indexOf("_activity_id_") != -1 || contextObject.parentNode.parentNode.id.indexOf("_activity_id_") != -1) {
//            var parentId = contextObject.parentNode.id.indexOf("_activity_id_") != -1 ? contextObject.parentNode.id : contextObject.parentNode.parentNode.id;
//            var activityId = parentId.split("_activity_id_")[1];
//            var wbsItemId = parentId.split("wbs_id_")[1].split("_activity_id_")[0];
//            showActivityDetails(activityId, wbsItemId);
//        }
//    }
//
//    function rightClickMenuShowWBSDictionary() {
//        showWBSDictionary();
//    }
//
//    function rightClickMenuShowScopeDeclaration() {
//        // var url = replaceAll("#gqs_anchor", "", window.location.href);
//
//        window.location = window.location.href + "&show_external_page=/modules/timeplanning/view/scope_declaration.php";
//    }
//
//    function rightClickMenuEditActivity() {
//        if (contextObject.parentNode.id.indexOf("_activity_id_") != -1 || contextObject.parentNode.parentNode.id.indexOf("_activity_id_") != -1) {
//            var parentId = contextObject.parentNode.id.indexOf("_activity_id_") != -1 ? contextObject.parentNode.id : contextObject.parentNode.parentNode.id;
//            var activityId = parentId.split("_activity_id_")[1];
//            var wbsItemId = parentId.split("wbs_id_")[1].split("_activity_id_")[0];
//            showActivityEdit(activityId, wbsItemId);
//        }
//    }
//
//    function rightClickMenuExcludeActivity() {
//        if (contextObject.parentNode.id.indexOf("_activity_id_") != -1 || contextObject.parentNode.parentNode.id.indexOf("_activity_id_") != -1) {
//            var parentId = contextObject.parentNode.id.indexOf("_activity_id_") != -1 ? contextObject.parentNode.id : contextObject.parentNode.parentNode.id;
//            var activityId = parentId.split("_activity_id_")[1];
//            var wbsItemId = parentId.split("wbs_id_")[1].split("_activity_id_")[0];
//            if (showConfirmMessage(mensagem_2_activity)) {
//                excludeActivity(activityId, wbsItemId);
//                //showMessage(mensagem_4);
//            }
//        }
//    }
//
//    function prepareRolesToSave(taskId) {
//        var quant = parseInt(document.getElementById("roles_num_" + taskId).value);//this field provides the amount of estimated roles
//        var rolesIds = document.getElementById("estimatedRolesIds_" + taskId);//this field stores all selected roles separed by ";"
//        var rolesHRs = document.getElementById("estimatedRolesHR_" + taskId);//this field stores all selected hr separed by ";"
//        var resultado = true;
//        var feedback_message = "";
//        //verifica se algum usuário já foi alocado mais de uma vez
//        for (i = 0; i < quant; i++) {
//            var selectField = document.getElementById("allocated_hr_role_" + taskId + "_" + i);
//            var testRHId = selectField.options[selectField.selectedIndex].value;
//            if (testRHId != "") {
//                var count = 0;
//                for (j = 0; j < quant; j++) {
//                    selectField = document.getElementById("allocated_hr_role_" + taskId + "_" + j);
//                    var tempRHid = selectField.options[selectField.selectedIndex].value;
//                    if (testRHId == tempRHid && selectField.style.display != "none") {
//                        count++;
//                    }
//                }
//
//                if (count > 1 && resultado) {//resultado is used just to ensure the message will be presented once
//                    selectField = document.getElementById("allocated_hr_role_" + taskId + "_" + i);
//                    feedback_message += selectField.options[selectField.selectedIndex].text + " já está alocado para esta atividade, escolha outra pessoa.<br />";
//                    resultado = false; // Do not allow to save: A user is allocated twice to the same activity
//                }
//            }
//        }
//
//        for (i = 0; i < quant; i++) {
//            var nextId = i;
//            selectField = document.getElementById("estimated_role_" + taskId + "_" + nextId);
//            var role_id = selectField.options[selectField.selectedIndex].value;
//            rolesIds.value += role_id + ";";
//            selectField = document.getElementById("allocated_hr_role_" + taskId + "_" + nextId);
//            var hr_id = selectField.options[selectField.selectedIndex].value;
//            var hr_name = selectField.options[selectField.selectedIndex].text;
//            rolesHRs.value += hr_id + ";";
//            //alert("Role: "+ document.getElementById("estimated_role_" + taskId + "_" + nextId).value + " | HR: "+document.getElementById("allocated_hr_role_" + taskId + "_" + nextId).value);
//            if (hr_id != "" && role_id == "") {// A human resource has been selected, but a role has not been estimated.
//                feedback_message += "Por favor, selecione o papel antes de alocar o RH. <br />Detalhes: O recurso humano (" + hr_name + ") foi alocado sem que um papel fosse estimado.<br />";
//                resultado = false; // Do not allow to save: A human resource was allocated without a role estimated
//            }
//        }
//        if (!resultado) {
//            setAppMessage(feedback_message, APP_MESSAGE_TYPE_WARNING);
//        }
//        return resultado;
//    }
//
//
//    function reload() {
//        window.location.reload();
//    }
//
//    function rightClickMenuNewEAPItem() {
//        if (contextObject.parentNode.id.indexOf("row_") != -1 || contextObject.parentNode.parentNode.id.indexOf("row_") != -1) {
//            var parentId = contextObject.parentNode.id.indexOf("row_") != -1 ? contextObject.parentNode.id : contextObject.parentNode.parentNode.id;
//            var wbsItemId = parentId.split("row_")[1];
//            if (!wbsHasActivity[wbsItemId]) {
//                newWBSItem(wbsItemId);
//            } else {
//                window.alert("<?php //echo $AppUI->_("LBL_WBS_ITEM_HAS_ACTIVITY", UI_OUTPUT_JS); ?>//");
//            }
//        }
//    }
//
//    function rightClickMenuDeleteWBSItem() {
//        if (contextObject.parentNode.id.indexOf("row_") != -1 || contextObject.parentNode.parentNode.id.indexOf("row_") != -1) {
//            var parentId = contextObject.parentNode.id.indexOf("row_") != -1 ? contextObject.parentNode.id : contextObject.parentNode.parentNode.id;
//            var wbsItemId = parentId.split("row_")[1];
//            deleteWBSItem(wbsItemId);
//        }
//    }
//
//    function deleteWBSItem(wbsItemId) {
//        if (showConfirmMessage(mensagem_2)) {
//            document.getElementById("delete_wbs_" + wbsItemId).submit();
//        }
//    }
//
//    function createFirstWBSItem() {
//        document.getElementById("new_wbs_item_first").submit();
//    }
//
//    function rightClickMenuNewActivity() {
//        if (contextObject.parentNode.id.indexOf("row_") != -1 || contextObject.parentNode.parentNode.id.indexOf("row_") != -1) {
//            var parentId = contextObject.parentNode.id.indexOf("row_") != -1 ? contextObject.parentNode.id : contextObject.parentNode.parentNode.id;
//            var wbsItemId = parentId.split("row_")[1];
//            newActivity(wbsItemId);
//        }
//    }
//
//    function rightClickMenuEditEAPItem() {
//        if (contextObject.parentNode.id.indexOf("row_") != -1 || contextObject.parentNode.parentNode.id.indexOf("row_") != -1) {
//            var parentId = contextObject.parentNode.id.indexOf("row_") != -1 ? contextObject.parentNode.id : contextObject.parentNode.parentNode.id;
//            var wbsItemId = parentId.split("row_")[1];
//            showWBSItemEdit(wbsItemId);
//        }
//    }
//
//    function newActivity(wbsItemId) {
//        if (document.getElementById("wbs_item_is_leaf_id_" + wbsItemId).value == "1") {
//            document.getElementById("new_activity_for_wbs_" + wbsItemId).submit();
//        } else {
//            window.alert("Ação não permitida!\nApenas pacotes de trabalho (folhas da EAP) podem ter atividades derivadas.");
//        }
//    }
//
//
//    function expandControlWorkpackageActivities(id) {
//        if (document.getElementById("collapse_icon_" + id).style.display == "none") {
//            expandActivities(id);
//        } else {
//            collapseActivities(id);
//        }
//    }
//
//    function expandActivities(id) {
//        var table = document.getElementById("tb_eap");
//        for (var i = 0; i < table.rows.length; i++) {
//            row = table.rows[i];
//            if (row.id.indexOf("wbs_id_" + id) != -1) {
//                row.style.display = "none";
//            }
//        }
//        document.getElementById("collapse_icon_" + id).style.display = "inline";
//        document.getElementById("expand_icon_" + id).style.display = "none";
//    }
//
//    function collapseActivities(id) {
//        var table = document.getElementById("tb_eap");
//        for (var i = 0; i < table.rows.length; i++) {
//            row = table.rows[i];
//            if (row.id.indexOf("wbs_id_" + id) != -1 && row.id.indexOf("activity_details_id_") == -1) {
//                row.style.display = "table-row";
//            }
//        }
//        document.getElementById("collapse_icon_" + id).style.display = "none";
//        document.getElementById("expand_icon_" + id).style.display = "inline";
//    }
//
//    function filterActivitiesByUser() {
//        document.select_human_resource_filter_form.submit();
//    }
//
//    function viewSequenceActivities() {
//        if (parseInt(document.getElementById("activities_count").value) == 0) {
//            window.alert(mensagem_1);
//        } else {
//            // console.info(window.location.href);
//            // var url = replaceAll("#gqs_anchor", "", );
//            window.location = window.location.href + "&show_external_page=/modules/timeplanning/view/projects_mdp.php";
//        }
//    }
//
//    function showWBSDictionary() {
//        var url = window.location.href;
//        window.location = url + "&show_external_page=/modules/timeplanning/view/projects_wbs_dictionary.php";
//    }
//
//    function showActivityDetails(activityId, wbsItemId) {
//
//        if (document.getElementById("activity_effort_edit_" + activityId).style.display != "block") {//expand/collapse just if in read mode
//            var el = document.getElementById("activity_details_id_" + activityId + "_wbs_id_" + wbsItemId);
//            document.getElementById("activity_responsible_edit_" + activityId).style.display = "none";
//            document.getElementById("activity_effort_edit_" + activityId).style.display = "none";
//
//            document.getElementById("activity_resources_edit_" + activityId).style.display = "none";
//            document.getElementById("activity_responsible_read_" + activityId).style.display = "inline-block";
//            document.getElementById("activity_effort_read_" + activityId).style.display = "inline-block";
//            document.getElementById("activity_resources_read_" + activityId).style.display = "inline-block";
//            document.getElementById("activity_edit_actions_" + activityId).style.display = "none";
//            document.getElementById("activity_sort_id_" + activityId).style.display = "inline-block";
//            document.getElementById("activity_code_id_" + activityId).style.display = "inline-block";
//
//            //The set of elements below are always displayed. So the edit/read mode show be controled.
//            document.getElementById("activity_rh_edit_id_" + activityId).style.display = "none";
//            document.getElementById("activity_date_end_edit_id_" + activityId).style.display = "none";
//            document.getElementById("activity_date_start_edit_id_" + activityId).style.display = "none";
//            document.getElementById("activity_description_edit_id_" + activityId).style.display = "none";
//            document.getElementById("activity_rh_read_id_" + activityId).style.display = "inline-block";
//            document.getElementById("activity_date_end_read_id_" + activityId).style.display = "inline-block";
//            document.getElementById("activity_date_start_read_id_" + activityId).style.display = "inline-block";
//            document.getElementById("activity_description_read_id_" + activityId).style.display = "inline-block";
//
//
//            if (el.style.display != "none") {
//                el.style.display = "none";
//            } else {
//                el.style.display = "table-row";
//            }
//        }
//    }
//
//    function showWBSItemEdit(wbsItemId) {
//        var el = document.getElementById("edit_workpackage_id_" + wbsItemId);
//        var el_read = document.getElementById("read_workpackage_id_" + wbsItemId);
//        if (el.style.display != "none") {
//            el.style.display = "none";
//            el_read.style.display = "table-row";
//        } else {
//            el.style.display = "table-row";
//            el_read.style.display = "none";
//        }
//        document.getElementById("wbs_item_description_" + wbsItemId).focus();
//
//
//    }
//
//
//    function showActivityEdit(activityId, wbsItemId) {
//        var el = document.getElementById("activity_details_id_" + activityId + "_wbs_id_" + wbsItemId);
//        document.getElementById("activity_responsible_edit_" + activityId).style.display = "inline-block";
//        document.getElementById("activity_effort_edit_" + activityId).style.display = "inline-block";
//        document.getElementById("activity_resources_edit_" + activityId).style.display = "inline-block";
//        document.getElementById("activity_responsible_read_" + activityId).style.display = "none";
//        document.getElementById("activity_effort_read_" + activityId).style.display = "none";
//        document.getElementById("activity_resources_read_" + activityId).style.display = "none";
//        document.getElementById("activity_edit_actions_" + activityId).style.display = "inline-block";
//
//        document.getElementById("activity_sort_id_" + activityId).style.display = "none";
//        document.getElementById("activity_code_id_" + activityId).style.display = "inline-block";
//
//        //The set of elements below are always displayed. So the edit/read mode show be controled.
//        document.getElementById("activity_rh_edit_id_" + activityId).style.display = "inline-block";
//        document.getElementById("activity_date_end_edit_id_" + activityId).style.display = "inline-block";
//        document.getElementById("activity_date_start_edit_id_" + activityId).style.display = "inline-block";
//        document.getElementById("activity_description_edit_id_" + activityId).style.display = "inline-block";
//
//        document.getElementById("activity_rh_read_id_" + activityId).style.display = "none";
//        document.getElementById("activity_date_end_read_id_" + activityId).style.display = "none";
//        document.getElementById("activity_date_start_read_id_" + activityId).style.display = "none";
//        document.getElementById("activity_description_read_id_" + activityId).style.display = "none";
//
//        // if (el.style.display != "none") {
//        //      el.style.display = "none";
//        // } else {
//        el.style.display = "table-row";
//        document.getElementById("activity_description_id_" + activityId).focus();
//        // }
//    }
//
//
//    function saveActivity(activityId, wbsItemId) {
//        if (validateEstimatedDates()) {
//            //showMessage(mensagem_4);
//            if (prepareRolesToSave(activityId)) {
//                document.getElementById("activity_form_" + activityId).submit();
//            }
//        }
//    }
//
//    function cancelSaveActivity(activityId, wbsItemId) {
//        if (showConfirmMessage(mensagem_5)) {
//            reload();
//        }
//    }
//
//    function excludeActivity(activityId, wbsItemId) {
//        document.getElementById("delete_activity_" + activityId).submit();
//        /*
//         var row = document.getElementById("wbs_id_" + wbsItemId + "_activity_id_" + activityId);
//         var rowDetails = document.getElementById("activity_details_id_" + activityId + "_wbs_id_" + wbsItemId);
//         document.getElementById("tb_eap").deleteRow(row.rowIndex);
//         document.getElementById("tb_eap").deleteRow(rowDetails.rowIndex);
//         */
//    }
//
//
//    function sortWBSItem(direction, wbsItemId) {
//        var form = document.getElementById("sort_wbs_" + wbsItemId);
//        form.direction.value = direction;
//        form.submit();
//    }
//
//
//    function moveRow(direction, rowId, rowDetailsId, taskId) {
//        var oTable = document.getElementById("tb_eap");
//        var trs = oTable.tBodies[0].getElementsByTagName("tr");
//        var i = document.getElementById(rowId).rowIndex;
//        var j = i + direction + direction;
//        if (j == 0) {
//            return false;
//        }
//        if (i >= 0 && j >= 0 && i < trs.length && j < trs.length) {
//            //logical hook:just allow switch beetween activities
//
//            if (oTable.rows[j].id.indexOf("activity_id_") == -1) {
//                //alert("Failed hook:"+oTable.rows[j].id);
//                return false;
//            }
//
//            var form = document.getElementById("sort_activity_" + taskId);
//            form.direction.value = direction;
//            form.submit();
//            //As linhas não serão movimentadas pois
//            /*
//             if (i == j + 1) {
//             oTable.tBodies[0].insertBefore(trs[i], trs[j]);
//             } else if (j == i + 1) {
//             oTable.tBodies[0].insertBefore(trs[j], trs[i]);
//             } else {
//             var tmpNode = oTable.tBodies[0].replaceChild(trs[i], trs[j]);
//             if (typeof(trs[i]) != "undefined") {
//             oTable.tBodies[0].insertBefore(tmpNode, trs[i]);
//             } else {
//             oTable.appendChild(tmpNode);
//             }
//             }
//             moveRowDetails(direction, rowDetailsId);
//             */
//        } else {
//            //alert("Invalid Values!");
//            return false;
//        }
//
//    }
//
//    function moveRowDetails(direction, rowId) {
//        var oTable = document.getElementById("tbl_project_activities");
//        var trs = oTable.tBodies[0].getElementsByTagName("tr");
//        var i = document.getElementById(rowId).rowIndex;
//        var j = i + direction + direction;
//        if (j == 0) {
//            return false;
//        }
//        if (i >= 0 && j >= 0 && i < trs.length && j < trs.length) {
//            if (i == j + 1) {
//                oTable.tBodies[0].insertBefore(trs[i], trs[j]);
//            } else if (j == i + 1) {
//                oTable.tBodies[0].insertBefore(trs[j], trs[i]);
//            } else {
//                var tmpNode = oTable.tBodies[0].replaceChild(trs[i], trs[j]);
//                if (typeof (trs[i]) != "undefined") {
//                    oTable.tBodies[0].insertBefore(tmpNode, trs[i]);
//                } else {
//                    oTable.appendChild(tmpNode);
//                }
//            }
//        } else {
//            //alert("Invalid Values!");
//            return false;
//        }
//    }
//
//    function addEstimatedRoleHR(taskId, roleId, hrId, quantity) {
//        var div = document.getElementById("div_res_" + taskId);
//        var roleField = document.createElement("select");
//        var hrField = document.createElement("select");
//        var removeButton = document.createElement("img");
//        var br = document.createElement("br");
//        var nextIdField = document.getElementById("roles_num_" + taskId);
//        var nextId = nextIdField.value;
//
//        roleField.name = "estimated_role_" + taskId + "_" + nextId;
//        roleField.id = "estimated_role_" + taskId + "_" + nextId;
//        roleField.className = "text";
//
//        hrField.name = "allocated_hr_role_" + taskId + "_" + nextId;
//        hrField.id = "allocated_hr_role_" + taskId + "_" + nextId;
//        hrField.className = "text";
//
//        br.id = "br_" + taskId + "_" + nextId;
//
//        removeButton.src = "./modules/dotproject_plus/images/trash_small.gif";
//        removeButton.id = nextId;
//        removeButton.title = roleId;
//        removeButton.name = taskId;
//        removeButton.style.cursor = "pointer";
//        removeButton.onclick = removeEstimatedRoleHR;
//
//        roleField.options[0] = new Option("<?php //echo $AppUI->_("LBL_ROLE"); ?>//", "");
//        for (i = 0; i < roleIds.length; i++) {
//            roleField.options[i + 1] = new Option(roleNames[i], roleIds[i]);
//            if (roleId == roleIds[i]) {
//                roleField.options[i + 1].selected = true;
//                roleField.selectedIndex = i + 1;
//            }
//        }
//
//        hrField.options[0] = new Option("<?php //echo $AppUI->_("Human Resource"); ?>//", "");
//
//        //set onchange evento to role select field
//        roleField.onchange = function () {
//            updateHROptionsBasedOnRole(roleField.id, hrField.id);
//        };
//
//        //add all created fields in the screen
//        div.appendChild(roleField);
//        div.appendChild(hrField);
//        div.appendChild(removeButton);
//        div.appendChild(br);
//        nextIdField.value = parseInt(nextIdField.value) + 1;
//
//        //Set default values on HR field, just if a previusly role had been selected
//        if (roleField.selectedIndex > 0) {
//            updateHROptionsBasedOnRole(roleField.id, hrField.id);//add hr obtions based on selected role.
//            //set the default value based in an previous selected hrId
//            for (i = 0; i < hrField.options.length; i++) {
//                if (hrId == hrField.options[i].value) {
//                    hrField.options[i].selected = true;
//                    hrField.selectedIndex = i;
//                }
//            }
//        }
//
//    }
//
//    function removeEstimatedRoleHR() {
//        var field = document.getElementById("estimatedRolesExcluded_" + this.name);
//        var fieldRemovedRolesIds = document.getElementById("estimatedRolesExcludedIds_" + this.name);
//        var idHR = "allocated_hr_role_" + this.name + "_" + this.id;
//        var idRole = "estimated_role_" + this.name + "_" + this.id;
//        var idBr = "br_" + this.name + "_" + this.id;
//        document.getElementById(idHR).style.display = "none";
//        document.getElementById(idRole).style.display = "none";
//        //document.getElementById(idBr).style.display="none";
//        this.style.display = "none";
//        var idRoleDb = this.title;
//        field.value += field.value == "" ? this.id : "," + this.id;
//        fieldRemovedRolesIds.value += fieldRemovedRolesIds.value == "" ? idRoleDb : "," + idRoleDb;
//    }
//
//    /**
//     * This function is called after the user select a option of an estimated role (onchange, onselect events).
//     * It receive as parameter the index of the role in the select options list, and then get the list of names available for this role.
//     * A new set of options is built based on this list.
//     */
//    function updateHROptionsBasedOnRole(roleSelectFieldId, hrSelectFieldId) {
//        var roleSelectField = document.getElementById(roleSelectFieldId);
//        var roleIndex = roleSelectField.selectedIndex - 1;//-1 because the first option of this field is invalid (the field label)
//        var hrSelectField = document.getElementById(hrSelectFieldId);
//        var hrs = hrPerRole[roleIndex];
//        var hr = null;
//        var hr_id = null;
//        var hr_name = null;
//        var option = null;
//        var optGroup = null;
//
//        if (roleIndex > -1) {
//            //remove all options from the select field
//            while (hrSelectField.options.length > 0) {
//                hrSelectField.options.remove(0)
//            }
//            //remove all optgroups from the select field
//            var ogl = hrSelectField.getElementsByTagName('optgroup');
//            for (var i = ogl.length - 1; i >= 0; i--) {
//                hrSelectField.removeChild(ogl[i])
//            }
//
//            //include options and optgroups
//            option = document.createElement("option");
//            option.text = "<?php //echo $AppUI->_("Human Resource") ?>//";
//            option.value = "";
//            hrSelectField.add(option);
//            optGroup = document.createElement("optgroup");
//            optGroup.label = roleSelectField.options[roleSelectField.selectedIndex].text;
//            hrSelectField.add(optGroup);
//            for (i = 0; i < hrs.length; i++) {
//                hr = hrs[i].split("#!");
//                if (hr.length == 2) {
//                    hr_id = hr[0];
//                    hr_name = hr[1];
//                    option = document.createElement("option");
//                    option.text = hr_name;
//                    option.value = hr_id;
//                    hrSelectField.add(option);
//                }
//            }
//            optGroup = document.createElement("optgroup");
//            //include all other hr that still does not contain the role
//            optGroup.label = "Outros";
//            hrSelectField.add(optGroup);
//            for (i = 0; i < hrIds.length; i++) {
//                if (hrs.indexOf(hrIds[i] + "#!" + hrNames[i]) == -1) {//include just HR that do not are in the hrs(hr per role) array
//                    option = new Option(hrNames[i], hrIds[i]);
//                    hrSelectField.add(option);
//                }
//            }
//        }
//    }
//    /**
//     * Function to assist the filling of activity dates.
//     * When the user select the start date, if it is after the current end date, or if it is empty, the it is filled with the same value as the start date.
//
//     * @returns {void}     */
//    function updateActivityDateOnChange(activityId, dateSeparator) {
//        try {
//            var start_field = document.getElementById("planned_start_date_activity_" + activityId);
//            var end_field = document.getElementById("planned_end_date_activity_" + activityId);
//            var date_parts = null;
//            var end_date_parts = null;
//            var startDate = null;
//            var endDate = null;
//            var updateEndDateField = false;
//            if (start_field.value.length == 10) {
//                date_parts = start_field.value.split(dateSeparator);
//                startDate = new Date(date_parts[2], date_parts[1], date_parts[0], 0, 0, 0, 0);
//                if (end_field.value.length == 10) {
//                    end_date_parts = end_field.value.split(dateSeparator);
//                    endDate = new Date(end_date_parts[2], end_date_parts[1], end_date_parts[0], 0, 0, 0, 0);
//                    if (startDate.getTime() > endDate.getTime()) {
//                        updateEndDateField = true;
//                    }
//                } else {
//                    updateEndDateField = true;
//
//                }
//                if (updateEndDateField) {
//                    end_field.value = date_parts[0] + dateSeparator + date_parts[1] + dateSeparator + date_parts[2];
//                }
//            }
//        } catch (e) {
//            console.log("Erro on function - updateActivityDateOnChange:" + e);
//        }
//    }
//
//    /*
//    * Funtion for disable/enable activity duration fields based on activity effort.
//    * It means,to enable the activity duration, the effort must be estimated
//    * This function is called after the rendering of these fields, and onchange of effort field.
//     */
//    function enableDurationBasedOnEffort(activityId){
//        if(activityId!=""){
//            var startDateInput=document.getElementById("planned_start_date_activity_"+activityId);
//            var endDateInput=document.getElementById("planned_end_date_activity_"+activityId);
//            var effortInput=document.getElementById("planned_effort_"+activityId);
//            var calendarIcon1=document.getElementById("calendar_trigger_1_"+activityId);
//            var calendarIcon2=document.getElementById("calendar_trigger_2_"+activityId);
//            var messagePanel=document.getElementById("message_effort_for_duration_"+activityId);
//            var effort=effortInput.value;
//
//
//            if(effort!="" && effort!=0 && !isNaN(parseInt(effort))){
//                endDateInput.disabled=false;
//                startDateInput.disabled=false;
//                calendarIcon1.style.visibility="visible";
//                calendarIcon2.style.visibility="visible";
//                messagePanel.style.visibility="hidden";
//            }else{
//                endDateInput.disabled=true;
//                startDateInput.disabled=true;
//                calendarIcon1.style.visibility="hidden";
//                calendarIcon2.style.visibility="hidden";
//                messagePanel.style.visibility="visible";
//            }
//        }
//    }

</script>

<?php
require_once (DP_BASE_DIR . "/modules/timeplanning/model/project_task_estimation.class.php");
require_once (DP_BASE_DIR . "/modules/timeplanning/model/wbs_item_estimation.class.php");
require_once (DP_BASE_DIR . "/modules/timeplanning/control/controller_wbs_items.class.php");
require_once (DP_BASE_DIR . "/modules/timeplanning/control/controller_wbs_item_activity_relationship.php");
require_once (DP_BASE_DIR . "/modules/timeplanning/control/controller_company_role.class.php");
require_once (DP_BASE_DIR . "/modules/tasks/tasks.class.php");
require_once (DP_BASE_DIR . "/modules/projects/projects.class.php");
require_once (DP_BASE_DIR . "/modules/dotproject_plus/copy_project/CopyProjectViewHelper.php");

$projectId = dPgetParam($_GET, 'project_id', 0);
$activitiesIdsForDisplay;
require_once (DP_BASE_DIR . "/modules/timeplanning/view/export_project_plan/time_planning_initializing_logical_ids.php");

if ($_GET["show_external_page"] != "") {
    include_once DP_BASE_DIR . $_GET["show_external_page"];
} else {
    $project_id = dPgetParam($_GET, "project_id", 0);
    $project = new CProject();
    $project->load($project_id);
    global $pstatus;
    $controllerWBSItem = new ControllerWBSItem();
    $ControllerWBSItemActivityRelationship = new ControllerWBSItemActivityRelationship();
    $controllerCompanyRole = new ControllerCompanyRole();

    $rolesArr = $controllerCompanyRole->getCompanyRoles($project->project_company);

    $i = 0;
    foreach ($rolesArr as $role) {
        $roles[$role->getId()] = $role->getDescription();

        $q = new DBQuery();
        $q->addTable('contacts', 'c');
        $q->addQuery('user_id, h.human_resource_id, contact_id,u.user_username');
        $q->innerJoin('users', 'u', 'u.user_contact = c.contact_id');
        $q->innerJoin('human_resource', 'h', 'h.human_resource_user_id = u.user_id');
        $q->innerJoin('human_resource_roles', 'hr_roles', 'hr_roles.human_resource_id =h.human_resource_id and hr_roles.human_resources_role_id=' . $role->getId());
        $q->addWhere('c.contact_company = ' . $project->project_company);
        $q->addOrder("u.user_username");
        $sql = $q->prepare();
        $records = db_loadList($sql);
        $j = 0;
        foreach ($records as $record) {
            $userNameByHRid[$record[1]] = $record[3];
            $j++;
        }
        $i++;
    }

    $q = new DBQuery();
    $q->addTable('contacts', 'c');
    $q->addQuery('user_id, human_resource_id, contact_id,u.user_username');
    $q->innerJoin('users', 'u', 'u.user_contact = c.contact_id');
    $q->innerJoin('human_resource', 'h', 'h.human_resource_user_id = u.user_id');
    $q->addWhere('c.contact_company = ' . $project->project_company);
    $q->addOrder("u.user_username");
    $sql = $q->prepare();
    $hr = db_loadList($sql);

    $currentPage = substr($_SERVER["REQUEST_URI"], strpos($_SERVER["REQUEST_URI"], "index.php") + 9);

    ?>

    <h4><?=$AppUI->_("LBL_WBS",UI_OUTPUT_HTML)?></h4>
    <hr>
    <input type="hidden" value="<?=$project_id?>" id="projectIdHidden" />
    <!-- Filter section -->
    <div class="row">
        <div class="col-md-3">
            <form name="select_human_resource_filter_form" action="<?php echo $currentPage ?>" method="post">
                <div class="form-group">
                    <select id="project_resources_filter"
                        name="project_resources_filter"
                        class="form-control form-control-sm">
                        <option <?=$_POST["project_resources_filter"] == "" ? "selected" : "" ?>   value=""><?=$AppUI->_("All"); ?></option>
                        <?php
                            foreach ($hr as $record) {
                        ?>
                            <option <?=$_POST["project_resources_filter"] == $record[1] ? "selected" : "" ?> value="<?=$record[1] ?>">
                                <?=$record[3] ?>
                            </option>
                        <?php
                            }
                        ?>
                    </select>
                </div>
                <?php
                    //verify if the is some activity defined in the entire project
                    $q = new DBQuery();
                    $q->addQuery("t.task_id");
                    $q->addTable("tasks", "t");
                    $q->addWhere("t.task_project=" . $project_id);
                    $sql = $q->prepare();
                    $records = db_loadList($sql);
                    $activitiesCount = count($records);
                ?>
                <input type="hidden" name="activities_count" id="activities_count" value="<?php echo $activitiesCount ?>" />
            </form>
        </div>

        <div class="col-sm-9 text-right">
            <button type="button" class="btn btn-secondary btn-sm" onclick="main.openScopeDeclaration()"><?=$AppUI->_("LBL_PROJECT_SCOPE_DECLARATION")?></button>
            <button type="button" class="btn btn-secondary btn-sm" onclick="main.openDictionaryModal()"><?=$AppUI->_("LBL_WBS_DICTIONARY")?></button>
<!--            <button type="button" class="btn btn-secondary btn-sm" onclick="window.location = 'index.php?a=view&m=projects&project_id=--><?php //echo $project_id ?><!--&tab=1&show_external_page=/modules/timeplanning/view/need_for_training.php#gqs_anchor';"><?//=$AppUI->_("LBL_NEED_FOR_TRAINING")?><!--</button>-->
            <button type="button" class="btn btn-secondary btn-sm" onclick="main.openMinutesModal()"><?=$AppUI->_("LBL_MINUTES_ESTIMATION_MEETINGS")?></button>
            <button type="button" class="btn btn-secondary btn-sm" data-toggle="modal" data-target="#modalCopyProjectFromTemplate"><?=$AppUI->_("LBL_COPY_FROM_TEMPLATE")?></button>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="alert alert-info" role="alert">
                Para sequenciar as atividades você deve arrastar a atividade predecessora e soltar sobre a atividade sucessora.
            </div>
        </div>
    </div>

    <div class="modal" tabindex="-1" role="dialog" id="modalCopyProjectFromTemplate">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><?=$AppUI->_("LBL_COPY_FROM_TEMPLATE")?></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-secondary" role="alert">
                        <?=$AppUI->_("LBL_COPY_FROM_TEMPLATE_HELP")?>
                    </div>

                    <form id="copy_project_form">
                        <input name="dosql" type="hidden" value="do_copy_project" />
                        <input name="target_project_id" type="hidden" value="<?=$project_id?>" />
                        <div class="form-group">
                            <label for="project_to_copy"><?=$AppUI->_("LBL_PROJECT")?></label>
                            <?php
                                $copyProjectViewHelper = new CopyProjectViewHelper();
                                echo $copyProjectViewHelper->getProjectsCombo();
                            ?>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal"><?=$AppUI->_('LBL_CLOSE')?></button>
                    <button type="button" class="btn btn-primary btn-sm" onclick="main.copyFromTemplate()"><?=$AppUI->_('LBL_COPY')?></button>
                </div>
            </div>
        </div>
    </div>


    <?php 
        $project = new CProject();
        $project->load($project_id);
        $company_id = $project->project_company;
        $showFirstActivityCreation = false; //this variable make the controlling to showing of the message to create the first activity 
        $items = $controllerWBSItem->getWBSItems($project_id);

        if (count($items) == 0) {
    ?>
            <div class="alert alert-secondary text-center" role="alert">
                <?php echo $AppUI->_("LBL_CLICK") ?>
                <a href="#" onclick="wbs.new(<?=$project_id?>)">
                    <b><u><?php echo $AppUI->_("LBL_HERE") ?></u></b>
                </a>
                <?php echo $AppUI->_("LBL_CREATE_NEW_WBS_ITEM") ?>
            </div>
    <?php
        // WBS TREE DECLARATION
        } else {

            function createTree($i, $activitiesIdsForDisplay, $userNameByHRid, $roles, $project_id) {
                $tree = array();
                foreach($i as $node) {
                    createIndex($node->getNumber(), $tree, $node);
                }
                $dom = new DOMDocument('1.0');
                $mainCard = $dom->createElement('div');
                $dom->appendChild(createHtml($tree, $mainCard, $dom, $activitiesIdsForDisplay, $userNameByHRid, $roles, $project_id));
                echo $dom->saveHTML();
            }
             
             
            function createIndex($number, &$tree, $obj) {
                $index = strpos($number, '.');
                if (!$index) {
                    $tree[$number] = $obj->toArray();
                    $tree[$number]['parent'] = substr($obj->getNumber(), 0, strlen($obj->getNumber())-2);
                    return;
                }
                $newNumber = substr($number, $index+1);
                if (!($newNumber == '' || $newNumber == null)) {
                    return createIndex($newNumber, $tree[substr($number, 0, $index)], $obj);
                }
            }
            
            function createHtml($tree, $mainCard, $dom, $activitiesIdsForDisplay, $userNameByHRid, $roles, $project_id) {
                foreach($tree as $branch) {
                    if (is_array($branch)) {
                        $isLeaf = (bool)$branch['isLeaf'];
                        $numberOfTasks = '';
                        if ($isLeaf) {
                            $eapItem = new WBSItemEstimation();
                            $id = $branch['id'];
                            $eapItem->load($id);
                            $ControllerWBSItemActivityRelationship = new ControllerWBSItemActivityRelationship();
                            $tasks = $ControllerWBSItemActivityRelationship->getActivitiesByWorkPackage($id);
                            $numberOfTasks = $isLeaf ? ' (' . sizeof($tasks) . ')' : '';
                        }
                        $innerCard = strlen($branch['number']) > 1 ? ' inner-card' : '';
                        $card = $dom->createElement('div');
                        $cardClass = $dom->createAttribute('class');
                        $cardClass->value = 'card' . $innerCard;
                        $card->appendChild($cardClass);

                        $cardBody = $dom->createElement('div');
                        $cardBodyId = $dom->createAttribute('id');
                        $cardBodyId->value = $branch['number'];
                        $cardBody->appendChild($cardBodyId);
                        $cardBodyClass = $dom->createAttribute('class');
                        $cardBodyClass->value = 'card-body';
                        $cardBody->appendChild($cardBodyClass);

                        $cardRow = $dom->createElement('div');
                        $cardRowClass = $dom->createAttribute('class');
                        $cardRowClass->value = 'row';
                        $cardRow->appendChild($cardRowClass);

                        $carCol8 = $dom->createElement('div');
                        $carCol8Class = $dom->createAttribute('class');
                        $carCol8Class->value = 'col-md-8';
                        $carCol8->appendChild($carCol8Class);
                        $h5 = $dom->createElement('h5', $branch['number'] . ' ' . $branch['name'] . $numberOfTasks);

                        if ($branch['number'] != 1) {
                            $small = $dom->createElement('small', ' | Tamanho: ' . $branch['size'] . ' ' . $branch['sizeUnit']);
                            $h5->appendChild($small);
                        }
                        $carCol8->appendChild($h5);
                        
                        $carCol4 = $dom->createElement('div');
                        $carCol4Class = $dom->createAttribute('class');
                        $carCol4Class->value = 'col-md-4 text-right';
                        $carCol4->appendChild($carCol4Class);

                        // Dropdown
                        $dropdown = $dom->createElement('div');
                        $dropdownClass = $dom->createAttribute('class');
                        $dropdownClass->value = 'dropdown';
                        $dropdown->appendChild($dropdownClass);

                        // Dropdown link
                        $dropdownA = $dom->createElement('a');
                        $dropdownAClass = $dom->createAttribute('class');
                        $dropdownAClass->value = '';
                        $dropdownA->appendChild($dropdownAClass);
                        $dropdownAhref = $dom->createAttribute('href');
                        $dropdownAhref->value = '#';
                        $dropdownA->appendChild($dropdownAhref);
                        $dropdownAToggle = $dom->createAttribute('data-toggle');
                        $dropdownAToggle->value = 'dropdown';
                        $dropdownA->appendChild($dropdownAToggle);
                        $dropdownAId = $dom->createAttribute('id');
                        $dropdownAId->value = '#dropdown_' . $branch['id'];
                        $dropdownA->appendChild($dropdownAId);
                        $icon = $dom->createElement('i');
                        $iconClass = $dom->createAttribute('class');
                        $iconClass->value = 'fas fa-bars';
                        $icon->appendChild($iconClass);
                        $dropdownA->appendChild($icon);

                        $dropdown->appendChild($dropdownA);

                        // Dropdown menu
                        $dropdownMenu = $dom->createElement('div');
                        $dropdownMenuClass = $dom->createAttribute('class');
                        $dropdownMenuClass->value = 'dropdown-menu dropdown-menu-right';
                        $dropdownMenu->appendChild($dropdownMenuClass);
                        $dropdownMenuAriaL = $dom->createAttribute('aria-labelledby');
                        $dropdownMenuAriaL->value = 'dropdown_'.$branch['id'];
                        $dropdownMenu->appendChild($dropdownMenuAriaL);
                        
                        // Dropdown item
                        if(sizeof($tasks) == 0) {
                            $dropdownItem = $dom->createElement('a');
                            $dropdownItemClass = $dom->createAttribute('class');
                            $dropdownItemClass->value = 'dropdown-item';
                            $dropdownItem->appendChild($dropdownItemClass);
                            $dropdownItemhref = $dom->createAttribute('href');
                            $dropdownItemhref->value = 'javascript:void(0)';
                            $dropdownItem->appendChild($dropdownItemhref);
                            $dropdownItemOC = $dom->createAttribute('onclick');
                            $dropdownItemOC->value = 'wbs.new('.$project_id.',"'.$branch['number'].'",'.$branch['id'].')';
                            $dropdownItem->appendChild($dropdownItemOC);
                            $icon = $dom->createElement('i');
                            $iconClass = $dom->createAttribute('class');
                            $iconClass->value = 'far fa-plus-square';
                            $icon->appendChild($iconClass);
                            $dropdownItem->appendChild($icon);
                            $dropdownItemSpan = $dom->createElement('span', ' Criar Item EAP');
                            $dropdownItem->appendChild($dropdownItemSpan);
                            $dropdownMenu->appendChild($dropdownItem);
                        }
                        
                        // Dropdown item
                        if($isLeaf) {
                            $dropdownItem = $dom->createElement('a');
                            $dropdownItemClass = $dom->createAttribute('class');
                            $dropdownItemClass->value = 'dropdown-item';
                            $dropdownItem->appendChild($dropdownItemClass);
                            $dropdownItemhref = $dom->createAttribute('href');
                            $dropdownItemhref->value = 'javascript:void(0)';
                            $dropdownItem->appendChild($dropdownItemhref);
                            $dropdownItemOC = $dom->createAttribute('onclick');
                            $dropdownItemOC->value = 'tasks.new('. $branch['id'] . ')';
                            $dropdownItem->appendChild($dropdownItemOC);
                            $icon = $dom->createElement('i');
                            $iconClass = $dom->createAttribute('class');
                            $iconClass->value = 'far fa-plus-square';
                            $icon->appendChild($iconClass);
                            $dropdownItem->appendChild($icon);
                            $dropdownItemSpan = $dom->createElement('span', ' Nova Atividade');
                            $dropdownItem->appendChild($dropdownItemSpan);
                            $dropdownMenu->appendChild($dropdownItem);
                        }

                        // Dropdown item
                        $dropdownItem = $dom->createElement('a');
                        $dropdownItemClass = $dom->createAttribute('class');
                        $dropdownItemClass->value = 'dropdown-item';
                        $dropdownItem->appendChild($dropdownItemClass);
                        $dropdownItemhref = $dom->createAttribute('href');
                        $dropdownItemhref->value = 'javascript:void(0)';
                        $dropdownItem->appendChild($dropdownItemhref);
                        $dropdownItemOC = $dom->createAttribute('onclick');
                        $wbsSize = $branch['size'] ? $branch['size'] : '';
                        $wbsSizeUnit = $branch['sizeUnit'] ? $branch['sizeUnit'] : '';
                        $dropdownItemOC->value = 'wbs.update('.$project_id.',"'.$branch['number'].'",'.$branch['id'].',"'.$branch['name'].'","'.$wbsSize.'","'.$wbsSizeUnit.'",'.$isLeaf.')';
                        $dropdownItem->appendChild($dropdownItemOC);
                        $icon = $dom->createElement('i');
                        $iconClass = $dom->createAttribute('class');
                        $iconClass->value = 'far fa-edit';
                        $icon->appendChild($iconClass);
                        $dropdownItem->appendChild($icon);
                        $dropdownItemSpan = $dom->createElement('span', ' Alterar Item EAP');
                        $dropdownItem->appendChild($dropdownItemSpan);
                        $dropdownMenu->appendChild($dropdownItem);

                        // Dropdown item
                        $dropdownItem = $dom->createElement('a');
                        $dropdownItemClass = $dom->createAttribute('class');
                        $dropdownItemClass->value = 'dropdown-item';
                        $dropdownItem->appendChild($dropdownItemClass);
                        $dropdownItemhref = $dom->createAttribute('href');
                        $dropdownItemhref->value = 'javascript:void(0)';
                        $dropdownItem->appendChild($dropdownItemhref);
                        $dropdownItemOC = $dom->createAttribute('onclick');
                        $dropdownItemOC->value = 'wbs.delete('.$project_id.','.$branch['id'].',"'.$branch['name'].'")';
                        $dropdownItem->appendChild($dropdownItemOC);
                        $icon = $dom->createElement('i');
                        $iconClass = $dom->createAttribute('class');
                        $iconClass->value = 'far fa-trash-alt';
                        $icon->appendChild($iconClass);
                        $dropdownItem->appendChild($icon);
                        $dropdownItemSpan = $dom->createElement('span', ' Excluir Item EAP');
                        $dropdownItem->appendChild($dropdownItemSpan);
                        $dropdownMenu->appendChild($dropdownItem);
                        
                        $dropdown->appendChild($dropdownMenu);
                        $carCol4->appendChild($dropdown);
                        $cardRow->appendChild($carCol8);
                        $cardRow->appendChild($carCol4);
                        $cardBody->appendChild($cardRow);

                        if ($isLeaf) {

                            foreach($tasks as $task) {
                                $task_id = $task->task_id;
                                $taskDescription = $task->task_name;
                                $projectTaskEstimation = new ProjectTaskEstimation();
                                $projectTaskEstimation->load($task_id);

                                //duration and start/end dates.
                                $obj = new CTask();
                                $obj->load($task_id);

                                // Load task dependencies
                                $arrDep = explode(",", $obj->getDependencies());
                                $dependencies = array();
                                foreach ($arrDep as $dep_id) {
                                    if ($dep_id == "") {
                                        continue;
                                    }
                                    $dep = new CTask();
                                    $dep->load($dep_id);
                                    $dependencies[$dep_id] = "A." . $activitiesIdsForDisplay[$dep_id] ." ".$dep->task_name;
                                }

                                // Start and end dates
                                $startDateTxt = "Não informado";
                                $endDateTxt = "Não informado";
                                if (isset($obj->task_start_date) && isset($obj->task_end_date)) {
                                    $startDateTxt = date("d/m/Y", strtotime($obj->task_start_date));
                                    $endDateTxt = date("d/m/Y", strtotime($obj->task_end_date));
                                }
                                $duration = "";
                                if ($projectTaskEstimation->getDuration() != "") {
                                    $duration = "" . $projectTaskEstimation->getDuration() . " dia(s)";
                                }

                                // Start creating html structure
                                $taskCard = $dom->createElement('div');
                                $taskCardClass = $dom->createAttribute('class');
                                $taskCardClass->value = 'card inner-card draggable droppable';
                                $taskCard->appendChild($taskCardClass);
                                $taskCardData = $dom->createAttribute('data');
                                $taskCardData->value = $task_id;
                                $taskCard->appendChild($taskCardData);
                                $taskCardDT = $dom->createAttribute('data-toggle');
                                $taskCardDT->value = 'tooltip';
                                $taskCard->appendChild($taskCardDT);

                                $taskCardBody = $dom->createElement('div');
                                $taskCardBodyId = $dom->createAttribute('id');
                                $taskCardBodyId->value = $branch['number'];
                                $taskCardBody->appendChild($taskCardBodyId);
                                $taskCardBodyClass = $dom->createAttribute('class');
                                $taskCardBodyClass->value = 'card-body shrink';
                                $taskCardBody->appendChild($taskCardBodyClass);
//                                $taskCardBodyStyle = $dom->createAttribute('style');
//                                $taskCardBodyStyle->value = 'padding: 10px 20px 1px 20px';
//                                $taskCardBody->appendChild($taskCardBodyStyle);

                                $taskCardRow = $dom->createElement('div');
                                $taskCardRowClass = $dom->createAttribute('class');
                                $taskCardRowClass->value = 'row';
                                $taskCardRow->appendChild($taskCardRowClass);

                                $carCol1Icon = $dom->createElement('i');
                                $carCol1IconClass = $dom->createAttribute('class');
                                $carCol1IconClass->value = 'fas fa-grip-vertical';
                                $carCol1Icon->appendChild($carCol1IconClass);
                                $carCol1IconStyle = $dom->createAttribute('style');
                                $carCol1IconStyle->value = 'float: left;margin-right: 10px;color: #cecece;cursor: crosshair;';
                                $carCol1Icon->appendChild($carCol1IconStyle);

                                $carCol8 = $dom->createElement('div');
                                $carCol8Class = $dom->createAttribute('class');
                                $carCol8Class->value = 'col-md-10';
                                $carCol8->appendChild($carCol8Class);
                                $carCol8->appendChild($carCol1Icon);

                                switch ($obj->task_percent_complete) {
                                    case 0:
                                        $activityStatus = $dom->createElement('span', 'Iniciada');
                                        $activityStatusClass = $dom->createAttribute('class');
                                        $activityStatusClass->value = 'badge badge-primary';
                                        $activityStatus->appendChild($activityStatusClass);
                                        break;
                                    case 100:
                                        $activityStatus = $dom->createElement('span', 'Finalizada');
                                        $activityStatusClass = $dom->createAttribute('class');
                                        $activityStatusClass->value = 'badge badge-success';
                                        $activityStatus->appendChild($activityStatusClass);
                                        break;
                                    default:
                                        $activityStatus = $dom->createElement('span', 'Não iniciada');
                                        $activityStatusClass = $dom->createAttribute('class');
                                        $activityStatusClass->value = 'badge badge-info';
                                        $activityStatus->appendChild($activityStatusClass);
                                        break;
                                }
                                // Responsável
                                $query = new DBQuery();
                                $query->addTable("users", "u");
                                $query->addQuery("user_id, user_username, contact_last_name, contact_first_name, contact_id");
                                $query->addJoin("contacts", "c", "u.user_contact = c.contact_id");
                                $query->addWhere("u.user_id = " . $obj->task_owner);
                                $res = & $query->exec();
                                $user_name = '';
                                for ($res; !$res->EOF; $res->MoveNext()) {
                                    $user_name = $res->fields["contact_first_name"] . " " . $res->fields["contact_last_name"];
                                }

                                $h6 = $dom->createElement('h6', 'A.' . $activitiesIdsForDisplay[$task_id] . ' ' . $taskDescription . ' ');
                                $h6Class = $dom->createAttribute('class');
                                $h6Class->value = 'mouse-cursor-pointer';
                                $h6->appendChild($h6Class);
                                $h6Toggle = $dom->createAttribute('data-toggle');
                                $h6Toggle->value = 'collapse';
                                $h6->appendChild($h6Toggle);
                                $h6Target = $dom->createAttribute('data-target');
                                $h6Target->value = '#card_'.$task_id;
                                $h6->appendChild($h6Target);
                                $h6Style = $dom->createAttribute('style');
                                $h6Style->value = 'float: left;';
                                $h6->appendChild($h6Style);

                                $icon = $dom->createElement('i', '&nbsp;');
                                $iconClass = $dom->createAttribute('class');
                                $iconClass->value = 'fas fa-caret-down title';
                                $icon->appendChild($iconClass);
                                $carCol8->appendChild($h6);
                                $carCol8->appendChild($icon);

                                $small = $span = $dom->createElement('small');

                                // Responsável
                                $span = $dom->createElement('span', 'Responsável: ' . $user_name);
                                $spanClass = $dom->createAttribute('class');
                                $spanClass->value = 'responsible';
                                $span->appendChild($spanClass);
                                $small->appendChild($span);

                                // Período
                                $span = $dom->createElement('span', ' | Período: ' . $startDateTxt . ' até ' . $endDateTxt);
                                $spanClass = $dom->createAttribute('class');
                                $spanClass->value = 'task-date';
                                $span->appendChild($spanClass);
                                $small->appendChild($span);

                                $carCol8->appendChild($small);

                                $carCol4 = $dom->createElement('div');
                                $carCol4Class = $dom->createAttribute('class');
                                $carCol4Class->value = 'col-md-2 text-right';
                                $carCol4->appendChild($carCol4Class);
                                $carCol4->appendChild($activityStatus);

                                // Dropdown activity options
                                $dropdown = $dom->createElement('div');
                                $dropdownClass = $dom->createAttribute('class');
                                $dropdownClass->value = 'dropdown';
                                $dropdown->appendChild($dropdownClass);
                                $dropdownStyle = $dom->createAttribute('style');
                                $dropdownStyle->value = 'width: 20%; float: right;';
                                $dropdown->appendChild($dropdownStyle);

                                // Dropdown link
                                $dropdownA = $dom->createElement('a');
                                $dropdownAClass = $dom->createAttribute('class');
                                $dropdownAClass->value = '';
                                $dropdownA->appendChild($dropdownAClass);
                                $dropdownAhref = $dom->createAttribute('href');
                                $dropdownAhref->value = 'javascript:void(0)';
                                $dropdownA->appendChild($dropdownAhref);
                                $dropdownAToggle = $dom->createAttribute('data-toggle');
                                $dropdownAToggle->value = 'dropdown';
                                $dropdownA->appendChild($dropdownAToggle);
                                $dropdownAId = $dom->createAttribute('id');
                                $dropdownAId->value = '#dropdown_' . $branch['id'];
                                $dropdownA->appendChild($dropdownAId);
                                $icon = $dom->createElement('i');
                                $iconClass = $dom->createAttribute('class');
                                $iconClass->value = 'fas fa-bars';
                                $icon->appendChild($iconClass);
                                $dropdownA->appendChild($icon);

                                $dropdown->appendChild($dropdownA);

                                $dropdownMenu = $dom->createElement('div');
                                $dropdownMenuClass = $dom->createAttribute('class');
                                $dropdownMenuClass->value = 'dropdown-menu dropdown-menu-right';
                                $dropdownMenu->appendChild($dropdownMenuClass);
                                $dropdownMenuAriaL = $dom->createAttribute('aria-labelledby');
                                $dropdownMenuAriaL->value = 'dropdown_'.$branch['id'];
                                $dropdownMenu->appendChild($dropdownMenuAriaL);

                                // Dropdown activity options item update activity
                                $dropdownItem = $dom->createElement('a');
                                $dropdownItemClass = $dom->createAttribute('class');
                                $dropdownItemClass->value = 'dropdown-item';
                                $dropdownItem->appendChild($dropdownItemClass);
                                $dropdownItemhref = $dom->createAttribute('href');
                                $dropdownItemhref->value = 'javascript:void(0)';
                                $dropdownItem->appendChild($dropdownItemhref);
                                $dropdownItemOC = $dom->createAttribute('onclick');
                                $dropdownItemOC->value = 'tasks.edit('.$branch['id'].','.$task_id.')';
                                $dropdownItem->appendChild($dropdownItemOC);
                                $icon = $dom->createElement('i');
                                $iconClass = $dom->createAttribute('class');
                                $iconClass->value = 'far fa-edit';
                                $icon->appendChild($iconClass);
                                $dropdownItem->appendChild($icon);
                                $dropdownItemSpan = $dom->createElement('span', ' Alterar Atividade');
                                $dropdownItem->appendChild($dropdownItemSpan);
                                $dropdownMenu->appendChild($dropdownItem);

                                // Dropdown activity options item log
                                if (getPermission('task_log', 'edit')) {
                                    $dropdownItem = $dom->createElement('a');
                                    $dropdownItemClass = $dom->createAttribute('class');
                                    $dropdownItemClass->value = 'dropdown-item';
                                    $dropdownItem->appendChild($dropdownItemClass);
                                    $dropdownItemhref = $dom->createAttribute('href');
                                    $dropdownItemhref->value = 'javascript:void(0)';
                                    $dropdownItem->appendChild($dropdownItemhref);
                                    $dropdownItemOC = $dom->createAttribute('onclick');
                                    $dropdownItemOC->value = 'tasks.newLog('.$task_id.')';
                                    $dropdownItem->appendChild($dropdownItemOC);
                                    $icon = $dom->createElement('i');
                                    $iconClass = $dom->createAttribute('class');
                                    $iconClass->value = 'far fa-file-alt';
                                    $icon->appendChild($iconClass);
                                    $dropdownItem->appendChild($icon);
                                    $dropdownItemSpan = $dom->createElement('span', ' Novo log');
                                    $dropdownItem->appendChild($dropdownItemSpan);
                                    $dropdownMenu->appendChild($dropdownItem);
                                }

                                // Dropdown activity options item delete activity
                                $dropdownItem = $dom->createElement('a');
                                $dropdownItemClass = $dom->createAttribute('class');
                                $dropdownItemClass->value = 'dropdown-item';
                                $dropdownItem->appendChild($dropdownItemClass);
                                $dropdownItemhref = $dom->createAttribute('href');
                                $dropdownItemhref->value = 'javascript:void(0)';
                                $dropdownItem->appendChild($dropdownItemhref);
                                $dropdownItemOC = $dom->createAttribute('onclick');
                                $dropdownItemOC->value = 'tasks.delete('.$task_id.')';
                                $dropdownItem->appendChild($dropdownItemOC);
                                $icon = $dom->createElement('i');
                                $iconClass = $dom->createAttribute('class');
                                $iconClass->value = 'far fa-trash-alt';
                                $icon->appendChild($iconClass);
                                $dropdownItem->appendChild($icon);
                                $dropdownItemSpan = $dom->createElement('span', ' Excluir Atividade');
                                $dropdownItem->appendChild($dropdownItemSpan);
                                $dropdownMenu->appendChild($dropdownItem);

                                $dropdown->appendChild($dropdownMenu);
                                $carCol4->appendChild($dropdown);

                                ?>
                                <form action="?m=dotproject_plus" method="POST" id="formDeleteActivity_<?=$task_id?>">
                                    <input type="hidden" name="activity_id" value="<?=$task_id?>" />
                                    <input type="hidden" name="project_id" value="<?=$project_id?>" />
                                    <input type="hidden"  name="dosql" value="do_delete_activity" />
                                </form>

                                <?php

                                $taskCardRow->appendChild($carCol8);
                                $taskCardRow->appendChild($carCol4);

                                $taskCardBody->appendChild($taskCardRow);

                                $taskCardRow = $dom->createElement('div');
                                $taskCardRowClass = $dom->createAttribute('class');
                                $taskCardRowClass->value = 'row';
                                $taskCardRow->appendChild($taskCardRowClass);

                                $carCol6 = $dom->createElement('div');
                                $carCol6Class = $dom->createAttribute('class');
                                $carCol6Class->value = 'col-md-6';
                                $carCol6->appendChild($carCol6Class);

                                // Data início
                                $span = $dom->createElement('span', 'Início: ' . $startDateTxt);
                                $spanClass = $dom->createAttribute('class');
                                $spanClass->value = 'd-block';
                                $span->appendChild($spanClass);
                                $carCol6->appendChild($span);

                                // Data fim
                                $span = $dom->createElement('span', 'Fim: ' . $endDateTxt);
                                $spanClass = $dom->createAttribute('class');
                                $spanClass->value = 'd-block';
                                $span->appendChild($spanClass);
                                $carCol6->appendChild($span);

                                // Duração
                                $span = $dom->createElement('span', 'Duração: ' . $duration);
                                $spanClass = $dom->createAttribute('class');
                                $spanClass->value = 'd-block';
                                $span->appendChild($spanClass);
                                $carCol6->appendChild($span);

                                $taskCardRow->appendChild($carCol6);

                                $carCol6 = $dom->createElement('div');
                                $carCol6Class = $dom->createAttribute('class');
                                $carCol6Class->value = 'col-md-6';
                                $carCol6->appendChild($carCol6Class);
                                $taskCardRow->appendChild($carCol6);

                                // Responsável
                                $span = $dom->createElement('span', 'Responsável: ' . $user_name);
                                $spanClass = $dom->createAttribute('class');
                                $spanClass->value = 'd-block';
                                $span->appendChild($spanClass);
                                $carCol6->appendChild($span);

                                // Esforço
                                //metric index is db key
                                $effortMetrics = array();
                                $effortMetrics[0] = 'Pessoas/Hora';
                                $effortMetrics[1] = 'Pessoas/Minuto';
                                $effortMetrics[2] = 'Pessoas/Dia';
                                $effort = $projectTaskEstimation->getEffort();
                                $mett = '';
                                $i = 0;
                                foreach ($effortMetrics as $metric) {
                                    $selected = $i == $projectTaskEstimation->getEffortUnit() ? "selected" : "";
                                    if ($selected) {
                                        $effort .= ' ' . $metric;
                                    }
                                    $i++;
                                }
                                $span = $dom->createElement('span', 'Esforço: ' . $effort . $mett);
                                $spanClass = $dom->createAttribute('class');
                                $spanClass->value = 'd-block';
                                $span->appendChild($spanClass);
                                $carCol6->appendChild($span);

                                // Recursos humanos
                                $estimatedRolesTxt = "";
                                $rolesNonGrouped = $projectTaskEstimation->getRolesNonGrouped($task_id);
                                $totalRoles = count($rolesNonGrouped);
                                $i = 1;
                                 if ($_POST["project_resources_filter"] == "") {
                                     $hasFilteredRH = true; //controls if will be some filter based on human resource
                                 } else {
                                     $hasFilteredRH = false;
                                 }
                                foreach ($rolesNonGrouped as $role) {
                                    $role_estimated_id = $role->getQuantity(); // the quantity field is been used to store the estimated role id
                                    $allocated_hr_id = ""; //Get the allocated HR  (maybe there is just the role without allocation, in this case write the role name)
                                    //Get id of a possible old allocation to delete it
                                    $q = new DBQuery();
                                    $q->addTable("human_resource_allocation");
                                    $q->addQuery("human_resource_id");
                                    $q->addWhere("project_tasks_estimated_roles_id=" . $role_estimated_id);
                                    $sql = $q->prepare();
                                    $records = db_loadList($sql);


                                    foreach ($records as $record) {
                                        $allocated_hr_id = $record[0];
                                    }

                                    if ($allocated_hr_id != "") {
                                        $estimatedRolesTxt.=$userNameByHRid[$allocated_hr_id];
                                    } else {
                                        $estimatedRolesTxt.= $roles[$role->getRoleId()];
                                    }
                                    if ($totalRoles > $i) {
                                        $estimatedRolesTxt.=", ";
                                    }
                                    $i++;
                                    // TODO Ver filtro posteriormente
                                     if (!$hasFilteredRH && $_POST["project_resources_filter"] == $allocated_hr_id) {
                                         $hasFilteredRH = true;
                                     }
                                }
                                $span = $dom->createElement('span', 'Recursos humanos: ' . $estimatedRolesTxt);
                                $spanClass = $dom->createAttribute('class');
                                $spanClass->value = 'd-block';
                                $span->appendChild($spanClass);
                                $carCol6->appendChild($span);

                                $divCollapse = $dom->createElement('div');
                                $divCollapseClass = $dom->createAttribute('class');
                                $divCollapseClass->value = 'collapse';
                                $divCollapse->appendChild($divCollapseClass);
                                $divCollapseId = $dom->createAttribute('id');
                                $divCollapseId->value = 'card_'.$task_id;
                                $divCollapse->appendChild($divCollapseId);

                                $divCollapse->appendChild($taskCardRow);

                                $taskCardRow = $dom->createElement('div');
                                $taskCardRowClass = $dom->createAttribute('class');
                                $taskCardRowClass->value = 'row';
                                $taskCardRow->appendChild($taskCardRowClass);

                                $carCol12 = $dom->createElement('div');
                                $carCol12Class = $dom->createAttribute('class');
                                $carCol12Class->value = 'col-md-12';
                                $carCol12->appendChild($carCol12Class);
                                $carCol12Id= $dom->createAttribute('id');
                                $carCol12Id->value = 'activity-dependency-'.$task_id;
                                $carCol12->appendChild($carCol12Id);

                                // Show activity dependencies
                                $ul = $dom->createElement('ul');
                                foreach ($dependencies as $depId => $depDesc) {
                                    $li = $dom->createElement('li', $depDesc . ' ');
                                    $liId = $dom->createAttribute('id');
                                    $liId->value = $depId.'_'.$task_id;
                                    $li->appendChild($liId);

                                    $btn = $dom->createElement('button');
                                    $btnT = $dom->createAttribute('type');
                                    $btnT->value = 'button';
                                    $btn->appendChild($btnT);
                                    $btnOC = $dom->createAttribute('onclick');
                                    $btnOC->value = 'tasks.deleteDependency('.$depId.','.$task_id.')';
                                    $btnC = $dom->createAttribute('class');
                                    $btnC->value = 'btn btn-danger btn-xs';
                                    $btn->appendChild($btnC);
                                    $btn->appendChild($btnOC);

                                    $i = $dom->createElement('i');
                                    $iClass = $dom->createAttribute('class');
                                    $iClass->value = 'far fa-trash-alt';
                                    $i->appendChild($iClass);

                                    $btn->appendChild($i);
                                    $li->appendChild($btn);
                                    $ul->appendChild($li);
                                }

                                $alert = $dom->createElement('div');
                                $alertClass = $dom->createAttribute('class');
                                $alertClass->value = 'alert alert-secondary';
                                $alert->appendChild($alertClass);
                                $alertRole = $dom->createAttribute('role');
                                $alertRole->value = 'alert';
                                $alert->appendChild($alertRole);

                                if (!count($dependencies)) {
                                    $style = $dom->createAttribute('style');
                                    $style->value = 'display: none';
                                    $alert->appendChild($style);
                                }

                                $h6 = $dom->createElement('h6', 'Atividades predecessoras');
                                $alert->appendChild($h6);

                                $hr = $dom->createElement('hr');
                                $alert->appendChild($hr);

                                $alert->appendChild($ul);

                                $carCol12->appendChild($dom->createElement('br'));
                                $carCol12->appendChild($alert);
                                $taskCardRow->appendChild($carCol12);
                                $divCollapse->appendChild($taskCardRow);

                                $taskCardRow = $dom->createElement('div');
                                $taskCardRowClass = $dom->createAttribute('class');
                                $taskCardRowClass->value = 'row';
                                $taskCardRow->appendChild($taskCardRowClass);

                                $carCol12 = $dom->createElement('div');
                                $carCol12Class = $dom->createAttribute('class');
                                $carCol12Class->value = 'col-md-12';
                                $carCol12->appendChild($carCol12Class);

                                $carCol12Id= $dom->createAttribute('id');
                                $carCol12Id->value = 'activity-log-'.$task_id;
                                $carCol12->appendChild($carCol12Id);

                                $taskCardRow->appendChild($carCol12);
                                $divCollapse->appendChild($taskCardRow);

                                $taskCardBody->appendChild($divCollapse);

                                $taskCard->appendChild($taskCardBody);
                                $cardBody->appendChild($taskCard);
                            }
                        }
                        $card->appendChild($cardBody);

                        $mainCard->appendChild($card);
                        createHtml($branch, $cardBody, $dom, $activitiesIdsForDisplay, $userNameByHRid, $roles, $project_id);
                    }
                }
                return $mainCard;
            }

            createTree($items, $activitiesIdsForDisplay, $userNameByHRid, $roles, $project_id);

        }
        $projectTaskEstimation = new ProjectTaskEstimation();
    ?>

    <!-- MODAL NEW WBS ITEM FORM -->
    <div id="newWBSItemModal" class="modal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="<?=$AppUI->_("LBL_CLOSE")?>">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form name="wbsForm">

                        <div class="form-group">
                            <span class="required"></span>
                            <?=$AppUI->_('requiredField');?>
                        </div>

                        <div class="form-group">
                            <label class="required" for="<?=$AppUI->_("LBL_DESCRICAO")?>"><?=$AppUI->_("LBL_DESCRICAO")?></label>
                            <input type="text" name="wbs_item_description" class="form-control form-control-sm" maxlength="50" />
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="<?=$AppUI->_("LBL_TAMANHO")?>"><?=$AppUI->_("LBL_TAMANHO")?></label>
                                    <input type="text" name="wbs_item_size" class="form-control form-control-sm" maxlength="10" size="15" />
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label for="<?=$AppUI->_("LBL_UNITY")?>"><?=$AppUI->_("LBL_UNITY")?></label>
                                    <input type="text" name="wbs_item_size_unit" class="form-control form-control-sm" maxlength="30" size="25" />
                                </div>
                            </div>
                        </div>
                        <input name="dosql" type="hidden" value="do_save_wbs" />
                        <input type="hidden" id="wbsProjectId" name="project_id" value="" />
                        <input type="hidden" id="wbsParentId" name="parent_id" value="" />
                        <input type="hidden" id="wbsParentNumber" name="parent_number" value="" />
                        <input type="hidden" id="wbsNumber" name="number" value="" />
                        <input type="hidden" id="wbsItemId" name="item_id" value="-1" />
                        <input type="hidden" id="wbsIsLeaf" name="is_leaf" value="" />
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal"><?=$AppUI->_("LBL_CLOSE")?></button>
                    <button type="button" class="btn btn-primary btn-sm" onclick="wbs.save()"><?=$AppUI->_("LBL_SAVE")?></button>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL WBS DICTIONARY FORM -->
    <div id="modalWbsDictionary" class="modal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><?=$AppUI->_("LBL_WBS_DICTIONARY")?></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="<?=$AppUI->_("LBL_CLOSE")?>">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body wbs-dictionary-modal">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal"><?=$AppUI->_("LBL_CLOSE")?></button>
                    <button type="button" class="btn btn-primary btn-sm" onclick="main.saveDictionary()"><?=$AppUI->_("LBL_SAVE")?></button>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL NEW TASK FORM -->
    <div id="taskModal" class="modal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><?=$AppUI->_("LBL_MENU_NEW_ACTIVITY")?></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="<?=$AppUI->_("LBL_CLOSE")?>">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body task-modal">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal"><?=$AppUI->_("LBL_CLOSE")?></button>
                    <button type="button" class="btn btn-primary btn-sm" onclick="tasks.save()"><?=$AppUI->_("LBL_SAVE")?></button>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL TASK LOG FORM -->
    <div id="taskLogModal" class="modal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Novo log</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="<?=$AppUI->_("LBL_CLOSE")?>">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body task-log">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal"><?=$AppUI->_("LBL_CLOSE")?></button>
                    <button type="button" class="btn btn-primary btn-sm" onclick="tasks.saveLog()"><?=$AppUI->_("LBL_SAVE")?></button>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL MINUTES -->
    <div id="minutesModal" class="modal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><?=$AppUI->_("LBL_MINUTES")?></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="<?=$AppUI->_("LBL_CLOSE")?>">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="minutesList">

                    </div>
                    <div id="minutesForm">

                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light btn-sm" id="btnBackList" onclick="main.backMinuteList()">Voltar</button>
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal"><?=$AppUI->_("LBL_CLOSE")?></button>
                    <button type="button" class="btn btn-primary btn-sm" id="btnSaveMinute" onclick="main.saveMinute()"><?=$AppUI->_("LBL_SAVE")?></button>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL SCOPE DECLARATION -->
    <div class="modal" tabindex="-1" role="dialog" id="modalScopeDeclaration">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><?=$AppUI->_('LBL_PROJECT_SCOPE_DECLARATION')?></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal"><?=$AppUI->_('LBL_CLOSE')?></button>
                    <button type="button" class="btn btn-primary btn-sm" onclick="main.saveScopeDeclaration()"><?=$AppUI->_("LBL_SAVE")?></button>
                </div>
            </div>
        </div>
    </div>
<?php
    }
?>

<script>

    var main = {
        projectId: $('#projectIdHidden').val(),
        btnYes: "Sim",
        btnNo: "Não",

        init: function() {

            $('.inner-card').find('h6').on('click', function(e) {
                var taskId;
                try {
                    taskId = $(e.target).attr('data-target').split('_')[1];
                } catch (err) {
                    taskId = $(e.target).parent().attr('data-target').split('_')[1];
                }
                if ($(this).next('i').hasClass('fa-caret-down')) {
                    $(this).next('i').removeClass('fa-caret-down');
                    $(this).next('i').addClass('fa-caret-up');
                    $(this).siblings('small').hide();
                    tasks.loadLogs(taskId);
                } else {
                    $(this).next('i').removeClass('fa-caret-up');
                    $(this).next('i').addClass('fa-caret-down');
                    $(this).siblings('small').show();
                }

            });

            $('#project_resources_filter').select2({
                placeholder: 'Filtrar por recurso humano',
                allowClear: true,
                theme: "bootstrap",
            }).on('select2:select', function (e) {
                document.select_human_resource_filter_form.submit();
            });

            $('#newWBSItemModal').on('hidden.bs.modal', function() {
                // Hidden fields
                $('#wbsProjectId').val('');
                $('#wbsParentId').val('');
                $('#wbsParentNumber').val('');
                $('#wbsNumber').val('');
                $('#wbsItemId').val('-1');

                // Shown fields
                $('input[name=wbs_item_description]').val('');
                $('input[name=wbs_item_size]').val('');
                $('input[name=wbs_item_size_unit]').val('');
            });

            $('#copy_project_form').find('select').select2({
                placeholder: '',
                allowClear: true,
                theme: "bootstrap",
                dropdownParent: $("#modalCopyProjectFromTemplate")

            });

            $('#minutesModal').on('hidden.bs.modal', function() {
                $("#minutesList").html('').show();
                $("#minutesForm").html('').hide();
            });

            main.initDragAndDrop();
        },

        openScopeDeclaration: function () {
            $.ajax({
                type: "get",
                url: "?m=timeplanning&template=/view/scope_declaration&project_id="+main.projectId
            }).done(function(response) {
                var modal = $('#modalScopeDeclaration');
                modal.find('.modal-body').html(response);
                modal.modal();
            });
        },

        saveScopeDeclaration: function () {
            $.ajax({
                method: 'POST',
                url: "?m=timeplanning",
                data: $("form[name=form_scope_declaration]").serialize(),
                success: function(resposta) {
                    $.alert({
                        title: "Sucesso",
                        content: resposta,
                        onClose: function() {
                            $('#modalScopeDeclaration').modal('hide');
                        }
                    });
                },
                error: function(resposta) {
                    $.alert({
                        title: "Erro",
                        content: "Algo deu errado"
                    });
                }
            });
        },

        openDictionaryModal: function() {
            $.ajax({
                type: "get",
                url: "?m=timeplanning&template=/view/projects_wbs_dictionary&project_id="+main.projectId
            }).done(function(response) {
                $(".wbs-dictionary-modal").html(response);
                $('#modalWbsDictionary').modal();
            });
        },

        saveDictionary: function () {
            $.ajax({
                method: 'POST',
                url: "?m=timeplanning",
                data: $("form[name=form_wbs_dictionary]").serialize(),
                success: function(resposta) {
                    $.alert({
                        title: "Sucesso",
                        content: resposta,
                        onClose: function() {
                            $('#modalWbsDictionary').modal('hide');
                        }
                    });
                },
                error: function(resposta) {
                    $.alert({
                        title: "Erro",
                        content: "Algo deu errado"
                    });
                }
            });
        },

        copyFromTemplate: function () {
            $.ajax({
                method: 'POST',
                url: "?m=dotproject_plus",
                data: $("#copy_project_form").serialize(),
                success: function(resposta) {
                    var resp = JSON.parse(resposta);
                    if (resp.err) {
                        $.alert({
                            title: "Erro",
                            content: resp.msg
                        });
                    } else {
                        $.alert({
                            title: "Sucesso",
                            content: resp.msg,
                            onClose: function() {
                            window.location.reload(true);
                            }
                        });
                    }
                },
                error: function(resposta) {
                    $.alert({
                        title: "Erro",
                        content: "Algo deu errado"
                    });
                }
            });
        },

        openMinutesModal: function () {
            $.ajax({
                type: "get",
                url: "?m=timeplanning&template=view/projects_estimations_minutes&project_id="+main.projectId
            }).done(function(response) {
                $('#btnSaveMinute').hide();
                $('#btnBackList').hide();
                $("#minutesList").html(response);
                $('#minutesModal').modal();
            });
        },

        showMinutesForm: function (id) {
            var urlSufix = "";
            if (id) {
                urlSufix = "&minute_id="+id;
            }

            $.ajax({
                type: "get",
                url: "?m=timeplanning&template=view/project_minute_form&project_id="+main.projectId+urlSufix
            }).done(function(response) {
                $('#btnSaveMinute').show();
                $('#btnBackList').show();
                $("#minutesList").hide();
                var form = $('#minutesForm').html(response);

                var members = $('#minuteMembersHidden').val();
                if (members) {
                    $('#selectMembers').val(JSON.parse(members)).trigger('change');
                }

                form.show();
            });
        },

        saveMinute: function () {
            var date = $('#date').val();
            var description = $('#description_edit').val();
            var msg = [];
            var err = false;
            if (!date) {
                err = true;
                msg.push('Favor informar a data da reunião');
            }
            if (!description) {
                err = true;
                msg.push('Favor informar a descrição da reunião');
            }

            if (err) {
                $.alert({
                    title: "Error",
                    content: msg.join('<br>')
                });
                return;
            }

            var dateFormatted = $('#date_edit').val();

            $.ajax({
                url: "?m=timeplanning",
                type: "post",
                datatype: "json",
                data: $("form[name=minute_form]").serialize(),
                success: function(resposta) {
                    resposta = JSON.parse(resposta);
                    var id = resposta.newMinuteId;
                    $.alert({
                        title: "Sucesso",
                        content: resposta.msg,
                        onClose: function() {

                            var btnDelete = '<button type="button" class="btn btn-sm btn-danger" onclick="main.deleteMinute('+id+')" title="Remover ata">' +
                                    '<i class="far fa-trash-alt"></i>' +
                                '</button>';
                            var btnEdit = '<button type="button" class="btn btn-sm btn-secondary" onclick="main.showMinutesForm('+id+')" title="Alterar ata">' +
                                    '<i class="far fa-edit"></i>' +
                                '</button>';

                             var cells = '<td>'
                                .concat(dateFormatted)
                                .concat('</td>')
                                .concat('<td>')
                                .concat(description)
                                .concat('</td>')
                                .concat('<td>')
                                .concat(btnDelete)
                                .concat(btnEdit)
                                .concat('</td>');

                            var tableLine = $('#tableMinutes_line_'+id);
                            var newLine = false;
                            if (!tableLine.html()) {
                                newLine = true;
                                tableLine = $('<tr id="tableMinutes_line_'+id+'"></tr>');
                            }
                            tableLine.html('').html(cells);
                            if (newLine) {
                                $('#minutesTableBody').append(tableLine[0]);
                            }
                            main.backMinuteList();
                        }
                    });
                },
                error: function(resposta) {
                    $.alert({
                        title: "Error",
                        content: "Algo deu errado"
                    });
                }
            });
        },

        backMinuteList: function () {
            $('#btnSaveMinute').hide();
            $('#btnBackList').hide();
            $("#minutesForm").hide();
            $("#minutesList").show();
        },

        deleteMinute: function (id) {
            $.confirm({
                title: 'Excluir ata',
                content: 'Você tem certeza de que quer excluir a Ata?',
                buttons: {
                    yes: {
                        text: main.btnYes,
                        action: function () {
                            $.ajax({
                                method: 'POST',
                                url: "?m=timeplanning",
                                data: {
                                    dosql: 'do_projects_estimations_aed',
                                    minute_id: id,
                                    action_estimation: 'delete'
                                }
                            }).done(function(resposta) {
                                $.alert({
                                    title: "Sucesso",
                                    content: resposta,
                                    onClose: function() {
                                        $('#tableMinutes_line_'+id).remove();
                                    }
                                });
                            });
                        },
                    },
                    no: {
                        text: main.btnNo
                    }
                }
            });
        },

        initDragAndDrop: function () {
            $( ".draggable" ).draggable({
                axis: "y",
                revert: true,
//                zIndex: 1000,
                handle: "i",
                snap: true,
                refreshPositions: true,
                stack: '.draggable'
            });

            $( ".droppable" ).droppable({
                activeClass: 'draggable-active',
                hoverClass: 'daggable-over',
                over: function (event, ui) {
//                    $(this).removeClass('daggable-over');
//                    $(this).find('.alert').addClass('daggable-over');
//                    $(this).find('.collapse').collapse('toggle');

                    var taskName = $(this).find('h6').html();
                    var predecessoraName = ui.draggable.find('h6').html();
                    $(this).tooltip({
                        title: 'Adicionar ' + predecessoraName + ' como predecessora de ' + taskName,
                        trigger: 'manual'
                    });
                    $(this).tooltip('show');
                },
                out: function (event, ui) {
                    $(this).tooltip('dispose');
//                    $(this).find('.collapse').collapse('hide');
//                    $(this).find('.alert').removeClass('daggable-over');
                },
                drop: function(event, ui) {
                    var card = $(this);
//                    card.find('.alert').removeClass('daggable-over');
                    card.tooltip('dispose');
                    var taskId = $(this).attr('data');
                    var dependencyId = ui.draggable.attr('data');
                    var projectId = $('#projectIdHidden').val();
                    var predecessoraName = ui.draggable.find('h6').html();
                    $.ajax({
                        method: 'POST',
                        url: "?m=timeplanning",
                        data: {
                            dosql: 'do_project_activity_add_dependency',
                            project_id: projectId,
                            activity_id: taskId,
                            dependency_id: dependencyId
                        }
                    }).done(function(resposta) {
                        $.alert({
                            title: "Sucesso",
                            content: resposta,
                            onClose: function() {
                                var li = $('<li id="'+dependencyId+'_'+taskId+'"></li>');
                                li.html(predecessoraName+' ');
                                var icon = $('<i class="far fa-trash-alt"></i>');
                                var btn = $('<btn type="button" class="btn btn-danger btn-xs" onclick="tasks.deleteDependency('+dependencyId+','+taskId+')"></button>');
                                btn.html(icon[0]);
                                li.append(btn[0]);
                                var ul = card.find('ul');
                                ul.append(li[0]);
                                ul.parent().show();
                            }
                        });
                    });
                }
            });
        }
    };


    var wbs = {
        msgDelete: "<?php echo $AppUI->_("LBL_MENU_DELETE_WBS_ITEM", UI_OUTPUT_JS); ?>",

        delete: function(projectId, itemId, itemName) {
            $.confirm({
                title: wbs.msgDelete,
                content: '<?=$AppUI->_(LBL_CONFIRM_WBS_ITEM_EXCLUSION, UI_OUTPUT_JS)?>',
                buttons: {
                    yes: {
                        text: main.btnYes,
                        action: function () {
                            $.ajax({
                                method: 'POST',
                                url: "?m=dotproject_plus",
                                data: {
                                    dosql: 'do_delete_wbs_item',
                                    project_id: projectId,
                                    wbs_item_id: itemId,
                                    wbs_item_name: itemName
                                }
                            }).done(function(resposta) {
                                $.alert({
                                    title: "Sucesso",
                                    content: resposta,
                                    onClose: function() {
                                        window.location.reload(true);
                                    }
                                });
                            });
                        },
                    },
                    no: {
                        text: main.btnNo
                    }
                }
            });
        },

        new: function(projectId, parentNumber, parentId) {
            $('#wbsProjectId').val(projectId);
            $('#wbsParentNumber').val(parentNumber);
            $('#wbsParentId').val(parentId);

            var modal = $('#newWBSItemModal');
            modal.find('.modal-title').html('<?=$AppUI->_("LBL_MENU_NEW_WBS_ITEM")?>');
            modal.modal();
        },

        update: function (projectId, itemNumber, itemId, description, size, sizeUnit, isLeaf) {
            $('#wbsProjectId').val(projectId);
            $('#wbsItemId').val(itemId);
            $('#wbsNumber').val(itemNumber);
            $('#wbsIsLeaf').val(isLeaf);
            $('input[name=wbs_item_description]').val(description);
            $('input[name=wbs_item_size]').val(size);
            $('input[name=wbs_item_size_unit]').val(sizeUnit);

            var modal = $('#newWBSItemModal');
            modal.find('.modal-title').html('<?=$AppUI->_("LBL_MENU_EDIT_WBS_ITEM")?>');
            modal.modal();
        },

        save: function () {
            var description = $('input[name=wbs_item_description]').val();

            var err = false;
            var msg = '';
            if (!description.trim()) {
                err = true;
                msg = 'A descrição é obrigatória';
            }
            if (err) {
                $.alert({
                    title: "Error",
                    content: msg
                });
                return;
            }

            // submete o formulário tanto para inclusão como alteração
            $.ajax({
                url: "?m=dotproject_plus",
                type: "post",
                datatype: "json",
                data: $("form[name=wbsForm]").serialize(),
                success: function(resposta) {
                    $.alert({
                        title: "Sucesso",
                        content: resposta,
                        onClose: function() {
                            $("#newWBSItemModal").modal("hide");
                            window.location.reload(true);
                        }
                    });
                },
                error: function(resposta) {
                    $.alert({
                        title: "Error",
                        content: "Algo deu errado"
                    });
                }
            });
        },

    };

    var tasks = {

        loadedLogs: [],

        msgDelete: "<?php echo $AppUI->_("LBL_CONFIRM_ACTIVITY_EXCLUSION", UI_OUTPUT_JS); ?>",

        delete: function(id) {
            $.confirm({
                title: '',
                content: tasks.msgDelete,
                buttons: {
                    yes: {
                        text: main.btnYes,
                        action: function () {
                            $('#formDeleteActivity_'+id).submit();
                        },
                    },
                    no: {
                        text: main.btnNo
                    }
                }
            });
        },

        new: function (wbsItemId) {
            $.ajax({
                type: "get",
                url: "?m=dotproject_plus&template=task_addedit&company_id=<?=$company_id?>&project_id="+main.projectId
            }).done(function(response) {
                var modal = $('#taskModal');
                modal.find('.modal-title').html('Nova atividade');
                $(".task-modal").html(response);
                $('#taskWbsItemId').val(wbsItemId);
                modal.modal();
            });
        },

        edit: function (wbsItemId, taskId) {
            $.ajax({
                type: "get",
                url: "?m=dotproject_plus&template=task_addedit&task_id="+taskId+"&company_id=<?=$company_id?>&project_id="+main.projectId
            }).done(function(response) {
                var modal = $('#taskModal');
                modal.find('.modal-title').html('Alterar atividade');
                $(".task-modal").html(response);
                $('#taskWbsItemId').val(wbsItemId);
                modal.modal();
            });
        },

        save : function() {

            var taskName = $('#taskDescription').val();
            if (!taskName) {
                $.alert({
                    title: "Error",
                    content: 'O nome da atividade é obrigatório'
                });
                return;
            }

            var startDate = $('#planned_start_date_activity').val();
            var endDate = $('#planned_end_date_activity').val();

            if (startDate && endDate) {
                var arrStart = startDate.split('/');
                var arrEnd = endDate.split('/');
                var date1 = new Date(arrStart[2], arrStart[1] - 1, arrStart[0]);
                var date2 = new Date(arrEnd[2], arrEnd[1] - 1, arrEnd[0]);

                if (date2 < date1) {
                    $.alert({
                        title: "Error",
                        content: 'A data de fim deve ser posterior a data da início'
                    });
                    return;
                }
            }

            var rolesHr = [];
            var rows = $('.resources-container').find('.row');

            var control = 0;
            for (var i = 0; i < rows.length; i++) {
                var row = rows[i];
                var obj = {};
                var roleId = $(row.children[control]).find('select').val();
                var hrId = $(row.children[control+1]).find('select').val();
                if (roleId) {
                    obj.role = roleId;
                    obj.hr = hrId || null;
                    rolesHr.push(obj);
                }
            }

            $('#rolesHrHidden').val(JSON.stringify(rolesHr));
            $.ajax({
                method: 'POST',
                url: "?m=dotproject_plus",
                data: $("form[name=taskForm]").serialize(),
                success: function(resposta) {
                    $.alert({
                        title: "Sucesso",
                        content: resposta,
                        onClose: function() {
                            window.location.reload(true);
                        }
                    });
                },
                error: function(resposta) {
                    $.alert({
                        title: "Error",
                        content: "Algo deu errado"
                    });
                }
            });
        },

        newLog: function (taskId) {
            $.ajax({
                type: "get",
                url: "?m=tasks&template=vw_log_update&task_id="+taskId
            }).done(function(response) {
                $(".task-log").html(response);
                $("#taskLogModal").modal();
            });
        },

        saveLog: function () {
            updateEmailContacts();
            $.ajax({
                method: 'POST',
                url: "?m=tasks",
                data: $("form[name=editFrm]").serialize(),
                success: function(resposta) {
                    $.alert({
                        title: "Sucesso",
                        content: resposta,
                        onClose: function() {
                            window.location.reload(true);
                        }
                    });
                },
                error: function(resposta) {
                    $.alert({
                        title: "Error",
                        content: "Algo deu errado"
                    });
                }
            });
        },

        deleteLog: function (logId) {
            $.confirm({
                title: '',
                content: '<?=$AppUI->_("doDelete", UI_OUTPUT_JS)." ".$AppUI->_("Task Log", UI_OUTPUT_JS)."?"?>',
                buttons: {
                    yes: {
                        text: main.btnYes,
                        action: function () {

                             $.ajax({
                                 method: 'POST',
                                 url: "?m=tasks",
                                 data: {
                                     task_log_id: logId,
                                     dosql: 'do_updatetask',
                                     del: 1
                                 },
                                 success: function(resposta) {
                                     $.alert({
                                         title: "Sucesso",
                                         content: resposta,
                                         onClose: function() {
                                             $('#table_log_'+logId).remove();
                                         }
                                     });
                                 },
                                 error: function(resposta) {
                                     $.alert({
                                         title: "Erro",
                                         content: "Algo deu errado"
                                     });
                                 }
                             });
                        },
                    },
                    no: {
                        text: main.btnNo
                    }
                }
            });
        },

        loadLogs: function (taskId) {
            if (tasks.loadedLogs.includes(taskId)) {
                return;
            }

            var element = $("#activity-log-"+taskId);
            element.loading({
                message: 'Carregando logs...'
            });
            $.ajax({
                type: "get",
                url: "?m=tasks&template=vw_logs&task_id="+taskId
            }).done(function(response) {
                tasks.loadedLogs.push(taskId);
                element.html(response);
                element.loading('stop');
            });
        },

        editLog: function (taskId, logId) {
            $.ajax({
                type: "get",
                url: "?m=tasks&template=vw_log_update&task_id="+taskId+"&task_log_id="+logId
            }).done(function(response) {
                $(".task-log").html(response);

                $("#taskLogModal").modal();
            });
        },
        
        deleteDependency: function (dependencyId, taskId) {
            var liId = dependencyId + '_' + taskId;
            var li = $('#'+liId);
            var ul = li.parent();

            $.confirm({
                title: '',
                content: 'Tem certeza de que deseja apagar esta dependência?',
                buttons: {
                    yes: {
                        text: main.btnYes,
                        action: function () {
                            $.ajax({
                                method: 'POST',
                                url: "?m=timeplanning",
                                data: {
                                    dosql: 'do_project_activity_exclude_dependency',
                                    project_id: main.projectId,
                                    activity_id: taskId,
                                    dependency_id: dependencyId
                                },
                                success: function(resposta) {
                                    li.remove();
                                    if (!ul.children().length) {
                                        ul.parent().hide();
                                    }

                                    $.alert({
                                        title: "Sucesso",
                                        content: resposta,
                                        onClose: function() {}
                                    });
                                },
                                error: function(resposta) {
                                    $.alert({
                                        title: "Erro",
                                        content: "Algo deu errado"
                                    });
                                }
                            });
                        },
                    },
                    no: {
                        text: main.btnNo
                    }
                }
            });
        }
    }

    $(document).ready(main.init);
</script>