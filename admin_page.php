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

// The navigation backbone...
echo "<div id='navigation-header'>";

	echo "<a href='#title'><div class='navigation-header-item'>Title</div></a>";
	echo "<a href='#full_name'><div class='navigation-header-item'>Full name</div></a>";
	echo "<a href='#summary'><div class='navigation-header-item'>Summary</div></a>";
	echo "<a href='#body'><div class='navigation-header-item'>Body</div></a>";
	echo "<a href='#studies'><div class='navigation-header-item'>Studies</div></a>";
	echo "<a href='#relationships'><div class='navigation-header-item'>Relationships</div></a>";
	if (!(empty($appendix_array))): echo "<a href='#appendix'><div class='navigation-header-item'>Appendix</div></a>"; endif;
	echo "<a href='#type'><div class='navigation-header-item'>Type</div></a>";

	echo "</div>";

// Add a little footer thing to track what's happening...
echo "<div>";

// Ticket

// Save button

echo "</div>";

// Put it on the left ...
echo "<div id='admin-page-add-entry'>&#x271A; Add entry</div>";

// Put it on the right ...
echo "<div id='admin-page-delete'>&#x2B19; Delete entry</div>";

// Put it on the right ...
echo "<div id='admin-page-log-out'>&#x2716; Log out</div>";

// Do a delete popover ... redirect if deletion works ...

echo "<amp-lightbox layout='nodisplay'>";

	echo "<p>Do you really want to delete this page?<br>";
	echo "<a href='https://".$domain."/".$_POST['entry_id']."/'>https://".$domain."/".$_POST['entry_id']."/</a></p>";

	echo "<form action='' method='post'>";

	echo "<input type='hidden' name='entry_id' value='".$page_temp."'>";

	echo "<div class='hover_overlay hover_bottomleft'>";
	echo "<button type='submit' name='delete_entry' value='".$page_temp."'>Delete</button></div>";

	echo "</form>";

	echo "</amp-lightbox>";

echo "<form action='' method='post'>";

$result_temp = file_get_contents("https://".$domain."/api/sitemap/");
$additional_array = json_decode($result_temp, true);

echo "<input type='hidden' name='entry_id' value='$page_temp'>";

echo "<br><br><span id='title'></span>";
echo "<h2>Title</h2>";

echo "<p>The title should be shorter and easier to comprehend compared to the full name.</p>";

foreach ($entry_info['name'] as $language_temp => $value_temp):
	echo "<label for='name[".$language_temp."]'>Title / ".ucfirst($language_temp)."</label>";
	echo "<input name='name[".$language_temp."]' value='".htmlspecialchars($value_temp, ENT_QUOTES)."' maxlength='70'>";
	endforeach;
foreach($site_info['languages'] as $language_temp):
	if (isset($entry_info['name'][$language_temp])): continue; endif;
	echo "<label for='name[".$language_temp."]'>Title / ".ucfirst($language_temp)."</label>";
	echo "<input name='name[".$language_temp."]' maxlength='70'>";
	endforeach;

echo "<span id='full_name'></span>";
echo "<h2>Full name</h2>";

foreach ($entry_info['alternate_name'] as $language_temp => $value_temp):
	echo "<label for='alternate_name[".$language_temp."]'>alternate name / ".ucfirst($language_temp)."</label>";
	echo "<input name='alternate_name[".$language_temp."]' value='".htmlspecialchars($value_temp, ENT_QUOTES)."' maxlength='70'>";
	endforeach;
foreach($site_info['languages'] as $language_temp):
	if (isset($entry_info['alternate_name'][$language_temp])): continue; endif;
	echo "<label for='alternate_name[".$language_temp."]'>alternate name / ".ucfirst($language_temp)."</label>";
	echo "<input name='alternate_name[".$language_temp."]' maxlength='70'>";
	endforeach;

echo "<span id='summary'></span>";
echo "<h2>Summary</h2>";
echo "<p>This short summary may get used for short-form content like stories, messages, and previews. It can contain multiple short paragraphs with images.</p>";

foreach ($entry_info['summary'] as $language_temp => $value_temp):
	echo "<label for='summary[".$language_temp."]'>summary / ".ucfirst($language_temp)."</label>";
	echo "<textarea style='height: 250px;' name='summary[".$language_temp."]' maxlength='1000'>".$value_temp."</textarea>";
	endforeach;
foreach($site_info['languages'] as $language_temp):
	if (isset($entry_info['summary'][$language_temp])): continue; endif;
	echo "<label for='summary[".$language_temp."]'>summary / ".ucfirst($language_temp)."</label>";
	echo "<textarea style='height: 250px;' name='summary[".$language_temp."]' maxlength='1000'></textarea>";
	endforeach;

echo "<span id='body'></span>";
echo "<h2>Body</h2>";
echo "<p>The body can be as long as wanted, and is long-form content.</p>";

foreach ($entry_info['body'] as $language_temp => $value_temp):
	echo "<label for='body[".$language_temp."]'>body / ".ucfirst($language_temp)."</label>";
	echo "<textarea style='height: 500px; max-height: none;' name='body[".$language_temp."]'>".$value_temp."</textarea>";
	endforeach;
