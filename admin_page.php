<? $entry_info = nesty_page($page_temp);
$entry_info = $entry_info[$page_temp];

$retrieve_page->execute(["page_id"=>$page_temp]);
$result = $retrieve_page->fetchAll();
foreach ($result as $row):
	$entry_info['summary']		= json_decode($row['summary'], true);
	$entry_info['body']		= json_decode($row['body'], true);
	$entry_info['studies']		= $row['studies'];
	endforeach;

$appendix_array = [];
if ($entry_info['type'] == "place"): $appendix_array = ["latitude"=>"string", "longitude"=>"string" ]; endif;
if ($entry_info['type'] == "village"): $appendix_array = ["latitude"=>"string", "longitude"=>"string"]; endif;
if ($entry_info['type'] == "person"): $appendix_array = [ "birthday"=>"date", "email"=>"string", "telephone"=>"string", "website"=>"string", "facebook"=>"string", "twitter"=>"string" ]; endif;

$new_page = null;
if ($page_temp == "new"): $new_page = "yes"; endif;

// When tap, then also close the amp-lightbox


// Do a delete popover ... redirect if deletion works ...
echo "<amp-lightbox id='delete-popover' layout='nodisplay'>";

	echo "<form action-xhr='/delete-xhr/' method='post' id='delete' target='_top' class='admin-page-form' on=\"
		submit:
			delete-popover-submit.hide;
		submit-error:
			delete-popover-submit.show
		\">";

	echo "<input type='hidden' name='entry_id' value='".$page_temp."'>";

	echo "<p>Do you really want to delete this entry?</p>";

	// Submit button ...
	echo "<br><span id='delete-popover-submit' role='button' tabindex='0' on='tap:delete.submit'>Delete</span>";

	echo "<div class='form-feedback' submitting>Submitting...</div>";
	echo "<div class='form-feedback' submit-error><template type='amp-mustache'>Error. {{{message}}}</template></div>";
	echo "<div class='form-feedback' submit-success><template type='amp-mustache'>{{{message}}}</template></div>";

	echo "</form>";

	echo "</amp-lightbox>";

echo "<form action-xhr='/edit-xhr/' method='post' class='admin-page-form' id='save' on=\"
		submit:
			admin-page-form-snackbar-ready.hide,
			admin-page-form-save.hide;
		submit-error:
			admin-page-form-save.show;
		submit-success:
			admin-page-form-save.show
		\">";

echo "<input type='hidden' name='entry_id' value='$page_temp'>";

echo "<label for='entry-link'><a href='https://".$domain."/".$page_temp."/' target='_blank'>Entry URL ►</a></label>";
echo "<input name='entry-link' type='text' value='".$domain."/".$page_temp."/' readonly>";
echo "<div class='input-button-wrapper'>";
	echo "<div class='input-button' role='button' tabindex='0' on='tap:delete-popover'>&#x2B19; Delete entry</div>";
	echo "</div>";

