<?php

$query = "SELECT TOP 1 *
          FROM tblUniqueOperationSteps";

          $query = "SELECT TOP 10 tblUniqueOperationSteps.TravelerID
          FROM tblUniqueOperationSteps tblUniqueOperationSteps";

$connOLTP = odbc_connect ( 'Driver={SQL Server};Server=CMERP\LucixSQL;Database=Online Traveler Program Official;', '', '' );

$result = odbc_exec($connOLTP,$query);

