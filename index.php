<? session_start();
mb_internal_encoding('UTF-8');
mb_http_output('UTF-8');
include_once('config.php');
$connection_pdo = new PDO("mysql:host=$server;dbname=$database;charset=utf8mb4", $username, $password);

include_once('functions.php');

$site_info = ["languages"=>["english", "sorani", "arabic"]];
	
$url_temp = $login = $page = $action = null;

$page_temp = $command_temp = null;
$url_temp = explode("/",$_SERVER['REQUEST_URI']);
if (!(empty($url_temp['1']))): $page_temp = $url_temp['1']; endif;
if (!(empty($url_temp['2']))): $command_temp = $url_temp['2']; endif;


//if the page is set to log out then logout
if ($page_temp == "logout-xhr"):

	// Clear cookie in the browser
	setcookie("cookie", null, time()+2700, '/');

	// Sanitize the variables
	$_COOKIE['cookie'] = $login = $page_temp = null;

	// Echo that it worked
	json_result($domain, "success", "/", "Logout was valid.");

	endif;


// if we are trying to log in, then check the login
if ($page_temp == "login-xhr"):

	// Validate that e-mail address is not empty
	$_POST['checkpoint_email'] = trim($_POST['checkpoint_email']);
	if (empty($_POST['checkpoint_email'])): json_result($domain, "error", null, "E-mail address cannot be empty."); endif;

	// Validate that password is not empty
	$_POST['checkpoint_password'] = trim($_POST['checkpoint_password']);
	if (empty($_POST['checkpoint_password'])): json_result($domain, "error", null, "Password cannot be empty."); endif;

	// Make lower-case for consistency
	$_POST['checkpoint_email'] = strtolower($_POST['checkpoint_email']);

	// Build the SHA1 hash of e-mail and password
	$hash = sha1($_POST['checkpoint_email'].$_POST['checkpoint_password']);

	// Ping the database
	foreach ($connection_pdo->query("SELECT * FROM $database.users WHERE `hash`='$hash'") as $row):
		$login = ["user_id" => $row['user_id'], "email" => $row['email']];
		endforeach;

	// There were no results and so it failed	
	if (empty($login)): json_result($domain, "error", null, "Login was invalid."); endif;

	// There was a result, so generate a cookie hash
	$_COOKIE['cookie'] = $new_cookie = sha1($login['user_id'].time());

	// We will need to prepare this query instead ...
	$cookie_statement = $connection_pdo->prepare("UPDATE $database.users SET cookie='". $new_cookie ."' WHERE user_id='".$login['user_id']."'");

	// Set the cookie in the database ...
	$cookie_statement->execute();

	// ... det the cookie in the browser ...
	setcookie("cookie", $new_cookie, time()+86400, '/');

	// ... and tell the login form that it worked
	json_result($domain, "success", null, "Login was valid.");

	endif;


// if there is a cookie then double-check it
if (!(empty($_COOKIE['cookie']))):
	$login = null;
	foreach ($connection_pdo->query("SELECT * FROM users WHERE cookie='".$_COOKIE['cookie']."'") as $row):
		$login = ["user_id" => $row['user_id'], "email" => $row['email']]; endforeach;
	if (empty($login)):
		setcookie("cookie", null, time()+2700, '/');
		permanent_redirect("https://".$domain."/".$page_temp);
		endif;
	endif;

// To display the login or logout buttons
$login_hidden = $logout_hidden = null;
if (empty($login)): $logout_hidden = "hidden"; // if we are not logged in
elseif (!(empty($login))): $login_hidden = "hidden"; endif; // if we are logged in


// this is the header index
$header_array = [
	"location" => "Regions",
	"village" => "Villages",
	"place" => "Places",
	"person" => "People",
	"party" => "Parties",
	"position" => "Positions",
	"demographic" => "Demographics",
	"term" => "Terms",
	"event" => "Events",	
	"topic" => "Topics",
	"article" => "Articles" ];


