<? // body_process, nesty_page, nesty_media, nesty_entry, clip_length, number_condense

// prepare for nesty_page
$sql_temp = "SELECT * FROM $database.information_directory WHERE entry_id=:page_id";
$retrieve_page = $connection_pdo->prepare($sql_temp);

// prepare for nesty_media
$sql_temp = "SELECT * FROM $database.media WHERE media_id=:media_id";
$retrieve_media = $connection_pdo->prepare($sql_temp);

// prepare for nesty_entry
// $sql_temp = "SELECT * FROM $database.entries WHERE entry_id=:entry_id";
// $retrieve_entry = $connection_pdo->prepare($sql_temp);

// prepare for paths
$sql_temp = "SELECT * FROM $database.information_paths WHERE parent_id=:content_id OR child_id=:content_id";
$retrieve_paths = $connection_pdo->prepare($sql_temp);

function sanitize_dates ($row=[], $additions_array=[]) {
	
	global $domain;
	global $site_info;
	
	$name_temp = [];
	if (!(empty(json_decode($row['name'], true)))): $name_temp = json_decode($row['name'], true); endif;
	if (!(is_array($name_temp))): $name_temp = []; endif;
	foreach ($name_temp as $key_temp => $value_temp):
		if (empty(trim($value_temp))): $name_temp[$key_temp] = null; endif;
		endforeach;
	$name_temp = array_filter($name_temp);
	if (empty($name_temp)):
		$language_temp = reset($site_info['languages']);
		$name_temp = [ $language_temp => "[ No title ]" ];
		endif;
	
	$entry_info = [
		"entry_id"		=> $row['entry_id'],
		"type"			=> $row['type'],
		"date_published"	=> date("Y-m-d"),
		"date_updated"		=> date("Y-m-d H:i:s"),
		"name"			=> $name_temp,
		"header"		=> null,
		"page_id"		=> $row['entry_id'],
		"link"			=> $row['link'] = "https://".$domain."/".$row['entry_id']."/",
		];
	
	if (in_array("parents", $additions_array)): $entry_info['parents'] = []; endif;
	if (in_array("children", $additions_array)): $entry_info['parents'] = []; endif;
	if (in_array("appendix", $additions_array)): $entry_info['parents'] = []; endif;
	
//	"name" => json_decode($row['name'], true),
//	"alternate_name" => json_decode($row['alternate_name'], true),
	
	// Correct the header array
	$header_array_corrections = [
		// Old type	// New type
		"location"	=> "regions",
		"village"	=> "settlements",
		"event"		=> "article",
		"topic"		=> "article",
		"position"	=> "offices-units",
		"term"		=> "offices-units",
		"terms"		=> "offices-units",
		];
	if (isset($header_array_corrections[$entry_info['type']])):
		$entry_info['type'] = $header_array_corrections[$entry_info['type']];
		endif;
//	if (empty($entry_info['type']) || empty($ite_info['category_array'][$entry_info['type']])):
//		$entry_info['type'] = array_key_first($ite_info['category_array']);
//		endif;
	
	// Because this column was added in an upgrade, it has to be constructed
	if (isset($row['date_updated']) && !(empty($row['date_updated']))):
		$entry_info['date_updated'] = date("Y-m-d H:i:s", strtotime($row['date_updated']));
	elseif (isset($row['timestamp']) && !(empty($row['timestamp']))):
		$entry_info['date_updated'] = date("Y-m-d H:i:s", strtotime($row['timestamp']));
		endif;
	
	// Because this column was added in an upgrade, it has to be constructed
	if (isset($row['date_published']) && !(empty($row['date_published']))):
		$entry_info['date_published'] = date("Y-m-d", strtotime($row['date_published']));
	elseif (isset($row['date_updated']) && !(empty($row['date_updated']))):
		$entry_info['date_published'] = date("Y-m-d", strtotime($row['date_updated']));
	elseif (isset($row['timestamp']) && !(empty($row['timestamp']))):
		$entry_info['date_published'] = date("Y-m-d", strtotime($row['timestamp']));
	endif;
	
	// Ensure they are formatted
	$entry_info['date_updated'] = date("Y-m-d H:i:s", strtotime($entry_info['date_updated']));
	$entry_info['date_published'] = date("Y-m-d", (strtotime($entry_info['date_published'])+5));
	
	// Prepare the name
	$name_temp = [];
	if (!(empty($row['name']))): $name_temp = json_decode($row['name'], true);
	elseif (!(empty($entry_info['name']))): $name_temp = $entry_info['name']; endif;
	if (!(is_array($name_temp))): $name_temp = []; endif;
	
	// Set up the name
	$entry_info['name'] = $name_temp;
	
	// Now set up the header
	$name_temp = array_filter($entry_info['name']);
	$entry_info['header'] = implode(" • ", $name_temp);

	return $entry_info; }


function nesty_page($page_id_temp) {

	global $domain;
	global $publisher;
	global $site_info;
	global $information_array;

	global $connection_pdo;
	global $retrieve_page;
	global $retrieve_media;
//	global $retrieve_entry;
	global $retrieve_paths;
	
	// Capture #anchor and add to array
	
	// Check for $domain

	if (empty($page_id_temp)): return null; endif;

	if (!(empty($information_array[$page_id_temp]))):
		$page_info = [ $page_id_temp => $information_array[$page_id_temp] ];
		return $page_info; endif;

	$domain_temp = $domain;
	if (strpos($page_id_temp, "|")):
		$domain_page_id_temp = explode("|", $page_id_temp);
		if (strpos($domain_page_id_temp[0], ".")): $domain_temp = $domain_page_id_temp[0]; $page_id_temp = $domain_page_id_temp[1];
		else: $domain_temp = $domain_page_id_temp[1]; $page_id_temp = $domain_page_id_temp[0]; endif; endif;
	$page_info = [];
	if (empty($domain_temp) || ($domain == $domain_temp)):
		$retrieve_page->execute(["page_id"=>$page_id_temp]);
		$result = $retrieve_page->fetchAll();
		foreach ($result as $row):

			$page_info[$row['entry_id']] = sanitize_dates($row);

			// Check if there is supposed to be an appendix
			if (!(isset($site_info['appendix_array'][$page_info[$row['entry_id']]['type']]))): continue; endif;

			// Set up the appendix according to the authorized, pre-defined structure; discard excess
			$appendix_sorted_temp = [];
			$appendix_sql_temp = json_decode($row['appendix'], true);
			foreach ($site_info['appendix_array'][$page_info[$row['entry_id']]['type']] as $appendix_key => $appendix_type):
				$appendix_sorted_temp[$appendix_key] = null;
				if (!(isset($appendix_sql_temp[$appendix_key]))): continue; endif;
				$appendix_sorted_temp[$appendix_key] = $appendix_sql_temp[$appendix_key];
				endforeach;

			// Update the $page_info
			$page_info[$row['entry_id']]['appendix'] = $appendix_sorted_temp;

			endforeach;

		$retrieve_paths->execute(["content_id"=>$page_id_temp]);
		$result = $retrieve_paths->fetchAll();
		foreach ($result as $row):
			if ($row['parent_id'] == $row['child_id']): continue; endif;
			if ($row['path_type'] == "parent_id"):
				$row['path_type'] = "hierarchy";
				$temp = $row['child_id'];
				$row['child_id'] = $row['parent_id'];
				$row['parent_id'] = $temp;
				endif;
			if ($page_id_temp == $row['parent_id']):
//				$page_info[$page_id_temp]['children'][$row['path_type']][] = $row['child_id'];
				$page_info[$page_id_temp]['children'][] = $row['child_id'];
				endif;
			if ($page_id_temp == $row['child_id']):
//				$page_info[$page_id_temp]['parents'][$row['path_type']][] = $row['parent_id'];
				$page_info[$page_id_temp]['parents'][] = $row['parent_id'];
				endif;
			endforeach;

	else:
		$page_info = file_get_contents("https://".$domain_temp."/".$page_id_temp."/ping/"); // check if the page exists
		$page_info = json_decode($page_info, true); // decode the json
		endif;
	if (empty($page_info[$page_id_temp])): return null; endif;
	return $page_info; }

