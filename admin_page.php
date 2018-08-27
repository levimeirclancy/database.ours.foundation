<? $entry_info = nesty_page($page_temp);
$entry_info = $entry_info[$page_temp];

echo "<br><br>";

if (isset($_POST['delete_entry']) && ($_POST['delete_entry'] == "delete") && ($_POST['entry_id'] == $page_temp)):

	echo "<p>Do you really want to delete this page?<br>";
	echo "<a href='https://".$domain."/".$_POST['entry_id']."/'>https://".$domain."/".$_POST['entry_id']."/</a></p>";

	echo "<form action='' method='post'>";

	echo "<input type='hidden' name='entry_id' value='".$page_temp."'>";

	echo "<div class='hover_overlay hover_bottomleft'>";
	echo "<button type='submit' name='delete_entry' value='".$page_temp."' class='material-icons'>delete</button></div>";

	echo "<div class='hover_overlay hover_bottomright'>";
	echo "<a href='/".$_POST['entry_id']."/edit/'><span class='button material-icons'>undo</span></a></div>";

	echo "</form>";

	footer();

	endif;


if (isset($_POST['delete_entry']) && ($_POST['delete_entry'] == $page_temp) && ($_POST['entry_id'] == $page_temp)):

	$sql_temp = "DELETE FROM ".$database.".information_paths WHERE (parent_id=:parent_id) OR (child_id=:child_id)";
	$paths_delete_statement = $connection_pdo->prepare($sql_temp);
	$paths_delete_statement->execute(["parent_id"=>$page_temp, "child_id"=>$page_temp]);
	execute_checkup($paths_delete_statement->errorInfo(), "deleting ".$_POST['entry_id']." in information_paths");

	$sql_temp = "DELETE FROM ".$database.".information_directory WHERE entry_id=:entry_id";
	$directory_delete_statement = $connection_pdo->prepare($sql_temp);
	$directory_delete_statement->execute(["entry_id"=>$page_temp]);
	execute_checkup($directory_delete_statement->errorInfo(), "deleting ".$_POST['entry_id']." in information_directory");

	replace_redirect("https://".$domain."/".$page_temp."/");

	endif;


