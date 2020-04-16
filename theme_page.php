<? // Crumbs and GPS ...
echo "<div id='article-breadcrumbs'>";

	function relationships_array($hierarchy_temp, $descriptor_temp) {
		global $entry_info;

		// Isolate the array we want
		if (empty($entry_info[$hierarchy_temp]['hierarchy'])): $entry_info[$hierarchy_temp]['hierarchy'] = []; endif;
		$array_temp = array_filter($entry_info[$hierarchy_temp]['hierarchy']);
		$array_temp = array_unique($entry_info[$hierarchy_temp]['hierarchy']);
		
		// 
		foreach ($array_temp as $key_temp => $parent_id_temp):
			unset($array_temp[$key_temp]);
			$contents_temp = body_process("{{{". $parent_id_temp ."}}}");
			if (empty($contents_temp)): continue; endif;
			// Add a random code in case two entries have the same name
			$array_temp[strip_tags($contents_temp).random_code(5)] = $contents_temp;
			endforeach;
		
		//
		if (empty($array_temp)): return; endif;
		
		//
		ksort($array_temp);
		$plural_temp = $descriptor_temp;
		if (count($array_temp) > 1): $plural_temp .= "s (". count($array_temp) .")"; endif;
		echo "<p class='article-genealogy' amp-fx='parallax' data-parallax-factor='1.25'><b>". $plural_temp ."</b>".implode(null, $array_temp)."</p>";

		}

	empty($login) ? $login_hidden = "hide" : $login_hidden = "navigation-header-item";
	echo "<a href='/".$page_temp."/edit/'><span id='edit-entry' amp-fx='parallax' data-parallax-factor='1.3' [class]=\"pageState.loginStatus == 'loggedin' ? 'navigation-header-item' : 'hide'\" class='$login_hidden'>&#10033; Edit</span></a>";

	// Type
	echo "<p amp-fx='parallax' data-parallax-factor='1.3' role='button' tabindex='0' on='tap:tap:sidebar-navigation.open,sidebar-navigation-lightbox-".$entry_info['type'].".open'>Type: ".$header_array[$entry_info['type']]."</p>";

	if (!(empty($entry_info['appendix']['latitude'])) && !(empty($entry_info['appendix']['longitude']))):
		echo "<p amp-fx='parallax' data-parallax-factor='1.3'><a href='https://".$domain."/".$entry_info['entry_id']."/map/' target='_blank'>";
		echo substr($entry_info['appendix']['latitude'],0,6).", ".substr($entry_info['appendix']['longitude'],0,6);
		echo " (GPS)</a></p>";
		endif;

//	$languages_temp = [];
//	if (!(empty($entry_info['summary']))): $languages_temp = array_merge($languages_temp, array_keys($entry_info['summary'])); endif;
//	if (!(empty($entry_info['body']))): $languages_temp = array_merge($languages_temp, array_keys($entry_info['body'])); endif;
//	if (!(empty($languages_temp))): $languages_temp = array_unique($languages_temp); endif;
//	if (count($languages_temp) > 1):
//		$language_array_temp = [];
//		foreach($languages_temp as $language_temp): $language_array_temp[] = "<a href='#".$language_temp."'>".ucfirst($language_temp)."</a>"; endforeach;
//		echo "<br>Languages > ". implode("&nbsp;&nbsp;|&nbsp;&nbsp;", $language_array_temp);
//		endif;

	relationships_array("parents", "Parent");
	relationships_array("children", "Subpage");

	endif;

// Arrange entry info

$entry_info = $information_array[$page_temp];

$retrieve_page->execute(["page_id"=>$page_temp]);
$result = $retrieve_page->fetchAll();
foreach ($result as $row):
	$entry_info['summary'] = json_decode($row['summary'], true);
	$entry_info['body'] = json_decode($row['body'], true);
	$entry_info['studies'] = $row['studies'];
	endforeach;

echo "<article><div vocab='http://schema.org/' typeof='Article'>";

echo "<header><h1 property='name' amp-fx='parallax' data-parallax-factor='1.2'><span>" . implode("</span> &bull; <span>", $entry_info['name']) . "</span></h1></header>";

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
