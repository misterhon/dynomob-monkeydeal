(function(window, google, List){

	var Mapster = (function(){
		function Mapster(el, opt) {
			this.gMap = new google.maps.Map(el, opt);
			this.markers = List.create();
			if (opt.cluster) {
				this.markerClustered = new MarkerClusterer(this.gMap, [], opt.cluster.options);
			}
			if (opt.geocoder) {
				this.geocoder = new google.maps.Geocoder();
			}
		}
		Mapster.prototype = {
			zoom: function(lvl) {
				if (level) {
					this.gMap.setZoom(lvl);
				} else {
					return this.gMap.getZoom();
				}
			},
			_on: function(opts) {
				var self = this;
				google.maps.event.addListener(
					opts.obj,
					opts.event,
					function(e) {
						opts.callback.call(self, e, opts.obj);
				});
			},
			geocode: function(opts) {
				this.geocoder.geocode({
					address: opts.address
				}, function(results, status) {
					if (status === google.maps.GeocoderStatus.OK) {
						opts.success.call(this, results, status);
					} else {
						opts.error.call(this, status);
					}
				});
			},
			setPano: function(el, opts) {
				var panorama = new google.maps.StreetViewPanorama(el, opts);
				this._attachEvents(panorama, opts.events);
				this.gMap.setStreetView(panorama);
			},
			addMarker: function(opts) {
				var marker;
				opts.position = {
					lat: opts.lat,
					lng: opts.lng
				}
				// opts.events = [{
				// 	name: 'click',
				// 	callback: function(e, marker) {
				// 		alert('Im clicked!');
				// 	}
				// }];
				marker = this._createMarker(opts);
				if (this.markerClustered) {
					this.markerClustered.addMarker(marker);
				}
				this._addMarker(marker);
				if (opts.events) {
					this._attachEvents(marker, opts.events);
				}
				if (opts.content) {
					this._on({
						obj: marker,
						event: 'click',
						callback: function() {
							var infoWindow = new google.maps.InfoWindow({
								content: opts.content
							});

							infoWindow.open(this.gMap, marker);
						}
					});
				}
				return marker;
			},
			_attachEvents: function(obj, events) {
				var self = this;
				events.forEach(function(event){
					self._on({
						obj: obj,
						event: event.name,
						callback: event.callback
					});
				});
			},
			_addMarker: function(marker) {
				this.markers.add(marker);
			},
			findBy: function(callback) {
				return this.markers.find(callback);
			},
			removeBy: function(callback) {
				var self = this;
				self.markers.find(callback, function(markers) {
					markers.forEach(function(marker){
						if (self.markerClustered) {
							self.markerClustered.removeMarker(marker);
						} else {
							marker.setMap(null);	
						}
					});
				});
			},
			_createMarker: function(opts) {
				opts.map = this.gMap;
				opts.icon = 'img/symbol_excla.png';
				return new google.maps.Marker(opts);
			},
		}
		return Mapster;
	}());

	Mapster.create = function(el, opt) {
		return new Mapster(el, opt);
	};

	window.Mapster = Mapster;

}(window, google, List));