<?PHP
require_once("DBConnection.php");
require_once("database_config.php");

$newProductsDB = new DBNewProducts();

//Provide config setting
$newProductsDB->SetConfig($default_hostname, $default_username, $default_password, $default_db);

$newProductsHistoryDB = new DBNewProductsHistory();

//Provide config setting
$newProductsHistoryDB->SetConfig($default_hostname, $default_username, $default_password, $default_db);
?>