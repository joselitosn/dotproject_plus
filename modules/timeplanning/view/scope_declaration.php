<?php require_once (DP_BASE_DIR . "/modules/projects/projects.class.php"); ?>

<?php $projectId = dPgetParam($_GET, "project_id", 0); ?>
<form name="form_scope_declaration" id="form_scope_declaration">
    <input name="dosql" type="hidden" value="do_project_scope_declaration" />
    <input name="project_id" type="hidden" id="project_id" value="<?php echo $projectId; ?>" />
    <?php
    $obj = new CProject();
    $obj->load($projectId);
    ?>
    <h6><?=$AppUI->_("LBL_DESCRIPTION")?></h6>
    <div class="form-group">
        <textarea name="scope_declaration" class="form-control input-sm" rows="20"><?=$obj->project_description ?></textarea>
    </div>
</form>

<?php
    exit();
?>