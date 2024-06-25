<?php
require_once("vendor/autoload.php");

use Minishlink\WebPush\Subscription;
use Minishlink\WebPush\WebPush;

$db_host = "localhost";
$db_user = "root";
$db_pass = "";
$db_name = "service_worker";

$mysqli = new mysqli($db_host, $db_user, $db_pass, $db_name);

if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
    exit();
}

$vapid_config_keys = json_decode(file_get_contents('vapid_keys.json'), true);

if ($vapid_config_keys === null) {
    throw new InvalidArgumentException('Invalid vapid keys');
}

$auth = [
    'VAPID' => [
        'subject' => 'mailto:contact@example.com',
        'publicKey' => $vapid_config_keys['publicKey'],
        'privateKey' => $vapid_config_keys['privateKey'],
    ],
];

$web_push = new WebPush($auth);

$random_title = bin2hex(random_bytes(16));
$random_body = bin2hex(random_bytes(64));
$default_icon = 'https://picsum.photos/200';

function get_postvalue($key, $default) {
    return isset($_POST[$key]) ? (empty($_POST[$key]) ? $default : $_POST[$key]) : $default;
}

$title = get_postvalue('title', $random_title);
$body = get_postvalue('body', $random_body);
$icon = get_postvalue('attachment', $default_icon);

$result = $mysqli->query("SELECT `endpoint`, `p256dh`, `auth` FROM `subscriptions`");

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $subscription = Subscription::create([
            'endpoint' => $row['endpoint'],
            'keys' => [
                'p256dh' => $row['p256dh'],
                'auth' => $row['auth'],
            ],
        ]);

        $payload = json_encode([
            'title' => $title,
            'body' => $body,
            'icon' => $icon,
            'url' => './?title=' . urlencode($title) . '&message=' . urlencode($body) . '&icon=' . urlencode($icon) . '',
        ]);

        $web_push->queueNotification($subscription, $payload);
    }
}

foreach ($web_push->flush() as $report) {
    $endpoint = $report->getRequest()->getUri()->__toString();
    if ($report->isSuccess()) {
        echo "Message sent successfully for subscription {$endpoint}.<br>";
    } else {
        echo "Message failed to sent for subscription {$endpoint}: {$report->getReason()}<br>";
    }
}

$mysqli->close();

echo "<script>setTimeout(function() { window.location.href = './'; }, 1000);</script>";
