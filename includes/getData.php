<?php
include 'connection.php';

function getEntities(){
  global $conn;
  $tsql = "SELECT * FROM Entities";
  $stmt = sqlsrv_query($conn, $tsql);
  $entities = array();
  while ($obj = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    $entities[] = $obj;
  }
  return $entities;
}

if(isset($_POST['selectedEntity'])){
  $entity = $_POST['selectedEntity'];
  $tsql = "SELECT * FROM [EntityTypes] WHERE ID = ?";
  $params = array($entity);
  $stmt = sqlsrv_query($conn, $tsql, $params);
  $entities = array();
  while ($obj = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    $entities[] = $obj;
  }
  echo json_encode($entities);
}

if (isset($_POST['entityType'])) {
  $entity = $_POST['entityType'];
  $tsql = "SELECT * FROM [PersonnelType] WHERE Entity = ?";
  $params = array($entity);
  $stmt = sqlsrv_query($conn, $tsql, $params);
  $entities = array();
  while ($obj = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    $entities[] = $obj;
  }
  echo json_encode($entities);
}
?>