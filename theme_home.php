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
	if (intval(strtotime($entry_info['date_published'])) >= intval(strtotime("-28 days"))): $count_published_recent++; endif;

	if (empty($entry_info['date_updated'])): continue; endif;

	if (intval(strtotime($entry_info['date_updated'])) >= intval(strtotime("-28 days"))): $count_updated_recent++; endif;
	$ordered_updated_array[$entry_id] = $entry_info['date_updated'];	

	endforeach; 

if (count($information_array) > 1):

	echo "<p>There are ".number_format(count($information_array))." entries in the database.";

	if ($count_published_recent > 1): " There have been ".number_format($count_published_recent)." entries published in the last 28 days."; endif;

	if (($count_updated_recent - $count_published_recent) > 1): " Also, there have been an additional ".number_format($count_updated_recent - $count_published_recent)." entries updated in the same period."; endif;

	echo "</p>";

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
