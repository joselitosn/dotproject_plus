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
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">

        <title>dotProject+</title>
        <meta name="description" content="The HTML5 Herald">

        <link rel="stylesheet" type="text/css" href="./style/<?php echo $uistyle; ?>/fontawesome/css/all.css" media="all" />
        <link rel="stylesheet" type="text/css" href="./style/<?php echo $uistyle; ?>/bootstrap/bootstrap.css" media="all" />
        <link rel="stylesheet" type="text/css" href="./style/<?php echo $uistyle; ?>/jquery-confirm/jquery-confirm.min.css" media="all" />
        <link rel="stylesheet" type="text/css" href="./style/<?php echo $uistyle; ?>/select-2/select2.min.css" media="all" />
        <link rel="stylesheet" type="text/css" href="./style/<?php echo $uistyle; ?>/select-2/select2-bootstrap.min.css" media="all" />
        <link rel="stylesheet" type="text/css" href="./style/<?php echo $uistyle; ?>/jquery/jquery-ui-1.12.1/jquery-ui.min.css" media="all" />
        <link rel="stylesheet" type="text/css" href="./style/<?php echo $uistyle; ?>/jquery-loading/jquery.loading.min.css" media="all" />
        <link rel="stylesheet" type="text/css" href="./style/<?php echo $uistyle; ?>/orgchart/getorgchart.css" />
        <link rel="stylesheet" type="text/css" href="./style/<?php echo $uistyle; ?>/css/main.css" media="all" />
        <link rel="stylesheet" type="text/css" href="./style/<?php echo $uistyle; ?>/css/side-menu.css" media="all" />
        <script type="text/javascript" src="./style/<?php echo $uistyle; ?>/jquery/jquery-3.3.1.min.js"></script>
        <script type="text/javascript" src="./style/<?php echo $uistyle; ?>/jquery/jquery-ui-1.12.1/jquery-ui.min.js"></script>
        <script type="text/javascript" src="./style/<?php echo $uistyle; ?>/bootstrap/bootstrap.bundle.min.js"></script>
        <script type="text/javascript" src="./style/<?php echo $uistyle; ?>/jquery-confirm/jquery-confirm.min.js"></script>
        <script type="text/javascript" src="./style/<?php echo $uistyle; ?>/jquery-mask/jquery.mask.min.js"></script>
        <script type="text/javascript" src="./style/<?php echo $uistyle; ?>/jquery-loading/jquery.loading.min.js"></script>
        <script type="text/javascript" src="./style/<?php echo $uistyle; ?>/orgchart/getorgchart.js"></script>
        <script type="text/javascript" src="./style/<?php echo $uistyle; ?>/select-2/select2.min.js"></script>
        <script type="text/javascript" src="./style/<?php echo $uistyle; ?>/js/moment.js"></script>
        <script>
            //set user language to be globaly acessible
            if ("<?php echo $AppUI->user_prefs["LOCALE"]; ?>" == "pt_br") {
                var languageCode = "pt-br";
            } else {
                var languageCode = "en";
            }

            var header = {

                init: function () {
                    $('#changePasswdModal').on('hidden.bs.modal', function() {
                       var form = $('form[name=formChangePasswd]');
                       if (form) {
                           form[0].reset();
                       }
                    });
                },

                savePasswd: function () {

                    var old_pwd = $('input[name=old_pwd]').val().trim();
                    var new_pwd1 = $('input[name=new_pwd1]').val().trim();
                    var new_pwd2 = $('input[name=new_pwd2]').val().trim();
                    var msg = [];
                    var err = false;
                    if (!old_pwd) {
                        err = true;
                        msg.push('Favor informar a senha atual');
                    }
                    if (!new_pwd1) {
                        err = true;
                        msg.push('Favor informar a nova senha');
                    }
                    if (!new_pwd2) {
                        err = true;
                        msg.push('Favor informar a confirmação da nova senha');
                    }
                    if (old_pwd && new_pwd1 && new_pwd2) {
                        if (new_pwd1.length < <?=dPgetConfig('password_min_len')?>) {
                            err = true;
                            msg.push("<?=$AppUI->_('chgpwValidNew', UI_OUTPUT_JS)?> com no mínimo " + <?=dPgetConfig('password_min_len')?> + ' caracteres');
                        }
                        if (new_pwd1 != new_pwd2) {
                            err = true;
                            msg.push('Nova senha não confere com a confirmação da nova senha');
                        }
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
                        method: 'POST',
                        url: "?m=public",
                        data: $("form[name=formChangePasswd]").serialize(),
                        success: function(resposta) {
                            var resp = JSON.parse(resposta);
                            if (resp.err) {
                                $.alert({
                                    icon: "far fa-times-circle",
                                    type: "red",
                                    title: "Erro",
                                    content: resp.msg
                                });
                            } else {
                                $.alert({
                                    icon: "far fa-check-circle",
                                    type: "green",
                                    title: "Sucesso",
                                    content: resp.msg,
                                    onClose: function() {
                                        $('#changePasswdModal').modal('hide');
                                    }
                                });
                            }
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
                }
            }

            $(document).ready(header.init);
        </script>
    </head>
    <body>
        <nav class="navbar navbar-expand-sm bg-dark navbar-dark" style="position: relative">
            <?php
                if (!$dialog) {
                    $nav = $AppUI->getMenuModules();
                    $perms = & $AppUI->acl();

                    ?>
                    
                    <!-- Brand/logo -->
                    <a class="navbar-brand" href="#">
                        <img src="style/<?php echo $uistyle; ?>/img/logo.png" style="width: 150px;height: 40px" />
                    </a>
                    <!-- Links -->
                    <ul class="navbar-nav">
                    <?php
                    foreach ($nav as $module) {
                        //workaround for instructor admin module (not visible by student accounts):: handling access rights complicated
                        if($module['mod_directory']=="instructor_admin" && strtolower($AppUI->user_first_name) == "grupo" ){
                            continue;//ignore this module and goes to the next iteration
                        }
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
            
            <ul class="navbar-nav" style="position: absolute; right:15px;">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle"
                        style="text-decoration: none" 
                        href="javascript:void(0)" 
                        data-toggle="dropdown">
                        <i class="far fa-bell"></i>
                        <span class="badge badge-danger" id="feedback_count"></span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <?php require_once (DP_BASE_DIR . "/modules/dotproject_plus/feedback/feedback_area.php")?>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="http://www.gqs.ufsc.br/evolution-of-dotproject/" target="_blank">
                        <i class="far fa-question-circle"></i>
                        <?php echo $AppUI->_("Help")  ?>
                    </a>
                </li>
                <!-- Dropdown -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="javascript:void(0)" data-toggle="dropdown">
                        <?=$AppUI->user_first_name . ' ' . $AppUI->user_last_name; ?>
                        <i class="fas fa-caret-down"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right">
<!--                        <a class="dropdown-item" href="./index.php?m=admin&amp;a=viewuser&amp;user_id=--><?php //echo $AppUI->user_id ?><!--">-->
                        <a class="dropdown-item" href="javascript:void(0)" data-toggle="modal" data-target="#changePasswdModal">
                            <i class="far fa-user"></i>
                            <?php echo $AppUI->_("Change User Password")  ?>
                        </a>
                        <?php
                            if (false) {
                        ?>
                        <a class="dropdown-item" href="./index.php?m=calendar&amp;a=day_view&amp;date=<?php echo $date = date('Ymd', time()); ?>">
                            <i class="far fa-calendar-alt"></i>
                            <?php echo $AppUI->_("LBL_MY_SCHEDULE")  ?>
                        </a>
                        <?php
                        }
                        ?>
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
        </nav>
        <!-- MODAL CHANGE PASSWORD -->
        <div id="changePasswdModal" class="modal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"><?=$AppUI->_("Change User Password")?></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="<?=$AppUI->_("LBL_CLOSE")?>">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form name="formChangePasswd">
                            <input type="hidden" name="user_id" value="<?=$AppUI->user_id?>">
                            <input type="hidden" name="dosql" value="do_changePasswd">

                            <div class="form-group">
                                <span class="required"></span>
                                <?=$AppUI->_('requiredField');?>
                            </div>

                            <div class="form-group">
                                <label for="company_name" class="required">
                                    <?php echo $AppUI->_('Current Password'); ?>
                                </label>
                                <input type="password" class="form-control form-control-sm" name="old_pwd" />
                            </div>

                            <div class="form-group">
                                <label for="company_name" class="required">
                                    <?php echo $AppUI->_('New Password'); ?>
                                </label>
                                <input type="password" class="form-control form-control-sm" name="new_pwd1" />
                            </div>

                            <div class="form-group">
                                <label for="company_name" class="required">
                                    <?php echo $AppUI->_('Repeat New Password'); ?>
                                </label>
                                <input type="password" class="form-control form-control-sm" name="new_pwd2" />
                            </div>
                        </form>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal"><?=$AppUI->_("LBL_CLOSE")?></button>
                        <button type="button" class="btn btn-primary btn-sm" onclick="header.savePasswd()"><?=$AppUI->_("LBL_SAVE")?></button>
                    </div>
                </div>
            </div>
        </div>
        

        