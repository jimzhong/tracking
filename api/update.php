<?php

require_once "location.inc.php";
require_once "rule.inc.php";
require_once "device.inc.php";
require_once "alert.inc.php";

function convert_units($data)
{
    $data['lat'] *= 1e-7;
    $data['lon'] *= 1e-7;
    $data['speed'] *= 1e-3 * 3.6;
    $data['heading'] *= 1e-5;
    $data['height'] *= 1e-3;
    return $data;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['data']) && !empty($_POST['devaddr']))
{
    $binary_data = base64_decode($_POST['data']);
    if ($binary_data === false)
    {
        die("Error decoding");
    }
    $data = unpack('Chour/Cmin/Csec/Cfixtype/llon/llat/lheight/lspeed/lheading/Cflags/Cbattery/CnumSV', $binary_data);

    $data = convert_units($data);

    var_dump($data);

    $device = get_device_by_devaddr($_POST['devaddr']);

    if ($device)
    {
        $location_id = insert_location($device['id'], $data);
        if ($data['flags'] == 1)
        {
            insert_alert($device.id, $location_id, "位移报警");
        }
        check_location($location_id);
    } else {
        echo "Device does not exist.";
    }
}

 ?>
