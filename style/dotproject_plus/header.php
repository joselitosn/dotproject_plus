<?php

$dialog = dPgetParam($_GET, 'dialog', 0);
if ($dialog) {
    $page_title = '';
} else {
    $page_title = $dPconfig['page_title'] . '&nbsp;' . $AppUI->getVersion();
}
/*It solve the problems of unformated chars: This lines may be commented weith in the environment dpp is isntaled the EBS items presents strange characters as identation*/
mysql_query("SET NAMES 'utf8'");
mysql_query('SET character_set_connection=utf8');
mysql_query('SET character_set_client=utf8');
mysql_query('SET character_set_results=utf8');
                        
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html;charset=<?php echo isset($locale_char_set) ? $locale_char_set : 'ISO-8859-1'; ?>" />
        <title>dotProject+</title>
        <link rel="stylesheet" type="text/css" href="./style/<?php echo $uistyle; ?>/main.css" media="all" />
        <script type="text/javascript" src="./modules/dotproject_plus/js/jquery-1.12.3.min.js"></script>
        <style type="text/css" media="all">
            @import "./style/<?php echo $uistyle; ?>/main.css";
        </style>
        <link rel="shortcut icon" href="./style/<?php echo $uistyle; ?>/img/favicon.ico" type="image/ico" />
        <style type="text/css" media="all">@import "./style/<?php echo $uistyle; ?>/dropdown_menu.css";</style>
