<? function html_header($title=null) {

	global $login;
	global $color;
	global $google_analytics_code;
	global $domain;
	
	if (empty($title)): $title = $domain; endif;

	
	// these must open the document
	echo "<!doctype html>" . "<html lang='en'>";

	// open html head
	echo "<head>" . "<meta charset='utf-8'>";

//	echo "<base href='/' />";

	echo "<style>";
	include_once('style.css');
	include_once('style_nesty.css');
	echo "</style>";
	
	// google analytics
	if (!(empty($google_analytics_code))):
		echo "<script>"; ?>
			(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
			(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
			m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
			})(window,document,'script','https://www.google-analytics.com/analytics.js','ga');
			ga('create', '<? echo $google_analytics_code ?>', 'auto');
			ga('send', 'pageview');
		<? echo "</script>";
		endif;

	echo "<title>" . $title . "</title>";

	echo '<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">';
	
	echo "</head><body>";
	echo "<div id='fb-root'></div>";
	echo "<script>"; ?>
		(function(d, s, id) {
			var js, fjs = d.getElementsByTagName(s)[0];
			if (d.getElementById(id)) return;
			js = d.createElement(s); js.id = id;
			js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.8&appId=342976929126894";
			fjs.parentNode.insertBefore(js, fjs);
			}(document, 'script', 'facebook-jssdk'));
	<? echo "</script>"; }


function amp_header($title=null, $canonical=null) {
	global $domain;
	global $publisher;
	global $google_analytics_code;
	global $color;
	global $page_temp;
	global $slug_temp;
	global $command_temp;
	global $header_array;
	global $information_array;
	global $login;
	
	if (empty($title)): $title = $domain; endif;

	// https://www.ampproject.org/docs/tutorials/create/basic_markup

	// these must open the document
	echo "<!doctype html>" . "<html amp lang='en'>";

	// open html head
	echo "<head>" . "<meta charset='utf-8'>";

	// for google analytics, this must precede amp js
	if (!(empty($google_analytics_code))):
		echo '<script async custom-element="amp-analytics" src="https://cdn.ampproject.org/v0/amp-analytics-0.1.js"></script>';
		endif;

	// amp js
	echo "<script async src='https://cdn.ampproject.org/v0.js'></script>";

	if (empty($canonical)): $canonical=$domain; endif; // do some sort of url validation here
	echo "<link rel='canonical' href='https://$canonical'>"; // must define canonical url for amp

	// amp boilerplate code https://www.ampproject.org/docs/reference/spec/amp-boilerplate
	echo "<style amp-boilerplate>body{-webkit-animation:-amp-start 8s steps(1,end) 0s 1 normal both;-moz-animation:-amp-start 8s steps(1,end) 0s 1 normal both;-ms-animation:-amp-start 8s steps(1,end) 0s 1 normal both;animation:-amp-start 8s steps(1,end) 0s 1 normal both}@-webkit-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-moz-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-ms-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-o-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}</style><noscript><style amp-boilerplate>body{-webkit-animation:none;-moz-animation:none;-ms-animation:none;animation:none}</style></noscript>";

	// for amp-form
	echo '<script async custom-element="amp-form" src="https://cdn.ampproject.org/v0/amp-form-0.1.js"></script>';

	// for amp-bind
	echo '<script async custom-element="amp-bind" src="https://cdn.ampproject.org/v0/amp-bind-0.1.js"></script>';
	
	// mostly for show-more features
	echo '<script async custom-element="amp-accordion" src="https://cdn.ampproject.org/v0/amp-accordion-0.1.js"></script>';
	
	// for lightbox search feature
	echo '<script async custom-element="amp-lightbox" src="https://cdn.ampproject.org/v0/amp-lightbox-0.1.js"></script>';

	// for youtube
	echo '<script async custom-element="amp-youtube" src="https://cdn.ampproject.org/v0/amp-youtube-0.1.js"></script>';
	
	// for fitting text
	echo '<script async custom-element="amp-fit-text" src="https://cdn.ampproject.org/v0/amp-fit-text-0.1.js"></script>';	

	// for the parallax
	echo '<script async custom-element="amp-fx-collection" src="https://cdn.ampproject.org/v0/amp-fx-collection-0.1.js"></script>';
	
	echo "<title>" . $title . "</title>";

	echo '<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">';
	
//	echo "<base href='/' />";
	echo "<meta name='viewport' content='width=device-width,minimum-scale=1,initial-scale=1'>"; // must define viewport for amp

	echo "<style amp-custom>";
	include_once('style.css');
	include_once('style_nesty.css');
	echo "</style>";

	echo "</head><body>";
	
	if (!(empty($google_analytics_code))):
		echo '<amp-analytics type="googleanalytics">';
		echo '<script type="application/json">';
		$google_analytics_array = [
			"vars" => ["account"=>$google_analytics_code],
			"triggers" => ["trackPageview" => ["on"=>"visible", "request"=>"pageview"] ] ];
		echo json_encode($google_analytics_array);
		echo '</script></amp-analytics>';
		endif;
	
	// this is the sidebar
	echo "<div id='navigation-sidebar'>";
		echo "<a href='/'><span id='navigation-sidebar-home'>".$domain."</span></a><br><br>"; // button to go home
		foreach ($header_array as $header_backend => $header_frontend):
			$selected_temp = null; if ($header_backend == $page_temp): $selected_temp = "navigation-sidebar-item-selected"; endif;
			echo "<a href='/$header_backend/'><span class='navigation-sidebar-item $selected_temp'>$header_frontend</span></a>";
			endforeach;
		echo "<br><br>";
		if (empty($login)):
			echo "<a href='/account/'><span class='navigation-sidebar-account'>Log in</span></a>"; // button to go log in
		else:
			echo "<a href='/account/'><span class='navigation-sidebar-account'>Account</span></a>";
			echo "<a href='/logout/'><span class='navigation-sidebar-account'>Log out</span></a>";
			endif;
		echo "</div>";

	echo "<div class='header'>";

	// this is the button to open the sidebar
	echo "<button on='tap:sidebar.open' class='material-icons menu-button header_button float_left'>dashboard</button>";

	if (!(empty($login))):
		echo "<a href='/new/' target='_blank'><span class='header_button float_right material-icons'>add_circle</span></a>";
		endif;

	if (!(empty($login)) && !(empty($page_temp)) && !(empty($information_array[$page_temp]))):
		echo "<a href='/".$page_temp."/edit/' target='_blank'><span class='header_button float_right material-icons'>edit</span></a>";
		endif;

	global $page_access_token; global $telegram_bot;
	if ( (!(empty($page_access_token)) || !(empty($telegram_bot))) && !(empty($page_temp)) && !(empty($information_array[$page_temp]))):
		echo "<a href='/".$page_temp."/flyer/' rel='nofollow'><span class='header_button float_right material-icons'>pages</span></a>";
		endif;

	echo "</div>";

	}


