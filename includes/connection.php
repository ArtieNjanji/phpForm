<?php
$serverName = "192.168.2.227";
$database = "SHE";
$uid = "axtsadmin";
$pass = "T1m33h33t2220";

// $serverName = "ICT_DEV01\SQLEXPRESS";
// $database = "SHE";
// $uid = "";
// $pass = "";

$connectionInfo = array(
    "UID" => $uid,
    "PWD" => $pass,
    "Database" => $database
);

try {
    /* Connect using SQL Server Authentication. */
    $conn = sqlsrv_connect($serverName, $connectionInfo);

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "</br>";
} 

?>