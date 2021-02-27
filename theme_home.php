<? 

if (!(empty($publisher))):
	echo "<h1>".$publisher."</h1>";
	endif;

if (empty($information_array)):
	echo "<p>No entries yet.</p>";
	footer(); endif;


$ordered_published_array = $ordered_updated_array = [];
$count_published_recent = $count_updated_recent = 0;
foreach ($information_array as $entry_id => $entry_info):

	$ordered_published_array[$entry_id] = $entry_info['date_published'];
	if (strtotime($entry_info['date_published']) >= strtotime("-28 days")): $count_published_recent++; endif;

	if (empty($entry_info['date_updated'])): continue; endif;

	if (strtotime($entry_info['date_updated']) >= strtotime("-28 days")): $count_updated_recent++; endif;
	$ordered_updated_array[$entry_id] = $entry_info['date_updated'];	

	endforeach; 

if (count($information_array) > 1):

	$list_temp = null;
	$list_temp .= "+++Entries in the database";
	$list_temp .= "++++++".number_format(count($information_array));

	if ($count_published_recent > 1):
		$list_temp .= "+++Entries published in last 28 days";
		$list_temp .= "++++++".number_format($count_published_recent)." entries";
		endif;

	if (($count_updated_recent - $count_published_recent) > 1):
		$list_temp .= "+++Entries updated in last 28 days";
		$list_temp .= "++++++".number_format($count_updated_recent - $count_published_recent)." entries";
		endif;

	echo body_process("+-+-+".$list_temp."+-+-+");

	endif;

arsort($ordered_published_array);
$ordered_published_array = array_slice($ordered_published_array, 0, 10);

echo "<h2>Recently published</h2>";
echo "<div class='navigation-list'>";
$list_temp = null;
foreach($ordered_published_array as $entry_id => $discard_info):
	$list_temp .= "+++{{{".$entry_id."}}}";
	endforeach;
echo body_process("+-+-+".$list_temp."+-+-+");
echo "</div>";

arsort($ordered_updated_array);
$ordered_updated_array = array_diff_key($ordered_updated_array, $ordered_published_array);
$ordered_updated_array = array_slice($ordered_updated_array, 0, 10);
if (!(empty($ordered_updated_array))):
	echo "<h2>Other updated posts</h2>";
	$list_temp = null;
	echo "<div class='navigation-list'>";
		foreach($ordered_updated_array as $entry_id => $discard_info):
		$list_temp .= "+++{{{".$entry_id."}}}";
		endforeach;
	echo body_process("+-+-+".$list_temp."+-+-+");
	echo "</div>"; 
	endif;
?>
