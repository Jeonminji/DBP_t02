<?php
// 쿼리 실행문, 중복 사용되어서 함수로 분리
    function execQuery($link, $gu, $dong, $minB, $maxB, $minA, $maxA) {
        if($dong == "전체") { # 동 선택 안함
            $query = "SELECT 아파트, CONCAT(ROUND(ANY_VALUE(AVG(거래금액))/10000, 1), '억') AS 거래금액, CONCAT(법정동, ' ', ANY_VALUE(지번)) AS 주소, ANY_VALUE(ROUND(전용면적/3.305785)) AS 평수, ANY_VALUE(전용면적) AS 전용면적, TRUNCATE(ANY_VALUE(전용면적), 2) AS 면적, ANY_VALUE(법정동) AS 법정동, ANY_VALUE(지번) AS 지번, '{$gu}' AS 지역구 
            FROM {$gu}
            WHERE ROUND(전용면적) > ROUND({$minA}*3.305785) AND ROUND(전용면적) < ROUND({$maxA}*3.305785) 
                        AND 년 = YEAR(sysdate()) 
                        AND 월 IN (MONTH(sysdate()) - 3, MONTH(sysdate()) - 2, MONTH(sysdate()) - 1, MONTH(sysdate())) # 3개월치(9-12)
                        AND 거래금액 > {$minB}*10000 AND 거래금액 < {$maxB}*10000
            GROUP BY 아파트, 법정동 
            ORDER BY ROUND(AVG(거래금액)/10000, 1) DESC";
        } else { # 동까지 선택
            $query = "SELECT 아파트, CONCAT(ROUND(ANY_VALUE(AVG(거래금액))/10000, 1), '억') AS 거래금액, CONCAT(법정동, ' ', ANY_VALUE(지번)) AS 주소, ANY_VALUE(ROUND(전용면적/3.305785)) AS 평수, ANY_VALUE(전용면적) AS 전용면적, TRUNCATE(ANY_VALUE(전용면적), 2) AS 면적, ANY_VALUE(법정동) AS 법정동, ANY_VALUE(지번) AS 지번, '{$gu}' AS 지역구 
            FROM {$gu}
            WHERE ROUND(전용면적) > ROUND({$minA}*3.305785) AND ROUND(전용면적) < ROUND({$maxA}*3.305785) 
                        AND 년 = YEAR(sysdate()) 
                        AND 월 IN (MONTH(sysdate()) - 3, MONTH(sysdate()) - 2, MONTH(sysdate()) - 1, MONTH(sysdate())) # 3개월치(9-12)
                        AND 거래금액 > {$minB}*10000 AND 거래금액 < {$maxB}*10000
                        AND 법정동 = '{$dong}'
            GROUP BY 아파트, 법정동 
            ORDER BY ROUND(AVG(거래금액)/10000, 1) DESC";
        }

    $result = mysqli_query($link, $query);
    return $result;
}

function WriteAddress($link, $gu, $dong, $minB, $maxB, $minA, $maxA){ 
    $result = execQuery($link, $gu, $dong, $minB, $maxB, $minA, $maxA);
    $apt_address = '';
    $num1 = 0;
    while($row = mysqli_fetch_array($result)) {
        $num1 += 1;
        if (mysqli_num_rows($result) == $num1) {
            $apt_address .= '["'.$row['법정동'].'", "'.$row['지번'].'", "'.$row['아파트'].'", "'.$row['거래금액'].'", "'.$row['전용면적'].'", "'.$row['지역구'].'"]';
        }
        else {
            $apt_address .= '["'.$row['법정동'].'", "'.$row['지번'].'", "'.$row['아파트'].'", "'.$row['거래금액'].'", "'.$row['전용면적'].'", "'.$row['지역구'].'"], ';
        } // 0 -> 동, 1 -> 지번, 2 -> 아파트명, 3-> 거래금액, 4 -> 전용면적, 5 -> 지역구
    }
    echo $apt_address;
} 
// 메인
    $db_host = "localhost"; 
    $db_user = "aptinfo"; 
    $db_passwd = "epqpvmf#2";
    $db_name = "aptinfo";
    header("Content-Type:text/html;charset=utf-8");
    $link = mysqli_connect($db_host, $db_user, $db_passwd, $db_name);

    $gu = mysqli_real_escape_string($link, $_POST['gu']);
    $dong = mysqli_real_escape_string($link, $_POST['dong']);
    $minB = mysqli_real_escape_string($link, $_POST['minBudget']);
    $maxB = mysqli_real_escape_string($link, $_POST['maxBudget']);
    $minA = mysqli_real_escape_string($link, $_POST['minA']);
    $maxA = mysqli_real_escape_string($link, $_POST['maxA']);
    
    $result = execQuery($link, $gu, $dong, $minB, $maxB, $minA, $maxA);
    $rows = mysqli_num_rows($result);
    $list = '';
    $alert = '';
    $path = '';
    if ($rows == 0) { //추가 : 사용자가 입력한 조건에 만족하는 아파트가 없을 경우 출력
        $alert = ' <script>
        alert("해당하는 조건의 아파트가 존재하지 않습니다.");
        document.location.href="index.php";
    </script>';
    } else{
        while($row = mysqli_fetch_array($result)){
            $list .= '<tr><td>';
            $list .= '<h3><p onclick="movePos(\''.$row['주소'].'\')" style="cursor:pointer;">'.$row['아파트'].'</p></h3>';
            $list .= '</td><td rowspan="3"><h2><a href="detail.php?name='.$row['아파트'].'&ac='.$row['전용면적'].'&gu='.$gu.'&지번='.$row['지번'].'&법정동='.$row['법정동'].'">'.$row['거래금액'].'</h2></td></tr>';
            $path = "images/{$gu}/{$row['법정동']}/{$row['아파트']}/{$row['면적']}.jpg";
            if(file_exists($path)){
                $list .= '<tr><td><a id="apt" href="images/'.$gu.'/'.$row['법정동'].'/'.$row['아파트'].'/'.$row['면적'].'.jpg">'.$row['평수'].'평 도면보기</td></tr>';
            }else{
                $list .= '<tr></tr>';
            }
            $list .= '<tr><td style="font-size:15px;">'.$row['주소'].'</td></tr>'; 
        }
    }
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title> DBP final </title>
    <link rel="stylesheet" href="./css/style.css?after">
    <link rel="stylesheet" href="./css/colorbox.css">
    <script src="./js/jquery.colorbox.js"></script>
    <script src="./js/jquery-3.3.1.js"></script>
    <?= $alert ?>
