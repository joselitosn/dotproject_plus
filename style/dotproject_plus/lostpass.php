<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<title><?php echo @dPgetConfig( 'page_title' );?></title>
<meta http-equiv="Content-Type" content="text/html;charset=<?php echo isset( $locale_char_set ) ? $locale_char_set : 'UTF-8';?>" />
<title><?php echo $dPconfig['company_name'];?>:: dotProject Login</title>
<meta http-equiv="Pragma" content="no-cache" />
<meta name="Version" content="<?php echo @$AppUI->getVersion();?>" />
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

                    <form method="post" action="<?php echo $loginFromPage; ?>" name="lostpassform">
						<input type="hidden" name="lostpass" value="1" />
						<input type="hidden" name="sendpass" value="end password" />
						<input type="hidden" name="redirect" value="<?php echo $redirect;?>" />
                        <div class="form-group">
                            <label for="username"><?=$AppUI->_('Username')?></label>
                            <input type="text" maxlength="20" name="checkusername" class="form-control form-control-sm" />
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" maxlength="64" name="checkemail" class="form-control form-control-sm" />
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-sm btn-primary"><?=$AppUI->_('LBL_SEND_PASSWORD')?></button>
                        </div>
                    </form>
                    
                </div>
            </div>
        </div>
<!-- 
















<form method="post" name="lostpassform">
	<table cellpadding="0" cellspacing="0" border="0" width="300px">
	<tr>
		<td><img src="style/<?php echo $uistyle;?>/img/tl.png" /></td>
		<td bgcolor="#ffffff" height="8px"></td>
		<td><img src="style/<?php echo $uistyle;?>/img/tr.png" /></td>
	</tr>
	<tr>
		<td bgcolor="#ffffff" width="8px"></td>
		<td bgcolor="#ffffff"><table align="center" border="0" width="250" cellpadding="6" cellspacing="0" class="std">
				<input type="hidden" name="lostpass" value="1" />
				<input type="hidden" name="redirect" value="<?php echo $redirect;?>" />
				<tr>
					<th colspan="2"><em><?php echo $dPconfig['company_name'];?></em></th>
				</tr>
				<tr>
					<td align="right" nowrap><?php echo $AppUI->_('Username');?>:</td>
					<td align="left" nowrap><input type="text" size="25" maxlength="20" name="checkusername" class="text" /></td>
				</tr>
				<tr>
					<td align="right" nowrap><?php echo $AppUI->_('EMail');?>:</td>
					<td align="left" nowrap><input type="email" size="25" maxlength="64" name="checkemail" class="text" /></td>
				</tr>
				<tr>
					<td align="right" valign="bottom" nowrap colspan="2"><input type="submit" name="sendpass" value="<?php echo $AppUI->_('send password');?>" class="button" /></td>
				</tr>
			</table>
			<?php if (@$AppUI->getVersion()) { ?>
			<div align="center"> <span style="font-size:7pt">Version <?php echo @$AppUI->getVersion();?></span> </div>
			<?php } ?>
</form>
<div align="center"><?php echo '<span class="error">'.$AppUI->getMsg().'</span>'; $msg = ''; $msg .= phpversion() < '4.1' ? '<br /><span class="warning">WARNING: dotproject is NOT SUPPORT for this PHP Version ('.phpversion().')</span>' : ''; $msg .= function_exists( 'mysql_pconnect' ) ? '': '<br /><span class="warning">WARNING: PHP may not be compiled with MySQL support.  This will prevent proper operation of dotProject.  Please check you system setup.</span>'; echo $msg; ?></div>
</td>
<td bgcolor="#FFFFFF" width="8px"></td>
</tr>
<tr>
	<td><img src="style/<?php echo $uistyle;?>/img/bl.png" /></td>
	<td bgcolor="#FFFFFF"></td>
	<td><img src="style/<?php echo $uistyle;?>/img/br.png" /></td>
</tr>
</table> -->
</body>
</html>