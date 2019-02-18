<?php

session_start ();

// define variables for common files
$title = "Lucix Facility Info";

// import common files
require_once 'fp/iconData.php';
require_once 'fp/header.php';
require_once 'fp/connections.php';

// build employee list
// build list of employees
$query = "SELECT emp_id, emp_name, emp_email, emp_dept, emp_locX, emp_locY, emp_phone
    FROM tbl_EMP
    WHERE emp_status ='1' 
    ORDER BY emp_name";

$result = odbc_exec ( $connENT, $query );

// check to see if there are any results

if (odbc_num_rows ( $result ) > 0) {

    $employeeList = "<select onchange='empSelected()' id='empSelect'>
                        <option value=''>Select Employee</option>";

    while(odbc_fetch_row($result)){
        $name = odbc_result($result, "emp_name");

        $employeeList .= "<option value='$name'>$name</option>";
    }

    $employeeList .= "</select>";

} else {

        $employeeList = "No Records found";
}

$deptQuery = "SELECT DISTINCT emp_dept
                FROM tbl_EMP
                WHERE emp_status ='1' 
                ORDER BY emp_dept";

$deptResult = odbc_exec ($connENT,$deptQuery);

if(odbc_num_rows($deptResult)){
    $deptList = "<select onchange='deptSelected()' id='deptSelect'>
                    <option value='all'>All Departments</option>";

    while(odbc_fetch_row($deptResult)){
        $department = odbc_result($deptResult, "emp_dept");
        
        if($department != ""){
            $deptList .= "<option value='$department'>$department</option>";
        }
    }

    $deptList .= "</select>";
}

else{
    $deptList = "";
}

echo <<<_FixedHTML

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

    </script>

    <title>Lucix Corporation</title>