// if it is delete-xhr
if ($command_temp == "delete-xhr"):

	// Delete this ...
	$sql_temp = "DELETE FROM ".$database.".information_paths WHERE (parent_id=:parent_id) OR (child_id=:child_id)";
	$paths_delete_statement = $connection_pdo->prepare($sql_temp);
	$paths_delete_statement->execute(["parent_id"=>$page_temp, "child_id"=>$page_temp]);
	execute_checkup($paths_delete_statement->errorInfo(), "deleting ".$_POST['entry_id']." in information_paths");

	$sql_temp = "DELETE FROM ".$database.".information_directory WHERE entry_id=:entry_id";
	$directory_delete_statement = $connection_pdo->prepare($sql_temp);
	$directory_delete_statement->execute(["entry_id"=>$page_temp]);
	execute_checkup($directory_delete_statement->errorInfo(), "deleting ".$_POST['entry_id']." in information_directory");

	// Then redirect ...

	exit;

	endif;




// if it is edit-xhr
if ($command_temp == "edit-xhr"):

	function clean_empty_array($array_temp) {
		if (ctype_space($array_temp)): return null; endif;
		foreach ($array_temp as $key_temp => $value_temp):
			if (empty($value_temp)): unset($array_temp[$key_temp]); continue; endif;
			if (is_array($value_temp)): $array_temp[$key_temp] = clean_empty_array($value_temp);
			else:
				$value_temp = str_replace("[[[", "\n\n[[[", $value_temp);
				$value_temp = str_replace("]]]", "]]]\n\n", $value_temp);
				$value_temp = preg_replace("/\r\n/", "\n", $value_temp);
				$value_temp = preg_replace('/(?:(?:\r\n|\r|\n)\s*){2}/s', "\n\n", $value_temp);
				$value_temp = trim($value_temp);
				if (ctype_space($value_temp)): $value_temp = null; endif;		
				$array_temp[$key_temp] = htmlspecialchars($value_temp);
				endif;
			endforeach;
		return $array_temp; }

	$values_temp = [
		"entry_id" => $_POST['entry_id'],
		"type" => $_POST['type'],
		"name" => $_POST['name'],
		"alternate_name" => $_POST['alternate_name'],
		"summary" => $_POST['summary'],
		"body" => $_POST['body'],
		"studies" => $_POST['studies'],
		"appendix" => $_POST['appendix'] ];

	$values_temp = clean_empty_array($values_temp);

	foreach ($values_temp as $key_temp => $value_temp):
		if (empty($value_temp) || !(is_array($value_temp))): continue; endif;
		$values_temp[$key_temp] = json_encode($value_temp);
		endforeach;

	// prepare statement
	$sql_temp = sql_setup($values_temp, $database.".information_directory");
	$information_directory_statement = $connection_pdo->prepare($sql_temp);
	$information_directory_statement->execute($values_temp);

	execute_checkup($information_directory_statement->errorInfo(), "updating ".$_POST['entry_id']." in information_directory");

	$values_temp = [
		"path_id" => null,
		"parent_id" => null,
		"path_type" => null,
		"child_id" => null ];
	$sql_temp = sql_setup($values_temp, "information_paths");
	$information_paths_statement = $connection_pdo->prepare($sql_temp);

	$sql_temp = "DELETE FROM ".$database.".information_paths WHERE (path_id=:path_id) OR (parent_id=:parent_id AND path_type=:path_type AND child_id=:child_id)";
	$information_paths_remove_statement = $connection_pdo->prepare($sql_temp);

	$path_types_check_array = array_merge(
		(array)array_keys($_POST['parents']),
		(array)array_keys($entry_info['parents']),
		(array)array_keys($_POST['children']),
		(array)array_keys($entry_info['children']) );

	function paths_check($relationship_type, $parent_id, $path_type, $child_id, $query_id) {
		global $entry_info;
		global $_POST;
		global $connection_pdo;
		global $information_paths_remove_statement;
		global $information_paths_statement;
		$values_temp = [
			"path_id" => $parent_id."_".$child_id."_".$path_type,
			"parent_id" => $parent_id,
			"path_type" => $path_type,
			"child_id" => $child_id ];
		if (in_array("clear_selection", $_POST[$relationship_type][$path_type])): $_POST[$relationship_type][$path_type] = []; endif;
		if (in_array($query_id, $entry_info[$relationship_type][$path_type]) && !(in_array($query_id, $_POST[$relationship_type][$path_type]))):
			$information_paths_remove_statement->execute($values_temp);
			execute_checkup($information_paths_remove_statement->errorInfo(), "removing path in information_paths");
		elseif (!(in_array($query_id, $entry_info[$relationship_type][$path_type])) && in_array($query_id, $_POST[$relationship_type][$path_type])):
			$information_paths_statement->execute($values_temp);
			execute_checkup($information_paths_statement->errorInfo(), "adding path in information_paths");
			endif; }

	foreach ((array)$path_types_check_array as $path_type):

		if (is_int($path_type)): continue; endif;

		if (empty($_POST['parents'][$path_type])): $_POST['parents'][$path_type] = []; endif;
		if (empty($_POST['children'][$path_type])): $_POST['children'][$path_type] = []; endif;
		if (empty($entry_info['parents'][$path_type])): $entry_info['parents'][$path_type] = []; endif;
		if (empty($entry_info['children'][$path_type])): $entry_info['children'][$path_type] = []; endif;
		$_POST['parents'][$path_type] = (array)$_POST['parents'][$path_type];
		$_POST['children'][$path_type] = (array)$_POST['children'][$path_type];
		$entry_info['parents'][$path_type] = (array)$entry_info['parents'][$path_type];
		$entry_info['children'][$path_type] = (array)$entry_info['children'][$path_type];

		$parents_temp = array_merge($_POST['parents'][$path_type], $entry_info['parents'][$path_type]);
		foreach($parents_temp as $path_temp):
			paths_check ("parents", $path_temp, $path_type, $_POST['entry_id'], $path_temp);
			endforeach;

		$children_temp = array_merge($_POST['children'][$path_type], $entry_info['children'][$path_type]);
		foreach($children_temp as $path_temp):
			paths_check ("children", $_POST['entry_id'], $path_type, $path_temp, $path_temp);
			endforeach;

		endforeach;

	$entry_info = nesty_page($page_temp);
	$entry_info = $entry_info[$page_temp];

	// Give what saved ...

	exit;

	endif;


