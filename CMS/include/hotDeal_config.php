<?PHP
require_once("DBConnection.php");
require_once("database_config.php");

$hotDealsDB = new DBHotDeals();

//Provide config setting
$hotDealsDB->SetConfig($default_hostname, $default_username, $default_password, $default_db);

$hotDealsHistoryDB = new DBHotDealsHistory();

//Provide config setting
$hotDealsHistoryDB->SetConfig($default_hostname, $default_username, $default_password, $default_db);
?>