<?php
$servername = "localhost";
$username = "root";
$password = "mouman321";
$dbname = "easypark";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$full_name = $_POST['name'];
$contact   = $_POST['contact'];
$location  = $_POST['location'];
$date      = $_POST['date'];
$time      = $_POST['time'];
$duration  = $_POST['duration']; // in minutes

// Initialize variables
$assigned_slot = null;
$esp_ip = "10.180.21.210"; // ESP32 IP

// ===========================
// 🔹 ONLY SMART PARKING VENUE: NEXON MALL
// ===========================
if (strtolower(trim($location)) == "nexon mall") {
    $slots = ["Slot 1", "Slot 2"];

    $status_json = @file_get_contents("http://$esp_ip/status");
    $live_status = $status_json ? json_decode($status_json, true) : [];

    // user-selected reservation time (future) + duration
    $selected_time = strtotime($time);
    $reservation_end_time = date("H:i:s", strtotime("+$duration minutes", $selected_time));

    foreach ($slots as $slot) {
        // skip physically occupied slot
        $sensor_state = strtolower($live_status[strtolower(str_replace(' ', '', $slot))] ?? "unknown");
        if ($sensor_state == "occupied") continue;

        // check DB for overlapping reservation
        $sql_check = "
            SELECT * FROM reservations
            WHERE location='Nexon Mall' 
              AND date='$date' 
              AND assigned_slot='$slot'
              AND (
                    (time <= '$time' AND ADDTIME(time, SEC_TO_TIME(duration*60)) > '$time')
                 OR (time < '$reservation_end_time' AND ADDTIME(time, SEC_TO_TIME(duration*60)) > '$time')
                 OR (time >= '$time' AND time < '$reservation_end_time')
              )
        ";

        $result = $conn->query($sql_check);
        if ($result->num_rows == 0) {
            $assigned_slot = $slot;
            break;
        }
    }

    if ($assigned_slot) {
        $sql_insert = "
            INSERT INTO reservations (full_name, contact, location, date, time, duration, assigned_slot)
            VALUES ('$full_name', '$contact', '$location', '$date', '$time', '$duration', '$assigned_slot')
        ";
        $conn->query($sql_insert);

        // ==============================
        // 🔥 LED trigger logic
        // ==============================
        date_default_timezone_set('Asia/Kolkata');
        $selected_timestamp = strtotime("$date $time");            
        $reservation_end_timestamp = $selected_timestamp + ($duration * 60); 
        $current_time = time();
        $glow_seconds = $reservation_end_timestamp - $current_time;

        if ($glow_seconds > 0) {
            $glow_minutes = (int) ceil($glow_seconds / 60.0);
            $led_url = ($assigned_slot == "Slot 1")
                ? "http://$esp_ip/slot1/on?duration=$glow_minutes"
                : "http://$esp_ip/slot2/on?duration=$glow_minutes";
            @file_get_contents($led_url);
        }

        $status_msg = "success";
    } else {
        $status_msg = "full";
    }
} 

