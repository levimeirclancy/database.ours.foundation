<? echo "<h1>".$site_info['category_array'][$page_temp]."</h1>";

function hierarchize_entry($entry_id, $indent_array=[]) {
	
	global $information_array;
	
	global $page_temp;
	
	$echo_temp = null;
	
	if (!(isset($information_array[$entry_id]))): return; endif;
	$entry_info = $information_array[$entry_id];
	
	if ($entry_info['type'] !== $page_temp): return; endif;
	
	// If we're doing the first round but it already has parents
	if (!(empty($entry_info['parents'])) && empty($indent_array)):
		
		// We are going to check all the parents
		foreach ($entry_info['parents'] as $entry_id_temp):
		
			// If it has one parent that is the same type, skip now and get to it as a child then
			if ($information_array[$entry_id_temp]['type'] !== $page_temp): return; endif;
		
			endforeach;
	
		endif;
	
	$echo_temp .= "<li><a href='/".$entry_id."'>".$entry_info['header']."</a>";
	
	// If we're doing the first round but it already has parents
	if (!(empty($entry_info['children']))):
		
		$children_temp = 0;
	
		$children_array = [];
	
		// We are going to check all the parents
		foreach ($information_array as $entry_id_temp => $entry_info_temp):
	
			if ($entry_id == $entry_id_temp): continue; endif;
	
			if (!(in_array($entry_id_temp, $entry_info['children']))): continue; endif;
	
			if ($entry_info_temp['type'] !== $page_temp): continue; endif;
	
			$children_array[] = $entry_id_temp;
	
			endforeach;
	
		if (!(empty($children_array))):
	
			$indent_array[] = $entry_id;
	
			$echo_temp .= "<ul>";
	
			foreach ($children_array as $entry_id_temp):

				hierarchize_entry($entry_id_temp, $indent_array);
	
				endforeach;
		
			$echo_temp .= "</ul>";
	
			endif;
	
		endif;
	
	$echo_temp .= "</li>";
	
	return $echo_temp;
	
	}

$indent_ever = 0;

echo "<ul id='entries-list-hierarchical'>";

	$echo_temp = null;

	foreach ($information_array as $entry_id => $entry_info):

		$echo_temp .= hierarchize_entry($entry_id);
		
		endforeach;

	$indent_ever = 0;
	if (strpos($echo_temp, "<ul>") !== FALSE): $indent_ever = 1; endif;

	// If we have indenting
	if ($indent_ever !== 0):
		$tap_temp = [
			"entries-list-alphabetical.show",
			"entries-list-hierarchical.hide",
			"entries-button-alphabetical.hide",
			"entries-button-hierarchical.show",
			];
		echo "<li><div role='button' tabindex='0' id='entries-button-alphabetical' class='navigation-header-item' on='tap:". implode(", ", $tap_temp) ."'>Switch to alphabetical</div><br></li>";
		endif;

	echo $echo_temp;

	echo "</ul>";

if ($indent_ever == 0): footer(); endif;

echo "<ul id='entries-list-alphabetical' hidden>";

	$tap_temp = [
		"entries-list-alphabetical.hide",
		"entries-list-hierarchical.show",
		"entries-button-alphabetical.show",
		"entries-button-hierarchical.hide",
		];
	echo "<li><div role='button' tabindex='0' id='entries-button-hierarchical' class='navigation-header-item' on='tap:". implode(", ", $tap_temp) ."'>Switch to hierarchical</div><br></li>";

	foreach ($information_array as $entry_id => $entry_info):

		if ($entry_info['type'] !== $page_temp): continue; endif;

		echo "<li><a href='/".$entry_id."/'>" . $entry_info['header'] . "</a></li>";

		endforeach;

	echo "</ul>"; ?>
