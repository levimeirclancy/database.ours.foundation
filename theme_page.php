<? // Arrange entry info
$entry_info = $information_array[$page_temp];

$retrieve_page->execute(["page_id"=>$page_temp]);
$result = $retrieve_page->fetchAll();
foreach ($result as $row):
	$entry_info['summary'] = json_decode($row['summary'], true);
	$entry_info['body'] = json_decode($row['body'], true);
	$entry_info['studies'] = $row['studies'];
	$entry_info['appendix'] = json_decode($row['appendix'],true);
	endforeach;

function relationships_array($entry_id, $hierarchy_temp, $descriptor_temp) {
	
	global $information_array;
	global $site_info;

	// If empty, just move on
	if (empty($information_array[$entry_id][$hierarchy_temp])): return; endif;
		
	// If not empty, let's clean it up
	$array_temp = $information_array[$entry_id][$hierarchy_temp];
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
		
	// Assecmble the section defaults
	$echo_section = [];
	$counter_section = null;

	foreach ($site_info['category_array'] as $header_backend => $header_frontend):
		
		$count_temp = $echo_section_temp = null;
	
		foreach ($array_output_temp as $entry_id_temp => $entry_header_temp):
			if ($entry_id_temp == $entry_id): continue; endif; // do not show itself as its own parent etc
			if ($information_array[$entry_id_temp]['type'] !== $header_backend): continue; endif;
			$echo_section_temp .= "<li><a href='/".$entry_id_temp."/'><span class='sidebar-navigation-item-title'>".$entry_header_temp."</span></a></li>";
			$count_temp++;
			if (!(isset($counter_section[$header_backend]))): $counter_section[$header_backend] = 0; endif;
			$counter_section[$header_backend]++;
			endforeach;

		 // Pluralize
		if ($count_temp > 1): $results_temp = "(".number_format($count_temp)." results)";
		elseif ($count_temp == 1): $results_temp = null;
		else: continue; endif;
	
		$echo_section[] =	"<li><i><span class='sidebar-navigation-item-title'>".
					$descriptor_temp.
					" / <a href='/".$header_backend."/'>".$header_frontend."</a>".
					" ".$results_temp."</span></i></li>".
					$echo_section_temp;

		endforeach;
	
	$echo_section = "<ul>".implode("</ul><ul>", $echo_section)."</ul>";
	
	// Pluralize
	if (array_sum($counter_section) > 1): $results_temp = " (".number_format(array_sum($counter_section))." results)";
	elseif (array_sum($counter_section) == 1): $results_temp = null;
	else: $echo_section = null; endif;

	$echo_section_final = 		"<li><b><span class='sidebar-navigation-item-title'>".
					$descriptor_temp . $results_temp . "</span></b>".
					$echo_section . "</li>";
	
	echo $echo_section_final; }

// Check for language and content
$languages_temp = [];
if (!(empty($entry_info['summary']))): $languages_temp = array_merge($languages_temp, array_keys($entry_info['summary'])); endif;
if (!(empty($entry_info['body']))): $languages_temp = array_merge($languages_temp, array_keys($entry_info['body'])); endif;
if (!(empty($languages_temp))): $languages_temp = array_unique($languages_temp); endif;

// Find grandparents
$information_array[$page_temp]['grandparents'] = [ ];
if (isset($information_array[$page_temp]['parents'])):
	$grandparents_array = [];
	foreach ($information_array[$page_temp]['parents'] as $entry_id_temp):
		if (!(isset($information_array[$entry_id_temp]['parents']))): continue; endif;
		if (empty($information_array[$entry_id_temp]['parents'])): continue; endif;
		$grandparents_array = array_merge($grandparents_array, $information_array[$entry_id_temp]['parents']);
		endforeach;
	if (!(empty($grandparents_array))):
		$grandparents_array = array_diff($grandparents_array, $information_array[$page_temp]['parents']);
		$grandparents_array = array_diff($grandparents_array, $information_array[$page_temp]['children']);
		$information_array[$page_temp]['grandparents'] = $grandparents_array;
		endif;
	endif;

// Find mentions
$information_array[$page_temp]['mentions'] = [ ];
$search_results = file_get_contents("https://".$domain."/api/search/?search={{{".$page_temp."}}}");
$search_results = json_decode($search_results, true);
if ($search_results['searchCount'] > 0):
	$information_array[$page_temp]['mentions'] = [ ];
	foreach($search_results['searchResults'] as $entry_info_temp):
		if (in_array($entry_info_temp['entry_id'], $information_array[$page_temp]['grandparents'])): continue; endif;
		if (in_array($entry_info_temp['entry_id'], $information_array[$page_temp]['parents'])): continue; endif;
		if (in_array($entry_info_temp['entry_id'], $information_array[$page_temp]['children'])): continue; endif;
		$information_array[$page_temp]['mentions'][] = $entry_info_temp['entry_id'];
		endforeach;
	endif;

