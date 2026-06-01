<?php

header("Content-Type: application/json");


$esp32_url = "http://10.180.21.210/status";

$response = @file_get_contents($esp32_url);

if ($response === FALSE) {
  echo json_encode([
    "slot1" => "disconnected",
    "slot2" => "disconnected"
  ]);
} else {
  echo $response; 
}
?>
