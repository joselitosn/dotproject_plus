<?php /* PUBLIC $Id: chpwd.php 6149 2012-01-09 11:58:40Z ajdonnison $ */
if (!defined('DP_BASE_DIR')) {
    die('You should not access this file directly.');
}

if (! ($user_id = (int)dPgetParam($_REQUEST, 'user_id', 0))) {
    $user_id = @$AppUI->user_id;
}

// check for a non-zero user id
$response = array('err' => true, 'msg' => 'Usuário não encontrado.');
if ($user_id) {
    $old_pwd = db_escape(trim(dPgetCleanParam($_POST, 'old_pwd', null)));
    $new_pwd1 = db_escape(trim(dPgetCleanParam($_POST, 'new_pwd1', null)));
    $new_pwd2 = db_escape(trim(dPgetCleanParam($_POST, 'new_pwd2', null)));

    // has the change form been posted
    if ($new_pwd1 && $new_pwd2 && $new_pwd1 == $new_pwd2) {
        // check that the old password matches
        $old_md5 = md5($old_pwd);
        $q = new DBQuery;
        $q->addQuery('user_id');
        $q->addTable('users');
        $q->addWhere("user_password='$old_md5' AND user_id=$user_id");
        if ($AppUI->user_type == 1 || $q->loadResult() == $user_id) {
            require_once($AppUI->getModuleClass('admin'));
            $user = new CUser();
            $user->user_id = $user_id;
            $user->user_password = $new_pwd1;

            if (($msg = $user->store())) {
                $AppUI->setMsg($msg, UI_MSG_ERROR);
                $response['err'] = true;
                $response['msg'] = $AppUI->getMsg();
            } else {
                $response['err'] = false;
                $response['msg'] = $AppUI->_('chgpwUpdated');
            }
        } else {
            $response['err'] = true;
            $response['msg'] = $AppUI->_('chgpwWrongPW');
        }
    } else {
        $response['err'] = true;
        $response['msg'] = 'Nova senha não confere com a confirmação da nova senha';
    }
}
echo json_encode($response);
exit();