if (isset($_POST['save_changes']) && ($_POST['entry_id'] == $page_temp)):

	if (($page_temp == "new") && ($_POST['entry_id'] = $page_temp)): $_POST['entry_id'] = random_code(7); endif;

	function clean_empty_array($array_temp) {
		if (ctype_space($array_temp)): return null; endif;
		foreach ($array_temp as $key_temp => $value_temp):
			if (empty($value_temp)): unset($array_temp[$key_temp]); continue; endif;
			if (is_array($value_temp)): $array_temp[$key_temp] = clean_empty_array($value_temp);
			else:
				$value_temp = str_replace("[[[", "\n\n[[[", $value_temp);
				$value_temp = str_replace("]]]", "]]]\n\n", $value_temp);
				$value_temp = preg_replace("/\r\n/", "\n", $value_temp);
				$value_temp = preg_replace('/(?:(?:\r\n|\r|\n)\s*){2}/s', "\n\n", $value_temp);
				$value_temp = trim($value_temp);
				if (ctype_space($value_temp)): $value_temp = null; endif;		
				$array_temp[$key_temp] = htmlspecialchars($value_temp);
				endif;
			endforeach;
		return $array_temp; }

	$values_temp = [
		"entry_id" => $_POST['entry_id'],
		"type" => $_POST['type'],
		"name" => $_POST['name'],
		"alternate_name" => $_POST['alternate_name'],
		"summary" => $_POST['summary'],
		"body" => $_POST['body'],
		"studies" => $_POST['studies'],
		"appendix" => $_POST['appendix'] ];

	$values_temp = clean_empty_array($values_temp);

	foreach ($values_temp as $key_temp => $value_temp):
		if (empty($value_temp) || !(is_array($value_temp))): continue; endif;
		$values_temp[$key_temp] = json_encode($value_temp);
		endforeach;

	// prepare statement
	$sql_temp = sql_setup($values_temp, $database.".information_directory");
	$information_directory_statement = $connection_pdo->prepare($sql_temp);
	$information_directory_statement->execute($values_temp);

	execute_checkup($information_directory_statement->errorInfo(), "updating ".$_POST['entry_id']." in information_directory");

	$values_temp = [
		"path_id" => null,
		"parent_id" => null,
		"path_type" => null,
		"child_id" => null ];
	$sql_temp = sql_setup($values_temp, "information_paths");
	$information_paths_statement = $connection_pdo->prepare($sql_temp);

	$sql_temp = "DELETE FROM ".$database.".information_paths WHERE (path_id=:path_id) OR (parent_id=:parent_id AND path_type=:path_type AND child_id=:child_id)";
	$information_paths_remove_statement = $connection_pdo->prepare($sql_temp);

	$path_types_check_array = array_merge(
		(array)array_keys($_POST['parents']),
		(array)array_keys($entry_info['parents']),
		(array)array_keys($_POST['children']),
		(array)array_keys($entry_info['children']) );

	function paths_check($relationship_type, $parent_id, $path_type, $child_id, $query_id) {
		global $entry_info;
		global $_POST;
		global $connection_pdo;
		global $information_paths_remove_statement;
		global $information_paths_statement;
		$values_temp = [
			"path_id" => $parent_id."_".$child_id."_".$path_type,
			"parent_id" => $parent_id,
			"path_type" => $path_type,
			"child_id" => $child_id ];
		if (in_array("clear_selection", $_POST[$relationship_type][$path_type])): $_POST[$relationship_type][$path_type] = []; endif;
		if (in_array($query_id, $entry_info[$relationship_type][$path_type]) && !(in_array($query_id, $_POST[$relationship_type][$path_type]))):
			$information_paths_remove_statement->execute($values_temp);
			execute_checkup($information_paths_remove_statement->errorInfo(), "removing path in information_paths");
		elseif (!(in_array($query_id, $entry_info[$relationship_type][$path_type])) && in_array($query_id, $_POST[$relationship_type][$path_type])):
			$information_paths_statement->execute($values_temp);
			execute_checkup($information_paths_statement->errorInfo(), "adding path in information_paths");
			endif; }

	foreach ((array)$path_types_check_array as $path_type):

		if (is_int($path_type)): continue; endif;

		if (empty($_POST['parents'][$path_type])): $_POST['parents'][$path_type] = []; endif;
		if (empty($_POST['children'][$path_type])): $_POST['children'][$path_type] = []; endif;
		if (empty($entry_info['parents'][$path_type])): $entry_info['parents'][$path_type] = []; endif;
		if (empty($entry_info['children'][$path_type])): $entry_info['children'][$path_type] = []; endif;
		$_POST['parents'][$path_type] = (array)$_POST['parents'][$path_type];
		$_POST['children'][$path_type] = (array)$_POST['children'][$path_type];
		$entry_info['parents'][$path_type] = (array)$entry_info['parents'][$path_type];
		$entry_info['children'][$path_type] = (array)$entry_info['children'][$path_type];

		$parents_temp = array_merge($_POST['parents'][$path_type], $entry_info['parents'][$path_type]);
		foreach($parents_temp as $path_temp):
			paths_check ("parents", $path_temp, $path_type, $_POST['entry_id'], $path_temp);
			endforeach;

		$children_temp = array_merge($_POST['children'][$path_type], $entry_info['children'][$path_type]);
		foreach($children_temp as $path_temp):
			paths_check ("children", $_POST['entry_id'], $path_type, $path_temp, $path_temp);
			endforeach;

		endforeach;

	if ($page_temp == "new"): replace_redirect("https://".$domain."/".$_POST['entry_id']."/edit/"); endif;

	$entry_info = nesty_page($page_temp);
	$entry_info = $entry_info[$page_temp];

	endif;

