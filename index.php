<? session_start();
mb_internal_encoding('UTF-8');
mb_http_output('UTF-8');
include_once('config.php');
$connection_pdo = new PDO(
	"mysql:host=$server;dbname=$database;charset=utf8mb4", 
	$username, 
	$password,
	array(
		PDO::ATTR_TIMEOUT => 3, // in seconds
		PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
		)
	);

if (empty($connection_pdo)): echo "Could not connect to mySQL."; exit; endif;

include_once('functions.php');
	
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
	json_result($domain, "success", null, "Logout was valid.");

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

	// ... Set the cookie in the browser ...
	setcookie("cookie", $new_cookie, time()+86400, '/');

	// ... And make the cookie expiration time available
	setcookie("cookie_time", time()+86400, time()+86400, '/');

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
		setcookie("cookie_time", null, time()+2700, '/');
		permanent_redirect("https://".$domain."/".$page_temp);
		endif;
	endif;

// Pull all the entries
$order_array = $information_array = [];

$order_language = reset($site_info['languages']);
if (isset($_REQUEST['order']) && in_array($_REQUEST['order'], $site_info['languages'])):
	$order_language = $_REQUEST['order'];
	unset($_REQUEST['order']); endif;

// foreach ($_REQUEST as $appendix_key => $appendix_value):
//	if (empty($appendix_value)): unset($_REQUEST[$appendix_key]); endif;
//	if (strpos("#".$appendix_value, ",") && ($appendix_key !== "search")): $appendix_value = explode(",", $appendix_value); endif;
//	if (!(is_array($appendix_value))): $appendix_value = [ $appendix_value ]; endif;
//	foreach ($appendix_value as $key_temp => $value_temp): $appendix_value[$key_temp] = trim($value_temp); endforeach;
//	$_REQUEST[$appendix_key] = $appendix_value;
//	endforeach;

// These counts are used for the navigation sidebar and the home page
$coordinate_counts = 0;

$search_temp = null;
if (isset($_REQUEST['search']) && !(empty($_REQUEST['search'][0]))):
	if (!(is_array($_REQUEST['search']))): $_REQUEST['search'] = [ $_REQUEST['search'] ]; endif;
	$search_temp = $_REQUEST['search'][0];
	endif;

$sql_temp = "SELECT * FROM " . $database . ".information_directory";
foreach($connection_pdo->query($sql_temp) as $row):

	$row['type'] = str_replace('"', null, $row['type']);

	// We want everything if we are editing, deleting, or making new entries
	if (!(in_array($page_temp, ["edit-xhr", "new-xhr", "delete-xhr"]))):
		if (isset($_REQUEST['entry_id']) && !(in_array($row['entry_id'], $_REQUEST['entry_id']))): continue; endif;

		if (isset($_REQUEST['type']) && !(in_array($row['type'], $_REQUEST['type']))): continue; endif;

		if (!(empty($search_temp))):
			$blob_temp = strtolower(implode(" ", $row));
			if (strpos($blob_temp, strtolower($search_temp)) === FALSE): continue; endif;
			endif;
		endif;

	$information_array[$row['entry_id']] = sanitize_dates($row, ["parents", "children", "appendix"]);

	if (isset($_REQUEST['summary']) && ($_REQUEST['summary'] == ["true"])):
		$summary_temp = json_decode($row['summary'], true);
		foreach ($summary_temp as $language_temp => $content_temp):
			$content_temp = body_process($content_temp);
			if (empty($content_temp)): continue; endif;
			$information_array[$row['entry_id']]['summary'][$language_temp] = $content_temp;
			endforeach;
		endif;

	$order_array[$row['entry_id']] = null;
	if (!(empty($information_array[$row['entry_id']]['name'][$order_language]))): $order_array[$row['entry_id']] = $information_array[$row['entry_id']]['name'][$order_language];
	elseif (isset($name_temp)): $order_array[$row['entry_id']] = reset($information_array[$row['entry_id']]['name']); endif;

	endforeach;

if (!(empty($information_array)) && ( ($command_temp !== "search") ||  ($_REQUEST['paths'] == ["true"]) ) ):
	$sql_temp = "SELECT * FROM " . $database . ".information_paths";
	foreach($connection_pdo->query($sql_temp) as $row):
		if ($row['parent_id'] == $row['child_id']): continue; endif;
		if ($row['path_type'] == "parent_id"):
			$row['path_type'] = "hierarchy";
			$temp = $row['child_id'];
			$row['child_id'] = $row['parent_id'];
			$row['parent_id'] = $temp; endif;
		if (array_key_exists($row['parent_id'], $information_array)):
			if (empty($information_array[$row['child_id']]['children'])): $information_array[$row['child_id']]['children'] = []; endif;
