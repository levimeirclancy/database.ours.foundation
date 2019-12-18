<? echo "<br><br><br><br><p>Welcome to ". ucfirst($domain) ."</p>";

$result_temp = file_get_contents("https://".$domain."/api/sitemap/?order=english");
$information_array = json_decode($result_temp, true);

// If there is nothing to proceed with ...
if (empty($information_array)): footer(); endif;

echo "<p>";

// How many total entries are there ...
echo "<b>". number_format(count($information_array)) ." total entries.</b><br><br>";

$type_counts_array = [];
$coordinate_counts = 0;
foreach ($information_array as $entry_id => $entry_info):
	if (empty($type_counts_array[$entry_info['type']])): $type_counts_array[$entry_info['type']] = 0; endif;
	$type_counts_array[$entry_info['type']]++;
	if (empty($entry_info['appendix']['latitude']) || empty($entry_info['appendix']['longitude'])): continue; endif;
	$coordinate_counts++;
	endforeach;

foreach ($header_array as $header_backend => $header_frontend):
	if (empty($type_counts_array[$header_backend])): continue; endif;
	echo "<a href='/". $header_backend ."'>". $header_frontend ." — ".number_format($type_counts_array[$header_backend])." entries</a>.<br>";
	endforeach;

// Display how many have GPS coordinates ...
if (!(empty($coordinate_counts))): echo "<br>". number_format($coordinate_counts)." entries with GPS coordinates.<br>"; endif;

echo "</p>";
		
// Show latest edits

footer(); ?>
