<?php
include "include/database_config.php";
include "include/DBConnection.php";

if (isset($_GET["vote"]) && is_numeric($_GET["vote"]) && isset($_GET["id"]) && is_numeric($_GET["id"]) && isset($_GET["website"]))
{
	$vote = strip_tags($_GET["vote"]);
	$id = strip_tags($_GET["id"]);
	$website = strip_tags($_GET["website"]);
	if ($vote >= 1 && $vote <= 5)
	{
		$commentDB = new DBComment();
		$commentDB->SetConfig($default_hostname, $default_username, $default_password, $default_db);
		$numRatings = $commentDB->recordVote($vote, $id, $website);

		echo $numRatings;
	}
}
?>