<?PHP
require_once("DBConnection.php");
require_once("database_config.php");

$commentDB = new DBComment();

//Provide config setting
$commentDB->SetConfig($default_hostname, $default_username, $default_password, $default_db);
?>