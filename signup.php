<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <title>My Web Page</title>
  <link rel="stylesheet" href="style.css">
  <style>
  a {
    text-decoration: none;
    color: rgb(12, 45, 9);
    margin-top: 20px;

  }

  .img img {
    width: 500px;
    height: 100px;
    border-radius: 5px;
    margin-bottom: 10px;
  }
  </style>
</head>

<body>

  <div class="container">
    <div class="img"><img src="assets/getsitelogo.jpeg" alt="Mimosa Logo"></div>
    <form>
      <label for="username">Username:</label>
      <input type="text" id="username" name="username" required>

      <label for="associated_entity">Associated Entity:</label>
      <select name="associated_entity" id="associated_entity">
        <?php
        include 'includes/connection.php';
        $tsql = "SELECT * FROM Entities";
        $stmt = sqlsrv_query($conn, $tsql);

        while ($obj = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
          $eID = $obj["ID"];
          $eName = $obj["EntityName"];
          echo "<option value=" . $eID . ">" . $eName . "</option>";
        }
        sqlsrv_free_stmt($stmt);
        ?>
      </select>

      <label for="password">Password:</label>
      <input type="password" id="password" name="password" required>

      <label for="confirm_password">Confirm Password:</label>
      <input type="password" id="cPassword" name="confirm_password" required>

      <button type="submit" onclick="signup()">Sign Up</button>
      <a href="signin.php"> Already have an account? Sign In</a>
    </form>
  </div>
  <script>
  async function signup() {
    event.preventDefault();
    let username = document.getElementById("username").value;
    let password = document.getElementById("password").value;
    let cPassword = document.getElementById("cPassword").value;
    let associated_entity = document.getElementById("associated_entity").value;

    if (!username || !password || !cPassword || !associated_entity) {
      alert("Please fill out all fields");
      return false;
    }

    let response = await fetch('includes/register.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({
        'username': username,
        'password': password,
        'cPassword': cPassword,
        'associated_entity': associated_entity
      }),

    });
    let rawRes = await response.text();
    console.log(rawRes);

    let data = JSON.parse(rawRes)
    console.log(data);

    // console.log(data);
    if (data.success) {
      window.location.href = "index.php";
    } else {
      alert(data.message);
    }
    return false;
  }
  </script>


</body>

</html>