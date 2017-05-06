<html>

<head>
    <title> 概览 </title>
    <link href="https://cdn.bootcss.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.bootcss.com/jquery/3.2.1/jquery.min.js"></script>
    <script type="text/javascript" src="https://webapi.amap.com/maps?v=1.3&key=bbbf7cd7130e7699860c374dd91e8855"></script>

    <style>

    </style>
</head>

<body>

    <div class="container">

        <?php include "navbar.php"; ?>

        <div id="map_container" class="container" style="height:500px">
        </div>

        <button id="setFitView" class="btn"/>自适应显示</button>
    </div>

    <script>

        var map = new AMap.Map('map_container',{zoom: 13, center: [120.12,30.28]});

        AMap.plugin(['AMap.ToolBar','AMap.Scale','AMap.OverView'], function(){
            map.addControl(new AMap.ToolBar());
            map.addControl(new AMap.Scale());
        });

        AMap.event.addDomListener(document.getElementById('setFitView'), 'click', function() {map.setFitView();});

        function draw_point(lat, lon, label, desc)
        {
            marker = new AMap.Marker({
                position: [lon, lat],
                icon: "static/mark.png",
                map: map,
                title: label
            });
        }

        $.getJSON("/api/devices.php", function (data) {
            data.data.forEach(function (device) {
                console.log(device);
                $.getJSON("/api/latest_location.php", {device_id: device.id}, function (data) {
                    if(data.data)
                    {
                        var loc = data.data;
                        draw_point(loc.lat, loc.lon, device.name, loc.sampled_at);
                    }
                })});
        })
    </script>

</body>

</html>
