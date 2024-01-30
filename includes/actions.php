<?php

include 'connection.php';
include 'getPeriod.php';

session_start();

$approvedBy = $_SESSION['username'];

if ($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST['approve'])) {
    $id = $_POST['id'];
    $tsql = "UPDATE ManHours SET Approved = 1, ApprovedBy = '$approvedBy' WHERE ID = $id";
    $stmt = sqlsrv_query($conn, $tsql);

    if ($stmt === false) {
        echo "Row not updated.\n";
        echo $id;
        die(print_r(sqlsrv_errors(), true));
    } else {
        echo "Row updated successfully.\n";
        header("Location: ../mainPage.php");
    }

    sqlsrv_free_stmt($stmt);
    sqlsrv_close($conn);
} else {
    echo "No data received";
}
