<?php

require_once 'connections.php';

$def3 = $_POST['def3'];

if($def3 != 'XX'){
    $query = "SELECT DISTINCT ODEF_4
              FROM ODEF
              WHERE ODEF_3 = '$def3'";
    
    $result = odbc_exec($connENT, $query);
    
    $opt = FALSE;
    $added = FALSE;
    
    if(odbc_num_rows($result) > 0){
        $select = "<select id='def4'>
                        <option value='XX'>Select</option>";
    
        while(odbc_fetch_row($result)){
            $name = odbc_result($result, "ODEF_4");
            
            if($name != "XX" ){
                $select .= "<option value='" . $name . "'>" . $name . "</option>";
                $added = TRUE;
            }
            
            else{
                $opt = TRUE;
            }
        }
        $select .= "</select>";
    }

    else{
        $select = "query failed";
    }

    if($added == TRUE){
        $output = "Optional &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp" . $select;
    }

    else{
        $output = "";
    }
}
else{
    $output = '';
}


echo $output;

?>