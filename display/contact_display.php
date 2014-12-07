<?php
    include "general_display_function.php";
    class contact_display {
    	var $hostname;
		var $username;
		var $password;
		var $db;
		var $current_language;
	
		function SetConfig($host, $user, $pass, $db, $current_language)
    	{
        	$this->hostname = $host;
			$this->username = $user;
			$this->password = $pass;
			$this->db = $db;
			$this->$current_language = $current_language;
 	   }

 	   /* Helper function thats displays product categories */
		function display_product_categories() {
			display_categories($this->hostname,$this->username,$this->password,$this->db,$this->current_language);
        }
    }
?>