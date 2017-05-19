<?php

require_once "api/device.inc.php";
require_once "api/location.inc.php";


$devices = get_all_devices();

if (!empty($_GET['device_id']) && !empty($_GET['start']) && !empty($_GET['end']))
{
    $dev = get_device_by_id($_GET['device_id']);
    $loc = get_location_history_by_device_id($_GET['device_id'], $_GET['start'], $_GET['end']);
}

?>

<html>

<head>
    <title> 历史轨迹 </title>
    <link rel="stylesheet" type="text/css" href="/static/jquery.datetimepicker.min.css">
    <link href="/static/bootstrap.min.css" rel="stylesheet">
    <script src="/static/jquery.min.js"></script>
    <script src="/static/convert.js"></script>
    <script src="/static/jquery.datetimepicker.full.min.js"></script>
    <script src="https://webapi.amap.com/maps?v=1.3&key=bbbf7cd7130e7699860c374dd91e8855"></script>
    <script src="https://webapi.amap.com/ui/1.0/main.js"></script>

    <style>

    </style>
</head>

<body>

    <div class="container">

        <?php include "navbar.php"; ?>
        <form class="form-inline" method="get">
            <div class="form-group">
                <label>车辆</label>
                <select class="form-control" name="device_id" required>
                    <?php foreach ($devices as $device): ?>
                        <option value="<?php echo $device['id'] ?>">
                            <?php echo $device['name'] ?>
                        </option>
                    <?php endforeach ?>
                </select>
            </div>
            <div class="form-group">
                <label>开始时间</label>
                <input class="form-control" name="start" id="start-time-picker" type="text" required="true">
            </div>
            <div class="form-group">
                <label>结束时间</label>
                <input class="form-control" name="end" id="end-time-picker" type="text" required="true">
            </div>
            <input type="submit" class="btn btn-primary" value="提交">
        </form>

        <?php if (isset($loc)): ?>
            <h4 class="text-center" style="margin: 30px"><?php echo $dev['name'] ?>在<?php echo $_GET['start'] ?>至<?php echo $_GET['end'] ?>的行驶轨迹</h4>
        <?php endif ?>

        <div id="map_container" class="container" style="height:500px">
        </div>
    </div>

    <script>
        $('#start-time-picker').datetimepicker();
        $('#end-time-picker').datetimepicker();

        var map = new AMap.Map('map_container',{zoom: 13, center: [120.12,30.28]});

        AMap.plugin(['AMap.ToolBar','AMap.Scale','AMap.OverView'], function(){
            map.addControl(new AMap.ToolBar());
            map.addControl(new AMap.Scale());
        });

    <?php
    if (isset($loc))
    {
        echo "var loc = ".json_encode($loc);
    }
    ?>

        if (typeof loc != 'undefined')
        {
            path = [];
            for (var i = 0; i < loc.length; i++)
            {
                // console.log(loc[i]);
                corrected_loc = GPS.gcj_encrypt(loc[i].lat, loc[i].lon);
                path.push([corrected_loc.lon, corrected_loc.lat]);
                /*var marker = new AMap.Marker({
                    position: [corrected_loc.lon, corrected_loc.lat],
                    map: map,
                    icon: "static/dot.png",
                    title: loc[i].sampled_at
                });*/
            }
            var line = new AMap.Polyline({
                map: map,
                path: path,
                strokeColor: "#F00",
                strokeWeight: 3
            });
            map.setFitView();
        }

    </script>

</body>

</html>
