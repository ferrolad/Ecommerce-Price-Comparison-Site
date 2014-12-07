<?PHP
require_once("DBConnection.php");
require_once("database_config.php");

$categoryDB = new DBCategory();
$allproductDB = new DBAllProduct();

//Provide config setting
$categoryDB->SetConfig($default_hostname, $default_username, $default_password, $default_db);
$allproductDB->SetConfig($default_hostname, $default_username, $default_password, $default_db);
?>