<? $order_array = $information_array = [];

$order_language = null;
if (isset($_REQUEST['order'])):
	$order_language = $_REQUEST['order'];
	unset($_REQUEST['order']); endif;

foreach ($_REQUEST as $appendix_key => $appendix_value):
	if (empty($appendix_value)): unset($_REQUEST[$appendix_key]); endif;
	if (strpos("#".$appendix_value, ",") && ($appendix_key !== "search")): $appendix_value = explode(",", $appendix_value); endif;
	if (!(is_array($appendix_value))): $appendix_value = [ $appendix_value ]; endif;
	foreach ($appendix_value as $key_temp => $value_temp): $appendix_value[$key_temp] = trim($value_temp); endforeach;
	$_REQUEST[$appendix_key] = $appendix_value;
	endforeach;

$sql_temp = "SELECT * FROM " . $database . ".information_directory";
foreach($connection_pdo->query($sql_temp) as $row):

	// requesting a specific entry id takes precedence over everything
	if (isset($_REQUEST['entry_id']) && !(in_array($row['entry_id'], $_REQUEST['entry_id']))): continue; endif;

	$row['type'] = str_replace('"', null, $row['type']);

	if (isset($_REQUEST['type']) && !(in_array($row['type'], $_REQUEST['type']))): continue; endif;

	if (isset($_REQUEST['search'])):
		$result_temp = 0;
		foreach ($_REQUEST['search'] as $search_temp):
			$blob_temp = "*".strtolower(implode(" ", $row));
			$search_temp = strtolower($search_temp);
			if (strpos($blob_temp, $search_temp)): $result_temp = 1; break; endif;
			endforeach;
		if ($result_temp == 0): continue; endif;
		endif;
		
	$appendix_temp = json_decode($row['appendix'], true);
	foreach ($_REQUEST as $appendix_key => $appendix_value):
		if (in_array($appendix_key, [ "entry_id", "type", "search", "summary" ])): continue; endif;
		if (!(isset($appendix_temp[$appendix_key]))): continue 2; endif;
		if (!(array_intersect($appendix_temp[$appendix_key], $appendix_value))): continue 2; endif;
		endforeach;

	$information_array[$row['entry_id']] = [
		"entry_id" => $row['entry_id'],
		"link" => "https://".$domain."/".$row['entry_id']."/",
		"type" => $row['type'],
		"name" => json_decode($row['name'], true),
		"alternate_name" => json_decode($row['alternate_name'], true),
		"summary" => [],
		"appendix" => $appendix_temp,
		"parents" => [],
		"children" => [] ];

	if (isset($_REQUEST['summary']) && ($_REQUEST['summary'] == ["true"])):
		$summary_temp = json_decode($row['summary'], true);
		foreach ($summary_temp as $language_temp => $content_temp):
			$information_array[$row['entry_id']]['summary'][$language_temp] = body_process($content_temp);
			endforeach;
		endif;

	$information_array[$row['entry_id']]['header'] = implode(" • ", $information_array[$row['entry_id']]['name']);

	$order_array[$row['entry_id']] = null;
	if (isset($order_language) && isset($information_array[$row['entry_id']]['name'][$order_language])): $order_array[$row['entry_id']] = $information_array[$row['entry_id']]['name'][$order_language];
	elseif (isset($information_array[$row['entry_id']]['name'])): $order_array[$row['entry_id']] = reset($information_array[$row['entry_id']]['name']); endif;

	endforeach;

if (!(empty($information_array))):
	$sql_temp = "SELECT * FROM " . $database . ".information_paths";
	foreach($connection_pdo->query($sql_temp) as $row):
		if ($row['parent_id'] == $row['child_id']): continue; endif;
		if ($row['path_type'] == "parent_id"):
			$row['path_type'] = "hierarchy";
			$temp = $row['child_id'];
			$row['child_id'] = $row['parent_id'];
			$row['parent_id'] = $temp; endif;
		if (array_key_exists($row['parent_id'], $information_array)):
			if (empty($information_array[$row['parent_id']]['children'][$row['path_type']])): $information_array[$row['parent_id']]['children'][$row['path_type']] = []; endif;
			$information_array[$row['parent_id']]['children'][$row['path_type']][] = $row['child_id']; endif;
		if (array_key_exists($row['child_id'], $information_array)):
			if (empty($information_array[$row['child_id']]['parents'][$row['path_type']])): $information_array[$row['child_id']]['parents'][$row['path_type']] = []; endif;
			$information_array[$row['child_id']]['parents'][$row['path_type']][] = $row['parent_id']; endif;
		endforeach;
	endif;

if (!(empty($order_array))):
	asort($order_array);
	// we must put null values at the end
	foreach($order_array as $key_temp => $value_temp):
		if (!(empty($value_temp))): continue; endif;
		unset($order_array[$key_temp]); // remove it from array
		$order_array[$key_temp] = null; // append it to the end
		endforeach;
	$information_array = array_merge($order_array, $information_array); endif;

$information_array = htmlspecialchars_array($information_array);

function htmlspecialchars_array($array_temp) {
	if (!(is_array($array_temp))): return html_entity_decode($array_temp); endif;
	foreach ($array_temp as $key_temp => $value_temp): $array_temp[$key_temp] = htmlspecialchars_array($value_temp); endforeach;
	return $array_temp; }

echo json_encode($information_array); ?>