</head>
<body>
    $header
    <div id='container'>
		<div id='content'>
            <div id='mainSection'>
                    <div class='leftDiv holderDiv'>
                        <div class='sectionHolder'>
                            <table class='empHolderTable'>
                                <tr>
                                    <td>$deptList</td><td>Filter by Department</td><td><img src='../media/facility/userPin.png' class='facImg'></td>
                                </tr>
                                <tr>
                                    <td><div id='empListHold'>$employeeList</div></td>
                                    <td>Select Employee</td>
                                    <td></td>
                                </tr>
                            </table>
                            
                        </div>
                        <div class='sectionHolder'>
                            <table class='table2' id='facTable'>
                                <tr>
                                    <td onclick="clickTd('aed')" class='aedTd tdLeft'>
                                        Defibrillator
                                    </td>
                                    <td onclick="clickTd('aed')" class='tdRight'>
                                        <img src='../media/facility/aed.png' class='facImg'>
                                        <input type='hidden' value='n' id='aedIn'>
                                    </td>
                                    <td onclick="clickTd('earthquake')" class='earthquakeTd tdLeft'>
                                        Earthquake Supplies
                                    </td>
                                    <td onclick="clickTd('earthquake')" class='tdRight'>
                                        <img src='../media/facility/earthquake.png' class='facImg'>
                                        <input type='hidden' value='n' id='earthquakeIn'>
                                    </td>
                                    <td onclick="clickTd('exit')" class='exitTd tdLeft'>
                                        Exits
                                    </td>
                                    <td onclick="clickTd('exit')" class='tdRight'>
                                        <img src='../media/facility/exit.png' class='facImg'>
                                        <input type='hidden' value='n' id='exitIn'>
                                    </td>
                                </tr>
                                <tr>
                                    <td onclick="clickTd('eye')" class='eyeTd tdLeft'>
                                        Eyewash Stations
                                    </td>
                                    <td onclick="clickTd('eye')" class='tdRight'>
                                        <img src='../media/facility/eye.png' class='facImg'>
                                        <input type='hidden' value='n' id='eyeIn'>
                                    </td>
                                    <td onclick="clickTd('extinguisher')" class='extinguisherTd tdLeft'>
                                        Fire Extinguishers
                                    </td>
                                    <td onclick="clickTd('extinguisher')" class='tdRight'>
                                        <img src='../media/facility/extinguisher.png' class='facImg'>
                                        <input type='hidden' value='n' id='extinguisherIn'>
                                    </td>
                                    <td onclick="clickTd('firstaid')" class='firstaidTd tdLeft'>
                                        First Aid
                                    </td>
                                    <td onclick="clickTd('firstaid')" class='tdRight'>
                                        <img src='../media/facility/firstAid.png' class='facImg'>
                                        <input type='hidden' value='n' id='firstaidIn'>
                                    </td>
                                </tr>
                                <tr>
                                    <td onclick="clickTd('gas')" class='gasTd tdLeft'>
                                        Gas Storage
                                    </td>
                                    <td onclick="clickTd('gas')" class='tdRight'>
                                        <img src='../media/facility/storedGas.png' class='facImg'>
                                        <input type='hidden' value='n' id='gasIn'>
                                    </td>
                                    <td onclick="clickTd('hazmat')" class='hazmatTd tdLeft'>
                                        Hazmat
                                    </td>
                                    <td onclick="clickTd('hazmat')" class='tdRight'>
                                        <img src='../media/facility/hazmat.png' class='facImg'>
                                        <input type='hidden' value='n' id='hazmatIn'>
                                    </td>
                                    <td onclick="clickTd('msds')" class='msdsTd tdLeft'>
                                        MSDS
                                    </td>
                                    <td onclick="clickTd('msds')" class='tdRight'>
                                        <img src='../media/facility/msds.png' class='facImg'>
                                        <input type='hidden' value='n' id='msdsIn'>
                                    </td>
                                </tr>
                                <tr>
                                    <td onclick="clickTd('printer')" class='printerTd tdLeft'>
                                        Printer
                                    </td>
                                    <td onclick="clickTd('printer')" class='tdRight'>
                                        <img src='../media/facility/printer.png' class='facImg'>
                                        <input type='hidden' value='n' id='printerIn'>
                                    </td>
                                    <td onclick="clickTd('conf')" class='confTd tdLeft'>
                                        Conference Rooms
                                    </td>
                                    <td onclick="clickTd('conf')" class='tdRight'>
                                        <img src='../media/facility/conf.png' class='facImg'>
                                        <input type='hidden' value='n' id='confIn'>
                                    </td>
                                </tr>
                            </table>
                            <table class='table3'>
                                <tr>
                                    <th colspan='4'>Room Color Legend</td>
                                </tr>
                                <tr>
                                    <td class='cleanRoom'>Clean Rooms</td>
                                    <td class='breakRoom'>Break Rooms</td>
                                    <td class='confRoom'>Conference Rooms</td>
                                    <td class='restRoom'>Restrooms</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class='rightDiv holderDiv'>
                        <div id='mapHolder'>
                            <div id='empInfo' hidden></div>
                            <img src='../media/facility/floorplan3.png' id='mapImage'>

                            <img src='../media/facility/printer.png' style='top:16.7%;left:22.1%;' class='mapImg printer'>
                            <div style='top:16.7%;left:23.8%;' class='printLabel'>CMPRINTER01</div>
                            <img src='../media/facility/printer.png' style='top:36%;left:11.5%;' class='mapImg printer'>
                            <div style='top:36%;left:13.2%;' class='printLabel'>CMPRINTER02</div>
                            <img src='../media/facility/printer.png' style='top:63.5%;left:26.6%;' class='mapImg printer'>
                            <div style='top:62%;left:28%;' class='printLabel'>CMPRINTER03</div>
                            <img src='../media/facility/printer.png' style='top:66.3%;left:44%;' class='mapImg printer'>
                            <div style='top:66.3%;left:45.6%;' class='printLabel'>CMPRINTER04</div>
                            <img src='../media/facility/printer.png' style='top:84%;left:45%;' class='mapImg printer'>
                            <div style='top:84%;left:46.7%;' class='printLabel'>CMPRINTER05</div>
                            <img src='../media/facility/printer.png' style='top:73%;left:14%;' class='mapImg printer'>
                            <div style='top:73%;left:15.7%;' class='printLabel'>CMPRINTER06</div>
                            <img src='../media/facility/printer.png' style='top:56%;left:62.3%;' class='mapImg printer'>
                            <div style='top:56%;left:64%;' class='printLabel'>CMPRINTER07</div>
                            <img src='../media/facility/printer.png' style='top:60%;left:79%;' class='mapImg printer'>
                            <div style='top:60%;left:80.7%;' class='printLabel'>CMPRINTER08</div>
                            <img src='../media/facility/printer.png' style='top:75%;left:91.5%;' class='mapImg printer'>
                            <div style='top:72%;right:8%;' class='printLabel'>CMPRINTER09</div>
                            <img src='../media/facility/printer.png' style='top:80%;left:73.5%;' class='mapImg printer'>
                            <div style='top:83%;left:75%;' class='printLabel'>CMPRINTER10</div>

                            <img src='../media/facility/userPin2.png' id='mapStar' hidden>

                            <img src='../media/facility/extinguisher.png' style='top:15%;left:30%;' class='mapImg extinguisher'>
                            <img src='../media/facility/extinguisher.png' style='top:21%;left:23%;' class='mapImg extinguisher'>
                            <img src='../media/facility/extinguisher.png' style='top:34%;left:24%;' class='mapImg extinguisher'>
                            <img src='../media/facility/extinguisher.png' style='top:44%;left:17%;' class='mapImg extinguisher'>
                            <img src='../media/facility/extinguisher.png' style='top:50%;left:25%;' class='mapImg extinguisher'>
                            <img src='../media/facility/extinguisher.png' style='top:55%;left:15%;' class='mapImg extinguisher'>
                            <img src='../media/facility/extinguisher.png' style='top:55%;left:26%;' class='mapImg extinguisher'>
                            <img src='../media/facility/extinguisher.png' style='top:67.5%;left:18.5%;' class='mapImg extinguisher'>
                            <img src='../media/facility/extinguisher.png' style='top:74%;left:25%;' class='mapImg extinguisher'>
                            <img src='../media/facility/extinguisher.png' style='top:80.5%;left:40%;' class='mapImg extinguisher'>
                            <img src='../media/facility/extinguisher.png' style='top:68%;left:55%;' class='mapImg extinguisher'>
                            <img src='../media/facility/extinguisher.png' style='top:52%;left:60%;' class='mapImg extinguisher'>
                            <img src='../media/facility/extinguisher.png' style='top:58%;left:58%;' class='mapImg extinguisher'>
                            <img src='../media/facility/extinguisher.png' style='top:74%;left:57%;' class='mapImg extinguisher'>
                            <img src='../media/facility/extinguisher.png' style='top:79%;left:63.5%;' class='mapImg extinguisher'>
                            <img src='../media/facility/extinguisher.png' style='top:56%;left:70%;' class='mapImg extinguisher'>
                            <img src='../media/facility/extinguisher.png' style='top:52%;left:73%;' class='mapImg extinguisher'>
                            <img src='../media/facility/extinguisher.png' style='top:65%;left:76%;' class='mapImg extinguisher'>
                            <img src='../media/facility/extinguisher.png' style='top:71%;left:71%;' class='mapImg extinguisher'>
                            <img src='../media/facility/extinguisher.png' style='top:66%;left:84.5%;' class='mapImg extinguisher'>
                            

                            <img src='../media/facility/firstAid.png' style='top:31%;left:30%;' class='mapImg firstaid'>
                            <img src='../media/facility/firstAid.png' style='top:44%;left:17.5%;' class='mapImg firstaid'>
                            <img src='../media/facility/firstAid.png' style='top:52%;left:54.5%;' class='mapImg firstaid'>
                            <img src='../media/facility/firstAid.png' style='top:59%;left:58%;' class='mapImg firstaid'>
                            <img src='../media/facility/firstAid.png' style='top:71%;left:55%;' class='mapImg firstaid'>
                            <img src='../media/facility/firstAid.png' style='top:79%;left:63.5%;' class='mapImg firstaid'>
                            <img src='../media/facility/firstAid.png' style='top:62%;left:78%;' class='mapImg firstaid'>
                            

                            <img src='../media/facility/exit.png' style='top:43%;left:29%;' class='mapImg exit'>
                            <img src='../media/facility/exit.png' style='top:40%;left:8%;' class='mapImg exit'>
                            <img src='../media/facility/exit.png' style='top:70%;left:8%;' class='mapImg exit'>
                            <img src='../media/facility/exit.png' style='top:88%;left:44.5%;' class='mapImg exit'>
                            <img src='../media/facility/exit.png' style='top:88%;left:70%;' class='mapImg exit'>
                            <img src='../media/facility/exit.png' style='top:49%;left:73%;' class='mapImg exit'>
                            <img src='../media/facility/exit.png' style='top:6%;left:24%;' class='mapImg exit'>
                            <img src='../media/facility/exit.png' style='top:63%;left:85%;' class='mapImg exit'>

                            <img src='../media/facility/eye.png' style='top:28%;left:33%;' class='mapImg eye'>
                            <img src='../media/facility/eye.png' style='top:50%;left:24%;' class='mapImg eye'>
                            <img src='../media/facility/eye.png' style='top:66%;left:44%;' class='mapImg eye'>
                            <img src='../media/facility/eye.png' style='top:53%;left:54%;' class='mapImg eye'>
                            <img src='../media/facility/eye.png' style='top:67%;left:55%;' class='mapImg eye'>
                            <img src='../media/facility/eye.png' style='top:59%;left:58%;' class='mapImg eye'>
                            <img src='../media/facility/eye.png' style='top:%;left:%;' class='mapImg eye'>
                            
                            <img src='../media/facility/hazmat.png' style='top:28%;left:32%;' class='mapImg hazmat'>
                            <img src='../media/facility/hazmat.png' style='top:13%;left:33%;' class='mapImg hazmat'>
                            <img src='../media/facility/hazmat.png' style='top:50%;left:22%;' class='mapImg hazmat'>
                            <img src='../media/facility/hazmat.png' style='top:56%;left:55%;' class='mapImg hazmat'>
                            <img src='../media/facility/hazmat.png' style='top:52%;left:69%;' class='mapImg hazmat'>

                            <img src='../media/facility/msds.png' style='top:15%;left:29.5%;' class='mapImg msds'>
                            <img src='../media/facility/msds.png' style='top:50%;left:20%;' class='mapImg msds'>
                            <img src='../media/facility/msds.png' style='top:28%;left:34%;' class='mapImg msds'>
                            <img src='../media/facility/msds.png' style='top:62%;left:38%;' class='mapImg msds'>
                            <img src='../media/facility/msds.png' style='top:54%;left:55%;' class='mapImg msds'>

                            <img src='../media/facility/storedGas.png' style='top:28%;left:27%;' class='mapImg gas'>
                            <img src='../media/facility/storedGas.png' style='top:52%;left:51%;' class='mapImg gas'>
                            <img src='../media/facility/storedGas.png' style='top:52%;left:55%;' class='mapImg gas'>
                            
                            <img src='../media/facility/aed.png' style='left:15%;top:69%;' class='mapImg aed'>
                            <img src='../media/facility/aed.png' style='left:63%;top:80%;' class='mapImg aed'>

                            <img src='../media/facility/earthquake.png' style='top:78%;left:41%;' class='mapImg earthquake'>

                            <img src='../media/facility/conf.png' style='top:25%;left:20%;' class='mapImg conf'>
                            <div style='top:25%;left:21%;' class='confLabel'>Accounting</div>
                            <img src='../media/facility/conf.png' style='top:82%;left:18%;' class='mapImg conf'>
                            <div style='top:89%;left:16%;' class='confLabel'>Purchasing</div>
                            <img src='../media/facility/conf.png' style='top:78%;left:29%;' class='mapImg conf'>
                            <div style='top:78%;left:30%;' class='confLabel'>Iradj</div>
                            <img src='../media/facility/conf.png' style='top:72%;left:86%;' class='mapImg conf'>
                            <div style='top:68%;left:80.7%;' class='confLabel'>Executive</div>
                            <img src='../media/facility/conf.png' style='top:72%;left:74%;' class='mapImg conf'>
                            <div style='top:73%;left:60%;' class='confLabel'>Baldarrama</div>

                        </div>
                    </div>
                    <div style="clear: both;"></div>
            </div>
        </div>
    </div>

    <script>

        function clickTd(box) {
            var box = box;
            var t = box + 'Td';
            var state = box + 'In';

            $("." + box).fadeToggle('fast');
            if(box=="printer"){
                $(".printLabel").fadeToggle('fast');
            }

            if(box=="conf"){
                $(".confLabel").fadeToggle('fast');
            }

            if(document.getElementById(state).value == 'n'){
                elements = document.getElementsByClassName(t);
                for (var i = 0; i < elements.length; i++) {
                    elements[i].style.backgroundColor="#00cc00";
                    elements[i].style.color="white";
                }
                document.getElementById(state).value = 'y';
            }

            else{
                elements = document.getElementsByClassName(t);
                for (var i = 0; i < elements.length; i++) {
                    elements[i].style.backgroundColor="#b8b8b8";
                    elements[i].style.color="black";
                }
                document.getElementById(state).value = 'n';
            }
        }

        function deptSelected() {
            var dept = $('#deptSelect').val();

            $.ajax({
                type: 'POST',
                url: 'fp/FAC_filterEmpList.php',
                dataType: 'html',
                data: {
                    dept: dept
                },

                success: function(html) {
                    $('#mapStar').hide();
                    document.getElementById('empInfo').innerHTML = ""
                    document.getElementById('empListHold').innerHTML = html;
                }
            });
        }

        function empSelected() {

            $('#mapStar').hide();

            var name = $('#empSelect').val();

            $.ajax({
                type: 'POST',

                url: 'fp/FAC_empInfo.php',

                dataType: 'html',

                data: {
                    name: name
                },

                success: function(html) {
                    document.getElementById('empInfo').innerHTML = html;
                    $('#empInfo').hide().css({ top: yPos, left: xPos}).fadeIn();
                    placePin();
                }
            });
        }

        function placePin(){
            var xPos = $('#xPos').val();
            var yPos = $('#yPos').val();

            $('#mapStar').css({ top: yPos, left: xPos}).fadeIn();
        }   

    </script>
</body>
</html>

_FixedHTML

?>