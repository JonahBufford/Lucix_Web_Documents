<?php

require_once "fp/connections.php";

$query = "SELECT DISTINCT ODEF_1
          FROM ODEF";

$result = odbc_exec( $connENT, $query);

if(odbc_num_rows($result) > 0){
    $def1 = "<select id='def1' onchange='makeDef2()'>
                <option value='XX'>Select</option>";

    while(odbc_fetch_row($result)){
        $name = odbc_result($result, "ODEF_1");

        $def1 .= "<option value='" . $name . "'>" . $name . "</option>";
    }

    $def1 .= "</select>";
}

echo <<< _FixedHTML

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" type="text/css" href="../css/default.css">
    <link rel="stylesheet" type="text/css" href="../css/FAC_facility.css">

    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css">
    <script src="../js/defaultJS.js"></script>
    <script src="../js/jquery-ui.min.js"></script>
    
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="/resources/demos/style.css">
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

    <title>Lucix Corporation</title>

</head>
<body>
    <script>
        function add() {
            var def1 = $('#def1').val();
            var def2 = 'XX';
            var def3 = 'XX';
            var def4 = 'XX';

            if(document.getElementById('def2div').innerHTML != ''){
                if($('#def2opt').val() != '2'){
                    var def2 = $('#def2').val();
    
                    if(document.getElementById('def3div').innerHTML != ''){
                        if($('#def3opt').val() != '2'){
                            var def3 = $('#def3').val();
                            alert(def3);
        
                            if(document.getElementById('def4div').innerHTML != ''){
                                var def4 = $('#def4').val();
                            }
                        }
                    }
                }
            }

            $.ajax({
                type: 'POST',

                url: 'fp/ODEF_addDef.php',

                dataType: 'html',

                data: {
                    def1: def1,
                    def2: def2,
                    def3: def3,
                    def4: def4
                },

                success: function(html) {
                    alert(html);
                }
            });
        }

        function makeDef2() {
            var def1 = $('#def1').val();

            $.ajax({
                type: 'POST',

                url: 'fp/ODEF_makeDef2.php',

                dataType: 'html',

                data: {
                    def1: def1
                },

                success: function(html) {
                    document.getElementById('def2div').innerHTML = html;

                    document.getElementById('def3div').innerHTML = '';
                    document.getElementById('def4div').innerHTML = '';

                    if( $('#def2opt' ).val() == '1' || $('#def2opt' ).val() == '2'){
                        document.getElementById('add').disabled = false;
                    }

                    else{
                        document.getElementById('add').disabled = true;
                    }
                }
            });
        }

        function makeDef3() {
            var def2 = $('#def2').val();

            $.ajax({
                type: 'POST',

                url: 'fp/ODEF_makeDef3.php',

                dataType: 'html',

                data: {
                    def2: def2
                },

                success: function(html) {
                    document.getElementById('def3div').innerHTML = html;
                    if( $('#def3opt' ).val() == '1' || $('#def3opt' ).val() == '2'){
                        document.getElementById('add').disabled = false;
                    }
                    else{
                        document.getElementById('add').disabled = true;
                    }
                }
            });
        }

        function makeDef4() {
            var def3 = $('#def3').val();

            $.ajax({
                type: 'POST',

                url: 'fp/ODEF_makeDef4.php',

                dataType: 'html',

                data: {
                    def3: def3
                },

                success: function(html) {
                    document.getElementById('def4div').innerHTML = html;
                    document.getElementById('add').disabled = false;
                }
            });
        }

    </script>
    <div>
        Add Defect 
        <br>
        Defect Type &nbsp&nbsp&nbsp&nbsp
        $def1 
        <br>
        <div id='def2div'></div>
        <div id='def3div'></div>
        <div id='def4div'></div>
        Notes<br>
        <textarea id='notes'></textarea><br>
        <button onclick='add()' disabled id='add'>Add</button>
        <button onclick='cancel()' id='cancel'>Cancel</button
    </div>
</body>
</html>

_FixedHTML;

?>