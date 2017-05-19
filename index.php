<html>

<head>
    <title> 概览 </title>
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

        AMapUI.setDomLibrary($);


        function draw_point(lat, lon, title, body)
        {
            AMapUI.loadUI(['overlay/SimpleInfoWindow'], function(SimpleInfoWindow) {

                var marker = new AMap.Marker({
                    position: [lon, lat],
                    icon: "static/mark.png",
                    map: map,
                });

                var infoWindow = new SimpleInfoWindow({
                    infoTitle: title,
                    infoBody: body,
                    offset: new AMap.Pixel(0, -31)
                });

                function openInfoWindow() {
                    infoWindow.open(map, marker.getPosition());
                }

                AMap.event.addListener(marker, 'click', function() {
                    openInfoWindow();
                });

                openInfoWindow();
            });
        }

        $.getJSON("/api/devices.php", function (data) {
            data.data.forEach(function (device) {
                console.log(device);
                $.getJSON("/api/latest_location.php", {device_id: device.id}, function (data) {
                    if(data.data)
                    {
                        var loc = data.data;
                        var corrected_loc = GPS.gcj_encrypt(loc.lat, loc.lon);
                        var body = sprintf("<ul><li>经度: %.6f</li><li>纬度: %.6f</li><li>定位时刻: %s</li><li>车速: %.2f km/h</li><li>航向: %.0f 度</li><li>电压: %.1f V</li></ul>", loc.lat, loc.lon, loc.sampled_at, loc.speed, loc.heading, loc.battery);
                        draw_point(corrected_loc.lat, corrected_loc.lon, device.name, body);
                    }
                })});
        })
    </script>

</body>

</html>
