<?php

require_once 'connections.php';

if (isset($_POST['sn'])){
    //$type = $_POST['qciType'];
    $type = $_POST['qtype'];
    $sn = $_POST['sn'];
    
    $user = '';       
    
    if(isset($_SESSION['username'])){
        $user = $_SESSION['username'];
    }
    
    if(is_numeric($sn)){

        //verify

        $query = "SELECT TOP 1 tblTraveler.[Traveler ID]
              FROM [Online Traveler Program Official].dbo.tblTraveler
              WHERE (tblTraveler.[Traveler ID] = '$sn')";
    
        $result = odbc_exec($connOLTP,$query);

        if (odbc_num_rows ( $result ) > 0) {
            
            //sn found

            $insert = "INSERT INTO QCI (QCI_serial, QCI_type)
                        VALUES ('$sn', '$type')";
                
                if (odbc_exec($connENT,$insert)){

                    echo 0; //everything worked

                } else { echo "Failed to add SN.";}
                
        } else { echo "SN not found.";}
    
    } else { echo "There was a problem recognising the SN";}

} else { echo "Post not set."; }

?>