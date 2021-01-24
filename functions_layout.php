<? // Full-featured header
function amp_header($title=null, $canonical=null) {

	// Via config.php
	global $domain;
	global $publisher;
	global $google_analytics_code;
	global $color;

	// URL information
	global $page_temp;
	global $command_temp;

	// Database contents
	global $header_array;
	global $information_array;
	
	// Login status
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

	// for the navigation sidebar
	echo '<script async custom-element="amp-sidebar" src="https://cdn.ampproject.org/v0/amp-sidebar-0.1.js"></script>';
	
	// for amp-form
	echo '<script async custom-element="amp-form" src="https://cdn.ampproject.org/v0/amp-form-0.1.js"></script>';

	// for amp-list
	echo '<script async custom-element="amp-list" src="https://cdn.ampproject.org/v0/amp-list-0.1.js"></script>';

	// for amp-bind
	echo '<script async custom-element="amp-bind" src="https://cdn.ampproject.org/v0/amp-bind-0.1.js"></script>';
	
	// for lightboxes
	echo '<script async custom-element="amp-lightbox" src="https://cdn.ampproject.org/v0/amp-lightbox-0.1.js"></script>';

	// for error results
	echo '<script async custom-template="amp-mustache" src="https://cdn.ampproject.org/v0/amp-mustache-0.2.js"></script>';
	
	// for the amp-selector
	echo '<script async custom-element="amp-selector" src="https://cdn.ampproject.org/v0/amp-selector-0.1.js"></script>';
	
	// for the parallax
	echo '<script async custom-element="amp-fx-collection" src="https://cdn.ampproject.org/v0/amp-fx-collection-0.1.js"></script>';
	
	// for the mathml formulas
	echo '<script async custom-element="amp-mathml" src="https://cdn.ampproject.org/v0/amp-mathml-0.1.js"></script>';
	
	// loading fonts
	echo '<link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">';
	echo '<link href="https://fonts.googleapis.com/css2?family=Assistant&display=swap" rel="stylesheet">';
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
	
	$login_hidden = $logout_hidden = "navigation-header-item"; // This would mean that buttons to login AND logout are shown
	(empty($login) ? $logout_hidden = "hide" : $login_hidden = "hide");

	echo "<amp-state id='pageState' src='/api/page-state/'></script></amp-state>";
	
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
	echo "<div id='navigation-header' amp-fx='parallax' data-parallax-factor='1.4'>";

	// The domain name, to go home ...
	echo "<div role='button' tabindex='0' class='navigation-header-item' on='tap:sidebar-navigation.open,sidebar-navigation.changeToLayoutContainer()'>Navigation</div>";
	
	// This is the login button ...
	echo "<div role='button' tabindex='0' id='login-popover-launch' on='tap:login-popover.open' [class]=\"pageState.login.loginStatus == 'loggedin' ? 'hide' : 'navigation-header-item'\" class='".$login_hidden."' >Log in</div>";
		
	// If you are signed in, the button for 'settings'...
	echo "<div role='button' tabindex='0' id='settings-popover-launch' on='tap:settings-popover' [class]=\"pageState.login.loginStatus == 'loggedin' ? 'navigation-header-item' : 'hide'\" class='".$logout_hidden."'>Settings</div>";

	// If you are viewing a page but not editing it...
	if (!(empty($page_temp)) && empty($command_temp)):
	
		// If you are signed in, the button for 'edit' will appear...
		echo "<a href='/".$page_temp."/edit/' target='_blank'><div id='settings-popover-launch' [class]=\"pageState.login.loginStatus == 'loggedin' ? 'navigation-header-item' : 'hide'\" class='".$logout_hidden."'>Edit</div></a>";
	
		endif;
	
	// If you are editing a page and want to preview it...
	if (!(empty($page_temp)) && ($command_temp == "edit")):
	
		// If you are signed in, the button for 'edit' will appear...
		echo "<a href='/".$page_temp."/' target='_blank'><div id='settings-popover-launch' [class]=\"pageState.login.loginStatus == 'loggedin' ? 'navigation-header-item' : 'hide'\" class='".$logout_hidden."'>View</div></a>";
	
		endif;

	// If you are signed in then button to add new...
	echo "<div role='button' tabindex='0' id='new-popover-launch' on='tap:new-popover' [class]=\"pageState.login.loginStatus == 'loggedin' ? 'navigation-header-item' : 'hide'\" class='".$logout_hidden."'>&#x271A; New</div>";	
	
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
			AMP.setState({pageState:{login: {loginStatus: 'loggedout'}}})
		\">";
	echo "<div role='button' tabindex='0' id='logout-submit' on='tap:logout.submit' [class]=\"pageState.login.loginStatus == 'loggedin' ? 'navigation-header-item' : 'hide'\" class='".$logout_hidden."'>&#x2716; Log out</div>";
//	echo "<div class='navigation-header-item' submitting>&#x25cf; Logging out...</div>";
	echo "<div role='button' tabindex='0' class='navigation-header-item' on='tap:logout.submit' id='logout-tryagain-submit' submit-error>&#x2716; Try logging out again</div>";
//	echo "<div role='button' tabindex='0' class='navigation-header-item' on='tap:logout.submit' submit-success>&#x2713; Logged out</div>";
	echo "</form>";
	
	// ... close out the navigation backbone
	echo "</div>";

	$navigation_lightboxes = implode(",", [
		"begin-image.hide",
		"login-popover.close",
		"settings-popover.close",
		"new-popover.close",
		"sidebar-navigation.close",
		]);		
	
	// This is the popover for the categories ...;
	echo "<amp-sidebar id='sidebar-navigation' layout='nodisplay' side='left' on='sidebarClose:pageState.refresh,navigation-header.show;sidebarOpen:begin-image.hide,login-popover.close,settings-popover.close,new-popover.close'>";
	
		echo "<amp-nested-menu layout='fill'><ul>";
		
		echo "<li class='sidebar-navigation-close'><div class='sidebar-back' on='tap:".$navigation_lightboxes."' role='button' tabindex='0' amp-nested-submenu-close>Close</div></li>";
	
		echo "<li class='sidebar-navigation-item'><div class='sidebar-navigation-item-title'  amp-nested-submenu-open>Search</div>";
		echo "<div amp-nested-submenu><ul>";
	
			echo "<li class='sidebar-navigation-close'><div class='sidebar-back' amp-nested-submenu-close>Back</li>";

			echo "<li id='sidebar-navigation-search'>";
			echo "<label for='search-input'>Search the database</label>";
//			echo "<input type='text' id='search-input' placeholder='...' on=\"input-throttled:AMP.setState({pageState:{searchTerm: event.value.replace('  ',' ').replace('  ',' ').replace('?',' ').replace(',',' ').replace('&',' ')}})\">";
			echo "<input type='text' id='search-input' placeholder='...' on=\"input-throttled:AMP.setState({pageState:{searchTerm: event.value}})\">";
			echo "<div id='search-submit' role='button' tabindex='0' on='tap:sidebar-navigation-lightbox-search-list.refresh,sidebar-navigation-lightbox-search-list.changeToLayoutContainer()'>Search</div>";
			echo "</li>";
	
			echo "<amp-list id='sidebar-navigation-lightbox-search-list' credentials='include' layout='responsive' width='800' height='800' items='.' max-items='100' binding='refresh' reset-on-refresh='always' items='searchResults' [src]=\"'/api/search/?search=' + pageState.searchTerm\">";

				echo "<li class='sidebar-navigation-item' fallback><span class='sidebar-navigation-item-title'>No search results.</span></li>";
				echo "<li class='sidebar-navigation-item' placeholder><span class='sidebar-navigation-item-title'>Loading search results...</span></li>";

				echo "<template type='amp-mustache'>";
					echo "<li class='sidebar-navigation-item'><a href='/{{entry_id}}/' target='_blank'><span class='sidebar-navigation-item-title' [text]=\"{{entry_id}}\">Click for more</span></a></li>";
					echo "</template>";
	
				echo "</amp-list></ul></div>";
		
			echo "</li>";

		echo "<li class='sidebar-navigation-item'><div class='sidebar-navigation-item-title' amp-nested-submenu-open>About</div>";
		echo "<div amp-nested-submenu><ul>";
			echo "<li class='sidebar-navigation-close'><div class='sidebar-back' amp-nested-submenu-close>Back</div></li>";
			echo "<li class='sidebar-navigation-item'>".number_format(count($information_array)) ." total entries.</li>";
			echo "</ul></div></li>";

		foreach ($header_array as $header_backend => $header_frontend):
			echo "<li class='sidebar-navigation-item'><div class='sidebar-navigation-item-title' role='button' tabindex='0' on='tap:sidebar-navigation.changeToLayoutContainer()' amp-nested-submenu-open>". ucfirst($header_frontend) ."</div>";	
			echo "<div amp-nested-submenu>";
				echo "<div class='sidebar-back' role='button' tabindex='0' on='tap:sidebar-navigation.changeToLayoutContainer()' amp-nested-submenu-close>Back</div>";
				echo "<div class='sidebar-navigation-item'><a href='/".$header_backend."/'><span class='sidebar-navigation-item-title'>Type: ".$header_frontend."</span></a></div>";
				$counter_temp = 0;
				foreach($information_array as $entry_id => $entry_info):
					if ($entry_info['type'] !== $header_backend): continue; endif;
					echo "<div class='sidebar-navigation-item'><a href='/". $entry_id . "/'>";
					echo "<span class='sidebar-navigation-item-title'>".$entry_info['header']."</span></a></div>";
					$counter_temp++;
					endforeach;
				if ($counter_temp == 0): echo "<li class='sidebar-navigation-item'><span class='sidebar-navigation-item-title'>No results.</span></li>"; endif;
				echo "</div></li>";
			endforeach;
	
			echo "</ul></amp-nested-menu>";
	
		echo "</amp-sidebar>";
	
	// This is the popover to log in ...
	echo "<amp-lightbox id='login-popover' on=\"lightboxClose:navigation-header.show;lightboxOpen:begin-image.hide,sidebar-navigation.close,settings-popover.close,new-popover.close,navigation-header.hide,AMP.setState({inputPasswordType: 'password'})\" layout='nodisplay'>";

		echo "<span role='button' tabindex='0' on='tap:".$navigation_lightboxes."' class='lightboxes-close'>Close</span>";

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
				AMP.setState({pageState:{login: {loginStatus: 'loggedin'}}})
			\">";

		echo "<label for='checkpoint_email'>E-mail address</label>";
		echo "<input type='email' id='checkpoint_email' name='checkpoint_email' placeholder='E-mail address' required>";

		echo "<label for='checkpoint_password'>Password</label>";
		echo "<input type='password' [type]=\"inputPasswordType\" id='checkpoint_password' name='checkpoint_password' placeholder='Password' required>";
		echo "<span class='password-toggle' role='button' tabindex='0' id='inputPasswordTypeText' on=\"tap:AMP.setState({inputPasswordType: 'text'}),inputPasswordTypeText.hide,inputPasswordTypePassword.show\">Show password</span>";
		echo "<span class='password-toggle' role='button' tabindex='0' id='inputPasswordTypePassword' on=\"tap:AMP.setState({inputPasswordType: 'password'}),inputPasswordTypeText.show,inputPasswordTypePassword.hide\" hidden>Hide password</span>";
	
		echo "<br><span id='login-popover-submit' role='button' tabindex='0' on='tap:login.submit'>Log in</span>";

		echo "<div class='form-feedback' submitting>Submitting...</div>";
		echo "<div class='form-feedback' submit-error><template type='amp-mustache'>Error. {{{message}}}</template></div>";
		echo "<div class='form-feedback' submit-success><template type='amp-mustache'>{{{message}}}</template></div>";
		echo "</form>";
		echo "</amp-lightbox>";
	
	// This is the popover for settings ...
	echo "<amp-lightbox id='settings-popover' on='lightboxClose:navigation-header.show;lightboxOpen:begin-image.hide,login-popover.close,navigation-sidebar.close,new-popover.close' layout='nodisplay'>";

		echo "<span role='button' tabindex='0' on='tap:".$navigation_lightboxes."' class='lightboxes-close'>Close</span>";

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
	echo "<amp-lightbox id='new-popover' on='lightboxClose:navigation-header.show;lightboxOpen:begin-image.hide,login-popover.close,navigation-sidebar.close,settings-popover.close' layout='nodisplay'>";

		echo "<span role='button' tabindex='0' on='tap:".$navigation_lightboxes."' class='lightboxes-close'>Close</span>";

		echo "<form action-xhr='/new-xhr/' method='post' id='new' target='_top' class='admin-page-form' on=\"
			submit:
				new-popover-submit.hide;
			submit-error:
				new-popover-submit.show
			\">";

		echo "<p>Do you really want to create a new entry?</p>";

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
	echo '<amp-mathml id="footer-formula" layout="container" data-formula="\[ 1 = 5 = 613 = \infty \]"></amp-mathml>';
	echo "</body></html>";
	exit; } ?>
