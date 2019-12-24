<? $entry_info = $information_array[$page_temp];

$retrieve_page->execute(["page_id"=>$page_temp]);
$result = $retrieve_page->fetchAll();
foreach ($result as $row):
	$entry_info['summary'] = json_decode($row['summary'], true);
	$entry_info['body'] = json_decode($row['body'], true);
	$entry_info['studies'] = $row['studies'];
	endforeach;


// if (!(empty($messenger_bot)) && file_exists("messenger/".$entry_info['entry_id'].".png")):
//	echo "<div id='messenger-code-image' ". $layout_nodisplay_temp .">";
//	echo "<a href='http://m.me/".$messenger_bot."?ref=entry_id=".$page_temp."' target='_blank'><amp-img src='/messenger/".$entry_info['entry_id'].".png' width='200px' height='200px'></amp-img></a></div>";
//	echo "<a href='/".$page_temp."/flyer/' target='_blank'><div id='messenger-flyer-button' ". $layout_nodisplay_temp .">Get flyer</div></a>";
//	endif;

echo "<a href='/".$page_temp."/edit/'><span id='edit-entry' amp-fx='parallax' data-parallax-factor='1.3' $logout_hidden>&#10033; Edit</span></a>";

// Crumbs and GPS ...
echo "<div id='article-breadcrumbs' amp-fx='parallax' data-parallax-factor='1.3'>";
	echo "<a href='/'>".ucfirst($domain)."</a>";
	echo " > <a href='/". $entry_info['type'] ."/'>".$header_array[$entry_info['type']]."</a>";
	if ( ($entry_info['type'] == "location") && !(empty($entry_info['unit_id'])) ):
		echo " > <a href='/". $entry_info['unit_id'] ."/'>";
		body_process("{{{".$entry_info['unit_id']."}}}");
		echo "</a>";
		endif;
	if (!(empty($entry_info['appendix']['latitude'])) && !(empty($entry_info['appendix']['longitude']))):
		echo "<br><a href='https://".$domain."/".$entry_info['entry_id']."/map/' target='_blank'>";
		echo substr($entry_info['appendix']['latitude'],0,6).", ".substr($entry_info['appendix']['longitude'],0,6);
		echo " (GPS)</a>";
		endif;
	echo "</div>";

echo "<article><div vocab='http://schema.org/' typeof='Article'>";

echo "<header><h1 property='name' amp-fx='parallax' data-parallax-factor='1.2'><span>" . implode("</span> &bull; <span>", $entry_info['name']) . "</span></h1></header>";

if (empty($entry_info['parents']['hierarchy'])): $entry_info['parents']['hierarchy'] = []; endif;
$parents_array = array_filter($entry_info['parents']['hierarchy']);
$parents_array = array_unique($entry_info['parents']['hierarchy']);
if (!(empty($parents_array))):
	$plural_temp = null; if (count($parents_array) > 1): $plural_temp = "s"; endif;
	foreach ($parents_array as $key_temp => $parent_id_temp):
		unset($parents_array[$key_temp]);
		$contents_temp = body_process("{{{". $parent_id_temp ."}}}");
		if (empty($contents_temp)): continue; endif;
		// Add a random code in case two entries have the same name
		$parents_array[strip_tags($contents_temp).random_code(5)] = $contents_temp;
		endforeach;
	ksort($parents_array);
	echo "<div class='article-genealogy' amp-fx='parallax' data-parallax-factor='1.25'><b>Parent". $plural_temp ." (". count($parents_array) .")</b>".implode(null, $parents_array)."</div>";
	endif;

if (empty($entry_info['children']['hierarchy'])): $entry_info['children']['hierarchy'] = []; endif;
$children_array = array_filter($entry_info['children']['hierarchy']);
$children_array = array_unique($entry_info['children']['hierarchy']);
if (!(empty($children_array))):
	$plural_temp = null; if (count($children_array) > 1): $plural_temp = "s"; endif;
	foreach ($children_array as $key_temp => $child_id_temp):
		unset($children_array[$key_temp]);
		$contents_temp = body_process("{{{". $child_id_temp ."}}}");
		if (empty($contents_temp)): continue; endif;
		// Add a random code in case two entries have the same name
		$children_array[strip_tags($contents_temp).random_code(5)] = $contents_temp;
		endforeach;
	ksort($children_array);
	echo "<div class='article-genealogy' amp-fx='parallax' data-parallax-factor='1.2'><b>Subpage". $plural_temp ." (". count($children_array) .")</b>".implode(null, $children_array)."</div>";
	endif;

$languages_temp = [];
if (!(empty($entry_info['summary']))): $languages_temp = array_merge($languages_temp, array_keys($entry_info['summary'])); endif;
if (!(empty($entry_info['body']))): $languages_temp = array_merge($languages_temp, array_keys($entry_info['body'])); endif;
if (!(empty($languages_temp))): $languages_temp = array_unique($languages_temp); endif;
if (count($languages_temp) > 1):
	echo "<p><b>Languages</b>";
	foreach($languages_temp as $language_temp): echo "<a href='#".$language_temp."'><span>".ucfirst($language_temp)."</span></a>"; endforeach;
	echo "</p>";
	endif;

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

if (!(empty($entry_info['studies']))):
	echo "<div class='studies'>" . body_process(html_entity_decode(htmlspecialchars_decode($entry_info['studies']))) . "</div>";
	endif;

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

echo "</div></article>"; ?>
