<?php
$projectSelected = intval(dPgetParam($_GET, 'project_id'));
$whereProject = ' and risk_project='.$projectSelected;
    
$projectSelected = intval(dPgetParam($_GET, 'project_id'));
$whereProject ='';
if ($projectSelected!=null) {
    $whereProject = ' and risk_project='.$projectSelected;
}
$t = intval(dPgetParam($_GET, 'tab'));

$q = new DBQuery(); 
$q->addQuery('*');
$q->addTable('risks');
$q->addWhere("risk_active = '0' and NOT risk_lessons_learned='' $whereProject");
$activeList = $q->loadList();

$q->clear();
$q->addQuery('*');
$q->addTable('risks');
$q->addWhere("risk_active = '1' and NOT risk_lessons_learned='' $whereProject");
$inactiveList = $q->loadList();
?>
<div class="card inner-card">
    <div class="card-header"><?=$AppUI->_("LBL_ACTIVE_RISKS")?></div>
    <div class="card-body">

        <div class="row">
            <div class="col-md-12">

                <table class="table table-sm table-bordered">
                    <thead class="thead-dark">
                    <tr>
                        <th width="40%"><?php echo $AppUI->_('LBL_RISK_NAME');?></th>
                        <th width="52%"><?php echo $AppUI->_('LBL_LESSONS');?></th>
                        <th width="8%"></th>
                    </tr>
                    </thead>
                    <tboody>
                        <?php
                        foreach ($activeList as $row) {
                            ?>
                            <tr>
                                <td><?php echo $row['risk_name'] ?></td>
                                <td><?php echo $row['risk_lessons_learned'] ?></td>
                                <td>
                                    <button type="button" class="btn btn-xs btn-secondary"
                                            onclick="risks.switchEditLessonLearnt(<?=$row['risk_id'] ?>, <?=$projectSelected?>)">
                                        <i class="far fa-edit"></i>
                                    </button>
                                    <button type="button" class="btn btn-xs btn-danger"
                                            onclick="risks.delete(<?=$row['risk_id'] ?>)">
                                        <i class="far fa-trash-alt"></i>
                                    </button>
                                </td>
                            </tr>
                            <?php
                        }
                        ?>
                    </tboody>
                </table>
            </div>
        </div>
    </div>
</div>

<br>

<div class="card inner-card">
    <div class="card-header"><?=$AppUI->_("LBL_INACTIVE_RISKS")?></div>
    <div class="card-body">

        <div class="row">
            <div class="col-md-12">

                <table class="table table-sm table-bordered">
                    <thead class="thead-dark">
                    <tr>
                        <th width="40%"><?php echo $AppUI->_('LBL_RISK_NAME');?></th>
                        <th width="52%"><?php echo $AppUI->_('LBL_LESSONS');?></th>
                        <th width="8%"></th>
                    </tr>
                    </thead>
                    <tboody>
                        <?php
                        foreach ($inactiveList as $row) {
                            ?>
                            <tr>
                                <td><?php echo $row['risk_name'] ?></td>
                                <td><?php echo $row['risk_lessons_learned'] ?></td>
                                <td>
                                    <button type="button" class="btn btn-xs btn-secondary"
                                            onclick="risks.switchEditLessonLearnt(<?=$row['risk_id'] ?>, <?=$projectSelected?>)">
                                        <i class="far fa-edit"></i>
                                    </button>
                                    <button type="button" class="btn btn-xs btn-danger"
                                            onclick="risks.delete(<?=$row['risk_id'] ?>)">
                                        <i class="far fa-trash-alt"></i>
                                    </button>
                                </td>
                            </tr>
                            <?php
                        }
                        ?>
                    </tboody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php
    exit();
?>