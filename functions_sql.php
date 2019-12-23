<? // sql_setup, fetchall, temporary_table, execute_checkup

function sql_setup($fields, $table) {
	foreach ((array)$fields as $column => $value):
		if (is_array($value)): $value = serialize($value); endif;
		$columns_temp[] = "`$column`";
		$updates_temp[] = "`$column`=VALUES(`$column`)";
		$bind_inputs[] = ":$column"; endforeach;
	$sql_setup = "INSERT INTO $table (".implode(", ", $columns_temp).") VALUES (".implode(", ", $bind_inputs).") ON DUPLICATE KEY UPDATE ".implode(", ", $updates_temp);
	return $sql_setup; }


function fetchall($sql_temp,$values_temp=null) {
	global $connection_pdo;
	$retrieve_statement = $connection_pdo->prepare($sql_temp); 
	$retrieve_statement->execute($values_temp); 
	$fetchall = $retrieve_statement->fetchAll();
	return $fetchall; }


function temporary_table($column_name,$array) {
	global $connection_pdo;
	$table_name = "temp_".random_code(5);
	$sql_temp = "CREATE TEMPORARY TABLE $table_name (`$column_name` VARCHAR(100), PRIMARY KEY (`$column_name`)) DEFAULT CHARSET=utf8mb4;";
	$create_statement = $connection_pdo->prepare($sql_temp);
	$create_statement->execute();
	execute_checkup($create_statement->errorInfo(), "creating temporary table $table_name");
	$sql_temp = "INSERT IGNORE INTO $table_name (`$column_name`) VALUES (:value)";
	$insert_statement = $connection_pdo->prepare($sql_temp);
	foreach ($array as $value):
		$insert_statement->execute(["value"=>$value]);
		execute_checkup($create_statement->errorInfo(), "adding data to temporary table $table_name");
		endforeach;
	return $table_name; }
  

function execute_checkup ($error_info) {
	if ($error_info[0] == "0000"): return "success";
	else: return $error_info[2]; endif; } ?>
