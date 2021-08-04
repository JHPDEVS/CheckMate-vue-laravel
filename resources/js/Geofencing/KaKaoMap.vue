<template>
    <input type="text" :value="distance"> <label for="">미터 떨어져있음</label>
                            <div v-if="counts>0">
                            <attend-form/>
                            </div>
                            <div v-else>
                                <div role="alert">
  <div class="bg-red-500 text-white font-bold rounded-t px-4 py-2">
    에러
  </div>
  <div class="border border-t-0 border-red-400 rounded-b bg-red-100 px-4 py-3 text-red-700">
    <p>출석체크를 할려면 100m 이내에 접근하세요</p>
  </div>
</div>
                                </div>

    <div id="map" style="width:100%;height:500px"></div>
    <h1>hello</h1>

</template>
<script>
    import AttendForm from '@/Geofencing/AttendForm'
    export default {
        data() {
            return {
                currentTab: 'Home',
                tabs: ['Home', 'Posts'],
                counts: '0',
                distance: '1000',
            }
        },
        computed: {
            currentTabComponent() {
                return 'tab-' + this.currentTab.toLowerCase()
            }
        },
        mounted() {
            window.kakao && window.kakao.maps ? this.initMap() : this.addScript();
            if (this.polylineLength <= 400) {
                alert("hello");
            }
        },
        methods: {
            initMap() {
                var mapContainer = document.getElementById('map'), // 지도를 표시할 div 
                    mapOption = {
                        center: new kakao.maps.LatLng(33.450701, 126.570667), // 지도의 중심좌표
                        level: 5 // 지도의 확대 레벨 
                    };

                var map = new kakao.maps.Map(mapContainer, mapOption); // 지도를 생성합니다

                // HTML5의 geolocation으로 사용할 수 있는지 확인합니다 
                if (navigator.geolocation) {

                    // GeoLocation을 이용해서 접속 위치를 얻어옵니다
                    navigator.geolocation.getCurrentPosition((position) => {


                        var lat = position.coords.latitude, // 위도
                            lon = position.coords.longitude; // 경도

                        var startlat = 35.896794008573096;
                        var startlon = 128.62092071087199;

                        var startPosition = new kakao.maps.LatLng(startlat,
                                startlon), // 마커가 표시될 위치를 geolocation으로 얻어온 좌표로 생성합니다
                            check = '<div style="padding:5px;">영진전문대 본관</div>'; // 인포윈도우에 표시될 내용입니다

                        var locPosition = new kakao.maps.LatLng(lat,
                                lon), // 마커가 표시될 위치를 geolocation으로 얻어온 좌표로 생성합니다
                            message = '<div>현재위치</div>'; // 인포윈도우에 표시될 내용입니다

                        // 마커와 인포윈도우를 표시합니다
                        // 마커를 생성합니다
                        var marker = new kakao.maps.Marker({
                            map: map,
                            position: locPosition
                        });


                        var newMarker = new kakao.maps.Marker({
                            map: map,
                            position: startPosition
                        });

                        var iwContent = message, // 인포윈도우에 표시할 내용
                            iwRemoveable = false;

                        // 인포윈도우를 생성합니다
                        var infowindow = new kakao.maps.InfoWindow({
                            content: check,
                            removable: iwRemoveable
                        });

 var infowindow2 = new kakao.maps.InfoWindow({
                            content: message,
                            removable: false
                        });
                        // 인포윈도우를 마커위에 표시합니다 


                        // 지도 중심좌표를 접속위치로 변경합니다
                        map.setCenter(locPosition);



                        var polyline = new kakao.maps.Polyline({
                            /* map:map, */
                            path: [
                                new kakao.maps.LatLng(lat, lon),
                                new kakao.maps.LatLng(startlat, startlon)
                            ],
                            strokeWeight: 5,
                            strokeColor: '#FF00FF',
                            strokeOpacity: 0.8,
                            strokeStyle: 'dashed'
                        });

                        var circle = new kakao.maps.Circle({
                            center: new kakao.maps.LatLng(startlat, startlon), // 원의 중심좌표 입니다 
                            radius: 50, // 미터 단위의 원의 반지름입니다 
                            strokeWeight: 5, // 선의 두께입니다 
                            strokeColor: '#75B8FA', // 선의 색깔입니다
                            strokeOpacity: 1, // 선의 불투명도 입니다 1에서 0 사이의 값이며 0에 가까울수록 투명합니다
                            strokeStyle: 'dashed', // 선의 스타일 입니다
                            fillColor: '#CFE7FF', // 채우기 색깔입니다
                            fillOpacity: 0.7 // 채우기 불투명도 입니다   
                        });


                        //return getTimeHTML(polyline.getLength());//미터단위로 길이 반환;
                        console.log("길이" + polyline.getLength());
                        var length = polyline.getLength();
                        this.distance = length;
                        polyline.setMap(map);
                        circle.setMap(map);

                        if (polyline.getLength() <= 500) {
                            console.log("출석체크 버튼 생성");
                            infowindow.open(map, newMarker);
                            infowindow2.open(map, marker);
                            this.addAttendButton();
                        }
                    });

                } else { // HTML5의 GeoLocation을 사용할 수 없을때 마커 표시 위치와 인포윈도우 내용을 설정합니다

                    var locPosition = new kakao.maps.LatLng(33.450701, 126.570667),
                        message = 'geolocation을 사용할수 없어요..'


                }
            },

            addScript() {
                const script = document.createElement('script'); /* global kakao */
                script.onload = () => kakao.maps.load(this.initMap);
                script.src =
                    'http://dapi.kakao.com/v2/maps/sdk.js?autoload=false&appkey=dc92402fe9472a39910383183e108f9c';
                document.head.appendChild(script);
            },
            addAttendButton: function () {
                this.counts =1;
            }
        }, components: {
            AttendForm
        }
    }
</script>