//			if (empty($information_array[$row['parent_id']]['children'][$row['path_type']])): $information_array[$row['parent_id']]['children'][$row['path_type']] = []; endif;
			if (empty($row['child_id'])): continue; endif;
//			$information_array[$row['parent_id']]['children'][$row['path_type']][] = $row['child_id']; endif;
			$information_array[$row['parent_id']]['children'][] = $row['child_id']; endif;
		if (array_key_exists($row['child_id'], $information_array)):
			if (empty($information_array[$row['child_id']]['parents'])): $information_array[$row['child_id']]['parents'] = []; endif;
//			if (empty($information_array[$row['child_id']]['parents'][$row['path_type']])): $information_array[$row['child_id']]['parents'][$row['path_type']] = []; endif;
			if (empty($row['parent_id'])): continue; endif;
//			$information_array[$row['child_id']]['parents'][$row['path_type']][] = $row['parent_id']; endif;
			$information_array[$row['child_id']]['parents'][] = $row['parent_id']; endif;
		endforeach;
	endif;

if (!(empty($order_array))):
	asort($order_array);
	// we must put null values at the end
	foreach($order_array as $key_temp => $value_temp):
		if (!(empty($value_temp))): continue; endif;
		unset($order_array[$key_temp]); // remove it from array
		$order_array[$key_temp] = null; // append it to the end
		endforeach;
	$information_array = array_merge($order_array, $information_array); endif;

$information_array = htmlspecialchars_array($information_array);

function htmlspecialchars_array($array_temp) {
	if (!(is_array($array_temp))): 
		if (empty(trim($array_temp))): return null; endif;
		return html_entity_decode($array_temp); endif;
	foreach ($array_temp as $key_temp => $value_temp): $array_temp[$key_temp] = htmlspecialchars_array($value_temp); endforeach;
	return $array_temp; }

