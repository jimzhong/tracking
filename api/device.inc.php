<?php

require_once "db.php";

function get_device_by_id($id)
{
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM devices WHERE id = ?");
    $stmt->execute([$id]);
    $row = $stmt->fetch();
    if ($row == false)
        return null;
    else {
        return $row;
    }
}

function get_device_by_devaddr($devaddr)
{
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM devices WHERE devaddr = ?");
    $stmt->execute([$devaddr]);
    $row = $stmt->fetch();
    if ($row == false)
        return null;
    else {
        return $row;
    }
}

function get_all_devices()
{
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM devices ORDER BY id");
    $stmt->execute([]);
    return $stmt->fetchAll();
}

 ?>
