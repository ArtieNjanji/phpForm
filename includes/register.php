<?php
include 'connection.php';

function signup($username, $password, $associated_entity, $confirmPassword)
{
  global $conn;

  if (!$conn) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed']);
  }
  try {
    // Check if the user already exists
    $query = "SELECT * FROM Users WHERE Username = ?";
    $params = array($username);
    $stmt = sqlsrv_query($conn, $query, $params);

    if ($stmt && sqlsrv_has_rows($stmt)) {
      // User already exists, sign up failed
      return ['success' => false, 'message' => 'User already exists.'];
    }
    if ($password !== $confirmPassword) {
      // Passwords do not match
      return ['success' => false, 'message' => 'Passwords do not match.'];
    }
    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert the new 
    $query = "INSERT INTO Users (Username, Password, AssociatedEntity, Role) VALUES (?, ?, ?, ?)";
    $params = array($username, $hashed_password, $associated_entity, 0);
    $stmt = sqlsrv_query($conn, $query, $params);

    if ($stmt && sqlsrv_rows_affected($stmt) === 1) {

      // User sign up successful
      $userData = ['user_id' => sqlsrv_get_field($stmt, 0), 'username' => $username, 'role' => 0, 'associatedEntity' => $associated_entity];
      return ['success' => true, 'message' => 'User sign up successful.', 'data' => $userData];
    } else {
      // Log or handle the error
      // error_log("SQL Server Error: " . print_r(sqlsrv_errors(), true));
      return ['success' => false, 'message' => 'An unexpected error occurred.'];
    }
  } catch (Exception $e) {
    // Log the exception
    error_log("Exception: " . $e->getMessage());

    // Return a JSON response with an error message
    return ['success' => false, 'message' => 'An unexpected error occurred.'];
  }
}
function signin($username, $password)
{
  global $conn;

  if (!$conn) {
    return ['success' => false, 'message' => 'Database connection failed.'];
  }
  try {
    // Validate input parameters
    if (empty($username) || empty($password)) {
      return ['success' => false, 'message' => 'Username and password are required.'];
    }
    // Check if the user already exists
    $query = "SELECT * FROM Users WHERE Username = ?";
    $params = array($username);
    $stmt = sqlsrv_query($conn, $query, $params);

    if ($stmt && sqlsrv_has_rows($stmt)) {
      // User exists, retrieve user data
      $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
      $hashed_password = $row['Password'];

      if (password_verify(trim($password), trim($hashed_password))) {
        // Password is correct, return additional user data if needed
        $userData = ['user_id' => $row['ID'], 'username' => $row['Username'], 'role' => $row['Role'], 'associatedEntity' => $row['AssociatedEntity']];
        return ['success' => true, 'message' => 'User sign in successful.', 'data' => $userData];
      } else {
        // Password is incorrect
        return ['success' => false, 'message' => 'Incorrect password.', 'data' => $hashed_password, 'data2' => $password];
      }
    } else {
      // User does not exist, sign in failed
      return ['success' => false, 'message' => 'User does not exist.'];
    }
  } catch (Exception $e) {
    // Log the exception
    error_log("Exception: " . $e->getMessage());

    // Return a JSON response with an error message
    return ['success' => false, 'message' => 'An unexpected error occurred.'];
  }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $inputJSON = file_get_contents('php://input');
  $input = json_decode($inputJSON, TRUE);

  $username = htmlspecialchars($input['username']);
  $password = htmlspecialchars($input['password']);
  $associated_entity = htmlspecialchars($input['associated_entity']);
  $confirmPassword = htmlspecialchars($input['cPassword']);

  $result = signup($username, $password, $associated_entity, $confirmPassword);

  if ($result['success'] === true) {
    session_start();
    $_SESSION['user_id'] = $result['data']['user_id'];
    $_SESSION['username'] = $result['data']['username'];
    $_SESSION['role'] = $result['data']['role'];
    $_SESSION['associatedEntity'] = $result['data']['associatedEntity'];

    // Regenerate session ID for security
    session_regenerate_id();

    // Return a JSON response indicating success and redirect URL
    echo json_encode(array('success' => true, 'redirect' => 'mainPage.php'));
  } else {
    // Return a JSON response indicating failure and an error message
    echo json_encode(array('success' => false, 'message' => $result['message']));
  }

  exit();
} else {
  // Return a JSON response for cases where no data is received
  echo json_encode(array('success' => false, 'message' => 'No data received'));
}
