<? $entry_info = nesty_page($page_temp);
$entry_info = $entry_info[$page_temp];

$retrieve_page->execute(["page_id"=>$page_temp]);
$result = $retrieve_page->fetchAll();
foreach ($result as $row):
	$entry_info['summary'] = json_decode($row['summary'], true);
	$entry_info['body'] = json_decode($row['body'], true);
	$entry_info['studies'] = $row['studies'];
	endforeach;

$appendix_array = [];
if ($entry_info['type'] == "location"): $appendix_array = [ "unit_id"=>"unit", "parent_id"=>"location" ]; endif;
if ($entry_info['type'] == "place"): $appendix_array = ["latitude"=>"string", "longitude"=>"string", "priority"=>"checkbox" ]; endif;
if ($entry_info['type'] == "village"): $appendix_array = ["latitude"=>"string", "longitude"=>"string"]; endif;
if ($entry_info['type'] == "person"): $appendix_array = [ "birthday"=>"date", "email"=>"string", "telephone"=>"string", "website"=>"string", "facebook"=>"string", "twitter"=>"string" ]; endif;

$new_page = null;
if ($page_temp == "new"): $new_page = "yes"; endif;

// When tap, then also close the amp-lightbox

echo "<div id='admin-page-actions' amp-fx='parallax' data-parallax-factor='1.2'>";
	echo "<a href='#title'><div class='navigation-header-item'>Title</div></a>";
	echo "<a href='#full-name'><div class='navigation-header-item'>Full name</div></a>";
	echo "<a href='#summary'><div class='navigation-header-item'>Summary</div></a>";
	echo "<a href='#body'><div class='navigation-header-item'>Body</div></a>";
	echo "<a href='#studies'><div class='navigation-header-item'>Studies</div></a>";
	echo "<a href='#hierarchy'><div class='navigation-header-item'>Hierarchy</div></a>";
	echo "<a href='#more'><div class='navigation-header-item'>&#x2756; More...</div></a>";
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

echo "<span id='title'></span>";
echo "<h2>Title</h2>";
echo "<p>The title should be shorter than the full name, and easier to comprehend as well. For example, <i>Sagrada Familia</i>. It may also contain bracketed elements for sorting, e.g. <i>[01] Sagrada Familia</i>.</p>";

foreach ($entry_info['name'] as $language_temp => $value_temp):
	$placeholder_temp = "Title / ". ucfirst($language_temp);
	echo "<label for='name[".$language_temp."]'>". $placeholder_temp ."</label>";
	echo "<input name='name[".$language_temp."]' placeholder='". $placeholder_temp ."' value='".htmlspecialchars($value_temp, ENT_QUOTES)."' maxlength='70'>";
	endforeach;
foreach($site_info['languages'] as $language_temp):
	if (isset($entry_info['name'][$language_temp])): continue; endif;
	$placeholder_temp = "Title / ". ucfirst($language_temp);
	echo "<label for='name[".$language_temp."]'>". $placeholder_temp ."</label>";
	echo "<input name='name[".$language_temp."]' placeholder='". $placeholder_temp ."' maxlength='70'>";
	endforeach;

echo "<span id='full-name'></span>";
echo "<h2>Full name</h2>";
echo "<p>The full name of a person should include middle, last, and family names. Places may also have full names, such as <i>Basílica de la Sagrada Familia</i>. It should not be depended on for sorting.</p>";

foreach ($entry_info['alternate_name'] as $language_temp => $value_temp):
	$placeholder_temp = "Full name / ".ucfirst($language_temp);
	echo "<label for='alternate_name[".$language_temp."]'>". $placeholder_temp ."</label>";
	echo "<input name='alternate_name[".$language_temp."]' placeholder='". $placeholder_temp ."' value='".htmlspecialchars($value_temp, ENT_QUOTES)."' maxlength='70'>";
	endforeach;