// ===========================
// 🚫 ALL OTHER VENUES (no smart parking)
// ===========================
else {
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Smart Parking Unavailable - EasyPark</title>
      <style>
        * { margin:0; padding:0; box-sizing:border-box; font-family:'Arial',sans-serif; }
        body { background:#f5f5f5; color:#333; }
        header { background:#1e90ff; color:white; padding:15px 50px; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; }
        .logo-container { display:flex; align-items:center; gap:5px; }
        .logo-container img { height:70px; }
        .logo-container h1 { font-size:36px; font-weight:bold; letter-spacing:2px; }
        nav a { color:white; text-decoration:none; margin-left:25px; font-weight:bold; font-size:18px; }
        nav a:hover { text-decoration:underline; }
        .section { padding:60px 50px; background:white; max-width:700px; margin:40px auto; border-radius:10px; box-shadow:0 4px 10px rgba(0,0,0,0.1); text-align:center; }
        .section h2 { font-size:32px; color:#ff4d4d; margin-bottom:20px; }
        .section p { font-size:18px; margin-bottom:30px; color:#333; }
        .button { display:inline-block; background:#1e90ff; color:white; padding:12px 25px; border-radius:6px; text-decoration:none; font-size:18px; }
        .button:hover { background:#187bcd; }
        footer { background:#1e90ff; color:white; padding:20px 50px; text-align:center; margin-top:60px; }
      </style>
    </head>
    <body>

      <header>
        <div class="logo-container">
          <img src="Screenshot_2025-10-06_160522-removebg-preview.png" alt="EasyPark Logo">
          <h1>EasyPark</h1>
        </div>
        <nav>
          <a href="home.html">Home</a>
          <a href="about.html">About</a>
          <a href="venue.html">Venues</a>
          <a href="adminlogin.html">Admin Login</a>
          <a href="contact.html">Contact</a>
        </nav>
      </header>

      <section class="section">
        <h2>Smart Parking Not Available</h2>
        <p>🚫 Sorry! Smart parking has not yet been set up at 
          <strong><?php echo htmlspecialchars($location); ?></strong>.<br><br>
          Please choose a supported venue such as 
          <strong>Nexon Mall</strong> to make a reservation.
        </p>
        <a href="find-slot.html" class="button">Go Back</a>
      </section>

      <footer>
        &copy; 2025 EasyPark. All rights reserved. | Contact: support@easypark.com
      </footer>

    </body>
    </html>
    <?php
    exit();
}

// ===========================
// NORMAL CONFIRMATION PAGE
// ===========================
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Reservation Status - EasyPark</title>
<style>
* { margin:0; padding:0; box-sizing:border-box; font-family:'Arial',sans-serif; }
body { background:#f5f5f5; color:#333; }
header { background:#1e90ff; color:white; padding:15px 50px; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; }
.logo-container { display:flex; align-items:center; gap:5px; }
.logo-container img { height:70px; }
.logo-container h1 { font-size:36px; font-weight:bold; letter-spacing:2px; }
nav a { color:white; text-decoration:none; margin-left:25px; font-weight:bold; font-size:18px; }
nav a:hover { text-decoration:underline; }
.section { padding:60px 50px; background:white; max-width:700px; margin:40px auto; border-radius:10px; box-shadow:0 4px 10px rgba(0,0,0,0.1); }
.section h2 { font-size:32px; color:#1e90ff; margin-bottom:30px; text-align:center; }
.ticket { border:1px dashed #1e90ff; padding:20px; margin:20px 0; border-radius:8px; background:#f0f8ff; }
.ticket p { margin:10px 0; font-size:18px; }
.ticket strong { color:#1e90ff; }
.button { display:inline-block; background:#1e90ff; color:white; padding:12px 25px; border-radius:6px; text-decoration:none; font-size:18px; margin-top:20px; }
.button:hover { background:#187bcd; }
footer { background:#1e90ff; color:white; padding:20px 50px; text-align:center; margin-top:60px; }
</style>
</head>
<body>

<header>
<div class="logo-container">
<img src="Screenshot_2025-10-06_160522-removebg-preview.png" alt="EasyPark Logo">
<h1>EasyPark</h1>
</div>
<nav>
<a href="home.html">Home</a>
<a href="about.html">About</a>
<a href="venue.html">Venues</a>
<a href="adminlogin.html">Admin Login</a>
<a href="contact.html">Contact</a>
</nav>
</header>

<section class="section">
<h2>Reservation Status</h2>

<?php if($status_msg=="success") { ?>
    <div class="ticket">
        <p><strong>Name:</strong> <?php echo $full_name; ?></p>
        <p><strong>Contact:</strong> <?php echo $contact; ?></p>
        <p><strong>Location:</strong> <?php echo $location; ?></p>
        <p><strong>Date:</strong> <?php echo $date; ?></p>
        <p><strong>Time:</strong> <?php echo $time; ?></p>
        <p><strong>Duration:</strong> <?php echo $duration; ?> minutes</p>
        <p><strong>Assigned Slot:</strong> <?php echo $assigned_slot; ?></p>
    </div>
    <a href="#" onclick="window.print()" class="button">Print / Save Ticket</a>
    <a href="find-slot.html" class="button">Go Back</a>

<?php } elseif($status_msg=="full") { ?>
    <div class="ticket">
        <p>Sorry! ❌ All Nexon Mall slots are currently reserved or occupied.</p>
    </div>
    <a href="find-slot.html" class="button">Go Back</a>
<?php } else { ?>
    <div class="ticket">
        <p>Reservation recorded successfully for <?php echo $location; ?> (no slot tracking required).</p>
    </div>
    <a href="find-slot.html" class="button">Go Back</a>
<?php } ?>

</section>

<footer>
&copy; 2025 EasyPark. All rights reserved. | Contact: support@easypark.com
</footer>

</body>
</html>
