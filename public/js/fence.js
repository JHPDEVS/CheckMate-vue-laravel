   window.onload = function() {
        var startPos;
        var startPosLat;
        var startPosLong;
        var distance;
      
        if (navigator.geolocation) {

          startPosLat = 44.95716993150707;
          startPosLong = -93.28439280496818;

          $("#startLat").text(startPosLat);
          $("#startLon").text(startPosLong);
      
          navigator.geolocation.watchPosition(function(position) {
            $("#currentLat").text(position.coords.latitude);
            $("#currentLon").text(position.coords.longitude);

            distance = calculateDistance(startPosLat, startPosLong,position.coords.latitude, position.coords.longitude)
            $("#distance").text(distance);

            if(distance < .05){
              $("#message").text("Yes, were inside .05 KM!!! :) A+")
            }else if(distance > .05){
              $("#message").text("No, not inside .05 KM :(")
            }
          });
        }
      };
      
      // Reused code - copyright Moveable Type Scripts - retrieved May 4, 2010.
      // http://www.movable-type.co.uk/scripts/latlong.html
      // Under Creative Commons License http://creativecommons.org/licenses/by/3.0/
      function calculateDistance(lat1, lon1, lat2, lon2) {
        var R = 6371; // km
        var dLat = (lat2-lat1).toRad();
        var dLon = (lon2-lon1).toRad();
        var a = Math.sin(dLat/2) * Math.sin(dLat/2) +
                Math.cos(lat1.toRad()) * Math.cos(lat2.toRad()) *
                Math.sin(dLon/2) * Math.sin(dLon/2);
        var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
        var d = R * c;
        return d;
      }
      Number.prototype.toRad = function() {
        return this * Math.PI / 180;
      }