function nesty_media($media_id_temp, $response_temp="full") {
	global $domain;
	global $publisher;
	
	return;

	global $connection_pdo;
	global $retrieve_page;
	global $retrieve_media;
//	global $retrieve_entry;
	global $retrieve_paths;

	if (empty($media_id_temp)): return null; endif;
	$domain_temp = $domain;
	if (strpos($media_id_temp, "|")):
		$domain_media_id_temp = explode("|", $media_id_temp);
		if (strpos($domain_media_id_temp[0], ".")): $domain_temp = $domain_media_id_temp[0]; $media_id_temp = $domain_media_id_temp[1];
		else: $domain_temp = $domain_media_id_temp[1]; $media_id_temp = $domain_media_id_temp[0]; endif; endif;	
	if (empty($domain_temp) || ($domain == $domain_temp)):

		$retrieve_media->execute(["media_id"=>utf8_encode($media_id_temp)]);
	
		$result = $retrieve_media->fetchAll();
		
		foreach ($result as $row):
		
			$description_temp = $width_temp = $height_temp = $type_temp = $attr_temp = null;

			if ($response_temp == "full"):
				$description_temp = $row['description'];
				// convert all images to links
				preg_match_all("/(?<=\[\[\[)(.*?)(?=\]\]\])/is", $description_temp, $matches_temp);
				if (empty($matches_temp)): $matches_temp = [ [], [] ]; endif;
				foreach ($matches_temp[0] as $temp): $description_temp = str_replace("[[[".$temp."]]]", "{{{".str_replace("][", "}{", $temp)."}}}", $description_temp); endforeach;
				$description_temp = body_process($description_temp);
				endif;

			// check if file exists and height and width
			$thumb_url = "https://".$domain_temp."/media/".$row['directory']."/".$row['filename_thumb'];
			list($width_temp, $height_temp, $type_temp, $attr_temp) = getimagesize($thumb_url);
			if (empty($width_temp)): continue; endif;
	
			$media_info[$row['media_id']] = [
				"media_id"=>$row['media_id'],
				"domain"=>$domain_temp,
				"publisher"=>$publisher,
				"link"=>"https://$domain/m/".$row['media_id']."/",
				"directory"=>$row['directory'],
				"description"=>$description_temp,
				"height"=>$height_temp, // provided by list function
				"width"=>$width_temp, // provided by list function
 				"type"=>$type_temp, // provided by list function
 				"attr"=>$attr_temp, // provided by list function
				"header"=>$row['datetime_original'],
				"datetime_original"=>$row['datetime_original'],
				"parents"=> [],
				"children"=> [] ];
			endforeach;
		$retrieve_paths->execute(["content_id"=>$media_id_temp]);
		$result = $retrieve_paths->fetchAll();
		foreach ($result as $row):
			if ($row['path_type'] == "parent_id"): $row['parent_id'] = $row['child_id']; $row['child_id'] = $media_id_temp; $row['path_type'] = "hierarchy"; endif;
			if ($media_id_temp == $row['parent_id']): $media_info[$media_id_temp]['children'][$row['path_type']][] = $row['child_id']; endif;
			if ($media_id_temp == $row['child_id']): $media_info[$media_id_temp]['parents'][$row['path_type']][] = $row['parent_id']; endif;
			endforeach;
	else:
		$media_info = file_get_contents("https://".$domain_temp."/m/".(string)$media_id_temp."/ping/"); // check if the media exists	
		$media_info = json_decode($media_info, true); // decode the json
		endif;
	if (empty($media_info[$media_id_temp])): return null; endif;
	return $media_info; }



// function nesty_entry($entry_id_temp) {
//	return null; // no entries here
//	global $domain;
//	global $publisher;

//	global $connection_pdo;
//	global $retrieve_page;
//	global $retrieve_media;
//	global $retrieve_entry;

//	if (empty($entry_id_temp)): return null; endif;
//	if (strpos($entry_id_temp, "|")):
//		$domain_entry_id_temp = explode("|", $entry_id_temp);
//		if (strpos($domain_entry_id_temp[0], ".")): $domain_temp = $domain_entry_id_temp[0]; $entry_id_temp = $domain_entry_id_temp[1];
//		else: $domain_temp = $domain_entry_id_temp[1]; $entry_id_temp = $domain_entry_id_temp[0]; endif; endif;
//	if (empty($domain_temp) || ($domain == $domain_temp)):
//		return null; // there are no entries in this CMS
//		$entry_confirmed = [];
//		$retrieve_entry->execute(["entry_id"=>(string)$entry_id_temp]);
//		$result = $retrieve_entry->fetchAll();
//		foreach ($result as $row): $entry_confirmed = $row; endforeach;
//		if (empty($entry_confirmed)): return null; endif;
//		$entry_confirmed['body'] = body_process($entry_confirmed['body']);
//		$entry_confirmed['domain'] = $domain;
///		$entry_confirmed['publisher'] = $publisher;
//		$entry_info = [ $entry_confirmed['entry_id'] => $entry_confirmed ];
//	else:
//		$entry_info = file_get_contents("https://$domain/e/".(string)$entry_id_temp."/ping/"); // check if the media exists
//		$entry_info = json_decode($entry_info, true); // decode the json
//		endif;
//	if (empty($entry_info[$entry_id_temp])): return null; endif;
//	return $entry_info; }


