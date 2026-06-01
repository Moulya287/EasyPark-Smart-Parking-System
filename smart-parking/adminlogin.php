<?php
$servername = "localhost";
$username = "root";
$password = "mouman321";
$dbname = "easypark";

// Connect to database
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$venue = $_POST['venueName'];
$email = $_POST['email'];
$pass = $_POST['password'];

$sql = "SELECT * FROM venue_admins WHERE venue_name=? AND email=? AND password=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $venue, $email, $pass);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
  session_start();
  $_SESSION['venue_name'] = $venue;
  header("Location: admin-dashboard.php");
  exit();
} else {
  echo "
<!DOCTYPE html>
<html lang='en'>
<head>
<meta charset='UTF-8'>
<meta name='viewport' content='width=device-width, initial-scale=1.0'>
<title>Login Error - EasyPark</title>
<style>
  body {
    background-color: #f5f5f5;
    font-family: 'Arial', sans-serif;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    height: 100vh;
  }
  .error-box {
    background-color: #ffffff;
    border-radius: 12px;
    padding: 40px 50px;
    text-align: center;
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    max-width: 500px;
  }
  h2 {
    color: #e74c3c;
    margin-bottom: 20px;
  }
  p {
    color: #333;
    font-size: 18px;
    margin-bottom: 30px;
  }
  a {
    text-decoration: none;
    background-color: #1e90ff;
    color: white;
    padding: 12px 25px;
    border-radius: 6px;
    font-weight: bold;
    transition: background 0.3s ease;
  }
  a:hover {
    background-color: #187bcd;
  }
  img {
    height: 100px;
    margin-bottom: 20px;
  }
</style>
</head>
<body>
  <div class='error-box'>
    <img src='Screenshot_2025-10-06_160522-removebg-preview.png' alt='EasyPark Logo'>
    <h2>Access Denied</h2>
    <p>Invalid credentials or this venue has not yet been set up for Smart Parking.</p>
    <a href='adminlogin.html'>Go Back</a>
  </div>
</body>
</html>
";
}
$conn->close();
?>