// if it is add-xhr
if ($command_temp == "add-xhr"):

	if (empty($_POST['type'])): json_result($domain, "error", null, "Needs type."); endif;
	if (empty($header_array[$_POST['type']])): json_result($domain, "error", null, "Type is not valid."); endif;

	// Create a unique entry_id
	$entry_id = random_code(7);

	$result_temp = file_get_contents("https://".$domain."/api/sitemap/?order=english");
	$information_array = json_decode($result_temp, true);

	while (isset($information_array[$entry_id])): $entry_id = random_code(7); endif;

	// Redirect to the edit ...
	$values_temp = [
		"entry_id" =>	$entry_id,
		"type" =>	$_POST['type'],
		];

	// prepare statement
	$sql_temp = sql_setup($values_temp, $database.".information_directory");
	$information_directory_statement = $connection_pdo->prepare($sql_temp);
	$information_directory_statement->execute($values_temp);

	$result_temp = execute_checkup($information_directory_statement->errorInfo());

	if ($result_temp !== "success"): json_result($domain, "error", null, $result_temp); endif;

	json_result($domain, "success", "/".$entry_id."/edit/", "Successfully added.");

	endif;

if (($page_temp == "new-xhr") && !(empty($login))):

	// Add new entry

	// And redirect to it

	endif;

if ($page_temp == "api"):
	if ($command_temp == "coordinate"): include_once('api_coordinate.php');
	elseif ($command_temp == "sitemap"): include_once('api_sitemap.php'); endif;
	exit; endif;

if ($page_temp == "sitemap.xml"):
	$url_temp = "/sitemap.xml";
	if ($_SERVER['REQUEST_URI'] !== $url_temp): permanent_redirect("https://".$domain.$url_temp); endif;
	$result_temp = file_get_contents("https://".$domain."/api/sitemap/?order=english");
	$information_array = json_decode($result_temp, true);
	echo "<?xml version='1.0' encoding='UTF-8'?>";
	echo "<urlset xmlns='http://www.sitemaps.org/schemas/sitemap/0.9'>";
	foreach ($information_array as $entry_id => $entry_info):
		echo "<url><loc>https://".$domain."/".$entry_id."/</loc>";
		echo "</url>";
		endforeach;
	echo "</urlset>";
	exit; endif;

