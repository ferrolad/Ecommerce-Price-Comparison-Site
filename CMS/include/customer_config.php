<?PHP
require_once("DBConnection.php");
require_once("database_config.php");

$customerDB = new DBCustomer();

//Provide config setting
$customerDB->SetConfig($default_hostname, $default_username, $default_password, $default_db);
?>