// if it is edit-xhr
if ($page_temp == "edit-xhr"):

	if (empty($login)): json_result($domain, "error", null, "Not logged in."); endif;

	if (empty($_POST['entry_id'])): json_result($domain, "error", null, "Needs entry."); endif;

	if (!(array_key_exists($_POST['entry_id'], $information_array))): json_result($domain, "error", null, "Entry does not exist."); endif;

	if (empty($_POST['type'])): json_result($domain, "error", null, "Needs type."); endif;
	if (empty($site_info['category_array'][$_POST['type']])): json_result($domain, "error", null, "Type is not valid."); endif;

	function clean_empty_array($array_temp) {
		if (ctype_space($array_temp)): return null; endif;
		foreach ($array_temp as $key_temp => $value_temp):
//			if (empty($value_temp)): unset($array_temp[$key_temp]); continue; endif;
			if (is_array($value_temp)):
				$value_temp = array_unique($value_temp);
				$array_temp[$key_temp] = clean_empty_array($value_temp);
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
		"entry_id"		=> $_POST['entry_id'],
		"type"			=> $_POST['type'],
		"date_published"	=> date("Y-m-d", (strtotime($_POST['date_published'])+5)), // mySQL DATE column has format YYYY-MM-DD
		"date_updated"		=> date("Y-m-d H:i:s", time()), // mySQL DATETIME column has format YYYY-MM-DD hh:mm:ss
		"name"			=> $_POST['name'],
//		"alternate_name"	=> $_POST['alternate_name'],
		"summary" 		=> $_POST['summary'],
		"body"			=> $_POST['body'],
		"studies"		=> $_POST['studies'],
		"appendix"		=> null,
		];

	$appendix_temp = [];
	if (isset($site_info['appendix_array'][$_POST['type']])):
		foreach ($site_info['appendix_array'][$_POST['type']] as $appendix_key => $appendix_type):
			$appendix_temp[$appendix_key] = null;
			if (isset($_POST[$appendix_key])): $appendix_temp[$appendix_key] = $_POST[$appendix_key];
			elseif (isset($_POST['appendix'][$appendix_key])): $appendix_temp[$appendix_key] = $_POST['appendix'][$appendix_key]; endif;
			endforeach;
		endif;

	$values_temp['appendix'] = $appendix_temp;

	$values_temp = clean_empty_array($values_temp);

	if (empty($values_temp['studies'])): $values_temp['studies'] = " "; endif;

	foreach ($values_temp as $key_temp => $value_temp):
		if (empty($value_temp) || !(is_array($value_temp))): continue; endif;
		$values_temp[$key_temp] = json_encode($value_temp);
		endforeach;

	// prepare statement
	$sql_temp = sql_setup($values_temp, $database.".information_directory");
	$information_directory_statement = $connection_pdo->prepare($sql_temp);
	$information_directory_statement->execute($values_temp);

	$result_temp = execute_checkup($information_directory_statement->errorInfo());

	if ($result_temp !== "success"): json_result($domain, "error", null, $result_temp); endif;

	// Clear out all of its relations

	$sql_temp = "DELETE FROM ".$database.".information_paths WHERE child_id=:entry_id OR parent_id=:entry_id";
	$information_paths_clear_statement = $connection_pdo->prepare($sql_temp);
	$information_paths_clear_statement->execute(["entry_id" => $_POST['entry_id']]);

	$values_temp = [
		"path_id" => null,
		"parent_id" => null,
//		"path_type" => null,
		"child_id" => null ];
	$sql_temp = sql_setup($values_temp, "information_paths");
	$information_paths_statement = $connection_pdo->prepare($sql_temp);

	function paths_check($parent_id, $child_id) {
		
		global $information_array;
		global $entry_info;
		global $connection_pdo;
		global $information_paths_statement;
		
		// It needs to really exist
		if (!(isset($information_array[$parent_id]))): return; endif;
		if (!(isset($information_array[$child_id]))): return; endif;
		
		// And this adds in the correct one
		$values_temp = [
			"path_id" => $parent_id."_".$child_id,
			"parent_id" => $parent_id,
			"child_id" => $child_id ];
		$information_paths_statement->execute($values_temp);
		$result_temp = execute_checkup($information_paths_statement->errorInfo());
		if ($result_temp !== "success"): json_result($domain, "error", null, "Error adding paths: ".$result_temp); endif;
		}
	
	// Initialize these values
	if (empty($_POST['parents'])): $_POST['parents'] = []; endif;
	if (empty($_POST['children'])): $_POST['children'] = []; endif;

	// Remove any parents from the children
	$_POST['children'] = array_diff($_POST['children'], $_POST['parents']);

	// Then add back any parents that were selected...
	foreach($_POST['parents'] as $path_temp):
		paths_check ($path_temp, $_POST['entry_id']);
		endforeach;

	// And addd back any children that were selected, too
	foreach($_POST['children'] as $path_temp):
		paths_check ($_POST['entry_id'], $path_temp);
		endforeach;

	json_result($domain, "success", null, "Successfully updated.");

	endif;

// if it is new-xhr
if ($page_temp == "new-xhr"):

	if (empty($login)): json_result($domain, "error", null, "Not logged in."); endif;

	if (empty($_POST['type'])): json_result($domain, "error", null, "Needs type."); endif;
	if (empty($site_info['category_array'][$_POST['type']])): json_result($domain, "error", null, "Type is not valid."); endif;

	// Create a unique entry_id
	$entry_id = random_code(7);

	// While the entry_id already exists, or is in use in the header array
	while (isset($information_array[$entry_id]) || isset($site_info['category_array'][$entry_id])): $entry_id = random_code(7); endwhile;

	// Redirect to the edit ...
	$values_temp = [
		"entry_id"		=>$entry_id,
		"type"			=> $_POST['type'],
		"date_published"	=> date("Y-m-d", time()), // mySQL DATE column has format YYYY-MM-DD
		"date_updated"		=> date("Y-m-d H:i:s", time()), // mySQL DATETIME column has format YYYY-MM-DD hh:mm:ss
		];

	// prepare statement
	$sql_temp = sql_setup($values_temp, $database.".information_directory");
	$information_directory_statement = $connection_pdo->prepare($sql_temp);
	$information_directory_statement->execute($values_temp);

	$result_temp = execute_checkup($information_directory_statement->errorInfo());

	if ($result_temp !== "success"): json_result($domain, "error", null, $result_temp); endif;

	json_result($domain, "success", "/".$entry_id."/edit/", "Successfully added.");

	endif;


