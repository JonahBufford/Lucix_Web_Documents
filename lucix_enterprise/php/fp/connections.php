<?php
//returns all dB connection strings

$connOLTP = odbc_connect ( 'Driver={SQL Server};Server=CMERP\LucixSQL;Database=Online Traveler Program Official;', '', '' );  //Flight OLTP

$connCOMM = odbc_connect ( 'Driver={SQL Server};Server=CMERP\LucixSQL;Database=Commercial Online Traveler Program;', '', '' );  //Commercial OLTP

$connERP = odbc_connect ( 'Driver={SQL Server};Server=CMERPSQL\aptean;Database=iERP87_Prod;', '', '' ); //Intuitive ERP

$connENT = odbc_connect ( 'Driver={SQL Server};Server=CMERP\LucixSQL;Database=Enterprise_Common;', '', '' );  //common enterprise files

$connECO = odbc_connect ( 'Driver={SQL Server};Server=CMERP\LucixSQL;Database=ECO;', '', '' );  //common enterprise files

?>