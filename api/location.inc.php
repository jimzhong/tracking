<?php

require_once "db.php";

function insert_location($device_id, $data)
{
    global $pdo;

    $sample_time = new DateTime();
    $sample_time->setTime($data['hour'], $data['min'], $data['sec']);
    if ($sample_time->getTimestamp() > (time() + 1800))
    {
        // sample time cannot be larger than current time + tolerance
        // must minus a day
        $interval = new DateInterval("P1D");
        $sample_time->sub($interval);
    }

    $stmt = $pdo->prepare('INSERT INTO locations (device_id, lat, lon, speed, heading, battery, sampled_at, created_at)
        VALUES (:device_id, :lat, :lon, :speed, :heading, :battery, :sampled_at, :created_at)');


    $stmt->execute([
        "device_id" => $device_id,
        'lat' => $data['lat'],
        'lon' => $data['lon'],
        'speed' => $data['speed'],
        'heading' => $data['heading'],
        'battery' => $data['battery'],
        'sampled_at' => $sample_time->format('Y-m-d H:i:s'),
        'created_at' => date('Y-m-d H:i:s'),
    ]);
    return $pdo->lastInsertId();
}

function get_location_by_id($id)
{
    global $pdo;
    $stmt = $pdo->prepare('SELECT * FROM locations WHERE id = ?');
    $stmt->execute([$id]);
    return $stmt->fetch();
}

function get_location_history_by_device_id($device_id, $start, $end, $limit=1000)
{
    global $pdo;
    $stmt = $pdo->prepare('SELECT * FROM locations WHERE device_id = ?
        AND sampled_at BETWEEN ? AND ? ORDER BY sampled_at DESC LIMIT ?');
    $stmt->execute([$device_id, $start, $end, $limit]);
    return $stmt->fetchAll();
}

function get_latest_location_by_device_id($device_id, $freshness = 3600)
{
    global $pdo;
    $stmt = $pdo->prepare('SELECT * FROM locations WHERE device_id = ? AND sampled_at >= ?
        ORDER BY sampled_at DESC LIMIT 1');
    $stmt->execute([$device_id, date('Y-m-d H:i:s', time() - $freshness)]);
    if ($row = $stmt->fetch())
    {
        return $row;
    }
    return null;
}

?>
