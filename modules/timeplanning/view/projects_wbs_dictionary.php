<?php
if (!defined("DP_BASE_DIR")) {
    die("You should not access this file directly.");
}
require_once (DP_BASE_DIR . "/modules/timeplanning/model/wbs_dictionary_entry.class.php");
require_once (DP_BASE_DIR . "/modules/timeplanning/control/controller_wbs_items.class.php");
$controllerWBSItem = new ControllerWBSItem();
?>
<?php $project_id = dPgetParam($_GET, "project_id", 0); ?>
<form name="form_wbs_dictionary">
    <input name="dosql" type="hidden" value="do_project_wbs_dictionary_aed" />
    <input name="eap_items_ids" id="eap_items_ids" type="hidden" />	
    <input name="project_id" type="hidden" id="project_id" value="<?=$project_id?>" />

    <?php
        $items = $controllerWBSItem->getWBSItems($project_id);
        $firstItem = current($items);
    ?>

    <div class="alert alert-secondary" role="alert">
        <?=$firstItem->getNumber()?>&nbsp;<?=$firstItem->getName()?>
    </div>

    <?php
        foreach ($items as $item) {
            if ($item->getNumber() == 1) {
                continue;
            }
            if ($item->isLeaf() == 1) {
                $obj=new WBSDictionaryEntry();
                $obj->load($item->getId());
            ?>
                <div class="form-group">
                    <span><?=$item->getNumber()?>&nbsp;<?=$item->getName()?></span>
                    <textarea class="form-control form-control-sm" name="wbs_item_dictionaty_entry_<?=$item->getId()?>" rows="3"><?=$obj->getDescription()?></textarea>
                </div>

            <?php
            }
        }
    ?>
</form>

<?php
    exit();
?>