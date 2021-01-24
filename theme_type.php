<? function print_row_loop ($header_backend, $entries_array=null, $indent_array=[]) {
	
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
//			"map"		=> $map_temp,
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

$entries_array  = print_row_loop ($page_temp, array_keys($information_array), []);

echo "<ul>";

foreach ($entries_array as $entry_info):

	$count_temp = 0;
	if (!(is_int($entry_info['indent_count']))): $entry_info['indent_count'] = 0; endif;
	echo "<li class='sidebar-navigation-item'><span class='categories-item-indent-wrapper'>"'
	while ($count_temp < $entry_info['indent_count']):
		echo "<span class='categories-item-indent'></span>";
		$count_temp++;
		endwhile;
	echo "</span>";
	
	echo $information_array[$entry_info['entry_id']]['header'] . "</li>";

	endforeach;

?>
