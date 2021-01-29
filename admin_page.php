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

echo "<div id='admin-page-actions' amp-fx='parallax' data-parallax-factor='1.2'>";
//	echo "<a href='#title'><div class='navigation-header-item'>Title</div></a>";
//	echo "<a href='#full-name'><div class='navigation-header-item'>Full name</div></a>";
//	echo "<a href='#summary'><div class='navigation-header-item'>Summary</div></a>";
//	echo "<a href='#body'><div class='navigation-header-item'>Body</div></a>";
//	echo "<a href='#studies'><div class='navigation-header-item'>Studies</div></a>";
//	echo "<a href='#relationality'><div class='navigation-header-item'>Relationality</div></a>";
	echo "<div id='admin-page-delete' on='tap:delete-popover'>&#x2B19; Delete entry</div>";
	echo "</div>";

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

echo "<h1 amp-fx='parallax' data-parallax-factor='1.05'><a href='https://".$domain."/".$page_temp."/' target='_blank'>".$domain."/".$page_temp."/</a></h1>";

//echo "<span id='title'></span>";
//echo "<h2>Title</h2>";
//echo "<p>The title should be shorter than the full name, and easier to comprehend as well. For example, <i>Sagrada Familia</i>. It may also contain bracketed elements for sorting, e.g. <i>[01] Sagrada Familia</i>.</p>";

$languages_array_temp = array_values($entry_info['name']);
$languages_array_temp = array_merge($site_info['languages'], $languages_array_temp);
$languages_array_temp = array_unique($languages_array_temp);
$echo_section = null;

foreach ($languages_array_temp as $language_temp):

	$echo_temp = $hide_temp = $value_temp = null;
	$placeholder_temp = "Title / ". ucfirst($language_temp);

	if (isset($entry_info['name'][$language_temp])):
		$value_temp = $entry_info['name'][$language_temp];
	elseif (!(isset($entry_info['name'][$language_temp]))):
		$hide_temp = "hide";
		endif;

	$echo_temp .= "<div class='admin-page-input ".$hide_temp."' id='admin-page-title-".$language."'>";
		$echo_temp .= "<label for='name[".$language_temp."]'>". $placeholder_temp;
		$echo_temp .= "<span on='tap:name-".$language.".clear,admin-page-title-".$language.".hide,admin-page-title-".$language."-button.show'>Remove ".ucfirst($language_temp)."</span>";
		$echo_temp .= "</label>";
		$echo_temp .= "<input id='name-".$language."' name='name[".$language_temp."]' placeholder='". $placeholder_temp ."' value='".htmlspecialchars($value_temp, ENT_QUOTES)."' maxlength='70'>";
		$echo_temp .= "</div>";

	echo $echo_temp; // Because it's stored as a string, we can also use this format to prepend or append onto $echo_section

	endforeach;
	
// echo "<span id='full-name'></span>";
// echo "<h2>Full name</h2>";
// echo "<p>The full name of a person should include middle, last, and family names. Places may also have full names, such as <i>Basílica de la Sagrada Familia</i>. It should not be depended on for sorting.</p>";

// foreach ($entry_info['alternate_name'] as $language_temp => $value_temp):
//	$placeholder_temp = "Full name / ".ucfirst($language_temp);
//	echo "<label for='alternate_name[".$language_temp."]'>". $placeholder_temp ."</label>";
//	echo "<input name='alternate_name[".$language_temp."]' placeholder='". $placeholder_temp ."' value='".htmlspecialchars($value_temp, ENT_QUOTES)."' maxlength='70'>";
//	endforeach;
// foreach($site_info['languages'] as $language_temp):
//	if (isset($entry_info['alternate_name'][$language_temp])): continue; endif;
//	$placeholder_temp = "Full name / ". ucfirst($language_temp);
//	echo "<label for='alternate_name[".$language_temp."]'>". $placeholder_temp ."</label>";
//	echo "<input name='alternate_name[".$language_temp."]' placeholder='". $placeholder_temp ."' maxlength='70'>";
//	endforeach;

// echo "<span id='summary'></span>";
// echo "<h2>Headline</h2>";
// echo "<p>This short headline may get used for short-form content like stories, messages, and previews.</p>";

foreach ($entry_info['summary'] as $language_temp => $value_temp):
	$placeholder_temp = "Summary / ". ucfirst($language_temp);
	echo "<label for='summary[".$language_temp."]'>". $placeholder_temp ."</label>";
	echo "<input name='summary[".$language_temp."]' placeholder='". $placeholder_temp ."' value='".$value_temp."'>";
	endforeach;
foreach($site_info['languages'] as $language_temp):
	if (isset($entry_info['summary'][$language_temp])): continue; endif;
	$placeholder_temp = "Summary / ". ucfirst($language_temp);
	echo "<label for='summary[".$language_temp."]'>". $placeholder_temp ."</label>";
	echo "<input name='summary[".$language_temp."]' placeholder='". $placeholder_temp ."'>";
	endforeach;

echo "<span id='body'></span>";
echo "<h2>Body</h2>";
echo "<p>The body can be as long as wanted, and is long-form content.</p>";

foreach ($entry_info['body'] as $language_temp => $value_temp):
	$placeholder_temp = "Body / ". ucfirst($language_temp);
	echo "<label for='body[".$language_temp."]'>". $placeholder_temp ."</label>";
	echo "<textarea name='body[".$language_temp."]' placeholder='". $placeholder_temp ."' class='admin-page-form-body'>".$value_temp."</textarea>";
	endforeach;
