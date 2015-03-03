(function(window, mapster){
	mapster.MAP_OPTIONS = {
		center: {
			lat: 40.74765,
			lng: -73.9875
		},
		zoom: 18,
		disableDefaultUI: false,
		scrollwheel: false,
		draggable: true,
		mapTypeId: google.maps.MapTypeId.ROADMAP,
		maxZoom: 20,
		minZoom: 16,
		zoomControlOptions: {
			position: google.maps.ControlPosition.TOP_LEFT,
			style: google.maps.ZoomControlStyle.SMALL
		},
		panControlOptions: {
			position: google.maps.ControlPosition.TOP_LEFT
		},
		cluster: {
			// options: {
			// 	// styles: [{
			// 	// 	// small density
			// 	// 	url: '//google-maps-utility-library-v3.googlecode.com/svn/tags/markerclusterer/1.0.2/images/m2.png',
			// 	// }]
			// }
		},
		geocoder: true,
	}
}(window, window.Mapster || (window.Mapster = {})));