foreach($site_info['languages'] as $language_temp):
	if (isset($entry_info['alternate_name'][$language_temp])): continue; endif;
	$placeholder_temp = "Full name / ". ucfirst($language_temp);
	echo "<label for='alternate_name[".$language_temp."]'>". $placeholder_temp ."</label>";
	echo "<input name='alternate_name[".$language_temp."]' placeholder='". $placeholder_temp ."' maxlength='70'>";
	endforeach;

echo "<span id='summary'></span>";
echo "<h2>Summary</h2>";
echo "<p>This short summary may get used for short-form content like stories, messages, and previews. It can contain multiple short paragraphs with images.</p>";

foreach ($entry_info['summary'] as $language_temp => $value_temp):
	$placeholder_temp = "Summary / ". ucfirst($language_temp);
	echo "<label for='summary[".$language_temp."]'>". $placeholder_temp ."</label>";
	echo "<textarea name='summary[".$language_temp."]' placeholder='". $placeholder_temp ."' class='admin-page-form-summary' maxlength='1000'>".$value_temp."</textarea>";
	endforeach;
foreach($site_info['languages'] as $language_temp):
	if (isset($entry_info['summary'][$language_temp])): continue; endif;
	$placeholder_temp = "Summary / ". ucfirst($language_temp);
	echo "<label for='summary[".$language_temp."]'>". $placeholder_temp ."</label>";
	echo "<textarea name='summary[".$language_temp."]' placeholder='". $placeholder_temp ."' class='admin-page-form-summary' maxlength='1000'></textarea>";
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

	global $header_array;
	global $information_array;

	echo "<label for='". $relationship_name ."[]'>".ucwords($relationship_name)."</label>";
	echo "<amp-selector layout='container' name='". $relationship_name ."[]' multiple>";

	if (!(empty($information_array[$entry_id]['hierarchy'][$relationship_name]))): echo "<span option='clear_selection' style='font-style: italic;'>Clear selection</span>";
	else: $information_array[$entry_id]['hierarchy'][$relationship_name] = []; endif;

	$information_array[$entry_id][$relationship_name]['hierarchy'] = array_intersect($possible_array, $information_array[$entry_id][$relationship_name]['hierarchy']);
	
	foreach ($information_array[$entry_id][$relationship_name]['hierarchy'] as $entry_id_temp):
		if (!(in_array($entry_id_temp, $possible_array))): continue; endif;
		if (empty($information_array[$entry_id_temp]['header'])): continue; endif;
		if ($entry_id == $entry_id_temp): continue; endif;
		echo "<span option='".$entry_id_temp."' selected>";
		echo $information_array[$entry_id_temp]['header'] . " • ". $header_array[$information_array[$entry_id_temp]['type']];
		echo "</span>"; endforeach;
	foreach ($possible_array as $entry_id_temp):
		if (in_array($entry_id_temp, $information_array[$entry_id][$relationship_name]['hierarchy'])): continue; endif;
		if (empty($information_array[$entry_id_temp]['header'])): continue; endif;
		if ($entry_id == $entry_id_temp): continue; endif;
		echo "<span option='".$entry_id_temp."'>";
		echo $information_array[$entry_id_temp]['header'] . " • ". $header_array[$information_array[$entry_id_temp]['type']];
		echo "</span>";
		endforeach;
	echo "</amp-selector>"; }

echo "<input type='hidden' name='parents[]'>";
echo "<input type='hidden' name='children[]'>";
hierarchy_selector($page_temp, "parents", array_keys($information_array));
hierarchy_selector($page_temp, "children", array_keys($information_array));

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

echo "<p>An entry's type is its most important organizational component. Types are largely self-explanatory, except for 'articles' which are intended to be less research-oriented and more consumption-oriented.</p>";

echo "<label for='type'>Type</label>";
echo "<amp-selector layout='container' name='type'>";
if (isset($header_array[$entry_info['type']])):
	echo "<span option='".$entry_info['type']."' selected>".$header_array[$entry_info['type']]."</span>";
	endif;
foreach ($header_array as $header_backend => $header_frontend):
	if ($header_backend == $entry_info['type']): continue; endif;
	echo "<span option='".$header_backend."'>".$header_frontend."</span>";
	endforeach;
echo "</amp-selector>";

