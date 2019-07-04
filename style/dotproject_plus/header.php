<?php

$dialog = dPgetParam($_GET, 'dialog', 0);
/*It solve the problems of unformated chars: This lines may be commented weith in the environment dpp is isntaled the EBS items presents strange characters as identation*/
mysql_query("SET NAMES 'utf8'");
mysql_query('SET character_set_connection=utf8');
mysql_query('SET character_set_client=utf8');
mysql_query('SET character_set_results=utf8');
                        
?>

<!doctype html>

<html lang="en">
    <head>
        <meta charset="utf-8">

        <title>dotProject+</title>
        <meta name="description" content="The HTML5 Herald">

        <link rel="stylesheet" type="text/css" href="./style/<?php echo $uistyle; ?>/fontawesome/css/all.css" media="all" />
        <link rel="stylesheet" type="text/css" href="./style/<?php echo $uistyle; ?>/bootstrap/bootstrap.min.css" media="all" />
        <link rel="stylesheet" type="text/css" href="./style/<?php echo $uistyle; ?>/jquery-confirm/jquery-confirm.min.css" media="all" />
        <link rel="stylesheet" type="text/css" href="./style/<?php echo $uistyle; ?>/select-2/select2.min.css" media="all" />
        <link rel="stylesheet" type="text/css" href="./style/<?php echo $uistyle; ?>/select-2/select2-bootstrap.min.css" media="all" />
        <link rel="stylesheet" type="text/css" href="./style/<?php echo $uistyle; ?>/jquery/jquery-ui-1.12.1/jquery-ui.min.css" media="all" />
        <link rel="stylesheet" type="text/css" href="./style/<?php echo $uistyle; ?>/orgchart/getorgchart.css" />
        <link rel="stylesheet" type="text/css" href="./style/<?php echo $uistyle; ?>/css/main.css" media="all" />
        <link rel="stylesheet" type="text/css" href="./style/<?php echo $uistyle; ?>/css/side-menu.css" media="all" />
        <script type="text/javascript" src="./style/<?php echo $uistyle; ?>/jquery/jquery-3.3.1.min.js"></script>
        <script type="text/javascript" src="./style/<?php echo $uistyle; ?>/bootstrap/bootstrap.bundle.min.js"></script>
        <script type="text/javascript" src="./style/<?php echo $uistyle; ?>/jquery-confirm/jquery-confirm.min.js"></script>
        <script type="text/javascript" src="./style/<?php echo $uistyle; ?>/jquery-mask/jquery.mask.min.js"></script>
        <script type="text/javascript" src="./style/<?php echo $uistyle; ?>/orgchart/getorgchart.js"></script>
        <script type="text/javascript" src="./style/<?php echo $uistyle; ?>/select-2/select2.min.js"></script>
        <script type="text/javascript" src="./style/<?php echo $uistyle; ?>/jquery/jquery-ui-1.12.1/jquery-ui.min.js"></script>
        <script>
            //set user language to be globaly acessible
            if ("<?php echo $AppUI->user_prefs["LOCALE"]; ?>" == "pt_br") {
                var languageCode = "pt-br";
            } else {
                var languageCode = "en";
            }
        </script>
    </head>
    <body>
        <nav class="navbar navbar-expand-sm bg-dark navbar-dark">
            <?php
                if (!$dialog) {
                    $nav = $AppUI->getMenuModules();
                    $perms = & $AppUI->acl();

            ?>
            <!-- Links -->
            <ul class="navbar-nav">
            <?php
                    foreach ($nav as $module) {
                        if ($perms->checkModule($module['mod_directory'], 'access')) {
                            if (isset($_REQUEST["m"]) and ( $_REQUEST["m"] == $module['mod_directory'])) {
            ?>
                                <li class="nav-item">
                                    <a class="nav-link active" href="?m=<?php echo $module['mod_directory']; ?>">
                                        <?php echo $AppUI->_($module['mod_ui_name'])  ?>
                                    </a>
                                </li>
            <?php
                            } else {
            ?>
                                <li class="nav-item">
                                    <a class="nav-link" href="?m=<?php echo $module['mod_directory']; ?>">
                                        <?php echo $AppUI->_($module['mod_ui_name'])  ?>
                                    </a>
                                </li>
            <?php
                            }
                        }
                    }
                }
            ?>
            </ul>
            <!-- Brand/logo -->
            <a class="navbar-brand mx-auto" href="#">
                <img src="style/<?php echo $uistyle; ?>/img/dotproject_plus_logo_header.png" style="width: 150px;height: 40px" />
            </a>
            <ul class="navbar-nav">
                <!-- Dropdown -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="javascript:void(0)" data-toggle="dropdown">
                        <?=$AppUI->user_first_name . ' ' . $AppUI->user_last_name; ?>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <a class="dropdown-item" href="./index.php?m=admin&amp;a=viewuser&amp;user_id=<?php echo $AppUI->user_id ?>">
                            <i class="far fa-user"></i>
                            <?php echo $AppUI->_("LBL_MY_DATA")  ?>
                        </a>
                        <a class="dropdown-item" href="./index.php?m=calendar&amp;a=day_view&amp;date=<?php echo $date = date('Ymd', time()); ?>">
                            <i class="far fa-calendar-alt"></i>
                            <?php echo $AppUI->_("LBL_MY_SCHEDULE")  ?>
                        </a>
                        <a class="dropdown-item" href="https://docs.google.com/forms/d/1ZaJAQrIOI93_wlFkIdJZbEq2uQiISas2w4aE72Qd5q4/viewform" target="_blank">
                            <i class="fas fa-bug"></i>
                            <?php echo $AppUI->_("LBL_BUG_REPORT")  ?>
                        </a>
                        <a class="dropdown-item" href="?logout=-1">
                            <i class="fas fa-sign-out-alt"></i>
                            <?php echo $AppUI->_("LBL_EXIT")  ?>
                        </a>
                    </div>
                </li>
            </ul>
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <i class="far fa-bell"></i>
                    </a>

                    <!-- Comment the line below to disable feedback module -->
                    <?php //require_once (DP_BASE_DIR . "/modules/dotproject_plus/feedback/feedback_area.php"); ?>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="http://www.gqs.ufsc.br/evolution-of-dotproject/"  target="_blank">
                        <i class="far fa-question-circle"></i>
                        <?php echo $AppUI->_("Help")  ?>
                    </a>
                </li>
            </ul>
        </nav>
    <!--    <div class="container-fluid">-->
        <div class="wrapper">
        <!-- Aplication message panel -->
            <?php /*
                $msg_dp = $AppUI->getMsg();
                if(isset($_SESSION["user_feedback_display_message"])){
                    
                    $msg_dp=$_SESSION["user_feedback_display_message"];
                    unset($_SESSION["user_feedback_display_message"]);
                }
            ?>
            <table align="center" width="400" id="app_message_panel" style="border-radius: 10px;border:1px solid silver;background-color: #FFFFFF;padding: 5px 5px;margin-top: 5px;display:<?php echo $msg_dp != "" ? "block" : "none" ?>">
                <tr>
                    <td colspan="3"  style="text-align: center;"  >
                        <span style="text-align: center;" id="app_message_panel_content">
                                        <?php
                                        echo $msg_dp;
                                        ?>
                        </span>
                    </td>
                </tr>
            </table>
            <script>
                //function defined to set messages on the panel. It was setup in this file, to be available to to whole application
                //These threee constants below represents the types of message, and are identical to the image file name.
                var APP_MESSAGE_TYPE_SUCCESS = "sucess";
                var APP_MESSAGE_TYPE_WARNING = "warning";
                var APP_MESSAGE_TYPE_INFO = "info";
                function setAppMessage(message, type) {
                    var app_message_panel = document.getElementById("app_message_panel");
                    var img = "<img src='./style/default/images/icon_" + type + ".png' />";
                    document.getElementById("app_message_panel_content").innerHTML = img + "&nbsp;&nbsp;" + message;
                    app_message_panel.style.display = "block";
                    app_message_panel.scrollIntoView();
                    setTimeout(closeAppMessage, 15000);//close the message panel after x seconds.
                }
                function closeAppMessage() {
                    document.getElementById("app_message_panel").style.display = "none";
                }
                
                
                //functions necessary for general system navegation
                function replaceAll(find, replace, str) {
                    return str.replace(new RegExp(find, 'g'), replace);
                }
                
                
                
            </script>
            */
            ?>
            <!-- end message panel -->
