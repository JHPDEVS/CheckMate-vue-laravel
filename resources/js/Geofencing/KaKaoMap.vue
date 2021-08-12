<template>
    <div v-if="isPC<1" class="flex ">
        <div class="py-1"><svg class="fill-current h-6 w-6 text-teal-500 mr-4" xmlns="http://www.w3.org/2000/svg"
                viewBox="0 0 20 20">
                <path
                    d="M2.93 17.07A10 10 0 1 1 17.07 2.93 10 10 0 0 1 2.93 17.07zm12.73-1.41A8 8 0 1 0 4.34 4.34a8 8 0 0 0 11.32 11.32zM9 11V9h2v6H9v-4zm0-6h2v2H9V5z" />
            </svg></div>
        <div>
            <p class="font-bold py-1">PC에서 접속시 현재위치가 정확하지 않을 수 있습니다!</p>
        </div>
    </div>
    <div v-if="isLoading==0">
        <loading-bar></loading-bar>
    </div>
    <div v-else>
        <div v-if="counts==1">
            <attend-form />
        </div>
        <div v-else-if="counts==0">
            <div role="alert">
                <div class="bg-red-500 text-white font-bold rounded-t px-4 py-2">
                    에러
                </div>
                <div class="border border-t-0 border-red-400 rounded-b bg-red-100 px-4 py-3 text-red-700">
                    <p>출석체크를 할려면 {{ boundary }}M 이내에 접근하세요</p>
                </div>
            </div>
            <div class="py-3">
                <not-visible-attend></not-visible-attend>
            </div>

        </div>
    </div>
    <div id="map" style="width:100%;height:300px" class="bg-white shadow-lg p-4 rounded-lg flex flex-wrap justify-between w-full mb-3"></div>

