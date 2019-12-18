<? function html_header($title=null) {
	//
	}


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
		
	// for lightbox search feature
	echo '<script async custom-element="amp-lightbox" src="https://cdn.ampproject.org/v0/amp-lightbox-0.1.js"></script>';

	// for fitting text
	echo '<script async custom-element="amp-fit-text" src="https://cdn.ampproject.org/v0/amp-fit-text-0.1.js"></script>';	

	// for error results
	echo '<script async custom-template="amp-mustache" src="https://cdn.ampproject.org/v0/amp-mustache-0.2.js"></script>';
	
	// for the amp-selector
	echo '<script async custom-element="amp-selector" src="https://cdn.ampproject.org/v0/amp-selector-0.1.js"></script>';
	
	// loading fonts
	echo '<link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">';
	echo '<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">';
	
	echo "<title>" . $title . "</title>";

	// Theme color for browser bar
	echo "<meta name='theme-color' content='#ffffff'>";
	
//	echo "<base href='/' />";
	echo "<meta name='viewport' content='width=device-width,minimum-scale=1,initial-scale=1'>"; // must define viewport for amp

	echo "<style amp-custom>";
	include_once('style.css');
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
	
	// The navigation backbone...
	echo "<div id='navigation-header'>";

	// The domain name, to go home ...
	echo "<div role='button' tabindex='0' class='navigation-header-item' on='tap:categories-popover'>&#x2742; Categories</div>";
	
	// ... then to toggle the search popover ...
	echo "<div role='button' tabindex='0' class='navigation-header-item' on='tap:search-popover'>&#x272A; Search</div>";

	// To display the login or logout buttons
	$login_hidden = $logout_hidden = null;
	if (empty($login)): $logout_hidden = "hidden"; // if we are not logged in
	elseif (!(empty($login))): $login_hidden = "hidden"; endif; // if we are logged in
	
	// This is the login button ...
	echo "<div role='button' tabindex='0' class='navigation-header-item' id='login-popover-launch' on='tap:login-popover' [class]=\"loginStatus == 'loggedin' ? 'hide' : 'navigation-header-item'\" $login_hidden>&#x2731; Log in</div>";
		
	// If you are signed in ...
	echo "<div role='button' tabindex='0' class='navigation-header-item' id='settings-popover-launch' on='tap:settings-popover' [class]=\"loginStatus == 'loggedin' ? 'navigation-header-item' : 'hide'\" $logout_hidden>&#x2699; Settings</div>";
	echo "<div role='button' tabindex='0' class='navigation-header-item' id='add-popover-launch' on='tap:add-popover' [class]=\"loginStatus == 'loggedin' ? 'navigation-header-item' : 'hide'\" $logout_hidden>&#x271A; Add entry</div>";	

	echo "<form id='logout' method='post' action-xhr='/logout-xhr/' target='_blank' on=\"
		submit:
			logout-popover-submit.hide,
			logout-popover-tryagain-submit.hide;
		submit-error:
			login-popover-launch.hide;
		submit-success:
			login-popover-launch.show,
			logout-popover-submit.hide,
			logout-popover-tryagain-submit.hide,
			settings-popover-launch.hide,
			add-popover-launch.hide,
			login.clear,
			logout.clear,
			AMP.setState({'loginStatus': 'loggedout'})
		\">";
	echo "<div role='button' tabindex='0' class='navigation-header-item' id='logout-popover-submit' on='tap:logout.submit' [class]=\"loginStatus == 'loggedin' ? 'navigation-header-item' : 'hide'\" $logout_hidden>&#x2716; Log out</div>";
	echo "<div class='navigation-header-item' submitting>&#x25cf; Logging out...</div>";
	echo "<div role='button' tabindex='0' class='navigation-header-item' on='tap:logout.submit' id='logout-popover-tryagain-submit' submit-error>&#x2716; Try logging out again</div>";
