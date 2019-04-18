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
		$indent_temp .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
		$count_temp++;
		endwhile;
	
	echo "<tr id='$entry_id'>";
	
       	// display names
	if ($entry_info['type'] !== $page_temp):
		echo "<td colspan='all' class='fadeout'>";
		if (!(empty($login))): echo "<a href='/$entry_id/edit/'><i class='material-icons'>edit</i></a> &nbsp;&nbsp;&nbsp;&nbsp;"; endif;
		echo $indent_temp . "<a href='/$entry_id/'>".$entry_info['header']."&nbsp;&nbsp;&nbsp;&nbsp;<i>".$entry_info['type']."</i></td></tr>";
	else:
		echo "<td>";
		if (!(empty($login))): echo "<a href='/$entry_id/edit/'><i class='material-icons fadeout'>edit</i></a> &nbsp;&nbsp;&nbsp;&nbsp;"; endif;
		echo $indent_temp . "<a href='/$entry_id/'>" . $entry_info['header'] . "</a></span></td>";
		
	    	// display latitude, longitude, and maps
		echo "<td>";
    		if (!(empty($entry_info['appendix']['latitude'])) && !(empty($entry_info['appendix']['longitude']))):
 			echo "<a href='https://".$domain."/".$entry_id."/map/' target='_blank'><i class='material-icons'>map</i></a>";
    			endif;
		echo "</td>";
 
		// show unit type
		echo "<td>";
		if (!(empty($unit_array[$entry_info['unit_id'][0]]['name']))):
			echo "<a href='/".$entry_info['unit_id'][0]."/'>".implode(" - ", $unit_array[$entry_info['unit_id'][0]]['name'])." Map link</a>";
			endif;
		echo "</td>";
	
		echo "<td>";
		if (count($entry_info['parents']['hierarchy']) > 0):
			echo number_format(count($entry_info['parents']['hierarchy']));
			endif;
		echo "</td>";

		endif;

    	echo "</tr>";

	if (!(empty($entry_info['children']['hierarchy']))):
		$indent_level++;
		$children_temp = array_intersect(array_keys($information_array), $entry_info['children']['hierarchy']); // sets the ordering
		foreach($children_temp as $child_id):
			print_row_loop ($child_id, $indent_level);
			endforeach;
		endif;
	} ?>

<table>
<thead><tr>
	<th>Name</th>
	<th>Map</th>
	<th>Type</th>
	<th>Nests</th>
	</tr></thead>
<tbody>
	
<? foreach ($information_array as $entry_id => $entry_info):
//	if (array_intersect($entry_info['parents']['hierarchy'], array_keys($information_array))): continue; endif;
	if (empty($entry_id)): continue; endif;
	print_row_loop ($entry_id, 0);
	endforeach; ?>

</tbody></table>

<? footer(); ?>