</template>
<script>
    import AttendForm from '@/Geofencing/AttendForm'
    import NotVisibleAttend from '@/Geofencing/notVisibleAttend.vue'
    import LoadingBar from "@/Pages/Board/LoadingBar"
    export default {
        data() {
            return {
                counts: '0',
                distance: '9999',
                isPC: '0',
                boundary: 300, // 출석허용거리
                isLoading: 0,
            }
        },
        computed: {},
        mounted() {
            window.kakao && window.kakao.maps ? this.initMap() : this.addScript();
            if (this.isMobile()) {
                this.isPC = 1;
            }
        },
        methods: {
            initMap() {
                var mapContainer = document.getElementById('map'), // 지도를 표시할 div 
                    mapOption = {
                        center: new kakao.maps.LatLng(33.450701, 126.570667), // 지도의 중심좌표
                        level: 4 // 지도의 확대 레벨 
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
                            lon); // 마커가 표시될 위치를 geolocation으로 얻어온 좌표로 생성합니다
                        // 인포윈도우에 표시될 내용입니다

                        // 마커와 인포윈도우를 표시합니다
                        // 마커를 생성합니다

                        var imageSrc = '/img/marker_yju.png', // 마커이미지의 주소입니다    
                            imageSize = new kakao.maps.Size(64, 69), // 마커이미지의 크기입니다
                            imageOption = {
                                offset: new kakao.maps.Point(22, 49)
                            }; // 마커이미지의 옵션입니다. 마커의 좌표와 일치시킬 이미지 안에서의 좌표를 설정합니다.

                        var imageSrc2 = '/img/marker_me.png', // 마커이미지의 주소입니다    
                            imageSize2 = new kakao.maps.Size(44, 49), // 마커이미지의 크기입니다
                            imageOption2 = {
                                offset: new kakao.maps.Point(32, 49)
                            }; // 마커이미지의 옵션입니다. 마커의 좌표와 일치시킬 이미지 안에서의 좌표를 설정합니다.

                        // 마커의 이미지정보를 가지고 있는 마커이미지를 생성합니다
                        var markerImage = new kakao.maps.MarkerImage(imageSrc, imageSize, imageOption),
                            markerPosition = new kakao.maps.LatLng(startlat, startlon); // 마커가 표시될 위치입니다

                        // 마커의 이미지정보를 가지고 있는 마커이미지를 생성합니다
                        var markerImage2 = new kakao.maps.MarkerImage(imageSrc2, imageSize2, imageOption2),
                            markerPosition2 = new kakao.maps.LatLng(startlat, startlon); // 마커가 표시될 위치입니다


                        var marker = new kakao.maps.Marker({
                            position: markerPosition,
                            image: markerImage // 마커이미지 설정 
                        });

                        var currentMarker = new kakao.maps.Marker({
                            position: locPosition,
                            image: markerImage2 // 마커이미지 설정 
                        });
                        marker.setMap(map);
                        currentMarker.setMap(map);


                        var content = '<div class="customoverlay">' +
                            '  <a href="//map.kakao.com/?itemId=17565943" target="_blank">' +
                            '    <span class="title">영진전문대 본관</span>' +
                            '  </a>' +
                            '</div>';
                        var customOverlay = new kakao.maps.CustomOverlay({
                            map: map,
                            position: startPosition,
                            content: content,
                            yAnchor: 1
                        });



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
                            radius: this.boundary, // 미터 단위의 원의 반지름입니다 
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

                        var distance = Math.ceil(polyline.getLength());
                        var content2 = '<span class="info-title">' + '<a>' + "학교까지  " + distance + "M  " +
                            '</a>' + '</span>'
                        var customOverlay2 = new kakao.maps.CustomOverlay({
                            map: map,
                            position: locPosition,
                            content: content2,
                            yAnchor: 1
                        });
                        if (polyline.getLength() <= this.boundary) {
                            console.log("출석체크 버튼 생성");
                            this.addAttendButton();
                        } else {

                        }
                        this.isLoading = 1;
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
                    '//dapi.kakao.com/v2/maps/sdk.js?autoload=false&appkey=dc92402fe9472a39910383183e108f9c';
                document.head.appendChild(script);
            },
            addAttendButton: function () {
                this.counts = 1;
            },
            isMobile() {
                return /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
            },
        },
        components: {
            AttendForm,
            NotVisibleAttend,
            LoadingBar,
        }
    }
</script>
<style>
    .customoverlay {
        position: relative;
        bottom: 60px;
        border-radius: 6px;
        border: 1px solid #ccc;
        border-bottom: 2px solid #ddd;
        float: left;
    }

    .customoverlay:nth-of-type(n) {
        border: 0;
        box-shadow: 0px 1px 2px #888;
    }

    .customoverlay a {
        display: block;
        text-decoration: none;
        color: #000;
        text-align: center;
        border-radius: 6px;
        font-size: 14px;
        font-weight: bold;
        overflow: hidden;
        background: #248EFC;
        background: #248EFC url(https://t1.daumcdn.net/localimg/localimages/07/mapapidoc/arrow_white.png) no-repeat right 14px center;
    }

    .customoverlay .title {
        display: block;
        text-align: center;
        background: #fff;
        margin-right: 35px;
        padding: 10px 15px;
        font-size: 14px;
        font-weight: bold;
    }

    .customoverlay:after {
        content: '';
        position: absolute;
        margin-left: -12px;
        left: 50%;
        bottom: -12px;
        width: 22px;
        height: 12px;
        background: url('https://t1.daumcdn.net/localimg/localimages/07/mapapidoc/vertex_white.png')
    }

    .info-title {
        position: relative;
        top: 50px;
        right: 10px;
        border-radius: 6px;
        border: 4px solid #fff;
        border-bottom: 4px solid #fff;
        float: right;
    }

    .info-title>a {
        display: block;
        background: #F87171;
        opacity: 0.8;
        color: #fff;
        text-align: center;
        height: 40px;
        width: 160px;
        line-height: 40px;

    }
</style>