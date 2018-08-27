<? if (!(empty($_POST['save_changes']))):
	$event_id = $_POST['event_id'];
	$values_temp = [
		"event_id"=>$event_id,
		"date"=>$_POST['event_info']['date'] ];
	$sql_temp = sql_setup($values_temp, "nawend_center.events_directory");
	$events_directory_statement = $connection_pdo->prepare($sql_temp);
	$events_directory_statement->execute($values_temp);
	execute_checkup($events_directory_statement->errorInfo(), "entering event into events_directory");
	include_once('admin_save.php'); endif;

$events_array = get_events(["event_id"=>$event_id]);
$entry_info = $events_array[$event_id];

echo "<style> form { text-align: center; } </style>";
echo "<style> div textarea { width: 23%; margin: 5px; padding: 5px; border: 1px solid #333; } </style>";

echo "<form action='?event_id=$event_id' method='post'>";
echo "<input type='hidden' name='entry_id' value='$event_id'>";
echo "<input type='hidden' name='event_id' value='$event_id'>";
echo "<input type='hidden' name='type' value='event'>";

name_short_picture($entry_info);

echo "<h6>date</h6>";
$value_temp = null; if (!(empty($entry_info['date']))): $value_temp = $entry_info['date']; endif;
echo "<input type='date' name='event_info[date]' value='$value_temp' required>";

$information_array = get_entries(["type"=>["position", "unit"]]);
message_one_two_three($entry_info, $information_array);

include_once('admin_edit.php');

echo "<textarea name='change_note' style='display: inline-block; width: 400px; height: 100px; margin: 10px;' placeholder='change notes'></textarea>";

echo "<br><input type='submit' name='save_changes' value='save' style='display: inline-block;'>";

echo "</form>"; ?>