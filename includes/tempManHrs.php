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
    $tsql = "INSERT INTO ManHours (CreatedBy, FinYear, FinMonth, PersonnelTypeID,  Surface, NumOfPersonnel, HostingEntityID, EntityID, [Hours]) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $params = array($_SESSION['username'], $finY, $finM, $pType, $env, $NoOfPersonnel, $hEntity, $entity, $manHrs);

    $stmt = sqlsrv_query($conn, $tsql, $params);

    if ($stmt && sqlsrv_rows_affected($stmt) === 1) {
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

    // sqlsrv_free_stmt($stmt);
  } catch (Exception $e) {
    die('Caught exception: ' . $e->getMessage());
  }
}
