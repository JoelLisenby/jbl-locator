jQuery(document).ready(function( $ ) {

	var JBLMap = function() {
		
		var jbl = this;
		var map, infowindow, orientation;
		
		jbl.init = function() {
			
			jbl.orientation = jbl.getMapOrientation();
			
			jbl.map = new google.maps.Map(document.getElementById('jbllocatormap'), {
				zoom: jbl.orientation.zoom,
				center: jbl.orientation.center,
				heading: jbl.orientation.rotation,
				mapTypeId: google.maps.MapTypeId.ROADMAP
			});
			
			jbl.mapSearch();
			jbl.mapMarkers();
			
			google.maps.event.addListener(jbl.map, "bounds_changed", jbl.setURLMapOrientation);
			
		};
		
		jbl.mapMarkers = function() {
			
			jbl.infowindow = new google.maps.InfoWindow();
			
			var i;
			for ( i = 0; i < posts_json.length; i++ ) {
				
				jbl.createMarker(posts_json[i]);
				
			}
			
		};
		
		jbl.createMarker = function( post ) {
			
			var marker = new google.maps.Marker({
				map: jbl.map,
				position: new google.maps.LatLng(post['lat'], post['lng'])
			});
			
			google.maps.event.addListener(marker, 'click', function() {
				
				var content = '<h2>'+ post['title'] +'</h2>';
				content += post['content'] !== '' ? '<p>'+ post['content'] +'</p>' : '';
				content += post['address'] !== '' ? '<p>'+ post['address'] +'</p>' : '';
				content += post['phone'] !== '' ? '<p>Call '+ post['phone'] +'</p>' : '';
				content += post['email'] !== '' ? '<p><a href="mailto:'+ post['email'] +'">'+ post['email'] +'</a></p>' : '';
				content += post['website'] !== '' ? '<p><a href="'+ post['website'] +'">'+ post['website'] +'</a></p>' : '';
				
				jbl.infowindow.setContent( content );
				jbl.infowindow.open( jbl.map, this );
				
			});
			
		};
		
		jbl.mapSearch = function() {
			var input = document.getElementById('jbllocatorsearch');
			var searchBox = new google.maps.places.SearchBox(input);
			jbl.map.controls[google.maps.ControlPosition.TOP_CENTER].push(input);

			jbl.map.addListener('bounds_changed', function() {
				
				searchBox.setBounds( jbl.map.getBounds() );
				
			});

			searchBox.addListener('places_changed', function() {
				
				var places = searchBox.getPlaces();

				if (places.length == 0) {
					
					return;
					
				}
				
				var bounds = new google.maps.LatLngBounds();
				
				places.forEach(function(place) {
				
					if (place.geometry.viewport) {
						bounds.union(place.geometry.viewport);
					} else {
						bounds.extend(place.geometry.location);
					}
					
				});

				jbl.map.fitBounds(bounds);
				
			});
		};
		
		jbl.getMapOrientation = function() {
			
			var orientation = {};
			var defaultOrientation = {
				zoom: 2,
				center: new google.maps.LatLng(32.82, -117.37),
				rotation: 0
			};
			
			if(window.location.hash !== '') {
				
				var hash = window.location.hash.replace('#map=', '');
				var parts = hash.split('/');
				
				if (parts.length === 4) {
					
					orientation.zoom = parseInt(parts[0], 10);
					orientation.center = new google.maps.LatLng(parseFloat(parts[1]), parseFloat(parts[2]));
					orientation.rotation = parseFloat(parts[3]);
					
				}
				
			}
			
			return $.extend(defaultOrientation, orientation);
			
		};
		
		jbl.setMapOrientation = function( orientation ) {
			
			var defaults = {
				zoom: jbl.map.getZoom(),
				center: jbl.map.getCenter(),
				rotation: jbl.map.getHeading()
			};
			
			var orientation = $.extend( defaults, orientation );
			
			jbl.map.setZoom(orientation.zoom);
			jbl.map.setCenter(orientation.center);
			jbl.map.setHeading(orientation.rotation);
			
			jbl.setURLMapOrientation();
			
		};
		
		jbl.setURLMapOrientation = function() {
			
			var orientation = {
				zoom: jbl.map.getZoom(),
				center: jbl.map.getCenter(),
				rotation: jbl.map.getHeading()
			};
			
			if(orientation.zoom != undefined) {
				
				var hash = '#map=' +
					orientation.zoom + '/' +
					Math.round(orientation.center.lat() * 100) / 100 + '/' +
					Math.round(orientation.center.lng() * 100) / 100 + '/' +
					orientation.rotation;
				
				window.history.pushState({hash}, 'map', hash);
				
			}
		};
		
		jbl.init();
		
	};

	if( $('#jbllocatormap').length ) {
		var jblmap = new JBLMap();
	}

});