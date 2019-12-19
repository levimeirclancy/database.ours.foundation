<? $result_temp = file_get_contents("https://".$domain."/api/sitemap/?order=english");
$information_array = json_decode($result_temp, true);

if (empty($information_array)): echo "empty"; footer(); endif;

$unit_array = [];
if (in_array($page_temp, ["location"])):
	$result_temp = file_get_contents("https://".$domain."/api/sitemap/?type=unit");
	$unit_array = json_decode($result_temp, true);
	endif;

function print_row_loop ($entry_id=null, $indent_level=0) {
	
	global $login;
	global $domain;
	global $page_temp;
	global $information_array;
	global $unit_array;
	
	if (!(array_key_exists($entry_id, $information_array))):
		return; endif;
	
	$entry_info = $information_array[$entry_id];

	if ( ($entry_info['type'] == $page_temp) && !(empty($entry_info['parents']['hierarchy'])) && ($indent_level == 0)):
		return; endif;
	
	if ($entry_info['type'] !== $page_temp):
		if (empty($entry_info['children']['hierarchy'])): return; endif;
		$skip_temp = 1;
		foreach ($entry_info['children']['hierarchy'] as $child_temp):
			foreach ($information_array[$child_temp]['parents']['hierarchy'] as $parent_temp):
				if ($information_array[$parent_temp]['type'] == $page_temp):
					return; endif;
				endforeach;
			if ($information_array[$child_temp]['type'] == $page_temp):
				$skip_temp = 0;
				break; endif;
			endforeach;
		if ($skip_temp == 1): return; endif;
		endif;
	
	$count_temp = 0; $indent_temp = null;
	while ($count_temp < $indent_level):
		$indent_temp .= " &nbsp;&nbsp;&nbsp;&nbsp; ";
		$count_temp++;
		endwhile;
	
       	// Display names
	if ($entry_info['type'] !== $page_temp): // add italic
		echo $indent_temp . "<a href='/$entry_id/'>". $entry_info['header'] ."&nbsp;&nbsp;&nbsp;&nbsp;<i>".$entry_info['type']."</i>";
	else:
		echo $indent_temp . "<a href='/$entry_id/'>" . $entry_info['header'] . "</a>";
		endif;

	echo "<a href='/$entry_id/edit/' [class]><i class='material-icons' [class]=\"loginStatus == 'loggedin' ? '' : 'hide'\">edit</i></a>";

	// Display maps link
    	if (!(empty($entry_info['appendix']['latitude'])) && !(empty($entry_info['appendix']['longitude']))):
 		echo "<a href='https://".$domain."/".$entry_id."/map/' target='_blank'><i class='material-icons'>map</i></a>";
    		endif;
	
	echo "<hr>";
	 
	if (!(empty($entry_info['children']['hierarchy']))):
		$indent_level++;
		$children_temp = array_intersect(array_keys($information_array), $entry_info['children']['hierarchy']); // sets the ordering
		foreach($children_temp as $child_id):
			print_row_loop ($child_id, $indent_level);
			endforeach;
		endif;
	}

foreach ($information_array as $entry_id => $entry_info):
//	if (array_intersect($entry_info['parents']['hierarchy'], array_keys($information_array))): continue; endif;
	if (empty($entry_id)): continue; endif;
	print_row_loop ($entry_id, 0);
	endforeach;

footer();