foreach($site_info['languages'] as $language_temp):
	if (isset($entry_info['body'][$language_temp])): continue; endif;
	echo "<label for='body[".$language_temp."]'>body / ".ucfirst($language_temp)."</label>";
	echo "<textarea style='height: 500px; max-height: none;' name='body[".$language_temp."]'></textarea>";
	endforeach;

echo "<span id='studies'></span>";
echo "<h2>Studies</h2>";
echo "<p>This is the list of references and notes.</p>";

echo "<label for='studies'>Studies</label>";
echo "<textarea style='height: 400px;' name='studies'>".$entry_info['studies']."</textarea>";


echo "<span id='relationships'></span>";
echo "<h2>Relationships</h2>";
			
echo "<input type='hidden' name='parents[]'>";
echo "<input type='hidden' name='children[]'>";

function relationships_edit ($relationship_orientation, $relationship_name, $possible_array=[], $multiple=null) {
	global $page_temp;
	global $entry_info;
	echo "<label for='".$relationship_orientation."[".$relationship_name."][]'>".ucwords($relationship_orientation).": ".str_replace("_", " ", $relationship_name)."</label>";
	echo "<select name='".$relationship_orientation."[".$relationship_name."][]' size='8' $multiple>";
	if (empty($entry_info[$relationship_orientation][$relationship_name])): $entry_info[$relationship_orientation][$relationship_name] = []; endif;
	if (!(empty($entry_info[$relationship_orientation][$relationship_name]))): echo "<option value='clear_selection' style='font-style: italic;'>clear selection</option>"; endif;

	foreach ($entry_info[$relationship_orientation][$relationship_name] as $entry_id_temp):
		if (empty($possible_array[$entry_id_temp])): continue; endif;
		if (empty($possible_array[$entry_id_temp]['name'])): continue; endif;
		if ($page_temp == $entry_id_temp): continue; endif;
		echo "<option value='".$entry_id_temp."' selected>";
		echo $possible_array[$entry_id_temp]['header'];
		echo "&nbsp; <i>".$possible_array[$entry_id_temp]['type']."</i>";
		echo "</option>"; endforeach;
	foreach ($possible_array as $entry_id_temp => $entry_info_temp):
		if ($page_temp == $entry_id_temp): continue; endif;
		if (empty($possible_array[$entry_id_temp]['name'])): continue; endif;
		if (in_array($entry_id_temp, $entry_info[$relationship_orientation][$relationship_name])): continue; endif;
		echo "<option value='".$entry_id_temp."'>";
		echo implode(" &nbsp;&nbsp; ", $entry_info_temp['name']);
		echo "&nbsp;&nbsp;&nbsp; (".$possible_array[$entry_id_temp]['type'].")";
		echo "</option>";
		endforeach;
	echo "</select>"; }

relationships_edit("parents", "hierarchy", $additional_array, "multiple");
foreach ($entry_info['parents'] as $relationship_name => $discard):
	if ($relationship_name == "hierarchy"): continue; endif;
	relationships_edit("parents", $relationship_name, $additional_array, "multiple");
	endforeach;
foreach ($entry_info['children'] as $relationship_name => $discard):
	relationships_edit("children", $relationship_name, $additional_array, "multiple");
	endforeach;

if (!(empty($appendix_array))):

	echo "<h2 id='appendix'>Appendix</h2>";

	foreach ($appendix_array as $appendix_key => $appendix_type):
		echo "<label for='appendix[".$appendix_key."]'>".str_replace("_", " ", $appendix_key)."</label>";
		if ($appendix_type == "string"):
			echo "<input type='text' name='appendix[".$appendix_key."]' value='".htmlspecialchars($entry_info['appendix'][$appendix_key], ENT_QUOTES)."'>";
		elseif ($appendix_type == "checkbox"):
			$checked_temp = null;
			if ($entry_info['appendix'][$appendix_key] == $appendix_key): $checked_temp = "checked"; endif;
			echo "<input type='checkbox' name='appendix[".$appendix_key."].' value='".$appendix_key."' $checked_temp>";
			endif;
		endforeach;
	endif;

echo "<h2 id='type'>Type</h2>";

echo "<label for='type'>Type</label>";
echo "<select name='type' size='12' required>";
echo "<option value='".$entry_info['type']."' selected>".$entry_info['type']."</option>";
foreach (array_keys($header_array) as $value_temp):
	if ($value_temp == $entry_info['type']): continue; endif;
	echo "<option value='".$value_temp."'>".$value_temp."</option>";
	endforeach;
echo "</select>";

echo "</form>";

exit;

	if (!(in_array($input_temp, [ "term" ]))):
		echo "<span style='font-family: Raleway; display: block; padding-top: 20px;'>".str_replace("_", " ", $input_temp)."</span>";
		endif;

	if (in_array($input_temp, [ "term" ])):
		$terms_array = get_terms(["person_id"=>$page_temp]);
		if (empty($terms_array)): continue; endif;
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
