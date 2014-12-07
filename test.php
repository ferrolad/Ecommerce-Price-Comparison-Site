<?php
$previous = 0.01;
for ($i = 2; $i < 255; $i++)
{
	$previous  = 1.02 * $previous;
}
echo $previous;
?>