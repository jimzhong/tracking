<?php

require_once "location.inc.php";

header('Content-type: application/json');

if (!empty($_GET['device_id']))
{
    $loc = get_latest_location_by_device_id($_GET['device_id']);
    echo json_encode(["data" => $loc]);
}
else {
    echo json_encode(["error" => "device_id is required"]);
}

 ?>
