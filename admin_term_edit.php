<? if (!(empty($_POST['save_changes']))):
	$values_temp = [
		"term_id"=>$term_id,
		"person_id"=>$_POST['term_info']['person_id'],
		"position_id"=>$_POST['term_info']['position_id'],
		"for"=>$_POST['term_info']['for'],
		"party_id"=>$_POST['term_info']['party_id'],
		"vote"=>$_POST['term_info']['vote'],
		"start_event"=>$_POST['term_info']['start_event'],
		"end_event"=>$_POST['term_info']['end_event'] ];
	$sql_temp = sql_setup($values_temp, "nawend_center.terms_directory");
	$terms_directory_statement = $connection_pdo->prepare($sql_temp);
	$terms_directory_statement->execute($values_temp);
	execute_checkup($terms_directory_statement->errorInfo(), "entering term into terms_directory");
	include_once('admin_save.php'); endif;

echo "<style> form { text-align: center; } </style>";
echo "<style> div textarea { width: 23%; margin: 5px; padding: 5px; border: 1px solid #333; } </style>";

$terms_array = get_terms(["term_id"=>$term_id]);
$entry_info = $terms_array[$term_id];

echo "<form action='?term_id=$term_id' method='post'>";
echo "<input type='hidden' name='entry_id' value='$term_id'>";
echo "<input type='hidden' name='term_id' value='$term_id'>";
echo "<input type='hidden' name='type' value='term'>";

$information_array = get_entries(["type"=>["person", "position", "location", "party", "unit"]]);

echo "<h6>person</h6>";
echo "<select name='term_info[person_id]' size='10'>";
$nobody_selected = null; if (empty($entry_info['person_id'])): $nobody_selected = "selected"; endif;
echo "<option $nobody_selected>nobody (party results)</option>";
echo "<optgroup label='people'>";
if (!(empty($entry_info['person_id']))):
	$temp_id = $entry_info['person_id'];
	$temp_info = $information_array[$temp_id];
	echo "<option value='$temp_id' selected>".$information_array[$temp_id]['name_english'][0]." ($temp_id)</option>";
	endif;
foreach ($information_array as $temp_id => $temp_info):
	if ($temp_info['type'] !== "person"): continue; endif;
	if ($entry_info['person_id'] == $temp_id): continue; endif;
	echo "<option value='$temp_id'>".$temp_info['name_english'][0]." ($temp_id)</option>";
	endforeach;
echo "</optgroup></select>";

echo "<h6>position</h6>";
echo "<select name='term_info[position_id]' size='10' required>";
if (!(empty($entry_info['position_id']))):
	$temp_id = $entry_info['position_id'];
	$temp_info = $information_array[$temp_id];
	echo "<option value='$temp_id' selected>".$information_array[$temp_id]['name_english'][0]." ($temp_id)</option>";
	endif;
foreach ($information_array as $temp_id => $temp_info):
	if ($temp_info['type'] !== "position"): continue; endif;
	if ($entry_info['position_id'] == $temp_id): continue; endif;
	echo "<option value='$temp_id'>".$temp_info['name_english'][0]." ($temp_id)</option>";	endforeach;
echo "</select>";

echo "<h6>for</h6>";
echo "<select name='term_info[for]' size='10' required>";
if (!(empty($entry_info['for']))):
	$temp_id = $entry_info['for'];
	$temp_info = $information_array[$temp_id];
	if ($temp_info['type'] == "location"):
		echo "<option value='$temp_id' selected>".$temp_info['name_english'][0]." | ".$information_array[$temp_info['unit_id'][0]]['name_english'][0]."</option>";
	else:
		echo "<option value='$temp_id' selected>".$information_array[$temp_id]['name_english'][0]."</option>"; endif;
	endif;
foreach($information_array as $temp_id => $temp_info):
	if ($temp_id == $entry_id): continue; endif;
	if ($temp_info['type'] !== "location"): continue; endif;
	if ($entry_info['for'] == $temp_id): continue; endif;
	echo "<option value='$temp_id'>".$temp_info['name_english'][0]." | ".$information_array[$temp_info['unit_id'][0]]['name_english'][0]."</option>";
	endforeach;
foreach ($information_array as $temp_id => $temp_info):
	if ($temp_info['type'] !== "party"): continue; endif;
	if ($entry_info['for'] == $temp_id): continue; endif;
	echo "<option value='$temp_id'>".$temp_info['name_english'][0]." ($temp_id)</option>";
	endforeach;
echo "</select>";

echo "<h6>party</h6>";
echo "<select name='term_info[party_id]' size='10' required>";
if ($entry_info['party_id'] == "none"):
	echo "<option value='none' selected>none</option>";
elseif (!(empty($entry_info['party_id']))):
	$temp_id = $entry_info['party_id'];
	$temp_info = $information_array[$temp_id];
	echo "<option value='$temp_id' selected>".$information_array[$temp_id]['name_english'][0]."</option>";
	endif;
foreach ($information_array as $temp_id => $temp_info):
	if ($temp_info['type'] !== "party"): continue; endif;
	if ($entry_info['party_id'] == $temp_id): continue; endif;
	echo "<option value='$temp_id'>".$temp_info['name_english'][0]." ($temp_id)</option>"; endforeach;
echo "<option>none</option>";
echo "</select>";

echo "<h6>vote</h6>";
echo "<input type='number' name='term_info[vote]' placeholder='vote tally' value='".$entry_info['vote']."'>";

$events_array = get_events();
echo "<h6>start</h6>";
echo "<select name='term_info[start_event]' size='10'>";
$selected_temp = null; if ($entry_info['start_event'] == "unknown"): $selected_temp = "selected"; endif;
echo "<option value='unknown' $selected_temp>unknown</option>";
foreach($events_array as $temp_id => $temp_info):
	$selected_temp = null; if ($entry_info['start_event'] == $temp_id): $selected_temp = "selected"; endif;
	echo "<option value='$temp_id' $selected_temp>".$temp_info['date'].": ".$temp_info['name_english'][0]." ($temp_id)</option>"; endforeach;
echo "</select>";

echo "<h6>end</h6>";
echo "<select name='term_info[end_event]' size='10' required>";
$selected_temp = null; if ($entry_info['end_event'] == "active"): $selected_temp = "selected"; endif;
echo "<option value='active' $selected_temp>active</option>";
foreach($events_array as $temp_id => $temp_info):
	$selected_temp = null; if ($entry_info['end_event'] == $temp_id): $selected_temp = "selected"; endif;
	echo "<option value='$temp_id' $selected_temp>".$temp_info['date'].": ".$temp_info['name_english'][0]." ($temp_id)</option>"; endforeach;
echo "</select>";

echo "<textarea name='change_note' style='display: inline-block; width: 400px; height: 100px; margin: 10px;' placeholder='change notes'></textarea>";

echo "<br><input type='submit' name='save_changes' value='save' style='display: inline-block;'>";

echo "</form>"; ?>