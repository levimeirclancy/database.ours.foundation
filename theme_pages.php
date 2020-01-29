<? $result_temp = file_get_contents("https://".$domain."/api/sitemap/?order=english");
$information_array = json_decode($result_temp, true);

function print_row_loop ($entry_id=null, $indent_level=0) {
	
	global $login;
	global $domain;
	global $page_temp;
	global $information_array;
	global $logout_hidden;
	
	if (!(array_key_exists($entry_id, $information_array))):
		return 0; endif;
		
	$entry_info = $information_array[$entry_id];

	if ( ($entry_info['type'] == $page_temp) && !(empty($entry_info['parents']['hierarchy'])) && ($indent_level == 0)):
		return 0; endif;
	
		echo "test5";

	
	if ($entry_info['type'] !== $page_temp):
		if (empty($entry_info['children']['hierarchy'])): return 0; endif;
		$skip_temp = 1;
		foreach ($entry_info['children']['hierarchy'] as $child_temp):
			foreach ($information_array[$child_temp]['parents']['hierarchy'] as $parent_temp):
				if ($information_array[$parent_temp]['type'] == $page_temp):
					return 0; endif;
				endforeach;
			if ($information_array[$child_temp]['type'] == $page_temp):
				$skip_temp = 0;
				break; endif;
			endforeach;
		if ($skip_temp == 1): return 0; endif;
		endif;
	
	$count_temp = 0; $indent_temp = null;
	while ($count_temp < $indent_level):
		$indent_temp .= "<span class='categories-item-indent'></span>";
		$count_temp++;
		endwhile;
	
	$fadeout_temp = null;
	if ($entry_info['type'] !== $page_temp):
		$fadeout_temp = "categories-item-fadeout";
		endif;

	 // Launch the row and indent
	echo "<span class='categories-item $fadeout_temp'>";

	// Add the link to the article
	echo $indent_temp . "<a href='/$entry_id/'><span class='categories-item-title'>". $entry_info['header'] ."</span></a>";
	
	// Add the edit link ... we are going to remove this since it is not toggling gracefully with login/logout
//	echo "<a href='/$entry_id/edit/'>";
//	echo "<span class='categories-item-button' $logout_hidden>Edit</span></a>";
	
	// Display maps link
    	if (!(empty($entry_info['appendix']['latitude'])) && !(empty($entry_info['appendix']['longitude']))): 
 		echo "<a href='/".$entry_id."/map/' target='_blank'><span class='categories-item-button'>Map</span></a>";
    		endif;
	
	// Close the row
	echo "</span>";
	 
	if (!(empty($entry_info['children']['hierarchy']))):
		$indent_level++;
		$children_temp = array_intersect(array_keys($information_array), $entry_info['children']['hierarchy']); // sets the ordering
		foreach($children_temp as $child_id):
			print_row_loop ($child_id, $indent_level);
			endforeach;
		endif;
	
	return 1;
	
	}

echo "<h1>".$header_array[$page_temp]."</h1><br>";

$count_temp = 0;
foreach ($information_array as $entry_id => $entry_info):
	if ($entry_info['type'] !== $page_temp): continue; endif;
	$result_temp = print_row_loop ($entry_id, 0);
	$count_temp += $result_temp;
	endforeach;

if (empty($count_temp)): echo "<p>Empty. Consider creating a new entry.</p>"; endif;

footer();