foreach($site_info['languages'] as $language_temp):
	if (isset($entry_info['body'][$language_temp])): continue; endif;
	$placeholder_temp = "Body / ". ucfirst($language_temp);
	echo "<label for='body[".$language_temp."]'>". $placeholder_temp ."</label>";
	echo "<textarea name='body[".$language_temp."]' placeholder='". $placeholder_temp ."' class='admin-page-form-body'></textarea>";
	endforeach;

echo "<span id='studies'></span>";
echo "<h2>Studies</h2>";
echo "<p>This is the list of references and notes.</p>";

$placeholder_temp = "Studies";
echo "<label for='studies'>". $placeholder_temp ."</label>";
echo "<textarea name='studies' placeholder='". $placeholder_temp ."' class='admin-page-form-body'>".$entry_info['studies']."</textarea>";

echo "<span id='hierarchy'></span>";
echo "<h2>Hierarchy</h2>";
echo "<p>The hierarchy is the entry's position downstream and upstream of other entries.</p>";

function hierarchy_selector ($entry_id, $relationship_name, $possible_array) {

	global $site_info;
	global $information_array;

	echo "<label for='". $relationship_name ."[]'>".ucwords($relationship_name)."</label>";
	echo "<amp-selector layout='container' name='". $relationship_name ."[]' multiple>";

	if (!(empty($information_array[$entry_id]['hierarchy'][$relationship_name]))): echo "<span option='clear_selection' style='font-style: italic;'>Clear selection</span>";
	else: $information_array[$entry_id]['hierarchy'][$relationship_name] = []; endif;

	$information_array[$entry_id][$relationship_name]['hierarchy'] = array_intersect($possible_array, $information_array[$entry_id][$relationship_name]['hierarchy']);
	
	if (!(is_array($information_array[$entry_id][$relationship_name]['hierarchy']))): $information_array[$entry_id][$relationship_name]['hierarchy'] = []; endif;
	
	foreach ($information_array[$entry_id][$relationship_name]['hierarchy'] as $entry_id_temp):
		if (!(in_array($entry_id_temp, $possible_array))): continue; endif;
		if (empty($information_array[$entry_id_temp]['header'])): continue; endif;
		if ($entry_id == $entry_id_temp): continue; endif;
		echo "<span option='".$entry_id_temp."' selected>";
		echo $information_array[$entry_id_temp]['header'] . " • ". $site_info['category_array'][$information_array[$entry_id_temp]['type']];
		echo "</span>"; endforeach;
	foreach ($possible_array as $entry_id_temp):
		if (in_array($entry_id_temp, $information_array[$entry_id][$relationship_name]['hierarchy'])): continue; endif;
		if (empty($information_array[$entry_id_temp]['header'])): continue; endif;
		if ($entry_id == $entry_id_temp): continue; endif;
		echo "<span option='".$entry_id_temp."'>";
		echo $information_array[$entry_id_temp]['header'] . " • ". $site_info['category_array'][$information_array[$entry_id_temp]['type']];
		echo "</span>";
		endforeach;
	echo "</amp-selector>"; }

echo "<h2 id='relationality'>Relationality</h2>";

echo "<input type='hidden' name='parents[]'>";
echo "<input type='hidden' name='children[]'>";
hierarchy_selector($page_temp, "parents", array_keys($information_array));
hierarchy_selector($page_temp, "children", array_keys($information_array));

echo "<p>An entry's type is its most important organizational component. Types are largely self-explanatory, except for 'articles' which are intended to be less research-oriented and more consumption-oriented.</p>";

echo "<label for='type'>Type</label>";
echo "<amp-selector layout='container' name='type'>";
if (isset($site_info['category_array'][$entry_info['type']])):
	echo "<span option='".$entry_info['type']."' selected>".$site_info['category_array'][$entry_info['type']]."</span>";
	endif;
foreach ($site_info['category_array'] as $header_backend => $header_frontend):
	if ($header_backend == $entry_info['type']): continue; endif;
	echo "<span option='".$header_backend."'>".$header_frontend."</span>";
	endforeach;
echo "</amp-selector>";

echo "<p>Directly edit its published time here,</p>";

echo "<label for='date_published'>Published date (YYYY-MM-DD, e.g. 2020-01-25).</label>";
echo "<input type='date' id='date_published' name='date_published' value='".$entry_info['date_published']."'>";

if (!(empty($appendix_array))):
	echo "<h2 id='more'>More...</h2>";
	foreach ($appendix_array as $appendix_key => $appendix_type):
		$placeholder_temp = str_replace("_", " ", $appendix_key);
		echo "<label for='appendix[".$appendix_key."]'>". $placeholder_temp ."</label>";
		if ($appendix_type == "string"):
			echo "<input type='text' name='appendix[".$appendix_key."]' placeholder='". $placeholder_temp ."' value='".htmlspecialchars($entry_info['appendix'][$appendix_key], ENT_QUOTES)."'>";
		elseif ($appendix_type == "checkbox"):
			$checked_temp = null;
			if ($entry_info['appendix'][$appendix_key] == $appendix_key): $checked_temp = "checked"; endif;
			echo "<input type='checkbox' name='appendix[".$appendix_key."].' value='".$appendix_key."' $checked_temp>";
			endif;
		endforeach;
	endif;

echo "<br><br><br><br><br>";

echo "<div id='admin-page-form-snackbar'>";
	echo "<div id='admin-page-form-snackbar-ready'>Ready...</div>";
	echo "<div submitting>Submitting...</div>";
	echo "<div submit-error><template type='amp-mustache'>Error. {{{message}}}</template></div>";
	echo "<div submit-success><template type='amp-mustache'>{{{message}}}</template></div>";
	echo "</div>";

echo "<div id='admin-page-form-save' role='button' tabindex='0' on='tap:pageState.refresh,save.submit'>Save</div>";

echo "</form>"; ?>
