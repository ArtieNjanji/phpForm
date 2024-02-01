<?php
$serverName = "192.168.2.227";
$database = "SHE";
$uid = "";
$pass = "";

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
