<? // Full-featured header
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
	global $login_hidden;
	global $logout_hidden;
	
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
	
	// for the parallax
	echo '<script async custom-element="amp-fx-collection" src="https://cdn.ampproject.org/v0/amp-fx-collection-0.1.js"></script>';
	
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
	
	if ($command_temp == "edit"): return; endif;
	
	// The navigation backbone...
	echo "<div id='navigation-header'>";

	// The domain name, to go home ...
	echo "<div role='button' tabindex='0' class='navigation-header-item' on='tap:categories-popover'>&#x2742; Categories</div>";
	
	// ... then to toggle the search popover ...
	echo "<div role='button' tabindex='0' class='navigation-header-item' on='tap:search-popover'>&#x272A; Search</div>";
	
	// This is the login button ...
	echo "<div role='button' tabindex='0' class='navigation-header-item' id='login-popover-launch' on='tap:login-popover' [class]=\"loginStatus == 'loggedin' ? 'hide' : 'navigation-header-item'\" $login_hidden>&#x2731; Log in</div>";
		
	// If you are signed in ...
	echo "<div role='button' tabindex='0' class='navigation-header-item' id='settings-popover-launch' on='tap:settings-popover' [class]=\"loginStatus == 'loggedin' ? 'navigation-header-item' : 'hide'\" $logout_hidden>&#x2699; Settings</div>";
	echo "<div role='button' tabindex='0' class='navigation-header-item' id='new-popover-launch' on='tap:new-popover' [class]=\"loginStatus == 'loggedin' ? 'navigation-header-item' : 'hide'\" $logout_hidden>&#x271A; New entry</div>";	

	echo "<form id='logout' method='post' action-xhr='/logout-xhr/' target='_blank' on=\"
		submit:
			logout-submit.hide,
			logout-tryagain-submit.hide;
		submit-error:
			login-popover-launch.hide;
		submit-success:
			login-popover-launch.show,
			login-popover-submit.show,
			logout-submit.hide,
			logout-tryagain-submit.hide,
			settings-popover-launch.hide,
			new-popover-launch.hide,
			edit-entry.hide,
			login.clear,
			logout.clear,
			AMP.setState({'loginStatus': 'loggedout'})
		\">";
	echo "<div role='button' tabindex='0' class='navigation-header-item' id='logout-submit' on='tap:logout.submit' [class]=\"loginStatus == 'loggedin' ? 'navigation-header-item' : 'hide'\" $logout_hidden>&#x2716; Log out</div>";
	echo "<div class='navigation-header-item' submitting>&#x25cf; Logging out...</div>";
	echo "<div role='button' tabindex='0' class='navigation-header-item' on='tap:logout.submit' id='logout-tryagain-submit' submit-error>&#x2716; Try logging out again</div>";
