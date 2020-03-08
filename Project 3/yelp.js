var map;
var marker;
var bounds;
var center;
var southwest;
var markers = new Array();
function initialize () 
{
  var location = new google.maps.LatLng(32.75, -97.13);
  var mapOptions = {
                    center: location, 
                    zoom: 16
                   }
  map = new google.maps.Map(document.getElementById('map'), mapOptions);
  marker = new google.maps.Marker({position: location, map:map});
}

function sendRequest () 
{
  document.getElementById("output").innerHTML = "<br>";
  // Gives bounding Box of map and finds defines center and southwest co-ordinate
  bounds = map.getBounds();
  var southwest = bounds.getSouthWest();
  var center = map.getCenter();
  //alert(bounds);

  // Obtaining radius
  var rad = Math.round(google.maps.geometry.spherical.computeDistanceBetween(center, southwest));
  var myLat = map.getCenter().lat();
  var myLng = map.getCenter().lng();
  //alert(rad);

   var xhr = new XMLHttpRequest();
   var query = encodeURI(document.getElementById("search").value);
   if(query=="")
   {
      alert("Whoa! Looks like you've left Search empty.");
      return;
   }
   xhr.open("GET", "proxy.php?term=" + query + "&latitude=" + myLat + "&longitude="+ myLng + "&radius=" + rad + "&limit=10");
   xhr.setRequestHeader("Accept","application/json");
   xhr.onreadystatechange = function () {
       if (this.readyState == 4) 
       {
          var json = JSON.parse(this.responseText);
          //alert(this.responseText.total);
          var len = json.total;
          if (len==0)
          {
            document.getElementById("output").innerHTML = document.getElementById("output").innerHTML + "<left><br> Oops! Wrong Search, No such restaurant exists. Please try again! ".bold() + "</left>";
          }
          else
          {
            marker.setMap(null);
            document.getElementById("output").innerHTML += "<br>";
            document.getElementById("output").innerHTML += "<left> Showing Results for: ".bold() + query + "</left>";
            document.getElementById("output").innerHTML += "<br><br>";
          }
          removeMarkers();
          // Parses JSON data and displays on screen
          for( var i= 0; i< len; i++)
          {
            var restaurant = json.businesses[i];
            document.getElementById("output").innerHTML += "<left><h4><a href=" + restaurant.url +  ">" + (i+1) + ")"+ restaurant.name + "</a></h4></left>";
            //document.getElementById("output").innerHTML += "<b>" + (i+1) + ")" + "</b>" + "Restaurant Name: ".bold() + restaurant.name + "</left></a></h4><br><br>";
            document.getElementById("output").innerHTML += "<left>" + "Ratings(1-5): ".bold() + restaurant.rating + "<br><br></left>";
            document.getElementById("output").innerHTML += "<left><img src=" + restaurant.image_url + "></img></left>";
            document.getElementById("output").innerHTML += "<br><br>";
            //alert(image);
            
            // finding location of restaurant
            //alert(restaurant.coordinates.latitude);
            //alert(restaurant.coordinates.longitude);
            marker = new google.maps.Marker({ icon:'http://chart.apis.google.com/chart?chst=d_map_pin_letter&chld='+(i+1)+'|3ce7a2|666666', 
                                              position: {lat: restaurant.coordinates.latitude, lng: restaurant.coordinates.longitude}, 
                                              map: map
                                           });
            markers.push(marker);
          }
          //var str = JSON.stringify(json,undefined,2);
          //document.getElementById("output").innerHTML = "<pre>" + str + "</pre>";
       }
   };
   xhr.send(null);

}
// Nullifies all previous markers
function removeMarkers()
{
  if(markers.length > 0)
  {
    for (var i=0; i< markers.length; i++)
    {
        markers[i].setMap(null);
    }
  }
}
