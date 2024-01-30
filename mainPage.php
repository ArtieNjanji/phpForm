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
          $tsql = "SELECT  mh.[ID] ID
                          ,mh.[FinYear]
                          ,mh.[FinMonth]
                          ,pt.TypeDesc  [PersonnelTypeID]
                          ,mh.[NumOfPersonnel]
                          ,mh.[Surface]
                          ,mh.[Hours]
                          ,e.EntityName  [EntityID]
                          ,e.EntityName [HostingEntityID]
                          ,mh.[CreatedBy]
                          ,mh.[Approved]
                          ,mh.[ApprovedBy] FROM ManHours mh 

                      left join Users u on mh.CreatedBy = u.Username
                      left join PersonnelType pt on mh.PersonnelTypeID = pt.ID
                      left join Entities e on mh.EntityID = e.ID
                      where mh.[EntityID] = $associatedEntity
                      order by mh.[ID] desc";
                               
        } else {
            $tsql = "SELECT   mh.[ID] ID      
                              ,mh.[FinYear]
                              ,mh.[FinMonth]
                              ,pt.TypeDesc  [PersonnelTypeID]
                              ,mh.[NumOfPersonnel]
                              ,mh.[Surface]
                              ,mh.[Hours]
                              ,e.EntityName  [EntityID]
                              ,e.EntityName [HostingEntityID]
                              ,mh.[CreatedBy]
                              ,mh.[Approved]
                              ,mh.[ApprovedBy] FROM ManHours mh 
                          left join Users u on mh.CreatedBy = u.Username
                          left join PersonnelType pt on mh.PersonnelTypeID = pt.ID
                          left join Entities e on mh.EntityID = e.ID 
                      where mh.CreatedBy = '$userID' order by mh.[ID] desc";
        }
            $stmt = sqlsrv_query($conn, $tsql);
            
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
            $he = $obj["HostingEntityID"];
            $e = $obj["EntityID"];
            $cb = $obj["CreatedBy"];
            $st = $obj["Approved"];
            $ab = $obj["ApprovedBy"];
            // $id = $obj["ID"];
            
            echo "<tr>";
            echo "<td>".$fy."</td>";
            echo "<td>".$fm."</td>";
            echo "<td>".$pt."</td>";
            echo "<td>".$nop."</td>";
            echo "<td>".$wp."</td>";
            echo "<td>".$hrs."</td>";
            echo "<td>".$he."</td>";
            echo "<td>".$e."</td>";
            echo "<td>".$cb."</td>";
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
            echo "<td>".$ab."</td>";
            echo "</tr>";
          }
            
        ?>
      </table>
    </div>

  </div>

</body>

</html>