function body_process($body_incoming) {
	global $domain;
	global $login;
	
	global $connection_pdo;
	global $retrieve_page;
	global $retrieve_media;
//	global $retrieve_entry;
			
	// Standardize line breaks
	$body_incoming = str_replace("\r", "\n", $body_incoming);
	
	// Replace double spces
//	$body_incoming = preg_replace("/\t\t+/", " ", $body_incoming);
//	$body_incoming = preg_replace("/^\S\t\n\r+/", "2", $body_incoming);
	
	$delimiter = "\n\n";

	$body_incoming = $delimiter.$body_incoming.$delimiter;
	
	$body_incoming = str_replace($delimiter."|||***", $delimiter."<table><thead><tr><th>", $body_incoming);
	$body_incoming = str_replace("\n|||***", $delimiter."</th><th>", $body_incoming);
	$body_incoming = str_replace("|||***", $delimiter."<table><thead><tr><th>", $body_incoming);
	$body_incoming = str_replace($delimiter."---\n---".$delimiter."***", $delimiter."</th></tr></thead><tbody>\n<tr><td>", $body_incoming);
	$body_incoming = str_replace($delimiter."---\n---", $delimiter."</td></tr></tbody></table>".$delimiter, $body_incoming);
	$body_incoming = str_replace($delimiter."---".$delimiter."***", $delimiter."</td></tr>\n<tr><td>", $body_incoming);
	$body_incoming = str_replace("\n***", $delimiter."</td><td>", $body_incoming);
	$body_incoming = str_replace("<blockquote>", $delimiter."<blockquote>".$delimiter, $body_incoming);
	$body_incoming = str_replace("</blockquote>", $delimiter."</blockquote>".$delimiter, $body_incoming);

	// Add COLSPAN
	$counter_limit = 25;
	foreach ( ["th", "td"] as $tag_temp):
		$colspan_temp = 1;
		$search_temp = "<".$tag_temp." colspan='".$colspan_temp."'>";
		$body_incoming = str_replace("<".$tag_temp.">", $search_temp, $body_incoming);
		while (strpos($body_incoming, $search_temp) !== FALSE):
			$colspan_temp++;
			$replace_temp = "<".$tag_temp." colspan='".$colspan_temp."'>";
			$body_incoming = str_replace($search_temp."***", $replace_temp, $body_incoming);
			$search_temp = $replace_temp;
			if ($colspan_temp >= $counter_limit): break; endif;
			endwhile;
		endforeach;

	// Quotes
	$body_incoming = str_replace("<<<", "<q>", $body_incoming);
	$body_incoming = str_replace(">>>", "</q>", $body_incoming);
	
	// process links first
	$matches = [];
	preg_match_all("/(?<=\+\-\+\-\+)(.*?)(?=\+\-\+\-\+)/is", $body_incoming, $matches);
	if (empty($matches)): $matches = [ [], [] ]; endif;
	$matches = array_unique($matches[0]);
	$list_delimiter = "+++";
//	$counter_temp = 0;
	foreach ($matches as $match_temp):
		$replace_temp = null;
		$indent_position_temp = 0;
	
		$digestion_temp = trim($match_temp);
	
		while (strlen($digestion_temp) > 0):
	
			// So we know if we just began
//			$counter_temp++;
	
			$indent_current_temp = 0;
	
			$order_tag_temp = null;
//			foreach (["ul", "ol",] as $tag_temp):
			if (stripos($digestion_temp, $list_delimiter."ol".$list_delimiter) === 0):
				$digestion_temp = substr($digestion_temp, 5);
				$order_tag_temp = " class='ordered-list'";
				endif;
			if (stripos($digestion_temp, $list_delimiter."ul".$list_delimiter) === 0):
				$digestion_temp = substr($digestion_temp, 5);
				$order_tag_temp = " class='unordered-list'";
				endif;
	
			while (strpos($digestion_temp, $list_delimiter) === 0):
				$digestion_temp = substr($digestion_temp, 3);
				$indent_current_temp++;
				endwhile;
	
			// We are going to want to add in some nested lists
			if ($indent_position_temp < $indent_current_temp):
				while ($indent_position_temp < ($indent_current_temp - 1)):
					$replace_temp .= "<ul><li>";
					$indent_position_temp++;
					endwhile;
				   
				// And the last one will always start a new <ul> so no special conditional
				$replace_temp .= "<ul ".$order_tag_temp.">";
				$indent_position_temp++;

			// We want to close out the books
			elseif ($indent_position_temp > $indent_current_temp):
				while ($indent_position_temp > $indent_current_temp):
					$replace_temp .= "</li></ul></li>";
					$indent_position_temp--;
					endwhile;
				       
				// But if we have our tag, we have to force a new list
				if (!(empty($order_tag_temp))):
					$replace_temp .= "</ul><ul ".$order_tag_temp.">";
					endif;

			// If we are at the same indent level...
			elseif ($indent_position_temp == $indent_current_temp):

				// If we have the tag, we have to close the books... Wait, this is impossible because it'll be from 0 to 1
//				if (empty($counter_temp)):
//					$replace_temp .= "<ul ".$order_tag_temp.">";
				if (!(empty($order_tag_temp))):
					$replace_temp .= "</li></ul><ul ".$order_tag_temp.">";
				elseif (empty($order_tag_temp)):
					$replace_temp .= "</li>";
					endif;
				       
				endif;
				       
				       
			$replace_temp .= "<li>";
	
//			$indent_position_temp = $indent_current_temp;
	
			$next_position_temp = strpos($digestion_temp, '+++');

			if ($next_position_temp === FALSE):
				$add_temp = $digestion_temp;
				$digestion_temp = null;
			else:
				$add_temp = substr($digestion_temp, 0, $next_position_temp);
				$digestion_temp = trim(substr($digestion_temp, $next_position_temp));
				endif;
	
			$replace_temp .= trim($add_temp);

			endwhile;
	
		$indent_position_temp = 0;
		while ($indent_current_temp > $indent_position_temp):
			$replace_temp .= "</li></ul>";
			$indent_position_temp++;
			endwhile;
			
		$body_incoming = str_replace("+-+-+".$match_temp."+-+-+", "<div class='wrapper-list'>".$replace_temp."</div>", $body_incoming);
	
		$digestion_temp = trim($digestion_temp);

		endforeach;
	
	
	// Add delimiter
	$paragraphize_array = [
		"th", "td",
		"ul", "ol", "li",
		"summary", "details",
		"dt", "dd",
		];
	foreach ($paragraphize_array as $tag_temp):
		$body_incoming = preg_replace('/<'.$tag_temp.'(.*?)>/', $delimiter.'<'.$tag_temp.'$1>'.$delimiter, $body_incoming);	
		$body_incoming = str_replace("</".$tag_temp.">", $delimiter."</".$tag_temp.">".$delimiter, $body_incoming);	
		endforeach;

	
	$image_lightbox_array = [];
	
	// process links first
	$matches = [];
	preg_match_all("/(?<=\{\{\{)(.*?)(?=\}\}\})/is", $body_incoming, $matches);
//	preg_match_all("/(?<=\{\{\{)(.+)(?=\}\}\})/is", $body_incoming, $matches); // too greedy
	if (empty($matches)): $matches = [ [], [] ]; endif;
	$matches = array_unique($matches[0]);
	foreach ($matches as $match_temp):

		$link_string = $link_type = null;
	
		$temp_array = explode("}{", $match_temp."}{");
	
		// Set up the array
		$temp_array = sanitize_temp_array($temp_array, 3);

		// If there is nothing, just skip it...
		if ($temp_array === FALSE):
			$body_incoming = str_replace("{{{".$match_temp."}}}", null, $body_incoming);
			continue;
			endif;
	
		$match_lowercase_temp = array_map('strtolower', $temp_array);
		$tag_check = 0;
		foreach ([ "h1", "h2", "h3", "h4", "h6", "h6", "aside", "cite", "cite-tanakh" ] as $tag_temp):
			if (FALSE !== $tag_check = array_search($tag_temp, $match_lowercase_temp)):	
				unset($temp_array[$tag_check]);
				$tag_check = 1;
				break; endif;
			$tag_check = 0;
			endforeach;
	
		// Re-index the array
		$temp_array = array_values($temp_array);	
	
		$link_check = 0;
	
		$link_url = $contents_string = null;
	
		if (filter_var($temp_array[0], FILTER_VALIDATE_URL) !== FALSE):
			$link_check = 1;
			$link_url = $temp_array[0];
			if (!(empty($temp_array[1]))): $contents_string = $temp_array[1];
			else: $contents_string = $temp_array[0]; endif;
			endif;
	
		// If there is no link yet...
		if ($link_check !== 1):
			
			// First check if there is a page result
			if (NULL == $link_info = nesty_page($temp_array[0])):
	
				// If there is no page result, check if there is a media result
				$link_info = nesty_media($temp_array[0]);
				endif;
	
			// If we did get a $link_info just now
			if ($link_info !== NULL):
	
				$link_check = 1;
				$link_id_temp = array_key_first($link_info);
				if (!(empty($temp_array[1]))): $contents_string = $temp_array[1];
				elseif (!(empty($link_info[$link_id_temp]['header']))): $contents_string = $link_info[$link_id_temp]['header'];
				else: $contents_string = "<i class='material-icons'>link</i>"; endif;
				$link_url = $link_info[$link_id_temp]['link'].$anchor_temp;
				endif;
	
			endif;

		if (empty($contents_string)):
			$contents_string = $temp_array[0];
			endif;
	
		// remove all images inside links
		preg_match_all("/(?<=\[\[\[)(.*?)(?=\]\]\])/is", $contents_string, $matches_temp);
		if (empty($matches_temp)): $matches_temp = [ [], [] ]; endif;
		foreach ($matches_temp[0] as $temp): $contents_string = str_replace("[[[".$temp."]]]", null, $contents_string); endforeach;

		// remove all links inside links
		preg_match_all("/(?<=\{\{\{)(.*?)(?=\}\}\})/is", $contents_string, $matches_temp);
		if (empty($matches_temp)): $matches_temp = [ [], [] ]; endif;
		foreach ($matches_temp[0] as $temp): $contents_string = str_replace("[[[".$temp."]]]", null, $contents_string); endforeach;

		// remove all citations inside links
//		preg_match_all("/(?<=\(\(\()(.*?)(?=\)\)\))/is", $contents_string, $matches_temp);
//		if (empty($matches_temp)): $matches_temp = [ [], [] ]; endif;
//		foreach ($matches_temp[0] as $temp): $contents_string = str_replace("(((".$temp.")))", null, $contents_string); endforeach;
		
		// Replace with hyphen that does not break lines
//		$link_string = str_replace("-", "&#8209;", $link_string);

		if ($link_check == 1):
			$contents_string = "<a href='".$link_url."'>".$contents_string."</a>";
			endif;
	
		if ($tag_check == 1):
			if ($tag_temp == "cite-tanakh"):
				$check_string = tanakh_check($contents_string);
				if ($check_string !== FALSE): $contents_string = $check_string; endif;
				$tag_temp = "cite";
				endif;
			$contents_string = "<".$tag_temp.">".$contents_string."</".$tag_temp.">";
			endif;
	
		// If $tag_temp == "cite"
		// Then make an array to add to endnotes
		// 

		$body_incoming = str_replace("{{{".$match_temp."}}}", $contents_string, $body_incoming);
	
		endforeach;
	
	// process media next
	$matches = [];
	preg_match_all("/(?<=\[\[\[)(.*?)(?=\]\]\])/is", $body_incoming, $matches);
	if (empty($matches)): $matches = [ [], [] ]; endif;
	$matches = array_unique($matches[0]);	
	foreach ($matches as $match_temp):

		$image_string = $filename_size = $file_description = null;

		$temp_array = explode("][", $match_temp."][");

		$media_info = nesty_media($temp_array[0]);

		$media_id_temp = $temp_array[0];
		if (strpos($temp_array[0], "|")):
			$domain_id_temp = explode("|", $temp_array[0]);
			if (strpos($domain_id_temp[0], ".")): $media_id_temp = $domain_id_temp[1];
			else: $media_id_temp = $domain_id_temp[0]; endif;
			endif;

		if (empty($media_info[$media_id_temp])):
			$body_incoming = str_replace("[[[".$match_temp."]]]", null, $body_incoming);
			continue; endif; // media id does not exist so skip it
		
		if (in_array($temp_array[1], ["full", "large", "thumb"])): $filename_size = $temp_array[1]; unset($temp_array[1]);
		elseif (in_array($temp_array[2], ["full", "large", "thumb"])): $filename_size = $temp_array[2]; unset($temp_array[2]); endif;

		if (!(empty($temp_array[1]))): $file_description = $temp_array[1];
		elseif (!(empty($temp_array[2]))): $file_description = $temp_array[2];
		elseif (!(empty($media_info[$media_id_temp]['description']))): $file_description = $media_info[$media_id_temp]['description']; endif;
	
		// convert all images to links
		preg_match_all("/(?<=\[\[\[)(.*?)(?=\]\]\])/is", $file_description, $matches_temp);
		if (empty($matches_temp)): $matches_temp = [ [], [] ]; endif;
		foreach ($matches_temp[0] as $temp): $file_description = str_replace("[[[".$temp."]]]", "{{{".str_replace("][", "}{", $temp)."}}}", $file_description); endforeach;

		// remove all citations inside images
		preg_match_all("/(?<=\(\(\()(.*?)(?=\)\)\))/is", $file_description, $matches_temp);
		if (empty($matches_temp)): $matches_temp = [ [], [] ]; endif;
		foreach ($matches_temp[0] as $temp): $file_description = str_replace("(((".$temp.")))", null, $file_description); endforeach;
	
		$file_description = body_process($file_description);
	
		$img_height = 240;
		$img_width = round(240*$media_info[$media_id_temp]['width']/$media_info[$media_id_temp]['height']);
		$img_height_large = round(2.5*$img_height);
		$img_width_large = round(2.5*$img_width);

		if ($filename_size == "full"):
			$image_string = "<a href='".$media_info[$media_id_temp]['link']."' on='tap:lightbox".$media_id_temp."' role='button' tabindex='1'>view image</a>";
	
		elseif ($filename_size == "large"):
			$image_string = "<div class='image_large'>";
			$image_string .= "<figure><amp-img on='tap:lightbox".$media_id_temp."' src='".$media_info[$media_id_temp]['link']."large/' width='".$img_width_large."px' height='".$img_height_large."px' role='button' tabindex='1' sizes='(min-width: 1100px) 1000px, (min-width: 500px) 90vw, 90vw'></amp-img>";
			if (!(empty($file_description))):
				$image_string .= "<amp-fit-text width='".($img_width_large)."px' height='30px' min-font-size='14px' max-font-size='14px'>".mb_substr(strip_tags(str_replace(["</th>", "</td>", "</div>", "</p>", "<br>", "<br />"], ' ',$file_description)),0,200)."</amp-fit-text>";
				endif;
			$image_string .= "</figure>";
			$image_string .= "<a href='".$media_info[$media_id_temp]['link']."' target='_blank'><div class='image-div-link-button material-icons'>link</div></a>";
			$image_string .= "<div on='tap:lightbox".$media_id_temp."' role='button' tabindex='1' class='image-div-open-button'>Tap to open</div>";
			$image_string .= "</div>";

		else:
			$image_string = "<div class='image_thumbnail'>";
			$image_string .= "<figure><amp-img on='tap:lightbox".$media_id_temp."' src='".$media_info[$media_id_temp]['link']."thumb/' width='".$img_width."px' height='".$img_height."px' role='button' tabindex='1' sizes='(min-width: ".($img_width+100)."px) ".$img_width."px, 70vw'></amp-img>";
			$image_string .= "<amp-fit-text width='".($img_width)."px' height='30px' min-font-size='14px' max-font-size='14px' sizes='(min-width: ".($img_width+100)."px) ".($img_width)."px, 70vw'>".mb_substr(strip_tags(str_replace(["</th>", "</td>", "</div>", "</p>", "<br>", "<br />"], ' ', $file_description)),0,200)."</amp-fit-text>";
			$image_string .= "</figure>";
			$image_string .= "<a href='".$media_info[$media_id_temp]['link']."' target='_blank'><div class='image-div-link-button material-icons'>link</div></a>";
			$image_string .= "<div on='tap:lightbox".$media_id_temp."' role='button' tabindex='1' class='image-div-open-button'>Tap to open</div>";
			$image_string .= "</div>"; endif;

	
		$body_incoming = str_replace("[[[".$match_temp."]]]", $image_string, $body_incoming);
	
		$lightbox_temp = "<amp-lightbox scrollable id='lightbox".$media_id_temp."' layout='nodisplay'>";
		$lightbox_temp .= "<figure><div class='image_large' on='tap:lightbox".$media_id_temp.".close' tabindex='1' role='button'><amp-img src='".$media_info[$media_id_temp]['link']."large/' width='".$img_width_large."px' height='".$img_height_large."px' sizes='(min-width: 1100px) 1000px, (min-width: 500px) 90vw, 90vw'></amp-img></div>";
		$lightbox_temp .= "<a href='".$media_info[$media_id_temp]['link']."' target='_blank'><div class='amp-lightbox-image-link-button'>new window</div></a>";
		$lightbox_temp .= "<div class='amp-lightbox-media-id'>".$domain."|".$media_id_temp."</div>";
		$lightbox_temp .= "<figcaption>".$file_description."</figcaption></figure>";
		$lightbox_temp .= "<div class='amp-lightbox-close background_2' on='tap:lightbox".$media_id_temp.".close' tabindex='1' role='button'>close</div>";
		$lightbox_temp .= "</amp-lightbox>";
		$image_lightbox_array[] = $lightbox_temp;

		endforeach;
	
	// process formulas
	$matches = [];
	preg_match_all("/(?<=\\$\\$\\$)(.*?)(?=\\$\\$\\$)/is", $body_incoming, $matches); // Must escape $ twice sa \\$ not just \$
	if (empty($matches)): $matches = [ [], [], ]; endif;
	$matches = $matches[0]; // We will not array_unique because we want to skip all odd (interstitial) content
	foreach ($matches as $key_temp => $match_temp):
		if ( ($key_temp !== 0) && ($key_temp % 2 !== 0) ): continue; endif;
		$link_string = $match_temp;
		$link_string = preg_replace("/\n/", " ", $link_string);
		$link_string = preg_replace("/\n+/", " ", $link_string);
//		$link_string = str_replace("'", "^{\prime}", $link_string);
		$link_string = str_replace("\'", "^{\prime}", $link_string);
		$link_string = str_replace("'", "&#x27;", $link_string);
		$link_string = trim($link_string);
		$link_string = "<amp-mathml inline layout='container' data-formula='\[".$link_string."\]'></amp-mathml>";
		$body_incoming = str_replace("$$$".$match_temp."$$$", $link_string, $body_incoming);
		endforeach;
			
	$skip_array = [
		"<blockquote", "blockquote>", "<iframe", "iframe>", "<div", "div>", "<hr>",  "<hr />", "<aside", "aside>", 
		"<table", "table>", "<thead", "thead>", "<tbody", "tbody>", "<tr", "tr>", "<td", "td>", "<th", "th>", 
		"<h1", "h1>", "<h2", "h2>", "<h3", "h3>", "<h4", "h4>", "<h5", "h5>", "<h6", "h6>", "<hr", 
		"<ul", "ul>", "<ol", "ol>", "<li", "li>", "<section", "section>", 
		"<dt", "dt>", "<dd", "dd>",
		"<summary", "summary>", "<details", "details>",
		"<amp-img", "amp-img>",
		"<amp-fit-text", "amp-fit-text>", "<amp-accordion", "amp-accordion>" ];
	
	$body_temp = explode($delimiter, $body_incoming);
	$body_incoming = $body_final = null;

	foreach($body_temp as $content_temp):
		$content_temp = trim($content_temp);
		if (ctype_space($content_temp)): continue; endif;
		if (empty($content_temp) && ($content_temp !== "0")): continue; endif;
		if (strpos("*".$content_temp, "///") == 1): continue; endif;
		foreach ($skip_array as $skip_temp):
			if (strpos("*".$content_temp, $skip_temp)):
				$body_final .= $content_temp;
				continue 2; endif;
			endforeach;
		$body_final .= "<p>".$content_temp."</p>";
		endforeach;
	
	$body_final .= implode(null, $image_lightbox_array);
	$body_final = str_replace("\n", "<br>", $body_final);
	$body_final = str_replace("><br>", ">", $body_final);
	$body_final = str_replace("</cite><cite>", "; ", $body_final);
	
	// Sanitize some more
	$body_final = str_replace("&#039;", "'", $body_final);
//	$link_string = str_replace("'", $apostrophe_temp, $link_string);
	$body_final = preg_replace('!\s+!', ' ', $body_final);

	return $body_final; }

