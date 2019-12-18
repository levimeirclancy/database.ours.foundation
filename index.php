<? session_start();
mb_internal_encoding('UTF-8');
mb_http_output('UTF-8');
include_once('config.php');
$connection_pdo = new PDO("mysql:host=$server;dbname=$database;charset=utf8mb4", $username, $password);

include_once('functions.php');

$site_info = ["languages"=>["english", "sorani", "arabic"]];
	
$url_temp = $login = $page = $action = null;

$page_temp = $slug_temp = $command_temp = null;
$url_temp = explode("/",$_SERVER['REQUEST_URI']);
if (!(empty($url_temp['1']))): $page_temp = $url_temp['1']; endif;
if (!(empty($url_temp['2']))): $command_temp = $url_temp['2']; endif;

//if the page is set to log out then logout
if ($page_temp == "logout"):
	setcookie("cookie", null, time()+2700, '/');
	$_COOKIE['cookie'] = $login = $page_temp = null;
	permanent_redirect("https://".$domain);
	endif;

// if we are trying to log in, then check the login
if (isset($_POST['checkpoint_email']) && isset($_POST['checkpoint_password'])):
	$_POST['checkpoint_email'] = strtolower($_POST['checkpoint_email']);
	$hash = sha1($_POST['checkpoint_email'].$_POST['checkpoint_password']);
	foreach ($connection_pdo->query("SELECT * FROM $database.users WHERE `hash`='$hash'") as $row):
		$login = ["user_id" => $row['user_id'], "email" => $row['email']];
		endforeach;
	if (empty($login)):
		json_result($domain, "error", null, "Login was invalid.");
		endif;
	$_COOKIE['cookie'] = $new_cookie = sha1($login['user_id'].time());
	$cookie_statement = $connection_pdo->prepare("UPDATE $database.users SET cookie='$new_cookie' WHERE user_id='".$login['user_id']."'");
	$cookie_statement->execute();
	setcookie("cookie", $new_cookie, time()+86400, '/');
	json_result($domain, "success", null, "Login was valid.");
	endif;

// if there is a cookie then double-check it
if (!(empty($_COOKIE['cookie']))):
	$login = null;
	foreach ($connection_pdo->query("SELECT * FROM users WHERE cookie='".$_COOKIE['cookie']."'") as $row):
		$login = ["user_id" => $row['user_id'], "email" => $row['email']]; endforeach;
	if (empty($login)):
		setcookie("cookie", null, time()+2700, '/');
		permanent_redirect("https://".$domain);
		endif;
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

if (!(empty($page_temp)) && ($page_temp == "new") && !(empty($login))):
	html_header();
	include_once('admin_page.php');
	footer(); endif;

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
	    	html_header();
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
