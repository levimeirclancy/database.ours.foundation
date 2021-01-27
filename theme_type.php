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

		if (!(isset($information_array[$entry_id]['type']))):
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

echo "<h1>".$header_array[$page_temp]."</h1>";

$entries_array  = print_row_loop ($page_temp, array_keys($information_array), []);

$indent_ever = 0;

echo "<ul  class='unnested-list' id='entries-list-hierarchical'>";

	echo "<li class='entries-list-item'></li>";

	foreach ($entries_array as $entry_info):

		$count_temp = 0;
		if (!(is_int($entry_info['indent_count']))): $entry_info['indent_count'] = 0; endif;
		echo "<li><span class='categories-item-indent-wrapper'>";
		while ($count_temp < $entry_info['indent_count']):
			echo "<span class='categories-item-indent'></span>";
			$count_temp++;
			$indent_ever++;
			endwhile;
		echo "</span><a href='/".$entry_info['entry_id']."/'><span class='sidebar-navigation-item-title'>";
	
		echo $information_array[$entry_info['entry_id']]['header'] . "</span></a></li>";

		endforeach;

	if ($indent_ever !== 0):
		$tap_temp = [
			"entries-list-alphabetical.show",
			"entries-list-hierarchical.hide",
			"entries-button-alphabetical.hide",
			"entries-button-hierarchical.show",
			];
		echo "<li><div role='button' tabindex='0' id='entries-button-alphabetical' class='navigation-header-item' on='tap:". implode(", ", $tap_temp) ."'>Switch to alphabetical</div></li>";
		endif;

	echo "</ul>";

if ($indent_ever == 0): footer(); endif;

echo "<ul class='unnested-list' id='entries-list-alphabetical' hidden>";

	echo "<li class='entries-list-item'></li>";

	foreach ($information_array as $entry_info):

		if ($entry_info['type'] !== $page_temp): continue; endif;

		echo "<li><a href='/".$entry_info['entry_id']."/'><span class='sidebar-navigation-item-title'>";
	
		echo $information_array[$entry_info['entry_id']]['header'] . "</span></a></li>";

		endforeach;

	$tap_temp = [
		"entries-list-alphabetical.hide",
		"entries-list-hierarchical.show",
		"entries-button-alphabetical.show",
		"entries-button-hierarchical.hide",
		];
	echo "<li><div role='button' tabindex='0' id='entries-button-hierarchical' class='navigation-header-item' on='tap:". implode(", ", $tap_temp) ."'>Switch to hierarchical</div></li>";

	echo "</ul>"; ?>