// Begin article content
echo "<article><div vocab='http://schema.org/' typeof='Article'>";

// Sidebar
echo "<amp-sidebar id='sidebar-entry-info' layout='nodisplay' side='right'>";
	echo "<div class='sidebar-back' on='tap:sidebar-entry-info.close' role='button' tabindex='0'>Close</div>";
	echo "<ul>";
	echo "<li><span class='sidebar-navigation-item-title'><b>Metadata</b></span>";
		echo "<ul>";
		echo "<li><span class='sidebar-navigation-item-title'>Type: <a href='/".$entry_info['type']."/'><span property='genre'>".$site_info['category_array'][$entry_info['type']]."</span></a></span></li>"; // Type
		echo "<li><span class='sidebar-navigation-item-title'>Publisher: <a href='https://".$domain."'><span property='publisher'>".$publisher."</span></a></span></li>"; // Publisher
		echo "<li><span class='sidebar-navigation-item-title'>Author: <span property='author'>".$author."</span></span></li>"; // Author
		echo "<li><span class='sidebar-navigation-item-title'>Type: <a href='/".$entry_info['type']."/'><span property='genre'>".$site_info['category_array'][$entry_info['type']]."</span></a></span></li>"; // Type
//		echo "<li><span class='sidebar-navigation-item-title'>Published: ".date("Y F d", strtotime($entry_info['date_published']))."</span></li>"; // Date published
//		echo "<li><span class='sidebar-navigation-item-title'>Updated: ".date("Y F d, H:i:s", strtotime($entry_info['date_updated']))."</span></li>"; // Date updated
		if (!(empty($entry_info['appendix']['latitude'])) && !(empty($entry_info['appendix']['longitude']))): // GPS
			echo "<li><a href='https://".$domain."/".$entry_info['entry_id']."/map/' target='_blank'><span class='sidebar-navigation-item-title'>";
			echo substr($entry_info['appendix']['latitude'],0,6).", ".substr($entry_info['appendix']['longitude'],0,6);
			echo " (GPS)</span></a></li>";
			endif;
		if (!(empty($entry_info['appendix']['unit']))): 
			$unit_temp = null;
			foreach($entry_info['appendix']['unit'] as $entry_id_temp):
				if (empty($information_array[$entry_id_temp]['header'])): continue; endif;
				$unit_temp[] = "<li><a href='/".$entry_id_temp."/'>". $information_array[$entry_id_temp]['header']."</a></li>";
				endforeach;
			if (!(empty($unit_temp))):
				$plural_temp = null;
				if (count($unit_temp) > 1): $plural_temp = "s"; endif;
				$unit_temp = "<li>Unit".$plural_temp."<ul>" . implode(null, $unit_temp) . "</ul></li>";
				echo $unit_temp;
				endif;
			endif;
		if (count($languages_temp) > 1):
			$language_array_temp = [];
			foreach($languages_temp as $language_temp):
				echo "<li><a href='#".$language_temp."'><span class='sidebar-navigation-item-title'>".ucfirst($language_temp)."</span></a></li>";
				endforeach;
			endif;
		echo "</ul></li>";

	relationships_array($page_temp, "grandparents", "Parents of parent pages");
	relationships_array($page_temp, "parents", "Parent pages");
	relationships_array($page_temp, "children", "Subpages");
	relationships_array($page_temp, "mentions", "Mentions");

	echo "</ul>";
	echo "</amp-sidebar>";

echo "<header><h1 property='name' amp-fx='parallax' data-parallax-factor='1.3'>" . $entry_info['header'] . "</h1></header>";

echo "<div class='entry-metadata-wrapper'>";
	echo "<span class='entry-metadata' amp-fx='parallax' data-parallax-factor='1.25'>Published: <time property='datePublished' datetime='".date("Y-m-d", strtotime($entry_info['date_published']))."'> ".date("Y F d", strtotime($entry_info['date_published']))."</time></span>";
	echo "<span class='entry-metadata' amp-fx='parallax' data-parallax-factor='1.25'>Modified: <time property='dateModified' datetime='".date("Y-m-dTH:i:s", strtotime($entry_info['date_published']))."'> ".date("Y F d, H:i:s", strtotime($entry_info['date_updated']))."</time></span>";
	echo "<span class='entry-metadata-more' amp-fx='parallax' data-parallax-factor='1.25' role='button' tabindex='0' on='tap:sidebar-entry-info.toggle'>More details</span>";
	echo "</div>";

echo "<span property='articleBody'>";

if (empty($languages_temp)):
	echo "<ul class='sidebar-entry-info-list'>";
	relationships_array($page_temp, "children", "Subpages");
	echo "</ul>";
	endif;

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
