<? include_once('functions_content.php');
include_once('functions_layout.php');
include_once('functions_sql.php');

function permanent_redirect ($url) {
	header('Cache-Control: no-cache');
	header('Pragma: no-cache');
	header("HTTP/1.1 301 Moved Permanently"); 
	header("Location: $url");
	exit; }

function replace_redirect ($url) {
	echo "<script>";
	echo "window.location.replace('".$url."');";
	echo "</script>";
	exit; }

function random_code($length=16) {
	$characters = [
		"2", "3", "4", "5", "6", "7",
//		"Q", "W", "E", "R". "T", "Y", "U", "I", "O", "P", 
		"Q", "W", "R". "T", "Y", "P", // remove vowels
//		"A", "S", "D", "F", "G", "H", "J", "K", "L", 
		"S", "D", "F", "G", "H", "J", "K", "L", // remove vowels
//		"Z", "X", "C", "V", "B", "N", "M"
		"Z", "C", "V", "B", "N", "M" // remove 'x' for vulgar use
		];
	$upper_offset = count($characters)-1;
	if (!(is_int($length))): $length = 16; endif;
	if ($length < 1): $length = 16; endif;
	$key_temp = null;
	while (strlen($key_temp) < $length): $key_temp .= $characters[rand(0,$upper_offset)]; endwhile;
	return $key_temp; }

function print_terms($terms_array) {
	if (empty($terms_array)): return; endif;
	foreach ($terms_array as $term_id => $term_info):
		$entry_id_array[$term_info['person_id']] = [];
		$entry_id_array[$term_info['position_id']] = [];
		$entry_id_array[$term_info['for_id']] = []; endforeach;
	echo "<table><thead><tr><th>term id</th><th>person</th><th>position</th><th>for</th><th>start</th><th>end</th><th>status</th></tr></thead><tbody>";
	foreach ($terms_array as $term_id => $term_info):
		echo "<tr><td>$term_id</td>";
		echo "<td>".$information_array[$term_info['person_id']]['english_name'][0]."</td>";
		echo "<td>".$information_array[$term_info['position_id']]['english_name'][0]."</td>";
		echo "<td>".$information_array[$term_info['for_id']]['english_name'][0]."</td>";
		echo "<td>".$term_info['date_start']."</td>";
		echo "<td>".$term_info['date_end']."</td>";
		echo "<td>".$term_info['status']."</td></tr>"; endforeach;
	echo "</tbody></table>"; }

function print_row_loop ($header_backend, $entries_array=null, $indent_array=[]) {
	
	// Make this available
	global $information_array;
	
	// Orders the array, and removes entries not in the $information_array
	$entries_array = array_intersect(array_keys($information_array), $entries_array);
	
	// Let's set up the array of organized entries
	$entries_organized = [];
		
	foreach ($entries_array as $entry_id):	
	
		// If the entry does not exist
		if (!(array_key_exists($entry_id, $information_array))):
			continue; endif;

		// If the type is not correct
		if ($information_array[$entry_id]['type'] !== $header_backend):
			continue; endif;
	
		// If we're doing the first round but it already has parents
		if (!(empty($information_array[$entry_id]['parents']['hierarchy'])) && empty($indent_array)):
		
			// We are going to check all the parents
			foreach ($information_array[$entry_id]['parents']['hierarchy'] as $entry_id_temp):
		
				// If it has one parent that is the same type, skip now and get to it as a child then
				if ($information_array[$entry_id_temp]['type'] == $header_backend): continue 2; endif;
		
				endforeach;
	
			endif;

//		$indent_count = count($indent_array);
//		if ($indent_count > 16): $indent_count = 16; endif; // After 16, the indents just flatten out
	
//	$fadeout_temp = null;
//	if ($information_array[$entry_id]['type'] !== $header_backend):
//		$fadeout_temp = "categories-item-fadeout";
//		endif;
	
		// Display maps link
		$map_temp = null;
	    	if (!(empty($information_array[$entry_id]['appendix']['latitude'])) && !(empty($information_array[$entry_id]['appendix']['longitude']))): 
			$map_temp = "yes";
			endif;

		$entries_organized[] = [
			"entry_id"	=> $entry_id,
//			"header"	=> $information_array[$entry_id]['header'],
			"map"		=> $map_temp,
			"indent_count"	=> count($indent_array),
			"indent_array"	=> $indent_array,
			];
	
		// If there are no children, just continue
		if (empty($information_array[$entry_id]['children']['hierarchy'])): continue; endif;
		
		$indent_array_temp = array_merge($indent_array, [$entry_id]);
	
		$result_temp = print_row_loop($header_backend, $information_array[$entry_id]['children']['hierarchy'], $indent_array_temp);
		$entries_organized = array_merge($entries_organized, $result_temp);
	
		endforeach;
	
	return $entries_organized;

	}

	?>
