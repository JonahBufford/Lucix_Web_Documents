<?php

require_once 'connections.php';

$def2 = $_POST['def2'];

if($def2 != 'XX'){
    $query = "SELECT DISTINCT ODEF_3
              FROM ODEF
              WHERE ODEF_2 = '$def2'";
    
    $result = odbc_exec($connENT, $query);
    
    $opt = FALSE;
    $added = FALSE;
    
    if(odbc_num_rows($result) > 0){
        $select = "<select id='def3' onchange='makeDef4()'>
                        <option value='XX'>Select</option>";
    
        while(odbc_fetch_row($result)){
            $name = odbc_result($result, "ODEF_3");
            
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
        if($opt == FALSE){
            $output = "Required<input type='hidden' value='0' id='def3opt'> &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp" . $select;
        }
        
        else{
            $output = "Optional<input type='hidden' value='1' id='def3opt'> &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp" . $select;
        }
    }

    else{
        $output = "<input type='hidden' value='2' id='def3opt'>";
    }
}
else{
    $output = '';
}


echo $output;

?>