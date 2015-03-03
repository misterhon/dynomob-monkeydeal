(function(window, Mapster){
	$.widget("mapster.mapster", {
		options: {},

		_create: function() {
			var element = this.element[0],
				options = this.options;
			this.map = Mapster.create(element, options);
		},

		addMarker: function( opts ) {
			var self = this;
			if ( opts.location ) {
				this.map.geocode({
					address: opts.location,
					success: function(results) {
						results.forEach(function(result) {
							opts.lat = result.geometry.location.lat();
							opts.lng = result.geometry.location.lng();
							self.map.addMarker(opts);
						});
					},
					error: function(status) {
						console.error(status);
					}
				});
			} else {
				this.map.addMarker(opts);
			}
		},

		findMarkers: function( cb ) {
			return this.map.findBy(cb);
		},

		removeMarkers: function( cb ) {
			this.map.removeBy(cb);
		},

		markers: function() {
			return this.map.markers.items;
		},

		setPano: function(selector, opts) {
			var elements = $(selector),
				self = this;
			$.each(elements, function(key, el) {
				self.map.setPano(el, opts);
			});
		}
	});
}(window, Mapster));