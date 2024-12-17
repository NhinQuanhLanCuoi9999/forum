<?php
session_start();
include('../app/network/php.php');
?>
    <title>IP Address and Location</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <link rel="icon" href="favicon.png" type="image/png">
    <link rel="stylesheet" type="text/css" href="/app/network/styles.css">
</head>
<body>

    <div class="container">
        <h2>Thông tin cấu hình mạng</h2>
        <div class="ip-info">
            <p>IPv6: <span id="ipv6">Loading...</span></p>
            <p>IPv4: <span id="ipv4">Loading...</span></p>
            <span class="show-more" onclick="toggleExtraInfo()" id="showMoreBtn">Xem thêm</span>
            <div class="extra-info" id="extraInfo">
                <p>ASN: <span id="asn">Loading...</span></p>
                <p>ISP: <span id="isp">Loading...</span></p>
                <p>Services: <span id="services">None detected</span></p>
                <p>Quốc gia: <span id="country">Loading...</span></p>
                <p>Bang/Vùng lãnh thổ: <span id="region">Loading...</span></p>
                <p>Thành phố: <span id="city">Loading...</span></p>
                <p>Vĩ độ: <span id="latitude">Loading...</span></p>
                <p>Kinh độ: <span id="longitude">Loading...</span></p>
            </div>
        </div>
        <div id="map"></div>
        <div id="timer">Phiên còn lại: <span id="time">30</span> giây</div>
    </div>

    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <script src = /app/network/Handle.js></script>

</body>
</html>  