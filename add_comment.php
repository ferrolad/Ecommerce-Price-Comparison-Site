<html>
<head>
<meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
</head>
<body>
<?php
include './CMS/include/comment_config.php';

$id = 0;
$website = "";
$name = "";
$rating = 0;
$title = "";
$review = "";
$date = date("Y-m-d H:i:s");
$status = "accepted";
$param = $_POST["param"];
/* Check id */
if (isset($_POST["id"]) && $_POST["id"] <> "") {
	$id = $_POST["id"];
	/* Check website */
	if (isset($_POST["website"]) && $_POST["website"] <> "") {
		$website = $_POST["website"];
		/* Check name */
		if (isset($_POST["name"]) && $_POST["name"] <> "") {
			$name =  $_POST["name"];
			/* Check rating */
			if (isset($_POST["rating"]) && $_POST["rating"] <> "") {
				$rating = $_POST["rating"];
				/* Check title */
				if (isset($_POST["title"]) && $_POST["title"] <> "") {
					$title = $_POST["title"];
					/* Check review */
					if (isset($_POST["review"]) && $_POST["review"] <> "") {
						$review = $_POST["review"];
						$commentDB->add(strip_tags($name), strip_tags($id), strip_tags($website), strip_tags($rating), strip_tags($title), strip_tags($review), strip_tags($date), strip_tags($status);
					}
				}
			}
		}
	}
}
?>
<script>
   	window.location = <?php echo "'$param.html'"; ?>;
</script>
</body>
</html>


