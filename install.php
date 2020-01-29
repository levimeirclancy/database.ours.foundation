<pre>

<h1>Setting up database schema</h1>

<? include_once('config.php');

function execute_checkup ($error_info, $message) {
	if ($error_info[0] == "0000"): echo "succcess ".$message;
	else: echo "failure ".$message."<br>".$error_info[2]; exit; endif; }

// make connection without database
$connection_pdo = new PDO(
	"mysql:host=$server;charset=utf8mb4", 
	$username, 
	$password,
//	array(
//		PDO::ATTR_TIMEOUT => 3, // in seconds
//		PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
//		)
	);


// create database
$sql_temp = "CREATE DATABASE IF NOT EXISTS $database CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;";
$run_statement = $connection_pdo->prepare($sql_temp);
$run_statement->execute();
execute_checkup($run_statement->errorInfo(), "creating database $database");

// make connection with database now that it certainly exists
$connection_pdo = new PDO(
	"mysql:host=$server;dbname=$database;charset=utf8mb4", 
	$username, 
	$password,
	array(
		PDO::ATTR_TIMEOUT => 3, // in seconds
		PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
		)
	);

// create users table
$sql_temp = "CREATE TABLE IF NOT EXISTS $database.users (`user_id` VARCHAR(100), `status` VARCHAR(100), `email` VARCHAR(100), `name` VARCHAR(100), `hash` VARCHAR(400), `authenticator` VARCHAR(100), `cookie` VARCHAR(100),  timestamp TIMESTAMP, PRIMARY KEY (`user_id`)) DEFAULT CHARSET=utf8mb4;";
$run_statement = $connection_pdo->prepare($sql_temp);
$run_statement->execute();
execute_checkup($run_statement->errorInfo(), "creating users table");

if (!(empty($_POST['submit']))):
	if (empty($_POST['email']) || empty($_POST['password1'])):
		echo "<br><i style='color: red;'>user information incomplete</i><br>";
	elseif ($_POST['password1'] == $_POST['password2']):
		$values_temp = [
			"user_id"=>random_code(10),
			"email"=>$_POST['email'],
			"hash"=>sha1($_POST['email'].$_POST['password1']) ];
		$sql_temp = sql_setup($values_temp, "$database.users");
		$run_statement = $connection_pdo->prepare($sql_temp);
		$run_statement->execute($values_temp);
		execute_checkup($run_statement->errorInfo(), "creating account login");
	else:
		echo "<br><i style='color: red;'>passwords did not match</i><br>";
		endif;
	endif;

// create locations shapes table
$sql_temp = "CREATE TABLE IF NOT EXISTS $database.locations_shapes (`line_id` VARCHAR(10), `shape_id` VARCHAR(10), `entry_id` VARCHAR(10), `start_latitude` DECIMAL(16,13), `start_longitude` DECIMAL(16,13), `end_latitude` DECIMAL(16,13), `end_longitude` DECIMAL(16,13), `timestamp` TIMESTAMP, PRIMARY KEY (`line_id`)) DEFAULT CHARSET=utf8mb4";
$run_statement = $connection_pdo->prepare($sql_temp);
$run_statement->execute();
execute_checkup($run_statement->errorInfo(), "creating locations_shapes table");

// create entries directory table
$columns_temp = [
	"`entry_id` VARCHAR(10)",
	"`type` VARCHAR(20)",
	"`name` VARCHAR(500)",
	"`alternate_name` VARCHAR(500)",
	"`summary` TEXT",
	"`body` TEXT",
	"`studies` TEXT",
	"`appendix` TEXT",
	"`timestamp` TIMESTAMP",
	"PRIMARY KEY (`entry_id`)" ];
$sql_temp = "CREATE TABLE IF NOT EXISTS $database.information_directory (".implode(", ", $columns_temp).") DEFAULT CHARSET=utf8mb4";
$run_statement = $connection_pdo->prepare($sql_temp);
$run_statement->execute();
execute_checkup($run_statement->errorInfo(), "creating information_directory table");

// create paths table
$sql_temp = "CREATE TABLE IF NOT EXISTS $database.information_paths (`path_id` VARCHAR(100), `parent_id` VARCHAR(100), `path_type` VARCHAR(100), `child_id` VARCHAR(100), timestamp TIMESTAMP, PRIMARY KEY (`path_id`)) DEFAULT CHARSET=utf8mb4;";
$run_statement = $connection_pdo->prepare($sql_temp);
$run_statement->execute();
execute_checkup($run_statement->errorInfo(), "creating information_paths table");
			
// create archived information table
$sql_temp = "CREATE TABLE IF NOT EXISTS $database.information_history (`information_id` VARCHAR(10), `entry_id` VARCHAR(10), `information_name` VARCHAR(100), `information_value` TEXT, `timestamp` TIMESTAMP, PRIMARY KEY (`information_id`)) DEFAULT CHARSET=utf8mb4";
$run_statement = $connection_pdo->prepare($sql_temp);
$run_statement->execute();
execute_checkup($run_statement->errorInfo(), "creating information_history table");

// create site info table
$sql_temp = "CREATE TABLE IF NOT EXISTS $database.siteinfo (`key` VARCHAR(100), `value` TEXT, timestamp TIMESTAMP, PRIMARY KEY (`key`)) DEFAULT CHARSET=utf8mb4;";
$run_statement = $connection_pdo->prepare($sql_temp);
$run_statement->execute();
execute_checkup($run_statement->errorInfo(), "creating siteinfo table");

// select users from table and if it is empty then create a user
$sql_temp = "SELECT * FROM $database.users";
$login = 0;
foreach ($connection_pdo->query($sql_temp) as $row):
	$login = 1;
	endforeach;

if ($login == 0):
	echo "<hr>";
	echo "<h1>Create login</h1>";
	echo "<form action='' method='post'>";
	echo "<input type='email' name='email' placeholder='email' required><br>";
	echo "<input type='password' name='password1' placeholder='password' required><br>";
	echo "<input type='password' name='password2' placeholder='password' required><br>";
	echo "<input type='submit' name='submit' value='create'>";
	echo "</form>";
	exit; endif; ?>

<hr>

<h1>Configure Apache</h1>

1) Run this command: <i>sudo a2enmod rewrite</i>
2) Locate your Apache config file, usually in /etc/apache2/sites-available/
3) Update your Apache config file by adding the follow chunk at the bottom outside virtualhosts,
<i><Directory /var/www/[DIRECTORY]/>
RewriteEngine on
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule . /index.php [L]
</Directory></i>
4) Run this command to restart Apache: <i>sudo service apache2 restart</i>

</pre>
