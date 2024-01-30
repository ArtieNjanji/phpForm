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
    <a href="mainPage.php">Home</a>
  </div>
  <div class="container">
    <form action="includes/manHours.php" method="post" onsubmit="return validateForm()">
      <label for="env">Work Place:</label>
      <select name="env" id="env">
        <option value="1">Surface</option>
        <option value="0">Underground</option>
      </select>

      <label for="entity">Entity:</label>
      <select id="entity" name="entity" id="entity">
        <option selected="" disabled="">Select Entity</option>
        <?php
        include 'includes/getData.php';
        $entties = getEntities();
        foreach ($entties as $entity) {
          $eID = $entity["ID"];
          $eName = $entity["EntityName"];
          $eDesc = $entity["EntityType"];
          echo "<option data-category = " . $eDesc . " value=" . $eID . " id = " . $eID . ">" . $eName . "</option>";
        }
        // sqlsrv_free_stmt($stmt);
        ?>
      </select>

      <!-- <input list="entityList"> -->
      <datalist id="entityList">
        <?php
        $entties = getEntities();
        foreach ($entties as $entity) {
          $eID = $entity["ID"];
          $eName = $entity["EntityName"];
          $eDesc = $entity["EntityType"];
          echo "<option data-category = " . $eDesc . " value=" . $eID . " id = " . $eID . ">" . $eName . "</option>";
        }
        // sqlsrv_free_stmt($stmt);
        ?>
      </datalist>


      <label for="hEntity">Hosting Department:</label>
      <select name="hEntity" id="hEntity">
        <option selected="" disabled="">Select Hosting Entity</option>
        <!-- Options will be dynamically populated based on the selected entity -->
      </select>

      <label for="entity_type">Entity Type: </label>
      <select name="entity_type" id="entity_type">
      </select>

      <label for="pType">Personnel Type:</label>
      <select name="pType" id="pType">
      </select>

      <label for="NoOfPersonnel">No. Of Personnel:</label>
      <input type="number" id="NoOfPersonnel" name="NoOfPersonnel" required>

      <label for="manHrs">Man Hours:</label>
      <input type="number" id="manHrs" name="manHrs" required>

      <!-- <label for="entity_type">Associated Entity:</label>
            <input type="text" id="entity_type" name="entity_type" false> -->
      <button type="submit" onclick="" name="mainForm">Submit</button>
    </form>
  </div>
  <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
  <script type="text/javascript">
  $(document).ready(function() {
    $("#entity").change(function() {
      let selectedEntity = $("#entity option:selected").data("category");
      let entityID = $("#entity").val();

      $.ajax({
        url: 'includes/getData.php',
        method: 'post',
        data: 'selectedEntity=' + selectedEntity + '&hEntity=' + (selectedEntity === 1 ? 'selected' :
          'all')
      }).done(function(entities) {
        entities = JSON.parse(entities);
        $("#hEntity").empty();
        if (selectedEntity === 1) {
          $("#hEntity").append('<option value="' + entities[0].ID + '">' + entities[0].EntityName +
            '</option>');
        } else {
          entities.forEach(function(entity) {
            $("#hEntity").append('<option value=' + entity.ID + '>' + entity.EntityName + '</option>');
          });
        }
      });

    });
    $("#entity").change(function() {
      // var selectedEntity = $("#entity").val();
      let selectedEntity = $("#entity option:selected").data("category");
      // console.log(selectedEntity);
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
        $("#pType").empty();
        $("#pType").append('<option selected="" disabled="" value="">Select Personnel Type</option>');
        pType.forEach(function(asso) {
          console.log("pType", pType);
          $("#pType").append('<option value=' + asso.ID + '>' + asso.TypeDesc +
            '</option>');
        })
      })
    })

  });

  function validateForm() {
    var noOfPersonnel = $("#NoOfPersonnel").val();
    var manHrs = $("#manHrs").val();

    // Check if manHrs is larger than 8 times NoOfPersonnel
    if (parseInt(manHrs) > 300 * parseInt(noOfPersonnel)) {
      alert(`Not valid working hours for ${noOfPersonnel} personnel in a month.`);
      return false; // Prevent form submission
    }

    // Your other validation logic can go here

    return true;
  }
  </script>
</body>

</html>