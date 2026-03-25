<?php
require_once __DIR__ . '/../config/database.php';

$db = Database::getConnection();

echo "Generating normal logs...\n";

// ---------- NORMAL DATA ----------
for ($i = 0; $i < 5000; $i++) {
    $userId = rand(2, 50);
    $action = ['login','logout','view','click'][array_rand(['a','b','c','d'])];
    $ip = "192.168.1." . rand(1, 100);

    $stmt = $db->prepare("
        INSERT INTO activity_logs 
        (user_id, action, metadata, ip_address, user_agent, created_at)
        VALUES (?, ?, ?, ?, ?, NOW() - INTERVAL FLOOR(RAND()*60) MINUTE)
    ");

    $stmt->execute([
        $userId,
        $action,
        json_encode(["type" => "normal"]),
        $ip,
        "Mozilla/5.0"
    ]);
}

echo "Normal logs inserted \n";


// ---------- HIGH ACTIVITY ANOMALY ----------
echo "Generating high activity anomaly...\n";

$userId = 1;

for ($i = 0; $i < 15; $i++) {
    $stmt = $db->prepare("
        INSERT INTO activity_logs 
        (user_id, action, metadata, ip_address, user_agent, created_at)
        VALUES (?, 'click', ?, '192.168.1.1', 'Mozilla/5.0', NOW())
    ");

    $stmt->execute([
        $userId,
        json_encode(["type" => "high_activity"])
    ]);
}

echo "High activity anomaly created \n";


// ---------- MULTI-IP ANOMALY ----------
echo "Generating multi-IP anomaly...\n";

$userId = 2;
$ips = ["10.0.0.1", "10.0.0.2", "10.0.0.3"];

foreach ($ips as $ip) {
    $stmt = $db->prepare("
        INSERT INTO activity_logs 
        (user_id, action, metadata, ip_address, user_agent, created_at)
        VALUES (?, 'login', ?, ?, 'Mozilla/5.0', NOW())
    ");

    $stmt->execute([
        $userId,
        json_encode(["type" => "multi_ip"]),
        $ip
    ]);
}

echo "Multi-IP anomaly created \n";

echo "Done! Now test /api/anomalies.php\n";