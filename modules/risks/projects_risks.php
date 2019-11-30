<?php
if (!defined('DP_BASE_DIR')) {
    die('You should not access this file directly.');
}
?>

<h4><?=$AppUI->_("LBL_PROJECT_RISKS",UI_OUTPUT_HTML)?></h4>
<hr>

<?php
    include(DP_BASE_DIR . '/modules/risks/indexProjectView.php');
?>