<?php //@$AppUI->loadJS(); ?><!-- This line was comment cause the included js files were not been found -->
<?php include("./style/" . $uistyle . "/color.php"); ?>
        <style>
            .label_dpp{
                text-align: left;
                font-weight: bold;
            }
        </style>
        <script>
            //set user language to be globaly acessible
            if ("<?php echo $AppUI->user_prefs["LOCALE"]; ?>" == "pt_br"){
                var languageCode = "pt-br";
            }else{
                var languageCode = "en";
            }
        </script>
    </head>
    <body>
        <?php
        if (!$dialog) {
            $nav = $AppUI->getMenuModules();
            $perms = & $AppUI->acl();
            ?>
            <style type="text/css" media="all"> 
                #container_content { top:160px; min-width:950px; } 
            </style>
            <div id="container_login">

                <div class="content" style="text-align: center;height: 35px" class="banner" align="left">
                    <span>
                        <!-- dotProject+ -->
                        <?php for($k=0;$k<20;$k++){echo "&nbsp;";} ?>
                        <img src="style/<?php echo $uistyle; ?>/img/dotproject_plus_logo_header.png" style="width: 150px;height: 40px" />
                    </span>
                    
                    <span style="float:right;color:#000000;margin-right: 50px; vertical-align: middle" >

                       
                        <span id='cssmenu'style="display: inline-block;vertical-align: top" >
                            <ul>
                                <li class='active has-sub'>
                                    <br/>
                            
                                    <u style="cursor:pointer"><?php //echo $AppUI->LOCALE;?> <?php echo $AppUI->user_first_name . ' ' . $AppUI->user_last_name; ?> <img style="vertical-align: bottom" src="./style/<?php echo $uistyle; ?>/img/arrow_down.png" /></u>

                                    <ul>
                                        <li>

                                            <a href="./index.php?m=admin&amp;a=viewuser&amp;user_id=<?php echo $AppUI->user_id ?>">
                                                <img src="./style/<?php echo $uistyle; ?>/img/icon_my_data.png" />&nbsp;
                                                <?php echo $AppUI->_("LBL_MY_DATA")  ?>
                                            </a>
                                        </li>


                                        <li>                               
                                            <a href="./index.php?m=calendar&amp;a=day_view&amp;date=<?php echo $date = date('Ymd', time()); ?>">
                                                <img src="./style/<?php echo $uistyle; ?>/img/icon_schedule.png" />&nbsp;
                                               <?php echo $AppUI->_("LBL_MY_SCHEDULE")  ?>
                                            </a>
                                        </li>
                                        
                                        <li>                               
                                            <a href="https://docs.google.com/forms/d/1ZaJAQrIOI93_wlFkIdJZbEq2uQiISas2w4aE72Qd5q4/viewform" target="_blank">
                                               <img src="./style/<?php echo $uistyle; ?>/img/bug.png" />&nbsp;
                                               <?php echo $AppUI->_("LBL_BUG_REPORT")  ?>
                                            </a>
                                        </li>
                                        
                                        
                                        
                                        <li class='last'>
                                            <a href="?logout=-1">
                                                <img src="./style/<?php echo $uistyle; ?>/img/icon_logout.png" />&nbsp;
                                                <?php echo $AppUI->_("LBL_EXIT")  ?>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </span>
                
                       <span id="feedback_area">
                           <!-- Comment the line below to disable feedback module -->
                           <?php require_once (DP_BASE_DIR . "/modules/dotproject_plus/feedback/feedback_area.php"); ?>
                        </span>  
                        &nbsp;&nbsp;&nbsp;
                         <a href="http://www.gqs.ufsc.br/evolution-of-dotproject/"  target="blank">
                            <img style="cursor:pointer" src="./style/<?php echo $uistyle; ?>/img/icon_help.png"  /> 
                        </a>
                        
                    </span>
                    
                </div>

                <!--
                
                
                    <div class="content"> <?php echo $AppUI->_('Welcome'); ?> <strong><?php echo $AppUI->user_first_name . "&nbsp;" . $AppUI->user_last_name; ?></strong> &bull; <?php echo dPcontextHelp('Help'); ?> &bull; <a href="./index.php?m=admin&a=viewuser&user_id=<?php echo $AppUI->user_id; ?>"><?php echo $AppUI->_('My Info'); ?></a> &bull;
    <?php if ($perms->checkModule('calendar', 'access')) {
        $now = new CDate();
        ?>
                                    <b><a href="./index.php?m=tasks&a=todo"><?php echo $AppUI->_('Todo'); ?></a></b> &bull; <a href="./index.php?m=calendar&a=day_view&date=<?php echo $now->format(FMT_TIMESTAMP_DATE); ?>"><?php echo $AppUI->_('Today'); ?></a>
    <?php } ?>
                            &bull; <a href="./index.php?logout=-1"><?php echo $AppUI->_('Logout'); ?></a>
                    </div>
                -->
            </div>
            <div id="container_header">
                <ul>
                    <?php
                    foreach ($nav as $module) {
                        if ($perms->checkModule($module['mod_directory'], 'access')) {
                            if (isset($_REQUEST["m"]) and ( $_REQUEST["m"] == $module['mod_directory'])) {
                                ?>
                                <li class="open" onclick="javascript:window.open('?m=<?php echo $module['mod_directory']; ?>', '_self')" style="cursor:pointer">
                                    <div class="content" style="color: #FFCC00"><?php echo $AppUI->_($module['mod_ui_name']); ?></div>
                                </li>
            <?php } else { ?>
                                <li onclick="javascript:window.open('?m=<?php echo $module['mod_directory']; ?>', '_self')">
                                    <div class="content"><?php echo $AppUI->_($module['mod_ui_name']); ?></div>
                                </li>
            <?php
            }
        }
    }
    ?>
                </ul>
            </div>
            <div id="container_new_item">
            </div>

            <!-- Aplication message panel -->
                            <?php
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

            <!-- end message panel -->

            <div id="container_company_name" style="display:none">
                <div class="company_name"><a href="<?php echo $dPconfig['base_url'] ?>"><?php echo $dPconfig['company_name'] ?></a></div>
            </div>    
            <br /><br />
            <div id="container_content">
                <table cellpadding="0" cellspacing="0" border="0" width="100%">
                    <tr>
                        <td><img src="style/<?php echo $uistyle; ?>/img/tl.png" /></td>
                        <td bgcolor="#ffffff" height="8px"></td>
                        <td><img src="style/<?php echo $uistyle; ?>/img/tr.png" /></td>
                    </tr>
                    <tr>
                        <td bgcolor="#ffffff" width="8px"></td>
                        <td bgcolor="#ffffff"><?php } else { ?>
                            <style type="text/css" media="all"> 
                                body { background-image:none; background-color:#FFF; } 
                            </style>
<?php } ?>
                        <div class="content">