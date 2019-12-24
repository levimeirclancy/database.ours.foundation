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

echo "<article><div vocab='http://schema.org/' typeof='Article'>";

echo "<p><a href='/'>".$domain."</a>";
echo " > <a href='/". $entry_info['type'] ."/'>".strtolower($header_array[$entry_info['type']])."</a>";

if ($entry_info['type'] == "location"):
	echo " > <a href='/". $entry_info['unit_id'][0] ."/'>";
	body_process("{{{".$entry_info['unit_id'][0]."}}}");
	echo "</a>";
	endif;

if (!(empty($entry_info['appendix']['latitude'])) && !(empty($entry_info['appendix']['longitude']))):
	echo "<br><a href='https://".$domain."/".$entry_info['entry_id']."/map/' target='_blank'>";
	echo substr($entry_info['appendix']['latitude'],0,6).", ".substr($entry_info['appendix']['longitude'],0,6);
	echo " (GPS)</a>";
	endif;

echo "</p>";


echo "<header><h1 property='name' amp-fx='parallax' data-parallax-factor='1.2'><span>" . implode("</span> &bull; <span>", $entry_info['name']) . "</span></h1></header>";

echo "<div class='genealogy_interstice' amp-fx='parallax' data-parallax-factor='1.05'>";

if (!(empty($entry_info['parents']['hierarchy']))):
	$entry_info['parents']['hierarchy'] = array_unique($entry_info['parents']['hierarchy']);
	$entry_array = [];
	foreach ($entry_info['parents']['hierarchy'] as $parent_id):
		if ($parent_id == $entry_info['entry_id']): continue; endif;
		$entry_array[] = "{{{".$parent_id."}}}";
		endforeach;
	if (!(empty($entry_array))):
		$entry_array = "<b>Parents: </b> <span>".implode("</span><span>", $entry_array)."</span>";
		echo body_process($entry_array);
		endif;
	endif;

if (!(empty($entry_info['children']['hierarchy']))):
	$entry_info['children']['hierarchy'] = array_unique($entry_info['children']['hierarchy']);
	$entry_array = [];
	foreach ($entry_info['children']['hierarchy'] as $child_id):
		if ($child_id == $entry_info['entry_id']): continue; endif;
		$entry_array[] = "{{{".$child_id."}}}";
		endforeach;
	if (!(empty($entry_array))):
		$entry_array = "<b>Subpages: </b> <span>".implode("</span><span>", $entry_array)."</span>";
		echo body_process($entry_array);
		endif;
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
