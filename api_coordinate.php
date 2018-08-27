<? $latitude = $_REQUEST['latitude'];
$longitude = $_REQUEST['longitude'];

function distance($lat1, $lon1, $lat2, $lon2, $unit="K") {
	// http://www.geodatasource.com/developers/php
	$theta = $lon1 - $lon2;
	$dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
	$dist = acos($dist);
	$dist = rad2deg($dist);
	$miles = $dist * 60 * 1.1515;
	$unit = strtoupper($unit);

	if ($unit == "K"): return ($miles * 1.609344);
	elseif ($unit == "N"): return ($miles * 0.8684);
	else: return $miles; endif; }

// add a thing to limit possibilities based on time

$results_array = [
	"location" => [],
	"nearby" => [] ];

//
// get nearest locations
//

$results_array_nearby_temp = $places_array = [];
$sql_temp = "SELECT * FROM " . $database . ".information_directory WHERE appendix IS NOT NULL";
foreach($connection_pdo->query($sql_temp) as $row):
	if (empty($row['appendix'])): continue; endif;
	$appendix_temp = json_decode($row['appendix'], true);
	if (empty($appendix_temp['latitude']) || empty($appendix_temp['longitude'])): continue; endif;
	$distance_temp = distance($appendix_temp['latitude'], $appendix_temp['longitude'], $latitude, $longitude);
	$results_array_nearby_temp[$row['entry_id']] = [
		"entry_id" => $row['entry_id'],
		"link" => "https://".$domain."/".$row['entry_id']."/",
		"type" => $row['type'],
		"name" => json_decode($row['name'], true),
		"alternate_name" => json_decode($row['alternate_name'], true),
		"summary" => [],
//		"body" => json_decode($row['body'], true),
//		"studies" => $row['studies'],
		"appendix" => json_decode($row['appendix'], true),
		"parents" => [],
		"children" => [],
		"distance" => $distance_temp
		];
	if (isset($_REQUEST['summary']) && ($_REQUEST['summary'] == "true")):
		$summary_temp = json_decode($row['summary'], true);
		foreach ($summary_temp as $language_temp => $content_temp):
			$results_array_nearby_temp[$row['entry_id']]['summary'][$language_temp] = body_process($content_temp);
			endforeach;
		endif;
	$places_array[$row['entry_id']] = $distance_temp;
	endforeach;

if (!(empty($places_array))):
	asort($places_array);
	$count_temp = 0;
	foreach ($places_array as $entry_id => $distance_temp):
		if ($count_temp >= 15): break; endif;
		$results_array['nearby'][$entry_id] = $results_array_nearby_temp[$entry_id];
		$count_temp++;
		endforeach;
	endif;

unset($results_array_nearby_temp);

//
// get what location it is in
//

// get any shape where its latitude spans both sides of the coordinate
$sql_temp = "SELECT entry_id, MAX(start_latitude) AS max_latitude, MIN(start_latitude) AS min_latitude FROM ".$database.".locations_shapes GROUP BY entry_id";
$sql_temp = "SELECT a.entry_id FROM ($sql_temp) a WHERE ($latitude BETWEEN a.min_latitude AND a.max_latitude)";
foreach($connection_pdo->query($sql_temp) as $row):
	$possible_latitude[] = $row['entry_id']; endforeach;

// get any shape where its longitude spans both sides of the coordinate
$sql_temp = "SELECT entry_id, MAX(start_longitude) AS max_longitude, MIN(start_longitude) AS min_longitude FROM ".$database.".locations_shapes GROUP BY entry_id";
$sql_temp = "SELECT a.entry_id FROM ($sql_temp) a WHERE ($longitude BETWEEN a.min_longitude AND a.max_longitude)";
foreach($connection_pdo->query($sql_temp) as $row):
	$possible_longitude[] = $row['entry_id']; endforeach;

// intersect these two lists of shapes
$possible_entries = array_intersect($possible_latitude, $possible_longitude);

// get the number of lines that intersect for these shapes for a ray extended out latitudinally
$sql_temp = "SELECT * FROM ".$database.".locations_shapes ";
$sql_temp .= "WHERE entry_id IN ('".implode("','", $possible_entries)."') ";
$sql_temp .= "AND ( ($latitude BETWEEN start_latitude AND end_latitude) OR ($latitude BETWEEN end_latitude AND start_latitude) )";
foreach($connection_pdo->query($sql_temp) as $row):
	if ($row['start_longitude'] <= $longitude): $side_temp = "west"; else: $side_temp = "east"; endif;
	if (empty($intersections_counts[$row['entry_id']])): $intersections_counts[$row['entry_id']] = ["west"=>null, "east"=>null ]; endif;
	if (empty($intersections_coordinates[$row['entry_id']])): $intersections_coordinates[$row['entry_id']] = []; endif;
	$intersections_counts[$row['entry_id']][$side_temp]++;
	$intersections_coordinates[$row['entry_id']][] = $row;
	endforeach;

// get only those shapes where that ray intersects an odd number of lines
foreach ($intersections_counts as $subdistrict_id=>$intersection_array):
	if (empty($check_array[$subdistrict_id])):
		$check_array[$subdistrict_id] = [];
		endif;
	if ( (!(empty($intersection_array['west'])) || ($intersection_array['west'] % 2 !== 0)) && (!(empty($intersection_array['east'])) || ($intersection_array['east'] % 2 !== 0))):
		$subdistricts_array[] = $subdistrict_id;
		endif; endforeach;

