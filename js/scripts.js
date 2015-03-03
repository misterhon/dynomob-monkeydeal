(function(window, $){

	var $mapster = $('#map-canvas').mapster(Mapster.MAP_OPTIONS);

	$.ajax({
		url: 'index.php',
		type: 'POST',
		dataType: 'JSON',
		data: 'business=',
		success: function(results){
			results.forEach(function(result){
				$mapster.mapster('addMarker', {
					location: result.location,
					content: '<span style="color:#333;">' + result.name + '</span>'
				});
			});
		}
	});

}(window, jQuery));