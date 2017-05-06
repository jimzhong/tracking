<?php

require_once "device.inc.php";



header('Content-type: application/json');
echo json_encode(["data" => get_all_devices()]);


 ?>
