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
if (!(empty($ordered_published_array))):
	arsort($ordered_published_array);
	$ordered_published_array = array_slice($ordered_published_array, 0, 10);
	echo "<h2>Recently published</h2>";
	echo "<ul>";
	foreach($ordered_published_array as $entry_id => $discard_info):
		echo "<li><a href='/".$entry_id."/'>".$information_array[$entry_id]['header']."</a></li>";
		endforeach;
		echo "</ul>";
	endif;

$ordered_updated_array = [];
foreach ($information_array as $entry_id => $entry_info):
	if (empty($entry_info['date_updated'])): continue; endif;
	if (isset($ordered_published_array[$entry_id])): continue; endif;
	$ordered_updated_array[$entry_id] = $entry_info['date_updated'];
	endforeach; 
if (!(empty($ordered_updated_array))):
	arsort($ordered_updated_array);
	$ordered_updated_array = array_slice($ordered_updated_array, 0, 10);
	echo "<h2>Other updated posts</h2>";
	echo "<ul>";
		foreach($ordered_updated_array as $entry_id => $discard_info):
		echo "<li><a href='/".$entry_id."/'>".$information_array[$entry_id]['header']."</a></li>";
		endforeach;
		echo "</ul>"; 
	endif;

?>