function tanakh_check($contents_string, $book_given=null, $chapter_given=null, $verse_given = null) {
	$tanakh_array =
		[
		
//		"parashah"			=> 
//			[
//			"name"			=> "פרשת השבוע Parasha",
//			"chapters-verses"	=>
//				[
//				],
			
//			],
		
//		"torah"				=> "תורה Torah",
		
		"genesis" => 
			[
			"name"			=> "בראשית Genesis",
			"chapters-verses"	=>
				[
				"1" => 31,
				"2" => 25,
				"3" => 24,
				"4" => 26,
				"5" => 32,
				"6" => 22,
				"7" => 24,
				"8" => 22,
				"9" => 29,
				"10" => 32,
				"11" => 32,
				"12" => 20,
				"13" => 18,
				"14" => 24,
				"15" => 21,
				"16" => 16,
				"17" => 27,
				"18" => 33,
				"19" => 38,
				"20" => 18,
				"21" => 34,
				"22" => 24,
				"23" => 20,
				"24" => 67,
				"25" => 34,
				"25" => 35,
				"26" => 46,
				"27" => 22,
				"28" => 35,
				"29" => 43,
				"30" => 43,
				"31" => 54,
				"32" => 33,
				"33" => 20,
				"34" => 31,
				"35" => 29,
				"36" => 43,
				"37" => 36,
				"38" => 30,
				"39" => 23,
				"40" => 23,
				"41" => 57,
				"42" => 38,
				"43" => 34,
				"44" => 34,
				"45" => 28,
				"46" => 34,
				"47" => 31,
				"48" => 22,
				"49" => 33,
				"50" => 26,
				], ],

		"exodus" =>
			[
			"name"			=> "שמות Exodus",
			"chapters-verses"	=>
				[
				"1" => null,
				"2" => null,
				"3" => null,
				"4" => null,
				"5" => null,
				"6" => null,
				"7" => null,
				"8" => null,
				"9" => null,
				"10" => null,
				"11" => null,
				"12" => null,
				"13" => null,
				"14" => null,
				"15" => null,
				"16" => null,
				"17" => null,
				"18" => null,
				"19" => null,
				"20" => null,
				"21" => null,
				"22" => null,
				"23" => null,
				"24" => null,
				"25" => null,
				"25" => null,
				"26" => null,
				"27" => null,
				"28" => null,
				"29" => null,
				"30" => null,
				"31" => null,
				"32" => null,
				"33" => null,
				"34" => null,
				"35" => null,
				"36" => null,
				"37" => null,
				"38" => null,
				"39" => null,
				"40" => null,
				], ],

		"leviticus" => 
			[
			"name"			=> "ויקרא Leviticus"
			"chapters-verses"	=>
				[
				"1" => null,
				"2" => null,
				"3" => null,
				"4" => null,
				"5" => null,
				"6" => null,
				"7" => null,
				"8" => null,
				"9" => null,
				"10" => null,
				"11" => null,
				"12" => null,
				"13" => null,
				"14" => null,
				"15" => null,
				"16" => null,
				"17" => null,
				"18" => null,
				"19" => null,
				"20" => null,
				"21" => null,
				"22" => null,
				"23" => null,
				"24" => null,
				"25" => null,
				"25" => null,
				"26" => null,
				"27" => null,
				], ],
		
		"numbers" => 
			[
			"name"			=> "במדבר Numbers",
			"chapters-verses"	=>
				[
				"1" => null,
				"2" => null,
				"3" => null,
				"4" => null,
				"5" => null,
				"6" => null,
				"7" => null,
				"8" => null,
				"9" => null,
				"10" => null,
				"11" => null,
				"12" => null,
				"13" => null,
				"14" => null,
				"15" => null,
				"16" => null,
				"17" => null,
				"18" => null,
				"19" => null,
				"20" => null,
				"21" => null,
				"22" => null,
				"23" => null,
				"24" => null,
				"25" => null,
				"25" => null,
				"26" => null,
				"27" => null,
				"28" => null,
				"29" => null,
				"30" => null,
				"31" => null,
				"32" => null,
				"33" => null,
				"34" => null,
				"35" => null,
				"36" => null,
				], ],

		"deuteronomy" =>
			[
			"name"			=> "דברים Deuteronomy",
			"chapters-verses"	=>
				[
				"1" => null,
				"2" => null,
				"3" => null,
				"4" => null,
				"5" => null,
				"6" => null,
				"7" => null,
				"8" => null,
				"9" => null,
				"10" => null,
				"11" => null,
				"12" => null,
				"13" => null,
				"14" => null,
				"15" => null,
				"16" => null,
				"17" => null,
				"18" => null,
				"19" => null,
				"20" => null,
				"21" => null,
				"22" => null,
				"23" => null,
				"24" => null,
				"25" => null,
				"25" => null,
				"26" => null,
				"27" => null,
				"28" => null,
				"29" => null,
				"30" => null,
				"31" => null,
				"32" => null,
				"33" => null,
				"34" => null,
				], ],

//		"prophets"			=> "נביאים Prophets",
		
		"joshua" =>
			[
			"name"			=> "יהושע Joshua",
			"chapters-verses"	=>
				[
				"1" => null,
				"2" => null,
				"3" => null,
				"4" => null,
				"5" => null,
				"6" => null,
				"7" => null,
				"8" => null,
				"9" => null,
				"10" => null,
				"11" => null,
				"12" => null,
				"13" => null,
				"14" => null,
				"15" => null,
				"16" => null,
				"17" => null,
				"18" => null,
				"19" => null,
				"20" => null,
				"21" => null,
				"22" => null,
				"23" => null,
				"24" => null,
				], ],

		"judges" => 
			[
			"name"			=> "שופטים Judges",
			"chapters-verses"	=>
				[
				"1" => null,
				"2" => null,
				"3" => null,
				"4" => null,
				"5" => null,
				"6" => null,
				"7" => null,
				"8" => null,
				"9" => null,
				"10" => null,
				"11" => null,
				"12" => null,
				"13" => null,
				"14" => null,
				"15" => null,
				"16" => null,
				"17" => null,
				"18" => null,
				"19" => null,
				"20" => null,
				"21" => null,
				], ],
		
		"ruth" => 
			[
			"name"			=> "רות Ruth",
			"chapters-verses"	=>
				[
				"1" => null,
				"2" => null,
				"3" => null,
				"4" => null,
				], ],

		"i samuel" => 
			[
			"name"			=> "שמואל Samuel I",
			"chapters-verses"	=>
				[
				"1" => null,
				"2" => null,
				"3" => null,
				"4" => null,
				"5" => null,
				"6" => null,
				"7" => null,
				"8" => null,
				"9" => null,
				"10" => null,
				"11" => null,
				"12" => null,
				"13" => null,
				"14" => null,
				"15" => null,
				"16" => null,
				"17" => null,
				"18" => null,
				"19" => null,
				"20" => null,
				"21" => null,
				"22" => null,
				"23" => null,
				"24" => null,
				"25" => null,
				"25" => null,
				"26" => null,
				"27" => null,
				"28" => null,
				"29" => null,
				"30" => null,
				"31" => null,
				], ],

		"ii samuel" => 
			[
			"name"			=> "שמואל Samuel II",
			"chapters-verses"	=>
				[
				"1" => null,
				"2" => null,
				"3" => null,
				"4" => null,
				"5" => null,
				"6" => null,
				"7" => null,
				"8" => null,
				"9" => null,
				"10" => null,
				"11" => null,
				"12" => null,
				"13" => null,
				"14" => null,
				"15" => null,
				"16" => null,
				"17" => null,
				"18" => null,
				"19" => null,
				"20" => null,
				"21" => null,
				"22" => null,
				"23" => null,
				"24" => null,
				], ],

		"i kings" => 
			[
			"name"			=> "מלכים Kings I",
			"chapters-verses"	=>
				[
				"1" => null,
				"2" => null,
				"3" => null,
				"4" => null,
				"5" => null,
				"6" => null,
				"7" => null,
				"8" => null,
				"9" => null,
				"10" => null,
				"11" => null,
				"12" => null,
				"13" => null,
				"14" => null,
				"15" => null,
				"16" => null,
				"17" => null,
				"18" => null,
				"19" => null,
				"20" => null,
				"21" => null,
				"22" => null,
				], ],

		"ii kings" => 
			[
			"name"			=> "מלכים Kings II",
			"chapters-verses"	=>
				[
				"1" => null,
				"2" => null,
				"3" => null,
				"4" => null,
				"5" => null,
				"6" => null,
				"7" => null,
				"8" => null,
				"9" => null,
				"10" => null,
				"11" => null,
				"12" => null,
				"13" => null,
				"14" => null,
				"15" => null,
				"16" => null,
				"17" => null,
				"18" => null,
				"19" => null,
				"20" => null,
				"21" => null,
				"22" => null,
				"23" => null,
				"24" => null,
				"25" => null,
				], ],
		
		"isaiah"	=> "ישעיה Isaiah",
		"jeremiah"	=> "ירמיה Jeremiah",
		"ezekiel"	=> "יחזקאל Ezekiel",
		
//		"minor prophets"	=> "תרי עשר The Twelve",
		
		"i chronicles" => 
			[
			"name"			=> "דברי הימים Chronicles I",
			"chapters-verses"	=>
				[
				"1" => null,
				"2" => null,
				"3" => null,
				"4" => null,
				"5" => null,
				"6" => null,
				"7" => null,
				"8" => null,
				"9" => null,
				"10" => null,
				"11" => null,
				"12" => null,
				"13" => null,
				"14" => null,
				"15" => null,
				"16" => null,
				"17" => null,
				"18" => null,
				"19" => null,
				"20" => null,
				"21" => null,
				"22" => null,
				"23" => null,
				"24" => null,
				"25" => null,
				"26" => null,
				"27" => null,
				"28" => null,
				"29" => null,
				], ],
		
		"ii chronicles" =>
			[
			"name"			=> "דברי הימים Chronicles II",
			"chapters-verses"	=>
				[
				"1" => null,
				"2" => null,
				"3" => null,
				"4" => null,
				"5" => null,
				"6" => null,
				"7" => null,
				"8" => null,
				"9" => null,
				"10" => null,
				"11" => null,
				"12" => null,
				"13" => null,
				"14" => null,
				"15" => null,
				"16" => null,
				"17" => null,
				"18" => null,
				"19" => null,
				"20" => null,
				"21" => null,
				"22" => null,
				"23" => null,
				"24" => null,
				"25" => null,
				"26" => null,
				"27" => null,
				"28" => null,
				"29" => null,
				"30" => null,
				"31" => null,
				"32" => null,
				"33" => null,
				"34" => null,
				"35" => null,
				"36" => null,
				], ],
		
		"ezra" => 
			[
			"name"			=> "עזרא Ezra ",
			"chapters-verses"	=>
				[
				"1" => null,
				"2" => null,
				"3" => null,
				"4" => null,
				"5" => null,
				"6" => null,
				"7" => null,
				"8" => null,
				"9" => null,
				"10" => null,
				], ],		
		
		
		"nehemiah"	=> 
			[
			"name"			=> "נחמיה Nechemiah",
			"chapters-verses"	=>
				[
				"1" => null,
				"2" => null,
				"3" => null,
				"4" => null,
				"5" => null,
				"6" => null,
				"7" => null,
				"8" => null,
				"9" => null,
				"10" => null,
				"11" => null,
				"12" => null,
				"13" => null,
				], ],

		"esther" =>
			[
			"name"			=>  "אסתר Esther",
			"chapters-verses"	=>
				[
				"1" => null,
				"2" => null,
				"3" => null,
				"4" => null,
				"5" => null,
				"6" => null,
				"7" => null,
				"8" => null,
				"9" => null,
				"10" => null,
				], ],	
		
//		"writings"	=> "כתובים Writings",
		
		];

	$return_string = null;
	
	$check_string = strtolower($contents_string);
	$check_string = str_replace("1 ", "i", $check_string);
	$check_string = str_replace("2 ", "ii", $check_string);
	$check_string = trim($check_string);
	
	$book_found = null;
	
	foreach ($tanakh_array as $tanakh_key => $tanakh_info):
		if (strpos($check_string, substr($tanakh_key, 0, 3)) !== FALSE):
			$book_found => $tanakh_key;
			break; endif;
		$tanakh_info = null;
		endforeach;

	if (empty($book_found) && !(empty($book_given)) && isset($tanakh_array[$book_given])):
		$book_found = $book_given;
		$tanakh_info = $tanakh_array[$book_given];
		endif;
	
	if (empty($book_found)):
		return FALSE;
		endif;
	
	// Remove book name
	$check_string = substr($check_string, 2)
	$check_array = explode(" ", $check_string);
	unset($check_array[0]); // Remove the book name
	foreach ($check_array as $key_temp => $value_temp):
		if (!(empty($value_temp)): continue; endif;
		unset($check_array[$key_temp]);
		endforeach;
	$check_string = trim(implode(" ", $check_array));

	foreach(["-", ",", ] as $punctuation_temp):
		if (strpos($check_string, "-") === 0):
			$check_string = tanakh_check(substr($check_string, 1), $book_found);
		    	endif;
		if ($check_string !== FALSE):
			$return_string = $return_string.$punctuation_temp." ".$check_string;
		    	endif;
		endforeach;
		    
	$chapter_found = null;

	foreach(array_reverse($tanakh_info['chapters-verses']) as $chapter_number => $verse_count):
		if (strpos($check_string, $chapter_number) !== 0): continue; endif;
		$chapter_found = $chapter_number;
		endforeach;

	if (empty($chapter_found) && !(empty($chapter_given)) && isset($tanakh_info['chapters-verses'][$chapter_given])):
		$chapter_found = $chapter_given;
		endif;

//	if (empty($chapter_found) && ($book_found == $book_given)):
//		return FALSE;
//		endif;

	if (empty($chapter_found) && ($book_found !== $book_given)):
		$return_string = $tanakh_info['name']." ".$return_string;
		return $return_string;
		endif;
		    
	foreach(["-", ",", ] as $punctuation_temp):
		if (strpos($check_string, "-") === 0):
			$check_string = tanakh_check(substr($check_string, 1), $book_found, $chapter_found);
		    	endif;
		if ($check_string !== FALSE):
			$return_string = $return_string.$punctuation_temp." ".$check_string;
		    	endif;
		endforeach;

	if (strpos($check_string, ":") !== 0):
		$return_string = $tanakh_info['name']." ".$chapter_found." ".$return_string;
		return $return_string;
		endif;
		    
	$verse_found = null;

	$verse_temp = 1;
	while($verse_temp <= $tanakh_info['chapters-verses'][$chapter_found]):
		if (strpos($check_string, $chapter_number.":".$verse_temp) !== 0): continue; endif;
		$verse_found = $verse_temp;
		endwhile;
		    
	if (empty($verse_found) && !(empty($verse_given)) && ($verse_given <= $tanakh_info['chapters-verses'][$chapter_found])):
		$verse_found = $verse_given;
		endif;
		    
	if (!(empty($verse_found))):
		$return_string = $tanakh_info['name']." ".$chapter_found.": ".$verse_found.$return_string;
		return $return_string;
		endif;
	
	$return_string = $tanakh_info['name']." ".$chapter_found.$return_string;
	return $return_string;

	}
 
function clip_length($content=null,$length=140,$ellipsis=null,$breaks=null) {
	if ($breaks == null): $content = str_replace(array("\r", "\n", "\r\n", "\v", "\t", "\0","\x"), " ", $content);
	else: $content = str_replace(array("\r\r", "\n\n", "\r\n"), "\r", $content); endif;
	$clip_length = mb_substr($content,0,$length,"utf-8");	
	if (strlen($clip_length) >= ($length-1) && (strrpos($clip_length, ' ') !== FALSE)): $clip_length = mb_substr($clip_length,0,strrpos($clip_length, ' ')); endif;
	if ( ($ellipsis == "ellipsis") && (strlen($content) >= ($length-1)) ): $clip_length .= "…"; endif;
	return $clip_length; }
  

function number_condense($n, $decimals=1) {
	$negative = null;
	if ($n < 0): $n = abs($n); $negative = "-"; endif;
	if (($n == 0) || ($n == null)): $n_format = "0"; $suffix_temp = null;
	elseif ($n < 1): $n_format = "0"; $suffix_temp = null;
	elseif ($n < 1000): $n_format = number_format($n); $suffix_temp = null;
	elseif ($n < 1000000): $n_format = number_format($n / 1000, $decimals); $suffix_temp = "k";
	elseif ($n < 1000000000): $n_format = number_format($n / 1000000, $decimals); $suffix_temp = "m";
	else: $n_format = number_format($n / 1000000000, $decimals); $suffix_temp = "b"; endif;
	if (strlen($n_format) - strripos($n_format,".0") == 2): $n_format = str_replace(".0", null, $n_format); endif;
	return $negative.$n_format.$suffix_temp; }

function json_output ($json_array) {
	
	global $domain;
	
	header("Content-type: application/json");
	header("Access-Control-Allow-Credentials: true");
	header("Access-Control-Allow-Origin: https://".$domain);
	header("AMP-Access-Control-Allow-Source-Origin: https://".$domain);
	header("Access-Control-Expose-Headers: AMP-Access-Control-Allow-Source-Origin");

	echo json_encode($json_array);
	       
	exit; }

function sanitize_temp_array($temp_array, $count) {
	$count_temp = $content_check = 0;
	while ($count_temp < $count):
		if (empty($temp_array[$count_temp])):
			$temp_array[$count_temp] = null;
		else:
			$temp_array[$count_temp] = trim($temp_array[$count_temp]);
			$content_check = 1;
			endif;
		$count_temp++;
		endwhile;
	if ($content_check !== 1): return FALSE; endif;
	return $temp_array; }

function json_status ($status, $message=null, $redirect=null) {
	
	global $domain;
	
	header("Content-type: application/json");
	header("Access-Control-Allow-Credentials: true");
	header("Access-Control-Allow-Origin: https://".$domain);
	header("AMP-Access-Control-Allow-Source-Origin: https://".$domain);
		
	if (($status !== "success") || empty($message)):
	       $status = "error";
	       header("HTTP/1.0 412 Precondition Failed", true, 412);
	else:
	       header("Access-Control-Expose-Headers: AMP-Access-Control-Allow-Source-Origin");
	       endif;
	
	if (($status == "success") && !(empty($redirect))):
		header("AMP-Redirect-To: https://".$domain.$redirect);
		header("Access-Control-Expose-Headers: AMP-Redirect-To, AMP-Access-Control-Allow-Source-Origin");
		endif;
	
	echo json_encode(["status"=>$status, "message"=>trim($message)]);
	       
	exit; }

?>