function create_inputs($entry_info, $input_backend, $input_descriptor, $input_type = "input-text", $language_toggle = "on", $visibility_manual = null, $possibilities_array = []) {
	
	global $site_info;
		
	$languages_array_temp = [ "placeholder" ];
	if ($language_toggle == "on"):
		if (!(isset($entry_info[$input_backend])) || empty($entry_info[$input_backend])):
			$entry_info[$input_backend] = [];
			endif;
		$languages_array_temp = array_keys($entry_info[$input_backend]);
		$languages_array_temp = array_merge($site_info['languages'], $languages_array_temp);
		$languages_array_temp = array_unique($languages_array_temp);
		endif;
	
//	$echo_section = null;

	foreach ($languages_array_temp as $language_temp):

		$echo_temp = $button_hidden_temp = $input_hidden_temp = $value_temp = null;
		
		if ($language_toggle == "on"):
			$placeholder_temp = ucfirst($input_descriptor)." / ". ucfirst($language_temp);
			$id_temp = $input_backend."-".$language_temp;
			$name_temp = $input_backend."[".$language_temp."]";
			if (isset($entry_info[$input_backend][$language_temp])): $value_temp = trim($entry_info[$input_backend][$language_temp]); endif;
		else:
			$placeholder_temp = ucfirst($input_descriptor);
			$id_temp = $input_backend;
			$name_temp = $input_backend;
			if (isset($entry_info[$input_backend])): $value_temp = trim($entry_info[$input_backend]); endif;
			endif;
	
		$multiple_temp = null;
		if ($input_type == "amp-selector-single"):
			$name_temp .= "[]";
			endif;
		if ($input_type == "amp-selector-multiple"):
			$name_temp .= "[]";
			$multiple_temp = "multiple";
			endif;
			
		if (($visibility_manual !== "off") && ( !(empty($value_temp)) || ([$input_backend,$language_temp] == ["name", "english"]))): // Set it up so name, english is open by default ... in the future make it pick first item in inputs array, first item in languages array
			$button_hidden_temp = "hidden";
			$input_hidden_temp = null;
		elseif (($visibility_manual == "off") || empty($value_temp)):
			$button_hidden_temp = null;
			$input_hidden_temp = "hidden";
			endif;

		$echo_temp .= "<div class='input-button-wrapper'><span role='button' tabindex='0' class='input-button' id='admin-page-".$id_temp."-button' on='tap:admin-page-".$id_temp.".show,admin-page-".$id_temp."-button.hide' ".$button_hidden_temp.">Show:  ".$placeholder_temp."</span></div>";

		$echo_temp .= "<div class='admin-page-input' id='admin-page-".$id_temp."' ".$input_hidden_temp.">";
			$echo_temp .= "<label for='".$name_temp."'>". $placeholder_temp ."</label>";
	
			if (in_array($input_type, ["amp-selector-single", "amp-selector-multiple"])):
				if (!(is_array($value_temp))): $value_temp = [ $value_temp ]; endif;
				$echo_temp .= "<input type='hidden' name='".$name_temp."'>";
				$echo_temp .= "<amp-selector layout='container' name='".$name_temp."' ".$multiple_temp.">";
				foreach ($value_temp as $value_temp_temp):
					$echo_temp .= "<span option='".$value_temp_temp."' selected>".$possibilities_array[$value_temp_temp]."</span>";
					endif;
				foreach ($possibilities_array as $value_temp_temp => $frontend_temp_temp):
					if (in_array($value_temp_temp, $value_temp)): continue; endif;
					$echo_temp .= "<span option='".$value_temp_temp."'>".$frontend_temp_temp."</span>";
					endforeach;
				echo "</amp-selector>";
			elseif ($input_type == "textarea-big"):
				$echo_temp .= "<textarea	name='".$name_temp."' placeholder='". $placeholder_temp ."' id='".$id_temp."'>".$value_temp."</textarea>";
			elseif ($input_type == "textarea-small"):
				$echo_temp .= "<textarea	name='".$name_temp."' placeholder='". $placeholder_temp ."' id='".$id_temp."' class='textarea-small'>".$value_temp."</textarea>";
			elseif ($input_type == "input-date"):
				$echo_temp .= "<input		name='".$name_temp."' placeholder='". $placeholder_temp ."' id='".$id_temp."' type='date' value='".htmlspecialchars($value_temp, ENT_QUOTES)."'>";
			else:
				$echo_temp .= "<input		name='".$name_temp."' placeholder='". $placeholder_temp ."' id='".$id_temp."' type='text' value='".htmlspecialchars($value_temp, ENT_QUOTES)."' maxlength='150'>";
				endif;	

			$echo_temp .= "<div class='input-button-wrapper'><span class='input-button' role='button' tabindex='0' on='tap:admin-page-".$id_temp.".hide,admin-page-".$id_temp."-button.show'>Hide: ".$placeholder_temp."</span></div>";
			
			$echo_temp .= "</div>";

//		$echo_temp .= "<div class='input-button-wrapper'><span class='input-button' role='button' tabindex='0' on='tap:admin-page-".$id_temp.".toggleVisibility'>Toggle: ".$placeholder_temp."</span></div>";
	
		echo $echo_temp; // Because it's stored as a string, we can also use this format to prepend or append onto $echo_section

		if ($language_toggle !== "on"): break; endif; // If no more languages, stop there
	
		endforeach;
	
	}
	
create_inputs($entry_info, "name", "title");
// create_inputs($entry_info['alternate_name'], "alternate_name", "full name");
create_inputs($entry_info, "summary", "headline", "textarea-small");
create_inputs($entry_info, "body", "body", "textarea");
create_inputs($entry_info, "studies", "studies", "textarea", "off");
create_inputs($entry_info, "date_published", "Published date", "input-date", "off", "off");

foreach ($appendix_array as $appendix_key => $appendix_type):
	create_inputs($entry_info, $appendix_key, str_replace("_", " ", $appendix_key), "input-text", "off");
	endforeach;

$possibilities_array = []
foreach ($information_array as $entry_id_temp => $entry_info_temp):
	$possibilities_array[$entry_id_temp] = $entry_info_temp['head'] . " • ". $site_info['category_array'][$entry_info_temp['type']];
	endforeach;
create_inputs($information_array[$entry_id]['hierarchy'], "parents", "parents", "amp-selector-multiple", "off", "off", $possibilities_array);
create_inputs($information_array[$entry_id]['hierarchy'], "children", "children", "amp-selector-multiple", "off", "off", $possibilities_array);

create_inputs($entry_info, "type", "Type", "amp-selector-single", "off", "off", $site_info['category_array']);

echo "<br><br><br><br><br>";

echo "<div id='admin-page-form-snackbar'>";
	echo "<div id='admin-page-form-snackbar-ready'>Ready...</div>";
	echo "<div submitting>Submitting...</div>";
	echo "<div submit-error><template type='amp-mustache'>Error. {{{message}}}</template></div>";
	echo "<div submit-success><template type='amp-mustache'>{{{message}}}</template></div>";
	echo "</div>";

echo "<div id='admin-page-form-save' role='button' tabindex='0' on='tap:pageState.refresh,save.submit'>Save</div>";

echo "</form>"; ?>