function login ($disclaimer=null) {
	echo "<div class='account_button'><a href='/'><i class='material-icons'>home</i></a></div>";
	if (!(empty($disclaimer))): echo $disclaimer; endif;
	echo "<form action='/' method='post'>";
	echo "<input type='email' name='checkpoint_email' placeholder='email'>"; 
	echo "<input type='password' name='checkpoint_password' placeholder='password'>"; 
	echo "<input type='submit' name='submit'>";
	echo "</form>";
	footer(); }


function generate_messenger_code ($entry_id) {
	global $page_access_token;
	if (empty($entry_id)): return null; endif;
	unlink("messenger/".$entry_id.".png");
	$postdata = [
		"type"		=> "standard",
		"image_size"	=> 1000,
		"data"		=> ["ref"=>"entry_id=".$entry_id]
		];
	$opts = ["http" => ["method"=>"POST", "header"=>"Content-type: application/json", "content"=>http_build_query($postdata)]];
	$context  = stream_context_create($opts);
	$result = file_get_contents("https://graph.facebook.com/v3.0/me/messenger_codes?access_token=".$page_access_token, false, $context);
	$json_decoded = json_decode($result, true);
	if (empty($json_decoded['uri'])): return null; endif;
	$photo = imagecreatefrompng($json_decoded['uri']);
	imagepng($photo, "messenger/".$entry_id.".png");
//	copy ($json_decoded['uri'], "messenger/".$entry_id.".png");
	}


function notfound() {
	echo "404ed";
	footer(); }


function footer() {
	echo "</body></html>";
	exit; } ?>
