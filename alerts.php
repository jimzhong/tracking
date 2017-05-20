<?php

require_once "api/alert.inc.php";

if (!empty($_GET['device_id']))
{
    $alerts = get_alerts_by_device_id($_GET['device_id']);
}
else
{
    $alerts = get_all_alerts();
}

 ?>

<html>

<head>
    <title>警报</title>
    <link href="/static/bootstrap.min.css" rel="stylesheet">
    <script src="/static/jquery.min.js"></script>
    <script src="/static/convert.js"></script>
    <script src="/static/sprintf.js"></script>
    <script type="text/javascript" src="https://webapi.amap.com/maps?v=1.3&key=bbbf7cd7130e7699860c374dd91e8855"></script>
    <script src="https://webapi.amap.com/ui/1.0/main.js"></script>
    <style>

    </style>
</head>

<body>

    <div class="container">

        <?php include "navbar.php"; ?>

        <table class="table">
            <thead>
                <tr><th>车辆名称</th><th>警报类型</th><th>时刻</th><th>经度</th><th>纬度</th><tr>
            </thead>
            <tbody>
                <?php foreach ($alerts as $alert): ?>
                    <tr>
                        <td><?php echo $alert.name ?></td>
                        <td><?php echo $alert.type ?></td>
                        <td><?php echo $alert.created_at ?></td>
                        <td><?php echo $alert.lon ?></td>
                        <td><?php echo $alert.lon ?></td>
                    </tr>
                <?php endforeach ?>
                <tr>
            </tbody>
        </table>
    </div>


</body>

</html>
