<?php

class language {
	var $current_lang;

	function SetConfig($lang)
	{
		$this->current_lang = $lang;
	} 

	function current_language()
	{
		return $this->current_lang;
	}
}

$lang = new language();
$lang->SetConfig("en");
//$lang->SetConfig("vi");

?>