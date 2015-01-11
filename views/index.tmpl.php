<?php include "_partials/header.php" ?>

	<h1>MonkeyDeal Affiliates</h1>	
	<form id="business-selection" method="post" action="index.php">
		<input type="text" name="business_search" id="business_search" value="" placeholder="Find affiliate by name">
		<input name="submit" id="submit" type="submit" value="Search">
	</form>
	
	<ul id="business_list">
		<script id="business_list_template" type="text/x-handlebars-template">
			{{#each this}}
			<li class="clearfix" data-business_id="{{id}}"><a href="businessDetail.php?business_id={{id}}">
				<span style="background-image:url('{{displayBusinessListingPhoto this}}');"></span>
				<h2>{{name}}</h2>
			</a></li>
			{{/each}}
		</script>
		<?php if ( isset( $biz ) ): ?>
		<?php
			if ( count( $biz ) ) {
				foreach($biz as $b) {
				echo "<li class='clearfix' data-business_id='{$b->id}'><a href='businessDetail.php?business_id={$b->id}'>
					<span style='background-image:url("
						.(($b->promophoto) ?: (($b->photo) ?: 'http://www.dynomob.com/app/images/defaultpic.png'))
						.");'></span>
					<h2>{$b->name}</h2>
				</a></li>";
				}
			} else {
				echo "<li>No Results. <a href=\"\">Try again</a>.</li>";
			}
		?>
		<?php else: ?>
			<li>Search for affiliates; leave blank to see complete listing.</li>
		<?php endif; ?>
	</ul>
	
	<div class="business_detail">
		<script id="business_detail_template" type="text/x-handlebars-template">
			{{#each this}}
			<h2>{{name}}</h2>
			<img src="{{displayBusinessPhoto this}}">
			<img class="map" src="http://maps.googleapis.com/maps/api/staticmap?center={{address}},{{city}},{{state}},{{zip}}&markers={{address}}+{{city}}+{{state}}&zoom=16&size=408x153&scale=2&sensor=false&visual_refres=true">
			<p>{{description}}<p>
			<span class="close">x</span>
			{{/each}}
		</script>
	</div>
	
<?php include "_partials/footer.php" ?>