<?php
$db_host = "localhost";
$db_user = "root";
$db_pass = "";
$db_name = "service_worker";

$mysqli = new mysqli($db_host, $db_user, $db_pass, $db_name);

if ($mysqli->connect_errno) {
  echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
  exit();
}

$data = file_get_contents('php://input');
$subscription = json_decode($data, true);

if (json_last_error() === JSON_ERROR_NONE) {
  $endpoint = $subscription['endpoint'];
  $keys = $subscription['keys'];
  $p256dh = $keys['p256dh'];
  $auth = $keys['auth'];

  $stmt = $mysqli->prepare("SELECT `id` FROM `subscriptions` WHERE `endpoint` = ?");
  $stmt->bind_param("s", $endpoint);
  $stmt->execute();
  $stmt->store_result();

  if ($stmt->num_rows > 0) {
    echo json_encode(["success" => false, "error" => "Subscription already exists"]);
  } else {
    $stmt->close();
    $stmt = $mysqli->prepare("INSERT INTO subscriptions (`endpoint`, `p256dh`, `auth`) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $endpoint, $p256dh, $auth);

    if ($stmt->execute()) {
      echo json_encode(["success" => true]);
    } else {
      echo json_encode(["success" => false, "error" => $stmt->error]);
    }
  }

  $stmt->close();
} else {
  echo json_encode(["success" => false, "error" => "Invalid JSON"]);
}

$mysqli->close();
