<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <title>She Form</title>
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
  <!-- Your content goes here -->
  <div class="container">
    <div class="img"><img src="assets/getsitelogo.jpeg" alt="Mimosa Logo"></div>
    <form>
      <label for="username">Username:</label>
      <input type="text" id="username" name="username" required>
      <label for="password">Password:</label>
      <input type="password" id="password" name="password" required>
      <button type="button" onclick="signin()">Sign In</button>
      <a href="signup.ph"> Do not have account? Sign Up</a>
    </form>
  </div>

  <script>
  async function signin() {
    let username = document.getElementById("username").value;
    let password = document.getElementById("password").value;
    let response = await fetch('includes/auth.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({
        'username': username,
        'password': password
      })
    });

    let data = await response.json();
    // console.log(data);

    if (data.success) {
      window.location.href = "index.php";
    } else {
      console.log(data);
      alert(data.message || "Authentication failed");
    }
  }
  </script>
</body>

</html>