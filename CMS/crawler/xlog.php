<?php

class XLog
{
	public static function record($message, 
				  $echoOut = true,
				  $writeToFile = true,
				  $truncate = false,
				  $truncateLength = 200)
	{
		$today = date("d-m-y", time());
		$current_hour = date("h", time());

		$time = date("d-m-y h:m:s", time());

		// Truncate message if the truncate flag is set
		$fullmessage = $message;
		if ($truncate)
		{
			$message = XLog::truncate_string($message, $truncateLength);
		}

		// Message is for echo out, full message is to write to log file
		$message = $time." ".$message."\n";
		$fullmessage = $time." ".str_replace("\n", "", $fullmessage)."\n";

		// Echo out the message if the echoOut flag is set
		if ($echoOut)
		{
			echo $message."<br/>";
		}

		// Write the message to log file if the writeToFile flag is set
		if ($writeToFile)
		{
			//$dir = dirname(__FILE__) ."/log/".$today;
			$dir = "./log/".$today; 
			if (!file_exists($dir))
			{
				//mkdir("./log/".$today);
				mkdir($dir, 755, true);
			}

			$filename = $dir."/".$current_hour.".log";
			file_put_contents($filename, $fullmessage, FILE_APPEND | LOCK_EX);
		}
	}

	public static function truncate_string($string, $truncateLength)
	{
		$length = strlen($string);
		if ($length > $truncateLength) 
		{
			$string = substr($string, 0, $truncateLength);
			$string = substr($string, 0, strrpos($string, " "));
			$string .= ".........................................................................................";
		}
		return $string;
	}
}
?>