//	echo "<div role='button' tabindex='0' class='navigation-header-item' on='tap:logout.submit' submit-success>&#x2713; Logged out</div>";
	echo "</form>";
	
	// ... close out the navigation backbone
	echo "</div>";
	
	$result_temp = file_get_contents("https://".$domain."/api/sitemap/?order=english");
	$information_array = json_decode($result_temp, true);

	$type_counts_array = [];
	$coordinate_counts = 0;
	foreach ($information_array as $entry_id => $entry_info):
		if (empty($type_counts_array[$entry_info['type']])): $type_counts_array[$entry_info['type']] = 0; endif;
		$type_counts_array[$entry_info['type']]++;
		if (empty($entry_info['appendix']['latitude']) || empty($entry_info['appendix']['longitude'])): continue; endif;
		$coordinate_counts++;
		endforeach;

	// If it is the homepage then display two lightboxes by default...
	$layout_temp = "nodisplay";
	if (empty($page_temp)): $layout_temp = null; endif;

	// This is the popover for the categories / sitemap ...
	echo "<amp-lightbox id='categories-popover' layout='". $layout_temp ."'>";
		echo "<div role='button' tabindex='0' on='tap:categories-popover.close' class='popover-close'>Back</div>";

		echo "<br>";
	
		$header_array_temp = array_merge(["main" => $domain], $header_array);
	
		foreach ($header_array_temp as $header_backend => $header_frontend):
			if (empty($type_counts_array[$header_backend]) || ($header_backend == "main")): continue; endif;
			$tap_temp = [ "categories-list-popover-thread-". $header_backend .".show" ];
			foreach (array_keys($header_array_temp) as $header_backend_temp):
				if ($header_backend == $header_backend_temp): continue; endif;
				$tap_temp[] = "categories-list-popover-thread-". $header_backend_temp .".hide";
				endforeach;
			echo "<div class='categories-popover-button' on='tap:". implode(",",$tap_temp) ."'>". $header_frontend ."</div>";
			endforeach;
	
		echo "</amp-lightbox>";

	echo "<amp-lightbox class='categories-list-popover-thread' id='categories-list-popover-thread-main' layout='". $layout_temp ."'>";

		// How many total entries are there ...
		echo "<b>". number_format(count($information_array)) ." total entries.</b>";

		// Display how many have GPS coordinates ...
		if (!(empty($coordinate_counts))): echo "<br>". number_format($coordinate_counts)." entries with GPS coordinates.<br>"; endif;

		// List of recently edited posts...

		echo "</amp-lightbox>";
	
	foreach ($header_array as $header_backend => $header_frontend):
		if (empty($type_counts_array[$header_backend])): continue; endif;

		echo "<amp-lightbox class='categories-list-popover-thread' id='categories-list-popover-thread-".$header_backend."' layout='nodisplay'>";

		echo "<h1>".$header_frontend."</h1><br>";
	
	 	echo number_format($type_counts_array[$header_backend])." entries";

		$count_temp = 0;
		foreach ($information_array as $entry_id => $entry_info):
			if ($entry_info['type'] !== $page_temp): continue; endif;
			$result_temp = print_row_loop ($entry_id, 0);
			$count_temp += $result_temp;
			endforeach;

		if (empty($count_temp)): echo "<p>Empty. Consider creating a new entry.</p>"; footer(); endif;

		echo "<span class='categories-item'></span>";

		echo "</amp-lightbox>";

		endforeach;
	
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
				login-popover.close,
				login-popover-launch.hide,
				logout-submit.show,
				settings-popover-launch.show,
				new-popover-launch.show,
				edit-entry.show,
				login.clear,
				logout.clear,
				AMP.setState({'loginStatus': 'loggedin'})
			\">";

		echo "<label for='checkpoint_email'>E-mail address</label>";
		echo "<input type='email' name='checkpoint_email' placeholder='E-mail address' required>";

		echo "<label for='checkpoint_email'>Password</label>";
		echo "<input type='password' name='checkpoint_password' placeholder='Password' required>";

		echo "<br><span id='login-popover-submit' role='button' tabindex='0' on='tap:login.submit'>Log in</span>";

		echo "<div class='form-feedback' submitting>Submitting...</div>";
		echo "<div class='form-feedback' submit-error><template type='amp-mustache'>Error. {{{message}}}</template></div>";
		echo "<div class='form-feedback' submit-success><template type='amp-mustache'>{{{message}}}</template></div>";
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

	// Add a new popover ... residrect if adding it works ...
	echo "<amp-lightbox id='new-popover' layout='nodisplay'>";

		echo "<form action-xhr='/new-xhr/' method='post' id='new' target='_top' class='admin-page-form' on=\"
			submit:
				new-popover-submit.hide;
			submit-error:
				new-popover-submit.show
			\">";

		echo "<p>Do you really want to add a new entry?</p>";

		// Create selector ...
		echo "<label for='type'>Type</label>";
		echo "<amp-selector layout='container' name='type'><div>";
		foreach ($header_array as $header_backend => $header_frontend):
			echo "<span option='".$header_backend."'>".$header_frontend."</span>";
			endforeach;
		echo "</div></amp-selector>";

		// Submit button ...
		echo "<br><span id='new-popover-submit' role='button' tabindex='0' on='tap:new.submit'>Create new</span>";

		echo "<div class='form-feedback' submitting>Submitting...</div>";
		echo "<div class='form-feedback' submit-error><template type='amp-mustache'>Error. {{{message}}}</template></div>";
		echo "<div class='form-feedback' submit-success><template type='amp-mustache'>{{{message}}}</template></div>";

		echo "</form>";

		echo "</amp-lightbox>";

	}


