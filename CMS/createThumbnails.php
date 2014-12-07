<?php
require_once("./include/membersite_config.php");
if(!$fgmembersite->CheckLogin())
{
    $fgmembersite->RedirectToURL("login.php");
    exit;
}
?>

<html>
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
</head>
<body>
	<?php
	include "include/category_config.php";
	set_time_limit(1000);

	/* Function that checks whether thumbnail exists or not */
	function thumbnail_exist($categoryDB, $path, $id, $website, $order) {
		/* Check if the file exists or not */
		if (!file_exists("..".$path."_thumbnail.jpg")) {
			echo ".".$path."_thumbnail.jpg<br/>";
			return false;
		}

		/* Check if the database contains the information about the thumbnail or not */
		if (!$categoryDB->check_thumbnail_existed($id, $website, $order)) {
			return false;
		}

		return true;
	}

	/* Function to create thumbnails image */
	function create_thumbnail($categoryDB, $path, $id, $website, $order) {
		/* Resize the image */
		// The file
		$filename = "..".$path.".jpg";
		// Get new dimensions
		list($width, $height) = getimagesize($filename);
		// Resample
		$image_p = imagecreatetruecolor(44, 45);
		$image = imagecreatefromjpeg($filename);
		/* If file is not jpeg, try png */
		if ($image == false) {
			$image = imagecreatefrompng($filename);
		}
		imagecopyresampled($image_p, $image, 0, 0, 0, 0, 44, 45, $width, $height);
		//Write the resized image to disk
		imagejpeg($image_p,"..".$path."_thumbnail.jpg",100);
		imagedestroy($image_p);

		/* If the thumbnail created is valid, update it in the database*/
		if (filesize("..".$path."_thumbnail.jpg") > 1024) {
			echo "<img src = \"/img/$website/current$order/".$id."_thumbnail.jpg\">";
			$categoryDB->update_thumbnail("/img/$website/current$order/".$id."_thumbnail.jpg", $id, $website, $order);
		}
	}

	/* Get all the images of all the products */
	$images = $categoryDB->get_images();

	/* Iterate through the list of products images */
	for ($i = 0; $i < sizeof($images); $i++) {
		$current_image_set = $images[$i];

		/* Iterate through the current set of images*/
		for ($j = 1; $j <= 7; $j++) {
			/* Check if the image is valid or not*/
			if ($current_image_set["newimage".$j] <> "" && file_exists("..".$current_image_set["newimage".$j]) && filesize("..".$current_image_set["newimage".$j]) > 3*1024) {
				/* If it is, create a thumbnail if the thumbnail hasn't existed already */
				$newname = $current_image_set["newimage".$j];
				$newname = substr($newname, 0 , strrpos($newname, "."));

				/* Check if the thumbnail exists or not*/
				if (!thumbnail_exist($categoryDB, $newname, $current_image_set["id"], $current_image_set["website"], $j)) {
					/* if not create the thumbnail */
					create_thumbnail($categoryDB, $newname, $current_image_set["id"], $current_image_set["website"], $j);
				}
			}
		}
	}
	?>
<<script>
	window.location = 'category.php';
</script>
</body>
</html>