// if the user created an entry then save it
if (!(empty($login)) && !(empty($_POST['create_entry']))):
	$values_temp = [
		"entry_id"=>$_POST['entry_id'],
		"type"=>$_POST['type']];
	$sql_temp = sql_setup($values_temp, "$database.entries_directory");
	$create_entry_statement = $connection_pdo->prepare($sql_temp); $create_entry_statement->execute($values_temp);
	execute_checkup($create_entry_statement->errorInfo(), "creating entry ".$_POST['entry_id']);
	endif;

$layout_nodisplay_temp = null;
if (!(empty($_REQUEST['view'])) && ($_REQUEST['view'] == "compact")): $layout_nodisplay_temp = "layout='nodisplay'"; endif;

// if the $page_temp is valid then go ahead and see if it exists
if (!(empty($page_temp)) && ($page_temp !== "new") && !(isset($header_array[$page_temp]))):
	$information_array = nesty_page($page_temp);
	if (!(isset($information_array[$page_temp]))):
		amp_header();
		notfound(); endif;
	$url_temp = "/".$page_temp."/";
	if ($command_temp == "ping"):
		$url_temp .= "ping/";
		if ($_SERVER['REQUEST_URI'] !== $url_temp): permanent_redirect("https://".$domain.$url_temp); endif;
		echo json_encode($information_array);
		exit; endif;

	if (($command_temp == "map") && !(empty($information_array[$page_temp]['appendix']['latitude'])) && !(empty($information_array[$page_temp]['appendix']['longitude']))):
		permanent_redirect("https://google.com/maps/place/".$information_array[$page_temp]['appendix']['latitude'].",".$information_array[$page_temp]['appendix']['longitude']);
		exit; endif;

	if (($command_temp == "edit") && !(empty($login))):
		$url_temp .= "edit/";
		if ($_SERVER['REQUEST_URI'] !== $url_temp): permanent_redirect("https://".$domain.$url_temp); endif;
	    	amp_header();
		include_once('admin_page.php');
		footer(); endif;

	if (($command_temp == "edit") && empty($login)):
		permanent_redirect("https://".$domain.$url_temp);
		endif;

	// generate messenger code
	if (!(empty($page_access_token))):
		if (!(is_dir("messenger"))): mkdir("/messenger", 0755, true); endif;
		if (file_exists("messenger/".$page_temp.".png") && (filemtime("/messenger/".$page_temp.".png") < time("- 1 days")) ): generate_messenger_code($entry_id); endif;
		if (!(file_exists("messenger/".$page_temp.".png"))): generate_messenger_code($page_temp); endif;
		endif;

	if (($command_temp == "flyer") && !(empty($page_access_token)) && file_exists("messenger/".$page_temp.".png")):
		$url_temp .= "flyer/";
		if ($_SERVER['REQUEST_URI'] !== $url_temp): permanent_redirect("https://".$domain.$url_temp); endif;
		include_once('theme_flyer.php');
		exit; endif;

	if (($command_temp == "flyer") && !(empty($telegram_bot))):
		$url_temp .= "flyer/";
		if ($_SERVER['REQUEST_URI'] !== $url_temp): permanent_redirect("https://".$domain.$url_temp); endif;
		include_once('theme_flyer.php');
		exit; endif;

	if (!(in_array($_SERVER['REQUEST_URI'], [$url_temp, $url_temp."?view=compact"]))): permanent_redirect("https://".$domain.$url_temp); endif;

	endif;

// view entries lists
if (isset($header_array[$page_temp])):
	$url_temp = "/".$page_temp."/";
	if ($_SERVER['REQUEST_URI'] !== $url_temp): permanent_redirect("https://".$domain.$url_temp); endif;
	amp_header($page_temp." list | ".$domain, $domain."/".$page_temp."/");
	include_once('theme_pages.php');
	footer(); endif;

if (!(empty($page_temp)) && isset($information_array[$page_temp])):
	amp_header(implode(" • ", $information_array[$page_temp]['name'])." | ".$domain, $domain."/".$page_temp."/");
	include_once('theme_page.php');
	footer(); endif;

if (!(empty($_SERVER['REQUEST_URI'])) && ($_SERVER['REQUEST_URI'] !== "/")): permanent_redirect("https://".$domain); endif;
amp_header($domain, $domain."/".$page_temp."/");
include_once('theme_home.php');
footer(); ?>
