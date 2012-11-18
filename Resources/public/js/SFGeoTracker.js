function SFGeoTracker(){
  var watchPosId=0;
  this.init();
}

SFGeoTracker.prototype = {
  watchPosId: 0,
  stepNumber: 0,
  lastPoint: false,
  totalDistance: 0,
  accuracy: 100,
  minimalStep: 0,
  geolocValues: new Array(),
  init: function(){
    if(!navigator.geolocation){
      alert("La géolocalisation ne fonctionne pas avec votre équipement.");
    }
    $.jStorage.set("geoloc",this.geolocValues);
  },
  pointsDistance: function(point1,point2){
    return point1.distance(point2);
  },
  watchPosition: function(){
    var self=this;
    this.watchPosId = navigator.geolocation.watchPosition(
      function(position) {
        if(position.coords.accuracy < self.accuracy){
          self.storePoint(new SFGeoPoint(position.coords.latitude, position.coords.longitude,position.coords.accuracy));
        }
      },
      function(error){
        alert(error);
      },
      {enableHighAccuracy:true}
    );
  },
  clearWatch: function(){
    navigator.geolocation.clearWatch(this.watchPosId);
  },
  storePoint: function(point){
    // Distance avec la précédente :
    if(this.lastPoint){
      var stepDistance=this.pointsDistance(this.lastPoint,point);
    }else{
      var stepDistance=0;
      this.totalDistance=0;
      var lastStep=0;
      this.lastPoint=point;
    }
    var totalDistance=this.totalDistance+stepDistance;
    if(stepDistance<this.minimalStep){
      return;
    }
    this.stepNumber++;
    this.lastPoint=point;
    this.displayPoint();
    this.stepDistance=stepDistance;
    if(CAPChrono.isRunning){
      this.totalDistance=totalDistance;
      //On affiche
      // On mémorise
      this.geolocValues=$.jStorage.get("geoloc");
      this.geolocValues.push(new Array(point.lat,point.lon));
      $.jStorage.set("geoloc",this.geolocValues);
    }else{
      if(stepDistance>1){
        $("#gpsState").html("acquisition position en cours... ...(précision : " + stepDistance + "m)");
        $("#chronoBtnStart").html("attendez  sans vous déplacer svp");
        $("#chronoBtnStart").attr("disabled","disabled");
      }else{
        $("#gpsState").html("GPS OK (précision : " + stepDistance + "m)");
        $("#chronoBtnStart").html("Démarrer");
        $("#chronoBtnStart").removeAttr("disabled");
      }
    }
  },
  displayPoint: function(){
    if($("#xylist .step:first").length){
      $("#xylist .step:first").remove();
    }
    $("#xylist").prepend("<tr class=\"step\"><td class=\"stepNumber\">" + this.stepNumber + "</td><td class=\"stepDistance\">" + this.stepDistance + "</td><td class=\"totalDistance\">" + this.totalDistance + "</td><td class=\"instant\">" + CAPChrono.getTime() + "</td><td class=\"latitude\">" + this.lastPoint.lat.toFixed(5) + "</td><td class=\"longitude\">" + this.lastPoint.lon.toFixed(5) + "</td><td class=\"longitude\">" + this.lastPoint.accuracy.toFixed(0) + "</td></tr>");
      // On mémorise
  },
  sendData: function(){

  },
  showMap:function(){
    var mapOptions = {
          center: new google.maps.LatLng(this.lastPoint.lat, this.lastPoint.lon),
          zoom: 18,
          mapTypeId: google.maps.MapTypeId.ROADMAP
        };
    var map = new google.maps.Map(
      document.getElementById("map_canvas"),
      mapOptions
    );
    var geolocValues=$.jStorage.get("geoloc");
    var flightPlanCoordinates = new Array;
    for (var i in geolocValues)
    {
      varArrPoint=geolocValues[i];
      flightPlanCoordinates.push(new google.maps.LatLng(varArrPoint[0],varArrPoint[1]));
    }
    var flightPath = new google.maps.Polyline({
      path: flightPlanCoordinates,
      strokeColor: "#FF0000",
      strokeOpacity: 1.0,
      strokeWeight: 2
    });
    flightPath.setMap(map);
  }
}


function SFGeoPoint(lat,lon,accuracy){
  if(typeof lat === 'undefined'){
    alert("Un point doit avoir une latitude !");
    return;
  }else{
    this.lat=lat;
  }
  if(typeof lon === 'undefined'){
    alert("Un point doit avoir une longitude !");
    return;
  }else{
    this.lon=lon;
  }
  if(typeof accuracy === 'undefined'){

  }else{
    this.accuracy=accuracy;
  }
  this.init();
}
SFGeoPoint.prototype = {
  lat: 0.0,
  lon: 0.0,
  accuracy: 0,
  init: function(){
  },
  distance: function(point){
    var R = 6371; // km (change this constant to get miles)
    var dLat = (point.lat-this.lat) * Math.PI / 180;
    var dLon = (point.lon-this.lon) * Math.PI / 180;
    var a = Math.sin(dLat/2) * Math.sin(dLat/2) +
    Math.cos(this.lat * Math.PI / 180 ) * Math.cos(point.lat * Math.PI / 180 ) *
    Math.sin(dLon/2) * Math.sin(dLon/2);
    var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
    var d = R * c;
    if (d>1) return Math.round(d)*1000;
    else if (d<=1) return Math.round(d*1000);
    return 0;
  }
}
