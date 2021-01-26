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

?>
