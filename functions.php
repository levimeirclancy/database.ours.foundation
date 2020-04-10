<? include_once('functions_content.php');
include_once('functions_layout.php');
include_once('functions_sql.php');

function permanent_redirect ($url) {
	header('Cache-Control: no-cache');
	header('Pragma: no-cache');
	header("HTTP/1.1 301 Moved Permanently"); 
	header("Location: $url");
	exit; }

function replace_redirect ($url) {
	echo "<script>";
	echo "window.location.replace('".$url."');";
	echo "</script>";
	exit; }

function random_code($length=16) {
	$characters = [
		"2", "3", "4", "5", "6", "7",
//		"Q", "W", "E", "R". "T", "Y", "U", "I", "O", "P", 
		"Q", "W", "R". "T", "Y", "P", // remove vowels
//		"A", "S", "D", "F", "G", "H", "J", "K", "L", 
		"S", "D", "F", "G", "H", "J", "K", "L", // remove vowels
//		"Z", "X", "C", "V", "B", "N", "M"
		"Z", "C", "V", "B", "N", "M" // remove 'x' for vulgar use
		];
	$upper_offset = count($characters)-1;
	if (!(is_int($length))): $length = 16; endif;
	if ($length < 1): $length = 16; endif;
	$key_temp = null;
	while (strlen($key_temp) < $length): $key_temp .= $characters[rand(0,$upper_offset)]; endwhile;
	return $key_temp; }

function print_terms($terms_array) {
	if (empty($terms_array)): return; endif;
	foreach ($terms_array as $term_id => $term_info):
		$entry_id_array[$term_info['person_id']] = [];
		$entry_id_array[$term_info['position_id']] = [];
		$entry_id_array[$term_info['for_id']] = []; endforeach;
	echo "<table><thead><tr><th>term id</th><th>person</th><th>position</th><th>for</th><th>start</th><th>end</th><th>status</th></tr></thead><tbody>";
	foreach ($terms_array as $term_id => $term_info):
		echo "<tr><td>$term_id</td>";
		echo "<td>".$information_array[$term_info['person_id']]['english_name'][0]."</td>";
		echo "<td>".$information_array[$term_info['position_id']]['english_name'][0]."</td>";
		echo "<td>".$information_array[$term_info['for_id']]['english_name'][0]."</td>";
		echo "<td>".$term_info['date_start']."</td>";
		echo "<td>".$term_info['date_end']."</td>";
		echo "<td>".$term_info['status']."</td></tr>"; endforeach;
	echo "</tbody></table>"; }

function get_users($user_id=null, $table="messenger_users") {
	$selector_temp = null; if (!(empty($location_id))): $selector_temp = "WHERE user_id='$user_id'"; endif;
	$sql_temp = "SELECT * FROM nawend_center.$table $selector_temp";
	$results = fetchall($sql_temp);
	foreach($results as $row): $users_array[$row['user_id']] = $row; endforeach;
	return $users_array; } ?>
