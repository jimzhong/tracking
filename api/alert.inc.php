<?php

require_once "db.php";


function get_alerts_by_device_id($id, $limit = 20)
{
    global $pdo;
    $stmt = $pdo->prepare('SELECT * FROM alerts WHERE device_id = ? ORDER BY created_at DESC LIMIT ?');
    $stmt->execute([$id, $limit]);

    return $stmt->fetchAll();
}

function get_all_alerts($limit = 100)
{
    global $pdo;
    $stmt = $pdo->prepare('SELECT * FROM alerts ORDER BY created_at DESC LIMIT ?');
    $stmt->execute([$limit]);

    return $stmt->fetchAll();
}


function insert_alert($device_id, $type, $location_id, $rule_id)
{
    global $pdo;
    $stmt = $pdo->prepare('INSERT INTO alerts (type, device_id, location_id, rule_id)
        VALUES (?, ?, ?, ?)');
    $stmt->execute([$type, $device_id, $location_id, $rule_id]);
}


?>
