<?php
include 'connection.php';
include 'getPeriod.php';
// include '../manHours.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST['mainForm'])) {
  $manHrs = htmlspecialchars($_POST['manHrs']);
  $env = htmlspecialchars($_POST['env']);
  $NoOfPersonnel = htmlspecialchars($_POST['NoOfPersonnel']);
  $entity = htmlspecialchars($_POST['entity']);
  $hEntity = htmlspecialchars($_POST['hEntity']);
  $pType = htmlspecialchars($_POST['pType']);
  // echo $pType;

  try {
    // 1, 'Whaleside',20,2,2,8,2500,'Artwell'
    $tsql = "exec insert_man_hours ?, ?, ?, ?, ?, ?, ?";
    $params = array($env, $entity, $hEntity, $pType, $NoOfPersonnel, $manHrs, $_SESSION['username']);

    $stmt = sqlsrv_query($conn, $tsql, $params) or die(print_r(sqlsrv_errors(), true));

    if ($stmt) {
      echo json_encode(['success' => true, 'message' => 'Item successfully added.']);

      header("Location: ../mainPage.php");
    } else {
      // Log or handle the error
      $errors = sqlsrv_errors();
      echo json_encode(["success" => false, "errors" => $errors]);
      $errorMessage = 'An error occurred during adding an item.';

      if ($errors !== null) {
        foreach ($errors as $error) {
          $errorMessage .= "\nSQL Server Error: " . $error['message'];
        }
      }

      echo json_encode(['success' => false, 'message' => $errorMessage]);

      error_log("SQL Server Error: " . print_r($errors, true));
    }

    sqlsrv_free_stmt($stmt);
  } catch (Exception $e) {
    die('Caught exception: ' . $e->getMessage());
  }
}
