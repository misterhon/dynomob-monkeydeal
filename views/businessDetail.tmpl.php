<?php include "_partials/header.php" ?>

	<h1>Business Details</h1>
	<?php
	
		$m = 'http://dynomob.com/app/images/defaultpic.png';
		foreach($detail as $d) {
		echo "<div>
			<h2>{$d->name}</h2>
			<img class='business_img' src='". (($d->promophoto) ?: (($d->photo) ?: $m) )."'>
			{$d->description}
			</div>";
		}
	?>
	<a href="index.php">Search again</a>
	
	
<?php include "_partials/footer.php" ?>