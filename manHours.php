<?php
// Check if the user is already authenticated
session_start();
if (!isset($_SESSION['user_id'])) {
  // Redirect to the login page if not authenticated
  header("Location: signin.php");
  exit();
}
$userID = $_SESSION['username'];

echo "Welcome " . $userID;
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <link rel="icon" href="../../favicon" type="image/x-icon">
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="index.css">

</head>

<body>
  <div class="link">
    <a href="mainPage.php">Back Home</a>
  </div>
  <div class="container">
    <form action="includes/manHours.php" method="post" onsubmit="return validateForm()">
      <label for="env">Work Place:</label>
      <select name="env" id="env">
        <option value="1">Surface</option>
        <option value="0">Underground</option>
      </select>

      <label for="entity">Entity:</label>

      <input list="entityList" id="entity" name="entity">
      <datalist id="entityList">
        <?php
        include 'includes/getData.php';
        $entties = getEntities();
        foreach ($entties as $entity) {
          $eID = $entity["ID"];
          $eName = $entity["EntityName"];
          $eDesc = $entity["EntityType"];
          echo "<option data-category = " . $eDesc . " value=" . htmlspecialchars_decode($eID) . " id = " . $eID . ">" . $eName . "</option>";
        }
        // sqlsrv_free_stmt($stmt);
        ?>
      </datalist>

      <div id="invisiq">
        <label for="hEntity">Hosting Department:</label>
        <select name="hEntity" id="hEntity">
          <option selected="" disabled="">Select Hosting Entity</option>
          <?php
          $hEntties = getEntities();
          foreach ($hEntties as $hEntity) {
            $hEID = $hEntity["ID"];
            $hEName = $hEntity["EntityName"];
            $hEDesc = $hEntity["EntityType"];
            echo "<option data-category = " . $hEDesc . " value=" . $hEID . " id = " . $hEID . ">" . $hEName . "</option>";
          }
          // sqlsrv_free_stmt($stmt);
          ?>
        </select>
      </div>

      <label for="entity_type">Entity Type: </label>
      <select name="entity_type" id="entity_type">
      </select>

      <label for="pType">Personnel Type:</label>
      <select name="pType" id="pType">
      </select>

      <label for="NoOfPersonnel">No. Of Personnel:</label>
      <input type="number" id="NoOfPersonnel" name="NoOfPersonnel">

      <label for="manHrs">Man Hours:</label>
      <input type="number" id="manHrs" name="manHrs">

      <button type="submit" onclick="" name="mainForm">Submit</button>
    </form>
  </div>
  <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
  <script type="text/javascript">
    $(document).ready(function() {
      $("#entity").change(function() {
        let selectedEntity = parseInt($("#entityList option[value='" + $("#entity").val() + "']").attr(
          "data-category"));
        let selectedHostingEntity = parseInt($("#entityList option[value='" + $("#entity").val() + "']").attr(
          "id"))

        if (selectedEntity === 1) {
          $("#invisiq").hide();
          $("#hEntity").val(selectedHostingEntity);
        } else {
          $("#invisiq").show();
          selectedEntity = 2;
          // if (!selectedHostingEntity) {
          //   $("#entity").val(parseInt($("#entityList option:last-child").attr("id")) + 1);
          // }
        }
        console.log((parseInt($("#entityList option:last-child").attr("id")) + 1))
        $.ajax({
          url: 'includes/getData.php',
          method: 'post',
          data: 'selectedEntity=' + selectedEntity
        }).done(function(entities) {
          // console.log("entities", entities);
          entities = JSON.parse(entities);
          $("#entity_type").empty();
          entities.forEach(function(entity) {
            $("#entity_type").append('<option value=' + entity.ID + '>' + entity.EntityDescription +
              '</option>');
          })
          $("#entity_type").trigger("change")
        })
      });
      $("#entity_type").change(function() {
        let entityType = $("#entity_type").val();
        // console.log("entityType", entityType);
        $.ajax({
          url: 'includes/getData.php',
          method: 'post',
          data: 'entityType=' + entityType
        }).done(function(pType) {
          pType = JSON.parse(pType);
          $("#pType").empty();
          $("#pType").append('<option selected="" disabled="" >Select Personnel Type</option>');
          pType.forEach(function(asso) {
            // console.log("pType", pType);
            $("#pType").append('<option value=' + asso.ID + '>' + asso.TypeDesc +
              '</option>');
          })
        })
      })
    });

    function validateForm() {
      var noOfPersonnel = $("#NoOfPersonnel").val();
      var manHrs = $("#manHrs").val();
      var pType = $("#pType").val();
      let selectedEntityCategory = $("#entity option:selected").data("category");
      let selectedHostingEntity = $("#hEntity option:selected").text();
      let selectedEntity = $("#entity option:selected").text();

      console.log("selectedEntityCategory", selectedEntityCategory);
      console.log("selectedHostingEntity", selectedHostingEntity);
      console.log("selectedEntity", selectedEntity);


      if (!pType || !manHrs || !noOfPersonnel) {
        alert("Please fill in all the fields");
        return false;
      }
      if (selectedEntityCategory === 1 && selectedHostingEntity !== selectedEntity) {
        alert("Please select the same entity for hosting and associated entity");
        return false;
      }

      // Check if manHrs is larger than 8 times NoOfPersonnel
      if (parseInt(manHrs) > 300 * parseInt(noOfPersonnel)) {
        alert(`Not valid working hours for ${noOfPersonnel} personnel in a month.`);
        return false; // Prevent form submission
      }

      return true;
    }
  </script>
</body>

</html>