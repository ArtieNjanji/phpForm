<?php

  $serverName = "192.168.2.227";
  $database = "AXDB";
  $uid = "axtsadmin";
  $pass = "T1m33h33t2220";



  $connectionInfo = array(
      "UID" => $uid,
      "PWD" => $pass,
      "Database" => $database
  );

  try {
      /* Connect using SQL Server Authentication. */
      $con = sqlsrv_connect($serverName, $connectionInfo);

      if($con){
        $tsql = "select  AXDB.dbo.get_current_fin_year() FinYear,AXDB.dbo.get_current_period() FinMonth";
        $stmt = sqlsrv_query( $con, $tsql);
        if( $stmt === false )
        {
          echo "Error in executing query.</br>";
          die( print_r( sqlsrv_errors(), true));
        }
        while ( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) )
        {
          // Assign the values to variables
          $finY = $row['FinYear'];
          $finM = $row['FinMonth'] - 1;
        
        }
    }
    else{
      echo "Connection could not be established.<br />";
      die( print_r( sqlsrv_errors(), true));
    } 
  }
    catch (Exception $e) {
        echo "Error: " . $e->getMessage() . "</br>";
    } 

if($finM ===1){
  $month = "July";
}
if($finM ===2){
  $month = "August";
}
if($finM ===3){
  $month = "September";
}
if($finM ===4){
  $month = "October";
}
if($finM ===5){
  $month = "November";
}
if($finM ===6){
  $month = "December";
}
if($finM ===7){
  $month = "January";
}
if($finM ===8){
  $month = "February";
}
if($finM ===9){
  $month = "March";
}
if($finM ===10){
  $month = "April";
}
if($finM ===11){
  $month = "May";
}
if($finM ===12){
  $month = "June";
}
sqlsrv_free_stmt( $stmt );