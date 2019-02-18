<?php

require_once 'connections.php';

$def1 = $_POST['def1'];

if($def1 != 'XX'){
    $query = "SELECT DISTINCT ODEF_2
            FROM ODEF
            WHERE ODEF_1 = '$def1'";

    $result = odbc_exec($connENT, $query);

    $opt = FALSE;
    $added = FALSE;

    if(odbc_num_rows($result) > 0){
        $select = "<select id='def2' onchange='makeDef3()'>
                        <option value='XX'>Select</option>";

        while(odbc_fetch_row($result)){
            $name = odbc_result($result, "ODEF_2");
            
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
            $output = "Required<input type='hidden' value='0' id='def2opt'> &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp" . $select;
        }

        else{
            $output = "Optional<input type='hidden' value='1' id='def2opt'> &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp" . $select;
        }
    }

    else{
        $output = "<input type='hidden' value='2' id='def2opt'>";
    }
}

else{
    $output = '';
}

echo $output;

?>