if (!(empty($subdistricts_array))):

	$subdistricts_array = array_unique($subdistricts_array);

	// if there are multiple results, e.g. a shape inside a shape, then choose the one with the least intersects
	foreach ($subdistricts_array as $subdistrict_id):
		$intersection_array = $intersections_counts[$subdistrict_id];
		$counts_array[$subdistrict_id] = $intersection_array['west'] + $intersection_array['east'];
		endforeach;
	asort($counts_array);
	$counts_array = array_keys($counts_array);
	$subdistrict_id = $counts_array[0];

	$sql_temp = "SELECT * FROM " . $database . ".information_directory WHERE entry_id IN ('".implode("', '", $subdistricts_array)."')";
	foreach($connection_pdo->query($sql_temp) as $row):
		$results_array['location'][$row['entry_id']] = [
			"entry_id" => $row['entry_id'],
			"link" => "https://".$domain."/".$row['entry_id']."/",
			"type" => $row['type'],
			"name" => json_decode($row['name'], true),
			"alternate_name" => json_decode($row['alternate_name'], true),
			"summary" => [],
//			"body" => json_decode($row['body'], true),
//			"studies" => $row['studies'],
			"appendix" => json_decode($row['appendix'], true),
			"parents" => [],
			"children" => [] 
			];
		if (isset($_REQUEST['summary']) && ($_REQUEST['summary'] == "true")):
			$summary_temp = json_decode($row['summary'], true);
			foreach ($summary_temp as $language_temp => $content_temp):
				$results_array_nearby_temp[$row['entry_id']]['summary'][$language_temp] = body_process($content_temp);
				endforeach;
			endif;
		endforeach;
	endif;

$sql_temp = "SELECT * FROM " . $database . ".information_paths";
foreach($connection_pdo->query($sql_temp) as $row):
	if ($row['path_type'] == "parent_id"):
		$row['path_type'] = "hierarchy";
		$temp = $row['child_id'];
		$row['child_id'] = $row['parent_id'];
		$row['parent_id'] = $temp; endif;
	if (array_key_exists($row['parent_id'], $results_array['location'])):
		if (empty($results_array['location'][$row['parent_id']]['children'][$row['path_type']])): $results_array['location'][$row['parent_id']]['children'][$row['path_type']] = []; endif;
		$results_array['location'][$row['parent_id']]['children'][$row['path_type']][] = $row['child_id']; endif;
	if (array_key_exists($row['child_id'], $results_array['location'])):
		if (empty($results_array['location'][$row['child_id']]['parents'][$row['path_type']])): $results_array['location'][$row['child_id']]['parents'][$row['path_type']] = []; endif;
		$results_array['location'][$row['child_id']]['parents'][$row['path_type']][] = $row['parent_id']; endif;
	if (array_key_exists($row['parent_id'], $results_array['nearby'])):
		if (empty($results_array['nearby'][$row['parent_id']]['children'][$row['path_type']])): $results_array['nearby'][$row['parent_id']]['children'][$row['path_type']] = []; endif;
		$results_array['nearby'][$row['parent_id']]['children'][$row['path_type']][] = $row['child_id']; endif;
	if (array_key_exists($row['child_id'], $results_array['nearby'])):
		if (empty($results_array['nearby'][$row['child_id']]['parents'][$row['path_type']])): $results_array['nearby'][$row['child_id']]['parents'][$row['path_type']] = []; endif;
		$results_array['nearby'][$row['child_id']]['parents'][$row['path_type']][] = $row['parent_id']; endif;
	endforeach;

foreach ($results_array as $category_temp => $category_results):
	foreach ($category_results as $entry_id => $entry_info):
		if (empty($entry_info['summary'])): continue; endif;
		foreach ($entry_info['summary'] as $language_temp => $summary_temp):
			$results_array[$category_temp][$entry_id]['summary'][$language_temp] = body_process($summary_temp);
			endforeach;
		endforeach;
	endforeach;

echo json_encode($results_array);
exit;




// other methods....

// if there are multiple results, e.g. a shape inside a shape, then we must choose the closest one
foreach($subdistricts_array as $subdistrict_id):
	$subdistricts_ranges[$subdistrict_id] = $subdistricts_min[$subdistrict_id] = $subdistricts_max[$subdistrict_id] = [];
	foreach($intersections_coordinates[$subdistrict_id] as $intersection_temp):
		$subdistricts_ranges[$subdistrict_id][] = $range_distance[] = $intersection_temp['start_latitude'] - $latitude;
		endforeach; endforeach;
$min_distance = min($range_distance);
$max_distance = max($range_distance);
$subdistricts_array = null;
foreach($subdistricts_ranges as $subdistrict_id => $range_array):
	if (min($range_array) == $min_distance): $subdistricts_array[] = $subdistrict_id; endif; endforeach;
	
if (count($subdistricts_array) == 1):
	echo $subdistricts_array[0];
	exit; endif;

$subdistricts_array = null;	
foreach($subdistricts_ranges as $subdistrict_id => $range_array):
	if (max($range_array) !== $max_distance): $subdistricts_array[] = $subdistrict_id; endif; endforeach;

if (count($subdistricts_array) == 1):
	echo $subdistricts_array[0];
	exit; endif;

echo "error"; ?>