function print_row_loop ($entry_id=null, $indent_level=0) {
	
	global $login;
	global $domain;
	global $page_temp;
	global $information_array;
	global $logout_hidden;
	
	if (!(array_key_exists($entry_id, $information_array))):
		return 0; endif;
		
	$entry_info = $information_array[$entry_id];
	
	if ( ($entry_info['type'] == $page_temp) && !(empty($entry_info['parents']['hierarchy'])) && ($indent_level == 0)):
		return 0; endif;

	if ($entry_info['type'] !== $page_temp):
		if (empty($entry_info['children']['hierarchy'])): return 0; endif;
		$skip_temp = 1;
		foreach ($entry_info['children']['hierarchy'] as $child_temp):
			foreach ($information_array[$child_temp]['parents']['hierarchy'] as $parent_temp):
				if ($information_array[$parent_temp]['type'] == $page_temp):
					return 0; endif;
				endforeach;
			if ($information_array[$child_temp]['type'] == $page_temp):
				$skip_temp = 0;
				break; endif;
			endforeach;
		if ($skip_temp == 1): return 0; endif;
		endif;
	
	$count_temp = 0; $indent_temp = null;
	while ($count_temp < $indent_level):
		$indent_temp .= "<span class='categories-item-indent'></span>";
		$count_temp++;
		endwhile;
	
	$fadeout_temp = null;
	if ($entry_info['type'] !== $page_temp):
		$fadeout_temp = "categories-item-fadeout";
		endif;

	 // Launch the row and indent
	echo "<span class='categories-item $fadeout_temp'>";

	// Add the link to the article
	echo $indent_temp . "<a href='/$entry_id/'><span class='categories-item-title'>". $entry_info['header'] ."</span></a>";
	
	// Add the edit link ... we are going to remove this since it is not toggling gracefully with login/logout
//	echo "<a href='/$entry_id/edit/'>";
//	echo "<span class='categories-item-button' $logout_hidden>Edit</span></a>";
	
	// Display maps link
    	if (!(empty($entry_info['appendix']['latitude'])) && !(empty($entry_info['appendix']['longitude']))): 
 		echo "<a href='/".$entry_id."/map/' target='_blank'><span class='categories-item-button'>Map</span></a>";
    		endif;
	
	// Close the row
	echo "</span>";
	 
	if (!(empty($entry_info['children']['hierarchy']))):
		$indent_level++;
		$children_temp = array_intersect(array_keys($information_array), $entry_info['children']['hierarchy']); // sets the ordering
		foreach($children_temp as $child_id):
			print_row_loop ($child_id, $indent_level);
			endforeach;
		endif;
	
	return 1;
	
	}


function json_result($domain, $result, $redirect, $message) {
	
	header("Content-type: application/json");
	header("Access-Control-Allow-Credentials: true");
	header("Access-Control-Allow-Origin: https://".$domain);
	header("AMP-Access-Control-Allow-Source-Origin: https://".$domain);

	// No redirect if it was a failure
	if (empty($redirect) || ($result !== "success")):
		header("Access-Control-Expose-Headers: AMP-Access-Control-Allow-Source-Origin");
		endif;

	// Immediately handle any error message, with no redirect
	if ($result !== "success"):
		header("HTTP/1.0 412 Precondition Failed", true, 412);
		echo json_encode(["result"=>"error", "message"=>$message]);
		exit;
		endif;
	
	if (!(empty($redirect))):	
		header("AMP-Redirect-To: https://".$domain.$redirect);
		header("Access-Control-Expose-Headers: AMP-Redirect-To, AMP-Access-Control-Allow-Source-Origin");
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
