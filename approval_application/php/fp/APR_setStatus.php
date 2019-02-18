<?php

require_once 'connections.php';

$query = "UPDATE APR_main
          SET APR_status = 'Pending'
          WHERE APR_isTemplate = 0";

mysqli_query($conn,$query);

$query2 = "UPDATE APR_main
           SET APR_status = 'Template'
           WHERE APR_isTemplate = 1";

mysqli_query($conn,$query2);

echo 1;

?>