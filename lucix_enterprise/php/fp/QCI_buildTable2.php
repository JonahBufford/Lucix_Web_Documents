<?php

session_start ();

//DEBUG
$tcCheck = 'initial';

// $user = $_SESSION ['username'];

$class1 = 'pending';
$class2 = 'pending';
$class3 = 'pending';
$sealType = "";
$sealDate = "";
$program = "";

$typeFilter = "%";
$statusFilter = "%";

require_once 'connections.php';

if (isset($_POST['typef'])){
  $typeFilter = $_POST['typef'];
  $statusFilter = $_POST['staf'];
  $noteFilter = $_POST['notf'];
  $progFilter = $_POST['prof'];
}

$query = "SELECT *
          FROM QCI
          WHERE QCI_type Like '$typeFilter' AND QCI_status NOT LIKE 'Archived'
          ORDER BY QCI_serial DESC";


$result = odbc_exec ( $connENT, $query );

if ($noteFilter == "On"){
  $table = "<table class='table2 tablesorter' id='table2'>
    <thead>
      <tr>
        <th class='sortable'>SN</th>
        <th class='sortable'>WO</th>
        <th class='sortable'>PN</th>
        <th class='sortable'>Description</th>
        <th class='sortable'>Program</th>
        <th class='sortable'>Status</th>
        <th class='sortable'>Seal Type</th>
        <th class='sortable'>Seal Date</th>
        <th class='sortable'>QCI Type</th>
        <th class=''>Step 1</th>
        <th class=''>Step 2</th>
        <th class=''>Step 3</th>
        <th class='noteTag'>Notes</th>
        <th></th>
        </tr>
      </thead>
  ";

} else {

  
  $table = "<table class='table2 tablesorter' id='table2'>
    <thead>
    <tr>
      <th class='sortable'>SN</th>
      <th class='sortable'>WO</th>
      <th class='sortable'>PN</th>
      <th class='sortable'>Description</th>
      <th class='sortable'>Program</th>
      <th class='sortable'>Status</th>
      <th class='sortable'>Seal Type</th>
      <th class='sortable'>Seal Date</th>
      <th class='sortable'>QCI Type</th>
      <th class=''>Step 1</th>
      <th class=''>Step 2</th>
      <th class=''>Step 3</th>
      </tr>
      </thead>
      ";
      
    }
  if (odbc_num_rows ( $result ) > 0) {

    while(odbc_fetch_row($result)){

        $sn = odbc_result($result,'QCI_serial');
        $id = odbc_result($result,'QCI_uid');
        $type = odbc_result($result,'QCI_type');
        $notes = odbc_result($result,'QCI_notes');
        $pn = odbc_result($result,'QCI_pn');
        $status = odbc_result($result,'QCI_status');
        $program = odbc_result($result,'QCI_prog');

        
        $tcDate = "";
        $wo = "";
        $checkIt = "";
        
        //reset variables
        $date1 = $date2 = $date3 = "TBD";
        $class1 = 'pending';
        $class2 = 'pending';
        $class3 = 'pending';
        $sealType = "";
        $sealDate = "";
        
        //set status select box

        $spen = "";
        $spas = "";
        $sfai = "";
        $sinp = "";

        $colorClass = 'staPend';
        
        switch ($status) {
          case "Pending":
              $spen = "selected";
              $colorClass = 'staPend';
              break;
          case "Passed":
              $spas = "selected";
              $colorClass = 'staPass';
              break;
          case "Failed":
              $sfai = "selected";
              $colorClass = 'staFail';
              break;
          case "In Process":
              $sinp = "selected";
              $colorClass = 'staInP';
              break;
          default:  
        }

        
        $staSelect = "<select class='statusSelect' onchange='saveStatus(this, $id)'>
          <option value='Pending' $spen>Pending</option>
          <option value='In Process' $sinp>In Process</option>
          <option value='Passed' $spas>Passed</option>
          <option value='Failed' $sfai>Failed</option>
        ";
        

        if (strcmp($sn,"TBD") < 0){
            
            //this is a live SN, pull the data from the traveler program

            $rowButton = "<button onclick='deselect($id)' class='btn2'>X</button>";

            $snStr = "<a href='oltp.php?snGet=$sn' target='blank' class='snLink'>$sn</a>";

            //Seal Data
            $queryWeld = 
              "SELECT TOP 1 tblOperationStages.OperationDesc, tblUniqueOperationSteps.SignDatetime
              FROM tblOperationStages
              INNER JOIN tblUniqueOperationSteps
              ON tblOperationStages.OperstageID = tblUniqueOperationSteps.OperstageID
              WHERE ((tblOperationStages.OperationDesc Like '%Seam%') AND (tblUniqueOperationSteps.TravelerID='$sn'))
              OR ((tblOperationStages.OperationDesc Like '%Laser%') AND (tblUniqueOperationSteps.TravelerID='$sn'))
              ORDER BY tblUniqueOperationSteps.SignDatetime DESC";
            
            $resultSeal = odbc_exec($connOLTP,$queryWeld);

            if (odbc_num_rows ( $resultSeal ) > 0) {

              //sn found
              while(odbc_fetch_row($resultSeal)){
                  $sealType = odbc_result($resultSeal,'OperationDesc');
                  $sealDate = odbc_result($resultSeal,'SignDatetime');

                  $sealDate = date_create($sealDate);
                  $sealDate = $sealDate->format('m/d/y H:i:s');

                  if(Strpos($sealType, "aser")){
                    $sealType = "Laser";
                  } else {
                    $sealType = "Seam";
                  }
              }
            }


            $queryTop = "SELECT TOP 1 tblTraveler.[Program Name], tblTraveler.[Work Order Number], tblTraveler.[Part Number], tblTraveler.[Part Description], tblTraveler.Status
              FROM [Online Traveler Program Official].dbo.tblTraveler
              WHERE (tblTraveler.[Traveler ID] = '$sn')";
    
            $resultTop = odbc_exec($connOLTP,$queryTop);

            if (odbc_num_rows ( $resultTop ) > 0) {

              //sn found
              while(odbc_fetch_row($resultTop)){
                  $program = odbc_result($resultTop,'Program Name');
                  $wo = odbc_result($resultTop,'Work Order Number');
                  $pn = odbc_result($resultTop,'Part Number');
                  $desc = odbc_result($resultTop,'Part Description');
              }

              //truncate description at second comma
              // $array = preg_split(',', $desc);
              $array = explode(",", $desc);

              $desc =  $array[0] . ", " . $array[1];

              //defaults
              if (strcmp($type,"RGA/DPA") > 0){
                $label1 = "RGA";
                $label2 = "DPA";
                $delay2 = 30;
                $delay3 = 30;
                $step2ID = '6265';
                $step3ID = '6264';
              } else {
                $label1 = "Pre-BI Test";
                $label2 = "Post-BI Test";
                $delay2 = 5;
                $delay3 = 46;
                $step2ID = '6216';
                $step3ID = '6217';
              }

            //check for temp cycle completion
            $queryTC = "SELECT 
              tblOperationStages.OperationDesc, tblUniqueOperationSteps.SignDatetime, tblUniqueOperationSteps.[Sequence 1]        
              FROM tblOperationStages INNER JOIN tblUniqueOperationSteps ON tblOperationStages.OperstageID = tblUniqueOperationSteps.OperstageID
              WHERE  tblUniqueOperationSteps.[Sequence 1]>800 
              AND tblOperationStages.OperstageID=6208 AND tblUniqueOperationSteps.TravelerID='$sn' AND tblUniqueOperationSteps.[Rework Flag] = 0";

            $resultTC = odbc_exec($connOLTP,$queryTC);

            $tcCheck = '1';

            if (odbc_num_rows ( $resultTC ) > 0) {

              while(odbc_fetch_row($resultTC)){

                $tcCheck = '3';

                $tcDate = odbc_result($resultTC,"SignDatetime");
                $tcDate = date_create($tcDate);
                $tcDate = $tcDate->format('m/d/y H:i:s');

                if ($tcDate != ""){

                    //tempcycle complete.  Check second step
                    $class1 = 'complete';

                    $query2nd = "SELECT 
                    tblOperationStages.OperationDesc, tblUniqueOperationSteps.SignDatetime, tblUniqueOperationSteps.[Sequence 1]        
                    FROM tblOperationStages INNER JOIN tblUniqueOperationSteps ON tblOperationStages.OperstageID = tblUniqueOperationSteps.OperstageID
                    WHERE  tblUniqueOperationSteps.[Sequence 1]>800 
                    AND tblOperationStages.OperstageID=$step2ID AND tblUniqueOperationSteps.TravelerID='$sn' AND tblUniqueOperationSteps.[Rework Flag] = 0
                    ORDER BY tblUniqueOperationSteps.[Sequence 1]";
      
                    $result2nd = odbc_exec($connOLTP, $query2nd);
                    
                    if (odbc_num_rows ( $result2nd ) > 0) {
                      
        
                      while(odbc_fetch_row($result2nd)){
        
                        $date2 = odbc_result($result2nd,"SignDatetime");
                        
                      }
                      
                      if ($date2 && ($date2 != "") ){ 
                        
                        //2nd step is complete
                        $date2 = date_create($date2);
                        $date2 = $date2->format('m/d/y H:i:s');
                        $class2 = 'complete';

                        //check 3rd step
                        $query3rd = "SELECT 
                        tblOperationStages.OperationDesc, tblUniqueOperationSteps.SignDatetime, tblUniqueOperationSteps.[Sequence 1]        
                        FROM tblOperationStages INNER JOIN tblUniqueOperationSteps ON tblOperationStages.OperstageID = tblUniqueOperationSteps.OperstageID
                        WHERE  tblUniqueOperationSteps.[Sequence 1]>800 
                        AND tblOperationStages.OperstageID=$step3ID AND tblUniqueOperationSteps.TravelerID='$sn' AND tblUniqueOperationSteps.[Rework Flag] = 0
                        ORDER BY tblUniqueOperationSteps.[Sequence 1]";
          
                        $result3rd = odbc_exec($connOLTP,$query3rd);
            
                        if (odbc_num_rows ( $result3rd ) > 0) {
            
                          while(odbc_fetch_row($result3rd)){
            
                            $date3 = odbc_result($result3rd,"SignDatetime");
                            
                          }
                          
                          if ($date3 != ""){
                            
                            //3rd step is complete
                            $date3 = date_create($date3);
                            $date3 = $date3->format('m/d/y H:i:s');
                            $class3 = 'complete';
                           
                          } else {
    
                            //3rd step not complete, project this step
    
                            $date = date_create($date2);
                            $date3 = date_add($date,date_interval_create_from_date_string("$delay3 days"));
                            $date3 = $date3->format('m/d/y H:i:s');
    
                          }
                        }

                      } else {

                        //2nd step not complete, project this and next step

                        $date = date_create($tcDate);
                        $dateB = date_create($tcDate);
                        $date2 = date_add($date,date_interval_create_from_date_string("$delay2 days"));
                        $date3 = date_add($dateB,date_interval_create_from_date_string("$delay2 days"));
                        $date3 = date_add($date3,date_interval_create_from_date_string("$delay3 days"));
                        $date2 = $date2->format('m/d/y H:i:s');
                        $date3 = $date3->format('m/d/y H:i:s');

                      }

                    }  //end query for step 2

                } else {

                  //TC not complete, chec auth date

                  $query = "SELECT tblUniqueOperationSteps.SignDatetime       
                  FROM tblUniqueOperationSteps
                  WHERE  tblUniqueOperationSteps.[Sequence 1] > 799 
                  AND tblUniqueOperationSteps.OperstageID = 414 AND tblUniqueOperationSteps.TravelerID='$sn'";
    
                  $result2 = odbc_exec($connOLTP,$query);
      
                  if (odbc_num_rows ( $result2 ) > 0) {
      
                    while(odbc_fetch_row($result2)){
      
                      $authDate = odbc_result($result2,"SignDatetime");

                      if ($authDate != ""){

                        //QCI Process started, project all dates
                        $date = date_create($authDate);
                        $tcDate = date_add($date,date_interval_create_from_date_string("3 days"));
                        $date2 = date_add($tcDate,date_interval_create_from_date_string("$delay2 days"));
                        $date3 = date_add($date2,date_interval_create_from_date_string("$delay3 days"));
                        $tcDate = $tcDate->format('m/d/y H:i:s');
                        $date2 = $date2->format('m/d/y H:i:s');
                        $date3 = $date3->format('m/d/y H:i:s');

                      } else {
                        $tcDate = 'QCI Process Not Started';
                      }

                    }
            
                  }

                } //end TC Not Complete

              }

            } else {

              //QCI Steps not yet added
              $tcDate = 'TBD';
              $date2 = 'TBD';
              $date3 = 'TBD';
            }
            
          }

          //INCLUDED IN EACH LOOP FOR EXISTING SNs

          //apply filter status $statusFilter
          if (
               ((strcmp($statusFilter,"%") == 0) || (strcmp($statusFilter,$status) == 0)) && ((strcmp($progFilter,"%") == 0) || (strcmp($progFilter,$program) == 0))
            ) {
            //add row
            
            //format dates for output
            $tcDate = subStr($tcDate,0,8);
            $date2 = subStr($date2,0,8);
            $date3 = subStr($date3,0,8);
            $sealDate = subStr($sealDate,0,8);
            
            if ($noteFilter == "On"){

            $table .= "
            <tr>
                <td>$snStr</td>
                <td>$wo</td>
                <td class='nowrap'>$pn</td>
                <td>$desc</td>
                <td>$program</td>
                <td class='$colorClass'><div class='hidden'>$status</div>$staSelect</td>
                <td>$sealType</td>
                <td>$sealDate</td>
                <td>$type</td>
                <td class = '$class1 nowrap'>Temp Cycle<hr>$tcDate</td>
                <td class = '$class2 nowrap'>$label1<hr>$date2</td>
                <td class = '$class3 nowrap'>$label2<hr>$date3</td>
                <td><textarea id='n$id' name='notes' class='dataInput noteTag'>$notes $checkIt</textarea></td>
                <td class='noteTag'>
                $rowButton
                </td>
              </tr>
                ";

            } else {
              $table .= "
            <tr>
                <td>$snStr</td>
                <td>$wo</td>
                <td class='nowrap'>$pn</td>
                <td>$desc</td>
                <td>$program</td>
                <td class='$colorClass'><div class='hidden'>$status</div>$staSelect</td>
                <td>$sealType</td>
                <td>$sealDate</td>
                <td>$type</td>
                <td class = '$class1 nowrap'>Temp Cycle<hr>$tcDate</td>
                <td class = '$class2 nowrap'>$label1<hr>$date2</td>
                <td class = '$class3 nowrap'>$label2<hr>$date3</td>
              </tr>
                ";
            }
                
          }
          
        } else {

          if ( ((strcmp($statusFilter,"%") == 0) || (strcmp($statusFilter,"Pending") == 0)) && (strcmp($progFilter,"%") == 0) ){
            //add row

            //this is a reserved by item line.  use data from QCI table
            $snStr = "<button onclick='addSN($id)' class='btn2'>Assign SN</button>";
            $rowButton = "<button onclick='deselect($id)' class='btn2'>X</button>";

            if ($noteFilter == "On"){

            $table .= "
              <tr>
                <td>$snStr</td>
                <td></td>
                <td>$pn</td>
                <td></td>
                <td class='inputHolder'><input id='pendPro' value='$program' class='progInput' onchange='saveProg(this, $id)'></td>
                <td class='$colorClass'><div class='hidden'>$status</div>$staSelect</td>
                <td></td>
                <td></td>
                <td>$type</td>
                <td></td>
                <td></td>
                <td></td>
                <td><textarea id='n$id' name='notes' class='dataInput noteTag'>$notes</textarea></td>
                <td class='noteTag'>
                  $rowButton
                </td>
              </tr>
              ";

            }else {
              $table .= "
              <tr>
                <td>$snStr</td>
                <td></td>
                <td>$pn</td>
                <td></td>
                <td class='inputHolder'><input id='pendPro' value='$program' class='progInput' onchange='saveProg(this, $id)'></td>
                <td class='$colorClass'><div class='hidden'>$status</div>$staSelect</td>
                <td></td>
                <td></td>
                <td>$type</td>
                <td></td>
                <td></td>
                <td></td>
              </tr>
              ";
            }
          }
        }
      }
    }

$table .= "</tbody></table>";

echo $table;

?>