</head>
<style>
@import url(http://fonts.googleapis.com/earlyaccess/amstelvaralpha.css);
    div.container {
        width: 100%;
        height: 100%;
        position: relative;
    }
    div.right {
        width: 25%;
        float: right;
    }
    div.left {
        float: left;
        margin: 0 auto;
    }
    html, body {
        width: 100%;
        height: 100%;
        padding: 0px;
        margin: 0px;
    }
</style>
<body>
    <div class = "container">
        <div class = "right">
            <table style="padding-left:5%; padding-right:5%;">
                <?= $list ?>
            </table>
        </div>
        <div class="left" id="map" style="width:75%; height:100%; position: fixed;"></div>
    </div>
    <script>
       // 라이트박스
       $(document).ready(function () {
           $('a').colorbox();
       });
    </script>
    <script type="text/javascript"
        src="//dapi.kakao.com/v2/maps/sdk.js?appkey=2ee0eb182138378558cb4db1be2a8a3b&libraries=services"></script>
    <script>
    var listData = [
        <?php 
            WriteAddress($link, $gu, $dong, $minB, $maxB, $minA, $maxA); 
        ?>
    ];

    // 맵을 넣을 div 
    var container = document.getElementById('map');
    var options = {
        center: new kakao.maps.LatLng(35.95, 128.25),
        level: 4
    };

    // 맵 표시 
    var map = new kakao.maps.Map(container, options);

    // 일반 지도와 스카이뷰로 지도 타입을 전환할 수 있는 지도타입 컨트롤을 생성
    var mapTypeControl = new kakao.maps.MapTypeControl();
    map.addControl(mapTypeControl, kakao.maps.ControlPosition.TOPRIGHT);

    // 지도 확대 축소를 제어할 수 있는 줌 컨트롤을 생성
    var zoomControl = new kakao.maps.ZoomControl();
    map.addControl(zoomControl, kakao.maps.ControlPosition.RIGHT);

    // 주소 -> 좌표 변환 라이브러리 
    var geocoder = new kakao.maps.services.Geocoder();

    // 전체 마커 한 눈에 보기 위한 객체 생성
    var bounds = new kakao.maps.LatLngBounds();

    // foreach loop 
    listData.forEach(function(addr, index) {
        geocoder.addressSearch(addr[0] + addr[1], function(result, status) {
            if (status === kakao.maps.services.Status.OK) {
                var coords = new kakao.maps.LatLng(result[0].y, result[0].x);

                var dong = addr[0];
                var street_num = addr[1];
                var name = addr[2];
                var size = addr[4];
                var gu = addr[5];

                var content = '<div class="customoverlay">' +
                    '  <a href="detail.php?name=' + name + '&ac=' + size  + '&gu=' + gu + '&지번=' + street_num + '&법정동=' + dong + '">' +
                    '    <span class="title">' + addr[2] + ' <p style="color:#5882FA; display:inline">' + addr[3] + '</p></span>' +
                    '  </a>' +
                    '</div>'

                var customMarker = new kakao.maps.CustomOverlay({
                    map: map,
                    position: coords,
                    content: content, 
                });

                // 마커를 지도에 표시
                customMarker.setMap(map);
                // 마커 좌표값 설정
                bounds.extend(new kakao.maps.LatLng(result[0].y, result[0].x));
                map.setBounds(bounds, 0, 0, 0, 0); //마커 한눈에 보기
            }
        });
    });

    function movePos(address){
        geocoder.addressSearch(address,  function(result, status) {
            if (status === kakao.maps.services.Status.OK) {
                var moveLocation = new kakao.maps.LatLng(result[0].y, result[0].x);
                map.setCenter(moveLocation);
                map.setLevel(2);
            }
        });
    }
 </script>
</body>
</html>