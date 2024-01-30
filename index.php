<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <title>My Web Page</title>
  <link rel="stylesheet" href="index.css">
</head>

<body>
  <!-- Your content goes here -->
  <a href="manHours.php">add item</a>

  <div class="browser">
    <div class="browse-items">
      <h4 class="head-items">Financial Year</h4>
      <h4 class="head-items">Financial Month</h4>
      <h4 class="head-items">Personnel Type</h4>
      <h4 class="head-items">Num Of Personnel</h4>
      <h4 class="head-items">Work Place</h4>
      <h4 class="head-items">Hours</h4>
      <h4 class="head-items">Hosting Entity</h4>
      <h4 class="head-items">Created By</h4>
      <h4 class="head-items">Status</h4>
      <h4 class="head-items">Approved By</h4>
    </div>
    <?php
        include 'includes/connection.php';
        $tsql = "SELECT * FROM ManHours";
        $stmt = sqlsrv_query($conn, $tsql);
      if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
      }
      while ($obj = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        $fy = $obj["FinYear"];
        $fm = $obj["FinMonth"];
        $pt = $obj["PersonnelTypeID"];
        $nop = $obj["NumOfPersonnel"];
        $wp = $obj["Surface"];
        $hrs = $obj["Hours"];
        $he = $obj["HostingEntityID"];
        $cb = $obj["CreatedBy"];
        $st = $obj["Approved"];
        $ab = $obj["ApprovedBy"];
        echo "<div class='browse-items'>";
        echo "<h4 class='browse-item'>".$fy."</h4>";
        echo "<h4 class='browse-item'>".$fm."</h4>";
        echo "<h4 class='browse-item'>".$pt."</h4>";
        echo "<h4 class='browse-item'>".$nop."</h4>";
        echo "<h4 class='browse-item'>".$wp."</h4>";
        echo "<h4 class='browse-item'>".$hrs."</h4>";
        echo "<h4 class='browse-item'>".$he."</h4>";
        echo "<h4 class='browse-item'>".$cb."</h4>";
        echo "<h4 class='browse-item'>".$st."</h4>";
        echo "<h4 class='browse-item'>".$ab."</h4>";
        echo "</div>";
      }
      ?>
  </div>


</body>

</html>