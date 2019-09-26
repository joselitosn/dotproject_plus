    <?php
    $q->clear();
    $q->addQuery('project_start_date,project_end_date');
    $q->addTable('projects');
    $q->addWhere("project_id = '$projectSelected'");
    $datesProject = & $q->exec();

    $meses = diferencaMeses(substr($datesProject->fields['project_start_date'], 0, -9), substr($datesProject->fields['project_end_date'], 0, -9),1);
    $monthStartProject = substr($datesProject->fields['project_start_date'], 5, -12);
    $monthEndProject = substr($datesProject->fields['project_end_date'], 5, -12);
    $monthSProject = substr($datesProject->fields['project_start_date'], 5, -12);
    $yearStartProject = substr($datesProject->fields['project_start_date'], 0, -15);
    $yearEndProject = substr($datesProject->fields['project_end_date'], 0, -15);
    $years = $yearEndProject - $yearStartProject;
    $tempYear = $yearStartProject;
 
    //$tempMeses is the quantity of months within a year (the first year in this case). It is update for each year
    //$meses is the absolut quantity of months
    if ($years == 0) {
        //$tempMeses = $monthEndProject - $monthStartProject;
        $tempMeses=0;
        for ($i = 0; $i <= $meses; $i++) {
            $tempMeses++;
        }
    } else {
        $tempMeses = (12 - $monthStartProject) + 1;
    }
   
    
    
    $sumColumns;
    $c = 0;
    $counter = 1;
    //set an array with the index for each month/year
    $monthsYearsIndex = array();
    $index_month = $monthStartProject;
    $index_year = $yearStartProject;
    for ($i = 0; $i <= $meses; $i++) {
        $monthPrefix=strlen($index_month) < 2 ? "0" : "";
        $monthsYearsIndex[$index_year . "_" .$monthPrefix .$index_month]=$i;
        if ($index_month == 12){
            $index_month = 0;
            $index_year++;
        }
        $index_month++;
    }
   
    ?>
    
    <tr>
        <th nowrap='nowrap'></th>
        <?php
        for ($i = 0; $i <= $years; $i++) {
            ?>
            <th nowrap="nowrap" colspan="<?php echo $tempMeses + 2?>">
                <?php echo $tempYear; ?>
            </th>
            <?php
            $tempMeses = ($meses - $tempMeses) + 1;
            $ns = $tempMeses - 12;
            if ($ns > 0)
                $tempMeses = 12;
            $tempYear++;
        }
        ?>
    </tr>