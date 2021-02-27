<?

$hypothetical_mean = [
	1000,
	15,
	60,
	800,
	75,
	];

$sample_mean = [
	624.0,
	11.1,
	65.8,
	839.6,
	78.9,
	];

$variance_covariance = [
	[ 157829.4, , , , ],
	[ 940.1, 35.8, , , ],
	[ 6075.8, 114.1, 934.9, , ],
	[ 102411.1, 2382.2, 7330.1, 2668452.4, ],
	[ 6701.6, 137.7, 477.2, 22063.3, 5416.3 ],
	];

$variance_covariance = [
	[ 157829.4, , , , ],
	[ 940.1, 35.8, , , ],
	[ 6075.8, 114.1, 934.9, , ],
	[ 102411.1, 2382.2, 7330.1, 2668452.4, ],
	[ 6701.6, 137.7, 477.2, 22063.3, 5416.3 ],
	];

$sample_size = 737;
	      
// Hotellings = $sample_size * ($sample_mean - $hypothetical_mean) * S-1 * ($sample_mean - $hypothetical_mean)

// We wind up with one number because a 1 x 5 matrix is multiplied by a 5 x 5 matrix, resulting in a 1 x 5 matrix. Then, that is multiplied by a 5 x 1 matrix, resulting in a scalar.

if (count($hypothetical_mean) !== count($sample_mean)): echo "Hypothetical mean vector and sample mean vector must have equal number of values."; exit; endif;

$mean_difference = []
foreach ($hypothetical_mean as $key_temp => $value_temp):
	$mean_difference[] = $sample_mean[$key_temp] - $hypothetical_mean[$key_temp];
	endforeach;




echo $hotellings;

?>
