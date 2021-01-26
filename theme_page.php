<? // Arrange entry info
$entry_info = $information_array[$page_temp];

$retrieve_page->execute(["page_id"=>$page_temp]);
$result = $retrieve_page->fetchAll();
foreach ($result as $row):
	$entry_info['summary'] = json_decode($row['summary'], true);
	$entry_info['body'] = json_decode($row['body'], true);
	$entry_info['studies'] = $row['studies'];
	endforeach;

function relationships_array($entry_id, $hierarchy_temp, $descriptor_temp) {
	
	global $information_array;

	// If empty, just move on
	if (empty($information_array[$entry_id][$hierarchy_temp]['hierarchy'])): return; endif;
		
	// If not empty, let's clean it up
	$array_temp = $information_array[$entry_id][$hierarchy_temp]['hierarchy'];
	$array_temp = array_filter($array_temp);
	$array_temp = array_unique($array_temp);
	
	// Sets the ordering and ensures the entry IDs exist
	$array_temp = array_intersect(array_keys($information_array), $array_temp);

	// Get the output array ready	
	$array_output_temp = [];
	
	// Get the headers
	foreach ($array_temp as $entry_id_temp):
		if (empty($information_array[$entry_id_temp]['header'])): continue; endif;
		$array_output_temp[$entry_id_temp] = $information_array[$entry_id_temp]['header'];
		endforeach;
		
	// If nothing made, then just end here
	if (empty($array_output_temp)): return; endif;
		
	// Finally echo it out
	echo "<div class='article-info-section'>";
	echo "<span class='article-info-section-caption'>". $descriptor_temp ." list</span>";
	foreach ($array_output_temp as $entry_id_temp => $entry_header_temp):
		echo "<span class='article-info-section-item'><a href='/".$entry_id_temp."/'>".$entry_header_temp."</a></span>";
		endforeach;
	echo "</div>"; }

echo "<article><div vocab='http://schema.org/' typeof='Article'>";

echo "<header><h1 property='name' amp-fx='parallax' data-parallax-factor='1.3'>" . $entry_info['header'] . "</h1></header>";

// Crumbs and GPS ...
echo "<div class='article-info' amp-fx='parallax' data-parallax-factor='1.2'>";

	echo "<div class='article-info-section'><span class='article-info-section-caption'>Metadata</span>";

	// Type
	echo "<span class='article-info-section-item'>Type: <a href='/".$entry_info['type']."/'>".$header_array[$entry_info['type']]."</a></span>";

	// Date published
	echo "<span class='article-info-section-item'>Published: ".date("Y F j", strtotime($entry_info['date_published']))."</span>";

	// Date modified
	echo "<span class='article-info-section-item'>Modified: ".date("Y F j, H:i:s", strtotime($entry_info['date_modified']))."</span>";

	// GPS
	if (!(empty($entry_info['appendix']['latitude'])) && !(empty($entry_info['appendix']['longitude']))):
		echo "<span class='article-info-section-item'><a href='https://".$domain."/".$entry_info['entry_id']."/map/' target='_blank'>";
		echo substr($entry_info['appendix']['latitude'],0,6).", ".substr($entry_info['appendix']['longitude'],0,6);
		echo " (GPS)</a></span>";
		endif;

	$languages_temp = [];
	if (!(empty($entry_info['summary']))): $languages_temp = array_merge($languages_temp, array_keys($entry_info['summary'])); endif;
	if (!(empty($entry_info['body']))): $languages_temp = array_merge($languages_temp, array_keys($entry_info['body'])); endif;
	if (!(empty($languages_temp))): $languages_temp = array_unique($languages_temp); endif;
	if (count($languages_temp) > 1):
		$language_array_temp = [];
		foreach($languages_temp as $language_temp):
			echo "<span class='article-info-section-item'><a href='#".$language_temp."'>".ucfirst($language_temp)."</a></span>";
			endforeach;
		endif;

	// Edit
//	$login_hidden = $logout_hidden = "article-info-section-item"; // This would mean that buttons to login AND logout are shown
//	(empty($login) ? $logout_hidden = "hide" : $login_hidden = "hide");
//	echo "<a href='/".$page_temp."/edit/'><span [class]=\"pageState.login.loginStatus == 'loggedin' ? 'article-info-section-item' : 'hide'\" class='".$logout_hidden."'>Edit entry</span></a>";

	echo "</div>";

	relationships_array($page_temp, "parents", "Parent");
	relationships_array($page_temp, "children", "Subpage");

	// Add a relationships_array for mentions
//	$search = "/api/search/?search=test";

	// Add one for mentions

	echo "</div>";

echo "<span property='articleBody'>";

foreach ($languages_temp as $language_temp):
	echo "<span id='".$language_temp."'></span>";
	if (!(empty($entry_info['summary'][$language_temp]))):
		echo body_process(html_entity_decode(htmlspecialchars_decode($entry_info['summary'][$language_temp]))); endif;
	if (!(empty($entry_info['body'][$language_temp]))):
		echo body_process(html_entity_decode(htmlspecialchars_decode($entry_info['body'][$language_temp]))); endif;
	echo "<hr>";
	endforeach;

if (!(empty($entry_info['studies']))): echo "<div class='studies'>" . body_process(html_entity_decode(htmlspecialchars_decode($entry_info['studies']))) . "</div>"; endif;

if ($entry_info['type'] == "person"):
	// person info an terms
//	$terms_array = get_terms(["person_id"=>$page_temp]);
	if (!(empty($terms_array))):
		echo "<hr><table><thead><tr><th>term</th><th>person</th><th>position</th><th>for</th><th>party</th><th>start</th><th>end</th><th>vote</th></tr></thead><tbody>";
		foreach($terms_array as $term_id => $term_info):
			$information_array = get_entries(["entry_id"=>$term_info]);
			echo "<tr><td><a href='?term_id=$term_id'>$term_id</a></td>";
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
		echo "</tbody></table><hr>";
		endif;
	endif;

echo "</span>";

echo "</div></article>";
