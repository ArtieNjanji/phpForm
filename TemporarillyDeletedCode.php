     // include 'includes/connection.php';
     // $tsql = "SELECT * FROM Entities";
     // $stmt = sqlsrv_query($conn, $tsql);

     // while ($obj = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
     // $eID = $obj["ID"];
     // $eName = $obj["EntityName"];
     // $eDesc = $obj["EntityType"];

     // echo "<option value=".$eID.">".$eName."</option>";


     // }

     async function signup() {
     try {
     const response = await fetch('manHours.php', {
     method: 'POST',
     headers: {
     'Content-Type': 'application/x-www-form-urlencoded',
     },
     body: new URLSearchParams(new FormData(document.getElementById('signupForm'))),
     name: 'signup'
     });
     console.log("response", response);
     const data = await response.json();
     console.log("data", data);

     if (data.success) {
     alert('Signup successful!');
     } else {
     alert('Signup failed. ' + data.message);
     }
     } catch (error) {
     console.error('Error:', error);
     }
     }



     <?php
      include 'includes/connection.php';
      $tsql = "SELECT * FROM PersonnelType";
      $stmt = sqlsrv_query($conn, $tsql);

      while ($obj = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        $pID = $obj["ID"];
        $pName = $obj["TypeDesc"];
        echo "<option value=" . $pID . ">" . $pName . "</option>";
      }
      // sqlsrv_free_stmt($stmt);
      ?>













     // Handle the signup request
     if ($_SERVER['REQUEST_METHOD'] === "POST") {
     // $username = htmlspecialchars($_POST['username']);
     // $password = htmlspecialchars($_POST['password']);
     // $associated_entity = htmlspecialchars($_POST['associated_entity']);
     // $cPassword = htmlspecialchars($_POST['confirm_password']);
     // $role = 0;

     $inputJSON = file_get_contents('php://input');
     $input = json_decode($inputJSON, TRUE);

     $username = htmlspecialchars($input['username']);
     $password = htmlspecialchars($input['password']);
     $associated_entity = htmlspecialchars($input['associated_entity']);
     $cPassword = htmlspecialchars($input['cPassword']);

     $result = signup($username, $password, $associated_entity, $cPassword);

     // Return a JSON response
     header('Content-Type: application/json');
     echo json_encode($result);
     // header("Location: index.php");
     if ($result['success'] === true) {
     session_start();
     $_SESSION['user_id'] = $result['data']['user_id'];
     $_SESSION['username'] = $result['data']['username'];
     $_SESSION['role'] = $result['data']['role'];
     $_SESSION['associatedEntity'] = $result['data']['associatedEntity'];

     header("Location: mainPage.php");
     exit();
     } else {
     echo json_encode($result);
     // header("Location: signup.php");
     }
     } else {
     echo json_encode(array('success' => false, 'message' => 'No data received'));

     // header("Location: signup.php");
     }




     // if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['signup'])) {
     // $inputJSON = file_get_contents('php://input');
     // $input = json_decode($inputJSON, TRUE);

     // $username = htmlspecialchars($input['username']);
     // $password = htmlspecialchars($input['password']);
     // $associated_entity = htmlspecialchars($input['associated_entity']);
     // $confirmPassword = htmlspecialchars($input['confirmPassword']);

     // $result = signup($username, $password, $associated_entity, $confirmPassword);

     // if ($result['success'] === true) {
     // // You can also perform additional actions or validations here if needed
     // echo trim(json_encode(array('success' => true, 'message' => 'User sign up successful.')));
     // } else {
     // // Return a JSON response indicating failure and an error message
     // echo trim(json_encode(array('success' => false, 'message' => $result['message'])));
     // }

     // exit();
     // } else {
     // // Return a JSON response for cases where no data is received
     // echo trim(json_encode(array('success' => false, 'message' => 'No data received')));
     // }











     <label for="hEntity">Hosting Entity:</label>
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