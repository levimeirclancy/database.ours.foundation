<?

function error($message) {
	echo $message;
	exit;
	}

// https://gist.github.com/unix1/7510208
function inverse_matrix($A, $debug = FALSE) {
	/// @todo check rows = columns

	$n = count($A);

	// get and append identity matrix
	$I = identity_matrix($n);
	for ($i = 0; $i < $n; ++ $i) {
		$A[$i] = array_merge($A[$i], $I[$i]);
	}

	if ($debug) {
		echo "\nStarting matrix: ";
		print_matrix($A);
	}

	// forward run
	for ($j = 0; $j < $n-1; ++ $j) {
		// for all remaining rows (diagonally)
		for ($i = $j+1; $i < $n; ++ $i) {
			// if the value is not already 0
			if ($A[$i][$j] !== 0) {
				// adjust scale to pivot row
				// subtract pivot row from current
				$scalar = $A[$j][$j] / $A[$i][$j];
				for ($jj = $j; $jj < $n*2; ++ $jj) {
					$A[$i][$jj] *= $scalar;
					$A[$i][$jj] -= $A[$j][$jj];
				}
			}
		}
		if ($debug) {
			echo "\nForward iteration $j: ";
			print_matrix($A);
		}
	}

	// reverse run
	for ($j = $n-1; $j > 0; -- $j) {
		for ($i = $j-1; $i >= 0; -- $i) {
			if ($A[$i][$j] !== 0) {
				$scalar = $A[$j][$j] / $A[$i][$j];
				for ($jj = $i; $jj < $n*2; ++ $jj) {
					$A[$i][$jj] *= $scalar;
					$A[$i][$jj] -= $A[$j][$jj];
				}
			}
		}
		if ($debug) {
			echo "\nReverse iteration $j: ";
			print_matrix($A);
		}
	}

	// last run to make all diagonal 1s
	/// @note this can be done in last iteration (i.e. reverse run) too!
	for ($j = 0; $j < $n; ++ $j) {
		if ($A[$j][$j] !== 1) {
			$scalar = 1 / $A[$j][$j];
			for ($jj = $j; $jj < $n*2; ++ $jj) {
				$A[$j][$jj] *= $scalar;
			}
		}
		if ($debug) {
			echo "\n1-out iteration $j: ";
			print_matrix($A);
		}
	}

	// take out the matrix inverse to return
	$Inv = array();
	for ($i = 0; $i < $n; ++ $i) {
		$Inv[$i] = array_slice($A[$i], $n);
	}

	return $Inv;
}


function identity_matrix($n) {
	$I = array();
	for ($i = 0; $i < $n; ++ $i) {
		for ($j = 0; $j < $n; ++ $j) {
			$I[$i][$j] = ($i == $j) ? 1 : 0;
		}
	}
	return $I;
	}

function multiply_matrices($matrix_one, $matrix_two) {
	$column_count_one = null;
	foreach ($matrix_one as $row_temp):
		if ( ($column_count_one !== null) && ($column_count_one !== count($row_temp)) ):
			error("Column count not consistent.");
			endif;
		$column_count_one = count($row_temp);
		endforeach;
	if ($column_count_one !== count($matrix_two)):
		error("Cannot multiply matrices: first has ".$column_count_one." columns; second has ".count($matrix_two)." rows." );
		endif;
	
	$product_temp = [];
	
	// Get the row ... now we need to keep this and multiply it by each column
	foreach ($matrix_one as $matrix_one_row_count => $matrix_one_row):
	
		if (!(isset($product_temp[$matrix_one_row_count]))):
			$product_temp[$matrix_one_row_count] = [];
			endif;
	
		foreach ($matrix_one_row as $matrix_one_column_count => $matrix_one_value):
	
			foreach($matrix_two as $matrix_two_row_count => $matrix_two_row):
	
				foreach($matrix_two_row as $matrix_two_column_count => $matrix_two_value):
	
					if ($matrix_one_column_count !== $matrix_two_row_count): continue; endif;

					if (!(isset($product_temp[$matrix_one_row_count][$matrix_two_column_count]))):
						$product_temp[$matrix_one_row_count][$matrix_two_column_count] = 0;
						endif;
	
					$product_temp[$matrix_one_row_count][$matrix_two_column_count] += ( $matrix_one_value * $matrix_two_value );
						
					endforeach;

				endforeach;
	
			endforeach;

		endforeach;
					
	return $product_temp;
	}

function transpose_matrix($matrix_array) {
	$transpose_temp = [];
	foreach($matrix_array as $row_count_temp => $row_temp):
		foreach($row_temp as $column_count_temp => $value_temp):
			if (!(isset($transpose_temp[$column_count_temp]))): $transpose_temp[$column_count_temp] = []; endif;
			$transpose_temp[$column_count_temp][$row_count_temp] = $value_temp;
			endforeach;
		endforeach;
	return $transpose_temp;
	}


$hypothetical_mean = [
	[ 1000 ],
	[ 15 ],
	[ 60 ],
	[ 800 ],
	[ 75 ],
	];

$sample_mean = [
	[ 624.0 ],
	[ 11.1 ],
	[ 65.8 ],
	[ 839.6 ],
	[ 78.9 ],
	];

// $variance_covariance = [
// 	[ 157829.4, , , , ],
// 	[ 940.1, 35.8, , , ],
// 	[ 6075.8, 114.1, 934.9, , ],
// 	[ 102411.1, 2382.2, 7330.1, 2668452.4, ],
// 	[ 6701.6, 137.7, 477.2, 22063.3, 5416.3 ],
// 	];

$variance_covariance = [
	[ 157829.4,	940.1,	6075.8,	102411.1,	6701.6 ],
	[ 940.1, 	35.8, 	114.1, 	2383.2, 	137.7 ],
	[ 6075.8, 	114.1, 	934.9, 	7330.1, 	477.2 ],
	[ 102411.1, 	2382.2, 7330.1, 2668452.4, 	22063.3],
	[ 6701.6, 	137.7, 	477.2, 	22063.3, 	5416.3 ],
	];

$sample_size = 737;
	      
// Hotellings = $sample_size * ($sample_mean - $hypothetical_mean) * S-1 * ($sample_mean - $hypothetical_mean)

// We wind up with one number because a 1 x 5 matrix is multiplied by a 5 x 5 matrix, resulting in a 1 x 5 matrix. Then, that is multiplied by a 5 x 1 matrix, resulting in a scalar.

if (count($hypothetical_mean) !== count($sample_mean)): error("Hypothetical mean vector and sample mean vector must have equal number of values."); endif;

$mean_difference = [];
foreach ($hypothetical_mean as $key_temp => $value_temp):
	$mean_difference[] = [ $sample_mean[$key_temp][0] - $hypothetical_mean[$key_temp][0] ];
	endforeach;

// Calculate inverse of the variance-covariance matrix,
$variance_covariance_inverse = inverse_matrix($variance_covariance);

$product_temp = multiply_matrices(transpose_matrix($mean_difference), $variance_covariance_inverse);
$product_temp = multiply_matrices($product_temp, $mean_difference);

echo $product_temp[0][0]*$sample_size;

?>
