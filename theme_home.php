<? 

if (!(empty($publisher))):
	echo "<h1>".$publisher."</h1>";
	endif;

if (empty($information_array)):
	echo "<p>No entries yet.</p>";
	footer();
	endif;

$ordered_published_array = [];
foreach ($information_array as $entry_id => $entry_info):
	$ordered_published_array[$entry_id] = $entry_info['date_published'];
	endforeach; 
arsort($ordered_published_array);
$ordered_published_array = array_slice($ordered_published_array, 0, 10);

echo "<h2>Recently published</h2>";
echo "<ul>";
foreach($ordered_published_array as $entry_id => $discard_info):
	echo "<li><a href='/".$entry_id."/'>".$information_array[$entry_id]['header']."</a></li>";
	endforeach;
	echo "</ul>"; 

$ordered_modified_array = [];
foreach ($information_array as $entry_id => $entry_info):
	if (empty($entry_info['date_modified'])): continue; endif;
	$ordered_modified_array[$entry_id] = $entry_info['date_modified'];
	endforeach; 
arsort($ordered_modified_array);
print_r($ordered_modified_array);
$ordered_array = array_slice($ordered_modified_array, 0, 10);

echo "<h2>Recently modified</h2>";
echo "<ul>";
foreach($ordered_modified_array as $entry_id => $discard_info):
	echo "<li><a href='/".$entry_id."/'>".$information_array[$entry_id]['header']."</a></li>";
	endforeach;
	echo "</ul>"; 

?>
