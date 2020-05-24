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

function print_row_loop ($header_backend, $entry_id=null, $indent_array=[]) {
	
	// If its parent is itself and has already been outputted
	if (in_array($entry_id, $indent_array)):
		return; endif;

	// Make this available
	global $information_array;
	
	// If the entry does not exist
	if (!(array_key_exists($entry_id, $information_array))):
		return; endif;
	
	// If we're doing the first round but it already has parents
	if (!(empty($information_array[$entry_id]['parents']['hierarchy'])) && empty($indent_array)):
	
		// We will assume for the sake of checking that it will be outputted
		$result_temp = 1;
	
		// 
//		if ($information_array[$entry_id]['type'] == $header_backend): 
	
		// However, if all its parents are the same type then we'll just output it there
		foreach ($information_array[$entry_id]['parents']['hierarchy'] as $entry_id_temp):
		
			// If its parents are all another type, it will get to go ahead
			if ($information_array[$entry_id_temp]['type'] !== $header_backend): continue; endif;
		
			// If none of these cases are met, it will not be outputted
			$result_temp = 0;
	
			endforeach;
	
		if ($result_temp == 0): return; endif;
	
		endif;
		
	if ($information_array[$entry_id]['type'] !== $header_backend):
		if (empty($information_array[$entry_id]['children']['hierarchy'])): return; endif;
		$skip_temp = 1;
		foreach ($information_array[$entry_id]['children']['hierarchy'] as $child_temp):
			foreach ($information_array[$child_temp]['parents']['hierarchy'] as $parent_temp):
				if ($information_array[$parent_temp]['type'] == $header_backend):
					return; endif;
				endforeach;
			if ($information_array[$child_temp]['type'] == $header_backend):
				$skip_temp = 0;
				break; endif;
			endforeach;
		if ($skip_temp == 1): return; endif;
		endif;

	return ["test6"];
	
	$indent_count = count($indent_array);
	if ($indent_count > 16): $indent_count = 16; endif; // After 16, the indents just flatten out
	
//	$fadeout_temp = null;
//	if ($information_array[$entry_id]['type'] !== $header_backend):
//		$fadeout_temp = "categories-item-fadeout";
//		endif;
	
	$entry_list = [];
	
	// Display maps link
	$map_temp = null;
    	if (!(empty($information_array[$entry_id]['appendix']['latitude'])) && !(empty($information_array[$entry_id]['appendix']['longitude']))): 
		$map_temp = "yes";
		endif;

	$entry_list[] = [
		"entry_id"	=> $entry_id,
		"header"	=> $information_array[$entry_id]['header'],
		"map"		=> $map_temp,
		"indent_count"	=> $indent_count,
		];
	
	// If no children then we cut it off here
	if (empty($information_array[$entry_id]['children']['hierarchy'])):
		return $entry_list;
		endif;
		
	$indent_array[] = $entry_id;
	
	// Sets the ordering
	$children_temp = array_intersect(array_keys($information_array), $information_array[$entry_id]['children']['hierarchy']);
	foreach($children_temp as $child_id):
		if ($child_id == $entry_id): continue; endif;
		$result_temp = print_row_loop($header_backend, $child_id, $indent_array);
		$entry_list = array_merge($entry_list, $result_temp);
		endforeach;
	
	return $entry_list;
	
	} ?>
