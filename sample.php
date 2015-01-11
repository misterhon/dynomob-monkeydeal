<?php

	// Hand-picking specific result subsets for demonstration purposes

	include 'lib/Deal.php';
	include 'lib/Business.php';
	include 'lib/User.php';

	include '_partials/header.php';	
?>

<pre>

include 'lib/Deal.php';
include 'lib/Business.php';
include 'lib/User.php';

// Business::getAll()
<?php var_dump( array_slice( Business::getAll(), 38, 2 ) ); ?>

// Deal::getAll()
<?php var_dump( array_slice( Deal::getAll(), 0, 2 ) ); ?>

// User::getAll()
<?php var_dump( array_slice( User::getAll(), 6, 2 ) ); ?>
	
</pre>

<?php include '_partials/footer.php'; ?>