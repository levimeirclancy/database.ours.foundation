<? $events_array = get_events();

if (empty($events_array)): echo "empty"; footer(); endif;

echo "<table><thead><tr><th>event</th><th>date</th><th>name (sorani)</th><th>name (badini)</th><th>name (english)</th><th>name (arabic)</th></tr></thead><tbody>";
foreach($events_array as $event_id => $event_info):
	echo "<tr><td><a href='?event_id=$event_id'>$event_id</a></td>";
	echo "<td>".$event_info['date']."</td>";
	echo "<td>".$event_info['name_sorani'][0]."</td>";
	echo "<td>".$event_info['name_badini'][0]."</td>";
	echo "<td>".$event_info['name_english'][0]."</td>";
	echo "<td>".$event_info['name_arabic'][0]."</td></tr>";
	endforeach;
echo "</tbody></table>"; ?>