$retrieve_page->execute(["page_id"=>$page_temp]);
$result = $retrieve_page->fetchAll();
foreach ($result as $row):
	$entry_info['summary'] = json_decode($row['summary'], true);
	$entry_info['body'] = json_decode($row['body'], true);
	$entry_info['studies'] = $row['studies'];
	endforeach;

?><style>

hr {
	margin: 60px auto; }

h2 {
	display: block;
	text-align: center;
	margin: 0 auto;
	padding: 120px 0 30px 0; }
	
h6 {
	margin: 30px auto 10px;
	min-width: 630px;
	max-width: 630px;
	width: 630px;
	display: block;
	padding: 0;
	font-size: 14px;
	text-align: center;
	text-transform: uppercase;
	font-weight: 700;
	letter-spacing: 1px;
	opacity: 0.5; }

input, textarea, select {
	width: 600px;
	border: 2px solid #eee;
	border-radius: 0;
	margin: 10px auto 40px;
	display: block;
	border-radius: 5px; }

input {
	font-size: 16px;
	padding: 15px; }

textarea {
	font-size: 13px;
	line-height: 16px;
	padding: 15px; }

select {
	font-size: 14px; }

option {
	padding: 10px 7px;
	margin: 0 0 4px 0; }

table {
	width: 850px; }
	
.link_list {
	padding: 0;
	margin: 5px;
	display: block;
	font-size: 15px;
	line-height: 15px; }

.link_sublist {
	width: 150px;
	padding: 0 0 0 10px;
	margin: 0 5px 5px;
	display: block;
	font-size: 13px;
	line-height: 13px; }

.link_sublist .material-icons {
	font-size: 14px;
	opacity: 0.4;
	float: right;
	margin-top: 4px }

.edit_bar {
	position: fixed;
	top: 0;
	left: 0;
	width: 100%;
	margin: 0;
	padding: 0 0 15px 0;
	display: block;
	text-align: center;
	background: #fff;
	box-shadow: 0 0 20px 0 rgba(100,100,100,0.2);
	z-index: 1000; }

.edit_bar span {
	display: inline-block;
	margin: 15px 25px 0 25px; }
	
.edit_bar .button {
	position: absolute;
	left: 2px;
	top: 0px; }

</style><?

$appendix_array = [];
if ($entry_info['type'] == "location"): $appendix_array = [ "unit_id"=>"unit", "parent_id"=>"location" ]; endif;
if ($entry_info['type'] == "place"): $appendix_array = ["latitude"=>"string", "longitude"=>"string", "priority"=>"checkbox" ]; endif;
if ($entry_info['type'] == "village"): $appendix_array = ["latitude"=>"string", "longitude"=>"string"]; endif;
if ($entry_info['type'] == "person"): $appendix_array = [ "birthday"=>"date", "email"=>"string", "telephone"=>"string", "website"=>"string", "facebook"=>"string", "twitter"=>"string" ]; endif;

$new_page = null;
if ($page_temp == "new"): $new_page = "yes"; endif;

echo "<form action='' method='post'>";

$result_temp = file_get_contents("https://".$domain."/api/sitemap/");
$additional_array = json_decode($result_temp, true);

echo "<div class='hover_overlay hover_bottomright'>";
echo "<button type='submit' name='save_changes' value='save' class='material-icons'>save</button>";
if ($new_page !== "yes"):
	echo "<div><button type='submit' name='delete_entry' value='delete' class='material-icons'>delete</button></div></div>";
	echo "<a href='/new/' target='_blank'><div class='hover_overlay hover_bottomleft material-icons'><span>add_circle</span></div></a>";
else:
	echo "</div>";
	endif;


echo "<input type='hidden' name='entry_id' value='$page_temp'>";

if ($new_page == "yes"):
	echo "<select name='type' size='12' required>";
	foreach (array_keys($header_array) as $value_temp):
		echo "<option value='".$value_temp."'>".$value_temp."</option>";
		endforeach;
	echo "</select>";
	echo "</form>";
	footer(); endif;

