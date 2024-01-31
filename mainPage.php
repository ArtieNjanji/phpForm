<?php
// Check if the user is already authenticated
session_start();
if (!isset($_SESSION['user_id'])) {
  // Redirect to the login page if not authenticated
  header("Location: signin.php");
  exit();
}
$userID = $_SESSION['username'];
$userRole = $_SESSION['role'];
$associatedEntity = $_SESSION['associatedEntity'];


echo "Welcome " . $userID;
?>
<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <title>My Web Page</title>
  <link rel="stylesheet" href="index.css">
</head>

<body>
  <div>

    <!-- Your content goes here -->
    <div class="link">
      <a href="manHours.php">Add Men hours</a>
    </div>
    <div class="table">
      <table>
        <tr>
          <th>Financial Year</th>
          <th>Financial Month</th>
          <th>Personnel Type</th>
          <th>Num Of Personnel</th>
          <th>Work Place</th>
          <th>Hours</th>
          <th>Hosting Entity</th>
          <th>Entity</th>
          <th>Created By</th>
          <th>Status</th>
          <th>Approved By</th>
        </tr>
        <?php

        include 'includes/connection.php';

        if ($userRole == 1) {
          $tsql = "SELECT
                            mh.[ID] AS ID,
                            mh.[FinYear],
                            mh.[FinMonth],
                            pt.TypeDesc AS [PersonnelTypeID],
                            mh.[NumOfPersonnel],
                            mh.[Surface],
                            mh.[Hours],
                            e.EntityName AS Entity,
                            eHosting.EntityName AS 'Hosting Department', -- Modified to include the hosting entity name
                            mh.[CreatedBy],
                            mh.[Approved],
                            mh.[ApprovedBy]
                        FROM
                            ManHours mh
                        LEFT JOIN
                            Users u ON mh.CreatedBy = u.Username
                        LEFT JOIN
                            PersonnelType pt ON mh.PersonnelTypeID = pt.ID
                        LEFT JOIN
                            Entities e ON mh.EntityID = e.ID
                        LEFT JOIN
                            Entities eHosting ON mh.HostingEntityID = eHosting.ID 
                      where mh.[EntityID] = ? or mh.CreatedBy = ?
                      order by mh.[ID] desc";
          $params = array($associatedEntity, $userID);
          $stmt = sqlsrv_query($conn, $tsql, $params);
        } else {
          $tsql = "SELECT
                            mh.[ID] AS ID,
                            mh.[FinYear],
                            mh.[FinMonth],
                            pt.TypeDesc AS [PersonnelTypeID],
                            mh.[NumOfPersonnel],
                            mh.[Surface],
                            mh.[Hours],
                            e.EntityName AS Entity,
                            eHosting.EntityName AS 'Hosting Department', -- Modified to include the hosting entity name
                            mh.[CreatedBy],
                            mh.[Approved],
                            mh.[ApprovedBy]
                        FROM
                            ManHours mh
                        LEFT JOIN
                            Users u ON mh.CreatedBy = u.Username
                        LEFT JOIN
                            PersonnelType pt ON mh.PersonnelTypeID = pt.ID
                        LEFT JOIN
                            Entities e ON mh.EntityID = e.ID
                        LEFT JOIN
                            Entities eHosting ON mh.HostingEntityID = eHosting.ID -- Added join for hosting entity
                        WHERE
                            mh.CreatedBy = '$userID'
                        ORDER BY
                            mh.[ID] DESC;";
          $stmt = sqlsrv_query($conn, $tsql);
        }

        if ($stmt === false) {
          die(print_r(sqlsrv_errors(), true));
        }
        while ($obj = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
          $id = $obj["ID"];
          $fy = $obj["FinYear"];
          $fm = $obj["FinMonth"];
          $pt = $obj["PersonnelTypeID"];
          $nop = $obj["NumOfPersonnel"];
          $wp = $obj["Surface"];
          $hrs = $obj["Hours"];
          $he = $obj["Hosting Department"];
          $e = $obj["Entity"];
          $cb = $obj["CreatedBy"];
          $st = $obj["Approved"];
          $ab = $obj["ApprovedBy"];
          // $id = $obj["ID"];

          echo "<tr>";
          echo "<td>" . $fy . "</td>";
          echo "<td>" . $fm . "</td>";
          echo "<td>" . $pt . "</td>";
          echo "<td>" . $nop . "</td>";
          echo "<td>" . $wp . "</td>";
          echo "<td>" . $hrs . "</td>";
          echo "<td>" . $e . "</td>";
          echo "<td>" . $he . "</td>";
          echo "<td>" . $cb . "</td>";
          echo "<td>";
          // Check if the Approved value is not 1
          if ($st != 1 && $userRole === 1) {
            echo "<form action='includes/actions.php' method='post'>
                          <input type='hidden' name='id' value='$id'>
                          <button class ='btn' type='submit' name='approve'>Approve</button>
                        </form>";
          } else if ($st != 1 && $userRole != 1) {
            // Approved value is 1, do not show the button
            echo "Pending";
          } else {
            echo "Approved";
          }

          echo "</td>";
          echo "<td>" . $ab . "</td>";
          echo "</tr>";
        }

        ?>
      </table>
    </div>

  </div>

</body>

</html>