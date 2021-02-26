<? // Arrange entry info
$entry_info = $information_array[$page_temp];

$retrieve_page->execute(["page_id"=>$page_temp]);
$result = $retrieve_page->fetchAll();
foreach ($result as $row):
	$entry_info['summary'] = json_decode($row['summary'], true);
	$entry_info['body'] = json_decode($row['body'], true);
	$entry_info['studies'] = trim($row['studies']);
	$entry_info['appendix'] = json_decode($row['appendix'],true);
	endforeach;

foreach ($entry_info['summary'] as $key_temp => $value_temp):
	if (empty(trim($value_temp))): unset($entry_info['summary'][$key_temp]); endif;
	endforeach;

foreach ($entry_info['body'] as $key_temp => $value_temp):
	if (empty(trim($value_temp))): unset($entry_info['body'][$key_temp]); endif;
	endforeach;

function relationships_array($entry_id, $hierarchy_temp, $descriptor_temp, $list_item=null) {
	
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
			$echo_section_temp .= "++++++{{{".$entry_id_temp."}}}";
			$count_temp++;
			if (!(isset($counter_section[$header_backend]))): $counter_section[$header_backend] = 0; endif;
			$counter_section[$header_backend]++;
			endforeach;

		 // Pluralize
		if ($count_temp > 1): $results_temp = "(".number_format($count_temp)." results)";
		elseif ($count_temp == 1): $results_temp = null;
		else: continue; endif;
	
		$echo_section[] =	"++++++<i>".
					$descriptor_temp.
//					" / {{{https://"./".$header_backend."/'>".$header_frontend."</a>".
					" / ".$header_frontend .
					" ".$results_temp."</i>".
					$echo_section_temp;

		endforeach;
	
	$echo_section = implode(null, $echo_section);
	
	// Pluralize
	if (array_sum($counter_section) > 1): $results_temp = " (".number_format(array_sum($counter_section))." results)";
	elseif (array_sum($counter_section) == 1): $results_temp = null;
	else: $echo_section = null; endif;

	$echo_section = 		"+++<i>".
					$descriptor_temp . $results_temp . "</i>".
					$echo_section;
	
	return $echo_section; }

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
	echo "<div class='navigation-list'>";
	$list_temp = null;
	$list_temp .= "+++Type: <a href='/".$entry_info['type']."/'><span property='genre'>".$site_info['category_array'][$entry_info['type']]."</span></a>"; // Type
	$list_temp .= "+++Publisher: <a href='https://".$domain."'><span property='publisher'>".$publisher."</span></a>"; // Publisher
	$list_temp .= "+++Author: <span property='author'>".$author."</span>"; // Author
	$list_temp .= "+++Type: <a href='/".$entry_info['type']."/'><span property='genre'>".$site_info['category_array'][$entry_info['type']]."</span></a>";
		if (!(empty($entry_info['appendix']['unit']))): 
			$unit_temp = null;
			foreach($entry_info['appendix']['unit'] as $entry_id_temp):
				if (empty($information_array[$entry_id_temp]['header'])): continue; endif;
				$list_temp .= "++++++<a href='/".$entry_id_temp."/'>". $information_array[$entry_id_temp]['header']."</a>";
//				$unit_temp[] = "++++++<a href='/".$entry_id_temp."/'>". $information_array[$entry_id_temp]['header']."</a>";
				endforeach;
//			if (!(empty($unit_temp))):
//				$plural_temp = null;
//				if (count($unit_temp) > 1): $plural_temp = "s"; endif;
//				echo "<ul>" . implode(null, $unit_temp) . "</ul>";
//				endif;
			endif;
//		echo "<li><span class='sidebar-navigation-item-title'>Published: ".date("Y F d", strtotime($entry_info['date_published']))."</span></li>"; // Date published
//		echo "<li><span class='sidebar-navigation-item-title'>Updated: ".date("Y F d, H:i:s", strtotime($entry_info['date_updated']))."</span></li>"; // Date updated
		if (!(empty($entry_info['appendix']['latitude'])) && !(empty($entry_info['appendix']['longitude']))): // GPS
			$list_temp .= "++++++GPS: <a href='https://".$domain."/".$entry_info['entry_id']."/map/' target='_blank'>";
			$list_temp .= substr($entry_info['appendix']['latitude'],0,6).", ".substr($entry_info['appendix']['longitude'],0,6);
			$list_temp .= "</a>";
			endif;
//		echo "</li>";
//		if (count($languages_temp) > 1):
//			echo "<li>Languages<ul>";
//			foreach($languages_temp as $language_temp):
//				echo "<li><a href='#".$language_temp."'><span class='sidebar-navigation-item-title'>".ucfirst($language_temp)."</span></a></li>";
//				endforeach;
//			echo "</ul></li>";
//			endif;
//		echo "</ul></li>";

	$list_temp .=relationships_array($page_temp, "grandparents", "Parents of parent pages", "yes");
	$list_temp .=relationships_array($page_temp, "parents", "Parent pages", "yes");
	$list_temp .=relationships_array($page_temp, "children", "Subpages", "yes");
	$list_temp .=relationships_array($page_temp, "mentions", "Mentions", "yes");

	echo body_process("+-+-+".$list_temp."+-+-+");

	echo "</div>";
	echo "</amp-sidebar>";

echo "<header><h1 property='name' amp-fx='parallax' data-parallax-factor='1.3'>" . $entry_info['header'] . "</h1></header>";

echo "<div class='entry-metadata-wrapper'>";
	echo "<span class='entry-metadata' amp-fx='parallax' data-parallax-factor='1.25'>Published: <time property='datePublished' datetime='".date("Y-m-d", strtotime($entry_info['date_published']))."'> ".date("Y F d", strtotime($entry_info['date_published']))."</time></span>";
	echo "<span class='entry-metadata' amp-fx='parallax' data-parallax-factor='1.25'>Modified: <time property='dateModified' datetime='".date("Y-m-dTH:i:s", strtotime($entry_info['date_published']))."'> ".date("Y F d, H:i:s", strtotime($entry_info['date_updated']))."</time></span>";
	echo "<span class='entry-metadata-more' amp-fx='parallax' data-parallax-factor='1.25' role='button' tabindex='0' on='tap:sidebar-entry-info.toggle'>More details</span>";
	echo "</div>";

echo "<span property='articleBody'>";

if (empty($languages_temp)):
	echo "<div class='navigation-list'>";
	$list_temp = relationships_array($page_temp, "children", "Subpages");
	echo body_process("+-+-+".$list_temp."+-+-+");
	echo "</div>";
	echo "<br><br><br>";
	echo "<br><br><br>";
	endif;

foreach ($languages_temp as $language_temp):
//	echo "<span id='".$language_temp."'></span>";
	if (!(empty($entry_info['summary'][$language_temp]))):
		echo body_process(html_entity_decode(htmlspecialchars_decode($entry_info['summary'][$language_temp]))); endif;
	if (!(empty($entry_info['body'][$language_temp]))):
		echo body_process(html_entity_decode(htmlspecialchars_decode($entry_info['body'][$language_temp]))); endif;
	echo "<br><br><br>";
	echo "<br><br><br>";
	endforeach;

if (!(empty($entry_info['studies']))):
	echo "<div class='studies'><h2>Endnotes</h2>";
	echo body_process(html_entity_decode(htmlspecialchars_decode($entry_info['studies'])));
	echo "</div>";
	endif;

echo "</span>";

echo "</div></article>";
