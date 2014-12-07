<?PHP
require_once("DBConnection.php");
require_once("database_config.php");

$adsDB = new DBAds();

//Provide config setting
$adsDB->SetConfig($default_hostname, $default_username, $default_password, $default_db);

$adsHistoryDB = new DBAdsHistory();

//Provide config setting
$adsHistoryDB->SetConfig($default_hostname, $default_username, $default_password, $default_db);
?>