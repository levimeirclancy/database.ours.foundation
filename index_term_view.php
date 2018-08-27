<? $terms_array = get_terms();

if (empty($terms_array)): echo "empty"; footer(); endif;

$information_array = [];
echo "<table><thead><tr><th>term</th><th>person</th><th>position</th><th>for</th><th>party</th><th>start</th><th>end</th><th>vote</th></tr></thead><tbody>";
foreach($terms_array as $term_id => $term_info):
	$get_array = null;
	$possibilities_temp = ["person_id", "position_id", "for", "party_id", "start_event", "end_event"];
	foreach ($possibilities_temp as $value): if (($value !== "active") && empty($information_array[$term_info[$value]])): $get_array[] = $term_info[$value]; endif; endforeach;
	$get_array = array_unique($get_array);
	if (!(empty($get_array))):
		$information_array_temp = get_entries(["entry_id"=>$get_array]);
		$information_array = array_merge($information_array, $information_array_temp); endif;
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
echo "</tbody></table>"; ?>