    var restaurantMarker;
    var geocoder;
    function initialize() {



        var markers = [];
        var map = new google.maps.Map(document.getElementById('map-canvas'), {
            mapTypeId: google.maps.MapTypeId.ROADMAP
        });
        geocoder = new google.maps.Geocoder();
        var defaultBounds = new google.maps.LatLngBounds(
                new google.maps.LatLng(23.0300, 72.5800),
                new google.maps.LatLng(22.9800, 72.6700));
        map.fitBounds(defaultBounds);

        // Create the search box and link it to the UI element.
        var input = /** @type {HTMLInputElement} */(
                document.getElementById('pac-input'));
        map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

        var searchBox = new google.maps.places.SearchBox(
                /** @type {HTMLInputElement} */(input));


        if (typeof restaurantLat != "undefined") {
            var latlng = new google.maps.LatLng(restaurantLat,restaurantLong);
            restaurantMarker = new google.maps.Marker({
                position: latlng,
                map: map
            });
        }

        // [START region_getplaces]
        // Listen for the event fired when the user selects an item from the
        // pick list. Retrieve the matching places for that item.
        google.maps.event.addListener(searchBox, 'places_changed', function() {
            var places = searchBox.getPlaces();

            if (places.length == 0) {
                return;
            }
            for (var i = 0, marker; marker = markers[i]; i++) {
                marker.setMap(null);
            }

            // For each place, get the icon, place name, and location.
            markers = [];
            var bounds = new google.maps.LatLngBounds();
            for (var i = 0, place; place = places[i]; i++) {
                var image = {
                    url: place.icon,
                    size: new google.maps.Size(71, 71),
                    origin: new google.maps.Point(0, 0),
                    anchor: new google.maps.Point(17, 34),
                    scaledSize: new google.maps.Size(25, 25)
                };

                // Create a marker for each place.
                var marker = new google.maps.Marker({
                    map: map,
                    icon: image,
                    title: place.name,
                    position: place.geometry.location
                });

                markers.push(marker);

                bounds.extend(place.geometry.location);
            }

            map.fitBounds(bounds);
        });
        // [END region_getplaces]

        // Bias the SearchBox results towards places that are within the bounds of the
        // current map's viewport.
        google.maps.event.addListener(map, 'bounds_changed', function() {
            var bounds = map.getBounds();
            searchBox.setBounds(bounds);
        });
        google.maps.event.addListener(map, 'click', function(e) {
        console.log(e);
            placeMarker(e.latLng, map);
        });
    }

    function placeMarker(position, map) {
        if (restaurantMarker == undefined) {
            restaurantMarker = new google.maps.Marker({
                position: position,
                map: map
            });
        } else {
            restaurantMarker.setPosition(position);
        }

        map.panTo(position);
        getAddress(position);
        console.log(position);
        document.getElementById("restaurant-latitude").value = position.lat();
        document.getElementById("restaurant-longitude").value = position.lng();
    }

    function getAddress(latLng) {

        geocoder.geocode({'latLng': latLng},
        function(results, status) {

            if (status == google.maps.GeocoderStatus.OK) {
                if (results[0]) {
                    var address_components = results[0].address_components;
                    var address_components_length = address_components.length;

                    var city = address_components[address_components_length - 5];
                    var area = address_components[address_components_length - 6];
                    var address = [];
                    for (i = (address_components_length - 6), j = 0; i >= 0; i--, j++) {
                        address[j] = address_components[j].long_name;
                    }

                    address_str = address.join(' ,');
                    document.getElementById("restaurant-address").value = address_str;
                    document.getElementById("restaurant-area").value = area.long_name;
                    document.getElementById("restaurant-city").value = city.long_name;
                    if (document.getElementById("restaurant-delivery_areas").value.trim() == "") {
                        document.getElementById("restaurant-delivery_areas").value = area.long_name + ", ";
                    } else if (!(document.getElementById("restaurant-delivery_areas").value.search(area.long_name) >= 0)) {
                        document.getElementById("restaurant-delivery_areas").value = area.long_name + "," + document.getElementById("restaurant-delivery_areas").value;
                    }

                }
                else {
                    alert('Could not get any address. Please add address manually');
                    //  document.getElementById("restaurant-address").value = "No results";
                }
            }
            else {
                alert('Could not get any address. Please add address manually');
                //document.getElementById("restaurant-address").value = status;
            }
        });
    }

    google.maps.event.addDomListener(window, 'load', initialize);