//	echo "<div role='button' tabindex='0' class='navigation-header-item' on='tap:logout.submit' submit-success>&#x2713; Logged out</div>";
	echo "</form>";
	
	// ... close out the navigation backbone
	echo "</div>";
	
	// This is the popover for the categories / sitemap ...
	echo "<amp-lightbox id='categories-popover' layout='nodisplay'>";
		echo "<div role='button' tabindex='0' on='tap:categories-popover.close' class='popover-close'>Back</div>";
		echo "<a href='/'><div class='navigation-categories-item'>". ucfirst($domain) ."</div></a><br>";
		foreach ($header_array as $header_backend => $header_frontend):
			echo "<a href='/". $header_backend ."'><div class='navigation-categories-item'>". $header_frontend ."</div></a>";
			endforeach;
		echo "</amp-lightbox>";
	
	// This is the popover to search ...
	echo "<amp-lightbox id='search-popover' layout='nodisplay'>";
		echo "<span role='button' tabindex='0' on='tap:search-popover.close' class='popover-close'>Back</span>";
		echo "Search input coming soon";
		echo "</amp-lightbox>";
	
	// This is the popover to log in ...
	echo "<amp-lightbox id='login-popover' layout='nodisplay'>";

		echo "<span role='button' tabindex='0' on='tap:login-popover.close' class='popover-close'>Back</span>";

		echo "<form id='login' method='post' action-xhr='/login-xhr/' target='_blank' on=\"
			submit:
				login-popover-submit.hide;
			submit-error:
				login-popover-submit.show;
			submit-success:
				login-popover.hide,
				login-popover-launch.hide,
				login-popover-submit.show,
				logout-popover-submit.show,
				settings-popover-launch.show,
				add-popover-launch.show,
				login.clear,
				logout.clear,
				AMP.setState({'loginStatus': 'loggedin'})
			\">";

		echo "<label for='checkpoint_email'>E-mail address</label>";
		echo "<input type='email' name='checkpoint_email' placeholder='E-mail address' required>";

		echo "<label for='checkpoint_email'>Password</label>";
		echo "<input type='password' name='checkpoint_password' placeholder='Password' required>";

		echo "<br><span id='login-popover-submit' role='button' tabindex='0' on='tap:login.submit'>Log in</span><br>";

		echo "<div submitting>Submitting...</div>";
		echo "<div submit-error><template type='amp-mustache'>Error. {{{message}}}</template></div>";
		echo "<div submit-success><template type='amp-mustache'>{{{message}}}</template></div>";
		echo "</form>";
		echo "</amp-lightbox>";
	
	// This is the popover for settings ...
	echo "<amp-lightbox id='settings-popover' layout='nodisplay'>";

		echo "<span role='button' tabindex='0' on='tap:settings-popover.close' class='popover-close'>Back</span>";

		echo "<p>Settings coming soon: password change, account management.</p>";
	
		echo "<label>Enter new e-mail address</label>";
		echo "<input name='checkpoint_newemail'>";
		echo "update email";

		echo "<label>Enter new password</label>";
		echo "<input name='checkpoint_newpassword'>";
		echo "<input name='checkpoint_newpassword'>";
		echo "update password";	
	
		echo "</amp-lightbox>";

	echo "<amp-lightbox id='add-popover' layout='nodisplay'>";

		echo "<span role='button' tabindex='0' on='tap:add-popover.close' class='popover-close'>Back</span>";

		echo "<p>Add new entry: coming soon.</p>";
	
		echo "</amp-lightbox>";

	}


function json_result($domain, $result, $redirect, $message) {
	
	header("Content-type: application/json");
	header("Access-Control-Allow-Credentials: true");
	header("Access-Control-Allow-Origin: https://".$domain);
	header("AMP-Access-Control-Allow-Source-Origin: https://".$domain);

	if (empty($redirect)):
		header("Access-Control-Expose-Headers: AMP-Access-Control-Allow-Source-Origin");
		endif;

	if (!(empty($redirect))):	
		header("AMP-Redirect-To: https://".$domain."/".$redirect);
		header("Access-Control-Expose-Headers: AMP-Redirect-To, AMP-Access-Control-Allow-Source-Origin");
		endif;

	// Immediately handle any error message, with no redirect
	if ($result !== "success"):
//		header("HTTP/1.0 412 Precondition Failed", true, 412);
		echo json_encode(["result"=>"error", "message"=>$message]);
		endif;

	echo json_encode(["result"=>"success", "message"=>$message]);

	exit; }


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
	$context = stream_context_create($opts);
	$result = file_get_contents("https://graph.facebook.com/v3.0/me/messenger_codes?access_token=".$page_access_token, false, $context);
	$json_decoded = json_decode($result, true);
	if (empty($json_decoded['uri'])): return null; endif;
	$photo = imagecreatefrompng($json_decoded['uri']);
	imagepng($photo, "messenger/".$entry_id.".png");
//	copy ($json_decoded['uri'], "messenger/".$entry_id.".png");
	}


function notfound() {
	echo "<p>404ed</p>";
	footer(); }


function footer() {
	echo "</body></html>";
	exit; } ?>
