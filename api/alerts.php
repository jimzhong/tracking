<?php

require_once "alert.inc.php";

header('Content-type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'GET')
{
    if (!empty($_GET['device_id']))
    {
        echo json_encode(["data" => get_alerts_by_device_id($_GET['device_id'])]);
    }
    else
    {
        echo json_encode(['data' => get_all_alerts()]);
    }
}

 ?>
