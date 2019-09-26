<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <title>dotProject+</title>
        <meta http-equiv="Content-Type" content="text/html;charset=<?php echo isset($locale_char_set) ? $locale_char_set : 'UTF-8'; ?>" />
        <meta http-equiv="Pragma" content="no-cache" />
        <meta name="Version" content="<?php echo @$AppUI->getVersion(); ?>" />
        <link rel="stylesheet" type="text/css" href="./style/<?php echo $uistyle; ?>/bootstrap/bootstrap.css" media="all" />
        <link rel="stylesheet" type="text/css" href="./style/<?php echo $uistyle; ?>/login.css" media="all" />
        <script type="text/javascript" src="./style/<?php echo $uistyle; ?>/jquery/jquery-3.3.1.min.js"></script>
        <link rel="shortcut icon" href="./style/<?php echo $uistyle; ?>/img/favicon.ico" type="image/ico" />
    </head>
    <body>
        <nav class="navbar bg-dark navbar-dark">
            <a class="navbar-brand" href="#">
                <img src="style/<?php echo $uistyle; ?>/img/logo.png" class="logo" />
            </a>
            <ul class="nav navbar-nav float-right">
                <li>
                    <a class="" href="#">
                        <img src="style/<?php echo $uistyle; ?>/img/brasao_UFSC_horizontal.png" class="logo" />
                    </a>
                </li>
            </ul>
        </nav>
        <div class="container">
            <div class="row justify-content-md-center">
                <div class="col col-md-5 login-box">
                    <span class="img-container">
                        <img src="style/<?php echo $uistyle; ?>/img/logo-gqs.png" height="60px" />
                    </span>
                    <hr>
                    <?php
                        $err = $AppUI->getMsg();
                        if ($err != '') {
                            ?>
                            <div class="alert alert-danger" role="alert">
                                <?=$err?>
                            </div>
                            <?php
                        }
                    ?>

                    <form method="post" action="<?php echo $loginFromPage; ?>" name="loginform">
                        <input type="hidden" name="login" value="login" />
                        <input type="hidden" name="lostpass" value="0" />
                        <input type="hidden" name="redirect" value="<?php echo $redirect; ?>" />
                        <div class="form-group">
                            <label for="username"><?=$AppUI->_('Username')?></label>
                            <input type="text" maxlength="20" name="username" class="form-control form-control-sm" />
                        </div>
                        <div class="form-group">
                            <label for="username"><?=$AppUI->_('Password')?></label>
                            <input type="password" maxlength="32" name="password" class="form-control form-control-sm" />
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-sm btn-primary"><?=$AppUI->_('LBL_LOGIN')?></button>
                        </div>
                        <div class="form-group">
                            <a href="#" onclick="f = document.loginform;f.lostpass.value = 1;f.submit();">
                                <?=$AppUI->_('forgotPassword')?>
                            </a>
                        </div>
                        <small><?php echo "* " . $AppUI->_("You must have cookies enabled in your browser"); ?></small>
                    </form>
                    <br>
                    <div class="row">
                        <div class="col-md-12 text-center">
                            <?php if (@$AppUI->getVersion()) { ?>
                                <small>Version <?php echo @$AppUI->getVersion(); ?></small>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>