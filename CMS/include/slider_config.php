<?PHP
require_once("DBConnection.php");
require_once("database_config.php");

$sliderDB = new DBSlider();

//Provide config setting
$sliderDB->SetConfig($default_hostname, $default_username, $default_password, $default_db);

$sliderHistoryDB = new DBSliderHistory();

//Provide config setting
$sliderHistoryDB->SetConfig($default_hostname, $default_username, $default_password, $default_db);
?>