// if it is delete-xhr
if ($page_temp == "delete-xhr"):

	if (empty($login)): json_result($domain, "error", null, "Not logged in."); endif;

	if (empty($_POST['entry_id'])): json_result($domain, "error", null, "No entry id."); endif;

	if (empty($information_array[$_POST['entry_id']])): json_result($domain, "error", null, "Does not exist."); endif;

	// Delete the paths ...
	$sql_temp = "DELETE FROM ".$database.".information_paths WHERE (parent_id=:parent_id) OR (child_id=:child_id)";
	$paths_delete_statement = $connection_pdo->prepare($sql_temp);
	$paths_delete_statement->execute(["parent_id"=>$_POST['entry_id'], "child_id"=>$_POST['entry_id']]);
	$result_temp = execute_checkup($paths_delete_statement->errorInfo());

	if ($result_temp !== "success"): json_result($domain, "error", null, $result_temp); endif;

	// Delete the paths ...
	$sql_temp = "DELETE FROM ".$database.".information_directory WHERE entry_id=:entry_id";
	$directory_delete_statement = $connection_pdo->prepare($sql_temp);
	$directory_delete_statement->execute(["entry_id"=>$_POST['entry_id']]);
	$result_temp = execute_checkup($directory_delete_statement->errorInfo());

	if ($result_temp !== "success"): json_result($domain, "error", null, $result_temp); endif;

	json_result($domain, "success", "/".$_POST['entry_id']."/", "Successfully deleted.");

	endif;


if ($page_temp == "api"):
	if ($command_temp == "coordinate"):
		include_once('api_coordinate.php');
	elseif ($command_temp == "sitemap"):
		json_output($information_array);
	elseif ($command_temp == "page-state"):

		$page_state = [
			"searchTerm"	=> "",
			"login"		=> [
				"loginStatus"	=> "loggedout", 
				"login"		=> null, 
				"countdown"	=> null,
				],
			];

		if (!(empty($login))):
			$page_state['login'] = [
				"inputPasswordType"	=> "password",
				"loginStatus"		=> "loggedin",
				"login"			=> $login['user_id'],
				"countdown"		=> $_COOKIE['cookie_time']-time(),
				];
			endif;

		echo json_output($page_state);

	elseif ($command_temp == "search"):

		$search_array = [
				"searchTerm" => $search_temp,
				"searchCount" => 0,
				"searchResults" => [ ],
				];
		if (empty($information_array) || empty($search_temp)):
//			json_status("error", "No results.");
			json_output($search_array);
		else:
			$search_array['searchCount'] = count($information_array);
			$search_array['searchResults'] = array_values($information_array);
			json_output($search_array); endif;
		endif;
	exit; endif;


if ($page_temp == "sitemap.xml"):
	$url_temp = "/sitemap.xml";
	if ($_SERVER['REQUEST_URI'] !== $url_temp): permanent_redirect("https://".$domain.$url_temp); endif;
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
if (!(empty($page_temp)) && !(isset($site_info['category_array'][$page_temp]))):
	if (!(isset($information_array[$page_temp]))):
		$page_temp = $command_temp = null; // To avoid showing edit options
		amp_header();
		notfound(); endif;

	$url_temp = "/".$page_temp."/";
	if ($command_temp == "ping"):
		$url_temp .= "ping/";
		if ($_SERVER['REQUEST_URI'] !== $url_temp): permanent_redirect("https://".$domain.$url_temp); endif;
		json_output([$page_temp = $information_array[$page_temp]]);
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

	if (!(in_array($_SERVER['REQUEST_URI'], [$url_temp, $url_temp."?view=compact"]))): permanent_redirect("https://".$domain.$url_temp); endif;

	endif;

if (!(empty($page_temp)) && isset($site_info['category_array'][$page_temp])):
	amp_header($site_info['category_array'][$page_temp], $domain."/".$page_temp."/");
	include_once('theme_type.php');
	footer(); endif;

if (!(empty($page_temp)) && isset($information_array[$page_temp])):
	amp_header($information_array[$page_temp]['header'], $domain."/".$page_temp."/");
	include_once('theme_page.php');
	footer(); endif;

if (!(empty($_SERVER['REQUEST_URI'])) && ($_SERVER['REQUEST_URI'] !== "/")):
	permanent_redirect("https://".$domain); endif;

amp_header($domain, $domain);
include_once('theme_home.php');
footer(); ?>