echo "<br><br><br><br><br>";

echo "<div id='admin-page-form-snackbar'>";
	echo "<div id='admin-page-form-snackbar-ready'>Ready...</div>";
	echo "<div submitting>Submitting...</div>";
	echo "<div submit-error><template type='amp-mustache'>Error. {{{message}}}</template></div>";
	echo "<div submit-success><template type='amp-mustache'>{{{message}}}</template></div>";
	echo "</div>";

echo "<div id='admin-page-form-save' role='button' tabindex='0' on='tap:pageState.refresh,save.submit'>Save</div>";

echo "</form>";

exit;

	if (!(in_array($input_temp, [ "term" ]))):
		echo "<span style='font-family: Raleway; display: block; padding-top: 20px;'>".str_replace("_", " ", $input_temp)."</span>";
		endif;

	if (in_array($input_temp, [ "term" ])):
		$terms_array = get_terms(["person_id"=>$page_temp]);
//		if (empty($terms_array)): continue; endif;
		echo "<table><thead><tr><th>term</th><th>person</th><th>position</th><th>for</th><th>party</th><th>start</th><th>end</th><th>vote</th></tr></thead><tbody>";
		foreach($terms_array as $term_id => $term_info):
			$information_array = get_entries(["entry_id"=>$term_info]);
			echo "<tr><td><a href='/$term_id/'>$term_id</a></td>";
			if (empty($term_info['person_id'])): echo "<td></td>";
			else: echo "<td><a href='?entry_id=".$term_info['person_id']."'>".$information_array[$term_info['person_id']]['name_english'][0]."</a></td>"; endif;
			echo "<td><a href='?entry_id=".$term_info['person_id']."'>".$information_array[$term_info['position_id']]['name_english'][0]."</a></td>";
			echo "<td><a href='?entry_id=".$term_info['person_id']."'>".$information_array[$term_info['for']]['name_english'][0]."</a></td>";
			echo "<td><a href='?entry_id=".$term_info['person_id']."'>".$information_array[$term_info['party_id']]['name_english'][0]."</a></td>";
			echo "<td><a href='?event_id=".$term_info['start_event']."'>".$information_array[$term_info['start_event']]['name_english'][0]."</a></td>";
			if ($term_info['end_event'] == "active"): echo "<td>active</td>";
			else: echo "<td><a href='?event_id=".$term_info['end_event']."'>".$information_array[$term_info['end_event']]['name_english'][0]."</a></td>"; endif;
			echo "<td>".$term_info['vote']."</a></td></tr>";
			endforeach;
		echo "</tbody></table>";

	elseif (in_array($input_temp, [ "start_date", "end_date" ])):
		echo "<input type='date' name='$input_temp' value='".$entry_info[$input_temp][0]."'>";

	elseif (in_array($input_temp, [ "priority" ])):
		$checked_temp = null;
		if (!(empty($entry_info[$input_temp])) && ($entry_info[$input_temp][0] == $input_temp)): $checked_temp = "checked"; endif;
		echo "<input type='hidden' name='$input_temp' value=''> <input type='checkbox' name='$input_temp' value='$input_temp' $checked_temp>";

	elseif (in_array($input_temp, [ "telephone" ])):
		echo "<input type='text' name='$input_temp' placeholder='".str_replace("_", " ", $input_temp)."' value='".$entry_info[$input_temp][0]."'>";

	elseif (in_array($input_temp, [ "website", "facebook", "twitter" ])):
		echo "<input type='website' name='$input_temp' value='".$entry_info[$input_temp][0]."'>";

	elseif (in_array($input_temp, [ "birthday" ])):
		echo "<input type='date' name='$input_temp' value='".$entry_info[$input_temp][0]."'>";

	elseif (in_array($input_temp, [ "email" ])):
		echo "<input type='email' name='$input_temp' value='".$entry_info[$input_temp][0]."'>";

	elseif (in_array($input_temp, [ "website", "facebook", "twitter" ])):
		echo "<input type='website' name='$input_temp' value='".$entry_info[$input_temp][0]."'>";

	endif; ?>