echo "<div class='edit_bar'>";
echo "<a href='/".$entry_info['type']."/' target='_blank'><span class='button material-icons'>view_list</span></a>";
echo "<span><a href='#name'>Name</a></span>";
echo "<span><a href='#alternate_name'>Alternate name</a></span>";
echo "<span><a href='#summary'>Summary</a></span>";
echo "<span><a href='#body'>Body</a></span>";
echo "<span><a href='#studies'>Studies</a></span>";
echo "<span><a href='#relationships'>Relationships</a></span>";
if (!(empty($appendix_array))): echo "<span><a href='#appendix'>Appendix</a></span>"; endif;
echo "<span><a href='#type'>Type</a></span>";
echo "</div>";

echo "<h2 id='name'>Name</h2>";

foreach ($entry_info['name'] as $language_temp => $value_temp):
	echo "<h6>name / ".$language_temp."</h6>";
	echo "<input name='name[".$language_temp."]' value='".htmlspecialchars($value_temp, ENT_QUOTES)."' maxlength='70'>";
	endforeach;
foreach($site_info['languages'] as $language_temp):
	if (isset($entry_info['name'][$language_temp])): continue; endif;
	echo "<h6>name / ".$language_temp."</h6>";
	echo "<input name='name[".$language_temp."]' maxlength='70'>";
	endforeach;

echo "<h2 id='alternate_name'>Alternate name</h2>";

foreach ($entry_info['alternate_name'] as $language_temp => $value_temp):
	echo "<h6>alternate name / ".$language_temp."</h6>";
	echo "<input name='alternate_name[".$language_temp."]' value='".htmlspecialchars($value_temp, ENT_QUOTES)."' maxlength='70'>";
	endforeach;
foreach($site_info['languages'] as $language_temp):
	if (isset($entry_info['alternate_name'][$language_temp])): continue; endif;
	echo "<h6>alternate name / ".$language_temp."</h6>";
	echo "<input name='alternate_name[".$language_temp."]' maxlength='70'>";
	endforeach;

echo "<h2 id='summary'>Summary</h2>";

foreach ($entry_info['summary'] as $language_temp => $value_temp):
	echo "<h6>summary / ".$language_temp."</h6>";
	echo "<textarea style='height: 250px;' name='summary[".$language_temp."]' maxlength='1000'>".$value_temp."</textarea>";
	endforeach;
foreach($site_info['languages'] as $language_temp):
	if (isset($entry_info['summary'][$language_temp])): continue; endif;
	echo "<h6>summary / ".$language_temp."</h6>";
	echo "<textarea style='height: 250px;' name='summary[".$language_temp."]' maxlength='1000'></textarea>";
	endforeach;

echo "<h2 id='body'>Body</h2>";

foreach ($entry_info['body'] as $language_temp => $value_temp):
	echo "<h6>body / ".$language_temp."</h6>";
	echo "<textarea style='height: 500px; max-height: none;' name='body[".$language_temp."]'>".$value_temp."</textarea>";
	endforeach;
foreach($site_info['languages'] as $language_temp):
	if (isset($entry_info['body'][$language_temp])): continue; endif;
	echo "<h6>body / ".$language_temp."</h6>";
	echo "<textarea style='height: 500px; max-height: none;' name='body[".$language_temp."]'></textarea>";
	endforeach;

echo "<h2 id='studies'>Studies</h2>";

echo "<h6>studies</h6>";
echo "<textarea style='height: 400px;' name='studies'>".$entry_info['studies']."</textarea>";

echo "<h2 id='relationships'>Relationships</h2>";
			
echo "<input type='hidden' name='parents[]'>";
echo "<input type='hidden' name='children[]'>";

function relationships_edit ($relationship_orientation, $relationship_name, $possible_array=[], $multiple=null) {
	global $page_temp;
	global $entry_info;
	echo "<h6>".ucwords($relationship_orientation).": ".str_replace("_", " ", $relationship_name)."</h6>";
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
		echo "<h6>".str_replace("_", " ", $appendix_key)."</h6>";
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

	endif;?>
