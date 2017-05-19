<?php

require_once "db.php";


function get_alerts_by_device_id($id, $limit = 200)
{
    global $pdo;
    $stmt = $pdo->prepare('SELECT * FROM alerts JOIN devices ON device_id=devices.id JOIN locations ON location_id=locations.id WHERE device_id = ? ORDER BY alerts.id DESC LIMIT ?');
    $stmt->execute([$id, $limit]);

    return $stmt->fetchAll();
}

function get_all_alerts($limit = 1000)
{
    global $pdo;
    $stmt = $pdo->prepare('SELECT * FROM alerts JOIN devices ON device_id=devices.id JOIN locations ON location_id=locations.id ORDER BY alerts.id DESC LIMIT ?');
    $stmt->execute([$limit]);

    return $stmt->fetchAll();
}

function insert_alert($device_id, $location_id, $type)
{
    global $pdo;
    $stmt = $pdo->prepare('INSERT INTO alerts (type, device_id, location_id)
        VALUES (?, ?, ?)');
    $stmt->execute([$type, $device_id, $location_id]);
}


?>
